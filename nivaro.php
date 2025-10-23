<?php
/**
 * Plugin Name: Nivaro
 * Plugin URI: https://github.com/transatlanticvoyage/nivaro
 * Description: Nivaro - WordPress plugin for zen data management with Elementor integration
 * Version: 1.0.0
 * Author: Nivaro Team
 * License: GPL v2 or later
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

// Define plugin constants
define('NIVARO_PLUGIN_VERSION', '1.0.0');
define('NIVARO_PLUGIN_PATH', plugin_dir_path(__FILE__));
define('NIVARO_PLUGIN_URL', plugin_dir_url(__FILE__));

// Initialize plugin
class Nivaro {
    
    private static $instance = null;
    public $database = null;
    
    public static function get_instance() {
        if (null === self::$instance) {
            self::$instance = new self();
        }
        return self::$instance;
    }
    
    public function __construct() {
        // Delay initialization until WordPress is fully loaded
        add_action('plugins_loaded', array($this, 'init'), 10);
    }
    
    public function init() {
        // Basic database functionality always loads
        $this->load_database_handler();
        
        // Initialize database instance
        if (class_exists('Nivaro_Database')) {
            $this->database = new Nivaro_Database();
        }
        
        // Register AJAX handlers for widgets
        $this->register_ajax_handlers();
        
        // Only load Elementor features if Elementor is active
        if ($this->is_elementor_active()) {
            add_action('elementor/init', array($this, 'elementor_init'));
        }
        
        // Enqueue frontend styles conditionally
        add_action('wp_enqueue_scripts', array($this, 'enqueue_frontend_assets'));
        
        // Enqueue editor styles and scripts
        add_action('elementor/editor/after_enqueue_scripts', array($this, 'enqueue_editor_assets'));
        add_action('elementor/editor/after_enqueue_styles', array($this, 'enqueue_editor_styles'));
    }
    
    private function load_database_handler() {
        $db_file = NIVARO_PLUGIN_PATH . 'includes/class-nivaro-database.php';
        if (file_exists($db_file)) {
            require_once $db_file;
        }
    }
    
    /**
     * Register AJAX handlers for independent widget operation
     */
    private function register_ajax_handlers() {
        // Leatherback auto-generation
        add_action('wp_ajax_nivaro_leatherback_auto_generate', array($this, 'ajax_leatherback_auto_generate'));
        add_action('wp_ajax_nopriv_nivaro_leatherback_auto_generate', array($this, 'ajax_leatherback_auto_generate'));
        
        // Gecko auto-generation
        add_action('wp_ajax_nivaro_gecko_auto_generate', array($this, 'ajax_gecko_auto_generate'));
        add_action('wp_ajax_nopriv_nivaro_gecko_auto_generate', array($this, 'ajax_gecko_auto_generate'));
        
        // Cameldrink count update
        add_action('wp_ajax_nivaro_cameldrink_get_count', array($this, 'ajax_cameldrink_get_count'));
        add_action('wp_ajax_nopriv_nivaro_cameldrink_get_count', array($this, 'ajax_cameldrink_get_count'));
        
        // Gambia count update
        add_action('wp_ajax_nivaro_gambia_get_count', array($this, 'ajax_gambia_get_count'));
        add_action('wp_ajax_nopriv_nivaro_gambia_get_count', array($this, 'ajax_gambia_get_count'));
    }
    
    public function elementor_init() {
        // Load widget files
        $widget_file = NIVARO_PLUGIN_PATH . 'includes/widgets/class-nivaro-zen-service-widget.php';
        if (file_exists($widget_file)) {
            require_once $widget_file;
        }
        
        $meerkat_file = NIVARO_PLUGIN_PATH . 'includes/widgets/class-nivaro-meerkat-box-widget.php';
        if (file_exists($meerkat_file)) {
            require_once $meerkat_file;
        }
        
        $fox_file = NIVARO_PLUGIN_PATH . 'includes/widgets/class-nivaro-fox-box-widget.php';
        if (file_exists($fox_file)) {
            require_once $fox_file;
        }
        
        $ocelot_file = NIVARO_PLUGIN_PATH . 'includes/widgets/class-nivaro-ocelot-service-widget.php';
        if (file_exists($ocelot_file)) {
            require_once $ocelot_file;
        }
        
        $leatherback_file = NIVARO_PLUGIN_PATH . 'includes/widgets/class-nivaro-leatherback-widget.php';
        if (file_exists($leatherback_file)) {
            require_once $leatherback_file;
        }
        
        $gecko_file = NIVARO_PLUGIN_PATH . 'includes/widgets/class-nivaro-gecko-widget.php';
        if (file_exists($gecko_file)) {
            require_once $gecko_file;
        }
        
        $cameldrink_file = NIVARO_PLUGIN_PATH . 'includes/widgets/class-nivaro-cameldrink-widget.php';
        if (file_exists($cameldrink_file)) {
            require_once $cameldrink_file;
        }
        
        $gambia_file = NIVARO_PLUGIN_PATH . 'includes/widgets/class-nivaro-gambia-widget.php';
        if (file_exists($gambia_file)) {
            require_once $gambia_file;
        }
        
        $sahara_file = NIVARO_PLUGIN_PATH . 'includes/widgets/class-nivaro-sahara-widget.php';
        if (file_exists($sahara_file)) {
            require_once $sahara_file;
        }
        
        // Load container extensions
        $coyote_file = NIVARO_PLUGIN_PATH . 'includes/extensions/class-nivaro-coyote-box-extension.php';
        if (file_exists($coyote_file)) {
            require_once $coyote_file;
            new Nivaro_Coyote_Box_Extension();
        }
        
        // Register widgets
        add_action('elementor/widgets/register', array($this, 'register_widgets'), 10, 1);
        
        // Enqueue editor styles
        add_action('elementor/editor/after_enqueue_styles', array($this, 'enqueue_assets'));
    }
    
    public function register_widgets($widgets_manager) {
        // Register Zen Service Widget
        if (class_exists('Nivaro_Zen_Service_Widget')) {
            try {
                $widgets_manager->register(new Nivaro_Zen_Service_Widget());
            } catch (Exception $e) {
                error_log('Nivaro: Failed to register Zen Service widget - ' . $e->getMessage());
            }
        }
        
        // Register Meerkat Box Widget
        if (class_exists('Nivaro_Meerkat_Box_Widget')) {
            try {
                $widgets_manager->register(new Nivaro_Meerkat_Box_Widget());
            } catch (Exception $e) {
                error_log('Nivaro: Failed to register Meerkat Box widget - ' . $e->getMessage());
            }
        }
        
        // Register Fox Box Widget
        if (class_exists('Nivaro_Fox_Box_Widget')) {
            try {
                $widgets_manager->register(new Nivaro_Fox_Box_Widget());
            } catch (Exception $e) {
                error_log('Nivaro: Failed to register Fox Box widget - ' . $e->getMessage());
            }
        }
        
        // Register Ocelot Service Widget
        if (class_exists('Nivaro_Ocelot_Service_Widget')) {
            try {
                $widgets_manager->register(new Nivaro_Ocelot_Service_Widget());
            } catch (Exception $e) {
                error_log('Nivaro: Failed to register Ocelot Service widget - ' . $e->getMessage());
            }
        }
        
        // Register Leatherback Widget
        if (class_exists('Nivaro_Leatherback_Widget')) {
            try {
                $widgets_manager->register(new Nivaro_Leatherback_Widget());
            } catch (Exception $e) {
                error_log('Nivaro: Failed to register Leatherback widget - ' . $e->getMessage());
            }
        }
        
        // Register Gecko Widget
        if (class_exists('Nivaro_Gecko_Widget')) {
            try {
                $widgets_manager->register(new Nivaro_Gecko_Widget());
            } catch (Exception $e) {
                error_log('Nivaro: Failed to register Gecko widget - ' . $e->getMessage());
            }
        }
        
        // Register Cameldrink Widget
        if (class_exists('Nivaro_Cameldrink_Widget')) {
            try {
                $widgets_manager->register(new Nivaro_Cameldrink_Widget());
            } catch (Exception $e) {
                error_log('Nivaro: Failed to register Cameldrink widget - ' . $e->getMessage());
            }
        }
        
        // Register Gambia Widget
        if (class_exists('Nivaro_Gambia_Widget')) {
            try {
                $widgets_manager->register(new Nivaro_Gambia_Widget());
            } catch (Exception $e) {
                error_log('Nivaro: Failed to register Gambia widget - ' . $e->getMessage());
            }
        }
        
        // Register Sahara Widget
        if (class_exists('Nivaro_Sahara_Widget')) {
            try {
                $widgets_manager->register(new Nivaro_Sahara_Widget());
            } catch (Exception $e) {
                error_log('Nivaro: Failed to register Sahara widget - ' . $e->getMessage());
            }
        }
    }
    
    /**
     * Check if page has Nivaro widgets
     */
    private function page_has_nivaro_widgets() {
        if (!class_exists('\Elementor\Plugin')) {
            return false;
        }
        
        $post_id = get_the_ID();
        if (!$post_id) {
            return false;
        }
        
        $document = \Elementor\Plugin::$instance->documents->get($post_id);
        if (!$document) {
            return false;
        }
        
        $elements = $document->get_elements_data();
        
        // Check recursively for our widgets
        return $this->search_for_widgets($elements);
    }
    
    /**
     * Recursively search for Nivaro widgets in Elementor data
     */
    private function search_for_widgets($elements) {
        if (!is_array($elements)) {
            return false;
        }
        
        $nivaro_widgets = array('nivaro-leatherback', 'nivaro-gecko', 'nivaro-ocelot-service', 'nivaro-cameldrink', 'nivaro-gambia', 'nivaro-sahara');
        
        foreach ($elements as $element) {
            if (isset($element['widgetType']) && in_array($element['widgetType'], $nivaro_widgets)) {
                return true;
            }
            
            if (!empty($element['elements'])) {
                if ($this->search_for_widgets($element['elements'])) {
                    return true;
                }
            }
        }
        
        return false;
    }
    
    /**
     * Enqueue frontend assets conditionally
     */
    public function enqueue_frontend_assets() {
        // Check if page has our widgets before loading assets
        if (!$this->page_has_nivaro_widgets()) {
            return;
        }
        
        // Enqueue Leatherback styles
        wp_enqueue_style(
            'nivaro-leatherback',
            NIVARO_PLUGIN_URL . 'assets/css/nivaro-leatherback.css',
            array(),
            NIVARO_PLUGIN_VERSION
        );
        
        // Enqueue Gecko styles
        wp_enqueue_style(
            'nivaro-gecko',
            NIVARO_PLUGIN_URL . 'assets/css/nivaro-gecko.css',
            array(),
            NIVARO_PLUGIN_VERSION
        );
        
        // Enqueue Cameldrink styles
        wp_enqueue_style(
            'nivaro-cameldrink',
            NIVARO_PLUGIN_URL . 'assets/css/nivaro-cameldrink.css',
            array(),
            NIVARO_PLUGIN_VERSION
        );
        
        // Enqueue Gambia styles
        wp_enqueue_style(
            'nivaro-gambia',
            NIVARO_PLUGIN_URL . 'assets/css/nivaro-gambia.css',
            array(),
            NIVARO_PLUGIN_VERSION
        );
        
        // Enqueue Sahara styles
        wp_enqueue_style(
            'nivaro-sahara',
            NIVARO_PLUGIN_URL . 'assets/css/nivaro-sahara.css',
            array(),
            NIVARO_PLUGIN_VERSION
        );
    }
    
    /**
     * Enqueue editor scripts for widget functionality
     */
    public function enqueue_editor_assets() {
        // Enqueue Leatherback editor script
        wp_enqueue_script(
            'nivaro-leatherback-editor',
            NIVARO_PLUGIN_URL . 'assets/js/nivaro-leatherback-editor.js',
            array('jquery', 'elementor-editor'),
            NIVARO_PLUGIN_VERSION,
            true
        );
        
        // Enqueue Gecko editor script
        wp_enqueue_script(
            'nivaro-gecko-editor',
            NIVARO_PLUGIN_URL . 'assets/js/nivaro-gecko-editor.js',
            array('jquery', 'elementor-editor'),
            NIVARO_PLUGIN_VERSION,
            true
        );
        
        // Enqueue Gambia editor script
        wp_enqueue_script(
            'nivaro-gambia-editor',
            NIVARO_PLUGIN_URL . 'assets/js/nivaro-gambia-editor.js',
            array('jquery', 'elementor-editor'),
            NIVARO_PLUGIN_VERSION,
            true
        );
        
        // Enqueue Cameldrink editor script
        wp_enqueue_script(
            'nivaro-cameldrink-editor',
            NIVARO_PLUGIN_URL . 'assets/js/nivaro-cameldrink-editor.js',
            array('jquery', 'elementor-editor'),
            NIVARO_PLUGIN_VERSION,
            true
        );
        
        // Localize scripts with AJAX data
        $ajax_data = array(
            'ajax_url' => admin_url('admin-ajax.php'),
            'nonce' => wp_create_nonce('nivaro_widget_nonce')
        );
        
        wp_localize_script('nivaro-leatherback-editor', 'nivaro_widget_ajax', $ajax_data);
        wp_localize_script('nivaro-gecko-editor', 'nivaro_widget_ajax', $ajax_data);
        wp_localize_script('nivaro-gambia-editor', 'nivaro_widget_ajax', $ajax_data);
        wp_localize_script('nivaro-cameldrink-editor', 'nivaro_widget_ajax', $ajax_data);
    }
    
    /**
     * Enqueue editor styles
     */
    public function enqueue_editor_styles() {
        // Enqueue widget styles in editor
        wp_enqueue_style(
            'nivaro-leatherback',
            NIVARO_PLUGIN_URL . 'assets/css/nivaro-leatherback.css',
            array(),
            NIVARO_PLUGIN_VERSION
        );
        
        wp_enqueue_style(
            'nivaro-gecko',
            NIVARO_PLUGIN_URL . 'assets/css/nivaro-gecko.css',
            array(),
            NIVARO_PLUGIN_VERSION
        );
    }
    
    public function enqueue_assets() {
        // Enqueue original widget styles
        $css_file = NIVARO_PLUGIN_URL . 'assets/css/nivaro-widget.css';
        wp_enqueue_style(
            'nivaro-widget',
            $css_file,
            array(),
            NIVARO_PLUGIN_VERSION
        );
        
        // Enqueue Meerkat Box styles
        $meerkat_css = NIVARO_PLUGIN_URL . 'assets/css/nivaro-meerkat-box.css';
        wp_enqueue_style(
            'nivaro-meerkat-box',
            $meerkat_css,
            array(),
            NIVARO_PLUGIN_VERSION
        );
        
        // Enqueue Fox Box styles
        $fox_css = NIVARO_PLUGIN_URL . 'assets/css/nivaro-fox-box.css';
        wp_enqueue_style(
            'nivaro-fox-box',
            $fox_css,
            array(),
            NIVARO_PLUGIN_VERSION
        );
        
        // Enqueue Ocelot Service styles
        $ocelot_css = NIVARO_PLUGIN_URL . 'assets/css/nivaro-ocelot-service.css';
        wp_enqueue_style(
            'nivaro-ocelot-service',
            $ocelot_css,
            array(),
            NIVARO_PLUGIN_VERSION
        );
        
        // Enqueue Leatherback styles
        $leatherback_css = NIVARO_PLUGIN_URL . 'assets/css/nivaro-leatherback.css';
        wp_enqueue_style(
            'nivaro-leatherback',
            $leatherback_css,
            array(),
            NIVARO_PLUGIN_VERSION
        );
        
        // Enqueue Gecko styles
        $gecko_css = NIVARO_PLUGIN_URL . 'assets/css/nivaro-gecko.css';
        wp_enqueue_style(
            'nivaro-gecko',
            $gecko_css,
            array(),
            NIVARO_PLUGIN_VERSION
        );
    }
    
    private function is_elementor_active() {
        // Check if Elementor is loaded
        return did_action('elementor/loaded');
    }
    
    /**
     * AJAX handler for Leatherback auto-generation
     * Moved from Coyote Extension for independent operation
     */
    public function ajax_leatherback_auto_generate() {
        // Verify nonce
        if (!wp_verify_nonce($_POST['nonce'], 'nivaro_widget_nonce')) {
            wp_die(json_encode(array('success' => false, 'message' => 'Security check failed')));
        }
        
        // Get all services from database with dynamic links
        $services = $this->database->get_all_services_with_links();
        $services_count = count($services);
        
        if (empty($services)) {
            wp_send_json_error('No services found in database');
        }
        
        // Prepare settings array for auto-generation (cap at 20 for performance)
        $effective_count = min($services_count, 20);
        $settings = array(
            'box_count' => $effective_count
        );
        
        // Auto-assign service IDs to each box with dynamic links (up to effective count)
        foreach (array_slice($services, 0, $effective_count) as $index => $service) {
            $box_number = $index + 1;
            $settings["service_{$box_number}_mode"] = 'auto';
            $settings["service_{$box_number}_auto_id"] = $service->service_id;
            $settings["service_{$box_number}_button_text"] = 'Learn More';
            // Set the button link field to show the dynamic link in the controls
            $settings["service_{$box_number}_button_link"] = array(
                'url' => $service->dynamic_link,
                'is_external' => false,
                'nofollow' => false
            );
        }
        
        wp_send_json_success(array(
            'services_count' => $effective_count,
            'settings' => $settings,
            'message' => sprintf('Successfully configured %d service boxes with dynamic links from database', $effective_count)
        ));
    }
    
    /**
     * AJAX handler for Gecko auto-generation
     * Moved from Coyote Extension for independent operation
     */
    public function ajax_gecko_auto_generate() {
        // Verify nonce
        if (!wp_verify_nonce($_POST['nonce'], 'nivaro_widget_nonce')) {
            wp_die(json_encode(array('success' => false, 'message' => 'Security check failed')));
        }
        
        // Get all services from database with Gecko-specific fields
        $services = $this->database->get_all_services_for_gecko();
        $services_count = count($services);
        
        if (empty($services)) {
            wp_send_json_error('No services found in database');
        }
        
        // Get quantity from request, default to 8, but cap at available services
        $quantity = isset($_POST['quantity']) ? intval($_POST['quantity']) : 8;
        $quantity = max(1, min($services_count, $quantity)); // Cannot exceed available services
        
        // Prepare response data for Gecko widget
        $response_services = array();
        
        // Convert up to requested quantity for Gecko widget format
        foreach (array_slice($services, 0, $quantity) as $service) {
            // Generate WordPress page URL from asn_service_page_id
            $page_url = '';
            if (!empty($service->asn_service_page_id)) {
                $page_url = get_permalink($service->asn_service_page_id);
            }
            
            // Get image URL from rel_image1_id
            $image_url = '';
            $image_id = '';
            if (!empty($service->rel_image1_id)) {
                $image_url = wp_get_attachment_url($service->rel_image1_id);
                $image_id = $service->rel_image1_id;
            }
            
            $response_services[] = array(
                'title' => $service->service_name,
                'description' => $service->description1_short ?? '',
                'link' => $page_url,
                'image_url' => $image_url,
                'image_id' => $image_id
            );
        }
        
        wp_send_json_success($response_services);
    }
    
    /**
     * AJAX handler for Cameldrink widget count updates
     */
    public function ajax_cameldrink_get_count() {
        // Verify nonce
        if (!wp_verify_nonce($_POST['nonce'], 'nivaro_coyote_nonce')) {
            wp_die(json_encode(array('success' => false, 'message' => 'Security check failed')));
        }
        
        global $wpdb;
        $table_name = $wpdb->prefix . 'zen_services';
        
        // Get filter values from request
        $is_active = sanitize_text_field($_POST['is_active'] ?? 'any');
        $is_pinned = sanitize_text_field($_POST['is_pinned'] ?? 'any');
        
        // Build WHERE clause based on filters
        $where_conditions = [];
        
        // Filter by is_active
        if ($is_active === 'yes') {
            $where_conditions[] = 'is_active = 1';
        } elseif ($is_active === 'no') {
            $where_conditions[] = '(is_active = 0 OR is_active IS NULL)';
        }
        
        // Filter by is_pinned
        if ($is_pinned === 'yes') {
            $where_conditions[] = 'is_pinned = 1';
        } elseif ($is_pinned === 'no') {
            $where_conditions[] = '(is_pinned = 0 OR is_pinned IS NULL)';
        }
        
        // Build WHERE clause
        $where = '';
        if (!empty($where_conditions)) {
            $where = 'WHERE ' . implode(' AND ', $where_conditions);
        }
        
        // Get count of filtered services
        $query = "SELECT COUNT(*) FROM {$table_name} {$where}";
        $count = $wpdb->get_var($query);
        
        wp_send_json_success(array(
            'count' => intval($count),
            'filters' => array(
                'is_active' => $is_active,
                'is_pinned' => $is_pinned
            )
        ));
    }
    
    /**
     * AJAX handler for Gambia widget count updates
     */
    public function ajax_gambia_get_count() {
        // Verify nonce
        if (!wp_verify_nonce($_POST['nonce'], 'nivaro_coyote_nonce')) {
            wp_die(json_encode(array('success' => false, 'message' => 'Security check failed')));
        }
        
        global $wpdb;
        $table_name = $wpdb->prefix . 'zen_services';
        
        // Get filter values from request
        $is_active = sanitize_text_field($_POST['is_active'] ?? 'any');
        $is_pinned = sanitize_text_field($_POST['is_pinned'] ?? 'any');
        
        // Build WHERE clause based on filters
        $where_conditions = [];
        
        // Filter by is_active
        if ($is_active === 'yes') {
            $where_conditions[] = 'is_active = 1';
        } elseif ($is_active === 'no') {
            $where_conditions[] = '(is_active = 0 OR is_active IS NULL)';
        }
        
        // Filter by is_pinned
        if ($is_pinned === 'yes') {
            $where_conditions[] = 'is_pinned = 1';
        } elseif ($is_pinned === 'no') {
            $where_conditions[] = '(is_pinned = 0 OR is_pinned IS NULL)';
        }
        
        // Build WHERE clause
        $where = '';
        if (!empty($where_conditions)) {
            $where = 'WHERE ' . implode(' AND ', $where_conditions);
        }
        
        // Get count of filtered services
        $query = "SELECT COUNT(*) FROM {$table_name} {$where}";
        $count = $wpdb->get_var($query);
        
        wp_send_json_success(array(
            'count' => intval($count),
            'filters' => array(
                'is_active' => $is_active,
                'is_pinned' => $is_pinned
            )
        ));
    }
}

// Start the plugin
function nivaro_init() {
    Nivaro::get_instance();
}
add_action('plugins_loaded', 'nivaro_init', 5);