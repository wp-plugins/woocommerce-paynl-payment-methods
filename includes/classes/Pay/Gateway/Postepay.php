<?php
class Pay_Gateway_Postepay extends Pay_Gateway_Abstract {

    public function getId() {
        return 'pay_gateway_postepay';
    }

    public function getName() {
        return 'Postepay';
    }

    public function getOptionId() {
        return 707;
    }

}
    