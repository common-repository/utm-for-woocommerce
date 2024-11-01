<?php
/**
 * UTM for Woocommerce
 *
 * @package       UTMFORWOO
 * @author        Spanrig Technologies
 * @license       gplv3-or-later
 * @version       1.0.1
 *
 * @wordpress-plugin
 * Plugin Name:   UTM for Woocommerce
 * Plugin URI:    https://spanrig.com
 * Description:   Simply track UTM parameters in Woocommerce orders.
 * Version:       1.0.1
 * Author:        Spanrig Technologies
 * Author URI:    https://www.upwork.com/fl/hncvj
 * Text Domain:   utm-for-woocommerce
 * Domain Path:   /languages
 * License:       GPLv3 or later
 * License URI:   https://www.gnu.org/licenses/gpl-3.0.html
 *
 * You should have received a copy of the GNU General Public License
 * along with UTM for Woocommerce. If not, see <https://www.gnu.org/licenses/gpl-3.0.html/>.
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * HELPER COMMENT START
 * 
 * This file contains the main information about the plugin.
 * It is used to register all components necessary to run the plugin.
 * 
 * The comment above contains all information about the plugin 
 * that are used by WordPress to differenciate the plugin and register it properly.
 * It also contains further PHPDocs parameter for a better documentation
 * 
 * The function UTMFORWOO() is the main function that you will be able to 
 * use throughout your plugin to extend the logic. Further information
 * about that is available within the sub classes.
 * 
 * HELPER COMMENT END
 */

// Plugin name
define( 'UTMFORWOO_NAME',			'UTM for Woocommerce' );

// Plugin version
define( 'UTMFORWOO_VERSION',		'1.0.1' );

// Plugin Root File
define( 'UTMFORWOO_PLUGIN_FILE',	__FILE__ );

// Plugin base
define( 'UTMFORWOO_PLUGIN_BASE',	plugin_basename( UTMFORWOO_PLUGIN_FILE ) );

// Plugin Folder Path
define( 'UTMFORWOO_PLUGIN_DIR',	plugin_dir_path( UTMFORWOO_PLUGIN_FILE ) );

// Plugin Folder URL
define( 'UTMFORWOO_PLUGIN_URL',	plugin_dir_url( UTMFORWOO_PLUGIN_FILE ) );

/**
 * Load the main class for the core functionality
 */
require_once UTMFORWOO_PLUGIN_DIR . 'core/class-utm-for-woocommerce.php';

/**
 * The main function to load the only instance
 * of our master class.
 *
 * @author  Spanrig Technologies
 * @since   1.0.0
 * @return  object|Utm_For_Woocommerce
 */
function UTMFORWOO() {
	return Utm_For_Woocommerce::instance();
}

UTMFORWOO();
