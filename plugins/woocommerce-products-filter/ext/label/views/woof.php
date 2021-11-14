<?php if (!defined('ABSPATH')) die('No direct access allowed'); ?>
<?php
global $WOOF;
$_REQUEST['additional_taxes'] = $additional_taxes;
$_REQUEST['hide_terms_count_txt'] = isset($this->settings['hide_terms_count_txt']) ? $this->settings['hide_terms_count_txt'] : 0;

if (!function_exists('woof_draw_label_childs'))
{

    function woof_draw_label_childs($taxonomy_info, $tax_slug, $childs, $show_count, $show_count_dynamic, $hide_dynamic_empty_pos)
    {
        $do_not_show_childs = (int) apply_filters('woof_terms_where_hidden_childs', $term_id);

        if ($do_not_show_childs == 1)
        {
            return "";
        }

        //***

        $current_request = array();
        global $WOOF;
        $request = $WOOF->get_request_data();
        if ($WOOF->is_isset_in_request_data($tax_slug))
        {
            $current_request = $request[$tax_slug];
            $current_request = explode(',', urldecode($current_request));
        }
        //***
        static $hide_childs = -1;
        if ($hide_childs == -1)
        {
            $hide_childs = (int) get_option('woof_checkboxes_slide');
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

        $childs = apply_filters('woof_sort_terms_before_out', $childs, 'label');
        ?>
        <?php if (!empty($childs)): ?>
            <ul class="woof_childs_list" <?php if ($hide_childs == 1): ?>style="display: none;"<?php endif; ?>>
                <?php foreach ($childs as $term) : $inique_id = uniqid(); ?>
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

                    //excluding hidden terms
                    if (in_array($term['term_id'], $hidden_terms))
                    {
                        continue;
                    }
                    ?>
                    <li <?php if ($WOOF->settings['dispay_in_row'][$tax_slug] AND empty($term['childs'])): ?>style="display: inline-block !important;"<?php endif; ?>>
                        <input type="checkbox" <?php if (!$count AND ! in_array($term['slug'], $current_request) AND $show_count): ?>disabled=""<?php endif; ?> id="<?php echo 'woof_' . $term['term_id'] . '_' . $inique_id ?>" class="woof_label_term" data-tax="<?php echo $tax_slug ?>" name="<?php echo $term['slug'] ?>" value="<?php echo $term['term_id'] ?>" <?php echo checked(in_array($term['slug'], $current_request)) ?> />&nbsp;<label for="<?php echo 'woof_' . $term['term_id'] . '_' . $inique_id ?>" <?php if (checked(in_array($term['slug'], $current_request))): ?>style="font-weight: bold;"<?php endif; ?>><?php
                            if (has_filter('woof_before_term_name'))
                                echo apply_filters('woof_before_term_name', $term, $taxonomy_info);
                            else
                                echo $term['name'];
                            ?> <?php echo $count_string ?></label>
                        <?php
                        if (!empty($term['childs']))
                        {
                            woof_draw_label_childs($taxonomy_info, $tax_slug, $term['childs'], $show_count, $show_count_dynamic, $hide_dynamic_empty_pos);
                        }
                        ?>
                        <input type="hidden" value="<?php echo $term['name'] ?>" data-anchor="woof_n_<?php echo $tax_slug ?>_<?php echo $term['slug'] ?>" />

                    </li>
                <?php endforeach; ?>
            </ul>
        <?php endif; ?>
        <?php
    }

}
?>
<ul class="woof_list woof_list_label">
    <?php
    $woof_tax_values = array();
    $current_request = array();
    $request = $this->get_request_data();
    if ($this->is_isset_in_request_data($tax_slug))
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

    $terms = apply_filters('woof_sort_terms_before_out', $terms, 'label');
    $terms_count_printed = 0;
    $hide_next_term_li = false;
    ?>
    <?php if (!empty($terms)): ?>
        <?php foreach ($terms as $term) : $inique_id = uniqid(); ?>
            <?php
            $count_string = "";
            $count = 0;
            $term_slug = $term['slug'];
            if (!in_array($term_slug, $current_request))
            {
                if ($show_count)
                {
                    if ($show_count_dynamic)
                    {
                        $count = $this->dynamic_count($term, 'multi', $_REQUEST['additional_taxes']);
                    } else
                    {
                        $count = $term['count'];
                    }
                    $count_string = '<span class="woof_label_count">' . $count . '</span>';
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

            //excluding hidden terms
            if (in_array($term['term_id'], $hidden_terms))
            {
                continue;
            }

            if ($not_toggled_terms_count > 0 AND $terms_count_printed === $not_toggled_terms_count)
            {
                $hide_next_term_li = true;
            }

            $checked = in_array($term_slug, $current_request);
            ?>
            <li class="woof_term_<?php echo $term['term_id'] ?> <?php if ($hide_next_term_li): ?>woof_hidden_term<?php endif; ?>" style="<?php if ($this->settings['dispay_in_row'][$tax_slug] AND empty($term['childs'])): ?>display: inline-block !important;<?php endif; ?>">
                <?php echo $count_string ?>
                <span class="checkbox woof_label_term <?php if ($checked) echo 'checked'; ?>">
                    <?php echo $term['name']; ?>
                    <input style="display: none;" type="checkbox" <?php if (!$count AND ! in_array($term_slug, $current_request) AND $show_count): ?>disabled=""<?php endif; ?> id="<?php echo 'woof_' . $term['term_id'] . '_' . $inique_id ?>" class="woof_label_term woof_label_term_<?php echo $term['term_id'] ?>" data-tax="<?php echo $tax_slug ?>" name="<?php echo $term_slug ?>" data-name="<?php echo $term['name'] ?>" data-term-id="<?php echo $term['term_id'] ?>" value="<?php echo $term['term_id'] ?>" <?php echo checked($checked) ?> />
                    <input type="hidden" value="<?php echo $term['name'] ?>" data-anchor="woof_n_<?php echo $tax_slug ?>_<?php echo $term['slug'] ?>" />
                </span>
                <?php
                if (!empty($term['childs']))
                {
                    //woof_draw_label_childs($taxonomy_info, $tax_slug, $term['childs'], $show_count, $show_count_dynamic, $hide_dynamic_empty_pos);
                }
                ?>

            </li>
            <?php
            $terms_count_printed++;
        endforeach;
        ?>


        <?php
        if ($not_toggled_terms_count > 0 AND $terms_count_printed > $not_toggled_terms_count):
            ?>
            <li class="woof_open_hidden_li"><?php WOOF_HELPER::draw_more_less_button('label') ?></li>
            <?php endif; ?>
        <?php endif; ?>
</ul>
<?php
//we need it only here, and keep it in $_REQUEST for using in function for child items
unset($_REQUEST['additional_taxes']);
