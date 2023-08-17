InitMenuNavigationAim = function () {
  var $block = $(".menu-navigation__sections-wrapper .menu-navigation__sections:not(.aim-init)");
  if ($block.length) {
    $block.addClass("aim-init");
    BX.loadScript(arAsproOptions["SITE_TEMPLATE_PATH"] + "/vendor/js/jquery.menu-aim.js", function () {
      $block.menuAim({
        firstActiveRow: true,
        rowSelector: "> .menu-navigation__sections-item",
        activate: function activate(a) {
          var _this = $(a),
            index = _this.index(),
            items = _this.closest(".menu-navigation__sections-wrapper").next(),
            link = _this.find("> a");

          _this.siblings().find("> a").addClass("dark_link");
          link.addClass("colored_theme_text").removeClass("dark_link");
          items.find(".parent-items").siblings().hide();
          items.find(".parent-items").eq(index).show();
        },
        deactivate: function deactivate(a) {
          var _this = $(a),
            index = _this.index(),
            items = _this.closest(".menu-navigation__sections-wrapper").next(),
            link = _this.find("> a");

          link.removeClass("colored_theme_text").addClass("dark_link");
          items.find(".parent-items").siblings().hide();
        },
      });
    });
  }
};

readyDOM(function () {
  window.asproAllProgressBar = document.querySelectorAll(".header-progress-bar__inner");
});

/*progress bar in header*/
function updateProgressBar() {
  let allProgressBar = typeofExt(window.asproAllProgressBar) === "nodelist" ? window.asproAllProgressBar : false;
  if (allProgressBar && allProgressBar.length) {
    let scrollHeight = Math.max(
      document.body.scrollHeight,
      document.documentElement.scrollHeight,
      document.body.offsetHeight,
      document.documentElement.offsetHeight,
      document.body.clientHeight,
      document.documentElement.clientHeight
    );
    let scrollTop = window.pageYOffset || document.documentElement.scrollTop;
    let clientHeight = document.documentElement.clientHeight;
    let scrollProcent = Math.round((scrollTop / (scrollHeight - clientHeight)) * 100);
    if (allProgressBar.length) {
      for (let item = 0; item < allProgressBar.length; item++) {
        allProgressBar[item].style.width = scrollProcent + "%";
      }
    }
  }
}
/**/

$(document).ready(function () {
  //dropdown-select
  $(document).on("click", ".dropdown-select .dropdown-select__title", function () {
    var _this = $(this),
      menu = _this.parent().find("> .dropdown-select__list");
    menu.stop().slideToggle(300);
  });

  // close select
  $("html, body").on("mousedown", function (e) {
    if (typeof e.target.className == "string" && e.target.className.indexOf("adm") < 0) {
      e.stopPropagation();

      if (!$(e.target).closest(".dropdown-select").length) {
        $(".dropdown-select__list:visible").slideUp();
      }
    }
  });
  /**/

  /*side head block*/
  $(document).on("click", ".slide-block .slide-block__head", function (e) {
    var _this = $(this),
      menu = _this.siblings(".slide-block__body"),
      bVisibleMeu = menu.is(":visible"),
      animate = bVisibleMeu ? "slideUp" : "slideDown";

    if (!_this.hasClass("clicked") && menu.length && !_this.hasClass("ignore") && !$(e.target).attr("href")) {
      var type = _this.data("id");
      _this.addClass("clicked");

      if (bVisibleMeu) $.cookie(type + "_CLOSED", "Y");
      else $.removeCookie(type + "_CLOSED");

      menu.stop().slideToggle({
        duration: 150,
        start: function () {
          _this.toggleClass("closed");
        },
        complete: function () {
          _this.removeClass("clicked");

          if (typeof window["stickySidebar"] !== "undefined") {
            window["stickySidebar"].updateSticky();
          }
        }
      });
    }
  });
  /**/

  /*sku change props*/
  if (!("SelectOfferProp" in window) && typeof window.SelectOfferProp != "function") {
    SelectOfferProp = function () {
      // return false;
      var _this = $(this),
        obParams = {},
        obSelect = {},
        objUrl = parseUrlQuery(),
        add_url = "",
        selectMode = _this.hasClass("list_values_wrapper") ? true : false,
        container = _this.closest(".bx_catalog_item_scu"),
        img = _this.closest(".item-parent").find(".thumb img"),
        bDetail = _this.closest(".product-main").length,
        curCode = _this.closest(".cur").data("code");

      /* request params */
      obParams = {
        PARAMS: bDetail ? $(".js-sku-config").data("params") : _this.closest(".js_wrapper_items").data("params"),
        ID: container.data("offer_id"),
        SITE_ID: container.data("site_id"),
        LINK_ID: container.data("id") + "_" + (bDetail ? "detail" : curCode ? curCode : "block"),
        IBLOCK_ID: container.data("offer_iblockid"),
        IBLOCK_ID_PARENT: container.data("iblockid"),
        PROPERTY_ID: container.data("propertyid"),
        DEPTH: _this.closest(".item_wrapper").index(),
        VALUE: selectMode ? _this.find("option:selected").data("onevalue") : _this.data("onevalue"),
        CLASS: "inner_content",
        PICTURE: img.data("src") ? img.data("src") : img.attr("src"),
        ARTICLE_NAME: _this.closest(".item-parent").find(".article_block").data("name"),
        ARTICLE_VALUE: _this.closest(".item-parent").find(".article_block").data("value"),
      };
      /**/

      if ("clear_cache" in objUrl) {
        if (objUrl.clear_cache == "Y") add_url += "?clear_cache=Y";
      }

      let isActiveContainer = container.hasClass("js-selected");
      // set active
      $(".bx_catalog_item_scu").removeClass("js-selected");
      container.addClass("js-selected");

      /* save selected values */
      for (i = 0; i < obParams.DEPTH + 1; i++) {
        strName = "PROP_" + container.find(".item_wrapper:eq(" + i + ") > div").data("id");
        if (container.find(".item_wrapper:eq(" + i + ") select").length) {
          obSelect[strName] = container.find(".item_wrapper:eq(" + i + ") select option:selected").data("onevalue");
          obParams[strName] = container.find(".item_wrapper:eq(" + i + ") select option:selected").data("onevalue");
        } else {
          obSelect[strName] = container.find(".item_wrapper:eq(" + i + ") li.item.active").data("onevalue");
          obParams[strName] = container.find(".item_wrapper:eq(" + i + ") li.item.active").data("onevalue");
        }
      }

      // obParams.SELECTED = JSON.stringify(obSelect);
      /**/

      if (!selectMode) {
        _this.siblings().removeClass("active");
        _this.addClass("active");
      }

      if (_this.attr("title")) {
        var skuVal = _this.attr("title").split(":")[1];
        _this.closest(".item_wrapper").find(".show_class .val").text(skuVal);
      } else {
        var img_row = _this.find(" > i");
        if (img_row.length && img_row.attr("title")) {
          var skuVal = img_row.attr("title").split(":")[1];
          _this.closest(".item_wrapper").find(".show_class .val").text(skuVal);
        }
      }

      /* get sku */
      if (window.obOffers && typeofExt(obOffers) === "array" && isActiveContainer) {
        //from /ajax/js_item_detail.php
        selectedValues = obSelect;
        strPropValue = obParams["VALUE"];
        depth = obParams["DEPTH"];
        //wrapper = item;
        arFilter = {};
        tmpFilter = [];

        UpdateSKUInfoByProps();
        finalActionSKUInfo(_this);
      } else {
        /* get sku */
        $.ajax({
          url: arMaxOptions["SITE_DIR"] + "ajax/js_item_detail.php" + add_url,
          type: "POST",
          data: obParams,
          success: function (html) {
            var ob = BX.processHTML(html);
            BX.ajax.processScripts(ob.SCRIPT);
            finalActionSKUInfo(_this);
          },
        });
      }
      function finalActionSKUInfo(th) {
        if ($(".counter_wrapp.list")) {
          $(".counter_wrapp.list .counter_block.big").removeClass("big");
        }
        var sku_props = th.closest(".sku_props").find(".item_wrapper .item.active, .item_wrapper .list_values_wrapper");
        $.each(sku_props, function (index, value) {
          value = $(value);
          var skuVal = "";
          if (value.attr("title")) {
            skuVal = value.attr("title").split(":")[1];
          } else if (typeof value.val() !== "undefined") {
            skuVal = value.val();
          } else {
            var img_row = value.find(" > i");
            if (img_row.length && img_row.attr("title")) {
              skuVal = img_row.attr("title").split(":")[1];
            }
          }

          if (skuVal != "") {
            value.closest(".item_wrapper").find(".show_class .val").text(skuVal);
          }
        });
      }
    };
    $(document).on("click", ".ajax_load .bx_catalog_item_scu li.item", SelectOfferProp);
    $(document).on("change", ".ajax_load .bx_catalog_item_scu select.list_values_wrapper", SelectOfferProp);
    //detail
    $(document).on("click", ".bx_catalog_item_scu.sku_in_detail li.item", SelectOfferProp);
    $(document).on("change", ".bx_catalog_item_scu.sku_in_detail select.list_values_wrapper", SelectOfferProp);
  }

  /**/

  /**/
  $(".switch-item-block .switch-item-block__icons").on("click", function () {
    var $this = $(this),
      animationTime = 200;

    if ($this.hasClass("switch-item-block__icons--small") && !$this.hasClass("active")) {
      $this.addClass("active");
      $this.siblings(".switch-item-block__icons--big").removeClass("active");
      $this
        .closest(".switch-item-block")
        .find(".switch-item-block__count-wrapper--big")
        .fadeOut(animationTime, function () {
          $this.closest(".switch-item-block").find(".switch-item-block__count-wrapper--small").fadeIn(animationTime);
        });

      $this
        .closest(".switch-item-block")
        .siblings(".big-gallery-block")
        .fadeOut(animationTime, function () {
          $this.closest(".switch-item-block").siblings(".small-gallery-block").fadeIn(animationTime);
        });
    } else if ($this.hasClass("switch-item-block__icons--big") && !$this.hasClass("active")) {
      $this.addClass("active");
      $this.siblings(".switch-item-block__icons--small").removeClass("active");
      $this
        .closest(".switch-item-block")
        .find(".switch-item-block__count-wrapper--small")
        .fadeOut(animationTime, function () {
          $this.closest(".switch-item-block").find(".switch-item-block__count-wrapper--big").fadeIn(animationTime);
        });

      $this
        .closest(".switch-item-block")
        .siblings(".small-gallery-block")
        .fadeOut(animationTime, function () {
          $this.closest(".switch-item-block").siblings(".big-gallery-block").fadeIn(animationTime);
        });
    }
    setTimeout(function () {
      InitLazyLoad();
    }, 300);
  });
  /**/

  /*many items menu*/

  //   InitMenuNavigationAim();
  /**/

  /*progress bar in header*/
  window.addEventListener("scroll", throttle(updateProgressBar, 200));
  /**/

  /*show password*/
  $(".form-control:not(.eye-password-ignore) [type=password]").each(function (item) {
    let passBlock = $(this).closest(".form-control");
    let labelBlock = passBlock.find(".label_block");
    if (labelBlock.length) {
      labelBlock.addClass("eye-password");
    } else {
      passBlock.addClass("eye-password");
    }
  });
  $(document).on("click", ".eye-password:not(.eye-password-ignore)", function (event) {
    let input = this.querySelector("input");
    let eyeWidth = 56;
    if (this.clientWidth - eyeWidth < event.offsetX) {
      if (input.type == "password") {
        input.type = "text";
        this.classList.add("password-show");
      } else if (input.type == "text") {
        input.type = "password";
        this.classList.remove("password-show");
      }
    }
  });
  /**/
});
