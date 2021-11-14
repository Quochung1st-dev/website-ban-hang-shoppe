<?php
if (!defined('ABSPATH'))
    die('No direct access allowed');

//18-04-2017
final class WOOF_EXT_BY_SKU extends WOOF_EXT {

    public $type = 'by_html_type';
    public $html_type = 'by_sku'; //your custom key here
    public $index = 'woof_sku'; //index in the search query
    public $html_type_dynamic_recount_behavior = 'none';

    public function __construct() {
	parent::__construct();
	$this->init();
    }

    public function get_ext_path() {
	return plugin_dir_path(__FILE__);
    }

    public function get_ext_link() {
	return plugin_dir_url(__FILE__);
    }

    public function woof_add_items_keys($keys) {
	$keys[] = $this->html_type;
	return $keys;
    }

    public function init() {
	add_filter('woof_add_items_keys', array($this, 'woof_add_items_keys'));
	add_action('woof_print_html_type_options_' . $this->html_type, array($this, 'woof_print_html_type_options'), 10, 1);
	add_action('woof_print_html_type_' . $this->html_type, array($this, 'print_html_type'), 10, 1);
	add_action('wp_head', array($this, 'wp_head'), 999);

	add_action('wp_ajax_woof_sku_autocomplete', array($this, 'woof_sku_autocomplete'));
	add_action('wp_ajax_nopriv_woof_sku_autocomplete', array($this, 'woof_sku_autocomplete'));

	self::$includes['js']['woof_' . $this->html_type . '_html_items'] = $this->get_ext_link() . 'js/' . $this->html_type . '.js';
	self::$includes['css']['woof_' . $this->html_type . '_html_items'] = $this->get_ext_link() . 'css/' . $this->html_type . '.css';
	self::$includes['js_init_functions'][$this->html_type] = 'woof_init_sku';
	//***
	add_shortcode('woof_sku_filter', array($this, 'woof_sku_filter'));
    }

    public function wp_head() {
	global $WOOF;
	//***
	if (isset($WOOF->settings['by_sku']['autocomplete']) AND $WOOF->settings['by_sku']['autocomplete']) {
	    wp_enqueue_script('easy-autocomplete', WOOF_LINK . 'js/easy-autocomplete/jquery.easy-autocomplete.min.js', array('jquery'));
	    wp_enqueue_style('easy-autocomplete', WOOF_LINK . 'js/easy-autocomplete/easy-autocomplete.min.css');
	    wp_enqueue_style('easy-autocomplete-theme', WOOF_LINK . 'js/easy-autocomplete/easy-autocomplete.themes.min.css');
	}
	?>
	<style type="text/css">
	<?php
	if (isset($WOOF->settings['by_sku']['image'])) {
	    if (!empty($WOOF->settings['by_sku']['image'])) {
		?>
		    .woof_sku_search_container .woof_sku_search_go{
			background: url(<?php echo $WOOF->settings['by_sku']['image'] ?>) !important;
		    }
		<?php
	    }
	}
	?>
	</style>
	<script type="text/javascript">
	    if (typeof woof_lang_custom == 'undefined') {
		var woof_lang_custom = {};//!!important
	    }
	    woof_lang_custom.<?php echo $this->index ?> = "<?php _e('by SKU', 'woocommerce-products-filter') ?>";

	    var woof_sku_autocomplete = 0;
	    var woof_sku_autocomplete_items = 10;
	<?php if (isset($WOOF->settings['by_sku']['autocomplete'])): ?>
	        woof_sku_autocomplete =<?php echo (int) $WOOF->settings['by_sku']['autocomplete']; ?>;
	        //woof_sku_autocomplete_items =<?php echo apply_filters('woof_sku_autocomplete_items', 10) ?>;
	        woof_sku_autocomplete_items =<?php echo (int) ($WOOF->settings['by_sku']['autocomplete_items'] ? $WOOF->settings['by_sku']['autocomplete_items'] : 10); ?>;
	<?php endif; ?>
	</script>
	<?php
    }

    //shortcode
    public function woof_sku_filter($args = array()) {
	global $WOOF;
	return $WOOF->render_html($this->get_ext_path() . 'views' . DIRECTORY_SEPARATOR . 'shortcodes' . DIRECTORY_SEPARATOR . 'woof_sku_filter.php', $args);
    }

    //settings page hook
    public function woof_print_html_type_options() {
	global $WOOF;
	echo $WOOF->render_html($this->get_ext_path() . 'views' . DIRECTORY_SEPARATOR . 'options.php', array(
	    'key' => $this->html_type,
	    "woof_settings" => get_option('woof_settings', array())
		)
	);
    }

    public function assemble_query_params(&$meta_query, $wp_query = NULL) {
	global $WOOF;
	$request = $WOOF->get_request_data();
	if (isset($request['woof_sku'])) {
	    if (!empty($request['woof_sku'])) {
		$woof_sku_request = explode(',', $request['woof_sku']);
		$woof_sku_request = array_map('urldecode', $woof_sku_request);
		$woof_sku_request = array_map('trim', $woof_sku_request);
		//***
		$use_for = isset($WOOF->settings['by_sku']['use_for']) ? $WOOF->settings['by_sku']['use_for'] : 'simple';
		if ($use_for == 'simple') {
		    if (!empty($woof_sku_request)) {
			$res = array();
			$res['relation'] = 'OR';
			foreach ($woof_sku_request as $sku) {
			    $res[] = array(
				'key' => '_sku',
				'value' => $sku,
				'compare' => $WOOF->settings['by_sku']['logic']
			    );
			}
			$meta_query[] = $res;
		    }
		} else {
		    add_filter('posts_where', array($this, 'posts_where'), 9999);
		}
	    }
	}

	return $meta_query;
    }

    public function posts_where($where = '') {
	global $WOOF;
	static $where_sku = "";

	//cache on the fly
	if (!empty($where_sku)) {
	    return $where . $where_sku;
	}

	$request = $WOOF->get_request_data();
	if (isset($request['woof_sku'])) {
	    if (!empty($request['woof_sku'])) {
		global $wpdb;
		//$woof_text = trim(urldecode($request['woof_sku']));
		$woof_sku_request = explode(',', $request['woof_sku']);
		$woof_sku_request = array_map('urldecode', $woof_sku_request);
		$woof_sku_request = array_map('trim', $woof_sku_request);
		//***
		$condtion_string = "";
		if (!empty($woof_sku_request)) {
		    foreach ($woof_sku_request as $k => $sku) {
			if ($k > 0) {
			    $condtion_string .= " OR ";
			}
			if ($WOOF->settings['by_sku']['logic'] == '=') {
			    $condtion_string .= "postmeta.meta_value {$WOOF->settings['by_sku']['logic']} '$sku'";
			} else {
			    $condtion_string .= "postmeta.meta_value {$WOOF->settings['by_sku']['logic']} '%$sku%'";
			}
		    }
		}

		//***

		$product_variations = $wpdb->get_results("
                    SELECT posts.ID
                    FROM $wpdb->posts AS posts
                    LEFT JOIN $wpdb->postmeta AS postmeta ON ( posts.ID = postmeta.post_id )
                    WHERE posts.post_type IN ('product_variation','product')
                    AND postmeta.meta_key = '_sku'
                    AND ($condtion_string)", ARRAY_N);
		//+++
		$product_variations_ids = array();
		if (!empty($product_variations)) {
		    foreach ($product_variations as $v) {
			$product_variations_ids[] = $v[0];
		    }

		    //+++
		    $product_variations_ids_string = implode(',', $product_variations_ids);
		    //die($product_variations_ids_string);
		    $products = $wpdb->get_results("
                        SELECT posts.post_parent
                        FROM $wpdb->posts AS posts
                        WHERE posts.ID IN ($product_variations_ids_string) AND posts.post_parent > 0", ARRAY_N);
		    //+++
		    $product_ids = array();
		    if (!empty($products)) {
			foreach ($products as $v) {
			    $product_ids[] = $v[0];
			}
		    }
		    $product_ids = implode(',', array_merge($product_ids, $product_variations_ids));
		    $where .= " AND $wpdb->posts.ID IN($product_ids)";
		    $where_sku = " AND $wpdb->posts.ID IN($product_ids)";
		    //die($where);
		}
	    }
	}

	return $where;
    }

    //ajax
    public function woof_sku_autocomplete() {
	global $WOOF;
	$results = array();
	$args = array(
	    'nopaging' => true,
	    'post_type' => 'product',
	    'post_status' => array('publish'),
	    'orderby' => 'title',
	    'order' => 'ASC',
	    //'max_num_pages' => apply_filters('woof_sku_autocomplete_items', 10),
	    'max_num_pages' => (int) $WOOF->settings['by_sku']['autocomplete_items']
	);

	if (class_exists('SitePress')) {
	    $args['lang'] = ICL_LANGUAGE_CODE;
	}

	//***

	$_GET['woof_sku'] = $_REQUEST['phrase'];
	add_filter('posts_where', array($this, 'posts_where'), 10);
	$query = new WP_Query($args);
	//+++
	//http://easyautocomplete.com/guide
	if ($query->have_posts()) {
	    include_once WOOF_PATH . 'lib' . DIRECTORY_SEPARATOR . 'aq_resizer.php';
	    foreach ($query->posts as $p) {
		$product = new WC_Product($p->ID);
		$data = array(
		    "name" => $product->get_sku(),
		    "type" => $p->post_title,
		    "link" => get_post_permalink($p->ID)
		);

		/*
		  if (has_post_thumbnail($p->ID))
		  {
		  $img_src = wp_get_attachment_image_src(get_post_thumbnail_id($p->ID), 'single-post-thumbnail');
		  $data['icon'] = woof_aq_resize($img_src[0], 100, 100, true);
		  } else
		  {
		  $data['icon'] = WOOF_LINK . 'img/not-found.jpg';
		  }
		 *
		 */
		$results[] = $data;
	    }
	} else {
	    $results[] = array(
		"name" => __("Products not found!", 'woocommerce-products-filter'),
		"type" => "",
		"link" => "#",
		"icon" => WOOF_LINK . 'img/not-found.jpg'
	    );
	}

	die(json_encode($results));
    }

}

WOOF_EXT::$includes['html_type_objects']['by_sku'] = new WOOF_EXT_BY_SKU();
