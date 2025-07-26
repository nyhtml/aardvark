<?php
/*
Plugin Name: Sipylus Dashboard Widget
Description: Displays the current PHP version in the "At a Glance" admin dashboard widget.
Version: 
Author: <a href="http://www.stephanpringle.com">Stephan Pringle</a>
*/

function dpv_enqueue_script($hook) {
    if ('index.php' !== $hook) return;
    if (!get_option('sipylus_enable_phpversion')) return;

    wp_enqueue_script(
        'phpv_script',
        plugin_dir_url(__FILE__) . '../assets/phpv.js',
        ['jquery'],
        '1.0',
        true
    );

    $conn = @mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
    $mysqlver = $conn ? preg_replace('/^5.5.5-/i', '', mysqli_get_server_info($conn)) : 'unknown';
    if ($conn) mysqli_close($conn);

    wp_localize_script('phpv_script', 'phpvObj', [
        'phpversion' => phpversion(),
        'mysqlversion' => $mysqlver
    ]);
}
add_action('admin_enqueue_scripts', 'dpv_enqueue_script');
