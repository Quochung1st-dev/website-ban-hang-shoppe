jQuery(function ($) {
    $('.woof_toggle_images').click(function () {
        $(this).parent().find('ul.woof_image_list').toggle();
    });
});
