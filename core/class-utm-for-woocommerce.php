<?php

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * HELPER COMMENT START
 * 
 * This is the main class that is responsible for registering
 * the core functions, including the files and setting up all features. 
 * 
 * To add a new class, here's what you need to do: 
 * 1. Add your new class within the following folder: core/includes/classes
 * 2. Create a new variable you want to assign the class to (as e.g. public $helpers)
 * 3. Assign the class within the instance() function ( as e.g. self::$instance->helpers = new Utm_For_Woocommerce_Helpers();)
 * 4. Register the class you added to core/includes/classes within the includes() function
 * 
 * HELPER COMMENT END
 */

if ( ! class_exists( 'Utm_For_Woocommerce' ) ) :

	/**
	 * Main Utm_For_Woocommerce Class.
	 *
	 * @package		UTMFORWOO
	 * @subpackage	Classes/Utm_For_Woocommerce
	 * @since		1.0.0
	 * @author		Spanrig Technologies
	 */
	final class Utm_For_Woocommerce {

		/**
		 * The real instance
		 *
		 * @access	private
		 * @since	1.0.0
		 * @var		object|Utm_For_Woocommerce
		 */
		private static $instance;

		/**
		 * UTMFORWOO helpers object.
		 *
		 * @access	public
		 * @since	1.0.0
		 * @var		object|Utm_For_Woocommerce_Helpers
		 */
		public $helpers;

		/**
		 * UTMFORWOO settings object.
		 *
		 * @access	public
		 * @since	1.0.0
		 * @var		object|Utm_For_Woocommerce_Settings
		 */
		public $settings;

		/**
		 * Throw error on object clone.
		 *
		 * Cloning instances of the class is forbidden.
		 *
		 * @access	public
		 * @since	1.0.0
		 * @return	void
		 */
		public function __clone() {
			_doing_it_wrong( __FUNCTION__, __( 'You are not allowed to clone this class.', 'utm-for-woocommerce' ), '1.0.0' );
		}

		/**
		 * Disable unserializing of the class.
		 *
		 * @access	public
		 * @since	1.0.0
		 * @return	void
		 */
		public function __wakeup() {
			_doing_it_wrong( __FUNCTION__, __( 'You are not allowed to unserialize this class.', 'utm-for-woocommerce' ), '1.0.0' );
		}

		/**
		 * Main Utm_For_Woocommerce Instance.
		 *
		 * Insures that only one instance of Utm_For_Woocommerce exists in memory at any one
		 * time. Also prevents needing to define globals all over the place.
		 *
		 * @access		public
		 * @since		1.0.0
		 * @static
		 * @return		object|Utm_For_Woocommerce	The one true Utm_For_Woocommerce
		 */
		public static function instance() {
			if ( ! isset( self::$instance ) && ! ( self::$instance instanceof Utm_For_Woocommerce ) ) {
				self::$instance					= new Utm_For_Woocommerce;
				self::$instance->base_hooks();
				self::$instance->includes();
				self::$instance->helpers		= new Utm_For_Woocommerce_Helpers();
				self::$instance->settings		= new Utm_For_Woocommerce_Settings();

				//Fire the plugin logic
				new Utm_For_Woocommerce_Run();

				/**
				 * Fire a custom action to allow dependencies
				 * after the successful plugin setup
				 */
				do_action( 'UTMFORWOO/plugin_loaded' );
			}

			return self::$instance;
		}

		/**
		 * Include required files.
		 *
		 * @access  private
		 * @since   1.0.0
		 * @return  void
		 */
		private function includes() {
			require_once UTMFORWOO_PLUGIN_DIR . 'core/includes/classes/class-utm-for-woocommerce-helpers.php';
			require_once UTMFORWOO_PLUGIN_DIR . 'core/includes/classes/class-utm-for-woocommerce-settings.php';

			require_once UTMFORWOO_PLUGIN_DIR . 'core/includes/classes/class-utm-for-woocommerce-run.php';
		}

		/**
		 * Add base hooks for the core functionality
		 *
		 * @access  private
		 * @since   1.0.0
		 * @return  void
		 */
		private function base_hooks() {
			add_action( 'plugins_loaded', array( self::$instance, 'load_textdomain' ) );
			add_action('before_woocommerce_init', array( self::$instance, 'utmforwoo_add_hpos_compatibility'));
		}

		/**
		 * Loads the plugin language files.
		 *
		 * @access  public
		 * @since   1.0.0
		 * @return  void
		 */
		public function load_textdomain() {
			load_plugin_textdomain( 'utm-for-woocommerce', FALSE, dirname( plugin_basename( UTMFORWOO_PLUGIN_FILE ) ) . '/languages/' );
		}

		/**
		 * Adds compatibility with Woocommerce HPOS.
		 *
		 * @access  public
		 * @since   1.0.0
		 * @return  void
		 */
		public function utmforwoo_add_hpos_compatibility(){
			if ( class_exists( \Automattic\WooCommerce\Utilities\FeaturesUtil::class ) ) {
				\Automattic\WooCommerce\Utilities\FeaturesUtil::declare_compatibility( 'custom_order_tables', UTMFORWOO_PLUGIN_FILE, true );
			}
		}

	}

endif; // End if class_exists check.