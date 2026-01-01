---
title: "List: Medium"
shortcode: '[cts_list view="medium"]'
category: "List"
difficulty: "Einfach"
excerpt: "Ausgewogene Listenansicht mit Date-Box und Beschreibung - ideal für Event-Übersichten"
order: 2
---

# List: Medium

Ausgewogene Listenansicht mit prominenter Datumsanzeige und Event-Beschreibung.

## Live Demo

[cts_list view="medium" limit="15"]

## Features

- **Date-Box**: Großes Datumselement auf der linken Seite
- **Beschreibung**: Event-Details mit gekapptem Text
- **Services**: Optionale Anzeige von Diensten und Personen
- **Kalender-Badge**: Farbige Kategorisierung
- **Location**: Ort mit Icon-Darstellung
- **Responsive**: Mobile-optimiert mit gestapeltem Layout

## Verwendung

### Basis-Shortcode

```shortcode
[cts_list view="medium"]
```

### Mit Services

```shortcode
[cts_list view="medium" show_services="true"]
```

### Mit Kalender-Filter

```shortcode
[cts_list view="medium" calendar="2,3" limit="10"]
```

## Parameter

| Parameter | Typ | Beschreibung | Standard |
|-----------|-----|--------------|----------|
| `view` | string | View-Typ (medium) | - |
| `calendar` | string | Kommaseparierte Kalender-IDs | alle |
| `limit` | int | Max. Anzahl Events | 20 |
| `show_services` | bool | Services anzeigen | true |
| `from` | string | Start-Datum (Y-m-d) | heute |
| `to` | string | End-Datum (Y-m-d) | +90 Tage |
| `class` | string | Zusätzliche CSS-Klasse | - |

## Einsatzbereiche

- **Event-Übersicht**: Hauptseite mit allen kommenden Veranstaltungen
- **Kategorie-Seiten**: Gefilterte Listen nach Veranstaltungstyp
- **Archiv-Seiten**: Vergangene Events mit Details
- **Sidebar-Widget**: Kompakte Next-Events-Anzeige

## Best Practices

### Services aktivieren
```shortcode
[cts_list view="medium" show_services="true" limit="10"]
```
Services helfen Besuchern, die verantwortlichen Personen zu identifizieren.

### Zeitraum-Optimierung
```shortcode
[cts_list view="medium" from="today" to="+30 days"]
```
Nächste 30 Tage = optimale Übersicht ohne Overload.

### Kalender-Kategorisierung
```shortcode
<!-- Gottesdienste -->
[cts_list view="medium" calendar="1"]

<!-- Jugend-Events -->
[cts_list view="medium" calendar="5,6"]
```

## CSS Customization

### Date-Box Farben

```css
.cts-list-medium .cts-date-box {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    border-radius: 12px;
}

.cts-list-medium .cts-date-day {
    font-size: 48px;
    font-weight: 700;
}
```

### Beschreibungs-Länge

```css
.cts-list-medium .cts-event-description {
    max-height: 60px;
    overflow: hidden;
    text-overflow: ellipsis;
}
```

### Hover-Effekte

```css
.cts-list-medium .cts-event-item:hover {
    transform: translateX(8px);
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
}
```

## Related Demos

- [List: Classic](/demos/list-classic/) - Klassische Listenansicht
- [List: Fluent](/demos/list-fluent/) - Fluent Design Style
- [Grid: Simple](/demos/grid-simple/) - Grid-Layout Alternative
