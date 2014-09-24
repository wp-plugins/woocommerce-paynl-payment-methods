<?php
class Pay_Gateway_Paysafecard extends Pay_Gateway_Abstract {

    public function getId() {
        return 'pay_gateway_paysafecard';
    }

    public function getName() {
        return 'Paysafecard';
    }

    public function getOptionId() {
        return 553;
    }

}