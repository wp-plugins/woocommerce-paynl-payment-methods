<?php

class Pay_Helper_Transaction {

    public static function newTransaction($transactionId, $opionId, $amount, $orderId, $startData, $optionSubId = null) {
        global $wpdb;

        $table_name_transactions = $wpdb->prefix . "pay_transactions";

        $wpdb->insert(
                $table_name_transactions, array(
            'transaction_id' => $transactionId,
            'option_id' => $opionId,
            'option_sub_id' => $optionSubId,
            'amount' => $amount,
            'order_id' => $orderId,
            'status' => Pay_Gateways::STATUS_PENDING,
            'start_data' => $startData,
                ), array(
            '%s', '%d', '%d', '%d', '%d', '%s', '%s'
                )
        );
        $insertId = $wbdb->insert_id;
        return $insertId;
    }
    
    public static function getPaidTransactionIdForOrderId($orderId){
        global $wpdb;
        $table_name_transactions = $wpdb->prefix . "pay_transactions";
        $result = $wpdb->get_results(
                $wpdb->prepare("SELECT transaction_id FROM $table_name_transactions WHERE order_id = %s AND status = 'SUCCESS'", $orderId), ARRAY_A
        );
        if(!empty($result)){
            return $result[0]['transaction_id'];
        } else {
            return false;
        }
    }

    private static function updateStatus($transactionId, $status) {
        global $wpdb;
        $table_name_transactions = $wpdb->prefix . "pay_transactions";
        $wpdb->query(
                $wpdb->prepare("
                        UPDATE $table_name_transactions SET status = %s WHERE transaction_id = %s
                    ", $status, $transactionId)
        );
    }

    private static function getTransaction($transactionId) {
        global $wpdb;

        $table_name_transactions = $wpdb->prefix . "pay_transactions";
        $result = $wpdb->get_results(
                $wpdb->prepare("SELECT * FROM $table_name_transactions WHERE transaction_id = %s", $transactionId), ARRAY_A
        );

        return $result[0];
    }

    public static function processTransaction($transactionId, $status = null) {
        global $woocommerce;        
        

       
        // we gaan eerst kijken naar de status
        $transaction = self::getTransaction($transactionId);

        if (empty($transaction)) {
            throw new Pay_Exception('Transaction not found');
        }

        //order ophalen
        $order = new WC_Order($transaction['order_id']);
        if ($status == $transaction['status']) {
            if($status == Pay_Gateways::STATUS_CANCELED){
                //Pay_Helper_Cart::fillCartFromOrder($order);
                return $woocommerce->cart->get_cart_url();
            }
            //update hoeft niet te worden doorgevoerd
            return self::getOrderReturnUrl($order);
        }

        // huidige status ophalen bij pay
        $apiInfo = new Pay_Api_Info();
        $apiInfo->setApiToken(Pay_Gateway_Abstract::getApiToken());
        $apiInfo->setServiceId(Pay_Gateway_Abstract::getServiceId());
        $apiInfo->setTransactionId($transactionId);

        $result = $apiInfo->doRequest();

        $apiStatus = Pay_Gateways::getStatusFromStatusId($result['paymentDetails']['state']);

        self::updateStatus($transactionId, $apiStatus);
        
        if($order->status == 'complete' || $order->status == 'processing'){
            throw new Pay_Exception('Order is already completed');
        }
        
        // aan de hand van apistatus gaan we hem updaten
        switch ($apiStatus) {
            case Pay_Gateways::STATUS_SUCCESS:
                $woocommerce->cart->empty_cart();
                
                // Remove cart
                $order->payment_complete();
                
                $order->add_order_note(sprintf(__('Pay.nl: Payment complete. customerkey: %s', 'woocommerce-payment-paynl'),  $result['paymentDetails']['identifierPublic']) );
                
                $url = self::getOrderReturnUrl($order);
                break;
            case Pay_Gateways::STATUS_CANCELED:
                //$order->update_status('cancelled');
                $order->add_order_note(__('Pay.nl: Payment canceled', 'woocommerce-payment-paynl'));
                
                //Pay_Helper_Cart::fillCartFromOrder($order);
                
                $url = $woocommerce->cart->get_checkout_url();
                break;
            case Pay_Gateways::STATUS_PENDING:
                // Pending doen we niks mee
                $url = self::getOrderReturnUrl($order);
                break;
        }

        return $url;
        
    }
    
    public static function getOrderReturnUrl(WC_Order $order){
        // return url returnen
        $return_url = $order->get_checkout_order_received_url();
        if (is_ssl() || get_option('woocommerce_force_ssl_checkout') == 'yes') {
            $return_url = str_replace('http:', 'https:', $return_url);
        }

        return apply_filters('woocommerce_get_return_url', $return_url);
    }
}
