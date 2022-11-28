<?php

namespace SB\Traits;

use Bitrix\Main\Context;
use Bitrix\Main\Loader;
use Bitrix\Main\LoaderException;

/**
 * Trait Bitrix
 * Проверка на существование свойтв у классов
 * @package SB
 */
trait Bitrix
{
    /**
     * Bitrix констуктор класса
     * @param array $idList
     * @throws LoaderException
     */
    protected function loadModules(array $idList = [])
    {
        try {
            foreach ($idList as $id) {
                Loader::includeModule($id);
            }
        } catch (LoaderException $loaderException) {
            throw $loaderException;
        }
    }

    public function getSiteId(): string
    {
        return Context::getCurrent()->getSite();
    }
}