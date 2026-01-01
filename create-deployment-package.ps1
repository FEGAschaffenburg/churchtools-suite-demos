# ChurchTools Suite Demo Site - Deployment Package Creator
# Creates a production-ready ZIP file for server upload

param(
    [string]$Version = "1.0.1",
    [string]$OutputDir = "C:\privat\"
)

Write-Host "===========================================" -ForegroundColor Cyan
Write-Host "ChurchTools Suite - Deployment Package" -ForegroundColor Cyan
Write-Host "Version: $Version" -ForegroundColor White
Write-Host "===========================================" -ForegroundColor Cyan
Write-Host ""

$timestamp = Get-Date -Format "yyyy-MM-dd_HHmmss"
$zipName = "churchtools-suite-demos-v$Version-$timestamp.zip"
$zipPath = Join-Path $OutputDir $zipName

# Remove old package if exists
if (Test-Path $zipPath) {
    Remove-Item $zipPath -Force
    Write-Host "[1/6] Alte Package entfernt" -ForegroundColor Yellow
}

Write-Host "[2/6] Erstelle temporaeres Verzeichnis..." -ForegroundColor Gray

# Create temporary staging directory
$tempDir = Join-Path $env:TEMP "cts-demo-deploy-$timestamp"
New-Item -ItemType Directory -Path $tempDir -Force | Out-Null

# Create subdirectories
$themeTarget = Join-Path $tempDir "theme\cts-demo-theme"
$pagesTarget = Join-Path $tempDir "pages"
New-Item -ItemType Directory -Path $themeTarget -Recurse -Force | Out-Null
New-Item -ItemType Directory -Path $pagesTarget -Recurse -Force | Out-Null

Write-Host "[3/6] Kopiere Theme-Dateien..." -ForegroundColor Gray
Copy-Item -Path "theme\cts-demo-theme\*" -Destination $themeTarget -Recurse -Force

Write-Host "[4/6] Kopiere Seiten..." -ForegroundColor Gray
Copy-Item -Path "pages\*" -Destination $pagesTarget -Recurse -Force

Write-Host "[5/6] Erstelle Dokumentation..." -ForegroundColor Gray
Copy-Item -Path "README.md" -Destination $tempDir -Force -ErrorAction SilentlyContinue
Copy-Item -Path "CONTRIBUTING.md" -Destination $tempDir -Force -ErrorAction SilentlyContinue

# Create INSTALL.txt with deployment instructions
$versionDisplay = $Version
$dateDisplay = Get-Date -Format "dd.MM.yyyy HH:mm"

$installText = @'
ChurchTools Suite Demo Site - Installation
==========================================

INSTALLATION VIA SCP + SSH:
-------------------------------

Schritt 1: ZIP auf Server hochladen
   scp -P 22073 ZIPNAME aschaffesshadmin@ftp.feg.de:/tmp/

Schritt 2: Per SSH verbinden
   ssh -p 22073 aschaffesshadmin@ftp.feg.de

Schritt 3: ZIP entpacken
   cd /tmp
   unzip ZIPNAME
   cd churchtools-suite-demos-*

Schritt 4: Theme deployen
   cp -r theme/cts-demo-theme /var/www/clients/client436/web2975/web/wp-content/themes/
   echo "Theme deployed!"

Schritt 5: Pages via WordPress Admin aktualisieren
   - Login: https://plugin.feg-aschaffenburg.de/wp-admin
   - Seiten -> Alle Seiten
   - Demo-Seiten einzeln bearbeiten und Content aktualisieren

Schritt 6: Aufraeumen
   cd /tmp
   rm -rf churchtools-suite-demos-* ZIPNAME

FERTIG!
-------
Besuchen Sie: https://plugin.feg-aschaffenburg.de/live-demos/
'@

# Replace placeholders
$installText = $installText -replace 'ZIPNAME', $zipName
$installText = "Version: $versionDisplay`nErstellt: $dateDisplay`n`n" + $installText

$installText | Out-File -Encoding UTF8 -FilePath (Join-Path $tempDir "INSTALL.txt")

Write-Host "[6/6] Erstelle ZIP-Archiv..." -ForegroundColor Gray
Compress-Archive -Path "$tempDir\*" -DestinationPath $zipPath -Force

# Cleanup
Remove-Item -Path $tempDir -Recurse -Force

# Success
$zipSize = (Get-Item $zipPath).Length / 1KB

Write-Host ""
Write-Host "===========================================" -ForegroundColor Green
Write-Host "ERFOLGREICH ERSTELLT" -ForegroundColor Green
Write-Host "===========================================" -ForegroundColor Green
Write-Host ""
Write-Host "Paket:   $zipName" -ForegroundColor White
Write-Host "Pfad:    $zipPath" -ForegroundColor White
Write-Host "Groesse: $([math]::Round($zipSize, 2)) KB" -ForegroundColor White
Write-Host ""
Write-Host "===========================================" -ForegroundColor Cyan
Write-Host "UPLOAD-BEFEHL:" -ForegroundColor Cyan
Write-Host "===========================================" -ForegroundColor Cyan
Write-Host "scp -P 22073 `"$zipPath`" aschaffesshadmin@ftp.feg.de:/tmp/" -ForegroundColor Yellow
Write-Host ""
Write-Host "Siehe INSTALL.txt im Paket fuer Deployment-Anleitung" -ForegroundColor Gray
Write-Host ""
