<?php
namespace WM_Mesh;

if (!defined('ABSPATH')) exit;

/**
 * Mesh Event Bus.
 * Handles handshake and node discovery.
 */
class Events {

    /**
     * Performs a handshake with a remote Mesh node.
     */
    public static function handshake($url) {

        $response = wp_remote_get($url . '/wp-json/wp-mesh/v1/node/capabilities');

        if (is_wp_error($response)) return false;

        $data = json_decode(wp_remote_retrieve_body($response), true);
        if (!$data) return false;

        Registry::instance()->add_or_update_node($url, $data);

        return true;
    }
}
