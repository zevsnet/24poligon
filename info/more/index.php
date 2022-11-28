<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("Возможности");
?>
<p>В решении поддерживается множество UI элементов, которые Вы с легкостью можете использовать для развития сайта и добавления нового функционала.</p>

<div class="row"> 						
	<div class="col-md-3 col-sm-6 col-xs-12"> 							
		<div class="more_wrapper">
			<a href="<?=SITE_DIR;?>info/more/typograpy/"  data-toggle="tooltip" title="" data-original-title="Можно использовать Tooltip!">
				<?=CMax::showIconSvg("more_icon colored", SITE_TEMPLATE_PATH.'/images/svg/decoration.svg', '', '', true, false);?>
				<div class="title color-theme-hover">
					Оформление
				</div>
			</a>
		</div>
	</div>
	<div class="col-md-3 col-sm-6 col-xs-12"> 
		<div class="more_wrapper">
			<a href="<?=SITE_DIR;?>info/more/buttons/"  data-toggle="tooltip" title="" data-original-title="Можно использовать Tooltip!">
				<?=CMax::showIconSvg("more_icon colored", SITE_TEMPLATE_PATH.'/images/svg/buttons.svg', '', '', true, false);?>
				<div class="title color-theme-hover">
					Кнопки
				</div>
			</a>
		</div>							
	</div>
	<div class="col-md-3 col-sm-6 col-xs-12"> 
		<div class="more_wrapper">
			<a href="<?=SITE_DIR;?>info/more/icons/"  data-toggle="tooltip" title="" data-original-title="Можно использовать Tooltip!">
				<?=CMax::showIconSvg("more_icon colored", SITE_TEMPLATE_PATH.'/images/svg/icons.svg', '', '', true, false);?>
				<div class="title color-theme-hover">
					Иконки
				</div>
			</a>
		</div>								
	</div>
	<div class="col-md-3 col-sm-6 col-xs-12"> 
		<div class="more_wrapper">
			<a href="<?=SITE_DIR;?>info/more/elements/"  data-toggle="tooltip" title="" data-original-title="Можно использовать Tooltip!">
				<?=CMax::showIconSvg("more_icon colored", SITE_TEMPLATE_PATH.'/images/svg/elements.svg', '', '', true, false);?>
				<div class="title color-theme-hover">
					Элементы
				</div>
			</a>
		</div>								
	</div>
</div>
<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>