<?php
namespace WM_Mesh;

use WP_REST_Response;

if (!defined('ABSPATH')) exit;

/**
 * Exposes Mesh node capabilities via REST API.
 */
class Capabilities {

    private static $instance = null;

    /**
     * Singleton instance.
     */
    public static function instance() {
        if (self::$instance === null) self::$instance = new self();
        return self::$instance;
    }

    private function __construct() {
        add_action('rest_api_init', [$this, 'register']);
    }

    /**
     * Registers REST routes for node capabilities.
     */
    public function register() {
        register_rest_route('wp-mesh/v1', '/node/capabilities', [
            'methods'  => 'GET',
            'callback' => [$this, 'get'],
            'permission_callback' => '__return_true'
        ]);
    }

    /**
     * Returns capabilities and context for this node.
     */
    public function get() {

        $current_user = wp_get_current_user();

        $data = [
            'node_id'      => Core::get_node_id(),
            'mesh_version' => WM_MESH_VERSION,
            'wordpress'    => get_bloginfo('version'),
            'php'          => PHP_VERSION,
            'multisite'    => is_multisite(),
            'timestamp'    => current_time('mysql'),
            'user'         => [
                'id'          => $current_user->ID,
                'name'        => $current_user->display_name,
                'email'       => $current_user->user_email,
                'roles'       => $current_user->roles,
                'federated'   => get_user_meta($current_user->ID, 'wm_mesh_federated_origin', true),
                'materialized_on' => get_user_meta($current_user->ID, 'wm_mesh_materialized_on', true),
            ],
            'permissions'  => [
                'global_admin' => Roles::is_global_admin(),
                'manage_mesh'  => current_user_can('manage_mesh'),
            ]
        ];

        return new WP_REST_Response($data, 200);
    }
}
