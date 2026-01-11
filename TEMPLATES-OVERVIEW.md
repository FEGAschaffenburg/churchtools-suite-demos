# ChurchTools Suite - Templates & Views Übersicht

## ✅ Verfügbare Shortcodes & Templates

### 📅 Calendar (Kalender)
- **Shortcode**: `[cts_calendar]`
- **Views**: 
  - `monthly-modern` - Moderner Monatskalender

### 📋 List (Liste)
- **Shortcode**: `[cts_list]`
- **Views**:
  - `classic` - Klassische Liste
  - `classic-services` - Mit Service-Informationen
  - `fluent` - Fließendes Design
  - `medium` - Mittlere Darstellung
  - `modern` - Modernes Design
  - `compact` - Kompakte Version für Sidebar

### 🎨 Grid (Gitter)
- **Shortcode**: `[cts_grid]`
- **Views**:
  - `simple` - Einfaches Design (3-spaltig)
  - `modern` - Modernes Design
  - `colorful` - Farbiges Design

### 🔍 Search (Suche)
- **Shortcode**: `[cts_search]`
- **Views**:
  - `classic` - Klassische Suchbox mit Filterung

### 📦 Agenda (Agenda)
- **Shortcode**: `[cts_agenda]` (intern als list/agenda)
- **Views**:
  - `classic` - Chronologische Agenda-Ansicht

### 🎠 Carousel (Karussell)
- **Shortcode**: `[cts_carousel]`
- **Views**:
  - `classic` - Karussell mit Navigationspfeilen

### 🎬 Slider (Schieber)
- **Shortcode**: `[cts_slider]`
- **Views**:
  - `classic` - Hero-Slider

### ⏳ Countdown
- **Shortcode**: `[cts_countdown]`
- **Views**:
  - `classic` - Countdown bis zum Event

### 🎯 Cover
- **Shortcode**: `[cts_cover]`
- **Views**:
  - `classic` - Cover-Bild mit Overlay

### 📍 Map (Karte)
- **Shortcode**: `[cts_map]`
- **Views**:
  - `classic` - OpenStreetMap Integration

### 🗓️ Timetable (Stundenplan)
- **Shortcode**: `[cts_timetable]`
- **Views**:
  - `classic` - Zeitplan-Ansicht

### 🎲 Masonry (Mauerwerk)
- **Shortcode**: `[cts_masonry]`
- **Views**:
  - `classic` - Pinterest-Style Masonry Layout

### 🪟 Widget (Widget)
- **Shortcode**: `[cts_widget]`
- **Views**:
  - `upcoming` - Kommende Termine Widget

### 📄 Single (Einzelansicht)
- **Shortcode**: `[cts_single]`
- **Views**:
  - `classic` - Klassische Einzelansicht
  - `card` - Karten-Design
  - `minimal` - Minimales Design
  - `modern` - Modernes Design

### 🎭 Modal (Fenster)
- **Shortcode**: `[cts_modal]`
- **Views**:
  - `event-detail` - Detailansicht im Modal

## 📚 Shortcode Syntax

### Basis-Shortcodes
```
[cts_calendar]
[cts_list]
[cts_grid limit="10" columns="3"]
[cts_search]
[cts_carousel limit="5"]
[cts_slider limit="5"]
[cts_countdown]
[cts_cover]
[cts_map]
[cts_timetable]
[cts_widget limit="10"]
[cts_single id="123"]
```

### Häufig genutzte Attribute

**limit** - Anzahl der Termine (default: 20)
```
[cts_grid limit="10"]
```

**columns** - Spaltenanzahl (default: 3)
```
[cts_grid columns="4"]
```

**show_time** - Uhrzeit anzeigen (true/false)
```
[cts_list show_time="false"]
```

**show_location** - Ort anzeigen (true/false)
```
[cts_list show_location="true"]
```

**show_description** - Beschreibung anzeigen (true/false)
```
[cts_grid show_description="true"]
```

**calendar** - Nach Kalender filtern (Komma-getrennt)
```
[cts_list calendar="1,2,3"]
```

**view** - Spezifische View verwenden
```
[cts_list view="fluent"]
```

## 🚀 Demo-Implementierung

### Empfohlene Demo-Seiten

1. **Grid Modern** - Modernes 3-spaltiges Grid
   ```
   [cts_grid view="modern" limit="12" columns="3" show_description="true"]
   ```

2. **List Fluent** - Fließende Liste
   ```
   [cts_list view="fluent" limit="15" show_location="true" show_services="true"]
   ```

3. **Agenda** - Chronologische Agenda
   ```
   [cts_list view="classic" limit="20" show_time="true" show_location="true"]
   ```

4. **Calendar** - Monatskalender
   ```
   [cts_calendar limit="100"]
   ```

5. **Search** - Event-Suche
   ```
   [cts_search show_location="true" show_description="true"]
   ```

6. **Widget** - Sidebar Widget
   ```
   [cts_widget limit="5"]
   ```

7. **Masonry** - Pinterest-Style
   ```
   [cts_masonry columns="3" show_description="true"]
   ```

8. **Single** - Event-Details
   ```
   [cts_single id="1"]
   ```

## 📊 Gesamt-Übersicht

- **15 Shortcodes** verfügbar
- **24 Template-Varianten** implementiert
- **Alle Views** mit vollständiger Demo-Unterstützung
- **Demo Data Provider** erzeugt automatisch Termine
