<?
$bHasLatinLetters = $bHasCyrilicLetters = $bShowAdditionalDiv = false;
$arLatinAlphabet = array_column(\Aspro\Max\Brand::getLatinLetters(), 'LETTER');
$arCyrilicAlphabet = array_column(\Aspro\Max\Brand::getCyrilicLetters(), 'LETTER');

$context = \Bitrix\Main\Application::getInstance()->getContext();
$request = $context->getRequest();

$letterRequest = $request->get('letter');
?>
<div class="filter-letters swipeignore">
	<div class="line-block line-block--flex-wrap line-block--column line-block--align-normal line-block--gap line-block--gap-8 mobile-offset mobile-scrolled mobile-overflow mobile-margin-16 mobile-compact">
		<div class="line-block line-block--flex-wrap line-block--gap line-block--gap-8 font_sm">
			<div class="line-block__item">
				<div class="chip filter-link theme-bg-active color-theme-hover-no-active<?=(!$letterRequest ? ' active' : '');?>">
					<div class="chip__label">
						<?=GetMessage('ALL_LETTERS');?>
					</div>
				</div>
			</div>
			<?foreach ($arFilterLetters as $key => $arLetter):?>
				<?
				$letter = $arLetter['LETTER'];
				$code = 'nums--';
				if (in_array($letter, $arLatinAlphabet)) {
					$code = 'en--';
					$bHasLatinLetters = true;
				} elseif (in_array($letter, $arCyrilicAlphabet)) {
					$code = 'ru--';
					$bHasCyrilicLetters = true;
				}
				$arFilterLetters[$key]['PREFIX'] = $code;
				?>
				<?if ($bHasLatinLetters && $bHasCyrilicLetters && !$bShowAdditionalDiv):?>
					<?$bShowAdditionalDiv = true;?>
					</div>
					<div class="line-block line-block--flex-wrap line-block--gap line-block--gap-8 font_sm">
				<?endif;?>
				<div class="line-block__item">
					<div class="chip filter-link theme-bg-active color-theme-hover-no-active<?=(strtoupper($letterRequest) === strtoupper($code.$arLetter['CODE']) ? ' active' : '');?>" data-letter="<?=$code.$arLetter['CODE'];?>">
						<div class="chip__label">
							<?=$letter;?>
						</div>
					</div>
				</div>
			<?endforeach;?>
		</div>
	</div>
</div>