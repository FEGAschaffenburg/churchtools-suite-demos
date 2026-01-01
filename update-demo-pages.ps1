# Update all demo pages with professional HTML formatting
# ChurchTools Suite Demo Site - Demo Pages Update Script

Write-Host "🔄 Updating demo pages..." -ForegroundColor Cyan

# Calendar: Weekly Fluent
@"
---
title: "Calendar: Weekly Fluent"
excerpt: "Wochenansicht im modernen Fluent Design Style - perfekt für Teamkalender und Zeitplan-Übersichten."
parent: "demos"
order: 1
---

<div class="demo-header">
<h1>📅 Calendar: Weekly Fluent</h1>
<p class="demo-description">Moderne Wochenansicht mit Microsoft Fluent Design - perfekt für Gemeinde-Wochenpläne und Teamkalender.</p>
</div>

<div class="demo-preview">
[cts_calendar view="weekly-fluent" limit="50"]
</div>

<div class="demo-info">
<h2>✨ Features</h2>
<ul class="feature-list">
<li><strong>Wochenansicht</strong> - Anzeige der aktuellen Woche mit allen Terminen</li>
<li><strong>Fluent Design</strong> - Microsoft Fluent Design Language für moderne Optik</li>
<li><strong>Time Slots</strong> - Termine in Zeitfenstern gruppiert</li>
<li><strong>Responsive</strong> - Mobile-optimiert mit Touch-Gesten</li>
<li><strong>Color Coding</strong> - Kalender-Farben für visuelle Unterscheidung</li>
<li><strong>Navigation</strong> - Vor/Zurück durch Wochen navigieren</li>
</ul>

<h2>📋 Verwendung</h2>
<pre><code>[cts_calendar view="weekly-fluent" limit="50"]</code></pre>

<h2>⚙️ Parameter</h2>
<table class="params-table">
<thead>
<tr><th>Parameter</th><th>Typ</th><th>Beschreibung</th><th>Standard</th></tr>
</thead>
<tbody>
<tr><td><code>view</code></td><td>string</td><td>View-Typ (weekly-fluent)</td><td>–</td></tr>
<tr><td><code>calendar</code></td><td>string</td><td>Kommagetrennte Kalender-IDs</td><td>alle</td></tr>
<tr><td><code>limit</code></td><td>int</td><td>Max. Anzahl Events</td><td>50</td></tr>
<tr><td><code>from</code></td><td>string</td><td>Start-Datum (Y-m-d)</td><td>heute</td></tr>
<tr><td><code>to</code></td><td>string</td><td>End-Datum (Y-m-d)</td><td>+7 Tage</td></tr>
<tr><td><code>class</code></td><td>string</td><td>Zusätzliche CSS-Klasse</td><td>–</td></tr>
</tbody>
</table>

<h2>🎯 Einsatzbereiche</h2>
<ul class="use-cases">
<li><strong>Gemeinde-Wochenplan</strong> - Alle Veranstaltungen der aktuellen Woche auf einen Blick</li>
<li><strong>Teamkalender</strong> - Koordination von Mitarbeiter-Diensten und Verantwortlichkeiten</li>
<li><strong>Raumplanung</strong> - Übersicht über Raumbelegungen und Verfügbarkeiten</li>
</ul>
</div>
"@ | Out-File -Encoding UTF8 -FilePath "pages\demos\calendar-weekly-fluent.md"
Write-Host "✅ Calendar: Weekly Fluent" -ForegroundColor Green

# List: Medium
@"
---
title: "List: Medium"
excerpt: "Liste mit Datum-Box und Beschreibung - ideal für Event-Übersichten."
parent: "demos"
order: 2
---

<div class="demo-header">
<h1>📋 List: Medium</h1>
<p class="demo-description">Liste mit Datum-Box, Beschreibung und Services - perfekt für Event-Übersichten und Archiv-Seiten.</p>
</div>

<div class="demo-preview">
[cts_list view="medium" limit="10"]
</div>

<div class="demo-info">
<h2>✨ Features</h2>
<ul class="feature-list">
<li><strong>Datum-Box</strong> - Großer Tag/Monat-Indikator links</li>
<li><strong>Beschreibungstext</strong> - Kurze Event-Info direkt sichtbar</li>
<li><strong>Services anzeigen</strong> - Personen und Dienste pro Event</li>
<li><strong>Kalender-Badge</strong> - Farbige Kalender-Zuordnung</li>
<li><strong>Responsive</strong> - Mobile-optimiert mit Touch-Support</li>
</ul>

<h2>📋 Verwendung</h2>
<pre><code>[cts_list view="medium" limit="10"]</code></pre>

<h2>⚙️ Parameter</h2>
<table class="params-table">
<thead>
<tr><th>Parameter</th><th>Typ</th><th>Beschreibung</th><th>Standard</th></tr>
</thead>
<tbody>
<tr><td><code>view</code></td><td>string</td><td>View-Typ (medium)</td><td>–</td></tr>
<tr><td><code>calendar</code></td><td>string</td><td>Kommagetrennte Kalender-IDs</td><td>alle</td></tr>
<tr><td><code>limit</code></td><td>int</td><td>Max. Anzahl Events</td><td>20</td></tr>
<tr><td><code>show_services</code></td><td>bool</td><td>Services anzeigen</td><td>true</td></tr>
<tr><td><code>from</code></td><td>string</td><td>Start-Datum (Y-m-d)</td><td>heute</td></tr>
<tr><td><code>to</code></td><td>string</td><td>End-Datum (Y-m-d)</td><td>+90 Tage</td></tr>
<tr><td><code>class</code></td><td>string</td><td>Zusätzliche CSS-Klasse</td><td>–</td></tr>
</tbody>
</table>

<h2>🎯 Einsatzbereiche</h2>
<ul class="use-cases">
<li><strong>Event-Übersicht</strong> - Hauptseite mit allen kommenden Veranstaltungen</li>
<li><strong>Kategorie-Seiten</strong> - Gefilterte Listen nach Veranstaltungstyp</li>
<li><strong>Archiv-Seiten</strong> - Vergangene Events mit Details</li>
<li><strong>Sidebar-Widget</strong> - Kompakte Next-Events-Anzeige</li>
</ul>
</div>
"@ | Out-File -Encoding UTF8 -FilePath "pages\demos\list-medium.md"
Write-Host "✅ List: Medium" -ForegroundColor Green

# Grid: Modern
@"
---
title: "Grid: Modern"
excerpt: "Moderne Karten-Ansicht mit Hover-Effekten - ideal für visuelle Event-Darstellung."
parent: "demos"
order: 3
---

<div class="demo-header">
<h1>🎨 Grid: Modern</h1>
<p class="demo-description">Moderne Karten-Ansicht mit Box-Shadows und Hover-Animation - perfekt für Event-Galerien und Homepage-Grids.</p>
</div>

<div class="demo-preview">
[cts_grid view="modern" columns="3" limit="6"]
</div>

<div class="demo-info">
<h2>✨ Features</h2>
<ul class="feature-list">
<li><strong>Card Design</strong> - Moderne Karten mit Box-Shadows</li>
<li><strong>Hover-Animation</strong> - Sanfte Transform-Effekte</li>
<li><strong>2-4 Spalten</strong> - Flexibles Spalten-Layout</li>
<li><strong>Responsive</strong> - Auto-Anpassung auf Tablets/Mobil</li>
<li><strong>Top-Border</strong> - Farbige Kalender-Akzente</li>
</ul>

<h2>📋 Verwendung</h2>
<pre><code>[cts_grid view="modern" columns="3" limit="6"]</code></pre>

<h2>⚙️ Parameter</h2>
<table class="params-table">
<thead>
<tr><th>Parameter</th><th>Typ</th><th>Beschreibung</th><th>Standard</th></tr>
</thead>
<tbody>
<tr><td><code>view</code></td><td>string</td><td>View-Typ (modern)</td><td>–</td></tr>
<tr><td><code>columns</code></td><td>int</td><td>Anzahl Spalten (2-4)</td><td>3</td></tr>
<tr><td><code>calendar</code></td><td>string</td><td>Kommagetrennte Kalender-IDs</td><td>alle</td></tr>
<tr><td><code>limit</code></td><td>int</td><td>Max. Anzahl Events</td><td>20</td></tr>
<tr><td><code>from</code></td><td>string</td><td>Start-Datum (Y-m-d)</td><td>heute</td></tr>
<tr><td><code>to</code></td><td>string</td><td>End-Datum (Y-m-d)</td><td>+90 Tage</td></tr>
<tr><td><code>class</code></td><td>string</td><td>Zusätzliche CSS-Klasse</td><td>–</td></tr>
</tbody>
</table>

<h2>📱 Responsive Spalten</h2>
<table class="params-table">
<thead>
<tr><th>Bildschirm</th><th>4 Spalten</th><th>3 Spalten</th><th>2 Spalten</th></tr>
</thead>
<tbody>
<tr><td>Desktop (>1200px)</td><td>4</td><td>3</td><td>2</td></tr>
<tr><td>Tablet (768-1200px)</td><td>3</td><td>2</td><td>2</td></tr>
<tr><td>Mobile (<768px)</td><td>1</td><td>1</td><td>1</td></tr>
</tbody>
</table>

<h2>🎯 Einsatzbereiche</h2>
<ul class="use-cases">
<li><strong>Event-Galerie</strong> - Visuelle Übersicht aller Veranstaltungen</li>
<li><strong>Kategorie-Seiten</strong> - Grid-Layout für Event-Kategorien</li>
<li><strong>Homepage-Grid</strong> - Featured Events auf Startseite</li>
<li><strong>Archiv-Seiten</strong> - Kompakte Übersicht vergangener Events</li>
</ul>
</div>
"@ | Out-File -Encoding UTF8 -FilePath "pages\demos\grid-modern.md"
Write-Host "✅ Grid: Modern" -ForegroundColor Green

# Slider: Type 1
@"
---
title: "Slider: Type 1"
excerpt: "Standard Fullwidth-Slider mit Autoplay - perfekt für Homepage-Header."
parent: "demos"
order: 4
---

<div class="demo-header">
<h1>🎬 Slider: Type 1</h1>
<p class="demo-description">Standard Fullwidth-Slider mit Autoplay und Touch-Support - ideal für Homepage-Hero-Bereiche und Event-Highlights.</p>
</div>

<div class="demo-preview">
[cts_slider view="type-1" autoplay="true" interval="6000" limit="5"]
</div>

<div class="demo-info">
<h2>✨ Features</h2>
<ul class="feature-list">
<li><strong>Autoplay</strong> - Automatisches Durchlaufen der Slides</li>
<li><strong>Touch Support</strong> - Wischen auf mobilen Geräten</li>
<li><strong>Navigation</strong> - Pfeile und Dots für manuelle Steuerung</li>
<li><strong>Fullwidth</strong> - 100% Breite, responsive Höhe</li>
<li><strong>Overlays</strong> - Gradient-Overlays für bessere Lesbarkeit</li>
<li><strong>Transitions</strong> - Sanfte Fade-Animationen</li>
</ul>

<h2>📋 Verwendung</h2>
<pre><code>[cts_slider view="type-1" autoplay="true" interval="6000"]</code></pre>

<h2>⚙️ Parameter</h2>
<table class="params-table">
<thead>
<tr><th>Parameter</th><th>Typ</th><th>Beschreibung</th><th>Standard</th></tr>
</thead>
<tbody>
<tr><td><code>view</code></td><td>string</td><td>View-Typ (type-1)</td><td>–</td></tr>
<tr><td><code>autoplay</code></td><td>bool</td><td>Automatisches Abspielen</td><td>false</td></tr>
<tr><td><code>interval</code></td><td>int</td><td>Intervall in ms (bei autoplay)</td><td>5000</td></tr>
<tr><td><code>calendar</code></td><td>string</td><td>Kommagetrennte Kalender-IDs</td><td>alle</td></tr>
<tr><td><code>limit</code></td><td>int</td><td>Max. Anzahl Slides</td><td>10</td></tr>
<tr><td><code>class</code></td><td>string</td><td>Zusätzliche CSS-Klasse</td><td>–</td></tr>
</tbody>
</table>

<h2>⚡ JavaScript API</h2>
<pre><code>// Slider steuern
const slider = document.querySelector('.cts-slider');
slider.ctsSlider.next();      // Nächste Slide
slider.ctsSlider.prev();      // Vorherige Slide
slider.ctsSlider.goTo(2);     // Zu Slide 2
slider.ctsSlider.pause();     // Autoplay pausieren
slider.ctsSlider.play();      // Autoplay starten</code></pre>

<h2>🎯 Einsatzbereiche</h2>
<ul class="use-cases">
<li><strong>Homepage Hero</strong> - Große Event-Präsentation im Header</li>
<li><strong>Event-Highlights</strong> - Featured Events rotierend anzeigen</li>
<li><strong>Image Gallery</strong> - Event-Bilder als Slideshow</li>
<li><strong>Landing Pages</strong> - Fullscreen Event-Präsentationen</li>
</ul>
</div>
"@ | Out-File -Encoding UTF8 -FilePath "pages\demos\slider-type-1.md"
Write-Host "✅ Slider: Type 1" -ForegroundColor Green

# Countdown: Type 1
@"
---
title: "Countdown: Type 1"
excerpt: "Flip-Clock Countdown zum nächsten Event - perfekt für Landing Pages."
parent: "demos"
order: 5
---

<div class="demo-header">
<h1>⏱️ Countdown: Type 1</h1>
<p class="demo-description">Flip-Clock Countdown mit automatischer Event-Erkennung - ideal für Event-Landing-Pages und Homepage-Hero-Bereiche.</p>
</div>

<div class="demo-preview">
[cts_countdown view="type-1"]
</div>

<div class="demo-info">
<h2>✨ Features</h2>
<ul class="feature-list">
<li><strong>Auto-Event</strong> - Findet automatisch das nächste Event</li>
<li><strong>Flip-Animation</strong> - Animierte Flip-Clock-Digits</li>
<li><strong>Real-Time</strong> - Sekunden-genaue Live-Aktualisierung</li>
<li><strong>Responsive</strong> - Passt sich Bildschirmgröße an</li>
<li><strong>Event-Info</strong> - Titel und Datum-Anzeige</li>
<li><strong>CTA Button</strong> - Optional Call-to-Action Link</li>
</ul>

<h2>📋 Verwendung</h2>
<pre><code>[cts_countdown view="type-1"]</code></pre>

<h2>⚙️ Parameter</h2>
<table class="params-table">
<thead>
<tr><th>Parameter</th><th>Typ</th><th>Beschreibung</th><th>Standard</th></tr>
</thead>
<tbody>
<tr><td><code>view</code></td><td>string</td><td>View-Typ (type-1)</td><td>–</td></tr>
<tr><td><code>event_id</code></td><td>int</td><td>Spezifisches Event (optional)</td><td>auto</td></tr>
<tr><td><code>calendar</code></td><td>string</td><td>Kalender-ID (bei auto)</td><td>alle</td></tr>
<tr><td><code>class</code></td><td>string</td><td>Zusätzliche CSS-Klasse</td><td>–</td></tr>
</tbody>
</table>

<h2>⚡ JavaScript Events</h2>
<pre><code>// Event-Handler
document.addEventListener('cts-countdown-complete', (e) => {
  console.log('Countdown abgelaufen!', e.detail);
});

document.addEventListener('cts-countdown-tick', (e) => {
  console.log('Sekunde:', e.detail.seconds);
});</code></pre>

<h2>🔄 Fallback-Verhalten</h2>
<ul class="use-cases">
<li><strong>Kein Event gefunden</strong> - Zeigt Meldung "Keine kommenden Events"</li>
<li><strong>Event gestartet</strong> - Zeigt "Event läuft gerade!"</li>
<li><strong>Nach Ablauf</strong> - Lädt automatisch nächstes Event nach</li>
</ul>

<h2>🎯 Einsatzbereiche</h2>
<ul class="use-cases">
<li><strong>Event Landing Page</strong> - Countdown zu Großveranstaltungen</li>
<li><strong>Homepage Hero</strong> - Countdown zum nächsten Gottesdienst</li>
<li><strong>Sidebar Widget</strong> - Kompakter Countdown in Sidebar</li>
<li><strong>Pre-Launch</strong> - Countdown bis Event-Start</li>
</ul>
</div>
"@ | Out-File -Encoding UTF8 -FilePath "pages\demos\countdown-type-1.md"
Write-Host "✅ Countdown: Type 1" -ForegroundColor Green

# Widget: Upcoming Events
@"
---
title: "Widget: Upcoming Events"
excerpt: "Kompaktes Sidebar-Widget für die nächsten 5 Events."
parent: "demos"
order: 6
---

<div class="demo-header">
<h1>📦 Widget: Upcoming Events</h1>
<p class="demo-description">Kompaktes Sidebar-Widget mit den nächsten Events - perfekt für Sidebars, Footer-Bereiche und Dashboard-Widgets.</p>
</div>

<div class="demo-preview">
[cts_widget view="upcoming-events" limit="5"]
</div>

<div class="demo-info">
<h2>✨ Features</h2>
<ul class="feature-list">
<li><strong>Kompakt</strong> - Optimiert für schmale Bereiche (min. 300px)</li>
<li><strong>Nächste Events</strong> - Zeigt nur kommende Veranstaltungen</li>
<li><strong>Datum-Badge</strong> - Farbige Datum-Anzeige</li>
<li><strong>Quick Info</strong> - Titel, Zeit und Ort auf einen Blick</li>
<li><strong>Kalender-Farben</strong> - Visuelle Kalender-Zuordnung</li>
<li><strong>Event-Links</strong> - Klickbar zu Event-Details</li>
</ul>

<h2>📋 Verwendung</h2>
<pre><code>[cts_widget view="upcoming-events" limit="5"]</code></pre>

<h2>⚙️ Parameter</h2>
<table class="params-table">
<thead>
<tr><th>Parameter</th><th>Typ</th><th>Beschreibung</th><th>Standard</th></tr>
</thead>
<tbody>
<tr><td><code>view</code></td><td>string</td><td>View-Typ (upcoming-events)</td><td>–</td></tr>
<tr><td><code>limit</code></td><td>int</td><td>Max. Anzahl Events</td><td>5</td></tr>
<tr><td><code>calendar</code></td><td>string</td><td>Kommagetrennte Kalender-IDs</td><td>alle</td></tr>
<tr><td><code>from</code></td><td>string</td><td>Start-Datum (Y-m-d)</td><td>heute</td></tr>
<tr><td><code>to</code></td><td>string</td><td>End-Datum (Y-m-d)</td><td>+30 Tage</td></tr>
<tr><td><code>class</code></td><td>string</td><td>Zusätzliche CSS-Klasse</td><td>–</td></tr>
</tbody>
</table>

<h2>🎨 Widget-Bereiche</h2>
<pre><code>// In functions.php registrieren
register_sidebar(array(
  'name' => 'Sidebar Events',
  'id' => 'sidebar-events',
  'before_widget' => '&lt;div class="widget"&gt;',
  'after_widget' => '&lt;/div&gt;',
));

// In Template verwenden
&lt;?php if (is_active_sidebar('sidebar-events')) {
  dynamic_sidebar('sidebar-events');
} ?&gt;</code></pre>

<h2>🎯 Einsatzbereiche</h2>
<ul class="use-cases">
<li><strong>Sidebar Widget</strong> - Klassische WordPress-Sidebar</li>
<li><strong>Footer Widget</strong> - Event-Übersicht im Footer</li>
<li><strong>Dashboard</strong> - Admin-Dashboard Widget</li>
<li><strong>Mobile Menü</strong> - Events in mobilem Dropdown</li>
</ul>
</div>
"@ | Out-File -Encoding UTF8 -FilePath "pages\demos\widget-upcoming-events.md"
Write-Host "✅ Widget: Upcoming Events" -ForegroundColor Green

Write-Host ""
Write-Host "✅ Alle Demo-Seiten erfolgreich aktualisiert!" -ForegroundColor Green
