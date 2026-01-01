# GitHub Actions Setup

Diese Datei erklärt, wie du die GitHub Actions Secrets für automatisches Deployment konfigurierst.

## Benötigte Secrets

Gehe zu **Settings → Secrets and variables → Actions** in deinem GitHub Repository und füge folgende Secrets hinzu:

### 1. SSH_PRIVATE_KEY

**Beschreibung**: Private SSH-Key für Server-Zugriff

**Erstellen**:
```bash
# Generiere SSH-Key-Paar (auf deinem lokalen Computer)
ssh-keygen -t ed25519 -C "github-actions-deploy" -f ~/.ssh/github_deploy

# Zeige Private Key an (füge diesen als Secret ein)
cat ~/.ssh/github_deploy

# Zeige Public Key an (füge diesen auf dem Server ein)
cat ~/.ssh/github_deploy.pub
```

**Auf dem Server** (via SSH):
```bash
# Füge Public Key zu authorized_keys hinzu
echo "DEIN_PUBLIC_KEY" >> ~/.ssh/authorized_keys
chmod 600 ~/.ssh/authorized_keys
```

**In GitHub**:
- Name: `SSH_PRIVATE_KEY`
- Value: Kompletter Inhalt von `~/.ssh/github_deploy` (inkl. `-----BEGIN ... KEY-----`)

### 2. DEPLOY_HOST

**Beschreibung**: Server-Hostname oder IP-Adresse

**Beispiel**: `plugin.aschaffenburg.feg.de` oder `192.168.1.100`

**In GitHub**:
- Name: `DEPLOY_HOST`
- Value: `plugin.aschaffenburg.feg.de`

### 3. DEPLOY_USER

**Beschreibung**: SSH-Benutzername auf dem Server

**Beispiel**: `www-data`, `wordpress`, `ubuntu`

**In GitHub**:
- Name: `DEPLOY_USER`
- Value: `wordpress`

### 4. WP_PATH

**Beschreibung**: Absoluter Pfad zur WordPress-Installation auf dem Server

**Beispiel**: `/var/www/html/wordpress` oder `/home/wordpress/public_html`

**In GitHub**:
- Name: `WP_PATH`
- Value: `/var/www/html/wordpress`

### 5. SLACK_WEBHOOK_URL (Optional)

**Beschreibung**: Slack Webhook für Deployment-Benachrichtigungen

**Erstellen**:
1. Gehe zu https://api.slack.com/apps
2. Erstelle neue App
3. Aktiviere "Incoming Webhooks"
4. Erstelle neuen Webhook für deinen Channel
5. Kopiere Webhook-URL

**In GitHub**:
- Name: `SLACK_WEBHOOK_URL`
- Value: `https://hooks.slack.com/services/...`

## Deployment testen

### Manuell triggern

1. Gehe zu **Actions → Deploy Demo Site** in GitHub
2. Klicke auf **Run workflow**
3. Wähle Branch: `master` oder `main`
4. Klicke auf **Run workflow**

### Automatisch bei Push

Deployment läuft automatisch bei jedem Push auf `main` oder `master` Branch:

```bash
git add .
git commit -m "Update demo content"
git push origin master
```

## Troubleshooting

### "Permission denied (publickey)"

**Problem**: SSH-Key nicht richtig konfiguriert

**Lösung**:
- Prüfe SSH_PRIVATE_KEY Secret (kompletter Key inkl. Header/Footer?)
- Prüfe Public Key auf Server (`~/.ssh/authorized_keys`)
- Prüfe Berechtigungen: `chmod 700 ~/.ssh` und `chmod 600 ~/.ssh/authorized_keys`

### "rsync: command not found"

**Problem**: rsync nicht auf dem Server installiert

**Lösung**:
```bash
# Ubuntu/Debian
sudo apt-get install rsync

# CentOS/RHEL
sudo yum install rsync
```

### "wp: command not found"

**Problem**: WP-CLI nicht installiert (nicht kritisch, Deployment funktioniert trotzdem)

**Lösung** (optional):
```bash
# WP-CLI installieren
curl -O https://raw.githubusercontent.com/wp-cli/builds/gh-pages/phar/wp-cli.phar
chmod +x wp-cli.phar
sudo mv wp-cli.phar /usr/local/bin/wp
```

### "Theme not activated"

**Problem**: WP-CLI Fehler oder fehlende Berechtigungen

**Lösung**:
- Aktiviere Theme manuell im WordPress-Admin
- Oder via WP-CLI auf dem Server: `wp theme activate cts-demo-theme`

## Server-Anforderungen

- **SSH-Zugriff** mit Key-basierter Authentifizierung
- **rsync** installiert
- **WordPress** installiert und konfiguriert
- **Schreibrechte** für Deploy-User auf `wp-content/themes/`
- **WP-CLI** (optional, aber empfohlen)

## Sicherheit

### Best Practices

- ✅ Verwende dedizierten Deploy-User (nicht root)
- ✅ Beschränke SSH-Key auf bestimmte Commands (optional)
- ✅ Verwende Ed25519 Keys (moderner, sicherer)
- ✅ Rotiere SSH-Keys regelmäßig
- ❌ Teile Private Keys niemals

### SSH-Key mit Command-Restriction (Advanced)

Füge in `~/.ssh/authorized_keys` auf dem Server hinzu:

```
command="rsync --server",no-port-forwarding,no-X11-forwarding,no-agent-forwarding,no-pty ssh-ed25519 AAAA...
```

Dadurch kann der Key nur für rsync verwendet werden.

## Workflow-Anpassungen

### Deployment nur für bestimmte Dateien

Ändere `deploy.yml`:

```yaml
- name: Deploy theme (only CSS/JS)
  run: |
    rsync -avz \
      --include='*.css' \
      --include='*.js' \
      --exclude='*' \
      theme/cts-demo-theme/assets/ \
      ${{ secrets.DEPLOY_USER }}@${{ secrets.DEPLOY_HOST }}:...
```

### Deployment mit Backup

Ändere `deploy.yml`:

```yaml
- name: Backup current theme
  run: |
    ssh ${{ secrets.DEPLOY_USER }}@${{ secrets.DEPLOY_HOST }} \
      "cd ${{ secrets.WP_PATH }}/wp-content/themes && \
       tar -czf cts-demo-theme-backup-$(date +%Y%m%d-%H%M%S).tar.gz cts-demo-theme/"
```

## Support

Fragen zu GitHub Actions Setup?

- **[GitHub Actions Docs](https://docs.github.com/en/actions)**
- **[rsync Docs](https://linux.die.net/man/1/rsync)**
- **[WP-CLI Docs](https://wp-cli.org/)**
