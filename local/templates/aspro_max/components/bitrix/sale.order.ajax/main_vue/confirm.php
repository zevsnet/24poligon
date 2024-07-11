<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

use Bitrix\Main\Localization\Loc;
use Bitrix\Sale\Basket;
use Bitrix\Sale\Order;
use Poligon\Core\Iblock\Helper;

/**
 * @var array $arParams
 * @var array $arResult
 * @var $APPLICATION CMain
 */

if ($arParams["SET_TITLE"] == "Y") {
    $APPLICATION->SetTitle(Loc::getMessage("SOA_ORDER_COMPLETE"));
}
?>
<? if (!empty($arResult["ORDER"])): ?>
<?
//\_::d($arResult);


\Bitrix\Main\Loader::includeModule('sale');
/** Sale\Basket $order объект заказа */
$basket = Order::load($arResult["ORDER"]['ID'])->getBasket();
$obBasketOrder = $basket->getBasketItems();
/** @var \Bitrix\Sale\BasketItem $basketItem */
foreach ($obBasketOrder as $basketItem) {
    $idOffers = $basketItem->getProductId();
    $obTmpElements = CIBlockElement::GetList([], ['ID' => $basketItem->getProductId()]);
    if ($obTmpElement = $obTmpElements->GetNextElement()) {
        $arFieldsOffer = $obTmpElement->GetFields();
        $arFieldsOffer['PROP'] = $obTmpElement->GetProperties();

        if ($arFieldsOffer['PREVIEW_PICTURE']) {
            $arFieldsOffer['PREVIEW_PICTURE'] = CFile::GetPath($arFieldsOffer['PREVIEW_PICTURE']);
        }
    }
    $arTmpBasket = [
        'ID' => $basketItem->getProductId(),
        'NAME' => $basketItem->getField('NAME'),
        'LINK' => $arFieldsOffer['DETAIL_PAGE_URL'],
        'SIZE' => $arFieldsOffer['PROP']['SIZE_EU']['VALUE'],
        'PREVIEW_PICTURE' => $arFieldsOffer['PREVIEW_PICTURE'],
        'PRICE_DISPLAY' =>Helper::getFormatPrice($basketItem->getField('PRICE')),
        'QUANTITY' => intval($basketItem->getField('QUANTITY')),
    ];

    if ($arFieldsOffer['PROP']['SIZE']['VALUE']) {
        $arTmpBasket['SIZE'] = $arFieldsOffer['PROP']['SIZE']['VALUE'];
    }
    $arResult['BASKET'][] = $arTmpBasket;

}

?>

<div class="placing-an-order order-is-processed">
    <div class="placing-an-order__inner">
        <div class="placing-an-order__cont">
            <div class="order-is-processed__inner">

                <?php if($arResult['ORDER']['PAYED']=='Y' || $arResult['ORDER']['PAY_SYSTEM_ID'] ==49):  //если заказ оплачен
                    ?>  <div class="order-is-processed__header"><div class="order-is-processed__header-img-w"><picture><source srcset="/images/checked-order.png" media="(min-width: 768px)"><img class="order-is-processed__header-img" src="/images/checked-order.png" alt=""></picture></div><h1 class="main-title">Спасибо за заказ!</h1></div>
                    <p class="order-is-processed__description">Номер вашего заказа  <b style="margin-left:6px "><?= $arResult["ORDER"]["ACCOUNT_NUMBER"] ?></b></p>
                    <p class="order-is-processed__description"> детали вашего заказа всегда будут в <a class="sb_link_detail" style="margin-left: 5px" href="/personal/orders/<?=$arResult["ORDER"]["ACCOUNT_NUMBER"]?>">личном кабинете</a></p>
                    <p>Спасибо что выбрали нас!</p>
                    <p>Если у вас остались вопросы вы можете задать их в чате нашему менеджеру. Если не хотите писать можете дождаться когда с Вами свяжется сотрудник и задать их по телефону.</p>
                    <div class="order-is-processed__footer">
                        <a class="btn btn--viking has-ripple sb_open_catalog" href="/catalog/">Перейти в каталог</a>
                        <a class="btn btn--gray sb_open_jivosite" href="/">Написать вопрос по заказу</a>
                    </div>


                <?else:?>
                    <div class="order-is-processed__header"><div class="order-is-processed__header-img-w"><picture><source srcset="/images/checked-order.png" media="(min-width: 768px)"><img class="order-is-processed__header-img" src="/images/checked-order.png" alt=""></picture></div><?
                        ?><h1 class="main-title">Заказ оформлен и добавлен в <a class="sb_link_detail" href="/personal/orders/<?=$arResult["ORDER"]["ACCOUNT_NUMBER"]?>">личный кабинет</a>, осталось оплатить</h1></div>

                <?
                    if ($arResult["ORDER"]["IS_ALLOW_PAY"] === 'Y') {
                        if (!empty($arResult["PAYMENT"])) {
                            foreach ($arResult["PAYMENT"] as $payment) {


                                if ($payment["PAID"] != 'Y') {
                                    if (!empty($arResult['PAY_SYSTEM_LIST'])
                                        && array_key_exists($payment["PAY_SYSTEM_ID"], $arResult['PAY_SYSTEM_LIST'])
                                    ) {
                                        $arPaySystem = $arResult['PAY_SYSTEM_LIST_BY_PAYMENT_ID'][$payment["ID"]];

                                        if (empty($arPaySystem["ERROR"])) {
                                            ?>
                                            <table class="order-is-processed__description">
                                                <tr>
                                                    <td class="ps_logo">
                                                        <div class="pay_name"><?= Loc::getMessage("SOA_PAY") ?></div>
                                                        <?= CFile::ShowImage($arPaySystem["LOGOTIP"], 100, 100, "border=0\" style=\"width:100px\"", "", false) ?>
                                                        <div class="paysystem_name"><?= $arPaySystem["NAME"] ?></div>
                                                        <br/>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>
                                                        <?

                                                        if ($arPaySystem["ACTION_FILE"] == 'tinkoff') {

                                                        } ?>
                                                        <? if (strlen($arPaySystem["ACTION_FILE"]) > 0 && $arPaySystem["NEW_WINDOW"] == "Y" && $arPaySystem["IS_CASH"] != "Y"): ?>
                                                            <?
                                                            $orderAccountNumber = urlencode(urlencode($arResult["ORDER"]["ACCOUNT_NUMBER"]));
                                                            $paymentAccountNumber = $payment["ACCOUNT_NUMBER"];
                                                            ?>
                                                            <script>
                                                                window.open('<?=$arParams["PATH_TO_PAYMENT"]?>?ORDER_ID=<?=$orderAccountNumber?>&PAYMENT_ID=<?=$paymentAccountNumber?>');
                                                            </script>
                                                        <?= Loc::getMessage("SOA_PAY_LINK", array("#LINK#" => $arParams["PATH_TO_PAYMENT"] . "?ORDER_ID=" . $orderAccountNumber . "&PAYMENT_ID=" . $paymentAccountNumber)) ?>
                                                        <? if (CSalePdf::isPdfAvailable() && $arPaySystem['IS_AFFORD_PDF']): ?>
                                                        <br/>
                                                            <?= Loc::getMessage("SOA_PAY_PDF", array("#LINK#" => $arParams["PATH_TO_PAYMENT"] . "?ORDER_ID=" . $orderAccountNumber . "&pdf=1&DOWNLOAD=Y")) ?>
                                                        <? endif ?>
                                                        <? else: ?>
                                                            <?= $arPaySystem["BUFFERED_OUTPUT"] ?>
                                                            <?

                                                            $pattern = '/<form\s+action="([^"]+)"/i';
                                                            preg_match($pattern, $arPaySystem["BUFFERED_OUTPUT"], $matches);
                                                            $action = false;
                                                            if (isset($matches[1])) {
                                                                $action = $matches[1];

                                                                header("HTTP/1.1 301 Moved Permanently");
                                                                header("Location: " . $action);
                                                                exit();
                                                                ?>

                                                            <? } ?>
                                                        <? endif ?>
                                                    </td>
                                                </tr>
                                            </table>

                                            <?
                                        } else {
                                            ?>
                                            <span style="color:red;"><?= Loc::getMessage("SOA_ORDER_PS_ERROR") ?></span>
                                            <?
                                        }
                                    } else {
                                        ?>
                                        <span style="color:red;"><?= Loc::getMessage("SOA_ORDER_PS_ERROR") ?></span>
                                        <?
                                    }
                                }

                            }
                        }
                    } else {
                        ?>
                        <br/><strong><?= $arParams['MESS_PAY_SYSTEM_PAYABLE_ERROR'] ?></strong>
                        <?
                    }
                    ?>
                    <p class="order-is-processed__description">Номер вашего заказа <b style="margin-left:6px "> <?= $arResult["ORDER"]["ACCOUNT_NUMBER"] ?></b></p>

                    <p class=""><b>Если вы сейчас не оплатите, нечего страшного вы всегда сможете сделать это из <a class="sb_link_detail" href="/personal/orders/<?=$arResult["ORDER"]["ACCOUNT_NUMBER"]?>">личного кабинета</a></b></p>

                    <div class="order-is-processed__footer">
                        <a class="btn btn--viking has-ripple sb_open_catalog" href="/catalog/">Перейти в каталог</a>
                        <a class="btn btn--gray sb_open_jivosite" href="/">Написать вопрос по заказу</a>
                    </div>
                <?endif;?>

                <?

                ?><? else: ?>
                    <b><?= Loc::getMessage("SOA_ERROR_ORDER") ?></b>
                    <table class="sale_order_full_table">
                        <tr>
                            <td>
                                <?= Loc::getMessage("SOA_ERROR_ORDER_LOST", ["#ORDER_ID#" => htmlspecialcharsbx($arResult["ACCOUNT_NUMBER"])]) ?>
                                <?= Loc::getMessage("SOA_ERROR_ORDER_LOST1") ?>
                            </td>
                        </tr>
                    </table>
                <? endif ?>

                <div class="sb_social">
                    <!-- Put this script tag to the <head> of your page -->
                    <script type="text/javascript" src="https://vk.com/js/api/openapi.js?168"></script>

                    <!-- Put this div tag to the place, where the Group block will be -->
                    <div id="vk_groups"></div>
                    <script type="text/javascript">
                        VK.Widgets.Group("vk_groups", {mode: 3, no_cover: 1, height: 600, color1: "FFFFFF", color2: "000000", color3: "5181B8"}, 166562603);
                    </script>

                    <div id="ok_group_widget"></div>
                    <script>
                        !function (d, id, did, st) {
                            var js = d.createElement("script");
                            js.src = "https://connect.ok.ru/connect.js";
                            js.onload = js.onreadystatechange = function () {
                                if (!this.readyState || this.readyState == "loaded" || this.readyState == "complete") {
                                    if (!this.executed) {
                                        this.executed = true;
                                        setTimeout(function () {
                                            OK.CONNECT.insertGroupWidget(id,did,st);
                                        }, 0);
                                    }
                                }};
                            d.documentElement.appendChild(js);
                        }(document,"ok_group_widget","70000004592937",'{"width":250,"height":285}');
                    </script>
                </div>
            </div>
        </div>
<?/*
        <div class="placing-an-order__order">
            <h2 class="main-title">Мой заказ</h2>
            <ul class="goods-order">
                <? foreach ($arResult['BASKET'] as $basketItem): ?>
                    <li class="goods-order__item">
                        <div class="goods-order__img-w"><a href="<?= $basketItem['LINK'] ?>"><img
                                        class="goods-order__img"
                                        src="<?= $basketItem['PREVIEW_PICTURE'] ?>"
                                        alt=""></a></div>
                        <div class="goods-order__cont">
                            <? if ($basketItem['SIZE']) {
                                ?>
                                <div class="goods-order__label">Размер <?= $basketItem['SIZE'] ?></div>
                                <?
                            } ?>
                            <h3 class="goods-order__name"><a
                                        href="<?= $basketItem['LINK'] ?>"><?= $basketItem['NAME'] ?></a></h3>
                            <div class="goods-order__price">
                                <div class="price">
                                    <div class="price__current"><?= $basketItem["PRICE_DISPLAY"] ?></div>
                                </div>
                                <div class="goods-order__number"><?= $basketItem['QUANTITY'] ?> шт</div>
                            </div>
                        </div>
                    </li>
                <? endforeach; ?>
            </ul>
        </div>
*/?>


    </div>
</div>