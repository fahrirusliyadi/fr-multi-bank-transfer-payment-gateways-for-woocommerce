<?php

/**
 * Bank Transfer Payment Gateway.
 *
 * Provides a Bank Transfer Payment Gateway. 
 *
 * @since 1.0.0
 * @author Fahri Rusliyadi <fahri.rusliyadi@gmail.com>
 */
class Fr_Multi_Bank_Transfer_Gateways_For_Woocommerce_Bank_Transfer extends WC_Gateway_BACS {
    /**
     * {@inheritdoc}
     * 
     * Copied and modified from {@see WC_Gateway_BACS::__construct()} version 3.5.2.
     * Modifications:
     *  - Change <code>id</code> property.
     *  - Change <code>icon</code> property.
     *  - Change <code>method_title</code> property.
     *  - Use current payment gateway option to save <code>account_details</code> value
     *  instead of <code>woocommerce_bacs_accounts</code>.
     *  - Remove <code>save_account_details</code> action handler.
     * 
     * @since 1.0.0
     */
    public function __construct($args = array()) {
        foreach($args as $property => $value) {
            $this->$property = $value;
        }

        $this->id                   = $this->id ? $this->id : 'bank_transfer';
        $this->icon                 = apply_filters("woocommerce_{$this->id}_icon", '');        
        $this->has_fields           = false;
        $this->method_title         = $this->method_title ? $this->method_title : __('Direct bank transfer', 'fr-multi-bank-transfer-gateways-for-woocommerce');
        $this->method_description   = __('Take payments in person via BACS. More commonly known as direct bank/wire transfer', 'fr-multi-bank-transfer-gateways-for-woocommerce');
     
        // Load the settings.
        $this->init_form_fields();   
        $this->init_settings();

        // Define user set variables.
        $this->title            = $this->get_option('title');
        $this->description      = $this->get_option('description');
        $this->instructions     = $this->get_option('instructions');
        // BACS account fields shown on the thanks page and in emails.
        $this->account_details  = $this->get_option('account_details');

        // Actions
        add_action('woocommerce_update_options_payment_gateways_' . $this->id, array($this, 'process_admin_options'));
        // We will not save the account_details as `woocommerce_bacs_accounts` option.
        // Instead we will save it in the current payment gateway option's array like 
        // the other fields. See `static::validate_account_details_field()`.
        //add_action('woocommerce_update_options_payment_gateways_' . $this->id, array($this, 'save_account_details'));
        add_action('woocommerce_thankyou_' . $this->id, array($this, 'thankyou_page'));

        // Customer Emails
        add_action('woocommerce_email_before_order_table', array($this, 'email_instructions'), 10, 3);
    }
    
    /**
     * {@inheritdoc}
     * 
     * Overridden to modify form fields.
     * 
     * @since 1.0.2
     */
    public function init_form_fields() {
        parent::init_form_fields();
        
        $this->form_fields['enabled']['label'] = __( 'Enable', 'fr-multi-bank-transfer-gateways-for-woocommerce' );
        $this->form_fields['title']['default'] = $this->method_title;
        // Similar to https://github.com/woocommerce/woocommerce/blob/5dcd8a1d5fa7b7b41463f956d23e69490a20297c/includes/gateways/bacs/class-wc-gateway-bacs.php#L54-L63.
        $this->form_fields['account_details']['default'] = array(
            array(
                'account_name'   => '',
                'account_number' => '',
                'sort_code'      => '',
                'bank_name'      => '',
                'iban'           => '',
                'bic'            => '',
            )
        );
    }
    
    /**
     * Validate <code>account_details</code> field.
     *
     * Make sure the data is escaped correctly, etc.
     * 
     * Copied and modified from {@see WC_Gateway_BACS::save_account_details()} version 3.5.2.
     * Modifications: 
     *  - Return the <code>account_details</code> field value instead of saving it as 
     *  <code>woocommerce_bacs_accounts</code> option. 
     *  <code>woocommerce_bacs_accounts</code> stores the <code>account_details</code>
     *  of <code>bacs</code> payment gateway.
     * 
     * @link https://github.com/woocommerce/woocommerce/blob/5dcd8a1d5fa7b7b41463f956d23e69490a20297c/includes/abstracts/abstract-wc-settings-api.php#L212
     *  When saving option, it will use {@see static::get_field_value()} to get the field value.
     * @link https://github.com/woocommerce/woocommerce/blob/5dcd8a1d5fa7b7b41463f956d23e69490a20297c/includes/abstracts/abstract-wc-settings-api.php#L139 
     *  Then, it will use {@see static::get_field_key()} to get the field key. But since 
     *  {@see static::generate_account_details_html()} uses <code>bacs_account_name</code>,
     *  <code>bacs_account_number</code>, ... as field names instead of <code>{$this->plugin_id}{$this->id}_account_details</code>,
     *  the returned value will be <code>null</code>.
     * @link https://github.com/woocommerce/woocommerce/blob/5dcd8a1d5fa7b7b41463f956d23e69490a20297c/includes/abstracts/abstract-wc-settings-api.php#L145-L148
     *  So we use {@see static::{"validate_{$key}_field"}()} to return the value.
     *
     * @since 1.0.2
     * @param  string $key Field key.
     * @param  string $value Posted Value.
     * @return string
     */
    public function validate_account_details_field($key, $value) {
        $accounts = array();

        // phpcs:disable WordPress.CSRF.NonceVerification.NoNonceVerification -- Nonce verification already handled in WC_Admin_Settings::save()
        if ( isset( $_POST['bacs_account_name'] ) && isset( $_POST['bacs_account_number'] ) && isset( $_POST['bacs_bank_name'] )
            && isset( $_POST['bacs_sort_code'] ) && isset( $_POST['bacs_iban'] ) && isset( $_POST['bacs_bic'] ) ) {

           $account_names   = wc_clean( wp_unslash( $_POST['bacs_account_name'] ) );
           $account_numbers = wc_clean( wp_unslash( $_POST['bacs_account_number'] ) );
           $bank_names      = wc_clean( wp_unslash( $_POST['bacs_bank_name'] ) );
           $sort_codes      = wc_clean( wp_unslash( $_POST['bacs_sort_code'] ) );
           $ibans           = wc_clean( wp_unslash( $_POST['bacs_iban'] ) );
           $bics            = wc_clean( wp_unslash( $_POST['bacs_bic'] ) );

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
        // phpcs:enable

        return $accounts;
    }

    /**
     * {@inheritdoc}
     *
     * Copied and modified from {@see WC_Gateway_BACS::email_instructions()} version 3.5.2.
     * Modifications: 
     *  - replace <code>'bacs'</code> with <code>$this->id</code> to allow our
     *  payment method pass the check.
     * 
     * @since 1.0.0
     */
    public function email_instructions( $order, $sent_to_admin, $plain_text = false ) {
        if ( ! $sent_to_admin && $this->id === $order->get_payment_method() && $order->has_status( 'on-hold' ) ) {
            if ( $this->instructions ) {
                echo wp_kses_post( wpautop( wptexturize( $this->instructions ) ) . PHP_EOL );
            }
            $this->bank_details( $order->get_id() );
        }
    }
    
    /**
     * {@inheritdoc}
     *
     * Exact copied from {@see WC_Gateway_BACS::bank_details()} version 3.5.2 because 
     * it is a private method so we cannot call it from {@see static::email_instructions()}.
     * 
     * @since 1.0.0
     */
    private function bank_details( $order_id = '' ) {
        if ( empty( $this->account_details ) ) {
            return;
        }

        // Get order and store in $order.
        $order = wc_get_order( $order_id );

        // Get the order country and country $locale.
        $country = $order->get_billing_country();
        $locale  = $this->get_country_locale();

        // Get sortcode label in the $locale array and use appropriate one.
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

                // BACS account fields shown on the thanks page and in emails.
                $account_fields = apply_filters(
                    'woocommerce_bacs_account_fields', array(
                        'bank_name'      => array(
                            'label' => __( 'Bank', 'woocommerce' ),
                            'value' => $bacs_account->bank_name,
                        ),
                        'account_number' => array(
                            'label' => __( 'Account number', 'woocommerce' ),
                            'value' => $bacs_account->account_number,
                        ),
                        'sort_code'      => array(
                            'label' => $sortcode,
                            'value' => $bacs_account->sort_code,
                        ),
                        'iban'           => array(
                            'label' => __( 'IBAN', 'woocommerce' ),
                            'value' => $bacs_account->iban,
                        ),
                        'bic'            => array(
                            'label' => __( 'BIC', 'woocommerce' ),
                            'value' => $bacs_account->bic,
                        ),
                    ), $order_id
                );

                foreach ( $account_fields as $field_key => $field ) {
                    if ( ! empty( $field['value'] ) ) {
                        $account_html .= '<li class="' . esc_attr( $field_key ) . '">' . wp_kses_post( $field['label'] ) . ': <strong>' . wp_kses_post( wptexturize( $field['value'] ) ) . '</strong></li>' . PHP_EOL;
                        $has_details   = true;
                    }
                }

                $account_html .= '</ul>';
            }

            if ( $has_details ) {
                echo '<section class="woocommerce-bacs-bank-details"><h2 class="wc-bacs-bank-details-heading">' . esc_html__( 'Our bank details', 'woocommerce' ) . '</h2>' . wp_kses_post( PHP_EOL . $account_html ) . '</section>';
            }
        }
    }
}
