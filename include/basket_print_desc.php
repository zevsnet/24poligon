<div class="store_property">
	<div class="title">Адрес</div>
	<div class="value"><?$APPLICATION->IncludeFile(SITE_DIR."include/top_page/site-address.php", Array(), Array("MODE" => "html", "NAME" => "address"));?></div>
</div>
<div class="store_property">
	<div class="title">Телефон</div>
	<div class="value"><?$APPLICATION->IncludeFile(SITE_DIR."include/phone.php", Array(), Array("MODE" => "html", "NAME" => "phone"));?></div>
</div>
<div class="store_property">
	<div class="title">Email</div>
	<div class="value"><?$APPLICATION->IncludeFile(SITE_DIR."include/footer/site-email.php", Array(), Array("MODE" => "html", "NAME" => "email"));?></div>
</div>
<div class="store_property">
	<div class="title">Режим работы</div>
	<div class="value"><?$APPLICATION->IncludeFile(SITE_DIR."include/schedule.php", Array(), Array("MODE" => "html", "NAME" => "schedule"));?></div>
</div>