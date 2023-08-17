const showOutOfProductionBlock = function () {
	const $jsBlock = document.querySelector("#js-item-analog");
	const $jsBlockDesktop = document.querySelector(".js-item-analog");
	const $jsBlockMobile = document.querySelector(".js-item-analog-mobile");

	if (!$jsBlock && (!$jsBlockDesktop || !$jsBlockMobile)) return;

	let params = $jsBlock.dataset.params;
	
	if (params) {
		try {
			const objUrl = parseUrlQuery();
			let add_url = '';
			params = JSON.parse(params);

			if ("clear_cache" in objUrl) {
				if (objUrl.clear_cache === "Y") 
					add_url += "?clear_cache=Y";
			}
			
			$.post(
				arAsproOptions["SITE_DIR"] + "ajax/out_of_production.php" + add_url,
				params,
				function (result) {					
					$jsBlockDesktop.innerHTML = result;
					if ($jsBlockMobile) {
						$jsBlockMobile.innerHTML = result;
					}
					setStatusButton();

					if (window.matchMedia('(min-width: 992px)').matches) {
						$($jsBlockDesktop).slideDown(800);
					} else {
						$($jsBlockDesktop).css('display', 'block');
					}

					if ($jsBlockMobile) {
						if (window.matchMedia('(max-width: 991px)').matches) {
							$($jsBlockMobile).slideDown(800);
						} else {
							$($jsBlockMobile).css('display', 'block');
						}
					}
					
				}
			);
		} catch (e) {}
	}
};

$(document).ready(function() {
  showOutOfProductionBlock();
});