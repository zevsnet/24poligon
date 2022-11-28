<? require_once($_SERVER['DOCUMENT_ROOT'] . "/bitrix/modules/main/include/prolog_before.php");
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true)
{
    die();
}
?>
<?
date_default_timezone_set('Asia/Krasnoyarsk');
switch($_REQUEST['ACTION'])
{
    case 'ADDSHEDULE':
        if(CModule::IncludeModule('iblock'))
        {
            $el = new CIBlockElement;
            $IBLOCK_ID = 38;
            $PROP = array();
            $arMessageError = array();
            $arMessageError['error'] = false;
            $arMessageError['len'] = 0;
            if($_REQUEST['MASTER'])
            {
                $PROP['Z_MASTER'] = $_REQUEST['MASTER'];
            }
            else
            {
                $arMessageError[] = 'Выберете мастера.';
                $arMessageError['len']++;

            }

            if($_REQUEST['DATE'])
            {
                $PROP['Z_DATE'] = substr($_REQUEST['DATE'], 0, strpos($_REQUEST['DATE'], ' '));
            }
            else
            {
                $arMessageError[] = 'Выберете дату и время.';
                $arMessageError['len']++;

            }
            if($_REQUEST['TIME'])
            {
                $PROP['Z_TIME'] = $_REQUEST['TIME'];
            }
            else
            {
                $arMessageError[] = 'Выберете время.';
                $arMessageError['len']++;

            }

            if($_REQUEST['SERVICES'])
            {
                $PROP['Z_SERVICES'] = $_REQUEST['SERVICES'];
            }
            else
            {
                $arMessageError[] = 'Выберете услугу.';
                $arMessageError['len']++;

            }

            if($_REQUEST['FIO'] || $_REQUEST['PHONE'])
            {
                $PROP['Z_FIO'] = $_REQUEST['FIO'];
                $PROP['Z_PHONE'] = $_REQUEST['PHONE'];
            }
            else
            {
                $arMessageError[] = 'Укажите ФИО или Телефон для связи.';
                $arMessageError['len']++;

            }

            $PROP['Z_EMAIL'] = $_REQUEST['EMAIL'];

            if($arMessageError['len'] == 0)
            {
                $IDELEMENT = '';

                $arSelect = Array();
                $arFilter = Array(
                    "IBLOCK_ID" => 35,
                    "ACTIVE"    => "Y",
                    //">DATE_ACTIVE_FROM" => $DATE_FINAL//ConvertTimeStamp(false, "FULL")
                    "%NAME"     => $PROP['Z_DATE']//ConvertTimeStamp(false, "FULL")
                );

                $res = CIBlockElement::GetList(Array('name' => 'ASC'), $arFilter, false, false, $arSelect);
                while($ob = $res->GetNextElement())
                {
                    $arFields = $ob->GetFields();
                    $arProps = $ob->GetProperties();
                    foreach($arProps['Z_END_TIME']['VALUE'] as $key => $time)
                    {
                        $start = $time;// substr($time, 0, strpos($time, '-'));
                        if(!strpos($time, ':'))
                        {
                            $time_value = $start . ':00';
                        }
                        else
                        {
                            $time_value = $start . '';
                        }

                        if($time_value == $PROP['Z_TIME'])
                        {
                            $IDELEMENT = $arFields['ID'];
                            break;
                        }
                    }
                }

                $arLoadProductArray = Array(
                    'MODIFIED_BY'       => $GLOBALS['USER']->GetID(),
                    // элемент изменен текущим пользователем
                    'IBLOCK_SECTION_ID' => false, // элемент лежит в корне раздела
                    'IBLOCK_ID'         => $IBLOCK_ID,
                    'PROPERTY_VALUES'   => $PROP,
                    'NAME'              => ($PROP['Z_EMAIL'] != '') ? $PROP['Z_EMAIL'] : ($PROP['Z_FIO'] != '') ? $PROP['Z_FIO'] : $PROP['Z_PHONE'],
                    'ACTIVE'            => 'Y', // активен
                    'PREVIEW_TEXT'      => '',
                );

                if($PRODUCT_ID = $el->Add($arLoadProductArray))
                {
                    //                echo 'New ID: ' . $PRODUCT_ID;
                    $arSelect = Array("ID", "NAME");
                    $arFilter = Array("IBLOCK_ID" => 33, "ID" => $PROP['Z_SERVICES']);
                    $res = CIBlockElement::GetList(Array(), $arFilter, false, false, $arSelect);
                    $SERVICE_LIST_NAME = '';
                    while($ob = $res->GetNextElement())
                    {
                        $arFields = $ob->GetFields();
                        $SERVICE_LIST_NAME = $SERVICE_LIST_NAME . $arFields['NAME'] . '<br/>';
                    }
                    $data = array(
                        "EMAIL"   => $PROP['Z_EMAIL'],
                        "DATE"    => $PROP['Z_DATE'],
                        "TIME"    => $PROP['Z_TIME'],
                        "FIO"     => $PROP['Z_FIO'],
                        "PHONE"   => $PROP['Z_PHONE'],
                        "SERVICE" => $SERVICE_LIST_NAME,
                    );
                    echo json_encode($data);
                    CEvent::send("SERVICE", "s2", $data);
                }
                else
                {
                    echo 'Error: ' . $el->LAST_ERROR;
                }

                /*удалим выбранное время*/
                $IB = 35;
                $ID = $IDELEMENT;
                $CODE = 'Z_END_TIME';

                $db_props = CIBlockElement::GetProperty($IB, $ID, array("sort" => "asc"), Array("CODE" => $CODE));
                $PROPERTY_VALUE = array();
                while($ar_props = $db_props->Fetch())
                {
                    //$start = $ar_props['VALUE'];//substr($ar_props['VALUE'], 0, strpos($ar_props['VALUE'], '-'));
                    $start = $ar_props['VALUE'];// substr($time, 0, strpos($time, '-'));
                    if(!strpos($ar_props['VALUE'], ':'))
                    {
                        $time_value = $start . ':00';
                    }
                    else
                    {
                        $time_value = $start . '';
                    }

                    if($time_value != $PROP['Z_TIME'])
                    {
                        $PROPERTY_VALUE[] = array('VALUE' => $ar_props['VALUE']);
                    }
                }

                if(empty($PROPERTY_VALUE))
                {
                    $el = new CIBlockElement;

                    $arLoadProductArray = Array("ACTIVE" => "N");
                    $PRODUCT_ID = $ID;  // изменяем элемент с кодом (ID) 2
                    $res = $el->Update($PRODUCT_ID, $arLoadProductArray);
                }
                else
                {
                    CIBlockElement::SetPropertyValuesEx($ID, $IB, array($CODE => $PROPERTY_VALUE));
                }
            }
            else
            {
                $arMessageError['error'] = true;
                echo json_encode($arMessageError);
            }
        }
        break;
    case 'DELETETIME':
        if(CModule::IncludeModule('iblock'))
        {
            /*удалим выбранное время*/
            $IB = 35;
            $ID = $_REQUEST['IDELEMENT'];
            $CODE = 'Z_END_TIME';

            $db_props = CIBlockElement::GetProperty($IB, $ID, array("sort" => "asc"), Array("CODE" => $CODE));
            $PROPERTY_VALUE = array();
            while($ar_props = $db_props->Fetch())
            {
                $start = $ar_props['VALUE'];//substr($ar_props['VALUE'], 0, strpos($ar_props['VALUE'], '-'));
                if($start != $_REQUEST['TIME'])
                {
                    $PROPERTY_VALUE[] = array('VALUE' => $ar_props['VALUE']);
                }
            }

            if(empty($PROPERTY_VALUE))
            {
                $el = new CIBlockElement;

                $arLoadProductArray = Array("ACTIVE" => "N");
                $PRODUCT_ID = $ID;  // изменяем элемент с кодом (ID) 2
                $res = $el->Update($PRODUCT_ID, $arLoadProductArray);
            }
            else
            {
                CIBlockElement::SetPropertyValuesEx($ID, $IB, array($CODE => $PROPERTY_VALUE));
            }
        }
        break;
    case 'GETTIME':
        if(CModule::IncludeModule('iblock'))
        {
            $DATE_FINAL = false;

            if($_REQUEST['DATE_FINAL'])
            {
                $DATE_FINAL = $_REQUEST['DATE_FINAL'];
				$DATE_FINAL = ConvertDateTime($DATE_FINAL, "DD.MM.YYYY", "ru");
            }
            if($DATE_FINAL)
            {
                $arSelect = Array();
                $arFilter = Array(
                    "IBLOCK_ID" => 35,
                    "ACTIVE"    => "Y",
                    //">DATE_ACTIVE_FROM" => $DATE_FINAL//ConvertTimeStamp(false, "FULL")
                    "%NAME"     => $DATE_FINAL//ConvertTimeStamp(false, "FULL")
                );

                $res = CIBlockElement::GetList(Array('name' => 'ASC'), $arFilter, false, false, $arSelect);
                $arResult = array();
                $TIME_WORK = array();
                while($ob = $res->GetNextElement())
                {
                    $arFields = $ob->GetFields();
                    $arProps = $ob->GetProperties();

                    $dateStart = substr($arFields['DATE_ACTIVE_FROM'], 0, strpos($arFields['DATE_ACTIVE_FROM'], ' '));
                    foreach($arProps['Z_END_TIME']['VALUE'] as $val)
                    {
                        if(!in_array($val, $TIME_WORK))
                        {
                            if(date('d.m.Y') == $DATE_FINAL)
                            {
                                if(date('h') < $val)
                                    $TIME_WORK[] = $val;
                            }
                            else
                            {
                                $TIME_WORK[] = $val;
                            }
                        }
                    }
                    //$arResult[] = array('NAME' => $dateStart, 'TIME_WORK' => $arProps['Z_END_TIME']['VALUE']);

                }
                echo json_encode($TIME_WORK);
                //_::d($DATE_FINAL);
            }
        }

        break;
    default:
        break;
}
?>
