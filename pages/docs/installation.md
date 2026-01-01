---
title: Installation
excerpt: Schritt-für-Schritt Anleitung zur Installation von ChurchTools Suite
---

# Installation

Diese Anleitung führt dich durch die Installation des ChurchTools Suite Plugins in deinem WordPress-System.

## Systemvoraussetzungen

Bevor du beginnst, stelle sicher, dass dein System die folgenden Anforderungen erfüllt:

- **WordPress**: Version 6.0 oder höher
- **PHP**: Version 8.0 oder höher
- **MySQL**: Version 5.7 oder höher (oder MariaDB 10.3+)
- **ChurchTools**: Zugang zu einer ChurchTools-Instanz mit API-Zugriff

## Installationsmethoden

### Methode 1: WordPress Admin (Empfohlen)

1. **Plugin herunterladen**
   - Lade die aktuelle Version von der [Download-Seite](/download/) herunter
   - Du erhältst eine ZIP-Datei: `churchtools-suite-x.x.x.zip`

2. **Plugin hochladen**
   - Gehe zu **Plugins → Installieren** in deinem WordPress-Admin
   - Klicke auf **Plugin hochladen**
   - Wähle die heruntergeladene ZIP-Datei
   - Klicke auf **Jetzt installieren**

3. **Plugin aktivieren**
   - Nach der Installation klicke auf **Plugin aktivieren**
   - Du wirst zur Plugin-Seite weitergeleitet

4. **Fertig!**
   - Das Plugin ist jetzt installiert und aktiviert
   - Fahre fort mit der [Konfiguration](/documentation/configuration/)

### Methode 2: FTP/SFTP Upload

Wenn du direkten Server-Zugriff hast:

1. **Entpacke die ZIP-Datei** auf deinem lokalen Computer

2. **Verbinde dich mit deinem Server** via FTP/SFTP

3. **Upload**
   - Navigiere zu `wp-content/plugins/`
   - Lade den entpackten Ordner `churchtools-suite` hoch

4. **Aktiviere das Plugin**
   - Gehe zu **Plugins** im WordPress-Admin
   - Finde "ChurchTools Suite"
   - Klicke auf **Aktivieren**

### Methode 3: WP-CLI

Für fortgeschrittene Benutzer mit Server-Zugriff:

```bash
# ZIP-Datei installieren
wp plugin install /path/to/churchtools-suite-x.x.x.zip

# Plugin aktivieren
wp plugin activate churchtools-suite
```

## Nach der Installation

### Automatische Datenbank-Migration

Beim ersten Aktivieren erstellt das Plugin automatisch:

- **Datenbank-Tabellen** für Kalender, Events, Services
- **WordPress-Optionen** für Plugin-Einstellungen
- **Cron-Jobs** für automatische Synchronisation

Du siehst keine Fehlermeldung? Dann war die Installation erfolgreich! ✅

### Plugin-Seite öffnen

Nach der Aktivierung findest du das Plugin im WordPress-Admin:

```
WordPress Admin → ChurchTools Suite
```

Oder direkt via URL:

```
https://deine-domain.de/wp-admin/admin.php?page=churchtools-suite
```

## Nächste Schritte

Nach erfolgreicher Installation:

1. **[ChurchTools API konfigurieren](/documentation/configuration/)** - Verbinde dein ChurchTools-System
2. **[Kalender synchronisieren](/documentation/configuration/#kalender-sync)** - Importiere deine Kalender
3. **[Shortcodes verwenden](/documentation/shortcodes/)** - Zeige Events auf deiner Website

## Häufige Probleme

### "Plugin konnte nicht installiert werden"

**Ursache**: Unzureichende Dateiberechtigungen oder zu wenig Speicher

**Lösung**:
- Prüfe Schreibrechte im Ordner `wp-content/plugins/`
- Erhöhe `upload_max_filesize` in `php.ini` (empfohlen: 64M)
- Erhöhe `post_max_size` in `php.ini` (empfohlen: 64M)

### "Fatal Error" nach Aktivierung

**Ursache**: PHP-Version zu alt oder fehlende Abhängigkeiten

**Lösung**:
- Prüfe PHP-Version: Mindestens PHP 8.0 erforderlich
- Prüfe WordPress-Version: Mindestens 6.0 erforderlich
- Kontaktiere deinen Hosting-Provider für PHP-Upgrade

### "Header already sent" Fehler

**Ursache**: Leerzeichen oder Zeichen vor `<?php` in Theme-Dateien

**Lösung**:
- Prüfe `functions.php` deines Themes
- Entferne Leerzeichen/Zeilenumbrüche vor `<?php`
- Deaktiviere andere Plugins zur Fehlersuche

## Update-Installation

### Automatisches Update (Empfohlen)

Das Plugin unterstützt automatische Updates:

1. **Update-Benachrichtigung** erscheint im WordPress-Admin
2. **Klicke auf "Jetzt aktualisieren"**
3. **Automatischer Download und Installation**
4. **Fertig!**

### Manuelles Update

Wenn automatische Updates nicht funktionieren:

1. **Backup erstellen** (Datenbank + Dateien)
2. **Plugin deaktivieren** (NICHT löschen!)
3. **Neue Version hochladen** (überschreibt alte Dateien)
4. **Plugin aktivieren**
5. **Migrations laufen automatisch**

### Update via WP-CLI

```bash
# Verfügbare Updates anzeigen
wp plugin list --update=available

# ChurchTools Suite aktualisieren
wp plugin update churchtools-suite

# Alle Plugins aktualisieren
wp plugin update --all
```

## Deinstallation

### Plugin vollständig entfernen

1. **Gehe zu Plugins** im WordPress-Admin
2. **Deaktiviere** ChurchTools Suite
3. **Klicke auf "Löschen"**
4. **Bestätige die Löschung**

⚠️ **Wichtig**: Alle Daten (Events, Kalender, Einstellungen) werden gelöscht!

### Nur deaktivieren (Daten behalten)

Wenn du das Plugin nur temporär deaktivieren möchtest:

1. **Gehe zu Plugins**
2. **Klicke auf "Deaktivieren"**
3. **Daten bleiben erhalten**

Bei späterer Reaktivierung sind alle Daten noch vorhanden.

## Support

Brauchst du Hilfe bei der Installation?

- **[GitHub Issues](https://github.com/FEGAschaffenburg/churchtools-suite/issues)** - Melde Probleme
- **[GitHub Discussions](https://github.com/FEGAschaffenburg/churchtools-suite/discussions)** - Stelle Fragen
- **[Troubleshooting-Guide](/documentation/troubleshooting/)** - Häufige Probleme lösen

---

**Nächster Schritt**: [Konfiguration →](/documentation/configuration/)
