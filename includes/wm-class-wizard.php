<?php
namespace WM_Mesh;

if (!defined('ABSPATH')) exit;

/**
 * Mesh Setup Wizard
 */
class Wizard {

    private static $instance = null;
    private $steps = ['intro','scan','profile','modules','node','summary'];

    public static function instance() {
        if (self::$instance === null) self::$instance = new self();
        return self::$instance;
    }

    private function __construct() {
        add_action('admin_menu', [$this, 'add_wizard_page']);
    }

    public function add_wizard_page() {
        add_submenu_page(
            null,
            __('Mesh Wizard', 'wp-mesh'),
            __('Mesh Wizard', 'wp-mesh'),
            'manage_options',
            'wm-mesh-wizard',
            [$this, 'render']
        );
    }

    public function render() {
        $step = $_GET['step'] ?? 'intro';
        if (!in_array($step, $this->steps)) $step = 'intro';

        include WM_MESH_DIR . "views/wizard/wm-step-$step.php";
    }
}
