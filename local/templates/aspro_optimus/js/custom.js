/*
You can use this file with your scripts.
It will not be overwritten when you upgrade solution.
*/
$(document).ready(function () {
    $(".wrapper_middle_menu.wrap_menu").sticky({topSpacing: 0});
    $('body').on('click', '.sb_detail_seo_text', function (e) {
        var $parentBlock = $(this).parent();
        console.log($parentBlock);
        $($parentBlock).addClass('sb_seo_open_text')
    })

    $('body').on('click','#sb_btn_save',function(){
        yaCounter43218779.reachGoal('oformit2');
    });


    var swiper = new Swiper('.swiper-container', {
        autoplay: {
            delay: 5000,
            disableOnInteraction: false,
        },
    });
});
