<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();
$this->setFrameMode(true);

$arParams['BX_EDITOR_RENDER_MODE'] = $arParams['BX_EDITOR_RENDER_MODE'] ?? 'N';
$arParams['KEY'] = $arParams['KEY'] ?? '';
$arParams['LOCALE'] = $arParams['LOCALE'] ?? '';
$arParams['DEV_MODE'] = $arParams['DEV_MODE'] ?? '';
$arParams['ONMAPREADY'] = $arParams['ONMAPREADY'] ?? '';

if ($arParams['BX_EDITOR_RENDER_MODE'] == 'Y'):
?>
<img src="/bitrix/components/bitrix/map.yandex.view/templates/.default/images/screenshot.png" border="0" />
<?
else:

	$arTransParams = array(
		'KEY' => $arParams['KEY'],
		'INIT_MAP_TYPE' => $arParams['INIT_MAP_TYPE'],
		'INIT_MAP_LON' => $arResult['POSITION']['yandex_lon'],
		'INIT_MAP_LAT' => $arResult['POSITION']['yandex_lat'],
		'INIT_MAP_SCALE' => $arResult['POSITION']['yandex_scale'],
		'MAP_WIDTH' => $arParams['MAP_WIDTH'],
		'MAP_HEIGHT' => $arParams['MAP_HEIGHT'],
		'CONTROLS' => $arParams['CONTROLS'],
		'OPTIONS' => $arParams['OPTIONS'],
		'MAP_ID' => $arParams['MAP_ID'],
		'LOCALE' => $arParams['LOCALE'],
		'ONMAPREADY' => 'BX_SetPlacemarks_'.$arParams['MAP_ID'],
	);

	if ($arParams['DEV_MODE'] == 'Y')
	{
		$arTransParams['DEV_MODE'] = 'Y';
		if ($arParams['WAIT_FOR_EVENT'])
			$arTransParams['WAIT_FOR_EVENT'] = $arParams['WAIT_FOR_EVENT'];
	}
?>
<script type="text/javascript">
var geo_result;
var clusterer;
var markerSVG = '<svg xmlns="http://www.w3.org/2000/svg" width="46" height="57" viewBox="0 0 46 57">'
					+'<defs><style>.cls-marker, .cls-marker3 {fill: #fff;}.cls-marker, .cls-marker2 {fill-rule: evenodd;}.cls-marker {opacity: 0.5;}.cls-marker2 {fill: #247ee3;}</style></defs>'
					+'<path data-name="Ellipse 275 copy" class="cls-marker" d="M142.976,200.433L143,200.469s-7.05,5.826-10,10.375c-2.263,3.489-2.974,6.153-5,6.156s-2.737-2.667-5-6.156c-2.95-4.549-10-10.375-10-10.375l0.024-.036A23,23,0,1,1,142.976,200.433Z" transform="translate(-105 -160)"/>'
					+'<path data-name="Ellipse 253 copy 4" class="cls-marker2" d="M140,198.971L140,199s-6.362,5.91-8.092,8.456C128.351,212.69,128,215,128,215s-0.307-2.084-3.826-7.448C121.8,203.935,116,199,116,199l0-.029A20,20,0,1,1,140,198.971Z" transform="translate(-105 -160)"/>'
					+'<circle data-name="Ellipse 254 copy 5" class="cls-marker3" cx="23" cy="23" r="12"/>'
				+'</svg>';

var clusterSVG = '<div class="cluster_custom"><span>$[properties.geoObjects.length]</span>'
					+'<svg xmlns="http://www.w3.org/2000/svg" width="56" height="56" viewBox="0 0 56 56">'
						+'<defs><style>.cls-cluster, .cls-cluster3 {fill: #fff;}.cls-cluster {opacity: 0.5;}.cls-cluster2 {fill: #247ee3;}</style></defs>'
						+'<circle class="cls-cluster" cx="28" cy="28" r="28"/>'
						+'<circle data-name="Ellipse 275 copy 2" class="cls-cluster2" cx="28" cy="28" r="25"/>'
						+'<circle data-name="Ellipse 276 copy" class="cls-cluster3" cx="28" cy="28" r="18"/>'
					+'</svg>'
				+'</div>';
				
var closeSVG = '<svg class="svg-close" width="14" height="14" viewBox="0 0 14 14">'
					+'<path data-name="Rounded Rectangle 568 copy 16" class="cls-1" d="M1009.4,953l5.32,5.315a0.987,0.987,0,0,1,0,1.4,1,1,0,0,1-1.41,0L1008,954.4l-5.32,5.315a0.991,0.991,0,0,1-1.4-1.4L1006.6,953l-5.32-5.315a0.991,0.991,0,0,1,1.4-1.4l5.32,5.315,5.31-5.315a1,1,0,0,1,1.41,0,0.987,0.987,0,0,1,0,1.4Z" transform="translate(-1001 -946)"></path>'
				+'</svg>';

function BX_SetPlacemarks_<?echo $arParams['MAP_ID']?>(map)
{
	if(typeof window["BX_YMapAddPlacemark"] != 'function')
	{
		/* If component's result was cached as html,
		 * script.js will not been loaded next time.
		 * let's do it manualy.
		*/

		(function(d, s, id)
		{
			var js, bx_ym = d.getElementsByTagName(s)[0];
			if (d.getElementById(id)) return;
			js = d.createElement(s); js.id = id;
			js.src = "<?=$templateFolder.'/script.js'?>";
			bx_ym.parentNode.insertBefore(js, bx_ym);
		}(document, 'script', 'bx-ya-map-js'));

		var ymWaitIntervalId = setInterval( function(){
				if(typeof window["BX_YMapAddPlacemark"] == 'function')
				{
					BX_SetPlacemarks_<?echo $arParams['MAP_ID']?>(map);
					clearInterval(ymWaitIntervalId);
				}
			}, 300
		);

		return;
	}
	var arObjects = {PLACEMARKS:[],POLYLINES:[]};
	clusterer = new ymaps.Clusterer();

<?
if( is_array($arResult['POSITION']['PLACEMARKS']) && ($cnt = count($arResult['POSITION']['PLACEMARKS'])) ){
	for( $i = 0; $i < $cnt; $i++ ){
?>
arObjects.PLACEMARKS[arObjects.PLACEMARKS.length] = BX_YMapAddPlacemark(map, <?echo CUtil::PhpToJsObject($arResult['POSITION']['PLACEMARKS'][$i])?><?if(count($arResult['POSITION']['PLACEMARKS'])>1):?>, true<?endif;?>);<?	
	}
}
?>

<?
	if (isset($arResult['POSITION']['POLYLINES']) && is_array($arResult['POSITION']['POLYLINES']) && ($cnt = count($arResult['POSITION']['POLYLINES']))):
		for($i = 0; $i < $cnt; $i++):
?>
	arObjects.POLYLINES[arObjects.POLYLINES.length] = BX_YMapAddPolyline(map, <?echo CUtil::PhpToJsObject($arResult['POSITION']['POLYLINES'][$i])?>);
<?
		endfor;
	endif;	

	if ($arParams['ONMAPREADY']):
?>
	if (window.<?echo $arParams['ONMAPREADY']?>)
	{
		window.<?echo $arParams['ONMAPREADY']?>(map, arObjects);
	}
<?
	endif;
?>
	/* set dynamic zoom for ballons */
	// map.setBounds(map.geoObjects.getBounds(), {checkZoomRange: true});
	   
	map.geoObjects.events.add('click', function (e) {
		setTimeout(function(){
			$('.ymaps-b-balloon .ymaps-b-balloon__close').addClass('close_custom').html(closeSVG);
		}, 100);
	});
	<?if(count($arResult['POSITION']['PLACEMARKS'])>1):?>
		 var clusterIcons = [{
			size: [56, 56],
			offset: [-28, -28]
		},
		/*{
			shape: {
				type: 'Circle',
				coordinates: [0, 0],
				radius: 28
			}
		}*/];

		clusterer = new ymaps.Clusterer({
		   clusterIcons: clusterIcons,
		   clusterIconContentLayout: ymaps.templateLayoutFactory.createClass(clusterSVG),
	   });
	   clusterer.add(arObjects.PLACEMARKS);
	    map.geoObjects.add(clusterer);
		// map.setBounds(map.geoObjects.getBounds(), {
		map.setBounds(clusterer.getBounds(), {
			zoomMargin: 40,
			// checkZoomRange: true
		});

		/*geo_result = ymaps.geoQuery(map.geoObjects);
		geo_result.applyBoundsToMap(map);*/

	<?endif;?>	

	$('.block_container .items .item').addClass('initied');
}

$(document).ready(function(){
	$('.contacts-stores .item .top-wrap .show_on_map>span[data-coordinates]').on('click', function(){
		var arCoordinates = $(this).data('coordinates').split(','),
			mapOffsetTop = $('.contacts-page-map').offset().top;

		$('html, body').animate({scrollTop: mapOffsetTop - (isMobile ? 20 : 180)}, 300);

		map.setCenter([arCoordinates[0], arCoordinates[1]], '<?=$arResult['POSITION']['yandex_scale']?>');
	});

	$('.stores-list1 .item .top-wrap .show_on_map>span[data-coordinates]').on('click', function(){
		var arCoordinates = $(this).data('coordinates').split(','),
			mapOffsetTop = $('.contacts_map').offset().top;
			
		$('html, body').animate({scrollTop: mapOffsetTop - (isMobile ? 20 : 180)}, 300);

		map.setCenter([arCoordinates[0], arCoordinates[1]], '17');
	});
});

</script>
<div class="bx-yandex-view-layout swipeignore">
	<div class="bx-yandex-view-map">
<?
	$APPLICATION->IncludeComponent('bitrix:map.yandex.system', 'map', $arTransParams, false, array('HIDE_ICONS' => 'Y'));
?>
	</div>
	<div class="yandex-map__mobile-opener"></div>
</div>
<?
endif;
?>