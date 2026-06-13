<?php

if (!defined('ABSPATH')) exit;

class TipTopPaymentSalesforce {

	private string $table_name;

	public function __construct() {
		global $wpdb;
		$this->table_name = $wpdb->prefix . 'tiptop_payment_settings';

		add_action('wp_ajax_sf_get_active_data', [$this, 'ajax_get_active_data']);
		add_action('wp_ajax_nopriv_sf_get_active_data', [$this, 'ajax_get_active_data']);
	}

	private function get_settings(): ?object {
		global $wpdb;
		$row = $wpdb->get_row("SELECT * FROM {$this->table_name} LIMIT 1");

		if (!$row || empty($row->instance_url) || empty($row->client_id) || empty($row->client_secret)) {
			return null;
		}

		return $row;
	}

	// ---------------------------------------------------------
	// OAuth access token
	// ---------------------------------------------------------
	public function get_access_token(): ?string {
		$cached = get_transient('tiptop_sf_token');
		if ($cached !== false) {
			return $cached;
		}

		$settings = $this->get_settings();
		if (!$settings) return null;

		$response = wp_remote_post(
			trailingslashit($settings->instance_url) . 'services/oauth2/token',
			[
				'body' => [
					'grant_type' => 'client_credentials',
					'client_id' => $settings->client_id,
					'client_secret' => $settings->client_secret,
				],
				'timeout' => 15,
			]
		);

		if (is_wp_error($response)) {
			error_log('TipTop SF OAuth error: ' . $response->get_error_message());
			return null;
		}

		$code = wp_remote_retrieve_response_code($response);
		$body = json_decode(wp_remote_retrieve_body($response), true);

		if ($code !== 200 || empty($body['access_token'])) {
			error_log('TipTop SF OAuth bad response: ' . print_r($body, true));
			return null;
		}

		// Cache for 1.5 hours (token valid ~2 hours)
		set_transient('tiptop_sf_token', $body['access_token'], 5400);

		return $body['access_token'];
	}

	// ---------------------------------------------------------
	// Active agents
	// ---------------------------------------------------------
	public function get_active_agents(): array {
		$cached = get_transient('tiptop_sf_agents');
		if ($cached !== false) {
			return $cached;
		}

		$settings = $this->get_settings();
		if (!$settings) return [];

		$token = $this->get_access_token();
		if (!$token) return [];

		$query = 'SELECT+Id,+Name,+core_Face2Face_AgentId__pc+FROM+Account+WHERE+IsPersonAccount=true+AND+core_Face2Face_Active_Agent__pc=true+ORDER+BY+Name';

		$response = wp_remote_get(
			trailingslashit($settings->instance_url) . "services/data/v61.0/query/?q={$query}",
			[
				'headers' => [
					'Authorization' => "Bearer {$token}",
				],
				'timeout' => 15,
			]
		);

		if (is_wp_error($response)) {
			error_log('TipTop SF agents error: ' . $response->get_error_message());
			return [];
		}

		$body = json_decode(wp_remote_retrieve_body($response), true);
		$records = $body['records'] ?? [];

		$agents = [];
		foreach ($records as $record) {
			if (!empty($record['core_Face2Face_AgentId__pc'])) {
				$agents[] = [
					'id' => $record['core_Face2Face_AgentId__pc'],
					'name' => $record['Name'] ?? '',
				];
			}
		}

		set_transient('tiptop_sf_agents', $agents, 900);

		return $agents;
	}

	public function get_active_venues(): array {
		$cached = get_transient('tiptop_sf_venues');
		if ($cached !== false) {
			return $cached;
		}

		$settings = $this->get_settings();
		if (!$settings) return [];

		$token = $this->get_access_token();
		if (!$token) return [];

		$query = 'SELECT+Id,+core_Face2Face_Venue_Id__c,+core_Face2Face_Venue_Name__c+FROM+Account+WHERE+IsPersonAccount=false+AND+core_Face2Face_Venue_Active__c=true+ORDER+BY+core_Face2Face_Venue_Name__c';

		$response = wp_remote_get(
			trailingslashit($settings->instance_url) . "services/data/v61.0/query/?q={$query}",
			[
				'headers' => [
					'Authorization' => "Bearer {$token}",
				],
				'timeout' => 15,
			]
		);

		if (is_wp_error($response)) {
			error_log('TipTop SF venues error: ' . $response->get_error_message());
			return [];
		}

		$body = json_decode(wp_remote_retrieve_body($response), true);
		$records = $body['records'] ?? [];

		$venues = [];
		foreach ($records as $record) {
			if (!empty($record['core_Face2Face_Venue_Id__c'])) {
				$venues[] = [
					'id' => $record['core_Face2Face_Venue_Id__c'],
					'name' => $record['core_Face2Face_Venue_Name__c'] ?? '',
				];
			}
		}

		set_transient('tiptop_sf_venues', $venues, 900);

		return $venues;
	}

	// ---------------------------------------------------------
	// AJAX handler for frontend
	// ---------------------------------------------------------
	public function ajax_get_active_data(): void {
		$agents = $this->get_active_agents();
		$venues = $this->get_active_venues();

		wp_send_json([
			'success' => true,
			'agents' => $agents,
			'venues' => $venues,
		]);
	}
}
