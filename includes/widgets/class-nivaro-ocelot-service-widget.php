<?php
/**
 * Nivaro Ocelot Service Widget
 * 
 * Dynamic service box widget for displaying zen services
 */

if (!defined('ABSPATH')) {
    exit;
}

class Nivaro_Ocelot_Service_Widget extends \Elementor\Widget_Base {
    
    private $database;
    
    public function __construct($data = [], $args = null) {
        parent::__construct($data, $args);
        
        // Initialize database handler
        if (class_exists('Nivaro_Database')) {
            $this->database = new Nivaro_Database();
        }
    }
    
    public function get_name() {
        return 'nivaro-ocelot-service';
    }
    
    public function get_title() {
        return __('Ocelot Service Box', 'nivaro');
    }
    
    public function get_icon() {
        return 'eicon-info-box';
    }
    
    public function get_categories() {
        return ['general'];
    }
    
    public function get_keywords() {
        return ['ocelot', 'service', 'box', 'zen', 'nivaro', 'dynamic'];
    }
    
    protected function register_controls() {
        
        // Content Section
        $this->start_controls_section(
            'content_section',
            [
                'label' => __('Service Selection', 'nivaro'),
                'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
            ]
        );
        
        // Service selection mode
        $this->add_control(
            'service_mode',
            [
                'label' => __('Service Selection Mode', 'nivaro'),
                'type' => \Elementor\Controls_Manager::SELECT,
                'options' => [
                    'auto' => __('Auto (Use with Coyote Option 4)', 'nivaro'),
                    'manual' => __('Manual Selection', 'nivaro'),
                ],
                'default' => 'auto',
                'description' => __('Auto mode works with Coyote Box Option 4 to automatically populate services', 'nivaro'),
            ]
        );
        
        // Manual service selection
        $service_options = $this->database ? $this->database->get_service_options() : [];
        
        $this->add_control(
            'selected_service',
            [
                'label' => __('Select Service', 'nivaro'),
                'type' => \Elementor\Controls_Manager::SELECT,
                'options' => $service_options,
                'default' => '',
                'condition' => [
                    'service_mode' => 'manual',
                ],
            ]
        );
        
        // Auto mode index (hidden from user, set programmatically)
        $this->add_control(
            'auto_index',
            [
                'label' => __('Auto Index', 'nivaro'),
                'type' => \Elementor\Controls_Manager::HIDDEN,
                'default' => '0',
                'condition' => [
                    'service_mode' => 'auto',
                ],
            ]
        );
        
        // Link behavior
        $this->add_control(
            'link_behavior',
            [
                'label' => __('Link Behavior', 'nivaro'),
                'type' => \Elementor\Controls_Manager::SELECT,
                'options' => [
                    'page' => __('Link to Service Page', 'nivaro'),
                    'none' => __('No Link', 'nivaro'),
                    'custom' => __('Custom URL', 'nivaro'),
                ],
                'default' => 'page',
            ]
        );
        
        $this->add_control(
            'custom_url',
            [
                'label' => __('Custom URL', 'nivaro'),
                'type' => \Elementor\Controls_Manager::URL,
                'placeholder' => __('https://your-link.com', 'nivaro'),
                'default' => [
                    'url' => '',
                ],
                'condition' => [
                    'link_behavior' => 'custom',
                ],
            ]
        );
        
        $this->end_controls_section();
        
        // Display Settings Section
        $this->start_controls_section(
            'display_section',
            [
                'label' => __('Display Settings', 'nivaro'),
                'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
            ]
        );
        
        $this->add_control(
            'show_image',
            [
                'label' => __('Show Featured Image', 'nivaro'),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'label_on' => __('Show', 'nivaro'),
                'label_off' => __('Hide', 'nivaro'),
                'return_value' => 'yes',
                'default' => 'yes',
            ]
        );
        
        $this->add_control(
            'show_title',
            [
                'label' => __('Show Title', 'nivaro'),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'label_on' => __('Show', 'nivaro'),
                'label_off' => __('Hide', 'nivaro'),
                'return_value' => 'yes',
                'default' => 'yes',
            ]
        );
        
        $this->add_control(
            'show_description',
            [
                'label' => __('Show Description', 'nivaro'),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'label_on' => __('Show', 'nivaro'),
                'label_off' => __('Hide', 'nivaro'),
                'return_value' => 'yes',
                'default' => 'yes',
            ]
        );
        
        $this->add_control(
            'show_button',
            [
                'label' => __('Show Button', 'nivaro'),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'label_on' => __('Show', 'nivaro'),
                'label_off' => __('Hide', 'nivaro'),
                'return_value' => 'yes',
                'default' => 'yes',
            ]
        );
        
        $this->add_control(
            'button_text',
            [
                'label' => __('Button Text', 'nivaro'),
                'type' => \Elementor\Controls_Manager::TEXT,
                'default' => __('Learn More', 'nivaro'),
                'placeholder' => __('Learn More', 'nivaro'),
                'condition' => [
                    'show_button' => 'yes',
                ],
            ]
        );
        
        $this->end_controls_section();
        
        // Style Section - Box
        $this->start_controls_section(
            'box_style_section',
            [
                'label' => __('Box Style', 'nivaro'),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );
        
        $this->add_control(
            'box_background',
            [
                'label' => __('Background Color', 'nivaro'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '#ffffff',
                'selectors' => [
                    '{{WRAPPER}} .ocelot-service-box' => 'background-color: {{VALUE}};',
                ],
            ]
        );
        
        $this->add_group_control(
            \Elementor\Group_Control_Border::get_type(),
            [
                'name' => 'box_border',
                'selector' => '{{WRAPPER}} .ocelot-service-box',
            ]
        );
        
        $this->add_control(
            'box_border_radius',
            [
                'label' => __('Border Radius', 'nivaro'),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%'],
                'default' => [
                    'top' => 8,
                    'right' => 8,
                    'bottom' => 8,
                    'left' => 8,
                    'unit' => 'px',
                ],
                'selectors' => [
                    '{{WRAPPER}} .ocelot-service-box' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    '{{WRAPPER}} .ocelot-service-image img' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} 0 0;',
                ],
            ]
        );
        
        $this->add_group_control(
            \Elementor\Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'box_shadow',
                'selector' => '{{WRAPPER}} .ocelot-service-box',
            ]
        );
        
        $this->add_responsive_control(
            'box_padding',
            [
                'label' => __('Padding', 'nivaro'),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', '%'],
                'default' => [
                    'top' => 20,
                    'right' => 20,
                    'bottom' => 20,
                    'left' => 20,
                    'unit' => 'px',
                ],
                'selectors' => [
                    '{{WRAPPER}} .ocelot-service-content' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        
        $this->end_controls_section();
        
        // Style Section - Image
        $this->start_controls_section(
            'image_style_section',
            [
                'label' => __('Image Style', 'nivaro'),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
                'condition' => [
                    'show_image' => 'yes',
                ],
            ]
        );
        
        $this->add_responsive_control(
            'image_height',
            [
                'label' => __('Height', 'nivaro'),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => ['px', 'vh'],
                'range' => [
                    'px' => [
                        'min' => 100,
                        'max' => 500,
                    ],
                ],
                'default' => [
                    'unit' => 'px',
                    'size' => 200,
                ],
                'selectors' => [
                    '{{WRAPPER}} .ocelot-service-image' => 'height: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .ocelot-service-image img' => 'height: {{SIZE}}{{UNIT}}; object-fit: cover;',
                ],
            ]
        );
        
        $this->end_controls_section();
        
        // Style Section - Title
        $this->start_controls_section(
            'title_style_section',
            [
                'label' => __('Title Style', 'nivaro'),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
                'condition' => [
                    'show_title' => 'yes',
                ],
            ]
        );
        
        $this->add_control(
            'title_color',
            [
                'label' => __('Color', 'nivaro'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '#333333',
                'selectors' => [
                    '{{WRAPPER}} .ocelot-service-title' => 'color: {{VALUE}};',
                ],
            ]
        );
        
        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'title_typography',
                'selector' => '{{WRAPPER}} .ocelot-service-title',
            ]
        );
        
        $this->add_responsive_control(
            'title_margin',
            [
                'label' => __('Margin', 'nivaro'),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em'],
                'default' => [
                    'top' => 0,
                    'right' => 0,
                    'bottom' => 10,
                    'left' => 0,
                    'unit' => 'px',
                ],
                'selectors' => [
                    '{{WRAPPER}} .ocelot-service-title' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        
        $this->end_controls_section();
        
        // Style Section - Description
        $this->start_controls_section(
            'description_style_section',
            [
                'label' => __('Description Style', 'nivaro'),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
                'condition' => [
                    'show_description' => 'yes',
                ],
            ]
        );
        
        $this->add_control(
            'description_color',
            [
                'label' => __('Color', 'nivaro'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '#666666',
                'selectors' => [
                    '{{WRAPPER}} .ocelot-service-description' => 'color: {{VALUE}};',
                ],
            ]
        );
        
        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'description_typography',
                'selector' => '{{WRAPPER}} .ocelot-service-description',
            ]
        );
        
        $this->end_controls_section();
        
        // Style Section - Button
        $this->start_controls_section(
            'button_style_section',
            [
                'label' => __('Button Style', 'nivaro'),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
                'condition' => [
                    'show_button' => 'yes',
                ],
            ]
        );
        
        $this->start_controls_tabs('button_tabs');
        
        $this->start_controls_tab(
            'button_normal_tab',
            [
                'label' => __('Normal', 'nivaro'),
            ]
        );
        
        $this->add_control(
            'button_color',
            [
                'label' => __('Text Color', 'nivaro'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '#ffffff',
                'selectors' => [
                    '{{WRAPPER}} .ocelot-service-button' => 'color: {{VALUE}};',
                ],
            ]
        );
        
        $this->add_control(
            'button_background',
            [
                'label' => __('Background Color', 'nivaro'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '#007cba',
                'selectors' => [
                    '{{WRAPPER}} .ocelot-service-button' => 'background-color: {{VALUE}};',
                ],
            ]
        );
        
        $this->end_controls_tab();
        
        $this->start_controls_tab(
            'button_hover_tab',
            [
                'label' => __('Hover', 'nivaro'),
            ]
        );
        
        $this->add_control(
            'button_hover_color',
            [
                'label' => __('Text Color', 'nivaro'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .ocelot-service-button:hover' => 'color: {{VALUE}};',
                ],
            ]
        );
        
        $this->add_control(
            'button_hover_background',
            [
                'label' => __('Background Color', 'nivaro'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .ocelot-service-button:hover' => 'background-color: {{VALUE}};',
                ],
            ]
        );
        
        $this->end_controls_tab();
        
        $this->end_controls_tabs();
        
        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'button_typography',
                'selector' => '{{WRAPPER}} .ocelot-service-button',
            ]
        );
        
        $this->add_responsive_control(
            'button_padding',
            [
                'label' => __('Padding', 'nivaro'),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em'],
                'default' => [
                    'top' => 10,
                    'right' => 20,
                    'bottom' => 10,
                    'left' => 20,
                    'unit' => 'px',
                ],
                'selectors' => [
                    '{{WRAPPER}} .ocelot-service-button' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        
        $this->add_control(
            'button_border_radius',
            [
                'label' => __('Border Radius', 'nivaro'),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%'],
                'default' => [
                    'top' => 4,
                    'right' => 4,
                    'bottom' => 4,
                    'left' => 4,
                    'unit' => 'px',
                ],
                'selectors' => [
                    '{{WRAPPER}} .ocelot-service-button' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        
        $this->end_controls_section();
    }
    
    /**
     * Get service data based on widget settings
     */
    private function get_service_data() {
        if (!$this->database) {
            return null;
        }
        
        $settings = $this->get_settings_for_display();
        $service_id = null;
        
        if ($settings['service_mode'] === 'manual' && !empty($settings['selected_service'])) {
            $service_id = $settings['selected_service'];
        } elseif ($settings['service_mode'] === 'auto') {
            // Auto mode - get service by index
            $services = $this->database->get_ocelot_services();
            $index = intval($settings['auto_index']);
            
            if (!empty($services) && isset($services[$index])) {
                $service_id = $services[$index]->service_id;
            }
        }
        
        if ($service_id) {
            return $this->database->get_service_by_id($service_id);
        }
        
        return null;
    }
    
    protected function render() {
        $settings = $this->get_settings_for_display();
        $service = $this->get_service_data();
        
        // If no service data, show placeholder in editor
        if (!$service && \Elementor\Plugin::$instance->editor->is_edit_mode()) {
            ?>
            <div class="ocelot-service-box ocelot-placeholder">
                <div class="ocelot-service-content">
                    <div style="padding: 40px; text-align: center; background: #f0f0f0; border: 2px dashed #ccc;">
                        <strong>Ocelot Service Box</strong><br>
                        <small>No service selected or available</small>
                    </div>
                </div>
            </div>
            <?php
            return;
        }
        
        // Don't render anything on frontend if no service
        if (!$service) {
            return;
        }
        
        // Get image URL
        $image_url = '';
        if ($settings['show_image'] === 'yes' && !empty($service->rel_image1_id)) {
            $image_url = wp_get_attachment_image_url($service->rel_image1_id, 'large');
        }
        
        // Get link URL
        $link_url = '';
        $link_target = '_self';
        
        if ($settings['link_behavior'] === 'page' && !empty($service->asn_service_page_id)) {
            $link_url = get_permalink($service->asn_service_page_id);
        } elseif ($settings['link_behavior'] === 'custom' && !empty($settings['custom_url']['url'])) {
            $link_url = $settings['custom_url']['url'];
            $link_target = $settings['custom_url']['is_external'] ? '_blank' : '_self';
        }
        
        ?>
        <div class="ocelot-service-box">
            <?php if ($settings['show_image'] === 'yes' && $image_url): ?>
                <div class="ocelot-service-image">
                    <?php if ($link_url): ?>
                        <a href="<?php echo esc_url($link_url); ?>" target="<?php echo esc_attr($link_target); ?>">
                            <img src="<?php echo esc_url($image_url); ?>" alt="<?php echo esc_attr($service->service_name); ?>">
                        </a>
                    <?php else: ?>
                        <img src="<?php echo esc_url($image_url); ?>" alt="<?php echo esc_attr($service->service_name); ?>">
                    <?php endif; ?>
                </div>
            <?php endif; ?>
            
            <div class="ocelot-service-content">
                <?php if ($settings['show_title'] === 'yes' && !empty($service->service_name)): ?>
                    <h3 class="ocelot-service-title">
                        <?php if ($link_url): ?>
                            <a href="<?php echo esc_url($link_url); ?>" target="<?php echo esc_attr($link_target); ?>">
                                <?php echo esc_html($service->service_name); ?>
                            </a>
                        <?php else: ?>
                            <?php echo esc_html($service->service_name); ?>
                        <?php endif; ?>
                    </h3>
                <?php endif; ?>
                
                <?php if ($settings['show_description'] === 'yes' && !empty($service->service_description)): ?>
                    <div class="ocelot-service-description">
                        <?php echo wp_kses_post($service->service_description); ?>
                    </div>
                <?php endif; ?>
                
                <?php if ($settings['show_button'] === 'yes' && $link_url): ?>
                    <a href="<?php echo esc_url($link_url); ?>" 
                       target="<?php echo esc_attr($link_target); ?>" 
                       class="ocelot-service-button">
                        <?php echo esc_html($settings['button_text']); ?>
                    </a>
                <?php endif; ?>
            </div>
        </div>
        <?php
    }
    
    protected function content_template() {
        ?>
        <# 
        // This template is for the editor preview
        // It will show a placeholder since we can't access PHP data here
        #>
        <div class="ocelot-service-box">
            <# if (settings.show_image === 'yes') { #>
                <div class="ocelot-service-image">
                    <div style="height: 200px; background: #e0e0e0; display: flex; align-items: center; justify-content: center;">
                        <span style="color: #999;">[Service Image]</span>
                    </div>
                </div>
            <# } #>
            
            <div class="ocelot-service-content">
                <# if (settings.show_title === 'yes') { #>
                    <h3 class="ocelot-service-title">[Service Title]</h3>
                <# } #>
                
                <# if (settings.show_description === 'yes') { #>
                    <div class="ocelot-service-description">
                        [Service description will appear here]
                    </div>
                <# } #>
                
                <# if (settings.show_button === 'yes') { #>
                    <a href="#" class="ocelot-service-button">{{{ settings.button_text }}}</a>
                <# } #>
            </div>
        </div>
        <?php
    }
}