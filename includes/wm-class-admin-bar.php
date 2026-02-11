<?php
namespace WM_Mesh;

if (!defined('ABSPATH')) exit;

/**
 * Adds the Mesh menu to the WordPress admin bar.
 */
class Admin_Bar {

    private static $instance = null;

    public static function instance() {
        if (self::$instance === null) self::$instance = new self();
        return self::$instance;
    }

    private function __construct() {
        add_action('admin_bar_menu', [$this, 'add_menu'], 100);
    }

    public function add_menu($wp_admin_bar) {

        $wp_admin_bar->add_node([
            'id'    => 'wm-mesh',
            'title' => '<span class="ab-icon"></span> Mesh',
            'href'  => admin_url('admin.php?page=wm-network-dashboard')
        ]);

        $wp_admin_bar->add_node([
            'id'     => 'wm-mesh-network',
            'parent' => 'wm-mesh',
            'title'  => __('Network', 'wp-mesh'),
            'href'   => admin_url('admin.php?page=wm-network-dashboard')
        ]);

        $wp_admin_bar->add_node([
            'id'     => 'wm-mesh-site',
            'parent' => 'wm-mesh',
            'title'  => __('Site', 'wp-mesh'),
            'href'   => admin_url()
        ]);
    }
}
