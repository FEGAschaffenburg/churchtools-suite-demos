<?php
/**
 * Demo Plugin Activator
 *
 * Handles demo data initialization on plugin activation.
 * Writes demo events directly to the database instead of generating them on-the-fly.
 *
 * @package ChurchTools_Suite_Demo
 * @since   1.0.4.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class ChurchTools_Suite_Demo_Activator {
	
	/**
	 * Option key for activation tracking
	 */
	const ACTIVATION_FLAG = 'churchtools_suite_demo_events_created';
	
	/**
	 * Activate plugin - Initialize demo data
	 *
	 * Called when demo plugin is activated.
	 * Creates demo events in the database.
	 */
	public static function activate(): void {
		// Check if main plugin is active
		if ( ! class_exists( 'ChurchTools_Suite' ) ) {
			wp_die( 'ChurchTools Suite Hauptplugin ist nicht aktiviert!' );
		}
		
		// Create demo calendars in database
		self::create_demo_calendars();

		// Create demo service groups and services
		self::create_demo_service_groups();
		self::create_demo_services();
		
		// Create demo events in database
		self::create_demo_events();
		
		// Mark as created
		update_option( self::ACTIVATION_FLAG, 1 );
		
		// Log
		if ( class_exists( 'ChurchTools_Suite_Logger' ) ) {
			ChurchTools_Suite_Logger::log(
				'demo_activator',
				'Demo plugin activated - events initialized in database',
				[]
			);
		}
	}
	
	/**
	 * Deactivate plugin - Optional cleanup
	 *
	 * Can optionally delete demo events when demo plugin is deactivated.
	 * Currently: KEEPS events (user may want to keep demo data)
	 * To enable deletion: Uncomment delete_demo_events() call below
	 */
	public static function deactivate(): void {
		// Remove all demo data from DB
		self::delete_demo_data();
		
		// Delete activation flag
		delete_option( self::ACTIVATION_FLAG );
		
		// Log
		if ( class_exists( 'ChurchTools_Suite_Logger' ) ) {
			ChurchTools_Suite_Logger::log(
				'demo_activator',
				'Demo plugin deactivated - demo data removed',
				[]
			);
		}
	}
	
	/**
	 * Create demo calendars in database
	 *
	 * Creates 6 demo calendars in the database.
	 * Uses Calendars Repository from main plugin.
	 *
	 * @return array Statistics ['created' => count]
	 */
	private static function create_demo_calendars(): array {
		// Load Calendars Repository from main plugin
		$calendars_repo_path = CHURCHTOOLS_SUITE_PATH . 'includes/repositories/class-churchtools-suite-calendars-repository.php';
		if ( ! file_exists( $calendars_repo_path ) ) {
			if ( class_exists( 'ChurchTools_Suite_Logger' ) ) {
				ChurchTools_Suite_Logger::error(
					'demo_activator',
					'Calendars Repository not found',
					[ 'path' => $calendars_repo_path ]
				);
			}
			return [ 'created' => 0 ];
		}
		
		require_once $calendars_repo_path;
		
		if ( ! class_exists( 'ChurchTools_Suite_Calendars_Repository' ) ) {
			return [ 'created' => 0 ];
		}
		
		$calendars_repo = new ChurchTools_Suite_Calendars_Repository();
		$created = 0;
		
		$demo_calendars = [
			[
				'calendar_id' => '1',
				'name' => 'Gottesdienste',
				'name_translated' => 'Gottesdienste',
				'color' => '#2563eb',
				'is_selected' => 1,
				'is_public' => 1,
			],
			[
				'calendar_id' => '2',
				'name' => 'Jugend',
				'name_translated' => 'Jugend',
				'color' => '#16a34a',
				'is_selected' => 1,
				'is_public' => 1,
			],
			[
				'calendar_id' => '3',
				'name' => 'Kinder',
				'name_translated' => 'Kinder',
				'color' => '#eab308',
				'is_selected' => 1,
				'is_public' => 1,
			],
			[
				'calendar_id' => '4',
				'name' => 'Musik',
				'name_translated' => 'Musik',
				'color' => '#dc2626',
				'is_selected' => 1,
				'is_public' => 1,
			],
			[
				'calendar_id' => '5',
				'name' => 'Kleingruppen',
				'name_translated' => 'Kleingruppen',
				'color' => '#9333ea',
				'is_selected' => 1,
				'is_public' => 1,
			],
			[
				'calendar_id' => '6',
				'name' => 'Gemeindeveranstaltungen',
				'name_translated' => 'Gemeindeveranstaltungen',
				'color' => '#0891b2',
				'is_selected' => 1,
				'is_public' => 1,
			],
		];
		
		foreach ( $demo_calendars as $calendar_data ) {
			$existing = $calendars_repo->get_by_calendar_id( $calendar_data['calendar_id'] );
			if ( ! $existing ) {
				$result = $calendars_repo->insert( $calendar_data );
				if ( $result ) {
					$created++;
				}
			}
		}
		
		if ( class_exists( 'ChurchTools_Suite_Logger' ) ) {
			ChurchTools_Suite_Logger::log(
				'demo_activator',
				'Demo calendars creation completed',
				[ 'created' => $created ]
			);
		}
		
		return [ 'created' => $created ];
	}

	/**
	 * Create demo service groups in database
	 *
	 * Uses Service Groups Repository from main plugin.
	 *
	 * @return array Statistics ['created' => count]
	 */
	private static function create_demo_service_groups(): array {
		$repo_path = CHURCHTOOLS_SUITE_PATH . 'includes/repositories/class-churchtools-suite-service-groups-repository.php';
		if ( ! file_exists( $repo_path ) ) {
			return [ 'created' => 0 ];
		}

		require_once $repo_path;

		if ( ! class_exists( 'ChurchTools_Suite_Service_Groups_Repository' ) ) {
			return [ 'created' => 0 ];
		}

		$groups_repo = new ChurchTools_Suite_Service_Groups_Repository();
		$created = 0;

		$demo_groups = [
			[
				'service_group_id' => 'sg_programm',
				'name' => 'Programm',
				'is_selected' => 1,
				'sort_order' => 10,
				'view_all' => 1,
			],
			[
				'service_group_id' => 'sg_musik',
				'name' => 'Musik',
				'is_selected' => 1,
				'sort_order' => 20,
				'view_all' => 1,
			],
			[
				'service_group_id' => 'sg_technik',
				'name' => 'Technik',
				'is_selected' => 1,
				'sort_order' => 30,
				'view_all' => 0,
			],
			[
				'service_group_id' => 'sg_kinder',
				'name' => 'Kinder',
				'is_selected' => 1,
				'sort_order' => 40,
				'view_all' => 0,
			],
		];

		foreach ( $demo_groups as $group ) {
			$result = $groups_repo->upsert( $group );
			if ( $result ) {
				$created++;
			}
		}

		return [ 'created' => $created ];
	}

	/**
	 * Create demo services in database
	 *
	 * Uses Services Repository from main plugin.
	 *
	 * @return array Statistics ['created' => count]
	 */
	private static function create_demo_services(): array {
		$repo_path = CHURCHTOOLS_SUITE_PATH . 'includes/repositories/class-churchtools-suite-services-repository.php';
		if ( ! file_exists( $repo_path ) ) {
			return [ 'created' => 0 ];
		}

		require_once $repo_path;

		if ( ! class_exists( 'ChurchTools_Suite_Services_Repository' ) ) {
			return [ 'created' => 0 ];
		}

		$services_repo = new ChurchTools_Suite_Services_Repository();
		$created = 0;

		$demo_services = [
			[ 'service_id' => 'svc_predigt', 'service_group_id' => 'sg_programm', 'name' => 'Prediger', 'name_translated' => 'Prediger', 'is_selected' => 1, 'sort_order' => 10 ],
			[ 'service_id' => 'svc_moderation', 'service_group_id' => 'sg_programm', 'name' => 'Moderation', 'name_translated' => 'Moderation', 'is_selected' => 1, 'sort_order' => 20 ],
			[ 'service_id' => 'svc_lobpreis', 'service_group_id' => 'sg_musik', 'name' => 'Lobpreis-Leitung', 'name_translated' => 'Lobpreis-Leitung', 'is_selected' => 1, 'sort_order' => 30 ],
			[ 'service_id' => 'svc_gesang', 'service_group_id' => 'sg_musik', 'name' => 'Gesang', 'name_translated' => 'Gesang', 'is_selected' => 1, 'sort_order' => 40 ],
			[ 'service_id' => 'svc_keyboard', 'service_group_id' => 'sg_musik', 'name' => 'Keyboard', 'name_translated' => 'Keyboard', 'is_selected' => 1, 'sort_order' => 50 ],
			[ 'service_id' => 'svc_gitarre', 'service_group_id' => 'sg_musik', 'name' => 'Gitarre', 'name_translated' => 'Gitarre', 'is_selected' => 1, 'sort_order' => 60 ],
			[ 'service_id' => 'svc_schlagzeug', 'service_group_id' => 'sg_musik', 'name' => 'Schlagzeug', 'name_translated' => 'Schlagzeug', 'is_selected' => 1, 'sort_order' => 70 ],
			[ 'service_id' => 'svc_technik', 'service_group_id' => 'sg_technik', 'name' => 'Technik', 'name_translated' => 'Technik', 'is_selected' => 1, 'sort_order' => 80 ],
			[ 'service_id' => 'svc_kinderbetreuung', 'service_group_id' => 'sg_kinder', 'name' => 'Kinderbetreuung', 'name_translated' => 'Kinderbetreuung', 'is_selected' => 1, 'sort_order' => 90 ],
		];

		foreach ( $demo_services as $service ) {
			$result = $services_repo->upsert( $service );
			if ( $result ) {
				$created++;
			}
		}

		return [ 'created' => $created ];
	}
	
	/**
	 * Create demo events in database
	 *
	 * Generates demo events for the next 90 days and writes them to the database.
	 * Uses Events Repository from main plugin for database persistence.
	 *
	 * @param bool $force Force creation even if activation flag exists
	 * @return array Statistics ['created' => count, 'skipped' => count]
	 */
	private static function create_demo_events( bool $force = false ): array {
		// Check if events already created (idempotent unless forced)
		if ( ! $force && get_option( self::ACTIVATION_FLAG ) ) {
			if ( class_exists( 'ChurchTools_Suite_Logger' ) ) {
				ChurchTools_Suite_Logger::log(
					'demo_activator',
					'Demo events already created - skipping',
					[]
				);
			}
			return [ 'created' => 0, 'skipped' => 0 ];
		}
		
		// Load Events Repository from main plugin
		$events_repo_path = CHURCHTOOLS_SUITE_PATH . 'includes/repositories/class-churchtools-suite-events-repository.php';
		if ( ! file_exists( $events_repo_path ) ) {
			if ( class_exists( 'ChurchTools_Suite_Logger' ) ) {
				ChurchTools_Suite_Logger::error(
					'demo_activator',
					'Events Repository not found',
					[ 'path' => $events_repo_path ]
				);
			}
			return [ 'created' => 0, 'skipped' => 0 ];
		}
		
		require_once $events_repo_path;
		
		if ( ! class_exists( 'ChurchTools_Suite_Events_Repository' ) ) {
			if ( class_exists( 'ChurchTools_Suite_Logger' ) ) {
				ChurchTools_Suite_Logger::error(
					'demo_activator',
					'Events Repository class not found',
					[]
				);
			}
			return [ 'created' => 0, 'skipped' => 0 ];
		}
		
		$events_repo = new ChurchTools_Suite_Events_Repository();
		$stats = [
			'created' => 0,
			'updated' => 0,
			'failed' => 0,
		];
		
		// Calculate date range (today to +90 days)
		$from = date( 'Y-m-d', current_time( 'timestamp' ) );
		$to = date( 'Y-m-d', current_time( 'timestamp' ) + 90 * DAY_IN_SECONDS );
		
		if ( class_exists( 'ChurchTools_Suite_Logger' ) ) {
			ChurchTools_Suite_Logger::log(
				'demo_activator',
				'Creating demo events',
				[ 'from' => $from, 'to' => $to ]
			);
		}
		
		// Generate and insert demo events
		$demo_events = self::generate_all_demo_events( $from, $to );
		
		foreach ( $demo_events as $event_data ) {
			// IMPORTANT: Use COMPOSITE KEY (appointment_id, start_datetime)
			// to prevent duplicate events on multiple activations
			$result = $events_repo->upsert_by_appointment_id( $event_data );
			
			if ( $result ) {
				// Check if newly created or updated
				$existing = $events_repo->get_by_id( $result );
				if ( $existing && $existing->created_at === $existing->updated_at ) {
					$stats['created']++;
				} else {
					$stats['updated']++;
				}
				
				// Assign demo services to this event
				self::assign_demo_services_to_event( $result, $event_data['calendar_id'] );
			} else {
				$stats['failed']++;
				if ( class_exists( 'ChurchTools_Suite_Logger' ) ) {
					ChurchTools_Suite_Logger::warning(
						'demo_activator',
						'Failed to create demo event',
						[
							'event_title' => $event_data['title'],
							'appointment_id' => $event_data['appointment_id'],
							'start_datetime' => $event_data['start_datetime'],
						]
					);
				}
			}
		}
		
		if ( class_exists( 'ChurchTools_Suite_Logger' ) ) {
			ChurchTools_Suite_Logger::log(
				'demo_activator',
				'Demo events creation completed',
				$stats
			);
		}
		
		return $stats;
	}
	
	/**
	 * Seed all demo data (calendars, services, events) – used for sync simulation
	 *
	 * @return array Statistics from event creation
	 */
	public static function seed_demo_data_for_sync(): array {
		self::create_demo_calendars();
		self::create_demo_service_groups();
		self::create_demo_services();
		return self::create_demo_events( true );
	}

	/**
	 * Delete all demo data (calendars, events, services, service groups)
	 */
	private static function delete_demo_data(): void {
		global $wpdb;
		$prefix = $wpdb->prefix . CHURCHTOOLS_SUITE_DB_PREFIX;

		$wpdb->query( "DELETE FROM {$prefix}event_services WHERE service_id LIKE 'svc_%'" );
		$wpdb->query( "DELETE FROM {$prefix}events WHERE appointment_id LIKE 'demo_%' OR event_id LIKE 'demo_%' OR calendar_id IN ('1','2','3','4','5','6')" );
		$wpdb->query( "DELETE FROM {$prefix}services WHERE service_id LIKE 'svc_%'" );
		$wpdb->query( "DELETE FROM {$prefix}service_groups WHERE service_group_id LIKE 'sg_%'" );
		$wpdb->query( "DELETE FROM {$prefix}calendars WHERE calendar_id IN ('1','2','3','4','5','6')" );

		if ( class_exists( 'ChurchTools_Suite_Logger' ) ) {
			ChurchTools_Suite_Logger::log(
				'demo_activator',
				'Deleted demo data',
				[]
			);
		}
	}

	/**
	 * Assign demo services to an event
	 * 
	 * Creates event_services entries for demo events based on calendar type.
	 * 
	 * @param int $event_id Local event ID
	 * @param string $calendar_id Calendar ID
	 * @return int Number of services assigned
	 */
	private static function assign_demo_services_to_event( int $event_id, string $calendar_id ): int {
		// Load Event Services Repository
		$repo_path = CHURCHTOOLS_SUITE_PATH . 'includes/repositories/class-churchtools-suite-event-services-repository.php';
		if ( ! file_exists( $repo_path ) ) {
			return 0;
		}
		
		require_once $repo_path;
		
		if ( ! class_exists( 'ChurchTools_Suite_Event_Services_Repository' ) ) {
			return 0;
		}
		
		$services_repo = new ChurchTools_Suite_Event_Services_Repository();
		
		// Delete existing services for this event (cleanup for re-seeding)
		$services_repo->delete_for_event( $event_id );
		
		// Define service assignments based on calendar type
		$service_assignments = [];
		
		switch ( $calendar_id ) {
			case '1': // Gottesdienste
				$service_assignments = [
					[ 'service_id' => 'svc_prediger', 'service_name' => 'Prediger', 'person_name' => 'Pastor Weber' ],
					[ 'service_id' => 'svc_moderation', 'service_name' => 'Moderation', 'person_name' => 'Anna Schmidt' ],
					[ 'service_id' => 'svc_lobpreis', 'service_name' => 'Lobpreis-Leitung', 'person_name' => 'Michael Becker' ],
					[ 'service_id' => 'svc_technik', 'service_name' => 'Technik', 'person_name' => 'Thomas Fischer' ],
				];
				break;
			
			case '2': // Jugend
				$service_assignments = [
					[ 'service_id' => 'svc_moderation', 'service_name' => 'Moderation', 'person_name' => 'Laura Wagner' ],
					[ 'service_id' => 'svc_musik', 'service_name' => 'Musik', 'person_name' => 'Daniel Richter' ],
				];
				break;
			
			case '3': // Kinder
				$service_assignments = [
					[ 'service_id' => 'svc_kinderbetreuung', 'service_name' => 'Kinderbetreuung', 'person_name' => 'Sophie Klein' ],
				];
				break;
			
			case '4': // Musik
				$service_assignments = [
					[ 'service_id' => 'svc_lobpreis', 'service_name' => 'Lobpreis-Leitung', 'person_name' => 'Julia Koch' ],
					[ 'service_id' => 'svc_keyboard', 'service_name' => 'Keyboard', 'person_name' => 'Sarah Weber' ],
					[ 'service_id' => 'svc_gitarre', 'service_name' => 'Gitarre', 'person_name' => 'Peter Mueller' ],
				];
				break;
			
			default:
				// No services for other calendars
				return 0;
		}
		
		// Insert services
		$assigned = 0;
		foreach ( $service_assignments as $service ) {
			$result = $services_repo->upsert( [
				'event_id' => $event_id,
				'service_id' => $service['service_id'],
				'service_name' => $service['service_name'],
				'person_name' => $service['person_name'],
			] );
			
			if ( $result ) {
				$assigned++;
			}
		}
		
		return $assigned;
	}

	/**
	 * Ensure a minimum number of future demo events exist
	 *
	 * Runs on a scheduled cron job to keep the demo calendar populated.
	 * Creates additional events up to the specified range if fewer than $min_future events exist.
	 *
	 * @param int $min_future_events Minimum events required in the future
	 * @param int $days_ahead        How many days ahead to seed events
	 * @return array Statistics including created/updated/failed counts
	 */
	public static function ensure_future_events( int $min_future_events = 30, int $days_ahead = 120 ): array {
		// Check dependencies
		if ( ! class_exists( 'ChurchTools_Suite' ) ) {
			return [ 'error' => 'main_plugin_missing' ];
		}

		$events_repo_path = CHURCHTOOLS_SUITE_PATH . 'includes/repositories/class-churchtools-suite-events-repository.php';
		if ( ! file_exists( $events_repo_path ) ) {
			return [ 'error' => 'events_repo_missing' ];
		}

		require_once $events_repo_path;
		if ( ! class_exists( 'ChurchTools_Suite_Events_Repository' ) ) {
			return [ 'error' => 'events_repo_class_missing' ];
		}

		$events_repo = new ChurchTools_Suite_Events_Repository();
		$table = $events_repo->get_table_name();
		$demo_calendar_ids = [ '1', '2', '3', '4', '5', '6' ];
		
		global $wpdb;
		$placeholders = implode( ',', array_fill( 0, count( $demo_calendar_ids ), '%s' ) );
		$now = current_time( 'mysql' );
		
		// Count existing future demo events
		$count_sql = "SELECT COUNT(*) FROM {$table} WHERE calendar_id IN ({$placeholders}) AND start_datetime >= %s";
		$count_prepared = $wpdb->prepare( $count_sql, array_merge( $demo_calendar_ids, [ $now ] ) );
		$existing_future = (int) $wpdb->get_var( $count_prepared );

		if ( $existing_future >= $min_future_events ) {
			if ( class_exists( 'ChurchTools_Suite_Logger' ) ) {
				ChurchTools_Suite_Logger::log(
					'demo_activator',
					'Future demo events already sufficient',
					[
						'existing_future_events' => $existing_future,
						'min_required' => $min_future_events,
					]
				);
			}
			return [
				'created' => 0,
				'updated' => 0,
				'failed' => 0,
				'existing_future_events' => $existing_future,
			];
		}

		// Seed additional events up to the specified range
		$from = date( 'Y-m-d', current_time( 'timestamp' ) );
		$to = date( 'Y-m-d', current_time( 'timestamp' ) + $days_ahead * DAY_IN_SECONDS );

		$demo_events = self::generate_all_demo_events( $from, $to );
		// Only keep future instances
		$demo_events = array_filter( $demo_events, function( $event ) use ( $now ) {
			return strtotime( $event['start_datetime'] ) >= strtotime( $now );
		} );

		$stats = [ 'created' => 0, 'updated' => 0, 'failed' => 0 ];

		foreach ( $demo_events as $event_data ) {
			$result = $events_repo->upsert_by_appointment_id( $event_data );
			if ( $result ) {
				$existing = $events_repo->get_by_id( $result );
				if ( $existing && $existing->created_at === $existing->updated_at ) {
					$stats['created']++;
				} else {
					$stats['updated']++;
				}
			} else {
				$stats['failed']++;
			}
		}

		// Recount to report final state
		$final_count = (int) $wpdb->get_var( $count_prepared );

		if ( class_exists( 'ChurchTools_Suite_Logger' ) ) {
			ChurchTools_Suite_Logger::log(
				'demo_activator',
				'Ensured future demo events',
				array_merge( $stats, [
					'existing_future_events' => $existing_future,
					'final_future_events' => $final_count,
					'min_required' => $min_future_events,
					'days_seeded' => $days_ahead,
				] )
			);
		}

		return array_merge( $stats, [
			'existing_future_events' => $existing_future,
			'final_future_events' => $final_count,
		] );
	}
	
	/**
	 * Delete demo events from database
	 *
	 * Removes all demo events (calendar_id 1-6) from database.
	 * Called on plugin deactivation (if enabled).
	 *
	 * @return int Number of deleted events
	 */
	private static function delete_demo_events(): int {
		global $wpdb;
		
		$prefix = $wpdb->prefix . CHURCHTOOLS_SUITE_DB_PREFIX;
		$table = $prefix . 'events';
		
		// Delete all events with demo calendar IDs (1-6)
		$deleted = $wpdb->query(
			$wpdb->prepare(
				"DELETE FROM {$table} WHERE calendar_id IN (%s, %s, %s, %s, %s, %s)",
				'1', '2', '3', '4', '5', '6'
			)
		);
		
		if ( class_exists( 'ChurchTools_Suite_Logger' ) ) {
			ChurchTools_Suite_Logger::log(
				'demo_activator',
				'Demo events deleted',
				[ 'deleted_count' => $deleted ]
			);
		}
		
		return $deleted;
	}
	
	/**
	 * Generate all demo events
	 *
	 * Creates recurring and special demo events.
	 *
	 * @param string $from Start date (Y-m-d)
	 * @param string $to End date (Y-m-d)
	 * @return array Array of event data arrays
	 */
	private static function generate_all_demo_events( string $from, string $to ): array {
		$events = [];
		
		// Weekly recurring events
		$weekly_events = self::generate_weekly_events( $from, $to );
		$events = array_merge( $events, $weekly_events );
		
		// Special one-time events
		$special_events = self::generate_special_events( $from, $to );
		$events = array_merge( $events, $special_events );
		
		return $events;
	}
	
	/**
	 * Generate weekly recurring events
	 *
	 * Creates events like Gottesdienst (Sundays), Jugendabend (Fridays), etc.
	 *
	 * @param string $from Start date
	 * @param string $to End date
	 * @return array
	 */
	private static function generate_weekly_events( string $from, string $to ): array {
		$events = [];
		
		// Define weekly recurring events
		$recurring_events = [
			[
				'title' => 'Gottesdienst',
				'calendar_id' => '1',
				'day_of_week' => 'Sunday',
				'start_time' => '10:00',
				'end_time' => '11:30',
				'location' => 'Hauptgottesdienst',
			],
			[
				'title' => 'Jugendabend',
				'calendar_id' => '2',
				'day_of_week' => 'Friday',
				'start_time' => '19:00',
				'end_time' => '21:00',
				'location' => 'Jugendraum',
			],
			[
				'title' => 'Kindergottesdienst',
				'calendar_id' => '3',
				'day_of_week' => 'Sunday',
				'start_time' => '10:00',
				'end_time' => '11:00',
				'location' => 'Kindergruppe',
			],
			[
				'title' => 'Lobpreis-Probe',
				'calendar_id' => '4',
				'day_of_week' => 'Thursday',
				'start_time' => '20:00',
				'end_time' => '21:30',
				'location' => 'Musikraum',
			],
			[
				'title' => 'Hauskreis',
				'calendar_id' => '5',
				'day_of_week' => 'Wednesday',
				'start_time' => '19:30',
				'end_time' => '21:30',
				'location' => 'Wechselnde Orte',
			],
		];
		
		// Generate event instances
		foreach ( $recurring_events as $event_def ) {
			$event_instances = self::generate_weekly_event_instances(
				$event_def['title'],
				$event_def['calendar_id'],
				$event_def['day_of_week'],
				$event_def['start_time'],
				$event_def['end_time'],
				$event_def['location'],
				$from,
				$to
			);
			$events = array_merge( $events, $event_instances );
		}
		
		return $events;
	}
	
	/**
	 * Generate instances of a weekly recurring event
	 *
	 * @param string $title Event title
	 * @param string $calendar_id Calendar ID
	 * @param string $day_of_week Day name
	 * @param string $start_time Start time (HH:MM)
	 * @param string $end_time End time (HH:MM)
	 * @param string $location Location name
	 * @param string $from Start date
	 * @param string $to End date
	 * @return array
	 */
	private static function generate_weekly_event_instances(
		string $title,
		string $calendar_id,
		string $day_of_week,
		string $start_time,
		string $end_time,
		string $location,
		string $from,
		string $to
	): array {
		$events = [];
		$from_ts = strtotime( $from );
		$current = strtotime( "next $day_of_week", $from_ts - DAY_IN_SECONDS );
		$end = strtotime( $to . ' 23:59:59' );
		
		// Generate unique appointment_id for this recurring series
		// (Same appointment_id for all instances of same recurring event)
		$appointment_id = 'demo_' . sanitize_title( $title );
		
		$counter = 0;
		while ( $current <= $end ) {
			// Create unique composite key: appointment_id + start_datetime
			$start_datetime = date( 'Y-m-d', $current ) . ' ' . $start_time . ':00';
			$end_datetime = date( 'Y-m-d', $current ) . ' ' . $end_time . ':00';
			
			$events[] = [
				'event_id' => null, // No external event_id for demo
				'calendar_id' => $calendar_id,
				'appointment_id' => $appointment_id, // Same for all instances
				'title' => $title,
				'description' => null,
				'event_description' => self::get_event_description( $title ),
				'appointment_description' => '',
				'start_datetime' => $start_datetime,
				'end_datetime' => $end_datetime,
				'is_all_day' => 0,
				'location_name' => $location,
				'address_name' => 'Aschaffenburg',
				'address_street' => 'Hauptstraße 123',
				'address_zip' => '63739',
				'address_city' => 'Aschaffenburg',
				'address_latitude' => 49.9745,
				'address_longitude' => 9.1501,
				'tags' => wp_json_encode( self::get_event_tags( $title ) ),
				'status' => 'active',
				'raw_payload' => wp_json_encode( [
					'id' => $appointment_id,
					'name' => $title,
					'note' => self::get_event_description( $title ),
					'location' => $location,
					'startDate' => $start_datetime,
					'endDate' => $end_datetime,
				] ),
			];
			
			$current = strtotime( '+1 week', $current );
		}
		
		return $events;
	}
	
	/**
	 * Generate special one-time events
	 *
	 * @param string $from Start date
	 * @param string $to End date
	 * @return array
	 */
	private static function generate_special_events( string $from, string $to ): array {
		$events = [];
		$start = strtotime( $from );
		
		// Gemeindefest (in 30 days)
		$fest_date = $start + 30 * DAY_IN_SECONDS;
		if ( $fest_date <= strtotime( $to ) ) {
			$events[] = [
				'event_id' => null,
				'calendar_id' => '6',
				'appointment_id' => 'demo_gemeindefest',
				'title' => 'Gemeindefest',
				'description' => null,
				'event_description' => 'Großes Gemeindefest mit Gottesdienst, Essen, Spielen und Musik für die ganze Familie.',
				'appointment_description' => '',
				'start_datetime' => date( 'Y-m-d', $fest_date ) . ' 11:00:00',
				'end_datetime' => date( 'Y-m-d', $fest_date ) . ' 17:00:00',
				'is_all_day' => 0,
				'location_name' => 'Gemeindezentrum',
				'address_name' => 'Gemeindezentrum',
				'address_street' => 'Hauptstraße 123',
				'address_zip' => '63739',
				'address_city' => 'Aschaffenburg',
				'address_latitude' => 49.9745,
				'address_longitude' => 9.1501,
				'tags' => wp_json_encode( [
					[ 'name' => 'Highlight', 'color' => '#dc2626' ],
					[ 'name' => 'Familie', 'color' => '#16a34a' ],
				] ),
				'status' => 'active',
				'raw_payload' => wp_json_encode( [
					'id' => 'demo_gemeindefest',
					'name' => 'Gemeindefest',
					'note' => 'Großes Gemeindefest mit Gottesdienst, Essen, Spielen und Musik für die ganze Familie.',
					'location' => 'Gemeindezentrum',
					'startDate' => date( 'Y-m-d', $fest_date ) . ' 11:00:00',
					'endDate' => date( 'Y-m-d', $fest_date ) . ' 17:00:00',
				] ),
			];
		}
		
		// Alpha-Kurs Start (in 14 days)
		$alpha_date = $start + 14 * DAY_IN_SECONDS;
		if ( $alpha_date <= strtotime( $to ) ) {
			$events[] = [
				'event_id' => null,
				'calendar_id' => '6',
				'appointment_id' => 'demo_alphakurs',
				'title' => 'Alpha-Kurs: Startabend',
				'description' => null,
				'event_description' => 'Der Alpha-Kurs ist eine Entdeckungsreise zum christlichen Glauben. Für alle, die Fragen haben.',
				'appointment_description' => '',
				'start_datetime' => date( 'Y-m-d', $alpha_date ) . ' 19:00:00',
				'end_datetime' => date( 'Y-m-d', $alpha_date ) . ' 21:30:00',
				'is_all_day' => 0,
				'location_name' => 'Gemeindezentrum',
				'address_name' => 'Gemeindezentrum',
				'address_street' => 'Hauptstraße 123',
				'address_zip' => '63739',
				'address_city' => 'Aschaffenburg',
				'address_latitude' => 49.9745,
				'address_longitude' => 9.1501,
				'tags' => wp_json_encode( [
					[ 'name' => 'Alpha-Kurs', 'color' => '#2563eb' ],
					[ 'name' => 'Gäste willkommen', 'color' => '#16a34a' ],
				] ),
				'status' => 'active',
				'raw_payload' => wp_json_encode( [
					'id' => 'demo_alphakurs',
					'name' => 'Alpha-Kurs: Startabend',
					'note' => 'Der Alpha-Kurs ist eine Entdeckungsreise zum christlichen Glauben. Für alle, die Fragen haben.',
					'location' => 'Gemeindezentrum',
					'startDate' => date( 'Y-m-d', $alpha_date ) . ' 19:00:00',
					'endDate' => date( 'Y-m-d', $alpha_date ) . ' 21:30:00',
				] ),
			];
		}
		
		return $events;
	}
	
	/**
	 * Get event description based on title
	 *
	 * @param string $title Event title
	 * @return string Description
	 */
	private static function get_event_description( string $title ): string {
		$descriptions = [
			'Gottesdienst' => 'Sonntagsgottesdienst mit Lobpreis, Predigt und Gemeinschaft.',
			'Jugendabend' => 'Gemeinsamer Abend für Jugendliche mit Programm und Austausch.',
			'Kindergottesdienst' => 'Altersgerechter Gottesdienst für Kinder mit Spielen und Lernen.',
			'Lobpreis-Probe' => 'Probe für die Lobpreisband - Musikalische Vorbereitung.',
			'Hauskreis' => 'Kleine Gruppe zum Austausch über Glaubensfragen und Gebet.',
		];
		
		return $descriptions[ $title ] ?? 'Gemeindeveranstaltung';
	}
	
	/**
	 * Get tags for event based on title
	 *
	 * @param string $title Event title
	 * @return array Array of tag objects
	 */
	private static function get_event_tags( string $title ): array {
		$tags = [
			'Gottesdienst' => [
				[ 'name' => 'Sonntagsgottesdienst', 'color' => '#dc2626' ],
				[ 'name' => 'Alle willkommen', 'color' => '#16a34a' ],
			],
			'Jugendabend' => [
				[ 'name' => 'Jugend', 'color' => '#3b82f6' ],
				[ 'name' => 'Gemeinschaft', 'color' => '#a855f7' ],
			],
			'Kindergottesdienst' => [
				[ 'name' => 'Kinder', 'color' => '#f59e0b' ],
				[ 'name' => 'Familien', 'color' => '#16a34a' ],
			],
			'Lobpreis-Probe' => [
				[ 'name' => 'Musik', 'color' => '#8b5cf6' ],
				[ 'name' => 'Band', 'color' => '#06b6d4' ],
			],
			'Hauskreis' => [
				[ 'name' => 'Kleingruppe', 'color' => '#06b6d4' ],
				[ 'name' => 'Vertiefung', 'color' => '#2563eb' ],
			],
		];
		
		return $tags[ $title ] ?? [];
	}
}

