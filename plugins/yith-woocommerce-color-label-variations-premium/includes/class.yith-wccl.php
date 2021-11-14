<?php
/**
 * Main class
 *
 * @author Yithemes
 * @package YITH WooCommerce Color and Label Variations Premium
 * @version 1.0.0
 */


if ( ! defined( 'YITH_WCCL' ) ) {
	exit;
} // Exit if accessed directly

if ( ! class_exists( 'YITH_WCCL' ) ) {
	/**
	 * YITH WooCommerce Color and Label Variations Premium
	 *
	 * @since 1.0.0
	 */
	class YITH_WCCL {

		/**
		 * Single instance of the class
		 *
		 * @var \YITH_WCCL
		 * @since 1.0.0
		 */
		protected static $instance;

		/**
		 * Plugin version
		 *
		 * @var string
		 * @since 1.0.0
		 */
		public $version = YITH_WCCL_VERSION;


		/**
		 * Returns single instance of the class
		 *
		 * @return \YITH_WFBT
		 * @since 1.0.0
		 */
		public static function get_instance(){
			if( is_null( self::$instance ) ){
				self::$instance = new self();
			}

			return self::$instance;
		}

		/**
		 * Constructor
		 *
		 * @return mixed YITH_WCCL_Admin | YITH_WCCL_Frontend
		 * @since 1.0.0
		 */
		public function __construct() {

			// Load Plugin Framework
			add_action( 'after_setup_theme', array( $this, 'plugin_fw_loader' ), 1 );

			// Class admin
			if ( $this->is_admin() ) {
				// require classes
				require_once( 'class.yith-wccl-admin.php' );
				// Admin Class
				YITH_WCCL_Admin();
			}
			else {
				// require classes
				require_once( 'class.yith-wccl-frontend.php' );
				// Frontend Class
				YITH_WCCL_Frontend();
			}

			// clear plugin transient on save product
			add_action( 'woocommerce_before_product_object_save', array ( $this, 'delete_transient_on_save' ), 10, 2 );
			// add new attribute types
			add_filter( 'product_attributes_type_selector', array( $this, 'attribute_types' ), 10, 1 );
		}

		/**
		 * Check if context is admin
		 *
		 * @since 1.2.2
		 * @author Francesco Licandro
		 * @return boolean
		 */
		public function is_admin(){
			$actions = apply_filters( 'yith_wccl_is_admin_actions_array', array(
				'prdctfltr_respond_550',
				'flatsome_quickview'
			));
			$is_frontend = isset( $_REQUEST['context'] ) && $_REQUEST['context'] == 'frontend';
			$is_ajax =  defined( 'DOING_AJAX' ) && DOING_AJAX && ( $is_frontend || ( isset( $_REQUEST['action'] ) && in_array( $_REQUEST['action'], $actions ) ) );
			
			return apply_filters( 'yith_wccl_load_admin_class', ( is_admin() && ! $is_ajax ) );
		}

		/**
		 * Load Plugin Framework
		 *
		 * @since  1.0
		 * @access public
		 * @return void
		 * @author Andrea Grillo <andrea.grillo@yithemes.com>
		 */
		public function plugin_fw_loader() {

			if ( ! defined( 'YIT_CORE_PLUGIN' ) ) {
				global $plugin_fw_data;
				if( ! empty( $plugin_fw_data ) ){
					$plugin_fw_file = array_shift( $plugin_fw_data );
					require_once( $plugin_fw_file );
				}
			}
		}

		/**
		 * Delete plugin transient on product save
		 *
		 * @since 1.5.0
		 * @author Francesco Licandro
		 * @param object $product \WC_Product
		 * @param array $data
		 */
		public function delete_transient_on_save( $product, $data ) {
			delete_transient( 'yith_wccl_get_available_variations_' . $product->get_id() );
		}

		/**
		 * Add new attribute types to standard WooCommerce
		 *
		 * @since 1.5.0
		 * @author Francesco Licandro <francesco.licandro@yithemes.com>
		 * @param array $default_type
		 * @return array
		 */
		public function attribute_types( $default_type ){
			$custom = ywccl_get_custom_tax_types();
			return is_array( $custom ) ? array_merge( $default_type, $custom ) : $default_type;
		}
	}
}

/**
 * Unique access to instance of YITH_WCCL class
 *
 * @return \YITH_WCCL
 * @since 1.0.0
 */
function YITH_WCCL(){
	return YITH_WCCL::get_instance();
}