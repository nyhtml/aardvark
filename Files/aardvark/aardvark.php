<?php
/*
Plugin Name: Aardvark&trade; by Sipylus
Plugin URI: https://github.com/nyhtml/aardvark
Description: The Anomalous Architecture for Responsive Design & Virtual Asset Replication Kit offers security, along with custom shortcodes that feature inline styling and responsive design.
Version: 2025.08.31.1
Author: Stephan Pringle
Author URI: http://www.stephanpringle.com
Contributors: nyhtml
Text Domain: aardvark
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html
*/

if (!defined('ABSPATH')) exit;

define('AARDVARK_PLUGIN_DIR', plugin_dir_path(__FILE__));
define('AARDVARK_PLUGIN_URL', plugin_dir_url(__FILE__));

// --- Load required files ---
require_once AARDVARK_PLUGIN_DIR . 'shortcode/cardNetworks.php';
require_once AARDVARK_PLUGIN_DIR . 'shortcode/cardResume.php';
require_once AARDVARK_PLUGIN_DIR . 'shortcode/cardSkill.php';
require_once AARDVARK_PLUGIN_DIR . 'plugin/phpVersion.php';
require_once AARDVARK_PLUGIN_DIR . 'plugin/securityRestBlock.php';

// --- Admin Menu ---
add_action('admin_menu', 'aardvark_admin_menu');
function aardvark_admin_menu() {
    add_menu_page(
        'Aardvark™ Settings',
        'Aardvark™',
        'manage_options',
        'aardvark-settings',
        'aardvark_settings_page',
        'dashicons-shield'
    );
    add_submenu_page(
        'aardvark-settings',
        'Aardvark™ Reset',
        'Reset',
        'manage_options',
        'aardvark-reset',
        'aardvark_reset_page'
    );
}

// --- Add Settings link on Plugins page ---
add_filter('plugin_action_links_' . plugin_basename(__FILE__), 'aardvark_custom_shortcodes_settings_link');
function aardvark_custom_shortcodes_settings_link($links) {
    $settings_link = '<a href="admin.php?page=aardvark-settings">Settings</a>';
    array_unshift($links, $settings_link);
    return $links;
}

// --- Register Settings ---
add_action('admin_init', 'aardvark_register_settings');
function aardvark_register_settings() {
    register_setting('aardvark_settings_group', 'aardvark_facebook');
    register_setting('aardvark_settings_group', 'aardvark_twitter');
    register_setting('aardvark_settings_group', 'aardvark_linkedin');
    register_setting('aardvark_settings_group', 'aardvark_github');
    register_setting('aardvark_settings_group', 'aardvark_youtube');
    register_setting('aardvark_settings_group', 'aardvark_instagram');
    register_setting('aardvark_power_group', 'aardvark_php_version_display');
    register_setting('aardvark_power_group', 'aardvark_rest_api_block');
}

// --- Settings Page ---
function aardvark_settings_page() {
    ?>
    <div class="wrap">
        <h1>Aardvark™ Settings</h1>
        <h2 class="nav-tab-wrapper">
            <a href="?page=aardvark-settings&tab=social" class="nav-tab <?php echo (!isset($_GET['tab']) || $_GET['tab'] == 'social') ? 'nav-tab-active' : ''; ?>">Social Media</a>
            <a href="?page=aardvark-settings&tab=power" class="nav-tab <?php echo (isset($_GET['tab']) && $_GET['tab'] == 'power') ? 'nav-tab-active' : ''; ?>">Power Options</a>
        </h2>
        <?php
        $active_tab = isset($_GET['tab']) ? $_GET['tab'] : 'social';
        if ($active_tab == 'social') {
            ?>
            <form method="post" action="options.php">
                <?php settings_fields('aardvark_settings_group'); ?>
                <table class="form-table">
                    <tr><th>Facebook URL or Username</th>
                        <td><input type="text" name="aardvark_facebook" value="<?php echo esc_attr(get_option('aardvark_facebook')); ?>" placeholder="e.g. username or https://facebook.com/username"></td></tr>
                    <tr><th>Twitter URL or Username</th>
                        <td><input type="text" name="aardvark_twitter" value="<?php echo esc_attr(get_option('aardvark_twitter')); ?>" placeholder="e.g. username or https://twitter.com/username"></td></tr>
                    <tr><th>LinkedIn URL or Username</th>
                        <td><input type="text" name="aardvark_linkedin" value="<?php echo esc_attr(get_option('aardvark_linkedin')); ?>" placeholder="e.g. username or https://linkedin.com/in/username"></td></tr>
                    <tr><th>Github URL or Username</th>
                        <td><input type="text" name="aardvark_github" value="<?php echo esc_attr(get_option('aardvark_github')); ?>" placeholder="e.g. username or https://github.com/username"></td></tr>
                    <tr><th>Youtube URL or Username</th>
                        <td><input type="text" name="aardvark_youtube" value="<?php echo esc_attr(get_option('aardvark_youtube')); ?>" placeholder="e.g. @username or https://youtube.com/@username"></td></tr>
                    <tr><th>Instagram URL or Username</th>
                        <td><input type="text" name="aardvark_instagram" value="<?php echo esc_attr(get_option('aardvark_instagram')); ?>" placeholder="e.g. username or https://instagram.com/username"></td></tr>
                </table>
                <?php submit_button(); ?>
            </form>
            <?php
        } elseif ($active_tab == 'power') {
            ?>
            <form method="post" action="options.php">
                <?php settings_fields('aardvark_power_group'); ?>
                <table class="form-table">
                    <tr><th>Enable PHP Version Display</th>
                        <td><input type="checkbox" name="aardvark_php_version_display" value="1" <?php checked(1, get_option('aardvark_php_version_display'), true); ?> /> Show PHP & MySQL versions on Dashboard</td></tr>
                    <tr><th>Block User REST Endpoint</th>
                        <td><input type="checkbox" name="aardvark_rest_api_block" value="1" <?php checked(1, get_option('aardvark_rest_api_block'), true); ?> /> Disable REST API endpoint</td></tr>
                </table>
                <?php submit_button(); ?>
            </form>
            <?php
        }
        ?>
    </div>
    <?php
}

// --- Reset Page ---
function aardvark_reset_page() {
    if (isset($_POST['aardvark_reset_confirm'])) {
        delete_option('aardvark_facebook');
        delete_option('aardvark_twitter');
        delete_option('aardvark_linkedin');
        delete_option('aardvark_github');
        delete_option('aardvark_youtube');
        delete_option('aardvark_instagram');
        delete_option('aardvark_php_version_display');
        delete_option('aardvark_rest_api_block');
        echo '<div class="updated"><p>Aardvark plugin data has been deleted.</p></div>';
    }
    ?>
    <div class="wrap">
        <h1>Aardvark™ Tools</h1>
        <form method="post">
            <p>Clicking the button below will delete all Aardvark settings from the database. This does NOT uninstall the plugin.</p>
            <input type="hidden" name="aardvark_reset_confirm" value="1">
            <?php submit_button('Reset Plugin Data', 'delete'); ?>
        </form>
    </div>
    <?php
}
