# Step 2: Core Theme Files Configuration

## Objective
Set up essential theme files including `functions.php`, theme setup functions, and asset enqueuing for optimal performance.

## What You'll Learn
- WordPress theme setup hooks and functions
- Asset enqueuing best practices
- Theme support features
- Performance optimization techniques
- Security and sanitization

## Core Files to Create

### 1. functions.php - Main Theme Functions File

```php
<?php
/**
 * ModernBlog2025 Theme Functions
 *
 * @package ModernBlog2025
 * @since 1.0.0
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

// Define theme constants
define('MODERNBLOG2025_VERSION', '1.0.0');
define('MODERNBLOG2025_THEME_DIR', get_template_directory());
define('MODERNBLOG2025_THEME_URI', get_template_directory_uri());

/**
 * Load theme include files
 */
require_once MODERNBLOG2025_THEME_DIR . '/inc/theme-setup.php';
require_once MODERNBLOG2025_THEME_DIR . '/inc/enqueue-scripts.php';
require_once MODERNBLOG2025_THEME_DIR . '/inc/customizer.php';
require_once MODERNBLOG2025_THEME_DIR . '/inc/block-patterns.php';

/**
 * Theme setup hook
 * Runs after theme is loaded
 */
function modernblog2025_setup() {
    // Load text domain for translations
    load_theme_textdomain('modernblog2025', MODERNBLOG2025_THEME_DIR . '/languages');

    // Add theme support for essential features
    add_theme_support('post-thumbnails');
    add_theme_support('title-tag');
    add_theme_support('automatic-feed-links');
    add_theme_support('html5', array(
        'search-form',
        'comment-form',
        'comment-list',
        'gallery',
        'caption',
        'style',
        'script',
    ));

    // Add support for responsive embeds
    add_theme_support('responsive-embeds');

    // Add support for experimental link color control
    add_theme_support('experimental-link-color');

    // Add support for custom line height controls
    add_theme_support('custom-line-height');

    // Add support for custom units
    add_theme_support('custom-units');

    // Add support for custom spacing
    add_theme_support('custom-spacing');

    // Add support for appearance tools
    add_theme_support('appearance-tools');

    // Add support for border controls
    add_theme_support('border');

    // Register navigation menus
    register_nav_menus(array(
        'primary' => esc_html__('Primary Menu', 'modernblog2025'),
        'footer' => esc_html__('Footer Menu', 'modernblog2025'),
    ));

    // Set content width for images and videos
    if (!isset($content_width)) {
        $content_width = 800;
    }
}
add_action('after_setup_theme', 'modernblog2025_setup');

/**
 * Register widget areas
 */
function modernblog2025_widgets_init() {
    register_sidebar(array(
        'name'          => esc_html__('Primary Sidebar', 'modernblog2025'),
        'id'            => 'sidebar-1',
        'description'   => esc_html__('Add widgets here to appear in your sidebar.', 'modernblog2025'),
        'before_widget' => '<section id="%1$s" class="widget %2$s">',
        'after_widget'  => '</section>',
        'before_title'  => '<h3 class="widget-title">',
        'after_title'   => '</h3>',
    ));

    register_sidebar(array(
        'name'          => esc_html__('Footer Widgets', 'modernblog2025'),
        'id'            => 'footer-widgets',
        'description'   => esc_html__('Add widgets here to appear in your footer.', 'modernblog2025'),
        'before_widget' => '<div id="%1$s" class="footer-widget %2$s">',
        'after_widget'  => '</div>',
        'before_title'  => '<h4 class="footer-widget-title">',
        'after_title'   => '</h4>',
    ));
}
add_action('widgets_init', 'modernblog2025_widgets_init');
```

### 2. inc/theme-setup.php - Theme Setup Functions

```php
<?php
/**
 * Theme Setup Functions
 *
 * @package ModernBlog2025
 * @since 1.0.0
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Add theme support for block editor features
 */
function modernblog2025_block_editor_setup() {
    // Add support for full and wide alignment
    add_theme_support('align-wide');

    // Add support for block editor styles
    add_theme_support('editor-styles');

    // Add editor stylesheet
    add_editor_style('assets/css/editor-style.css');

    // Add support for custom editor font sizes
    add_theme_support('editor-font-sizes', array(
        array(
            'name' => esc_html__('Small', 'modernblog2025'),
            'size' => 14,
            'slug' => 'small'
        ),
        array(
            'name' => esc_html__('Normal', 'modernblog2025'),
            'size' => 16,
            'slug' => 'normal'
        ),
        array(
            'name' => esc_html__('Large', 'modernblog2025'),
            'size' => 20,
            'slug' => 'large'
        ),
        array(
            'name' => esc_html__('Extra Large', 'modernblog2025'),
            'size' => 24,
            'slug' => 'extra-large'
        )
    ));

    // Add support for custom color palette
    add_theme_support('editor-color-palette', array(
        array(
            'name'  => esc_html__('Primary', 'modernblog2025'),
            'slug'  => 'primary',
            'color' => '#2c3e50',
        ),
        array(
            'name'  => esc_html__('Secondary', 'modernblog2025'),
            'slug'  => 'secondary',
            'color' => '#3498db',
        ),
        array(
            'name'  => esc_html__('Accent', 'modernblog2025'),
            'slug'  => 'accent',
            'color' => '#e74c3c',
        ),
        array(
            'name'  => esc_html__('Light Gray', 'modernblog2025'),
            'slug'  => 'light-gray',
            'color' => '#f8f9fa',
        ),
        array(
            'name'  => esc_html__('Dark Gray', 'modernblog2025'),
            'slug'  => 'dark-gray',
            'color' => '#6c757d',
        ),
    ));

    // Disable custom colors to maintain design consistency
    add_theme_support('disable-custom-colors');

    // Disable custom font sizes to maintain typography hierarchy
    add_theme_support('disable-custom-font-sizes');
}
add_action('after_setup_theme', 'modernblog2025_block_editor_setup');

/**
 * Customize excerpt length
 */
function modernblog2025_excerpt_length($length) {
    return 25;
}
add_filter('excerpt_length', 'modernblog2025_excerpt_length');

/**
 * Customize excerpt more string
 */
function modernblog2025_excerpt_more($more) {
    return '&hellip; <a href="' . get_permalink() . '" class="read-more">' . esc_html__('Read More', 'modernblog2025') . '</a>';
}
add_filter('excerpt_more', 'modernblog2025_excerpt_more');

/**
 * Add custom image sizes
 */
function modernblog2025_image_sizes() {
    // Featured image for blog posts
    add_image_size('modernblog2025-featured', 800, 450, true);
    
    // Thumbnail for post grid
    add_image_size('modernblog2025-thumbnail', 400, 250, true);
    
    // Hero image for pages
    add_image_size('modernblog2025-hero', 1200, 600, true);
}
add_action('after_setup_theme', 'modernblog2025_image_sizes');

/**
 * Add custom image size labels
 */
function modernblog2025_image_size_names($sizes) {
    return array_merge($sizes, array(
        'modernblog2025-featured' => esc_html__('Featured Image', 'modernblog2025'),
        'modernblog2025-thumbnail' => esc_html__('Post Thumbnail', 'modernblog2025'),
        'modernblog2025-hero' => esc_html__('Hero Image', 'modernblog2025'),
    ));
}
add_filter('image_size_names_choose', 'modernblog2025_image_size_names');

/**
 * Security enhancements
 */
function modernblog2025_security_setup() {
    // Remove WordPress version from head
    remove_action('wp_head', 'wp_generator');
    
    // Remove RSD link
    remove_action('wp_head', 'rsd_link');
    
    // Remove Windows Live Writer manifest link
    remove_action('wp_head', 'wlwmanifest_link');
    
    // Remove shortlink
    remove_action('wp_head', 'wp_shortlink_wp_head');
    
    // Remove feed links (unless specifically needed)
    remove_action('wp_head', 'feed_links_extra', 3);
}
add_action('init', 'modernblog2025_security_setup');

/**
 * Optimize head section
 */
function modernblog2025_optimize_head() {
    // Remove unnecessary emoji scripts and styles
    remove_action('wp_head', 'print_emoji_detection_script', 7);
    remove_action('admin_print_scripts', 'print_emoji_detection_script');
    remove_action('wp_print_styles', 'print_emoji_styles');
    remove_action('admin_print_styles', 'print_emoji_styles');
    
    // Remove unnecessary oEmbed discovery links
    remove_action('wp_head', 'wp_oembed_add_discovery_links');
    
    // Remove REST API link from head
    remove_action('wp_head', 'rest_output_link_wp_head');
}
add_action('init', 'modernblog2025_optimize_head');
```

### 3. inc/enqueue-scripts.php - Asset Management

```php
<?php
/**
 * Enqueue Scripts and Styles
 *
 * @package ModernBlog2025
 * @since 1.0.0
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Enqueue front-end styles and scripts
 */
function modernblog2025_enqueue_assets() {
    // Main theme stylesheet
    wp_enqueue_style(
        'modernblog2025-style',
        MODERNBLOG2025_THEME_URI . '/assets/css/style.min.css',
        array(),
        MODERNBLOG2025_VERSION
    );

    // Responsive styles
    wp_enqueue_style(
        'modernblog2025-responsive',
        MODERNBLOG2025_THEME_URI . '/assets/css/responsive.min.css',
        array('modernblog2025-style'),
        MODERNBLOG2025_VERSION
    );

    // Print styles
    wp_enqueue_style(
        'modernblog2025-print',
        MODERNBLOG2025_THEME_URI . '/assets/css/print.min.css',
        array(),
        MODERNBLOG2025_VERSION,
        'print'
    );

    // Main theme JavaScript (minimal, only if needed)
    if (is_singular() && comments_open() && get_option('thread_comments')) {
        wp_enqueue_script('comment-reply');
    }

    // Custom theme JavaScript (only if absolutely necessary)
    wp_enqueue_script(
        'modernblog2025-script',
        MODERNBLOG2025_THEME_URI . '/assets/js/theme.min.js',
        array(),
        MODERNBLOG2025_VERSION,
        array(
            'strategy' => 'defer', // Use defer for better performance
            'in_footer' => true
        )
    );
}
add_action('wp_enqueue_scripts', 'modernblog2025_enqueue_assets');

/**
 * Enqueue block editor styles
 */
function modernblog2025_enqueue_block_editor_styles() {
    wp_enqueue_style(
        'modernblog2025-editor-style',
        MODERNBLOG2025_THEME_URI . '/assets/css/editor-style.min.css',
        array(),
        MODERNBLOG2025_VERSION
    );
}
add_action('enqueue_block_editor_assets', 'modernblog2025_enqueue_block_editor_styles');

/**
 * Preload critical resources
 */
function modernblog2025_preload_resources() {
    // Preload main stylesheet
    echo '<link rel="preload" href="' . esc_url(MODERNBLOG2025_THEME_URI . '/assets/css/style.min.css') . '" as="style" onload="this.onload=null;this.rel=\'stylesheet\'">' . "\n";
    
    // Preload fonts (if using custom fonts)
    // echo '<link rel="preload" href="' . esc_url(MODERNBLOG2025_THEME_URI . '/assets/fonts/font-file.woff2') . '" as="font" type="font/woff2" crossorigin>' . "\n";
}
add_action('wp_head', 'modernblog2025_preload_resources', 1);

/**
 * Add resource hints for performance
 */
function modernblog2025_resource_hints($urls, $relation_type) {
    switch ($relation_type) {
        case 'dns-prefetch':
            $urls[] = '//fonts.googleapis.com';
            $urls[] = '//fonts.gstatic.com';
            break;
        
        case 'preconnect':
            $urls[] = array(
                'href' => '//fonts.gstatic.com',
                'crossorigin' => 'anonymous'
            );
            break;
    }
    
    return $urls;
}
add_filter('wp_resource_hints', 'modernblog2025_resource_hints', 10, 2);

/**
 * Optimize jQuery loading
 */
function modernblog2025_optimize_jquery() {
    // Only load jQuery if needed (for comment replies, etc.)
    if (!is_admin() && !is_singular() || !comments_open()) {
        wp_deregister_script('jquery');
    }
}
add_action('wp_enqueue_scripts', 'modernblog2025_optimize_jquery', 1);

/**
 * Add async/defer attributes to scripts
 */
function modernblog2025_script_attributes($tag, $handle, $src) {
    // List of scripts to defer
    $defer_scripts = array(
        'modernblog2025-script',
    );
    
    // List of scripts to load async
    $async_scripts = array(
        // Add handles of scripts that should load async
    );
    
    if (in_array($handle, $defer_scripts)) {
        return str_replace('<script ', '<script defer ', $tag);
    }
    
    if (in_array($handle, $async_scripts)) {
        return str_replace('<script ', '<script async ', $tag);
    }
    
    return $tag;
}
add_filter('script_loader_tag', 'modernblog2025_script_attributes', 10, 3);

/**
 * Remove unnecessary scripts and styles
 */
function modernblog2025_cleanup_wp_scripts() {
    // Remove jQuery Migrate if not needed
    if (!is_admin()) {
        wp_deregister_script('jquery-migrate');
    }
    
    // Remove Gutenberg block library CSS on frontend if not using blocks
    // Uncomment if you're not using Gutenberg blocks on frontend
    // wp_dequeue_style('wp-block-library');
    // wp_dequeue_style('wp-block-library-theme');
}
add_action('wp_enqueue_scripts', 'modernblog2025_cleanup_wp_scripts', 20);
```

### 4. inc/customizer.php - Theme Customizer Settings

```php
<?php
/**
 * Customizer Settings
 *
 * @package ModernBlog2025
 * @since 1.0.0
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Add customizer settings and controls
 */
function modernblog2025_customize_register($wp_customize) {
    
    // Add theme options panel
    $wp_customize->add_panel('modernblog2025_theme_options', array(
        'title'    => esc_html__('Theme Options', 'modernblog2025'),
        'priority' => 30,
    ));

    // Header Settings Section
    $wp_customize->add_section('modernblog2025_header_settings', array(
        'title'    => esc_html__('Header Settings', 'modernblog2025'),
        'panel'    => 'modernblog2025_theme_options',
        'priority' => 10,
    ));

    // Logo max height
    $wp_customize->add_setting('modernblog2025_logo_height', array(
        'default'           => '60',
        'sanitize_callback' => 'absint',
        'transport'         => 'postMessage',
    ));

    $wp_customize->add_control('modernblog2025_logo_height', array(
        'label'       => esc_html__('Logo Max Height (px)', 'modernblog2025'),
        'section'     => 'modernblog2025_header_settings',
        'type'        => 'number',
        'input_attrs' => array(
            'min' => 30,
            'max' => 150,
        ),
    ));

    // Footer Settings Section
    $wp_customize->add_section('modernblog2025_footer_settings', array(
        'title'    => esc_html__('Footer Settings', 'modernblog2025'),
        'panel'    => 'modernblog2025_theme_options',
        'priority' => 20,
    ));

    // Footer copyright text
    $wp_customize->add_setting('modernblog2025_footer_copyright', array(
        'default'           => sprintf(esc_html__('© %s All rights reserved.', 'modernblog2025'), date('Y')),
        'sanitize_callback' => 'sanitize_text_field',
        'transport'         => 'postMessage',
    ));

    $wp_customize->add_control('modernblog2025_footer_copyright', array(
        'label'   => esc_html__('Copyright Text', 'modernblog2025'),
        'section' => 'modernblog2025_footer_settings',
        'type'    => 'text',
    ));

    // Performance Settings Section
    $wp_customize->add_section('modernblog2025_performance_settings', array(
        'title'    => esc_html__('Performance Settings', 'modernblog2025'),
        'panel'    => 'modernblog2025_theme_options',
        'priority' => 30,
    ));

    // Enable lazy loading
    $wp_customize->add_setting('modernblog2025_lazy_loading', array(
        'default'           => true,
        'sanitize_callback' => 'rest_sanitize_boolean',
    ));

    $wp_customize->add_control('modernblog2025_lazy_loading', array(
        'label'   => esc_html__('Enable Lazy Loading for Images', 'modernblog2025'),
        'section' => 'modernblog2025_performance_settings',
        'type'    => 'checkbox',
    ));
}
add_action('customize_register', 'modernblog2025_customize_register');

/**
 * Bind JS handlers to instantly live-preview changes
 */
function modernblog2025_customize_preview_js() {
    wp_enqueue_script(
        'modernblog2025-customize-preview',
        MODERNBLOG2025_THEME_URI . '/assets/js/customize-preview.js',
        array('customize-preview'),
        MODERNBLOG2025_VERSION,
        true
    );
}
add_action('customize_preview_init', 'modernblog2025_customize_preview_js');
```

### 5. inc/block-patterns.php - Custom Block Patterns

```php
<?php
/**
 * Block Patterns
 *
 * @package ModernBlog2025
 * @since 1.0.0
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Register custom block patterns
 */
function modernblog2025_register_block_patterns() {
    
    // Check if block patterns are supported
    if (!function_exists('register_block_pattern')) {
        return;
    }

    // Register pattern category
    register_block_pattern_category(
        'modernblog2025',
        array('label' => esc_html__('ModernBlog2025', 'modernblog2025'))
    );

    // Hero section pattern
    register_block_pattern(
        'modernblog2025/hero-section',
        array(
            'title'       => esc_html__('Hero Section', 'modernblog2025'),
            'description' => esc_html__('A hero section with title and description', 'modernblog2025'),
            'categories'  => array('modernblog2025'),
            'content'     => '<!-- wp:cover {"url":"","id":0,"dimRatio":50,"minHeight":400,"contentPosition":"center center","isDark":false,"className":"hero-section"} -->
<div class="wp-block-cover hero-section" style="min-height:400px"><span aria-hidden="true" class="wp-block-cover__background has-background-dim"></span><div class="wp-block-cover__inner-container"><!-- wp:heading {"textAlign":"center","level":1,"fontSize":"extra-large"} -->
<h1 class="wp-block-heading has-text-align-center has-extra-large-font-size">Welcome to Our Blog</h1>
<!-- /wp:heading -->

<!-- wp:paragraph {"align":"center"} -->
<p class="has-text-align-center">Discover amazing content and insights</p>
<!-- /wp:paragraph -->

<!-- wp:buttons {"layout":{"type":"flex","justifyContent":"center"}} -->
<div class="wp-block-buttons"><!-- wp:button {"className":"is-style-fill"} -->
<div class="wp-block-button is-style-fill"><a class="wp-block-button__link wp-element-button">Get Started</a></div>
<!-- /wp:button --></div>
<!-- /wp:buttons --></div></div>
<!-- /wp:cover -->',
        )
    );

    // Two column content pattern
    register_block_pattern(
        'modernblog2025/two-column-content',
        array(
            'title'       => esc_html__('Two Column Content', 'modernblog2025'),
            'description' => esc_html__('Content split into two columns', 'modernblog2025'),
            'categories'  => array('modernblog2025'),
            'content'     => '<!-- wp:columns -->
<div class="wp-block-columns"><!-- wp:column -->
<div class="wp-block-column"><!-- wp:heading {"level":3} -->
<h3 class="wp-block-heading">Left Column</h3>
<!-- /wp:heading -->

<!-- wp:paragraph -->
<p>This is content for the left column. You can add any blocks here.</p>
<!-- /wp:paragraph --></div>
<!-- /wp:column -->

<!-- wp:column -->
<div class="wp-block-column"><!-- wp:heading {"level":3} -->
<h3 class="wp-block-heading">Right Column</h3>
<!-- /wp:heading -->

<!-- wp:paragraph -->
<p>This is content for the right column. Perfect for side-by-side layouts.</p>
<!-- /wp:paragraph --></div>
<!-- /wp:column --></div>
<!-- /wp:columns -->',
        )
    );
}
add_action('init', 'modernblog2025_register_block_patterns');
```

## Asset Files Structure

Create placeholder files for assets:

### assets/css/style.min.css
```css
/* Main styles will be built in Step 7 - CSS Architecture */
```

### assets/css/responsive.min.css
```css
/* Responsive styles will be built in Step 7 - CSS Architecture */
```

### assets/css/print.min.css
```css
/* Print styles for better printing experience */
@media print {
    * {
        background: transparent !important;
        color: black !important;
        box-shadow: none !important;
        text-shadow: none !important;
    }
    
    body {
        font-size: 12pt;
        line-height: 1.4;
    }
    
    .no-print {
        display: none !important;
    }
}
```

### assets/js/theme.min.js
```javascript
// Minimal theme JavaScript - will be built in later steps
(function() {
    'use strict';
    
    // Theme initialization
    document.addEventListener('DOMContentLoaded', function() {
        console.log('ModernBlog2025 theme loaded');
    });
})();
```

## Verification Checklist

After completing this step, verify:

- [ ] `functions.php` loads without errors
- [ ] Theme appears in WordPress admin
- [ ] All include files are properly loaded
- [ ] No PHP errors in debug log
- [ ] Customizer settings appear correctly
- [ ] Block patterns are registered

## Next Steps

In Step 3, we'll create the `theme.json` file for Full Site Editing configuration and global styling control.

## Performance Benefits

1. **Optimized asset loading** - Scripts are deferred/async where appropriate
2. **Minimal JavaScript** - Only essential scripts are loaded
3. **Resource hints** - DNS prefetch and preconnect for external resources
4. **Security enhancements** - Removed unnecessary WordPress head elements
5. **Clean code structure** - Organized includes for maintainability

## Troubleshooting

**Theme functions not loading:**
- Check file paths in require_once statements
- Verify file permissions
- Check PHP syntax with `php -l functions.php`

**Customizer settings missing:**
- Ensure customizer.php is properly included
- Check for JavaScript errors in browser console
- Verify hook names are correct