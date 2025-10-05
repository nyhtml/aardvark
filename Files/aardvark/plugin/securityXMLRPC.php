<?php
/**
 * securityXMLRPC.php — helper for Aardvark plugin
 */

if (!defined('ABSPATH')) exit; // Prevent direct access
if (!defined('AARDVARK_SECURITYXMLRPC_VERSION')) {
    define('AARDVARK_SECURITYXMLRPC_VERSION', '1.5.0');
}

/**
 * Toggle XML-RPC block in .htaccess
 */
function aardvark_toggle_xmlrpc_block($enable = true, $uninstall = false) {
    $htaccess = ABSPATH . '.htaccess';

    if (!file_exists($htaccess) || !is_writable($htaccess)) {
        return false; // Fail safe
    }

    $contents = file_get_contents($htaccess);

    // Define Aardvark markers
    $start = "# Aardvark Security — Block XML-RPC";
    $end   = "# End Aardvark Security — Block XML-RPC";

    // Define our block
    $block = <<<HTA
$start
<Files "xmlrpc.php">
    Require all denied
</Files>
$end
HTA;

    // Remove any old Aardvark block
    $pattern = "/$start.*?$end/s";
    $contents = preg_replace($pattern, '', $contents);

    // Remove stray legacy xmlrpc.php <Files> blocks
    $legacyPattern = '/<Files\s+"?xmlrpc\.php"?>(.*?)<\/Files>/is';
    $contents = preg_replace($legacyPattern, '', $contents);

    if ($enable) {
        // Insert block before cPanel PHP handler if possible
        $marker = "# php -- BEGIN cPanel-generated handler";
        if (strpos($contents, $marker) !== false) {
			$contents = str_replace($marker, $block . PHP_EOL . PHP_EOL . $marker, $contents);
        } else {
            $contents .= PHP_EOL . $block . PHP_EOL;
        }
    }

    if ($uninstall) {
        // On uninstall, leave a plain vanilla block without branding
        $vanilla = <<<HTA
<Files "xmlrpc.php">
    Require all denied
</Files>
HTA;
		$contents .= PHP_EOL . PHP_EOL . $vanilla . PHP_EOL;
    }

    // Save back
    file_put_contents($htaccess, trim($contents) . PHP_EOL);
    return true;
}

/**
 * Register setting
 */
function aardvark_register_security_settings() {
    register_setting('aardvark_secure_group', 'aardvark_xmlrpc_block');
}
add_action('admin_init', 'aardvark_register_security_settings');

/**
 * Admin settings UI
 */
function aardvark_security_settings_page() {
    ?>
    <h1>👮 Security Settings</h1>
    <form method="post" action="options.php">
        <?php settings_fields('aardvark_secure_group'); ?>
        <table class="form-table">
            <tr>
                <th>🔒 Block XML-RPC</th>
                <td>
                    <input type="checkbox" name="aardvark_xmlrpc_block" value="1"
                        <?php checked(1, get_option('aardvark_xmlrpc_block'), true); ?> />
                    Disable XML-RPC endpoint in .htaccess
                </td>
            </tr>
        </table>
        <?php submit_button(); ?>
    </form>
    <?php
}

/**
 * Hook option change to toggle XML-RPC block
 */
function aardvark_update_xmlrpc_toggle($old_value, $value) {
    if ($value == 1) {
        aardvark_toggle_xmlrpc_block(true);
    } else {
        aardvark_toggle_xmlrpc_block(false);
    }
}
add_action('update_option_aardvark_xmlrpc_block', 'aardvark_update_xmlrpc_toggle', 10, 2);

/**
 * On uninstall, restore a plain block
 */
function aardvark_security_uninstall() {
    aardvark_toggle_xmlrpc_block(false, true);
}
register_uninstall_hook(__FILE__, 'aardvark_security_uninstall');
