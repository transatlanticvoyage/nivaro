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
            'coyote_box_option_1',
            array(
                'label' => __('<strong>Option 1 - default/fallback</strong>', 'nivaro'),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'label_on' => __('On', 'nivaro'),
                'label_off' => __('Off', 'nivaro'),
                'return_value' => 'yes',
                'default' => '',
                'condition' => array(
                    'coyote_box_enable' => 'yes',
                ),
            )
        );
        
        $element->add_control(
            'coyote_box_option_2',
            array(
                'label' => __('<strong>Option 2 - Dynamic System</strong>', 'nivaro'),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'label_on' => __('On', 'nivaro'),
                'label_off' => __('Off', 'nivaro'),
                'return_value' => 'yes',
                'default' => '',
                'condition' => array(
                    'coyote_box_enable' => 'yes',
                ),
            )
        );
        
        $element->add_control(
            'coyote_box_option_3',
            array(
                'label' => __('<strong>Option 3 - Dynamic System</strong>', 'nivaro'),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'label_on' => __('On', 'nivaro'),
                'label_off' => __('Off', 'nivaro'),
                'return_value' => 'yes',
                'default' => '',
                'condition' => array(
                    'coyote_box_enable' => 'yes',
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
        
        // Get background image URL
        $bg_image_url = '';
        
        // First try to get from zen service
        if (!empty($settings['coyote_box_service_id'])) {
            $bg_image_url = $this->database->get_service_image_url($settings['coyote_box_service_id'], 'full');
        }
        
        // If no service image, use fallback
        if (empty($bg_image_url) && !empty($settings['coyote_box_fallback_image']['url'])) {
            $bg_image_url = $settings['coyote_box_fallback_image']['url'];
        }
        
        // Apply dynamic background
        if (!empty($bg_image_url)) {
            $element->add_render_attribute('_wrapper', 'style', 
                'background-image: url(' . esc_url($bg_image_url) . ') !important; ' .
                'background-position: top left !important; ' .
                'background-size: cover !important; ' .
                'background-repeat: no-repeat !important;'
            );
        }
        
        // Add CSS classes
        $element->add_render_attribute('_wrapper', 'class', array(
            'coyote-box-container',
            'coyote-box-enabled',
        ));
        
        if (!empty($settings['coyote_box_service_id'])) {
            $element->add_render_attribute('_wrapper', 'class', 'coyote-box-service-' . $settings['coyote_box_service_id']);
        }
        
        // Add data attributes
        $element->add_render_attribute('_wrapper', array(
            'data-coyote-box' => 'enabled',
            'data-coyote-service-id' => $settings['coyote_box_service_id'] ?? '',
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
}