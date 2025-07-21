<?php
/**
 * Minimal NearMenus Theme Functions - For Testing
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
    // Add theme support
    add_theme_support('title-tag');
    add_theme_support('post-thumbnails');
    
    // Register nav menus
    register_nav_menus(array(
        'primary' => __('Primary Menu', 'nearmenus'),
    ));
    
    // Set content width
    $GLOBALS['content_width'] = 1200;
}
add_action('after_setup_theme', 'nearmenus_setup');

/**
 * Enqueue scripts and styles
 */
function nearmenus_scripts() {
    // Emergency CSS - Always load basic styles
    wp_register_style('nearmenus-emergency', false);
    wp_enqueue_style('nearmenus-emergency');
    
    $emergency_css = "
    /* Emergency Styles for NearMenus Theme */
    body { 
        font-family: Arial, sans-serif; 
        margin: 0; 
        padding: 0; 
        background: #f9fafb;
        color: #111827;
        line-height: 1.6;
    }
    
    .container { 
        max-width: 1200px; 
        margin: 0 auto; 
        padding: 0 20px; 
    }
    
    header {
        background: white;
        border-bottom: 1px solid #e5e7eb;
        padding: 20px 0;
        margin-bottom: 20px;
    }
    
    .hero-section { 
        background: linear-gradient(135deg, #f97316, #ea580c); 
        color: white; 
        padding: 60px 0; 
        text-align: center; 
        margin-bottom: 40px;
    }
    
    .hero-section h1 {
        font-size: 3rem;
        margin-bottom: 1rem;
        font-weight: bold;
    }
    
    .hero-section p {
        font-size: 1.2rem;
        max-width: 600px;
        margin: 0 auto 2rem;
    }
    
    .restaurant-card, .post-card { 
        background: white; 
        border: 1px solid #e5e7eb; 
        border-radius: 8px; 
        padding: 20px; 
        margin-bottom: 20px; 
        box-shadow: 0 1px 3px rgba(0,0,0,0.1);
    }
    
    .restaurant-card h3, .post-card h3 {
        margin-top: 0;
        margin-bottom: 10px;
        font-size: 1.5rem;
    }
    
    .restaurant-card a, .post-card a {
        color: #3b82f6;
        text-decoration: none;
    }
    
    .restaurant-card a:hover, .post-card a:hover {
        color: #2563eb;
    }
    
    .btn-primary { 
        background: #3b82f6; 
        color: white; 
        padding: 12px 24px; 
        border: none; 
        border-radius: 6px; 
        text-decoration: none; 
        display: inline-block; 
        font-weight: 500;
        transition: background-color 0.2s;
    }
    
    .btn-primary:hover {
        background: #2563eb;
        color: white;
        text-decoration: none;
    }
    
    .grid { 
        display: grid; 
        grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); 
        gap: 20px; 
    }
    
    .text-center { text-align: center; }
    .mb-4 { margin-bottom: 1rem; }
    .mb-8 { margin-bottom: 2rem; }
    .py-16 { padding: 4rem 0; }
    
    /* Navigation */
    nav ul {
        list-style: none;
        padding: 0;
        margin: 0;
        display: flex;
        gap: 2rem;
    }
    
    nav a {
        color: #374151;
        text-decoration: none;
        font-weight: 500;
    }
    
    nav a:hover {
        color: #3b82f6;
    }
    
    /* Responsive */
    @media (max-width: 768px) {
        .container { padding: 0 15px; }
        .hero-section h1 { font-size: 2rem; }
        .hero-section p { font-size: 1rem; }
        .grid { grid-template-columns: 1fr; }
    }
    ";
    
    wp_add_inline_style('nearmenus-emergency', $emergency_css);
    
    // Try to enqueue main CSS if it exists
    $css_path_min = NEARMENUS_THEME_DIR . '/assets/dist/css/main.min.css';
    $css_path_reg = NEARMENUS_THEME_DIR . '/assets/css/main.css';
    
    if (file_exists($css_path_min)) {
        wp_enqueue_style(
            'nearmenus-main',
            NEARMENUS_THEME_URI . '/assets/dist/css/main.min.css',
            array('nearmenus-emergency'),
            NEARMENUS_VERSION
        );
    } elseif (file_exists($css_path_reg)) {
        wp_enqueue_style(
            'nearmenus-main',
            NEARMENUS_THEME_URI . '/assets/css/main.css',
            array('nearmenus-emergency'),
            NEARMENUS_VERSION
        );
    }
}
add_action('wp_enqueue_scripts', 'nearmenus_scripts');

/**
 * Display rating stars
 */
function nearmenus_display_rating($rating) {
    $rating = floatval($rating);
    $stars = '';
    for ($i = 1; $i <= 5; $i++) {
        if ($i <= $rating) {
            $stars .= '<span class="star">★</span>';
        } elseif ($i - 0.5 <= $rating) {
            $stars .= '<span class="star half">☆</span>';
        } else {
            $stars .= '<span class="star empty">☆</span>';
        }
    }
    return '<div class="rating-stars">' . $stars . '</div>';
}

/**
 * Pagination function
 */
function nearmenus_pagination() {
    the_posts_pagination(array(
        'mid_size' => 2,
        'prev_text' => __('&laquo; Previous', 'nearmenus'),
        'next_text' => __('Next &raquo;', 'nearmenus'),
    ));
}

/**
 * Fallback menu function
 */
function nearmenus_fallback_menu() {
    echo '<ul><li><a href="' . home_url('/') . '">Home</a></li></ul>';
}
?>