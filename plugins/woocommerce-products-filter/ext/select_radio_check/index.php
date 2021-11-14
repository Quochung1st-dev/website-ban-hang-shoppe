<?php

if (!defined('ABSPATH'))
    die('No direct access allowed');

//31-10-2016
final class WOOF_EXT_SEL_RADIO_CHECK extends WOOF_EXT {

    public $type = 'html_type';
    public $html_type = 'select_radio_check'; //your custom key here
    public $html_type_dynamic_recount_behavior = 'multi'; //doesnt matter in this extension

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
        $types[$this->html_type] = __('Radio/Checkbox in drop-down', 'woocommerce-products-filter');
        return $types;
    }

    public function init()
    {
        add_filter('woof_add_html_types', array($this, 'woof_add_html_types'));
        self::$includes['js']['woof_' . $this->html_type . '_html_items'] = $this->get_ext_link() . 'js/html_types/' . $this->html_type . '.js';
        self::$includes['css']['woof_' . $this->html_type . '_html_items'] = $this->get_ext_link() . 'css/html_types/' . $this->html_type . '.css';
        self::$includes['js_init_functions'][$this->html_type] = 'woof_init_' . $this->html_type;


        $this->taxonomy_type_additional_options = array(
            'select_radio_check_type' => array(
                'title' => __('Type', 'woocommerce-products-filter'),
                'tip' => __('How to display this filter-element on the site frontend', 'woocommerce-products-filter'),
                'type' => 'select',
                'options' => array(
                    0 => __('Radio', 'woocommerce-products-filter'),
                    1 => __('Checkbox', 'woocommerce-products-filter')
                )
            ),
            'select_radio_check_height' => array(
                'title' => __('Height', 'woocommerce-products-filter'),
                'tip' => __('Height of the drop-down of the opened active panel.', 'woocommerce-products-filter'),
                'type' => 'text',
                'placeholder' => __('enter value in px', 'woocommerce-products-filter'),
            )
        );
    }

}

WOOF_EXT::$includes['taxonomy_type_objects']['select_radio_check'] = new WOOF_EXT_SEL_RADIO_CHECK();

