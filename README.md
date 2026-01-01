# ChurchTools Suite - Demo & Documentation Site

**Live Demo:** https://plugin.aschaffenburg.feg.de  
**Plugin Repository:** https://github.com/FEGAschaffenburg/churchtools-suite

---

## 📋 Über dieses Repository

Dieses Repository enthält die **komplette Demo- und Dokumentations-Website** für das ChurchTools Suite WordPress-Plugin.

### Inhalt:
- ✅ WordPress Custom Theme für die Demo-Site
- ✅ Vorgefertigte Seiten-Templates für alle Plugin-Features
- ✅ Automatisierte Setup-Scripts
- ✅ Dokumentation und Guides
- ✅ Live-Beispiele aller Shortcode-Varianten

---

## 🎯 Features

### Demo-Seiten
- **Calendar Demos** - Alle Kalender-Ansichten (Monthly, Weekly, Daily)
- **List Demos** - Alle Listen-Varianten (Classic, Modern, Fluent, Liquid)
- **Grid Demos** - Alle Raster-Ansichten (Simple, Modern, Colorful, Tile)
- **Slider Demos** - Alle Slider-Typen
- **Single Event** - Event-Detailseiten
- **Interactive Generator** - Live Shortcode-Generator

### Dokumentation
- **Installation Guide** - Schritt-für-Schritt Anleitung
- **Configuration** - Plugin-Einrichtung
- **Shortcode Reference** - Vollständige Shortcode-Dokumentation
- **Template Customization** - Theme-Anpassungen
- **API Documentation** - Entwickler-Dokumentation

---

## 🚀 Quick Start

### Voraussetzungen
- WordPress 6.0+
- PHP 8.0+
- ChurchTools Suite Plugin v0.9.4.9+
- ChurchTools-Instanz mit API-Zugriff

### Automatische Installation

```powershell
# 1. Repository klonen
git clone https://github.com/FEGAschaffenburg/churchtools-suite-demos.git
cd churchtools-suite-demos

# 2. Setup-Script ausführen
.\scripts\setup.ps1 -Domain "plugin.aschaffenburg.feg.de"
```

Das Script:
- ✅ Erstellt WordPress-Installation
- ✅ Installiert Theme
- ✅ Importiert alle Demo-Seiten
- ✅ Konfiguriert Menüs und Navigation

### Manuelle Installation

1. **WordPress installieren** auf deiner Domain

2. **Theme aktivieren**
   ```bash
   cp -r theme/cts-demo-theme /path/to/wordpress/wp-content/themes/
   ```

3. **Plugin installieren**
   - ChurchTools Suite v0.9.4.9+ hochladen
   - ChurchTools API konfigurieren

4. **Seiten importieren**
   ```bash
   wp import pages/demo-pages.xml --authors=create
   ```

---

## 📁 Repository-Struktur

```
churchtools-suite-demos/
├─ theme/
│  └─ cts-demo-theme/           # Custom WordPress Theme
│     ├─ style.css              # Theme-Stylesheet
│     ├─ functions.php          # Theme-Funktionen
│     ├─ header.php             # Header-Template
│     ├─ footer.php             # Footer-Template
│     ├─ page-templates/        # Seiten-Templates
│     │  ├─ demo-page.php       # Demo-Seiten-Template
│     │  └─ documentation.php   # Dokumentations-Template
│     └─ assets/
│        ├─ css/                # Custom CSS
│        └─ js/                 # Custom JavaScript
│
├─ pages/
│  ├─ demo-pages.xml            # WordPress XML Import
│  ├─ demos/                    # Demo-Seiten (Markdown)
│  │  ├─ calendar-demos.md
│  │  ├─ list-demos.md
│  │  ├─ grid-demos.md
│  │  └─ ...
│  └─ docs/                     # Dokumentation (Markdown)
│     ├─ installation.md
│     ├─ configuration.md
│     └─ shortcode-reference.md
│
├─ scripts/
│  ├─ setup.ps1                 # Automatisches Setup-Script
│  ├─ import-pages.ps1          # Seiten-Import
│  ├─ generate-demos.ps1        # Demo-Seiten generieren
│  └─ deploy.ps1                # Deployment-Script
│
├─ config/
│  ├─ menus.json                # WordPress Menü-Struktur
│  ├─ widgets.json              # Sidebar-Widgets
│  └─ settings.json             # WordPress-Settings
│
└─ README.md                    # Diese Datei
```

---

## 🎨 Theme-Features

Das **CTS Demo Theme** ist ein lightweight WordPress-Theme speziell für die Demo-Site:

- ✅ **Responsive Design** - Mobile-first
- ✅ **Fast Loading** - Minimale Dependencies
- ✅ **SEO-optimiert** - Semantic HTML, Schema.org
- ✅ **Syntax Highlighting** - Code-Beispiele mit Prism.js
- ✅ **Copy-to-Clipboard** - Shortcodes kopieren per Klick
- ✅ **Dark Mode** - Automatische Theme-Umschaltung
- ✅ **Breadcrumbs** - Navigation-Pfad
- ✅ **Related Demos** - Verwandte Beispiele am Ende jeder Seite

---

## 📝 Demo-Seiten erstellen

### Neue Demo-Seite hinzufügen

1. **Markdown-Datei erstellen**
   ```bash
   touch pages/demos/my-new-demo.md
   ```

2. **Frontmatter definieren**
   ```yaml
   ---
   title: "Calendar: Monthly Modern"
   shortcode: "[cts_calendar view=\"monthly-modern\"]"
   category: "calendar"
   difficulty: "beginner"
   tags: ["calendar", "modern", "responsive"]
   ---
   ```

3. **Content schreiben**
   ```markdown
   ## Beschreibung
   Die Monthly Modern Ansicht zeigt...
   
   ## Live-Demo
   <!-- shortcode-preview -->
   
   ## Parameter
   - `view`: "monthly-modern"
   - `calendar`: Kalender-IDs
   ...
   ```

4. **Generieren & Importieren**
   ```powershell
   .\scripts\generate-demos.ps1
   .\scripts\import-pages.ps1
   ```

---

## 🔧 Konfiguration

### WordPress Settings

**wp-config.php Empfehlungen:**
```php
// Debug (nur Development)
define('WP_DEBUG', false);
define('WP_DEBUG_LOG', false);

// Performance
define('WP_CACHE', true);
define('COMPRESS_CSS', true);
define('COMPRESS_SCRIPTS', true);

// Security
define('DISALLOW_FILE_EDIT', true);
```

### Theme Settings

**functions.php Customization:**
```php
// In theme/cts-demo-theme/functions.php
add_filter('cts_demo_site_config', function($config) {
    $config['show_code_preview'] = true;
    $config['enable_copy_button'] = true;
    $config['syntax_highlighting'] = 'prism';
    return $config;
});
```

---

## 🚀 Deployment

### Automatisches Deployment (GitHub Actions)

```yaml
# .github/workflows/deploy.yml wird automatisch erstellt
```

**Workflow:**
1. Push zu `main` branch
2. GitHub Action triggered
3. Automatischer Deploy zu `plugin.aschaffenburg.feg.de`
4. Cache-Clearing
5. Sitemap-Update

### Manuelles Deployment

```powershell
.\scripts\deploy.ps1 -Environment "production" -Target "plugin.aschaffenburg.feg.de"
```

---

## 📊 Wartung

### Updates

**Plugin-Updates:**
```bash
# ChurchTools Suite aktualisieren
wp plugin update churchtools-suite
```

**Demo-Seiten aktualisieren:**
```powershell
# Neue Demo-Seiten generieren
.\scripts\generate-demos.ps1

# Import
.\scripts\import-pages.ps1
```

**Theme-Updates:**
```bash
git pull origin main
cp -r theme/cts-demo-theme /path/to/wordpress/wp-content/themes/
```

---

## 🤝 Contributing

Beiträge sind willkommen! Siehe [CONTRIBUTING.md](CONTRIBUTING.md)

### Development Workflow

1. **Fork** das Repository
2. **Branch** erstellen: `git checkout -b feature/new-demo`
3. **Änderungen** committen: `git commit -m 'Add new calendar demo'`
4. **Push** zu Branch: `git push origin feature/new-demo`
5. **Pull Request** erstellen

---

## 📄 Lizenz

GPL v2 or later - siehe [LICENSE](../LICENSE)

---

## 🔗 Links

- **Plugin Repository:** https://github.com/FEGAschaffenburg/churchtools-suite
- **Live Demo:** https://plugin.aschaffenburg.feg.de
- **ChurchTools:** https://www.church.tools/
- **Support:** https://github.com/FEGAschaffenburg/churchtools-suite/issues

---

## 📧 Kontakt

**FEG Aschaffenburg**  
GitHub: [@FEGAschaffenburg](https://github.com/FEGAschaffenburg)

---

**Version:** 1.0.0  
**Last Updated:** Januar 2026
