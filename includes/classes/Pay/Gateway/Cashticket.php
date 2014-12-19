<?php
class Pay_Gateway_Cashticket extends Pay_Gateway_Abstract {

    public function getId() {
        return 'pay_gateway_cashticket';
    }

    public function getName() {
        return 'Cashticket';
    }

    public function getOptionId() {
        return 550;
    }

}

