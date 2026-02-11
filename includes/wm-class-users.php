<?php
namespace WM_Mesh;

if (!defined('ABSPATH')) exit;

/**
 * Handles Mesh federated users.
 * - Local admin sees only local users.
 * - Mesh Global Admin sees users grouped by node.
 * - When a federated user logs in, they become a local user (materialization).
 */
class Users {

    private static $instance = null;

    /**
     * Singleton instance.
     */
    public static function instance() {
        if (self::$instance === null) self::$instance = new self();
        return self::$instance;
    }

    private function __construct() {
        add_action('rest_api_init', [$this, 'register_routes']);
        add_action('wp_login', [$this, 'materialize_federated_user'], 10, 2);
        add_action('user_register', [$this, 'tag_local_user']);
    }

    /**
     * Tags a newly created user as originating from this node.
     */
    public function tag_local_user($user_id) {
        update_user_meta($user_id, 'wm_mesh_federated_origin', Core::get_node_id());
        update_user_meta($user_id, 'wm_mesh_last_seen', current_time('mysql'));
    }

    /**
     * Registers REST routes for user federation.
     */
    public function register_routes() {

        register_rest_route('wp-mesh/v1', '/node/users', [
            'methods'  => 'GET',
            'callback' => [$this, 'get_local_users'],
            'permission_callback' => '__return_true'
        ]);
    }

    /**
     * Returns local users for this node (REST).
     */
    public function get_local_users() {

        $wp_users = get_users();
        $users = [];

        foreach ($wp_users as $u) {
            $users[] = [
                'id'        => $u->ID,
                'name'      => $u->display_name,
                'email'     => $u->user_email,
                'roles'     => $u->roles,
                'federated' => get_user_meta($u->ID, 'wm_mesh_federated_origin', true),
                'materialized_on' => get_user_meta($u->ID, 'wm_mesh_materialized_on', true),
                'last_seen' => get_user_meta($u->ID, 'wm_mesh_last_seen', true),
            ];
        }

        return $users;
    }

    /**
     * When a federated user logs in, they become a local user.
     * This is the "materialization" step.
     */
    public function materialize_federated_user($user_login, $user) {

        // If user has no federated origin, nothing to do.
        if (!get_user_meta($user->ID, 'wm_mesh_federated_origin', true)) {
            return;
        }

        // Mark user as materialized on this node and update last seen.
        update_user_meta($user->ID, 'wm_mesh_materialized_on', Core::get_node_id());
        update_user_meta($user->ID, 'wm_mesh_last_seen', current_time('mysql'));
    }

    /**
     * Returns users grouped by node for Mesh Global Admin.
     */
    public static function get_users_grouped_by_node() {

        $registry = Registry::instance()->get_nodes();
        $result = [];

        // Local node
        $result['local'] = [
            'node_id' => Core::get_node_id(),
            'users'   => self::get_local_users_static()
        ];

        // Remote nodes
        foreach ($registry as $node) {

            $url = $node['url'];
            $endpoint = rtrim($url, '/') . '/wp-json/wp-mesh/v1/node/users';

            $response = wp_remote_get($endpoint);

            if (is_wp_error($response)) {
                $result[$url] = [
                    'node_id' => $node['node_id'],
                    'users'   => [],
                    'error'   => 'offline'
                ];
                continue;
            }

            $data = json_decode(wp_remote_retrieve_body($response), true);

            $result[$url] = [
                'node_id' => $node['node_id'],
                'users'   => is_array($data) ? $data : []
            ];
        }

        return $result;
    }

    /**
     * Static helper: returns local users as array.
     */
    public static function get_local_users_static() {
        $wp_users = get_users();
        $users = [];

        foreach ($wp_users as $u) {
            $users[] = [
                'id'        => $u->ID,
                'name'      => $u->display_name,
                'email'     => $u->user_email,
                'roles'     => $u->roles,
                'federated' => get_user_meta($u->ID, 'wm_mesh_federated_origin', true),
                'materialized_on' => get_user_meta($u->ID, 'wm_mesh_materialized_on', true),
                'last_seen' => get_user_meta($u->ID, 'wm_mesh_last_seen', true),
            ];
        }

        return $users;
    }
}
