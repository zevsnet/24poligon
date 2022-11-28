<?php

namespace Poligon\Core\Sale;

class Catalog
{
    public static function getElementsSortFields()
    {
        global $APPLICATION;
        $ELEMENT_SORT_FIELD = [];

        $ELEMENT_SORT_FIELD['ELEMENT_SORT_FIELD'] = 'SORT';
        $ELEMENT_SORT_FIELD['ELEMENT_SORT_ORDER'] = 'ASC';

//        $ELEMENT_SORT_FIELD['ELEMENT_SORT_FIELD'] = 'PROPERTY_IN_STOCK_VALUE';
//        $ELEMENT_SORT_FIELD['ELEMENT_SORT_ORDER'] = 'DESC';

//        $ELEMENT_SORT_FIELD['ELEMENT_SORT_FIELD2'] = 'shows';
//        $ELEMENT_SORT_FIELD['ELEMENT_SORT_ORDER2'] = 'DESC';
//
//        $ELEMENT_SORT_FIELD['ELEMENT_SORT_FIELD3'] = 'PROPERTY_MINIMUM_PRICE';
//        $ELEMENT_SORT_FIELD['ELEMENT_SORT_ORDER3'] = 'asc,nulls';
//
//        $ELEMENT_SORT_FIELD['ELEMENT_SORT_FIELD4'] = 'CATALOG_QUANTITY';
//        $ELEMENT_SORT_FIELD['ELEMENT_SORT_ORDER4'] = 'asc,nulls';


        return $ELEMENT_SORT_FIELD;
    }
}