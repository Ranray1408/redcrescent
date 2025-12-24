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
        $admin_email = get_option('admin_email');

        // Build email subject
        $subject = "New TipTopPay Donation";

        // Build email body
        $message = "New payment was completed:\n\n";
        $message .= "Name: " . ($data['firstName'] ?? '') . " " . ($data['lastName'] ?? '') . "\n";
        $message .= "Email: " . ($data['email'] ?? '') . "\n";
        $message .= "Amount: " . ($data['amount'] ?? '') . "\n";
        $message .= "Currency: " . ($data['currency'] ?? '') . "\n";
        $message .= "Description: " . ($data['description'] ?? '') . "\n";
        $message .= "Birth: " . ($data['birth'] ?? '') . "\n";
        $message .= "External ID: " . ($data['externalId'] ?? '') . "\n";

        // Send email
        if(wp_mail($admin_email, $subject, $message)){
            wp_send_json_success("Email sent");
        } else {
            wp_send_json_error("Failed to send email");
        }
    }
}
