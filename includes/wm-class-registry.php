<?php
namespace WM_Mesh;

if (!defined('ABSPATH')) exit;

/**
 * Registry of federated Mesh nodes.
 * Handles auto-discovery, updates, and offline detection.
 */
class Registry {

    private static $instance = null;
    private $option_key = 'wm_mesh_registry';

    public static function instance() {
        if (self::$instance === null) self::$instance = new self();
        return self::$instance;
    }

    private function __construct() {
        add_action('wm_mesh_cron_refresh_nodes', [$this, 'refresh_all_nodes']);

        if (!wp_next_scheduled('wm_mesh_cron_refresh_nodes')) {
            wp_schedule_event(time(), 'hourly', 'wm_mesh_cron_refresh_nodes');
        }
    }

    /**
     * Returns all known Mesh nodes.
     */
    public function get_nodes() {
        return get_option($this->option_key, []);
    }

    /**
     * Saves the registry.
     */
    public function save_nodes($nodes) {
        update_option($this->option_key, $nodes);
    }

    /**
     * Adds or updates a node entry.
     */
    public function add_or_update_node($url, $capabilities) {
        $nodes = $this->get_nodes();

        $nodes[$url] = [
            'url'         => $url,
            'node_id'     => $capabilities['node_id'] ?? null,
            'version'     => $capabilities['mesh_version'] ?? null,
            'protocol'    => $capabilities['mesh_protocol'] ?? null,
            'timestamp'   => current_time('mysql'),
            'status'      => 'online',
            'capabilities'=> $capabilities
        ];

        $this->save_nodes($nodes);
    }

    /**
     * Marks a node as offline.
     */
    public function mark_offline($url) {
        $nodes = $this->get_nodes();
        if (isset($nodes[$url])) {
            $nodes[$url]['status'] = 'offline';
            $this->save_nodes($nodes);
        }
    }

    /**
     * Refreshes all nodes by calling their capabilities endpoint.
     */
    public function refresh_all_nodes() {
        $nodes = $this->get_nodes();

        foreach ($nodes as $url => $node) {

            $response = wp_remote_get($url . '/wp-json/wp-mesh/v1/node/capabilities');

            if (is_wp_error($response)) {
                $this->mark_offline($url);
                continue;
            }

            $data = json_decode(wp_remote_retrieve_body($response), true);

            if (!$data) {
                $this->mark_offline($url);
                continue;
            }

            $this->add_or_update_node($url, $data);
        }
    }
}
