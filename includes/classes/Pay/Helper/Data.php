<?php

class Pay_Helper_Data {

    public static function getIp() {

        //Just get the headers if we can or else use the SERVER global
        if (function_exists('apache_request_headers')) {

            $headers = apache_request_headers();
        } else {

            $headers = $_SERVER;
        }

        //Get the forwarded IP if it exists
        if (array_key_exists('X-Forwarded-For', $headers) && filter_var($headers['X-Forwarded-For'], FILTER_VALIDATE_IP, FILTER_FLAG_IPV4)) {

            $the_ip = $headers['X-Forwarded-For'];
        } elseif (array_key_exists('HTTP_X_FORWARDED_FOR', $headers) && filter_var($headers['HTTP_X_FORWARDED_FOR'], FILTER_VALIDATE_IP, FILTER_FLAG_IPV4)
        ) {

            $the_ip = $headers['HTTP_X_FORWARDED_FOR'];
        } else {

            $the_ip = filter_var($_SERVER['REMOTE_ADDR'], FILTER_VALIDATE_IP, FILTER_FLAG_IPV4);
        }

        return $the_ip;
    }

    public static function loadPaymentMethods() {
        global $wpdb;

        $apiToken = Pay_Gateway_Abstract::getApiToken();
        $serviceId = Pay_Gateway_Abstract::getServiceId();

        $api = new Pay_Api_Getservice();
        $api->setApiToken($apiToken);
        $api->setServiceId($serviceId);


        $data = $api->doRequest();


        $imageBasePath = $data['service']['basePath'];

        $paymentOptions = $data['paymentOptions'];

        $table_name_options = $wpdb->prefix . "pay_options";
        $table_name_option_subs = $wpdb->prefix . "pay_option_subs";

        //eerst flushen
        $wpdb->query('TRUNCATE TABLE ' . $table_name_option_subs);
        $wpdb->query('TRUNCATE TABLE ' . $table_name_options);

        foreach ($paymentOptions as $paymentOption) {
            $image = $imageBasePath . $paymentOption['path'] . $paymentOption['img'];
            $wpdb->insert(
                    $table_name_options, array(
                'id' => $paymentOption['id'],
                'name' => $paymentOption['visibleName'],
                'image' => $image,
                'update_date' => current_time('mysql'),
                    ), array('%d', '%s', '%s', '%s')
            );
            if (!empty($paymentOption['paymentOptionSubList']) && $paymentOption['id'] == 10) {
                foreach ($paymentOption['paymentOptionSubList'] as $paymentOptionSub) {
                    $image = $imageBasePath . $paymentOptionSub['path'] . $paymentOptionSub['img'];
                    $wpdb->insert(
                            $table_name_option_subs, array(
                        'option_id' => $paymentOption['id'],
                        'option_sub_id' => $paymentOptionSub['id'],
                        'name' => $paymentOptionSub['visibleName'],
                        'image' => $image,
                        'active' => $paymentOptionSub['state'] == 1,
                            ), array('%d', '%d', '%s', '%s', '%d')
                    );
                }
            }
        }
    }

    public static function getOptions() {
        global $wpdb;

        $table_name_options = $wpdb->prefix . "pay_options";
        $query = "SELECT id, name, image, update_date FROM $table_name_options";

        $options = $wpdb->get_results($query, ARRAY_A);

        return $options;
    }

    public static function getOptionSubs($optionId) {
        global $wpdb;

        $table_name_option_subs = $wpdb->prefix . "pay_option_subs";
        $query = $wpdb->prepare("SELECT option_id, option_sub_id, name, image "
                . "FROM $table_name_option_subs "
                . "WHERE active = 1 AND option_id = %d", $optionId
        );

        $optionSubs = $wpdb->get_results($query, ARRAY_A);

        return $optionSubs;
    }

    public static function isOptionAvailable($optionId) {
        global $wpdb;

        $table_name_options = $wpdb->prefix . "pay_options";
        $query = $wpdb->prepare("SELECT id, name, image, update_date FROM $table_name_options WHERE id = %d", $optionId);

        $result = $wpdb->get_results($query, ARRAY_A);

        if (empty($result))
            return false;
        else
            return true;
    }

}
