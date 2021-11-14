<?php
if (!defined('ABSPATH'))
    die('No direct access allowed');

global $WOOF,$wp_query;;
//***
if(is_user_logged_in()){
       $request="";
             if (isset($_REQUEST['woof_wp_query'])AND ! empty($_REQUEST['woof_wp_query'])) {
		$request =$_REQUEST['woof_wp_query']->request;
	    } else {
		$request=$wp_query->request;
	    }
       $WOOF->storage->set_val("woof_pm_request_".get_current_user_id(),  base64_encode($request)); //Save current request
}
             
if ( isset($WOOF->settings['products_messenger']) AND $WOOF->settings['products_messenger']['show']) {
  echo do_shortcode('[woof_products_messenger in_filter=1 ]');
}




