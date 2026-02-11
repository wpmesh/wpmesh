<?php
namespace WM_Mesh;

if (!defined('ABSPATH')) exit;

/**
 * Simple logger for Mesh events and updates.
 */
class Logger {

    public static function log($message) {
        $file = WM_MESH_DIR . 'cron/wm-update-log.txt';
        $line = "[" . current_time('mysql') . "] " . $message . "\n";
        file_put_contents($file, $line, FILE_APPEND);
    }
}
