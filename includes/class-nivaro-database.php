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
    
    /**
     * Get service image URL by page ID using Wombat System
     * Looks up service by asn_service_page_id matching current post ID
     */
    public function get_wombat_image_url($post_id, $size = 'full') {
        if (!$post_id || !is_numeric($post_id)) {
            return '';
        }
        
        $table_name = $this->wpdb->prefix . 'zen_services';
        
        if (!$this->table_exists($table_name)) {
            return '';
        }
        
        // Find service row where asn_service_page_id matches the post ID
        $service = $this->wpdb->get_row(
            $this->wpdb->prepare(
                "SELECT rel_image1_id FROM {$table_name} WHERE asn_service_page_id = %d LIMIT 1",
                $post_id
            )
        );
        
        if (!$service || !$service->rel_image1_id) {
            return '';
        }
        
        // Get WordPress attachment URL
        $image_url = wp_get_attachment_image_url($service->rel_image1_id, $size);
        
        return $image_url ? $image_url : '';
    }
    
    /**
     * Get services for Ocelot grid display
     * Returns specified number of services with images for grid layout
     */
    public function get_ocelot_services($limit = 8) {
        $table_name = $this->wpdb->prefix . 'zen_services';
        
        if (!$this->table_exists($table_name)) {
            return array();
        }
        
        $results = $this->wpdb->get_results(
            $this->wpdb->prepare(
                "SELECT service_id, service_name, service_placard, rel_image1_id 
                 FROM {$table_name} 
                 WHERE service_name IS NOT NULL 
                 AND rel_image1_id IS NOT NULL 
                 AND rel_image1_id != 0
                 ORDER BY service_name ASC 
                 LIMIT %d",
                $limit
            )
        );
        
        return $results ? $results : array();
    }
    
    /**
     * Get all services for Leatherback auto-generation
     * Returns all services from database for dynamic population
     */
    public function get_all_services_for_leatherback() {
        $table_name = $this->wpdb->prefix . 'zen_services';
        
        if (!$this->table_exists($table_name)) {
            return array();
        }
        
        $results = $this->wpdb->get_results(
            "SELECT service_id, service_name, service_placard, rel_image1_id 
             FROM {$table_name} 
             WHERE service_name IS NOT NULL 
             ORDER BY service_name ASC"
        );
        
        return $results ? $results : array();
    }
    
    /**
     * Get all services for Gecko widget with specific fields
     * Returns: service_id, service_name, description1_short, rel_image1_id, asn_service_page_id
     */
    public function get_all_services_for_gecko() {
        $table_name = $this->wpdb->prefix . 'zen_services';
        
        if (!$this->table_exists($table_name)) {
            return array();
        }
        
        $results = $this->wpdb->get_results(
            "SELECT service_id, service_name, description1_short, rel_image1_id, asn_service_page_id 
             FROM {$table_name} 
             WHERE service_name IS NOT NULL 
             ORDER BY service_name ASC"
        );
        
        return $results ? $results : array();
    }
    
    /**
     * Get count of all services in database
     */
    public function get_services_count() {
        $table_name = $this->wpdb->prefix . 'zen_services';
        
        if (!$this->table_exists($table_name)) {
            return 0;
        }
        
        $count = $this->wpdb->get_var(
            "SELECT COUNT(*) FROM {$table_name} WHERE service_name IS NOT NULL"
        );
        
        return intval($count);
    }
    
    /**
     * Get dynamic link for a service by tracing to wp_posts
     * Returns proper URL based on post status (published/draft)
     */
    public function get_service_dynamic_link($service_id) {
        if (!$service_id || !is_numeric($service_id)) {
            return '#';
        }
        
        $zen_table = $this->wpdb->prefix . 'zen_services';
        $posts_table = $this->wpdb->prefix . 'posts';
        
        if (!$this->table_exists($zen_table)) {
            return '#';
        }
        
        // Get the asn_service_page_id from zen_services
        $page_id = $this->wpdb->get_var(
            $this->wpdb->prepare(
                "SELECT asn_service_page_id FROM {$zen_table} WHERE service_id = %d",
                $service_id
            )
        );
        
        // Debug logging
        if (defined('WP_DEBUG') && WP_DEBUG) {
            error_log("Nivaro Debug: Service ID {$service_id} → asn_service_page_id: " . ($page_id ?? 'NULL'));
        }
        
        if (!$page_id) {
            return '#';
        }
        
        // Get post details from wp_posts
        $post = $this->wpdb->get_row(
            $this->wpdb->prepare(
                "SELECT ID, post_name, post_status, post_type FROM {$posts_table} WHERE ID = %d",
                $page_id
            )
        );
        
        if (!$post) {
            return '#';
        }
        
        // Construct URL based on post status
        if ($post->post_status === 'publish') {
            // Published page - use clean URL
            if ($post->post_type === 'page') {
                $url = home_url('/' . $post->post_name . '/');
            } else {
                // For posts or custom post types
                $url = get_permalink($post->ID);
            }
        } else {
            // Draft/unpublished - use preview URL
            $url = home_url('/?page_id=' . $post->ID . '&preview=true');
        }
        
        // Debug logging (remove in production)
        if (defined('WP_DEBUG') && WP_DEBUG) {
            error_log("Nivaro Debug: Service ID {$service_id} → Page ID {$page_id} → URL: {$url}");
        }
        
        return $url;
    }
    
    /**
     * Get service data with dynamic link included
     */
    public function get_service_with_link($service_id) {
        $service = $this->get_service_by_id($service_id);
        
        if (!$service) {
            return null;
        }
        
        // Add dynamic link to service object
        $service->dynamic_link = $this->get_service_dynamic_link($service_id);
        
        return $service;
    }
    
    /**
     * Get all services for Leatherback with dynamic links included
     */
    public function get_all_services_with_links() {
        $services = $this->get_all_services_for_leatherback();
        
        // Add dynamic links to each service
        foreach ($services as $service) {
            $service->dynamic_link = $this->get_service_dynamic_link($service->service_id);
        }
        
        return $services;
    }
    
    /**
     * Debug method to check service-to-page relationships
     * This will help identify why links aren't working
     */
    public function debug_service_page_relationships() {
        $zen_table = $this->wpdb->prefix . 'zen_services';
        $posts_table = $this->wpdb->prefix . 'posts';
        
        if (!$this->table_exists($zen_table)) {
            return array('error' => 'zen_services table not found');
        }
        
        // Get all services with their page relationships
        $results = $this->wpdb->get_results(
            "SELECT 
                zs.service_id,
                zs.service_name,
                zs.asn_service_page_id,
                p.ID as post_id,
                p.post_title,
                p.post_name,
                p.post_status,
                p.post_type
             FROM {$zen_table} zs
             LEFT JOIN {$posts_table} p ON zs.asn_service_page_id = p.ID
             WHERE zs.service_name IS NOT NULL
             ORDER BY zs.service_id ASC
             LIMIT 10"
        );
        
        $debug_info = array();
        foreach ($results as $row) {
            $debug_info[] = array(
                'service_id' => $row->service_id,
                'service_name' => $row->service_name,
                'asn_service_page_id' => $row->asn_service_page_id,
                'page_found' => !empty($row->post_id),
                'post_title' => $row->post_title,
                'post_name' => $row->post_name,
                'post_status' => $row->post_status,
                'expected_url' => $row->post_name ? home_url('/' . $row->post_name . '/') : 'No URL possible'
            );
        }
        
        return $debug_info;
    }
}