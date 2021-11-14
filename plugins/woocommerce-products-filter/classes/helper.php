<?php
if (!defined('ABSPATH'))
    die('No direct access allowed');

final class WOOF_HELPER {

    static $notices = array();

    //log test data while makes debbuging
    public static function log($string) {
	$handle = fopen(WOOF_PATH . 'log.txt', 'a+');
	$string .= PHP_EOL;
	fwrite($handle, $string);
	fclose($handle);
    }

    public static function get_var_size(&$var) {
	if (is_string($var)) {
	    return strlen($var);
	} elseif (is_array($var)) {
	    $size = 0;
	    foreach ($var as $k => $v) {
		$size += strlen($k) + getsize($v);
	    }
	    return $size;
	} else {
	    return 1;
	}
    }

    public static function escape($value) {
	return sanitize_text_field(esc_html($value));
    }

    public static function parse_ext_data($file_path) {
	$info = array();
	if (is_file($file_path)) {
	    if (function_exists('parse_ini_file')) {
		$info = parse_ini_file($file_path);
	    }

	    if (empty($info)) {
		$array = file($file_path);
		foreach ($array as $val) {
		    if (preg_match("#^([^=]*)=([^=]*)$#isU", $val)) {
			list($key, $value) = explode("=", trim($val));
			$info[trim($key)] = trim($value, ' "');
		    }
		}
	    }

	    //print_r($info);
	}
	return $info;
    }

    //for multi language strtolower
    //http://stackoverflow.com/questions/13288785/mb-strtolower-and-utf8-strings
    public static function strtolower($string) {
	if (function_exists('mb_strtolower')) {
	    $string = mb_strtolower($string, 'UTF-8');
	} else {
	    $string = strtolower($string);
	}

	return $string;
    }

    public static function get_terms($taxonomy, $hide_empty = true, $get_childs = true, $selected = 0, $category_parent = 0) {
	static $collector = array();
	global $WOOF;

	$lang_key = ""; //WPML compatibility
	if (class_exists('SitePress')) {
	    $lang_key = ICL_LANGUAGE_CODE;
	}

	if (isset($collector[$taxonomy])) {
	    return $collector[$taxonomy];
	}

	//***
	if (isset($WOOF->settings['cache_terms']) AND $WOOF->settings['cache_terms'] == 1) {
	    $cache_key = 'woof_terms_cache_' . md5($taxonomy . '-' . (int) $hide_empty . '-' . (int) $get_childs . '-' . (int) $selected . '-' . (int) $category_parent . '-' . $lang_key);
	    if (false !== ( $cats = get_transient($cache_key) )) {
		return $cats;
	    }
	}
	//***    

	$args = array(
	    //'orderby' => $orderby,
	    //'order' => $order,
	    'style' => 'list',
	    'show_count' => 0,
	    'hide_empty' => $hide_empty,
	    'use_desc_for_title' => 1,
	    'child_of' => 0,
	    'hierarchical' => true,
	    'title_li' => '',
	    'show_option_none' => '',
	    'number' => '',
	    'echo' => 0,
	    'depth' => 0,
	    'current_category' => $selected,
	    'pad_counts' => 0,
	    'taxonomy' => $taxonomy,
	    'walker' => 'Walker_Category');

	//***

	$orderby = apply_filters('woof_get_terms_orderby', $taxonomy);
	if (!empty($orderby)) {
	    if ($orderby != $taxonomy) {
		$args['orderby'] = $orderby;
	    }
	}
	$order = apply_filters('woof_get_terms_order', $taxonomy, $orderby);
	if (!empty($order)) {
	    if ($order != $taxonomy) {
		$args['order'] = $order;
	    }
	}

	//WPML compatibility
	if (class_exists('SitePress')) {
	    $args['lang'] = ICL_LANGUAGE_CODE;
	}

	$cats_objects = get_categories($args);

	$cats = array();
	if (!empty($cats_objects)) {
	    foreach ($cats_objects as $value) {
		if (is_object($value) AND $value->category_parent == $category_parent) {
		    $cats[$value->term_id] = array();
		    $cats[$value->term_id]['term_id'] = $value->term_id;

		    if (isset($WOOF->settings['non_latin_mode']) AND $WOOF->settings['non_latin_mode'] == 1) {
			$cats[$value->term_id]['slug'] = urldecode($value->slug);
			$cats[$value->term_id]['taxonomy'] = urldecode($value->taxonomy);
		    } else {
			$cats[$value->term_id]['slug'] = $value->slug;
			$cats[$value->term_id]['taxonomy'] = $value->taxonomy;
		    }


		    $cats[$value->term_id]['name'] = $value->name;
		    $cats[$value->term_id]['count'] = $value->count;
		    $cats[$value->term_id]['parent'] = $value->parent;
		    if ($get_childs) {
			$cats[$value->term_id]['childs'] = self::assemble_terms_childs($cats_objects, $value->term_id);
		    }
		}
	    }
	}

	//***
	if (isset($WOOF->settings['cache_terms']) AND $WOOF->settings['cache_terms'] == 1) {
	    $period = 0;

	    $periods = array(
		0 => 0,
		'hourly' => HOUR_IN_SECONDS,
		'twicedaily' => 12 * HOUR_IN_SECONDS,
		'daily' => DAY_IN_SECONDS,
		'days2' => 2 * DAY_IN_SECONDS,
		'days3' => 3 * DAY_IN_SECONDS,
		'days4' => 4 * DAY_IN_SECONDS,
		'days5' => 5 * DAY_IN_SECONDS,
		'days6' => 6 * DAY_IN_SECONDS,
		'days7' => 7 * DAY_IN_SECONDS
	    );

	    if (isset($WOOF->settings['cache_terms_auto_clean'])) {
		$period = $WOOF->settings['cache_terms_auto_clean'];

		if (!$period) {
		    $period = 0;
		}
	    }

	    set_transient($cache_key, $cats, $periods[$period]);
	}
	//***

	$collector[$taxonomy] = $cats;
	return $cats;
    }

    //just for get_terms
    private static function assemble_terms_childs($cats_objects, $parent_id) {
	global $WOOF;
	$res = array();
	foreach ($cats_objects as $value) {
	    if ($value->category_parent == $parent_id) {
		$res[$value->term_id]['term_id'] = $value->term_id;
		$res[$value->term_id]['name'] = $value->name;
		if (isset($WOOF->settings['non_latin_mode']) AND $WOOF->settings['non_latin_mode'] == 1) {
		    $res[$value->term_id]['slug'] = urldecode($value->slug);
		    $res[$value->term_id]['taxonomy'] = urldecode($value->taxonomy);
		} else {
		    $res[$value->term_id]['slug'] = $value->slug;
		    $res[$value->term_id]['taxonomy'] = $value->taxonomy;
		}
		$res[$value->term_id]['count'] = $value->count;
		$res[$value->term_id]['parent'] = $value->parent;
		$res[$value->term_id]['childs'] = self::assemble_terms_childs($cats_objects, $value->term_id);
	    }
	}

	return $res;
    }

    //https://wordpress.org/support/topic/translated-label-with-wpml
    //for taxonomies labels translations
    public static function wpml_translate($taxonomy_info, $string = '', $index = -1) {
	global $WOOF;

	if (empty($string)) {
	    if (is_object($taxonomy_info)) {
		$string = $WOOF->settings['custom_tax_label'][$taxonomy_info->name];
	    }
	}


	if (empty($string)) {
	    if (is_object($taxonomy_info)) {
		$string = $taxonomy_info->label;
	    }
	}


	//***
	$check_for_custom_label = false;
	if (class_exists('SitePress')) {
	    $lang = ICL_LANGUAGE_CODE;
	    $woof_settings = get_option('woof_settings');
	    if (isset($woof_settings['wpml_tax_labels']) AND ! empty($woof_settings['wpml_tax_labels'])) {
		$translations = $woof_settings['wpml_tax_labels'];
		//$translations = unserialize($translations);
		/*
		  $translations = array(
		  'es' => array(
		  'Locations' => 'Ubicaciones',
		  'Size' => 'Tamaño'
		  ),
		  'de' => array(
		  'Locations' => 'Lage',
		  'Size' => 'Größe'
		  ),
		  );
		 */

		if (isset($translations[$lang])) {
		    if (isset($translations[$lang][$string])) {
			$string = $translations[$lang][$string];
		    } else {
			$check_for_custom_label = TRUE;
		    }
		} else {
		    $check_for_custom_label = TRUE;
		}
	    } else {
		$check_for_custom_label = TRUE;
	    }
	}

	//+++
	if (empty($string)) {
	    $check_for_custom_label = FALSE;
	}


	//for hierarchy titles type of: name1+name2+name3^Title
	if ($index != -1) {
	    if (stripos($string, '+')) {
		$tmp = explode('+', $string);
		if (isset($tmp[$index])) {
		    $string = explode('^', $tmp[$index]);
		    $string = $string[0];
		}
	    }
	}

	return $string;
    }

    //drawing of native woo price filter
    public static function price_filter() {
	global $_chosen_attributes, $wpdb, $wp, $WOOF;
	$request = $WOOF->get_request_data();
	/*
	  if (!is_post_type_archive('product') && !is_tax(get_object_taxonomies('product')))
	  {
	  return;
	  }

	  if (sizeof(WC()->query->unfiltered_product_ids) == 0)
	  {
	  return; // None shown - return
	  }
	 */
	$min_price = $WOOF->is_isset_in_request_data('min_price') ? esc_attr($request['min_price']) : '';
	$max_price = $WOOF->is_isset_in_request_data('max_price') ? esc_attr($request['max_price']) : '';

	//wp_enqueue_script('wc-price-slider');
	// Remember current filters/search
	$fields = '';

	if (get_search_query()) {
	    $fields .= '<input type="hidden" name="s" value="' . get_search_query() . '" />';
	}

	if (!empty($_GET['post_type'])) {
	    $fields .= '<input type="hidden" name="post_type" value="' . esc_attr($_GET['post_type']) . '" />';
	}

	if (!empty($_GET['product_cat'])) {
	    $fields .= '<input type="hidden" name="product_cat" value="' . esc_attr($_GET['product_cat']) . '" />';
	}

	if (!empty($_GET['product_tag'])) {
	    $fields .= '<input type="hidden" name="product_tag" value="' . esc_attr($_GET['product_tag']) . '" />';
	}

	if (!empty($_GET['orderby'])) {
	    $fields .= '<input type="hidden" name="orderby" value="' . esc_attr($_GET['orderby']) . '" />';
	}

	if ($_chosen_attributes) {
	    foreach ($_chosen_attributes as $attribute => $data) {
		$taxonomy_filter = 'filter_' . str_replace('pa_', '', $attribute);

		$fields .= '<input type="hidden" name="' . esc_attr($taxonomy_filter) . '" value="' . esc_attr(implode(',', $data['terms'])) . '" />';

		if ('or' == $data['query_type']) {
		    $fields .= '<input type="hidden" name="' . esc_attr(str_replace('pa_', 'query_type_', $attribute)) . '" value="or" />';
		}
	    }
	}

	//***
	$min = self::get_min_price();
	$max = self::get_max_price();
	//***

	if ($min == $max) {
	    return;
	}


	if ('' == get_option('permalink_structure')) {
	    $form_action = remove_query_arg(array('page', 'paged'), add_query_arg($wp->query_string, '', home_url($wp->request)));
	} else {
	    $form_action = preg_replace('%\/page/[0-9]+%', '', home_url(trailingslashit($wp->request)));
	}

	$price_slider_html = '<form method="get" action="' . esc_url($form_action) . '">
			<div class="price_slider_wrapper">
				<div class="price_slider" style="display:none;"></div>
				<div class="price_slider_amount">
					<input type="text" id="min_price" name="min_price" value="' . esc_attr($min_price) . '" data-min="' . esc_attr(apply_filters('woocommerce_price_filter_widget_amount', $min)) . '" placeholder="' . __('Min price', 'woocommerce-products-filter') . '" />
					<input type="text" id="max_price" name="max_price" value="' . esc_attr($max_price) . '" data-max="' . esc_attr(apply_filters('woocommerce_price_filter_widget_amount', $max)) . '" placeholder="' . __('Max price', 'woocommerce-products-filter') . '" />
					<button type="submit" class="button">' . __('Filter', 'woocommerce-products-filter') . '</button>
					<div class="price_label" style="display:none;">
						' . __('Price:', 'woocommerce-products-filter') . ' <span class="from"></span> &mdash; <span class="to"></span>
					</div>
					' . $fields . '
					<div class="clear"></div>
				</div>
			</div>
		</form>';

	$price_slider_data = array(
	    'form_action' => esc_url($form_action),
	    'min_price' => esc_attr($min_price),
	    'max_price' => esc_attr($max_price),
	    'fields' => $fields
	);
	$price_slider_html = apply_filters('woof_price_slider_html', $price_slider_html, $price_slider_data);
	echo $price_slider_html;
    }

    //for drop-down price filter
    public static function get_price2_filter_data($additional_taxes = '') {
	$woof_settings = get_option('woof_settings', array());
	if (isset($woof_settings['by_price']['ranges']) AND ! empty($woof_settings['by_price']['ranges'])) {
	    global $WOOF;
	    $res = array();
	    $request = $WOOF->get_request_data();
	    $res['selected'] = '';
	    if (isset($request['min_price']) AND isset($request['max_price'])) {
		$res['selected'] = $request['min_price'] . '-' . $request['max_price'];
	    }

	    //+++
	    $r = array(); //drop-doen options
	    $rc = array(); //count of items
	    $ranges = explode(',', trim($woof_settings['by_price']['ranges']));
	    $get = $request;
	    $max = self::get_max_price();
	    $show_count = get_option('woof_show_count', 0);
	    foreach ($ranges as $value) {
		$key = str_replace('i', $max, $value);
		//+++
		$tmp = explode('-', trim($value));
		$wc_price_args = array();
		/*
		  $wc_price_args = array(
		  'currency' => 'default'
		  );
		 */
		//$_REQUEST['woof_price_filter_working'] = TRUE;


		$tmp[0] = wc_price(floatval($tmp[0]), $wc_price_args);

		if ($tmp[1] != 'i') {
		    $tmp[1] = wc_price(floatval($tmp[1]), $wc_price_args);
		} else {
		    $tmp[1] = '&#8734;';
		}
		//$_REQUEST['woof_price_filter_working'] = FALSE;
		$value = $tmp[0] . ' - ' . $tmp[1];
		//***
		$v = explode('-', $key);
		$_GET['min_price'] = $v[0];
		$_GET['max_price'] = $v[1];
		//***
		if ($v[0] >= $v[1]) {
		    continue;
		}
		//***
		$r[$key] = $value;
		if ($show_count) {
		    $rc[$key] = $WOOF->dynamic_count(NULL, 'none', $additional_taxes);
		} else {
		    $rc[$key] = 0;
		}
	    }
	    $_GET = $get;
	    $ranges['options'] = $r;
	    $ranges['count'] = $rc;
	    //+++
	    $res['ranges'] = $ranges;
	    return $res;
	}


	return array();
    }

    public static function set_layered_nav_product_ids() {
	//after update to woo 2.6.x this function can be removed
	//WC()->query->layered_nav_product_ids = array(15, 37, 19, 22, 31, 34);
	if (isset($_REQUEST['woof_wp_query_ids'])) {
	    if ((defined('DOING_AJAX') && DOING_AJAX) AND isset($_REQUEST['predict_ids_and_continue'])) {
		//for relevant recounting of price range on cat page in AJAX mode
		WC()->query->layered_nav_product_ids = $_REQUEST['woof_wp_query_ids'];
	    }
	}
    }

    /**
     * Get filtered min price for current products.
     * @return int
     * woocommerce native function from 2.6.0 version - added by WOOF author
     */
    //wp-content\plugins\woocommerce\includes\widgets\class-wc-widget-price-filter.php
    public static function get_filtered_price() {
	global $wpdb, $wp_the_query;

	$args = $wp_the_query->query_vars;
	$tax_query = isset($args['tax_query']) ? $args['tax_query'] : array();
	$meta_query = isset($args['meta_query']) ? $args['meta_query'] : array();


// Fix for price slider in ajax
	if (isset($_REQUEST['woof_wp_query'])AND ! empty($_REQUEST['woof_wp_query'])) {
	    $arg = $_REQUEST['woof_wp_query'];
	    $tax_query = isset($arg->query['tax_query']) ? $arg->query['tax_query'] : array();
	    $meta_query = isset($arg->query['meta_query']) ? $arg->query['meta_query'] : array();
	}

	//++++    

	if (!empty($args['taxonomy']) && !empty($args['term'])) {
	    $tax_query[] = array(
		'taxonomy' => $args['taxonomy'],
		'terms' => array($args['term']),
		'field' => 'slug',
	    );
	}

	if (!empty($meta_query) AND is_array($meta_query)) {
	    foreach ($meta_query as $key => $query) {
		if (!empty($query['price_filter']) || !empty($query['rating_filter'])) {
		    unset($meta_query[$key]);
		}
	    }
	}

	$meta_query = new WP_Meta_Query($meta_query);
	$tax_query = new WP_Tax_Query($tax_query);

	$meta_query_sql = $meta_query->get_sql('post', $wpdb->posts, 'ID');
	$tax_query_sql = $tax_query->get_sql($wpdb->posts, 'ID');
	//CAST( price_meta.meta_value AS UNSIGNED )
	$sql = "SELECT min( price_meta.meta_value + 0.0  ) as min_price, max( price_meta.meta_value + 0.0  )as max_price FROM {$wpdb->posts} ";
	$sql .= " LEFT JOIN {$wpdb->postmeta} as price_meta ON {$wpdb->posts}.ID = price_meta.post_id " . $tax_query_sql['join'] . $meta_query_sql['join'];
	$sql .= " 	WHERE {$wpdb->posts}.post_type = 'product'
					AND {$wpdb->posts}.post_status = 'publish'
					AND price_meta.meta_key IN ('" . implode("','", array_map('esc_sql', apply_filters('woocommerce_price_filter_meta_keys', array('_price')))) . "')
					AND price_meta.meta_value > '' ";
	$sql .= $tax_query_sql['where'] . $meta_query_sql['where'];
	return $wpdb->get_row($sql);
    }

    public static function get_max_price() {
	global $wpdb;

	if (version_compare(WOOCOMMERCE_VERSION, '2.6', '>')) {

	    $prices = self::get_filtered_price();
	    $max = ceil($prices->max_price);
	} else {
	    self::set_layered_nav_product_ids();
	    if (0 === sizeof(WC()->query->layered_nav_product_ids)) {

		$max = ceil($wpdb->get_var(
				$wpdb->prepare('
					SELECT max(meta_value + 0)
					FROM %1$s
					LEFT JOIN %2$s ON %1$s.ID = %2$s.post_id
					WHERE meta_key IN ("' . implode('","', apply_filters('woocommerce_price_filter_meta_keys', array('_price'))) . '")
				', $wpdb->posts, $wpdb->postmeta, '_price')
		));
	    } else {

		$max = ceil($wpdb->get_var(
				$wpdb->prepare('
					SELECT max(meta_value + 0)
					FROM %1$s
					LEFT JOIN %2$s ON %1$s.ID = %2$s.post_id
					WHERE meta_key IN ("' . implode('","', apply_filters('woocommerce_price_filter_meta_keys', array('_price'))) . '")
					AND (
						%1$s.ID IN (' . implode(',', array_map('absint', WC()->query->layered_nav_product_ids)) . ')
						OR (
							%1$s.post_parent IN (' . implode(',', array_map('absint', WC()->query->layered_nav_product_ids)) . ')
							AND %1$s.post_parent != 0
						)
					)
				', $wpdb->posts, $wpdb->postmeta
		)));
	    }
	}


	return $max;
    }

    public static function get_min_price() {
	global $wpdb;

	if (version_compare(WOOCOMMERCE_VERSION, '2.6', '>')) {
	    $prices = self::get_filtered_price();
	    $min = floor($prices->min_price);
	} else {
	    self::set_layered_nav_product_ids();
	    if (0 === sizeof(WC()->query->layered_nav_product_ids)) {
		$min = floor($wpdb->get_var(
				$wpdb->prepare('
					SELECT min(meta_value + 0)
					FROM %1$s
					LEFT JOIN %2$s ON %1$s.ID = %2$s.post_id
					WHERE meta_key IN ("' . implode('","', apply_filters('woocommerce_price_filter_meta_keys', array('_price', '_min_variation_price'))) . '")
					AND meta_value != ""
				', $wpdb->posts, $wpdb->postmeta)
		));
	    } else {
		$min = floor($wpdb->get_var(
				$wpdb->prepare('
					SELECT min(meta_value + 0)
					FROM %1$s
					LEFT JOIN %2$s ON %1$s.ID = %2$s.post_id
					WHERE meta_key IN ("' . implode('","', apply_filters('woocommerce_price_filter_meta_keys', array('_price', '_min_variation_price'))) . '")
					AND meta_value != ""
					AND (
						%1$s.ID IN (' . implode(',', array_map('absint', WC()->query->layered_nav_product_ids)) . ')
						OR (
							%1$s.post_parent IN (' . implode(',', array_map('absint', WC()->query->layered_nav_product_ids)) . ')
							AND %1$s.post_parent != 0
						)
					)
				', $wpdb->posts, $wpdb->postmeta
		)));
	    }
	}


	return $min;
    }

    //is customer look the site from mobile device
    public static function is_mobile_device() {
	/*
	  if (isset($_SERVER["HTTP_USER_AGENT"]))
	  {
	  return preg_match("/(android|avantgo|blackberry|bolt|boost|cricket|docomo|fone|hiptop|mini|mobi|palm|phone|pie|tablet|up\.browser|up\.link|webos|wos)/i", $_SERVER["HTTP_USER_AGENT"]);
	  }
	 */
	return wp_is_mobile();
    }

    public static function show_admin_notice($key) {
	global $WOOF;
	echo $WOOF->render_html(WOOF_PATH . 'views/notices/' . $key . '.php');
    }

    public static function add_notice($key) {
	//update_option('woof_notices', array());
	$notices = get_option('woof_notices', array());
	$is_hidden = false;
	if (isset($notices[$key]) AND $notices[$key] == 'hidden') {
	    $is_hidden = true;
	}

	if (!$is_hidden) {
	    self::$notices[] = $key;
	    $notices[] = $key;
	    update_option('woof_notices', $notices);
	}
    }

    public static function hide_admin_notices() {
	if (isset($_GET['woof_hide_notice']) && isset($_GET['_wpnonce'])) {
	    if (!wp_verify_nonce($_GET['_wpnonce'])) {
		wp_die(__('Action failed. Please refresh the page and retry.', 'woocommerce-products-filter'));
	    }

	    if (!current_user_can('manage_woocommerce')) {
		wp_die(__('Cheatin&#8217; huh?', 'woocommerce-products-filter'));
	    }

	    $key = self::escape($_GET['woof_hide_notice']);
	    unset(self::$notices[$key]);
	    $notices = get_option('woof_notices', array());
	    $notices[$key] = 'hidden';
	    update_option('woof_notices', $notices);
	}
    }

    public static function draw_title_toggle($show, $block_is_closed) {
	if (!$show) {
	    return "";
	}

	global $WOOF;
	$condition = 'closed';
	$toggle_type = ((isset($WOOF->settings['toggle_type']) AND ! empty($WOOF->settings['toggle_type'])) ? $WOOF->settings['toggle_type'] : 'text');


	if ($block_is_closed) {
	    $toggle_text = ((isset($WOOF->settings['toggle_closed_text']) AND ! empty($WOOF->settings['toggle_closed_text'])) ? self::wpml_translate(null, $WOOF->settings['toggle_closed_text']) : '-');
	    $toggle_image = ((isset($WOOF->settings['toggle_closed_image']) AND ! empty($WOOF->settings['toggle_closed_image'])) ? $WOOF->settings['toggle_closed_image'] : WOOF_LINK . 'img/plus3.png');
	} else {
	    $toggle_text = ((isset($WOOF->settings['toggle_opened_text']) AND ! empty($WOOF->settings['toggle_opened_text'])) ? self::wpml_translate(null, $WOOF->settings['toggle_opened_text']) : '+');
	    $toggle_image = ((isset($WOOF->settings['toggle_opened_image']) AND ! empty($WOOF->settings['toggle_opened_image'])) ? $WOOF->settings['toggle_opened_image'] : WOOF_LINK . 'img/minus3.png');
	    $condition = 'opened';
	}



	if ($toggle_type == 'text' OR empty($toggle_image)) {
	    ?>
	    <a href="javascript: void(0);" title="<?php _e('toggle', 'woocommerce-products-filter') ?>" class="woof_front_toggle woof_front_toggle_<?php echo $condition ?>" data-condition="<?php echo $condition ?>"><?php echo $toggle_text ?></a>
	    <?php
	} else {
	    ?>
	    <a href="javascript: void(0);" title="<?php _e('toggle', 'woocommerce-products-filter') ?>" class="woof_front_toggle woof_front_toggle_<?php echo $condition ?>" data-condition="<?php echo $condition ?>">
	        <img src="<?php echo $toggle_image ?>" alt="<?php _e('toggle', 'woocommerce-products-filter') ?>" />
	    </a>
	    <?php
	}
    }

    //for checkboxes,radio,colors,labels,images
    public static function draw_more_less_button($type) {
	//$type - color,radio,checkbox,label,image
	$args = apply_filters('woof_get_more_less_button_' . $type, array());
	if (empty($args)) {
	    $args['type'] = 'text'; //image
	    $args['closed'] = __('Show more', 'woocommerce-products-filter');
	    $args['opened'] = __('Show less', 'woocommerce-products-filter');
	}

	if ($args['type'] == 'image') {
	    ?>
	    <a href="javascript:void(0);" class="woof_open_hidden_li_btn" data-type="<?php echo $args['type'] ?>" data-state="closed" data-closed="<?php echo $args['closed'] ?>" data-opened="<?php echo $args['opened'] ?>"><img src="<?php echo $args['closed'] ?>" alt="" /></a>
	    <?php
	} else {
	    ?>
	    <a href="javascript:void(0);" class="woof_open_hidden_li_btn" data-type="<?php echo $args['type'] ?>" data-state="closed" data-closed="<?php echo $args['closed'] ?>" data-opened="<?php echo $args['opened'] ?>"><?php echo $args['closed'] ?></a>
	    <?php
	}
    }

    public static function recurse_dirsize($directory, $exclude = '') {
	$size = 0;
	$directory = untrailingslashit($directory);
	//***
	if (!file_exists($directory) || !is_dir($directory) || !is_readable($directory) || $directory === $exclude) {
	    return false;
	}
	//***
	if ($handle = opendir($directory)) {
	    while (($file = readdir($handle)) !== false) {
		$path = $directory . '/' . $file;
		if ($file != '.' && $file != '..') {
		    if (is_file($path)) {
			$size += filesize($path);
		    } elseif (is_dir($path)) {
			$handlesize = self::recurse_dirsize($path, $exclude);
			if ($handlesize > 0) {
			    $size += $handlesize;
			}
		    }
		}
	    }
	    closedir($handle);
	}
	return $size;
    }

}
