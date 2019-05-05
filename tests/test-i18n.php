<?php
/**
 * Class I18nTest
 *
 * @package Fr_Multi_Bank_Transfer_Payment_Gateways_For_Woocommerce
 * @author Fahri Rusliyadi <fahri.rusliyadi@gmail.com>
 */

/**
 * I18n test case.
 */
class I18nTest extends WP_UnitTestCase {
	/**
	 * Load the plugin text domain.
	 */
	public function test_load() {
		global $l10n;

		$i18n = new Fr_Multi_Bank_Transfer_Gateways_For_Woocommerce_i18n();

		add_filter( 'plugin_locale', array( $this, 'switch_locale' ) );
		add_filter( 'load_textdomain_mofile', array( $this, 'correct_mofile' ) );

		$this->assertArrayNotHasKey( 'fr-multi-bank-transfer-gateways-for-woocommerce', (array) $l10n );
		$i18n->load_plugin_textdomain();
		$this->assertArrayHasKey( 'fr-multi-bank-transfer-gateways-for-woocommerce', $l10n );
	}

	/**
	 * Switch locale to id_ID.
	 *
	 * @param string $locale Current locale.
	 * @return string New locale.
	 */
	public function switch_locale( $locale ) {
		return 'id_ID';
	}

	/**
	 * Correct MO file path because our plugin is not in wp-content/plugins/ directory.
	 *
	 * @link https://github.com/WordPress/WordPress/blob/5.1.1/wp-includes/l10n.php#L795
	 *  WordPress is prepending our path with WP_PLUGIN_DIR.
	 *
	 * @param string $mofile Path to the MO file.
	 * @return string Correct path to the MO file.
	 */
	public function correct_mofile( $mofile ) {
		return str_replace( WP_PLUGIN_DIR, '', $mofile );
	}
}
