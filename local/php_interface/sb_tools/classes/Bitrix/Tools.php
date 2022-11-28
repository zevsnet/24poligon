<?php

namespace SB\Bitrix;

use Bitrix\Main\Application;
use Bitrix\Main\Web\Uri;
use SB\Bitrix\Tools\RequestCheck;

/**
 * Class Common
 * @package SB\Bitrix
 */
class Tools
{
    /**
     * проверка на нахождение в определенном пути
     *
     * @param string $uri - адрес для проверки
     * @param RequestCheck|null $checker - для проверки параметров запроса (get/post/cookie)
     * @return bool
     * @throws \Bitrix\Main\ArgumentException
     * @throws \Bitrix\Main\SystemException
     *
     * @example Bitrix\Tools\inFilePath.php 2 Проверка на нахождение на главной странице
     * @example Bitrix\Tools\inFilePath2.php 2 Проверка на нахождение в поиске
     */
    public static function inFilePath(string $uri, RequestCheck $checker = null): bool
    {
        $request = Application::getInstance()->getContext()->getRequest();
        $requestUri = $request->getRequestedPage();

        $obUri = new Uri($uri);

        $currentUri = $obUri->getPath();

        if (strpos($requestUri, $currentUri) !== 0) {
            return false;
        }

        if($checker)
        {
            return $checker->check();
        }

        return true;
    }


    /**
     * проверка на нахождение на главной странице
     *
     * @return bool
     * @see inFilePath
     * @throws \Bitrix\Main\ArgumentException
     * @throws \Bitrix\Main\SystemException
     */
    public static function isHome(): bool
    {
        return self::inFilePath('/index.php');
    }

    /**
     * Проверяет проходит ли обмен с 1с
     *
     * @return bool
     */
    public static function isCMLImport(): bool
    {
        static $isCMLImport;

        if ($isCMLImport === null) {
            $isCMLImport = false;
            $arDebug = debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS);

            foreach ($arDebug as $arItem) {
                if ($arItem['class'] === 'CIBlockCMLImport') {
                    $isCMLImport = true;
                    break;
                }
            }
        }

        return $isCMLImport;
    }

    /**
     * возвращает серверное имя, берётся из главного модуля "URL сервера", если не заполнен, то берёт из $_SERVER
     *
     * @uses isAdminSection
     * @return string
     * @throws \Bitrix\Main\SystemException
     */
    public static function getServerName(): string
    {
        $context = Application::getInstance()->getContext();
        if(!empty(SITE_SERVER_NAME) && !static::isAdminSection()) {
            return SITE_SERVER_NAME;
        }
        return $context->getServer()->getServerName();
    }

    /**
     * флаг нахождения в админке
     * @return bool
     */
    public static function isAdminSection(): bool
    {
        return \defined('ADMIN_SECTION');
    }

    /**
     * возвращает поддомен сайта
     * @uses getServerName
     * @param bool $removeWWW - флаг удаления 'www'
     * @return string
     * @throws \Bitrix\Main\SystemException
     */
    public static function getSubDomain(bool $removeWWW = true): string
    {
        $arSearch = [];
        if($removeWWW) {
            $arSearch[] = 'www.';
        }
        $arSearch[] = static::getServerName();

        $context = Application::getInstance()->getContext();

        return trim(str_replace($arSearch, '', $context->getServer()->getServerName()), '.');
    }
}