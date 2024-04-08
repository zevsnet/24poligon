<?php

namespace Poligon\Core\Iblock;

use Bitrix\Currency\CurrencyManager;
use Bitrix\Main\Context;
use Bitrix\Sale\Basket;
use Bitrix\Sale\BasketBase;
use Bitrix\Sale\BasketItem;
use Bitrix\Sale\Fuser;
use Bitrix\Sale\Registry;
use CSaleBasket;
use Poligon\Core\RedirectsDetector;


class Events
{
    /**
     * Подлючаемся к событиям Галавный модуль
     */
    static public function addEventHandlers()
    {
        AddEventHandler('main', 'OnPageStart', Array(__CLASS__, "Redirect301"));
        AddEventHandler('form', 'onAfterResultUpdate', Array(__CLASS__, "onAfterResultAddHandler"));
    }


    public static function Redirect301()
    {
        $redirectsDetector = RedirectsDetector::getInstance();
        $redirectsDetector->detectRedirects();
    }

    static function onAfterResultAddHandler($WEB_FORM_ID, $RESULT_ID){

        $rsForm = \CForm::GetByID($WEB_FORM_ID);
        $arForm = $rsForm->Fetch();
        if($arForm && $arForm['SID'] == 'CHEVRON')
        {
            \CForm::GetResultAnswerArray(
                $WEB_FORM_ID,
                $arrColumns,
                $arrAnswers,
                $arrAnswersVarname,
                array("RESULT_ID" => $RESULT_ID)
            );
            \CFormResult::GetDataByID($RESULT_ID, array(), $arResultFields, $arAnswers);

            if($arrAnswersVarname)
            {
                //BODY
                //KANT
                //SUMBOL
                //TEXT
                //TYPE
                $nameProductCustom = 'Нашивка '.$arrAnswersVarname[$RESULT_ID]['TYPE'][0]['USER_TEXT'].' Текст нашивки: ' . $arrAnswersVarname[$RESULT_ID]['TEXT'][0]['USER_TEXT'];
                if($arrAnswersVarname[$RESULT_ID]['FILE']){

                }
                //Создаем товар с таким названием
//                $basket = Basket::loadItemsForFUser(Fuser::getId(), Context::getCurrent()->getSite());
//127846

//                $customProduct = array(
//                    "PRODUCT_ID" => 0, // Установите ID товара в 0 для создания пользовательского товара
//                    "NAME" => $nameProductCustom,
//                    "PRICE" => 200, // Установите цену товара
//                    "CURRENCY" => "RUB", // Установите валюту товара
//                    "QUANTITY" => 1, // Установите количество товара
//                    'MODULE'=>'catalog',
//                );
//// Создание нового элемента корзины
//                $basketItem = new CSaleBasket;
//
//// Добавление пользовательского товара в корзину
//                $basketItemID = $basketItem->Add($customProduct);
//
//                if ($basketItemID) {
//                    \_::d("Пользовательский товар успешно добавлен в корзину. ID: " . $basketItemID);
//                } else {
//                    \_::d("Ошибка при добавлении пользовательского товара в корзину: " );
//                    \_::dd($basketItem->LAST_ERROR);
//                }
//
//                $fields = array(
//                    "MODULE" => 'catalog',
//                    "BASE_PRICE" => 0,
//                    "PRICE" => 200,
//                    "CUSTOM_PRICE" => 'Y',
//                    "PRODUCT_ID" => 127846,
//                );
//
//                $basketItem = \Bitrix\Sale\BasketItem::create($basket, 'catalog', '127846');
//                $basketItem->setField('QUANTITY', 1);
//                $basket->addItem($basketItem);
//
//                $basket->save();
            }
        }
    }


}
