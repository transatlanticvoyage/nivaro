/**
 * Nivaro Leatherback Widget Editor JavaScript
 * Handles auto-generation functionality in Elementor editor
 */

(function($) {
    'use strict';
    
    // Check for AJAX configuration from either source (for backward compatibility)
    var ajax_config = typeof nivaro_widget_ajax !== 'undefined' ? nivaro_widget_ajax : 
                      typeof nivaro_coyote_ajax !== 'undefined' ? nivaro_coyote_ajax : null;
    
    if (!ajax_config) {
        console.warn('Nivaro Leatherback: AJAX configuration not found');
        return;
    }
    
    var LeatherbackEditor = {
        
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
            window.leatherbackAutoGenerate = this.autoGenerate.bind(this);
            
            // Listen for panel changes
            elementor.hooks.addAction('panel/open_editor/widget', this.handlePanelOpen.bind(this));
        },
        
        /**
         * Handle panel opening for Leatherback widget
         */
        handlePanelOpen: function(panel, model, view) {
            if (model.get('widgetType') === 'nivaro-leatherback') {
                // Add event listener for auto-generate button when panel opens
                setTimeout(function() {
                    this.attachAutoGenerateHandler();
                }.bind(this), 100);
            }
        },
        
        /**
         * Attach event handler to auto-generate button
         */
        attachAutoGenerateHandler: function() {
            $(document).off('click', '#leatherback-auto-generate');
            $(document).on('click', '#leatherback-auto-generate', function(e) {
                e.preventDefault();
                LeatherbackEditor.autoGenerate();
            });
        },
        
        /**
         * Auto-generate service boxes from database
         */
        autoGenerate: function() {
            var button = $('#leatherback-auto-generate');
            var originalText = button.html();
            
            // Show loading state
            button.html('<i class="eicon-loading eicon-animation-spin" style="margin-right: 5px;"></i>Generating...');
            button.prop('disabled', true);
            
            $.ajax({
                url: ajax_config.ajax_url,
                type: 'POST',
                data: {
                    action: 'nivaro_leatherback_auto_generate',
                    nonce: ajax_config.nonce
                },
                success: function(response) {
                    if (response.success && response.data.settings) {
                        // Apply settings to current widget
                        LeatherbackEditor.applyGeneratedSettings(response.data.settings);
                        
                        // Show success message
                        LeatherbackEditor.showNotification('success', response.data.message);
                        
                        // Update button text to reflect new count
                        var newText = originalText.replace(/\d+/, response.data.services_count);
                        button.html(newText);
                    } else {
                        LeatherbackEditor.showNotification('error', 'Failed to generate service boxes');
                        button.html(originalText);
                    }
                },
                error: function(xhr, status, error) {
                    console.error('Leatherback auto-generation error:', error);
                    LeatherbackEditor.showNotification('error', 'An error occurred during auto-generation');
                    button.html(originalText);
                },
                complete: function() {
                    button.prop('disabled', false);
                }
            });
        },
        
        /**
         * Apply generated settings to current widget
         */
        applyGeneratedSettings: function(settings) {
            if (!elementor || !elementor.getPanelView()) {
                console.error('Elementor panel not available');
                return;
            }
            
            var currentWidget = elementor.getPanelView().getCurrentPageView();
            if (!currentWidget || !currentWidget.model) {
                console.error('No widget model found');
                return;
            }
            
            var model = currentWidget.model;
            
            // Apply each setting
            for (var key in settings) {
                if (settings.hasOwnProperty(key)) {
                    try {
                        model.setSetting(key, settings[key]);
                    } catch (e) {
                        console.warn('Failed to set setting:', key, e);
                    }
                }
            }
            
            // Trigger refresh
            setTimeout(function() {
                if (currentWidget.render) {
                    currentWidget.render();
                }
                elementor.reloadPreview();
            }, 100);
        },
        
        /**
         * Show notification to user
         */
        showNotification: function(type, message) {
            if (typeof elementor !== 'undefined' && elementor.notifications) {
                elementor.notifications.showToast({
                    message: message,
                    buttons: [
                        {
                            name: 'close',
                            text: 'Close'
                        }
                    ]
                });
            } else {
                // Fallback to alert
                alert(message);
            }
        }
    };
    
    // Initialize when DOM is ready
    $(document).ready(function() {
        LeatherbackEditor.init();
    });
    
    // Also try to initialize after a delay in case Elementor loads later
    setTimeout(function() {
        LeatherbackEditor.init();
    }, 1000);
    
})(jQuery);