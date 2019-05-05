<?php
/**
 * Fr_Multi_Bank_Transfer_Gateways_For_Woocommerce_Factory class.
 *
 * @package Fr_Multi_Bank_Transfer_Gateways_For_Woocommerce
 */

defined( 'ABSPATH' ) || die;

/**
 * Plugin factory.
 *
 * @since 1.1.0
 * @author Fahri Rusliyadi <fahri.rusliyadi@gmail.com>
 */
class Fr_Multi_Bank_Transfer_Gateways_For_Woocommerce_Factory implements Fr_Multi_Bank_Transfer_Gateways_For_Woocommerce_Container_Entry_Factory_Interface {
	/**
	 * Create plugin instance.
	 *
	 * @since 1.1.0
	 * @param Fr_Multi_Bank_Transfer_Gateways_For_Woocommerce_Container $container Dependency injection container.
	 * @return Fr_Multi_Bank_Transfer_Gateways_For_Woocommerce Plugin instance.
	 */
	public function create( Fr_Multi_Bank_Transfer_Gateways_For_Woocommerce_Container $container ) {
		return new Fr_Multi_Bank_Transfer_Gateways_For_Woocommerce(
			$container->get( 'Fr_Multi_Bank_Transfer_Gateways_For_Woocommerce_Loader' )
		);
	}
}
