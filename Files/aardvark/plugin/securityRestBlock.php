<?php
if (!defined('ABSPATH')) exit; // Prevent direct access

// Check if the toggle is enabled
$block_users_endpoint = get_option('sipylus_block_rest_users', '');

if ($block_users_endpoint) {
    add_filter('rest_endpoints', function($endpoints) {
        if (isset($endpoints['/wp/v2/users'])) {
            unset($endpoints['/wp/v2/users']);
        }
        if (isset($endpoints['/wp/v2/users/(?P<id>[\d]+)'])) {
            unset($endpoints['/wp/v2/users/(?P<id>[\d]+)']);
        }
        return $endpoints;
    });
}
