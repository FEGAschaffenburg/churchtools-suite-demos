---
title: "Calendar: Weekly Fluent"
shortcode: '[cts_calendar view="weekly-fluent"]'
category: "Calendar"
difficulty: "Mittel"
excerpt: "Moderne Wochenansicht im Fluent Design Style für übersichtliche Terminplanung"
order: 2
---

# Calendar: Weekly Fluent

Wochenansicht im modernen Fluent Design Style - perfekt für Teamkalender und Zeitplan-Übersichten.

## Live Demo

[cts_calendar view="weekly-fluent" limit="50"]

## Features

- **Wochenansicht**: Anzeige der aktuellen Woche mit allen Terminen
- **Fluent Design**: Microsoft Fluent Design Language für moderne Optik
- **Time Slots**: Termine in Zeitfenstern gruppiert
- **Responsive**: Mobile-optimiert mit Touch-Gesten
- **Color Coding**: Kalender-Farben für visuelle Unterscheidung
- **Navigation**: Vor/Zurück durch Wochen navigieren

## Verwendung

### Basis-Shortcode

```shortcode
[cts_calendar view="weekly-fluent"]
```

### Mit Kalender-Filter

```shortcode
[cts_calendar view="weekly-fluent" calendar="2,3"]
```

### Mit Datumsbereich

```shortcode
[cts_calendar view="weekly-fluent" from="2025-12-01" to="2025-12-31"]
```

## Parameter

| Parameter | Typ | Beschreibung | Standard |
|-----------|-----|--------------|----------|
| `view` | string | View-Typ (weekly-fluent) | - |
| `calendar` | string | Kommaseparierte Kalender-IDs | alle |
| `limit` | int | Max. Anzahl Events | 50 |
| `from` | string | Start-Datum (Y-m-d) | heute |
| `to` | string | End-Datum (Y-m-d) | +7 Tage |
| `class` | string | Zusätzliche CSS-Klasse | - |

## Einsatzbereiche

- **Gemeinde-Wochenplan**: Übersicht aller Veranstaltungen der Woche
- **Teamkalender**: Dienstpläne und Meetings im Überblick
- **Raumplanung**: Belegungsplan für Gemeinderäume
- **Gottesdienst-Planung**: Wochenübersicht mit allen Services

## Best Practices

### Mobile-First
```shortcode
[cts_calendar view="weekly-fluent" limit="30"]
```
Auf Mobilgeräten max. 30 Events für bessere Performance.

### Kalender-Filter
```shortcode
[cts_calendar view="weekly-fluent" calendar="1,5,7"]
```
Nur relevante Kalender anzeigen für fokussierte Darstellung.

### Zeitraum-Optimierung
```shortcode
[cts_calendar view="weekly-fluent" from="monday this week" to="sunday this week"]
```
Wochengrenzen automatisch setzen.

## CSS Customization

### Primärfarbe ändern

```css
.cts-calendar-weekly-fluent {
    --primary-color: #0078d4;
    --accent-color: #106ebe;
}
```

### Zeitslot-Höhe anpassen

```css
.cts-calendar-weekly-fluent .cts-timeslot {
    min-height: 60px;
}
```

### Header-Styling

```css
.cts-calendar-weekly-fluent .cts-week-header {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    padding: 20px;
}
```

## Related Demos

- [Calendar: Monthly Modern](/demos/calendar-monthly-modern/) - Monatsansicht
- [Calendar: Daily](/demos/calendar-daily/) - Tagesansicht
- [List: Classic](/demos/list-classic/) - Listenansicht
