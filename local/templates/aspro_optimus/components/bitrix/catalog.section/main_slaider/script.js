$(document).ready(function(){
    var swiper = new Swiper('.swiper-container', {
        slidesPerView: 4,
        spaceBetween: 0,
        freeMode: true,
        loop: true,
        autoplay: true,

        pagination: {
            el: '.swiper-pagination',
            clickable: true,
        },
        navigation: {
            nextEl: '.swiper-button-next',
            prevEl: '.swiper-button-prev',
        },
        breakpoints: {
            // when window width is <= 320px
            450: {
                slidesPerView: 1,
                touchRatio: false,

            },
            // when window width is <= 480px
            768: {
                slidesPerView: 2,

            },
            // when window width is <= 640px
            1024: {
                slidesPerView: 4,

            }
        }
    });
});