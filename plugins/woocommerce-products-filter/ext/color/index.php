<?php
if (!defined('ABSPATH'))
    die('No direct access allowed');

final class WOOF_EXT_COLOR extends WOOF_EXT
{

    public $type = 'html_type';
    public $html_type = 'color'; //your custom key here
    public $html_type_dynamic_recount_behavior = 'multi';

    public function __construct()
    {
	parent::__construct();
	$this->init();
    }

    public function get_ext_path()
    {
	return plugin_dir_path(__FILE__);
    }

    public function get_ext_link()
    {
	return plugin_dir_url(__FILE__);
    }

    public function init()
    {
	add_filter('woof_add_html_types', array($this, 'woof_add_html_types'));
	add_action('wp_head', array($this, 'wp_head'), 999);
	add_action('woocommerce_settings_tabs_woof', array($this, 'woocommerce_settings_tabs_woof'), 51);
	add_action('woof_print_tax_additional_options_color', array($this, 'print_additional_options'), 10, 1);
	add_action('woof_print_design_additional_options', array($this, 'woof_print_design_additional_options'), 10, 1);
	self::$includes['js']['woof_' . $this->html_type . '_html_items'] = $this->get_ext_link() . 'js/html_types/' . $this->html_type . '.js';
	self::$includes['css']['woof_' . $this->html_type . '_html_items'] = $this->get_ext_link() . 'css/html_types/' . $this->html_type . '.css';
	self::$includes['js_init_functions'][$this->html_type] = 'woof_init_colors';

	add_action('admin_head', array($this, 'admin_head'), 50);

	$this->taxonomy_type_additional_options = array(
	    'show_tooltip' => array(
		'title' => __('Show tooltip', 'woocommerce-products-filter'),
		'tip' => __('Show tooltip on hover', 'woocommerce-products-filter'),
		'type' => 'select',
		'options' => array(
		    1 => __('Yes', 'woocommerce-products-filter'),
		    0 => __('No', 'woocommerce-products-filter')		    
		)
	    )
	);
    }

    public function admin_head()
    {
	if (isset($_GET['tab']) AND $_GET['tab'] == 'woof')
	{
	    wp_enqueue_style('woof_color', $this->get_ext_link() . 'css/admin.css');
	    wp_enqueue_script('woof_color', $this->get_ext_link() . 'js/html_types/plugin_options.js', array('jquery'));
	}
    }

    public function woof_add_html_types($types)
    {
	$types[$this->html_type] = __('Color', 'woocommerce-products-filter');
	return $types;
    }

    public function woocommerce_settings_tabs_woof()
    {
	wp_enqueue_style('wp-color-picker');
	wp_enqueue_script('wp-color-picker');
    }

    public function wp_head()
    {
	global $WOOF;
	?>
	<style type="text/css">
	<?php
	if (isset($WOOF->settings['checked_color_img']))
	{
	    if (!empty($WOOF->settings['checked_color_img']))
	    {
		?>
		    .checked .woof_color_checked{
			background: url(<?php echo $WOOF->settings['checked_color_img'] ?>) !important;
		    }             
		<?php
	    }
	}
	?>
	</style>
	<?php
    }

    public function print_additional_options($key)
    {
	global $WOOF;
	$woof_settings = $WOOF->settings;
	$terms = WOOF_HELPER::get_terms($key, 0, 0, 0, 0);
	if (!empty($terms))
	{
	    ?>
	<br /><a href="javascript:void(0);" class="button woof_toggle_colors"><?php _e('toggle color terms', 'woocommerce-products-filter') ?></a><br />
	    <ul class="woof_color_list">
		<?php
		foreach ($terms as $t)
		{
		    $color = '#000000';
		    if (isset($woof_settings['color'][$key][$t['slug']]))
		    {
			$color = $woof_settings['color'][$key][$t['slug']];
		    }

		    $color_img = '';
		    if (isset($woof_settings['color_img'][$key][$t['slug']]))
		    {
			$color_img = $woof_settings['color_img'][$key][$t['slug']];
		    }
		    ?>
		    <li>
			<table>
			    <tr>
				<td valign="top">
				    <input type="text" name="woof_settings[color][<?php echo $key ?>][<?php echo $t['slug'] ?>]" value="<?php echo $color ?>" id="woof_color_picker_<?php echo $t['slug'] ?>" class="woof-color-picker" >
				</td>
				<td>
				    <input type="text" name="woof_settings[color_img][<?php echo $key ?>][<?php echo $t['slug'] ?>]" value="<?php echo $color_img ?>" placeholder="<?php _e('background image url 25x25', 'woocommerce-products-filter') ?>" class="text" style="width: 600px;" />
				    <a href="#" class="woof-button woof_select_image"><?php _e('Select Image', 'woocommerce-products-filter') ?></a>
				</td>
				<td valign="top" style="padding: 4px 0 0 0;">
				    <p class="description"> [ <?php echo WOOF_HELPER::strtolower($t['name']) ?> ]</p>
				</td>
			    </tr>
			</table>
		    </li>
		    <?php
		}
		echo '</ul>';
	    }
	}

	public function woof_print_design_additional_options()
	{
	    global $WOOF;
	    $woof_settings = $WOOF->settings;

	    if (!isset($woof_settings['checked_color_img']))
	    {
		$woof_settings['checked_color_img'] = '';
	    }
	    ?>

	    <!--<div class="woof-control-section">

			    <<?php echo apply_filters('woof_title_tag', 'h4'); ?>><?php _e('Image for checked color type checkbox', 'woocommerce-products-filter') ?></<?php echo apply_filters('woof_title_tag', 'h4'); ?>>

		<div class="woof-control-container">
		    <div class="woof-control woof-upload-style-wrap">
					    <input type="text" name="woof_settings[checked_color_img]" value="<?php //echo $woof_settings['checked_color_img']          ?>" />
					    <a href="#" class="woof-button woof_select_image"><?php _e('Select Image', 'woocommerce-products-filter') ?></a>
		    </div>
		    <div class="woof-description">
					    <p class="description"><?php _e('Image for color checkboxes when its checked. Better use png. Size is: 25x25 px.', 'woocommerce-products-filter') ?></p>
		    </div>
		</div>

	    </div> -->

	    <?php
	}

    }

    WOOF_EXT::$includes['taxonomy_type_objects']['color'] = new WOOF_EXT_COLOR();
    