<?php

namespace SB\Router;

use Bitrix\Main\Application;
use Bitrix\Main\Context;
use SB\Exception;
use SB\Handler\Ajax as AjaxHandler;
use \Throwable;
use SB\Model\Ajax\Response;

/**
 * Роутер для Ajax запросов
 * Класс для обработки обычно располагается 'SB\Site\Handler\Ajax\*', данный класс должен наследоваться от '\SB\Handler\Ajax'
 * @see \SB\Handler\Ajax
 * @package SB
 * @example Router/Ajax.php 2
 * @example Router/AjaxRewrite.php 2 пример записи в urlrewrite.php
 * @example Router/AjaxExample.php 2 пример класса для обработки Ajax-запросов, чтобы вызвалась необходимая функция /ajax/pathToClass/functionName/
 */
class Ajax
{
    /** @var Response Объект ответа */
    protected $response;

    /** @var string Имя класса контроллера */
    protected $controllerName;

    /** @var string Имя метода контроллера */
    protected $methodName;

    /** @var array Пространство имен контроллера */
    protected $controllerNamespace = ['SB', 'Site', 'Handler', 'Ajax'];

    /** @var \ReflectionClass|null Объект-обертка контроллера */
    protected $controllerReflection;

    /** @var AjaxHandler|null Объект контроллера */
    protected $controller;

    /**
     * Ajax constructor.
     * @param string $controllerName - Имя класса контроллера
     * @param string $methodName - Имя метода
     * @param array $controllerNamespace - Путь до папки с контроллерами
     * Если $controllerName и $methodName пустые, заполняет из из URL
     */
    public function __construct(string $controllerName = '', string $methodName = '', array $controllerNamespace = [])
    {
        try {
            if ($controllerNamespace) {
                $this->controllerNamespace = $controllerNamespace;
            }
            $this->response = new Response();

            if (empty($controllerName) || empty($methodName)) {
                $arUrlData = $this->getUrlData();
                $this->methodName = array_pop($arUrlData);
                $this->controllerNamespace = array_merge($this->controllerNamespace, $arUrlData);
                $this->controllerName = implode('\\', $this->controllerNamespace);
            } else {
                $this->controllerName = implode('\\', array_merge($this->controllerNamespace, $controllerName));
                $this->methodName = $methodName;
            }

            if (empty($this->controllerName) || empty($this->methodName)) {
                throw new Exception('Не указан контроллер и метод');
            }

            try {
                $this->controllerReflection = new \ReflectionClass($this->controllerName);
            } catch (\Throwable $exception) {
                throw new Exception('Контроллер ' . $this->controllerName . ' не найден');
            }

            if (!$this->controllerReflection->isSubclassOf(AjaxHandler::class)) {
                throw new Exception('Контроллер ' . $this->controllerName . ' не валиден');
            }

            if (!$this->controllerReflection->hasMethod($this->methodName)) {
                throw new Exception('Метод ' . $this->methodName . ' не найден');
            }

            $request = Application::getInstance()->getContext()->getRequest();
            $this->controller = $this->controllerReflection->newInstance($request, $this->response);
        } catch (Throwable $throwable) {
            $this->response->addError($throwable->getMessage());
        }
    }

    /**
     * исполняет метод и возвращает результат
     * @return Response
     */
    public function execute(): Response
    {
        if ($this->response->getStatus() === false) {
            return $this->response;
        }

        try {
            $this->controllerReflection->getMethod($this->methodName)->invoke($this->controller);
        } catch (Throwable $throwable) {
            $this->response->addError($throwable->getMessage());
        }

        return $this->response;
    }

    /**
     * @return AjaxHandler
     */
    public function getController(): AjaxHandler
    {
        return $this->controller;
    }

    protected function getUrlData()
    {
        $realFile = Context::getCurrent()->getRequest()->getScriptFile();
        $realPath = $this->removeIndex($realFile);
        $requestPath = $this->removeIndex(Context::getCurrent()->getRequest()->getRequestedPage());
        $path = str_replace($realPath, '', $requestPath);
        $path = trim($path, DIRECTORY_SEPARATOR);
        return explode(DIRECTORY_SEPARATOR, $path);
    }

    protected function removeIndex($path)
    {
        return str_replace('index.php', '', $path);
    }
}