<?php
/**
 * Version Verification for JECO MAINPROCA Plugin
 * 
 * @package JECO_MainProca
 * @since 1.0.0
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Class for handling version requirements
 */
class JECO_MainProca_Version_Verification {
    
    /**
     * Minimum PHP version required
     */
    const MIN_PHP_VERSION = '8.1.0';
    
    /**
     * Minimum WordPress version required
     */
    const MIN_WP_VERSION = '6.0.0';
    
    /**
     * Check if system meets minimum requirements
     * 
     * @return array Array of error messages (empty if all requirements met)
     */
    public static function check_requirements() {
        $errors = array();
        
        // Check PHP version
        if (!self::check_php_version()) {
            $errors[] = sprintf(
                __('JECO MAINPROCA requires PHP %s or higher. You are running PHP %s.', 'jeco-mainproca'),
                self::MIN_PHP_VERSION,
                PHP_VERSION
            );
        }
        
        // Check WordPress version
        if (!self::check_wp_version()) {
            global $wp_version;
            $errors[] = sprintf(
                __('JECO MAINPROCA requires WordPress %s or higher. You are running WordPress %s.', 'jeco-mainproca'),
                self::MIN_WP_VERSION,
                $wp_version
            );
        }
        
        return $errors;
    }
    
    /**
     * Check PHP version requirement
     * 
     * @return bool True if PHP version meets requirement
     */
    public static function check_php_version() {
        return version_compare(PHP_VERSION, self::MIN_PHP_VERSION, '>=');
    }
    
    /**
     * Check WordPress version requirement
     * 
     * @return bool True if WordPress version meets requirement
     */
    public static function check_wp_version() {
        global $wp_version;
        return version_compare($wp_version, self::MIN_WP_VERSION, '>=');
    }
    
    /**
     * Display admin notice for requirement errors
     */
    public static function display_requirements_notice() {
        $errors = self::check_requirements();
        
        if (!empty($errors)) {
            echo '<div class="notice notice-error"><p>';
            echo '<strong>' . __('Plugin Activation Error:', 'jeco-mainproca') . '</strong><br>';
            echo implode('<br>', $errors);
            echo '</p></div>';
            
            // Deactivate the plugin
            deactivate_plugins(plugin_basename(JECO_MAINPROCA_PLUGIN_FILE));
        }
    }
    
    /**
     * Handle activation requirements check
     */
    public static function activation_check() {
        $errors = self::check_requirements();
        
        if (!empty($errors)) {
            // Show error and prevent activation
            wp_die(
                implode('<br>', $errors) . '<br><br>' . 
                '<a href="' . admin_url('plugins.php') . '">' . __('Go back to plugins', 'jeco-mainproca') . '</a>',
                __('Plugin Activation Error', 'jeco-mainproca'),
                array('back_link' => true)
            );
        }
    }
}