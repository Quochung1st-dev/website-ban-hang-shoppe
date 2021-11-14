<?php

// CART

Flatsome_Option::add_section( 'cart-checkout', array(
	'title'       => __( 'Cart', 'flatsome-admin' ),
	'panel' => 'woocommerce',
  'priority'    => 1000
) );


Flatsome_Option::add_field( 'option', array(
  'type'        => 'radio-buttonset',
  'settings'     => 'cart_layout',
  'label'       => __( 'Cart layout', 'flatsome-admin' ),
  'section'     => 'cart-checkout',
  'default'     => '',
  'choices'     => array(
    '' => __( 'Default', 'flatsome-admin' ),
    'simple' => __( 'Simple', 'flatsome-admin' ),
    'focused' => __( 'Focused', 'flatsome-admin' ),
  ),
));


Flatsome_Option::add_field( 'option',  array(
  'type'        => 'checkbox',
  'settings'     => 'cart_sticky_sidebar',
  'label'       => __( 'Sticky sidebar', 'flatsome-admin' ),
  'section'     => 'cart-checkout',
  'default' => 0
));

Flatsome_Option::add_field( 'option', array(
	'type'     => 'checkbox',
	'settings' => 'cart_auto_refresh',
	'label'    => __( 'Auto update on quantity change (NEW)', 'flatsome-admin' ),
	'section'  => 'cart-checkout',
	'default'  => 0,
) );

Flatsome_Option::add_field( 'option',  array(
	'type'        => 'textarea',
	'settings'     => 'html_cart_sidebar',
	'transport' => $transport,
	'label'       => __( 'Cart Sidebar content', 'flatsome-admin' ),
	'help'        => __( 'Enter HTML that will show on bottom of cart sidebar' ),
	'section'     => 'cart-checkout',
	'default'     => '',
));

Flatsome_Option::add_field( 'option',  array(
	'type'        => 'textarea',
	'settings'     => 'html_cart_footer',
	'transport' => $transport,
	'label'       => __( 'After Cart content', 'flatsome-admin' ),
	'help'        => __( 'Enter HTML or Shortcodes that will show after cart here.' ),
	'section'     => 'cart-checkout',
	'default'     => '',
));


// CHECKOUT

Flatsome_Option::add_field( 'option', array(
  'type'        => 'radio-buttonset',
  'settings'     => 'checkout_layout',
  'priority' => 1,
  'label'       => __( 'Checkout layout', 'flatsome-admin' ),
  'section'     => 'woocommerce_checkout',
  'default'     => '',
  'choices'     => array(
    '' => __( 'Default', 'flatsome-admin' ),
    'simple' => __( 'Simple', 'flatsome-admin' ),
    'focused' => __( 'Focused', 'flatsome-admin' ),
  ),
));


if( is_nextend_facebook_login() ){
	Flatsome_Option::add_field( 'option',  array(
		'type'        => 'checkbox',
		'settings'     => 'facebook_login_checkout',
		'label'       => __( 'Social Login Buttons', 'flatsome-admin' ),
		'section'     => 'woocommerce_checkout',
		'default' => 0
	));
}

Flatsome_Option::add_field( 'option',  array(
  'type'        => 'checkbox',
  'settings'     => 'checkout_floating_labels',
  'label'       => __( 'Floating field labels (NEW)', 'flatsome-admin' ),
  'section'     => 'woocommerce_checkout',
  'default' => 0
));

Flatsome_Option::add_field( 'option',  array(
  'type'        => 'checkbox',
  'settings'     => 'checkout_fields_email_first',
  'label'       => __( 'Move E-mail field to first position (NEW)', 'flatsome-admin' ),
  'section'     => 'woocommerce_checkout',
  'default' => 0
));

Flatsome_Option::add_field( 'option',  array(
  'type'        => 'checkbox',
  'settings'     => 'checkout_sticky_sidebar',
  'label'       => __( 'Sticky sidebar (NEW)', 'flatsome-admin' ),
  'section'     => 'woocommerce_checkout',
  'default' => 0
));

Flatsome_Option::add_field( 'option',  array(
	'type'        => 'textarea',
	'settings'     => 'html_checkout_sidebar',
	'transport' => $transport,
	'label'       => __( 'Checkout Sidebar content', 'flatsome-admin' ),
	'help'        => __( 'Enter HTML that will show on bottom of checkout sidebar' ),
	'section'     => 'woocommerce_checkout',
	'default'     => '',
));
