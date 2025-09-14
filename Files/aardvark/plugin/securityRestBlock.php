<?php
/**
 * securityRestBlock.php — helper for Aardvark plugin
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
