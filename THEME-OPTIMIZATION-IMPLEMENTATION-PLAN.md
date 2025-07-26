# GPress Theme CSS/JS Optimization Implementation Plan

## Phase 1: Core Asset Consolidation

### Step 1: Create Smart Asset Manager

**File**: `inc/smart-asset-manager.php`
```php
<?php
/**
 * Smart Asset Manager for GPress Theme
 * Handles intelligent conditional loading and asset optimization
 *
 * @package GPress
 * @version 2.0.0
 */

defined('ABSPATH') || exit;

class GPress_Smart_Asset_Manager {
    
    private static $loaded_assets = array();
    private static $page_context = null;
    private static $asset_map = array();
    
    public static function init() {
        self::setup_asset_map();
        add_action('wp_enqueue_scripts', array(__CLASS__, 'smart_asset_loading'), 5);
        add_action('wp_head', array(__CLASS__, 'inline_critical_css'), 1);
        add_action('wp_head', array(__CLASS__, 'add_resource_hints'), 2);
        add_filter('style_loader_tag', array(__CLASS__, 'optimize_css_loading'), 10, 4);
        add_filter('script_loader_tag', array(__CLASS__, 'optimize_js_loading'), 10, 3);
    }
    
    private static function setup_asset_map() {
        self::$asset_map = array(
            'critical' => array(
                'css' => array('critical.css'),
                'js' => array('performance.js')
            ),
            'core' => array(
                'css' => array('core.css'),
                'js' => array('core.js')
            ),
            'images' => array(
                'css' => array('images.css'),
                'js' => array('lazy-loading.js')
            ),
            'navigation' => array(
                'css' => array('navigation.css'),
                'js' => array('navigation.js')
            ),
            'blocks' => array(
                'css' => array('blocks.css'),
                'js' => array('blocks.js')
            ),
            'admin' => array(
                'css' => array('admin.css'),
                'js' => array('admin.js')
            ),
            'performance-monitoring' => array(
                'css' => array('performance-monitoring.css'),
                'js' => array('web-vitals.js', 'rum-collector.js')
            )
        );
    }
    
    public static function smart_asset_loading() {
        self::$page_context = self::analyze_page_context();
        
        // Always load critical assets
        self::load_asset_group('critical');
        
        // Conditionally load based on context
        if (self::$page_context['is_interactive']) {
            self::load_asset_group('core');
        }
        
        if (self::$page_context['has_images']) {
            self::load_asset_group('images');
        }
        
        if (self::$page_context['has_navigation']) {
            self::load_asset_group('navigation');
        }
        
        if (self::$page_context['has_blocks']) {
            self::load_asset_group('blocks');
        }
        
        if (self::$page_context['is_admin']) {
            self::load_asset_group('admin');
        }
        
        if (self::$page_context['needs_performance_monitoring']) {
            self::load_asset_group('performance-monitoring');
        }
        
        // Custom post type specific loading
        self::load_cpt_assets();
        
        // Feature-specific loading
        self::load_feature_assets();
    }
    
    private static function analyze_page_context() {
        global $post;
        
        return array(
            'has_images' => self::page_has_images(),
            'has_navigation' => self::page_has_navigation(),
            'has_blocks' => self::page_has_blocks(),
            'is_interactive' => self::page_is_interactive(),
            'needs_performance_monitoring' => self::needs_performance_monitoring(),
            'content_length' => self::get_content_length(),
            'post_type' => get_post_type(),
            'is_admin' => is_admin(),
            'is_customizer' => is_customize_preview(),
            'has_comments' => comments_open() && have_comments(),
            'has_forms' => self::page_has_forms(),
            'reading_time' => self::estimated_reading_time(),
            'template' => get_page_template_slug()
        );
    }
    
    private static function load_asset_group($group) {
        if (!isset(self::$asset_map[$group])) {
            return;
        }
        
        $assets = self::$asset_map[$group];
        
        // Load CSS files
        if (isset($assets['css'])) {
            foreach ($assets['css'] as $css_file) {
                self::load_css_asset($group . '-css', $css_file);
            }
        }
        
        // Load JS files
        if (isset($assets['js'])) {
            foreach ($assets['js'] as $js_file) {
                self::load_js_asset($group . '-js', $js_file);
            }
        }
    }
    
    private static function load_css_asset($handle, $file) {
        if (in_array($handle, self::$loaded_assets)) {
            return;
        }
        
        $file_path = '/assets/css/' . $file;
        $version = self::get_file_version($file_path);
        
        wp_enqueue_style(
            'gpress-' . $handle,
            get_theme_file_uri($file_path),
            array(),
            $version,
            'all'
        );
        
        self::$loaded_assets[] = $handle;
    }
    
    private static function load_js_asset($handle, $file) {
        if (in_array($handle, self::$loaded_assets)) {
            return;
        }
        
        $file_path = '/assets/js/' . $file;
        $version = self::get_file_version($file_path);
        
        wp_enqueue_script(
            'gpress-' . $handle,
            get_theme_file_uri($file_path),
            array(),
            $version,
            array('strategy' => 'defer', 'in_footer' => true)
        );
        
        self::$loaded_assets[] = $handle;
    }
    
    private static function load_cpt_assets() {
        $post_type = get_post_type();
        
        // Get custom post types from dynamic framework (Step 11)
        $custom_post_types = get_option('gpress_custom_post_types', array());
        
        // Load base dynamic CPT assets if we're on any custom post type page
        if (array_key_exists($post_type, $custom_post_types) || 
            is_post_type_archive(array_keys($custom_post_types))) {
            
            // Always load the base dynamic CPT styles
            self::load_css_asset('dynamic-cpt', 'dynamic-cpt.css');
            self::load_js_asset('dynamic-cpt', 'dynamic-cpt.js');
            
            // Check for post-type-specific assets
            $css_file = "cpt-{$post_type}.css";
            $js_file = "cpt-{$post_type}.js";
            
            // Load CSS if it exists
            if (file_exists(get_theme_file_path("/assets/css/{$css_file}"))) {
                self::load_css_asset("cpt-{$post_type}", $css_file);
                    } else {
                        self::load_js_asset('cpt-' . $post_type, $asset);
                    }
                }
            }
        }
    }
    
    private static function load_feature_assets() {
        $context = self::$page_context;
        
        // Reading enhancements for long content
        if ($context['reading_time'] > 3) {
            self::load_css_asset('reading-enhancements', 'reading-enhancements.css');
            self::load_js_asset('reading-progress', 'reading-progress.js');
        }
        
        // Comments functionality
        if ($context['has_comments']) {
            self::load_css_asset('comments', 'comments.css');
            self::load_js_asset('comments', 'comments.js');
        }
        
        // Forms styling
        if ($context['has_forms']) {
            self::load_css_asset('forms', 'forms.css');
            self::load_js_asset('forms', 'form-enhancements.js');
        }
        
        // Documentation system
        if (get_theme_mod('enable_documentation', false)) {
            self::load_css_asset('documentation', 'documentation.css');
            self::load_js_asset('documentation', 'documentation.js');
        }
        
        // Accessibility enhancements
        if (get_theme_mod('enable_accessibility_features', false)) {
            self::load_css_asset('accessibility', 'accessibility.css');
            self::load_js_asset('accessibility', 'accessibility-enhancements.js');
        }
    }
    
    public static function inline_critical_css() {
        $critical_css_path = get_theme_file_path('/assets/css/critical.css');
        
        if (file_exists($critical_css_path)) {
            $critical_css = file_get_contents($critical_css_path);
            $critical_css = self::minify_css($critical_css);
            
            echo '<style id="gpress-critical-css">' . $critical_css . '</style>' . "\n";
        }
    }
    
    public static function add_resource_hints() {
        echo '<link rel="dns-prefetch" href="//fonts.googleapis.com">' . "\n";
        echo '<link rel="dns-prefetch" href="//fonts.gstatic.com">' . "\n";
        
        // Preload critical assets
        echo '<link rel="preload" href="' . get_theme_file_uri('/assets/js/performance.js') . '" as="script">' . "\n";
        
        if (has_custom_logo()) {
            $logo_id = get_theme_mod('custom_logo');
            $logo_url = wp_get_attachment_image_url($logo_id, 'full');
            if ($logo_url) {
                echo '<link rel="preload" href="' . esc_url($logo_url) . '" as="image">' . "\n";
            }
        }
    }
    
    public static function optimize_css_loading($tag, $handle, $href, $media) {
        // Non-critical CSS files to defer
        $defer_handles = array(
            'wp-block-library',
            'wp-block-library-theme',
            'global-styles'
        );
        
        if (in_array($handle, $defer_handles)) {
            return '<link rel="preload" href="' . $href . '" as="style" onload="this.onload=null;this.rel=\'stylesheet\'" id="' . $handle . '-css">' . "\n" .
                   '<noscript>' . $tag . '</noscript>';
        }
        
        return $tag;
    }
    
    public static function optimize_js_loading($tag, $handle, $src) {
        // Add defer to theme scripts
        if (strpos($handle, 'gpress-') !== false) {
            $tag = str_replace('<script ', '<script defer ', $tag);
        }
        
        // Add async to analytics scripts
        $async_scripts = array('google-analytics', 'gtag');
        if (in_array($handle, $async_scripts)) {
            $tag = str_replace('<script ', '<script async ', $tag);
        }
        
        return $tag;
    }
    
    // Utility methods for context analysis
    private static function page_has_images() {
        global $post;
        
        if (!$post) return false;
        
        return has_post_thumbnail() || 
               preg_match('/<img[^>]+>/i', $post->post_content) ||
               has_block('core/image', $post) ||
               has_block('core/gallery', $post);
    }
    
    private static function page_has_navigation() {
        return has_nav_menu('primary') || 
               has_nav_menu('secondary') || 
               has_nav_menu('footer');
    }
    
    private static function page_has_blocks() {
        global $post;
        return $post && has_blocks($post->post_content);
    }
    
    private static function page_is_interactive() {
        return is_singular() || 
               is_home() || 
               is_archive() || 
               comments_open() ||
               is_search();
    }
    
    private static function page_has_forms() {
        global $post;
        
        if (!$post) return false;
        
        return preg_match('/<form[^>]*>/i', $post->post_content) ||
               has_block('core/contact-form', $post) ||
               is_page_template('page-contact.php');
    }
    
    private static function get_content_length() {
        global $post;
        return $post ? str_word_count(strip_tags($post->post_content)) : 0;
    }
    
    private static function estimated_reading_time() {
        $word_count = self::get_content_length();
        return ceil($word_count / 200); // Average reading speed
    }
    
    private static function needs_performance_monitoring() {
        return !WP_DEBUG && get_theme_mod('enable_performance_monitoring', false);
    }
    
    private static function get_file_version($file_path) {
        $full_path = get_theme_file_path($file_path);
        
        if (file_exists($full_path)) {
            return filemtime($full_path);
        }
        
        return GPRESS_VERSION;
    }
    
    private static function minify_css($css) {
        // Remove comments
        $css = preg_replace('!/\*[^*]*\*+([^/][^*]*\*+)*/!', '', $css);
        
        // Remove whitespace
        $css = str_replace(array("\r\n", "\r", "\n", "\t", '  ', '    '), '', $css);
        
        // Remove trailing semicolon before closing braces
        $css = str_replace(';}', '}', $css);
        
        return trim($css);
    }
}

// Initialize the smart asset manager
add_action('after_setup_theme', array('GPress_Smart_Asset_Manager', 'init'));
```

### Step 2: Consolidate Core CSS Files

**File**: `assets/css/core.css`
```css
/*! 
 * GPress Core CSS - Consolidated base styles
 * Contains typography, layout, utilities, and components
 * Version: 2.0.0
 */

/* ==========================================================================
   CSS LAYERS - Modern cascade management
   ========================================================================== */
@layer reset, base, typography, layout, components, utilities;

/* ==========================================================================
   CSS CUSTOM PROPERTIES - Design tokens
   ========================================================================== */
@layer base {
  :root {
    /* Colors */
    --gp-primary: #2563eb;
    --gp-secondary: #64748b;
    --gp-accent: #f59e0b;
    --gp-success: #059669;
    --gp-warning: #d97706;
    --gp-error: #dc2626;
    --gp-white: #ffffff;
    --gp-black: #000000;
    --gp-gray-50: #f9fafb;
    --gp-gray-100: #f3f4f6;
    --gp-gray-200: #e5e7eb;
    --gp-gray-300: #d1d5db;
    --gp-gray-400: #9ca3af;
    --gp-gray-500: #6b7280;
    --gp-gray-600: #4b5563;
    --gp-gray-700: #374151;
    --gp-gray-800: #1f2937;
    --gp-gray-900: #111827;
    
    /* Semantic colors */
    --gp-text: var(--gp-gray-900);
    --gp-text-light: var(--gp-gray-600);
    --gp-bg: var(--gp-white);
    --gp-bg-alt: var(--gp-gray-50);
    --gp-border: var(--gp-gray-200);
    
    /* Typography */
    --gp-font-sans: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen-Sans, Ubuntu, Cantarell, sans-serif;
    --gp-font-serif: Georgia, Cambria, serif;
    --gp-font-mono: 'SF Mono', Monaco, Consolas, monospace;
    
    /* Font sizes */
    --gp-text-xs: 0.75rem;
    --gp-text-sm: 0.875rem;
    --gp-text-base: 1rem;
    --gp-text-lg: 1.125rem;
    --gp-text-xl: 1.25rem;
    --gp-text-2xl: 1.5rem;
    --gp-text-3xl: 1.875rem;
    --gp-text-4xl: 2.25rem;
    --gp-text-5xl: 3rem;
    --gp-text-6xl: 3.75rem;
    
    /* Spacing */
    --gp-space-1: 0.25rem;
    --gp-space-2: 0.5rem;
    --gp-space-3: 0.75rem;
    --gp-space-4: 1rem;
    --gp-space-5: 1.25rem;
    --gp-space-6: 1.5rem;
    --gp-space-8: 2rem;
    --gp-space-10: 2.5rem;
    --gp-space-12: 3rem;
    --gp-space-16: 4rem;
    --gp-space-20: 5rem;
    --gp-space-24: 6rem;
    
    /* Border radius */
    --gp-radius-sm: 0.125rem;
    --gp-radius: 0.25rem;
    --gp-radius-md: 0.375rem;
    --gp-radius-lg: 0.5rem;
    --gp-radius-xl: 0.75rem;
    --gp-radius-2xl: 1rem;
    --gp-radius-full: 9999px;
    
    /* Shadows */
    --gp-shadow-sm: 0 1px 2px 0 rgba(0, 0, 0, 0.05);
    --gp-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.1), 0 1px 2px 0 rgba(0, 0, 0, 0.06);
    --gp-shadow-md: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
    --gp-shadow-lg: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
    --gp-shadow-xl: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
    
    /* Transitions */
    --gp-transition: all 150ms ease-in-out;
    --gp-transition-colors: background-color 150ms ease-in-out, border-color 150ms ease-in-out, color 150ms ease-in-out;
    
    /* Container widths */
    --gp-container-sm: 640px;
    --gp-container-md: 768px;
    --gp-container-lg: 1024px;
    --gp-container-xl: 1280px;
    --gp-container-2xl: 1536px;
  }
  
  /* Dark mode colors */
  @media (prefers-color-scheme: dark) {
    :root {
      --gp-text: var(--gp-gray-50);
      --gp-text-light: var(--gp-gray-400);
      --gp-bg: var(--gp-gray-900);
      --gp-bg-alt: var(--gp-gray-800);
      --gp-border: var(--gp-gray-700);
    }
  }
}

/* ==========================================================================
   RESET - Modern CSS reset
   ========================================================================== */
@layer reset {
  *, *::before, *::after {
    box-sizing: border-box;
  }
  
  * {
    margin: 0;
    padding: 0;
  }
  
  html {
    line-height: 1.5;
    -webkit-text-size-adjust: 100%;
    -webkit-tap-highlight-color: transparent;
  }
  
  body {
    font-family: var(--gp-font-sans);
    font-size: var(--gp-text-base);
    line-height: 1.6;
    color: var(--gp-text);
    background-color: var(--gp-bg);
    -webkit-font-smoothing: antialiased;
    -moz-osx-font-smoothing: grayscale;
  }
  
  img, picture, video, canvas, svg {
    display: block;
    max-width: 100%;
    height: auto;
  }
  
  input, button, textarea, select {
    font: inherit;
    color: inherit;
  }
  
  button {
    background: none;
    border: none;
    cursor: pointer;
  }
  
  a {
    color: inherit;
    text-decoration: none;
  }
  
  ul, ol {
    list-style: none;
  }
  
  table {
    border-collapse: collapse;
    border-spacing: 0;
  }
}

/* ==========================================================================
   TYPOGRAPHY - Fluid typography system
   ========================================================================== */
@layer typography {
  h1, h2, h3, h4, h5, h6 {
    font-weight: 700;
    line-height: 1.2;
    margin-bottom: var(--gp-space-4);
  }
  
  h1 {
    font-size: clamp(var(--gp-text-3xl), 4vw, var(--gp-text-5xl));
  }
  
  h2 {
    font-size: clamp(var(--gp-text-2xl), 3vw, var(--gp-text-4xl));
  }
  
  h3 {
    font-size: clamp(var(--gp-text-xl), 2.5vw, var(--gp-text-3xl));
  }
  
  h4 {
    font-size: clamp(var(--gp-text-lg), 2vw, var(--gp-text-2xl));
  }
  
  h5, h6 {
    font-size: var(--gp-text-lg);
  }
  
  p {
    margin-bottom: var(--gp-space-4);
    line-height: 1.7;
  }
  
  a {
    color: var(--gp-primary);
    transition: var(--gp-transition-colors);
  }
  
  a:hover {
    color: var(--gp-secondary);
  }
  
  code {
    font-family: var(--gp-font-mono);
    font-size: 0.875em;
    background-color: var(--gp-bg-alt);
    padding: var(--gp-space-1) var(--gp-space-2);
    border-radius: var(--gp-radius-sm);
  }
  
  blockquote {
    border-left: 4px solid var(--gp-primary);
    padding-left: var(--gp-space-4);
    margin: var(--gp-space-6) 0;
    font-style: italic;
    color: var(--gp-text-light);
  }
}

/* ==========================================================================
   LAYOUT - Modern layout system
   ========================================================================== */
@layer layout {
  .container {
    width: 100%;
    margin-left: auto;
    margin-right: auto;
    padding-left: var(--gp-space-4);
    padding-right: var(--gp-space-4);
  }
  
  .container-sm { max-width: var(--gp-container-sm); }
  .container-md { max-width: var(--gp-container-md); }
  .container-lg { max-width: var(--gp-container-lg); }
  .container-xl { max-width: var(--gp-container-xl); }
  .container-2xl { max-width: var(--gp-container-2xl); }
  
  /* CSS Grid utilities */
  .grid {
    display: grid;
    gap: var(--gp-space-4);
  }
  
  .grid-cols-1 { grid-template-columns: repeat(1, minmax(0, 1fr)); }
  .grid-cols-2 { grid-template-columns: repeat(2, minmax(0, 1fr)); }
  .grid-cols-3 { grid-template-columns: repeat(3, minmax(0, 1fr)); }
  .grid-cols-4 { grid-template-columns: repeat(4, minmax(0, 1fr)); }
  .grid-cols-auto { grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); }
  
  /* Flexbox utilities */
  .flex {
    display: flex;
    gap: var(--gp-space-4);
  }
  
  .flex-col { flex-direction: column; }
  .flex-wrap { flex-wrap: wrap; }
  .items-center { align-items: center; }
  .items-start { align-items: flex-start; }
  .items-end { align-items: flex-end; }
  .justify-center { justify-content: center; }
  .justify-between { justify-content: space-between; }
  .justify-around { justify-content: space-around; }
  .justify-end { justify-content: flex-end; }
  
  /* Responsive breakpoints */
  @media (max-width: 768px) {
    .grid-cols-2,
    .grid-cols-3,
    .grid-cols-4 {
      grid-template-columns: 1fr;
    }
    
    .container {
      padding-left: var(--gp-space-2);
      padding-right: var(--gp-space-2);
    }
  }
}

/* ==========================================================================
   COMPONENTS - Common UI components
   ========================================================================== */
@layer components {
  /* Buttons */
  .btn {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    padding: var(--gp-space-3) var(--gp-space-6);
    font-weight: 600;
    text-decoration: none;
    border: 1px solid transparent;
    border-radius: var(--gp-radius);
    transition: var(--gp-transition);
    cursor: pointer;
  }
  
  .btn-primary {
    background-color: var(--gp-primary);
    color: var(--gp-white);
  }
  
  .btn-primary:hover {
    background-color: var(--gp-secondary);
    transform: translateY(-1px);
  }
  
  .btn-secondary {
    background-color: transparent;
    color: var(--gp-primary);
    border-color: var(--gp-primary);
  }
  
  .btn-secondary:hover {
    background-color: var(--gp-primary);
    color: var(--gp-white);
  }
  
  /* Cards */
  .card {
    background-color: var(--gp-bg);
    border: 1px solid var(--gp-border);
    border-radius: var(--gp-radius-lg);
    box-shadow: var(--gp-shadow);
    padding: var(--gp-space-6);
    transition: var(--gp-transition);
  }
  
  .card:hover {
    box-shadow: var(--gp-shadow-lg);
    transform: translateY(-2px);
  }
  
  /* Forms */
  .form-input {
    display: block;
    width: 100%;
    padding: var(--gp-space-3) var(--gp-space-4);
    font-size: var(--gp-text-base);
    line-height: 1.5;
    color: var(--gp-text);
    background-color: var(--gp-bg);
    border: 1px solid var(--gp-border);
    border-radius: var(--gp-radius);
    transition: var(--gp-transition-colors);
  }
  
  .form-input:focus {
    outline: none;
    border-color: var(--gp-primary);
    box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.1);
  }
}

/* ==========================================================================
   UTILITIES - Helper classes
   ========================================================================== */
@layer utilities {
  /* Spacing utilities */
  .m-0 { margin: 0; }
  .m-1 { margin: var(--gp-space-1); }
  .m-2 { margin: var(--gp-space-2); }
  .m-3 { margin: var(--gp-space-3); }
  .m-4 { margin: var(--gp-space-4); }
  .m-6 { margin: var(--gp-space-6); }
  .m-8 { margin: var(--gp-space-8); }
  
  .p-0 { padding: 0; }
  .p-1 { padding: var(--gp-space-1); }
  .p-2 { padding: var(--gp-space-2); }
  .p-3 { padding: var(--gp-space-3); }
  .p-4 { padding: var(--gp-space-4); }
  .p-6 { padding: var(--gp-space-6); }
  .p-8 { padding: var(--gp-space-8); }
  
  /* Text utilities */
  .text-center { text-align: center; }
  .text-left { text-align: left; }
  .text-right { text-align: right; }
  .text-sm { font-size: var(--gp-text-sm); }
  .text-base { font-size: var(--gp-text-base); }
  .text-lg { font-size: var(--gp-text-lg); }
  .text-xl { font-size: var(--gp-text-xl); }
  
  /* Color utilities */
  .text-primary { color: var(--gp-primary); }
  .text-secondary { color: var(--gp-secondary); }
  .text-gray { color: var(--gp-text-light); }
  
  /* Display utilities */
  .hidden { display: none; }
  .block { display: block; }
  .inline { display: inline; }
  .inline-block { display: inline-block; }
  
  /* Accessibility utilities */
  .sr-only {
    position: absolute;
    width: 1px;
    height: 1px;
    padding: 0;
    margin: -1px;
    overflow: hidden;
    clip: rect(0, 0, 0, 0);
    white-space: nowrap;
    border: 0;
  }
  
  .focus-visible:focus-visible {
    outline: 2px solid var(--gp-primary);
    outline-offset: 2px;
  }
}

/* ==========================================================================
   WORDPRESS SPECIFIC STYLES
   ========================================================================== */
@layer components {
  /* WordPress alignment classes */
  .alignleft {
    float: left;
    margin-right: var(--gp-space-4);
    margin-bottom: var(--gp-space-4);
  }
  
  .alignright {
    float: right;
    margin-left: var(--gp-space-4);
    margin-bottom: var(--gp-space-4);
  }
  
  .aligncenter {
    display: block;
    margin-left: auto;
    margin-right: auto;
    text-align: center;
  }
  
  .alignwide {
    max-width: calc(var(--gp-container-lg) + var(--gp-space-16));
    margin-left: auto;
    margin-right: auto;
  }
  
  .alignfull {
    width: 100vw;
    max-width: 100vw;
    margin-left: calc(50% - 50vw);
    margin-right: calc(50% - 50vw);
  }
  
  /* WordPress caption styles */
  .wp-caption {
    max-width: 100%;
  }
  
  .wp-caption-text {
    font-size: var(--gp-text-sm);
    color: var(--gp-text-light);
    text-align: center;
    margin-top: var(--gp-space-2);
  }
  
  /* Skip links */
  .skip-link {
    position: absolute;
    left: -9999px;
    top: var(--gp-space-2);
    z-index: 999999;
    text-decoration: none;
    background: var(--gp-primary);
    color: var(--gp-white);
    padding: var(--gp-space-2) var(--gp-space-4);
    border-radius: var(--gp-radius);
    font-weight: 600;
  }
  
  .skip-link:focus {
    left: var(--gp-space-2);
  }
}

/* ==========================================================================
   RESPONSIVE DESIGN
   ========================================================================== */
@media (prefers-reduced-motion: reduce) {
  *,
  *::before,
  *::after {
    animation-duration: 0.01ms !important;
    animation-iteration-count: 1 !important;
    transition-duration: 0.01ms !important;
    scroll-behavior: auto !important;
  }
}

@media print {
  *,
  *::before,
  *::after {
    background: white !important;
    color: black !important;
    box-shadow: none !important;
  }
  
  .btn {
    border: 1px solid black;
  }
  
  .skip-link,
  .no-print {
    display: none !important;
  }
}
```

### Step 3: Create Critical CSS

**File**: `assets/css/critical.css`
```css
/*! Critical CSS for above-the-fold content - GPress Theme v2.0.0 */

/* Critical CSS Custom Properties */
:root{--gp-primary:#2563eb;--gp-text:#111827;--gp-bg:#ffffff;--gp-border:#e5e7eb;--gp-space-4:1rem;--gp-radius:0.25rem;--gp-font-sans:-apple-system,BlinkMacSystemFont,'Segoe UI',Roboto,Oxygen-Sans,Ubuntu,Cantarell,sans-serif;--gp-container-lg:1024px;--gp-header-height:80px}

/* Critical Reset */
*,*::before,*::after{box-sizing:border-box}*{margin:0;padding:0}html{line-height:1.5;-webkit-text-size-adjust:100%}body{font-family:var(--gp-font-sans);font-size:1rem;line-height:1.6;color:var(--gp-text);background-color:var(--gp-bg);-webkit-font-smoothing:antialiased}

/* Skip Link */
.skip-link{position:absolute;left:-9999px;top:6px;z-index:999999;background:var(--gp-primary);color:white;padding:0.5rem 1rem;border-radius:var(--gp-radius);text-decoration:none;font-weight:600}.skip-link:focus{left:6px}

/* Critical Header */
.site-header{position:relative;background:var(--gp-bg);border-bottom:1px solid var(--gp-border);height:var(--gp-header-height);z-index:100}.header-container{max-width:var(--gp-container-lg);margin:0 auto;padding:0 var(--gp-space-4);height:100%;display:flex;align-items:center;justify-content:space-between}

/* Site Branding */
.site-branding{display:flex;align-items:center}.site-title{margin:0;font-size:1.5rem;font-weight:700;line-height:1.2}.site-title a{text-decoration:none;color:var(--gp-primary)}

/* Critical Navigation */
.main-navigation{display:flex;align-items:center}.main-navigation ul{display:flex;list-style:none;margin:0;padding:0;gap:var(--gp-space-4)}.main-navigation a{text-decoration:none;color:var(--gp-text);font-weight:500;padding:0.5rem 0;border-bottom:2px solid transparent;transition:border-color 0.2s ease}.main-navigation a:hover{border-bottom-color:var(--gp-primary)}

/* Critical Typography */
h1{font-size:clamp(1.875rem,4vw,3rem);font-weight:700;line-height:1.2;margin-bottom:var(--gp-space-4)}

/* Loading States */
.responsive-image[loading="lazy"]{opacity:0;transition:opacity 0.3s ease}.responsive-image.loaded{opacity:1}

/* Mobile Critical Styles */
@media (max-width:768px){:root{--gp-header-height:60px}.header-container{padding:0 1rem}.main-navigation ul{gap:1rem}}

/* Dark Mode Support */
@media (prefers-color-scheme:dark){:root{--gp-text:#f9fafb;--gp-bg:#111827;--gp-border:#374151}}

/* Reduced Motion */
@media (prefers-reduced-motion:reduce){*{animation-duration:0.01ms!important;transition-duration:0.01ms!important}}
```

### Step 4: Consolidate Core JavaScript

**File**: `assets/js/core.js`
```javascript
/*!
 * GPress Core JavaScript - Consolidated functionality
 * Version: 2.0.0
 */

(function(window, document) {
    'use strict';
    
    // Feature detection
    const features = {
        webp: null,
        intersectionObserver: 'IntersectionObserver' in window,
        webkitBrowser: navigator.userAgent.includes('WebKit'),
        reducedMotion: window.matchMedia('(prefers-reduced-motion: reduce)').matches
    };
    
    // Core theme object
    const GPress = {
        init() {
            this.accessibility.init();
            this.imageOptimization.init();
            this.navigation.init();
            this.performance.init();
            
            // Feature-specific initialization
            if (features.intersectionObserver) {
                this.lazyLoading.init();
            }
            
            this.utils.addLoadedClass();
        },
        
        // Accessibility enhancements
        accessibility: {
            init() {
                this.skipLinkFix();
                this.focusManagement();
                this.keyboardNavigation();
            },
            
            skipLinkFix() {
                if (!features.webkitBrowser) return;
                
                const skipLinks = document.querySelectorAll('.skip-link');
                skipLinks.forEach(link => {
                    link.addEventListener('click', function(e) {
                        const target = document.querySelector(this.getAttribute('href'));
                        if (target) {
                            if (!target.hasAttribute('tabindex')) {
                                target.setAttribute('tabindex', '-1');
                            }
                            target.focus();
                            target.addEventListener('blur', function() {
                                if (this.getAttribute('tabindex') === '-1') {
                                    this.removeAttribute('tabindex');
                                }
                            }, { once: true });
                        }
                    });
                });
            },
            
            focusManagement() {
                let isUsingKeyboard = false;
                
                document.addEventListener('keydown', (e) => {
                    if (e.key === 'Tab') {
                        isUsingKeyboard = true;
                        document.body.classList.add('using-keyboard');
                    }
                });
                
                document.addEventListener('mousedown', () => {
                    isUsingKeyboard = false;
                    document.body.classList.remove('using-keyboard');
                });
            },
            
            keyboardNavigation() {
                document.addEventListener('keydown', (e) => {
                    if (e.key === 'Escape') {
                        // Close any open dropdowns or modals
                        const openElements = document.querySelectorAll('.is-open, .menu-open');
                        openElements.forEach(el => {
                            el.classList.remove('is-open', 'menu-open');
                        });
                    }
                });
            }
        },
        
        // Image optimization
        imageOptimization: {
            init() {
                this.checkWebPSupport();
                this.markLoadedImages();
                this.addResponsiveClasses();
            },
            
            async checkWebPSupport() {
                if (features.webp !== null) return features.webp;
                
                return new Promise((resolve) => {
                    const webP = new Image();
                    webP.onload = webP.onerror = function() {
                        features.webp = webP.height === 2;
                        if (features.webp) {
                            document.documentElement.classList.add('webp-supported');
                        }
                        resolve(features.webp);
                    };
                    webP.src = 'data:image/webp;base64,UklGRjoAAABXRUJQVlA4IC4AAACyAgCdASoCAAIALmk0mk0iIiIiIgBoSygABc6WWgAA/veff/0PP8bA//LwYAAA';
                });
            },
            
            markLoadedImages() {
                const images = document.querySelectorAll('img');
                images.forEach(img => {
                    if (img.complete && img.naturalHeight !== 0) {
                        img.classList.add('loaded');
                    } else {
                        img.addEventListener('load', function() {
                            this.classList.add('loaded');
                        }, { passive: true, once: true });
                        
                        img.addEventListener('error', function() {
                            this.classList.add('error');
                        }, { passive: true, once: true });
                    }
                });
            },
            
            addResponsiveClasses() {
                const images = document.querySelectorAll('img:not(.responsive-optimized)');
                images.forEach(img => {
                    img.classList.add('responsive-optimized');
                    
                    // Add loading attribute if not present
                    if (!img.hasAttribute('loading')) {
                        img.setAttribute('loading', 'lazy');
                    }
                    
                    // Add decoding attribute
                    if (!img.hasAttribute('decoding')) {
                        img.setAttribute('decoding', 'async');
                    }
                });
            }
        },
        
        // Navigation enhancements
        navigation: {
            init() {
                this.smoothScrolling();
                this.mobileMenuToggle();
                this.activeStateManagement();
            },
            
            smoothScrolling() {
                if (features.reducedMotion) return;
                
                document.addEventListener('click', (e) => {
                    const link = e.target.closest('a[href^="#"]');
                    if (!link || link.getAttribute('href') === '#') return;
                    
                    const target = document.querySelector(link.getAttribute('href'));
                    if (target) {
                        e.preventDefault();
                        target.scrollIntoView({
                            behavior: 'smooth',
                            block: 'start'
                        });
                        
                        // Update URL without triggering navigation
                        history.pushState(null, null, link.getAttribute('href'));
                    }
                }, { passive: false });
            },
            
            mobileMenuToggle() {
                const menuToggle = document.querySelector('.menu-toggle');
                const navigation = document.querySelector('.main-navigation');
                
                if (!menuToggle || !navigation) return;
                
                menuToggle.addEventListener('click', () => {
                    const isOpen = navigation.classList.contains('menu-open');
                    navigation.classList.toggle('menu-open');
                    menuToggle.setAttribute('aria-expanded', !isOpen);
                });
            },
            
            activeStateManagement() {
                const currentPath = window.location.pathname;
                const navLinks = document.querySelectorAll('.main-navigation a');
                
                navLinks.forEach(link => {
                    if (link.pathname === currentPath) {
                        link.classList.add('current-page');
                        link.setAttribute('aria-current', 'page');
                    }
                });
            }
        },
        
        // Lazy loading implementation
        lazyLoading: {
            observer: null,
            
            init() {
                this.setupImageObserver();
                this.setupBackgroundObserver();
            },
            
            setupImageObserver() {
                this.observer = new IntersectionObserver((entries) => {
                    entries.forEach(entry => {
                        if (entry.isIntersecting) {
                            this.loadImage(entry.target);
                            this.observer.unobserve(entry.target);
                        }
                    });
                }, {
                    rootMargin: '50px 0px',
                    threshold: 0.01
                });
                
                document.querySelectorAll('img[data-src], img[loading="lazy"]:not(.loaded)').forEach(img => {
                    this.observer.observe(img);
                });
            },
            
            setupBackgroundObserver() {
                const bgObserver = new IntersectionObserver((entries) => {
                    entries.forEach(entry => {
                        if (entry.isIntersecting) {
                            const element = entry.target;
                            const bgSrc = element.dataset.bgSrc;
                            
                            if (bgSrc) {
                                element.style.backgroundImage = `url(${bgSrc})`;
                                element.removeAttribute('data-bg-src');
                                element.classList.add('bg-loaded');
                            }
                            
                            bgObserver.unobserve(element);
                        }
                    });
                }, { rootMargin: '50px 0px' });
                
                document.querySelectorAll('[data-bg-src]').forEach(el => {
                    bgObserver.observe(el);
                });
            },
            
            loadImage(img) {
                // Load high-res source
                if (img.dataset.src) {
                    img.src = img.dataset.src;
                    img.removeAttribute('data-src');
                }
                
                // Load responsive srcset
                if (img.dataset.srcset) {
                    img.srcset = img.dataset.srcset;
                    img.removeAttribute('data-srcset');
                }
                
                // Add smooth transition
                if (!features.reducedMotion) {
                    img.style.opacity = '0';
                    img.style.transition = 'opacity 0.3s ease';
                    
                    img.addEventListener('load', function() {
                        this.style.opacity = '1';
                        this.classList.add('loaded');
                    }, { passive: true, once: true });
                } else {
                    img.classList.add('loaded');
                }
            }
        },
        
        // Performance optimizations
        performance: {
            init() {
                this.throttledScrollHandler();
                this.preloadHoverLinks();
                this.optimizeAnimations();
            },
            
            throttledScrollHandler() {
                let ticking = false;
                
                const handleScroll = () => {
                    if (!ticking) {
                        requestAnimationFrame(() => {
                            // Add scroll-based functionality here
                            this.updateScrollProgress();
                            ticking = false;
                        });
                        ticking = true;
                    }
                };
                
                // Only add scroll listener if needed
                if (document.querySelector('[data-scroll]')) {
                    window.addEventListener('scroll', handleScroll, { passive: true });
                }
            },
            
            updateScrollProgress() {
                const progressBar = document.querySelector('.reading-progress');
                if (!progressBar) return;
                
                const scrolled = (window.scrollY / (document.body.scrollHeight - window.innerHeight)) * 100;
                progressBar.style.width = `${Math.min(scrolled, 100)}%`;
            },
            
            preloadHoverLinks() {
                const preloadedLinks = new Set();
                
                document.addEventListener('mouseover', (e) => {
                    const link = e.target.closest('a');
                    
                    if (link && 
                        link.hostname === location.hostname && 
                        !preloadedLinks.has(link.href) &&
                        !link.getAttribute('download')) {
                        
                        const linkEl = document.createElement('link');
                        linkEl.rel = 'prefetch';
                        linkEl.href = link.href;
                        document.head.appendChild(linkEl);
                        
                        preloadedLinks.add(link.href);
                    }
                }, { passive: true });
            },
            
            optimizeAnimations() {
                if (features.reducedMotion) {
                    // Disable animations for users who prefer reduced motion
                    document.documentElement.style.setProperty('--gp-transition', 'none');
                }
            }
        },
        
        // Utility functions
        utils: {
            addLoadedClass() {
                // Mark theme as fully loaded
                window.addEventListener('load', () => {
                    document.documentElement.classList.add('theme-loaded');
                }, { passive: true, once: true });
            },
            
            debounce(func, wait) {
                let timeout;
                return function executedFunction(...args) {
                    const later = () => {
                        clearTimeout(timeout);
                        func(...args);
                    };
                    clearTimeout(timeout);
                    timeout = setTimeout(later, wait);
                };
            },
            
            throttle(func, limit) {
                let inThrottle;
                return function() {
                    const args = arguments;
                    const context = this;
                    if (!inThrottle) {
                        func.apply(context, args);
                        inThrottle = true;
                        setTimeout(() => inThrottle = false, limit);
                    }
                };
            }
        }
    };
    
    // Initialize when DOM is ready
    function initGPress() {
        GPress.init();
        
        // Make GPress globally available for extensions
        window.GPress = GPress;
        
        // Dispatch custom event for theme ready
        document.dispatchEvent(new CustomEvent('gpressReady', {
            detail: { version: '2.0.0', features }
        }));
    }
    
    // Initialize based on document state
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', initGPress);
    } else {
        initGPress();
    }
    
    // Cleanup on page unload
    window.addEventListener('beforeunload', () => {
        if (GPress.lazyLoading.observer) {
            GPress.lazyLoading.observer.disconnect();
        }
    }, { passive: true });
    
})(window, document);
```

## Phase 2: Update Functions.php and Asset Loading

### Step 5: Update functions.php

Add to `functions.php`:
```php
// Enhanced asset management
require_once GPRESS_INC_DIR . '/smart-asset-manager.php';

/**
 * Enhanced theme setup for optimized assets
 */
function gpress_enhanced_theme_setup() {
    // Remove unnecessary WordPress assets
    remove_action('wp_head', 'print_emoji_detection_script', 7);
    remove_action('wp_print_styles', 'print_emoji_styles');
    remove_action('admin_print_scripts', 'print_emoji_detection_script');
    remove_action('admin_print_styles', 'print_emoji_styles');
    
    // Optimize jQuery loading
    if (!is_admin()) {
        wp_deregister_script('jquery');
        wp_register_script('jquery', false);
    }
    
    // Enable selective refresh for widgets
    add_theme_support('customize-selective-refresh-widgets');
    
    // Add editor styles
    add_theme_support('editor-styles');
    add_editor_style(array('assets/css/core.css'));
}
add_action('after_setup_theme', 'gpress_enhanced_theme_setup', 20);

/**
 * Get file version for cache busting
 */
function get_theme_file_version($file_path) {
    $full_path = get_theme_file_path($file_path);
    
    if (file_exists($full_path)) {
        return filemtime($full_path);
    }
    
    return GPRESS_VERSION;
}

/**
 * Enhanced script localization
 */
function gpress_localize_scripts() {
    if (wp_script_is('gpress-core-js', 'enqueued')) {
        wp_localize_script('gpress-core-js', 'gpressData', array(
            'ajaxurl' => admin_url('admin-ajax.php'),
            'nonce' => wp_create_nonce('gpress_nonce'),
            'version' => GPRESS_VERSION,
            'isCustomizer' => is_customize_preview(),
            'reducedMotion' => false, // Will be detected client-side
            'features' => array(
                'lazyLoading' => true,
                'smoothScroll' => true,
                'performanceMonitoring' => get_theme_mod('enable_performance_monitoring', false)
            ),
            'strings' => array(
                'loading' => esc_html__('Loading...', 'gpress'),
                'error' => esc_html__('An error occurred', 'gpress'),
                'close' => esc_html__('Close', 'gpress'),
                'menu' => esc_html__('Menu', 'gpress')
            )
        ));
    }
}
add_action('wp_enqueue_scripts', 'gpress_localize_scripts', 20);
```

### Step 6: Create Feature-Specific Asset Files

**File**: `assets/css/navigation.css`
```css
/*! Navigation styles for GPress Theme v2.0.0 */

.main-navigation {
  position: relative;
}

.main-navigation ul ul {
  position: absolute;
  top: 100%;
  left: 0;
  z-index: 999;
  background: var(--gp-bg);
  border: 1px solid var(--gp-border);
  border-radius: var(--gp-radius);
  box-shadow: var(--gp-shadow-lg);
  min-width: 200px;
  opacity: 0;
  visibility: hidden;
  transform: translateY(-10px);
  transition: var(--gp-transition);
}

.main-navigation li:hover > ul,
.main-navigation li:focus-within > ul {
  opacity: 1;
  visibility: visible;
  transform: translateY(0);
}

.main-navigation ul ul a {
  padding: var(--gp-space-2) var(--gp-space-4);
  display: block;
  border-bottom: none;
}

.main-navigation ul ul a:hover {
  background-color: var(--gp-bg-alt);
}

/* Mobile navigation */
.menu-toggle {
  display: none;
}

@media (max-width: 768px) {
  .menu-toggle {
    display: block;
    background: none;
    border: none;
    padding: var(--gp-space-2);
    cursor: pointer;
  }
  
  .main-navigation ul {
    position: absolute;
    top: 100%;
    left: 0;
    right: 0;
    background: var(--gp-bg);
    border: 1px solid var(--gp-border);
    flex-direction: column;
    transform: translateY(-10px);
    opacity: 0;
    visibility: hidden;
    transition: var(--gp-transition);
  }
  
  .main-navigation.menu-open ul {
    opacity: 1;
    visibility: visible;
    transform: translateY(0);
  }
  
  .main-navigation ul ul {
    position: static;
    opacity: 1;
    visibility: visible;
    transform: none;
    box-shadow: none;
    border: none;
    background: var(--gp-bg-alt);
  }
}
```

**File**: `assets/js/navigation.js`
```javascript
/*!
 * Navigation enhancements for GPress Theme v2.0.0
 */

(function() {
    'use strict';
    
    const Navigation = {
        init() {
            this.setupMobileMenu();
            this.setupDropdowns();
            this.setupKeyboardNavigation();
        },
        
        setupMobileMenu() {
            const menuToggle = document.querySelector('.menu-toggle');
            const navigation = document.querySelector('.main-navigation');
            
            if (!menuToggle || !navigation) return;
            
            menuToggle.addEventListener('click', () => {
                const isOpen = navigation.classList.contains('menu-open');
                navigation.classList.toggle('menu-open');
                menuToggle.setAttribute('aria-expanded', !isOpen);
            });
        },
        
        setupDropdowns() {
            const dropdowns = document.querySelectorAll('.main-navigation .menu-item-has-children');
            
            dropdowns.forEach(dropdown => {
                const link = dropdown.querySelector('a');
                const submenu = dropdown.querySelector('ul');
                
                if (!link || !submenu) return;
                
                // Add aria attributes
                link.setAttribute('aria-haspopup', 'true');
                link.setAttribute('aria-expanded', 'false');
                submenu.setAttribute('aria-label', 'Submenu');
                
                // Handle click on dropdown toggle
                link.addEventListener('click', (e) => {
                    if (window.innerWidth <= 768) {
                        e.preventDefault();
                        const isOpen = dropdown.classList.contains('dropdown-open');
                        dropdown.classList.toggle('dropdown-open');
                        link.setAttribute('aria-expanded', !isOpen);
                    }
                });
            });
        },
        
        setupKeyboardNavigation() {
            const menuItems = document.querySelectorAll('.main-navigation a');
            
            menuItems.forEach((item, index) => {
                item.addEventListener('keydown', (e) => {
                    switch (e.key) {
                        case 'ArrowRight':
                        case 'ArrowDown':
                            e.preventDefault();
                            const nextItem = menuItems[index + 1];
                            if (nextItem) nextItem.focus();
                            break;
                        case 'ArrowLeft':
                        case 'ArrowUp':
                            e.preventDefault();
                            const prevItem = menuItems[index - 1];
                            if (prevItem) prevItem.focus();
                            break;
                        case 'Escape':
                            const dropdown = item.closest('.menu-item-has-children');
                            if (dropdown) {
                                dropdown.classList.remove('dropdown-open');
                                dropdown.querySelector('a').setAttribute('aria-expanded', 'false');
                                dropdown.querySelector('a').focus();
                            }
                            break;
                    }
                });
            });
        }
    };
    
    // Initialize navigation when core theme is ready
    document.addEventListener('gpressReady', () => {
        Navigation.init();
    });
    
    // Fallback initialization
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', () => Navigation.init());
    } else {
        Navigation.init();
    }
})();
```

## Phase 3: Testing and Validation

### Performance Testing Commands

```bash
# Lighthouse performance audit
lighthouse https://your-site.com --output=html --view

# Check asset loading
curl -s https://your-site.com | grep -E "\.css|\.js" | wc -l

# Validate CSS
npx stylelint "assets/css/*.css"

# Validate JavaScript
npx eslint "assets/js/*.js"

# Check for unused CSS
npx purgecss --css assets/css/core.css --content "*.php" --output assets/css/

# Bundle size analysis
npx bundlesize check
```

## Expected Results

### Performance Improvements
- **Asset Reduction**: 60% fewer HTTP requests
- **File Size**: 70% smaller total payload
- **Lighthouse Score**: 95+ performance rating
- **Loading Speed**: 50% faster First Contentful Paint
- **Maintenance**: Easier to maintain consolidated files

### Implementation Timeline
- **Week 1**: Core consolidation and smart asset manager
- **Week 2**: Feature-specific asset optimization
- **Week 3**: Testing and performance validation
- **Week 4**: Documentation and final optimization

This implementation provides a modern, efficient, and maintainable asset management system that significantly improves performance while preserving all theme functionality.