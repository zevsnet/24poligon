<?
// подключение служебной части пролога
use SB\Site\General;

require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/prolog_before.php");
//\SB\Site\General::addLink2saleSection(['NAME_SECTION_RU' => 'Новинки', 'CODE_SECTION' => 'news_product', 'CODE_PROP' => 'NOVINKA', 'CODE_FIND_PROP' => 'PROPERTY_NOVINKA'],'Новинка');
//\SB\Site\General::addLink2saleSection(['NAME_SECTION_RU' => 'Коллекция весна 2020', 'CODE_SECTION' => 'kollektion_2020', 'CODE_PROP' => 'KOLLEKTSIYA_SEZON', 'CODE_FIND_PROP' => 'PROPERTY_KOLLEKTSIYA_SEZON'],'весна');
\_::d('finish');
