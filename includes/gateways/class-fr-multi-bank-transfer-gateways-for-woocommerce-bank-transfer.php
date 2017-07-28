<?php

/**
 * Bank Transfer Payment Gateway abstract.
 *
 * Provides a Bank Transfer Payment Gateway. 
 *
 * @author Fahri Rusliyadi <fahri.rusliyadi@gmail.com>
 */
abstract class Fr_Multi_Bank_Transfer_Gateways_For_Woocommerce_Activator_Bank_Transfer extends WC_Gateway_BACS {
    /**
     * Constructor for the gateway.
     */
    public function __construct() {
        $this->id                 = 'bank_transfer';
        $this->method_title       = __( 'Bank Transfer', 'fr-multi-bank-transfer-gateways-for-woocommerce' );
        
        $this->_init();
    }
    
    /**
     * Initialize.
     * 
     * Copied and modified from WC_Gateway_BACS::__construct() version 2.1.0 
     */
    protected function _init() {
        $this->icon               = apply_filters( 'woocommerce_' . $this->id . '_icon', '' );        
        $this->has_fields         = false;
        $this->method_description = __( 'Allows payments by BACS, more commonly known as direct bank/wire transfer.', 'fr-multi-bank-transfer-gateways-for-woocommerce' );

        $this->init_form_fields();
        
        // Modify form fields.
        $this->form_fields['enabled']['label']              = __( 'Enable', 'fr-multi-bank-transfer-gateways-for-woocommerce' );
        $this->form_fields['title']['default']              = $this->method_title;
        $this->form_fields['account_details']['default']    = array(
                                                                array(
                                                                    'account_name'   => '',
                                                                    'account_number' => '',
                                                                    'sort_code'      => '',
                                                                    'bank_name'      => '',
                                                                    'iban'           => '',
                                                                    'bic'            => '',
                                                                )
                                                            );
        
        // Load the settings.
        $this->init_settings();

        // Define user set variables
        $this->title            = $this->get_option( 'title' );
        $this->description      = $this->get_option( 'description' );
        $this->instructions     = $this->get_option( 'instructions' );

        // BACS account fields shown on the thanks page and in emails
        $this->account_details  = $this->get_option( 'account_details' );

        // Actions
        add_action( 'woocommerce_update_options_payment_gateways_' . $this->id, array( $this, 'process_admin_options' ) );
        add_filter( 'woocommerce_settings_api_sanitized_fields_' . $this->id, array( $this, 'append_account_details_options' ) );
        add_action( 'woocommerce_thankyou_' . $this->id, array( $this, 'thankyou_page' ) );

        // Customer Emails
        add_action( 'woocommerce_email_before_order_table', array( $this, 'email_instructions' ), 10, 3 );
    }
    
    /**
     * Append the account details to the settings array just before it saved.
     * 
     * Copied and modified from WC_Gateway_BACS::save_account_details() version 2.1.0 
     * 
     * Hooked on `'woocommerce_settings_api_sanitized_fields_{$this->id}` filter.
     */
    public function append_account_details_options($settings) {
            $accounts = array();

            if ( isset( $_POST['bacs_account_name'] ) ) {

                    $account_names   = array_map( 'wc_clean', $_POST['bacs_account_name'] );
                    $account_numbers = array_map( 'wc_clean', $_POST['bacs_account_number'] );
                    $bank_names      = array_map( 'wc_clean', $_POST['bacs_bank_name'] );
                    $sort_codes      = array_map( 'wc_clean', $_POST['bacs_sort_code'] );
                    $ibans           = array_map( 'wc_clean', $_POST['bacs_iban'] );
                    $bics            = array_map( 'wc_clean', $_POST['bacs_bic'] );

                    foreach ( $account_names as $i => $name ) {
                            if ( ! isset( $account_names[ $i ] ) ) {
                                    continue;
                            }

                            $accounts[] = array(
                                    'account_name'   => $account_names[ $i ],
                                    'account_number' => $account_numbers[ $i ],
                                    'bank_name'      => $bank_names[ $i ],
                                    'sort_code'      => $sort_codes[ $i ],
                                    'iban'           => $ibans[ $i ],
                                    'bic'            => $bics[ $i ],
                            );
                    }
            }

            $settings['account_details'] = $accounts;
            
            return $settings;
    }

    /**
     * Add content to the WC emails.
     *
     * Copied and modified from WC_Gateway_BACS::email_instructions() version 2.1.0 
     * 
     * Hooked on `woocommerce_email_before_order_table` action.
     * 
     * @param WC_Order $order
     * @param bool $sent_to_admin
     * @param bool $plain_text
     */
    public function email_instructions( $order, $sent_to_admin, $plain_text = false ) {
        if ( ! $sent_to_admin && $this->id === $order->get_payment_method() && $order->has_status( 'on-hold' ) ) {
            if ( $this->instructions ) {
                echo wpautop( wptexturize( $this->instructions ) ) . PHP_EOL;
            }
            
            $this->bank_details( $order->get_id() );
        }
    }

    /**
     * Get bank details and place into a list format.
     *
     * Exact copied from WC_Gateway_BACS::email_instructions() version 2.1.0
     * 
     * @param int $order_id
     */
    protected function bank_details( $order_id = '' ) {

            if ( empty( $this->account_details ) ) {
                    return;
            }

            // Get order and store in $order
            $order 		= wc_get_order( $order_id );

            // Get the order country and country $locale
            $country 	= $order->get_billing_country();
            $locale		= $this->get_country_locale();

            // Get sortcode label in the $locale array and use appropriate one
            $sortcode = isset( $locale[ $country ]['sortcode']['label'] ) ? $locale[ $country ]['sortcode']['label'] : __( 'Sort code', 'woocommerce' );

            $bacs_accounts = apply_filters( 'woocommerce_bacs_accounts', $this->account_details );

            if ( ! empty( $bacs_accounts ) ) {
                    $account_html = '';
                    $has_details  = false;

                    foreach ( $bacs_accounts as $bacs_account ) {
                            $bacs_account = (object) $bacs_account;

                            if ( $bacs_account->account_name ) {
                                    $account_html .= '<h3 class="wc-bacs-bank-details-account-name">' . wp_kses_post( wp_unslash( $bacs_account->account_name ) ) . ':</h3>' . PHP_EOL;
                            }

                            $account_html .= '<ul class="wc-bacs-bank-details order_details bacs_details">' . PHP_EOL;

                            // BACS account fields shown on the thanks page and in emails
                            $account_fields = apply_filters( 'woocommerce_bacs_account_fields', array(
                                    'bank_name' => array(
                                            'label' => __( 'Bank', 'woocommerce' ),
                                            'value' => $bacs_account->bank_name,
                                    ),
                                    'account_number' => array(
                                            'label' => __( 'Account number', 'woocommerce' ),
                                            'value' => $bacs_account->account_number,
                                    ),
                                    'sort_code'     => array(
                                            'label' => $sortcode,
                                            'value' => $bacs_account->sort_code,
                                    ),
                                    'iban'          => array(
                                            'label' => __( 'IBAN', 'woocommerce' ),
                                            'value' => $bacs_account->iban,
                                    ),
                                    'bic'           => array(
                                            'label' => __( 'BIC', 'woocommerce' ),
                                            'value' => $bacs_account->bic,
                                    ),
                            ), $order_id );

                            foreach ( $account_fields as $field_key => $field ) {
                                    if ( ! empty( $field['value'] ) ) {
                                            $account_html .= '<li class="' . esc_attr( $field_key ) . '">' . wp_kses_post( $field['label'] ) . ': <strong>' . wp_kses_post( wptexturize( $field['value'] ) ) . '</strong></li>' . PHP_EOL;
                                            $has_details   = true;
                                    }
                            }

                            $account_html .= '</ul>';
                    }

                    if ( $has_details ) {
                            echo '<section class="woocommerce-bacs-bank-details"><h2 class="wc-bacs-bank-details-heading">' . __( 'Our bank details', 'woocommerce' ) . '</h2>' . PHP_EOL . $account_html . '</section>';
                    }
            }

    }
}
