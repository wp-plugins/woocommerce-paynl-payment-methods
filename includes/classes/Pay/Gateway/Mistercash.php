<?php
class Pay_Gateway_Mistercash extends Pay_Gateway_Abstract {

    public function getId() {
        return 'pay_gateway_mistercash';
    }

    public function getName() {
        return 'MisterCash / Bancontact';
    }

    public function getOptionId() {
        return 436;
    }

}
    