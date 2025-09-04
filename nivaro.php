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
    
    public function __construct() {
        add_action('init', array($this, 'init'));
    }
    
    public function init() {
        // Plugin initialization code will go here
    }
}

// Start the plugin
new Nivaro();