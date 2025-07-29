<?php
/**
 * Plugin Name: JECO MAINPROCA
 * Description: Inventory and record system for managing products, orders, and purchases
 * Version: 1.0.0
 * Author: Jesus Carrero
 * Author URI: https://jesusjeco.dev
 * Website: https://jesusjeco.dev
 * Text Domain: jeco-mainproca
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

// Define plugin constants
define('JECO_MAINPROCA_VERSION', '1.0.0');
define('JECO_MAINPROCA_PLUGIN_PATH', plugin_dir_path(__FILE__));
define('JECO_MAINPROCA_PLUGIN_URL', plugin_dir_url(__FILE__));
define('JECO_MAINPROCA_PLUGIN_FILE', __FILE__);

// Include version verification
require_once JECO_MAINPROCA_PLUGIN_PATH . 'includes/version-verification.php';

// Include admin menu class
require_once JECO_MAINPROCA_PLUGIN_PATH . 'includes/class-admin-menu.php';

// Include database management class
require_once JECO_MAINPROCA_PLUGIN_PATH . 'includes/class-database.php';

// Include product feature classes
require_once JECO_MAINPROCA_PLUGIN_PATH . 'includes/products/class-product-database.php';
require_once JECO_MAINPROCA_PLUGIN_PATH . 'includes/products/class-product.php';
require_once JECO_MAINPROCA_PLUGIN_PATH . 'includes/products/class-product-admin.php';

/**
 * Main Plugin Class - Singleton Pattern
 */
class JECO_MainProca {
    
    private static $instance = null;
    
    /**
     * Get singleton instance
     */
    public static function get_instance() {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }
    
    /**
     * Private constructor - prevents multiple instances
     */
    private function __construct() {
        $this->init();
    }
    
    /**
     * Initialize the plugin
     */
    private function init() {
        // Initialize admin menu
        if (is_admin()) {
            new JECO_MainProca_Admin_Menu();
            new JECO_MainProca_Product_Admin();
        }
        
        add_action('init', array($this, 'plugin_init'));
    }
    
    /**
     * Plugin initialization callback
     */
    public function plugin_init() {
        // This will run when WordPress initializes
        // We'll add more functionality here later
    }
}// JECO Mainproca Class

/**
 * Activation Hook
 */
function jeco_mainproca_activate() {
    // Check requirements first
    JECO_MainProca_Version_Verification::activation_check();
    
    // Requirements met - proceed with activation
    // Create database tables
    JECO_MainProca_Database::create_tables();
}

/**
 * Deactivation Hook
 */
function jeco_mainproca_deactivate() {
    // Drop database tables for development convenience
    // This ensures a fresh start every time the plugin is reactivated
    JECO_MainProca_Product_Database::drop_table();
    
    // Clean up scheduled events, etc.
    // We'll add more cleanup tasks here as needed
}

// Register hooks
register_activation_hook(__FILE__, 'jeco_mainproca_activate');
register_deactivation_hook(__FILE__, 'jeco_mainproca_deactivate');

// Add admin notice for version requirements
add_action('admin_notices', array('JECO_MainProca_Version_Verification', 'display_requirements_notice'));

// Only initialize if requirements are met
if (empty(JECO_MainProca_Version_Verification::check_requirements())) {
    JECO_MainProca::get_instance();
}