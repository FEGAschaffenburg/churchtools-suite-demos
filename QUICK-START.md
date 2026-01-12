# 🚀 ChurchTools Suite Demo v1.0.3.1 - QUICK START

**Ziel:** Demo Plugin v1.0.3.1 auf dem Server deployen  
**Zeit:** ~5 Minuten

---

## ⚡ 3-SCHRITT DEPLOYMENT

### SCHRITT 1: ZIP erstellen (lokal)
```powershell
cd C:\Users\nauma\OneDrive\Plugin_neu\churchtools-suite-demo
.\deploy-demo-plugin.ps1
```
**Ergebnis:** `C:\privat\churchtools-suite-demo-1.0.3.1.zip`

### SCHRITT 2: Hochladen (FTP/SSH)
- Hochladen zu: `/wp-content/plugins/churchtools-suite-demo-1.0.3.1.zip`
- Entzippen
- Alte Version `churchtools-suite-demo-1.0.3.0` löschen

### SCHRITT 3: Aktivieren (WordPress Admin)
```
Plugins → "ChurchTools Suite Demo" → Aktivieren
```

---

## ✅ VALIDIERUNG (2 SEKUNDEN)

Browser öffnen:
```
https://example.com/wp-content/plugins/churchtools-suite-demo/validate-installation.php
```

**Erwartet:** Alle ✅ grün

Falls nicht:
1. Screenshot machen
2. Links auf **ERROR** klicken (rote Felder)
3. Lösungstipps unten anschauen

---

## 🧪 TESTS

```
1. Events-Seite → Demo-Event klicken → Modal sollte öffnen ✅
2. Demo-Registrierung → Formular ausfüllen → E-Mail prüfen ✅
3. Admin → ChurchTools Suite → Demo Users → User sichtbar ✅
```

---

## 🆘 WENN ETWAS SCHIEFGEHT

### Problem: "Table doesn't exist" Fehler
```
→ Plugin deaktivieren
→ 30 Sekunden warten
→ Plugin aktivieren
→ Validator neuladen (F5)
```

### Problem: "Error Loading Event" Modal
```
→ Browser Cache leeren: Ctrl+Shift+Del
→ Seite neuladen: Ctrl+F5
→ Plugin neu aktivieren (siehe oben)
```

### Problem: Version zeigt noch 1.0.3
```
→ Admin → Plugins → Seite neu laden: Ctrl+F5
→ Browser Cache komplett leeren
```

### Problem: Validator zeigt noch Fehler
```
→ Manuelle SQL ausführen (in UPDATE-DEPLOYMENT.md)
→ Plugin neu aktivieren
→ Validator neuladen
```

---

## 📖 AUSFÜHRLICHE DOKUMENTATION

- **UPDATE-DEPLOYMENT.md** - Langfassung mit allen Details
- **DEPLOYMENT-INSTRUCTIONS.md** - Technische Tiefdoku
- **validate-installation.php** - Interaktiver Helfer im Browser

---

## 📋 CHECKLISTE

- [ ] `.\deploy-demo-plugin.ps1` ausgeführt
- [ ] ZIP hochgeladen
- [ ] Alte Version gelöscht
- [ ] Plugin aktiviert (Admin)
- [ ] Validator geöffnet (alle ✅?)
- [ ] Demo-Event klicken (Modal öffnet?)
- [ ] Demo-Registrierung testen (E-Mail?)
- [ ] Admin Panel (User sichtbar?)

**Alle erledigt?** → 🎉 **Fertig!**

---

## 💾 VERSIONEN

| Version | Datum | Status |
|---------|-------|--------|
| 1.0.3.1 | Dezember 2024 | ✅ Live |
| 1.0.3 | Dezember 2024 | ❌ Obsolet |
| 1.0.0-1.0.2 | Früher | ❌ Obsolet |

---

## 🔗 LINKS

- **GitHub Repo:** https://github.com/FEGAschaffenburg/churchtools-suite
- **Hauptplugin v1.0.3.1:** Muss auch aktiv sein!
- **WordPress:** https://example.com/wp-admin/

---

## ❓ HÄUFIGE FRAGEN

**Q: Muss ich die alte Version vorher löschen?**  
A: Ja! `churchtools-suite-demo-1.0.3.0` komplett löschen, dann neu entzippen.

**Q: Was ist der Validator?**  
A: PHP-Datei, die prüft ob alles installiert ist. Nach Deployment öffnen.

**Q: Warum 5 Minuten?**  
A: Includes Hochladen (1-2 min), Aktivieren (30 sec), Validierung (30 sec).

**Q: Was mache ich bei Errors?**  
A: Siehe "🆘 WENN ETWAS SCHIEFGEHT" oben oder UPDATE-DEPLOYMENT.md öffnen.

---

**Status:** ✅ READY  
**Fragen?** → Siehe UPDATE-DEPLOYMENT.md oder Validator öffnen
