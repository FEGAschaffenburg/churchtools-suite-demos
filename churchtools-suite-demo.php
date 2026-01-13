<?php
/**
 * Plugin Name:       ChurchTools Suite Demo
 * Plugin URI:        https://github.com/FEGAschaffenburg/churchtools-suite
 * Description:       Demo-Addon für ChurchTools Suite - Self-Service Demo Registration mit Backend-Zugang. Erfordert ChurchTools Suite v1.0.0+
 * Version:           1.0.5.15
 * Requires at least: 6.0
 * Requires PHP:      8.0
 * Requires Plugins:  churchtools-suite
 * Author:            FEG Aschaffenburg
 * Author URI:        https://feg-aschaffenburg.de
 * License:           GPL v2 or later
 * License URI:       https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain:       churchtools-suite-demo
 * Domain Path:       /languages
 *
 * @package ChurchTools_Suite_Demo
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// Suppress WP 6.7 JIT translation notice IMMEDIATELY (v1.0.5.15)
remove_filter( 'load_textdomain_mofile', 'wp_check_load_textdomain_just_in_time' );

// Define plugin constants
define( 'CHURCHTOOLS_SUITE_DEMO_VERSION', '1.0.5.15' );
define( 'CHURCHTOOLS_SUITE_DEMO_PATH', plugin_dir_path( __FILE__ ) );
define( 'CHURCHTOOLS_SUITE_DEMO_URL', plugin_dir_url( __FILE__ ) );

/**
 * Main Plugin Class
 */
class ChurchTools_Suite_Demo {
	
	/**
	 * Singleton instance
	 *
	 * @var ChurchTools_Suite_Demo
	 */
	private static $instance = null;
	
	/**
	 * Demo Users Repository
	 *
	 * @var ChurchTools_Suite_Demo_Users_Repository
	 */
	public $demo_users_repo;
	
	/**
	 * Demo Registration Service
	 *
	 * @var ChurchTools_Suite_Demo_Registration_Service
	 */
	public $registration_service;
	
	/**
	 * Get singleton instance
	 *
	 * @return ChurchTools_Suite_Demo
	 */
	public static function instance(): ChurchTools_Suite_Demo {
		if ( is_null( self::$instance ) ) {
			self::$instance = new self();
		}
		return self::$instance;
	}
	
	/**
	 * Constructor
	 */
	private function __construct() {
		// Load text domain early (WordPress 6.7 compatibility)
		add_action( 'init', [ $this, 'load_textdomain' ], 1 );
		
		// Initialize plugin first (loads translations)
		add_action( 'plugins_loaded', [ $this, 'init' ] );
		
		// Check dependencies after init
		add_action( 'admin_init', [ $this, 'check_dependencies' ] );
		
		// Activation/Deactivation hooks
		register_activation_hook( __FILE__, [ $this, 'activate' ] );
		register_deactivation_hook( __FILE__, [ $this, 'deactivate' ] );
	}
	
	/**
	 * Load plugin text domain (WordPress 6.7 compatibility)
	 */
	public function load_textdomain(): void {
		load_plugin_textdomain( 'churchtools-suite-demo', false, dirname( plugin_basename( __FILE__ ) ) . '/languages' );
	}

	/**
	 * Check if parent plugin is active
	 */
	public function check_dependencies(): void {
		if ( ! class_exists( 'ChurchTools_Suite' ) ) {
			deactivate_plugins( plugin_basename( __FILE__ ) );
			wp_die(
				'ChurchTools Suite Demo benötigt das ChurchTools Suite Plugin. Bitte installieren und aktivieren Sie es zuerst.',
				'Plugin-Abhängigkeit fehlt',
				[ 'back_link' => true ]
			);
		}
	}
	
	/**
	 * Initialize plugin (v1.0.3.1: Create tables on init for robustness)
	 */
	public function init(): void {
		// Ensure database tables exist (robustness: also on init, not just activation)
		$this->create_tables();
		
		// Load dependencies
		$this->load_dependencies();
		
		// Initialize repositories
		$this->init_repositories();
		
		// Initialize services
		$this->init_services();
		
		// Register custom user role
		$this->register_demo_role();
		
		// Register shortcodes
		$this->register_shortcodes();
		
		// Register admin pages
		if ( is_admin() ) {
			$this->register_admin();
		}
		
		// Register cron jobs
		$this->register_cron_jobs();
		
		// Register verification handler
		add_action( 'template_redirect', [ $this, 'handle_verification' ] );
		
		// Hook into parent plugin's data provider (Priority 99 to ensure it runs last)
		add_filter( 'churchtools_suite_get_events', [ $this, 'provide_demo_events' ], 99, 2 );
		
		// Intercept sync operations and simulate them
		add_action( 'wp_ajax_cts_sync_calendars', [ $this, 'simulate_calendar_sync' ], 1 );
		add_action( 'wp_ajax_cts_sync_events', [ $this, 'simulate_event_sync' ], 1 );
		add_action( 'wp_ajax_cts_test_connection', [ $this, 'simulate_connection_test' ], 1 );
		
		// Prevent settings changes in demo mode (but no persistent notice)
		add_action( 'wp_ajax_cts_save_calendar_selection', [ $this, 'prevent_calendar_changes' ], 1 );
	}
	
	/**
	 * Load plugin dependencies
	 */
	private function load_dependencies(): void {
		// Repositories
		require_once CHURCHTOOLS_SUITE_DEMO_PATH . 'includes/repositories/class-demo-users-repository.php';
		
		// Services
		require_once CHURCHTOOLS_SUITE_DEMO_PATH . 'includes/services/class-demo-data-provider.php';
		require_once CHURCHTOOLS_SUITE_DEMO_PATH . 'includes/services/class-demo-registration-service.php';
		
		// Admin
		require_once CHURCHTOOLS_SUITE_DEMO_PATH . 'admin/class-demo-admin.php';
		
		// Shortcodes
		require_once CHURCHTOOLS_SUITE_DEMO_PATH . 'includes/class-demo-shortcodes.php';
	}
	
	/**
	 * Initialize repositories
	 */
	private function init_repositories(): void {
		$this->demo_users_repo = new ChurchTools_Suite_Demo_Users_Repository();
	}
	
	/**
	 * Initialize services
	 */
	private function init_services(): void {
		$this->registration_service = new ChurchTools_Suite_Demo_Registration_Service( $this->demo_users_repo );
	}
	
	/**
	 * Register custom demo user role
	 */
	private function register_demo_role(): void {
		// Remove old role if exists (for capability updates)
		if ( get_role( 'cts_demo_user' ) ) {
			remove_role( 'cts_demo_user' );
		}
		
		// Create role with all necessary capabilities for plugin access
		add_role(
			'cts_demo_user',
			__( 'ChurchTools Demo User', 'churchtools-suite-demo' ),
			[
				'read' => true, // Basic WordPress read capability
				'cts_view_plugin' => true, // Custom capability to view plugin
				'manage_churchtools_suite' => true, // Access to ChurchTools Suite menu
				'manage_churchtools_calendars' => true, // View/manage calendars
				'manage_churchtools_events' => true, // View/manage events
			]
		);
	}
	
	/**
	 * Register shortcodes
	 */
	private function register_shortcodes(): void {
		$shortcodes = new ChurchTools_Suite_Demo_Shortcodes( $this->registration_service );
		$shortcodes->init();
	}
	
	/**
	 * Register admin pages
	 */
	private function register_admin(): void {
		$admin = new ChurchTools_Suite_Demo_Admin( $this->demo_users_repo );
		$admin->init();
	}
	
	/**
	 * Register cron jobs
	 */
	private function register_cron_jobs(): void {
		// Schedule daily cleanup if not already scheduled
		if ( ! wp_next_scheduled( 'cts_demo_cleanup' ) ) {
			wp_schedule_event( time(), 'daily', 'cts_demo_cleanup' );
		}

		// Schedule daily demo event seeding to ensure future events are present
		if ( ! wp_next_scheduled( 'cts_demo_seed_events' ) ) {
			wp_schedule_event( time(), 'daily', 'cts_demo_seed_events' );
		}
		
		// Register cleanup action
		add_action( 'cts_demo_cleanup', [ $this, 'run_cleanup' ] );

		// Register demo seeding action
		add_action( 'cts_demo_seed_events', [ $this, 'seed_demo_events' ] );
	}
	
	/**
	 * Run cleanup job
	 */
	public function run_cleanup(): void {
		// Delete unverified users older than 7 days
		$unverified_deleted = $this->demo_users_repo->delete_unverified_older_than( 7 );
		
		// Delete verified users older than 30 days
		$verified_deleted = $this->demo_users_repo->delete_verified_older_than( 30 );
		
		// Log cleanup
		if ( class_exists( 'ChurchTools_Suite_Logger' ) ) {
			ChurchTools_Suite_Logger::log( 'demo_cleanup', 'Cleanup completed', [
				'unverified_deleted' => $unverified_deleted,
				'verified_deleted' => $verified_deleted,
			] );
		}
	}

	/**
	 * Ensure demo events exist in the future (cron)
	 */
	public function seed_demo_events(): void {
		require_once CHURCHTOOLS_SUITE_DEMO_PATH . 'includes/class-churchtools-suite-demo-activator.php';
		$stats = ChurchTools_Suite_Demo_Activator::ensure_future_events();
		if ( class_exists( 'ChurchTools_Suite_Logger' ) ) {
			ChurchTools_Suite_Logger::log( 'demo_seed_events', 'Demo events ensured via cron', $stats );
		}
	}
	
	/**
	 * Handle email verification
	 */
	public function handle_verification(): void {
		if ( isset( $_GET['action'] ) && $_GET['action'] === 'cts_verify_demo_user' && isset( $_GET['token'] ) ) {
			$token = sanitize_text_field( wp_unslash( $_GET['token'] ) );
			
			$result = $this->registration_service->verify_email( $token );
			
			if ( is_wp_error( $result ) ) {
				wp_die(
					$result->get_error_message(),
					__( 'Verifizierung fehlgeschlagen', 'churchtools-suite-demo' ),
					[ 'back_link' => true ]
				);
			}
			
			// Auto-login
			$this->registration_service->auto_login( $result['wp_user_id'] );
			
			// Redirect to admin
			wp_safe_redirect( admin_url() );
			exit;
		}
	}
	
	/**
	 * Simulate calendar sync (AJAX intercept)
	 */
	public function simulate_calendar_sync(): void {
		check_ajax_referer( 'churchtools_suite_admin', 'nonce' );
		
		if ( ! current_user_can( 'manage_churchtools_suite' ) ) {
			wp_send_json_error( [ 'message' => 'Keine Berechtigung' ] );
		}
		
		// Simulate successful sync
		wp_send_json_success( [
			'message' => __( 'Demo-Modus: Kalender-Synchronisation simuliert', 'churchtools-suite-demo' ),
			'calendars_found' => 6,
			'calendars_created' => 0,
			'calendars_updated' => 0,
		] );
	}
	
	/**
	 * Simulate event sync (AJAX intercept)
	 */
	public function simulate_event_sync(): void {
		check_ajax_referer( 'churchtools_suite_admin', 'nonce' );
		
		if ( ! current_user_can( 'manage_churchtools_suite' ) ) {
			wp_send_json_error( [ 'message' => 'Keine Berechtigung' ] );
		}
		
		// Seed demo data on simulated sync
		require_once CHURCHTOOLS_SUITE_DEMO_PATH . 'includes/class-churchtools-suite-demo-activator.php';
		$seed_stats = ChurchTools_Suite_Demo_Activator::seed_demo_data_for_sync();
		
		// Count existing demo events
		global $wpdb;
		$table = $wpdb->prefix . 'cts_events';
		$count = $wpdb->get_var( "SELECT COUNT(*) FROM {$table} WHERE calendar_id IN ('1','2','3','4','5','6')" );
		
		// Simulate successful sync
		wp_send_json_success( [
			'message' => __( 'Demo-Modus: Event-Synchronisation simuliert', 'churchtools-suite-demo' ),
			'calendars_processed' => 6,
			'events_found' => (int) $count,
			'events_inserted' => $seed_stats['created'] ?? 0,
			'events_updated' => $seed_stats['updated'] ?? 0,
			'events_skipped' => 0,
			'services_imported' => 0,
		] );
	}
	
	/**
	 * Simulate connection test (AJAX intercept)
	 */
	public function simulate_connection_test(): void {
		check_ajax_referer( 'churchtools_suite_admin', 'nonce' );
		
		if ( ! current_user_can( 'manage_churchtools_suite' ) ) {
			wp_send_json_error( [ 'message' => 'Keine Berechtigung' ] );
		}
		
		// Simulate successful connection
		wp_send_json_success( [
			'message' => __( 'Demo-Modus: Verbindung simuliert (keine echte API-Verbindung)', 'churchtools-suite-demo' ),
		] );
	}
	
	/**
	 * Prevent calendar selection changes in demo mode
	 */
	public function prevent_calendar_changes(): void {
		check_ajax_referer( 'churchtools_suite_admin', 'nonce' );
		
		if ( ! current_user_can( 'manage_churchtools_calendars' ) ) {
			wp_send_json_error( [ 'message' => 'Keine Berechtigung' ] );
		}
		
		// Prevent changes in demo mode
		wp_send_json_error( [
			'message' => __( 'Demo-Modus: Kalenderauswahl kann nicht geändert werden', 'churchtools-suite-demo' ),
		] );
	}
	
	/**
	 * Provide demo events (filter hook)
	 *
	 * @param array $events  Original events
	 * @param array $filters Query filters
	 * @return array Demo events
	 */
	public function provide_demo_events( array $events, array $filters ): array {
		// Debug logging
		if ( defined( 'WP_DEBUG' ) && WP_DEBUG ) {
			error_log( 'ChurchTools Suite Demo: Filter called with ' . count( $events ) . ' input events' );
			error_log( 'ChurchTools Suite Demo: Filters: ' . print_r( $filters, true ) );
		}
		
		$demo_provider = new ChurchTools_Suite_Demo_Data_Provider();
		
		$demo_args = [
			'from' => $filters['from'] ?? date( 'Y-m-d H:i:s' ),
			'to' => $filters['to'] ?? date( 'Y-m-d H:i:s', strtotime( '+90 days' ) ),
			'limit' => $filters['limit'] ?? 20,
			'calendar_ids' => $filters['calendar_ids'] ?? [],
		];
		
		$demo_events = $demo_provider->get_events( $demo_args );
		
		// Format events for template compatibility
		$demo_events = array_map( [ $this, 'format_demo_event' ], $demo_events );
		
		// Debug logging
		if ( defined( 'WP_DEBUG' ) && WP_DEBUG ) {
			error_log( 'ChurchTools Suite Demo: Returning ' . count( $demo_events ) . ' demo events' );
		}
		
		return $demo_events;
	}
	
	/**
	 * Format demo event for template compatibility
	 *
	 * @param array $event Raw demo event
	 * @return array Formatted event
	 */
	private function format_demo_event( array $event ): array {
		// WordPress date/time formats
		$date_format = get_option( 'date_format', 'd.m.Y' );
		$time_format = get_option( 'time_format', 'H:i' );
		
		$start_timestamp = strtotime( $event['start_datetime'] );
		$end_timestamp = ! empty( $event['end_datetime'] ) ? strtotime( $event['end_datetime'] ) : null;
		
		// Add formatted date/time fields
		$event['start_date'] = date_i18n( $date_format, $start_timestamp );
		$event['start_time'] = date_i18n( $time_format, $start_timestamp );
		$event['start_day'] = date_i18n( 'd', $start_timestamp );
		$event['start_month'] = date_i18n( 'F', $start_timestamp );
		$event['start_month_short'] = date_i18n( 'M', $start_timestamp );
		$event['start_year'] = date_i18n( 'Y', $start_timestamp );
		$event['start_weekday'] = date_i18n( 'l', $start_timestamp );
		
		if ( $end_timestamp ) {
			$event['end_date'] = date_i18n( $date_format, $end_timestamp );
			$event['end_time'] = date_i18n( $time_format, $end_timestamp );
		} else {
			$event['end_date'] = '';
			$event['end_time'] = '';
		}
		
		// Calendar color (from demo calendars)
		$calendar_colors = [
			'1' => '#2563eb',
			'2' => '#16a34a',
			'3' => '#dc2626',
			'4' => '#9333ea',
			'5' => '#ea580c',
			'6' => '#0891b2',
		];
		$event['calendar_color'] = $calendar_colors[ $event['calendar_id'] ] ?? '#667eea';
		
		// Location (combine address fields if available)
		if ( ! empty( $event['address_city'] ) ) {
			$event['location'] = $event['address_city'];
		} else {
			$event['location'] = $event['location_name'] ?? '';
		}
		
		// Services (empty for demo events, could be extended later)
		$event['services'] = [];
		
		return $event;
	}
	
	/**
	 * Plugin activation
	 */
	public function activate(): void {
		// Create database tables
		$this->create_tables();
		
		// Register demo role
		$this->register_demo_role();
		
		// Create default demo user if it doesn't exist
		$this->create_demo_user();
		
		// Flush rewrite rules
		flush_rewrite_rules();
	}
	
	/**
	 * Plugin deactivation
	 */
	public function deactivate(): void {
		// Clear scheduled cron jobs
		$timestamp = wp_next_scheduled( 'cts_demo_cleanup' );
		if ( $timestamp ) {
			wp_unschedule_event( $timestamp, 'cts_demo_cleanup' );
		}
		
		// Flush rewrite rules
		flush_rewrite_rules();
	}
	
	/**
	 * Create default demo user if it doesn't exist
	 * 
	 * This user has cts_manager role to access the plugin backend
	 * 
	 * @since 1.0.2
	 */
	private function create_demo_user(): void {
		// Check if demo-user already exists
		$demo_user = get_user_by( 'login', 'demo-manager' );
		
		if ( $demo_user ) {
			return; // Demo user already exists
		}
		
		// Create new demo user with strong password
		$password = wp_generate_password( 16, true );
		
		$user_id = wp_create_user(
			'demo-manager',
			$password,
			'demo@example.com'
		);
		
		if ( is_wp_error( $user_id ) ) {
			// Log error if logging available
			if ( class_exists( 'ChurchTools_Suite_Logger' ) ) {
				ChurchTools_Suite_Logger::log(
					'demo_plugin',
					'Failed to create demo user',
					[ 'error' => $user_id->get_error_message() ]
				);
			}
			return;
		}
		
		// Assign cts_manager role (requires ChurchTools Suite to be active)
		$user = new WP_User( $user_id );
		if ( method_exists( $user, 'add_role' ) ) {
			$user->add_role( 'cts_manager' );
		}
		
		// Log success
		if ( class_exists( 'ChurchTools_Suite_Logger' ) ) {
			ChurchTools_Suite_Logger::log(
				'demo_plugin',
				'Demo user created on activation',
				[
					'user_id' => $user_id,
					'username' => 'demo-manager',
					'role' => 'cts_manager',
				]
			);
		}
	}
	
	/**
	 * Create database tables (v1.0.3.1: Fixed table name prefix and added columns)
	 */
	private function create_tables(): void {
		global $wpdb;
		
		$charset_collate = $wpdb->get_charset_collate();
		$table_name = $wpdb->prefix . 'cts_demo_users'; // wp_cts_demo_users
		
		// Migration: Rename old table if exists (v1.0.5.1)
		$old_table = $wpdb->prefix . 'demo_users';
		$table_exists = $wpdb->get_var( "SHOW TABLES LIKE '{$old_table}'" );
		if ( $table_exists ) {
			$wpdb->query( "RENAME TABLE {$old_table} TO {$table_name}" );
			
			if ( class_exists( 'ChurchTools_Suite_Logger' ) ) {
				ChurchTools_Suite_Logger::log(
					'demo_plugin',
					'Migrated old table name',
					[ 'from' => $old_table, 'to' => $table_name ]
				);
			}
		}
		
		$sql = "CREATE TABLE IF NOT EXISTS {$table_name} (
			id bigint(20) unsigned NOT NULL AUTO_INCREMENT,
			email varchar(255) NOT NULL,
			name varchar(255) NOT NULL,
			organization varchar(255) DEFAULT NULL,
			purpose text DEFAULT NULL,
			verification_token varchar(64) NOT NULL,
			is_verified tinyint(1) DEFAULT 0,
			verified_at datetime DEFAULT NULL,
			wordpress_user_id bigint(20) unsigned DEFAULT NULL,
			last_login_at datetime DEFAULT NULL,
			expires_at datetime DEFAULT NULL,
			created_at datetime DEFAULT CURRENT_TIMESTAMP,
			updated_at datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
			PRIMARY KEY  (id),
			UNIQUE KEY email (email),
			UNIQUE KEY verification_token (verification_token),
			KEY is_verified (is_verified),
			KEY verified_at (verified_at),
			KEY created_at (created_at),
			KEY wordpress_user_id (wordpress_user_id)
		) $charset_collate;";
		
		require_once ABSPATH . 'wp-admin/includes/upgrade.php';
		dbDelta( $sql );
		
		// Log table creation
		if ( class_exists( 'ChurchTools_Suite_Logger' ) ) {
			ChurchTools_Suite_Logger::log(
				'demo_plugin',
				'Demo tables created/verified',
				[ 'table' => $table_name ]
			);
		}
	}
}

// Initialize plugin
function churchtools_suite_demo() {
	return ChurchTools_Suite_Demo::instance();
}

// Start plugin
churchtools_suite_demo();

// Plugin activation/deactivation hooks (v1.0.4.0: Added demo event persistence)
register_activation_hook( __FILE__, function() {
	// Load Activator for demo events
	require_once CHURCHTOOLS_SUITE_DEMO_PATH . 'includes/class-churchtools-suite-demo-activator.php';
	
	// Run demo activator (creates demo events in database)
	ChurchTools_Suite_Demo_Activator::activate();
	
	// Run plugin activator (creates tables, users)
	churchtools_suite_demo()->activate();
} );

register_deactivation_hook( __FILE__, function() {
	// Load Activator for demo events
	require_once CHURCHTOOLS_SUITE_DEMO_PATH . 'includes/class-churchtools-suite-demo-activator.php';
	
	// Run demo deactivator (optional cleanup)
	ChurchTools_Suite_Demo_Activator::deactivate();
	
	// Run plugin deactivator (cleanup)
	churchtools_suite_demo()->deactivate();
} );
