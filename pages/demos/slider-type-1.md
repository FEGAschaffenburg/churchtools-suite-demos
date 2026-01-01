---
title: "Slider: Type 1"
excerpt: "Standard Fullwidth-Slider mit Autoplay - perfekt fÃ¼r Homepage-Header."
parent: "demos"
order: 4
---

<div class="demo-header">
<h1>ðŸŽ¬ Slider: Type 1</h1>
<p class="demo-description">Standard Fullwidth-Slider mit Autoplay und Touch-Support - ideal fÃ¼r Homepage-Hero-Bereiche und Event-Highlights.</p>
</div>

<div class="demo-preview">
[cts_slider view="type-1" autoplay="true" interval="6000" limit="5"]
</div>

<div class="demo-info">
<h2>âœ¨ Features</h2>
<ul class="feature-list">
<li><strong>Autoplay</strong> - Automatisches Durchlaufen der Slides</li>
<li><strong>Touch Support</strong> - Wischen auf mobilen GerÃ¤ten</li>
<li><strong>Navigation</strong> - Pfeile und Dots fÃ¼r manuelle Steuerung</li>
<li><strong>Fullwidth</strong> - 100% Breite, responsive HÃ¶he</li>
<li><strong>Overlays</strong> - Gradient-Overlays fÃ¼r bessere Lesbarkeit</li>
<li><strong>Transitions</strong> - Sanfte Fade-Animationen</li>
</ul>

<h2>ðŸ“‹ Verwendung</h2>
<pre><code>[cts_slider view="type-1" autoplay="true" interval="6000"]</code></pre>

<h2>âš™ï¸ Parameter</h2>
<table class="params-table">
<thead>
<tr><th>Parameter</th><th>Typ</th><th>Beschreibung</th><th>Standard</th></tr>
</thead>
<tbody>
<tr><td><code>view</code></td><td>string</td><td>View-Typ (type-1)</td><td>â€“</td></tr>
<tr><td><code>autoplay</code></td><td>bool</td><td>Automatisches Abspielen</td><td>false</td></tr>
<tr><td><code>interval</code></td><td>int</td><td>Intervall in ms (bei autoplay)</td><td>5000</td></tr>
<tr><td><code>calendar</code></td><td>string</td><td>Kommagetrennte Kalender-IDs</td><td>alle</td></tr>
<tr><td><code>limit</code></td><td>int</td><td>Max. Anzahl Slides</td><td>10</td></tr>
<tr><td><code>class</code></td><td>string</td><td>ZusÃ¤tzliche CSS-Klasse</td><td>â€“</td></tr>
</tbody>
</table>

<h2>âš¡ JavaScript API</h2>
<pre><code>// Slider steuern
const slider = document.querySelector('.cts-slider');
slider.ctsSlider.next();      // NÃ¤chste Slide
slider.ctsSlider.prev();      // Vorherige Slide
slider.ctsSlider.goTo(2);     // Zu Slide 2
slider.ctsSlider.pause();     // Autoplay pausieren
slider.ctsSlider.play();      // Autoplay starten</code></pre>

<h2>ðŸŽ¯ Einsatzbereiche</h2>
<ul class="use-cases">
<li><strong>Homepage Hero</strong> - GroÃŸe Event-PrÃ¤sentation im Header</li>
<li><strong>Event-Highlights</strong> - Featured Events rotierend anzeigen</li>
<li><strong>Image Gallery</strong> - Event-Bilder als Slideshow</li>
<li><strong>Landing Pages</strong> - Fullscreen Event-PrÃ¤sentationen</li>
</ul>
</div>
