<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");?>

<?require($_SERVER["DOCUMENT_ROOT"]."/tmp/oc_product.php");?>
<?require($_SERVER["DOCUMENT_ROOT"]."/tmp/oc_product_description.php");?>
<?require($_SERVER["DOCUMENT_ROOT"]."/tmp/oc_product_image.php");?>

<?
/*
$tmpImagesPath = $_SERVER["DOCUMENT_ROOT"].'/upload/oc_images/';

$ocProductsExIDs = array_column($oc_product, 'mpn');
$ocProductImgIds = array_column($oc_product_image, 'product_id');
//var_Dump($ocProductDescIds );
$dbCurrentProducts = CIBlockElement::GetList(array(), array('IBLOCK_ID' => 95), false, false, array('IBLOCK_ID', 'ID', 'EXTERNAL_ID'));
$arDoneProds = array();
while ($arProduct = $dbCurrentProducts->fetch()){
    $exId = $arProduct['EXTERNAL_ID'];

     $ocProdIndex = array_search($exId, $ocProductsExIDs);
     if ($ocProdIndex ) {
         $ocProdId = $oc_product[$ocProdIndex]['product_id'];
         $ocImgId = array_keys($ocProductImgIds, $ocProdId);

         if($ocImgId != array()) {

             foreach($ocImgId as $imgId){
                 $imgPath = $oc_product_image[$imgId]["image"];
                 if ($imgPath) {
                     $arDoneProds[$arProduct['ID']]['IMGS'][] = CFile::MakeFileArray($tmpImagesPath.$imgPath);
                 }


             }//var_dump( $tmpImagesPath.$imgPath);
             // var_dump();
         } else {
             $imgPath = $oc_product[$ocProdIndex]['image'];
             if ($imgPath) {
                 $arDoneProds[$arProduct['ID']]['IMGS'][] = CFile::MakeFileArray($tmpImagesPath.$imgPath);
             }
         }
     } else {
         var_dump($arProduct['ID']);
     }


}

/*foreach($arDoneProds as $key => $arProd) {
    $el = new CIBlockElement;
    $detailPic = array_shift($arProd['IMGS']);
    $otherPics = $arProd['IMGS'];
    echo '<pre>';
  //  var_dump($key, $detailPic);
    var_dump($el->Update($key, array('DETAIL_PICTURE' => $detailPic, 'PREVIEW_PICTURE' => $detailPic, 'PROPERTY_VALUES' => array('MORE_PHOTO' => $otherPics)) ));
    echo '</pre>';
}
*/

$dbCurrentProducts = CIBlockElement::GetList(array(), array('IBLOCK_ID' => 100), false, false, array('IBLOCK_ID', 'ID'));
$arDoneProds = array();
/*
while ($arProduct = $dbCurrentProducts->GetNextElement()) {

    //получаем значения свойства "Характеристики" (прилетает из 1с)
    $props = $arProduct->GetProperties(array(), array('CODE' => 'CML2_ATTRIBUTES'));

    foreach($props["CML2_ATTRIBUTES"]["DESCRIPTION"] as $key => $propDesc) {
        //получаем название свойства, и проверяем имеется ли такое в битриксе
        $arProp = CIBlockProperty::GetList(Array("sort"=>"asc", "name"=>"asc"), Array("ACTIVE"=>"Y", "IBLOCK_ID"=>100, 'NAME' => $propDesc))->fetch();
        $ibp = new CIBlockProperty;
        $propCode =  CUtil::translit($propDesc, 'ru');
        $PropID;

       //если нет, то создаем новое свойство типа список и получаем его айдишник
        if (!$arProp) {
            $arFields = Array(
                "NAME" => $propDesc,
                "ACTIVE" => "Y",
                "SORT" => "100",
                "CODE" => $propCode,
                "PROPERTY_TYPE" => "L",
                "IBLOCK_ID" => 100
            );

            $PropID = $ibp->Add($arFields);

        } else {
            //если имеется то просто получаем айдишник по коду свойства
            $properties = CIBlockProperty::GetList(Array("sort"=>"asc", "name"=>"asc") , Array("ACTIVE"=>"Y", "IBLOCK_ID"=>100,"CODE"=>$propCode));

            while ($prop_fields = $properties->GetNext())

            {

                $PropID = $prop_fields["ID"];
            }
        }
        $ibpenum = new CIBlockPropertyEnum;
        //формируем значения свойства
        $propVal = $props["CML2_ATTRIBUTES"]['VALUE'][$key];
        //$propXMLId = intval($propVal);
        $propValId = 0;
        $propHasValues = CIBlockPropertyEnum::GetList(array(),Array("IBLOCK_ID"=>100, 'ID' => $PropID, 'VALUE' =>  $propVal))->GetNext();

        if (!$propHasValues) {
            $propValId = $ibpenum->Add(Array('PROPERTY_ID'=>  $PropID, 'VALUE' =>  $propVal));
        } else {
            $propValId =  $propHasValues['ID'];
        }
       // var_dump($propValId );
        if ($propValId != 0) {
            CIBlockElement::SetPropertyValuesEx($arProduct->fields['ID'], 100, array($propCode => $propValId ));
        }

    }
}*/