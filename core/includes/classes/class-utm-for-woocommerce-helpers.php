<?php

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * Class Utm_For_Woocommerce_Helpers
 *
 * This class contains repetitive functions that
 * are used globally within the plugin.
 *
 * @package		UTMFORWOO
 * @subpackage	Classes/Utm_For_Woocommerce_Helpers
 * @author		Spanrig Technologies
 * @since		1.0.0
 */
if ( ! class_exists( 'Utm_For_Woocommerce_Helpers' ) ) :
	class Utm_For_Woocommerce_Helpers{

		/**
		 * ######################
		 * ###
		 * #### CALLABLE FUNCTIONS
		 * ###
		 * ######################
		 */

		/**
		 * HELPER COMMENT START
		 *
		 * Within this class, you can define common functions that you are 
		 * going to use throughout the whole plugin. 
		 * 
		 * UTMFORWOO()->helpers->ufw_get_params();
		 * 
		 */

		public function ufw_get_params(){
			// Define the list of parameters we want to capture
			$parameters = ['utm_source', 'utm_medium', 'utm_campaign', 'utm_term', 'utm_content', 'gclid', 'fbclid'];

			// Apply a filter to allow other developers to modify the parameters
			return apply_filters('utmforwoo_parameters', $parameters);
		}

		/**
		 * Get the order object with HPOS compatibility.
		 *
		 * @param WC_Order|WP_Post|int $post_or_order The post ID or object.
		 *
		 * @return WC_Order The order object
		 * @throws Exception When the order isn't found.
		 */
		public function ufw_get_hpos_order_object( $post_or_order ) {
			// If we've already got an order object, just return it.
			if ( $post_or_order instanceof WC_Order ) {
				return $post_or_order;
			}

			// If we have a post ID, get the post object.
			if ( is_numeric( $post_or_order ) ) {
				$post_or_order = wc_get_order( $post_or_order );
			}

			// Throw an exception if we don't have an order object.
			if ( ! $post_or_order instanceof WC_Order ) {
				throw new Exception( __( 'Order not found.', 'woocommerce' ) );
			}

			return $post_or_order;
		}

		/**
		 * HELPER COMMENT END
		*/

	}
endif;
