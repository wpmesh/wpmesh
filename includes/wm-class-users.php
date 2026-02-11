<?php
namespace WM_Mesh;

if (!defined('ABSPATH')) exit;

/**
 * Handles Mesh federated users.
 * - Local admin sees only local users.
 * - Global admin sees users grouped by node.
 * - When a federated user logs in, they become a local user.
 */
class Users {

    private static $instance = null;

    public static function instance() {
        if (self::$instance === null) self::$instance = new self();
        return self::$instance;
    }

    private function __construct() {
        add_action('rest_api_init', [$this, 'register_routes']);
        add_action('wp_login', [$this, 'materialize_federated_user'], 10, 2);
    }

    /**
     * REST endpoint: returns local users for this node.
     */
    public function register_routes() {

        register_rest_route('wp-mesh/v1', '/node/users', [
            'methods'  => 'GET',
            'callback' => [$this, 'get_local_users'],
            'permission_callback' => '__return_true'
        ]);
    }

    /**
     * Returns local users for this node.
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
                'federated' => get_user_meta($u->ID, 'wm_mesh_federated_origin', true)
            ];
        }

        return $users;
    }

    /**
     * When a federated user logs in, they become a local user.
     * This is the "materialization" step.
     */
    public function materialize_federated_user($user_login, $user) {

        // If already local, nothing to do
        if (!get_user_meta($user->ID, 'wm_mesh_federated_origin', true)) {
            return;
        }

        // Mark user as "materialized" on this node
        update_user_meta($user->ID, 'wm_mesh_materialized_on', Core::get_node_id());
    }

    /**
     * Returns users grouped by node for global admin.
     */
    public static function get_users_grouped_by_node() {

        $registry = Registry::instance()->get_nodes();
        $result = [];

        // Local node first
        $result['local'] = [
            'node_id' => Core::get_node_id(),
            'users'   => self::get_local_users_static()
        ];

        // Remote nodes
        foreach ($registry as $node) {

            $url = $node['url'];
            $endpoint = $url . '/wp-json/wp-mesh/v1/node/users';

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
                'users'   => $data
            ];
        }

        return $result;
    }

    /**
     * Static helper for local users.
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
                'federated' => get_user_meta($u->ID, 'wm_mesh_federated_origin', true)
            ];
        }

        return $users;
    }
}
