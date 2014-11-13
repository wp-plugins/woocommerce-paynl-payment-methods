<?php
class Pay_Gateway_Paypal extends Pay_Gateway_Abstract {

    public function getId() {
        return 'pay_gateway_paypal';
    }

    public function getName() {
        return 'Paypal';
    }

    public function getOptionId() {
        return 138;
    }

}