<?php

if (!defined('WP_UNINSTALL_PLUGIN')) {
    die;
}

global $wpdb;

$table = $wpdb->prefix . 'tiptop_payment_settings';

// Delete table
$wpdb->query("DROP TABLE IF EXISTS {$table}");

// Delete options if stored
delete_option('tiptop_payment_widget_settings');
