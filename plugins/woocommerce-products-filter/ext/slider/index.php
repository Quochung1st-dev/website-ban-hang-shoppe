<?php

if (!defined('ABSPATH'))
    die('No direct access allowed');

//29-11-2016
//need  $orderby = apply_filters('woof_get_terms_orderby', $taxonomy);
final class WOOF_EXT_SLIDER extends WOOF_EXT {

    public $type = 'html_type';
    public $html_type = 'slider'; //your custom key here
    public $html_type_dynamic_recount_behavior = 'multi';

    public function __construct()
    {
        parent::__construct();
        $this->init();
    }

    public function get_ext_path()
    {
        return plugin_dir_path(__FILE__);
    }

    public function get_ext_link()
    {
        return plugin_dir_url(__FILE__);
    }

    public function woof_add_html_types($types)
    {
        $types[$this->html_type] = __('Slider', 'woocommerce-products-filter');
        return $types;
    }

    public function init()
    {
        add_filter('woof_add_html_types', array($this, 'woof_add_html_types'));
        add_action('wp_head', array($this, 'wp_head'), 999);
        self::$includes['js']['woof_' . $this->html_type . '_html_items'] = $this->get_ext_link() . 'js/html_types/slider.js';
        self::$includes['css']['woof_' . $this->html_type . '_html_items'] = $this->get_ext_link() . 'css/html_types/slider.css';
        self::$includes['js_init_functions'][$this->html_type] = 'woof_init_sliders';


        $this->taxonomy_type_additional_options = array(
            'slider_dynamic_recount' => array(
                'title' => __('Enable dynamic recount', 'woocommerce-products-filter'),
                'tip' => __('Activates dynamic recount for each term in the range. If you have very long range (a lot of terms) this feature will generate additional MySQL queries which will create additional loading on the server!', 'woocommerce-products-filter'),
                'type' => 'select',
                'options' => array(
                    0 => __('No', 'woocommerce-products-filter'),
                    1 => __('Yes', 'woocommerce-products-filter')
                )
            )
        );
    }

    public function wp_head()
    {
        global $WOOF;
        wp_enqueue_script('ion.range-slider', WOOF_LINK . 'js/ion.range-slider/js/ion-rangeSlider/ion.rangeSlider.min.js', array('jquery'));
        wp_enqueue_style('ion.range-slider', WOOF_LINK . 'js/ion.range-slider/css/ion.rangeSlider.css');
        $ion_slider_skin = 'skinNice';
        if (isset($WOOF->settings['ion_slider_skin']))
        {
            $ion_slider_skin = $WOOF->settings['ion_slider_skin'];
        }
        wp_enqueue_style('ion.range-slider-skin', WOOF_LINK . 'js/ion.range-slider/css/ion.rangeSlider.' . $ion_slider_skin . '.css');
    }

}

WOOF_EXT::$includes['taxonomy_type_objects']['slider'] = new WOOF_EXT_SLIDER();
