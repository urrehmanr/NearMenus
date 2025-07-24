# Step 7: CSS Architecture & Global Styles

## Objective
Create a maintainable, performant CSS architecture for the **GPress** theme that works seamlessly with theme.json, provides consistent styling across all templates, and optimizes for Core Web Vitals performance metrics. Implement conditional loading to ensure CSS modules are only loaded when needed.

## What You'll Learn
- Modern CSS architecture principles
- Integration with theme.json variables
- Performance-optimized CSS structure with conditional loading
- Responsive design patterns
- CSS custom properties usage
- Critical CSS strategies
- Component-based CSS organization

## Files to Create in This Step

```
assets/css/
├── components.css          # Component-specific styles
├── components.min.css      # Minified components
├── responsive.css          # Advanced responsive styles
├── responsive.min.css      # Minified responsive
├── editor-style.css        # Block editor styles
├── editor-style.min.css    # Minified editor styles
├── critical.css           # Critical above-the-fold CSS
└── print.css              # Print-specific styles

inc/
└── css-architecture.php   # CSS loading and optimization functions

build/
├── postcss.config.js      # PostCSS configuration
└── package.json           # Build dependencies
```

## 1. Create CSS Architecture Functions

### File: `inc/css-architecture.php`
```php
<?php
/**
 * CSS Architecture Functions for GPress Theme
 *
 * @package GPress
 * @version 1.0.0
 */

// Prevent direct access
defined('ABSPATH') || exit;

/**
 * Conditional CSS Module Loading
 * Load specific CSS modules based on page context
 */
function gpress_conditional_css_modules() {
    // Component styles - load on pages with enhanced components
    $load_components = false;
    
    // Check for pages that need component styles
    if (is_single() || is_page() || is_category() || is_tag() || is_author()) {
        $load_components = true;
    }
    
    // Check for specific blocks/components
    if (has_block('core/post-template') || has_block('core/query') || 
        has_block('core/buttons') || has_block('core/search')) {
        $load_components = true;
    }
    
    if ($load_components) {
        wp_enqueue_style(
            'gpress-components',
            GPRESS_THEME_URI . '/assets/css/components.min.css',
            array('gpress-style'),
            GPRESS_VERSION
        );
    }
    
    // Advanced responsive styles for content-heavy pages
    if (is_single() || is_page() || is_category() || is_author()) {
        wp_enqueue_style(
            'gpress-responsive',
            GPRESS_THEME_URI . '/assets/css/responsive.min.css',
            array('gpress-style'),
            GPRESS_VERSION
        );
    }
    
    // Print styles only on content pages
    if (is_single() || is_page()) {
        wp_enqueue_style(
            'gpress-print',
            GPRESS_THEME_URI . '/assets/css/print.css',
            array('gpress-style'),
            GPRESS_VERSION,
            'print'
        );
    }
}
add_action('wp_enqueue_scripts', 'gpress_conditional_css_modules');

/**
 * Inline Critical CSS
 * Inline critical above-the-fold CSS for better performance
 */
function gpress_inline_critical_css() {
    $critical_css_file = GPRESS_THEME_DIR . '/assets/css/critical.css';
    
    if (file_exists($critical_css_file)) {
        $critical_css = file_get_contents($critical_css_file);
        $critical_css = gpress_minify_css($critical_css);
        
        // Only inline on front-facing pages
        if (!is_admin() && !is_customize_preview()) {
            echo '<style id="gpress-critical-css">' . $critical_css . '</style>';
        }
    }
}
add_action('wp_head', 'gpress_inline_critical_css', 1);

/**
 * Load Non-Critical CSS Asynchronously
 * Improve Core Web Vitals by loading non-critical CSS async
 */
function gpress_async_css_loading() {
    ?>
    <script>
    // Async CSS loading function
    function loadCSS(href, before, media, attributes) {
        var doc = window.document;
        var ss = doc.createElement("link");
        var ref;
        if (before) {
            ref = before;
        } else {
            var refs = (doc.body || doc.getElementsByTagName("head")[0]).childNodes;
            ref = refs[refs.length - 1];
        }
        var sheets = doc.styleSheets;
        if (attributes) {
            for (var attributeName in attributes) {
                if (attributes.hasOwnProperty(attributeName)) {
                    ss.setAttribute(attributeName, attributes[attributeName]);
                }
            }
        }
        ss.rel = "stylesheet";
        ss.href = href;
        ss.media = "only x";
        function ready(cb) {
            if (doc.body) {
                return cb();
            }
            setTimeout(function() {
                ready(cb);
            });
        }
        ready(function() {
            ref.parentNode.insertBefore(ss, (before ? ref : ref.nextSibling));
        });
        var onloadcssdefined = function(cb) {
            var resolvedHref = ss.href;
            var i = sheets.length;
            while (i--) {
                if (sheets[i].href === resolvedHref) {
                    return cb();
                }
            }
            setTimeout(function() {
                onloadcssdefined(cb);
            });
        };
        function loadCB() {
            if (ss.addEventListener) {
                ss.removeEventListener("load", loadCB);
            }
            ss.media = media || "all";
        }
        if (ss.addEventListener) {
            ss.addEventListener("load", loadCB);
        }
        ss.onloadcssdefined = onloadcssdefined;
        onloadcssdefined(loadCB);
        return ss;
    }
    
    // Load non-critical CSS after page load
    window.addEventListener('load', function() {
        <?php if (wp_style_is('gpress-components', 'enqueued')): ?>
        loadCSS('<?php echo wp_get_attachment_url(get_theme_file_uri('/assets/css/components.min.css')); ?>');
        <?php endif; ?>
        
        <?php if (wp_style_is('gpress-responsive', 'enqueued')): ?>
        loadCSS('<?php echo wp_get_attachment_url(get_theme_file_uri('/assets/css/responsive.min.css')); ?>');
        <?php endif; ?>
    });
    </script>
    <?php
}
add_action('wp_head', 'gpress_async_css_loading', 20);

/**
 * CSS Custom Properties Generator
 * Generate dynamic CSS custom properties from theme options
 */
function gpress_dynamic_css_properties() {
    $primary_color = get_theme_mod('primary_color', '#2c3e50');
    $secondary_color = get_theme_mod('secondary_color', '#3498db');
    $accent_color = get_theme_mod('accent_color', '#e74c3c');
    $font_size_base = get_theme_mod('font_size_base', 16);
    
    $css = ':root {';
    $css .= '--gpress-primary-color: ' . esc_attr($primary_color) . ';';
    $css .= '--gpress-secondary-color: ' . esc_attr($secondary_color) . ';';
    $css .= '--gpress-accent-color: ' . esc_attr($accent_color) . ';';
    $css .= '--gpress-font-size-base: ' . esc_attr($font_size_base) . 'px;';
    $css .= '}';
    
    wp_add_inline_style('gpress-style', $css);
}
add_action('wp_enqueue_scripts', 'gpress_dynamic_css_properties', 20);

/**
 * CSS Minification
 * Simple CSS minification for inline styles
 */
function gpress_minify_css($css) {
    // Remove comments
    $css = preg_replace('!/\*[^*]*\*+([^/][^*]*\*+)*/!', '', $css);
    
    // Remove whitespace
    $css = str_replace(array("\r\n", "\r", "\n", "\t", '  ', '    ', '    '), '', $css);
    
    return $css;
}

/**
 * Preload Critical Resources
 * Preload important CSS and font files
 */
function gpress_preload_critical_resources() {
    // Preload main stylesheet
    echo '<link rel="preload" href="' . get_stylesheet_uri() . '" as="style" onload="this.onload=null;this.rel=\'stylesheet\'">';
    
    // Preload critical fonts
    $google_fonts = get_theme_mod('google_fonts_enable', false);
    if ($google_fonts) {
        echo '<link rel="preconnect" href="https://fonts.googleapis.com">';
        echo '<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>';
    }
    
    // Preload hero image on front page
    if (is_front_page() && has_custom_header()) {
        $header_image = get_header_image();
        if ($header_image) {
            echo '<link rel="preload" href="' . esc_url($header_image) . '" as="image">';
        }
    }
}
add_action('wp_head', 'gpress_preload_critical_resources', 1);

/**
 * Block-Specific CSS Loading
 * Load CSS for specific blocks only when present
 */
function gpress_block_specific_css() {
    global $post;
    
    if (!$post) return;
    
    $post_content = $post->post_content;
    
    // Gallery block styles
    if (has_block('core/gallery', $post) || has_block('core/image', $post)) {
        wp_add_inline_style('gpress-style', '
            .wp-block-gallery { 
                gap: 1rem; 
            }
            .wp-block-gallery .wp-block-image img { 
                border-radius: 8px; 
                transition: transform 0.3s ease; 
            }
            .wp-block-gallery .wp-block-image:hover img { 
                transform: scale(1.02); 
            }
        ');
    }
    
    // Video block styles
    if (has_block('core/video', $post) || has_block('core/embed', $post)) {
        wp_add_inline_style('gpress-style', '
            .wp-block-video video,
            .wp-block-embed iframe { 
                border-radius: 12px; 
                box-shadow: 0 4px 12px rgba(0,0,0,0.1); 
            }
        ');
    }
    
    // Quote block styles
    if (has_block('core/quote', $post) || has_block('core/pullquote', $post)) {
        wp_add_inline_style('gpress-style', '
            .wp-block-quote { 
                border-left: 4px solid var(--wp--preset--color--primary); 
                padding-left: 2rem; 
                font-style: italic; 
            }
            .wp-block-pullquote { 
                border-top: 4px solid var(--wp--preset--color--primary); 
                border-bottom: 4px solid var(--wp--preset--color--primary); 
                padding: 2rem 0; 
                text-align: center; 
            }
        ');
    }
    
    // Table block styles
    if (has_block('core/table', $post)) {
        wp_add_inline_style('gpress-style', '
            .wp-block-table table { 
                border-collapse: collapse; 
                width: 100%; 
            }
            .wp-block-table th,
            .wp-block-table td { 
                border: 1px solid var(--wp--preset--color--gray-300); 
                padding: 0.75rem; 
            }
            .wp-block-table th { 
                background: var(--wp--preset--color--gray-50); 
                font-weight: 600; 
            }
        ');
    }
}
add_action('wp_enqueue_scripts', 'gpress_block_specific_css', 30);

/**
 * CSS Performance Monitoring
 * Add performance hints for CSS loading
 */
function gpress_css_performance_hints() {
    // Resource hints for external stylesheets
    echo '<link rel="dns-prefetch" href="//fonts.googleapis.com">';
    echo '<link rel="dns-prefetch" href="//fonts.gstatic.com">';
    
    // Critical resource hints
    echo '<link rel="preconnect" href="' . home_url() . '">';
}
add_action('wp_head', 'gpress_css_performance_hints', 1);

/**
 * CSS Cache Busting
 * Add version parameters for better cache control
 */
function gpress_css_cache_busting($src, $handle) {
    // Add timestamp for development
    if (defined('WP_DEBUG') && WP_DEBUG) {
        $file_path = str_replace(GPRESS_THEME_URI, GPRESS_THEME_DIR, $src);
        if (file_exists($file_path)) {
            $file_time = filemtime($file_path);
            $src = add_query_arg('t', $file_time, $src);
        }
    }
    
    return $src;
}
add_filter('style_loader_src', 'gpress_css_cache_busting', 10, 2);
```

## 2. Create Component Styles

### File: `assets/css/components.css`
```css
/*
 * Component Styles for GPress Theme
 * Conditional component-specific styling
 * Version: 1.0.0
 */

/* ==========================================================================
   CSS Custom Properties Integration
   ========================================================================== */

:root {
  /* Theme.json variables are automatically generated by WordPress */
  /* Additional custom properties for advanced features */
  --gpress-transition-base: 0.2s ease;
  --gpress-transition-slow: 0.3s ease;
  --gpress-border-radius-small: 4px;
  --gpress-border-radius-medium: 8px;
  --gpress-border-radius-large: 12px;
  --gpress-box-shadow-small: 0 2px 4px rgba(0, 0, 0, 0.1);
  --gpress-box-shadow-medium: 0 4px 8px rgba(0, 0, 0, 0.12);
  --gpress-box-shadow-large: 0 8px 24px rgba(0, 0, 0, 0.15);
  --gpress-z-index-dropdown: 100;
  --gpress-z-index-sticky: 200;
  --gpress-z-index-modal: 1000;
  --gpress-z-index-tooltip: 1100;
}

/* ==========================================================================
   Layout Components
   ========================================================================== */

/* Site Container */
.site-container {
  min-height: 100vh;
  display: flex;
  flex-direction: column;
}

/* Main Content Area */
.site-main {
  flex: 1;
}

/* Constrained Layout Enhancement */
.wp-block-group.is-layout-constrained {
  padding-left: var(--wp--preset--spacing--medium);
  padding-right: var(--wp--preset--spacing--medium);
}

/* ==========================================================================
   Block Enhancements
   ========================================================================== */

/* Post Cards */
.post-card,
.blog-post-card {
  transition: transform var(--gpress-transition-base), box-shadow var(--gpress-transition-base);
  border: 1px solid var(--wp--preset--color--gray-200);
  box-shadow: var(--gpress-box-shadow-small);
  border-radius: var(--gpress-border-radius-medium);
  background: var(--wp--preset--color--white);
  overflow: hidden;
}

.post-card:hover,
.blog-post-card:hover {
  transform: translateY(-2px);
  box-shadow: var(--gpress-box-shadow-medium);
}

/* Archive Post Cards */
.archive-post-card,
.category-post-card {
  transition: transform var(--gpress-transition-base), box-shadow var(--gpress-transition-base);
  border: 1px solid var(--wp--preset--color--gray-200);
  box-shadow: var(--gpress-box-shadow-small);
  border-radius: var(--gpress-border-radius-medium);
  background: var(--wp--preset--color--white);
  overflow: hidden;
  cursor: pointer;
}

.archive-post-card:hover,
.category-post-card:hover {
  transform: translateY(-3px);
  box-shadow: var(--gpress-box-shadow-large);
}

/* Featured Images */
.wp-block-post-featured-image img,
.post-thumbnail img {
  transition: transform var(--gpress-transition-base);
  border-radius: var(--gpress-border-radius-medium);
}

.wp-block-post-featured-image:hover img,
.post-thumbnail:hover img {
  transform: scale(1.02);
}

/* Button Enhancements */
.wp-block-button__link {
  transition: all var(--gpress-transition-base);
  border: 2px solid transparent;
  border-radius: var(--gpress-border-radius-small);
  padding: 0.75rem 1.5rem;
  font-weight: 600;
  text-decoration: none;
  display: inline-block;
}

.wp-block-button__link:hover {
  transform: translateY(-1px);
  box-shadow: var(--gpress-box-shadow-small);
}

.wp-block-button.is-style-outline .wp-block-button__link {
  border-color: currentColor;
  background: transparent;
}

.wp-block-button.is-style-outline .wp-block-button__link:hover {
  background: var(--wp--preset--color--primary);
  color: var(--wp--preset--color--white);
}

/* Search Form Enhancement */
.wp-block-search {
  max-width: 100%;
}

.wp-block-search__input {
  border: 1px solid var(--wp--preset--color--gray-300);
  border-radius: var(--gpress-border-radius-small);
  padding: 0.75rem 1rem;
  transition: border-color var(--gpress-transition-base), box-shadow var(--gpress-transition-base);
  font-size: var(--wp--preset--font-size--medium);
  width: 100%;
}

.wp-block-search__input:focus {
  border-color: var(--wp--preset--color--primary);
  box-shadow: 0 0 0 2px rgba(44, 62, 80, 0.1);
  outline: none;
}

.wp-block-search__button {
  border-radius: var(--gpress-border-radius-small);
  padding: 0.75rem 1rem;
  transition: all var(--gpress-transition-base);
  background: var(--wp--preset--color--primary);
  color: var(--wp--preset--color--white);
  border: none;
  cursor: pointer;
  font-weight: 600;
}

.wp-block-search__button:hover {
  background: var(--wp--preset--color--secondary);
  transform: translateY(-1px);
}

/* ==========================================================================
   Navigation Enhancements
   ========================================================================== */

.wp-block-navigation-link__content {
  transition: color var(--gpress-transition-base);
  font-weight: 500;
  text-decoration: none;
}

.wp-block-navigation .wp-block-navigation-item.current-menu-item .wp-block-navigation-link__content {
  color: var(--wp--preset--color--primary);
  font-weight: 600;
}

/* Submenu Styling */
.wp-block-navigation .has-child .wp-block-navigation__submenu-container {
  background: var(--wp--preset--color--white);
  border: 1px solid var(--wp--preset--color--gray-200);
  border-radius: var(--gpress-border-radius-medium);
  box-shadow: var(--gpress-box-shadow-medium);
  padding: var(--wp--preset--spacing--small);
  min-width: 200px;
  margin-top: 0.5rem;
}

.wp-block-navigation .has-child .wp-block-navigation__submenu-container .wp-block-navigation-link__content {
  padding: 0.5rem 1rem;
  border-radius: var(--gpress-border-radius-small);
  transition: background-color var(--gpress-transition-base);
}

.wp-block-navigation .has-child .wp-block-navigation__submenu-container .wp-block-navigation-link__content:hover {
  background: var(--wp--preset--color--gray-50);
}

/* ==========================================================================
   Template-Specific Components
   ========================================================================== */

/* Header Enhancements */
.wp-block-template-part[data-area="header"] {
  position: sticky;
  top: 0;
  z-index: var(--gpress-z-index-sticky);
  background: rgba(255, 255, 255, 0.95);
  backdrop-filter: blur(10px);
  border-bottom: 1px solid var(--wp--preset--color--gray-200);
  transition: background-color var(--gpress-transition-base);
}

/* Header scrolled state */
.header-scrolled {
  background: var(--wp--preset--color--white);
  box-shadow: var(--gpress-box-shadow-small);
}

/* Footer Styling */
.wp-block-template-part[data-area="footer"] {
  margin-top: auto;
  background: var(--wp--preset--color--gray-50);
  border-top: 1px solid var(--wp--preset--color--gray-200);
}

/* Sidebar Widgets */
.wp-block-template-part .wp-block-group {
  transition: transform var(--gpress-transition-base);
  border-radius: var(--gpress-border-radius-medium);
  padding: var(--wp--preset--spacing--medium);
  background: var(--wp--preset--color--white);
  border: 1px solid var(--wp--preset--color--gray-200);
}

.wp-block-template-part .wp-block-group:hover {
  transform: translateY(-1px);
  box-shadow: var(--gpress-box-shadow-small);
}

/* ==========================================================================
   Form Components
   ========================================================================== */

/* Newsletter Form */
.newsletter-form {
  width: 100%;
  background: var(--wp--preset--color--gray-50);
  border-radius: var(--gpress-border-radius-medium);
  padding: var(--wp--preset--spacing--medium);
  border: 1px solid var(--wp--preset--color--gray-200);
}

.newsletter-input-group {
  display: flex;
  gap: 0.5rem;
  margin-bottom: 0.5rem;
}

.newsletter-input {
  flex: 1;
  padding: 0.75rem;
  border: 1px solid var(--wp--preset--color--gray-300);
  border-radius: var(--gpress-border-radius-small);
  font-size: var(--wp--preset--font-size--small);
  transition: border-color var(--gpress-transition-base);
}

.newsletter-input:focus {
  border-color: var(--wp--preset--color--primary);
  outline: none;
  box-shadow: 0 0 0 2px rgba(44, 62, 80, 0.1);
}

.newsletter-button {
  padding: 0.75rem 1rem;
  background: var(--wp--preset--color--primary);
  color: var(--wp--preset--color--white);
  border: none;
  border-radius: var(--gpress-border-radius-small);
  font-size: var(--wp--preset--font-size--small);
  font-weight: 600;
  cursor: pointer;
  transition: all var(--gpress-transition-base);
  white-space: nowrap;
}

.newsletter-button:hover {
  background: var(--wp--preset--color--secondary);
  transform: translateY(-1px);
}

.newsletter-privacy {
  margin: 0.5rem 0 0 0;
  font-size: var(--wp--preset--font-size--x-small);
  color: var(--wp--preset--color--gray-600);
  line-height: 1.4;
}

/* Contact Form */
.contact-form {
  max-width: 100%;
  background: var(--wp--preset--color--white);
  border-radius: var(--gpress-border-radius-medium);
  padding: var(--wp--preset--spacing--large);
  border: 1px solid var(--wp--preset--color--gray-200);
  box-shadow: var(--gpress-box-shadow-small);
}

.form-group {
  margin-bottom: 1rem;
}

.form-group label {
  display: block;
  margin-bottom: 0.25rem;
  font-weight: 600;
  color: var(--wp--preset--color--gray-700);
}

.form-group input,
.form-group textarea {
  width: 100%;
  padding: 0.75rem;
  border: 1px solid var(--wp--preset--color--gray-300);
  border-radius: var(--gpress-border-radius-small);
  font-size: var(--wp--preset--font-size--medium);
  transition: border-color var(--gpress-transition-base), box-shadow var(--gpress-transition-base);
  font-family: inherit;
}

.form-group input:focus,
.form-group textarea:focus {
  border-color: var(--wp--preset--color--primary);
  outline: none;
  box-shadow: 0 0 0 2px rgba(44, 62, 80, 0.1);
}

.contact-submit {
  padding: 0.875rem 2rem;
  background: var(--wp--preset--color--primary);
  color: var(--wp--preset--color--white);
  border: none;
  border-radius: var(--gpress-border-radius-small);
  font-weight: 600;
  cursor: pointer;
  transition: all var(--gpress-transition-base);
  font-size: var(--wp--preset--font-size--medium);
}

.contact-submit:hover {
  background: var(--wp--preset--color--secondary);
  transform: translateY(-1px);
}

.contact-submit:disabled {
  opacity: 0.6;
  cursor: not-allowed;
  transform: none;
}

/* ==========================================================================
   Comment System
   ========================================================================== */

.wp-block-post-comments-form input[type="text"],
.wp-block-post-comments-form input[type="email"],
.wp-block-post-comments-form input[type="url"],
.wp-block-post-comments-form textarea {
  border: 1px solid var(--wp--preset--color--gray-300);
  border-radius: var(--gpress-border-radius-small);
  padding: 0.75rem;
  transition: border-color var(--gpress-transition-base);
  width: 100%;
  font-family: inherit;
}

.wp-block-post-comments-form input[type="text"]:focus,
.wp-block-post-comments-form input[type="email"]:focus,
.wp-block-post-comments-form input[type="url"]:focus,
.wp-block-post-comments-form textarea:focus {
  border-color: var(--wp--preset--color--primary);
  outline: none;
  box-shadow: 0 0 0 2px rgba(44, 62, 80, 0.1);
}

.wp-block-post-comments-form .form-submit input {
  background: var(--wp--preset--color--primary);
  color: var(--wp--preset--color--white);
  border: none;
  border-radius: var(--gpress-border-radius-small);
  padding: 0.75rem 1.5rem;
  font-weight: 600;
  cursor: pointer;
  transition: background-color var(--gpress-transition-base);
}

.wp-block-post-comments-form .form-submit input:hover {
  background: var(--wp--preset--color--secondary);
}

/* ==========================================================================
   Utility Classes
   ========================================================================== */

/* Loading States */
.loading {
  opacity: 0.6;
  pointer-events: none;
  position: relative;
}

.loading::after {
  content: "";
  position: absolute;
  top: 50%;
  left: 50%;
  width: 20px;
  height: 20px;
  margin: -10px 0 0 -10px;
  border: 2px solid var(--wp--preset--color--gray-300);
  border-top-color: var(--wp--preset--color--primary);
  border-radius: 50%;
  animation: gpress-spin 1s linear infinite;
}

@keyframes gpress-spin {
  to {
    transform: rotate(360deg);
  }
}

/* Error States */
.error {
  border-color: var(--wp--preset--color--accent) !important;
}

.field-error {
  color: var(--wp--preset--color--accent);
  font-size: var(--wp--preset--font-size--small);
  margin-top: 0.25rem;
}

/* Success States */
.success {
  color: #22c55e;
  border-color: #22c55e;
}

/* ==========================================================================
   Animation Utilities
   ========================================================================== */

.fade-in {
  animation: gpress-fade-in 0.3s ease;
}

@keyframes gpress-fade-in {
  from {
    opacity: 0;
    transform: translateY(10px);
  }
  to {
    opacity: 1;
    transform: translateY(0);
  }
}

.slide-up {
  animation: gpress-slide-up 0.3s ease;
}

@keyframes gpress-slide-up {
  from {
    opacity: 0;
    transform: translateY(20px);
  }
  to {
    opacity: 1;
    transform: translateY(0);
  }
}

/* ==========================================================================
   Responsive Component Adjustments
   ========================================================================== */

@media (max-width: 600px) {
  .newsletter-input-group {
    flex-direction: column;
  }
  
  .newsletter-input {
    margin-bottom: 0.5rem;
  }
  
  .wp-block-navigation .has-child .wp-block-navigation__submenu-container {
    position: static;
    box-shadow: none;
    border: none;
    background: transparent;
    padding: 0;
    margin-top: 0;
  }
  
  .contact-form {
    padding: var(--wp--preset--spacing--medium);
  }
}

@media (min-width: 768px) {
  .wp-block-group.is-layout-constrained {
    padding-left: var(--wp--preset--spacing--large);
    padding-right: var(--wp--preset--spacing--large);
  }
  
  .wp-block-navigation .has-child .wp-block-navigation__submenu-container {
    min-width: 220px;
  }
}

@media (min-width: 1200px) {
  .wp-block-group.is-layout-constrained {
    padding-left: var(--wp--preset--spacing--x-large);
    padding-right: var(--wp--preset--spacing--x-large);
  }
}

/* ==========================================================================
   Performance Optimizations
   ========================================================================== */

/* Reduce motion for users who prefer it */
@media (prefers-reduced-motion: reduce) {
  *,
  *::before,
  *::after {
    animation-duration: 0.01ms !important;
    animation-iteration-count: 1 !important;
    transition-duration: 0.01ms !important;
  }
}

/* High contrast mode support */
@media (prefers-contrast: high) {
  .post-card,
  .archive-post-card,
  .category-post-card {
    border-width: 2px;
  }
  
  .wp-block-button__link {
    border-width: 2px;
  }
}

/* Dark mode preparation */
@media (prefers-color-scheme: dark) {
  :root {
    --gpress-dark-bg: #1a1a1a;
    --gpress-dark-text: #e0e0e0;
    --gpress-dark-border: #333;
  }
  
  /* Uncomment when implementing dark mode */
  /*
  .post-card,
  .archive-post-card,
  .category-post-card {
    background: var(--gpress-dark-bg);
    border-color: var(--gpress-dark-border);
    color: var(--gpress-dark-text);
  }
  */
}
```

## 3. Create Advanced Responsive Styles

### File: `assets/css/responsive.css`
```css
/*
 * Advanced Responsive Styles for GPress Theme
 * Mobile-first responsive design enhancements
 * Version: 1.0.0
 */

/* ==========================================================================
   Responsive Typography Scale
   ========================================================================== */

/* Base mobile typography */
:root {
  --gpress-h1-mobile: clamp(1.75rem, 4vw, 2.5rem);
  --gpress-h2-mobile: clamp(1.5rem, 3.5vw, 2rem);
  --gpress-h3-mobile: clamp(1.25rem, 3vw, 1.75rem);
  --gpress-body-mobile: clamp(0.875rem, 2.5vw, 1rem);
}

/* Desktop typography */
@media (min-width: 768px) {
  :root {
    --gpress-h1-desktop: clamp(2.5rem, 5vw, 3.5rem);
    --gpress-h2-desktop: clamp(2rem, 4vw, 2.75rem);
    --gpress-h3-desktop: clamp(1.75rem, 3vw, 2.25rem);
    --gpress-body-desktop: clamp(1rem, 2vw, 1.125rem);
  }
}

/* ==========================================================================
   Container Queries (Future Enhancement)
   ========================================================================== */

/* When browser support improves, replace with container queries */
.post-card-container {
  container-type: inline-size;
}

/* Simulate container query behavior with media queries for now */
@media (min-width: 400px) {
  .post-card {
    display: grid;
    grid-template-columns: 1fr 2fr;
    gap: 1rem;
    align-items: start;
  }
  
  .post-card .wp-block-post-featured-image {
    grid-column: 1;
    grid-row: 1 / -1;
  }
  
  .post-card .post-content {
    grid-column: 2;
  }
}

/* ==========================================================================
   Advanced Grid Layouts
   ========================================================================== */

/* Dynamic grid based on screen size */
.wp-block-post-template.is-layout-grid {
  display: grid;
  gap: var(--wp--preset--spacing--medium);
}

/* Mobile: 1 column */
@media (max-width: 767px) {
  .wp-block-post-template.is-layout-grid {
    grid-template-columns: 1fr;
  }
}

/* Tablet: 2 columns */
@media (min-width: 768px) and (max-width: 1023px) {
  .wp-block-post-template.is-layout-grid {
    grid-template-columns: repeat(2, 1fr);
    gap: var(--wp--preset--spacing--large);
  }
}

/* Desktop: 3 columns */
@media (min-width: 1024px) {
  .wp-block-post-template.is-layout-grid {
    grid-template-columns: repeat(3, 1fr);
    gap: var(--wp--preset--spacing--x-large);
  }
}

/* Large desktop: flexible columns */
@media (min-width: 1400px) {
  .wp-block-post-template.is-layout-grid {
    grid-template-columns: repeat(auto-fit, minmax(320px, 1fr));
  }
}

/* ==========================================================================
   Responsive Navigation
   ========================================================================== */

/* Mobile navigation enhancements */
@media (max-width: 767px) {
  .wp-block-navigation__responsive-container.is-menu-open {
    background: rgba(255, 255, 255, 0.98);
    backdrop-filter: blur(20px);
    border-radius: var(--gpress-border-radius-medium);
    margin: 1rem;
    box-shadow: var(--gpress-box-shadow-large);
  }
  
  .wp-block-navigation__responsive-container-content {
    padding: var(--wp--preset--spacing--medium);
  }
  
  .wp-block-navigation-item {
    margin-bottom: 0.5rem;
  }
  
  .wp-block-navigation-link__content {
    padding: 0.75rem 1rem;
    border-radius: var(--gpress-border-radius-small);
    transition: background-color var(--gpress-transition-base);
  }
  
  .wp-block-navigation-link__content:hover {
    background: var(--wp--preset--color--gray-50);
  }
}

/* Tablet navigation */
@media (min-width: 768px) and (max-width: 1023px) {
  .wp-block-navigation {
    gap: 1.5rem;
  }
  
  .wp-block-navigation-link__content {
    font-size: var(--wp--preset--font-size--small);
  }
}

/* Desktop navigation */
@media (min-width: 1024px) {
  .wp-block-navigation {
    gap: 2rem;
  }
  
  /* Enhanced hover effects */
  .wp-block-navigation-link__content::after {
    content: '';
    position: absolute;
    bottom: -2px;
    left: 0;
    width: 0;
    height: 2px;
    background: var(--wp--preset--color--primary);
    transition: width var(--gpress-transition-base);
  }
  
  .wp-block-navigation-link__content:hover::after {
    width: 100%;
  }
}

/* ==========================================================================
   Responsive Forms
   ========================================================================== */

/* Mobile form optimizations */
@media (max-width: 767px) {
  .contact-form {
    padding: 1rem;
  }
  
  .form-group input,
  .form-group textarea {
    font-size: 16px; /* Prevent zoom on iOS */
  }
  
  .contact-submit {
    width: 100%;
    padding: 1rem;
    font-size: var(--wp--preset--font-size--medium);
  }
  
  .newsletter-form {
    padding: 1rem;
  }
  
  .newsletter-input-group {
    flex-direction: column;
    gap: 0.75rem;
  }
  
  .newsletter-button {
    width: 100%;
    padding: 0.875rem;
  }
}

/* Tablet form enhancements */
@media (min-width: 768px) and (max-width: 1023px) {
  .contact-form {
    padding: 1.5rem;
  }
  
  .newsletter-input-group {
    max-width: 400px;
  }
}

/* Desktop form styling */
@media (min-width: 1024px) {
  .contact-form {
    padding: 2rem;
    max-width: 600px;
  }
  
  .newsletter-form {
    max-width: 500px;
  }
  
  .newsletter-input-group {
    max-width: none;
  }
}

/* ==========================================================================
   Responsive Images and Media
   ========================================================================== */

/* Enhanced image responsiveness */
.wp-block-image,
.wp-block-post-featured-image {
  position: relative;
  overflow: hidden;
}

/* Mobile image optimizations */
@media (max-width: 767px) {
  .wp-block-image img,
  .wp-block-post-featured-image img {
    border-radius: var(--gpress-border-radius-small);
  }
  
  .wp-block-gallery {
    gap: 0.5rem;
  }
}

/* Tablet image enhancements */
@media (min-width: 768px) and (max-width: 1023px) {
  .wp-block-image img,
  .wp-block-post-featured-image img {
    border-radius: var(--gpress-border-radius-medium);
  }
  
  .wp-block-gallery {
    gap: 1rem;
  }
}

/* Desktop image styling */
@media (min-width: 1024px) {
  .wp-block-image img,
  .wp-block-post-featured-image img {
    border-radius: var(--gpress-border-radius-large);
  }
  
  .wp-block-gallery {
    gap: 1.5rem;
  }
  
  /* Enhanced hover effects */
  .wp-block-image:hover img,
  .wp-block-post-featured-image:hover img {
    transform: scale(1.02);
  }
}

/* ==========================================================================
   Responsive Header and Footer
   ========================================================================== */

/* Mobile header */
@media (max-width: 767px) {
  .wp-block-template-part[data-area="header"] {
    padding: 0.5rem 1rem;
  }
  
  .site-title {
    font-size: var(--wp--preset--font-size--large);
  }
  
  .site-tagline {
    font-size: var(--wp--preset--font-size--small);
  }
}

/* Tablet header */
@media (min-width: 768px) and (max-width: 1023px) {
  .wp-block-template-part[data-area="header"] {
    padding: 1rem 2rem;
  }
}

/* Desktop header */
@media (min-width: 1024px) {
  .wp-block-template-part[data-area="header"] {
    padding: 1rem 3rem;
  }
  
  /* Sticky header enhancements */
  .wp-block-template-part[data-area="header"].scrolled {
    padding: 0.5rem 3rem;
    transition: padding var(--gpress-transition-base);
  }
}

/* Mobile footer */
@media (max-width: 767px) {
  .wp-block-template-part[data-area="footer"] {
    padding: 2rem 1rem;
  }
  
  .wp-block-template-part[data-area="footer"] .wp-block-columns {
    flex-direction: column;
    gap: 1.5rem;
  }
}

/* Desktop footer */
@media (min-width: 768px) {
  .wp-block-template-part[data-area="footer"] {
    padding: 3rem;
  }
}

/* ==========================================================================
   Responsive Utilities
   ========================================================================== */

/* Show/hide content based on screen size */
.mobile-only {
  display: block;
}

.tablet-only,
.desktop-only {
  display: none;
}

@media (min-width: 768px) and (max-width: 1023px) {
  .mobile-only,
  .desktop-only {
    display: none;
  }
  
  .tablet-only {
    display: block;
  }
}

@media (min-width: 1024px) {
  .mobile-only,
  .tablet-only {
    display: none;
  }
  
  .desktop-only {
    display: block;
  }
}

/* Responsive spacing utilities */
.spacing-mobile-small {
  margin: var(--wp--preset--spacing--small);
}

.spacing-mobile-medium {
  margin: var(--wp--preset--spacing--medium);
}

@media (min-width: 768px) {
  .spacing-tablet-large {
    margin: var(--wp--preset--spacing--large);
  }
}

@media (min-width: 1024px) {
  .spacing-desktop-x-large {
    margin: var(--wp--preset--spacing--x-large);
  }
}

/* ==========================================================================
   Performance Optimizations
   ========================================================================== */

/* Optimize animations for different devices */
@media (max-width: 767px) {
  /* Reduce animations on mobile for better performance */
  .post-card:hover,
  .archive-post-card:hover {
    transform: none;
  }
  
  .wp-block-post-featured-image:hover img {
    transform: none;
  }
}

/* Enhanced animations for devices with better performance */
@media (min-width: 1024px) and (prefers-reduced-motion: no-preference) {
  .post-card:hover {
    transform: translateY(-4px) rotateX(2deg);
  }
  
  .archive-post-card:hover {
    transform: translateY(-6px) scale(1.02);
  }
}

/* Optimize for high-DPI displays */
@media (min-resolution: 2dppx) {
  .post-card,
  .archive-post-card {
    border-width: 0.5px;
  }
}

/* Print optimizations */
@media print {
  .mobile-only,
  .tablet-only {
    display: none !important;
  }
  
  .desktop-only {
    display: block !important;
  }
  
  .wp-block-template-part[data-area="header"],
  .wp-block-template-part[data-area="footer"] {
    position: static;
    box-shadow: none;
    border: none;
    background: transparent;
  }
  
  .post-card,
  .archive-post-card {
    box-shadow: none;
    border: 1px solid #ddd;
    break-inside: avoid;
  }
}
```

## 4. Create Editor Styles

### File: `assets/css/editor-style.css`
```css
/*
 * Block Editor Styles for GPress Theme
 * Ensures WYSIWYG experience in Gutenberg
 * Version: 1.0.0
 */

/* ==========================================================================
   Editor Base Styles
   ========================================================================== */

/* Match frontend typography */
.editor-styles-wrapper {
  font-family: var(--wp--preset--font-family--system);
  font-size: var(--wp--preset--font-size--medium);
  line-height: 1.6;
  color: var(--wp--preset--color--gray-800);
  background: var(--wp--preset--color--white);
}

.editor-styles-wrapper .wp-block {
  max-width: none;
}

/* Editor-specific custom properties */
.editor-styles-wrapper {
  --gpress-editor-spacing: 1rem;
  --gpress-editor-border-radius: 8px;
  --gpress-editor-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
}

/* ==========================================================================
   Block Editor Enhancements
   ========================================================================== */

/* Post cards in editor */
.editor-styles-wrapper .post-card,
.editor-styles-wrapper .blog-post-card,
.editor-styles-wrapper .archive-post-card {
  border: 1px solid var(--wp--preset--color--gray-200);
  border-radius: var(--gpress-editor-border-radius);
  padding: var(--wp--preset--spacing--medium);
  background: var(--wp--preset--color--white);
  box-shadow: var(--gpress-editor-shadow);
  margin-bottom: var(--gpress-editor-spacing);
}

/* Button preview */
.editor-styles-wrapper .wp-block-button__link {
  border-radius: var(--gpress-border-radius-small, 4px);
  padding: 0.75rem 1.5rem;
  transition: none; /* Disable transitions in editor */
  font-weight: 600;
  text-decoration: none;
  display: inline-block;
}

.editor-styles-wrapper .wp-block-button.is-style-outline .wp-block-button__link {
  border: 2px solid currentColor;
  background: transparent;
}

/* Navigation preview */
.editor-styles-wrapper .wp-block-navigation .wp-block-navigation-link__content {
  font-weight: 500;
  text-decoration: none;
  color: var(--wp--preset--color--gray-800);
}

.editor-styles-wrapper .wp-block-navigation .wp-block-navigation-item.current-menu-item .wp-block-navigation-link__content {
  color: var(--wp--preset--color--primary);
  font-weight: 600;
}

/* Form elements in editor */
.editor-styles-wrapper input,
.editor-styles-wrapper textarea,
.editor-styles-wrapper select {
  border: 1px solid var(--wp--preset--color--gray-300);
  border-radius: var(--gpress-border-radius-small, 4px);
  padding: 0.75rem;
  font-family: inherit;
  font-size: var(--wp--preset--font-size--medium);
  width: 100%;
  max-width: 100%;
}

.editor-styles-wrapper .wp-block-search__input {
  border: 1px solid var(--wp--preset--color--gray-300);
  border-radius: var(--gpress-border-radius-small, 4px);
  padding: 0.75rem 1rem;
}

.editor-styles-wrapper .wp-block-search__button {
  border-radius: var(--gpress-border-radius-small, 4px);
  padding: 0.75rem 1rem;
  background: var(--wp--preset--color--primary);
  color: var(--wp--preset--color--white);
  border: none;
  font-weight: 600;
}

/* ==========================================================================
   Enhanced Block Previews
   ========================================================================== */

/* Image blocks */
.editor-styles-wrapper .wp-block-image img,
.editor-styles-wrapper .wp-block-post-featured-image img {
  border-radius: var(--gpress-editor-border-radius);
  box-shadow: var(--gpress-editor-shadow);
}

/* Gallery blocks */
.editor-styles-wrapper .wp-block-gallery {
  gap: 1rem;
}

.editor-styles-wrapper .wp-block-gallery .wp-block-image img {
  border-radius: var(--gpress-border-radius-small, 4px);
}

/* Quote blocks */
.editor-styles-wrapper .wp-block-quote {
  border-left: 4px solid var(--wp--preset--color--primary);
  padding-left: 2rem;
  font-style: italic;
  margin: 2rem 0;
}

.editor-styles-wrapper .wp-block-pullquote {
  border-top: 4px solid var(--wp--preset--color--primary);
  border-bottom: 4px solid var(--wp--preset--color--primary);
  padding: 2rem 0;
  text-align: center;
  margin: 3rem 0;
}

/* Table blocks */
.editor-styles-wrapper .wp-block-table table {
  border-collapse: collapse;
  width: 100%;
}

.editor-styles-wrapper .wp-block-table th,
.editor-styles-wrapper .wp-block-table td {
  border: 1px solid var(--wp--preset--color--gray-300);
  padding: 0.75rem;
  text-align: left;
}

.editor-styles-wrapper .wp-block-table th {
  background: var(--wp--preset--color--gray-50);
  font-weight: 600;
}

/* Code blocks */
.editor-styles-wrapper .wp-block-code {
  background: var(--wp--preset--color--gray-50);
  border: 1px solid var(--wp--preset--color--gray-200);
  border-radius: var(--gpress-editor-border-radius);
  padding: 1rem;
  font-family: 'Monaco', 'Consolas', monospace;
  font-size: 0.875rem;
  overflow-x: auto;
}

/* Separator blocks */
.editor-styles-wrapper .wp-block-separator {
  border-color: var(--wp--preset--color--gray-300);
  margin: 2rem 0;
}

.editor-styles-wrapper .wp-block-separator.is-style-wide {
  width: 100%;
}

/* ==========================================================================
   Custom Block Styles Preview
   ========================================================================== */

/* Newsletter form preview */
.editor-styles-wrapper .newsletter-form {
  background: var(--wp--preset--color--gray-50);
  border-radius: var(--gpress-editor-border-radius);
  padding: var(--wp--preset--spacing--medium);
  border: 1px solid var(--wp--preset--color--gray-200);
}

.editor-styles-wrapper .newsletter-input-group {
  display: flex;
  gap: 0.5rem;
  margin-bottom: 0.5rem;
}

.editor-styles-wrapper .newsletter-input {
  flex: 1;
  padding: 0.75rem;
  border: 1px solid var(--wp--preset--color--gray-300);
  border-radius: var(--gpress-border-radius-small, 4px);
  font-size: var(--wp--preset--font-size--small);
}

.editor-styles-wrapper .newsletter-button {
  padding: 0.75rem 1rem;
  background: var(--wp--preset--color--primary);
  color: var(--wp--preset--color--white);
  border: none;
  border-radius: var(--gpress-border-radius-small, 4px);
  font-size: var(--wp--preset--font-size--small);
  font-weight: 600;
  white-space: nowrap;
}

/* Contact form preview */
.editor-styles-wrapper .contact-form {
  background: var(--wp--preset--color--white);
  border-radius: var(--gpress-editor-border-radius);
  padding: var(--wp--preset--spacing--large);
  border: 1px solid var(--wp--preset--color--gray-200);
  box-shadow: var(--gpress-editor-shadow);
}

.editor-styles-wrapper .form-group {
  margin-bottom: 1rem;
}

.editor-styles-wrapper .form-group label {
  display: block;
  margin-bottom: 0.25rem;
  font-weight: 600;
  color: var(--wp--preset--color--gray-700);
}

.editor-styles-wrapper .contact-submit {
  padding: 0.875rem 2rem;
  background: var(--wp--preset--color--primary);
  color: var(--wp--preset--color--white);
  border: none;
  border-radius: var(--gpress-border-radius-small, 4px);
  font-weight: 600;
  cursor: pointer;
}

/* ==========================================================================
   Editor-Specific Utilities
   ========================================================================== */

/* Better visual hierarchy in editor */
.editor-styles-wrapper h1,
.editor-styles-wrapper h2,
.editor-styles-wrapper h3,
.editor-styles-wrapper h4,
.editor-styles-wrapper h5,
.editor-styles-wrapper h6 {
  margin-top: 2rem;
  margin-bottom: 1rem;
  line-height: 1.2;
  font-weight: 600;
}

.editor-styles-wrapper h1 {
  font-size: var(--wp--preset--font-size--xx-large);
}

.editor-styles-wrapper h2 {
  font-size: var(--wp--preset--font-size--x-large);
}

.editor-styles-wrapper h3 {
  font-size: var(--wp--preset--font-size--large);
}

.editor-styles-wrapper p {
  margin-bottom: 1rem;
  line-height: 1.6;
}

.editor-styles-wrapper a {
  color: var(--wp--preset--color--primary);
  text-decoration: none;
}

.editor-styles-wrapper a:hover {
  text-decoration: underline;
}

/* Editor spacing improvements */
.editor-styles-wrapper .wp-block + .wp-block {
  margin-top: var(--gpress-editor-spacing);
}

/* List styling */
.editor-styles-wrapper ul,
.editor-styles-wrapper ol {
  padding-left: 2rem;
  margin-bottom: 1rem;
}

.editor-styles-wrapper li {
  margin-bottom: 0.5rem;
}

/* Blockquote styling */
.editor-styles-wrapper blockquote {
  border-left: 4px solid var(--wp--preset--color--gray-300);
  padding-left: 1.5rem;
  margin: 2rem 0;
  font-style: italic;
  color: var(--wp--preset--color--gray-700);
}

/* ==========================================================================
   Editor Responsive Preview
   ========================================================================== */

/* Mobile editor preview */
.editor-styles-wrapper[data-device="mobile"] .newsletter-input-group {
  flex-direction: column;
  gap: 0.75rem;
}

.editor-styles-wrapper[data-device="mobile"] .newsletter-button {
  width: 100%;
}

.editor-styles-wrapper[data-device="mobile"] .contact-submit {
  width: 100%;
  padding: 1rem;
}

/* Tablet editor preview */
.editor-styles-wrapper[data-device="tablet"] .wp-block-columns {
  flex-direction: column;
  gap: 1.5rem;
}

/* ==========================================================================
   Editor Dark Mode Support
   ========================================================================== */

@media (prefers-color-scheme: dark) {
  .editor-styles-wrapper {
    background: #1a1a1a;
    color: #e0e0e0;
  }
  
  .editor-styles-wrapper .post-card,
  .editor-styles-wrapper .blog-post-card,
  .editor-styles-wrapper .archive-post-card,
  .editor-styles-wrapper .contact-form {
    background: #2a2a2a;
    border-color: #404040;
    color: #e0e0e0;
  }
  
  .editor-styles-wrapper input,
  .editor-styles-wrapper textarea,
  .editor-styles-wrapper select {
    background: #2a2a2a;
    border-color: #404040;
    color: #e0e0e0;
  }
  
  .editor-styles-wrapper .wp-block-code {
    background: #2a2a2a;
    border-color: #404040;
  }
}

/* ==========================================================================
   Editor Performance Optimizations
   ========================================================================== */

/* Disable animations in editor for better performance */
.editor-styles-wrapper * {
  transition: none !important;
  animation: none !important;
}

/* Optimize image rendering in editor */
.editor-styles-wrapper img {
  image-rendering: auto;
  image-rendering: crisp-edges;
  image-rendering: -webkit-optimize-contrast;
}
```

## 5. Create Critical CSS

### File: `assets/css/critical.css`
```css
/*
 * Critical CSS for GPress Theme
 * Above-the-fold styles for performance
 * Version: 1.0.0
 */

/* Essential base styles */
*,*::before,*::after{box-sizing:border-box}*{margin:0;padding:0}html{scroll-behavior:smooth}body{line-height:1.6;font-family:var(--wp--preset--font-family--system);color:var(--wp--preset--color--gray-800);background:var(--wp--preset--color--white);-webkit-font-smoothing:antialiased;-moz-osx-font-smoothing:grayscale}

/* Critical layout */
.site-container{min-height:100vh;display:flex;flex-direction:column}.site-main{flex:1}

/* Header */
.wp-block-template-part[data-area="header"]{position:sticky;top:0;z-index:200;background:rgba(255,255,255,0.95);backdrop-filter:blur(10px);border-bottom:1px solid var(--wp--preset--color--gray-200)}

/* Navigation */
.wp-block-navigation{display:flex;flex-wrap:wrap;gap:2rem}.wp-block-navigation-link__content{color:var(--wp--preset--color--gray-800);text-decoration:none;font-weight:500;transition:color 0.2s ease}

/* Typography */
h1,h2,h3,h4,h5,h6{line-height:1.2;font-weight:600;color:var(--wp--preset--color--gray-800)}h1{font-size:var(--wp--preset--font-size--xx-large)}h2{font-size:var(--wp--preset--font-size--x-large)}a{color:var(--wp--preset--color--primary);text-decoration:none}a:hover{color:var(--wp--preset--color--secondary);text-decoration:underline}

/* Buttons */
.wp-block-button__link{background:var(--wp--preset--color--primary);color:var(--wp--preset--color--white);padding:0.75rem 1.5rem;border-radius:4px;font-weight:600;text-decoration:none;display:inline-block;transition:all 0.2s ease;border:2px solid transparent}

/* Grid layouts */
.wp-block-post-template.is-layout-grid{display:grid;gap:2rem}.wp-block-columns{display:flex;flex-wrap:wrap;gap:2rem}.wp-block-column{flex:1;min-width:0}

/* Images */
img{max-width:100%;height:auto;display:block}.wp-block-post-featured-image img{border-radius:8px}

/* Essential utilities */
.screen-reader-text{clip:rect(1px,1px,1px,1px);position:absolute!important;height:1px;width:1px;overflow:hidden}

/* Mobile-first responsive */
@media (min-width:768px){.wp-block-post-template.is-layout-grid{grid-template-columns:repeat(2,1fr)}}@media (min-width:1024px){.wp-block-post-template.is-layout-grid{grid-template-columns:repeat(3,1fr)}}

/* Performance optimizations */
@media (prefers-reduced-motion:reduce){*,*::before,*::after{animation-duration:0.01ms!important;animation-iteration-count:1!important;transition-duration:0.01ms!important}}
```

## 6. Create Print Styles

### File: `assets/css/print.css`
```css
/*
 * Print Styles for GPress Theme
 * Optimized for printing content
 * Version: 1.0.0
 */

@media print {
  /* Reset for print */
  * {
    background: transparent !important;
    color: black !important;
    box-shadow: none !important;
    text-shadow: none !important;
  }
  
  /* Page setup */
  @page {
    margin: 0.5in;
    size: A4;
  }
  
  /* Typography for print */
  body {
    font-family: "Times New Roman", Times, serif;
    font-size: 12pt;
    line-height: 1.4;
  }
  
  h1, h2, h3, h4, h5, h6 {
    font-family: Arial, sans-serif;
    page-break-after: avoid;
    break-after: avoid;
  }
  
  h1 { font-size: 18pt; }
  h2 { font-size: 16pt; }
  h3 { font-size: 14pt; }
  h4, h5, h6 { font-size: 12pt; }
  
  /* Hide non-essential elements */
  .no-print,
  .wp-block-navigation,
  .wp-block-search,
  .wp-block-button,
  .newsletter-form,
  .contact-form,
  .wp-block-template-part[data-area="header"],
  .wp-block-template-part[data-area="footer"],
  .wp-block-template-part[data-area="sidebar"] {
    display: none !important;
  }
  
  /* Content optimization */
  .wp-block-post-content,
  .wp-block-group,
  .entry-content {
    break-inside: avoid;
  }
  
  /* Links */
  a {
    text-decoration: underline;
    color: black !important;
  }
  
  a[href^="http"]:after {
    content: " (" attr(href) ")";
    font-size: 10pt;
    font-style: italic;
  }
  
  /* Images */
  img {
    max-width: 100% !important;
    height: auto !important;
    break-inside: avoid;
  }
  
  /* Tables */
  table {
    border-collapse: collapse;
    width: 100%;
  }
  
  th, td {
    border: 1px solid #ddd;
    padding: 8pt;
    text-align: left;
  }
  
  th {
    background: #f5f5f5 !important;
    font-weight: bold;
  }
  
  /* Page breaks */
  .page-break-before {
    page-break-before: always;
    break-before: page;
  }
  
  .page-break-after {
    page-break-after: always;
    break-after: page;
  }
  
  /* Post meta */
  .post-meta {
    font-size: 10pt;
    color: #666 !important;
    margin-bottom: 1em;
  }
  
  /* Blockquotes */
  blockquote {
    border-left: 3pt solid #ccc;
    padding-left: 1em;
    margin: 1em 0;
    font-style: italic;
  }
  
  /* Code blocks */
  pre, code {
    font-family: "Courier New", Courier, monospace;
    font-size: 10pt;
    border: 1pt solid #ccc;
    background: #f9f9f9 !important;
    padding: 0.5em;
  }
}
```

## 7. Update Functions.php

### Add to: `functions.php`
```php
// CSS Architecture Support
require_once GPRESS_INC_DIR . '/css-architecture.php';

/**
 * Enable Editor Styles
 */
function gpress_editor_styles_support() {
    // Add editor styles
    add_theme_support('editor-styles');
    
    // Enqueue editor styles
    add_editor_style('assets/css/editor-style.min.css');
}
add_action('after_setup_theme', 'gpress_editor_styles_support');
```

## 8. Create Build Configuration

### File: `build/postcss.config.js`
```javascript
module.exports = {
  plugins: [
    require('autoprefixer'),
    require('cssnano')({
      preset: ['default', {
        discardComments: {
          removeAll: true,
        },
        normalizeWhitespace: true,
        mergeLonghand: true,
        mergeRules: true,
      }],
    }),
  ],
}
```

### File: `build/package.json`
```json
{
  "name": "gpress-theme-build",
  "version": "1.0.0",
  "description": "Build tools for GPress theme",
  "scripts": {
    "build:css-components": "postcss ../assets/css/components.css -o ../assets/css/components.min.css",
    "build:css-responsive": "postcss ../assets/css/responsive.css -o ../assets/css/responsive.min.css",
    "build:css-editor": "postcss ../assets/css/editor-style.css -o ../assets/css/editor-style.min.css",
    "build:all-css": "npm run build:css-components && npm run build:css-responsive && npm run build:css-editor",
    "watch:css": "npm run build:css-components -- --watch & npm run build:css-responsive -- --watch & npm run build:css-editor -- --watch",
    "dev": "npm run watch:css"
  },
  "devDependencies": {
    "autoprefixer": "^10.4.16",
    "cssnano": "^6.0.1",
    "postcss": "^8.4.31",
    "postcss-cli": "^10.1.0"
  }
}
```

## Testing Instructions

### 1. CSS Architecture Test
```bash
# Test CSS loading and organization
1. Activate GPress theme
2. Check that base styles load on all pages
3. Verify component styles only load on relevant pages
4. Test responsive styles on different screen sizes
5. Check print styles by using print preview
```

### 2. Conditional Loading Test
```bash
# Test conditional CSS module loading
1. Visit homepage - should load base styles only
2. Visit single post - should load components + responsive
3. Visit category page - should load all modules
4. Check Network tab to verify conditional loading
```

### 3. Editor Styles Test
```bash
# Test block editor styling
1. Go to WordPress Admin → Posts → Add New
2. Add various blocks (buttons, images, quotes)
3. Verify editor matches frontend appearance
4. Test responsive preview in editor
```

### 4. Performance Test
```bash
# Test CSS performance optimizations
1. Run Lighthouse audit
2. Check for render-blocking CSS
3. Verify critical CSS is inlined
4. Test loading times with slow 3G
```

### 5. Cross-browser Test
```bash
# Test CSS compatibility
1. Test in Chrome, Firefox, Safari, Edge
2. Verify CSS custom properties work
3. Check fallbacks for older browsers
4. Test responsive design across browsers
```

### 6. Build Process Test
```bash
# Test CSS build tools
cd build/
npm install
npm run build:all-css
# Verify minified CSS files are created
```

## Expected Results

After completing this step, you should have:

- ✅ Comprehensive CSS architecture with conditional loading
- ✅ Component-based styling system
- ✅ Advanced responsive design implementation
- ✅ Editor styles that match frontend appearance
- ✅ Critical CSS for performance optimization
- ✅ Print-optimized styles
- ✅ Build process for CSS optimization
- ✅ Performance-optimized asset loading

The theme should now have a professional, maintainable CSS architecture that loads efficiently and provides consistent styling across all components and templates.

## Next Steps

In Step 8, we'll implement advanced performance optimization techniques including resource loading, caching strategies, and Core Web Vitals improvements.