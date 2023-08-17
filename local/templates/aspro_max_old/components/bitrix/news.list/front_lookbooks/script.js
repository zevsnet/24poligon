BX.ready(function () {
  $(".lookbooks .flexslider .js-click").click(function () {
    var _this = $(this),
      block = "",
      activeBlock = _this.closest(".lookbook-wrapper").find(".lookbook.lookbook--active");

    if (_this.hasClass("flex-prev")) {
      activeBlock.fadeOut(function () {
        $(this).removeClass("lookbook--active");
        block = activeBlock.prev(".lookbook");
        if (block.length) {
          block.fadeIn(function () {
            $(this).addClass("lookbook--active");
          });
        } else {
          _this
            .closest(".lookbook-wrapper")
            .find(".lookbook:last-of-type")
            .fadeIn(function () {
              $(this).addClass("lookbook--active");
            });
        }
      });
    } else {
      activeBlock.fadeOut(function () {
        $(this).removeClass("lookbook--active");

        block = activeBlock.next(".lookbook");
        if (block.length) {
          block.fadeIn(function () {
            $(this).addClass("lookbook--active");
          });
        } else {
          _this
            .closest(".lookbook-wrapper")
            .find(".lookbook:eq(0)")
            .fadeIn(function () {
              $(this).addClass("lookbook--active");
            });
        }
      });
    }
  });
  var slideText = $(".lookbook__info-toggle:first").text();
  $(".lookbook__info-toggle").click(function () {
    var _this = $(this),
      slideBlock = _this.closest(".lookbook__info").find(".lookbook__info-text-more");
    if (slideBlock.is(":visible")) {
      _this.text(slideText);
      slideBlock.parent().removeClass("clicked");
    } else {
      _this.text(_this.data("hide"));
      slideBlock.parent().addClass("clicked");
    }
    slideBlock.slideToggle();
  });
  loadScrollTabs(".lookbooks .tabs-wrapper", () => {
    $(".lookbooks .tabs-wrapper").scrollTab({
      tabs_wrapper: "ul.tabs",
      arrows_css: {
        top: "-1px",
      },
      onResize: function (options) {
        var top_wrapper = options.scrollTab.closest(".top_block");
        if (top_wrapper.length) {
          var tabs_wrapper = top_wrapper.find(".right_block_wrapper .tabs-wrapper");

          if (window.matchMedia("(max-width: 767px)").matches) {
            tabs_wrapper.css({
              width: "100%",
              "max-width": "",
            });
            return true;
          }

          var title = top_wrapper.find("h3");
          var right_link = top_wrapper.find(".right_block_wrapper > a");
          var all_width = top_wrapper[0].getBoundingClientRect().width;

          if (title.length) {
            all_width -= title.outerWidth(true);
          }

          if (right_link.length) {
            all_width -= right_link.outerWidth(true);
          }

          all_width -= Number.parseInt(tabs_wrapper.css("margin-right"));

          tabs_wrapper.css({
            "max-width": all_width,
            width: "",
          });
        }
        options.width = all_width;
      },
    });
  });
});

BX.addCustomEvent(window, "clickedTabsLi", function (e) {
  if ($(".lookbook-wrapper").length && !e.target.hasClass("clicked")) {
    $(".lookbook-wrapper").hide();
    $(".lookbook-wrapper:eq(" + e.index + ")").show();
  }
});
