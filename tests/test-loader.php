<?php
/**
 * Class LoaderTest
 *
 * @package Fr_Multi_Bank_Transfer_Payment_Gateways_For_Woocommerce
 * @author Fahri Rusliyadi <fahri.rusliyadi@gmail.com>
 */

/**
 * Loader test case.
 */
class LoaderTest extends WP_UnitTestCase {
	/**
	 * Add action test.
	 */
	public function test_add_action() {
		$loader  = new Fr_Multi_Bank_Transfer_Gateways_For_Woocommerce_Loader();
		$service = Mockery::mock();

		$loader->add_action( 'sample_action', $service, 'sample_method' );
		$this->assertFalse( has_action( 'sample_action', array( $service, 'sample_method' ) ) );
		$loader->run();
		$this->assertSame( 10, has_action( 'sample_action', array( $service, 'sample_method' ) ) );
	}

	/**
	 * Add filter test.
	 */
	public function test_add_filter() {
		$loader  = new Fr_Multi_Bank_Transfer_Gateways_For_Woocommerce_Loader();
		$service = Mockery::mock();

		$loader->add_filter( 'sample_action', $service, 'sample_method' );
		$this->assertFalse( has_filter( 'sample_action', array( $service, 'sample_method' ) ) );
		$loader->run();
		$this->assertSame( 10, has_filter( 'sample_action', array( $service, 'sample_method' ) ) );
	}
}
