<?php
/**
 * Class PluginFactoryTest
 *
 * @package Fr_Multi_Bank_Transfer_Payment_Gateways_For_Woocommerce
 * @author Fahri Rusliyadi <fahri.rusliyadi@gmail.com>
 */

/**
 * Core plugin factory class test case.
 */
class PluginFactoryTest extends WP_UnitTestCase {
	/**
	 * Create plugin test.
	 */
	public function test_create() {
		$container = Mockery::mock( 'Fr_Multi_Bank_Transfer_Gateways_For_Woocommerce_Container' );
		$factory   = new Fr_Multi_Bank_Transfer_Gateways_For_Woocommerce_Factory();

		$container->shouldReceive( 'get' )->once()->andReturn( new Fr_Multi_Bank_Transfer_Gateways_For_Woocommerce_Loader() );
		$this->assertInstanceOf( 'Fr_Multi_Bank_Transfer_Gateways_For_Woocommerce', $factory->create( $container ) );
	}
}
