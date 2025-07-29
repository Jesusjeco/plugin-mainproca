<?php
/**
 * Admin Menu Management Class
 *
 * Handles the main admin menu registration and coordination
 * with feature-specific submenus.
 *
 * @package JECO_MainProca
 * @since 1.0.0
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Class JECO_MainProca_Admin_Menu
 *
 * Manages the main WordPress admin menu for the plugin
 */
class JECO_MainProca_Admin_Menu {

    /**
     * Menu slug for the main menu
     *
     * @var string
     */
    const MAIN_MENU_SLUG = 'jeco-mainproca';

    /**
     * Initialize the admin menu
     *
     * @since 1.0.0
     */
    public function __construct() {
        $this->init();
    }

    /**
     * Initialize the admin menu hooks
     *
     * @since 1.0.0
     */
    public function init() {
        add_action('admin_menu', [$this, 'add_main_menu']);
    }

    /**
     * Add the main menu page
     *
     * @since 1.0.0
     */
    public function add_main_menu() {
        add_menu_page(
            __('JECO MainProca Dashboard', 'jeco-mainproca'),    // Page title
            __('MainProca', 'jeco-mainproca'),                   // Menu title
            'manage_options',                                    // Capability
            self::MAIN_MENU_SLUG,                               // Menu slug
            [$this, 'render_dashboard'],                        // Callback function
            'dashicons-store',                                   // Icon
            30                                                   // Position
        );

        // Add dashboard as first submenu (to rename the first item)
        add_submenu_page(
            self::MAIN_MENU_SLUG,                               // Parent slug
            __('Dashboard', 'jeco-mainproca'),                  // Page title
            __('Dashboard', 'jeco-mainproca'),                  // Menu title
            'manage_options',                                    // Capability
            self::MAIN_MENU_SLUG,                               // Menu slug (same as parent)
            [$this, 'render_dashboard']                         // Callback function
        );
    }

    /**
     * Render the main dashboard page
     *
     * @since 1.0.0
     */
    public function render_dashboard() {
        // Check user capabilities
        if (!current_user_can('manage_options')) {
            wp_die(__('You do not have sufficient permissions to access this page.', 'jeco-mainproca'));
        }

        // Include the dashboard view
        $dashboard_file = JECO_MAINPROCA_PLUGIN_PATH . 'views/dashboard.php';
        
        if (file_exists($dashboard_file)) {
            include $dashboard_file;
        } else {
            echo "No Dashboard found";
        }
    }

    /**
     * Get the main menu slug (for use by submenus)
     *
     * @since 1.0.0
     * @return string Main menu slug
     */
    public static function get_main_menu_slug() {
        return self::MAIN_MENU_SLUG;
    }
}