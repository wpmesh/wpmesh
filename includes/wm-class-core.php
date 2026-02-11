<?php
namespace WM_Mesh;

if (!defined('ABSPATH')) exit;

class Core {

    private static $instance = null;

    public static function instance() {
        if (self::$instance === null) self::$instance = new self();
        return self::$instance;
    }

    private function __construct() {
        $this->includes();
        $this->init_hooks();
    }

    private function includes() {
        require_once WM_MESH_CORE_DIR . 'includes/wm-class-admin-bar.php';
        require_once WM_MESH_CORE_DIR . 'includes/wm-class-network-admin.php';
        require_once WM_MESH_CORE_DIR . 'includes/wm-class-wizard.php';
        require_once WM_MESH_CORE_DIR . 'includes/wm-class-capabilities.php';
    }

    private function init_hooks() {
        Admin_Bar::instance();
        Network_Admin::instance();
        Wizard::instance();
        Capabilities::instance();
    }

    public static function get_node_id() {
        $id = get_option('wm_mesh_node_id');
        if (!$id) {
            $id = 'mesh-' . wp_generate_uuid4();
            update_option('wm_mesh_node_id', $id);
        }
        return $id;
    }
}
