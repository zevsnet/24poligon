<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
/** @var array $arParams */
/** @var array $arResult */
/** @global CMain $APPLICATION */
/** @global CUser $USER */
/** @global CDatabase $DB */
/** @var CBitrixComponentTemplate $this */
/** @var string $templateName */
/** @var string $templateFile */
/** @var string $templateFolder */
/** @var string $componentPath */
/** @var CBitrixComponent $component */
$this->setFrameMode(true);
		
$templateData = array(
	'TABS_ID' => 'soc_comments_'.$arResult['ELEMENT']['ID'],
	'TABS_FRAME_ID' => 'soc_comments_div_'.$arResult['ELEMENT']['ID'],
	'BLOG_USE' => ($arResult['BLOG_USE'] ? 'Y' : 'N'),
	'FB_USE' => $arParams['FB_USE'],
	'VK_USE' => $arParams['VK_USE'],
	'BLOG' => array(
		'BLOG_FROM_AJAX' => $arResult['BLOG_FROM_AJAX'],
	),
	'TEMPLATE_THEME' => $this->GetFolder().'/themes/'.$arParams['TEMPLATE_THEME'].'/style.css',
	'TEMPLATE_CLASS' => 'bx_'.$arParams['TEMPLATE_THEME']
);

if (!$templateData['BLOG']['BLOG_FROM_AJAX'])
{
	if (!empty($arResult['ERRORS']))
	{
		ShowError(implode('<br>', $arResult['ERRORS']));
		return;
	}

	$arData = array();
	$arJSParams = array(
		'serviceList' => array(

		),
		'settings' => array(

		),
		'tabs' => array(

		)
	);

	if ($arResult['BLOG_USE'])
	{
		$templateData['BLOG']['AJAX_PARAMS'] = array(
			'IBLOCK_ID' => $arResult['ELEMENT']['IBLOCK_ID'],
			'ELEMENT_ID' => $arResult['ELEMENT']['ID'],
			'URL_TO_COMMENT' => $arParams['~URL_TO_COMMENT'],
			'WIDTH' => $arParams['WIDTH'],
			'COMMENTS_COUNT' => $arParams['COMMENTS_COUNT'],
			'BLOG_USE' => 'Y',
			'BLOG_FROM_AJAX' => 'Y',
			'FB_USE' => 'N',
			'VK_USE' => 'N',
			'BLOG_TITLE' => $arParams['~BLOG_TITLE'],
			'BLOG_URL' => $arParams['~BLOG_URL'],
			'PATH_TO_SMILE' => $arParams['~PATH_TO_SMILE'],
			'EMAIL_NOTIFY' => $arParams['EMAIL_NOTIFY'],
			'AJAX_POST' => $arParams['AJAX_POST'],
			'SHOW_SPAM' => $arParams['SHOW_SPAM'],
			'SHOW_RATING' => $arParams['SHOW_RATING'],
			'RATING_TYPE' => $arParams['~RATING_TYPE'],
			'CACHE_TYPE' => 'N',
			'CACHE_TIME' => '0',
			'CACHE_GROUPS' => $arParams['CACHE_GROUPS'],
			'TEMPLATE_THEME' => $arParams['~TEMPLATE_THEME'],
			'SHOW_DEACTIVATED' => $arParams['SHOW_DEACTIVATED'],
			'COMMENT_PROPERTY' => array(
				'UF_BLOG_COMMENT_DOC'
			),
		);

		$arJSParams['serviceList']['blog'] = true;
		$arJSParams['settings']['blog'] = array(
			'ajaxUrl' => $templateFolder.'/ajax.php?IBLOCK_ID='.$arResult['ELEMENT']['IBLOCK_ID'].'&ELEMENT_ID='.$arResult['ELEMENT']['ID'].'&SITE_ID='.SITE_ID,
			'ajaxParams' => array(),
			'contID' => 'bx-cat-soc-comments-blg_'.$arResult['ELEMENT']['ID']
		);

		$arData["BLOG"] =  array(
			"NAME" => ($arParams['BLOG_TITLE'] != '' ? $arParams['BLOG_TITLE'] : GetMessage('IBLOCK_CSC_TAB_COMMENTS')),
			"ACTIVE" => "Y",
			"CONTENT" => '<div id="bx-cat-soc-comments-blg_'.$arResult['ELEMENT']['ID'].'">'.GetMessage("IBLOCK_CSC_COMMENTS_LOADING").'</div>'
		);
	}

	if (!empty($arData))
	{
		$arTabsParams = array(
			"DATA" => $arData,
			"ID" => $templateData['TABS_ID']
		);

?><div id="<? echo $templateData['TABS_FRAME_ID']; ?>" class="bx_soc_comments_div bx_important <? echo $templateData['TEMPLATE_CLASS']; ?>"><?
		$content = "";
		$activeTabId = "";
		$tabIDList = array();
?><div id="<? echo $templateData['TABS_ID']; ?>" class="bx-catalog-tab-section-container tabs">
	<div class="">
		<ul class="bx-catalog-tab-list1 hidden nav nav-tabs" style="left: 0;"><?
			// $arData['BLOG']['CONTENT']
			$id = $templateData['TABS_ID'].'BLOG';
			$tabActive = true;
			?><li id="<?=$id?>" class="muted bordered font_upper_md BLOG"><a href="#<?=$id;?>_cont" data-toggle="tab"></a></li><?
			$activeTabId = 'BLOG';

			$content .= '<div id="'.$id.'_cont" >'.$arData['BLOG']['CONTENT'].'</div>';
			$tabIDList[] = 'BLOG';

		?></ul>
	</div>
	<div class="bx-catalog-tab-body-container catalog_reviews_extended">
		<div class="bx-catalog-tab-container"><?=$content?></div>
	</div>
</div>
<?
		$arJSParams['tabs'] = array(
			'activeTabId' =>  'BLOG',
			'tabsContId' => $templateData['TABS_ID'],
			'tabList' => $tabIDList
		);
?></div>
<script type="text/javascript">
var obCatalogComments_<? echo $arResult['ELEMENT']['ID']; ?> = new JCCatalogSocnetsComments(<? echo CUtil::PhpToJSObject($arJSParams, false, true); ?>);
$(document).on('click', '.show-comment.btn', function(){

	if($(this).hasClass('clicked') && $('#form_comment_0 #form_c_del').length) {
		$('#form_comment_0').slideToggle();
	} else {
		showComment('0');
		$('#form_comment_0').show();
	}
	fileInputInit("<?=GetMessage('INPUT_FILE_DEFAULT')?>");
	$(this).addClass('clicked');

})
</script><?
	}
	else
	{
		ShowError(GetMessage("IBLOCK_CSC_NO_DATA"));
	}
}