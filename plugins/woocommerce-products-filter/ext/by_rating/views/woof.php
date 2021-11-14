<?php
if (!defined('ABSPATH'))
    die('No direct access allowed');

global $WOOF;
if (isset($WOOF->settings['by_rating']) AND $WOOF->settings['by_rating']['show'])
{
    ?>
    <div data-css-class="woof_by_rating_container" class="woof_by_rating_container woof_container">
        <div class="woof_container_overlay_item"></div>
        <div class="woof_container_inner">
            <select class="woof_by_rating_dropdown woof_select" name="min_rating">
                <?php
                $vals = array(
                    0 => __('Filter by rating', 'woocommerce-products-filter'),
                    4 => __('average rating between 4 to 5', 'woocommerce-products-filter'),
                    3 => __('average rating between 3 to 4-', 'woocommerce-products-filter'),
                    2 => __('average rating between 2 to 3-', 'woocommerce-products-filter'),
                    1 => __('average rating between 1 to 2-', 'woocommerce-products-filter')
                );
                $request = $WOOF->get_request_data();
                $selected = $WOOF->is_isset_in_request_data('min_rating') ? $request['min_rating'] : 0;
                ?>
                <?php foreach ($vals as $key => $value): ?>
                    <option <?php echo selected($selected, $key); ?> value="<?php echo $key ?>"><?php echo $value ?></option>
                <?php endforeach; ?>
            </select>
        </div>
    </div>
    <?php
}


