<?php
/**
 * NearMenus Theme Functions
 * 
 * Main theme functions file that sets up theme support,
 * enqueues scripts/styles, and includes modular components.
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

// Theme constants
define('NEARMENUS_VERSION', '1.0.0');
define('NEARMENUS_THEME_DIR', get_template_directory());
define('NEARMENUS_THEME_URL', get_template_directory_uri());

/**
 * Theme Setup
 */
function nearmenus_theme_setup() {
    // Add theme support features
    add_theme_support('post-thumbnails');
    add_theme_support('title-tag');
    add_theme_support('custom-logo');
    add_theme_support('html5', array(
        'search-form',
        'comment-form',
        'comment-list',
        'gallery',
        'caption',
        'style',
        'script'
    ));
    
    // Add custom image sizes for restaurants
    add_image_size('restaurant-card', 600, 400, true);
    add_image_size('restaurant-hero', 1200, 600, true);
    add_image_size('restaurant-gallery', 400, 300, true);
    add_image_size('restaurant-thumb', 150, 150, true);
    
    // Register navigation menus
    register_nav_menus(array(
        'primary' => __('Primary Menu', 'nearmenus'),
        'footer' => __('Footer Menu', 'nearmenus'),
    ));
    
    // Set content width
    $GLOBALS['content_width'] = 1200;
}
add_action('after_setup_theme', 'nearmenus_theme_setup');

/**
 * Enqueue Styles and Scripts
 */
function nearmenus_enqueue_assets() {
    // Main stylesheet
    wp_enqueue_style('nearmenus-style', NEARMENUS_THEME_URL . '/assets/css/main.css', array(), NEARMENUS_VERSION);
    
    // Main JavaScript
    wp_enqueue_script('nearmenus-main', NEARMENUS_THEME_URL . '/assets/js/main.js', array(), NEARMENUS_VERSION, true);
    
    // Search and filtering script
    wp_enqueue_script('nearmenus-search', NEARMENUS_THEME_URL . '/assets/js/search.js', array('jquery'), NEARMENUS_VERSION, true);
    
    // Localize script for AJAX
    wp_localize_script('nearmenus-search', 'nearmenus_ajax', array(
        'ajax_url' => admin_url('admin-ajax.php'),
        'nonce' => wp_create_nonce('nearmenus_search_nonce'),
    ));
}
add_action('wp_enqueue_scripts', 'nearmenus_enqueue_assets');

/**
 * Admin Enqueue
 */
function nearmenus_admin_enqueue($hook) {
    if ('post.php' == $hook || 'post-new.php' == $hook) {
        wp_enqueue_style('nearmenus-admin', NEARMENUS_THEME_URL . '/assets/css/admin.css', array(), NEARMENUS_VERSION);
        wp_enqueue_script('nearmenus-admin', NEARMENUS_THEME_URL . '/assets/js/admin.js', array('jquery'), NEARMENUS_VERSION, true);
    }
}
add_action('admin_enqueue_scripts', 'nearmenus_admin_enqueue');

/**
 * Include modular components
 */
require_once NEARMENUS_THEME_DIR . '/inc/taxonomies.php';
require_once NEARMENUS_THEME_DIR . '/inc/custom-fields.php';
require_once NEARMENUS_THEME_DIR . '/inc/search-functions.php';
require_once NEARMENUS_THEME_DIR . '/inc/template-functions.php';
require_once NEARMENUS_THEME_DIR . '/inc/customizer.php';
require_once NEARMENUS_THEME_DIR . '/inc/performance.php';
require_once NEARMENUS_THEME_DIR . '/inc/seo.php';

/**
 * Widget Areas
 */
function nearmenus_register_sidebars() {
    register_sidebar(array(
        'name' => __('Restaurant Sidebar', 'nearmenus'),
        'id' => 'restaurant-sidebar',
        'description' => __('Appears on restaurant pages', 'nearmenus'),
        'before_widget' => '<div id="%1$s" class="widget %2$s">',
        'after_widget' => '</div>',
        'before_title' => '<h3 class="widget-title">',
        'after_title' => '</h3>',
    ));
    
    register_sidebar(array(
        'name' => __('Footer Widgets', 'nearmenus'),
        'id' => 'footer-widgets',
        'description' => __('Appears in footer area', 'nearmenus'),
        'before_widget' => '<div id="%1$s" class="footer-widget %2$s">',
        'after_widget' => '</div>',
        'before_title' => '<h4 class="footer-widget-title">',
        'after_title' => '</h4>',
    ));
}
add_action('widgets_init', 'nearmenus_register_sidebars');

/**
 * Modify main query for restaurant archive
 */
function nearmenus_modify_main_query($query) {
    if (!is_admin() && $query->is_main_query()) {
        if (is_home()) {
            $query->set('posts_per_page', 12);
            $query->set('meta_key', '_restaurant_rating');
            $query->set('orderby', 'meta_value_num');
            $query->set('order', 'DESC');
        }
    }
}
add_action('pre_get_posts', 'nearmenus_modify_main_query');

/**
 * Add body classes for styling
 */
function nearmenus_body_classes($classes) {
    if (is_singular('post')) {
        $classes[] = 'single-restaurant';
    }
    if (is_home()) {
        $classes[] = 'restaurant-archive';
    }
    return $classes;
}
add_filter('body_class', 'nearmenus_body_classes');

/**
 * Excerpt length
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