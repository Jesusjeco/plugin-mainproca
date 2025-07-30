<?php
/**
 * Product Form View (Add/Edit)
 *
 * @package JECO_MainProca
 * @since 1.0.0
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

$is_edit = !empty($product->id);
$page_title = $is_edit ? __('Edit Product', 'jeco-mainproca') : __('Add New Product', 'jeco-mainproca');
?>

<style>
/* WordPress Native Form Styling - Minimal Custom Overrides */
.jeco-form-container {
    max-width: 100%;
    margin: 0;
}

.jeco-form-card {
    background: #fff;
    border: 1px solid #c3c4c7;
    border-radius: 3px;
    box-shadow: 0 1px 1px rgba(0, 0, 0, 0.04);
    margin-top: 20px;
}

.jeco-form-header {
    background: #f6f7f7;
    border-bottom: 1px solid #c3c4c7;
    padding: 12px 20px;
}

.jeco-form-title {
    font-size: 14px;
    font-weight: 600;
    color: #1d2327;
    margin: 0;
    line-height: 1.4;
}

.jeco-form-subtitle {
    color: #646970;
    font-size: 13px;
    margin: 4px 0 0 0;
    font-weight: 400;
}

.jeco-form-body {
    padding: 20px;
}

.required {
    color: #d63638;
    margin-left: 2px;
}

.jeco-price-input {
    position: relative;
}

.jeco-price-symbol {
    position: absolute;
    left: 8px;
    top: 50%;
    transform: translateY(-50%);
    color: #646970;
    font-weight: 400;
    font-size: 14px;
    pointer-events: none;
    z-index: 1;
}

@media (max-width: 782px) {
    .jeco-form-header,
    .jeco-form-body {
        padding: 15px;
    }
}
</style>

<div class="wrap">
    <h1 class="wp-heading-inline"><?php echo esc_html($page_title); ?></h1>
    <a href="<?php echo esc_url(admin_url('admin.php?page=jeco-mainproca-products')); ?>" class="page-title-action">
        <?php _e('← Back to Products', 'jeco-mainproca'); ?>
    </a>
    <hr class="wp-header-end">

    <?php
    // Display messages using WordPress native notices
    if (isset($_GET['message'])) {
        $message = sanitize_text_field($_GET['message']);
        switch ($message) {
            case 'name_required':
                echo '<div class="notice notice-error"><p><strong>' . __('Error:', 'jeco-mainproca') . '</strong> ' . __('Product name is required. Please enter a product name.', 'jeco-mainproca') . '</p></div>';
                break;
            case 'duplicate_name':
                echo '<div class="notice notice-error"><p><strong>' . __('Error:', 'jeco-mainproca') . '</strong> ' . __('A product with this name already exists. Please choose a different name.', 'jeco-mainproca') . '</p></div>';
                break;
            case 'save_error':
                echo '<div class="notice notice-error"><p><strong>' . __('Error:', 'jeco-mainproca') . '</strong> ' . __('Failed to save product. Please check that the database table exists and try again.', 'jeco-mainproca') . '</p></div>';
                break;
        }
    }
    ?>

    <div class="jeco-form-container">
        <div class="jeco-form-card">
            <div class="jeco-form-header">
                <h2 class="jeco-form-title">
                    <?php echo esc_html($page_title); ?>
                </h2>
                <p class="jeco-form-subtitle">
                    <?php $is_edit ? _e('Update the product information below', 'jeco-mainproca') : _e('Fill in the product information to create a new product', 'jeco-mainproca'); ?>
                </p>
            </div>

            <form method="post" action="<?php echo esc_url(admin_url('admin-post.php')); ?>" class="jeco-form-body">
                <?php wp_nonce_field('jeco_save_product'); ?>
                <input type="hidden" name="action" value="jeco_save_product">
                <?php if ($is_edit): ?>
                    <input type="hidden" name="product_id" value="<?php echo esc_attr($product->id); ?>">
                <?php endif; ?>

                <table class="form-table" role="presentation">
                    <tbody>
                        <tr>
                            <th scope="row">
                                <label for="name" class="jeco-label">
                                    <?php _e('Product Name', 'jeco-mainproca'); ?> <span class="required">*</span>
                                </label>
                            </th>
                            <td>
                                <input type="text" 
                                       id="name" 
                                       name="name" 
                                       value="<?php echo esc_attr($product->name); ?>" 
                                       required
                                       class="regular-text"
                                       placeholder="<?php _e('Enter a descriptive product name...', 'jeco-mainproca'); ?>">
                                <p class="description">
                                    <?php _e('Choose a clear and descriptive name that customers will easily understand', 'jeco-mainproca'); ?>
                                </p>
                            </td>
                        </tr>
                        
                        <tr>
                            <th scope="row">
                                <label for="quantity" class="jeco-label">
                                    <?php _e('Stock Quantity', 'jeco-mainproca'); ?>
                                </label>
                            </th>
                            <td>
                                <input type="number" 
                                       id="quantity" 
                                       name="quantity" 
                                       value="<?php echo esc_attr($product->quantity); ?>" 
                                       min="0"
                                       step="0.01"
                                       class="small-text"
                                       placeholder="0.00">
                                <p class="description">
                                    <?php _e('Current number of items available in stock (decimals allowed, e.g., 10.50)', 'jeco-mainproca'); ?>
                                </p>
                            </td>
                        </tr>
                        
                        <tr>
                            <th scope="row">
                                <label for="price" class="jeco-label">
                                    <?php _e('Price (USD)', 'jeco-mainproca'); ?>
                                </label>
                            </th>
                            <td>
                                <div class="jeco-price-input">
                                    <span class="jeco-price-symbol">$</span>
                                    <input type="number" 
                                           id="price" 
                                           name="price" 
                                           value="<?php echo esc_attr($product->price); ?>" 
                                           min="0" 
                                           step="0.01"
                                           class="small-text"
                                           style="padding-left: 20px;"
                                           placeholder="0.00">
                                </div>
                                <p class="description">
                                    <?php _e('Set the selling price for this product', 'jeco-mainproca'); ?>
                                </p>
                            </td>
                        </tr>
                        
                        <tr>
                            <th scope="row">
                                <label for="description" class="jeco-label">
                                    <?php _e('Product Description', 'jeco-mainproca'); ?>
                                </label>
                            </th>
                            <td>
                                <textarea id="description" 
                                          name="description" 
                                          class="large-text"
                                          rows="5"
                                          placeholder="<?php _e('Describe the product features, benefits, and any important details...', 'jeco-mainproca'); ?>"><?php echo esc_textarea($product->description); ?></textarea>
                                <p class="description">
                                    <?php _e('Provide a detailed description to help customers understand the product', 'jeco-mainproca'); ?>
                                </p>
                            </td>
                        </tr>
                    </tbody>
                </table>

                <p class="submit">
                    <button type="submit" class="button button-primary">
                        <?php $is_edit ? _e('Update Product', 'jeco-mainproca') : _e('Add Product', 'jeco-mainproca'); ?>
                    </button>
                    <a href="<?php echo esc_url(admin_url('admin.php?page=jeco-mainproca-products')); ?>" 
                       class="button button-secondary">
                        <?php _e('Cancel', 'jeco-mainproca'); ?>
                    </a>
                </p>
            </form>
        </div>
    </div>
</div>