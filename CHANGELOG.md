# Changelog - ChurchTools Suite Demo Site

Alle bedeutenden Änderungen an diesem Projekt werden in dieser Datei dokumentiert.

Das Format basiert auf [Keep a Changelog](https://keepachangelog.com/de/1.0.0/),
und dieses Projekt folgt [Semantic Versioning](https://semver.org/lang/de/).

## [Unreleased]

### Geplant
- Weitere Demo-Varianten (slider, countdown, cover)
- Weitere Dokumentations-Seiten (templates, customization, troubleshooting)
- Interaktiver Shortcode-Generator
- Dark Mode Toggle
- Suche-Funktionalität

## [1.0.0] - 2025-12-29

### Hinzugefügt
- **WordPress Theme "CTS Demo Theme"**
  - Vollständiges responsive Design
  - CSS-Variablen für einfaches Theming
  - Breadcrumb-Navigation
  - Syntax-Highlighting mit Prism.js
  - Copy-to-Clipboard Funktionalität
  
- **Page Templates**
  - `demo-page.php` - Template für Demo-Seiten mit Live-Preview
  - `documentation.php` - Template für Dokumentations-Seiten mit Sidebar
  - `index.php` - Homepage mit Hero-Section und Features
  - `page.php` - Standard-Seiten-Template
  
- **Demo Content**
  - Kalender Demo: Monthly Modern
  - Liste Demo: Classic
  - Grid Demo: Simple
  
- **Dokumentation**
  - Installation-Guide
  - Konfigurations-Anleitung
  
- **Automatisierung**
  - `setup.ps1` - WordPress Theme-Installation und Page-Setup
  - `import-pages.ps1` - Markdown-to-WordPress Import
  - GitHub Actions Deployment-Workflow
  
- **Developer-Tools**
  - Meta-Boxes für Demo-Einstellungen (category, shortcode, difficulty)
  - Helper-Funktionen (breadcrumbs, code blocks, related demos)
  - Template-Override-System
  
- **Dokumentation**
  - README.md mit Quick Start und Features
  - CONTRIBUTING.md mit Contribution Guidelines
  - .github/DEPLOYMENT.md mit GitHub Actions Setup
  - .gitignore für WordPress-Projekte

### Technische Details
- WordPress 6.0+ Kompatibilität
- PHP 8.0+ Anforderungen
- Mobile-First Responsive Design
- SEO-optimiert (HTML5, Semantic Markup)
- Accessibility-Features (ARIA-Labels, Keyboard-Navigation)

## Version-History

### Versions-Schema

```
MAJOR.MINOR.PATCH

MAJOR - Breaking Changes (Theme-Struktur, API-Änderungen)
MINOR - Neue Features (neue Templates, Demos, Funktionen)
PATCH - Bugfixes, kleine Verbesserungen
```

### Zukünftige Releases

**v1.1.0** (geplant: Januar 2025)
- Weitere Demo-Varianten (5+ neue Demos)
- Erweiterte Dokumentation (10+ Seiten)
- Interaktiver Shortcode-Generator
- Performance-Optimierungen

**v1.2.0** (geplant: Februar 2025)
- Dark Mode Support
- Suche-Funktionalität
- Comments-System für Demos
- Rating-System

**v2.0.0** (geplant: März 2025)
- Gutenberg-Block Integration für Demo-Site
- Erweiterte Template-Varianten
- Multi-Language Support (EN/DE)

## Mitwirken

Siehe [CONTRIBUTING.md](CONTRIBUTING.md) für Details, wie du zu diesem Projekt beitragen kannst.

## Links

- **Repository**: https://github.com/FEGAschaffenburg/churchtools-suite-demos
- **Plugin Repository**: https://github.com/FEGAschaffenburg/churchtools-suite
- **Live Demo**: https://plugin.aschaffenburg.feg.de (coming soon)

---

**Format**: [Keep a Changelog](https://keepachangelog.com/de/1.0.0/)  
**Versioning**: [Semantic Versioning](https://semver.org/lang/de/)
