<?php

namespace SB\Util;

use Bitrix\Main\Data\Cache;

/**
 * Class Cache
 * @package SB\Util
 * @see \SB\Traits\Cache
 */
class CacheEngine
{
    const DEFAULT_CACHE_TIME = 86400;
    const CACHE_DIR = 'SB';

    /** @var CacheEngine[] массив объектов инстансов */
    protected static $instances;

    /** @var \ReflectionClass отражение класса */
    protected $classReflection;

    /**
     * Cache constructor.
     * @param string $className
     * @throws \ReflectionException
     */
    protected function __construct(string $className)
    {
        $this->classReflection  = new \ReflectionClass($className);
    }

    /**
     * @param string $className
     * @return static
     * @throws \ReflectionException
     */
    public static function getInstance(string $className)
    {
        if (empty(self::$instances[$className]))
        {
            self::$instances[$className] = new static($className);
        }
        return self::$instances[$className];
    }

    /**
     * @param string $method
     * @param $arguments
     * @param null $object
     * @return mixed|null
     */
    public function initialize(string $method, $arguments, $object = null)
    {
        if(!$pos = static::checkMethod($method)) {
            throw new \BadMethodCallException('Метод не найден');
        }

        $method = substr($method, 0, $pos);
        $reflectionMethod = $this->getReflectionMethod($method);

        $cacheTime = $this->getCacheTime($reflectionMethod);

        $result = null;
        $cacheParams = $this->getCacheParams($method, $reflectionMethod, $arguments);

        $cache = Cache::createInstance();
        $cacheId = md5(serialize($cacheParams));

        global $CACHE_MANAGER;
        if ($cache->startDataCache($cacheTime, $cacheId, $cacheParams['path'])) {

            $CACHE_MANAGER->StartTagCache($cacheParams['path'] . DIRECTORY_SEPARATOR . $cache::getPath($cacheId));
            $CACHE_MANAGER->RegisterTag($cacheId);

            $result = $reflectionMethod->invokeArgs($object, $arguments);

            $CACHE_MANAGER->EndTagCache();
            $cache->endDataCache($result);
        } else {
            $result = $cache->getVars();
        }

        return $result;
    }

    /**
     * скидывает кэш класса
     */
    public function cleanCache()
    {
        Cache::createInstance()->cleanDir($this->getCachePath());
    }

    /**
     * скидывает кэш всей функции
     * @param string $functionName
     */
    public function cleanFuncCache(string $functionName)
    {
        if($pos = static::checkMethod($functionName)) {
            $functionName = static::normalizeMethod($functionName, $pos);
        }

        Cache::createInstance()->cleanDir($this->getCachePath($functionName));
    }

    /**
     * скидывает кэш функции с определёнными параметрами
     * @param $functionName
     * @param array $args
     */
    public function cleanFuncArgsCache(string $functionName, array $args = [])
    {
        if($pos = static::checkMethod($functionName)) {
            $functionName = static::normalizeMethod($functionName, $pos);
        }
        $reflectionMethod = $this->getReflectionMethod($functionName);

        $cacheParams = $this->getCacheParams($functionName, $reflectionMethod, $args);
        $cacheId = md5(serialize($cacheParams));
        global $CACHE_MANAGER;
        $CACHE_MANAGER->ClearByTag($cacheId);
    }

    /**
     * получение времени кэширования
     * @param \ReflectionMethod $reflectionMethod
     * @return int|null
     */
    protected function getCacheTime(\ReflectionMethod $reflectionMethod)
    {
        $cacheTime = null;
        $arDoc = static::parseDocBlock($reflectionMethod->getDocComment());
        if (isset($arDoc['cache_time'])) {
            $cacheTime = (int)$arDoc['cache_time'];
        }

        if (null === $cacheTime) {
            $arDoc = static::parseDocBlock($this->classReflection->getDocComment());
            if (isset($arDoc['cache_time'])) {
                $cacheTime = (int)$arDoc['cache_time'];
            }
        }

        if (null === $cacheTime) {
            $cacheTime = self::DEFAULT_CACHE_TIME;
        }

        return $cacheTime;
    }

    /**
     * получение дополнительных параметров для кэша
     * @param \ReflectionMethod $reflectionMethod
     * @param array $args
     * @return string
     */
    protected function getAdditionParams(\ReflectionMethod $reflectionMethod, array $args = []): string
    {
        $additionString = '';
        $arDoc = static::parseDocBlock($reflectionMethod->getDocComment());
        if (isset($arDoc['cache_addition']) && \is_callable([$this->classReflection->getName(), trim($arDoc['cache_addition'])])) {
            $funcResult = \call_user_func([$this->classReflection->getName(), trim($arDoc['cache_addition'])], $reflectionMethod->getName(), $args);
            $additionString = $funcResult ?: '';
        }
        return $additionString;
    }

    /**
     * получить из метода его рефлекшн
     * @param $method
     * @return \ReflectionMethod
     */
    protected function getReflectionMethod($method): \ReflectionMethod
    {
        if (!$this->classReflection->hasMethod($method)) {
            throw new \BadMethodCallException('метод не найден');
        }

        $reflectionMethod = $this->classReflection->getMethod($method);
        $reflectionMethod->setAccessible(true);
        return $reflectionMethod;
    }

    /**
     * возвращает массив параметров кэша
     * @param $functionName
     * @param \ReflectionMethod $method
     * @param array $args
     * @return array
     */
    protected function getCacheParams($functionName, \ReflectionMethod $method, array $args = []): array
    {
        $args += $this->getDefaultValues($method);

        return [
            'path' => $this->getCachePath($functionName),
            'args' => $args,
            'addition' => $this->getAdditionParams($method, $args)
        ];
    }

    /**
     * собирает значения по умолчанию
     * @param \ReflectionMethod $method
     * @return array
     */
    protected function getDefaultValues(\ReflectionMethod $method): array
    {
        $defaultValues = [];
        foreach ($method->getParameters() as $parameterPosition => $reflectionParameter) {
            if(!$reflectionParameter->isDefaultValueAvailable()) {
                continue;
            }
            $defaultValues[$parameterPosition] = $reflectionParameter->getDefaultValue();
        }
        return $defaultValues;
    }

    /**
     * собирает путь для кэша
     * @param string $functionName
     * @return string
     */
    protected function getCachePath(string $functionName = ''): string
    {
        $arPath = [
            static::CACHE_DIR,
            urlencode($this->classReflection->getName()),
            $functionName
        ];
        return implode(DIRECTORY_SEPARATOR, array_filter($arPath));
    }

    /**
     * рабирает phpdoc
     * @param string $doc
     * @return array
     */
    public static function parseDocBlock(string $doc): array
    {
        $arDoc = [];
        if (preg_match_all("/@([a-zA-Z_]+)\s*([a-zA-Z0-9, ()_].*)$/m", $doc, $matches, PREG_SET_ORDER)) {
            foreach ($matches as $match) {
                $arDoc[$match[1]] = $match[2];
            }
        }
        return $arDoc;
    }

    /**
     * проверяет метод на постфикс 'Cache'
     * @param string $method
     * @return bool|int
     */
    public static function checkMethod(string $method)
    {
        return strrpos($method, 'Cache');
    }

    /**
     * нормализует метод
     * @param string $method
     * @param string $length
     * @return string
     */
    public static function normalizeMethod(string $method, string $length)
    {
        return substr($method, 0, $length);
    }
}