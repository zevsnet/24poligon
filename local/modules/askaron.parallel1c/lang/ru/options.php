<?
$MESS["askaron_parallel1c_options_about"] = '<strong>Добро пожаловать!</strong>
<br><br>
Модуль содержит страницы обмена, на которых расположен модифицированный компонент импорта товаров.
Модифицированный компонент работает так же, как оригинальный. Мы следим за его своевременным обновлением.
<br><br>
В отличие от стандартного компонента, можно запускать параллельно выгрузки товаров в один или разные инфоблоки.
Дополнительно можно настроить, какие данные обмен должен менять, а какие не должен.
<br><br>
<strong>Примеры применения модуля:</strong>
<br>
<br><strong>1. Если на сайте несколько инфоблоков товаров,</strong> то без проблем выгружаете два и более инфоблоков параллельно.
В 1С достаточно настроить несколько обменов вместо одного с разными файлами вида /bitrix/admin/a03_askaron_parallel1c_exchange.php, /bitrix/admin/a04_askaron_parallel1c_exchange.php и т. д.
<br>
<br><strong>2. Если на сайте один инфоблок,</strong> то в 1С настраиваете два обмена:
<br>- в первом обмене выгружаете товары полностью, но без торговых предложений, цен и остатков (или на сайте включите опции их пропускать)
<br>- во втором обмене выгружаете цены, остатки и торговые предложения. Товары выгружаете без картинок и свойств товаров, a на сайте включаем флажок "Импортировать только новые товары из import*.xml"
<br>в итоге должны получиться два обмена, которые выгружают разные данные в один инфоблок, работают параллельно и не конфликтуют.
<br><br>
Во втором обмене вы получите ускорение выгрузки цен и остатков. А первый обмен выгрузит описания товаров.
<br><br>
<style>
	.askaron_parallel1c_option_table 
	{
		border-spacing: 0;
		border-collapse: collapse;
	}

	.askaron_parallel1c_option_table td 
	{		
		margin: 0;
		padding: 5px 5px;
		border: 1px solid black;
	}

	.askaron_parallel1c_option_table th 
	{		
		margin: 0;
		padding: 5px 5px;
		border: 1px solid black;
	}
</style>
<table class="askaron_parallel1c_option_table">
	<tr>
		<th></th>
		<th>a01</th>
		<th>a02</th>
	</tr>
	<tr>
		<td>Товары import*.xml</td>
		<td>да</td>
		<td>Записываем только новые товары<br>(старые пропускаем)</td>
	</tr>
	<tr>
		<td>Торговые предложения offers*.xml</td>
		<td>нет</td>
		<td>да</td>
	</tr>
	<tr>
		<td>Цены prices*.xml</td>
		<td>нет</td>
		<td>да</td>
	</tr>
	<tr>
		<td>Остатки prices*.xml</td>
		<td>нет</td>
		<td>да</td>
	</tr>
	<tr>
		<td>Деактивакция товаров<br>(срабатывает при полной выгрузке, при выгрузке изменений<br>даже стандартный обмен не деактивирует, т.к. неизвестно, что деактивировать)</td>
		<td>да</td>
		<td>нет</td>
	</tr>
</table>

 
<br><br>
<a href="http://askaron.ru/api_help/course1/lesson221/" target="_blank">Документация по модулю</a>
<br><br>
<a href="/bitrix/admin/askaron_parallel1c_exchange_admin.php?lang='.LANG.'">Настройки обменов с 1С</a>
';



$MESS["askaron_parallel1c_options_about_html"] = 'На этой странице есть только общие настройки модуля.';

$MESS ['askaron_parallel1c_header_debug'] = "Отладка обмена";

$MESS ['askaron_parallel1c_copy_exchange_files'] = "Копировать XML-файлы обмена в папку модуля";
$MESS ['askaron_parallel1c_copy_exchange_files_help'] = 'Опция полезна лишь для отладки.
<br><br>
Не включайте опцию постоянно. Она нужна лишь программисту при настройке и отладе обмена. Модуль не следит за размером папки, и место на диске может закончиться.
<br><br>
Папка файлов <a target="_blank" href="/bitrix/admin/fileman_admin.php?lang='.LANGUAGE_ID.'&amp;path='.urlencode("/upload/1c_catalog_copy_askaron_parallel1c").'">/upload/1c_catalog_copy_askaron_parallel1c</a>
';



//
//$MESS["ASKARON_PARALLEL1C1_LOG_GROUP"] = 'Разработчикам: настройки для функции \\CAskaronParallel1c::log( $value, $label );';
//
//$MESS["ASKARON_PARALLEL1C1_LOG_GROUP"] = 'Разработчикам: настройки для функции \\CAskaronParallel1c::log( $value, $label );';
//$MESS["ASKARON_PARALLEL1C1_LOG_GROUP_HELP"] = 'Функцию <strong>\\CAskaronParallel1c::log</strong> можно использовать
//для отладки собственных обработчиков данных. Функция является оберткой над стандартной <a target="_blank" href="https://dev.1c-bitrix.ru/api_help/main/functions/debug/addmessage2log.php">AddMessage2Log</a>
//
//<br><br>Имя файла лога можете указать самостоятельно. Например, в файле <strong>/bitrix/php_interface/dbconn.php</strong> можете написать:
//
//<br><br><strong>define( "LOG_FILENAME", $_SERVER["DOCUMENT_ROOT"]."/log-'.
//\COption::GetOptionString( "askaron.parallel1c", "random_value" ).'.txt");</strong>';
//
//$MESS ['askaron_pro1c_log_trace'] = "Записывать в лог-файл порядок вызова функций";
//$MESS ['askaron_pro1c_log_trace_help'] = "Количество строк трейса, которые дополнительно попадут в лог-файл";
//
//$MESS ['askaron_pro1c_log_max_size'] = "Максимальный размер лог-файла";
//$MESS ['askaron_pro1c_log_max_size_2'] = "мегабайт";
//$MESS ['askaron_pro1c_log_max_size_help'] = "При достижении максимального размера лог-файл будет очищен.
//Это нужно, чтобы место на сайте не закочилось.";

?>