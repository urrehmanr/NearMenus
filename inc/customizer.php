<?php
/**
 * Theme Customizer Configuration
 * 
 * @package NearMenus
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Add customizer settings
 */
function nearmenus_customize_register($wp_customize) {
    
    // Site Identity Section (modify existing)
    $wp_customize->get_setting('blogname')->transport = 'postMessage';
    $wp_customize->get_setting('blogdescription')->transport = 'postMessage';
    
    // Custom Logo (already exists in WordPress, just configure)
    $wp_customize->add_setting('nearmenus_logo_height', array(
        'default' => '40',
        'sanitize_callback' => 'absint',
    ));
    
    $wp_customize->add_control('nearmenus_logo_height', array(
        'label' => __('Logo Height (px)', 'nearmenus'),
        'section' => 'title_tagline',
        'type' => 'number',
        'input_attrs' => array(
            'min' => 20,
            'max' => 100,
            'step' => 1,
        ),
    ));
    
    // Colors Section
    $wp_customize->add_section('nearmenus_colors', array(
        'title' => __('Theme Colors', 'nearmenus'),
        'priority' => 40,
        'description' => __('Customize the theme colors.', 'nearmenus'),
    ));
    
    // Primary Color
    $wp_customize->add_setting('nearmenus_primary_color', array(
        'default' => '#3b82f6',
        'sanitize_callback' => 'sanitize_hex_color',
        'transport' => 'postMessage',
    ));
    
    $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'nearmenus_primary_color', array(
        'label' => __('Primary Color', 'nearmenus'),
        'section' => 'nearmenus_colors',
        'description' => __('Used for buttons, links, and highlights.', 'nearmenus'),
    )));
    
    // Secondary Color
    $wp_customize->add_setting('nearmenus_secondary_color', array(
        'default' => '#f97316',
        'sanitize_callback' => 'sanitize_hex_color',
        'transport' => 'postMessage',
    ));
    
    $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'nearmenus_secondary_color', array(
        'label' => __('Secondary Color', 'nearmenus'),
        'section' => 'nearmenus_colors',
        'description' => __('Used for accents and secondary elements.', 'nearmenus'),
    )));
    
    // Success Color
    $wp_customize->add_setting('nearmenus_success_color', array(
        'default' => '#10b981',
        'sanitize_callback' => 'sanitize_hex_color',
        'transport' => 'postMessage',
    ));
    
    $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'nearmenus_success_color', array(
        'label' => __('Success Color', 'nearmenus'),
        'section' => 'nearmenus_colors',
        'description' => __('Used for success messages and positive actions.', 'nearmenus'),
    )));
    
    // Warning Color
    $wp_customize->add_setting('nearmenus_warning_color', array(
        'default' => '#f59e0b',
        'sanitize_callback' => 'sanitize_hex_color',
        'transport' => 'postMessage',
    ));
    
    $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'nearmenus_warning_color', array(
        'label' => __('Warning Color', 'nearmenus'),
        'section' => 'nearmenus_colors',
        'description' => __('Used for warning messages.', 'nearmenus'),
    )));
    
    // Error Color
    $wp_customize->add_setting('nearmenus_error_color', array(
        'default' => '#ef4444',
        'sanitize_callback' => 'sanitize_hex_color',
        'transport' => 'postMessage',
    ));
    
    $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'nearmenus_error_color', array(
        'label' => __('Error Color', 'nearmenus'),
        'section' => 'nearmenus_colors',
        'description' => __('Used for error messages and negative actions.', 'nearmenus'),
    )));
    
    // Hero Section
    $wp_customize->add_section('nearmenus_hero', array(
        'title' => __('Hero Section', 'nearmenus'),
        'priority' => 50,
        'description' => __('Configure the homepage hero section.', 'nearmenus'),
    ));
    
    // Hero Title
    $wp_customize->add_setting('nearmenus_hero_title', array(
        'default' => __('Discover Amazing Restaurants Near You', 'nearmenus'),
        'sanitize_callback' => 'sanitize_text_field',
        'transport' => 'postMessage',
    ));
    
    $wp_customize->add_control('nearmenus_hero_title', array(
        'label' => __('Hero Title', 'nearmenus'),
        'section' => 'nearmenus_hero',
        'type' => 'text',
    ));
    
    // Hero Subtitle
    $wp_customize->add_setting('nearmenus_hero_subtitle', array(
        'default' => __('Find the perfect dining experience with our curated selection of local restaurants', 'nearmenus'),
        'sanitize_callback' => 'sanitize_textarea_field',
        'transport' => 'postMessage',
    ));
    
    $wp_customize->add_control('nearmenus_hero_subtitle', array(
        'label' => __('Hero Subtitle', 'nearmenus'),
        'section' => 'nearmenus_hero',
        'type' => 'textarea',
    ));
    
    // Hero Background Image
    $wp_customize->add_setting('nearmenus_hero_bg', array(
        'sanitize_callback' => 'absint',
    ));
    
    $wp_customize->add_control(new WP_Customize_Media_Control($wp_customize, 'nearmenus_hero_bg', array(
        'label' => __('Hero Background Image', 'nearmenus'),
        'section' => 'nearmenus_hero',
        'mime_type' => 'image',
    )));
    
    // Hero CTA Button Text
    $wp_customize->add_setting('nearmenus_hero_cta_text', array(
        'default' => __('Browse Restaurants', 'nearmenus'),
        'sanitize_callback' => 'sanitize_text_field',
    ));
    
    $wp_customize->add_control('nearmenus_hero_cta_text', array(
        'label' => __('CTA Button Text', 'nearmenus'),
        'section' => 'nearmenus_hero',
        'type' => 'text',
    ));
    
    // Hero CTA Button URL
    $wp_customize->add_setting('nearmenus_hero_cta_url', array(
        'default' => '/restaurants/',
        'sanitize_callback' => 'esc_url_raw',
    ));
    
    $wp_customize->add_control('nearmenus_hero_cta_url', array(
        'label' => __('CTA Button URL', 'nearmenus'),
        'section' => 'nearmenus_hero',
        'type' => 'url',
    ));
    
    // Contact Information Section
    $wp_customize->add_section('nearmenus_contact', array(
        'title' => __('Contact Information', 'nearmenus'),
        'priority' => 60,
        'description' => __('Configure site contact information.', 'nearmenus'),
    ));
    
    // Phone Number
    $wp_customize->add_setting('nearmenus_phone', array(
        'sanitize_callback' => 'sanitize_text_field',
    ));
    
    $wp_customize->add_control('nearmenus_phone', array(
        'label' => __('Phone Number', 'nearmenus'),
        'section' => 'nearmenus_contact',
        'type' => 'tel',
    ));
    
    // Email Address
    $wp_customize->add_setting('nearmenus_email', array(
        'sanitize_callback' => 'sanitize_email',
    ));
    
    $wp_customize->add_control('nearmenus_email', array(
        'label' => __('Email Address', 'nearmenus'),
        'section' => 'nearmenus_contact',
        'type' => 'email',
    ));
    
    // Physical Address
    $wp_customize->add_setting('nearmenus_address', array(
        'sanitize_callback' => 'sanitize_textarea_field',
    ));
    
    $wp_customize->add_control('nearmenus_address', array(
        'label' => __('Physical Address', 'nearmenus'),
        'section' => 'nearmenus_contact',
        'type' => 'textarea',
    ));
    
    // Social Media Section
    $wp_customize->add_section('nearmenus_social', array(
        'title' => __('Social Media', 'nearmenus'),
        'priority' => 70,
        'description' => __('Configure social media links.', 'nearmenus'),
    ));
    
    // Social Media Links
    $social_platforms = array(
        'facebook' => 'Facebook',
        'twitter' => 'Twitter',
        'instagram' => 'Instagram',
        'youtube' => 'YouTube',
        'linkedin' => 'LinkedIn',
        'tiktok' => 'TikTok',
    );
    
    foreach ($social_platforms as $platform => $label) {
        $wp_customize->add_setting("nearmenus_social_{$platform}", array(
            'sanitize_callback' => 'esc_url_raw',
        ));
        
        $wp_customize->add_control("nearmenus_social_{$platform}", array(
            'label' => sprintf(__('%s URL', 'nearmenus'), $label),
            'section' => 'nearmenus_social',
            'type' => 'url',
        ));
    }
    
    // Search Settings Section
    $wp_customize->add_section('nearmenus_search', array(
        'title' => __('Search Settings', 'nearmenus'),
        'priority' => 80,
        'description' => __('Configure search and listing options.', 'nearmenus'),
    ));
    
    // Default Sort Order
    $wp_customize->add_setting('nearmenus_default_sort', array(
        'default' => 'rating',
        'sanitize_callback' => 'nearmenus_sanitize_select',
    ));
    
    $wp_customize->add_control('nearmenus_default_sort', array(
        'label' => __('Default Sort Order', 'nearmenus'),
        'section' => 'nearmenus_search',
        'type' => 'select',
        'choices' => array(
            'rating' => __('Highest Rated', 'nearmenus'),
            'name' => __('Name (A-Z)', 'nearmenus'),
            'newest' => __('Newest First', 'nearmenus'),
            'reviews' => __('Most Reviews', 'nearmenus'),
        ),
    ));
    
    // Results Per Page
    $wp_customize->add_setting('nearmenus_results_per_page', array(
        'default' => '12',
        'sanitize_callback' => 'absint',
    ));
    
    $wp_customize->add_control('nearmenus_results_per_page', array(
        'label' => __('Results Per Page', 'nearmenus'),
        'section' => 'nearmenus_search',
        'type' => 'number',
        'input_attrs' => array(
            'min' => 6,
            'max' => 24,
            'step' => 3,
        ),
    ));
    
    // Enable AJAX Search
    $wp_customize->add_setting('nearmenus_ajax_search', array(
        'default' => true,
        'sanitize_callback' => 'nearmenus_sanitize_checkbox',
    ));
    
    $wp_customize->add_control('nearmenus_ajax_search', array(
        'label' => __('Enable AJAX Search', 'nearmenus'),
        'section' => 'nearmenus_search',
        'type' => 'checkbox',
        'description' => __('Search results update without page reload', 'nearmenus'),
    ));
    
    // Display Options Section
    $wp_customize->add_section('nearmenus_display', array(
        'title' => __('Display Options', 'nearmenus'),
        'priority' => 90,
        'description' => __('Configure layout and display options.', 'nearmenus'),
    ));
    
    // Card Layout Style
    $wp_customize->add_setting('nearmenus_card_style', array(
        'default' => 'modern',
        'sanitize_callback' => 'nearmenus_sanitize_select',
    ));
    
    $wp_customize->add_control('nearmenus_card_style', array(
        'label' => __('Restaurant Card Style', 'nearmenus'),
        'section' => 'nearmenus_display',
        'type' => 'select',
        'choices' => array(
            'modern' => __('Modern', 'nearmenus'),
            'classic' => __('Classic', 'nearmenus'),
            'minimal' => __('Minimal', 'nearmenus'),
        ),
    ));
    
    // Show Restaurant Features
    $wp_customize->add_setting('nearmenus_show_features', array(
        'default' => true,
        'sanitize_callback' => 'nearmenus_sanitize_checkbox',
    ));
    
    $wp_customize->add_control('nearmenus_show_features', array(
        'label' => __('Show Restaurant Features', 'nearmenus'),
        'section' => 'nearmenus_display',
        'type' => 'checkbox',
        'description' => __('Display feature icons on restaurant cards', 'nearmenus'),
    ));
    
    // Show Rating Stars
    $wp_customize->add_setting('nearmenus_show_ratings', array(
        'default' => true,
        'sanitize_callback' => 'nearmenus_sanitize_checkbox',
    ));
    
    $wp_customize->add_control('nearmenus_show_ratings', array(
        'label' => __('Show Rating Stars', 'nearmenus'),
        'section' => 'nearmenus_display',
        'type' => 'checkbox',
    ));
    
    // Pagination Style
    $wp_customize->add_setting('nearmenus_pagination_style', array(
        'default' => 'numbers',
        'sanitize_callback' => 'nearmenus_sanitize_select',
    ));
    
    $wp_customize->add_control('nearmenus_pagination_style', array(
        'label' => __('Pagination Style', 'nearmenus'),
        'section' => 'nearmenus_display',
        'type' => 'select',
        'choices' => array(
            'numbers' => __('Page Numbers', 'nearmenus'),
            'load_more' => __('Load More Button', 'nearmenus'),
            'infinite' => __('Infinite Scroll', 'nearmenus'),
        ),
    ));
}
add_action('customize_register', 'nearmenus_customize_register');

/**
 * Sanitize select fields
 */
function nearmenus_sanitize_select($input, $setting) {
    $input = sanitize_key($input);
    $choices = $setting->manager->get_control($setting->id)->choices;
    return (array_key_exists($input, $choices) ? $input : $setting->default);
}

/**
 * Sanitize checkbox fields
 */
function nearmenus_sanitize_checkbox($checked) {
    return ((isset($checked) && true == $checked) ? true : false);
}

/**
 * Bind JS handlers to instantly live-preview changes
 */
function nearmenus_customize_preview_js() {
    wp_enqueue_script(
        'nearmenus-customizer',
        get_template_directory_uri() . '/assets/js/customizer.js',
        array('customize-preview'),
        wp_get_theme()->get('Version'),
        true
    );
}
add_action('customize_preview_init', 'nearmenus_customize_preview_js');

/**
 * Enqueue customizer controls JavaScript
 */
function nearmenus_customize_controls_js() {
    wp_enqueue_script(
        'nearmenus-customizer-controls',
        get_template_directory_uri() . '/assets/js/customizer-controls.js',
        array('customize-controls'),
        wp_get_theme()->get('Version'),
        true
    );
}
add_action('customize_controls_enqueue_scripts', 'nearmenus_customize_controls_js');

/**
 * Generate custom CSS from customizer options
 */
function nearmenus_customizer_css() {
    $primary_color = get_theme_mod('nearmenus_primary_color', '#3b82f6');
    $secondary_color = get_theme_mod('nearmenus_secondary_color', '#f97316');
    $success_color = get_theme_mod('nearmenus_success_color', '#10b981');
    $warning_color = get_theme_mod('nearmenus_warning_color', '#f59e0b');
    $error_color = get_theme_mod('nearmenus_error_color', '#ef4444');
    $logo_height = get_theme_mod('nearmenus_logo_height', '40');
    
    ?>
    <style type="text/css">
        :root {
            --color-primary: <?php echo esc_attr($primary_color); ?>;
            --color-secondary: <?php echo esc_attr($secondary_color); ?>;
            --color-success: <?php echo esc_attr($success_color); ?>;
            --color-warning: <?php echo esc_attr($warning_color); ?>;
            --color-error: <?php echo esc_attr($error_color); ?>;
        }
        
        .site-logo img {
            height: <?php echo esc_attr($logo_height); ?>px;
            width: auto;
        }
        
        .btn-primary,
        .bg-primary {
            background-color: <?php echo esc_attr($primary_color); ?>;
        }
        
        .text-primary,
        .text-primary a {
            color: <?php echo esc_attr($primary_color); ?>;
        }
        
        .border-primary {
            border-color: <?php echo esc_attr($primary_color); ?>;
        }
        
        .btn-secondary,
        .bg-secondary {
            background-color: <?php echo esc_attr($secondary_color); ?>;
        }
        
        .text-secondary {
            color: <?php echo esc_attr($secondary_color); ?>;
        }
        
        .bg-success {
            background-color: <?php echo esc_attr($success_color); ?>;
        }
        
        .text-success {
            color: <?php echo esc_attr($success_color); ?>;
        }
        
        .bg-warning {
            background-color: <?php echo esc_attr($warning_color); ?>;
        }
        
        .text-warning {
            color: <?php echo esc_attr($warning_color); ?>;
        }
        
        .bg-error {
            background-color: <?php echo esc_attr($error_color); ?>;
        }
        
        .text-error {
            color: <?php echo esc_attr($error_color); ?>;
        }
        
        .star-rating .star.filled {
            color: <?php echo esc_attr($secondary_color); ?>;
        }
        
        .hero-section {
            <?php if (get_theme_mod('nearmenus_hero_bg')): ?>
            background-image: url('<?php echo esc_url(wp_get_attachment_url(get_theme_mod('nearmenus_hero_bg'))); ?>');
            <?php endif; ?>
        }
    </style>
    <?php
}
add_action('wp_head', 'nearmenus_customizer_css');