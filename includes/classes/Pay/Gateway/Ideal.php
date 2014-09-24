<?php

class Pay_Gateway_Ideal extends Pay_Gateway_Abstract {
    public function getId() {
        return 'pay_gateway_ideal';
    }
    public function getName() {
        return 'iDEAL';
    }
    public function getOptionId() {
        return 10;
    }
}
