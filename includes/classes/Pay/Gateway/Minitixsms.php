<?php
class Pay_Gateway_Minitixsms extends Pay_Gateway_Abstract {

    public function getId() {
        return 'pay_gateway_minitixsms';
    }

    public function getName() {
        return 'Minitix sms';
    }

    public function getOptionId() {
        return 808;
    }

}
    