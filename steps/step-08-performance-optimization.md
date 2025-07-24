# Step 8: Performance Optimization

## Objective
Implement comprehensive performance optimization strategies for the **GPress** theme to achieve 95+ Lighthouse scores and excellent Core Web Vitals metrics, ensuring fast loading times and optimal user experience. Focus on conditional loading to ensure performance optimizations only activate when needed.

## What You'll Learn
- Core Web Vitals optimization with conditional loading
- Resource loading strategies
- Image optimization techniques  
- JavaScript performance optimization
- Caching implementations with smart detection
- Database query optimization
- Service Worker implementation

## Files to Create in This Step

```
assets/js/
├── performance.js           # Performance optimization script
├── performance.min.js       # Minified performance script
├── service-worker.js        # Service worker for caching
└── web-vitals.js           # Core Web Vitals monitoring

inc/
├── performance.php          # Performance optimization functions
├── image-optimization.php   # Image optimization and WebP support
├── caching.php             # Advanced caching implementation
└── database-optimization.php # Database query optimization

build/
└── performance-config.js    # Performance build configuration

sw.js                        # Service worker registration file
```

## Core Web Vitals Target Metrics

### Performance Goals
- **Largest Contentful Paint (LCP)**: < 2.5 seconds
- **First Input Delay (FID)**: < 100 milliseconds  
- **Cumulative Layout Shift (CLS)**: < 0.1
- **First Contentful Paint (FCP)**: < 1.8 seconds
- **Time to Interactive (TTI)**: < 3.8 seconds

## 1. Create Performance Optimization Functions

### File: `inc/performance.php`
```php
<?php
/**
 * Performance Optimization Functions for GPress Theme
 *
 * @package GPress
 * @version 1.0.0
 */

// Prevent direct access
defined('ABSPATH') || exit;

/**
 * Initialize Performance Optimizations
 */
function gpress_init_performance_optimizations() {
    // Only run performance optimizations on frontend
    if (!is_admin() && !is_customize_preview()) {
        gpress_optimize_resource_loading();
        gpress_implement_critical_css();
        gpress_optimize_javascript_loading();
        gpress_add_performance_monitoring();
    }
    
    // Always run these optimizations
    gpress_remove_bloat();
    gpress_optimize_heartbeat();
}
add_action('init', 'gpress_init_performance_optimizations');

/**
 * Conditional Performance Asset Loading
 * Load performance scripts only when needed
 */
function gpress_conditional_performance_assets() {
    $load_performance_js = false;
    
    // Load performance scripts on pages with dynamic content
    if (is_single() || is_page() || is_home() || is_category() || is_tag()) {
        $load_performance_js = true;
    }
    
    // Check for performance-heavy features
    if (has_block('core/gallery') || has_block('core/image') || 
        has_block('core/video') || has_block('core/embed')) {
        $load_performance_js = true;
    }
    
    if ($load_performance_js) {
        wp_enqueue_script(
            'gpress-performance',
            GPRESS_THEME_URI . '/assets/js/performance.min.js',
            array(),
            GPRESS_VERSION,
            array(
                'strategy' => 'defer',
                'in_footer' => true
            )
        );
        
        // Localize performance script
        wp_localize_script('gpress-performance', 'gpressPerformance', array(
            'ajaxurl' => admin_url('admin-ajax.php'),
            'nonce' => wp_create_nonce('gpress_performance_nonce'),
            'enableLazyLoad' => get_theme_mod('enable_lazy_loading', true),
            'enableWebVitals' => get_theme_mod('enable_web_vitals', true),
            'enableServiceWorker' => get_theme_mod('enable_service_worker', false)
        ));
    }
    
    // Load Web Vitals monitoring on production sites
    if (!WP_DEBUG && get_theme_mod('enable_web_vitals_monitoring', false)) {
        wp_enqueue_script(
            'gpress-web-vitals',
            GPRESS_THEME_URI . '/assets/js/web-vitals.js',
            array(),
            GPRESS_VERSION,
            array(
                'strategy' => 'defer',
                'in_footer' => true
            )
        );
    }
}
add_action('wp_enqueue_scripts', 'gpress_conditional_performance_assets');

/**
 * Optimize Resource Loading
 */
function gpress_optimize_resource_loading() {
    // Preload critical resources
    function gpress_preload_critical_resources() {
        // Preload main stylesheet
        echo '<link rel="preload" href="' . get_stylesheet_uri() . '" as="style" onload="this.onload=null;this.rel=\'stylesheet\'">' . "\n";
        
        // Preload critical fonts
        $google_fonts = get_theme_mod('google_fonts_enable', false);
        if ($google_fonts) {
            echo '<link rel="preconnect" href="https://fonts.googleapis.com">' . "\n";
            echo '<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>' . "\n";
        }
        
        // Preload hero image on front page
        if (is_front_page()) {
            $hero_image = get_theme_mod('hero_image');
            if ($hero_image) {
                echo '<link rel="preload" href="' . esc_url($hero_image) . '" as="image" fetchpriority="high">' . "\n";
            }
        }
        
        // Preload featured image on single posts
        if (is_singular('post') && has_post_thumbnail()) {
            $featured_image = wp_get_attachment_image_src(get_post_thumbnail_id(), 'large');
            if ($featured_image) {
                echo '<link rel="preload" href="' . esc_url($featured_image[0]) . '" as="image" fetchpriority="high">' . "\n";
            }
        }
    }
    add_action('wp_head', 'gpress_preload_critical_resources', 1);
    
    // Add resource hints
    function gpress_add_resource_hints($urls, $relation_type) {
        switch ($relation_type) {
            case 'dns-prefetch':
                $urls[] = '//fonts.googleapis.com';
                $urls[] = '//fonts.gstatic.com';
                $urls[] = '//www.google-analytics.com';
                $urls[] = '//www.googletagmanager.com';
                break;
                
            case 'preconnect':
                $urls[] = array(
                    'href' => '//fonts.gstatic.com',
                    'crossorigin' => 'anonymous'
                );
                break;
        }
        
        return $urls;
    }
    add_filter('wp_resource_hints', 'gpress_add_resource_hints', 10, 2);
}

/**
 * Implement Critical CSS
 */
function gpress_implement_critical_css() {
    function gpress_inline_critical_css() {
        $critical_css_file = GPRESS_THEME_DIR . '/assets/css/critical.css';
        
        if (file_exists($critical_css_file)) {
            $critical_css = file_get_contents($critical_css_file);
            $critical_css = gpress_minify_css($critical_css);
            
            echo '<style id="gpress-critical-css">' . $critical_css . '</style>' . "\n";
        }
    }
    add_action('wp_head', 'gpress_inline_critical_css', 1);
    
    // Defer non-critical CSS
    function gpress_defer_non_critical_css($tag, $handle, $href, $media) {
        $defer_handles = array(
            'gpress-components',
            'gpress-responsive', 
            'wp-block-library'
        );
        
        if (in_array($handle, $defer_handles)) {
            return '<link rel="preload" href="' . $href . '" as="style" onload="this.onload=null;this.rel=\'stylesheet\'" id="' . $handle . '-css">' . "\n" .
                   '<noscript>' . $tag . '</noscript>' . "\n";
        }
        
        return $tag;
    }
    add_filter('style_loader_tag', 'gpress_defer_non_critical_css', 10, 4);
}

/**
 * Optimize JavaScript Loading
 */
function gpress_optimize_javascript_loading() {
    // Add async/defer attributes to scripts
    function gpress_optimize_script_loading($tag, $handle, $src) {
        // Scripts to defer
        $defer_scripts = array(
            'gpress-performance',
            'gpress-web-vitals',
            'comment-reply'
        );
        
        // Scripts to load async
        $async_scripts = array(
            'google-analytics',
            'gtag'
        );
        
        if (in_array($handle, $defer_scripts)) {
            return str_replace('<script ', '<script defer ', $tag);
        }
        
        if (in_array($handle, $async_scripts)) {
            return str_replace('<script ', '<script async ', $tag);
        }
        
        return $tag;
    }
    add_filter('script_loader_tag', 'gpress_optimize_script_loading', 10, 3);
    
    // Remove query strings from static resources
    function gpress_remove_query_strings($src) {
        if (!is_admin()) {
            return preg_replace('/\?.*/', '', $src);
        }
        return $src;
    }
    add_filter('script_loader_src', 'gpress_remove_query_strings');
    add_filter('style_loader_src', 'gpress_remove_query_strings');
}

/**
 * Remove WordPress Bloat
 */
function gpress_remove_bloat() {
    // Disable WordPress emoji scripts
    remove_action('wp_head', 'print_emoji_detection_script', 7);
    remove_action('admin_print_scripts', 'print_emoji_detection_script');
    remove_action('wp_print_styles', 'print_emoji_styles');
    remove_action('admin_print_styles', 'print_emoji_styles');
    
    // Remove unnecessary WordPress head elements
    remove_action('wp_head', 'wp_shortlink_wp_head', 10, 0);
    remove_action('wp_head', 'rsd_link');
    remove_action('wp_head', 'wlwmanifest_link');
    remove_action('wp_head', 'wp_generator');
    
    // Disable XML-RPC
    add_filter('xmlrpc_enabled', '__return_false');
    
    // Remove version from CSS/JS
    function gpress_remove_version_strings($src) {
        if (strpos($src, 'ver=')) {
            $src = remove_query_arg('ver', $src);
        }
        return $src;
    }
    add_filter('style_loader_src', 'gpress_remove_version_strings', 9999);
    add_filter('script_loader_src', 'gpress_remove_version_strings', 9999);
}

/**
 * Optimize Heartbeat API
 */
function gpress_optimize_heartbeat() {
    // Optimize heartbeat settings
    function gpress_heartbeat_settings($settings) {
        $settings['interval'] = 60; // 60 seconds instead of 15
        return $settings;
    }
    add_filter('heartbeat_settings', 'gpress_heartbeat_settings');
    
    // Disable heartbeat on frontend
    function gpress_disable_frontend_heartbeat() {
        if (!is_admin()) {
            wp_deregister_script('heartbeat');
        }
    }
    add_action('init', 'gpress_disable_frontend_heartbeat');
}

/**
 * Add Performance Monitoring
 */
function gpress_add_performance_monitoring() {
    if (get_theme_mod('enable_performance_monitoring', false)) {
        function gpress_performance_monitoring_script() {
            ?>
            <script>
            // Basic performance monitoring
            window.addEventListener('load', function() {
                // Measure page load time
                const loadTime = performance.timing.loadEventEnd - performance.timing.navigationStart;
                
                // Send to analytics if available
                if (typeof gtag !== 'undefined') {
                    gtag('event', 'page_load_time', {
                        value: Math.round(loadTime),
                        event_category: 'Performance'
                    });
                }
                
                // Console log for debugging
                if (<?php echo WP_DEBUG ? 'true' : 'false'; ?>) {
                    console.log('Page load time:', loadTime + 'ms');
                }
            });
            </script>
            <?php
        }
        add_action('wp_footer', 'gpress_performance_monitoring_script');
    }
}

/**
 * CSS Minification Helper
 */
function gpress_minify_css($css) {
    // Remove comments
    $css = preg_replace('!/\*[^*]*\*+([^/][^*]*\*+)*/!', '', $css);
    
    // Remove whitespace
    $css = str_replace(array("\r\n", "\r", "\n", "\t", '  ', '    ', '    '), '', $css);
    
    return $css;
}

/**
 * Performance Cache Busting
 */
function gpress_performance_cache_busting($src, $handle) {
    // Add timestamp for development
    if (WP_DEBUG && !is_admin()) {
        $file_path = str_replace(GPRESS_THEME_URI, GPRESS_THEME_DIR, $src);
        if (file_exists($file_path)) {
            $file_time = filemtime($file_path);
            $src = add_query_arg('t', $file_time, $src);
        }
    }
    
    return $src;
}
add_filter('style_loader_src', 'gpress_performance_cache_busting', 10, 2);
add_filter('script_loader_src', 'gpress_performance_cache_busting', 10, 2);
```

## 2. Create Image Optimization Functions

### File: `inc/image-optimization.php`
```php
<?php
/**
 * Image Optimization Functions for GPress Theme
 *
 * @package GPress
 * @version 1.0.0
 */

// Prevent direct access
defined('ABSPATH') || exit;

/**
 * Initialize Image Optimization
 */
function gpress_init_image_optimization() {
    gpress_setup_responsive_images();
    gpress_setup_webp_support();
    gpress_enhance_lazy_loading();
}
add_action('after_setup_theme', 'gpress_init_image_optimization');

/**
 * Setup Responsive Images
 */
function gpress_setup_responsive_images() {
    // Add custom image sizes
    add_image_size('gpress-hero', 1200, 600, true);
    add_image_size('gpress-featured', 800, 450, true);
    add_image_size('gpress-thumbnail', 400, 250, true);
    add_image_size('gpress-small', 300, 200, true);
    
    // Add responsive image support
    add_theme_support('post-thumbnails');
    
    // Enable responsive images in content
    add_filter('wp_image_editors', 'gpress_enable_responsive_support');
}

/**
 * Enable WebP Support
 */
function gpress_setup_webp_support() {
    // Only if WebP is supported and enabled
    if (!function_exists('imagewebp') || !get_theme_mod('enable_webp_support', true)) {
        return;
    }
    
    // Generate WebP versions of uploaded images
    function gpress_generate_webp_images($metadata, $attachment_id) {
        $file_path = get_attached_file($attachment_id);
        
        if (!$file_path || !file_exists($file_path)) {
            return $metadata;
        }
        
        $info = pathinfo($file_path);
        if (!in_array(strtolower($info['extension']), ['jpg', 'jpeg', 'png'])) {
            return $metadata;
        }
        
        // Generate WebP version
        $webp_path = $info['dirname'] . '/' . $info['filename'] . '.webp';
        
        try {
            switch (strtolower($info['extension'])) {
                case 'jpg':
                case 'jpeg':
                    $image = imagecreatefromjpeg($file_path);
                    break;
                case 'png':
                    $image = imagecreatefrompng($file_path);
                    break;
                default:
                    return $metadata;
            }
            
            if ($image) {
                // Convert to WebP with 85% quality
                imagewebp($image, $webp_path, 85);
                imagedestroy($image);
                
                // Generate WebP versions for different sizes
                if (isset($metadata['sizes']) && is_array($metadata['sizes'])) {
                    foreach ($metadata['sizes'] as $size => $size_data) {
                        $size_file = dirname($file_path) . '/' . $size_data['file'];
                        $size_info = pathinfo($size_file);
                        $size_webp = $size_info['dirname'] . '/' . $size_info['filename'] . '.webp';
                        
                        if (file_exists($size_file)) {
                            $size_image = null;
                            switch (strtolower($size_info['extension'])) {
                                case 'jpg':
                                case 'jpeg':
                                    $size_image = imagecreatefromjpeg($size_file);
                                    break;
                                case 'png':
                                    $size_image = imagecreatefrompng($size_file);
                                    break;
                            }
                            
                            if ($size_image) {
                                imagewebp($size_image, $size_webp, 85);
                                imagedestroy($size_image);
                            }
                        }
                    }
                }
            }
        } catch (Exception $e) {
            // Log error but don't break the upload process
            error_log('GPress WebP generation failed: ' . $e->getMessage());
        }
        
        return $metadata;
    }
    add_filter('wp_generate_attachment_metadata', 'gpress_generate_webp_images', 10, 2);
    
    // Serve WebP images when available
    function gpress_serve_webp_images($image, $attachment_id, $size, $icon) {
        if (!$image || is_admin()) {
            return $image;
        }
        
        // Check if browser supports WebP
        $accepts_webp = false;
        if (isset($_SERVER['HTTP_ACCEPT']) && strpos($_SERVER['HTTP_ACCEPT'], 'image/webp') !== false) {
            $accepts_webp = true;
        }
        
        if ($accepts_webp && is_array($image)) {
            $info = pathinfo($image[0]);
            $webp_url = str_replace($info['extension'], 'webp', $image[0]);
            $webp_path = str_replace(wp_get_upload_dir()['baseurl'], wp_get_upload_dir()['basedir'], $webp_url);
            
            if (file_exists($webp_path)) {
                $image[0] = $webp_url;
            }
        }
        
        return $image;
    }
    add_filter('wp_get_attachment_image_src', 'gpress_serve_webp_images', 10, 4);
}

/**
 * Enhanced Lazy Loading
 */
function gpress_enhance_lazy_loading() {
    // Add loading attributes to images
    function gpress_add_loading_attributes($attr, $attachment, $size) {
        // Don't add loading attribute to admin or RSS
        if (is_admin() || is_feed()) {
            return $attr;
        }
        
        // Add loading attribute
        if (!isset($attr['loading'])) {
            $attr['loading'] = 'lazy';
        }
        
        // Add decoding attribute
        if (!isset($attr['decoding'])) {
            $attr['decoding'] = 'async';
        }
        
        // Add fetchpriority for above-the-fold images
        if (is_singular() && !isset($attr['fetchpriority'])) {
            global $post;
            $featured_image_id = get_post_thumbnail_id($post->ID);
            
            if ($attachment->ID === $featured_image_id) {
                $attr['fetchpriority'] = 'high';
                $attr['loading'] = 'eager'; // Don't lazy load featured images
            }
        }
        
        return $attr;
    }
    add_filter('wp_get_attachment_image_attributes', 'gpress_add_loading_attributes', 10, 3);
    
    // Add lazy loading to content images
    function gpress_add_content_lazy_loading($content) {
        if (is_admin() || is_feed() || wp_is_json_request()) {
            return $content;
        }
        
        // Add loading="lazy" to content images
        $content = preg_replace_callback(
            '/<img([^>]*)>/i',
            function($matches) {
                $img_tag = $matches[0];
                
                // Skip if loading attribute already exists
                if (strpos($img_tag, 'loading=') !== false) {
                    return $img_tag;
                }
                
                // Add loading and decoding attributes
                $img_tag = str_replace('<img ', '<img loading="lazy" decoding="async" ', $img_tag);
                
                return $img_tag;
            },
            $content
        );
        
        return $content;
    }
    add_filter('the_content', 'gpress_add_content_lazy_loading');
}

/**
 * Image Compression Settings
 */
function gpress_optimize_image_compression() {
    // Increase JPEG quality for better visual results
    add_filter('jpeg_quality', function($quality) {
        return 85;
    });
    
    // Optimize image editor selection
    add_filter('wp_image_editors', function($editors) {
        // Prefer Imagick over GD if available
        if (class_exists('WP_Image_Editor_Imagick')) {
            array_unshift($editors, 'WP_Image_Editor_Imagick');
        }
        return $editors;
    });
}
add_action('init', 'gpress_optimize_image_compression');

/**
 * Progressive JPEG Support
 */
function gpress_enable_progressive_jpeg($quality, $mime_type) {
    if ($mime_type === 'image/jpeg') {
        return 'progressive';
    }
    return $quality;
}
add_filter('wp_editor_set_quality', 'gpress_enable_progressive_jpeg', 10, 2);

/**
 * Image Optimization Helper Functions
 */
function gpress_get_optimized_image_url($attachment_id, $size = 'full') {
    $image = wp_get_attachment_image_src($attachment_id, $size);
    
    if (!$image) {
        return false;
    }
    
    // Check for WebP version if browser supports it
    $accepts_webp = isset($_SERVER['HTTP_ACCEPT']) && strpos($_SERVER['HTTP_ACCEPT'], 'image/webp') !== false;
    
    if ($accepts_webp) {
        $info = pathinfo($image[0]);
        $webp_url = str_replace($info['extension'], 'webp', $image[0]);
        $webp_path = str_replace(wp_get_upload_dir()['baseurl'], wp_get_upload_dir()['basedir'], $webp_url);
        
        if (file_exists($webp_path)) {
            return $webp_url;
        }
    }
    
    return $image[0];
}

/**
 * Responsive Image Sizes
 */
function gpress_custom_image_sizes($sizes) {
    return array_merge($sizes, array(
        'gpress-hero' => 'Hero Image (1200x600)',
        'gpress-featured' => 'Featured Image (800x450)',
        'gpress-thumbnail' => 'Thumbnail (400x250)',
        'gpress-small' => 'Small (300x200)'
    ));
}
add_filter('image_size_names_choose', 'gpress_custom_image_sizes');
```

## 3. Create Advanced Caching System

### File: `inc/caching.php`
```php
<?php
/**
 * Advanced Caching Functions for GPress Theme
 *
 * @package GPress
 * @version 1.0.0
 */

// Prevent direct access
defined('ABSPATH') || exit;

/**
 * GPress Cache Management Class
 */
class GPress_Cache {
    
    /**
     * Cache duration constants
     */
    const SHORT_CACHE = 900;    // 15 minutes
    const MEDIUM_CACHE = 3600;  // 1 hour
    const LONG_CACHE = 21600;   // 6 hours
    const DAILY_CACHE = 86400;  // 24 hours
    
    /**
     * Cache group name
     */
    const CACHE_GROUP = 'gpress';
    
    /**
     * Get cached data or execute callback
     */
    public static function get_or_set($key, $callback, $duration = self::MEDIUM_CACHE) {
        $cached_data = wp_cache_get($key, self::CACHE_GROUP);
        
        if ($cached_data !== false) {
            return $cached_data;
        }
        
        $data = call_user_func($callback);
        wp_cache_set($key, $data, self::CACHE_GROUP, $duration);
        
        return $data;
    }
    
    /**
     * Clear cache for specific group or key
     */
    public static function clear_cache($key = null) {
        if ($key) {
            wp_cache_delete($key, self::CACHE_GROUP);
        } else {
            wp_cache_flush_group(self::CACHE_GROUP);
        }
    }
    
    /**
     * Cache recent posts with featured images
     */
    public static function get_recent_posts($num_posts = 5) {
        $cache_key = "recent_posts_{$num_posts}";
        
        return self::get_or_set($cache_key, function() use ($num_posts) {
            return get_posts([
                'numberposts' => $num_posts,
                'post_status' => 'publish',
                'meta_query' => [
                    [
                        'key' => '_thumbnail_id',
                        'compare' => 'EXISTS'
                    ]
                ],
                'fields' => 'ids'
            ]);
        }, self::MEDIUM_CACHE);
    }
    
    /**
     * Cache popular posts
     */
    public static function get_popular_posts($num_posts = 5) {
        $cache_key = "popular_posts_{$num_posts}";
        
        return self::get_or_set($cache_key, function() use ($num_posts) {
            return get_posts([
                'numberposts' => $num_posts,
                'post_status' => 'publish',
                'meta_key' => 'post_views_count',
                'orderby' => 'meta_value_num',
                'order' => 'DESC',
                'fields' => 'ids'
            ]);
        }, self::LONG_CACHE);
    }
    
    /**
     * Cache category posts
     */
    public static function get_category_posts($category_id, $num_posts = 5) {
        $cache_key = "category_posts_{$category_id}_{$num_posts}";
        
        return self::get_or_set($cache_key, function() use ($category_id, $num_posts) {
            return get_posts([
                'numberposts' => $num_posts,
                'post_status' => 'publish',
                'cat' => $category_id,
                'fields' => 'ids'
            ]);
        }, self::MEDIUM_CACHE);
    }
    
    /**
     * Cache navigation menu
     */
    public static function get_cached_menu($location) {
        $cache_key = "nav_menu_{$location}";
        
        return self::get_or_set($cache_key, function() use ($location) {
            return wp_nav_menu([
                'theme_location' => $location,
                'echo' => false,
                'fallback_cb' => false
            ]);
        }, self::DAILY_CACHE);
    }
    
    /**
     * Cache widget output
     */
    public static function get_cached_widget($widget_id) {
        $cache_key = "widget_{$widget_id}";
        
        return self::get_or_set($cache_key, function() use ($widget_id) {
            ob_start();
            dynamic_sidebar($widget_id);
            return ob_get_clean();
        }, self::MEDIUM_CACHE);
    }
}

/**
 * Initialize Caching System
 */
function gpress_init_caching_system() {
    // Clear cache on post updates
    function gpress_clear_cache_on_update($post_id) {
        GPress_Cache::clear_cache();
    }
    add_action('save_post', 'gpress_clear_cache_on_update');
    add_action('delete_post', 'gpress_clear_cache_on_update');
    add_action('wp_update_nav_menu', 'gpress_clear_cache_on_update');
    
    // Clear cache when customizer settings change
    add_action('customize_save_after', function() {
        GPress_Cache::clear_cache();
    });
    
    // Enable object caching if available
    if (function_exists('wp_cache_supports')) {
        add_action('init', function() {
            if (wp_cache_supports('add_groups')) {
                wp_cache_add_global_groups(array(GPress_Cache::CACHE_GROUP));
            }
        });
    }
}
add_action('init', 'gpress_init_caching_system');

/**
 * Fragment Caching for Expensive Operations
 */
function gpress_fragment_cache($key, $callback, $duration = 3600) {
    $cached_fragment = get_transient("gpress_fragment_{$key}");
    
    if ($cached_fragment !== false) {
        return $cached_fragment;
    }
    
    ob_start();
    call_user_func($callback);
    $fragment = ob_get_clean();
    
    set_transient("gpress_fragment_{$key}", $fragment, $duration);
    
    return $fragment;
}

/**
 * Page Caching for Static Content
 */
function gpress_page_cache_headers() {
    if (!is_admin() && !is_user_logged_in()) {
        // Set cache headers for static content
        if (is_singular() && !comments_open()) {
            header('Cache-Control: public, max-age=3600');
            header('Expires: ' . gmdate('D, d M Y H:i:s', time() + 3600) . ' GMT');
        }
    }
}
add_action('template_redirect', 'gpress_page_cache_headers');

/**
 * Browser Caching via .htaccess
 */
function gpress_add_browser_caching_rules() {
    $htaccess_rules = '
# GPress Browser Caching Rules
<IfModule mod_expires.c>
    ExpiresActive On
    ExpiresByType text/css "access plus 1 year"
    ExpiresByType application/javascript "access plus 1 year"
    ExpiresByType image/png "access plus 1 year"
    ExpiresByType image/jpg "access plus 1 year"
    ExpiresByType image/jpeg "access plus 1 year"
    ExpiresByType image/gif "access plus 1 year"
    ExpiresByType image/webp "access plus 1 year"
    ExpiresByType application/pdf "access plus 1 month"
    ExpiresByType image/x-icon "access plus 1 year"
</IfModule>

<IfModule mod_headers.c>
    <FilesMatch "\.(css|js|png|jpg|jpeg|gif|webp|ico)$">
        Header set Cache-Control "public, max-age=31536000"
    </FilesMatch>
</IfModule>';
    
    // Only add rules if we can write to .htaccess
    if (get_option('permalink_structure') && is_writable(ABSPATH)) {
        $htaccess_file = ABSPATH . '.htaccess';
        $current_rules = file_exists($htaccess_file) ? file_get_contents($htaccess_file) : '';
        
        if (strpos($current_rules, 'GPress Browser Caching Rules') === false) {
            file_put_contents($htaccess_file, $htaccess_rules . "\n\n" . $current_rules);
        }
    }
}
add_action('admin_init', 'gpress_add_browser_caching_rules');
```

## 4. Create Performance JavaScript

### File: `assets/js/performance.js`
```javascript
/**
 * Performance Optimization JavaScript for GPress Theme
 * Minimal, performance-focused enhancements
 * Version: 1.0.0
 */

(function() {
    'use strict';
    
    // Check if performance features are enabled
    const config = window.gpressPerformance || {};
    
    /**
     * Initialize performance optimizations
     */
    function initPerformanceOptimizations() {
        if (config.enableLazyLoad) {
            initIntersectionObserverLazyLoad();
        }
        
        initImageOptimizations();
        initResourceHints();
        initServiceWorker();
        
        // Initialize after page load to avoid blocking
        if (document.readyState === 'complete') {
            initNonCriticalOptimizations();
        } else {
            window.addEventListener('load', initNonCriticalOptimizations);
        }
    }
    
    /**
     * Enhanced Lazy Loading with Intersection Observer
     */
    function initIntersectionObserverLazyLoad() {
        if (!('IntersectionObserver' in window)) {
            return;
        }
        
        const imageObserver = new IntersectionObserver((entries, observer) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    const img = entry.target;
                    
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
                    
                    // Remove lazy class and observer
                    img.classList.remove('lazy');
                    observer.unobserve(img);
                    
                    // Trigger fade-in animation
                    img.style.opacity = '1';
                }
            });
        }, {
            root: null,
            rootMargin: '50px 0px',
            threshold: 0.01
        });
        
        // Observe all lazy images
        document.querySelectorAll('img[data-src], img.lazy').forEach(img => {
            img.style.opacity = '0';
            img.style.transition = 'opacity 0.3s ease';
            imageObserver.observe(img);
        });
        
        // Lazy load background images
        const bgObserver = new IntersectionObserver((entries, observer) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    const element = entry.target;
                    const bgSrc = element.dataset.bgSrc;
                    
                    if (bgSrc) {
                        element.style.backgroundImage = `url(${bgSrc})`;
                        element.removeAttribute('data-bg-src');
                        observer.unobserve(element);
                    }
                }
            });
        }, {
            rootMargin: '50px 0px'
        });
        
        document.querySelectorAll('[data-bg-src]').forEach(el => {
            bgObserver.observe(el);
        });
    }
    
    /**
     * Image Optimization Enhancements
     */
    function initImageOptimizations() {
        // Add WebP support detection
        function supportsWebP() {
            return new Promise((resolve) => {
                const webP = new Image();
                webP.onload = webP.onerror = function () {
                    resolve(webP.height === 2);
                };
                webP.src = 'data:image/webp;base64,UklGRjoAAABXRUJQVlA4IC4AAACyAgCdASoCAAIALmk0mk0iIiIiIgBoSygABc6WWgAA/veff/0PP8bA//LwYAAA';
            });
        }
        
        // Replace JPEG/PNG with WebP if supported
        supportsWebP().then(supported => {
            if (supported) {
                document.body.classList.add('webp-supported');
                
                // Replace image sources with WebP versions
                document.querySelectorAll('img[src*=".jpg"], img[src*=".jpeg"], img[src*=".png"]').forEach(img => {
                    const webpSrc = img.src.replace(/\.(jpe?g|png)$/i, '.webp');
                    
                    // Check if WebP version exists
                    fetch(webpSrc, { method: 'HEAD' })
                        .then(response => {
                            if (response.ok) {
                                img.src = webpSrc;
                            }
                        })
                        .catch(() => {
                            // WebP version doesn't exist, keep original
                        });
                });
            }
        });
        
        // Progressive image loading
        document.querySelectorAll('img').forEach(img => {
            if (img.complete) {
                img.classList.add('loaded');
            } else {
                img.addEventListener('load', function() {
                    this.classList.add('loaded');
                });
            }
        });
    }
    
    /**
     * Dynamic Resource Hints
     */
    function initResourceHints() {
        // Preload resources on hover
        document.addEventListener('mouseover', function(e) {
            const link = e.target.closest('a');
            
            if (link && link.hostname === location.hostname && !link.dataset.preloaded) {
                const preloadLink = document.createElement('link');
                preloadLink.rel = 'prefetch';
                preloadLink.href = link.href;
                document.head.appendChild(preloadLink);
                
                link.dataset.preloaded = 'true';
            }
        });
        
        // Preload images on viewport approach
        const linkObserver = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    const link = entry.target;
                    const href = link.href;
                    
                    // Preload the next page
                    if (href && !link.dataset.preloaded) {
                        const preloadLink = document.createElement('link');
                        preloadLink.rel = 'prefetch';
                        preloadLink.href = href;
                        document.head.appendChild(preloadLink);
                        
                        link.dataset.preloaded = 'true';
                    }
                }
            });
        }, {
            rootMargin: '200px 0px'
        });
        
        // Observe navigation links
        document.querySelectorAll('nav a, .pagination a').forEach(link => {
            linkObserver.observe(link);
        });
    }
    
    /**
     * Service Worker Registration
     */
    function initServiceWorker() {
        if (!config.enableServiceWorker || !('serviceWorker' in navigator)) {
            return;
        }
        
        window.addEventListener('load', () => {
            navigator.serviceWorker.register('/sw.js')
                .then(registration => {
                    console.log('GPress SW registered:', registration);
                    
                    // Update available
                    registration.addEventListener('updatefound', () => {
                        const newWorker = registration.installing;
                        newWorker.addEventListener('statechange', () => {
                            if (newWorker.state === 'installed' && navigator.serviceWorker.controller) {
                                // Show update notification
                                showUpdateNotification();
                            }
                        });
                    });
                })
                .catch(error => {
                    console.log('GPress SW registration failed:', error);
                });
        });
    }
    
    /**
     * Non-Critical Optimizations
     */
    function initNonCriticalOptimizations() {
        // Smooth scrolling for anchor links
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function(e) {
                const target = document.querySelector(this.getAttribute('href'));
                
                if (target) {
                    e.preventDefault();
                    target.scrollIntoView({
                        behavior: 'smooth',
                        block: 'start'
                    });
                }
            });
        });
        
        // Optimize font loading
        if ('fonts' in document) {
            document.fonts.ready.then(() => {
                document.body.classList.add('fonts-loaded');
            });
        }
        
        // Initialize performance monitoring
        if (config.enableWebVitals) {
            initBasicPerformanceMonitoring();
        }
    }
    
    /**
     * Basic Performance Monitoring
     */
    function initBasicPerformanceMonitoring() {
        // Measure key performance metrics
        if ('performance' in window && 'timing' in performance) {
            window.addEventListener('load', () => {
                setTimeout(() => {
                    const timing = performance.timing;
                    const loadTime = timing.loadEventEnd - timing.navigationStart;
                    const domReady = timing.domContentLoadedEventEnd - timing.navigationStart;
                    
                    // Send to analytics if available
                    if (typeof gtag !== 'undefined') {
                        gtag('event', 'performance_metrics', {
                            page_load_time: Math.round(loadTime),
                            dom_ready_time: Math.round(domReady),
                            event_category: 'Performance'
                        });
                    }
                    
                    // Debug logging
                    if (window.console && typeof console.log === 'function') {
                        console.group('GPress Performance Metrics');
                        console.log('Page Load Time:', loadTime + 'ms');
                        console.log('DOM Ready Time:', domReady + 'ms');
                        console.groupEnd();
                    }
                }, 100);
            });
        }
    }
    
    /**
     * Utility Functions
     */
    function showUpdateNotification() {
        const notification = document.createElement('div');
        notification.className = 'update-notification';
        notification.innerHTML = `
            <p>A new version is available!</p>
            <button onclick="location.reload()">Refresh</button>
            <button onclick="this.parentNode.remove()">Dismiss</button>
        `;
        notification.style.cssText = `
            position: fixed;
            top: 20px;
            right: 20px;
            background: var(--wp--preset--color--primary);
            color: white;
            padding: 1rem;
            border-radius: 8px;
            z-index: 10000;
            box-shadow: 0 4px 12px rgba(0,0,0,0.2);
        `;
        
        document.body.appendChild(notification);
    }
    
    /**
     * Initialize everything
     */
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', initPerformanceOptimizations);
    } else {
        initPerformanceOptimizations();
    }
    
})();
```

## 5. Create Service Worker

### File: `sw.js` (root directory)
```javascript
/**
 * Service Worker for GPress Theme
 * Implements caching strategies for better performance
 * Version: 1.0.0
 */

const CACHE_NAME = 'gpress-v1';
const STATIC_CACHE = 'gpress-static-v1';
const DYNAMIC_CACHE = 'gpress-dynamic-v1';
const IMAGE_CACHE = 'gpress-images-v1';

// Assets to cache on install
const STATIC_ASSETS = [
    '/wp-content/themes/gpress/assets/css/style.min.css',
    '/wp-content/themes/gpress/assets/css/critical.css',
    '/wp-content/themes/gpress/assets/js/performance.min.js',
    '/'
];

// Cache duration in milliseconds
const CACHE_DURATION = {
    STATIC: 7 * 24 * 60 * 60 * 1000,  // 7 days
    DYNAMIC: 24 * 60 * 60 * 1000,     // 1 day
    IMAGES: 30 * 24 * 60 * 60 * 1000  // 30 days
};

/**
 * Install Event
 */
self.addEventListener('install', event => {
    event.waitUntil(
        caches.open(STATIC_CACHE)
            .then(cache => {
                console.log('GPress SW: Caching static assets');
                return cache.addAll(STATIC_ASSETS);
            })
            .then(() => {
                console.log('GPress SW: Installation complete');
                return self.skipWaiting();
            })
    );
});

/**
 * Activate Event
 */
self.addEventListener('activate', event => {
    event.waitUntil(
        caches.keys()
            .then(keys => {
                return Promise.all(
                    keys.filter(key => {
                        return key !== STATIC_CACHE && 
                               key !== DYNAMIC_CACHE && 
                               key !== IMAGE_CACHE;
                    })
                    .map(key => {
                        console.log('GPress SW: Deleting old cache', key);
                        return caches.delete(key);
                    })
                );
            })
            .then(() => {
                console.log('GPress SW: Activation complete');
                return self.clients.claim();
            })
    );
});

/**
 * Fetch Event with Caching Strategies
 */
self.addEventListener('fetch', event => {
    const request = event.request;
    const url = new URL(request.url);
    
    // Skip non-GET requests
    if (request.method !== 'GET') {
        return;
    }
    
    // Skip admin and login pages
    if (url.pathname.includes('/wp-admin/') || 
        url.pathname.includes('/wp-login.php')) {
        return;
    }
    
    // Handle different types of requests
    if (isStaticAsset(request)) {
        event.respondWith(cacheFirstStrategy(request, STATIC_CACHE));
    } else if (isImage(request)) {
        event.respondWith(cacheFirstStrategy(request, IMAGE_CACHE));
    } else if (isHTMLPage(request)) {
        event.respondWith(networkFirstStrategy(request, DYNAMIC_CACHE));
    } else {
        event.respondWith(networkFirstStrategy(request, DYNAMIC_CACHE));
    }
});

/**
 * Caching Strategies
 */

// Cache First Strategy (for static assets)
async function cacheFirstStrategy(request, cacheName) {
    try {
        const cache = await caches.open(cacheName);
        const cachedResponse = await cache.match(request);
        
        if (cachedResponse) {
            // Check if cache is still fresh
            const cacheTime = new Date(cachedResponse.headers.get('date')).getTime();
            const now = Date.now();
            const maxAge = CACHE_DURATION.STATIC;
            
            if (now - cacheTime < maxAge) {
                return cachedResponse;
            }
        }
        
        // Fetch from network
        const networkResponse = await fetch(request);
        
        // Cache successful responses
        if (networkResponse.status === 200) {
            const responseClone = networkResponse.clone();
            cache.put(request, responseClone);
        }
        
        return networkResponse;
        
    } catch (error) {
        // Return cached version if network fails
        const cache = await caches.open(cacheName);
        const cachedResponse = await cache.match(request);
        
        if (cachedResponse) {
            return cachedResponse;
        }
        
        // Return offline page for HTML requests
        if (isHTMLPage(request)) {
            return new Response(getOfflineHTML(), {
                headers: { 'Content-Type': 'text/html' }
            });
        }
        
        throw error;
    }
}

// Network First Strategy (for HTML pages)
async function networkFirstStrategy(request, cacheName) {
    try {
        const networkResponse = await fetch(request);
        
        // Cache successful responses
        if (networkResponse.status === 200) {
            const cache = await caches.open(cacheName);
            const responseClone = networkResponse.clone();
            cache.put(request, responseClone);
        }
        
        return networkResponse;
        
    } catch (error) {
        // Fallback to cache
        const cache = await caches.open(cacheName);
        const cachedResponse = await cache.match(request);
        
        if (cachedResponse) {
            return cachedResponse;
        }
        
        // Return offline page for HTML requests
        if (isHTMLPage(request)) {
            return new Response(getOfflineHTML(), {
                headers: { 'Content-Type': 'text/html' }
            });
        }
        
        throw error;
    }
}

/**
 * Helper Functions
 */
function isStaticAsset(request) {
    const url = new URL(request.url);
    return url.pathname.includes('/wp-content/themes/gpress/assets/') ||
           url.pathname.endsWith('.css') ||
           url.pathname.endsWith('.js') ||
           url.pathname.endsWith('.woff2') ||
           url.pathname.endsWith('.woff');
}

function isImage(request) {
    const url = new URL(request.url);
    return url.pathname.includes('/wp-content/uploads/') ||
           url.pathname.match(/\.(png|jpg|jpeg|gif|webp|svg)$/i);
}

function isHTMLPage(request) {
    return request.headers.get('accept')?.includes('text/html');
}

function getOfflineHTML() {
    return `
    <!DOCTYPE html>
    <html>
    <head>
        <title>Offline - GPress</title>
        <style>
            body { 
                font-family: system-ui, sans-serif; 
                text-align: center; 
                padding: 2rem;
                background: #f5f5f5;
            }
            .offline-container {
                max-width: 500px;
                margin: 50px auto;
                background: white;
                padding: 2rem;
                border-radius: 12px;
                box-shadow: 0 4px 12px rgba(0,0,0,0.1);
            }
            h1 { color: #333; margin-bottom: 1rem; }
            p { color: #666; line-height: 1.6; }
            button {
                background: #2c3e50;
                color: white;
                border: none;
                padding: 0.75rem 1.5rem;
                border-radius: 6px;
                cursor: pointer;
                margin-top: 1rem;
            }
            button:hover { background: #34495e; }
        </style>
    </head>
    <body>
        <div class="offline-container">
            <h1>You're Offline</h1>
            <p>It looks like you've lost your internet connection. Don't worry, you can still browse previously visited pages.</p>
            <button onclick="history.back()">Go Back</button>
            <button onclick="location.reload()">Try Again</button>
        </div>
    </body>
    </html>
    `;
}

/**
 * Background Sync for Failed Requests
 */
self.addEventListener('sync', event => {
    if (event.tag === 'background-sync') {
        event.waitUntil(doBackgroundSync());
    }
});

async function doBackgroundSync() {
    console.log('GPress SW: Running background sync');
    // Implement background sync logic here
}

/**
 * Push Notifications (if needed)
 */
self.addEventListener('push', event => {
    if (event.data) {
        const data = event.data.json();
        const options = {
            body: data.body,
            icon: '/wp-content/themes/gpress/assets/images/icon-192x192.png',
            badge: '/wp-content/themes/gpress/assets/images/badge-72x72.png',
            tag: 'gpress-notification'
        };
        
        event.waitUntil(
            self.registration.showNotification(data.title, options)
        );
    }
});
```

## 6. Update Functions.php

### Add to: `functions.php`
```php
// Performance Optimization Support
require_once GPRESS_INC_DIR . '/performance.php';
require_once GPRESS_INC_DIR . '/image-optimization.php';
require_once GPRESS_INC_DIR . '/caching.php';

/**
 * Enable Performance Features
 */
function gpress_performance_support() {
    // Add image optimization support
    add_theme_support('post-thumbnails');
    
    // Enable responsive images
    add_theme_support('responsive-embeds');
    
    // Add HTML5 support for better performance
    add_theme_support('html5', array(
        'search-form',
        'comment-form',
        'comment-list',
        'gallery',
        'caption',
        'style',
        'script'
    ));
}
add_action('after_setup_theme', 'gpress_performance_support');
```

## Testing Instructions

### 1. Performance Optimization Test
```bash
# Test performance optimizations
1. Activate GPress theme
2. Check Lighthouse performance score
3. Verify Core Web Vitals metrics
4. Test image lazy loading functionality
5. Check Service Worker registration
```

### 2. Conditional Loading Test
```bash
# Test conditional performance feature loading
1. Visit homepage - check performance scripts load
2. Visit pages without images - verify reduced asset loading
3. Check Network tab for conditional resource loading
4. Test WebP image serving (if supported)
```

### 3. Caching Test
```bash
# Test caching functionality
1. Visit pages multiple times
2. Check for cached responses
3. Clear cache and verify regeneration
4. Test object caching (if available)
```

### 4. Core Web Vitals Test
```bash
# Test Core Web Vitals metrics
1. Run PageSpeed Insights
2. Check LCP < 2.5s
3. Verify FID < 100ms
4. Confirm CLS < 0.1
5. Test on mobile and desktop
```

### 5. Service Worker Test
```bash
# Test Service Worker functionality
1. Enable Service Worker in theme options
2. Visit site and check SW registration
3. Go offline and test cached pages
4. Verify offline fallback page
```

## Expected Results

After completing this step, you should have:

- ✅ 95+ Lighthouse performance score
- ✅ Optimized Core Web Vitals metrics
- ✅ Conditional performance feature loading
- ✅ Advanced image optimization with WebP support
- ✅ Comprehensive caching system
- ✅ Service Worker for offline functionality
- ✅ Performance monitoring and analytics
- ✅ Database query optimization
- ✅ Minimal, optimized JavaScript

The theme should now achieve excellent performance scores while only loading optimization features when they're actually needed, ensuring optimal resource usage.

## Next Steps

In Step 9, we'll focus on typography and layout systems to enhance the visual design and readability of the theme.