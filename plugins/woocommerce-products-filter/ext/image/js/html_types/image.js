function woof_init_image() {
    //http://jsfiddle.net/jtbowden/xP2Ns/
    jQuery('.woof_image_term').each(function () {
        //title: jQuery(this).prev('.woof_tooltip_data').html().replace(/(<([^>]+)>)/ig, "")
        var image = jQuery(this).data('image');
        var styles = jQuery(this).data('styles');
        if (image.length > 0) {
            styles += '; background-image: url(' + image + ');';
        } else {
            styles += '; background-color: #ffffff;';
        }

        var span = jQuery('<span style="' + styles + '" class="' + jQuery(this).attr('type') + ' ' + jQuery(this).attr('class') + '" title=""></span>').click(woof_image_do_check).mousedown(woof_image_do_down).mouseup(woof_image_do_up);
        if (jQuery(this).is(':checked')) {
            span.addClass('checked');
        }
        jQuery(this).wrap(span).hide();
        jQuery(this).after('<span class="woof_image_checked"></span>');//for checking
    });

    function woof_image_do_check() {
        var is_checked = false;
        if (jQuery(this).hasClass('checked')) {
            jQuery(this).removeClass('checked');
            jQuery(this).children().prop("checked", false);
        } else {
            jQuery(this).addClass('checked');
            jQuery(this).children().prop("checked", true);
            is_checked = true;
        }

        woof_image_process_data(this, is_checked);
    }

    function woof_image_do_down() {
        jQuery(this).addClass('clicked');
    }

    function woof_image_do_up() {
        jQuery(this).removeClass('clicked');
    }
}

function woof_image_process_data(_this, is_checked) {
    var tax = jQuery(_this).find('input[type=checkbox]').data('tax');
    var name = jQuery(_this).find('input[type=checkbox]').attr('name');
    var term_id = jQuery(_this).find('input[type=checkbox]').data('term-id');
    woof_image_direct_search(term_id, name, tax, is_checked);
}

function woof_image_direct_search(term_id, name, tax, is_checked) {

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
    jQuery('.woof_image_term_' + term_id).attr('checked', checked);
    woof_ajax_page_num = 1;
    if (woof_autosubmit) {
        woof_submit_link(woof_get_submit_link());
    }
}


