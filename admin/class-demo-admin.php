<?php
/**
 * Demo Admin Panel
 *
 * Admin interface for managing demo user registrations.
 * 
 * @package ChurchTools_Suite_Demo
 * @since   1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class ChurchTools_Suite_Demo_Admin {
	
	/**
	 * Demo Users Repository
	 *
	 * @var ChurchTools_Suite_Demo_Users_Repository
	 */
	private $repo;
	
	/**
	 * Constructor
	 *
	 * @param ChurchTools_Suite_Demo_Users_Repository $repo
	 */
	public function __construct( ChurchTools_Suite_Demo_Users_Repository $repo ) {
		$this->repo = $repo;
	}
	
	/**
	 * Initialize admin
	 */
	public function init(): void {
		// Add submenu to parent plugin
		add_action( 'admin_menu', [ $this, 'add_submenu' ], 20 );
		
		// Register AJAX handlers
		add_action( 'wp_ajax_cts_demo_delete_user', [ $this, 'ajax_delete_user' ] );
		add_action( 'wp_ajax_cts_demo_export_users', [ $this, 'ajax_export_users' ] );
	}
	
	/**
	 * Add submenu page
	 */
	public function add_submenu(): void {
		add_submenu_page(
			'churchtools-suite',
			__( 'Demo-Registrierungen', 'churchtools-suite-demo' ),
			__( 'Demo-Users', 'churchtools-suite-demo' ),
			'manage_churchtools_suite', // v1.0.3.1: Use same capability as main plugin
			'churchtools-suite-demo',
			[ $this, 'render_admin_page' ]
		);
	}
	
	/**
	 * Render admin page
	 */
	public function render_admin_page(): void {
		// Get statistics
		$stats = $this->repo->get_statistics();
		
		// Get users (paginated)
		$page = isset( $_GET['paged'] ) ? absint( $_GET['paged'] ) : 1;
		$per_page = 50;
		$offset = ( $page - 1 ) * $per_page;
		
		$users = $this->repo->get_paginated( [
			'limit' => $per_page,
			'offset' => $offset,
			'orderby' => 'created_at',
			'order' => 'DESC',
		] );
		
		include CHURCHTOOLS_SUITE_DEMO_PATH . 'admin/views/demo-users.php';
	}
	
	/**
	 * AJAX: Delete demo user
	 */
	public function ajax_delete_user(): void {
		check_ajax_referer( 'cts_demo_admin', 'nonce' );
		
		if ( ! current_user_can( 'manage_options' ) ) {
			wp_send_json_error( [ 'message' => 'Keine Berechtigung' ] );
		}
		
		$id = isset( $_POST['id'] ) ? absint( $_POST['id'] ) : 0;
		
		if ( ! $id ) {
			wp_send_json_error( [ 'message' => 'Ungültige ID' ] );
		}
		
		// Get user to delete WP user too
		$demo_user = $this->repo->get_by_id( $id );
		
		if ( ! $demo_user ) {
			wp_send_json_error( [ 'message' => 'Benutzer nicht gefunden' ] );
		}
		
		// Delete WP user if exists
		if ( $demo_user->wp_user_id ) {
			require_once ABSPATH . 'wp-admin/includes/user.php';
			wp_delete_user( $demo_user->wp_user_id );
		}
		
		// Delete demo user
		$this->repo->delete( $id );
		
		wp_send_json_success( [ 'message' => 'Benutzer gelöscht' ] );
	}
	
	/**
	 * AJAX: Export users to CSV
	 */
	public function ajax_export_users(): void {
		check_ajax_referer( 'cts_demo_admin', 'nonce' );
		
		if ( ! current_user_can( 'manage_options' ) ) {
			wp_die( 'Keine Berechtigung' );
		}
		
		$users = $this->repo->get_all();
		
		// Set headers
		header( 'Content-Type: text/csv; charset=utf-8' );
		header( 'Content-Disposition: attachment; filename=demo-users-' . date( 'Y-m-d' ) . '.csv' );
		
		// Output CSV
		$output = fopen( 'php://output', 'w' );
		
		// Header row
		fputcsv( $output, [ 'ID', 'Email', 'Name', 'Firma/Gemeinde', 'Zweck', 'Verifiziert', 'Letzter Login', 'Registriert am' ] );
		
		// Data rows
		foreach ( $users as $user ) {
			fputcsv( $output, [
				$user->id,
				$user->email,
				$user->name,
				$user->company,
				$user->purpose,
				$user->verified_at ? 'Ja' : 'Nein',
				$user->last_login_at ?: '-',
				$user->created_at,
			] );
		}
		
		fclose( $output );
		exit;
	}
}
