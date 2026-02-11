<?php
namespace WM_Mesh;

if (!defined('ABSPATH')) exit;

/**
 * Provides health information for the local Mesh node.
 */
class Health {

    public static function get_status() {
        return [
            'php_version'      => PHP_VERSION,
            'wordpress'        => get_bloginfo('version'),
            'mesh_version'     => WM_MESH_VERSION,
            'multisite'        => is_multisite(),
            'cron_running'     => wp_next_scheduled('wm_mesh_cron_refresh_nodes') ? true : false,
            'timestamp'        => current_time('mysql')
        ];
    }
}
