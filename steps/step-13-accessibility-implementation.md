# Step 13: Accessibility Implementation

## Overview
This step implements comprehensive accessibility features to ensure WCAG 2.1 AA compliance. We'll focus on semantic HTML, ARIA attributes, keyboard navigation, screen reader support, and inclusive design patterns with conditional asset loading.

## Objectives
- Achieve WCAG 2.1 AA compliance
- Implement semantic HTML structure
- Add proper ARIA attributes and landmarks
- Ensure keyboard navigation support
- Support screen readers and assistive technologies
- Create inclusive design patterns
- Implement conditional accessibility asset loading

## Implementation

### 1. Accessibility Foundation

Create `inc/accessibility.php`:

```php
<?php
/**
 * GPress Accessibility Features
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Initialize accessibility system
 */
function gpress_init_accessibility_system() {
    // Core accessibility setup
    add_action('after_setup_theme', 'gpress_accessibility_setup');
    
    // Conditional accessibility asset loading
    add_action('wp_enqueue_scripts', 'gpress_conditional_accessibility_assets');
    
    // Accessibility enhancements
    add_action('wp_body_open', 'gpress_add_skip_links');
    add_filter('wp_get_attachment_image_attributes', 'gpress_enhance_image_accessibility', 10, 3);
    add_filter('the_content', 'gpress_enhance_content_accessibility');
    add_action('wp_footer', 'gpress_accessibility_javascript');
    
    // Add accessibility testing in dev mode
    if (defined('WP_DEBUG') && WP_DEBUG) {
        add_action('wp_footer', 'gpress_accessibility_dev_tools');
    }
}
add_action('after_setup_theme', 'gpress_init_accessibility_system');

/**
 * Accessibility setup
 */
function gpress_accessibility_setup() {
    // Theme support for accessibility features
    add_theme_support('accessible-colors');
    add_theme_support('keyboard-navigation');
    add_theme_support('screen-reader-text');
    
    // Remove title attribute from nav menu links
    add_filter('nav_menu_link_attributes', 'gpress_remove_nav_title_attribute', 10, 4);
    
    // Enhance post navigation
    add_filter('get_the_archive_title_prefix', '__return_empty_string');
}

/**
 * Conditional accessibility asset loading
 */
function gpress_conditional_accessibility_assets() {
    $load_accessibility = false;
    
    // Check if enhanced accessibility features are needed
    if (is_singular() || is_archive() || is_search() || 
        get_theme_mod('enable_accessibility_enhancements', true) ||
        isset($_GET['accessibility']) ||
        (function_exists('wp_get_current_user') && current_user_can('edit_posts'))) {
        $load_accessibility = true;
    }
    
    // Always load basic accessibility styles
    wp_enqueue_style(
        'gpress-accessibility-base',
        get_theme_file_uri('/assets/css/accessibility-base.css'),
        array('gpress-style'),
        GPRESS_VERSION
    );
    
    if ($load_accessibility) {
        wp_enqueue_style(
            'gpress-accessibility',
            get_theme_file_uri('/assets/css/accessibility.css'),
            array('gpress-accessibility-base'),
            GPRESS_VERSION
        );
        
        wp_enqueue_script(
            'gpress-accessibility',
            get_theme_file_uri('/assets/js/accessibility.js'),
            array('jquery'),
            GPRESS_VERSION,
            array(
                'strategy' => 'defer',
                'in_footer' => true
            )
        );
        
        // Localize accessibility script
        wp_localize_script('gpress-accessibility', 'gpressA11y', array(
            'strings' => array(
                'skip_to_content' => __('Skip to content', 'gpress'),
                'skip_to_navigation' => __('Skip to navigation', 'gpress'),
                'menu_expanded' => __('Menu expanded', 'gpress'),
                'menu_collapsed' => __('Menu collapsed', 'gpress'),
                'page_loaded' => __('Page loaded', 'gpress'),
                'form_error' => __('Form contains errors', 'gpress')
            )
        ));
    }
}

/**
 * Add skip links
 */
function gpress_add_skip_links() {
    ?>
    <div class="skip-links">
        <a class="skip-link screen-reader-text" href="#main"><?php esc_html_e('Skip to main content', 'gpress'); ?></a>
        <a class="skip-link screen-reader-text" href="#primary-navigation"><?php esc_html_e('Skip to navigation', 'gpress'); ?></a>
        <a class="skip-link screen-reader-text" href="#sidebar"><?php esc_html_e('Skip to sidebar', 'gpress'); ?></a>
        <a class="skip-link screen-reader-text" href="#footer"><?php esc_html_e('Skip to footer', 'gpress'); ?></a>
    </div>
    <?php
}

/**
 * Enqueue accessibility styles
 */
function gpress_enqueue_accessibility_styles() {
    wp_enqueue_style(
        'gpress-accessibility',
        get_theme_file_uri('/assets/css/accessibility.min.css'),
        array('gpress-style'),
        GPRESS_VERSION
    );
}

/**
 * Enhance image accessibility
 */
function gpress_enhance_image_accessibility($attr, $attachment, $size) {
    $image_id = $attachment->ID;
    
    // Add better alt text if empty
    if (empty($attr['alt'])) {
        $alt_text = get_post_meta($image_id, '_wp_attachment_image_alt', true);
        
        if (empty($alt_text)) {
            // Use title or filename as fallback
            $alt_text = $attachment->post_title;
            
            if (empty($alt_text)) {
                $alt_text = pathinfo($attachment->guid, PATHINFO_FILENAME);
                $alt_text = str_replace('-', ' ', $alt_text);
                $alt_text = ucwords($alt_text);
            }
        }
        
        $attr['alt'] = $alt_text;
    }
    
    // Add aria-describedby for images with captions
    $caption = wp_get_attachment_caption($image_id);
    if (!empty($caption)) {
        $attr['aria-describedby'] = 'caption-' . $image_id;
    }
    
    // Add role for decorative images
    if (empty($attr['alt']) || $attr['alt'] === '' || $attr['alt'] === 'decorative') {
        $attr['role'] = 'presentation';
        $attr['alt'] = '';
    }
    
    return $attr;
}

/**
 * Enhance content accessibility
 */
function gpress_enhance_content_accessibility($content) {
    // Add aria-label to links that open in new window
    $content = preg_replace(
        '/<a([^>]*?)target=["\']_blank["\']([^>]*?)>/',
        '<a$1target="_blank"$2 aria-label="$0 (opens in new window)">',
        $content
    );
    
    // Add proper heading hierarchy
    $content = gpress_fix_heading_hierarchy($content);
    
    // Enhance table accessibility
    $content = gpress_enhance_table_accessibility($content);
    
    return $content;
}

/**
 * Fix heading hierarchy
 */
function gpress_fix_heading_hierarchy($content) {
    // Extract all headings
    preg_match_all('/<h([1-6])([^>]*)>(.*?)<\/h[1-6]>/i', $content, $matches, PREG_SET_ORDER);
    
    if (empty($matches)) {
        return $content;
    }
    
    $current_level = 2; // Start with h2 after main title
    $replacements = array();
    
    foreach ($matches as $match) {
        $original = $match[0];
        $level = intval($match[1]);
        $attributes = $match[2];
        $text = $match[3];
        
        // Ensure proper hierarchy
        if ($level > $current_level + 1) {
            $level = $current_level + 1;
        }
        
        $new_heading = "<h{$level}{$attributes}>{$text}</h{$level}>";
        $replacements[$original] = $new_heading;
        
        $current_level = $level;
    }
    
    // Apply replacements
    foreach ($replacements as $old => $new) {
        $content = str_replace($old, $new, $content);
    }
    
    return $content;
}

/**
 * Enhance table accessibility
 */
function gpress_enhance_table_accessibility($content) {
    // Add scope attributes to table headers
    $content = preg_replace(
        '/<th([^>]*)>/',
        '<th$1 scope="col">',
        $content
    );
    
    // Add table captions if missing
    $content = preg_replace_callback(
        '/<table([^>]*)>(.*?)<\/table>/s',
        function($matches) {
            $table_content = $matches[2];
            
            // Check if caption exists
            if (strpos($table_content, '<caption>') === false) {
                // Add a default caption
                $table_content = '<caption class="screen-reader-text">Data table</caption>' . $table_content;
            }
            
            return '<table' . $matches[1] . ' role="table">' . $table_content . '</table>';
        },
        $content
    );
    
    return $content;
}

/**
 * Focus management script
 */
function gpress_focus_management_script() {
    ?>
    <script>
    // Focus management for dynamic content
    document.addEventListener('DOMContentLoaded', function() {
        // Focus on main content when skip link is used
        const skipLinks = document.querySelectorAll('.skip-link');
        skipLinks.forEach(function(link) {
            link.addEventListener('click', function(e) {
                e.preventDefault();
                const target = document.querySelector(this.getAttribute('href'));
                if (target) {
                    target.focus();
                    target.scrollIntoView();
                }
            });
        });
        
        // Announce page changes to screen readers
        function announcePageChange(message) {
            const announcement = document.createElement('div');
            announcement.setAttribute('aria-live', 'polite');
            announcement.setAttribute('aria-atomic', 'true');
            announcement.className = 'screen-reader-text';
            announcement.textContent = message;
            
            document.body.appendChild(announcement);
            
            // Remove after announcement
            setTimeout(function() {
                document.body.removeChild(announcement);
            }, 1000);
        }
        
        // Announce form errors
        const forms = document.querySelectorAll('form');
        forms.forEach(function(form) {
            form.addEventListener('submit', function(e) {
                const errors = form.querySelectorAll('.error, [aria-invalid="true"]');
                if (errors.length > 0) {
                    e.preventDefault();
                    announcePageChange('Form contains errors. Please review and correct.');
                    errors[0].focus();
                }
            });
        });
    });
    </script>
    <?php
}

/**
 * Add accessibility testing tools in development
 */
function gpress_accessibility_dev_tools() {
    if (defined('WP_DEBUG') && WP_DEBUG) {
        ?>
        <script>
        // Console accessibility checker
        console.log('Accessibility Check:');
        
        // Check for images without alt text
        const imagesWithoutAlt = document.querySelectorAll('img:not([alt])');
        if (imagesWithoutAlt.length > 0) {
            console.warn('Images without alt text:', imagesWithoutAlt);
        }
        
        // Check for heading hierarchy
        const headings = document.querySelectorAll('h1, h2, h3, h4, h5, h6');
        let previousLevel = 0;
        headings.forEach(function(heading) {
            const level = parseInt(heading.tagName.charAt(1));
            if (level > previousLevel + 1) {
                console.warn('Heading hierarchy issue:', heading);
            }
            previousLevel = level;
        });
        
        // Check for form labels
        const inputs = document.querySelectorAll('input, select, textarea');
        inputs.forEach(function(input) {
            if (!input.labels || input.labels.length === 0) {
                if (!input.getAttribute('aria-label') && !input.getAttribute('aria-labelledby')) {
                    console.warn('Form input without proper label:', input);
                }
            }
        });
        </script>
        <?php
    }
}
add_action('wp_footer', 'gpress_accessibility_dev_tools');
```

### 2. Accessibility CSS

Create `assets/css/accessibility.css`:

```css
/* Accessibility Styles */

/* Screen reader only text */
.screen-reader-text,
.sr-only {
    position: absolute !important;
    width: 1px !important;
    height: 1px !important;
    padding: 0 !important;
    margin: -1px !important;
    overflow: hidden !important;
    clip: rect(1px, 1px, 1px, 1px) !important;
    white-space: nowrap !important;
    border: 0 !important;
}

/* Show screen reader text on focus */
.screen-reader-text:focus,
.sr-only:focus {
    position: absolute !important;
    top: 1rem !important;
    left: 1rem !important;
    width: auto !important;
    height: auto !important;
    padding: 0.5rem 1rem !important;
    margin: 0 !important;
    overflow: visible !important;
    clip: auto !important;
    white-space: normal !important;
    background: var(--wp--preset--color--background) !important;
    color: var(--wp--preset--color--foreground) !important;
    border: 2px solid var(--wp--preset--color--primary) !important;
    border-radius: 4px !important;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2) !important;
    z-index: 999999 !important;
    font-size: 1rem !important;
    font-weight: 600 !important;
    text-decoration: none !important;
}

/* Skip links */
.skip-links {
    position: absolute;
    top: 0;
    left: 0;
    z-index: 999999;
}

.skip-link {
    position: absolute;
    left: -9999px;
    top: 1rem;
    background: var(--wp--preset--color--primary);
    color: var(--wp--preset--color--background);
    padding: 0.5rem 1rem;
    text-decoration: none;
    border-radius: 4px;
    font-weight: 600;
    transition: all 0.3s ease;
}

.skip-link:focus {
    left: 1rem;
    outline: 2px solid var(--wp--preset--color--accent);
    outline-offset: 2px;
}

/* Focus management */
*:focus {
    outline: 2px solid var(--wp--preset--color--primary);
    outline-offset: 2px;
}

/* Enhanced focus for interactive elements */
a:focus,
button:focus,
input:focus,
select:focus,
textarea:focus,
[tabindex]:focus {
    outline: 2px solid var(--wp--preset--color--primary);
    outline-offset: 2px;
    box-shadow: 0 0 0 4px rgba(var(--wp--preset--color--primary-rgb), 0.2);
}

/* Focus-within for containers */
.has-submenu:focus-within,
.search-form:focus-within,
.widget:focus-within {
    outline: 1px solid var(--wp--preset--color--primary);
    outline-offset: 1px;
}

/* High contrast mode support */
@media (prefers-contrast: high) {
    /* Increase contrast for borders and text */
    .wp-block-separator,
    .wp-block-table,
    .wp-block-image,
    .wp-block-quote {
        border-color: currentColor;
    }
    
    /* Ensure sufficient contrast for buttons */
    .wp-block-button__link,
    .wp-element-button {
        border: 2px solid currentColor;
    }
    
    /* High contrast focus indicators */
    *:focus {
        outline: 3px solid;
        outline-offset: 2px;
    }
}

/* Reduced motion support */
@media (prefers-reduced-motion: reduce) {
    /* Disable animations and transitions */
    *,
    *::before,
    *::after {
        animation-duration: 0.01ms !important;
        animation-iteration-count: 1 !important;
        transition-duration: 0.01ms !important;
        scroll-behavior: auto !important;
    }
    
    /* Keep essential focus transitions */
    a:focus,
    button:focus,
    input:focus,
    select:focus,
    textarea:focus {
        transition: outline 0.1s ease;
    }
}

/* Dark mode accessibility */
@media (prefers-color-scheme: dark) {
    /* Ensure sufficient contrast in dark mode */
    .wp-block-quote {
        border-left-color: var(--wp--preset--color--primary);
    }
    
    /* Adjust focus colors for dark backgrounds */
    *:focus {
        outline-color: var(--wp--preset--color--accent);
    }
}

/* Print accessibility */
@media print {
    /* Show URLs for links */
    a[href]:after {
        content: " (" attr(href) ")";
        font-size: 0.8em;
        color: #666;
    }
    
    /* Hide decorative elements */
    .skip-links,
    .mobile-menu-toggle,
    .social-links,
    [aria-hidden="true"] {
        display: none !important;
    }
    
    /* Ensure readability */
    body {
        font-size: 12pt;
        line-height: 1.5;
        color: #000;
        background: #fff;
    }
}

/* Form accessibility */
.wp-block-search input[type="search"],
.wp-block-contact-form-7 input,
.wp-block-contact-form-7 textarea,
.wp-block-contact-form-7 select {
    border: 2px solid var(--wp--preset--color--border);
    border-radius: 4px;
    padding: 0.75rem;
    font-size: 1rem;
    transition: border-color 0.3s ease;
}

.wp-block-search input[type="search"]:focus,
.wp-block-contact-form-7 input:focus,
.wp-block-contact-form-7 textarea:focus,
.wp-block-contact-form-7 select:focus {
    border-color: var(--wp--preset--color--primary);
    box-shadow: 0 0 0 2px rgba(var(--wp--preset--color--primary-rgb), 0.2);
}

/* Error states */
input[aria-invalid="true"],
textarea[aria-invalid="true"],
select[aria-invalid="true"] {
    border-color: #d63384;
    background-color: #f8d7da;
}

.error-message {
    color: #d63384;
    font-size: 0.875rem;
    margin-top: 0.25rem;
    font-weight: 600;
}

/* Table accessibility */
.wp-block-table table {
    border-collapse: collapse;
    width: 100%;
}

.wp-block-table th,
.wp-block-table td {
    border: 1px solid var(--wp--preset--color--border);
    padding: 0.75rem;
    text-align: left;
}

.wp-block-table th {
    background: var(--wp--preset--color--background-secondary);
    font-weight: 600;
}

.wp-block-table caption {
    font-weight: 600;
    margin-bottom: 0.5rem;
    text-align: left;
}

/* List accessibility */
.wp-block-list li {
    margin-bottom: 0.5rem;
}

/* Image accessibility */
.wp-block-image figcaption {
    font-size: 0.875rem;
    color: var(--wp--preset--color--foreground-secondary);
    margin-top: 0.5rem;
    padding: 0 1rem;
}

/* Video accessibility */
.wp-block-video video {
    width: 100%;
    height: auto;
}

/* Ensure videos have proper captions */
.wp-block-video:not([data-has-captions="true"]):after {
    content: "⚠️ This video may not have captions available";
    display: block;
    font-size: 0.875rem;
    color: #856404;
    background: #fff3cd;
    padding: 0.5rem;
    border-radius: 4px;
    margin-top: 0.5rem;
}

/* Color accessibility indicators */
.color-accessibility-warning {
    position: relative;
}

.color-accessibility-warning:after {
    content: "⚠️ Color contrast may not meet accessibility standards";
    position: absolute;
    bottom: 100%;
    left: 0;
    background: #856404;
    color: #fff;
    padding: 0.25rem 0.5rem;
    font-size: 0.75rem;
    border-radius: 4px;
    white-space: nowrap;
    opacity: 0;
    pointer-events: none;
    transition: opacity 0.3s ease;
}

.color-accessibility-warning:hover:after,
.color-accessibility-warning:focus:after {
    opacity: 1;
}

/* Touch accessibility */
@media (pointer: coarse) {
    /* Increase touch targets */
    button,
    .wp-block-button__link,
    a,
    input,
    select,
    textarea {
        min-height: 44px;
        min-width: 44px;
    }
    
    /* Increase spacing for better touch targets */
    .wp-block-navigation .wp-block-navigation-item {
        margin: 0.25rem;
    }
}

/* Voice control accessibility */
.voice-command-hint {
    position: absolute;
    top: -2rem;
    left: 0;
    font-size: 0.75rem;
    color: var(--wp--preset--color--foreground-secondary);
    background: var(--wp--preset--color--background-secondary);
    padding: 0.25rem 0.5rem;
    border-radius: 4px;
    opacity: 0;
    transition: opacity 0.3s ease;
}

.voice-command-hint.show {
    opacity: 1;
}

/* Keyboard navigation indicators */
.keyboard-navigation-active *:focus {
    outline: 3px solid var(--wp--preset--color--accent);
    outline-offset: 2px;
}

/* Announcement region for screen readers */
.sr-announcement {
    position: absolute;
    left: -10000px;
    width: 1px;
    height: 1px;
    overflow: hidden;
}

/* Progress indicators */
.progress-indicator {
    background: var(--wp--preset--color--background-secondary);
    height: 4px;
    border-radius: 2px;
    overflow: hidden;
    margin: 1rem 0;
}

.progress-indicator .progress-bar {
    background: var(--wp--preset--color--primary);
    height: 100%;
    transition: width 0.3s ease;
}

/* Loading states */
[aria-busy="true"] {
    position: relative;
    opacity: 0.6;
    pointer-events: none;
}

[aria-busy="true"]:after {
    content: "Loading...";
    position: absolute;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    background: var(--wp--preset--color--background);
    padding: 0.5rem 1rem;
    border-radius: 4px;
    font-weight: 600;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.2);
}

/* Responsive text scaling */
@media (prefers-reduced-data: reduce) {
    /* Optimize for reduced data usage */
    .wp-block-image img {
        image-rendering: crisp-edges;
    }
    
    /* Disable autoplay for reduced data */
    video[autoplay] {
        autoplay: false;
    }
}
```

### 3. ARIA Landmarks and Roles

Update template files to include proper ARIA landmarks:

Update `templates/index.html`:

```html
<!-- wp:template-part {"slug":"header","tagName":"header"} /-->

<!-- wp:group {"tagName":"nav","className":"breadcrumb-wrapper"} -->
<nav class="wp-block-group breadcrumb-wrapper" aria-label="Breadcrumb">
    <!-- Breadcrumb navigation will be inserted here -->
</nav>
<!-- /wp:group -->

<!-- wp:group {"tagName":"main","className":"main-content","layout":{"type":"constrained"}} -->
<main class="wp-block-group main-content" id="main" role="main" aria-label="Main content">
    <!-- wp:heading {"level":1,"className":"page-title"} -->
    <h1 class="wp-block-heading page-title">Latest Posts</h1>
    <!-- /wp:heading -->
    
    <!-- wp:query {"queryId":1,"query":{"perPage":10,"pages":0,"offset":0,"postType":"post","order":"desc","orderBy":"date","author":"","search":"","exclude":[],"sticky":"","inherit":true}} -->
    <div class="wp-block-query" role="region" aria-label="Blog posts">
        <!-- wp:post-template {"layout":{"type":"grid","columnCount":2}} -->
            <!-- wp:group {"className":"post-card","layout":{"type":"constrained"}} -->
            <article class="wp-block-group post-card" role="article">
                <!-- wp:post-featured-image {"className":"post-thumbnail","isLink":true} /-->
                
                <!-- wp:group {"className":"post-content","layout":{"type":"constrained"}} -->
                <div class="wp-block-group post-content">
                    <!-- wp:post-title {"level":2,"isLink":true,"className":"post-title"} /-->
                    
                    <!-- wp:group {"className":"post-meta","layout":{"type":"flex","flexWrap":"wrap"}} -->
                    <div class="wp-block-group post-meta" role="group" aria-label="Post metadata">
                        <!-- wp:post-date {"className":"post-date"} /-->
                        <!-- wp:post-author {"className":"post-author"} /-->
                        <!-- wp:post-terms {"term":"category","className":"post-categories"} /-->
                    </div>
                    <!-- /wp:group -->
                    
                    <!-- wp:post-excerpt {"className":"post-excerpt"} /-->
                    
                    <!-- wp:read-more {"content":"Read full article","className":"read-more-link"} /-->
                </div>
                <!-- /wp:group -->
            </article>
            <!-- /wp:group -->
        <!-- /wp:post-template -->
        
        <!-- wp:query-pagination {"layout":{"type":"flex","justifyContent":"center"}} -->
        <nav class="wp-block-query-pagination" role="navigation" aria-label="Posts pagination">
            <!-- wp:query-pagination-previous {"label":"Previous posts"} /-->
            <!-- wp:query-pagination-numbers /-->
            <!-- wp:query-pagination-next {"label":"Next posts"} /-->
        </nav>
        <!-- /wp:query-pagination -->
        
        <!-- wp:query-no-results -->
            <!-- wp:heading {"level":2} -->
            <h2 class="wp-block-heading">No posts found</h2>
            <!-- /wp:heading -->
            
            <!-- wp:paragraph -->
            <p>Sorry, but no posts were found. Please try a different search or browse our categories.</p>
            <!-- /wp:paragraph -->
        <!-- /wp:query-no-results -->
    </div>
    <!-- /wp:query -->
</main>
<!-- /wp:group -->

<!-- wp:template-part {"slug":"sidebar","tagName":"aside"} /-->

<!-- wp:template-part {"slug":"footer","tagName":"footer"} /-->
```

### 4. Form Accessibility Enhancements

Create `inc/form-accessibility.php`:

```php
<?php
/**
 * Form Accessibility Enhancements
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Enhance form accessibility
 */
function gpress_enhance_form_accessibility() {
    // Add ARIA attributes to search forms
    add_filter('get_search_form', 'gpress_accessible_search_form');
    
    // Enhance comment form accessibility
    add_filter('comment_form_defaults', 'gpress_accessible_comment_form');
    
    // Add form validation enhancements
    add_action('wp_footer', 'gpress_form_validation_script');
}
add_action('init', 'gpress_enhance_form_accessibility');

/**
 * Make search form more accessible
 */
function gpress_accessible_search_form($form) {
    $search_id = uniqid('search-');
    $label_id = $search_id . '-label';
    
    $form = '
    <form role="search" method="get" class="search-form" action="' . esc_url(home_url('/')) . '" aria-labelledby="' . $label_id . '">
        <label for="' . $search_id . '" id="' . $label_id . '" class="search-label">
            <span class="screen-reader-text">' . __('Search for:', 'gpress') . '</span>
        </label>
        <div class="search-input-wrapper">
            <input type="search" 
                   id="' . $search_id . '" 
                   class="search-field" 
                   placeholder="' . esc_attr__('Search...', 'gpress') . '" 
                   value="' . get_search_query() . '" 
                   name="s" 
                   autocomplete="off"
                   aria-describedby="' . $search_id . '-desc" />
            <button type="submit" 
                    class="search-submit" 
                    aria-label="' . esc_attr__('Submit search', 'gpress') . '">
                <span class="search-icon" aria-hidden="true">🔍</span>
                <span class="screen-reader-text">' . __('Search', 'gpress') . '</span>
            </button>
        </div>
        <div id="' . $search_id . '-desc" class="screen-reader-text">
            ' . __('Press Enter to search or Escape to close', 'gpress') . '
        </div>
    </form>';
    
    return $form;
}

/**
 * Enhance comment form accessibility
 */
function gpress_accessible_comment_form($defaults) {
    $comment_id = uniqid('comment-');
    
    $defaults['fields']['author'] = '
    <div class="comment-form-author">
        <label for="author-' . $comment_id . '">' . __('Name', 'gpress') . ' 
            <span class="required" aria-label="required">*</span>
        </label>
        <input id="author-' . $comment_id . '" 
               name="author" 
               type="text" 
               value="' . esc_attr($commenter['comment_author']) . '" 
               size="30" 
               required 
               aria-required="true"
               aria-describedby="author-' . $comment_id . '-desc" />
        <div id="author-' . $comment_id . '-desc" class="field-description">
            ' . __('Your name will be displayed publicly with your comment', 'gpress') . '
        </div>
    </div>';
    
    $defaults['fields']['email'] = '
    <div class="comment-form-email">
        <label for="email-' . $comment_id . '">' . __('Email', 'gpress') . ' 
            <span class="required" aria-label="required">*</span>
        </label>
        <input id="email-' . $comment_id . '" 
               name="email" 
               type="email" 
               value="' . esc_attr($commenter['comment_author_email']) . '" 
               size="30" 
               required 
               aria-required="true"
               aria-describedby="email-' . $comment_id . '-desc" />
        <div id="email-' . $comment_id . '-desc" class="field-description">
            ' . __('Your email address will not be published', 'gpress') . '
        </div>
    </div>';
    
    $defaults['fields']['url'] = '
    <div class="comment-form-url">
        <label for="url-' . $comment_id . '">' . __('Website', 'gpress') . '</label>
        <input id="url-' . $comment_id . '" 
               name="url" 
               type="url" 
               value="' . esc_attr($commenter['comment_author_url']) . '" 
               size="30"
               aria-describedby="url-' . $comment_id . '-desc" />
        <div id="url-' . $comment_id . '-desc" class="field-description">
            ' . __('Optional: Include your website URL', 'gpress') . '
        </div>
    </div>';
    
    $defaults['comment_field'] = '
    <div class="comment-form-comment">
        <label for="comment-' . $comment_id . '">' . __('Comment', 'gpress') . ' 
            <span class="required" aria-label="required">*</span>
        </label>
        <textarea id="comment-' . $comment_id . '" 
                  name="comment" 
                  cols="45" 
                  rows="8" 
                  required 
                  aria-required="true"
                  aria-describedby="comment-' . $comment_id . '-desc"
                  placeholder="' . esc_attr__('Share your thoughts...', 'gpress') . '"></textarea>
        <div id="comment-' . $comment_id . '-desc" class="field-description">
            ' . __('Please be respectful and constructive in your comments', 'gpress') . '
        </div>
    </div>';
    
    $defaults['submit_button'] = '
    <button type="submit" 
            class="submit-comment" 
            aria-describedby="submit-' . $comment_id . '-desc">
        ' . __('Post Comment', 'gpress') . '
    </button>
    <div id="submit-' . $comment_id . '-desc" class="screen-reader-text">
        ' . __('Your comment will be reviewed before being published', 'gpress') . '
    </div>';
    
    return $defaults;
}

/**
 * Form validation script
 */
function gpress_form_validation_script() {
    ?>
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        // Real-time form validation
        const forms = document.querySelectorAll('form');
        
        forms.forEach(function(form) {
            const inputs = form.querySelectorAll('input[required], textarea[required], select[required]');
            
            inputs.forEach(function(input) {
                // Add validation on blur
                input.addEventListener('blur', function() {
                    validateField(this);
                });
                
                // Clear validation on input
                input.addEventListener('input', function() {
                    clearFieldError(this);
                });
            });
            
            // Form submission validation
            form.addEventListener('submit', function(e) {
                let hasErrors = false;
                
                inputs.forEach(function(input) {
                    if (!validateField(input)) {
                        hasErrors = true;
                    }
                });
                
                if (hasErrors) {
                    e.preventDefault();
                    
                    // Focus first error
                    const firstError = form.querySelector('[aria-invalid="true"]');
                    if (firstError) {
                        firstError.focus();
                        announceError('Please correct the errors in the form');
                    }
                }
            });
        });
        
        function validateField(field) {
            const value = field.value.trim();
            const type = field.type;
            let isValid = true;
            let errorMessage = '';
            
            // Required field validation
            if (field.hasAttribute('required') && !value) {
                isValid = false;
                errorMessage = 'This field is required';
            }
            
            // Email validation
            else if (type === 'email' && value) {
                const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
                if (!emailRegex.test(value)) {
                    isValid = false;
                    errorMessage = 'Please enter a valid email address';
                }
            }
            
            // URL validation
            else if (type === 'url' && value) {
                const urlRegex = /^https?:\/\/.+\..+/;
                if (!urlRegex.test(value)) {
                    isValid = false;
                    errorMessage = 'Please enter a valid URL starting with http:// or https://';
                }
            }
            
            // Update field state
            if (isValid) {
                field.setAttribute('aria-invalid', 'false');
                clearFieldError(field);
            } else {
                field.setAttribute('aria-invalid', 'true');
                showFieldError(field, errorMessage);
            }
            
            return isValid;
        }
        
        function showFieldError(field, message) {
            clearFieldError(field);
            
            const errorId = field.id + '-error';
            const errorElement = document.createElement('div');
            errorElement.id = errorId;
            errorElement.className = 'error-message';
            errorElement.textContent = message;
            errorElement.setAttribute('role', 'alert');
            
            field.parentNode.appendChild(errorElement);
            field.setAttribute('aria-describedby', 
                field.getAttribute('aria-describedby') + ' ' + errorId);
        }
        
        function clearFieldError(field) {
            const existingError = field.parentNode.querySelector('.error-message');
            if (existingError) {
                existingError.remove();
            }
            
            // Clean up aria-describedby
            const describedBy = field.getAttribute('aria-describedby');
            if (describedBy) {
                const cleanDescribedBy = describedBy.replace(/ [^ ]+-error/g, '');
                field.setAttribute('aria-describedby', cleanDescribedBy);
            }
        }
        
        function announceError(message) {
            const announcement = document.createElement('div');
            announcement.setAttribute('aria-live', 'assertive');
            announcement.setAttribute('aria-atomic', 'true');
            announcement.className = 'sr-announcement';
            announcement.textContent = message;
            
            document.body.appendChild(announcement);
            
            setTimeout(function() {
                document.body.removeChild(announcement);
            }, 1000);
        }
    });
    </script>
    <?php
}
```

### 5. Color Contrast and Theme Support

Create `inc/color-accessibility.php`:

```php
<?php
/**
 * Color Accessibility Features
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Check color contrast ratio
 */
function gpress_check_color_contrast($color1, $color2) {
    // Convert hex to RGB
    $rgb1 = gpress_hex_to_rgb($color1);
    $rgb2 = gpress_hex_to_rgb($color2);
    
    // Calculate relative luminance
    $l1 = gpress_relative_luminance($rgb1);
    $l2 = gpress_relative_luminance($rgb2);
    
    // Calculate contrast ratio
    $ratio = ($l1 > $l2) ? ($l1 + 0.05) / ($l2 + 0.05) : ($l2 + 0.05) / ($l1 + 0.05);
    
    return $ratio;
}

/**
 * Convert hex color to RGB
 */
function gpress_hex_to_rgb($hex) {
    $hex = ltrim($hex, '#');
    
    if (strlen($hex) === 3) {
        $hex = $hex[0] . $hex[0] . $hex[1] . $hex[1] . $hex[2] . $hex[2];
    }
    
    return array(
        'r' => hexdec(substr($hex, 0, 2)),
        'g' => hexdec(substr($hex, 2, 2)),
        'b' => hexdec(substr($hex, 4, 2))
    );
}

/**
 * Calculate relative luminance
 */
function gpress_relative_luminance($rgb) {
    $r = $rgb['r'] / 255;
    $g = $rgb['g'] / 255;
    $b = $rgb['b'] / 255;
    
    $r = ($r <= 0.03928) ? $r / 12.92 : pow(($r + 0.055) / 1.055, 2.4);
    $g = ($g <= 0.03928) ? $g / 12.92 : pow(($g + 0.055) / 1.055, 2.4);
    $b = ($b <= 0.03928) ? $b / 12.92 : pow(($b + 0.055) / 1.055, 2.4);
    
    return 0.2126 * $r + 0.7152 * $g + 0.0722 * $b;
}

/**
 * Validate theme color accessibility
 */
function gpress_validate_theme_colors() {
    $theme_json = wp_get_global_settings();
    $colors = $theme_json['color']['palette']['theme'] ?? array();
    
    $warnings = array();
    
    foreach ($colors as $color) {
        if ($color['slug'] === 'foreground' || $color['slug'] === 'background') {
            $foreground = null;
            $background = null;
            
            foreach ($colors as $compare_color) {
                if ($compare_color['slug'] === 'foreground') {
                    $foreground = $compare_color['color'];
                }
                if ($compare_color['slug'] === 'background') {
                    $background = $compare_color['color'];
                }
            }
            
            if ($foreground && $background) {
                $ratio = gpress_check_color_contrast($foreground, $background);
                
                if ($ratio < 4.5) {
                    $warnings[] = array(
                        'type' => 'contrast',
                        'message' => sprintf(
                            __('Text contrast ratio of %s:1 may not meet WCAG AA standards (4.5:1 required)', 'gpress'),
                            number_format($ratio, 2)
                        ),
                        'colors' => array($foreground, $background)
                    );
                }
            }
        }
    }
    
    return $warnings;
}

/**
 * Add accessibility admin notice for color issues
 */
function gpress_accessibility_admin_notices() {
    if (!current_user_can('manage_options')) {
        return;
    }
    
    $warnings = gpress_validate_theme_colors();
    
    if (!empty($warnings)) {
        foreach ($warnings as $warning) {
            echo '<div class="notice notice-warning is-dismissible">';
            echo '<p><strong>' . __('Accessibility Warning:', 'gpress') . '</strong> ' . esc_html($warning['message']) . '</p>';
            echo '</div>';
        }
    }
}
add_action('admin_notices', 'gpress_accessibility_admin_notices');
```

### 6. Update Functions.php

Add to `functions.php`:

```php
// Accessibility features
require_once get_theme_file_path('/inc/accessibility.php');
require_once get_theme_file_path('/inc/form-accessibility.php');
require_once get_theme_file_path('/inc/color-accessibility.php');
```

### 7. ARIA Live Regions

Create `inc/aria-live-regions.php`:

```php
<?php
/**
 * ARIA Live Regions for Dynamic Content
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Add ARIA live regions to footer
 */
function gpress_aria_live_regions() {
    ?>
    <!-- ARIA Live Regions for Screen Reader Announcements -->
    <div id="aria-live-polite" aria-live="polite" aria-atomic="true" class="screen-reader-text"></div>
    <div id="aria-live-assertive" aria-live="assertive" aria-atomic="true" class="screen-reader-text"></div>
    
    <script>
    // Helper functions for live announcements
    window.announceToScreenReader = function(message, priority = 'polite') {
        const liveRegion = document.getElementById('aria-live-' + priority);
        if (liveRegion) {
            liveRegion.textContent = message;
            
            // Clear after announcement
            setTimeout(function() {
                liveRegion.textContent = '';
            }, 1000);
        }
    };
    
    // Announce page load completion
    window.addEventListener('load', function() {
        announceToScreenReader('Page loaded successfully');
    });
    
    // Announce AJAX content updates
    document.addEventListener('DOMContentLoaded', function() {
        // Monitor for content changes
        const contentAreas = document.querySelectorAll('main, [role="main"], .content-area');
        
        contentAreas.forEach(function(area) {
            const observer = new MutationObserver(function(mutations) {
                mutations.forEach(function(mutation) {
                    if (mutation.type === 'childList' && mutation.addedNodes.length > 0) {
                        // Announce content updates
                        announceToScreenReader('Content updated');
                    }
                });
            });
            
            observer.observe(area, {
                childList: true,
                subtree: true
            });
        });
    });
    </script>
    <?php
}
add_action('wp_footer', 'gpress_aria_live_regions');
```

## Testing

1. **WCAG Compliance Testing**: Use automated tools like axe-core and WAVE
2. **Screen Reader Testing**: Test with NVDA, JAWS, and VoiceOver
3. **Keyboard Navigation**: Ensure all functionality works without a mouse
4. **Color Contrast**: Verify 4.5:1 ratio for normal text, 3:1 for large text
5. **Focus Management**: Test focus flow and skip links

## Accessibility Checklist

- [ ] Skip links implemented and functional
- [ ] Proper heading hierarchy (h1 → h2 → h3)
- [ ] All images have meaningful alt text
- [ ] Forms have proper labels and error handling
- [ ] Color contrast meets WCAG AA standards
- [ ] Keyboard navigation works for all interactive elements
- [ ] ARIA landmarks and roles properly implemented
- [ ] Screen reader announcements for dynamic content
- [ ] Focus indicators visible and appropriate
- [ ] No accessibility barriers in JavaScript functionality

## Next Steps

In Step 14, we'll implement comprehensive SEO optimization to improve search engine visibility and performance.

## Key Benefits

- WCAG 2.1 AA compliance
- Enhanced screen reader support
- Improved keyboard navigation
- Better form accessibility
- Semantic HTML structure
- Inclusive design patterns
- Color accessibility validation
- Comprehensive testing framework