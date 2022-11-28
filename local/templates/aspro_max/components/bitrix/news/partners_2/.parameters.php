<?
if(!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

use Bitrix\Main\Web\Json; 

/* get component template pages & params array */
$arPageBlocksParams = array();
if(\Bitrix\Main\Loader::includeModule('aspro.max')){
	$arPageBlocks = CMax::GetComponentTemplatePageBlocks(__DIR__);
	$arPageBlocksParams = CMax::GetComponentTemplatePageBlocksParams($arPageBlocks);
	CMax::AddComponentTemplateModulePageBlocksParams(__DIR__, $arPageBlocksParams); // add option value FROM_MODULE
}

CBitrixComponent::includeComponentClass('bitrix:catalog.section'); 

$arTemplateParameters = array_merge($arPageBlocksParams, array(
	'SHOW_SECTION_PREVIEW_DESCRIPTION' => array(
		'PARENT' => 'LIST_SETTINGS',
		'SORT' => 500,
		'NAME' => GetMessage('SHOW_SECTION_PREVIEW_DESCRIPTION'),
		'TYPE' => 'CHECKBOX',
		'DEFAULT' => 'Y',
	),
	'USE_SHARE' => array(
		'PARENT' => 'DETAIL_SETTINGS',
		'SORT' => 600,
		'NAME' => GetMessage('USE_SHARE'),
		'TYPE' => 'CHECKBOX',
		'DEFAULT' => 'N',
	),
	'USE_SUBSCRIBE_IN_TOP' => array(
		'PARENT' => 'DETAIL_SETTINGS',
		'SORT' => 600,
		'NAME' => GetMessage('USE_SUBSCRIBE_IN_TOP'),
		'TYPE' => 'CHECKBOX',
		'DEFAULT' => 'N',
	),
	'T_DOCS' => array(
		'SORT' => 703,
		'NAME' => GetMessage('T_DOCS'),
		'TYPE' => 'TEXT',
		'DEFAULT' => '',
	),
	'T_VIDEO' => array(
		'SORT' => 704,
		'NAME' => GetMessage('T_VIDEO'),
		'TYPE' => 'TEXT',
		'DEFAULT' => '',
	),
	"SIDE_LEFT_BLOCK" => Array(
		"PARENT" => "BASE",
		"NAME" => GetMessage("SIDE_LEFT_BLOCK_NAME"),
		"TYPE" => "LIST",
		"VALUES" => array("LEFT" => GetMessage("T_LEFT"), "RIGHT" => GetMessage("T_RIGHT"), "FROM_MODULE" => GetMessage("FROM_MODULE_PARAMS")),
		"DEFAULT" => "FROM_MODULE",
	),
	"TYPE_LEFT_BLOCK" => Array(
		"PARENT" => "BASE",
		"NAME" => GetMessage("TYPE_LEFT_BLOCK_NAME"),
		"TYPE" => "LIST",
		"VALUES" => array("FROM_MODULE" => GetMessage("FROM_MODULE_PARAMS"),"1" => GetMessage("T_FULL"), "2" => GetMessage("T_TYPE_LEFT_BLOCK_2"), "3" => GetMessage("T_TYPE_LEFT_BLOCK_3"), "4" => GetMessage("T_TYPE_LEFT_BLOCK_4")),
		"DEFAULT" => "FROM_MODULE",
	),
	"SIDE_LEFT_BLOCK_DETAIL" => Array(
		"PARENT" => "BASE",
		"NAME" => GetMessage("SIDE_LEFT_BLOCK_DETAIL_NAME"),
		"TYPE" => "LIST",
		"VALUES" => array("LEFT" => GetMessage("T_LEFT"), "RIGHT" => GetMessage("T_RIGHT"), "FROM_MODULE" => GetMessage("FROM_MODULE_PARAMS")),
		"DEFAULT" => "FROM_MODULE",
	),
	"TYPE_LEFT_BLOCK_DETAIL" => Array(
		"PARENT" => "BASE",
		"NAME" => GetMessage("TYPE_LEFT_BLOCK_DETAIL_NAME"),
		"TYPE" => "LIST",
		"VALUES" => array("FROM_MODULE" => GetMessage("FROM_MODULE_PARAMS"),"1" => GetMessage("T_FULL"), "2" => GetMessage("T_TYPE_LEFT_BLOCK_2"), "3" => GetMessage("T_TYPE_LEFT_BLOCK_3"), "4" => GetMessage("T_TYPE_LEFT_BLOCK_4")),
		"DEFAULT" => "FROM_MODULE",
	),
	"GALLERY_TYPE" => Array(
		"PARENT" => "DETAIL_SETTINGS",
		"NAME" => GetMessage("GALLERY_TYPE"),
		"TYPE" => "LIST",
		"VALUES" => array("small" => GetMessage("GALLERY_SMALL"),"big" => GetMessage("GALLERY_BIG")),
		"DEFAULT" => "small",
	),
	"STAFF_TYPE_DETAIL" => Array(
		"PARENT" => "DETAIL_SETTINGS",
		"NAME" => GetMessage("STAFF_TYPE_DETAIL_NAME"),
		"TYPE" => "LIST",
		"VALUES" => array( 'list' => GetMessage('T_LIST'), 'block' => GetMessage('T_BLOCK')),
		"DEFAULT" => 'list',
	),
	"IBLOCK_LINK_NEWS_ID" => Array( 
            "NAME" => GetMessage("IBLOCK_LINK_NEWS_NAME"), 
            "TYPE" => "STRING", 
            "DEFAULT" => "", 
        ), 
        "IBLOCK_LINK_SERVICES_ID" => Array( 
            "NAME" => GetMessage("IBLOCK_LINK_SERVICES_NAME"), 
            "TYPE" => "STRING", 
            "DEFAULT" => "", 
        ), 
        "IBLOCK_LINK_TIZERS_ID" => Array( 
            "NAME" => GetMessage("IBLOCK_LINK_TIZERS_NAME"), 
            "TYPE" => "STRING", 
            "DEFAULT" => "", 
        ),  
        "IBLOCK_LINK_REVIEWS_ID" => Array( 
            "NAME" => GetMessage("IBLOCK_LINK_REVIEWS_NAME"), 
            "TYPE" => "STRING", 
            "DEFAULT" => "", 
        ),
	"IBLOCK_LINK_STAFF_ID" => Array( 
            "NAME" => GetMessage("IBLOCK_LINK_STAFF_NAME"), 
            "TYPE" => "STRING", 
            "DEFAULT" => "", 
        ),
	"IBLOCK_LINK_VACANCY_ID" => Array( 
            "NAME" => GetMessage("IBLOCK_LINK_VACANCY_NAME"), 
            "TYPE" => "STRING", 
            "DEFAULT" => "", 
        ),
	"IBLOCK_LINK_BLOG_ID" => Array( 
            "NAME" => GetMessage("IBLOCK_LINK_BLOG_NAME"), 
            "TYPE" => "STRING", 
            "DEFAULT" => "", 
        ),
	"IBLOCK_LINK_PROJECTS_ID" => Array( 
            "NAME" => GetMessage("IBLOCK_LINK_PROJECTS_NAME"), 
            "TYPE" => "STRING", 
            "DEFAULT" => "", 
        ),
	"IBLOCK_LINK_BRANDS_ID" => Array( 
            "NAME" => GetMessage("IBLOCK_LINK_BRANDS_NAME"), 
            "TYPE" => "STRING", 
            "DEFAULT" => "", 
        ),
	"IBLOCK_LINK_LANDINGS_ID" => Array( 
            "NAME" => GetMessage("IBLOCK_LINK_LANDINGS_NAME"), 
            "TYPE" => "STRING", 
            "DEFAULT" => "", 
        ),
	"BLOCK_SERVICES_NAME" => Array( 
            "NAME" => GetMessage("BLOCK_SERVICES_NAME_TITLE"), 
            "TYPE" => "STRING", 
            "DEFAULT" => "",
        ),
	"BLOCK_NEWS_NAME" => Array( 
            "NAME" => GetMessage("BLOCK_NEWS_NAME_TITLE"), 
            "TYPE" => "STRING", 
            "DEFAULT" => "",
        ),
	"BLOCK_TIZERS_NAME" => Array( 
            "NAME" => GetMessage("BLOCK_TIZERS_NAME_TITLE"), 
            "TYPE" => "STRING", 
            "DEFAULT" => "",
        ),
	"BLOCK_REVIEWS_NAME" => Array( 
            "NAME" => GetMessage("BLOCK_REVIEWS_NAME_TITLE"), 
            "TYPE" => "STRING", 
            "DEFAULT" => "",
        ),
	"BLOCK_STAFF_NAME" => Array( 
            "NAME" => GetMessage("BLOCK_STAFF_NAME_TITLE"), 
            "TYPE" => "STRING", 
            "DEFAULT" => "",
        ),
	"BLOCK_VACANCY_NAME" => Array( 
            "NAME" => GetMessage("BLOCK_VACANCY_NAME_TITLE"), 
            "TYPE" => "STRING", 
            "DEFAULT" => "",
        ),
	"BLOCK_PROJECTS_NAME" => Array( 
            "NAME" => GetMessage("BLOCK_PROJECTS_NAME_TITLE"), 
            "TYPE" => "STRING", 
            "DEFAULT" => "",
        ),
	"BLOCK_BRANDS_NAME" => Array( 
            "NAME" => GetMessage("BLOCK_BRANDS_NAME_TITLE"), 
            "TYPE" => "STRING", 
            "DEFAULT" => "",
        ),
	"BLOCK_BLOG_NAME" => Array( 
            "NAME" => GetMessage("BLOCK_BLOG_NAME_TITLE"), 
            "TYPE" => "STRING", 
            "DEFAULT" => "",
        ),
	"BLOCK_LANDINGS_NAME" => Array( 
            "NAME" => GetMessage("BLOCK_LANDINGS_NAME_TITLE"), 
            "TYPE" => "STRING", 
            "DEFAULT" => "",
        ),
	
	"IBLOCK_LINK_PARTNERS_ID" => Array( 
            "NAME" => GetMessage("IBLOCK_LINK_PARTNERS_NAME"), 
            "TYPE" => "STRING", 
            "DEFAULT" => "", 
        ),
	"BLOCK_PARTNERS_NAME" => Array( 
            "NAME" => GetMessage("BLOCK_PARTNERS_NAME_TITLE"), 
            "TYPE" => "STRING", 
            "DEFAULT" => "",
        ),
));

$arTemplateParameters['IMAGE_POSITION'] = array(
	'PARENT' => 'LIST_SETTINGS',
	'SORT' => 250,
	'NAME' => GetMessage('IMAGE_POSITION'),
	'TYPE' => 'LIST',
	'VALUES' => array(
		'left' => GetMessage('IMAGE_POSITION_LEFT'),
		'right' => GetMessage('IMAGE_POSITION_RIGHT'),
	),
	'DEFAULT' => 'left',
);

$arTemplateParameters['COUNT_IN_LINE'] = array(
	'PARENT' => 'LIST_SETTINGS',
	'NAME' => GetMessage('COUNT_IN_LINE'),
	'TYPE' => 'STRING',
	'DEFAULT' => '3',
);

$arTemplateParameters['DETAIL_BLOCKS_ALL_ORDER'] = array(  
	'PARENT' => 'DETAIL_SETTINGS', 
	'NAME' => GetMessage('CP_BC_TPL_CONTENT_BLOCKS_ALL_ORDER'), 
	'TYPE' => 'CUSTOM', 
	'JS_FILE' => CatalogSectionComponent::getSettingsScript('/bitrix/components/bitrix/catalog.section', 'dragdrop_order'), 
	'JS_EVENT' => 'initDraggableOrderControl', 
	'JS_DATA' => Json::encode(array( 
		'docs' => GetMessage('CP_BC_TPL_CONTENT_BLOCK_DOCS'), 
		'brands' => GetMessage('CP_BC_TPL_CONTENT_BLOCK_BRANDS'), 
		'gallery' => GetMessage('CP_BC_TPL_CONTENT_BLOCK_GALLERY'), 
		'desc' => GetMessage('CP_BC_TPL_CONTENT_BLOCK_DESC'), 
		'char' => GetMessage('CP_BC_TPL_CONTENT_BLOCK_CHAR'), 
		'tizers' => GetMessage('CP_BC_TPL_CONTENT_BLOCK_TIZERS'),  
		'video' => GetMessage('CP_BC_TPL_CONTENT_BLOCK_VIDEO'),  
		'services' => GetMessage('CP_BC_TPL_CONTENT_BLOCK_SERVICES'), 
		'news' => GetMessage('CP_BC_TPL_CONTENT_BLOCK_NEWS'), 
		'blog' => GetMessage('CP_BC_TPL_CONTENT_BLOCK_BLOG'), 
		'vacancy' => GetMessage('CP_BC_TPL_CONTENT_BLOCK_VACANCY'), 
		'reviews' => GetMessage('CP_BC_TPL_CONTENT_BLOCK_REVIEWS'),
		'comments' => GetMessage('CP_BC_TPL_CONTENT_BLOCK_COMMENTS'), 
		'goods' => GetMessage('CP_BC_TPL_CONTENT_BLOCK_GOODS'), 
		'projects' => GetMessage('CP_BC_TPL_CONTENT_BLOCK_PROJECTS'), 
		'landings' => GetMessage('CP_BC_TPL_CONTENT_BLOCK_LANDINGS'),
		'form_order' => GetMessage('CP_BC_TPL_CONTENT_BLOCK_FORM_ORDER'),
		'partners' => GetMessage('CP_BC_TPL_CONTENT_BLOCK_PARTNERS'),
		'sale' => GetMessage('CP_BC_TPL_CONTENT_BLOCK_SALE'), 
		'staff' => GetMessage('CP_BC_TPL_CONTENT_BLOCK_STAFF'), 
	)), 
	'DEFAULT' => 'tizers,desc,char,docs,services,news,vacancy,blog,reviews,projects,staff,comments,brands,gallery,video,goods,landings,form_order,partners,sale'  
);

$arTemplateParameters['DETAIL_USE_COMMENTS'] = array(
	'PARENT' => 'DETAIL_SETTINGS',
	'NAME' => GetMessage('CP_BC_TPL_DETAIL_USE_COMMENTS'),
	'TYPE' => 'CHECKBOX',
	'DEFAULT' => 'Y',
	'REFRESH' => 'Y'
);

if ('N' != $arCurrentValues['DETAIL_USE_COMMENTS'])
{
	if (\Bitrix\Main\ModuleManager::isModuleInstalled("blog"))
	{
		$arTemplateParameters['DETAIL_BLOG_USE'] = array(
			'PARENT' => 'DETAIL_SETTINGS',
			'NAME' => GetMessage('CP_BC_TPL_DETAIL_BLOG_USE'),
			'TYPE' => 'CHECKBOX',
			'DEFAULT' => 'N',
			'REFRESH' => 'Y'
		);
		if (isset($arCurrentValues['DETAIL_BLOG_USE']) && $arCurrentValues['DETAIL_BLOG_USE'] == 'Y')
		{
			$arTemplateParameters['DETAIL_BLOG_URL'] = array(
				'PARENT' => 'DETAIL_SETTINGS',
				'NAME' => GetMessage('CP_BC_DETAIL_TPL_BLOG_URL'),
				'TYPE' => 'STRING',
				'DEFAULT' => 'catalog_comments'
			);
			$arTemplateParameters['COMMENTS_COUNT'] = array(
				'PARENT' => 'DETAIL_SETTINGS',
				'NAME' => GetMessage('T_COMMENTS_COUNT'),
				'TYPE' => 'STRING',
				'DEFAULT' => '5'
			);
			$arTemplateParameters['BLOG_TITLE'] = array(
				'PARENT' => 'DETAIL_SETTINGS',
				'NAME' => GetMessage('BLOCK_TITLE_TAB'),
				'TYPE' => 'STRING',
				'DEFAULT' => GetMessage('S_COMMENTS_VALUE')
			);
			$arTemplateParameters['DETAIL_BLOG_EMAIL_NOTIFY'] = array(
				'PARENT' => 'DETAIL_SETTINGS',
				'NAME' => GetMessage('CP_BC_TPL_DETAIL_BLOG_EMAIL_NOTIFY'),
				'TYPE' => 'CHECKBOX',
				'DEFAULT' => 'N'
			);
		}
	}

	$boolRus = false;
	$langBy = "id";
	$langOrder = "asc";
	$rsLangs = CLanguage::GetList($langBy, $langOrder, array('ID' => 'ru',"ACTIVE" => "Y"));
	if ($arLang = $rsLangs->Fetch())
	{
		$boolRus = true;
	}

	if ($boolRus)
	{
		$arTemplateParameters['DETAIL_VK_USE'] = array(
			'PARENT' => 'DETAIL_SETTINGS',
			'NAME' => GetMessage('CP_BC_TPL_DETAIL_VK_USE'),
			'TYPE' => 'CHECKBOX',
			'DEFAULT' => 'N',
			'REFRESH' => 'Y'
		);

		if (isset($arCurrentValues['DETAIL_VK_USE']) && 'Y' == $arCurrentValues['DETAIL_VK_USE'])
		{
			$arTemplateParameters['VK_TITLE'] = array(
				'PARENT' => 'DETAIL_SETTINGS',
				'NAME' => GetMessage('BLOCK_TITLE_TAB'),
				'TYPE' => 'STRING',
				'DEFAULT' => GetMessage('S_VK_VALUE')
			);
			$arTemplateParameters['DETAIL_VK_API_ID'] = array(
				'PARENT' => 'DETAIL_SETTINGS',
				'NAME' => GetMessage('CP_BC_TPL_DETAIL_VK_API_ID'),
				'TYPE' => 'STRING',
				'DEFAULT' => 'API_ID'
			);
		}
	}

	$arTemplateParameters['DETAIL_FB_USE'] = array(
		'PARENT' => 'DETAIL_SETTINGS',
		'NAME' => GetMessage('CP_BC_TPL_DETAIL_FB_USE'),
		'TYPE' => 'CHECKBOX',
		'DEFAULT' => 'N',
		'REFRESH' => 'Y'
	);

	if (isset($arCurrentValues['DETAIL_FB_USE']) && 'Y' == $arCurrentValues['DETAIL_FB_USE'])
	{
		$arTemplateParameters['FB_TITLE'] = array(
			'PARENT' => 'DETAIL_SETTINGS',
			'NAME' => GetMessage('BLOCK_TITLE_TAB'),
			'TYPE' => 'STRING',
			'DEFAULT' => GetMessage('S_FB_VALUE')
		);
		$arTemplateParameters['DETAIL_FB_APP_ID'] = array(
			'PARENT' => 'DETAIL_SETTINGS',
			'NAME' => GetMessage('CP_BC_TPL_DETAIL_FB_APP_ID'),
			'TYPE' => 'STRING',
			'DEFAULT' => ''
		);
	}
}
?>
