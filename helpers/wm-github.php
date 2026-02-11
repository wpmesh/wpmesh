<?php
namespace WM_Mesh;

if (!defined('ABSPATH')) exit;

/**
 * Helper for GitHub API requests.
 */
class GitHub {

    public static function get($url) {

        $response = wp_remote_get($url, [
            'headers' => ['User-Agent' => 'wpmesh-github']
        ]);

        if (is_wp_error($response)) return false;

        $body = wp_remote_retrieve_body($response);
        return json_decode($body, true);
    }
}
