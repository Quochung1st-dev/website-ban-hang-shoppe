<?php

if (!defined('ABSPATH'))
    die('No direct access allowed');

abstract class WOOF_EXT {

    public static $includes = array();
    public $type = NULL; //html_type, by_html_type, addon, connector
    public $html_type = NULL; //your custom key here, for applications it is should be folder name!!
    //index in the search query
    public $index = NULL; //for by_html_type only: 'woof_sku' for example. This is key in the link
    public $html_type_dynamic_recount_behavior = 2; //0,1,2
    public $folder_name = NULL;
    //for TAX html_type only
    //price2 -> 0 (default)
    //radio, select -> 1
    //checkbox, mselect -> 2
    public $options = array();
    public $taxonomy_type_additional_options = array(); //select, text
    public static $ext_count = 0; //count of activated extensions in system
    public $woof_settings = array();

    public function __construct()
    {
        $this->woof_settings = get_option('woof_settings', array());


        if (!isset(self::$includes['html_type_objects']))
        {
            self::$includes['html_type_objects'] = array(); //for by_html_type only: by_text, by_sku, by_author
        }

        if (!isset(self::$includes['taxonomy_type_objects']))
        {
            self::$includes['taxonomy_type_objects'] = array(); //for TAX html_type only
        }

        if (!isset(self::$includes['js']))
        {
            self::$includes['js'] = array();
        }

        if (!isset(self::$includes['css']))
        {
            self::$includes['css'] = array();
        }

        if (!isset(self::$includes['js_init_functions']))
        {
            self::$includes['js_init_functions'] = array();
        }

        //$this->init();
        if ($this->type === NULL)
        {
            wp_die('WOOF EXTENSION TYPE SHOULD BE DEFINED!');
        }

        //***
        self::$ext_count++;
    }

    public function get_html_type_view()
    {
        return $this->get_ext_path() . 'views' . DIRECTORY_SEPARATOR . 'woof.php';
    }

    public function print_html_type()
    {
        global $WOOF;
        echo $WOOF->render_html($this->get_html_type_view());
    }

    public static function draw_options($options, $folder_name = '')
    {
        global $WOOF;
        foreach ($options as $key => $value)
        {
            echo $WOOF->render_html(WOOF_PATH . 'views' . DIRECTORY_SEPARATOR . 'ext_options.php', array(
                'options' => $value,
                'key' => $key,
                'woof_settings' => $WOOF->settings
                    )
            );
        }
    }

    public static function is_ext_activated($full_path)
    {
        $woof_settings = get_option('woof_settings', array());
        //***
        $idx1 = md5($full_path); //old system before v.2.1.6
        $idx2 = self::get_ext_idx($full_path);
        $checked1 = $checked2 = FALSE;
        if (isset($woof_settings['activated_extensions']))
        {
            $checked1 = in_array($idx1, (array) $woof_settings['activated_extensions']);
            $checked2 = in_array($idx2, (array) $woof_settings['activated_extensions']);
        }
        //$checked=false;
        if ($checked1 OR $checked2)
        {
            return $idx2;
        }
        return false;
    }

    //new from v.2.1.6
    public static function get_ext_idx($full_path)
    {
        return md5(str_replace(ABSPATH, '', $full_path));
    }

    abstract public function init();

    abstract public function get_ext_path();

    abstract public function get_ext_link();
}
