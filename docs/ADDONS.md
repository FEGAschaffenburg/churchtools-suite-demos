# ChurchTools Suite - Addons

## Übersicht

ChurchTools Suite kann mit Addons erweitert werden. Addons sind eigenständige Plugins, die zusätzliche Funktionalität bereitstellen.

## 📋 Verfügbare Addons

### ⚡ Elementor Integration (v0.5.3)

**Status:** ✅ Verfügbar  
**GitHub:** https://github.com/FEGAschaffenburg/churchtools-suite-elementor  
**Download:** https://github.com/FEGAschaffenburg/churchtools-suite-elementor/releases/latest

#### Features

- **ChurchTools Events Widget** für Elementor Page Builder
- **28+ Kontrollparameter** in 6 Kategorien
  - Content (Kalender, Tags, Zeitrahmen, Limit)
  - Filters (Services, Past Events)
  - Display (Beschreibungen, Location, Tags, Services)
  - Grid (Columns, Gap, Alignment)
  - Style (Colors, Typography, Spacing)
  - Advanced (CSS, Wrapper)
- **3 View-Typen:**
  - List (4 Templates: classic, classic-with-images, minimal, modern)
  - Grid (2 Templates: simple, modern)
  - Calendar (monthly)
- **Live-Preview** im Elementor Editor
- **Shortcode-Wrapper** Architektur (re-use Main Plugin)
- **Dependency Checks** (ChurchTools Suite, Elementor)

#### Installation

##### One-Click Installation (empfohlen)
1. WordPress Admin → **ChurchTools → Addons**
2. Klicke auf **"⚡ Jetzt installieren"**
3. Plugin wird automatisch heruntergeladen, installiert und aktiviert

##### Manuelle Installation
1. [Download v0.5.3](https://github.com/FEGAschaffenburg/churchtools-suite-elementor/releases/download/v0.5.3/churchtools-suite-elementor-0.5.3.zip)
2. WordPress Admin → Plugins → Installieren → ZIP hochladen
3. Plugin aktivieren

#### Verwendung

1. Seite in Elementor bearbeiten
2. Widget-Panel öffnen (linke Sidebar)
3. **"ChurchTools Suite"** Kategorie finden
4. **"ChurchTools Events"** Widget per Drag & Drop auf die Seite ziehen
5. Widget-Einstellungen im linken Panel anpassen

#### Voraussetzungen

- **ChurchTools Suite:** >= v1.0.9.0
- **Elementor:** >= v3.0.0 (Free reicht)
- **WordPress:** >= v6.0
- **PHP:** >= v8.0

#### Changelog

**v0.5.3** (15. Februar 2025)
- 🐛 Fix: Build-Script repariert (ZIP enthielt leere Dateien)
- ✅ ZIP enthält jetzt 42 KB Inhalt (vorher: 0 Bytes)
- ✅ One-Click Installation funktioniert korrekt

**v0.5.2** (DEFEKT - nicht verwenden)
- ❌ ZIP hatte korrekte Ordnerstruktur, aber leere Dateien (0 Bytes)

**v0.5.0** (13. Februar 2025)
- 🎉 Initial Beta Release
- ⚡ Elementor Widget mit 28+ Parametern
- 🎨 3 View-Typen (List, Grid, Calendar)
- 🔧 Sub-Plugin System mit Hook-Support

---

## 🔮 Geplante Addons

### 🎨 Visual Composer Integration
**Status:** 🚧 In Planung  
**Beschreibung:** WPBakery Page Builder Integration mit Custom Elements

### ⚙️ Beaver Builder Integration
**Status:** 🚧 In Planung  
**Beschreibung:** Beaver Builder Modul für ChurchTools Events

### 🔔 Notifications Addon
**Status:** 🚧 In Planung  
**Beschreibung:** E-Mail und Push Benachrichtigungen für Events

### 📱 Mobile App Connector
**Status:** 🚧 In Planung  
**Beschreibung:** Native Mobile App mit ChurchTools Sync

### 📊 Analytics Addon
**Status:** 🚧 In Planung  
**Beschreibung:** Event-Statistiken und Teilnehmer-Tracking

### 🎯 Advanced Filters
**Status:** 🚧 In Planung  
**Beschreibung:** Erweiterte Filter- und Such-Funktionen

---

## 📖 Addon-Entwicklung

### Architektur

Addons folgen dem **Sub-Plugin Pattern**:

1. **Eigenständiges Plugin** mit eigener `plugin-name.php` Datei
2. **Dependency auf ChurchTools Suite** via `Requires Plugins:` Header
3. **Hook-basierte Integration** via `churchtools_suite_loaded` Hook
4. **Namespace-Trennung** (z.B. `CTS_Elementor_*`)

### Beispiel: Plugin-Header

```php
<?php
/**
 * Plugin Name:       ChurchTools Suite - My Addon
 * Plugin URI:        https://github.com/YourOrg/churchtools-suite-my-addon
 * Description:       My awesome addon for ChurchTools Suite
 * Version:           1.0.0
 * Requires at least: 6.0
 * Requires PHP:      8.0
 * Requires Plugins:  churchtools-suite
 * Author:            Your Name
 * Author URI:        https://yourwebsite.com
 * License:           GPL-3.0-or-later
 * License URI:       https://www.gnu.org/licenses/gpl-3.0.html
 * Text Domain:       churchtools-suite-my-addon
 * Domain Path:       /languages
 */
```

### Beispiel: Hook-Integration

```php
<?php
// Warte auf churchtools_suite_loaded Hook
add_action( 'churchtools_suite_loaded', function() {
    // Dein Addon-Code hier
    // Main Plugin ist geladen und verfügbar
    
    if ( class_exists( 'ChurchTools_Suite' ) ) {
        // Initialisiere dein Addon
        My_Addon::init();
    }
}, 10 );

// Alternative: Late initialization support (falls Hook bereits gefeuert)
if ( did_action( 'churchtools_suite_loaded' ) || 
     ( isset( $GLOBALS['churchtools_suite_plugin_instance'] ) && 
       $GLOBALS['churchtools_suite_plugin_instance'] instanceof ChurchTools_Suite ) ) {
    // Hook wurde bereits gefeuert, sofort initialisieren
    My_Addon::init();
}
```

### Best Practices

1. **Namespace:** Verwende eindeutige Präfixe (z.B. `CTS_MyAddon_`)
2. **Dependencies:** Prüfe Abhängigkeiten im Code (nicht nur im Header)
3. **Deactivation:** Cleanup bei Deaktivierung
4. **Uninstall:** Daten-Cleanup bei Deinstallation (optional)
5. **GitHub Releases:** Verwende GitHub Releases für einfache Updates
6. **One-Click Install:** Registriere dein Addon in der Addons-Seite

### Addon-Registration

Um dein Addon in der ChurchTools → Addons Seite zu registrieren:

```php
// In admin/class-churchtools-suite-admin.php
private static $addon_repos = [
    'churchtools-suite-elementor' => 'FEGAschaffenburg/churchtools-suite-elementor',
    'churchtools-suite-my-addon' => 'YourOrg/churchtools-suite-my-addon', // <-- Füge hier hinzu
];
```

---

## 📚 Ressourcen

### Main Plugin
- **Repository:** https://github.com/FEGAschaffenburg/churchtools-suite
- **Dokumentation:** https://github.com/FEGAschaffenburg/churchtools-suite/blob/main/README.md
- **Issues:** https://github.com/FEGAschaffenburg/churchtools-suite/issues

### Elementor Addon
- **Repository:** https://github.com/FEGAschaffenburg/churchtools-suite-elementor
- **Releases:** https://github.com/FEGAschaffenburg/churchtools-suite-elementor/releases
- **Changelog:** https://github.com/FEGAschaffenburg/churchtools-suite-elementor/blob/master/CHANGELOG.md

### Demo-Homepage
- **HTML-Demo:** [addons-overview.html](./addons-overview.html)
- **Live-Demo:** https://churchtools-suite.demo (bald verfügbar)

---

## 🤝 Contribution

Möchtest du ein Addon entwickeln?

1. **Fork** das [ChurchTools Suite Repository](https://github.com/FEGAschaffenburg/churchtools-suite)
2. **Erstelle** ein neues Repository für dein Addon
3. **Folge** dem Sub-Plugin Pattern (siehe oben)
4. **Erstelle** einen Pull Request, um dein Addon zu registrieren
5. **Dokumentiere** dein Addon in dieser Datei

---

## 📄 Lizenz

Alle ChurchTools Suite Addons sind unter der **GPL-3.0-or-later** Lizenz verfügbar.

- **Kostenlos** für private und kommerzielle Nutzung
- **Open Source** auf GitHub
- **Keine Garantie** (siehe Lizenztext)
- **Community-getrieben**

---

## 🆘 Support

**Fragen oder Probleme?**

1. **Suche** in den [GitHub Issues](https://github.com/FEGAschaffenburg/churchtools-suite/issues)
2. **Erstelle** ein neues Issue mit detaillierter Beschreibung
3. **Diskutiere** in den GitHub Discussions (bald verfügbar)

**Entwickler-Support:**

- E-Mail: [support@feg-aschaffenburg.de](mailto:support@feg-aschaffenburg.de)
- GitHub: [@FEGAschaffenburg](https://github.com/FEGAschaffenburg)
