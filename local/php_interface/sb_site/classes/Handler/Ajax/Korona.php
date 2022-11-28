<?php

namespace SB\Site\Handler\Ajax;

use Bitrix\Main\HttpRequest;
use Bitrix\Sale\Basket;
use Bitrix\Sale\BasketItem;
use Bitrix\Sale\Fuser;
use SB\Handler\Ajax;
use SB\Korona\Type\Authentication;
use SB\Korona\Type\Cheque;
use SB\Korona\Type\ChequeItem;
use SB\Site\General;
use SB\Site\Korona\KoronaClient;
use SB\Site\Users;

class Korona extends Ajax
{
    public function BalanceCardUser()
    {
        global $USER;
        //$isCard = false;
        if (!$USER->isAuthorized()) {
            $status = false;
            $balance = '';

        } else {
            session_start();
            $status = false;
            $balance = 'Баланс: <a href="/personal/account/">привязать </a> или <a href="/personal/bonus-new/"> выпустить карту</a>';
            $arListCard = \SB\Site\Users::getCard2User();
            if ($arListCard) {
                $PAN = $arListCard[0]['NAME'];
                if ($PAN != '' && $PAN) {
                    if (is_null($_SESSION['SB_SUM_CARD_USER_DATE']) || time() - $_SESSION['SB_SUM_CARD_USER_DATE'] > 300) {

                        $arInfoCard = KoronaClient::getClientInfo($PAN);
                        $arInfoCard['personalInfo'] = \SB\Site\Users::getKey2Value($arInfoCard['personalInfo'], 'code');
                        $arInfoCard['entities'] = \SB\Site\Users::getKey2Value($arInfoCard['entities'], 'type');

                        if ($arInfoCard) {
                            if (empty($arInfoCard['personalInfo'])) {
                                $this->arResult["ERRORS"] = 'Карта №' . $PAN . ' не зарегистрирована.' . "\n" . '<a href="/personal/bonus-new/"></a>';
                            } else {
                                $SUM = CurrencyFormat($arInfoCard['bonusBalance']['active'], 'RUB');

                                $_SESSION['SB_SUM_CARD_USER'] = $SUM;
                                $_SESSION['SB_SUM_CARD_USER_DATE'] = time();

                                $balance = '<a href="/personal/account/">' . 'Баланс ' . $_SESSION['SB_SUM_CARD_USER'] . '</a>';
                                $status = true;
                            }
                        }

                    } else {
                        $balance = '<a href="/personal/account/">' . 'Баланс ' . $_SESSION['SB_SUM_CARD_USER'] . '</a>';
                        $status = true;
                    }
                } else {
                    $status = false;
                }
            } else {
                $status = false;
            }
        }
        $this->getResult()->setStatus($status);
        $this->getResult()->addData('balance', $balance);


    }

    public function issueCard()
    {
        global $USER;
        $status = true;
        if (!$USER->isAuthorized()) {
            $status = false;
            $balance = '';
        } else {
            if ($_REQUEST) {
                $arListCard = Users::getCard2User();

                if (!$arListCard) {
                    //Выпускаем карту
                    $resultSOAP = KoronaClient::issueCard();

                    if ($resultSOAP['status']['code'] == 0) {
                        $PAN = $resultSOAP['pan'];
                        if ($PAN) {
                            Users::addCard2UserSOAP($resultSOAP);
                        }
                    }

                    if ($PAN) {
                        $personalFields = [];
                        foreach ($_REQUEST as $key => $value) {
                            $tmp = [];
                            $tmp['code'] = $key;
                            $tmp['value'] = $value;

                            switch ($key) {
                                case 'BIRTH_DATE':
                                    $tmp['value'] = str_replace('-', '', $value);
                                    $personalFields[] = $tmp;
                                    break;

                                case 'FILL_DATE':
                                case 'EMAIL':
                                case 'FIRST_NAME':
                                case 'LAST_NAME':
                                case 'PATRONYMIC_NAME':
                                case 'GENDER':
                                case 'MOBILE_PHONE':
                                case 'SMS_PERMISSION':
                                    $personalFields[] = $tmp;
                                    break;
                                default:
                                    break;
                            }
                        }
                        KoronaClient::postClientInfo($PAN, $personalFields);

                    }
                }

                //test TODO: DELETE
                $PAN = '7780002950427143';
                if ($PAN) {
                    $personalFields = [];
                    foreach ($_REQUEST as $key => $value) {
                        $tmp = [];
                        $tmp['code'] = $key;
                        $tmp['value'] = $value;

                        switch ($key) {
                            case 'BIRTH_DATE':
                                $tmp['value'] = str_replace('-', '', $value);
                                $personalFields[] = $tmp;
                                break;

                            case 'FILL_DATE':
                            case 'EMAIL':
                            case 'FIRST_NAME':
                            case 'LAST_NAME':
                            case 'PATRONYMIC_NAME':
                            case 'GENDER':
                            case 'MOBILE_PHONE':
                            case 'SMS_PERMISSION':
                                $personalFields[] = $tmp;
                                break;
                            default:
                                break;
                        }
                    }

                    KoronaClient::postClientInfo($PAN, $personalFields);
                }
            }
        }
        $this->getResult()->setStatus($status);
    }

    public function getBonusSaleGetInfo2()
    {
        $basketUser = Fuser::getId();
        $basket = Basket::loadItemsForFUser($basketUser, SITE_ID);
        $basketItems = $basket->getBasketItems();
        $cheque = General::getChequeOrder($basketItems);

        $Price_order = $_REQUEST['Price_order'];
        $PAN = $_REQUEST['PAN'] ?: false;

        $Price_Bonus = General::getBonusSaleGetInfo2($Price_order, $cheque, $PAN);
        $this->getResult()->addData(false, $Price_Bonus);
    }

    public function authPoints()
    {
        $PAN = $_REQUEST['PAN'] ?: false;

        if (!$PAN) {
            if ($arPanUser = \SB\Site\Users::getCard2User()) {
                $PAN = $arPanUser[0]['NAME'];
            }
        }
        if ($PAN) {
            $basketUser = Fuser::getId();
            $basket = Basket::loadItemsForFUser($basketUser, SITE_ID);
            $basketItems = $basket->getBasketItems();
            $cheque = General::getChequeOrder($basketItems);

            $Price_order = $_REQUEST['Price_order'];

            $Price_Bonus = General::getBonusSaleGetInfo2($Price_order, $cheque, $PAN);//вычесление максимальной суммы скидки
            $isGift = KoronaClient::isGift(['PAN' => $PAN, 'cheque' => $cheque]);
            if ($isGift == false) {

                $checkTokenRequired = KoronaClient::checkTokenRequired(['PAN' => $PAN, 'PRICE_BONUS' => $Price_Bonus, 'cheque' => $cheque]);
                if ($checkTokenRequired['tokenRequired']) {
                    $Authentication = (new Authentication())->withPurchaseId($basketUser);
                    $getAuthToken = KoronaClient::getAuthToken(
                        [
                            'PAN' => $PAN,
                            'PRICE_BONUS' => $Price_Bonus,
                            'ORDER_SUM_ALL' => $Price_order,
                            'Authentication' => $Authentication
                        ]
                    );
                }
                $getAuthToken['tokenRequired'] = $checkTokenRequired['tokenRequired'];
            } else {
                $getAuthToken['tokenRequired'] = false;
            }

            $this->getResult()->addData(false, $getAuthToken);
        }
    }
}