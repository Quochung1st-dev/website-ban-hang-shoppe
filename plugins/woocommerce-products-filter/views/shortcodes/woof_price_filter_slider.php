<?php if (!defined('ABSPATH')) die('No direct access allowed'); ?>

<?php
wp_enqueue_script('ion.range-slider', WOOF_LINK . 'js/ion.range-slider/js/ion-rangeSlider/ion.rangeSlider.min.js', array('jquery'));
wp_enqueue_style('ion.range-slider', WOOF_LINK . 'js/ion.range-slider/css/ion.rangeSlider.css');
$ion_slider_skin = 'skinNice';
if (isset($this->settings['ion_slider_skin']))
{
    $ion_slider_skin = $this->settings['ion_slider_skin'];
}
wp_enqueue_style('ion.range-slider-skin', WOOF_LINK . 'js/ion.range-slider/css/ion.rangeSlider.' . $ion_slider_skin . '.css');
//***
$request = $this->get_request_data();
$uniqid = uniqid();
$preset_min =WOOF_HELPER::get_min_price();
$preset_max = WOOF_HELPER::get_max_price();

$min_price =$this->is_isset_in_request_data('min_price') ? esc_attr($request['min_price']) : $preset_min;
$max_price = $this->is_isset_in_request_data('max_price') ? esc_attr($request['max_price']) : $preset_max;
//***
if (class_exists('WOOCS'))
{
    $preset_min = apply_filters('woocs_exchange_value', $preset_min);
    $preset_max = apply_filters('woocs_exchange_value', $preset_max);
    $min_price = apply_filters('woocs_exchange_value', $min_price);
    $max_price = apply_filters('woocs_exchange_value', $max_price);
}
//***
$slider_step = 1;
if (isset($this->settings['by_price']['ion_slider_step']))
{
    $slider_step = $this->settings['by_price']['ion_slider_step'];
    if (!$slider_step)
    {
        $slider_step = 1;
    }
}
//***
//esc_attr( str_replace( array( '%1$s', '%2$s' ), array( '%s', '%v' ), get_woocommerce_price_format() ) )
$slider_prefix = '';
$slider_postfix = '';
if (class_exists('WOOCS'))
{
    global $WOOCS;
    $currencies = $WOOCS->get_currencies();
    $currency_pos = 'left';
    if (isset($currencies[$WOOCS->current_currency]))
    {
        $currency_pos = $currencies[$WOOCS->current_currency]['position'];
    }
} else
{
    $currency_pos = get_option('woocommerce_currency_pos');
}
switch ($currency_pos)
{
    case 'left':
        $slider_prefix = get_woocommerce_currency_symbol();
        break;
    case 'left_space':
        $slider_prefix = get_woocommerce_currency_symbol() . ' ';
        break;
    case 'right':
        $slider_postfix = get_woocommerce_currency_symbol();
        break;
    case 'right_space':
        $slider_postfix = ' ' . get_woocommerce_currency_symbol();
        break;

    default:
        break;
}

//***
//https://wordpress.org/support/topic/results-found/
if ($preset_max < $max_price)
{
    $max = $max_price;
} else
{
    $max = $preset_max;
}
if ($preset_min > $min_price)
{
    $min = $min_price;
} else
{
    $min = $preset_min;
}
?>
<input class="woof_range_slider" id="<?php echo $uniqid ?>" data-min="<?php echo $min ?>" data-max="<?php echo $max ?>" data-min-now="<?php echo $min_price ?>" data-max-now="<?php echo $max_price ?>" data-step="<?php echo $slider_step ?>" data-slider-prefix="<?php echo $slider_prefix ?>" data-slider-postfix="<?php echo $slider_postfix ?>" value="" />
