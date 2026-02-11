<?php
use WM_Mesh\Registry;

$nodes = Registry::instance()->get_nodes();
?>

<div class="wrap">
    <h1><?php _e('Mesh Network Sites', 'wp-mesh'); ?></h1>

    <table class="widefat striped">
        <thead>
            <tr>
                <th><?php _e('URL', 'wp-mesh'); ?></th>
                <th><?php _e('Node ID', 'wp-mesh'); ?></th>
                <th><?php _e('Version', 'wp-mesh'); ?></th>
                <th><?php _e('Protocol', 'wp-mesh'); ?></th>
                <th><?php _e('Status', 'wp-mesh'); ?></th>
                <th><?php _e('Last Update', 'wp-mesh'); ?></th>
            </tr>
        </thead>

        <tbody>
        <?php foreach ($nodes as $node): ?>
            <tr>
                <td><?php echo esc_html($node['url']); ?></td>
                <td><?php echo esc_html($node['node_id']); ?></td>
                <td><?php echo esc_html($node['version']); ?></td>
                <td><?php echo esc_html($node['protocol']); ?></td>
                <td>
                    <?php if ($node['status'] === 'online'): ?>
                        <span style="color:green;font-weight:bold"><?php _e('Online', 'wp-mesh'); ?></span>
                    <?php else: ?>
                        <span style="color:red;font-weight:bold"><?php _e('Offline', 'wp-mesh'); ?></span>
                    <?php endif; ?>
                </td>
                <td><?php echo esc_html($node['timestamp']); ?></td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
</div>
