<?php
/**
 * Nivaro Leatherback Widget
 * 
 * Companion widget for Armadillo Setup (Option 5) containing 8 sub-widgets
 * Each sub-widget displays a complete service box with individual controls
 */

if (!defined('ABSPATH')) {
    exit;
}

class Nivaro_Leatherback_Widget extends \Elementor\Widget_Base {
    
    private $database;
    
    public function __construct($data = [], $args = null) {
        parent::__construct($data, $args);
        $this->database = new Nivaro_Database();
    }
    
    public function get_name() {
        return 'nivaro-leatherback';
    }
    
    public function get_title() {
        return __('🐢 Leatherback Services', 'nivaro');
    }
    
    public function get_icon() {
        return 'eicon-gallery-grid';
    }
    
    public function get_categories() {
        return ['nivaro'];
    }
    
    public function get_keywords() {
        return ['services', 'leatherback', 'armadillo', 'grid', 'nivaro'];
    }
    
    protected function register_controls() {
        
        // Content Section
        $this->start_controls_section(
            'content_section',
            [
                'label' => __('Leatherback Settings', 'nivaro'),
                'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
            ]
        );
        
        $this->add_control(
            'leatherback_note',
            [
                'type' => \Elementor\Controls_Manager::RAW_HTML,
                'raw' => __('<strong>🐢 Leatherback Widget</strong><br>This widget contains 8 individual service boxes that you can customize separately. Perfect for use with Armadillo Setup (Option 5) in Coyote Box containers.', 'nivaro'),
                'content_classes' => 'elementor-panel-alert elementor-panel-alert-info',
            ]
        );
        
        $this->add_control(
            'box_count',
            [
                'label' => __('Number of Service Boxes', 'nivaro'),
                'type' => \Elementor\Controls_Manager::NUMBER,
                'default' => 8,
                'min' => 1,
                'max' => 12,
                'description' => __('How many service boxes to display (1-12)', 'nivaro'),
            ]
        );
        
        $this->end_controls_section();
        
        // Service Options
        $service_options = $this->database->get_service_options();
        
        // Individual Service Box Controls (8 boxes)
        for ($i = 1; $i <= 8; $i++) {
            $this->start_controls_section(
                "service_box_{$i}_section",
                [
                    'label' => __("Service Box {$i}", 'nivaro'),
                    'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
                    'condition' => [
                        'box_count' => range($i, 12)
                    ],
                ]
            );
            
            $this->add_control(
                "service_{$i}_mode",
                [
                    'label' => __('Content Mode', 'nivaro'),
                    'type' => \Elementor\Controls_Manager::SELECT,
                    'default' => 'auto',
                    'options' => [
                        'auto' => __('Auto (Database)', 'nivaro'),
                        'manual' => __('Manual Entry', 'nivaro'),
                    ],
                ]
            );
            
            $this->add_control(
                "service_{$i}_auto_id",
                [
                    'label' => __('Select Service', 'nivaro'),
                    'type' => \Elementor\Controls_Manager::SELECT,
                    'options' => $service_options,
                    'default' => '',
                    'condition' => [
                        "service_{$i}_mode" => 'auto',
                    ],
                ]
            );
            
            $this->add_control(
                "service_{$i}_manual_image",
                [
                    'label' => __('Service Image', 'nivaro'),
                    'type' => \Elementor\Controls_Manager::MEDIA,
                    'default' => [
                        'url' => \Elementor\Utils::get_placeholder_image_src(),
                    ],
                    'condition' => [
                        "service_{$i}_mode" => 'manual',
                    ],
                ]
            );
            
            $this->add_control(
                "service_{$i}_manual_title",
                [
                    'label' => __('Service Title', 'nivaro'),
                    'type' => \Elementor\Controls_Manager::TEXT,
                    'default' => __("Service Title {$i}", 'nivaro'),
                    'label_block' => true,
                    'condition' => [
                        "service_{$i}_mode" => 'manual',
                    ],
                ]
            );
            
            $this->add_control(
                "service_{$i}_manual_description",
                [
                    'label' => __('Service Description', 'nivaro'),
                    'type' => \Elementor\Controls_Manager::TEXTAREA,
                    'default' => __('Enter your service description here...', 'nivaro'),
                    'rows' => 3,
                    'condition' => [
                        "service_{$i}_mode" => 'manual',
                    ],
                ]
            );
            
            $this->add_control(
                "service_{$i}_button_text",
                [
                    'label' => __('Button Text', 'nivaro'),
                    'type' => \Elementor\Controls_Manager::TEXT,
                    'default' => __('Learn More', 'nivaro'),
                ]
            );
            
            $this->add_control(
                "service_{$i}_button_link",
                [
                    'label' => __('Button Link', 'nivaro'),
                    'type' => \Elementor\Controls_Manager::URL,
                    'placeholder' => __('https://your-link.com', 'nivaro'),
                    'default' => [
                        'url' => '#',
                    ],
                ]
            );
            
            $this->end_controls_section();
        }
        
        // Grid Style Section
        $this->start_controls_section(
            'grid_style_section',
            [
                'label' => __('Grid Layout', 'nivaro'),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );
        
        $this->add_responsive_control(
            'columns',
            [
                'label' => __('Columns', 'nivaro'),
                'type' => \Elementor\Controls_Manager::SELECT,
                'default' => '4',
                'tablet_default' => '3',
                'mobile_default' => '2',
                'options' => [
                    '1' => '1',
                    '2' => '2',
                    '3' => '3',
                    '4' => '4',
                    '6' => '6',
                ],
                'selectors' => [
                    '{{WRAPPER}} .leatherback-grid' => 'grid-template-columns: repeat({{VALUE}}, 1fr);',
                ],
            ]
        );
        
        $this->add_responsive_control(
            'gap',
            [
                'label' => __('Gap', 'nivaro'),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => ['px', '%'],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 100,
                    ],
                ],
                'default' => [
                    'unit' => 'px',
                    'size' => 20,
                ],
                'selectors' => [
                    '{{WRAPPER}} .leatherback-grid' => 'gap: {{SIZE}}{{UNIT}};',
                ],
            ]
        );
        
        $this->end_controls_section();
        
        // Box Style Section
        $this->start_controls_section(
            'box_style_section',
            [
                'label' => __('Service Box Style', 'nivaro'),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );
        
        $this->add_group_control(
            \Elementor\Group_Control_Background::get_type(),
            [
                'name' => 'box_background',
                'label' => __('Background', 'nivaro'),
                'types' => ['classic', 'gradient'],
                'selector' => '{{WRAPPER}} .leatherback-service-box',
            ]
        );
        
        $this->add_group_control(
            \Elementor\Group_Control_Border::get_type(),
            [
                'name' => 'box_border',
                'label' => __('Border', 'nivaro'),
                'selector' => '{{WRAPPER}} .leatherback-service-box',
            ]
        );
        
        $this->add_responsive_control(
            'box_border_radius',
            [
                'label' => __('Border Radius', 'nivaro'),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%'],
                'selectors' => [
                    '{{WRAPPER}} .leatherback-service-box' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        
        $this->add_group_control(
            \Elementor\Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'box_shadow',
                'label' => __('Box Shadow', 'nivaro'),
                'selector' => '{{WRAPPER}} .leatherback-service-box',
            ]
        );
        
        $this->add_responsive_control(
            'box_padding',
            [
                'label' => __('Padding', 'nivaro'),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%'],
                'selectors' => [
                    '{{WRAPPER}} .leatherback-service-content' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        
        $this->end_controls_section();
        
        // Image Style Section
        $this->start_controls_section(
            'image_style_section',
            [
                'label' => __('Image Style', 'nivaro'),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );
        
        $this->add_responsive_control(
            'image_height',
            [
                'label' => __('Image Height', 'nivaro'),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => ['px'],
                'range' => [
                    'px' => [
                        'min' => 100,
                        'max' => 400,
                    ],
                ],
                'default' => [
                    'unit' => 'px',
                    'size' => 200,
                ],
                'selectors' => [
                    '{{WRAPPER}} .leatherback-service-image' => 'height: {{SIZE}}{{UNIT}};',
                ],
            ]
        );
        
        $this->add_responsive_control(
            'image_border_radius',
            [
                'label' => __('Border Radius', 'nivaro'),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%'],
                'selectors' => [
                    '{{WRAPPER}} .leatherback-service-image' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    '{{WRAPPER}} .leatherback-service-image img' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        
        $this->end_controls_section();
        
        // Typography Section
        $this->start_controls_section(
            'typography_section',
            [
                'label' => __('Typography', 'nivaro'),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );
        
        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'title_typography',
                'label' => __('Title Typography', 'nivaro'),
                'selector' => '{{WRAPPER}} .leatherback-service-title',
            ]
        );
        
        $this->add_control(
            'title_color',
            [
                'label' => __('Title Color', 'nivaro'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .leatherback-service-title' => 'color: {{VALUE}};',
                ],
            ]
        );
        
        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'description_typography',
                'label' => __('Description Typography', 'nivaro'),
                'selector' => '{{WRAPPER}} .leatherback-service-description',
            ]
        );
        
        $this->add_control(
            'description_color',
            [
                'label' => __('Description Color', 'nivaro'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .leatherback-service-description' => 'color: {{VALUE}};',
                ],
            ]
        );
        
        $this->end_controls_section();
        
        // Button Style Section
        $this->start_controls_section(
            'button_style_section',
            [
                'label' => __('Button Style', 'nivaro'),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );
        
        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'button_typography',
                'label' => __('Typography', 'nivaro'),
                'selector' => '{{WRAPPER}} .leatherback-btn',
            ]
        );
        
        $this->start_controls_tabs('button_tabs');
        
        $this->start_controls_tab(
            'button_normal',
            [
                'label' => __('Normal', 'nivaro'),
            ]
        );
        
        $this->add_control(
            'button_text_color',
            [
                'label' => __('Text Color', 'nivaro'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '#ffffff',
                'selectors' => [
                    '{{WRAPPER}} .leatherback-btn' => 'color: {{VALUE}};',
                ],
            ]
        );
        
        $this->add_group_control(
            \Elementor\Group_Control_Background::get_type(),
            [
                'name' => 'button_background',
                'label' => __('Background', 'nivaro'),
                'types' => ['classic', 'gradient'],
                'selector' => '{{WRAPPER}} .leatherback-btn',
            ]
        );
        
        $this->end_controls_tab();
        
        $this->start_controls_tab(
            'button_hover',
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
                    '{{WRAPPER}} .leatherback-btn:hover' => 'color: {{VALUE}};',
                ],
            ]
        );
        
        $this->add_group_control(
            \Elementor\Group_Control_Background::get_type(),
            [
                'name' => 'button_hover_background',
                'label' => __('Background', 'nivaro'),
                'types' => ['classic', 'gradient'],
                'selector' => '{{WRAPPER}} .leatherback-btn:hover',
            ]
        );
        
        $this->end_controls_tab();
        
        $this->end_controls_tabs();
        
        $this->add_responsive_control(
            'button_padding',
            [
                'label' => __('Padding', 'nivaro'),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%'],
                'selectors' => [
                    '{{WRAPPER}} .leatherback-btn' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        
        $this->add_responsive_control(
            'button_border_radius',
            [
                'label' => __('Border Radius', 'nivaro'),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%'],
                'selectors' => [
                    '{{WRAPPER}} .leatherback-btn' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        
        $this->end_controls_section();
    }
    
    protected function render() {
        $settings = $this->get_settings_for_display();
        $box_count = $settings['box_count'];
        
        ?>
        <div class="leatherback-widget">
            <div class="leatherback-grid">
                <?php for ($i = 1; $i <= $box_count; $i++): ?>
                    <?php $this->render_service_box($i, $settings); ?>
                <?php endfor; ?>
            </div>
        </div>
        <?php
    }
    
    private function render_service_box($index, $settings) {
        $mode = $settings["service_{$index}_mode"] ?? 'auto';
        
        // Get service data based on mode
        if ($mode === 'auto') {
            $service_id = $settings["service_{$index}_auto_id"] ?? '';
            if ($service_id) {
                $service = $this->database->get_service_by_id($service_id);
                $image_url = $this->database->get_service_image_url($service_id, 'medium');
                $title = $service->service_name ?? '';
                $description = $service->service_placard ?? '';
            } else {
                $image_url = '';
                $title = "Select Service {$index}";
                $description = 'Please select a service from the dropdown';
            }
        } else {
            // Manual mode
            $image_data = $settings["service_{$index}_manual_image"] ?? [];
            $image_url = $image_data['url'] ?? '';
            $title = $settings["service_{$index}_manual_title"] ?? "Service Title {$index}";
            $description = $settings["service_{$index}_manual_description"] ?? '';
        }
        
        $button_text = $settings["service_{$index}_button_text"] ?? 'Learn More';
        $button_link = $settings["service_{$index}_button_link"] ?? ['url' => '#'];
        
        ?>
        <div class="leatherback-service-box">
            <?php if ($image_url): ?>
                <div class="leatherback-service-image">
                    <img src="<?php echo esc_url($image_url); ?>" alt="<?php echo esc_attr($title); ?>" loading="lazy">
                </div>
            <?php endif; ?>
            
            <div class="leatherback-service-content">
                <?php if ($title): ?>
                    <h3 class="leatherback-service-title"><?php echo esc_html($title); ?></h3>
                <?php endif; ?>
                
                <?php if ($description): ?>
                    <p class="leatherback-service-description"><?php echo esc_html($description); ?></p>
                <?php endif; ?>
                
                <div class="leatherback-service-button">
                    <a href="<?php echo esc_url($button_link['url']); ?>" 
                       class="leatherback-btn"
                       <?php echo $button_link['is_external'] ? 'target="_blank"' : ''; ?>
                       <?php echo $button_link['nofollow'] ? 'rel="nofollow"' : ''; ?>>
                        <?php echo esc_html($button_text); ?>
                    </a>
                </div>
            </div>
        </div>
        <?php
    }
    
    protected function content_template() {
        ?>
        <#
        var boxCount = settings.box_count;
        #>
        <div class="leatherback-widget">
            <div class="leatherback-grid">
                <# for (var i = 1; i <= boxCount; i++) { #>
                    <div class="leatherback-service-box">
                        <div class="leatherback-service-image">
                            <# 
                            var mode = settings['service_' + i + '_mode'];
                            var imageUrl = '';
                            var title = '';
                            var description = '';
                            
                            if (mode === 'manual') {
                                if (settings['service_' + i + '_manual_image'] && settings['service_' + i + '_manual_image'].url) {
                                    imageUrl = settings['service_' + i + '_manual_image'].url;
                                }
                                title = settings['service_' + i + '_manual_title'] || 'Service Title ' + i;
                                description = settings['service_' + i + '_manual_description'] || '';
                            } else {
                                title = 'Service Box ' + i;
                                description = 'Auto mode - preview not available in editor';
                            }
                            #>
                            
                            <# if (imageUrl) { #>
                                <img src="{{ imageUrl }}" alt="{{ title }}">
                            <# } else { #>
                                <div style="background: #f0f0f0; height: 200px; display: flex; align-items: center; justify-content: center; color: #666;">
                                    Service Image {{ i }}
                                </div>
                            <# } #>
                        </div>
                        
                        <div class="leatherback-service-content">
                            <h3 class="leatherback-service-title">{{ title }}</h3>
                            <# if (description) { #>
                                <p class="leatherback-service-description">{{ description }}</p>
                            <# } #>
                            <div class="leatherback-service-button">
                                <a href="#" class="leatherback-btn">{{ settings['service_' + i + '_button_text'] || 'Learn More' }}</a>
                            </div>
                        </div>
                    </div>
                <# } #>
            </div>
        </div>
        <?php
    }
}