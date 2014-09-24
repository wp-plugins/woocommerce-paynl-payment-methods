<?php
class Pay_Gateway_Mybank extends Pay_Gateway_Abstract {

    public function getId() {
        return 'pay_gateway_mybank';
    }

    public function getName() {
        return 'Mybank';
    }

    public function getOptionId() {
        return 1588;
    }

}
    