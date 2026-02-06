# ChurchTools Suite Demo - Rollen & Berechtigungen (v1.0.7.0+)

**Version:** 1.0.7.0+  
**Status:** Überarbeitete Demo-User Struktur  
**Zielgruppe:** Demo-User, Admin

---

## 🎯 Überblick

Die Demo erlaubt es, die ChurchTools Suite **sicher zu testen**, ohne dass Demo-User versehentlich etwas ändern können.

### Rollen im Demo:

| Rolle | Nutzung | Zugriff |
|-------|---------|--------|
| **Administrator** | Vollverwaltung | ✅ Alles (WordPress + ChurchTools) |
| **cts_demo_user** | Eingeladene Demo-Tester | ✅ Dashboard anschauen + eigene Test-Seiten |

---

## 👤 cts_demo_user Rolle

### Was Demo-User KÖNNEN:

✅ **ChurchTools Suite Dashboard anschauen**
- Events sehen
- Kalender sehen
- Services sehen
- Debug-Infos (optional)

✅ **Eigene Test-Seiten erstellen** (CPT: `cts_demo_page`)
- Neue Demo Pages anlegen
- Mit Shortcodes experimentieren
- Events-Anzeige testen

### Was Demo-User NICHT können:

❌ **Keine Einstellungs-Änderungen:**
- ❌ ChurchTools API konfigurieren
- ❌ Kalender synchronisieren
- ❌ Services konfigurieren
- ❌ Debug-Modus aktivieren

❌ **Keine Fremden-Seiten bearbeiten:**
- ❌ Seiten von anderen Demo-Usern sehen
- ❌ WordPress-Einstellungen ändern
- ❌ Plugins/Themes verwalten
- ❌ Menüs bearbeiten

---

## 📝 Demo Pages (CPT)

### Was sind Demo Pages?

**Demo Pages** sind ein spezieller Seiten-Typ (Custom Post Type) für Demo-User zum Testen.

```
ChurchTools Suite
├── Dashboard        (Lesen)
├── Demo Pages       (Erstellen/Bearbeiten/Löschen - nur eigene!)
│   ├── Meine Test-Seite 1  (erstellt von demo-user@example.com)
│   ├── Meine Test-Seite 2  (erstellt von demo-user@example.com)
│   ├── Andere Test-Seite   (erstellt von demo-user-2@example.com)
└── (weitere ChurchTools Tabs - NUR ANSCHAUEN)
```

### Eigenschaften:

✅ **Privat:**
- Nicht öffentlich sichtbar
- Nur für Admin + Creator sichtbar

✅ **User-isoliert:**
- Demo-User sieht nur SEINE OWN Seiten
- Nicht die von anderen Demo-Usern
- Admin sieht alle

✅ **Auto-Cleanup:**
- Wenn Demo-User gelöscht wird → alle seine Demo Pages gelöscht
- Keine Waisenseiten in der Datenbank

✅ **Zum Testen ideal:**
- Shortcodes experimentieren: `[cts_list view="classic"]`
- Event-Anzeige testen
- Templates ausprobieren

---

## 🔑 Capabilities im Demo

```php
// cts_demo_user hat genau diese Capabilities:
[
    'read' => true,  // Grundlegend
    
    // ChurchTools Suite - READ-ONLY
    'manage_churchtools_suite' => true,      // Dashboard sehen
    'view_churchtools_debug' => false,       // KEINE Debug-Infos
    'manage_churchtools_calendars' => false, // KEINE Changes
    'configure_churchtools_suite' => false,  // KEINE API-Config
    'sync_churchtools_events' => false,      // KEINE Sync
    'manage_churchtools_services' => false,  // KEINE Services-Config
    
    // Demo Pages - Full control (nur eigene!)
    'manage_cts_demo_pages' => true,  // Erstellen
    'edit_cts_demo_page' => true,     // Bearbeiten
    'delete_cts_demo_page' => true,   // Löschen
    'view_cts_demo_pages' => true,    // Anschauen
]
```

---

## 👥 Mehrere Demo-User gleichzeitig

### Szenario: 3 Demo-User testen gleichzeitig

```
Demo User 1: demo1@example.com (cts_demo_user)
├── Meine Demo Pages
│   ├── Homepage Test
│   └── Events-Listing Test
└── Dashboard (READ-ONLY)

Demo User 2: demo2@example.com (cts_demo_user)
├── Meine Demo Pages
│   └── Custom Template Test
└── Dashboard (READ-ONLY)

Demo User 3: demo3@example.com (cts_demo_user)
├── Meine Demo Pages
│   ├── Shortcode Test
│   └── Grid Layout Test
└── Dashboard (READ-ONLY)
```

**Isolation:**
- Demo User 1 sieht NICHT die Pages von Demo User 2 & 3
- Jeder hat seinen eigenen isolierten Bereich
- Keine gegenseitige Beeinflussung

---

## 🔄 User-Lifecycle

### 1. Demo-Registrierung

```php
// In: includes/services/class-demo-registration-service.php
$user_id = wp_create_user(
    $email,
    $password,
    $email
);

$user = new WP_User( $user_id );
$user->set_role( 'cts_demo_user' );  // Role setzen
```

**Ergebnis:**
- WordPress User erstellt
- Role `cts_demo_user` zugewiesen
- Nur ChurchTools Suite sichtbar
- Keine Demo Pages vorhanden

### 2. Demo-Benutzung

```
WordPress Admin Login
↓
ChurchTools Suite Dashboard (READ-ONLY, kein Zugriff auf Settings)
↓
"Demo Pages" Tab → "Neue Demo Page" klicken
↓
Seite erstellen + Shortcodes einfügen
↓
Speichern → Seite testet Events-Anzeige
↓
Weitere Pages erstellen, experimentieren
```

### 3. User-Löschung

```php
// WordPress Hook: delete_user
// In: class-demo-template-cpt.php
delete_user_demo_pages( int $user_id ): void {
    // 1. Alle Demo Pages des Users finden
    // 2. Force delete alle (skip trash)
    // 3. Fertig - keine Waisenseiten
}
```

**Ergebnis:**
- User gelöscht
- ✅ Alle seine Demo Pages gelöscht
- ✅ Sauberer Zustand
- ✅ Keine Datenmüll

---

## 🛡️ Sicherheit

### Isolation:

| Aktion | cts_demo_user | Administrator |
|--------|---------------|----------------|
| Sieht andere Users | ❌ | ✅ |
| Bearbeitet fremde Demo Pages | ❌ | ✅ |
| Ändert Einstellungen | ❌ | ✅ |
| Liest API-Credentials | ❌ | ✅ |
| Führt Event-Sync aus | ❌ | ✅ |

### Audit-Trail:

Die `wp_cts_demo_users` Tabelle logged:
- Wer registriert hat (Email)
- Wann registriert (created_at)
- Ob verifiziert (verified_at)
- Letzter Login (last_login_at)
- Status (active/expired/deleted)

---

## 📋 Praktische Beispiele

### Beispiel 1: Demo-User erstellt Test-Seite

```
1. Admin laden → Demo Pages Tab
2. "Neue Demo Page" klicken
3. Titel: "Events Listing Test"
4. Content:
   [cts_list view="classic" limit="5"]
5. Speichern
6. Seite sehen im Frontend (nur für Admin/Creator sichtbar)
7. Events werden angezeigt!
```

### Beispiel 2: Demo-User soll Settings nicht sehen

```
Demo-User hat NO Capability für:
❌ ChurchTools Suite → Einstellungen
❌ ChurchTools Suite → Synchronisation
❌ ChurchTools Suite → Kalender
❌ ChurchTools Suite → Services

✅ ChurchTools Suite → Dashboard (READ-ONLY)
✅ ChurchTools Suite → Demo Pages (Vollzugriff auf eigene)
```

### Beispiel 3: Mehrere Demo-User, kein Chaos

```
Demo User A erstellt: "Meine Test-Seite"
Demo User B erstellt: "Meine Test-Seite"
Demo User C erstellt: "Meine Test-Seite"

Sie sehen NICHT gegenseitig:
❌ User A sieht B's Seiten nicht
❌ User B sieht C's Seiten nicht
❌ User C sieht A's Seiten nicht

Admin sieht ALLES:
✅ Admin sieht alle 3 Seiten
✅ Admin kann moderieren
✅ Admin kann löschen
```

---

## 🔧 Konfiguration

### Auto-Cleanup (optional)

Unverifizierte Demo-User nach 7 Tagen löschen:

```php
// In: churchtools-suite-demo.php
add_action( 'churchtools_suite_demo_cleanup_unverified', function() {
    // Löscht alle nicht verifizierten User älter als 7 Tage
});
```

### Demo-Seite Templates

```php
// Schnellstart-Template für neue Demo Pages
// In: wp_content/plugins/churchtools-suite-demo/templates/demo-page-template.php

// Option A: Liste
[cts_list view="classic" limit="10"]

// Option B: Grid
[cts_grid view="simple" limit="9"]

// Option C: Kalender
[churchtools_events view="monthly-clean"]
```

---

## 📚 Weitere Info

- [ROLES-AND-CAPABILITIES-v2.md](../../churchtools-suite/docs/ROLES-AND-CAPABILITIES-v2.md) – Rollen im Hauptplugin
- [README.md](./README.md) – Demo-Plugin Überblick
- [DEPLOYMENT-INSTRUCTIONS.md](./DEPLOYMENT-INSTRUCTIONS.md) – Setup-Anleitung

---

**Version:** 1.0.7.0  
**Letztes Update:** 13. Januar 2026

