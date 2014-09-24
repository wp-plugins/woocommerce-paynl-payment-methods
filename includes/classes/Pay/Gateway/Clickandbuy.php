<?php
class Pay_Gateway_Clickandbuy extends Pay_Gateway_Abstract {

    public function getId() {
        return 'pay_gateway_clickandbuy';
    }

    public function getName() {
        return 'Clickandbuy';
    }

    public function getOptionId() {
        return 139;
    }

}
