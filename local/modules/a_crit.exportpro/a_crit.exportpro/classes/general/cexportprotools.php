<?php

use Bitrix\Main;
use Bitrix\Main\Localization\Loc;
use Bitrix\Catalog;

\Bitrix\Main\Loader::includeModule( "iblock" );
\Bitrix\Main\Loader::includeModule( "a_crit.exportpro" );

Loc::loadMessages( __FILE__ );

class CAcritExportproTools{
    private $iblockIncluded = false;
    protected static $arSectionTreeCache = array();

    public function __construct(){
        $this->iblockIncluded = @CModule::IncludeModule( "iblock" );
    }

    public function GetHttpHost(){
        $arHttpHost = explode( ":", $_SERVER["HTTP_HOST"] );
        return $arHttpHost[0];
    }

    public function RoundNumber( $number, $precision, $mode, $precision_default = false ){
        switch( $mode ){
            case "UP":
                $mode = PHP_ROUND_HALF_UP;
                break;
            case "DOWN":
                $mode = PHP_ROUND_HALF_DOWN;
                break;
            case "EVEN":
                $mode = PHP_ROUND_HALF_EVEN;
                break;
            case "ODD":
                $mode = PHP_ROUND_HALF_ODD;
                break;
            default:
                $mode = PHP_ROUND_HALF_UP;
                break;
        }

        if( !is_numeric( $number ) && !is_float( $number ) ){
            return $number;
        }

        if( is_numeric( $precision ) ){
            return round( $number, abs( $precision ), $mode );
        }
        elseif( $precision_default !== false ){
            return round( $number, abs( $precision_default ), $mode );
        }

        return $number;
    }

    public function BitrixRoundNumber( $priceValue, $priceCode ){
        \Bitrix\Main\Loader::includeModule( "catalog" );

        $resultPrice = false;
        $arPriceCode = explode( "_", $priceCode );
        $arBitrixRoundRules = \Bitrix\Catalog\Product\Price::getRoundRules( $arPriceCode[1] );
        $resultPrice = Catalog\Product\Price::roundValue( $priceValue, $arBitrixRoundRules[0]["ROUND_PRECISION"], $arBitrixRoundRules[0]["ROUND_TYPE"] );

        return $resultPrice;
    }

    private function ArrayMultiply( &$arResult, $arTuple, $arTemp = array() ){
        if( $arTuple ){
            reset( $arTuple );
            list( $key, $head ) = each( $arTuple );
            unset( $arTuple[$key] );
            $arTemp[$key] = false;
            if( is_array( $head ) ){
                if( empty( $head ) ){
                    if( empty( $arTuple ) )
                        $arResult[] = $arTemp;
                    else
                        self::ArrayMultiply( $arResult, $arTuple, $arTemp );
                }
                else{
                    foreach( $head as $value ){
                        $arTemp[$key] = $value;
                        if( empty( $arTuple ) )
                            $arResult[] = $arTemp;
                        else
                            self::ArrayMultiply( $arResult, $arTuple, $arTemp );
                    }
                }
            }
            else{
                $arTemp[$key] = $head;
                if( empty( $arTuple ) )
                    $arResult[] = $arTemp;
                else
                    self::ArrayMultiply( $arResult, $arTuple, $arTemp );
            }
        }
        else{
            $arResult[] = $arTemp;
        }
    }

    public function ExportArrayMultiply( &$arResult, $arTuple, $arTemp = array() ){
        if( count( $arTuple ) == 0 ){
            $arResult[] = $arTemp;
        }
        else{
            $head = array_shift( $arTuple );
            $arTemp[] = false;
            if( is_array( $head ) ){
                if( empty( $head ) ){
                    $arTemp[count( $arTemp ) - 1] = "";
                    self::ArrayMultiply( $arResult, $arTuple, $arTemp );
                }
                else{
                    foreach( $head as $key => $value ){
                        $arTemp[count( $arTemp ) - 1] = $value;
                        self::ExportArrayMultiply( $arResult, $arTuple, $arTemp );
                    }
                }
            }
            else{
                $arTemp[count( $arTemp ) - 1] = $head;
                self::ExportArrayMultiply( $arResult, $arTuple, $arTemp );
            }
        }
    }

    /*public function ExportArrayMultiply( &$arResult, $arTuple, $arTemp = array() ){
        if( empty( $arTuple ) ){
            $arResult[] = $arTemp;
        }
        else{
            $head = array_shift( $arTuple );
            $arTemp[] = false;
            if( is_array( $head ) ){
                if( empty( $head ) ){
                    $arTemp[count( $arTemp ) - 1] = "";
                    self::ExportArrayMultiply( $arResult, $arTuple, $arTemp );
                }
                else{
                    foreach( $head as &$value ){
                        $arTemp[count( $arTemp ) - 1] = $value;
                        self::ExportArrayMultiply( $arResult, $arTuple, $arTemp );
                    }
                    if( isset( $value ) ){
                        unset( $value );
                    }
                }
            }
            else{
                $arTemp[count( $arTemp ) - 1] = $head;
                self::ExportArrayMultiply( $arResult, $arTuple, $arTemp );
            }
        }
    }*/

    public function GetYandexDateTime( $dateTime ){
        global $DB;
        $resultTime = false;

        $localTime = new DateTime();
        $dateTimeZoneDiff = $localTime->getOffset() / 3600;

        $dateTimeZone = ( ( intval( $dateTimeZoneDiff ) > 0 ) ? "+" : "-" ).date( "H:i", mktime( $dateTimeZoneDiff, 0, 0, 0, 0, 0 ) );

        $dateTimeValue = MakeTimeStamp( $dateTime );
        $dateTimeFormattedValue = date( "Y-m-d", $dateTimeValue )."T".date( "H:i:s", $dateTimeValue );

        $resultTime = $dateTimeFormattedValue.$dateTimeZone;

        return $resultTime;
    }

    public function GetIblockUserFields( $iblockId ){
        $result = false;
        $dbSectionUserFields = CUserTypeEntity::GetList(
            array(),
            array(
                "ENTITY_ID" => "IBLOCK_".$iblockId."_SECTION",
                "LANG" => LANGUAGE_ID
            )
        );

        while( $arSectionUserFields = $dbSectionUserFields->Fetch() ){
            if( !$result ) $result = array();
            $result[] = $arSectionUserFields;
        }

        return $result;
    }

    public static function GetSectionTree( $IBLOCK_ID, $SECTION_ID, $arSelect = array() ){
        global $DB, $DBHost, $DBName, $DBLogin, $DBPassword;

        $IBLOCK_ID = (int)$IBLOCK_ID;

        $arFields = array(
            "ID" => "BS.ID",
            "CODE" => "BS.CODE",
            "XML_ID" => "BS.XML_ID",
            "EXTERNAL_ID" => "BS.XML_ID",
            "IBLOCK_ID" => "BS.IBLOCK_ID",
            "IBLOCK_SECTION_ID" => "BS.IBLOCK_SECTION_ID",
            "SORT" => "BS.SORT",
            "NAME" => "BS.NAME",
            "ACTIVE" => "BS.ACTIVE",
            "GLOBAL_ACTIVE" => "BS.GLOBAL_ACTIVE",
            "PICTURE" => "BS.PICTURE",
            "DESCRIPTION" => "BS.DESCRIPTION",
            "DESCRIPTION_TYPE" => "BS.DESCRIPTION_TYPE",
            "LEFT_MARGIN" => "BS.LEFT_MARGIN",
            "RIGHT_MARGIN" => "BS.RIGHT_MARGIN",
            "DEPTH_LEVEL" => "BS.DEPTH_LEVEL",
            "SEARCHABLE_CONTENT" => "BS.SEARCHABLE_CONTENT",
            "MODIFIED_BY" => "BS.MODIFIED_BY",
            "CREATED_BY" => "BS.CREATED_BY",
            "DETAIL_PICTURE" => "BS.DETAIL_PICTURE",
            "TMP_ID" => "BS.TMP_ID",

            "LIST_PAGE_URL" => "B.LIST_PAGE_URL",
            "SECTION_PAGE_URL" => "B.SECTION_PAGE_URL",
            "IBLOCK_TYPE_ID" => "B.IBLOCK_TYPE_ID",
            "IBLOCK_CODE" => "B.CODE",
            "IBLOCK_EXTERNAL_ID" => "B.XML_ID",
            "SOCNET_GROUP_ID" => "BS.SOCNET_GROUP_ID",
        );

        $arSqlSelect = array();
        foreach( $arSelect as $field ){
            $field = strtoupper( $field );
            if( isset( $arFields[$field] ) ){
                $arSqlSelect[$field] = $arFields[$field]." AS ".$field;
            }
        }

        if( isset( $arSqlSelect["DESCRIPTION"] ) ){
            $arSqlSelect["DESCRIPTION_TYPE"] = $arFields["DESCRIPTION_TYPE"]." AS DESCRIPTION_TYPE";
        }

        if( isset( $arSqlSelect["LIST_PAGE_URL"] ) || isset( $arSqlSelect["SECTION_PAGE_URL"] ) ){
            $arSqlSelect["ID"] = $arFields["ID"]." AS ID";
            $arSqlSelect["CODE"] = $arFields["CODE"]." AS CODE";
            $arSqlSelect["EXTERNAL_ID"] = $arFields["EXTERNAL_ID"]." AS EXTERNAL_ID";
            $arSqlSelect["IBLOCK_TYPE_ID"] = $arFields["IBLOCK_TYPE_ID"]." AS IBLOCK_TYPE_ID";
            $arSqlSelect["IBLOCK_ID"] = $arFields["IBLOCK_ID"]." AS IBLOCK_ID";
            $arSqlSelect["IBLOCK_CODE"] = $arFields["IBLOCK_CODE"]." AS IBLOCK_CODE";
            $arSqlSelect["IBLOCK_EXTERNAL_ID"] = $arFields["IBLOCK_EXTERNAL_ID"]." AS IBLOCK_EXTERNAL_ID";
            $arSqlSelect["GLOBAL_ACTIVE"] = $arFields["GLOBAL_ACTIVE"]." AS GLOBAL_ACTIVE";
        }

        if( !empty( $arSelect ) ){
            $field = "IBLOCK_SECTION_ID";
            $arSqlSelect[$field] = $arFields[$field]." AS ".$field;
            $strSelect = implode( ", ", $arSqlSelect );
        }
        else{
            $strSelect = "
                BS.*,
                B.LIST_PAGE_URL,
                B.SECTION_PAGE_URL,
                B.IBLOCK_TYPE_ID,
                B.CODE as IBLOCK_CODE,
                B.XML_ID as IBLOCK_EXTERNAL_ID,
                BS.XML_ID as EXTERNAL_ID
            ";
        }

        $key = md5( $strSelect );
        if( !isset( self::$arSectionTreeCache[$key] ) ){
            self::$arSectionTreeCache[$key] = array();
        }

        $sectionPath = array();
        do{
            $SECTION_ID = (int)$SECTION_ID;

            if( !isset( self::$arSectionTreeCache[$key][$SECTION_ID] ) ){
                $queryBox = "SELECT
                                    ".$strSelect."
                                FROM
                                    b_iblock_section BS
                                    INNER JOIN b_iblock B ON B.ID = BS.IBLOCK_ID
                                WHERE BS.ID=".$SECTION_ID."
                                    ".($IBLOCK_ID > 0 ? "AND BS.IBLOCK_ID=".$IBLOCK_ID : "")."
                            ";

                $rsSection = $DB->Query( $queryBox, true );
                if( $DB->GetErrorMessage() != "" ){
                    $DB->Disconnect();
                    if( $DB->Connect( $DBHost, $DBName, $DBLogin, $DBPassword ) && $DB->DoConnect() ){
                        $rsSection = $DB->Query( $queryBox, true );
                    }
                }

                self::$arSectionTreeCache[$key][$SECTION_ID] = $rsSection->Fetch();
            }

            if( self::$arSectionTreeCache[$key][$SECTION_ID] ){
                $sectionPath[] = self::$arSectionTreeCache[$key][$SECTION_ID];
                $SECTION_ID = self::$arSectionTreeCache[$key][$SECTION_ID]["IBLOCK_SECTION_ID"];
            }
            else{
                $SECTION_ID = 0;
            }
        } while( $SECTION_ID > 0 );

        $res = new CDBResult;
        $res->InitFromArray( array_reverse( $sectionPath ) );
        $res = new CIBlockResult( $res );
        $res->bIBlockSection = true;
        return $res;
    }

    public function CheckCondition( $arItem, $code ){
        unset( $GLOBALS["CHECK_COND"] );
        if( is_array( $arItem["SECTION_ID"] ) && is_array( $arItem["SECTION_PARENT_ID"] ) )
            $arItem["SECTION_ID"] = array_merge( $arItem["SECTION_ID"], $arItem["SECTION_PARENT_ID"] );

        $GLOBALS["CHECK_COND"] = $arItem;

        return eval( "return $code;" );
    }

    public function GetStringCharset( $str ){
        $resEncoding = "cp1251";

        if( preg_match( "#.#u", $str ) ){
            $resEncoding = "utf8";
        }

        return $resEncoding;
    }

    public function GetSectionNavChain( $sectionId ){
        static $arResult = null;
        if( !is_null( $arResult ) )
            return $arResult;

        $arResult = array();

        $dbSectionList = CIBlockSection::GetNavChain(
            false,
            $sectionId
        );

        while( $arSection = $dbSectionList->GetNext() ){
            $arResult[] = $arSection["ID"];
        }

        return $arResult;
    }

    public function ProcessMarketCategoriesOnEmpty( &$arCategoryList ){
        $bNotEmptyMarketCategoryListValue = false;

        $temp = array();
        if( is_array( $arCategoryList ) && !empty( $arCategoryList ) ){
            foreach( $arCategoryList as $k => $v ){
                $bNotEmptyCurrentMarketCategoryListValue = false;
                if( strlen( trim( $v ) ) > 0 ){
                    if( !$bNotEmptyMarketCategoryListValue ){
                        $bNotEmptyMarketCategoryListValue = true;
                    }
                    $bNotEmptyCurrentMarketCategoryListValue = true;
                }

                if( $bNotEmptyCurrentMarketCategoryListValue ){
                    $rsParentSection = CIBlockSection::GetByID( $k );
                    if( $arParentSection = $rsParentSection->GetNext() ){
                        $temp[$k] = $arParentSection["IBLOCK_SECTION_ID"];

                        $arFilter = array(
                            "IBLOCK_ID" => $arParentSection["IBLOCK_ID"],
                            ">LEFT_MARGIN" => $arParentSection["LEFT_MARGIN"],
                            "<RIGHT_MARGIN" => $arParentSection["RIGHT_MARGIN"],
                            ">DEPTH_LEVEL" => $arParentSection["DEPTH_LEVEL"]
                        );

                        $rsSect = CIBlockSection::GetList(
                            array(
                                "left_margin" => "asc"
                            ),
                            $arFilter,
                            false,
                            array(
                                "ID",
                                "IBLOCK_SECTION_ID"
                            )
                        );

                        while( $arSect = $rsSect->GetNext() ){
                            $temp[$arSect["ID"]] = $arSect["IBLOCK_SECTION_ID"];
                        }
                    }
                }
            }
        }
        if( $bNotEmptyMarketCategoryListValue ){
            $maxDepth = 0;
            $i = 0;
            $rsSect = CIBlockSection::GetList( array( "DEPTH_LEVEL" => "DESC" ), array( ">IBLOCK_ID" => 0 ), false, array( "DEPTH_LEVEL" ) );
            while( $arSect = $rsSect->GetNext() ){
                $i++;
                $maxDepth = $arSect["DEPTH_LEVEL"];
                if( $i == 1 ){
                    break;
                }
            }

            foreach( $temp as $k => $v ){
                $tempCatName = "";
                if( $arCategoryList[$k] ){
                    $tempCatName = $arCategoryList[$k];
                }

                $j = 0;
                while( $j++ < $maxDepth ){
                    foreach( $temp as $k_ => $v_ ){
                        if( $v_ == $k ){
                            if( $arCategoryList[$k_] ){
                                $tempCatName = $arCategoryList[$k_];
                            }
                            else{
                                if( $tempCatName ){
                                    $arCategoryList[$k_] = $tempCatName;
                                }
                            }
                        }
                    }
                }
            }
        }
        unset( $temp );

        return $bNotEmptyMarketCategoryListValue;
    }

    public function NormalisePath( $path ){
        $arPathParts = explode( "/", $path );
        $arSafe = array();
        foreach( $arPathParts as $idx => $pathPart ){
            if( empty( $pathPart ) || ( "." == $pathPart ) ){
                continue;
            }
            elseif( ".." == $pathPart ){
                array_pop( $arSafe );
                continue;
            }
            else{
                $arSafe[] = $pathPart;
            }
        }

        $path = "/".implode( "/", $arSafe );
        return $path;
    }

    public function GetSiteDocumentRoot( $siteId ){
        $result = false;

        $dbSite = CSite::GetByID( $siteId );
        if( ( $arSite = $dbSite->Fetch() ) && ( strlen( $arSite["DOC_ROOT"] ) > 0 ) ){
            $result = $arSite["DOC_ROOT"];
        }

        return $result;
    }

    public function GetFilePath( $fileId ){
        $result = false;

        global $DB;
        $strSql = "SELECT f.*,".$DB->DateToCharFunction( "f.TIMESTAMP_X" )." as TIMESTAMP_X FROM b_file f WHERE f.ID=".$fileId;
        $dbFile = $DB->Query( $strSql, false, "FILE: ".__FILE__."<br>LINE: ".__LINE__ );
        $result = CFile::GetFileSRC( $dbFile->Fetch() );

        return $result;
    }

    public function ArrayValidate( $arData ){
        $result = false;

        if( isset( $arData ) && is_array( $arData ) && !empty( $arData ) ){
            $result = true;
        }

        return $result;
    }

    public function GetProcessPriceId( $arProfile ){
        $result = false;
        if( self::ArrayValidate( $arProfile["XMLDATA"] ) ){
            foreach( $arProfile["XMLDATA"] as $arXmlItem ){
                if( $arXmlItem["CODE"] == "PRICE" ){
                    preg_match( "#[\d]+#", $arXmlItem["VALUE"], $arPriceId );
                    if( isset( $arPriceId[0] ) && !empty( $arPriceId[0] ) ){
                        $result = $arPriceId[0];
                    }
                }
            }
        }

        return $result;
    }

    public function GetSections( $arProfile ){
        $arSessionData = AcritExportproSession::GetAllSession( $arProfile["ID"] );
        $sessionData = array();
        if( !empty( $arSessionData ) ){
            $sessionData = $arSessionData[0];
            if( !is_array( $sessionData["EXPORTPRO"][$arProfile["ID"]]["CATEGORY"] ) )
                $sessionData["EXPORTPRO"][$arProfile["ID"]]["CATEGORY"] = array();

            unset( $arSessionData[0] );
            foreach( $arSessionData as $sData ){
                if( is_array( $sData["EXPORTPRO"][$arProfile["ID"]]["CATEGORY"] ) ){
                    $sessionData["EXPORTPRO"][$arProfile["ID"]]["CATEGORY"] = array_merge(
                        $sessionData["EXPORTPRO"][$arProfile["ID"]]["CATEGORY"],
                        $sData["EXPORTPRO"][$arProfile["ID"]]["CATEGORY"]
                    );
                }
            }
        }
        return array_unique( $sessionData["EXPORTPRO"][$arProfile["ID"]]["CATEGORY"] );
    }

    public function GetCurrencies( $arProfile ){
        $arSessionData = AcritExportproSession::GetAllSession( $arProfile["ID"] );
        $sessionData = array();
        if( !empty( $arSessionData ) ){
            $sessionData = $arSessionData[0];
            if( !is_array( $sessionData["EXPORTPRO"][$arProfile["ID"]]["CURRENCY"] ) )
                $sessionData["EXPORTPRO"][$arProfile["ID"]]["CURRENCY"] = array();

            unset( $arSessionData[0] );
            foreach( $arSessionData as $sData ){
                if( is_array( $sData["EXPORTPRO"][$arProfile["ID"]]["CURRENCY"] ) ){
                    $sessionData["EXPORTPRO"][$arProfile["ID"]]["CURRENCY"] = array_merge(
                        $sessionData["EXPORTPRO"][$arProfile["ID"]]["CURRENCY"],
                        $sData["EXPORTPRO"][$arProfile["ID"]]["CURRENCY"]
                    );
                }
            }
        }
        return array_unique( array_filter( $sessionData["EXPORTPRO"][$arProfile["ID"]]["CURRENCY"] ) );
    }

    public function SaveSections( $arProfile, $sections ){
        if( is_array( $sections ) ){
            $sessionData = AcritExportproSession::GetSession( $arProfile["ID"] );

            if( !is_array( $sessionData["EXPORTPRO"][$arProfile["ID"]]["CATEGORY"] ) )
                $sessionData["EXPORTPRO"][$arProfile["ID"]]["CATEGORY"] = array();

            $sessionData["EXPORTPRO"][$arProfile["ID"]]["CATEGORY"] = array_merge(
                $sessionData["EXPORTPRO"][$arProfile["ID"]]["CATEGORY"],
                $sections
            );
            AcritExportproSession::SetSession( $arProfile["ID"], $sessionData );
        }
    }

    public function SaveCurrencies( $arProfile, $currencies ){
        if( is_array( $currencies ) ){
            $sessionData = AcritExportproSession::GetSession( $arProfile["ID"] );
            if( !is_array( $sessionData["EXPORTPRO"][$arProfile["ID"]]["CURRENCY"] ) )
                $sessionData["EXPORTPRO"][$arProfile["ID"]]["CURRENCY"] = array();

            $sessionData["EXPORTPRO"][$arProfile["ID"]]["CURRENCY"] = array_merge(
                $sessionData["EXPORTPRO"][$arProfile["ID"]]["CURRENCY"],
                $currencies
            );
            AcritExportproSession::SetSession( $arProfile["ID"], $sessionData );
        }
    }

    public function GetProperties( $arItem, $arFilter ){
        $props = CIBlockElement::GetProperty( $arItem["IBLOCK_ID"], $arItem["ID"], array(), $arFilter );

        $arAllProps = array();
        while( $arProp = $props->Fetch() ){
            if( strlen( trim( $arProp["CODE"] ) ) > 0 )
                $PIND = $arProp["CODE"];
            else
                $PIND = $arProp["ID"];

            $arProp["ORIGINAL_VALUE"] = $arProp["VALUE"];

            if( $arProp["PROPERTY_TYPE"] == "L" ){
                if( $arProp["MULTIPLE"] != "Y" )
                    $arProp["ORIGINAL_VALUE"] = array( $arProp["ORIGINAL_VALUE"] );
                $arProp["VALUE_ENUM_ID"] = $arProp["VALUE"];
                $arProp["VALUE"] = $arProp["VALUE_ENUM"];
            }

            if( is_array( $arProp["VALUE"] ) || ( strlen( $arProp["VALUE"] ) > 0 ) ){
                $arProp["~VALUE"] = $arProp["VALUE"];
                if( is_array( $arProp["VALUE"] ) || preg_match( "/[;&<>\"]/", $arProp["VALUE"] ) )
                    $arProp["VALUE"] = htmlspecialcharsex( $arProp["VALUE"] );
                $arProp["~DESCRIPTION"] = $arProp["DESCRIPTION"];
                if( preg_match( "/[;&<>\"]/", $arProp["DESCRIPTION"] ) )
                    $arProp["DESCRIPTION"] = htmlspecialcharsex( $arProp["DESCRIPTION"] );
            }
            else{
                $arProp["VALUE"] = $arProp["~VALUE"] = "";
                $arProp["DESCRIPTION"] = $arProp["~DESCRIPTION"] = "";
            }

            if( $arProp["MULTIPLE"] == "Y" ){
                if( array_key_exists( $PIND, $arAllProps ) ){
                    $arTemp = &$arAllProps[$PIND];
                    if( $arProp["VALUE"] !== "" ){
                        if( is_array( $arTemp["VALUE"] ) ){
                            $arTemp["ORIGINAL_VALUE"][] = $arProp["ORIGINAL_VALUE"];
                            $arTemp["VALUE"][] = $arProp["VALUE"];
                            $arTemp["~VALUE"][] = $arProp["~VALUE"];
                            $arTemp["DESCRIPTION"][] = $arProp["DESCRIPTION"];
                            $arTemp["~DESCRIPTION"][] = $arProp["~DESCRIPTION"];
                            $arTemp["PROPERTY_VALUE_ID"][] = $arProp["PROPERTY_VALUE_ID"];
                            if( $arProp["PROPERTY_TYPE"] == "L" ){
                                $arTemp["VALUE_ENUM_ID"][] = $arProp["VALUE_ENUM_ID"];
                                $arTemp["VALUE_ENUM"][] = $arProp["VALUE_ENUM"];
                                $arTemp["VALUE_XML_ID"][] = $arProp["VALUE_XML_ID"];
                            }
                        }
                        else{
                            $arTemp["ORIGINAL_VALUE"] = array( $arProp["ORIGINAL_VALUE"] );
                            $arTemp["VALUE"] = array( $arProp["VALUE"] );
                            $arTemp["~VALUE"] = array( $arProp["~VALUE"] );
                            $arTemp["DESCRIPTION"] = array( $arProp["DESCRIPTION"] );
                            $arTemp["~DESCRIPTION"] = array( $arProp["~DESCRIPTION"] );
                            $arTemp["PROPERTY_VALUE_ID"] = array( $arProp["PROPERTY_VALUE_ID"] );
                            if( $arProp["PROPERTY_TYPE"] == "L" ){
                                $arTemp["VALUE_ENUM_ID"] = array( $arProp["VALUE_ENUM_ID"] );
                                $arTemp["VALUE_ENUM"] = array( $arProp["VALUE_ENUM"] );
                                $arTemp["VALUE_XML_ID"] = array( $arProp["VALUE_XML_ID"] );
                                $arTemp["VALUE_SORT"] = array( $arProp["VALUE_SORT"] );
                                $arTemp["ORIGINAL_VALUE"] = array( $arProp["ORIGINAL_VALUE"] );
                            }
                        }
                    }
                }
                else{
                    $arProp["~NAME"] = $arProp["NAME"];
                    if( preg_match( "/[;&<>\"]/", $arProp["NAME"] ) )
                        $arProp["NAME"] = htmlspecialcharsex( $arProp["NAME"] );

                    $arProp["~DEFAULT_VALUE"] = $arProp["DEFAULT_VALUE"];

                    if( is_array( $arProp["DEFAULT_VALUE"] ) || preg_match( "/[;&<>\"]/", $arProp["DEFAULT_VALUE"] ) )
                        $arProp["DEFAULT_VALUE"] = htmlspecialcharsex( $arProp["DEFAULT_VALUE"] );

                    if( $arProp["VALUE"] !== "" ){
                        $arProp["ORIGINAL_VALUE"] = array( $arProp["ORIGINAL_VALUE"] );
                        $arProp["VALUE"] = array( $arProp["VALUE"] );
                        $arProp["~VALUE"] = array( $arProp["~VALUE"] );
                        $arProp["DESCRIPTION"] = array( $arProp["DESCRIPTION"] );
                        $arProp["~DESCRIPTION"] = array( $arProp["~DESCRIPTION"] );
                        $arProp["PROPERTY_VALUE_ID"] = array( $arProp["PROPERTY_VALUE_ID"] );
                        if( $arProp["PROPERTY_TYPE"] == "L" ){
                            $arProp["VALUE_ENUM_ID"] = array( $arProp["VALUE_ENUM_ID"] );
                            $arProp["VALUE_ENUM"] = array( $arProp["VALUE_ENUM"] );
                            $arProp["VALUE_XML_ID"] = array( $arProp["VALUE_XML_ID"] );
                            $arProp["VALUE_SORT"] = array( $arProp["VALUE_SORT"] );
                        }
                    }
                    else{
                        $arProp["ORIGINAL_VALUE"] = false;
                        $arProp["VALUE"] = false;
                        $arProp["~VALUE"] = false;
                        $arProp["DESCRIPTION"] = false;
                        $arProp["~DESCRIPTION"] = false;
                        $arProp["PROPERTY_VALUE_ID"] = false;
                        if( $arProp["PROPERTY_TYPE"] == "L" ){
                            $arProp["VALUE_ENUM_ID"] = false;
                            $arProp["VALUE_ENUM"] = false;
                            $arProp["VALUE_XML_ID"] = false;
                            $arProp["VALUE_SORT"] = false;
                        }
                    }
                    $arAllProps[$PIND] = $arProp;
                }
            }
            else{
                $arProp["~NAME"] = $arProp["NAME"];

                if( preg_match( "/[;&<>\"]/", $arProp["NAME"] ) )
                    $arProp["NAME"] = htmlspecialcharsex( $arProp["NAME"] );

                $arProp["~DEFAULT_VALUE"] = $arProp["DEFAULT_VALUE"];

                if( is_array( $arProp["DEFAULT_VALUE"] ) || preg_match( "/[;&<>\"]/", $arProp["DEFAULT_VALUE"] ) )
                    $arProp["DEFAULT_VALUE"] = htmlspecialcharsex( $arProp["DEFAULT_VALUE"] );

                $arAllProps[$PIND] = $arProp;
            }
        }

        return $arAllProps;
    }

    public function isVariant( $arProfile, $categoryId = false ){
        if( $categoryId ){
            return ( ( $arProfile["USE_VARIANT"] == "Y" )
                && ( $arProfile["TYPE"] == "activizm" )
                && ( $arProfile["VARIANT"]["CATEGORY"][$categoryId] ) );
        }
        return ( ( $arProfile["USE_VARIANT"] == "Y" ) && ( $arProfile["TYPE"] == "activizm" ) );
    }

    public function GetProfileMarketCategoryType( $type ){
        switch( $type ){
            case "tiu_standart":
            case "tiu_standart_vendormodel":
                return "CExportproMarketTiuDB";
                break;
            case "ua_prom_ua":
                return "CExportproMarketPromuaDB";
	            break;
	        case "mailru":
	        case "mailru_clothing":
		        return "CExportproMarketMailruDB";
		        break;
        }
    }

    public function HasBadCronExports( $arProfile ){
        $result = false;

        $arPath = explode( "/", $arProfile["SETUP"]["URL_DATA_FILE"] );
        $exportPath = ( $arPath[1] == "a_crit.exportpro" ) ? $_SERVER["DOCUMENT_ROOT"]."/upload/".$arPath[1]."/" : $_SERVER["DOCUMENT_ROOT"]."/".$arPath[1]."/";
        if( $handle = opendir( $exportPath ) ){
            while( false !== ( $file = readdir( $handle ) ) ){
                if( stripos( $file, $arPath[2].".part" ) !== false ){
                    $result = true;
                    break;
                }
            }
            closedir( $handle );
        }

        return $result;
    }

    public function ClearExportSession( $profileId ){
        AcritExportproSession::DeleteSession( $profileId, $_SERVER["DOCUMENT_ROOT"]."/local/tools/a_crit.exportpro/" );
    }

    public function ParseMultiproFormat( $arProcessValues, $arProfile, $fieldCode ){
        $arProcessValuesSelectedMultiprop = array();

        $arSelectedMultipropIndexes = array();
        $fieldMultipropFormat = trim( $arProfile["XMLDATA"][$fieldCode]["MULTIPROP_FORMAT"] );
        if( strlen( $fieldMultipropFormat ) > 0 ){
            $arFieldMultipropFormatParts = explode( ",", $fieldMultipropFormat );

            if( is_array( $arFieldMultipropFormatParts ) && !empty( $arFieldMultipropFormatParts ) ){
                foreach( $arFieldMultipropFormatParts as $arFieldMultipropFormatPartsItem ){
                    $arFieldMultipropFormatAtom = explode( "-", trim( $arFieldMultipropFormatPartsItem ) );
                    switch( count( $arFieldMultipropFormatAtom ) ){
                        case 2:
                            for( $multipropIndex = $arFieldMultipropFormatAtom[0]; $multipropIndex < ( $arFieldMultipropFormatAtom[1] + 1 ); $multipropIndex++ ){
                                $arSelectedMultipropIndexes[] = $multipropIndex;
                            }
                            break;
                        case 1:
                        default:
                            $arSelectedMultipropIndexes[] = intval( $arFieldMultipropFormatAtom[0] );
                            break;
                    }
                }
            }
        }

        foreach( $arSelectedMultipropIndexes as $selectedMultipropIndexesItem ){
            if( isset( $arProcessValues[$selectedMultipropIndexesItem] ) ){
                $arProcessValuesSelectedMultiprop[] = $arProcessValues[$selectedMultipropIndexesItem];
            }
        }

        return $arProcessValuesSelectedMultiprop;
    }

    public function CheckProfileDefaults( $arProfile ){
        $bNeedPayment = false;
        if( ( $arProfile["SETUP"]["FILE_TYPE"] == "csv" ) || ( $arProfile["SETUP"]["FILE_TYPE"] == "xls" ) ){
            $bNeedPayment = true;
        }
        else{
            $obProfileUtils = new CExportproProfile();
            $arProfileDefaults = $obProfileUtils->GetDefaults( $arProfile["TYPE"] );

            if(
                ( count( $arProfileDefaults["XMLDATA"] ) != count( $arProfile["XMLDATA"] ) )
                //|| ( strlen( trim( $arProfileDefaults["FORMAT"] ) ) != strlen( trim( $arProfile["FORMAT"] ) ) )
                //|| ( strlen( trim( $arProfileDefaults["OFFER_TEMPLATE"] ) ) != strlen( trim( $arProfile["OFFER_TEMPLATE"] ) ) )
                //|| ( strlen( trim( $arProfileDefaults["CATEGORY_TEMPLATE"] ) ) != strlen( trim( $arProfile["CATEGORY_TEMPLATE"] ) ) )
                //|| ( strlen( trim( $arProfileDefaults["CURRENCY_TEMPLATE"] ) ) != strlen( trim( $arProfile["CURRENCY_TEMPLATE"] ) ) )
            ){
                $bNeedPayment = true;
            }
            else{
                $arProfileTagKeys = array_keys( $arProfile["XMLDATA"] );
                $arProfileDefaultsTagKeys = array_keys( $arProfileDefaults["XMLDATA"] );

                $arKeysDiff = array_diff( ( is_array( $rProfileTagKeys ) ? $rProfileTagKeys : array() ), $arProfileDefaultsTagKeys );
                if( is_array( $arKeysDiff ) && !empty( $arKeysDiff ) ){
                    $bNeedPayment = true;
                }
            }
        }

        return $bNeedPayment;
    }

    public function IsFunctionEnabled( $functionName ){
        $functionName = strtolower( trim( $functionName ) );
        if( $functionName == "" ){
            return false;
        }

        $arDisabledFunctions = explode( ",", @ini_get( "disable_functions" ) );
        if( empty( $arDisabledFunctions ) ){
            $arDisabledFunctions = array();
        }
        else {
            $arDisabledFunctions = array_map( "trim", array_map( "strtolower", $arDisabledFunctions ) );
        }

        return ( function_exists( $functionName ) && is_callable( $functionName ) && !in_array( $functionName, $arDisabledFunctions ) );
    }

    public function GetPotentialPhpPaths(){
        $arPaths = false;

        if( self::IsFunctionEnabled( "exec" ) ){
            $sPaths = exec( "whereis php" );
            $arPaths = explode( " ", $sPaths );
            $arPaths[0] = exec( "which php" );

            $arPaths = array_map( "trim", $arPaths );
            $arPaths = array_unique( $arPaths );
        }

        return $arPaths;
    }

    public function ArrayToExcel( $fileFullName, $fileTitle, $arData, $arProfile, $page ){
        if( !empty( $fileFullName ) && is_array( $arData ) ) {
            $obLog = new CAcritExportproLog( $arProfile["ID"] );
            try{
                $objPHPExcel = false;
                if( $page == 1 ){
                    $objPHPExcel = new PHPExcel();
                }
                elseif( file_exists( $fileFullName ) ){
                    $inputFileType = PHPExcel_IOFactory::identify( $fileFullName );

                    $cacheMethod = PHPExcel_CachedObjectStorageFactory::cache_to_phpTemp;
                    $cacheSettings = array( "memoryCacheSize" => "1024MB" );
                    PHPExcel_Settings::setCacheStorageMethod( $cacheMethod, $cacheSettings );

                    $objReader = PHPExcel_IOFactory::createReader( $inputFileType );
                    $objPHPExcel = $objReader->load( $fileFullName );
                    $arRows = $objPHPExcel->getActiveSheet()->toArray();
                    $iHeaderColumnsCnt = count( $arRows[0] ) - 1;
                }

                if( $objPHPExcel ){
                    $objPHPExcel->getProperties()->setCreator( "a_crit.exportpro" )
                    ->setLastModifiedBy( "a_crit.exportpro" )
                    ->setTitle( $fileTitle )
                    ->setSubject( $fileTitle );

                    foreach( $arData as $dataType => $arDataset ){
                        if( $dataType == "HEADER" ){
                            $arColumnValType = array();
                            foreach( $arDataset as $keyHeader => $valueHeader ){
                                if( isset( $valueHeader["NAME"] ) ){
                                    $objPHPExcel->setActiveSheetIndex(0)->setCellValueByColumnAndRow(
                                        $keyHeader,
                                        1,
                                        $valueHeader["NAME"]
                                    );

                                    if( isset( $valueHeader["TYPE"] ) ){
                                        $arColumnValType[$keyHeader] = $valueHeader["TYPE"];
                                    }
                                    else{
                                        $arColumnValType[$keyHeader] = PHPExcel_Cell_DataType::TYPE_STRING2;
                                    }
                                }
                            }
                        }
                        elseif( $dataType == "ROWS" ){
                            foreach( $arDataset as $indexRow => $row ){
                                if( $page == 1 ){
                                    if( count( $row ) == count( $arData["HEADER"] ) ){
                                        foreach( $row as $indexColumn => $value ){
                                            $objPHPExcel->setActiveSheetIndex( 0 )->setCellValueExplicitByColumnAndRow(
                                                $indexColumn,
                                                ( $indexRow + 2 ),
                                                $GLOBALS['APPLICATION']->ConvertCharset($value,'CP1251','UTF-8'),
                                                $arColumnValType[$indexColumn]
                                            );
                                        }
                                    }
                                    else{
                                        $obLog->AddMessage( GetMessage( "ACRIT_EXPORTPRO_PHPEXCEL_BROKEN_DATA" ).$indexRow.PHP_EOL );
                                        $obLog->IncProductError();
                                    }
                                }
                                else{
                                    if( count( $row ) == $iHeaderColumnsCnt ){
                                        foreach( $row as $indexColumn => $value ){
                                            $objPHPExcel->setActiveSheetIndex( 0 )->setCellValueExplicitByColumnAndRow(
                                                $indexColumn,
                                                ( $indexRow + count( $arRows ) + 1 ),
                                                $GLOBALS['APPLICATION']->ConvertCharset($value,'CP1251','UTF-8'),
                                                PHPExcel_Cell_DataType::TYPE_STRING2
                                            );
                                        }
                                    }
                                    else{
                                        $obLog->AddMessage( GetMessage( "ACRIT_EXPORTPRO_PHPEXCEL_BROKEN_DATA" ).( $indexRow + count( $arRows ) ).PHP_EOL );
                                        $obLog->IncProductError();
                                    }
                                }
                            }
                        }
                    }

                    for( $i = 0; $i <= count( $arData["HEADER"] ); $i++ ){
                        $objPHPExcel->getActiveSheet()->getColumnDimensionByColumn( $i )->setAutoSize( true );
                    }

                    $objPHPExcel->getActiveSheet()->setTitle( $fileTitle );
                    $objPHPExcel->setActiveSheetIndex( 0 );
                    $objWriter = PHPExcel_IOFactory::createWriter( $objPHPExcel, "Excel2007" );
                    $objWriter->save( $fileFullName );
                }

                return $fileFullName;
            }
            catch( \Exception $e ){
                $obLog->AddMessage( GetMessage( "ACRIT_EXPORTPRO_PHPEXCEL_BAD_EXPORT" ) );
                $obLog->IncProductError();
            }
        }
    }
}

class CAcritExportproStringProcess{
    public function AcritTruncateText( $text, $lenght ){
        $truncatedString = $text;

        $arMbStringData = mb_get_info();

        if( ( strlen( $truncatedString ) > $lenght ) && is_array( $arMbStringData ) && !empty( $arMbStringData ) ){
            $truncatedString = rtrim( mb_substr( $truncatedString, 0, $lenght, mb_detect_encoding( $truncatedString, "auto" ) ) )."...";
        }

        return $truncatedString;
    }

    public function AcritArrayTrimFunc( &$value ){
        $value = trim( $value );
    }

    public function ConvertData( $sConvertField, $arConvertPatterns ){
        if( is_array( $arConvertPatterns ) && !empty( $arConvertPatterns ) ){
            foreach( $arConvertPatterns as $arConvertBlock ){
                $sConvertField = str_replace( $arConvertBlock[0], $arConvertBlock[1], $sConvertField );
            }
        }

        return $sConvertField;
    }

    public function AcritHtmlToTxt( $sConvertField ){
        $sConvertField = HTMLToTxt( $sConvertField );

        return $sConvertField;
    }

    public function HtmlEncodeCut( $convertField ){
        global $APPLICATION;

        if( !empty( $convertField ) ){
            if( is_array( $convertField ) ){
                foreach( $convertField as &$val ){
                    $templateValueCharset = CAcritExportproTools::GetStringCharset( $val );
                    if( $templateValueCharset == "cp1251" ) {
                        $convertedTemplateValue = $APPLICATION->ConvertCharset( $val, "windows-1251", "UTF-8" );
                        $convertedTemplateValue = preg_replace( "/&(amp;)?(.+?);/", "", $convertedTemplateValue );
                        $convertedTemplateValue = preg_replace( "/&(amp;)?#\d+;/", "", $convertedTemplateValue );
                        $val = $APPLICATION->ConvertCharset( $convertedTemplateValue, "UTF-8", "windows-1251" );
                    }
                    else{
                        $val = preg_replace( "/&(amp;)?(.+?);/", "", $val );
                        $val = preg_replace( "/&(amp;)?#\d+;/", "", $val );
                    }
                }
            }
            else{
                $templateValueCharset = CAcritExportproTools::GetStringCharset( $convertField );
                if( $templateValueCharset == "cp1251" ){
                    $convertedTemplateValue = $APPLICATION->ConvertCharset( $convertField, "windows-1251", "UTF-8" );
                    $convertedTemplateValue = preg_replace( "/&(amp;)?(.+?);/", "", $convertedTemplateValue );
                    $convertedTemplateValue = preg_replace( "/&(amp;)?#\d+;/", "", $convertedTemplateValue );
                    $convertField = $APPLICATION->ConvertCharset( $convertedTemplateValue, "UTF-8", "windows-1251" );
                }
                else{
                    $convertField = preg_replace( "/&(amp;)?(.+?);/", "", $convertField );
                    $convertField = preg_replace( "/&(amp;)?#\d+;/", "", $convertField );
                }
            }
        }

        return $convertField;
    }

    public function HtmlEncode( $convertField ){
        if( !empty( $convertField ) ){
            if( is_array( $convertField ) ){
                foreach( $convertField as &$val ){
                    $val = htmlspecialcharsbx( $val );
                }
            }
            else{
                $convertField = htmlspecialcharsbx( $convertField );
            }
        }

        return $convertField;
    }

    public function UrlEncode( $convertField ){
        if( !empty( $convertField ) ){
            if( is_array( $convertField ) ){
                foreach( $convertField as &$val ){
                    $val = str_replace( array( " " ), array( "%20" ), $val );
                }
            }
            else{
               $convertField = str_replace( array( " " ), array( "%20" ), $convertField );
            }
        }

        return $convertField;
    }

    public function ConvertCase( $convertField ){
        if( is_array( $convertField ) ){
            foreach( $convertField as &$val ){
                $val = explode( ".", $val );
                foreach( $val as &$tmpStr ){
                    $tmpStr = strtolower( trim( $tmpStr ) );
                    $strWords = explode( " ", $tmpStr );
                    if( ( strlen( $strWords[0] ) > 0 ) && ( count( $strWords ) > 1 ) )
                        $strWords[0] = mb_convert_case( $strWords[0], MB_CASE_TITLE );
                    $tmpStr = implode( " ", $strWords );
                }
                $val = implode( ". ", $val );
            }
        }
        else{
            $arTmp = explode( ".", $convertField );

            foreach( $arTmp as &$tmpStr ){
                $tmpStr = ToLower( trim( $tmpStr ) );

                $strWords = explode( " ", $tmpStr );

                if( ( strlen( $strWords[0] ) > 0 ) && ( count( $strWords ) > 1 ) ){
                    $templateValueCharset = CAcritExportproTools::GetStringCharset( $convertField );

                    if( $templateValueCharset == "cp1251" ){
                        $strWords[0] = mb_convert_case( $strWords[0], MB_CASE_TITLE, "WINDOWS-1251" );
                    }
                    else{
                        $strWords[0] = mb_convert_case( $strWords[0], MB_CASE_TITLE );
                    }
                }

                $tmpStr = implode( " ", $strWords );
            }
            $convertField = implode( ". ", $arTmp );
        }

        return $convertField;
    }

    public function DeleteEmptyRow( $itemTemplate, &$templateValues, $arMatches, $match, $baseTagMatchId, $bDeleteEmpty = false, $bDeleteEmptyForse = false, $bDeleteEmptyRowForse = false ){
        if( $bDeleteEmptyRowForse ){
            if( $templateValues[$match] == "" ){
                preg_match_all( "/.*<[\w\d_-]+([^<>]*)>(.*#.+#.*)?<\/.+>/", $arMatches[0][$baseTagMatchId], $curTagData );

                if( ( stripos( $curTagData[1][0], $match ) !== false ) ||
                    ( stripos( $curTagData[2][0], $match ) !== false )
                ){
                    $itemTemplate = str_replace( $arMatches[0][$baseTagMatchId], "", $itemTemplate );
                }
                else{
                    $itemTemplate = str_replace( $match, "", $itemTemplate );
                }
            }
            elseif( is_array( $templateValues[$match] ) ){
                $replacementValue = array();
                for( $i = 0; $i < count( $templateValues[$match] ); $i++ ){
                    $newName = preg_replace( "/\#((.)+)\#/", "#$1_LISTVAL_ITEM_$i#", $arMatches[2][$baseTagMatchId] );
                    $replacementValue[] = str_replace( $arMatches[2][$baseTagMatchId], $newName, $arMatches[0][$baseTagMatchId] );
                    $templateValues[$newName] = $templateValues[$match][$i];
                }

                $itemTemplate = str_replace( $arMatches[0][$baseTagMatchId], implode( PHP_EOL, $replacementValue ), $itemTemplate );
            }
        }
        elseif( $bDeleteEmptyForse ){
            if( $templateValues[$match] == "" ){
                preg_match_all( "/.*<[\w\d_-]+[^<>]*>(.*#[\w\d_-]+:*[\w\d_-]+#.*)<\/.+>/", $arMatches[0][$baseTagMatchId], $curTagData );
                if( $match == $curTagData[1][0] ){
                    $itemTemplate = str_replace( $arMatches[0][$baseTagMatchId], "", $itemTemplate );
                }
                else{
                    $itemTemplate = str_replace( $match, "", $itemTemplate );
                }
            }
            elseif( is_array( $templateValues[$match] ) ){
                $replacementValue = array();
                for( $i = 0; $i < count( $templateValues[$match] ); $i++ ){
                    $newName = preg_replace( "/\#((.)+)\#/", "#$1_LISTVAL_ITEM_$i#", $arMatches[2][$baseTagMatchId] );
                    $replacementValue[] = str_replace( $arMatches[2][$baseTagMatchId], $newName, $arMatches[0][$baseTagMatchId] );
                    $templateValues[$newName] = $templateValues[$match][$i];
                }

                $itemTemplate = str_replace( $arMatches[0][$baseTagMatchId], implode( PHP_EOL, $replacementValue ), $itemTemplate );
            }
        }
        elseif( $bDeleteEmpty ){
            if( $templateValues[$match] == "" ){
                preg_match_all( "/.*<[\w\d_-]+>(.*#[\w\d_-]+:*[\w\d_-]+#.*)<\/.+>/", $arMatches[0][$baseTagMatchId], $curTagData );
                if( $match == $curTagData[1][0] ){
                    $itemTemplate = str_replace( $arMatches[0][$baseTagMatchId], "", $itemTemplate );
                }
                else{
                    $itemTemplate = str_replace( $match, "", $itemTemplate );
                }
            }
            elseif( is_array( $templateValues[$match] ) ){
                $replacementValue = array();
                for( $i = 0; $i < count( $templateValues[$match] ); $i++ ){
                    $newName = preg_replace( "/\#((.)+)\#/", "#$1_LISTVAL_ITEM_$i#", $arMatches[2][$baseTagMatchId] );
                    $replacementValue[] = str_replace( $arMatches[2][$baseTagMatchId], $newName, $arMatches[0][$baseTagMatchId] );
                    $templateValues[$newName] = $templateValues[$match][$i];
                }

                $itemTemplate = str_replace( $arMatches[0][$baseTagMatchId], implode( PHP_EOL, $replacementValue ), $itemTemplate );
            }
        }

        return $itemTemplate;
    }

    public function ProcessTagOptions( $templateValues, $field, $propId, $bXmlExport = false, &$itemTemplate = false, $arMatches = false ){
        if( is_array( $templateValues ) && !empty( $templateValues )
            && is_array( $field ) && !empty( $field )
            && !empty( $propId )
        ){
            $templateValues[$propId] = self::ConvertData(
                $templateValues[$propId],
                $field["CONVERT_DATA"]
            );

            if( ( $field["HTML_TO_TXT"] == "Y" ) && !is_array( $templateValues[$propId] ) ){
                $templateValues[$propId] = self::AcritHtmlToTxt( $templateValues[$propId] );
            }

            if( $field["HTML_ENCODE"] == "Y" ){
                 $templateValues[$propId] = self::HtmlEncode(
                    $templateValues[$propId]
                 );
            }

            if( $field["HTML_ENCODE_CUT"] == "Y" ){
                $templateValues[$propId] = self::HtmlEncodeCut(
                    $templateValues[$propId]
                );
            }

            if( $field["URL_ENCODE"] == "Y" ){
                $templateValues[$propId] = self::UrlEncode(
                    $templateValues[$propId]
                );
            }

            if( $field["CONVERT_CASE"] == "Y" ){
                $templateValues[$propId] = self::ConvertCase(
                    $templateValues[$propId]
                );
            }

            if( intval( $field["TEXT_LIMIT"] ) > 0 ){
                $templateValues[$propId] = self::AcritTruncateText( $templateValues[$propId], $field["TEXT_LIMIT"] );
            }

            $fieldPrePostfix = ( $bXmlExport ) ? "#" : "";
            if( ( intval( $templateValues[$fieldPrePostfix."OLDPRICE".$fieldPrePostfix] ) > 0 )
                && ( intval( $templateValues[$fieldPrePostfix."OLDPRICE".$fieldPrePostfix] ) <= intval( $templateValues[$fieldPrePostfix."PRICE".$fieldPrePostfix] ) )
                && ( $field["REQUIRED"] != "Y" )
            ){
                $templateValues[$fieldPrePostfix."OLDPRICE".$fieldPrePostfix] = "";
            }

            if( $bXmlExport && ( strlen( $itemTemplate ) > 0 )
                && is_array( $arMatches ) && !empty( $arMatches )
            ){
                $baseTagMatchId = array_search( $propId, $arMatches[2] );
                $itemTemplate = self::DeleteEmptyRow(
                    $itemTemplate,
                    $templateValues,
                    $arMatches,
                    $propId,
                    $baseTagMatchId,
                    ( $field["DELETE_ONEMPTY"] == "Y" ),
                    ( $field["DELETE_ONEMPTY_FORCE"] == "Y" ),
                    ( $field["DELETE_ONEMPTY_ROWFORCE"] == "Y" )
                );
            }
        }

        return $templateValues;
    }
}

class CAcritExportproMarketCategories{
    const YANDEX_CATEGORY_SERVER_PATH = "http://download.cdn.yandex.net/market/market_categories.xls";
    const GOOGLE_CATEGORY_SERVER_PATH = "http://www.google.com/basepages/producttype/taxonomy-with-ids.ru-RU.xls";
    const TIU_CATEGORY_SERVER_PATH = "https://my.tiu.ru/cabinet/export_categories/xls";
    const PROMUA_CATEGORY_SERVER_PATH = "https://my.prom.ua/cabinet/export_categories/xls";
	const MAILRU_CATEGORY_SERVER_PATH = "https://torg.mail.ru/info/exmpl1/";

    private function GetCategoryArray( $localFilePath ){
	    if (ini_get('mbstring.func_overload') == 0) {
		    // dont work on mbstring.func_overload=2
		    $inputFileType = PHPExcel_IOFactory::identify($localFilePath);
		    $objReader = PHPExcel_IOFactory::createReader($inputFileType);

		    $objPHPExcel = $objReader->load($localFilePath);
		    $arCategories = $objPHPExcel->getActiveSheet()->toArray();
	    } else {
		    exec('php -d mbstring.func_overload=0 -f ' . __DIR__ . '/readExcelShell.php "' . $localFilePath . '"', $o);
		    $arCategories = json_decode(implode('', $o));
		    unset($o);
	    }
        return $arCategories;
    }

    private function GetLocalFilePath( $yandexCategoryServerPath ){
        $yandexCategoryServerPathParts = explode( "/", $yandexCategoryServerPath );
        $yandexCategoryFileName = end( $yandexCategoryServerPathParts );
        $yandexCategoryLocalPath = $_SERVER["DOCUMENT_ROOT"]."/upload/a_crit.exportpro/".$yandexCategoryFileName;

        copy($yandexCategoryServerPath, $yandexCategoryLocalPath);

        return ( file_exists( $yandexCategoryLocalPath ) ? $yandexCategoryLocalPath : false );
    }

    private function GetCorrectCharsetRow( $inputRow ){
        global $APPLICATION;

        $itemCharset = CAcritExportproTools::GetStringCharset( $inputRow );
        if ( $itemCharset == "utf8" && ini_get('mbstring.func_overload') == 0) { // json string already
            $itemRow = $APPLICATION->ConvertCharset( $inputRow, "UTF-8", "windows-1251" );
        } else {
            $itemRow = $inputRow;
        }

        return $itemRow;
    }

    private function GetYandexCategoryRow( $categoryServerPath ){
        if( $categoryLocalPath = self::GetLocalFilePath( $categoryServerPath ) ){
            $arCategories = self::GetCategoryArray( $categoryLocalPath );
        }

        $sCategoryRow = "";
        foreach( $arCategories as $itemIndex => $arCategoriesItem ){
            $itemRow = self::GetCorrectCharsetRow( $arCategoriesItem[0] );
            $sCategoryRow .= $itemRow.( ( $itemIndex < ( count( $arCategories ) - 1 ) ) ? "\n" : "" );
        }

        return $sCategoryRow;
    }

    private function GetGoogleCategoryRow( $categoryServerPath ){
        if( $categoryLocalPath = self::GetLocalFilePath( $categoryServerPath ) ){
            $arCategories = self::GetCategoryArray( $categoryLocalPath );
        }

        $sCategoryRow = "";
        foreach( $arCategories as $itemIndex => $arCategoriesItem ){
            $categoriesItemRow = "";
            foreach( $arCategoriesItem as $indexItemPart => $categoriesItemPart ){
                if( $indexItemPart && !empty( $categoriesItemPart ) ){
                    $categoriesItemRow .= $categoriesItemPart.( !empty( $arCategoriesItem[$indexItemPart + 1] ) ? " > " : "" );
                }
            }

            $itemRow = self::GetCorrectCharsetRow( $categoriesItemRow );
            $sCategoryRow .= $itemRow.( ( $itemIndex < ( count( $arCategories ) - 1 ) ) ? "\n" : "" );
        }

        return $sCategoryRow;
    }

    private function GetTiuCategoryArray( $categoryServerPath ){
        if( $categoryLocalPath = self::GetLocalFilePath( $categoryServerPath ) ){
            $arCategories = self::GetCategoryArray( $categoryLocalPath );
        }

        foreach( $arCategories as $itemIndex => $arCategoriesItem ){
            $categoriesItemRow = "";
            foreach( $arCategoriesItem as $indexItemPart => $categoriesItemPart ){
                $arCategories[$itemIndex][$indexItemPart] = self::GetCorrectCharsetRow( $categoriesItemPart );
            }
        }

        return $arCategories;
    }

    private function GetPromuaCategoryArray( $categoryServerPath ){
        if( $categoryLocalPath = self::GetLocalFilePath( $categoryServerPath ) ){
            $arCategories = self::GetCategoryArray( $categoryLocalPath );
        }

        foreach( $arCategories as $itemIndex => $arCategoriesItem ){
            $categoriesItemRow = "";
            foreach( $arCategoriesItem as $indexItemPart => $categoriesItemPart ){
                $arCategories[$itemIndex][$indexItemPart] = self::GetCorrectCharsetRow( $categoriesItemPart );
            }
        }

        return $arCategories;
    }

	private function GetMailruCategoryArray( $categoryServerPath )
	{
		$arCategories = array();

		$httpClient = new \Bitrix\Main\Web\HttpClient();
		$contentToParse = $httpClient->get($categoryServerPath);

		if (constant('BX_UTF') === true) {
			$contentToParse = mb_convert_encoding($contentToParse, 'utf-8', 'windows-1251');
		}
		$contentToParse = str_replace('&bull;', '', $contentToParse);

		$doc = new \Bitrix\Main\Web\DOM\Document();
		$doc->loadHTML($contentToParse);
		unset($contentToParse);

		$lastCategories = array();
		$addedIds = array();

		$categories = $doc->querySelectorAll('div.t75 div');
		foreach ($categories as $node) {
			/* @var $node \Bitrix\Main\Web\DOM\Node */
			$text = trim($node->getTextContent());
			$text = self::GetCorrectCharsetRow($text);

			if (! preg_match('#padding-left:\s*([\d]+)\s*px#is', $node->getOuterHTML(), $ml)) {
				continue;
			}
			$ml[1] = (int)$ml[1];
			$level = round(($ml[1] - 20) / 50);

			if (preg_match('#.+\(([\d]+)\)#is', $text, $m)) {
				$id = (int)$m[1];
				$arCat = array('ID' => $id);
				$lastCategories[$level] = trim(str_replace('('.$id.')', '', $text));
				foreach ($lastCategories as $i => $cat) {
					if ($i > $level) {
						break;
					}
					$arCat['NAME' . ($i+1)] = $cat;
				}
				$arCat['PORTAL_ID'] = $id;

				if (! in_array($id, $addedIds)) {
					$arCategories[] = $arCat;
					$addedIds[] = $id;
				}
			} else {
				if ($level == 0) {
					$lastCategories = array($text);
				} else {
					$lastCategories[$level] = $text;
				}
			}
		}

		return $arCategories;
	}

    public function Process()
    {
        $sYandexCategoryRow = self::GetYandexCategoryRow( self::YANDEX_CATEGORY_SERVER_PATH );

        $sGoogleCategoryRow = self::GetGoogleCategoryRow( self::GOOGLE_CATEGORY_SERVER_PATH );

        if( ( strlen( $sYandexCategoryRow ) > 0 ) || ( strlen( $sGoogleCategoryRow ) > 0 ) ){
            $obMarket = new CExportproMarketDB();
            $arMarketCategories = $obMarket->GetList();

            $iYandexMarketCategoryId = false;
            foreach( $arMarketCategories as $marketCategoriesItemIndex => $arMarketCategoriesItem ){
                if( $arMarketCategoriesItem["name"] == "Yandex Market" ){
                    $iYandexMarketCategoryId = $marketCategoriesItemIndex;
                    $arMarketCategories[$marketCategoriesItemIndex]["data"] = $sYandexCategoryRow;
                }
            }

            if( $iYandexMarketCategoryId ){
                $obMarket->Update( $arMarketCategories[$iYandexMarketCategoryId]["id"], $arMarketCategories[$iYandexMarketCategoryId] );
            }
            else{
                $obMarket->Add(
                    array(
                        "name" => "Yandex Market",
                        "data" => $sYandexCategoryRow
                    )
                );
            }

            $iGoogleMarketCategoryId = false;
            foreach( $arMarketCategories as $marketCategoriesItemIndex => $arMarketCategoriesItem ){
                if( $arMarketCategoriesItem["name"] == "Google Merchant" ){
                    $iGoogleMarketCategoryId = $marketCategoriesItemIndex;
                    $arMarketCategories[$marketCategoriesItemIndex]["data"] = $sGoogleCategoryRow;
                }
            }

            if( $iGoogleMarketCategoryId ){
                $obMarket->Update( $arMarketCategories[$iGoogleMarketCategoryId]["id"], $arMarketCategories[$iGoogleMarketCategoryId] );
            }
            else{
                $obMarket->Add(
                    array(
                        "name" => "Google Merchant",
                        "data" => $sGoogleCategoryRow
                    )
                );
            }
        }

        $arTiuCategory = self::GetTiuCategoryArray( self::TIU_CATEGORY_SERVER_PATH );
        if( is_array( $arTiuCategory ) && !empty( $arTiuCategory ) ){
            $obTiuDB = new CExportproMarketTiuDB();
            $obTiuDB->DeleteAll();
            foreach( $arTiuCategory as $tiuCategoryItemIndex => $arTiuCategoryItem ){
                if( $tiuCategoryItemIndex < 2 ) continue;
                $iTiuCategoryItemCnt = array_unshift( $arTiuCategoryItem, $tiuCategoryItemIndex - 1 );
                $obTiuDB->Add( $arTiuCategoryItem );
            }
        }

        $arPromuaCategory = self::GetPromuaCategoryArray( self::PROMUA_CATEGORY_SERVER_PATH );
        if( is_array( $arPromuaCategory ) && !empty( $arPromuaCategory ) ){
            $obPromuaDB = new CExportproMarketPromuaDB();
            $obPromuaDB->DeleteAll();
            foreach( $arPromuaCategory as $promuaCategoryItemIndex => $arPromuaCategoryItem ){
                if( $promuaCategoryItemIndex < 1 ) continue;
                $iPromuaCategoryItemCnt = array_unshift( $arPromuaCategoryItem, $promuaCategoryItemIndex );
                $obPromuaDB->Add( $arPromuaCategoryItem );
            }
        }

	    $arMailruCategory = self::GetMailruCategoryArray(self::MAILRU_CATEGORY_SERVER_PATH);
	    if ( is_array( $arMailruCategory ) && !empty( $arMailruCategory ) ) {
		    $obMailruDB = new CExportproMarketMailruDB();
		    $obMailruDB->DeleteAll();
		    foreach ($arMailruCategory as $arCategoryItem) {
			    $obMailruDB->Add($arCategoryItem);
		    }
	    }
    }
}