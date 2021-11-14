<?php if (!defined('ABSPATH')) die('No direct access allowed'); ?>
<?php
if (!isset($additional_taxes)) {
    $additional_taxes = '';
}
$price2_filter_data = WOOF_HELPER::get_price2_filter_data($additional_taxes);
$price_filter2_1opt_txt = __('filter by price', 'woocommerce-products-filter');
if (isset($this->settings['by_price']['first_option_text'])) {
    if (!empty($this->settings['by_price']['first_option_text'])) {
	$price_filter2_1opt_txt = WOOF_HELPER::wpml_translate(null, $this->settings['by_price']['first_option_text']);
    }
}

if (isset($placeholder)) {
    $price_filter2_1opt_txt = WOOF_HELPER::wpml_translate(null, $placeholder);
}

$show_count = get_option('woof_show_count', 0);
$show_count_dynamic = get_option('woof_show_count_dynamic', 0);
$hide_dynamic_empty_pos = get_option('woof_hide_dynamic_empty_pos', 0);
?>

<<?php echo apply_filters('woof_title_tag', 'h4'); ?>><?php echo $price_filter2_1opt_txt ?></<?php echo apply_filters('woof_title_tag', 'h4'); ?>>
<div data-css-class="woof_price_filter_radio_container" class="woof_checkbox_authors_container woof_container">
    <div class="woof_container_overlay_item"></div>
    <div class="woof_container_inner">
	<ul class='woof_authors '>
<?php foreach ($price2_filter_data['ranges']['options'] as $k => $value): $value = trim($value); ?>
    <?php
    $c = 0;
    $cs = '';
    if ($show_count) {
	$c = (int) $price2_filter_data['ranges']['count'][$k];
	$cs = '(' . $c . ')';
    }

    if ($show_count_dynamic AND $c == 0) {
	if ($hide_dynamic_empty_pos) {
	    continue;
	}
    }
    //***
    
    $unique_id= uniqid('wr_');    
    ?>
    	    <li class="woof_list">
    		<input type="radio" <?php if ($c == 0 AND $show_count): ?>disabled=""<?php endif; ?> class="woof_price_filter_radio"  <?php echo checked($price2_filter_data['selected'], $k); ?>  name="woof_price_radio" id="woof_price_radio_<?php echo $unique_id ?>" value="<?php echo $k ?>"  />
    		&nbsp;&nbsp;<label for="woof_price_radio_<?php echo $unique_id ?>"><?php echo($value) ?> <?php echo $cs ?>  </label>
    		<a href="" data-tax="price" style="display: none;" class="woof_radio_price_reset <?php if ($price2_filter_data['selected'] == $k): ?> woof_radio_term_reset_visible <?php endif; ?> woof_radio_term_reset_<?php echo $k ?>"><img src="<?php echo WOOF_LINK ?>img/delete.png" height="12" width="12" alt="" /></a>
    	    </li>
	    <?php endforeach; ?>
	</ul>
    </div>
</div>


<?php


