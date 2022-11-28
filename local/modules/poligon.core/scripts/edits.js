$(document).ready(function () {
    let arDomains = '24poligon.ru';
    $($('[name="IPROPERTY_TEMPLATES[DETAIL_TEXT_PRODUCT_ELEMENT_' + arDomains + '][TEMPLATE]"]')[1])
        .trumbowyg()
        .on('tbwfocus', function () {
            let checkTmp = $(this).parents('.adm-detail-content-cell-r').find('[name="IPROPERTY_TEMPLATES[DETAIL_TEXT_PRODUCT_ELEMENT_24poligon.ru][INHERITED]"]');

            checkTmp.each(function () {
                if (!$(this).prop('checked') && ($(this).attr('name') == 'IPROPERTY_TEMPLATES[DETAIL_TEXT_PRODUCT_ELEMENT_24poligon.ru][INHERITED]')) {
                    $(this).parents('.adm-detail-content-cell-r').find('[name="IPROPERTY_TEMPLATES[DETAIL_TEXT_PRODUCT_ELEMENT_24poligon.ru][INHERITED]"]').click()
                }
            });
        });
    $('[id="mnu_IPROPERTY_TEMPLATES_DETAIL_TEXT_PRODUCT_ELEMENT_' + arDomains + '"]').hide();

    //Default
    $($('[name="IPROPERTY_TEMPLATES[ELEMENT_DETAIL_TEXT_PRODUCT][TEMPLATE]"]')[1])
        .trumbowyg()
        .on('tbwfocus', function () {
            let checkTmpDef = $(this).parents('.adm-detail-content-cell-r').find('[name="IPROPERTY_TEMPLATES[ELEMENT_DETAIL_TEXT_PRODUCT][INHERITED]"]');
            checkTmpDef.each(function () {
                if (!$(this).prop('checked') && ($(this).attr('name') == 'IPROPERTY_TEMPLATES[ELEMENT_DETAIL_TEXT_PRODUCT][INHERITED]')) {
                    $('[name="IPROPERTY_TEMPLATES[ELEMENT_DETAIL_TEXT_PRODUCT][INHERITED]"]').click()
                }
            });
        });

    $('#mnu_IPROPERTY_TEMPLATES_ELEMENT_DETAIL_TEXT_PRODUCT').hide();
//SECTION !!!!
    $($('[name="IPROPERTY_TEMPLATES[DETAIL_TEXT_SECTION_SECTION_' + arDomains + '][TEMPLATE]"]')[1])
        .trumbowyg()
        .on('tbwfocus', function () {
            let checkTmp = $(this).parents('.adm-detail-content-cell-r').find('[name="IPROPERTY_TEMPLATES[DETAIL_TEXT_SECTION_SECTION_24poligon.ru][INHERITED]"]');

            checkTmp.each(function () {
                if (!$(this).prop('checked') && ($(this).attr('name') == 'IPROPERTY_TEMPLATES[DETAIL_TEXT_SECTION_SECTION_24poligon.ru][INHERITED]')) {
                    $(this).parents('.adm-detail-content-cell-r').find('[name="IPROPERTY_TEMPLATES[DETAIL_TEXT_SECTION_SECTION_24poligon.ru][INHERITED]"]').click()
                }
            });
        });
    //$('[id="mnu_IPROPERTY_TEMPLATES_DETAIL_TEXT_PRODUCT_ELEMENT_' + arDomains + '"]').hide();


    //Default Section
    $($('[name="IPROPERTY_TEMPLATES[SECTION_DETAIL_TEXT_SECTION][TEMPLATE]"]')[1])
        .trumbowyg()
        .on('tbwfocus', function () {
            let checkTmpDef = $(this).parents('.adm-detail-content-cell-r').find('[name="IPROPERTY_TEMPLATES[SECTION_DETAIL_TEXT_SECTION][INHERITED]"]');
            checkTmpDef.each(function () {
                if (!$(this).prop('checked') && ($(this).attr('name') == 'IPROPERTY_TEMPLATES[SECTION_DETAIL_TEXT_SECTION][INHERITED]')) {
                    $('[name="IPROPERTY_TEMPLATES[SECTION_DETAIL_TEXT_SECTION][INHERITED]"]').click()
                }
            });
        });
    //$('#mnu_IPROPERTY_TEMPLATES_ELEMENT_DETAIL_TEXT_PRODUCT').hide();
});