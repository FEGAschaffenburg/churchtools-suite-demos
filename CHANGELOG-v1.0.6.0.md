# Changelog - ChurchTools Suite Demo

## [1.0.6.0] - 2026-02-05

### 🎯 Major Feature: Multi-User Backend Isolation

**Jeder Demo-Tester erhält jetzt seine eigene isolierte Umgebung!**

#### Added
- **Multi-User Settings System** (`class-user-settings.php`)
  - User-spezifische Einstellungen via WordPress `usermeta`
  - Fallback zu globalen Einstellungen für Backwards Compatibility
  - `get()`, `set()`, `delete()`, `get_all()` Methoden
  - Auto-Migration von globalen zu user-spezifischen Settings

- **Database Migrations** (`class-demo-migrations.php`)
  - Migration 1.1: Adds `user_id` column to `plugin_cts_views` table
  - Migration 1.1: Adds `user_id` column to `plugin_cts_shortcode_presets` table
  - Automatic assignment of existing data to admin user
  - System presets remain with `user_id = NULL` (shared)

- **User-Aware Repositories**
  - `class-demo-presets-repository.php`: Shortcode-Presets mit user_id Filterung
  - Ownership verification für CRUD-Operationen
  - System-Presets (is_system=1) bleiben für alle sichtbar
  - User-Presets nur für eigenen User sichtbar

- **User-Aware ChurchTools Client** (`class-demo-ct-client.php`)
  - Wrapper für Hauptplugin-CT-Client
  - Lädt user-spezifische ChurchTools-Verbindung (URL, Username, Password, Cookies)
  - Filter-basierte Setting-Override-Mechanik
  - Transparent für bestehenden Code

- **Auto-Setup bei Registrierung** (`initialize_user_settings()`)
  - Neue Demo-User bekommen automatisch Default-Einstellungen:
    - ChurchTools URL: `https://demo.church.tools`
    - Sync Intervall: 7 Tage zurück, 90 Tage vorwärts
    - Auto-Sync: Deaktiviert (Demo-User testen manuell)
  - Keine Konfiguration notwendig nach Registrierung

#### Changed
- **Version Bump**: `1.0.7.4` → `1.0.6.0` (Clean Slate für Multi-User Feature)
- **Plugin Dependencies**: Lädt neue User-Settings und CT-Client Klassen beim Init
- **Migration Runner**: Wird bei jedem `init` ausgeführt (nur pending migrations)

#### Technical Details
- **Database Schema**: 
  - `plugin_cts_views.user_id` (bigint, NULL = system view)
  - `plugin_cts_shortcode_presets.user_id` (bigint, NULL = system preset)
  - Indizes auf `user_id` für Performance

- **Settings Storage**:
  - BEFORE: `wp_options` (global)
  - AFTER: `wp_usermeta` (per-user)
  - Prefix: `churchtools_suite_*`

- **Isolation Scope**:
  ✅ ChurchTools Connection (URL, Username, Password, Cookies)
  ✅ Sync Settings (Days Past/Future, Auto-Sync Interval)
  ✅ Shortcode Presets (User-Custom)
  ✅ Views (Future: when implemented in main plugin)
  ⚠️ Events/Calendars: Shared (Demo-Daten)
  ⚠️ Services: Shared (Demo-Daten)

#### Testing
- Test-Script: `test-multiuser-isolation.php`
- Erstellt 2 Test-User mit unterschiedlichen Settings
- Verifiziert vollständige Isolation
- Test-User Credentials:
  - `demo_test_user1` / `TestPass123!`
  - `demo_test_user2` / `TestPass456!`

#### Migration Notes
- Existing installations: Migration runs automatically on plugin init
- Existing views: Assigned to admin user (first administrator found)
- Existing user presets: Assigned to admin user
- System presets: Remain with `user_id = NULL`
- No data loss - graceful fallback to global settings

#### Breaking Changes
⚠️ **None** - Fully backwards compatible
- Existing installations continue working without changes
- Settings fallback ensures no disruption
- Admin user inherits all existing data

### Performance
- Minimal overhead: Single user_meta query per setting access
- Indexed columns for fast filtering
- No impact on existing single-user installations

### Security
- User ownership verification on all CRUD operations
- System presets protected (cannot be edited/deleted by users)
- ChurchTools credentials stored per-user (no cross-user access)

---

## [1.0.5.16] - 2026-02-05

### Fixed
- AJAX handler for registration updated to accept `first_name`, `last_name`, `password` fields
- Fixed "Vor- und Nachname sind erforderlich" error despite fields being filled

---

## [1.0.5.15] - 2026-02-05

### Added
- Password-based registration (min 8 characters)
- Password confirmation with live validation
- First name / last name separation
- Personalized email greetings

### Database
- Added `password_hash` column to `plugin_cts_demo_users`
- Added `first_name` column
- Added `last_name` column
- Auto-generates `name` field from first_name + last_name

---

## Previous Versions
See full changelog history in [CHANGELOG.md](docs/CHANGELOG.md)

---

**Repository**: [GitHub - FEGAschaffenburg/churchtools-suite-demo](https://github.com/FEGAschaffenburg/churchtools-suite-demo)  
**Live Demo**: [plugin.feg-aschaffenburg.de/backend-demo](https://plugin.feg-aschaffenburg.de/backend-demo/)
