/**
 * woofTabs v1.0.0
 */
;
(function ($, window) {

    'use strict';

    $.fn.woofTabs = function (options) {

	if (!this.length)
	    return;

	return this.each(function () {

	    var $this = $(this);

	    ({
		init: function () {
		    this.tabsNav = $this.children('nav');
		    this.items = $this.children('.content-wrap').children('section');
		    this._show();
		    this._initEvents();
		},
		_initEvents: function () {
		    var self = this;
		    this.tabsNav.on('click', 'a', function (e) {
			e.preventDefault();
			self._show($(this));
		    });
		},
		_show: function (element) {

		    if (element == undefined) {
			this.firsTab = this.tabsNav.find('li').first();
			this.firstSection = this.items.first();

			if (!this.firsTab.hasClass('tab-current')) {
			    this.firsTab.addClass('tab-current');
			}

			if (!this.firstSection.hasClass('content-current')) {
			    this.firstSection.addClass('content-current');
			}
		    }

		    var $this = $(element),
			    $to = $($this.attr('href'));

		    if ($to.length) {
			$this.parent('li').siblings().removeClass().end().addClass('tab-current');
			$to.siblings().removeClass().end().addClass('content-current');
		    }

		}

	    }).init();

	});
    };

})(jQuery, window);


/*	Popup
 /* --------------------------------------------- */

/**
 * woofPopupPrepare v1.0.0
 */
(function ($) {

    $.woof_popup_prepare = function (el, options) {
	this.el = el;
	this.options = $.extend({}, $.woof_popup_prepare.DEFAULTS, options);
	this.init();
    };

    $.woof_popup_prepare.DEFAULTS = {};
    $.woof_popup_prepare.openInstance = [];

    $.woof_popup_prepare.prototype = {
	init: function () {

	    $.woof_popup_prepare.openInstance.unshift(this);

	    var base = this;
	    base.scope = false;
	    base.body = $('body');
	    base.wrap = $('#wpwrap');
	    base.modal = $('<div class="woof-modal woof-style"></div>');
	    base.overlay = $('<div class="woof-modal-backdrop"></div>');
	    base.container = $('.woof-tabs');
	    base.instance = $.woof_popup_prepare.openInstance.length;
	    base.namespace = '.popup_modal_' + base.instance;

	    base.support = {
		touch: Modernizr.touch
	    };
	    base.eventtype = base.support.touch ? 'touchstart' : 'click';
	    base.loadPopup();
	},
	loadPopup: function () {
	    this.container.on(this.eventtype, this.el, $.proxy(function (e) {
		if (!this.scope) {
		    this.body.addClass('woof-noscroll');
		    this.openPopup(e);
		}
		this.scope = true;
	    }, this));
	},
	openPopup: function (e) {
	    e.preventDefault();

	    var base = this,
		    el = $(e.target),
		    data = el.data();

	    if (el.hasClass('js_woof_options')) {
		//for 'by-' items
		var key = data['key'],
			name = data['name'] + ' [' + data['key'] + ']',
			type = false,
			info = $("#woof-modal-content-" + key),
			content = info.html();
	    } else {
		//for taxonomies
		var type = el.parent().find('.woof_select_tax_type').val();
		var key = data['taxonomy'];
		var name = data['taxonomyName'] + ' [' + key + ']';
		var info = $("#woof-modal-content");
		info.find('.woof_option_container').hide();
		info.find('.woof_option_all').show();
		info.find('.woof_option_' + type).show();
		var content = info.html();
	    }

	    base.create_html(key, name, content, info, type);
	    base.add_behavior(key, name, content, info, type);
	},
	create_html: function (key, name, content, info, type) {

	    var base = this,
		    title = name ? '<h3 class="woof-modal-title"> ' + name + '</h3>' : '',
		    loading = ' preloading ',
		    output = '<div class="woof-modal-inner">';
	    output += '<div class="woof-modal-inner-header">' + title + '<a href="javascript:void(0)" class="woof-modal-close"></a></div>';
	    output += '<div class="woof-modal-inner-content ' + loading + '">' + content + '</div>';
	    output += '<div class="woof-modal-inner-footer">';
	    output += '<a href="javascript:void(0)" class="woof-modal-save button button-primary button-large">Apply</a>';
	    output += '</div>';
	    output += '</div>';

	    base.wrap.append(base.modal).append(base.overlay);
	    base.modal.html(output);
	    base.modal.find('.woof-modal-inner-content').removeClass('preloading');

	    var multiplier = base.instance - 1,
		    old = parseInt(base.modal.css('zIndex'), 10);
	    base.modal.css({margin: (30 * multiplier), zIndex: (old + multiplier + 1)});
	    base.overlay.css({zIndex: (old + multiplier)});

	    base.on_load_callback(key, name, content, info, type);
	},
	closeModal: function () {
	    var base = this;

	    $.woof_popup_prepare.openInstance.shift();

	    base.modal.remove();
	    base.overlay.remove();

	    base.body.removeClass('woof-noscroll');
	    base.scope = false;
	},
	add_behavior: function (key, name, content, info, type) {
	    var base = this;

	    base.modal.on(base.eventtype + base.namespace, '.woof-modal-save', function (e) {
		e.preventDefault();
		base.on_close_callback(key, name, content, info, type);
		base.closeModal();
	    });

	    base.modal.on(base.eventtype + base.namespace, '.woof-modal-close', function (e) {
		e.preventDefault();
		base.closeModal();
	    });

	    base.overlay.on(base.eventtype + base.namespace, function (e) {
		e.preventDefault();
		base.closeModal();
	    });

	},
	on_load_callback: function (key, name, content, info, type) {

	    if (type) {

		info.find('.woof_option_container').hide();
		info.find('.woof_option_all').show();
		info.find('.woof_option_' + type).show();

		$.each($('.woof_popup_option', this.modal), function () {
		    var option = $(this).data('option'),
			    val = $('input[name="woof_settings[' + option + '][' + key + ']"]').val();
		    $(this).val(val);
		});

	    } else {

		$.each($('.woof_popup_option', this.modal), function () {
		    var option = $(this).data('option'),
			    val = $('input[name="woof_settings[' + key + '][' + option + ']"]').val();
		    $(this).val(val);
		});

	    }

	},
	on_close_callback: function (key, name, content, info, type) {

	    if (type) {

		$.each($('.woof_popup_option', this.modal), function () {
		    var option = $(this).data('option'), val = $(this).val();
		    $('input[name="woof_settings[' + option + '][' + key + ']"]').val(val);
		});

	    } else {

		$.each($('.woof_popup_option', this.modal), function () {
		    var option = $(this).data('option'), val = $(this).val();
		    $('input[name="woof_settings[' + key + '][' + option + ']"]').val(val);
		});

	    }

	}
    };

})(jQuery);

var woof_sort_order = [];

(function ($) {

    jQuery.fn.life = function (types, data, fn) {
	jQuery(this.context).on(types, this.selector, data, fn);
	return this;
    };

    $.woof_mod = $.woof_mod || {};

    $.woof_mod.popup_prepare = function () {
	new $.woof_popup_prepare('.js_woof_options');
	new $.woof_popup_prepare('.js_woof_add_options');
    };

    $(function () {

	$('.woof-tabs').woofTabs();

	$.woof_mod.popup_prepare();

	try {
	    $('.woof-color-picker').wpColorPicker();
	} catch (e) {
	    console.log(e);
	}

	$("#woof_options").sortable({
	    update: function (event, ui) {
		woof_sort_order = [];
		$.each($('#woof_options').children('li'), function (index, value) {
		    var key = $(this).data('key');
		    woof_sort_order.push(key);
		});
		$('input[name="woof_settings[items_order]"]').val(woof_sort_order.toString());
	    },
	    opacity: 0.8,
	    cursor: "crosshair",
	    handle: '.woof_drag_and_drope',
	    placeholder: 'woof-options-highlight'
	});


	//options saving
	$('#mainform').submit(function () {
	    $('input[name=save]').hide();
	    woof_show_info_popup(woof_lang_saving);
	    var data = {
		action: "woof_save_options",
		formdata: $(this).serialize()
	    };
	    $.post(ajaxurl, data, function () {
		window.location = woof_save_link;
	    });

	    return false;
	});


	$('.woof_reset_order').click(function () {
	    if (prompt('To reset order of items write word "reset". The page will be reloaded!') == 'reset') {
		$('input[name="woof_settings[items_order]"]').val('');
		$('#mainform').submit();
	    }
	});


	$('.js_cache_count_data_clear').click(function () {
	    $(this).next('span').html('clearing ...');
	    var _this = this;
	    var data = {
		action: "woof_cache_count_data_clear"
	    };
	    $.post(ajaxurl, data, function () {
		$(_this).next('span').html('cleared!');
	    });

	    return false;
	});


	$('.js_cache_terms_clear').click(function () {
	    $(this).next('span').html('clearing ...');
	    var _this = this;
	    var data = {
		action: "woof_cache_terms_clear"
	    };
	    $.post(ajaxurl, data, function () {
		$(_this).next('span').html('cleared!');
	    });

	    return false;
	});

	//in extension tab
	$('#woof_manipulate_with_ext').change(function () {
	    var val = parseInt($(this).val(), 10);
	    switch (val) {
		case 1:
		    $('ul.woof_extensions li').hide();
		    $('ul.woof_extensions li.is_enabled').show();
		    break;
		case 2:
		    $('ul.woof_extensions li').hide();
		    $('ul.woof_extensions li.is_disabled').show();
		    break;
		default:
		    $('ul.woof_extensions li').show();
		    break;
	    }
	});

	//***

	jQuery('.woof_select_image').life('click', function ()
	{
	    var input_object = jQuery(this).prev('input[type=text]');
	    window.send_to_editor = function (html)
	    {
		jQuery('#woof_buffer').html(html);
		var imgurl = jQuery('#woof_buffer').find('a').eq(0).attr('href');
		jQuery('#woof_buffer').html("");
		jQuery(input_object).val(imgurl);
		jQuery(input_object).trigger('change');
		tb_remove();
	    };
	    tb_show('', 'media-upload.php?post_id=0&type=image&TB_iframe=true');

	    return false;
	});

	//***

	$('.woof_ext_remove').life('click', function () {
	    if (confirm('Sure?')) {
		woof_show_info_popup('Extension removing ...');
		var _this = this;
		var data = {
		    action: "woof_remove_ext",
		    idx: $(this).data('idx')
		};
		$.post(ajaxurl, data, function () {
		    woof_show_info_popup('Extension is removed!');
		    $(_this).parents('.woof_ext_li').remove();
		    woof_hide_info_popup();
		});
	    }

	    return false;
	});

	//***

	$('#toggle_type').change(function () {
	    if ($(this).val() == 'text') {
		$('.toggle_type_text').show(200);
		$('.toggle_type_image').hide(200);
	    } else {
		$('.toggle_type_image').show(200);
		$('.toggle_type_text').hide(200);
	    }
	});

	//***
	//to avoid logic errors with the count options
	$('#woof_hide_dynamic_empty_pos').change(function () {
	    if ($(this).val() == 1) {
		$('#woof_show_count').val(1);
		$('#woof_show_count_dynamic').val(1);
	    }
	});

	$('#woof_show_count_dynamic').change(function () {
	    if ($(this).val() == 1) {
		$('#woof_show_count').val(1);
	    } else {
		$('#woof_hide_dynamic_empty_pos').val(0);
	    }
	});

	$('#woof_show_count').change(function () {
	    if ($(this).val() == 0) {
		$('#woof_show_count_dynamic').val(0);
		$('#woof_hide_dynamic_empty_pos').val(0);
	    }
	});

	//***


	//loader
	$(".woof-admin-preloader").fadeOut("slow");

    });

})(jQuery);


function woof_show_info_popup(text) {
    jQuery("#woof_html_buffer").text(text);
    jQuery("#woof_html_buffer").fadeTo(333, 0.9);
}

function woof_hide_info_popup() {
    window.setTimeout(function () {
	jQuery("#woof_html_buffer").fadeOut(500);
    }, 333);
}
