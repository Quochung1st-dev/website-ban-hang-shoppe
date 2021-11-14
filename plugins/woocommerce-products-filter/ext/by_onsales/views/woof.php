<?php
if (!defined('ABSPATH'))
    die('No direct access allowed');

global $WOOF;
if (isset($WOOF->settings['by_onsales']) AND $WOOF->settings['by_onsales']['show'])
{
    ?>
    <div data-css-class="woof_checkbox_sales_container" class="woof_checkbox_sales_container woof_container">
        <div class="woof_container_overlay_item"></div>
        <div class="woof_container_inner">
            <input type="checkbox" class="woof_checkbox_sales" id="woof_checkbox_sales" name="sales" value="0" <?php checked('salesonly', $WOOF->is_isset_in_request_data('onsales') ? 'salesonly' : '', true) ?> />&nbsp;&nbsp;<label for="woof_checkbox_sales"><?php _e('On sales', 'woocommerce-products-filter') ?></label><br />
        </div>
    </div>
    <?php
}


