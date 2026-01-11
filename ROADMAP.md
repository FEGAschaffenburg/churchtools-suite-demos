# ChurchTools Suite - Roadmap

> **Aktueller Stand:** v0.9.0 (Januar 2026)  
> **Status:** Pre-Release - Clean Slate Version

---

## 🎯 Vision

ChurchTools Suite ist eine umfassende WordPress-Integration für ChurchTools, die es Gemeinden ermöglicht, ihre Termine, Kalender und Services nahtlos auf ihrer Website zu präsentieren.

---

## ✅ Abgeschlossen

### v0.1.0 - v0.9.5.2: Core Features & Templates
- ✅ Cookie-basierte ChurchTools API-Authentifizierung
- ✅ Repository-Pattern für Datenzugriff
- ✅ 2-Phasen Event-Sync (Events + Appointments)
- ✅ Admin UI (Dashboard, Settings, Calendars, Events, Sync, Debug)
- ✅ Migration System (DB-Versionierung bis 2.2)
- ✅ Service Groups & Services Synchronisation
- ✅ Event Services Import
- ✅ Template System mit 13 Shortcode-Handlern
- ✅ Gutenberg Block & Elementor Integration
- ✅ Incremental Sync mit Deleted Events Detection
- ✅ Plugin-eigenes Logging System
- ✅ Clickable Events mit Modal Details
- ✅ Appointment Change Tracking
- ✅ Composite Unique Key (appointment_id + start_datetime)
- ✅ Separate Descriptions (Event vs. Appointment)
- ✅ Address Details & Tags Support
- ✅ Demo Mode mit realistischen Events

### v0.9.0: Clean Slate (AKTUELL)
**Ziel:** Neustart mit minimalem Feature-Set

**Änderungen:**
- ✅ Demo-Features in separates Plugin ausgelagert (churchtools-suite-demo)
- ✅ Filter-Hook `churchtools_suite_get_events` für Erweiterbarkeit
- ✅ Demo Data Provider bleibt, aber wird nur via Filter aktiviert
- ✅ Migration 2.3 (demo_users) entfernt
- ✅ Demo-Repository/Service-Klassen entfernt
- ✅ Production Plugin bereinigt für echte Gemeinden

**Deployment:**
- Production Plugin: Git + GitHub Releases
- Demo Plugin: SSH-only (KEIN Git)
- Demo Pages: SSH-only (KEIN Git)

---

## � Nächste Schritte

### v0.9.1: Template Rollout (Nächstes)
**Ziel:** Schrittweise Reaktivierung der Templates

**Phase 1: Listen-Views**
- [ ] List/Medium aktivieren
- [ ] List/Extended aktivieren
- [ ] Tests & Bugfixes

**Phase 2: Grid-Views**
- [ ] Grid/Simple aktivieren
- [ ] Grid/Modern aktivieren
- [ ] Tests & Bugfixes

**Phase 3: Calendar-Views**
- [ ] Calendar/Monthly aktivieren
- [ ] Calendar/Weekly aktivieren
- [ ] Tests & Bugfixes

---

## 📋 Backlog (Post-v1.0)

### Advanced Filtering
- Calendar-Filter in Shortcodes
- Datum-Range Filter
- Service-Filter
- Text-Search

### Performance Optimizations
- Batch Event Processing
- API Response Caching
- Query Optimization

### Extended Admin
- Shortcode Presets
- Visual Builder
- Statistics Dashboard

---

## 🎓 Ressourcen

**Dokumentation:**
- [ChurchTools API Docs](https://api.church.tools/)
- [WordPress Plugin Handbook](https://developer.wordpress.org/plugins/)

**Tools:**
- [WP-CLI](https://wp-cli.org/)
- [Query Monitor](https://querymonitor.com/)

---

**Letzte Aktualisierung:** Januar 2026 (v0.9.0 - Clean Slate)
