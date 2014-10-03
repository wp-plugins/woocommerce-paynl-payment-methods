<?php
class Pay_Gateway_Visamastercard extends Pay_Gateway_Abstract {

    public function getId() {
        return 'pay_gateway_visamastercard';
    }

    public function getName() {
        return 'Visa/Mastercard';
    }

    public function getOptionId() {
        return 706;
    }

}
    