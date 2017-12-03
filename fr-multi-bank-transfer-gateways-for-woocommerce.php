<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              https://example.com
 * @since             1.0.0
 * @package           Fr_Multi_Bank_Transfer_Gateways_For_Woocommerce
 *
 * @wordpress-plugin
 * Plugin Name:       Fr Multi Bank Transfer Payment Gateways for WooCommerce
 * Plugin URI:		  https://wordpress.org/plugins/fr-multi-bank-transfer-payment-gateways-for-woocommerce/
 * Description:       Add multiple bank transfer payment gateways.
 * Version:           1.0.0
 * Author:            Fahri Rusliyadi
 * Author URI:        https://profiles.wordpress.org/fahrirusliyadi
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       fr-multi-bank-transfer-gateways-for-woocommerce
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-fr-multi-bank-transfer-gateways-for-woocommerce.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_fr_multi_bank_transfer_gateways_for_woocommerce() {

	$plugin = new Fr_Multi_Bank_Transfer_Gateways_For_Woocommerce();
	$plugin->run();

}
run_fr_multi_bank_transfer_gateways_for_woocommerce();
