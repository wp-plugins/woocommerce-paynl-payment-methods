<?php

class Pay_Gateways {

    const STATUS_PENDING = 'PENDING';
    const STATUS_CANCELED = 'CANCELED';
    const STATUS_SUCCESS = 'SUCCESS';

    public static function _getGateways($arrDefault) {
      
        $paymentOptions = array(
            'Pay_Gateway_Afterpay',
			'Pay_Gateway_Cartebleue',
            'Pay_Gateway_Clickandbuy',
            'Pay_Gateway_Sofortbanking',
            'Pay_Gateway_Giropay',
            'Pay_Gateway_Ideal',
            'Pay_Gateway_Incasso',
            'Pay_Gateway_Maestro',
            'Pay_Gateway_Minitixsms',
            'Pay_Gateway_Mistercash',
            'Pay_Gateway_Mybank',
            'Pay_Gateway_Overboeking',
            'Pay_Gateway_Paypal',
            'Pay_Gateway_Paysafecard',
			'Pay_Gateway_Postepay',
            'Pay_Gateway_Visamastercard',
        );
        
        $paymentOptions = array_merge($paymentOptions, $arrDefault);
     
        return $paymentOptions;
    }

    public static function _addGlobalSettings($settings) {
        
        $loadedPaymentMethods = "";
        try{            
            Pay_Helper_Data::loadPaymentMethods();
            
            $arrOptions = Pay_Helper_Data::getOptions();
            $loadedPaymentMethods .= '<br /><br />'.__('The following payment methods can be enabled', 'woocommerce-payment-paynl');
            
            $loadedPaymentMethods .= '<ul style="width:900px;">';
            foreach($arrOptions as $option){
                $loadedPaymentMethods .= '<li style="float: left; width:300px;"><img src="'.$option['image'].'" alt="'.$option['name'].'" title="'.$option['name'].'" /> '.$option['name'].'</li>';
                
            }
            $loadedPaymentMethods .= '</ul>';
            $loadedPaymentMethods .= '<div class="clear"></div>';
            
        } catch (Pay_Exception $e) {
            $loadedPaymentMethods = '<span style="color:red; font-weight:bold;">Error: '.$e->getMessage().'</span>';
        }
        
        
        $updatedSettings = array();

        $addedSettings = array();
        $addedSettings[] = array(
            'title' => __('Pay.nl settings', 'woocommerce-payment-paynl'),
            'type' => 'title',
            'desc' => '<p>'.$loadedPaymentMethods.'</p><p>'.__('The following options are required to use the Pay.nl Payment Gateway and are used by all Pay.nl Payment Methods', 'woocommerce-payment-paynl').'</p>',
            'id' => 'paynl_global_settings',
        );
        $addedSettings[] = array(
            'name' => __('Api token', 'woocommerce-payment-paynl'),
            'type' => 'text',
            'desc' => __('The api token used to communicate with the Pay.nl API, you can find your token <a href="https://admin.pay.nl/my_merchant" target="api_token">here</a>', 'woocommerce-payment-paynl'),
            'id' => 'paynl_apitoken',
        );
        $addedSettings[] = array(
            'name' => __('Service id', 'woocommerce-payment-paynl'),
            'type' => 'text',
            'desc' => __('The serviceid to identify your website, you can find your serviceid <a href="https://admin.pay.nl/programs/programs" target="serviceid">here</a>', 'woocommerce-payment-paynl'),
            'id' => 'paynl_serviceid',
            'desc_tip' => __('The serviceid should be in the following format: SL-xxxx-xxxx', 'woocommerce-payment-paynl'),
        );
        $addedSettings[] = array(
            'name' => __('Send order data', 'woocommerce-payment-paynl'),
            'type' => 'checkbox',
            'desc' => __('Check this box if you want to send the order data to pay.nl, this is required if you want to use afterpay', 'woocommerce-payment-paynl'),
            'id' => 'paynl_send_order_data',
            'default' => 'yes',            
        );
        $addedSettings[] = array(
            'name' => __('Payment screen language', 'woocommerce-payment-paynl'),
            'type' => 'select',
            'options' => array(
                'nl' => __('Dutch','woocommerce-payment-paynl'),
                'en' => __('English','woocommerce-payment-paynl'),
                'de' => __('German','woocommerce-payment-paynl'),
                'es' => __('Spanish','woocommerce-payment-paynl'),
                'it' => __('Italian','woocommerce-payment-paynl'),
                'fr' => __('French','woocommerce-payment-paynl'),
              
            ),
            'desc' => __('This is the language in which the paymetn screen will be shown', 'woocommerce-payment-paynl'),
            'id' => 'paynl_language',
            'default' => 'nl',            
        );
        $addedSettings[] = array(
            'name' => __('Bankselection', 'woocommerce-payment-paynl'),
            'type' => 'select',
            'options' => array(
                'none' => __('No bankselection','woocommerce-payment-paynl'),
                'select' => __('Selectbox','woocommerce-payment-paynl'),
                'radio' => __('Radiobuttons','woocommerce-payment-paynl'),
            ),
            'desc' => __('Pick the type of bankselection', 'woocommerce-payment-paynl'),
            'id' => 'paynl_bankselection',
            'default' => 'none',            
        );
        
        $addedSettings[] = array(
            'type' => 'sectionend',
            'id' => 'paynl_global_settings',
        );
        foreach ($settings as $setting) {
            if (isset($setting['id']) && $setting['id'] == 'payment_gateways_options' && $setting['type'] != 'sectionend') {
                $updatedSettings = array_merge($updatedSettings, $addedSettings);
            }
            $updatedSettings[] = $setting;
        }
        
        
        return $updatedSettings;
    }

    /**
     * This function registers the Pay Payment Gateways
     */
    public static function register() {           
        add_filter('woocommerce_payment_gateways', array(__CLASS__, '_getGateways'));            
    }

    /**
     * This function adds the Pay Global Settings to the woocommerce payment method settings
     */
    public static function addSettings() {
        add_filter('woocommerce_payment_gateways_settings', array(__CLASS__, '_addGlobalSettings'));
    }

    /**
     * Register the API's to catch the return and exchange
     */
    public static function registerApi() {
        add_action('woocommerce_api_wc_pay_gateway_return', array(__CLASS__, '_onReturn'));
        add_action('woocommerce_api_wc_pay_gateway_exchange', array(__CLASS__, '_onExchange'));
    }

    public static function _onReturn() {
        $status = self::getStatusFromStatusId($_GET['orderStatusId']);

        $url = Pay_Helper_Transaction::processTransaction($_GET['orderId'], $status);
        
        wp_redirect($url);
    }

    public static function _onExchange() {
        try{
            if($_GET['action'] == 'new_ppt'){
                $status = self::STATUS_SUCCESS;
            } elseif($_GET['action'] == 'pending'){
                $status = self::STATUS_PENDING;
                die('TRUE|Ignoring pending');
                return ;
            } elseif($_GET['action'] == 'cancel'){
                $status = self::STATUS_CANCELED;
            }

            $url = Pay_Helper_Transaction::processTransaction($_GET['order_id'], $status);
            $message = 'Status updated to '. $status;
        } catch(Pay_Exception $e){
            $message = 'Error: '.$e->getMessage();
        }
        die('TRUE|'.$message);
    }

    public static function getStatusFromStatusId($statusId) {
        switch ($statusId) {
            case '100':
                $status = self::STATUS_SUCCESS;
                break;
            case '-90':
                $status = self::STATUS_CANCELED;
                break;
            default :
                $status = self::STATUS_PENDING;
        }
        return $status;
    }

}
