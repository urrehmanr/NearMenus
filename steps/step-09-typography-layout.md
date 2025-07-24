# Step 9: Typography & Layout Systems

## Objective
Implement advanced typography and layout systems that enhance readability, create visual hierarchy, and provide flexible design options while maintaining performance and accessibility standards.

## What You'll Learn
- Advanced typography scales and systems
- CSS Grid and Flexbox layout patterns
- Responsive typography implementation
- Custom layout components
- Content width management
- Vertical rhythm and spacing

## Typography System Enhancement

### 1. Advanced Typography Scale

Update `theme.json` typography settings:
```json
{
  "typography": {
    "fluid": true,
    "customFontSize": false,
    "dropCap": true,
    "fontStyle": true,
    "fontWeight": true,
    "letterSpacing": true,
    "lineHeight": true,
    "textDecoration": true,
    "textTransform": true,
    "fontFamilies": [
      {
        "name": "System Font Stack",
        "slug": "system",
        "fontFamily": "-apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen-Sans, Ubuntu, Cantarell, 'Helvetica Neue', sans-serif"
      },
      {
        "name": "Serif",
        "slug": "serif",
        "fontFamily": "Georgia, 'Times New Roman', Times, serif"
      },
      {
        "name": "Monospace",
        "slug": "monospace",
        "fontFamily": "Consolas, Monaco, 'Andale Mono', 'Ubuntu Mono', monospace"
      }
    ],
    "fontSizes": [
      {
        "name": "Extra Small",
        "slug": "x-small",
        "size": "0.75rem",
        "fluid": {
          "min": "0.75rem",
          "max": "0.875rem"
        }
      },
      {
        "name": "Small",
        "slug": "small",
        "size": "0.875rem",
        "fluid": {
          "min": "0.875rem",
          "max": "1rem"
        }
      },
      {
        "name": "Medium",
        "slug": "medium",
        "size": "1rem",
        "fluid": {
          "min": "1rem",
          "max": "1.125rem"
        }
      },
      {
        "name": "Large",
        "slug": "large",
        "size": "1.25rem",
        "fluid": {
          "min": "1.125rem",
          "max": "1.5rem"
        }
      },
      {
        "name": "Extra Large",
        "slug": "x-large",
        "size": "1.75rem",
        "fluid": {
          "min": "1.5rem",
          "max": "2rem"
        }
      },
      {
        "name": "XX Large",
        "slug": "xx-large",
        "size": "2.25rem",
        "fluid": {
          "min": "2rem",
          "max": "2.75rem"
        }
      },
      {
        "name": "Huge",
        "slug": "huge",
        "size": "3rem",
        "fluid": {
          "min": "2.5rem",
          "max": "4rem"
        }
      }
    ]
  }
}
```

### 2. Typography CSS Enhancements

Add to `assets/css/style.css`:
```css
/* ==========================================================================
   Advanced Typography System
   ========================================================================== */

/* Fluid Typography Base */
:root {
  --font-size-fluid-min: 1rem;
  --font-size-fluid-max: 1.25rem;
  --font-size-fluid-val: 4vw;
  
  /* Line height scale */
  --line-height-tight: 1.2;
  --line-height-normal: 1.5;
  --line-height-relaxed: 1.7;
  
  /* Letter spacing scale */
  --letter-spacing-tight: -0.025em;
  --letter-spacing-normal: 0;
  --letter-spacing-wide: 0.025em;
  --letter-spacing-wider: 0.05em;
}

/* Typography Utilities */
.font-system {
  font-family: var(--wp--preset--font-family--system);
}

.font-serif {
  font-family: var(--wp--preset--font-family--serif);
}

.font-mono {
  font-family: var(--wp--preset--font-family--monospace);
}

/* Font Weight Classes */
.font-thin { font-weight: 100; }
.font-light { font-weight: 300; }
.font-normal { font-weight: 400; }
.font-medium { font-weight: 500; }
.font-semibold { font-weight: 600; }
.font-bold { font-weight: 700; }
.font-extrabold { font-weight: 800; }
.font-black { font-weight: 900; }

/* Line Height Classes */
.leading-tight { line-height: var(--line-height-tight); }
.leading-normal { line-height: var(--line-height-normal); }
.leading-relaxed { line-height: var(--line-height-relaxed); }

/* Letter Spacing Classes */
.tracking-tight { letter-spacing: var(--letter-spacing-tight); }
.tracking-normal { letter-spacing: var(--letter-spacing-normal); }
.tracking-wide { letter-spacing: var(--letter-spacing-wide); }
.tracking-wider { letter-spacing: var(--letter-spacing-wider); }

/* Advanced Typography Styles */
.text-balance {
  text-wrap: balance;
}

.text-pretty {
  text-wrap: pretty;
}

/* Drop Caps Enhancement */
.has-drop-cap:not(:focus):first-letter {
  font-size: 5.5em;
  font-weight: 700;
  line-height: 0.8;
  float: left;
  margin: 0.1em 0.1em 0 0;
  color: var(--wp--preset--color--primary);
  font-family: var(--wp--preset--font-family--serif);
}

/* Text Selection */
::selection {
  background-color: var(--wp--preset--color--primary);
  color: var(--wp--preset--color--white);
}

::-moz-selection {
  background-color: var(--wp--preset--color--primary);
  color: var(--wp--preset--color--white);
}

/* Improved Readability */
.readable-text {
  max-width: 65ch;
  font-size: clamp(1rem, 2.5vw, 1.125rem);
  line-height: 1.7;
  font-family: var(--wp--preset--font-family--serif);
}

/* Code Typography */
code, pre {
  font-family: var(--wp--preset--font-family--monospace);
  font-size: 0.875em;
  background-color: var(--wp--preset--color--gray-100);
  padding: 0.125em 0.25em;
  border-radius: 2px;
}

pre {
  padding: 1rem;
  border-radius: 4px;
  overflow-x: auto;
  line-height: 1.5;
}

pre code {
  background: none;
  padding: 0;
}

/* Quote Typography */
blockquote {
  border-left: 4px solid var(--wp--preset--color--primary);
  padding-left: 1.5rem;
  margin: 2rem 0;
  font-style: italic;
  font-size: 1.125em;
  line-height: 1.6;
  color: var(--wp--preset--color--gray-700);
}

blockquote cite {
  display: block;
  margin-top: 1rem;
  font-style: normal;
  font-size: 0.875em;
  color: var(--wp--preset--color--gray-600);
}

blockquote cite::before {
  content: "— ";
}
```

## Advanced Layout System

### 1. CSS Grid Layout Components

Add to `assets/css/style.css`:
```css
/* ==========================================================================
   Advanced Layout System
   ========================================================================== */

/* Container Queries Support */
@supports (container-type: inline-size) {
  .layout-container {
    container-type: inline-size;
  }
  
  @container (min-width: 400px) {
    .card-layout {
      display: grid;
      grid-template-columns: auto 1fr;
      gap: 1rem;
    }
  }
  
  @container (min-width: 600px) {
    .card-layout {
      grid-template-columns: 200px 1fr;
    }
  }
}

/* CSS Grid Layouts */
.grid-layout {
  display: grid;
  gap: var(--wp--preset--spacing--medium);
}

.grid-auto-fit {
  grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
}

.grid-auto-fill {
  grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
}

.grid-2-cols {
  grid-template-columns: repeat(2, 1fr);
}

.grid-3-cols {
  grid-template-columns: repeat(3, 1fr);
}

.grid-4-cols {
  grid-template-columns: repeat(4, 1fr);
}

/* Responsive Grid */
.grid-responsive {
  grid-template-columns: 1fr;
}

@media (min-width: 768px) {
  .grid-responsive {
    grid-template-columns: repeat(2, 1fr);
  }
}

@media (min-width: 1024px) {
  .grid-responsive {
    grid-template-columns: repeat(3, 1fr);
  }
}

/* Flexbox Layouts */
.flex-layout {
  display: flex;
  gap: var(--wp--preset--spacing--medium);
}

.flex-wrap {
  flex-wrap: wrap;
}

.flex-nowrap {
  flex-wrap: nowrap;
}

.flex-col {
  flex-direction: column;
}

.flex-row {
  flex-direction: row;
}

/* Alignment Classes */
.items-start { align-items: flex-start; }
.items-center { align-items: center; }
.items-end { align-items: flex-end; }
.items-stretch { align-items: stretch; }

.justify-start { justify-content: flex-start; }
.justify-center { justify-content: center; }
.justify-end { justify-content: flex-end; }
.justify-between { justify-content: space-between; }
.justify-around { justify-content: space-around; }
.justify-evenly { justify-content: space-evenly; }

/* Content Width Management */
.content-narrow {
  max-width: 600px;
  margin-left: auto;
  margin-right: auto;
}

.content-normal {
  max-width: 800px;
  margin-left: auto;
  margin-right: auto;
}

.content-wide {
  max-width: 1200px;
  margin-left: auto;
  margin-right: auto;
}

.content-full {
  width: 100%;
  max-width: none;
}

/* Aspect Ratio Utilities */
.aspect-square { aspect-ratio: 1/1; }
.aspect-video { aspect-ratio: 16/9; }
.aspect-4-3 { aspect-ratio: 4/3; }
.aspect-3-2 { aspect-ratio: 3/2; }
.aspect-golden { aspect-ratio: 1.618/1; }

/* Object Fit Utilities */
.object-contain { object-fit: contain; }
.object-cover { object-fit: cover; }
.object-fill { object-fit: fill; }
.object-none { object-fit: none; }
.object-scale-down { object-fit: scale-down; }

.object-top { object-position: top; }
.object-bottom { object-position: bottom; }
.object-center { object-position: center; }
.object-left { object-position: left; }
.object-right { object-position: right; }

/* Advanced Spacing System */
.space-y-0 > * + * { margin-top: 0; }
.space-y-1 > * + * { margin-top: 0.25rem; }
.space-y-2 > * + * { margin-top: 0.5rem; }
.space-y-3 > * + * { margin-top: 0.75rem; }
.space-y-4 > * + * { margin-top: 1rem; }
.space-y-6 > * + * { margin-top: 1.5rem; }
.space-y-8 > * + * { margin-top: 2rem; }

.space-x-0 > * + * { margin-left: 0; }
.space-x-1 > * + * { margin-left: 0.25rem; }
.space-x-2 > * + * { margin-left: 0.5rem; }
.space-x-3 > * + * { margin-left: 0.75rem; }
.space-x-4 > * + * { margin-left: 1rem; }
.space-x-6 > * + * { margin-left: 1.5rem; }
.space-x-8 > * + * { margin-left: 2rem; }

/* Sticky Elements */
.sticky-top {
  position: sticky;
  top: 0;
  z-index: var(--z-index-sticky);
}

.sticky-bottom {
  position: sticky;
  bottom: 0;
  z-index: var(--z-index-sticky);
}
```

### 2. Layout Block Patterns

Create `inc/layout-patterns.php`:
```php
<?php
/**
 * Layout Block Patterns
 *
 * @package ModernBlog2025
 * @since 1.0.0
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Register layout block patterns
 */
function modernblog2025_register_layout_patterns() {
    
    if (!function_exists('register_block_pattern')) {
        return;
    }

    // Register layout pattern category
    register_block_pattern_category(
        'modernblog2025-layouts',
        array('label' => esc_html__('ModernBlog2025 Layouts', 'modernblog2025'))
    );

    // Hero section with call-to-action
    register_block_pattern(
        'modernblog2025/hero-cta',
        array(
            'title'       => esc_html__('Hero with CTA', 'modernblog2025'),
            'description' => esc_html__('Large hero section with call-to-action button', 'modernblog2025'),
            'categories'  => array('modernblog2025-layouts'),
            'content'     => '<!-- wp:cover {"url":"","dimRatio":40,"minHeight":500,"contentPosition":"center center","isDark":false,"className":"hero-cta"} -->
<div class="wp-block-cover hero-cta" style="min-height:500px"><span aria-hidden="true" class="wp-block-cover__background has-background-dim-40"></span><div class="wp-block-cover__inner-container"><!-- wp:group {"layout":{"type":"constrained","contentSize":"800px"}} -->
<div class="wp-block-group"><!-- wp:heading {"textAlign":"center","level":1,"fontSize":"huge","className":"text-balance"} -->
<h1 class="wp-block-heading has-text-align-center has-huge-font-size text-balance">Build Amazing Websites with Modern Tools</h1>
<!-- /wp:heading -->

<!-- wp:paragraph {"align":"center","fontSize":"large","className":"text-pretty"} -->
<p class="has-text-align-center has-large-font-size text-pretty">Discover the latest techniques and best practices for creating high-performance WordPress themes that delight users.</p>
<!-- /wp:paragraph -->

<!-- wp:buttons {"layout":{"type":"flex","justifyContent":"center"},"style":{"spacing":{"margin":{"top":"2rem"}}}} -->
<div class="wp-block-buttons" style="margin-top:2rem"><!-- wp:button {"fontSize":"medium","className":"font-semibold"} -->
<div class="wp-block-button font-semibold has-medium-font-size"><a class="wp-block-button__link wp-element-button">Get Started Today</a></div>
<!-- /wp:button --></div>
<!-- /wp:buttons --></div>
<!-- /wp:group --></div></div>
<!-- /wp:cover -->',
        )
    );

    // Feature grid layout
    register_block_pattern(
        'modernblog2025/feature-grid',
        array(
            'title'       => esc_html__('Feature Grid', 'modernblog2025'),
            'description' => esc_html__('Three-column feature grid with icons', 'modernblog2025'),
            'categories'  => array('modernblog2025-layouts'),
            'content'     => '<!-- wp:group {"className":"grid-layout grid-3-cols","layout":{"type":"constrained"}} -->
<div class="wp-block-group grid-layout grid-3-cols"><!-- wp:group {"style":{"spacing":{"padding":{"all":"2rem"}},"border":{"radius":"8px"}},"backgroundColor":"gray-100","className":"text-center"} -->
<div class="wp-block-group text-center has-gray-100-background-color has-background" style="border-radius:8px;padding:2rem"><!-- wp:image {"width":64,"height":64,"sizeSlug":"thumbnail","className":"mx-auto"} -->
<figure class="wp-block-image size-thumbnail is-resized mx-auto"><img src="" alt="" width="64" height="64"/></figure>
<!-- /wp:image -->

<!-- wp:heading {"textAlign":"center","level":3,"fontSize":"large"} -->
<h3 class="wp-block-heading has-text-align-center has-large-font-size">Fast Performance</h3>
<!-- /wp:heading -->

<!-- wp:paragraph {"align":"center"} -->
<p class="has-text-align-center">Optimized for Core Web Vitals with 95+ Lighthouse scores.</p>
<!-- /wp:paragraph --></div>
<!-- /wp:group -->

<!-- wp:group {"style":{"spacing":{"padding":{"all":"2rem"}},"border":{"radius":"8px"}},"backgroundColor":"gray-100","className":"text-center"} -->
<div class="wp-block-group text-center has-gray-100-background-color has-background" style="border-radius:8px;padding:2rem"><!-- wp:image {"width":64,"height":64,"sizeSlug":"thumbnail","className":"mx-auto"} -->
<figure class="wp-block-image size-thumbnail is-resized mx-auto"><img src="" alt="" width="64" height="64"/></figure>
<!-- /wp:image -->

<!-- wp:heading {"textAlign":"center","level":3,"fontSize":"large"} -->
<h3 class="wp-block-heading has-text-align-center has-large-font-size">Modern Design</h3>
<!-- /wp:heading -->

<!-- wp:paragraph {"align":"center"} -->
<p class="has-text-align-center">Clean, contemporary layouts that work on all devices.</p>
<!-- /wp:paragraph --></div>
<!-- /wp:group -->

<!-- wp:group {"style":{"spacing":{"padding":{"all":"2rem"}},"border":{"radius":"8px"}},"backgroundColor":"gray-100","className":"text-center"} -->
<div class="wp-block-group text-center has-gray-100-background-color has-background" style="border-radius:8px;padding:2rem"><!-- wp:image {"width":64,"height":64,"sizeSlug":"thumbnail","className":"mx-auto"} -->
<figure class="wp-block-image size-thumbnail is-resized mx-auto"><img src="" alt="" width="64" height="64"/></figure>
<!-- /wp:image -->

<!-- wp:heading {"textAlign":"center","level":3,"fontSize":"large"} -->
<h3 class="wp-block-heading has-text-align-center has-large-font-size">SEO Ready</h3>
<!-- /wp:heading -->

<!-- wp:paragraph {"align":"center"} -->
<p class="has-text-align-center">Built with semantic HTML and optimized for search engines.</p>
<!-- /wp:paragraph --></div>
<!-- /wp:group --></div>
<!-- /wp:group -->',
        )
    );

    // Card layout pattern
    register_block_pattern(
        'modernblog2025/card-layout',
        array(
            'title'       => esc_html__('Card Layout', 'modernblog2025'),
            'description' => esc_html__('Responsive card layout with images and content', 'modernblog2025'),
            'categories'  => array('modernblog2025-layouts'),
            'content'     => '<!-- wp:group {"className":"grid-layout grid-auto-fit","layout":{"type":"constrained"}} -->
<div class="wp-block-group grid-layout grid-auto-fit"><!-- wp:group {"style":{"spacing":{"padding":{"all":"0"}},"border":{"radius":"8px"}},"backgroundColor":"white","className":"card-layout"} -->
<div class="wp-block-group card-layout has-white-background-color has-background" style="border-radius:8px;padding:0"><!-- wp:image {"aspectRatio":"16/9","scale":"cover","sizeSlug":"large","className":"object-cover"} -->
<figure class="wp-block-image size-large object-cover"><img src="" alt="" style="aspect-ratio:16/9;object-fit:cover"/></figure>
<!-- /wp:image -->

<!-- wp:group {"style":{"spacing":{"padding":{"all":"1.5rem"}}},"layout":{"type":"flex","orientation":"vertical"}} -->
<div class="wp-block-group" style="padding:1.5rem"><!-- wp:heading {"level":3,"fontSize":"medium"} -->
<h3 class="wp-block-heading has-medium-font-size">Card Title</h3>
<!-- /wp:heading -->

<!-- wp:paragraph {"fontSize":"small"} -->
<p class="has-small-font-size">Brief description of the card content goes here.</p>
<!-- /wp:paragraph -->

<!-- wp:buttons -->
<div class="wp-block-buttons"><!-- wp:button {"size":"small"} -->
<div class="wp-block-button has-custom-font-size has-small-font-size"><a class="wp-block-button__link wp-element-button">Read More</a></div>
<!-- /wp:button --></div>
<!-- /wp:buttons --></div>
<!-- /wp:group --></div>
<!-- /wp:group --></div>
<!-- /wp:group -->',
        )
    );

    // Sidebar layout pattern
    register_block_pattern(
        'modernblog2025/content-sidebar',
        array(
            'title'       => esc_html__('Content with Sidebar', 'modernblog2025'),
            'description' => esc_html__('Main content area with complementary sidebar', 'modernblog2025'),
            'categories'  => array('modernblog2025-layouts'),
            'content'     => '<!-- wp:columns {"style":{"spacing":{"blockGap":{"top":"2rem","left":"3rem"}}}} -->
<div class="wp-block-columns"><!-- wp:column {"width":"70%"} -->
<div class="wp-block-column" style="flex-basis:70%"><!-- wp:heading {"level":2,"fontSize":"x-large"} -->
<h2 class="wp-block-heading has-x-large-font-size">Main Content Area</h2>
<!-- /wp:heading -->

<!-- wp:paragraph {"className":"readable-text"} -->
<p class="readable-text">This is the main content area where your primary content will be displayed. The text is optimized for readability with proper line height and character width.</p>
<!-- /wp:paragraph -->

<!-- wp:paragraph {"className":"readable-text"} -->
<p class="readable-text">Additional paragraphs and content blocks can be added here to create a comprehensive article or page layout.</p>
<!-- /wp:paragraph --></div>
<!-- /wp:column -->

<!-- wp:column {"width":"30%"} -->
<div class="wp-block-column" style="flex-basis:30%"><!-- wp:group {"style":{"spacing":{"padding":{"all":"1.5rem"}},"border":{"radius":"8px"}},"backgroundColor":"gray-100"} -->
<div class="wp-block-group has-gray-100-background-color has-background" style="border-radius:8px;padding:1.5rem"><!-- wp:heading {"level":3,"fontSize":"medium"} -->
<h3 class="wp-block-heading has-medium-font-size">Sidebar Widget</h3>
<!-- /wp:heading -->

<!-- wp:paragraph {"fontSize":"small"} -->
<p class="has-small-font-size">Sidebar content such as recent posts, categories, or promotional content.</p>
<!-- /wp:paragraph --></div>
<!-- /wp:group --></div>
<!-- /wp:column --></div>
<!-- /wp:columns -->',
        )
    );
}
add_action('init', 'modernblog2025_register_layout_patterns');
```

### 3. Responsive Layout Utilities

Add to `assets/css/responsive.css`:
```css
/* ==========================================================================
   Responsive Layout Utilities
   ========================================================================== */

/* Mobile-first responsive utilities */
@media (max-width: 767px) {
  .mobile-hidden { display: none !important; }
  .mobile-block { display: block !important; }
  .mobile-flex { display: flex !important; }
  .mobile-grid { display: grid !important; }
  
  .mobile-text-center { text-align: center !important; }
  .mobile-text-left { text-align: left !important; }
  
  .mobile-full-width {
    width: 100vw !important;
    margin-left: 50% !important;
    transform: translateX(-50%) !important;
  }
  
  /* Stack columns on mobile */
  .mobile-stack {
    flex-direction: column !important;
  }
  
  .mobile-stack > * {
    width: 100% !important;
    flex-basis: auto !important;
  }
}

/* Tablet responsive utilities */
@media (min-width: 768px) and (max-width: 1023px) {
  .tablet-hidden { display: none !important; }
  .tablet-block { display: block !important; }
  .tablet-flex { display: flex !important; }
  .tablet-grid { display: grid !important; }
  
  .tablet-2-cols {
    grid-template-columns: repeat(2, 1fr) !important;
  }
}

/* Desktop responsive utilities */
@media (min-width: 1024px) {
  .desktop-hidden { display: none !important; }
  .desktop-block { display: block !important; }
  .desktop-flex { display: flex !important; }
  .desktop-grid { display: grid !important; }
  
  .desktop-3-cols {
    grid-template-columns: repeat(3, 1fr) !important;
  }
  
  .desktop-4-cols {
    grid-template-columns: repeat(4, 1fr) !important;
  }
}

/* Print utilities */
@media print {
  .print-hidden { display: none !important; }
  .print-block { display: block !important; }
  
  .print-break-before { break-before: page; }
  .print-break-after { break-after: page; }
  .print-break-inside-avoid { break-inside: avoid; }
}
```

## Advanced Content Layout Components

### 1. Reading Layout Component

Create `inc/reading-layout.php`:
```php
<?php
/**
 * Reading Layout Enhancements
 *
 * @package ModernBlog2025
 * @since 1.0.0
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Enhanced content layout for better readability
 */
function modernblog2025_enhance_content_layout($content) {
    if (is_admin() || !is_singular()) {
        return $content;
    }
    
    // Add reading-optimized classes to paragraphs
    $content = preg_replace('/<p>/', '<p class="readable-text">', $content);
    
    // Enhance blockquotes
    $content = preg_replace('/<blockquote>/', '<blockquote class="text-pretty">', $content);
    
    // Add proper spacing to headings
    $content = preg_replace('/<h([2-6])([^>]*)>/', '<h$1$2 class="space-y-4">', $content);
    
    return $content;
}
add_filter('the_content', 'modernblog2025_enhance_content_layout');

/**
 * Add estimated reading time
 */
function modernblog2025_reading_time($content) {
    if (!is_singular('post')) {
        return;
    }
    
    $word_count = str_word_count(strip_tags($content));
    $reading_time = ceil($word_count / 200); // Average reading speed: 200 words per minute
    
    if ($reading_time < 1) {
        $reading_time = 1;
    }
    
    return sprintf(
        '<div class="reading-time text-small text-gray-600 mb-4">%s</div>',
        sprintf(
            esc_html(_n('%d minute read', '%d minutes read', $reading_time, 'modernblog2025')),
            $reading_time
        )
    );
}

/**
 * Add reading progress indicator
 */
function modernblog2025_reading_progress() {
    if (!is_singular('post')) {
        return;
    }
    ?>
    <div id="reading-progress" class="reading-progress" style="
        position: fixed;
        top: 0;
        left: 0;
        width: 0%;
        height: 3px;
        background: var(--wp--preset--color--primary);
        z-index: 1000;
        transition: width 0.2s ease;
    "></div>
    
    <script>
    (function() {
        const progressBar = document.getElementById('reading-progress');
        
        if (!progressBar) return;
        
        function updateProgress() {
            const scrollTop = window.scrollY;
            const docHeight = document.documentElement.scrollHeight - window.innerHeight;
            const scrollPercent = (scrollTop / docHeight) * 100;
            
            progressBar.style.width = Math.min(scrollPercent, 100) + '%';
        }
        
        window.addEventListener('scroll', updateProgress, { passive: true });
        updateProgress();
    })();
    </script>
    <?php
}
add_action('wp_footer', 'modernblog2025_reading_progress');

/**
 * Table of contents generation
 */
function modernblog2025_generate_toc($content) {
    if (!is_singular('post') || !get_theme_mod('enable_toc', false)) {
        return $content;
    }
    
    // Extract headings
    preg_match_all('/<h([2-6])[^>]*>(.*?)<\/h[2-6]>/i', $content, $matches, PREG_SET_ORDER);
    
    if (empty($matches)) {
        return $content;
    }
    
    $toc = '<div class="table-of-contents">';
    $toc .= '<h3>' . esc_html__('Table of Contents', 'modernblog2025') . '</h3>';
    $toc .= '<ul class="toc-list">';
    
    foreach ($matches as $match) {
        $level = $match[1];
        $text = strip_tags($match[2]);
        $id = sanitize_title($text);
        
        // Add ID to the heading in content
        $content = str_replace($match[0], 
            '<h' . $level . ' id="' . $id . '">' . $match[2] . '</h' . $level . '>', 
            $content
        );
        
        $toc .= '<li class="toc-level-' . $level . '">';
        $toc .= '<a href="#' . $id . '">' . esc_html($text) . '</a>';
        $toc .= '</li>';
    }
    
    $toc .= '</ul></div>';
    
    // Insert TOC after the first paragraph
    $content = preg_replace('/(<p[^>]*>.*?<\/p>)/', '$1' . $toc, $content, 1);
    
    return $content;
}
add_filter('the_content', 'modernblog2025_generate_toc');
```

### 2. Layout CSS Additions

Add to `assets/css/style.css`:
```css
/* ==========================================================================
   Reading Layout Components
   ========================================================================== */

/* Reading Time */
.reading-time {
  font-size: var(--wp--preset--font-size--small);
  color: var(--wp--preset--color--gray-600);
  margin-bottom: 1rem;
}

/* Table of Contents */
.table-of-contents {
  background: var(--wp--preset--color--gray-100);
  border-radius: 8px;
  padding: 1.5rem;
  margin: 2rem 0;
  border-left: 4px solid var(--wp--preset--color--primary);
}

.table-of-contents h3 {
  margin-top: 0;
  margin-bottom: 1rem;
  font-size: var(--wp--preset--font-size--medium);
  color: var(--wp--preset--color--gray-800);
}

.toc-list {
  margin: 0;
  padding: 0;
  list-style: none;
}

.toc-list li {
  margin-bottom: 0.5rem;
}

.toc-list a {
  color: var(--wp--preset--color--gray-700);
  text-decoration: none;
  font-size: var(--wp--preset--font-size--small);
  transition: color var(--transition-base);
}

.toc-list a:hover {
  color: var(--wp--preset--color--primary);
}

.toc-level-3 { padding-left: 1rem; }
.toc-level-4 { padding-left: 2rem; }
.toc-level-5 { padding-left: 3rem; }
.toc-level-6 { padding-left: 4rem; }

/* Enhanced Lists */
.wp-block-list li {
  margin-bottom: 0.5rem;
}

.wp-block-list li::marker {
  color: var(--wp--preset--color--primary);
}

/* Enhanced Tables */
.wp-block-table table {
  border-collapse: collapse;
  width: 100%;
  margin: 2rem 0;
}

.wp-block-table th,
.wp-block-table td {
  border: 1px solid var(--wp--preset--color--gray-200);
  padding: 0.75rem;
  text-align: left;
}

.wp-block-table th {
  background-color: var(--wp--preset--color--gray-100);
  font-weight: 600;
}

.wp-block-table tbody tr:nth-child(even) {
  background-color: var(--wp--preset--color--gray-50);
}

/* Scroll margin for anchor links */
h1, h2, h3, h4, h5, h6 {
  scroll-margin-top: 100px;
}
```

## Verification Checklist

After implementing typography and layout systems:

- [ ] Typography scale is consistent and readable
- [ ] Fluid typography works across devices
- [ ] Layout components are flexible and responsive
- [ ] Grid and flexbox utilities function properly
- [ ] Reading enhancements improve user experience
- [ ] Block patterns are available in editor
- [ ] Performance is maintained with new features

## Next Steps

In Step 10, we'll enhance Gutenberg block support with custom block styles and advanced block configurations.

## Best Practices Applied

1. **Typography Hierarchy**: Clear visual hierarchy with consistent scaling
2. **Responsive Design**: Mobile-first approach with flexible layouts
3. **Performance**: Minimal CSS with efficient selectors
4. **Accessibility**: Proper contrast, focus states, and semantic markup
5. **User Experience**: Reading-optimized layouts and helpful features

## Advanced Features

### Progressive Enhancement
- Container queries for advanced browsers
- CSS Grid with fallbacks
- Advanced typography features where supported

### Performance Optimizations
- CSS custom properties for dynamic theming
- Minimal JavaScript for enhanced features
- Efficient layout algorithms

## Troubleshooting

**Typography not scaling:**
- Check fluid typography settings in theme.json
- Verify CSS custom properties are loading
- Test across different viewport sizes

**Layout components not working:**
- Validate CSS Grid and Flexbox support
- Check for conflicting styles
- Verify utility classes are properly applied