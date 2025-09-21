<?php
/**
 * Nivaro Coyote Box Container Extension
 * 
 * Pre-configured container with exact replica settings and dynamic capabilities
 */

if (!defined('ABSPATH')) {
    exit;
}

class Nivaro_Coyote_Box_Extension {
    
    private $database;
    
    public function __construct() {
        $this->database = new Nivaro_Database();
        
        // Add controls to containers
        add_action('elementor/element/container/section_layout/after_section_end', array($this, 'add_coyote_box_controls'), 10, 2);
        
        // Apply pre-configured settings when enabled
        add_action('elementor/element/container/before_render', array($this, 'apply_coyote_box_settings'));
        add_action('elementor/frontend/container/before_render', array($this, 'apply_coyote_box_settings'));
        
        // Inject inner content structure
        add_action('elementor/frontend/container/before_render', array($this, 'inject_coyote_content_start'));
        add_action('elementor/frontend/container/after_render', array($this, 'inject_coyote_content_end'));
        
        // Enqueue frontend styles
        add_action('wp_enqueue_scripts', array($this, 'enqueue_coyote_styles'));
        add_action('elementor/preview/enqueue_styles', array($this, 'enqueue_coyote_styles'));
        add_action('elementor/editor/after_enqueue_styles', array($this, 'enqueue_coyote_styles'));
        
        // Editor-specific hooks for background image visibility
        add_action('elementor/editor/after_enqueue_scripts', array($this, 'enqueue_editor_scripts'));
        add_action('elementor/editor/footer', array($this, 'editor_background_handler'));
        
        // AJAX handlers for editor background image retrieval
        add_action('wp_ajax_nivaro_get_service_image', array($this, 'ajax_get_service_image'));
        add_action('wp_ajax_nopriv_nivaro_get_service_image', array($this, 'ajax_get_service_image'));
    }
    
    /**
     * Add Coyote Box controls to container
     */
    public function add_coyote_box_controls($element, $args) {
        
        // Get service options from database
        $service_options = $this->database->get_service_options();
        
        // Add Coyote Box section at the top of Layout tab
        $element->start_controls_section(
            'coyote_box_section',
            array(
                'label' => __('🦊 Coyote Box Template', 'nivaro'),
                'tab' => \Elementor\Controls_Manager::TAB_LAYOUT,
            )
        );
        
        $element->add_control(
            'coyote_box_enable',
            array(
                'label' => __('Enable Coyote Box Template', 'nivaro'),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'label_on' => __('Yes', 'nivaro'),
                'label_off' => __('No', 'nivaro'),
                'return_value' => 'yes',
                'default' => '',
                'description' => __('Transform this container into a pre-styled hero section with dynamic background', 'nivaro'),
            )
        );
        
        $element->add_control(
            'coyote_box_important_note',
            array(
                'type' => \Elementor\Controls_Manager::RAW_HTML,
                'raw' => __('<strong>Note:</strong> When enabled, this will override container settings with pre-configured hero styling matching your template.', 'nivaro'),
                'content_classes' => 'elementor-panel-alert elementor-panel-alert-info',
                'condition' => array(
                    'coyote_box_enable' => 'yes',
                ),
            )
        );
        
        $element->add_control(
            'coyote_box_terminology_note',
            array(
                'type' => \Elementor\Controls_Manager::RAW_HTML,
                'raw' => __('<strong>NOTE:</strong> This is a "container extension". It can be referred to as the Coyote Container Extension', 'nivaro'),
                'content_classes' => 'elementor-panel-alert elementor-panel-alert-info',
                'condition' => array(
                    'coyote_box_enable' => 'yes',
                ),
            )
        );
        
        $element->add_control(
            'coyote_box_separator_1',
            array(
                'type' => \Elementor\Controls_Manager::DIVIDER,
                'condition' => array(
                    'coyote_box_enable' => 'yes',
                ),
            )
        );
        
        $element->add_control(
            'coyote_box_producement_heading',
            array(
                'type' => \Elementor\Controls_Manager::RAW_HTML,
                'raw' => __('producement options', 'nivaro'),
                'content_classes' => 'elementor-control-field-description',
                'condition' => array(
                    'coyote_box_enable' => 'yes',
                ),
            )
        );
        
        $element->add_control(
            'coyote_box_separator_2',
            array(
                'type' => \Elementor\Controls_Manager::DIVIDER,
                'condition' => array(
                    'coyote_box_enable' => 'yes',
                ),
            )
        );
        
        $element->add_control(
            'coyote_box_producement_mode',
            array(
                'label' => __('Producement Mode', 'nivaro'),
                'type' => \Elementor\Controls_Manager::SELECT,
                'options' => array(
                    'option_1' => __('Option 1 - default/fallback', 'nivaro'),
                    'option_2' => __('Option 2 - Dynamic System', 'nivaro'),
                    'option_3' => __('Option 3 - Derive From Wombat System', 'nivaro'),
                ),
                'default' => 'option_1',
                'condition' => array(
                    'coyote_box_enable' => 'yes',
                ),
                'description' => __('Select how the container will derive its background image', 'nivaro'),
            )
        );
        
        $element->add_control(
            'coyote_box_option_2_service_id',
            array(
                'label' => __('Select Zen Service', 'nivaro'),
                'type' => \Elementor\Controls_Manager::SELECT,
                'options' => $service_options,
                'default' => '',
                'condition' => array(
                    'coyote_box_enable' => 'yes',
                    'coyote_box_producement_mode' => 'option_2',
                ),
                'description' => __('Select a service to use its image as background', 'nivaro'),
            )
        );
        
        $element->add_control(
            'coyote_box_background_size',
            array(
                'label' => __('Background Size', 'nivaro'),
                'type' => \Elementor\Controls_Manager::SELECT,
                'options' => array(
                    'cover' => __('Cover', 'nivaro'),
                    'contain' => __('Contain', 'nivaro'),
                    'auto' => __('Auto', 'nivaro'),
                    '100% 100%' => __('Stretch', 'nivaro'),
                ),
                'default' => 'cover',
                'condition' => array(
                    'coyote_box_enable' => 'yes',
                    'coyote_box_producement_mode' => 'option_2',
                    'coyote_box_option_2_service_id!' => '',
                ),
            )
        );
        
        $element->add_control(
            'coyote_box_background_position',
            array(
                'label' => __('Background Position', 'nivaro'),
                'type' => \Elementor\Controls_Manager::SELECT,
                'options' => array(
                    'center center' => __('Center Center', 'nivaro'),
                    'center top' => __('Center Top', 'nivaro'),
                    'center bottom' => __('Center Bottom', 'nivaro'),
                    'left top' => __('Left Top', 'nivaro'),
                    'left center' => __('Left Center', 'nivaro'),
                    'left bottom' => __('Left Bottom', 'nivaro'),
                    'right top' => __('Right Top', 'nivaro'),
                    'right center' => __('Right Center', 'nivaro'),
                    'right bottom' => __('Right Bottom', 'nivaro'),
                ),
                'default' => 'center center',
                'condition' => array(
                    'coyote_box_enable' => 'yes',
                    'coyote_box_producement_mode' => 'option_2',
                    'coyote_box_option_2_service_id!' => '',
                ),
            )
        );
        
        $element->add_control(
            'coyote_box_background_repeat',
            array(
                'label' => __('Background Repeat', 'nivaro'),
                'type' => \Elementor\Controls_Manager::SELECT,
                'options' => array(
                    'no-repeat' => __('No Repeat', 'nivaro'),
                    'repeat' => __('Repeat', 'nivaro'),
                    'repeat-x' => __('Repeat X', 'nivaro'),
                    'repeat-y' => __('Repeat Y', 'nivaro'),
                ),
                'default' => 'no-repeat',
                'condition' => array(
                    'coyote_box_enable' => 'yes',
                    'coyote_box_producement_mode' => 'option_2',
                    'coyote_box_option_2_service_id!' => '',
                ),
            )
        );
        
        $element->end_controls_section();
        
        // Add Dynamic Content section
        $element->start_controls_section(
            'coyote_box_content_section',
            array(
                'label' => __('Coyote Box Content', 'nivaro'),
                'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
                'condition' => array(
                    'coyote_box_enable' => 'yes',
                ),
            )
        );
        
        $element->add_control(
            'coyote_box_heading',
            array(
                'label' => __('Heading Text', 'nivaro'),
                'type' => \Elementor\Controls_Manager::TEXT,
                'default' => __('Your Heading Here', 'nivaro'),
                'label_block' => true,
                'description' => __('The main heading text displayed in the container', 'nivaro'),
            )
        );
        
        $element->add_control(
            'coyote_box_service_id',
            array(
                'label' => __('Select Zen Service', 'nivaro'),
                'type' => \Elementor\Controls_Manager::SELECT,
                'options' => $service_options,
                'default' => '',
                'description' => __('Select a service to use its image as background', 'nivaro'),
            )
        );
        
        $element->add_control(
            'coyote_box_fallback_image',
            array(
                'label' => __('Fallback Background Image', 'nivaro'),
                'type' => \Elementor\Controls_Manager::MEDIA,
                'description' => __('Used when no service is selected or service has no image', 'nivaro'),
            )
        );
        
        $element->end_controls_section();
        
        // Add Override Options section  
        $element->start_controls_section(
            'coyote_box_overrides_section',
            array(
                'label' => __('Coyote Box Overrides', 'nivaro'),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
                'condition' => array(
                    'coyote_box_enable' => 'yes',
                ),
            )
        );
        
        $element->add_control(
            'coyote_box_override_height',
            array(
                'label' => __('Override Min Height', 'nivaro'),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'label_on' => __('Custom', 'nivaro'),
                'label_off' => __('Default', 'nivaro'),
                'return_value' => 'yes',
                'default' => '',
            )
        );
        
        $element->add_responsive_control(
            'coyote_box_custom_height',
            array(
                'label' => __('Custom Min Height', 'nivaro'),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => ['px', 'vh'],
                'range' => array(
                    'px' => array(
                        'min' => 100,
                        'max' => 1000,
                    ),
                    'vh' => array(
                        'min' => 10,
                        'max' => 100,
                    ),
                ),
                'condition' => array(
                    'coyote_box_override_height' => 'yes',
                ),
                'selectors' => array(
                    '{{WRAPPER}}.coyote-box-container' => 'min-height: {{SIZE}}{{UNIT}} !important;',
                ),
            )
        );
        
        $element->add_control(
            'coyote_box_override_overlay',
            array(
                'label' => __('Override Overlay Opacity', 'nivaro'),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'label_on' => __('Custom', 'nivaro'),
                'label_off' => __('Default', 'nivaro'),
                'return_value' => 'yes',
                'default' => '',
            )
        );
        
        $element->add_control(
            'coyote_box_custom_overlay_opacity',
            array(
                'label' => __('Custom Overlay Opacity', 'nivaro'),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'range' => array(
                    'px' => array(
                        'min' => 0,
                        'max' => 1,
                        'step' => 0.1,
                    ),
                ),
                'default' => array(
                    'size' => 0.7,
                ),
                'condition' => array(
                    'coyote_box_override_overlay' => 'yes',
                ),
                'selectors' => array(
                    '{{WRAPPER}} .coyote-box-overlay' => 'opacity: {{SIZE}} !important;',
                ),
            )
        );
        
        $element->end_controls_section();
    }
    
    /**
     * Apply pre-configured settings when Coyote Box is enabled
     */
    public function apply_coyote_box_settings($element) {
        $settings = $element->get_settings();
        
        // Check if Coyote Box is enabled
        if (empty($settings['coyote_box_enable']) || $settings['coyote_box_enable'] !== 'yes') {
            return;
        }
        
        // Apply pre-configured container settings from original JSON
        $pre_configured = array(
            // Layout settings
            'container_type' => 'flex',
            'content_width' => 'boxed',
            'boxed_width' => array('size' => 1200, 'unit' => 'px'),
            'min_height' => array('size' => 350, 'unit' => 'px'),
            'min_height_tablet' => array('size' => 300, 'unit' => 'px'),
            'min_height_mobile' => array('size' => 250, 'unit' => 'px'),
            'flex_direction' => 'column',
            'flex_justify_content' => 'center',
            'flex_align_items' => 'center',
            'padding' => array(
                'top' => '50',
                'right' => '20',
                'bottom' => '50',
                'left' => '20',
                'unit' => 'px',
                'isLinked' => false,
            ),
        );
        
        // Force apply settings
        foreach ($pre_configured as $key => $value) {
            $element->set_settings($key, $value);
        }
        
        // Handle background based on producement mode
        $producement_mode = $settings['coyote_box_producement_mode'] ?? 'option_1';
        $bg_image_url = '';
        
        switch ($producement_mode) {
            case 'option_1':
                // Use fallback/default behavior - rely on normal Elementor container background settings
                // No dynamic background applied, container uses its own background settings
                break;
                
            case 'option_2':
                // Use zen service dynamic system
                if (!empty($settings['coyote_box_option_2_service_id'])) {
                    $bg_image_url = $this->database->get_service_image_url($settings['coyote_box_option_2_service_id'], 'full');
                }
                break;
                
            case 'option_3':
                // Use Wombat System - derive from current page ID
                $current_post_id = get_the_ID();
                if ($current_post_id) {
                    $bg_image_url = $this->database->get_wombat_image_url($current_post_id, 'full');
                }
                break;
        }
        
        // Apply dynamic background only for option 2 and 3
        if (!empty($bg_image_url) && $producement_mode !== 'option_1') {
            // Get user-selected background settings
            $bg_size = $settings['coyote_box_background_size'] ?? 'cover';
            $bg_position = $settings['coyote_box_background_position'] ?? 'center center';
            $bg_repeat = $settings['coyote_box_background_repeat'] ?? 'no-repeat';
            
            $element->add_render_attribute('_wrapper', 'style', 
                'background-image: url(' . esc_url($bg_image_url) . ') !important; ' .
                'background-position: ' . esc_attr($bg_position) . ' !important; ' .
                'background-size: ' . esc_attr($bg_size) . ' !important; ' .
                'background-repeat: ' . esc_attr($bg_repeat) . ' !important;'
            );
        }
        
        // Add CSS classes
        $element->add_render_attribute('_wrapper', 'class', array(
            'coyote-box-container',
            'coyote-box-enabled',
        ));
        
        // Add mode-specific classes and data
        $element->add_render_attribute('_wrapper', 'class', 'coyote-box-mode-' . $producement_mode);
        
        if ($producement_mode === 'option_2' && !empty($settings['coyote_box_option_2_service_id'])) {
            $element->add_render_attribute('_wrapper', 'class', 'coyote-box-service-' . $settings['coyote_box_option_2_service_id']);
        }
        
        // Add data attributes
        $element->add_render_attribute('_wrapper', array(
            'data-coyote-box' => 'enabled',
            'data-coyote-mode' => $producement_mode,
            'data-coyote-service-id' => ($producement_mode === 'option_2') ? ($settings['coyote_box_option_2_service_id'] ?? '') : '',
        ));
    }
    
    /**
     * Inject content structure start
     */
    public function inject_coyote_content_start($element) {
        $settings = $element->get_settings();
        
        if (empty($settings['coyote_box_enable']) || $settings['coyote_box_enable'] !== 'yes') {
            return;
        }
        
        // Start output buffering to inject our content
        ob_start();
    }
    
    /**
     * Inject content structure end
     */
    public function inject_coyote_content_end($element) {
        $settings = $element->get_settings();
        
        if (empty($settings['coyote_box_enable']) || $settings['coyote_box_enable'] !== 'yes') {
            return;
        }
        
        // Get the container's content
        $content = ob_get_clean();
        
        // Output our structure
        ?>
        <!-- Coyote Box Structure -->
        <div class="coyote-box-inner-wrapper">
            <!-- Overlay -->
            <div class="coyote-box-overlay"></div>
            
            <!-- Content Container -->
            <div class="coyote-box-content-container">
                <?php if (!empty($settings['coyote_box_heading'])): ?>
                    <h1 class="coyote-box-heading">
                        <?php echo esc_html($settings['coyote_box_heading']); ?>
                    </h1>
                <?php endif; ?>
            </div>
            
            <!-- Original container content (if any widgets were added) -->
            <?php echo $content; ?>
        </div>
        <?php
    }
    
    /**
     * Enqueue frontend styles
     */
    public function enqueue_coyote_styles() {
        wp_enqueue_style(
            'nivaro-coyote-box',
            NIVARO_PLUGIN_URL . 'assets/css/nivaro-coyote-box.css',
            array(),
            NIVARO_PLUGIN_VERSION
        );
        
        // Enqueue Google Fonts
        wp_enqueue_style(
            'google-fonts-poppins',
            'https://fonts.googleapis.com/css2?family=Poppins:wght@600&display=swap',
            array(),
            null
        );
    }
    
    /**
     * Enqueue editor scripts for background image handling
     */
    public function enqueue_editor_scripts() {
        wp_enqueue_script(
            'nivaro-coyote-editor',
            NIVARO_PLUGIN_URL . 'assets/js/nivaro-coyote-editor.js',
            array('jquery', 'elementor-editor'),
            NIVARO_PLUGIN_VERSION,
            true
        );
        
        // Localize script with AJAX data
        wp_localize_script('nivaro-coyote-editor', 'nivaro_coyote_ajax', array(
            'ajax_url' => admin_url('admin-ajax.php'),
            'nonce' => wp_create_nonce('nivaro_coyote_nonce'),
            'action' => 'nivaro_get_service_image'
        ));
    }
    
    /**
     * Editor background handler - inject dynamic CSS for editor
     */
    public function editor_background_handler() {
        ?>
        <script type="text/javascript">
        jQuery(document).ready(function($) {
            // Function to update container background in editor
            function updateCoyoteBackground(containerId, serviceId, bgSize, bgPosition, bgRepeat, mode) {
                if (mode === 'option_2' && !serviceId) return;
                
                // Set defaults if not provided
                bgSize = bgSize || 'cover';
                bgPosition = bgPosition || 'center center';
                bgRepeat = bgRepeat || 'no-repeat';
                
                // AJAX call to get service image URL
                $.ajax({
                    url: nivaro_coyote_ajax.ajax_url,
                    type: 'POST',
                    data: {
                        action: nivaro_coyote_ajax.action,
                        service_id: serviceId || '',
                        bg_size: bgSize,
                        bg_position: bgPosition,
                        bg_repeat: bgRepeat,
                        mode: mode || 'option_2',
                        nonce: nivaro_coyote_ajax.nonce
                    },
                    success: function(response) {
                        if (response.success && response.data.image_url) {
                            var imageUrl = response.data.image_url;
                            
                            // Target the container in editor iframe
                            var containerSelector = '[data-id="' + containerId + '"]';
                            var $container = $(containerSelector);
                            
                            if ($container.length) {
                                // Get background settings from response
                                var bgSize = response.data.bg_size || 'cover';
                                var bgPosition = response.data.bg_position || 'center center';
                                var bgRepeat = response.data.bg_repeat || 'no-repeat';
                                
                                // Apply background image with high specificity
                                $container.css({
                                    'background-image': 'url(' + imageUrl + ') !important',
                                    'background-position': bgPosition + ' !important',
                                    'background-size': bgSize + ' !important',
                                    'background-repeat': bgRepeat + ' !important'
                                });
                                
                                // Also inject as inline style for higher priority
                                var inlineStyle = 'background-image: url(' + imageUrl + ') !important; ' +
                                                'background-position: ' + bgPosition + ' !important; ' +
                                                'background-size: ' + bgSize + ' !important; ' +
                                                'background-repeat: ' + bgRepeat + ' !important;';
                                
                                $container.attr('style', ($container.attr('style') || '') + inlineStyle);
                            }
                        }
                    }
                });
            }
            
            // Listen for Elementor panel changes
            if (typeof elementor !== 'undefined') {
                elementor.hooks.addAction('panel/open_editor/widget', function(panel, model, view) {
                    // Handle container controls
                    if (model.get('elType') === 'container') {
                        var settings = model.get('settings');
                        if (settings.get('coyote_box_enable') === 'yes' && 
                            (settings.get('coyote_box_producement_mode') === 'option_2' || 
                             settings.get('coyote_box_producement_mode') === 'option_3')) {
                            
                            var serviceId = settings.get('coyote_box_option_2_service_id');
                            var bgSize = settings.get('coyote_box_background_size');
                            var bgPosition = settings.get('coyote_box_background_position');
                            var bgRepeat = settings.get('coyote_box_background_repeat');
                            var containerId = model.get('id');
                            var mode = settings.get('coyote_box_producement_mode');
                            
                            if ((mode === 'option_2' && serviceId) || mode === 'option_3') {
                                updateCoyoteBackground(containerId, serviceId, bgSize, bgPosition, bgRepeat, mode);
                            }
                        }
                    }
                });
                
                // Listen for setting changes
                elementor.hooks.addAction('panel/editor/change', function(controlView, elementView) {
                    var model = elementView.model;
                    var settings = model.get('settings');
                    
                    if (model.get('elType') === 'container' && 
                        settings.get('coyote_box_enable') === 'yes' &&
                        (settings.get('coyote_box_producement_mode') === 'option_2' || 
                         settings.get('coyote_box_producement_mode') === 'option_3')) {
                        
                        var serviceId = settings.get('coyote_box_option_2_service_id');
                        var bgSize = settings.get('coyote_box_background_size');
                        var bgPosition = settings.get('coyote_box_background_position');
                        var bgRepeat = settings.get('coyote_box_background_repeat');
                        var containerId = model.get('id');
                        var mode = settings.get('coyote_box_producement_mode');
                        
                        if ((mode === 'option_2' && serviceId) || mode === 'option_3') {
                            setTimeout(function() {
                                updateCoyoteBackground(containerId, serviceId, bgSize, bgPosition, bgRepeat, mode);
                            }, 100);
                        }
                    }
                });
            }
        });
        </script>
        <?php
    }
    
    /**
     * AJAX handler to get service image URL for editor
     */
    public function ajax_get_service_image() {
        // Verify nonce
        if (!wp_verify_nonce($_POST['nonce'], 'nivaro_coyote_nonce')) {
            wp_die(json_encode(array('success' => false, 'message' => 'Security check failed')));
        }
        
        $service_id = sanitize_text_field($_POST['service_id'] ?? '');
        $bg_size = sanitize_text_field($_POST['bg_size'] ?? 'cover');
        $bg_position = sanitize_text_field($_POST['bg_position'] ?? 'center center');
        $bg_repeat = sanitize_text_field($_POST['bg_repeat'] ?? 'no-repeat');
        $mode = sanitize_text_field($_POST['mode'] ?? 'option_2');
        
        $image_url = '';
        
        if ($mode === 'option_3') {
            // Use Wombat System - get current post ID
            // In editor context, we need to get the post ID from the current page being edited
            $post_id = isset($_POST['post_id']) ? intval($_POST['post_id']) : get_the_ID();
            if (!$post_id && isset($_GET['post'])) {
                $post_id = intval($_GET['post']);
            }
            if (!$post_id && isset($_POST['editor_post_id'])) {
                $post_id = intval($_POST['editor_post_id']);
            }
            
            if ($post_id) {
                $image_url = $this->database->get_wombat_image_url($post_id, 'full');
            }
        } elseif ($mode === 'option_2') {
            // Use Option 2 - service selection
            if (empty($service_id)) {
                wp_send_json_error('No service ID provided for Option 2');
            }
            
            // Get image URL using existing database method
            $image_url = $this->database->get_service_image_url($service_id, 'full');
        }
        
        if (!empty($image_url)) {
            wp_send_json_success(array(
                'image_url' => $image_url,
                'bg_size' => $bg_size,
                'bg_position' => $bg_position,
                'bg_repeat' => $bg_repeat,
                'mode' => $mode
            ));
        } else {
            wp_send_json_error('No image found for ' . ($mode === 'option_3' ? 'current page in Wombat System' : 'selected service'));
        }
    }
}