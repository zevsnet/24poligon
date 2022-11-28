<?if(!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true ) die();?>
<?$this->setFrameMode(true);?>
<?use \Bitrix\Main\Localization\Loc;?>

<?
if($arResult['BENEFITS'])
{
	$templateData['BENEFITS'] = $arResult['DISPLAY_PROPERTIES']['LINK_BENEFIT']['VALUE'];
}
?>

<div class="content_wrapper_block <?=$templateName;?>">
	<div class="maxwidth-theme <?=$arParams['TYPE_BLOCK'] == 'type2' ? '' : 'wide'?> ">
		<?// preview image
		$bShowImage = in_array('PREVIEW_PICTURE', $arParams['FIELD_CODE']);
		$bShowUrl = (isset($arResult['DISPLAY_PROPERTIES']['URL']) && strlen($arResult['DISPLAY_PROPERTIES']['URL']['VALUE']));

		if($bShowImage)
		{
			$bImage = strlen($arResult['FIELDS']['PREVIEW_PICTURE']['SRC']);
			$arImage = ($bImage ? CFile::ResizeImageGet($arResult['FIELDS']['PREVIEW_PICTURE']['ID'], array('width' => 1000, 'height' => 1000), BX_RESIZE_IMAGE_PROPORTIONAL_ALT, true) : array());
			$imageSrc = ($bImage ? $arImage['src'] : '');
		}

		$bNoImg = ($arParams['TYPE_IMG'] == 'sm no-img' && $arParams['TYPE_BLOCK'] == 'type2');

		$videoSource = strlen($arResult['PROPERTIES']['VIDEO_SOURCE']['VALUE_XML_ID']) ? $arResult['PROPERTIES']['VIDEO_SOURCE']['VALUE_XML_ID'] : 'LINK';
		$videoSrc = $arResult['PROPERTIES']['VIDEO_SRC']['VALUE'];
		if($videoFileID = $arResult['PROPERTIES']['VIDEO']['VALUE'])
			$videoFileSrc = CFile::GetPath($videoFileID);

		$videoPlayer = $videoPlayerSrc = '';
		if($videoSource == 'LINK' ? strlen($videoSrc) : strlen($videoFileSrc))
		{
			$bVideo = true;
			// $bVideoAutoStart = $arResult['PROPERTIES']['VIDEO_AUTOSTART']['VALUE_XML_ID'] === 'YES';
			if(strlen($videoSrc) && $videoSource === 'LINK')
			{
				// videoSrc available values
				// YOTUBE:
				// https://youtu.be/WxUOLN933Ko
				// <iframe width="560" height="315" src="https://www.youtube.com/embed/WxUOLN933Ko" frameborder="0" allowfullscreen></iframe>
				// VIMEO:
				// https://vimeo.com/211336204
				// <iframe src="https://player.vimeo.com/video/211336204?title=0&byline=0&portrait=0" width="640" height="360" frameborder="0" webkitallowfullscreen mozallowfullscreen allowfullscreen></iframe>
				// RUTUBE:
				// <iframe width="720" height="405" src="//rutube.ru/play/embed/10314281" frameborder="0" webkitAllowFullScreen mozallowfullscreen allowfullscreen></iframe>

				$videoPlayer = 'YOUTUBE';
				$videoSrc = htmlspecialchars_decode($videoSrc);
				if(strpos($videoSrc, 'iframe') !== false)
				{
					$re = '/<iframe.*src=\"(.*)\".*><\/iframe>/isU';
					preg_match_all($re, $videoSrc, $arMatch);
					$videoSrc = $arMatch[1][0];
				}
				$videoPlayerSrc = $videoSrc;

				switch($videoSrc)
				{
					case(($v = strpos($videoSrc, 'vimeo.com/')) !== false):
						$videoPlayer = 'VIMEO';
						if(strpos($videoSrc, 'player.vimeo.com/') === false)
							$videoPlayerSrc = str_replace('vimeo.com/', 'player.vimeo.com/', $videoPlayerSrc);

						if(strpos($videoSrc, 'vimeo.com/video/') === false)
							$videoPlayerSrc = str_replace('vimeo.com/', 'vimeo.com/video/', $videoPlayerSrc);

						break;
					case(($v = strpos($videoSrc, 'rutube.ru/')) !== false):
						$videoPlayer = 'RUTUBE';
						break;
					case(strpos($videoSrc, 'watch?') !== false && ($v = strpos($videoSrc, 'v=')) !== false):
						$videoPlayerSrc = 'https://www.youtube.com/embed/'.substr($videoSrc, $v + 2, 11);
						break;
					case(strpos($videoSrc, 'youtu.be/') !== false && $v = strpos($videoSrc, 'youtu.be/')):
						$videoPlayerSrc = 'https://www.youtube.com/embed/'.substr($videoSrc, $v + 9, 11);
						break;
					case(strpos($videoSrc, 'embed/') !== false && $v = strpos($videoSrc, 'embed/')):
						$videoPlayerSrc = 'https://www.youtube.com/embed/'.substr($videoSrc, $v + 6, 11);
						break;
				}

				$bVideoPlayerYoutube = $videoPlayer === 'YOUTUBE';
				$bVideoPlayerVimeo = $videoPlayer === 'VIMEO';
				$bVideoPlayerRutube = $videoPlayer === 'RUTUBE';

				if(strlen($videoPlayerSrc))
				{
					$videoPlayerSrc = trim($videoPlayerSrc.
						($bVideoPlayerYoutube ? '?autoplay=1&enablejsapi=1&controls=0&showinfo=0&rel=0&disablekb=1&iv_load_policy=3' :
						($bVideoPlayerVimeo ? '?autoplay=1&badge=0&byline=0&portrait=0&title=0' :
						($bVideoPlayerRutube ? '?quality=1&autoStart=0&sTitle=false&sAuthor=false&platform=someplatform' : '')))
					);
				}
			}
			else
			{
				$videoPlayer = 'HTML5';
				$videoPlayerSrc = $videoFileSrc;
			}
		}
		?>

		<div class="item-views company lazy <?=$arParams['TYPE_IMG'];?> <?=$arParams['TYPE_BLOCK'];?><?=($arParams['TYPE_IMG'] == 'lg' ? ' ' : ' with-padding');?>" <?if($arResult['BG_IMG']):?>data-src="<?=$arResult['BG_IMG'];?>" style="background-image:url(<?=\Aspro\Functions\CAsproMax::showBlankImg($arResult['BG_IMG']);?>)"<?endif;?>>
			<div class="company-block">
				<div class="row flexbox<?=($arParams['TYPE_BLOCK'] == 'type2' ? ($arParams['REVERCE_IMG_BLOCK'] == 'Y' ? '' : ' flex-direction-row-reverse') : ($arParams['REVERCE_IMG_BLOCK'] == 'Y' ? ' flex-direction-row-reverse' : ''))?>">
					<div class="col-md-<?=($arParams['TYPE_BLOCK'] == 'type2' ? ($arParams['TYPE_IMG'] == 'md' ? 8 : 9) : 6);?> text-block col-xs-12">
						<div class="item">
							<div class="item-inner">
								<div class="text">
									<?ob_start();?>
										<?if($bShowUrl):?>
											<a class="show_all muted font_upper" href="<?=$arResult['DISPLAY_PROPERTIES']['URL']['VALUE'];?>">
										<?else:?>
											<span class="muted font_upper">
										<?endif;?>
										
										<?if(in_array('NAME', $arParams['FIELD_CODE']) && $arResult['FIELDS']['NAME']):?>
											<span><?=$arResult['FIELDS']['NAME']?></span>
										<?endif;?>

										<?if($bShowUrl):?>
											</a>
										<?else:?>
											</span>
										<?endif;?>
									<?$text = ob_get_contents();
									ob_end_clean();?>

									<?if(!$bNoImg):?>
										<?=$text;?>
									<?endif;?>

									<?if($arParams['REGION'] && $arParams['~REGION']['DETAIL_TEXT']):?>
										<?=$arParams['~REGION']['DETAIL_TEXT'];?>
									<?else:?>
										<?if(isset($arResult['DISPLAY_PROPERTIES']['COMPANY_NAME']) && $arResult['DISPLAY_PROPERTIES']['COMPANY_NAME']['VALUE']):?>
											<h3><?=$arResult['DISPLAY_PROPERTIES']['COMPANY_NAME']['VALUE'];?></h3>
										<?endif;?>
										<?if($arResult['PREVIEW_TEXT']):?>
											<div class="preview-text muted777"><?=$arResult['PREVIEW_TEXT'];?></div>
										<?endif;?>
									<?endif;?>

									<?ob_start();?>
										<div class="buttons">
											<?if(isset($arResult['DISPLAY_PROPERTIES']['URL']) && strlen($arResult['DISPLAY_PROPERTIES']['URL']['VALUE'])):?>
												<a class="btn <?=(!$bNoImg ? 'btn-default' : 'btn-transparent-border-color');?>" href="<?=str_replace('//', '/', SITE_DIR.'/'.$arResult['DISPLAY_PROPERTIES']['URL']['VALUE']);?>"><span><?=(strlen($arResult['PROPERTIES']['MORE_BUTTON_TITLE']['VALUE']) ? $arResult['PROPERTIES']['MORE_BUTTON_TITLE']['VALUE'] : Loc::getMessage('S_TO_SHOW_ALL_MORE'))?></span></a>
											<?endif;?>
										</div>
									<?$button = ob_get_contents();
									ob_end_clean();?>

									<?if($bNoImg):?>
										<?=$button;?>
									<?endif;?>

									<div class="js-tizers"></div>
									

									<?if(!$bNoImg):?>
										<?=$button;?>
									<?endif;?>
								</div>
							</div>
						</div>
					</div>
					<div class="col-md-<?=($arParams['TYPE_BLOCK'] == 'type2' ? ($arParams['TYPE_IMG'] == 'md' ? 4 : 3) : 6);?> image-block col-xs-12">
						<div class="item video-block">
							<?if($bNoImg):?>
								<div class="with-text-block-wrapper">
									<?=$text;?>
									<div class="js-h3"></div>
									<?=$button;?>
								</div>
							<?elseif($bImage):?>
								<div class="image lazy<?=($arParams['TYPE_BLOCK'] == 'type2' ? ' rounded' : '');?>" data-src="<?=$imageSrc;?>" style="background-image:url(<?=\Aspro\Functions\CAsproMax::showBlankImg($imageSrc);?>)"  data-video_source="<?=$videoSource?>" <?=(strlen($videoPlayer) ? ' data-video_player="'.$videoPlayer.'"' : '')?><?=(strlen($videoPlayerSrc) ? ' data-video_src="'.$videoPlayerSrc.'"' : '')?>>
									<?if($bVideo):?>
										<div class="play">
											<div class="fancy" rel="nofollow">
												<?if($videoPlayer == 'HTML5'):?>
													<video id="company_video" muted playsinline controls loop><source  class="video-block" src="#" data-src="<?=$videoPlayerSrc;?>" type='video/mp4; codecs="avc1.42E01E, mp4a.40.2"' /></video>
												<?else:?>
													<iframe id="company_video_iframe" data-src="<?=$videoPlayerSrc;?>"></iframe>
												<?endif;?>
											</div>
										</div>
									<?endif;?>
								</div>
							<?endif;?>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>