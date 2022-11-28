<?define("STATISTIC_SKIP_ACTIVITY_CHECK", "true");?>
<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");?>

<?
if(\Bitrix\Main\Loader::includeModule('subscribe')){
	$arData = $arRubricsID = array();
	parse_str($_REQUEST['data'], $arData);

	if($arData){
		foreach($arData as $key => $value){
			$_REQUEST[$key] = $value;
		}	
	}

	$dbRes = CRubric::GetList(array('ID' => 'ASC'), array('LID' => SITE_ID, 'ACTIVE' => 'Y'));
	while($arRes = $dbRes->Fetch()){
		$arRubricsID[] = $arRes['ID'];
	}
	
	$arSubscription = CSubscription::GetList(array('ID' => 'ASC'), array('ACTIVE' => 'Y', 'EMAIL' => $arData['EMAIL']))->Fetch();
	
	$subscr = new CSubscription;
	
	if($arSubscription){
		$subscr->Update($arSubscription['ID'], array('EMAIL' => $arData['EMAIL'], 'RUB_ID' => $arRubricsID), SITE_ID);
	}
	else{
		$subscr->Add(array('EMAIL' => $arData['EMAIL'], 'RUB_ID' => $arRubricsID), SITE_ID);
	}
}
?>

<div class="text success">
	<div class="description">
		<svg class="success_icon" xmlns="http://www.w3.org/2000/svg" width="80" height="80" viewBox="0 0 80 80">
		  <path class="clsp-1" d="M1550,156a40,40,0,1,1-40,40A40,40,0,0,1,1550,156Zm0,2a38,38,0,1,1-38,38A38,38,0,0,1,1550,158Z" transform="translate(-1510 -156)"></path>
		  <path id="Ellipse_273_copy_2" data-name="Ellipse 273 copy 2" class="clsp-1" d="M1580.74,210.529l-0.03-.017a0.969,0.969,0,0,1-.84.488,1,1,0,0,1-1-1,1.018,1.018,0,0,1,.15-0.488l-0.01,0A31.934,31.934,0,0,0,1582,196a1,1,0,0,1,2,0A33.8,33.8,0,0,1,1580.74,210.529ZM1550,230a33.825,33.825,0,0,1-15.33-3.661,1.1,1.1,0,0,1-.14-0.066s-0.01,0-.01,0h0a0.99,0.99,0,0,1,.48-1.862,0.944,0.944,0,0,1,.48.141l0.02-.025a31.973,31.973,0,0,0,40.69-10.152l0.03,0.023a0.993,0.993,0,1,1,1.46,1.334A33.953,33.953,0,0,1,1550,230Zm9-64.75a1.043,1.043,0,0,1-.26-0.052v0.02a31.967,31.967,0,0,0-29.55,6.483l-0.02-.026a0.995,0.995,0,0,1-1.73-.675,0.986,0.986,0,0,1,.46-0.822,33.953,33.953,0,0,1,31.41-6.877v0.011a0.986,0.986,0,0,1,.69.938A1,1,0,0,1,1559,165.25ZM1525.43,175.5a32.077,32.077,0,0,0-5.59,9.807l-0.02-.009a1,1,0,0,1-1.95-.3,1.047,1.047,0,0,1,.11-0.434v0a33.786,33.786,0,0,1,5.78-10.183l0.01,0.008a0.976,0.976,0,0,1,.79-0.42,1,1,0,0,1,1,1,0.906,0.906,0,0,1-.16.509Z" transform="translate(-1510 -156)"></path>
		  <path class="clsp-2" d="M1533,194h2l3,3,6,6,16-17,4-4h2l3,2v2l-2,2-21,21-2,1-2-1-6-6-5-5v-3Z" transform="translate(-1510 -156)"></path>
		  <path id="Rounded_Rectangle_840_copy_2" data-name="Rounded Rectangle 840 copy 2" class="clsp-1" d="M1545.41,210.678a4.075,4.075,0,0,1-.74.22,4,4,0,0,1-3.59-1.1h0l-9.91-9.88a4,4,0,0,1,5.66-5.646l7.08,7.057,19.23-19.172a4,4,0,0,1,5.66,5.646L1546.74,209.8A3.924,3.924,0,0,1,1545.41,210.678Zm21.97-24.283a2,2,0,1,0-2.83-2.823l-19.23,19.172-1.41,1.411-1.42-1.411-7.07-7.057a2,2,0,0,0-2.83,0,1.976,1.976,0,0,0,0,2.822l7.45,7.438,2.45,2.443a2.05,2.05,0,0,0,.66.438,2.005,2.005,0,0,0,2.17-.438l2.44-2.432Z" transform="translate(-1510 -156)"></path>
		</svg>
		<div class="success-text">
			<p class="introtexts"><?=GetMessage('THANK_YOU');?></p>
			<p><?=GetMessage('SUBSCRIBE_SUCCESS_TEXT');?></p>
		</div>
	</div>
	<div class="clearfix"></div>
</div>