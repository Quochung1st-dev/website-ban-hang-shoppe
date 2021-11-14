<?php
if (!defined('ABSPATH'))
    die('No direct access allowed');

//0 - radio, 1 - checkbox
$select_radio_check_type = (int) $this->settings['select_radio_check_type'][$tax_slug];
$select_radio_check_height = (int) $this->settings['select_radio_check_height'][$tax_slug];
?>

<dl class="woof_select_radio_check">

    <dt>
        <a href="javascript: void(0);" class="woof_select_radio_check_opener">
            <span class="woof_hida woof_hida_<?php echo $tax_slug ?>" data-title="<?php echo WOOF_HELPER::wpml_translate($taxonomy_info) ?>"><?php echo WOOF_HELPER::wpml_translate($taxonomy_info) ?></span>    
            <p class="woof_multiSel"></p>  
        </a>
    </dt>

    <dd>
        <div class="woof_mutliSelect woof_no_close_childs" data-height="<?php echo $select_radio_check_height ?>">
            <?php
            $args = array();
            $args['taxonomy_info'] = $taxonomies_info[$tax_slug];
            $args['tax_slug'] = $tax_slug;
            $args['terms'] = $terms;
            $args['additional_taxes'] = '';
            if (isset($additional_taxes))
            {
                $args['additional_taxes'] = $additional_taxes;
            }
            //***

            $args['woof_settings'] = get_option('woof_settings', array());
            $args['show_count'] = get_option('woof_show_count', 0);
            $args['show_count_dynamic'] = get_option('woof_show_count_dynamic', 0);
            $args['hide_dynamic_empty_pos'] = get_option('woof_hide_dynamic_empty_pos', 0);

            if ($select_radio_check_type)
            {
                echo $this->render_html(WOOF_PATH . 'views/html_types/checkbox.php', $args);
            } else
            {
                echo $this->render_html(WOOF_PATH . 'views/html_types/radio.php', $args);
            }
            ?>            
        </div>
    </dd>
</dl>
