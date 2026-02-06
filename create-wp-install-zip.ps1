# WordPress Plugin ZIP Ersteller
# Erstellt installationsfertiges ZIP für churchtools-suite-demo

param(
    [string]$OutputDir = "C:\temp"
)

$PluginDir = $PSScriptRoot
$PluginName = "churchtools-suite-demo"
$Version = "1.0.5.16"
$OutputZip = Join-Path $OutputDir "$PluginName-$Version-wp-install.zip"

Write-Host "========================================" -ForegroundColor Cyan
Write-Host "  WordPress Plugin ZIP Creator" -ForegroundColor Cyan
Write-Host "========================================" -ForegroundColor Cyan
Write-Host ""
Write-Host "Plugin: $PluginName" -ForegroundColor Yellow
Write-Host "Version: $Version" -ForegroundColor Yellow
Write-Host "Output: $OutputZip" -ForegroundColor Yellow
Write-Host ""

# Erstelle Temp-Verzeichnis
$TempDir = Join-Path $env:TEMP "wp-plugin-build-$(Get-Date -Format 'yyyyMMddHHmmss')"
$PluginTempDir = Join-Path $TempDir $PluginName

Write-Host "[1/5] Erstelle Temp-Verzeichnis..." -ForegroundColor Cyan
New-Item -ItemType Directory -Path $PluginTempDir -Force | Out-Null
Write-Host "  OK: $PluginTempDir" -ForegroundColor Green

# Dateien kopieren (ohne .git, node_modules, etc.)
Write-Host ""
Write-Host "[2/5] Kopiere Plugin-Dateien..." -ForegroundColor Cyan

$ExcludeDirs = @('.git', '.github', 'node_modules', '.vscode', '.idea')
$ExcludeFiles = @('*.zip', '*.tar', '*.gz', '*.log', '.gitignore', '.gitattributes', 'desktop.ini', 'Thumbs.db', '*.md~', 'create-wp-install-zip.ps1')

# Robocopy mit Exclusions
$robocopyArgs = @(
    $PluginDir,
    $PluginTempDir,
    '/E',           # Alle Unterverzeichnisse
    '/R:0',         # Keine Wiederholungen
    '/W:0',         # Keine Wartezeit
    '/NFL',         # No file list
    '/NDL',         # No directory list
    '/NJH',         # No job header
    '/NJS',         # No job summary
    '/XD'           # Exclude directories
)
$robocopyArgs += $ExcludeDirs
$robocopyArgs += '/XF'  # Exclude files
$robocopyArgs += $ExcludeFiles

& robocopy @robocopyArgs | Out-Null
if ($LASTEXITCODE -gt 7) {
    Write-Host "  WARNUNG: Robocopy Exit Code: $LASTEXITCODE" -ForegroundColor Yellow
} else {
    Write-Host "  OK: Dateien kopiert" -ForegroundColor Green
}

# Zähle Dateien
$FileCount = (Get-ChildItem -Path $PluginTempDir -Recurse -File).Count
Write-Host "  Dateien: $FileCount" -ForegroundColor Gray

# Erstelle Output-Verzeichnis
Write-Host ""
Write-Host "[3/5] Erstelle Output-Verzeichnis..." -ForegroundColor Cyan
if (-not (Test-Path $OutputDir)) {
    New-Item -ItemType Directory -Path $OutputDir -Force | Out-Null
}
Write-Host "  OK: $OutputDir" -ForegroundColor Green

# Lösche altes ZIP falls vorhanden
if (Test-Path $OutputZip) {
    Write-Host ""
    Write-Host "[4/5] Lösche altes ZIP..." -ForegroundColor Cyan
    Remove-Item -Path $OutputZip -Force
    Write-Host "  OK: Altes ZIP gelöscht" -ForegroundColor Green
}

# Erstelle ZIP
Write-Host ""
Write-Host "[5/5] Erstelle ZIP-Archiv..." -ForegroundColor Cyan
try {
    Compress-Archive -Path $PluginTempDir -DestinationPath $OutputZip -Force
    $ZipSize = [math]::Round((Get-Item $OutputZip).Length / 1MB, 2)
    Write-Host "  OK: ZIP erstellt ($ZipSize MB)" -ForegroundColor Green
} catch {
    Write-Host "  FEHLER: $_" -ForegroundColor Red
    exit 1
}

# Cleanup
Write-Host ""
Write-Host "Cleanup..." -ForegroundColor Gray
Remove-Item -Path $TempDir -Recurse -Force

Write-Host ""
Write-Host "========================================" -ForegroundColor Green
Write-Host "  ZIP erfolgreich erstellt!" -ForegroundColor Green
Write-Host "========================================" -ForegroundColor Green
Write-Host ""
Write-Host "📦 ZIP-Datei:" -ForegroundColor Yellow
Write-Host "   $OutputZip" -ForegroundColor White
Write-Host ""
Write-Host "Installation:" -ForegroundColor Yellow
Write-Host "   1. WordPress Admin → Plugins → Installieren" -ForegroundColor White
Write-Host "   2. 'Plugin hochladen' → ZIP auswählen" -ForegroundColor White
Write-Host "   3. 'Jetzt installieren' → 'Plugin aktivieren'" -ForegroundColor White
Write-Host ""
