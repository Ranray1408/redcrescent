<?php
/**
 * Plugin Name: TipTop Payment Widget
 * Description: Payment widget integration for TipTopPay with admin options.
 * Version: 1.1.0
 * Author: DioZX
 */

if (!defined('ABSPATH')) exit;

require_once plugin_dir_path(__FILE__) . 'includes/class-tiptop-payment-widget.php';
require_once plugin_dir_path(__FILE__) . 'includes/class-tiptop-payment-mailer.php';
require_once plugin_dir_path(__FILE__) . 'includes/class-tiptop-payment-salesforce.php';

register_activation_hook(__FILE__, ['TipTopPaymentWidget', 'activate_static']);

function tiptop_payment_widget_run() {
    new TipTopPaymentMailer();
    new TipTopPaymentSalesforce();
    $plugin = new TipTopPaymentWidget();
    $plugin->init();
}
add_action('plugins_loaded', 'tiptop_payment_widget_run');
