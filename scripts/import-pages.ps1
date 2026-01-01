# Import Demo Pages from Markdown
# Converts Markdown files to WordPress pages with proper templates and meta fields
#
# Usage: .\import-pages.ps1 -WordPressPath "C:\xampp\htdocs\wordpress" -ContentType "demos"

param(
    [Parameter(Mandatory=$true)]
    [string]$WordPressPath,
    
    [Parameter(Mandatory=$false)]
    [ValidateSet("demos", "docs", "all")]
    [string]$ContentType = "all"
)

# Color output functions
function Write-Success { param($Message) Write-Host "✓ $Message" -ForegroundColor Green }
function Write-Error { param($Message) Write-Host "✗ $Message" -ForegroundColor Red }
function Write-Info { param($Message) Write-Host "ℹ $Message" -ForegroundColor Cyan }
function Write-Warning { param($Message) Write-Host "⚠ $Message" -ForegroundColor Yellow }

# Validate WordPress installation
if (-not (Test-Path "$WordPressPath\wp-config.php")) {
    Write-Error "WordPress installation nicht gefunden: $WordPressPath"
    exit 1
}

Write-Info "Importiere Pages von Markdown..."

# Get script directory
$ScriptDir = Split-Path -Parent $MyInvocation.MyCommand.Path
$RepoRoot = Split-Path -Parent $ScriptDir

# Check WP-CLI availability
$HasWpCli = $false
try {
    $wpCliTest = wp --version 2>&1
    if ($LASTEXITCODE -eq 0) {
        $HasWpCli = $true
        Write-Success "WP-CLI gefunden"
    }
} catch {
    Write-Warning "WP-CLI nicht verfügbar - Manueller Import erforderlich"
}

# Function to parse YAML frontmatter
function Parse-Frontmatter {
    param([string]$FilePath)
    
    $content = Get-Content $FilePath -Raw
    
    if ($content -match '(?s)^---\s*\n(.*?)\n---\s*\n(.*)$') {
        $frontmatter = $matches[1]
        $body = $matches[2]
        
        $meta = @{}
        
        foreach ($line in ($frontmatter -split '\n')) {
            if ($line -match '^(\w+):\s*(.+)$') {
                $key = $matches[1]
                $value = $matches[2].Trim("'`"")
                $meta[$key] = $value
            }
        }
        
        return @{
            Meta = $meta
            Content = $body.Trim()
        }
    }
    
    return $null
}

# Function to create WordPress page
function Create-WordPressPage {
    param(
        [hashtable]$Data,
        [string]$Template,
        [string]$ParentSlug = ""
    )
    
    if (-not $HasWpCli) {
        Write-Warning "WP-CLI nicht verfügbar - Überspringe Page-Erstellung"
        return
    }
    
    $title = $Data.Meta['title']
    $content = $Data.Content
    $excerpt = $Data.Meta['excerpt']
    
    # Create page via WP-CLI
    Push-Location $WordPressPath
    
    try {
        # Check if page exists
        $existingPage = wp post list --post_type=page --title="$title" --format=ids --quiet 2>&1
        
        if ($existingPage -and $existingPage -match '^\d+$') {
            # Update existing page
            wp post update $existingPage --post_content="$content" --post_excerpt="$excerpt" --quiet
            Write-Success "Page aktualisiert: $title (ID: $existingPage)"
            $pageId = $existingPage
        } else {
            # Create new page
            $args = @(
                "post", "create",
                "--post_type=page",
                "--post_title=$title",
                "--post_content=$content",
                "--post_status=publish",
                "--quiet",
                "--porcelain"
            )
            
            if ($excerpt) {
                $args += "--post_excerpt=$excerpt"
            }
            
            if ($ParentSlug) {
                $parentId = wp post list --post_type=page --name="$ParentSlug" --format=ids --quiet 2>&1
                if ($parentId -match '^\d+$') {
                    $args += "--post_parent=$parentId"
                }
            }
            
            $pageId = wp @args 2>&1
            
            if ($pageId -match '^\d+$') {
                Write-Success "Page erstellt: $title (ID: $pageId)"
            } else {
                Write-Error "Fehler beim Erstellen: $title"
                return
            }
        }
        
        # Set page template
        if ($Template -and $pageId -match '^\d+$') {
            wp post meta update $pageId _wp_page_template "page-templates/$Template.php" --quiet
        }
        
        # Set custom meta fields (for demo pages)
        if ($Data.Meta['shortcode']) {
            wp post meta update $pageId demo_shortcode $Data.Meta['shortcode'] --quiet
        }
        if ($Data.Meta['category']) {
            wp post meta update $pageId demo_category $Data.Meta['category'] --quiet
        }
        if ($Data.Meta['difficulty']) {
            wp post meta update $pageId demo_difficulty $Data.Meta['difficulty'] --quiet
        }
        
    } catch {
        Write-Error "WP-CLI Fehler: $_"
    } finally {
        Pop-Location
    }
}

# Import demo pages
if ($ContentType -eq "demos" -or $ContentType -eq "all") {
    Write-Info "`nImportiere Demo-Seiten..."
    
    $demosDir = Join-Path $RepoRoot "pages\demos"
    
    if (Test-Path $demosDir) {
        $demoFiles = Get-ChildItem $demosDir -Filter "*.md"
        
        foreach ($file in $demoFiles) {
            Write-Info "Verarbeite: $($file.Name)"
            
            $data = Parse-Frontmatter -FilePath $file.FullName
            
            if ($data) {
                Create-WordPressPage -Data $data -Template "demo-page" -ParentSlug "demos"
            } else {
                Write-Warning "Kein gültiges Frontmatter: $($file.Name)"
            }
        }
        
        Write-Success "$($demoFiles.Count) Demo-Seiten verarbeitet"
    } else {
        Write-Warning "Demos-Verzeichnis nicht gefunden: $demosDir"
    }
}

# Import documentation pages
if ($ContentType -eq "docs" -or $ContentType -eq "all") {
    Write-Info "`nImportiere Dokumentations-Seiten..."
    
    $docsDir = Join-Path $RepoRoot "pages\docs"
    
    if (Test-Path $docsDir) {
        $docFiles = Get-ChildItem $docsDir -Filter "*.md"
        
        foreach ($file in $docFiles) {
            Write-Info "Verarbeite: $($file.Name)"
            
            $data = Parse-Frontmatter -FilePath $file.FullName
            
            if ($data) {
                Create-WordPressPage -Data $data -Template "documentation" -ParentSlug "documentation"
            } else {
                Write-Warning "Kein gültiges Frontmatter: $($file.Name)"
            }
        }
        
        Write-Success "$($docFiles.Count) Dokumentations-Seiten verarbeitet"
    } else {
        Write-Warning "Docs-Verzeichnis nicht gefunden: $docsDir"
    }
}

Write-Success "`n✅ Import abgeschlossen!"

# Summary
Write-Host "`n" -NoNewline
Write-Host "════════════════════════════════════════" -ForegroundColor Cyan
Write-Host "  Nächste Schritte" -ForegroundColor White
Write-Host "════════════════════════════════════════" -ForegroundColor Cyan
Write-Host ""
Write-Host "1. Öffne WordPress Admin" -ForegroundColor Yellow
Write-Host "2. Gehe zu Seiten → Alle Seiten" -ForegroundColor Yellow
Write-Host "3. Prüfe importierte Pages" -ForegroundColor Yellow
Write-Host "4. Passe Inhalte bei Bedarf an" -ForegroundColor Yellow
Write-Host ""
Write-Host "════════════════════════════════════════" -ForegroundColor Cyan
