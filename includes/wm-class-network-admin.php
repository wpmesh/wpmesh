<?php
namespace WM_Mesh;

if (!defined('ABSPATH')) exit;

/**
 * Registers the Mesh Network Admin menu.
 */
class Network_Admin {

    private static $instance = null;

    /**
     * Singleton instance.
     */
    public static function instance() {
        if (self::$instance === null) self::$instance = new self();
        return self::$instance;
    }

    private function __construct() {
        add_action('admin_menu', [$this, 'register_menu']);
    }

    /**
     * Checks if current user can access Mesh Network screens.
     */
    private function can_access_network() {
        return current_user_can('mesh_global_admin') || current_user_can('manage_options');
    }

    /**
     * Registers Mesh Network menu and submenus.
     */
    public function register_menu() {

        if (!$this->can_access_network()) {
            return;
        }

        add_menu_page(
            __('Mesh Network', 'wp-mesh'),
            __('Mesh Network', 'wp-mesh'),
            'manage_options',
            'wm-network-dashboard',
            [$this, 'render_dashboard'],
            'dashicons-share-alt2',
            2
        );

        add_submenu_page(
            'wm-network-dashboard',
            __('Dashboard', 'wp-mesh'),
            __('Dashboard', 'wp-mesh'),
            'manage_options',
            'wm-network-dashboard',
            [$this, 'render_dashboard']
        );

        add_submenu_page(
            'wm-network-dashboard',
            __('Sites', 'wp-mesh'),
            __('Sites', 'wp-mesh'),
            'manage_options',
            'wm-network-sites',
            [$this, 'render_sites']
        );

        add_submenu_page(
            'wm-network-dashboard',
            __('Users', 'wp-mesh'),
            __('Users', 'wp-mesh'),
            'manage_options',
            'wm-network-users',
            [$this, 'render_users']
        );

        add_submenu_page(
            'wm-network-dashboard',
            __('Themes', 'wp-mesh'),
            __('Themes', 'wp-mesh'),
            'manage_options',
            'wm-network-themes',
            [$this, 'render_themes']
        );

        add_submenu_page(
            'wm-network-dashboard',
            __('Plugins', 'wp-mesh'),
            __('Plugins', 'wp-mesh'),
            'manage_options',
            'wm-network-plugins',
            [$this, 'render_plugins']
        );

        add_submenu_page(
            'wm-network-dashboard',
            __('Settings', 'wp-mesh'),
            __('Settings', 'wp-mesh'),
            'manage_options',
            'wm-network-settings',
            [$this, 'render_settings']
        );
    }

    public function render_dashboard()  { include WM_MESH_DIR . 'views/network/wm-network-dashboard.php'; }
    public function render_sites()      { include WM_MESH_DIR . 'views/network/wm-network-sites.php'; }
    public function render_users()      { include WM_MESH_DIR . 'views/network/wm-network-users.php'; }
    public function render_themes()     { include WM_MESH_DIR . 'views/network/wm-network-themes.php'; }
    public function render_plugins()    { include WM_MESH_DIR . 'views/network/wm-network-plugins.php'; }
    public function render_settings()   { include WM_MESH_DIR . 'views/network/wm-network-settings.php'; }
}
