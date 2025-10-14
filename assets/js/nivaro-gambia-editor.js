(function($) {
    'use strict';

    // Wait for Elementor to be ready
    $(window).on('elementor:init', function() {
        
        // Hook into the Gambia widget panel open event
        elementor.hooks.addAction('panel/open_editor/widget/nivaro-gambia', function(panel, model, view) {
            
            // Store the presets data
            var stylePresets = {
                'orange_skin_1': {
                    'label': 'Orange Skin 1 (default)',
                    'settings': {
                        'widget_border_radius': {'size': 24, 'unit': 'px'},
                        'widget_background_color': '#E7851D',
                        'widget_padding_top': {'size': 20, 'unit': 'px'},
                        'widget_padding_bottom': {'size': 20, 'unit': 'px'},
                        'widget_padding_left': {'size': 20, 'unit': 'px'},
                        'widget_padding_right': {'size': 20, 'unit': 'px'},
                        'heading_label_color': '#ffffff',
                        'heading_label_font_weight': '700',
                        'heading_label_font_family': 'Helvetica, Arial, sans-serif',
                        'link_text_font_size': {'size': 21, 'unit': 'px'},
                        'link_text_color': '#030303',
                        'link_text_font_weight': '500',
                        'current_page_background_color': '#18293E',
                        'current_page_text_color': '#ffffff',
                        'list_item_vertical_spacing': {'size': 26, 'unit': 'px'},
                        'list_item_padding_top': {'size': 5, 'unit': 'px'},
                        'list_item_padding_right': {'size': 14, 'unit': 'px'},
                        'list_item_padding_bottom': {'size': 5, 'unit': 'px'},
                        'list_item_padding_left': {'size': 14, 'unit': 'px'},
                        'list_item_border_radius': {'size': 9, 'unit': 'px'}
                    }
                },
                'corporate_blue': {
                    'label': 'Corporate Blue',
                    'settings': {
                        'widget_border_radius': {'size': 8, 'unit': 'px'},
                        'widget_background_color': '#1e3a5f',
                        'widget_padding_top': {'size': 25, 'unit': 'px'},
                        'widget_padding_bottom': {'size': 25, 'unit': 'px'},
                        'widget_padding_left': {'size': 25, 'unit': 'px'},
                        'widget_padding_right': {'size': 25, 'unit': 'px'},
                        'heading_label_color': '#ffffff',
                        'heading_label_font_weight': '600',
                        'heading_label_font_family': 'Georgia, serif',
                        'link_text_font_size': {'size': 18, 'unit': 'px'},
                        'link_text_color': '#e0e8f0',
                        'link_text_font_weight': '400',
                        'current_page_background_color': '#4a90e2',
                        'current_page_text_color': '#ffffff',
                        'list_item_vertical_spacing': {'size': 15, 'unit': 'px'},
                        'list_item_padding_top': {'size': 8, 'unit': 'px'},
                        'list_item_padding_right': {'size': 16, 'unit': 'px'},
                        'list_item_padding_bottom': {'size': 8, 'unit': 'px'},
                        'list_item_padding_left': {'size': 16, 'unit': 'px'},
                        'list_item_border_radius': {'size': 4, 'unit': 'px'}
                    }
                },
                'dark_professional': {
                    'label': 'Dark Professional',
                    'settings': {
                        'widget_border_radius': {'size': 12, 'unit': 'px'},
                        'widget_background_color': '#2c2c2c',
                        'widget_padding_top': {'size': 30, 'unit': 'px'},
                        'widget_padding_bottom': {'size': 30, 'unit': 'px'},
                        'widget_padding_left': {'size': 30, 'unit': 'px'},
                        'widget_padding_right': {'size': 30, 'unit': 'px'},
                        'heading_label_color': '#ffffff',
                        'heading_label_font_weight': '300',
                        'heading_label_font_family': 'Roboto, sans-serif',
                        'link_text_font_size': {'size': 17, 'unit': 'px'},
                        'link_text_color': '#b8b8b8',
                        'link_text_font_weight': '300',
                        'current_page_background_color': '#ffffff',
                        'current_page_text_color': '#2c2c2c',
                        'list_item_vertical_spacing': {'size': 12, 'unit': 'px'},
                        'list_item_padding_top': {'size': 10, 'unit': 'px'},
                        'list_item_padding_right': {'size': 20, 'unit': 'px'},
                        'list_item_padding_bottom': {'size': 10, 'unit': 'px'},
                        'list_item_padding_left': {'size': 20, 'unit': 'px'},
                        'list_item_border_radius': {'size': 6, 'unit': 'px'}
                    }
                },
                'minimal_clean': {
                    'label': 'Minimal Clean',
                    'settings': {
                        'widget_border_radius': {'size': 0, 'unit': 'px'},
                        'widget_background_color': '#ffffff',
                        'widget_padding_top': {'size': 40, 'unit': 'px'},
                        'widget_padding_bottom': {'size': 40, 'unit': 'px'},
                        'widget_padding_left': {'size': 0, 'unit': 'px'},
                        'widget_padding_right': {'size': 0, 'unit': 'px'},
                        'heading_label_color': '#000000',
                        'heading_label_font_weight': '400',
                        'heading_label_font_family': 'system-ui, -apple-system, sans-serif',
                        'link_text_font_size': {'size': 16, 'unit': 'px'},
                        'link_text_color': '#333333',
                        'link_text_font_weight': '400',
                        'current_page_background_color': 'transparent',
                        'current_page_text_color': '#000000',
                        'list_item_vertical_spacing': {'size': 20, 'unit': 'px'},
                        'list_item_padding_top': {'size': 12, 'unit': 'px'},
                        'list_item_padding_right': {'size': 0, 'unit': 'px'},
                        'list_item_padding_bottom': {'size': 12, 'unit': 'px'},
                        'list_item_padding_left': {'size': 0, 'unit': 'px'},
                        'list_item_border_radius': {'size': 0, 'unit': 'px'}
                    }
                }
            };

            // Add click handler for the apply button after panel renders
            setTimeout(function() {
                // Remove any existing handlers to prevent duplicates
                $(document).off('click.gambiaPreset');
                
                // Add new handler
                $(document).on('click.gambiaPreset', '#gambia-apply-preset', function(e) {
                    e.preventDefault();
                    
                    console.log('Gambia Apply Preset clicked');
                    
                    // Get the selected preset from the dropdown
                    var presetSelector = panel.$el.find('[data-setting="style_preset_selector"]');
                    var selectedPreset = presetSelector.val();
                    
                    console.log('Selected preset:', selectedPreset);
                    
                    if (!selectedPreset || !stylePresets[selectedPreset]) {
                        console.log('Invalid preset selected');
                        return;
                    }
                    
                    var presetSettings = stylePresets[selectedPreset].settings;
                    var $button = $('#gambia-apply-preset');
                    
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
                function updateGambiaCount() {
                    var isActive = panel.$el.find('[data-setting="filter_is_active"]').val() || 'any';
                    var isPinned = panel.$el.find('[data-setting="filter_is_pinned"]').val() || 'any';
                    
                    if (typeof nivaro_widget_ajax !== 'undefined') {
                        $.ajax({
                            url: nivaro_widget_ajax.ajax_url,
                            type: 'POST',
                            data: {
                                action: 'nivaro_gambia_get_count',
                                is_active: isActive,
                                is_pinned: isPinned,
                                nonce: nivaro_widget_ajax.nonce
                            },
                            success: function(response) {
                                if (response.success) {
                                    $('#gambia-filtered-count').text(response.data.count);
                                }
                            }
                        });
                    }
                }
                
                // Update count when filters change
                panel.$el.find('[data-setting="filter_is_active"], [data-setting="filter_is_pinned"]').on('change', function() {
                    updateGambiaCount();
                });
                
                // Handle save button
                $(document).off('click.gambiaSave').on('click.gambiaSave', '#gambia-save-filters', function(e) {
                    e.preventDefault();
                    
                    // Mark editor as changed
                    if (typeof elementor !== 'undefined') {
                        elementor.saver.setFlagEditorChange(true);
                        $(this).text('Settings Saved!');
                        setTimeout(function() {
                            $('#gambia-save-filters').text('Save Filter Settings');
                        }, 2000);
                    }
                });
                
                // Initial count update
                setTimeout(updateGambiaCount, 500);
                
            }, 100);
        });
    });

})(jQuery);