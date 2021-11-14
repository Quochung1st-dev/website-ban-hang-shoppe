<?php if (!defined('ABSPATH')) die('No direct access allowed'); ?>
<div data-css-class="woof_text_search_container" class="woof_text_search_container woof_container">
    <div class="woof_container_overlay_item"></div>
    <div class="woof_container_inner">
        <?php
        global $WOOF;
        $woof_text = '';
        $request = $WOOF->get_request_data();

        if (isset($request['woof_text']))
        {
            $woof_text = $request['woof_text'];
        }
        //+++
        if (!isset($placeholder))
        {
            $p = __('enter a product title here ...', 'woocommerce-products-filter');
        }

        if (isset($WOOF->settings['by_text']['placeholder']) AND ! isset($placeholder))
        {
            if (!empty($WOOF->settings['by_text']['placeholder']))
            {
                $p = $WOOF->settings['by_text']['placeholder'];
                $p = WOOF_HELPER::wpml_translate(null, $p);
                $p = __($p, 'woocommerce-products-filter');
            }


            if ($WOOF->settings['by_text']['placeholder'] == 'none')
            {
                $p = '';
            }
        }
        //***
        $unique_id = uniqid('woof_text_search_');
        ?>

        <div class="woof_show_text_search_container">
            <img width="36" class="woof_show_text_search_loader" style="display: none;" src="<?php echo $loader_img ?>" alt="loader" />
            <a href="javascript:void(0);" data-uid="<?php echo $unique_id ?>" class="woof_text_search_go <?php echo $unique_id ?>"></a>
            <input type="search" class="woof_show_text_search <?php echo $unique_id ?>" id="<?php echo $unique_id ?>" data-uid="<?php echo $unique_id ?>" data-auto_res_count="<?php echo(isset($auto_res_count) ? $auto_res_count : 0) ?>" data-auto_search_by="<?php echo(isset($auto_search_by) ? $auto_search_by : "") ?>" placeholder="<?php echo(isset($placeholder) ? $placeholder : $p) ?>" name="woof_text" value="<?php echo $woof_text ?>" />
        </div>


    </div>
</div>