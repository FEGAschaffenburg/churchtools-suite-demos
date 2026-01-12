<?php
/**
 * Demo Plugin Installation Validator
 * 
 * Datei: /wp-content/plugins/churchtools-suite-demo/validate-installation.php
 * 
 * So verwenden:
 * 1. Nach Upload & Aktivierung des Plugins
 * 2. Im Browser aufrufen: https://example.com/wp-content/plugins/churchtools-suite-demo/validate-installation.php
 * 3. Alle Checks müssen ✅ sein
 * 
 * Prüft:
 * - WordPress ist geladen
 * - Demo Plugin ist aktiv
 * - Datenbankverbindung
 * - Tabelle wp_cts_demo_users existiert
 * - Alle erforderlichen Spalten
 * - Hauptplugin v1.0.3.1+ installiert
 * 
 * @package ChurchTools_Suite_Demo
 * @since   1.0.3.1
 */

// === Load WordPress ===
if (!function_exists('is_plugin_active')) {
    require_once dirname(dirname(dirname(__FILE__))) . '/wp-load.php';
}

// ===== Styling =====
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>ChurchTools Demo Plugin - Installation Validator</title>
    <style>
        body {
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
            max-width: 900px;
            margin: 40px auto;
            padding: 20px;
            background: #f5f5f5;
            color: #333;
        }
        .container {
            background: white;
            border-radius: 8px;
            padding: 30px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        h1 {
            color: #2563eb;
            border-bottom: 3px solid #2563eb;
            padding-bottom: 10px;
        }
        h2 {
            color: #1e293b;
            font-size: 16px;
            margin-top: 20px;
            margin-bottom: 10px;
        }
        .check {
            display: flex;
            align-items: center;
            padding: 10px;
            margin: 8px 0;
            border-radius: 4px;
            background: #f9fafb;
            border-left: 4px solid #ccc;
        }
        .check.success {
            background: #dcfce7;
            border-left-color: #22c55e;
        }
        .check.warning {
            background: #fef3c7;
            border-left-color: #f59e0b;
        }
        .check.error {
            background: #fee2e2;
            border-left-color: #ef4444;
        }
        .check-icon {
            font-size: 20px;
            margin-right: 12px;
            min-width: 24px;
        }
        .check-text {
            flex: 1;
        }
        .check-value {
            font-family: monospace;
            background: white;
            padding: 4px 8px;
            border-radius: 3px;
            font-size: 12px;
            color: #666;
            margin-top: 4px;
        }
        .summary {
            margin-top: 30px;
            padding: 20px;
            border-radius: 6px;
            background: #f0f9ff;
            border: 2px solid #0284c7;
        }
        .summary.success {
            background: #dcfce7;
            border-color: #22c55e;
        }
        .summary.warning {
            background: #fef3c7;
            border-color: #f59e0b;
        }
        .summary.error {
            background: #fee2e2;
            border-color: #ef4444;
        }
        .summary h3 {
            margin: 0 0 10px 0;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
            font-size: 13px;
        }
        table th, table td {
            padding: 8px;
            text-align: left;
            border: 1px solid #e5e7eb;
        }
        table th {
            background: #f3f4f6;
            font-weight: 600;
        }
        .code {
            background: #1f2937;
            color: #10b981;
            padding: 12px;
            border-radius: 4px;
            font-family: monospace;
            font-size: 12px;
            overflow-x: auto;
            margin-top: 10px;
        }
        .footer {
            margin-top: 30px;
            padding-top: 20px;
            border-top: 1px solid #e5e7eb;
            font-size: 12px;
            color: #666;
        }
    </style>
</head>
<body>

<div class="container">
    <h1>✔️ ChurchTools Demo Plugin - Installation Validator</h1>
    <p>Validierung der Installationsintegrität (v1.0.3.1)</p>

<?php

$checks = [];
$success_count = 0;
$warning_count = 0;
$error_count = 0;

// ===== CHECK 1: WordPress geladen =====
if (function_exists('get_option')) {
    $checks[] = [
        'status' => 'success',
        'icon' => '✅',
        'title' => 'WordPress geladen',
        'value' => 'WordPress ' . get_bloginfo('version')
    ];
    $success_count++;
} else {
    $checks[] = [
        'status' => 'error',
        'icon' => '❌',
        'title' => 'WordPress geladen',
        'value' => 'FEHLER: WordPress nicht korrekt geladen'
    ];
    $error_count++;
    die('WordPress konnte nicht geladen werden. Bitte prüfen Sie die wp-load.php Datei.');
}

// ===== CHECK 2: Demo Plugin aktiv =====
$demo_active = is_plugin_active('churchtools-suite-demo/churchtools-suite-demo.php');
if ($demo_active) {
    $checks[] = [
        'status' => 'success',
        'icon' => '✅',
        'title' => 'Demo Plugin aktiv',
        'value' => 'churchtools-suite-demo/churchtools-suite-demo.php'
    ];
    $success_count++;
} else {
    $checks[] = [
        'status' => 'error',
        'icon' => '❌',
        'title' => 'Demo Plugin aktiv',
        'value' => 'FEHLER: Demo Plugin nicht aktiv'
    ];
    $error_count++;
}

// ===== CHECK 3: Demo Plugin Version =====
if (function_exists('get_file_data')) {
    $plugin_file = dirname(__FILE__) . '/churchtools-suite-demo.php';
    if (file_exists($plugin_file)) {
        $data = get_file_data($plugin_file, ['Version' => 'Version']);
        $version = $data['Version'] ?? 'Unbekannt';
        
        if (version_compare($version, '1.0.3.1', '>=')) {
            $checks[] = [
                'status' => 'success',
                'icon' => '✅',
                'title' => 'Demo Plugin Version',
                'value' => $version
            ];
            $success_count++;
        } else {
            $checks[] = [
                'status' => 'warning',
                'icon' => '⚠️',
                'title' => 'Demo Plugin Version',
                'value' => "$version (sollte ≥ 1.0.3.1 sein)"
            ];
            $warning_count++;
        }
    }
}

// ===== CHECK 4: Datenbankverbindung =====
global $wpdb;
if ($wpdb && $wpdb->dbh) {
    $checks[] = [
        'status' => 'success',
        'icon' => '✅',
        'title' => 'Datenbankverbindung',
        'value' => 'Verbunden mit: ' . $wpdb->dbname
    ];
    $success_count++;
} else {
    $checks[] = [
        'status' => 'error',
        'icon' => '❌',
        'title' => 'Datenbankverbindung',
        'value' => 'FEHLER: Kann nicht mit Datenbank verbinden'
    ];
    $error_count++;
}

// ===== CHECK 5: Tabelle wp_cts_demo_users existiert =====
$table_name = $wpdb->prefix . 'cts_demo_users';
$table_exists = $wpdb->get_var("SHOW TABLES LIKE '{$table_name}'") === $table_name;

if ($table_exists) {
    $checks[] = [
        'status' => 'success',
        'icon' => '✅',
        'title' => 'Tabelle existiert',
        'value' => $table_name
    ];
    $success_count++;
} else {
    $checks[] = [
        'status' => 'error',
        'icon' => '❌',
        'title' => 'Tabelle existiert',
        'value' => "FEHLER: Tabelle {$table_name} nicht gefunden"
    ];
    $error_count++;
}

// ===== CHECK 6: Tabellenspalten =====
if ($table_exists) {
    $required_columns = [
        'id' => 'BIGINT',
        'email' => 'VARCHAR',
        'name' => 'VARCHAR',
        'verification_token' => 'VARCHAR',
        'is_verified' => 'TINYINT',
        'verified_at' => 'DATETIME',         // v1.0.3.1 neu
        'wordpress_user_id' => 'BIGINT',
        'last_login_at' => 'DATETIME',       // v1.0.3.1 neu
        'expires_at' => 'DATETIME',
        'created_at' => 'DATETIME',
        'updated_at' => 'DATETIME',          // v1.0.3.1 neu
    ];
    
    $columns = $wpdb->get_results("SHOW COLUMNS FROM {$table_name}");
    $column_names = array_column($columns, 'Field');
    
    $missing = array_diff(array_keys($required_columns), $column_names);
    
    if (empty($missing)) {
        $checks[] = [
            'status' => 'success',
            'icon' => '✅',
            'title' => 'Erforderliche Spalten',
            'value' => 'Alle ' . count($required_columns) . ' Spalten vorhanden'
        ];
        $success_count++;
    } else {
        $checks[] = [
            'status' => 'error',
            'icon' => '❌',
            'title' => 'Erforderliche Spalten',
            'value' => 'Fehlende Spalten: ' . implode(', ', $missing)
        ];
        $error_count++;
    }
    
    // ===== CHECK 7: Tabellenzeilen =====
    $row_count = $wpdb->get_var("SELECT COUNT(*) FROM {$table_name}");
    $checks[] = [
        'status' => 'success',
        'icon' => 'ℹ️',
        'title' => 'Tabellenzeilen',
        'value' => $row_count . ' Einträge'
    ];
}

// ===== CHECK 8: Hauptplugin installiert =====
$main_plugin_active = is_plugin_active('churchtools-suite/churchtools-suite.php');
if ($main_plugin_active) {
    // Prüfe Version
    if (function_exists('get_file_data')) {
        $main_file = dirname(dirname(__FILE__)) . '/churchtools-suite/churchtools-suite.php';
        if (file_exists($main_file)) {
            $data = get_file_data($main_file, ['Version' => 'Version']);
            $main_version = $data['Version'] ?? 'Unbekannt';
            
            if (version_compare($main_version, '1.0.3.1', '>=')) {
                $checks[] = [
                    'status' => 'success',
                    'icon' => '✅',
                    'title' => 'Hauptplugin ChurchTools Suite',
                    'value' => $main_version
                ];
                $success_count++;
            } else {
                $checks[] = [
                    'status' => 'warning',
                    'icon' => '⚠️',
                    'title' => 'Hauptplugin ChurchTools Suite',
                    'value' => "$main_version (sollte ≥ 1.0.3.1 sein)"
                ];
                $warning_count++;
            }
        }
    }
} else {
    $checks[] = [
        'status' => 'warning',
        'icon' => '⚠️',
        'title' => 'Hauptplugin ChurchTools Suite',
        'value' => 'WARNUNG: Hauptplugin nicht aktiv (Demo braucht dieses)'
    ];
    $warning_count++;
}

// ===== RENDER CHECKS =====
echo '<h2>📋 Validierungsergebnisse:</h2>';
foreach ($checks as $check) {
    echo '<div class="check ' . $check['status'] . '">';
    echo '<div class="check-icon">' . $check['icon'] . '</div>';
    echo '<div class="check-text">';
    echo '<strong>' . htmlspecialchars($check['title']) . '</strong>';
    if (!empty($check['value'])) {
        echo '<div class="check-value">' . htmlspecialchars($check['value']) . '</div>';
    }
    echo '</div>';
    echo '</div>';
}

// ===== SUMMARY =====
if ($error_count === 0 && $warning_count === 0) {
    $summary_status = 'success';
    $summary_title = '✅ Alles OK!';
    $summary_text = 'Die Installation ist vollständig und korrekt konfiguriert.';
} elseif ($error_count === 0) {
    $summary_status = 'warning';
    $summary_title = '⚠️ Warnungen vorhanden';
    $summary_text = 'Es gibt ' . $warning_count . ' Warnung(en), aber keine kritischen Fehler.';
} else {
    $summary_status = 'error';
    $summary_title = '❌ Fehler vorhanden';
    $summary_text = 'Es gibt ' . $error_count . ' kritische Fehler, die behoben werden müssen.';
}

echo '<div class="summary ' . $summary_status . '">';
echo '<h3>' . $summary_title . '</h3>';
echo '<p>' . $summary_text . '</p>';
echo '<p style="margin: 0;">Erfolg: ' . $success_count . ' | Warnungen: ' . $warning_count . ' | Fehler: ' . $error_count . '</p>';
echo '</div>';

// ===== NEXT STEPS =====
if ($error_count === 0) {
    echo '<h2>✨ Nächste Schritte:</h2>';
    echo '<ol>';
    echo '<li>Demo Plugin ist aktiv und korrekt konfiguriert</li>';
    echo '<li>Testen Sie die Demo-Registrierung: <a href="' . home_url() . '?page_id=registration" target="_blank">Demo Registration Page</a></li>';
    echo '<li>Prüfen Sie die WordPress Admin Seite: ChurchTools Suite → Demo Users</li>';
    echo '<li>Testen Sie das Event Modal: Klicken Sie auf ein Demo Event</li>';
    echo '</ol>';
} else {
    echo '<h2>🔧 Fehlerbehandlung:</h2>';
    echo '<div style="background: #fee2e2; padding: 15px; border-radius: 4px; margin-top: 10px;">';
    echo '<p><strong>Fehler müssen behoben werden:</strong></p>';
    echo '<ol>';
    
    if ($error_count > 0) {
        echo '<li><strong>Plugin deaktivieren/aktivieren:</strong>';
        echo '<div class="code">1. WordPress Admin → Plugins<br>';
        echo '2. "ChurchTools Suite Demo" deaktivieren<br>';
        echo '3. 5 Sekunden warten<br>';
        echo '4. "ChurchTools Suite Demo" aktivieren<br>';
        echo '5. Diese Seite neu laden (F5)</div></li>';
        
        echo '<li><strong>Wenn Table nicht existiert, manuelle SQL ausführen:</strong>';
        echo '<div class="code">CREATE TABLE IF NOT EXISTS ' . $table_name . ' (<br>';
        echo '  id bigint(20) unsigned NOT NULL AUTO_INCREMENT,<br>';
        echo '  email varchar(255) NOT NULL UNIQUE,<br>';
        echo '  name varchar(255) NOT NULL,<br>';
        echo '  organization varchar(255),<br>';
        echo '  purpose text,<br>';
        echo '  verification_token varchar(64) NOT NULL UNIQUE,<br>';
        echo '  is_verified tinyint(1) DEFAULT 0,<br>';
        echo '  verified_at datetime,<br>';
        echo '  wordpress_user_id bigint(20),<br>';
        echo '  last_login_at datetime,<br>';
        echo '  expires_at datetime,<br>';
        echo '  created_at datetime DEFAULT CURRENT_TIMESTAMP,<br>';
        echo '  updated_at datetime ON UPDATE CURRENT_TIMESTAMP,<br>';
        echo '  PRIMARY KEY (id),<br>';
        echo '  UNIQUE KEY email (email),<br>';
        echo '  UNIQUE KEY verification_token (verification_token),<br>';
        echo '  KEY verified_at (verified_at),<br>';
        echo '  KEY created_at (created_at),<br>';
        echo '  KEY wordpress_user_id (wordpress_user_id)<br>';
        echo ') DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;</div></li>';
        
        echo '<li><strong>Diese Validator-Seite neu laden:</strong> <a href="">Hier klicken</a></li>';
    }
    
    echo '</ol>';
    echo '</div>';
}

?>

    <div class="footer">
        <p>🔒 Diese Datei sollte nur auf Testsystemen verfügbar sein. Im Produktion empfohlen: Löschen oder mit Authentifizierung schützen.</p>
        <p>Letzte Überprüfung: <?php echo date('Y-m-d H:i:s'); ?></p>
    </div>

</div>

</body>
</html>
