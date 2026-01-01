---
title: "Shortcode Reference"
excerpt: "Schnellreferenz aller ChurchTools Suite Shortcodes - Alle 70+ View-Varianten auf einen Blick"
parent: "Dokumentation"
order: 4
---

# Shortcode Quick Reference

**Version**: 0.9.4.9 | **Letzte Aktualisierung**: 23. Dezember 2025

## 📋 Alle Shortcodes auf einen Blick

### 1. Calendar Views (8 Varianten)
```shortcode
[cts_calendar view="monthly-modern" limit="20"]
[cts_calendar view="monthly-clean" limit="20"]
[cts_calendar view="monthly-classic" limit="20"]
[cts_calendar view="weekly-fluent" limit="20"]
[cts_calendar view="weekly-liquid" limit="20"]
[cts_calendar view="yearly" limit="50"]
[cts_calendar view="daily" limit="10"]
[cts_calendar view="daily-liquid" limit="10"]
```

### 2. List Views (10 Varianten)
```shortcode
[cts_list view="classic" limit="10" show_services="true"]
[cts_list view="standard" limit="10" show_services="true"]
[cts_list view="modern" limit="10" show_services="true"]
[cts_list view="minimal" limit="10"]
[cts_list view="toggle" limit="10" show_services="true"]
[cts_list view="with-map" limit="10"]
[cts_list view="fluent" limit="10" show_services="true"]
[cts_list view="large-liquid" limit="10" show_services="true"]
[cts_list view="medium-liquid" limit="10" show_services="true"]
[cts_list view="small-liquid" limit="10" show_services="true"]
```

### 3. Grid Views (14 Varianten)
```shortcode
[cts_grid view="simple" columns="3" limit="9"]
[cts_grid view="modern" columns="3" limit="9"]
[cts_grid view="minimal" columns="3" limit="9"]
[cts_grid view="ocean" columns="3" limit="9"]
[cts_grid view="classic" columns="3" limit="9"]
[cts_grid view="colorful" columns="3" limit="9"]
[cts_grid view="novel" columns="3" limit="9"]
[cts_grid view="with-map" columns="2" limit="6"]
[cts_grid view="large-liquid" columns="2" limit="6"]
[cts_grid view="medium-liquid" columns="3" limit="9"]
[cts_grid view="small-liquid" columns="4" limit="8"]
[cts_grid view="tile" columns="3" limit="9"]
```

### 4. Slider Views (5 Varianten)
```shortcode
[cts_slider view="type-1" limit="5" autoplay="true" interval="5000"]
[cts_slider view="type-2" limit="5" autoplay="true" interval="5000"]
[cts_slider view="type-3" limit="5" autoplay="true" interval="5000"]
[cts_slider view="type-4" limit="5" autoplay="false"]
[cts_slider view="type-5" limit="5" autoplay="true" interval="4000"]
```

### 5. Countdown Views (3 Varianten)
```shortcode
[cts_countdown view="type-1"]
[cts_countdown view="type-2"]
[cts_countdown view="type-3"]
```

### 6. Cover Views (5 Varianten)
```shortcode
[cts_cover view="classic" limit="1"]
[cts_cover view="modern" limit="1"]
[cts_cover view="clean" limit="1"]
[cts_cover view="fluent" limit="1"]
[cts_cover view="liquid" limit="1"]
```

### 7. Timetable Views (3 Varianten)
```shortcode
[cts_timetable view="modern" limit="20"]
[cts_timetable view="clean" limit="20"]
[cts_timetable view="timeline" limit="20"]
```

### 8. Carousel Views (4 Varianten)
```shortcode
[cts_carousel view="type-1" limit="10" autoplay="true" interval="5000"]
[cts_carousel view="type-2" limit="10" autoplay="true" interval="5000"]
[cts_carousel view="type-3" limit="10" autoplay="false"]
[cts_carousel view="type-4" limit="10" autoplay="true" interval="4000"]
```

### 9. Widget Views (3 Varianten)
```shortcode
[cts_widget view="upcoming-events" limit="5"]
[cts_widget view="calendar-widget"]
[cts_widget view="countdown-widget"]
```

### 10. Search Views (2 Varianten)
```shortcode
[cts_search view="bar"]
[cts_search view="advanced"]
```

### 11. Map Views (3 Varianten)
```shortcode
[cts_map view="standard" limit="20"]
[cts_map view="advanced" limit="20"]
[cts_map view="liquid" limit="20"]
```

### 12. Modal Views (1 Variante)
```shortcode
[cts_modal view="default"]
```

### 13. Single Event Views (2 Varianten)
```shortcode
[cts_single event_id="123" view="detail"]
[cts_single event_id="123" view="full"]
```

---

## 🎯 Gemeinsame Parameter

### Alle Views unterstützen:
- `calendar="1,2,3"` - Kalender-IDs (kommagetrennt) oder leer für alle
- `limit="10"` - Maximale Anzahl Events
- `from="today"` - Start-Datum (Y-m-d oder "today")
- `to="+30 days"` - End-Datum (Y-m-d oder relative)

### List-Views zusätzlich:
- `show_services="true|false"` - Dienste anzeigen

### Grid-Views zusätzlich:
- `columns="2|3|4"` - Anzahl Spalten

### Slider/Carousel zusätzlich:
- `autoplay="true|false"` - Automatisches Abspielen
- `interval="5000"` - Intervall in Millisekunden

---

## 📊 Statistik

- **13 Shortcode-Typen**
- **70+ View-Varianten**
- **50+ Parameter-Kombinationen**
- **100% Gutenberg-Block kompatibel**

---

## 🚀 Verwendung

### In Beiträgen/Seiten:
1. Shortcode kopieren
2. In WordPress-Editor einfügen
3. Parameter anpassen
4. Vorschau/Veröffentlichen

### Als Gutenberg-Block:
1. Block hinzufügen → ChurchTools Suite
2. Block-Typ wählen (Calendar/List/Grid)
3. View auswählen
4. Kalender per Checkbox auswählen
5. Parameter einstellen

---

## 🔧 Troubleshooting

### Shortcode zeigt nichts:
- ✅ Plugin aktiviert?
- ✅ ChurchTools-Verbindung konfiguriert?
- ✅ Kalender synchronisiert?
- ✅ Events vorhanden?

### Falsche Events:
- ✅ Kalender-IDs korrekt?
- ✅ Datumsbereich prüfen
- ✅ Sync durchgeführt?

### Layout-Probleme:
- ✅ Theme-CSS-Konflikte?
- ✅ Browser-Console prüfen
- ✅ CSS-Cache leeren

---

## 📖 Weitere Dokumentation

- [Shortcode Guide](/docs/shortcode-guide/) - Ausführliche Anleitung mit Beispielen
- [Template Documentation](/docs/templates/) - Template-Entwicklung
- [Live Demos](/demos/) - Alle View-Varianten live erleben

**Support:** [GitHub Issues](https://github.com/FEGAschaffenburg/churchtools-suite/issues)

**Letzte Aktualisierung**: 23. Dezember 2025
