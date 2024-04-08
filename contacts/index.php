<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetPageProperty("description", "Магазины военных товаров и амуниции. Адреса и информация о работе Военторга.");
$APPLICATION->SetPageProperty("title", "Розничные магазины Полигон - адреса ");
$APPLICATION->SetTitle("Адреса магазинов спецназначения Полигон");?>

<?include_once $_SERVER["DOCUMENT_ROOT"]."/contacts/cmaxcustom.php"; ?>
<?CMax::ShowPageType('page_contacts');?>

<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>