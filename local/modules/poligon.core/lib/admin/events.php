<?php

namespace Poligon\Core\Admin;

use Bitrix\Sale\Order;
use CJSCore;
use Poligon\Core\User\Helper;
use SB\Site\General;

class Events
{
    /**
     * Подлючаемся к событиям Магазина
     */
    static public function addEventHandlers()
    {

//        AddEventHandler(
//            'main',
//            'OnAdminTabControlBegin',
//           [__CLASS__,'seoEditor']
//        );
    }
    function seoEditor() {
        global $APPLICATION;
        CJSCore::Init(['jquery2']);
        \Bitrix\Main\Page\Asset::getInstance()->addJs('/local/modules/poligon.core/scripts/edits.js');
        \Bitrix\Main\Page\Asset::getInstance()->addString('<script src="https://cdnjs.cloudflare.com/ajax/libs/Trumbowyg/2.25.1/trumbowyg.min.js"></script>');
        \Bitrix\Main\Page\Asset::getInstance()->addString('<script src="https://cdnjs.cloudflare.com/ajax/libs/Trumbowyg/2.25.1/langs/ru.min.js"></script>');
        \Bitrix\Main\Page\Asset::getInstance()->addString('<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/Trumbowyg/2.25.1/ui/trumbowyg.min.css">');
    }
}