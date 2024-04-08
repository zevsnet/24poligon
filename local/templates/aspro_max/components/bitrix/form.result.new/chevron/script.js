$(document).ready(function () {
    $('body').on('change', '#form_text_95', function (e) {
        let colorHex = $(this).val();

        $('[name="form_text_95"]').val($("#form_text_95 option:selected").text());

        $('.sb_body').css({'background': '#' + colorHex});
        $('.form_text_95').find('.sb_color').css({'background': '#' + colorHex});
    });

    $('body').on('change', '#form_text_96', function (e) {
        let colorHex = $(this).val();

        $('[name="form_text_96"]').val($("#form_text_96 option:selected").text());

        $('.sb_body').css({'border': '8px solid #' + colorHex});
        $('.form_text_96').find('.sb_color').css({'background': '#' + colorHex});


        // $('.line_kant_bottom').css({'background':'#'+colorHex});
    });

    $('body').on('change', '#form_text_97', function (e) {
        let colorHex = $(this).val();
        $('[name="form_text_97"]').val($("#form_text_97 option:selected").text());

        $('.sb_text').css({'color': '#' + colorHex});
        $('.form_text_97').find('.sb_color').css({'background': '#' + colorHex});
    });

    $('body').on('click', '.sb_ul li', function (e) {
        $('.sb_ul li').removeClass('active');
        $(this).addClass('active');
        $('[name="form_text_101"]').val($(this).text());
        if ($(this).text() == 'ВС(120*25)') {
            $('.sb_block_pic').css({'height': '100px'});
        } else {
            $('.sb_block_pic').css({'height': '120px'});

        }
    });

    $('body').on('input', '#form_text_98', function (e) {
        let sizeFont = 32;
        $('.sb_text').css({'font-size': sizeFont + 'px'})
        $('.sb_text').html($(this).val());
        let tmpWidth = $('.sb_text').width();
        let newSize = sizeFont - (tmpWidth - 200) * 0.1;
        if (newSize < 14) {
            newSize = 0;
        }
        if (tmpWidth > 250 && newSize == 0) {
            newSize = 14
        }

        if (tmpWidth > 250) {
            $('.sb_text').css({'font-size': newSize + 'px'})
        } else {
            $('.sb_text').css({'font-size': newSize + 'px'})
        }
    });
    $('body').on('click', '#sb_submit', function (e) {
        debugger
    });

});