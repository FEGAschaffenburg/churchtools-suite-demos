---
title: Grid - Simple
shortcode: '[cts_grid view="simple" columns="3"]'
category: grid
difficulty: beginner
excerpt: Einfaches Grid-Layout mit Event-Karten in flexiblen Spalten.
---

## Übersicht

Das **Simple Grid** zeigt Events als Karten in einem responsiven Grid. Ideal für Übersichtsseiten und Landing Pages.

## Features

✅ **Flexibles Grid** - 1-4 Spalten konfigurierbar  
✅ **Event-Karten** - Kompakte Darstellung  
✅ **Hover-Effekte** - Moderne Interaktionen  
✅ **Responsive** - Auto-Anpassung auf mobilen Geräten  
✅ **Thumbnail-Support** - Optionale Event-Bilder  

## Verwendung

### Basis-Shortcode (3 Spalten)

```
[cts_grid view="simple" columns="3"]
```

### 4 Spalten

```
[cts_grid view="simple" columns="4"]
```

### 2 Spalten mit Limit

```
[cts_grid view="simple" columns="2" limit="6"]
```

### Mit Kalender-Filter

```
[cts_grid view="simple" columns="3" calendar="123,456"]
```

## Parameter

| Parameter | Typ | Default | Beschreibung |
|-----------|-----|---------|--------------|
| `view` | String | `simple` | Template-Variante (erforderlich) |
| `columns` | Integer | 3 | Anzahl Spalten (1-4) |
| `limit` | Integer | 20 | Max. Anzahl Events |
| `calendar` | String | Alle | Komma-getrennte Kalender-IDs |
| `from` | Date | Heute | Start-Datum (Y-m-d) |
| `to` | Date | +90 Tage | End-Datum (Y-m-d) |

## Responsive Verhalten

| Viewport | Spalten |
|----------|---------|
| Desktop (>1024px) | Wie konfiguriert |
| Tablet (768-1023px) | Max. 2 Spalten |
| Mobile (<768px) | 1 Spalte |

## Anpassung

### CSS-Variablen

```css
.cts-grid-simple {
	--grid-gap: 1.5rem;
	--grid-card-radius: 8px;
	--grid-card-shadow: 0 2px 8px rgba(0,0,0,0.1);
	--grid-hover-shadow: 0 4px 16px rgba(0,0,0,0.15);
}
```

### Template Override

Kopiere `templates/grid/simple.php` in dein Theme:

```
dein-theme/
  churchtools-suite/
    grid/
      simple.php
```

## Event-Karte Struktur

Jede Karte enthält:

- **Datum-Badge** - Prominent oben rechts
- **Titel** - Event-Name
- **Uhrzeit** - Start/End-Zeit
- **Location** - Veranstaltungsort
- **Beschreibung** - Gekürzte Beschreibung (optional)
- **Call-to-Action** - "Mehr erfahren" Button

## Best Practices

- ✅ Verwende 3 Spalten für optimale Balance
- ✅ Setze `limit` basierend auf Spaltenanzahl (z.B. 6 bei 3 Spalten)
- ✅ Filter nach Kalendern für thematische Gruppierung
- ❌ Vermeide mehr als 4 Spalten (zu schmal)
- ❌ Vermeide zu viele Events ohne Pagination

## Performance-Tipps

- Limitiere auf 12-15 Events pro Seite
- Verwende Kalender-Filter statt alle Events zu laden
- Kombiniere mit AJAX-Pagination für große Listen

## Verwandte Demos

- [Grid - Modern](/demos/grid-modern/)
- [Grid - Colorful](/demos/grid-colorful/)
- [Liste - Classic](/demos/list-classic/)
