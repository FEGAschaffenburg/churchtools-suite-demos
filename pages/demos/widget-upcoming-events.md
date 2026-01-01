---
title: "Widget: Upcoming Events"
excerpt: "Kompaktes Sidebar-Widget fÃ¼r die nÃ¤chsten 5 Events."
parent: "demos"
order: 6
---

<div class="demo-header">
<h1>ðŸ“¦ Widget: Upcoming Events</h1>
<p class="demo-description">Kompaktes Sidebar-Widget mit den nÃ¤chsten Events - perfekt fÃ¼r Sidebars, Footer-Bereiche und Dashboard-Widgets.</p>
</div>

<div class="demo-preview">
[cts_widget view="upcoming-events" limit="5"]
</div>

<div class="demo-info">
<h2>âœ¨ Features</h2>
<ul class="feature-list">
<li><strong>Kompakt</strong> - Optimiert fÃ¼r schmale Bereiche (min. 300px)</li>
<li><strong>NÃ¤chste Events</strong> - Zeigt nur kommende Veranstaltungen</li>
<li><strong>Datum-Badge</strong> - Farbige Datum-Anzeige</li>
<li><strong>Quick Info</strong> - Titel, Zeit und Ort auf einen Blick</li>
<li><strong>Kalender-Farben</strong> - Visuelle Kalender-Zuordnung</li>
<li><strong>Event-Links</strong> - Klickbar zu Event-Details</li>
</ul>

<h2>ðŸ“‹ Verwendung</h2>
<pre><code>[cts_widget view="upcoming-events" limit="5"]</code></pre>

<h2>âš™ï¸ Parameter</h2>
<table class="params-table">
<thead>
<tr><th>Parameter</th><th>Typ</th><th>Beschreibung</th><th>Standard</th></tr>
</thead>
<tbody>
<tr><td><code>view</code></td><td>string</td><td>View-Typ (upcoming-events)</td><td>â€“</td></tr>
<tr><td><code>limit</code></td><td>int</td><td>Max. Anzahl Events</td><td>5</td></tr>
<tr><td><code>calendar</code></td><td>string</td><td>Kommagetrennte Kalender-IDs</td><td>alle</td></tr>
<tr><td><code>from</code></td><td>string</td><td>Start-Datum (Y-m-d)</td><td>heute</td></tr>
<tr><td><code>to</code></td><td>string</td><td>End-Datum (Y-m-d)</td><td>+30 Tage</td></tr>
<tr><td><code>class</code></td><td>string</td><td>ZusÃ¤tzliche CSS-Klasse</td><td>â€“</td></tr>
</tbody>
</table>

<h2>ðŸŽ¨ Widget-Bereiche</h2>
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

<h2>ðŸŽ¯ Einsatzbereiche</h2>
<ul class="use-cases">
<li><strong>Sidebar Widget</strong> - Klassische WordPress-Sidebar</li>
<li><strong>Footer Widget</strong> - Event-Ãœbersicht im Footer</li>
<li><strong>Dashboard</strong> - Admin-Dashboard Widget</li>
<li><strong>Mobile MenÃ¼</strong> - Events in mobilem Dropdown</li>
</ul>
</div>
