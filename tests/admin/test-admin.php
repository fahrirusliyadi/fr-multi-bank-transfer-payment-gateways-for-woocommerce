<?php
/**
 * Class AdminTest
 *
 * @package Fr_Multi_Bank_Transfer_Payment_Gateways_For_Woocommerce
 * @author Fahri Rusliyadi <fahri.rusliyadi@gmail.com>
 */

/**
 * Admin test case.
 */
class AdminTest extends WP_UnitTestCase {
	/**
	 * Add plugin action link test.
	 */
	public function test_add_action_links() {
		$admin = new Fr_Multi_Bank_Transfer_Gateways_For_Woocommerce_Admin();

		$action_links = $admin->add_action_links( array( 'Sample action link' ) );
		$this->assertContains( 'admin.php?page=wc-settings&tab=checkout#fr_multi_bank_transfer_gateways_for_woocommerce_bank_number', $action_links[0] );
		$this->assertContains( 'Settings', $action_links[0] );
		$this->assertSame( 'Sample action link', $action_links[1] );
	}

	/**
	 * Add custom checkout settings test.
	 */
	public function test_add_custom_checkout_settings() {
		$admin = new Fr_Multi_Bank_Transfer_Gateways_For_Woocommerce_Admin();

		$settings = $admin->add_custom_checkout_settings(
			array(
				array( 'type' => 'sample_type' ),
				array( 'type' => 'payment_gateways' ),
				array( 'type' => 'sample_type' ),
			)
		);
		$this->assertSame( array( 'type' => 'sample_type' ), $settings[0] );
		$this->assertSame( array( 'type' => 'payment_gateways' ), $settings[1] );
		$this->assertSame(
			array(
				'title'             => __( 'Number of additional bank transfer gateways', 'fr-multi-bank-transfer-gateways-for-woocommerce' ),
				'desc'              => __( 'How many bank transfer gateways do you want to add?', 'fr-multi-bank-transfer-gateways-for-woocommerce' ),
				'id'                => 'fr_multi_bank_transfer_gateways_for_woocommerce_bank_number',
				'type'              => 'number',
				'desc_tip'          => true,
				'custom_attributes' => array(
					'min' => 0,
				),
			),
			$settings[2]
		);
		$this->assertSame( array( 'type' => 'sample_type' ), $settings[3] );
	}

	/**
	 * Add payment gateways with number option set to 0 test.
	 */
	public function test_add_gateway_classes_disabled() {
		$admin = new Fr_Multi_Bank_Transfer_Gateways_For_Woocommerce_Admin();

		$gateways = $admin->add_gateway_classes( array( 'WC_Gateway_BACS' ) );
		$this->assertCount( 1, $gateways );
		$this->assertSame( 'WC_Gateway_BACS', $gateways[0] );
	}

	/**
	 * Add payment gateways test.
	 */
	public function test_add_gateway_classes() {
		$admin = new Fr_Multi_Bank_Transfer_Gateways_For_Woocommerce_Admin();

		update_option( 'fr_multi_bank_transfer_gateways_for_woocommerce_bank_number', 20 );

		$gateways = $admin->add_gateway_classes( array( 'WC_Gateway_BACS' ) );
		$this->assertCount( 21, $gateways );

		foreach ( $gateways as $i => $gateway ) {
			if ( 0 === $i ) {
				$this->assertSame( 'WC_Gateway_BACS', $gateway );
			} else {
				$this->assertInstanceOf( 'Fr_Multi_Bank_Transfer_Gateways_For_Woocommerce_Bank_Transfer', $gateway );
				$this->assertSame( "bank_transfer_$i", $gateway->id );
				$this->assertSame( sprintf( 'Bank Transfer %d', $i ), $gateway->method_title );

				// Backward compatibility.
				// TODO: remove on version 2.0.0.
				if ( $i <= 10 ) {
					$this->assertTrue( class_exists( "Fr_Multi_Bank_Transfer_Gateways_For_Woocommerce_Bank_Transfer_$i" ) );
				}
			}
		}
	}
}
