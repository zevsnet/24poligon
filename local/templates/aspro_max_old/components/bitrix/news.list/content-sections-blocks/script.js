$(document).ready(function () {
    $(".content-sections2 .body-info .button_opener").on("click", function (e) {
        var _this = $(this),
            slideBlock = _this.closest(".body-info").find(".text.childs"),
            bOpen = slideBlock.is(":visible"),
            btnOpen = _this.find(".opener"),
            dur = bOpen ? 200 : 400,
            func = bOpen ? "slideUp" : "slideDown",
            openText =
                typeof btnOpen.data("open_text") !== "undefined"
                    ? btnOpen.data("open_text")
                    : "",
            closeText =
                typeof btnOpen.data("close_text") !== "undefined"
                    ? btnOpen.data("close_text")
                    : "";

        if (slideBlock.length) {
            slideBlock
                .toggleClass("opened")
                .find(".text-list__element--hidden")
                .toggleClass("active");

            _this.toggleClass("opened");

            if (slideBlock.hasClass("opened")) {
                if (openText.length) {
                    btnOpen.text(openText);
                }
            } else if (!slideBlock.hasClass("opened")) {
                if (closeText.length) {
                    btnOpen.text(closeText);
                }
            }
        }
    });
});
