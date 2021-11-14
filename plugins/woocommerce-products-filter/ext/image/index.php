<?php
if (!defined('ABSPATH'))
    die('No direct access allowed');

final class WOOF_EXT_IMAGE extends WOOF_EXT {

    public $type = 'html_type';
    public $html_type = 'image'; //your custom key here
    public $html_type_dynamic_recount_behavior = 'multi';

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

    public function init() {
	add_filter('woof_add_html_types', array($this, 'woof_add_html_types'));
	add_action('wp_head', array($this, 'wp_head'), 999);
	add_action('woocommerce_settings_tabs_woof', array($this, 'woocommerce_settings_tabs_woof'), 51);
	add_action('woof_print_tax_additional_options_image', array($this, 'print_additional_options'), 10, 1);
	add_action('woof_print_design_additional_options', array($this, 'woof_print_design_additional_options'), 10, 1);
	self::$includes['js']['woof_' . $this->html_type . '_html_items'] = $this->get_ext_link() . 'js/html_types/' . $this->html_type . '.js';
	self::$includes['css']['woof_' . $this->html_type . '_html_items'] = $this->get_ext_link() . 'css/html_types/' . $this->html_type . '.css';
	self::$includes['js_init_functions'][$this->html_type] = 'woof_init_image';

	add_action('admin_head', array($this, 'admin_head'), 50);

	/*
	  $this->taxonomy_type_additional_options = array(
	  'checked_image' => array(
	  'title' => __('Image for checked image type checkbox', 'woocommerce-products-filter'),
	  'tip' => __('Image for image checkboxes when its checked. Better use png. Size is: 25x25 px.', 'woocommerce-products-filter'),
	  'type' => 'image'
	  )
	  );
	 */
    }

    public function admin_head() {
	if (isset($_GET['tab']) AND $_GET['tab'] == 'woof') {
	    wp_enqueue_style('woof_image', $this->get_ext_link() . 'css/admin.css');
	    wp_enqueue_script('woof_image', $this->get_ext_link() . 'js/html_types/plugin_options.js', array('jquery'));
	}
    }

    public function woof_add_html_types($types) {
	$types[$this->html_type] = __('Image', 'woocommerce-products-filter');
	return $types;
    }

    public function woocommerce_settings_tabs_woof() {
	//wp_enqueue_style('wp-image-picker');
	//wp_enqueue_script('wp-image-picker');
    }

    public function wp_head() {
	
    }

    public function print_additional_options($key) {
	global $WOOF;
	$woof_settings = $WOOF->settings;
	$terms = WOOF_HELPER::get_terms($key, 0, 0, 0, 0);
	if (!empty($terms)) {
	    ?>
	    <br /><a href="javascript:void(0);" class="button woof_toggle_images"><?php _e('toggle image terms', 'woocommerce-products-filter') ?></a><br />
	    <ul class="woof_image_list">
	    <?php
	    foreach ($terms as $t) {
		$term_key = 'images_term_' . $t['term_id'];
		$image = '';
		if (isset($woof_settings[$term_key]['image_url'])) {
		    $image = $woof_settings[$term_key]['image_url'];
		}

		if (!isset($woof_settings[$term_key]['image_styles'])) {
		    $woof_settings[$term_key]['image_styles'] = '';
		}
		?>
		    <li>
			<table>
			    <tr>

				<td>
				    <input type="text" name="woof_settings[<?php echo $term_key ?>][image_url]" value="<?php echo $image ?>" placeholder="<?php _e('set link to the image', 'woocommerce-products-filter') ?>" class="text" style="width: 600px;" />
				    <a href="#" class="woof-button woof_select_image"><?php _e('Select Image', 'woocommerce-products-filter') ?></a>
				</td>
				<td>
				    <input type="button" value="&#xea49" data-key="<?php echo $term_key ?>" data-name="<?php printf(__('Image settings for term %s', 'woocommerce-products-filter'), $t['name']) ?>" class="woof-button js_woof_options js_woof_options_image icon-book">
				    <input type="hidden" name="woof_settings[<?php echo $term_key ?>][image_styles]" value="<?php echo $woof_settings[$term_key]['image_styles'] ?>" />

				    <div id="woof-modal-content-<?php echo $term_key ?>" style="display: none;">

					<div class="woof-form-element-container">

					    <div class="woof-name-description" style="width: 20%;">
						<strong><?php _e('Image styles', 'woocommerce-products-filter') ?></strong>
						<span><?php _e('This option should be set', 'woocommerce-products-filter') ?></span>
					    </div>                                       


					    <div class="woof-form-element" style="width: 80%;">
						<textarea class="woof_popup_option" data-option="image_styles"></textarea><br />
						<b><?php _e('Example', 'woocommerce-products-filter') ?>:</b><br />
						<code>width: 100px;<br />
						    height:50px;<br />
						    margin: 0 3px 3px 0;<br />
						    background-size: 100% 100%;<br />
						    background-clip: content-box;<br />
						    border: 2px solid #e2e6e7;<br />
						    padding: 2px;<br />
						    color: #292f38;<br />
						    font-size: 0;<br />
						    text-align: center;<br />
						    cursor: pointer;<br />
						    -webkit-border-radius: 4px;<br />
						    border-radius: 4px;<br />
						    -webkit-transition: border-color .35s ease;<br />
						    transition: border-color .35s ease;<br />
						</code>
					    </div>

					</div>



				    </div>
				</td>
				<td valign="top" style="padding: 4px 0 0 0;">
				    <p class="description"> [ <?php echo WOOF_HELPER::strtolower($t['slug']); ?> ]</p>
				</td>
			    </tr>
			</table>
		    </li>
		<?php
	    }
	    echo '</ul>';
	}
    }

    public function woof_print_design_additional_options() {
	global $WOOF;
	$woof_settings = $WOOF->settings;

	if (!isset($woof_settings['checked_image'])) {
	    $woof_settings['checked_image'] = '';
	}
	?>

	    <!--<div class="woof-control-section">

			    <<?php echo apply_filters('woof_title_tag', 'h4'); ?>><?php _e('Image for checked image type checkbox', 'woocommerce-products-filter') ?></<?php echo apply_filters('woof_title_tag', 'h4'); ?>>

		<div class="woof-control-container">
		    <div class="woof-control woof-upload-style-wrap">
					    <input type="text" name="woof_settings[checked_image]" value="<?php //echo $woof_settings['checked_image']             ?>" />
					    <a href="#" class="woof-button woof_select_image"><?php _e('Select Image', 'woocommerce-products-filter') ?></a>
		    </div>
		    <div class="woof-description">
					    <p class="description"><?php _e('Image for image checkboxes when its checked. Better use png. Size is: 25x25 px.', 'woocommerce-products-filter') ?></p>
		    </div>
		</div>

	    </div> -->

	    <?php
	}

    }

    WOOF_EXT::$includes['taxonomy_type_objects']['image'] = new WOOF_EXT_IMAGE();
    