/**
 * Nivaro Gecko Widget Editor JavaScript
 * Handles auto-generation functionality in Elementor editor
 */

(function($) {
    'use strict';
    
    // Check for AJAX configuration from either source (for backward compatibility)
    var ajax_config = typeof nivaro_widget_ajax !== 'undefined' ? nivaro_widget_ajax : 
                      typeof nivaro_coyote_ajax !== 'undefined' ? nivaro_coyote_ajax : null;
    
    if (!ajax_config) {
        console.warn('Nivaro Gecko: AJAX configuration not found');
        return;
    }
    
    var GeckoEditor = {
        
        /**
         * Initialize editor functionality
         */
        init: function() {
            // Wait for Elementor to be ready
            if (typeof elementor !== 'undefined') {
                this.setupEventListeners();
            } else {
                // Retry when Elementor loads
                $(document).on('elementor/loaded', this.setupEventListeners.bind(this));
            }
        },
        
        /**
         * Setup Elementor event listeners
         */
        setupEventListeners: function() {
            // Global auto-generate function
            window.geckoAutoGenerate = this.autoGenerate.bind(this);
            
            // Listen for panel changes
            elementor.hooks.addAction('panel/open_editor/widget', this.handlePanelOpen.bind(this));
        },
        
        /**
         * Handle panel opening for Gecko widget
         */
        handlePanelOpen: function(panel, model, view) {
            if (model.get('widgetType') === 'nivaro-gecko') {
                // Add event listener for auto-generate button when panel opens
                setTimeout(function() {
                    this.attachAutoGenerateHandler();
                }.bind(this), 100);
            }
        },
        
        /**
         * Attach click handler to auto-generate button
         */
        attachAutoGenerateHandler: function() {
            // Remove existing handlers to prevent duplicates
            $(document).off('click', '#gecko-auto-generate');
            
            // Add new handler
            $(document).on('click', '#gecko-auto-generate', function(e) {
                e.preventDefault();
                GeckoEditor.autoGenerate();
            });
        },
        
        /**
         * Auto-generate service boxes from database
         */
        autoGenerate: function() {
            var $button = $('#gecko-auto-generate');
            var originalText = $button.html();
            
            // Get current widget settings to determine quantity
            var currentElement = elementor.getCurrentElement();
            var settings = currentElement ? currentElement.getEditModel().toJSON() : {};
            var generateQuantity = settings.generate_quantity || 8;
            var boxesPerRow = settings.boxes_per_row || 4;
            
            // Show loading state
            $button.prop('disabled', true)
                   .html('<i class="eicon-loading eicon-animation-spin" style="margin-right: 10px;"></i>GENERATING...');
            
            // Make AJAX request
            $.ajax({
                url: ajax_config.ajax_url,
                type: 'POST',
                data: {
                    action: 'nivaro_gecko_auto_generate',
                    nonce: ajax_config.nonce,
                    quantity: generateQuantity,
                    boxes_per_row: boxesPerRow
                },
                success: function(response) {
                    if (response.success && response.data) {
                        var actualGenerated = Math.min(response.data.length, generateQuantity);
                        GeckoEditor.populateFields(response.data, actualGenerated);
                        
                        // Show appropriate message
                        if (actualGenerated < generateQuantity) {
                            GeckoEditor.showNotification('Generated ' + actualGenerated + ' service boxes (limited by available services in database)', 'warning');
                        } else {
                            GeckoEditor.showNotification('Generated ' + actualGenerated + ' service boxes successfully!', 'success');
                        }
                    } else {
                        GeckoEditor.showNotification('Failed to generate services: ' + (response.data || 'Unknown error'), 'error');
                    }
                },
                error: function() {
                    GeckoEditor.showNotification('AJAX error occurred during auto-generation', 'error');
                },
                complete: function() {
                    // Restore button
                    $button.prop('disabled', false)
                           .html(originalText);
                }
            });
        },
        
        /**
         * Populate widget fields with generated data
         */
        populateFields: function(services, quantity) {
            if (!elementor || !elementor.getCurrentElement) {
                console.error('Gecko: Elementor not available for field population');
                return;
            }
            
            var currentElement = elementor.getCurrentElement();
            if (!currentElement) {
                console.error('Gecko: No current element found');
                return;
            }
            
            var settings = {};
            quantity = quantity || 8;
            
            // Clear all existing service fields first
            for (var i = 1; i <= 20; i++) {
                settings['service_' + i + '_title'] = '';
                settings['service_' + i + '_description'] = '';
                settings['service_' + i + '_link'] = {
                    url: '',
                    is_external: false,
                    nofollow: false
                };
                settings['service_' + i + '_image'] = {
                    url: '',
                    id: ''
                };
            }
            
            // Populate up to the specified quantity, but not more than available services
            var actualQuantity = Math.min(services.length, quantity);
            
            for (var i = 0; i < actualQuantity; i++) {
                var service = services[i];
                var index = i + 1;
                
                settings['service_' + index + '_title'] = service.title || '';
                settings['service_' + index + '_description'] = service.description || '';
                settings['service_' + index + '_link'] = {
                    url: service.link || '',
                    is_external: false,
                    nofollow: false
                };
                
                // Handle image
                if (service.image_url) {
                    settings['service_' + index + '_image'] = {
                        url: service.image_url,
                        id: service.image_id || ''
                    };
                }
            }
            
            // Apply settings to widget with error handling
            try {
                // Apply each setting individually to be safe
                for (var settingKey in settings) {
                    if (settings.hasOwnProperty(settingKey)) {
                        currentElement.getEditModel().setSetting(settingKey, settings[settingKey]);
                    }
                }
                
                // Refresh the panel to show updated values
                setTimeout(function() {
                    if (elementor.panel && elementor.panel.currentView) {
                        elementor.panel.currentView.render();
                    }
                }, 100);
            } catch (error) {
                console.error('Gecko: Error applying settings:', error);
                GeckoEditor.showNotification('Error updating widget settings', 'error');
            }
        },
        
        /**
         * Show notification to user
         */
        showNotification: function(message, type) {
            type = type || 'info';
            
            if (elementor && elementor.notification) {
                elementor.notification.show({
                    message: message,
                    type: type
                });
            } else {
                // Fallback alert
                alert(message);
            }
        },
        
        /**
         * Debug function to log widget data
         */
        debugWidget: function() {
            if (elementor && elementor.getCurrentElement) {
                var currentElement = elementor.getCurrentElement();
                if (currentElement) {
                    console.log('Gecko Widget Debug:', {
                        type: currentElement.get('elType'),
                        widgetType: currentElement.get('widgetType'),
                        settings: currentElement.getEditModel().toJSON()
                    });
                }
            }
        }
    };
    
    // Initialize when document is ready
    $(document).ready(function() {
        GeckoEditor.init();
    });
    
    // Make available globally for debugging
    window.GeckoEditor = GeckoEditor;
    
})(jQuery);