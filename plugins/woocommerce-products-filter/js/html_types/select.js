function woof_init_selects() {
    if (is_woof_use_chosen) {
        try {
            // jQuery("select.woof_select").chosen('destroy').trigger("liszt:updated");
            jQuery("select.woof_select, select.woof_price_filter_dropdown").chosen(/*{disable_search_threshold: 10}*/);
        } catch (e) {

        }
    }

    jQuery('.woof_select').change(function () {
        var slug = jQuery(this).val();
        var name = jQuery(this).attr('name');
        woof_select_direct_search(this, name, slug);
    });
}

function woof_select_direct_search(_this, name, slug) {

    jQuery.each(woof_current_values, function (index, value) {
        if (index == name) {
            delete woof_current_values[name];
            return;
        }
    });

    if (slug != 0) {
        woof_current_values[name] = slug;
    }

    woof_ajax_page_num = 1;
    if (woof_autosubmit || jQuery(_this).within('.woof').length == 0) {
        woof_submit_link(woof_get_submit_link());
    }

}


