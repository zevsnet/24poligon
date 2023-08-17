/*function retrieveScale(btn) {
	var btnRadius = btn.width()/2,
		left = btn.offset().left + btnRadius,
		top = btn.offset().top + btnRadius - $(window).scrollTop(),
		scale = scaleValue(top, left, btnRadius, $(window).height(), $(window).width());

	btn.css('position', 'fixed').velocity({
		top: top - btnRadius,
		left: left - btnRadius,
		translateX: 0,
	}, 0);

	return scale;
}
*/

function scaleValue( topValue, leftValue, radiusValue, windowW, windowH) {
	var maxDistHor = ( leftValue > windowW/2) ? leftValue : (windowW - leftValue),
		maxDistVert = ( topValue > windowH/2) ? topValue : (windowH - topValue);
	return Math.ceil(Math.sqrt( Math.pow(maxDistHor, 2) + Math.pow(maxDistVert, 2) )/radiusValue);
}
/*
function animateLayer(layer, scaleVal, bool) {
	layer.velocity({ scale: scaleVal }, 400, function(){
		$('body').toggleClass('overflow-hidden', bool);
		(bool)
			? layer.parents('.cd-section').addClass('modal-is-visible').end().off('webkitTransitionEnd otransitionend oTransitionEnd msTransitionEnd transitionend')
			: layer.removeClass('is-visible').removeAttr( 'style' ).siblings('[data-type="modal-trigger"]').removeClass('to-circle');
	});
}

function updateLayer() {
	var layer = $('.cd-section.modal-is-visible .cd-modal-bg'),
		layerRadius = layer.width()/2,
		layerTop = layer.siblings('.btn').offset().top + layerRadius - $(window).scrollTop(),
		layerLeft = layer.siblings('.btn').offset().left + layerRadius,
		scale = scaleValue(layerTop, layerLeft, layerRadius, $(window).height(), $(window).width());

	layer.velocity({
		top: layerTop - layerRadius,
		left: layerLeft - layerRadius,
		scale: scale,
	}, 0);
}
*/

function closeModal() {
	$('.cd-modal-bg').fadeOut();
	// animateLayer($('.cd-modal-bg'), 1, false);
	//$('.cd-modal-bg').removeAttr('style');
	//setTimeout(function(){
		$('.cd-modal-bg').removeClass('is-visible');
	//}, 510);
}