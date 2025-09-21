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
        
        // Only load Elementor features if Elementor is active
        if ($this->is_elementor_active()) {
            add_action('elementor/init', array($this, 'elementor_init'));
        }
        
        // Enqueue frontend styles
        add_action('wp_enqueue_scripts', array($this, 'enqueue_assets'));
    }
    
    private function load_database_handler() {
        $db_file = NIVARO_PLUGIN_PATH . 'includes/class-nivaro-database.php';
        if (file_exists($db_file)) {
            require_once $db_file;
        }
    }
    
    public function elementor_init() {
        // Load widget file
        $widget_file = NIVARO_PLUGIN_PATH . 'includes/widgets/class-nivaro-zen-service-widget.php';
        if (file_exists($widget_file)) {
            require_once $widget_file;
        }
        
        // Register widget
        add_action('elementor/widgets/register', array($this, 'register_widgets'), 10, 1);
        
        // Enqueue editor styles
        add_action('elementor/editor/after_enqueue_styles', array($this, 'enqueue_assets'));
    }
    
    public function register_widgets($widgets_manager) {
        // Only register if class exists
        if (class_exists('Nivaro_Zen_Service_Widget')) {
            try {
                $widgets_manager->register(new Nivaro_Zen_Service_Widget());
            } catch (Exception $e) {
                error_log('Nivaro: Failed to register widget - ' . $e->getMessage());
            }
        }
    }
    
    public function enqueue_assets() {
        $css_file = NIVARO_PLUGIN_URL . 'assets/css/nivaro-widget.css';
        wp_enqueue_style(
            'nivaro-widget',
            $css_file,
            array(),
            NIVARO_PLUGIN_VERSION
        );
    }
    
    private function is_elementor_active() {
        // Check if Elementor is loaded
        return did_action('elementor/loaded');
    }
}

// Start the plugin
function nivaro_init() {
    Nivaro::get_instance();
}
add_action('plugins_loaded', 'nivaro_init', 5);