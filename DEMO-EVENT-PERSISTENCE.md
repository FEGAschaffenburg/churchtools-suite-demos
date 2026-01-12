# ChurchTools Suite Demo Plugin - v1.0.4.0 Release Notes

## Major Feature: Event Persistence to Database

### Overview

The Demo Plugin has been refactored to store demo events **directly in the database** instead of generating them on-the-fly on every request.

### What Changed

**Before (v1.0.3.x):**
- Demo events were generated dynamically each time they were requested
- No persistence between page loads
- Events existed only in memory
- Inefficient for large numbers of events

**After (v1.0.4.0):**
- Demo events are written to the database during **plugin activation**
- Events are stored in the main `wp_cts_events` table (reusing existing schema)
- Events are queried from the database, like real ChurchTools events
- Much more efficient and realistic simulation
- Persistent demo data across page loads and sessions

### Implementation Details

#### 1. New Class: `ChurchTools_Suite_Demo_Activator`

**File:** `includes/class-churchtools-suite-demo-activator.php`

**Responsibilities:**
- Called on plugin activation via `register_activation_hook()`
- Creates demo events in the database using the Events Repository
- Generates weekly recurring events (Gottesdienst, Jugendabend, etc.)
- Generates special one-time events (Gemeindefest, Alpha-Kurs)
- Uses **COMPOSITE KEY** (appointment_id + start_datetime) to prevent duplicates on re-activation
- Logs all operations via ChurchTools Logger

**Key Methods:**
- `activate()` - Main activation entry point
- `deactivate()` - Optional deactivation cleanup
- `create_demo_events()` - Writes events to database
- `generate_all_demo_events()` - Generates event data for database
- `generate_weekly_event_instances()` - Creates recurring event instances
- `generate_special_events()` - Creates one-time special events

#### 2. Updated: `ChurchTools_Suite_Demo_Data_Provider`

**File:** `includes/services/class-demo-data-provider.php`

**Changes:**
- New `get_events_from_database()` method queries Events Repository
- Modified `get_events()` to:
  1. Try loading from database first (if Events Repository available)
  2. Fall back to on-the-fly generation if database not initialized
  3. Ensures backwards compatibility
- New `generate_events_fallback()` - Preserves original generation logic

**Backwards Compatibility:**
- If database is not initialized (Events Repository not available), falls back to original on-the-fly generation
- Existing code that relies on generation still works
- No breaking changes to the API

#### 3. Updated: Plugin Initialization

**File:** `churchtools-suite-demo.php`

**Changes:**
- Updated `register_activation_hook()` to call new `ChurchTools_Suite_Demo_Activator::activate()`
- Updated `register_deactivation_hook()` to call new `ChurchTools_Suite_Demo_Activator::deactivate()`
- Loads Activator class dynamically before calling methods
- Version bumped from v1.0.3.1 to v1.0.4.0

### Database Schema

Demo events are stored in the existing **`wp_cts_events`** table with the following characteristics:

**Relevant Fields:**
- `calendar_id`: Demo calendars use IDs 1-6
- `appointment_id`: Unique identifier per recurring series (e.g., 'demo_gottesdienst')
- `start_datetime` / `end_datetime`: Event timing
- `title`: Event name
- `event_description` / `appointment_description`: Event descriptions
- `address_name`, `address_street`, `address_city`, etc.: Location details
- `tags`: JSON-encoded array of tags
- `status`: Always 'active' for demo events

**Unique Constraint (Composite Key):**
The table uses a **COMPOSITE UNIQUE KEY** on `(appointment_id, start_datetime)`:
- Same appointment_id can appear multiple times with different start_datetime values (recurring events)
- Prevents duplicate event instances on re-activation
- Ensures each instance of a recurring event is unique

### Demo Events Created

**Weekly Recurring Events:**
1. **Gottesdienst** (Sundays, 10:00-11:30)
   - Calendar: 1 (blue, #2563eb)
   - Appointment ID: demo_gottesdienst

2. **Jugendabend** (Fridays, 19:00-21:00)
   - Calendar: 2 (green, #16a34a)
   - Appointment ID: demo_jugendabend

3. **Kindergottesdienst** (Sundays, 10:00-11:00)
   - Calendar: 3 (yellow, #eab308)
   - Appointment ID: demo_kindergottesdienst

4. **Lobpreis-Probe** (Thursdays, 20:00-21:30)
   - Calendar: 4 (red, #dc2626)
   - Appointment ID: demo_lobpreis_probe

5. **Hauskreis** (Wednesdays, 19:30-21:30)
   - Calendar: 5 (orange, #ea580c)
   - Appointment ID: demo_hauskreis

**Special Events:**
1. **Gemeindefest** (30 days from today, 11:00-17:00)
   - Calendar: 6 (cyan, #0891b2)
   - Appointment ID: demo_gemeindefest
   - Tags: Highlight, Familie

2. **Alpha-Kurs: Startabend** (14 days from today, 19:00-21:30)
   - Calendar: 6 (cyan, #0891b2)
   - Appointment ID: demo_alphakurs
   - Tags: Alpha-Kurs, Gäste willkommen

**Event Range:**
- Generated from today to +90 days
- Covers next 13 weeks of recurring events
- Location: Aschaffenburg (Hauptstraße 123, 63739)
- GPS: 49.9745, 9.1501

### Activation Process

#### When Demo Plugin is Activated:

1. **Activator loads**
   ```php
   register_activation_hook( __FILE__, function() {
       require_once CHURCHTOOLS_SUITE_DEMO_PATH . 'includes/class-churchtools-suite-demo-activator.php';
       ChurchTools_Suite_Demo_Activator::activate();
       churchtools_suite_demo()->activate();
   });
   ```

2. **Check idempotency**
   - Checks if events already created (via `churchtools_suite_demo_events_created` option)
   - Skips creation if already done (safe for re-activation)

3. **Load Events Repository**
   - Requires `ChurchTools_Suite_Events_Repository` from main plugin
   - Fails gracefully if main plugin not available

4. **Generate event data**
   - Creates 90 days of weekly events (60-70 instances)
   - Creates 2 special events
   - All formatted for database storage

5. **Write to database**
   - Uses `upsert_by_appointment_id()` for COMPOSITE KEY handling
   - Each instance gets unique (appointment_id, start_datetime) combination
   - Logs each insertion/update

6. **Set activation flag**
   - `update_option('churchtools_suite_demo_events_created', 1)`
   - Prevents duplicate creation on subsequent activations

### Deactivation Process

#### When Demo Plugin is Deactivated:

1. **Activator calls deactivate()**
2. **Optional cleanup** (currently disabled)
   - Could delete demo events from database
   - Currently: KEEPS events (preserves user data)
   - Can be enabled by uncommenting `self::delete_demo_events()`

3. **Clear activation flag**
   - `delete_option('churchtools_suite_demo_events_created')`

### Event Flow in Frontend

1. **Plugin activation** → Demo events written to database
2. **User visits page** → Events query in AJAX modal
3. **Demo Data Provider** → Queries database for demo events
4. **Events Repository** → Returns database records
5. **Frontend template** → Displays events (same as real events)

### Backwards Compatibility

**Fallback Generation:**
If Events Repository is not available (e.g., main plugin older version):
```php
$db_events = $this->get_events_from_database( $args );
if ( ! empty( $db_events ) ) {
    return $db_events; // Use database events
}
// Fallback: Generate on-the-fly (v1.0.3.x behavior)
return $this->generate_events_fallback( $args );
```

### Logging

All operations are logged via `ChurchTools_Suite_Logger`:

**Activation Logs:**
```
demo_activator | Demo plugin activated - events initialized in database
demo_activator | Creating demo events (from: 2025-01-12, to: 2025-04-12)
demo_activator | Demo events creation completed (created: 72, updated: 0, failed: 0)
```

**Event Query Logs:**
```
Demo Provider: Loaded 72 events from database
Demo Provider: Returning 72 events from database
```

### Testing & Validation

**To test the new feature:**

1. **Activate Demo Plugin**
   - Check logs: `admin → ChurchTools Suite → Advanced → Logs`
   - Should see "Demo plugin activated - events initialized"

2. **Verify Events in Database**
   - Check `wp_cts_events` table
   - Should have 70-75 rows with calendar_id in (1-6)
   - All should have appointment_id starting with "demo_"

3. **Test Frontend**
   - Open Events modal on frontend
   - Should display demo events
   - All 6 demo calendars should have events

4. **Test Deactivation**
   - Deactivate demo plugin
   - Check database: Events remain (not deleted)
   - Check option: `churchtools_suite_demo_events_created` should be deleted

5. **Test Re-activation**
   - Re-activate demo plugin
   - No duplicate events created
   - Events updated with new dates (if activated on different day)

### Migration Notes

**From v1.0.3.x to v1.0.4.0:**

- No user action required
- On first activation of v1.0.4.0:
  - New demo events written to database
  - Demo Data Provider automatically uses database events
  - Old on-the-fly generation still works as fallback
- Existing databases: No schema changes needed (reuses `wp_cts_events`)

### Performance Improvements

**Benefits of database persistence:**

1. **Faster Rendering:** Events queried once, cached in database
2. **More Realistic:** Behaves like real ChurchTools sync
3. **Scalability:** Handles large numbers of events efficiently
4. **Filtering:** Database supports calendar_id filtering natively
5. **Sorting:** Events pre-sorted by start_datetime

### Known Limitations

1. **Demo events never expire:**
   - Once created, they stay in database
   - User can manually delete via SQL or admin
   - Optional: Enable deactivation cleanup to auto-delete

2. **No recurring event logic:**
   - Each instance created separately (not recurring rule)
   - More realistic for demo purposes
   - Future: Could add iCal export for recurring events

3. **Fixed times:**
   - Times are hardcoded (Sunday 10:00, Friday 19:00, etc.)
   - Could be extended to allow customization

### Future Enhancements

**Potential improvements:**
- Allow customization of demo event times/dates
- Add UI to manage demo events in admin
- Auto-regenerate events monthly/quarterly
- Support for custom recurring patterns
- Export demo events as iCal file

---

## Version History

- **v1.0.4.0** (2025-01-12): Event persistence to database - demo events now stored in DB instead of generated on-the-fly
- **v1.0.3.1** (2025-01-10): Admin menu fix, capability consistency
- **v1.0.3.0** (2025-01-08): Initial release with demo registration system

## Compatibility

- **Requires:** ChurchTools Suite v1.0.0+
- **PHP:** 8.0+
- **WordPress:** 6.0+
- **Database:** `wp_cts_events` table (shared with main plugin)
