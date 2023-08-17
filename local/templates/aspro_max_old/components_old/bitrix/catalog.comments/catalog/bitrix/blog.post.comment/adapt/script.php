<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>

<script>

var rating_value = <?=CUtil::PhpToJSObject($arResult['ALL_RATING_VALUE'])?>;
if(!isNaN(rating_value) && rating_value !== undefined && rating_value) {
	var rating_wrapper = $('.right_reviews_info .rating-wrapper');
	rating_value = (+rating_value).toFixed(1);
	rating_wrapper.find('.rating-value .count').text(rating_value);
	rating_value = Math.round(rating_value);

	var maximum = $('.tab-pane.reviews.EXTENDED .rating-value .maximum_value');
	if(maximum.length) {
		maximum.text('/5');
	}

	var stars = rating_wrapper.find('.item-rating');
	if(stars.length) {
		for (var i = 0; i < rating_value; i++) {
			$(stars[i]).addClass('filed');
		}
	}

	rating_wrapper.css('display', '');
}

var reviews_count = <?=CUtil::PhpToJSObject($arResult['REVIEWS_COUNT'])?>;
if(!isNaN(reviews_count) && reviews_count !== undefined && reviews_count) {
	$('.EXTENDED .element-count-wrapper .element-count').text(reviews_count).css('display', '');
}

<?if($arResult['REVIEWS_COUNT']):?>
	var tabReviews = $('a[href="#reviews"]');
	tabReviews.each(function(){
		if(!$(this).hasClass('counted')) {
			$(this).text( $(this).text()+'(<?=$arResult['REVIEWS_COUNT']?>)' ).addClass('counted');
		}
	});
<?endif;?>

function onLightEditorShow(content)
{
	if (!window.oBlogComLHE)
		return BX.addCustomEvent(window, 'LHE_OnInit', function(){setTimeout(function(){onLightEditorShow(content);},	500);});

	oBlogComLHE.SetContent(content || '');
	oBlogComLHE.CreateFrame(); // We need to recreate editable frame after reappending editor container
	oBlogComLHE.SetEditorContent(oBlogComLHE.content);
	oBlogComLHE.SetFocus();
}

function answerComment(key, el) {
	var _this = $(el);
	var comment = $('#form_comment_' + key);
	if(_this.hasClass('clicked')) {
		comment.slideUp();
		_this.removeClass('clicked');
	} else {
		if(comment.children().length) {
			comment.slideDown();
		} else {
			showComment(key);
		}
		_this.addClass('clicked');
	}
}

function showComment(key, error, userName, userEmail, needData)
{
	subject = '';
	comment = '';

	if(needData == "Y")
	{
		subject = window["title"+key];
		comment = window["text"+key];
	}

	var pFormCont = BX('form_c_del');
	if(!pFormCont){
		return;
	}

	clearForm(pFormCont);

	if(BX.hasClass(pFormCont, 'edit-form')) {
		BX.removeClass(pFormCont, 'edit-form');
	}
	BX('form_comment_' + key).appendChild(pFormCont); // Move form
	fileInputInit("<?=GetMessage('INPUT_FILE_DEFAULT')?>");

	pFormCont.style.display = "block";

	document.form_comment.parentId.value = key;
	document.form_comment.edit_id.value = '';
	document.form_comment.act.value = 'add';
	document.form_comment.post.value = '<?=GetMessageJS("B_B_MS_SEND")?>';
	document.form_comment.action = document.form_comment.action + "#" + key;

	if(error == "Y")
	{
		if(comment.length > 0)
		{
			comment = comment.replace(/\/</gi, '<');
			comment = comment.replace(/\/>/gi, '>');
		}
		if(userName.length > 0)
		{
			userName = userName.replace(/\/</gi, '<');
			userName = userName.replace(/\/>/gi, '>');
			document.form_comment.user_name.value = userName;
		}
		if(userEmail.length > 0)
		{
			userEmail = userEmail.replace(/\/</gi, '<');
			userEmail = userEmail.replace(/\/>/gi, '>');
			document.form_comment.user_email.value = userEmail;
		}
		if(subject && subject.length>0 && document.form_comment.subject)
		{
			subject = subject.replace(/\/</gi, '<');
			subject = subject.replace(/\/>/gi, '>');
			document.form_comment.subject.value = subject;
		}
	}

	files = BX('form_comment')["UF_BLOG_COMMENT_DOC[]"];
	if(files !== null && typeof files != 'undefined')
	{
		if(!files.length)
		{
			BX.remove(files);
		}
		else
		{
			for(i = 0; i < files.length; i++)
				BX.remove(BX(files[i]));
		}
	}
	filesForm = BX.findChild(BX('blog-comment-user-fields-UF_BLOG_COMMENT_DOC'), {'className': 'file-placeholder-tbody' }, true, false);
	if(filesForm !== null && typeof filesForm != 'undefined')
		BX.cleanNode(filesForm, false);

	filesForm = BX.findChild(BX('blog-comment-user-fields-UF_BLOG_COMMENT_DOC'), {'className': 'feed-add-photo-block' }, true, true);
	if(filesForm !== null && typeof filesForm != 'undefined')

	{
		for(i = 0; i < filesForm.length; i++)
		{
			if(BX(filesForm[i]).parentNode.id != 'file-image-template')
				BX.remove(BX(filesForm[i]));
		}
	}

	filesForm = BX.findChild(BX('blog-comment-user-fields-UF_BLOG_COMMENT_DOC'), {'className': 'file-selectdialog' }, true, false);
	if(filesForm !== null && typeof filesForm != 'undefined')
	{
		BX.hide(BX.findChild(BX('blog-comment-user-fields-UF_BLOG_COMMENT_DOC'), {'className': 'file-selectdialog' }, true, false));
		BX.show(BX('blog-upload-file'));
	}

	onLightEditorShow(comment);
	return false;
}

function editComment(key)
{
	subject = window["title"+key];
	comment = window["text"+key];

	if(comment.length > 0)
	{
		comment = comment.replace(/\/</gi, '<');
		comment = comment.replace(/\/>/gi, '>');
	}

	var pFormCont = BX('form_c_del');
	var parent = BX.findParent(BX('form_comment_' + key), {"class" : "blog-comment"});
	var commentBlock = BX('blg-comment-'+key);

	if(commentBlock === null) {
		if( BX('blg-comment-'+key+'old') !== null ) {
			$('#blg-comment-'+key+'old').attr('id', 'blg-comment-'+key);
			commentBlock = BX('blg-comment-'+key);
		}
	}

	if(BX.hasClass(parent, 'parent')) {
		BX.addClass(pFormCont, 'edit-form');
		updateEditForm(commentBlock, pFormCont, true);
	} else if(BX.hasClass(pFormCont, 'edit-form')) {
		BX.removeClass(pFormCont, 'edit-form');
		updateEditForm(commentBlock, pFormCont);
	} else {
		updateEditForm(commentBlock, pFormCont);
	}

	BX('form_comment_' + key).appendChild(pFormCont); // Move form
	fileInputInit("<?=GetMessage('INPUT_FILE_DEFAULT')?>");
	pFormCont.style.display = "block";

	files = BX('form_comment')["UF_BLOG_COMMENT_DOC[]"];
	if(files !== null && typeof files != 'undefined')
	{
		if(!files.length)
		{
			BX.remove(files);
		}
		else
		{
			for(i = 0; i < files.length; i++)
				BX.remove(BX(files[i]));
		}
	}
	filesForm = BX.findChild(BX('blog-comment-user-fields-UF_BLOG_COMMENT_DOC'), {'className': 'file-placeholder-tbody' }, true, false);
	if(filesForm !== null && typeof filesForm != 'undefined')
		BX.cleanNode(filesForm, false);

	filesForm = BX.findChild(BX('blog-comment-user-fields-UF_BLOG_COMMENT_DOC'), {'className': 'feed-add-photo-block' }, true, true);
	if(filesForm !== null && typeof filesForm != 'undefined')

	{
		for(i = 0; i < filesForm.length; i++)
		{
			if(BX(filesForm[i]).parentNode.id != 'file-image-template')
				BX.remove(BX(filesForm[i]));
		}
	}

	filesForm = BX.findChild(BX('blog-comment-user-fields-UF_BLOG_COMMENT_DOC'), {'className': 'file-selectdialog' }, true, false);
	if(filesForm !== null && typeof filesForm != 'undefined')
	{
		BX.hide(BX.findChild(BX('blog-comment-user-fields-UF_BLOG_COMMENT_DOC'), {'className': 'file-selectdialog' }, true, false));
		BX.show(BX('blog-upload-file'));
	}

	onLightEditorShow(comment);

	document.form_comment.parentId.value = '';
	document.form_comment.edit_id.value = key;
	document.form_comment.act.value = 'edit';
	document.form_comment.post.value = '<?=GetMessageJS("B_B_MS_SAVE")?>';
	document.form_comment.action = document.form_comment.action + "#" + key;

	if(subject && subject.length > 0 && document.form_comment.subject)
	{
		subject = subject.replace(/\/</gi, '<');
		subject = subject.replace(/\/>/gi, '>');
		document.form_comment.subject.value = subject;
	}
	return false;
}

function waitResult(id)
{
	r = 'new_comment_' + id;
	ob = BX(r);
	if(ob.innerHTML.length > 0)
	{
		var obNew = BX.processHTML(ob.innerHTML, true);
		scripts = obNew.SCRIPT;
		BX.ajax.processScripts(scripts, true);
		if(window.commentEr && window.commentEr == "Y")
		{
			BX('err_comment_'+id).innerHTML = ob.innerHTML;
			ob.innerHTML = '';
		}
		else
		{
			if(BX('edit_id').value > 0)
			{
				if(BX('blg-comment-'+id))
				{
					BX('blg-comment-'+id+'old').innerHTML = BX('blg-comment-'+id).innerHTML;
					BX('blg-comment-'+id+'old').id = 'blg-comment-'+id;
					if(BX.browser.IsIE()) //for IE, numbered list not rendering well
						setTimeout(function (){BX('blg-comment-'+id).innerHTML = BX('blg-comment-'+id).innerHTML}, 10);
				}
				else
				{
					BX('blg-comment-'+id+'old').innerHTML = ob.innerHTML;
					if(BX.browser.IsIE()) //for IE, numbered list not rendering well
						setTimeout(function (){BX('blg-comment-'+id+'old').innerHTML = BX('blg-comment-'+id+'old').innerHTML}, 10);

				}
			}
			else
			{
				BX('new_comment_cont_'+id).innerHTML += ob.innerHTML;
				if(BX.browser.IsIE()) //for IE, numbered list not rendering well
					setTimeout(function (){BX('new_comment_cont_'+id).innerHTML = BX('new_comment_cont_'+id).innerHTML}, 10);
			}
			ob.innerHTML = '';
		}
		window.commentEr = false;

		BX.closeWait();
		BX('post-button').disabled = false;
		BX.onCustomEvent("onIblockCatalogCommentSubmit");

		if(id == 0) {
			var comments = document.querySelector('.EXTENDED .blog-comments');
			if(comments !== null) {
				comments.classList.remove('empty-reviews');
			}

			var bError = $(comments).find('[id^=err_comment] *').length > 0;
			if( !bError ) {
				$('#form_comment_0').slideUp();
				clearForm(BX('form_c_del'));
			}
			else{
				$('#form_comment_0 [name=comment]').val(window.commentOr);
			}
		} else {
			BX('form_c_del').style.display = "none";
		}
	}
	else
		setTimeout("waitResult('"+id+"')", 500);
}

function submitComment()
{
	//oBlogComLHE.SaveContent();
	BX('post-button').focus();
	BX('post-button').disabled = true;
	obForm = BX('form_comment');
	<?
	if($arParams["AJAX_POST"] == "Y")
	{
		?>
		if(BX('edit_id').value > 0)
		{
			val = BX('edit_id').value;
			BX('blg-comment-'+val).id = 'blg-comment-'+val+'old';
		}
		else
			val = BX('parentId').value;
		id = 'new_comment_' + val;

		if(BX('err_comment_'+val))
			BX('err_comment_'+val).innerHTML = '';

		prepareFormInfo(obForm);

		BX.ajax.submitComponentForm(obForm, id);
		var comment = $(obForm).find('[name=comment]');
		setTimeout(function(){comment.css('color', '');}, 1500);

		setTimeout("waitResult('"+val+"')", 100);
		<?
	}
	?>

	var eventdata = {type: 'form_submit', form: $(obForm)};
	BX.onCustomEvent('onSubmitForm', [eventdata]);
}

function prepareFormInfo(obForm)
{
	var form = $(obForm);
	var comment = form.find('[name=comment]');
	var limitations = form.find('[name=limitations]');
	var virtues = form.find('[name=virtues]');
	var rating = form.find('[name=rating]');

	var resultCommentText = '';
	if(virtues.val()) {
		resultCommentText += '<virtues>'+virtues.val()+'</virtues>'+'\n';
	}

	if(limitations.val()) {
		resultCommentText += '<limitations>'+limitations.val()+'</limitations>'+'\n';
	}

	if(comment.val()) {
		resultCommentText += '<comment>'+comment.val()+'</comment>';
	}

	comment.css('color', '#fafafa');

	window.commentOr = comment.val();
	if(resultCommentText) {
		comment.val(resultCommentText);
	}

	var ratingVal = rating.closest('.votes_block').data('rating');
	if(ratingVal) {
		rating.val(ratingVal);
	}
}

function hideShowComment(url, id)
{
	var siteID = '<? echo SITE_ID; ?>';
	var bcn = BX('blg-comment-'+id);
	BX.showWait(bcn);
	bcn.id = 'blg-comment-'+id+'old';
	BX('err_comment_'+id).innerHTML = '';
	url += '&SITE_ID='+siteID;
	BX.ajax.get(url, function(data) {
		var obNew = BX.processHTML(data, true);
		scripts = obNew.SCRIPT;
		BX.ajax.processScripts(scripts, true);
		var nc = BX('new_comment_'+id);
		var bc = BX('blg-comment-'+id+'old');
		nc.style.display = "none";
		nc.innerHTML = data;

		if(BX('blg-comment-'+id))
		{
			bc.innerHTML = BX('blg-comment-'+id).innerHTML;
		}
		else
		{
			BX('err_comment_'+id).innerHTML = nc.innerHTML;
		}
		BX('blg-comment-'+id+'old').id = 'blg-comment-'+id;

		BX.closeWait();
	});

	return false;
}

function deleteComment(url, id)
{
	var siteID = '<? echo SITE_ID; ?>';
	BX.showWait(BX('blg-comment-'+id));
	url += '&SITE_ID='+siteID;
	BX.ajax.get(url, function(data) {
		var obNew = BX.processHTML(data, true);
		scripts = obNew.SCRIPT;
		BX.ajax.processScripts(scripts, true);

		var nc = BX('new_comment_'+id);
		nc.style.display = "none";
		nc.innerHTML = data;

		if(BX('blg-com-err'))
		{
			BX('err_comment_'+id).innerHTML = nc.innerHTML;
		}
		else
		{
			BX('blg-comment-'+id).innerHTML = nc.innerHTML;
		}
		nc.innerHTML = '';

		BX.closeWait();
	});


	return false;
}

function updateEditForm(commentBlock, pFormCont, parent) {
	clearForm(pFormCont);
	if(parent) {
		var rating = BX.findChild(commentBlock, {"class": 'item-rating filed'}, true, true);
		rating = rating === undefined ? 0 : rating.length;

		var stars = BX.findChild(pFormCont, {"class": 'item-rating'}, true, true);
		var _this = $(stars[rating-1]),
			index = rating,
			ratingMessage = _this.data('message');

		_this.closest('.votes_block').data('rating', index);
		var ratingInput = _this.closest('.votes_block').find('input[name=RATING]');
		if(ratingInput.length){
			ratingInput.val(index);
		}else{
			_this.closest('.votes_block').find('input[data-sid=RATING]').val(index);
		}
		_this.closest('.votes_block').find('.rating_message').data('message', ratingMessage).text(ratingMessage);;

		stars.forEach(function(star, index){
			if(index < rating) {
				$(star).addClass('filed');
			} else {
				$(star).removeClass('filed');
			}
		});

		var virtues = BX.findChild(commentBlock, {"class": 'comment-text__text VIRTUES'}, true);
		if(virtues !== null) {
			virtues = virtues.innerHTML.trim();
		}
		var limitations = BX.findChild(commentBlock, {"class": 'comment-text__text LIMITATIONS'}, true);
		if(limitations !== null) {
			limitations = limitations.innerHTML.trim();
		}
		var comment = BX.findChild(commentBlock, {"class": 'comment-text__text COMMENT'}, true);
		if(comment !== null) {
			comment = comment.innerHTML.trim();
		}

		$(pFormCont).find('.form.virtues textarea').val(virtues);
		$(pFormCont).find('.form.limitations textarea').val(limitations);
		$(pFormCont).find('.form.comment textarea').val(comment);
	} else {
		var comment = BX.findChild(commentBlock, {"class": 'comment-text__text COMMENT'}, true).innerHTML.trim();
		$(pFormCont).find('.form.comment textarea').val(comment);
	}


}

function clearForm(pFormCont) {

	var stars = BX.findChild(pFormCont, {"class": 'item-rating'}, true, true);
	stars.forEach(function(star, index){
		$(stars).removeClass('filed');
	});


	var votesBlock = $(pFormCont).find('.votes_block');

	votesBlock.data('rating', '');

	if(votesBlock.find('input[name=RATING]').length){
		votesBlock.find('input[name=RATING]').val('');
	}
	else{
		votesBlock.find('input[data-sid=RATING]').val('');
	}
	votesBlock.find('.rating_message').data('message', "<?=GetMessage('RATING_MESSAGE_0')?>").text("<?=GetMessage('RATING_MESSAGE_0')?>");

	$(pFormCont).find('.form.virtues textarea').val('');
	$(pFormCont).find('.form.limitations textarea').val('');
	$(pFormCont).find('.form.comment textarea').val('');
	$(pFormCont).find('input[type=file]').val('');
	$(pFormCont).find('input[name=rating]').val('');
}

<?if($arResult["NEED_NAV"] == "Y"):?>
function bcNav(page, th)
{
	//BX.showWait(th);
	var container = $(th).closest('div[id^=comp_');
	container.addClass('blur');
	setTimeout(function() {
		for(i=1; i <= <?=$arResult["PAGE_COUNT"]?>; i++)
		{
			if(i == page)
			{
				BX.addClass(BX('blog-comment-nav-t'+i), 'blog-comment-nav-item-sel colored_theme_bg');
				BX.addClass(BX('blog-comment-nav-b'+i), 'blog-comment-nav-item-sel colored_theme_bg');
				BX('blog-comment-page-'+i).style.display = "";
			}
			else
			{
				BX.removeClass(BX('blog-comment-nav-t'+i), 'blog-comment-nav-item-sel colored_theme_bg');
				BX.removeClass(BX('blog-comment-nav-b'+i), 'blog-comment-nav-item-sel colored_theme_bg');
				BX('blog-comment-page-'+i).style.display = "none";
			}
		}
		container.removeClass('blur');
		if(typeof window['stickySidebar'] !== 'undefined')
		{
			window['stickySidebar'].updateSticky();
		}
		//BX.closeWait();
		}, 300);
	return false;
}
<?endif;?>

function blogShowFile()
{
	el = BX('blog-upload-file');
	if(el.style.display != 'none')
		BX.hide(el);
	else
		BX.show(el);
	BX.onCustomEvent(BX('blog-comment-user-fields-UF_BLOG_COMMENT_DOC'), "BFileDLoadFormController");
}

$('.blog-add-comment .btn').on('click', function(){
	if(!$(this).hasClass('clicked'))
	{
		showComment('0');
		$(this).addClass('clicked');
	}
	else
		$('#form_comment_0').slideToggle();
});

<?if(!$arResult["CanUserComment"]):?>
	$('.show-comment.btn').on('click', function(){
		$('.blog-note-error').remove();
		$('<div class="alert alert-danger blog-note-box blog-note-error"><div class="blog-error-text"><?=GetMessage('BPC_ERROR_NO_COMMENT_PERM')?></div></div>').insertBefore('#reviews_sort_continer');
	});
<?endif;?>
</script>