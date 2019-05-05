<?php
/**
 * Dependency injection container entry definitions.
 *
 * @since 1.1.0
 * @package Fr_Multi_Bank_Transfer_Gateways_For_Woocommerce
 * @author Fahri Rusliyadi <fahri.rusliyadi@gmail.com>
 */

// phpcs:disable WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedVariableFound
// This file is not included in global scope. See bootstrap/plugin.php.

defined( 'ABSPATH' ) || die;

$services = array(
	'factories' => array(
		'Fr_Multi_Bank_Transfer_Gateways_For_Woocommerce' => 'Fr_Multi_Bank_Transfer_Gateways_For_Woocommerce_Factory',
		'Fr_Multi_Bank_Transfer_Gateways_For_Woocommerce_Loader' => 'Fr_Multi_Bank_Transfer_Gateways_For_Woocommerce_Loader_Factory',
	),
);

/**
 * Filter the service definitions.
 *
 * @since 1.1.0
 * @param array $services Services definitions. [
 *      factories => (array) Factory definitions [
 *          (string) Entry ID => (string) Factory class name.
 *      ]
 *  ].
 */
return apply_filters( 'fr_multi_bank_transfer_gateways_for_woocommerce_services', $services );
