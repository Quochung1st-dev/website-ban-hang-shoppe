var woof_messenger_init = 0;
function woof_init_products_messenger() {   
    if (woof_messenger_init > 0) {
	return;
    }
    woof_messenger_init++;
    jQuery('#woof_add_subscr').attr('data-href', location.href);

    jQuery('#woof_add_subscr').life('click', function () {
	var data = {
	    action: "woof_messenger_add_subscr",
	    user_id: jQuery(this).attr('data-user'),
            get_var: woof_current_values,
	    link: location.href
	};
	jQuery.post(woof_ajaxurl, data, function (content) {
	    // alert(jQuery.parseJSON(content));
	    if (content) {
		var req = content;
		woof_redraw_subscr(req);
	    }
	});

	return false;
    });
    jQuery('.woof_remove_subscr').life('click', function () {
	if (!confirm(woof_confirm_lang)) {
	    return false;
	}
	var data = {
	    action: "woof_messenger_remove_subscr",
	    user_id: jQuery(this).attr('data-user'),
	    key: jQuery(this).attr('data-key')
	};
	// console.log(data);
	jQuery.post(woof_ajaxurl, data, function (content) {

	    var req = jQuery.parseJSON(content);
	    //console.log(req); 
	    jQuery('.woof_subscr_item_' + req.key).remove();
	    woof_check_count_subscr();

	});

	return false;
    });
    function woof_redraw_subscr(data) {
	jQuery('.woof_subscr_list ul').append(data);
	woof_check_count_subscr();
    }

    function woof_check_count_subscr() {
	var count_li = jQuery('.woof_subscr_list ul:first').find('li').size();
	var max_count = jQuery('.woof_add_subscr_cont input').attr('data-count');

	if (count_li >= max_count) {
	    jQuery('.woof_add_subscr_cont').hide();
	} else {
	    jQuery('.woof_add_subscr_cont').show();

	}

    }
}
