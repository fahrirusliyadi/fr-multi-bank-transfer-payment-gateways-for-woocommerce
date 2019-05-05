<?php
/**
 * Class ContainerTest
 *
 * @package Fr_Multi_Bank_Transfer_Payment_Gateways_For_Woocommerce
 * @author Fahri Rusliyadi <fahri.rusliyadi@gmail.com>
 */

/**
 * Dependency injection container test case.
 */
class ContainerTest extends WP_UnitTestCase {
	/**
	 * Set raw entry test.
	 */
	public function test_set() {
		$contaner = new Fr_Multi_Bank_Transfer_Gateways_For_Woocommerce_Container();

		$contaner->set( 'sample_key', 'Sample value' );
		$this->assertSame( 'Sample value', $contaner->get( 'sample_key' ) );
	}

	/**
	 * Set factory test.
	 */
	public function test_set_factory() {
		$contaner = new Fr_Multi_Bank_Transfer_Gateways_For_Woocommerce_Container();
		$factory  = Mockery::mock( 'overload:ContainerTestSampleFactory', 'Fr_Multi_Bank_Transfer_Gateways_For_Woocommerce_Container_Entry_Factory_Interface' );

		$contaner->set_factory( 'sample_factory', 'ContainerTestSampleFactory' );
		$factory->shouldReceive( 'create' )->with( $contaner )->once()->andReturn( 'Sample factory value' );
		$this->assertSame( 'Sample factory value', $contaner->get( 'sample_factory' ) );
	}

	/**
	 * Invalid factory test.
	 */
	public function test_invalid_factory() {
		$contaner      = new Fr_Multi_Bank_Transfer_Gateways_For_Woocommerce_Container();
		$factory_class = get_class( Mockery::mock() );

		$contaner->set_factory( 'sample_factory', $factory_class );
		$this->expectException( 'LogicException' );
		$this->expectExceptionMessage( "$factory_class must implements Fr_Multi_Bank_Transfer_Gateways_For_Woocommerce_Container_Entry_Factory_Interface" );
		$contaner->get( 'sample_factory' );
	}

	/**
	 * Simple service test.
	 *
	 * Services that does not need constructor arguments.
	 */
	public function test_simple_service() {
		$contaner      = new Fr_Multi_Bank_Transfer_Gateways_For_Woocommerce_Container();
		$service_class = get_class( Mockery::mock() );

		$this->assertInstanceOf( $service_class, $contaner->get( $service_class ) );
	}

	/**
	 * Service not found test.
	 */
	public function test_not_found() {
		$contaner = new Fr_Multi_Bank_Transfer_Gateways_For_Woocommerce_Container();

		$this->expectException( 'Exception' );
		$this->expectExceptionMessage( 'sample_service entry cannot be found in the container' );
		$contaner->get( 'sample_service' );
	}
}
