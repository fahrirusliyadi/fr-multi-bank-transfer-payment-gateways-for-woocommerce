<?php
/**
 * The admin-specific functionality of the plugin.
 *
 * @package Fr_Multi_Bank_Transfer_Gateways_For_Woocommerce
 */

defined( 'ABSPATH' ) || die;

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @since 1.0.0
 * @author Fahri Rusliyadi <fahri.rusliyadi@gmail.com>
 */
class Fr_Multi_Bank_Transfer_Gateways_For_Woocommerce_Admin {
	/**
	 * Add plugin action links.
	 *
	 * Hooked on `plugin_action_links_{$plugin_file}` filter. The dynamic portion
	 * of the hook name, $plugin_file, refers to the path to the plugin file,
	 * relative to the plugins directory, which is
	 * `fr-multi-bank-transfer-payment-gateways-for-woocommerce/fr-multi-bank-transfer-gateways-for-woocommerce.php`.
	 *
	 * @since 1.0.2
	 * @access private
	 * @param array $action_links An array of plugin action links.
	 * @return array Modified plugin action links.
	 */
	public function add_action_links( $action_links ) {
		array_unshift(
			$action_links,
			sprintf(
				'<a href="%s">%s</a>',
				admin_url( 'admin.php?page=wc-settings&tab=checkout#fr_multi_bank_transfer_gateways_for_woocommerce_bank_number' ),
				__(
					'Settings',
					'fr-multi-bank-transfer-gateways-for-woocommerce'
				)
			)
		);

		return $action_links;
	}

	/**
	 * Add custom checkout settings.
	 *
	 * Hooked on `woocommerce_get_settings_{$this->id}` filter. The dynamic portion
	 * `$this->id` refers to the setting page id, which is `checkout`.
	 *
	 * @since 1.0.0
	 * @access private
	 * @param array $settings Settings array.
	 */
	public function add_custom_checkout_settings( $settings ) {
		$new_settings = array();

		foreach ( $settings as $value ) {
			$new_settings[] = $value;

			if ( array_key_exists( 'type', $value ) && 'payment_gateways' === $value['type'] ) {
				$new_settings[] = array(
					'title'             => __( 'Number of additional bank transfer gateways', 'fr-multi-bank-transfer-gateways-for-woocommerce' ),
					'desc'              => __( 'How many bank transfer gateways do you want to add?', 'fr-multi-bank-transfer-gateways-for-woocommerce' ),
					'id'                => 'fr_multi_bank_transfer_gateways_for_woocommerce_bank_number',
					'type'              => 'number',
					'desc_tip'          => true,
					'custom_attributes' => array(
						'min' => 0,
					),
				);
			}
		}

		return $new_settings;
	}

	/**
	 * Register payment gateways.
	 *
	 * Hooked on `woocommerce_payment_gateways` filter.
	 *
	 * @since 1.0.0
	 * @access private
	 * @param array $gateways Payment gateways.
	 * @return array Modified payment gateways.
	 */
	public function add_gateway_classes( $gateways ) {
		$bank_number = get_option( 'fr_multi_bank_transfer_gateways_for_woocommerce_bank_number', 0 );
		if ( $bank_number < 1 ) {
			return $gateways;
		}

		require_once FR_MULTI_BANK_TRANSFER_GATEWAYS_FOR_WOOCOMMERCE_DIR . 'includes/gateways/class-fr-multi-bank-transfer-gateways-for-woocommerce-bank-transfer.php';

		for ( $i = 1; $i <= $bank_number; $i++ ) {
			$args = array(
				'id'           => "bank_transfer_$i",
				/* translators: %d: bank transfer number */
				'method_title' => sprintf( __( 'Bank Transfer %d', 'fr-multi-bank-transfer-gateways-for-woocommerce' ), $i ),
			);

			$gateways[] = new Fr_Multi_Bank_Transfer_Gateways_For_Woocommerce_Bank_Transfer( $args );
		}

		// Backward compatibility. The classes may be used by other plugins or themes.
		// TODO: remove on version 2.0.0.
		for ( $i = 1; $i <= $bank_number && $i <= 10; $i++ ) {
			require_once FR_MULTI_BANK_TRANSFER_GATEWAYS_FOR_WOOCOMMERCE_DIR . "includes/gateways/class-fr-multi-bank-transfer-gateways-for-woocommerce-bank-transfer-$i.php";
		}

		return $gateways;
	}
}
