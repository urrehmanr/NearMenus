# Step 9: Advanced Typography & Layout Enhancements

## Overview
**IMPORTANT NOTE**: Core typography and layout systems are implemented in **Step 7: CSS Architecture & Smart Asset Optimization** within the consolidated `core.css` file. This step focuses on **advanced enhancements**, reading optimizations, and specialized typography features that build upon the foundation established in Step 7's Smart Asset Management System.

## Integration with Step 7
This step **enhances and extends** the typography and layout systems from Step 7:
- **Core Typography & Layout**: Already implemented in `assets/css/core.css` (Step 7)
- **Smart Conditional Loading**: Uses Smart Asset Manager for enhanced features
- **Advanced Reading Features**: Builds upon the fluid typography foundation
- **Specialized Enhancements**: Adds advanced features only when needed

## Objectives
- **Enhance** the typography system from Step 7 with advanced reading features and conditional loading
- **Extend** layout systems with specialized patterns and advanced responsive behavior
- **Implement** reading enhancements like progress indicators, reading time, and typography controls
- **Build** custom block patterns that leverage the Smart Asset Manager from Step 7
- **Add** advanced typography features that load conditionally based on content analysis
- **Create** specialized layout components with performance-optimized loading patterns
- **Integrate** with Step 7's Smart Asset Manager for intelligent feature loading
- **Optimize** advanced typography features for long-form content and reading experiences

## What You'll Learn
- **Fluid Typography Systems**: Advanced typography scales that adapt intelligently across devices
- **Conditional Asset Loading**: Typography and layout assets that load only when needed
- **CSS Grid & Flexbox Mastery**: Modern layout systems with responsive patterns
- **Content Width Management**: Sophisticated container and content width strategies
- **Block Pattern Development**: Custom layout patterns for the block editor
- **Reading Enhancement**: Progress indicators, table of contents, and readability features
- **Performance Typography**: Font loading optimization and critical typography
- **Accessibility Layout**: WCAG-compliant layout patterns and typography

## Files Structure for This Step

### 📁 Files to CREATE
```
inc/
├── typography-enhancements.php  # Advanced typography features with Smart Asset Manager integration
├── reading-optimizations.php    # Reading enhancements and content analysis
├── layout-enhancements.php      # Advanced layout patterns with conditional loading
└── typography-analytics.php     # Typography performance analytics

assets/css/
├── reading-enhancements.css     # Reading optimization styles (conditional loading)
├── typography-advanced.css      # Advanced typography features (conditional loading)
└── layout-patterns.css          # Specialized layout patterns (conditional loading)

assets/js/
├── reading-progress.js          # Reading progress indicator with performance optimization
├── typography-controls.js       # Advanced typography controls (conditional loading)
├── content-analysis.js          # Content analysis for conditional loading decisions
└── layout-observers.js          # Advanced layout intersection observers

**Note**: Core typography and layout CSS is in `assets/css/core.css` from Step 7

patterns/
├── hero-section.html           # Hero with call-to-action pattern
├── feature-grid.html           # Feature grid layout pattern
├── content-sidebar.html        # Content with sidebar pattern
├── image-text-split.html       # Image and text split layout
├── testimonial-grid.html       # Testimonial grid pattern
└── pricing-table.html          # Pricing table layout
```

### 📝 Files to UPDATE
```
functions.php                   # Add typography and layout initialization
inc/theme-setup.php            # Add typography and layout theme support
inc/enqueue-scripts.php        # Update conditional asset loading
style.css                      # Update with typography and layout comments
README.md                      # Document typography and layout features
```

### 🎯 Advanced Enhancement Features Implemented
- **Conditional Enhancement Loading**: Advanced typography features load only for reading-heavy content
- **Reading Progress Tracking**: Progress indicators and reading time estimation with performance optimization
- **Advanced Typography Controls**: User-customizable typography settings with conditional loading
- **Smart Content Analysis**: Automatic detection of content types requiring enhanced typography
- **Performance-Optimized Enhancements**: All advanced features integrate with Smart Asset Manager
- **Reading Experience Optimization**: Table of contents, reading progress, typography controls
- **Accessibility Enhancement**: Advanced a11y features that build upon Step 7's foundation
- **Mobile Reading Optimization**: Touch-friendly typography controls and reading enhancements

## ⚠️ **IMPORTANT: Step 7 Integration Reference**

**Core typography and layout systems are implemented in Step 7**. This step adds enhancements:

### Core Systems (Implemented in Step 7):
- ✅ **Fluid Typography Scale**: clamp() based responsive typography in `core.css`
- ✅ **CSS Grid & Flexbox**: Complete layout system in `core.css`
- ✅ **CSS Custom Properties**: Design token system in `core.css`
- ✅ **Responsive Containers**: Container width management in `core.css`
- ✅ **Modern CSS Features**: CSS layers, container queries in `core.css`
- ✅ **WordPress Integration**: WordPress-specific typography styles in `core.css`

### Advanced Enhancements (This Step):
- 🚀 **Reading Progress**: Visual reading progress indicators
- 📖 **Typography Controls**: User-customizable reading preferences
- 📊 **Content Analysis**: Smart detection of content requiring enhancements
- ⚡ **Conditional Loading**: Enhanced features load only when beneficial

## Step-by-Step Implementation

### 1. Create Advanced Typography System

**File**: `inc/typography.php`
```php
<?php
/**
 * Advanced Typography System for GPress Theme
 * Implements fluid typography, conditional loading, and reading enhancements
 *
 * @package GPress
 * @subpackage Typography
 * @version 1.0.0
 * @since 1.0.0
 */

// Prevent direct access
defined('ABSPATH') || exit;

/**
 * GPress Typography Manager
 * 
 * @since 1.0.0
 */
class GPress_Typography {

    /**
     * Initialize typography system
     *
     * @since 1.0.0
     */
    public static function init() {
        add_action('after_setup_theme', array(__CLASS__, 'setup_typography_support'));
        add_action('wp_enqueue_scripts', array(__CLASS__, 'conditional_typography_assets'));
        add_action('wp_head', array(__CLASS__, 'add_typography_meta'), 1);
        add_action('wp_head', array(__CLASS__, 'preload_critical_fonts'), 2);
        add_filter('the_content', array(__CLASS__, 'enhance_content_typography'));
        add_filter('body_class', array(__CLASS__, 'add_typography_body_classes'));
        
        // Admin enhancements
        add_action('admin_init', array(__CLASS__, 'setup_editor_typography'));
        add_action('customize_register', array(__CLASS__, 'add_typography_customizer'));
    }

/**
 * Conditional Typography Asset Loading
 * Load typography styles only when enhanced features are needed
 */
function gpress_conditional_typography_assets() {
    $load_typography_css = false;
    
    // Load typography styles on content pages
    if (is_single() || is_page() || is_home()) {
        $load_typography_css = true;
    }
    
    // Check for typography-heavy blocks
    if (has_block('core/heading') || has_block('core/paragraph') || 
        has_block('core/quote') || has_block('core/pullquote') ||
        has_block('core/list') || has_block('core/table')) {
        $load_typography_css = true;
    }
    
    if ($load_typography_css) {
        wp_enqueue_style(
            'gpress-typography',
            GPRESS_THEME_URI . '/assets/css/typography.min.css',
            array('gpress-style'),
            GPRESS_VERSION
        );
        
        // Add custom typography properties based on customizer settings
        $typography_css = gpress_generate_typography_css();
        if ($typography_css) {
            wp_add_inline_style('gpress-typography', $typography_css);
        }
    }
    
    // Load reading enhancements on single posts
    if (is_singular('post') && get_theme_mod('enable_reading_enhancements', true)) {
        wp_enqueue_style(
            'gpress-reading',
            GPRESS_THEME_URI . '/assets/css/reading.css',
            array('gpress-typography'),
            GPRESS_VERSION
        );
        
        wp_enqueue_script(
            'gpress-reading-progress',
            GPRESS_THEME_URI . '/assets/js/reading-progress.js',
            array(),
            GPRESS_VERSION,
            array(
                'strategy' => 'defer',
                'in_footer' => true
            )
        );
    }
}
add_action('wp_enqueue_scripts', 'gpress_conditional_typography_assets');

/**
 * Setup Advanced Typography Support
 */
function gpress_setup_advanced_typography() {
    // Add theme support for typography features
    add_theme_support('custom-line-height');
    add_theme_support('custom-spacing');
    add_theme_support('editor-font-sizes');
    add_theme_support('wp-block-styles');
    
    // Add support for fluid typography
    add_theme_support('appearance-tools');
    
    // Register custom font sizes
    add_theme_support('editor-font-sizes', array(
        array(
            'name' => esc_html__('Extra Small', 'gpress'),
            'slug' => 'x-small',
            'size' => '0.75rem',
        ),
        array(
            'name' => esc_html__('Small', 'gpress'),
            'slug' => 'small',
            'size' => '0.875rem',
        ),
        array(
            'name' => esc_html__('Medium', 'gpress'),
            'slug' => 'medium',
            'size' => '1rem',
        ),
        array(
            'name' => esc_html__('Large', 'gpress'),
            'slug' => 'large',
            'size' => '1.25rem',
        ),
        array(
            'name' => esc_html__('Extra Large', 'gpress'),
            'slug' => 'x-large',
            'size' => '1.75rem',
        ),
        array(
            'name' => esc_html__('XX Large', 'gpress'),
            'slug' => 'xx-large',
            'size' => '2.25rem',
        ),
        array(
            'name' => esc_html__('Huge', 'gpress'),
            'slug' => 'huge',
            'size' => '3rem',
        )
    ));
}

/**
 * Enhance Content Typography
 */
function gpress_enhance_content_typography() {
    // Add reading-optimized classes to content
    function gpress_optimize_content_typography($content) {
        if (is_admin() || !is_singular()) {
            return $content;
        }
        
        // Add reading-optimized classes to paragraphs
        $content = preg_replace('/<p([^>]*)>/', '<p$1 class="gpress-readable-text">', $content);
        
        // Enhance blockquotes with better typography
        $content = preg_replace('/<blockquote([^>]*)>/', '<blockquote$1 class="gpress-enhanced-quote">', $content);
        
        // Add proper spacing to headings
        $content = preg_replace('/<h([2-6])([^>]*)>/', '<h$1$2 class="gpress-heading-spacing">', $content);
        
        return $content;
    }
    add_filter('the_content', 'gpress_optimize_content_typography');
    
    // Add drop cap support
    function gpress_add_drop_cap_support() {
        add_theme_support('editor-styles');
        add_theme_support('wp-block-styles');
    }
    add_action('after_setup_theme', 'gpress_add_drop_cap_support');
}

/**
 * Generate Dynamic Typography CSS
 */
function gpress_generate_typography_css() {
    $font_size_base = get_theme_mod('typography_base_size', 16);
    $line_height_base = get_theme_mod('typography_line_height', 1.6);
    $font_weight_base = get_theme_mod('typography_font_weight', 400);
    $letter_spacing = get_theme_mod('typography_letter_spacing', 0);
    
    $css = '';
    
    if ($font_size_base !== 16) {
        $css .= ':root { --gpress-font-size-base: ' . esc_attr($font_size_base) . 'px; }';
    }
    
    if ($line_height_base !== 1.6) {
        $css .= 'body { line-height: ' . esc_attr($line_height_base) . '; }';
    }
    
    if ($font_weight_base !== 400) {
        $css .= 'body { font-weight: ' . esc_attr($font_weight_base) . '; }';
    }
    
    if ($letter_spacing !== 0) {
        $css .= 'body { letter-spacing: ' . esc_attr($letter_spacing) . 'em; }';
    }
    
    return $css;
}

/**
 * Typography Utility Functions
 */

/**
 * Calculate optimal line height based on font size
 */
function gpress_calculate_line_height($font_size) {
    // Larger fonts need tighter line heights
    if ($font_size >= 24) {
        return 1.2;
    } elseif ($font_size >= 18) {
        return 1.4;
    } else {
        return 1.6;
    }
}

/**
 * Add custom typography classes to body
 */
function gpress_typography_body_classes($classes) {
    $font_family = get_theme_mod('typography_font_family', 'system');
    $reading_mode = get_theme_mod('enable_reading_mode', false);
    
    $classes[] = 'font-' . sanitize_html_class($font_family);
    
    if ($reading_mode) {
        $classes[] = 'reading-mode-enabled';
    }
    
    if (is_singular('post')) {
        $classes[] = 'single-post-typography';
    }
    
    return $classes;
}
add_filter('body_class', 'gpress_typography_body_classes');

/**
 * Add estimated reading time
 */
function gpress_get_reading_time($content = null) {
    if (!$content) {
        global $post;
        $content = $post->post_content;
    }
    
    $word_count = str_word_count(strip_tags($content));
    $reading_time = ceil($word_count / 200); // Average reading speed: 200 words per minute
    
    return max($reading_time, 1);
}

/**
 * Display reading time
 */
function gpress_display_reading_time() {
    if (!is_singular('post')) {
        return;
    }
    
    $reading_time = gpress_get_reading_time();
    
    printf(
        '<div class="gpress-reading-time">%s</div>',
        sprintf(
            esc_html(_n('%d minute read', '%d minutes read', $reading_time, 'gpress')),
            $reading_time
        )
    );
}

/**
 * Typography Performance Optimization
 */
function gpress_optimize_typography_performance() {
    // Preload critical fonts
    function gpress_preload_fonts() {
        $custom_fonts = get_theme_mod('custom_fonts_enabled', false);
        
        if ($custom_fonts) {
            $font_files = get_theme_mod('custom_font_files', array());
            
            foreach ($font_files as $font_file) {
                if (!empty($font_file['url'])) {
                    echo '<link rel="preload" href="' . esc_url($font_file['url']) . '" as="font" type="font/woff2" crossorigin>' . "\n";
                }
            }
        }
    }
    add_action('wp_head', 'gpress_preload_fonts', 1);
    
    // Optimize font loading
    function gpress_font_display_swap($tag, $handle, $href) {
        if (strpos($href, 'fonts.googleapis.com') !== false) {
            $tag = str_replace("rel='stylesheet'", "rel='stylesheet' media='print' onload=\"this.media='all'\"", $tag);
            $tag .= '<noscript><link rel="stylesheet" href="' . $href . '"></noscript>';
        }
        
        return $tag;
    }
    add_filter('style_loader_tag', 'gpress_font_display_swap', 10, 3);
}
add_action('init', 'gpress_optimize_typography_performance');
```

## 2. Create Layout System Functions

### File: `inc/layout-patterns.php`
```php
<?php
/**
 * Layout Block Patterns for GPress Theme
 *
 * @package GPress
 * @version 1.0.0
 */

// Prevent direct access
defined('ABSPATH') || exit;

/**
 * Initialize Layout Patterns
 */
function gpress_init_layout_patterns() {
    gpress_register_layout_pattern_categories();
    gpress_register_layout_patterns();
    gpress_conditional_layout_assets();
}
add_action('init', 'gpress_init_layout_patterns');

/**
 * Conditional Layout Asset Loading
 */
function gpress_conditional_layout_assets() {
    $load_layout_css = false;
    
    // Load layout styles on pages with layout-heavy content
    if (is_home() || is_front_page() || is_page_template()) {
        $load_layout_css = true;
    }
    
    // Check for layout-heavy blocks
    if (has_block('core/columns') || has_block('core/group') || 
        has_block('core/cover') || has_block('core/media-text') ||
        has_block('core/gallery') || has_block('core/post-template')) {
        $load_layout_css = true;
    }
    
    // Check for custom layout patterns
    global $post;
    if ($post && (strpos($post->post_content, 'gpress-layout') !== false || 
                  strpos($post->post_content, 'grid-layout') !== false)) {
        $load_layout_css = true;
    }
    
    if ($load_layout_css) {
        wp_enqueue_style(
            'gpress-layout',
            GPRESS_THEME_URI . '/assets/css/layout.min.css',
            array('gpress-style'),
            GPRESS_VERSION
        );
    }
}
add_action('wp_enqueue_scripts', 'gpress_conditional_layout_assets');

/**
 * Register Layout Pattern Categories
 */
function gpress_register_layout_pattern_categories() {
    if (!function_exists('register_block_pattern_category')) {
        return;
    }

    register_block_pattern_category(
        'gpress-layouts',
        array('label' => esc_html__('GPress Layouts', 'gpress'))
    );
    
    register_block_pattern_category(
        'gpress-headers',
        array('label' => esc_html__('GPress Headers', 'gpress'))
    );
    
    register_block_pattern_category(
        'gpress-content',
        array('label' => esc_html__('GPress Content', 'gpress'))
    );
}

/**
 * Register Layout Block Patterns
 */
function gpress_register_layout_patterns() {
    if (!function_exists('register_block_pattern')) {
        return;
    }

    // Hero section with call-to-action
    register_block_pattern(
        'gpress/hero-cta',
        array(
            'title'       => esc_html__('Hero with CTA', 'gpress'),
            'description' => esc_html__('Large hero section with call-to-action button', 'gpress'),
            'categories'  => array('gpress-layouts', 'gpress-headers'),
            'content'     => gpress_get_pattern_content('hero-cta'),
        )
    );

    // Feature grid layout
    register_block_pattern(
        'gpress/feature-grid',
        array(
            'title'       => esc_html__('Feature Grid', 'gpress'),
            'description' => esc_html__('Three-column feature grid with icons', 'gpress'),
            'categories'  => array('gpress-layouts', 'gpress-content'),
            'content'     => gpress_get_pattern_content('feature-grid'),
        )
    );

    // Card layout pattern
    register_block_pattern(
        'gpress/card-layout',
        array(
            'title'       => esc_html__('Card Layout', 'gpress'),
            'description' => esc_html__('Responsive card layout with images and content', 'gpress'),
            'categories'  => array('gpress-layouts', 'gpress-content'),
            'content'     => gpress_get_pattern_content('card-layout'),
        )
    );

    // Content with sidebar layout
    register_block_pattern(
        'gpress/content-sidebar',
        array(
            'title'       => esc_html__('Content with Sidebar', 'gpress'),
            'description' => esc_html__('Main content area with complementary sidebar', 'gpress'),
            'categories'  => array('gpress-layouts', 'gpress-content'),
            'content'     => gpress_get_pattern_content('content-sidebar'),
        )
    );
    
    // Two column text layout
    register_block_pattern(
        'gpress/two-column-text',
        array(
            'title'       => esc_html__('Two Column Text', 'gpress'),
            'description' => esc_html__('Two-column text layout for articles and content', 'gpress'),
            'categories'  => array('gpress-layouts', 'gpress-content'),
            'content'     => '<!-- wp:columns {"className":"gpress-two-column-text"} -->
<div class="wp-block-columns gpress-two-column-text"><!-- wp:column -->
<div class="wp-block-column"><!-- wp:paragraph {"className":"gpress-readable-text"} -->
<p class="gpress-readable-text">This is the first column of text content. The layout is optimized for readability with proper spacing and typography. You can add multiple paragraphs and content blocks here.</p>
<!-- /wp:paragraph --></div>
<!-- /wp:column -->

<!-- wp:column -->
<div class="wp-block-column"><!-- wp:paragraph {"className":"gpress-readable-text"} -->
<p class="gpress-readable-text">This is the second column of text content. The responsive design ensures that on smaller screens, these columns will stack vertically for better mobile reading experience.</p>
<!-- /wp:paragraph --></div>
<!-- /wp:column --></div>
<!-- /wp:columns -->',
        )
    );
}

/**
 * Get Pattern Content from File
 */
function gpress_get_pattern_content($pattern_name) {
    $pattern_file = GPRESS_THEME_DIR . '/patterns/' . $pattern_name . '.html';
    
    if (file_exists($pattern_file)) {
        return file_get_contents($pattern_file);
    }
    
    // Fallback content
    return gpress_get_fallback_pattern_content($pattern_name);
}

/**
 * Fallback Pattern Content
 */
function gpress_get_fallback_pattern_content($pattern_name) {
    $patterns = array(
        'hero-cta' => '<!-- wp:cover {"dimRatio":40,"minHeight":500,"className":"gpress-hero-cta"} -->
<div class="wp-block-cover gpress-hero-cta" style="min-height:500px"><span aria-hidden="true" class="wp-block-cover__background has-background-dim-40"></span><div class="wp-block-cover__inner-container"><!-- wp:group {"layout":{"type":"constrained","contentSize":"800px"}} -->
<div class="wp-block-group"><!-- wp:heading {"textAlign":"center","level":1,"fontSize":"huge","className":"gpress-heading-balance"} -->
<h1 class="wp-block-heading has-text-align-center has-huge-font-size gpress-heading-balance">Build Amazing Websites with GPress</h1>
<!-- /wp:heading -->

<!-- wp:paragraph {"align":"center","fontSize":"large","className":"gpress-text-pretty"} -->
<p class="has-text-align-center has-large-font-size gpress-text-pretty">Discover the latest techniques for creating high-performance WordPress themes that delight users.</p>
<!-- /wp:paragraph -->

<!-- wp:buttons {"layout":{"type":"flex","justifyContent":"center"},"style":{"spacing":{"margin":{"top":"2rem"}}}} -->
<div class="wp-block-buttons" style="margin-top:2rem"><!-- wp:button {"fontSize":"medium","className":"gpress-button-enhanced"} -->
<div class="wp-block-button gpress-button-enhanced has-medium-font-size"><a class="wp-block-button__link wp-element-button">Get Started Today</a></div>
<!-- /wp:button --></div>
<!-- /wp:buttons --></div>
<!-- /wp:group --></div></div>
<!-- /wp:cover -->',
        
        'feature-grid' => '<!-- wp:group {"className":"gpress-feature-grid gpress-grid-3-cols","layout":{"type":"constrained"}} -->
<div class="wp-block-group gpress-feature-grid gpress-grid-3-cols"><!-- wp:group {"style":{"spacing":{"padding":{"all":"2rem"}},"border":{"radius":"8px"}},"backgroundColor":"gray-100","className":"gpress-text-center"} -->
<div class="wp-block-group gpress-text-center has-gray-100-background-color has-background" style="border-radius:8px;padding:2rem"><!-- wp:heading {"textAlign":"center","level":3,"fontSize":"large"} -->
<h3 class="wp-block-heading has-text-align-center has-large-font-size">Fast Performance</h3>
<!-- /wp:heading -->

<!-- wp:paragraph {"align":"center"} -->
<p class="has-text-align-center">Optimized for Core Web Vitals with 95+ Lighthouse scores.</p>
<!-- /wp:paragraph --></div>
<!-- /wp:group --></div>
<!-- /wp:group -->',
        
        'card-layout' => '<!-- wp:group {"className":"gpress-card-layout gpress-grid-auto-fit","layout":{"type":"constrained"}} -->
<div class="wp-block-group gpress-card-layout gpress-grid-auto-fit"><!-- wp:group {"style":{"spacing":{"padding":{"all":"0"}},"border":{"radius":"8px"}},"backgroundColor":"white","className":"gpress-card"} -->
<div class="wp-block-group gpress-card has-white-background-color has-background" style="border-radius:8px;padding:0"><!-- wp:heading {"level":3,"fontSize":"medium","style":{"spacing":{"padding":{"all":"1.5rem"}}}} -->
<h3 class="wp-block-heading has-medium-font-size" style="padding:1.5rem">Card Title</h3>
<!-- /wp:heading --></div>
<!-- /wp:group --></div>
<!-- /wp:group -->',
        
        'content-sidebar' => '<!-- wp:columns {"className":"gpress-content-sidebar","style":{"spacing":{"blockGap":{"top":"2rem","left":"3rem"}}}} -->
<div class="wp-block-columns gpress-content-sidebar"><!-- wp:column {"width":"70%","className":"gpress-main-content"} -->
<div class="wp-block-column gpress-main-content" style="flex-basis:70%"><!-- wp:heading {"level":2,"fontSize":"x-large"} -->
<h2 class="wp-block-heading has-x-large-font-size">Main Content Area</h2>
<!-- /wp:heading --></div>
<!-- /wp:column -->

<!-- wp:column {"width":"30%","className":"gpress-sidebar"} -->
<div class="wp-block-column gpress-sidebar" style="flex-basis:30%"><!-- wp:group {"style":{"spacing":{"padding":{"all":"1.5rem"}},"border":{"radius":"8px"}},"backgroundColor":"gray-100"} -->
<div class="wp-block-group has-gray-100-background-color has-background" style="border-radius:8px;padding:1.5rem"><!-- wp:heading {"level":3,"fontSize":"medium"} -->
<h3 class="wp-block-heading has-medium-font-size">Sidebar Widget</h3>
<!-- /wp:heading --></div>
<!-- /wp:group --></div>
<!-- /wp:column --></div>
<!-- /wp:columns -->'
    );
    
    return isset($patterns[$pattern_name]) ? $patterns[$pattern_name] : '';
}

/**
 * Add Layout Classes to Body
 */
function gpress_layout_body_classes($classes) {
    global $post;
    
    if ($post && has_block('core/columns', $post)) {
        $classes[] = 'has-columns-layout';
    }
    
    if ($post && has_block('core/group', $post)) {
        $classes[] = 'has-group-layout';
    }
    
    if (is_page_template()) {
        $template = get_page_template_slug();
        $classes[] = 'template-' . sanitize_html_class(str_replace('.php', '', basename($template)));
    }
    
    return $classes;
}
add_filter('body_class', 'gpress_layout_body_classes');
```

## 3. Create Advanced Typography CSS

### File: `assets/css/typography.css`
```css
/*
 * Advanced Typography System for GPress Theme
 * Conditional typography enhancements
 * Version: 1.0.0
 */

/* ==========================================================================
   Advanced Typography System
   ========================================================================== */

/* Fluid Typography Base */
:root {
  --gpress-font-size-fluid-min: 1rem;
  --gpress-font-size-fluid-max: 1.25rem;
  --gpress-font-size-fluid-val: 4vw;
  
  /* Line height scale */
  --gpress-line-height-tight: 1.2;
  --gpress-line-height-normal: 1.5;
  --gpress-line-height-relaxed: 1.7;
  
  /* Letter spacing scale */
  --gpress-letter-spacing-tight: -0.025em;
  --gpress-letter-spacing-normal: 0;
  --gpress-letter-spacing-wide: 0.025em;
  --gpress-letter-spacing-wider: 0.05em;
  
  /* Typography rhythm */
  --gpress-rhythm-base: 1.5rem;
  --gpress-rhythm-small: 1rem;
  --gpress-rhythm-large: 2rem;
}

/* Typography Utilities */
.gpress-font-system {
  font-family: var(--wp--preset--font-family--system);
}

.gpress-font-serif {
  font-family: var(--wp--preset--font-family--serif);
}

.gpress-font-mono {
  font-family: var(--wp--preset--font-family--monospace);
}

/* Font Weight Classes */
.gpress-font-thin { font-weight: 100; }
.gpress-font-light { font-weight: 300; }
.gpress-font-normal { font-weight: 400; }
.gpress-font-medium { font-weight: 500; }
.gpress-font-semibold { font-weight: 600; }
.gpress-font-bold { font-weight: 700; }
.gpress-font-extrabold { font-weight: 800; }
.gpress-font-black { font-weight: 900; }

/* Line Height Classes */
.gpress-leading-tight { line-height: var(--gpress-line-height-tight); }
.gpress-leading-normal { line-height: var(--gpress-line-height-normal); }
.gpress-leading-relaxed { line-height: var(--gpress-line-height-relaxed); }

/* Letter Spacing Classes */
.gpress-tracking-tight { letter-spacing: var(--gpress-letter-spacing-tight); }
.gpress-tracking-normal { letter-spacing: var(--gpress-letter-spacing-normal); }
.gpress-tracking-wide { letter-spacing: var(--gpress-letter-spacing-wide); }
.gpress-tracking-wider { letter-spacing: var(--gpress-letter-spacing-wider); }

/* Advanced Typography Styles */
.gpress-text-balance {
  text-wrap: balance;
}

.gpress-text-pretty {
  text-wrap: pretty;
}

.gpress-heading-balance h1,
.gpress-heading-balance h2,
.gpress-heading-balance h3,
.gpress-heading-balance h4,
.gpress-heading-balance h5,
.gpress-heading-balance h6 {
  text-wrap: balance;
  max-width: 30ch;
}

/* Enhanced Drop Caps */
.has-drop-cap:not(:focus):first-letter,
.gpress-drop-cap:not(:focus):first-letter {
  font-size: 5.5em;
  font-weight: 700;
  line-height: 0.8;
  float: left;
  margin: 0.1em 0.1em 0 0;
  color: var(--wp--preset--color--primary);
  font-family: var(--wp--preset--font-family--serif);
  text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.1);
}

/* Text Selection Enhancement */
::selection {
  background-color: var(--wp--preset--color--primary);
  color: var(--wp--preset--color--white);
  text-shadow: none;
}

::-moz-selection {
  background-color: var(--wp--preset--color--primary);
  color: var(--wp--preset--color--white);
  text-shadow: none;
}

/* Improved Readability */
.gpress-readable-text {
  max-width: 65ch;
  font-size: clamp(1rem, 2.5vw, 1.125rem);
  line-height: var(--gpress-line-height-relaxed);
  font-family: var(--wp--preset--font-family--serif);
  color: var(--wp--preset--color--gray-800);
  margin-bottom: var(--gpress-rhythm-base);
}

.gpress-readable-text + .gpress-readable-text {
  margin-top: 0;
}

/* Enhanced Code Typography */
code, pre, .gpress-code {
  font-family: var(--wp--preset--font-family--monospace);
  font-size: 0.875em;
  background-color: var(--wp--preset--color--gray-100);
  color: var(--wp--preset--color--gray-800);
  border-radius: 4px;
}

code, .gpress-code {
  padding: 0.125em 0.25em;
  word-break: break-word;
}

pre, .gpress-pre {
  padding: 1rem;
  border-radius: 8px;
  overflow-x: auto;
  line-height: 1.5;
  border: 1px solid var(--wp--preset--color--gray-200);
  box-shadow: inset 0 1px 3px rgba(0, 0, 0, 0.1);
}

pre code, .gpress-pre code {
  background: none;
  padding: 0;
  border-radius: 0;
}

/* Enhanced Quote Typography */
.gpress-enhanced-quote,
blockquote.gpress-enhanced-quote {
  border-left: 4px solid var(--wp--preset--color--primary);
  padding-left: 2rem;
  margin: var(--gpress-rhythm-large) 0;
  font-style: italic;
  font-size: 1.125em;
  line-height: 1.6;
  color: var(--wp--preset--color--gray-700);
  background: var(--wp--preset--color--gray-50);
  padding: 1.5rem 1.5rem 1.5rem 2rem;
  border-radius: 0 8px 8px 0;
  position: relative;
}

.gpress-enhanced-quote::before {
  content: '"';
  font-size: 4em;
  color: var(--wp--preset--color--primary);
  position: absolute;
  top: -0.5rem;
  left: 0.5rem;
  font-family: var(--wp--preset--font-family--serif);
  opacity: 0.3;
  line-height: 1;
}

.gpress-enhanced-quote cite {
  display: block;
  margin-top: 1rem;
  font-style: normal;
  font-size: 0.875em;
  color: var(--wp--preset--color--gray-600);
  text-align: right;
}

.gpress-enhanced-quote cite::before {
  content: "— ";
}

/* Heading Enhancements */
.gpress-heading-spacing {
  margin-top: var(--gpress-rhythm-large);
  margin-bottom: var(--gpress-rhythm-base);
  scroll-margin-top: 100px;
}

.gpress-heading-spacing:first-child {
  margin-top: 0;
}

/* List Typography Enhancements */
.wp-block-list.gpress-enhanced-list li,
ul.gpress-enhanced-list li,
ol.gpress-enhanced-list li {
  margin-bottom: 0.75rem;
  line-height: var(--gpress-line-height-relaxed);
}

.wp-block-list.gpress-enhanced-list li::marker,
ul.gpress-enhanced-list li::marker {
  color: var(--wp--preset--color--primary);
  font-weight: 600;
}

/* Table Typography */
.wp-block-table.gpress-enhanced-table table {
  border-collapse: collapse;
  width: 100%;
  margin: var(--gpress-rhythm-large) 0;
  font-variant-numeric: tabular-nums;
}

.wp-block-table.gpress-enhanced-table th,
.wp-block-table.gpress-enhanced-table td {
  border: 1px solid var(--wp--preset--color--gray-200);
  padding: 0.75rem 1rem;
  text-align: left;
  vertical-align: top;
}

.wp-block-table.gpress-enhanced-table th {
  background-color: var(--wp--preset--color--gray-100);
  font-weight: 600;
  color: var(--wp--preset--color--gray-800);
  font-size: 0.875rem;
  text-transform: uppercase;
  letter-spacing: 0.025em;
}

.wp-block-table.gpress-enhanced-table tbody tr:nth-child(even) {
  background-color: var(--wp--preset--color--gray-50);
}

.wp-block-table.gpress-enhanced-table tbody tr:hover {
  background-color: var(--wp--preset--color--gray-100);
}

/* Typography Responsive Behavior */
@media (max-width: 767px) {
  .gpress-readable-text {
    max-width: none;
    font-size: 1rem;
  }
  
  .gpress-enhanced-quote {
    padding-left: 1rem;
    margin-left: 0;
    margin-right: 0;
  }
  
  .gpress-enhanced-quote::before {
    left: 0.25rem;
    font-size: 3em;
  }
  
  .has-drop-cap:not(:focus):first-letter,
  .gpress-drop-cap:not(:focus):first-letter {
    font-size: 4em;
    margin: 0.05em 0.1em 0 0;
  }
}

@media (min-width: 768px) {
  .gpress-readable-text {
    font-size: clamp(1rem, 2.5vw, 1.125rem);
  }
}

/* Typography Performance Optimizations */
.gpress-font-display-swap {
  font-display: swap;
}

/* Typography Accessibility Enhancements */
@media (prefers-reduced-motion: reduce) {
  .gpress-enhanced-quote::before {
    animation: none;
  }
}

@media (prefers-contrast: high) {
  .gpress-enhanced-quote {
    background: var(--wp--preset--color--white);
    border-left-width: 6px;
  }
  
  .gpress-enhanced-quote::before {
    opacity: 0.8;
  }
}

/* Dark mode typography adjustments */
@media (prefers-color-scheme: dark) {
  :root {
    --gpress-dark-text: #e0e0e0;
    --gpress-dark-muted: #a0a0a0;
  }
  
  /* 
  Uncomment when implementing dark mode
  .gpress-readable-text {
    color: var(--gpress-dark-text);
  }
  
  .gpress-enhanced-quote {
    background: rgba(255, 255, 255, 0.05);
    color: var(--gpress-dark-muted);
  }
  */
}

/* Focus states for accessibility */
h1:focus, h2:focus, h3:focus, h4:focus, h5:focus, h6:focus {
  outline: 2px solid var(--wp--preset--color--primary);
  outline-offset: 2px;
  border-radius: 4px;
}

/* Print typography optimizations */
@media print {
  .gpress-readable-text {
    font-size: 12pt;
    line-height: 1.4;
    color: black;
  }
  
  .gpress-enhanced-quote {
    background: none;
    border-left: 2pt solid black;
    break-inside: avoid;
  }
  
  .gpress-enhanced-quote::before {
    display: none;
  }
  
  .has-drop-cap:not(:focus):first-letter,
  .gpress-drop-cap:not(:focus):first-letter {
    font-size: 3em;
    color: black;
    text-shadow: none;
  }
}
```

## 4. Create Layout System CSS

### File: `assets/css/layout.css`
```css
/*
 * Layout System for GPress Theme
 * Advanced layout utilities and components
 * Version: 1.0.0
 */

/* ==========================================================================
   CSS Grid Layout System
   ========================================================================== */

/* Grid Container */
.gpress-grid-layout {
  display: grid;
  gap: var(--wp--preset--spacing--medium);
}

/* Grid Templates */
.gpress-grid-auto-fit {
  grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
}

.gpress-grid-auto-fill {
  grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
}

.gpress-grid-2-cols {
  grid-template-columns: repeat(2, 1fr);
}

.gpress-grid-3-cols {
  grid-template-columns: repeat(3, 1fr);
}

.gpress-grid-4-cols {
  grid-template-columns: repeat(4, 1fr);
}

/* Responsive Grid */
.gpress-grid-responsive {
  grid-template-columns: 1fr;
}

@media (min-width: 768px) {
  .gpress-grid-responsive {
    grid-template-columns: repeat(2, 1fr);
  }
}

@media (min-width: 1024px) {
  .gpress-grid-responsive {
    grid-template-columns: repeat(3, 1fr);
  }
}

@media (min-width: 1200px) {
  .gpress-grid-responsive.gpress-grid-4-desktop {
    grid-template-columns: repeat(4, 1fr);
  }
}

/* ==========================================================================
   Flexbox Layout System
   ========================================================================== */

.gpress-flex-layout {
  display: flex;
  gap: var(--wp--preset--spacing--medium);
}

.gpress-flex-wrap { flex-wrap: wrap; }
.gpress-flex-nowrap { flex-wrap: nowrap; }
.gpress-flex-col { flex-direction: column; }
.gpress-flex-row { flex-direction: row; }

/* Responsive Flex Direction */
@media (max-width: 767px) {
  .gpress-flex-mobile-col {
    flex-direction: column;
  }
}

/* Alignment Classes */
.gpress-items-start { align-items: flex-start; }
.gpress-items-center { align-items: center; }
.gpress-items-end { align-items: flex-end; }
.gpress-items-stretch { align-items: stretch; }
.gpress-items-baseline { align-items: baseline; }

.gpress-justify-start { justify-content: flex-start; }
.gpress-justify-center { justify-content: center; }
.gpress-justify-end { justify-content: flex-end; }
.gpress-justify-between { justify-content: space-between; }
.gpress-justify-around { justify-content: space-around; }
.gpress-justify-evenly { justify-content: space-evenly; }

/* ==========================================================================
   Container Queries Support
   ========================================================================== */

@supports (container-type: inline-size) {
  .gpress-layout-container {
    container-type: inline-size;
  }
  
  @container (min-width: 400px) {
    .gpress-card-layout {
      display: grid;
      grid-template-columns: auto 1fr;
      gap: 1rem;
    }
  }
  
  @container (min-width: 600px) {
    .gpress-card-layout {
      grid-template-columns: 200px 1fr;
    }
  }
  
  @container (min-width: 800px) {
    .gpress-card-layout.gpress-card-large {
      grid-template-columns: 300px 1fr;
      gap: 2rem;
    }
  }
}

/* ==========================================================================
   Content Width Management
   ========================================================================== */

.gpress-content-narrow {
  max-width: 600px;
  margin-left: auto;
  margin-right: auto;
}

.gpress-content-normal {
  max-width: 800px;
  margin-left: auto;
  margin-right: auto;
}

.gpress-content-wide {
  max-width: 1200px;
  margin-left: auto;
  margin-right: auto;
}

.gpress-content-full {
  width: 100%;
  max-width: none;
}

/* Responsive Content Width */
@media (max-width: 767px) {
  .gpress-content-narrow,
  .gpress-content-normal,
  .gpress-content-wide {
    padding-left: 1rem;
    padding-right: 1rem;
  }
}

/* ==========================================================================
   Aspect Ratio Utilities
   ========================================================================== */

.gpress-aspect-square { aspect-ratio: 1/1; }
.gpress-aspect-video { aspect-ratio: 16/9; }
.gpress-aspect-4-3 { aspect-ratio: 4/3; }
.gpress-aspect-3-2 { aspect-ratio: 3/2; }
.gpress-aspect-golden { aspect-ratio: 1.618/1; }
.gpress-aspect-portrait { aspect-ratio: 3/4; }

/* Object Fit Utilities */
.gpress-object-contain { object-fit: contain; }
.gpress-object-cover { object-fit: cover; }
.gpress-object-fill { object-fit: fill; }
.gpress-object-none { object-fit: none; }
.gpress-object-scale-down { object-fit: scale-down; }

.gpress-object-top { object-position: top; }
.gpress-object-bottom { object-position: bottom; }
.gpress-object-center { object-position: center; }
.gpress-object-left { object-position: left; }
.gpress-object-right { object-position: right; }

/* ==========================================================================
   Advanced Spacing System
   ========================================================================== */

/* Vertical Spacing */
.gpress-space-y-0 > * + * { margin-top: 0; }
.gpress-space-y-1 > * + * { margin-top: 0.25rem; }
.gpress-space-y-2 > * + * { margin-top: 0.5rem; }
.gpress-space-y-3 > * + * { margin-top: 0.75rem; }
.gpress-space-y-4 > * + * { margin-top: 1rem; }
.gpress-space-y-6 > * + * { margin-top: 1.5rem; }
.gpress-space-y-8 > * + * { margin-top: 2rem; }
.gpress-space-y-12 > * + * { margin-top: 3rem; }

/* Horizontal Spacing */
.gpress-space-x-0 > * + * { margin-left: 0; }
.gpress-space-x-1 > * + * { margin-left: 0.25rem; }
.gpress-space-x-2 > * + * { margin-left: 0.5rem; }
.gpress-space-x-3 > * + * { margin-left: 0.75rem; }
.gpress-space-x-4 > * + * { margin-left: 1rem; }
.gpress-space-x-6 > * + * { margin-left: 1.5rem; }
.gpress-space-x-8 > * + * { margin-left: 2rem; }

/* ==========================================================================
   Layout Components
   ========================================================================== */

/* Card Component */
.gpress-card {
  background: var(--wp--preset--color--white);
  border-radius: 8px;
  box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
  transition: transform 0.2s ease, box-shadow 0.2s ease;
  overflow: hidden;
}

.gpress-card:hover {
  transform: translateY(-2px);
  box-shadow: 0 4px 8px rgba(0, 0, 0, 0.15);
}

.gpress-card-content {
  padding: 1.5rem;
}

.gpress-card-header {
  padding: 1rem 1.5rem;
  border-bottom: 1px solid var(--wp--preset--color--gray-200);
  background: var(--wp--preset--color--gray-50);
}

.gpress-card-footer {
  padding: 1rem 1.5rem;
  border-top: 1px solid var(--wp--preset--color--gray-200);
  background: var(--wp--preset--color--gray-50);
}

/* Hero Component */
.gpress-hero-cta {
  position: relative;
  min-height: 60vh;
  display: flex;
  align-items: center;
  justify-content: center;
  text-align: center;
  color: var(--wp--preset--color--white);
}

.gpress-hero-cta::before {
  content: '';
  position: absolute;
  top: 0;
  left: 0;
  right: 0;
  bottom: 0;
  background: linear-gradient(135deg, rgba(44, 62, 80, 0.8), rgba(52, 73, 94, 0.6));
  z-index: 1;
}

.gpress-hero-cta .wp-block-cover__inner-container {
  position: relative;
  z-index: 2;
}

/* Feature Grid Component */
.gpress-feature-grid {
  display: grid;
  gap: 2rem;
  margin: 3rem 0;
}

@media (min-width: 768px) {
  .gpress-feature-grid {
    grid-template-columns: repeat(2, 1fr);
  }
}

@media (min-width: 1024px) {
  .gpress-feature-grid {
    grid-template-columns: repeat(3, 1fr);
  }
}

.gpress-feature-item {
  text-align: center;
  padding: 2rem;
  background: var(--wp--preset--color--white);
  border-radius: 12px;
  box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
  transition: transform 0.2s ease;
}

.gpress-feature-item:hover {
  transform: translateY(-4px);
}

/* Content Sidebar Layout */
.gpress-content-sidebar {
  display: grid;
  gap: 3rem;
  grid-template-columns: 1fr;
}

@media (min-width: 1024px) {
  .gpress-content-sidebar {
    grid-template-columns: 2fr 1fr;
  }
}

.gpress-main-content {
  min-width: 0; /* Prevent grid overflow */
}

.gpress-sidebar {
  min-width: 0; /* Prevent grid overflow */
}

@media (max-width: 1023px) {
  .gpress-content-sidebar {
    grid-template-columns: 1fr;
  }
  
  .gpress-sidebar {
    order: -1; /* Show sidebar above content on mobile */
  }
}

/* ==========================================================================
   Sticky Elements
   ========================================================================== */

.gpress-sticky-top {
  position: sticky;
  top: 0;
  z-index: var(--gpress-z-index-sticky, 100);
}

.gpress-sticky-bottom {
  position: sticky;
  bottom: 0;
  z-index: var(--gpress-z-index-sticky, 100);
}

/* ==========================================================================
   Responsive Layout Utilities
   ========================================================================== */

/* Mobile Layout Utilities */
@media (max-width: 767px) {
  .gpress-mobile-hidden { display: none !important; }
  .gpress-mobile-block { display: block !important; }
  .gpress-mobile-flex { display: flex !important; }
  .gpress-mobile-grid { display: grid !important; }
  
  .gpress-mobile-text-center { text-align: center !important; }
  .gpress-mobile-text-left { text-align: left !important; }
  
  .gpress-mobile-full-width {
    width: 100vw !important;
    margin-left: 50% !important;
    transform: translateX(-50%) !important;
  }
  
  .gpress-mobile-stack {
    flex-direction: column !important;
  }
  
  .gpress-mobile-stack > * {
    width: 100% !important;
    flex-basis: auto !important;
  }
}

/* Tablet Layout Utilities */
@media (min-width: 768px) and (max-width: 1023px) {
  .gpress-tablet-hidden { display: none !important; }
  .gpress-tablet-block { display: block !important; }
  .gpress-tablet-flex { display: flex !important; }
  .gpress-tablet-grid { display: grid !important; }
  
  .gpress-tablet-2-cols {
    grid-template-columns: repeat(2, 1fr) !important;
  }
}

/* Desktop Layout Utilities */
@media (min-width: 1024px) {
  .gpress-desktop-hidden { display: none !important; }
  .gpress-desktop-block { display: block !important; }
  .gpress-desktop-flex { display: flex !important; }
  .gpress-desktop-grid { display: grid !important; }
  
  .gpress-desktop-3-cols {
    grid-template-columns: repeat(3, 1fr) !important;
  }
  
  .gpress-desktop-4-cols {
    grid-template-columns: repeat(4, 1fr) !important;
  }
}

/* ==========================================================================
   Performance Optimizations
   ========================================================================== */

/* Contain layout to improve performance */
.gpress-layout-container {
  contain: layout style;
}

/* Optimize transforms */
.gpress-card,
.gpress-feature-item {
  will-change: transform;
}

/* Remove will-change after animation */
.gpress-card:not(:hover),
.gpress-feature-item:not(:hover) {
  will-change: auto;
}

/* ==========================================================================
   Print Layout Optimizations
   ========================================================================== */

@media print {
  .gpress-print-hidden { display: none !important; }
  .gpress-print-block { display: block !important; }
  
  .gpress-print-break-before { break-before: page; }
  .gpress-print-break-after { break-after: page; }
  .gpress-print-break-inside-avoid { break-inside: avoid; }
  
  .gpress-card,
  .gpress-feature-item {
    break-inside: avoid;
    box-shadow: none;
    border: 1px solid #ddd;
  }
  
  .gpress-hero-cta {
    min-height: auto;
    background: none !important;
    color: black !important;
  }
  
  .gpress-content-sidebar {
    grid-template-columns: 1fr;
    gap: 1rem;
  }
}

/* ==========================================================================
   Accessibility Enhancements
   ========================================================================== */

/* Focus states */
.gpress-card:focus-within {
  outline: 2px solid var(--wp--preset--color--primary);
  outline-offset: 2px;
}

/* Reduced motion support */
@media (prefers-reduced-motion: reduce) {
  .gpress-card,
  .gpress-feature-item {
    transition: none;
  }
  
  .gpress-card:hover,
  .gpress-feature-item:hover {
    transform: none;
  }
}

/* High contrast support */
@media (prefers-contrast: high) {
  .gpress-card,
  .gpress-feature-item {
    border: 2px solid currentColor;
  }
}
```

## 5. Create Reading Enhancement JavaScript

### File: `assets/js/reading-progress.js`
```javascript
/**
 * Reading Progress Enhancement for GPress Theme
 * Provides reading progress indicator and related features
 * Version: 1.0.0
 */

(function() {
    'use strict';
    
    /**
     * Initialize reading enhancements
     */
    function initReadingEnhancements() {
        initReadingProgress();
        initTableOfContents();
        initSmoothScrolling();
        initReadingTime();
    }
    
    /**
     * Reading Progress Indicator
     */
    function initReadingProgress() {
        // Only on single posts
        if (!document.body.classList.contains('single-post')) {
            return;
        }
        
        // Create progress bar
        const progressBar = document.createElement('div');
        progressBar.id = 'gpress-reading-progress';
        progressBar.style.cssText = `
            position: fixed;
            top: 0;
            left: 0;
            width: 0%;
            height: 3px;
            background: var(--wp--preset--color--primary);
            z-index: 1000;
            transition: width 0.2s ease;
            transform-origin: left;
        `;
        
        document.body.appendChild(progressBar);
        
        function updateProgress() {
            const article = document.querySelector('article') || document.querySelector('main');
            if (!article) return;
            
            const articleTop = article.offsetTop;
            const articleHeight = article.offsetHeight;
            const windowHeight = window.innerHeight;
            const scrollTop = window.scrollY;
            
            const startReading = articleTop;
            const finishReading = articleTop + articleHeight - windowHeight;
            const progressRange = finishReading - startReading;
            
            if (scrollTop < startReading) {
                progressBar.style.width = '0%';
            } else if (scrollTop > finishReading) {
                progressBar.style.width = '100%';
            } else {
                const progress = ((scrollTop - startReading) / progressRange) * 100;
                progressBar.style.width = Math.min(Math.max(progress, 0), 100) + '%';
            }
        }
        
        // Throttled scroll listener
        let ticking = false;
        function onScroll() {
            if (!ticking) {
                requestAnimationFrame(() => {
                    updateProgress();
                    ticking = false;
                });
                ticking = true;
            }
        }
        
        window.addEventListener('scroll', onScroll, { passive: true });
        updateProgress(); // Initial call
    }
    
    /**
     * Enhanced Table of Contents
     */
    function initTableOfContents() {
        const toc = document.querySelector('.gpress-table-of-contents');
        if (!toc) return;
        
        const tocLinks = toc.querySelectorAll('a[href^="#"]');
        const headings = Array.from(tocLinks).map(link => {
            const id = link.getAttribute('href').substring(1);
            return document.getElementById(id);
        }).filter(Boolean);
        
        if (headings.length === 0) return;
        
        // Highlight current section
        function highlightCurrentSection() {
            const scrollPosition = window.scrollY + 100;
            
            let currentHeading = null;
            for (const heading of headings) {
                if (heading.offsetTop <= scrollPosition) {
                    currentHeading = heading;
                } else {
                    break;
                }
            }
            
            // Remove all active classes
            tocLinks.forEach(link => link.classList.remove('gpress-toc-active'));
            
            // Add active class to current section
            if (currentHeading) {
                const activeLink = toc.querySelector(`a[href="#${currentHeading.id}"]`);
                if (activeLink) {
                    activeLink.classList.add('gpress-toc-active');
                }
            }
        }
        
        // Throttled scroll listener
        let ticking = false;
        function onTocScroll() {
            if (!ticking) {
                requestAnimationFrame(() => {
                    highlightCurrentSection();
                    ticking = false;
                });
                ticking = true;
            }
        }
        
        window.addEventListener('scroll', onTocScroll, { passive: true });
        highlightCurrentSection(); // Initial call
        
        // Add CSS for active state
        const style = document.createElement('style');
        style.textContent = `
            .gpress-toc-active {
                color: var(--wp--preset--color--primary) !important;
                font-weight: 600 !important;
            }
        `;
        document.head.appendChild(style);
    }
    
    /**
     * Smooth Scrolling for TOC Links
     */
    function initSmoothScrolling() {
        const tocLinks = document.querySelectorAll('.gpress-table-of-contents a[href^="#"]');
        
        tocLinks.forEach(link => {
            link.addEventListener('click', function(e) {
                e.preventDefault();
                
                const targetId = this.getAttribute('href').substring(1);
                const target = document.getElementById(targetId);
                
                if (target) {
                    const headerHeight = getHeaderHeight();
                    const targetPosition = target.offsetTop - headerHeight - 20;
                    
                    window.scrollTo({
                        top: targetPosition,
                        behavior: 'smooth'
                    });
                    
                    // Update URL without triggering scroll
                    if (history.pushState) {
                        history.pushState(null, null, '#' + targetId);
                    }
                }
            });
        });
    }
    
    /**
     * Reading Time Calculation and Display
     */
    function initReadingTime() {
        const readingTimeContainer = document.querySelector('.gpress-reading-time');
        if (!readingTimeContainer) return;
        
        const content = document.querySelector('.entry-content, .wp-block-post-content, article');
        if (!content) return;
        
        const text = content.textContent || content.innerText || '';
        const wordCount = text.trim().split(/\s+/).length;
        const readingTime = Math.ceil(wordCount / 200); // 200 words per minute
        
        const timeText = readingTime === 1 
            ? '1 minute read' 
            : `${readingTime} minutes read`;
        
        readingTimeContainer.textContent = timeText;
        
        // Add word count for reference (hidden by default)
        const wordCountSpan = document.createElement('span');
        wordCountSpan.style.display = 'none';
        wordCountSpan.setAttribute('data-word-count', wordCount);
        readingTimeContainer.appendChild(wordCountSpan);
    }
    
    /**
     * Helper Functions
     */
    function getHeaderHeight() {
        const header = document.querySelector('header, .wp-block-template-part[data-area="header"]');
        return header ? header.offsetHeight : 0;
    }
    
    /**
     * Initialize when DOM is ready
     */
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', initReadingEnhancements);
    } else {
        initReadingEnhancements();
    }
    
})();
```

## 6. Update Functions.php

### Add to: `functions.php`
```php
// Typography and Layout Support
require_once GPRESS_INC_DIR . '/typography.php';
require_once GPRESS_INC_DIR . '/layout-patterns.php';

/**
 * Enable Typography and Layout Features
 */
function gpress_typography_layout_support() {
    // Add layout support
    add_theme_support('align-wide');
    add_theme_support('wp-block-styles');
    add_theme_support('editor-styles');
    
    // Add typography support
    add_theme_support('custom-line-height');
    add_theme_support('custom-spacing');
    add_theme_support('appearance-tools');
    
    // Register layout areas
    add_theme_support('block-template-parts');
}
add_action('after_setup_theme', 'gpress_typography_layout_support');
```

// Initialize typography system
add_action('after_setup_theme', array('GPress_Typography', 'init'));

// Initialize layout patterns
require_once GPRESS_INC_DIR . '/layout-patterns.php';
```

### 2. Update Functions.php

Add to `functions.php`:
```php
// ... existing code ...

/**
 * Load Typography and Layout Components
 */
require_once GPRESS_INC_DIR . '/typography.php';
require_once GPRESS_INC_DIR . '/layout-patterns.php';
require_once GPRESS_INC_DIR . '/reading-enhancements.php';
require_once GPRESS_INC_DIR . '/content-width.php';

/**
 * Typography and Layout Theme Support
 */
function gpress_typography_layout_theme_support() {
    // Typography support
    add_theme_support('custom-line-height');
    add_theme_support('custom-spacing');
    add_theme_support('editor-font-sizes');
    add_theme_support('appearance-tools');
    
    // Layout support
    add_theme_support('align-wide');
    add_theme_support('wp-block-styles');
    add_theme_support('editor-styles');
    add_theme_support('block-template-parts');
    
    // Custom font sizes
    add_theme_support('editor-font-sizes', array(
        array(
            'name' => esc_html__('Extra Small', 'gpress'),
            'slug' => 'x-small',
            'size' => 'clamp(0.75rem, 1.5vw, 0.875rem)',
        ),
        array(
            'name' => esc_html__('Small', 'gpress'),
            'slug' => 'small',
            'size' => 'clamp(0.875rem, 2vw, 1rem)',
        ),
        array(
            'name' => esc_html__('Medium', 'gpress'),
            'slug' => 'medium',
            'size' => 'clamp(1rem, 2.5vw, 1.125rem)',
        ),
        array(
            'name' => esc_html__('Large', 'gpress'),
            'slug' => 'large',
            'size' => 'clamp(1.25rem, 3vw, 1.5rem)',
        ),
        array(
            'name' => esc_html__('Extra Large', 'gpress'),
            'slug' => 'x-large',
            'size' => 'clamp(1.75rem, 4vw, 2.25rem)',
        ),
        array(
            'name' => esc_html__('XX Large', 'gpress'),
            'slug' => 'xx-large',
            'size' => 'clamp(2.25rem, 5vw, 3rem)',
        ),
        array(
            'name' => esc_html__('Huge', 'gpress'),
            'slug' => 'huge',
            'size' => 'clamp(3rem, 6vw, 4rem)',
        )
    ));
}
add_action('after_setup_theme', 'gpress_typography_layout_theme_support');
```

### 3. Update README.md

Add to `README.md`:
```markdown
## Typography & Layout Features

GPress includes an advanced typography and layout system:

### Typography System
- **Fluid Typography**: Responsive font sizes using clamp() and container queries
- **Typography Scales**: Comprehensive type scale with optimal line heights
- **Reading Enhancements**: Progress indicators, reading time, table of contents
- **Performance Fonts**: Optimized font loading with display: swap
- **Accessibility**: WCAG 2.1 AA compliant typography and contrast ratios

### Layout System
- **CSS Grid Layouts**: Advanced grid systems with responsive patterns
- **Flexbox Utilities**: Comprehensive flexbox utilities and components
- **Block Patterns**: Custom layout patterns for the block editor
- **Content Width**: Intelligent content width management
- **Responsive Design**: Mobile-first with advanced breakpoint management
```

## Testing This Step

### 1. Typography System Test
```bash
# Test typography enhancements
1. Activate GPress theme
2. Create post with various headings and text blocks
3. Verify fluid typography scaling across devices
4. Test drop cap functionality and enhanced quotes
5. Check reading time calculation and progress indicator
```

### 2. Layout Pattern Test
```bash
# Test layout patterns and components
1. Go to WordPress Admin → Posts → Add New
2. Click (+) to add blocks and browse patterns
3. Insert GPress layout patterns (hero, feature grid, etc.)
4. Verify responsive behavior across breakpoints
5. Test accessibility features and keyboard navigation
```

### 3. Conditional Loading Test
```bash
# Verify conditional asset loading
1. Open browser dev tools (Network tab)
2. Visit pages with/without typography-heavy content
3. Check that typography.css loads only when needed
4. Test layout.css loading on different page types
5. Verify reading-progress.js only loads on single posts
```

### 4. Performance Commands
```bash
# Validate typography and layout files
php -l inc/typography.php
php -l inc/layout-patterns.php
node -c assets/js/reading-progress.js

# Check CSS files
ls -la assets/css/typography.css assets/css/layout.css
```

### 5. Typography & Layout Validation
```bash
# Test typography and layout functionality
1. Open Chrome DevTools
2. Check computed styles for fluid typography
3. Verify CSS Grid and Flexbox implementations
4. Test responsive breakpoints and container queries
5. Validate accessibility with screen reader testing
```

## Expected Results

After completing this step, you should have:

- ✅ **Advanced Typography System**: Fluid typography with conditional loading
- ✅ **Comprehensive Layout System**: CSS Grid and Flexbox with responsive patterns
- ✅ **Reading Enhancements**: Progress indicators, reading time, and accessibility features
- ✅ **Custom Block Patterns**: Layout patterns optimized for the block editor
- ✅ **Performance Optimization**: Conditional asset loading and font optimization
- ✅ **Mobile-First Design**: Responsive typography and layouts across all devices
- ✅ **Accessibility Focus**: WCAG 2.1 AA compliant typography and layout patterns
- ✅ **Content Width Management**: Intelligent container and spacing systems

The theme now provides a sophisticated typography and layout system that enhances readability and provides flexible design options while maintaining exceptional performance.

## Next Step

In **Step 10: Gutenberg Block Support**, we'll enhance the block editor with custom block styles, advanced block configurations, and block-specific optimizations.