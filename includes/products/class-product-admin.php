<?php
/**
 * Product Admin Menu Class
 *
 * Handles product-related admin menu functionality.
 *
 * @package JECO_MainProca
 * @since 1.0.0
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Class JECO_MainProca_Product_Admin
 *
 * Manages product admin interface
 */
class JECO_MainProca_Product_Admin {

    /**
     * Initialize the product admin
     *
     * @since 1.0.0
     */
    public function __construct() {
        $this->init();
    }

    /**
     * Initialize hooks
     *
     * @since 1.0.0
     */
    public function init() {
        add_action('admin_menu', [$this, 'add_submenu'], 20);
        add_action('admin_post_jeco_save_product', [$this, 'handle_save_product']);
        add_action('admin_post_jeco_delete_product', [$this, 'handle_delete_product']);
    }

    /**
     * Add products submenu
     *
     * @since 1.0.0
     */
    public function add_submenu() {
        add_submenu_page(
            JECO_MainProca_Admin_Menu::get_main_menu_slug(),    // Parent slug
            __('Products', 'jeco-mainproca'),                   // Page title
            __('Products', 'jeco-mainproca'),                   // Menu title
            'manage_options',                                    // Capability
            'jeco-mainproca-products',                          // Menu slug
            [$this, 'render_products_page']                     // Callback function
        );
    }

    /**
     * Render the products page
     *
     * @since 1.0.0
     */
    public function render_products_page() {
        // Check user capabilities
        if (!current_user_can('manage_options')) {
            wp_die(__('You do not have sufficient permissions to access this page.', 'jeco-mainproca'));
        }

        // Handle different actions
        $action = isset($_GET['action']) ? sanitize_text_field($_GET['action']) : 'list';
        
        switch ($action) {
            case 'add':
                $this->render_add_product_form();
                break;
            case 'edit':
                $this->render_edit_product_form();
                break;
            default:
                $this->render_products_list();
                break;
        }
    }

    /**
     * Render products list
     *
     * @since 1.0.0
     */
    private function render_products_list() {
        $products = JECO_MainProca_Product::get_all();
        $total_count = JECO_MainProca_Product::get_count();
        
        include JECO_MAINPROCA_PLUGIN_PATH . 'includes/products/views/products-list.php';
    }

    /**
     * Render add product form
     *
     * @since 1.0.0
     */
    private function render_add_product_form() {
        $product = new JECO_MainProca_Product();
        include JECO_MAINPROCA_PLUGIN_PATH . 'includes/products/views/product-form.php';
    }

    /**
     * Render edit product form
     *
     * @since 1.0.0
     */
    private function render_edit_product_form() {
        $product_id = isset($_GET['id']) ? (int) $_GET['id'] : 0;
        $product = JECO_MainProca_Product::find($product_id);
        
        if (!$product) {
            wp_die(__('Product not found.', 'jeco-mainproca'));
        }
        
        include JECO_MAINPROCA_PLUGIN_PATH . 'includes/products/views/product-form.php';
    }

    /**
     * Handle save product form submission
     *
     * @since 1.0.0
     */
    public function handle_save_product() {
        // Verify nonce
        if (!wp_verify_nonce($_POST['_wpnonce'], 'jeco_save_product')) {
            wp_die(__('Security check failed.', 'jeco-mainproca'));
        }

        // Check permissions
        if (!current_user_can('manage_options')) {
            wp_die(__('You do not have sufficient permissions.', 'jeco-mainproca'));
        }

        // Validate required fields
        if (empty($_POST['name'])) {
            $redirect_url = add_query_arg(
                array(
                    'page' => 'jeco-mainproca-products', 
                    'action' => isset($_POST['product_id']) ? 'edit' : 'add',
                    'id' => isset($_POST['product_id']) ? (int) $_POST['product_id'] : null,
                    'message' => 'name_required'
                ),
                admin_url('admin.php')
            );
            wp_redirect($redirect_url);
            exit;
        }

        $product_id = isset($_POST['product_id']) ? (int) $_POST['product_id'] : 0;
        
        if ($product_id) {
            $product = JECO_MainProca_Product::find($product_id);
            if (!$product) {
                wp_die(__('Product not found.', 'jeco-mainproca'));
            }
        } else {
            $product = new JECO_MainProca_Product();
        }

        // Fill product data
        $product->fill($_POST);

        // Save product
        $result = $product->save();

        if ($result) {
            $redirect_url = add_query_arg(
                array('page' => 'jeco-mainproca-products', 'message' => 'saved'),
                admin_url('admin.php')
            );
        } else {
            $redirect_url = add_query_arg(
                array(
                    'page' => 'jeco-mainproca-products', 
                    'action' => $product_id ? 'edit' : 'add',
                    'id' => $product_id ? $product_id : null,
                    'message' => 'save_error'
                ),
                admin_url('admin.php')
            );
        }

        wp_redirect($redirect_url);
        exit;
    }

    /**
     * Handle delete product
     *
     * @since 1.0.0
     */
    public function handle_delete_product() {
        // Verify nonce
        if (!wp_verify_nonce($_GET['_wpnonce'], 'jeco_delete_product_' . $_GET['id'])) {
            wp_die(__('Security check failed.', 'jeco-mainproca'));
        }

        // Check permissions
        if (!current_user_can('manage_options')) {
            wp_die(__('You do not have sufficient permissions.', 'jeco-mainproca'));
        }

        $product_id = isset($_GET['id']) ? (int) $_GET['id'] : 0;
        $product = JECO_MainProca_Product::find($product_id);

        if (!$product) {
            wp_die(__('Product not found.', 'jeco-mainproca'));
        }

        $result = $product->delete();

        if ($result) {
            $redirect_url = add_query_arg(
                array('page' => 'jeco-mainproca-products', 'message' => 'deleted'),
                admin_url('admin.php')
            );
        } else {
            $redirect_url = add_query_arg(
                array('page' => 'jeco-mainproca-products', 'message' => 'error'),
                admin_url('admin.php')
            );
        }

        wp_redirect($redirect_url);
        exit;
    }
}