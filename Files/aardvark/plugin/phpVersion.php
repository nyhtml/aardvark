<?php
/*
Plugin Name: phpVersion.php
Plugin URI: https://github.com/nyhtml/aardvark
Description: Shows the server PHP and MySQL version in the WP Dashboard "At a Glance" widget.
Version: 1.0.1
Author: Stephan Pringle
Author URI: https://www.stephanpringle.com/#aardvark
Contributors: nyhtml
Text Domain: aardvark
License: GPLv3 or later
License URI: https://www.gnu.org/licenses/gpl.html
*/

add_action('admin_enqueue_scripts', 'dpv_enqueue_script');

function dpv_enqueue_script($hook) {
    // Only run on the Dashboard main page
    if ('index.php' !== $hook) return;

    // Check if feature is enabled (corrected key)
    if (!get_option('aardvark_php_version_display')) return;

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

    // Pass version data to JS
    wp_localize_script('phpv_script', 'phpvObj', [
        'phpversion'   => phpversion(),
        'mysqlversion' => $mysqlver
    ]);
}
