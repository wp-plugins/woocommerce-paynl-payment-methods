<?php

/**
 * Plugin Name: Woocommerce Pay.nl Payment Methods
 * Plugin URI: https://wordpress.org/plugins/woocommerce-paynl-payment-methods/
 * Description: Pay.nl payment methods for woocommerce
 * Version: 2.2.6
 * Author: andypay
 * Author URI: http://www.pay.nl
 * Requires at least: 3.0.1
 * Tested up to: 4.0
 *
 * Text Domain: woocommerce-payment-paynl
 * Domain Path: /i18n/languages/
 */
/**
 * Check if WooCommerce is active
 * */
//Autoloader laden en registreren
require_once dirname(__FILE__) . '/includes/classes/Autoload.php';

//plugin functies inladen
require_once( ABSPATH . '/wp-admin/includes/plugin.php' );

//textdomain inladen
load_plugin_textdomain('woocommerce-payment-paynl', false, '/woocommerce-payment-paynl/i18n/languages/');

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
    add_action('admin_notices', function() {
        echo '<div class="error"><p>' . __('The Pay.nl payment methods plugin requires woocommerce to be active', 'woocommerce-payment-paynl') . '</p></div>';
    });
}

