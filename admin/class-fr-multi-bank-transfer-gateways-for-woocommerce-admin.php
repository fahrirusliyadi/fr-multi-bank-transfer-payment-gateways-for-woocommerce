<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @since      1.0.0
 * @package    Fr_Multi_Bank_Transfer_Gateways_For_Woocommerce
 * @subpackage Fr_Multi_Bank_Transfer_Gateways_For_Woocommerce/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @since      1.0.0
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
     * @since 1.0.0
     * @param string $plugin_name The name of this plugin.
     * @param string $version The version of this plugin.
     */
    public function __construct( $plugin_name, $version ) {

            $this->plugin_name = $plugin_name;
            $this->version = $version;

    }
    
    /**
     * Add plugin action links.
     * 
     * Hooked on `plugin_action_links_{$plugin_file}` filter. The dynamic portion 
     * of the hook name, $plugin_file, refers to the path to the plugin file, 
     * relative to the plugins directory, which is `fr-multi-bank-transfer-payment-gateways-for-woocommerce/fr-multi-bank-transfer-gateways-for-woocommerce.php`.
     * 
     * @since 1.0.2
     * @param array $action_links
     * @return array
     */
    public function add_action_links($action_links) {
        array_unshift($action_links, sprintf(
            '<a href="%s">%s</a>', 
            admin_url('admin.php?page=wc-settings&tab=checkout#fr_multi_bank_transfer_gateways_for_woocommerce_bank_number'), 
            __('Settings', 'fr-multi-bank-transfer-gateways-for-woocommerce'
        )));
        
        return $action_links;
    }

    /**
     * Add custom checkout settings.
     * 
     * @since 1.0.0
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
     * @since 1.0.0
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
