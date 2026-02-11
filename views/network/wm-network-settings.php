<?php
use WM_Mesh\Roles;
use WM_Mesh\Core;

if (!defined('ABSPATH')) exit;

if (!Roles::is_global_admin()) {
    echo '<div class="notice notice-error"><p>' .
        esc_html__('You do not have permission to access Mesh settings.', 'wp-mesh') .
        '</p></div>';
    return;
}

$node_id = Core::get_node_id();
?>

<div class="wrap">
    <h1><?php _e('Mesh Settings', 'wp-mesh'); ?></h1>

    <h2><?php _e('Node Information', 'wp-mesh'); ?></h2>
    <table class="widefat striped">
        <tbody>
            <tr>
                <th><?php _e('Node ID', 'wp-mesh'); ?></th>
                <td><?php echo esc_html($node_id); ?></td>
            </tr>
            <tr>
                <th><?php _e('Mesh Version', 'wp-mesh'); ?></th>
                <td><?php echo esc_html(WM_MESH_VERSION); ?></td>
            </tr>
        </tbody>
    </table>

    <h2 style="margin-top:40px;"><?php _e('Global Admin Tools', 'wp-mesh'); ?></h2>

    <p><?php _e('As a Mesh Global Admin, you can manage nodes, users, roles, and federation settings.', 'wp-mesh'); ?></p>

    <ul>
        <li>✔ <?php _e('View and refresh all federated nodes', 'wp-mesh'); ?></li>
        <li>✔ <?php _e('View all federated users across nodes', 'wp-mesh'); ?></li>
        <li>✔ <?php _e('Manage Mesh roles and permissions', 'wp-mesh'); ?></li>
        <li>✔ <?php _e('Trigger manual node registry refresh', 'wp-mesh'); ?></li>
        <li>✔ <?php _e('Trigger Mesh modules manifest update', 'wp-mesh'); ?></li>
    </ul>
</div>
