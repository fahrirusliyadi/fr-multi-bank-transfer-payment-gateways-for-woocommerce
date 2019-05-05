<?php
/**
 * PHPUnit bootstrap file
 *
 * @package Fr_Multi_Bank_Transfer_Payment_Gateways_For_Woocommerce
 * @author Fahri Rusliyadi <fahri.rusliyadi@gmail.com>
 */

$_fr_multi_bank_transfer_gateways_for_woocommerce_tests_dir = getenv( 'WP_TESTS_DIR' );

if ( ! $_fr_multi_bank_transfer_gateways_for_woocommerce_tests_dir ) {
	$_fr_multi_bank_transfer_gateways_for_woocommerce_tests_dir = rtrim( sys_get_temp_dir(), '/\\' ) . '/wordpress-tests-lib';
}

if ( ! file_exists( $_fr_multi_bank_transfer_gateways_for_woocommerce_tests_dir . '/includes/functions.php' ) ) {
	echo "Could not find $_fr_multi_bank_transfer_gateways_for_woocommerce_tests_dir/includes/functions.php, have you run bin/install-wp-tests.sh ?" . PHP_EOL; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
	exit( 1 );
}

// Give access to tests_add_filter() function.
require_once $_fr_multi_bank_transfer_gateways_for_woocommerce_tests_dir . '/includes/functions.php';

/**
 * Manually load the plugin being tested.
 */
function _fr_multi_bank_transfer_gateways_for_woocommerce_manually_load_plugin() {
	require WP_PLUGIN_DIR . '/woocommerce/woocommerce.php';
	require dirname( dirname( __FILE__ ) ) . '/fr-multi-bank-transfer-gateways-for-woocommerce.php';
}
tests_add_filter( 'muplugins_loaded', '_fr_multi_bank_transfer_gateways_for_woocommerce_manually_load_plugin' );

// Start up the WP testing environment.
require $_fr_multi_bank_transfer_gateways_for_woocommerce_tests_dir . '/includes/bootstrap.php';
