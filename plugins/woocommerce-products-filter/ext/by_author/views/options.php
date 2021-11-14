<?php
if (!defined('ABSPATH'))
    die('No direct access allowed');
?>

<li data-key="<?php echo $key ?>" class="woof_options_li">

    <?php
    $show = 0;
    if (isset($woof_settings[$key]['show']))
    {
        $show = $woof_settings[$key]['show'];
    } else
    {
        $show = get_option('woof_show_author_search', 0);
    }
    ?>

    <a href="#" class="help_tip woof_drag_and_drope" data-tip="<?php _e("drag and drope", 'woocommerce-products-filter'); ?>"><img src="<?php echo WOOF_LINK ?>img/move.png" alt="<?php _e("move", 'woocommerce-products-filter'); ?>" /></a>

    <strong style="display: inline-block; width: 176px;"><?php _e("Search by Author", 'woocommerce-products-filter'); ?>:</strong>

    <img class="help_tip" data-tip="<?php _e('Show Search by author drop-down inside woof search form', 'woocommerce-products-filter') ?>" src="<?php echo WP_PLUGIN_URL ?>/woocommerce/assets/images/help.png" height="16" width="16" />

    <div class="select-wrap">
        <select name="woof_settings[<?php echo $key ?>][show]" class="woof_setting_select">
            <option value="0" <?php echo selected($show, 0) ?>><?php _e('No', 'woocommerce-products-filter') ?></option>
            <option value="1" <?php echo selected($show, 1) ?>><?php _e('Yes', 'woocommerce-products-filter') ?></option>
        </select>
    </div>

    <input type="button" value="<?php _e('additional options', 'woocommerce-products-filter') ?>" data-key="<?php echo $key ?>" data-name="<?php _e("Search by Author", 'woocommerce-products-filter'); ?>" class="woof-button js_woof_options js_woof_options_<?php echo $key ?>" />


    <?php
    if (!isset($woof_settings[$key]['placeholder']))
    {
        $woof_settings[$key]['placeholder'] = '';
    }

    if (!isset($woof_settings[$key]['role']))
    {
        $woof_settings[$key]['role'] = 0;
    }
    if (!isset($woof_settings[$key]['view']))
    {
        $woof_settings[$key]['view'] = 'drop-down';
    }
    ?>

    <input type="hidden" name="woof_settings[<?php echo $key ?>][placeholder]" value="<?php echo $woof_settings[$key]['placeholder'] ?>" />
    <input type="hidden" name="woof_settings[<?php echo $key ?>][role]" value="<?php echo $woof_settings[$key]['role'] ?>" />
    <input type="hidden" name="woof_settings[<?php echo $key ?>][view]" value="<?php echo $woof_settings[$key]['view'] ?>" />

    <div id="woof-modal-content-<?php echo $key ?>" style="display: none;">


        <div class="woof-form-element-container">

            <div class="woof-name-description">
                <strong><?php _e('Placeholder text', 'woocommerce-products-filter') ?></strong>
                <span><?php _e('First drop-down option placeholder text OR title for checkboxes', 'woocommerce-products-filter') ?></span>
            </div>

            <div class="woof-form-element">
                <input type="text" class="woof_popup_option" data-option="placeholder" placeholder="" value="" />
            </div>

        </div>


        <div class="woof-form-element-container">

            <div class="woof-name-description">
                <strong><?php _e('Role', 'woocommerce-products-filter') ?></strong>
                <span><?php _e('You can define whith which role show users in the drop down', 'woocommerce-products-filter') ?></span>
            </div>

            <div class="woof-form-element">
                <?php global $wp_roles; ?>
                <div class="select-wrap">
                    <select class="woof_popup_option" data-option="role">
                        <option value="0"><?php _e('all', 'woocommerce-products-filter') ?></option>
                        <?php foreach ($wp_roles->get_names() as $key => $value) : ?>
                            <option value="<?php echo $key; ?>"><?php echo $value; ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>

            </div>

        </div>
        <div class="woof-form-element-container">

                    <div class="woof-name-description">
                        <strong><?php _e('View', 'woocommerce-products-filter') ?></strong>
                        <span><?php _e('View of the search by author ', 'woocommerce-products-filter') ?></span>
                    </div>

                    <div class="woof-form-element">
                        <?php
                        $view = array(
                            'drop-down' => __('Drop-down', 'woocommerce-products-filter'),
                            'checkbox' => __('Checkbox', 'woocommerce-products-filter')
                        );
                        ?>

                        <div class="select-wrap">
                            <select class="woof_popup_option" data-option="view">
                                <?php foreach ($view as $key => $value) : ?>
                                    <option value="<?php echo $key; ?>"><?php echo $value; ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                    </div>

                </div>


    </div>

</li>
