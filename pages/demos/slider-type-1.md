---
title: "Slider: Type 1"
shortcode: '[cts_slider view="type-1" autoplay="true"]'
category: "Slider"
difficulty: "Mittel"
excerpt: "Standard Image-Slider mit Autoplay für Hero-Bereiche und Event-Highlights"
order: 1
---

# Slider: Type 1

Standard Fullwidth-Slider mit automatischem Bildwechsel - perfekt für Hero-Sections.

## Live Demo

[cts_slider view="type-1" limit="5" autoplay="true" interval="5000"]

## Features

- **Autoplay**: Automatischer Bildwechsel
- **Touch-Support**: Swipe-Gesten auf Mobile
- **Navigation**: Vor/Zurück-Pfeile + Dots
- **Fullwidth**: 100% Breite für maximale Wirkung
- **Overlays**: Text-Overlays auf Bildern
- **Transitions**: Smooth Fade-Effekte

## Verwendung

### Basis-Shortcode

```shortcode
[cts_slider view="type-1"]
```

### Mit Autoplay

```shortcode
[cts_slider view="type-1" autoplay="true" interval="5000"]
```

### Mit Kalender-Filter

```shortcode
[cts_slider view="type-1" calendar="2,3" limit="8"]
```

## Parameter

| Parameter | Typ | Beschreibung | Standard |
|-----------|-----|--------------|----------|
| `view` | string | View-Typ (type-1) | - |
| `limit` | int | Max. Anzahl Slides | 5 |
| `autoplay` | bool | Auto-Play aktivieren | true |
| `interval` | int | Intervall in ms | 5000 |
| `calendar` | string | Kommaseparierte Kalender-IDs | alle |
| `class` | string | Zusätzliche CSS-Klasse | - |

## Einsatzbereiche

- **Homepage Hero**: Hauptbereich mit Featured Events
- **Event-Highlight**: Top-Veranstaltungen im Fokus
- **Bildergalerie**: Event-Impressionen im Slider
- **Landingpages**: Marketing-orientierte Event-Präsentation

## Best Practices

### Autoplay-Timing
```shortcode
[cts_slider view="type-1" autoplay="true" interval="6000"]
```
6 Sekunden = optimale Balance zwischen Lesbarkeit und Dynamik.

### Slide-Anzahl begrenzen
```shortcode
[cts_slider view="type-1" limit="5"]
```
Max. 5 Slides für bessere Performance und UX.

### High-Priority Events
```shortcode
[cts_slider view="type-1" calendar="1" from="today" to="+14 days" limit="3"]
```
Nur wichtige Events der nächsten 2 Wochen.

## CSS Customization

### Slider-Höhe

```css
.cts-slider-type-1 .cts-slide {
    height: 600px; /* Standard: 500px */
}

@media (max-width: 768px) {
    .cts-slider-type-1 .cts-slide {
        height: 400px;
    }
}
```

### Overlay-Styling

```css
.cts-slider-type-1 .cts-slide-overlay {
    background: linear-gradient(
        to bottom,
        rgba(0, 0, 0, 0) 0%,
        rgba(0, 0, 0, 0.7) 100%
    );
}
```

### Navigation-Buttons

```css
.cts-slider-type-1 .cts-slider-prev,
.cts-slider-type-1 .cts-slider-next {
    background: rgba(255, 255, 255, 0.9);
    width: 60px;
    height: 60px;
    border-radius: 50%;
}
```

### Dots-Position

```css
.cts-slider-type-1 .cts-slider-dots {
    bottom: 30px;
    gap: 12px;
}

.cts-slider-type-1 .cts-slider-dot {
    width: 12px;
    height: 12px;
    border-radius: 50%;
    background: rgba(255, 255, 255, 0.5);
}

.cts-slider-type-1 .cts-slider-dot.active {
    background: white;
    transform: scale(1.3);
}
```

## JavaScript API

### Manuelle Steuerung

```javascript
// Slider-Instanz holen
const slider = document.querySelector('.cts-slider-type-1');

// Nächstes Slide
slider.ctsSlidernext();

// Vorheriges Slide
slider.ctsSlider.prev();

// Zu Slide 3 springen
slider.ctsSlider.goTo(2); // 0-basiert

// Autoplay pausieren
slider.ctsSlider.pause();

// Autoplay fortsetzen
slider.ctsSlider.play();
```

## Related Demos

- [Slider: Type 3](/demos/slider-type-3/) - Slider mit Thumbnails
- [Carousel: Type 1](/demos/carousel-type-1/) - Carousel-Variante
- [Cover: Modern](/demos/cover-modern/) - Static Hero Alternative
