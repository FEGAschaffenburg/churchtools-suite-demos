<?php
/**
 * Demo Registration Service
 *
 * Handles self-service demo user registration with email verification.
 * 
 * @package ChurchTools_Suite
 * @since   0.10.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class ChurchTools_Suite_Demo_Registration_Service {
	
	/**
	 * Demo Users Repository
	 *
	 * @var ChurchTools_Suite_Demo_Users_Repository
	 */
	private $repo;
	
	/**
	 * Constructor
	 *
	 * @param ChurchTools_Suite_Demo_Users_Repository $repo Demo users repository
	 */
	public function __construct( ChurchTools_Suite_Demo_Users_Repository $repo ) {
		$this->repo = $repo;
	}
	
	/**
	 * Register new demo user
	 *
	 * @param array $data {
	 *     Registration data
	 *     @type string $email    Required
	 *     @type string $name     Optional
	 *     @type string $company  Optional
	 *     @type string $purpose  Optional
	 * }
	 * @return array|WP_Error {
	 *     Success: ['demo_user_id' => int, 'token' => string]
	 *     Error: WP_Error object
	 * }
	 */
	public function register_user( array $data ) {
		// Validate email
		$email = sanitize_email( $data['email'] ?? '' );
		
		if ( ! is_email( $email ) ) {
			return new WP_Error( 'invalid_email', __( 'Ungültige E-Mail-Adresse', 'churchtools-suite' ) );
		}
		
		// Check if email already registered
		$existing = $this->repo->get_by_email( $email );
		if ( $existing ) {
			if ( $existing->verified_at ) {
				return new WP_Error( 'email_exists', __( 'Diese E-Mail-Adresse ist bereits registriert', 'churchtools-suite' ) );
			} else {
				// Resend verification email
				$this->send_verification_email( $existing->id, $email, $existing->verification_token );
				return new WP_Error( 'email_unverified', __( 'Verifizierungs-Email wurde erneut gesendet', 'churchtools-suite' ) );
			}
		}
		
		// Generate verification token
		$token = $this->generate_verification_token();
		
		// Create demo user record
		$demo_user_id = $this->repo->create( [
			'email' => $email,
			'name' => sanitize_text_field( $data['name'] ?? '' ),
			'company' => sanitize_text_field( $data['company'] ?? '' ),
			'purpose' => sanitize_textarea_field( $data['purpose'] ?? '' ),
			'verification_token' => $token,
		] );
		
		if ( ! $demo_user_id ) {
			return new WP_Error( 'creation_failed', __( 'Registrierung fehlgeschlagen', 'churchtools-suite' ) );
		}
		
		// Send verification email
		$sent = $this->send_verification_email( $demo_user_id, $email, $token );
		
		if ( ! $sent ) {
			return new WP_Error( 'email_failed', __( 'Verifizierungs-Email konnte nicht gesendet werden', 'churchtools-suite' ) );
		}
		
		// Log registration
		if ( class_exists( 'ChurchTools_Suite_Logger' ) ) {
			ChurchTools_Suite_Logger::log( 'demo_registration', 'New demo user registered', [
				'demo_user_id' => $demo_user_id,
				'email' => $email,
				'company' => $data['company'] ?? null,
			] );
		}
		
		// Send admin notification
		$this->send_admin_notification( $demo_user_id, $email, $data );
		
		return [
			'demo_user_id' => $demo_user_id,
			'token' => $token,
		];
	}
	
	/**
	 * Verify email and create WordPress user
	 *
	 * @param string $token Verification token
	 * @return array|WP_Error {
	 *     Success: ['wp_user_id' => int, 'auto_login' => bool]
	 *     Error: WP_Error object
	 * }
	 */
	public function verify_email( string $token ) {
		// Get demo user by token
		$demo_user = $this->repo->get_by_token( $token );
		
		if ( ! $demo_user ) {
			return new WP_Error( 'invalid_token', __( 'Ungültiger Verifizierungs-Link', 'churchtools-suite' ) );
		}
		
		// Check if already verified
		if ( $demo_user->verified_at ) {
			return new WP_Error( 'already_verified', __( 'E-Mail bereits verifiziert. Sie können sich jetzt anmelden.', 'churchtools-suite' ) );
		}
		
		// Create WordPress user
		$wp_user_id = $this->create_wp_user( $demo_user );
		
		if ( is_wp_error( $wp_user_id ) ) {
			return $wp_user_id;
		}
		
		// Mark as verified
		$this->repo->verify( $demo_user->id, $wp_user_id );
		
		// Log verification
		if ( class_exists( 'ChurchTools_Suite_Logger' ) ) {
			ChurchTools_Suite_Logger::log( 'demo_registration', 'Demo user verified', [
				'demo_user_id' => $demo_user->id,
				'wp_user_id' => $wp_user_id,
				'email' => $demo_user->email,
			] );
		}
		
		return [
			'wp_user_id' => $wp_user_id,
			'auto_login' => true,
		];
	}
	
	/**
	 * Auto-login user after verification
	 *
	 * @param int $wp_user_id WordPress user ID
	 * @return bool Success
	 */
	public function auto_login( int $wp_user_id ): bool {
		$user = get_userdata( $wp_user_id );
		
		if ( ! $user ) {
			return false;
		}
		
		// Set auth cookies
		wp_set_auth_cookie( $wp_user_id, true );
		wp_set_current_user( $wp_user_id );
		do_action( 'wp_login', $user->user_login, $user );
		
		// Update last login
		$demo_user = $this->repo->get_by_wp_user_id( $wp_user_id );
		if ( $demo_user ) {
			$this->repo->update_last_login( $demo_user->id );
		}
		
		return true;
	}
	
	/**
	 * Create WordPress user with demo role
	 *
	 * @param object $demo_user Demo user object
	 * @return int|WP_Error WordPress user ID or error
	 */
	private function create_wp_user( object $demo_user ) {
		// Generate username from email
		$username = $this->generate_username( $demo_user->email );
		
		// Generate random password
		$password = wp_generate_password( 20, true, true );
		
		// Create user
		$user_id = wp_create_user( $username, $password, $demo_user->email );
		
		if ( is_wp_error( $user_id ) ) {
			return new WP_Error( 'user_creation_failed', __( 'WordPress-Benutzer konnte nicht erstellt werden', 'churchtools-suite' ) );
		}
		
		// Set display name
		wp_update_user( [
			'ID' => $user_id,
			'display_name' => $demo_user->name ?: $demo_user->email,
			'first_name' => $demo_user->name,
		] );
		
		// Assign demo role
		$user = new WP_User( $user_id );
		$user->set_role( 'cts_demo_user' );
		
		return $user_id;
	}
	
	/**
	 * Generate verification token
	 *
	 * @return string Unique 64-character token
	 */
	private function generate_verification_token(): string {
		return bin2hex( random_bytes( 32 ) );
	}
	
	/**
	 * Generate username from email
	 *
	 * @param string $email Email address
	 * @return string Unique username
	 */
	private function generate_username( string $email ): string {
		$base = sanitize_user( current( explode( '@', $email ) ), true );
		$username = 'demo_' . $base;
		
		// Ensure uniqueness
		$counter = 1;
		while ( username_exists( $username ) ) {
			$username = 'demo_' . $base . '_' . $counter;
			$counter++;
		}
		
		return $username;
	}
	
	/**
	 * Send verification email
	 *
	 * @param int    $demo_user_id Demo user ID
	 * @param string $email        Email address
	 * @param string $token        Verification token
	 * @return bool Success
	 */
	private function send_verification_email( int $demo_user_id, string $email, string $token ): bool {
		$verification_url = add_query_arg( [
			'action' => 'cts_verify_demo_user',
			'token' => $token,
		], home_url( '/' ) );
		
		$subject = sprintf( __( 'Verifizieren Sie Ihre Demo-Registrierung - %s', 'churchtools-suite' ), get_bloginfo( 'name' ) );
		
		$message = sprintf(
			__( "Hallo,\n\nvielen Dank für Ihre Registrierung für die ChurchTools Suite Demo!\n\nBitte verifizieren Sie Ihre E-Mail-Adresse, indem Sie auf den folgenden Link klicken:\n\n%s\n\nDieser Link ist 7 Tage gültig.\n\nNach der Verifizierung erhalten Sie sofortigen Zugang zum WordPress-Backend und können das Plugin testen.\n\nHinweis: Ihr Demo-Zugang ist 30 Tage gültig und wird dann automatisch gelöscht.\n\nViel Spaß beim Testen!\n\nMit freundlichen Grüßen\nIhr ChurchTools Suite Team", 'churchtools-suite' ),
			$verification_url
		);
		
		$headers = [
			'Content-Type: text/plain; charset=UTF-8',
		];
		
		return wp_mail( $email, $subject, $message, $headers );
	}
	
	/**
	 * Send admin notification
	 *
	 * @param int    $demo_user_id Demo user ID
	 * @param string $email        User email
	 * @param array  $data         Registration data
	 * @return bool Success
	 */
	private function send_admin_notification( int $demo_user_id, string $email, array $data ): bool {
		$admin_email = get_option( 'admin_email' );
		
		if ( ! $admin_email ) {
			return false;
		}
		
		$subject = sprintf( __( 'Neue Demo-Registrierung - %s', 'churchtools-suite' ), get_bloginfo( 'name' ) );
		
		$message = sprintf(
			__( "Neue Demo-Registrierung:\n\nE-Mail: %s\nName: %s\nFirma/Gemeinde: %s\nZweck: %s\n\nRegistriert am: %s\n\nDer Benutzer muss seine E-Mail-Adresse noch verifizieren.", 'churchtools-suite' ),
			$email,
			$data['name'] ?? '-',
			$data['company'] ?? '-',
			$data['purpose'] ?? '-',
			current_time( 'Y-m-d H:i:s' )
		);
		
		$headers = [
			'Content-Type: text/plain; charset=UTF-8',
		];
		
		return wp_mail( $admin_email, $subject, $message, $headers );
	}
}
