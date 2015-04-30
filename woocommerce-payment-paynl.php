<?php

/**
 * Plugin Name: Woocommerce Pay.nl Payment Methods
 * Plugin URI: https://wordpress.org/plugins/woocommerce-paynl-payment-methods/
 * Description: Pay.nl payment methods for woocommerce
 * Version: 2.3.3
 * Author: andypay
 * Author URI: http://www.pay.nl
 * Requires at least: 3.0.1
 * Tested up to: 4.3
 *
 * Text Domain: woocommerce-payment-paynl
 * Domain Path: /i18n/languages/
 */

//Autoloader laden en registreren
require_once dirname(__FILE__) . '/includes/classes/Autoload.php';

//plugin functies inladen
require_once( ABSPATH . '/wp-admin/includes/plugin.php' );

//textdomain inladen
load_plugin_textdomain('woocommerce-payment-paynl', false, 'woocommerce-paynl-payment-methods/i18n/languages');

function error_woocommerce_not_active(){
	echo '<div class="error"><p>' . __('The Pay.nl payment methods plugin requires woocommerce to be active', 'woocommerce-payment-paynl') . '</p></div>';
}
function error_curl_not_installed(){
	echo '<div class="error"><p>' . __('Curl is not installed.<br />In order to use the Pay.nl payment methods, you must install install CURL.<br />Ask your system administrator to install php_curl', 'woocommerce-payment-paynl') . '</p></div>';
}

// Curl is niet geinstalleerd. foutmelding weergeven
if(!function_exists('curl_version')){
    add_action('admin_notices', 'error_curl_not_installed');
}



//Autoloader registreren
Pay_Autoload::register();

//Installer registreren
register_activation_hook(__FILE__, array('Pay_Setup', 'install'));

if (is_plugin_active('woocommerce/woocommerce.php') || is_plugin_active_for_network('woocommerce/woocommerce.php')) {
    
    //Gateways van pay aan woocommerce koppelen
    Pay_Gateways::register();
    
    //Globale settings van pay aan woocommerce koppelen
    Pay_Gateways::addSettings();

    //Return en exchange functies koppelen aan de woocommerce API
    Pay_Gateways::registerApi();
} else {
    // Woocommerce is niet actief. foutmelding weergeven
    add_action('admin_notices', error_woocommerce_not_active);
}

