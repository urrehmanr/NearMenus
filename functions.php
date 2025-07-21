<?php
/**
 * NearMenus Theme Functions
 *
 * @package NearMenus
 * @since 1.0.0
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

// Define theme constants
define('NEARMENUS_VERSION', '1.0.0');
define('NEARMENUS_THEME_DIR', get_template_directory());
define('NEARMENUS_THEME_URI', get_template_directory_uri());

/**
 * Theme setup
 */
function nearmenus_setup() {
    // Make theme available for translation
    load_theme_textdomain('nearmenus', get_template_directory() . '/languages');

    // Add default posts and comments RSS feed links to head
    add_theme_support('automatic-feed-links');

    // Let WordPress manage the document title
    add_theme_support('title-tag');

    // Enable support for Post Thumbnails on posts and pages
    add_theme_support('post-thumbnails');

    // Add custom image sizes
    add_image_size('restaurant-card', 600, 400, true);
    add_image_size('restaurant-hero', 1200, 600, true);
    add_image_size('restaurant-gallery', 400, 300, true);
    add_image_size('restaurant-thumb', 150, 150, true);

    // This theme uses wp_nav_menu() in multiple locations
    register_nav_menus(array(
        'primary' => __('Primary Menu', 'nearmenus'),
        'footer' => __('Footer Menu', 'nearmenus'),
        'mobile' => __('Mobile Menu', 'nearmenus'),
    ));

    // Switch default core markup to output valid HTML5
    add_theme_support('html5', array(
        'search-form',
        'comment-form',
        'comment-list',
        'gallery',
        'caption',
        'style',
        'script',
    ));

    // Add theme support for selective refresh for widgets
    add_theme_support('customize-selective-refresh-widgets');

    // Add support for core custom logo
    add_theme_support('custom-logo', array(
        'height'      => 80,
        'width'       => 200,
        'flex-width'  => true,
        'flex-height' => true,
    ));

    // Add support for responsive embeds
    add_theme_support('responsive-embeds');

    // Add support for editor styles
    add_theme_support('editor-styles');

    // Add support for wide alignment
    add_theme_support('align-wide');

    // Set content width
    $GLOBALS['content_width'] = 1200;
}
add_action('after_setup_theme', 'nearmenus_setup');

/**
 * Enqueue scripts and styles
 */
function nearmenus_scripts() {
    // Try minified CSS first, fallback to regular CSS
    $css_path_min = NEARMENUS_THEME_DIR . '/assets/dist/css/main.min.css';
    $css_path_reg = NEARMENUS_THEME_DIR . '/assets/css/main.css';
    
    if (file_exists($css_path_min)) {
        wp_enqueue_style(
            'nearmenus-style',
            NEARMENUS_THEME_URI . '/assets/dist/css/main.min.css',
            array(),
            NEARMENUS_VERSION
        );
    } elseif (file_exists($css_path_reg)) {
        wp_enqueue_style(
            'nearmenus-style',
            NEARMENUS_THEME_URI . '/assets/css/main.css',
            array(),
            NEARMENUS_VERSION
        );
    }

    // Try minified JS first, fallback to regular JS
    $js_path_min = NEARMENUS_THEME_DIR . '/assets/dist/js/main.min.js';
    $js_path_reg = NEARMENUS_THEME_DIR . '/assets/js/main.js';
    
    if (file_exists($js_path_min)) {
        wp_enqueue_script(
            'nearmenus-script',
            NEARMENUS_THEME_URI . '/assets/dist/js/main.min.js',
            array(),
            NEARMENUS_VERSION,
            true
        );
    } elseif (file_exists($js_path_reg)) {
        wp_enqueue_script(
            'nearmenus-script',
            NEARMENUS_THEME_URI . '/assets/js/main.js',
            array(),
            NEARMENUS_VERSION,
            true
        );
    }

    // Localize script for AJAX (attached to main script)
    wp_localize_script('nearmenus-script', 'nearmenus_ajax', array(
        'ajax_url' => admin_url('admin-ajax.php'),
        'nonce' => wp_create_nonce('nearmenus_search_nonce'),
        'search_placeholder' => __('Search restaurants, cuisines, or dishes...', 'nearmenus'),
        'no_results' => __('No restaurants found.', 'nearmenus'),
        'loading' => __('Loading...', 'nearmenus'),
    ));

    // Comment reply script
    if (is_singular() && comments_open() && get_option('thread_comments')) {
        wp_enqueue_script('comment-reply');
    }
    
    // Emergency inline CSS if main CSS fails to load
    if (!file_exists($css_path_min) && !file_exists($css_path_reg)) {
        wp_register_style('nearmenus-emergency', false);
        wp_enqueue_style('nearmenus-emergency');
        $emergency_css = "
        body { font-family: Arial, sans-serif; margin: 0; padding: 0; }
        .container { max-width: 1200px; margin: 0 auto; padding: 0 20px; }
        .hero-section { background: linear-gradient(135deg, #f97316, #ea580c); color: white; padding: 60px 0; text-align: center; }
        .restaurant-card { background: white; border: 1px solid #e5e7eb; border-radius: 8px; padding: 20px; margin-bottom: 20px; }
        .btn-primary { background: #3b82f6; color: white; padding: 10px 20px; border: none; border-radius: 4px; text-decoration: none; display: inline-block; }
        .grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 20px; }
        header { background: white; border-bottom: 1px solid #e5e7eb; padding: 20px 0; }
        ";
        wp_add_inline_style('nearmenus-emergency', $emergency_css);
    }
}
add_action('wp_enqueue_scripts', 'nearmenus_scripts');

/**
 * Include required files
 */
require_once NEARMENUS_THEME_DIR . '/inc/taxonomies.php';
require_once NEARMENUS_THEME_DIR . '/inc/custom-fields.php';
require_once NEARMENUS_THEME_DIR . '/inc/search-functions.php';
require_once NEARMENUS_THEME_DIR . '/inc/customizer.php';
require_once NEARMENUS_THEME_DIR . '/inc/template-functions.php';
require_once NEARMENUS_THEME_DIR . '/inc/performance.php';
require_once NEARMENUS_THEME_DIR . '/inc/seo.php';

/**
 * Custom excerpt length
 */
function nearmenus_excerpt_length($length) {
    return 25;
}
add_filter('excerpt_length', 'nearmenus_excerpt_length');

/**
 * Custom excerpt more
 */
function nearmenus_excerpt_more($more) {
    return '...';
}
add_filter('excerpt_more', 'nearmenus_excerpt_more');

/**
 * Fallback menu function
 */
function nearmenus_fallback_menu() {
    wp_page_menu(array(
        'show_home' => true,
        'menu_class' => 'fallback-menu',
        'container' => false,
    ));
}

/**
 * Footer fallback menu
 */
function nearmenus_footer_fallback_menu() {
    $pages = get_pages(array(
        'sort_column' => 'menu_order',
        'number' => 5,
    ));
    
    if ($pages) {
        echo '<ul class="footer-menu space-y-2">';
        foreach ($pages as $page) {
            echo '<li><a href="' . get_permalink($page->ID) . '" class="text-gray-600 hover:text-primary">' . $page->post_title . '</a></li>';
        }
        echo '</ul>';
    }
}

/**
 * Pagination function
 */
function nearmenus_pagination() {
    the_posts_pagination(array(
        'mid_size' => 2,
        'prev_text' => __('&laquo; Previous', 'nearmenus'),
        'next_text' => __('Next &raquo;', 'nearmenus'),
        'before_page_number' => '<span class="screen-reader-text">' . __('Page', 'nearmenus') . ' </span>',
    ));
}

/**
 * Body classes
 */
function nearmenus_body_classes($classes) {
    // Add a class of group-blog to sites with more than 1 published author.
    if (is_multi_author()) {
        $classes[] = 'group-blog';
    }

    // Add a class of hfeed to non-singular pages.
    if (!is_singular()) {
        $classes[] = 'hfeed';
    }

    return $classes;
}
add_filter('body_class', 'nearmenus_body_classes');

/**
 * Pingback header
 */
function nearmenus_pingback_header() {
    if (is_singular() && pings_open()) {
        printf('<link rel="pingback" href="%s">', esc_url(get_bloginfo('pingback_url')));
    }
}
add_action('wp_head', 'nearmenus_pingback_header');

/**
 * Admin styles and scripts
 */
function nearmenus_admin_styles($hook_suffix) {
    // Only load on post edit pages and relevant admin pages
    $allowed_pages = array(
        'post.php',
        'post-new.php',
        'edit.php',
        'edit-tags.php',
        'term.php'
    );
    
    if (!in_array($hook_suffix, $allowed_pages)) {
        return;
    }
    
    // Admin styles
    wp_enqueue_style(
        'nearmenus-admin',
        NEARMENUS_THEME_URI . '/assets/dist/css/admin.min.css',
        array(),
        NEARMENUS_VERSION
    );
    
    // Admin scripts (only on post edit pages)
    if (in_array($hook_suffix, array('post.php', 'post-new.php'))) {
        wp_enqueue_script(
            'nearmenus-admin-js',
            NEARMENUS_THEME_URI . '/assets/dist/js/admin.min.js',
            array('jquery'),
            NEARMENUS_VERSION,
            true
        );
        
        // Enqueue WordPress media library
        wp_enqueue_media();
        
        // Localize admin script
        wp_localize_script('nearmenus-admin-js', 'nearmenusAdmin', array(
            'nonce' => wp_create_nonce('nearmenus_admin_nonce'),
            'ajax_url' => admin_url('admin-ajax.php'),
        ));
    }
}
add_action('admin_enqueue_scripts', 'nearmenus_admin_styles');