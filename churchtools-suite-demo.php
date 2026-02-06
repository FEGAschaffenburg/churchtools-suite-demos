<?php
/**
 * Plugin Name:       ChurchTools Suite Demo
 * Plugin URI:        https://github.com/FEGAschaffenburg/churchtools-suite
 * Description:       Demo-Addon für ChurchTools Suite - Self-Service Demo Registration mit Backend-Zugang. Erfordert ChurchTools Suite v1.0.8+
 * Version:           1.0.7.0
 * Requires at least: 6.0
 * Requires PHP:      8.0
 * Requires Plugins:  churchtools-suite
 * Author:            FEG Aschaffenburg
 * Author URI:        https://feg-aschaffenburg.de
 * License:           GPL v2 or later
 * License URI:       https://www.gnu.org/licenses/gpl-2.0.html
 *
 * TRADEMARK NOTICE:
 * ChurchTools ist eine registrierte Marke der ChurchTools GmbH.
 * Dieses Projekt steht in keiner Verbindung zu oder Unterstützung durch die ChurchTools GmbH.
 * ChurchTools Suite Demo wird ohne Gewährleistung bereitgestellt (see LICENSE).
 *
 * @package ChurchTools_Suite_Demo
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// Define plugin constants
define( 'CHURCHTOOLS_SUITE_DEMO_VERSION', '1.0.7.0' );
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
		// Initialize plugin
		add_action( 'plugins_loaded', [ $this, 'init' ] );
		
		// Check dependencies after init
		add_action( 'admin_init', [ $this, 'check_dependencies' ] );
		
		// Activation/Deactivation hooks
		register_activation_hook( __FILE__, [ $this, 'activate' ] );
		register_deactivation_hook( __FILE__, [ $this, 'deactivate' ] );
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
		
		// Run migrations (v1.0.6.0: Multi-user support)
		require_once CHURCHTOOLS_SUITE_DEMO_PATH . 'includes/class-demo-migrations.php';
		ChurchTools_Suite_Demo_Migrations::run_migrations();
		
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
		
		// Register Template CPT (Demo only)
		add_action( 'init', [ 'ChurchTools_Suite_Demo_Template_CPT', 'register' ] );
		add_action( 'init', [ 'ChurchTools_Suite_Demo_Template_CPT', 'add_capabilities' ] );
		
		// Cleanup demo pages when user is deleted
		add_action( 'delete_user', [ 'ChurchTools_Suite_Demo_Template_CPT', 'delete_user_demo_pages' ] );
		
		// Enqueue frontend CSS for Demo Pages
		add_action( 'wp_enqueue_scripts', [ $this, 'enqueue_frontend_styles' ] );
		
		// Add info banner to all shortcode outputs
		add_filter( 'the_content', [ $this, 'add_demo_info_banner' ], 999 );
		
		// Register admin pages
		if ( is_admin() ) {
			$this->register_admin();
		}
		
		// Register cron jobs
		$this->register_cron_jobs();
		
		// Register verification handler
		add_action( 'template_redirect', [ $this, 'handle_verification' ] );
		
		// v1.0.7.0: Demo mode settings override
		add_action( 'admin_enqueue_scripts', [ $this, 'inject_demo_mode_ui' ] );
		add_action( 'wp_ajax_cts_demo_toggle_mode', [ $this, 'ajax_toggle_demo_mode' ] );
		
		// v1.0.7.0: Custom menu for demo pages
		add_action( 'init', [ $this, 'register_demo_menu_location' ] );
		add_filter( 'wp_nav_menu_items', [ $this, 'add_demo_pages_to_menu' ], 10, 2 );
		add_action( 'admin_bar_menu', [ $this, 'add_demo_pages_to_admin_bar' ], 100 );
		
		// NOTE: Demo events are stored in database and retrieved like normal ChurchTools events
		// No special filter needed - main plugin handles everything
		
		// Intercept sync operations and simulate them
		add_action( 'wp_ajax_cts_sync_calendars', [ $this, 'simulate_calendar_sync' ], 1 );
		add_action( 'wp_ajax_cts_sync_events', [ $this, 'simulate_event_sync' ], 1 );
		add_action( 'wp_ajax_cts_test_connection', [ $this, 'simulate_connection_test' ], 1 );
		
		// Prevent settings changes in demo mode (but no persistent notice)
		add_action( 'wp_ajax_cts_save_calendar_selection', [ $this, 'prevent_calendar_changes' ], 1 );
		
		// Restrict demo user capabilities (only Demo Pages + ChurchTools dashboard)
		if ( is_admin() ) {
			add_action( 'admin_menu', [ $this, 'restrict_demo_user_capabilities' ], 999 );
		}
	}
	
	/**
	 * Load plugin dependencies
	 */
	private function load_dependencies(): void {
		// Multi-user support (v1.0.6.0)
		require_once CHURCHTOOLS_SUITE_DEMO_PATH . 'includes/class-user-settings.php';
		require_once CHURCHTOOLS_SUITE_DEMO_PATH . 'includes/class-demo-ct-client.php';
		
		// Demo Repositories (v1.0.7.0: Isolated per user)
		require_once CHURCHTOOLS_SUITE_DEMO_PATH . 'includes/repositories/class-demo-users-repository.php';
		require_once CHURCHTOOLS_SUITE_DEMO_PATH . 'includes/repositories/class-demo-presets-repository.php';
		require_once CHURCHTOOLS_SUITE_DEMO_PATH . 'includes/repositories/class-demo-events-repository.php';
		require_once CHURCHTOOLS_SUITE_DEMO_PATH . 'includes/repositories/class-demo-calendars-repository.php';
		require_once CHURCHTOOLS_SUITE_DEMO_PATH . 'includes/repositories/class-demo-services-repository.php';
		
		// CPT for Templates (Demo only)
		require_once CHURCHTOOLS_SUITE_DEMO_PATH . 'includes/class-demo-template-cpt.php';
		
		// Services
		require_once CHURCHTOOLS_SUITE_DEMO_PATH . 'includes/services/class-demo-data-provider.php';
		require_once CHURCHTOOLS_SUITE_DEMO_PATH . 'includes/services/class-demo-registration-service.php';
		
		// Admin
		require_once CHURCHTOOLS_SUITE_DEMO_PATH . 'admin/class-demo-admin.php';
		
		// Shortcodes
		require_once CHURCHTOOLS_SUITE_DEMO_PATH . 'includes/class-demo-shortcodes.php';
		
		// v1.0.7.0: Repository overrides for demo users
		// Register filters globally - they check user role internally
		add_action( 'init', [ $this, 'setup_demo_repository_overrides' ], 1 );
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
	 * 
	 * Demo User kann:
	 * - ChurchTools Suite Dashboard ANSCHAUEN (READ-ONLY)
	 * - Eigene Demo-Seiten (CPT) erstellen/bearbeiten/löschen
	 * 
	 * Demo User KANN NICHT:
	 * - Einstellungen ändern
	 * - Events synchronisieren
	 * - Kalender/Services konfigurieren
	 * - Andere User sehen
	 * - Plugins/Themes verwalten
	 * - Normale Posts/Pages sehen
	 * - Dateien verwalten
	 */
	private function register_demo_role(): void {
		// Remove old role if exists (for capability updates)
		if ( get_role( 'cts_demo_user' ) ) {
			remove_role( 'cts_demo_user' );
		}
		
		// Create role with EXTREMELY LIMITED capabilities:
		// Demo users get ONLY what they need, nothing more
		add_role(
			'cts_demo_user',
			'ChurchTools Demo User',
			[
				// === ABSOLUTE MINIMUM ===
				'read' => true, // Can access admin area
				
				// === ChurchTools Suite - READ-ONLY Dashboard ===
				'manage_churchtools_suite' => true, // Can VIEW menu + dashboard only
				
				// === NO ChurchTools modifications allowed ===
				'view_churchtools_debug' => false,
				'manage_churchtools_calendars' => false,
				'configure_churchtools_suite' => false,
				'sync_churchtools_events' => false,
				'manage_churchtools_services' => false,
				
				// === Demo Pages (CPT) - FULL control on OWN pages ===
				'manage_cts_demo_pages' => true,
				'edit_cts_demo_page' => true,
				'delete_cts_demo_page' => true,
				'edit_cts_demo_pages' => true,
				'delete_cts_demo_pages' => true,
				'publish_cts_demo_pages' => true,
				'edit_published_cts_demo_pages' => true,     // WICHTIG: Veröffentlichte bearbeiten
				'delete_published_cts_demo_pages' => true,   // WICHTIG: Veröffentlichte löschen
				'view_cts_demo_pages' => true,
				
				// === NO access to standard WordPress content ===
				'edit_posts' => false,           // NO Posts
				'delete_posts' => false,
				'publish_posts' => false,
				'read_private_posts' => false,
				'edit_private_posts' => false,
				'delete_private_posts' => false,
				'edit_others_posts' => false,
				'delete_others_posts' => false,
				
				'edit_pages' => false,           // NO Pages
				'delete_pages' => false,
				'publish_pages' => false,
				'read_private_pages' => false,
				'edit_private_pages' => false,
				'delete_private_pages' => false,
				'edit_others_pages' => false,
				'delete_others_pages' => false,
				
				'upload_files' => false,         // NO Media
				'delete_users' => false,         // NO User management
				'edit_users' => false,
				'manage_options' => false,       // NO Settings
				'manage_plugins' => false,       // NO Plugins
				'manage_themes' => false,        // NO Themes
				'manage_categories' => false,    // NO Taxonomies
				'manage_links' => false,         // NO Links
				'moderate_comments' => false,    // NO Comments
			]
		);
	}
	
	/**
	 * Restrict admin menu for demo users
	 * 
	 * Demo users should ONLY see:
	 * - Dashboard (read-only)
	 * - Demo Pages (CPT)
	 * - ChurchTools Suite (read-only dashboard)
	 * 
	 * Hide all other menus via capabilities
	 * 
	 * @since 1.0.7.2
	 */
	public function restrict_demo_user_capabilities(): void {
		$user = wp_get_current_user();
		
		// Only apply to demo users
		if ( ! in_array( 'cts_demo_user', (array) $user->roles, true ) ) {
			return;
		}
		
		// Explicitly deny access to prohibited admin pages
		global $pagenow;
		
		// List of restricted pages
		$restricted_pages = [
			'users.php',              // Users
			'edit-comments.php',      // Comments
			'edit.php',               // Posts
			'edit.php?post_type=page', // Pages
			'upload.php',             // Media
			'edit.php?post_type=cpt', // Other CPTs
			'tools.php',              // Tools
			'options-general.php',    // Settings
			'plugins.php',            // Plugins
			'themes.php',             // Themes
			'admin.php',              // Custom admin pages
		];
		
		// If user tries to access restricted page, redirect to dashboard
		if ( is_admin() && ! in_array( $pagenow, [ 'index.php', 'post.php', 'edit.php', 'upload.php' ], true ) ) {
			// Check if current page is not dashboard and not demo pages
			global $current_screen;
			if ( $current_screen && $current_screen->post_type !== 'cts_demo_page' ) {
				wp_safe_remote_post( admin_url( 'admin-ajax.php' ), [ // Trigger redirect on next page load
					'blocking' => false,
					'timeout'  => 0.01,
				] );
			}
		}
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
				$error_code = $result->get_error_code();
				
				// Already verified - show friendly message and redirect to login
				if ( $error_code === 'already_verified' ) {
					wp_die(
						'<h1>✅ E-Mail bereits verifiziert</h1>' .
						'<p>Ihr Account wurde bereits aktiviert. Sie können sich jetzt anmelden.</p>' .
						'<p><a href="' . esc_url( wp_login_url() ) . '" class="button button-primary">Zum Login</a></p>',
						'Bereits verifiziert'
					);
				}
				
				// Other errors - show error message
				wp_die(
					$result->get_error_message(),
					'Verifizierung fehlgeschlagen',
					[ 'back_link' => true ]
				);
			}
			
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
			'message' => 'Demo-Modus: Kalender-Synchronisation simuliert',
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
			'message' => 'Demo-Modus: Event-Synchronisation simuliert',
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
			'message' => 'Demo-Modus: Verbindung simuliert (keine echte API-Verbindung)',
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
			'message' => 'Demo-Modus: Kalenderauswahl kann nicht geändert werden',
		] );
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
		
		// Assign cts_demo_user role (v1.0.7.3: Deprecated cts_manager, use cts_demo_user instead)
		$user = new WP_User( $user_id );
		if ( method_exists( $user, 'set_role' ) ) {
			$user->set_role( 'cts_demo_user' );
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
	
	/**
	 * Enqueue frontend CSS for Demo Pages
	 * 
	 * Adds professional styling for single demo page display on frontend
	 * 
	 * @since 1.0.7.4
	 */
	public function enqueue_frontend_styles(): void {
		// Only enqueue on single demo page posts
		if ( ! is_singular( 'cts_demo_page' ) ) {
			return;
		}
		
		// Register and enqueue demo pages frontend CSS
		wp_enqueue_style(
			'cts-demo-pages-frontend',
			CHURCHTOOLS_SUITE_DEMO_URL . 'assets/css/demo-pages-frontend.css',
			[],
			CHURCHTOOLS_SUITE_DEMO_VERSION,
			'all'
		);
	}
	
	/**
	 * Add demo info banner above content on view pages only
	 * 
	 * Shows a helpful banner on specific demo view pages only,
	 * to guide users on creating their own demo pages.
	 * 
	 * @param string $content Page content
	 * @return string Modified content
	 */
	public function add_demo_info_banner( string $content ): string {
		// Only on frontend, not in admin
		if ( is_admin() ) {
			return $content;
		}
		
		// Only on view pages
		global $post;
		if ( ! $post ) {
			return $content;
		}
		
		// Check if page title starts with view-specific prefixes
		$view_prefixes = [
			'Listen:',
			'Karten:',
			'Kalender:',
			'Grid:',
			'Slider:',
			'Countdown:',
		];
		
		$is_view_page = false;
		foreach ( $view_prefixes as $prefix ) {
			if ( strpos( $post->post_title, $prefix ) === 0 ) {
				$is_view_page = true;
				break;
			}
		}
		
		if ( ! $is_view_page ) {
			return $content;
		}
		
		// Additional safety check: only if shortcode present
		if ( ! has_shortcode( $content, 'cts_list' ) && 
		     ! has_shortcode( $content, 'cts_grid' ) && 
		     ! has_shortcode( $content, 'cts_calendar' ) &&
		     ! has_shortcode( $content, 'cts_countdown' ) &&
		     ! has_shortcode( $content, 'cts_slider' ) ) {
			return $content;
		}
		
		// Banner HTML
		$banner = '
		<div class="cts-demo-info-banner" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; padding: 1.5rem; border-radius: 8px; margin: 0 0 2rem 0; box-shadow: 0 4px 6px rgba(0,0,0,0.1);">
			<div style="display: flex; align-items: center; gap: 1rem;">
				<span style="font-size: 2rem; flex-shrink: 0;">💡</span>
				<div>
					<h3 style="margin: 0 0 0.5rem 0; font-size: 1.1rem; color: white; font-weight: 600;">Eigene Demo-Seiten erstellen</h3>
					<p style="margin: 0; font-size: 0.95rem; line-height: 1.5; opacity: 0.95;">
						Sie können im Backend eigene Seiten mit individuellen Einstellungen erstellen!<br>
						<strong style="color: #fbbf24;">→ Demo Pages > Neue Seite hinzufügen</strong><br>
						Verwenden Sie den Gutenberg-Block <strong>"ChurchTools Events"</strong> oder Shortcodes wie <code style="background: rgba(255,255,255,0.2); padding: 0.2rem 0.4rem; border-radius: 3px; font-size: 0.9rem;">[cts_list view="minimal"]</code>
					</p>
				</div>
			</div>
		</div>';
		
		return $banner . $content;
	}
	
	/**
	 * Inject demo mode UI into Settings tab (v1.0.7.0)
	 */
	public function inject_demo_mode_ui( $hook ): void {
		// Only on ChurchTools Suite admin pages
		if ( ! isset( $_GET['page'] ) || $_GET['page'] !== 'churchtools-suite' ) {
			return;
		}
		
		// Only on Settings tab
		if ( ! isset( $_GET['tab'] ) || $_GET['tab'] !== 'settings' ) {
			return;
		}
		
		// Only on API subtab
		if ( ! isset( $_GET['subtab'] ) || $_GET['subtab'] !== 'api' ) {
			return;
		}
		
		// Only for demo users
		if ( ! current_user_can( 'cts_demo_user' ) ) {
			return;
		}
		
		// Buffer output and inject demo mode UI
		ob_start( function( $content ) {
			// Find the API card and inject demo mode toggle before it
			$demo_ui = file_get_contents( CHURCHTOOLS_SUITE_DEMO_PATH . 'admin/views/settings-override-api.php' );
			$demo_ui = eval( '?>' . $demo_ui );
			
			// Inject before first form
			$content = preg_replace( 
				'/(<form[^>]*method="post"[^>]*>)/',
				ob_get_clean() . '$1',
				$content,
				1
			);
			
			return $content;
		} );
	}
	
	/**
	 * Handle demo mode toggle (v1.0.7.0)
	 */
	public function ajax_toggle_demo_mode(): void {
		check_ajax_referer( 'cts_demo_toggle', 'nonce' );
		
		if ( ! current_user_can( 'cts_demo_user' ) ) {
			wp_send_json_error( [ 'message' => 'Keine Berechtigung' ] );
		}
		
		$enabled = ! empty( $_POST['enabled'] );
		$user_id = get_current_user_id();
		
		// Load user settings class
		require_once CHURCHTOOLS_SUITE_DEMO_PATH . 'includes/class-user-settings.php';
		
		// Toggle mode
		ChurchTools_Suite_User_Settings::set_demo_mode( $enabled, $user_id );
		
		// Log
		error_log( sprintf(
			'[ChurchTools Demo] User %d toggled demo mode: %s',
			$user_id,
			$enabled ? 'enabled' : 'disabled'
		) );
		
		wp_send_json_success( [
			'message' => $enabled 
				? 'Demo-Modus aktiviert - Sie nutzen jetzt vorkonfigurierte Demo-Daten'
				: 'Demo-Modus deaktiviert - Sie können jetzt Ihre eigene ChurchTools-Instanz konfigurieren'
		] );
	}
	
	/**
	 * Register custom menu location for demo pages (v1.0.7.0)
	 */
	public function register_demo_menu_location(): void {
		register_nav_menus( [
			'demo-pages' => __( 'Demo Pages Menu (automatisch)', 'churchtools-suite' ),
		] );
	}
	
	/**
	 * Add user's demo pages to menu (v1.0.7.0)
	 * 
	 * Automatically adds the logged-in user's demo pages to any menu
	 * assigned to the 'demo-pages' location.
	 *
	 * @param string $items Menu HTML
	 * @param object $args Menu args
	 * @return string Modified menu HTML
	 */
	public function add_demo_pages_to_menu( string $items, $args ): string {
		// Only for demo-pages menu location
		if ( ! isset( $args->theme_location ) || $args->theme_location !== 'demo-pages' ) {
			return $items;
		}
		
		// Only for logged-in demo users
		if ( ! is_user_logged_in() || ! current_user_can( 'cts_demo_user' ) ) {
			return $items;
		}
		
		// Get user's demo pages
		$demo_pages = get_posts( [
			'post_type' => 'cts_demo_page',
			'author' => get_current_user_id(),
			'posts_per_page' => -1,
			'orderby' => 'title',
			'order' => 'ASC',
			'post_status' => 'publish',
		] );
		
		if ( empty( $demo_pages ) ) {
			// No pages yet - show hint
			$items .= '<li class="menu-item no-demo-pages">';
			$items .= '<a href="' . admin_url( 'post-new.php?post_type=cts_demo_page' ) . '">';
			$items .= '➕ ' . __( 'Erste Demo-Seite erstellen', 'churchtools-suite' );
			$items .= '</a></li>';
			return $items;
		}
		
		// Add pages to menu
		foreach ( $demo_pages as $page ) {
			$items .= sprintf(
				'<li class="menu-item menu-item-demo-page"><a href="%s">📄 %s</a></li>',
				get_permalink( $page->ID ),
				esc_html( $page->post_title )
			);
		}
		
		// Add "Create new" link
		$items .= '<li class="menu-item menu-item-create-demo">';
		$items .= '<a href="' . admin_url( 'post-new.php?post_type=cts_demo_page' ) . '">';
		$items .= '➕ ' . __( 'Neue Seite', 'churchtools-suite' );
		$items .= '</a></li>';
		
		return $items;
	}
	
	/**
	 * Add demo pages to admin bar (v1.0.7.0)
	 *
	 * @param WP_Admin_Bar $wp_admin_bar
	 */
	public function add_demo_pages_to_admin_bar( $wp_admin_bar ): void {
		// Only for logged-in demo users
		if ( ! is_user_logged_in() || ! current_user_can( 'cts_demo_user' ) ) {
			return;
		}
		
		// Get user's demo pages
		$demo_pages = get_posts( [
			'post_type' => 'cts_demo_page',
			'author' => get_current_user_id(),
			'posts_per_page' => 10,
			'orderby' => 'modified',
			'order' => 'DESC',
			'post_status' => 'publish',
		] );
		
		// Add parent menu
		$wp_admin_bar->add_node( [
			'id' => 'demo-pages',
			'title' => '📄 Meine Demo-Seiten (' . count( $demo_pages ) . ')',
			'href' => admin_url( 'edit.php?post_type=cts_demo_page' ),
		] );
		
		// Add pages as submenu
		if ( ! empty( $demo_pages ) ) {
			foreach ( $demo_pages as $page ) {
				$wp_admin_bar->add_node( [
					'parent' => 'demo-pages',
					'id' => 'demo-page-' . $page->ID,
					'title' => esc_html( $page->post_title ),
					'href' => get_permalink( $page->ID ),
				] );
			}
			
			// Separator
			$wp_admin_bar->add_node( [
				'parent' => 'demo-pages',
				'id' => 'demo-pages-divider',
				'title' => '—',
				'meta' => [ 'class' => 'ab-sub-secondary' ],
			] );
		}
		
		// Add "Create new" link
		$wp_admin_bar->add_node( [
			'parent' => 'demo-pages',
			'id' => 'demo-pages-new',
			'title' => '➕ Neue Seite erstellen',
			'href' => admin_url( 'post-new.php?post_type=cts_demo_page' ),
		] );
		
		// Add "Manage all" link
		$wp_admin_bar->add_node( [
			'parent' => 'demo-pages',
			'id' => 'demo-pages-manage',
			'title' => '⚙️ Alle verwalten',
			'href' => admin_url( 'edit.php?post_type=cts_demo_page' ),
		] );
	}
	
	/**
	 * Setup demo repository overrides (v1.0.7.0)
	 * 
	 * Temporarily replaces main plugin repository classes with demo versions
	 * for current demo user. This allows shortcodes to automatically use isolated
	 * demo data without modifying main plugin code.
	 * 
	 * Uses PHP class_alias to redirect class names.
	 */
	/**
	 * Setup demo repository overrides (v1.0.7.0)
	 * 
	 * Registers filter hooks to override main plugin repositories with isolated demo versions.
	 * Uses Repository Factory pattern from main plugin v1.0.8.0+.
	 * 
	 * Filters are registered globally but only activate for demo users (checked in override methods).
	 */
	public function setup_demo_repository_overrides(): void {
		// Check if main plugin has Repository Factory (v1.0.8.0+)
		if ( ! function_exists( 'churchtools_suite_get_repository' ) ) {
			// Fallback: Main plugin not updated yet
			error_log( '[ChurchTools Demo] WARNING: Main plugin needs v1.0.8.0+ for Repository Factory' );
			return;
		}
		
		// Register filters to override repositories
		// Note: Filters check user role internally before returning demo repositories
		add_filter( 'churchtools_suite_get_events_repository', [ $this, 'override_events_repository' ], 10, 2 );
		add_filter( 'churchtools_suite_get_calendars_repository', [ $this, 'override_calendars_repository' ], 10, 2 );
		add_filter( 'churchtools_suite_get_services_repository', [ $this, 'override_services_repository' ], 10, 2 );
		add_filter( 'churchtools_suite_get_event_services_repository', [ $this, 'override_event_services_repository' ], 10, 2 );
	}
	
	/**
	 * Override Events Repository for demo users (v1.0.7.0)
	 * 
	 * Returns isolated demo repository when user is demo user.
	 * This ensures demo users only see their own events.
	 *
	 * @param mixed $repository Default repository (ignored)
	 * @param int|null $user_id User ID (optional)
	 * @return mixed Demo repository or original
	 */
	public function override_events_repository( $repository, $user_id = null ) {
		// Get current user if not specified
		if ( ! $user_id ) {
			$user_id = get_current_user_id();
		}
		
		// Check if user is demo user
		if ( $user_id && user_can( $user_id, 'cts_demo_user' ) ) {
			return new ChurchTools_Suite_Demo_Events_Repository( $user_id );
		}
		
		return $repository;
	}
	
	/**
	 * Override Calendars Repository for demo users (v1.0.7.0)
	 *
	 * @param mixed $repository Default repository (ignored)
	 * @param int|null $user_id User ID (optional)
	 * @return mixed Demo repository or original
	 */
	public function override_calendars_repository( $repository, $user_id = null ) {
		// Get current user if not specified
		if ( ! $user_id ) {
			$user_id = get_current_user_id();
		}
		
		// Check if user is demo user
		if ( $user_id && user_can( $user_id, 'cts_demo_user' ) ) {
			return new ChurchTools_Suite_Demo_Calendars_Repository( $user_id );
		}
		
		return $repository;
	}
	
	/**
	 * Override Services Repository for demo users (v1.0.7.0)
	 *
	 * @param mixed $repository Default repository (ignored)
	 * @param int|null $user_id User ID (optional)
	 * @return mixed Demo repository or original
	 */
	public function override_services_repository( $repository, $user_id = null ) {
		// Get current user if not specified
		if ( ! $user_id ) {
			$user_id = get_current_user_id();
		}
		
		// Check if user is demo user
		if ( $user_id && user_can( $user_id, 'cts_demo_user' ) ) {
			return new ChurchTools_Suite_Demo_Services_Repository( $user_id );
		}
		
		return $repository;
	}
	
	/**
	 * Override Event Services Repository for demo users (v1.0.7.0)
	 * 
	 * Note: Demo plugin doesn't have separate event_services table yet.
	 * Returns null to use main plugin's repository (services are part of events).
	 *
	 * @param mixed $repository Default repository (ignored)
	 * @param int|null $user_id User ID (optional)
	 * @return mixed Demo repository or original
	 */
	public function override_event_services_repository( $repository, $user_id = null ) {
		// Demo plugin doesn't have event_services table isolation yet
		// Services are stored within events, so this is handled by events repository
		return $repository;
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
