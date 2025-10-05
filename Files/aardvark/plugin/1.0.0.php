<?php
/**
 * securityXMLRPC.php — helper for Aardvark plugin
 */

if (!defined('ABSPATH')) exit;

function aardvark_register_xmlrpc_setting() {
    register_setting('aardvark_secure_group', 'aardvark_xmlrpc_block');
}
add_action('admin_init', 'aardvark_register_xmlrpc_setting');

function aardvark_apply_xmlrpc_block() {
    $enabled = get_option('aardvark_xmlrpc_block', 0);

    // Always kill inside WordPress if enabled
    if ($enabled) {
        add_filter('xmlrpc_enabled', '__return_false');
        add_action('template_redirect', function () {
            if (strpos($_SERVER['REQUEST_URI'], 'xmlrpc.php') !== false) {
                header('HTTP/1.1 403 Forbidden');
                exit('XML-RPC services are disabled on this site.');
            }
        });
    }

    // Sync .htaccess rules with checkbox
    aardvark_sync_xmlrpc_htaccess($enabled);
}
add_action('init', 'aardvark_apply_xmlrpc_block');

/**
 * Add/remove XML-RPC block rules in .htaccess depending on checkbox state
 */
function aardvark_sync_xmlrpc_htaccess($enabled) {
    $htaccess_file = ABSPATH . '.htaccess';
    if (!file_exists($htaccess_file) || !is_writable($htaccess_file)) return;

    $marker_start = "# Aardvark Security — Block XML-RPC";
    $marker_end   = "# End Aardvark Security — Block XML-RPC";

    $rule_block = <<<EOT
{$marker_start}
<Files "xmlrpc.php">
    Require all denied
</Files>
{$marker_end}
EOT;

    $contents = file_get_contents($htaccess_file);

    if ($enabled) {
        // Insert if missing
        if (strpos($contents, $marker_start) === false) {
            $contents = rtrim($contents) . "\n\n" . $rule_block . "\n";
            file_put_contents($htaccess_file, $contents, LOCK_EX);
        }
    } else {
        // Remove if present
        $pattern = '/' . preg_quote($marker_start, '/') . '.*?' . preg_quote($marker_end, '/') . '\n?/s';
        $new_contents = preg_replace($pattern, '', $contents);
        if ($new_contents !== $contents) {
            file_put_contents($htaccess_file, trim($new_contents) . "\n", LOCK_EX);
        }
    }
}
