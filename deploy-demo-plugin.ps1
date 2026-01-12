#!/usr/bin/env powershell
<#
.SYNOPSIS
ChurchTools Suite Demo Plugin - Deployment Script v1.0.3.1

.DESCRIPTION
Erstellt ein ZIP-Package des Demo Plugins zur Verwendung auf dem Server.

.EXAMPLE
.\deploy-demo-plugin.ps1

#>

param(
    [string]$Version = "1.0.3.1"
)

Write-Host "=== ChurchTools Suite Demo Plugin Deployment ===" -ForegroundColor Cyan
Write-Host "Version: $Version`n"

# Get script directory
$scriptDir = Split-Path -Parent $MyInvocation.MyCommand.Path
$pluginDir = $scriptDir
$outputDir = "C:\privat"

# Create output directory if it doesn't exist
if (!(Test-Path $outputDir)) {
    New-Item -ItemType Directory -Path $outputDir -Force | Out-Null
}

# Define ZIP path
$zipPath = "$outputDir\churchtools-suite-demo-$Version.zip"

# Check if ZIP already exists
if (Test-Path $zipPath) {
    Write-Host "⚠️  ZIP already exists. Archiving old version..." -ForegroundColor Yellow
    $timestamp = Get-Date -Format "yyyyMMdd-HHmmss"
    $archiveDir = "$outputDir\archiv"
    if (!(Test-Path $archiveDir)) {
        New-Item -ItemType Directory -Path $archiveDir -Force | Out-Null
    }
    Move-Item $zipPath "$archiveDir\churchtools-suite-demo-$Version-$timestamp.zip" -Force
    Write-Host "✅  Archived to: $archiveDir\churchtools-suite-demo-$Version-$timestamp.zip`n"
}

# Files to include
$filesToInclude = @(
    "admin",
    "assets",
    "includes",
    "languages",
    "templates",
    "churchtools-suite-demo.php",
    "README.md"
)

Write-Host "📦 Creating ZIP package..."
Write-Host "Output: $zipPath`n"

# Create temporary working directory
$tempDir = "$env:TEMP\cts-demo-build-$(Get-Random)"
New-Item -ItemType Directory -Path $tempDir -Force | Out-Null

try {
    # Copy files to temp directory
    Write-Host "📋 Copying files..."
    foreach ($file in $filesToInclude) {
        $source = Join-Path $pluginDir $file
        $dest = Join-Path $tempDir "churchtools-suite-demo" $file
        
        if (Test-Path $source) {
            if ((Get-Item $source).PSIsContainer) {
                Copy-Item -Path $source -Destination $dest -Recurse -Force | Out-Null
                Write-Host "  ✓ $file (directory)"
            } else {
                $destDir = Split-Path -Parent $dest
                New-Item -ItemType Directory -Path $destDir -Force | Out-Null
                Copy-Item -Path $source -Destination $dest -Force | Out-Null
                Write-Host "  ✓ $file"
            }
        } else {
            Write-Host "  ⚠️  Skipped: $file (not found)"
        }
    }
    
    Write-Host "`n📦 Creating ZIP archive..."
    
    # Create ZIP using .NET
    Add-Type -AssemblyName System.IO.Compression.FileSystem
    [System.IO.Compression.ZipFile]::CreateFromDirectory($tempDir, $zipPath, [System.IO.Compression.CompressionLevel]::Optimal, $true)
    
    Write-Host "✅  ZIP created successfully!`n"
    
    # Validate ZIP
    Write-Host "🔍 Validating ZIP..."
    $zip = [System.IO.Compression.ZipFile]::OpenRead($zipPath)
    $entryCount = $zip.Entries.Count
    $zip.Dispose()
    
    $fileSize = (Get-Item $zipPath).Length / 1MB
    Write-Host "   File size: $([Math]::Round($fileSize, 2)) MB"
    Write-Host "   Entries: $entryCount"
    
    # Show first 5 entries
    Write-Host "`n📄 First 5 entries:"
    $zip = [System.IO.Compression.ZipFile]::OpenRead($zipPath)
    $zip.Entries | Select-Object -First 5 | ForEach-Object {
        Write-Host "   - $($_.FullName)"
    }
    $zip.Dispose()
    
    Write-Host "`n✨ SUCCESS! Ready for deployment`n"
    Write-Host "📤 Next steps:"
    Write-Host "   1. Copy: $zipPath"
    Write-Host "   2. Upload to server via FTP/SCP"
    Write-Host "   3. Extract to /wp-content/plugins/"
    Write-Host "   4. Activate in WordPress Admin"
    
} finally {
    # Cleanup
    Write-Host "`n🧹 Cleaning up temporary files..."
    Remove-Item -Path $tempDir -Recurse -Force
    Write-Host "✅  Done"
}
