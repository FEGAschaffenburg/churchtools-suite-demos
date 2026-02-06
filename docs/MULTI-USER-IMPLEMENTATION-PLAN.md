# Multi-User Backend Demo - Implementierungsplan

> **Status:** Planung  
> **Version:** churchtools-suite-demo v1.0.6.0  
> **Datum:** 05. Februar 2026  
> **Backup:** before_multiuser_20260205_151031

---

## 🎯 Ziel

Jeder Demo-Tester erhält eine **isolierte Backend-Umgebung** mit eigenen:
- ChurchTools-Verbindungseinstellungen (URL, API-Key)
- Plugin-Einstellungen (Sync-Intervalle, Template-Auswahl)
- Views (gespeicherte Event-Listen-Konfigurationen)
- Shortcode-Presets (benutzerdefinierte Shortcodes)

**Kein Einfluss** auf andere Tester - jeder arbeitet in seiner eigenen "Sandbox".

---

## 🏗️ Architektur-Entscheidung

**Option 1: User-Specific Settings** (EMPFOHLEN)

### Vorteile
✅ Minimal invasiv - kein großer Umbau  
✅ Nutzt WordPress-Standards (wp_usermeta)  
✅ Schnelle Implementierung (2-3 Tage)  
✅ Einfache Wartung  
✅ Nutzt bestehende WordPress User-Isolation

### Nachteile
⚠️ Shared Database (Views/Presets brauchen user_id)  
⚠️ Event-Cache shared (akzeptabel für Demo)

---

## 📋 Implementierungs-Checkliste

### Phase 1: Datenbank-Anpassungen (1-2 Stunden)

#### 1.1 Tabellen-Erweiterung
```sql
-- plugin_cts_views
ALTER TABLE plugin_cts_views 
ADD COLUMN user_id bigint(20) unsigned DEFAULT NULL AFTER id,
ADD INDEX idx_user_id (user_id);

-- plugin_cts_shortcode_presets (nur custom presets)
ALTER TABLE plugin_cts_shortcode_presets 
ADD COLUMN user_id bigint(20) unsigned DEFAULT NULL AFTER id,
ADD INDEX idx_user_id (user_id);
```

**Betroffene Tabellen:**
- ✅ `plugin_cts_views` - MUSS isoliert werden
- ✅ `plugin_cts_shortcode_presets` - MUSS isoliert werden
- ❌ `plugin_cts_calendars` - KANN shared bleiben (nur Demo-Daten)
- ❌ `plugin_cts_events` - KANN shared bleiben (nur Demo-Daten)
- ❌ `plugin_cts_services` - KANN shared bleiben (nur Demo-Daten)

#### 1.2 Migration Script
```php
// Existing views: Assign to admin user or mark as "system"
UPDATE plugin_cts_views 
SET user_id = (SELECT ID FROM plugin_users WHERE user_login = 'admin' LIMIT 1)
WHERE user_id IS NULL;

// Existing presets: Mark system presets (is_system=1), others to admin
UPDATE plugin_cts_shortcode_presets 
SET user_id = NULL 
WHERE is_system = 1;
```

**Datei:** `includes/migrations/class-multi-user-migration.php`

---

### Phase 2: Settings System Refactoring (2-3 Stunden)

#### 2.1 Neue Settings-Klasse

**Datei:** `includes/class-churchtools-suite-user-settings.php`

```php
class ChurchTools_Suite_User_Settings {
    
    /**
     * Get user-specific setting
     * Falls back to global setting if user setting not found
     */
    public static function get($key, $user_id = null, $default = null) {
        if (!$user_id) {
            $user_id = get_current_user_id();
        }
        
        // Try user-specific setting first
        $user_value = get_user_meta($user_id, "churchtools_suite_{$key}", true);
        
        if ($user_value !== '' && $user_value !== false) {
            return $user_value;
        }
        
        // Fallback to global setting (for backwards compatibility)
        $global_value = get_option("churchtools_suite_{$key}");
        
        return $global_value !== false ? $global_value : $default;
    }
    
    /**
     * Set user-specific setting
     */
    public static function set($key, $value, $user_id = null) {
        if (!$user_id) {
            $user_id = get_current_user_id();
        }
        
        return update_user_meta($user_id, "churchtools_suite_{$key}", $value);
    }
    
    /**
     * Delete user-specific setting
     */
    public static function delete($key, $user_id = null) {
        if (!$user_id) {
            $user_id = get_current_user_id();
        }
        
        return delete_user_meta($user_id, "churchtools_suite_{$key}");
    }
}
```

#### 2.2 Settings Migration

**Betroffene Settings (von wp_options → wp_usermeta):**

**ChurchTools Connection:**
- `churchtools_suite_ct_url` → user_meta
- `churchtools_suite_ct_username` → user_meta
- `churchtools_suite_ct_password` → user_meta (encrypted!)
- `churchtools_suite_ct_cookies` → user_meta

**Plugin Settings:**
- `churchtools_suite_sync_days_past` → user_meta
- `churchtools_suite_sync_days_future` → user_meta
- `churchtools_suite_auto_sync_enabled` → user_meta
- `churchtools_suite_auto_sync_interval` → user_meta
- `churchtools_suite_default_template` → user_meta

**Shared Settings (bleiben in wp_options):**
- `churchtools_suite_db_version` - global
- `churchtools_suite_advanced_mode` - global
- `churchtools_suite_last_sync_timestamp` - global (oder auch per-user?)

#### 2.3 Code-Anpassungen

**Suchmuster:** `get_option('churchtools_suite_`  
**Ersetzen mit:** `ChurchTools_Suite_User_Settings::get('`

**Betroffene Dateien (geschätzt):**
- `includes/services/class-churchtools-suite-ct-client.php`
- `includes/services/class-churchtools-suite-event-sync-service.php`
- `admin/class-churchtools-suite-admin.php`
- `admin/views/tab-settings.php`
- Alle AJAX-Handler in Settings-Tab

---

### Phase 3: Repository-Anpassungen (1-2 Stunden)

#### 3.1 Views Repository

**Datei:** `includes/repositories/class-churchtools-suite-views-repository.php`

```php
// BEFORE
public function get_all() {
    global $wpdb;
    return $wpdb->get_results("SELECT * FROM {$this->table} ORDER BY name ASC");
}

// AFTER
public function get_all($user_id = null) {
    global $wpdb;
    
    if (!$user_id) {
        $user_id = get_current_user_id();
    }
    
    return $wpdb->get_results($wpdb->prepare(
        "SELECT * FROM {$this->table} WHERE user_id = %d ORDER BY name ASC",
        $user_id
    ));
}

// NEW: Get system views (user_id = NULL)
public function get_system_views() {
    global $wpdb;
    return $wpdb->get_results("SELECT * FROM {$this->table} WHERE user_id IS NULL ORDER BY name ASC");
}
```

**Anpassungen:**
- `get_all()` - Filter by user_id
- `create()` - Inject user_id
- `update()` - Verify ownership
- `delete()` - Verify ownership

#### 3.2 Shortcode Presets Repository

**Datei:** `includes/repositories/class-churchtools-suite-shortcode-presets-repository.php`

```php
// System presets: user_id = NULL
// User presets: user_id = current_user_id

public function get_all($user_id = null) {
    global $wpdb;
    
    if (!$user_id) {
        $user_id = get_current_user_id();
    }
    
    // Get system presets + user presets
    return $wpdb->get_results($wpdb->prepare(
        "SELECT * FROM {$this->table} 
         WHERE user_id IS NULL OR user_id = %d 
         ORDER BY is_system DESC, name ASC",
        $user_id
    ));
}
```

---

### Phase 4: Admin UI Anpassungen (1-2 Stunden)

#### 4.1 Settings-Tab Labels

**Datei:** `admin/views/tab-settings.php`

```php
// BEFORE
<h2><?php _e('ChurchTools Verbindung', 'churchtools-suite'); ?></h2>

// AFTER
<h2><?php _e('Ihre ChurchTools Verbindung', 'churchtools-suite'); ?></h2>
<p class="description">
    <?php _e('Diese Einstellungen gelten nur für Ihren Account.', 'churchtools-suite'); ?>
</p>
```

#### 4.2 User Context Indicator

**Neues Element im Admin Header:**

```php
<div class="cts-user-context">
    <span class="dashicons dashicons-admin-users"></span>
    <?php printf(
        __('Angemeldet als: <strong>%s</strong> (Ihre persönliche Demo-Umgebung)', 'churchtools-suite'),
        wp_get_current_user()->display_name
    ); ?>
</div>
```

**CSS:**
```css
.cts-user-context {
    background: #e7f3ff;
    border-left: 4px solid #0073aa;
    padding: 12px 16px;
    margin-bottom: 20px;
    font-size: 14px;
}
```

#### 4.3 Views Liste

**Datei:** `admin/views/tab-sync.php` (Views-Tabelle)

- Filter: Zeige nur eigene Views
- System-Views: Readonly, aber sichtbar (Badge: "System")
- Action-Links: "Bearbeiten" nur für eigene Views

#### 4.4 Dashboard

**Datei:** `admin/views/tab-dashboard.php`

- Stats: Nur eigene Events (oder shared? → Entscheidung nötig)
- Last Sync: Per-user oder global? → Entscheidung nötig

---

### Phase 5: ChurchTools Client Isolation (1 Stunde)

#### 5.1 CT Client Update

**Datei:** `includes/services/class-churchtools-suite-ct-client.php`

```php
class ChurchTools_Suite_CT_Client {
    
    private $user_id;
    
    public function __construct($user_id = null) {
        $this->user_id = $user_id ?: get_current_user_id();
    }
    
    private function get_setting($key, $default = null) {
        return ChurchTools_Suite_User_Settings::get($key, $this->user_id, $default);
    }
    
    private function set_setting($key, $value) {
        return ChurchTools_Suite_User_Settings::set($key, $value, $this->user_id);
    }
    
    // Use get_setting() for:
    // - ct_url
    // - ct_username
    // - ct_password
    // - ct_cookies
}
```

---

### Phase 6: AJAX Handler Updates (1 Stunde)

#### 6.1 Settings AJAX

**Datei:** `admin/class-churchtools-suite-admin.php`

```php
public function ajax_save_settings() {
    check_ajax_referer('churchtools_suite_admin', 'nonce');
    
    if (!current_user_can('cts_manage_settings')) {
        wp_send_json_error(['message' => 'Keine Berechtigung']);
    }
    
    $user_id = get_current_user_id();
    
    // Save to user_meta instead of options
    ChurchTools_Suite_User_Settings::set('ct_url', $_POST['ct_url'], $user_id);
    ChurchTools_Suite_User_Settings::set('ct_username', $_POST['ct_username'], $user_id);
    // ...
    
    wp_send_json_success(['message' => 'Ihre Einstellungen wurden gespeichert']);
}
```

#### 6.2 Views AJAX

```php
public function ajax_save_view() {
    // ...
    $view_data['user_id'] = get_current_user_id();
    $view_id = $views_repo->create($view_data);
    // ...
}

public function ajax_delete_view() {
    // ...
    $view = $views_repo->get_by_id($view_id);
    
    // Verify ownership
    if ($view->user_id != get_current_user_id()) {
        wp_send_json_error(['message' => 'Sie können nur Ihre eigenen Views löschen']);
    }
    // ...
}
```

---

### Phase 7: Testing Strategy (2-3 Stunden)

#### 7.1 Local Testing

**Test-Accounts erstellen:**
```sql
-- Test User 1: max@example.com
INSERT INTO plugin_cts_demo_users (email, first_name, last_name, verification_token, is_verified, verified_at)
VALUES ('max@example.com', 'Max', 'Mustermann', MD5(RAND()), 1, NOW());

-- Test User 2: anna@example.com
INSERT INTO plugin_cts_demo_users (email, first_name, last_name, verification_token, is_verified, verified_at)
VALUES ('anna@example.com', 'Anna', 'Schmidt', MD5(RAND()), 1, NOW());
```

**Test Cases:**

1. ✅ User 1: ChurchTools-Verbindung einrichten
2. ✅ User 2: Eigene ChurchTools-Verbindung (andere URL)
3. ✅ User 1: View erstellen ("Meine Gottesdienste")
4. ✅ User 2: Sieht NICHT User 1's View
5. ✅ User 2: Eigene View erstellen ("Meine Events")
6. ✅ User 1: Sieht NICHT User 2's View
7. ✅ Beide: Sehen System-Views (readonly)
8. ✅ User 1: Shortcode-Preset erstellen
9. ✅ User 2: Sieht NICHT User 1's Preset
10. ✅ Settings ändern: Keine Auswirkung auf anderen User

#### 7.2 Live Testing

**Vorsichtig!** Live-Tests nur mit echten Demo-Usern:
- Login als Demo-User (via WP-Admin)
- ChurchTools-Verbindung testen
- View erstellen
- Mit anderem Demo-User einloggen
- Isolation verifizieren

---

### Phase 8: Deployment (30 Minuten)

#### 8.1 Version Bump

**Datei:** `churchtools-suite-demo.php`

```php
/**
 * Version: 1.0.6.0
 */
define('CHURCHTOOLS_SUITE_DEMO_VERSION', '1.0.6.0');
```

#### 8.2 Deployment Script

```bash
#!/bin/bash
# deploy-multiuser-live.sh

VERSION="1.0.6.0"
REMOTE="plugin-test"
PLUGIN_DIR="/var/www/clients/client436/web2980/web/wp-content/plugins/churchtools-suite-demo-${VERSION}"

echo "=== Deploying Multi-User Implementation ==="
echo "Version: ${VERSION}"

# 1. Create ZIP
cd C:/Users/nauma/OneDrive/laragon/www/plugin-homepage/wp-content/plugins
zip -r churchtools-suite-demo-${VERSION}.zip churchtools-suite-demo -x "*.git*"

# 2. Upload
scp churchtools-suite-demo-${VERSION}.zip ${REMOTE}:~/

# 3. Install
ssh ${REMOTE} << 'EOF'
cd /var/www/clients/client436/web2980/web/wp-content/plugins
unzip ~/churchtools-suite-demo-1.0.6.0.zip
mv churchtools-suite-demo churchtools-suite-demo-1.0.6.0
echo "✅ Plugin deployed"
EOF

# 4. Activate
ssh ${REMOTE} "cd /var/www/clients/client436/web2980/web && wp plugin activate churchtools-suite-demo"

echo "✅ Deployment complete"
```

#### 8.3 Rollback Plan

**Falls Probleme auftreten:**

```bash
# Restore from backup
ssh plugin-test << 'EOF'
BACKUP_DIR="/var/www/clients/client436/web2980/backups/before_multiuser_20260205_151031"
PLUGIN_DIR="/var/www/clients/client436/web2980/web/wp-content/plugins"

# 1. Restore plugin
cd $PLUGIN_DIR
rm -rf churchtools-suite-demo*
tar xzf $BACKUP_DIR/churchtools-suite-demo.tar.gz

# 2. Restore database
cd $BACKUP_DIR
gunzip < database_cts_tables.sql.gz | mysql -h db.feg.de -u aschaffesql_plugin -p aschaffesql_plugin

# 3. Restore settings
wp option delete churchtools_suite_db_version

echo "✅ Rollback complete - restart from backup"
EOF
```

---

## 📊 Aufwands-Schätzung

| Phase | Aufgabe | Zeit |
|-------|---------|------|
| 1 | Datenbank-Anpassungen | 1-2h |
| 2 | Settings System Refactoring | 2-3h |
| 3 | Repository-Anpassungen | 1-2h |
| 4 | Admin UI Anpassungen | 1-2h |
| 5 | ChurchTools Client Isolation | 1h |
| 6 | AJAX Handler Updates | 1h |
| 7 | Testing | 2-3h |
| 8 | Deployment | 0.5h |
| **GESAMT** | **10-15.5 Stunden** | **~2-3 Tage** |

---

## ⚠️ Risiken & Mitigation

### Risiko 1: Migration von bestehenden Settings

**Problem:** Aktuell gespeicherte Settings sind global (wp_options)

**Mitigation:**
- Migration Script: Kopiere globale Settings zu allen Demo-Usern als Fallback
- Graceful Fallback: Wenn user_meta leer, nutze wp_options
- Keine Datenverluste

### Risiko 2: Shared Event-Cache

**Problem:** Events werden shared gecached (alle Tester sehen gleiche Events)

**Mitigation:**
- Akzeptabel für Demo-Zweck
- Alternative: Cache per user_id (overkill für Demo)

### Risiko 3: ChurchTools API Rate-Limits

**Problem:** Mehrere Tester = mehr API-Calls

**Mitigation:**
- Bestehende Rate-Limit-Logik im CT Client
- Cache nutzen (shared ist OK)
- Auto-Sync deaktivierbar per user

### Risiko 4: Komplexität bei Debugging

**Problem:** User-spezifische Settings schwerer zu debuggen

**Mitigation:**
- Debug-Tab: Zeige Current User ID
- Debug-Log: Prefix mit User ID
- Admin kann Settings aller User sehen (neue Capability)

---

## 🔮 Zukunfts-Features (Nice-to-Have)

### V2: Admin User Management

**Admin kann:**
- Alle Demo-User-Settings einsehen
- Settings zurücksetzen ("Reset to Default")
- User-Aktivität überwachen

### V3: Templates pro User

**User kann:**
- Eigene Templates hochladen (wie im Template Manager)
- Templates nur für sich nutzen
- Templates mit anderen teilen

### V4: Import/Export Settings

**User kann:**
- Eigene Settings exportieren (JSON)
- Settings in neue Demo-Umgebung importieren
- Vorlagen teilen

---

## ✅ Definition of Done

**Multi-User Implementation ist fertig wenn:**

- [x] Backup erstellt (✅ before_multiuser_20260205_151031)
- [ ] Datenbank-Migrations executed (user_id columns)
- [ ] User-Settings-Klasse implementiert
- [ ] Alle Settings-Zugriffe refactored (get_option → User_Settings)
- [ ] Repository-Methoden akzeptieren user_id
- [ ] Admin UI zeigt "Ihre Einstellungen"
- [ ] AJAX-Handler verifizieren ownership
- [ ] 10 Test Cases bestanden (local)
- [ ] Live-Test mit 2 echten Demo-Usern
- [ ] Dokumentation aktualisiert
- [ ] CHANGELOG.md Entry
- [ ] Version bump zu 1.0.6.0
- [ ] Deployment auf Live-Server
- [ ] Smoke-Test auf Live

---

## 📚 Dokumentations-Updates

**Dateien zu aktualisieren:**

1. `docs/USER-MANAGEMENT-GUIDE.md`
   - Section: "Multi-User Isolation"
   - Beschreibung der user-spezifischen Features

2. `docs/ROADMAP.md`
   - ✅ v1.0.6.0: Multi-User Backend Isolation
   - Details zu implementierten Features

3. `CHANGELOG.md`
   ```markdown
   ## [1.0.6.0] - 2026-02-05
   ### Added
   - Multi-User Isolation: Jeder Demo-Tester erhält eigene Settings
   - User-specific ChurchTools connections (URL, API Key)
   - User-specific Views und Shortcode Presets
   - User Context Indicator im Admin
   
   ### Changed
   - Settings Migration: wp_options → wp_usermeta
   - Views Repository: Filter by user_id
   - Presets Repository: Filter by user_id
   
   ### Database
   - Added user_id column to plugin_cts_views
   - Added user_id column to plugin_cts_shortcode_presets
   ```

4. `README.md`
   - Feature-Liste erweitern: "Multi-User Demo Environment"

---

## 🤝 Nächste Schritte

1. **Review dieses Plans** mit User
2. **Entscheidungen treffen:**
   - Sollen Events auch per-user gecached werden? (Empfehlung: NEIN)
   - Soll Last-Sync per-user sein? (Empfehlung: JA)
   - Sollen Dashboard-Stats per-user sein? (Empfehlung: JA)
3. **Start Implementation** - Phase für Phase
4. **Testing** nach jeder Phase
5. **Deployment** mit Rollback-Bereitschaft

---

**Erstellt am:** 05. Februar 2026  
**Backup-Location:** `/var/www/clients/client436/web2980/backups/before_multiuser_20260205_151031/`  
**Nächste Version:** v1.0.6.0
