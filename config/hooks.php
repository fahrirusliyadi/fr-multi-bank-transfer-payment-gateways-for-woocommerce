<?php
/**
 * Hook definitions.
 *
 * @since 1.1.0
 * @package Fr_Multi_Bank_Transfer_Gateways_For_Woocommerce
 * @author Fahri Rusliyadi <fahri.rusliyadi@gmail.com>
 */

defined( 'ABSPATH' ) || die;

return array(
	array( 'action', 'plugins_loaded', 'Fr_Multi_Bank_Transfer_Gateways_For_Woocommerce_i18n', 'load_plugin_textdomain' ),

	array( 'action', 'plugin_action_links_' . plugin_basename( FR_MULTI_BANK_TRANSFER_GATEWAYS_FOR_WOOCOMMERCE_FILE ), 'Fr_Multi_Bank_Transfer_Gateways_For_Woocommerce_Admin', 'add_action_links' ),
	array( 'filter', 'woocommerce_get_settings_checkout', 'Fr_Multi_Bank_Transfer_Gateways_For_Woocommerce_Admin', 'add_custom_checkout_settings' ),
	array( 'filter', 'woocommerce_payment_gateways', 'Fr_Multi_Bank_Transfer_Gateways_For_Woocommerce_Admin', 'add_gateway_classes' ),
);
