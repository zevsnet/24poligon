<?
$MESS["ACRIT_EXPORTPRO_WIKIMART_SIMPLE_NAME"] = "Упрощенное описание";
$MESS["ACRIT_EXPORTPRO_WIKIMART_SIMPLE_FIELD_ID"] = "Идентификатор торгового предложения";
$MESS["ACRIT_EXPORTPRO_WIKIMART_SIMPLE_FIELD_BID"] = "Основная ставка клика";
$MESS["ACRIT_EXPORTPRO_WIKIMART_SIMPLE_FIELD_AVAILABLE"] = "Cтатус доступности товара";
$MESS["ACRIT_EXPORTPRO_WIKIMART_SIMPLE_FIELD_URL"] = "URL страницы товара.<br/>Максимальная длина URL — 512 символов.<br/>Необязательный элемент для магазинов-салонов";
$MESS["ACRIT_EXPORTPRO_WIKIMART_SIMPLE_FIELD_PRICE"] = "Цена, по которой данный товар можно приобрести.<br/><b>Обязательный элемент</b>";
$MESS["ACRIT_EXPORTPRO_WIKIMART_SIMPLE_FIELD_CURRENCY"] = "Идентификатор валюты товара (RUR, USD, UAH, KZT).<br/><b>Обязательный элемент</b>";
$MESS["ACRIT_EXPORTPRO_WIKIMART_SIMPLE_FIELD_CATEGORY"] = "Идентификатор категории товара.<br/>Товарное предложение может принадлежать только одной категории.<br/><b>Обязательный элемент</b>";
$MESS["ACRIT_EXPORTPRO_WIKIMART_SIMPLE_FIELD_PICTURE"] = "Ссылка на картинку соответствующего товарного предложения";
$MESS["ACRIT_EXPORTPRO_WIKIMART_SIMPLE_FIELD_NAME"] = "Наименование товарного предложения";
$MESS["ACRIT_EXPORTPRO_WIKIMART_SIMPLE_FIELD_DESCRIPTION"] = "Описание товара.<br/>Длина текста не более 175 символов (не включая знаки препинания),<br/> запрещено использовать HTML-теги <br/>(информация внутри тегов публиковаться не будет)";
$MESS["ACRIT_EXPORTPRO_WIKIMART_SIMPLE_FIELD_AGE"] = "Возрастная категория товара";
$MESS["ACRIT_EXPORTPRO_WIKIMART_SIMPLE_FIELD_STORE"] = "Покупка соответствующего товара в розничном магазине<br/>Возможные значения: true, false";
$MESS["ACRIT_EXPORTPRO_WIKIMART_SIMPLE_FIELD_VENDOR"] = "Производитель. Не отображается в названии предложения";
$MESS["ACRIT_EXPORTPRO_WIKIMART_SIMPLE_FIELD_VENDORCODE"] = "Код товара (указывается код производителя)";
$MESS["ACRIT_EXPORTPRO_WIKIMART_SIMPLE_FIELD_ADULT"] = "Элемент обязателен для обозначения товара,<br/> имеющего отношение к удовлетворению сексуальных потребностей";
$MESS["ACRIT_EXPORTPRO_WIKIMART_SIMPLE_FIELD_CPA"] = "<b>Участие товарных предложений в программе «Заказ на Маркете»:</b><br/>
0 — все товары из прайс-листа не участвуют в программе «Заказ на Маркете»;<br/> 
1 — все товары из прайс-листа участвуют в программе «Заказ на Маркете».";
$MESS["ACRIT_EXPORTPRO_WIKIMART_SIMPLE_FIELD_UTM_SOURCE"] = "UTM метка: рекламная площадка";
$MESS["ACRIT_EXPORTPRO_WIKIMART_SIMPLE_FIELD_UTM_SOURCE_VALUE"] = "cpc_yandex_market";
$MESS["ACRIT_EXPORTPRO_WIKIMART_SIMPLE_FIELD_UTM_MEDIUM"] = "UTM метка: тип рекламы";
$MESS["ACRIT_EXPORTPRO_WIKIMART_SIMPLE_FIELD_UTM_MEDIUM_VALUE"] = "cpc";
$MESS["ACRIT_EXPORTPRO_WIKIMART_SIMPLE_FIELD_UTM_TERM"] = "UTM метка: ключевая фраза";
$MESS["ACRIT_EXPORTPRO_WIKIMART_SIMPLE_FIELD_UTM_CONTENT"] = "UTM метка: контейнер для дополнительной информации";
$MESS["ACRIT_EXPORTPRO_WIKIMART_SIMPLE_FIELD_UTM_CAMPAIGN"] = "UTM метка: название рекламной кампании";
$MESS["ACRIT_EXPORTPRO_TYPE_WIKIMART_SIMPLE_PORTAL_REQUIREMENTS"] = "http://img.0cw.ru/docs/Wikimart_common_instruction.pdf?c796e6";
$MESS["ACRIT_EXPORTPRO_TYPE_WIKIMART_SIMPLE_EXAMPLE"] = "
<offer id=\"12346\" available=\"true\" bid=\"21\">
    <url>http://best.seller.ru/product_page.asp?pid=12348</url>
    <price>600</price>
    <currencyId>USD</currencyId>
    <categoryId>6</categoryId>
    <picture>http://best.seller.ru/img/device12345.jpg</picture>
    <picture>http://best.seller.ru/img/device12346.jpg</picture>
    <picture>http://best.seller.ru/img/device12347.jpg</picture>
    <store>false</store>
    <name>Наручные часы Casio A1234567B</name>
    <vendor>Casio</vendor>
    <vendorCode>A1234567B</vendorCode>
    <description>Изящные наручные часы.</description>
    <age unit=\"year\">18</age>
    <cpa>1</cpa>  
</offer>
";
?>