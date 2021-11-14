/**
 * Frontend
 *
 * @author Your Inspiration Themes
 * @package YITH WooCommerce Colors and Labels Variations Premium
 * @version 1.0.0
 */
jQuery(document).ready( function($) {
    "use strict";

    if ( typeof yith_wccl_general === 'undefined' )
        return false;

    var attr = [],
        forms = '';

    $.fn.yith_wccl_select = function ( attr, form ) {

        var t = $(this),
            current_attr = attr[ t.attr('name') ],
            select_box  = t.parent().find( '.select_box' ),
            current_option = [],
            name, type, opt;

        if( typeof current_attr == 'undefined' ) {
            name = decodeURIComponent( t.attr('name') );
            current_attr = attr[ name ];
        }

        type = ( typeof current_attr != 'undefined' ) ? current_attr.type : t.data('type');
        opt  = ( typeof current_attr != 'undefined' ) ? current_attr.terms : false;


        t.addClass('yith_wccl_custom').hide();
        t.closest('.select-wrapper').addClass( 'yith_wccl_is_custom' );

        if( ! select_box.length || ! yith_wccl_general.grey_out ) {

            select_box.remove();

            select_box = $('<div />', {
                'class': 'select_box_' + type + ' select_box ' + t.attr('name')
            }).insertAfter(t);
        }

        t.find( 'option' ).each(function () {

            var option_val = $(this).val();

            if ( ( opt && typeof opt[option_val] != 'undefined' ) || $(this).data('value') ) {

                current_option.push( option_val );

                var classes = 'select_option_' + type + ' select_option',
                    value   = opt && typeof opt[option_val] != 'undefined' ? opt[option_val].value : $(this).data('value'),
                    tooltip = opt && typeof opt[option_val] != 'undefined' ? opt[option_val].tooltip : $(this).data('tooltip'),
                    o       = $(this),
                    option  = select_box.find( '[data-value="' + option_val + '"]');

                // add options
                if( ! option.length ) {

                    // add selected class if is default
                    if ( option_val == t.val() ) {
                        classes += ' selected';
                    }

                    option = $('<div/>', {
                        'class': classes,
                        'data-value': option_val
                    }).appendTo(select_box);

                    // event
                    option.on('click', function (e) {

                        if( $(this).hasClass('inactive') ) {

                            var current_attribute_name = t.data( 'attribute_name' ) || t.attr( 'name' );

                            if( variations_match( form, $(this).data('value'), current_attribute_name ) ) {
                                t.val('').change();
                            }
                            else {
                                // reset variation
                                yith_wccl_reset_form( form, false, [] );
                            }
                        }

                        if ( $(this).hasClass('selected') ) {
                            t.val('').change();
                        }
                        else {
                            t.val( o.val() ).change();
                        }

                        selected_options( $(this) );
                    });

                    // options type
                    if (type == 'colorpicker') {

                        value = value.split(',');

                        if( value.length == 1 ) {
                            option.append($('<span/>', {
                                'class': 'yith_wccl_value',
                                'css': {
                                    'background': value
                                }
                            }));
                        }
                        else {
                            option.append($('<span class="yith_wccl_value"><span class="yith-wccl-bicolor"/></span>') );
                            option.find( '.yith-wccl-bicolor' ).css({
                                'border-bottom-color' : value[0],
                                'border-left-color' : value[1]
                            });
                        }
                    }
                    else if (type == 'image') {
                        option.append($('<img/>', {
                            'class': 'yith_wccl_value',
                            'src': value
                        }));
                    }
                    else if (type == 'label') {
                        option.append($('<span/>', {
                            'class': 'yith_wccl_value',
                            'text': value
                        }));
                    }

                    // add tooltip if any
                    if ( yith_wccl_general.tooltip && typeof tooltip != 'undefined' && tooltip != '') {
                        if( type == 'image' && tooltip.indexOf( '{show_image}' ) != -1 ) {
                            tooltip = tooltip.replace( '{show_image}', '<img src="' + value +'" />' );
                        }
                        
                        yith_wccl_tooltip(option, tooltip);
                    }
                }
            }
        });
        
        select_box.children().each(function () {
            var val = $(this).data('value') + '';

            if ( $.inArray( val, current_option ) == '-1' ) {
                $(this).addClass('inactive');
            }
            else {
                $(this).removeClass('inactive');
            }
        });

        $( document ).trigger( 'yith_wccl_select_initialized', [ t, current_attr ] );
    };

    /**
     * Matches inline variation objects to chosen attributes and return variation
     * @type {Object}
     */
    var variations_match = function( form, value, current_attribute_name ) {
        var match = false,
            product_variations = form.data( 'product_variations' ),
            all_select = form.find( '.variations select' ),
            settings = [];

        // current selected values
        $.each( all_select, function(){
            var attribute_name = $( this ).data( 'attribute_name' ) || $( this ).attr( 'name' );
            if( current_attribute_name == attribute_name ) {
                settings[attribute_name] = value;
            }
            else {
                settings[attribute_name] = $(this).val();
            }
        });

        for ( var i = 0; i < product_variations.length; i++ ) {
            var variation    = product_variations[i];

            // if found matching variation exit
            if( match ) {
                break;
            }

            match = variation;
            for ( var attr_name in variation.attributes ) {
                if ( variation.attributes.hasOwnProperty( attr_name ) ) {
                    var val1 = variation.attributes[ attr_name ],
                        val2 = settings[ attr_name ];

                    if ( val1 != val2 && val1 != '' ) {
                        match = false;
                    }
                }
            }
        }

        return match;
    };

    var yith_wccl_reset_form = function( form, is_init, attr ){

        var select = form.find( '.variations select' );

        if( select.length == 1 && is_init ) {
            form.trigger('woocommerce_update_variation_values');
        }
        else{
            select.val('');
        }

        select.change();

        if( ! is_init ) {
            form.find('div.select_option').removeClass('selected inactive');
            form.trigger('reset_data');
        }
    };

    var yith_wccl_tooltip = function( opt, tooltip ){

        var tooltip_wrapper = $('<span class="yith_wccl_tooltip"></span>'),
            classes         = yith_wccl_general.tooltip_pos + ' ' + yith_wccl_general.tooltip_ani;

        tooltip_wrapper.addClass( classes );

        opt.append( tooltip_wrapper.html( '<span>' + tooltip + '</span>' ) );
    };

    var selected_options = function( option ) {
        option.toggleClass('selected');
        option.siblings().removeClass('selected');
    };

    var yith_wccl_add_cart = function( ev ) {

        ev.preventDefault();

        var b          = $( this ),
            product_id = b.data( 'product_id' ),
            quantity   = b.data( 'quantity' ),
            attr = [],
            $supports_html5_storage = false;

        // get select value
        ev.data.select.each( function(index){
            attr[ index ] = this.name + '=' + this.value;
        });

        // fragment storage
        try {
            $supports_html5_storage = ( 'sessionStorage' in window && window.sessionStorage !== null );

            window.sessionStorage.setItem( 'wc', 'test' );
            window.sessionStorage.removeItem( 'wc' );
        } catch( err ) {
            $supports_html5_storage = false;
        }

        $.ajax({
            url: yith_wccl_general.ajaxurl.toString().replace( '%%endpoint%%', 'yith_wccl_add_to_cart' ),
            type: 'POST',
            data: {
                action: 'yith_wccl_add_to_cart',
                product_id : product_id,
                variation_id : ev.data.variation,
                attr: attr.join('&'),
                quantity: quantity,
                context: 'frontend'
            },
            beforeSend: function(){
                b.addClass( 'loading')
                 .removeClass( 'added' );
            },
            success: function( res ){

                // redirect to product page if some error occurred
                if ( res.error && res.product_url ) {
                    window.location = res.product_url;
                    return;
                }
                // redirect to cart
                if ( yith_wccl_general.cart_redirect ) {
                    window.location = yith_wccl_general.cart_url;
                    return;
                }

                // change button
                b.removeClass('loading')
                    .addClass('added');

                if( ! b.next('.added_to_cart').length ) {
                    b.after(' <a href="' + yith_wccl_general.cart_url + '" class="added_to_cart wc-forward" title="' + yith_wccl_general.view_cart + '">' + yith_wccl_general.view_cart + '</a>');
                }

                // Replace fragments
                if ( res.fragments ) {
                    $.each( res.fragments, function( key, value ) {
                        $( key ).replaceWith( value );
                    });
                }

                if ( $supports_html5_storage ) {
                    sessionStorage.setItem( wc_cart_fragments_params.fragment_name, JSON.stringify( res.fragments ) );
                    sessionStorage.setItem( 'wc_cart_hash', res.cart_hash );
                }

                // trigger refresh also cart page
                $( document ).trigger( 'wc_update_cart' );

                // added to cart
                $( document.body ).trigger( 'added_to_cart', [ res.fragments, res.cart_hash, b ] );
            }
        });

    };

    $.yith_wccl = function( attr ) {

        forms = $( '.variations_form.cart:not(.initialized), .owl-item.cloned .variations_form, form.cart.ywcp_form_loaded' );

        // get attr
        attr = ( typeof yith_wccl != 'undefined' ) ? JSON.parse( yith_wccl.attributes ) : attr;
        // prevent undefined attr error
        if( typeof attr == 'undefined' )
            attr = [];

        forms.each(function () {

            if( attr.length === 0 && ! $(this).hasClass( 'in_loop' ) ) {
                return;
            }

            var form    = $(this),
                // get all form select
                select  = form.find( '.variations select' ),
                // variable for loop page
                found       = false,
                changed     = false,
                wrapper     = form.closest( yith_wccl_general.wrapper_container_shop ).length ? form.closest( yith_wccl_general.wrapper_container_shop ) : form.closest('.product-add-to-cart' ),
                image       = wrapper.find( 'img.wp-post-image' ),
                image_src       = ( image.data('lazy-src') ) ? image.data('lazy-src') : image.attr( 'src' ),
                image_srcset    = ( image.data('lazy-srcset') ) ? image.data('lazy-srcset') : image.attr( 'srcset' ),
                price_html  = wrapper.find( 'span.price' ).clone().wrap('<p>').parent().html(),
                button      = wrapper.find( 'a.product_type_variable' ),
                button_html = button.html(),
                input_qty   = wrapper.find('input.thumbnail-quantity'),
                product_variations    = form.data( 'product_variations' ),
                use_ajax              = product_variations === false,
                init = function (select, start) {

                    var index = select.length;

                    select.each(function () {
                        var current_attr = attr[ this.name ],
                            current_attr_exists = typeof current_attr != 'undefined',
                            type         = $(this).data('type'),
                            is_initialized_form = form.hasClass('initialized'),
                            name;

                        if( ! current_attr_exists ) {
                            // search by decode
                            name = decodeURIComponent( this.name );
                            current_attr = attr[ name ];
                            current_attr_exists = typeof current_attr != 'undefined';
                        }

                        // decrease index
                        --index;

                        if( ! is_initialized_form ) {

                            if( ! start ) {
                                // if is not start assign default
                                $(this).val( $(this).data('default_value') );
                            }
                            else {
                                // store default
                                $(this).attr('data-default_value', $(this).val() );
                            }
                        }

                        if( current_attr_exists || type ) {
                            if (start) {
                                // start process actions
                                if ( ! form.hasClass('in_loop') && ! wrapper.length && yith_wccl_general.description && current_attr.descr ) {

                                    var is_table = $(this).closest('tr').length ? true : false,
                                        descr_html = is_table ? '<tr><td colspan="2">' + current_attr.descr + '</td></tr>' : '<p class="attribute_description">' + current_attr.descr + '</p>';

                                    is_table ? $(this).closest('tr').after(descr_html) : $(this).parent().append(descr_html);
                                }
                            }
                            else {
                                // if isset terms or type apply custom style
                                if ( ( current_attr_exists && current_attr.terms ) || type ) {
                                    $(this).yith_wccl_select(attr, form);
                                }
                            }
                        }

                        if( start && ! index ) {
                            yith_wccl_reset_form(form, true, attr);
                        }

                        if( ! index && ! is_initialized_form ) {
                            form.addClass('initialized');
                            form.fadeIn();
                            // trigger action after form is initialized
                            $( form ).trigger( 'yith_wccl_form_initialized' );
                        }
                    });
                },
                reset_loop_item = function() {
                    // reset image
                    change_loop_image( false );
                    wrapper.find('span.price').replaceWith(price_html);
                    wrapper.find('.ywccl_stock').remove();

                    if( input_qty.length ){
                        input_qty.hide();
                    }

                    button.html( button_html )
                        .off( 'click', yith_wccl_add_cart )
                        .removeClass( 'added' )
                        .next('.added_to_cart').remove();
                },
                change_loop_image = function( variation ) {

                    if( ! variation ) {
                        image.attr( 'src', image_src );
                        image.attr( 'srcset', image_srcset );
                    }
                    else {
                        var var_image = ( typeof variation.image != 'undefined') ? variation.image.src : variation.image_src,
                            var_image_srcset = ( typeof variation.image != 'undefined') ? variation.image.srcset : variation.image_srcset;

                        if( var_image && var_image.length ) {
                            image.attr('src', var_image);
                            image.attr('data-lazy-src', var_image);
                        }
                        if( var_image_srcset && var_image_srcset.length ) {
                            image.attr( 'srcset', var_image_srcset );
                            image.attr( 'data-lazy-srcset', var_image_srcset );
                        }
                    }
                };

            form.on( 'check_variations', function ( ev, data, focus ) {
                if ( ! focus ) {
                    if( found ) {
                        found = false;
                        if( ! use_ajax ) return;
                    }
                    if( changed ) {
                        changed = false;
                        // reset
                        reset_loop_item();
                    }
                }
            });

            form.on( 'woocommerce_update_variation_values', function() {
                init(select, false);
            });

            form.on( 'reset_data', function () {
                if( use_ajax ) {
                    init(select, false);
                }
            });

            form.on( 'found_variation', function (ev, variation) {

                if( use_ajax ) {
                    init(select, false);
                }
                else {
                    select.last().trigger('focusin');
                }

                if ( form.hasClass('in_loop') ) {

                    if( changed ) {
                        // if changed reset to prevent error
                        reset_loop_item();
                    }
                    // found it!
                    found = true;
                    changed = true;

                    var var_price = variation.display_price,
                        var_price_html = var_price ? variation.price_html : '',
                        var_id = variation.variation_id;

                    // change image
                    change_loop_image(variation);

                    // change price
                    if (var_price_html) {
                        wrapper.find('span.price').replaceWith(var_price_html);
                    }

                    // show qty input
                    if (input_qty.length) {
                        input_qty.show();
                    }
                    // change button and add event add to cart
                    if (variation.is_in_stock) {
                        button.html(yith_wccl_general.add_cart);
                        button.off('click').on('click', {variation: var_id, select: select}, yith_wccl_add_cart);
                    }
                    // add availability
                    wrapper.find('span.price').after($(variation.availability_html).addClass('ywccl_stock'));

                    $(document).trigger('ywccl_found_variation_in_loop', [variation]);
                }
            });

            form.on( 'click', '.reset_variations', function(){
                $('.select_option.selected').removeClass('selected');
            });

            if( form.hasClass('in_loop') ) {
                form.parent().on( 'change', function(e) { e.stopPropagation(); });
            }

            // hide input qty if present
            if( input_qty.length ){
                input_qty.hide();
            }

            // start the game
            init( select, true );

            // change image on hover is select is one
            if( select.length == 1 && yith_wccl_general.image_hover ) {
                form.on('mouseenter', '.select_option', function() {
                    var value = $(this).attr("data-value"),
                        attr_name = select.attr('name'),
                        variation = variations_match( form, value, attr_name ); // find variation

                    if( $(this).hasClass('selected') || $(this).siblings().hasClass('selected') ){
                        return;
                    }

                    if( variation ) {
                        if( form.hasClass('in_loop') ) {
                            change_loop_image( variation );
                        }
                        else {
                            form.wc_variations_image_update( variation );
                        }
                    }
                });
                form.on('mouseleave', '.select_option', function() {
                    if( $(this).hasClass('selected') || $(this).siblings().hasClass('selected') ){
                        return;
                    }

                    if( form.hasClass('in_loop') ) {
                        change_loop_image( false );
                    }
                    else {
                        form.wc_variations_image_update( false );
                    }
                });
            }
        });
    };

    // START
    $.yith_wccl( attr );

    // plugin compatibility
    $(document).on( 'yith-wcan-ajax-filtered yith_infs_adding_elem initialized.owl.carousel post-load', function() {
        if( typeof $.yith_wccl != 'undefined' && typeof $.fn.wc_variation_form != 'undefined' ) {
            // not initialized
            $(document).find( '.variations_form:not(.initialized), .owl-item.cloned .variations_form' ).each( function() {
                $(this).wc_variation_form();
            });
            $.yith_wccl(attr);
        }
    });

    // reinit for woocommerce quick view
    $( 'body' ).on( 'quick-view-displayed', function() {
       var attr_qv = $('.pp_woocommerce_quick_view').find('.yith-wccl-data').data('attr');
        if( attr_qv ) {
            $.yith_wccl(attr_qv);
        }
    });
});