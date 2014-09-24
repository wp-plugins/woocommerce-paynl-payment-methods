<?php
class Pay_Gateway_Directebankinggb extends Pay_Gateway_Abstract {

    public function getId() {
        return 'pay_gateway_directebankinggb';
    }

    public function getName() {
        return 'Sofortbanking Groot-Brittanië';
    }

    public function getOptionId() {
        return 565;
    }

}