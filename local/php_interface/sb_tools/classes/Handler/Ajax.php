<?php

namespace SB\Handler;

use Bitrix\Main\HttpRequest;
use SB\Model\Ajax\Response as AjaxResponse;

/**
 * Класс для обработки Ajax-запросов
 * Class Ajax
 * @package SB\Handler
 */
abstract class Ajax
{
    /** @var AjaxResponse результат*/
    protected $result;

    /** @var HttpRequest запрос*/
    protected $request;

    /** @var array параметры (get/post/cookie) */
    protected $params;

    /**
     * Ajax constructor.
     * @param HttpRequest|null $request
     * @param AjaxResponse|null $result
     */
    public function __construct(HttpRequest $request = null, AjaxResponse $result = null)
    {
        $this->request = $request;
        $this->result = $result ?? new AjaxResponse();
        $this->params = null === $request ? [] : $request->getQueryList()->toArray() + $request->getPostList()->toArray() + $request->getCookieList()->toArray();
    }

    /**
     * Объект запроса
     * @return HttpRequest
     */
    public function getRequest(): HttpRequest
    {
        return $this->request;
    }

    /**
     * Объект результата
     * @return AjaxResponse
     */
    public function getResult(): AjaxResponse
    {
        return $this->result;
    }

    /**
     * Параметры (get,post,cookie)
     * @return array
     */
    public function getParams(): array
    {
        return $this->params;
    }
}