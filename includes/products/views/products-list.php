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

.wp-list-table.jeco-products .column-description {
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
/* Simple Search Box Styles */
.jeco-search-box {
    background: #fff;
    border: 1px solid #c3c4c7;
    border-radius: 4px;
    padding: 15px;
    margin: 20px 0;
    box-shadow: 0 1px 1px rgba(0,0,0,.04);
}

.jeco-search-form {
    display: flex;
    align-items: center;
    gap: 10px;
    flex-wrap: wrap;
}

.jeco-search-input {
    padding: 6px 12px;
    border: 1px solid #8c8f94;
    border-radius: 4px;
    font-size: 14px;
    line-height: 1.4;
    background-color: #fff;
    min-width: 300px;
    flex: 1;
}

.jeco-search-input:focus {
    border-color: #2271b1;
    box-shadow: 0 0 0 1px #2271b1;
    outline: none;
}

.jeco-search-form .button {
    margin: 0;
}

/* Responsive design */
@media (max-width: 782px) {
    .jeco-search-form {
        flex-direction: column;
        align-items: stretch;
    }
    
    .jeco-search-input {
        min-width: auto;
        width: 100%;
        margin-bottom: 10px;
    }
    
    .jeco-search-form .button {
        width: 100%;
        margin-bottom: 5px;
    }
}

/* Pagination Styles */
.jeco-pagination-wrapper {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin: 20px 0;
    padding: 15px 0;
    border-top: 1px solid #c3c4c7;
}

.jeco-pagination-info {
    color: #646970;
    font-size: 13px;
    font-weight: 400;
}

.jeco-pagination-nav {
    display: flex;
    align-items: center;
    gap: 5px;
}

.jeco-pagination-link {
    display: inline-flex;
    align-items: center;
    gap: 5px;
    padding: 6px 12px;
    background: #f6f7f7;
    border: 1px solid #c3c4c7;
    border-radius: 3px;
    color: #2271b1;
    text-decoration: none;
    font-size: 13px;
    line-height: 1.4;
    transition: all 0.2s ease;
}

.jeco-pagination-link:hover {
    background: #2271b1;
    color: #fff;
    border-color: #2271b1;
}

.jeco-pagination-link.disabled {
    color: #a7aaad;
    background: #f6f7f7;
    border-color: #dcdcde;
    cursor: default;
    pointer-events: none;
}

.jeco-pagination-link .dashicons {
    font-size: 16px;
    width: 16px;
    height: 16px;
}

.jeco-page-numbers {
    display: flex;
    align-items: center;
    gap: 2px;
    margin: 0 10px;
}

.jeco-page-number {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    min-width: 32px;
    height: 32px;
    padding: 0 8px;
    background: #f6f7f7;
    border: 1px solid #c3c4c7;
    border-radius: 3px;
    color: #2271b1;
    text-decoration: none;
    font-size: 13px;
    line-height: 1;
    transition: all 0.2s ease;
}

.jeco-page-number:hover {
    background: #2271b1;
    color: #fff;
    border-color: #2271b1;
}

.jeco-page-number.current {
    background: #2271b1;
    color: #fff;
    border-color: #2271b1;
    font-weight: 600;
    cursor: default;
}

.jeco-page-dots {
    color: #646970;
    padding: 0 5px;
    font-size: 13px;
}

/* Responsive pagination */
@media (max-width: 782px) {
    .jeco-pagination-wrapper {
        flex-direction: column;
        gap: 15px;
        align-items: center;
    }
    
    .jeco-pagination-info {
        order: 2;
    }
    
    .jeco-pagination-nav {
        order: 1;
        flex-wrap: wrap;
        justify-content: center;
    }
    
    .jeco-page-numbers {
        margin: 0 5px;
    }
    
    .jeco-pagination-link {
        padding: 8px 12px;
        font-size: 14px;
    }
    
    .jeco-page-number {
        min-width: 36px;
        height: 36px;
        font-size: 14px;
    }
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
            case 'duplicate_name':
                echo '<div class="notice notice-error is-dismissible"><p>' . __('A product with this name already exists. Please choose a different name.', 'jeco-mainproca') . '</p></div>';
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

    <!-- Simple Search Bar -->
    <div class="jeco-search-box">
        <form method="get" action="" class="jeco-search-form">
            <input type="hidden" name="page" value="jeco-mainproca-products">
            <input type="search" 
                   name="s" 
                   value="<?php echo esc_attr(isset($_GET['s']) ? $_GET['s'] : ''); ?>" 
                   placeholder="<?php _e('Search products by name...', 'jeco-mainproca'); ?>"
                   class="jeco-search-input">
            <input type="submit" value="<?php _e('Search', 'jeco-mainproca'); ?>" class="button button-primary">
            <?php if (!empty($_GET['s'])): ?>
                <a href="<?php echo esc_url(admin_url('admin.php?page=jeco-mainproca-products')); ?>" class="button">
                    <?php _e('Clear', 'jeco-mainproca'); ?>
                </a>
            <?php endif; ?>
        </form>
    </div>

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
                    <th scope="col" class="manage-column column-description">
                        <?php _e('Description', 'jeco-mainproca'); ?>
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
                        
                        <td class="column-description" data-colname="<?php _e('Description', 'jeco-mainproca'); ?>">
                            
                            <?php if (!empty($product->description)): ?>
                                <div class="jeco-product-description">
                                    <?php echo esc_html(wp_trim_words($product->description, 15)); ?>
                                </div>
                            <?php endif; ?>
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

        <?php if ($pagination['total_pages'] > 1): ?>
            <div class="jeco-pagination-wrapper">
                <div class="jeco-pagination-info">
                    <?php
                    $start = (($pagination['current_page'] - 1) * $pagination['per_page']) + 1;
                    $end = min($pagination['current_page'] * $pagination['per_page'], $pagination['total_items']);
                    printf(
                        __('Showing %d-%d of %d products', 'jeco-mainproca'),
                        $start,
                        $end,
                        $pagination['total_items']
                    );
                    ?>
                </div>
                
                <div class="jeco-pagination-nav">
                    <?php
                    // Build base URL with current search parameters
                    $base_url = admin_url('admin.php?page=jeco-mainproca-products');
                    $url_params = array();
                    if (!empty($_GET['s'])) {
                        $url_params['s'] = sanitize_text_field($_GET['s']);
                    }
                    
                    // Previous page link
                    if ($pagination['current_page'] > 1):
                        $prev_url = add_query_arg(array_merge($url_params, array('paged' => $pagination['current_page'] - 1)), $base_url);
                    ?>
                        <a href="<?php echo esc_url($prev_url); ?>" class="jeco-pagination-link jeco-prev-page">
                            <span class="dashicons dashicons-arrow-left-alt2"></span>
                            <?php _e('Previous', 'jeco-mainproca'); ?>
                        </a>
                    <?php else: ?>
                        <span class="jeco-pagination-link jeco-prev-page disabled">
                            <span class="dashicons dashicons-arrow-left-alt2"></span>
                            <?php _e('Previous', 'jeco-mainproca'); ?>
                        </span>
                    <?php endif; ?>

                    <div class="jeco-page-numbers">
                        <?php
                        // Calculate page range to show
                        $range = 2; // Show 2 pages before and after current page
                        $start_page = max(1, $pagination['current_page'] - $range);
                        $end_page = min($pagination['total_pages'], $pagination['current_page'] + $range);
                        
                        // Show first page if not in range
                        if ($start_page > 1):
                            $first_url = add_query_arg($url_params, $base_url);
                        ?>
                            <a href="<?php echo esc_url($first_url); ?>" class="jeco-page-number">1</a>
                            <?php if ($start_page > 2): ?>
                                <span class="jeco-page-dots">...</span>
                            <?php endif; ?>
                        <?php endif; ?>

                        <?php for ($i = $start_page; $i <= $end_page; $i++): ?>
                            <?php if ($i == $pagination['current_page']): ?>
                                <span class="jeco-page-number current"><?php echo $i; ?></span>
                            <?php else: ?>
                                <?php 
                                $page_url = ($i == 1) ? 
                                    add_query_arg($url_params, $base_url) : 
                                    add_query_arg(array_merge($url_params, array('paged' => $i)), $base_url);
                                ?>
                                <a href="<?php echo esc_url($page_url); ?>" class="jeco-page-number"><?php echo $i; ?></a>
                            <?php endif; ?>
                        <?php endfor; ?>

                        <?php
                        // Show last page if not in range
                        if ($end_page < $pagination['total_pages']):
                            if ($end_page < $pagination['total_pages'] - 1):
                        ?>
                                <span class="jeco-page-dots">...</span>
                            <?php endif; ?>
                            <?php 
                            $last_url = add_query_arg(array_merge($url_params, array('paged' => $pagination['total_pages'])), $base_url);
                            ?>
                            <a href="<?php echo esc_url($last_url); ?>" class="jeco-page-number"><?php echo $pagination['total_pages']; ?></a>
                        <?php endif; ?>
                    </div>

                    <?php
                    // Next page link
                    if ($pagination['current_page'] < $pagination['total_pages']):
                        $next_url = add_query_arg(array_merge($url_params, array('paged' => $pagination['current_page'] + 1)), $base_url);
                    ?>
                        <a href="<?php echo esc_url($next_url); ?>" class="jeco-pagination-link jeco-next-page">
                            <?php _e('Next', 'jeco-mainproca'); ?>
                            <span class="dashicons dashicons-arrow-right-alt2"></span>
                        </a>
                    <?php else: ?>
                        <span class="jeco-pagination-link jeco-next-page disabled">
                            <?php _e('Next', 'jeco-mainproca'); ?>
                            <span class="dashicons dashicons-arrow-right-alt2"></span>
                        </span>
                    <?php endif; ?>
                </div>
            </div>
        <?php endif; ?>
    <?php endif; ?>
</div>