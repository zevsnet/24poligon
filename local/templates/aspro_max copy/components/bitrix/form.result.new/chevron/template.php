<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) {
    die();
}

use Bitrix\Main\Page\Asset;
use SB\Site\SB_CMax;
Asset::getInstance()->addJs("https://cdn.bootcss.com/dom-to-image/2.6.0/dom-to-image.min.js");
Asset::getInstance()->addJs("https://cdn.bootcss.com/FileSaver.js/2014-11-29/FileSaver.min.js");

?>

<div class="form <?= $arResult["arForm"]["SID"] ?>  ">
    <!--noindex-->
    <div class="form_head">

        <? if ($arResult["isFormDescription"] == "Y"): ?>
            <div class="form_desc"><?= $arResult["FORM_DESCRIPTION"] ?></div>
        <? endif; ?>
    </div>
    <? if (strlen($arResult["FORM_NOTE"])) { ?>
        <div class="form_result <?= ($arResult["isFormErrors"] == "Y" ? 'error' : 'success') ?>">
            <? if ($arResult["isFormErrors"] == "Y"): ?>
                <?= $arResult["FORM_ERRORS_TEXT"] ?>
            <? else: ?>
                <?= CMax::showIconSvg(' colored', SITE_TEMPLATE_PATH . '/images/svg/success.svg') ?>
                <span class="success_text">
					<? $successNoteFile = SITE_DIR . "include/form/success_{$arResult["arForm"]["SID"]}.php"; ?>
                    <? if (file_exists($_SERVER["DOCUMENT_ROOT"] . $successNoteFile)): ?>
                        <? $APPLICATION->IncludeFile($successNoteFile, array(),
                            array("MODE" => "html", "NAME" => "Form success note")); ?>
                    <? else: ?>
                        <?= GetMessage("FORM_SUCCESS"); ?>
                    <? endif; ?>
				</span>
                <script>
                    if (arMaxOptions['THEME']['USE_FORMS_GOALS'] !== 'NONE') {
                        var eventdata = {goal: 'goal_webform_success' + (arMaxOptions['THEME']['USE_FORMS_GOALS'] === 'COMMON' ? '' : '_<?=$arResult["arForm"]["ID"]?>')};
                        BX.onCustomEvent('onCounterGoals', [eventdata]);
                    }
                    $(window).scroll();
                </script>
                <div class="close-btn-wrapper">
                    <div class="btn btn-default btn-lg jqmClose"><?= GetMessage('FORM_CLOSE') ?></div>
                </div>
            <? endif; ?>
        </div>
    <? } else { ?>
        <? if ($arResult["isFormErrors"] == "Y"): ?>
            <div class="form_body error"><?= $arResult["FORM_ERRORS_TEXT"] ?></div>
        <? endif; ?>
        <?= $arResult["FORM_HEADER"];//str_replace('method="POST"','method="POST" onsubmit="return validateFormSb()"',$arResult["FORM_HEADER"])?>

        <?= bitrix_sessid_post(); ?>
        <div class="form_body">
            <? if (is_array($arResult["QUESTIONS"])): ?>
            <div class="sb_section sb_section_info" style="display: none">
                <h3>Контактная информация</h3>
                <? foreach ($arResult["QUESTIONS"] as $FIELD_SID => $arQuestion) {
                    switch ($FIELD_SID) {
                        case "SURNAME":
                            echo '<div class="sb_block_fio">';
                        case "NAME":
                        case "OTCHESTVO":
                            SB_CMax::drawFormField($FIELD_SID, $arQuestion);
                            if ($FIELD_SID == 'OTCHESTVO') {
                                echo '</div>';
                            }
                            break;
                        case 'PHONE':
                            echo '<div class="sb_block_contact">';
                        case 'EMAIL':
                            SB_CMax::drawFormField($FIELD_SID, $arQuestion);

                            if ($FIELD_SID == 'EMAIL') {
                                echo '</div>';
                            }

                            break;
                    }
                } ?>
                <div class="sb_hint">*контактная информация необходима для подтверждения заказа</div>
            </div>
            <div class="sb_section sb_section_calc">
                <h3>Нашивка именная на грудь</h3>
                <? foreach ($arResult["QUESTIONS"] as $FIELD_SID => $arQuestion): ?>
                    <? switch ($FIELD_SID) {

                        case 'BODY':
                            ?>
                            <? echo '<div class="sb_block_constructor">'; ?>
                            <div class="form-control form_text_95">
                                <label for="form_text_95">Цвет фона</label>
                                <input type="hidden" name="form_text_95" value="чёрный">
                                <select id="form_text_95" placeholder="Цвет фона" data-sid="BODY">
                                    <option value="181d23" selected>чёрный</option>
                                    <option value="191c3d">чернильный</option>
                                    <option value="0428a0">тёмно-синий</option>
                                    <option value="6e863c">олива</option>
                                    <option value="0f4336">зелёный мох</option>
                                    <option value="5d8aa8">синий мох</option>
                                </select>
                                <div class="sb_color" style="background: #181d23;"></div>

                            </div>
                            <? break;
                        case 'KANT':
                            ?>
                            <div class="form-control form_text_96">
                                <label for="form_text_96">Цвет канта</label>
                                <input type="hidden" name="form_text_96" value="желтый">
                                <select id="form_text_96" placeholder="Цвет канта" data-sid="KANT">
                                    <option value="e5e219" selected>желтый</option>
                                    <option value="bc273a">красный</option>
                                    <option value="e5e219">светло-оливковый</option>
                                    <option value="6b8e23">тёмно-оливковый</option>
                                    <option value="335ed1">синий</option>
                                    <option value="9ea6b3">серый</option>
                                    <option value="32aceb">голубой</option>
                                    <option value="8bf7e8">светло-голубой</option>
                                </select>
                                <div class="sb_color" style="background: #e5e219;"></div>

                            </div>
                            <? break;
                        case 'SUMBOL':
                            ?>
                            <div class="form-control form_text_97">
                                <label for="form_text_97">Цвет текста</label>
                                <input type="hidden" name="form_text_97" value="желтый">
                                <select id="form_text_97" placeholder="Цвет текста" data-sid="SUMBOL">
                                    <option value="e5e219" selected>желтый</option>
                                    <option value="bc273a">красный</option>
                                    <option value="e5e219">светло-оливковый</option>
                                    <option value="6b8e23">тёмно-оливковый</option>
                                    <option value="335ed1">синий</option>
                                    <option value="9ea6b3">серый</option>
                                    <option value="32aceb">голубой</option>
                                    <option value="8bf7e8">светло-голубой</option>
                                </select>
                                <div class="sb_color" style="background: #e5e219;"></div>
                            </div>
                            <? echo '</div>';
                            break;
                        case 'TEXT':
                            ?>
                            <div class="form-control">
                                <label for="form_text_98">Текст нашивки</label>
                                <input class="" type="text" placeholder="Текст" data-sid="TEXT" required=""
                                       id="form_text_98"
                                       name="form_text_98" value="" aria-required="true">
                            </div>

                            <div class="sb_block_construct">
                                <div class="sb_block_pic">
                                    <div class="sb_body">
                                        <div class="sb_text"></div>
                                    </div>
                                </div>
                                <div class="sb_section_offers">
                                    <input type="hidden" name="form_text_101" id="form_text_101" value="ВС(120*25)">
                                    <ul class="sb_ul">
                                        <li class="active">ВС(120*25)</li>
                                        <li>МЧС(120*30)</li>
                                        <li>Полиция(120*30)</li>
                                        <li>Росгвардия(120*30)</li>
                                        <li>Охрана(120*30)</li>
                                        <li>ФСИН(120*30)</li>
                                        <li>ФССП(120*30)</li>
                                    </ul>
                                </div>
                            </div>
                            <?
                            break;
                    }
                    ?>
                <? endforeach; ?>
            </div>
            <div >
                <? foreach ($arResult["QUESTIONS"] as $FIELD_SID => $arQuestion): ?>
                    <? switch ($FIELD_SID) {
                        case 'BODY':
                        case 'KANT':
                        case 'SUMBOL':
                        case 'TEXT':
                        case "SURNAME":
                        case "NAME":
                        case "OTCHESTVO":
                        case 'PHONE':
                        case 'EMAIL':
                        case 'TYPE':
                            break;
                        default:
                            SB_CMax::drawFormField($FIELD_SID, $arQuestion);
                            break;
                    }
                    ?>
                <? endforeach; ?>
                <? endif; ?>
            </div>

            <div class="clearboth"></div>

            <? $bHiddenCaptcha = (isset($arParams["HIDDEN_CAPTCHA"]) ? $arParams["HIDDEN_CAPTCHA"] : COption::GetOptionString("aspro.max",
                "HIDDEN_CAPTCHA", "Y")); ?>
            <? if ($arResult["isUseCaptcha"] == "Y"): ?>
                <div class="form-control captcha-row clearfix">
                    <label><span><?= GetMessage("FORM_CAPRCHE_TITLE") ?>&nbsp;<span class="star">*</span></span></label>
                    <div class="captcha_image">
                        <img src="/bitrix/tools/captcha.php?captcha_sid=<?= htmlspecialcharsbx($arResult["CAPTCHACode"]) ?>"
                             border="0"/>
                        <input type="hidden" name="captcha_sid"
                               value="<?= htmlspecialcharsbx($arResult["CAPTCHACode"]) ?>"/>
                        <div class="captcha_reload"></div>
                    </div>
                    <div class="captcha_input">
                        <input type="text" class="inputtext captcha" name="captcha_word" size="30" maxlength="50"
                               value="" required/>
                    </div>
                </div>
            <? elseif ($bHiddenCaptcha == "Y"): ?>
                <textarea name="nspm" style="display:none;"></textarea>
            <? endif; ?>
            <div class="clearboth"></div>
        </div>
        <div class="form_footer">
            <? $bShowLicenses = (isset($arParams["SHOW_LICENCE"]) ? $arParams["SHOW_LICENCE"] : COption::GetOptionString("aspro.max",
                "SHOW_LICENCE", "Y")); ?>
            <? if ($bShowLicenses == "Y"): ?>
                <div class="licence_block filter onoff label_block">
                    <input type="checkbox" id="licenses_popup" name="licenses_popup"
                           <?= (COption::GetOptionString("aspro.max", "LICENCE_CHECKED",
                               "N") == "Y" ? "checked" : ""); ?> required value="Y">
                    <label for="licenses_popup">
                        <? $APPLICATION->IncludeFile(SITE_DIR . "include/licenses_text.php", Array(),
                            Array("MODE" => "html", "NAME" => "LICENSES")); ?>
                    </label>
                </div>
            <? endif; ?>
            <button id="sb_submit" type="submit" class="btn btn-lg btn-default"><span><?= $arResult["arForm"]["BUTTON"] ?></span>
            </button>
            <input type="hidden" class="btn btn-default" value="<?= $arResult["arForm"]["BUTTON"] ?>"
                   name="web_form_submit">
        </div>
        <?= $arResult["FORM_FOOTER"] ?>
    <? } ?>
    <!--/noindex-->
    <script type="text/javascript">
        $(document).ready(function () {

            $('form[name="<?=$arResult["arForm"]["VARNAME"]?>"]').validate({
                highlight: function (element) {
                    $(element).parent().addClass('error');
                },
                unhighlight: function (element) {
                    $(element).parent().removeClass('error');
                },
                submitHandler: function (form) {
                    if ($('form[name="<?=$arResult["arForm"]["VARNAME"]?>"]').valid()) {
                        setTimeout(function () {
                            $(form).find('button[type="submit"]').attr("disabled", "disabled");
                        }, 500);
                        var eventdata = {
                            type: 'form_submit',
                            form: form,
                            form_name: '<?=$arResult["arForm"]["VARNAME"]?>'
                        };
                        BX.onCustomEvent('onSubmitForm', [eventdata]);
                    }
                },
                errorPlacement: function (error, element) {
                    error.insertBefore(element);
                },
                messages: {
                    licenses_popup: {
                        required: BX.message('JS_REQUIRED_LICENSES')
                    }
                }
            });

            if (arMaxOptions['THEME']['PHONE_MASK'].length) {
                var base_mask = arMaxOptions['THEME']['PHONE_MASK'].replace(/(\d)/g, '_');
                $('form[name=<?=$arResult["arForm"]["VARNAME"]?>] input.phone').inputmask('mask', {'mask': arMaxOptions['THEME']['PHONE_MASK']});
                $('form[name=<?=$arResult["arForm"]["VARNAME"]?>] input.phone').blur(function () {
                    if ($(this).val() == base_mask || $(this).val() == '') {
                        if ($(this).hasClass('required')) {
                            $(this).parent().find('label.error').html(BX.message('JS_REQUIRED'));
                        }
                    }
                });
            }

            $('input[type=file]').uniform({
                fileButtonHtml: BX.message('JS_FILE_BUTTON_NAME'),
                fileDefaultHtml: BX.message('JS_FILE_DEFAULT')
            });
            $(document).on('change', 'input[type=file]', function () {
                if ($(this).val()) {
                    $(this).closest('.uploader').addClass('files_add');
                } else {
                    $(this).closest('.uploader').removeClass('files_add');
                }
            })
            $('.form .add_file').on('click', function () {
                var index = $(this).closest('.input').find('input[type=file]').length + 1;

                $(this).closest('.form-group').find('.input').append('<input type="file" id="POPUP_FILE" name="FILE_n' + index + '"   class="inputfile" value="" />');
                //$('<input type="file" id="POPUP_FILE" name="FILE_n'+index+'"   class="inputfile" value="" />').closest()($(this));
                $('input[type=file]').uniform({
                    fileButtonHtml: BX.message('JS_FILE_BUTTON_NAME'),
                    fileDefaultHtml: BX.message('JS_FILE_DEFAULT')
                });
            });

            $('.form .add_file').on('click', function () {
                var index = $(this).closest('.input').find('input[type=file]').length + 1;

                $(this).closest('.form-group').find('.input').append('<input type="file" id="POPUP_FILE" name="FILE_n' + index + '"   class="inputfile" value="" />');
                //$('<input type="file" id="POPUP_FILE" name="FILE_n'+index+'"   class="inputfile" value="" />').closest()($(this));
                $('input[type=file]').uniform({
                    fileButtonHtml: BX.message('JS_FILE_BUTTON_NAME'),
                    fileDefaultHtml: BX.message('JS_FILE_DEFAULT')
                });
            });

            $('.form .add_text').on('click', function () {
                var input = $(this).closest('.form-group').find('input[type=text]').first(),
                    index = $(this).closest('.form-group').find('input[type=text]').length,
                    name = input.attr('id').split('POPUP_')[1];

                $(this).closest('.form-group').find('.input').append('<input type="text" id="POPUP_' + name + '" name="' + name + '[' + index + ']"  class="form-control " value="" />');
            });

            // $('.popup').jqmAddClose('a.jqmClose');
            $('.jqmClose').on('click', function (e) {
                e.preventDefault();
                $(this).closest('.jqmWindow').jqmHide();
            })
            $('.popup').jqmAddClose('button[name="web_form_reset"]');
        });
    </script>
</div>