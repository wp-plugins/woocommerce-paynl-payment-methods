<?php
class Pay_Gateway_Visamastercard extends Pay_Gateway_Abstract {

    public static function getId() {
        return 'pay_gateway_visamastercard';
    }

    public static function getName() {
        return 'Visa/Mastercard';
    }

    public static function getOptionId() {
        return 706;
    }

}
    