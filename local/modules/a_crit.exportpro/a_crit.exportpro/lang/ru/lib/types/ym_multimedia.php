<?
$MESS["ACRIT_EXPORTPRO_MARKET_MULTIMEDIA_NAME"] = "Музыкальная и видео продукция (ym_multimedia)";
$MESS["ACRIT_EXPORTPRO_MARKET_MULTIMEDIA_FIELD_ID"] = "Идентификатор торгового предложения";
$MESS["ACRIT_EXPORTPRO_MARKET_MULTIMEDIA_FIELD_BID"] = "Размер ставки на остальных местах размещения (кроме карточки модели).";
$MESS["ACRIT_EXPORTPRO_MARKET_MULTIMEDIA_FIELD_CBID"] = "Размер ставки для карточки модели";
$MESS["ACRIT_EXPORTPRO_MARKET_MULTIMEDIA_FIELD_FEE"] = "Размер комиссии на товарное предложение, участвующее в программе \"Заказ на Маркете\"";
$MESS["ACRIT_EXPORTPRO_MARKET_MULTIMEDIA_FIELD_AVAILABLE"] = "Cтатус доступности товара";
$MESS["ACRIT_EXPORTPRO_MARKET_MULTIMEDIA_FIELD_URL"] = "URL страницы товара.<br/>Максимальная длина URL — 512 символов.<br/>Необязательный элемент для магазинов-салонов";
$MESS["ACRIT_EXPORTPRO_MARKET_MULTIMEDIA_FIELD_PRICE"] = "Цена, по которой данный товар можно приобрести.<br/><b>Обязательный элемент</b>";
$MESS["ACRIT_EXPORTPRO_MARKET_MULTIMEDIA_FIELD_VAT"] = "Значения VAT:<br/><br/>
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
$MESS["ACRIT_EXPORTPRO_MARKET_MULTIMEDIA_FIELD_CURRENCY"] = "Идентификатор валюты товара (RUR, USD, UAH, KZT).<br/><b>Обязательный элемент</b>";
$MESS["ACRIT_EXPORTPRO_MARKET_MULTIMEDIA_FIELD_CATEGORY"] = "Идентификатор категории товара.<br/>Товарное предложение может принадлежать только одной категории.<br/><b>Обязательный элемент</b>";
$MESS["ACRIT_EXPORTPRO_MARKET_MULTIMEDIA_FIELD_PICTURE"] = "Ссылка на картинку соответствующего товарного предложения";
$MESS["ACRIT_EXPORTPRO_MARKET_MULTIMEDIA_FIELD_DESCRIPTION"] = "Описание товара.<br/>Длина текста не более 175 символов (не включая знаки препинания),<br/> запрещено использовать HTML-теги <br/>(информация внутри тегов публиковаться не будет)";
$MESS["ACRIT_EXPORTPRO_MARKET_MULTIMEDIA_FIELD_AGE"] = "Возрастная категория товара";
$MESS["ACRIT_EXPORTPRO_MARKET_MULTIMEDIA_FIELD_STORE"] = "Покупка соответствующего товара в розничном магазине<br/>Возможные значения: true, false";
$MESS["ACRIT_EXPORTPRO_MARKET_MULTIMEDIA_FIELD_PICKUP"] = "Возможность зарезервировать выбранный товар и забрать его самостоятельно<br/>Возможные значения: true, false";
$MESS["ACRIT_EXPORTPRO_MARKET_MULTIMEDIA_FIELD_DELIVERY"] = "Возможность доставки<br/>Возможные значения: true, false";
$MESS["ACRIT_EXPORTPRO_MARKET_MULTIMEDIA_FIELD_BARCODE"] = "Штрихкод товара, указанный производителем";
$MESS["ACRIT_EXPORTPRO_MARKET_MULTIMEDIA_FIELD_ARTIST"] = "Исполнитель";
$MESS["ACRIT_EXPORTPRO_MARKET_MULTIMEDIA_FIELD_TITLE"] = "Название фильма.<br/><b>Обязательный элемент</b>";
$MESS["ACRIT_EXPORTPRO_MARKET_MULTIMEDIA_FIELD_MEDIA"] = "Носитель";
$MESS["ACRIT_EXPORTPRO_MARKET_MULTIMEDIA_FIELD_STARRING"] = "Актеры";
$MESS["ACRIT_EXPORTPRO_MARKET_MULTIMEDIA_FIELD_DIRECTOR"] = "Режиссер";
$MESS["ACRIT_EXPORTPRO_MARKET_MULTIMEDIA_FIELD_ORIGINALNAME"] = "Оригинальное название";
$MESS["ACRIT_EXPORTPRO_MARKET_MULTIMEDIA_FIELD_COUNTRY"] = "Актеры";
$MESS["ACRIT_EXPORTPRO_MARKET_MULTIMEDIA_FIELD_UTM_SOURCE"] = "UTM метка: рекламная площадка";
$MESS["ACRIT_EXPORTPRO_MARKET_MULTIMEDIA_FIELD_UTM_SOURCE_VALUE"] = "cpc_yandex_market";
$MESS["ACRIT_EXPORTPRO_MARKET_MULTIMEDIA_FIELD_UTM_MEDIUM"] = "UTM метка: тип рекламы";
$MESS["ACRIT_EXPORTPRO_MARKET_MULTIMEDIA_FIELD_UTM_MEDIUM_VALUE"] = "cpc";
$MESS["ACRIT_EXPORTPRO_MARKET_MULTIMEDIA_FIELD_UTM_TERM"] = "UTM метка: ключевая фраза";
$MESS["ACRIT_EXPORTPRO_MARKET_MULTIMEDIA_FIELD_UTM_CONTENT"] = "UTM метка: контейнер для дополнительной информации";
$MESS["ACRIT_EXPORTPRO_MARKET_MULTIMEDIA_FIELD_UTM_CAMPAIGN"] = "UTM метка: название рекламной кампании";
$MESS["ACRIT_EXPORTPRO_TYPE_MARKET_MULTIMEDIA_PORTAL_REQUIREMENTS"] = "https://yandex.ru/support/partnermarket/export/music-video.xml";
$MESS["ACRIT_EXPORTPRO_TYPE_MARKET_MULTIMEDIA_PORTAL_VALIDATOR"] = "https://webmaster.yandex.ru/tools/xml-validator/";
$MESS["ACRIT_EXPORTPRO_TYPE_MARKET_MULTIMEDIA_EXAMPLE"] = "
<offer id=\"12345\" type=\"artist.title\" available=\"true\" bid=\"11\">
    <url>http://best.seller.ru/product_page.asp?pid=12946</url>
    <price>450</price>
    <vat>VAT_10</vat>
    <currencyId>USD</currencyId>
    <categoryId>2</categoryId>
    <picture>http://best.seller.ru/product_page.asp?pid=14345.jpg</picture>
    <store>false</store>
    <pickup>false</pickup>
    <delivery>true</delivery>
    <artist>Pink Floyd</artist>
    <title>Dark Side Of The Moon, Platinum Disc</title>
    <year>1999</year>
    <media>CD</media>
    <description>Dark Side Of The Moon, поставивший мир на уши
    невиданным сочетанием звуков, — это всего-навсего девять
    треков, и даже не все они писались специально для альбома.
    Порывшись по сусекам, участники Pink Floyd мудро сделали
    новое из хорошо забытого старого — песен, которые
    почему-либо не пошли в дело или остались незаконченными.
    Одним из источников вдохновения стали саундтреки
    для кинофильмов, которые группа производила в больших количествах.</description>
    <age unit=\"year\">18</age>
    <barcode>2345678901234</barcode>
</offer>
";
?>