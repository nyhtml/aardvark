<?php
/*
Plugin Name: securityRestBlock.php
Plugin URI: https://github.com/nyhtml/aardvark
Description: Prevents WordPress REST API user enumeration.
Version: 1.0.1
Author: Stephan Pringle
Author URI: https://www.stephanpringle.com/#aardvark
Contributors: nyhtml
Text Domain: aardvark
License: GPLv3 or later
License URI: https://www.gnu.org/licenses/gpl.html
*/

if (!defined('ABSPATH')) exit; // Prevent direct access

add_action('rest_api_init', function() {
    // Correct option key from aardvark.php
    $block_users_endpoint = filter_var(get_option('aardvark_rest_api_block', false), FILTER_VALIDATE_BOOLEAN);

    if ($block_users_endpoint) {
        add_filter('rest_endpoints', function($endpoints) {
            unset($endpoints['/wp/v2/users']);
            unset($endpoints['/wp/v2/users/(?P<id>[\d]+)']);
            return $endpoints;
        });
    }
});
