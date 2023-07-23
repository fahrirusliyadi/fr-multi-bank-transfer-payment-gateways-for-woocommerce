<?php
/**
 * Fr_Multi_Bank_Transfer_Gateways_For_Woocommerce_Compat class.
 *
 * @package Fr_Multi_Bank_Transfer_Gateways_For_Woocommerce
 */

defined( 'ABSPATH' ) || die;

/**
 * Define compatibility with other plugins.
 *
 * @since 1.1.1
 * @author Fahri Rusliyadi <fahri.rusliyadi@gmail.com>
 */
class Fr_Multi_Bank_Transfer_Gateways_For_Woocommerce_Compat {
	/**
	 * Declare HPOS compatibility.
	 */
	public function custom_order_tables() {
		if ( class_exists( \Automattic\WooCommerce\Utilities\FeaturesUtil::class ) ) {
			\Automattic\WooCommerce\Utilities\FeaturesUtil::declare_compatibility( 'custom_order_tables', FR_MULTI_BANK_TRANSFER_GATEWAYS_FOR_WOOCOMMERCE_FILE, true );
		}
	}
}
