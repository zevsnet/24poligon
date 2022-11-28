<?
$MESS["askaron_parallel1c_exchange_edit_title"] = "Настройка обмена с 1С";

$MESS["askaron_parallel1c_exchange_edit_group1"] = "Основные настройки";
$MESS["askaron_parallel1c_exchange_edit_group1_help"] = "";

$MESS["askaron_parallel1c_exchange_edit_group2"] = "Параметры импорта товаров";
$MESS["askaron_parallel1c_exchange_edit_group2_help"] = "";

$MESS["askaron_parallel1c_exchange_edit_group3"] = "Импорт товаров: ускорение импорта";
$MESS["askaron_parallel1c_exchange_edit_group3_help"] = '';

$MESS["askaron_parallel1c_exchange_edit_group4"] = "Импорт товаров: этап деактивации (деактивации/удаления) элементов и разделов";
$MESS["askaron_parallel1c_exchange_edit_group4_help"] = '';

$MESS["askaron_parallel1c_exchange_edit_group5"] = "Версия компонента импорта и класса импорта";
$MESS["askaron_parallel1c_exchange_edit_group5_help"] = '';

$MESS["askaron_parallel1c_exchange_edit_id_not_found"] = "Настройка обмена с 1С с ID=#ID# не найдена";

$MESS["askaron_parallel1c_exchange_edit_code"] = "Код";
$MESS["askaron_parallel1c_exchange_edit_code_help"] = "Код используется в имени временной таблицы и в имени временной папки. Латинские буквы и цифры. Уникальный";

$MESS["askaron_parallel1c_exchange_edit_name"] = "Название";
$MESS["askaron_parallel1c_exchange_edit_name_help"] = "Назовите обмен, чтобы вам было понятно";


$MESS["askaron_parallel1c_exchange_edit_path"] = "Файл обмена";
$MESS["askaron_parallel1c_exchange_edit_path_help"] = "Файл обмена, к которому применяется эта настройка.";

$MESS["askaron_parallel1c_exchange_edit_component_import_ver"] = "Версия компонента импорта";
$MESS["askaron_parallel1c_exchange_edit_component_import_ver_help"] = "Настоятельно рекомендуется использовать последнюю версию
компонента импорта товаров, старую версию можно включить в том случае, если у вас старый Битрикс, а наш модуль обмена новый.
<br><br>Старые версии компонента не поддерживаются, не обновляются и ошибки в них не исправляются. Мы храним старые версии, потому что они могут быть кому-то полезны при обновлениях, 
чтобы ничего не сломалось.
<br><br>Битрикс редко обновляет импорт товаров из 1С, поэтому компонент в нашем модуле обновляется нечасто. Это нормально.";


$MESS["askaron_parallel1c_exchange_edit_cml2_import_ver"] = "Версия класса импорта CIBlockCMLImport";
$MESS["askaron_parallel1c_exchange_edit_cml2_import_ver_help"] = "Рекомендуется использовать класс CIBlockCMLImport из ядра 1С-Битрикс, обычно обмен с 1С в Битриксе новее, чем в модуле, и будут доступны все возможности формата обмена с самими новыми 1С.
<br><br>Мы храним старые версии, потому что они могут быть кому-то полезны при обновлениях, чтобы ничего не сломалось.";






$MESS["askaron_parallel1c_exchange_edit_field_is_empty"] = "Поле «#FIELD#» не заполнено";


$MESS["askaron_parallel1c_exchange_disallow_import_xml_step"] = "Пропустить импорт файлов import*.xml";
$MESS["askaron_parallel1c_exchange_disallow_import_xml_step_help"] = "При включенной опции метаданные каталога, свойства, разделы и сами товары не импортируются.
Опция не влияет на импорт торговых предложений (offers.xml), цен (prices.xml) и остатков (rests.xml).
<br><br>При включенной опции этап дективации пропускается, потому что деактивация связана со списком товаров.";

$MESS["askaron_parallel1c_exchange_disallow_offers_xml_step"] = "Пропустить импорт файлов offers*.xml (торговые предложения)";
$MESS["askaron_parallel1c_exchange_disallow_offers_xml_step_help"] = "";

$MESS["askaron_parallel1c_exchange_disallow_prices_xml_step"] = "Пропустить импорт файлов prices*.xml (цены)";
$MESS["askaron_parallel1c_exchange_disallow_prices_xml_step_help"] = "";

$MESS["askaron_parallel1c_exchange_disallow_rests_xml_step"] = "Пропустить импорт файлов rests*.xml (остатки)";
$MESS["askaron_parallel1c_exchange_disallow_rests_xml_step_help"] = "";



$MESS["askaron_parallel1c_exchange_disallow_deactivate_step"] = "Пропустить этап деактивации (деактивации/удаления) элементов и разделов";
$MESS["askaron_parallel1c_exchange_disallow_deactivate_step_help"] = "При включенной опции этап деактивации (дективации/удаления) не произойдет, даже, если на стороне 1С или в настройках обмена она включена.";


$MESS["askaron_parallel1c_exchange_allow_deactivate_iblocks"] = "Инфоблоки, с которым происходит обмен";
$MESS["askaron_parallel1c_exchange_allow_deactivate_iblocks_help"] =
"Иногда обмен с 1С при параллельных выгрузках деактивирует элементы не из своего инфоблока, 
а из параллельного работающего обмена.
<br><br>Явно укажите явно инфоблоки, в которые выгружает данный обмен, например «4,5».
<br><br>Опция срабатывает при выгрузке из новой 1С, если включена деактивация (на шаге mode=deactivate).";


$MESS["askaron_parallel1c_exchange_import_xml_new_only"] = "Импортировать только новые товары из import*.xml";
$MESS["askaron_parallel1c_exchange_import_xml_new_only_help"] = "При включенной опции товары, которые были на сайте раньше,
 пропускаются, и за счёт этого происходит существенное ускорение, потому что свойства и описания товаров меняются редко, но пишутся долго. Деактивация товаров не происходит.
<br><br>Опция не влияет на импорт торговых предложений (offers.xml), цен (prices.xml) и остатков (rests.xml).
<br><br>Предполагается, что должен быть второй обмен, который выгрузит import.xml полностью.";








//$MESS["askaron_parallel1c_exchange_edit_comment_is_empty"] = "Комментарий не заполнен";
//
//$MESS["askaron_parallel1c_exchange_edit_code_not_found"] = "Обработчик данных с CODE=#CODE# не найден";
//$MESS["askaron_parallel1c_exchange_edit_not_selected"] = "Обработчик данных не указан";
//$MESS["askaron_parallel1c_exchange_edit_save_ok"] = "Изменения успешно сохранены";
//$MESS["askaron_parallel1c_exchange_edit_doc"] = "Документация";
//
//
//$MESS["ASKARON_PARALLEL1C1_KOD"] = "Код";
//$MESS["ASKARON_PARALLEL1C1_NAZVANIE"] = "Название";
//$MESS["ASKARON_PARALLEL1C1_AKTIVNOSTQ"] = "Активность";
//$MESS["ASKARON_PARALLEL1C1_PORADOK_V_FAYLE_INIC"] = "Сортировка";
//$MESS["ASKARON_PARALLEL1C1_POZVOLAET_RASSTAVITQ"] = "Порядок в файле инициалиации. Позволяет расставить обработчики данных в файле инициализации в желаемом порядке";
//$MESS["ASKARON_PARALLEL1C1_KOMMENTARIY"] = "Комментарий";
//$MESS["ASKARON_PARALLEL1C1_OBAZATELQNO_UKAJITE"] = "Обязательно укажите комментарий. Комментарий поможет потом вам или другому человеку разобраться зачем установлен обработчик данных";
?>