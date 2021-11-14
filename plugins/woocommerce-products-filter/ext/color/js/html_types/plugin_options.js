jQuery(function ($) {    
    $('.woof_toggle_colors').click(function () {
        $(this).parent().find('ul.woof_color_list').toggle();
    });
});
