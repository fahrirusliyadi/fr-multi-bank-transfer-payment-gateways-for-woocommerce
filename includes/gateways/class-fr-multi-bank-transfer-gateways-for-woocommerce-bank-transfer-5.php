<?php

/**
 * Bank Transfer 5 Payment Gateway.
 *
 * Provides a Bank Transfer Payment Gateway. 
 * 
 * @author Fahri Rusliyadi <fahri.rusliyadi@gmail.com>
 */
class Fr_Multi_Bank_Transfer_Gateways_For_Woocommerce_Activator_Bank_Transfer_5 extends Fr_Multi_Bank_Transfer_Gateways_For_Woocommerce_Activator_Bank_Transfer {
    /**
     * Constructor for the gateway.
     */
    public function __construct() {
        $this->id           = 'bank_transfer_5';
        $this->method_title = __( 'Bank Transfer 5', 'fr-multi-bank-transfer-gateways-for-woocommerce' );
        
        $this->_init();
    }
}
