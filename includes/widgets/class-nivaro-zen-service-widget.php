<?php
/**
 * Nivaro Zen Service Widget
 * 
 * Custom Elementor widget for zen services with dynamic backgrounds
 */

if (!defined('ABSPATH')) {
    exit;
}

class Nivaro_Zen_Service_Widget extends \Elementor\Widget_Base {
    
    private $database;
    
    public function __construct($data = [], $args = null) {
        parent::__construct($data, $args);
        $this->database = new Nivaro_Database();
    }
    
    public function get_name() {
        return 'nivaro-zen-service';
    }
    
    public function get_title() {
        return __('Zen Service Container', 'nivaro');
    }
    
    public function get_icon() {
        return 'eicon-background';
    }
    
    public function get_categories() {
        return ['general'];
    }
    
    public function get_keywords() {
        return ['zen', 'service', 'background', 'dynamic', 'nivaro'];
    }
    
    protected function register_controls() {
        
        // Content Section
        $this->start_controls_section(
            'content_section',
            [
                'label' => __('Content', 'nivaro'),
                'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
            ]
        );
        
        $this->add_control(
            'heading_text',
            [
                'label' => __('Heading', 'nivaro'),
                'type' => \Elementor\Controls_Manager::TEXT,
                'default' => __('Default Heading', 'nivaro'),
                'placeholder' => __('Type your heading here', 'nivaro'),
            ]
        );
        
        $this->add_control(
            'subheading_text',
            [
                'label' => __('Subheading', 'nivaro'),
                'type' => \Elementor\Controls_Manager::TEXT,
                'default' => __('Default Subheading', 'nivaro'),
                'placeholder' => __('Type your subheading here', 'nivaro'),
            ]
        );
        
        $this->end_controls_section();
        
        // Dynamic Service Section
        $this->start_controls_section(
            'dynamic_service_section',
            [
                'label' => __('Dynamic Service', 'nivaro'),
                'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
            ]
        );
        
        $service_options = $this->database->get_service_options();
        
        $this->add_control(
            'zen_service_id',
            [
                'label' => __('Select Zen Service', 'nivaro'),
                'type' => \Elementor\Controls_Manager::SELECT,
                'options' => $service_options,
                'default' => '',
                'description' => __('Choose a service to automatically use its image as background', 'nivaro'),
            ]
        );
        
        $this->add_control(
            'background_size',
            [
                'label' => __('Background Size', 'nivaro'),
                'type' => \Elementor\Controls_Manager::SELECT,
                'options' => [
                    'cover' => __('Cover', 'nivaro'),
                    'contain' => __('Contain', 'nivaro'),
                    'auto' => __('Auto', 'nivaro'),
                    '100% 100%' => __('Stretch', 'nivaro'),
                ],
                'default' => 'cover',
                'condition' => [
                    'zen_service_id!' => '',
                ],
            ]
        );
        
        $this->add_control(
            'background_position',
            [
                'label' => __('Background Position', 'nivaro'),
                'type' => \Elementor\Controls_Manager::SELECT,
                'options' => [
                    'center center' => __('Center Center', 'nivaro'),
                    'center top' => __('Center Top', 'nivaro'),
                    'center bottom' => __('Center Bottom', 'nivaro'),
                    'left top' => __('Left Top', 'nivaro'),
                    'left center' => __('Left Center', 'nivaro'),
                    'left bottom' => __('Left Bottom', 'nivaro'),
                    'right top' => __('Right Top', 'nivaro'),
                    'right center' => __('Right Center', 'nivaro'),
                    'right bottom' => __('Right Bottom', 'nivaro'),
                ],
                'default' => 'center center',
                'condition' => [
                    'zen_service_id!' => '',
                ],
            ]
        );
        
        $this->add_control(
            'background_repeat',
            [
                'label' => __('Background Repeat', 'nivaro'),
                'type' => \Elementor\Controls_Manager::SELECT,
                'options' => [
                    'no-repeat' => __('No Repeat', 'nivaro'),
                    'repeat' => __('Repeat', 'nivaro'),
                    'repeat-x' => __('Repeat X', 'nivaro'),
                    'repeat-y' => __('Repeat Y', 'nivaro'),
                ],
                'default' => 'no-repeat',
                'condition' => [
                    'zen_service_id!' => '',
                ],
            ]
        );
        
        $this->add_control(
            'overlay_enable',
            [
                'label' => __('Enable Overlay', 'nivaro'),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'label_on' => __('Yes', 'nivaro'),
                'label_off' => __('No', 'nivaro'),
                'return_value' => 'yes',
                'default' => '',
                'condition' => [
                    'zen_service_id!' => '',
                ],
            ]
        );
        
        $this->add_control(
            'overlay_color',
            [
                'label' => __('Overlay Color', 'nivaro'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => 'rgba(0, 0, 0, 0.5)',
                'condition' => [
                    'zen_service_id!' => '',
                    'overlay_enable' => 'yes',
                ],
            ]
        );
        
        $this->end_controls_section();
        
        // Style Section - Container
        $this->start_controls_section(
            'container_style_section',
            [
                'label' => __('Container', 'nivaro'),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );
        
        $this->add_responsive_control(
            'container_min_height',
            [
                'label' => __('Min Height', 'nivaro'),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => ['px', 'vh', '%'],
                'range' => [
                    'px' => [
                        'min' => 100,
                        'max' => 1000,
                        'step' => 10,
                    ],
                    'vh' => [
                        'min' => 10,
                        'max' => 100,
                        'step' => 1,
                    ],
                    '%' => [
                        'min' => 10,
                        'max' => 100,
                        'step' => 1,
                    ],
                ],
                'default' => [
                    'unit' => 'px',
                    'size' => 300,
                ],
                'selectors' => [
                    '{{WRAPPER}} .nivaro-zen-service-container' => 'min-height: {{SIZE}}{{UNIT}};',
                ],
            ]
        );
        
        $this->add_responsive_control(
            'container_padding',
            [
                'label' => __('Padding', 'nivaro'),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', '%'],
                'default' => [
                    'top' => '40',
                    'right' => '40',
                    'bottom' => '40',
                    'left' => '40',
                    'unit' => 'px',
                    'isLinked' => true,
                ],
                'selectors' => [
                    '{{WRAPPER}} .nivaro-zen-service-container' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        
        $this->add_control(
            'container_border_radius',
            [
                'label' => __('Border Radius', 'nivaro'),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%'],
                'selectors' => [
                    '{{WRAPPER}} .nivaro-zen-service-container' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        
        $this->end_controls_section();
        
        // Style Section - Heading
        $this->start_controls_section(
            'heading_style_section',
            [
                'label' => __('Heading', 'nivaro'),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );
        
        $this->add_control(
            'heading_color',
            [
                'label' => __('Text Color', 'nivaro'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '#ffffff',
                'selectors' => [
                    '{{WRAPPER}} .nivaro-heading' => 'color: {{VALUE}};',
                ],
            ]
        );
        
        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'heading_typography',
                'selector' => '{{WRAPPER}} .nivaro-heading',
            ]
        );
        
        $this->add_responsive_control(
            'heading_margin',
            [
                'label' => __('Margin', 'nivaro'),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em'],
                'selectors' => [
                    '{{WRAPPER}} .nivaro-heading' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        
        $this->end_controls_section();
        
        // Style Section - Subheading
        $this->start_controls_section(
            'subheading_style_section',
            [
                'label' => __('Subheading', 'nivaro'),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );
        
        $this->add_control(
            'subheading_color',
            [
                'label' => __('Text Color', 'nivaro'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '#ffffff',
                'selectors' => [
                    '{{WRAPPER}} .nivaro-subheading' => 'color: {{VALUE}};',
                ],
            ]
        );
        
        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'subheading_typography',
                'selector' => '{{WRAPPER}} .nivaro-subheading',
            ]
        );
        
        $this->add_responsive_control(
            'subheading_margin',
            [
                'label' => __('Margin', 'nivaro'),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em'],
                'selectors' => [
                    '{{WRAPPER}} .nivaro-subheading' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        
        $this->end_controls_section();
    }
    
    protected function render() {
        $settings = $this->get_settings_for_display();
        
        // Get background image if service is selected
        $background_image_url = '';
        if (!empty($settings['zen_service_id'])) {
            $background_image_url = $this->database->get_service_image_url($settings['zen_service_id'], 'full');
        }
        
        // Build container styles
        $container_styles = array();
        
        if (!empty($background_image_url)) {
            $bg_size = $settings['background_size'] ?? 'cover';
            $bg_position = $settings['background_position'] ?? 'center center';
            $bg_repeat = $settings['background_repeat'] ?? 'no-repeat';
            
            $container_styles[] = "background-image: url('{$background_image_url}')";
            $container_styles[] = "background-size: {$bg_size}";
            $container_styles[] = "background-position: {$bg_position}";
            $container_styles[] = "background-repeat: {$bg_repeat}";
            $container_styles[] = "background-attachment: scroll";
        }
        
        $container_style_attr = !empty($container_styles) ? 'style="' . implode('; ', $container_styles) . '"' : '';
        
        // Container classes
        $container_classes = array('nivaro-zen-service-container');
        if (!empty($settings['zen_service_id'])) {
            $container_classes[] = 'nivaro-has-background';
            $container_classes[] = 'nivaro-service-' . $settings['zen_service_id'];
        }
        
        ?>
        <div class="<?php echo esc_attr(implode(' ', $container_classes)); ?>" <?php echo $container_style_attr; ?>>
            
            <?php if (!empty($settings['overlay_enable']) && $settings['overlay_enable'] === 'yes' && !empty($settings['overlay_color'])): ?>
                <div class="nivaro-overlay" style="position: absolute; top: 0; left: 0; right: 0; bottom: 0; background-color: <?php echo esc_attr($settings['overlay_color']); ?>; pointer-events: none; z-index: 1;"></div>
            <?php endif; ?>
            
            <div class="nivaro-content" style="position: relative; z-index: 2;">
                <?php if (!empty($settings['heading_text'])): ?>
                    <h2 class="nivaro-heading"><?php echo esc_html($settings['heading_text']); ?></h2>
                <?php endif; ?>
                
                <?php if (!empty($settings['subheading_text'])): ?>
                    <div class="nivaro-subheading"><?php echo esc_html($settings['subheading_text']); ?></div>
                <?php endif; ?>
            </div>
            
        </div>
        
        <?php if (\Elementor\Plugin::$instance->editor->is_edit_mode() && empty($background_image_url) && !empty($settings['zen_service_id'])): ?>
            <div style="padding: 10px; background: #f0f0f0; border: 1px dashed #ccc; text-align: center; color: #666;">
                <strong>Nivaro:</strong> Service <?php echo esc_html($settings['zen_service_id']); ?> - No image found or service doesn't exist
            </div>
        <?php endif; ?>
        
        <?php
    }
    
    protected function content_template() {
        ?>
        <#
        var containerClasses = ['nivaro-zen-service-container'];
        var containerStyles = [];
        
        if (settings.zen_service_id) {
            containerClasses.push('nivaro-has-background');
            containerClasses.push('nivaro-service-' + settings.zen_service_id);
        }
        
        var containerStyleAttr = containerStyles.length ? 'style="' + containerStyles.join('; ') + '"' : '';
        #>
        
        <div class="{{ containerClasses.join(' ') }}" {{{ containerStyleAttr }}}>
            
            <# if (settings.overlay_enable === 'yes' && settings.overlay_color) { #>
                <div class="nivaro-overlay" style="position: absolute; top: 0; left: 0; right: 0; bottom: 0; background-color: {{ settings.overlay_color }}; pointer-events: none; z-index: 1;"></div>
            <# } #>
            
            <div class="nivaro-content" style="position: relative; z-index: 2;">
                <# if (settings.heading_text) { #>
                    <h2 class="nivaro-heading">{{{ settings.heading_text }}}</h2>
                <# } #>
                
                <# if (settings.subheading_text) { #>
                    <div class="nivaro-subheading">{{{ settings.subheading_text }}}</div>
                <# } #>
            </div>
            
        </div>
        
        <# if (settings.zen_service_id && !settings.zen_service_id == '') { #>
            <div style="padding: 10px; background: #e8f4f8; border: 1px dashed #0073aa; text-align: center; color: #0073aa; margin-top: 10px;">
                <strong>Nivaro Preview:</strong> Service {{ settings.zen_service_id }} selected - Background will load on frontend
            </div>
        <# } #>
        
        <?php
    }
}