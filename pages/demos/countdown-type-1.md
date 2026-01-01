---
title: "Countdown: Type 1"
excerpt: "Flip-Clock Countdown zum nÃ¤chsten Event - perfekt fÃ¼r Landing Pages."
parent: "demos"
order: 5
---

<div class="demo-header">
<h1>â±ï¸ Countdown: Type 1</h1>
<p class="demo-description">Flip-Clock Countdown mit automatischer Event-Erkennung - ideal fÃ¼r Event-Landing-Pages und Homepage-Hero-Bereiche.</p>
</div>

<div class="demo-preview">
[cts_countdown view="type-1"]
</div>

<div class="demo-info">
<h2>âœ¨ Features</h2>
<ul class="feature-list">
<li><strong>Auto-Event</strong> - Findet automatisch das nÃ¤chste Event</li>
<li><strong>Flip-Animation</strong> - Animierte Flip-Clock-Digits</li>
<li><strong>Real-Time</strong> - Sekunden-genaue Live-Aktualisierung</li>
<li><strong>Responsive</strong> - Passt sich BildschirmgrÃ¶ÃŸe an</li>
<li><strong>Event-Info</strong> - Titel und Datum-Anzeige</li>
<li><strong>CTA Button</strong> - Optional Call-to-Action Link</li>
</ul>

<h2>ðŸ“‹ Verwendung</h2>
<pre><code>[cts_countdown view="type-1"]</code></pre>

<h2>âš™ï¸ Parameter</h2>
<table class="params-table">
<thead>
<tr><th>Parameter</th><th>Typ</th><th>Beschreibung</th><th>Standard</th></tr>
</thead>
<tbody>
<tr><td><code>view</code></td><td>string</td><td>View-Typ (type-1)</td><td>â€“</td></tr>
<tr><td><code>event_id</code></td><td>int</td><td>Spezifisches Event (optional)</td><td>auto</td></tr>
<tr><td><code>calendar</code></td><td>string</td><td>Kalender-ID (bei auto)</td><td>alle</td></tr>
<tr><td><code>class</code></td><td>string</td><td>ZusÃ¤tzliche CSS-Klasse</td><td>â€“</td></tr>
</tbody>
</table>

<h2>âš¡ JavaScript Events</h2>
<pre><code>// Event-Handler
document.addEventListener('cts-countdown-complete', (e) => {
  console.log('Countdown abgelaufen!', e.detail);
});

document.addEventListener('cts-countdown-tick', (e) => {
  console.log('Sekunde:', e.detail.seconds);
});</code></pre>

<h2>ðŸ”„ Fallback-Verhalten</h2>
<ul class="use-cases">
<li><strong>Kein Event gefunden</strong> - Zeigt Meldung "Keine kommenden Events"</li>
<li><strong>Event gestartet</strong> - Zeigt "Event lÃ¤uft gerade!"</li>
<li><strong>Nach Ablauf</strong> - LÃ¤dt automatisch nÃ¤chstes Event nach</li>
</ul>

<h2>ðŸŽ¯ Einsatzbereiche</h2>
<ul class="use-cases">
<li><strong>Event Landing Page</strong> - Countdown zu GroÃŸveranstaltungen</li>
<li><strong>Homepage Hero</strong> - Countdown zum nÃ¤chsten Gottesdienst</li>
<li><strong>Sidebar Widget</strong> - Kompakter Countdown in Sidebar</li>
<li><strong>Pre-Launch</strong> - Countdown bis Event-Start</li>
</ul>
</div>
