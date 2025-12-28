<?php

if (!defined('ABSPATH')) exit;

class TipTopPaymentMailer {

    public function __construct() {
        // Register ajax handlers
        add_action('wp_ajax_tiptop_payment_email', [$this, 'send_payment_email']);
        add_action('wp_ajax_nopriv_tiptop_payment_email', [$this, 'send_payment_email']);
    }

    /**
     * Send email with payment details
     */
    public function send_payment_email() {

        // Validate
        if (!isset($_POST['paymentData'])) {
            wp_send_json_error("Missing payment data");
        }

        // Decode json data
        $data = json_decode(stripslashes($_POST['paymentData']), true);

        if (!$data) {
            wp_send_json_error("Invalid JSON");
        }

        // Admin email
        $admin_email = 'diozx5@gmail.com'; //get_option('admin_email');

        // Build email subject
        $subject = "Новое поступление средств";

        $is_subscription = !empty($data['recurrent']) ? 'Подписка' : 'Разовый платеж';

        // Build email body
        $message = "Тип платежа: $is_subscription\n\n";
        $message .= "От: " . ($data['firstName'] ?? '') . " " . ($data['lastName'] ?? '') . "\n";
        $message .= "Email: " . ($data['email'] ?? '') . "\n";
        $message .= "Количество: " . ($data['amount'] ?? '') . "\n";
        $message .= "Валюта: " . ($data['currency'] ?? '') . "\n";
        $message .= "Описание: " . ($data['description'] ?? '') . "\n";
        $message .= "Дата рождения: " . ($data['birth'] ?? '') . "\n";
        //$message .= "External ID: " . ($data['externalId'] ?? '') . "\n";

        $headers = ['Content-Type: text/plain; charset=UTF-8'];

        // Send email
        if (wp_mail($admin_email, $subject, $message, $headers)) {
            wp_send_json_success("Email sent");
        } else {
            wp_send_json_error("Failed to send email");
        }
    }
}
