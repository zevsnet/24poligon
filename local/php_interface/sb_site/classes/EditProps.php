<?php

namespace SB\Site;


use Bitrix\Iblock\PropertyFeatureTable;


class EditProps
{
    public $catalog_id = Variables::IBLOCK_ID_CATALOG;
    public $catalog_sku_id = Variables::IBLOCK_ID_CATALOG_OFFERS;
    public $iblock_id_excluded_props = 43;

    /*
     * Все свойства инфоблока
     */
    public function getProps(int $id_iblock)
    {
        $arr = [];
        $arr_dict = [];
        $properties = \CIBlockProperty::GetList([], ["ACTIVE" => "Y", "IBLOCK_ID" => $id_iblock]);
        while ($prop_fields = $properties->GetNext()) {
            $arr[] = $prop_fields["ID"];
            $arr_dict[$prop_fields["ID"]] = $prop_fields["NAME"];
        }
        return ['list' => $arr, 'dict' => $arr_dict];
    }

    /*
     * Списки - используемые/не используемые свойства
     */
    public function getStatPropsForIb(array $arr_id_props)
    {
        global $DB;
        $all_active_prop = [];
        $strSql = "SELECT DISTINCT IBLOCK_PROPERTY_ID FROM b_iblock_element_property";
        $res = $DB->Query($strSql, false);
        while ($i = $res->Fetch()) {
            $all_active_prop[] = $i['IBLOCK_PROPERTY_ID'];
        }

        $used = array_intersect($all_active_prop, $arr_id_props);
        $unused = array_diff($arr_id_props, $used);

        return ['used' => $used, 'unused' => $unused];
    }

    /*
     * Установка FeatureProps
     */
    public function setFeatureProps(int $propertyId, string $is_enabled, string $type_property)
    {
        $arParam = [];
        $arParam['PROPERTY_ID'] = $propertyId;
        $arParam['MODULE_ID'] = 'iblock';
        $arParam['IS_ENABLED'] = $is_enabled;
        $arParam['FEATURE_ID'] = $type_property;
//        if($type_property == 'DETAIL_PAGE_SHOW' || $type_property == 'LIST_PAGE_SHOW'){
//
//        }
//        else{
//            return false;
//        }

        $iterator = PropertyFeatureTable::getList([
            'select' => ['*'],
            'filter' => [
                '=PROPERTY_ID' => $propertyId,
                '=FEATURE_ID' => $arParam['FEATURE_ID']
            ]
        ]);
        if ($row = $iterator->fetch()) {
            $row['ID'] = (int)$row['ID'];
            PropertyFeatureTable::update(
                $row['ID'],
                ['IS_ENABLED' => $arParam['IS_ENABLED']]
            );
        } else {
            PropertyFeatureTable::add($arParam);
        }
    }


    /*
     * Установка SmartFilter
     */
    public function setSmartFilter(int $prop_id, string $is_enabled)
    {
        global $DB;


        $DB->PrepareFields("b_iblock_section_property");
            $arFields = array(
                "SMART_FILTER" =>  $is_enabled,
            );
        $strUpdate = $DB->PrepareUpdate("b_iblock_section_property", $arFields, "catalog");
        $strSql = "UPDATE b_iblock_section_property SET " . $strUpdate . " WHERE PROPERTY_ID='" . $prop_id . "' AND SECTION_ID='0'";
        $res = $DB->Query($strSql, false, "FILE: " . __FILE__ . "<br> LINE: " . __LINE__);
//            $DB->Update("b_iblock_section_property", $strUpdate, "WHERE PROPERTY_ID='" . $prop_id . "' AND SECTION_ID='0'");


    }


    /*
     * Установка всем свойствам SmartFilter и FeatureProps в истину, кроме исключений
     */
    public function setAll()
    {
        $catalog_props = $this->getProps($this->catalog_id);
        $catalog_props_sku = $this->getProps($this->catalog_sku_id);
        $all_props = array_merge($catalog_props['list'], $catalog_props_sku['list']);
        $excluded_props = $this->getExcludedProperties();
        $result_props = array_diff($all_props, $excluded_props['list']);

        foreach ($result_props as $val) {
            $this->setSmartFilter($val, 'Y');
            $this->setFeatureProps($val, 'Y', 'DETAIL_PAGE_SHOW');
            $this->setFeatureProps($val, 'Y', 'LIST_PAGE_SHOW');
        }
    }

    /*
    * Установка для исключенных свойств
    */
    public function setExcludedProperties()
    {
        $res = $this->getExcludedProperties();

        foreach ($res['dict'] as $key => $val) {
            $this->setSmartFilter($val['ID'], $val['SMART_FILTER']);
            $this->setFeatureProps($val['ID'], $val['DETAIL_PAGE_SHOW'], 'DETAIL_PAGE_SHOW');
            $this->setFeatureProps($val['ID'], $val['LIST_PAGE_SHOW'], 'LIST_PAGE_SHOW');
        }
    }


    /*
    * Получить исключенные свойства
    */
    public function getExcludedProperties()
    {
        $arr_pr = [];
        $arr_id = [];

        $res = \CIBlockElement::GetList([], ["IBLOCK_ID" => $this->iblock_id_excluded_props], false, [], []);
        while ($ob = $res->GetNextElement()) {
            $arProps = $ob->GetProperties();
            $arr_pr[] = [
                'ID' => $arProps['ID_PROPS']['VALUE'],
                'SMART_FILTER' => $arProps['SMART_FILTER']['VALUE_XML_ID'],
                'LIST_PAGE_SHOW' => $arProps['LIST_PAGE_SHOW']['VALUE_XML_ID'],
                'DETAIL_PAGE_SHOW' => $arProps['DETAIL_PAGE_SHOW']['VALUE_XML_ID'],
            ];
            $arr_id[] = $arProps['ID_PROPS']['VALUE'];
        }
        return ['list' => $arr_id, 'dict' => $arr_pr];
    }

    /*
     * Удаление всех не используемых свойств
     */
    public function deleteAllUnusedProps()
    {
        $catalog_props = $this->getProps($this->catalog_id);
        $catalog_props_sku = $this->getProps($this->catalog_sku_id);

        $stat_catalog_props = $this->getStatPropsForIb($catalog_props['list']);
        $stat_catalog_props_sku = $this->getStatPropsForIb($catalog_props_sku['list']);

        $result_unused_props = array_merge($stat_catalog_props['unused'], $stat_catalog_props_sku['unused']);

        foreach ($result_unused_props as $id) {
            \CIBlockProperty::Delete($id);
        }
    }

    /*
     * Изменяет свойства у одного элемента
     */
    public function setOneExcludedProp(int $id_elem)
    {
        $res = \CIBlockElement::GetList([], ["IBLOCK_ID" => $this->iblock_id_excluded_props, "ID" => $id_elem], false,
            [], []);
        $ob = $res->GetNextElement();
        $arProps = $ob->GetProperties();

        $this->setSmartFilter($arProps['ID_PROPS']['VALUE'], $arProps['SMART_FILTER']['VALUE_XML_ID']);
        $this->setFeatureProps($arProps['ID_PROPS']['VALUE'], $arProps['DETAIL_PAGE_SHOW']['VALUE_XML_ID'],
            'DETAIL_PAGE_SHOW');
        $this->setFeatureProps($arProps['ID_PROPS']['VALUE'], $arProps['LIST_PAGE_SHOW']['VALUE_XML_ID'],
            'LIST_PAGE_SHOW');
    }
}