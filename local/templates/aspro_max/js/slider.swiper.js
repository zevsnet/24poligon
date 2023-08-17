function initSwiperSlider(selector) {
  const $slider = $(selector || ".swiper" + ':not(.swiper-initialized):not(.appear-block)');
  $slider.each(function () {
    const _this = $(this);
    let options = {
      grabCursor: true,
      //   longSwipes: false,
      navigation: {
        nextEl: _this.parent().find(".swiper-button-next")[0],
        prevEl: _this.parent().find(".swiper-button-prev")[0],
      },
      pagination: {
        // el: ".swiper-pagination",
        el: _this.parent().find(".swiper-pagination")[0],
        type: "bullets",
        clickable: true,
      },
    };
    if (_this.data("pluginOptions")) {
      options = deepMerge({}, options, _this.data("pluginOptions"));
    }

    BX.onCustomEvent("onSetSliderOptions", [options]);
    const swiper = new Swiper(this, options);

    swiper.on("slideChange", function (swiper) {
      const eventdata = { slider: swiper };
      BX.onCustomEvent("onSlideChanges", [eventdata]);
    });

    if (options.init === false) {
      swiper.on("init", function (swiper) {
        const eventdata = { slider: swiper, options: options };
        BX.onCustomEvent("onInitSlider", [eventdata]);
        
        if( $slider.length === 1 )
          BX.onCustomEvent("onSlideChanges", [{ slider: swiper }])
      });
      // init Swiper
      swiper.init();
    }

    _this.data("swiper", swiper);
  });
}

function deepMerge() {
  const arr = [].slice.call(arguments);
  let destination = arr[0];
  const other = arr.slice(1);

  other.forEach(function (params) {
    for (let param in params) {
      if (typeof params[param] === "object") {
        for (let param2 in params[param]) {
          if (typeof destination[param] !== "object") {
            destination[param] = {};
          }
          destination[param][param2] = params[param][param2];
        }
      } else {
        destination[param] = params[param];
      }
    }
  });
  return destination;
}
readyDOM(function () {
  initSwiperSlider();
});
