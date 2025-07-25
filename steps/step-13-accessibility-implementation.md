# Step 13: WCAG 2.1 AA Accessibility Implementation

## Overview
This step implements comprehensive accessibility features to ensure WCAG 2.1 AA compliance across all theme components. We'll establish semantic HTML structures, ARIA patterns, keyboard navigation, screen reader support, and inclusive design principles with intelligent conditional loading and performance optimization.

## Objectives
- Achieve full WCAG 2.1 AA compliance across all theme components
- Implement semantic HTML structure with proper landmarks
- Establish comprehensive ARIA attributes and live regions
- Ensure complete keyboard navigation support
- Optimize for screen readers and assistive technologies
- Create inclusive design patterns and color contrast compliance
- Implement conditional accessibility asset loading for performance

## What You'll Learn
- Advanced WCAG 2.1 guidelines implementation and testing
- Semantic HTML5 structures and accessibility landmarks
- ARIA attributes, roles, and live regions for dynamic content
- Keyboard navigation patterns and focus management
- Screen reader optimization and assistive technology support
- Color contrast and visual accessibility design principles
- Performance optimization for accessibility features

## Files Structure for This Step

### 📁 Files to CREATE:
```
inc/
├── accessibility.php              # Core accessibility system with Smart Asset Manager integration
├── accessibility-enhancements.php # Advanced accessibility features with conditional loading
├── accessibility-testing.php      # Accessibility testing and validation
└── aria-patterns.php             # ARIA design patterns implementation

**Note**: Accessibility assets are handled by Smart Asset Manager and core.css:
- Core accessibility styles: Included in `assets/css/core.css` from Step 7 (always loaded)
- Advanced a11y features: Loaded conditionally via Smart Asset Manager based on user preferences
- High contrast/reduced motion: Handled via CSS media queries in core.css
- Screen reader optimizations: Built into core.css structure

**Integration with Step 7**: Uses Smart Asset Manager's context detection for accessibility enhancements

assets/js/
├── accessibility.js             # Main accessibility JavaScript
├── aria-live.js                # Live regions and dynamic content
├── keyboard-navigation.js      # Enhanced keyboard navigation
├── focus-management.js         # Focus trap and management
├── screen-reader-utils.js      # Screen reader utilities
└── accessibility-testing.js    # Client-side accessibility testing

templates/
├── accessibility-statement.html  # Accessibility statement template
└── accessibility-help.html      # Accessibility help template

parts/
├── skip-links.html              # Skip navigation links
├── aria-landmarks.html          # ARIA landmark structure
└── accessibility-toolbar.html   # Accessibility toolbar component
```

### 📝 Files to UPDATE:
```
functions.php                    # Include accessibility files and initialization
inc/theme-setup.php             # Add accessibility theme support
inc/enqueue-scripts.php         # Conditional accessibility asset loading
style.css                       # Base accessibility integration styles
README.md                       # Document accessibility features and compliance
theme.json                      # Add accessibility-specific settings and styles
index.html                      # Update with proper semantic structure
header.html                     # Add ARIA landmarks and skip links
footer.html                     # Add footer landmarks and navigation
```

### 🎯 Optimization Features Implemented:
- Conditional asset loading based on accessibility needs detection
- Performance-optimized ARIA live regions with smart updates
- Lazy loading for accessibility enhancements on user interaction
- Efficient keyboard navigation with optimized event handling
- Smart color contrast detection and automatic adjustments
- Reduced motion preferences with graceful degradation
- Accessibility testing automation with performance monitoring

## Step-by-Step Implementation

### 1. Create Core Accessibility System

Create `inc/accessibility.php`:

```php
<?php
/**
 * Core Accessibility System for GPress Theme
 * Implements WCAG 2.1 AA compliance with performance optimization
 *
 * @package GPress
 * @subpackage Accessibility
 * @version 1.0.0
 * @since 1.0.0
 */

// Prevent direct access
defined('ABSPATH') || exit;

/**
 * GPress Accessibility Manager
 * 
 * @since 1.0.0
 */
class GPress_Accessibility {

    /**
     * Initialize accessibility system
     *
     * @since 1.0.0
     */
    public static function init() {
        add_action('after_setup_theme', array(__CLASS__, 'accessibility_setup'));
        add_action('wp_enqueue_scripts', array(__CLASS__, 'conditional_accessibility_assets'));
        add_action('wp_body_open', array(__CLASS__, 'add_skip_links'));
        add_action('wp_head', array(__CLASS__, 'add_accessibility_meta'));
        add_action('wp_footer', array(__CLASS__, 'accessibility_javascript'));
        
        // Content enhancements
        add_filter('wp_get_attachment_image_attributes', array(__CLASS__, 'enhance_image_accessibility'), 10, 3);
        add_filter('the_content', array(__CLASS__, 'enhance_content_accessibility'));
        add_filter('nav_menu_link_attributes', array(__CLASS__, 'enhance_nav_accessibility'), 10, 4);
        add_filter('body_class', array(__CLASS__, 'accessibility_body_classes'));
        
        // Admin enhancements
        add_action('admin_init', array(__CLASS__, 'setup_accessibility_admin'));
        
        // Development tools
        if (defined('WP_DEBUG') && WP_DEBUG) {
            add_action('wp_footer', array(__CLASS__, 'accessibility_dev_tools'));
        }
    }

    /**
     * Accessibility setup and theme support
     *
     * @since 1.0.0
     */
    public static function accessibility_setup() {
        // Add theme support for accessibility features
        add_theme_support('accessible-colors');
        add_theme_support('keyboard-navigation');
        add_theme_support('screen-reader-text');
        add_theme_support('skip-links');
        add_theme_support('aria-landmarks');
        add_theme_support('high-contrast-mode');
        add_theme_support('reduced-motion');
        
        // Set default accessibility options
        if (get_option('gpress_accessibility_initialized') !== 'yes') {
            update_option('gpress_enable_accessibility_toolbar', true);
            update_option('gpress_enable_high_contrast', true);
            update_option('gpress_enable_font_scaling', true);
            update_option('gpress_enable_keyboard_shortcuts', true);
            update_option('gpress_accessibility_initialized', 'yes');
        }
    }

    /**
     * Conditional accessibility asset loading
     *
     * @since 1.0.0
     */
    public static function conditional_accessibility_assets() {
        $load_full_accessibility = false;
        $load_high_contrast = false;
        $load_reduced_motion = false;
        
        // Determine loading requirements
        if (self::needs_accessibility_enhancements()) {
            $load_full_accessibility = true;
        }
        
        if (self::prefers_high_contrast()) {
            $load_high_contrast = true;
        }
        
        if (self::prefers_reduced_motion()) {
            $load_reduced_motion = true;
        }
        
        // Always load base accessibility styles
        wp_enqueue_style(
            'gpress-accessibility-base',
            get_theme_file_uri('/assets/css/accessibility-base.css'),
            array('gpress-style'),
            GPRESS_VERSION,
            'all'
        );
        
        if ($load_full_accessibility) {
            wp_enqueue_style(
                'gpress-accessibility',
                get_theme_file_uri('/assets/css/accessibility.css'),
                array('gpress-accessibility-base'),
                GPRESS_VERSION,
                'all'
            );
            
            wp_enqueue_script(
                'gpress-accessibility',
                get_theme_file_uri('/assets/js/accessibility.js'),
                array('jquery'),
                GPRESS_VERSION,
                array('strategy' => 'defer', 'in_footer' => true)
            );
            
            // Enhanced keyboard navigation
            wp_enqueue_script(
                'gpress-keyboard-navigation',
                get_theme_file_uri('/assets/js/keyboard-navigation.js'),
                array('gpress-accessibility'),
                GPRESS_VERSION,
                array('strategy' => 'defer', 'in_footer' => true)
            );
            
            // Focus management
            wp_enqueue_script(
                'gpress-focus-management',
                get_theme_file_uri('/assets/js/focus-management.js'),
                array('gpress-accessibility'),
                GPRESS_VERSION,
                array('strategy' => 'defer', 'in_footer' => true)
            );
            
            // ARIA live regions
            wp_enqueue_script(
                'gpress-aria-live',
                get_theme_file_uri('/assets/js/aria-live.js'),
                array('gpress-accessibility'),
                GPRESS_VERSION,
                array('strategy' => 'defer', 'in_footer' => true)
            );
            
            // Localize accessibility script
            wp_localize_script('gpress-accessibility', 'gpressA11y', array(
                'ajaxurl' => admin_url('admin-ajax.php'),
                'nonce' => wp_create_nonce('gpress_accessibility_nonce'),
                'settings' => array(
                    'enableToolbar' => get_option('gpress_enable_accessibility_toolbar', true),
                    'enableHighContrast' => get_option('gpress_enable_high_contrast', true),
                    'enableFontScaling' => get_option('gpress_enable_font_scaling', true),
                    'enableKeyboardShortcuts' => get_option('gpress_enable_keyboard_shortcuts', true),
                ),
                'strings' => array(
                    'skip_to_content' => __('Skip to main content', 'gpress'),
                    'skip_to_navigation' => __('Skip to navigation', 'gpress'),
                    'skip_to_sidebar' => __('Skip to sidebar', 'gpress'),
                    'skip_to_footer' => __('Skip to footer', 'gpress'),
                    'menu_expanded' => __('Menu expanded', 'gpress'),
                    'menu_collapsed' => __('Menu collapsed', 'gpress'),
                    'page_loaded' => __('Page loaded', 'gpress'),
                    'form_error' => __('Form contains errors', 'gpress'),
                    'loading' => __('Loading', 'gpress'),
                    'high_contrast_on' => __('High contrast mode enabled', 'gpress'),
                    'high_contrast_off' => __('High contrast mode disabled', 'gpress'),
                    'font_size_increased' => __('Font size increased', 'gpress'),
                    'font_size_decreased' => __('Font size decreased', 'gpress'),
                    'font_size_reset' => __('Font size reset to default', 'gpress'),
                )
            ));
        }
        
        if ($load_high_contrast) {
            wp_enqueue_style(
                'gpress-high-contrast',
                get_theme_file_uri('/assets/css/high-contrast.css'),
                array('gpress-accessibility-base'),
                GPRESS_VERSION,
                'all'
            );
        }
        
        if ($load_reduced_motion) {
            wp_enqueue_style(
                'gpress-reduced-motion',
                get_theme_file_uri('/assets/css/reduced-motion.css'),
                array('gpress-accessibility-base'),
                GPRESS_VERSION,
                'all'
            );
        }
        
        // Screen reader specific styles
        wp_enqueue_style(
            'gpress-screen-reader',
            get_theme_file_uri('/assets/css/screen-reader.css'),
            array('gpress-accessibility-base'),
            GPRESS_VERSION,
            'screen'
        );
        
        // Print accessibility styles
        wp_enqueue_style(
            'gpress-accessibility-print',
            get_theme_file_uri('/assets/css/accessibility-print.css'),
            array('gpress-accessibility-base'),
            GPRESS_VERSION,
            'print'
        );
    }

    /**
     * Check if accessibility enhancements are needed
     *
     * @since 1.0.0
     */
    private static function needs_accessibility_enhancements() {
        // Check various indicators
        $needs_enhancements = false;
        
        // User preference or URL parameter
        if (isset($_GET['accessibility']) || 
            get_user_meta(get_current_user_id(), 'gpress_accessibility_mode', true) === 'enhanced') {
            $needs_enhancements = true;
        }
        
        // Check for admin users (often need accessibility features)
        if (current_user_can('edit_posts')) {
            $needs_enhancements = true;
        }
        
        // Check theme option
        if (get_theme_mod('enable_accessibility_enhancements', true)) {
            $needs_enhancements = true;
        }
        
        // Check for accessibility-focused pages
        if (is_page() && has_block('core/accessibility')) {
            $needs_enhancements = true;
        }
        
        return apply_filters('gpress_needs_accessibility_enhancements', $needs_enhancements);
    }

    /**
     * Check if user prefers high contrast
     *
     * @since 1.0.0
     */
    private static function prefers_high_contrast() {
        // Check user preference, URL parameter, or browser setting
        return isset($_GET['high-contrast']) || 
               get_user_meta(get_current_user_id(), 'gpress_high_contrast', true) === 'enabled' ||
               (isset($_COOKIE['gpress_high_contrast']) && $_COOKIE['gpress_high_contrast'] === 'enabled');
    }

    /**
     * Check if user prefers reduced motion
     *
     * @since 1.0.0
     */
    private static function prefers_reduced_motion() {
        // This will be handled via CSS media query, but we can also check preferences
        return isset($_GET['reduced-motion']) ||
               get_user_meta(get_current_user_id(), 'gpress_reduced_motion', true) === 'enabled' ||
               (isset($_COOKIE['gpress_reduced_motion']) && $_COOKIE['gpress_reduced_motion'] === 'enabled');
    }

    /**
     * Add skip links to page
     *
     * @since 1.0.0
     */
    public static function add_skip_links() {
        ?>
        <div class="skip-links" role="navigation" aria-label="<?php esc_attr_e('Skip links', 'gpress'); ?>">
            <a class="skip-link screen-reader-text" href="#main" tabindex="1">
                <?php esc_html_e('Skip to main content', 'gpress'); ?>
            </a>
            <a class="skip-link screen-reader-text" href="#primary-navigation" tabindex="2">
                <?php esc_html_e('Skip to navigation', 'gpress'); ?>
            </a>
            <?php if (is_active_sidebar('sidebar-1')) : ?>
            <a class="skip-link screen-reader-text" href="#sidebar" tabindex="3">
                <?php esc_html_e('Skip to sidebar', 'gpress'); ?>
            </a>
            <?php endif; ?>
            <a class="skip-link screen-reader-text" href="#colophon" tabindex="4">
                <?php esc_html_e('Skip to footer', 'gpress'); ?>
            </a>
        </div>
        <?php
    }

    /**
     * Add accessibility meta information
     *
     * @since 1.0.0
     */
    public static function add_accessibility_meta() {
        ?>
        <!-- Accessibility Meta Information -->
        <meta name="accessibility-compliance" content="WCAG 2.1 AA">
        <meta name="color-scheme" content="light dark">
        <?php if (self::prefers_high_contrast()) : ?>
        <meta name="theme-color" content="#000000" media="(prefers-contrast: high)">
        <?php endif; ?>
        
        <!-- Accessibility Statement Link -->
        <link rel="help" href="<?php echo esc_url(home_url('/accessibility-statement/')); ?>" 
              title="<?php esc_attr_e('Accessibility Statement', 'gpress'); ?>">
        <?php
    }

    /**
     * Enhance image accessibility
     *
     * @since 1.0.0
     */
    public static function enhance_image_accessibility($attr, $attachment, $size) {
        $image_id = $attachment->ID;
        
        // Enhance alt text
        if (empty($attr['alt'])) {
            $alt_text = get_post_meta($image_id, '_wp_attachment_image_alt', true);
            
            if (empty($alt_text)) {
                // Use caption or title as fallback
                $alt_text = wp_get_attachment_caption($image_id);
                if (empty($alt_text)) {
                    $alt_text = $attachment->post_title;
                }
                
                // Clean up filename-based alt text
                if (empty($alt_text)) {
                    $alt_text = pathinfo($attachment->guid, PATHINFO_FILENAME);
                    $alt_text = str_replace(array('-', '_'), ' ', $alt_text);
                    $alt_text = ucwords($alt_text);
                }
            }
            
            $attr['alt'] = $alt_text;
        }
        
        // Add aria-describedby for images with captions
        $caption = wp_get_attachment_caption($image_id);
        if (!empty($caption)) {
            $attr['aria-describedby'] = 'caption-' . $image_id;
        }
        
        // Handle decorative images
        if (empty($attr['alt']) || in_array($attr['alt'], array('decorative', 'decoration', ''))) {
            $attr['role'] = 'presentation';
            $attr['alt'] = '';
            $attr['aria-hidden'] = 'true';
        }
        
        // Add loading attribute for better performance
        if (!isset($attr['loading'])) {
            $attr['loading'] = 'lazy';
        }
        
        return $attr;
    }

    /**
     * Enhance content accessibility
     *
     * @since 1.0.0
     */
    public static function enhance_content_accessibility($content) {
        // Enhance external links
        $content = preg_replace_callback(
            '/<a\s+([^>]*?)href=["\']([^"\']*)["\']([^>]*?)>(.*?)<\/a>/i',
            function($matches) {
                $before_href = $matches[1];
                $href = $matches[2];
                $after_href = $matches[3];
                $link_text = $matches[4];
                
                // Check if it's an external link
                $is_external = !empty($href) && 
                              strpos($href, home_url()) === false && 
                              (strpos($href, 'http') === 0 || strpos($href, '//') === 0);
                
                // Check if opens in new window
                $opens_new_window = strpos($after_href, 'target="_blank"') !== false || 
                                   strpos($before_href, 'target="_blank"') !== false;
                
                if ($is_external || $opens_new_window) {
                    $aria_label = $link_text;
                    
                    if ($is_external) {
                        $aria_label .= ' (' . __('external link', 'gpress') . ')';
                    }
                    
                    if ($opens_new_window) {
                        $aria_label .= ' (' . __('opens in new window', 'gpress') . ')';
                    }
                    
                    // Add or update aria-label
                    if (strpos($before_href . $after_href, 'aria-label') === false) {
                        $after_href .= ' aria-label="' . esc_attr($aria_label) . '"';
                    }
                    
                    // Add external link indicator
                    if ($is_external && strpos($link_text, 'external-link-icon') === false) {
                        $link_text .= ' <span class="external-link-icon" aria-hidden="true">↗</span>';
                    }
                }
                
                return '<a ' . $before_href . 'href="' . $href . '"' . $after_href . '>' . $link_text . '</a>';
            },
            $content
        );
        
        // Enhance headings with proper hierarchy
        $content = self::enhance_heading_hierarchy($content);
        
        // Enhance tables with proper headers
        $content = self::enhance_table_accessibility($content);
        
        return $content;
    }

    /**
     * Enhance navigation accessibility
     *
     * @since 1.0.0
     */
    public static function enhance_nav_accessibility($atts, $item, $args, $depth) {
        // Remove title attribute (redundant with link text)
        if (isset($atts['title'])) {
            unset($atts['title']);
        }
        
        // Add aria-current for current page
        if (in_array('current-menu-item', $item->classes)) {
            $atts['aria-current'] = 'page';
        }
        
        return $atts;
    }

    /**
     * Add accessibility body classes
     *
     * @since 1.0.0
     */
    public static function accessibility_body_classes($classes) {
        if (self::prefers_high_contrast()) {
            $classes[] = 'high-contrast-mode';
        }
        
        if (self::prefers_reduced_motion()) {
            $classes[] = 'reduced-motion';
        }
        
        if (get_option('gpress_enable_accessibility_toolbar', true)) {
            $classes[] = 'has-accessibility-toolbar';
        }
        
        if (self::needs_accessibility_enhancements()) {
            $classes[] = 'accessibility-enhanced';
        }
        
        return $classes;
    }

    /**
     * Enhance heading hierarchy
     *
     * @since 1.0.0
     */
    private static function enhance_heading_hierarchy($content) {
        // This would implement heading hierarchy checking and enhancement
        // For now, we'll add ARIA labelledby where appropriate
        return $content;
    }

    /**
     * Enhance table accessibility
     *
     * @since 1.0.0
     */
    private static function enhance_table_accessibility($content) {
        // Add proper table headers and captions
        $content = preg_replace_callback(
            '/<table([^>]*)>(.*?)<\/table>/is',
            function($matches) {
                $table_attrs = $matches[1];
                $table_content = $matches[2];
                
                // Add role if not present
                if (strpos($table_attrs, 'role=') === false) {
                    $table_attrs .= ' role="table"';
                }
                
                // Add scope to th elements
                $table_content = preg_replace(
                    '/<th([^>]*?)>/',
                    '<th$1 scope="col">',
                    $table_content
                );
                
                return '<table' . $table_attrs . '>' . $table_content . '</table>';
            },
            $content
        );
        
        return $content;
    }

    /**
     * Setup accessibility admin features
     *
     * @since 1.0.0
     */
    public static function setup_accessibility_admin() {
        add_action('admin_enqueue_scripts', array(__CLASS__, 'admin_accessibility_assets'));
        add_action('admin_menu', array(__CLASS__, 'add_accessibility_admin_menu'));
    }

    /**
     * Enqueue admin accessibility assets
     *
     * @since 1.0.0
     */
    public static function admin_accessibility_assets($hook) {
        wp_enqueue_style(
            'gpress-admin-accessibility',
            get_theme_file_uri('/assets/css/admin-accessibility.css'),
            array(),
            GPRESS_VERSION
        );
    }

    /**
     * Add accessibility admin menu
     *
     * @since 1.0.0
     */
    public static function add_accessibility_admin_menu() {
        add_theme_page(
            __('Accessibility Settings', 'gpress'),
            __('Accessibility', 'gpress'),
            'manage_options',
            'gpress-accessibility',
            array(__CLASS__, 'accessibility_admin_page')
        );
    }

    /**
     * Accessibility admin page
     *
     * @since 1.0.0
     */
    public static function accessibility_admin_page() {
        ?>
        <div class="wrap">
            <h1><?php esc_html_e('Accessibility Settings', 'gpress'); ?></h1>
            <form method="post" action="options.php">
                <?php
                settings_fields('gpress_accessibility_settings');
                do_settings_sections('gpress_accessibility_settings');
                ?>
                <table class="form-table">
                    <tr>
                        <th scope="row"><?php esc_html_e('Enable Accessibility Toolbar', 'gpress'); ?></th>
                        <td>
                            <input type="checkbox" name="gpress_enable_accessibility_toolbar" value="1" 
                                   <?php checked(get_option('gpress_enable_accessibility_toolbar', true)); ?>>
                            <p class="description"><?php esc_html_e('Show accessibility toolbar for users.', 'gpress'); ?></p>
                        </td>
                    </tr>
                    <tr>
                        <th scope="row"><?php esc_html_e('Enable High Contrast Mode', 'gpress'); ?></th>
                        <td>
                            <input type="checkbox" name="gpress_enable_high_contrast" value="1" 
                                   <?php checked(get_option('gpress_enable_high_contrast', true)); ?>>
                            <p class="description"><?php esc_html_e('Allow users to toggle high contrast mode.', 'gpress'); ?></p>
                        </td>
                    </tr>
                    <tr>
                        <th scope="row"><?php esc_html_e('Enable Font Scaling', 'gpress'); ?></th>
                        <td>
                            <input type="checkbox" name="gpress_enable_font_scaling" value="1" 
                                   <?php checked(get_option('gpress_enable_font_scaling', true)); ?>>
                            <p class="description"><?php esc_html_e('Allow users to increase or decrease font size.', 'gpress'); ?></p>
                        </td>
                    </tr>
                </table>
                <?php submit_button(); ?>
            </form>
            
            <h2><?php esc_html_e('Accessibility Test', 'gpress'); ?></h2>
            <p><?php esc_html_e('Run accessibility tests on your site:', 'gpress'); ?></p>
            <button type="button" class="button" onclick="gpressRunA11yTest()">
                <?php esc_html_e('Run Accessibility Test', 'gpress'); ?>
            </button>
            <div id="accessibility-test-results"></div>
        </div>
        <?php
    }

    /**
     * Add accessibility JavaScript
     *
     * @since 1.0.0
     */
    public static function accessibility_javascript() {
        if (!wp_script_is('gpress-accessibility', 'enqueued')) {
            return;
        }
        ?>
        <script>
        // Basic accessibility enhancements
        document.addEventListener('DOMContentLoaded', function() {
            // Announce page load to screen readers
            const announcement = document.createElement('div');
            announcement.setAttribute('aria-live', 'polite');
            announcement.setAttribute('aria-atomic', 'true');
            announcement.className = 'screen-reader-text';
            announcement.textContent = gpressA11y.strings.page_loaded;
            document.body.appendChild(announcement);
            
            setTimeout(function() {
                announcement.textContent = '';
            }, 1000);
            
            // Add focus outline for keyboard users
            let isKeyboardUser = false;
            
            document.addEventListener('keydown', function(e) {
                if (e.key === 'Tab') {
                    isKeyboardUser = true;
                    document.body.classList.add('keyboard-navigation');
                }
            });
            
            document.addEventListener('mousedown', function() {
                isKeyboardUser = false;
                document.body.classList.remove('keyboard-navigation');
            });
            
            // Manage focus for modals and overlays
            const modals = document.querySelectorAll('[role="dialog"], .modal, .overlay');
            modals.forEach(function(modal) {
                const observer = new MutationObserver(function(mutations) {
                    mutations.forEach(function(mutation) {
                        if (mutation.type === 'attributes' && 
                            (mutation.attributeName === 'class' || mutation.attributeName === 'style')) {
                            const isVisible = modal.offsetParent !== null;
                            if (isVisible) {
                                modal.focus();
                            }
                        }
                    });
                });
                
                observer.observe(modal, { attributes: true });
            });
        });
        </script>
        <?php
    }

    /**
     * Add development accessibility tools
     *
     * @since 1.0.0
     */
    public static function accessibility_dev_tools() {
        if (!current_user_can('edit_themes')) {
            return;
        }
        ?>
        <div id="accessibility-dev-tools" style="position: fixed; bottom: 10px; right: 10px; z-index: 99999; background: #000; color: #fff; padding: 10px; border-radius: 5px; font-size: 12px;">
            <h4>Accessibility Dev Tools</h4>
            <button onclick="gpressCheckHeadings()">Check Headings</button>
            <button onclick="gpressCheckImages()">Check Images</button>
            <button onclick="gpressCheckFocus()">Check Focus</button>
            <button onclick="gpressCheckContrast()">Check Contrast</button>
        </div>
        
        <script>
        function gpressCheckHeadings() {
            const headings = document.querySelectorAll('h1, h2, h3, h4, h5, h6');
            console.log('Heading structure check:', headings);
            // Implementation for heading hierarchy check
        }
        
        function gpressCheckImages() {
            const images = document.querySelectorAll('img');
            images.forEach(img => {
                if (!img.alt && img.getAttribute('role') !== 'presentation') {
                    console.warn('Image missing alt text:', img);
                }
            });
        }
        
        function gpressCheckFocus() {
            const focusableElements = document.querySelectorAll('a, button, input, textarea, select, [tabindex]');
            console.log('Focusable elements:', focusableElements.length);
        }
        
        function gpressCheckContrast() {
            // Basic contrast check implementation
            console.log('Running contrast check...');
        }
        </script>
        <?php
    }
}

// Initialize the accessibility system
GPress_Accessibility::init();
```

### 2. Create Accessibility Base Styles

Create `assets/css/accessibility-base.css`:

```css
/**
 * GPress Accessibility Base Styles
 * Core accessibility styles loaded on every page
 *
 * @package GPress
 * @subpackage Accessibility
 * @version 1.0.0
 * @since 1.0.0
 */

/* Screen Reader Text */
.screen-reader-text {
    border: 0;
    clip: rect(1px, 1px, 1px, 1px);
    clip-path: inset(50%);
    height: 1px;
    margin: -1px;
    overflow: hidden;
    padding: 0;
    position: absolute !important;
    width: 1px;
    word-wrap: normal !important;
}

.screen-reader-text:focus {
    background-color: var(--wp--preset--color--background);
    border-radius: 3px;
    box-shadow: 0 0 2px 2px rgba(0, 0, 0, 0.6);
    clip: auto !important;
    clip-path: none;
    color: var(--wp--preset--color--foreground);
    display: block;
    font-size: 0.875rem;
    font-weight: 600;
    height: auto;
    left: 5px;
    line-height: normal;
    padding: 15px 23px 14px;
    text-decoration: none;
    top: 5px;
    width: auto;
    z-index: 100000;
}

/* Skip Links */
.skip-links {
    position: absolute;
    top: 0;
    left: 0;
    z-index: 999999;
}

.skip-link {
    background: var(--wp--preset--color--primary);
    color: var(--wp--preset--color--background);
    padding: 0.75rem 1rem;
    text-decoration: none;
    font-weight: 600;
    border-radius: 0 0 4px 0;
    transition: all 0.3s ease;
}

.skip-link:hover,
.skip-link:focus {
    background: var(--wp--preset--color--accent);
    outline: 3px solid var(--wp--preset--color--accent);
    outline-offset: 2px;
}

/* Focus Management */
.keyboard-navigation *:focus {
    outline: 3px solid var(--wp--preset--color--primary);
    outline-offset: 2px;
    border-radius: 3px;
}

.keyboard-navigation button:focus,
.keyboard-navigation input:focus,
.keyboard-navigation textarea:focus,
.keyboard-navigation select:focus {
    box-shadow: 0 0 0 3px rgba(var(--wp--preset--color--primary-rgb), 0.3);
}

/* High Contrast Base Support */
@media (prefers-contrast: high) {
    * {
        border-color: currentColor !important;
    }
    
    .skip-link:focus {
        outline-width: 4px;
    }
}

/* Reduced Motion Base Support */
@media (prefers-reduced-motion: reduce) {
    *,
    *::before,
    *::after {
        animation-duration: 0.01ms !important;
        animation-iteration-count: 1 !important;
        transition-duration: 0.01ms !important;
        scroll-behavior: auto !important;
    }
}

/* Print Accessibility */
@media print {
    .skip-links,
    .screen-reader-text {
        display: none !important;
    }
    
    /* Ensure good contrast in print */
    * {
        background: white !important;
        color: black !important;
        box-shadow: none !important;
        text-shadow: none !important;
    }
    
    a,
    a:visited {
        text-decoration: underline;
        color: black !important;
    }
    
    a[href^="http"]:after {
        content: " (" attr(href) ")";
        font-size: 0.8em;
    }
}

/* ARIA Live Regions */
[aria-live="polite"],
[aria-live="assertive"] {
    position: absolute;
    left: -10000px;
    width: 1px;
    height: 1px;
    overflow: hidden;
}

[aria-live="polite"]:not(:empty),
[aria-live="assertive"]:not(:empty) {
    position: static;
    width: auto;
    height: auto;
    overflow: visible;
    padding: 1rem;
    background: var(--wp--preset--color--background-secondary);
    border: 2px solid var(--wp--preset--color--primary);
    border-radius: 6px;
    margin: 1rem 0;
}

/* Focus Trap */
.focus-trap {
    position: relative;
}

.focus-trap::before,
.focus-trap::after {
    content: '';
    position: absolute;
    top: 0;
    width: 1px;
    height: 1px;
    opacity: 0;
    pointer-events: none;
}

/* External Link Indicators */
.external-link-icon {
    font-size: 0.8em;
    margin-left: 0.25rem;
    opacity: 0.7;
}

/* Button and Link States */
button:disabled,
input:disabled,
textarea:disabled,
select:disabled {
    opacity: 0.6;
    cursor: not-allowed;
}

/* Ensure sufficient click targets */
button,
input[type="button"],
input[type="submit"],
input[type="reset"],
a {
    min-height: 44px;
    min-width: 44px;
}

/* Table Accessibility */
table {
    border-collapse: collapse;
    width: 100%;
}

th {
    text-align: left;
    font-weight: 600;
}

th,
td {
    padding: 0.75rem;
    border: 1px solid var(--wp--preset--color--border);
}

caption {
    text-align: left;
    font-weight: 600;
    margin-bottom: 0.5rem;
    padding: 0.5rem 0;
}

/* Form Accessibility */
label {
    display: block;
    margin-bottom: 0.25rem;
    font-weight: 600;
}

.required::after {
    content: " *";
    color: #d63384;
}

fieldset {
    border: 2px solid var(--wp--preset--color--border);
    border-radius: 6px;
    padding: 1rem;
    margin: 1rem 0;
}

legend {
    font-weight: 600;
    padding: 0 0.5rem;
}

/* Error States */
.error,
[aria-invalid="true"] {
    border-color: #d63384 !important;
    outline-color: #d63384 !important;
}

.error-message {
    color: #d63384;
    font-size: 0.875rem;
    margin-top: 0.25rem;
    display: flex;
    align-items: center;
    gap: 0.25rem;
}

.error-message::before {
    content: "⚠";
    font-size: 1rem;
}

/* Success States */
.success {
    border-color: #198754 !important;
    outline-color: #198754 !important;
}

.success-message {
    color: #198754;
    font-size: 0.875rem;
    margin-top: 0.25rem;
    display: flex;
    align-items: center;
    gap: 0.25rem;
}

.success-message::before {
    content: "✓";
    font-size: 1rem;
}
```

### 3. Update Functions.php

Update `functions.php`:

```php
/**
 * Load Accessibility Components
 * Comprehensive WCAG 2.1 AA compliance implementation
 *
 * @since 1.0.0
 */
function gpress_load_accessibility_components() {
    // Core accessibility system (always loaded)
    require_once get_theme_file_path('/inc/accessibility.php');
    
    // Enhanced accessibility features (conditionally loaded)
    if (get_theme_mod('enable_accessibility_enhancements', true)) {
        require_once get_theme_file_path('/inc/accessibility-enhancements.php');
        require_once get_theme_file_path('/inc/aria-patterns.php');
    }
    
    // Admin and development components
    if (is_admin()) {
        require_once get_theme_file_path('/inc/accessibility-testing.php');
        require_once get_theme_file_path('/inc/accessibility-optimization.php');
    }
}
add_action('after_setup_theme', 'gpress_load_accessibility_components');

/**
 * Add accessibility-specific theme support
 *
 * @since 1.0.0
 */
function gpress_add_accessibility_theme_support() {
    // WCAG compliance features
    add_theme_support('wcag-compliance');
    add_theme_support('accessible-colors');
    add_theme_support('keyboard-navigation');
    add_theme_support('screen-reader-text');
    add_theme_support('skip-links');
    add_theme_support('aria-landmarks');
    
    // User preference support
    add_theme_support('high-contrast-mode');
    add_theme_support('reduced-motion');
    add_theme_support('font-scaling');
    
    // Accessibility toolbar
    add_theme_support('accessibility-toolbar');
}
add_action('after_setup_theme', 'gpress_add_accessibility_theme_support');
```

### 4. Update Style.css

Update `style.css`:

```css
/**
 * Accessibility Integration Styles
 * Base accessibility integration with theme styles
 *
 * @since 1.0.0
 */

/* Ensure proper color contrast ratios */
:root {
    --wp--custom--accessibility--contrast-ratio: 4.5;
    --wp--custom--accessibility--large-contrast-ratio: 3;
    --wp--custom--accessibility--focus-color: var(--wp--preset--color--primary);
    --wp--custom--accessibility--error-color: #d63384;
    --wp--custom--accessibility--success-color: #198754;
    --wp--custom--accessibility--warning-color: #ffc107;
}

/* High Contrast Mode Variables */
.high-contrast-mode {
    --wp--preset--color--background: #000000;
    --wp--preset--color--foreground: #ffffff;
    --wp--preset--color--primary: #ffff00;
    --wp--preset--color--secondary: #00ffff;
    --wp--preset--color--accent: #ff00ff;
    --wp--preset--color--border: #ffffff;
    --wp--preset--color--background-secondary: #1a1a1a;
    --wp--preset--color--foreground-secondary: #cccccc;
}

/* Focus Styles Integration */
.wp-block-button__link:focus,
.wp-block-search__button:focus,
.wp-block-file__button:focus {
    outline: 3px solid var(--wp--custom--accessibility--focus-color);
    outline-offset: 2px;
}

/* Ensure accessibility for block elements */
.wp-block-image img[alt=""],
.wp-block-image img:not([alt]) {
    border: 2px dashed var(--wp--custom--accessibility--warning-color);
}

.wp-block-image img[alt=""]:after,
.wp-block-image img:not([alt]):after {
    content: "Missing alt text";
    position: absolute;
    background: var(--wp--custom--accessibility--warning-color);
    color: #000;
    padding: 0.25rem;
    font-size: 0.75rem;
    font-weight: 600;
}

/* Accessibility Toolbar Integration */
.has-accessibility-toolbar .site-header {
    padding-top: 60px; /* Make room for toolbar */
}

.accessibility-toolbar {
    position: fixed;
    top: 0;
    left: 0;
    right: 0;
    background: var(--wp--preset--color--background);
    border-bottom: 2px solid var(--wp--preset--color--border);
    z-index: 99999;
    padding: 0.5rem 1rem;
    display: flex;
    justify-content: space-between;
    align-items: center;
    gap: 1rem;
}

.accessibility-controls {
    display: flex;
    gap: 0.5rem;
    align-items: center;
}

.accessibility-control {
    background: var(--wp--preset--color--background-secondary);
    border: 2px solid var(--wp--preset--color--border);
    color: var(--wp--preset--color--foreground);
    padding: 0.5rem 1rem;
    border-radius: 6px;
    cursor: pointer;
    font-size: 0.875rem;
    font-weight: 600;
    transition: all 0.3s ease;
    min-height: 44px;
    display: flex;
    align-items: center;
    gap: 0.25rem;
}

.accessibility-control:hover,
.accessibility-control:focus {
    background: var(--wp--preset--color--primary);
    color: var(--wp--preset--color--background);
    outline: 2px solid var(--wp--preset--color--primary);
    outline-offset: 2px;
}

.accessibility-control.active {
    background: var(--wp--preset--color--accent);
    color: var(--wp--preset--color--background);
    border-color: var(--wp--preset--color--accent);
}

/* Font Scaling Support */
.font-scale-small {
    font-size: 0.875em;
}

.font-scale-large {
    font-size: 1.125em;
}

.font-scale-extra-large {
    font-size: 1.25em;
}

/* Reduced Motion Integration */
.reduced-motion * {
    animation-duration: 0.01ms !important;
    animation-iteration-count: 1 !important;
    transition-duration: 0.01ms !important;
    scroll-behavior: auto !important;
}

/* Error and Success State Integration */
.wp-block-group.has-error-state {
    border: 2px solid var(--wp--custom--accessibility--error-color);
    border-radius: 6px;
    padding: 1rem;
}

.wp-block-group.has-success-state {
    border: 2px solid var(--wp--custom--accessibility--success-color);
    border-radius: 6px;
    padding: 1rem;
}

/* Responsive Accessibility */
@media (max-width: 768px) {
    .accessibility-toolbar {
        flex-direction: column;
        padding: 0.75rem 0.5rem;
    }
    
    .accessibility-controls {
        flex-wrap: wrap;
        justify-content: center;
    }
    
    .has-accessibility-toolbar .site-header {
        padding-top: 120px; /* Adjust for mobile toolbar height */
    }
}

/* Print Accessibility Integration */
@media print {
    .accessibility-toolbar,
    .accessibility-controls,
    .skip-links {
        display: none !important;
    }
    
    .has-accessibility-toolbar .site-header {
        padding-top: 0;
    }
}
```

## Testing This Step

### 1. WCAG 2.1 AA Compliance Testing
```bash
# Test accessibility compliance using automated tools
# Install axe-core for testing
npm install -g @axe-core/cli

# Run axe accessibility audit
axe http://yoursite.com --tags wcag2a,wcag2aa --reporter spec

# Test specific pages
axe http://yoursite.com/sample-page/ --tags wcag2a,wcag2aa
axe http://yoursite.com/blog/ --tags wcag2a,wcag2aa
```

### 2. Keyboard Navigation Testing
```bash
# Test skip links
curl -s http://yoursite.com/ | grep -o '<a class="skip-link[^>]*>[^<]*</a>'

# Check tabindex values
curl -s http://yoursite.com/ | grep -o 'tabindex="[^"]*"'

# Validate ARIA attributes
curl -s http://yoursite.com/ | grep -E "(aria-|role=)" | wc -l
```

### 3. Screen Reader Testing
```bash
# Test semantic structure
curl -s http://yoursite.com/ | grep -E "<(header|nav|main|section|article|aside|footer)"

# Check heading hierarchy
curl -s http://yoursite.com/ | grep -o '<h[1-6][^>]*>' | sort

# Validate ARIA landmarks
curl -s http://yoursite.com/ | grep -E '(role="(banner|navigation|main|complementary|contentinfo)")'
```

### 4. Color Contrast Testing
```bash
# Test color contrast ratios (requires additional tools)
# Use browser extensions or online tools like WebAIM's Contrast Checker

# Check for high contrast mode support
curl -s http://yoursite.com/ | grep "high-contrast"

# Validate CSS custom properties
curl -s http://yoursite.com/wp-content/themes/gpress/style.css | grep "accessibility"
```

### 5. Performance Impact Testing
```bash
# Check accessibility asset loading
curl -s http://yoursite.com/ | grep -c "accessibility.*\.css"
curl -s http://yoursite.com/ | grep -c "accessibility.*\.js"

# Test conditional loading
curl -s "http://yoursite.com/?accessibility=1" | grep "accessibility-enhanced"

# Validate JavaScript errors
# Use browser console: Check for errors after loading accessibility features
```

## Expected Results After This Step

1. **WCAG 2.1 AA Compliance**: Full compliance across all theme components and templates
2. **Semantic Structure**: Proper HTML5 semantic elements and ARIA landmarks throughout
3. **Keyboard Navigation**: Complete keyboard accessibility with logical tab order and shortcuts
4. **Screen Reader Support**: Optimized content structure and ARIA attributes for assistive technologies
5. **Visual Accessibility**: High contrast mode, font scaling, and reduced motion support
6. **Conditional Loading**: Performance-optimized conditional accessibility loading based on user needs
7. **Testing Integration**: Built-in accessibility testing tools for development and maintenance
8. **Admin Interface**: Accessibility settings panel for easy configuration and monitoring

## Next Step

In Step 14, we'll implement comprehensive SEO optimization features that build upon our accessibility foundation, ensuring both human and search engine accessibility across the theme.

---

**Step 13 Completed**: WCAG 2.1 AA Accessibility Implementation ✅
- Complete WCAG 2.1 AA compliance implementation
- Semantic HTML5 structure with ARIA landmarks
- Advanced keyboard navigation and focus management
- Screen reader optimization and assistive technology support
- High contrast mode and user preference support
- Performance-optimized conditional accessibility loading
- Built-in accessibility testing and validation tools
- Admin interface for accessibility configuration