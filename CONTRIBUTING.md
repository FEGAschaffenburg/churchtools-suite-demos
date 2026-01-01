# Contributing to ChurchTools Suite Demos

Vielen Dank für dein Interesse, zur Demo-Website beizutragen! 🎉

## Wie du beitragen kannst

### 1. Neue Demos hinzufügen

Wenn du ein neues Demo-Template zeigen möchtest:

**Schritt 1: Markdown-Datei erstellen**

Erstelle eine neue Datei in `pages/demos/`:

```markdown
---
title: Dein Demo-Titel
shortcode: '[cts_calendar view="deine-variante"]'
category: calendar|list|grid|slider|single
difficulty: beginner|intermediate|advanced
excerpt: Kurze Beschreibung (1-2 Sätze)
---

## Übersicht

Beschreibe dein Demo hier...

## Features

- ✅ Feature 1
- ✅ Feature 2

## Verwendung

### Basis-Shortcode

```
[dein_shortcode]
```

## Parameter

| Parameter | Typ | Default | Beschreibung |
|-----------|-----|---------|--------------|
| `view` | String | - | Template-Variante |

## Anpassung

Zeige CSS/Template-Overrides...

## Best Practices

- ✅ Do this
- ❌ Don't do that
```

**Schritt 2: Pull Request erstellen**

1. Forke das Repository
2. Erstelle Branch: `git checkout -b demo/deine-variante`
3. Committe deine Änderungen: `git commit -m "Add demo: Deine Variante"`
4. Pushe Branch: `git push origin demo/deine-variante`
5. Erstelle Pull Request auf GitHub

### 2. Dokumentation verbessern

Wenn du die Dokumentation verbessern möchtest:

**Neue Dokumentations-Seite**:

Erstelle Datei in `pages/docs/`:

```markdown
---
title: Dein Thema
excerpt: Kurzbeschreibung
---

# Dein Thema

Deine Dokumentation hier...
```

**Bestehende Seite aktualisieren**:

1. Finde Markdown-Datei in `pages/docs/`
2. Bearbeite Inhalt
3. Committe und erstelle Pull Request

### 3. Theme-Verbesserungen

**CSS-Anpassungen**:

Bearbeite `theme/cts-demo-theme/style.css`:

- Verwende CSS-Variablen für konsistentes Theming
- Teste Responsive-Verhalten (Desktop, Tablet, Mobile)
- Prüfe Browser-Kompatibilität (Chrome, Firefox, Safari, Edge)

**PHP-Template-Verbesserungen**:

Bearbeite Templates in `theme/cts-demo-theme/`:

- Halte Code clean und kommentiert
- Verwende WordPress Best Practices
- Escaping: `esc_html()`, `esc_url()`, `esc_attr()`
- Sanitization: `sanitize_text_field()`, etc.

### 4. Bugs melden

Gefunden einen Bug? Melde ihn:

**Schritt 1: Prüfen, ob bereits gemeldet**

- Durchsuche [GitHub Issues](https://github.com/FEGAschaffenburg/churchtools-suite-demos/issues)

**Schritt 2: Neues Issue erstellen**

Verwende folgendes Template:

```
**Beschreibung**
Klare Beschreibung des Problems

**Schritte zum Reproduzieren**
1. Gehe zu '...'
2. Klicke auf '....'
3. Scroll runter zu '....'
4. Fehler erscheint

**Erwartetes Verhalten**
Was sollte passieren

**Screenshots**
Falls relevant

**Umgebung**
- Browser: [z.B. Chrome 120]
- WordPress: [z.B. 6.4]
- PHP: [z.B. 8.2]
```

## Code-Konventionen

### PHP

- **WordPress Coding Standards** befolgen
- **PSR-12** für allgemeine PHP-Konventionen
- **PHPDoc** Kommentare für alle Funktionen
- **Escaping/Sanitization** immer verwenden

**Beispiel**:

```php
/**
 * Get demo category from meta or slug
 *
 * @param int $post_id Post ID
 * @return string Category slug
 */
function cts_get_demo_category( $post_id ) {
    $category = get_post_meta( $post_id, 'demo_category', true );
    
    if ( ! $category ) {
        $post = get_post( $post_id );
        $category = sanitize_title( $post->post_name );
    }
    
    return sanitize_text_field( $category );
}
```

### CSS

- **BEM-Naming** für Klassen: `.cts-block__element--modifier`
- **CSS-Variablen** für wiederverwendbare Werte
- **Mobile-First** Responsive Design
- **Kommentare** für komplexe Bereiche

**Beispiel**:

```css
/* Demo Preview Section */
.cts-demo-preview {
    --preview-padding: 2rem;
    --preview-border: 1px solid var(--border-color);
    
    padding: var(--preview-padding);
    border: var(--preview-border);
    border-radius: 8px;
}

.cts-demo-preview__header {
    margin-bottom: 1rem;
}

@media (max-width: 768px) {
    .cts-demo-preview {
        --preview-padding: 1rem;
    }
}
```

### JavaScript

- **ES6+** Syntax verwenden
- **Strict Mode** aktivieren
- **Event Delegation** für dynamische Elemente
- **Kommentare** für komplexe Logik

**Beispiel**:

```javascript
/**
 * Initialize clipboard functionality
 */
function initCopyButtons() {
    const buttons = document.querySelectorAll('.copy-button');
    
    buttons.forEach(button => {
        button.addEventListener('click', handleCopy);
    });
}

/**
 * Handle copy button click
 * @param {Event} e Click event
 */
function handleCopy(e) {
    // Implementation...
}
```

### Markdown

- **YAML Frontmatter** für Meta-Daten
- **Überschriften** hierarchisch (H2 für Hauptabschnitte, H3 für Unterabschnitte)
- **Code-Blöcke** mit Sprach-Indikator: ` ```php `, ` ```css `
- **Links** relativ: `[Text](/documentation/page/)` statt `https://...`

## Pull Request Prozess

### 1. Branch erstellen

```bash
# Feature-Branch
git checkout -b feature/beschreibung

# Bugfix-Branch
git checkout -b bugfix/beschreibung

# Demo-Branch
git checkout -b demo/varianten-name
```

### 2. Änderungen committen

```bash
# Staging
git add .

# Commit mit aussagekräftiger Message
git commit -m "Add feature: Beschreibung

- Detail 1
- Detail 2"
```

**Commit-Message Konventionen**:

- `Add`: Neue Features/Dateien
- `Update`: Änderungen an bestehenden Dateien
- `Fix`: Bugfixes
- `Remove`: Entfernte Features/Dateien
- `Refactor`: Code-Umstrukturierung ohne Funktionsänderung

### 3. Push und Pull Request

```bash
# Push Branch
git push origin feature/beschreibung
```

**Auf GitHub**:

1. Gehe zu Repository
2. Klicke "Compare & pull request"
3. Fülle Template aus:
   - **Title**: Kurze Beschreibung
   - **Description**: Detaillierte Änderungen
   - **Screenshots**: Falls UI-Änderungen
   - **Testing**: Wie du getestet hast
4. Klicke "Create pull request"

### 4. Review-Prozess

- Warte auf Review von Maintainern
- Reagiere auf Feedback
- Mache requested changes
- Nach Approval: Merge durch Maintainer

## Testen

### Lokales Testing

**Setup**:

```powershell
# WordPress installieren
# Theme aktivieren
.\scripts\setup.ps1 -WordPressPath "C:\xampp\htdocs\wordpress"

# Pages importieren
.\scripts\import-pages.ps1 -WordPressPath "C:\xampp\htdocs\wordpress"
```

**Browser-Tests**:

- Chrome (latest)
- Firefox (latest)
- Safari (latest)
- Edge (latest)

**Responsive-Tests**:

- Desktop (1920px)
- Laptop (1366px)
- Tablet (768px)
- Mobile (375px)

### Checkliste vor Pull Request

- [ ] Code folgt Konventionen
- [ ] Kommentare hinzugefügt
- [ ] Getestet in mehreren Browsern
- [ ] Responsive Design geprüft
- [ ] Keine Console-Errors
- [ ] Markdown-Formatierung korrekt
- [ ] Screenshots/GIFs bei UI-Änderungen

## Hilfe bekommen

### Fragen stellen

- **[GitHub Discussions](https://github.com/FEGAschaffenburg/churchtools-suite-demos/discussions)** - Allgemeine Fragen
- **[GitHub Issues](https://github.com/FEGAschaffenburg/churchtools-suite-demos/issues)** - Bug-Reports & Feature-Requests

### Ressourcen

- **[WordPress Coding Standards](https://developer.wordpress.org/coding-standards/)**
- **[WordPress Theme Handbook](https://developer.wordpress.org/themes/)**
- **[Markdown Guide](https://www.markdownguide.org/)**
- **[Git Basics](https://git-scm.com/book/en/v2)**

## Lizenz

Indem du zu diesem Projekt beiträgst, stimmst du zu, dass deine Beiträge unter der gleichen Lizenz wie das Hauptprojekt veröffentlicht werden.

---

**Danke für deine Beiträge!** 🙏

Jeder Beitrag hilft, die ChurchTools Suite Demo-Website besser zu machen.
