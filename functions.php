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
    // Enqueue main stylesheet
    wp_enqueue_style(
        'nearmenus-style',
        NEARMENUS_THEME_URI . '/assets/css/main.css',
        array(),
        NEARMENUS_VERSION
    );

    // Enqueue main script
    wp_enqueue_script(
        'nearmenus-script',
        NEARMENUS_THEME_URI . '/assets/js/main.js',
        array(),
        NEARMENUS_VERSION,
        true
    );

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
        NEARMENUS_THEME_URI . '/assets/css/admin.css',
        array(),
        NEARMENUS_VERSION
    );
    
    // Admin scripts (only on post edit pages)
    if (in_array($hook_suffix, array('post.php', 'post-new.php'))) {
        wp_enqueue_script(
            'nearmenus-admin-js',
            NEARMENUS_THEME_URI . '/assets/js/admin.js',
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