<?php

if (!defined('ABSPATH')) exit;

class TipTopPaymentWidget {

	private string $table_name;

	public function __construct() {
		global $wpdb;
		$this->table_name = $wpdb->prefix . 'tiptop_payment_settings';
	}

	public static function activate_static() {
		$self = new self();
		$self->on_activate();
	}

	// ---------------------------------------------------------
	// INIT
	// ---------------------------------------------------------
	public function init(): void {
		add_action('admin_menu', [$this, 'register_admin_page']);
		add_action('admin_post_tiptop_save_settings', [$this, 'save_settings']);
		add_action('wp_enqueue_scripts', [$this, 'enqueue_scripts'], 5);
	}

	// ---------------------------------------------------------
	// CREATE TABLE ON ACTIVATE
	// ---------------------------------------------------------
	public function on_activate(): void {
		global $wpdb;

		$charset_collate = $wpdb->get_charset_collate();

		$sql = "CREATE TABLE {$this->table_name} (
            id INT NOT NULL AUTO_INCREMENT,
            currency VARCHAR(10) NOT NULL,
            success_redirect VARCHAR(255) NOT NULL,
            terminal_id VARCHAR(255) NOT NULL,
            description VARCHAR(255) DEFAULT '',
            PRIMARY KEY  (id)
        ) $charset_collate;";

		require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
		dbDelta($sql);

		$exists = $wpdb->get_var("SELECT COUNT(*) FROM {$this->table_name}");

		if (!$exists) {
			$wpdb->insert($this->table_name, [
				'currency' => 'KZT',
				'terminal_id' => 'pk_XXXXXXXXXXXX',
				'description' => 'Donation',
				'successRedirectUrl' => ''
			]);
		}
	}

	// ---------------------------------------------------------
	// ADMIN PAGE
	// ---------------------------------------------------------
	public function register_admin_page(): void {
		add_menu_page(
			'TipTop Payment',
			'TipTop Payment',
			'manage_options',
			'tiptop-payment',
			[$this, 'render_admin_page'],
			'dashicons-money',
			56
		);
	}

	// ---------------------------------------------------------
	// RENDER ADMIN PAGE
	// ---------------------------------------------------------
	public function render_admin_page(): void {
		global $wpdb;
		$settings = $wpdb->get_row("SELECT * FROM {$this->table_name} LIMIT 1") ?? '';

		if (!$settings) {
			$settings = (object)[
				'currency' => '',
				'success_redirect' => '',
				'terminal_id' => '',
				'description' => ''
			];
		}

?>
		<div class="wrap">
			<h1>TipTop Payment Settings</h1>

			<form method="POST" action="<?php echo admin_url('admin-post.php'); ?>">
				<input type="hidden" name="action" value="tiptop_save_settings">
				<?php wp_nonce_field('tiptop_settings_nonce'); ?>

				<table class="form-table">

					<tr>
						<th>Currency</th>
						<td><input type="text" name="currency"
								value="<?php echo esc_attr($settings->currency); ?>"
								class="regular-text"></td>
					</tr>

					<tr>
						<th>Terminal ID</th>
						<td><input type="text" name="terminal_id"
								value="<?php echo esc_attr($settings->terminal_id); ?>"
								class="regular-text"></td>
					</tr>

					<tr>
						<th>Description</th>
						<td><input type="text" name="description"
								value="<?php echo esc_attr($settings->description); ?>"
								class="regular-text"></td>
					</tr>

				</table>

				<?php submit_button('Save Settings'); ?>
			</form>
		</div>
<?php
	}

	// ---------------------------------------------------------
	// SAVE SETTINGS
	// ---------------------------------------------------------
	public function save_settings(): void {
		if (!current_user_can('manage_options')) wp_die('Access denied.');
		check_admin_referer('tiptop_settings_nonce');

		global $wpdb;

		$wpdb->update(
			$this->table_name,
			[
				'currency' => sanitize_text_field($_POST['currency']),
				'success_redirect' => esc_url_raw($_POST['success_redirect']),
				'terminal_id' => sanitize_text_field($_POST['terminal_id']),
				'description' => sanitize_text_field($_POST['description']),
			],
			['id' => 1]
		);

		wp_redirect(admin_url('admin.php?page=tiptop-payment&updated=true'));
		exit;
	}

	// ---------------------------------------------------------
	// FRONTEND SCRIPTS
	// ---------------------------------------------------------
	public function enqueue_scripts(): void {

		global $wpdb;
		$settings = $wpdb->get_row("SELECT * FROM {$this->table_name} LIMIT 1");

		// 1. TipTopPay SDK
		wp_enqueue_script(
			'tiptop-sdk',
			'https://widget.tiptoppay.kz/bundles/widget.js',
			[],
			null,
			true
		);

		// 2. TipTopPaymentWidget
		wp_enqueue_script(
			'tiptop-payment-widget',
			plugin_dir_url(dirname(__FILE__)) . 'scripts/TipTopPaymentWidget.js',
			['tiptop-sdk'],
			'1.0.0',
			false
		);

		wp_localize_script('tiptop-payment-widget', 'tiptopSettings', [
			'currency' => $settings->currency,
			'successRedirect' => $settings->success_redirect,
			'terminalId' => $settings->terminal_id,
			'description' => $settings->description,
			'ajaxUrl' => admin_url('admin-ajax.php'),
		]);
	}
}
