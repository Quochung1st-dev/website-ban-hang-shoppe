<?php if (!defined('ABSPATH')) die('No direct access allowed'); ?>
<?php
global $WOOF;
$_REQUEST['additional_taxes'] = $additional_taxes;
//***
$request = $this->get_request_data();
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

        $request = $WOOF->get_request_data();
        if ($WOOF->is_isset_in_request_data($tax_slug))
        {
            $current_request = $request[$tax_slug];
            $current_request = explode(',', urldecode($current_request));
        }
        else{
           $current_request=array();
        }

//***
$terms = apply_filters('woof_sort_terms_before_out', $terms, 'slider');
$values_js = array();
$titles_js = array();
$max = 0;
$all = array();
$sum_count = 0;
if (!empty($terms))
{
    foreach ($terms as $term)
    {
        //excluding hidden terms
        if (in_array($term['term_id'], $hidden_terms))
        {
            continue;
        }
        //***
        //hiding empty marks in the range-slider, not in production
        if (isset($this->settings['slider_dynamic_recount'][$tax_slug]) AND $this->settings['slider_dynamic_recount'][$tax_slug])
        {
            $count = (int) $this->dynamic_count($term, 'multi', $_REQUEST['additional_taxes']);
            if ($count <= 0 AND !in_array($term['slug'], $current_request) )
            {
                continue;
            }
        }
        $sum_count++;
        //***
        $values_js[] = $term['slug'];
        $titles_js[] = $term['name'];
        ?>
        <input type="hidden" value="<?php echo $term['name'] ?>" data-anchor="woof_n_<?php echo $tax_slug ?>_<?php echo $term['slug'] ?>" />
        <?php
    }
}
//***
$max = count($values_js);
//array_walk($values_js, create_function('&$str', '$str = "\"$str\"";'));
$values_js = implode(',', $values_js);
//array_walk($titles_js, create_function('&$str', '$str = "\"$str\"";'));
$titles_js = implode(',', $titles_js);

$current = isset($request[$tax_slug]) ? $request[$tax_slug] : '';
?>

<?php if ($sum_count > 1): ?>
    <input class="woof_taxrange_slider" value='' data-current="<?php echo $current ?>" data-max='<?php echo $max ?>' data-titles='<?php echo $titles_js ?>' data-values='<?php echo $values_js ?>' data-tax="<?php echo $tax_slug ?>" />
<?php else: ?>
    <div class="woof_hide_slider"></div>
<?php endif; ?>

<?php
//we need it only here, and keep it in $_REQUEST for using in function for child items
unset($_REQUEST['additional_taxes']);

