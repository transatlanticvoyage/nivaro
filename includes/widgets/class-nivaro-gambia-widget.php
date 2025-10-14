<?php
/**
 * Nivaro Gambia Services Link List Widget
 * 
 * Creates a simple list of service links with dynamic filtering
 * Displays services as an HTML unordered list with links
 */

if (!defined('ABSPATH')) {
    exit;
}

class Nivaro_Gambia_Widget extends \Elementor\Widget_Base {
    
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
        return 'nivaro-gambia';
    }
    
    public function get_title() {
        return __('🇬🇲 Gambia Services Link List v2', 'nivaro');
    }
    
    public function get_icon() {
        return 'eicon-editor-list-ul';
    }
    
    public function get_categories() {
        return ['nivaro'];
    }
    
    public function get_keywords() {
        return ['services', 'gambia', 'links', 'list', 'menu', 'nivaro'];
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
            'orange_skin_1' => [
                'label' => __('Orange Skin 1 (default)', 'nivaro'),
                'settings' => [
                    // General Widget Style
                    'widget_border_radius' => ['size' => 24, 'unit' => 'px'],
                    'widget_background_color' => '#E7851D',
                    'widget_padding_top' => ['size' => 20, 'unit' => 'px'],
                    'widget_padding_bottom' => ['size' => 20, 'unit' => 'px'],
                    'widget_padding_left' => ['size' => 20, 'unit' => 'px'],
                    'widget_padding_right' => ['size' => 20, 'unit' => 'px'],
                    // Heading
                    'heading_label_color' => '#ffffff',
                    'heading_label_font_weight' => '700',
                    'heading_label_font_family' => 'Helvetica, Arial, sans-serif',
                    // Link Text
                    'link_text_font_size' => ['size' => 21, 'unit' => 'px'],
                    'link_text_color' => '#030303',
                    'link_text_font_weight' => '500',
                    // Current Page
                    'current_page_background_color' => '#18293E',
                    'current_page_text_color' => '#ffffff',
                    // List Items
                    'list_item_vertical_spacing' => ['size' => 26, 'unit' => 'px'],
                    'list_item_padding_top' => ['size' => 5, 'unit' => 'px'],
                    'list_item_padding_right' => ['size' => 14, 'unit' => 'px'],
                    'list_item_padding_bottom' => ['size' => 5, 'unit' => 'px'],
                    'list_item_padding_left' => ['size' => 14, 'unit' => 'px'],
                    'list_item_border_radius' => ['size' => 9, 'unit' => 'px'],
                ]
            ],
            'corporate_blue' => [
                'label' => __('Corporate Blue', 'nivaro'),
                'settings' => [
                    // General Widget Style
                    'widget_border_radius' => ['size' => 8, 'unit' => 'px'],
                    'widget_background_color' => '#1e3a5f',
                    'widget_padding_top' => ['size' => 25, 'unit' => 'px'],
                    'widget_padding_bottom' => ['size' => 25, 'unit' => 'px'],
                    'widget_padding_left' => ['size' => 25, 'unit' => 'px'],
                    'widget_padding_right' => ['size' => 25, 'unit' => 'px'],
                    // Heading
                    'heading_label_color' => '#ffffff',
                    'heading_label_font_weight' => '600',
                    'heading_label_font_family' => 'Georgia, serif',
                    // Link Text
                    'link_text_font_size' => ['size' => 18, 'unit' => 'px'],
                    'link_text_color' => '#e0e8f0',
                    'link_text_font_weight' => '400',
                    // Current Page
                    'current_page_background_color' => '#4a90e2',
                    'current_page_text_color' => '#ffffff',
                    // List Items
                    'list_item_vertical_spacing' => ['size' => 15, 'unit' => 'px'],
                    'list_item_padding_top' => ['size' => 8, 'unit' => 'px'],
                    'list_item_padding_right' => ['size' => 16, 'unit' => 'px'],
                    'list_item_padding_bottom' => ['size' => 8, 'unit' => 'px'],
                    'list_item_padding_left' => ['size' => 16, 'unit' => 'px'],
                    'list_item_border_radius' => ['size' => 4, 'unit' => 'px'],
                ]
            ],
            'dark_professional' => [
                'label' => __('Dark Professional', 'nivaro'),
                'settings' => [
                    // General Widget Style
                    'widget_border_radius' => ['size' => 12, 'unit' => 'px'],
                    'widget_background_color' => '#2c2c2c',
                    'widget_padding_top' => ['size' => 30, 'unit' => 'px'],
                    'widget_padding_bottom' => ['size' => 30, 'unit' => 'px'],
                    'widget_padding_left' => ['size' => 30, 'unit' => 'px'],
                    'widget_padding_right' => ['size' => 30, 'unit' => 'px'],
                    // Heading
                    'heading_label_color' => '#ffffff',
                    'heading_label_font_weight' => '300',
                    'heading_label_font_family' => 'Roboto, sans-serif',
                    // Link Text
                    'link_text_font_size' => ['size' => 17, 'unit' => 'px'],
                    'link_text_color' => '#b8b8b8',
                    'link_text_font_weight' => '300',
                    // Current Page
                    'current_page_background_color' => '#ffffff',
                    'current_page_text_color' => '#2c2c2c',
                    // List Items
                    'list_item_vertical_spacing' => ['size' => 12, 'unit' => 'px'],
                    'list_item_padding_top' => ['size' => 10, 'unit' => 'px'],
                    'list_item_padding_right' => ['size' => 20, 'unit' => 'px'],
                    'list_item_padding_bottom' => ['size' => 10, 'unit' => 'px'],
                    'list_item_padding_left' => ['size' => 20, 'unit' => 'px'],
                    'list_item_border_radius' => ['size' => 6, 'unit' => 'px'],
                ]
            ],
            'minimal_clean' => [
                'label' => __('Minimal Clean', 'nivaro'),
                'settings' => [
                    // General Widget Style
                    'widget_border_radius' => ['size' => 0, 'unit' => 'px'],
                    'widget_background_color' => '#ffffff',
                    'widget_padding_top' => ['size' => 40, 'unit' => 'px'],
                    'widget_padding_bottom' => ['size' => 40, 'unit' => 'px'],
                    'widget_padding_left' => ['size' => 0, 'unit' => 'px'],
                    'widget_padding_right' => ['size' => 0, 'unit' => 'px'],
                    // Heading
                    'heading_label_color' => '#000000',
                    'heading_label_font_weight' => '400',
                    'heading_label_font_family' => 'system-ui, -apple-system, sans-serif',
                    // Link Text
                    'link_text_font_size' => ['size' => 16, 'unit' => 'px'],
                    'link_text_color' => '#333333',
                    'link_text_font_weight' => '400',
                    // Current Page
                    'current_page_background_color' => 'transparent',
                    'current_page_text_color' => '#000000',
                    // List Items
                    'list_item_vertical_spacing' => ['size' => 20, 'unit' => 'px'],
                    'list_item_padding_top' => ['size' => 12, 'unit' => 'px'],
                    'list_item_padding_right' => ['size' => 0, 'unit' => 'px'],
                    'list_item_padding_bottom' => ['size' => 12, 'unit' => 'px'],
                    'list_item_padding_left' => ['size' => 0, 'unit' => 'px'],
                    'list_item_border_radius' => ['size' => 0, 'unit' => 'px'],
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
                'default' => 'orange_skin_1',
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
                                id="gambia-apply-preset" 
                                class="elementor-button elementor-button-default" 
                                style="width: 100%; padding: 10px; background: #007cba; color: white; border: none; border-radius: 4px; cursor: pointer; font-size: 14px; font-weight: 500;">
                            Apply Selected Preset
                        </button>
                        <p style="margin-top: 10px; font-size: 12px; color: #666; font-style: italic;">
                            After applying a preset, you can still customize individual settings below
                        </p>
                    </div>
                    <script>
                    (function() {
                        // Wait for jQuery and Elementor to be available
                        var checkReady = setInterval(function() {
                            if (typeof jQuery !== "undefined" && jQuery("[data-setting=\'style_preset_selector\']").length) {
                                clearInterval(checkReady);
                                initGambiaPresets();
                            }
                        }, 100);
                        
                        function initGambiaPresets() {
                            var $ = jQuery;
                            var stylePresets = ' . json_encode($this->get_style_presets()) . ';
                            
                            console.log("Gambia presets initialized", stylePresets);
                            
                            // Apply preset button click handler
                            $(document).off("click.gambiaPreset").on("click.gambiaPreset", "#gambia-apply-preset", function(e) {
                                e.preventDefault();
                                console.log("Apply preset clicked");
                                
                                var selectedPreset = $("[data-setting=\'style_preset_selector\']").val();
                                console.log("Selected preset:", selectedPreset);
                                
                                if (!selectedPreset || !stylePresets[selectedPreset]) {
                                    console.log("No valid preset selected");
                                    return;
                                }
                                
                                var presetSettings = stylePresets[selectedPreset].settings;
                                console.log("Applying settings:", presetSettings);
                                
                                // Apply each preset setting to its corresponding control
                                for (var settingKey in presetSettings) {
                                    var settingValue = presetSettings[settingKey];
                                    
                                    // Find the control container
                                    var $controlContainer = $(".elementor-control-" + settingKey);
                                    
                                    if ($controlContainer.length) {
                                        var $input = $controlContainer.find("input, select, textarea").first();
                                        
                                        if ($input.length) {
                                            // Handle different control types
                                            if (typeof settingValue === "object" && settingValue.size !== undefined) {
                                                // Slider control
                                                $input.val(settingValue.size);
                                                $input.trigger("input").trigger("change");
                                                
                                                // Update the slider UI
                                                var $slider = $controlContainer.find(".elementor-slider");
                                                if ($slider.length && $slider.data("uiSlider")) {
                                                    $slider.slider("value", settingValue.size);
                                                }
                                            } else if ($input.attr("type") === "color" || $controlContainer.hasClass("elementor-control-type-color")) {
                                                // Color control
                                                $input.val(settingValue);
                                                $input.trigger("input").trigger("change");
                                                
                                                // Update color picker button if exists
                                                var $colorButton = $controlContainer.find(".pcr-button");
                                                if ($colorButton.length) {
                                                    $colorButton.css("color", settingValue);
                                                }
                                            } else {
                                                // Regular control
                                                $input.val(settingValue);
                                                $input.trigger("input").trigger("change");
                                            }
                                            
                                            console.log("Set " + settingKey + " to", settingValue);
                                        }
                                    }
                                }
                                
                                // Show success message
                                $("#gambia-apply-preset").text("Preset Applied!");
                                $("#gambia-apply-preset").css("background", "#46b450");
                                
                                // Reset button after 2 seconds
                                setTimeout(function() {
                                    $("#gambia-apply-preset").text("Apply Selected Preset");
                                    $("#gambia-apply-preset").css("background", "#007cba");
                                }, 2000);
                                
                                // Trigger Elementor to recognize changes
                                if (typeof elementor !== "undefined") {
                                    elementor.reloadPreview();
                                }
                            });
                        }
                    })();
                    </script>',
            ]
        );
        
        $this->end_controls_section();
        
        // Content Section
        $this->start_controls_section(
            'filter_section',
            [
                'label' => __('Gambia Services Link List - Settings', 'nivaro'),
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
                            <strong>Services According To Filters Below:</strong> <span id="gambia-filtered-count" style="color: #007cba; font-weight: bold;">%d</span>
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
                                id="gambia-save-filters" 
                                class="elementor-button elementor-button-default elementor-size-md" 
                                style="width: 100%; padding: 12px; background: #007cba; color: white; border: none; border-radius: 4px; cursor: pointer; font-size: 14px; font-weight: bold;">
                            Save Filter Settings
                        </button>
                    </div>
                    <script>
                    jQuery(document).ready(function($) {
                        // Function to update filtered count
                        function updateGambiaCount() {
                            var isActive = $("[data-setting=\'filter_is_active\']").val() || "any";
                            var isPinned = $("[data-setting=\'filter_is_pinned\']").val() || "any";
                            
                            $.ajax({
                                url: nivaro_coyote_ajax.ajax_url,
                                type: "POST",
                                data: {
                                    action: "nivaro_gambia_get_count",
                                    is_active: isActive,
                                    is_pinned: isPinned,
                                    nonce: nivaro_coyote_ajax.nonce
                                },
                                success: function(response) {
                                    if (response.success) {
                                        $("#gambia-filtered-count").text(response.data.count);
                                    }
                                }
                            });
                        }
                        
                        // Update count when filters change
                        $("[data-setting=\'filter_is_active\'], [data-setting=\'filter_is_pinned\']").on("change", function() {
                            updateGambiaCount();
                        });
                        
                        // Save button click handler
                        $(document).on("click", "#gambia-save-filters", function(e) {
                            e.preventDefault();
                            
                            // Trigger Elementor save
                            if (typeof elementor !== "undefined") {
                                elementor.saver.setFlagEditorChange(true);
                                $(this).text("Settings Saved!");
                                setTimeout(function() {
                                    $("#gambia-save-filters").text("Save Filter Settings");
                                }, 2000);
                            }
                        });
                        
                        // Initial count update
                        setTimeout(updateGambiaCount, 500);
                    });
                    </script>',
            ]
        );
        
        // Separator
        $this->add_control(
            'heading_separator',
            [
                'type' => \Elementor\Controls_Manager::DIVIDER,
            ]
        );
        
        // Heading Label Text
        $this->add_control(
            'heading_label_text',
            [
                'label' => __('heading label text', 'nivaro'),
                'type' => \Elementor\Controls_Manager::TEXT,
                'default' => __('Our Services', 'nivaro'),
                'label_block' => true,
                'placeholder' => __('Enter heading text...', 'nivaro'),
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
            'widget_width_desktop',
            [
                'label' => __('widget width (desktop viewport)', 'nivaro'),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => ['px', '%'],
                'range' => [
                    'px' => [
                        'min' => 200,
                        'max' => 800,
                    ],
                    '%' => [
                        'min' => 10,
                        'max' => 100,
                    ],
                ],
                'default' => [
                    'unit' => 'px',
                    'size' => 370,
                ],
                'selectors' => [
                    '{{WRAPPER}} .gambia-widget' => 'max-width: {{SIZE}}{{UNIT}}; width: 100%;',
                ],
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
                    'size' => 24,
                ],
                'selectors' => [
                    '{{WRAPPER}} .gambia-widget' => 'border-radius: {{SIZE}}{{UNIT}};',
                ],
            ]
        );
        
        $this->add_control(
            'widget_background_color',
            [
                'label' => __('background-color', 'nivaro'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '#E7851D',
                'selectors' => [
                    '{{WRAPPER}} .gambia-widget' => 'background-color: {{VALUE}};',
                ],
            ]
        );
        
        $this->add_control(
            'widget_padding_top',
            [
                'label' => __('padding-top', 'nivaro'),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => ['px'],
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
                    '{{WRAPPER}} .gambia-widget' => 'padding-top: {{SIZE}}{{UNIT}};',
                ],
            ]
        );
        
        $this->add_control(
            'widget_padding_bottom',
            [
                'label' => __('padding-bottom', 'nivaro'),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => ['px'],
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
                    '{{WRAPPER}} .gambia-widget' => 'padding-bottom: {{SIZE}}{{UNIT}};',
                ],
            ]
        );
        
        $this->add_control(
            'widget_padding_left',
            [
                'label' => __('padding-left', 'nivaro'),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => ['px'],
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
                    '{{WRAPPER}} .gambia-widget' => 'padding-left: {{SIZE}}{{UNIT}};',
                ],
            ]
        );
        
        $this->add_control(
            'widget_padding_right',
            [
                'label' => __('padding-right', 'nivaro'),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => ['px'],
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
                    '{{WRAPPER}} .gambia-widget' => 'padding-right: {{SIZE}}{{UNIT}};',
                ],
            ]
        );
        
        $this->end_controls_section();
        
        // Style Options Section
        $this->start_controls_section(
            'style_options_section',
            [
                'label' => __('STYLE OPTIONS', 'nivaro'),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );
        
        // Heading Label Text Styles
        $this->add_control(
            'heading_label_styles_heading',
            [
                'type' => \Elementor\Controls_Manager::RAW_HTML,
                'raw' => '<div style="font-size: 16px; font-weight: bold; color: #333; margin: 15px 0 10px 0; border-bottom: 2px solid #ddd; padding-bottom: 8px;">heading label text</div>',
            ]
        );
        
        $this->add_control(
            'heading_label_color',
            [
                'label' => __('color', 'nivaro'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '#ffffff',
                'selectors' => [
                    '{{WRAPPER}} .gambia-list-heading' => 'color: {{VALUE}} !important;',
                ],
            ]
        );
        
        $this->add_control(
            'heading_label_font_weight',
            [
                'label' => __('font weight', 'nivaro'),
                'type' => \Elementor\Controls_Manager::SELECT,
                'default' => '700',
                'options' => [
                    '300' => __('300 (Light)', 'nivaro'),
                    '400' => __('400 (Normal)', 'nivaro'),
                    '500' => __('500 (Medium)', 'nivaro'),
                    '600' => __('600 (Semi Bold)', 'nivaro'),
                    '700' => __('700 (Bold)', 'nivaro'),
                    '800' => __('800 (Extra Bold)', 'nivaro'),
                ],
                'selectors' => [
                    '{{WRAPPER}} .gambia-list-heading' => 'font-weight: {{VALUE}};',
                ],
            ]
        );
        
        $this->add_control(
            'heading_label_font_family',
            [
                'label' => __('font family', 'nivaro'),
                'type' => \Elementor\Controls_Manager::FONT,
                'default' => 'Helvetica, Arial, sans-serif',
                'selectors' => [
                    '{{WRAPPER}} .gambia-list-heading' => 'font-family: {{VALUE}};',
                ],
            ]
        );
        
        // Separator
        $this->add_control(
            'style_separator_1',
            [
                'type' => \Elementor\Controls_Manager::DIVIDER,
            ]
        );
        
        // Link Text Styles
        $this->add_control(
            'link_text_styles_heading',
            [
                'type' => \Elementor\Controls_Manager::RAW_HTML,
                'raw' => '<div style="font-size: 16px; font-weight: bold; color: #333; margin: 15px 0 10px 0; border-bottom: 2px solid #ddd; padding-bottom: 8px;">link text</div>',
            ]
        );
        
        $this->add_control(
            'link_text_font_size',
            [
                'label' => __('font size', 'nivaro'),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => ['px'],
                'range' => [
                    'px' => [
                        'min' => 10,
                        'max' => 30,
                    ],
                ],
                'default' => [
                    'unit' => 'px',
                    'size' => 21,
                ],
                'selectors' => [
                    '{{WRAPPER}} .gambia-service-link' => 'font-size: {{SIZE}}{{UNIT}};',
                ],
            ]
        );
        
        $this->add_control(
            'link_text_color',
            [
                'label' => __('color', 'nivaro'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '#030303',
                'selectors' => [
                    '{{WRAPPER}} .gambia-service-link' => 'color: {{VALUE}} !important;',
                    '{{WRAPPER}} .gambia-service-link:visited' => 'color: {{VALUE}} !important;',
                ],
            ]
        );
        
        $this->add_control(
            'link_text_font_weight',
            [
                'label' => __('font weight', 'nivaro'),
                'type' => \Elementor\Controls_Manager::SELECT,
                'default' => '500',
                'options' => [
                    '300' => __('300 (Light)', 'nivaro'),
                    '400' => __('400 (Normal)', 'nivaro'),
                    '500' => __('500 (Medium)', 'nivaro'),
                    '600' => __('600 (Semi Bold)', 'nivaro'),
                    '700' => __('700 (Bold)', 'nivaro'),
                    '800' => __('800 (Extra Bold)', 'nivaro'),
                ],
                'selectors' => [
                    '{{WRAPPER}} .gambia-service-link' => 'font-weight: {{VALUE}};',
                ],
            ]
        );
        
        $this->add_control(
            'link_text_font_family',
            [
                'label' => __('font family', 'nivaro'),
                'type' => \Elementor\Controls_Manager::FONT,
                'default' => '',
                'selectors' => [
                    '{{WRAPPER}} .gambia-service-link' => 'font-family: {{VALUE}};',
                ],
            ]
        );
        
        // Separator
        $this->add_control(
            'style_separator_2',
            [
                'type' => \Elementor\Controls_Manager::DIVIDER,
            ]
        );
        
        // Currently Open Page Link Styles
        $this->add_control(
            'current_page_styles_heading',
            [
                'type' => \Elementor\Controls_Manager::RAW_HTML,
                'raw' => '<div style="font-size: 16px; font-weight: bold; color: #333; margin: 15px 0 10px 0; border-bottom: 2px solid #ddd; padding-bottom: 8px;">currently open page &lt;li&gt;&lt;/li&gt; item</div>',
            ]
        );
        
        $this->add_control(
            'current_page_background_color',
            [
                'label' => __('background-color', 'nivaro'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '#18293E',
                'selectors' => [
                    '{{WRAPPER}} .gambia-services-list li.current-page' => 'background-color: {{VALUE}};',
                ],
            ]
        );
        
        $this->add_control(
            'current_page_text_color',
            [
                'label' => __('text color', 'nivaro'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '#ffffff',
                'selectors' => [
                    '{{WRAPPER}} .gambia-services-list li.current-page .gambia-service-link' => 'color: {{VALUE}} !important;',
                ],
            ]
        );
        
        // Separator
        $this->add_control(
            'style_separator_4',
            [
                'type' => \Elementor\Controls_Manager::DIVIDER,
            ]
        );
        
        // Vertical Space Between List Items
        $this->add_control(
            'list_item_vertical_spacing_heading',
            [
                'type' => \Elementor\Controls_Manager::RAW_HTML,
                'raw' => '<div style="font-size: 16px; font-weight: bold; color: #333; margin: 15px 0 10px 0; border-bottom: 2px solid #ddd; padding-bottom: 8px;">vertical space between &lt;li&gt;&lt;/li&gt; items</div>',
            ]
        );
        
        $this->add_control(
            'list_item_vertical_spacing',
            [
                'label' => __('spacing (px)', 'nivaro'),
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
                    'size' => 26,
                ],
                'selectors' => [
                    '{{WRAPPER}} .gambia-services-list li' => 'margin-bottom: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .gambia-services-list li:last-child' => 'margin-bottom: 0;',
                ],
            ]
        );
        
        // Separator
        $this->add_control(
            'style_separator_5',
            [
                'type' => \Elementor\Controls_Manager::DIVIDER,
            ]
        );
        
        // Padding for List Items
        $this->add_control(
            'list_item_padding_heading',
            [
                'type' => \Elementor\Controls_Manager::RAW_HTML,
                'raw' => '<div style="font-size: 16px; font-weight: bold; color: #333; margin: 15px 0 10px 0; border-bottom: 2px solid #ddd; padding-bottom: 8px;">padding for each &lt;li&gt;&lt;/li&gt; item</div>',
            ]
        );
        
        $this->add_control(
            'list_item_padding_top',
            [
                'label' => __('top', 'nivaro'),
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
                    'size' => 5,
                ],
                'selectors' => [
                    '{{WRAPPER}} .gambia-services-list li' => 'padding-top: {{SIZE}}{{UNIT}};',
                ],
            ]
        );
        
        $this->add_control(
            'list_item_padding_right',
            [
                'label' => __('right', 'nivaro'),
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
                    'size' => 14,
                ],
                'selectors' => [
                    '{{WRAPPER}} .gambia-services-list li' => 'padding-right: {{SIZE}}{{UNIT}};',
                ],
            ]
        );
        
        $this->add_control(
            'list_item_padding_bottom',
            [
                'label' => __('bottom', 'nivaro'),
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
                    'size' => 5,
                ],
                'selectors' => [
                    '{{WRAPPER}} .gambia-services-list li' => 'padding-bottom: {{SIZE}}{{UNIT}};',
                ],
            ]
        );
        
        $this->add_control(
            'list_item_padding_left',
            [
                'label' => __('left', 'nivaro'),
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
                    'size' => 14,
                ],
                'selectors' => [
                    '{{WRAPPER}} .gambia-services-list li' => 'padding-left: {{SIZE}}{{UNIT}};',
                ],
            ]
        );
        
        $this->add_control(
            'list_item_border_radius',
            [
                'label' => __('border-radius', 'nivaro'),
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
                    'size' => 9,
                ],
                'selectors' => [
                    '{{WRAPPER}} .gambia-services-list li' => 'border-radius: {{SIZE}}{{UNIT}};',
                ],
            ]
        );
        
        $this->end_controls_section();
        
        // List Style Section (keeping original advanced controls)
        $this->start_controls_section(
            'list_style_section',
            [
                'label' => __('Advanced List Style', 'nivaro'),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );
        
        // Heading Style
        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'heading_typography',
                'label' => __('Heading Typography', 'nivaro'),
                'selector' => '{{WRAPPER}} .gambia-list-heading',
            ]
        );
        
        // Link Style
        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'link_typography',
                'label' => __('Link Typography', 'nivaro'),
                'selector' => '{{WRAPPER}} .gambia-service-link',
            ]
        );
        
        $this->end_controls_section();
    }
    
    /**
     * Get filtered services based on widget settings (copied from Cameldrink)
     */
    private function get_filtered_services($settings) {
        global $wpdb;
        $table_name = $wpdb->prefix . 'zen_services';
        
        // Build WHERE clause based on filters
        $where_conditions = [];
        
        // Filter by is_active
        if ($settings['filter_is_active'] === 'yes') {
            $where_conditions[] = 'is_active = 1';
        } elseif ($settings['filter_is_active'] === 'no') {
            $where_conditions[] = '(is_active = 0 OR is_active IS NULL)';
        }
        
        // Filter by is_pinned
        if ($settings['filter_is_pinned'] === 'yes') {
            $where_conditions[] = 'is_pinned = 1';
        } elseif ($settings['filter_is_pinned'] === 'no') {
            $where_conditions[] = '(is_pinned = 0 OR is_pinned IS NULL)';
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
                $order_by = 'ORDER BY position_in_custom_order ASC, service_id ASC';
                break;
            case 'pinned_alpha':
                // Pinned items first (sorted alphabetically), then unpinned (sorted alphabetically)
                $order_by = 'ORDER BY COALESCE(is_pinned, 0) DESC, service_name ASC';
                break;
            case 'service_id':
            default:
                $order_by = 'ORDER BY service_id ASC';
                break;
        }
        
        // Build and execute query
        $query = "SELECT * FROM {$table_name} {$where} {$order_by}";
        $services = $wpdb->get_results($query);
        
        // Add dynamic links to services
        foreach ($services as &$service) {
            $service->dynamic_link = $this->database->get_service_dynamic_link($service->service_id);
        }
        
        return $services;
    }
    
    protected function render() {
        $settings = $this->get_settings_for_display();
        
        // Get filtered services
        $services = $this->get_filtered_services($settings);
        
        if (empty($services)) {
            echo '<div class="gambia-no-services">No services found matching the selected filters.</div>';
            return;
        }
        
        $heading_text = $settings['heading_label_text'] ?? 'Our Services';
        
        // Get widget style settings
        $widget_width = isset($settings['widget_width_desktop']) ? $settings['widget_width_desktop'] : ['size' => 370, 'unit' => 'px'];
        $width_value = $widget_width['size'] . $widget_width['unit'];
        
        $background_color = $settings['widget_background_color'] ?? '';
        
        // Build inline styles (removed border-radius since it's handled by CSS selector)
        $widget_styles = sprintf(
            'max-width: %s;%s',
            esc_attr($width_value),
            $background_color ? ' background-color: ' . esc_attr($background_color) . ';' : ''
        );
        
        ?>
        <div class="gambia-widget" style="<?php echo $widget_styles; ?>">
            <?php if ($heading_text): ?>
                <h2 class="gambia-list-heading"><?php echo esc_html($heading_text); ?></h2>
            <?php endif; ?>
            
            <ul class="gambia-services-list">
                <?php foreach ($services as $service): ?>
                    <?php 
                    $service_name = $service->service_name ?? '';
                    $dynamic_link = $service->dynamic_link ?? '#';
                    
                    // Check if this link matches the current page
                    $current_url = home_url($_SERVER['REQUEST_URI']);
                    $is_current_page = ($dynamic_link === $current_url) || 
                                     (rtrim($dynamic_link, '/') === rtrim($current_url, '/'));
                    
                    $li_classes = $is_current_page ? 'current-page' : '';
                    ?>
                    <?php if ($service_name): ?>
                        <li class="<?php echo esc_attr($li_classes); ?>">
                            <a href="<?php echo esc_url($dynamic_link); ?>" 
                               class="gambia-service-link">
                                <?php echo esc_html($service_name); ?>
                            </a>
                        </li>
                    <?php endif; ?>
                <?php endforeach; ?>
            </ul>
        </div>
        <?php
    }
    
    protected function content_template() {
        ?>
        <#
        // Preview message for editor
        #>
        <div class="gambia-widget">
            <div style="background: #f9f9f9; border: 2px dashed #007cba; padding: 30px; text-align: center; border-radius: 8px;">
                <h3 style="color: #007cba; margin: 0 0 10px 0;">🇬🇲 Gambia Services Link List</h3>
                <p style="margin: 0 0 15px 0; color: #666;">Dynamic filtered service links will display here on the frontend</p>
                <div style="font-size: 13px; color: #888;">
                    <div>is_active: <strong>{{ settings.filter_is_active }}</strong></div>
                    <div>is_pinned: <strong>{{ settings.filter_is_pinned }}</strong></div>
                    <div>order: <strong>{{ settings.filter_display_order }}</strong></div>
                </div>
                <div style="margin-top: 15px; padding-top: 15px; border-top: 1px solid #ddd;">
                    <div style="text-align: left; font-size: 14px;">
                        <div style="font-weight: bold; margin-bottom: 10px;">{{ settings.heading_label_text || 'Our Services' }}</div>
                        <ul style="margin: 0; padding-left: 20px;">
                            <li>Service Link 1</li>
                            <li>Service Link 2</li>
                            <li>Service Link 3</li>
                            <li>...</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
        <?php
    }
}