<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>

<?use \Bitrix\Main\Localization\Loc;?>
<?$_SESSION['LETTERS_LIST'] = [];?>
<?$frame = $this->createFrame()->begin('');?>
	<div 
		id="js-observer-block"
		class="js-brands loading_block_content"
		data-file="<?=$arParams['AJAX_PATH'];?>"
		data-letters='<?=str_replace('\'', '"', CUtil::PhpToJSObject($arParams['LETTERS'], false))?>'
		data-letter="<?=$arParams['LETTER'];?>"
		data-iblock="<?=$arParams['IBLOCK_ID'];?>"
		data-site_id="<?=SITE_ID;?>"
	>
		<div class="skeleton-grid" style="--gap: 0px;">
			<?foreach (range(1,1) as $val):?>
				<div class="skeleton-grid skeleton-brand-row bordered" style="grid-template-columns: 100px 1fr;">
					<div class="skeleton" style="height: 24px;"></div>
					<div class="skeleton-grid skeleton-grid--column" style="--repeat-column: 4; --gap: 6px 15px;">
						<?foreach (range(1,8) as $val):?>
							<div class="skeleton" style="height: 12px;"></div>
						<?endforeach;?>
					</div>
				</div>
			<?endforeach;?>
		</div>
	</div>
	<script data-skip-moving="true">
		const target = document.querySelector('#js-observer-block');
		var $brandLoader = target.querySelector('.skeleton-grid');
		
		//in FF observe long delay for show block
		/*
		if ('IntersectionObserver' in window) {
			const options = {
				root: null,
				rootMargin: '32px',
				threshold: 0
			}
			const callback = function(entries, observer) {
				entries.forEach(function(entry) {
					if (entry.isIntersecting) {
						requestBrand(entry.target)
						observer.unobserve(entry.target)
					}
				});
			};
			const observer = new IntersectionObserver(callback, options);
			observer.observe(target);
		}
		*/
		requestBrand(target);

		function requestBrand(target, loader, cb){
			if (!target) return;
			target.classList.add('loading')
			if (loader && BX.type.isDomNode(loader)) target.innerHTML = loader.outerHTML
			$.ajax({
				url: target.dataset['file'],
				method: 'POST',
				data: {
					'SITE_ID':target.dataset['site_id'],
					'IBLOCK_ID':target.dataset['iblock'],
					'LETTERS':target.dataset['letters'],
					'LETTER':target.dataset['letter']
				},
				error: function(data) {
					console.error(data)
				},
				success: function(html) {
					target.classList.remove('loading')
					target.innerHTML = html

					if (cb && typeof cb === 'function') cb()
				}
			})
		}
	</script>
<?$frame->end();?>