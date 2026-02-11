<?php
/**
 * Plugin Name: WP Mesh
 * Plugin URI: https://wpmesh.org
 * Description: Distributed Network of WordPress Sites.
 * Version: 0.1.0
 * Author: wpmesh
 * Author URI: https://wpmesh.org
 * Text Domain: wp-mesh
 * Domain Path: /languages
 */

if (!defined('ABSPATH')) exit;

define('WM_MESH_VERSION', '0.1.0');
define('WM_MESH_DIR', plugin_dir_path(__FILE__));
define('WM_MESH_URL', plugin_dir_url(__FILE__));

require_once WM_MESH_DIR . 'includes/wm-class-core.php';

function wm_mesh_bootstrap() {
    \WM_Mesh\Core::instance();
}
add_action('plugins_loaded', 'wm_mesh_bootstrap');
