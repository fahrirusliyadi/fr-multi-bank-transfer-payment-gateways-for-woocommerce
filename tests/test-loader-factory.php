<?php
/**
 * Class LoaderFactoryTest
 *
 * @package Fr_Multi_Bank_Transfer_Payment_Gateways_For_Woocommerce
 * @author Fahri Rusliyadi <fahri.rusliyadi@gmail.com>
 */

/**
 * Loader factory test case.
 */
class LoaderFactoryTest extends WP_UnitTestCase {
	/**
	 * A single example test.
	 */
	public function test_create() {
		$service   = Mockery::mock();
		$container = new Fr_Multi_Bank_Transfer_Gateways_For_Woocommerce_Container();
		$config    = array(
			'hooks' => array(
				array( 'action', 'sample_action', get_class( $service ), 'sample_method' ),
			),
		);

		$container->set( 'config', $config );

		$factory = new Fr_Multi_Bank_Transfer_Gateways_For_Woocommerce_Loader_Factory();
		$loader  = $factory->create( $container );

		$this->assertInstanceOf( 'Fr_Multi_Bank_Transfer_Gateways_For_Woocommerce_Loader', $loader );

		$this->assertFalse( has_action( 'sample_action', array( $container->get( get_class( $service ) ), 'sample_method' ) ) );
		$loader->run();
		$this->assertSame( 10, has_action( 'sample_action', array( $container->get( get_class( $service ) ), 'sample_method' ) ) );
	}
}
