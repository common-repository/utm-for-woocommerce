<?php

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * HELPER COMMENT START
 * 
 * This class is used to bring your plugin to life. 
 * All the other registered classed bring features which are
 * controlled and managed by this class.
 * 
 * Within the add_hooks() function, you can register all of 
 * your WordPress related actions and filters as followed:
 * 
 * add_action( 'my_action_hook_to_call', array( $this, 'the_action_hook_callback', 10, 1 ) );
 * or
 * add_filter( 'my_filter_hook_to_call', array( $this, 'the_filter_hook_callback', 10, 1 ) );
 * or
 * add_shortcode( 'my_shortcode_tag', array( $this, 'the_shortcode_callback', 10 ) );
 * 
 * Once added, you can create the callback function, within this class, as followed: 
 * 
 * public function the_action_hook_callback( $some_variable ){}
 * or
 * public function the_filter_hook_callback( $some_variable ){}
 * or
 * public function the_shortcode_callback( $attributes = array(), $content = '' ){}
 * 
 * 
 * HELPER COMMENT END
 */

/**
 * Class Utm_For_Woocommerce_Run
 *
 * Thats where we bring the plugin to life
 *
 * @package		UTMFORWOO
 * @subpackage	Classes/Utm_For_Woocommerce_Run
 * @author		Spanrig Technologies
 * @since		1.0.0
 */
if ( ! class_exists( 'Utm_For_Woocommerce_Run' ) ) :
	class Utm_For_Woocommerce_Run{

		/**
		 * The accepted parameters
		 *
		 * @var		string
		 * @since   1.0.0
		 */
		private $params;

		/**
		 * Our Utm_For_Woocommerce_Run constructor 
		 * to run the plugin logic.
		 *
		 * @since 1.0.0
		 */
		function __construct(){
			$this->add_hooks();
			$this->params = UTMFORWOO()->helpers->ufw_get_params();
		}

		/**
		 * ######################
		 * ###
		 * #### WORDPRESS HOOKS
		 * ###
		 * ######################
		 */

		/**
		 * Registers all WordPress and plugin related hooks
		 *
		 * @access	private
		 * @since	1.0.0
		 * @return	void
		 */
		private function add_hooks(){
			
			add_action( 'plugin_action_links_' . UTMFORWOO_PLUGIN_BASE, array( $this, 'utmforwoo_add_plugin_action_link' ), 20 );

			add_action('init', array( $this, 'utmforwoo_capture_utm_and_clid'));

			add_action('woocommerce_checkout_update_order_meta', array( $this, 'utmforwoo_save_utm_and_clid_order_meta'));

			add_filter('manage_edit-shop_order_columns', array( $this, 'utmforwoo_add_utm_and_clid_order_columns'), 20);
			
			add_filter('manage_woocommerce_page_wc-orders_columns', array( $this, 'utmforwoo_add_utm_and_clid_order_columns'), 20);

			add_action('manage_shop_order_posts_custom_column', array( $this, 'utmforwoo_add_utm_and_clid_order_column_content'), 10, 2);

			add_action('manage_woocommerce_page_wc-orders_custom_column', array( $this, 'utmforwoo_add_utm_and_clid_order_column_content'), 10, 2);

			add_action( 'add_meta_boxes', array($this, 'utmforwoo_order_meta_box' ));

			add_filter('woo_ca_session_abandoned_data', array( $this, 'utmforwoo_save_utm_in_wcf'), 10);

		}

		

		/**
		 * ######################
		 * ###
		 * #### WORDPRESS HOOK CALLBACKS
		 * ###
		 * ######################
		 */

		/**
		* Adds action links to the plugin list table
		*
		* @access	public
		* @since	1.0.0
		*
		* @param	array	$links An array of plugin action links.
		*
		* @return	array	An array of plugin action links.
		*/
		public function utmforwoo_add_plugin_action_link( $links ) {

			$links['our_shop'] = sprintf( '<a href="%s" target="_blank title="https://rzp.io/l/hncvj" style="font-weight:700;">%s</a>', 'https://rzp.io/l/hncvj', __( 'Support Us', 'utm-for-woocommerce' ) );

			return $links;
		}


		/**
		 * Captures UTM parameters and click identifiers (gclid and fbclid) from the URL and stores them in the user's session.
		 *
		 * @access public
		 * @since 1.0.0
		 *
		 * This function checks if a session is already started, if not, it starts a new session. It then defines a list of parameters 
		 * that we want to capture. It loops through each parameter and checks if the parameter is set in the URL. If the parameter is 
		 * set, it sanitizes it to prevent XSS attacks and saves it in the session.
		 *
		 * @return void
		 */
		public function utmforwoo_capture_utm_and_clid() {
			if (!session_id()) {
				session_start();
			}
		
			$parameters = $this->params;
		
			foreach ($parameters as $parameter) {
				if (isset($_GET[$parameter])) {
					$_SESSION[$parameter] = sanitize_text_field($_GET[$parameter]);
				}
			}
		}


		/**
		 * Saves UTM parameters and click identifiers (gclid and fbclid) from the user's session to the order meta.
		 *
		 * @access public
		 * @since 1.0.0
		 *
		 * This function takes an order ID as a parameter. It then defines a list of parameters that we want to save. 
		 * It loops through each parameter and checks if the parameter is set in the session. If the parameter is set, 
		 * it saves it to the order meta using the update_meta_data function.
		 *
		 * @param int $order_id The ID of the order to which the parameters should be saved.
		 * @return void
		 */
		public function utmforwoo_save_utm_and_clid_order_meta($order_id) {
			$order = UTMFORWOO()->helpers->ufw_get_hpos_order_object( $order_id );
			$parameters = $this->params;

			foreach ($parameters as $parameter) {
				if (isset($_SESSION[$parameter])) {
					$order->update_meta_data( $parameter, sanitize_text_field($_SESSION[$parameter]) );
				}
			}
			$order->save();
		}

		/**
		 * Add meta box to the Order edit screen.
		 *
		 * @return void
		 */
		public function utmforwoo_order_meta_box() {
			add_meta_box(
				'utmforwoo_order_meta_box',
				__( 'UTM for WOO Order Tracking Information', 'woocommerce' ),
				array($this, 'utmforwoo_order_meta_box_callback'),
				'shop_order',
				'side',
				'core'
			);
		}


		/**
		 * Callback function for UTM for WOO Tracking Information meta box.
		 *
		 * @param object $post Post object.
		 *
		 * @return void
		 */
		function utmforwoo_order_meta_box_callback( $post ) {

			$order = UTMFORWOO()->helpers->ufw_get_hpos_order_object( $post->ID );

			$parameters = $this->params;
			echo '<table style="width: 100%;">';
			foreach ($parameters as $parameter) {
					echo '<tr><td>'.ucwords(str_replace('_', ' ', $parameter)).'</td><td>'.$order->get_meta( $parameter, true ).'</td></tr>';
			}
			echo '</table>';
		}



		/**
		 * Saves UTM parameters and click identifiers (gclid and fbclid) from the user's session to the WCF data.
		 *
		 * @access public
		 * @since 1.0.0
		 *
		 * This function is executed to store the UTM params against WCF plugin order data.
		 *
		 * @param array $checkout_details The array of the order data of WCF plugin.
		 * @return array
		 */
		public function utmforwoo_save_utm_in_wcf($checkout_details) {
			$parameters = $this->params;

			if(isset($checkout_details) && !empty($checkout_details)){
				if(is_serialized($checkout_details['other_fields'])) {
					$checkout_details['other_fields'] = unserialize($checkout_details['other_fields']);
				}

				foreach ($parameters as $parameter) {
					if (isset($_SESSION[$parameter])) {
						$checkout_details['other_fields']['wcf_'.$parameter] = sanitize_text_field($_SESSION[$parameter]);
					}
				}

				$checkout_details['other_fields'] = maybe_serialize($checkout_details['other_fields']);
			}

			return $checkout_details;
		}


		/**
		 * Adds UTM parameters and click identifiers (gclid and fbclid) as new columns in the WooCommerce orders list screen.
		 *
		 * @access public
		 * @since 1.0.0
		 *
		 * This function takes an array of existing columns as a parameter. It then creates a new array of columns, 
		 * copying the existing columns to it. When it encounters the 'order_total' column, it adds new columns for 
		 * each UTM parameter and click identifier. The new columns are added after the 'order_total' column.
		 *
		 * @param array $columns An array of existing columns.
		 * @return array $new_columns An array of modified columns.
		 */
		public function utmforwoo_add_utm_and_clid_order_columns($columns) {
			$new_columns = [];
		
			foreach ($columns as $column_name => $column_info) {
				$new_columns[$column_name] = $column_info;
		
				if ('order_total' === $column_name) {
					$parameters = $this->params;
		
					foreach ($parameters as $parameter) {
						$name = ucwords(str_replace('_', ' ', $parameter));
						$new_columns[$parameter] = __($name, 'woocommerce');
					}
				}
			}
		
			return $new_columns;
		}


		/**
		 * Displays the content for the UTM parameters and click identifiers (gclid and fbclid) columns in the WooCommerce orders list screen.
		 *
		 * @access public
		 * @since 1.0.0
		 *
		 * This function takes a column name as a parameter. If the column name is one of the UTM parameters or click identifiers, 
		 * it retrieves the value of the parameter from the order meta and displays it. If the value is not set, it displays a dash.
		 *
		 * @global WP_Post $post The current post object which is being displayed.
		 * @param string $column The name of the column to display.
		 * @return void
		 */
		public function utmforwoo_add_utm_and_clid_order_column_content($column, $order_id) {
		
			try {
				
				if (in_array($column, $this->params)) {
					$order = UTMFORWOO()->helpers->ufw_get_hpos_order_object( $order_id );
					$value = $order->get_meta($column);
			
					if (!empty($value)) {
						echo esc_html($value);
					} else {
						echo '-';
					}
				}
				
			} catch ( Exception $e ) {
				return;
			}
			
		}

	}
endif;