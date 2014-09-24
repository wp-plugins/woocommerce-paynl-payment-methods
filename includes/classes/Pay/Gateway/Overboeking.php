<?php
class Pay_Gateway_Overboeking extends Pay_Gateway_Abstract {

    public function getId() {
        return 'pay_gateway_overboeking';
    }

    public function getName() {
        return 'Overboeking';
    }

    public function getOptionId() {
        return 136;
    }

}