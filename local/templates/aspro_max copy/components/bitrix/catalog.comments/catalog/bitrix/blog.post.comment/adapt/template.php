<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
/** @global CMain $APPLICATION */
CJSCore::Init(array("image"));
?>
<script>
BX.ready( function(){
	if(BX.viewImageBind)
	{
		BX.viewImageBind(
			'blg-comment-<?=$arParams["ID"]?>',
			false,
			{tag:'IMG', attr: 'data-bx-image'}
		);
	}
});
</script>
<div id="reviews_sort_continer"></div>
<div class="blog-comments" id="blg-comment-<?=$arParams["ID"]?>">
<a name="comments"></a>
<?
if($arResult["is_ajax_post"] != "Y") {
	include($_SERVER["DOCUMENT_ROOT"].$templateFolder."/script.php");
}
else
{
	$APPLICATION->RestartBuffer();
	?>
	<script>window.BX = top.BX;
		<?if($arResult["use_captcha"]===true)
		{
			?>
				var cc ='<?=$arResult["CaptchaCode"]?>';
				if(BX('captcha')){
					BX('captcha').src='/bitrix/tools/captcha.php?captcha_code='+cc;
				}

				if(BX('captcha_code')){
					BX('captcha_code').value = cc;
				}

				if(BX('captcha_word')){
					BX('captcha_word').value = "";
				}
			<?
		}
	?>
	if(!top.arImages)
		top.arImages = [];
	if(!top.arImagesId)
		top.arImagesId = [];
	<?
	if($arResult["Images"]) {
		foreach($arResult["Images"] as $aImg)
		{
			?>
			top.arImages.push('<?=CUtil::JSEscape($aImg["SRC"])?>');
			top.arImagesId.push('<?=$aImg["ID"]?>');
			<?
		}
	}
	?>
	</script><?
	if(strlen($arResult["COMMENT_ERROR"])>0)
	{
		?>
		<script>top.commentEr = 'Y';</script>
		<div class="alert alert-danger blog-note-box blog-note-error">
			<div class="blog-error-text">
				<?=$arResult["COMMENT_ERROR"]?>
			</div>
		</div>
		<?
	}
}

if(strlen($arResult["MESSAGE"])>0)
{
	?>
	<div class="blog-textinfo blog-note-box">
		<div class="blog-textinfo-text">
			<?=$arResult["MESSAGE"]?>
		</div>
	</div>
	<?
}
if(strlen($arResult["ERROR_MESSAGE"])>0)
{
	?>
	<div class="alert alert-danger blog-note-box blog-note-error">
		<div class="blog-error-text" id="blg-com-err">
			<?=$arResult["ERROR_MESSAGE"]?>
		</div>
	</div>
	<?
}
if(strlen($arResult["FATAL_MESSAGE"])>0)
{
	?>
	<div class="alert alert-danger blog-note-box blog-note-error">
		<div class="blog-error-text">
			<?=$arResult["FATAL_MESSAGE"]?>
		</div>
	</div>
	<?
}
else
{
	if($arResult["imageUploadFrame"] == "Y")
	{
		?>
		<script>
			<?if(!empty($arResult["Image"])):?>
				top.bxBlogImageId = top.arImagesId.push('<?=$arResult["Image"]["ID"]?>');
				top.arImages.push('<?=CUtil::JSEscape($arResult["Image"]["SRC"])?>');
				top.bxBlogImageIdWidth = '<?=CUtil::JSEscape($arResult["Image"]["WIDTH"])?>';
			<?elseif(strlen($arResult["ERROR_MESSAGE"]) > 0):?>
				top.bxBlogImageError = '<?=CUtil::JSEscape($arResult["ERROR_MESSAGE"])?>';
			<?endif;?>
		</script>
		<?
		die();
	}
	else
	{
		if($arResult["is_ajax_post"] != "Y" && $arResult["CanUserComment"])
		{
			/*$ajaxPath = POST_FORM_ACTION_URI;
			$parent = $component->GetParent();
			if (isset($parent) && is_object($parent))
			{
				$ajaxPath = $parent->GetTemplate()->GetFolder().'/ajax.php';
			}*/
			$ajaxPath = $templateFolder.'/ajax.php';
			?>
			<div id="form_comment_" style="display:none;">
				<div id="form_c_del" style="display:none;">
				<div class="blog-comment-form rounded3 bordered">
				<form enctype="multipart/form-data" method="POST" name="form_comment" id="form_comment" action="<?=$ajaxPath; ?>">
				<input type="hidden" name="parentId" id="parentId" value="">
				<input type="hidden" name="edit_id" id="edit_id" value="">
				<input type="hidden" name="act" id="act" value="add">
				<input type="hidden" name="post" value="Y">
				<?
				if(isset($_REQUEST["IBLOCK_ID"]))
				{
					?><input type="hidden" name="IBLOCK_ID" value="<?=(int)$_REQUEST["IBLOCK_ID"]; ?>"><?
				}
				if(isset($_REQUEST["ELEMENT_ID"]))
				{
					?><input type="hidden" name="ELEMENT_ID" value="<?=(int)$_REQUEST["ELEMENT_ID"]; ?>"><?
				}
				if(isset($_REQUEST["SITE_ID"]))
				{
					?><input type="hidden" name="SITE_ID" value="<?=htmlspecialcharsbx($_REQUEST["SITE_ID"]); ?>"><?
				}

				echo makeInputsFromParams($arParams["PARENT_PARAMS"]);
				echo bitrix_sessid_post();?>
				<div class="form blog-comment-fields">
					<?
					if(empty($arResult["User"]))
					{
						?>
						<div class="blog-comment-field blog-comment-field-user">
							<div class="row form">
								<div class="col-md-6 col-sm-6">
									<div class="form-group animated-labels <?=($_SESSION["blog_user_name"] ? 'input-filed' : '');?>">
										<label for="user_name"><?=GetMessage("B_B_MS_NAME")?> <span class="required-star">*</span></label>
										<div class="input">
										<input maxlength="255" size="30" class="form-control" tabindex="3" type="text" name="user_name" id="user_name" value="<?=htmlspecialcharsEx($_SESSION["blog_user_name"])?>">
										</div>
									</div>
								</div>
								<div class="col-md-6 col-sm-6">
									<div class="form-group animated-labels <?=($_SESSION["blog_user_email"] ? 'input-filed' : '');?>">
										<label for="user_email">E-mail</label>
										<div class="input">
										<input maxlength="255" size="30" class="form-control" tabindex="4" type="text" name="user_email" id="user_email" value="<?=htmlspecialcharsEx($_SESSION["blog_user_email"])?>">
										</div>
									</div>
								</div>
							</div>
						</div>
						<?
					}
					?>
					<?if($arParams["NOT_USE_COMMENT_TITLE"] != "Y")
					{
						?>
						<div class="row form">
							<div class="col-md-12">
								<div class="form-group animated-labels">
									<label for="user_sbj"><?=GetMessage("BPC_SUBJECT")?></label>
									<div class="input">
									<input maxlength="255" size="70" class="form-control" tabindex="3" type="text" name="subject" id="user_sbj" value="">
									</div>
								</div>
							</div>
						</div>
						<?
					}?>

					<label class="rating_label"><?=GetMessage("BPC_RATING")?></label>
					<div class="votes_block nstar big with-text">
						<div class="ratings">
							<div class="inner_rating">
								<?for($i=1;$i<=5;$i++):?>
									<div class="item-rating" data-message="<?=GetMessage('RATING_MESSAGE_'.$i)?>"><?=CMax::showIconSvg("star", SITE_TEMPLATE_PATH."/images/svg/star_lg.svg");?></div>
								<?endfor;?>
							</div>
						</div>
						<div class="rating_message muted" data-message="<?=GetMessage('RATING_MESSAGE_0')?>"><?=GetMessage('RATING_MESSAGE_0')?></div>
						<input class="hidden" name="rating">
					</div>

					<div class="row form virtues">
						<div class="col-md-12">
							<div class="form-group animated-labels">
								<label for="virtues"><?=GetMessage("BPC_VIRTUES")?></label>
								<div class="input">
								<textarea rows="3" class="form-control" tabindex="3" name="virtues" id="virtues" value=""></textarea>
								</div>
							</div>
						</div>
					</div>

					<div class="row form limitations">
						<div class="col-md-12">
							<div class="form-group animated-labels">
								<label for="limitations"><?=GetMessage("BPC_LIMITATIONS")?></label>
								<div class="input">
								<textarea rows="3" class="form-control" tabindex="3" name="limitations" id="limitations" value=""></textarea>
								</div>
							</div>
						</div>
					</div>

					<div class="row form comment">
						<div class="col-md-12">
							<div class="form-group animated-labels">
								<label for="comment"><?=GetMessage("BPC_MESSAGE")?></label>
								<div class="input">
								<textarea rows="3" class="form-control" tabindex="3" name="comment" id="comment" value=""></textarea>
								</div>
							</div>
						</div>
					</div>

					<div class="row form files">
						<div class="col-md-12">
							<div class="form-group">
								<div class="input">
								<input type="file" multiple class="form-control" tabindex="3" name="comment_images[]" id="comment_images" value="">
								</div>
							</div>
						</div>
					</div>

					<?
					//include($_SERVER["DOCUMENT_ROOT"].$templateFolder."/lhe.php");

					if($arResult["COMMENT_PROPERTIES"]["SHOW"] == "Y")
					{
						?><br /><?
						$eventHandlerID = false;
						$eventHandlerID = AddEventHandler('main', 'system.field.edit.file', array('CBlogTools', 'blogUFfileEdit'));
						foreach($arResult["COMMENT_PROPERTIES"]["DATA"] as $FIELD_NAME => $arPostField)
						{
							if($FIELD_NAME=='UF_BLOG_COMMENT_DOC')
							{
								?><a id="blog-upload-file" href="javascript:blogShowFile()"><?=GetMessage("BLOG_ADD_FILES")?></a><?
							}
							?>
							<div id="blog-comment-user-fields-<?=$FIELD_NAME?>"><?=($FIELD_NAME=='UF_BLOG_COMMENT_DOC' ? "" : $arPostField["EDIT_FORM_LABEL"].":")?>
								<?$APPLICATION->IncludeComponent(
										"bitrix:system.field.edit",
										$arPostField["USER_TYPE"]["USER_TYPE_ID"],
										array("arUserField" => $arPostField), null, array("HIDE_ICONS"=>"Y"));?>
							</div><?
						}
						if ($eventHandlerID !== false && ( intval($eventHandlerID) > 0 ))
							RemoveEventHandler('main', 'system.field.edit.file', $eventHandlerID);
					}

					if(strlen($arResult["NoCommentReason"]) > 0)
					{
						?>
						<div id="nocommentreason" style="display:none;"><?=$arResult["NoCommentReason"]?></div>
						<?
					}
					if($arResult["use_captcha"]===true)
					{
						?>
						<div class="row captcha-row form">
							<div class="col-md-6 col-sm-6 col-xs-6">
								<div class="form-group animated-labels">
									<label for="captcha_word"><?=GetMessage("B_B_MS_CAPTCHA_SYM")?> <span class="required-star">*</span></label>
									<div class="input">
										<input type="text" size="30" name="captcha_word" class="form-control" id="captcha_word" value=""  tabindex="7">
									</div>
								</div>
							</div>
							<div class="col-md-6 col-sm-6 col-xs-6">
								<div class="form-group">
									<div class="captcha-img">
										<img src="/bitrix/tools/captcha.php?captcha_code=<?=$arResult["CaptchaCode"]?>" class="captcha_img" width="180" height="40" id="captcha" border="0" />
										<input type="hidden" name="captcha_code" id="captcha_code" value="<?=$arResult["CaptchaCode"]?>" />
										<span class="refresh captcha_reload"><a href="javascript:;" rel="nofollow"><?=GetMessage("REFRESH")?></a></span>
									</div>
								</div>
							</div>
						</div>

						<?
					}
					?>

					<div class="blog-comment-buttons-wrapper">
						<input tabindex="10" class="btn btn-default btn-lg" value="<?=GetMessage("B_B_MS_SEND")?>" type="button" name="sub-post" id="post-button" onclick="submitComment()">
					</div>
				</div>
				<input type="hidden" name="blog_upload_cid" id="upload-cid" value="">
				</form>
				</div>
			</div>
			</div>

			<?
		}

		$prevTab = 0;
		function ShowComment($comment, $tabCount=0, $tabSize=2.5, $canModerate=false, $User=Array(), $use_captcha=false, $bCanUserComment=false, $errorComment=false, $arParams = array())
		{
			$comment["urlToAuthor"] = "";
			$comment["urlToBlog"] = "";

			if($comment["SHOW_AS_HIDDEN"] == "Y" || $comment["PUBLISH_STATUS"] == BLOG_PUBLISH_STATUS_PUBLISH || $comment["SHOW_SCREENNED"] == "Y" || $comment["ID"] == "preview")
			{
				global $prevTab;
				$tabCount = IntVal($tabCount);
				$startVal = $comment['PARENT_ID'] ? 32 : 0;
				if($tabCount <= 5)
					$paddingSize = 26 * $tabCount;
				elseif($tabCount > 5 && $tabCount <= 10)
					$paddingSize = 26 * 5 + ($tabCount - 5) * 1.5;
				elseif($tabCount > 10)
					$paddingSize = 26 * 5 + 1.5 * 5 + ($tabCount-10) * 1;

				if(($tabCount+1) <= 5)
					$paddingSizeNew = 26 * ($tabCount+1);
				elseif(($tabCount+1) > 5 && ($tabCount+1) <= 10)
					$paddingSizeNew = 26 * 5 + (($tabCount+1) - 5) * 1.5;
				elseif(($tabCount+1) > 10)
					$paddingSizeNew = 26 * 5 + 1.5 * 5 + (($tabCount+1)-10) * 1;
				$paddingSizeNew -= $paddingSize;

				if($prevTab > $tabCount)
					$prevTab = $tabCount;
				if($prevTab <= 5)
					$prevPaddingSize = 26 * $prevTab;
				elseif($prevTab > 5 && $prevTab <= 10)
					$prevPaddingSize = 26 * 5 + ($prevTab - 5) * 1.5;
				elseif($prevTab > 10)
					$prevPaddingSize = 26 * 5 + 1.5 * 5 + ($prevTab-10) * 1;

					$prevTab = $tabCount;
				?>
				<a name="<?=$comment["ID"]?>"></a>
				<div class="blog-comment <?=$tabCount > 0 || $comment['PARENT_ID'] ? 'child' : 'parent bordered rounded3'?>" style="padding-left:calc(<?=$startVal?>px + <?=$paddingSize?>px);">
				<div id="blg-comment-<?=$comment["ID"]?>">
				<?

				if(isset($_SESSION['NOT_ADDED_FILES']) && $_SESSION['NOT_ADDED_FILES']['FILES'] && $_SESSION['NOT_ADDED_FILES']['ID'] == $comment["ID"]) {?>
					<div class="alert alert-danger"><?
						print_r(GetMessage('NOT_ADDED_FILES').'<br />');
						foreach($_SESSION['NOT_ADDED_FILES']['FILES'] as $fileName) {
							echo $fileName.'<br />';
						}
						unset($_SESSION['NOT_ADDED_FILES']);
					?></div>
				<?}


				if($comment["PUBLISH_STATUS"] == BLOG_PUBLISH_STATUS_PUBLISH || $comment["SHOW_SCREENNED"] == "Y" || $comment["ID"] == "preview")
				{
					$aditStyle = "";
					if($arParams["is_ajax_post"] == "Y" || $comment["NEW"] == "Y")
						$aditStyle .= " blog-comment-new";
					if($comment["AuthorIsAdmin"] == "Y")
						$aditStyle = " blog-comment-admin";
					if(IntVal($comment["AUTHOR_ID"]) > 0)
						$aditStyle .= " blog-comment-user-".IntVal($comment["AUTHOR_ID"]);
					if($comment["AuthorIsPostAuthor"] == "Y")
						$aditStyle .= " blog-comment-author";
					if($comment["PUBLISH_STATUS"] != BLOG_PUBLISH_STATUS_PUBLISH && $comment["ID"] != "preview")
						$aditStyle .= " blog-comment-hidden";
					if($comment["ID"] == "preview")
						$aditStyle .= " blog-comment-preview";
					?>
					<div class="blog-comment-cont colored_theme_bg_before<?=$aditStyle?>">
					<div class="blog-comment-cont-white">
					<div class="blog-comment-info">
						<?if($tabCount > 0 || $comment['PARENT_ID']){
							print_r(CMax::showIconSvg("arrow_answer", SITE_TEMPLATE_PATH."/images/svg/arrow_answer.svg"));
						}?>

						<div class="left_info">
							<?
							if (COption::GetOptionString("blog", "allow_alias", "Y") == "Y" && (strlen($comment["urlToBlog"]) > 0 || strlen($comment["urlToAuthor"]) > 0) && array_key_exists("ALIAS", $comment["BlogUser"]) && strlen($comment["BlogUser"]["ALIAS"]) > 0)
								$arTmpUser = array(
									"NAME" => "",
									"LAST_NAME" => "",
									"SECOND_NAME" => "",
									"LOGIN" => "",
									"NAME_LIST_FORMATTED" => $comment["BlogUser"]["~ALIAS"],
								);
							elseif (strlen($comment["urlToBlog"]) > 0 || strlen($comment["urlToAuthor"]) > 0)
								$arTmpUser = array(
									"NAME" => $comment["arUser"]["~NAME"],
									"LAST_NAME" => $comment["arUser"]["~LAST_NAME"],
									"SECOND_NAME" => $comment["arUser"]["~SECOND_NAME"],
									"LOGIN" => $comment["arUser"]["~LOGIN"],
									"NAME_LIST_FORMATTED" => "",
								);

							if(strlen($comment["urlToBlog"])>0)
							{
								?>
								<div class="blog-author">
								<?

								$GLOBALS["APPLICATION"]->IncludeComponent("bitrix:main.user.link",
									'',
									array(
										"ID" => $comment["arUser"]["ID"],
										"HTML_ID" => "blog_post_comment_".$comment["arUser"]["ID"],
										"NAME" => $arTmpUser["NAME"],
										"LAST_NAME" => $arTmpUser["LAST_NAME"],
										"SECOND_NAME" => $arTmpUser["SECOND_NAME"],
										"LOGIN" => $arTmpUser["LOGIN"],
										"NAME_LIST_FORMATTED" => $arTmpUser["NAME_LIST_FORMATTED"],
										"USE_THUMBNAIL_LIST" => "N",
										"PROFILE_URL" => $comment["urlToAuthor"],
										"PROFILE_URL_LIST" => $comment["urlToBlog"],
										"PATH_TO_SONET_MESSAGES_CHAT" => $arParams["~PATH_TO_MESSAGES_CHAT"],
										"PATH_TO_VIDEO_CALL" => $arParams["~PATH_TO_VIDEO_CALL"],
										"DATE_TIME_FORMAT" => $arParams["DATE_TIME_FORMAT"],
										"SHOW_YEAR" => $arParams["SHOW_YEAR"],
										"CACHE_TYPE" => $arParams["CACHE_TYPE"],
										"CACHE_TIME" => $arParams["CACHE_TIME"],
										"NAME_TEMPLATE" => $arParams["NAME_TEMPLATE"],
										"SHOW_LOGIN" => $arParams["SHOW_LOGIN"],
										"PATH_TO_CONPANY_DEPARTMENT" => $arParams["~PATH_TO_CONPANY_DEPARTMENT"],
										"PATH_TO_SONET_USER_PROFILE" => ($arParams["USE_SOCNET"] == "Y" ? $comment["urlToAuthor"] : $arParams["~PATH_TO_SONET_USER_PROFILE"]),
										"INLINE" => "Y",
										"SEO_USER" => $arParams["SEO_USER"],
									),
									false,
									array("HIDE_ICONS" => "Y")
								);
								?>
								</div>
								<?
							}
							elseif(strlen($comment["urlToAuthor"])>0)
							{
								?><div class="blog-author">
								<?if($arParams["SEO_USER"] == "Y"):?>
									<noindex>
								<?endif;?>
								<?
								$GLOBALS["APPLICATION"]->IncludeComponent("bitrix:main.user.link",
									'',
									array(
										"ID" => $comment["arUser"]["ID"],
										"HTML_ID" => "blog_post_comment_".$comment["arUser"]["ID"],
										"NAME" => $arTmpUser["NAME"],
										"LAST_NAME" => $arTmpUser["LAST_NAME"],
										"SECOND_NAME" => $arTmpUser["SECOND_NAME"],
										"LOGIN" => $arTmpUser["LOGIN"],
										"NAME_LIST_FORMATTED" => $arTmpUser["NAME_LIST_FORMATTED"],
										"USE_THUMBNAIL_LIST" => "N",
										"PROFILE_URL" => $comment["urlToAuthor"],
										"PATH_TO_SONET_MESSAGES_CHAT" => $arParams["~PATH_TO_MESSAGES_CHAT"],
										"PATH_TO_VIDEO_CALL" => $arParams["~PATH_TO_VIDEO_CALL"],
										"DATE_TIME_FORMAT" => $arParams["DATE_TIME_FORMAT"],
										"SHOW_YEAR" => $arParams["SHOW_YEAR"],
										"CACHE_TYPE" => $arParams["CACHE_TYPE"],
										"CACHE_TIME" => $arParams["CACHE_TIME"],
										"NAME_TEMPLATE" => $arParams["NAME_TEMPLATE"],
										"SHOW_LOGIN" => $arParams["SHOW_LOGIN"],
										"PATH_TO_CONPANY_DEPARTMENT" => $arParams["~PATH_TO_CONPANY_DEPARTMENT"],
										"PATH_TO_SONET_USER_PROFILE" => ($arParams["USE_SOCNET"] == "Y" ? $comment["urlToAuthor"] : $arParams["~PATH_TO_SONET_USER_PROFILE"]),
										"INLINE" => "Y",
										"SEO_USER" => $arParams["SEO_USER"],
									),
									false,
									array("HIDE_ICONS" => "Y")
								);
								?>
								<?if($arParams["SEO_USER"] == "Y"):?>
									</noindex>
								<?endif;?>
								</div>
								<?
							}
							else
							{
								?>
								<div class="blog-author"><?=$comment["AuthorName"]?></div>
								<?
							}

							if(strlen($comment["urlToDelete"])>0 && strlen($comment["AuthorEmail"])>0)
							{
								?>
								(<a href="mailto:<?=$comment["AuthorEmail"]?>"><?=$comment["AuthorEmail"]?></a>)
								<?
							}

							?>
							<div class="blog-comment-date"><?=$comment["DateFormated"]?></div>
						</div>

						<div class="blog-info__rating">
							<div class="votes_block nstar big with-text">
								<div class="ratings">
									<div class="inner_rating">
										<?for($i=1;$i<=5;$i++):?>
											<div class="item-rating <?=$i<=$comment['UF_ASPRO_COM_RATING'] ? 'filed' : ''?>"><?=CMax::showIconSvg("star", SITE_TEMPLATE_PATH."/images/svg/star.svg");?></div>
										<?endfor;?>
									</div>
								</div>
							</div>
						</div>
					</div>
					<div class="blog-clear-float"></div>
					<div class="blog-comment-content">
						<?if(strlen($comment["TitleFormated"])>0)
						{
							?>
							<b><?=$comment["TitleFormated"]?></b><br />
							<?
						}
						?>

						<?if(isset($comment["TEXT"]['TYPE']) && $comment["TEXT"]['TYPE'] == 'PARENT'):?>
							<?if($comment["TEXT"]['VIRTUES']):?>
								<div class="comment-text__title">
									<?=GetMessage('BPC_VIRTUES')?>
								</div>
								<div class="comment-text__text VIRTUES">
									<?=$comment["TEXT"]['VIRTUES']?>
								</div>
							<?endif;?>
							<?if($comment["TEXT"]['LIMITATIONS']):?>
								<div class="comment-text__title">
									<?=GetMessage('BPC_LIMITATIONS')?>
								</div>
								<div class="comment-text__text LIMITATIONS">
									<?=$comment["TEXT"]['LIMITATIONS']?>
								</div>
							<?endif;?>
							<?if($comment["TEXT"]['COMMENT']):?>
								<?if($tabCount == 0): //only for parents?>
									<div class="comment-text__title">
										<?=GetMessage('BPC_MESSAGE')?>
									</div>
								<?endif;?>
								<div class="comment-text__text COMMENT">
									<?=$comment["TEXT"]['COMMENT']?>
								</div>
							<?endif;?>
						<?else:?>
							<?if($comment["~POST_TEXT"]):?>
								<?
								$pattern = '/<comment>(.*?)<\/comment>/s';
								preg_match($pattern, $comment["~POST_TEXT"], $matches);
								$commentText = $matches[1];
								?>
								<div class="comment-text__text COMMENT">
									<?=$commentText?>
								</div>
							<?endif;?>
						<?endif;?>

						<?if($comment['IMAGES']):?>

							<div class="comment-image__wrapper">

								<?foreach ($comment['IMAGES'] as $arImg):?>
									<?if($arImg['FILE_ID']):?>
										<?$smalImage = CFile::ResizeImageGet($arImg['FILE_ID'], array("width"=>120, "height"=>120), BX_RESIZE_IMAGE_PROPORTIONAL_ALT)['src'];?>
										<a class="comment-image fancy" data-fancybox="<?='comment-'.$comment['ID'].'gallery'?>" href="<?=CFile::GetPath($arImg['FILE_ID'])?>" style="background-image: url(<?=$smalImage?>);background-size: cover;"></a>
									<?endif;?>
								<?endforeach;?>

							</div>

						<?endif;?>

						<?
						if(!empty($arParams["arImages"][$comment["ID"]]))
						{
							?>
							<div class="feed-com-files">
								<div class="feed-com-files-title"><?=GetMessage("BLOG_PHOTO")?></div>
								<div class="feed-com-files-cont">
									<?
									foreach($arParams["arImages"][$comment["ID"]] as $val)
									{
										?><span class="feed-com-files-photo"><img src="<?=$val["small"]?>" alt="" border="0" data-bx-image="<?=$val["full"]?>"></span><?
									}
									?>
								</div>
							</div>
							<?
						}

						if($comment["COMMENT_PROPERTIES"]["SHOW"] == "Y")
						{
							$eventHandlerID = AddEventHandler('main', 'system.field.view.file', Array('CBlogTools', 'blogUFfileShow'));
							?><div><?
							foreach ($comment["COMMENT_PROPERTIES"]["DATA"] as $FIELD_NAME => $arPostField)
							{
								if(!empty($arPostField["VALUE"]))
								{
									$GLOBALS["APPLICATION"]->IncludeComponent(
										"bitrix:system.field.view",
										$arPostField["USER_TYPE"]["USER_TYPE_ID"],
										array("arUserField" => $arPostField), null, array("HIDE_ICONS"=>"Y"));
								}
							}
							?></div><?
							if ($eventHandlerID !== false && ( intval($eventHandlerID) > 0 ))
								RemoveEventHandler('main', 'system.field.view.file', $eventHandlerID);
						}
						?>
						<div class="blog-comment-meta">
						<?
						if($bCanUserComment===true)
						{
							?>
							<span class="blog-comment-answer"><a href="javascript:void(0)" class="muted" onclick="answerComment('<?=$comment["ID"]?>', this)"><?=GetMessage("B_B_MS_REPLY")?></a></span>
							<span class="blog-vert-separator"></span>
							<?
						}

						?>
						<?/*<span class="blog-comment-link"><a href="#<?=$comment["ID"]?>"><?=GetMessage("B_B_MS_LINK")?></a></span>*/?>
						<?
						if($comment["CAN_EDIT"] == "Y")
						{
							?>
							<script>
								top.text<?=$comment["ID"]?> = text<?=$comment["ID"]?> = '<?=CUtil::JSEscape($comment["~POST_TEXT"])?>';
								top.title<?=$comment["ID"]?> = title<?=$comment["ID"]?> = '<?=CUtil::JSEscape($comment["TITLE"])?>';
							</script>
							<span class="blog-vert-separator"></span>
							<span class="blog-comment-edit font_upper"><a href="javascript:void(0)" class="muted" onclick="return editComment('<?=$comment["ID"]?>')"><?=GetMessage("BPC_MES_EDIT")?></a></span>
							<?
						}
						if(strlen($comment["urlToShow"])>0)
						{
							?>
							<span class="blog-vert-separator"></span>
							<span class="blog-comment-show font_upper">
								<?if($arParams["AJAX_POST"] == "Y"):?>
									<a href="javascript:void(0)" class="muted" onclick="return hideShowComment('<?=$comment["urlToShow"]."&".bitrix_sessid_get()?>', '<?=$comment["ID"]?>');" title="<?=GetMessage("BPC_MES_SHOW")?>">
								<?else:?>
									<a href="<?=$comment["urlToShow"]."&".bitrix_sessid_get()?>" class="muted" title="<?=GetMessage("BPC_MES_SHOW")?>">
								<?endif;?>
								<?=GetMessage("BPC_MES_SHOW")?></a></span>
							<?
						}
						if(strlen($comment["urlToHide"])>0)
						{
							?>
							<span class="blog-vert-separator"></span>
							<span class="blog-comment-show font_upper">
								<?if($arParams["AJAX_POST"] == "Y"):?>
									<a href="javascript:void(0)" class="muted" onclick="return hideShowComment('<?=$comment["urlToHide"]."&".bitrix_sessid_get()?>&IBLOCK_ID=<?=$_REQUEST["IBLOCK_ID"]?>&ELEMENT_ID=<?=$_REQUEST["ELEMENT_ID"]?>', '<?=$comment["ID"]?>');" title="<?=GetMessage("BPC_MES_HIDE")?>">
								<?else:?>
									<a href="<?=$comment["urlToHide"]."&".bitrix_sessid_get()?>&IBLOCK_ID=<?=$_REQUEST["IBLOCK_ID"]?>&ELEMENT_ID=<?=$_REQUEST["ELEMENT_ID"]?>" class="muted" title="<?=GetMessage("BPC_MES_HIDE")?>">
								<?endif;?>
								<?=GetMessage("BPC_MES_HIDE")?></a></span>
							<?
						}
						if(strlen($comment["urlToDelete"])>0)
						{
							?>
							<span class="blog-vert-separator"></span>
							<span class="blog-comment-delete font_upper">
								<?if($arParams["AJAX_POST"] == "Y"):?>
									<a href="javascript:void(0)" class="muted" onclick="if(confirm('<?=GetMessage("BPC_MES_DELETE_POST_CONFIRM")?>')) deleteComment('<?=$comment["urlToDelete"]."&".bitrix_sessid_get()?>&IBLOCK_ID=<?=$_REQUEST["IBLOCK_ID"]?>&ELEMENT_ID=<?=$_REQUEST["ELEMENT_ID"]?>', '<?=$comment["ID"]?>');" title="<?=GetMessage("BPC_MES_DELETE")?>">
								<?else:?>
									<a href="javascript:if(confirm('<?=GetMessage("BPC_MES_DELETE_POST_CONFIRM")?>')) window.location='<?=$comment["urlToDelete"]."&".bitrix_sessid_get()?>&IBLOCK_ID=<?=$_REQUEST["IBLOCK_ID"]?>&ELEMENT_ID=<?=$_REQUEST["ELEMENT_ID"]?>'" class="muted" title="<?=GetMessage("BPC_MES_DELETE")?>">
								<?endif;?>
								<?=GetMessage("BPC_MES_DELETE")?></a></span>
							<?
						}
						if(strlen($comment["urlToSpam"])>0)
						{
							?>
							<span class="blog-vert-separator"></span>
							<span class="blog-comment-delete blog-comment-spam font_upper"><a href="<?=$comment["urlToSpam"]?>" class="muted" title="<?=GetMessage("BPC_MES_SPAM_TITLE")?>"><?=GetMessage("BPC_MES_SPAM")?></a></span>
							<?
						}
						if ($arParams["SHOW_RATING"] == "Y")
						{
							?>
							<span class="rating_vote_text pull-right" style="display:inline-block !important;">
								<?include('like.php');?>
							</span>
							<?
						}
						?>
						</div>

					</div>
					</div>
					</div>
						<div class="blog-clear-float"></div>

					<?
					if(strlen($errorComment) <= 0 && (strlen($_POST["preview"]) > 0 && $_POST["show_preview"] != "N") && (IntVal($_POST["parentId"]) > 0 || IntVal($_POST["edit_id"]) > 0)
						&& ( (IntVal($_POST["parentId"])==$comment["ID"] && IntVal($_POST["edit_id"]) <= 0)
							|| (IntVal($_POST["edit_id"]) > 0 && IntVal($_POST["edit_id"]) == $comment["ID"] && $comment["CAN_EDIT"] == "Y")))
					{
						$level = 0;
						$commentPreview = Array(
								"ID" => "preview",
								"TitleFormated" => htmlspecialcharsEx($_POST["subject"]),
								"TextFormated" => $_POST["commentFormated"],
								"AuthorName" => $User["NAME"],
								"DATE_CREATE" => GetMessage("B_B_MS_PREVIEW_TITLE"),
							);
						ShowComment($commentPreview, (IntVal($_POST["edit_id"]) == $comment["ID"] && $comment["CAN_EDIT"] == "Y") ? $level : ($level+1), 2.5, false, Array(), false, false, false, $arParams);
					}

					if(strlen($errorComment)>0 && $bCanUserComment===true
						&& (IntVal($_POST["parentId"])==$comment["ID"] || IntVal($_POST["edit_id"]) == $comment["ID"]))
					{
						?>
						<div class="alert alert-dangerblog-note-box blog-note-error">
							<div class="blog-error-text">
								<?=$errorComment?>
							</div>
						</div>
						<?
					}
					?>
					</div>


					<div id="err_comment_<?=$comment['ID']?>"></div>
					<div id="form_comment_<?=$comment['ID']?>"></div>
					<div id="new_comment_cont_<?=$comment['ID']?>" style="margin-left: -7px;"></div>
					<div id="new_comment_<?=$comment['ID']?>" style="display:none;"></div>
					<?
					if((strlen($errorComment) > 0 || strlen($_POST["preview"]) > 0)
						&& (IntVal($_POST["parentId"])==$comment["ID"] || IntVal($_POST["edit_id"]) == $comment["ID"])
						&& $bCanUserComment===true)
					{
						?>
						<script>
						top.text<?=$comment["ID"]?> = text<?=$comment["ID"]?> = '<?=CUtil::JSEscape($_POST["comment"])?>';
						top.title<?=$comment["ID"]?> = title<?=$comment["ID"]?> = '<?=CUtil::JSEscape($_POST["subject"])?>';
						<?
						if(IntVal($_POST["edit_id"]) == $comment["ID"])
						{
							?>editComment('<?=$comment["ID"]?>');<?
						}
						else
						{
							?>showComment('<?=$comment["ID"]?>', 'Y', '<?=CUtil::JSEscape($_POST["user_name"])?>', '<?=CUtil::JSEscape($_POST["user_email"])?>', 'Y');<?
						}
						?>
						</script>
						<?
					}

				}
				elseif($comment["SHOW_AS_HIDDEN"] == "Y")
					echo "<b>".GetMessage("BPC_HIDDEN_COMMENT")."</b>";
				?>
				<?if($tabCount > 0):?>
					</div>
				<?endif;?>
				<?
			}
		}

		function RecursiveComments($sArray, $key, $level=0, $first=false, $canModerate=false, $User, $use_captcha, $bCanUserComment, $errorComment, $arSumComments, $arParams)
		{
			if(!empty($sArray[$key]))
			{
				foreach($sArray[$key] as $comment)
				{
					if(!empty($arSumComments[$comment["ID"]]))
					{
						$comment["CAN_EDIT"] = $arSumComments[$comment["ID"]]["CAN_EDIT"];
						$comment["SHOW_AS_HIDDEN"] = $arSumComments[$comment["ID"]]["SHOW_AS_HIDDEN"];
						$comment["SHOW_SCREENNED"] = $arSumComments[$comment["ID"]]["SHOW_SCREENNED"];
						$comment["NEW"] = $arSumComments[$comment["ID"]]["NEW"];
					}
					ShowComment($comment, $level, 2.5, $canModerate, $User, $use_captcha, $bCanUserComment, $errorComment, $arParams);
					if(!empty($sArray[$comment["ID"]]))
					{
						foreach($sArray[$comment["ID"]] as $key1)
						{
							if(!empty($arSumComments[$key1["ID"]]))
							{
								$key1["CAN_EDIT"] = $arSumComments[$key1["ID"]]["CAN_EDIT"];
								$key1["SHOW_AS_HIDDEN"] = $arSumComments[$key1["ID"]]["SHOW_AS_HIDDEN"];
								$key1["SHOW_SCREENNED"] = $arSumComments[$key1["ID"]]["SHOW_SCREENNED"];
								$key1["NEW"] = $arSumComments[$key1["ID"]]["NEW"];
							}
							ShowComment($key1, ($level+1), 2.5, $canModerate, $User, $use_captcha, $bCanUserComment, $errorComment, $arParams);

							if(!empty($sArray[$key1["ID"]]))
							{
								RecursiveComments($sArray, $key1["ID"], ($level+2), false, $canModerate, $User, $use_captcha, $bCanUserComment, $errorComment, $arSumComments, $arParams);
							}
						}
					}
					if($first)
						$level=0;

					if($level == 0):?>
						</div>
					<?endif;
				}?>
				<?
			}
		}
		?>
		<?
		if($arResult["is_ajax_post"] != "Y")
		{
			if($arResult["CanUserComment"])
			{
				$postTitle = "";
				if($arParams["NOT_USE_COMMENT_TITLE"] != "Y")
					$postTitle = "RE: ".CUtil::JSEscape($arResult["Post"]["TITLE"]);
				?>
				<div class="blog-add-comment"><a class="btn btn-lg btn-transparent-border-color white" href="javascript:void(0)"><?=GetMessage("B_B_MS_ADD_COMMENT")?></a></div>
				<a name="0"></a>
				<?
				if(strlen($arResult["COMMENT_ERROR"]) > 0 && strlen($_POST["parentId"]) < 2
					&& IntVal($_POST["parentId"])==0 && IntVal($_POST["edit_id"]) <= 0)
				{
					?>
					<div class="alert alert-danger blog-note-box blog-note-error">
						<div class="blog-error-text"><?=$arResult["COMMENT_ERROR"]?></div>
					</div>
					<?
				}
			}

			if($arResult["CanUserComment"])
			{
				?>

				<div id="form_comment_0">
					<div id="err_comment_0"></div>
					<div id="form_comment_0"></div>
					<div id="new_comment_0" style="display:none;"></div>
				</div>

				<?include_once('sort.php');?>


				<div id="new_comment_cont_0"></div>

				<?
				if((strlen($arResult["COMMENT_ERROR"])>0 || strlen($_POST["preview"]) > 0)
					&& IntVal($_POST["parentId"]) == 0 && strlen($_POST["parentId"]) < 2 && IntVal($_POST["edit_id"]) <= 0)
				{
					?>
					<script>
					top.text0 = text0 = '<?=CUtil::JSEscape($_POST["comment"])?>';
					top.title0 = title0 = '<?=CUtil::JSEscape($_POST["subject"])?>';
					showComment('0', 'Y', '<?=CUtil::JSEscape($_POST["user_name"])?>', '<?=CUtil::JSEscape($_POST["user_email"])?>', 'Y');
					</script>
					<?
				}
			}
		}

		$arParams["RATING"] = $arResult["RATING"];
		$arParams["component"] = $component;
		$arParams["arImages"] = $arResult["arImages"];
		if($arResult["is_ajax_post"] == "Y")
			$arParams["is_ajax_post"] = "Y";

		if($arResult["is_ajax_post"] != "Y" && $arResult["NEED_NAV"] == "Y")
		{
			for($i = 1; $i <= $arResult["PAGE_COUNT"]; $i++)
			{
				$tmp = $arResult["CommentsResult"];
				$tmp[0] = $arResult["PagesComment"][$i];
				?>
					<div id="blog-comment-page-<?=$i?>"<?if($arResult["PAGE"] != $i) echo "style=\"display:none;\""?>><?RecursiveComments($tmp, $arResult["firstLevel"], 0, true, $arResult["canModerate"], $arResult["User"], $arResult["use_captcha"], $arResult["CanUserComment"], $arResult["COMMENT_ERROR"], $arResult["Comments"], $arParams);?></div>
				<?
			}
		}
		else {
			if(!$arResult["CommentsResult"][0] && !$arResult["ajax_comment"] && !strlen($arResult["COMMENT_ERROR"])):?>
				<div class="rounded3 bordered alert-empty">
					<?=GetMessage('EMPTY_REVIEWS')?>
				</div>
				<script>
					var comments = $('.EXTENDED .blog-comments');
					if(comments.length) {
						comments.addClass('empty-reviews');
					}
				</script>
			<?endif;
			RecursiveComments($arResult["CommentsResult"], $arResult["firstLevel"], 0, true, $arResult["canModerate"], $arResult["User"], $arResult["use_captcha"], $arResult["CanUserComment"], $arResult["COMMENT_ERROR"], $arResult["Comments"], $arParams);
		}

		if($arResult["is_ajax_post"] != "Y")
		{
			if($arResult["NEED_NAV"] == "Y")
			{
				?>
				<div class="blog-comment-nav">
					<?
					for($i = 1; $i <= $arResult["PAGE_COUNT"]; $i++)
					{
						$style = "blog-comment-nav-item";
						if($i == $arResult["PAGE"])
							$style .= " blog-comment-nav-item-sel colored_theme_bg";
						?><a class="<?=$style?>" href="<?=$arResult["NEW_PAGES"][$i]?>" onclick="return bcNav('<?=$i?>', this)" id="blog-comment-nav-b<?=$i?>"><?=$i?></a><?
					}
				?>
				</div>
				<?
			}
		}
	}
}
?>
</div>
<?
if($arResult["is_ajax_post"] == "Y")
	die();

function makeInputsFromParams($arParams, $name="PARAMS")
{
	$result = "";

	if(is_array($arParams))
	{
		foreach ($arParams as $key => $value)
		{
			if(substr($key, 0, 1) != "~")
			{
				$inputName = $name.'['.$key.']';

				if(is_array($value))
					$result .= makeInputsFromParams($value, $inputName);
				else
					$result .= '<input type="hidden" name="'.$inputName.'" value="'.$value.'">'.PHP_EOL;
			}
		}
	}

	return $result;
}
?>