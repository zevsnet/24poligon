<?
$MESS["ACRIT_EXPORTPRO_MARKET_DYNAMIC_SIMPLE_NAME"] = "Упрощенное описание (ym_dynamic_simple)";
$MESS["ACRIT_EXPORTPRO_MARKET_DYNAMIC_SIMPLE_FIELD_ID"] = "Идентификатор торгового предложения";
$MESS["ACRIT_EXPORTPRO_MARKET_DYNAMIC_SIMPLE_FIELD_AVAILABLE"] = "Cтатус доступности товара";
$MESS["ACRIT_EXPORTPRO_MARKET_DYNAMIC_SIMPLE_FIELD_URL"] = "URL страницы товара.<br/>Максимальная длина URL — 512 символов.<br/>Необязательный элемент для магазинов-салонов";
$MESS["ACRIT_EXPORTPRO_MARKET_DYNAMIC_SIMPLE_FIELD_PRICE"] = "Цена, по которой данный товар можно приобрести.<br/><b>Обязательный элемент</b>";
$MESS["ACRIT_EXPORTPRO_MARKET_DYNAMIC_SIMPLE_FIELD_VAT"] = "Значения VAT:<br/><br/>
18%. Основная ставка НДС.<br/>
Формат для прайс-листа: <vat>1</vat> или <vat>VAT_18</vat><br/><br/>
10%. Применяется при реализации отдельных категорий товаров и услуг, например, при реализации ряда продовольственных товаров, товаров для детей, некоторых медицинских товаров, печатных изданий и т.п.<br/>
Формат для прайс-листа: <vat>2</vat> или <vat>VAT_10</vat><br/><br/>
18/118. Расчётная ставка НДС, которая применяется при получении предоплаты в счет предстоящих поставок товаров и оказания услуг, реализация которых облагается по ставке НДС 18%.<br/>
Формат для прайс-листа: <vat>3</vat> или <vat>VAT_18_118</vat><br/><br/>
10/110. Расчётная ставка НДС, которая применяется при получении предоплаты в счет предстоящих поставок товаров и оказания услуг, реализация которых облагается по ставке НДС 10%.<br/>
Формат для прайс-листа: <vat>4</vat> или <vat>VAT_10_110</vat><br/><br/>
НДС 0%. Применяется, например, при реализации товаров, вывезенных в таможенной процедуре экспорта; оказании услуг по международной перевозке товаров.<br/>
Формат для прайс-листа: <vat>5</vat> или <vat>VAT_0</vat><br/><br/>
НДС не облагается. Применяется при реализации отдельных категорий товаров и услуг, например, ряда медицинских товаров и услуг, исключительных прав на программы для ЭВМ, базы данных и т.п., а также в случае применения специальных режимов налогообложения.<br/>
Формат для прайс-листа: <vat>6</vat> или <vat>NO_VAT</vat><br/><br/>
Обратите внимание, что передать ставку Маркету можно в любом формате. Если вы укажете 18% или 10% для чека, который будет сформирован при оплате товара на Маркете, мы автоматически преобразуем ставку в 18/118 или в 10/110, чтобы соблюсти требование налоговой службы.
";
$MESS["ACRIT_EXPORTPRO_MARKET_DYNAMIC_SIMPLE_FIELD_OLDPRICE"] = "Старая цена на товар, которая обязательно должна быть выше новой цены (<price>). Параметр <oldprice> необходим для автоматического расчета скидки на товар";
$MESS["ACRIT_EXPORTPRO_MARKET_DYNAMIC_SIMPLE_FIELD_CURRENCY"] = "Идентификатор валюты товара (RUR, USD, UAH, KZT).<br/><b>Обязательный элемент</b>";
$MESS["ACRIT_EXPORTPRO_MARKET_DYNAMIC_SIMPLE_FIELD_CATEGORY"] = "Идентификатор категории товара.<br/>Товарное предложение может принадлежать только одной категории.<br/><b>Обязательный элемент</b>";
$MESS["ACRIT_EXPORTPRO_MARKET_DYNAMIC_SIMPLE_FIELD_GROUPID"] = "Элемент используется в описаниях всех предложений, которые являются вариациями одной модели, при этом элемент должен иметь одинаковое значение. Значение должно быть целым числом, максимум 9 разрядов.<br/>
Является атрибутом элемента offer.<br/><br/><b>Внимание!</b><br/>Элемент используется только в формате YML и только в категориях Одежда, обувь и аксессуары, Мебель, Косметика, парфюмерия и уход, Детские товары, Аксессуары для портативной электроники.";
$MESS["ACRIT_EXPORTPRO_MARKET_DYNAMIC_SIMPLE_FIELD_PICTURE"] = "Ссылка на картинку соответствующего товарного предложения";
$MESS["ACRIT_EXPORTPRO_MARKET_DYNAMIC_SIMPLE_FIELD_STORE"] = "Покупка соответствующего товара в розничном магазине<br/>Возможные значения: true, false";
$MESS["ACRIT_EXPORTPRO_MARKET_DYNAMIC_SIMPLE_FIELD_PICKUP"] = "Возможность зарезервировать выбранный товар и забрать его самостоятельно<br/>Возможные значения: true, false";
$MESS["ACRIT_EXPORTPRO_MARKET_DYNAMIC_SIMPLE_FIELD_DELIVERY"] = "Возможность доставки<br/>Возможные значения: true, false";
$MESS["ACRIT_EXPORTPRO_MARKET_DYNAMIC_SIMPLE_FIELD_NAME"] = "Наименование товарного предложения.<br/><b>Обязательный элемент</b>";
$MESS["ACRIT_EXPORTPRO_MARKET_DYNAMIC_SIMPLE_FIELD_VENDOR"] = "Производитель. Не отображается в названии предложения";
$MESS["ACRIT_EXPORTPRO_MARKET_DYNAMIC_SIMPLE_FIELD_VENDORCODE"] = "Код товара (указывается код производителя)";
$MESS["ACRIT_EXPORTPRO_MARKET_DYNAMIC_SIMPLE_FIELD_DESCRIPTION"] = "Описание товара.<br/>Длина текста не более 175 символов (не включая знаки препинания),<br/> запрещено использовать HTML-теги <br/>(информация внутри тегов публиковаться не будет)";
$MESS["ACRIT_EXPORTPRO_MARKET_DYNAMIC_SIMPLE_FIELD_SALESNOTES"] = "Элемент используется для отражения информации<br/>о минимальной сумме заказа, минимальной партии<br/>товара или необходимости предоплаты, а так же для<br/>описания акций, скидок и распродаж.<br/>Допустимая длина текста в элементе — 50 символов";
$MESS["ACRIT_EXPORTPRO_MARKET_DYNAMIC_SIMPLE_FIELD_MANUFACTURERWARRANTY"] = "Гарантия<br/>Возможные значения: true, false";
$MESS["ACRIT_EXPORTPRO_MARKET_DYNAMIC_SIMPLE_FIELD_COUNTRYOFORIGIN"] = "Страны производства товара.<br/> Список стран доступен по адресу:<br/>http://partner.market.yandex.ru/pages/help/Countries.pdf";
$MESS["ACRIT_EXPORTPRO_MARKET_DYNAMIC_SIMPLE_FIELD_AGE"] = "Возрастная категория товара";
$MESS["ACRIT_EXPORTPRO_MARKET_DYNAMIC_SIMPLE_FIELD_UTM_SOURCE"] = "UTM метка: рекламная площадка";
$MESS["ACRIT_EXPORTPRO_MARKET_DYNAMIC_SIMPLE_FIELD_UTM_SOURCE_VALUE"] = "cpc_yandex_market";
$MESS["ACRIT_EXPORTPRO_MARKET_DYNAMIC_SIMPLE_FIELD_UTM_MEDIUM"] = "UTM метка: тип рекламы";
$MESS["ACRIT_EXPORTPRO_MARKET_DYNAMIC_SIMPLE_FIELD_UTM_MEDIUM_VALUE"] = "cpc";
$MESS["ACRIT_EXPORTPRO_MARKET_DYNAMIC_SIMPLE_FIELD_UTM_TERM"] = "UTM метка: ключевая фраза";
$MESS["ACRIT_EXPORTPRO_MARKET_DYNAMIC_SIMPLE_FIELD_UTM_CONTENT"] = "UTM метка: контейнер для дополнительной информации";
$MESS["ACRIT_EXPORTPRO_MARKET_DYNAMIC_SIMPLE_FIELD_UTM_CAMPAIGN"] = "UTM метка: название рекламной кампании";
$MESS["ACRIT_EXPORTPRO_TYPE_MARKET_DYNAMIC_SIMPLE_PORTAL_REQUIREMENTS"] = "https://yandex.ru/support/direct/features/dynamic-text-ads_feed.xml";
$MESS["ACRIT_EXPORTPRO_TYPE_MARKET_DYNAMIC_SIMPLE_PORTAL_VALIDATOR"] = "https://webmaster.yandex.ru/tools/xml-validator/";
$MESS["ACRIT_EXPORTPRO_TYPE_MARKET_DYNAMIC_SIMPLE_EXAMPLE"] = "<offer id=\"0123456\" available=\"true\">
    <url>http://www.надежнаятехника.рф/catalog/element/index.php?from=ya_market&amp;utm_source=ya_market&amp;utm_medium=cpc&amp</url>
    <price>1620.00</price>
    <oldprice>1800.00</oldprice>
    <vat>VAT_10</vat>
    <currencyId>RUB</currencyId>
    <categoryId>19</categoryId>
    <picture>http://89.123.45.678/catalog/photo/19/6.JPG</picture>
    <store>false</store>
    <pickup>true</pickup>
    <delivery>false</delivery>
    <name>Антивирус ESET NOD32 Platinum Edition</name>
    <vendor>Eset</vendor>
    <vendorCode>NOD32-ENA-NS(BOX)-2-1</vendorCode>
    <description>Антивирус ESET NOD32 Platinum Edition - лицензия на 2 года NOD32-ENA-NS(BOX)-2-1</description>
    <sales_notes>Оплата: Наличные, Б/Н, пластиковые карты, кредит</sales_notes>
    <manufacturer_warranty>true</manufacturer_warranty>
    <country_of_origin>Россия</country_of_origin>
    <age unit=\"year\">18</age>
</offer>";
?>