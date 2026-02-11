<?php
namespace WM_Mesh;

if (!defined('ABSPATH')) exit;

/**
 * Main Mesh Core Loader.
 */
class Core {

    private static $instance = null;

    /**
     * Singleton instance.
     */
    public static function instance() {
        if (self::$instance === null) self::$instance = new self();
        return self::$instance;
    }

    private function __construct() {
        $this->includes();
        $this->init_hooks();
    }

    /**
     * Includes all core classes.
     */
    private function includes() {
        require_once WM_MESH_DIR . 'includes/wm-class-roles.php';
        require_once WM_MESH_DIR . 'includes/wm-class-admin-bar.php';
        require_once WM_MESH_DIR . 'includes/wm-class-network-admin.php';
        require_once WM_MESH_DIR . 'includes/wm-class-wizard.php';
        require_once WM_MESH_DIR . 'includes/wm-class-capabilities.php';
        require_once WM_MESH_DIR . 'includes/wm-class-registry.php';
        require_once WM_MESH_DIR . 'includes/wm-class-health.php';
        require_once WM_MESH_DIR . 'includes/wm-class-events.php';
        require_once WM_MESH_DIR . 'includes/wm-class-logger.php';
        require_once WM_MESH_DIR . 'includes/wm-class-users.php';
        require_once WM_MESH_DIR . 'includes/wm-class-updater.php';
        require_once WM_MESH_DIR . 'includes/wm-class-modules.php';
        require_once WM_MESH_DIR . 'includes/helpers/wm-github.php';
    }

    /**
     * Initializes hooks and subsystems.
     */
    private function init_hooks() {
        Roles::init();
        Admin_Bar::instance();
        Network_Admin::instance();
        Wizard::instance();
        Capabilities::instance();
        Registry::instance();
        Users::instance();
        Updater::instance();
        Modules::instance();
    }

    /**
     * Returns or generates the unique node ID for this site.
     */
    public static function get_node_id() {
        $id = get_option('wm_mesh_node_id');
        if (!$id) {
            $id = 'mesh-' . wp_generate_uuid4();
            update_option('wm_mesh_node_id', $id);
        }
        return $id;
    }
}
