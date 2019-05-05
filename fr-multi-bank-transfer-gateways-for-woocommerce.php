<?php
/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @package         Fr_Multi_Bank_Transfer_Gateways_For_Woocommerce
 *
 * @wordpress-plugin
 * Plugin Name:     Fr Multi Bank Transfer Payment Gateways for WooCommerce
 * Plugin URI:      https://wordpress.org/plugins/fr-multi-bank-transfer-payment-gateways-for-woocommerce/
 * Description:     Add multiple bank transfer payment gateways.
 * Version:         1.1.0
 * Author:          Fahri Rusliyadi
 * Author URI:      https://profiles.wordpress.org/fahrirusliyadi
 * License:         GPL-2.0+
 * License URI:     http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:     fr-multi-bank-transfer-gateways-for-woocommerce
 * Domain Path:     /languages
 * WC tested up to: 3.6.2
 */

// If this file is called directly, abort.
defined( 'ABSPATH' ) || die;

/**
 * Currently plugin version.
 * Start at version 1.0.0 and use SemVer - https://semver.org
 * Rename this for your plugin and update it as you release new versions.
 *
 * @since 1.0.1
 */
define( 'FR_MULTI_BANK_TRANSFER_GATEWAYS_FOR_WOOCOMMERCE_VERSION', '1.1.0' );

/**
 * The full path and filename of this file.
 *
 * @since 1.0.2
 */
define( 'FR_MULTI_BANK_TRANSFER_GATEWAYS_FOR_WOOCOMMERCE_FILE', __FILE__ );

/**
 * The directory of this file.
 *
 * @since 1.1.0
 */
define( 'FR_MULTI_BANK_TRANSFER_GATEWAYS_FOR_WOOCOMMERCE_DIR', plugin_dir_path( __FILE__ ) );

require FR_MULTI_BANK_TRANSFER_GATEWAYS_FOR_WOOCOMMERCE_DIR . 'includes/class-fr-multi-bank-transfer-gateways-for-woocommerce-container-entry-factory-interface.php';
require FR_MULTI_BANK_TRANSFER_GATEWAYS_FOR_WOOCOMMERCE_DIR . 'includes/class-fr-multi-bank-transfer-gateways-for-woocommerce-container.php';
require FR_MULTI_BANK_TRANSFER_GATEWAYS_FOR_WOOCOMMERCE_DIR . 'includes/class-fr-multi-bank-transfer-gateways-for-woocommerce-factory.php';
require FR_MULTI_BANK_TRANSFER_GATEWAYS_FOR_WOOCOMMERCE_DIR . 'includes/class-fr-multi-bank-transfer-gateways-for-woocommerce.php';
require FR_MULTI_BANK_TRANSFER_GATEWAYS_FOR_WOOCOMMERCE_DIR . 'includes/class-fr-multi-bank-transfer-gateways-for-woocommerce-i18n.php';
require FR_MULTI_BANK_TRANSFER_GATEWAYS_FOR_WOOCOMMERCE_DIR . 'includes/class-fr-multi-bank-transfer-gateways-for-woocommerce-loader-factory.php';
require FR_MULTI_BANK_TRANSFER_GATEWAYS_FOR_WOOCOMMERCE_DIR . 'includes/class-fr-multi-bank-transfer-gateways-for-woocommerce-loader.php';
require FR_MULTI_BANK_TRANSFER_GATEWAYS_FOR_WOOCOMMERCE_DIR . 'admin/class-fr-multi-bank-transfer-gateways-for-woocommerce-admin.php';

/**
 * Begins execution of the plugin.
 *
 * @since 1.0.0
 * @author Fahri Rusliyadi <fahri.rusliyadi@gmail.com>
 */
function fr_multi_bank_transfer_gateways_for_woocommerce_run() {
	$plugin = require FR_MULTI_BANK_TRANSFER_GATEWAYS_FOR_WOOCOMMERCE_DIR . 'bootstrap/plugin.php';
	$plugin->run();
}
// Priority 5, in case we also have services that need to hook on plugins_loaded action.
add_action( 'plugins_loaded', 'fr_multi_bank_transfer_gateways_for_woocommerce_run', 5 );
