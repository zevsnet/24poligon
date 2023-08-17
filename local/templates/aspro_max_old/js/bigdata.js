function setBigData(params) {
  const { siteId, template, componentPath, parameters, bigData, wrapper, countBigdata } = params;
  if (bigData && (typeof bigData === 'object') && !(bigData instanceof Array)) {
    BX.cookie_prefix = bigData.js.cookiePrefix || "";
    BX.cookie_domain = bigData.js.cookieDomain || "";
    BX.current_server_time = bigData.js.serverTime;
 
    let url = "https://analytics.bitrix.info/crecoms/v1_0/recoms.php";
    const data = BX.ajax.prepareData(bigData.params);

    if (data) {
      url += (url.indexOf("?") !== -1 ? "&" : "?") + data;
    }

    const onReady = function (result) {
      sendRequest({
        action: "deferredLoad",
        bigData: "Y",
        items: (result && result.items) || [],
        rid: result && result.id,
        count: countBigdata,
        rowsRange:[countBigdata],
        shownIds: bigData.shownIds,
        siteId: siteId,
        template: template,
        parameters: parameters,
      });
    };

    BX.ajax({
      method: "GET",
      dataType: "json",
      url: url,
      timeout: 3,
      onsuccess: onReady,
      onfailure: onReady,
    });

    function sendRequest(data) {
      BX.ajax({
        url:
          componentPath + "/ajax.php" + (document.location.href.indexOf("clear_cache=Y") !== -1 ? "?clear_cache=Y" : ""),
        method: "POST",
        dataType: "json",
        timeout: 60,
        data: data,
        onsuccess: function (result) {
          if (!result || !result.JS) return;
          $(wrapper).html(result.items);
          var eventdata = { action: "jsLoadBlock" };
          BX.onCustomEvent("onCompleteAction", [eventdata, wrapper]);
        },
      });
    }
  }
}

function getCookie (name){
  var matches = document.cookie.match(new RegExp(
    "(?:^|; )" + name.replace(/([\.$?*|{}\(\)\[\]\\\/\+^])/g, '\\$1') + "=([^;]*)"
  ));

  return matches ? decodeURIComponent(matches[1]) : null;
}

function rememberProductRecommendation (item) {
  const cookieName = BX.cookie_prefix + '_RCM_PRODUCT_LOG';
  let cookie = getCookie(cookieName);
  let itemFound = false;

      let cItems = [],
        cItem;

      if (cookie)
      {
        cItems = cookie.split('.');
      }

      let i = cItems.length;

      while (i--)
      {
        cItem = cItems[i].split('-');

        if (cItem[0] == item)
        {
          // it's already in recommendations, update the date
          cItem = cItems[i].split('-');

          // update rcmId and date
          cItem[1] = 'mostviewed';
          cItem[2] = BX.current_server_time;

          cItems[i] = cItem.join('-');
          itemFound = true;
        }
        else
        {
          if ((BX.current_server_time - cItem[2]) > 3600 * 24 * 30)
          {
            cItems.splice(i, 1);
          }
        }
      }

      if (!itemFound)
      {
        // add recommendation
        cItems.push([item, 'mostviewed', BX.current_server_time].join('-'));
      }

      // serialize
      let plNewCookie = cItems.join('.'),
        cookieDate = new Date(new Date().getTime() + 1000 * 3600 * 24 * 365 * 10).toUTCString();
      document.cookie = cookieName + "=" + plNewCookie + "; path=/; expires=" + cookieDate + "; domain=" + BX.cookie_domain;
      
}


BX.addCustomEvent("onCompleteAction", function (eventdata, _this) {
  const wrapperItem = $(_this).closest(".item");
  if (eventdata.action === "loadForm") {
    if (wrapperItem.data("bigdata") === "Y") {      
      rememberProductRecommendation(wrapperItem.data("id"));
    }
  }
});

$(document).on("click", "[data-bigdata] a", function () {
  rememberProductRecommendation($(this).closest("[data-bigdata]").data("id"));
});

