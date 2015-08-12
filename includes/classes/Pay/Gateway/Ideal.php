<?php

class Pay_Gateway_Ideal extends Pay_Gateway_Abstract {
    public static function getId() {
        return 'pay_gateway_ideal';
    }
    public static function getName() {
        return 'iDEAL';
    }
    public static function getOptionId() {
        return 10;
    }
}
