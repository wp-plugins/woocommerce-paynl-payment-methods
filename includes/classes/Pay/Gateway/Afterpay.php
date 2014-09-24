<?php

class Pay_Gateway_Afterpay extends Pay_Gateway_Abstract {

    public function getId() {
        return 'pay_gateway_afterpay';
    }

    public function getName() {
        return 'Afterpay';
    }

    public function getOptionId() {
        return 739;
    }

}