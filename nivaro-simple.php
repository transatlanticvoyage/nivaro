<?php
/**
 * Plugin Name: Nivaro Simple Test
 * Description: Testing basic activation
 * Version: 1.0.0
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

// Simple test class
class Nivaro_Simple_Test {
    public function __construct() {
        add_action('admin_notices', array($this, 'test_notice'));
    }
    
    public function test_notice() {
        echo '<div class="notice notice-success"><p>Nivaro Simple Test is working!</p></div>';
    }
}

// Initialize
new Nivaro_Simple_Test();