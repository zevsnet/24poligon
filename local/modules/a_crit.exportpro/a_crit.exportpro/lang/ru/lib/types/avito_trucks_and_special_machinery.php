<?
$MESS["ACRIT_EXPORTPRO_AVITO_TRUCKS_AND_SPECIAL_MACHINERY_NAME"] = "Экспорт в систему авито (Грузовики и спецтехника)";
$MESS["ACRIT_EXPORTPRO_AVITO_TRUCKS_AND_SPECIAL_MACHINERY_FIELD_ID"] = "Уникальный идентификатор объявления<br/>(строка не более 100 символов)<br/><b class='required'>Обязательный элемент</b>";
$MESS["ACRIT_EXPORTPRO_AVITO_TRUCKS_AND_SPECIAL_MACHINERY_FIELD_DATEBEGIN"] = "Дата начала экспозиции объявления";
$MESS["ACRIT_EXPORTPRO_AVITO_TRUCKS_AND_SPECIAL_MACHINERY_FIELD_DATEEND"] = "Дата конца экспозиции объявления";
$MESS["ACRIT_EXPORTPRO_AVITO_TRUCKS_AND_SPECIAL_MACHINERY_FIELD_LISTINGFEE"] = "Вариант платного размещения — одно из значений списка:<br/><br/>Package — размещение объявления осуществляется только при наличии подходящего пакета размещения;<br/>PackageSingle — при наличии подходящего пакета оплата размещения объявления произойдет с него; если нет подходящего пакета, но достаточно денег на кошельке Avito, то произойдет разовое размещение;<br/>Single — только разовое размещение, произойдет при наличии достаточной суммы на кошельке Avito; если есть подходящий пакет размещения, он будет проигнорирован.<br/>Если элемент пуст или отсутствует, то значение по умолчанию — Package.";
$MESS["ACRIT_EXPORTPRO_AVITO_TRUCKS_AND_SPECIAL_MACHINERY_FIELD_ADSTATUS"] = "Платная услуга, которую нужно применить к объявлению — одно из значений списка:<br/><br/>Free — обычное объявление;<br/>Premium — премиум-объявление;<br/>VIP — VIP-объявление;<br/>PushUp — поднятие объявления в поиске;<br/>Highlight — выделение объявления;<br/>TurboSale— применение пакета Турбо-продажа;<br/>QuickSale — применение пакета Быстрая продажа.";
$MESS["ACRIT_EXPORTPRO_AVITO_TRUCKS_AND_SPECIAL_MACHINERY_FIELD_AVITOID"] = "Номер объявления на Avito — целое число.<br/>Если вы разместили объявление вручную, а теперь хотите управлять объявлением с помощью Автозагрузки — укажите здесь номер объявления на сайте Авито.";
$MESS["ACRIT_EXPORTPRO_AVITO_TRUCKS_AND_SPECIAL_MACHINERY_FIELD_ALLOWEMAIL"] = "Возможность написать сообщение по объявлению через сайт — одно из значений списка: Да, Нет. Примечание: значение по умолчанию — Да.";
$MESS["ACRIT_EXPORTPRO_AVITO_TRUCKS_AND_SPECIAL_MACHINERY_FIELD_MANAGERNAME"] = "Имя менеджера, контактного лица компании по данному объявлению — строка не более 40 символов.";
$MESS["ACRIT_EXPORTPRO_AVITO_TRUCKS_AND_SPECIAL_MACHINERY_FIELD_CONTACTPHONE"] = "Контактный телефон — строка, содержащая только один российский номер телефона; должен быть обязательно указан код города или мобильного оператора. Корректные примеры:<br/>+7 (495) 777-10-66,<br/>(81374) 4-55-75,<br/>8 905 207 04 90,<br/>+7 905 2070490,<br/>88123855085,<br/>9052070490.";
$MESS["ACRIT_EXPORTPRO_AVITO_TRUCKS_AND_SPECIAL_MACHINERY_FIELD_REGION"] = "Регион,<br/>в котором находится объект объявления<br/>в соответствии со значениями из справочника.<br/><b class='required'>Обязательный элемент</b>";
$MESS["ACRIT_EXPORTPRO_AVITO_TRUCKS_AND_SPECIAL_MACHINERY_FIELD_CITY"] = "Город или населенный пункт, в котором находится объект объявления — в соответствии со значениями из справочника.<br/>
Элемент обязателен для всех регионов, кроме Москвы и Санкт-Петербурга.<br/>
Справочник является неполным. Если требуемое значение в нем отсутствует, то укажите ближайший к вашему объекту пункт из справочника, а точное название населенного пункта — в элементе Street.";

$MESS["ACRIT_EXPORTPRO_AVITO_TRUCKS_AND_SPECIAL_MACHINERY_FIELD_SUBWAY"] = "Ближайшая станция метро<br/>(в соответствии со значениями из справочника)";
$MESS["ACRIT_EXPORTPRO_AVITO_TRUCKS_AND_SPECIAL_MACHINERY_FIELD_DISTRICT"] = "Район города — в соответствии со значениями из справочника.";
$MESS["ACRIT_EXPORTPRO_AVITO_TRUCKS_AND_SPECIAL_MACHINERY_FIELD_CATEGORY"] = "Категория товара — строка: Грузовики и спецтехника";
$MESS["ACRIT_EXPORTPRO_AVITO_TRUCKS_AND_SPECIAL_MACHINERY_FIELD_CATEGORY_VALUE"] = "Грузовики и спецтехника";

$MESS["ACRIT_EXPORTPRO_AVITO_TRUCKS_AND_SPECIAL_MACHINERY_FIELD_GOODSTYPE"] = "<b>Вид техники — одно из значений списка:</b><br/><br/>
Автобусы<br/>
Автодома<br/>
Автокраны<br/>
Бульдозеры<br/>
Грузовики<br/>
Коммунальная техника<br/>
Лёгкий транспорт<br/>
Погрузчики<br/>
Прицепы<br/>
Сельхозтехника<br/>
Строительная техника<br/>
Техника для лесозаготовки<br/>
Тягачи<br/>
Экскаваторы";

$MESS["ACRIT_EXPORTPRO_AVITO_TRUCKS_AND_SPECIAL_MACHINERY_FIELD_TITLE"] = "Название объявления — строка до 50 символов.<br/>Примечание: не пишите в название цену и контактную информацию — для этого есть отдельные поля — и не используйте слово - продам.";
$MESS["ACRIT_EXPORTPRO_AVITO_TRUCKS_AND_SPECIAL_MACHINERY_FIELD_DESCRIPTION"] = "Текстовое описание объявления в соответствии с правилами Avito — строка не более 3000 символов.";

$MESS["ACRIT_EXPORTPRO_AVITO_TRUCKS_AND_SPECIAL_MACHINERY_FIELD_PRICE"] = "Цена в рублях";

$MESS["ACRIT_EXPORTPRO_AVITO_TRUCKS_AND_SPECIAL_MACHINERY_FIELD_IMAGE"] = "Изображения";
$MESS["ACRIT_EXPORTPRO_TYPE_AVITO_TRUCKS_AND_SPECIAL_MACHINERY_PORTAL_REQUIREMENTS"] = "http://autoload.avito.ru/format/gruzoviki_i_spetstehnika/";
$MESS["ACRIT_EXPORTPRO_TYPE_AVITO_TRUCKS_AND_SPECIAL_MACHINERY_PORTAL_VALIDATOR"] = "http://autoload.avito.ru/format/xmlcheck/";
$MESS["ACRIT_EXPORTPRO_TYPE_AVITO_TRUCKS_AND_SPECIAL_MACHINERY_EXAMPLE"] = "
<Ads formatVersion=\"3\" target=\"Avito.ru\">
    <Ad>
        <Id>723681273</Id>
        <DateBegin>2015-11-27</DateBegin>
        <DateEnd>2079-08-28</DateEnd>
        <AdStatus>TurboSale</AdStatus>
        <AllowEmail>Да</AllowEmail>
        <ManagerName>Иван Петров-Водкин</ManagerName>
        <ContactPhone>+7 916 683-78-22</ContactPhone>
        <Region>Владимирская область</Region>
        <City>Владимир</City>
        <District>Ленинский</District>
        <Category>Грузовики и спецтехника</Category>
        <GoodsType>Строительная техника</GoodsType>
        <Title>Автобетононасос Junjin JXZ 37-4.16HP на Daewoo</Title>
        <Description><![CDATA[
<p><strong>Характеристики насоса:</strong></p>
<ul>
<li>Объем подачи: 158 м3/ч
<li>Диаметр цилиндра: 230 мм
<li>Ход поршня: 2100 мм
</ul>
]]></Description>
        <Price>9000000</Price>
        <Images>
            <Image url=\"http://img.test.ru/8F7B-4A4F3A0F2BA1.jpg\" />
            <Image url=\"http://img.test.ru/8F7B-4A4F3A0F2XA3.jpg\" />
        </Images>
    </Ad>
    <Ad>
        <Id>odb3727321-12</Id>
        <Region>Санкт-Петербург</Region>
        <Subway>Автово</Subway>
        <Category>Грузовики и спецтехника</Category>
        <GoodsType>Автокраны</GoodsType>
        <Title>Автокран Днепр 25 т</Title>
        <Description>Продам автокран:
- Дата выпуска: 05.2014 г.
- Техника в отличном состоянии.
- Наработка около 700 м/ч.
- Вложений не требует.</Description>
        <Price>1100000</Price>
    </Ad>
</Ads>
";
?>