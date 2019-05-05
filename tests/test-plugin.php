<?php
/**
 * Class PluginTest
 *
 * @package Fr_Multi_Bank_Transfer_Payment_Gateways_For_Woocommerce
 * @author Fahri Rusliyadi <fahri.rusliyadi@gmail.com>
 */

/**
 * Core plugin class test case.
 */
class PluginTest extends WP_UnitTestCase {
	/**
	 * Run plugin test.
	 */
	public function test_run() {
		$loader  = new Fr_Multi_Bank_Transfer_Gateways_For_Woocommerce_Loader();
		$plugin  = new Fr_Multi_Bank_Transfer_Gateways_For_Woocommerce( $loader );
		$service = Mockery::mock();

		$loader->add_action( 'sample_action', $service, 'sample_method' );

		$this->assertFalse( has_action( 'sample_action', array( $service, 'sample_method' ) ) );
		$plugin->run();
		$this->assertSame( 10, has_action( 'sample_action', array( $service, 'sample_method' ) ) );
	}
}
