<?php
/**
 * Fr_Multi_Bank_Transfer_Gateways_For_Woocommerce_I18n class.
 *
 * @package Fr_Multi_Bank_Transfer_Gateways_For_Woocommerce
 */

defined( 'ABSPATH' ) || die;

/**
 * Define the internationalization functionality.
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @since 1.0.0
 * @author Fahri Rusliyadi <fahri.rusliyadi@gmail.com>
 */
class Fr_Multi_Bank_Transfer_Gateways_For_Woocommerce_I18n {
	/**
	 * Load the plugin text domain for translation.
	 *
	 * @since 1.0.0
	 */
	public function load_plugin_textdomain() {
		load_plugin_textdomain( 'fr-multi-bank-transfer-gateways-for-woocommerce', false, plugin_basename( FR_MULTI_BANK_TRANSFER_GATEWAYS_FOR_WOOCOMMERCE_DIR ) . '/languages/' );
	}
}
