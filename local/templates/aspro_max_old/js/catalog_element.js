(function (window) {
  /*new js code for offers*/
  /*set new delivery for offer*/
  window.setDeliverySKU = function (th, obj) {
    var buyBlock = th.find(".offer_buy_block"),
      input_value = obj.CONFIG.MIN_QUANTITY_BUY;

    // if (this.offers[this.offerNum].offer_set_quantity) {
    //   input_value = this.offers[this.offerNum].offer_set_quantity;
    // }
    if (buyBlock.find("input[name=quantity]").length) {
      input_value = buyBlock.find("input[name=quantity]").val();
    }

    var $calculate = buyBlock.closest(".catalog_detail").find(".calculate-delivery");
    if ($calculate.length) {
      $calculate.each(function () {
        var $calculateSpan = $(this).find("span[data-event=jqm]").first();

        if ($calculateSpan.length) {
          var $clone = $calculateSpan.clone();
          $clone.attr("data-param-product_id", obj.ID).attr("data-param-quantity", input_value).removeClass("clicked");
          $clone.insertAfter($calculateSpan).on("click", function () {
            //if (!jQuery.browser.mobile) {
              $(this).parent().addClass("loadings");
            //}
          });
          $calculateSpan.remove();
        }

        if ($(this).hasClass("with_preview")) {
          $(this).removeClass("inited");

          if (this.timerInitCalculateDelivery) {
            clearTimeout(this.timerInitCalculateDelivery);
          }

          var that = this;
          this.timerInitCalculateDelivery = setTimeout(function () {
            initCalculatePreview();
            that.timerInitCalculateDelivery = false;
          }, 1000);
        }
      });

      if (obj.CONFIG.ACTION === "ADD" && obj.CONFIG.CAN_BUY === true) {
        $calculate.show();
      } else {
        $calculate.hide();
      }
    }

    if (th.find(".cheaper_form").length) {
      var cheaper_form = th.find(".cheaper_form span");
      cheaper_form.data("autoload-product_name", obj.NAME);
      cheaper_form.data("autoload-product_id", obj.ID);
    }

    InitFancyBoxVideo();

    if (typeof obMaxPredictions === "object") {
      obMaxPredictions.showAll();
    }
  };
  window.UpdatePropsSKU = function (container, obj) {
    const $props = container.find(".js-offers-prop:first");
    if ($props.length) {
      container.find(".js-prop").remove();
      if (obj["DISPLAY_PROPERTIES"]) {
        if (!Object.keys(obj["DISPLAY_PROPERTIES"]).length) {
          return;
        }
        if (!window["propTemplate"]) {
          let $clone = $props.clone();
          $clone.find("> *:not(:first-child)").remove();
          $clone.find(".js-prop-replace").removeClass("js-prop-replace").addClass("js-prop");
          $clone.find(".js-prop-title").text("#PROP_TITLE#");
          $clone.find(".js-prop-value").text("#PROP_VALUE#");
          $clone.find(".hint").remove();
          let cloneHtml = $clone.html();
          window["propTemplate"] = cloneHtml;
        }

        let html = "";
        for (let key in obj["DISPLAY_PROPERTIES"]) {
          let title = obj["DISPLAY_PROPERTIES"][key]["NAME"];
          let value = obj["DISPLAY_PROPERTIES"][key]["DISPLAY_VALUE"] || obj["DISPLAY_PROPERTIES"][key]["VALUE"];

          let str = window["propTemplate"].replace("#PROP_TITLE#", title).replace("#PROP_VALUE#", value);

          html += str;
        }
        if (html) {
          $props[0].insertAdjacentHTML("beforeend", html);
        }
      }
    }
  };

  window.UpdateGroupPropsSKU = function (container, obj) {
    const $props = container.find(".js-offers-group-wrap:first");
    if ($props.length) {
      container.find(".js-offers-group").remove();
      container.find(".js-offers-group__item").remove();
      if (obj["DISPLAY_PROPERTIES"] && obj["PROPS_GROUP_HTML"]) {
        if (!Object.keys(obj["DISPLAY_PROPERTIES"]).length) {
          return;
        }
        let tmpDiv = document.createElement("div");
        tmpDiv.innerHTML = obj["PROPS_GROUP_HTML"];
        let offerPropGroups = tmpDiv.querySelectorAll(".js-offers-group");
        let noGroupContainer = container.find("[data-group-code='no-group']");
        if(offerPropGroups.length){
          let groupCode, elementPropGroup;
          for(let keyGroup = 0; keyGroup < offerPropGroups.length; keyGroup++){
            groupCode = offerPropGroups[keyGroup].getAttribute("data-group-code");
            elementPropGroup = container.find("[data-group-code='" + groupCode + "'] .js-offers-group__items-wrap");
            if(elementPropGroup.length){
              elementPropGroup.append(offerPropGroups[keyGroup].querySelectorAll(".js-offers-group__item"));
            } else {
              if (noGroupContainer.length) {
                noGroupContainer.before(offerPropGroups[keyGroup]);
              } else {
                $props.append(offerPropGroups[keyGroup]);
              }              
            }
          }
        }
      }
    }
  };

  window.SetSliderPictSKU = function (obj, wrapper) {
    var mainConteiner = wrapper.closest(".catalog_detail"),
      container = mainConteiner.find(".product-detail-gallery__slider--big"),
      containerThmb = mainConteiner.find(".product-detail-gallery__slider.thmb"),
      slideHtml = "",
      slideThmbHtml = "";
    (countPhoto = obj.GALLERY.length), (product = mainConteiner);

    containerThmb.css({
      "max-width": Math.ceil((countPhoto <= 4 ? countPhoto : 4) * 70 - 10),
    });
    if (countPhoto <= 1 ) {
      containerThmb.addClass('hidden');
    }else {
      containerThmb.removeClass('hidden');
    } 

    if (obj.GALLERY.length) {
      for (var i in obj.GALLERY) {
        if (typeof obj.GALLERY[i] == "object") {
          slideHtml +=
            '<div id="photo-' +
            i +
            '" class="product-detail-gallery__item product-detail-gallery__item--big text-center" data-big="' +
            obj.GALLERY[i].BIG.src +
            '">' +
            '<a href="' +
            obj.GALLERY[i].BIG.src +
            '" data-fancybox="gallery" class="product-detail-gallery__link fancy"><img class="product-detail-gallery__picture" border="0" src="' +
            obj.GALLERY[i].SMALL.src +
            '" alt="' +
            obj.GALLERY[i].ALT +
            '" title="' +
            obj.GALLERY[i].TITLE +
            '" /></a>' +
            "</div>";
        }
      }

      if (countPhoto > 1) {
        for (var i in obj.GALLERY) {
          if (typeof obj.GALLERY[i] == "object") {
            slideThmbHtml +=
              '<div class="product-detail-gallery__item product-detail-gallery__item--thmb text-center" data-big="' +
              obj.GALLERY[i].BIG.src +
              '">' +
              '<img class="product-detail-gallery__picture" border="0" src="' +
              obj.GALLERY[i].SMALL.src +
              '" alt="' +
              obj.GALLERY[i].ALT +
              '" title="' +
              obj.GALLERY[i].TITLE +
              '" data-xoriginalwidth="' +
              obj.GALLERY[i].BIG.width +
              '" data-xoriginalheight="' +
              obj.GALLERY[i].BIG.height +
              '" />' +
              "</div>";
          }
        }
      }
    } else {
      slideHtml +=
        '<div class="product-detail-gallery__item product-detail-gallery__item--big text-center">' +
        '<span class="product-detail-gallery__link"><img class="product-detail-gallery__picture" border="0" src="' +
        mainItemForOffers.NO_PHOTO +
        '" alt="' +
        obj.NAME +
        '" title="' +
        obj.NAME +
        '" /></span>' +
        "</div>";
    }

    container.html(slideHtml);
    containerThmb.attr("data-size", countPhoto).html(slideThmbHtml);

    product.find(".popup_video").remove();
    let popUpVideo = obj.POPUP_VIDEO ? obj.POPUP_VIDEO : mainItemForOffers.POPUP_VIDEO;

    if (popUpVideo) {
      let popupHtml =
        '<div class="video-block popup_video ' +
        (obj.GALLERY.length > 4 ? "fromtop" : "") +
        ' sm"><a class="various video_link image dark_link" href="' +
        popUpVideo +
        '" title="' +
        BX.message("POPUP_VIDEO") +
        '"><span class="play text-upper font_xs">' +
        BX.message("POPUP_VIDEO") +
        "</span></a></div>";
      if (containerThmb.length) {
        $(popupHtml).insertAfter(containerThmb);
      } else {
        let fastViewConteiner = mainConteiner.find(".fastview-product__top-info");
        if (fastViewConteiner.length) {
          $(popupHtml).appendTo(fastViewConteiner);
        }
      }
    }

    if (!slideThmbHtml) product.find(".popup_video").addClass("only-item");

    if (container.data("owl.carousel") !== undefined) container.data("owl.carousel").destroy();

    if (containerThmb.data("owl.carousel") !== undefined) containerThmb.data("owl.carousel").destroy();

    InitOwlSlider();
    InitFancyBox();
    InitFancyBoxVideo();

    if (arAsproOptions["THEME"]["DETAIL_PICTURE_MODE"] == "MAGNIFIER") {
      var pict = "";
      if (obj.GALLERY && obj.GALLERY[0]) {
        pict =
          '<img class="product-detail-gallery__picture zoom_picture" border="0" src="' +
          obj.GALLERY[0].SMALL.src +
          '" alt="' +
          obj.GALLERY[0].ALT +
          '" title="' +
          obj.GALLERY[0].TITLE +
          '" data-xoriginal="' +
          obj.GALLERY[0].BIG.src +
          '" data-xoriginalwidth="' +
          obj.GALLERY[0].BIG.width +
          '" data-xoriginalheight="' +
          obj.GALLERY[0].BIG.height +
          '"/>';
      } else {
        pict =
          '<img class="product-detail-gallery__picture one" border="0" src="' +
          mainItemForOffers.NO_PHOTO +
          '" alt="' +
          obj.NAME +
          '" title="' +
          obj.NAME +
          '" data-xoriginal2="' +
          mainItemForOffers.NO_PHOTO +
          '"/>';
      }

      if (product.find(".line_link").length) {
        product.find(".line_link").html(pict);
      } else if (
        product.find(".product-detail-gallery__picture.one").length ||
        product.find(".product-detail-gallery__picture.zoom_picture").length
      ) {
        product.find("#photo-sku").html(pict);
      }
      InitZoomPict();
    }
    //set image for send_gift
    if (obj.GALLERY.length) {
      let newOfferImage = obj.GALLERY[0]["BIG"]["src"] ? obj.GALLERY[0]["BIG"]["src"] : obj.GALLERY[0]["SRC"];
      mainConteiner.find('.product-detail-gallery__container link[itemprop="image"]').attr("href", newOfferImage);
    }
  };

  window.SetArticleSKU = function (obj, wrapper) {
    let articleBlock = wrapper.find(".product-info-headnote__article .article");
    if (articleBlock) {
      var article_text = obj.ARTICLE ? obj.ARTICLE : "";
      if (!article_text && obj.SHOW_ARTICLE_SKU == "Y" && mainItemForOffers.ARTICLE) {
        article_text = mainItemForOffers.ARTICLE;
      }
      if (articleBlock.find(".article__value").length) {
        articleBlock.find(".article__value").text(article_text);
      }
      if (article_text) {
        articleBlock.show();
      } else {
        articleBlock.hide();
      }
      let article_name = obj.ARTICLE_NAME ? obj.ARTICLE_NAME : "";
      if (article_name && articleBlock.find(".article__title").length) {
        articleBlock.find(".article__title").text(article_name + ":");
      }
    }
  };

  window.SetHrefSKU = function (obj, wrapper) {
    let titleHref = wrapper.find(".fast-view-title"),
      bottomHref = wrapper.find(".bottom-href-fast-view");
    if (titleHref.length) {
      titleHref.attr("href", obj.URL);
    }
    if (bottomHref.length) {
      bottomHref.attr("href", obj.URL);
    }
  };

  window.SetServicesSKU = function (obj, wrapper) {
    // set offer for buy services
    var servWrap = wrapper.closest(".product-container").find(".buy_services_wrap");
    if (servWrap.length) {
      servWrap.attr("data-parent_product", obj["ID"]);
      servWrap.find(".services-item").each(function () {
        var serviceItem = $(this);
        var basketItem = arBasketAspro.SERVICES[serviceItem.attr("data-item_id") + "_" + obj["ID"]];

        if (typeof basketItem != "undefined" && basketItem["basket_id"]) {
          serviceItem.find('input[name="buy_switch_services"]').prop("checked", true);
          serviceItem.find('.counter_block input[name="quantity"]').val(basketItem.quantity);
          serviceItem.addClass("services_on");
        } else {
          serviceItem.removeClass("services_on");
          serviceItem.find('input[name="buy_switch_services"]').prop("checked", false);
        }
      });
    }
    ////
  };

  window.SetTitleSKU = function (obj, wrapper) {
    if (arMaxOptions["THEME"]["CHANGE_TITLE_ITEM_DETAIL"] == "Y") {
      var skuName =
        typeof obj.IPROPERTY_VALUES === "object" && obj.IPROPERTY_VALUES.ELEMENT_PAGE_TITLE
          ? obj.IPROPERTY_VALUES.ELEMENT_PAGE_TITLE
          : obj.NAME;
      var skuWindowTitle =
        typeof obj.IPROPERTY_VALUES === "object" && obj.IPROPERTY_VALUES.ELEMENT_META_TITLE
          ? obj.IPROPERTY_VALUES.ELEMENT_META_TITLE
          : obj.NAME;
      var bFastView = wrapper.find(".fastview-product").length;
      if (bFastView) {
        wrapper.find(".fast-view-title").html(skuName);
      } else {
        $("h1").html(skuName);
        document.title = skuWindowTitle + "" + mainItemForOffers.POSTFIX;
        if (typeof ItemObj == "object") {
          ItemObj.TITLE = skuName;
          ItemObj.WINDOW_TITLE = skuWindowTitle;
        }
      }
    }
    $('.catalog_detail input[data-sid="PRODUCT_NAME"]').attr("value", $("h1").text());
  };

  window.setDescriptionSKU = function (wrapper, obj) {
    var block = wrapper,
      isDetail = block.hasClass("product-main"),
      detailTextBlock = block.closest(".product-container").find(".detail-text-wrap"),
      previewTextBlock = block.find(".preview-text-replace");
    if (isDetail && detailTextBlock.length && mainItemForOffers.SHOW_SKU_DESCRIPTION) {
      if (obj.DETAIL_TEXT.length) {
        $(".detail-text-wrap").html(obj.DETAIL_TEXT);
      } else if (mainItemForOffers.DETAIL_TEXT.length) {
        $(".detail-text-wrap").html(mainItemForOffers.DETAIL_TEXT);
      }
    }
    if (isDetail && previewTextBlock.length && mainItemForOffers.SHOW_SKU_DESCRIPTION) {
      if (obj.PREVIEW_TEXT.length) {
        previewTextBlock.html(obj.PREVIEW_TEXT);
      } else if (mainItemForOffers.PREVIEW_TEXT.length) {
        previewTextBlock.html(mainItemForOffers.PREVIEW_TEXT);
      }
    }
    // else if(previewTextBlock.length){
    // 	previewTextBlock.html(obj.PREVIEW_TEXT);
    // }
  };

  window.setOfferSetSKU = function (obj, wrapper) {
    var mainWrap = wrapper.closest(".product-container"),
      offerSet = mainWrap.find("[data-offerSetId]"),
      currentOfferSet = mainWrap.find("[data-offerSetId=" + obj["ID"] + "]");
    if (offerSet.length) {
      offerSet.hide();
    }
    if (offerSet.length) {
      currentOfferSet.show();
    }
  };

  window.SetViewedSKU = function (obj) {
    var arPriceItem = obj.MIN_PRICE;

    setViewedProduct(obj.ID, {
      PRODUCT_ID: mainItemForOffers.PRODUCT_ID,
      IBLOCK_ID: obj.IBLOCK_ID,
      NAME: obj.NAME,
      DETAIL_PAGE_URL: obj.URL,
      PICTURE_ID: obj.PREVIEW_PICTURE_FIELD
        ? obj.PREVIEW_PICTURE_FIELD.ID
        : obj.PARENT_PICTURE
        ? obj.PARENT_PICTURE.ID
        : obj.GALLERY.length
        ? obj.GALLERY[0].ID
        : false,
      CATALOG_MEASURE_NAME: obj.MEASURE,
      MIN_PRICE: arPriceItem,
      CAN_BUY: obj.CONFIG.CAN_BUY ? "Y" : "N",
      IS_OFFER: "Y",
      WITH_OFFERS: "N",
    });
  };

  window.SetAdditionalGallerySKU = function (obj, wrapper) {
    var $gallery = wrapper.closest(".product-container").find(".additional-gallery");

    if(window.scrollTabsTimeout !== undefined) {
      clearTimeout(window.scrollTabsTimeout);
    }
    if (typeof ResizeScrollTabs === 'function') {
      window.scrollTabsTimeout = setTimeout(
        ResizeScrollTabs,
        20
      );
    }

    if ($gallery.length) {
      var bHidden = $gallery.hasClass("hidden"),
        bigGallery = $gallery.find(".big-gallery-block .owl-carousel"),
        smallGallery = $gallery.find(".small-gallery-block .row"),
        slideBigHtml = (slideSmallHtml = ""),
        descTab = $('.nav.nav-tabs [href="#desc"]').closest("li");


      var galleryItems = obj.ADDITIONAL_GALLERY.concat(mainItemForOffers.ADDITIONAL_GALLERY);

      if (galleryItems.length) {
        if (bHidden) {
          $gallery.removeClass("hidden");
          bigGallery.removeClass("owl-hidden");
          descTab.removeClass("hidden");
        }

        $gallery
          .find(".switch-item-block .switch-item-block__count-wrapper--small .switch-item-block__count-value")
          .text(galleryItems.length);
        $gallery
          .find(".switch-item-block .switch-item-block__count-wrapper--big .switch-item-block__count-value")
          .text(1 + "/" + galleryItems.length);

        for (var i in galleryItems) {
          if (typeof galleryItems[i] == "object") {
            slideBigHtml +=
              '<div class="item">' +
              '<a href="' +
              galleryItems[i].DETAIL.SRC +
              '" data-fancybox="big-gallery" class="fancy"><img class="picture" border="0" src="' +
              galleryItems[i].PREVIEW.src +
              '" alt="' +
              galleryItems[i].ALT +
              '" title="' +
              galleryItems[i].TITLE +
              '" /></a>' +
              "</div>";

            slideSmallHtml +=
              '<div class="col-md-3"><div class="item">' +
              '<a href="' +
              galleryItems[i].DETAIL.SRC +
              '" data-fancybox="small-gallery" class="fancy"><img class="picture" border="0" src="' +
              galleryItems[i].PREVIEW.src +
              '" alt="' +
              galleryItems[i].ALT +
              '" title="' +
              galleryItems[i].TITLE +
              '" /></a>' +
              "</div></div>";
          }
        }

        bigGallery.html(slideBigHtml);
        smallGallery.html(slideSmallHtml);

        if (bigGallery.data("owl.carousel") !== undefined) bigGallery.data("owl.carousel").destroy();

        InitOwlSlider();
        InitFancyBox();
        typeof ResizeScrollTabs === 'function' && ResizeScrollTabs();
      } else {
        $gallery.addClass("hidden");
        if($gallery.closest('.tab-pane').find(".ordered-block").length <= 1 && $gallery.closest('.tab-pane').find(".detail-text-wrap").length === 0){
          descTab.addClass("hidden");
          SetActiveTab($(wrapper.closest(".product-container").find(".tabs > .nav-tabs > li")));
          typeof ResizeScrollTabs === 'function' && ResizeScrollTabs();
        }
      }
    }
  };
  window.SetActiveTab = function(wrapperTabs) {
    wrapperTabs.each(function(){
      var _this = $(this);
      if( (_this.hasClass("active") && _this.hasClass("hidden"))) {
        _this.next().find("a").click();
      }else if($(this).hasClass("hidden")){
        _this.next().find("a").click();
      }
    });
  };
})(window);
