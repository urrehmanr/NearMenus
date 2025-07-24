# Step 1: Foundation Setup & Basic Files

## Overview
This step establishes the basic foundation for the **GPress** theme. We'll create only the essential files needed for a minimal working WordPress theme that can be activated and tested, while implementing performance optimizations and accessibility features from the start.

## Objectives
- Create the basic theme directory structure with optimal organization
- Set up essential WordPress theme files with performance considerations
- Ensure the theme can be activated without errors
- Establish version control and documentation
- Implement basic accessibility and performance optimizations
- Create a solid foundation for progressive enhancement

## What You'll Learn
- WordPress theme structure and requirements
- Performance-first CSS architecture
- Basic accessibility implementation
- Security best practices from the start
- Version control setup for theme development
- Responsive design foundations

## Files Structure for This Step

### 📁 **Files to CREATE** (New Files)
```
gpress/                      # Main theme directory
├── style.css               # Theme header + critical styles
├── index.php               # Minimal fallback template
├── functions.php           # Core theme functionality
├── README.md               # Documentation and setup guide
├── .gitignore              # Version control exclusions
├── screenshot.png          # Theme preview (create placeholder)
└── assets/                 # Asset organization structure
    └── images/             # Image assets directory
        └── .gitkeep        # Keep directory in version control
```

### 📝 **Files to UPDATE** (Existing Files)
```
None - This is the foundation step, all files are new
```

### 🎯 **Optimization Features Implemented**
- Critical CSS inlining strategy
- Mobile-first responsive approach
- Accessibility-first design patterns
- Performance-optimized asset structure
- Security hardening from start
- SEO-ready markup foundation

## Step-by-Step Implementation

### 1. Create Theme Directory Structure

```bash
# Create main theme directory
mkdir gpress
cd gpress

# Create asset directories
mkdir -p assets/images

# Create .gitkeep for empty directories
touch assets/images/.gitkeep
```

### 2. style.css (Theme Header & Critical Styles)

**Purpose**: WordPress theme identification + critical performance styles

```css
/*
Theme Name: GPress
Description: A high-performance, accessibility-focused WordPress blog theme built for 2025's web standards. Features Full Site Editing, advanced performance optimizations, and WCAG 2.1 AA accessibility compliance.
Author: Your Name
Version: 1.0.0
Requires at least: 6.4
Tested up to: 6.5
Requires PHP: 8.0
License: GPL v2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html
Text Domain: gpress
Domain Path: /languages
Tags: blog, one-column, two-columns, accessibility-ready, custom-header, custom-menu, editor-style, featured-images, full-site-editing, rtl-language-support, sticky-post, translation-ready, block-patterns, block-styles, wide-blocks, performance-optimized
Network: false
Update URI: false
*/

/**
 * Critical CSS - Above the Fold Styles
 * These styles are inlined to prevent render-blocking
 */

/* CSS Custom Properties for consistent theming */
:root {
    /* Color System */
    --gp-color-primary: #2563eb;
    --gp-color-primary-dark: #1e40af;
    --gp-color-secondary: #64748b;
    --gp-color-accent: #f59e0b;
    --gp-color-text: #1e293b;
    --gp-color-text-light: #64748b;
    --gp-color-background: #ffffff;
    --gp-color-surface: #f8fafc;
    --gp-color-border: #e2e8f0;
    --gp-color-success: #059669;
    --gp-color-warning: #d97706;
    --gp-color-error: #dc2626;
    
    /* Typography System */
    --gp-font-family-base: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Oxygen-Sans, Ubuntu, Cantarell, "Helvetica Neue", sans-serif;
    --gp-font-family-heading: var(--gp-font-family-base);
    --gp-font-family-mono: ui-monospace, "SF Mono", Monaco, "Cascadia Code", "Roboto Mono", Consolas, "Courier New", monospace;
    
    /* Font Sizes (Responsive) */
    --gp-font-size-xs: clamp(0.75rem, 0.7rem + 0.25vw, 0.875rem);
    --gp-font-size-sm: clamp(0.875rem, 0.8rem + 0.375vw, 1rem);
    --gp-font-size-base: clamp(1rem, 0.95rem + 0.25vw, 1.125rem);
    --gp-font-size-lg: clamp(1.125rem, 1.05rem + 0.375vw, 1.25rem);
    --gp-font-size-xl: clamp(1.25rem, 1.15rem + 0.5vw, 1.5rem);
    --gp-font-size-2xl: clamp(1.5rem, 1.35rem + 0.75vw, 1.875rem);
    --gp-font-size-3xl: clamp(1.875rem, 1.65rem + 1.125vw, 2.25rem);
    --gp-font-size-4xl: clamp(2.25rem, 1.95rem + 1.5vw, 3rem);
    
    /* Spacing System */
    --gp-spacing-xs: 0.25rem;
    --gp-spacing-sm: 0.5rem;
    --gp-spacing-md: 1rem;
    --gp-spacing-lg: 1.5rem;
    --gp-spacing-xl: 2rem;
    --gp-spacing-2xl: 3rem;
    --gp-spacing-3xl: 4rem;
    
    /* Layout */
    --gp-container-max-width: 1200px;
    --gp-content-max-width: 65ch;
    --gp-border-radius: 0.375rem;
    --gp-border-radius-lg: 0.5rem;
    
    /* Transitions */
    --gp-transition-fast: 150ms ease;
    --gp-transition-normal: 250ms ease;
    --gp-transition-slow: 350ms ease;
    
    /* Z-index System */
    --gp-z-dropdown: 1000;
    --gp-z-sticky: 1010;
    --gp-z-fixed: 1020;
    --gp-z-modal: 1030;
    --gp-z-popover: 1040;
    --gp-z-tooltip: 1050;
}

/* High Contrast Mode Support */
@media (prefers-contrast: high) {
    :root {
        --gp-color-primary: #0052cc;
        --gp-color-text: #000000;
        --gp-color-background: #ffffff;
        --gp-color-border: #000000;
    }
}

/* Dark Mode Support */
@media (prefers-color-scheme: dark) {
    :root {
        --gp-color-primary: #60a5fa;
        --gp-color-primary-dark: #3b82f6;
        --gp-color-text: #f1f5f9;
        --gp-color-text-light: #94a3b8;
        --gp-color-background: #0f172a;
        --gp-color-surface: #1e293b;
        --gp-color-border: #334155;
    }
}

/* Reduced Motion Support */
@media (prefers-reduced-motion: reduce) {
    :root {
        --gp-transition-fast: 0ms;
        --gp-transition-normal: 0ms;
        --gp-transition-slow: 0ms;
    }
    
    *,
    *::before,
    *::after {
        animation-duration: 0.01ms !important;
        animation-iteration-count: 1 !important;
        transition-duration: 0.01ms !important;
        scroll-behavior: auto !important;
    }
}

/* Modern CSS Reset with Performance Optimizations */
*,
*::before,
*::after {
    box-sizing: border-box;
    margin: 0;
    padding: 0;
}

/* Optimize font rendering */
html {
    font-size: 100%;
    line-height: 1.6;
    scroll-behavior: smooth;
    text-rendering: optimizeSpeed;
    -webkit-font-smoothing: antialiased;
    -moz-osx-font-smoothing: grayscale;
    font-feature-settings: "kern" 1, "liga" 1;
}

/* Performance-optimized body styles */
body {
    font-family: var(--gp-font-family-base);
    font-size: var(--gp-font-size-base);
    line-height: 1.6;
    color: var(--gp-color-text);
    background-color: var(--gp-color-background);
    min-height: 100vh;
    display: flex;
    flex-direction: column;
    word-wrap: break-word;
    overflow-wrap: break-word;
}

/* Skip Links for Accessibility */
.skip-link {
    position: absolute;
    left: -9999px;
    top: auto;
    width: 1px;
    height: 1px;
    overflow: hidden;
    background: var(--gp-color-primary);
    color: white;
    padding: var(--gp-spacing-sm) var(--gp-spacing-md);
    text-decoration: none;
    border-radius: var(--gp-spacing-xs);
    font-weight: 600;
    z-index: var(--gp-z-tooltip);
}

.skip-link:focus {
    position: absolute;
    left: var(--gp-spacing-md);
    top: var(--gp-spacing-md);
    width: auto;
    height: auto;
    overflow: visible;
}

/* Screen Reader Only Content */
.screen-reader-text {
    position: absolute !important;
    clip: rect(1px, 1px, 1px, 1px);
    clip-path: inset(50%);
    width: 1px;
    height: 1px;
    overflow: hidden;
    word-wrap: normal !important;
}

/* Focus Styles for Accessibility */
:focus {
    outline: 2px solid var(--gp-color-primary);
    outline-offset: 2px;
}

:focus:not(:focus-visible) {
    outline: none;
}

:focus-visible {
    outline: 2px solid var(--gp-color-primary);
    outline-offset: 2px;
}

/* Optimized Link Styles */
a {
    color: var(--gp-color-primary);
    text-decoration: none;
    transition: color var(--gp-transition-fast);
}

a:hover,
a:focus {
    color: var(--gp-color-primary-dark);
    text-decoration: underline;
}

/* Typography Hierarchy */
h1, h2, h3, h4, h5, h6 {
    font-family: var(--gp-font-family-heading);
    font-weight: 600;
    line-height: 1.3;
    margin-bottom: var(--gp-spacing-md);
    color: var(--gp-color-text);
    text-wrap: balance;
}

h1 { font-size: var(--gp-font-size-3xl); }
h2 { font-size: var(--gp-font-size-2xl); }
h3 { font-size: var(--gp-font-size-xl); }
h4 { font-size: var(--gp-font-size-lg); }
h5 { font-size: var(--gp-font-size-base); }
h6 { font-size: var(--gp-font-size-sm); }

/* Content Elements */
p {
    margin-bottom: var(--gp-spacing-md);
    max-width: var(--gp-content-max-width);
}

/* Basic Layout Container */
.wp-site-blocks {
    max-width: var(--gp-container-max-width);
    margin: 0 auto;
    padding: 0 var(--gp-spacing-md);
}

/* Main Content Area */
main {
    flex: 1;
    min-height: 0;
}

/* Performance: Optimize image loading */
img {
    max-width: 100%;
    height: auto;
    border-style: none;
}

/* Lazy loading optimization */
img[loading="lazy"] {
    opacity: 0;
    transition: opacity var(--gp-transition-normal);
}

img[loading="lazy"].loaded {
    opacity: 1;
}

/* Form Elements */
button,
input,
select,
textarea {
    font-family: inherit;
    font-size: 100%;
    line-height: 1.6;
    margin: 0;
}

button {
    background: var(--gp-color-primary);
    color: white;
    border: none;
    padding: var(--gp-spacing-sm) var(--gp-spacing-md);
    border-radius: var(--gp-border-radius);
    cursor: pointer;
    transition: background-color var(--gp-transition-fast);
}

button:hover,
button:focus {
    background: var(--gp-color-primary-dark);
}

/* Print Styles */
@media print {
    *,
    *::before,
    *::after {
        background: transparent !important;
        color: black !important;
        box-shadow: none !important;
        text-shadow: none !important;
    }
    
    a,
    a:visited {
        text-decoration: underline;
    }
    
    .skip-link,
    .screen-reader-text {
        display: none !important;
    }
}

/* Progressive Enhancement Base */
.no-js {
    /* Fallback styles for when JavaScript is disabled */
}

.js {
    /* Enhanced styles when JavaScript is available */
}
```

### 3. index.php (Minimal Fallback Template)

**Purpose**: Fallback template with semantic structure and accessibility

```php
<?php
/**
 * The main template file for GPress theme
 * 
 * This is the most generic template file in a WordPress theme
 * and one of the two required files for a theme (the other being style.css).
 * It is used to display a page when nothing more specific matches a query.
 * E.g., it puts together the home page when no home.php file exists.
 *
 * @package GPress
 * @since 1.0.0
 * @version 1.0.0
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

get_header(); ?>

<main id="main" class="site-main" role="main" aria-label="<?php esc_attr_e('Main content', 'gpress'); ?>">
    
    <?php if (have_posts()) : ?>
        
        <header class="page-header">
            <?php if (is_home() && !is_front_page()) : ?>
                <h1 class="page-title screen-reader-text">
                    <?php single_post_title(); ?>
                </h1>
            <?php endif; ?>
        </header>

        <div class="posts-container">
            <?php while (have_posts()) : the_post(); ?>
                
                <article id="post-<?php the_ID(); ?>" <?php post_class('post-entry'); ?> role="article">
                    
                    <header class="entry-header">
                        <?php
                        if (is_singular()) :
                            the_title('<h1 class="entry-title">', '</h1>');
                        else :
                            the_title('<h2 class="entry-title"><a href="' . esc_url(get_permalink()) . '" rel="bookmark">', '</a></h2>');
                        endif;
                        ?>
                        
                        <?php if (!is_page()) : ?>
                            <div class="entry-meta">
                                <span class="posted-on">
                                    <time class="entry-date published" datetime="<?php echo esc_attr(get_the_date('c')); ?>">
                                        <?php echo esc_html(get_the_date()); ?>
                                    </time>
                                </span>
                                <span class="byline">
                                    <span class="screen-reader-text"><?php esc_html_e('Author:', 'gpress'); ?></span>
                                    <a class="url fn n" href="<?php echo esc_url(get_author_posts_url(get_the_author_meta('ID'))); ?>">
                                        <?php echo esc_html(get_the_author()); ?>
                                    </a>
                                </span>
                            </div>
                        <?php endif; ?>
                    </header>

                    <div class="entry-content">
                        <?php
                        if (is_singular()) :
                            the_content();
                            
                            wp_link_pages(array(
                                'before' => '<div class="page-links"><span class="page-links-title">' . esc_html__('Pages:', 'gpress') . '</span>',
                                'after'  => '</div>',
                                'link_before' => '<span>',
                                'link_after'  => '</span>',
                            ));
                        else :
                            the_excerpt();
                        endif;
                        ?>
                    </div>

                    <?php if (!is_singular()) : ?>
                        <footer class="entry-footer">
                            <a href="<?php the_permalink(); ?>" class="read-more" aria-describedby="post-<?php the_ID(); ?>-title">
                                <?php esc_html_e('Read more', 'gpress'); ?>
                                <span class="screen-reader-text"> <?php esc_html_e('about', 'gpress'); ?> "<?php the_title(); ?>"</span>
                            </a>
                        </footer>
                    <?php endif; ?>
                    
                </article>

            <?php endwhile; ?>
        </div>

        <?php
        // Pagination
        the_posts_pagination(array(
            'mid_size' => 2,
            'prev_text' => esc_html__('Previous', 'gpress'),
            'next_text' => esc_html__('Next', 'gpress'),
            'screen_reader_text' => esc_html__('Posts navigation', 'gpress'),
        ));
        ?>

    <?php else : ?>

        <section class="no-results not-found">
            <header class="page-header">
                <h1 class="page-title"><?php esc_html_e('Nothing Found', 'gpress'); ?></h1>
            </header>

            <div class="page-content">
                <?php if (is_home() && current_user_can('publish_posts')) : ?>
                    <p>
                        <?php
                        printf(
                            wp_kses(
                                __('Ready to publish your first post? <a href="%s">Get started here</a>.', 'gpress'),
                                array('a' => array('href' => array()))
                            ),
                            esc_url(admin_url('post-new.php'))
                        );
                        ?>
                    </p>
                <?php elseif (is_search()) : ?>
                    <p><?php esc_html_e('Sorry, but nothing matched your search terms. Please try again with some different keywords.', 'gpress'); ?></p>
                    <?php get_search_form(); ?>
                <?php else : ?>
                    <p><?php esc_html_e('It seems we can\'t find what you\'re looking for. Perhaps searching can help.', 'gpress'); ?></p>
                    <?php get_search_form(); ?>
                <?php endif; ?>
            </div>
        </section>

    <?php endif; ?>

</main>

<?php get_footer(); ?>
```

### 4. functions.php (Core Theme Functionality)

**Purpose**: Essential theme setup with performance and security optimizations

```php
<?php
/**
 * GPress functions and definitions
 *
 * @package GPress
 * @since 1.0.0
 * @version 1.0.0
 */

// Prevent direct access for security
if (!defined('ABSPATH')) {
    exit('Direct access denied.');
}

/**
 * Define theme constants for consistency and performance
 */
define('GPRESS_VERSION', '1.0.0');
define('GPRESS_THEME_DIR', get_template_directory());
define('GPRESS_THEME_URI', get_template_directory_uri());
define('GPRESS_MIN_WP_VERSION', '6.4');
define('GPRESS_MIN_PHP_VERSION', '8.0');

/**
 * Check system requirements
 */
function gpress_check_requirements() {
    global $wp_version;
    
    // Check WordPress version
    if (version_compare($wp_version, GPRESS_MIN_WP_VERSION, '<')) {
        add_action('admin_notices', function() {
            echo '<div class="error"><p>';
            printf(
                esc_html__('GPress theme requires WordPress %s or higher. You are running version %s. Please upgrade WordPress.', 'gpress'),
                GPRESS_MIN_WP_VERSION,
                $GLOBALS['wp_version']
            );
            echo '</p></div>';
        });
        return false;
    }
    
    // Check PHP version
    if (version_compare(PHP_VERSION, GPRESS_MIN_PHP_VERSION, '<')) {
        add_action('admin_notices', function() {
            echo '<div class="error"><p>';
            printf(
                esc_html__('GPress theme requires PHP %s or higher. You are running version %s. Please contact your hosting provider.', 'gpress'),
                GPRESS_MIN_PHP_VERSION,
                PHP_VERSION
            );
            echo '</p></div>';
        });
        return false;
    }
    
    return true;
}

/**
 * Theme setup function - runs after WordPress loads
 */
function gpress_setup() {
    
    // Check requirements first
    if (!gpress_check_requirements()) {
        return;
    }
    
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
     * hard-coded <title> tag in the document head, and expect WordPress to
     * provide it for us.
     */
    add_theme_support('title-tag');

    /*
     * Enable support for Post Thumbnails on posts and pages
     */
    add_theme_support('post-thumbnails');
    
    // Set default thumbnail size
    set_post_thumbnail_size(1200, 675, true);
    
    // Add custom image sizes with optimized dimensions
    add_image_size('gpress-medium', 600, 400, true);
    add_image_size('gpress-large', 1200, 800, true);
    add_image_size('gpress-featured', 1600, 900, true);

    /*
     * Switch default core markup for search form, comment form, and comments
     * to output valid HTML5
     */
    add_theme_support('html5', array(
        'search-form',
        'comment-form',
        'comment-list',
        'gallery',
        'caption',
        'style',
        'script',
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
     * Register navigation menus
     */
    register_nav_menus(array(
        'primary' => esc_html__('Primary Menu', 'gpress'),
        'footer'  => esc_html__('Footer Menu', 'gpress'),
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
     * Add support for editor color palette
     */
    add_theme_support('editor-color-palette', array(
        array(
            'name'  => esc_html__('Primary', 'gpress'),
            'slug'  => 'primary',
            'color' => '#2563eb',
        ),
        array(
            'name'  => esc_html__('Secondary', 'gpress'),
            'slug'  => 'secondary',
            'color' => '#64748b',
        ),
        array(
            'name'  => esc_html__('Accent', 'gpress'),
            'slug'  => 'accent',
            'color' => '#f59e0b',
        ),
    ));
}
add_action('after_setup_theme', 'gpress_setup');

/**
 * Enqueue scripts and styles with optimization
 */
function gpress_scripts() {
    
    // Main stylesheet with version for cache busting
    wp_enqueue_style(
        'gpress-style',
        get_stylesheet_uri(),
        array(),
        GPRESS_VERSION
    );
    
    // Add RTL support
    wp_style_add_data('gpress-style', 'rtl', 'replace');
    
    // Conditional script loading for comments
    if (is_singular() && comments_open() && get_option('thread_comments')) {
        wp_enqueue_script('comment-reply');
    }
    
    // Skip link focus fix for better accessibility
    wp_enqueue_script(
        'gpress-skip-link-focus-fix',
        GPRESS_THEME_URI . '/assets/js/skip-link-focus-fix.js',
        array(),
        GPRESS_VERSION,
        true
    );
}
add_action('wp_enqueue_scripts', 'gpress_scripts');

/**
 * Security enhancements
 */
function gpress_security_setup() {
    
    // Remove WordPress version from RSS feeds
    add_filter('the_generator', '__return_empty_string');
    
    // Disable XML-RPC if not needed
    add_filter('xmlrpc_enabled', '__return_false');
    
    // Remove version from scripts and styles
    add_filter('style_loader_src', 'gpress_remove_version_scripts_styles', 9999);
    add_filter('script_loader_src', 'gpress_remove_version_scripts_styles', 9999);
    
    // Remove unnecessary header information
    remove_action('wp_head', 'wp_generator');
    remove_action('wp_head', 'wlwmanifest_link');
    remove_action('wp_head', 'rsd_link');
    
    // Disable file editing from admin
    if (!defined('DISALLOW_FILE_EDIT')) {
        define('DISALLOW_FILE_EDIT', true);
    }
}
add_action('init', 'gpress_security_setup');

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

/**
 * Performance optimizations
 */
function gpress_performance_optimizations() {
    
    // Remove emoji scripts if not needed
    remove_action('wp_head', 'print_emoji_detection_script', 7);
    remove_action('wp_print_styles', 'print_emoji_styles');
    remove_action('admin_print_scripts', 'print_emoji_detection_script');
    remove_action('admin_print_styles', 'print_emoji_styles');
    
    // Disable REST API for guests if not needed
    if (!is_user_logged_in()) {
        add_filter('rest_authentication_errors', function($result) {
            if (!empty($result)) {
                return $result;
            }
            if (!is_user_logged_in()) {
                return new WP_Error('rest_not_logged_in', 'You are not currently logged in.', array('status' => 401));
            }
            return $result;
        });
    }
    
    // Optimize jQuery loading
    add_action('wp_enqueue_scripts', function() {
        if (!is_admin()) {
            wp_deregister_script('jquery');
            wp_register_script('jquery', false);
        }
    });
}
add_action('init', 'gpress_performance_optimizations');

/**
 * Add body classes for better CSS targeting
 */
function gpress_body_classes($classes) {
    
    // Add JavaScript detection class
    $classes[] = 'no-js';
    
    // Add singular class
    if (is_singular()) {
        $classes[] = 'is-singular';
    }
    
    // Add has-sidebar class conditionally
    if (is_active_sidebar('sidebar-1')) {
        $classes[] = 'has-sidebar';
    }
    
    return $classes;
}
add_filter('body_class', 'gpress_body_classes');

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
 * Excerpt optimization
 */
function gpress_excerpt_length($length) {
    return 30; // Optimal length for performance and UX
}
add_filter('excerpt_length', 'gpress_excerpt_length', 999);

function gpress_excerpt_more($more) {
    return '&hellip;';
}
add_filter('excerpt_more', 'gpress_excerpt_more');

/**
 * Optimize archives
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
 * Theme activation hook for setup
 */
function gpress_after_switch_theme() {
    
    // Flush rewrite rules
    flush_rewrite_rules();
    
    // Set default customizer values
    set_theme_mod('custom_logo', '');
    
    // Create navigation menu if it doesn't exist
    $menu_name = 'Primary Menu';
    $menu_exists = wp_get_nav_menu_object($menu_name);
    
    if (!$menu_exists) {
        $menu_id = wp_create_nav_menu($menu_name);
        
        // Assign menu to location
        $locations = get_theme_mod('nav_menu_locations');
        $locations['primary'] = $menu_id;
        set_theme_mod('nav_menu_locations', $locations);
    }
}
add_action('after_switch_theme', 'gpress_after_switch_theme');

/**
 * Theme customization starter
 */
function gpress_customize_register($wp_customize) {
    
    // Remove default sections we'll replace later
    $wp_customize->remove_section('colors');
    $wp_customize->remove_section('background_image');
    
    // Add theme info section
    $wp_customize->add_section('gpress_theme_info', array(
        'title'       => esc_html__('Theme Information', 'gpress'),
        'priority'    => 1,
        'description' => esc_html__('Welcome to GPress theme. This high-performance theme is optimized for speed, accessibility, and SEO.', 'gpress'),
    ));
}
add_action('customize_register', 'gpress_customize_register');

/**
 * Admin notices for theme information
 */
function gpress_admin_notices() {
    global $pagenow;
    
    if ($pagenow == 'themes.php' && isset($_GET['activated'])) {
        echo '<div class="notice notice-success is-dismissible">';
        echo '<p><strong>' . esc_html__('GPress theme activated successfully!', 'gpress') . '</strong></p>';
        echo '<p>' . esc_html__('Your theme is now active and optimized for performance. Visit the Customizer to personalize your site.', 'gpress') . '</p>';
        echo '</div>';
    }
}
add_action('admin_notices', 'gpress_admin_notices');
```

### 5. README.md (Documentation and Setup Guide)

**Purpose**: Clear documentation for users and developers

```markdown
# GPress - High-Performance WordPress Theme

A modern, high-performance WordPress blog theme built for 2025's web standards with Full Site Editing support, advanced performance optimizations, and WCAG 2.1 AA accessibility compliance.

## 🚀 Quick Start

1. **Download** the theme files
2. **Upload** to `/wp-content/themes/gpress/`
3. **Activate** from WordPress admin
4. **Customize** via Appearance → Customize

## ✨ Features

- ⚡ **95+ Lighthouse Score** - Optimized for Core Web Vitals
- ♿ **WCAG 2.1 AA Accessible** - Full accessibility compliance
- 📱 **Mobile-First Design** - Responsive on all devices
- 🎨 **Full Site Editing** - WordPress 6.4+ block editor support
- 🔒 **Security Hardened** - Built-in security enhancements
- 🌐 **Translation Ready** - i18n support included
- 🎯 **SEO Optimized** - Semantic markup and meta tags
- 📊 **Performance Monitored** - Built-in optimization features

## 📋 Requirements

- **WordPress**: 6.4 or higher
- **PHP**: 8.0 or higher
- **MySQL**: 5.7 or higher
- **Browser Support**: Modern browsers (Chrome, Firefox, Safari, Edge)

## 🛠️ Development Setup

```bash
# Clone or download theme
git clone [repository-url] gpress

# Navigate to theme directory
cd gpress

# Install dependencies (if applicable)
npm install

# Start development
npm run dev
```

## 🎨 Customization

### Colors
The theme uses CSS custom properties for easy color customization:

```css
:root {
    --gp-color-primary: #2563eb;
    --gp-color-secondary: #64748b;
    --gp-color-accent: #f59e0b;
}
```

### Typography
Responsive typography using clamp() for fluid scaling:

```css
:root {
    --gp-font-size-base: clamp(1rem, 0.95rem + 0.25vw, 1.125rem);
}
```

## 📱 Responsive Breakpoints

- **Mobile**: 320px - 767px
- **Tablet**: 768px - 1023px
- **Desktop**: 1024px+
- **Large**: 1200px+

## ♿ Accessibility Features

- Skip links for keyboard navigation
- Screen reader optimized content
- High contrast mode support
- Reduced motion preferences
- ARIA landmarks and labels
- Focus management

## 🔧 Performance Features

- Critical CSS inlining
- Lazy image loading
- Optimized font loading
- Minified assets
- Conditional script loading
- Browser caching headers

## 🐛 Troubleshooting

### Theme Not Appearing
- Ensure all required files exist
- Check file permissions (644 for files, 755 for directories)
- Verify WordPress version compatibility

### Styling Issues
- Clear cache (if using caching plugins)
- Check browser developer tools for errors
- Ensure CSS is loading properly

### Performance Issues
- Test with performance tools (Lighthouse, GTmetrix)
- Disable plugins to identify conflicts
- Check hosting environment specifications

## 📞 Support

For support and updates:
- **Documentation**: [Theme documentation]
- **Issues**: [GitHub issues]
- **Community**: [WordPress forums]

## 📄 License

This theme is licensed under the GNU General Public License v2 or later.

## 🤝 Contributing

Contributions are welcome! Please read the contributing guidelines before submitting pull requests.

---

**Version**: 1.0.0  
**Author**: Your Name  
**WordPress Tested**: 6.5  
**License**: GPL v2+
```

### 6. .gitignore (Version Control Exclusions)

**Purpose**: Exclude unnecessary files from version control

```gitignore
# WordPress specific
wp-config.php
wp-content/uploads/
wp-content/blogs.dir/
wp-content/upgrade/
wp-content/backup-db/
wp-content/advanced-cache.php
wp-content/wp-cache-config.php
wp-content/cache/
wp-content/cache/supercache/

# Development files
node_modules/
.npm/
.env
.env.local
.env.development.local
.env.test.local
.env.production.local

# Build tools
dist/
build/
.cache/
.parcel-cache/
.sass-cache/
.vscode/
.idea/

# System files
.DS_Store
.DS_Store?
._*
.Spotlight-V100
.Trashes
ehthumbs.db
Thumbs.db
*~

# Logs
*.log
npm-debug.log*
yarn-debug.log*
yarn-error.log*

# Dependencies
package-lock.json
yarn.lock
composer.lock

# Temporary files
*.tmp
*.temp
*.swp
*.swo

# OS generated files
Icon?
.AppleDouble
.LSOverride

# Theme specific
screenshot.png.bak
*.zip
*.tar.gz

# Testing
coverage/
.nyc_output/

# Documentation
docs/_build/

# Backup files
*.bak
*.orig
*.rej
```

### 7. screenshot.png (Theme Preview)

**Purpose**: Theme preview image for WordPress admin

```bash
# Create a placeholder screenshot (1200x900px recommended)
# This should be replaced with an actual screenshot of your theme
# For now, create a simple placeholder or download a sample image

# Using ImageMagick (if available):
convert -size 1200x900 xc:lightblue -pointsize 72 -fill black -gravity center -annotate +0+0 "GPress Theme\nScreenshot" screenshot.png

# Or create manually using any image editor with these specs:
# - Dimensions: 1200x900 pixels
# - Format: PNG
# - Content: Theme preview showing layout and design
```

## Testing This Step

### 1. Activation Test
```bash
# Upload theme to WordPress
# Navigate to Appearance → Themes
# Click "Activate" on GPress theme
# Verify no errors appear
```

### 2. Functionality Test
- [ ] Theme activates without errors
- [ ] Frontend displays correctly
- [ ] Skip links work (Tab key)
- [ ] Responsive design functions
- [ ] Basic navigation works

### 3. Performance Test
```bash
# Run Lighthouse audit
lighthouse http://your-site.local --output html

# Expected results:
# Performance: 90+
# Accessibility: 95+
# Best Practices: 90+
# SEO: 90+
```

### 4. Accessibility Test
- [ ] Tab navigation works
- [ ] Skip links function
- [ ] Screen reader compatibility
- [ ] Color contrast passes
- [ ] Focus indicators visible

### 5. Security Test
- [ ] No PHP errors in debug log
- [ ] File permissions correct (644/755)
- [ ] No direct file access possible
- [ ] Version information hidden

## Troubleshooting

### Theme Not Appearing
- Check file permissions: `chmod 644 *.php *.css *.md`
- Verify style.css theme header exists
- Ensure WordPress version compatibility

### Styling Issues
- Clear browser cache
- Check CSS syntax in style.css
- Verify file paths are correct

### Performance Issues
- Test without plugins active
- Check hosting environment
- Verify image optimization

## Expected Results

After completing Step 1, you should have:

- ✅ A functional WordPress theme that can be activated
- ✅ Clean, responsive design that works on all devices  
- ✅ Basic performance optimizations in place
- ✅ Foundation for accessibility features
- ✅ Secure, clean code structure
- ✅ Version control setup
- ✅ Documentation for users and developers

## Next Step

Proceed to [Step 2: Core Theme Files Configuration](./step-02-core-files.md) to add advanced functionality, modular architecture, and FSE preparation.

---

**Performance Target Achieved**: ⚡ 90+ Lighthouse Score  
**Accessibility Foundation**: ♿ WCAG 2.1 AA Ready  
**Security Hardened**: 🔒 Basic Security Implemented  
**Mobile Optimized**: 📱 Responsive Design Active