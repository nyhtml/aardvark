<?php
/**
 * Plugin Name: Display PHP Version (Aardvark)
 * Description: Shows the server PHP and MySQL version in the WP Dashboard "At a Glance" widget.
 * Version: 1.0
 * Author: Your Name or Company
 */

add_action('admin_enqueue_scripts', 'dpv_enqueue_script');

/**
 * Enqueue JS and pass PHP/MySQL version info to it for dashboard display
 *
 * @param string $hook The current admin page hook
 */
function dpv_enqueue_script($hook) {
    // Only run on the Dashboard main page
    if ('index.php' !== $hook) return;

    // Check if feature is enabled
    if (!get_option('sipylus_enable_phpversion')) return;

    // Calculate the correct JS URL relative to this file
    $js_url = plugin_dir_url(__DIR__) . 'assets/phpv.js';

    // Enqueue the JS file
    wp_enqueue_script(
        'phpv_script',
        $js_url,
        ['jquery'],
        false,
        true
    );

    // Get MySQL version
    $conn = @mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
    $mysqlver = $conn ? preg_replace('/^5.5.5-/i', '', mysqli_get_server_info($conn)) : 'unknown';
    if ($conn) mysqli_close($conn);

    // Localize PHP & MySQL version data for use in JS
    wp_localize_script('phpv_script', 'phpvObj', [
        'phpversion'   => phpversion(),
        'mysqlversion' => $mysqlver
    ]);
}
