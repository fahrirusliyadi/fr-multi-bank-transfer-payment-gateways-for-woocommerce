<?php
/**
 * Bootstrap the plugin.
 *
 * @since 1.1.0
 * @package Fr_Multi_Bank_Transfer_Gateways_For_Woocommerce
 * @author Fahri Rusliyadi <fahri.rusliyadi@gmail.com>
 */

// phpcs:disable WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedVariableFound
// This file is not included in global scope. See fr_multi_bank_transfer_gateways_for_woocommerce_run().

defined( 'ABSPATH' ) || die;

$services  = require FR_MULTI_BANK_TRANSFER_GATEWAYS_FOR_WOOCOMMERCE_DIR . 'config/services.php';
$hooks     = require FR_MULTI_BANK_TRANSFER_GATEWAYS_FOR_WOOCOMMERCE_DIR . 'config/hooks.php';
$container = new Fr_Multi_Bank_Transfer_Gateways_For_Woocommerce_Container();

$container->set( 'config', compact( 'hooks' ) );

foreach ( $services['factories'] as $name => $factory ) {
	$container->set_factory( $name, $factory );
}

$plugin = $container->get( 'Fr_Multi_Bank_Transfer_Gateways_For_Woocommerce' );

return $plugin;
