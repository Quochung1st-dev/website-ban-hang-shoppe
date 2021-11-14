jQuery(document).ready(function () {
    jQuery(".ot-vm-click #mega-menu-title").click(function () {

        jQuery("#mega_menu").toggleClass("active")

    }),
        jQuery("body").click(function (e) {

            var i = jQuery(e.target);
            "mega-menu-title" != i.attr("id") && jQuery("#mega_menu.active").removeClass("active")

        }),
        jQuery("#mega_menu>li").each(function (e) {

            jQuery(this).children(".sub-menu").css("margin-top", 37 * -e + "px"), jQuery(this).children(".menu-image").css("margin-top", 37 * -e + "px"), jQuery(this).find("li").each(function (e) {
                jQuery(this).children(".menu-image").css("margin-top", 36 * -e + "px")
            })

        })

});