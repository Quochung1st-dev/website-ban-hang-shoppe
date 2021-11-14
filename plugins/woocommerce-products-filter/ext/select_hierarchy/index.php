<?php

if (!defined('ABSPATH'))
    die('No direct access allowed');

//03-10-2016
final class WOOF_EXT_SELECT_HIERARCHY extends WOOF_EXT {

    public $type = 'html_type';
    public $html_type = 'select_hierarchy'; //your custom key here
    public $html_type_dynamic_recount_behavior = 'single';

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
        $types[$this->html_type] = __('Hierarchy drop-down', 'woocommerce-products-filter');
        return $types;
    }

    public function init()
    {
        add_filter('woof_add_html_types', array($this, 'woof_add_html_types'));
        self::$includes['js']['woof_' . $this->html_type . '_html_items'] = $this->get_ext_link() . 'js/html_types/' . $this->html_type . '.js';
        self::$includes['css']['woof_' . $this->html_type . '_html_items'] = $this->get_ext_link() . 'css/html_types/' . $this->html_type . '.css';
        self::$includes['js_init_functions'][$this->html_type] = 'woof_init_' . $this->html_type;

        $this->taxonomy_type_additional_options = array(
            'show_chain_always' => array(
                'title' => __('Show chain always', 'woocommerce-products-filter'),
                'tip' => __('Allows show disabled drop-downs with its custom name. Necessary changing custom taxonomy label to title like: Country+City+District^My Locations', 'woocommerce-products-filter'),
                'type' => 'select',
                'options' => array(
                    0 => __('No', 'woocommerce-products-filter'),
                    1 => __('Yes', 'woocommerce-products-filter')
                )
            )
        );
    }

}

WOOF_EXT::$includes['taxonomy_type_objects']['select_hierarchy'] = new WOOF_EXT_SELECT_HIERARCHY();
