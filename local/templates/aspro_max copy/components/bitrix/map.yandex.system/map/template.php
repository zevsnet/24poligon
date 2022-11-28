<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();
?>
<?$this->setFrameMode(true);?>
<script type="text/javascript">
if (!window.GLOBAL_arMapObjects)
	window.GLOBAL_arMapObjects = {};

var map;
var animateFunctionexists = false;
var markerSVG = '<svg xmlns="http://www.w3.org/2000/svg" width="46" height="57" viewBox="0 0 46 57">'
					+'<defs><style>.cls-marker, .cls-marker3 {fill: #fff;}.cls-marker, .cls-marker2 {fill-rule: evenodd;}.cls-marker {opacity: 0.5;}.cls-marker2 {fill: #247ee3;}</style></defs>'
					+'<path data-name="Ellipse 275 copy" class="cls-marker" d="M142.976,200.433L143,200.469s-7.05,5.826-10,10.375c-2.263,3.489-2.974,6.153-5,6.156s-2.737-2.667-5-6.156c-2.95-4.549-10-10.375-10-10.375l0.024-.036A23,23,0,1,1,142.976,200.433Z" transform="translate(-105 -160)"/>'
					+'<path data-name="Ellipse 253 copy 4" class="cls-marker2" d="M140,198.971L140,199s-6.362,5.91-8.092,8.456C128.351,212.69,128,215,128,215s-0.307-2.084-3.826-7.448C121.8,203.935,116,199,116,199l0-.029A20,20,0,1,1,140,198.971Z" transform="translate(-105 -160)"/>'
					+'<circle data-name="Ellipse 254 copy 5" class="cls-marker3" cx="23" cy="23" r="12"/>'
				+'</svg>';

function init_<?echo $arParams['MAP_ID']?>()
{
	if (!window.ymaps)
		return;

	/*if(typeof window.GLOBAL_arMapObjects['<?echo $arParams['MAP_ID']?>'] !== "undefined")
		return;*/

	var node = BX("BX_YMAP_<?echo $arParams['MAP_ID']?>");
	node.innerHTML = '';

	map = window.GLOBAL_arMapObjects['<?echo $arParams['MAP_ID']?>'] = new ymaps.Map(node, {
		center: [<?echo $arParams['INIT_MAP_LAT']?>, <?echo $arParams['INIT_MAP_LON']?>],
		zoom: <?echo $arParams['INIT_MAP_SCALE']?>,
		type: 'yandex#<?=$arResult['ALL_MAP_TYPES'][$arParams['INIT_MAP_TYPE']]?>',
		// adjustZoomOnTypeChange: true
	});
	
	var props = {};
	map.options.set('balloonAutoPanMargin', 10);
	/*map.geoObjects.events.add('balloonopen', function (e){
	var balloon = new ymaps.Balloon(map);
		props.autoPanMargin = [-100, 0, 0, 0];
		balloon.options.setParent(props);
		console.log(123);
	});*/
	
	/*map.geoObjects.events.add('balloonclose', function (e){
		setTimeout(function(){
			$('.ymaps-image-with-content').each(function(){
				if(!$(this).find('.marker').length){
					$(this).prepend('<div class="marker">'+markerSVG+'</div>');
				}
			});
		}, 20);
	});
		
	map.events.add('boundschange', function (e) {
		//$('.ymaps-image-with-content .marker').remove();
		setTimeout(function(){
			$('.ymaps-image-with-content').each(function(){
				if(!$(this).find('.marker').length){
					$(this).prepend('<div class="marker">'+markerSVG+'</div>');
				}
			});
			//$(window).resize();
		}, 300);
	});*/
	
	if($('.contacts.type_4')){
		ymaps.ready(function(){
			$('.contacts.type_4 .bx-yandex-view-map').css('opacity', 0);
			setTimeout(function(){
				map.container.fitToViewport();
				setTimeout(function(){
					$('.contacts.type_4 .bx-yandex-view-map').css('opacity', 1);
				}, 500);
			}, 1500);
		});
	}
<?
foreach ($arResult['ALL_MAP_OPTIONS'] as $option => $method)
{
	if (in_array($option, $arParams['OPTIONS'])):
?>
	map.behaviors.enable("<?echo $method?>");
<?
	else:
?>
	if (map.behaviors.isEnabled("<?echo $method?>"))
		map.behaviors.disable("<?echo $method?>");
<?
	endif;
}

foreach ($arResult['ALL_MAP_CONTROLS'] as $control => $method)
{
	if (in_array($control, $arParams['CONTROLS'])):
?>
	map.controls.add('<?=$method?>');

<?
	endif;
}


if ($arParams['DEV_MODE'] == 'Y'):
?>
	window.bYandexMapScriptsLoaded = true;
<?
endif;

if ($arParams['ONMAPREADY']):
?>
	if (window.<?echo $arParams['ONMAPREADY']?>)
	{
<?
	if ($arParams['ONMAPREADY_PROPERTY']):
?>
		<?echo $arParams['ONMAPREADY_PROPERTY']?> = map;
		window.<?echo $arParams['ONMAPREADY']?>();
<?
	else:
?>
		window.<?echo $arParams['ONMAPREADY']?>(map);
<?
	endif;
?>
	}
<?
endif;

?>
}
<?
if ($arParams['DEV_MODE'] == 'Y'):
?>
function BXMapLoader_<?echo $arParams['MAP_ID']?>()
{
	if (null == window.bYandexMapScriptsLoaded)
	{
		function _wait_for_map(){
			if (window.ymaps && window.ymaps.Map)
				init_<?echo $arParams['MAP_ID']?>();
			else
				setTimeout(_wait_for_map, 50);
		}
		BX.loadScript('<?=$arResult['MAPS_SCRIPT_URL']?>', _wait_for_map);
	}
	else
	{
		init_<?echo $arParams['MAP_ID']?>();
	}
}
<?
	if ($arParams['WAIT_FOR_EVENT']):
?>
	<?=CUtil::JSEscape($arParams['WAIT_FOR_EVENT'])?> = BXMapLoader_<?=$arParams['MAP_ID']?>;
<?
	elseif ($arParams['WAIT_FOR_CUSTOM_EVENT']):
?>
	BX.addCustomEvent('<?=CUtil::JSEscape($arParams['WAIT_FOR_EVENT'])?>', BXMapLoader_<?=$arParams['MAP_ID']?>);
<?
	else:
?>
	BX.ready(BXMapLoader_<?echo $arParams['MAP_ID']?>);
<?
	endif;
else: // $arParams['DEV_MODE'] == 'Y'
?>
			
(function bx_ymaps_waiter(){
	if(typeof ymaps !== 'undefined'){
		ymaps.ready(init_<?echo $arParams['MAP_ID']?>);
	}
	else
		setTimeout(bx_ymaps_waiter, 100);
	
})();

<?
endif; // $arParams['DEV_MODE'] == 'Y'
?>

/* if map inits in hidden block (display:none)
*  after the block showed
*  for properly showing map this function must be called
*/
function BXMapYandexAfterShow(mapId)
{
	if(window.GLOBAL_arMapObjects[mapId] !== undefined)
		window.GLOBAL_arMapObjects[mapId].container.fitToViewport();
}
</script>
<div id="BX_YMAP_<?echo $arParams['MAP_ID']?>" class="bx-yandex-map" style="height: <?echo $arParams['MAP_HEIGHT'];?>; width: <?echo $arParams['MAP_WIDTH']?>;"><?echo GetMessage('MYS_LOADING'.($arParams['WAIT_FOR_EVENT'] ? '_WAIT' : ''));?></div>