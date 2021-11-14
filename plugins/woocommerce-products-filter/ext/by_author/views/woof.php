<?php

if (!defined('ABSPATH'))
    die('No direct access allowed');

global $WOOF;
if (isset($WOOF->settings['by_author']))
{
    if ($WOOF->settings['by_author']['show'])
    {
        $placeholder = '';
        $role = '';
        if (isset($WOOF->settings['by_author']['placeholder']))
        {
            WOOF_HELPER::wpml_translate(null, $WOOF->settings['by_author']['placeholder']);
        }

        if (isset($WOOF->settings['by_author']['role']))
        {
            $role = $WOOF->settings['by_author']['role'];
        }

        echo do_shortcode('[woof_author_filter role="' . $role . '" placeholder="' . $placeholder . '"]');
    }
}

