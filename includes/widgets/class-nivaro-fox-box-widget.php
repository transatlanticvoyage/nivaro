<?php
/**
 * Nivaro Fox Box Widget
 * 
 * Companion widget for Coyote Box container - provides pre-configured heading content
 */

if (!defined('ABSPATH')) {
    exit;
}

class Nivaro_Fox_Box_Widget extends \Elementor\Widget_Base {
    
    public function get_name() {
        return 'nivaro-fox-box';
    }
    
    public function get_title() {
        return __('🦊 Fox Box Content', 'nivaro');
    }
    
    public function get_icon() {
        return 'eicon-heading';
    }
    
    public function get_categories() {
        return ['general'];
    }
    
    public function get_keywords() {
        return ['fox', 'box', 'heading', 'coyote', 'content', 'nivaro'];
    }
    
    protected function register_controls() {
        
        // Content Section
        $this->start_controls_section(
            'fox_box_content_section',
            [
                'label' => __('Content', 'nivaro'),
                'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
            ]
        );
        
        $this->add_control(
            'fox_box_title',
            [
                'label' => __('Heading Text', 'nivaro'),
                'type' => \Elementor\Controls_Manager::TEXT,
                'default' => __('Your Heading Here', 'nivaro'),
                'placeholder' => __('Type your heading here', 'nivaro'),
                'label_block' => true,
                'description' => __('The main heading text for your hero section', 'nivaro'),
            ]
        );
        
        $this->add_control(
            'fox_box_header_size',
            [
                'label' => __('HTML Tag', 'nivaro'),
                'type' => \Elementor\Controls_Manager::SELECT,
                'options' => [
                    'h1' => 'H1',
                    'h2' => 'H2',
                    'h3' => 'H3',
                    'h4' => 'H4',
                    'h5' => 'H5',
                    'h6' => 'H6',
                    'div' => 'div',
                    'span' => 'span',
                    'p' => 'p',
                ],
                'default' => 'h1',
            ]
        );
        
        $this->add_control(
            'fox_box_link',
            [
                'label' => __('Link', 'nivaro'),
                'type' => \Elementor\Controls_Manager::URL,
                'placeholder' => __('https://your-link.com', 'nivaro'),
                'default' => [
                    'url' => '',
                    'is_external' => false,
                    'nofollow' => false,
                ],
                'show_external' => true,
                'description' => __('Optional link for the heading', 'nivaro'),
            ]
        );
        
        $this->end_controls_section();
        
        // Style Section - Typography
        $this->start_controls_section(
            'fox_box_typography_section',
            [
                'label' => __('Typography', 'nivaro'),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );
        
        $this->add_control(
            'fox_box_title_color',
            [
                'label' => __('Text Color', 'nivaro'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '#FFFFFF',
                'selectors' => [
                    '{{WRAPPER}} .fox-box-heading' => 'color: {{VALUE}};',
                ],
            ]
        );
        
        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'fox_box_typography',
                'selector' => '{{WRAPPER}} .fox-box-heading',
                'fields_options' => [
                    'typography' => ['default' => 'yes'],
                    'font_family' => ['default' => 'Poppins'],
                    'font_size' => [
                        'default' => ['size' => 64, 'unit' => 'px'],
                        'tablet_default' => ['size' => 50, 'unit' => 'px'],
                        'mobile_default' => ['size' => 38, 'unit' => 'px'],
                    ],
                    'font_weight' => ['default' => '600'],
                    'line_height' => [
                        'default' => ['size' => 1, 'unit' => 'em'],
                        'tablet_default' => ['size' => 1, 'unit' => 'em'],
                        'mobile_default' => ['size' => 1.1, 'unit' => 'em'],
                    ],
                    'letter_spacing' => [
                        'default' => ['size' => -1.928, 'unit' => 'px'],
                        'tablet_default' => ['size' => -1, 'unit' => 'px'],
                        'mobile_default' => ['size' => 0, 'unit' => 'px'],
                    ],
                    'text_transform' => ['default' => 'capitalize'],
                    'text_decoration' => ['default' => 'none'],
                    'font_style' => ['default' => 'normal'],
                ],
            ]
        );
        
        $this->add_responsive_control(
            'fox_box_align',
            [
                'label' => __('Alignment', 'nivaro'),
                'type' => \Elementor\Controls_Manager::CHOOSE,
                'options' => [
                    'left' => [
                        'title' => __('Left', 'nivaro'),
                        'icon' => 'eicon-text-align-left',
                    ],
                    'center' => [
                        'title' => __('Center', 'nivaro'),
                        'icon' => 'eicon-text-align-center',
                    ],
                    'right' => [
                        'title' => __('Right', 'nivaro'),
                        'icon' => 'eicon-text-align-right',
                    ],
                    'justify' => [
                        'title' => __('Justified', 'nivaro'),
                        'icon' => 'eicon-text-align-justify',
                    ],
                ],
                'default' => 'center',
                'selectors' => [
                    '{{WRAPPER}} .fox-box-heading' => 'text-align: {{VALUE}};',
                ],
            ]
        );
        
        $this->end_controls_section();
        
        // Style Section - Text Effects  
        $this->start_controls_section(
            'fox_box_text_effects_section',
            [
                'label' => __('Text Effects', 'nivaro'),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );
        
        $this->add_group_control(
            \Elementor\Group_Control_Text_Shadow::get_type(),
            [
                'name' => 'fox_box_text_shadow',
                'selector' => '{{WRAPPER}} .fox-box-heading',
            ]
        );
        
        $this->add_control(
            'fox_box_text_stroke_heading',
            [
                'label' => __('Text Stroke', 'nivaro'),
                'type' => \Elementor\Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );
        
        $this->add_control(
            'fox_box_text_stroke_type',
            [
                'label' => __('Text Stroke', 'nivaro'),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'label_on' => __('Yes', 'nivaro'),
                'label_off' => __('No', 'nivaro'),
                'return_value' => 'yes',
                'default' => 'yes',
            ]
        );
        
        $this->add_responsive_control(
            'fox_box_text_stroke_width',
            [
                'label' => __('Stroke Width', 'nivaro'),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => ['px'],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 20,
                        'step' => 0.1,
                    ],
                ],
                'condition' => [
                    'fox_box_text_stroke_type' => 'yes',
                ],
                'selectors' => [
                    '{{WRAPPER}} .fox-box-heading' => '-webkit-text-stroke-width: {{SIZE}}{{UNIT}};',
                ],
            ]
        );
        
        $this->add_control(
            'fox_box_text_stroke_color',
            [
                'label' => __('Stroke Color', 'nivaro'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '#000000',
                'condition' => [
                    'fox_box_text_stroke_type' => 'yes',
                ],
                'selectors' => [
                    '{{WRAPPER}} .fox-box-heading' => '-webkit-text-stroke-color: {{VALUE}};',
                ],
            ]
        );
        
        $this->end_controls_section();
        
        // Style Section - Spacing
        $this->start_controls_section(
            'fox_box_spacing_section',
            [
                'label' => __('Spacing', 'nivaro'),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );
        
        $this->add_responsive_control(
            'fox_box_margin',
            [
                'label' => __('Margin', 'nivaro'),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', '%'],
                'selectors' => [
                    '{{WRAPPER}} .fox-box-heading' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        
        $this->add_responsive_control(
            'fox_box_padding',
            [
                'label' => __('Padding', 'nivaro'),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', '%'],
                'selectors' => [
                    '{{WRAPPER}} .fox-box-heading' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        
        $this->end_controls_section();
        
        // Style Section - Hover Effects
        $this->start_controls_section(
            'fox_box_hover_section',
            [
                'label' => __('Hover Effects', 'nivaro'),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );
        
        $this->add_control(
            'fox_box_hover_color',
            [
                'label' => __('Hover Color', 'nivaro'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .fox-box-heading:hover' => 'color: {{VALUE}};',
                ],
            ]
        );
        
        $this->add_control(
            'fox_box_hover_transition',
            [
                'label' => __('Transition Duration (s)', 'nivaro'),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 3,
                        'step' => 0.1,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .fox-box-heading' => 'transition: color {{SIZE}}s ease;',
                ],
            ]
        );
        
        $this->end_controls_section();
    }
    
    protected function render() {
        $settings = $this->get_settings_for_display();
        
        // Get heading tag
        $heading_tag = $settings['fox_box_header_size'];
        
        // Build link attributes if link is provided
        $link_attributes = '';
        if (!empty($settings['fox_box_link']['url'])) {
            $this->add_link_attributes('fox_box_link', $settings['fox_box_link']);
            $link_attributes = $this->get_render_attribute_string('fox_box_link');
        }
        
        // Build CSS classes
        $css_classes = 'fox-box-heading elementor-heading-title elementor-size-default';
        
        ?>
        <div class="fox-box-wrapper">
            <?php if (!empty($settings['fox_box_link']['url'])): ?>
                <a <?php echo $link_attributes; ?>>
                    <<?php echo esc_html($heading_tag); ?> class="<?php echo esc_attr($css_classes); ?>">
                        <?php echo esc_html($settings['fox_box_title']); ?>
                    </<?php echo esc_html($heading_tag); ?>>
                </a>
            <?php else: ?>
                <<?php echo esc_html($heading_tag); ?> class="<?php echo esc_attr($css_classes); ?>">
                    <?php echo esc_html($settings['fox_box_title']); ?>
                </<?php echo esc_html($heading_tag); ?>>
            <?php endif; ?>
        </div>
        
        <?php if (\Elementor\Plugin::$instance->editor->is_edit_mode()): ?>
            <div style="margin-top: 10px; padding: 10px; background: rgba(255, 255, 255, 0.1); border-radius: 3px; text-align: center;">
                <small style="color: rgba(255, 255, 255, 0.8); font-size: 12px;">
                    🦊 Fox Box Content - Designed for Coyote Box containers
                </small>
            </div>
        <?php endif; ?>
        <?php
    }
    
    protected function content_template() {
        ?>
        <#
        var headingTag = settings.fox_box_header_size;
        var cssClasses = 'fox-box-heading elementor-heading-title elementor-size-default';
        #>
        
        <div class="fox-box-wrapper">
            <# if (settings.fox_box_link.url) { #>
                <a href="{{ settings.fox_box_link.url }}">
                    <{{ headingTag }} class="{{ cssClasses }}">
                        {{{ settings.fox_box_title }}}
                    </{{ headingTag }}>
                </a>
            <# } else { #>
                <{{ headingTag }} class="{{ cssClasses }}">
                    {{{ settings.fox_box_title }}}
                </{{ headingTag }}>
            <# } #>
        </div>
        
        <div style="margin-top: 10px; padding: 10px; background: rgba(255, 255, 255, 0.1); border-radius: 3px; text-align: center;">
            <small style="color: rgba(255, 255, 255, 0.8); font-size: 12px;">
                🦊 Fox Box Content - Designed for Coyote Box containers
            </small>
        </div>
        <?php
    }
}