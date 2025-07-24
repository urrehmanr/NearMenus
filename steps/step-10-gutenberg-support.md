# Step 10: Gutenberg Block Support

## Objective
Implement comprehensive Gutenberg block support with custom block styles, advanced configurations, and seamless integration with the theme's design system to provide users with powerful content creation tools.

## What You'll Learn
- Advanced block support configuration
- Custom block styles and variations
- Block pattern creation and management
- Block editor customization
- Performance optimization for blocks
- Custom block development basics

## Advanced Block Support Configuration

### 1. Enhanced Theme Support

Update `inc/theme-setup.php`:
```php
<?php
/**
 * Enhanced Gutenberg Block Support
 *
 * @package ModernBlog2025
 * @since 1.0.0
 */

/**
 * Add comprehensive block support
 */
function modernblog2025_enhanced_block_support() {
    
    // Core block supports
    add_theme_support('align-wide');
    add_theme_support('wp-block-styles');
    add_theme_support('responsive-embeds');
    add_theme_support('editor-styles');
    
    // Advanced block features
    add_theme_support('custom-line-height');
    add_theme_support('custom-spacing');
    add_theme_support('custom-units', array('px', 'em', 'rem', 'vh', 'vw', '%'));
    add_theme_support('appearance-tools');
    add_theme_support('border');
    add_theme_support('link-color');
    
    // Block editor gradient support
    add_theme_support('editor-gradient-presets', array(
        array(
            'name'     => esc_html__('Blue to Purple', 'modernblog2025'),
            'gradient' => 'linear-gradient(135deg, #667eea 0%, #764ba2 100%)',
            'slug'     => 'blue-purple'
        ),
        array(
            'name'     => esc_html__('Light to Dark', 'modernblog2025'),
            'gradient' => 'linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%)',
            'slug'     => 'light-dark'
        ),
        array(
            'name'     => esc_html__('Sunset', 'modernblog2025'),
            'gradient' => 'linear-gradient(135deg, #ff9a9e 0%, #fecfef 50%, #fecfef 100%)',
            'slug'     => 'sunset'
        )
    ));
    
    // Block editor duotone support
    add_theme_support('editor-duotone-presets', array(
        array(
            'colors' => array('#000000', '#ffffff'),
            'slug'   => 'black-white',
            'name'   => esc_html__('Black and White', 'modernblog2025'),
        ),
        array(
            'colors' => array('#2c3e50', '#3498db'),
            'slug'   => 'primary-secondary',
            'name'   => esc_html__('Primary and Secondary', 'modernblog2025'),
        ),
    ));
}
add_action('after_setup_theme', 'modernblog2025_enhanced_block_support');

/**
 * Register custom block styles
 */
function modernblog2025_register_block_styles() {
    
    // Button block styles
    register_block_style('core/button', array(
        'name'  => 'outline-primary',
        'label' => esc_html__('Outline Primary', 'modernblog2025'),
        'style_handle' => 'modernblog2025-blocks'
    ));
    
    register_block_style('core/button', array(
        'name'  => 'gradient-fill',
        'label' => esc_html__('Gradient Fill', 'modernblog2025'),
        'style_handle' => 'modernblog2025-blocks'
    ));
    
    register_block_style('core/button', array(
        'name'  => 'shadow-hover',
        'label' => esc_html__('Shadow on Hover', 'modernblog2025'),
        'style_handle' => 'modernblog2025-blocks'
    ));
    
    // Group block styles
    register_block_style('core/group', array(
        'name'  => 'card-shadow',
        'label' => esc_html__('Card with Shadow', 'modernblog2025'),
        'style_handle' => 'modernblog2025-blocks'
    ));
    
    register_block_style('core/group', array(
        'name'  => 'glass-effect',
        'label' => esc_html__('Glass Effect', 'modernblog2025'),
        'style_handle' => 'modernblog2025-blocks'
    ));
    
    // Cover block styles
    register_block_style('core/cover', array(
        'name'  => 'parallax-effect',
        'label' => esc_html__('Parallax Effect', 'modernblog2025'),
        'style_handle' => 'modernblog2025-blocks'
    ));
    
    // Image block styles
    register_block_style('core/image', array(
        'name'  => 'rounded-corners',
        'label' => esc_html__('Rounded Corners', 'modernblog2025'),
        'style_handle' => 'modernblog2025-blocks'
    ));
    
    register_block_style('core/image', array(
        'name'  => 'polaroid',
        'label' => esc_html__('Polaroid Style', 'modernblog2025'),
        'style_handle' => 'modernblog2025-blocks'
    ));
    
    // Quote block styles
    register_block_style('core/quote', array(
        'name'  => 'minimal-quote',
        'label' => esc_html__('Minimal Quote', 'modernblog2025'),
        'style_handle' => 'modernblog2025-blocks'
    ));
    
    register_block_style('core/quote', array(
        'name'  => 'highlighted-quote',
        'label' => esc_html__('Highlighted Quote', 'modernblog2025'),
        'style_handle' => 'modernblog2025-blocks'
    ));
    
    // List block styles
    register_block_style('core/list', array(
        'name'  => 'checkmark-list',
        'label' => esc_html__('Checkmark List', 'modernblog2025'),
        'style_handle' => 'modernblog2025-blocks'
    ));
    
    register_block_style('core/list', array(
        'name'  => 'arrow-list',
        'label' => esc_html__('Arrow List', 'modernblog2025'),
        'style_handle' => 'modernblog2025-blocks'
    ));
    
    // Heading block styles
    register_block_style('core/heading', array(
        'name'  => 'underlined',
        'label' => esc_html__('Underlined', 'modernblog2025'),
        'style_handle' => 'modernblog2025-blocks'
    ));
    
    register_block_style('core/heading', array(
        'name'  => 'gradient-text',
        'label' => esc_html__('Gradient Text', 'modernblog2025'),
        'style_handle' => 'modernblog2025-blocks'
    ));
}
add_action('init', 'modernblog2025_register_block_styles');

/**
 * Enqueue block styles
 */
function modernblog2025_enqueue_block_styles() {
    wp_enqueue_style(
        'modernblog2025-blocks',
        MODERNBLOG2025_THEME_URI . '/assets/css/blocks.min.css',
        array(),
        MODERNBLOG2025_VERSION
    );
}
add_action('wp_enqueue_scripts', 'modernblog2025_enqueue_block_styles');
add_action('enqueue_block_editor_assets', 'modernblog2025_enqueue_block_styles');
```

### 2. Custom Block Styles CSS

Create `assets/css/blocks.css`:
```css
/*!
 * ModernBlog2025 Custom Block Styles
 * Advanced styling for Gutenberg blocks
 */

/* ==========================================================================
   Button Block Styles
   ========================================================================== */

/* Outline Primary Button */
.wp-block-button.is-style-outline-primary .wp-block-button__link {
  border: 2px solid var(--wp--preset--color--primary);
  background: transparent;
  color: var(--wp--preset--color--primary);
  transition: all var(--transition-base);
}

.wp-block-button.is-style-outline-primary .wp-block-button__link:hover {
  background: var(--wp--preset--color--primary);
  color: var(--wp--preset--color--white);
  transform: translateY(-2px);
  box-shadow: var(--box-shadow-medium);
}

/* Gradient Fill Button */
.wp-block-button.is-style-gradient-fill .wp-block-button__link {
  background: linear-gradient(135deg, var(--wp--preset--color--primary) 0%, var(--wp--preset--color--secondary) 100%);
  border: none;
  color: var(--wp--preset--color--white);
  position: relative;
  overflow: hidden;
  transition: transform var(--transition-base);
}

.wp-block-button.is-style-gradient-fill .wp-block-button__link::before {
  content: '';
  position: absolute;
  top: 0;
  left: -100%;
  width: 100%;
  height: 100%;
  background: linear-gradient(135deg, var(--wp--preset--color--secondary) 0%, var(--wp--preset--color--primary) 100%);
  transition: left var(--transition-base);
  z-index: -1;
}

.wp-block-button.is-style-gradient-fill .wp-block-button__link:hover::before {
  left: 0;
}

.wp-block-button.is-style-gradient-fill .wp-block-button__link:hover {
  transform: translateY(-2px);
}

/* Shadow Hover Button */
.wp-block-button.is-style-shadow-hover .wp-block-button__link {
  transition: all var(--transition-base);
  box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
}

.wp-block-button.is-style-shadow-hover .wp-block-button__link:hover {
  transform: translateY(-4px);
  box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15);
}

/* ==========================================================================
   Group Block Styles
   ========================================================================== */

/* Card with Shadow */
.wp-block-group.is-style-card-shadow {
  background: var(--wp--preset--color--white);
  border-radius: var(--border-radius-medium);
  box-shadow: var(--box-shadow-small);
  padding: var(--wp--preset--spacing--large);
  border: 1px solid var(--wp--preset--color--gray-200);
  transition: all var(--transition-base);
}

.wp-block-group.is-style-card-shadow:hover {
  box-shadow: var(--box-shadow-large);
  transform: translateY(-4px);
}

/* Glass Effect */
.wp-block-group.is-style-glass-effect {
  background: rgba(255, 255, 255, 0.1);
  backdrop-filter: blur(10px);
  border: 1px solid rgba(255, 255, 255, 0.2);
  border-radius: var(--border-radius-large);
  padding: var(--wp--preset--spacing--large);
}

/* ==========================================================================
   Cover Block Styles
   ========================================================================== */

/* Parallax Effect */
.wp-block-cover.is-style-parallax-effect {
  background-attachment: fixed;
  background-size: cover;
  background-position: center;
}

@media (prefers-reduced-motion: reduce) {
  .wp-block-cover.is-style-parallax-effect {
    background-attachment: scroll;
  }
}

/* ==========================================================================
   Image Block Styles
   ========================================================================== */

/* Rounded Corners */
.wp-block-image.is-style-rounded-corners img {
  border-radius: var(--border-radius-large);
  transition: transform var(--transition-base);
}

.wp-block-image.is-style-rounded-corners:hover img {
  transform: scale(1.02);
}

/* Polaroid Style */
.wp-block-image.is-style-polaroid {
  padding: 1rem;
  background: var(--wp--preset--color--white);
  box-shadow: var(--box-shadow-medium);
  border-radius: 4px;
  transform: rotate(-2deg);
  transition: transform var(--transition-base);
}

.wp-block-image.is-style-polaroid:hover {
  transform: rotate(0deg) scale(1.05);
}

.wp-block-image.is-style-polaroid img {
  border-radius: 2px;
}

.wp-block-image.is-style-polaroid figcaption {
  margin-top: 0.5rem;
  text-align: center;
  font-style: italic;
  color: var(--wp--preset--color--gray-600);
}

/* ==========================================================================
   Quote Block Styles
   ========================================================================== */

/* Minimal Quote */
.wp-block-quote.is-style-minimal-quote {
  border: none;
  padding: 0;
  margin: 2rem 0;
  font-style: normal;
}

.wp-block-quote.is-style-minimal-quote p {
  font-size: var(--wp--preset--font-size--large);
  font-weight: 300;
  line-height: 1.6;
  color: var(--wp--preset--color--gray-700);
  position: relative;
}

.wp-block-quote.is-style-minimal-quote p::before {
  content: '"';
  font-size: 4rem;
  color: var(--wp--preset--color--primary);
  position: absolute;
  left: -2rem;
  top: -1rem;
  font-family: var(--wp--preset--font-family--serif);
}

.wp-block-quote.is-style-minimal-quote cite {
  font-style: normal;
  font-weight: 500;
  color: var(--wp--preset--color--gray-600);
}

/* Highlighted Quote */
.wp-block-quote.is-style-highlighted-quote {
  background: linear-gradient(135deg, var(--wp--preset--color--primary) 0%, var(--wp--preset--color--secondary) 100%);
  color: var(--wp--preset--color--white);
  padding: 2rem;
  border-radius: var(--border-radius-medium);
  border: none;
  margin: 2rem 0;
}

.wp-block-quote.is-style-highlighted-quote p {
  color: var(--wp--preset--color--white);
  font-size: var(--wp--preset--font-size--large);
  margin-bottom: 1rem;
}

.wp-block-quote.is-style-highlighted-quote cite {
  color: rgba(255, 255, 255, 0.8);
  font-style: normal;
}

/* ==========================================================================
   List Block Styles
   ========================================================================== */

/* Checkmark List */
.wp-block-list.is-style-checkmark-list {
  list-style: none;
  padding-left: 0;
}

.wp-block-list.is-style-checkmark-list li {
  position: relative;
  padding-left: 2rem;
  margin-bottom: 0.75rem;
}

.wp-block-list.is-style-checkmark-list li::before {
  content: '✓';
  position: absolute;
  left: 0;
  top: 0;
  color: var(--wp--preset--color--success);
  font-weight: bold;
  font-size: 1.2em;
}

/* Arrow List */
.wp-block-list.is-style-arrow-list {
  list-style: none;
  padding-left: 0;
}

.wp-block-list.is-style-arrow-list li {
  position: relative;
  padding-left: 2rem;
  margin-bottom: 0.75rem;
}

.wp-block-list.is-style-arrow-list li::before {
  content: '→';
  position: absolute;
  left: 0;
  top: 0;
  color: var(--wp--preset--color--primary);
  font-weight: bold;
  font-size: 1.2em;
}

/* ==========================================================================
   Heading Block Styles
   ========================================================================== */

/* Underlined Heading */
.wp-block-heading.is-style-underlined {
  position: relative;
  padding-bottom: 0.5rem;
}

.wp-block-heading.is-style-underlined::after {
  content: '';
  position: absolute;
  bottom: 0;
  left: 0;
  width: 3rem;
  height: 3px;
  background: var(--wp--preset--color--primary);
  border-radius: 2px;
}

/* Gradient Text Heading */
.wp-block-heading.is-style-gradient-text {
  background: linear-gradient(135deg, var(--wp--preset--color--primary) 0%, var(--wp--preset--color--secondary) 100%);
  background-clip: text;
  -webkit-background-clip: text;
  -webkit-text-fill-color: transparent;
  color: transparent;
}

/* Fallback for browsers that don't support background-clip: text */
@supports not (background-clip: text) {
  .wp-block-heading.is-style-gradient-text {
    color: var(--wp--preset--color--primary);
  }
}

/* ==========================================================================
   Column Block Enhancements
   ========================================================================== */

.wp-block-columns.is-layout-flex {
  gap: var(--wp--preset--spacing--large);
}

.wp-block-column {
  flex: 1;
  min-width: 0;
}

/* Equal height columns */
.wp-block-columns.equal-height {
  align-items: stretch;
}

.wp-block-columns.equal-height .wp-block-column {
  display: flex;
  flex-direction: column;
}

/* ==========================================================================
   Media & Text Block Enhancements
   ========================================================================== */

.wp-block-media-text {
  border-radius: var(--border-radius-medium);
  overflow: hidden;
  box-shadow: var(--box-shadow-small);
}

.wp-block-media-text.has-background {
  padding: 0;
}

.wp-block-media-text__content {
  padding: var(--wp--preset--spacing--large);
}

/* ==========================================================================
   Gallery Block Enhancements
   ========================================================================== */

.wp-block-gallery.is-cropped .wp-block-image img {
  border-radius: var(--border-radius-small);
  transition: transform var(--transition-base);
}

.wp-block-gallery.is-cropped .wp-block-image:hover img {
  transform: scale(1.05);
}

/* ==========================================================================
   Separator Block Styles
   ========================================================================== */

.wp-block-separator.is-style-dots {
  background: none;
  border: none;
  text-align: center;
  height: auto;
  width: auto;
}

.wp-block-separator.is-style-dots::before {
  content: '• • •';
  color: var(--wp--preset--color--gray-400);
  font-size: 1.5rem;
  letter-spacing: 1rem;
}

/* ==========================================================================
   Table Block Enhancements
   ========================================================================== */

.wp-block-table.is-style-stripes tbody tr:nth-child(odd) {
  background-color: var(--wp--preset--color--gray-50);
}

.wp-block-table table {
  border-radius: var(--border-radius-small);
  overflow: hidden;
  box-shadow: var(--box-shadow-small);
}

/* ==========================================================================
   Responsive Block Styles
   ========================================================================== */

@media (max-width: 768px) {
  .wp-block-group.is-style-card-shadow,
  .wp-block-group.is-style-glass-effect {
    padding: var(--wp--preset--spacing--medium);
  }
  
  .wp-block-quote.is-style-minimal-quote p::before {
    display: none;
  }
  
  .wp-block-quote.is-style-highlighted-quote {
    padding: 1.5rem;
  }
}

/* ==========================================================================
   Print Styles for Blocks
   ========================================================================== */

@media print {
  .wp-block-button,
  .wp-block-embed,
  .wp-block-social-links {
    display: none !important;
  }
  
  .wp-block-group.is-style-card-shadow,
  .wp-block-group.is-style-glass-effect {
    border: 1px solid #ccc;
    background: white;
    box-shadow: none;
  }
  
  .wp-block-quote.is-style-highlighted-quote {
    background: none;
    color: black;
    border-left: 4px solid #000;
  }
}
```

### 3. Block Pattern Library

Create `inc/block-pattern-library.php`:
```php
<?php
/**
 * Advanced Block Pattern Library
 *
 * @package ModernBlog2025
 * @since 1.0.0
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Register advanced block patterns
 */
function modernblog2025_register_advanced_patterns() {
    
    if (!function_exists('register_block_pattern')) {
        return;
    }

    // Register pattern categories
    register_block_pattern_category(
        'modernblog2025-content',
        array('label' => esc_html__('ModernBlog2025 Content', 'modernblog2025'))
    );
    
    register_block_pattern_category(
        'modernblog2025-call-to-action',
        array('label' => esc_html__('ModernBlog2025 Call to Action', 'modernblog2025'))
    );
    
    register_block_pattern_category(
        'modernblog2025-testimonials',
        array('label' => esc_html__('ModernBlog2025 Testimonials', 'modernblog2025'))
    );

    // FAQ Section Pattern
    register_block_pattern(
        'modernblog2025/faq-section',
        array(
            'title'       => esc_html__('FAQ Section', 'modernblog2025'),
            'description' => esc_html__('Frequently asked questions with expandable answers', 'modernblog2025'),
            'categories'  => array('modernblog2025-content'),
            'content'     => '<!-- wp:group {"style":{"spacing":{"padding":{"all":"3rem"}}},"backgroundColor":"gray-100","layout":{"type":"constrained","contentSize":"800px"}} -->
<div class="wp-block-group has-gray-100-background-color has-background" style="padding:3rem"><!-- wp:heading {"textAlign":"center","level":2,"fontSize":"x-large","style":{"spacing":{"margin":{"bottom":"3rem"}}}} -->
<h2 class="wp-block-heading has-text-align-center has-x-large-font-size" style="margin-bottom:3rem">Frequently Asked Questions</h2>
<!-- /wp:heading -->

<!-- wp:details {"style":{"spacing":{"margin":{"bottom":"1rem"}}}} -->
<details class="wp-block-details" style="margin-bottom:1rem"><summary>How do I customize the theme?</summary><!-- wp:paragraph -->
<p>You can customize the theme through the WordPress Customizer or the Site Editor for full-site editing capabilities.</p>
<!-- /wp:paragraph --></details>
<!-- /wp:details -->

<!-- wp:details {"style":{"spacing":{"margin":{"bottom":"1rem"}}}} -->
<details class="wp-block-details" style="margin-bottom:1rem"><summary>Is the theme mobile-responsive?</summary><!-- wp:paragraph -->
<p>Yes, the theme is fully responsive and optimized for all device sizes using a mobile-first approach.</p>
<!-- /wp:paragraph --></details>
<!-- /wp:details -->

<!-- wp:details {"style":{"spacing":{"margin":{"bottom":"1rem"}}}} -->
<details class="wp-block-details" style="margin-bottom:1rem"><summary>Does it support custom blocks?</summary><!-- wp:paragraph -->
<p>The theme includes comprehensive Gutenberg support with custom block styles and patterns for enhanced content creation.</p>
<!-- /wp:paragraph --></details>
<!-- /wp:details --></div>
<!-- /wp:group -->',
        )
    );

    // Testimonial Card Pattern
    register_block_pattern(
        'modernblog2025/testimonial-card',
        array(
            'title'       => esc_html__('Testimonial Card', 'modernblog2025'),
            'description' => esc_html__('Single testimonial with photo and details', 'modernblog2025'),
            'categories'  => array('modernblog2025-testimonials'),
            'content'     => '<!-- wp:group {"style":{"spacing":{"padding":{"all":"2rem"}},"border":{"radius":"12px"}},"backgroundColor":"white","className":"is-style-card-shadow","layout":{"type":"constrained"}} -->
<div class="wp-block-group is-style-card-shadow has-white-background-color has-background" style="border-radius:12px;padding:2rem"><!-- wp:quote {"className":"is-style-minimal-quote"} -->
<blockquote class="wp-block-quote is-style-minimal-quote"><!-- wp:paragraph -->
<p>"This theme has completely transformed our website. The performance improvements and clean design have significantly increased our engagement rates."</p>
<!-- /wp:paragraph --><cite>Sarah Johnson<br>Marketing Director</cite></blockquote>
<!-- /wp:quote -->

<!-- wp:group {"style":{"spacing":{"margin":{"top":"2rem"}}},"layout":{"type":"flex","flexWrap":"nowrap","verticalAlignment":"center"}} -->
<div class="wp-block-group" style="margin-top:2rem"><!-- wp:image {"width":60,"height":60,"scale":"cover","sizeSlug":"thumbnail","style":{"border":{"radius":"50%"}}} -->
<figure class="wp-block-image size-thumbnail is-resized" style="border-radius:50%"><img src="" alt="" width="60" height="60" style="object-fit:cover"/></figure>
<!-- /wp:image -->

<!-- wp:group {"style":{"spacing":{"blockGap":"0.25rem"}},"layout":{"type":"flex","orientation":"vertical"}} -->
<div class="wp-block-group"><!-- wp:heading {"level":4,"fontSize":"medium","style":{"spacing":{"margin":{"top":"0","bottom":"0"}}}} -->
<h4 class="wp-block-heading has-medium-font-size" style="margin-top:0;margin-bottom:0">Sarah Johnson</h4>
<!-- /wp:heading -->

<!-- wp:paragraph {"fontSize":"small","style":{"color":{"text":"var:preset|color|gray-600"},"spacing":{"margin":{"top":"0","bottom":"0"}}}} -->
<p class="has-small-font-size" style="color:var(--wp--preset--color--gray-600);margin-top:0;margin-bottom:0">Marketing Director, TechCorp</p>
<!-- /wp:paragraph --></div>
<!-- /wp:group --></div>
<!-- /wp:group --></div>
<!-- /wp:group -->',
        )
    );

    // CTA Banner Pattern
    register_block_pattern(
        'modernblog2025/cta-banner',
        array(
            'title'       => esc_html__('CTA Banner', 'modernblog2025'),
            'description' => esc_html__('Call-to-action banner with gradient background', 'modernblog2025'),
            'categories'  => array('modernblog2025-call-to-action'),
            'content'     => '<!-- wp:group {"style":{"spacing":{"padding":{"all":"3rem"}},"border":{"radius":"12px"}},"gradient":"blue-purple","layout":{"type":"constrained","contentSize":"600px"}} -->
<div class="wp-block-group has-blue-purple-gradient-background has-background" style="border-radius:12px;padding:3rem"><!-- wp:heading {"textAlign":"center","level":2,"fontSize":"x-large","style":{"color":{"text":"#ffffff"},"spacing":{"margin":{"bottom":"1rem"}}}} -->
<h2 class="wp-block-heading has-text-align-center has-x-large-font-size" style="color:#ffffff;margin-bottom:1rem">Ready to Get Started?</h2>
<!-- /wp:heading -->

<!-- wp:paragraph {"align":"center","style":{"color":{"text":"#ffffff"},"spacing":{"margin":{"bottom":"2rem"}}}} -->
<p class="has-text-align-center" style="color:#ffffff;margin-bottom:2rem">Join thousands of users who have already transformed their websites with our modern theme.</p>
<!-- /wp:paragraph -->

<!-- wp:buttons {"layout":{"type":"flex","justifyContent":"center"}} -->
<div class="wp-block-buttons"><!-- wp:button {"backgroundColor":"white","textColor":"primary","className":"is-style-shadow-hover"} -->
<div class="wp-block-button is-style-shadow-hover"><a class="wp-block-button__link has-primary-color has-white-background-color has-text-color has-background wp-element-button">Download Now</a></div>
<!-- /wp:button --></div>
<!-- /wp:buttons --></div>
<!-- /wp:group -->',
        )
    );

    // Feature Comparison Pattern
    register_block_pattern(
        'modernblog2025/feature-comparison',
        array(
            'title'       => esc_html__('Feature Comparison', 'modernblog2025'),
            'description' => esc_html__('Comparison table with features and checkmarks', 'modernblog2025'),
            'categories'  => array('modernblog2025-content'),
            'content'     => '<!-- wp:group {"layout":{"type":"constrained"}} -->
<div class="wp-block-group"><!-- wp:heading {"textAlign":"center","level":2,"fontSize":"x-large","style":{"spacing":{"margin":{"bottom":"3rem"}}}} -->
<h2 class="wp-block-heading has-text-align-center has-x-large-font-size" style="margin-bottom:3rem">Compare Features</h2>
<!-- /wp:heading -->

<!-- wp:table {"className":"is-style-stripes"} -->
<figure class="wp-block-table is-style-stripes"><table><thead><tr><th>Feature</th><th>Basic</th><th>Premium</th></tr></thead><tbody><tr><td>Responsive Design</td><td>✓</td><td>✓</td></tr><tr><td>Custom Colors</td><td>✓</td><td>✓</td></tr><tr><td>Advanced Blocks</td><td>—</td><td>✓</td></tr><tr><td>Premium Support</td><td>—</td><td>✓</td></tr><tr><td>Custom Layouts</td><td>—</td><td>✓</td></tr></tbody></table></figure>
<!-- /wp:table --></div>
<!-- /wp:group -->',
        )
    );
}
add_action('init', 'modernblog2025_register_advanced_patterns');
```

### 4. Block Editor Enhancements

Create `inc/block-editor-enhancements.php`:
```php
<?php
/**
 * Block Editor Enhancements
 *
 * @package ModernBlog2025
 * @since 1.0.0
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Customize block editor settings
 */
function modernblog2025_block_editor_settings($settings, $context) {
    
    // Enable custom font sizes
    $settings['fontSizes'] = array(
        array(
            'name' => esc_html__('Small', 'modernblog2025'),
            'size' => '14px',
            'slug' => 'small'
        ),
        array(
            'name' => esc_html__('Normal', 'modernblog2025'),
            'size' => '16px',
            'slug' => 'normal'
        ),
        array(
            'name' => esc_html__('Medium', 'modernblog2025'),
            'size' => '20px',
            'slug' => 'medium'
        ),
        array(
            'name' => esc_html__('Large', 'modernblog2025'),
            'size' => '24px',
            'slug' => 'large'
        ),
        array(
            'name' => esc_html__('Extra Large', 'modernblog2025'),
            'size' => '32px',
            'slug' => 'extra-large'
        )
    );
    
    // Disable custom font sizes
    $settings['disableCustomFontSizes'] = true;
    
    // Enable drop cap
    $settings['enableCustomLineHeight'] = true;
    $settings['enableCustomSpacing'] = true;
    $settings['enableCustomUnits'] = array('px', 'em', 'rem', '%', 'vh', 'vw');
    
    return $settings;
}
add_filter('block_editor_settings_all', 'modernblog2025_block_editor_settings', 10, 2);

/**
 * Add custom block categories
 */
function modernblog2025_custom_block_categories($categories, $post) {
    return array_merge(
        $categories,
        array(
            array(
                'slug'  => 'modernblog2025-blocks',
                'title' => esc_html__('ModernBlog2025 Blocks', 'modernblog2025'),
                'icon'  => 'admin-customizer',
            ),
        )
    );
}
add_filter('block_categories_all', 'modernblog2025_custom_block_categories', 10, 2);

/**
 * Enqueue block editor assets
 */
function modernblog2025_block_editor_assets() {
    wp_enqueue_script(
        'modernblog2025-block-editor',
        MODERNBLOG2025_THEME_URI . '/assets/js/block-editor.js',
        array('wp-blocks', 'wp-dom-ready', 'wp-edit-post'),
        MODERNBLOG2025_VERSION,
        true
    );
    
    wp_enqueue_style(
        'modernblog2025-block-editor-styles',
        MODERNBLOG2025_THEME_URI . '/assets/css/block-editor.css',
        array('wp-edit-blocks'),
        MODERNBLOG2025_VERSION
    );
}
add_action('enqueue_block_editor_assets', 'modernblog2025_block_editor_assets');

/**
 * Remove core block patterns (optional)
 */
function modernblog2025_remove_core_patterns() {
    remove_theme_support('core-block-patterns');
}
// Uncomment the line below to remove core WordPress block patterns
// add_action('after_setup_theme', 'modernblog2025_remove_core_patterns');

/**
 * Allowed blocks for the editor
 */
function modernblog2025_allowed_block_types($allowed_blocks, $post) {
    
    // Allow all blocks by default
    if (is_admin()) {
        return $allowed_blocks;
    }
    
    // Optionally restrict blocks for specific post types
    if ($post->post_type === 'page') {
        $allowed_blocks = array(
            'core/paragraph',
            'core/heading',
            'core/image',
            'core/list',
            'core/quote',
            'core/group',
            'core/columns',
            'core/column',
            'core/cover',
            'core/button',
            'core/buttons',
            'core/spacer',
            'core/separator',
            'core/media-text',
            'core/gallery',
            'core/embed',
            'core/html'
        );
    }
    
    return $allowed_blocks;
}
// Uncomment to enable block restrictions
// add_filter('allowed_block_types_all', 'modernblog2025_allowed_block_types', 10, 2);
```

### 5. Block Editor JavaScript

Create `assets/js/block-editor.js`:
```javascript
/**
 * Block Editor Enhancements
 * Customize the Gutenberg editor experience
 */

(function() {
    'use strict';
    
    // Wait for the DOM to be ready
    wp.domReady(function() {
        
        // Unregister unwanted block style variations
        wp.blocks.unregisterBlockStyle('core/button', 'outline');
        wp.blocks.unregisterBlockStyle('core/quote', 'large');
        
        // Add custom class to editor
        document.body.classList.add('modernblog2025-editor');
        
        // Enhanced block toolbar
        const { createHigherOrderComponent } = wp.compose;
        const { Fragment } = wp.element;
        const { InspectorControls } = wp.blockEditor;
        const { PanelBody, ToggleControl } = wp.components;
        
        // Add custom controls to blocks
        const withCustomControls = createHigherOrderComponent((BlockEdit) => {
            return (props) => {
                const { attributes, setAttributes, name } = props;
                
                // Only add controls to specific blocks
                if (name !== 'core/group' && name !== 'core/cover') {
                    return <BlockEdit {...props} />;
                }
                
                return (
                    <Fragment>
                        <BlockEdit {...props} />
                        <InspectorControls>
                            <PanelBody title="ModernBlog2025 Settings" initialOpen={false}>
                                <ToggleControl
                                    label="Add shadow effect"
                                    checked={attributes.addShadow || false}
                                    onChange={(addShadow) => setAttributes({ addShadow })}
                                />
                                <ToggleControl
                                    label="Enable hover animation"
                                    checked={attributes.enableHover || false}
                                    onChange={(enableHover) => setAttributes({ enableHover })}
                                />
                            </PanelBody>
                        </InspectorControls>
                    </Fragment>
                );
            };
        }, 'withCustomControls');
        
        wp.hooks.addFilter(
            'editor.BlockEdit',
            'modernblog2025/with-custom-controls',
            withCustomControls
        );
        
        // Apply custom classes based on attributes
        const addCustomClasses = (props, blockType, attributes) => {
            if (attributes.addShadow) {
                props.className = props.className ? 
                    props.className + ' has-shadow' : 'has-shadow';
            }
            
            if (attributes.enableHover) {
                props.className = props.className ? 
                    props.className + ' has-hover-effect' : 'has-hover-effect';
            }
            
            return props;
        };
        
        wp.hooks.addFilter(
            'blocks.getSaveContent.extraProps',
            'modernblog2025/add-custom-classes',
            addCustomClasses
        );
    });
    
    // Add editor-specific styles for better preview
    const editorStyles = `
        .modernblog2025-editor .editor-styles-wrapper {
            font-family: var(--wp--preset--font-family--system);
        }
        
        .modernblog2025-editor .has-shadow {
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.12);
        }
        
        .modernblog2025-editor .has-hover-effect {
            transition: transform 0.2s ease;
        }
        
        .modernblog2025-editor .has-hover-effect:hover {
            transform: translateY(-2px);
        }
    `;
    
    // Inject styles into editor
    const styleSheet = document.createElement('style');
    styleSheet.textContent = editorStyles;
    document.head.appendChild(styleSheet);
    
})();
```

## Verification Checklist

After implementing Gutenberg block support:

- [ ] Custom block styles are registered and working
- [ ] Block patterns appear in the pattern library
- [ ] Editor styles match frontend appearance
- [ ] Block editor enhancements function properly
- [ ] Performance is maintained with new features
- [ ] All block styles are responsive
- [ ] Accessibility standards are met

## Next Steps

In Step 11, we'll implement custom post types and fields to extend the theme's functionality beyond basic posts and pages.

## Best Practices Applied

1. **User Experience**: Custom block styles enhance content creation
2. **Performance**: Minimal CSS and JavaScript additions
3. **Accessibility**: All custom styles maintain accessibility standards
4. **Consistency**: Block styles follow the theme's design system
5. **Flexibility**: Comprehensive pattern library for varied layouts

## Advanced Features

### Custom Block Development
- Created reusable block patterns for common layouts
- Enhanced editor experience with custom controls
- Implemented performance-optimized styling

### Editor Integration
- Seamless integration with theme.json
- Custom controls for enhanced functionality
- Responsive design considerations

## Troubleshooting

**Block styles not appearing:**
- Check that styles are properly enqueued
- Verify block style registration syntax
- Clear browser and WordPress cache

**Editor styles don't match frontend:**
- Ensure editor styles include the same CSS
- Check theme.json configuration
- Verify custom property usage

**Performance issues with blocks:**
- Optimize CSS selectors
- Minimize JavaScript in editor
- Use efficient block registration methods