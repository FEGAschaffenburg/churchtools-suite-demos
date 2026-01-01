---
title: "Grid: Modern"
shortcode: '[cts_grid view="modern" columns="3"]'
category: "Grid"
difficulty: "Einfach"
excerpt: "Modernes Grid-Layout mit Cards, Hover-Effekten und flexiblen Spalten"
order: 2
---

# Grid: Modern

Modernes Card-Grid mit Shadow-Effekten und responsivem Layout.

## Live Demo

[cts_grid view="modern" columns="3" limit="9"]

## Features

- **Card-Design**: Jedes Event als moderne Card
- **Box-Shadows**: Subtile Schatten für Tiefeneffekt
- **Hover-Animation**: Transform-Effekt beim Drüberfahren
- **Flexible Columns**: 2-4 Spalten konfigurierbar
- **Responsive**: Auto-Anpassung für Mobile
- **Calendar Colors**: Farbige Top-Border nach Kalender

## Verwendung

### Basis-Shortcode

```shortcode
[cts_grid view="modern" columns="3"]
```

### 4-Spalten Layout

```shortcode
[cts_grid view="modern" columns="4" limit="12"]
```

### Mit Kalender-Filter

```shortcode
[cts_grid view="modern" columns="3" calendar="2,3" limit="9"]
```

## Parameter

| Parameter | Typ | Beschreibung | Standard |
|-----------|-----|--------------|----------|
| `view` | string | View-Typ (modern) | - |
| `columns` | int | Spaltenanzahl (2-4) | 3 |
| `calendar` | string | Kommaseparierte Kalender-IDs | alle |
| `limit` | int | Max. Anzahl Events | 20 |
| `from` | string | Start-Datum (Y-m-d) | heute |
| `to` | string | End-Datum (Y-m-d) | +90 Tage |
| `class` | string | Zusätzliche CSS-Klasse | - |

## Spalten-Logik

| Spalten | Desktop (>1200px) | Tablet (768-1200px) | Mobile (<768px) |
|---------|-------------------|---------------------|-----------------|
| 2 | 2 | 2 | 1 |
| 3 | 3 | 2 | 1 |
| 4 | 4 | 3 | 1 |

## Einsatzbereiche

- **Event-Galerie**: Visuelle Übersicht mit Bildern
- **Kategorie-Seiten**: Mehrere Event-Typen nebeneinander
- **Homepage-Grid**: Featured Events im Hero-Bereich
- **Archive**: Übersicht vergangener Veranstaltungen

## Best Practices

### Performance-Optimierung
```shortcode
[cts_grid view="modern" columns="3" limit="9"]
```
Limit auf Spalten * Zeilen setzen (z.B. 3×3 = 9).

### Responsive Breakpoints
```shortcode
<!-- Desktop: 4 Spalten -->
[cts_grid view="modern" columns="4" limit="8" class="desktop-only"]

<!-- Tablet: 3 Spalten -->
[cts_grid view="modern" columns="3" limit="6" class="tablet-only"]

<!-- Mobile: 1 Spalte -->
[cts_grid view="modern" columns="2" limit="4" class="mobile-only"]
```

### Kalender-Kategorien
```shortcode
<!-- Gottesdienste -->
[cts_grid view="modern" columns="2" calendar="1" limit="6"]

<!-- Jugend-Events -->
[cts_grid view="modern" columns="3" calendar="5,6" limit="9"]
```

## CSS Customization

### Card-Styling

```css
.cts-grid-modern .cts-event-card {
    border-radius: 16px;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
    transition: all 0.3s ease;
}

.cts-grid-modern .cts-event-card:hover {
    transform: translateY(-8px);
    box-shadow: 0 12px 24px rgba(0, 0, 0, 0.15);
}
```

### Top-Border Farben

```css
.cts-grid-modern .cts-event-card {
    border-top: 4px solid var(--calendar-color);
}
```

### Spalten-Gaps

```css
.cts-grid-modern .cts-grid-container {
    gap: 24px; /* Standard: 20px */
}
```

### Responsive Override

```css
@media (max-width: 768px) {
    .cts-grid-modern .cts-grid-container {
        grid-template-columns: 1fr;
        gap: 16px;
    }
}
```

## Related Demos

- [Grid: Simple](/demos/grid-simple/) - Minimalistisches Grid
- [Grid: Colorful](/demos/grid-colorful/) - Farbiges Grid mit Accents
- [List: Medium](/demos/list-medium/) - Listen-Alternative
