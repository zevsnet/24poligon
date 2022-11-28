<?php
/**
 * Created by PhpStorm.
 * User: dnkolosov
 * Date: 20.02.2018
 * Time: 15:01
 * @author Denis Kolosov <kdnn@mail.ru>
 */

namespace SB\Traits;
use SB\Util\CacheEngine;

/**
 * Траит для реализации кэширования методов, реализуется путём вызова любого метода с добавлением 'Cache' в названии метода.
 *
 * Через phpDoc можно указать время кэширования как метода, так и всего класса отвечает 'cache_time' (если у метода нет 'cache_time', то поиск производится в классе)
 *
 * Если необходимо указать дополнительные параметры для кэша (разные поддомены и т.п.) можно у метода указать phpDoc 'cache_addition' с именем метода для вызова
 *
 * !!!ВНИМАНИЕ!!! не возвращайте из метода указаного в 'cache_addition' динамические данные, типа функции 'time()', это приведёт к постоянному созданию нового кэша
 *
 * Trait Cache
 * @package SB
 * @example Traits/CacheClass.php 2 пример класса для реализации
 * @example Traits/Cache.php 2 пример работы с траитом
 */
trait Cache
{
    /**
     * скидывает кэш функции с определёнными параметрами
     * @param $functionName
     * @param array $args
     * @throws \ReflectionException
     */
    public static function cleanTraitFuncArgsCache($functionName, array $args = [])
    {
        static::getTraitCache()->cleanFuncArgsCache($functionName, $args);
    }

    /**
     * скидывает кэш всей функции
     * @param string $functionName
     * @throws \ReflectionException
     */
    public static function cleanTraitFuncCache(string $functionName)
    {
        static::getTraitCache()->cleanFuncCache($functionName);
    }

    /**
     * скидывает кэш класса
     * @throws \ReflectionException
     */
    public static function cleanTraitCache()
    {
        static::getTraitCache()->cleanCache();
    }

    /**
     * @return CacheEngine
     * @throws \ReflectionException
     */
    public static function getTraitCache(): CacheEngine
    {
        return CacheEngine::getInstance(static::class);
    }

    /**
     * @param $name
     * @param $arguments
     * @return mixed|null
     * @throws \BadMethodCallException
     * @throws \ReflectionException
     */
    public function __call($name, $arguments)
    {
        return static::getTraitCache()->initialize($name, $arguments, $this);
    }

    /**
     * @param $name
     * @param $arguments
     * @return mixed|null
     * @throws \BadMethodCallException
     * @throws \ReflectionException
     */
    public static function __callStatic($name, $arguments)
    {
        return static::getTraitCache()->initialize($name, $arguments);
    }
}