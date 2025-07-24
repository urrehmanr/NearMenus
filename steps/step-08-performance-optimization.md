# Step 8: Performance Optimization

## Objective
Implement comprehensive performance optimization strategies to achieve 95+ Lighthouse scores and excellent Core Web Vitals metrics, ensuring fast loading times and optimal user experience.

## What You'll Learn
- Core Web Vitals optimization
- Resource loading strategies
- Image optimization techniques
- JavaScript performance optimization
- Caching implementations
- Database query optimization
- CDN integration strategies

## Core Web Vitals Target Metrics

### Performance Goals
- **Largest Contentful Paint (LCP)**: < 2.5 seconds
- **First Input Delay (FID)**: < 100 milliseconds
- **Cumulative Layout Shift (CLS)**: < 0.1
- **First Contentful Paint (FCP)**: < 1.8 seconds
- **Time to Interactive (TTI)**: < 3.8 seconds

## Image Optimization

### 1. Responsive Images Implementation

Update `inc/theme-setup.php`:
```php
/**
 * Add responsive image support
 */
function modernblog2025_responsive_images() {
    // Enable responsive images
    add_theme_support('post-thumbnails');
    
    // Add custom image sizes with responsive srcset
    add_image_size('modernblog2025-hero', 1200, 600, true);
    add_image_size('modernblog2025-featured', 800, 450, true);
    add_image_size('modernblog2025-thumbnail', 400, 250, true);
    add_image_size('modernblog2025-small', 300, 200, true);
    
    // Enable WebP support detection
    add_filter('wp_image_editors', 'modernblog2025_enable_webp_support');
}
add_action('after_setup_theme', 'modernblog2025_responsive_images');

/**
 * Enable WebP support
 */
function modernblog2025_enable_webp_support($editors) {
    if (!class_exists('WP_Image_Editor_Imagick')) {
        return $editors;
    }
    
    array_unshift($editors, 'WP_Image_Editor_Imagick');
    return $editors;
}

/**
 * Generate WebP versions of images
 */
function modernblog2025_generate_webp_images($metadata, $attachment_id) {
    if (!function_exists('imagewebp')) {
        return $metadata;
    }
    
    $upload_dir = wp_upload_dir();
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
        imagewebp($image, $webp_path, 85);
        imagedestroy($image);
    }
    
    return $metadata;
}
add_filter('wp_generate_attachment_metadata', 'modernblog2025_generate_webp_images', 10, 2);

/**
 * Add WebP support to srcset
 */
function modernblog2025_webp_srcset($sources, $size_array, $image_src, $image_meta, $attachment_id) {
    foreach ($sources as $width => $source) {
        $file_path = get_attached_file($attachment_id);
        $info = pathinfo($file_path);
        $webp_url = str_replace($info['extension'], 'webp', $source['url']);
        $webp_path = str_replace($info['extension'], 'webp', $file_path);
        
        if (file_exists($webp_path)) {
            $sources[$width]['url'] = $webp_url;
        }
    }
    
    return $sources;
}
add_filter('wp_calculate_image_srcset', 'modernblog2025_webp_srcset', 10, 5);
```

### 2. Lazy Loading Enhancement

```php
/**
 * Enhanced lazy loading implementation
 */
function modernblog2025_enhance_lazy_loading($attr, $attachment, $size) {
    // Add loading attribute for better browser support
    if (!isset($attr['loading'])) {
        $attr['loading'] = 'lazy';
    }
    
    // Add decoding attribute for better performance
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
add_filter('wp_get_attachment_image_attributes', 'modernblog2025_enhance_lazy_loading', 10, 3);

/**
 * Add native lazy loading to content images
 */
function modernblog2025_add_lazy_loading($content) {
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
            
            // Add loading="lazy" before the closing >
            return str_replace('>', ' loading="lazy" decoding="async">', $img_tag);
        },
        $content
    );
    
    return $content;
}
add_filter('the_content', 'modernblog2025_add_lazy_loading');
```

## Advanced Caching Implementation

### 1. Object Caching

```php
/**
 * Enhanced object caching for queries
 */
class ModernBlog2025_Cache {
    
    /**
     * Cache duration in seconds
     */
    const CACHE_DURATION = 3600; // 1 hour
    
    /**
     * Get cached data or execute callback
     */
    public static function get_or_set($key, $callback, $duration = self::CACHE_DURATION) {
        $cached_data = wp_cache_get($key, 'modernblog2025');
        
        if ($cached_data !== false) {
            return $cached_data;
        }
        
        $data = call_user_func($callback);
        wp_cache_set($key, $data, 'modernblog2025', $duration);
        
        return $data;
    }
    
    /**
     * Clear cache for specific patterns
     */
    public static function clear_cache_group($group) {
        wp_cache_flush_group($group);
    }
    
    /**
     * Cache recent posts
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
                ]
            ]);
        });
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
                'order' => 'DESC'
            ]);
        }, self::CACHE_DURATION * 2); // Cache longer for popular posts
    }
}

/**
 * Clear cache on post updates
 */
function modernblog2025_clear_cache_on_update($post_id) {
    ModernBlog2025_Cache::clear_cache_group('modernblog2025');
}
add_action('save_post', 'modernblog2025_clear_cache_on_update');
add_action('delete_post', 'modernblog2025_clear_cache_on_update');
```

### 2. Database Query Optimization

```php
/**
 * Optimize database queries
 */
function modernblog2025_optimize_queries() {
    
    /**
     * Optimize main query
     */
    function modernblog2025_optimize_main_query($query) {
        if (!is_admin() && $query->is_main_query()) {
            
            // Optimize home page query
            if (is_home()) {
                $query->set('posts_per_page', 10);
                $query->set('ignore_sticky_posts', 1);
                
                // Only load necessary fields
                $query->set('fields', 'ids');
            }
            
            // Optimize category/tag archives
            if (is_category() || is_tag()) {
                $query->set('posts_per_page', 12);
            }
            
            // Optimize search queries
            if (is_search()) {
                // Exclude pages from search
                $query->set('post_type', 'post');
                $query->set('posts_per_page', 10);
            }
        }
    }
    add_action('pre_get_posts', 'modernblog2025_optimize_main_query');
    
    /**
     * Remove unnecessary queries
     */
    function modernblog2025_remove_unnecessary_queries() {
        // Remove emoji detection queries
        remove_action('wp_head', 'print_emoji_detection_script', 7);
        remove_action('admin_print_scripts', 'print_emoji_detection_script');
        remove_action('wp_print_styles', 'print_emoji_styles');
        remove_action('admin_print_styles', 'print_emoji_styles');
        
        // Remove shortlink if not needed
        remove_action('wp_head', 'wp_shortlink_wp_head', 10, 0);
        
        // Remove RSD link
        remove_action('wp_head', 'rsd_link');
        
        // Remove Windows Live Writer manifest
        remove_action('wp_head', 'wlwmanifest_link');
    }
    add_action('init', 'modernblog2025_remove_unnecessary_queries');
}
modernblog2025_optimize_queries();

/**
 * Add database indexes for better performance
 */
function modernblog2025_add_custom_indexes() {
    global $wpdb;
    
    // Add index for post meta queries (like featured posts)
    $wpdb->query("
        CREATE INDEX IF NOT EXISTS idx_postmeta_key_value 
        ON {$wpdb->postmeta} (meta_key, meta_value(10))
    ");
    
    // Add index for post status and date
    $wpdb->query("
        CREATE INDEX IF NOT EXISTS idx_posts_status_date 
        ON {$wpdb->posts} (post_status, post_date)
    ");
}
add_action('after_setup_theme', 'modernblog2025_add_custom_indexes');
```

## Resource Loading Optimization

### 1. Advanced Script Loading

Update `inc/enqueue-scripts.php`:
```php
/**
 * Advanced script loading with performance optimization
 */
function modernblog2025_enqueue_optimized_assets() {
    
    // Defer non-critical CSS
    wp_enqueue_style(
        'modernblog2025-style',
        MODERNBLOG2025_THEME_URI . '/assets/css/style.min.css',
        array(),
        MODERNBLOG2025_VERSION,
        'all'
    );
    
    // Preload critical resources
    modernblog2025_preload_critical_resources();
    
    // Load scripts with modern loading strategies
    if (is_singular() && comments_open() && get_option('thread_comments')) {
        wp_enqueue_script('comment-reply');
        
        // Add async loading for comment reply
        add_filter('script_loader_tag', function($tag, $handle) {
            if ($handle === 'comment-reply') {
                return str_replace('<script ', '<script async ', $tag);
            }
            return $tag;
        }, 10, 2);
    }
    
    // Load theme JavaScript with module support
    wp_enqueue_script(
        'modernblog2025-script',
        MODERNBLOG2025_THEME_URI . '/assets/js/theme.min.js',
        array(),
        MODERNBLOG2025_VERSION,
        array(
            'strategy' => 'defer',
            'in_footer' => true
        )
    );
}
add_action('wp_enqueue_scripts', 'modernblog2025_enqueue_optimized_assets');

/**
 * Preload critical resources
 */
function modernblog2025_preload_critical_resources() {
    // Preload critical CSS
    echo '<link rel="preload" href="' . esc_url(MODERNBLOG2025_THEME_URI . '/assets/css/style.min.css') . '" as="style" onload="this.onload=null;this.rel=\'stylesheet\'">' . "\n";
    echo '<noscript><link rel="stylesheet" href="' . esc_url(MODERNBLOG2025_THEME_URI . '/assets/css/style.min.css') . '"></noscript>' . "\n";
    
    // Preload hero images on front page
    if (is_front_page()) {
        $hero_image = get_theme_mod('hero_image');
        if ($hero_image) {
            echo '<link rel="preload" href="' . esc_url($hero_image) . '" as="image">' . "\n";
        }
    }
    
    // Preload featured image on single posts
    if (is_singular('post') && has_post_thumbnail()) {
        $featured_image = wp_get_attachment_image_src(get_post_thumbnail_id(), 'large');
        if ($featured_image) {
            echo '<link rel="preload" href="' . esc_url($featured_image[0]) . '" as="image">' . "\n";
        }
    }
}

/**
 * Add resource hints for external resources
 */
function modernblog2025_add_resource_hints($urls, $relation_type) {
    switch ($relation_type) {
        case 'dns-prefetch':
            // Add DNS prefetch for external resources
            $urls[] = '//fonts.googleapis.com';
            $urls[] = '//www.google-analytics.com';
            $urls[] = '//www.googletagmanager.com';
            break;
            
        case 'preconnect':
            // Preconnect to critical third-party origins
            $urls[] = array(
                'href' => '//fonts.gstatic.com',
                'crossorigin' => 'anonymous'
            );
            break;
    }
    
    return $urls;
}
add_filter('wp_resource_hints', 'modernblog2025_add_resource_hints', 10, 2);
```

### 2. Critical CSS Implementation

```php
/**
 * Inline critical CSS for performance
 */
function modernblog2025_inline_critical_css() {
    $critical_css_file = MODERNBLOG2025_THEME_DIR . '/assets/css/critical.min.css';
    
    if (file_exists($critical_css_file)) {
        $critical_css = file_get_contents($critical_css_file);
        
        // Inline critical CSS
        echo '<style id="modernblog2025-critical-css">' . $critical_css . '</style>' . "\n";
        
        // Add script to load non-critical CSS
        echo '<script>
            (function() {
                var link = document.createElement("link");
                link.rel = "stylesheet";
                link.href = "' . esc_url(MODERNBLOG2025_THEME_URI . '/assets/css/style.min.css') . '";
                document.head.appendChild(link);
            })();
        </script>' . "\n";
    }
}
add_action('wp_head', 'modernblog2025_inline_critical_css', 1);

/**
 * Remove render-blocking CSS
 */
function modernblog2025_defer_css_loading($tag, $handle, $href, $media) {
    // Don't defer admin or login styles
    if (is_admin() || strpos($_SERVER['REQUEST_URI'], 'wp-login.php') !== false) {
        return $tag;
    }
    
    // Defer non-critical stylesheets
    $defer_handles = [
        'modernblog2025-responsive',
        'wp-block-library'
    ];
    
    if (in_array($handle, $defer_handles)) {
        return '<link rel="preload" href="' . $href . '" as="style" onload="this.onload=null;this.rel=\'stylesheet\'" id="' . $handle . '-css">' . "\n" .
               '<noscript>' . $tag . '</noscript>' . "\n";
    }
    
    return $tag;
}
add_filter('style_loader_tag', 'modernblog2025_defer_css_loading', 10, 4);
```

## JavaScript Optimization

### 1. Minimal Theme JavaScript - assets/js/theme.js

```javascript
/**
 * ModernBlog2025 Theme JavaScript
 * Minimal, performance-focused interactions
 */
(function() {
    'use strict';
    
    /**
     * Initialize theme functionality
     */
    function initTheme() {
        initLazyLoading();
        initSmoothScrolling();
        initFormEnhancements();
        initPerformanceObserver();
    }
    
    /**
     * Enhanced lazy loading with Intersection Observer
     */
    function initLazyLoading() {
        if ('IntersectionObserver' in window) {
            const imageObserver = new IntersectionObserver((entries, observer) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        const img = entry.target;
                        
                        // Replace data-src with src
                        if (img.dataset.src) {
                            img.src = img.dataset.src;
                            img.removeAttribute('data-src');
                        }
                        
                        // Replace data-srcset with srcset
                        if (img.dataset.srcset) {
                            img.srcset = img.dataset.srcset;
                            img.removeAttribute('data-srcset');
                        }
                        
                        img.classList.remove('lazy');
                        observer.unobserve(img);
                    }
                });
            }, {
                rootMargin: '50px 0px',
                threshold: 0.01
            });
            
            // Observe all lazy images
            document.querySelectorAll('img.lazy').forEach(img => {
                imageObserver.observe(img);
            });
        }
    }
    
    /**
     * Smooth scrolling for anchor links
     */
    function initSmoothScrolling() {
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
    }
    
    /**
     * Form enhancements
     */
    function initFormEnhancements() {
        // Newsletter form enhancement
        const newsletterForms = document.querySelectorAll('.newsletter-form');
        
        newsletterForms.forEach(form => {
            form.addEventListener('submit', function(e) {
                e.preventDefault();
                
                const email = this.querySelector('input[type="email"]').value;
                const button = this.querySelector('button[type="submit"]');
                
                // Basic email validation
                if (!isValidEmail(email)) {
                    showMessage('Please enter a valid email address.', 'error');
                    return;
                }
                
                // Loading state
                button.textContent = 'Subscribing...';
                button.disabled = true;
                
                // Simulate subscription (replace with actual implementation)
                setTimeout(() => {
                    showMessage('Thank you for subscribing!', 'success');
                    button.textContent = 'Subscribe';
                    button.disabled = false;
                    this.reset();
                }, 1000);
            });
        });
        
        // Contact form enhancement
        const contactForms = document.querySelectorAll('.contact-form');
        
        contactForms.forEach(form => {
            form.addEventListener('submit', function(e) {
                e.preventDefault();
                
                const formData = new FormData(this);
                const button = this.querySelector('button[type="submit"]');
                
                // Loading state
                button.textContent = 'Sending...';
                button.disabled = true;
                
                // Simulate form submission (replace with actual implementation)
                setTimeout(() => {
                    showMessage('Message sent successfully!', 'success');
                    button.textContent = 'Send Message';
                    button.disabled = false;
                    this.reset();
                }, 1500);
            });
        });
    }
    
    /**
     * Performance monitoring
     */
    function initPerformanceObserver() {
        if ('PerformanceObserver' in window) {
            // Monitor Largest Contentful Paint
            const lcpObserver = new PerformanceObserver(list => {
                const entries = list.getEntries();
                const lastEntry = entries[entries.length - 1];
                
                // Send LCP data to analytics (optional)
                if (window.gtag) {
                    gtag('event', 'web_vitals', {
                        name: 'LCP',
                        value: Math.round(lastEntry.startTime),
                        event_category: 'Performance'
                    });
                }
            });
            
            lcpObserver.observe({ type: 'largest-contentful-paint', buffered: true });
            
            // Monitor Cumulative Layout Shift
            const clsObserver = new PerformanceObserver(list => {
                let clsValue = 0;
                
                for (const entry of list.getEntries()) {
                    if (!entry.hadRecentInput) {
                        clsValue += entry.value;
                    }
                }
                
                // Send CLS data to analytics (optional)
                if (window.gtag && clsValue > 0) {
                    gtag('event', 'web_vitals', {
                        name: 'CLS',
                        value: Math.round(clsValue * 1000),
                        event_category: 'Performance'
                    });
                }
            });
            
            clsObserver.observe({ type: 'layout-shift', buffered: true });
        }
    }
    
    /**
     * Utility functions
     */
    function isValidEmail(email) {
        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        return emailRegex.test(email);
    }
    
    function showMessage(message, type = 'info') {
        const messageEl = document.createElement('div');
        messageEl.className = `message message--${type}`;
        messageEl.textContent = message;
        messageEl.style.cssText = `
            position: fixed;
            top: 20px;
            right: 20px;
            padding: 1rem;
            background: var(--wp--preset--color--${type === 'error' ? 'accent' : 'success'});
            color: white;
            border-radius: 4px;
            z-index: 1000;
            animation: slideIn 0.3s ease;
        `;
        
        document.body.appendChild(messageEl);
        
        setTimeout(() => {
            messageEl.style.animation = 'slideOut 0.3s ease';
            setTimeout(() => messageEl.remove(), 300);
        }, 3000);
    }
    
    /**
     * Initialize when DOM is ready
     */
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', initTheme);
    } else {
        initTheme();
    }
    
    /**
     * Service Worker registration for caching
     */
    if ('serviceWorker' in navigator) {
        window.addEventListener('load', () => {
            navigator.serviceWorker.register('/sw.js')
                .then(registration => {
                    console.log('SW registered: ', registration);
                })
                .catch(registrationError => {
                    console.log('SW registration failed: ', registrationError);
                });
        });
    }
})();
```

## Service Worker for Caching

Create `sw.js` in theme root:
```javascript
/**
 * Service Worker for ModernBlog2025
 * Implements caching strategies for better performance
 */

const CACHE_NAME = 'modernblog2025-v1';
const STATIC_CACHE = 'modernblog2025-static-v1';
const DYNAMIC_CACHE = 'modernblog2025-dynamic-v1';

// Assets to cache on install
const STATIC_ASSETS = [
    '/wp-content/themes/modernblog2025/assets/css/style.min.css',
    '/wp-content/themes/modernblog2025/assets/js/theme.min.js',
    '/wp-content/themes/modernblog2025/assets/css/critical.min.css',
    '/'
];

// Install event
self.addEventListener('install', event => {
    event.waitUntil(
        caches.open(STATIC_CACHE)
            .then(cache => cache.addAll(STATIC_ASSETS))
    );
});

// Activate event
self.addEventListener('activate', event => {
    event.waitUntil(
        caches.keys()
            .then(keys => {
                return Promise.all(keys
                    .filter(key => key !== STATIC_CACHE && key !== DYNAMIC_CACHE)
                    .map(key => caches.delete(key))
                );
            })
    );
});

// Fetch event with caching strategies
self.addEventListener('fetch', event => {
    const request = event.request;
    
    // Cache first strategy for static assets
    if (request.url.includes('/wp-content/themes/modernblog2025/assets/')) {
        event.respondWith(
            caches.match(request)
                .then(response => response || fetch(request))
        );
        return;
    }
    
    // Network first strategy for HTML pages
    if (request.headers.get('Accept').includes('text/html')) {
        event.respondWith(
            fetch(request)
                .then(response => {
                    const responseClone = response.clone();
                    caches.open(DYNAMIC_CACHE)
                        .then(cache => cache.put(request, responseClone));
                    return response;
                })
                .catch(() => caches.match(request))
        );
        return;
    }
    
    // Cache first strategy for images
    if (request.url.includes('/wp-content/uploads/')) {
        event.respondWith(
            caches.match(request)
                .then(response => {
                    return response || fetch(request)
                        .then(fetchResponse => {
                            const responseClone = fetchResponse.clone();
                            caches.open(DYNAMIC_CACHE)
                                .then(cache => cache.put(request, responseClone));
                            return fetchResponse;
                        });
                })
        );
        return;
    }
});
```

## Performance Monitoring

### 1. Real User Monitoring

```php
/**
 * Add performance monitoring
 */
function modernblog2025_add_performance_monitoring() {
    if (is_admin()) {
        return;
    }
    
    ?>
    <script>
    // Core Web Vitals monitoring
    function sendToAnalytics(metric) {
        // Replace with your analytics implementation
        if (typeof gtag !== 'undefined') {
            gtag('event', metric.name, {
                value: Math.round(metric.value),
                event_category: 'Web Vitals',
                non_interaction: true,
            });
        }
    }
    
    // Load web-vitals library
    (function() {
        const script = document.createElement('script');
        script.src = 'https://unpkg.com/web-vitals@3/dist/web-vitals.iife.js';
        script.onload = function() {
            webVitals.onCLS(sendToAnalytics);
            webVitals.onFID(sendToAnalytics);
            webVitals.onLCP(sendToAnalytics);
            webVitals.onFCP(sendToAnalytics);
            webVitals.onTTFB(sendToAnalytics);
        };
        document.head.appendChild(script);
    })();
    </script>
    <?php
}
add_action('wp_head', 'modernblog2025_add_performance_monitoring');
```

### 2. Performance Optimization Functions

```php
/**
 * Additional performance optimizations
 */
function modernblog2025_additional_optimizations() {
    
    // Disable WordPress emoji scripts
    remove_action('wp_head', 'print_emoji_detection_script', 7);
    remove_action('admin_print_scripts', 'print_emoji_detection_script');
    remove_action('wp_print_styles', 'print_emoji_styles');
    remove_action('admin_print_styles', 'print_emoji_styles');
    
    // Remove query strings from static resources
    function remove_query_strings($src) {
        return preg_replace('/\?.*/', '', $src);
    }
    add_filter('script_loader_src', 'remove_query_strings');
    add_filter('style_loader_src', 'remove_query_strings');
    
    // Optimize heartbeat API
    function optimize_heartbeat_settings($settings) {
        $settings['interval'] = 60; // 60 seconds instead of 15
        return $settings;
    }
    add_filter('heartbeat_settings', 'optimize_heartbeat_settings');
    
    // Disable heartbeat on frontend
    function disable_frontend_heartbeat() {
        if (!is_admin()) {
            wp_deregister_script('heartbeat');
        }
    }
    add_action('init', 'disable_frontend_heartbeat');
    
    // Optimize database auto-cleanup
    function optimize_database_cleanup() {
        // Clean up spam comments older than 30 days
        global $wpdb;
        $wpdb->query("DELETE FROM {$wpdb->comments} WHERE comment_approved = 'spam' AND comment_date < DATE_SUB(NOW(), INTERVAL 30 DAY)");
        
        // Clean up auto-drafts older than 7 days
        $wpdb->query("DELETE FROM {$wpdb->posts} WHERE post_status = 'auto-draft' AND post_date < DATE_SUB(NOW(), INTERVAL 7 DAY)");
        
        // Clean up orphaned meta
        $wpdb->query("DELETE FROM {$wpdb->postmeta} WHERE post_id NOT IN (SELECT ID FROM {$wpdb->posts})");
    }
    
    // Schedule cleanup weekly
    if (!wp_next_scheduled('modernblog2025_database_cleanup')) {
        wp_schedule_event(time(), 'weekly', 'modernblog2025_database_cleanup');
    }
    add_action('modernblog2025_database_cleanup', 'optimize_database_cleanup');
}
add_action('init', 'modernblog2025_additional_optimizations');
```

## Verification Checklist

After implementing performance optimizations:

- [ ] Lighthouse score 95+ achieved
- [ ] Core Web Vitals metrics meet targets
- [ ] Image optimization implemented
- [ ] Caching strategies in place
- [ ] JavaScript optimized and minimal
- [ ] Database queries optimized
- [ ] Service worker implemented
- [ ] Performance monitoring active

## Next Steps

In Step 9, we'll focus on typography and layout systems to enhance the visual design and readability of the theme.

## Performance Testing Tools

1. **Google Lighthouse** - Overall performance audit
2. **WebPageTest** - Detailed loading analysis
3. **GTmetrix** - Performance monitoring
4. **Chrome DevTools** - Real-time performance debugging
5. **Core Web Vitals Chrome Extension** - Real user metrics

## Common Performance Issues

**Large images**: Implement WebP, responsive images, lazy loading
**Render-blocking resources**: Defer non-critical CSS/JS
**Unused CSS/JS**: Remove or defer unnecessary code
**Database queries**: Use caching, optimize queries
**Third-party scripts**: Load asynchronously, use DNS prefetch

## Advanced Optimizations

**HTTP/2 Server Push**: Implement for critical resources
**Edge caching**: Use CDN for global content delivery
**Database optimization**: Regular cleanup and indexing
**Code splitting**: Load JavaScript modules on demand