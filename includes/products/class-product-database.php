<?php
/**
 * Product Database Management Class
 *
 * Handles database table creation and management for products.
 *
 * @package JECO_MainProca
 * @since 1.0.0
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Class JECO_MainProca_Product_Database
 *
 * Manages product database operations and table creation
 */
class JECO_MainProca_Product_Database {

    /**
     * Create the products table
     *
     * @since 1.0.0
     */
    public static function create_table() {
        global $wpdb;

        $table_name = $wpdb->prefix . 'jeco_mainproca_products';

        $charset_collate = $wpdb->get_charset_collate();

        $sql = "CREATE TABLE $table_name (
            id bigint(20) unsigned NOT NULL AUTO_INCREMENT,
            name varchar(255) NOT NULL,
            quantity decimal(10,2) NOT NULL DEFAULT 0.00,
            price decimal(10,2) NOT NULL DEFAULT 0.00,
            description text,
            created_at datetime DEFAULT CURRENT_TIMESTAMP,
            updated_at datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            PRIMARY KEY (id),
            KEY name_index (name),
            KEY quantity_index (quantity),
            KEY price_index (price)
        ) $charset_collate;";

        require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
        dbDelta($sql);

        // Store the database version
        add_option('jeco_mainproca_products_db_version', '1.1.0');
    }

    /**
     * Drop the products table (for uninstall)
     *
     * @since 1.0.0
     */
    public static function drop_table() {
        global $wpdb;

        $table_name = $wpdb->prefix . 'jeco_mainproca_products';
        $wpdb->query("DROP TABLE IF EXISTS $table_name");

        delete_option('jeco_mainproca_products_db_version');
    }

    /**
     * Get the products table name
     *
     * @since 1.0.0
     * @return string Table name with prefix
     */
    public static function get_table_name() {
        global $wpdb;
        return $wpdb->prefix . 'jeco_mainproca_products';
    }
}