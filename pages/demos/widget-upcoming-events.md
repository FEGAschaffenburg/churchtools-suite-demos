---
title: "Widget: Upcoming Events"
shortcode: '[cts_widget view="upcoming-events" limit="5"]'
category: "Widget"
difficulty: "Einfach"
excerpt: "Kompakte Sidebar-Widget für die nächsten 5 Events - perfekt für Sidebars"
order: 1
---

# Widget: Upcoming Events

Kompaktes Sidebar-Widget mit den nächsten anstehenden Events - ideal für WordPress Sidebars.

## Live Demo

[cts_widget view="upcoming-events" limit="5"]

## Features

- **Kompakt**: Optimiert für schmale Sidebars (300px)
- **Next Events**: Zeigt kommende Termine chronologisch
- **Date Badge**: Kleines Datumselement
- **Quick Info**: Titel, Zeit, Ort
- **Calendar Colors**: Farbliche Kategorisierung
- **Link**: Jedes Event verlinkbar

## Verwendung

### Basis-Shortcode

```shortcode
[cts_widget view="upcoming-events" limit="5"]
```

### Mit Kalender-Filter

```shortcode
[cts_widget view="upcoming-events" calendar="2,3" limit="3"]
```

### Nächste Woche

```shortcode
[cts_widget view="upcoming-events" from="today" to="+7 days" limit="10"]
```

## Parameter

| Parameter | Typ | Beschreibung | Standard |
|-----------|-----|--------------|----------|
| `view` | string | View-Typ (upcoming-events) | - |
| `limit` | int | Max. Anzahl Events | 5 |
| `calendar` | string | Kommaseparierte Kalender-IDs | alle |
| `from` | string | Start-Datum (Y-m-d) | heute |
| `to` | string | End-Datum (Y-m-d) | +30 Tage |
| `class` | string | Zusätzliche CSS-Klasse | - |

## Einsatzbereiche

- **Sidebar-Widget**: Nächste Events in Blog-Sidebar
- **Footer-Widget**: Terminvorschau im Footer
- **Dashboard**: Widget für WordPress-Dashboard
- **Mobile-Menu**: Termine im Hamburger-Menü

## Best Practices

### Optimale Anzahl
```shortcode
[cts_widget view="upcoming-events" limit="5"]
```
5 Events = optimale Balance zwischen Übersicht und Vollständigkeit.

### Kategorie-Filter
```shortcode
<!-- Nur Gottesdienste -->
[cts_widget view="upcoming-events" calendar="1" limit="3"]

<!-- Jugend-Events -->
[cts_widget view="upcoming-events" calendar="5,6" limit="5"]
```

### Zeitraum-Begrenzung
```shortcode
[cts_widget view="upcoming-events" to="+14 days" limit="10"]
```
Nur nächste 2 Wochen für aktuellere Vorschau.

## Widget-Areas

### Sidebar Registration

```php
// In functions.php
register_sidebar(array(
    'name' => 'Events Sidebar',
    'id' => 'events-sidebar',
    'before_widget' => '<div class="widget %2$s">',
    'after_widget' => '</div>',
    'before_title' => '<h3 class="widget-title">',
    'after_title' => '</h3>',
));
```

### Template Usage

```php
// In sidebar.php
<aside class="sidebar">
    <?php if (is_active_sidebar('events-sidebar')) : ?>
        <?php dynamic_sidebar('events-sidebar'); ?>
    <?php endif; ?>
    
    <!-- Fallback Widget -->
    <div class="widget">
        <h3>Nächste Termine</h3>
        <?php echo do_shortcode('[cts_widget view="upcoming-events"]'); ?>
    </div>
</aside>
```

## CSS Customization

### Widget-Container

```css
.cts-widget-upcoming-events {
    background: #f9fafb;
    border: 1px solid #e5e7eb;
    border-radius: 8px;
    padding: 20px;
}

.cts-widget-upcoming-events h3 {
    font-size: 18px;
    font-weight: 600;
    margin: 0 0 16px 0;
    padding-bottom: 12px;
    border-bottom: 2px solid #e5e7eb;
}
```

### Event-Items

```css
.cts-widget-upcoming-events .cts-event-item {
    display: flex;
    gap: 12px;
    padding: 12px 0;
    border-bottom: 1px solid #f3f4f6;
    transition: background 0.2s;
}

.cts-widget-upcoming-events .cts-event-item:hover {
    background: rgba(102, 126, 234, 0.05);
}

.cts-widget-upcoming-events .cts-event-item:last-child {
    border-bottom: none;
}
```

### Date-Badge

```css
.cts-widget-upcoming-events .cts-date-badge {
    flex-shrink: 0;
    width: 50px;
    height: 50px;
    background: var(--calendar-color, #667eea);
    color: white;
    border-radius: 6px;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
}

.cts-widget-upcoming-events .cts-date-day {
    font-size: 20px;
    font-weight: 700;
    line-height: 1;
}

.cts-widget-upcoming-events .cts-date-month {
    font-size: 11px;
    text-transform: uppercase;
    margin-top: 2px;
}
```

### Event-Info

```css
.cts-widget-upcoming-events .cts-event-info {
    flex: 1;
    min-width: 0; /* Für text-overflow */
}

.cts-widget-upcoming-events .cts-event-title {
    font-size: 14px;
    font-weight: 600;
    margin: 0 0 4px 0;
    overflow: hidden;
    text-overflow: ellipsis;
    white-space: nowrap;
}

.cts-widget-upcoming-events .cts-event-time {
    font-size: 12px;
    color: #6b7280;
}
```

## Responsive Behavior

### Mobile (< 768px)

```css
@media (max-width: 768px) {
    .cts-widget-upcoming-events {
        padding: 16px;
    }
    
    .cts-widget-upcoming-events .cts-date-badge {
        width: 40px;
        height: 40px;
    }
    
    .cts-widget-upcoming-events .cts-event-title {
        font-size: 13px;
    }
}
```

## Related Demos

- [Widget: Calendar Widget](/demos/widget-calendar-widget/) - Mini-Kalender
- [Widget: Countdown](/demos/widget-countdown-widget/) - Countdown-Widget
- [List: Classic](/demos/list-classic/) - Vollständige Listenansicht
