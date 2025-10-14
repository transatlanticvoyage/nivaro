(function($) {
    'use strict';

    // Wait for Elementor to be ready
    $(window).on('elementor:init', function() {
        
        // Hook into the Cameldrink widget panel open event
        elementor.hooks.addAction('panel/open_editor/widget/nivaro-cameldrink', function(panel, model, view) {
            
            // Store the presets data - matching PHP configuration
            var stylePresets = {
                'skyburn': {
                    'label': 'Skyburn Preset (default)',
                    'settings': {
                        // Content Settings
                        'image_source': 'rel_image1_id',
                        'image_frame_height': '200',
                        'image_alignment': 'center',
                        'image_display_philosophy': 'contain',
                        'display_learn_more_button': 'no',
                        'place_image_inside_subframe': 'yes',
                        'subframe_height_percentage': '80',
                        'subframe_bg_color': '#f5f5f5',
                        // Style Settings
                        'widget_border_radius': {'size': 12, 'unit': 'px'},
                        'widget_background_color': '',
                        'columns': '4',
                        'gap': {'size': 20, 'unit': 'px'},
                        'service_box_max_width_toggle': 'yes',
                        'service_box_max_width_value': {'size': 316, 'unit': 'px'},
                        'box_shadow_top_blur': {'size': 6, 'unit': 'px'},
                        'box_shadow_top_spread': {'size': 3, 'unit': 'px'},
                        'box_shadow_top_offset': {'size': -2, 'unit': 'px'},
                        'box_shadow_top_color': 'rgba(0, 0, 0, 0.1)',
                        'box_shadow_right_blur': {'size': 4, 'unit': 'px'},
                        'box_shadow_right_spread': {'size': 0, 'unit': 'px'},
                        'box_shadow_right_offset': {'size': 2, 'unit': 'px'},
                        'box_shadow_right_color': 'rgba(0, 0, 0, 0.05)',
                        'box_shadow_bottom_blur': {'size': 8, 'unit': 'px'},
                        'box_shadow_bottom_spread': {'size': 0, 'unit': 'px'},
                        'box_shadow_bottom_offset': {'size': 2, 'unit': 'px'},
                        'box_shadow_bottom_color': 'rgba(0, 0, 0, 0.1)',
                        'box_shadow_left_blur': {'size': 0, 'unit': 'px'},
                        'box_shadow_left_spread': {'size': 0, 'unit': 'px'},
                        'box_shadow_left_offset': {'size': -2, 'unit': 'px'},
                        'box_shadow_left_color': 'rgba(0, 0, 0, 0.1)',
                        'image_height': {'size': 200, 'unit': 'px'},
                        'title_color': '#3163C5',
                        'description_color': '',
                        'button_text_color': '#ffffff'
                    }
                },
                'midnight_elegance': {
                    'label': 'Midnight Elegance',
                    'settings': {
                        // Content Settings
                        'image_source': 'rel_image1_id',
                        'image_frame_height': '200',
                        'image_alignment': 'center',
                        'image_display_philosophy': 'contain',
                        'display_learn_more_button': 'no',
                        'place_image_inside_subframe': 'yes',
                        'subframe_height_percentage': '80',
                        'subframe_bg_color': '#0f0f1e',
                        // Style Settings
                        'widget_border_radius': {'size': 8, 'unit': 'px'},
                        'widget_background_color': '#1a1a2e',
                        'columns': '3',
                        'gap': {'size': 30, 'unit': 'px'},
                        'service_box_max_width_toggle': 'yes',
                        'service_box_max_width_value': {'size': 350, 'unit': 'px'},
                        'box_shadow_top_blur': {'size': 15, 'unit': 'px'},
                        'box_shadow_top_spread': {'size': 5, 'unit': 'px'},
                        'box_shadow_top_offset': {'size': -5, 'unit': 'px'},
                        'box_shadow_top_color': 'rgba(0, 0, 0, 0.4)',
                        'box_shadow_right_blur': {'size': 15, 'unit': 'px'},
                        'box_shadow_right_spread': {'size': 5, 'unit': 'px'},
                        'box_shadow_right_offset': {'size': 5, 'unit': 'px'},
                        'box_shadow_right_color': 'rgba(0, 0, 0, 0.4)',
                        'box_shadow_bottom_blur': {'size': 20, 'unit': 'px'},
                        'box_shadow_bottom_spread': {'size': 8, 'unit': 'px'},
                        'box_shadow_bottom_offset': {'size': 10, 'unit': 'px'},
                        'box_shadow_bottom_color': 'rgba(0, 0, 0, 0.5)',
                        'box_shadow_left_blur': {'size': 15, 'unit': 'px'},
                        'box_shadow_left_spread': {'size': 5, 'unit': 'px'},
                        'box_shadow_left_offset': {'size': -5, 'unit': 'px'},
                        'box_shadow_left_color': 'rgba(0, 0, 0, 0.4)',
                        'image_height': {'size': 250, 'unit': 'px'},
                        'title_color': '#f39c12',
                        'description_color': '#ecf0f1',
                        'button_text_color': '#1a1a2e'
                    }
                },
                'fresh_mint': {
                    'label': 'Fresh Mint',
                    'settings': {
                        // Content Settings
                        'image_source': 'rel_image1_id',
                        'image_frame_height': '200',
                        'image_alignment': 'center',
                        'image_display_philosophy': 'contain',
                        'display_learn_more_button': 'no',
                        'place_image_inside_subframe': 'yes',
                        'subframe_height_percentage': '80',
                        'subframe_bg_color': '#e6f7ff',
                        // Style Settings
                        'widget_border_radius': {'size': 20, 'unit': 'px'},
                        'widget_background_color': '#f0f9ff',
                        'columns': '4',
                        'gap': {'size': 15, 'unit': 'px'},
                        'service_box_max_width_toggle': 'yes',
                        'service_box_max_width_value': {'size': 280, 'unit': 'px'},
                        'box_shadow_top_blur': {'size': 0, 'unit': 'px'},
                        'box_shadow_top_spread': {'size': 0, 'unit': 'px'},
                        'box_shadow_top_offset': {'size': 0, 'unit': 'px'},
                        'box_shadow_top_color': 'rgba(0, 0, 0, 0)',
                        'box_shadow_right_blur': {'size': 10, 'unit': 'px'},
                        'box_shadow_right_spread': {'size': 0, 'unit': 'px'},
                        'box_shadow_right_offset': {'size': 3, 'unit': 'px'},
                        'box_shadow_right_color': 'rgba(16, 185, 129, 0.1)',
                        'box_shadow_bottom_blur': {'size': 15, 'unit': 'px'},
                        'box_shadow_bottom_spread': {'size': 0, 'unit': 'px'},
                        'box_shadow_bottom_offset': {'size': 5, 'unit': 'px'},
                        'box_shadow_bottom_color': 'rgba(16, 185, 129, 0.15)',
                        'box_shadow_left_blur': {'size': 0, 'unit': 'px'},
                        'box_shadow_left_spread': {'size': 0, 'unit': 'px'},
                        'box_shadow_left_offset': {'size': 0, 'unit': 'px'},
                        'box_shadow_left_color': 'rgba(0, 0, 0, 0)',
                        'image_height': {'size': 180, 'unit': 'px'},
                        'title_color': '#10b981',
                        'description_color': '#6b7280',
                        'button_text_color': '#ffffff'
                    }
                },
                'corporate_clean': {
                    'label': 'Corporate Clean',
                    'settings': {
                        // Content Settings
                        'image_source': 'rel_image1_id',
                        'image_frame_height': '200',
                        'image_alignment': 'center',
                        'image_display_philosophy': 'contain',
                        'display_learn_more_button': 'no',
                        'place_image_inside_subframe': 'yes',
                        'subframe_height_percentage': '80',
                        'subframe_bg_color': '#fafafa',
                        // Style Settings
                        'widget_border_radius': {'size': 0, 'unit': 'px'},
                        'widget_background_color': '#ffffff',
                        'columns': '3',
                        'gap': {'size': 40, 'unit': 'px'},
                        'service_box_max_width_toggle': 'no',
                        'service_box_max_width_value': {'size': 400, 'unit': 'px'},
                        'box_shadow_top_blur': {'size': 0, 'unit': 'px'},
                        'box_shadow_top_spread': {'size': 0, 'unit': 'px'},
                        'box_shadow_top_offset': {'size': -1, 'unit': 'px'},
                        'box_shadow_top_color': 'rgba(0, 0, 0, 0.1)',
                        'box_shadow_right_blur': {'size': 0, 'unit': 'px'},
                        'box_shadow_right_spread': {'size': 0, 'unit': 'px'},
                        'box_shadow_right_offset': {'size': 1, 'unit': 'px'},
                        'box_shadow_right_color': 'rgba(0, 0, 0, 0.1)',
                        'box_shadow_bottom_blur': {'size': 4, 'unit': 'px'},
                        'box_shadow_bottom_spread': {'size': 0, 'unit': 'px'},
                        'box_shadow_bottom_offset': {'size': 1, 'unit': 'px'},
                        'box_shadow_bottom_color': 'rgba(0, 0, 0, 0.12)',
                        'box_shadow_left_blur': {'size': 0, 'unit': 'px'},
                        'box_shadow_left_spread': {'size': 0, 'unit': 'px'},
                        'box_shadow_left_offset': {'size': -1, 'unit': 'px'},
                        'box_shadow_left_color': 'rgba(0, 0, 0, 0.1)',
                        'image_height': {'size': 220, 'unit': 'px'},
                        'title_color': '#1f2937',
                        'description_color': '#4b5563',
                        'button_text_color': '#ffffff'
                    }
                }
            };

            // Add click handler for the apply button after panel renders
            setTimeout(function() {
                // Remove any existing handlers to prevent duplicates
                $(document).off('click.cameldrinkPreset');
                
                // Add new handler
                $(document).on('click.cameldrinkPreset', '#cameldrink-apply-preset', function(e) {
                    e.preventDefault();
                    
                    console.log('Cameldrink Apply Preset clicked');
                    
                    // Get the selected preset from the dropdown
                    var presetSelector = panel.$el.find('[data-setting="style_preset_selector"]');
                    var selectedPreset = presetSelector.val();
                    
                    console.log('Selected preset:', selectedPreset);
                    
                    if (!selectedPreset || !stylePresets[selectedPreset]) {
                        console.log('Invalid preset selected');
                        return;
                    }
                    
                    var presetSettings = stylePresets[selectedPreset].settings;
                    var $button = $('#cameldrink-apply-preset');
                    
                    // Apply each setting using Elementor's API
                    for (var settingKey in presetSettings) {
                        var settingValue = presetSettings[settingKey];
                        
                        // Use Elementor's model to set the value
                        model.setSetting(settingKey, settingValue);
                        
                        console.log('Applied', settingKey, ':', settingValue);
                    }
                    
                    // Update button to show success
                    $button.text('Preset Applied!').css('background', '#46b450');
                    
                    // Reset button after 2 seconds
                    setTimeout(function() {
                        $button.text('Apply Selected Preset').css('background', '#007cba');
                    }, 2000);
                    
                    // Refresh the preview
                    elementor.reloadPreview();
                });
                
                // Also handle the filter count update
                function updateCameldrinkCount() {
                    var isActive = panel.$el.find('[data-setting="filter_is_active"]').val() || 'any';
                    var isPinned = panel.$el.find('[data-setting="filter_is_pinned"]').val() || 'any';
                    
                    if (typeof nivaro_widget_ajax !== 'undefined') {
                        $.ajax({
                            url: nivaro_widget_ajax.ajax_url,
                            type: 'POST',
                            data: {
                                action: 'nivaro_cameldrink_get_count',
                                is_active: isActive,
                                is_pinned: isPinned,
                                nonce: nivaro_widget_ajax.nonce
                            },
                            success: function(response) {
                                if (response.success) {
                                    $('#cameldrink-filtered-count').text(response.data.count);
                                }
                            }
                        });
                    }
                }
                
                // Update count when filters change
                panel.$el.find('[data-setting="filter_is_active"], [data-setting="filter_is_pinned"]').on('change', function() {
                    updateCameldrinkCount();
                });
                
                // Handle save button
                $(document).off('click.cameldrinkSave').on('click.cameldrinkSave', '#cameldrink-save-filters', function(e) {
                    e.preventDefault();
                    
                    // Mark editor as changed
                    if (typeof elementor !== 'undefined') {
                        elementor.saver.setFlagEditorChange(true);
                        $(this).text('Settings Saved!');
                        setTimeout(function() {
                            $('#cameldrink-save-filters').text('Save Filter Settings');
                        }, 2000);
                    }
                });
                
                // Initial count update
                setTimeout(updateCameldrinkCount, 500);
                
            }, 100);
        });
    });

})(jQuery);