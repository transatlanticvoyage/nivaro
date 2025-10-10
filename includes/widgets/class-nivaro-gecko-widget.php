<?php
/**
 * Nivaro Gecko Widget
 * 
 * Companion widget for Gecko Setup (Option 6) containing 8 sub-widgets
 * Each sub-widget displays a complete service box with custom styling
 */

if (!defined('ABSPATH')) {
    exit;
}

class Nivaro_Gecko_Widget extends \Elementor\Widget_Base {
    
    private $database;
    
    public function __construct($data = [], $args = null) {
        parent::__construct($data, $args);
        $this->database = new Nivaro_Database();
    }
    
    public function get_name() {
        return 'nivaro-gecko';
    }
    
    public function get_title() {
        return __('🦎 Gecko Services', 'nivaro');
    }
    
    public function get_icon() {
        return 'eicon-gallery-grid';
    }
    
    public function get_categories() {
        return ['nivaro'];
    }
    
    public function get_keywords() {
        return ['services', 'gecko', 'grid', 'nivaro', 'alternative'];
    }
    
    protected function register_controls() {
        
        // Content Section
        $this->start_controls_section(
            'content_section',
            [
                'label' => __('Gecko Settings', 'nivaro'),
                'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
            ]
        );
        
        $this->add_control(
            'gecko_note',
            [
                'type' => \Elementor\Controls_Manager::RAW_HTML,
                'raw' => __('<strong>🦎 Gecko Widget</strong><br>This widget contains individual service boxes with alternative styling. Perfect for use with Gecko Setup (Option 6) in Coyote Box containers.', 'nivaro'),
                'content_classes' => 'elementor-panel-alert elementor-panel-alert-info',
            ]
        );
        
        // Get current service count from database
        $services_count = $this->database->get_services_count();
        
        $this->add_control(
            'auto_generate_info',
            [
                'type' => \Elementor\Controls_Manager::RAW_HTML,
                'raw' => sprintf(
                    '<div style="background: #f0f8ff; border: 2px solid #4169e1; padding: 15px; border-radius: 6px; margin: 15px 0;">
                        <div style="font-size: 14px; font-weight: bold; color: #4169e1; margin-bottom: 8px;">
                            📊 DATABASE STATUS
                        </div>
                        <div style="font-size: 13px; margin-bottom: 5px;">
                            Found <strong style="color: #4169e1;">%d services</strong> in your zen_services table
                        </div>
                        <div style="font-size: 11px; color: #666;">
                            Ready for auto-generation ✓
                        </div>
                    </div>',
                    $services_count
                ),
            ]
        );
        
        $this->add_control(
            'boxes_per_row',
            [
                'label' => __('Boxes Per Row', 'nivaro'),
                'type' => \Elementor\Controls_Manager::NUMBER,
                'default' => 4,
                'min' => 1,
                'max' => 8,
                'step' => 1,
                'description' => __('Number of service boxes per row before line break', 'nivaro'),
            ]
        );
        
        // Set max quantity based on actual services in database
        $max_services = min($services_count, 20); // Cap at 20 for performance
        $default_quantity = min(8, $services_count); // Default to 8 or less if fewer services
        
        $this->add_control(
            'generate_quantity',
            [
                'label' => __('Quantity to Generate', 'nivaro'),
                'type' => \Elementor\Controls_Manager::NUMBER,
                'default' => $default_quantity,
                'min' => 1,
                'max' => $max_services,
                'step' => 1,
                'description' => sprintf(__('Number of service boxes to auto-generate from database (Max: %d available)', 'nivaro'), $services_count),
            ]
        );
        
        $this->add_control(
            'auto_generate_button',
            [
                'type' => \Elementor\Controls_Manager::RAW_HTML,
                'raw' => '
                    <div style="margin: 20px 0; text-align: center;">
                        <div style="background: linear-gradient(135deg, #4169e1, #2c4aa0); padding: 3px; border-radius: 8px; margin-bottom: 10px;">
                            <button type="button" 
                                    id="gecko-auto-generate" 
                                    class="elementor-button elementor-button-default elementor-size-md" 
                                    style="width: 100%; padding: 15px; background: white; color: #4169e1; border: none; border-radius: 5px; cursor: pointer; font-size: 14px; font-weight: bold; box-shadow: 0 2px 4px rgba(0,0,0,0.1);">
                                <i class="eicon-sync" style="margin-right: 10px; font-size: 16px;"></i>
                                🚀 GENERATE SERVICE BOXES
                            </button>
                        </div>
                        <div style="font-size: 11px; color: #666; line-height: 1.4;">
                            Click to automatically populate service boxes with data from your database
                        </div>
                    </div>',
            ]
        );
        
        // Individual Service Box Controls
        for ($i = 1; $i <= 20; $i++) {
            $this->add_control(
                "service_{$i}_heading",
                [
                    'label' => sprintf(__('Service Box %d', 'nivaro'), $i),
                    'type' => \Elementor\Controls_Manager::HEADING,
                    'separator' => 'before',
                ]
            );
            
            $this->add_control(
                "service_{$i}_title",
                [
                    'label' => __('Title', 'nivaro'),
                    'type' => \Elementor\Controls_Manager::TEXT,
                    'default' => sprintf(__('Service %d', 'nivaro'), $i),
                    'placeholder' => __('Enter service title', 'nivaro'),
                ]
            );
            
            $this->add_control(
                "service_{$i}_description",
                [
                    'label' => __('Description', 'nivaro'),
                    'type' => \Elementor\Controls_Manager::TEXTAREA,
                    'default' => sprintf(__('Description for service %d', 'nivaro'), $i),
                    'placeholder' => __('Enter service description', 'nivaro'),
                    'rows' => 3,
                ]
            );
            
            $this->add_control(
                "service_{$i}_image",
                [
                    'label' => __('Image', 'nivaro'),
                    'type' => \Elementor\Controls_Manager::MEDIA,
                    'default' => [
                        'url' => \Elementor\Utils::get_placeholder_image_src(),
                    ],
                ]
            );
            
            $this->add_control(
                "service_{$i}_link",
                [
                    'label' => __('Service Page Link', 'nivaro'),
                    'type' => \Elementor\Controls_Manager::URL,
                    'placeholder' => __('https://your-link.com', 'nivaro'),
                    'show_external' => false,
                    'default' => [
                        'url' => '',
                        'is_external' => false,
                        'nofollow' => false,
                    ],
                ]
            );
        }
        
        $this->end_controls_section();
        
        // Style Section
        $this->start_controls_section(
            'style_section',
            [
                'label' => __('Gecko Styling', 'nivaro'),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );
        
        $this->add_control(
            'columns',
            [
                'label' => __('Columns', 'nivaro'),
                'type' => \Elementor\Controls_Manager::SELECT,
                'default' => '4',
                'options' => [
                    '1' => __('1 Column', 'nivaro'),
                    '2' => __('2 Columns', 'nivaro'),
                    '3' => __('3 Columns', 'nivaro'),
                    '4' => __('4 Columns', 'nivaro'),
                    '6' => __('6 Columns', 'nivaro'),
                ],
            ]
        );
        
        $this->add_control(
            'gap',
            [
                'label' => __('Gap', 'nivaro'),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => ['px'],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 50,
                        'step' => 1,
                    ],
                ],
                'default' => [
                    'unit' => 'px',
                    'size' => 20,
                ],
                'selectors' => [
                    '{{WRAPPER}} .gecko-grid' => 'gap: {{SIZE}}{{UNIT}};',
                ],
            ]
        );
        
        $this->end_controls_section();
    }
    
    protected function render() {
        $settings = $this->get_settings_for_display();
        
        $columns = $settings['boxes_per_row'] ?? $settings['columns'] ?? '4';
        ?>
        <div class="gecko-widget">
            <div class="gecko-grid cols-<?php echo esc_attr($columns); ?>">
                <?php for ($i = 1; $i <= 20; $i++) : ?>
                    <?php
                    $title = $settings["service_{$i}_title"] ?? '';
                    $description = $settings["service_{$i}_description"] ?? '';
                    $image = $settings["service_{$i}_image"] ?? [];
                    $service_link = $settings["service_{$i}_link"] ?? [];
                    
                    // Skip empty boxes
                    if (empty($title) && empty($description) && empty($image['url'])) {
                        continue;
                    }
                    ?>
                    <div class="gecko-service-box">
                        <?php if (!empty($service_link['url'])) : ?>
                            <a href="<?php echo esc_url($service_link['url']); ?>" class="gecko-service-link"
                               <?php if ($service_link['is_external']) echo 'target="_blank"'; ?>
                               <?php if ($service_link['nofollow']) echo 'rel="nofollow"'; ?>>
                        <?php endif; ?>
                        
                            <!-- Image area always displays -->
                            <div class="gecko-service-image">
                                <?php if (!empty($image['url'])) : ?>
                                    <img src="<?php echo esc_url($image['url']); ?>" alt="<?php echo esc_attr($title); ?>">
                                <?php endif; ?>
                            </div>
                            
                            <?php if (!empty($title)) : ?>
                                <h3 class="gecko-service-title"><?php echo esc_html($title); ?></h3>
                            <?php endif; ?>
                        
                        <?php if (!empty($service_link['url'])) : ?>
                            </a>
                        <?php endif; ?>
                        
                        <?php if (!empty($description)) : ?>
                            <p class="gecko-service-description"><?php echo esc_html($description); ?></p>
                        <?php endif; ?>
                    </div>
                <?php endfor; ?>
            </div>
        </div>
        <?php
    }
    
    protected function content_template() {
        ?>
        <#
        var columns = settings.boxes_per_row || settings.columns || '4';
        #>
        <div class="gecko-widget">
            <div class="gecko-grid cols-{{{ columns }}}">
                <# for (var i = 1; i <= 20; i++) { #>
                    <#
                    var title = settings['service_' + i + '_title'] || '';
                    var description = settings['service_' + i + '_description'] || '';
                    var image = settings['service_' + i + '_image'] || {};
                    var service_link = settings['service_' + i + '_link'] || {};
                    
                    if (!title && !description && !image.url) {
                        continue;
                    }
                    #>
                    <div class="gecko-service-box">
                        <# if (service_link.url) { #>
                            <a href="{{{ service_link.url }}}" class="gecko-service-link"
                               <# if (service_link.is_external) { #>target="_blank"<# } #>
                               <# if (service_link.nofollow) { #>rel="nofollow"<# } #>>
                        <# } #>
                        
                            <!-- Image area always displays -->
                            <div class="gecko-service-image">
                                <# if (image.url) { #>
                                    <img src="{{{ image.url }}}" alt="{{{ title }}}">
                                <# } #>
                            </div>
                            
                            <# if (title) { #>
                                <h3 class="gecko-service-title">{{{ title }}}</h3>
                            <# } #>
                        
                        <# if (service_link.url) { #>
                            </a>
                        <# } #>
                        
                        <# if (description) { #>
                            <p class="gecko-service-description">{{{ description }}}</p>
                        <# } #>
                    </div>
                <# } #>
            </div>
        </div>
        <?php
    }
}