<?php
/**
 * Class BankTransferTest
 *
 * @package Fr_Multi_Bank_Transfer_Payment_Gateways_For_Woocommerce
 * @author Fahri Rusliyadi <fahri.rusliyadi@gmail.com>
 */

/**
 * Bank transfer payment gateway test case.
 */
class BankTransferTest extends WP_UnitTestCase {
	/**
	 * Sample account details value.
	 *
	 * @var array
	 */
	private $sample_account_details = array(
		array(
			'account_name'   => 'Account name',
			'account_number' => '4485480221084675',
			'sort_code'      => '13',
			'bank_name'      => 'Bank name',
			'iban'           => 'IT31A8497112740YZ575DJ28BP4',
			'bic'            => 'RZTIAT22263',
		),
	);

	/**
	 * {@inheritdoc}
	 *
	 * @return void
	 */
	public function setUp() {
		require_once FR_MULTI_BANK_TRANSFER_GATEWAYS_FOR_WOOCOMMERCE_DIR . 'includes/gateways/class-fr-multi-bank-transfer-gateways-for-woocommerce-bank-transfer.php';

		parent::setUp();
	}

	/**
	 * Construct test.
	 */
	public function test_constructor() {
		$bank_transfer = new Fr_Multi_Bank_Transfer_Gateways_For_Woocommerce_Bank_Transfer();

		$default_account_details = array(
			array_map( '__return_empty_string', $this->sample_account_details[0] ),
		);

		// Properties.
		$this->assertSame( 'bank_transfer', $bank_transfer->id );
		$this->assertSame( '', $bank_transfer->icon );
		$this->assertFalse( $bank_transfer->has_fields );
		$this->assertSame( 'Direct bank transfer', $bank_transfer->method_title );
		$this->assertSame( 'Take payments in person via BACS. More commonly known as direct bank/wire transfer.', $bank_transfer->method_description );

		// init_form_fields.
		$this->assertSame( __( 'Enable', 'fr-multi-bank-transfer-gateways-for-woocommerce' ), $bank_transfer->form_fields['enabled']['label'] );
		$this->assertSame( $bank_transfer->method_title, $bank_transfer->form_fields['title']['default'] );
		$this->assertSame( $default_account_details, $bank_transfer->form_fields['account_details']['default'] );

		// init_settings.
		$this->assertArrayHasKey( 'enabled', $bank_transfer->settings );
		$this->assertArrayHasKey( 'title', $bank_transfer->settings );
		$this->assertArrayHasKey( 'description', $bank_transfer->settings );
		$this->assertArrayHasKey( 'instructions', $bank_transfer->settings );
		$this->assertArrayHasKey( 'account_details', $bank_transfer->settings );
		$this->assertSame( $bank_transfer->form_fields['title']['default'], $bank_transfer->settings['title'] );
		$this->assertSame( $default_account_details, $bank_transfer->settings['account_details'] );

		// User set variables.
		$this->assertSame( $bank_transfer->title, $bank_transfer->settings['title'] );
		$this->assertSame( $bank_transfer->description, $bank_transfer->settings['description'] );
		$this->assertSame( $bank_transfer->instructions, $bank_transfer->settings['instructions'] );
		$this->assertSame( $bank_transfer->account_details, $bank_transfer->settings['account_details'] );

		// Actions.
		$this->assertSame( 10, has_action( 'woocommerce_update_options_payment_gateways_' . $bank_transfer->id, array( $bank_transfer, 'process_admin_options' ) ) );
		$this->assertSame( 10, has_action( 'woocommerce_thankyou_' . $bank_transfer->id, array( $bank_transfer, 'thankyou_page' ) ) );
		$this->assertSame( 10, has_action( 'woocommerce_email_before_order_table', array( $bank_transfer, 'email_instructions' ) ) );
	}

	/**
	 * Construct with argument test.
	 */
	public function test_constructor_with_arguments() {
		$bank_transfer = new Fr_Multi_Bank_Transfer_Gateways_For_Woocommerce_Bank_Transfer(
			array(
				'id'           => 'bank_transfer_sample',
				'method_title' => 'Bank Transfer Sample',
			)
		);

		$this->assertSame( 'bank_transfer_sample', $bank_transfer->id );
		$this->assertSame( 'Bank Transfer Sample', $bank_transfer->method_title );
	}

	/**
	 * Validate account details test.
	 */
	public function test_validate_account_details_field() {
		$bank_transfer = new Fr_Multi_Bank_Transfer_Gateways_For_Woocommerce_Bank_Transfer();

		foreach ( $this->sample_account_details[0] as $key => $detail ) {
			$_POST[ "bacs_$key" ][0] = 'account_name' === $key ? 'Account <em>name</em>' : $detail;
		}

		$account_details = $bank_transfer->validate_account_details_field( 'account_details', null );
		foreach ( $this->sample_account_details[0] as $key => $detail ) {
			$this->assertSame( $detail, $account_details[0][ $key ] );
		}
	}

	/**
	 * Empty email instructions test.
	 */
	public function test_email_instructions_empty() {
		$bank_transfer = new Fr_Multi_Bank_Transfer_Gateways_For_Woocommerce_Bank_Transfer();
		$order         = $this->create_order();

		ob_start();
		$bank_transfer->email_instructions( $order, false );
		$output = ob_get_clean();
		$this->assertSame( '', $output );
	}

	/**
	 * Email instructions with empty account details test.
	 */
	public function test_email_instructions_with_empty_account_details() {
		$bank_transfer = new Fr_Multi_Bank_Transfer_Gateways_For_Woocommerce_Bank_Transfer();
		$order         = $this->create_order();

		$bank_transfer->account_details = array();

		ob_start();
		$bank_transfer->email_instructions( $order, false );
		$output = ob_get_clean();
		$this->assertSame( '', $output );
	}

	/**
	 * Email instructions test.
	 */
	public function test_email_instructions_with_instructions() {
		$bank_transfer = new Fr_Multi_Bank_Transfer_Gateways_For_Woocommerce_Bank_Transfer();
		$order         = $this->create_order();

		$bank_transfer->instructions = 'Sample instruction';
		ob_start();
		$bank_transfer->email_instructions( $order, false );
		$output = ob_get_clean();
		$this->assertContains( 'Sample instruction', $output );
	}

	/**
	 * Email instructions with bank details test.
	 */
	public function test_email_instructions_with_bank_details() {
		$bank_transfer = new Fr_Multi_Bank_Transfer_Gateways_For_Woocommerce_Bank_Transfer();
		$order         = $this->create_order();

		$bank_transfer->account_details = $this->sample_account_details;

		ob_start();
		$bank_transfer->email_instructions( $order, false );
		$output = ob_get_clean();

		foreach ( $this->sample_account_details[0] as $detail ) {
			$this->assertContains( $detail, $output );
		}
	}

	/**
	 * Deprecated bank transfer classes test.
	 *
	 * @todo remove on version 2.0.0
	 */
	public function test_deprecated_classes() {
		// Test deprecated error is triggered.
		for ( $i = 1;  $i <= 10; $i++ ) {
			require_once FR_MULTI_BANK_TRANSFER_GATEWAYS_FOR_WOOCOMMERCE_DIR . "includes/gateways/class-fr-multi-bank-transfer-gateways-for-woocommerce-bank-transfer-$i.php";

			try {
				$class = "Fr_Multi_Bank_Transfer_Gateways_For_Woocommerce_Bank_Transfer_$i";
				new $class();
			} catch ( PHPUnit_Framework_Error_Deprecated $e ) {
				$this->assertSame( sprintf( '%1$s is <strong>deprecated</strong> since version %2$s with no alternative available.', $class, '1.1.0' ), $e->getMessage() );
			}
		}

		// Test properties with deprecated error suppressed.
		set_error_handler( '__return_null', E_USER_DEPRECATED ); // phpcs:ignore WordPress.PHP.DevelopmentFunctions.error_log_set_error_handler
		for ( $i = 1;  $i <= 10; $i++ ) {
			$class         = "Fr_Multi_Bank_Transfer_Gateways_For_Woocommerce_Bank_Transfer_$i";
			$bank_transfer = new $class();

			$this->assertSame( "bank_transfer_$i", $bank_transfer->id );
			$this->assertSame( sprintf( 'Bank Transfer %d', $i ), $bank_transfer->method_title );
		}
		restore_error_handler();
	}

	/**
	 * Create an order object.
	 */
	private function create_order() {
		$order = new WC_Order();

		$order->set_payment_method( 'bank_transfer' );
		$order->set_status( 'on-hold' );
		$order->save();

		return $order;
	}
}
