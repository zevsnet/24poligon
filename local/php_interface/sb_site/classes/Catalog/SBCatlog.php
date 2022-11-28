<?php


namespace SB\Site\Catalog;


class SBCatlog
{

    public static function getElementsSortFields()
    {
        global $APPLICATION;
        $ELEMENT_SORT_FIELD = [];

        $ELEMENT_SORT_FIELD['ELEMENT_SORT_FIELD']='PROPERTY_IN_STOCK_VALUE';
        $ELEMENT_SORT_FIELD['ELEMENT_SORT_ORDER']='DESC';

        $ELEMENT_SORT_FIELD['ELEMENT_SORT_FIELD2']='SORT';
        $ELEMENT_SORT_FIELD['ELEMENT_SORT_ORDER2']='asc';

        $ELEMENT_SORT_FIELD['ELEMENT_SORT_FIELD3']='PROPERTY_MINIMUM_PRICE';
        $ELEMENT_SORT_FIELD['ELEMENT_SORT_ORDER3']='asc,nulls';

        $ELEMENT_SORT_FIELD['ELEMENT_SORT_FIELD4']='CATALOG_QUANTITY';
        $ELEMENT_SORT_FIELD['ELEMENT_SORT_ORDER4']='asc,nulls';


        if(strpos($APPLICATION->GetCurPage(),'rosgvardiya') !==false){
            $ELEMENT_SORT_FIELD['ELEMENT_SORT_FIELD']='PROPERTY_MAXIMUM_PRICE';
            $ELEMENT_SORT_FIELD['ELEMENT_SORT_ORDER']='DESC';


            unset($ELEMENT_SORT_FIELD['ELEMENT_SORT_FIELD3']);
            unset($ELEMENT_SORT_FIELD['ELEMENT_SORT_FIELD3']);
        }
//        $ELEMENT_SORT_FIELD = [];

        return $ELEMENT_SORT_FIELD;
    }
}