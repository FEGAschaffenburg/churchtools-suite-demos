# ChurchTools Suite Demo Plugin - Deployment v1.0.3.1

## 🚀 Schnelle Anleitung für Plugin-Update

### Fehler der vorherigen Version (v1.0.3)
- ❌ Tabelle `wp_cts_demo_users` wurde nicht erstellt
- ❌ Activation Hooks nicht registriert
- ❌ Fehlende Spalten (`verified_at`, `last_login_at`, etc.)

### Fixes in v1.0.3.1 ✅
- ✅ Activation Hooks registriert
- ✅ Tabelle wird auch bei `init()` erstellt (robuster)
- ✅ Alle erforderlichen Spalten hinzugefügt
- ✅ Indizes optimiert

---

## 📋 Deployment-Schritte

### Option 1: Einfaches Copy-Paste (Empfohlen)

1. **Lokal**: Gehe in `c:\Users\nauma\OneDrive\Plugin_neu\churchtools-suite-demo`
2. **Kopiere den gesamten Ordner**
3. **Server**: Gehe zu `/wp-content/plugins/`
4. **Ersetze** den alten `churchtools-suite-demo` Ordner mit dem neuen
5. **WordPress Admin**: 
   - Plugins → ChurchTools Suite Demo
   - Deaktivieren → Aktivieren (triggert Tabellenerstellung)
6. **Fertig!** 🎉

---

### Option 2: Manuell per FTP

```bash
1. Stelle sicher, dass das Demo Plugin DEAKTIVIERT ist
2. Lösche den alten Ordner /wp-content/plugins/churchtools-suite-demo/
3. Lade den neuen Ordner hoch
4. Im Admin: Plugins → Aktivieren
```

---

### Option 3: SSH/Terminal (für Profis)

```bash
# SSH-Verbindung
ssh user@plugin.feg-aschaffenburg.de

# In Plugin-Verzeichnis gehen
cd /var/www/clients/client436/web2975/web/wp-content/plugins/

# Alten Ordner sichern (optional)
mv churchtools-suite-demo churchtools-suite-demo.backup

# Neuen Ordner hochladen (z.B. via SCP vorher)
# scp -r c:\Users\nauma\OneDrive\Plugin_neu\churchtools-suite-demo user@plugin.feg-aschaffenburg.de:/path/

# Rechte setzen
chown -R web2975:client436 churchtools-suite-demo/
chmod -R 755 churchtools-suite-demo/
```

---

## 🧪 Nach dem Update testen

1. **WordPress Admin öffnen**
2. **Plugins → ChurchTools Suite Demo** 
   - Status sollte ✅ "Aktiv" sein
   - Version sollte **1.0.3.1** sein
3. **Debug-Tab prüfen** (falls verfügbar)
   - Sollte keine Fehler zu `wp_cts_demo_users` anzeigen
4. **Demo-Registrierungen** testen
   - Registrierung ausfüllen
   - E-Mail-Bestätigung erhalten
   - Tabelle sollte jetzt Einträge haben

---

## 📊 Technische Details

### Was wurde gefixt?

#### Problem 1: Fehlende Activation Hooks
```php
// VORHER: Nichts registriert
// Tabelle wurde nie erstellt!

// NACHHER:
register_activation_hook( __FILE__, function() {
    churchtools_suite_demo()->activate();
});
```

#### Problem 2: Tabellenerstellung nur beim Activation
```php
// VORHER: Nur beim Activation
public function activate(): void {
    $this->create_tables();
}

// NACHHER: Auch beim normalen Init (robuster)
public function init(): void {
    $this->create_tables(); // ← Neu!
    // ... rest
}
```

#### Problem 3: Tabellen-Schema
```sql
-- NACHHER: Verbessert
CREATE TABLE IF NOT EXISTS wp_cts_demo_users (
    id ...,
    verified_at datetime,        -- ✨ NEU
    last_login_at datetime,      -- ✨ NEU
    updated_at datetime,         -- ✨ NEU
    KEY verified_at (verified_at),
    KEY created_at (created_at),
    KEY wordpress_user_id (wordpress_user_id)
)
```

---

## ⚠️ Wichtig!

- **Backup**: Mache vor dem Update ein Backup der Datenbank!
- **Test zuerst**: Test auf lokaler Installation vor dem Live-Deploy
- **Admin-Zugang**: Du brauchst FTP/SSH-Zugang zum Server

---

## 🆘 Wenn es immer noch nicht funktioniert

1. **Datenbank manuell erstellen** (letzte Hoffnung):
   ```sql
   CREATE TABLE IF NOT EXISTS wp_cts_demo_users (
       id bigint(20) unsigned NOT NULL AUTO_INCREMENT,
       email varchar(255) NOT NULL,
       name varchar(255) NOT NULL,
       organization varchar(255),
       purpose text,
       verification_token varchar(64) NOT NULL,
       is_verified tinyint(1) DEFAULT 0,
       verified_at datetime,
       wordpress_user_id bigint(20) unsigned,
       last_login_at datetime,
       created_at datetime DEFAULT CURRENT_TIMESTAMP,
       updated_at datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
       PRIMARY KEY (id),
       UNIQUE KEY email (email),
       UNIQUE KEY verification_token (verification_token),
       KEY is_verified (is_verified),
       KEY verified_at (verified_at),
       KEY created_at (created_at),
       KEY wordpress_user_id (wordpress_user_id)
   ) DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
   ```

2. **Plugin Debug Mode anschalten**:
   ```php
   // In wp-config.php hinzufügen:
   define('WP_DEBUG', true);
   define('WP_DEBUG_LOG', true);
   ```

3. **Logs prüfen**: `/wp-content/debug.log`

---

## 📝 Version-Info

| Version | Datum | Status | Changes |
|---------|-------|--------|---------|
| 1.0.3.1 | 12. Jan 2026 | ✅ Current | Activation Hooks + Table Robustness |
| 1.0.3   | 12. Jan 2026 | ⚠️ Broken | Missing table creation |
| 1.0.2   | 2025 | ✅ Stable | Initial release |

---

**Letzte Aktualisierung:** 12. Januar 2026 (v1.0.3.1)
