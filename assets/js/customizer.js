/**
 * Customizer Live Preview JavaScript
 * 
 * @package NearMenus
 */

(function($) {
    'use strict';

    // Site title and description
    wp.customize('blogname', function(value) {
        value.bind(function(to) {
            $('.site-title').text(to);
        });
    });

    wp.customize('blogdescription', function(value) {
        value.bind(function(to) {
            $('.site-description').text(to);
        });
    });

    // Hero section customizations
    wp.customize('nearmenus_hero_title', function(value) {
        value.bind(function(to) {
            $('.hero-title').text(to);
        });
    });

    wp.customize('nearmenus_hero_subtitle', function(value) {
        value.bind(function(to) {
            $('.hero-subtitle').text(to);
        });
    });

    wp.customize('nearmenus_hero_cta_text', function(value) {
        value.bind(function(to) {
            $('.hero-cta-btn').text(to);
        });
    });

    // Color customizations
    wp.customize('nearmenus_primary_color', function(value) {
        value.bind(function(to) {
            updateCSSVariable('--color-primary', to);
            updateElementColors('.btn-primary, .bg-primary', 'background-color', to);
            updateElementColors('.text-primary, .text-primary a', 'color', to);
            updateElementColors('.border-primary', 'border-color', to);
        });
    });

    wp.customize('nearmenus_secondary_color', function(value) {
        value.bind(function(to) {
            updateCSSVariable('--color-secondary', to);
            updateElementColors('.btn-secondary, .bg-secondary', 'background-color', to);
            updateElementColors('.text-secondary', 'color', to);
            updateElementColors('.star-rating .star.filled', 'color', to);
        });
    });

    wp.customize('nearmenus_success_color', function(value) {
        value.bind(function(to) {
            updateCSSVariable('--color-success', to);
            updateElementColors('.bg-success', 'background-color', to);
            updateElementColors('.text-success', 'color', to);
        });
    });

    wp.customize('nearmenus_warning_color', function(value) {
        value.bind(function(to) {
            updateCSSVariable('--color-warning', to);
            updateElementColors('.bg-warning', 'background-color', to);
            updateElementColors('.text-warning', 'color', to);
        });
    });

    wp.customize('nearmenus_error_color', function(value) {
        value.bind(function(to) {
            updateCSSVariable('--color-error', to);
            updateElementColors('.bg-error', 'background-color', to);
            updateElementColors('.text-error', 'color', to);
        });
    });

    // Logo height
    wp.customize('nearmenus_logo_height', function(value) {
        value.bind(function(to) {
            $('.site-logo img').css('height', to + 'px');
        });
    });

    // Hero background image
    wp.customize('nearmenus_hero_bg', function(value) {
        value.bind(function(to) {
            if (to) {
                // Get the attachment URL from the media ID
                wp.media.attachment(to).fetch().then(function() {
                    var imageUrl = wp.media.attachment(to).get('url');
                    $('.hero-section').css('background-image', 'url(' + imageUrl + ')');
                });
            } else {
                $('.hero-section').css('background-image', 'none');
            }
        });
    });

    // Contact information
    wp.customize('nearmenus_phone', function(value) {
        value.bind(function(to) {
            $('.contact-phone').text(to);
            $('.contact-phone').closest('a').attr('href', 'tel:' + to);
        });
    });

    wp.customize('nearmenus_email', function(value) {
        value.bind(function(to) {
            $('.contact-email').text(to);
            $('.contact-email').closest('a').attr('href', 'mailto:' + to);
        });
    });

    wp.customize('nearmenus_address', function(value) {
        value.bind(function(to) {
            $('.contact-address').text(to);
        });
    });

    // Social media links
    var socialPlatforms = ['facebook', 'twitter', 'instagram', 'youtube', 'linkedin', 'tiktok'];
    
    socialPlatforms.forEach(function(platform) {
        wp.customize('nearmenus_social_' + platform, function(value) {
            value.bind(function(to) {
                var socialLink = $('.social-link-' + platform);
                if (to) {
                    socialLink.attr('href', to).show();
                } else {
                    socialLink.hide();
                }
            });
        });
    });

    // Search settings
    wp.customize('nearmenus_default_sort', function(value) {
        value.bind(function(to) {
            $('#sort-filter').val(to);
        });
    });

    wp.customize('nearmenus_results_per_page', function(value) {
        value.bind(function(to) {
            // Update any display of results per page
            $('.results-per-page').text(to);
        });
    });

    // Display options
    wp.customize('nearmenus_card_style', function(value) {
        value.bind(function(to) {
            var restaurantCards = $('.restaurant-card');
            restaurantCards.removeClass('card-modern card-classic card-minimal');
            restaurantCards.addClass('card-' + to);
        });
    });

    wp.customize('nearmenus_show_features', function(value) {
        value.bind(function(to) {
            if (to) {
                $('.restaurant-features').show();
            } else {
                $('.restaurant-features').hide();
            }
        });
    });

    wp.customize('nearmenus_show_ratings', function(value) {
        value.bind(function(to) {
            if (to) {
                $('.rating-display').show();
            } else {
                $('.rating-display').hide();
            }
        });
    });

    wp.customize('nearmenus_pagination_style', function(value) {
        value.bind(function(to) {
            var pagination = $('.pagination-wrapper');
            pagination.removeClass('pagination-numbers pagination-load-more pagination-infinite');
            pagination.addClass('pagination-' + to);
        });
    });

    /**
     * Helper function to update CSS variables
     */
    function updateCSSVariable(variable, value) {
        document.documentElement.style.setProperty(variable, value);
    }

    /**
     * Helper function to update element colors
     */
    function updateElementColors(selector, property, value) {
        var elements = document.querySelectorAll(selector);
        elements.forEach(function(element) {
            element.style[property] = value;
        });
    }

    /**
     * Helper function to create dynamic CSS rules
     */
    function addDynamicCSS(css) {
        var style = document.getElementById('nearmenus-dynamic-css');
        if (!style) {
            style = document.createElement('style');
            style.id = 'nearmenus-dynamic-css';
            document.head.appendChild(style);
        }
        style.textContent = css;
    }

    /**
     * Update hero section styles
     */
    function updateHeroStyles() {
        var heroTitle = wp.customize('nearmenus_hero_title')();
        var heroSubtitle = wp.customize('nearmenus_hero_subtitle')();
        var heroBg = wp.customize('nearmenus_hero_bg')();
        var primaryColor = wp.customize('nearmenus_primary_color')();

        var css = '';
        
        if (heroBg) {
            css += '.hero-section { background-image: url(' + heroBg + '); }';
        }
        
        if (primaryColor) {
            css += '.hero-cta-btn { background-color: ' + primaryColor + '; }';
            css += '.hero-cta-btn:hover { background-color: ' + adjustColorBrightness(primaryColor, -20) + '; }';
        }

        addDynamicCSS(css);
    }

    /**
     * Adjust color brightness
     */
    function adjustColorBrightness(hex, percent) {
        // Remove the hash symbol if present
        hex = hex.replace(/^#/, '');
        
        // Parse the hex values
        var num = parseInt(hex, 16);
        var amt = Math.round(2.55 * percent);
        var R = (num >> 16) + amt;
        var B = (num >> 8 & 0x00FF) + amt;
        var G = (num & 0x0000FF) + amt;
        
        // Ensure values stay within 0-255 range
        R = R > 255 ? 255 : R < 0 ? 0 : R;
        B = B > 255 ? 255 : B < 0 ? 0 : B;
        G = G > 255 ? 255 : G < 0 ? 0 : G;
        
        return "#" + (G | (B << 8) | (R << 16)).toString(16);
    }

    /**
     * Initialize preview enhancements
     */
    function initPreviewEnhancements() {
        // Add visual indicators for customizable elements
        if (typeof wp !== 'undefined' && wp.customize && wp.customize.preview) {
            // Add hover effects to show customizable areas
            var customizableElements = [
                '.site-title',
                '.site-description', 
                '.hero-title',
                '.hero-subtitle',
                '.hero-cta-btn',
                '.contact-phone',
                '.contact-email',
                '.contact-address'
            ];

            customizableElements.forEach(function(selector) {
                var elements = document.querySelectorAll(selector);
                elements.forEach(function(element) {
                    element.style.cursor = 'pointer';
                    element.title = 'Click to customize this element';
                    
                    element.addEventListener('mouseenter', function() {
                        this.style.outline = '2px dashed #0073aa';
                        this.style.outlineOffset = '2px';
                    });
                    
                    element.addEventListener('mouseleave', function() {
                        this.style.outline = 'none';
                    });
                });
            });
        }
    }

    // Initialize when document is ready
    $(document).ready(function() {
        initPreviewEnhancements();
        updateHeroStyles();
    });

    // Re-initialize after partial refreshes
    wp.customize.selectiveRefresh.bind('partial-content-rendered', function() {
        initPreviewEnhancements();
    });

})(jQuery);