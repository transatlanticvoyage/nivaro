/**
 * Nivaro Coyote Box Editor JavaScript
 * Handles background image visibility in Elementor editor
 */

(function($) {
    'use strict';
    
    // Ensure nivaro_coyote_ajax is available
    if (typeof nivaro_coyote_ajax === 'undefined') {
        console.warn('Nivaro Coyote: AJAX configuration not found');
        return;
    }
    
    var CoyoteEditor = {
        
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
            // Listen for container selection/editing
            elementor.hooks.addAction('panel/open_editor/widget', this.handlePanelOpen.bind(this));
            
            // Listen for control changes
            elementor.hooks.addAction('panel/editor/change', this.handleControlChange.bind(this));
            
            // Listen for preview updates
            elementor.hooks.addAction('preview:loaded', this.refreshAllCoyoteBackgrounds.bind(this));
        },
        
        /**
         * Handle panel opening for containers
         */
        handlePanelOpen: function(panel, model, view) {
            if (model.get('elType') === 'container') {
                setTimeout(function() {
                    this.checkAndUpdateBackground(model);
                }.bind(this), 100);
            }
        },
        
        /**
         * Handle control changes
         */
        handleControlChange: function(controlView, elementView) {
            var model = elementView.model;
            
            if (model.get('elType') === 'container') {
                // Debounce the update to avoid excessive calls
                clearTimeout(this.updateTimeout);
                this.updateTimeout = setTimeout(function() {
                    this.checkAndUpdateBackground(model);
                }.bind(this), 200);
            }
        },
        
        /**
         * Check if container should have Coyote background and update
         */
        checkAndUpdateBackground: function(model) {
            var settings = model.get('settings');
            
            if (settings.get('coyote_box_enable') === 'yes' && 
                settings.get('coyote_box_producement_mode') === 'option_2') {
                
                var serviceId = settings.get('coyote_box_option_2_service_id');
                var bgSize = settings.get('coyote_box_background_size');
                var bgPosition = settings.get('coyote_box_background_position');
                var bgRepeat = settings.get('coyote_box_background_repeat');
                var containerId = model.get('id');
                
                if (serviceId) {
                    this.updateBackground(containerId, serviceId, bgSize, bgPosition, bgRepeat);
                } else {
                    this.clearBackground(containerId);
                }
            }
        },
        
        /**
         * Update container background with service image
         */
        updateBackground: function(containerId, serviceId, bgSize, bgPosition, bgRepeat) {
            // Set defaults
            bgSize = bgSize || 'cover';
            bgPosition = bgPosition || 'center center';
            bgRepeat = bgRepeat || 'no-repeat';
            
            $.ajax({
                url: nivaro_coyote_ajax.ajax_url,
                type: 'POST',
                data: {
                    action: nivaro_coyote_ajax.action,
                    service_id: serviceId,
                    bg_size: bgSize,
                    bg_position: bgPosition,
                    bg_repeat: bgRepeat,
                    nonce: nivaro_coyote_ajax.nonce
                },
                success: function(response) {
                    if (response.success && response.data.image_url) {
                        this.applyBackgroundToContainer(containerId, response.data.image_url, response.data.bg_size, response.data.bg_position, response.data.bg_repeat);
                    }
                }.bind(this),
                error: function(xhr, status, error) {
                    console.warn('Nivaro Coyote: Failed to load background image', error);
                }
            });
        },
        
        /**
         * Apply background image to container in editor
         */
        applyBackgroundToContainer: function(containerId, imageUrl, bgSize, bgPosition, bgRepeat) {
            // Set defaults
            bgSize = bgSize || 'cover';
            bgPosition = bgPosition || 'center center';
            bgRepeat = bgRepeat || 'no-repeat';
            
            // Target container in editor preview iframe
            var $previewFrame = $('#elementor-preview-iframe');
            var $container;
            
            if ($previewFrame.length) {
                var previewDoc = $previewFrame.contents();
                $container = previewDoc.find('[data-id="' + containerId + '"]');
            } else {
                $container = $('[data-id="' + containerId + '"]');
            }
            
            if ($container.length) {
                // Apply background with high specificity
                var backgroundStyle = 'background-image: url(' + imageUrl + ') !important; ' +
                                    'background-position: ' + bgPosition + ' !important; ' +
                                    'background-size: ' + bgSize + ' !important; ' +
                                    'background-repeat: ' + bgRepeat + ' !important;';
                
                // Set via CSS
                $container.css({
                    'background-image': 'url(' + imageUrl + ')',
                    'background-position': bgPosition,
                    'background-size': bgSize,
                    'background-repeat': bgRepeat
                });
                
                // Also set as inline style for maximum priority
                var existingStyle = $container.attr('style') || '';
                if (existingStyle.indexOf('background-image') === -1) {
                    $container.attr('style', existingStyle + backgroundStyle);
                }
                
                // Add class for identification
                $container.addClass('coyote-dynamic-bg');
            }
        },
        
        /**
         * Clear background from container
         */
        clearBackground: function(containerId) {
            var $previewFrame = $('#elementor-preview-iframe');
            var $container;
            
            if ($previewFrame.length) {
                var previewDoc = $previewFrame.contents();
                $container = previewDoc.find('[data-id="' + containerId + '"]');
            } else {
                $container = $('[data-id="' + containerId + '"]');
            }
            
            if ($container.length) {
                $container.css({
                    'background-image': '',
                    'background-position': '',
                    'background-size': '',
                    'background-repeat': ''
                });
                
                $container.removeClass('coyote-dynamic-bg');
            }
        },
        
        /**
         * Refresh all Coyote backgrounds in preview
         */
        refreshAllCoyoteBackgrounds: function() {
            if (typeof elementor !== 'undefined' && elementor.elements) {
                elementor.elements.each(function(model) {
                    if (model.get('elType') === 'container') {
                        this.checkAndUpdateBackground(model);
                    }
                }.bind(this));
            }
        }
    };
    
    // Initialize when DOM is ready
    $(document).ready(function() {
        CoyoteEditor.init();
    });
    
    // Also try to initialize after a delay in case Elementor loads later
    setTimeout(function() {
        CoyoteEditor.init();
    }, 1000);
    
})(jQuery);