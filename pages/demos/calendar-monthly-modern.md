---
title: Kalender - Monatlich Modern
shortcode: '[cts_calendar view="monthly-modern"]'
category: calendar
difficulty: beginner
excerpt: Moderner Monatskalender mit klarem Design und Farbkodierung nach Kalender.
---

## Übersicht

Der **Monthly Modern** Kalender zeigt Events in einer modernen Monatsansicht. Jeder Kalender hat eine eigene Farbe, Events werden als farbige Blöcke dargestellt.

## Features

✅ **Responsive Design** - Funktioniert auf allen Geräten  
✅ **Farbkodierung** - Jeder Kalender hat eigene Farbe  
✅ **Monatswechsel** - Navigation zwischen Monaten  
✅ **Event-Details** - Klick öffnet Modal mit Details  
✅ **Clean UI** - Moderne Typografie und Spacing  

## Verwendung

### Basis-Shortcode

```
[cts_calendar view="monthly-modern"]
```

### Mit Kalender-Filter

```
[cts_calendar view="monthly-modern" calendar="123,456"]
```

### Mit Start-Datum

```
[cts_calendar view="monthly-modern" from="2025-01-01"]
```

## Parameter

| Parameter | Typ | Default | Beschreibung |
|-----------|-----|---------|--------------|
| `view` | String | `monthly-modern` | Template-Variante (erforderlich) |
| `calendar` | String | Alle | Komma-getrennte Kalender-IDs |
| `from` | Date | Heute | Start-Datum (Y-m-d) |
| `to` | Date | +90 Tage | End-Datum (Y-m-d) |

## Anpassung

### CSS-Variablen

```css
.cts-calendar-monthly-modern {
	--calendar-border: #e5e7eb;
	--calendar-header-bg: #f9fafb;
	--calendar-today-bg: #eff6ff;
	--calendar-event-radius: 4px;
}
```

### Template Override

Kopiere `templates/calendar/monthly-modern.php` in dein Theme:

```
dein-theme/
  churchtools-suite/
    calendar/
      monthly-modern.php
```

## Best Practices

- ✅ Verwende Kalender-Filter für bessere Performance
- ✅ Kombiniere mit Event-Details Modal
- ✅ Teste auf mobilen Geräten
- ❌ Vermeide zu viele Kalender gleichzeitig (max. 5)

## Verwandte Demos

- [Kalender - Monthly Clean](/demos/calendar-monthly-clean/)
- [Kalender - Weekly Fluent](/demos/calendar-weekly-fluent/)
- [Grid - Simple](/demos/grid-simple/)
