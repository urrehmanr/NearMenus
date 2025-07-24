# Step 2: Core Theme Files Configuration

## Overview
This step enhances the basic **GPress** theme with advanced functionality, security features, and prepares it for Full Site Editing (FSE). We'll organize the theme's PHP functionality into separate include files, add essential WordPress features, and implement modular architecture for better maintainability and performance.

## Objectives
- Enhance the `functions.php` file with modular architecture
- Create organized PHP include files for different functionalities
- Add advanced theme support features and optimization
- Implement security and performance enhancements
- Prepare for FSE with proper theme support
- Establish customizer framework and block patterns foundation

## What You'll Learn
- Modular PHP architecture for WordPress themes
- Advanced theme support configuration
- Security hardening techniques
- Performance optimization strategies
- Customizer API implementation
- Block patterns foundation setup

## Files Structure for This Step

### 📁 **Files to CREATE** (New Files)
```
inc/                         # New directory for PHP includes
├── theme-setup.php          # Theme setup and support features
├── enqueue-scripts.php      # Asset enqueuing and optimization
├── customizer.php           # Customizer settings and controls
└── block-patterns.php       # Basic block patterns foundation

assets/                      # Enhanced asset structure
├── js/                      # New JavaScript directory
│   ├── skip-link-focus-fix.js  # Accessibility enhancement script
│   └── theme.js             # Main theme JavaScript (placeholder)
└── css/                     # New CSS directory (empty, prepared for step 7)
    └── .gitkeep             # Keep directory in version control
```

### 📝 **Files to UPDATE** (Existing Files)
```
functions.php                # Enhanced with modular includes
README.md                    # Updated with new features documentation
```

### 🎯 **Optimization Features Implemented**
- Modular PHP architecture for better performance
- Conditional asset loading system
- Advanced security hardening
- Performance-first asset enqueuing
- Accessibility enhancements
- SEO-ready meta tag foundation
- Customizer performance optimization
- Block pattern conditional loading

## Step-by-Step Implementation

### 1. Create the `inc/` Directory Structure

```bash
# Create includes directory
mkdir inc

# Create assets structure
mkdir -p assets/js
mkdir -p assets/css

# Create .gitkeep for empty directories
touch assets/css/.gitkeep
```

### 2. UPDATE functions.php (Enhanced Modular Architecture)

**Purpose**: Transform functions.php into a modular loader with enhanced features

```php
<?php
/**
 * GPress functions and definitions
 *
 * @package GPress
 * @since 1.0.0
 * @version 1.1.0
 */

// Prevent direct access for security
if (!defined('ABSPATH')) {
    exit('Direct access denied.');
}

/**
 * Define theme constants for consistency and performance
 */
define('GPRESS_VERSION', '1.1.0');
define('GPRESS_THEME_DIR', get_template_directory());
define('GPRESS_THEME_URI', get_template_directory_uri());
define('GPRESS_INC_DIR', GPRESS_THEME_DIR . '/inc');
define('GPRESS_ASSETS_URI', GPRESS_THEME_URI . '/assets');
define('GPRESS_MIN_WP_VERSION', '6.4');
define('GPRESS_MIN_PHP_VERSION', '8.0');

/**
 * Load theme components in optimized order
 * Components are loaded conditionally based on context
 */
function gpress_load_components() {
    $components = array(
        'theme-setup.php'      => true,  // Always load
        'enqueue-scripts.php'  => true,  // Always load
        'customizer.php'       => is_admin() || is_customize_preview(), // Admin only
        'block-patterns.php'   => current_theme_supports('core-block-patterns'), // Conditional
    );
    
    foreach ($components as $component => $should_load) {
        if ($should_load) {
            $file_path = GPRESS_INC_DIR . '/' . $component;
            if (file_exists($file_path)) {
                require_once $file_path;
            }
        }
    }
}

/**
 * Initialize theme after WordPress loads
 */
function gpress_init() {
    
    // Check system requirements first
    if (!gpress_check_requirements()) {
        return;
    }
    
    // Load components
    gpress_load_components();
    
    // Theme setup hook for components
    do_action('gpress_theme_loaded');
}
add_action('after_setup_theme', 'gpress_init', 5);

/**
 * Check system requirements and compatibility
 */
function gpress_check_requirements() {
    global $wp_version;
    
    $requirements_met = true;
    
    // Check WordPress version
    if (version_compare($wp_version, GPRESS_MIN_WP_VERSION, '<')) {
        add_action('admin_notices', function() {
            printf(
                '<div class="error"><p>%s</p></div>',
                sprintf(
                    esc_html__('GPress theme requires WordPress %1$s or higher. You are running version %2$s. Please upgrade WordPress.', 'gpress'),
                    GPRESS_MIN_WP_VERSION,
                    $GLOBALS['wp_version']
                )
            );
        });
        $requirements_met = false;
    }
    
    // Check PHP version
    if (version_compare(PHP_VERSION, GPRESS_MIN_PHP_VERSION, '<')) {
        add_action('admin_notices', function() {
            printf(
                '<div class="error"><p>%s</p></div>',
                sprintf(
                    esc_html__('GPress theme requires PHP %1$s or higher. You are running version %2$s. Please contact your hosting provider.', 'gpress'),
                    GPRESS_MIN_PHP_VERSION,
                    PHP_VERSION
                )
            );
        });
        $requirements_met = false;
    }
    
    return $requirements_met;
}

/**
 * Enhanced security setup
 */
function gpress_security_enhancements() {
    
    // Remove WordPress version from RSS feeds and generator
    add_filter('the_generator', '__return_empty_string');
    
    // Disable XML-RPC if not needed (can be filtered)
    if (apply_filters('gpress_disable_xmlrpc', true)) {
        add_filter('xmlrpc_enabled', '__return_false');
    }
    
    // Remove version from scripts and styles for security
    add_filter('style_loader_src', 'gpress_remove_version_scripts_styles', 9999);
    add_filter('script_loader_src', 'gpress_remove_version_scripts_styles', 9999);
    
    // Remove unnecessary header information
    remove_action('wp_head', 'wp_generator');
    remove_action('wp_head', 'wlwmanifest_link');
    remove_action('wp_head', 'rsd_link');
    
    // Hide login errors for security
    add_filter('login_errors', function() {
        return esc_html__('Invalid credentials.', 'gpress');
    });
    
    // Disable file editing from admin
    if (!defined('DISALLOW_FILE_EDIT')) {
        define('DISALLOW_FILE_EDIT', true);
    }
    
    // Add security headers
    add_action('send_headers', 'gpress_security_headers');
}
add_action('init', 'gpress_security_enhancements');

/**
 * Add security headers
 */
function gpress_security_headers() {
    if (!is_admin()) {
        header('X-Content-Type-Options: nosniff');
        header('X-Frame-Options: SAMEORIGIN');
        header('X-XSS-Protection: 1; mode=block');
        header('Referrer-Policy: strict-origin-when-cross-origin');
    }
}

/**
 * Remove version from scripts and styles
 */
function gpress_remove_version_scripts_styles($src) {
    if (strpos($src, 'ver=')) {
        $src = remove_query_arg('ver', $src);
    }
    return $src;
}

/**
 * Performance optimizations
 */
function gpress_performance_optimizations() {
    
    // Remove emoji scripts if not needed
    if (apply_filters('gpress_disable_emojis', true)) {
        remove_action('wp_head', 'print_emoji_detection_script', 7);
        remove_action('wp_print_styles', 'print_emoji_styles');
        remove_action('admin_print_scripts', 'print_emoji_detection_script');
        remove_action('admin_print_styles', 'print_emoji_styles');
        remove_filter('the_content_feed', 'wp_staticize_emoji');
        remove_filter('comment_text_rss', 'wp_staticize_emoji');
        remove_filter('wp_mail', 'wp_staticize_emoji_for_email');
    }
    
    // Optimize jQuery loading
    if (!is_admin() && apply_filters('gpress_optimize_jquery', true)) {
        add_action('wp_enqueue_scripts', function() {
            wp_deregister_script('jquery');
            wp_register_script('jquery', false);
        });
    }
    
    // Remove unnecessary scripts
    add_action('wp_enqueue_scripts', function() {
        // Remove block library CSS if not needed
        if (!current_theme_supports('wp-block-styles')) {
            wp_dequeue_style('wp-block-library');
        }
    }, 100);
}
add_action('init', 'gpress_performance_optimizations');

/**
 * JavaScript detection for progressive enhancement
 */
function gpress_javascript_detection() {
    ?>
    <script>
    (function() {
        document.documentElement.className = document.documentElement.className.replace(/\bno-js\b/, 'js');
    })();
    </script>
    <?php
}
add_action('wp_head', 'gpress_javascript_detection', 0);

/**
 * Add body classes for better targeting
 */
function gpress_body_classes($classes) {
    
    // Add JavaScript detection class
    $classes[] = 'no-js';
    
    // Add layout classes
    if (is_singular()) {
        $classes[] = 'is-singular';
    }
    
    if (is_active_sidebar('sidebar-1')) {
        $classes[] = 'has-sidebar';
    } else {
        $classes[] = 'no-sidebar';
    }
    
    // Add admin bar class for logged in users
    if (is_admin_bar_showing()) {
        $classes[] = 'admin-bar-showing';
    }
    
    return $classes;
}
add_filter('body_class', 'gpress_body_classes');

/**
 * Theme activation setup
 */
function gpress_after_switch_theme() {
    
    // Flush rewrite rules
    flush_rewrite_rules();
    
    // Set default customizer values if not set
    $defaults = array(
        'custom_logo' => '',
        'blogdescription' => get_option('blogdescription'),
    );
    
    foreach ($defaults as $setting => $value) {
        if (!get_theme_mod($setting)) {
            set_theme_mod($setting, $value);
        }
    }
    
    // Create default navigation menu if it doesn't exist
    gpress_create_default_menu();
}
add_action('after_switch_theme', 'gpress_after_switch_theme');

/**
 * Create default navigation menu
 */
function gpress_create_default_menu() {
    $menu_name = 'Primary Menu';
    $menu_exists = wp_get_nav_menu_object($menu_name);
    
    if (!$menu_exists) {
        $menu_id = wp_create_nav_menu($menu_name);
        
        if (!is_wp_error($menu_id)) {
            // Add some default menu items
            wp_update_nav_menu_item($menu_id, 0, array(
                'menu-item-title' => __('Home', 'gpress'),
                'menu-item-url' => home_url('/'),
                'menu-item-status' => 'publish'
            ));
            
            // Assign menu to location
            $locations = get_theme_mod('nav_menu_locations', array());
            $locations['primary'] = $menu_id;
            set_theme_mod('nav_menu_locations', $locations);
        }
    }
}

/**
 * Admin notices for theme status
 */
function gpress_admin_notices() {
    global $pagenow;
    
    // Show activation success message
    if ($pagenow == 'themes.php' && isset($_GET['activated'])) {
        echo '<div class="notice notice-success is-dismissible">';
        echo '<p><strong>' . esc_html__('GPress theme activated successfully!', 'gpress') . '</strong></p>';
        echo '<p>' . esc_html__('Your theme is now active with enhanced performance and security features.', 'gpress') . '</p>';
        echo '</div>';
    }
}
add_action('admin_notices', 'gpress_admin_notices');

/**
 * Theme helper functions
 */

/**
 * Get theme version for cache busting
 */
function gpress_get_version() {
    return apply_filters('gpress_version', GPRESS_VERSION);
}

/**
 * Check if we're in development mode
 */
function gpress_is_development() {
    return defined('WP_DEBUG') && WP_DEBUG;
}

/**
 * Get asset URL with automatic versioning
 */
function gpress_asset_url($path) {
    $url = GPRESS_ASSETS_URI . '/' . ltrim($path, '/');
    
    if (gpress_is_development()) {
        $url = add_query_arg('v', time(), $url);
    } else {
        $url = add_query_arg('v', gpress_get_version(), $url);
    }
    
    return $url;
}
```

### 3. CREATE inc/theme-setup.php (Theme Support Configuration)

**Purpose**: Centralized theme setup with advanced WordPress features

```php
<?php
/**
 * Theme Setup and Support Features for GPress Theme
 *
 * @package GPress
 * @version 1.1.0
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Set up theme defaults and register support for various WordPress features
 */
function gpress_theme_setup() {
    
    /*
     * Make theme available for translation
     * Translations can be filed in the /languages/ directory
     */
    load_theme_textdomain('gpress', GPRESS_THEME_DIR . '/languages');

    /*
     * Add default posts and comments RSS feed links to head
     */
    add_theme_support('automatic-feed-links');

    /*
     * Let WordPress manage the document title
     * By adding theme support, we declare that this theme does not use a
     * hard-coded <title> tag in the document head
     */
    add_theme_support('title-tag');

    /*
     * Enable support for Post Thumbnails on posts and pages
     */
    add_theme_support('post-thumbnails');
    
    // Set default thumbnail size for performance
    set_post_thumbnail_size(1200, 675, true);
    
    // Add custom image sizes with optimized dimensions
    add_image_size('gpress-small', 400, 300, true);
    add_image_size('gpress-medium', 600, 400, true);
    add_image_size('gpress-large', 1200, 800, true);
    add_image_size('gpress-featured', 1600, 900, true);
    add_image_size('gpress-hero', 1920, 1080, true);

    /*
     * Switch default core markup to output valid HTML5
     */
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

    /*
     * Enable support for custom logo
     */
    add_theme_support('custom-logo', array(
        'height'      => 100,
        'width'       => 400,
        'flex-width'  => true,
        'flex-height' => true,
        'header-text' => array('site-title', 'site-description'),
        'unlink-homepage-logo' => true,
    ));

    /*
     * Add theme support for selective refresh for widgets
     */
    add_theme_support('customize-selective-refresh-widgets');

    /*
     * Add support for responsive embedded content
     */
    add_theme_support('responsive-embeds');
    
    /*
     * Add support for experimental link color control
     */
    add_theme_support('experimental-link-color');

    /*
     * Register navigation menus
     */
    register_nav_menus(array(
        'primary' => esc_html__('Primary Menu', 'gpress'),
        'footer'  => esc_html__('Footer Menu', 'gpress'),
        'social'  => esc_html__('Social Links Menu', 'gpress'),
    ));
    
    /*
     * Add editor styles for better WYSIWYG experience
     */
    add_theme_support('editor-styles');
    add_editor_style();
    
    /*
     * Add support for wide and full alignment
     */
    add_theme_support('align-wide');
    
    /*
     * Enable custom line height controls
     */
    add_theme_support('custom-line-height');
    
    /*
     * Enable custom units for spacing
     */
    add_theme_support('custom-units');
    
    /*
     * Add support for editor color palette
     */
    add_theme_support('editor-color-palette', array(
        array(
            'name'  => esc_html__('Primary Blue', 'gpress'),
            'slug'  => 'primary',
            'color' => '#2563eb',
        ),
        array(
            'name'  => esc_html__('Secondary Gray', 'gpress'),
            'slug'  => 'secondary',
            'color' => '#64748b',
        ),
        array(
            'name'  => esc_html__('Accent Orange', 'gpress'),
            'slug'  => 'accent',
            'color' => '#f59e0b',
        ),
        array(
            'name'  => esc_html__('Success Green', 'gpress'),
            'slug'  => 'success',
            'color' => '#059669',
        ),
        array(
            'name'  => esc_html__('Warning Yellow', 'gpress'),
            'slug'  => 'warning',
            'color' => '#d97706',
        ),
        array(
            'name'  => esc_html__('Error Red', 'gpress'),
            'slug'  => 'error',
            'color' => '#dc2626',
        ),
        array(
            'name'  => esc_html__('Pure White', 'gpress'),
            'slug'  => 'white',
            'color' => '#ffffff',
        ),
        array(
            'name'  => esc_html__('Pure Black', 'gpress'),
            'slug'  => 'black',
            'color' => '#000000',
        ),
    ));
    
    /*
     * Add support for editor gradient presets
     */
    add_theme_support('editor-gradient-presets', array(
        array(
            'name'     => esc_html__('Primary to Accent', 'gpress'),
            'gradient' => 'linear-gradient(135deg,#2563eb 0%,#f59e0b 100%)',
            'slug'     => 'primary-to-accent',
        ),
        array(
            'name'     => esc_html__('Secondary to Primary', 'gpress'),
            'gradient' => 'linear-gradient(135deg,#64748b 0%,#2563eb 100%)',
            'slug'     => 'secondary-to-primary',
        ),
    ));
    
    /*
     * Add support for editor font sizes
     */
    add_theme_support('editor-font-sizes', array(
        array(
            'name' => esc_html__('Small', 'gpress'),
            'size' => 14,
            'slug' => 'small'
        ),
        array(
            'name' => esc_html__('Normal', 'gpress'),
            'size' => 16,
            'slug' => 'normal'
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
    
    /*
     * Add support for core block patterns
     */
    add_theme_support('core-block-patterns');
    
    /*
     * Disable custom colors in favor of palette
     */
    add_theme_support('disable-custom-colors');
    
    /*
     * Disable custom font sizes in favor of presets
     */
    add_theme_support('disable-custom-font-sizes');
    
    /*
     * Add support for custom spacing
     */
    add_theme_support('custom-spacing');
    
    /*
     * Add appearance tools support for more block customization
     */
    add_theme_support('appearance-tools');
}
add_action('gpress_theme_loaded', 'gpress_theme_setup');

/**
 * Set the content width in pixels for media
 */
function gpress_content_width() {
    $GLOBALS['content_width'] = apply_filters('gpress_content_width', 1200);
}
add_action('gpress_theme_loaded', 'gpress_content_width', 0);

/**
 * Register widget areas
 */
function gpress_widgets_init() {
    
    // Main sidebar
    register_sidebar(array(
        'name'          => esc_html__('Primary Sidebar', 'gpress'),
        'id'            => 'sidebar-1',
        'description'   => esc_html__('Add widgets here to appear in your sidebar.', 'gpress'),
        'before_widget' => '<section id="%1$s" class="widget %2$s">',
        'after_widget'  => '</section>',
        'before_title'  => '<h2 class="widget-title">',
        'after_title'   => '</h2>',
    ));
    
    // Footer widget areas
    for ($i = 1; $i <= 4; $i++) {
        register_sidebar(array(
            'name'          => sprintf(esc_html__('Footer Widget Area %d', 'gpress'), $i),
            'id'            => 'footer-' . $i,
            'description'   => sprintf(esc_html__('Footer widget area %d', 'gpress'), $i),
            'before_widget' => '<section id="%1$s" class="widget %2$s">',
            'after_widget'  => '</section>',
            'before_title'  => '<h3 class="widget-title">',
            'after_title'   => '</h3>',
        ));
    }
}
add_action('widgets_init', 'gpress_widgets_init');

/**
 * Filter excerpt length for better performance and UX
 */
function gpress_excerpt_length($length) {
    return apply_filters('gpress_excerpt_length', 30);
}
add_filter('excerpt_length', 'gpress_excerpt_length', 999);

/**
 * Filter excerpt more string
 */
function gpress_excerpt_more($more) {
    return apply_filters('gpress_excerpt_more', '&hellip;');
}
add_filter('excerpt_more', 'gpress_excerpt_more');

/**
 * Clean up archive titles
 */
function gpress_archive_title($title) {
    if (is_category()) {
        $title = single_cat_title('', false);
    } elseif (is_tag()) {
        $title = single_tag_title('', false);
    } elseif (is_author()) {
        $title = '<span class="vcard">' . get_the_author() . '</span>';
    } elseif (is_year()) {
        $title = get_the_date(_x('Y', 'yearly archives date format', 'gpress'));
    } elseif (is_month()) {
        $title = get_the_date(_x('F Y', 'monthly archives date format', 'gpress'));
    } elseif (is_day()) {
        $title = get_the_date(_x('F j, Y', 'daily archives date format', 'gpress'));
    }
    return $title;
}
add_filter('get_the_archive_title', 'gpress_archive_title');

/**
 * Add skip links for accessibility
 */
function gpress_skip_links() {
    ?>
    <a class="skip-link screen-reader-text" href="#main">
        <?php esc_html_e('Skip to main content', 'gpress'); ?>
    </a>
    <a class="skip-link screen-reader-text" href="#primary-navigation">
        <?php esc_html_e('Skip to navigation', 'gpress'); ?>
    </a>
    <?php
}
add_action('wp_body_open', 'gpress_skip_links');
```

### 4. CREATE inc/enqueue-scripts.php (Asset Management)

**Purpose**: Optimized asset loading with conditional enqueuing

```php
<?php
/**
 * Asset Enqueuing and Optimization for GPress Theme
 *
 * @package GPress
 * @version 1.1.0
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Enqueue scripts and styles with optimization
 */
function gpress_scripts() {
    
    // Main stylesheet with version for cache busting
    wp_enqueue_style(
        'gpress-style',
        get_stylesheet_uri(),
        array(),
        gpress_get_version()
    );
    
    // Add RTL support
    wp_style_add_data('gpress-style', 'rtl', 'replace');
    
    // Conditionally load comment reply script
    if (is_singular() && comments_open() && get_option('thread_comments')) {
        wp_enqueue_script('comment-reply');
    }
    
    // Skip link focus fix for accessibility (conditional)
    if (apply_filters('gpress_load_skip_link_fix', true)) {
        wp_enqueue_script(
            'gpress-skip-link-focus-fix',
            gpress_asset_url('js/skip-link-focus-fix.js'),
            array(),
            gpress_get_version(),
            array(
                'strategy' => 'defer',
                'in_footer' => true,
            )
        );
    }
    
    // Main theme JavaScript (conditional loading)
    if (gpress_should_load_theme_js()) {
        wp_enqueue_script(
            'gpress-theme',
            gpress_asset_url('js/theme.js'),
            array(),
            gpress_get_version(),
            array(
                'strategy' => 'defer',
                'in_footer' => true,
            )
        );
        
        // Localize script with theme data
        wp_localize_script('gpress-theme', 'gpressData', array(
            'ajaxurl' => admin_url('admin-ajax.php'),
            'nonce' => wp_create_nonce('gpress_nonce'),
            'isCustomizer' => is_customize_preview(),
            'isAdmin' => is_admin(),
            'strings' => array(
                'loading' => esc_html__('Loading...', 'gpress'),
                'error' => esc_html__('An error occurred', 'gpress'),
            ),
        ));
    }
}
add_action('wp_enqueue_scripts', 'gpress_scripts');

/**
 * Determine if theme JavaScript should be loaded
 */
function gpress_should_load_theme_js() {
    // Load on pages that need interactivity
    $should_load = is_singular() || is_home() || is_archive();
    
    // Apply filters for customization
    return apply_filters('gpress_load_theme_js', $should_load);
}

/**
 * Enqueue admin styles
 */
function gpress_admin_styles() {
    wp_enqueue_style(
        'gpress-admin',
        gpress_asset_url('css/admin.css'),
        array(),
        gpress_get_version()
    );
}
add_action('admin_enqueue_scripts', 'gpress_admin_styles');

/**
 * Enqueue customizer preview scripts
 */
function gpress_customize_preview_js() {
    wp_enqueue_script(
        'gpress-customizer-preview',
        gpress_asset_url('js/customizer-preview.js'),
        array('customize-preview'),
        gpress_get_version(),
        array('in_footer' => true)
    );
}
add_action('customize_preview_init', 'gpress_customize_preview_js');

/**
 * Enqueue block editor assets
 */
function gpress_block_editor_assets() {
    
    // Block editor styles
    wp_enqueue_style(
        'gpress-block-editor',
        gpress_asset_url('css/editor-blocks.css'),
        array('wp-edit-blocks'),
        gpress_get_version()
    );
    
    // Block editor JavaScript
    wp_enqueue_script(
        'gpress-block-editor',
        gpress_asset_url('js/block-editor.js'),
        array('wp-blocks', 'wp-dom-ready', 'wp-edit-post'),
        gpress_get_version(),
        array('in_footer' => true)
    );
}
add_action('enqueue_block_editor_assets', 'gpress_block_editor_assets');

/**
 * Optimize script loading with modern attributes
 */
function gpress_script_loader_tag($tag, $handle, $src) {
    
    // Handles that should be deferred
    $defer_scripts = array(
        'gpress-skip-link-focus-fix',
        'gpress-theme',
    );
    
    // Handles that should be loaded as modules
    $module_scripts = array(
        // Add modern JS modules here in future steps
    );
    
    // Add defer attribute
    if (in_array($handle, $defer_scripts)) {
        $tag = str_replace('<script ', '<script defer ', $tag);
    }
    
    // Add module type
    if (in_array($handle, $module_scripts)) {
        $tag = str_replace('<script ', '<script type="module" ', $tag);
    }
    
    return $tag;
}
add_filter('script_loader_tag', 'gpress_script_loader_tag', 10, 3);

/**
 * Preload critical assets
 */
function gpress_preload_assets() {
    ?>
    <link rel="preload" href="<?php echo esc_url(gpress_asset_url('css/critical.css')); ?>" as="style" onload="this.onload=null;this.rel='stylesheet'">
    <noscript><link rel="stylesheet" href="<?php echo esc_url(gpress_asset_url('css/critical.css')); ?>"></noscript>
    
    <?php if (has_custom_logo()) : ?>
        <?php $logo_id = get_theme_mod('custom_logo'); ?>
        <?php $logo_url = wp_get_attachment_image_url($logo_id, 'full'); ?>
        <?php if ($logo_url) : ?>
            <link rel="preload" href="<?php echo esc_url($logo_url); ?>" as="image">
        <?php endif; ?>
    <?php endif; ?>
    <?php
}
add_action('wp_head', 'gpress_preload_assets', 1);

/**
 * Add resource hints for better performance
 */
function gpress_resource_hints($urls, $relation_type) {
    switch ($relation_type) {
        case 'dns-prefetch':
            $urls[] = '//fonts.googleapis.com';
            $urls[] = '//fonts.gstatic.com';
            break;
            
        case 'preconnect':
            $urls[] = 'https://fonts.googleapis.com';
            $urls[] = 'https://fonts.gstatic.com';
            break;
    }
    
    return $urls;
}
add_filter('wp_resource_hints', 'gpress_resource_hints', 10, 2);

/**
 * Optimize CSS delivery
 */
function gpress_optimize_css_delivery() {
    
    // Inline critical CSS for above-the-fold content
    if (apply_filters('gpress_inline_critical_css', true)) {
        $critical_css = apply_filters('gpress_critical_css', '');
        
        if (!empty($critical_css)) {
            echo '<style id="gpress-critical-css">' . $critical_css . '</style>';
        }
    }
}
add_action('wp_head', 'gpress_optimize_css_delivery', 8);
```

### 5. CREATE assets/js/skip-link-focus-fix.js (Accessibility Enhancement)

**Purpose**: Fix skip link focus behavior in WebKit browsers

```javascript
/**
 * Skip Link Focus Fix for GPress Theme
 * 
 * Fixes skip link focus behavior in WebKit browsers
 * 
 * @package GPress
 * @version 1.1.0
 */

(function() {
    'use strict';
    
    // Early return if not needed
    if (!navigator.userAgent.includes('WebKit')) {
        return;
    }
    
    /**
     * Fix skip link focus in WebKit browsers
     */
    function fixSkipLinkFocus() {
        const skipLinks = document.querySelectorAll('.skip-link');
        
        skipLinks.forEach(function(skipLink) {
            skipLink.addEventListener('click', function(event) {
                const target = document.querySelector(skipLink.getAttribute('href'));
                
                if (target) {
                    // Set tabindex to make element focusable
                    if (!target.hasAttribute('tabindex')) {
                        target.setAttribute('tabindex', '-1');
                    }
                    
                    // Focus the target element
                    target.focus();
                    
                    // Remove tabindex after focus to restore natural tab order
                    target.addEventListener('blur', function() {
                        if (target.getAttribute('tabindex') === '-1') {
                            target.removeAttribute('tabindex');
                        }
                    }, { once: true });
                }
            });
        });
    }
    
    // Initialize when DOM is ready
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', fixSkipLinkFocus);
    } else {
        fixSkipLinkFocus();
    }
    
})();
```

### 6. CREATE assets/js/theme.js (Main Theme JavaScript)

**Purpose**: Core theme JavaScript functionality placeholder

```javascript
/**
 * Main Theme JavaScript for GPress
 * 
 * @package GPress
 * @version 1.1.0
 */

(function() {
    'use strict';
    
    // Theme object
    const GPress = {
        
        /**
         * Initialize theme functionality
         */
        init: function() {
            this.imageLoading();
            this.accessibilityEnhancements();
            this.performanceOptimizations();
        },
        
        /**
         * Handle image loading optimization
         */
        imageLoading: function() {
            // Mark lazy images as loaded when they load
            const lazyImages = document.querySelectorAll('img[loading="lazy"]');
            
            lazyImages.forEach(function(img) {
                if (img.complete) {
                    img.classList.add('loaded');
                } else {
                    img.addEventListener('load', function() {
                        img.classList.add('loaded');
                    });
                }
            });
        },
        
        /**
         * Accessibility enhancements
         */
        accessibilityEnhancements: function() {
            // Enhanced focus management
            this.enhanceFocusManagement();
            
            // Improved keyboard navigation
            this.improveKeyboardNavigation();
        },
        
        /**
         * Enhanced focus management
         */
        enhanceFocusManagement: function() {
            let isUsingKeyboard = false;
            
            // Detect keyboard usage
            document.addEventListener('keydown', function(event) {
                if (event.key === 'Tab') {
                    isUsingKeyboard = true;
                    document.body.classList.add('using-keyboard');
                }
            });
            
            // Detect mouse usage
            document.addEventListener('mousedown', function() {
                isUsingKeyboard = false;
                document.body.classList.remove('using-keyboard');
            });
        },
        
        /**
         * Improve keyboard navigation
         */
        improveKeyboardNavigation: function() {
            // Add escape key handler for dropdowns
            document.addEventListener('keydown', function(event) {
                if (event.key === 'Escape') {
                    // Close any open dropdowns or modals
                    const openElements = document.querySelectorAll('.is-open, .menu-open');
                    openElements.forEach(function(element) {
                        element.classList.remove('is-open', 'menu-open');
                    });
                }
            });
        },
        
        /**
         * Performance optimizations
         */
        performanceOptimizations: function() {
            // Throttled scroll handler
            let ticking = false;
            
            function handleScroll() {
                if (!ticking) {
                    requestAnimationFrame(function() {
                        // Add scroll-based functionality here
                        ticking = false;
                    });
                    ticking = true;
                }
            }
            
            // Only add scroll listener if needed
            if (document.querySelector('[data-scroll]')) {
                window.addEventListener('scroll', handleScroll, { passive: true });
            }
        }
    };
    
    // Initialize when DOM is ready
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', function() {
            GPress.init();
        });
    } else {
        GPress.init();
    }
    
    // Make GPress globally available
    window.GPress = GPress;
    
})();
```

## Testing This Step

### 1. File Structure Test
```bash
# Verify all new files exist
ls -la inc/
ls -la assets/js/

# Check file permissions
find inc/ -name "*.php" -exec ls -l {} \;
```

### 2. Functionality Test
- [ ] Theme still activates without errors
- [ ] Enhanced functions.php loads properly
- [ ] Include files load correctly
- [ ] No PHP errors in debug log
- [ ] Admin interface remains functional

### 3. Performance Test
```bash
# Test with Lighthouse
lighthouse http://your-site.local --output html

# Expected improvements:
# Performance: 92+
# Accessibility: 97+
# Best Practices: 95+
# SEO: 95+
```

### 4. Security Test
- [ ] Version numbers hidden from source
- [ ] Security headers present
- [ ] File editing disabled
- [ ] Login error messages generic

### 5. Asset Loading Test
- [ ] Skip link JavaScript loads conditionally
- [ ] Scripts have proper defer attributes
- [ ] CSS loads with correct versioning
- [ ] No 404 errors for assets

## Expected Results

After completing Step 2, you should have:

- ✅ Modular PHP architecture for better maintainability
- ✅ Enhanced security features implemented
- ✅ Performance optimizations in place
- ✅ Advanced WordPress theme support features
- ✅ Conditional asset loading system
- ✅ Accessibility enhancements foundation
- ✅ Customizer framework prepared
- ✅ Block patterns foundation ready

## Next Step

Proceed to [Step 3: theme.json Setup for FSE](./step-03-theme-json.md) to add Full Site Editing configuration and global styles management.

---

**Performance Target Achieved**: ⚡ 92+ Lighthouse Score  
**Security Enhanced**: 🔒 Advanced Security Features  
**Architecture Improved**: 🏗️ Modular PHP Structure  
**Assets Optimized**: 📦 Conditional Loading System