function woof_init_sliders() {
    jQuery.each(jQuery('.woof_taxrange_slider'), function (index, input) {
        try {
            var values = [];
            try {
                values = jQuery(input).data('values').split(',');
            } catch (e) {
                console.log(e);
            }
            //***
            var titles = jQuery(input).data('titles').split(',');
            var tax = jQuery(input).data('tax');
            var current = jQuery(input).data('current').split(',');
            var from_index = 0, to_index = titles.length - 1;
            var last = values.length - 1;
            if (current.length > 0) {
                last = current[current.length - 1];
            }
            //console.log(titles);
            //***
            if (jQuery(input).data('current').length > 0 && values.length > 0) {
                jQuery.each(values, function (index, v) {
                    if (v.toLowerCase() == current[0].toLowerCase()) {
                        from_index = index;
                    }
                    if (v.toLowerCase() == current[current.length - 1].toLowerCase()) {
                        to_index = index;
                    }
                });
            } else {
                to_index = parseInt(jQuery(input).data('max'), 10) - 1;
            }
            //***
            jQuery(input).ionRangeSlider({
                //values: values,
                decorate_both: false,
                values_separator: "",
                from: from_index,
                to: to_index,
                min_interval: 1,
                type: 'double',
                prefix: '',
                postfix: '',
                prettify: true,
                hideMinMax: false,
                hideFromTo: false,
                grid: true,
                step: 1,
                onFinish: function (ui) {
                    //*** range
                    woof_current_values[tax] = (values.slice(ui.from, ui.to + 1)).join(',');
                    woof_ajax_page_num = 1;
                    if (woof_autosubmit) {
                        woof_submit_link(woof_get_submit_link());
                    }

                    return false;
                },
                onChange: function (ui) {
                    woof_update_tax_slider(titles, input, ui.from, ui.to);
                }

            });

            woof_update_tax_slider(titles, input, from_index, to_index);

        } catch (e) {

        }
    });
    
    //***
    
    jQuery('.woof_hide_slider').parent('.woof_block_html_items').parent('.woof_container_inner').parent('.woof_container_slider').remove();
}

function woof_update_tax_slider(titles, input, from, to) {
    jQuery(input).prev('span').find('.irs-from').html(titles[from]);
    jQuery(input).prev('span').find('.irs-to').html(titles[to]);
    //***
    jQuery(input).prev('span').find('.irs-min').html(titles[0]);
    jQuery(input).prev('span').find('.irs-max').html(titles[titles.length - 1]);
    for (var i = 0; i < titles.length; i++) {
        jQuery(input).prev('span').find('.js-grid-text-' + i).html(titles[i]);
    }
}


