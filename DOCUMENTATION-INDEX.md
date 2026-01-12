# 📚 ChurchTools Suite Demo v1.0.3.1 - Documentation Index

**Version:** 1.0.3.1  
**Status:** ✅ Production Ready  
**Date:** Dezember 2024

---

## 🎯 FÜR VERSCHIEDENE ZIELGRUPPEN

### 👤 Für den Benutzer (Deployment)
**Du bist:** Jemand, der das Plugin auf dem Server deployen muss  
**Start hier:**

1. **[QUICK-START.md](QUICK-START.md)** ⚡ (5 Minuten)
   - Schnelle 3-Schritt Anleitung
   - Validierungs-Checkliste
   - FAQ

2. **[UPDATE-DEPLOYMENT.md](UPDATE-DEPLOYMENT.md)** 📖 (10 Minuten)
   - Detailliertes Deployment-Guide
   - Fehlerbehandlung
   - Tests
   - Manuelle SQL (Fallback)

3. **`validate-installation.php`** 🔍 (Browser öffnen)
   - Nach Deployment öffnen
   - Alle Checks sollten ✅ grün sein
   - Zeigt konkrete Fehler bei Problemen

---

### 💻 Für den Admin (Wartung)
**Du bist:** WordPress-Admin, der das System überwachen muss  
**Start hier:**

1. **[DEPLOYMENT-STATUS.md](DEPLOYMENT-STATUS.md)** 📊
   - Kompletter Status-Report
   - Alle Bugs und deren Fixes
   - Deployment-Checkliste

2. **[DEPLOYMENT-INSTRUCTIONS.md](DEPLOYMENT-INSTRUCTIONS.md)** 📖
   - Technische Details aller Fixes
   - Database Schema
   - Troubleshooting für Admins

3. **`validate-installation.php`** 🔍
   - Regelmäßig öffnen zur Validierung
   - Prüft: Version, Tabelle, Spalten, Indexes

---

### 👨‍💻 Für den Entwickler (Code)
**Du bist:** Developer, der den Code verstehen muss  
**Start hier:**

1. **[DEPLOYMENT-STATUS.md](DEPLOYMENT-STATUS.md)** - Technical Überblick
   - Version-Vergleich
   - Bugs behoben
   - Git Commits

2. **`churchtools-suite-demo.php`** - Hauptdatei
   - Lies: `init()` Methode (Tabellenerstellung)
   - Lies: `create_tables()` Methode (Schema)
   - Lies: Activation Hooks (am Ende)

3. **`validate-installation.php`** - Code anschauen
   - Prüf: Wie wird Validation gemacht?
   - Nützlich für ähnliche Checks

4. **`.gitlog`** oder GitHub
   - Commits: 193a394, 593349b, dc36e3d, 3efa4da
   - Zeigt: Exact was changed in jedem Commit

---

### 🚀 Für den DevOps/Ops Team
**Du bist:** Ops-Person, die den Deployment automatisieren soll  
**Start hier:**

1. **`deploy-demo-plugin.ps1`** - PowerShell Skript
   - Erstellt ZIP-Paket automatisch
   - Kann in CI/CD Pipeline integriert werden
   - Nutze: `.\deploy-demo-plugin.ps1 -Version 1.0.3.1`

2. **[UPDATE-DEPLOYMENT.md](UPDATE-DEPLOYMENT.md)** - Deployment-Optionen
   - Option 1: Manual (für Testing)
   - Option 2: FTP (für kleine Teams)
   - Option 3: SSH/Terminal (für Automation)

3. **Weitere Automatisierung:**
   ```bash
   # Shell-Wrapper für PowerShell
   #!/bin/bash
   powershell -ExecutionPolicy Bypass -File deploy-demo-plugin.ps1
   
   # Dann mit SCP hochladen und extrahieren
   scp churchtools-suite-demo-1.0.3.1.zip user@server:/wp-content/plugins/
   ssh user@server "cd /wp-content/plugins && unzip ..."
   ```

---

## 📋 DATEISTRUKTUR

### Hauptdateien

| Datei | Typ | Größe | Zweck | Audience |
|-------|-----|-------|-------|----------|
| **README.md** | Markdown | ~2 KB | Overview + Links | Alle |
| **QUICK-START.md** | Markdown | ~5 KB | 5-Min Anleitung | User |
| **UPDATE-DEPLOYMENT.md** | Markdown | ~15 KB | Deployment Guide | User + Admin |
| **DEPLOYMENT-INSTRUCTIONS.md** | Markdown | ~12 KB | Technische Details | Admin + Dev |
| **DEPLOYMENT-STATUS.md** | Markdown | ~20 KB | Kompletter Status | Alle |

### Code-Dateien

| Datei | Typ | Zweck |
|-------|-----|-------|
| **churchtools-suite-demo.php** | PHP | Main Plugin (UPDATED v1.0.3.1) |
| **deploy-demo-plugin.ps1** | PowerShell | ZIP-Creator (NEW) |
| **validate-installation.php** | PHP | Browser-Validator (NEW) |

### Klassische Dateien

| Datei | Zweck |
|-------|-------|
| **admin/class-demo-admin.php** | Admin-Interface |
| **includes/repositories/** | Database Layer |
| **includes/services/** | Business Logic |
| **templates/demo/** | HTML-Templates |

---

## 🔄 WORKFLOW: Von Entwicklung bis Deployment

### Phase 1: Entwicklung (lokal)
```
1. Code ändern in churchtools-suite-demo.php
2. Testen in WordPress (lokal)
3. Git commits machen: git add . && git commit -m "..."
4. Pushen: git push origin master
```

### Phase 2: Release (Git)
```
1. Alle Commits final? → Ja
2. Git tag erstellen: git tag v1.0.3.1
3. Pushen: git push origin --tags
4. GitHub Release erstellen (optional)
```

### Phase 3: ZIP erstellen (lokal)
```
.\deploy-demo-plugin.ps1
→ Erstellt: C:\privat\churchtools-suite-demo-1.0.3.1.zip
```

### Phase 4: Deployment (Server)
```
1. ZIP hochladen zu /wp-content/plugins/
2. Entzippen
3. Alte Version löschen
4. Plugin aktivieren (WordPress Admin)
→ Activation Hook feuert → Tabelle erstellt
```

### Phase 5: Validierung (Server)
```
1. Browser öffnen: validate-installation.php
2. Alle ✅ grün?
3. Ja → Fertig! 🎉
4. Nein → Fehlerbehandlung anschauen
```

---

## 🔗 QUICK LINKS

### Lokal
- **Commit History:** `git log --oneline -10`
- **Uncommitted Changes:** `git status`
- **Deploy Skript:** `.\deploy-demo-plugin.ps1`

### Server (nach Deployment)
- **Validator:** `/wp-content/plugins/churchtools-suite-demo/validate-installation.php`
- **Logs:** `/wp-content/debug.log`
- **Admin:** `/wp-admin/plugins.php`

### GitHub
- **Repository:** https://github.com/FEGAschaffenburg/churchtools-suite
- **Issues:** https://github.com/FEGAschaffenburg/churchtools-suite/issues
- **Releases:** https://github.com/FEGAschaffenburg/churchtools-suite/releases

---

## 📊 STATUS ÜBERSICHT

### Release-Status
- **Main Plugin:** ✅ v1.0.3.1 auf GitHub
- **Demo Plugin:** ✅ v1.0.3.1 lokal fertig, bereit für Upload
- **Documentation:** ✅ Vollständig
- **Tools:** ✅ Deploy-Skript + Validator erstellt

### Deployment-Status
- **Code:** ✅ Ready
- **Database Schema:** ✅ Complete
- **Tests:** ✅ Validator erstellt
- **Server Upload:** ⏳ Awaiting user action

### Bekannte Probleme
- ✅ Keine (v1.0.3.1 behebt alle bisherigen Bugs)

---

## 💡 PRO TIPS

### Für schnellere Deployments
```bash
# 1. PowerShell-Skript automatisieren
.\deploy-demo-plugin.ps1 -Version 1.0.3.1

# 2. FTP-Skript (z.B. WinSCP)
"open user@server" > deploy.txt
"cd /wp-content/plugins/" >> deploy.txt
"put churchtools-suite-demo-1.0.3.1.zip" >> deploy.txt
# Dann: winscp /script=deploy.txt

# 3. SSH-One-Liner
ssh user@server "cd /wp-content/plugins && unzip churchtools-suite-demo-1.0.3.1.zip && rm -rf churchtools-suite-demo-1.0.3.0"
```

### Für Monitoring
```bash
# Logs in Echtzeit überwachen
tail -f /wp-content/debug.log | grep "ChurchTools"

# Validator regelmäßig prüfen (Cron)
0 2 * * * curl https://example.com/wp-content/plugins/.../validate.php
```

### Für Backup
```bash
# Database sichern vor Deployment
mysqldump -u user -p dbname > backup-$(date +%Y%m%d).sql

# Plugin sichern
tar czf churchtools-demo-backup-$(date +%Y%m%d).tar.gz churchtools-suite-demo/
```

---

## 🎓 WEITERE RESSOURCEN

### WordPress
- [WordPress Plugin Handbook](https://developer.wordpress.org/plugins/)
- [WordPress dbDelta](https://developer.wordpress.org/reference/functions/dbDelta/)
- [Activation Hooks](https://developer.wordpress.org/plugins/lifecycle/activating-and-deactivating-a-plugin/)

### PHP
- [PHP Type Declarations](https://www.php.net/manual/en/language.types.declarations.php)
- [PHP Namespaces](https://www.php.net/manual/en/language.namespaces.php)

### ChurchTools API
- [ChurchTools API Docs](https://api.church.tools/)

---

## ❓ FAQ

**Q: Welche Datei soll ich zuerst lesen?**  
A: Kommt drauf an. User → QUICK-START.md. Admin → DEPLOYMENT-STATUS.md. Dev → Code.

**Q: Wo finde ich die neuesten Änderungen?**  
A: In Git Commits oder hier in DEPLOYMENT-STATUS.md.

**Q: Kann ich alles automatisieren?**  
A: Ja! PowerShell-Skript exists already. Nutze `deploy-demo-plugin.ps1`.

**Q: Was macht der Validator?**  
A: Prüft ob alles installiert ist. Öffne einfach im Browser nach Deployment.

**Q: Wo sind die Fehler wenn etwas schiefgeht?**  
A: `/wp-content/debug.log` + Validator-Output prüfen.

---

## 📞 SUPPORT KONTAKT

Falls Probleme bei Deployment:
1. Validator öffnen
2. UPDATE-DEPLOYMENT.md Fehlerbehandlung lesen
3. Logs prüfen (/wp-content/debug.log)
4. GitHub Issues öffnen

---

**Index-Version:** 1.0  
**Last Updated:** Dezember 2024  
**Status:** ✅ Complete
