<?php

/**
 * Bank Transfer 3 Payment Gateway.
 *
 * Provides a Bank Transfer Payment Gateway. 
 * 
 * @author Fahri Rusliyadi <fahri.rusliyadi@gmail.com>
 */
class Fr_Multi_Bank_Transfer_Gateways_For_Woocommerce_Bank_Transfer_3 extends Fr_Multi_Bank_Transfer_Gateways_For_Woocommerce_Bank_Transfer {
    /**
     * Constructor for the gateway.
     */
    public function __construct() {
        $this->id           = 'bank_transfer_3';
        $this->method_title = __( 'Bank Transfer 3', 'fr-multi-bank-transfer-gateways-for-woocommerce' );
        
        $this->_init();
    }
}
