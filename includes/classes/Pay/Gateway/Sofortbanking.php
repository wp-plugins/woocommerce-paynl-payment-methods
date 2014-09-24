<?php
class Pay_Gateway_Sofortbanking extends Pay_Gateway_Abstract {

    public function getId() {
        return 'pay_gateway_sofortbanking';
    }

    public function getName() {
        return 'Sofortbanking';
    }

    public function getOptionId() {
        return 559;
    }

}