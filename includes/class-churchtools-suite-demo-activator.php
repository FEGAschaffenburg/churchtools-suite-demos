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
			wp_die( __( 'ChurchTools Suite Hauptplugin ist nicht aktiviert!', 'churchtools-suite-demo' ) );
		}
		
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
		// Optionally delete demo events
		// self::delete_demo_events();
		
		// Delete activation flag
		delete_option( self::ACTIVATION_FLAG );
		
		// Log
		if ( class_exists( 'ChurchTools_Suite_Logger' ) ) {
			ChurchTools_Suite_Logger::log(
				'demo_activator',
				'Demo plugin deactivated',
				[]
			);
		}
	}
	
	/**
	 * Create demo events in database
	 *
	 * Generates demo events for the next 90 days and writes them to the database.
	 * Uses Events Repository from main plugin for database persistence.
	 *
	 * @return array Statistics ['created' => count, 'skipped' => count]
	 */
	private static function create_demo_events(): array {
		// Check if events already created (idempotent)
		if ( get_option( self::ACTIVATION_FLAG ) ) {
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
