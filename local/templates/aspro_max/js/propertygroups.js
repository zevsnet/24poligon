$(document).on("click", ".properties-group__hint .icon", function (e) {
    var tooltipWrapp = $(this).closest(".properties-group__hint");

    if (tooltipWrapp.hasClass("active")) {
        tooltipWrapp.removeClass("active").find(".tooltip").slideUp(200);
    } else {
        tooltipWrapp.addClass("active");
        tooltipWrapp.find(".tooltip").slideDown(200);
        tooltipWrapp.find(".tooltip_close").click(function (e) {
            e.stopPropagation();
            tooltipWrapp.removeClass("active").find(".tooltip").slideUp(100);
        });
    }
    e.stopPropagation();
});
$(document).ready(function () {
    $("html, body").on("mousedown", function (e) {
        if (typeof e.target.className == "string") {
            if (!$(e.target).closest(".properties-group__hint.active").length) {
                $(".properties-group__hint.active .icon").trigger("click");
            }
        }
    });
});