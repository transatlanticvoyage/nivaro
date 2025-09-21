<?php
/**
 * Nivaro Database Handler
 * 
 * Database access for zen services data
 */

if (!defined('ABSPATH')) {
    exit;
}

class Nivaro_Database {
    
    private $wpdb;
    
    public function __construct() {
        global $wpdb;
        $this->wpdb = $wpdb;
    }
    
    /**
     * Get service data by ID
     */
    public function get_service_by_id($service_id) {
        if (!$service_id || !is_numeric($service_id)) {
            return null;
        }
        
        $table_name = $this->wpdb->prefix . 'zen_services';
        
        if (!$this->table_exists($table_name)) {
            return null;
        }
        
        $result = $this->wpdb->get_row(
            $this->wpdb->prepare(
                "SELECT * FROM {$table_name} WHERE service_id = %d",
                $service_id
            )
        );
        
        return $result;
    }
    
    /**
     * Get image URL from service relation
     */
    public function get_service_image_url($service_id, $size = 'full') {
        $service = $this->get_service_by_id($service_id);
        
        if (!$service || !$service->rel_image1_id) {
            return '';
        }
        
        // Get WordPress attachment URL
        $image_url = wp_get_attachment_image_url($service->rel_image1_id, $size);
        
        return $image_url ? $image_url : '';
    }
    
    /**
     * Get all image sizes for a service
     */
    public function get_service_image_sizes($service_id) {
        $service = $this->get_service_by_id($service_id);
        
        if (!$service || !$service->rel_image1_id) {
            return array();
        }
        
        $sizes = array();
        $available_sizes = wp_get_additional_image_sizes();
        $available_sizes['thumbnail'] = array('width' => get_option('thumbnail_size_w'), 'height' => get_option('thumbnail_size_h'));
        $available_sizes['medium'] = array('width' => get_option('medium_size_w'), 'height' => get_option('medium_size_h'));
        $available_sizes['large'] = array('width' => get_option('large_size_w'), 'height' => get_option('large_size_h'));
        $available_sizes['full'] = array('width' => null, 'height' => null);
        
        foreach ($available_sizes as $size_name => $size_data) {
            $image_url = wp_get_attachment_image_url($service->rel_image1_id, $size_name);
            if ($image_url) {
                $sizes[$size_name] = $image_url;
            }
        }
        
        return $sizes;
    }
    
    /**
     * Check if table exists
     */
    private function table_exists($table_name) {
        $result = $this->wpdb->get_var(
            $this->wpdb->prepare(
                "SHOW TABLES LIKE %s",
                $table_name
            )
        );
        
        return $result === $table_name;
    }
    
    /**
     * Get all services for dropdown options
     */
    public function get_all_services() {
        $table_name = $this->wpdb->prefix . 'zen_services';
        
        if (!$this->table_exists($table_name)) {
            return array();
        }
        
        $results = $this->wpdb->get_results(
            "SELECT service_id, service_name, service_placard 
             FROM {$table_name} 
             WHERE service_name IS NOT NULL 
             ORDER BY service_name ASC"
        );
        
        return $results ? $results : array();
    }
    
    /**
     * Get service options for Elementor dropdown
     */
    public function get_service_options() {
        try {
            $services = $this->get_all_services();
            $options = array('' => __('Select a service...', 'nivaro'));
            
            foreach ($services as $service) {
                $label = $service->service_name;
                if (!empty($service->service_placard)) {
                    $label .= ' (' . $service->service_placard . ')';
                }
                $options[$service->service_id] = $label;
            }
            
            return $options;
        } catch (Exception $e) {
            // Return default options if database error
            return array('' => __('Database error - check zen_services table', 'nivaro'));
        }
    }
}