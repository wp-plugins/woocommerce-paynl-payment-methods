<?php
class Pay_Gateway_Sofortbanking extends Pay_Gateway_Abstract {

    public static function getId() {
        return 'pay_gateway_sofortbanking';
    }

    public static function getName() {
        return 'Sofortbanking';
    }

    public static function getOptionId() {
        return 559;
    }

}