<?php
/**
 * Plugin Name: Nivaro
 * Plugin URI: https://github.com/transatlanticvoyage/nivaro
 * Description: Nivaro - WordPress plugin for zen data management
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
        add_action('plugins_loaded', array($this, 'init'));
    }
    
    public function init() {
        // Check if Elementor is active
        if (!did_action('elementor/loaded')) {
            add_action('admin_notices', array($this, 'elementor_missing_notice'));
            return;
        }
        
        // Load dependencies
        $this->load_dependencies();
        
        // Initialize Elementor widgets
        add_action('elementor/widgets/widgets_registered', array($this, 'register_widgets'));
        
        // Enqueue assets
        add_action('wp_enqueue_scripts', array($this, 'enqueue_assets'));
        add_action('elementor/editor/after_enqueue_styles', array($this, 'enqueue_assets'));
    }
    
    private function load_dependencies() {
        require_once NIVARO_PLUGIN_PATH . 'includes/class-nivaro-database.php';
        require_once NIVARO_PLUGIN_PATH . 'includes/widgets/class-nivaro-zen-service-widget.php';
    }
    
    public function register_widgets() {
        \Elementor\Plugin::instance()->widgets_manager->register_widget_type(new \Nivaro_Zen_Service_Widget());
    }
    
    public function enqueue_assets() {
        wp_enqueue_style(
            'nivaro-widget',
            NIVARO_PLUGIN_URL . 'assets/css/nivaro-widget.css',
            array(),
            NIVARO_PLUGIN_VERSION
        );
    }
    
    public function elementor_missing_notice() {
        echo '<div class="notice notice-warning is-dismissible">';
        echo '<p><strong>Nivaro:</strong> This plugin requires Elementor to be installed and activated.</p>';
        echo '</div>';
    }
}

// Start the plugin
Nivaro::get_instance();