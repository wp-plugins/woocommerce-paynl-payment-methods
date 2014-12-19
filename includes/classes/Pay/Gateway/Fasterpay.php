<?php
class Pay_Gateway_Fasterpay extends Pay_Gateway_Abstract {

    public function getId() {
        return 'pay_gateway_fasterpay';
    }

    public function getName() {
        return 'Fasterpay';
    }

    public function getOptionId() {
        return 610;
    }

}