<?php
class Pay_Gateway_Incasso extends Pay_Gateway_Abstract {

    public function getId() {
        return 'pay_gateway_incasso';
    }

    public function getName() {
        return 'Incasso';
    }

    public function getOptionId() {
        return 137;
    }

}