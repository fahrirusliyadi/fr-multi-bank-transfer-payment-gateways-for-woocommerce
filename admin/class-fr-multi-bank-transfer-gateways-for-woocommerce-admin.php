<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://example.com
 * @since      1.0.0
 *
 * @package    Fr_Multi_Bank_Transfer_Gateways_For_Woocommerce
 * @subpackage Fr_Multi_Bank_Transfer_Gateways_For_Woocommerce/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Fr_Multi_Bank_Transfer_Gateways_For_Woocommerce
 * @subpackage Fr_Multi_Bank_Transfer_Gateways_For_Woocommerce/admin
 * @author     Fahri Rusliyadi <fahri.rusliyadi@gmail.com>
 */
class Fr_Multi_Bank_Transfer_Gateways_For_Woocommerce_Admin {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of this plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

	}

	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Fr_Multi_Bank_Transfer_Gateways_For_Woocommerce_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Fr_Multi_Bank_Transfer_Gateways_For_Woocommerce_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/fr-multi-bank-transfer-gateways-for-woocommerce-admin.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Fr_Multi_Bank_Transfer_Gateways_For_Woocommerce_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Fr_Multi_Bank_Transfer_Gateways_For_Woocommerce_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/fr-multi-bank-transfer-gateways-for-woocommerce-admin.js', array( 'jquery' ), $this->version, false );

	}
        
        /**
         * 
         * Hooked on `'woocommerce_get_settings_{$this->id}` filter. The dynamic portion
         * `$this->id` refers to the setting page id, which is `checkout`.
         * 
         * @param type $settings
         */
        public function add_custom_checkout_settings($settings) {
            $new_settings = array();
            
            foreach ($settings as $value) {
                $new_settings[] = $value;
                
                if (array_key_exists('type', $value) && $value['type'] == 'payment_gateways') {
                    $new_settings[] = array(
                        'title'             => __( 'Number of additional bank transfer gateways', 'fr-multi-bank-transfer-gateways-for-woocommerce' ),
                        'desc'              => __( 'How many bank transfer gateways do you want to add? Maximum 10. Refresh after saving this option.', 'fr-multi-bank-transfer-gateways-for-woocommerce' ),
                        'id'                => 'fr_multi_bank_transfer_gateways_for_woocommerce_bank_number',
                        'type'              => 'number',
                        'desc_tip'          => true,
                        'custom_attributes' => array(
                                                'min' => 0,
                                                'max' => 10,
                                            )
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
         * @param array $gateways
         * @return array
         */
        public function add_gateway_classes($gateways) {
            $bank_number = get_option('fr_multi_bank_transfer_gateways_for_woocommerce_bank_number', 0);
            
            if ($bank_number < 1) {
                return $gateways;
            }
            
            if ($bank_number > 10) {
                $bank_number = 10;
            }
            
            // Load the abstract class.
            require_once plugin_dir_path(dirname(__FILE__)) . 'includes/gateways/class-fr-multi-bank-transfer-gateways-for-woocommerce-bank-transfer.php';
            
            for ($i = 1; $i <= $bank_number; $i++) {
                require_once plugin_dir_path(dirname(__FILE__)) . 'includes/gateways/class-fr-multi-bank-transfer-gateways-for-woocommerce-bank-transfer-' . $i . '.php';
                
                $gateways[] = 'Fr_Multi_Bank_Transfer_Gateways_For_Woocommerce_Bank_Transfer_' . $i;
            }
            
            return $gateways;
        }

}
