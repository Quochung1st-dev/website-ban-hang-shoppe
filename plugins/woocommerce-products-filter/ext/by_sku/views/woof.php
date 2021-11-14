<?php
if (!defined('ABSPATH'))
    die('No direct access allowed');

global $WOOF;
if (isset($WOOF->settings['by_sku']) AND $WOOF->settings['by_sku']['show'])
{
    if (isset($WOOF->settings['by_sku']['title']) AND ! empty($WOOF->settings['by_sku']['title']))
    {
        ?>
        <!-- <<?php echo apply_filters('woof_title_tag', 'h4'); ?>><?php echo $WOOF->settings['by_sku']['title']; ?></<?php echo apply_filters('woof_title_tag', 'h4'); ?>> -->
        <?php
    }
    echo do_shortcode('[woof_sku_filter]');
}

