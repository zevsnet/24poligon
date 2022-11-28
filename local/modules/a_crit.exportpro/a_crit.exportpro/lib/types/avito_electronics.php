<?php
IncludeModuleLangFile( __FILE__ );

$profileTypes["avito_electronics"] = array(
    "CODE" => "avito_electronics",
    "NAME" => GetMessage( "ACRIT_EXPORTPRO_AVITO_ELECTRONICS_NAME" ),
    "DESCRIPTION" => GetMessage( "ACRIT_EXPORTPRO_PODDERJIVAETSA_ANDEK" ),
    "REG" => "http://market.yandex.ru/",
    "HELP" => "http://help.yandex.ru/partnermarket/export/feed.xml",
    "FIELDS" => array(
        array(
            "CODE" => "Id",
            "NAME" => GetMessage( "ACRIT_EXPORTPRO_AVITO_ELECTRONICS_FIELD_ID" ),
            "VALUE" => "ID",
            "REQUIRED" => "Y",
            "TYPE" => "field",
        ),
        array(
            "CODE" => "DateBegin",
            "NAME" => GetMessage( "ACRIT_EXPORTPRO_AVITO_ELECTRONICS_FIELD_DATEBEGIN" ),
        ),
        array(
            "CODE" => "DateEnd",
            "NAME" => GetMessage( "ACRIT_EXPORTPRO_AVITO_ELECTRONICS_FIELD_DATEEND" ),
        ),
        array(
            "CODE" => "ListingFee",
            "NAME" => GetMessage( "ACRIT_EXPORTPRO_AVITO_ELECTRONICS_FIELD_LISTINGFEE" ),
        ),
        array(
            "CODE" => "AdStatus",
            "NAME" => GetMessage( "ACRIT_EXPORTPRO_AVITO_ELECTRONICS_FIELD_ADSTATUS" ),
        ),
        array(
            "CODE" => "AvitoId",
            "NAME" => GetMessage( "ACRIT_EXPORTPRO_AVITO_ELECTRONICS_FIELD_AVITOID" ),
        ),
        array(
            "CODE" => "AllowEmail",
            "NAME" => GetMessage( "ACRIT_EXPORTPRO_AVITO_ELECTRONICS_FIELD_ALLOWEMAIL" ),
        ),
        array(
            "CODE" => "ManagerName",
            "NAME" => GetMessage( "ACRIT_EXPORTPRO_AVITO_ELECTRONICS_FIELD_MANAGERNAME" ),
        ),
        array(
            "CODE" => "ContactPhone",
            "NAME" => GetMessage( "ACRIT_EXPORTPRO_AVITO_ELECTRONICS_FIELD_CONTACTPHONE" ),
        ),
        array(
            "CODE" => "Region",
            "NAME" => GetMessage( "ACRIT_EXPORTPRO_AVITO_ELECTRONICS_FIELD_REGION" ),
            "REQUIRED" => "Y",
        ),
        array(
            "CODE" => "City",
            "NAME" => GetMessage( "ACRIT_EXPORTPRO_AVITO_ELECTRONICS_FIELD_CITY" ),
            "REQUIRED" => "Y",
        ),
        array(
            "CODE" => "Subway",
            "NAME" => GetMessage( "ACRIT_EXPORTPRO_AVITO_ELECTRONICS_FIELD_SUBWAY" ),
        ),
        array(
            "CODE" => "District",
            "NAME" => GetMessage( "ACRIT_EXPORTPRO_AVITO_ELECTRONICS_FIELD_DISTRICT" ),
            "REQUIRED" => "Y",
        ),
        array(
            "CODE" => "DeliveryWarehouseKey",
            "NAME" => GetMessage( "ACRIT_EXPORTPRO_AVITO_ELECTRONICS_FIELD_DELIVERYWAREHOUSEKEY" ),
        ),
        array(
            "CODE" => "DeliveryWeight",
            "NAME" => GetMessage( "ACRIT_EXPORTPRO_AVITO_ELECTRONICS_FIELD_DELIVERYWEIGHT" ),
        ),
        array(
            "CODE" => "DeliveryIsAllowPrepayment",
            "NAME" => GetMessage( "ACRIT_EXPORTPRO_AVITO_ELECTRONICS_FIELD_DELIVERYISALLOWPREPAYMENT" ),
        ),
        array(
            "CODE" => "DeliveryWidth",
            "NAME" => GetMessage( "ACRIT_EXPORTPRO_AVITO_ELECTRONICS_FIELD_DELIVERYWIDTH" ),
        ),
        array(
            "CODE" => "DeliveryHeight",
            "NAME" => GetMessage( "ACRIT_EXPORTPRO_AVITO_ELECTRONICS_FIELD_DELIVERYHEIGHT" ),
        ),
        array(
            "CODE" => "DeliveryLength",
            "NAME" => GetMessage( "ACRIT_EXPORTPRO_AVITO_ELECTRONICS_FIELD_DELIVERYLENGTH" ),
        ),
        array(
            "CODE" => "Category",
            "NAME" => GetMessage( "ACRIT_EXPORTPRO_AVITO_ELECTRONICS_FIELD_CATEGORY" ),
            "REQUIRED" => "Y",
        ),
        array(
            "CODE" => "GoodsType",
            "NAME" => GetMessage( "ACRIT_EXPORTPRO_AVITO_ELECTRONICS_FIELD_GOODSTYPE" ),
        ),
        array(
            "CODE" => "Title",
            "NAME" => GetMessage( "ACRIT_EXPORTPRO_AVITO_ELECTRONICS_FIELD_TITLE" ),
            "REQUIRED" => "Y",
        ),
        array(
            "CODE" => "Description",
            "NAME" => GetMessage( "ACRIT_EXPORTPRO_AVITO_ELECTRONICS_FIELD_DESCRIPTION" ),
            "REQUIRED" => "Y",
        ),
        array(
            "CODE" => "Price",
            "NAME" => GetMessage( "ACRIT_EXPORTPRO_AVITO_ELECTRONICS_FIELD_PRICE" ),
            "TYPE" => "const",
            "CONTVALUE_TRUE" => "0",
        ),
        array(
            "CODE" => "Image",
            "NAME" => GetMessage( "ACRIT_EXPORTPRO_AVITO_ELECTRONICS_FIELD_IMAGE" ),
        ),
    ),
    "FORMAT" => '<?xml version="1.0"?>
<Ads formatVersion="3" target="Avito.ru">
    #ITEMS#
</Ads>',

    "DATEFORMAT" => "Y-m-d",
);

$bCatalog = false;
if( CModule::IncludeModule( "catalog" ) ){
    $arBasePrice = CCatalogGroup::GetBaseGroup();
    $basePriceCode = "CATALOG-PRICE_".$arBasePrice["ID"];
    $basePriceCodeWithDiscount = "CATALOG-PRICE_".$arBasePrice["ID"]."_WD";
    $bCatalog = true;

    $profileTypes["avito_electronics"]["FIELDS"][23] = array(
        "CODE" => "Price",
        "NAME" => GetMessage( "ACRIT_EXPORTPRO_AVITO_ELECTRONICS_FIELD_PRICE" ),
        "TYPE" => "field",
        "VALUE" => $basePriceCode,
    );
}

$profileTypes["avito_electronics"]["PORTAL_REQUIREMENTS"] = GetMessage( "ACRIT_EXPORTPRO_TYPE_AVITO_ELECTRONICS_PORTAL_REQUIREMENTS" );
$profileTypes["avito_electronics"]["PORTAL_VALIDATOR"] = GetMessage( "ACRIT_EXPORTPRO_TYPE_AVITO_ELECTRONICS_PORTAL_VALIDATOR" );
$profileTypes["avito_electronics"]["EXAMPLE"] = GetMessage( "ACRIT_EXPORTPRO_TYPE_AVITO_ELECTRONICS_EXAMPLE" );

$profileTypes["avito_electronics"]["CURRENCIES"] = "";

$profileTypes["avito_electronics"]["SECTIONS"] = "";

$profileTypes["avito_electronics"]["ITEMS_FORMAT"] = "
<Ad>
    <Id>#Id#</Id>
    <DateBegin>#DateBegin#</DateBegin>
    <DateEnd>#DateEnd#</DateEnd>
    <ListingFee>#ListingFee#</ListingFee>
    <AdStatus>#AdStatus#</AdStatus>
    <AvitoId>#AvitoId#</AvitoId>
    <AllowEmail>#AllowEmail#</AllowEmail>
    <ManagerName>#ManagerName#</ManagerName>
    <ContactPhone>#ContactPhone#</ContactPhone>
    <Region>#Region#</Region>
    <City>#City#</City>
    <Subway>#Subway#</Subway>
    <District>#District#</District>
    <Category>#Category#</Category>
    <GoodsType>#GoodsType#</GoodsType>
    <Title>#Title#</Title>
    <Description>#Description#</Description>
    <Price>#Price#</Price>
    <Images>
        <Image url=\"#SITE_URL##Image#\"></Image>
    </Images>
    <Delivery>
        <WarehouseKey>#DeliveryWarehouseKey#</WarehouseKey>
        <Weight>#DeliveryWeight#</Weight>
        <IsAllowPrepayment>#DeliveryIsAllowPrepayment#</IsAllowPrepayment>
        <Width>#DeliveryWidth#</Width>
        <Height>#DeliveryHeight#</Height>
        <Length>#DeliveryLength#</Length>
    </Delivery>
</Ad>
";