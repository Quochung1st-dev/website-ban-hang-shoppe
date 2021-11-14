<li class="woof_subscr_item woof_subscr_item_<?php echo $key ?>">
    <?php
    if (!isset($counter)) {
	$counter = __('new', 'woocommerce-products-filter');
    }
    ?>
    <a class="woof_link_to_subscr"  href="<?php echo $link ?>" target="blank" >#<?php echo $counter ?>.&nbsp;<?php echo $subscr_lang ?></a>
    <p class="woof_tooltip"><span class="woof_tooltip_data"><?php echo $get ?></span>  <span class="woof_icon_subscr"></span></p>   
    <a href="#" class="woof_remove_subscr" data-user="<?php echo $user_id ?>" data-key="<?php echo $key ?>"><img src="<?php echo WOOF_LINK ?>img/delete.png" height="12" width="12" alt="" /></a>
</li>
