# ChurchTools Suite Demo - Changelog

## v1.0.5.0 - Full Demo Mode (12. Januar 2026)

### ✨ Features
- **Demo-Kalender automatisch erstellen** - 6 Kalender werden bei Aktivierung angelegt
- **Sync-Simulation** - Kalender- und Event-Sync werden abgefangen und simuliert
- **Konfigurationsschutz** - Verhindert Änderungen an Settings/Kalenderauswahl
- **Admin-Hinweis** - Zeigt Demo-Modus-Banner im Admin
- **API-Verbindungstest simuliert** - Test Connection zeigt Erfolg ohne echte API

### 🔧 Änderungen
- Alle Sync-AJAX-Hooks werden abgefangen (Priority 1)
- Settings-Änderungen blockiert mit Fehlermeldung
- Demo-Kalender in DB persistent (ID 1-6)

---

## v1.0.4.2 - Compatibility Update (12. Januar 2026)

### 🔧 Änderungen
- Entfernt ungenutzten `CTS_DEMO_MODE` Check (Demo-Events kommen jetzt ausschließlich aus der DB)
- Kompatibilität mit ChurchTools Suite v1.0.3.3+

---

## v1.0.4.1 - Bugfixes (12. Januar 2026)

### 🐛 Bugfixes
- Demo User Auto-Creation verbessert
- Aktivierungs-Hooks korrigiert

---

## v1.0.4.0 - Event Persistence (12. Januar 2026)

### ✨ Features
- Demo-Events werden bei Aktivierung in DB geschrieben (persistent)
- Verwendet main plugin Events Repository
- 70+ Demo-Events für 90 Tage
- Fallback zu On-the-fly Generation falls DB leer

---

## v1.0.3.1 - Initial Release

### ✨ Features
- Demo-Registrierung für Benutzer
- Demo-User automatisch erstellt
- Backend-Zugang für Demo-Manager
