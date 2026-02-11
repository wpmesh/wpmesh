<?php
namespace WM_Mesh;

if (!defined('ABSPATH')) exit;

/**
 * Handles Mesh Modules: installed, available, active.
 */
class Modules {

    private static $instance = null;
    private $manifest_file;

    public static function instance() {
        if (self::$instance === null) self::$instance = new self();
        return self::$instance;
    }

    private function __construct() {
        $this->manifest_file = WM_MESH_DIR . 'manifest/wm-modules.json';
    }

    /**
     * Returns the module manifest from local cache.
     */
    public function get_manifest() {
        if (!file_exists($this->manifest_file)) return [];

        $json = file_get_contents($this->manifest_file);
        $data = json_decode($json, true);

        return is_array($data) ? $data : [];
    }

    /**
     * Returns installed WordPress plugins that are Mesh Modules.
     */
    public function get_installed_modules() {
        $plugins = get_plugins();
        $installed = [];

        foreach ($plugins as $path => $plugin) {
            if (strpos($plugin['Name'], 'Mesh Module') !== false) {
                $installed[$path] = $plugin;
            }
        }

        return $installed;
    }

    /**
     * Returns available modules (manifest - installed).
     */
    public function get_available_modules() {
        $manifest = $this->get_manifest();
        $installed = $this->get_installed_modules();

        $available = [];

        foreach ($manifest as $module) {
            $slug = $module['slug'];

            $is_installed = false;
            foreach ($installed as $path => $plugin) {
                if (strpos($path, $slug) !== false) {
                    $is_installed = true;
                    break;
                }
            }

            if (!$is_installed) {
                $available[] = $module;
            }
        }

        return $available;
    }
}
