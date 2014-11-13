<?php
class Pay_Gateway_Directebankingde extends Pay_Gateway_Abstract {

    public function getId() {
        return 'pay_gateway_directebankingde';
    }

    public function getName() {
        return 'Sofortbanking Duitsland';
    }

    public function getOptionId() {
        return 562;
    }

}