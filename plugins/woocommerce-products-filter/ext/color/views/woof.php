<?php if (!defined('ABSPATH')) die('No direct access allowed'); ?>

<?php
global $WOOF;
//http://code.tutsplus.com/articles/how-to-use-wordpress-color-picker-api--wp-33067
$colors = isset($woof_settings['color'][$tax_slug]) ? $woof_settings['color'][$tax_slug] : array();
$colors_imgs = isset($woof_settings['color_img'][$tax_slug]) ? $woof_settings['color_img'][$tax_slug] : array();
$show_count = get_option('woof_show_count', 0);
$show_count_dynamic = get_option('woof_show_count_dynamic', 0);
$hide_dynamic_empty_pos = get_option('woof_hide_dynamic_empty_pos', 0);
$woof_autosubmit = get_option('woof_autosubmit', 0);
//********************
$show_tooltip = (int) $this->settings['show_tooltip'][$tax_slug];
?>

<ul class = "woof_list woof_list_color">
    <?php
    $woof_tax_values = array();
    $current_request = array();
    $request = $WOOF->get_request_data();
    $_REQUEST['additional_taxes'] = $additional_taxes;
    $_REQUEST['hide_terms_count_txt'] = isset($WOOF->settings['hide_terms_count_txt']) ? $WOOF->settings['hide_terms_count_txt'] : 0;

    if ($WOOF->is_isset_in_request_data($tax_slug))
    {
        $current_request = $request[$tax_slug];
        $current_request = explode(',', urldecode($current_request));
    }
//excluding hidden terms
    $hidden_terms = array();
    if (!isset($_REQUEST['woof_shortcode_excluded_terms']))
    {
        if (isset($WOOF->settings['excluded_terms'][$tax_slug]))
        {
            $hidden_terms = explode(',', $WOOF->settings['excluded_terms'][$tax_slug]);
        }
    } else
    {
        $hidden_terms = explode(',', $_REQUEST['woof_shortcode_excluded_terms']);
    }


    //***

    $not_toggled_terms_count = 0;
    if (isset($WOOF->settings['not_toggled_terms_count'][$tax_slug]))
    {
        $not_toggled_terms_count = intval($WOOF->settings['not_toggled_terms_count'][$tax_slug]);
    }
    //***

    $terms = apply_filters('woof_sort_terms_before_out', $terms, 'color');
    $terms_count_printed = 0;
    $hide_next_term_li = false;
    ?>
    <?php if (!empty($terms)): ?>
        <?php foreach ($terms as $term) : $inique_id = uniqid(); ?>
            <?php
            $count_string = "";
            $count = 0;
            if (!in_array($term['slug'], $current_request))
            {
                if ($show_count)
                {
                    if ($show_count_dynamic)
                    {
                        $count = $WOOF->dynamic_count($term, 'multi', $_REQUEST['additional_taxes']);
                    } else
                    {
                        $count = $term['count'];
                    }
                    $count_string = '<span>(' . $count . ')</span>';
                }
                //+++
                if ($hide_dynamic_empty_pos AND $count == 0)
                {
                    continue;
                }
            }

            if ($_REQUEST['hide_terms_count_txt'])
            {
                $count_string = "";
            }

            $color = '#000000';
            if (isset($colors[$term['slug']]))
            {
                $color = $colors[$term['slug']];
            }

            $color_img = '';
            if (isset($colors_imgs[$term['slug']]) AND ! empty($colors_imgs[$term['slug']]))
            {
                $color_img = $colors_imgs[$term['slug']];
            }

            //excluding hidden terms
            if (in_array($term['term_id'], $hidden_terms))
            {
                continue;
            }


            if ($not_toggled_terms_count > 0 AND $terms_count_printed === $not_toggled_terms_count)
            {
                $hide_next_term_li = true;
            }


            $term_desc = strip_tags(term_description($term['term_id'], $term['taxonomy']));
            ?>
            <li class="woof_color_term_<?php echo sanitize_title($color) ?> woof_color_term_<?php echo $term['term_id'] ?> <?php if ($hide_next_term_li): ?>woof_hidden_term<?php endif; ?>">


                <p class="woof_tooltip"><?php if ($show_tooltip): ?><span class="woof_tooltip_data"><?php echo $term['name'] ?> <?php echo $count_string ?><?php echo(!empty($term_desc) ? '<br /><i>' . $term_desc . '</i>' : '') ?></span><?php endif; ?><input type="checkbox" <?php checked(in_array($term['slug'], $current_request)) ?> id="<?php echo 'woof_' . $term['term_id'] . '_' . $inique_id ?>" class="woof_color_term woof_color_term_<?php echo $term['term_id'] ?> <?php if (in_array($term['slug'], $current_request)): ?>checked<?php endif; ?>" data-color="<?php echo $color ?>" data-img="<?php echo $color_img ?>" data-tax="<?php echo $tax_slug ?>" name="<?php echo $term['slug'] ?>" data-term-id="<?php echo $term['term_id'] ?>" value="<?php echo $term['term_id'] ?>" <?php echo checked(in_array($term['slug'], $current_request)) ?> /></p>


                <input type="hidden" value="<?php echo $term['name'] ?>" data-anchor="woof_n_<?php echo $tax_slug ?>_<?php echo $term['slug'] ?>" />
            </li>
            <?php
            $terms_count_printed++;
        endforeach;
        ?>

        <?php
        if ($not_toggled_terms_count > 0 AND $terms_count_printed > $not_toggled_terms_count):
            ?>
            <li class="woof_open_hidden_li"><?php WOOF_HELPER::draw_more_less_button('color') ?></li>
        <?php endif; ?>
    <?php endif; ?>
</ul>
<div style="clear: both;"></div>
<?php
//we need it only here, and keep it in $_REQUEST for using in function for child items
unset($_REQUEST['additional_taxes']);

