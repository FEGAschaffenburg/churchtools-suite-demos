#!/usr/bin/env pwsh
<#
.SYNOPSIS
    Deploy Demo Plugin to Server via SSH
    
.DESCRIPTION
    Uploads the churchtools-suite-demo plugin to the production server via SSH/SCP.
    Uses existing ZIP file from C:\privat\ directory.
    
.EXAMPLE
    .\deploy-ssh.ps1
    
.NOTES
    Requires:
    - SSH key at ~/.ssh/id_feg_rsa
    - Demo plugin ZIP in C:\privat\
#>

# Configuration
$SSH_HOST = "web73.feg.de"
$SSH_PORT = "22073"
$SSH_KEY = "$HOME/.ssh/id_feg_rsa"
$SSH_USER = "aschaffesshadmin"
$WP_PATH = "/var/www/clients/client436/web2975/web"
$PLUGIN_NAME = "churchtools-suite-demo"
$ZIP_DIR = "C:\privat"

# Colors
$Blue = [System.ConsoleColor]::Blue
$Green = [System.ConsoleColor]::Green
$Red = [System.ConsoleColor]::Red
$Yellow = [System.ConsoleColor]::Yellow

function Write-ColorOutput {
    param(
        [string]$Message,
        [System.ConsoleColor]$Color = [System.ConsoleColor]::White
    )
    Write-Host $Message -ForegroundColor $Color
}

Write-ColorOutput "`n🚀 ChurchTools Suite Demo Plugin - SSH Deployment" $Blue
Write-ColorOutput ("=" * 60) $Blue

# Find latest demo plugin ZIP
Write-ColorOutput "`n📦 Searching for plugin ZIP..." $Yellow
$zipFiles = Get-ChildItem -Path $ZIP_DIR -Filter "churchtools-suite-demo-*.zip" | Sort-Object LastWriteTime -Descending

if ($zipFiles.Count -eq 0) {
    Write-ColorOutput "`n❌ No demo plugin ZIP found in $ZIP_DIR" $Red
    Write-ColorOutput "   Run .\deploy-demo-plugin.ps1 first to create ZIP" $Yellow
    exit 1
}

$zipFile = $zipFiles[0]
$zipPath = $zipFile.FullName
Write-ColorOutput "   Found: $($zipFile.Name)" $Green
Write-ColorOutput "   Size: $([Math]::Round($zipFile.Length / 1MB, 2)) MB" $Green

# Check SSH key
if (-not (Test-Path $SSH_KEY)) {
    Write-ColorOutput "`n❌ SSH key not found: $SSH_KEY" $Red
    Write-ColorOutput "   Run .\generate-ssh-key.ps1 first" $Yellow
    exit 1
}

# Confirm deployment
Write-ColorOutput "`n⚠️  This will replace the demo plugin on production server!" $Yellow
Write-ColorOutput "   Server: $SSH_HOST" $Yellow
Write-ColorOutput "   Target: $WP_PATH/wp-content/plugins/$PLUGIN_NAME" $Yellow
$confirm = Read-Host "`nContinue? (y/N)"

if ($confirm -ne 'y' -and $confirm -ne 'Y') {
    Write-ColorOutput "`n❌ Deployment cancelled" $Red
    exit 0
}

Write-ColorOutput "`n📤 Uploading ZIP to server..." $Yellow

# Upload ZIP to /tmp/
scp -i $SSH_KEY -P $SSH_PORT $zipPath "${SSH_USER}@${SSH_HOST}:/tmp/"

if ($LASTEXITCODE -ne 0) {
    Write-ColorOutput "`n❌ Upload failed" $Red
    exit 1
}

Write-ColorOutput "`n📂 Extracting plugin on server..." $Yellow

# Extract and replace plugin (ZIP is now flat, so extract directly)
$zipFileName = $zipFile.Name
$remoteCommands = "cd /tmp && rm -rf $PLUGIN_NAME && mkdir -p $PLUGIN_NAME && unzip -o -q $zipFileName -d $PLUGIN_NAME && rm -rf $WP_PATH/wp-content/plugins/$PLUGIN_NAME && mv $PLUGIN_NAME $WP_PATH/wp-content/plugins/ && rm $zipFileName && chown -R web2975:client436 $WP_PATH/wp-content/plugins/$PLUGIN_NAME && chmod -R 755 $WP_PATH/wp-content/plugins/$PLUGIN_NAME && echo 'Plugin deployed successfully'"

ssh -i $SSH_KEY -p $SSH_PORT "${SSH_USER}@${SSH_HOST}" $remoteCommands

if ($LASTEXITCODE -eq 0) {
    Write-ColorOutput "`n✅ Demo Plugin deployed successfully!" $Green
    Write-ColorOutput "`n📋 Post-deployment steps:" $Blue
    Write-ColorOutput "   1. Go to: https://plugin.feg-aschaffenburg.de/wp-admin" $Blue
    Write-ColorOutput "   2. Navigate to: Plugins → ChurchTools Suite Demo" $Blue
    Write-ColorOutput "   3. Deactivate → Activate (if needed)" $Blue
    Write-ColorOutput "   4. Verify version: 1.0.5.0" $Blue
} else {
    Write-ColorOutput "`n❌ Deployment failed" $Red
    exit 1
}

Write-ColorOutput "`n🌐 Demo site: https://plugin.feg-aschaffenburg.de`n" $Blue

