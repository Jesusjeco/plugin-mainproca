<?php
/**
 * Database Management Class
 *
 * Handles database table creation and management for the plugin.
 *
 * @package JECO_MainProca
 * @since 1.0.0
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Class JECO_MainProca_Database
 *
 * Manages database operations and table creation
 */
class JECO_MainProca_Database {

    /**
     * Create all plugin tables
     *
     * @since 1.0.0
     */
    public static function create_tables() {
        // Create products table
        JECO_MainProca_Product_Database::create_table();
    }

    /**
     * Drop all plugin tables (for uninstall)
     *
     * @since 1.0.0
     */
    public static function drop_tables() {
        // Drop products table
        JECO_MainProca_Product_Database::drop_table();
    }
}