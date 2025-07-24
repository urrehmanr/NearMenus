# Step 10: Gutenberg Block Support

## Objective
Enhance the **GPress** theme with advanced Gutenberg block support, custom block styles, and intelligent block asset loading. Implement comprehensive block editor enhancements while ensuring optimal performance through conditional loading of block-specific styles and scripts.

## What You'll Learn
- Custom block styles and variations with conditional loading
- Block editor customization and enhancements
- Block pattern creation and management
- Advanced block settings and configurations
- Block-specific asset management
- Editor styling and preview optimization

## Files to Create in This Step

```
assets/css/
├── blocks.css              # Custom block styles
├── blocks.min.css          # Minified block styles
├── editor-blocks.css       # Block editor specific styles
└── block-patterns.css      # Block pattern styles

assets/js/
├── block-editor.js         # Block editor enhancements
├── block-editor.min.js     # Minified block editor script
└── block-variations.js     # Custom block variations

inc/
├── block-editor.php        # Block editor enhancements
├── block-styles.php        # Custom block style registration
├── block-patterns.php      # Block pattern registration
└── block-assets.php        # Conditional block asset loading

patterns/
├── call-to-action.html     # CTA block pattern
├── testimonials.html       # Testimonials block pattern
├── faq-section.html        # FAQ block pattern
└── feature-comparison.html # Feature comparison pattern
```

## 1. Create Block Asset Management

### File: `inc/block-assets.php`
```php
<?php
/**
 * Block Asset Management for GPress Theme
 *
 * @package GPress
 * @version 1.0.0
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Initialize block asset management
 */
function gpress_init_block_assets() {
    // Conditional block asset loading
    add_action('wp_enqueue_scripts', 'gpress_conditional_block_assets');
    add_action('enqueue_block_editor_assets', 'gpress_enqueue_block_editor_assets');
    
    // Block registration
    add_action('init', 'gpress_register_block_styles');
    add_action('init', 'gpress_register_block_patterns');
    
    // Block-specific optimizations
    add_filter('render_block', 'gpress_optimize_block_output', 10, 2);
    add_filter('block_categories_all', 'gpress_add_custom_block_category');
}
add_action('after_setup_theme', 'gpress_init_block_assets');

/**
 * Conditional block asset loading
 */
function gpress_conditional_block_assets() {
    global $post;
    
    $load_blocks = false;
    $load_patterns = false;
    
    // Check if advanced blocks are used
    if ($post && has_blocks($post->post_content)) {
        $blocks = parse_blocks($post->post_content);
        
        foreach ($blocks as $block) {
            // Check for blocks that need enhanced styling
            if (in_array($block['blockName'], [
                'core/quote',
                'core/pullquote', 
                'core/table',
                'core/media-text',
                'core/columns',
                'core/cover',
                'core/gallery'
            ])) {
                $load_blocks = true;
            }
            
            // Check for pattern blocks
            if (strpos($block['blockName'] ?? '', 'gpress/') === 0) {
                $load_patterns = true;
            }
        }
    }
    
    // Load on editor pages
    if (is_admin() || is_customize_preview()) {
        $load_blocks = true;
        $load_patterns = true;
    }
    
    if ($load_blocks) {
        wp_enqueue_style(
            'gpress-blocks',
            get_theme_file_uri('/assets/css/blocks.css'),
            array('gpress-style'),
            GPRESS_VERSION
        );
        
        wp_enqueue_script(
            'gpress-block-variations',
            get_theme_file_uri('/assets/js/block-variations.js'),
            array('wp-blocks', 'wp-element', 'wp-editor'),
            GPRESS_VERSION,
            array('strategy' => 'defer', 'in_footer' => true)
        );
    }
    
    if ($load_patterns) {
        wp_enqueue_style(
            'gpress-block-patterns',
            get_theme_file_uri('/assets/css/block-patterns.css'),
            array('gpress-blocks'),
            GPRESS_VERSION
        );
    }
}

/**
 * Enqueue block editor assets
 */
function gpress_enqueue_block_editor_assets() {
    wp_enqueue_style(
        'gpress-editor-blocks',
        get_theme_file_uri('/assets/css/editor-blocks.css'),
        array('wp-edit-blocks'),
        GPRESS_VERSION
    );
    
    wp_enqueue_script(
        'gpress-block-editor',
        get_theme_file_uri('/assets/js/block-editor.js'),
        array('wp-blocks', 'wp-dom-ready', 'wp-edit-post'),
        GPRESS_VERSION,
        array('strategy' => 'defer', 'in_footer' => true)
    );
    
    // Localize editor script
    wp_localize_script('gpress-block-editor', 'gpressBlocks', array(
        'themeUrl' => get_template_directory_uri(),
        'patterns' => array(
            'cta' => __('Call to Action', 'gpress'),
            'testimonials' => __('Testimonials', 'gpress'),
            'faq' => __('FAQ Section', 'gpress'),
            'features' => __('Feature Comparison', 'gpress')
        )
    ));
}

/**
 * Optimize block output
 */
function gpress_optimize_block_output($block_content, $block) {
    // Add performance optimizations to blocks
    if ($block['blockName'] === 'core/image') {
        // Add loading="lazy" to images if not present
        if (strpos($block_content, 'loading=') === false) {
            $block_content = str_replace('<img ', '<img loading="lazy" ', $block_content);
        }
    }
    
    // Add ARIA labels to media blocks
    if ($block['blockName'] === 'core/video') {
        if (strpos($block_content, 'aria-label=') === false) {
            $block_content = str_replace('<video ', '<video aria-label="' . __('Video content', 'gpress') . '" ', $block_content);
        }
    }
    
    return $block_content;
}

/**
 * Add custom block category
 */
function gpress_add_custom_block_category($categories) {
    return array_merge(
        $categories,
        array(
            array(
                'slug'  => 'gpress',
                'title' => __('GPress Blocks', 'gpress'),
                'icon'  => 'wordpress-alt'
            )
        )
    );
}
```

## 2. Create Block Style Registration

### File: `inc/block-styles.php`
```php
<?php
/**
 * Block Style Registration for GPress Theme
 *
 * @package GPress
 * @version 1.0.0
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Register custom block styles
 */
function gpress_register_block_styles() {
    // Quote block styles
    register_block_style(
        'core/quote',
        array(
            'name'  => 'gpress-modern-quote',
            'label' => __('Modern Quote', 'gpress'),
        )
    );
    
    register_block_style(
        'core/quote',
        array(
            'name'  => 'gpress-highlight-quote',
            'label' => __('Highlight Quote', 'gpress'),
        )
    );
    
    // Button block styles
    register_block_style(
        'core/button',
        array(
            'name'  => 'gpress-gradient-button',
            'label' => __('Gradient Button', 'gpress'),
        )
    );
    
    register_block_style(
        'core/button',
        array(
            'name'  => 'gpress-outline-button',
            'label' => __('Outline Button', 'gpress'),
        )
    );
    
    // Columns block styles
    register_block_style(
        'core/columns',
        array(
            'name'  => 'gpress-card-columns',
            'label' => __('Card Columns', 'gpress'),
        )
    );
    
    register_block_style(
        'core/columns',
        array(
            'name'  => 'gpress-feature-columns',
            'label' => __('Feature Columns', 'gpress'),
        )
    );
    
    // Cover block styles
    register_block_style(
        'core/cover',
        array(
            'name'  => 'gpress-overlay-cover',
            'label' => __('Overlay Cover', 'gpress'),
        )
    );
    
    // Table block styles
    register_block_style(
        'core/table',
        array(
            'name'  => 'gpress-striped-table',
            'label' => __('Striped Table', 'gpress'),
        )
    );
    
    register_block_style(
        'core/table',
        array(
            'name'  => 'gpress-minimal-table',
            'label' => __('Minimal Table', 'gpress'),
        )
    );
    
    // List block styles
    register_block_style(
        'core/list',
        array(
            'name'  => 'gpress-checkmark-list',
            'label' => __('Checkmark List', 'gpress'),
        )
    );
    
    register_block_style(
        'core/list',
        array(
            'name'  => 'gpress-arrow-list',
            'label' => __('Arrow List', 'gpress'),
        )
    );
    
    // Gallery block styles
    register_block_style(
        'core/gallery',
        array(
            'name'  => 'gpress-masonry-gallery',
            'label' => __('Masonry Gallery', 'gpress'),
        )
    );
}

/**
 * Register block editor settings
 */
function gpress_block_editor_settings() {
    // Add theme support for block features
    add_theme_support('editor-color-palette', array(
        array(
            'name'  => __('Primary', 'gpress'),
            'slug'  => 'primary',
            'color' => '#0073aa',
        ),
        array(
            'name'  => __('Secondary', 'gpress'),
            'slug'  => 'secondary',
            'color' => '#005177',
        ),
        array(
            'name'  => __('Accent', 'gpress'),
            'slug'  => 'accent',
            'color' => '#00a0d2',
        ),
        array(
            'name'  => __('Success', 'gpress'),
            'slug'  => 'success',
            'color' => '#46b450',
        ),
        array(
            'name'  => __('Warning', 'gpress'),
            'slug'  => 'warning',
            'color' => '#ffb900',
        ),
        array(
            'name'  => __('Error', 'gpress'),
            'slug'  => 'error',
            'color' => '#dc3232',
        ),
        array(
            'name'  => __('Dark', 'gpress'),
            'slug'  => 'dark',
            'color' => '#1e1e1e',
        ),
        array(
            'name'  => __('Light', 'gpress'),
            'slug'  => 'light',
            'color' => '#f8f9fa',
        ),
    ));
    
    // Add theme support for font sizes
    add_theme_support('editor-font-sizes', array(
        array(
            'name' => __('Small', 'gpress'),
            'size' => 14,
            'slug' => 'small'
        ),
        array(
            'name' => __('Regular', 'gpress'),
            'size' => 16,
            'slug' => 'regular'
        ),
        array(
            'name' => __('Medium', 'gpress'),
            'size' => 20,
            'slug' => 'medium'
        ),
        array(
            'name' => __('Large', 'gpress'),
            'size' => 24,
            'slug' => 'large'
        ),
        array(
            'name' => __('Extra Large', 'gpress'),
            'size' => 32,
            'slug' => 'extra-large'
        ),
    ));
    
    // Disable custom font sizes and colors
    add_theme_support('disable-custom-font-sizes');
    add_theme_support('disable-custom-colors');
    
    // Add wide and full alignment support
    add_theme_support('align-wide');
    
    // Add responsive embeds
    add_theme_support('responsive-embeds');
    
    // Add custom line height support
    add_theme_support('custom-line-height');
    
    // Add custom spacing support
    add_theme_support('custom-spacing');
}
add_action('after_setup_theme', 'gpress_block_editor_settings');
```

## 3. Create Block Pattern Registration

### File: `inc/block-patterns.php`
```php
<?php
/**
 * Block Pattern Registration for GPress Theme
 *
 * @package GPress
 * @version 1.0.0
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Register block patterns
 */
function gpress_register_block_patterns() {
    // Register pattern categories
    register_block_pattern_category(
        'gpress-sections',
        array('label' => __('GPress Sections', 'gpress'))
    );
    
    register_block_pattern_category(
        'gpress-content',
        array('label' => __('GPress Content', 'gpress'))
    );
    
    // Call to Action pattern
    register_block_pattern(
        'gpress/call-to-action',
        array(
            'title'       => __('Call to Action', 'gpress'),
            'description' => __('A call to action section with heading, text, and button.', 'gpress'),
            'content'     => gpress_get_pattern_content('call-to-action'),
            'categories'  => array('gpress-sections'),
            'keywords'    => array('cta', 'call', 'action', 'button'),
        )
    );
    
    // Testimonials pattern
    register_block_pattern(
        'gpress/testimonials',
        array(
            'title'       => __('Testimonials', 'gpress'),
            'description' => __('A testimonials section with quotes and author information.', 'gpress'),
            'content'     => gpress_get_pattern_content('testimonials'),
            'categories'  => array('gpress-content'),
            'keywords'    => array('testimonial', 'quote', 'review'),
        )
    );
    
    // FAQ pattern
    register_block_pattern(
        'gpress/faq-section',
        array(
            'title'       => __('FAQ Section', 'gpress'),
            'description' => __('Frequently asked questions with collapsible answers.', 'gpress'),
            'content'     => gpress_get_pattern_content('faq-section'),
            'categories'  => array('gpress-content'),
            'keywords'    => array('faq', 'questions', 'help'),
        )
    );
    
    // Feature comparison pattern
    register_block_pattern(
        'gpress/feature-comparison',
        array(
            'title'       => __('Feature Comparison', 'gpress'),
            'description' => __('A comparison table for features or services.', 'gpress'),
            'content'     => gpress_get_pattern_content('feature-comparison'),
            'categories'  => array('gpress-sections'),
            'keywords'    => array('features', 'comparison', 'table'),
        )
    );
}

/**
 * Get pattern content from file
 */
function gpress_get_pattern_content($pattern_name) {
    $pattern_file = get_theme_file_path("/patterns/{$pattern_name}.html");
    
    if (file_exists($pattern_file)) {
        return file_get_contents($pattern_file);
    }
    
    return '';
}
```

## 4. Create Block Editor Enhancement Script

### File: `assets/js/block-editor.js`
```javascript
/**
 * GPress Block Editor Enhancements
 */
(function() {
    'use strict';
    
    // Wait for WordPress to be ready
    wp.domReady(function() {
        // Remove unwanted block styles
        wp.blocks.unregisterBlockStyle('core/quote', 'large');
        wp.blocks.unregisterBlockStyle('core/separator', 'wide');
        wp.blocks.unregisterBlockStyle('core/separator', 'dots');
        
        // Add custom block variations
        addCustomBlockVariations();
        
        // Enhance block toolbar
        enhanceBlockToolbar();
        
        // Add custom block inspector controls
        addCustomInspectorControls();
    });
    
    /**
     * Add custom block variations
     */
    function addCustomBlockVariations() {
        // Button variations
        wp.blocks.registerBlockVariation(
            'core/button',
            {
                name: 'gpress-cta-button',
                title: 'CTA Button',
                description: 'Call to action button with emphasis',
                attributes: {
                    className: 'is-style-gpress-gradient-button',
                    fontSize: 'medium'
                },
                scope: ['inserter']
            }
        );
        
        // Column variations
        wp.blocks.registerBlockVariation(
            'core/columns',
            {
                name: 'gpress-feature-grid',
                title: 'Feature Grid',
                description: 'Three columns for feature display',
                innerBlocks: [
                    ['core/column', {}, [
                        ['core/heading', { level: 3, content: 'Feature 1' }],
                        ['core/paragraph', { content: 'Description of the first feature.' }]
                    ]],
                    ['core/column', {}, [
                        ['core/heading', { level: 3, content: 'Feature 2' }],
                        ['core/paragraph', { content: 'Description of the second feature.' }]
                    ]],
                    ['core/column', {}, [
                        ['core/heading', { level: 3, content: 'Feature 3' }],
                        ['core/paragraph', { content: 'Description of the third feature.' }]
                    ]]
                ],
                scope: ['inserter']
            }
        );
    }
    
    /**
     * Enhance block toolbar
     */
    function enhanceBlockToolbar() {
        // Add custom toolbar controls if needed
        const { createHigherOrderComponent } = wp.compose;
        const { Fragment } = wp.element;
        const { InspectorControls } = wp.blockEditor;
        const { PanelBody, ToggleControl } = wp.components;
        
        const withCustomControls = createHigherOrderComponent((BlockEdit) => {
            return (props) => {
                const { attributes, setAttributes, name } = props;
                
                // Only add controls to specific blocks
                if (!['core/paragraph', 'core/heading'].includes(name)) {
                    return wp.element.createElement(BlockEdit, props);
                }
                
                return wp.element.createElement(
                    Fragment,
                    {},
                    wp.element.createElement(BlockEdit, props),
                    wp.element.createElement(
                        InspectorControls,
                        {},
                        wp.element.createElement(
                            PanelBody,
                            {
                                title: 'GPress Settings',
                                initialOpen: false
                            },
                            wp.element.createElement(ToggleControl, {
                                label: 'Enhanced Styling',
                                checked: attributes.gpressEnhanced || false,
                                onChange: (value) => setAttributes({ gpressEnhanced: value })
                            })
                        )
                    )
                );
            };
        }, 'withCustomControls');
        
        wp.hooks.addFilter(
            'editor.BlockEdit',
            'gpress/custom-controls',
            withCustomControls
        );
    }
    
    /**
     * Add custom inspector controls
     */
    function addCustomInspectorControls() {
        // Additional inspector controls for blocks
        const { __ } = wp.i18n;
        
        // Add performance hints
        if (window.console && window.console.log) {
            console.log('GPress: Block editor enhancements loaded');
        }
    }
    
    // Block validation enhancements
    const { subscribe } = wp.data;
    
    subscribe(() => {
        const blocks = wp.data.select('core/block-editor').getBlocks();
        
        // Validate accessibility
        blocks.forEach((block) => {
            if (block.name === 'core/image') {
                const { alt } = block.attributes;
                if (!alt) {
                    // Could add notification about missing alt text
                }
            }
        });
    });
    
})();
```

## 5. Create Block Styles CSS

### File: `assets/css/blocks.css`
```css
/* GPress Block Styles */

/* Quote Block Styles */
.wp-block-quote.is-style-gpress-modern-quote {
    border-left: 4px solid var(--wp--preset--color--primary);
    background: var(--wp--preset--color--background-secondary);
    padding: 2rem;
    border-radius: 8px;
    margin: 2rem 0;
    position: relative;
    font-style: italic;
}

.wp-block-quote.is-style-gpress-modern-quote::before {
    content: """;
    font-size: 4rem;
    color: var(--wp--preset--color--primary);
    position: absolute;
    top: -0.5rem;
    left: 1rem;
    line-height: 1;
}

.wp-block-quote.is-style-gpress-highlight-quote {
    background: linear-gradient(135deg, var(--wp--preset--color--primary), var(--wp--preset--color--accent));
    color: var(--wp--preset--color--background);
    padding: 2rem;
    border-radius: 12px;
    border: none;
    margin: 2rem 0;
    position: relative;
}

.wp-block-quote.is-style-gpress-highlight-quote cite {
    color: rgba(255, 255, 255, 0.8);
    font-weight: 600;
}

/* Button Block Styles */
.wp-block-button.is-style-gpress-gradient-button .wp-block-button__link {
    background: linear-gradient(135deg, var(--wp--preset--color--primary), var(--wp--preset--color--accent));
    border: none;
    box-shadow: 0 4px 15px rgba(0, 115, 170, 0.3);
    transition: all 0.3s ease;
    position: relative;
    overflow: hidden;
}

.wp-block-button.is-style-gpress-gradient-button .wp-block-button__link:hover {
    transform: translateY(-2px);
    box-shadow: 0 6px 20px rgba(0, 115, 170, 0.4);
}

.wp-block-button.is-style-gpress-outline-button .wp-block-button__link {
    background: transparent;
    border: 2px solid var(--wp--preset--color--primary);
    color: var(--wp--preset--color--primary);
    transition: all 0.3s ease;
}

.wp-block-button.is-style-gpress-outline-button .wp-block-button__link:hover {
    background: var(--wp--preset--color--primary);
    color: var(--wp--preset--color--background);
}

/* Columns Block Styles */
.wp-block-columns.is-style-gpress-card-columns {
    gap: 2rem;
}

.wp-block-columns.is-style-gpress-card-columns .wp-block-column {
    background: var(--wp--preset--color--background-secondary);
    padding: 2rem;
    border-radius: 12px;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    transition: transform 0.3s ease;
}

.wp-block-columns.is-style-gpress-card-columns .wp-block-column:hover {
    transform: translateY(-5px);
}

.wp-block-columns.is-style-gpress-feature-columns .wp-block-column {
    text-align: center;
    padding: 1.5rem;
}

.wp-block-columns.is-style-gpress-feature-columns .wp-block-column h3 {
    color: var(--wp--preset--color--primary);
    margin-bottom: 1rem;
}

/* Cover Block Styles */
.wp-block-cover.is-style-gpress-overlay-cover {
    position: relative;
}

.wp-block-cover.is-style-gpress-overlay-cover::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: linear-gradient(45deg, rgba(0, 115, 170, 0.8), rgba(0, 160, 210, 0.6));
    z-index: 1;
}

.wp-block-cover.is-style-gpress-overlay-cover .wp-block-cover__inner-container {
    position: relative;
    z-index: 2;
}

/* Table Block Styles */
.wp-block-table.is-style-gpress-striped-table table {
    border-collapse: collapse;
    width: 100%;
}

.wp-block-table.is-style-gpress-striped-table tbody tr:nth-child(even) {
    background: var(--wp--preset--color--background-secondary);
}

.wp-block-table.is-style-gpress-striped-table th,
.wp-block-table.is-style-gpress-striped-table td {
    padding: 1rem;
    border: 1px solid var(--wp--preset--color--border);
}

.wp-block-table.is-style-gpress-minimal-table table {
    border: none;
}

.wp-block-table.is-style-gpress-minimal-table th {
    border-bottom: 2px solid var(--wp--preset--color--primary);
    border-top: none;
    border-left: none;
    border-right: none;
    font-weight: 600;
}

.wp-block-table.is-style-gpress-minimal-table td {
    border: none;
    border-bottom: 1px solid var(--wp--preset--color--border);
}

/* List Block Styles */
.wp-block-list.is-style-gpress-checkmark-list {
    list-style: none;
    padding-left: 0;
}

.wp-block-list.is-style-gpress-checkmark-list li {
    position: relative;
    padding-left: 2rem;
    margin-bottom: 0.5rem;
}

.wp-block-list.is-style-gpress-checkmark-list li::before {
    content: "✓";
    position: absolute;
    left: 0;
    color: var(--wp--preset--color--success);
    font-weight: bold;
    font-size: 1.2em;
}

.wp-block-list.is-style-gpress-arrow-list {
    list-style: none;
    padding-left: 0;
}

.wp-block-list.is-style-gpress-arrow-list li {
    position: relative;
    padding-left: 2rem;
    margin-bottom: 0.5rem;
}

.wp-block-list.is-style-gpress-arrow-list li::before {
    content: "→";
    position: absolute;
    left: 0;
    color: var(--wp--preset--color--primary);
    font-weight: bold;
}

/* Gallery Block Styles */
.wp-block-gallery.is-style-gpress-masonry-gallery {
    columns: 3;
    column-gap: 1rem;
}

.wp-block-gallery.is-style-gpress-masonry-gallery .wp-block-image {
    break-inside: avoid;
    margin-bottom: 1rem;
    border-radius: 8px;
    overflow: hidden;
}

/* Responsive Design */
@media (max-width: 768px) {
    .wp-block-columns.is-style-gpress-card-columns {
        gap: 1rem;
    }
    
    .wp-block-columns.is-style-gpress-card-columns .wp-block-column {
        padding: 1.5rem;
    }
    
    .wp-block-gallery.is-style-gpress-masonry-gallery {
        columns: 2;
    }
}

@media (max-width: 480px) {
    .wp-block-gallery.is-style-gpress-masonry-gallery {
        columns: 1;
    }
    
    .wp-block-quote.is-style-gpress-modern-quote,
    .wp-block-quote.is-style-gpress-highlight-quote {
        padding: 1.5rem;
        margin: 1rem 0;
    }
}

/* High Contrast Mode Support */
@media (prefers-contrast: high) {
    .wp-block-quote.is-style-gpress-modern-quote {
        border-left-width: 6px;
    }
    
    .wp-block-button.is-style-gpress-outline-button .wp-block-button__link {
        border-width: 3px;
    }
}

/* Reduced Motion Support */
@media (prefers-reduced-motion: reduce) {
    .wp-block-button.is-style-gpress-gradient-button .wp-block-button__link,
    .wp-block-button.is-style-gpress-outline-button .wp-block-button__link,
    .wp-block-columns.is-style-gpress-card-columns .wp-block-column {
        transition: none;
    }
    
    .wp-block-button.is-style-gpress-gradient-button .wp-block-button__link:hover,
    .wp-block-columns.is-style-gpress-card-columns .wp-block-column:hover {
        transform: none;
    }
}

/* Print Styles */
@media print {
    .wp-block-quote.is-style-gpress-highlight-quote {
        background: none;
        color: black;
        border: 2px solid black;
    }
    
    .wp-block-button.is-style-gpress-gradient-button .wp-block-button__link {
        background: white;
        color: black;
        border: 1px solid black;
    }
}
```

## 6. Create Block Patterns HTML

### File: `patterns/call-to-action.html`
```html
<!-- wp:group {"align":"full","backgroundColor":"primary","textColor":"background","className":"gpress-cta-section","layout":{"type":"constrained"}} -->
<div class="wp-block-group alignfull gpress-cta-section has-background-color has-primary-background-color has-text-color">
    <!-- wp:group {"layout":{"type":"constrained","contentSize":"800px"}} -->
    <div class="wp-block-group">
        <!-- wp:heading {"textAlign":"center","level":2,"fontSize":"large"} -->
        <h2 class="wp-block-heading has-text-align-center has-large-font-size">Ready to Get Started?</h2>
        <!-- /wp:heading -->
        
        <!-- wp:paragraph {"align":"center","fontSize":"medium"} -->
        <p class="has-text-align-center has-medium-font-size">Join thousands of satisfied customers who have transformed their business with our solution.</p>
        <!-- /wp:paragraph -->
        
        <!-- wp:buttons {"layout":{"type":"flex","justifyContent":"center"},"style":{"spacing":{"margin":{"top":"2rem"}}}} -->
        <div class="wp-block-buttons" style="margin-top:2rem">
            <!-- wp:button {"backgroundColor":"accent","className":"is-style-gpress-gradient-button"} -->
            <div class="wp-block-button is-style-gpress-gradient-button">
                <a class="wp-block-button__link has-accent-background-color has-background" href="#">Get Started Today</a>
            </div>
            <!-- /wp:button -->
            
            <!-- wp:button {"textColor":"background","className":"is-style-gpress-outline-button"} -->
            <div class="wp-block-button is-style-gpress-outline-button">
                <a class="wp-block-button__link has-background-color has-text-color" href="#">Learn More</a>
            </div>
            <!-- /wp:button -->
        </div>
        <!-- /wp:buttons -->
    </div>
    <!-- /wp:group -->
</div>
<!-- /wp:group -->
```

### File: `patterns/testimonials.html`
```html
<!-- wp:group {"className":"gpress-testimonials-section","layout":{"type":"constrained"}} -->
<div class="wp-block-group gpress-testimonials-section">
    <!-- wp:heading {"textAlign":"center","level":2,"style":{"spacing":{"margin":{"bottom":"3rem"}}}} -->
    <h2 class="wp-block-heading has-text-align-center" style="margin-bottom:3rem">What Our Customers Say</h2>
    <!-- /wp:heading -->
    
    <!-- wp:columns {"className":"is-style-gpress-card-columns"} -->
    <div class="wp-block-columns is-style-gpress-card-columns">
        <!-- wp:column -->
        <div class="wp-block-column">
            <!-- wp:quote {"className":"is-style-gpress-modern-quote"} -->
            <blockquote class="wp-block-quote is-style-gpress-modern-quote">
                <p>"This solution has completely transformed how we handle our business operations. Highly recommended!"</p>
                <cite>Sarah Johnson, CEO</cite>
            </blockquote>
            <!-- /wp:quote -->
        </div>
        <!-- /wp:column -->
        
        <!-- wp:column -->
        <div class="wp-block-column">
            <!-- wp:quote {"className":"is-style-gpress-modern-quote"} -->
            <blockquote class="wp-block-quote is-style-gpress-modern-quote">
                <p>"Outstanding customer service and a product that actually delivers on its promises."</p>
                <cite>Mike Chen, Director</cite>
            </blockquote>
            <!-- /wp:quote -->
        </div>
        <!-- /wp:column -->
        
        <!-- wp:column -->
        <div class="wp-block-column">
            <!-- wp:quote {"className":"is-style-gpress-modern-quote"} -->
            <blockquote class="wp-block-quote is-style-gpress-modern-quote">
                <p>"Easy to use, powerful features, and excellent support. Everything we needed in one package."</p>
                <cite>Emma Davis, Manager</cite>
            </blockquote>
            <!-- /wp:quote -->
        </div>
        <!-- /wp:column -->
    </div>
    <!-- /wp:columns -->
</div>
<!-- /wp:group -->
```

### File: `patterns/faq-section.html`
```html
<!-- wp:group {"className":"gpress-faq-section","layout":{"type":"constrained","contentSize":"800px"}} -->
<div class="wp-block-group gpress-faq-section">
    <!-- wp:heading {"textAlign":"center","level":2,"style":{"spacing":{"margin":{"bottom":"3rem"}}}} -->
    <h2 class="wp-block-heading has-text-align-center" style="margin-bottom:3rem">Frequently Asked Questions</h2>
    <!-- /wp:heading -->
    
    <!-- wp:details -->
    <details class="wp-block-details">
        <summary>How does the pricing work?</summary>
        <!-- wp:paragraph -->
        <p>Our pricing is transparent and flexible. We offer monthly and annual plans with no hidden fees. You can upgrade or downgrade at any time.</p>
        <!-- /wp:paragraph -->
    </details>
    <!-- /wp:details -->
    
    <!-- wp:details -->
    <details class="wp-block-details">
        <summary>Is there a free trial available?</summary>
        <!-- wp:paragraph -->
        <p>Yes! We offer a 14-day free trial with full access to all features. No credit card required to get started.</p>
        <!-- /wp:paragraph -->
    </details>
    <!-- /wp:details -->
    
    <!-- wp:details -->
    <details class="wp-block-details">
        <summary>What kind of support do you provide?</summary>
        <!-- wp:paragraph -->
        <p>We provide 24/7 customer support via chat, email, and phone. Our team is always ready to help you succeed.</p>
        <!-- /wp:paragraph -->
    </details>
    <!-- /wp:details -->
    
    <!-- wp:details -->
    <details class="wp-block-details">
        <summary>Can I cancel my subscription anytime?</summary>
        <!-- wp:paragraph -->
        <p>Absolutely. You can cancel your subscription at any time with no cancellation fees. Your access continues until the end of your billing period.</p>
        <!-- /wp:paragraph -->
    </details>
    <!-- /wp:details -->
</div>
<!-- /wp:group -->
```

### File: `patterns/feature-comparison.html`
```html
<!-- wp:group {"className":"gpress-feature-comparison","layout":{"type":"constrained"}} -->
<div class="wp-block-group gpress-feature-comparison">
    <!-- wp:heading {"textAlign":"center","level":2,"style":{"spacing":{"margin":{"bottom":"3rem"}}}} -->
    <h2 class="wp-block-heading has-text-align-center" style="margin-bottom:3rem">Choose Your Plan</h2>
    <!-- /wp:heading -->
    
    <!-- wp:table {"className":"is-style-gpress-minimal-table"} -->
    <figure class="wp-block-table is-style-gpress-minimal-table">
        <table>
            <thead>
                <tr>
                    <th>Feature</th>
                    <th>Basic</th>
                    <th>Pro</th>
                    <th>Enterprise</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>Users</td>
                    <td>Up to 5</td>
                    <td>Up to 25</td>
                    <td>Unlimited</td>
                </tr>
                <tr>
                    <td>Storage</td>
                    <td>10 GB</td>
                    <td>100 GB</td>
                    <td>1 TB</td>
                </tr>
                <tr>
                    <td>Support</td>
                    <td>Email</td>
                    <td>Email + Chat</td>
                    <td>24/7 Phone</td>
                </tr>
                <tr>
                    <td>Analytics</td>
                    <td>Basic</td>
                    <td>Advanced</td>
                    <td>Custom</td>
                </tr>
                <tr>
                    <td>API Access</td>
                    <td>❌</td>
                    <td>✅</td>
                    <td>✅</td>
                </tr>
            </tbody>
        </table>
    </figure>
    <!-- /wp:table -->
    
    <!-- wp:buttons {"layout":{"type":"flex","justifyContent":"center"},"style":{"spacing":{"margin":{"top":"2rem"}}}} -->
    <div class="wp-block-buttons" style="margin-top:2rem">
        <!-- wp:button {"className":"is-style-gpress-outline-button"} -->
        <div class="wp-block-button is-style-gpress-outline-button">
            <a class="wp-block-button__link" href="#">Choose Basic</a>
        </div>
        <!-- /wp:button -->
        
        <!-- wp:button {"className":"is-style-gpress-gradient-button"} -->
        <div class="wp-block-button is-style-gpress-gradient-button">
            <a class="wp-block-button__link" href="#">Choose Pro</a>
        </div>
        <!-- /wp:button -->
        
        <!-- wp:button {"className":"is-style-gpress-outline-button"} -->
        <div class="wp-block-button is-style-gpress-outline-button">
            <a class="wp-block-button__link" href="#">Choose Enterprise</a>
        </div>
        <!-- /wp:button -->
    </div>
    <!-- /wp:buttons -->
</div>
<!-- /wp:group -->
```

## 7. Create Block Editor Styles

### File: `assets/css/editor-blocks.css`
```css
/* GPress Block Editor Styles */

/* Editor-specific styling for blocks */
.editor-styles-wrapper .wp-block-quote.is-style-gpress-modern-quote {
    border-left: 4px solid #0073aa;
    background: #f8f9fa;
    padding: 2rem;
    border-radius: 8px;
    margin: 2rem 0;
    position: relative;
    font-style: italic;
}

.editor-styles-wrapper .wp-block-quote.is-style-gpress-modern-quote::before {
    content: """;
    font-size: 4rem;
    color: #0073aa;
    position: absolute;
    top: -0.5rem;
    left: 1rem;
    line-height: 1;
}

.editor-styles-wrapper .wp-block-button.is-style-gpress-gradient-button .wp-block-button__link {
    background: linear-gradient(135deg, #0073aa, #00a0d2);
    border: none;
    box-shadow: 0 4px 15px rgba(0, 115, 170, 0.3);
}

.editor-styles-wrapper .wp-block-button.is-style-gpress-outline-button .wp-block-button__link {
    background: transparent;
    border: 2px solid #0073aa;
    color: #0073aa;
}

.editor-styles-wrapper .wp-block-columns.is-style-gpress-card-columns .wp-block-column {
    background: #f8f9fa;
    padding: 2rem;
    border-radius: 12px;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    margin-bottom: 1rem;
}

.editor-styles-wrapper .wp-block-table.is-style-gpress-striped-table tbody tr:nth-child(even) {
    background: #f8f9fa;
}

.editor-styles-wrapper .wp-block-list.is-style-gpress-checkmark-list {
    list-style: none;
    padding-left: 0;
}

.editor-styles-wrapper .wp-block-list.is-style-gpress-checkmark-list li {
    position: relative;
    padding-left: 2rem;
}

.editor-styles-wrapper .wp-block-list.is-style-gpress-checkmark-list li::before {
    content: "✓";
    position: absolute;
    left: 0;
    color: #46b450;
    font-weight: bold;
}

/* Editor enhancements */
.editor-styles-wrapper {
    font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
}

.editor-styles-wrapper .wp-block {
    max-width: 100%;
}

/* Editor block previews */
.block-editor-block-preview__container .wp-block-quote.is-style-gpress-modern-quote {
    transform: scale(0.8);
    transform-origin: top left;
}
```

## 8. Update Functions.php

Add to `functions.php`:

```php
// Block editor and pattern support
require_once get_theme_file_path('/inc/block-assets.php');
require_once get_theme_file_path('/inc/block-styles.php');
require_once get_theme_file_path('/inc/block-patterns.php');
```

## Testing

1. **Block Style Testing**:
   - Install and activate the theme
   - Create a new page/post in the block editor
   - Test each custom block style (quotes, buttons, columns, etc.)
   - Verify styles appear correctly in both editor and frontend
   - Check conditional loading works (styles only load when blocks are used)

2. **Block Pattern Testing**:
   - Open the block inserter and look for "GPress Sections" and "GPress Content" categories
   - Insert each pattern and verify proper rendering
   - Test pattern content is editable and maintains structure
   - Verify patterns work with different themes colors

3. **Editor Enhancement Testing**:
   - Test custom block variations in the inserter
   - Verify unwanted core block styles are removed
   - Check editor scripts load without errors
   - Test custom inspector controls appear for supported blocks

4. **Performance Testing**:
   - Create pages with and without custom blocks
   - Verify block assets only load when blocks are present
   - Test page loading speeds with Lighthouse
   - Check no JavaScript errors in console

5. **Accessibility Testing**:
   - Test block patterns with screen readers
   - Verify all interactive elements are keyboard accessible
   - Check color contrast meets WCAG standards
   - Test patterns work with high contrast mode

## Next Steps

In Step 11, we'll implement advanced form handling and contact features with conditional asset loading.

## Key Benefits

- Custom block styles that enhance visual appeal
- Intelligent conditional loading of block assets
- Professional block patterns for quick page building
- Enhanced block editor experience
- Performance-optimized block implementation
- Accessibility-compliant block styling
- Editor and frontend style consistency
- Comprehensive block customization system