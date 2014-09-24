<?php
class Pay_Gateway_Maestro extends Pay_Gateway_Abstract {

    public function getId() {
        return 'pay_gateway_maestro';
    }

    public function getName() {
        return 'Maestro';
    }

    public function getOptionId() {
        return 712;
    }

}
    