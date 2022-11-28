<?
require_once("sb_tools/init.php"); //подключение общих классов
require_once("sb_site/init.php");
\Bitrix\Main\Loader::includeModule('poligon.core');

// Roistat content BEGIN

use Bitrix\Main\Event;
use Bitrix\Sale;
use Bitrix\Sale\Delivery\Services\Manager;

$eventManager = \Bitrix\Main\EventManager::getInstance();
$eventManager->addEventHandler('sale', 'OnSaleOrderSaved', 'rsOnAddOrder');

function rsOnAddOrder(Event $event) {
    if(!$event->getParameter('IS_NEW')) return;
    /** @var Sale\Order $order */
    $order              = $event->getParameter('ENTITY');
    $basket             = $order->getBasket();
    $propertyCollection = $order->getPropertyCollection();

    $products = array();
    $items  = $basket->getBasketItems();
    foreach ($items as $item) {
        $products[] = array(
            'id' => $item->getId(),
            'name'  => $item->getField('NAME'),
            'price' => $item->getPrice(),
            'count' => $item->getQuantity(),
        );
    }
    $list = null;
    foreach($basket->getListOfFormatText() as $item) {
        $list .= $item."\n";
    }

    $price       = $order->getPrice();
    $discount    = $order->getDiscountPrice();
    $description = $order->getField('USER_DESCRIPTION');
    $userName  = null;
    $phone     = null;
    $email     = null;
    $address   = null;
    $location  = null;


    foreach ($propertyCollection as $property) {
        $code  = $property->getField('CODE');
        $value = $property->getValue();
        // Если в заказе есть какие либо доп. поля, их нужно указать тут.
        switch($code) {
            case 'PHONE':
                $phone = $value;
                break;
            case 'EMAIL':
                $email = $value;
                break;
            case 'F_NAME':
                $userName = $value;
                break;
            case 'LOCATION':
                $location = CSaleLocation::GetByID(CSaleLocation::getLocationIDbyCODE($value));
                break;
            case 'ADDRESS':
                $address = $value;
                break;
        }
    }

    $paymentCollection = $order->getPaymentCollection();
    $paymentName = $paymentCollection['0']->getPaymentSystemName();
    $deliverySystemId = $order->getDeliverySystemId();
    $managerById = Manager::getById($deliverySystemId['0']);
    $deliveryName = $managerById['NAME'];
    $form_name = "Корзина";

    // Следующим образом можно быстро определить не в 1 клик ли заказ
    if (array_key_exists('BUY_MODE', $_REQUEST) !== false) {
        $form_name = "В 1 клик";
        $userName = iconv('UTF-8', SITE_CHARSET, $_REQUEST['NAME']);
        $phone = $_REQUEST['PHONE'];
        $email = $_REQUEST['EMAIl'];
    }

    $comment = "{$description} \n";

    $comment .= "\n\nСписок товаров:\n".
        "{$list}\n\n".
        "Способ доставки: {$deliveryName}\n".
        "Способ оплаты: {$paymentName}\n";

    if ($order->getDeliveryPrice() > 0) {
        $comment .= 'Доставка - '.number_format($order->getDeliveryPrice(),0,'',' ')." руб\n";
    }

    if ($discount > 0) {
        $comment .= 'Скидка - '.number_format($discount,0,'',' ')." руб\n";
    }
    $comment .= "Итого - {$price} руб";

    $roistatData = array(
        'roistat' => isset($_COOKIE['roistat_visit']) ? $_COOKIE['roistat_visit'] : null,
        'key' => 'ZTI4NzNmNWNkNjc5MzIwZTM2NWE2YTM0Nzg3ZDUxY2U6MjEwNDg1',
        'comment' => $comment,
        'title' => "Заказ № " . $order->getId(),
        'name'    => $userName,
        'email'   => $email,
        'phone'   => $phone,
        'is_need_check_order_in_processing' => '0',
        'is_need_check_order_in_processing_append' => '0',
        'is_skip_sending' => '0',
        'fields' => array(
            "roistat_marker" => isset($_COOKIE['roistat_marker']) ? $_COOKIE['roistat_marker'] : "-",
            "form" => $form_name,
            "location" => $location,
            "adress" => $address,
            "shipping_method" => $deliveryName,
            "payment_method" => $paymentName,
            "price" => $price,
            "price_delivery" => $order->getDeliveryPrice(),
            "discount" =>  $discount,
            "products" => $products,
            "comment" => $description,
        ),
    );
    file_get_contents("https://cloud.roistat.com/api/proxy/1.0/leads/add?" . http_build_query($roistatData));
}

// Roistat END



function clean_expire_cache($path = "") {
    if (!class_exists("CFileCacheCleaner")) {
        require_once ($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/classes/general/cache_files_cleaner.php");
    }
    $curentTime = mktime();
    if (defined("BX_CRONTAB") && BX_CRONTAB === true) $endTime = time() + 5; //Если на кроне, то работаем 5 секунд
    else $endTime = time() + 1; //Если на хитах, то не более секунды
    //Работаем со всем кешем
    $obCacheCleaner = new CFileCacheCleaner("all");
    if (!$obCacheCleaner->InitPath($path)) {
        //Произошла ошибка
        return "clean_expire_cache();";
    }
    $obCacheCleaner->Start();
    while ($file = $obCacheCleaner->GetNextFile()) {
        if (is_string($file)) {
            $date_expire = $obCacheCleaner->GetFileExpiration($file);
            if ($date_expire) {
                if ($date_expire < $curentTime) {
                    unlink($file);
                }
            }
            if (time() >= $endTime) break;
        }
    }
    if (is_string($file)) {
        return "clean_expire_cache(\"" . $file . "\");";
    }
    else {
        return "clean_expire_cache();";
    }
}

AddEventHandler('main', 'OnEpilog', 'Show404Error');
function Show404Error()
{
		if (CHTTP::GetLastStatus() == '404 Not Found') {
				global $APPLICATION;
				global $USER;
				$APPLICATION->RestartBuffer();
				require $_SERVER['DOCUMENT_ROOT'] . SITE_TEMPLATE_PATH . "/header.php";
				require $_SERVER['DOCUMENT_ROOT'] . '/404.php';
				require $_SERVER['DOCUMENT_ROOT'] . SITE_TEMPLATE_PATH . "/footer.php";
				exit();
		}
}