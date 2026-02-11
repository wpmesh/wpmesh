<?php
/**
 * Plugin Name: WP Mesh Core
 * Description: Kernel del protocollo federato Mesh per WordPress.
 * Version: 0.1.0
 * Author: wpmesh
 */

if (!defined('ABSPATH')) exit;

define('WM_MESH_CORE_VERSION', '0.1.0');
define('WM_MESH_CORE_DIR', plugin_dir_path(__FILE__));
define('WM_MESH_CORE_URL', plugin_dir_url(__FILE__));

require_once WM_MESH_CORE_DIR . 'includes/wm-class-core.php';

function wm_mesh_core_bootstrap() {
    \WM_Mesh\Core::instance();
}
add_action('plugins_loaded', 'wm_mesh_core_bootstrap');
