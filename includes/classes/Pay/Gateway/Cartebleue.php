<?php
class Pay_Gateway_Cartebleue extends Pay_Gateway_Abstract {

    public function getId() {
        return 'pay_gateway_cartebleue';
    }

    public function getName() {
        return 'Carte Bleue';
    }

    public function getOptionId() {
        return 710;
    }

}
    