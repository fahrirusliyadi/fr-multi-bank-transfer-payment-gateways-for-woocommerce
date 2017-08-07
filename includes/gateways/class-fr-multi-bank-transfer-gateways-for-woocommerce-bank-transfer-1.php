<?php

/**
 * Bank Transfer 1 Payment Gateway.
 *
 * Provides a Bank Transfer Payment Gateway. 
 * 
 * @author Fahri Rusliyadi <fahri.rusliyadi@gmail.com>
 */
class Fr_Multi_Bank_Transfer_Gateways_For_Woocommerce_Bank_Transfer_1 extends Fr_Multi_Bank_Transfer_Gateways_For_Woocommerce_Bank_Transfer {
    /**
     * Constructor for the gateway.
     */
    public function __construct() {
        $this->id           = 'bank_transfer_1';
        $this->method_title = __( 'Bank Transfer 1', 'fr-multi-bank-transfer-gateways-for-woocommerce' );
        
        $this->_init();
    }
}
