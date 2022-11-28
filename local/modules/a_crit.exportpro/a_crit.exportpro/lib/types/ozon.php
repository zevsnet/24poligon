<?php
IncludeModuleLangFile( __FILE__ );

$profileTypes["ozon"] = array(
    "CODE" => "ozon",
    "NAME" => GetMessage( "ACRIT_EXPORTPRO_OZON" ),
    "DESCRIPTION" => GetMessage( "ACRIT_EXPORTPRO_OZON_DESCR" ),
    "REG" => "/",
    "HELP" => "/",
    "FIELDS" => array(
        array(
            "CODE" => "ID",
            "NAME" => GetMessage( "ACRIT_EXPORTPRO_OZON_FIELD_ID" ),
            "VALUE" => "ID",
            "REQUIRED" => "Y",
            "TYPE" => "field",
            "DELETE_ONEMPTY" => "N",
        ),
        array(
            "CODE" => "SELLING_STATE",
            "NAME" => GetMessage( "ACRIT_EXPORTPRO_OZON_FIELD_AVAILABLE" ),
            "VALUE" => "",
            "TYPE" => "const",
            "CONDITION" => array(
                "CLASS_ID" => "CondGroup",
                "DATA" => array(
                    "All" => "AND",
                    "True" => "True"
                ),
                "CHILDREN" => array(
                    array(
                        "CLASS_ID" => "CondCatQuantity",
                        "DATA" => array(
                                "logic" => "EqGr",
                                "value" => "1"
                        )
                    )
                )
            ),
            "USE_CONDITION" => "Y",
            "CONTVALUE_TRUE" => "ForSale",
            "CONTVALUE_FALSE" => "NotForSale",
        ),
        array(
            "CODE" => "SUPPLY_PERIOD",
            "NAME" => GetMessage( "ACRIT_EXPORTPRO_OZON_FIELD_SUPPLY_PERIOD" ),
        ),
        array(
            "CODE" => "QTY",
            "NAME" => GetMessage( "ACRIT_EXPORTPRO_OZON_FIELD_SUPPLY_QTY" ),
        ),
        array(
            "CODE" => "NAME",
            "NAME" => GetMessage( "ACRIT_EXPORTPRO_OZON_FIELD_NAME" ),
            "VALUE" => "NAME",
            "TYPE" => "field",
            "DELETE_ONEMPTY" => "N",
        ),
        array(
            "CODE" => "MANUFACTURER_IDENTIFIER",
            "NAME" => GetMessage( "ACRIT_EXPORTPRO_OZON_FIELD_MANUFACTURER_IDENTIFIER" ),
        ),
        array(
            "CODE" => "GROSS_WEIGHT",
            "NAME" => GetMessage( "ACRIT_EXPORTPRO_OZON_FIELD_GROSS_WEIGHT" ),
            "REQUIRED" => "Y",
            "DELETE_ONEMPTY" => "N",
        ),
        array(
            "CODE" => "INTERNAL_NAME",
            "NAME" => GetMessage( "ACRIT_EXPORTPRO_OZON_FIELD_INTERNAL_NAME" ),
        ),
        array(
            "CODE" => "SELLING_PRICE",
            "NAME" => GetMessage( "ACRIT_EXPORTPRO_OZON_FIELD_SELLING_PRICE" ),
            "TYPE" => "const",
            "CONTVALUE_TRUE" => "0",
        ),
        array(
            "CODE" => "DISCOUNT",
            "NAME" => GetMessage( "ACRIT_EXPORTPRO_OZON_FIELD_DISCOUNT" ),
        ),
        array(
            "CODE" => "ALTERNAME",
            "NAME" => GetMessage( "ACRIT_EXPORTPRO_OZON_FIELD_ALTERNAME" ),
        ),
        array(
            "CODE" => "ELUCIDATIVENAME",
            "NAME" => GetMessage( "ACRIT_EXPORTPRO_OZON_FIELD_ELUCIDATIVENAME" ),
        ),
        array(
            "CODE" => "STICKERINFO",
            "NAME" => GetMessage( "ACRIT_EXPORTPRO_OZON_FIELD_STICKERINFO" ),
        ),
        array(
            "CODE" => "ANNOTATION",
            "NAME" => GetMessage( "ACRIT_EXPORTPRO_OZON_FIELD_ANNOTATION" ),
        ),
        array(
            "CODE" => "PICTURE",
            "NAME" => GetMessage( "ACRIT_EXPORTPRO_OZON_FIELD_PICTURE" ),
            "VALUE" => "DETAIL_PICTURE",
            "TYPE" => "field",
        ),
        array(
            "CODE" => "IMAGES",
            "NAME" => GetMessage( "ACRIT_EXPORTPRO_OZON_FIELD_IMAGES" ),
        ),
        array(
            "CODE" => "RELEASEYEAR",
            "NAME" => GetMessage( "ACRIT_EXPORTPRO_OZON_FIELD_RELEASEYEAR" ),
        ),
        array(
            "CODE" => "COUNTRY",
            "NAME" => GetMessage( "ACRIT_EXPORTPRO_OZON_FIELD_COUNTRY" ),
        ),
        array(
            "CODE" => "PACKING",
            "NAME" => GetMessage( "ACRIT_EXPORTPRO_OZON_FIELD_PACKING" ),
        ),
        array(
            "CODE" => "PRODUCER_NAME",
            "NAME" => GetMessage( "ACRIT_EXPORTPRO_OZON_FIELD_PRODUCER_NAME" ),
            "REQUIRED" => "Y",
            "DELETE_ONEMPTY" => "N",
        ),
        array(
            "CODE" => "PRODUCER_COMPANYINFO",
            "NAME" => GetMessage( "ACRIT_EXPORTPRO_OZON_FIELD_PRODUCER_COMPANYINFO" ),
        ),
        array(
            "CODE" => "CAPABILITY_NAME",
            "NAME" => GetMessage( "ACRIT_EXPORTPRO_OZON_FIELD_CAPABILITY_NAME" ),
            "REQUIRED" => "Y",
            "DELETE_ONEMPTY" => "N",
        ),
        array(
            "CODE" => "CAPABILITY_PURPOSE",
            "NAME" => GetMessage( "ACRIT_EXPORTPRO_OZON_FIELD_CAPABILITY_PURPOSE" ),
        ),
        array(
            "CODE" => "CAPABILITY_MATERIAL",
            "NAME" => GetMessage( "ACRIT_EXPORTPRO_OZON_FIELD_CAPABILITY_MATERIAL" ),
        ),
        array(
            "CODE" => "CAPABILITY_AGEMIN",
            "NAME" => GetMessage( "ACRIT_EXPORTPRO_OZON_FIELD_CAPABILITY_AGEMIN" ),
        ),
        array(
            "CODE" => "CAPABILITY_AGEMAX",
            "NAME" => GetMessage( "ACRIT_EXPORTPRO_OZON_FIELD_CAPABILITY_AGEMAX" ),
        ),
        array(
            "CODE" => "CAPABILITY_MAXLOADCOMPLEX",
            "NAME" => GetMessage( "ACRIT_EXPORTPRO_OZON_FIELD_CAPABILITY_MAXLOADCOMPLEX" ),
        ),
        array(
            "CODE" => "CAPABILITY_MAXLOADUNIT",
            "NAME" => GetMessage( "ACRIT_EXPORTPRO_OZON_FIELD_CAPABILITY_MAXLOADUNIT" ),
        ),
        array(
            "CODE" => "CAPABILITY_ACCESSORIES",
            "NAME" => GetMessage( "ACRIT_EXPORTPRO_OZON_FIELD_CAPABILITY_ACCESSORIES" ),
        ),
        array(
            "CODE" => "CAPABILITY_PLAYGROUND",
            "NAME" => GetMessage( "ACRIT_EXPORTPRO_OZON_FIELD_CAPABILITY_PLAYGROUND" ),
        ),
        array(
            "CODE" => "CAPABILITY_LENGTH",
            "NAME" => GetMessage( "ACRIT_EXPORTPRO_OZON_FIELD_CAPABILITY_LENGTH" ),
        ),
        array(
            "CODE" => "CAPABILITY_WIDTH",
            "NAME" => GetMessage( "ACRIT_EXPORTPRO_OZON_FIELD_CAPABILITY_WIDTH" ),
        ),
        array(
            "CODE" => "CAPABILITY_ANNOTATION",
            "NAME" => GetMessage( "ACRIT_EXPORTPRO_OZON_FIELD_CAPABILITY_ANNOTATION" ),
        ),
        array(
            "CODE" => "CAPABILITY_EXTERNALID",
            "NAME" => GetMessage( "ACRIT_EXPORTPRO_OZON_FIELD_CAPABILITY_CAPABILITY_EXTERNALID" ),
            "REQUIRED" => "Y",
            "DELETE_ONEMPTY" => "N",
            "VALUE" => "ID",
            "TYPE" => "field",
        ),
        array(
            "CODE" => "BRAND_NAME",
            "NAME" => GetMessage( "ACRIT_EXPORTPRO_OZON_FIELD_BRAND_NAME" ),
            "REQUIRED" => "Y",
            "DELETE_ONEMPTY" => "N",
        ),
        array(
            "CODE" => "BRAND_COMPANYINFO",
            "NAME" => GetMessage( "ACRIT_EXPORTPRO_OZON_FIELD_BRAND_COMPANYINFO" ),
        ),
        array(
            "CODE" => "SERIA_NAME",
            "NAME" => GetMessage( "ACRIT_EXPORTPRO_OZON_FIELD_SERIA_NAME" ),
        ),
        array(
            "CODE" => "SUBSTANCE",
            "NAME" => GetMessage( "ACRIT_EXPORTPRO_OZON_FIELD_SUBSTANCE" ),
        ),
        array(
            "CODE" => "COLOR_NAME",
            "NAME" => GetMessage( "ACRIT_EXPORTPRO_OZON_FIELD_COLOR_NAME" ),
            "REQUIRED" => "Y",
            "DELETE_ONEMPTY" => "N",
        ),
        array(
            "CODE" => "COLOR_COLOR",
            "NAME" => GetMessage( "ACRIT_EXPORTPRO_OZON_FIELD_COLOR_COLOR" ),
            "REQUIRED" => "Y",
            "DELETE_ONEMPTY" => "N",
        ),
        array(
            "CODE" => "DIMENSIONS",
            "NAME" => GetMessage( "ACRIT_EXPORTPRO_OZON_FIELD_DIMENSIONS" ),
        ),
        array(
            "CODE" => "TDIMENSIONS",
            "NAME" => GetMessage( "ACRIT_EXPORTPRO_OZON_FIELD_TDIMENSIONS" ),
        ),
        array(
            "CODE" => "WARRANTY",
            "NAME" => GetMessage( "ACRIT_EXPORTPRO_OZON_FIELD_WARRANTY" ),
        ),
        array(
            "CODE" => "SCALE",
            "NAME" => GetMessage( "ACRIT_EXPORTPRO_OZON_FIELD_SCALE" ),
        ),
        array(
            "CODE" => "WEIGHT",
            "NAME" => GetMessage( "ACRIT_EXPORTPRO_OZON_FIELD_WEIGHT" ),
        ),
        array(
            "CODE" => "SEX",
            "NAME" => GetMessage( "ACRIT_EXPORTPRO_OZON_FIELD_SEX" ),
        ),
        array(
            "CODE" => "ELEMENTCOUNT",
            "NAME" => GetMessage( "ACRIT_EXPORTPRO_OZON_FIELD_ELEMENTCOUNT" ),
        ),
        array(
            "CODE" => "FROMAGE",
            "NAME" => GetMessage( "ACRIT_EXPORTPRO_OZON_FIELD_FROMAGE" ),
        ),
        array(
            "CODE" => "TOAGE",
            "NAME" => GetMessage( "ACRIT_EXPORTPRO_OZON_FIELD_TOAGE" ),
        ),
        array(
            "CODE" => "ARTICLE",
            "NAME" => GetMessage( "ACRIT_EXPORTPRO_OZON_FIELD_ARTICLE" ),
        ),
        array(
            "CODE" => "RESTRICTION",
            "NAME" => GetMessage( "ACRIT_EXPORTPRO_OZON_FIELD_RESTRICTION" ),
        ),
        array(
            "CODE" => "AGEO",
            "NAME" => GetMessage( "ACRIT_EXPORTPRO_OZON_FIELD_AGEO" ),
        ),
        array(
            "CODE" => "COMPOSITION",
            "NAME" => GetMessage( "ACRIT_EXPORTPRO_OZON_FIELD_COMPOSITION" ),
        ),
        array(
            "CODE" => "SEARCHNAME",
            "NAME" => GetMessage( "ACRIT_EXPORTPRO_OZON_FIELD_SEARCHNAME" ),
        ),
        array(
            "CODE" => "NUM",
            "NAME" => GetMessage( "ACRIT_EXPORTPRO_OZON_FIELD_NUM" ),
        ),
        array(
            "CODE" => "SEASON",
            "NAME" => GetMessage( "ACRIT_EXPORTPRO_OZON_FIELD_SEASON" ),
        ),
        array(
            "CODE" => "ASPECT",
            "NAME" => GetMessage( "ACRIT_EXPORTPRO_OZON_FIELD_ASPECT" ),
        ),
        array(
            "CODE" => "INTERNALCOMMENT",
            "NAME" => GetMessage( "ACRIT_EXPORTPRO_OZON_FIELD_INTERNALCOMMENT" ),
        ),
        array(
            "CODE" => "PACKAGE",
            "NAME" => GetMessage( "ACRIT_EXPORTPRO_OZON_FIELD_PACKAGE" ),
        ),
        array(
            "CODE" => "COMMENT",
            "NAME" => GetMessage( "ACRIT_EXPORTPRO_OZON_FIELD_COMMENT" ),
        ),
        array(
            "CODE" => "MOVIE_NAME",
            "NAME" => GetMessage( "ACRIT_EXPORTPRO_OZON_FIELD_MOVIE_NAME" ),
        ),
        array(
            "CODE" => "MOVIE_SIZE",
            "NAME" => GetMessage( "ACRIT_EXPORTPRO_OZON_FIELD_MOVIE_SIZE" ),
        ),
        array(
            "CODE" => "MOVIE_WIDTH",
            "NAME" => GetMessage( "ACRIT_EXPORTPRO_OZON_FIELD_MOVIE_WIDTH" ),
        ),
        array(
            "CODE" => "MOVIE_HEIGHT",
            "NAME" => GetMessage( "ACRIT_EXPORTPRO_OZON_FIELD_MOVIE_HEIGHT" ),
        ),
        array(
            "CODE" => "MOVIE_DURATIONTIME",
            "NAME" => GetMessage( "ACRIT_EXPORTPRO_OZON_FIELD_MOVIE_DURATIONTIME" ),
        ),
        array(
            "CODE" => "MOVIE_STREAMMOVIE",
            "NAME" => GetMessage( "ACRIT_EXPORTPRO_OZON_FIELD_MOVIE_STREAMMOVIE" ),
        ),
        array(
            "CODE" => "MOVIE_YOUTUBE",
            "NAME" => GetMessage( "ACRIT_EXPORTPRO_OZON_FIELD_MOVIE_YOUTUBE" ),
        ),
    ),
    "FORMAT" => '<?xml version="1.0" encoding="#ENCODING#"?>
<Products>
    #ITEMS#
</Products>',
);

$bCatalog = false;
if( CModule::IncludeModule( "catalog" ) ){
    $arBasePrice = CCatalogGroup::GetBaseGroup();
    $basePriceCode = "CATALOG-PRICE_".$arBasePrice["ID"];
    $basePriceCodeWithDiscount = "CATALOG-PRICE_".$arBasePrice["ID"]."_WD";
    $bCatalog = true;

    $profileTypes["ozon"]["FIELDS"][8] = array(
        "CODE" => "SELLING_PRICE",
        "NAME" => GetMessage( "ACRIT_EXPORTPRO_OZON_FIELD_SELLING_PRICE" ),
        "TYPE" => "field",
        "VALUE" => $basePriceCode,
    );
}

$profileTypes["ozon"]["PORTAL_REQUIREMENTS"] = GetMessage( "ACRIT_EXPORTPRO_TYPE_OZON_PORTAL_REQUIREMENTS" );
$profileTypes["ozon"]["PORTAL_VALIDATOR"] = GetMessage( "ACRIT_EXPORTPRO_TYPE_OZON_PORTAL_VALIDATOR" );
$profileTypes["ozon"]["EXAMPLE"] = GetMessage( "ACRIT_EXPORTPRO_TYPE_OZON_EXAMPLE" );
$profileTypes["ozon"]["SCHEME_OFFER_DESCRIPTION"] = GetMessage( "ACRIT_EXPORTPRO_TYPE_OZON_SCHEME_DESCRIPTION" );

$profileTypes["ozon"]["CURRENCIES"] =
    "<currency id='#CURRENCY#' rate='#RATE#' plus='#PLUS#'></currency>".PHP_EOL;

$profileTypes["ozon"]["SECTIONS"] =
    "<category id='#ID#'>#NAME#</category>".PHP_EOL;

$profileTypes["ozon"]["ITEMS_FORMAT"] = '
<Product MerchantSKU="#ID#" ProductTypeID="#MARKET_CATEGORY#">
    <SKU>
        <Name>#NAME#</Name>
        <ManufacturerIdentifier>#MANUFACTURER_IDENTIFIER#</ManufacturerIdentifier>
        <GrossWeight>#GROSS_WEIGHT#</GrossWeight>
        <InternalName>#INTERNAL_NAME#</InternalName>
    </SKU>
    <Price>
        <SellingPrice>#SELLING_PRICE#</SellingPrice>
        <Discount>#DISCOUNT#</Discount>
    </Price>
    <Availability>
        <SellingState>#SELLING_STATE#</SellingState>
        <SupplyPeriod>#SUPPLY_PERIOD#</SupplyPeriod>
        <Qty>#QTY#</Qty>
    </Availability>
    <Description>
        <Name>#NAME#</Name>
        <AlterName>#ALTERNAME#</AlterName>
        <ElucidativeName>#ELUCIDATIVENAME#</ElucidativeName>
        <StickerInfo>#STICKERINFO#</StickerInfo>
        <Annotation>#ANNOTATION#</Annotation>
        <Picture>#SITE_URL##PICTURE#</Picture>
        <Images>#SITE_URL##IMAGES#</Images>
        <ReleaseYear>#RELEASEYEAR#</ReleaseYear>
        <Country>#COUNTRY#</Country>
        <Packing>#PACKING#</Packing>
        <Producer>
            <Name>#PRODUCER_NAME#</Name>
            <CompanyInfo>#PRODUCER_COMPANYINFO#</CompanyInfo>
        </Producer>
        <Capability>
            <Type>#CAPABILITY_TYPE#</Type>
            <Name>#CAPABILITY_NAME#</Name>
            <Purpose>#CAPABILITY_PURPOSE#</Purpose>
            <Material>#CAPABILITY_MATERIAL#</Material>
            <AgeMin>#CAPABILITY_AGEMIN#</AgeMin>
            <AgeMax>#CAPABILITY_AGEMAX#</AgeMax>
            <MaxLoadComplex>#CAPABILITY_MAXLOADCOMPLEX#</MaxLoadComplex>
            <MaxLoadUnit>#CAPABILITY_MAXLOADUNIT#</MaxLoadUnit>
            <Accessories>#CAPABILITY_ACCESSORIES#</Accessories>
            <Playground>#CAPABILITY_PLAYGROUND#</Playground>
            <Annotation>#CAPABILITY_ANNOTATION#</Annotation>
            <ExternalID>#CAPABILITY_EXTERNALID#</ExternalID>
            <Length>#CAPABILITY_LENGTH#</Length>
            <Width>#CAPABILITY_WIDTH#</Width>
        </Capability>
        <Brand>
            <Name>#BRAND_NAME#</Name>
            <CompanyInfo>#BRAND_COMPANYINFO#</CompanyInfo>
        </Brand>
        <Seria>
            <Name>#SERIA_NAME#</Name>
        </Seria>
        <Substance>#SUBSTANCE#</Substance>
        <Color>
            <Name>#COLOR_NAME#</Name>
            <Color>#COLOR_COLOR#</Color>
        </Color>
        <Warranty>#WARRANTY#</Warranty>
        <Dimensions>#DIMENSIONS#</Dimensions>
        <TDimensions>#TDIMENSIONS#</TDimensions>
        <Scale>#SCALE#</Scale>
        <Weight>#WEIGHT#</Weight>
        <Sex>#SEX#</Sex>
        <ElementCount>#ELEMENTCOUNT#</ElementCount>
        <FromAge>#FROMAGE#</FromAge>
        <ToAge>#TOAGE#</ToAge>
        <Article>#ARTICLE#</Article>
        <Restriction>#RESTRICTION#</Restriction>
        <AgeO>#AGEO#</AgeO>
        <Composition>#COMPOSITION#</Composition>
        <SearchName>#SEARCHNAME#</SearchName>
        <Num>#NUM#</Num>
        <Season>#SEASON#</Season>
        <Aspect>#ASPECT#</Aspect>
        <InternalComment>#INTERNALCOMMENT#</InternalComment>
        <Package>#PACKAGE#</Package>
        <Comment>#COMMENT#</Comment>
        <Movie>
            <Name>#MOVIE_NAME#</Name>
            <Size>#MOVIE_SIZE#</Size>
            <Width>#MOVIE_WIDTH#</Width>
            <Height>#MOVIE_HEIGHT#</Height>
            <DurationTime>#MOVIE_DURATIONTIME#</DurationTime>
            <StreamMovie>#MOVIE_STREAMMOVIE#</StreamMovie>
            <YouTube>#MOVIE_YOUTUBE#</YouTube>
        </Movie>
    </Description>
</Product>';