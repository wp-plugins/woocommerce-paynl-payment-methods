<?php
class Pay_Gateway_Directebankingat extends Pay_Gateway_Abstract {

    public function getId() {
        return 'pay_gateway_directebankingat';
    }

    public function getName() {
        return 'Sofortbanking Oostenrijk';
    }

    public function getOptionId() {
        return 568;
    }

}