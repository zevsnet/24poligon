var basketTimeout;
var totalSum;
var timerBasketUpdate = false;

function setQuantity(basketId, ratio, direction) {
    var curVal = parseFloat(BX("QUANTITY_INPUT_" + basketId).value), newVal;
    ratio = parseFloat(ratio);
    newVal = (direction == 'up') ? curVal + ratio : curVal - ratio;
    // newVal=newVal.toFixed(2);
    if (newVal < 0) newVal = 0;
    if (newVal > 0) {
        BX("QUANTITY_INPUT_" + basketId).value = newVal;
        BX("QUANTITY_INPUT_" + basketId).defaultValue = curVal;

        totalSum = 0;
        $('#basket_form tr[data-id=' + basketId + ']').closest('table').find("tbody tr[data-id]").each(function (i, element) {
            id = $(element).attr("data-id");
            count = BX("QUANTITY_INPUT_" + id).value;

            price = $(document).find("#basket_form input[name=item_price_" + id + "]").val();
            sum = count * price;
            totalSum += sum;
            $(document).find("#basket_form [data-id=" + id + "] .summ-cell .price").html(jsPriceFormat(sum));
        });

        $("#basket_form .top_total_row span.price").html(jsPriceFormat(totalSum));
        $("#basket_form .top_total_row div.discount").fadeTo("slow", 0.2);


        if (timerBasketUpdate) {
            clearTimeout(timerBasketUpdate);
            timerBasketUpdate = false;
        }

        timerBasketUpdate = setTimeout(function () {
            updateQuantity('QUANTITY_INPUT_' + basketId, basketId, ratio);
            timerBasketUpdate = false;
        }, 700);
    }
}

function updateQuantity(controlId, basketId, ratio, animate) {

    var oldVal = BX(controlId).defaultValue, newVal = parseFloat(BX(controlId).value) || 0;
    bValidChange = false; // if quantity is correct for this ratio
    if (!newVal) {
        bValidChange = false;
        BX(controlId).value = oldVal;
    }

    /*if($("#"+controlId).hasClass('focus'))
     newVal -= newVal % ratio;
     var is_int_ratio = (ratio % 1 == 0);
     newVal = is_int_ratio ? parseInt(newVal) : parseFloat(newVal).toFixed(2);*/

    if (ratio == 0 || ratio == 1) {
        bValidChange = true;
    } else {
        var newValInt = newVal * 10000, ratioInt = ratio * 10000, reminder = newValInt % ratioInt;
        if (reminder == 0) bValidChange = true;
    }

    if ($("#" + controlId).hasClass('focus'))
        newVal -= newVal % ratio;

    newVal = (ratio == 0 || ratio == 1) ? parseInt(newVal) : parseFloat(newVal).toFixed(1);

    if (isRealValue(BX("QUANTITY_SELECT_" + basketId))) {
        var option, options = BX("QUANTITY_SELECT_" + basketId).options, i = options.length;
    }
    while (i--) {
        option = options[i];
        if (parseFloat(option.value).toFixed(2) == parseFloat(newVal).toFixed(2)) option.selected = true;
    }

    BX("QUANTITY_" + basketId).value = newVal;
    BX("QUANTITY_INPUT_" + basketId).value = newVal;

    $('form[name^=basket_form]').prepend('<input type="hidden" name="BasketRefresh" value="Y" />');
    if (!!BX('COUPON')) BX('COUPON').disabled = true;
    // $.post( arOptimusOptions['SITE_DIR']+'ajax/show_basket.php', $("form[name^=basket_form]").serialize(), $.proxy(function( data){
    $.post($('#cur_page').val(), $("form[name^=basket_form]").serialize(), $.proxy(function (data) {
        if (timerBasketUpdate == false) {
            $("#basket-replace").html(data);
            $("#basket-replace .bigdata_recommended_products_container").css({
                'position': 'absolute',
                'opacity': 0,
                'visibility': 'hidden'
            });
            if ($("#basket-replace").find('span[id*=bigdata_recommended_]').length)
                $("#basket-replace").find('span[id*=bigdata_recommended_]').css({
                    'position': 'absolute',
                    'opacity': 0,
                    'visibility': 'hidden'
                });
        }
    }));
}

function basketAjaxReload() {
    if (!!BX('COUPON')) BX('COUPON').disabled = true;
    // $.post( arOptimusOptions['SITE_DIR']+'ajax/show_basket.php', $("form[name^=basket_form]").serialize(), $.proxy(function( data){

    $.post($('#cur_page').val(), $("form[name^=basket_form]").serialize(), $.proxy(function (data) {
        $("#basket-replace").html(data);
        $("#basket-replace .bigdata_recommended_products_container").css({
            'position': 'absolute',
            'opacity': 0,
            'visibility': 'hidden'
        });
        if ($("#basket-replace").find('span[id*=bigdata_recommended_]').length)
            $("#basket-replace").find('span[id*=bigdata_recommended_]').css({
                'position': 'absolute',
                'opacity': 0,
                'visibility': 'hidden'
            });
    }));
}

function delete_all_items(type, item_section, correctSpeed, url) {

    $.post(arOptimusOptions['SITE_DIR'] + "ajax/action_basket.php", "TYPE=" + type + "&CLEAR_ALL=Y", $.proxy(function (data) {
        $('input[name="BasketOrder"]').remove();
        basketAjaxReload();
    }));
}


function deleteProduct(basketId, itemSection, item) {
    function _deleteProduct(basketId, itemSection) {
        // $.post( arOptimusOptions['SITE_DIR']+'ajax/show_basket.php?action=delete&id='+basketId, $.proxy(function( data ){
        $.post($('#cur_page').val() + '?action=delete&id=' + basketId, $.proxy(function (data) {
            $("#basket-replace").html(data);
            $("#basket-replace .bigdata_recommended_products_container").css({
                'position': 'absolute',
                'opacity': 0,
                'visibility': 'hidden'
            });
            if ($("#basket-replace").find('span[id*=bigdata_recommended_]').length)
                $("#basket-replace").find('span[id*=bigdata_recommended_]').css({
                    'position': 'absolute',
                    'opacity': 0,
                    'visibility': 'hidden'
                });
        }));
    }

    if (checkCounters()) {
        delFromBasketCounter(item);
        setTimeout(function () {
            _deleteProduct(basketId, itemSection);
        }, 100);
    }
    else {
        _deleteProduct(basketId, itemSection);
    }
}

function delayProduct(basketId, itemSection) {
    // $.post( arOptimusOptions['SITE_DIR']+'ajax/show_basket.php?action=delay&id='+basketId, function( data ){
    $.post($('#cur_page').val() + '?action=delay&id=' + basketId, function (data) {
        $("#basket-replace").html(data);
        $("#basket-replace .bigdata_recommended_products_container").css({
            'position': 'absolute',
            'opacity': 0,
            'visibility': 'hidden'
        });
        if ($("#basket-replace").find('span[id*=bigdata_recommended_]').length)
            $("#basket-replace").find('span[id*=bigdata_recommended_]').css({
                'position': 'absolute',
                'opacity': 0,
                'visibility': 'hidden'
            });
    });
    /*if($('#basket_line').size()){
     reloadTopBasket('top', $('#basket_line'), 200, 2000, 'N');
     }*/
}

function addProduct(basketId, itemSection) {
    // $.post( arOptimusOptions['SITE_DIR']+'ajax/show_basket.php?action=add&id='+basketId, function( data ) {
    $.post($('#cur_page').val() + '?action=add&id=' + basketId, function (data) {
        $("#basket-replace").html(data);
        $("#basket-replace .bigdata_recommended_products_container").css({
            'position': 'absolute',
            'opacity': 0,
            'visibility': 'hidden'
        });
        if ($("#basket-replace").find('span[id*=bigdata_recommended_]').length)
            $("#basket-replace").find('span[id*=bigdata_recommended_]').css({
                'position': 'absolute',
                'opacity': 0,
                'visibility': 'hidden'
            });
    });
    /*if($('#basket_line').size()){
     reloadTopBasket('top', $('#basket_line'), 200, 2000, 'N');
     }*/
}


function checkOut(event) {
    function _checkOut(href) {
        if (typeof href === 'undefined') {
            BX("basket_form").submit();
        }
        else {

            location.href = href;
        }
    }

    if (!!BX('COUPON')) BX('COUPON').disabled = true;
    event = event || window.event;
    var th = $(event.target).parent();
    if (checkCounters('google')) {
        checkoutCounter(1, th.data('text'), function () {
            _checkOut(th.data('href'));
        });
        setTimeout(function () {
            _checkOut(th.data('href'));
        }, 600);
    } else {
        _checkOut(th.data('href'));
    }
    return true;
}