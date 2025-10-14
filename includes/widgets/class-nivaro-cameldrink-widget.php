<?php
/**
 * Nivaro Cameldrink Service Boxes Widget
 * 
 * Dynamic filtering widget for displaying services based on database criteria
 * Filters by is_active, is_pinned, and various sorting options
 */

if (!defined('ABSPATH')) {
    exit;
}

class Nivaro_Cameldrink_Widget extends \Elementor\Widget_Base {
    
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
        return 'nivaro-cameldrink';
    }
    
    public function get_title() {
        return __('🐪 Cameldrink Service Boxes', 'nivaro');
    }
    
    public function get_icon() {
        return 'eicon-filter';
    }
    
    public function get_categories() {
        return ['nivaro'];
    }
    
    public function get_keywords() {
        return ['services', 'cameldrink', 'filter', 'dynamic', 'nivaro'];
    }
    
    /**
     * Get preset options for dropdown
     */
    private function get_style_preset_options() {
        $presets = $this->get_style_presets();
        $options = [];
        foreach ($presets as $key => $preset) {
            $options[$key] = $preset['label'];
        }
        return $options;
    }
    
    /**
     * Style Presets Configuration
     */
    private function get_style_presets() {
        return [
            'skyburn' => [
                'label' => __('Skyburn Preset (default)', 'nivaro'),
                'settings' => [
                    // Content Settings
                    'image_source' => 'rel_image1_id',
                    'image_frame_height' => '200',
                    'image_alignment' => 'center',
                    'image_display_philosophy' => 'contain',
                    'display_learn_more_button' => 'no',
                    'place_image_inside_subframe' => 'yes',
                    'subframe_height_percentage' => '80',
                    // General Widget Style
                    'widget_border_radius' => ['size' => 12, 'unit' => 'px'],
                    'widget_background_color' => '',
                    // Grid Layout
                    'columns' => '4',
                    'gap' => ['size' => 20, 'unit' => 'px'],
                    'service_box_max_width_toggle' => 'yes',
                    'service_box_max_width_value' => ['size' => 316, 'unit' => 'px'],
                    // Box Shadows
                    'box_shadow_top_blur' => ['size' => 6, 'unit' => 'px'],
                    'box_shadow_top_spread' => ['size' => 3, 'unit' => 'px'],
                    'box_shadow_top_offset' => ['size' => -2, 'unit' => 'px'],
                    'box_shadow_top_color' => 'rgba(0, 0, 0, 0.1)',
                    'box_shadow_right_blur' => ['size' => 4, 'unit' => 'px'],
                    'box_shadow_right_spread' => ['size' => 0, 'unit' => 'px'],
                    'box_shadow_right_offset' => ['size' => 2, 'unit' => 'px'],
                    'box_shadow_right_color' => 'rgba(0, 0, 0, 0.05)',
                    'box_shadow_bottom_blur' => ['size' => 8, 'unit' => 'px'],
                    'box_shadow_bottom_spread' => ['size' => 0, 'unit' => 'px'],
                    'box_shadow_bottom_offset' => ['size' => 2, 'unit' => 'px'],
                    'box_shadow_bottom_color' => 'rgba(0, 0, 0, 0.1)',
                    'box_shadow_left_blur' => ['size' => 0, 'unit' => 'px'],
                    'box_shadow_left_spread' => ['size' => 0, 'unit' => 'px'],
                    'box_shadow_left_offset' => ['size' => -2, 'unit' => 'px'],
                    'box_shadow_left_color' => 'rgba(0, 0, 0, 0.1)',
                    // Image
                    'image_height' => ['size' => 200, 'unit' => 'px'],
                    // Typography
                    'title_color' => '#3163C5',
                    'description_color' => '',
                    // Button
                    'button_text_color' => '#ffffff',
                ]
            ],
            'midnight_elegance' => [
                'label' => __('Midnight Elegance', 'nivaro'),
                'settings' => [
                    // General Widget Style
                    'widget_border_radius' => ['size' => 8, 'unit' => 'px'],
                    'widget_background_color' => '#1a1a2e',
                    // Grid Layout
                    'columns' => '3',
                    'gap' => ['size' => 30, 'unit' => 'px'],
                    'service_box_max_width_toggle' => 'yes',
                    'service_box_max_width_value' => ['size' => 350, 'unit' => 'px'],
                    // Box Shadows - Dramatic dark shadows
                    'box_shadow_top_blur' => ['size' => 15, 'unit' => 'px'],
                    'box_shadow_top_spread' => ['size' => 5, 'unit' => 'px'],
                    'box_shadow_top_offset' => ['size' => -5, 'unit' => 'px'],
                    'box_shadow_top_color' => 'rgba(0, 0, 0, 0.4)',
                    'box_shadow_right_blur' => ['size' => 15, 'unit' => 'px'],
                    'box_shadow_right_spread' => ['size' => 5, 'unit' => 'px'],
                    'box_shadow_right_offset' => ['size' => 5, 'unit' => 'px'],
                    'box_shadow_right_color' => 'rgba(0, 0, 0, 0.4)',
                    'box_shadow_bottom_blur' => ['size' => 20, 'unit' => 'px'],
                    'box_shadow_bottom_spread' => ['size' => 8, 'unit' => 'px'],
                    'box_shadow_bottom_offset' => ['size' => 10, 'unit' => 'px'],
                    'box_shadow_bottom_color' => 'rgba(0, 0, 0, 0.5)',
                    'box_shadow_left_blur' => ['size' => 15, 'unit' => 'px'],
                    'box_shadow_left_spread' => ['size' => 5, 'unit' => 'px'],
                    'box_shadow_left_offset' => ['size' => -5, 'unit' => 'px'],
                    'box_shadow_left_color' => 'rgba(0, 0, 0, 0.4)',
                    // Image
                    'image_height' => ['size' => 250, 'unit' => 'px'],
                    // Typography
                    'title_color' => '#f39c12',
                    'description_color' => '#ecf0f1',
                    // Button
                    'button_text_color' => '#1a1a2e',
                ]
            ],
            'fresh_mint' => [
                'label' => __('Fresh Mint', 'nivaro'),
                'settings' => [
                    // General Widget Style
                    'widget_border_radius' => ['size' => 20, 'unit' => 'px'],
                    'widget_background_color' => '#f0f9ff',
                    // Grid Layout
                    'columns' => '4',
                    'gap' => ['size' => 15, 'unit' => 'px'],
                    'service_box_max_width_toggle' => 'yes',
                    'service_box_max_width_value' => ['size' => 280, 'unit' => 'px'],
                    // Box Shadows - Soft, minimal shadows
                    'box_shadow_top_blur' => ['size' => 0, 'unit' => 'px'],
                    'box_shadow_top_spread' => ['size' => 0, 'unit' => 'px'],
                    'box_shadow_top_offset' => ['size' => 0, 'unit' => 'px'],
                    'box_shadow_top_color' => 'rgba(0, 0, 0, 0)',
                    'box_shadow_right_blur' => ['size' => 10, 'unit' => 'px'],
                    'box_shadow_right_spread' => ['size' => 0, 'unit' => 'px'],
                    'box_shadow_right_offset' => ['size' => 3, 'unit' => 'px'],
                    'box_shadow_right_color' => 'rgba(16, 185, 129, 0.1)',
                    'box_shadow_bottom_blur' => ['size' => 15, 'unit' => 'px'],
                    'box_shadow_bottom_spread' => ['size' => 0, 'unit' => 'px'],
                    'box_shadow_bottom_offset' => ['size' => 5, 'unit' => 'px'],
                    'box_shadow_bottom_color' => 'rgba(16, 185, 129, 0.15)',
                    'box_shadow_left_blur' => ['size' => 0, 'unit' => 'px'],
                    'box_shadow_left_spread' => ['size' => 0, 'unit' => 'px'],
                    'box_shadow_left_offset' => ['size' => 0, 'unit' => 'px'],
                    'box_shadow_left_color' => 'rgba(0, 0, 0, 0)',
                    // Image
                    'image_height' => ['size' => 180, 'unit' => 'px'],
                    // Typography
                    'title_color' => '#10b981',
                    'description_color' => '#6b7280',
                    // Button
                    'button_text_color' => '#ffffff',
                ]
            ],
            'corporate_clean' => [
                'label' => __('Corporate Clean', 'nivaro'),
                'settings' => [
                    // General Widget Style
                    'widget_border_radius' => ['size' => 0, 'unit' => 'px'],
                    'widget_background_color' => '#ffffff',
                    // Grid Layout
                    'columns' => '3',
                    'gap' => ['size' => 40, 'unit' => 'px'],
                    'service_box_max_width_toggle' => 'no',
                    'service_box_max_width_value' => ['size' => 400, 'unit' => 'px'],
                    // Box Shadows - Sharp, professional shadows
                    'box_shadow_top_blur' => ['size' => 0, 'unit' => 'px'],
                    'box_shadow_top_spread' => ['size' => 0, 'unit' => 'px'],
                    'box_shadow_top_offset' => ['size' => -1, 'unit' => 'px'],
                    'box_shadow_top_color' => 'rgba(0, 0, 0, 0.1)',
                    'box_shadow_right_blur' => ['size' => 0, 'unit' => 'px'],
                    'box_shadow_right_spread' => ['size' => 0, 'unit' => 'px'],
                    'box_shadow_right_offset' => ['size' => 1, 'unit' => 'px'],
                    'box_shadow_right_color' => 'rgba(0, 0, 0, 0.1)',
                    'box_shadow_bottom_blur' => ['size' => 4, 'unit' => 'px'],
                    'box_shadow_bottom_spread' => ['size' => 0, 'unit' => 'px'],
                    'box_shadow_bottom_offset' => ['size' => 1, 'unit' => 'px'],
                    'box_shadow_bottom_color' => 'rgba(0, 0, 0, 0.12)',
                    'box_shadow_left_blur' => ['size' => 0, 'unit' => 'px'],
                    'box_shadow_left_spread' => ['size' => 0, 'unit' => 'px'],
                    'box_shadow_left_offset' => ['size' => -1, 'unit' => 'px'],
                    'box_shadow_left_color' => 'rgba(0, 0, 0, 0.1)',
                    // Image
                    'image_height' => ['size' => 220, 'unit' => 'px'],
                    // Typography
                    'title_color' => '#1f2937',
                    'description_color' => '#4b5563',
                    // Button
                    'button_text_color' => '#ffffff',
                ]
            ],
        ];
    }
    
    protected function register_controls() {
        
        // Style Preset Section - At the very top
        $this->start_controls_section(
            'style_preset_section',
            [
                'label' => __('Style Preset', 'nivaro'),
                'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
            ]
        );
        
        $this->add_control(
            'style_preset_selector',
            [
                'label' => __('Choose Style Preset', 'nivaro'),
                'type' => \Elementor\Controls_Manager::SELECT,
                'default' => 'skyburn',
                'options' => $this->get_style_preset_options(),
                'label_block' => true,
            ]
        );
        
        // Add preset data as a hidden control
        $this->add_control(
            'style_preset_data',
            [
                'type' => \Elementor\Controls_Manager::HIDDEN,
                'default' => json_encode($this->get_style_presets()),
            ]
        );
        
        $this->add_control(
            'style_preset_apply_button',
            [
                'type' => \Elementor\Controls_Manager::RAW_HTML,
                'raw' => '
                    <div style="margin-top: 15px;">
                        <button type="button" 
                                id="cameldrink-apply-preset" 
                                class="elementor-button elementor-button-default" 
                                style="width: 100%; padding: 10px; background: #007cba; color: white; border: none; border-radius: 4px; cursor: pointer; font-size: 14px; font-weight: 500;">
                            Apply Selected Preset
                        </button>
                        <p style="margin-top: 10px; font-size: 12px; color: #666; font-style: italic;">
                            After applying a preset, you can still customize individual settings below
                        </p>
                    </div>',
            ]
        );
        
        $this->end_controls_section();
        
        // Content Section
        $this->start_controls_section(
            'filter_section',
            [
                'label' => __('Cameldrink Service Boxes - Settings', 'nivaro'),
                'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
            ]
        );
        
        // Service Count Display
        $total_services = $this->database->get_services_count();
        
        $this->add_control(
            'service_count_display',
            [
                'type' => \Elementor\Controls_Manager::RAW_HTML,
                'raw' => sprintf(
                    '<div style="background: #f0f8ff; border: 2px solid #007cba; padding: 15px; border-radius: 6px; margin-bottom: 15px;">
                        <div style="font-size: 14px; color: #333; margin-bottom: 8px;">
                            <strong>Total Services:</strong> <span style="color: #007cba; font-weight: bold;">%d</span>
                        </div>
                        <div style="font-size: 14px; color: #333;">
                            <strong>Services According To Filters Below:</strong> <span id="cameldrink-filtered-count" style="color: #007cba; font-weight: bold;">%d</span>
                        </div>
                    </div>
                    <div style="font-size: 16px; font-weight: bold; color: #333; margin: 20px 0 15px 0; border-bottom: 2px solid #ddd; padding-bottom: 10px;">
                        Select Services To Display:
                    </div>',
                    $total_services,
                    $total_services // Initially shows all
                ),
            ]
        );
        
        // Separator
        $this->add_control(
            'filter_separator_1',
            [
                'type' => \Elementor\Controls_Manager::DIVIDER,
            ]
        );
        
        // is_active Filter
        $this->add_control(
            'filter_is_active',
            [
                'label' => __('is_active', 'nivaro'),
                'type' => \Elementor\Controls_Manager::SELECT,
                'default' => 'any',
                'options' => [
                    'any' => __('any', 'nivaro'),
                    'yes' => __('yes', 'nivaro'),
                    'no' => __('no', 'nivaro'),
                ],
                'label_block' => true,
            ]
        );
        
        // Separator
        $this->add_control(
            'filter_separator_2',
            [
                'type' => \Elementor\Controls_Manager::DIVIDER,
            ]
        );
        
        // is_pinned Filter
        $this->add_control(
            'filter_is_pinned',
            [
                'label' => __('is_pinned', 'nivaro'),
                'type' => \Elementor\Controls_Manager::SELECT,
                'default' => 'any',
                'options' => [
                    'any' => __('any', 'nivaro'),
                    'yes' => __('yes', 'nivaro'),
                    'no' => __('no', 'nivaro'),
                ],
                'label_block' => true,
            ]
        );
        
        // Separator
        $this->add_control(
            'filter_separator_3',
            [
                'type' => \Elementor\Controls_Manager::DIVIDER,
            ]
        );
        
        // Display Order
        $this->add_control(
            'filter_display_order',
            [
                'label' => __('display order', 'nivaro'),
                'type' => \Elementor\Controls_Manager::SELECT,
                'default' => 'service_id',
                'options' => [
                    'service_id' => __('service_id', 'nivaro'),
                    'position_in_custom_order' => __('position_in_custom_order', 'nivaro'),
                    'pinned_alpha' => __('pinned first, not pinned second, alphabetical sort within the 2 categories', 'nivaro'),
                ],
                'label_block' => true,
            ]
        );
        
        // Separator
        $this->add_control(
            'filter_separator_4',
            [
                'type' => \Elementor\Controls_Manager::DIVIDER,
            ]
        );
        
        // Save Button
        $this->add_control(
            'save_button',
            [
                'type' => \Elementor\Controls_Manager::RAW_HTML,
                'raw' => '
                    <div style="margin-top: 20px;">
                        <button type="button" 
                                id="cameldrink-save-filters" 
                                class="elementor-button elementor-button-default elementor-size-md" 
                                style="width: 100%; padding: 12px; background: #007cba; color: white; border: none; border-radius: 4px; cursor: pointer; font-size: 14px; font-weight: bold;">
                            Save Filter Settings
                        </button>
                    </div>
                    <script>
                    jQuery(document).ready(function($) {
                        // Function to update filtered count
                        function updateCameldrinkCount() {
                            var isActive = $("[data-setting=\'filter_is_active\']").val() || "any";
                            var isPinned = $("[data-setting=\'filter_is_pinned\']").val() || "any";
                            
                            $.ajax({
                                url: nivaro_coyote_ajax.ajax_url,
                                type: "POST",
                                data: {
                                    action: "nivaro_cameldrink_get_count",
                                    is_active: isActive,
                                    is_pinned: isPinned,
                                    nonce: nivaro_coyote_ajax.nonce
                                },
                                success: function(response) {
                                    if (response.success) {
                                        $("#cameldrink-filtered-count").text(response.data.count);
                                    }
                                }
                            });
                        }
                        
                        // Update count when filters change
                        $("[data-setting=\'filter_is_active\'], [data-setting=\'filter_is_pinned\']").on("change", function() {
                            updateCameldrinkCount();
                        });
                        
                        // Save button click handler
                        $(document).on("click", "#cameldrink-save-filters", function(e) {
                            e.preventDefault();
                            
                            // Trigger Elementor save
                            if (typeof elementor !== "undefined") {
                                elementor.saver.setFlagEditorChange(true);
                                $(this).text("Settings Saved!");
                                setTimeout(function() {
                                    $("#cameldrink-save-filters").text("Save Filter Settings");
                                }, 2000);
                            }
                        });
                        
                        // Initial count update
                        setTimeout(updateCameldrinkCount, 500);
                    });
                    </script>',
            ]
        );
        
        // Big Separator
        $this->add_control(
            'filter_big_separator',
            [
                'type' => \Elementor\Controls_Manager::RAW_HTML,
                'raw' => '<div style="margin: 30px 0; border-top: 3px solid #ddd; border-bottom: 3px solid #ddd; height: 10px; background: repeating-linear-gradient(45deg, #f0f0f0, #f0f0f0 10px, #fff 10px, #fff 20px);"></div>',
            ]
        );
        
        // Image Source Selection
        $this->add_control(
            'image_source',
            [
                'label' => __('image source to use', 'nivaro'),
                'type' => \Elementor\Controls_Manager::SELECT,
                'default' => 'rel_image1_id',
                'options' => [
                    'rel_image1_id' => __('rel_image1_id (default option)', 'nivaro'),
                    'rel_icon_image_id' => __('rel_icon_image_id', 'nivaro'),
                ],
                'label_block' => true,
            ]
        );
        
        // Separator
        $this->add_control(
            'image_separator_1',
            [
                'type' => \Elementor\Controls_Manager::DIVIDER,
            ]
        );
        
        // Image Frame Container Height
        $this->add_control(
            'image_frame_height',
            [
                'label' => __('image frame container height', 'nivaro'),
                'type' => \Elementor\Controls_Manager::SELECT,
                'default' => '200',
                'options' => [
                    '100' => __('100 px', 'nivaro'),
                    '150' => __('150 px', 'nivaro'),
                    '200' => __('200 px (default option)', 'nivaro'),
                    'custom' => __('Custom', 'nivaro'),
                ],
                'label_block' => true,
            ]
        );
        
        // Custom Height Input (shows only when custom is selected)
        $this->add_control(
            'image_frame_height_custom',
            [
                'label' => __('Custom Height (px)', 'nivaro'),
                'type' => \Elementor\Controls_Manager::NUMBER,
                'default' => 250,
                'min' => 50,
                'max' => 500,
                'condition' => [
                    'image_frame_height' => 'custom',
                ],
                'label_block' => true,
            ]
        );
        
        // Separator
        $this->add_control(
            'image_separator_2',
            [
                'type' => \Elementor\Controls_Manager::DIVIDER,
            ]
        );
        
        // Image Alignment
        $this->add_control(
            'image_alignment',
            [
                'label' => __('horizontal image alignment inside the frame container', 'nivaro'),
                'type' => \Elementor\Controls_Manager::SELECT,
                'default' => 'center',
                'options' => [
                    'left' => __('left', 'nivaro'),
                    'center' => __('center (default option)', 'nivaro'),
                    'right' => __('right', 'nivaro'),
                ],
                'label_block' => true,
            ]
        );
        
        // Separator
        $this->add_control(
            'image_separator_3',
            [
                'type' => \Elementor\Controls_Manager::DIVIDER,
            ]
        );
        
        // Image Display Philosophy
        $this->add_control(
            'image_display_philosophy',
            [
                'label' => __('general image display philosophy inside frame', 'nivaro'),
                'type' => \Elementor\Controls_Manager::SELECT,
                'default' => 'contain',
                'options' => [
                    'contain' => __('constrain image size however necessary so that it fits entirely inside the frame (default option)', 'nivaro'),
                    'cover_height' => __('fit image height to height of frame', 'nivaro'),
                    'cover_width' => __('fit image width to width of frame', 'nivaro'),
                ],
                'label_block' => true,
            ]
        );
        
        // Separator
        $this->add_control(
            'button_separator',
            [
                'type' => \Elementor\Controls_Manager::DIVIDER,
            ]
        );
        
        // Display Learn More Button
        $this->add_control(
            'display_learn_more_button',
            [
                'label' => __('display the "learn more" button', 'nivaro'),
                'type' => \Elementor\Controls_Manager::SELECT,
                'default' => 'no',
                'options' => [
                    'yes' => __('yes', 'nivaro'),
                    'no' => __('no (default option)', 'nivaro'),
                ],
                'label_block' => true,
            ]
        );
        
        // Separator
        $this->add_control(
            'subframe_separator',
            [
                'type' => \Elementor\Controls_Manager::DIVIDER,
            ]
        );
        
        // Place Image Inside Subframe
        $this->add_control(
            'place_image_inside_subframe',
            [
                'label' => __('place image inside subframe', 'nivaro'),
                'type' => \Elementor\Controls_Manager::SELECT,
                'default' => 'yes',
                'options' => [
                    'no' => __('no', 'nivaro'),
                    'yes' => __('yes (default option)', 'nivaro'),
                ],
                'label_block' => true,
            ]
        );
        
        // Separator
        $this->add_control(
            'subframe_separator_2',
            [
                'type' => \Elementor\Controls_Manager::DIVIDER,
                'condition' => [
                    'place_image_inside_subframe' => 'yes',
                ],
            ]
        );
        
        // Subframe Height Percentage
        $this->add_control(
            'subframe_height_percentage',
            [
                'label' => __('subframe height percentage of main frame', 'nivaro'),
                'type' => \Elementor\Controls_Manager::SELECT,
                'default' => '80',
                'options' => [
                    '80' => __('80% (default option)', 'nivaro'),
                    '70' => __('70%', 'nivaro'),
                    'custom' => __('Custom Percentage', 'nivaro'),
                ],
                'label_block' => true,
                'condition' => [
                    'place_image_inside_subframe' => 'yes',
                ],
            ]
        );
        
        // Custom Percentage Input (shows only when custom is selected)
        $this->add_control(
            'subframe_height_custom',
            [
                'label' => __('Custom Percentage (%)', 'nivaro'),
                'type' => \Elementor\Controls_Manager::NUMBER,
                'default' => 75,
                'min' => 10,
                'max' => 100,
                'condition' => [
                    'place_image_inside_subframe' => 'yes',
                    'subframe_height_percentage' => 'custom',
                ],
                'label_block' => true,
            ]
        );
        
        $this->end_controls_section();
        
        // General Widget Style Settings Section
        $this->start_controls_section(
            'general_widget_style_section',
            [
                'label' => __('general widget style settings', 'nivaro'),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );
        
        $this->add_control(
            'widget_border_radius',
            [
                'label' => __('border radius', 'nivaro'),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => ['px'],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 50,
                    ],
                ],
                'default' => [
                    'unit' => 'px',
                    'size' => 12,
                ],
                'selectors' => [
                    '{{WRAPPER}} .cameldrink-service-box' => 'border-radius: {{SIZE}}{{UNIT}} !important;',
                ],
            ]
        );
        
        $this->add_control(
            'widget_background_color',
            [
                'label' => __('background-color', 'nivaro'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '',
                'selectors' => [
                    '{{WRAPPER}} .cameldrink-widget' => 'background-color: {{VALUE}};',
                ],
            ]
        );
        
        $this->end_controls_section();
        
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
                    '{{WRAPPER}} .cameldrink-grid' => 'grid-template-columns: repeat({{VALUE}}, 1fr);',
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
                    '{{WRAPPER}} .cameldrink-grid' => 'gap: {{SIZE}}{{UNIT}};',
                ],
            ]
        );
        
        $this->add_control(
            'service_box_max_width_toggle',
            [
                'label' => __('service box max width', 'nivaro'),
                'type' => \Elementor\Controls_Manager::SELECT,
                'default' => 'yes',
                'options' => [
                    'no' => __('no max width', 'nivaro'),
                    'yes' => __('yes max width', 'nivaro'),
                ],
                'label_block' => true,
            ]
        );
        
        $this->add_control(
            'service_box_max_width_value',
            [
                'label' => __('max width value', 'nivaro'),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => ['px'],
                'range' => [
                    'px' => [
                        'min' => 200,
                        'max' => 600,
                    ],
                ],
                'default' => [
                    'unit' => 'px',
                    'size' => 316,
                ],
                'selectors' => [
                    '{{WRAPPER}} .cameldrink-service-box' => 'max-width: {{SIZE}}{{UNIT}};',
                ],
                'condition' => [
                    'service_box_max_width_toggle' => 'yes',
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
                'selector' => '{{WRAPPER}} .cameldrink-service-box',
            ]
        );
        
        $this->add_group_control(
            \Elementor\Group_Control_Border::get_type(),
            [
                'name' => 'box_border',
                'label' => __('Border', 'nivaro'),
                'selector' => '{{WRAPPER}} .cameldrink-service-box',
            ]
        );
        
        $this->add_responsive_control(
            'box_border_radius',
            [
                'label' => __('Border Radius', 'nivaro'),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%'],
                'selectors' => [
                    '{{WRAPPER}} .cameldrink-service-box' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        
        // Custom 4-Side Box Shadow Controls
        $this->add_control(
            'box_shadow_heading',
            [
                'type' => \Elementor\Controls_Manager::RAW_HTML,
                'raw' => '<div style="font-size: 16px; font-weight: bold; color: #333; margin: 15px 0 10px 0; border-bottom: 2px solid #ddd; padding-bottom: 8px;">Box Shadow - 4 Sides</div>',
            ]
        );
        
        // Top Shadow
        $this->add_control(
            'box_shadow_top_heading',
            [
                'type' => \Elementor\Controls_Manager::RAW_HTML,
                'raw' => '<div style="font-size: 14px; font-weight: 600; color: #555; margin: 10px 0 5px 0;">Top Shadow</div>',
            ]
        );
        
        $this->add_control(
            'box_shadow_top_blur',
            [
                'label' => __('blur radius', 'nivaro'),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => ['px'],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 30,
                    ],
                ],
                'default' => [
                    'unit' => 'px',
                    'size' => 6,
                ],
            ]
        );
        
        $this->add_control(
            'box_shadow_top_spread',
            [
                'label' => __('spread radius', 'nivaro'),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => ['px'],
                'range' => [
                    'px' => [
                        'min' => -20,
                        'max' => 20,
                    ],
                ],
                'default' => [
                    'unit' => 'px',
                    'size' => 3,
                ],
            ]
        );
        
        $this->add_control(
            'box_shadow_top_offset',
            [
                'label' => __('offset distance', 'nivaro'),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => ['px'],
                'range' => [
                    'px' => [
                        'min' => -20,
                        'max' => 20,
                    ],
                ],
                'default' => [
                    'unit' => 'px',
                    'size' => -2,
                ],
            ]
        );
        
        $this->add_control(
            'box_shadow_top_color',
            [
                'label' => __('color', 'nivaro'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => 'rgba(0, 0, 0, 0.1)',
            ]
        );
        
        // Right Shadow
        $this->add_control(
            'box_shadow_right_heading',
            [
                'type' => \Elementor\Controls_Manager::RAW_HTML,
                'raw' => '<div style="font-size: 14px; font-weight: 600; color: #555; margin: 15px 0 5px 0;">Right Shadow</div>',
            ]
        );
        
        $this->add_control(
            'box_shadow_right_blur',
            [
                'label' => __('blur radius', 'nivaro'),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => ['px'],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 30,
                    ],
                ],
                'default' => [
                    'unit' => 'px',
                    'size' => 4,
                ],
            ]
        );
        
        $this->add_control(
            'box_shadow_right_spread',
            [
                'label' => __('spread radius', 'nivaro'),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => ['px'],
                'range' => [
                    'px' => [
                        'min' => -20,
                        'max' => 20,
                    ],
                ],
                'default' => [
                    'unit' => 'px',
                    'size' => 0,
                ],
            ]
        );
        
        $this->add_control(
            'box_shadow_right_offset',
            [
                'label' => __('offset distance', 'nivaro'),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => ['px'],
                'range' => [
                    'px' => [
                        'min' => -20,
                        'max' => 20,
                    ],
                ],
                'default' => [
                    'unit' => 'px',
                    'size' => 2,
                ],
            ]
        );
        
        $this->add_control(
            'box_shadow_right_color',
            [
                'label' => __('color', 'nivaro'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => 'rgba(0, 0, 0, 0.1)',
            ]
        );
        
        // Bottom Shadow
        $this->add_control(
            'box_shadow_bottom_heading',
            [
                'type' => \Elementor\Controls_Manager::RAW_HTML,
                'raw' => '<div style="font-size: 14px; font-weight: 600; color: #555; margin: 15px 0 5px 0;">Bottom Shadow</div>',
            ]
        );
        
        $this->add_control(
            'box_shadow_bottom_blur',
            [
                'label' => __('blur radius', 'nivaro'),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => ['px'],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 30,
                    ],
                ],
                'default' => [
                    'unit' => 'px',
                    'size' => 8,
                ],
            ]
        );
        
        $this->add_control(
            'box_shadow_bottom_spread',
            [
                'label' => __('spread radius', 'nivaro'),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => ['px'],
                'range' => [
                    'px' => [
                        'min' => -20,
                        'max' => 20,
                    ],
                ],
                'default' => [
                    'unit' => 'px',
                    'size' => 0,
                ],
            ]
        );
        
        $this->add_control(
            'box_shadow_bottom_offset',
            [
                'label' => __('offset distance', 'nivaro'),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => ['px'],
                'range' => [
                    'px' => [
                        'min' => -20,
                        'max' => 20,
                    ],
                ],
                'default' => [
                    'unit' => 'px',
                    'size' => 2,
                ],
            ]
        );
        
        $this->add_control(
            'box_shadow_bottom_color',
            [
                'label' => __('color', 'nivaro'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => 'rgba(0, 0, 0, 0.1)',
            ]
        );
        
        // Left Shadow
        $this->add_control(
            'box_shadow_left_heading',
            [
                'type' => \Elementor\Controls_Manager::RAW_HTML,
                'raw' => '<div style="font-size: 14px; font-weight: 600; color: #555; margin: 15px 0 5px 0;">Left Shadow</div>',
            ]
        );
        
        $this->add_control(
            'box_shadow_left_blur',
            [
                'label' => __('blur radius', 'nivaro'),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => ['px'],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 30,
                    ],
                ],
                'default' => [
                    'unit' => 'px',
                    'size' => 0,
                ],
            ]
        );
        
        $this->add_control(
            'box_shadow_left_spread',
            [
                'label' => __('spread radius', 'nivaro'),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => ['px'],
                'range' => [
                    'px' => [
                        'min' => -20,
                        'max' => 20,
                    ],
                ],
                'default' => [
                    'unit' => 'px',
                    'size' => 0,
                ],
            ]
        );
        
        $this->add_control(
            'box_shadow_left_offset',
            [
                'label' => __('offset distance', 'nivaro'),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => ['px'],
                'range' => [
                    'px' => [
                        'min' => -20,
                        'max' => 20,
                    ],
                ],
                'default' => [
                    'unit' => 'px',
                    'size' => -2,
                ],
            ]
        );
        
        $this->add_control(
            'box_shadow_left_color',
            [
                'label' => __('color', 'nivaro'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => 'rgba(0, 0, 0, 0.1)',
            ]
        );
        
        
        $this->add_responsive_control(
            'box_padding',
            [
                'label' => __('Padding', 'nivaro'),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%'],
                'selectors' => [
                    '{{WRAPPER}} .cameldrink-service-content' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
                    '{{WRAPPER}} .cameldrink-service-image' => 'height: {{SIZE}}{{UNIT}};',
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
                    '{{WRAPPER}} .cameldrink-service-image' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    '{{WRAPPER}} .cameldrink-service-image img' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
                'selector' => '{{WRAPPER}} .cameldrink-service-title',
            ]
        );
        
        $this->add_control(
            'title_color',
            [
                'label' => __('Title Color', 'nivaro'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '#3163C5',
                'selectors' => [
                    '{{WRAPPER}} .cameldrink-service-title' => 'color: {{VALUE}};',
                ],
            ]
        );
        
        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'description_typography',
                'label' => __('Description Typography', 'nivaro'),
                'selector' => '{{WRAPPER}} .cameldrink-service-description',
            ]
        );
        
        $this->add_control(
            'description_color',
            [
                'label' => __('Description Color', 'nivaro'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .cameldrink-service-description' => 'color: {{VALUE}};',
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
                'selector' => '{{WRAPPER}} .cameldrink-btn',
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
                    '{{WRAPPER}} .cameldrink-btn' => 'color: {{VALUE}};',
                ],
            ]
        );
        
        $this->add_group_control(
            \Elementor\Group_Control_Background::get_type(),
            [
                'name' => 'button_background',
                'label' => __('Background', 'nivaro'),
                'types' => ['classic', 'gradient'],
                'selector' => '{{WRAPPER}} .cameldrink-btn',
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
                    '{{WRAPPER}} .cameldrink-btn:hover' => 'color: {{VALUE}};',
                ],
            ]
        );
        
        $this->add_group_control(
            \Elementor\Group_Control_Background::get_type(),
            [
                'name' => 'button_hover_background',
                'label' => __('Background', 'nivaro'),
                'types' => ['classic', 'gradient'],
                'selector' => '{{WRAPPER}} .cameldrink-btn:hover',
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
                    '{{WRAPPER}} .cameldrink-btn' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
                    '{{WRAPPER}} .cameldrink-btn' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        
        $this->end_controls_section();
    }
    
    /**
     * Get filtered services based on widget settings
     */
    private function get_filtered_services($settings) {
        global $wpdb;
        $table_name = $wpdb->prefix . 'zen_services';
        $posts_table = $wpdb->prefix . 'posts';
        
        // Build WHERE clause based on filters
        $where_conditions = [];
        
        // Always filter out services with invalid or unpublished pages
        $where_conditions[] = 's.asn_service_page_id IS NOT NULL';
        $where_conditions[] = "s.asn_service_page_id != ''";
        $where_conditions[] = 'p.ID IS NOT NULL';
        $where_conditions[] = "p.post_status = 'publish'";
        
        // Filter by is_active
        if ($settings['filter_is_active'] === 'yes') {
            $where_conditions[] = 's.is_active = 1';
        } elseif ($settings['filter_is_active'] === 'no') {
            $where_conditions[] = '(s.is_active = 0 OR s.is_active IS NULL)';
        }
        
        // Filter by is_pinned
        if ($settings['filter_is_pinned'] === 'yes') {
            $where_conditions[] = 's.is_pinned = 1';
        } elseif ($settings['filter_is_pinned'] === 'no') {
            $where_conditions[] = '(s.is_pinned = 0 OR s.is_pinned IS NULL)';
        }
        
        // Build WHERE clause
        $where = '';
        if (!empty($where_conditions)) {
            $where = 'WHERE ' . implode(' AND ', $where_conditions);
        }
        
        // Build ORDER BY clause based on display order
        $order_by = '';
        switch ($settings['filter_display_order']) {
            case 'position_in_custom_order':
                $order_by = 'ORDER BY s.position_in_custom_order ASC, s.service_id ASC';
                break;
            case 'pinned_alpha':
                // Pinned items first (sorted alphabetically), then unpinned (sorted alphabetically)
                $order_by = 'ORDER BY COALESCE(s.is_pinned, 0) DESC, s.service_name ASC';
                break;
            case 'service_id':
            default:
                $order_by = 'ORDER BY s.service_id ASC';
                break;
        }
        
        // Build and execute query with LEFT JOIN to check post validity
        $query = "
            SELECT s.* 
            FROM {$table_name} s
            LEFT JOIN {$posts_table} p ON s.asn_service_page_id = p.ID
            {$where} 
            {$order_by}
        ";
        $services = $wpdb->get_results($query);
        
        // Add dynamic links to services
        foreach ($services as &$service) {
            $service->dynamic_link = $this->database->get_service_dynamic_link($service->service_id);
        }
        
        return $services;
    }
    
    protected function render() {
        $settings = $this->get_settings_for_display();
        
        // Generate 4-side box shadow CSS
        $this->generate_box_shadow_css($settings);
        
        // Get filtered services
        $services = $this->get_filtered_services($settings);
        
        if (empty($services)) {
            echo '<div class="cameldrink-no-services">No services found matching the selected filters.</div>';
            return;
        }
        
        ?>
        <div class="cameldrink-widget">
            <div class="cameldrink-grid">
                <?php foreach ($services as $service): ?>
                    <?php $this->render_service_box($service, $settings); ?>
                <?php endforeach; ?>
            </div>
        </div>
        <?php
    }
    
    /**
     * Generate CSS for 4-side box shadow
     */
    private function generate_box_shadow_css($settings) {
        $shadow_parts = [];
        
        // Top shadow: 0 offset-y blur spread color
        $top_offset = isset($settings['box_shadow_top_offset']['size']) ? (int)$settings['box_shadow_top_offset']['size'] : -2;
        $top_blur = isset($settings['box_shadow_top_blur']['size']) ? (int)$settings['box_shadow_top_blur']['size'] : 0;
        $top_spread = isset($settings['box_shadow_top_spread']['size']) ? (int)$settings['box_shadow_top_spread']['size'] : 0;
        $top_color = $settings['box_shadow_top_color'] ?? 'rgba(0, 0, 0, 0.1)';
        
        if ($top_blur > 0 || $top_offset != 0) {
            $shadow_parts[] = "0 {$top_offset}px {$top_blur}px {$top_spread}px {$top_color}";
        }
        
        // Right shadow: offset-x 0 blur spread color
        $right_offset = isset($settings['box_shadow_right_offset']['size']) ? (int)$settings['box_shadow_right_offset']['size'] : 2;
        $right_blur = isset($settings['box_shadow_right_blur']['size']) ? (int)$settings['box_shadow_right_blur']['size'] : 4;
        $right_spread = isset($settings['box_shadow_right_spread']['size']) ? (int)$settings['box_shadow_right_spread']['size'] : 0;
        $right_color = $settings['box_shadow_right_color'] ?? 'rgba(0, 0, 0, 0.1)';
        
        if ($right_blur > 0 || $right_offset != 0) {
            $shadow_parts[] = "{$right_offset}px 0 {$right_blur}px {$right_spread}px {$right_color}";
        }
        
        // Bottom shadow: 0 offset-y blur spread color
        $bottom_offset = isset($settings['box_shadow_bottom_offset']['size']) ? (int)$settings['box_shadow_bottom_offset']['size'] : 2;
        $bottom_blur = isset($settings['box_shadow_bottom_blur']['size']) ? (int)$settings['box_shadow_bottom_blur']['size'] : 8;
        $bottom_spread = isset($settings['box_shadow_bottom_spread']['size']) ? (int)$settings['box_shadow_bottom_spread']['size'] : 0;
        $bottom_color = $settings['box_shadow_bottom_color'] ?? 'rgba(0, 0, 0, 0.1)';
        
        if ($bottom_blur > 0 || $bottom_offset != 0) {
            $shadow_parts[] = "0 {$bottom_offset}px {$bottom_blur}px {$bottom_spread}px {$bottom_color}";
        }
        
        // Left shadow: offset-x 0 blur spread color
        $left_offset = isset($settings['box_shadow_left_offset']['size']) ? (int)$settings['box_shadow_left_offset']['size'] : -2;
        $left_blur = isset($settings['box_shadow_left_blur']['size']) ? (int)$settings['box_shadow_left_blur']['size'] : 0;
        $left_spread = isset($settings['box_shadow_left_spread']['size']) ? (int)$settings['box_shadow_left_spread']['size'] : 0;
        $left_color = $settings['box_shadow_left_color'] ?? 'rgba(0, 0, 0, 0.1)';
        
        if ($left_blur > 0 || $left_offset != 0) {
            $shadow_parts[] = "{$left_offset}px 0 {$left_blur}px {$left_spread}px {$left_color}";
        }
        
        // Output combined CSS if we have any shadows
        if (!empty($shadow_parts)) {
            $combined_shadow = implode(', ', $shadow_parts);
            $unique_class = 'cameldrink-shadow-' . $this->get_id();
            
            echo '<style>';
            echo ".elementor-element-{$this->get_id()} .cameldrink-service-box { box-shadow: {$combined_shadow} !important; }";
            echo '</style>';
        }
    }
    
    private function render_service_box($service, $settings) {
        // Get image based on selected source - NO FALLBACK
        $image_source = $settings['image_source'] ?? 'rel_image1_id';
        $image_url = '';
        
        if ($image_source === 'rel_icon_image_id') {
            // Only use icon image if selected
            if (!empty($service->rel_icon_image_id)) {
                $image_url = wp_get_attachment_url($service->rel_icon_image_id);
            }
        } else {
            // Use rel_image1_id only if it's the selected source
            if (!empty($service->rel_image1_id)) {
                $image_url = wp_get_attachment_url($service->rel_image1_id);
            }
        }
        
        // Get other service data
        $title = $service->service_name ?? '';
        $description = $service->service_placard ?? '';
        $short_description = $service->description1_short ?? '';
        $dynamic_link = $service->dynamic_link ?? '#';
        
        // Get image settings
        $frame_height = $settings['image_frame_height'] ?? '200';
        if ($frame_height === 'custom') {
            $frame_height = $settings['image_frame_height_custom'] ?? 250;
        }
        $image_alignment = $settings['image_alignment'] ?? 'center';
        $display_philosophy = $settings['image_display_philosophy'] ?? 'contain';
        $display_button = $settings['display_learn_more_button'] ?? 'no';
        $use_subframe = $settings['place_image_inside_subframe'] ?? 'no';
        $subframe_height = $settings['subframe_height_percentage'] ?? '80';
        if ($subframe_height === 'custom') {
            $subframe_height = $settings['subframe_height_custom'] ?? 75;
        }
        
        // Convert alignment to object-position value
        $object_position = $image_alignment . ' center';
        
        // Determine CSS object-fit based on philosophy
        $object_fit = 'cover'; // default
        $additional_styles = '';
        
        switch ($display_philosophy) {
            case 'contain':
                $object_fit = 'contain';
                break;
            case 'cover_height':
                // Fit height to frame height, may overflow width
                $object_fit = 'cover';
                $additional_styles = 'height: 100%; width: auto; max-width: none;';
                break;
            case 'cover_width':
                // Fit width to frame width, may overflow height  
                $object_fit = 'cover';
                $additional_styles = 'width: 100%; height: auto; max-height: none;';
                break;
        }
        
        // Add class if using icon image
        $container_classes = 'service-image-frame-container cameldrink-service-image';
        if ($image_source === 'rel_icon_image_id') {
            $container_classes .= ' using-icon';
        }
        
        ?>
        <div class="cameldrink-service-box">
            <?php if ($image_url): ?>
                <div class="<?php echo esc_attr($container_classes); ?>" 
                     style="height: <?php echo esc_attr($frame_height); ?>px;">
                    <?php if ($use_subframe === 'yes'): ?>
                        <div class="image-subframe-container" 
                             style="height: <?php echo esc_attr($subframe_height); ?>%; width: 80%; margin: auto;">
                            <img src="<?php echo esc_url($image_url); ?>" 
                                 alt="<?php echo esc_attr($title); ?>" 
                                 loading="lazy"
                                 style="object-fit: <?php echo esc_attr($object_fit); ?>; object-position: <?php echo esc_attr($object_position); ?>; <?php echo $additional_styles; ?>">
                        </div>
                    <?php else: ?>
                        <img src="<?php echo esc_url($image_url); ?>" 
                             alt="<?php echo esc_attr($title); ?>" 
                             loading="lazy"
                             style="object-fit: <?php echo esc_attr($object_fit); ?>; object-position: <?php echo esc_attr($object_position); ?>; <?php echo $additional_styles; ?>">
                    <?php endif; ?>
                </div>
            <?php else: ?>
                <div class="<?php echo esc_attr($container_classes); ?>" 
                     style="height: <?php echo esc_attr($frame_height); ?>px; background: #f5f5f5;">
                    <?php if ($use_subframe === 'yes'): ?>
                        <div class="image-subframe-container" 
                             style="height: <?php echo esc_attr($subframe_height); ?>%; width: 80%; margin: auto; background: #e5e5e5;">
                            <!-- No image for selected source: <?php echo esc_html($image_source); ?> -->
                        </div>
                    <?php else: ?>
                        <!-- No image for selected source: <?php echo esc_html($image_source); ?> -->
                    <?php endif; ?>
                </div>
            <?php endif; ?>
            
            <div class="cameldrink-service-content">
                <?php if ($title): ?>
                    <h3 class="cameldrink-service-title">
                        <a href="<?php echo esc_url($dynamic_link); ?>" class="cameldrink-title-link">
                            <?php echo esc_html($title); ?>
                        </a>
                    </h3>
                <?php endif; ?>
                
                <?php if ($short_description): ?>
                    <p class="cameldrink-service-short-description"><?php echo esc_html($short_description); ?></p>
                <?php endif; ?>
                
                <?php if ($description): ?>
                    <p class="cameldrink-service-description"><?php echo esc_html($description); ?></p>
                <?php endif; ?>
                
                <?php if ($display_button === 'yes'): ?>
                    <div class="cameldrink-service-button">
                        <a href="<?php echo esc_url($dynamic_link); ?>" 
                           class="cameldrink-btn">
                            Learn More
                        </a>
                    </div>
                <?php endif; ?>
            </div>
        </div>
        <?php
    }
    
    protected function content_template() {
        ?>
        <#
        // Preview message for editor
        #>
        <div class="cameldrink-widget">
            <div style="background: #f9f9f9; border: 2px dashed #007cba; padding: 40px; text-align: center; border-radius: 8px;">
                <h3 style="color: #007cba; margin: 0 0 10px 0;">🐪 Cameldrink Service Boxes</h3>
                <p style="margin: 0 0 15px 0; color: #666;">Dynamic filtered services will display here on the frontend</p>
                <div style="font-size: 13px; color: #888;">
                    <div>is_active: <strong>{{ settings.filter_is_active }}</strong></div>
                    <div>is_pinned: <strong>{{ settings.filter_is_pinned }}</strong></div>
                    <div>order: <strong>{{ settings.filter_display_order }}</strong></div>
                </div>
            </div>
        </div>
        <?php
    }
}