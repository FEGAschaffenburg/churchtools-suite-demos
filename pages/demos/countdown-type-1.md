---
title: "Countdown: Type 1"
shortcode: '[cts_countdown view="type-1"]'
category: "Countdown"
difficulty: "Einfach"
excerpt: "Flip-Clock Style Countdown zum nächsten Event - dynamisch und animated"
order: 1
---

# Countdown: Type 1

Flip-Clock Style Countdown - animierter Timer zum nächsten anstehenden Event.

## Live Demo

[cts_countdown view="type-1"]

## Features

- **Auto-Event-Detection**: Findet automatisch nächstes Event
- **Flip-Animation**: Klassische Flip-Clock Übergänge
- **Real-Time**: Live-Countdown mit JavaScript
- **Responsive**: Mobile-optimiert
- **Event-Info**: Zeigt Event-Titel und Details
- **Call-to-Action**: Optional mit Button/Link

## Verwendung

### Basis-Shortcode (Auto-Event)

```shortcode
[cts_countdown view="type-1"]
```

### Spezifisches Event

```shortcode
[cts_countdown view="type-1" event_id="2026"]
```

### Mit Kalender-Filter

```shortcode
[cts_countdown view="type-1" calendar="2"]
```

## Parameter

| Parameter | Typ | Beschreibung | Standard |
|-----------|-----|--------------|----------|
| `view` | string | View-Typ (type-1) | - |
| `event_id` | string | Spezifische Event-ID | auto |
| `calendar` | string | Kalender-IDs (falls kein event_id) | alle |
| `class` | string | Zusätzliche CSS-Klasse | - |

## Auto-Event Logic

Wenn **kein** `event_id` angegeben:
1. Suche nächstes Event nach Start-Datum (aufsteigend)
2. Filtere nach `calendar` (falls angegeben)
3. Nur zukünftige Events (start_datetime > NOW())
4. Nimmt erstes Ergebnis

## Einsatzbereiche

- **Event-Landingpage**: Countdown zum Haupt-Event
- **Homepage Hero**: Nächster Gottesdienst/Event
- **Sidebar Widget**: Kompakter Countdown
- **Pre-Launch**: Teaser für neue Veranstaltungsreihe

## Best Practices

### Spezifisches Highlight-Event
```shortcode
[cts_countdown view="type-1" event_id="2026"]
```
Für fixe Events (z.B. Weihnachtsgottesdienst).

### Dynamisch nach Kategorie
```shortcode
[cts_countdown view="type-1" calendar="1"]
```
Nächster Gottesdienst (Kalender-ID 1).

### Kombination mit CTA
```html
<div class="event-hero">
    [cts_countdown view="type-1" calendar="2"]
    <a href="/anmeldung" class="cta-button">Jetzt anmelden</a>
</div>
```

## CSS Customization

### Flip-Digit Styling

```css
.cts-countdown-type-1 .cts-flip-digit {
    font-size: 72px;
    font-weight: 700;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    border-radius: 8px;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
}
```

### Label-Anpassung

```css
.cts-countdown-type-1 .cts-digit-label {
    font-size: 14px;
    text-transform: uppercase;
    letter-spacing: 2px;
    color: #666;
    margin-top: 8px;
}
```

### Responsive Breakpoints

```css
@media (max-width: 768px) {
    .cts-countdown-type-1 .cts-flip-digit {
        font-size: 48px;
    }
    
    .cts-countdown-type-1 .cts-digit-container {
        margin: 0 8px; /* Kleinere Abstände */
    }
}
```

### Event-Info Styling

```css
.cts-countdown-type-1 .cts-event-info {
    text-align: center;
    margin-top: 40px;
}

.cts-countdown-type-1 .cts-event-title {
    font-size: 32px;
    font-weight: 600;
    color: #1d2327;
    margin-bottom: 12px;
}
```

## JavaScript Hooks

### Countdown-Events

```javascript
// Countdown abgelaufen
document.addEventListener('cts-countdown-complete', function(e) {
    console.log('Event startet!', e.detail.eventId);
    // Redirect, Popup, etc.
});

// Countdown Update (jede Sekunde)
document.addEventListener('cts-countdown-tick', function(e) {
    const { days, hours, minutes, seconds } = e.detail;
    // Custom Logic
});
```

## Fallback-Handling

### Kein Event gefunden

Wenn kein passendes Event existiert:
```html
<div class="cts-countdown-empty">
    <p>Aktuell keine anstehenden Events.</p>
</div>
```

### Event bereits gestartet

Wenn Event in der Vergangenheit:
```html
<div class="cts-countdown-started">
    <p>Event läuft bereits!</p>
    <a href="/event/123">Jetzt teilnehmen →</a>
</div>
```

## Related Demos

- [Countdown: Type 2](/demos/countdown-type-2/) - Circular Progress
- [Countdown: Type 3](/demos/countdown-type-3/) - Minimal Counter
- [Widget: Upcoming Events](/demos/widget-upcoming-events/) - Nächste Events-Liste
