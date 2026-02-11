<?php
use WM_Mesh\Modules;

$modules = Modules::instance();
$installed = $modules->get_installed_modules();
$available = $modules->get_available_modules();
?>

<div class="wrap">
    <h1><?php _e('Mesh Network Plugins', 'wp-mesh'); ?></h1>

    <h2><?php _e('Installed Mesh Modules', 'wp-mesh'); ?></h2>

    <table class="widefat striped">
        <thead>
            <tr>
                <th><?php _e('Module', 'wp-mesh'); ?></th>
                <th><?php _e('Version', 'wp-mesh'); ?></th>
            </tr>
        </thead>
        <tbody>
        <?php foreach ($installed as $path => $plugin): ?>
            <tr>
                <td><?php echo esc_html($plugin['Name']); ?></td>
                <td><?php echo esc_html($plugin['Version']); ?></td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>

    <h2 style="margin-top:40px;"><?php _e('Available Mesh Modules', 'wp-mesh'); ?></h2>

    <table class="widefat striped">
        <thead>
            <tr>
                <th><?php _e('Module', 'wp-mesh'); ?></th>
                <th><?php _e('Description', 'wp-mesh'); ?></th>
                <th><?php _e('Version', 'wp-mesh'); ?></th>
            </tr>
        </thead>
        <tbody>
        <?php foreach ($available as $module): ?>
            <tr>
                <td><?php echo esc_html($module['name']); ?></td>
                <td><?php echo esc_html($module['description']); ?></td>
                <td><?php echo esc_html($module['version']); ?></td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
</div>
