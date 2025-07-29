<?php
/**
 * Products List View
 *
 * @package JECO_MainProca
 * @since 1.0.0
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}
?>

<style>
/* WordPress-style Products List */
.jeco-products-meta {
    margin: 20px 0;
    padding: 12px 20px;
    background: #f9f9f9;
    border: 1px solid #e5e5e5;
    border-radius: 3px;
    font-size: 13px;
    color: #666;
}

.jeco-empty-state {
    text-align: center;
    padding: 40px 20px;
    background: #fff;
    border: 1px solid #e5e5e5;
    border-radius: 3px;
    margin-top: 20px;
}

.jeco-empty-state .dashicons {
    font-size: 48px;
    color: #c3c4c7;
    margin-bottom: 20px;
}

.jeco-empty-state h3 {
    font-size: 18px;
    margin: 0 0 10px;
    color: #1d2327;
}

.jeco-empty-state p {
    color: #646970;
    margin: 0 0 20px;
    font-size: 14px;
}

.jeco-empty-state .button-primary {
    font-size: 14px;
    padding: 8px 16px;
}

/* WordPress-style table */
.wp-list-table.jeco-products {
    margin-top: 20px;
}

.wp-list-table.jeco-products th {
    font-weight: 600;
}

.wp-list-table.jeco-products .column-name {
    width: 30%;
}

.wp-list-table.jeco-products .column-quantity {
    width: 15%;
}

.wp-list-table.jeco-products .column-price {
    width: 15%;
}

.wp-list-table.jeco-products .column-date {
    width: 20%;
}

.wp-list-table.jeco-products .column-actions {
    width: 20%;
}

.jeco-product-title {
    font-weight: 600;
    color: #1d2327;
    text-decoration: none;
}

.jeco-product-title:hover {
    color: #135e96;
}

.jeco-product-description {
    color: #646970;
    font-size: 13px;
    margin-top: 4px;
    line-height: 1.4;
}

.jeco-quantity-badge {
    display: inline-block;
    padding: 3px 8px;
    border-radius: 3px;
    font-size: 11px;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.jeco-quantity-in-stock {
    background: #00a32a;
    color: #fff;
}

.jeco-quantity-out-of-stock {
    background: #d63638;
    color: #fff;
}

.jeco-quantity-low-stock {
    background: #dba617;
    color: #fff;
}

.jeco-price {
    font-weight: 600;
    color: #1d2327;
}

.jeco-date {
    color: #646970;
    font-size: 13px;
}

.jeco-row-actions {
    color: #646970;
    font-size: 13px;
}

.jeco-row-actions a {
    color: #2271b1;
    text-decoration: none;
}

.jeco-row-actions a:hover {
    color: #135e96;
}

.jeco-row-actions .delete a {
    color: #d63638;
}

.jeco-row-actions .delete a:hover {
    color: #b32d2e;
}

.jeco-row-actions span {
    color: #c3c4c7;
}

/* Status indicators */
.jeco-status-indicator {
    display: inline-block;
    width: 8px;
    height: 8px;
    border-radius: 50%;
    margin-right: 6px;
}

.jeco-status-active {
    background: #00a32a;
}

.jeco-status-inactive {
    background: #d63638;
}

.jeco-status-warning {
    background: #dba617;
}
</style>

<div class="wrap">
    <h1 class="wp-heading-inline"><?php _e('Products', 'jeco-mainproca'); ?></h1>
    <a href="<?php echo esc_url(add_query_arg('action', 'add')); ?>" class="page-title-action">
        <?php _e('Add New Product', 'jeco-mainproca'); ?>
    </a>
    <hr class="wp-header-end">

    <?php
    // Display messages
    if (isset($_GET['message'])) {
        $message = sanitize_text_field($_GET['message']);
        switch ($message) {
            case 'saved':
                echo '<div class="notice notice-success is-dismissible"><p>' . __('Product saved successfully.', 'jeco-mainproca') . '</p></div>';
                break;
            case 'deleted':
                echo '<div class="notice notice-success is-dismissible"><p>' . __('Product deleted successfully.', 'jeco-mainproca') . '</p></div>';
                break;
            case 'name_required':
                echo '<div class="notice notice-error is-dismissible"><p>' . __('Product name is required. Please enter a product name.', 'jeco-mainproca') . '</p></div>';
                break;
            case 'save_error':
                echo '<div class="notice notice-error is-dismissible"><p>' . __('Failed to save product. Please check that the database table exists and try again.', 'jeco-mainproca') . '</p></div>';
                break;
            case 'error':
                echo '<div class="notice notice-error is-dismissible"><p>' . __('An error occurred. Please try again.', 'jeco-mainproca') . '</p></div>';
                break;
        }
    }
    ?>

    <div class="jeco-products-meta">
        <?php printf(__('Total products: %d', 'jeco-mainproca'), $total_count); ?>
    </div>

    <?php if (empty($products)): ?>
        <div class="jeco-empty-state">
            <span class="dashicons dashicons-products"></span>
            <h3><?php _e('No products found', 'jeco-mainproca'); ?></h3>
            <p><?php _e('Get started by adding your first product to manage your inventory.', 'jeco-mainproca'); ?></p>
            <a href="<?php echo esc_url(add_query_arg('action', 'add')); ?>" class="button button-primary">
                <?php _e('Add First Product', 'jeco-mainproca'); ?>
            </a>
        </div>
    <?php else: ?>
        <table class="wp-list-table widefat fixed striped jeco-products">
            <thead>
                <tr>
                    <th scope="col" class="manage-column column-name column-primary">
                        <?php _e('Product Name', 'jeco-mainproca'); ?>
                    </th>
                    <th scope="col" class="manage-column column-quantity">
                        <?php _e('Quantity', 'jeco-mainproca'); ?>
                    </th>
                    <th scope="col" class="manage-column column-price">
                        <?php _e('Price', 'jeco-mainproca'); ?>
                    </th>
                    <th scope="col" class="manage-column column-date">
                        <?php _e('Date Created', 'jeco-mainproca'); ?>
                    </th>
                    <th scope="col" class="manage-column column-actions">
                        <?php _e('Actions', 'jeco-mainproca'); ?>
                    </th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($products as $product): ?>
                    <tr>
                        <td class="column-name column-primary" data-colname="<?php _e('Product Name', 'jeco-mainproca'); ?>">
                            <strong>
                                <a href="<?php echo esc_url(add_query_arg(array('action' => 'edit', 'id' => $product->id))); ?>" class="jeco-product-title">
                                    <?php echo esc_html($product->name); ?>
                                </a>
                            </strong>
                            <?php if (!empty($product->description)): ?>
                                <div class="jeco-product-description">
                                    <?php echo esc_html(wp_trim_words($product->description, 15)); ?>
                                </div>
                            <?php endif; ?>
                            <div class="row-actions">
                                <span class="edit">
                                    <a href="<?php echo esc_url(add_query_arg(array('action' => 'edit', 'id' => $product->id))); ?>">
                                        <?php _e('Edit', 'jeco-mainproca'); ?>
                                    </a> |
                                </span>
                                <span class="delete">
                                    <a href="<?php echo esc_url(wp_nonce_url(admin_url('admin-post.php?action=jeco_delete_product&id=' . $product->id), 'jeco_delete_product_' . $product->id)); ?>" 
                                       onclick="return confirm('<?php _e('Are you sure you want to delete this product?', 'jeco-mainproca'); ?>')">
                                        <?php _e('Delete', 'jeco-mainproca'); ?>
                                    </a>
                                </span>
                            </div>
                            <button type="button" class="toggle-row"><span class="screen-reader-text"><?php _e('Show more details', 'jeco-mainproca'); ?></span></button>
                        </td>
                        <td class="column-quantity" data-colname="<?php _e('Quantity', 'jeco-mainproca'); ?>">
                            <?php 
                            $quantity_class = 'jeco-quantity-in-stock';
                            if ($product->quantity <= 0) {
                                $quantity_class = 'jeco-quantity-out-of-stock';
                            } elseif ($product->quantity <= 10) {
                                $quantity_class = 'jeco-quantity-low-stock';
                            }
                            ?>
                            <span class="jeco-quantity-badge <?php echo $quantity_class; ?>">
                                <?php echo number_format($product->quantity, 2); ?>
                            </span>
                        </td>
                        <td class="column-price" data-colname="<?php _e('Price', 'jeco-mainproca'); ?>">
                            <span class="jeco-price">$<?php echo number_format($product->price, 2); ?></span>
                        </td>
                        <td class="column-date" data-colname="<?php _e('Date Created', 'jeco-mainproca'); ?>">
                            <span class="jeco-date">
                                <?php echo date_i18n(get_option('date_format'), strtotime($product->created_at)); ?>
                            </span>
                        </td>
                        <td class="column-actions" data-colname="<?php _e('Actions', 'jeco-mainproca'); ?>">
                            <div class="jeco-row-actions">
                                <a href="<?php echo esc_url(add_query_arg(array('action' => 'edit', 'id' => $product->id))); ?>">
                                    <?php _e('Edit', 'jeco-mainproca'); ?>
                                </a>
                                <span> | </span>
                                <span class="delete">
                                    <a href="<?php echo esc_url(wp_nonce_url(admin_url('admin-post.php?action=jeco_delete_product&id=' . $product->id), 'jeco_delete_product_' . $product->id)); ?>" 
                                       onclick="return confirm('<?php _e('Are you sure you want to delete this product?', 'jeco-mainproca'); ?>')">
                                        <?php _e('Delete', 'jeco-mainproca'); ?>
                                    </a>
                                </span>
                            </div>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>
</div>