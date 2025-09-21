<?php
/**
 * Nivaro Coyote Box Container Extension
 * 
 * Adds dynamic zen service capabilities to Elementor containers
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
        
        // Apply dynamic backgrounds before render
        add_action('elementor/frontend/container/before_render', array($this, 'apply_coyote_box_background'));
        add_action('elementor/element/container/before_render', array($this, 'apply_coyote_box_background'));
        
        // Add custom CSS classes
        add_action('elementor/frontend/container/before_render', array($this, 'add_coyote_box_classes'));
        
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
        
        // Add Coyote Box section
        $element->start_controls_section(
            'coyote_box_section',
            array(
                'label' => __('Coyote Box Dynamic Background', 'nivaro'),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            )
        );
        
        $element->add_control(
            'coyote_box_enable',
            array(
                'label' => __('Enable Coyote Box', 'nivaro'),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'label_on' => __('Yes', 'nivaro'),
                'label_off' => __('No', 'nivaro'),
                'return_value' => 'yes',
                'default' => '',
                'description' => __('Enable dynamic background from zen services', 'nivaro'),
            )
        );
        
        $element->add_control(
            'coyote_box_service_id',
            array(
                'label' => __('Select Zen Service', 'nivaro'),
                'type' => \Elementor\Controls_Manager::SELECT,
                'options' => $service_options,
                'default' => '',
                'condition' => array(
                    'coyote_box_enable' => 'yes',
                ),
                'description' => __('Select a service to use its image as background', 'nivaro'),
            )
        );
        
        $element->add_control(
            'coyote_box_heading',
            array(
                'label' => __('Coyote Box Heading', 'nivaro'),
                'type' => \Elementor\Controls_Manager::TEXT,
                'default' => '',
                'label_block' => true,
                'condition' => array(
                    'coyote_box_enable' => 'yes',
                ),
                'description' => __('Optional heading to display over the background', 'nivaro'),
            )
        );
        
        $element->add_control(
            'coyote_box_heading_tag',
            array(
                'label' => __('Heading HTML Tag', 'nivaro'),
                'type' => \Elementor\Controls_Manager::SELECT,
                'options' => array(
                    'h1' => 'H1',
                    'h2' => 'H2',
                    'h3' => 'H3',
                    'h4' => 'H4',
                    'h5' => 'H5',
                    'h6' => 'H6',
                    'div' => 'div',
                    'span' => 'span',
                    'p' => 'p',
                ),
                'default' => 'h1',
                'condition' => array(
                    'coyote_box_enable' => 'yes',
                    'coyote_box_heading!' => '',
                ),
            )
        );
        
        $element->add_control(
            'coyote_box_fallback_image',
            array(
                'label' => __('Fallback Background Image', 'nivaro'),
                'type' => \Elementor\Controls_Manager::MEDIA,
                'condition' => array(
                    'coyote_box_enable' => 'yes',
                ),
                'description' => __('Used when no service is selected or service has no image', 'nivaro'),
            )
        );
        
        $element->add_control(
            'coyote_box_overlay',
            array(
                'label' => __('Enable Overlay', 'nivaro'),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'label_on' => __('Yes', 'nivaro'),
                'label_off' => __('No', 'nivaro'),
                'return_value' => 'yes',
                'default' => 'yes',
                'condition' => array(
                    'coyote_box_enable' => 'yes',
                ),
            )
        );
        
        $element->add_control(
            'coyote_box_overlay_color',
            array(
                'label' => __('Overlay Color', 'nivaro'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '#000000',
                'condition' => array(
                    'coyote_box_enable' => 'yes',
                    'coyote_box_overlay' => 'yes',
                ),
            )
        );
        
        $element->add_control(
            'coyote_box_overlay_opacity',
            array(
                'label' => __('Overlay Opacity', 'nivaro'),
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
                    'coyote_box_enable' => 'yes',
                    'coyote_box_overlay' => 'yes',
                ),
            )
        );
        
        $element->add_control(
            'coyote_box_bg_position',
            array(
                'label' => __('Background Position', 'nivaro'),
                'type' => \Elementor\Controls_Manager::SELECT,
                'default' => 'top left',
                'options' => array(
                    'top left' => __('Top Left', 'nivaro'),
                    'top center' => __('Top Center', 'nivaro'),
                    'top right' => __('Top Right', 'nivaro'),
                    'center left' => __('Center Left', 'nivaro'),
                    'center center' => __('Center Center', 'nivaro'),
                    'center right' => __('Center Right', 'nivaro'),
                    'bottom left' => __('Bottom Left', 'nivaro'),
                    'bottom center' => __('Bottom Center', 'nivaro'),
                    'bottom right' => __('Bottom Right', 'nivaro'),
                ),
                'condition' => array(
                    'coyote_box_enable' => 'yes',
                ),
            )
        );
        
        $element->add_control(
            'coyote_box_bg_size',
            array(
                'label' => __('Background Size', 'nivaro'),
                'type' => \Elementor\Controls_Manager::SELECT,
                'default' => 'cover',
                'options' => array(
                    'auto' => __('Auto', 'nivaro'),
                    'cover' => __('Cover', 'nivaro'),
                    'contain' => __('Contain', 'nivaro'),
                ),
                'condition' => array(
                    'coyote_box_enable' => 'yes',
                ),
            )
        );
        
        // Heading styling controls
        $element->add_control(
            'coyote_box_heading_style',
            array(
                'label' => __('Heading Style', 'nivaro'),
                'type' => \Elementor\Controls_Manager::HEADING,
                'separator' => 'before',
                'condition' => array(
                    'coyote_box_enable' => 'yes',
                    'coyote_box_heading!' => '',
                ),
            )
        );
        
        $element->add_control(
            'coyote_box_heading_color',
            array(
                'label' => __('Heading Color', 'nivaro'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '#FFFFFF',
                'condition' => array(
                    'coyote_box_enable' => 'yes',
                    'coyote_box_heading!' => '',
                ),
            )
        );
        
        $element->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            array(
                'name' => 'coyote_box_heading_typography',
                'label' => __('Typography', 'nivaro'),
                'selector' => '{{WRAPPER}} .coyote-box-heading',
                'condition' => array(
                    'coyote_box_enable' => 'yes',
                    'coyote_box_heading!' => '',
                ),
                'fields_options' => array(
                    'typography' => array('default' => 'yes'),
                    'font_family' => array('default' => 'Poppins'),
                    'font_size' => array('default' => array('size' => 64, 'unit' => 'px')),
                    'font_weight' => array('default' => '600'),
                    'line_height' => array('default' => array('size' => 1, 'unit' => 'em')),
                    'letter_spacing' => array('default' => array('size' => -1.928, 'unit' => 'px')),
                    'text_transform' => array('default' => 'capitalize'),
                ),
            )
        );
        
        $element->add_responsive_control(
            'coyote_box_heading_align',
            array(
                'label' => __('Heading Alignment', 'nivaro'),
                'type' => \Elementor\Controls_Manager::CHOOSE,
                'options' => array(
                    'left' => array(
                        'title' => __('Left', 'nivaro'),
                        'icon' => 'eicon-text-align-left',
                    ),
                    'center' => array(
                        'title' => __('Center', 'nivaro'),
                        'icon' => 'eicon-text-align-center',
                    ),
                    'right' => array(
                        'title' => __('Right', 'nivaro'),
                        'icon' => 'eicon-text-align-right',
                    ),
                ),
                'default' => 'center',
                'condition' => array(
                    'coyote_box_enable' => 'yes',
                    'coyote_box_heading!' => '',
                ),
            )
        );
        
        $element->end_controls_section();
    }
    
    /**
     * Apply dynamic background before render
     */
    public function apply_coyote_box_background($element) {
        $settings = $element->get_settings();
        
        // Check if Coyote Box is enabled
        if (empty($settings['coyote_box_enable']) || $settings['coyote_box_enable'] !== 'yes') {
            return;
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
        
        // Apply dynamic styles
        if (!empty($bg_image_url)) {
            $bg_position = $settings['coyote_box_bg_position'] ?? 'top left';
            $bg_size = $settings['coyote_box_bg_size'] ?? 'cover';
            
            // Add inline styles for background
            $element->add_render_attribute('_wrapper', 'style', 
                'background-image: url(' . esc_url($bg_image_url) . '); ' .
                'background-position: ' . $bg_position . '; ' .
                'background-size: ' . $bg_size . '; ' .
                'background-repeat: no-repeat;'
            );
        }
        
        // Add data attributes for JavaScript if needed
        $element->add_render_attribute('_wrapper', array(
            'data-coyote-box' => 'enabled',
            'data-coyote-service-id' => $settings['coyote_box_service_id'] ?? '',
            'data-coyote-has-overlay' => $settings['coyote_box_overlay'] ?? '',
        ));
        
        // Add overlay HTML if enabled
        if (!empty($settings['coyote_box_overlay']) && $settings['coyote_box_overlay'] === 'yes') {
            $overlay_color = $settings['coyote_box_overlay_color'] ?? '#000000';
            $overlay_opacity = $settings['coyote_box_overlay_opacity']['size'] ?? 0.7;
            
            add_action('elementor/frontend/container/after_render', function() use ($overlay_color, $overlay_opacity, $settings) {
                ?>
                <div class="coyote-box-overlay" style="
                    position: absolute;
                    top: 0;
                    left: 0;
                    right: 0;
                    bottom: 0;
                    background-color: <?php echo esc_attr($overlay_color); ?>;
                    opacity: <?php echo esc_attr($overlay_opacity); ?>;
                    pointer-events: none;
                    z-index: 1;
                "></div>
                <?php
                
                // Add heading if provided
                if (!empty($settings['coyote_box_heading'])) {
                    $heading_tag = $settings['coyote_box_heading_tag'] ?? 'h1';
                    $heading_color = $settings['coyote_box_heading_color'] ?? '#FFFFFF';
                    $heading_align = $settings['coyote_box_heading_align'] ?? 'center';
                    ?>
                    <div class="coyote-box-heading-wrapper" style="
                        position: relative;
                        z-index: 2;
                        text-align: <?php echo esc_attr($heading_align); ?>;
                        width: 100%;
                    ">
                        <<?php echo esc_html($heading_tag); ?> class="coyote-box-heading" style="
                            color: <?php echo esc_attr($heading_color); ?>;
                        ">
                            <?php echo esc_html($settings['coyote_box_heading']); ?>
                        </<?php echo esc_html($heading_tag); ?>>
                    </div>
                    <?php
                }
            }, 10, 0);
        }
    }
    
    /**
     * Add CSS classes to container
     */
    public function add_coyote_box_classes($element) {
        $settings = $element->get_settings();
        
        if (!empty($settings['coyote_box_enable']) && $settings['coyote_box_enable'] === 'yes') {
            $element->add_render_attribute('_wrapper', 'class', 'coyote-box-container');
            
            if (!empty($settings['coyote_box_service_id'])) {
                $element->add_render_attribute('_wrapper', 'class', 'coyote-box-service-' . $settings['coyote_box_service_id']);
            }
            
            if (!empty($settings['coyote_box_overlay']) && $settings['coyote_box_overlay'] === 'yes') {
                $element->add_render_attribute('_wrapper', 'class', 'coyote-box-has-overlay');
            }
        }
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
    }
}