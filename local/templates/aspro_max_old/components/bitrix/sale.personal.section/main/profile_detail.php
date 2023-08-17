<?
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
use Bitrix\Main\Localization\Loc;

$APPLICATION->SetTitle(Loc::getMessage("SPS_TITLE_PROFILE"));
// $APPLICATION->AddChainItem(Loc::getMessage("SPS_CHAIN_MAIN"), $arResult['SEF_FOLDER']);
$APPLICATION->AddChainItem(Loc::getMessage("SPS_CHAIN_PROFILE"), $arResult['PATH_TO_PROFILE']);
$APPLICATION->AddChainItem(Loc::getMessage("SPS_CHAIN_PROFILE_INFO", array("#ID#" => $arResult["VARIABLES"]["ID"])));?>

<?
$arUserPropValue = array();
$iPersonType = 0;
$rsUserPropValue = CSaleOrderUserPropsValue::GetList(
	array('ID' => 'ASC'), 
	array('USER_PROPS_ID' => $arResult["VARIABLES"]["ID"], 'IS_PHONE' => 'Y')
);
while($arUserPropValueTmp = $rsUserPropValue->fetch())
{
	$arUserPropValue[$arUserPropValueTmp['ORDER_PROPS_ID']] = $arUserPropValueTmp;
	$iPersonType = $arUserPropValueTmp['PROP_PERSON_TYPE_ID'];
}
if($arUserPropValue)
{
	$arPhoneProp = CSaleOrderProps::GetList(
		array('SORT' => 'ASC'),
		array(
				'PERSON_TYPE_ID' => $iPersonType,
				'IS_PHONE' => 'Y',
			),
		false,
		false,
		array()
	)->fetch(); // get phone prop
	if($arPhoneProp)
	{
		if($arUserPropValue[$arPhoneProp['ID']])
		{
			if($arUserPropValue[$arPhoneProp['ID']]['VALUE'])
			{
				$mask = \Bitrix\Main\Config\Option::get('aspro.max', 'PHONE_MASK', '+7 (999) 999-99-99');
				if(strpos($arUserPropValue[$arPhoneProp['ID']]['VALUE'], '+') === false && strpos($mask, '+') !== false)
				{
					CSaleOrderUserPropsValue::Update($arUserPropValue[$arPhoneProp['ID']]['ID'], array('VALUE'=>'+'.$arUserPropValue[$arPhoneProp['ID']]['VALUE']));
				}
			}
			?>
			<script>
				$(document).ready(function()
				{
					if (typeof appAspro === 'object' && appAspro && appAspro.phone) {
						appAspro.phone.init($('input[name=ORDER_PROP_<?=$arPhoneProp['ID']?>'), {
							coutriesData: '<?=CMax::$arParametrsList['FORMS']['OPTIONS']['USE_INTL_PHONE']['DEPENDENT_PARAMS']['PHONE_CITIES']['TYPE_SELECT']['SRC']?>',
							mask: arAsproOptions['THEME']['PHONE_MASK'],
							onlyCountries: '<?=CMax::GetFrontParametrValue('PHONE_CITIES');?>',
							preferredCountries: '<?=CMax::GetFrontParametrValue('PHONE_CITIES_FAVORITE');?>'
						})
					}
				})
			</script>
			<?$arScripts = ['phone_input']?>
			<?if (CMax::GetFrontParametrValue('USE_INTL_PHONE') === 'Y'):?>
				<?$arScripts[] = 'intl_phone_input'?>
			<?elseif (CMax::GetFrontParametrValue('PHONE_MASK')):?>
				<?$arScripts[] = 'phone_mask'?>
			<?endif;?>

			<?\Aspro\Max\Functions\Extensions::init($arScripts);?>
		<?}
	}
}
?>

<div class="personal_wrapper">
	<?$APPLICATION->IncludeComponent(
		"bitrix:sale.personal.profile.detail",
		"",
		array(
			"PATH_TO_LIST" => $arResult["PATH_TO_PROFILE"],
			"PATH_TO_DETAIL" => $arResult["PATH_TO_PROFILE_DETAIL"],
			"SET_TITLE" =>$arParams["SET_TITLE"],
			"USE_AJAX_LOCATIONS" => $arParams['USE_AJAX_LOCATIONS_PROFILE'],
			"ID" => $arResult["VARIABLES"]["ID"],
		),
		$component
	);
	?>
</div>