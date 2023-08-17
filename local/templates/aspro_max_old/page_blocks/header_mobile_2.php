<div class="mobileheader-v2">
	<div class="burger pull-left">
		<?=CMax::showIconSvg("burger dark", SITE_TEMPLATE_PATH."/images/svg/burger.svg");?>
		<?=CMax::showIconSvg("close dark", SITE_TEMPLATE_PATH."/images/svg/Close.svg");?>
	</div>
	<div class="title-block col-sm-5 col-xs-5 pull-left"><?($APPLICATION->GetTitle() ? $APPLICATION->ShowTitle(false) : $APPLICATION->ShowTitle());?></div>
	<div class="right-icons pull-right">
		<div class="pull-right">
			<div class="wrap_icon wrap_basket">
				<?=CMax::ShowBasketWithCompareLink('', 'big white', false, false, true);?>
			</div>
		</div>
		<div class="pull-right">
			<div class="wrap_icon wrap_cabinet">
				<?=CMax::showCabinetLink(true, false, 'big white');?>
			</div>
		</div>
		<div class="pull-right">
			<div class="wrap_icon">
				<button class="top-btn inline-search-show twosmallfont">
					<?=CMax::showIconSvg("search", SITE_TEMPLATE_PATH."/images/svg/Search.svg");?>
				</button>
			</div>
		</div>
		<div class="pull-right">
			<div class="wrap_icon wrap_phones">
				<?CMax::ShowHeaderMobilePhones("big");?>
			</div>
		</div>
	</div>
</div>