<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");?>
<?
global $USER;
$bSuccessConfigSave = false;

if($USER->IsAdmin() && (isset($_POST['SAVE_OPTIONS']) && $_POST['SAVE_OPTIONS'] == 'Y'))
{
	if(isset($_SESSION['THEME']) && $_SESSION['THEME'])
	{
		if($_SESSION['THEME'][SITE_ID])
		{
			\Bitrix\Main\Loader::includeModule('aspro.max');

			// get options
			foreach(CMax::$arParametrsList as $blockCode => $arBlock)
			{
				if($arBlock['OPTIONS'] && is_array($arBlock['OPTIONS']))
				{
					foreach($arBlock['OPTIONS'] as $optionCode => $arOption)
					{
						if($arOption['TYPE'] !== 'note' && $arOption['TYPE'] !== 'includefile' && $arOption['TYPE'] !== 'file')
							$arBackParametrs[$optionCode] = $arOption;
					}
				}
			}
			$bSuccessConfigSave = true;
			$arDependentParams = array();
			
			foreach($_SESSION['THEME'][SITE_ID] as $optionCode => $optionValue)
			{
				if($arBackParametrs[$optionCode]) //save exists option
				{
					\Bitrix\Main\Config\Option::set('aspro.max', $optionCode, $optionValue, SITE_ID);
				}
				else //get dependent option
				{
					if(strpos($optionCode, 'index') !== false)
					{
						if(strpos($optionCode, 'SORT_ORDER_') !== false)
						{
							$arTmpOption = explode('_', $optionCode);
							$index_code = array_pop($arTmpOption);
							$index_subvalue = implode('_', $arTmpOption);
						}
						else
						{
							$arTmpOption = explode('_', $optionCode, 2);
							$index_code = reset($arTmpOption);
							$index_subvalue = end($arTmpOption);
						}

						$arDependentParams[$index_code][$index_subvalue] = $optionValue;
					}
					else
						$arDependentParams[$optionCode] = $optionValue;
				}
			}
			if($arDependentParams) // save dependent options
			{
				foreach($arBackParametrs as $optionCode => $arOption)
				{
					if(isset($arOption['DEPENDENT_PARAMS']) && $arOption['DEPENDENT_PARAMS'])
					{
						foreach($arOption['DEPENDENT_PARAMS'] as $dependOptionCode => $arValue)
						{
							if($arDependentParams[$dependOptionCode])
							{
								if($arValue['LIST'][$arDependentParams[$dependOptionCode]])
									\Bitrix\Main\Config\Option::set('aspro.max', $dependOptionCode, $arDependentParams[$dependOptionCode], SITE_ID);
							}
						}
					}
					elseif(isset($arOption['ADDITIONAL_OPTIONS']) && $arOption['ADDITIONAL_OPTIONS'])
					{
						foreach($arOption['LIST'] as $keyh => $arValueH)
						{
							if($arValueH['ADDITIONAL_OPTIONS'])
							{
								foreach($arValueH['ADDITIONAL_OPTIONS'] as $keyh2 => $arValueH2)
								{
									if($arDependentParams[$keyh2."_".$keyh])
									{
										\Bitrix\Main\Config\Option::set('aspro.max', $keyh2."_".$keyh, $arDependentParams[$keyh2."_".$keyh], SITE_ID);
									}
								}
							}
						}
					}
					elseif(isset($arOption['SUB_PARAMS']) && $arOption['SUB_PARAMS'])
					{
						$arOptionKeys = array_keys($arDependentParams);
						foreach($arDependentParams as $key => $arSubParams)
						{
							$arTemplateIndex = array();
							if(is_array($arSubParams))
							{
								foreach($arSubParams as $key2 => $value)
								{
									if(strpos($key2, 'TEMPLATE') !== false)
									{
										$arTemplateIndex[$key2] = $value;
										unset($arSubParams[$key2]);

										$strTmpCode = str_replace('TEMPLATE', '', $key2);
										$arTmpDopConditions = array();
										foreach($arSubParams as $key3 => $value)
										{
											if(strpos($key3, $strTmpCode) !== false)
											{
												if($arSubParams[$key3])
												{
													$arTmpDopConditions[$key3] = $value;
													unset($arSubParams[$key3]);
												}
											}
										}
										if($arTmpDopConditions)
										{
											\Bitrix\Main\Config\Option::set('aspro.max', "N_O_".$optionCode."_".$key."_".$strTmpCode, serialize($arTmpDopConditions), SITE_ID);
										}
									}
									elseif(strpos($key2, 'SORT_ORDER_') !== false)
										unset($arSubParams[$key2]);
									elseif(strpos($key2, 'fon') !== false)
									{
										\Bitrix\Main\Config\Option::set('aspro.max', $key2, $value, SITE_ID);
									}
								}
							}

							if($arOption['LIST'][$key] && $arOption['SUB_PARAMS'][$key])
							{
								\Bitrix\Main\Config\Option::set('aspro.max', "NESTED_OPTIONS_".$optionCode."_".$key, serialize($arSubParams), SITE_ID);
							}

							//save teplate index
							if($arTemplateIndex)
							{
								foreach($arTemplateIndex as $key2 => $value)
								{
									\Bitrix\Main\Config\Option::set('aspro.max', $key."_".$key2, $value, SITE_ID);
								}
							}
							//sort order prop for main page
							$param = 'SORT_ORDER_'.$optionCode.'_'.$key;
							\Bitrix\Main\Config\Option::set('aspro.max', $param, $_SESSION['THEME'][SITE_ID][$param], SITE_ID);
						}
					}
				}
			}
		}
	}
}

if($bSuccessConfigSave)
{
	$addResult = array('STATUS' => 'OK', 'MESSAGE' => 'CONFIG_SAVE_SUCCESS');
	unset($_SESSION['THEME'][SITE_ID]);
}
else
	$addResult = array('STATUS' => 'ERROR', 'MESSAGE' => 'CONFIG_SAVE_FAIL');	

echo json_encode($addResult);
die();
?>