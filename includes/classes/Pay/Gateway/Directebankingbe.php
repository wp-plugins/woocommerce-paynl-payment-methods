<?php
class Pay_Gateway_Directebankingbe extends Pay_Gateway_Abstract {

    public function getId() {
        return 'pay_gateway_directebankingbe';
    }

    public function getName() {
        return 'Sofortbanking België';
    }

    public function getOptionId() {
        return 559;
    }

}