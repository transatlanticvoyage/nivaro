<?php
/**
 * Nivaro Meerkat Box Widget
 * 
 * Exact replica of container with dynamic zen service integration
 */

if (!defined('ABSPATH')) {
    exit;
}

class Nivaro_Meerkat_Box_Widget extends \Elementor\Widget_Base {
    
    private $database;
    
    public function __construct($data = [], $args = null) {
        parent::__construct($data, $args);
        $this->database = new Nivaro_Database();
    }
    
    public function get_name() {
        return 'nivaro-meerkat-box';
    }
    
    public function get_title() {
        return __('Meerkat Box', 'nivaro');
    }
    
    public function get_icon() {
        return 'eicon-call-to-action';
    }
    
    public function get_categories() {
        return ['general'];
    }
    
    public function get_keywords() {
        return ['meerkat', 'box', 'hero', 'dynamic', 'zen', 'nivaro'];
    }
    
    protected function register_controls() {
        
        // Content Section
        $this->start_controls_section(
            'meerkat_content_section',
            [
                'label' => __('Content', 'nivaro'),
                'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
            ]
        );
        
        $this->add_control(
            'meerkat_heading_text',
            [
                'label' => __('Heading Text', 'nivaro'),
                'type' => \Elementor\Controls_Manager::TEXT,
                'default' => __('Your Heading Here', 'nivaro'),
                'placeholder' => __('Enter your heading', 'nivaro'),
                'label_block' => true,
            ]
        );
        
        $this->add_control(
            'meerkat_heading_tag',
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
                ],
                'default' => 'h1',
            ]
        );
        
        $this->end_controls_section();
        
        // Dynamic Service Section
        $this->start_controls_section(
            'meerkat_dynamic_section',
            [
                'label' => __('Dynamic Background', 'nivaro'),
                'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
            ]
        );
        
        $service_options = $this->database->get_service_options();
        
        $this->add_control(
            'meerkat_service_id',
            [
                'label' => __('Select Zen Service', 'nivaro'),
                'type' => \Elementor\Controls_Manager::SELECT,
                'options' => $service_options,
                'default' => '',
                'description' => __('Select a service to use its image as background', 'nivaro'),
            ]
        );
        
        $this->add_control(
            'meerkat_fallback_image',
            [
                'label' => __('Fallback Background Image', 'nivaro'),
                'type' => \Elementor\Controls_Manager::MEDIA,
                'description' => __('Used when no service is selected or service has no image', 'nivaro'),
            ]
        );
        
        $this->end_controls_section();
        
        // Style Section - Container
        $this->start_controls_section(
            'meerkat_container_style',
            [
                'label' => __('Container', 'nivaro'),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );
        
        $this->add_responsive_control(
            'meerkat_min_height',
            [
                'label' => __('Min Height', 'nivaro'),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => ['px', 'vh'],
                'range' => [
                    'px' => [
                        'min' => 100,
                        'max' => 1000,
                    ],
                    'vh' => [
                        'min' => 10,
                        'max' => 100,
                    ],
                ],
                'default' => [
                    'unit' => 'px',
                    'size' => 350,
                ],
                'tablet_default' => [
                    'unit' => 'px',
                    'size' => 300,
                ],
                'mobile_default' => [
                    'unit' => 'px',
                    'size' => 250,
                ],
                'selectors' => [
                    '{{WRAPPER}} .meerkat-box-container' => 'min-height: {{SIZE}}{{UNIT}};',
                ],
            ]
        );
        
        $this->add_responsive_control(
            'meerkat_padding',
            [
                'label' => __('Padding', 'nivaro'),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', '%'],
                'default' => [
                    'top' => '50',
                    'right' => '20',
                    'bottom' => '50',
                    'left' => '20',
                    'unit' => 'px',
                    'isLinked' => false,
                ],
                'selectors' => [
                    '{{WRAPPER}} .meerkat-box-container' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        
        $this->end_controls_section();
        
        // Style Section - Background
        $this->start_controls_section(
            'meerkat_background_style',
            [
                'label' => __('Background', 'nivaro'),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );
        
        $this->add_control(
            'meerkat_bg_position',
            [
                'label' => __('Background Position', 'nivaro'),
                'type' => \Elementor\Controls_Manager::SELECT,
                'default' => 'top left',
                'options' => [
                    'top left' => __('Top Left', 'nivaro'),
                    'top center' => __('Top Center', 'nivaro'),
                    'top right' => __('Top Right', 'nivaro'),
                    'center left' => __('Center Left', 'nivaro'),
                    'center center' => __('Center Center', 'nivaro'),
                    'center right' => __('Center Right', 'nivaro'),
                    'bottom left' => __('Bottom Left', 'nivaro'),
                    'bottom center' => __('Bottom Center', 'nivaro'),
                    'bottom right' => __('Bottom Right', 'nivaro'),
                ],
                'selectors' => [
                    '{{WRAPPER}} .meerkat-box-container' => 'background-position: {{VALUE}};',
                ],
            ]
        );
        
        $this->add_control(
            'meerkat_bg_size',
            [
                'label' => __('Background Size', 'nivaro'),
                'type' => \Elementor\Controls_Manager::SELECT,
                'default' => 'cover',
                'options' => [
                    'auto' => __('Auto', 'nivaro'),
                    'cover' => __('Cover', 'nivaro'),
                    'contain' => __('Contain', 'nivaro'),
                ],
                'selectors' => [
                    '{{WRAPPER}} .meerkat-box-container' => 'background-size: {{VALUE}};',
                ],
            ]
        );
        
        $this->add_control(
            'meerkat_overlay_opacity',
            [
                'label' => __('Overlay Opacity', 'nivaro'),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 1,
                        'step' => 0.1,
                    ],
                ],
                'default' => [
                    'size' => 0.7,
                ],
                'selectors' => [
                    '{{WRAPPER}} .meerkat-box-overlay' => 'opacity: {{SIZE}};',
                ],
            ]
        );
        
        $this->add_control(
            'meerkat_overlay_color',
            [
                'label' => __('Overlay Color', 'nivaro'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '#000000',
                'selectors' => [
                    '{{WRAPPER}} .meerkat-box-overlay' => 'background-color: {{VALUE}};',
                ],
            ]
        );
        
        $this->end_controls_section();
        
        // Style Section - Heading
        $this->start_controls_section(
            'meerkat_heading_style',
            [
                'label' => __('Heading', 'nivaro'),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );
        
        $this->add_control(
            'meerkat_heading_color',
            [
                'label' => __('Text Color', 'nivaro'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '#FFFFFF',
                'selectors' => [
                    '{{WRAPPER}} .meerkat-box-heading' => 'color: {{VALUE}};',
                ],
            ]
        );
        
        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'meerkat_heading_typography',
                'selector' => '{{WRAPPER}} .meerkat-box-heading',
                'fields_options' => [
                    'typography' => ['default' => 'yes'],
                    'font_family' => ['default' => 'Poppins'],
                    'font_size' => ['default' => ['size' => 64, 'unit' => 'px']],
                    'font_weight' => ['default' => '600'],
                    'line_height' => ['default' => ['size' => 1, 'unit' => 'em']],
                    'letter_spacing' => ['default' => ['size' => -1.928, 'unit' => 'px']],
                    'text_transform' => ['default' => 'capitalize'],
                ],
            ]
        );
        
        $this->add_responsive_control(
            'meerkat_heading_align',
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
                ],
                'default' => 'center',
                'selectors' => [
                    '{{WRAPPER}} .meerkat-box-heading' => 'text-align: {{VALUE}};',
                ],
            ]
        );
        
        $this->end_controls_section();
    }
    
    protected function render() {
        $settings = $this->get_settings_for_display();
        
        // Get background image URL
        $bg_image_url = '';
        
        // First try to get from zen service
        if (!empty($settings['meerkat_service_id'])) {
            $bg_image_url = $this->database->get_service_image_url($settings['meerkat_service_id'], 'full');
        }
        
        // If no service image, use fallback
        if (empty($bg_image_url) && !empty($settings['meerkat_fallback_image']['url'])) {
            $bg_image_url = $settings['meerkat_fallback_image']['url'];
        }
        
        // Build inline styles for background
        $container_style = '';
        if (!empty($bg_image_url)) {
            $container_style = 'background-image: url(' . esc_url($bg_image_url) . ');';
        }
        
        // Get heading tag
        $heading_tag = $settings['meerkat_heading_tag'];
        
        ?>
        <div class="meerkat-box-container" style="<?php echo esc_attr($container_style); ?>">
            <div class="meerkat-box-overlay"></div>
            <div class="meerkat-box-content">
                <<?php echo esc_html($heading_tag); ?> class="meerkat-box-heading">
                    <?php echo esc_html($settings['meerkat_heading_text']); ?>
                </<?php echo esc_html($heading_tag); ?>>
            </div>
        </div>
        <?php
    }
    
    protected function content_template() {
        ?>
        <#
        var headingTag = settings.meerkat_heading_tag;
        var bgImageUrl = '';
        
        if (settings.meerkat_fallback_image && settings.meerkat_fallback_image.url) {
            bgImageUrl = settings.meerkat_fallback_image.url;
        }
        
        var containerStyle = bgImageUrl ? 'background-image: url(' + bgImageUrl + ');' : '';
        #>
        
        <div class="meerkat-box-container" style="{{ containerStyle }}">
            <div class="meerkat-box-overlay"></div>
            <div class="meerkat-box-content">
                <{{ headingTag }} class="meerkat-box-heading">
                    {{{ settings.meerkat_heading_text }}}
                </{{ headingTag }}>
            </div>
        </div>
        <?php
    }
}