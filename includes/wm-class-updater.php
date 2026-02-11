<?php
namespace WM_Mesh;

if (!defined('ABSPATH')) exit;

/**
 * Handles automatic updates of WP Mesh and Mesh Modules from GitHub.
 */
class Updater {

    private static $instance = null;
    private $repo_core = 'https://api.github.com/repos/wpmesh/wp-mesh/releases/latest';
    private $repo_modules = 'https://api.github.com/repos/wpmesh/mesh-modules/contents/manifest.json';

    public static function instance() {
        if (self::$instance === null) self::$instance = new self();
        return self::$instance;
    }

    private function __construct() {
        add_action('wm_mesh_cron_update', [$this, 'run_update']);

        if (!wp_next_scheduled('wm_mesh_cron_update')) {
            wp_schedule_event(time(), 'twicedaily', 'wm_mesh_cron_update');
        }
    }

    /**
     * Main update routine.
     */
    public function run_update() {

        Logger::log('Starting Mesh update check.');

        $this->update_core();
        $this->update_modules();

        Logger::log('Mesh update check completed.');
    }

    /**
     * Checks GitHub for a new WP Mesh release.
     */
    private function update_core() {

        $response = wp_remote_get($this->repo_core, [
            'headers' => ['User-Agent' => 'wpmesh-updater']
        ]);

        if (is_wp_error($response)) {
            Logger::log('Core update failed: GitHub unreachable.');
            return;
        }

        $data = json_decode(wp_remote_retrieve_body($response), true);
        if (!$data || !isset($data['tag_name'])) {
            Logger::log('Core update failed: invalid GitHub response.');
            return;
        }

        $latest = $data['tag_name'];

        if (version_compare($latest, WM_MESH_VERSION, '<=')) {
            Logger::log("Core is up to date (version $latest).");
            return;
        }

        Logger::log("New Mesh Core version available: $latest");

        // Download ZIP
        $zip_url = $data['zipball_url'];
        $zip = download_url($zip_url);

        if (is_wp_error($zip)) {
            Logger::log('Core update failed: cannot download ZIP.');
            return;
        }

        // Install ZIP
        $result = unzip_file($zip, WP_PLUGIN_DIR);
        @unlink($zip);

        if (is_wp_error($result)) {
            Logger::log('Core update failed: cannot unzip.');
            return;
        }

        Logger::log("Core updated to version $latest.");
    }

    /**
     * Updates Mesh Modules manifest.
     */
    private function update_modules() {

        $response = wp_remote_get($this->repo_modules, [
            'headers' => ['User-Agent' => 'wpmesh-updater']
        ]);

        if (is_wp_error($response)) {
            Logger::log('Module update failed: GitHub unreachable.');
            return;
        }

        $data = json_decode(wp_remote_retrieve_body($response), true);
        if (!$data || !isset($data['download_url'])) {
            Logger::log('Module update failed: invalid manifest response.');
            return;
        }

        $manifest_url = $data['download_url'];
        $manifest = wp_remote_get($manifest_url);

        if (is_wp_error($manifest)) {
            Logger::log('Module update failed: cannot download manifest.');
            return;
        }

        $json = wp_remote_retrieve_body($manifest);

        file_put_contents(WM_MESH_DIR . 'manifest/wm-modules.json', $json);

        Logger::log('Mesh modules manifest updated.');
    }
}
