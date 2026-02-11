<?php
use WM_Mesh\Health;
use WM_Mesh\Registry;

$health = Health::get_status();
$nodes  = Registry::instance()->get_nodes();
?>

<div class="wrap">
    <h1><?php _e('Mesh Network – Dashboard', 'wp-mesh'); ?></h1>

    <h2><?php _e('Local Node Status', 'wp-mesh'); ?></h2>
    <ul>
        <li>PHP: <?php echo esc_html($health['php_version']); ?></li>
        <li>WordPress: <?php echo esc_html($health['wordpress']); ?></li>
        <li>Mesh: <?php echo esc_html($health['mesh_version']); ?></li>
        <li><?php _e('Multisite', 'wp-mesh'); ?>: <?php echo $health['multisite'] ? 'Yes' : 'No'; ?></li>
        <li><?php _e('Cron Active', 'wp-mesh'); ?>: <?php echo $health['cron_running'] ? 'Yes' : 'No'; ?></li>
    </ul>

    <h2><?php _e('Federated Nodes', 'wp-mesh'); ?></h2>
    <p><?php _e('Total nodes', 'wp-mesh'); ?>: <strong><?php echo count($nodes); ?></strong></p>
</div>
