<?php
if (!defined('ABSPATH'))
    die('No direct access allowed');

global $WOOF;

//***
if (is_user_logged_in() AND isset($WOOF->settings['products_messenger']) ) {

    $subscr_count = 2;
    $p = "";
    $adding_class="";
    if($in_filter!=true){
        $adding_class="products_messenger_shortcode";
    }
    //var_dump($WOOF->settings['products_messenger']);
    if (isset($WOOF->settings['products_messenger'])AND ! empty($WOOF->settings['products_messenger'])) {
	$subscr_count = $WOOF->settings['products_messenger']['subscr_count'];
	$p = $WOOF->settings['products_messenger']['label'];
    }

    $cur_user_id = get_current_user_id();
    //if($cur_user_id==0)return;
    $user_data_mess = get_user_meta($cur_user_id, 'woof_user_messenger', true);
    ?>
    <div data-css-class="woof_products_messenger_container" class="woof_products_messenger_container woof_container <?php echo $adding_class ?>">
        <div class="woof_container_overlay_item"></div>
        <div class="woof_container_inner">
    	<<?php echo apply_filters('woof_title_tag', 'h4'); ?>>
            <?php
		echo WOOF_HELPER::wpml_translate(null, $p);
		?> 
    	</<?php echo apply_filters('woof_title_tag', 'h4'); ?>>
	    <?php
	    if (!is_array($user_data_mess)) {
		$user_data_mess = array();
	    }
	    ?>
    	<div class="woof_subscr_list">
    	    <ul> 
		    <?php
		    $counter = 1;
		    foreach ($user_data_mess as $data) {
			//echo date('D, d M Y H:i:s',$data['date']),"*****",$data['count'],"<br>";
			$data['counter'] = $counter;
			echo $this->render_html(WOOF_EXT_PATH . 'products_messenger/views/item_list_subscr.php', $data);
			$counter++;
		    }
		    ?>
    	    </ul>
    	</div> 
	    <?php
	    $get_array = $WOOF->get_request_data();
            
	    if (!(!$WOOF->settings['products_messenger']['show_btn_subscr'] AND ( empty($get_array) OR count($get_array) <= 1) )) { // hide btn without search query
		$visible = 'none';
		if ($subscr_count > count($user_data_mess)) {
		    $visible = 'block';
		}
		?>
		<div class="woof_add_subscr_cont" style="display: <?php echo $visible ?>" >
		    <input name="add_subscr_messenger" data-count="<?php echo $subscr_count ?>" type="button" id="woof_add_subscr" data-user="<?php echo $cur_user_id ?>" value="<?php _e('Subscribe on the current search request', 'woocommerce-products-filter') ?>"  >
		</div>
        
	    <?php } ?>
            <?php if (isset($this->settings['products_messenger']['notes_for_customer']) AND ! empty($this->settings['products_messenger']['notes_for_customer'])): ?>
                <span class="woof_products_messenger_notes_for_customer"><?php echo do_shortcode($this->settings['products_messenger']['notes_for_customer']) ?></span>
            <?php endif; ?>
        </div>
    </div>
    <?php
}

