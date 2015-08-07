<?php

class Pay_Setup {

    const db_version = 1.0;

    public static function _install() {
        self::checkRequirements();
        global $wpdb;

        require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );

        $pay_db_version = get_option("pay_db_version");

        if (empty($pay_db_version)) {
            $table_name_transactions = $wpdb->prefix . "pay_transactions";
            $table_name_options = $wpdb->prefix . "pay_options";
            $table_name_option_subs = $wpdb->prefix . "pay_option_subs";

            $sqlTransactions = "CREATE TABLE `$table_name_transactions` (
            `id` int(11) NOT NULL AUTO_INCREMENT,
            `transaction_id` varchar(50) NOT NULL,
            `option_id` int(11) NOT NULL,
            `option_sub_id` int(11) DEFAULT NULL,
            `amount` int(11) NOT NULL,
            `order_id` int(11) NOT NULL,
            `status` varchar(10) NOT NULL DEFAULT 'PENDING',
            `created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
            `last_update` datetime DEFAULT NULL,
            `start_data` text NOT NULL,
            PRIMARY KEY (`id`),
            UNIQUE KEY `{$table_name_transactions}_transaction_id` (`transaction_id`)
          );";
            $sqlOptions = "CREATE TABLE `$table_name_options` (
              `id` int(10) unsigned NOT NULL COMMENT 'Payment option Id',
              `name` varchar(255) NOT NULL COMMENT 'Payment option name',
              `image` varchar(255) NOT NULL COMMENT 'The url to the icon image',
              `update_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'The datetime this payment option was refreshed',
              PRIMARY KEY (`id`)
            );";
            $sqlOptionSub = "CREATE TABLE `$table_name_option_subs` (
            `option_id` int(10) unsigned NOT NULL COMMENT 'Payment option Id',
            `option_sub_id` int(10) unsigned NOT NULL COMMENT 'Payment option sub Id',  
            `name` varchar(255) NOT NULL COMMENT 'The name of the option sub',
            `image` varchar(255) NOT NULL COMMENT 'The url to the icon image',
            `active` tinyint(1) NOT NULL COMMENT 'OptionSub  active or not',
            PRIMARY KEY (`option_id`, option_sub_id)
          );";
            $sql = $sqlTransactions . "\n" . $sqlOptions . "\n" . $sqlOptionSub;

            dbDelta($sql);

            add_option("pay_db_version", self::db_version);
        }
    }

    public static function install() {
        if (is_multisite() && is_plugin_active_for_network('woocommerce-paynl-payment-methods/woocommerce-payment-paynl.php')) {
            global $wpdb, $blog_id;
            $dbquery = 'SELECT blog_id FROM ' . $wpdb->blogs;
            $ids = $wpdb->get_col($dbquery);
            foreach ($ids as $id) {
                switch_to_blog($id);
                self::_install();
            }
            switch_to_blog($blog_id);
        } else {
            self::_install();
        }
    }

    public function newBlog($blog_id, $user_id, $domain, $path, $site_id, $meta) {
        global $wpdb;

        $old_blog = $wpdb->blogid;
        switch_to_blog($blog_id);
        self::_install();
        switch_to_blog($old_blog);
    }

    private static function checkRequirements() {
        if (!is_plugin_active('woocommerce/woocommerce.php') && !is_plugin_active_for_network('woocommerce/woocommerce.php')) {
            $error = __('Cannot activate Woocommerce Paynl Payment Methods because woocommerce is not found.<br />Please install and activate woocommerce first', 'woocommerce-payment-paynl');
            $title = __('Woocommerce not found', 'woocommerce-payment-paynl');

            wp_die($error, $title, array('back_link' => true));
        }
    }

}
