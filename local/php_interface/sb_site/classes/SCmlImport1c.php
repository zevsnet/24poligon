<?

namespace SB\Site;

/*
This class is used to parse and load an xml file into database table.
*/


use Bitrix\Catalog\Model\Price;
use Bitrix\Catalog\Model\Product;
use Bitrix\Catalog\Product\Sku;
use Bitrix\Catalog\StoreTable;
use Bitrix\Iblock\ElementTable;
use Bitrix\Iblock\PropertyIndex\Manager;
use Bitrix\Main\Localization\Loc;
use Bitrix\Main\ModuleManager;
use CCatalog;
use CIBlock;
use CIBlockCMLImport;
use CIBlockElement;
use CIBlockProperty;
use CIBlockXMLFile;
use CMain;
use CModule;
use CUtil;

class SCmlImport1c extends CIBlockCMLImport
{
    function InitEx(&$next_step, $params)
    {
        $defaultParams = array(
            "files_dir" => false,
            "use_crc" => true,
            "preview" => false,
            "detail" => false,
            "use_offers" => false,
            "force_offers" => false,
            "use_iblock_type_id" => false,
            "table_name" => "b_xml_tree",
            "translit_on_add" => false,
            "translit_on_update" => false,
            "translit_params" => array(
                "max_len" => 255,
                "change_case" => 'L', // 'L' - toLower, 'U' - toUpper, false - do not change
                "replace_space" => '-',
                "replace_other" => '-',
                "delete_repeat_replace" => true,
            ),
            "skip_root_section" => false,
            "disable_change_price_name" => false,
            "iblock_cache_mode" => self::IBLOCK_CACHE_NORMAL
        );
        foreach($defaultParams as $key => $value)
            if(!array_key_exists($key, $params))
                $params[$key] = $value;

        $this->Init($next_step,
            $params["files_dir"],
            $params["use_crc"],
            $params["preview"],
            $params["detail"],
            $params["use_offers"],
            $params["use_iblock_type_id"],
            $params["table_name"]
        );

        if($params["translit_on_add"])
            $this->translit_on_add = $params["translit_params"];
        if($params["translit_on_update"])
            $this->translit_on_update = $params["translit_params"];
        if ($params["disable_change_price_name"])
            $this->disable_change_price_name = $params["disable_change_price_name"];

        $this->skip_root_section = ($params["skip_root_section"] === true);
        $this->force_offers = ($params["force_offers"] === true);

        if (
            $params['iblock_cache_mode'] == self::IBLOCK_CACHE_FREEZE
            || $params['iblock_cache_mode'] == self::IBLOCK_CACHE_FINAL
            || $params['iblock_cache_mode'] == self::IBLOCK_CACHE_HIT
            || $params['iblock_cache_mode'] == self::IBLOCK_CACHE_NORMAL
        )
        {
            $this->iblockCacheMode = $params['iblock_cache_mode'];
        }
    }

    function Init(&$next_step, $files_dir = false, $use_crc = true, $preview = false, $detail = false, $use_offers = false, $use_iblock_type_id = false, $table_name = "b_xml_tree")
    {
        $this->next_step = &$next_step;
        $this->files_dir = $files_dir;
        $this->use_offers = $use_offers;
        $this->use_iblock_type_id = $use_iblock_type_id;
        $this->use_crc = $use_crc;

        $this->_xml_file = new CIBlockXMLFile($table_name);

        if(!is_array($preview) && $preview)
            $this->iblock_resize = true;

        if(is_array($preview) && count($preview)==2)
            $this->preview = $preview;
        else
            $this->preview = false;

        if(is_array($detail) && count($detail)==2)
            $this->detail = $detail;
        else
            $this->detail = false;

        $this->bCatalog = CModule::IncludeModule('catalog');
        if ($this->bCatalog)
        {
            $catalogsIterator = \Bitrix\Catalog\CatalogIblockTable::getList(array(
                'select' => array('IBLOCK_ID'),
                'filter' => array('=IBLOCK_ID' => $this->next_step["IBLOCK_ID"])
            ));
            if ($catalogData = $catalogsIterator->fetch())
                $this->isCatalogIblock = true;
            unset($catalogData, $catalogsIterator);
        }

        $this->arProperties = array();
        $this->PROPERTY_MAP = array();
        if($this->next_step["IBLOCK_ID"] > 0)
        {
            $obProperty = new CIBlockProperty;
            $rsProperties = $obProperty->GetList(array(), array("IBLOCK_ID"=>$this->next_step["IBLOCK_ID"], "ACTIVE"=>"Y"));
            $arTmpKey = $this->getArrKey();
            while($arProperty = $rsProperties->Fetch())
            {
                if(!empty($arTmpKey[$arProperty['NAME']])){
                    $arProperty["ID"] = $arTmpKey[$arProperty['NAME']]['MAIN'];
                }
                $this->PROPERTY_MAP[$arProperty["XML_ID"]] = $arProperty["ID"];
                $this->arProperties[$arProperty["ID"]] = $arProperty;
            }
        }

        if ($this->next_step["lang"])
            $this->mess = Loc::loadLanguageFile(__FILE__, $this->next_step["lang"]);

        $this->arTempFiles = array();
        $this->arLinkedProps = false;
    }

    /**
     * @param int $start_time
     * @param int $interval
     *
     * @return array
     */
    public function ImportElements($start_time, $interval)
    {
        global $DB;
        $counter = array(
            "ADD" => 0,
            "UPD" => 0,
            "DEL" => 0,
            "DEA" => 0,
            "ERR" => 0,
            "CRC" => 0,
        );
        if ($this->next_step["XML_ELEMENTS_PARENT"]) {
            Manager::enableDeferredIndexing();
            if ($this->bCatalog) {
                Sku::enableDeferredCalculation();
            }
            $this->activeStores = $this->getActiveStores();

            $obElement = new CIBlockElement();
            $obElement->CancelWFSetMove();
            $bWF = CModule::IncludeModule("workflow");
            $rsParents = $this->_xml_file->GetList(
                array("ID" => "asc"),
                array("PARENT_ID" => $this->next_step["XML_ELEMENTS_PARENT"], ">ID" => $this->next_step["XML_LAST_ID"]),
                array("ID", "LEFT_MARGIN", "RIGHT_MARGIN")
            );
            while ($arParent = $rsParents->Fetch()) {
                if (!$arParent["RIGHT_MARGIN"]) {
                    continue;
                }

                $counter["CRC"]++;

                $arXMLElement = $this->_xml_file->GetAllChildrenArray($arParent);
                $hashPosition = strrpos($arXMLElement[$this->mess["IBLOCK_XML2_ID"]], "#");
                if (!$this->next_step["bOffer"] && $this->use_offers) {
                    if ($hashPosition !== false) {
                        $this->next_step["XML_LAST_ID"] = $arParent["ID"];
                        continue;
                    }
                }
                if (array_key_exists($this->mess["IBLOCK_XML2_STATUS"],
                        $arXMLElement) && ($arXMLElement[$this->mess["IBLOCK_XML2_STATUS"]] == $this->mess["IBLOCK_XML2_DELETED"])) {
                    $ID = $this->GetElementByXML_ID($this->next_step["IBLOCK_ID"],
                        $arXMLElement[$this->mess["IBLOCK_XML2_ID"]]);
                    if ($ID && $obElement->Update($ID, array("ACTIVE" => "N"), $bWF)) {
                        if ($this->use_offers) {
                            $this->ChangeOffersStatus($ID, "N", $bWF);
                        }
                        $counter["DEA"]++;
                    } else {
                        $counter["ERR"]++;
                    }
                } elseif (array_key_exists($this->mess["IBLOCK_XML2_BX_TAGS"], $arXMLElement)) {
                    //This is our export file
                    $ID = $this->ImportElement($arXMLElement, $counter, $bWF, $arParent);
                } else {
                    $this->arFileDescriptionsMap = array();
                    $this->arElementFilesId = array();
                    $this->arElementFiles = array();
                    //offers.xml
                    if ($this->next_step["bOffer"]) {
                        if (!$this->use_offers) {
                            //We have only one information block
                            $ID = $this->ImportElementPrices($arXMLElement, $counter, $arParent);
                        } elseif ($hashPosition === false && !$this->force_offers) {
                            //We have separate offers iblock and there is element price

                            $ID = $this->ImportElementPrices($arXMLElement, $counter, $arParent);
                        } else {
                            $xmlKeys = array_keys($arXMLElement);
                            if ($xmlKeys == array($this->mess["IBLOCK_XML2_ID"], $this->mess["IBLOCK_XML2_PRICES"])) {
                                //prices.xml
                                $ID = $this->ImportElementPrices($arXMLElement, $counter, $arParent);
                            } elseif ($xmlKeys == array(
                                    $this->mess["IBLOCK_XML2_ID"],
                                    $this->mess["IBLOCK_XML2_RESTS"]
                                )) {
                                //rests.xml
                                $ID = $this->ImportElementPrices($arXMLElement, $counter, $arParent);
                            } else {
                                //It's an offer in offers iblock
                                $ID = $this->ImportElement($arXMLElement, $counter, $bWF, $arParent);
                            }
                        }
                    } //import.xml
                    else {

                        $ID = $this->ImportElement($arXMLElement, $counter, $bWF, $arParent);
                    }
                }

                if ($ID) {
                    //$ipropValues = new \Bitrix\Iblock\InheritedProperty\ElementValues($this->next_step["IBLOCK_ID"], $ID);
//                    $ipropValues->clearValues();

                    $DB->Query("UPDATE b_iblock_element SET TIMESTAMP_X = " . $DB->CurrentTimeFunction() . " WHERE ID=" . $ID);
                    $this->_xml_file->Add(array("PARENT_ID" => 0, "LEFT_MARGIN" => $ID));
                }

                $this->next_step["XML_LAST_ID"] = $arParent["ID"];

                if ($interval > 0 && (time() - $start_time) > (2 * $interval / 3)) {
                    break;
                }
            }
            if ($this->bCatalog) {
                Sku::disableDeferredCalculation();
                Sku::calculate();
            }
            //Manager::disableDeferredIndexing();
            //Manager::runDeferredIndexing($this->next_step["IBLOCK_ID"]);
        }
        $this->CleanTempFiles();
        return $counter;
    }

    /**
     * Returns a external codes list of active warehouses.
     *
     * @return array
     */
    protected function getActiveStores()
    {
        $result = array();
        if ($this->bCatalog) {
            $iterator = StoreTable::getList(array(
                'select' => array('ID', 'XML_ID'),
                'filter' => array('=ACTIVE' => 'Y')
            ));
            while ($row = $iterator->fetch()) {
                $result[$row['XML_ID']] = $row['ID'];
            }
            unset($row);
            unset($iterator);
        }
        return $result;
    }

    function ImportElementPrices($arXMLElement, &$counter, $arParent = false)
    {
        /** @global CMain $APPLICATION */
        global $APPLICATION;
        static $catalogs = array();

        $arElement = array(
            "ID" => 0,
            "XML_ID" => $arXMLElement[$this->mess["IBLOCK_XML2_ID"]],
        );

        $hashPosition = strrpos($arElement["XML_ID"], "#");
        if (
            $this->use_offers
            && $hashPosition === false && !$this->force_offers
            && isset($this->PROPERTY_MAP["CML2_LINK"])
            && isset($this->arProperties[$this->PROPERTY_MAP["CML2_LINK"]])
        ) {
            //Для ТП
            $IBLOCK_ID = $this->arProperties[$this->PROPERTY_MAP["CML2_LINK"]]["LINK_IBLOCK_ID"];
            if (!isset($catalogs[$IBLOCK_ID])) {
                $catalogs[$IBLOCK_ID] = true;

                $rs = CCatalog::GetList(array(), array("IBLOCK_ID" => $IBLOCK_ID));
                if (!$rs->Fetch()) {
                    $obCatalog = new CCatalog();
                    $boolFlag = $obCatalog->Add(array(
                        "IBLOCK_ID" => $IBLOCK_ID,
                        "YANDEX_EXPORT" => "N",
                        "SUBSCRIPTION" => "N",
                    ));
                    if (!$boolFlag) {
                        if ($ex = $APPLICATION->GetException()) {
                            $this->LAST_ERROR = $ex->GetString();
                        }
                        return 0;
                    }
                }
            }
        } else {
            $IBLOCK_ID = $this->next_step["IBLOCK_ID"];
        }

        $arDBElement = ElementTable::getList([
            'filter' => [
                "=XML_ID" => $arElement["XML_ID"],
                "IBLOCK_ID" => $IBLOCK_ID
            ],
            'select' => ['ID']
        ])->fetch();
        if ($arDBElement) {
            $arElement["ID"] = $arDBElement["ID"];
        }

        if (isset($arXMLElement[$this->mess["IBLOCK_XML2_STORE_AMOUNT_LIST"]])) {
            $arElement["STORE_AMOUNT"] = array();
            foreach ($arXMLElement[$this->mess["IBLOCK_XML2_STORE_AMOUNT_LIST"]] as $storeAmount) {
                if (isset($storeAmount[$this->mess["IBLOCK_XML2_STORE_ID"]])) {
                    $storeXMLID = $storeAmount[$this->mess["IBLOCK_XML2_STORE_ID"]];
                    $amount = $this->ToFloat($storeAmount[$this->mess["IBLOCK_XML2_AMOUNT"]]);
                    $arElement["STORE_AMOUNT"][$storeXMLID] = $amount;
                }
            }
        } elseif (isset($arXMLElement[$this->mess["IBLOCK_XML2_RESTS"]])) {
            $arElement["STORE_AMOUNT"] = array();
            foreach ($arXMLElement[$this->mess["IBLOCK_XML2_RESTS"]] as $xmlRest) {
                foreach ($xmlRest as $storeAmount) {
                    if (is_array($storeAmount)) {
                        if (isset($storeAmount[$this->mess["IBLOCK_XML2_ID"]])) {
                            $storeXMLID = $storeAmount[$this->mess["IBLOCK_XML2_ID"]];
                            $amount = $this->ToFloat($storeAmount[$this->mess["IBLOCK_XML2_AMOUNT"]]);
                            $arElement["STORE_AMOUNT"][$storeXMLID] = $amount;
                        }
                    } else {
                        if (strlen($storeAmount) > 0) {
                            $amount = $this->ToFloat($storeAmount);
                            $arElement["QUANTITY"] = $amount;
                        }
                    }
                }
            }
        } elseif (
            $arParent
            && (
                array_key_exists($this->mess["IBLOCK_XML2_STORES"], $arXMLElement)
                || array_key_exists($this->mess["IBLOCK_XML2_STORE"], $arXMLElement)
            )
        ) {
            $arElement["STORE_AMOUNT"] = array();
            $rsStores = $this->_xml_file->GetList(
                array("ID" => "asc"),
                array(
                    "><LEFT_MARGIN" => array($arParent["LEFT_MARGIN"], $arParent["RIGHT_MARGIN"]),
                    "NAME" => $this->mess["IBLOCK_XML2_STORE"],
                ),
                array("ID", "ATTRIBUTES")
            );
            while ($arStore = $rsStores->Fetch()) {
                if (strlen($arStore["ATTRIBUTES"]) > 0) {
                    $info = unserialize($arStore["ATTRIBUTES"]);
                    if (
                        is_array($info)
                        && array_key_exists($this->mess["IBLOCK_XML2_STORE_ID"], $info)
                        && array_key_exists($this->mess["IBLOCK_XML2_STORE_AMOUNT"], $info)
                    ) {
                        $arElement["STORE_AMOUNT"][$info[$this->mess["IBLOCK_XML2_STORE_ID"]]] = $this->ToFloat($info[$this->mess["IBLOCK_XML2_STORE_AMOUNT"]]);
                    }
                }
            }
        }

        if (isset($arElement["STORE_AMOUNT"])) {
            $this->ImportStoresAmount($arElement["STORE_AMOUNT"], $arElement["ID"], $counter);
        }

        if ($arDBElement) {
            $arProduct = array(
                "ID" => $arElement["ID"],
            );

            if (isset($arXMLElement[$this->mess["IBLOCK_XML2_PRICES"]])) {
                $arElement["PRICES"] = array();
                $arTmpPrice = [];
                foreach ($arXMLElement[$this->mess["IBLOCK_XML2_PRICES"]] as $key => $price) {
                    $arTmpPrice[$price['ИдТипаЦены']] = array_merge(['NAME_KEY' => $key], $price);
                }

                //Цена для оптовиков от
                $newPrices = [];
                $newPrices['Цена'] = $arTmpPrice['65fcda7e-6c18-11e6-cb90-0050568931bf'];

                if ($arTmpPrice['77d4c5aa-0447-11ea-80fb-00155d0a5d02']) {//цена от 10
                    $newPrices['Цена']['КоличествоОт'] = 0;
                    $newPrices['Цена']['КоличествоДо'] = 9;

                    $newPrices['Цена1'] = $arTmpPrice['77d4c5aa-0447-11ea-80fb-00155d0a5d02'];
                    $newPrices['Цена1']['КоличествоОт'] = 10;


                    if ($arTmpPrice['1f8ed85f-0448-11ea-80fb-00155d0a5d02']) {//цена от 50
                        $newPrices['Цена1']['КоличествоДо'] = 49;

                        $newPrices['Цена2'] = $arTmpPrice['1f8ed85f-0448-11ea-80fb-00155d0a5d02'];
                        $newPrices['Цена2']['КоличествоОт'] = 50;
                    }
                } elseif ($arTmpPrice['1f8ed85f-0448-11ea-80fb-00155d0a5d02']) {
                    $newPrices['Цена']['КоличествоОт'] = 0;
                    $newPrices['Цена']['КоличествоДо'] = 49;

                    $newPrices['Цена1'] = $arTmpPrice['1f8ed85f-0448-11ea-80fb-00155d0a5d02'];
                    $newPrices['Цена1']['КоличествоОт'] = 50;
                }
                //------------
                $arXMLElement[$this->mess["IBLOCK_XML2_PRICES"]] = $newPrices;

                foreach ($arXMLElement[$this->mess["IBLOCK_XML2_PRICES"]] as &$price) {
                    $price['ИдТипаЦены'] = '65fcda7e-6c18-11e6-cb90-0050568931bf';
                    if (
                        isset($price[$this->mess["IBLOCK_XML2_PRICE_TYPE_ID"]])
                        && array_key_exists($price[$this->mess["IBLOCK_XML2_PRICE_TYPE_ID"]], $this->PRICES_MAP)
                    ) {
                        $price["PRICE"] = $this->PRICES_MAP[$price[$this->mess["IBLOCK_XML2_PRICE_TYPE_ID"]]];
                        $arElement["PRICES"][] = $price;

                        if (
                            array_key_exists($this->mess["IBLOCK_XML2_MEASURE"], $price)
                            && !isset($arProduct["MEASURE"])
                        ) {
                            $tmp = $this->convertBaseUnitFromXmlToPropertyValue($price[$this->mess["IBLOCK_XML2_MEASURE"]]);
                            if ($tmp["DESCRIPTION"] > 0) {
                                $arProduct["MEASURE"] = $tmp["DESCRIPTION"];
                            }
                        }
                    }
                }

                $arElement["DISCOUNTS"] = array();
                if (isset($arXMLElement[$this->mess["IBLOCK_XML2_DISCOUNTS"]])) {
                    foreach ($arXMLElement[$this->mess["IBLOCK_XML2_DISCOUNTS"]] as $discount) {
                        if (
                            isset($discount[$this->mess["IBLOCK_XML2_DISCOUNT_CONDITION"]])
                            && $discount[$this->mess["IBLOCK_XML2_DISCOUNT_CONDITION"]] === $this->mess["IBLOCK_XML2_DISCOUNT_COND_VOLUME"]
                        ) {
                            $discount_value = $this->ToInt($discount[$this->mess["IBLOCK_XML2_DISCOUNT_COND_VALUE"]]);
                            $discount_percent = $this->ToFloat($discount[$this->mess["IBLOCK_XML2_DISCOUNT_COND_PERCENT"]]);
                            if ($discount_value > 0 && $discount_percent > 0) {
                                $arElement["DISCOUNTS"][$discount_value] = $discount_percent;
                            }
                        }
                    }
                }
            }

            if ($this->bCatalog && array_key_exists($this->mess["IBLOCK_XML2_AMOUNT"], $arXMLElement)) {
                $arElement["QUANTITY_RESERVED"] = 0;
                if ($arElement["ID"]) {
                    $iterator = Product::getList([
                        'select' => ['ID', 'QUANTITY_RESERVED'],
                        'filter' => ['=ID' => $arDBElement['ID']]
                    ]);
                    $arElementTmp = $iterator->fetch();
                    if (!empty($arElementTmp) && is_array($arElementTmp) && isset($arElementTmp["QUANTITY_RESERVED"])) {
                        $arElement["QUANTITY_RESERVED"] = (float)$arElementTmp["QUANTITY_RESERVED"];
                    }
                    unset($arElementTmp);
                    unset($iterator);
                }
                $arElement["QUANTITY"] = $this->ToFloat($arXMLElement[$this->mess["IBLOCK_XML2_AMOUNT"]]) - $arElement["QUANTITY_RESERVED"];
            }

            if (isset($arElement["PRICES"]) && $this->bCatalog) {
                if (isset($arElement["QUANTITY"])) {
                    $arProduct["QUANTITY"] = (float)$arElement["QUANTITY"];
                } elseif (isset($arElement["STORE_AMOUNT"]) && !empty($arElement["STORE_AMOUNT"])) {
                    $arProduct["QUANTITY"] = $this->countTotalQuantity($arElement["STORE_AMOUNT"]);
                }

                $rsWeight = CIBlockElement::GetProperty($IBLOCK_ID, $arElement["ID"], array(),
                    array("CODE" => "CML2_TRAITS"));
                while ($arWeight = $rsWeight->Fetch()) {
                    if ($arWeight["DESCRIPTION"] == $this->mess["IBLOCK_XML2_WEIGHT"]) {
                        $arProduct["WEIGHT"] = $this->ToFloat($arWeight["VALUE"]) * 1000;
                    }
                }

                $rsUnit = CIBlockElement::GetProperty($IBLOCK_ID, $arElement["ID"], array(),
                    array("CODE" => "CML2_BASE_UNIT"));
                while ($arUnit = $rsUnit->Fetch()) {
                    if ($arUnit["DESCRIPTION"] > 0) {
                        $arProduct["MEASURE"] = $arUnit["DESCRIPTION"];
                    }
                }

                //Here start VAT handling

                //Check if all the taxes exists in BSM catalog
                $arTaxMap = array();
                $rsTaxProperty = CIBlockElement::GetProperty($IBLOCK_ID, $arElement["ID"], array("sort" => "asc"),
                    array("CODE" => "CML2_TAXES"));
                while ($arTaxProperty = $rsTaxProperty->Fetch()) {
                    if (
                        strlen($arTaxProperty["VALUE"]) > 0
                        && strlen($arTaxProperty["DESCRIPTION"]) > 0
                        && !array_key_exists($arTaxProperty["DESCRIPTION"], $arTaxMap)
                    ) {
                        $arTaxMap[$arTaxProperty["DESCRIPTION"]] = array(
                            "RATE" => $this->ToFloat($arTaxProperty["VALUE"]),
                            "ID" => $this->CheckTax($arTaxProperty["DESCRIPTION"],
                                $this->ToFloat($arTaxProperty["VALUE"])),
                        );
                    }
                }

                //Try to search in main element
                if (
                    !$arTaxMap
                    && $this->use_offers
                    && $hashPosition !== false
                    && $this->arProperties[$this->PROPERTY_MAP["CML2_LINK"]]["LINK_IBLOCK_ID"] > 0
                ) {
                    $rsLinkProperty = CIBlockElement::GetProperty($IBLOCK_ID, $arElement["ID"], array("sort" => "asc"),
                        array("CODE" => "CML2_LINK"));
                    if (($arLinkProperty = $rsLinkProperty->Fetch()) && ($arLinkProperty["VALUE"] > 0)) {
                        $rsTaxProperty = CIBlockElement::GetProperty($this->arProperties[$this->PROPERTY_MAP["CML2_LINK"]]["LINK_IBLOCK_ID"],
                            $arLinkProperty["VALUE"], array("sort" => "asc"), array("CODE" => "CML2_TAXES"));
                        while ($arTaxProperty = $rsTaxProperty->Fetch()) {
                            if (
                                strlen($arTaxProperty["VALUE"]) > 0
                                && strlen($arTaxProperty["DESCRIPTION"]) > 0
                                && !array_key_exists($arTaxProperty["DESCRIPTION"], $arTaxMap)
                            ) {
                                $arTaxMap[$arTaxProperty["DESCRIPTION"]] = array(
                                    "RATE" => $this->ToFloat($arTaxProperty["VALUE"]),
                                    "ID" => $this->CheckTax($arTaxProperty["DESCRIPTION"],
                                        $this->ToFloat($arTaxProperty["VALUE"])),
                                );
                            }
                        }
                    }
                }

                //First find out if all the prices have TAX_IN_SUM true
                $TAX_IN_SUM = "Y";
                foreach ($arElement["PRICES"] as $price) {
                    if ($price["PRICE"]["TAX_IN_SUM"] !== "true") {
                        $TAX_IN_SUM = "N";
                        break;
                    }
                }
                //If there was found not included tax we'll make sure
                //that all prices has the same flag
                if ($TAX_IN_SUM === "N") {
                    foreach ($arElement["PRICES"] as $price) {
                        if ($price["PRICE"]["TAX_IN_SUM"] !== "false") {
                            $TAX_IN_SUM = "Y";
                            break;
                        }
                    }
                    //Check if there is a mix of tax in sum
                    //and correct it by recalculating all the prices
                    if ($TAX_IN_SUM === "Y") {
                        foreach ($arElement["PRICES"] as $key => $price) {
                            if ($price["PRICE"]["TAX_IN_SUM"] !== "true") {
                                $TAX_NAME = $price["PRICE"]["TAX_NAME"];
                                if (array_key_exists($TAX_NAME, $arTaxMap)) {
                                    $PRICE_WO_TAX = $this->ToFloat($price[$this->mess["IBLOCK_XML2_PRICE_FOR_ONE"]]);
                                    $PRICE = $PRICE_WO_TAX + ($PRICE_WO_TAX / 100.0 * $arTaxMap[$TAX_NAME]["RATE"]);
                                    $arElement["PRICES"][$key][$this->mess["IBLOCK_XML2_PRICE_FOR_ONE"]] = $PRICE;
                                }
                            }
                        }
                    }
                }

                if ($TAX_IN_SUM == "Y" && $arTaxMap) {
                    $vat = current($arTaxMap);
                    $arProduct["VAT_ID"] = $vat["ID"];
                } else {
                    foreach ($arElement["PRICES"] as $price) {
                        $TAX_NAME = $price["PRICE"]["TAX_NAME"];
                        if (array_key_exists($TAX_NAME, $arTaxMap)) {
                            $arProduct["VAT_ID"] = $arTaxMap[$TAX_NAME]["ID"];
                            break;
                        }
                    }
                }

                $arProduct["VAT_INCLUDED"] = $TAX_IN_SUM;
                $productCache = Product::getCacheItem($arProduct['ID'], true);
                if (!empty($productCache)) {
                    $productResult = Product::update(
                        $arProduct['ID'],
                        array(
                            'fields' => $arProduct,
                            'external_fields' => array(
                                'IBLOCK_ID' => $this->next_step["IBLOCK_ID"]
                            )
                        )
                    );
                } else {
                    $productResult = Product::add(
                        array(
                            'fields' => $arProduct,
                            'external_fields' => array(
                                'IBLOCK_ID' => $this->next_step["IBLOCK_ID"]
                            )
                        )
                    );
                }
                if ($productResult->isSuccess()) {
                    //TODO: replace this code after upload measure ratio from 1C
                    $iterator = \Bitrix\Catalog\MeasureRatioTable::getList(array(
                        'select' => array('ID'),
                        'filter' => array('=PRODUCT_ID' => $arElement['ID'])
                    ));
                    $ratioRow = $iterator->fetch();
                    if (empty($ratioRow)) {
                        $ratioResult = \Bitrix\Catalog\MeasureRatioTable::add(array(
                            'PRODUCT_ID' => $arElement['ID'],
                            'RATIO' => 1,
                            'IS_DEFAULT' => 'Y'
                        ));
                        unset($ratioResult);
                    }
                    unset($ratioRow, $iterator);
                }
                $this->SetProductPrice($arElement["ID"], $arElement["PRICES"], $arElement["DISCOUNTS"]);
                \Bitrix\Iblock\PropertyIndex\Manager::updateElementIndex($IBLOCK_ID, $arElement["ID"]);
            } elseif (
                $this->bCatalog
                && (
                    (isset($arElement["STORE_AMOUNT"]) && !empty($arElement["STORE_AMOUNT"]))
                    || isset($arElement["QUANTITY"])
                )
            ) {
                $iterator = Product::getList([
                    'select' => ['ID', 'QUANTITY_RESERVED'],
                    'filter' => ['=ID' => $arElement['ID']]
                ]);
                $arElementTmp = $iterator->fetch();
                if (!empty($arElementTmp) && is_array($arElementTmp)) {
                    $quantityReserved = 0;
                    if (isset($arElementTmp["QUANTITY_RESERVED"])) {
                        $quantityReserved = (float)$arElementTmp["QUANTITY_RESERVED"];
                    }
                    $internalFields = [];
                    if (isset($arElement["STORE_AMOUNT"]) && !empty($arElement["STORE_AMOUNT"])) {
                        $internalFields['QUANTITY'] = $this->countTotalQuantity($arElement["STORE_AMOUNT"]);
                    } elseif (isset($arElement["QUANTITY"])) {
                        $internalFields['QUANTITY'] = $arElement["QUANTITY"];
                    }
                    if (!empty($internalFields)) {
                        $internalFields['QUANTITY'] -= $quantityReserved;
                        $internalResult = Product::update($arElement['ID'], ['fields' => $internalFields]);
                        if (!$internalResult->isSuccess()) {

                        }
                        unset($internalResult);
                    }
                    unset($internalFields);
                    unset($quantityReserved);
                }
                unset($arElementTmp);
                unset($iterator);
            }
        }

        $counter["UPD"]++;
        return $arElement["ID"];
    }

    /**
     * @param int $PRODUCT_ID
     * @param array $arPrices
     * @param bool|array $arDiscounts
     */
    function SetProductPrice($PRODUCT_ID, $arPrices, $arDiscounts = false)
    {
        $arDBPrices = array();
        $iterator = Price::getList(array(
            'select' => array(
                'ID',
                'PRODUCT_ID',
                'CATALOG_GROUP_ID',
                'QUANTITY_FROM',
                'QUANTITY_TO'
            ),
            'filter' => array('=PRODUCT_ID' => $PRODUCT_ID)
        ));
        while ($row = $iterator->fetch()) {
            $arDBPrices[$row["CATALOG_GROUP_ID"] . ":" . $row["QUANTITY_FROM"] . ":" . $row["QUANTITY_TO"]] = $row["ID"];
        }

        $arToDelete = $arDBPrices;

        if (!is_array($arPrices)) {
            $arPrices = array();
        }


        foreach ($arPrices as $price) {
            if (!isset($price[$this->mess["IBLOCK_XML2_CURRENCY"]])) {
                $price[$this->mess["IBLOCK_XML2_CURRENCY"]] = $price["PRICE"]["CURRENCY"];
            }

            $arPrice = Array(
                "PRODUCT_ID" => $PRODUCT_ID,
                "CATALOG_GROUP_ID" => $price["PRICE"]["ID"],
                "^PRICE" => $this->ToFloat($price[$this->mess["IBLOCK_XML2_PRICE_FOR_ONE"]]),
                "CURRENCY" => $this->CheckCurrency($price[$this->mess["IBLOCK_XML2_CURRENCY"]]),
            );

            if (
                strlen($price[$this->mess["IBLOCK_XML2_QUANTITY_FROM"]])
                || strlen($price[$this->mess["IBLOCK_XML2_QUANTITY_TO"]])
            ) {

                $arPrice["QUANTITY_FROM"] = $price[$this->mess["IBLOCK_XML2_QUANTITY_FROM"]];
                $arPrice["QUANTITY_TO"] = $price[$this->mess["IBLOCK_XML2_QUANTITY_TO"]];
                $arPrice["PRICE"] = $arPrice["^PRICE"];
                unset($arPrice["^PRICE"]);
                if ($arPrice["QUANTITY_FROM"] === ''
                    || $arPrice["QUANTITY_FROM"] === false
                    || $arPrice["QUANTITY_FROM"] === '0'
                    || $arPrice["QUANTITY_FROM"] === 0
                ) {
                    $arPrice["QUANTITY_FROM"] = null;
                }
                if ($arPrice["QUANTITY_TO"] === ''
                    || $arPrice["QUANTITY_TO"] === false
                    || $arPrice["QUANTITY_TO"] === '0'
                    || $arPrice["QUANTITY_TO"] === 0
                ) {
                    $arPrice["QUANTITY_TO"] = null;
                }

                $id = $arPrice["CATALOG_GROUP_ID"] . ":" . $arPrice["QUANTITY_FROM"] . ":" . $arPrice["QUANTITY_TO"];

                if (isset($arDBPrices[$id])) {
                    $priceResult = Price::update($arDBPrices[$id], $arPrice);
                    unset($arToDelete[$id]);
                } else {
                    $priceResult = Price::add($arPrice);
                }
            } else {
                foreach ($this->ConvertDiscounts($arDiscounts) as $arDiscount) {
                    $arPrice["QUANTITY_FROM"] = $arDiscount["QUANTITY_FROM"];
                    $arPrice["QUANTITY_TO"] = $arDiscount["QUANTITY_TO"];
                    if ($arDiscount["PERCENT"] > 0) {
                        $arPrice["PRICE"] = $arPrice["^PRICE"] - $arPrice["^PRICE"] / 100 * $arDiscount["PERCENT"];
                    } else {
                        $arPrice["PRICE"] = $arPrice["^PRICE"];
                    }
                    unset($arPrice["^PRICE"]);
                    if ($arPrice["QUANTITY_FROM"] === ''
                        || $arPrice["QUANTITY_FROM"] === false
                        || $arPrice["QUANTITY_FROM"] === '0'
                        || $arPrice["QUANTITY_FROM"] === 0
                    ) {
                        $arPrice["QUANTITY_FROM"] = null;
                    }
                    if ($arPrice["QUANTITY_TO"] === ''
                        || $arPrice["QUANTITY_TO"] === false
                        || $arPrice["QUANTITY_TO"] === '0'
                        || $arPrice["QUANTITY_TO"] === 0
                    ) {
                        $arPrice["QUANTITY_TO"] = null;
                    }

                    $id = $arPrice["CATALOG_GROUP_ID"] . ":" . $arPrice["QUANTITY_FROM"] . ":" . $arPrice["QUANTITY_TO"];
                    if (isset($arDBPrices[$id])) {
                        $priceResult = Price::update($arDBPrices[$id], $arPrice);
                        unset($arToDelete[$id]);
                    } else {
                        $priceResult = Price::add($arPrice);
                    }
                }
            }
        }

        foreach ($arToDelete as $id) {
            $priceResult = Price::delete($id);
        }
    }

    function ImportElement($arXMLElement, &$counter, $bWF, $arParent)
    {

        global $USER;
        $USER_ID = is_object($USER) ? intval($USER->GetID()) : 0;
        $arElement = array(
            "ACTIVE" => "Y",
            "PROPERTY_VALUES" => array(),
        );

        if (isset($arXMLElement[$this->mess["IBLOCK_XML2_VERSION"]])) {
            $arElement["TMP_ID"] = $arXMLElement[$this->mess["IBLOCK_XML2_VERSION"]];
        } else {
            $arElement["TMP_ID"] = $this->GetElementCRC($arXMLElement);
        }

        if (isset($arXMLElement[$this->mess["IBLOCK_XML2_ID_1C_SITE"]])) {
            $arElement["XML_ID"] = $arXMLElement[$this->mess["IBLOCK_XML2_ID_1C_SITE"]];
        } elseif (isset($arXMLElement[$this->mess["IBLOCK_XML2_ID"]])) {
            $arElement["XML_ID"] = $arXMLElement[$this->mess["IBLOCK_XML2_ID"]];
        }

        $obElement = new CIBlockElement;
        //$obElement->CancelWFSetMove();
        $rsElement = $obElement->GetList(
            Array("ID"=>"asc"),
            Array("=XML_ID" => $arElement["XML_ID"], "IBLOCK_ID" => $this->next_step["IBLOCK_ID"]),
            false, false,
            Array("ID", "TMP_ID", "ACTIVE",'NAME', "CODE", "PREVIEW_PICTURE", "DETAIL_PICTURE")
        );

        $bMatch = false;
         if($arDBElement = $rsElement->Fetch()){

             //$bMatch = ($arElement["TMP_ID"] == $arDBElement["TMP_ID"]);
         }


         $isImportPicture = false;

foreach ($arXMLElement['ЗначенияРеквизитов'] as $arValue){
    if($arValue['Наименование'] == 'ВыгрузкаКартинок' ){
        $isImportPicture = $arValue['Значение'] == 'Y';
    }
}


        if ($bMatch && $this->use_crc) {
            //Проверьте, что для флага Active в XML не установлено значение false.
            if ($this->CheckIfElementIsActive($arXMLElement)) {
                //Если элемент не активен в базе данных, мы должны активировать его и его предложения.
                if ($arDBElement["ACTIVE"] != "Y") {
                    $obElement->Update($arDBElement["ID"], array("ACTIVE" => "Y"), $bWF);
                    $this->ChangeOffersStatus($arDBElement["ID"], "Y", $bWF);
                    $counter["UPD"]++;
                }
            }
            echo 'Магия,товар просто поменял автивность и все' . '<br>';
            $arElement["ID"] = $arDBElement["ID"];
        } elseif (isset($arXMLElement[$this->mess["IBLOCK_XML2_NAME"]])) {
            if(!$isImportPicture){
                if ($arDBElement) {
                    if ($arDBElement["PREVIEW_PICTURE"] > 0) {
                        $this->arElementFilesId["PREVIEW_PICTURE"] = array($arDBElement["PREVIEW_PICTURE"]);
                    }
                    if ($arDBElement["DETAIL_PICTURE"] > 0) {
                        $this->arElementFilesId["DETAIL_PICTURE"] = array($arDBElement["DETAIL_PICTURE"]);
                    }

                    $rsProperties = $obElement->GetProperty($this->next_step["IBLOCK_ID"], $arDBElement["ID"], "sort","asc");
                    $arTmpKeyProp = $this->getArrKey();
//                    \_::d($arTmpKeyProp);
                    while ($arProperty = $rsProperties->Fetch()) {

                        if (!array_key_exists($arProperty["ID"], $arElement["PROPERTY_VALUES"])) {
                            $arElement["PROPERTY_VALUES"][$arProperty["ID"]] = array(
                                "bOld" => true,
                            );
                        }

                        $arElement["PROPERTY_VALUES"][$arProperty["ID"]][$arProperty['PROPERTY_VALUE_ID']] = array(
                            "VALUE" => $arProperty['VALUE'],
                            "DESCRIPTION" => $arProperty["DESCRIPTION"]
                        );

                        if ($arProperty["PROPERTY_TYPE"] == "F" && $arProperty["VALUE"] > 0) {
                            $this->arElementFilesId[$arProperty["ID"]][] = $arProperty["VALUE"];
                        }
                    }
                }

                if ($this->bCatalog && $this->next_step["bOffer"]) {
                    $p = strpos($arXMLElement[$this->mess["IBLOCK_XML2_ID"]], "#");
                    if ($p !== false) {
                        $link_xml_id = substr($arXMLElement[$this->mess["IBLOCK_XML2_ID"]], 0, $p);
                    } else {
                        $link_xml_id = $arXMLElement[$this->mess["IBLOCK_XML2_ID"]];
                    }
                    $arElement["PROPERTY_VALUES"][$this->PROPERTY_MAP["CML2_LINK"]] = array(
                        "n0" => array(
                            "VALUE" => $this->GetElementByXML_ID(
                                $this->arProperties[$this->PROPERTY_MAP["CML2_LINK"]]["LINK_IBLOCK_ID"],
                                $link_xml_id
                            ),
                            "DESCRIPTION" => false,
                        ),
                    );
                }

                if (isset($arXMLElement[$this->mess["IBLOCK_XML2_NAME"]])) {
                    $arElement["NAME"] = $arXMLElement[$this->mess["IBLOCK_XML2_NAME"]];
                }

                if (isset($arXMLElement[$this->mess["IBLOCK_XML2_DELETE_MARK"]])) {
                    $value = $arXMLElement[$this->mess["IBLOCK_XML2_DELETE_MARK"]];
                    $arElement["ACTIVE"] = ($value == "true") || intval($value) ? "N" : "Y";
                }

                if (array_key_exists($this->mess["IBLOCK_XML2_BX_TAGS"], $arXMLElement)) {
                    $arElement["TAGS"] = $arXMLElement[$this->mess["IBLOCK_XML2_BX_TAGS"]];
                }

                if (array_key_exists($this->mess["IBLOCK_XML2_DESCRIPTION"], $arXMLElement)) {
                    if (strlen($arXMLElement[$this->mess["IBLOCK_XML2_DESCRIPTION"]]) > 0) {
                        $arElement["DETAIL_TEXT"] = $arXMLElement[$this->mess["IBLOCK_XML2_DESCRIPTION"]];
                    } else {
                        $arElement["DETAIL_TEXT"] = "";
                    }

                    if (preg_match('/<[a-zA-Z0-9]+.*?>/', $arElement["DETAIL_TEXT"])) {
                        $arElement["DETAIL_TEXT_TYPE"] = "html";
                    } else {
                        $arElement["DETAIL_TEXT_TYPE"] = "text";
                    }
                }

                if (array_key_exists($this->mess["IBLOCK_XML2_FULL_TITLE"], $arXMLElement)) {
                    if (strlen($arXMLElement[$this->mess["IBLOCK_XML2_FULL_TITLE"]]) > 0) {
                        $arElement["PREVIEW_TEXT"] = $arXMLElement[$this->mess["IBLOCK_XML2_FULL_TITLE"]];
                    } else {
                        $arElement["PREVIEW_TEXT"] = "";
                    }

                    if (preg_match('/<[a-zA-Z0-9]+.*?>/', $arElement["PREVIEW_TEXT"])) {
                        $arElement["PREVIEW_TEXT_TYPE"] = "html";
                    } else {
                        $arElement["PREVIEW_TEXT_TYPE"] = "text";
                    }
                }

                if (array_key_exists($this->mess["IBLOCK_XML2_INHERITED_TEMPLATES"], $arXMLElement)) {
                    $arElement["IPROPERTY_TEMPLATES"] = array();
                    foreach ($arXMLElement[$this->mess["IBLOCK_XML2_INHERITED_TEMPLATES"]] as $TEMPLATE) {
                        $id = $TEMPLATE[$this->mess["IBLOCK_XML2_ID"]];
                        $template = $TEMPLATE[$this->mess["IBLOCK_XML2_VALUE"]];
                        if (strlen($id) > 0 && strlen($template) > 0) {
                            $arElement["IPROPERTY_TEMPLATES"][$id] = $template;
                        }
                    }
                }
                if (array_key_exists($this->mess["IBLOCK_XML2_BAR_CODE2"], $arXMLElement)) {
                    $arElement["PROPERTY_VALUES"][$this->PROPERTY_MAP["CML2_BAR_CODE"]] = array(
                        "n0" => array(
                            "VALUE" => $arXMLElement[$this->mess["IBLOCK_XML2_BAR_CODE2"]],
                            "DESCRIPTION" => false,
                        ),
                    );
                } elseif (array_key_exists($this->mess["IBLOCK_XML2_BAR_CODE"], $arXMLElement)) {
                    $arElement["PROPERTY_VALUES"][$this->PROPERTY_MAP["CML2_BAR_CODE"]] = array(
                        "n0" => array(
                            "VALUE" => $arXMLElement[$this->mess["IBLOCK_XML2_BAR_CODE"]],
                            "DESCRIPTION" => false,
                        ),
                    );
                }

                if (array_key_exists($this->mess["IBLOCK_XML2_ARTICLE"], $arXMLElement)) {
                    $arElement["PROPERTY_VALUES"][$this->PROPERTY_MAP["CML2_ARTICLE"]] = array(
                        "n0" => array(
                            "VALUE" => $arXMLElement[$this->mess["IBLOCK_XML2_ARTICLE"]],
                            "DESCRIPTION" => false,
                        ),
                    );
                }

                if (
                    array_key_exists($this->mess["IBLOCK_XML2_MANUFACTURER"], $arXMLElement)
                    && $this->PROPERTY_MAP["CML2_MANUFACTURER"] > 0
                ) {
                    $arElement["PROPERTY_VALUES"][$this->PROPERTY_MAP["CML2_MANUFACTURER"]] = array(
                        "n0" => array(
                            "VALUE" => $this->CheckManufacturer($arXMLElement[$this->mess["IBLOCK_XML2_MANUFACTURER"]]),
                            "DESCRIPTION" => false,
                        ),
                    );
                }
                if (array_key_exists($this->mess["IBLOCK_XML2_PICTURE"], $arXMLElement)) {
                    $rsFiles = $this->_xml_file->GetList(
                        array("ID" => "asc"),
                        array("PARENT_ID" => $arParent["ID"], "NAME" => $this->mess["IBLOCK_XML2_PICTURE"])
                    );
                    $arFile = $rsFiles->Fetch();
                    if ($arFile) {
                        $description = "";
                        if (strlen($arFile["ATTRIBUTES"])) {
                            $arAttributes = unserialize($arFile["ATTRIBUTES"]);
                            if (is_array($arAttributes) && array_key_exists($this->mess["IBLOCK_XML2_DESCRIPTION"],
                                    $arAttributes)) {
                                $description = $arAttributes[$this->mess["IBLOCK_XML2_DESCRIPTION"]];
                            }
                        }

                        if (strlen($arFile["VALUE"]) > 0) {
                            $arElement["DETAIL_PICTURE"] = $this->ResizePicture($arFile["VALUE"], $this->detail,
                                "DETAIL_PICTURE", $this->PROPERTY_MAP["CML2_PICTURES"]);

                            if (is_array($arElement["DETAIL_PICTURE"])) {
                                $arElement["DETAIL_PICTURE"]["description"] = $description;
                                $this->arFileDescriptionsMap[$arFile["VALUE"]][] = &$arElement["DETAIL_PICTURE"]["description"];
                            }

                            if (is_array($this->preview)) {
                                $arElement["PREVIEW_PICTURE"] = $this->ResizePicture($arFile["VALUE"], $this->preview,
                                    "PREVIEW_PICTURE");
                                if (is_array($arElement["PREVIEW_PICTURE"])) {
                                    $arElement["PREVIEW_PICTURE"]["description"] = $description;
                                    $this->arFileDescriptionsMap[$arFile["VALUE"]][] = &$arElement["PREVIEW_PICTURE"]["description"];
                                }
                            }
                        } else {
                            $arElement["DETAIL_PICTURE"] = $this->MakeFileArray($this->_xml_file->GetAllChildrenArray($arFile["ID"]));

                            if (is_array($arElement["DETAIL_PICTURE"])) {
                                $arElement["DETAIL_PICTURE"]["description"] = $description;
                            }
                        }

                        $prop_id = $this->PROPERTY_MAP["CML2_PICTURES"];
                        if ($prop_id > 0) {
                            $i = 1;
                            while ($arFile = $rsFiles->Fetch()) {
                                $description = "";
                                if (strlen($arFile["ATTRIBUTES"])) {
                                    $arAttributes = unserialize($arFile["ATTRIBUTES"]);
                                    if (is_array($arAttributes) && array_key_exists($this->mess["IBLOCK_XML2_DESCRIPTION"],
                                            $arAttributes)) {
                                        $description = $arAttributes[$this->mess["IBLOCK_XML2_DESCRIPTION"]];
                                    }
                                }

                                if (strlen($arFile["VALUE"]) > 0) {
                                    $arPropFile = $this->ResizePicture($arFile["VALUE"], $this->detail,
                                        $this->PROPERTY_MAP["CML2_PICTURES"], "DETAIL_PICTURE");
                                } else {
                                    $arPropFile = $this->MakeFileArray($this->_xml_file->GetAllChildrenArray($arFile["ID"]));
                                }

                                if (is_array($arPropFile)) {
                                    $arPropFile = array(
                                        "VALUE" => $arPropFile,
                                        "DESCRIPTION" => $description,
                                    );
                                }
                                $arElement["PROPERTY_VALUES"][$prop_id]["n" . $i] = $arPropFile;
                                if (strlen($arFile["VALUE"]) > 0) {
                                    $this->arFileDescriptionsMap[$arFile["VALUE"]][] = &$arElement["PROPERTY_VALUES"][$prop_id]["n" . $i]["DESCRIPTION"];
                                }
                                $i++;
                            }

                            if (is_array($arElement["PROPERTY_VALUES"][$prop_id])) {
                                foreach ($arElement["PROPERTY_VALUES"][$prop_id] as $PROPERTY_VALUE_ID => $PROPERTY_VALUE) {
                                    if (!$PROPERTY_VALUE_ID) {
                                        unset($arElement["PROPERTY_VALUES"][$prop_id][$PROPERTY_VALUE_ID]);
                                    } elseif (substr($PROPERTY_VALUE_ID, 0, 1) !== "n") {
                                        $arElement["PROPERTY_VALUES"][$prop_id][$PROPERTY_VALUE_ID] = array(
                                            "tmp_name" => "",
                                            "del" => "Y",
                                        );
                                    }
                                }
                                unset($arElement["PROPERTY_VALUES"][$prop_id]["bOld"]);
                            }
                        }
                    }
                }

                $cleanCml2FilesProperty = false;
                if (
                    array_key_exists($this->mess["IBLOCK_XML2_FILE"], $arXMLElement)
                    && strlen($this->PROPERTY_MAP["CML2_FILES"]) > 0
                ) {
                    $prop_id = $this->PROPERTY_MAP["CML2_FILES"];
                    $rsFiles = $this->_xml_file->GetList(
                        array("ID" => "asc"),
                        array("PARENT_ID" => $arParent["ID"], "NAME" => $this->mess["IBLOCK_XML2_FILE"])
                    );
                    $i = 1;
                    while ($arFile = $rsFiles->Fetch()) {

                        if (strlen($arFile["VALUE"]) > 0) {
                            $file = $this->MakeFileArray($arFile["VALUE"], array($prop_id));
                        } else {
                            $file = $this->MakeFileArray($this->_xml_file->GetAllChildrenArray($arFile["ID"]));
                        }

                        $arElement["PROPERTY_VALUES"][$prop_id]["n" . $i] = array(
                            "VALUE" => $file,
                            "DESCRIPTION" => $file["description"],
                        );
                        if (strlen($arFile["ATTRIBUTES"])) {
                            $desc = unserialize($arFile["ATTRIBUTES"]);
                            if (is_array($desc) && array_key_exists($this->mess["IBLOCK_XML2_DESCRIPTION"], $desc)) {
                                $arElement["PROPERTY_VALUES"][$prop_id]["n" . $i]["DESCRIPTION"] = $desc[$this->mess["IBLOCK_XML2_DESCRIPTION"]];
                            }
                        }
                        $i++;
                    }
                    $cleanCml2FilesProperty = true;
                }

                if (isset($arXMLElement[$this->mess["IBLOCK_XML2_GROUPS"]])) {
                    $arElement["IBLOCK_SECTION"] = array();
                    foreach ($arXMLElement[$this->mess["IBLOCK_XML2_GROUPS"]] as $value) {
                        if (array_key_exists($value, $this->SECTION_MAP)) {
                            $arElement["IBLOCK_SECTION"][] = $this->SECTION_MAP[$value];
                        }
                    }
                    if ($arElement["IBLOCK_SECTION"]) {
                        $arElement["IBLOCK_SECTION_ID"] = $arElement["IBLOCK_SECTION"][0];
                    }
                }

                if (array_key_exists($this->mess["IBLOCK_XML2_PRICES"],
                    $arXMLElement)) {//Collect price information for future use
                    $arElement["PRICES"] = array();
                    if (is_array($arXMLElement[$this->mess["IBLOCK_XML2_PRICES"]])) {
                        foreach ($arXMLElement[$this->mess["IBLOCK_XML2_PRICES"]] as $price) {
                            if (isset($price[$this->mess["IBLOCK_XML2_PRICE_TYPE_ID"]]) && array_key_exists($price[$this->mess["IBLOCK_XML2_PRICE_TYPE_ID"]],
                                    $this->PRICES_MAP)) {
                                $price["PRICE"] = $this->PRICES_MAP[$price[$this->mess["IBLOCK_XML2_PRICE_TYPE_ID"]]];
                                $arElement["PRICES"][] = $price;
                            }
                        }
                    }

                    $arElement["DISCOUNTS"] = array();
                    if (isset($arXMLElement[$this->mess["IBLOCK_XML2_DISCOUNTS"]])) {
                        foreach ($arXMLElement[$this->mess["IBLOCK_XML2_DISCOUNTS"]] as $discount) {
                            if (
                                isset($discount[$this->mess["IBLOCK_XML2_DISCOUNT_CONDITION"]])
                                && $discount[$this->mess["IBLOCK_XML2_DISCOUNT_CONDITION"]] === $this->mess["IBLOCK_XML2_DISCOUNT_COND_VOLUME"]
                            ) {
                                $discount_value = $this->ToInt($discount[$this->mess["IBLOCK_XML2_DISCOUNT_COND_VALUE"]]);
                                $discount_percent = $this->ToFloat($discount[$this->mess["IBLOCK_XML2_DISCOUNT_COND_PERCENT"]]);
                                if ($discount_value > 0 && $discount_percent > 0) {
                                    $arElement["DISCOUNTS"][$discount_value] = $discount_percent;
                                }
                            }
                        }
                    }
                }

                if ($this->bCatalog && array_key_exists($this->mess["IBLOCK_XML2_AMOUNT"], $arXMLElement)) {
                    $arElement["QUANTITY_RESERVED"] = 0;
                    if ($arDBElement["ID"]) {
                        $iterator = Catalog\Model\Product::getList([
                            'select' => ['ID', 'QUANTITY_RESERVED'],
                            'filter' => ['=ID' => $arDBElement['ID']]
                        ]);
                        $arElementTmp = $iterator->fetch();
                        if (isset($arElementTmp["QUANTITY_RESERVED"])) {
                            $arElement["QUANTITY_RESERVED"] = (float)$arElementTmp["QUANTITY_RESERVED"];
                        }
                        unset($arElementTmp);
                        unset($iterator);
                    }
                    $arElement["QUANTITY"] = $this->ToFloat($arXMLElement[$this->mess["IBLOCK_XML2_AMOUNT"]]) - $arElement["QUANTITY_RESERVED"];
                }

                if (isset($arXMLElement[$this->mess["IBLOCK_XML2_ITEM_ATTRIBUTES"]])) {
                    $arElement["PROPERTY_VALUES"][$this->PROPERTY_MAP["CML2_ATTRIBUTES"]] = array();
                    $i = 0;
                    foreach ($arXMLElement[$this->mess["IBLOCK_XML2_ITEM_ATTRIBUTES"]] as $value) {
                        $arElement["PROPERTY_VALUES"][$this->PROPERTY_MAP["CML2_ATTRIBUTES"]]["n" . $i] = array(
                            "VALUE" => $value[$this->mess["IBLOCK_XML2_VALUE"]],
                            "DESCRIPTION" => $value[$this->mess["IBLOCK_XML2_NAME"]],
                        );
                        $i++;
                    }
                }

                $i = 0;
                $weightKey = false;
                if (isset($arXMLElement[$this->mess["IBLOCK_XML2_TRAITS_VALUES"]])) {
                    $arElement["PROPERTY_VALUES"][$this->PROPERTY_MAP["CML2_TRAITS"]] = array();
                    foreach ($arXMLElement[$this->mess["IBLOCK_XML2_TRAITS_VALUES"]] as $value) {
                        if (
                            !array_key_exists("PREVIEW_TEXT", $arElement)
                            && $value[$this->mess["IBLOCK_XML2_NAME"]] == $this->mess["IBLOCK_XML2_FULL_TITLE2"]
                        ) {
                            $arElement["PREVIEW_TEXT"] = $value[$this->mess["IBLOCK_XML2_VALUE"]];
                            if (strpos($arElement["PREVIEW_TEXT"], "<") !== false) {
                                $arElement["PREVIEW_TEXT_TYPE"] = "html";
                            } else {
                                $arElement["PREVIEW_TEXT_TYPE"] = "text";
                            }
                        } elseif (
                            $value[$this->mess["IBLOCK_XML2_NAME"]] == $this->mess["IBLOCK_XML2_HTML_DESCRIPTION"]
                        ) {
                            if (strlen($value[$this->mess["IBLOCK_XML2_VALUE"]]) > 0) {
                                $arElement["DETAIL_TEXT"] = $value[$this->mess["IBLOCK_XML2_VALUE"]];
                                $arElement["DETAIL_TEXT_TYPE"] = "html";
                            }
                        } elseif (
                            $value[$this->mess["IBLOCK_XML2_NAME"]] == $this->mess["IBLOCK_XML2_FILE"]
                        ) {
                            if (strlen($value[$this->mess["IBLOCK_XML2_VALUE"]]) > 0) {
                                $prop_id = $this->PROPERTY_MAP["CML2_FILES"];

                                $j = 1;
                                while (isset($arElement["PROPERTY_VALUES"][$prop_id]["n" . $j])) {
                                    $j++;
                                }

                                $file = $this->MakeFileArray($value[$this->mess["IBLOCK_XML2_VALUE"]], array($prop_id));
                                if (is_array($file)) {
                                    $arElement["PROPERTY_VALUES"][$prop_id]["n" . $j] = array(
                                        "VALUE" => $file,
                                        "DESCRIPTION" => "",
                                    );
                                    unset($arElement["PROPERTY_VALUES"][$prop_id]["bOld"]);
                                    $this->arFileDescriptionsMap[$value[$this->mess["IBLOCK_XML2_VALUE"]]][] = &$arElement["PROPERTY_VALUES"][$prop_id]["n" . $j]["DESCRIPTION"];
                                    $cleanCml2FilesProperty = true;
                                }
                            }
                        } elseif (
                            $value[$this->mess["IBLOCK_XML2_NAME"]] == $this->mess["IBLOCK_XML2_FILE_DESCRIPTION"]
                        ) {
                            if (strlen($value[$this->mess["IBLOCK_XML2_VALUE"]]) > 0) {
                                list($fileName, $description) = explode("#", $value[$this->mess["IBLOCK_XML2_VALUE"]]);
                                if (isset($this->arFileDescriptionsMap[$fileName])) {
                                    foreach ($this->arFileDescriptionsMap[$fileName] as $k => $tmp) {
                                        $this->arFileDescriptionsMap[$fileName][$k] = $description;
                                    }
                                }
                            }
                        } else {
                            if ($value[$this->mess["IBLOCK_XML2_NAME"]] == $this->mess["IBLOCK_XML2_WEIGHT"]) {
                                $arElement["BASE_WEIGHT"] = $this->ToFloat($value[$this->mess["IBLOCK_XML2_VALUE"]]) * 1000;
                                $weightKey = "n" . $i;
                            }

                            $arElement["PROPERTY_VALUES"][$this->PROPERTY_MAP["CML2_TRAITS"]]["n" . $i] = array(
                                "VALUE" => $value[$this->mess["IBLOCK_XML2_VALUE"]],
                                "DESCRIPTION" => $value[$this->mess["IBLOCK_XML2_NAME"]],
                            );
                            $i++;
                        }
                    }
                }

                if (isset($arXMLElement[$this->mess["IBLOCK_XML2_WEIGHT"]])) {
                    if ($weightKey !== false) {
                    } elseif (!isset($arElement["PROPERTY_VALUES"][$this->PROPERTY_MAP["CML2_TRAITS"]])) {
                        $arElement["PROPERTY_VALUES"][$this->PROPERTY_MAP["CML2_TRAITS"]] = array();
                        $weightKey = "n0";
                    } else // $weightKey === false && isset($arElement["PROPERTY_VALUES"][$this->PROPERTY_MAP["CML2_TRAITS"]])
                    {
                        $weightKey = "n" . $i;
                    }
                    $arElement["PROPERTY_VALUES"][$this->PROPERTY_MAP["CML2_TRAITS"]][$weightKey] = array(
                        "VALUE" => $arXMLElement[$this->mess["IBLOCK_XML2_WEIGHT"]],
                        "DESCRIPTION" => $this->mess["IBLOCK_XML2_WEIGHT"],
                    );
                    $arElement["BASE_WEIGHT"] = $this->ToFloat($arXMLElement[$this->mess["IBLOCK_XML2_WEIGHT"]]) * 1000;
                }

                if ($cleanCml2FilesProperty) {
                    $prop_id = $this->PROPERTY_MAP["CML2_FILES"];
                    if (is_array($arElement["PROPERTY_VALUES"][$prop_id])) {
                        foreach ($arElement["PROPERTY_VALUES"][$prop_id] as $PROPERTY_VALUE_ID => $PROPERTY_VALUE) {
                            if (!$PROPERTY_VALUE_ID) {
                                unset($arElement["PROPERTY_VALUES"][$prop_id][$PROPERTY_VALUE_ID]);
                            } elseif (substr($PROPERTY_VALUE_ID, 0, 1) !== "n") {
                                $arElement["PROPERTY_VALUES"][$prop_id][$PROPERTY_VALUE_ID] = array(
                                    "tmp_name" => "",
                                    "del" => "Y",
                                );
                            }
                        }
                        unset($arElement["PROPERTY_VALUES"][$prop_id]["bOld"]);
                    }
                }

                if (isset($arXMLElement[$this->mess["IBLOCK_XML2_TAXES_VALUES"]])) {
                    $arElement["PROPERTY_VALUES"][$this->PROPERTY_MAP["CML2_TAXES"]] = array();
                    $i = 0;
                    foreach ($arXMLElement[$this->mess["IBLOCK_XML2_TAXES_VALUES"]] as $value) {
                        $arElement["PROPERTY_VALUES"][$this->PROPERTY_MAP["CML2_TAXES"]]["n" . $i] = array(
                            "VALUE" => $value[$this->mess["IBLOCK_XML2_TAX_VALUE"]],
                            "DESCRIPTION" => $value[$this->mess["IBLOCK_XML2_NAME"]],
                        );
                        $i++;
                    }
                }

                $rsBaseUnit = $this->_xml_file->GetList(
                    array("ID" => "asc"),
                    array(
                        "><LEFT_MARGIN" => array($arParent["LEFT_MARGIN"], $arParent["RIGHT_MARGIN"]),
                        "NAME" => $this->mess["IBLOCK_XML2_BASE_UNIT"],
                    ),
                    array("ID", "ATTRIBUTES")
                );
                while ($arBaseUnit = $rsBaseUnit->Fetch()) {
                    if (strlen($arBaseUnit["ATTRIBUTES"]) > 0) {
                        $info = unserialize($arBaseUnit["ATTRIBUTES"]);
                        if (
                            is_array($info)
                            && array_key_exists($this->mess["IBLOCK_XML2_CODE"], $info)
                        ) {
                            $arXMLElement[$this->mess["IBLOCK_XML2_BASE_UNIT"]] = $info[$this->mess["IBLOCK_XML2_CODE"]];
                        }
                    }
                }

                if (isset($arXMLElement[$this->mess["IBLOCK_XML2_BASE_UNIT"]])) {
                    $arElement["PROPERTY_VALUES"][$this->PROPERTY_MAP["CML2_BASE_UNIT"]] = array(
                        "n0" => $this->convertBaseUnitFromXmlToPropertyValue($arXMLElement[$this->mess["IBLOCK_XML2_BASE_UNIT"]]),
                    );
                }

                if (isset($arXMLElement[$this->mess["IBLOCK_XML2_PROPERTIES_VALUES"]])) {
                    foreach ($arXMLElement[$this->mess["IBLOCK_XML2_PROPERTIES_VALUES"]] as $value) {
                        if (!array_key_exists($this->mess["IBLOCK_XML2_ID"], $value)) {
                            continue;
                        }

                        $prop_id = $value[$this->mess["IBLOCK_XML2_ID"]];
                        unset($value[$this->mess["IBLOCK_XML2_ID"]]);

                        //Handle properties which is actually element fields
                        if (!array_key_exists($prop_id, $this->PROPERTY_MAP)) {
                            if ($prop_id == "CML2_CODE") {
                                $arElement["CODE"] = isset($value[$this->mess["IBLOCK_XML2_VALUE"]]) ? $value[$this->mess["IBLOCK_XML2_VALUE"]] : "";
                            } elseif ($prop_id == "CML2_ACTIVE") {
                                $value = array_pop($value);
                                $arElement["ACTIVE"] = ($value == "true") || intval($value) ? "Y" : "N";
                            } elseif ($prop_id == "CML2_SORT") {
                                $arElement["SORT"] = array_pop($value);
                            } elseif ($prop_id == "CML2_ACTIVE_FROM") {
                                $arElement["ACTIVE_FROM"] = CDatabase::FormatDate(array_pop($value), "YYYY-MM-DD HH:MI:SS",
                                    CLang::GetDateFormat("FULL"));
                            } elseif ($prop_id == "CML2_ACTIVE_TO") {
                                $arElement["ACTIVE_TO"] = CDatabase::FormatDate(array_pop($value), "YYYY-MM-DD HH:MI:SS",
                                    CLang::GetDateFormat("FULL"));
                            } elseif ($prop_id == "CML2_PREVIEW_TEXT") {
                                if (array_key_exists($this->mess["IBLOCK_XML2_VALUE"], $value)) {
                                    if (isset($value[$this->mess["IBLOCK_XML2_VALUE"]])) {
                                        $arElement["PREVIEW_TEXT"] = $value[$this->mess["IBLOCK_XML2_VALUE"]];
                                    } else {
                                        $arElement["PREVIEW_TEXT"] = "";
                                    }

                                    if (isset($value[$this->mess["IBLOCK_XML2_TYPE"]])) {
                                        $arElement["PREVIEW_TEXT_TYPE"] = $value[$this->mess["IBLOCK_XML2_TYPE"]];
                                    } else {
                                        $arElement["PREVIEW_TEXT_TYPE"] = "html";
                                    }
                                }
                            } elseif ($prop_id == "CML2_DETAIL_TEXT") {
                                if (array_key_exists($this->mess["IBLOCK_XML2_VALUE"], $value)) {
                                    if (isset($value[$this->mess["IBLOCK_XML2_VALUE"]])) {
                                        $arElement["DETAIL_TEXT"] = $value[$this->mess["IBLOCK_XML2_VALUE"]];
                                    } else {
                                        $arElement["DETAIL_TEXT"] = "";
                                    }

                                    if (isset($value[$this->mess["IBLOCK_XML2_TYPE"]])) {
                                        $arElement["DETAIL_TEXT_TYPE"] = $value[$this->mess["IBLOCK_XML2_TYPE"]];
                                    } else {
                                        $arElement["DETAIL_TEXT_TYPE"] = "html";
                                    }
                                }
                            } elseif ($prop_id == "CML2_PREVIEW_PICTURE") {
                                if (!is_array($this->preview) || !$arElement["PREVIEW_PICTURE"]) {
                                    $arElement["PREVIEW_PICTURE"] = $this->MakeFileArray($value[$this->mess["IBLOCK_XML2_VALUE"]],
                                        array("PREVIEW_PICTURE"));
                                    $arElement["PREVIEW_PICTURE"]["COPY_FILE"] = "Y";
                                }
                            }

                            continue;
                        }

                        $prop_id = $this->PROPERTY_MAP[$prop_id];
                        $prop_type = $this->arProperties[$prop_id]["PROPERTY_TYPE"];

                        if (!array_key_exists($prop_id, $arElement["PROPERTY_VALUES"])) {
                            $arElement["PROPERTY_VALUES"][$prop_id] = array();
                        }

                        //check for bitrix extended format
                        if (array_key_exists($this->mess["IBLOCK_XML2_PROPERTY_VALUE"], $value)) {
                            $i = 1;
                            $strPV = $this->mess["IBLOCK_XML2_PROPERTY_VALUE"];
                            $lPV = strlen($strPV);
                            foreach ($value as $k => $prop_value) {
                                if (substr($k, 0, $lPV) === $strPV) {
                                    if (array_key_exists($this->mess["IBLOCK_XML2_SERIALIZED"], $prop_value)) {
                                        $prop_value[$this->mess["IBLOCK_XML2_VALUE"]] = $this->Unserialize($prop_value[$this->mess["IBLOCK_XML2_VALUE"]]);
                                    }
                                    if ($prop_type == "F") {
                                        $prop_value[$this->mess["IBLOCK_XML2_VALUE"]] = $this->MakeFileArray($prop_value[$this->mess["IBLOCK_XML2_VALUE"]],
                                            array($prop_id));
                                    } elseif ($prop_type == "G") {
                                        $prop_value[$this->mess["IBLOCK_XML2_VALUE"]] = $this->GetSectionByXML_ID($this->arProperties[$prop_id]["LINK_IBLOCK_ID"],
                                            $prop_value[$this->mess["IBLOCK_XML2_VALUE"]]);
                                    } elseif ($prop_type == "E") {
                                        $prop_value[$this->mess["IBLOCK_XML2_VALUE"]] = $this->GetElementByXML_ID($this->arProperties[$prop_id]["LINK_IBLOCK_ID"],
                                            $prop_value[$this->mess["IBLOCK_XML2_VALUE"]]);
                                    } elseif ($prop_type == "L") {
                                        $prop_value[$this->mess["IBLOCK_XML2_VALUE"]] = $this->GetEnumByXML_ID($this->arProperties[$prop_id]["ID"],
                                            $prop_value[$this->mess["IBLOCK_XML2_VALUE"]]);
                                    }

                                    if (array_key_exists("bOld", $arElement["PROPERTY_VALUES"][$prop_id])) {
                                        if ($prop_type == "F") {
                                            foreach ($arElement["PROPERTY_VALUES"][$prop_id] as $PROPERTY_VALUE_ID => $PROPERTY_VALUE) {
                                                $arElement["PROPERTY_VALUES"][$prop_id][$PROPERTY_VALUE_ID] = array(
                                                    "tmp_name" => "",
                                                    "del" => "Y",
                                                );
                                            }
                                            unset($arElement["PROPERTY_VALUES"][$prop_id]["bOld"]);
                                        } else {
                                            $arElement["PROPERTY_VALUES"][$prop_id] = array();
                                        }
                                    }

                                    $arElement["PROPERTY_VALUES"][$prop_id]["n" . $i] = array(
                                        "VALUE" => $prop_value[$this->mess["IBLOCK_XML2_VALUE"]],
                                        "DESCRIPTION" => $prop_value[$this->mess["IBLOCK_XML2_DESCRIPTION"]],
                                    );
                                    $i++;
                                }
                            }
                        } else {
                            if ($prop_type == "L" && !array_key_exists($this->mess["IBLOCK_XML2_VALUE_ID"], $value)) {
                                $l_key = $this->mess["IBLOCK_XML2_VALUE"];
                            } else {
                                $l_key = $this->mess["IBLOCK_XML2_VALUE_ID"];
                            }

                            $i = 0;
                            foreach ($value as $k => $prop_value) {
                                if (array_key_exists("bOld", $arElement["PROPERTY_VALUES"][$prop_id])) {
                                    if ($prop_type == "F") {
                                        foreach ($arElement["PROPERTY_VALUES"][$prop_id] as $PROPERTY_VALUE_ID => $PROPERTY_VALUE) {
                                            $arElement["PROPERTY_VALUES"][$prop_id][$PROPERTY_VALUE_ID] = array(
                                                "tmp_name" => "",
                                                "del" => "Y",
                                            );
                                        }
                                        unset($arElement["PROPERTY_VALUES"][$prop_id]["bOld"]);
                                    } else {
                                        $arElement["PROPERTY_VALUES"][$prop_id] = array();
                                    }
                                }

                                if ($prop_type == "L" && $k == $l_key) {
                                    $prop_value = $this->GetEnumByXML_ID($this->arProperties[$prop_id]["ID"], $prop_value);
                                } elseif ($prop_type == "N" && isset($this->next_step["sdp"])) {
                                    if (strlen($prop_value) > 0) {
                                        $prop_value = $this->ToFloat($prop_value);
                                    }
                                }

                                $arElement["PROPERTY_VALUES"][$prop_id]["n" . $i] = array(
                                    "VALUE" => $prop_value,
                                    "DESCRIPTION" => false,
                                );
                                $i++;
                            }
                        }
                    }
                }

                //If there is no BaseUnit specified check prices for it
                if (
                    (
                        !array_key_exists($this->PROPERTY_MAP["CML2_BASE_UNIT"], $arElement["PROPERTY_VALUES"])
                        || (
                            is_array($arElement["PROPERTY_VALUES"][$this->PROPERTY_MAP["CML2_BASE_UNIT"]])
                            && array_key_exists("bOld",
                                $arElement["PROPERTY_VALUES"][$this->PROPERTY_MAP["CML2_BASE_UNIT"]])
                        )
                    )
                    && isset($arXMLElement[$this->mess["IBLOCK_XML2_PRICES"]])
                ) {
                    foreach ($arXMLElement[$this->mess["IBLOCK_XML2_PRICES"]] as $price) {
                        if (
                            isset($price[$this->mess["IBLOCK_XML2_PRICE_TYPE_ID"]])
                            && array_key_exists($price[$this->mess["IBLOCK_XML2_PRICE_TYPE_ID"]], $this->PRICES_MAP)
                            && array_key_exists($this->mess["IBLOCK_XML2_MEASURE"], $price)
                        ) {
                            $arElement["PROPERTY_VALUES"][$this->PROPERTY_MAP["CML2_BASE_UNIT"]] = array(
                                "n0" => $this->convertBaseUnitFromXmlToPropertyValue($price[$this->mess["IBLOCK_XML2_MEASURE"]]),
                            );
                            break;
                        }
                    }
                }

                if ($arDBElement) {
                    foreach ($arElement["PROPERTY_VALUES"] as $prop_id => $prop) {
                        if (is_array($arElement["PROPERTY_VALUES"][$prop_id]) && array_key_exists("bOld",
                                $arElement["PROPERTY_VALUES"][$prop_id])) {
                            if ($this->arProperties[$prop_id]["PROPERTY_TYPE"] == "F") {
                                unset($arElement["PROPERTY_VALUES"][$prop_id]);
                            } else {
                                unset($arElement["PROPERTY_VALUES"][$prop_id]["bOld"]);
                            }
                        }
                    }

                    if (intval($arElement["MODIFIED_BY"]) <= 0 && $USER_ID > 0) {
                        $arElement["MODIFIED_BY"] = $USER_ID;
                    }

                    if (!array_key_exists("CODE", $arElement) && is_array($this->translit_on_update)) {
                        $CODE = CUtil::translit($arElement["NAME"], LANGUAGE_ID, $this->translit_on_update);
                        $CODE = $this->CheckElementCode($this->next_step["IBLOCK_ID"], $arDBElement["ID"], $CODE);
                        if ($CODE !== false) {
                            $arElement["CODE"] = $CODE;
                        }
                    }

                    //Check if detail picture hasn't been changed
                    if (
                        isset($arElement["DETAIL_PICTURE"])
                        && !isset($arElement["PREVIEW_PICTURE"])
                        && is_array($arElement["DETAIL_PICTURE"])
                        && isset($arElement["DETAIL_PICTURE"]["external_id"])
                        && $this->arElementFilesId
                        && $this->arElementFilesId["DETAIL_PICTURE"]
                        && isset($this->arElementFiles[$this->arElementFilesId["DETAIL_PICTURE"][0]])
                        && $this->arElementFiles[$this->arElementFilesId["DETAIL_PICTURE"][0]]["EXTERNAL_ID"] === $arElement["DETAIL_PICTURE"]["external_id"]
                        && $this->arElementFiles[$this->arElementFilesId["DETAIL_PICTURE"][0]]["DESCRIPTION"] === $arElement["DETAIL_PICTURE"]["description"]
                    ) {
                        unset($arElement["DETAIL_PICTURE"]);
                    }

                    $updateResult = $obElement->Update($arDBElement["ID"], $arElement, $bWF, true, $this->iblock_resize);
                    //In case element was not active in database we have to activate its offers
                    if ($arDBElement["ACTIVE"] != "Y") {
                        $this->ChangeOffersStatus($arDBElement["ID"], "Y", $bWF);
                    }
                    $arElement["ID"] = $arDBElement["ID"];
                    if ($updateResult) {
                        $counter["UPD"]++;
                    } else {
                        $this->LAST_ERROR = $obElement->LAST_ERROR;
                        $counter["ERR"]++;
                    }
                } else {
                    if (!array_key_exists("CODE", $arElement) && is_array($this->translit_on_add)) {
                        $CODE = CUtil::translit($arElement["NAME"], LANGUAGE_ID, $this->translit_on_add);
                        $CODE = $this->CheckElementCode($this->next_step["IBLOCK_ID"], 0, $CODE);
                        if ($CODE !== false) {
                            $arElement["CODE"] = $CODE;
                        }
                    }

                    $arElement["IBLOCK_ID"] = $this->next_step["IBLOCK_ID"];
                    $this->fillDefaultPropertyValues($arElement, $this->arProperties);

                    $arElement["ID"] = $obElement->Add($arElement, $bWF, true, $this->iblock_resize);
                    if ($arElement["ID"]) {
                        $counter["ADD"]++;
                    } else {
                        $this->LAST_ERROR = $obElement->LAST_ERROR;
                        $counter["ERR"]++;
                    }
                }
            }else{

                if (array_key_exists($this->mess["IBLOCK_XML2_PICTURE"], $arXMLElement)) {
                    $rsFiles = $this->_xml_file->GetList(
                        array("ID" => "asc"),
                        array("PARENT_ID" => $arParent["ID"], "NAME" => $this->mess["IBLOCK_XML2_PICTURE"])
                    );
                    $arFile = $rsFiles->Fetch();
                    if ($arFile) {
                        if (strlen($arFile["VALUE"]) > 0) {
                            if(file_exists($this->files_dir.trim($arFile['VALUE']))){
                                $arElement["PREVIEW_PICTURE"] = $arElement['DETAIL_PICTURE'] = \CFile::MakeFileArray($this->files_dir . trim($arFile['VALUE']));
                            }
                        } else {
                            \_::d("Пока не понятно что тут");
//                        $arElement["DETAIL_PICTURE"] = $this->MakeFileArray($this->_xml_file->GetAllChildrenArray($arFile["ID"]));
//
//                        if(is_array($arElement["DETAIL_PICTURE"]))
//                        {
//                            $arElement["DETAIL_PICTURE"]["description"] = $description;
//                        }
                        }

                        $prop_id = $this->PROPERTY_MAP["CML2_PICTURES"];
                        if ($prop_id > 0) {
                            $i = 1;
                            //Если картинок больше чем 1, то запишим их в свойство
                            while ($arFile = $rsFiles->Fetch()) {
                                $description = "";

                                if (strlen($arFile["VALUE"]) > 0) {
                                    $arPropFile = $this->ResizePicture($arFile["VALUE"], $this->detail,$this->PROPERTY_MAP["CML2_PICTURES"], "DETAIL_PICTURE");
                                } else {
                                    $arPropFile = $this->MakeFileArray($this->_xml_file->GetAllChildrenArray($arFile["ID"]));
                                }

                                if (is_array($arPropFile)) {
                                    $arPropFile = array(
                                        "VALUE" => $arPropFile,
                                        "DESCRIPTION" => $description,
                                    );
                                }
                                $arElement["PROPERTY_VALUES"][$prop_id]["n" . $i] = $arPropFile;
                                if (strlen($arFile["VALUE"]) > 0) {
                                    $this->arFileDescriptionsMap[$arFile["VALUE"]][] = &$arElement["PROPERTY_VALUES"][$prop_id]["n" . $i]["DESCRIPTION"];
                                }
                                $i++;
                            }

                            if (is_array($arElement["PROPERTY_VALUES"][$prop_id])) {
                                foreach ($arElement["PROPERTY_VALUES"][$prop_id] as $PROPERTY_VALUE_ID => $PROPERTY_VALUE) {
                                    if (!$PROPERTY_VALUE_ID) {
                                        unset($arElement["PROPERTY_VALUES"][$prop_id][$PROPERTY_VALUE_ID]);
                                    } elseif (substr($PROPERTY_VALUE_ID, 0, 1) !== "n") {
                                        $arElement["PROPERTY_VALUES"][$prop_id][$PROPERTY_VALUE_ID] = array("tmp_name" => "","del" => "Y",);
                                    }
                                }
                                unset($arElement["PROPERTY_VALUES"][$prop_id]["bOld"]);
                            }
                        }
                    }

                    if (isset($arXMLElement[$this->mess["IBLOCK_XML2_NAME"]])) {
                        $arElement["NAME"] = $arXMLElement[$this->mess["IBLOCK_XML2_NAME"]];
                    }

                $updateResult = $obElement->Update($arDBElement["ID"], $arElement );

                if ($updateResult) {
                    $counter["UPD"]++;
                } else {
                    $this->LAST_ERROR = $obElement->LAST_ERROR;
                    $counter["ERR"]++;
                }
                }
            }

        } elseif (array_key_exists($this->mess["IBLOCK_XML2_PRICES"], $arXMLElement)) {
            //Collect price information for future use
            $arElement["PRICES"] = array();
            if (is_array($arXMLElement[$this->mess["IBLOCK_XML2_PRICES"]])) {
                foreach ($arXMLElement[$this->mess["IBLOCK_XML2_PRICES"]] as $price) {
                    if (isset($price[$this->mess["IBLOCK_XML2_PRICE_TYPE_ID"]]) && array_key_exists($price[$this->mess["IBLOCK_XML2_PRICE_TYPE_ID"]],
                            $this->PRICES_MAP)) {
                        $price["PRICE"] = $this->PRICES_MAP[$price[$this->mess["IBLOCK_XML2_PRICE_TYPE_ID"]]];
                        $arElement["PRICES"][] = $price;
                    }
                }
            }

            $arElement["DISCOUNTS"] = array();
            if (isset($arXMLElement[$this->mess["IBLOCK_XML2_DISCOUNTS"]])) {
                foreach ($arXMLElement[$this->mess["IBLOCK_XML2_DISCOUNTS"]] as $discount) {
                    if (
                        isset($discount[$this->mess["IBLOCK_XML2_DISCOUNT_CONDITION"]])
                        && $discount[$this->mess["IBLOCK_XML2_DISCOUNT_CONDITION"]] === $this->mess["IBLOCK_XML2_DISCOUNT_COND_VOLUME"]
                    ) {
                        $discount_value = $this->ToInt($discount[$this->mess["IBLOCK_XML2_DISCOUNT_COND_VALUE"]]);
                        $discount_percent = $this->ToFloat($discount[$this->mess["IBLOCK_XML2_DISCOUNT_COND_PERCENT"]]);
                        if ($discount_value > 0 && $discount_percent > 0) {
                            $arElement["DISCOUNTS"][$discount_value] = $discount_percent;
                        }
                    }
                }
            }

            if ($arDBElement) {
                $arElement["ID"] = $arDBElement["ID"];
                $counter["UPD"]++;
            }
        }

        if (isset($arXMLElement[$this->mess["IBLOCK_XML2_STORE_AMOUNT_LIST"]])) {
            $arElement["STORE_AMOUNT"] = array();
            foreach ($arXMLElement[$this->mess["IBLOCK_XML2_STORE_AMOUNT_LIST"]] as $storeAmount) {
                if (isset($storeAmount[$this->mess["IBLOCK_XML2_STORE_ID"]])) {
                    $storeXMLID = $storeAmount[$this->mess["IBLOCK_XML2_STORE_ID"]];
                    $amount = $this->ToFloat($storeAmount[$this->mess["IBLOCK_XML2_AMOUNT"]]);
                    $arElement["STORE_AMOUNT"][$storeXMLID] = $amount;
                }
            }
        } elseif (
            array_key_exists($this->mess["IBLOCK_XML2_STORES"], $arXMLElement)
            || array_key_exists($this->mess["IBLOCK_XML2_STORE"], $arXMLElement)
        ) {
            $arElement["STORE_AMOUNT"] = array();
            $rsStores = $this->_xml_file->GetList(
                array("ID" => "asc"),
                array(
                    "><LEFT_MARGIN" => array($arParent["LEFT_MARGIN"], $arParent["RIGHT_MARGIN"]),
                    "NAME" => $this->mess["IBLOCK_XML2_STORE"],
                ),
                array("ID", "ATTRIBUTES")
            );
            while ($arStore = $rsStores->Fetch()) {
                if (strlen($arStore["ATTRIBUTES"]) > 0) {
                    $info = unserialize($arStore["ATTRIBUTES"]);
                    if (
                        is_array($info)
                        && array_key_exists($this->mess["IBLOCK_XML2_STORE_ID"], $info)
                        && array_key_exists($this->mess["IBLOCK_XML2_STORE_AMOUNT"], $info)
                    ) {
                        $arElement["STORE_AMOUNT"][$info[$this->mess["IBLOCK_XML2_STORE_ID"]]] = $this->ToFloat($info[$this->mess["IBLOCK_XML2_STORE_AMOUNT"]]);
                    }
                }
            }
        }

        if ($bMatch && $this->use_crc) {
            //nothing to do
        } elseif ($arElement["ID"] && $this->bCatalog && $this->isCatalogIblock) {
            $CML_LINK = $this->PROPERTY_MAP["CML2_LINK"];

            $arProduct = array(
                "ID" => $arElement["ID"],
            );

            if (isset($arElement["QUANTITY"])) {
                $arProduct["QUANTITY"] = (float)$arElement["QUANTITY"];
            } elseif (isset($arElement["STORE_AMOUNT"]) && !empty($arElement["STORE_AMOUNT"])) {
                $arProduct["QUANTITY"] = $this->countTotalQuantity($arElement["STORE_AMOUNT"]);
            }

            $CML_LINK_ELEMENT = $arElement["PROPERTY_VALUES"][$CML_LINK];
            if (is_array($CML_LINK_ELEMENT) && isset($CML_LINK_ELEMENT["n0"])) {
                $CML_LINK_ELEMENT = $CML_LINK_ELEMENT["n0"];
            }
            if (is_array($CML_LINK_ELEMENT) && isset($CML_LINK_ELEMENT["VALUE"])) {
                $CML_LINK_ELEMENT = $CML_LINK_ELEMENT["VALUE"];
            }

            if (isset($arElement["BASE_WEIGHT"])) {
                $arProduct["WEIGHT"] = (float)$arElement["BASE_WEIGHT"];
            } elseif ($CML_LINK_ELEMENT > 0) {
                $rsWeight = CIBlockElement::GetProperty($this->arProperties[$CML_LINK]["LINK_IBLOCK_ID"],
                    $CML_LINK_ELEMENT, array(), array("CODE" => "CML2_TRAITS"));
                while ($arWeight = $rsWeight->Fetch()) {
                    if ($arWeight["DESCRIPTION"] == $this->mess["IBLOCK_XML2_WEIGHT"]) {
                        $arProduct["WEIGHT"] = $this->ToFloat($arWeight["VALUE"]) * 1000;
                    }
                }
            }

            if ($CML_LINK_ELEMENT > 0) {
                $rsUnit = CIBlockElement::GetProperty($this->arProperties[$CML_LINK]["LINK_IBLOCK_ID"],
                    $CML_LINK_ELEMENT, array(), array("CODE" => "CML2_BASE_UNIT"));
                while ($arUnit = $rsUnit->Fetch()) {
                    if ($arUnit["DESCRIPTION"] > 0) {
                        $arProduct["MEASURE"] = $arUnit["DESCRIPTION"];
                    }
                }
            }

            if (isset($arElement["PRICES"])) {
                //Here start VAT handling

                //Check if all the taxes exists in BSM catalog
                $arTaxMap = array();
                $rsTaxProperty = CIBlockElement::GetProperty($this->arProperties[$CML_LINK]["LINK_IBLOCK_ID"],
                    $CML_LINK_ELEMENT, "sort", "asc", array("CODE" => "CML2_TAXES"));
                while ($arTaxProperty = $rsTaxProperty->Fetch()) {
                    if (
                        strlen($arTaxProperty["VALUE"]) > 0
                        && strlen($arTaxProperty["DESCRIPTION"]) > 0
                        && !array_key_exists($arTaxProperty["DESCRIPTION"], $arTaxMap)
                    ) {
                        $arTaxMap[$arTaxProperty["DESCRIPTION"]] = array(
                            "RATE" => $this->ToFloat($arTaxProperty["VALUE"]),
                            "ID" => $this->CheckTax($arTaxProperty["DESCRIPTION"],
                                $this->ToFloat($arTaxProperty["VALUE"])),
                        );
                    }
                }

                //First find out if all the prices have TAX_IN_SUM true
                $TAX_IN_SUM = "Y";
                foreach ($arElement["PRICES"] as $price) {
                    if ($price["PRICE"]["TAX_IN_SUM"] !== "true") {
                        $TAX_IN_SUM = "N";
                        break;
                    }
                }
                //If there was found not included tax we'll make sure
                //that all prices has the same flag
                if ($TAX_IN_SUM === "N") {
                    foreach ($arElement["PRICES"] as $price) {
                        if ($price["PRICE"]["TAX_IN_SUM"] !== "false") {
                            $TAX_IN_SUM = "Y";
                            break;
                        }
                    }
                    //Check if there is a mix of tax in sum
                    //and correct it by recalculating all the prices
                    if ($TAX_IN_SUM === "Y") {
                        foreach ($arElement["PRICES"] as $key => $price) {
                            if ($price["PRICE"]["TAX_IN_SUM"] !== "true") {
                                $TAX_NAME = $price["PRICE"]["TAX_NAME"];
                                if (array_key_exists($TAX_NAME, $arTaxMap)) {
                                    $PRICE_WO_TAX = $this->ToFloat($price[$this->mess["IBLOCK_XML2_PRICE_FOR_ONE"]]);
                                    $PRICE = $PRICE_WO_TAX + ($PRICE_WO_TAX / 100.0 * $arTaxMap[$TAX_NAME]["RATE"]);
                                    $arElement["PRICES"][$key][$this->mess["IBLOCK_XML2_PRICE_FOR_ONE"]] = $PRICE;
                                }
                            }
                        }
                    }
                }
                foreach ($arElement["PRICES"] as $price) {
                    $TAX_NAME = $price["PRICE"]["TAX_NAME"];
                    if (array_key_exists($TAX_NAME, $arTaxMap)) {
                        $arProduct["VAT_ID"] = $arTaxMap[$TAX_NAME]["ID"];
                        break;
                    }
                }
                $arProduct["VAT_INCLUDED"] = $TAX_IN_SUM;
            }

            $productCache = Product::getCacheItem($arProduct['ID'], true);
            if (!empty($productCache)) {
                $productResult = Product::update(
                    $arProduct['ID'],
                    array(
                        'fields' => $arProduct,
                        'external_fields' => array(
                            'IBLOCK_ID' => $this->next_step["IBLOCK_ID"]
                        )
                    )
                );
            } else {
                $productResult = Product::add(
                    array(
                        'fields' => $arProduct,
                        'external_fields' => array(
                            'IBLOCK_ID' => $this->next_step["IBLOCK_ID"]
                        )
                    )
                );
            }
            if ($productResult->isSuccess()) {
                //TODO: replace this code after upload measure ratio from 1C
                $iterator = \Bitrix\Catalog\MeasureRatioTable::getList(array(
                    'select' => array('ID'),
                    'filter' => array('=PRODUCT_ID' => $arElement['ID'])
                ));
                $ratioRow = $iterator->fetch();
                if (empty($ratioRow)) {
                    $ratioResult = \Bitrix\Catalog\MeasureRatioTable::add(array(
                        'PRODUCT_ID' => $arElement['ID'],
                        'RATIO' => 1,
                        'IS_DEFAULT' => 'Y'
                    ));
                    unset($ratioResult);
                }
                unset($ratioRow, $iterator);
            }

            if (isset($arElement["PRICES"])) {
                $this->SetProductPrice($arElement["ID"], $arElement["PRICES"], $arElement["DISCOUNTS"]);
            }

            if (isset($arElement["STORE_AMOUNT"])) {
                $this->ImportStoresAmount($arElement["STORE_AMOUNT"], $arElement["ID"], $counter);
            }
        }


        return $arElement["ID"];
    }

    /**
     * Вернет Дубликаты  свойств и XML Основного
     * @return array
     */
    function getArrKey(){
        $IBLOCK_ID = \SB\Site\Variables::IBLOCK_ID_CATALOG;
        $properties = CIBlockProperty::GetList(Array("sort" => "asc", "name" => "asc"),
            Array("ACTIVE" => "Y", "IBLOCK_ID" => $IBLOCK_ID));
        $arPropCopy = [];
        while ($prop_fields = $properties->GetNext()) {
            $arPropCopy[$prop_fields['NAME']][] = $prop_fields['XML_ID'];
        }

        foreach ($arPropCopy as $key => $item) {
            if (count($item) == 1) {
                unset($arPropCopy[$key]);
            }else{
                $tmpValue = $item[0];
                switch($key){
                    case 'Размер':
                        $tmpValue = 'CML2_RAZMER_NF';
                    break;
                }
                $arPropCopy[$key] = [
                    'MAIN' => $tmpValue,
                    'FULL' => $item
                ];
            }
        }

        return $arPropCopy;
    }

    function ImportProperties($XML_PROPERTIES_PARENT, $IBLOCK_ID)
    {
        $obProperty = new CIBlockProperty;
        $sort = 100;

        $arElementFields = array(
            "CML2_ACTIVE" => $this->mess["IBLOCK_XML2_BX_ACTIVE"],
            "CML2_CODE" => $this->mess["IBLOCK_XML2_SYMBOL_CODE"],
            "CML2_SORT" => $this->mess["IBLOCK_XML2_SORT"],
            "CML2_ACTIVE_FROM" => $this->mess["IBLOCK_XML2_START_TIME"],
            "CML2_ACTIVE_TO" => $this->mess["IBLOCK_XML2_END_TIME"],
            "CML2_PREVIEW_TEXT" => $this->mess["IBLOCK_XML2_ANONS"],
            "CML2_DETAIL_TEXT" => $this->mess["IBLOCK_XML2_DETAIL"],
            "CML2_PREVIEW_PICTURE" => $this->mess["IBLOCK_XML2_PREVIEW_PICTURE"],
        );

        $rs = $this->_xml_file->GetList(
            array("ID" => "asc"),
            array("PARENT_ID" => $XML_PROPERTIES_PARENT),
            array("ID")
        );
        while($ar = $rs->Fetch())
        {
            $XML_ENUM_PARENT = false;
            $isExternal = false;
            $arProperty = array(
            );
            $rsP = $this->_xml_file->GetList(
                array("ID" => "asc"),
                array("PARENT_ID" => $ar["ID"])
            );
            while($arP = $rsP->Fetch())
            {
                if(isset($arP["VALUE_CLOB"]))
                    $arP["VALUE"] = $arP["VALUE_CLOB"];

                if($arP["NAME"]==$this->mess["IBLOCK_XML2_ID"])
                {
                    $arProperty["XML_ID"] = $arP["VALUE"];
                    if(array_key_exists($arProperty["XML_ID"], $arElementFields))
                        break;
                }
                elseif($arP["NAME"]==$this->mess["IBLOCK_XML2_NAME"])
                    $arProperty["NAME"] = $arP["VALUE"];
                elseif($arP["NAME"]==$this->mess["IBLOCK_XML2_MULTIPLE"])
                    $arProperty["MULTIPLE"] = ($arP["VALUE"]=="true") || intval($arP["VALUE"])? "Y": "N";
                elseif($arP["NAME"]==$this->mess["IBLOCK_XML2_BX_SORT"])
                    $arProperty["SORT"] = $arP["VALUE"];
                elseif($arP["NAME"]==$this->mess["IBLOCK_XML2_BX_CODE"])
                    $arProperty["CODE"] = $arP["VALUE"];
                elseif($arP["NAME"]==$this->mess["IBLOCK_XML2_BX_DEFAULT_VALUE"])
                    $arProperty["DEFAULT_VALUE"] = $arP["VALUE"];
                elseif($arP["NAME"]==$this->mess["IBLOCK_XML2_SERIALIZED"])
                    $arProperty["SERIALIZED"] = ($arP["VALUE"]=="true") || intval($arP["VALUE"])? "Y": "N";
                elseif($arP["NAME"]==$this->mess["IBLOCK_XML2_BX_PROPERTY_TYPE"])
                {
                    $arProperty["PROPERTY_TYPE"] = $arP["VALUE"];
                    $arProperty["USER_TYPE"] = "";
                }
                elseif($arP["NAME"]==$this->mess["IBLOCK_XML2_BX_ROWS"])
                    $arProperty["ROW_COUNT"] = $arP["VALUE"];
                elseif($arP["NAME"]==$this->mess["IBLOCK_XML2_BX_COLUMNS"])
                    $arProperty["COL_COUNT"] = $arP["VALUE"];
                elseif($arP["NAME"]==$this->mess["IBLOCK_XML2_BX_LIST_TYPE"])
                    $arProperty["LIST_TYPE"] = $arP["VALUE"];
                elseif($arP["NAME"]==$this->mess["IBLOCK_XML2_BX_FILE_EXT"])
                    $arProperty["FILE_TYPE"] = $arP["VALUE"];
                elseif($arP["NAME"]==$this->mess["IBLOCK_XML2_BX_FIELDS_COUNT"])
                    $arProperty["MULTIPLE_CNT"] = $arP["VALUE"];
                elseif($arP["NAME"]==$this->mess["IBLOCK_XML2_BX_USER_TYPE"])
                    $arProperty["USER_TYPE"] = $arP["VALUE"];
                elseif($arP["NAME"]==$this->mess["IBLOCK_XML2_BX_WITH_DESCRIPTION"])
                    $arProperty["WITH_DESCRIPTION"] = ($arP["VALUE"]=="true") || intval($arP["VALUE"])? "Y": "N";
                elseif($arP["NAME"]==$this->mess["IBLOCK_XML2_BX_SEARCH"])
                    $arProperty["SEARCHABLE"] = ($arP["VALUE"]=="true") || intval($arP["VALUE"])? "Y": "N";
                elseif($arP["NAME"]==$this->mess["IBLOCK_XML2_BX_FILTER"])
                    $arProperty["FILTRABLE"] = ($arP["VALUE"]=="true") || intval($arP["VALUE"])? "Y": "N";
                elseif($arP["NAME"]==$this->mess["IBLOCK_XML2_BX_LINKED_IBLOCK"])
                    $arProperty["LINK_IBLOCK_ID"] = $this->GetIBlockByXML_ID($arP["VALUE"]);
                elseif($arP["NAME"]==$this->mess["IBLOCK_XML2_CHOICE_VALUES"])
                    $XML_ENUM_PARENT = $arP["ID"];
                elseif($arP["NAME"]==$this->mess["IBLOCK_XML2_BX_IS_REQUIRED"])
                    $arProperty["IS_REQUIRED"] = ($arP["VALUE"]=="true") || intval($arP["VALUE"])? "Y": "N";
                elseif($arP["NAME"]==$this->mess["IBLOCK_XML2_VALUES_TYPE"])
                {
                    if(
                        $arP["VALUE"] == $this->mess["IBLOCK_XML2_TYPE_LIST"]
                        && !$isExternal
                    )
                    {
                        $arProperty["PROPERTY_TYPE"] = "L";
                        $arProperty["USER_TYPE"] = "";
                    }
                    elseif($arP["VALUE"] == $this->mess["IBLOCK_XML2_TYPE_NUMBER"])
                    {
                        $arProperty["PROPERTY_TYPE"] = "N";
                        $arProperty["USER_TYPE"] = "";
                    }
                    elseif($arP["VALUE"] == $this->mess["IBLOCK_XML2_TYPE_STRING"])
                    {
                        $arProperty["PROPERTY_TYPE"] = "S";
                        $arProperty["USER_TYPE"] = "";
                    }
                    elseif($arP["VALUE"] == $this->mess["IBLOCK_XML2_USER_TYPE_DATE"])
                    {
                        $arProperty["PROPERTY_TYPE"] = "S";
                        $arProperty["USER_TYPE"] = "Date";
                    }
                    elseif($arP["VALUE"] == $this->mess["IBLOCK_XML2_USER_TYPE_DATETIME"])
                    {
                        $arProperty["PROPERTY_TYPE"] = "S";
                        $arProperty["USER_TYPE"] = "DateTime";
                    }
                }
                elseif($arP["NAME"]==$this->mess["IBLOCK_XML2_VALUES_TYPES"])
                {
                    //This property metadata contains information about it's type
                    $rsTypes = $this->_xml_file->GetList(
                        array("ID" => "asc"),
                        array("PARENT_ID" => $arP["ID"]),
                        array("ID", "LEFT_MARGIN", "RIGHT_MARGIN", "NAME")
                    );
                    $arType = $rsTypes->Fetch();
                    //We'll process only properties with NOT composing types
                    //composed types will be supported only as simple string properties
                    if($arType && !$rsTypes->Fetch())
                    {
                        $rsType = $this->_xml_file->GetList(
                            array("ID" => "asc"),
                            array("PARENT_ID" => $arType["ID"]),
                            array("ID", "LEFT_MARGIN", "RIGHT_MARGIN", "NAME", "VALUE")
                        );
                        while($arType = $rsType->Fetch())
                        {
                            if($arType["NAME"] == $this->mess["IBLOCK_XML2_TYPE"])
                            {
                                if($arType["VALUE"] == $this->mess["IBLOCK_XML2_TYPE_LIST"])
                                    $arProperty["PROPERTY_TYPE"] = "L";
                                elseif($arType["VALUE"] == $this->mess["IBLOCK_XML2_TYPE_NUMBER"])
                                    $arProperty["PROPERTY_TYPE"] = "N";
                            }
                            elseif($arType["NAME"] == $this->mess["IBLOCK_XML2_CHOICE_VALUES"])
                            {
                                $XML_ENUM_PARENT = $arType["ID"];
                            }
                        }
                    }
                }
                elseif($arP["NAME"]==$this->mess["IBLOCK_XML2_BX_USER_TYPE_SETTINGS"])
                {
                    $arProperty["USER_TYPE_SETTINGS"] = unserialize($arP["VALUE"]);
                }
                elseif($arP["NAME"]==$this->mess["IBLOCK_XML2_EXTERNAL"])
                {
                    $isExternal = ($arP["VALUE"]=="true") || intval($arP["VALUE"])? true: false;
                    if ($isExternal)
                    {
                        $arProperty["PROPERTY_TYPE"] = "S";
                        $arProperty["USER_TYPE"] = "directory";
                    }
                }
                elseif($arP["NAME"]==$this->mess["IBLOCK_XML2_BX_PROPERTY_FEATURE_LIST"])
                {
                    $arProperty["FEATURES"] = unserialize($arP["VALUE"]);
                }
            }
            $arKeyTmp = $this->getArrKey();
            if($arKeyTmp[$arProperty['NAME']]){
                $arProperty["XML_ID"] = $arKeyTmp[$arProperty['NAME']]['MAIN'];
            }
            if(array_key_exists($arProperty["XML_ID"], $arElementFields)){

                continue;

            }

            // Skip properties with no choice values
            // http://jabber.bx/view.php?id=30476
            $arEnumXmlNodes = array();
            if($XML_ENUM_PARENT)
            {
                $rsE = $this->_xml_file->GetList(
                    array("ID" => "asc"),
                    array("PARENT_ID" => $XML_ENUM_PARENT)
                );
                while($arE = $rsE->Fetch())
                {
                    if(isset($arE["VALUE_CLOB"]))
                        $arE["VALUE"] = $arE["VALUE_CLOB"];
                    $arEnumXmlNodes[] = $arE;
                }
                if (empty($arEnumXmlNodes))
                    continue;
            }

            if($arProperty["SERIALIZED"] == "Y")
                $arProperty["DEFAULT_VALUE"] = unserialize($arProperty["DEFAULT_VALUE"]);

            $rsProperty = $obProperty->GetList(array(), array("IBLOCK_ID"=>$IBLOCK_ID, "XML_ID"=>$arProperty["XML_ID"]));
            if($arDBProperty = $rsProperty->Fetch())
            {
                $bChanged = false;
                foreach($arProperty as $key=>$value)
                {
                    if($arDBProperty[$key] !== $value)
                    {
                        $bChanged = true;
                        break;
                    }
                }
                if(!$bChanged)
                    $arProperty["ID"] = $arDBProperty["ID"];
                elseif($obProperty->Update($arDBProperty["ID"], $arProperty))
                    $arProperty["ID"] = $arDBProperty["ID"];
                else
                    return $obProperty->LAST_ERROR;
            }
            else
            {
                $arProperty["IBLOCK_ID"] = $IBLOCK_ID;
                $arProperty["ACTIVE"] = "Y";
                if(!array_key_exists("PROPERTY_TYPE", $arProperty))
                    $arProperty["PROPERTY_TYPE"] = "S";
                if(!array_key_exists("SORT", $arProperty))
                    $arProperty["SORT"] = $sort;
                if(!array_key_exists("CODE", $arProperty))
                {
                    $arProperty["CODE"] = $this->safeTranslit($arProperty["NAME"]);
                    if(preg_match('/^[0-9]/', $arProperty["CODE"]))
                        $arProperty["CODE"] = '_'.$arProperty["CODE"];

                    $rsProperty = $obProperty->GetList(array(), array("IBLOCK_ID"=>$IBLOCK_ID, "CODE"=>$arProperty["CODE"]));
                    if($arDBProperty = $rsProperty->Fetch())
                    {
                        $suffix = 0;
                        do {
                            $suffix++;
                            $rsProperty = $obProperty->GetList(array(), array("IBLOCK_ID"=>$IBLOCK_ID, "CODE"=>$arProperty["CODE"]."_".$suffix));
                        } while ($rsProperty->Fetch());
                        $arProperty["CODE"] .= '_'.$suffix;
                    }
                }
                $arProperty["ID"] = $obProperty->Add($arProperty);
                if(!$arProperty["ID"])
                    return $obProperty->LAST_ERROR;
            }

            if($XML_ENUM_PARENT)
            {
                if ($isExternal)
                    $result = $this->ImportPropertyDirectory($arProperty, $arEnumXmlNodes);
                else
                    $result = $this->ImportPropertyEnum($arProperty, $arEnumXmlNodes);

                if ($result !== true)
                    return $result;
            }
            $sort += 100;
        }
        return true;
    }

    //Для типа список
    function ImportPropertyEnum($arProperty, $arEnumXmlNodes)
    {
        $arEnumMap = array();
        $arEnumKeyName = [];
        $arProperty["VALUES"] = array();
        $rsEnum = CIBlockProperty::GetPropertyEnum($arProperty["ID"]);
        while($arEnum = $rsEnum->Fetch())
        {
            $arProperty["VALUES"][$arEnum["ID"]] = $arEnum;
            $arEnumMap[$arEnum["XML_ID"]] = &$arProperty["VALUES"][$arEnum["ID"]];
            $arEnumKeyName[$arEnum["VALUE"]] = $arProperty["VALUES"][$arEnum["ID"]];
        }

        $i = 0;
        foreach($arEnumXmlNodes as $arE)
        {
            if(
                $arE["NAME"] == $this->mess["IBLOCK_XML2_CHOICE"]
                || $arE["NAME"] == $this->mess["IBLOCK_XML2_CHOICE_VALUE"]
            )
            {
                $arE = $this->_xml_file->GetAllChildrenArray($arE);
                if(isset($arE[$this->mess["IBLOCK_XML2_ID"]]))
                {
                    $xml_id = $arE[$this->mess["IBLOCK_XML2_ID"]];
                    if(!array_key_exists($xml_id, $arEnumMap))
                    {
                        $arProperty["VALUES"]["n".$i] = array();
                        $arEnumMap[$xml_id] = &$arProperty["VALUES"]["n".$i];
                        $i++;
                    }
                    $arEnumMap[$xml_id]["CML2_EXPORT_FLAG"] = true;
                    $arEnumMap[$xml_id]["XML_ID"] = $xml_id;
                    if(isset($arE[$this->mess["IBLOCK_XML2_VALUE"]]))
                        $arEnumMap[$xml_id]["VALUE"] = $arE[$this->mess["IBLOCK_XML2_VALUE"]];
                    if(isset($arE[$this->mess["IBLOCK_XML2_BY_DEFAULT"]]))
                        $arEnumMap[$xml_id]["DEF"] = ($arE[$this->mess["IBLOCK_XML2_BY_DEFAULT"]]=="true") || intval($arE[$this->mess["IBLOCK_XML2_BY_DEFAULT"]])? "Y": "N";
                    if(isset($arE[$this->mess["IBLOCK_XML2_SORT"]]))
                        $arEnumMap[$xml_id]["SORT"] = intval($arE[$this->mess["IBLOCK_XML2_SORT"]]);
                }
            }
            elseif(
                $arE["NAME"] == $this->mess["IBLOCK_XML2_TYPE_LIST"]
            )
            {
                $arE = $this->_xml_file->GetAllChildrenArray($arE);
                if(isset($arE[$this->mess["IBLOCK_XML2_VALUE_ID"]]))
                {
                    $xml_id = $arE[$this->mess["IBLOCK_XML2_VALUE_ID"]];

                    if(!empty($arEnumKeyName[$arE[$this->mess["IBLOCK_XML2_VALUE"]]])){
                        $xml_id = $arEnumKeyName[$arE[$this->mess["IBLOCK_XML2_VALUE"]]]['XML_ID'];
                    }
                    if(!array_key_exists($xml_id, $arEnumMap))
                    {
                        $arProperty["VALUES"]["n".$i] = array();
                        $arEnumMap[$xml_id] = &$arProperty["VALUES"]["n".$i];
                        $i++;
                    }
                    $arEnumMap[$xml_id]["CML2_EXPORT_FLAG"] = true;
                    $arEnumMap[$xml_id]["XML_ID"] = $xml_id;
                    if(isset($arE[$this->mess["IBLOCK_XML2_VALUE"]]))
                        $arEnumMap[$xml_id]["VALUE"] = $arE[$this->mess["IBLOCK_XML2_VALUE"]];
                }
            }


            //todo: делаем сортировку значений
            array_multisort( array_column($arEnumMap, "VALUE"), SORT_ASC, $arEnumMap );
            $sortTmp=10;
            foreach ($arEnumMap as $keyTmp => $arPropTmp){
                $arEnumMap[$keyTmp]['SORT'] = $sortTmp;
                $sortTmp +=10;
            }

        }

        $bUpdateOnly = array_key_exists("bUpdateOnly", $this->next_step) && $this->next_step["bUpdateOnly"];
        $sort = 100;

        foreach($arProperty["VALUES"] as $id=>$arEnum)
        {
            if(!isset($arEnum["CML2_EXPORT_FLAG"]))
            {
                //Delete value only when full exchange happened
                if(!$bUpdateOnly)
                    $arProperty["VALUES"][$id]["VALUE"] = "";
            }
            elseif(isset($arEnum["SORT"]))
            {
                if($arEnum["SORT"] > $sort)
                    $sort = $arEnum["SORT"] + 100;
            }
            else
            {
                $arProperty["VALUES"][$id]["SORT"] = $sort;
                $sort += 100;
            }
        }

        $obProperty = new CIBlockProperty;
        $obProperty->UpdateEnum($arProperty["ID"], $arProperty["VALUES"], false);

        return true;
    }

    function ImportPropertyDirectory($arProperty, $arEnumXmlNodes)
    {
        if (!CModule::IncludeModule('highloadblock'))
            return true;

        $rsProperty = CIBlockProperty::GetList(array(), array("ID"=>$arProperty["ID"]));
        $arProperty = $rsProperty->Fetch();
        if (!$arProperty)
            return true;

        $tableName = 'b_'.strtolower($arProperty["CODE"]);
        if (strlen($arProperty["USER_TYPE_SETTINGS"]["TABLE_NAME"]) <= 0)
        {
            $hlblock = Bitrix\Highloadblock\HighloadBlockTable::getList(array(
                "filter" => array(
                    "=TABLE_NAME" => $tableName,
                )))->fetch();
            if (!$hlblock)
            {
                $highBlockName = trim($arProperty["CODE"]);
                $highBlockName = preg_replace("/([^A-Za-z0-9]+)/", "", $highBlockName);
                if ($highBlockName == "")
                    return GetMessage("IBLOCK_XML2_HBLOCK_NAME_IS_INVALID");

                $highBlockName = strtoupper(substr($highBlockName, 0, 1)).substr($highBlockName, 1);
                $data = array(
                    'NAME' => $highBlockName,
                    'TABLE_NAME' => $tableName,
                );
                $result = Bitrix\Highloadblock\HighloadBlockTable::add($data);
                $highBlockID = $result->getId();

                $arFieldsName = array(
                    'UF_NAME' => array("Y", "string"),
                    'UF_XML_ID' => array("Y", "string"),
                    'UF_LINK' => array("N", "string"),
                    'UF_DESCRIPTION' => array("N", "string"),
                    'UF_FULL_DESCRIPTION' => array("N", "string"),
                    'UF_SORT' => array("N", "integer"),
                    'UF_FILE' => array("N", "file"),
                    'UF_DEF' => array("N", "boolean"),
                );
                $obUserField = new CUserTypeEntity();
                $sort = 100;
                foreach($arFieldsName as $fieldName => $fieldValue)
                {
                    $arUserField = array(
                        "ENTITY_ID" => "HLBLOCK_".$highBlockID,
                        "FIELD_NAME" => $fieldName,
                        "USER_TYPE_ID" => $fieldValue[1],
                        "XML_ID" => "",
                        "SORT" => $sort,
                        "MULTIPLE" => "N",
                        "MANDATORY" => $fieldValue[0],
                        "SHOW_FILTER" => "N",
                        "SHOW_IN_LIST" => "Y",
                        "EDIT_IN_LIST" => "Y",
                        "IS_SEARCHABLE" => "N",
                        "SETTINGS" => array(),
                    );
                    $obUserField->Add($arUserField);
                    $sort += 100;
                }
            }

            $arProperty["USER_TYPE_SETTINGS"]["TABLE_NAME"] = $tableName;
            $obProperty = new CIBlockProperty;
            $obProperty->Update($arProperty["ID"], $arProperty);
        }

        $hlblock = Bitrix\Highloadblock\HighloadBlockTable::getList(array(
            "filter" => array(
                "=TABLE_NAME" => $arProperty["USER_TYPE_SETTINGS"]["TABLE_NAME"],
            )))->fetch();

        $entity = Bitrix\Highloadblock\HighloadBlockTable::compileEntity($hlblock);
        $entity_data_class = $entity->getDataClass();

        $arEnumMap = array();
        $rsData = $entity_data_class::getList(array(
            "select" => array("ID", "UF_NAME", "UF_XML_ID", "UF_SORT"),
        ));
        while($arData = $rsData->fetch())
        {
            $arEnumMap[$arData["UF_XML_ID"]] = $arData;
        }

        $i = 0;
        foreach($arEnumXmlNodes as $arE)
        {
            if(
                $arE["NAME"] == $this->mess["IBLOCK_XML2_TYPE_LIST"]
            )
            {
                $arE = $this->_xml_file->GetAllChildrenArray($arE);
                if(
                    isset($arE[$this->mess["IBLOCK_XML2_VALUE_ID"]])
                    && isset($arE[$this->mess["IBLOCK_XML2_VALUE"]])
                )
                {
                    $xml_id = $arE[$this->mess["IBLOCK_XML2_VALUE_ID"]];
                    $arFields = array(
                        "UF_XML_ID" => $xml_id,
                        "UF_NAME" => $arE[$this->mess["IBLOCK_XML2_VALUE"]],
                    );
                    if (isset($arE[$this->mess["IBLOCK_XML2_PICTURE"]]))
                    {
                        $arFields["UF_FILE"] = $this->MakeFileArray($arE[$this->mess["IBLOCK_XML2_PICTURE"]]);
                    }

                    if(!array_key_exists($xml_id, $arEnumMap))
                    {
                        $entity_data_class::add($arFields);
                    }
                    elseif ($arEnumMap[$xml_id]["UF_NAME"] !== $arFields['UF_NAME'])
                    {
                        $entity_data_class::update($arEnumMap[$xml_id]["ID"], $arFields);
                    }
                }
            }
        }

        return true;
    }


    function ImportMetaData($xml_root_id, $IBLOCK_TYPE, $IBLOCK_LID, $bUpdateIBlock = true)
    {
        global $APPLICATION;

        $rs = $this->_xml_file->GetList(
            array("ID" => "asc"),
            array("ID" => $xml_root_id),
            array("ID", "NAME", "ATTRIBUTES")
        );
        $ar = $rs->Fetch();

        if ($ar)
        {
            foreach(array(LANGUAGE_ID, "en", "ru") as $lang)
            {
                $mess = Loc::loadLanguageFile(__FILE__, $lang);
                if($ar["NAME"] === $mess["IBLOCK_XML2_COMMERCE_INFO"])
                {
                    $this->mess = $mess;
                    $this->next_step["lang"] = $lang;
                }
            }
            $xml_root_id = $ar["ID"];
        }

        if($ar && (strlen($ar["ATTRIBUTES"]) > 0))
        {
            $info = unserialize($ar["ATTRIBUTES"]);
            if(is_array($info) && array_key_exists($this->mess["IBLOCK_XML2_SUM_FORMAT"], $info))
            {
                if(preg_match("#".$this->mess["IBLOCK_XML2_SUM_FORMAT_DELIM"]."=(.);{0,1}#", $info[$this->mess["IBLOCK_XML2_SUM_FORMAT"]], $match))
                {
                    $this->next_step["sdp"] = $match[1];
                }
            }
        }

        $meta_data_xml_id = false;
        $XML_ELEMENTS_PARENT = false;
        $XML_SECTIONS_PARENT = false;
        $XML_PROPERTIES_PARENT = false;
        $XML_SECTIONS_PROPERTIES_PARENT = false;
        $XML_PRICES_PARENT = false;
        $XML_STORES_PARENT = false;
        $XML_BASE_UNITS_PARENT = false;
        $XML_SECTION_PROPERTIES = false;
        $arIBlock = array();

        $this->next_step["bOffer"] = false;
        $rs = $this->_xml_file->GetList(
            array(),

            array("PARENT_ID" => $xml_root_id, "NAME" => $this->mess["IBLOCK_XML2_CATALOG"]),
            array("ID", "ATTRIBUTES")
        );
        $ar = $rs->Fetch();
        if(!$ar)
        {
            $rs = $this->_xml_file->GetList(
                array(),
                array("PARENT_ID" => $xml_root_id, "NAME" => $this->mess["IBLOCK_XML2_OFFER_LIST"]),
                array("ID", "ATTRIBUTES")
            );
            $ar = $rs->Fetch();
            $this->next_step["bOffer"] = true;
        }
        if(!$ar)
        {
            $rs = $this->_xml_file->GetList(
                array(),
                array("PARENT_ID" => $xml_root_id, "NAME" => $this->mess["IBLOCK_XML2_OFFERS_CHANGE"]),
                array("ID", "ATTRIBUTES")
            );
            $ar = $rs->Fetch();
            $this->next_step["bOffer"] = true;
            $this->next_step["bUpdateOnly"] = true;
            $bUpdateIBlock = false;
        }

        if ($this->next_step["bOffer"] && !$this->bCatalog)
            return GetMessage('IBLOCK_XML2_MODULE_CATALOG_IS_ABSENT');
        if($ar)
        {
            if(strlen($ar["ATTRIBUTES"]) > 0)
            {
                $attrs = unserialize($ar["ATTRIBUTES"]);
                if(is_array($attrs))
                {
                    if(array_key_exists($this->mess["IBLOCK_XML2_UPDATE_ONLY"], $attrs))
                        $this->next_step["bUpdateOnly"] = ($attrs[$this->mess["IBLOCK_XML2_UPDATE_ONLY"]]=="true") || intval($attrs[$this->mess["IBLOCK_XML2_UPDATE_ONLY"]])? true: false;
                }
            }

            $rs = $this->_xml_file->GetList(
                array("ID" => "asc"),
                array("PARENT_ID" => $ar["ID"])
            );
            while($ar = $rs->Fetch())
            {

                if(isset($ar["VALUE_CLOB"]))
                    $ar["VALUE"] = $ar["VALUE_CLOB"];

                if($ar["NAME"] == $this->mess["IBLOCK_XML2_ID"])
                    $arIBlock["XML_ID"] = ($this->use_iblock_type_id? $IBLOCK_TYPE."-": "").$ar["VALUE"];
                elseif($ar["NAME"] == $this->mess["IBLOCK_XML2_CATALOG_ID"])
                    $arIBlock["CATALOG_XML_ID"] = ($this->use_iblock_type_id? $IBLOCK_TYPE."-": "").$ar["VALUE"];
                elseif($ar["NAME"] == $this->mess["IBLOCK_XML2_NAME"])
                    $arIBlock["NAME"] = $ar["VALUE"];
                elseif($ar["NAME"] == $this->mess["IBLOCK_XML2_DESCRIPTION"])
                {
                    $arIBlock["DESCRIPTION"] = $ar["VALUE"];
                    $arIBlock["DESCRIPTION_TYPE"] = "html";
                }
                elseif($ar["NAME"] == $this->mess["IBLOCK_XML2_POSITIONS"] || $ar["NAME"] == $this->mess["IBLOCK_XML2_OFFERS"])
                    $XML_ELEMENTS_PARENT = $ar["ID"];
                elseif($ar["NAME"] == $this->mess["IBLOCK_XML2_PRICE_TYPES"])
                    $XML_PRICES_PARENT = $ar["ID"];
                elseif($ar["NAME"] == $this->mess["IBLOCK_XML2_STORES"])
                    $XML_STORES_PARENT = $ar["ID"];
                elseif($ar["NAME"] == $this->mess["IBLOCK_XML2_BASE_UNITS"])
                    $XML_BASE_UNITS_PARENT = $ar["ID"];
                elseif($ar["NAME"] == $this->mess["IBLOCK_XML2_METADATA_ID"])
                    $meta_data_xml_id = $ar["VALUE"];
                elseif($ar["NAME"] == $this->mess["IBLOCK_XML2_UPDATE_ONLY"])
                    $this->next_step["bUpdateOnly"] = ($ar["VALUE"]=="true") || intval($ar["VALUE"])? true: false;
                elseif($ar["NAME"] == $this->mess["IBLOCK_XML2_BX_CODE"])
                    $arIBlock["CODE"] = $ar["VALUE"];
                elseif($ar["NAME"] == $this->mess["IBLOCK_XML2_BX_SORT"])
                    $arIBlock["SORT"] = $ar["VALUE"];
                elseif($ar["NAME"] == $this->mess["IBLOCK_XML2_BX_LIST_URL"])
                    $arIBlock["LIST_PAGE_URL"] = $ar["VALUE"];
                elseif($ar["NAME"] == $this->mess["IBLOCK_XML2_BX_DETAIL_URL"])
                    $arIBlock["DETAIL_PAGE_URL"] = $ar["VALUE"];
                elseif($ar["NAME"] == $this->mess["IBLOCK_XML2_BX_SECTION_URL"])
                    $arIBlock["SECTION_PAGE_URL"] = $ar["VALUE"];
                elseif($ar["NAME"] == $this->mess["IBLOCK_XML2_BX_CANONICAL_URL"])
                    $arIBlock["CANONICAL_PAGE_URL"] = $ar["VALUE"];
                elseif($ar["NAME"] == $this->mess["IBLOCK_XML2_BX_INDEX_ELEMENTS"])
                    $arIBlock["INDEX_ELEMENT"] = ($ar["VALUE"]=="true") || intval($ar["VALUE"])? "Y": "N";
                elseif($ar["NAME"] == $this->mess["IBLOCK_XML2_BX_INDEX_SECTIONS"])
                    $arIBlock["INDEX_SECTION"] = ($ar["VALUE"]=="true") || intval($ar["VALUE"])? "Y": "N";
                elseif($ar["NAME"] == $this->mess["IBLOCK_XML2_BX_SECTIONS_NAME"])
                    $arIBlock["SECTIONS_NAME"] = $ar["VALUE"];
                elseif($ar["NAME"] == $this->mess["IBLOCK_XML2_BX_SECTION_NAME"])
                    $arIBlock["SECTION_NAME"] = $ar["VALUE"];
                elseif($ar["NAME"] == $this->mess["IBLOCK_XML2_BX_ELEMENTS_NAME"])
                    $arIBlock["ELEMENTS_NAME"] = $ar["VALUE"];
                elseif($ar["NAME"] == $this->mess["IBLOCK_XML2_BX_ELEMENT_NAME"])
                    $arIBlock["ELEMENT_NAME"] = $ar["VALUE"];
                elseif($ar["NAME"] == $this->mess["IBLOCK_XML2_BX_PICTURE"])
                {
                    if(strlen($ar["VALUE"]) > 0)
                        $arIBlock["PICTURE"] = $this->MakeFileArray($ar["VALUE"]);
                    else
                        $arIBlock["PICTURE"] = $this->MakeFileArray($this->_xml_file->GetAllChildrenArray($ar["ID"]));
                }
                elseif($ar["NAME"] == $this->mess["IBLOCK_XML2_BX_WORKFLOW"])
                    $arIBlock["WORKFLOW"] = ($ar["VALUE"]=="true") || intval($ar["VALUE"])? "Y": "N";
                elseif($ar["NAME"] == $this->mess["IBLOCK_XML2_INHERITED_TEMPLATES"])
                {
                    $arIBlock["IPROPERTY_TEMPLATES"] = array();
                    $arTemplates = $this->_xml_file->GetAllChildrenArray($ar["ID"]);
                    foreach($arTemplates as $TEMPLATE)
                    {
                        $id = $TEMPLATE[$this->mess["IBLOCK_XML2_ID"]];
                        $template = $TEMPLATE[$this->mess["IBLOCK_XML2_VALUE"]];
                        if(strlen($id) > 0 && strlen($template) > 0)
                            $arIBlock["IPROPERTY_TEMPLATES"][$id] = $template;
                    }
                }
                elseif($ar["NAME"] == $this->mess["IBLOCK_XML2_LABELS"])
                {
                    $arLabels = $this->_xml_file->GetAllChildrenArray($ar["ID"]);
                    foreach($arLabels as $arLabel)
                    {
                        $id = $arLabel[$this->mess["IBLOCK_XML2_ID"]];
                        $label = $arLabel[$this->mess["IBLOCK_XML2_VALUE"]];
                        if(strlen($id) > 0 && strlen($label) > 0)
                            $arIBlock[$id] = $label;
                    }
                }
            }
            if($this->next_step["bOffer"] && !$this->use_offers)
            {
                if(strlen($arIBlock["CATALOG_XML_ID"]) > 0)
                {
                    $arIBlock["XML_ID"] = $arIBlock["CATALOG_XML_ID"];
                    $this->next_step["bUpdateOnly"] = true;
                }
            }


            $obIBlock = new CIBlock;
            $rsIBlocks = $obIBlock->GetList(array(), array("XML_ID"=>$arIBlock["XML_ID"]));
            $ar = $rsIBlocks->Fetch();

            //Also check for non bitrix xml file
            if(!$ar && !array_key_exists("CODE", $arIBlock))
            {
                if($this->next_step["bOffer"] && $this->use_offers)
                    $rsIBlocks = $obIBlock->GetList(array(), array("XML_ID"=>"FUTURE-1C-OFFERS"));
                else
                    $rsIBlocks = $obIBlock->GetList(array(), array("XML_ID"=>"FUTURE-1C-CATALOG"));
                $ar = $rsIBlocks->Fetch();
            }
            if($ar)
            {
                if($bUpdateIBlock && (!$this->next_step["bOffer"] || $this->use_offers))
                {
                    if($obIBlock->Update($ar["ID"], $arIBlock))
                        $arIBlock["ID"] = $ar["ID"];
                    else
                        return $obIBlock->LAST_ERROR;
                }
                else
                {
                    $arIBlock["ID"] = $ar["ID"];
                }
            }
            else
            {
                $arIBlock["IBLOCK_TYPE_ID"] = $this->CheckIBlockType($IBLOCK_TYPE);
                if(!$arIBlock["IBLOCK_TYPE_ID"])
                    return GetMessage("IBLOCK_XML2_TYPE_ADD_ERROR");
                $arIBlock["GROUP_ID"] = array(2=>"R");
                $arIBlock["LID"] = $this->CheckSites($IBLOCK_LID);
                $arIBlock["ACTIVE"] = "Y";
                $arIBlock["WORKFLOW"] = "N";
                if (
                    $this->translit_on_add
                    && !array_key_exists("CODE", $arIBlock)
                )
                {
                    $arIBlock["FIELDS"] = array(
                        "CODE" => array( "DEFAULT_VALUE" => array(
                            "TRANSLITERATION" => "Y",
                            "TRANS_LEN" => $this->translit_on_add["max_len"],
                            "TRANS_CASE" => $this->translit_on_add["change_case"],
                            "TRANS_SPACE" => $this->translit_on_add["replace_space"],
                            "TRANS_OTHER" => $this->translit_on_add["replace_other"],
                            "TRANS_EAT" => $this->translit_on_add["delete_repeat_replace"]? "Y": "N",
                        )),
                        "SECTION_CODE" => array( "DEFAULT_VALUE" => array(
                            "TRANSLITERATION" => "Y",
                            "TRANS_LEN" => $this->translit_on_add["max_len"],
                            "TRANS_CASE" => $this->translit_on_add["change_case"],
                            "TRANS_SPACE" => $this->translit_on_add["replace_space"],
                            "TRANS_OTHER" => $this->translit_on_add["replace_other"],
                            "TRANS_EAT" => $this->translit_on_add["delete_repeat_replace"]? "Y": "N",
                        )),
                    );
                }
                $arIBlock["ID"] = $obIBlock->Add($arIBlock);
                if(!$arIBlock["ID"])
                    return $obIBlock->LAST_ERROR;
            }

            //Make this catalog
            if($this->bCatalog && $this->next_step["bOffer"])
            {
                $obCatalog = new CCatalog();
                $intParentID = $this->GetIBlockByXML_ID($arIBlock["CATALOG_XML_ID"]);
                if (0 < intval($intParentID) && $this->use_offers)
                {
                    $mxSKUProp = $obCatalog->LinkSKUIBlock($intParentID,$arIBlock["ID"]);
                    if (!$mxSKUProp)
                    {
                        if ($ex = $APPLICATION->GetException())
                        {
                            $result = $ex->GetString();
                            return $result;
                        }
                    }
                    else
                    {
                        $rs = CCatalog::GetList(array(),array("IBLOCK_ID"=>$arIBlock["ID"]));
                        if($arOffer = $rs->Fetch())
                        {
                            $boolFlag = $obCatalog->Update($arIBlock["ID"],array('PRODUCT_IBLOCK_ID' => $intParentID,'SKU_PROPERTY_ID' => $mxSKUProp));
                        }
                        else
                        {
                            $boolFlag = $obCatalog->Add(array("IBLOCK_ID"=>$arIBlock["ID"], "YANDEX_EXPORT"=>"N", "SUBSCRIPTION"=>"N",'PRODUCT_IBLOCK_ID' => $intParentID,'SKU_PROPERTY_ID' => $mxSKUProp));
                        }
                        if (!$boolFlag)
                        {
                            if ($ex = $APPLICATION->GetException())
                            {
                                $result = $ex->GetString();
                                return $result;
                            }
                        }
                    }
                }
                else
                {
                    $rs = CCatalog::GetList(array(),array("IBLOCK_ID"=>$arIBlock["ID"]));
                    if(!($rs->Fetch()))
                    {
                        $boolFlag = $obCatalog->Add(array("IBLOCK_ID"=>$arIBlock["ID"], "YANDEX_EXPORT"=>"N", "SUBSCRIPTION"=>"N"));
                        if (!$boolFlag)
                        {
                            if ($ex = $APPLICATION->GetException())
                            {
                                $result = $ex->GetString();
                                return $result;
                            }
                        }
                    }
                }
            }

            //For non bitrix xml file
            //Check for mandatory properties and add them as necessary
            if(!array_key_exists("CODE", $arIBlock))
            {
                $arProperties = array(
                    "CML2_BAR_CODE" => GetMessage("IBLOCK_XML2_BAR_CODE"),
                    "CML2_ARTICLE" => GetMessage("IBLOCK_XML2_ARTICLE"),
                    "CML2_ATTRIBUTES" => array(
                        "NAME" => GetMessage("IBLOCK_XML2_ATTRIBUTES"),
                        "MULTIPLE" => "Y",
                        "WITH_DESCRIPTION" => "Y",
                        "MULTIPLE_CNT" => 1,
                    ),
                    "CML2_TRAITS" => array(
                        "NAME" => GetMessage("IBLOCK_XML2_TRAITS"),
                        "MULTIPLE" => "Y",
                        "WITH_DESCRIPTION" => "Y",
                        "MULTIPLE_CNT" => 1,
                    ),
                    "CML2_BASE_UNIT" => array(
                        "NAME" => GetMessage("IBLOCK_XML2_BASE_UNIT_NAME"),
                        "WITH_DESCRIPTION" => "Y",
                    ),
                    "CML2_TAXES" => array(
                        "NAME" => GetMessage("IBLOCK_XML2_TAXES"),
                        "MULTIPLE" => "Y",
                        "WITH_DESCRIPTION" => "Y",
                        "MULTIPLE_CNT" => 1,
                    ),
                    "CML2_PICTURES" => array(
                        "NAME" => GetMessage("IBLOCK_XML2_PICTURES"),
                        "MULTIPLE" => "Y",
                        "WITH_DESCRIPTION" => "Y",
                        "MULTIPLE_CNT" => 1,
                        "PROPERTY_TYPE" => "F",
                        "CODE" => "MORE_PHOTO",
                    ),
                    "CML2_FILES" => array(
                        "NAME" => GetMessage("IBLOCK_XML2_FILES"),
                        "MULTIPLE" => "Y",
                        "WITH_DESCRIPTION" => "Y",
                        "MULTIPLE_CNT" => 1,
                        "PROPERTY_TYPE" => "F",
                        "CODE" => "FILES",
                    ),
                    "CML2_MANUFACTURER" => array(
                        "NAME" => GetMessage("IBLOCK_XML2_PROP_MANUFACTURER"),
                        "MULTIPLE" => "N",
                        "WITH_DESCRIPTION" => "N",
                        "MULTIPLE_CNT" => 1,
                        "PROPERTY_TYPE" => "L",
                    ),
                    "CML2_RAZMER_NF" => array(
                        "NAME" => 'Размер',
                        "MULTIPLE" => "N",
                        "WITH_DESCRIPTION" => "N",
                        "MULTIPLE_CNT" => 1,
                        "PROPERTY_TYPE" => "L",
                    ),
                );

                foreach($arProperties as $k=>$v)
                {
                    $result = $this->CheckProperty($arIBlock["ID"], $k, $v);
                    if($result!==true)
                        return $result;
                }
                //For offers make special property: link to catalog
                if(isset($arIBlock["CATALOG_XML_ID"]) && $this->use_offers)
                    $this->CheckProperty($arIBlock["ID"], "CML2_LINK", array(
                        "NAME" => GetMessage("IBLOCK_XML2_CATALOG_ELEMENT"),
                        "PROPERTY_TYPE" => "E",
                        "USER_TYPE" => "SKU",
                        "LINK_IBLOCK_ID" => $this->GetIBlockByXML_ID($arIBlock["CATALOG_XML_ID"]),
                        "FILTRABLE" => "Y",
                    ));
            }

            $this->next_step["IBLOCK_ID"] = $arIBlock["ID"];
            $this->next_step["XML_ELEMENTS_PARENT"] = $XML_ELEMENTS_PARENT;
        }

        if($meta_data_xml_id)
        {
            $rs = $this->_xml_file->GetList(
                array(),
                array("PARENT_ID" => $xml_root_id, "NAME" => $this->mess["IBLOCK_XML2_METADATA"]),
                array("ID")
            );
            while($arMetadata = $rs->Fetch())
            {
                //Find referenced metadata
                $bMetaFound = false;
                $meta_roots = array();
                $rsMetaRoots = $this->_xml_file->GetList(
                    array("ID" => "asc"),
                    array("PARENT_ID" => $arMetadata["ID"])
                );
                while($arMeta = $rsMetaRoots->Fetch())
                {
                    if(isset($arMeta["VALUE_CLOB"]))
                        $arMeta["VALUE"] = $arMeta["VALUE_CLOB"];

                    if($arMeta["NAME"] == $this->mess["IBLOCK_XML2_ID"] && $arMeta["VALUE"] == $meta_data_xml_id)
                        $bMetaFound = true;

                    $meta_roots[] = $arMeta;
                }

                //Get xml parents of the properties and sections
                if($bMetaFound)
                {
                    foreach($meta_roots as $arMeta)
                    {
                        if($arMeta["NAME"] == $this->mess["IBLOCK_XML2_GROUPS"])
                            $XML_SECTIONS_PARENT = $arMeta["ID"];
                        elseif($arMeta["NAME"] == $this->mess["IBLOCK_XML2_PROPERTIES"])
                            $XML_PROPERTIES_PARENT = $arMeta["ID"];
                        elseif($arMeta["NAME"] == $this->mess["IBLOCK_XML2_GROUPS_PROPERTIES"])
                            $XML_SECTIONS_PROPERTIES_PARENT = $arMeta["ID"];
                        elseif($arMeta["NAME"] == $this->mess["IBLOCK_XML2_SECTION_PROPERTIES"])
                            $XML_SECTION_PROPERTIES = $arMeta["ID"];
                        elseif($arMeta["NAME"] == $this->mess["IBLOCK_XML2_PRICE_TYPES"])
                            $XML_PRICES_PARENT = $arMeta["ID"];
                        elseif($arMeta["NAME"] == $this->mess["IBLOCK_XML2_STORES"])
                            $XML_STORES_PARENT = $arMeta["ID"];
                        elseif($arMeta["NAME"] == $this->mess["IBLOCK_XML2_BASE_UNITS"])
                            $XML_BASE_UNITS_PARENT = $arMeta["ID"];
                    }
                    break;
                }
            }
        }

        $iblockFields = CIBlock::GetFields($arIBlock["ID"]);
        $iblockFields["XML_IMPORT_START_TIME"] = array(
            "NAME" => "XML_IMPORT_START_TIME",
            "IS_REQUIRED" => "N",
            "DEFAULT_VALUE" => date("Y-m-d H:i:s"),
        );
        CIBlock::SetFields($arIBlock["ID"], $iblockFields);

        if($XML_PROPERTIES_PARENT)
        {
            $result = $this->ImportProperties($XML_PROPERTIES_PARENT, $arIBlock["ID"]);
            if($result!==true)
                return $result;
        }

        if($XML_SECTION_PROPERTIES)
        {
            $result = $this->ImportSectionProperties($XML_SECTION_PROPERTIES, $arIBlock["ID"]);
            if($result!==true)
                return $result;
        }

        if($XML_SECTIONS_PROPERTIES_PARENT)
        {
            $result = $this->ImportSectionsProperties($XML_SECTIONS_PROPERTIES_PARENT, $arIBlock["ID"]);
            if($result!==true)
                return $result;
        }

        if($XML_PRICES_PARENT)
        {
            if($this->bCatalog)
            {
                $result = $this->ImportPrices($XML_PRICES_PARENT, $arIBlock["ID"], $IBLOCK_LID);
                if($result!==true)
                    return $result;
            }
        }

        if($XML_STORES_PARENT)
        {
            if($this->bCatalog)
            {
                $result = $this->ImportStores($XML_STORES_PARENT);
                if($result!==true)
                    return $result;
            }
        }

        if($XML_BASE_UNITS_PARENT)
        {
            if($this->bCatalog)
            {
                $result = $this->ImportBaseUnits($XML_BASE_UNITS_PARENT);
                if($result!==true)
                    return $result;
            }
        }

        $this->next_step["section_sort"] = 100;
        $this->next_step["XML_SECTIONS_PARENT"] = $XML_SECTIONS_PARENT;

        $rs = $this->_xml_file->GetList(
            array(),
            array("PARENT_ID" => $xml_root_id, "NAME" => $this->mess["IBLOCK_XML2_PRODUCTS_SETS"]),
            array("ID", "ATTRIBUTES")
        );
        $ar = $rs->Fetch();
        if ($ar)
        {
            $this->next_step["SETS"] = $ar["ID"];
        }

        return true;
    }
}