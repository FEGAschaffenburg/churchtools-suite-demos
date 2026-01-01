---
title: Konfiguration
excerpt: ChurchTools API verbinden und Plugin konfigurieren
---

# Konfiguration

Nach der Installation musst du das Plugin mit deiner ChurchTools-Instanz verbinden und die gewünschten Kalender/Services auswählen.

## ChurchTools API-Verbindung

### 1. API-Zugangsdaten erstellen

**In ChurchTools:**

1. Melde dich als Administrator bei ChurchTools an
2. Gehe zu **Einstellungen → Sicherheit → API-Zugriff**
3. Aktiviere **API-Zugriff** (falls nicht bereits aktiv)
4. Erstelle einen **Service-Benutzer** für WordPress:
   - Name: `wordpress-integration` (oder ähnlich)
   - Berechtigungen: Mindestens Leserechte auf Kalender & Events
5. Notiere **Username** und **Passwort**

### 2. Plugin konfigurieren

**In WordPress:**

1. Gehe zu **ChurchTools Suite → Einstellungen**
2. Unter **API-Einstellungen** trage ein:
   - **ChurchTools URL**: `https://deine-gemeinde.church.tools` (ohne `/` am Ende)
   - **Username**: Service-Benutzer (z.B. `wordpress-integration`)
   - **Passwort**: Passwort des Service-Benutzers
3. Klicke auf **Verbindung testen**

✅ **Erfolgreich?** Du siehst eine grüne Meldung mit deinem Namen  
❌ **Fehler?** Prüfe URL, Username, Passwort und API-Berechtigungen

## Kalender synchronisieren

### Kalender-Liste abrufen

1. Gehe zu **Einstellungen → Kalender**
2. Klicke auf **Kalender synchronisieren**
3. Das Plugin lädt alle verfügbaren Kalender von ChurchTools

### Kalender auswählen

1. Wähle die Kalender aus, die du auf deiner Website anzeigen möchtest
2. Klicke auf **Auswahl speichern**

**Tipp**: Wähle nur relevante Kalender aus, um die Performance zu optimieren

### Sync-Einstellungen

Unter **Sync-Einstellungen** kannst du konfigurieren:

- **Vergangene Tage**: Wie viele Tage in der Vergangenheit sollen synchronisiert werden? (Default: 7)
- **Zukünftige Tage**: Wie viele Tage in der Zukunft sollen synchronisiert werden? (Default: 90)
- **Auto-Sync**: Automatische Synchronisation aktivieren (empfohlen)
- **Sync-Intervall**: Wie oft soll synchronisiert werden? (Stündlich/Täglich)

### Events synchronisieren

1. Gehe zu **Synchronisation → Events**
2. Klicke auf **Events synchronisieren**
3. Das Plugin lädt alle Events im konfigurierten Zeitraum

**Manueller Sync**: Klicke jederzeit auf "Events synchronisieren" für manuelle Updates

**Automatischer Sync**: Wenn aktiviert, läuft Sync automatisch im Hintergrund

## Services konfigurieren (optional)

Services sind Dienste, die Events zugeordnet werden können (z.B. Predigt, Moderation, Musik).

### Service Groups synchronisieren

1. Gehe zu **Einstellungen → Services**
2. Klicke auf **Service Groups synchronisieren**
3. Wähle relevante Service Groups aus

### Services auswählen

1. Klicke auf **Services synchronisieren**
2. Wähle die Services aus, die du importieren möchtest
3. Klicke auf **Auswahl speichern**

**Beispiel-Services**:
- Predigt
- Moderation
- Musik
- Technik
- Kinderprogramm

Services werden in Listen-Templates angezeigt (z.B. "Predigt: Max Mustermann").

## Erweiterte Einstellungen

### Incremental Sync

**Standard**: Plugin verwendet inkrementellen Sync (nur geänderte Events werden aktualisiert)

**Full Sync erzwingen**: Klicke auf "Vollständiger Sync" um alle Events neu zu laden

### Debug-Modus

Aktiviere den Debug-Modus für detaillierte Logs:

1. Gehe zu **Einstellungen → Erweitert**
2. Aktiviere **Advanced Mode**
3. Gehe zu **Erweitert → Logs**
4. Prüfe Sync-Logs für Fehlersuche

### Auto-Update

Das Plugin unterstützt automatische Updates:

1. **Einstellungen → Erweitert**
2. **Auto-Update aktivieren** (empfohlen)
3. Plugin prüft automatisch auf neue Versionen

**Manueller Update-Check**: Klicke auf "Update prüfen"

## Berechtigungen

### WordPress-Berechtigungen

Plugin-Verwaltung erfordert `manage_options` Berechtigung (Administrator-Rolle).

### ChurchTools-Berechtigungen

Der Service-Benutzer benötigt mindestens:

- **Kalender lesen** - Zugriff auf Kalender-API
- **Events lesen** - Zugriff auf Events-API
- **Appointments lesen** - Zugriff auf Appointments-API
- **Services lesen** - Zugriff auf Service-API (optional)

## Fehlerbehebung

### "Connection refused"

**Ursache**: Server kann ChurchTools-URL nicht erreichen

**Lösung**:
- Prüfe ChurchTools URL (korrekte Domain?)
- Prüfe Firewall-Regeln
- Prüfe PHP `allow_url_fopen` Einstellung

### "Unauthorized"

**Ursache**: Falsche Zugangsdaten oder fehlende Berechtigungen

**Lösung**:
- Prüfe Username/Passwort
- Prüfe API-Berechtigungen in ChurchTools
- Erstelle neuen Service-Benutzer

### "Sync läuft sehr langsam"

**Ursache**: Zu viele Kalender oder zu großer Zeitraum

**Lösung**:
- Reduziere Anzahl ausgewählter Kalender
- Reduziere Sync-Zeitraum (z.B. nur +30 Tage statt +90)
- Aktiviere inkrementellen Sync

### Cookies werden nicht gespeichert

**Ursache**: Session-Probleme oder Permission-Fehler

**Lösung**:
- Prüfe `wp-content` Schreibrechte
- Deaktiviere Caching-Plugins temporär
- Erhöhe `max_execution_time` in `php.ini`

## Best Practices

### Empfohlene Konfiguration

```
Vergangene Tage: 7
Zukünftige Tage: 90
Auto-Sync: Aktiviert
Sync-Intervall: Stündlich
Incremental Sync: Aktiviert
```

### Performance-Optimierung

- ✅ Wähle nur benötigte Kalender aus
- ✅ Verwende Kalender-Filter in Shortcodes
- ✅ Aktiviere Caching (WP Super Cache, W3 Total Cache)
- ✅ Verwende `limit` Parameter in Shortcodes
- ❌ Vermeide zu großen Sync-Zeitraum (>180 Tage)
- ❌ Vermeide zu häufigen manuellen Sync (max. 1x/Stunde)

### Sicherheit

- ✅ Verwende dedizierten Service-Benutzer (nicht deinen Admin-Account)
- ✅ Vergebe minimale Berechtigungen (nur Leserechte)
- ✅ Ändere Passwort regelmäßig
- ✅ Verwende HTTPS für ChurchTools-Verbindung
- ❌ Teile API-Zugangsdaten nicht

## Support

Brauchst du Hilfe bei der Konfiguration?

- **[Troubleshooting-Guide](/documentation/troubleshooting/)** - Häufige Probleme lösen
- **[GitHub Issues](https://github.com/FEGAschaffenburg/churchtools-suite/issues)** - Fehler melden
- **[GitHub Discussions](https://github.com/FEGAschaffenburg/churchtools-suite/discussions)** - Fragen stellen

---

**Nächster Schritt**: [Shortcodes verwenden →](/documentation/shortcodes/)
