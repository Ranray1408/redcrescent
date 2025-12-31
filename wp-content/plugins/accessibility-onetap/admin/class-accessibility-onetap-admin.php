<?php
/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://wponetap.com
 * @since      1.0.0
 *
 * @package    Accessibility_Onetap
 * @subpackage Accessibility_Onetap/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Accessibility_Onetap
 * @subpackage Accessibility_Onetap/admin
 * @author     OneTap <support@wponetap.com>
 */
class Accessibility_Onetap_Admin {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * The option group name used for registering accessibility status settings.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $accessibility_status_option_group    Option group ID for accessibility status settings.
	 */
	protected $accessibility_status_option_group = 'options_group_onetap_accessibility_status';

	/**
	 * The list of allowed languages for accessibility status settings.
	 *
	 * @since    1.0.0
	 * @access   public
	 * @const    array     ALLOWED_LANGUAGES    Array of allowed language codes.
	 */
	const ALLOWED_LANGUAGES = array(
		'en',
		'de',
		'es',
		'fr',
		'it',
		'pl',
		'se',
		'fi',
		'pt',
		'ro',
		'si',
		'sk',
		'nl',
		'dk',
		'gr',
		'cz',
		'hu',
		'lt',
		'lv',
		'ee',
		'hr',
		'ie',
		'bg',
		'no',
		'tr',
		'id',
		'pt-br',
		'ja',
		'ko',
		'zh',
		'ar',
		'ru',
		'hi',
		'uk',
		'sr',
	);

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string $plugin_name       The name of this plugin.
	 * @param      string $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version     = $version;
	}

	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since    1.0.0
	 *
	 * @param string $hook The current admin page hook suffix passed by WordPress.
	 */
	public function enqueue_styles( $hook ) {

		if ( 'toplevel_page_accessibility-onetap-settings' === $hook ||
			'onetap_page_accessibility-onetap-general-settings' === $hook ||
			'onetap_page_accessibility-onetap-alt-text' === $hook ||
			'onetap_page_accessibility-onetap-modules' === $hook ||
			'admin_page_onetap-module-labels' === $hook ||
			'onetap_page_accessibility-onetap-accessibility-status' === $hook
		) {

			// Enqueue the main admin CSS for the plugin.
			wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/accessibility-onetap-admin.css', array(), $this->version, 'all' );

			// Enqueue Google Fonts (Inter) for the admin pages of the plugin.
			wp_enqueue_style(
				'onetap-google-fonts',
				'https://fonts.googleapis.com/css2?family=Inter:ital,opsz,wght@0,14..32,100..900;1,14..32,100..900&display=swap',
				array(),
				$this->version,
				'all'
			);
		}
	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    1.0.0
	 *
	 * @param string $hook The current admin page hook suffix passed by WordPress.
	 */
	public function enqueue_scripts( $hook ) {

		if ( 'toplevel_page_accessibility-onetap-settings' === $hook ||
			'onetap_page_accessibility-onetap-general-settings' === $hook ||
			'onetap_page_accessibility-onetap-alt-text' === $hook ||
			'onetap_page_accessibility-onetap-modules' === $hook ||
			'admin_page_onetap-module-labels' === $hook ||
			'onetap_page_accessibility-onetap-accessibility-status' === $hook
		) {
			wp_enqueue_script( $this->plugin_name, ACCESSIBILITY_ONETAP_PLUGINS_URL . 'assets/js/admin-menu.min.js', array( 'jquery' ), $this->version, true );

			wp_enqueue_script( $this->plugin_name . '-sweetalert', ACCESSIBILITY_ONETAP_PLUGINS_URL . 'assets/js/sweetalert.min.js', array(), $this->version, true );

			// Get plugin settings.
			$plugin_settings = get_option( 'onetap_settings' );

			// Get active language.
			$language = isset( $plugin_settings['language'] ) ? $plugin_settings['language'] : 'en';

			// Localize admin.
			wp_localize_script(
				$this->plugin_name,
				'adminLocalize',
				array(
					'ajaxUrl'         => admin_url( 'admin-ajax.php' ),
					'ajaxNonce'       => wp_create_nonce( 'onetap-ajax-verification' ),
					'adminUrl'        => esc_url( admin_url() ),
					'pluginUrl'       => ACCESSIBILITY_ONETAP_PLUGINS_URL,
					'activeLanguage'  => $language,
					'localizedLabels' => get_option( 'apop_localized_labels' ),
				)
			);
		}

		wp_enqueue_script( $this->plugin_name . '-admin-global', ACCESSIBILITY_ONETAP_PLUGINS_URL . 'assets/js/admin-global.min.js', array( 'jquery' ), $this->version, true );
	}

	/**
	 * Display admin notice.
	 */
	public function display_admin_notice() {
		$get_cookie_disable_admin_notices = isset( $_COOKIE['onetap_disable_admin_notices'] ) ? sanitize_key( $_COOKIE['onetap_disable_admin_notices'] ) : '';
		if ( 'disable' === $get_cookie_disable_admin_notices ) {
			return;
		}
		?>
		<div class="notice-success notice-onetap notice is-dismissible">
			<div class="box" style="display: none;">
				<div class="box-text">
					<p>
					<?php
					esc_html_e(
						"Hey!
					We’re happy that you’re using OneTap. We'd like to take this opportunity to kindly ask for a 5-star rating on WordPress.",
						'accessibility-onetap'
					);
					?>
						</p>
					<strong><?php esc_html_e( 'It would mean a lot to us.', 'accessibility-onetap' ); ?></strong>
				</div>
				<div class="box-btn">
					<a class="onetap-button" href="https://wordpress.org/support/plugin/accessibility-onetap/reviews/#new-post" target="_blank"><?php esc_html_e( 'Ok, you deserve it', 'accessibility-onetap' ); ?></a>
					<button class="outline already-did" type="button"><?php esc_html_e( 'Already did', 'accessibility-onetap' ); ?></button>
					<a class="onetap-button outline" href="https://wponetap.com/feedback" target="_blank"><?php esc_html_e( 'No, it’s not  good enough', 'accessibility-onetap' ); ?></a>
				</div>
			</div>

			<div class="otn-zya-notice otn-zya-notice--dismissible" data-notice_id="design_not_appearing">
				<i class="otn-zya-notice__dismiss" role="button" aria-label="Diesen Hinweis ausblenden." tabindex="0"></i>

				<div class="otn-zya-notice__aside-bg">
					<div class="otn-zya-icon">
					<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 283.5 283.5" width="20" height="20">
						<path d="M183.5 0H100C44.8 0 0 44.8 0 100v83.5c0 55.2 44.8 100 100 100h83.5c55.2 0 100-44.8 100-100V100c0-55.2-44.8-100-100-100Zm-55.2 61.5c19.6-14.9 41.7 7.2 26.8 26.8-19.6 14.9-41.7-7.2-26.8-26.8Zm80.7 46.7-42.9 9.7c-1.9.4-3.3 2-3.3 3.9 0 28.3 4.8 50.3 14.1 77.1l5.1 14.6c1.8 5.4-1.1 11.1-6.6 12.9-1.1.4 1 .5-3.4.5s-8.5-2.7-10-7L141.7 166l-20.3 53.9c-1.9 5.5-8.1 8.3-13.7 6.3-5.4-1.9-8.1-7.8-6.2-13.1l5-14.3c9.4-26.8 14.1-48.8 14.1-77.1s-1.4-3.5-3.3-3.9l-42.9-9.7c-5.1-1-8.7-5.6-7.9-10.6.8-5.2 5.9-8.7 11.3-7.7l35.6 5.5c18.7 2.9 37.8 2.9 56.6 0l35.6-5.5c5.3-.9 10.4 2.6 11.3 7.7.8 5-2.8 9.6-7.9 10.6Z" fill="#0048fe"/>
					</svg>
					</div>
				</div>

				<div class="otn-zya-notice__content">
					<h3><?php esc_html_e( 'Hey! We’re happy that you’re using OneTap!', 'accessibility-onetap' ); ?></h3>
					<p><?php esc_html_e( 'If OneTap helped you make your site more accessible, we’d truly appreciate a quick review – a good rating would mean a lot to us ❤️', 'accessibility-onetap' ); ?></p>

					<a id="otn-link-rate-positive" href="https://wordpress.org/support/plugin/accessibility-onetap/reviews/#new-post" target="_blank">
						<?php esc_html_e( 'Ok, you deserve it', 'accessibility-onetap' ); ?>
					</a>
					<a id="otn-link-rate-done" href="#" class="already-did">
						<?php esc_html_e( 'Already did', 'accessibility-onetap' ); ?>
					</a>
					<a id="otn-link-rate-negative" href="https://wponetap.com/feedback" target="_blank">
						<?php esc_html_e( 'No, it’s not good enough', 'accessibility-onetap' ); ?>
					</a>
				</div>
			</div>
		</div>
		<style>
		.notice-onetap .otn-zya-notice {
			display: flex;
			position: relative;
			border: 1px solid #eee;
			border-left: 4px solid #0048FE;
			background-color: #fff;
			font-family: Arial, sans-serif;
			box-shadow: 0 1px 4px rgba(0, 0, 0, 0.05);
		}

		.notice-onetap .otn-zya-notice__dismiss {
			position: absolute;
			top: 0.75em;
			right: 0.75em;
			cursor: pointer;
			font-size: 16px;
			color: #3f444b;
		}

		.notice-onetap .otn-zya-notice__aside-bg {
			background-color: #ECF1F7; /* gräuliches grün wie im Screenshot */
			padding: 1em;
			display: flex;
			align-items: flex-start;
			justify-content: center;
		}

		.notice-onetap .otn-zya-icon {
			background-color: #fff; /* z. B. Schwarz für dunklen Kreis */
			border-radius: 100%;

			display: flex;
			align-items: center;
			justify-content: center;
		}

		.notice-onetap .otn-zya-notice__content {
			background-color: #fff;
			padding: 1em;
			flex-grow: 1;
		}

		.notice-onetap .otn-zya-notice__content h3 {
			margin: 0 0 0.5em;
			font-size: 1rem;
			font-weight: 600;
			color: #1e1e1e;
		}

		.notice-onetap .otn-zya-notice__content p {
			font-size: .875rem !important;
			color: #3c434a;
			margin-bottom: 0.75rem;
		}

		.notice-onetap .otn-zya-notice__content a {
			color: 0048FE;
			text-decoration: underline;
			margin-bottom: 0.75rem;
		}

		.notice-onetap #otn-link-rate-positive {
			display: inline-block;
			text-decoration: none;
			font-size: 13px;
			line-height: 2.15384615;
			min-height: 30px;
			margin: 0;
			padding: 0 10px;
			cursor: pointer;
			border-width: 1px;
			border-style: solid;
			-webkit-appearance: none;
			border-radius: 3px;
			white-space: nowrap;
			box-sizing: border-box;
			color: #fff;
			border-color: #2271b1;
			background: #2271b1;
			vertical-align: top;
			margin-right: 0.5rem;
			font-weight: 400;
		}

		.notice-onetap #otn-link-rate-done {
			display: inline-block;
			text-decoration: none;
			font-size: 13px;
			line-height: 2.15384615;
			min-height: 30px;
			margin: 0;
			padding: 0 10px;
			cursor: pointer;
			-webkit-appearance: none;
			border-radius: 3px;
			white-space: nowrap;
			box-sizing: border-box;
			color: #2271b1;
			vertical-align: top;
			margin-right: 0rem;
			text-decoration: underline !important;
			font-weight: 400;
		}

		.notice-onetap #otn-link-rate-negative {
			display: inline-block;
			text-decoration: none;
			font-size: 13px;
			line-height: 2.15384615;
			min-height: 30px;
			margin: 0;
			padding: 0 10px;
			cursor: pointer;
			-webkit-appearance: none;
			border-radius: 3px;
			white-space: nowrap;
			box-sizing: border-box;
			color: #2271b1;
			vertical-align: top;
			margin-right: 0rem;
			text-decoration: underline !important;
			font-weight: 400;
		}

			.notice-onetap {
				padding: 0 !important;
				border: none;
				/* font-family: system-ui, -apple-system, BlinkMacSystemFont, Segoe UI, Roboto, Noto Sans, Ubuntu, Cantarell, Helvetica Neue, Oxygen, Fira Sans, Droid Sans, sans-serif !important; */
			}

			.notice-onetap .box {
				background: #0048FE;
				border-radius: 8px;
				padding: 25px;
			}

			.notice-onetap .notice-dismiss:focus {
				box-shadow: none;
				outline: 0;
			}

			.notice-onetap .notice-dismiss::before {
				color: #FFFFFF;
			}

			.notice-onetap .box .box-text p,
			.notice-onetap .box .box-text strong {
				color: #FFFFFF;
				font-size: 16px;
				font-weight: 400;
				line-height: 1.5rem;
				padding: 0;
				margin: 0;
			}

			.notice-onetap .box .box-text strong {
				font-weight: 600;
			}

			.notice-onetap .box .box-btn {
				margin-top: 20px;
				display: flex;
				flex-wrap: wrap;
				gap: 10px;
			}

			.notice-onetap .box .box-btn button,
			.notice-onetap .box .box-btn a.onetap-button {
				font-size: 0.875rem;
				line-height: 1.25rem;
				fill: #0048FE;
				color: #0048FE;
				background-color: #FFFFFF;
				border-style: solid;
				border-width: 1px 1px 1px 1px;
				border-color: #FFFFFF;
				padding: 0.625rem 0.875rem 0.625rem 0.875rem;
				border-radius: 6px;
				font-weight: 500;
				display: inline-block;
				cursor: pointer;
				text-decoration: none;
			}

			.notice-onetap .box .box-btn button.outline,
			.notice-onetap .box .box-btn a.outline {
				fill: #FFFFFF;
				color: #FFFFFF;
				background: transparent;
			}
		</style>
		<?php
	}

	/**
	 * Callback function for AJAX to dismiss admin notices and set a cookie.
	 */
	public function dismiss_notice_ajax_callback() {
		// Security check: Ensure nonce is valid.
		check_ajax_referer( 'onetap-ajax-verification', 'mynonce' );

		// Set a cookie to disable admin notices for 5 days.
		setcookie( 'onetap_disable_admin_notices', 'disable', time() + ( 5 * 24 * 60 * 60 ) );

		// Terminate the AJAX request.
		wp_send_json( array( 'success' => true ) );
		wp_die();
	}

	/**
	 * Allow external domain for safe redirect.
	 *
	 * @param string[] $hosts Array of allowed hosts.
	 * @return string[] Modified array with external host.
	 */
	public function onetap_allow_external_redirect_host( $hosts ) {
		$hosts[] = 'wponetap.com';
		return $hosts;
	}

	/**
	 * Redirect users accessing a specific admin page to an external pricing URL.
	 *
	 * This function runs on 'admin_init' and checks whether the current admin page
	 * matches a specific plugin slug.
	 *
	 * @return void
	 */
	public function onetap_redirect_admin_page_to_pricing() {
		global $plugin_page, $pagenow;

		// Check if we are in the admin and on admin.php.
		if ( is_admin() && 'admin.php' === $pagenow && 'accessibility-onetap-pro' === $plugin_page ) {
			// Redirect.
			wp_safe_redirect( 'https://wponetap.com/pricing/', 301 );
			exit;
		}
	}

	/**
	 * Add a custom "Settings" link below the plugin description metadata
	 * (next to "Version | By | View details") in the Plugins admin screen.
	 *
	 * This function hooks into 'plugin_row_meta' and adds a link to the plugin's settings page
	 * only for this specific plugin file.
	 *
	 * @param array  $links Array of existing plugin row meta links.
	 * @param string $file  The plugin file path (relative to the plugins directory).
	 *
	 * @return array Modified array of plugin row meta links.
	 */
	public function add_row_meta( $links, $file ) {

		// Only add the settings link for this specific plugin.
		if ( 'accessibility-onetap/accessibility-onetap.php' === $file ) {
			$settings_url  = admin_url( 'admin.php?page=accessibility-onetap-settings' );
			$settings_link = '<a href="' . esc_url( $settings_url ) . '">' . esc_html__( 'Settings', 'accessibility-onetap' ) . '</a>';

			// Append the custom settings link to the plugin meta row.
			$links[] = $settings_link;
		}

		return $links;
	}

	/**
	 * Register settings related to the accessibility status section.
	 *
	 * This method registers multiple options using the WordPress Settings API,
	 * including checkboxes, text inputs, select dropdowns, and rich text fields.
	 *
	 * @since 1.0.0
	 * @access public
	 * @return void
	 */
	public function register_settings_for_accessibility_status() {
		// Register a boolean setting for showing the accessibility status toggle.
		register_setting(
			$this->accessibility_status_option_group,
			'onetap_show_accessibility',
			array(
				'type'              => 'boolean',
				'sanitize_callback' => array( $this, 'sanitize_checkbox_input' ),
			)
		);

		// Register a string setting for language selection.
		register_setting(
			$this->accessibility_status_option_group,
			'onetap_select_language',
			array(
				'type'              => 'string',
				'sanitize_callback' => array( $this, 'sanitize_select_language' ),
			)
		);

		// Register a string setting for the company name input.
		register_setting(
			$this->accessibility_status_option_group,
			'onetap_company_name',
			array(
				'type'              => 'string',
				'sanitize_callback' => 'sanitize_text_field',
			)
		);

		// Register a string setting for the company website URL.
		register_setting(
			$this->accessibility_status_option_group,
			'onetap_company_website',
			array(
				'type'              => 'string',
				'sanitize_callback' => 'esc_url_raw',
			)
		);

		// Register a string setting for the business email address.
		register_setting(
			$this->accessibility_status_option_group,
			'onetap_business_email',
			array(
				'type'              => 'string',
				'sanitize_callback' => 'sanitize_email',
			)
		);

		// Register a boolean setting for the confirmation checkbox.
		register_setting(
			$this->accessibility_status_option_group,
			'onetap_confirmation_checkbox',
			array(
				'type'              => 'boolean',
				'sanitize_callback' => array( $this, 'sanitize_checkbox_input' ),
			)
		);

		// Register a string setting for a WYSIWYG or rich text editor.
		register_setting(
			$this->accessibility_status_option_group,
			'onetap_editor_generator',
			array(
				'type'              => 'string',
				'sanitize_callback' => 'wp_kses_post',
			)
		);
	}

	/**
	 * Sanitize checkbox input value.
	 *
	 * Converts any truthy value to integer 1, otherwise returns 0.
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @param mixed $value The input value to sanitize.
	 * @return int Sanitized checkbox value (1 or 0).
	 */
	public function sanitize_checkbox_input( $value ) {
		return ( isset( $value ) && (bool) $value ) ? 1 : 0;
	}

	/**
	 * Sanitize the selected language input.
	 *
	 * Ensures the selected language is in the list of allowed values.
	 * Defaults to 'en' if the input is not valid.
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @param string $value The selected language value.
	 * @return string Sanitized language code.
	 */
	public function sanitize_select_language( $value ) {
		return in_array( $value, self::ALLOWED_LANGUAGES, true ) ? $value : 'en';
	}
}
