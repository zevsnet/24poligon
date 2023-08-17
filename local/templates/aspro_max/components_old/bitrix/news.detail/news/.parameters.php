<?
if(!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

$arTemplateParameters = array(
	'DISPLAY_DATE' => Array(
		'NAME' => GetMessage('T_IBLOCK_DESC_NEWS_DATE'),
		'TYPE' => 'CHECKBOX',
		'DEFAULT' => 'Y',
	),
	'DISPLAY_PICTURE' => Array(
		'NAME' => GetMessage('T_IBLOCK_DESC_NEWS_PICTURE'),
		'TYPE' => 'CHECKBOX',
		'DEFAULT' => 'Y',
	),
	'DISPLAY_PREVIEW_TEXT' => Array(
		'NAME' => GetMessage('T_IBLOCK_DESC_NEWS_TEXT'),
		'TYPE' => 'CHECKBOX',
		'DEFAULT' => 'Y',
	),
	'USE_SHARE' => array(
		'NAME' => GetMessage('USE_SHARE'),
		'TYPE' => 'CHECKBOX',
		'DEFAULT' => 'N',
	),
	'S_ASK_QUESTION' => array(
		'SORT' => 700,
		'NAME' => GetMessage('S_ASK_QUESTION'),
		'TYPE' => 'TEXT',
		'DEFAULT' => '',
	),
	'S_ORDER_SERVISE' => array(
		'SORT' => 701,
		'NAME' => GetMessage('S_ORDER_SERVISE'),
		'TYPE' => 'TEXT',
		'DEFAULT' => '',
	),
	'T_GALLERY' => array(
		'SORT' => 702,
		'NAME' => GetMessage('T_GALLERY'),
		'TYPE' => 'TEXT',
		'DEFAULT' => '',
	),
	'T_DOCS' => array(
		'SORT' => 703,
		'NAME' => GetMessage('T_DOCS'),
		'TYPE' => 'TEXT',
		'DEFAULT' => '',
	),
	'T_GOODS' => array(
		'SORT' => 704,
		'NAME' => GetMessage('T_GOODS'),
		'TYPE' => 'TEXT',
		'DEFAULT' => '',
	),
	'T_STUDY' => array(
		'SORT' => 705,
		'NAME' => GetMessage('T_STUDY'),
		'TYPE' => 'TEXT',
		'DEFAULT' => '',
	),
	'T_SERVICES' => array(
		'SORT' => 706,
		'NAME' => GetMessage('T_SERVICES'),
		'TYPE' => 'TEXT',
		'DEFAULT' => '',
	),
	'T_VIDEO' => array(
		'SORT' => 707,
		'NAME' => GetMessage('T_VIDEO'),
		'TYPE' => 'TEXT',
		'DEFAULT' => '',
	),
	"STAFF_TYPE" => Array(
		//"PARENT" => "LIST_SETTINGS",
		"NAME" => GetMessage("STAFF_TYPE_NAME"),
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
	"IBLOCK_LINK_VACANCIES_ID" => Array( 
            "NAME" => GetMessage("IBLOCK_LINK_VACANCIES_NAME"), 
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
	"BLOCK_VACANCIES_NAME" => Array( 
            "NAME" => GetMessage("BLOCK_VACANCIES_NAME_TITLE"), 
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
	'SALE_MODE' => array(
		'NAME' => GetMessage('SALE_MODE_NAME'),
		'TYPE' => 'CHECKBOX',
		'DEFAULT' => 'N',
	),
	'PARTNERS_MODE' => array(
		'NAME' => GetMessage('PARTNERS_MODE_NAME'),
		'TYPE' => 'CHECKBOX',
		'DEFAULT' => 'N',
	),
	
	
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