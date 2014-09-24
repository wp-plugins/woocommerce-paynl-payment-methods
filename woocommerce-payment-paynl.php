<?php
/**
 * Plugin Name: Woocommerce Pay.nl Payment Methods
 * Plugin URI: 
 * Description: Pay.nl payment methods for woocommerce
 * Version: 2.2.3
 * Author: andypay
 * Author URI: http://www.pay.nl
 * Requires at least: 3.0.1
 * Tested up to: 34.0
 *
 * Text Domain: woocommerce-payment-paynl
 * Domain Path: /i18n/languages/
 */

/**
 * Check if WooCommerce is active
 **/
$active_plugins = (array) get_option( 'active_plugins', array() );
if (is_multisite()){
    $active_plugins = array_merge($active_plugins, get_site_option( 'active_sitewide_plugins', array() ));
}

if (in_array( 'woocommerce/woocommerce.php', $active_plugins ) || array_key_exists( 'woocommerce/woocommerce.php', $active_plugins )) {
    //Autoloader laden en registreren
    require_once dirname(__FILE__).'/includes/classes/Autoload.php';
    Pay_Autoload::register();
    
    //multilang initialisation
    $plugin_dir = basename(dirname(__FILE__));
    load_plugin_textdomain( 'woocommerce-payment-paynl', false, '/woocommerce-payment-paynl/i18n/languages/' );
    
    //Installer registreren
    register_activation_hook( __FILE__, array('Pay_Setup', 'install') );
    
    //Gateways van pay aan woocommerce koppelen
    Pay_Gateways::register();
    //Globale settings van pay aan woocommerce koppelen
    Pay_Gateways::addSettings();
    //Return en exchange functies koppelen aan de woocommerce API
    Pay_Gateways::registerApi();
} 
