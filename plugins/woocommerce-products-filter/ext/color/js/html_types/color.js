function woof_init_colors() {
    //http://jsfiddle.net/jtbowden/xP2Ns/
    jQuery('.woof_color_term').each(function () {
        //title: jQuery(this).prev('.woof_tooltip_data').html().replace(/(<([^>]+)>)/ig, "")
        var color = jQuery(this).data('color');
        var img = jQuery(this).data('img');

        var bg = '';
        if (img.length > 0) {
            bg = 'background: url(' + img + ') !important';
        } else {
            bg = 'background:' + color + ' !important';
        }

        var span = jQuery('<span style="' + bg + '" class="' + jQuery(this).attr('type') + ' ' + jQuery(this).attr('class') + '" title=""></span>').click(woof_color_do_check).mousedown(woof_color_do_down).mouseup(woof_color_do_up);
        if (jQuery(this).is(':checked')) {
            span.addClass('checked');
        }
        jQuery(this).wrap(span).hide();
        jQuery(this).after('<span class="woof_color_checked"></span>');//for checking
    });

    function woof_color_do_check() {
        var is_checked = false;
        if (jQuery(this).hasClass('checked')) {
            jQuery(this).removeClass('checked');
            jQuery(this).children().prop("checked", false);
        } else {
            jQuery(this).addClass('checked');
            jQuery(this).children().prop("checked", true);
            is_checked = true;
        }

        woof_color_process_data(this, is_checked);
    }

    function woof_color_do_down() {
        jQuery(this).addClass('clicked');
    }

    function woof_color_do_up() {
        jQuery(this).removeClass('clicked');
    }
}

function woof_color_process_data(_this, is_checked) {
    var tax = jQuery(_this).find('input[type=checkbox]').data('tax');
    var name = jQuery(_this).find('input[type=checkbox]').attr('name');
    var term_id = jQuery(_this).find('input[type=checkbox]').data('term-id');
    woof_color_direct_search(term_id, name, tax, is_checked);
}

function woof_color_direct_search(term_id, name, tax, is_checked) {

    var values = '';
    var checked = true;
    if (is_checked) {
        if (tax in woof_current_values) {
            woof_current_values[tax] = woof_current_values[tax] + ',' + name;
        } else {
            woof_current_values[tax] = name;
        }
        checked = true;
    } else {
        values = woof_current_values[tax];
        values = values.split(',');
        var tmp = [];
        jQuery.each(values, function (index, value) {
            if (value != name) {
                tmp.push(value);
            }
        });
        values = tmp;
        if (values.length) {
            woof_current_values[tax] = values.join(',');
        } else {
            delete woof_current_values[tax];
        }
        checked = false;
    }
    jQuery('.woof_color_term_' + term_id).attr('checked', checked);
    woof_ajax_page_num = 1;
    if (woof_autosubmit) {
        woof_submit_link(woof_get_submit_link());
    }
}


