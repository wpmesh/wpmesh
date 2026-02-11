<?php
use WM_Mesh\Users;

$current_user = wp_get_current_user();
$is_global_admin = in_array('mesh_global_admin', $current_user->roles);

// Local admin → only local users
if (!$is_global_admin) {

    $local = Users::get_local_users_static();
    ?>

    <div class="wrap">
        <h1><?php _e('Local Users', 'wp-mesh'); ?></h1>

        <table class="widefat striped">
            <thead>
                <tr>
                    <th><?php _e('Name', 'wp-mesh'); ?></th>
                    <th><?php _e('Email', 'wp-mesh'); ?></th>
                    <th><?php _e('Roles', 'wp-mesh'); ?></th>
                </tr>
            </thead>

            <tbody>
            <?php foreach ($local as $u): ?>
                <tr>
                    <td><?php echo esc_html($u['name']); ?></td>
                    <td><?php echo esc_html($u['email']); ?></td>
                    <td><?php echo implode(', ', $u['roles']); ?></td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    <?php
    return;
}

// Global admin → users grouped by node
$nodes = Users::get_users_grouped_by_node();
?>

<div class="wrap">
    <h1><?php _e('Mesh Network Users', 'wp-mesh'); ?></h1>

    <?php foreach ($nodes as $node_url => $node): ?>

        <h2 style="margin-top:40px;">
            <?php echo $node_url === 'local'
                ? __('Local Node', 'wp-mesh')
                : sprintf(__('Node: %s', 'wp-mesh'), esc_html($node_url)); ?>
        </h2>

        <?php if (isset($node['error'])): ?>
            <p style="color:red;"><?php _e('Node offline', 'wp-mesh'); ?></p>
            <?php continue; ?>
        <?php endif; ?>

        <table class="widefat striped">
            <thead>
                <tr>
                    <th><?php _e('Name', 'wp-mesh'); ?></th>
                    <th><?php _e('Email', 'wp-mesh'); ?></th>
                    <th><?php _e('Roles', 'wp-mesh'); ?></th>
                    <th><?php _e('Federated Origin', 'wp-mesh'); ?></th>
                </tr>
            </thead>

            <tbody>
            <?php foreach ($node['users'] as $u): ?>
                <tr>
                    <td><?php echo esc_html($u['name']); ?></td>
                    <td><?php echo esc_html($u['email']); ?></td>
                    <td><?php echo implode(', ', $u['roles']); ?></td>
                    <td><?php echo $u['federated'] ? esc_html($u['federated']) : '-'; ?></td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>

    <?php endforeach; ?>
</div>
