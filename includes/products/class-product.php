<?php
/**
 * Product Model Class
 *
 * Handles product data operations and validation.
 *
 * @package JECO_MainProca
 * @since 1.0.0
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Class JECO_MainProca_Product
 *
 * Product model for database operations
 */
class JECO_MainProca_Product {

    /**
     * Product ID
     * @var int
     */
    public $id;

    /**
     * Product name
     * @var string
     */
    public $name;

    /**
     * Product quantity
     * @var int
     */
    public $quantity;

    /**
     * Product price
     * @var float
     */
    public $price;

    /**
     * Product description
     * @var string
     */
    public $description;

    /**
     * Created timestamp
     * @var string
     */
    public $created_at;

    /**
     * Updated timestamp
     * @var string
     */
    public $updated_at;

    /**
     * Constructor
     *
     * @param array $data Product data
     */
    public function __construct($data = array()) {
        if (!empty($data)) {
            $this->fill($data);
        }
    }

    /**
     * Fill the model with data
     *
     * @param array $data Product data
     */
    public function fill($data) {
        // Handle both 'id' and 'product_id' for form submissions
        $this->id = isset($data['id']) ? (int) $data['id'] : (isset($data['product_id']) ? (int) $data['product_id'] : null);
        $this->name = isset($data['name']) ? sanitize_text_field($data['name']) : '';
        $this->quantity = isset($data['quantity']) ? max(0, (float) $data['quantity']) : 0.00;
        $this->price = isset($data['price']) ? max(0, (float) $data['price']) : 0.00;
        $this->description = isset($data['description']) ? sanitize_textarea_field($data['description']) : '';
        $this->created_at = isset($data['created_at']) ? $data['created_at'] : null;
        $this->updated_at = isset($data['updated_at']) ? $data['updated_at'] : null;
    }

    /**
     * Save the product to database
     *
     * @return int|false Product ID on success, false on failure
     */
    public function save() {
        global $wpdb;

        // Validate required fields
        if (empty($this->name)) {
            return false;
        }

        $table_name = JECO_MainProca_Product_Database::get_table_name();

        // Check if table exists - important for data persistence
        if ($wpdb->get_var("SHOW TABLES LIKE '$table_name'") != $table_name) {
            // Table doesn't exist, try to create it
            JECO_MainProca_Product_Database::create_table();
        }

        $data = array(
            'name' => $this->name,
            'quantity' => $this->quantity,
            'price' => $this->price,
            'description' => $this->description
        );

        if ($this->id) {
            // Update existing product
            $result = $wpdb->update(
                $table_name,
                $data,
                array('id' => $this->id),
                array('%s', '%f', '%f', '%s'),
                array('%d')
            );
            
            if ($result === false) {
                // Log the error for debugging
                error_log('JECO MainProca: Failed to update product. Error: ' . $wpdb->last_error);
                return false;
            }
            
            return $this->id;
        } else {
            // Insert new product
            $result = $wpdb->insert(
                $table_name,
                $data,
                array('%s', '%f', '%f', '%s')
            );
            
            if ($result === false) {
                // Log the error for debugging
                error_log('JECO MainProca: Failed to insert product. Error: ' . $wpdb->last_error);
                return false;
            }
            
            $this->id = $wpdb->insert_id;
            return $this->id;
        }
    }

    /**
     * Delete the product from database
     *
     * @return bool True on success, false on failure
     */
    public function delete() {
        if (!$this->id) {
            return false;
        }

        global $wpdb;
        $table_name = JECO_MainProca_Product_Database::get_table_name();

        $result = $wpdb->delete(
            $table_name,
            array('id' => $this->id),
            array('%d')
        );

        return $result !== false;
    }

    /**
     * Find a product by ID
     *
     * @param int $id Product ID
     * @return JECO_MainProca_Product|null Product object or null if not found
     */
    public static function find($id) {
        global $wpdb;
        $table_name = JECO_MainProca_Product_Database::get_table_name();

        $product_data = $wpdb->get_row(
            $wpdb->prepare("SELECT * FROM $table_name WHERE id = %d", $id),
            ARRAY_A
        );

        return $product_data ? new self($product_data) : null;
    }

    /**
     * Get all products
     *
     * @param array $args Query arguments
     * @return array Array of Product objects
     */
    public static function get_all($args = array()) {
        global $wpdb;
        $table_name = JECO_MainProca_Product_Database::get_table_name();

        $defaults = array(
            'limit' => 50,
            'offset' => 0,
            'orderby' => 'created_at',
            'order' => 'DESC'
        );

        $args = wp_parse_args($args, $defaults);

        $sql = "SELECT * FROM $table_name ORDER BY {$args['orderby']} {$args['order']} LIMIT %d OFFSET %d";
        
        $results = $wpdb->get_results(
            $wpdb->prepare($sql, $args['limit'], $args['offset']),
            ARRAY_A
        );

        $products = array();
        foreach ($results as $product_data) {
            $products[] = new self($product_data);
        }

        return $products;
    }

    /**
     * Get total products count
     *
     * @return int Total count
     */
    public static function get_count() {
        global $wpdb;
        $table_name = JECO_MainProca_Product_Database::get_table_name();

        return (int) $wpdb->get_var("SELECT COUNT(*) FROM $table_name");
    }

    /**
     * Convert to array
     *
     * @return array Product data as array
     */
    public function to_array() {
        return array(
            'id' => $this->id,
            'name' => $this->name,
            'quantity' => $this->quantity,
            'price' => $this->price,
            'description' => $this->description,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at
        );
    }
}