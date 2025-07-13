<?php
/*
Plugin Name: A.I.O. Sipylus Toolkit
Description: Provides custom shortcodes with inline styling and responsive design.
Version: 2025.07.13
Author: <a title="Stephan Pringle" href="http://www.stephanpringle.com">Stephan Pringle</a>
*/

if (!defined('ABSPATH')) exit; // Prevent direct access

// Define plugin constants
define('SIPYLUS_PLUGIN_DIR', plugin_dir_path(__FILE__));
define('SIPYLUS_PLUGIN_URL', plugin_dir_url(__FILE__));

// Load shortcodes
require_once SIPYLUS_PLUGIN_DIR . 'shortcode/cardResume.php';
require_once SIPYLUS_PLUGIN_DIR . 'shortcode/cardNetworks.php';

// Add Settings link on the Plugins page
function sipylus_custom_shortcodes_settings_link($links) {
    $settings_link = '<a href="options-general.php?page=sipylus-shortcodes-settings">Settings</a>';
    array_unshift($links, $settings_link);
    return $links;
}
add_filter('plugin_action_links_' . plugin_basename(__FILE__), 'sipylus_custom_shortcodes_settings_link');

// Register admin menu
function sipylus_register_admin_menu() {
    add_options_page(
        'Sipylus Shortcodes Settings',   // Page title
        'Sipylus Shortcodes',             // Menu label
        'manage_options',                 // Capability
        'sipylus-shortcodes-settings',   // Menu slug
        'sipylus_render_settings_page'   // Callback function
    );
}
add_action('admin_menu', 'sipylus_register_admin_menu');

// Render the settings page
function sipylus_render_settings_page() {
    ?>
    <div class="wrap">
        <h1>Sipylus Shortcodes Settings</h1>
        <p>Provides custom shortcodes with inline styling and responsive design..</p>
        <form method="post" action="options.php">
            <?php
                settings_fields('sipylus_settings_group');
                do_settings_sections('sipylus-shortcodes');
                submit_button();
            ?>
        </form>
    </div>
    <?php
}

// Register all settings and fields for social media links
function sipylus_register_settings() {

    add_settings_section(
        'sipylus_main_section',
        'Social Media Settings',
        'sipylus_main_section_text',
        'sipylus-shortcodes'
    );

    // Register social media options
    register_setting('sipylus_settings_group', 'sipylus_facebook');
    register_setting('sipylus_settings_group', 'sipylus_twitter');
    register_setting('sipylus_settings_group', 'sipylus_linkedin');
    register_setting('sipylus_settings_group', 'sipylus_github');
    register_setting('sipylus_settings_group', 'sipylus_youtube');

    add_settings_field(
        'sipylus_facebook',
        'Facebook URL or Username',
        'sipylus_facebook_callback',
        'sipylus-shortcodes',
        'sipylus_main_section'
    );
    add_settings_field(
        'sipylus_twitter',
        'Twitter URL or Username',
        'sipylus_twitter_callback',
        'sipylus-shortcodes',
        'sipylus_main_section'
    );
    add_settings_field(
        'sipylus_linkedin',
        'LinkedIn URL or Username',
        'sipylus_linkedin_callback',
        'sipylus-shortcodes',
        'sipylus_main_section'
    );
    add_settings_field(
        'sipylus_github',
        'GitHub URL or Username',
        'sipylus_github_callback',
        'sipylus-shortcodes',
        'sipylus_main_section'
    );
    add_settings_field(
        'sipylus_youtube',
        'YouTube URL or Username',
        'sipylus_youtube_callback',
        'sipylus-shortcodes',
        'sipylus_main_section'
    );
}
add_action('admin_init', 'sipylus_register_settings');

// Description for the Social Media Settings section
function sipylus_main_section_text() {
    echo '<p>Enter your social media profile URLs or usernames below.</p>';
}

// Callback functions to render input fields for social media URLs/usernames

function sipylus_facebook_callback() {
    $value = get_option('sipylus_facebook', '');
    echo '<input type="text" name="sipylus_facebook" value="' . esc_attr($value) . '" class="regular-text" placeholder="e.g. username or https://facebook.com/username">';
}
function sipylus_twitter_callback() {
    $value = get_option('sipylus_twitter', '');
    echo '<input type="text" name="sipylus_twitter" value="' . esc_attr($value) . '" class="regular-text" placeholder="e.g. username or https://twitter.com/username">';
}
function sipylus_linkedin_callback() {
    $value = get_option('sipylus_linkedin', '');
    echo '<input type="text" name="sipylus_linkedin" value="' . esc_attr($value) . '" class="regular-text" placeholder="e.g. username or https://linkedin.com/in/username">';
}
function sipylus_github_callback() {
    $value = get_option('sipylus_github', '');
    echo '<input type="text" name="sipylus_github" value="' . esc_attr($value) . '" class="regular-text" placeholder="e.g. username or https://github.com/username">';
}
function sipylus_youtube_callback() {
    $value = get_option('sipylus_youtube', '');
    echo '<input type="text" name="sipylus_youtube" value="' . esc_attr($value) . '" class="regular-text" placeholder="e.g. @username or https://youtube.com/@username">';
}
