---
title: Liste - Classic
shortcode: '[cts_list view="classic"]'
category: list
difficulty: beginner
excerpt: Klassische Event-Liste mit Datum, Titel, Beschreibung und Services.
---

## Übersicht

Die **Classic List** ist die Standard-Ansicht für Event-Listen. Übersichtlich, klar strukturiert, mit allen wichtigen Informationen.

## Features

✅ **Datum & Uhrzeit** - Prominent dargestellt  
✅ **Titel & Beschreibung** - Vollständige Event-Info  
✅ **Services** - Zeigt zugeordnete Dienste (optional)  
✅ **Location** - Event-Standort  
✅ **Responsive** - Mobile-optimiert  

## Verwendung

### Basis-Shortcode

```
[cts_list view="classic"]
```

### Mit Limit

```
[cts_list view="classic" limit="10"]
```

### Ohne Services

```
[cts_list view="classic" show_services="false"]
```

### Mit Datum-Range

```
[cts_list view="classic" from="2025-01-01" to="2025-12-31"]
```

## Parameter

| Parameter | Typ | Default | Beschreibung |
|-----------|-----|---------|--------------|
| `view` | String | `classic` | Template-Variante (erforderlich) |
| `limit` | Integer | 20 | Max. Anzahl Events |
| `calendar` | String | Alle | Komma-getrennte Kalender-IDs |
| `from` | Date | Heute | Start-Datum (Y-m-d) |
| `to` | Date | +90 Tage | End-Datum (Y-m-d) |
| `show_services` | Boolean | true | Services anzeigen |

## Anpassung

### CSS-Variablen

```css
.cts-list-classic {
	--list-item-padding: 1.5rem;
	--list-item-border: #e5e7eb;
	--list-date-color: #2563eb;
	--list-title-size: 1.5rem;
}
```

### Template Override

Kopiere `templates/list/classic.php` in dein Theme:

```
dein-theme/
  churchtools-suite/
    list/
      classic.php
```

## Services Integration

Die Classic List zeigt standardmäßig alle konfigurierten Services an:

- **Predigt** - Name des Predigers
- **Moderation** - Moderator
- **Musik** - Musiker/Band
- **Technik** - Techniker

Services werden nur angezeigt, wenn sie in den Plugin-Einstellungen ausgewählt sind.

## Best Practices

- ✅ Verwende `limit` für bessere Performance
- ✅ Filter nach relevanten Kalendern
- ✅ Aktiviere nur benötigte Services
- ❌ Vermeide `show_services="true"` ohne Service-Konfiguration

## Verwandte Demos

- [Liste - Modern](/demos/list-modern/)
- [Liste - Fluent](/demos/list-fluent/)
- [Grid - Simple](/demos/grid-simple/)
