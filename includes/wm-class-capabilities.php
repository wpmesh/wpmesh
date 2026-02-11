<?php
namespace WM_Mesh;

use WP_REST_Response;

if (!defined('ABSPATH')) exit;

/**
 * Exposes Mesh node capabilities via REST API.
 */
class Capabilities {

    private static $instance = null;

    public static function instance() {
        if (self::$instance === null) self::$instance = new self();
        return self::$instance;
    }

    private function __construct() {
        add_action('rest_api_init', [$this, 'register']);
    }

    public function register() {
        register_rest_route('wp-mesh/v1', '/node/capabilities', [
            'methods'  => 'GET',
            'callback' => [$this, 'get'],
            'permission_callback' => '__return_true'
        ]);
    }

    public function get() {
        return new WP_REST_Response([
            'node_id'      => Core::get_node_id(),
            'mesh_version' => WM_MESH_VERSION,
            'wordpress'    => get_bloginfo('version'),
            'php'          => PHP_VERSION,
            'multisite'    => is_multisite(),
            'timestamp'    => current_time('mysql')
        ], 200);
    }
}
