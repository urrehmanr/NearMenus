# Step 2: Core Theme Files Configuration

## Overview
This step enhances the basic GPress theme with advanced functionality, security features, and prepares it for Full Site Editing (FSE). We'll organize the theme's PHP functionality into separate include files and add essential WordPress features.

## Objectives
- Enhance the `functions.php` file with modular architecture
- Create organized PHP include files for different functionalities
- Add advanced theme support features
- Implement security and performance optimizations
- Prepare for FSE with proper theme support

## Files to Create in This Step

### Updated Directory Structure
```
gpress/
├── style.css                # (already exists)
├── index.php                # (already exists)
├── functions.php             # (enhanced in this step)
├── README.md                # (already exists)
├── .gitignore               # (already exists)
└── inc/                     # (new directory with PHP includes)
    ├── theme-setup.php      # Theme setup and support features
    ├── enqueue-scripts.php  # Asset enqueuing and optimization
    ├── customizer.php       # Customizer settings and controls
    └── block-patterns.php   # Basic block patterns
```

## Step-by-Step Implementation

### 1. Create the `inc/` Directory

First, create the includes directory:

```bash
mkdir inc
```

### 2. Enhanced functions.php

Update the main `functions.php` file:

```php
<?php
/**
 * GPress functions and definitions
 *
 * @package GPress
 * @since 1.0.0
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Define theme constants
 */
define('GPRESS_VERSION', '1.0.0');
define('GPRESS_THEME_DIR', get_template_directory());
define('GPRESS_THEME_URI', get_template_directory_uri());
define('GPRESS_INC_DIR', GPRESS_THEME_DIR . '/inc');

/**
 * Load theme components
 */
require_once GPRESS_INC_DIR . '/theme-setup.php';
require_once GPRESS_INC_DIR . '/enqueue-scripts.php';
require_once GPRESS_INC_DIR . '/customizer.php';
require_once GPRESS_INC_DIR . '/block-patterns.php';

/**
 * Security enhancements
 */

// Remove WordPress version from RSS feeds
function gpress_remove_version() {
    return '';
}
add_filter('the_generator', 'gpress_remove_version');

// Disable XML-RPC
add_filter('xmlrpc_enabled', '__return_false');

// Remove version from scripts and styles
function gpress_remove_version_scripts_styles($src) {
    if (strpos($src, 'ver=')) {
        $src = remove_query_arg('ver', $src);
    }
    return $src;
}
add_filter('style_loader_src', 'gpress_remove_version_scripts_styles', 9999);
add_filter('script_loader_src', 'gpress_remove_version_scripts_styles', 9999);

// Disable file editing from admin
if (!defined('DISALLOW_FILE_EDIT')) {
    define('DISALLOW_FILE_EDIT', true);
}

// Remove unnecessary meta tags
function gpress_clean_head() {
    remove_action('wp_head', 'rsd_link');
    remove_action('wp_head', 'wp_generator');
    remove_action('wp_head', 'feed_links', 2);
    remove_action('wp_head', 'index_rel_link');
    remove_action('wp_head', 'wlwmanifest_link');
    remove_action('wp_head', 'feed_links_extra', 3);
    remove_action('wp_head', 'start_post_rel_link', 10, 0);
    remove_action('wp_head', 'parent_post_rel_link', 10, 0);
    remove_action('wp_head', 'adjacent_posts_rel_link', 10, 0);
    remove_action('wp_head', 'adjacent_posts_rel_link_wp_head', 10, 0);
    remove_action('wp_head', 'wp_shortlink_wp_head', 10, 0);
}
add_action('init', 'gpress_clean_head');

/**
 * Performance optimizations
 */

// Optimize images
function gpress_optimize_images($attr, $attachment, $size) {
    // Add loading="lazy" to images (except the first few)
    if (!is_admin() && !wp_is_mobile() && !is_feed()) {
        $attr['loading'] = 'lazy';
    }
    
    // Add decoding="async" for better performance
    $attr['decoding'] = 'async';
    
    return $attr;
}
add_filter('wp_get_attachment_image_attributes', 'gpress_optimize_images', 10, 3);

// Defer non-critical scripts
function gpress_defer_scripts($tag, $handle, $src) {
    // Don't defer admin scripts, jQuery, or critical scripts
    if (is_admin() || strpos($handle, 'jquery') !== false || strpos($handle, 'gpress-critical') !== false) {
        return $tag;
    }
    
    // Add defer attribute to scripts
    return str_replace('<script ', '<script defer ', $tag);
}
add_filter('script_loader_tag', 'gpress_defer_scripts', 10, 3);

// Remove query strings from static resources
function gpress_remove_query_strings($src) {
    if (strpos($src, '?ver=')) {
        $src = remove_query_arg('ver', $src);
    }
    return $src;
}
add_filter('script_loader_src', 'gpress_remove_query_strings', 15, 1);
add_filter('style_loader_src', 'gpress_remove_query_strings', 15, 1);

/**
 * Basic header template (fallback if header.php doesn't exist)
 */
if (!function_exists('gpress_header')) {
    function gpress_header() {
        ?>
        <!doctype html>
        <html <?php language_attributes(); ?>>
        <head>
            <meta charset="<?php bloginfo('charset'); ?>">
            <meta name="viewport" content="width=device-width, initial-scale=1">
            <link rel="profile" href="https://gmpg.org/xfn/11">
            <?php wp_head(); ?>
        </head>

        <body <?php body_class(); ?>>
        <?php wp_body_open(); ?>
        
        <a class="skip-link screen-reader-text" href="#main"><?php esc_html_e('Skip to content', 'gpress'); ?></a>

        <header class="site-header">
            <div class="site-container">
                <div class="site-branding">
                    <?php gpress_site_logo(); ?>
                </div>
            </div>
        </header>
        <?php
    }
}

/**
 * Basic footer template (fallback if footer.php doesn't exist)
 */
if (!function_exists('gpress_footer')) {
    function gpress_footer() {
        ?>
        <footer class="site-footer">
            <div class="site-container">
                <div class="site-info">
                    <p>&copy; <?php echo date('Y'); ?> <?php bloginfo('name'); ?>. <?php esc_html_e('All rights reserved.', 'gpress'); ?></p>
                    <p>
                        <?php 
                        printf(
                            esc_html__('Powered by %1$s and %2$s theme.', 'gpress'),
                            '<a href="' . esc_url(__('https://wordpress.org/', 'gpress')) . '">WordPress</a>',
                            '<a href="#">GPress</a>'
                        );
                        ?>
                    </p>
                </div>
            </div>
        </footer>

        <?php wp_footer(); ?>
        </body>
        </html>
        <?php
    }
}

// Use template functions if header.php and footer.php don't exist
if (!locate_template('header.php')) {
    function get_header($name = null) {
        gpress_header();
    }
}

if (!locate_template('footer.php')) {
    function get_footer($name = null) {
        gpress_footer();
    }
}

/**
 * Site logo helper function
 */
function gpress_site_logo() {
    if (has_custom_logo()) {
        the_custom_logo();
    } else {
        if (is_front_page() && is_home()) :
            ?>
            <h1 class="site-title">
                <a href="<?php echo esc_url(home_url('/')); ?>" rel="home">
                    <?php bloginfo('name'); ?>
                </a>
            </h1>
            <?php
        else :
            ?>
            <p class="site-title">
                <a href="<?php echo esc_url(home_url('/')); ?>" rel="home">
                    <?php bloginfo('name'); ?>
                </a>
            </p>
            <?php
        endif;
        
        $description = get_bloginfo('description', 'display');
        if ($description || is_customize_preview()) :
            ?>
            <p class="site-description"><?php echo $description; ?></p>
        <?php endif;
    }
}
```

### 3. inc/theme-setup.php

Create the theme setup file:

```php
<?php
/**
 * Theme setup functions
 *
 * @package GPress
 * @since 1.0.0
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Set up theme defaults and register support for various WordPress features
 */
function gpress_theme_setup() {
    
    // Add default posts and comments RSS feed links to head
    add_theme_support('automatic-feed-links');

    // Let WordPress manage the document title
    add_theme_support('title-tag');

    // Enable support for Post Thumbnails on posts and pages
    add_theme_support('post-thumbnails');
    
    // Set default post thumbnail size
    set_post_thumbnail_size(1200, 675, true);
    
    // Add additional image sizes
    add_image_size('gpress-medium', 768, 432, true);
    add_image_size('gpress-small', 400, 225, true);

    // Add theme support for selective refresh for widgets
    add_theme_support('customize-selective-refresh-widgets');

    // Add support for HTML5 markup
    add_theme_support('html5', array(
        'search-form',
        'comment-form',
        'comment-list',
        'gallery',
        'caption',
        'style',
        'script',
        'navigation-widgets',
    ));

    // Set up the WordPress core custom background feature
    add_theme_support('custom-background', apply_filters('gpress_custom_background_args', array(
        'default-color' => 'ffffff',
        'default-image' => '',
    )));

    // Add theme support for custom logo
    add_theme_support('custom-logo', array(
        'height'      => 250,
        'width'       => 250,
        'flex-width'  => true,
        'flex-height' => true,
        'header-text' => array('site-title', 'site-description'),
    ));

    // Add support for editor styles
    add_theme_support('editor-styles');
    
    // Add editor stylesheet
    add_editor_style('assets/css/editor-style.css');

    // Add support for responsive embeds
    add_theme_support('responsive-embeds');
    
    // Add support for wide and full width alignments
    add_theme_support('align-wide');
    
    // Add support for block editor color palette
    add_theme_support('editor-color-palette', array(
        array(
            'name'  => esc_html__('Primary Blue', 'gpress'),
            'slug'  => 'primary-blue',
            'color' => '#3498db',
        ),
        array(
            'name'  => esc_html__('Secondary Blue', 'gpress'),
            'slug'  => 'secondary-blue',
            'color' => '#2980b9',
        ),
        array(
            'name'  => esc_html__('Dark Gray', 'gpress'),
            'slug'  => 'dark-gray',
            'color' => '#2c3e50',
        ),
        array(
            'name'  => esc_html__('Light Gray', 'gpress'),
            'slug'  => 'light-gray',
            'color' => '#f8f9fa',
        ),
        array(
            'name'  => esc_html__('White', 'gpress'),
            'slug'  => 'white',
            'color' => '#ffffff',
        ),
    ));
    
    // Add support for custom editor font sizes
    add_theme_support('editor-font-sizes', array(
        array(
            'name' => esc_html__('Small', 'gpress'),
            'size' => 14,
            'slug' => 'small'
        ),
        array(
            'name' => esc_html__('Regular', 'gpress'),
            'size' => 16,
            'slug' => 'regular'
        ),
        array(
            'name' => esc_html__('Medium', 'gpress'),
            'size' => 20,
            'slug' => 'medium'
        ),
        array(
            'name' => esc_html__('Large', 'gpress'),
            'size' => 24,
            'slug' => 'large'
        ),
        array(
            'name' => esc_html__('Extra Large', 'gpress'),
            'size' => 32,
            'slug' => 'extra-large'
        ),
    ));

    // Disable custom colors and font sizes
    add_theme_support('disable-custom-colors');
    add_theme_support('disable-custom-font-sizes');

    // Load theme text domain for translations
    load_theme_textdomain('gpress', GPRESS_THEME_DIR . '/languages');
    
    // Register navigation menus
    register_nav_menus(array(
        'primary' => esc_html__('Primary Menu', 'gpress'),
        'footer'  => esc_html__('Footer Menu', 'gpress'),
    ));
}
add_action('after_setup_theme', 'gpress_theme_setup');

/**
 * Set the content width in pixels, based on the theme's design and stylesheet
 */
function gpress_content_width() {
    $GLOBALS['content_width'] = apply_filters('gpress_content_width', 1200);
}
add_action('after_setup_theme', 'gpress_content_width', 0);

/**
 * Register widget areas
 */
function gpress_widgets_init() {
    
    // Primary sidebar
    register_sidebar(array(
        'name'          => esc_html__('Primary Sidebar', 'gpress'),
        'id'            => 'sidebar-1',
        'description'   => esc_html__('Main sidebar that appears on the right.', 'gpress'),
        'before_widget' => '<section id="%1$s" class="widget %2$s">',
        'after_widget'  => '</section>',
        'before_title'  => '<h2 class="widget-title">',
        'after_title'   => '</h2>',
    ));
    
    // Footer widgets
    for ($i = 1; $i <= 4; $i++) {
        register_sidebar(array(
            'name'          => sprintf(esc_html__('Footer Widget %d', 'gpress'), $i),
            'id'            => 'footer-' . $i,
            'description'   => sprintf(esc_html__('Footer widget area %d.', 'gpress'), $i),
            'before_widget' => '<section id="%1$s" class="widget %2$s">',
            'after_widget'  => '</section>',
            'before_title'  => '<h3 class="widget-title">',
            'after_title'   => '</h3>',
        ));
    }
}
add_action('widgets_init', 'gpress_widgets_init');

/**
 * Add custom image sizes to the media library dropdown
 */
function gpress_custom_image_sizes($sizes) {
    return array_merge($sizes, array(
        'gpress-medium' => esc_html__('Medium (768x432)', 'gpress'),
        'gpress-small'  => esc_html__('Small (400x225)', 'gpress'),
    ));
}
add_filter('image_size_names_choose', 'gpress_custom_image_sizes');

/**
 * Improve excerpt functionality
 */
function gpress_custom_excerpt_length($length) {
    return 25;
}
add_filter('excerpt_length', 'gpress_custom_excerpt_length', 999);

function gpress_custom_excerpt_more($more) {
    return '&hellip;';
}
add_filter('excerpt_more', 'gpress_custom_excerpt_more');

/**
 * Add support for SVG uploads
 */
function gpress_svg_upload_support($mimes) {
    $mimes['svg'] = 'image/svg+xml';
    return $mimes;
}
add_filter('upload_mimes', 'gpress_svg_upload_support');

/**
 * Fix SVG display in media library
 */
function gpress_svg_media_library_display($response, $attachment, $meta) {
    if ($response['type'] === 'image' && $response['subtype'] === 'svg+xml') {
        $response['image'] = array(
            'src' => $response['url'],
        );
    }
    return $response;
}
add_filter('wp_prepare_attachment_for_js', 'gpress_svg_media_library_display', 10, 3);
```

### 4. inc/enqueue-scripts.php

Create the asset enqueuing file:

```php
<?php
/**
 * Enqueue scripts and styles
 *
 * @package GPress
 * @since 1.0.0
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Enqueue scripts and styles
 */
function gpress_scripts() {
    
    // Enqueue main stylesheet
    wp_enqueue_style(
        'gpress-style',
        get_stylesheet_uri(),
        array(),
        GPRESS_VERSION
    );
    
    // Add RTL support
    wp_style_add_data('gpress-style', 'rtl', 'replace');

    // Enqueue Google Fonts (if needed)
    // wp_enqueue_style(
    //     'gpress-fonts',
    //     gpress_fonts_url(),
    //     array(),
    //     GPRESS_VERSION
    // );

    // Enqueue comment reply script for threaded comments
    if (is_singular() && comments_open() && get_option('thread_comments')) {
        wp_enqueue_script('comment-reply');
    }
    
    // Enqueue skip link focus fix for IE11
    wp_enqueue_script(
        'gpress-skip-link-focus-fix',
        GPRESS_THEME_URI . '/assets/js/skip-link-focus-fix.js',
        array(),
        GPRESS_VERSION,
        true
    );
    
    // Add preload for critical assets
    add_action('wp_head', 'gpress_preload_assets', 1);
}
add_action('wp_enqueue_scripts', 'gpress_scripts');

/**
 * Enqueue block editor styles
 */
function gpress_block_editor_styles() {
    wp_enqueue_style(
        'gpress-block-editor-style',
        GPRESS_THEME_URI . '/assets/css/editor-style.css',
        array(),
        GPRESS_VERSION
    );
}
add_action('enqueue_block_editor_assets', 'gpress_block_editor_styles');

/**
 * Preload critical assets
 */
function gpress_preload_assets() {
    // Preload main stylesheet
    echo '<link rel="preload" href="' . esc_url(get_stylesheet_uri()) . '" as="style" onload="this.onload=null;this.rel=\'stylesheet\'">';
    echo '<noscript><link rel="stylesheet" href="' . esc_url(get_stylesheet_uri()) . '"></noscript>';
    
    // DNS prefetch for external domains
    echo '<link rel="dns-prefetch" href="//fonts.googleapis.com">';
    echo '<link rel="dns-prefetch" href="//fonts.gstatic.com">';
}

/**
 * Google Fonts URL (optional)
 */
function gpress_fonts_url() {
    $fonts_url = '';
    
    /*
     * Translators: If there are characters in your language that are not
     * supported by the font, translate this to 'off'. Do not translate
     * into your own language.
     */
    $font = _x('on', 'Google font: on or off', 'gpress');
    
    if ('off' !== $font) {
        $font_families = array();
        
        // Add fonts here if needed
        // $font_families[] = 'Source Sans Pro:400,400i,600,700';
        
        if (!empty($font_families)) {
            $query_args = array(
                'family' => urlencode(implode('|', $font_families)),
                'subset' => urlencode('latin,latin-ext'),
                'display' => 'swap',
            );
            
            $fonts_url = add_query_arg($query_args, 'https://fonts.googleapis.com/css');
        }
    }
    
    return esc_url_raw($fonts_url);
}

/**
 * Add resource hints
 */
function gpress_resource_hints($urls, $relation_type) {
    if (wp_style_is('gpress-fonts', 'queue') && 'preconnect' === $relation_type) {
        $urls[] = array(
            'href' => 'https://fonts.gstatic.com',
            'crossorigin',
        );
    }
    return $urls;
}
add_filter('wp_resource_hints', 'gpress_resource_hints', 10, 2);

/**
 * Add critical CSS inline (basic implementation)
 */
function gpress_critical_css() {
    if (is_front_page() || is_home()) {
        echo '<style id="gpress-critical-css">';
        echo 'body{font-family:-apple-system,BlinkMacSystemFont,"Segoe UI",Roboto,Oxygen-Sans,Ubuntu,Cantarell,"Helvetica Neue",sans-serif;}';
        echo '.site-header{border-bottom:1px solid #e1e8ed;padding:2rem 0;}';
        echo '.site-container{max-width:1200px;margin:0 auto;padding:0 1rem;}';
        echo '</style>';
    }
}
add_action('wp_head', 'gpress_critical_css', 1);
```

### 5. inc/customizer.php

Create the customizer settings file:

```php
<?php
/**
 * Customizer settings and controls
 *
 * @package GPress
 * @since 1.0.0
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Add postMessage support for site title and description for the Theme Customizer.
 */
function gpress_customize_register($wp_customize) {
    
    // Transport settings for live preview
    $wp_customize->get_setting('blogname')->transport         = 'postMessage';
    $wp_customize->get_setting('blogdescription')->transport  = 'postMessage';
    $wp_customize->get_setting('header_textcolor')->transport = 'postMessage';

    // Site Identity enhancements
    if (isset($wp_customize->selective_refresh)) {
        $wp_customize->selective_refresh->add_partial('blogname', array(
            'selector'        => '.site-title a',
            'render_callback' => 'gpress_customize_partial_blogname',
        ));
        $wp_customize->selective_refresh->add_partial('blogdescription', array(
            'selector'        => '.site-description',
            'render_callback' => 'gpress_customize_partial_blogdescription',
        ));
    }
    
    // Theme Options Section
    $wp_customize->add_section('gpress_theme_options', array(
        'title'    => esc_html__('Theme Options', 'gpress'),
        'priority' => 30,
    ));
    
    // Layout Options
    $wp_customize->add_setting('gpress_layout', array(
        'default'           => 'full-width',
        'sanitize_callback' => 'gpress_sanitize_layout',
    ));
    
    $wp_customize->add_control('gpress_layout', array(
        'label'    => esc_html__('Site Layout', 'gpress'),
        'section'  => 'gpress_theme_options',
        'type'     => 'select',
        'choices'  => array(
            'full-width'    => esc_html__('Full Width', 'gpress'),
            'boxed'         => esc_html__('Boxed', 'gpress'),
        ),
    ));
    
    // Header Options
    $wp_customize->add_setting('gpress_header_style', array(
        'default'           => 'default',
        'sanitize_callback' => 'gpress_sanitize_header_style',
    ));
    
    $wp_customize->add_control('gpress_header_style', array(
        'label'    => esc_html__('Header Style', 'gpress'),
        'section'  => 'gpress_theme_options',
        'type'     => 'select',
        'choices'  => array(
            'default' => esc_html__('Default', 'gpress'),
            'minimal' => esc_html__('Minimal', 'gpress'),
        ),
    ));
    
    // Footer Options
    $wp_customize->add_setting('gpress_footer_text', array(
        'default'           => '',
        'sanitize_callback' => 'sanitize_text_field',
    ));
    
    $wp_customize->add_control('gpress_footer_text', array(
        'label'    => esc_html__('Footer Text', 'gpress'),
        'section'  => 'gpress_theme_options',
        'type'     => 'text',
    ));
    
    // Performance Section
    $wp_customize->add_section('gpress_performance', array(
        'title'    => esc_html__('Performance', 'gpress'),
        'priority' => 35,
    ));
    
    // Enable Google Fonts
    $wp_customize->add_setting('gpress_google_fonts', array(
        'default'           => false,
        'sanitize_callback' => 'rest_sanitize_boolean',
    ));
    
    $wp_customize->add_control('gpress_google_fonts', array(
        'label'    => esc_html__('Enable Google Fonts', 'gpress'),
        'section'  => 'gpress_performance',
        'type'     => 'checkbox',
    ));
}
add_action('customize_register', 'gpress_customize_register');

/**
 * Render the site title for the selective refresh partial.
 */
function gpress_customize_partial_blogname() {
    bloginfo('name');
}

/**
 * Render the site tagline for the selective refresh partial.
 */
function gpress_customize_partial_blogdescription() {
    bloginfo('description');
}

/**
 * Sanitize layout option
 */
function gpress_sanitize_layout($input) {
    $valid = array('full-width', 'boxed');
    return in_array($input, $valid, true) ? $input : 'full-width';
}

/**
 * Sanitize header style option
 */
function gpress_sanitize_header_style($input) {
    $valid = array('default', 'minimal');
    return in_array($input, $valid, true) ? $input : 'default';
}

/**
 * Bind JS handlers to instantly live-preview changes
 */
function gpress_customize_preview_js() {
    wp_enqueue_script(
        'gpress-customizer',
        GPRESS_THEME_URI . '/assets/js/customizer.js',
        array('customize-preview'),
        GPRESS_VERSION,
        true
    );
}
add_action('customize_preview_init', 'gpress_customize_preview_js');

/**
 * Customizer CSS output
 */
function gpress_customizer_css() {
    $layout = get_theme_mod('gpress_layout', 'full-width');
    $header_style = get_theme_mod('gpress_header_style', 'default');
    
    $css = '';
    
    // Layout styles
    if ('boxed' === $layout) {
        $css .= 'body { background-color: #f5f5f5; }';
        $css .= '.site-container { background-color: #fff; box-shadow: 0 0 10px rgba(0,0,0,0.1); }';
    }
    
    // Header styles
    if ('minimal' === $header_style) {
        $css .= '.site-header { border-bottom: none; padding: 1rem 0; }';
        $css .= '.site-title { font-size: 1.5rem; }';
    }
    
    if (!empty($css)) {
        echo '<style type="text/css" id="gpress-customizer-css">' . $css . '</style>';
    }
}
add_action('wp_head', 'gpress_customizer_css');
```

### 6. inc/block-patterns.php

Create the block patterns file:

```php
<?php
/**
 * Block patterns
 *
 * @package GPress
 * @since 1.0.0
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Register block patterns
 */
function gpress_register_block_patterns() {
    
    // Check if function exists (WordPress 5.5+)
    if (!function_exists('register_block_pattern')) {
        return;
    }
    
    // Register pattern categories
    register_block_pattern_category('gpress-headers', array(
        'label' => esc_html__('GPress Headers', 'gpress'),
    ));
    
    register_block_pattern_category('gpress-footers', array(
        'label' => esc_html__('GPress Footers', 'gpress'),
    ));
    
    register_block_pattern_category('gpress-content', array(
        'label' => esc_html__('GPress Content', 'gpress'),
    ));
    
    // Simple header pattern
    register_block_pattern('gpress/simple-header', array(
        'title'       => esc_html__('Simple Header', 'gpress'),
        'categories'  => array('gpress-headers'),
        'description' => esc_html__('A simple header with site title and navigation.', 'gpress'),
        'content'     => '<!-- wp:group {"layout":{"type":"flex","justifyContent":"space-between"}} -->
        <div class="wp-block-group">
            <!-- wp:site-title {"level":0} /-->
            <!-- wp:navigation {"layout":{"type":"flex","setCascadingProperties":true,"justifyContent":"right"}} /-->
        </div>
        <!-- /wp:group -->'
    ));
    
    // Call to action pattern
    register_block_pattern('gpress/call-to-action', array(
        'title'       => esc_html__('Call to Action', 'gpress'),
        'categories'  => array('gpress-content'),
        'description' => esc_html__('A call to action section with heading and button.', 'gpress'),
        'content'     => '<!-- wp:group {"style":{"spacing":{"padding":{"top":"3rem","bottom":"3rem"}}},"backgroundColor":"light-gray","layout":{"type":"constrained"}} -->
        <div class="wp-block-group has-light-gray-background-color has-background" style="padding-top:3rem;padding-bottom:3rem">
            <!-- wp:heading {"textAlign":"center"} -->
            <h2 class="wp-block-heading has-text-align-center">Ready to Get Started?</h2>
            <!-- /wp:heading -->
            <!-- wp:paragraph {"align":"center"} -->
            <p class="has-text-align-center">Join thousands of satisfied customers and experience the difference.</p>
            <!-- /wp:paragraph -->
            <!-- wp:buttons {"layout":{"type":"flex","justifyContent":"center"}} -->
            <div class="wp-block-buttons">
                <!-- wp:button -->
                <div class="wp-block-button">
                    <a class="wp-block-button__link wp-element-button">Get Started Today</a>
                </div>
                <!-- /wp:button -->
            </div>
            <!-- /wp:buttons -->
        </div>
        <!-- /wp:group -->'
    ));
    
    // Two column content pattern
    register_block_pattern('gpress/two-columns', array(
        'title'       => esc_html__('Two Columns Content', 'gpress'),
        'categories'  => array('gpress-content'),
        'description' => esc_html__('Two columns with heading and text.', 'gpress'),
        'content'     => '<!-- wp:columns -->
        <div class="wp-block-columns">
            <!-- wp:column -->
            <div class="wp-block-column">
                <!-- wp:heading {"level":3} -->
                <h3 class="wp-block-heading">Feature One</h3>
                <!-- /wp:heading -->
                <!-- wp:paragraph -->
                <p>Describe your first feature here. Make it compelling and easy to understand.</p>
                <!-- /wp:paragraph -->
            </div>
            <!-- /wp:column -->
            <!-- wp:column -->
            <div class="wp-block-column">
                <!-- wp:heading {"level":3} -->
                <h3 class="wp-block-heading">Feature Two</h3>
                <!-- /wp:heading -->
                <!-- wp:paragraph -->
                <p>Describe your second feature here. Highlight the benefits and value proposition.</p>
                <!-- /wp:paragraph -->
            </div>
            <!-- /wp:column -->
        </div>
        <!-- /wp:columns -->'
    ));
}
add_action('init', 'gpress_register_block_patterns');
```

### 7. Create Basic JavaScript Files

Create the `assets/js/` directory and add basic JavaScript files:

```bash
mkdir -p assets/js
```

#### assets/js/skip-link-focus-fix.js

```javascript
/**
 * Fix for skip link focus in IE11
 */
(function() {
    var isIE = /(trident|msie)/i.test(navigator.userAgent);

    if (isIE && document.getElementById && window.addEventListener) {
        window.addEventListener('hashchange', function() {
            var id = location.hash.substring(1),
                element;

            if (!(/^[A-z0-9_-]+$/.test(id))) {
                return;
            }

            element = document.getElementById(id);

            if (element) {
                if (!(/^(?:a|select|input|button|textarea)$/i.test(element.tagName))) {
                    element.tabIndex = -1;
                }
                element.focus();
            }
        }, false);
    }
})();
```

#### assets/js/customizer.js

```javascript
/**
 * Customizer live preview
 */
(function($) {
    // Site title
    wp.customize('blogname', function(value) {
        value.bind(function(to) {
            $('.site-title a').text(to);
        });
    });

    // Site description
    wp.customize('blogdescription', function(value) {
        value.bind(function(to) {
            $('.site-description').text(to);
        });
    });

    // Header text color
    wp.customize('header_textcolor', function(value) {
        value.bind(function(to) {
            if ('blank' === to) {
                $('.site-title, .site-description').css({
                    'clip': 'rect(1px, 1px, 1px, 1px)',
                    'position': 'absolute'
                });
            } else {
                $('.site-title, .site-description').css({
                    'clip': 'auto',
                    'position': 'relative'
                });
                $('.site-title a, .site-description').css({
                    'color': to
                });
            }
        });
    });
})(jQuery);
```

## Testing Instructions

After completing this step, perform the following tests:

### 1. File Structure Test
```bash
# Verify all new files are created
ls -la inc/
# Should show: theme-setup.php, enqueue-scripts.php, customizer.php, block-patterns.php

ls -la assets/js/
# Should show: skip-link-focus-fix.js, customizer.js
```

### 2. Theme Activation Test
1. Refresh WordPress Admin → Appearance → Themes
2. Re-activate the GPress theme if needed
3. Verify no PHP errors appear

### 3. Functionality Test
1. Go to Appearance → Customize
2. Verify new "Theme Options" and "Performance" sections appear
3. Test layout and header style options
4. Verify live preview works

### 4. Block Editor Test
1. Create a new post
2. Try adding some blocks (paragraph, heading, image)
3. Verify custom color palette appears in color settings
4. Test custom font sizes in text settings

### 5. Pattern Test
1. In block editor, click the "+" to add blocks
2. Go to the "Patterns" tab
3. Verify "GPress Headers", "GPress Footers", and "GPress Content" categories appear
4. Try inserting a pattern

### 6. Widget Test
1. Go to Appearance → Widgets
2. Verify sidebar and footer widget areas are available
3. Add a test widget to verify it works

### 7. Performance Test
1. Run Lighthouse test again
2. Should maintain 90+ performance score
3. Verify CSS and JS are loading properly

### 8. Security Test
1. Check that PHP files cannot be accessed directly
2. Verify WordPress version is not displayed in page source
3. Test that file editing is disabled in admin

## Expected Results

After completing Step 2, you should have:

- ✅ Modular PHP architecture with organized include files
- ✅ Enhanced theme setup with advanced WordPress features
- ✅ Custom color palette and font sizes for block editor
- ✅ Basic customizer options for theme settings
- ✅ Block patterns for content creation
- ✅ Widget areas for sidebars and footer
- ✅ Security enhancements and clean WordPress head
- ✅ Performance optimizations for scripts and styles
- ✅ Foundation for Full Site Editing features

## Next Step

Proceed to [Step 3: theme.json Setup for FSE](./step-03-theme-json.md) to add Full Site Editing configuration and advanced block support.

## Troubleshooting

**Customizer not working:**
- Clear any caching plugins
- Check browser console for JavaScript errors
- Verify customizer.js is loading

**Block patterns not appearing:**
- Ensure WordPress version is 5.5 or higher
- Check that block-patterns.php is being included
- Verify no PHP errors in error log

**Performance issues:**
- Check that asset enqueuing is working properly
- Verify critical CSS is inlining correctly
- Test with caching plugins disabled

**Widget areas not showing:**
- Confirm gpress_widgets_init() is running
- Check Appearance → Widgets in admin
- Verify no conflicts with other plugins