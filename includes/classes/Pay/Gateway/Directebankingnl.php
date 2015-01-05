<?php
class Pay_Gateway_Directebankingnl extends Pay_Gateway_Abstract {

    public function getId() {
        return 'pay_gateway_directebankingnl';
    }

    public function getName() {
        return 'Sofortbanking Nederland';
    }

    public function getOptionId() {
        return 556;
    }

}