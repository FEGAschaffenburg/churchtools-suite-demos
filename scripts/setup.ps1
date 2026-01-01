<#
.SYNOPSIS
    ChurchTools Suite Demo Site - Automatisches Setup
    
.DESCRIPTION
    Dieses Script richtet die komplette Demo-Site ein:
    - WordPress-Installation prüfen
    - Theme kopieren und aktivieren
    - Seiten erstellen
    - Menüs konfigurieren
    - ChurchTools Suite Plugin prüfen
    
.PARAMETER WordPressPath
    Pfad zur WordPress-Installation (z.B. C:\inetpub\wwwroot\plugin.aschaffenburg.feg.de)
    
.PARAMETER Domain
    Domain der Website (z.B. plugin.aschaffenburg.feg.de)
    
.PARAMETER CreatePages
    Sollen Demo-Seiten automatisch erstellt werden? (Standard: $true)

.EXAMPLE
    .\setup.ps1 -WordPressPath "C:\inetpub\wwwroot\plugin" -Domain "plugin.aschaffenburg.feg.de"
    
.EXAMPLE
    .\setup.ps1 -WordPressPath "C:\inetpub\wwwroot\plugin" -CreatePages $false
#>

param(
    [Parameter(Mandatory=$true)]
    [string]$WordPressPath,
    
    [Parameter(Mandatory=$false)]
    [string]$Domain = "plugin.aschaffenburg.feg.de",
    
    [Parameter(Mandatory=$false)]
    [bool]$CreatePages = $true
)

Write-Host "========================================" -ForegroundColor Cyan
Write-Host "ChurchTools Suite Demo Site - Setup" -ForegroundColor Cyan
Write-Host "========================================" -ForegroundColor Cyan
Write-Host ""

# Variablen
$ScriptRoot = Split-Path -Parent $PSCommandPath
$RepoRoot = Split-Path -Parent $ScriptRoot
$ThemeSource = Join-Path $RepoRoot "theme\cts-demo-theme"
$PagesSource = Join-Path $RepoRoot "pages"
$ConfigSource = Join-Path $RepoRoot "config"

# WordPress-Pfade
$ThemesPath = Join-Path $WordPressPath "wp-content\themes"
$PluginsPath = Join-Path $WordPressPath "wp-content\plugins"
$ThemeTarget = Join-Path $ThemesPath "cts-demo-theme"

Write-Host "Konfiguration:" -ForegroundColor Yellow
Write-Host "  WordPress-Pfad: $WordPressPath" -ForegroundColor Gray
Write-Host "  Domain: $Domain" -ForegroundColor Gray
Write-Host "  Seiten erstellen: $CreatePages" -ForegroundColor Gray
Write-Host ""

# ========================================
# SCHRITT 1: WordPress-Installation prüfen
# ========================================
Write-Host "[1/6] WordPress-Installation prüfen..." -ForegroundColor Yellow

if (!(Test-Path $WordPressPath)) {
    Write-Host "  ✗ FEHLER: WordPress-Pfad nicht gefunden: $WordPressPath" -ForegroundColor Red
    Write-Host "  Bitte gültigen Pfad angeben." -ForegroundColor Red
    exit 1
}

$wpConfigPath = Join-Path $WordPressPath "wp-config.php"
if (!(Test-Path $wpConfigPath)) {
    Write-Host "  ✗ FEHLER: wp-config.php nicht gefunden in $WordPressPath" -ForegroundColor Red
    Write-Host "  Ist WordPress installiert?" -ForegroundColor Red
    exit 1
}

Write-Host "  ✓ WordPress-Installation gefunden" -ForegroundColor Green

# ========================================
# SCHRITT 2: ChurchTools Suite Plugin prüfen
# ========================================
Write-Host ""
Write-Host "[2/6] ChurchTools Suite Plugin prüfen..." -ForegroundColor Yellow

$pluginPath = Join-Path $PluginsPath "churchtools-suite"
if (!(Test-Path $pluginPath)) {
    Write-Host "  ⚠ ChurchTools Suite Plugin nicht installiert" -ForegroundColor Yellow
    Write-Host "  Bitte Plugin manuell hochladen und aktivieren:" -ForegroundColor Yellow
    Write-Host "  https://github.com/FEGAschaffenburg/churchtools-suite/releases" -ForegroundColor Cyan
} else {
    Write-Host "  ✓ ChurchTools Suite Plugin gefunden" -ForegroundColor Green
}

# ========================================
# SCHRITT 3: Theme kopieren
# ========================================
Write-Host ""
Write-Host "[3/6] Theme kopieren..." -ForegroundColor Yellow

if (!(Test-Path $ThemeSource)) {
    Write-Host "  ✗ FEHLER: Theme-Quellordner nicht gefunden: $ThemeSource" -ForegroundColor Red
    exit 1
}

# Backup erstellen falls Theme schon existiert
if (Test-Path $ThemeTarget) {
    $backupPath = "$ThemeTarget-backup-$(Get-Date -Format 'yyyyMMdd-HHmmss')"
    Write-Host "  Erstelle Backup: $backupPath" -ForegroundColor Gray
    Move-Item -Path $ThemeTarget -Destination $backupPath -Force
}

# Theme kopieren
Write-Host "  Kopiere Theme nach: $ThemeTarget" -ForegroundColor Gray
Copy-Item -Path $ThemeSource -Destination $ThemeTarget -Recurse -Force

Write-Host "  ✓ Theme kopiert" -ForegroundColor Green

# ========================================
# SCHRITT 4: Theme aktivieren (WP-CLI)
# ========================================
Write-Host ""
Write-Host "[4/6] Theme aktivieren..." -ForegroundColor Yellow

# WP-CLI prüfen
$wpCliAvailable = Get-Command wp -ErrorAction SilentlyContinue
if ($wpCliAvailable) {
    Push-Location $WordPressPath
    wp theme activate cts-demo-theme 2>$null
    Pop-Location
    
    if ($LASTEXITCODE -eq 0) {
        Write-Host "  ✓ Theme aktiviert via WP-CLI" -ForegroundColor Green
    } else {
        Write-Host "  ⚠ WP-CLI Fehler - bitte Theme manuell aktivieren" -ForegroundColor Yellow
    }
} else {
    Write-Host "  ⚠ WP-CLI nicht verfügbar - bitte Theme manuell aktivieren im WordPress-Backend" -ForegroundColor Yellow
    Write-Host "  Dashboard → Design → Themes → 'ChurchTools Suite Demo' aktivieren" -ForegroundColor Cyan
}

# ========================================
# SCHRITT 5: Seiten erstellen (optional)
# ========================================
Write-Host ""
Write-Host "[5/6] Demo-Seiten erstellen..." -ForegroundColor Yellow

if (!$CreatePages) {
    Write-Host "  ⊘ Übersprungen (CreatePages=$false)" -ForegroundColor Gray
} else {
    if ($wpCliAvailable) {
        Push-Location $WordPressPath
        
        # Hauptseiten erstellen
        $pages = @(
            @{title="Home"; slug="home"; content="Willkommen auf der ChurchTools Suite Demo-Site!"},
            @{title="Demos"; slug="demos"; content="Live-Demos aller Plugin-Features"},
            @{title="Calendar Demos"; slug="calendar-demos"; parent="demos"; content="[cts_calendar view='monthly-modern']"},
            @{title="List Demos"; slug="list-demos"; parent="demos"; content="[cts_list view='classic']"},
            @{title="Grid Demos"; slug="grid-demos"; parent="demos"; content="[cts_grid view='simple']"},
            @{title="Dokumentation"; slug="documentation"; content="Plugin-Dokumentation"},
            @{title="Download"; slug="download"; content="ChurchTools Suite herunterladen"}
        )
        
        $createdCount = 0
        foreach ($page in $pages) {
            $existing = wp post list --post_type=page --name=$($page.slug) --format=count 2>$null
            
            if ($existing -eq "0") {
                if ($page.parent) {
                    $parentId = wp post list --post_type=page --name=$($page.parent) --field=ID --format=csv 2>$null
                    wp post create --post_type=page --post_title="$($page.title)" --post_name="$($page.slug)" --post_content="$($page.content)" --post_status=publish --post_parent=$parentId 2>$null
                } else {
                    wp post create --post_type=page --post_title="$($page.title)" --post_name="$($page.slug)" --post_content="$($page.content)" --post_status=publish 2>$null
                }
                $createdCount++
            }
        }
        
        Pop-Location
        Write-Host "  ✓ $createdCount Seiten erstellt" -ForegroundColor Green
    } else {
        Write-Host "  ⚠ WP-CLI nicht verfügbar - Seiten müssen manuell erstellt werden" -ForegroundColor Yellow
    }
}

# ========================================
# SCHRITT 6: Abschluss
# ========================================
Write-Host ""
Write-Host "[6/6] Abschluss..." -ForegroundColor Yellow
Write-Host "  ✓ Setup abgeschlossen!" -ForegroundColor Green
Write-Host ""

# Zusammenfassung
Write-Host "========================================" -ForegroundColor Cyan
Write-Host "NÄCHSTE SCHRITTE:" -ForegroundColor Cyan
Write-Host "========================================" -ForegroundColor Cyan
Write-Host ""

Write-Host "1. WordPress-Backend öffnen:" -ForegroundColor Yellow
Write-Host "   https://$Domain/wp-admin" -ForegroundColor Cyan
Write-Host ""

if (!(Test-Path $pluginPath)) {
    Write-Host "2. ChurchTools Suite Plugin installieren:" -ForegroundColor Yellow
    Write-Host "   Plugins → Installieren → Upload → churchtools-suite-0.9.4.9.zip" -ForegroundColor Cyan
    Write-Host "   Download: https://github.com/FEGAschaffenburg/churchtools-suite/releases" -ForegroundColor Cyan
    Write-Host ""
}

Write-Host "3. ChurchTools API konfigurieren:" -ForegroundColor Yellow
Write-Host "   ChurchTools → Einstellungen → API-Zugangsdaten eingeben" -ForegroundColor Cyan
Write-Host ""

Write-Host "4. Kalender synchronisieren:" -ForegroundColor Yellow
Write-Host "   ChurchTools → Daten → Kalender synchronisieren" -ForegroundColor Cyan
Write-Host ""

Write-Host "5. Demo-Site aufrufen:" -ForegroundColor Yellow
Write-Host "   https://$Domain" -ForegroundColor Cyan
Write-Host ""

Write-Host "========================================" -ForegroundColor Cyan
Write-Host "Viel Erfolg! 🎉" -ForegroundColor Green
Write-Host "========================================" -ForegroundColor Cyan
