# ChurchTools Suite Demo - v1.0.3.1 Deployment Guide

**Version:** 1.0.3.1  
**Datum:** Dezember 2024  
**Status:** Ready to Deploy  

---

## 📋 Was wurde behoben?

### Kritische Fehler in v1.0.3:
1. ❌ Version nicht aktualisiert (1.0.3 vs 1.0.3.1)
2. ❌ Tabelle `wp_cts_demo_users` wurde **nie erstellt** (Activation Hooks fehlten)
3. ❌ Fehlende Datenbankpalten: `verified_at`, `last_login_at`, `updated_at`
4. ❌ Modal zeigt "Error Loading Event" bei Demo-Events
5. ❌ Keine Robustheit bei Errors

### Fixes in v1.0.3.1:
1. ✅ Version auf 1.0.3.1 aktualisiert
2. ✅ Activation Hooks registriert → Tabelle wird beim Aktivieren erstellt
3. ✅ init() ruft `create_tables()` auf → Fallback-Robustheit
4. ✅ Alle fehlenden Spalten & Indexes hinzugefügt
5. ✅ AJAX Modal-Handler unterstützt Demo-Mode
6. ✅ Logging für Debugging hinzugefügt

---

## 🚀 SCHNELLE BEREITSTELLUNG (5 Minuten)

### Schritt 1: ZIP-Paket erstellen

PowerShell öffnen und folgendes ausführen:

```powershell
cd C:\Users\nauma\OneDrive\Plugin_neu\churchtools-suite-demo
.\deploy-demo-plugin.ps1
```

**Ergebnis:** ZIP-Datei in `C:\privat\churchtools-suite-demo-1.0.3.1.zip`

### Schritt 2: Auf Server hochladen

**Option A: Via Filezilla/FTP**
1. Filezilla öffnen
2. Mit Server verbinden
3. ZIP hochladen zu: `/wp-content/plugins/`
4. Datei auf Server entzippen
5. `churchtools-suite-demo-1.0.3.0` (alt) **löschen**

**Option B: Via SSH/Terminal**
```bash
cd /path/to/server/wp-content/plugins/
scp C:\privat\churchtools-suite-demo-1.0.3.1.zip user@server:/wp-content/plugins/
unzip churchtools-suite-demo-1.0.3.1.zip
rm -rf churchtools-suite-demo-1.0.3.0  # Alte Version löschen
```

### Schritt 3: Plugin deaktivieren

1. WordPress Admin öffnen
2. **Plugins** aufrufen
3. **"ChurchTools Suite Demo"** suchen
4. **"Deaktivieren"** klicken
5. **30 Sekunden warten**

### Schritt 4: Plugin aktivieren

1. **"ChurchTools Suite Demo"** suchen
2. **"Aktivieren"** klicken
3. 🎉 Fertig!

### Schritt 5: Validierung

Öffnen Sie im Browser:
```
https://example.com/wp-content/plugins/churchtools-suite-demo/validate-installation.php
```

**Alle Checks sollten ✅ sein:**
- ✅ WordPress geladen
- ✅ Demo Plugin aktiv (v1.0.3.1)
- ✅ Datenbankverbindung
- ✅ Tabelle `wp_cts_demo_users` existiert
- ✅ Alle erforderlichen Spalten vorhanden
- ✅ Hauptplugin aktiv (v1.0.3.1+)

---

## 🧪 TESTS NACH DEPLOYMENT

### Test 1: Demo-Registrierung
1. Öffnen Sie die Demo-Registrierungsseite
2. Füllen Sie das Formular aus:
   - E-Mail: `test@example.com`
   - Name: `Test User`
   - Zweck: `Testing`
3. Klicken Sie "Registrieren"
4. ✅ **Erwartet:** Bestätigungsemail erhalten

### Test 2: Demo-Events Modal
1. Gehen Sie zur Events-Seite
2. Klicken Sie auf ein **Demo Event**
3. ✅ **Erwartet:** Modal öffnet sich ohne "Error Loading Event"

### Test 3: Admin Panel
1. WordPress Admin → **ChurchTools Suite**
2. Tab **"Demo Users"**
3. ✅ **Erwartet:** Registrierter User ist sichtbar

---

## 🔧 FEHLERBEHANDLUNG

### Problem: "Fehler Loading Event" ist noch da

**Lösung 1: Cache löschen**
```powershell
# WordPress Cache löschen
cd C:\path\to\wordpress
.\wp-cli cache flush
# Browser Cache: Ctrl+Shift+Del
```

**Lösung 2: Plugin neu aktivieren**
1. Admin → Plugins
2. Demo Plugin: **Deaktivieren**
3. 30 Sekunden warten
4. Demo Plugin: **Aktivieren**
5. Browser neuladen (Ctrl+F5)

**Lösung 3: Browser Dev Tools prüfen**
1. F12 öffnen (Developer Console)
2. Tab **"Network"** öffnen
3. Event-Modal klicken
4. AJAX-Request `cts_get_event_details` suchen
5. Response prüfen:
   - ✅ Status 200 → OK
   - ❌ Status 404 → Plugin nicht gefunden
   - ❌ Fehler → JavaScript-Error

---

### Problem: Tabelle existiert nicht (Database Error)

**Symptom:**
```
WordPress-Datenbank-Fehler: [Table 'database.wp_cts_demo_users' doesn't exist]
```

**Lösung 1: Plugin neu aktivieren**
```
Admin → Plugins → Demo deaktivieren → 30 Sek → aktivieren
```

**Lösung 2: Manuelle SQL ausführen (phpMyAdmin)**

1. phpMyAdmin öffnen
2. SQL-Tab wählen
3. Folgendes einfügen:

```sql
CREATE TABLE IF NOT EXISTS `wp_cts_demo_users` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `email` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `organization` varchar(255) DEFAULT NULL,
  `purpose` text,
  `verification_token` varchar(64) NOT NULL,
  `is_verified` tinyint(1) DEFAULT 0,
  `verified_at` datetime DEFAULT NULL,
  `wordpress_user_id` bigint(20) unsigned DEFAULT NULL,
  `last_login_at` datetime DEFAULT NULL,
  `expires_at` datetime DEFAULT NULL,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `email` (`email`),
  UNIQUE KEY `verification_token` (`verification_token`),
  KEY `verified_at` (`verified_at`),
  KEY `created_at` (`created_at`),
  KEY `wordpress_user_id` (`wordpress_user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
```

4. **Ausführen** klicken
5. Validator neu laden

---

### Problem: "Plugin funktioniert nicht" generisch

**Schritt 1: Logs prüfen**

WordPress Debug-Mode aktivieren in `wp-config.php`:
```php
define('WP_DEBUG', true);
define('WP_DEBUG_LOG', true);
define('WP_DEBUG_DISPLAY', false);
```

Dann logs prüfen: `/wp-content/debug.log`

**Schritt 2: Plugin-Test**
```bash
cd /wp-content/plugins/churchtools-suite-demo
# Syntax-Fehler prüfen
php -l churchtools-suite-demo.php
# Sollte zeigen: "No syntax errors detected"
```

**Schritt 3: Validator ausführen**
```
https://example.com/wp-content/plugins/churchtools-suite-demo/validate-installation.php
```

---

## 📊 VERSION-VERGLEICH

| Feature | v1.0.3 | v1.0.3.1 |
|---------|--------|----------|
| Activation Hooks | ❌ Nein | ✅ Ja |
| Tabelle erstellt | ❌ Nein | ✅ Ja |
| init() table creation | ❌ Nein | ✅ Ja |
| `verified_at` Spalte | ❌ Nein | ✅ Ja |
| `last_login_at` Spalte | ❌ Nein | ✅ Ja |
| `updated_at` Spalte | ❌ Nein | ✅ Ja |
| Event Modal Demo-Support | ❌ Nein | ✅ Ja |
| Logging | ❌ Basis | ✅ Enhanced |
| Robustheit | ⚠️ Gering | ✅ Hoch |

---

## 🎯 NEXT STEPS

### Sofort nach Deployment:
- [ ] Validator ausführen
- [ ] Demo-Registrierung testen
- [ ] Event-Modal klicken
- [ ] Admin Panel prüfen

### Falls Fehler:
- [ ] Logs in `wp-content/debug.log` prüfen
- [ ] Plugin neu aktivieren
- [ ] Validator neu laden
- [ ] ggf. Manuelle SQL ausführen

### Langfristig:
- [ ] Validator-Datei löschen (nur für Tests!)
- [ ] Regelmäßig Logs aufräumen
- [ ] Updates durchführen (wenn verfügbar)

---

## 💡 TIPPS

### Automatische Fehlertoleranz aktivieren
Falls der Fehler immer noch auftritt, können Sie in `churchtools-suite-demo.php` folgende Zeile hinzufügen:

```php
// In der init() Methode, GANZ AM ANFANG
public function init(): void {
    // Auto-create tables IMMER (auch ohne Activation Hook)
    $this->create_tables();
    // ... rest
}
```

**Status:** Das ist bereits in v1.0.3.1 implementiert! ✅

### Cron-Job testen
Demo nutzt WP-Cron für regelmäßige Aufgaben. Testen Sie:

```bash
# WordPress CLI
wp cron test
# Sollte zeigen: "Cron is working"
```

---

## 📞 SUPPORT

Falls nach Deployment noch Probleme auftreten:

1. **Validator-Seite öffnen:**
   ```
   https://example.com/wp-content/plugins/churchtools-suite-demo/validate-installation.php
   ```
   → Alle Checks sollten ✅ sein

2. **Logs prüfen:**
   ```
   /wp-content/debug.log
   ```
   → Suchen Sie nach "ChurchTools Demo" Einträgen

3. **Hauptplugin Version prüfen:**
   ```
   Admin → Plugins → "ChurchTools Suite"
   ```
   → Sollte v1.0.3.1+ sein

4. **Datenbank backup**
   ```
   Vor jedem Update immer Backup machen!
   ```

---

## 📝 GIT COMMITS

Diese Changes wurden committed:

```
Commit 193a394: "fix(demo-plugin): Register activation hooks and fix table creation (v1.0.3.1)"
- Added register_activation_hook() and register_deactivation_hook()
- Fixed table name to wp_cts_demo_users (with cts_ prefix)
- Enhanced create_tables() with all required columns
- Added indexes for performance
- Added logging for debugging

Commit 593349b: "fix(demo-plugin): Create tables on init for robustness (v1.0.3.1)"
- Added $this->create_tables() call at start of init()
- Ensures tables are created even if activation hooks fail
- Provides fallback mechanism for robustness
```

---

**Deployment-Datum:** <?php echo date('Y-m-d H:i:s'); ?>  
**Status:** ✅ Ready for Production
