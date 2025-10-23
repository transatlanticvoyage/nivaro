<?php
/**
 * Nivaro Sahara Widget
 * 
 * Dynamic image widget with automatic zen_services relationship lookup
 */

if (!defined('ABSPATH')) {
    exit;
}

class Nivaro_Sahara_Widget extends \Elementor\Widget_Base {
    
    private $database;
    
    public function __construct($data = [], $args = null) {
        parent::__construct($data, $args);
        
        // Use global plugin instance for database access
        $plugin = Nivaro::get_instance();
        $this->database = $plugin->database;
        
        // Fallback if plugin instance not available
        if (!$this->database) {
            $this->database = new Nivaro_Database();
        }
    }
    
    public function get_name() {
        return 'nivaro-sahara';
    }
    
    public function get_title() {
        return __('Sahara Image', 'nivaro');
    }
    
    public function get_icon() {
        return 'eicon-image';
    }
    
    public function get_categories() {
        return ['nivaro'];
    }
    
    public function get_keywords() {
        return ['sahara', 'image', 'dynamic', 'nivaro', 'zen'];
    }
    
    protected function register_controls() {
        
        // Content Section
        $this->start_controls_section(
            'content_section',
            [
                'label' => __('Image', 'nivaro'),
                'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
            ]
        );
        
        // Main Configuration Option
        $this->add_control(
            'main_configuration_option',
            [
                'label' => __('Main Configuration Option:', 'nivaro'),
                'type' => \Elementor\Controls_Manager::SELECT,
                'options' => [
                    'auto_image1' => __('Pull Image From (wp)_zen_services.rel_image1_id', 'nivaro'),
                    'auto_icon' => __('Pull Image From (wp)_zen_services.rel_icon_image_id', 'nivaro'),
                    'manual' => __('Manually Assign An Image', 'nivaro'),
                ],
                'default' => 'auto_image1',
            ]
        );
        
        // Manual Image Selection
        $this->add_control(
            'image',
            [
                'label' => __('Choose Image', 'nivaro'),
                'type' => \Elementor\Controls_Manager::MEDIA,
                'default' => [
                    'url' => \Elementor\Utils::get_placeholder_image_src(),
                ],
                'condition' => [
                    'main_configuration_option' => 'manual',
                ],
            ]
        );
        
        // Image Link
        $this->add_control(
            'link_to',
            [
                'label' => __('Link', 'nivaro'),
                'type' => \Elementor\Controls_Manager::SELECT,
                'default' => 'none',
                'options' => [
                    'none' => __('None', 'nivaro'),
                    'file' => __('Media File', 'nivaro'),
                    'custom' => __('Custom URL', 'nivaro'),
                ],
            ]
        );
        
        $this->add_control(
            'link',
            [
                'label' => __('Link', 'nivaro'),
                'type' => \Elementor\Controls_Manager::URL,
                'placeholder' => __('https://your-link.com', 'nivaro'),
                'condition' => [
                    'link_to' => 'custom',
                ],
                'show_label' => false,
            ]
        );
        
        $this->add_control(
            'open_lightbox',
            [
                'label' => __('Lightbox', 'nivaro'),
                'type' => \Elementor\Controls_Manager::SELECT,
                'default' => 'default',
                'options' => [
                    'default' => __('Default', 'nivaro'),
                    'yes' => __('Yes', 'nivaro'),
                    'no' => __('No', 'nivaro'),
                ],
                'condition' => [
                    'link_to' => 'file',
                ],
            ]
        );
        
        // Sahara Widget Note
        $this->add_control(
            'sahara_note',
            [
                'type' => \Elementor\Controls_Manager::RAW_HTML,
                'raw' => __('<strong>Note:</strong> This is a Sahara widget.', 'nivaro'),
                'content_classes' => 'elementor-panel-alert elementor-panel-alert-info',
                'separator' => 'before',
            ]
        );
        
        $this->end_controls_section();
        
        // Style Section - Image
        $this->start_controls_section(
            'section_style_image',
            [
                'label' => __('Image', 'nivaro'),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );
        
        $this->add_responsive_control(
            'width',
            [
                'label' => __('Width', 'nivaro'),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'default' => [
                    'unit' => '%',
                ],
                'tablet_default' => [
                    'unit' => '%',
                ],
                'mobile_default' => [
                    'unit' => '%',
                ],
                'size_units' => [ '%', 'px', 'vw' ],
                'range' => [
                    '%' => [
                        'min' => 1,
                        'max' => 100,
                    ],
                    'px' => [
                        'min' => 1,
                        'max' => 1000,
                    ],
                    'vw' => [
                        'min' => 1,
                        'max' => 100,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .nivaro-sahara-widget img' => 'width: {{SIZE}}{{UNIT}};',
                ],
            ]
        );
        
        $this->add_responsive_control(
            'space',
            [
                'label' => __('Max Width', 'nivaro'),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'default' => [
                    'unit' => '%',
                ],
                'tablet_default' => [
                    'unit' => '%',
                ],
                'mobile_default' => [
                    'unit' => '%',
                ],
                'size_units' => [ '%', 'px', 'vw' ],
                'range' => [
                    '%' => [
                        'min' => 1,
                        'max' => 100,
                    ],
                    'px' => [
                        'min' => 1,
                        'max' => 1000,
                    ],
                    'vw' => [
                        'min' => 1,
                        'max' => 100,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .nivaro-sahara-widget img' => 'max-width: {{SIZE}}{{UNIT}};',
                ],
            ]
        );
        
        $this->add_responsive_control(
            'height',
            [
                'label' => __('Height', 'nivaro'),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'default' => [
                    'unit' => 'px',
                ],
                'tablet_default' => [
                    'unit' => 'px',
                ],
                'mobile_default' => [
                    'unit' => 'px',
                ],
                'size_units' => [ 'px', 'vh' ],
                'range' => [
                    'px' => [
                        'min' => 1,
                        'max' => 500,
                    ],
                    'vh' => [
                        'min' => 1,
                        'max' => 100,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .nivaro-sahara-widget img' => 'height: {{SIZE}}{{UNIT}};',
                ],
            ]
        );
        
        $this->add_responsive_control(
            'object_fit',
            [
                'label' => __('Object Fit', 'nivaro'),
                'type' => \Elementor\Controls_Manager::SELECT,
                'condition' => [
                    'height[size]!' => '',
                ],
                'options' => [
                    '' => __('Default', 'nivaro'),
                    'fill' => __('Fill', 'nivaro'),
                    'cover' => __('Cover', 'nivaro'),
                    'contain' => __('Contain', 'nivaro'),
                    'none' => __('None', 'nivaro'),
                ],
                'default' => '',
                'selectors' => [
                    '{{WRAPPER}} .nivaro-sahara-widget img' => 'object-fit: {{VALUE}};',
                ],
            ]
        );
        
        $this->add_responsive_control(
            'align',
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
                'selectors' => [
                    '{{WRAPPER}}' => 'text-align: {{VALUE}};',
                ],
            ]
        );
        
        $this->add_control(
            'separator_panel_style',
            [
                'type' => \Elementor\Controls_Manager::DIVIDER,
                'style' => 'thick',
            ]
        );
        
        $this->start_controls_tabs('image_effects');
        
        $this->start_controls_tab('normal',
            [
                'label' => __('Normal', 'nivaro'),
            ]
        );
        
        $this->add_control(
            'opacity',
            [
                'label' => __('Opacity', 'nivaro'),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'range' => [
                    'px' => [
                        'max' => 1,
                        'min' => 0.10,
                        'step' => 0.01,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .nivaro-sahara-widget img' => 'opacity: {{SIZE}};',
                ],
            ]
        );
        
        $this->add_group_control(
            \Elementor\Group_Control_Css_Filter::get_type(),
            [
                'name' => 'css_filters',
                'selector' => '{{WRAPPER}} .nivaro-sahara-widget img',
            ]
        );
        
        $this->end_controls_tab();
        
        $this->start_controls_tab('hover',
            [
                'label' => __('Hover', 'nivaro'),
            ]
        );
        
        $this->add_control(
            'opacity_hover',
            [
                'label' => __('Opacity', 'nivaro'),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'range' => [
                    'px' => [
                        'max' => 1,
                        'min' => 0.10,
                        'step' => 0.01,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .nivaro-sahara-widget:hover img' => 'opacity: {{SIZE}};',
                ],
            ]
        );
        
        $this->add_group_control(
            \Elementor\Group_Control_Css_Filter::get_type(),
            [
                'name' => 'css_filters_hover',
                'selector' => '{{WRAPPER}} .nivaro-sahara-widget:hover img',
            ]
        );
        
        $this->add_control(
            'background_hover_transition',
            [
                'label' => __('Transition Duration', 'nivaro'),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'range' => [
                    'px' => [
                        'max' => 3,
                        'step' => 0.1,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .nivaro-sahara-widget img' => 'transition-duration: {{SIZE}}s',
                ],
            ]
        );
        
        $this->end_controls_tab();
        
        $this->end_controls_tabs();
        
        $this->add_group_control(
            \Elementor\Group_Control_Border::get_type(),
            [
                'name' => 'image_border',
                'selector' => '{{WRAPPER}} .nivaro-sahara-widget img',
                'separator' => 'before',
            ]
        );
        
        $this->add_responsive_control(
            'image_border_radius',
            [
                'label' => __('Border Radius', 'nivaro'),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%' ],
                'selectors' => [
                    '{{WRAPPER}} .nivaro-sahara-widget img' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        
        $this->add_group_control(
            \Elementor\Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'image_box_shadow',
                'exclude' => [
                    'box_shadow_position',
                ],
                'selector' => '{{WRAPPER}} .nivaro-sahara-widget img',
            ]
        );
        
        $this->end_controls_section();
    }
    
    /**
     * Get the image ID based on the widget configuration
     */
    private function get_dynamic_image_id() {
        $settings = $this->get_settings_for_display();
        $config_option = $settings['main_configuration_option'];
        
        // If manual, return the manually selected image
        if ($config_option === 'manual') {
            return isset($settings['image']['id']) ? $settings['image']['id'] : 0;
        }
        
        // Get current post ID
        $current_post_id = get_the_ID();
        if (!$current_post_id) {
            return 0;
        }
        
        // Determine which column to use based on configuration
        $image_column = ($config_option === 'auto_icon') ? 'rel_icon_image_id' : 'rel_image1_id';
        
        // Query the database for the service with this page relationship
        global $wpdb;
        $table_name = $wpdb->prefix . 'zen_services';
        
        $result = $wpdb->get_row($wpdb->prepare(
            "SELECT $image_column FROM $table_name WHERE asn_service_page_id = %d AND is_active = 1 LIMIT 1",
            $current_post_id
        ));
        
        if ($result && !empty($result->$image_column)) {
            return intval($result->$image_column);
        }
        
        return 0;
    }
    
    protected function render() {
        $settings = $this->get_settings_for_display();
        
        // Get the image ID
        $image_id = $this->get_dynamic_image_id();
        
        if (!$image_id) {
            // No image found, show placeholder in editor only
            if (\Elementor\Plugin::$instance->editor->is_edit_mode()) {
                echo '<div class="nivaro-sahara-widget">';
                echo '<img src="' . \Elementor\Utils::get_placeholder_image_src() . '" alt="Placeholder" />';
                echo '</div>';
            }
            return;
        }
        
        // Get image URL
        $image_url = wp_get_attachment_image_url($image_id, 'full');
        if (!$image_url) {
            return;
        }
        
        // Get image alt text
        $image_alt = get_post_meta($image_id, '_wp_attachment_image_alt', true);
        
        // Start wrapper
        echo '<div class="nivaro-sahara-widget">';
        
        // Handle link
        $link = '';
        $link_attributes = '';
        
        switch ($settings['link_to']) {
            case 'file':
                $link = $image_url;
                if ($settings['open_lightbox'] === 'yes') {
                    $link_attributes = 'data-elementor-open-lightbox="yes"';
                } elseif ($settings['open_lightbox'] === 'no') {
                    $link_attributes = 'data-elementor-open-lightbox="no"';
                }
                break;
            case 'custom':
                if (!empty($settings['link']['url'])) {
                    $link = $settings['link']['url'];
                    if ($settings['link']['is_external']) {
                        $link_attributes .= ' target="_blank"';
                    }
                    if ($settings['link']['nofollow']) {
                        $link_attributes .= ' rel="nofollow"';
                    }
                }
                break;
        }
        
        // Output image with or without link
        if ($link) {
            echo '<a href="' . esc_url($link) . '"' . $link_attributes . '>';
        }
        
        echo '<img src="' . esc_url($image_url) . '" alt="' . esc_attr($image_alt) . '" />';
        
        if ($link) {
            echo '</a>';
        }
        
        echo '</div>';
    }
    
    protected function content_template() {
        ?>
        <#
        var image = {
            id: '',
            url: ''
        };
        
        if (settings.main_configuration_option === 'manual' && settings.image.url) {
            image = settings.image;
        } else {
            // In editor, show placeholder for dynamic images
            image.url = '<?php echo \Elementor\Utils::get_placeholder_image_src(); ?>';
        }
        
        if (image.url) {
            var imgUrl = image.url;
            var link = '';
            var linkAttributes = '';
            
            switch(settings.link_to) {
                case 'file':
                    link = imgUrl;
                    if (settings.open_lightbox === 'yes') {
                        linkAttributes = 'data-elementor-open-lightbox="yes"';
                    } else if (settings.open_lightbox === 'no') {
                        linkAttributes = 'data-elementor-open-lightbox="no"';
                    }
                    break;
                case 'custom':
                    if (settings.link && settings.link.url) {
                        link = settings.link.url;
                        if (settings.link.is_external) {
                            linkAttributes += ' target="_blank"';
                        }
                        if (settings.link.nofollow) {
                            linkAttributes += ' rel="nofollow"';
                        }
                    }
                    break;
            }
            #>
            <div class="nivaro-sahara-widget">
                <# if (link) { #>
                    <a href="{{ link }}"{{ linkAttributes }}>
                <# } #>
                <img src="{{ imgUrl }}" />
                <# if (link) { #>
                    </a>
                <# } #>
            </div>
            <#
        }
        #>
        <?php
    }
}