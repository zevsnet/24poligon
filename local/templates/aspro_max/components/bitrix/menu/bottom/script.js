function setClassMobile() {
    if (arAsproOptions["THEME"] && arAsproOptions["THEME"]["COMPACT_FOOTER_MOBILE"] == "Y") {
        if (window.matchMedia("(max-width:767px)").matches) {
            $("footer").addClass("mobile");
            $(".bottom-menu .items>.wrap_compact_mobile").addClass("accordion-body collapse");
            $(".bottom-menu .items>.item.childs").attr("data-toggle", "collapse");
        } else {
            $("footer").removeClass("mobile");
            $(".bottom-menu .items>.wrap_compact_mobile").removeClass("accordion-body collapse");
            $(".bottom-menu .items>.item.childs").removeAttr("data-toggle");
        }
    }
}
readyDOM(function () {
    setClassMobile();
});	
$(window).on('resize', function(){
	setClassMobile();
});	