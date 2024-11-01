<?php

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * HELPER COMMENT START
 * 
 * This class contains all of the plugin related settings.
 * Everything that is relevant data and used multiple times throughout 
 * the plugin.
 * 
 * To define the actual values, we recommend adding them as shown above
 * within the __construct() function as a class-wide variable. 
 * This variable is then used by the callable functions down below. 
 * These callable functions can be called everywhere within the plugin 
 * as followed using the get_plugin_name() as an example: 
 * 
 * UTMFORWOO->settings->get_plugin_name();
 * 
 * HELPER COMMENT END
 */

/**
 * Class Utm_For_Woocommerce_Settings
 *
 * This class contains all of the plugin settings.
 * Here you can configure the whole plugin data.
 *
 * @package		UTMFORWOO
 * @subpackage	Classes/Utm_For_Woocommerce_Settings
 * @author		Spanrig Technologies
 * @since		1.0.0
 */
if ( ! class_exists( 'Utm_For_Woocommerce_Settings' ) ) :
	class Utm_For_Woocommerce_Settings{

		/**
		 * The plugin name
		 *
		 * @var		string
		 * @since   1.0.0
		 */
		private $plugin_name;

		/**
		 * Our Utm_For_Woocommerce_Settings constructor 
		 * to run the plugin logic.
		 *
		 * @since 1.0.0
		 */
		function __construct(){

			$this->plugin_name = UTMFORWOO_NAME;
		}

		/**
		 * ######################
		 * ###
		 * #### CALLABLE FUNCTIONS
		 * ###
		 * ######################
		 */

		/**
		 * Return the plugin name
		 *
		 * @access	public
		 * @since	1.0.0
		 * @return	string The plugin name
		 */
		public function get_plugin_name(){
			return apply_filters( 'UTMFORWOO/settings/get_plugin_name', $this->plugin_name );
		}
	}
endif;