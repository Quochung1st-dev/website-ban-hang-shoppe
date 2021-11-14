var woof_stat_data = new Array();
var woof_operative_tables = null;
//***
jQuery(function ($) {
    woof_stat_init_calendars();

    //under dev
    $('#woof_stat_snippet').change(function () {
        var taxonomies = $(this).val();
        if (taxonomies !== null && taxonomies.length > 0) {

            $.each(taxonomies, function (i, slug) {
                var id = 'woof_stat_snippet_' + slug;
                if (!$('#' + id).length) {
                    $('#woof_stat_snippets_tags').prepend('<li id="' + id + '" data-slug="' + slug + '"><label>' + slug + ' terms:</label><br /><input type="text" placeholder="' + woof_stat_vars.woof_stat_leave_empty + '" /></li>');
                }
            });

            //removing term inputs
            $.each($('#woof_stat_snippets_tags li'), function (i, li) {
                var slug = $(li).data('slug');
                if ($.inArray(slug, taxonomies) == -1) {
                    $(li).remove();
                }
            });

        } else {
            $('#woof_stat_snippets_tags').html("");
        }
    });
});

function woof_stat_get_request_snippets() {
    //*** assemble request_snippets
    var request_snippets = {};
    jQuery.each(jQuery('#woof_stat_snippets_tags li'), function (i, li) {
        var slug = jQuery(li).data('slug');
        var terms = jQuery(li).find('input').val();
        request_snippets[slug] = terms;
    });

    return request_snippets;
}

function woof_stat_calculate() {

    var calendar_from = parseInt(jQuery('#woof_stat_calendar_from').val(), 10);
    var calendar_to = parseInt(jQuery('#woof_stat_calendar_to').val(), 10);
    var request_snippets = woof_stat_get_request_snippets();

    jQuery('#chart_div_1').html("");
    jQuery('#chart_div_1_set').html("");
    jQuery('#woof_stat_print_btn').hide();

    if (calendar_from == 0 || calendar_to == 0) {
        alert(woof_stat_vars.woof_stat_sel_date_range);
        return false;
    }

    if (Object.keys(request_snippets).length === 0) {
        //alert('Select statistical parameters!');
        //return false;
        //as selected no one taxonomy will be shown top of terms
    }

    woof_stat_data = new Array();
    woof_show_info_popup(woof_stat_vars.woof_stat_calc);
    jQuery('#woof_stat_get_monitor').html("");
    woof_stat_process_monitor(woof_stat_vars.woof_stat_get_oper_tbls);
    var data = {
        action: "woof_get_operative_tables",
        calendar_from: calendar_from,
        calendar_to: calendar_to
    };
    jQuery.post(ajaxurl, data, function (tables) {
        tables = jQuery.parseJSON(tables);
        if (tables.length > 0) {
            woof_stat_process_monitor(woof_stat_vars.woof_stat_oper_tbls_prep);
            if (tables.length) {
                woof_stat_request_tables_data(0, tables);
            }
        } else {
            woof_hide_info_popup();
            woof_stat_process_monitor(woof_stat_vars.woof_stat_done);
            alert(woof_stat_vars.woof_stat_no_data);
        }
    });

    return false;
}

function woof_stat_request_tables_data(index, tables) {
    var calendar_from = parseInt(jQuery('#woof_stat_calendar_from').val(), 10);
    var calendar_to = parseInt(jQuery('#woof_stat_calendar_to').val(), 10);

    //console.log(index);
    woof_stat_process_monitor(woof_stat_vars.woof_stat_getting_dftbls + ' ' + tables[index] + ' ...');
    var data = {
        action: "woof_get_stat_data",
        table: tables[index],
        request_snippets: woof_stat_get_request_snippets(),
        calendar_from: calendar_from,
        calendar_to: calendar_to
    };
    jQuery.post(ajaxurl, data, function (stat_data) {
        stat_data = jQuery.parseJSON(stat_data);
        woof_stat_data.push(stat_data);
        //+++
        if ((index + 1) < tables.length) {
            woof_stat_request_tables_data(index + 1, tables);
        } else {
            if (Object.keys(woof_stat_get_request_snippets()).length === 0) {
                var data = {
                    action: "woof_get_top_terms",
                    woof_stat_data: woof_stat_data
                };
                jQuery.post(ajaxurl, data, function (stat_data) {
                    woof_stat_data = jQuery.parseJSON(stat_data);
                    woof_hide_info_popup();
                    woof_stat_process_monitor(woof_stat_vars.woof_stat_done);
                    woof_stat_draw_graphs();
                });
            } else {
                woof_hide_info_popup();
                woof_stat_process_monitor(woof_stat_vars.woof_stat_done);
                woof_stat_draw_graphs();
            }
        }
    });
}


function woof_stat_process_monitor(text) {
    jQuery('#woof_stat_get_monitor').prepend('<li>' + text + '</li>');
}

function woof_stat_init_calendars() {
    jQuery(".woof_stat_calendar").datepicker(
            {
                showWeek: true,
                firstDay: woof_stat_vars.week_first_day,
                changeMonth: true,
                changeYear: true,
                showButtonPanel: true,
                maxDate: 'today',
                //maxDate: new Date(2017, 11 - 1, 30), //comment it, for tests only
                onSelect: function (selectedDate, self) {
                    var date = new Date(parseInt(self.currentYear, 10), parseInt(self.currentMonth, 10), parseInt(self.currentDay, 10), 23, 59, 59);
                    var mktime = (date.getTime() / 1000);
                    var css_class = 'woof_stat_calendar_from';
                    if (jQuery(this).hasClass('woof_stat_calendar_from')) {
                        css_class = 'woof_stat_calendar_to';
                        jQuery(this).parent().find('.' + css_class).datepicker("option", "minDate", selectedDate);
                    } else {
                        jQuery(this).parent().find('.' + css_class).datepicker("option", "maxDate", selectedDate);
                    }
                    jQuery(this).prev('input[type=hidden]').val(mktime);
                }
            }
    );
    jQuery(".woof_stat_calendar").datepicker("option", "minDate", new Date(woof_stat_vars.min_year, woof_stat_vars.min_month - 1, 1));
    jQuery(".woof_stat_calendar").datepicker("option", "dateFormat", woof_stat_vars.calendar_date_format);
    jQuery(".woof_stat_calendar").datepicker("option", "showAnim", 'fadeIn');
    //+++
    jQuery(".woof_stat_calendar").life('keyup', function (e) {
        if (e.keyCode == 8 || e.keyCode == 46) {
            jQuery.datepicker._clearDate(this);
            jQuery(this).prev('input[type=hidden]').val("");
        }
    });

    jQuery(".woof_stat_calendar").each(function () {
        var mktime = parseInt(jQuery(this).prev('input[type=hidden]').val(), 10);
        if (mktime > 0) {
            var date = new Date(mktime * 1000);
            jQuery(this).datepicker('setDate', new Date(date));
        }
    });

}

