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
     * Check if a product name already exists
     *
     * @param string $name Product name to check
     * @param int $exclude_id Product ID to exclude from check (for updates)
     * @return bool True if name exists, false otherwise
     */
    public static function name_exists($name, $exclude_id = null) {
        global $wpdb;
        $table_name = JECO_MainProca_Product_Database::get_table_name();
        
        $query = "SELECT COUNT(*) FROM $table_name WHERE name = %s";
        $params = array($name);
        
        if ($exclude_id) {
            $query .= " AND id != %d";
            $params[] = $exclude_id;
        }
        
        $count = $wpdb->get_var($wpdb->prepare($query, $params));
        return $count > 0;
    }

    /**
     * Save the product to database
     *
     * @return int|false|string Product ID on success, false on failure, 'duplicate' if name exists
     */
    public function save() {
        global $wpdb;

        // Validate required fields
        if (empty($this->name)) {
            return false;
        }

        // Check for duplicate names
        if (self::name_exists($this->name, $this->id)) {
            return 'duplicate';
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
     * Get all products with optional search and pagination
     *
     * @param array $args Optional arguments for filtering and pagination
     * @return array Array of Product objects
     */
    public static function get_all($args = array()) {
        global $wpdb;
        $table_name = JECO_MainProca_Product_Database::get_table_name();
        
        $defaults = array(
            'limit' => 50,
            'offset' => 0,
            'orderby' => 'created_at',
            'order' => 'DESC',
            'search' => ''
        );
        
        $args = wp_parse_args($args, $defaults);
        
        // Build the query
        $query = "SELECT * FROM $table_name";
        $where_conditions = array();
        $query_params = array();
        
        // Add search condition
        if (!empty($args['search'])) {
            $where_conditions[] = "name LIKE %s";
            $query_params[] = '%' . $wpdb->esc_like($args['search']) . '%';
        }
        
        // Add WHERE clause if we have conditions
        if (!empty($where_conditions)) {
            $query .= " WHERE " . implode(' AND ', $where_conditions);
        }
        
        // Add ORDER BY
        $allowed_orderby = array('id', 'name', 'quantity', 'price', 'created_at', 'updated_at');
        $orderby = in_array($args['orderby'], $allowed_orderby) ? $args['orderby'] : 'created_at';
        $order = strtoupper($args['order']) === 'ASC' ? 'ASC' : 'DESC';
        $query .= " ORDER BY {$orderby} {$order}";
        
        // Add LIMIT and OFFSET
        $query .= " LIMIT %d OFFSET %d";
        $query_params[] = $args['limit'];
        $query_params[] = $args['offset'];
        
        // Prepare and execute query
        $prepared_query = $wpdb->prepare($query, $query_params);
        $results = $wpdb->get_results($prepared_query, ARRAY_A);
        
        $products = array();
        foreach ($results as $product_data) {
            $products[] = new self($product_data);
        }
        
        return $products;
    }

    /**
     * Get total count of products with optional search
     *
     * @param array $args Optional arguments for filtering
     * @return int Total count
     */
    public static function get_count($args = array()) {
        global $wpdb;
        $table_name = JECO_MainProca_Product_Database::get_table_name();
        
        $defaults = array(
            'search' => ''
        );
        
        $args = wp_parse_args($args, $defaults);
        
        // Build the query
        $query = "SELECT COUNT(*) FROM $table_name";
        $where_conditions = array();
        $query_params = array();
        
        // Add search condition
        if (!empty($args['search'])) {
            $where_conditions[] = "name LIKE %s";
            $query_params[] = '%' . $wpdb->esc_like($args['search']) . '%';
        }
        
        // Add WHERE clause if we have conditions
        if (!empty($where_conditions)) {
            $query .= " WHERE " . implode(' AND ', $where_conditions);
        }
        
        // Prepare and execute query
        if (!empty($query_params)) {
            $prepared_query = $wpdb->prepare($query, $query_params);
        } else {
            $prepared_query = $query;
        }
        
        return (int) $wpdb->get_var($prepared_query);
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