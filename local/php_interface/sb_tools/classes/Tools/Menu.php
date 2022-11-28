<?php
/**
 * Created by PhpStorm.
 * User: dnkolosov
 * Date: 30.08.2017
 * Time: 14:26
 * @author Denis Kolosov <kdnn@mail.ru>
 */

namespace SB\Tools;


class Menu
{
    /**
     * формирует вложенное дерево из стандартного результата компонента меню
     *
     * @param array $arData
     * @param array $arKeys массив ключей. Доступные значения lvl, children. Могут быть переназначены на нужные значения, по дефолту DEPTH_LEVEL, CHILDREN
     *
     * @return array
     */
    static function getTree(array $arData = array(), array $arKeys = array())
    {
        $arKeys = $arKeys + array('lvl' => 'DEPTH_LEVEL', 'children' => 'CHILDREN');

        $arResult = array(); //массив будущего дерева

        foreach ($arData as $arItem) {
            $arLink = &$arResult;

            for ($i = 1; $i < $arItem[$arKeys['lvl']]; $i++) {
                $key = array_search(end($arLink), $arLink);
                $arLink = &$arLink[$key][$arKeys['children']];
            }
            $arLink[] = $arItem;
        }

        return $arResult;
    }
}