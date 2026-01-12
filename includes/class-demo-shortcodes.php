<?php
/**
 * Demo Shortcodes
 *
 * Registers demo-specific shortcodes for user registration.
 * 
 * @package ChurchTools_Suite_Demo
 * @since   1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class ChurchTools_Suite_Demo_Shortcodes {
	
	/**
	 * Registration Service
	 *
	 * @var ChurchTools_Suite_Demo_Registration_Service
	 */
	private $registration_service;
	
	/**
	 * Constructor
	 *
	 * @param ChurchTools_Suite_Demo_Registration_Service $registration_service
	 */
	public function __construct( ChurchTools_Suite_Demo_Registration_Service $registration_service ) {
		$this->registration_service = $registration_service;
	}
	
	/**
	 * Initialize shortcodes
	 */
	public function init(): void {
		add_shortcode( 'cts_demo_register', [ $this, 'render_registration_form' ] );
		add_shortcode( 'cts_demo_register_success', [ $this, 'render_registration_success' ] );
		
		// Register AJAX handlers
		add_action( 'wp_ajax_nopriv_cts_demo_register', [ $this, 'ajax_register' ] );
		add_action( 'wp_ajax_cts_demo_register', [ $this, 'ajax_register' ] );
		
		// Enqueue scripts
		add_action( 'wp_enqueue_scripts', [ $this, 'enqueue_scripts' ] );
	}
	
	/**
	 * Render registration form
	 *
	 * @param array $atts Shortcode attributes
	 * @return string HTML output
	 */
	public function render_registration_form( $atts ): string {
		ob_start();
		include CHURCHTOOLS_SUITE_DEMO_PATH . 'templates/demo/registration-form.php';
		return ob_get_clean();
	}
	
	/**
	 * Render registration success page (new in v1.0.2)
	 * 
	 * Usage: [cts_demo_register_success email="user@example.com" password="xxx" demo_url="..."]
	 *
	 * @param array $atts Shortcode attributes
	 * @return string HTML output
	 */
	public function render_registration_success( $atts ): string {
		$atts = shortcode_atts( [
			'email' => '',
			'password' => '',
			'demo_url' => admin_url(),
		], $atts );
		
		if ( empty( $atts['email'] ) || empty( $atts['password'] ) ) {
			return '<p style="color: red;">' . esc_html__( 'Registrierungs-Daten fehlen', 'churchtools-suite-demo' ) . '</p>';
		}
		
		// Load response class
		require_once CHURCHTOOLS_SUITE_DEMO_PATH . 'includes/class-demo-registration-response.php';
		
		ob_start();
		ChurchTools_Suite_Demo_Registration_Response::render_success(
			[ 'email' => $atts['email'] ],
			$atts['password'],
			$atts['demo_url']
		);
		return ob_get_clean();
	}
	
	/**
	 * Handle AJAX registration
	 */
	public function ajax_register(): void {
		// Verify nonce
		check_ajax_referer( 'cts_demo_register', 'nonce' );
		
		// Get form data
		$data = [
			'email' => sanitize_email( $_POST['email'] ?? '' ),
			'name' => sanitize_text_field( $_POST['name'] ?? '' ),
			'company' => sanitize_text_field( $_POST['company'] ?? '' ),
			'purpose' => sanitize_textarea_field( $_POST['purpose'] ?? '' ),
		];
		
		// Validate DSGVO checkbox
		if ( empty( $_POST['privacy_accepted'] ) ) {
			wp_send_json_error( [
				'message' => __( 'Bitte akzeptieren Sie die Datenschutzerklärung', 'churchtools-suite-demo' ),
			] );
		}
		
		// Register user
		$result = $this->registration_service->register_user( $data );
		
		if ( is_wp_error( $result ) ) {
			wp_send_json_error( [
				'message' => $result->get_error_message(),
			] );
		}
		
		// v1.0.2: Return password + success URL for post-registration display
		wp_send_json_success( [
			'message' => __( 'Registrierung erfolgreich! Bitte prüfen Sie Ihre E-Mails zur Verifizierung.', 'churchtools-suite-demo' ),
			'email' => $data['email'],
			'password' => $result['password'] ?? '', // Generated password (if applicable)
			'redirect_url' => apply_filters( 'cts_demo_registration_redirect_url', admin_url(), $data ),
		] );
	}
	
	/**
	 * Enqueue scripts and styles
	 */
	public function enqueue_scripts(): void {
		global $post;
		
		// Only enqueue on pages with shortcode
		if ( is_a( $post, 'WP_Post' ) && has_shortcode( $post->post_content, 'cts_demo_register' ) ) {
			wp_enqueue_style(
				'cts-demo-registration',
				CHURCHTOOLS_SUITE_DEMO_URL . 'assets/css/registration.css',
				[],
				CHURCHTOOLS_SUITE_DEMO_VERSION
			);
			
			wp_enqueue_script(
				'cts-demo-registration',
				CHURCHTOOLS_SUITE_DEMO_URL . 'assets/js/registration.js',
				[ 'jquery' ],
				CHURCHTOOLS_SUITE_DEMO_VERSION,
				true
			);
			
			wp_localize_script(
				'cts-demo-registration',
				'ctsDemo',
				[
					'ajaxUrl' => admin_url( 'admin-ajax.php' ),
					'nonce' => wp_create_nonce( 'cts_demo_register' ),
					'strings' => [
						'sending' => __( 'Wird gesendet...', 'churchtools-suite-demo' ),
						'error' => __( 'Ein Fehler ist aufgetreten', 'churchtools-suite-demo' ),
					],
				]
			);
		}
	}
}
