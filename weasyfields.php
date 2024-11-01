<?php defined( 'ABSPATH' ) || exit;

/**
 * @package WeasyFields Basic
 * @version 1.0.1
 * 
 * Plugin Name: WeasyFields Basic
 * Version:     1.0.1
 * Plugin URI:  https://1.envato.market/NnxA2
 * Description: WooCommerce easy custom fields plugin, Product Fields, Checkout Fields
 * Author:      BeycanPress
 * Author URI:  https://beycanpress.com
 * License:     GPLv2 or later
 * License URI: http://www.gnu.org/licenses/old-licenses/gpl-2.0.html
 * Text Domain: weasyfields
 * Domain Path: /languages
 * Tags: WooCommerce custom fields plugin, WooCommerce easy custom fields, WooCommerce product fields plugin, WooCommerce checkout fields, WooCommerce billing fields, WooCommerce shipping fields, WooCommerce dyanmic product fields
 * Requires at least: 5.0
 * Tested up to: 5.8
 * Requires PHP: 7.3
*/

// Constants
define( 'WEASYFIELDS_URL', trailingslashit( plugin_dir_url( __FILE__ ) ) );
define( 'WEASYFIELDS_PATH', trailingslashit( plugin_dir_path( __FILE__ ) ) );
define( 'WEASYFIELDS_VIEW_DIR', trailingslashit( WEASYFIELDS_PATH . 'views' ) );
define( 'WEASYFIELDS_LANG_DIR', dirname( plugin_basename( __FILE__ ) ) . '/languages' );
define( 'WEASYFIELDS_PLUGIN_FILE', __FILE__ );
define( 'WEASYFIELDS_VERSION', '1.0.1' );

// Loader include
require_once WEASYFIELDS_PATH . 'includes/loader.php';