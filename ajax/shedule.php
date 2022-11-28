<?require_once($_SERVER['DOCUMENT_ROOT']
    . "/bitrix/modules/main/include/prolog_before.php");
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) {
    die();
}
?>
<?
switch($_REQUEST['ACTION']){
    case 'ADDSHEDULE':
        if(CModule::IncludeModule('iblock')) {
            $el = new CIBlockElement;
            $IBLOCK_ID=38;
            $PROP = array();
            $START_DATE = explode(" ", $_REQUEST['START_DATE']);
            $END_DATE = explode(" ", $_REQUEST['END_DATE']);
            $month=array("","Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep","Oct", "Nov", "Dec");
            $m_start = array_search($START_DATE[1],$month);
            if($m_start >9)$m_start=$m_start; else $m_start='0'.$m_start;
            $PROP['Z_START_DATE'] = $START_DATE[2].'/'.$m_start.'/'.$START_DATE[3].' '.$START_DATE[4];
            $PROP['Z_END_DATE'] = $END_DATE[2].'/'.array_search($END_DATE[1],$month).'/'.$END_DATE[3].' '.$END_DATE[4];

            $arLoadProductArray = Array(
                'MODIFIED_BY'       => $GLOBALS['USER']->GetID(),
                // элемент изменен текущим пользователем
                'IBLOCK_SECTION_ID' => false, // элемент лежит в корне раздела
                'IBLOCK_ID'         => $IBLOCK_ID,
                'PROPERTY_VALUES'   => $PROP,
                'NAME'              => 'FIO',
                'ACTIVE'            => 'Y', // активен
                'PREVIEW_TEXT'      => $_REQUEST['MESSAGE'],
            );
//print_r($arLoadProductArray);
            if ($PRODUCT_ID = $el->Add($arLoadProductArray)) {
                echo 'New ID: ' . $PRODUCT_ID;
            } else {
                echo 'Error: ' . $el->LAST_ERROR;
            }
        }
        break;
    default:break;
}
?>
