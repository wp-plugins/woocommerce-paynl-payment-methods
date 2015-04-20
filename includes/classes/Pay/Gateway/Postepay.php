<?php
class Pay_Gateway_Postepay extends Pay_Gateway_Abstract {

    public static function getId() {
        return 'pay_gateway_postepay';
    }

    public static function getName() {
        return 'Postepay';
    }

    public static function getOptionId() {
        return 707;
    }

}
    