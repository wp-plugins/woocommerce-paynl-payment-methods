<?php
class Pay_Gateway_Giropay extends Pay_Gateway_Abstract {

    public function getId() {
        return 'pay_gateway_giropay';
    }

    public function getName() {
        return 'Giropay';
    }

    public function getOptionId() {
        return 694;
    }

}