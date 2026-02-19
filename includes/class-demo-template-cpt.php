<?php
/**
 * ChurchTools Suite Demo - Demo Pages (CPT)
 * 
 * Erlaubt Demo-Usern, ihre eigenen Test-Seiten zu erstellen
 * um Events und Shortcodes zu testen.
 * 
 * - Jeder Demo-User sieht nur seine eigenen Seiten
 * - Seiten sind PRIVAT (nicht öffentlich sichtbar)
 * - Beim User-Löschen werden auch seine Seiten gelöscht
 * 
 * NUR IM DEMO-PLUGIN verfügbar
 * 
 * @package ChurchTools_Suite_Demo
 * @since   1.0.7.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class ChurchTools_Suite_Demo_Template_CPT {
	
	/**
	 * Register Custom Post Type for Demo Pages
	 */
	public static function register(): void {
		// Debug: Check if we can access current_user_can
		error_log( 'ChurchTools Demo: CPT register() called' );
		
		$args = [
			'label'               => 'Demo Pages',
			'description'         => 'Test-Seiten zum Ausprobieren von Events und Shortcodes',
			'supports'            => [ 'title', 'editor', 'thumbnail', 'excerpt', 'custom-fields' ], // Gutenberg & Post Features
			'hierarchical'        => false,
			'public'              => true, // ✅ ÖFFENTLICH SICHTBAR - URLs generieren
			'publicly_queryable'  => true, // ✅ Frontend URLs aufrufbar
			'show_ui'             => true,
			'show_in_menu'        => true, // ✅ EIGENSTÄNDIGES Top-Level Menü
			'show_in_nav_menus'   => false,
			'show_in_admin_bar'   => true,
			'show_in_rest'        => true, // Gutenberg Editor Support!
			'menu_position'       => 65, // ✅ UNTER ChurchTools Suite (Position ~25), aber EIGENSTÄNDIG
			'menu_icon'           => 'dashicons-sticky', // ✅ ICON: Notiz
			'can_export'          => false,
			'has_archive'         => false,
			'exclude_from_search' => true, // NICHT in Suche
			'rewrite'             => [
				'slug'       => 'demo-pages', // URL-Slug: /demo-pages/{name}
				'with_front' => false,
				'hierarchical' => false,
			],
			'capability_type'     => 'cts_demo_page',
			'capabilities'        => [
				'create_posts'           => 'manage_cts_demo_pages',
				'read_private_posts'     => 'view_cts_demo_pages',
				'edit_post'              => 'edit_cts_demo_page',
				'edit_posts'             => 'edit_cts_demo_pages',
				'edit_published_posts'   => 'edit_published_cts_demo_pages',  // WICHTIG!
				'edit_others_posts'      => false,
				'edit_private_posts'     => 'manage_cts_demo_pages',
				'delete_post'            => 'delete_cts_demo_page',
				'delete_posts'           => 'delete_cts_demo_pages',
				'delete_published_posts' => 'delete_published_cts_demo_pages', // WICHTIG!
				'delete_others_posts'    => false,
				'delete_private_posts'   => 'delete_cts_demo_page',
				'publish_posts'          => 'publish_cts_demo_pages',
			],
			'map_meta_cap'        => true,
			'labels'              => [
				'name'                     => 'Demo Pages',
				'singular_name'            => 'Demo Page',
				'menu_name'                => 'Demo Pages',
				'all_items'                => 'Alle Demo Pages',
				'add_new'                  => 'Neue Demo Page',
				'add_new_item'             => 'Neue Demo Page erstellen',
				'edit_item'                => 'Demo Page bearbeiten',
				'new_item'                 => 'Neue Demo Page',
				'view_item'                => 'Demo Page anzeigen',
				'search_items'             => 'Demo Pages durchsuchen',
				'not_found'                => 'Keine Demo Pages gefunden',
				'not_found_in_trash'       => 'Keine Demo Pages im Papierkorb',
			],
		];
		
		$result = register_post_type( 'cts_demo_page', $args );
		
		// Debug
		if ( is_wp_error( $result ) ) {
			error_log( 'ChurchTools Demo: CPT register error - ' . $result->get_error_message() );
		} else {
			error_log( 'ChurchTools Demo: CPT registered successfully' );
		}
		
		// Flush rewrite rules on first registration
		if ( ! get_option( 'churchtools_suite_demo_pages_rewrite_flushed' ) ) {
			flush_rewrite_rules();
			update_option( 'churchtools_suite_demo_pages_rewrite_flushed', true );
		}
		
		// Filter query to show only user's own pages
		add_action( 'pre_get_posts', [ __CLASS__, 'filter_demo_pages_by_user' ] );
	}
	
	/**
	 * Filter demo pages - Demo Users see only their OWN pages
	 * 
	 * @param WP_Query $query Query object
	 */
	public static function filter_demo_pages_by_user( $query ): void {
		// Only in admin
		if ( ! is_admin() ) {
			return;
		}
		
		// Only for demo pages
		if ( $query->get( 'post_type' ) !== 'cts_demo_page' ) {
			return;
		}
		
		$user = wp_get_current_user();
		
		// Demo users see only their own pages
		if ( in_array( 'cts_demo_user', (array) $user->roles, true ) ) {
			$query->set( 'author', $user->ID );
		}
		// Admins see all (no filter)
	}
	
	/**
	 * Register capabilities for Demo Pages
	 */
	public static function add_capabilities(): void {
		$caps = [
			'manage_cts_demo_pages',
			'edit_cts_demo_page',
			'edit_cts_demo_pages',              // Plural - WICHTIG!
			'view_cts_demo_pages',
			'delete_cts_demo_page',
			'delete_cts_demo_pages',            // Plural - WICHTIG!
			'publish_cts_demo_pages',           // Veröffentlichen - WICHTIG!
			'edit_published_cts_demo_pages',    // Published bearbeiten - WICHTIG!
			'delete_published_cts_demo_pages',  // Published löschen - WICHTIG!
		];
		
		// Add to Administrator
		$admin = get_role( 'administrator' );
		if ( $admin ) {
			foreach ( $caps as $cap ) {
				$admin->add_cap( $cap );
			}
		}
		
		// Add to cts_demo_user (already added in register_demo_role, but ensure it)
		$demo_user = get_role( 'cts_demo_user' );
		if ( $demo_user ) {
			foreach ( $caps as $cap ) {
				$demo_user->add_cap( $cap );
			}
		}
	}
	
	/**
	 * Clean up demo pages when user is deleted
	 * 
	 * Hook this in the main plugin init
	 * 
	 * @param int $user_id User ID being deleted
	 */
	public static function delete_user_demo_pages( int $user_id ): void {
		global $wpdb;
		
		// Get all demo pages by this user
		$pages = get_posts( [
			'post_type'   => 'cts_demo_page',
			'author'      => $user_id,
			'numberposts' => -1,
			'fields'      => 'ids',
		] );
		
		// Force delete all pages (skip trash)
		foreach ( $pages as $page_id ) {
			wp_delete_post( $page_id, true );
		}
	}
}
