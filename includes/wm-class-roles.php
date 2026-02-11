<?php
namespace WM_Mesh;

if (!defined('ABSPATH')) exit;

/**
 * Registers Mesh roles and capabilities.
 */
class Roles {

    public static function init() {
        add_action('init', [__CLASS__, 'register_roles']);
    }

    /**
     * Creates the mesh_global_admin role.
     */
    public static function register_roles() {

        add_role(
            'mesh_global_admin',
            __('Mesh Global Admin', 'wp-mesh'),
            [
                'read'              => true,
                'manage_mesh'       => true,
                'manage_mesh_nodes' => true,
                'manage_mesh_users' => true,
                'manage_mesh_roles' => true,
                'manage_options'    => true
            ]
        );
    }

    /**
     * Checks if current user is global admin.
     */
    public static function is_global_admin() {
        $user = wp_get_current_user();
        return in_array('mesh_global_admin', $user->roles);
    }
}
