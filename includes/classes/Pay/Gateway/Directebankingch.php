<?php
class Pay_Gateway_Directebankingch extends Pay_Gateway_Abstract {

    public function getId() {
        return 'pay_gateway_directebankingch';
    }

    public function getName() {
        return 'Sofortbanking Zwitserland';
    }

    public function getOptionId() {
        return 571;
    }

}
