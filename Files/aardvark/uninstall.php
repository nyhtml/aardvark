<?php
/*
Plugin Name: uninstall.php
Plugin URI: https://github.com/nyhtml/aardvark
Description: Uninstall script for Aardvark Plugin.
Version: 5.0.0
Author: Stephan Pringle
Author URI: https://www.stephanpringle.com/#aardvark
Contributors: nyhtml
Text Domain: aardvark
License: GPLv3 or later
License URI: https://www.gnu.org/licenses/gpl.html
*/

if (!defined('WP_UNINSTALL_PLUGIN')) {
    exit; // Prevent direct access
}

// List of plugin options to remove
$aardvark_options = [
    'aardvark_facebook',
    'aardvark_twitter',
    'aardvark_linkedin',
    'aardvark_github',
    'aardvark_youtube',
    'aardvark_instagram',
    'aardvark_enable_phpversion',
    'aardvark_block_rest_users'
];

// Delete each option
foreach ($aardvark_options as $option) {
    delete_option($option);
}

// If your plugin stores multisite options, remove them as well
if (is_multisite()) {
    global $wpdb;
    $blog_ids = $wpdb->get_col("SELECT blog_id FROM {$wpdb->blogs}");
    foreach ($blog_ids as $blog_id) {
        switch_to_blog($blog_id);
        foreach ($aardvark_options as $option) {
            delete_option($option);
        }
    }
    restore_current_blog();
}
