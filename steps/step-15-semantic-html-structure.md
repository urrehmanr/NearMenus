# Step 15: Semantic HTML Structure Enhancement

## Objective
Enhance the **GPress** theme's semantic HTML structure to improve accessibility, SEO, and code maintainability by implementing advanced HTML5 elements, ARIA landmarks, microdata, and semantic markup patterns with conditional asset loading.

## What You'll Learn
- Advanced semantic HTML5 structure implementation with conditional loading
- ARIA landmarks and roles optimization
- Microdata and structured markup integration
- Content outline and heading hierarchy enhancement
- Screen reader navigation improvements
- SEO semantic markup optimization

## Files to Create in This Step

```
assets/css/
├── semantic.css           # Semantic structure styles
├── semantic.min.css       # Minified semantic styles
├── outline.css           # Document outline styles
└── microdata.css         # Microdata styling

inc/
├── semantic-structure.php  # Semantic HTML enhancement functions
├── aria-landmarks.php      # ARIA landmark management
├── microdata.php          # Microdata and structured markup
└── outline-generator.php   # Document outline generation

assets/js/
├── semantic-nav.js        # Semantic navigation enhancements
├── outline-generator.js   # Dynamic outline generation
└── landmark-skip.js       # Landmark-based skip navigation

templates/
├── semantic-article.html  # Enhanced semantic article template
├── semantic-page.html     # Enhanced semantic page template
└── semantic-archive.html  # Enhanced semantic archive template
```

## 1. Create Semantic Structure Enhancement Functions

### File: `inc/semantic-structure.php`
```php
<?php
/**
 * Semantic HTML Structure Enhancement Functions for GPress Theme
 *
 * @package GPress
 * @version 1.0.0
 */

// Prevent direct access
defined('ABSPATH') || exit;

/**
 * Initialize Semantic Structure System
 */
function gpress_init_semantic_structure() {
    gpress_setup_semantic_html();
    gpress_conditional_semantic_assets();
    gpress_enhance_content_structure();
    gpress_setup_content_outline();
}
add_action('after_setup_theme', 'gpress_init_semantic_structure');

/**
 * Conditional Semantic Asset Loading
 * Load semantic enhancement styles only when needed
 */
function gpress_conditional_semantic_assets() {
    $load_semantic_css = false;
    $load_outline_css = false;
    $load_microdata_css = false;
    
    // Load semantic styles on content-heavy pages
    if (is_single() || is_page() || is_home() || is_archive()) {
        $load_semantic_css = true;
    }
    
    // Load outline styles when enabled in customizer
    if (get_theme_mod('enable_document_outline', false)) {
        $load_outline_css = true;
    }
    
    // Load microdata styles when structured data is enabled
    if (get_theme_mod('enable_structured_data', true)) {
        $load_microdata_css = true;
    }
    
    // Check for content blocks that benefit from semantic enhancement
    global $post;
    if ($post && has_blocks($post->post_content)) {
        if (has_block('core/heading') || has_block('core/list') || 
            has_block('core/table') || has_block('core/quote')) {
            $load_semantic_css = true;
        }
    }
    
    if ($load_semantic_css) {
        wp_enqueue_style(
            'gpress-semantic',
            GPRESS_THEME_URI . '/assets/css/semantic.min.css',
            array('gpress-style'),
            GPRESS_VERSION
        );
        
        wp_enqueue_script(
            'gpress-semantic-nav',
            GPRESS_THEME_URI . '/assets/js/semantic-nav.js',
            array(),
            GPRESS_VERSION,
            true
        );
    }
    
    if ($load_outline_css) {
        wp_enqueue_style(
            'gpress-outline',
            GPRESS_THEME_URI . '/assets/css/outline.css',
            array('gpress-semantic'),
            GPRESS_VERSION
        );
        
        wp_enqueue_script(
            'gpress-outline-generator',
            GPRESS_THEME_URI . '/assets/js/outline-generator.js',
            array('gpress-semantic-nav'),
            GPRESS_VERSION,
            true
        );
    }
    
    if ($load_microdata_css) {
        wp_enqueue_style(
            'gpress-microdata',
            GPRESS_THEME_URI . '/assets/css/microdata.css',
            array('gpress-semantic'),
            GPRESS_VERSION
        );
    }
}
add_action('wp_enqueue_scripts', 'gpress_conditional_semantic_assets');

/**
 * Setup Advanced Semantic HTML Support
 */
function gpress_setup_semantic_html() {
    // Add semantic HTML5 support
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
    
    // Enhanced semantic body classes
    add_filter('body_class', 'gpress_semantic_body_classes');
    
    // Enhance post classes
    add_filter('post_class', 'gpress_semantic_post_classes');
    
    // Add semantic attributes to navigation
    add_filter('wp_nav_menu_args', 'gpress_semantic_nav_attributes');
}

/**
 * Enhanced Content Structure
 */
function gpress_enhance_content_structure() {
    // Enhance content filters for semantic structure
    add_filter('the_content', 'gpress_enhance_content_semantics', 10);
    add_filter('the_excerpt', 'gpress_enhance_excerpt_semantics', 10);
    
    // Add semantic microdata to content
    add_filter('the_content', 'gpress_add_content_microdata', 15);
    
    // Enhance heading structure
    add_filter('the_content', 'gpress_optimize_heading_hierarchy', 20);
}

/**
 * Setup Content Outline System
 */
function gpress_setup_content_outline() {
    // Add outline generation to content
    add_action('wp_head', 'gpress_add_outline_schema');
    
    // Generate dynamic table of contents
    add_filter('the_content', 'gpress_generate_table_of_contents', 25);
    
    // Add document outline metadata
    add_action('wp_head', 'gpress_add_document_outline_meta');
}

/**
 * Add Semantic Body Classes
 */
function gpress_semantic_body_classes($classes) {
    global $post;
    
    // Add semantic page type classes
    if (is_singular()) {
        $classes[] = 'semantic-article';
        
        // Add content type classes
        if ($post) {
            $word_count = str_word_count(strip_tags($post->post_content));
            if ($word_count > 1000) {
                $classes[] = 'long-form-content';
            }
            
            // Add heading structure class
            if (preg_match_all('/<h[1-6]/i', $post->post_content) > 3) {
                $classes[] = 'structured-content';
            }
        }
    }
    
    if (is_archive()) {
        $classes[] = 'semantic-archive';
    }
    
    if (is_search()) {
        $classes[] = 'semantic-search';
    }
    
    // Add outline support class
    if (get_theme_mod('enable_document_outline', false)) {
        $classes[] = 'has-document-outline';
    }
    
    // Add structured data class
    if (get_theme_mod('enable_structured_data', true)) {
        $classes[] = 'has-structured-data';
    }
    
    return $classes;
}

/**
 * Add Semantic Post Classes
 */
function gpress_semantic_post_classes($classes) {
    global $post;
    
    if ($post) {
        // Add content structure classes
        $content = $post->post_content;
        
        if (has_block('core/table', $content)) {
            $classes[] = 'has-data-tables';
        }
        
        if (has_block('core/list', $content)) {
            $classes[] = 'has-structured-lists';
        }
        
        if (has_block('core/quote', $content)) {
            $classes[] = 'has-quotations';
        }
        
        // Add reading time class
        $reading_time = gpress_calculate_reading_time($content);
        if ($reading_time > 5) {
            $classes[] = 'long-read';
        } elseif ($reading_time < 2) {
            $classes[] = 'quick-read';
        }
    }
    
    return $classes;
}

/**
 * Add Semantic Navigation Attributes
 */
function gpress_semantic_nav_attributes($args) {
    $semantic_attributes = array(
        'primary' => array(
            'aria-label' => __('Primary Navigation', 'gpress'),
            'role' => 'navigation'
        ),
        'secondary' => array(
            'aria-label' => __('Secondary Navigation', 'gpress'),
            'role' => 'navigation'
        ),
        'footer' => array(
            'aria-label' => __('Footer Navigation', 'gpress'),
            'role' => 'navigation'
        ),
    );
    
    if (isset($semantic_attributes[$args['theme_location']])) {
        $args['items_wrap'] = '<ul id="%1$s" class="%2$s" ' . 
            'aria-label="' . $semantic_attributes[$args['theme_location']]['aria-label'] . '" ' .
            'role="' . $semantic_attributes[$args['theme_location']]['role'] . '">%3$s</ul>';
    }
    
    return $args;
}

/**
 * Enhance Content Semantics
 */
function gpress_enhance_content_semantics($content) {
    if (!is_main_query() || is_admin()) {
        return $content;
    }
    
    // Enhance blockquotes with semantic markup
    $content = preg_replace(
        '/<blockquote([^>]*)>/i',
        '<blockquote$1 role="complementary" aria-label="' . __('Quote', 'gpress') . '">',
        $content
    );
    
    // Enhance tables with semantic markup
    $content = preg_replace_callback(
        '/<table([^>]*)>/i',
        function($matches) {
            $attrs = $matches[1];
            if (strpos($attrs, 'role=') === false) {
                $attrs .= ' role="table"';
            }
            if (strpos($attrs, 'aria-label=') === false) {
                $attrs .= ' aria-label="' . __('Data Table', 'gpress') . '"';
            }
            return '<table' . $attrs . '>';
        },
        $content
    );
    
    // Enhance lists with semantic markup
    $content = preg_replace(
        '/<ul([^>]*)>/i',
        '<ul$1 role="list">',
        $content
    );
    
    $content = preg_replace(
        '/<ol([^>]*)>/i',
        '<ol$1 role="list">',
        $content
    );
    
    return $content;
}

/**
 * Add Content Microdata
 */
function gpress_add_content_microdata($content) {
    if (!get_theme_mod('enable_structured_data', true) || !is_singular()) {
        return $content;
    }
    
    global $post;
    
    // Add article microdata
    if (is_single()) {
        $content = '<div itemscope itemtype="https://schema.org/Article">' .
                  '<meta itemprop="headline" content="' . esc_attr(get_the_title()) . '">' .
                  '<meta itemprop="datePublished" content="' . get_the_date('c') . '">' .
                  '<meta itemprop="dateModified" content="' . get_the_modified_date('c') . '">' .
                  '<div itemprop="articleBody">' . $content . '</div>' .
                  '</div>';
    }
    
    return $content;
}

/**
 * Optimize Heading Hierarchy
 */
function gpress_optimize_heading_hierarchy($content) {
    if (!is_main_query() || is_admin()) {
        return $content;
    }
    
    // Add proper heading structure and ARIA labels
    $content = preg_replace_callback(
        '/<(h[1-6])([^>]*)>(.*?)<\/h[1-6]>/i',
        function($matches) {
            $tag = $matches[1];
            $attrs = $matches[2];
            $text = $matches[3];
            
            // Add semantic attributes
            if (strpos($attrs, 'id=') === false) {
                $id = sanitize_title($text);
                $attrs .= ' id="' . $id . '"';
            }
            
            // Add tabindex for keyboard navigation
            if (strpos($attrs, 'tabindex=') === false) {
                $attrs .= ' tabindex="-1"';
            }
            
            return '<' . $tag . $attrs . '>' . $text . '</' . $tag . '>';
        },
        $content
    );
    
    return $content;
}

/**
 * Calculate Reading Time
 */
function gpress_calculate_reading_time($content) {
    $word_count = str_word_count(strip_tags($content));
    return ceil($word_count / 200); // Assuming 200 words per minute
}

/**
 * Generate Table of Contents
 */
function gpress_generate_table_of_contents($content) {
    if (!get_theme_mod('enable_table_of_contents', false) || !is_singular('post')) {
        return $content;
    }
    
    // Extract headings
    preg_match_all('/<(h[2-6])([^>]*?)>(.*?)<\/h[2-6]>/i', $content, $matches, PREG_SET_ORDER);
    
    if (count($matches) < 3) {
        return $content; // Don't show TOC for less than 3 headings
    }
    
    $toc = '<nav class="gpress-table-of-contents" aria-label="' . __('Table of Contents', 'gpress') . '">';
    $toc .= '<h2>' . __('Table of Contents', 'gpress') . '</h2>';
    $toc .= '<ol>';
    
    foreach ($matches as $match) {
        $level = substr($match[1], 1); // Extract number from h2, h3, etc.
        $attrs = $match[2];
        $text = strip_tags($match[3]);
        
        // Extract or generate ID
        if (preg_match('/id=["\']([^"\']+)["\']/', $attrs, $id_match)) {
            $id = $id_match[1];
        } else {
            $id = sanitize_title($text);
        }
        
        $toc .= '<li class="toc-level-' . $level . '">';
        $toc .= '<a href="#' . $id . '">' . esc_html($text) . '</a>';
        $toc .= '</li>';
    }
    
    $toc .= '</ol></nav>';
    
    // Insert TOC after first paragraph
    $paragraphs = explode('</p>', $content, 2);
    if (count($paragraphs) > 1) {
        $content = $paragraphs[0] . '</p>' . $toc . $paragraphs[1];
    } else {
        $content = $toc . $content;
    }
    
    return $content;
}

/**
 * Add Document Outline Meta
 */
function gpress_add_document_outline_meta() {
    if (!get_theme_mod('enable_document_outline', false)) {
        return;
    }
    
    echo '<meta name="document-outline" content="enabled">' . "\n";
    echo '<script type="application/ld+json">' . "\n";
    echo json_encode(array(
        '@context' => 'https://schema.org',
        '@type' => 'WebPage',
        'mainEntity' => array(
            '@type' => 'Article',
            'headline' => get_the_title(),
            'description' => get_the_excerpt(),
        )
    ));
    echo "\n" . '</script>' . "\n";
}
```

## 2. Create ARIA Landmarks Management

### File: `inc/aria-landmarks.php`
```php
<?php
/**
 * ARIA Landmarks Management for GPress Theme
 *
 * @package GPress
 * @version 1.0.0
 */

// Prevent direct access
defined('ABSPATH') || exit;

/**
 * Initialize ARIA Landmarks System
 */
function gpress_init_aria_landmarks() {
    gpress_setup_landmark_navigation();
    gpress_enhance_landmark_structure();
}
add_action('after_setup_theme', 'gpress_init_aria_landmarks');

/**
 * Setup Landmark Navigation
 */
function gpress_setup_landmark_navigation() {
    // Add skip links for landmarks
    add_action('gpress_before_header', 'gpress_landmark_skip_links');
    
    // Enhance existing landmarks
    add_filter('gpress_header_attributes', 'gpress_header_landmark_attributes');
    add_filter('gpress_main_attributes', 'gpress_main_landmark_attributes');
    add_filter('gpress_aside_attributes', 'gpress_aside_landmark_attributes');
    add_filter('gpress_footer_attributes', 'gpress_footer_landmark_attributes');
}

/**
 * Add Landmark Skip Links
 */
function gpress_landmark_skip_links() {
    $skip_links = array(
        'main' => __('Skip to main content', 'gpress'),
        'navigation' => __('Skip to navigation', 'gpress'),
        'sidebar' => __('Skip to sidebar', 'gpress'),
        'footer' => __('Skip to footer', 'gpress'),
    );
    
    echo '<div class="gpress-landmark-skip-links">';
    foreach ($skip_links as $target => $text) {
        if (gpress_has_landmark($target)) {
            echo '<a href="#gpress-' . $target . '" class="screen-reader-text">' . 
                 esc_html($text) . '</a>';
        }
    }
    echo '</div>';
}

/**
 * Check if landmark exists on current page
 */
function gpress_has_landmark($landmark) {
    switch ($landmark) {
        case 'main':
            return true; // Always present
        case 'navigation':
            return has_nav_menu('primary') || has_nav_menu('secondary');
        case 'sidebar':
            return is_active_sidebar('primary-sidebar') && 
                   (is_single() || is_page() || is_home());
        case 'footer':
            return is_active_sidebar('footer-widgets') || has_nav_menu('footer');
        default:
            return false;
    }
}

/**
 * Header Landmark Attributes
 */
function gpress_header_landmark_attributes($attributes) {
    $attributes['role'] = 'banner';
    $attributes['aria-label'] = __('Site Header', 'gpress');
    return $attributes;
}

/**
 * Main Content Landmark Attributes
 */
function gpress_main_landmark_attributes($attributes) {
    $attributes['role'] = 'main';
    $attributes['id'] = 'gpress-main';
    
    if (is_single()) {
        $attributes['aria-label'] = __('Article Content', 'gpress');
    } elseif (is_page()) {
        $attributes['aria-label'] = __('Page Content', 'gpress');
    } elseif (is_home()) {
        $attributes['aria-label'] = __('Blog Posts', 'gpress');
    } elseif (is_archive()) {
        $attributes['aria-label'] = __('Archive Content', 'gpress');
    } else {
        $attributes['aria-label'] = __('Main Content', 'gpress');
    }
    
    return $attributes;
}

/**
 * Sidebar Landmark Attributes
 */
function gpress_aside_landmark_attributes($attributes) {
    $attributes['role'] = 'complementary';
    $attributes['id'] = 'gpress-sidebar';
    $attributes['aria-label'] = __('Sidebar Content', 'gpress');
    return $attributes;
}

/**
 * Footer Landmark Attributes
 */
function gpress_footer_landmark_attributes($attributes) {
    $attributes['role'] = 'contentinfo';
    $attributes['aria-label'] = __('Site Footer', 'gpress');
    return $attributes;
}
```

## 3. Create Microdata and Structured Markup

### File: `inc/microdata.php`
```php
<?php
/**
 * Microdata and Structured Markup for GPress Theme
 *
 * @package GPress
 * @version 1.0.0
 */

// Prevent direct access
defined('ABSPATH') || exit;

/**
 * Initialize Microdata System
 */
function gpress_init_microdata() {
    if (get_theme_mod('enable_structured_data', true)) {
        gpress_setup_microdata_support();
        gpress_add_structured_data();
    }
}
add_action('after_setup_theme', 'gpress_init_microdata');

/**
 * Setup Microdata Support
 */
function gpress_setup_microdata_support() {
    // Add microdata to post content
    add_filter('post_class', 'gpress_microdata_post_classes');
    
    // Add structured data to head
    add_action('wp_head', 'gpress_structured_data_head');
    
    // Add microdata attributes to content elements
    add_filter('the_content', 'gpress_add_microdata_attributes', 5);
}

/**
 * Add Microdata Post Classes
 */
function gpress_microdata_post_classes($classes) {
    if (is_singular('post')) {
        $classes[] = 'h-entry'; // Microformats
        $classes[] = 'hentry';  // Legacy support
    }
    
    return $classes;
}

/**
 * Add Structured Data to Head
 */
function gpress_structured_data_head() {
    $structured_data = array();
    
    // Website schema
    $structured_data[] = array(
        '@context' => 'https://schema.org',
        '@type' => 'WebSite',
        'name' => get_bloginfo('name'),
        'description' => get_bloginfo('description'),
        'url' => home_url('/'),
        'potentialAction' => array(
            '@type' => 'SearchAction',
            'target' => home_url('/?s={search_term_string}'),
            'query-input' => 'required name=search_term_string'
        )
    );
    
    // Organization schema
    $structured_data[] = array(
        '@context' => 'https://schema.org',
        '@type' => 'Organization',
        'name' => get_bloginfo('name'),
        'url' => home_url('/'),
        'logo' => array(
            '@type' => 'ImageObject',
            'url' => get_theme_mod('custom_logo') ? 
                    wp_get_attachment_image_url(get_theme_mod('custom_logo'), 'full') : 
                    ''
        )
    );
    
    // Article schema for single posts
    if (is_singular('post')) {
        global $post;
        $author = get_userdata($post->post_author);
        
        $structured_data[] = array(
            '@context' => 'https://schema.org',
            '@type' => 'Article',
            'headline' => get_the_title(),
            'description' => get_the_excerpt(),
            'image' => has_post_thumbnail() ? get_the_post_thumbnail_url(null, 'full') : '',
            'datePublished' => get_the_date('c'),
            'dateModified' => get_the_modified_date('c'),
            'author' => array(
                '@type' => 'Person',
                'name' => $author->display_name,
                'url' => get_author_posts_url($author->ID)
            ),
            'publisher' => array(
                '@type' => 'Organization',
                'name' => get_bloginfo('name'),
                'logo' => array(
                    '@type' => 'ImageObject',
                    'url' => get_theme_mod('custom_logo') ? 
                            wp_get_attachment_image_url(get_theme_mod('custom_logo'), 'full') : 
                            ''
                )
            ),
            'mainEntityOfPage' => get_permalink(),
            'articleSection' => gpress_get_primary_category(),
            'keywords' => gpress_get_post_keywords()
        );
    }
    
    // Breadcrumb schema
    if (function_exists('gpress_display_breadcrumbs') && !is_front_page()) {
        $breadcrumbs = gpress_get_breadcrumb_data();
        if (!empty($breadcrumbs)) {
            $structured_data[] = array(
                '@context' => 'https://schema.org',
                '@type' => 'BreadcrumbList',
                'itemListElement' => $breadcrumbs
            );
        }
    }
    
    foreach ($structured_data as $data) {
        echo '<script type="application/ld+json">' . 
             wp_json_encode($data, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) . 
             '</script>' . "\n";
    }
}

/**
 * Add Microdata Attributes to Content
 */
function gpress_add_microdata_attributes($content) {
    if (!is_singular('post')) {
        return $content;
    }
    
    // Add microdata to images
    $content = preg_replace_callback(
        '/<img([^>]+)>/i',
        function($matches) {
            $img_tag = $matches[0];
            if (strpos($img_tag, 'itemprop=') === false) {
                $img_tag = str_replace('<img', '<img itemprop="image"', $img_tag);
            }
            return $img_tag;
        },
        $content
    );
    
    // Add microdata to time elements
    $content = preg_replace_callback(
        '/<time([^>]+)>/i',
        function($matches) {
            $time_tag = $matches[0];
            if (strpos($time_tag, 'itemprop=') === false) {
                $time_tag = str_replace('<time', '<time itemprop="datePublished"', $time_tag);
            }
            return $time_tag;
        },
        $content
    );
    
    return $content;
}

/**
 * Get Primary Category
 */
function gpress_get_primary_category() {
    $categories = get_the_category();
    return !empty($categories) ? $categories[0]->name : '';
}

/**
 * Get Post Keywords
 */
function gpress_get_post_keywords() {
    $tags = get_the_tags();
    if (empty($tags)) {
        return '';
    }
    
    $keywords = array();
    foreach ($tags as $tag) {
        $keywords[] = $tag->name;
    }
    
    return implode(', ', $keywords);
}

/**
 * Get Breadcrumb Data for Schema
 */
function gpress_get_breadcrumb_data() {
    $breadcrumbs = array();
    $position = 1;
    
    // Home
    $breadcrumbs[] = array(
        '@type' => 'ListItem',
        'position' => $position++,
        'name' => __('Home', 'gpress'),
        'item' => home_url('/')
    );
    
    if (is_category() || is_single()) {
        $categories = get_the_category();
        if (!empty($categories)) {
            $breadcrumbs[] = array(
                '@type' => 'ListItem',
                'position' => $position++,
                'name' => $categories[0]->name,
                'item' => get_category_link($categories[0]->term_id)
            );
        }
    }
    
    if (is_single()) {
        $breadcrumbs[] = array(
            '@type' => 'ListItem',
            'position' => $position++,
            'name' => get_the_title(),
            'item' => get_permalink()
        );
    }
    
    return $breadcrumbs;
}
```

## 4. Create Semantic Navigation JavaScript

### File: `assets/js/semantic-nav.js`
```javascript
/**
 * Semantic Navigation Enhancement for GPress Theme
 * Enhances keyboard navigation and semantic structure
 */

document.addEventListener('DOMContentLoaded', function() {
    initSemanticNavigation();
});

/**
 * Initialize Semantic Navigation Features
 */
function initSemanticNavigation() {
    initLandmarkNavigation();
    initHeadingNavigation();
    initSemanticSkipLinks();
    initContentOutline();
    announcePageContent();
}

/**
 * Initialize Landmark-based Navigation
 */
function initLandmarkNavigation() {
    const landmarks = document.querySelectorAll('[role="main"], [role="banner"], [role="navigation"], [role="complementary"], [role="contentinfo"]');
    
    // Add keyboard shortcuts for landmark navigation
    document.addEventListener('keydown', function(e) {
        // Alt + L: List landmarks
        if (e.altKey && e.key === 'l') {
            e.preventDefault();
            showLandmarkList();
        }
        
        // Alt + H: Go to header
        if (e.altKey && e.key === 'h') {
            e.preventDefault();
            focusLandmark('banner');
        }
        
        // Alt + M: Go to main content
        if (e.altKey && e.key === 'm') {
            e.preventDefault();
            focusLandmark('main');
        }
        
        // Alt + S: Go to sidebar
        if (e.altKey && e.key === 's') {
            e.preventDefault();
            focusLandmark('complementary');
        }
        
        // Alt + F: Go to footer
        if (e.altKey && e.key === 'f') {
            e.preventDefault();
            focusLandmark('contentinfo');
        }
    });
}

/**
 * Initialize Heading-based Navigation
 */
function initHeadingNavigation() {
    const headings = document.querySelectorAll('h1, h2, h3, h4, h5, h6');
    
    // Add keyboard shortcuts for heading navigation
    document.addEventListener('keydown', function(e) {
        // Alt + 1-6: Jump to next heading of that level
        if (e.altKey && e.key >= '1' && e.key <= '6') {
            e.preventDefault();
            jumpToHeading(parseInt(e.key));
        }
        
        // Alt + N: Next heading
        if (e.altKey && e.key === 'n') {
            e.preventDefault();
            navigateHeadings('next');
        }
        
        // Alt + P: Previous heading
        if (e.altKey && e.key === 'p') {
            e.preventDefault();
            navigateHeadings('prev');
        }
    });
    
    // Enhance headings with better focus handling
    headings.forEach(function(heading) {
        if (!heading.hasAttribute('tabindex')) {
            heading.setAttribute('tabindex', '-1');
        }
        
        heading.addEventListener('focus', function() {
            announceHeading(this);
        });
    });
}

/**
 * Initialize Semantic Skip Links
 */
function initSemanticSkipLinks() {
    const skipLinks = document.querySelectorAll('.gpress-landmark-skip-links a');
    
    skipLinks.forEach(function(link) {
        link.addEventListener('click', function(e) {
            const target = document.querySelector(this.getAttribute('href'));
            if (target) {
                e.preventDefault();
                target.focus();
                target.scrollIntoView({ behavior: 'smooth', block: 'start' });
                
                // Announce the skip
                announceToScreenReader('Skipped to ' + this.textContent);
            }
        });
    });
}

/**
 * Initialize Content Outline
 */
function initContentOutline() {
    if (!document.body.classList.contains('has-document-outline')) {
        return;
    }
    
    // Create outline toggle button
    const outlineButton = document.createElement('button');
    outlineButton.innerHTML = 'Show Page Outline';
    outlineButton.className = 'gpress-outline-toggle';
    outlineButton.setAttribute('aria-pressed', 'false');
    outlineButton.setAttribute('aria-label', 'Toggle page outline visibility');
    
    // Add to page
    const main = document.querySelector('[role="main"]');
    if (main) {
        main.insertBefore(outlineButton, main.firstChild);
    }
    
    // Create outline container
    const outlineContainer = document.createElement('nav');
    outlineContainer.className = 'gpress-page-outline';
    outlineContainer.setAttribute('aria-label', 'Page Outline');
    outlineContainer.style.display = 'none';
    
    // Generate outline
    const outline = generateContentOutline();
    outlineContainer.innerHTML = outline;
    
    outlineButton.addEventListener('click', function() {
        const isVisible = outlineContainer.style.display !== 'none';
        outlineContainer.style.display = isVisible ? 'none' : 'block';
        this.setAttribute('aria-pressed', isVisible ? 'false' : 'true');
        this.innerHTML = isVisible ? 'Show Page Outline' : 'Hide Page Outline';
    });
    
    if (main) {
        main.insertBefore(outlineContainer, outlineButton.nextSibling);
    }
}

/**
 * Focus Landmark by Role
 */
function focusLandmark(role) {
    const landmark = document.querySelector('[role="' + role + '"]');
    if (landmark) {
        landmark.focus();
        landmark.scrollIntoView({ behavior: 'smooth', block: 'start' });
        announceToScreenReader('Navigated to ' + role);
    }
}

/**
 * Show Landmark List
 */
function showLandmarkList() {
    const landmarks = document.querySelectorAll('[role]');
    const landmarkNames = [];
    
    landmarks.forEach(function(landmark) {
        const role = landmark.getAttribute('role');
        const label = landmark.getAttribute('aria-label') || role;
        landmarkNames.push(label + ' (' + role + ')');
    });
    
    if (landmarkNames.length > 0) {
        announceToScreenReader('Page landmarks: ' + landmarkNames.join(', '));
    }
}

/**
 * Jump to Heading by Level
 */
function jumpToHeading(level) {
    const headings = document.querySelectorAll('h' + level);
    if (headings.length > 0) {
        headings[0].focus();
        headings[0].scrollIntoView({ behavior: 'smooth', block: 'start' });
        announceHeading(headings[0]);
    }
}

/**
 * Navigate Between Headings
 */
function navigateHeadings(direction) {
    const headings = Array.from(document.querySelectorAll('h1, h2, h3, h4, h5, h6'));
    const currentFocus = document.activeElement;
    const currentIndex = headings.indexOf(currentFocus);
    
    let targetIndex;
    if (direction === 'next') {
        targetIndex = currentIndex + 1;
    } else {
        targetIndex = currentIndex - 1;
    }
    
    if (targetIndex >= 0 && targetIndex < headings.length) {
        headings[targetIndex].focus();
        headings[targetIndex].scrollIntoView({ behavior: 'smooth', block: 'start' });
        announceHeading(headings[targetIndex]);
    }
}

/**
 * Announce Heading to Screen Reader
 */
function announceHeading(heading) {
    const level = heading.tagName.toLowerCase();
    const text = heading.textContent.trim();
    announceToScreenReader('Heading level ' + level.charAt(1) + ': ' + text);
}

/**
 * Generate Content Outline
 */
function generateContentOutline() {
    const headings = document.querySelectorAll('h1, h2, h3, h4, h5, h6');
    let outline = '<h3>Page Outline</h3><ol>';
    
    headings.forEach(function(heading, index) {
        const level = parseInt(heading.tagName.charAt(1));
        const text = heading.textContent.trim();
        const id = heading.id || 'heading-' + index;
        
        if (!heading.id) {
            heading.id = id;
        }
        
        outline += '<li class="outline-level-' + level + '">';
        outline += '<a href="#' + id + '">' + escapeHtml(text) + '</a>';
        outline += '</li>';
    });
    
    outline += '</ol>';
    return outline;
}

/**
 * Announce Page Content Changes
 */
function announcePageContent() {
    // Announce page type and main content
    let announcement = '';
    
    if (document.body.classList.contains('single')) {
        announcement = 'Article page loaded';
    } else if (document.body.classList.contains('page')) {
        announcement = 'Page loaded';
    } else if (document.body.classList.contains('home')) {
        announcement = 'Blog home page loaded';
    } else if (document.body.classList.contains('archive')) {
        announcement = 'Archive page loaded';
    }
    
    const main = document.querySelector('[role="main"]');
    if (main) {
        const headingCount = main.querySelectorAll('h1, h2, h3, h4, h5, h6').length;
        if (headingCount > 0) {
            announcement += ', ' + headingCount + ' headings found';
        }
        
        const landmarkCount = document.querySelectorAll('[role]').length;
        if (landmarkCount > 0) {
            announcement += ', ' + landmarkCount + ' landmarks available';
        }
    }
    
    if (announcement) {
        setTimeout(function() {
            announceToScreenReader(announcement);
        }, 1000);
    }
}

/**
 * Announce to Screen Reader
 */
function announceToScreenReader(message) {
    const announcement = document.createElement('div');
    announcement.setAttribute('aria-live', 'polite');
    announcement.setAttribute('aria-atomic', 'true');
    announcement.className = 'screen-reader-text';
    announcement.textContent = message;
    
    document.body.appendChild(announcement);
    
    setTimeout(function() {
        document.body.removeChild(announcement);
    }, 1000);
}

/**
 * Escape HTML for Security
 */
function escapeHtml(text) {
    const div = document.createElement('div');
    div.textContent = text;
    return div.innerHTML;
}
```

## 5. Create Semantic Structure CSS

### File: `assets/css/semantic.css`
```css
/**
 * Semantic HTML Structure Styles for GPress Theme
 * Enhanced semantic markup styling and accessibility
 */

/* ==========================================================================
   Semantic Structure Base
   ========================================================================== */

.gpress-semantic-enhanced {
    --gpress-semantic-accent: #2563eb;
    --gpress-semantic-text: #374151;
    --gpress-semantic-border: #e5e7eb;
    --gpress-semantic-background: #f9fafb;
}

/* ==========================================================================
   Landmark Enhancements
   ========================================================================== */

/* Skip Links */
.gpress-landmark-skip-links {
    position: absolute;
    top: -999px;
    left: -999px;
    z-index: 9999;
}

.gpress-landmark-skip-links a {
    position: absolute;
    top: 999px;
    left: 999px;
    display: inline-block;
    padding: 0.75rem 1rem;
    background: var(--gpress-semantic-accent);
    color: white;
    text-decoration: none;
    font-weight: 600;
    border-radius: 0.25rem;
    transition: all 0.2s ease;
}

.gpress-landmark-skip-links a:focus {
    top: 1rem;
    left: 1rem;
    outline: 2px solid white;
    outline-offset: 2px;
}

/* Landmark Indicators (when outline is enabled) */
.has-document-outline [role] {
    position: relative;
}

.has-document-outline [role]::before {
    content: attr(role);
    position: absolute;
    top: -1px;
    right: -1px;
    background: var(--gpress-semantic-accent);
    color: white;
    font-size: 0.75rem;
    padding: 0.25rem 0.5rem;
    border-radius: 0.25rem;
    opacity: 0;
    pointer-events: none;
    transition: opacity 0.2s ease;
    z-index: 10;
}

.has-document-outline [role]:focus::before,
.has-document-outline [role]:hover::before {
    opacity: 1;
}

/* ==========================================================================
   Content Structure Enhancements
   ========================================================================== */

/* Enhanced Article Structure */
.semantic-article .entry-content {
    position: relative;
}

/* Enhanced Heading Hierarchy */
.structured-content h1,
.structured-content h2,
.structured-content h3,
.structured-content h4,
.structured-content h5,
.structured-content h6 {
    position: relative;
    scroll-margin-top: 2rem;
}

.structured-content h1:focus,
.structured-content h2:focus,
.structured-content h3:focus,
.structured-content h4:focus,
.structured-content h5:focus,
.structured-content h6:focus {
    outline: 2px solid var(--gpress-semantic-accent);
    outline-offset: 2px;
}

/* Table of Contents */
.gpress-table-of-contents {
    background: var(--gpress-semantic-background);
    border: 1px solid var(--gpress-semantic-border);
    border-radius: 0.5rem;
    padding: 1.5rem;
    margin: 2rem 0;
}

.gpress-table-of-contents h2 {
    margin: 0 0 1rem 0;
    font-size: 1.25rem;
    color: var(--gpress-semantic-text);
}

.gpress-table-of-contents ol {
    margin: 0;
    padding-left: 1.5rem;
    list-style: decimal;
}

.gpress-table-of-contents li {
    margin: 0.5rem 0;
    line-height: 1.5;
}

.gpress-table-of-contents .toc-level-3 {
    margin-left: 1rem;
}

.gpress-table-of-contents .toc-level-4 {
    margin-left: 2rem;
}

.gpress-table-of-contents .toc-level-5,
.gpress-table-of-contents .toc-level-6 {
    margin-left: 3rem;
}

.gpress-table-of-contents a {
    color: var(--gpress-semantic-accent);
    text-decoration: none;
    border-bottom: 1px solid transparent;
    transition: border-color 0.2s ease;
}

.gpress-table-of-contents a:hover,
.gpress-table-of-contents a:focus {
    border-bottom-color: var(--gpress-semantic-accent);
}

/* ==========================================================================
   Page Outline
   ========================================================================== */

.gpress-outline-toggle {
    background: var(--gpress-semantic-accent);
    color: white;
    border: none;
    padding: 0.5rem 1rem;
    border-radius: 0.25rem;
    font-size: 0.875rem;
    cursor: pointer;
    margin-bottom: 1rem;
    transition: background-color 0.2s ease;
}

.gpress-outline-toggle:hover,
.gpress-outline-toggle:focus {
    background: #1d4ed8;
    outline: 2px solid transparent;
    outline-offset: 2px;
}

.gpress-page-outline {
    background: var(--gpress-semantic-background);
    border: 1px solid var(--gpress-semantic-border);
    border-radius: 0.5rem;
    padding: 1rem;
    margin-bottom: 2rem;
}

.gpress-page-outline h3 {
    margin: 0 0 1rem 0;
    font-size: 1.125rem;
}

.gpress-page-outline ol {
    margin: 0;
    padding-left: 1.5rem;
}

.gpress-page-outline .outline-level-1 {
    font-weight: 600;
}

.gpress-page-outline .outline-level-2 {
    margin-left: 1rem;
}

.gpress-page-outline .outline-level-3 {
    margin-left: 2rem;
}

.gpress-page-outline .outline-level-4,
.gpress-page-outline .outline-level-5,
.gpress-page-outline .outline-level-6 {
    margin-left: 3rem;
    font-size: 0.875rem;
}

/* ==========================================================================
   Microdata Enhancements
   ========================================================================== */

/* Article Schema Enhancements */
[itemscope][itemtype*="Article"] {
    position: relative;
}

[itemprop="headline"] {
    position: relative;
}

[itemprop="articleBody"] {
    position: relative;
}

/* Image Microdata */
[itemprop="image"] {
    position: relative;
}

/* Author Microdata */
[itemprop="author"] {
    position: relative;
}

/* ==========================================================================
   Enhanced Content Elements
   ========================================================================== */

/* Enhanced Blockquotes */
blockquote[role="complementary"] {
    position: relative;
    border-left: 4px solid var(--gpress-semantic-accent);
    padding-left: 1.5rem;
    margin: 2rem 0;
    font-style: italic;
}

blockquote[role="complementary"]::before {
    content: '\201C';
    position: absolute;
    left: -0.5rem;
    top: -0.5rem;
    font-size: 3rem;
    color: var(--gpress-semantic-accent);
    opacity: 0.3;
}

/* Enhanced Tables */
table[role="table"] {
    position: relative;
    border-collapse: collapse;
    width: 100%;
    margin: 2rem 0;
    border: 1px solid var(--gpress-semantic-border);
}

table[role="table"] caption {
    font-weight: 600;
    text-align: left;
    padding: 0.75rem;
    background: var(--gpress-semantic-background);
    border-bottom: 1px solid var(--gpress-semantic-border);
}

table[role="table"] th,
table[role="table"] td {
    padding: 0.75rem;
    text-align: left;
    border-bottom: 1px solid var(--gpress-semantic-border);
}

table[role="table"] th {
    background: var(--gpress-semantic-background);
    font-weight: 600;
}

/* Enhanced Lists */
ul[role="list"],
ol[role="list"] {
    position: relative;
    margin: 1rem 0;
    padding-left: 2rem;
}

ul[role="list"] li,
ol[role="list"] li {
    margin: 0.5rem 0;
    line-height: 1.6;
}

/* ==========================================================================
   Reading Enhancements
   ========================================================================== */

/* Long-form Content */
.long-form-content .entry-content {
    max-width: 65ch;
    line-height: 1.7;
}

.long-form-content h2,
.long-form-content h3 {
    margin-top: 3rem;
    margin-bottom: 1rem;
}

/* Quick Read Indicator */
.quick-read::before {
    content: '⚡ Quick Read';
    display: inline-block;
    background: #10b981;
    color: white;
    font-size: 0.75rem;
    padding: 0.25rem 0.5rem;
    border-radius: 0.25rem;
    margin-right: 0.5rem;
}

/* Long Read Indicator */
.long-read::before {
    content: '📖 Long Read';
    display: inline-block;
    background: #f59e0b;
    color: white;
    font-size: 0.75rem;
    padding: 0.25rem 0.5rem;
    border-radius: 0.25rem;
    margin-right: 0.5rem;
}

/* ==========================================================================
   Responsive Design
   ========================================================================== */

@media (max-width: 768px) {
    .gpress-table-of-contents {
        padding: 1rem;
        margin: 1.5rem 0;
    }
    
    .gpress-table-of-contents .toc-level-3,
    .gpress-table-of-contents .toc-level-4,
    .gpress-table-of-contents .toc-level-5,
    .gpress-table-of-contents .toc-level-6 {
        margin-left: 0;
    }
    
    .has-document-outline [role]::before {
        display: none;
    }
    
    .long-form-content .entry-content {
        max-width: 100%;
    }
}

/* ==========================================================================
   High Contrast Support
   ========================================================================== */

@media (prefers-contrast: high) {
    .gpress-semantic-enhanced {
        --gpress-semantic-accent: #000000;
        --gpress-semantic-text: #000000;
        --gpress-semantic-border: #000000;
        --gpress-semantic-background: #ffffff;
    }
    
    .gpress-table-of-contents,
    .gpress-page-outline {
        border-width: 2px;
    }
    
    .gpress-outline-toggle {
        border: 2px solid #000000;
    }
}

/* ==========================================================================
   Reduced Motion Support
   ========================================================================== */

@media (prefers-reduced-motion: reduce) {
    .gpress-landmark-skip-links a,
    .gpress-outline-toggle,
    .gpress-table-of-contents a {
        transition: none;
    }
    
    .structured-content h1,
    .structured-content h2,
    .structured-content h3,
    .structured-content h4,
    .structured-content h5,
    .structured-content h6 {
        scroll-behavior: auto;
    }
}

/* ==========================================================================
   Print Styles
   ========================================================================== */

@media print {
    .gpress-landmark-skip-links,
    .gpress-outline-toggle,
    .has-document-outline [role]::before {
        display: none !important;
    }
    
    .gpress-table-of-contents {
        background: transparent;
        border: 1px solid #000000;
    }
    
    .gpress-page-outline {
        background: transparent;
        border: 1px solid #000000;
    }
    
    .gpress-table-of-contents a,
    .gpress-page-outline a {
        color: #000000;
        text-decoration: underline;
    }
}
```

## 6. Update Functions.php

### File: `functions.php` (Update)
```php
// ... existing code ...

/**
 * Require Semantic Structure Files
 */
require_once GPRESS_INC_DIR . '/semantic-structure.php';
require_once GPRESS_INC_DIR . '/aria-landmarks.php';
require_once GPRESS_INC_DIR . '/microdata.php';

/**
 * Add Semantic Structure Support
 */
function gpress_semantic_structure_support() {
    // Enable enhanced semantic HTML5 support
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
    
    // Add semantic customizer settings
    add_action('customize_register', 'gpress_semantic_customizer_settings');
}
add_action('after_setup_theme', 'gpress_semantic_structure_support');

/**
 * Add Semantic Customizer Settings
 */
function gpress_semantic_customizer_settings($wp_customize) {
    // Semantic Structure Section
    $wp_customize->add_section('gpress_semantic_structure', array(
        'title' => __('Semantic Structure', 'gpress'),
        'description' => __('Configure semantic HTML and accessibility features.', 'gpress'),
        'priority' => 35,
    ));
    
    // Enable Document Outline
    $wp_customize->add_setting('enable_document_outline', array(
        'default' => false,
        'sanitize_callback' => 'wp_validate_boolean',
    ));
    
    $wp_customize->add_control('enable_document_outline', array(
        'label' => __('Enable Document Outline', 'gpress'),
        'description' => __('Show document outline and landmark indicators.', 'gpress'),
        'section' => 'gpress_semantic_structure',
        'type' => 'checkbox',
    ));
    
    // Enable Table of Contents
    $wp_customize->add_setting('enable_table_of_contents', array(
        'default' => false,
        'sanitize_callback' => 'wp_validate_boolean',
    ));
    
    $wp_customize->add_control('enable_table_of_contents', array(
        'label' => __('Enable Table of Contents', 'gpress'),
        'description' => __('Automatically generate table of contents for posts.', 'gpress'),
        'section' => 'gpress_semantic_structure',
        'type' => 'checkbox',
    ));
    
    // Enable Structured Data
    $wp_customize->add_setting('enable_structured_data', array(
        'default' => true,
        'sanitize_callback' => 'wp_validate_boolean',
    ));
    
    $wp_customize->add_control('enable_structured_data', array(
        'label' => __('Enable Structured Data', 'gpress'),
        'description' => __('Add schema.org microdata for better SEO.', 'gpress'),
        'section' => 'gpress_semantic_structure',
        'type' => 'checkbox',
    ));
}

// ... existing code ...
```

## Testing Instructions

### 1. **Installation Testing**
```bash
# Verify files are created correctly
ls -la assets/css/semantic.css
ls -la inc/semantic-structure.php
ls -la assets/js/semantic-nav.js

# Check for PHP syntax errors
php -l inc/semantic-structure.php
php -l inc/aria-landmarks.php
php -l inc/microdata.php
```

### 2. **Semantic Structure Validation**
- Install "axe DevTools" browser extension
- Test with W3C Markup Validator
- Verify ARIA landmarks are properly implemented
- Check heading hierarchy with HeadingsMap extension
- Test with screen reader (NVDA, JAWS, or VoiceOver)

### 3. **Functionality Testing**
- **Skip Links**: Test keyboard navigation (Tab to reveal skip links)
- **Landmark Navigation**: Test Alt+H, Alt+M, Alt+S, Alt+F shortcuts
- **Heading Navigation**: Test Alt+1-6, Alt+N, Alt+P shortcuts
- **Table of Contents**: Enable in Customizer, check auto-generation
- **Document Outline**: Enable in Customizer, test outline toggle

### 4. **Microdata Validation**
- Use Google's Rich Results Test
- Validate structured data with Schema.org validator
- Check JSON-LD output in page source
- Test breadcrumb structured data

### 5. **Accessibility Testing**
```bash
# Test with axe-core
npm install -g @axe-core/cli
axe http://your-site.test --tags wcag2aa

# Check color contrast
# Use WebAIM Contrast Checker

# Validate keyboard navigation
# Test all interactive elements with Tab/Shift+Tab
```

### 6. **Performance Testing**
- Check conditional asset loading works
- Verify CSS/JS only loads when semantic features are used
- Test with PageSpeed Insights
- Monitor Core Web Vitals impact

### 7. **Cross-Browser Testing**
- Test semantic features in Chrome, Firefox, Safari, Edge
- Verify ARIA support across browsers
- Check microdata rendering consistency
- Test keyboard shortcuts functionality

## Next Steps
- **Step 16**: Performance Testing Implementation
- **Step 17**: Cross-Browser Testing Setup
- **Step 18**: Quality Assurance System
- **Step 19**: Theme Documentation Creation
- **Step 20**: Deployment & Distribution

## Key Benefits
- **🎯 Enhanced Accessibility**: WCAG 2.1 AA compliant semantic structure
- **🔍 Better SEO**: Rich microdata and structured markup
- **⌨️ Keyboard Navigation**: Advanced keyboard shortcuts for power users
- **📖 Reading Experience**: Table of contents and document outline features
- **🚀 Performance**: Conditional loading ensures fast page speeds
- **🔧 Developer-Friendly**: Clean, semantic markup for easier maintenance