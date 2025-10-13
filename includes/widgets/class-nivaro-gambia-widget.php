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
        return __('🇬🇲 Gambia Services Link List', 'nivaro');
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
    
    protected function register_controls() {
        
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
        
        $this->end_controls_section();
        
        // List Style Section
        $this->start_controls_section(
            'list_style_section',
            [
                'label' => __('List Style', 'nivaro'),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );
        
        // Heading Style
        $this->add_control(
            'heading_text',
            [
                'label' => __('List Heading Text', 'nivaro'),
                'type' => \Elementor\Controls_Manager::TEXT,
                'default' => __('Our Services', 'nivaro'),
                'label_block' => true,
            ]
        );
        
        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'heading_typography',
                'label' => __('Heading Typography', 'nivaro'),
                'selector' => '{{WRAPPER}} .gambia-list-heading',
            ]
        );
        
        $this->add_control(
            'heading_color',
            [
                'label' => __('Heading Color', 'nivaro'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '#333',
                'selectors' => [
                    '{{WRAPPER}} .gambia-list-heading' => 'color: {{VALUE}};',
                ],
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
        
        $this->start_controls_tabs('link_tabs');
        
        $this->start_controls_tab(
            'link_normal',
            [
                'label' => __('Normal', 'nivaro'),
            ]
        );
        
        $this->add_control(
            'link_color',
            [
                'label' => __('Link Color', 'nivaro'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '#007cba',
                'selectors' => [
                    '{{WRAPPER}} .gambia-service-link' => 'color: {{VALUE}};',
                ],
            ]
        );
        
        $this->end_controls_tab();
        
        $this->start_controls_tab(
            'link_hover',
            [
                'label' => __('Hover', 'nivaro'),
            ]
        );
        
        $this->add_control(
            'link_hover_color',
            [
                'label' => __('Hover Color', 'nivaro'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '#005a87',
                'selectors' => [
                    '{{WRAPPER}} .gambia-service-link:hover' => 'color: {{VALUE}};',
                ],
            ]
        );
        
        $this->end_controls_tab();
        
        $this->end_controls_tabs();
        
        // List Item Spacing
        $this->add_responsive_control(
            'list_item_spacing',
            [
                'label' => __('List Item Spacing', 'nivaro'),
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
                    'size' => 8,
                ],
                'selectors' => [
                    '{{WRAPPER}} .gambia-services-list li' => 'margin-bottom: {{SIZE}}{{UNIT}};',
                ],
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
        
        $heading_text = $settings['heading_text'] ?? 'Our Services';
        
        ?>
        <div class="gambia-widget">
            <?php if ($heading_text): ?>
                <h2 class="gambia-list-heading"><?php echo esc_html($heading_text); ?></h2>
            <?php endif; ?>
            
            <ul class="gambia-services-list">
                <?php foreach ($services as $service): ?>
                    <?php 
                    $service_name = $service->service_name ?? '';
                    $dynamic_link = $service->dynamic_link ?? '#';
                    ?>
                    <?php if ($service_name): ?>
                        <li>
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
                        <div style="font-weight: bold; margin-bottom: 10px;">{{ settings.heading_text || 'Our Services' }}</div>
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