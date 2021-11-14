<?php
if (!defined('ABSPATH'))
    die('No direct access allowed');
?>

<li data-key="<?php echo $key ?>" class="woof_options_li">

    <?php
    $show = 0;
    if (isset($woof_settings[$key]['show']))
    {
        $show = (int) $woof_settings[$key]['show'];
    }
    ?>

    <a href="#" class="help_tip woof_drag_and_drope" data-tip="<?php _e("drag and drope", 'woocommerce-products-filter'); ?>"><img src="<?php echo WOOF_LINK ?>img/move.png" alt="<?php _e("move", 'woocommerce-products-filter'); ?>" /></a>

    <strong style="display: inline-block; width: 176px;"><?php _e("Search by SKU", 'woocommerce-products-filter'); ?>:</strong>

    <img class="help_tip" data-tip="<?php _e('Show textinput for searching by products sku', 'woocommerce-products-filter') ?>" src="<?php echo WP_PLUGIN_URL ?>/woocommerce/assets/images/help.png" height="16" width="16" />

    <div class="select-wrap">
        <select name="woof_settings[<?php echo $key ?>][show]" class="woof_setting_select">
            <option value="0" <?php echo selected($show, 0) ?>><?php _e('No', 'woocommerce-products-filter') ?></option>
            <option value="1" <?php echo selected($show, 1) ?>><?php _e('Yes', 'woocommerce-products-filter') ?></option>
        </select>
    </div>

    <input type="button" value="<?php _e('additional options', 'woocommerce-products-filter') ?>" data-key="<?php echo $key ?>" data-name="<?php _e("Search by SKU", 'woocommerce-products-filter'); ?>" class="woof-button js_woof_options js_woof_options_<?php echo $key ?>" />

    <?php
    if (!isset($woof_settings[$key]['logic']) OR empty($woof_settings[$key]['logic']))
    {
        $woof_settings[$key]['logic'] = 'LIKE';
    }

    if (!isset($woof_settings[$key]['autocomplete']) OR empty($woof_settings[$key]['autocomplete']))
    {
        $woof_settings[$key]['autocomplete'] = 0;
    }
    
     if (!isset($woof_settings[$key]['autocomplete_items']) OR empty($woof_settings[$key]['autocomplete_items']))
    {
        $woof_settings[$key]['autocomplete_items'] = 10;
    }

    if (!isset($woof_settings[$key]['use_for']) OR empty($woof_settings[$key]['use_for']))
    {
        $woof_settings[$key]['use_for'] = 'simple';
    }


    if (!isset($woof_settings[$key]['title']))
    {
        $woof_settings[$key]['title'] = '';
    }

    if (!isset($woof_settings[$key]['placeholder']))
    {
        $woof_settings[$key]['placeholder'] = '';
    }

    if (!isset($woof_settings[$key]['image']))
    {
        $woof_settings[$key]['image'] = '';
    }

    if (!isset($woof_settings[$key]['notes_for_customer']))
    {
        $woof_settings[$key]['notes_for_customer'] = '';
    }
    ?>

    <input type="hidden" name="woof_settings[<?php echo $key ?>][logic]" value="<?php echo $woof_settings[$key]['logic'] ?>" />
    <input type="hidden" name="woof_settings[<?php echo $key ?>][autocomplete]" value="<?php echo $woof_settings[$key]['autocomplete'] ?>" />
    <input type="hidden" name="woof_settings[<?php echo $key ?>][autocomplete_items]" value="<?php echo $woof_settings[$key]['autocomplete_items'] ?>" />
    <input type="hidden" name="woof_settings[<?php echo $key ?>][use_for]" value="<?php echo $woof_settings[$key]['use_for'] ?>" />
    <input type="hidden" name="woof_settings[<?php echo $key ?>][title]" value="<?php echo $woof_settings[$key]['title'] ?>" />
    <input type="hidden" name="woof_settings[<?php echo $key ?>][placeholder]" value="<?php echo $woof_settings[$key]['placeholder'] ?>" />
    <input type="hidden" name="woof_settings[<?php echo $key ?>][image]" value="<?php echo $woof_settings[$key]['image'] ?>" />
    <input type="hidden" name="woof_settings[<?php echo $key ?>][notes_for_customer]" value="<?php echo $woof_settings[$key]['notes_for_customer'] ?>" />

    <div id="woof-modal-content-<?php echo $key ?>" style="display: none;">

        <div style="display: none;">
            <div class="woof-form-element-container">

                <div class="woof-name-description">
                    <strong><?php _e('Title text', 'woocommerce-products-filter') ?></strong>
                    <span><?php _e('Leave it empty if you not need this', 'woocommerce-products-filter') ?></span>
                </div>

                <div class="woof-form-element">
                    <input type="text" class="woof_popup_option" data-option="title" placeholder="" value="" />
                </div>

            </div>
        </div>

        <div class="woof-form-element-container">

            <div class="woof-name-description">
                <strong><?php _e('Placeholder text', 'woocommerce-products-filter') ?></strong>
                <span><?php _e('Leave it empty if you not need this', 'woocommerce-products-filter') ?></span>
                <span><?php _e('SKU textinput placeholder. Set "none" if you want leave it empty on the front.', 'woocommerce-products-filter') ?></span>
            </div>

            <div class="woof-form-element">
                <input type="text" class="woof_popup_option" data-option="placeholder" placeholder="" value="" />
            </div>

        </div>

        <div class="woof-form-element-container">

            <div class="woof-name-description">
                <strong><?php _e('Conditions logic', 'woocommerce-products-filter') ?></strong>
                <span><?php _e('LIKE or Equally', 'woocommerce-products-filter') ?></span>
            </div>

            <div class="woof-form-element">
                <?php
                $logic = array(
                    '=' => __('Exact match', 'woocommerce-products-filter'),
                    'LIKE' => __('LIKE', 'woocommerce-products-filter')
                );
                ?>

                <div class="select-wrap">
                    <select class="woof_popup_option" data-option="logic">
                        <?php foreach ($logic as $key => $value) : ?>
                            <option value="<?php echo $key; ?>"><?php echo $value; ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>

            </div>

        </div>


        <div class="woof-form-element-container">

            <div class="woof-name-description">
                <strong><?php _e('Autocomplete', 'woocommerce-products-filter') ?></strong>
                <span><?php _e('Autocomplete relevant variants in SKU textinput', 'woocommerce-products-filter') ?></span>
            </div>

            <div class="woof-form-element">
                <?php
                $autocomplete = array(
                    0 => __('No', 'woocommerce-products-filter'),
                    1 => __('Yes', 'woocommerce-products-filter')
                );
                ?>

                <div class="select-wrap">
                    <select class="woof_popup_option" data-option="autocomplete">
                        <?php foreach ($autocomplete as $key => $value) : ?>
                            <option value="<?php echo $key; ?>"><?php echo $value; ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>

            </div>

        </div>
	
	
	<div class="woof-form-element-container">

            <div class="woof-name-description">
                <strong><?php _e('Autocomplete products count', 'woocommerce-products-filter') ?></strong>
                <span><?php _e('How many show products in the autocomplete list', 'woocommerce-products-filter') ?></span>
            </div>

            <div class="woof-form-element">
                <input type="text" class="woof_popup_option" data-option="autocomplete_items" placeholder="" value="" />
            </div>

        </div>


        <div class="woof-form-element-container">

            <div class="woof-name-description">
                <strong><?php _e('Use for', 'woocommerce-products-filter') ?></strong>
                <span><?php _e('For which type of products will be realized searching by SKU. Request for variables products creates more mysql queries in database ...', 'woocommerce-products-filter') ?></span>
            </div>

            <div class="woof-form-element">
                <?php
                $use_for = array(
                    'simple' => __('For simple products only', 'woocommerce-products-filter'),
                    'both' => __('For simple and for variables products', 'woocommerce-products-filter')
                );
                ?>

                <div class="select-wrap">
                    <select class="woof_popup_option" data-option="use_for">
                        <?php foreach ($use_for as $key => $value) : ?>
                            <option value="<?php echo $key; ?>"><?php echo $value; ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>

            </div>

        </div>


        <div class="woof-form-element-container">

            <div class="woof-name-description">
                <strong><?php _e('Notes for customer', 'woocommerce-products-filter') ?></strong>
                <span><?php _e('Any notes for customer.<br /><b>Example</b>: use comma for searching by more than 1 SKU!', 'woocommerce-products-filter') ?></span>
            </div>

            <div class="woof-form-element">
                <textarea class="woof_popup_option" data-option="notes_for_customer"></textarea>
            </div>

        </div>

        <div class="woof-form-element-container">

            <div class="woof-name-description">
                <strong><?php _e('Image', 'woocommerce-products-filter') ?></strong>
                <span><?php _e('Image for sku search button which appears near input when users typing there any symbols. Better use png. Size is: 20x20 px.', 'woocommerce-products-filter') ?></span>
                <span><?php _e('Example', 'woocommerce-products-filter') ?>: <?php echo WOOF_LINK ?>img/eye-icon1.png</span>
            </div>

            <div class="woof-form-element">
                <input type="text" class="woof_popup_option" data-option="image" placeholder="" value="" />
                <a href="#" style="margin: 5px 0 0 0; clear: both;" class="woof-button woof_select_image"><?php _e('Select Image', 'woocommerce-products-filter') ?></a>
            </div>

        </div>

    </div>

</li>