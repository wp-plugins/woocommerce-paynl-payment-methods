<?php
class Pay_Gateway_Overboeking extends Pay_Gateway_Abstract {

    public static function getId() {
        return 'pay_gateway_overboeking';
    }

    public static function getName() {
        return 'Overboeking';
    }

    public static function getOptionId() {
        return 136;
    }

}