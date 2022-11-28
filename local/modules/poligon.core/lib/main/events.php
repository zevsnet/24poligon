<?php

namespace Poligon\Core\Main;


use Bitrix\Main\Loader;
use CFormResult;

Loader::includeModule('sale');

class Events
{
    /**
     * Подлючаемся к событиям Галавный модуль
     */
    static public function addEventHandlers()
    {

        // зарегистрируем функцию как обработчик двух событий
        AddEventHandler('form', 'onAfterResultAdd', [__CLASS__, 'my_onAfterResultAddUpdate']);
        AddEventHandler('form', 'onAfterResultUpdate', [__CLASS__, 'my_onAfterResultAddUpdate']);
    }

    function my_onAfterResultAddUpdate($WEB_FORM_ID, $RESULT_ID)
    {
        // действие обработчика распространяется только на форму с ID=6
        if ($WEB_FORM_ID == 20) {
            $rsResult = CFormResult::GetDataByID($RESULT_ID, [
                'SURNAME',
                'NAME',
                'OTCHESTVO',
                'PHONE',
                'BODY',
                'KANT',
                'SUMBOL',
                'TEXT',
                'FILE',
                'COMMENTS',
                'TYPE',
            ], $arrRES, $arrANSWER);

            $TYPE = current($arrANSWER['TYPE'])['USER_TEXT'];
            $BODY = current($arrANSWER['BODY'])['USER_TEXT'];
            $KANT = current($arrANSWER['KANT'])['USER_TEXT'];
            $SUMBOL = current($arrANSWER['SUMBOL'])['USER_TEXT'];

            $productId = 9998+date('i')+date('s');
            $quantity = 1;
            $price = 200;


            $basket = \Bitrix\Sale\Basket::loadItemsForFUser(
                \Bitrix\Sale\Fuser::getId(),
                \Bitrix\Main\Context::getCurrent()->getSite()
            );

            $properties = [];
            $properties['COLOR_BODY'] = array('NAME' => 'Цвет фона','CODE' => 'COLOR_BODY','VALUE' => $BODY,'SORT' => 100);
            $properties['COLOR_KANT'] = array('NAME' => 'Цвет канта','CODE' => 'COLOR_KANT','VALUE' => $KANT,'SORT' => 110);
            $properties['COLOR_SUMBOL'] = array('NAME' => 'Цвет текста','CODE' => 'COLOR_SUMBOL','VALUE' => $SUMBOL,'SORT' => 120);

            if ($item = $basket->getExistsItem('catalog', $productId,$properties)) {
                $item->setField('QUANTITY', $item->getQuantity() + $quantity);
            } else {
                $item = $basket->createItem('catalog', $productId);
                $item->setFields([
                    'QUANTITY' => $quantity,
                    'CURRENCY' => \Bitrix\Currency\CurrencyManager::getBaseCurrency(),
                    'LID' => \Bitrix\Main\Context::getCurrent()->getSite(),
                    'PRICE' => $price,
                    'CUSTOM_PRICE' => 'Y',
                    //'NAME' => 'Нашивка именная на грудь ' . current($arrANSWER['SURNAME'])['USER_TEXT'] . ' ' . current($arrANSWER['NAME'])['USER_TEXT'] . ' ' . current($arrANSWER['OTCHESTVO'])['USER_TEXT']
                    'NAME' => 'Нашивка именная на грудь ' . $TYPE .' '. current($arrANSWER['TEXT'])['USER_TEXT']
                ]);
            }
            $basket->save();


            if(isset($properties)) {
                $basketPropertyCollection = $item->getPropertyCollection();
                $basketPropertyCollection->setProperty($properties);
                $basketPropertyCollection->save();
            }

        }
    }

}