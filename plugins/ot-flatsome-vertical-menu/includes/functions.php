<?php
/**
 * Created by PhpStorm.
 * User: thinhngo
 * Date: 7/13/2017
 * Time: 9:12 PM
 */

function ot_fl_vm_get_option($key, $default = '')
{
    $options = get_option('ot_vm');
    $option = isset($options[$key]) ? $options[$key] : $default;
    return $option;
}