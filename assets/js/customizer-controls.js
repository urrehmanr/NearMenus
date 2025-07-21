/**
 * Customizer Controls JavaScript
 * 
 * @package NearMenus
 */

(function($) {
    'use strict';

    wp.customize.bind('ready', function() {
        
        /**
         * Enhanced color controls with palette suggestions
         */
        function enhanceColorControls() {
            var colorControls = [
                'nearmenus_primary_color',
                'nearmenus_secondary_color', 
                'nearmenus_success_color',
                'nearmenus_warning_color',
                'nearmenus_error_color'
            ];

            colorControls.forEach(function(controlId) {
                var control = wp.customize.control(controlId);
                if (control) {
                    addColorPalette(control);
                }
            });
        }

        /**
         * Add color palette suggestions
         */
        function addColorPalette(control) {
            var palettes = {
                'nearmenus_primary_color': [
                    '#3b82f6', '#1d4ed8', '#2563eb', '#1e40af', '#1e3a8a'
                ],
                'nearmenus_secondary_color': [
                    '#f97316', '#ea580c', '#dc2626', '#b91c1c', '#991b1b'
                ],
                'nearmenus_success_color': [
                    '#10b981', '#059669', '#047857', '#065f46', '#064e3b'
                ],
                'nearmenus_warning_color': [
                    '#f59e0b', '#d97706', '#b45309', '#92400e', '#78350f'
                ],
                'nearmenus_error_color': [
                    '#ef4444', '#dc2626', '#b91c1c', '#991b1b', '#7f1d1d'
                ]
            };

            var palette = palettes[control.id];
            if (!palette) return;

            var paletteHtml = '<div class="customize-control-color-palette" style="margin-top: 10px;">';
            paletteHtml += '<span style="font-size: 12px; color: #666; display: block; margin-bottom: 5px;">Quick colors:</span>';
            paletteHtml += '<div style="display: flex; gap: 5px; flex-wrap: wrap;">';
            
            palette.forEach(function(color) {
                paletteHtml += '<button type="button" class="palette-color" ';
                paletteHtml += 'style="width: 30px; height: 30px; border: 2px solid #ddd; border-radius: 3px; ';
                paletteHtml += 'background-color: ' + color + '; cursor: pointer; padding: 0;" ';
                paletteHtml += 'data-color="' + color + '" title="' + color + '"></button>';
            });
            
            paletteHtml += '</div></div>';

            control.container.find('.customize-control-content').append(paletteHtml);

            // Add click handlers
            control.container.find('.palette-color').on('click', function() {
                var color = $(this).data('color');
                control.setting.set(color);
            });
        }

        /**
         * Add conditional logic for sections
         */
        function addConditionalLogic() {
            // Show/hide hero CTA URL based on button text
            wp.customize('nearmenus_hero_cta_text', function(setting) {
                setting.bind(function(value) {
                    var ctaUrlControl = wp.customize.control('nearmenus_hero_cta_url');
                    if (ctaUrlControl) {
                        if (value) {
                            ctaUrlControl.container.show();
                        } else {
                            ctaUrlControl.container.hide();
                        }
                    }
                });
            });

            // Show/hide social media controls based on whether URLs are set
            var socialPlatforms = ['facebook', 'twitter', 'instagram', 'youtube', 'linkedin', 'tiktok'];
            socialPlatforms.forEach(function(platform) {
                wp.customize('nearmenus_social_' + platform, function(setting) {
                    setting.bind(function(value) {
                        var control = wp.customize.control('nearmenus_social_' + platform);
                        if (control) {
                            // Add visual indicator if URL is set
                            var container = control.container;
                            var label = container.find('label');
                            
                            if (value) {
                                if (!label.find('.social-indicator').length) {
                                    label.append(' <span class="social-indicator" style="color: green;">✓</span>');
                                }
                            } else {
                                label.find('.social-indicator').remove();
                            }
                        }
                    });
                });
            });
        }

        /**
         * Add section descriptions and help text
         */
        function addSectionDescriptions() {
            var sectionDescriptions = {
                'nearmenus_colors': 'These colors will be used throughout your theme. Changes are previewed in real-time.',
                'nearmenus_hero': 'Customize your homepage hero section to make a great first impression.',
                'nearmenus_contact': 'Contact information appears in your footer and can be used in schema markup.',
                'nearmenus_social': 'Add your social media profiles to increase engagement.',
                'nearmenus_search': 'Configure how restaurant listings are displayed and searched.',
                'nearmenus_display': 'Control the appearance and layout of your restaurant listings.'
            };

            Object.keys(sectionDescriptions).forEach(function(sectionId) {
                var section = wp.customize.section(sectionId);
                if (section) {
                    var description = sectionDescriptions[sectionId];
                    section.container.find('.accordion-section-content').prepend(
                        '<div class="customize-section-description" style="padding: 15px; background: #f7f7f7; border: 1px solid #ddd; margin-bottom: 15px; border-radius: 3px; font-size: 14px; color: #666;">' + 
                        description + 
                        '</div>'
                    );
                }
            });
        }

        /**
         * Add quick actions
         */
        function addQuickActions() {
            // Add reset buttons for color sections
            var colorSection = wp.customize.section('nearmenus_colors');
            if (colorSection) {
                var resetButton = '<button type="button" id="reset-colors" class="button" style="margin: 10px 15px;">';
                resetButton += 'Reset to Default Colors</button>';
                
                colorSection.container.find('.accordion-section-content').append(resetButton);
                
                $('#reset-colors').on('click', function() {
                    if (confirm('Are you sure you want to reset all colors to their defaults?')) {
                        wp.customize('nearmenus_primary_color').set('#3b82f6');
                        wp.customize('nearmenus_secondary_color').set('#f97316');
                        wp.customize('nearmenus_success_color').set('#10b981');
                        wp.customize('nearmenus_warning_color').set('#f59e0b');
                        wp.customize('nearmenus_error_color').set('#ef4444');
                    }
                });
            }

            // Add social media profile validation
            var socialSection = wp.customize.section('nearmenus_social');
            if (socialSection) {
                var validationInfo = '<div style="padding: 10px 15px; background: #fff3cd; border: 1px solid #ffeaa7; margin: 10px 15px; border-radius: 3px; font-size: 12px;">';
                validationInfo += '<strong>Tip:</strong> Enter full URLs (including https://) for your social media profiles.';
                validationInfo += '</div>';
                
                socialSection.container.find('.accordion-section-content').append(validationInfo);
            }
        }

        /**
         * Add live validation for URLs and other inputs
         */
        function addInputValidation() {
            // Validate social media URLs
            var socialPlatforms = ['facebook', 'twitter', 'instagram', 'youtube', 'linkedin', 'tiktok'];
            
            socialPlatforms.forEach(function(platform) {
                var control = wp.customize.control('nearmenus_social_' + platform);
                if (control) {
                    control.container.find('input').on('blur', function() {
                        var value = $(this).val();
                        var isValid = true;
                        
                        if (value && !value.match(/^https?:\/\//)) {
                            isValid = false;
                        }
                        
                        // Add visual feedback
                        $(this).removeClass('invalid-input valid-input');
                        if (value) {
                            $(this).addClass(isValid ? 'valid-input' : 'invalid-input');
                        }
                    });
                }
            });

            // Validate email
            var emailControl = wp.customize.control('nearmenus_email');
            if (emailControl) {
                emailControl.container.find('input').on('blur', function() {
                    var value = $(this).val();
                    var isValid = !value || value.match(/^[^\s@]+@[^\s@]+\.[^\s@]+$/);
                    
                    $(this).removeClass('invalid-input valid-input');
                    if (value) {
                        $(this).addClass(isValid ? 'valid-input' : 'invalid-input');
                    }
                });
            }

            // Validate phone
            var phoneControl = wp.customize.control('nearmenus_phone');
            if (phoneControl) {
                phoneControl.container.find('input').on('blur', function() {
                    var value = $(this).val();
                    var isValid = !value || value.match(/^[\+]?[1-9][\d]{0,15}$/);
                    
                    $(this).removeClass('invalid-input valid-input');
                    if (value) {
                        $(this).addClass(isValid ? 'valid-input' : 'invalid-input');
                    }
                });
            }
        }

        /**
         * Add custom CSS for enhanced controls
         */
        function addCustomCSS() {
            var css = `
                <style>
                .valid-input {
                    border-color: #10b981 !important;
                    box-shadow: 0 0 0 1px #10b981 !important;
                }
                .invalid-input {
                    border-color: #ef4444 !important;
                    box-shadow: 0 0 0 1px #ef4444 !important;
                }
                .palette-color:hover {
                    transform: scale(1.1);
                    border-color: #666 !important;
                }
                .palette-color:focus {
                    outline: 2px solid #0073aa;
                    outline-offset: 2px;
                }
                .customize-section-description p {
                    margin: 0;
                }
                </style>
            `;
            $('head').append(css);
        }

        /**
         * Add keyboard shortcuts
         */
        function addKeyboardShortcuts() {
            $(document).on('keydown', function(e) {
                // Ctrl/Cmd + S to save
                if ((e.ctrlKey || e.metaKey) && e.which === 83) {
                    e.preventDefault();
                    wp.customize.previewer.save();
                }
                
                // Escape to close any open sections
                if (e.which === 27) {
                    wp.customize.section.each(function(section) {
                        if (section.expanded()) {
                            section.collapse();
                        }
                    });
                }
            });
        }

        /**
         * Add progress indicator
         */
        function addProgressIndicator() {
            var totalSections = Object.keys(wp.customize.section._value).length;
            var completedSections = 0;
            
            function updateProgress() {
                completedSections = 0;
                
                wp.customize.section.each(function(section) {
                    var hasSettings = false;
                    section.controls().forEach(function(control) {
                        if (control.setting && control.setting.get() !== control.setting._value) {
                            hasSettings = true;
                        }
                    });
                    
                    if (hasSettings) {
                        completedSections++;
                    }
                });
                
                var progress = Math.round((completedSections / totalSections) * 100);
                
                // Update or create progress bar
                var progressBar = $('#nearmenus-progress');
                if (!progressBar.length) {
                    $('#customize-header-actions').prepend(
                        '<div id="nearmenus-progress" style="background: #ddd; height: 3px; margin-bottom: 10px; border-radius: 3px;">' +
                        '<div class="progress-fill" style="background: #0073aa; height: 100%; width: 0%; transition: width 0.3s; border-radius: 3px;"></div>' +
                        '</div>'
                    );
                    progressBar = $('#nearmenus-progress');
                }
                
                progressBar.find('.progress-fill').css('width', progress + '%');
            }
            
            // Update progress when settings change
            wp.customize.bind('change', updateProgress);
            updateProgress();
        }

        // Initialize all enhancements
        enhanceColorControls();
        addConditionalLogic();
        addSectionDescriptions();
        addQuickActions();
        addInputValidation();
        addCustomCSS();
        addKeyboardShortcuts();
        addProgressIndicator();

        // Add welcome message for first-time users
        if (!localStorage.getItem('nearmenus_customizer_welcome')) {
            setTimeout(function() {
                wp.customize.notifications.add('nearmenus_welcome', new wp.customize.Notification('nearmenus_welcome', {
                    message: 'Welcome to NearMenus theme customization! Use the sections below to customize your restaurant directory.',
                    type: 'info',
                    dismissible: true
                }));
                
                localStorage.setItem('nearmenus_customizer_welcome', 'shown');
            }, 1000);
        }
    });

})(jQuery);