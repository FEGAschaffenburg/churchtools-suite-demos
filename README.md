# ChurchTools Suite Demo Plugin

Demo-Addon für ChurchTools Suite mit Self-Service Backend-Registrierung.

## Features

- ✅ Self-Service Registrierung mit E-Mail-Verifizierung
- ✅ Auto-Login nach Verifizierung
- ✅ Custom WordPress Role: `cts_demo_user` (nur Plugin-Zugriff)
- ✅ Auto-Cleanup: Unverifizierte User nach 7 Tagen, Verifizierte nach 30 Tagen
- ✅ Admin-Panel für User-Verwaltung
- ✅ Admin-Benachrichtigungen bei neuen Registrierungen
- ✅ DSGVO-konform: Privacy Checkbox, Auto-Löschung, Export
- ✅ Demo-Daten: 6 Kalender mit realistischen Events

## Voraussetzungen

- WordPress 6.0+
- PHP 8.0+
- **ChurchTools Suite Plugin v0.9.0** (Hauptplugin erforderlich!)

⚠️ **HINWEIS:** Erfordert das neue Clean-Slate Release des Hauptplugins!

## Installation (nur Demo-Server!)

⚠️ **WICHTIG:** Dieses Plugin ist **nur für den Demo-Server** gedacht und wird **NICHT via Git deployed**!

### SSH-Deployment

```bash
# 1. ZIP erstellen (lokal in PowerShell)
cd c:\privat\churchtools-suite-demo
Compress-Archive -Path * -DestinationPath ..\churchtools-suite-demo-1.0.0.zip -Force

# 2. ZIP zum Server hochladen (via FTP/SSH)
# z.B. mit WinSCP oder FileZilla

# 3. Auf Server per SSH einloggen
ssh user@plugin.feg-aschaffenburg.de

# 4. In Plugin-Verzeichnis wechseln
cd /var/www/clients/client436/web2975/web/wp-content/plugins/

# 5. ZIP entpacken
unzip churchtools-suite-demo-1.0.0.zip -d churchtools-suite-demo/

# 6. Rechte setzen
chown -R web2975:client436 churchtools-suite-demo/
chmod -R 755 churchtools-suite-demo/

# 7. Plugin aktivieren (via WP-CLI oder WordPress Admin)
wp plugin activate churchtools-suite-demo

# 8. ZIP entfernen
rm ../churchtools-suite-demo-1.0.0.zip
```

### Alternativ: Direkte Datei-Bearbeitung per SSH

```bash
# Nur für kleine Änderungen:
ssh user@plugin.feg-aschaffenburg.de
cd /var/www/clients/client436/web2975/web/wp-content/plugins/churchtools-suite-demo/
nano includes/services/class-demo-registration-service.php
```

## Konfiguration

Nach Aktivierung automatisch verfügbar:

### Admin-Panel

- **WordPress Admin → ChurchTools Suite → Demo-Users**
- Statistiken: Gesamt, Verifiziert, Unverifiziert, Letzte 7 Tage
- User-Liste mit Lösch-Funktion
- CSV-Export aller Registrierungen

### Shortcode

Auf beliebiger Seite einfügen:

```
[cts_demo_register]
```

Zeigt Registrierungsformular mit:
- E-Mail-Adresse (Pflichtfeld)
- Name (Pflichtfeld)
- Firma/Gemeinde (optional)
- Verwendungszweck (optional)
- DSGVO-Checkbox (Pflichtfeld)

### Auto-Cleanup Cron

Läuft **täglich automatisch** via WordPress Cron:

```php
// Hook: churchtools_suite_demo_cleanup
// Frequenz: Täglich
// Funktion: Löscht alte Demo-User
```

**Cleanup-Regeln:**
- Unverifizierte User: Nach 7 Tagen gelöscht
- Verifizierte User: Nach 30 Tagen gelöscht
- Zugehörige WordPress-User werden mitgelöscht

## Architektur

### Datenbank

Tabelle: `wp_cts_demo_users`

```sql
CREATE TABLE wp_cts_demo_users (
    id bigint(20) unsigned NOT NULL AUTO_INCREMENT,
    email varchar(255) NOT NULL,
    name varchar(255) DEFAULT NULL,
    company varchar(255) DEFAULT NULL,
    purpose text DEFAULT NULL,
    verification_token varchar(64) NOT NULL,
    verified_at datetime DEFAULT NULL,
    wp_user_id bigint(20) unsigned DEFAULT NULL,
    last_login_at datetime DEFAULT NULL,
    created_at datetime DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (id),
    UNIQUE KEY email (email),
    UNIQUE KEY verification_token (verification_token)
);
```

### Benutzer-Rolle

**Role:** `cts_demo_user`

**Capabilities:**
- `read` - Kann sich einloggen
- `cts_view_plugin` - Kann ChurchTools Suite Plugin sehen

**KEIN Zugriff auf:**
- Posts, Pages, Media
- Themes, Plugins
- WordPress Settings
- Andere Benutzer

### Hook-System

Demo-Daten werden via Filter eingespeist:

```php
// Demo-Plugin aktiviert Filter im Haupt-Plugin:
add_filter('churchtools_suite_get_events', function($events, $filters) {
    // Wenn Demo-Daten angefordert werden:
    if (isset($filters['demo']) && $filters['demo']) {
        return $demo_provider->get_events($filters);
    }
    return $events;
}, 10, 2);
```

## Registrierungs-Flow

1. **User füllt Formular aus** ([cts_demo_register] Shortcode)
2. **AJAX-Submit** an `wp_ajax_nopriv_cts_demo_register`
3. **Validierung** (Email, Name, Privacy Checkbox)
4. **Demo-User erstellen** (wp_cts_demo_users Tabelle)
5. **Verification-Email senden** mit Token-Link
6. **User klickt auf Link** (`?action=cts_verify_demo_user&token=...`)
7. **WordPress-User erstellen** mit Role `cts_demo_user`
8. **Auto-Login** und Redirect zu `/wp-admin`
9. **Admin-Notification** an Plugin-Admin

## E-Mail Templates

### Verifizierungs-Email

```
Betreff: Verifizierung Ihrer Demo-Registrierung

Hallo,

vielen Dank für Ihr Interesse am ChurchTools Suite Plugin!

Bitte klicken Sie auf den folgenden Link, um Ihre E-Mail-Adresse zu verifizieren:

{verification_link}

Nach der Verifizierung werden Sie automatisch eingeloggt und können 
das Plugin-Backend 30 Tage lang testen.

Ihr Demo-Zugang wird nach 30 Tagen automatisch gelöscht.

Viel Spaß beim Testen!
```

### Admin-Notification

```
Betreff: Neue Demo-Registrierung

Eine neue Demo-Registrierung ist eingegangen:

E-Mail: {email}
Name: {name}
Firma: {company}
Zweck: {purpose}

Registriert am: {created_at}
```

## Sicherheit

### DSGVO-Konformität

- ✅ Explizite Privacy Checkbox erforderlich
- ✅ Link zur Datenschutzerklärung
- ✅ Auto-Löschung nach 7/30 Tagen
- ✅ CSV-Export für Admin (DSGVO Auskunft)
- ✅ Manuelles Löschen möglich

### Zugriffsbeschränkungen

- ✅ Demo-User sehen **NUR** ChurchTools Suite Plugin
- ✅ Keine Admin-Rechte
- ✅ Kein Zugriff auf sensible Daten
- ✅ Keine Schreibrechte außerhalb Plugin

### Nonce-Validierung

Alle AJAX-Requests prüfen WordPress Nonces:

```php
check_ajax_referer('cts_demo_register', 'nonce');
check_ajax_referer('cts_demo_admin', 'nonce');
```

## Entwicklung

### Lokales Testen

```bash
# Symlink erstellen (statt ZIP)
cd /path/to/wordpress/wp-content/plugins/
ln -s c:/privat/churchtools-suite-demo churchtools-suite-demo

# Plugin aktivieren
wp plugin activate churchtools-suite-demo
```

### Debugging

```php
// In wp-config.php:
define('WP_DEBUG', true);
define('WP_DEBUG_LOG', true);

// Logs prüfen:
tail -f wp-content/debug.log
```

## Troubleshooting

### "Requires Plugins: churchtools-suite not found"

**Lösung:** Haupt-Plugin muss zuerst installiert sein!

```bash
wp plugin install /path/to/churchtools-suite-0.9.x.zip --activate
wp plugin activate churchtools-suite-demo
```

### Verification-Email kommt nicht an

**Lösung:** SMTP Plugin installieren

```bash
wp plugin install wp-mail-smtp --activate
```

### Cron läuft nicht

**Lösung:** System-Cron statt WP-Cron nutzen

```bash
# In wp-config.php:
define('DISABLE_WP_CRON', true);

# System Crontab:
*/15 * * * * wget -q -O - https://plugin.feg-aschaffenburg.de/wp-cron.php
```

## Updates

⚠️ **Keine automatischen Updates!** Plugin ist SSH-only.

**Update-Prozess:**

1. Lokale Änderungen testen
2. Neues ZIP erstellen
3. Per SSH auf Server hochladen
4. Altes Verzeichnis sichern (`mv churchtools-suite-demo churchtools-suite-demo.backup`)
5. Neues ZIP entpacken
6. Testen
7. Backup löschen

## Support

Bei Problemen:

1. **Logs prüfen:** `wp-content/debug.log`
2. **ChurchTools Suite Logger:** Admin → Erweitert → Logs
3. **Demo-User Statistiken:** Admin → Demo-Users

## Lizenz

Proprietär - Nur für Demo-Server FEG Aschaffenburg.

**Nicht veröffentlichen oder weiterverteilen!**

---

**Version:** 1.0.0  
**Letzte Aktualisierung:** Januar 2026
