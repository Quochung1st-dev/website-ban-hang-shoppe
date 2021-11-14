<?php

/*
 Plugin Name: WC Custom Add to Cart labels
 Plugin URI: https://profiles.wordpress.org/rynald0s
 Description: This plugin lets you change the "add to cart" labels on single product pages (per product type) and archive / shop page (per product type). 
 Author: Rynaldo Stoltz
 Author URI: https://github.com/rynaldos
 Version: 1.1
 License: GPLv3 or later License
 URI: http://www.gnu.org/licenses/gpl-3.0.html
 */

if ( ! defined( 'ABSPATH' ) ) { 
    exit; // Exit if accessed directly
}

/**
 * Check if WooCommerce is active
 **/

if ( in_array( 'woocommerce/woocommerce.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) ) ) {

/**
 * Add settings
 */

function catcl_section( $sections ) {
    $sections['catcl_section'] = __( 'Add to cart button labels', 'woocommerce' );
    return $sections;
}

add_filter( 'woocommerce_get_sections_products', 'catcl_section' );

function catcl_settings( $settings, $current_section ) {
    
    /**
     * Check the current section is what we want
     **/

    if ( 'catcl_section' === $current_section ) {

        $catcl_settings[] = array( 'title' => __( 'Change the "add to cart" button label on single product pages (per product type)', 'woocommerce' ), 'type' => 'title', 'id' => 'wc_atc_change' );

        $catcl_settings[] = array(
                'title'    => __( 'Simple products', 'woocommerce' ),
                'desc' => 'This will change the "add to cart" label shown on single product page of simple product type',
                'id'       => 'simple_button_text_single',
                'type'     => 'text',
                'placeholder' => 'Add to cart',
                'css'      => 'min-width:350px;',
            );

        $catcl_settings[] = array(
                'title'    => __( 'Grouped products', 'woocommerce' ),
                'desc' => 'This will change the "add to cart" label shown on single product page of grouped product type',
                'id'       => 'grouped_button_text_single',
                'type'     => 'text',
                'placeholder' => 'Add to cart',
                'css'      => 'min-width:350px;',
            );

        $catcl_settings[] = array(
                'title'    => __( 'External products', 'woocommerce' ),
                'desc' => 'This will change the "add to cart" label shown on single product page of external product type',
                'id'       => 'external_button_text_single',
                'type'     => 'text',
                'placeholder' => 'Add to cart',
                'css'      => 'min-width:350px;',
            );

        $catcl_settings[] = array(
                'title'    => __( 'Variable products', 'woocommerce' ),
                'desc' => 'This will change the "add to cart" label shown on single product page of variable product type',
                'id'       => 'variable_button_text_single',
                'type'     => 'text',
                'placeholder' => 'Add to cart',
                'css'      => 'min-width:350px;',
            );

        $catcl_settings[] = array(
                'title'    => __( 'Bookable products', 'woocommerce' ),
                'desc' => 'This will change the "add to cart" label shown on single product page of bookable product type',
                'id'       => 'booking_button_text_single',
                'type'     => 'text',
                'placeholder' => 'Add to cart',
                'css'      => 'min-width:350px;',
            );

        $catcl_settings[] = array( 'type' => 'sectionend', 'id' => 'wc_atc_change' );

        $catcl_settings[] = array( 'title' => __( 'Change the "add to cart" button label on archive / shop page (per product type)', 'woocommerce' ), 'type' => 'title', 'id' => 'wc_atc_change' );

        $catcl_settings[] = array(
                'title'    => __( 'Simple products (archive)', 'woocommerce' ),
                'desc' => 'This will change the "add to cart" label on simple products that are shown on the archive page',
                'id'       => 'simple_button_text',
                'type'     => 'text',
                'placeholder' => 'Add to cart',
                'css'      => 'min-width:350px;',
            );

        $catcl_settings[] = array(
                'title'    => __( 'Grouped products (archive)', 'woocommerce' ),
                'desc' => 'This will change the "add to cart" label on grouped products that are shown on the archive page',
                'id'       => 'grouped_button_text',
                'type'     => 'text',
                'placeholder' => 'Add to cart',
                'css'      => 'min-width:350px;',
            );

        $catcl_settings[] = array(
                'title'    => __( 'External products (archive)', 'woocommerce' ),
                'desc' => 'This will change the "add to cart" label on external products that are shown on the archive page',
                'id'       => 'external_button_text',
                'type'     => 'text',
                'placeholder' => 'Add to cart',
                'css'      => 'min-width:350px;',
            );

        $catcl_settings[] = array(
                'title'    => __( 'Variable products (archive)', 'woocommerce' ),
                'desc' => 'This will change the "add to cart" label on variable products that are shown on the archive page',
                'id'       => 'variable_button_text',
                'type'     => 'text',
                'placeholder' => 'Add to cart',
                'css'      => 'min-width:350px;',
            );

        $catcl_settings[] = array(
                'title'    => __( 'Bookable products (archive)', 'woocommerce' ),
                'desc' => 'This will change the "add to cart" label on bookable products that are shown on the archive page',
                'id'       => 'booking_button_text',
                'type'     => 'text',
                'placeholder' => 'Add to cart',
                'css'      => 'min-width:350px;',
            );

        $catcl_settings[] = array( 'type' => 'sectionend', 'id' => 'wc_atc_change' );
        return $catcl_settings;
} else {
        return $settings;
    }

}

add_filter( 'woocommerce_product_add_to_cart_text' , 'custom_woocommerce_product_add_to_cart_text' );
add_filter( 'woocommerce_product_single_add_to_cart_text', 'custom_woocommerce_product_add_to_cart_text' );
add_filter( 'woocommerce_booking_single_add_to_cart_text', 'custom_woocommerce_product_add_to_cart_text' );

function custom_woocommerce_product_add_to_cart_text() {
    global $product;

    $product_type = $product->get_type();

if (is_product ()) {
    
    switch ( $product_type ) {
        case 'simple':
            return __( $options = catcl_get_settings( 'simple_button_text_single'), 'woocommerce' );
        break;
        case 'grouped':
            return __( $options = catcl_get_settings( 'grouped_button_text_single'), 'woocommerce' );
        break;
        case 'external':
            return __( $options = catcl_get_settings( 'external_button_text_single'), 'woocommerce' );
        break;
        case 'variable':
            return __( $options = catcl_get_settings( 'variable_button_text_single'), 'woocommerce' );
        break;
        case 'booking':
            return __( $options = catcl_get_settings( 'booking_button_text_single'), 'woocommerce-bookings' );
        break;
        default:
            return __( 'Read more', 'woocommerce' );
        } 
    }
else {

    switch ( $product_type ) {
        case 'simple':
            return __( $options = catcl_get_settings( 'simple_button_text'), 'woocommerce' );
        break;
        case 'grouped':
            return __( $options = catcl_get_settings( 'grouped_button_text'), 'woocommerce' );
        break;
        case 'external':
            return __( $options = catcl_get_settings( 'external_button_text'), 'woocommerce' );
        break;
        case 'variable':
            return __( $options = catcl_get_settings( 'variable_button_text'), 'woocommerce' );
        break;
        case 'booking':
            return __( $options = catcl_get_settings( 'booking_button_text'), 'woocommerce-bookings' );
        break;
        default:
            return __( 'Read more', 'woocommerce' );
            }
        } 
    }
}

add_filter( 'woocommerce_get_settings_products','catcl_settings', 10, 2 );

function catcl_get_settings( $key ) {
    $saved = get_option( $key );
    if( $saved && '' != $saved ) {
        return $saved;
    }
    return 'Add to cart';
}
