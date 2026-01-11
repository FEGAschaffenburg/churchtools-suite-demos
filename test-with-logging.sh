#!/bin/bash
cd /var/www/clients/client436/web2975/web

# Clear debug log
rm -f wp-content/debug.log

# Test shortcode
echo "=== Testing Grid Shortcode with Debug Logging ==="
wp eval 'echo do_shortcode("[cts_grid limit=\"3\"]");' > /dev/null

echo ""
echo "=== Debug Log (Full) ==="
cat wp-content/debug.log 2>/dev/null || echo "No log file"
