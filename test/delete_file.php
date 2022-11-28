<?
$_SERVER["DOCUMENT_ROOT"] = realpath(__DIR__ . '/../');

require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/prolog_before.php");

function getFilesNoExist($offset = 0, $limit = 1000)
{
    $arFilesDelete = [];
    $obFiles = \Bitrix\Main\FileTable::getList([
        'filter' => [
            'MODULE_ID' => 'iblock',
        ],
        'offset' => $offset,
        'limit' => $limit,
        'select' => [
            'ID',
            'SUBDIR',
            'FILE_NAME',
        ]
    ]);

    while ($arFile = $obFiles->Fetch()) {
        if($arFile['SUBDIR']){
            $strLinkFile = $_SERVER["DOCUMENT_ROOT"] . '/upload/' . $arFile['SUBDIR'] . '/' . $arFile['FILE_NAME'];
            if (!file_exists($strLinkFile)) {
                $arFilesDelete[] = $arFile;
            }
        }

        unset($arFile);
    }
    unset($obFiles);
    return $arFilesDelete;
}
$step = 0;
while(true){
    $arFilesDelete = getFilesNoExist(0,30000);
    foreach ($arFilesDelete as $item) {
        file_put_contents($_SERVER['DOCUMENT_ROOT'] . '/upload/b_file_id_delete.log',$item['ID'] . "\n",FILE_APPEND);
        CFile::Delete($item['ID']);
    }

    if($step == 1){
        break;
    }
    $step++;
    echo $step;
    unset($arFilesDelete);
}

