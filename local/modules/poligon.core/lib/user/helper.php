<?php


namespace Poligon\Core\User;


use Bitrix\Main\UserTable;
use Bitrix\Sale\Internals\UserPropsTable;
use Bitrix\Sale\OrderUserProperties;

class Helper
{
    public static function export2CSV()
    {
        $arUser = UserTable::getList(['select' => ['ID', 'EMAIL','PHONE_AUTH']])->fetchAll();
        $linkFileExport = $_SERVER['DOCUMENT_ROOT'] . '/upload/user.csv';
        if (file_exists($linkFileExport)) {
            file_put_contents($linkFileExport, 'ID;EMAIL;PHONE_AUTH'. "\n");
        }
        foreach ($arUser as $item) {

            file_put_contents($linkFileExport, implode(';', [
                $item['ID'],
                $item['EMAIL'],
                $item['MAIN_USER_PHONE_AUTH_PHONE_NUMBER']
                ]) . "\n", FILE_APPEND);
        }
    }

    /**
     * Выгрузка
     * Контрагенты(профиль на сайте)
     * @throws \Bitrix\Main\ArgumentException
     * @throws \Bitrix\Main\ObjectPropertyException
     * @throws \Bitrix\Main\SystemException
     */
    public static function exportProfile2CSV()
    {
        $linkFileExport = $_SERVER['DOCUMENT_ROOT'] . '/upload/userProfile.csv';

        file_put_contents($linkFileExport, 'ID_PROFILE;NAME;INN;KPP;USER_ID;EMAIL;PHONE;FIO;TYPE;GUID' . "\n");
        $obElementsProfiles = UserPropsTable::getList(['select' => ['*']]);
        while ($arProfile = $obElementsProfiles->Fetch()) {
            $idProfile = $arProfile['ID'];

            $userPropertyValues = OrderUserProperties::getProfileValues((int)$idProfile);

            $strCSV = '';
            switch ($arProfile['PERSON_TYPE_ID']) {
                case '1':
                    $strCSV = implode(';',
                        [
                            $idProfile,
                            trim($arProfile['NAME']),
                            $userPropertyValues[10] ?: '',//ИНН
                            $userPropertyValues[11] ?: '',//КПП
                            $arProfile['USER_ID'],//USER_ID
                            $userPropertyValues[2] ?: '',//Email
                            $userPropertyValues[3] ?: '',//PHONE
                            $userPropertyValues[1] ?: '',//FIO
                            $arProfile['PERSON_TYPE_ID'],//TYPE
                            $arProfile['XML_ID']//GUID
                        ]
                    );
                    break;
                case '2':
                    $strCSV = implode(';',
                        [
                            $idProfile,
                            trim($arProfile['NAME']),
                            $userPropertyValues[10] ?: '',//ИНН
                            $userPropertyValues[11] ?: '',//КПП
                            $arProfile['USER_ID'],//USER_ID
                            $userPropertyValues[13] ?: '',//Email
                            $userPropertyValues[14] ?: '',//PHONE
                            $userPropertyValues[12] ?: '',//FIO
                            $arProfile['PERSON_TYPE_ID'],//TYPE
                            $arProfile['XML_ID']//GUID
                        ]
                    );
                    break;
                case '3':
                    $strCSV = implode(';',
                        [
                            $idProfile,
                            trim($arProfile['NAME']),
                            $userPropertyValues[22] ?: '',//ИНН
                            '',//КПП
                            $arProfile['USER_ID'],//USER_ID
                            $userPropertyValues[25] ?: '',//Email
                            $userPropertyValues[26] ?: '',//PHONE
                            $userPropertyValues[24] ?: '',//FIO
                            $arProfile['PERSON_TYPE_ID'],//TYPE
                            $arProfile['XML_ID']//GUID
                        ]
                    );
                    break;
                case '4':
                    $strCSV = implode(';',
                        [
                            $idProfile,
                            trim($arProfile['NAME']),
                            $userPropertyValues[33] ?: '',//ИНН
                            '',//КПП
                            $arProfile['USER_ID'],//USER_ID
                            $userPropertyValues[35] ?: '',//Email
                            $userPropertyValues[36] ?: '',//PHONE
                            $userPropertyValues[34] ?: '',//FIO
                            $arProfile['PERSON_TYPE_ID'],//TYPE
                            $arProfile['XML_ID']//GUID
                        ]
                    );
                    break;
            }
            file_put_contents($linkFileExport, $strCSV. "\n", FILE_APPEND);
        }
    }

    // Handle the parsing of the _ga cookie or setting it to a unique identifier
    public static function gaParseCookie()
    {
        if (isset($_COOKIE['_ga'])) {
            list($version, $domainDepth, $cid1, $cid2) = mb_split('[\.]', $_COOKIE["_ga"], 4);
            $contents = array('version' => $version, 'domainDepth' => $domainDepth, 'cid' => $cid1 . '.' . $cid2);
            $cid = $contents['cid'];
        } else {
            $cid = '';//gaGenUUID();
        }
        return $cid;
    }
}
