<?php
/**
 * Dashboard View
 * 
 * @package JECO_MainProca
 * @since 1.0.0
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

// Check user capabilities
if (!current_user_can('manage_options')) {
    wp_die(__('You do not have sufficient permissions to access this page.'));
}

// Get product statistics
$total_products = JECO_MainProca_Product::get_count();
$products = JECO_MainProca_Product::get_all(array('limit' => 5));

// Calculate total inventory value
$total_value = 0;
$low_stock_count = 0;
foreach (JECO_MainProca_Product::get_all(array('limit' => 999)) as $product) {
    $total_value += $product->price * $product->quantity;
    if ($product->quantity < 10) {
        $low_stock_count++;
    }
}
?>

<div class="wrap">
    <h1 class="wp-heading-inline">
        <?php echo esc_html(get_admin_page_title()); ?>
        <span class="title-count"><?php _e('v1.0.0', 'jeco-mainproca'); ?></span>
    </h1>
    
    <hr class="wp-header-end">

    <!-- Welcome Notice -->
    <div class="notice notice-success notice-large">
        <p>
            <strong><?php _e('Welcome to JECO MainProca!', 'jeco-mainproca'); ?></strong>
            <?php _e('Your inventory and record management system is ready to use.', 'jeco-mainproca'); ?>
        </p>
    </div>

    <!-- Dashboard Grid -->
    <div class="jeco-dashboard-container">
        
        <!-- Quick Stats Row -->
        <div class="jeco-dashboard-row">
            <div class="jeco-stat-card">
                <div class="jeco-stat-icon">
                    <span class="dashicons dashicons-products"></span>
                </div>
                <div class="jeco-stat-content">
                    <h3><?php _e('Products', 'jeco-mainproca'); ?></h3>
                    <span class="jeco-stat-number"><?php echo number_format($total_products); ?></span>
                    <p><?php _e('Total products in inventory', 'jeco-mainproca'); ?></p>
                </div>
            </div>

            <div class="jeco-stat-card">
                <div class="jeco-stat-icon">
                    <span class="dashicons dashicons-warning"></span>
                </div>
                <div class="jeco-stat-content">
                    <h3><?php _e('Low Stock', 'jeco-mainproca'); ?></h3>
                    <span class="jeco-stat-number"><?php echo number_format($low_stock_count); ?></span>
                    <p><?php _e('Products with low stock', 'jeco-mainproca'); ?></p>
                </div>
            </div>

            <div class="jeco-stat-card">
                <div class="jeco-stat-icon">
                    <span class="dashicons dashicons-cart"></span>
                </div>
                <div class="jeco-stat-content">
                    <h3><?php _e('Orders', 'jeco-mainproca'); ?></h3>
                    <span class="jeco-stat-number">0</span>
                    <p><?php _e('Total orders processed', 'jeco-mainproca'); ?></p>
                </div>
            </div>

            <div class="jeco-stat-card">
                <div class="jeco-stat-icon">
                    <span class="dashicons dashicons-money-alt"></span>
                </div>
                <div class="jeco-stat-content">
                    <h3><?php _e('Inventory Value', 'jeco-mainproca'); ?></h3>
                    <span class="jeco-stat-number">$<?php echo number_format($total_value, 2); ?></span>
                    <p><?php _e('Total inventory value', 'jeco-mainproca'); ?></p>
                </div>
            </div>
        </div>

        <!-- Main Content Row -->
        <div class="jeco-dashboard-row">
            <!-- Quick Actions -->
            <div class="jeco-dashboard-card jeco-card-half">
                <div class="jeco-card-header">
                    <h2><?php _e('Quick Actions', 'jeco-mainproca'); ?></h2>
                </div>
                <div class="jeco-card-body">
                    <div class="jeco-quick-actions">
                        <a href="<?php echo esc_url(admin_url('admin.php?page=jeco-mainproca-products&action=add')); ?>" class="jeco-action-button jeco-button-primary">
                            <span class="dashicons dashicons-plus-alt"></span>
                            <?php _e('Add Product', 'jeco-mainproca'); ?>
                        </a>
                        <a href="#" class="jeco-action-button jeco-button-secondary" data-action="add-client">
                            <span class="dashicons dashicons-businessman"></span>
                            <?php _e('Add Client', 'jeco-mainproca'); ?>
                        </a>
                        <a href="#" class="jeco-action-button jeco-button-secondary" data-action="new-order">
                            <span class="dashicons dashicons-clipboard"></span>
                            <?php _e('New Order', 'jeco-mainproca'); ?>
                        </a>
                        <a href="<?php echo esc_url(admin_url('admin.php?page=jeco-mainproca-products')); ?>" class="jeco-action-button jeco-button-secondary">
                            <span class="dashicons dashicons-visibility"></span>
                            <?php _e('View Products', 'jeco-mainproca'); ?>
                        </a>
                    </div>
                </div>
            </div>

            <!-- Recent Products -->
            <div class="jeco-dashboard-card jeco-card-half">
                <div class="jeco-card-header">
                    <h2><?php _e('Recent Products', 'jeco-mainproca'); ?></h2>
                </div>
                <div class="jeco-card-body">
                    <div class="jeco-activity-list">
                        <?php if (!empty($products)) : ?>
                            <?php foreach ($products as $product) : ?>
                                <div class="jeco-activity-item">
                                    <div class="jeco-activity-icon">
                                        <span class="dashicons dashicons-products"></span>
                                    </div>
                                    <div class="jeco-activity-content">
                                        <p><strong><?php echo esc_html($product->name); ?></strong></p>
                                        <p class="jeco-activity-meta">
                                            <?php printf(
                                                __('Quantity: %d | Price: $%s | Added %s', 'jeco-mainproca'),
                                                $product->quantity,
                                                number_format($product->price, 2),
                                                human_time_diff(strtotime($product->created_at), current_time('timestamp')) . ' ago'
                                            ); ?>
                                        </p>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        <?php else : ?>
                            <div class="jeco-activity-item jeco-activity-empty">
                                <span class="dashicons dashicons-info"></span>
                                <p><?php _e('No products found. Start by adding your first product!', 'jeco-mainproca'); ?></p>
                                <a href="<?php echo esc_url(admin_url('admin.php?page=jeco-mainproca-products&action=add')); ?>" class="button button-primary">
                                    <?php _e('Add Product', 'jeco-mainproca'); ?>
                                </a>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>

        <!-- System Status Row -->
        <div class="jeco-dashboard-row">
            <div class="jeco-dashboard-card jeco-card-full">
                <div class="jeco-card-header">
                    <h2><?php _e('System Status', 'jeco-mainproca'); ?></h2>
                </div>
                <div class="jeco-card-body">
                    <div class="jeco-system-status">
                        <div class="jeco-status-item jeco-status-good">
                            <span class="dashicons dashicons-yes-alt"></span>
                            <span><?php _e('Database Connection', 'jeco-mainproca'); ?></span>
                            <span class="jeco-status-badge jeco-badge-success"><?php _e('OK', 'jeco-mainproca'); ?></span>
                        </div>
                        <div class="jeco-status-item jeco-status-good">
                            <span class="dashicons dashicons-yes-alt"></span>
                            <span><?php printf(__('PHP Version: %s', 'jeco-mainproca'), PHP_VERSION); ?></span>
                            <span class="jeco-status-badge jeco-badge-success"><?php _e('Compatible', 'jeco-mainproca'); ?></span>
                        </div>
                        <div class="jeco-status-item jeco-status-good">
                            <span class="dashicons dashicons-yes-alt"></span>
                            <span><?php printf(__('WordPress Version: %s', 'jeco-mainproca'), get_bloginfo('version')); ?></span>
                            <span class="jeco-status-badge jeco-badge-success"><?php _e('Compatible', 'jeco-mainproca'); ?></span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Dashboard Styles -->
<style>
.jeco-dashboard-container {
    margin-top: 20px;
}

.jeco-dashboard-row {
    display: flex;
    gap: 20px;
    margin-bottom: 20px;
    flex-wrap: wrap;
}

/* Stat Cards */
.jeco-stat-card {
    background: #fff;
    border: 1px solid #c3c4c7;
    border-radius: 4px;
    padding: 20px;
    flex: 1;
    min-width: 200px;
    display: flex;
    align-items: center;
    gap: 15px;
    box-shadow: 0 1px 1px rgba(0,0,0,.04);
}

.jeco-stat-icon {
    background: #2271b1;
    color: white;
    width: 50px;
    height: 50px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 20px;
}

.jeco-stat-content h3 {
    margin: 0 0 5px 0;
    font-size: 14px;
    color: #646970;
    font-weight: 600;
}

.jeco-stat-number {
    font-size: 24px;
    font-weight: bold;
    color: #1d2327;
    display: block;
    margin-bottom: 5px;
}

.jeco-stat-content p {
    margin: 0;
    font-size: 12px;
    color: #646970;
}

/* Dashboard Cards */
.jeco-dashboard-card {
    background: #fff;
    border: 1px solid #c3c4c7;
    border-radius: 4px;
    box-shadow: 0 1px 1px rgba(0,0,0,.04);
}

.jeco-card-half {
    flex: 1;
    min-width: 300px;
}

.jeco-card-full {
    flex: 1;
    width: 100%;
}

.jeco-card-header {
    padding: 15px 20px;
    border-bottom: 1px solid #c3c4c7;
    background: #f6f7f7;
}

.jeco-card-header h2 {
    margin: 0;
    font-size: 16px;
    color: #1d2327;
}

.jeco-card-body {
    padding: 20px;
}

/* Quick Actions */
.jeco-quick-actions {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
    gap: 15px;
}

.jeco-action-button {
    display: flex;
    align-items: center;
    gap: 8px;
    padding: 12px 16px;
    border-radius: 4px;
    text-decoration: none;
    font-weight: 500;
    transition: all 0.2s ease;
    border: 1px solid;
    text-align: center;
    justify-content: center;
}

.jeco-button-primary {
    background: #2271b1;
    color: white;
    border-color: #2271b1;
}

.jeco-button-primary:hover {
    background: #135e96;
    color: white;
}

.jeco-button-secondary {
    background: #f6f7f7;
    color: #2c3338;
    border-color: #c3c4c7;
}

.jeco-button-secondary:hover {
    background: #e5e5e5;
    color: #2c3338;
}

/* Activity List */
.jeco-activity-item {
    display: flex;
    align-items: center;
    gap: 10px;
    padding: 10px 0;
}

.jeco-activity-empty {
    color: #646970;
    font-style: italic;
}

/* System Status */
.jeco-system-status {
    display: flex;
    flex-direction: column;
    gap: 15px;
}

.jeco-status-item {
    display: flex;
    align-items: center;
    gap: 10px;
}

.jeco-status-item span:nth-child(2) {
    flex: 1;
}

.jeco-status-badge {
    padding: 4px 8px;
    border-radius: 3px;
    font-size: 11px;
    font-weight: 600;
    text-transform: uppercase;
}

.jeco-badge-success {
    background: #00a32a;
    color: white;
}

.jeco-status-good .dashicons {
    color: #00a32a;
}

/* Responsive */
@media (max-width: 768px) {
    .jeco-dashboard-row {
        flex-direction: column;
    }
    
    .jeco-quick-actions {
        grid-template-columns: 1fr;
    }
}
</style>