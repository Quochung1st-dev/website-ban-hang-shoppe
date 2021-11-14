function woof_init_mselects() {
    try {
        // jQuery("select.woof_select").chosen('destroy').trigger("liszt:updated");
        jQuery("select.woof_mselect").chosen(/*{disable_search_threshold: 10}*/);
    } catch (e) {

    }

    jQuery('.woof_mselect').change(function (a) {
        var slug = jQuery(this).val();
        var name = jQuery(this).attr('name');

        //fix for multiselect if in chosen mode remove options
        if (is_woof_use_chosen) {
            var vals = jQuery(this).chosen().val();
            jQuery('.woof_mselect[name=' + name + '] option:selected').removeAttr("selected");
            jQuery('.woof_mselect[name=' + name + '] option').each(function (i, option) {
                var v = jQuery(this).val();
                if (jQuery.inArray(v, vals) !== -1) {
                    jQuery(this).prop("selected", true);
                }
            });
        }

        woof_mselect_direct_search(name, slug);
        return true;
    });
}

function woof_mselect_direct_search(name, slug) {
    //mode with Filter button
    var values = [];
    jQuery('.woof_mselect[name=' + name + '] option:selected').each(function (i, v) {
        values.push(jQuery(this).val());
    });

    //duplicates removing
    //http://stackoverflow.com/questions/9229645/remove-duplicates-from-javascript-array
    values = values.filter(function (item, pos) {
        return values.indexOf(item) == pos;
    });

    values = values.join(',');
    if (values.length) {
        woof_current_values[name] = values;
    } else {
        delete woof_current_values[name];
    }

    woof_ajax_page_num = 1;
    if (woof_autosubmit) {
        woof_submit_link(woof_get_submit_link());
    }
}


