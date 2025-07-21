<?php
/**
 * Performance Optimizations
 * 
 * @package NearMenus
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Optimize database queries with proper caching
 */
class NearMenus_Performance {
    
    public function __construct() {
        add_action('init', array($this, 'init_performance_features'));
        add_action('wp_enqueue_scripts', array($this, 'optimize_script_loading'));
        add_filter('wp_resource_hints', array($this, 'add_resource_hints'), 10, 2);
        add_action('wp_head', array($this, 'add_preload_hints'));
        add_filter('script_loader_tag', array($this, 'add_async_defer_attributes'), 10, 2);
        add_action('wp_footer', array($this, 'add_critical_css'));
    }
    
    /**
     * Initialize performance features
     */
    public function init_performance_features() {
        // Enable object caching for expensive queries
        add_filter('pre_get_posts', array($this, 'optimize_main_query'));
        
        // Cache restaurant data
        add_action('save_post', array($this, 'clear_restaurant_cache'));
        add_action('edited_term', array($this, 'clear_taxonomy_cache'));
        
        // Optimize images
        add_filter('wp_get_attachment_image_attributes', array($this, 'add_lazy_loading'));
        add_filter('the_content', array($this, 'add_lazy_loading_to_content'));
        
        // Minify HTML output
        if (!is_admin()) {
            add_action('template_redirect', array($this, 'start_html_minification'));
        }
    }
    
    /**
     * Optimize main query performance
     */
    public function optimize_main_query($query) {
        if (!is_admin() && $query->is_main_query()) {
            if (is_home() || is_archive()) {
                // Optimize restaurant queries
                $query->set('meta_key', '_restaurant_rating');
                $query->set('orderby', 'meta_value_num date');
                $query->set('order', 'DESC');
                
                // Limit posts per page for better performance
                $posts_per_page = get_theme_mod('nearmenus_results_per_page', 12);
                $query->set('posts_per_page', $posts_per_page);
            }
        }
        return $query;
    }
    
    /**
     * Cache restaurant data
     */
    public function get_cached_restaurants($args = array()) {
        $cache_key = 'nearmenus_restaurants_' . md5(serialize($args));
        $cached_data = get_transient($cache_key);
        
        if (false === $cached_data) {
            $restaurants = new WP_Query($args);
            $cached_data = array(
                'posts' => $restaurants->posts,
                'found_posts' => $restaurants->found_posts,
                'max_num_pages' => $restaurants->max_num_pages
            );
            
            // Cache for 1 hour
            set_transient($cache_key, $cached_data, HOUR_IN_SECONDS);
        }
        
        return $cached_data;
    }
    
    /**
     * Cache cuisine data
     */
    public function get_cached_cuisines() {
        $cache_key = 'nearmenus_cuisines_with_counts';
        $cached_cuisines = get_transient($cache_key);
        
        if (false === $cached_cuisines) {
            $cuisines = get_terms(array(
                'taxonomy' => 'cuisine',
                'hide_empty' => true,
                'orderby' => 'count',
                'order' => 'DESC',
                'meta_query' => array(
                    array(
                        'key' => '_cuisine_featured',
                        'value' => '1',
                        'compare' => '='
                    )
                )
            ));
            
            // Cache for 6 hours
            set_transient($cache_key, $cuisines, 6 * HOUR_IN_SECONDS);
            $cached_cuisines = $cuisines;
        }
        
        return $cached_cuisines;
    }
    
    /**
     * Clear restaurant cache on save
     */
    public function clear_restaurant_cache($post_id) {
        if (get_post_type($post_id) === 'post') {
            // Clear restaurant caches
            $this->clear_cache_by_pattern('nearmenus_restaurants_*');
            $this->clear_cache_by_pattern('nearmenus_featured_*');
            $this->clear_cache_by_pattern('nearmenus_search_*');
        }
    }
    
    /**
     * Clear taxonomy cache on edit
     */
    public function clear_taxonomy_cache($term_id) {
        $this->clear_cache_by_pattern('nearmenus_cuisines_*');
        $this->clear_cache_by_pattern('nearmenus_locations_*');
        $this->clear_cache_by_pattern('nearmenus_restaurants_*');
    }
    
    /**
     * Clear cache by pattern
     */
    private function clear_cache_by_pattern($pattern) {
        global $wpdb;
        
        $pattern = str_replace('*', '%', $pattern);
        $wpdb->query($wpdb->prepare(
            "DELETE FROM {$wpdb->options} WHERE option_name LIKE %s",
            '_transient_' . $pattern
        ));
        $wpdb->query($wpdb->prepare(
            "DELETE FROM {$wpdb->options} WHERE option_name LIKE %s",
            '_transient_timeout_' . $pattern
        ));
    }
    
    /**
     * Optimize script loading
     */
    public function optimize_script_loading() {
        // Remove jQuery migrate but keep jQuery core
        if (!is_admin()) {
            wp_deregister_script('jquery-migrate');
        }
        
        // Move scripts to footer for better performance
        if (!is_admin()) {
            wp_scripts()->add_data('jquery', 'group', 1);
            wp_scripts()->add_data('jquery-core', 'group', 1);
        }
        
        // Combine and minify CSS (in production)
        if (!WP_DEBUG) {
            $this->combine_css_files();
        }
    }
    
    /**
     * Add resource hints for better loading
     */
    public function add_resource_hints($urls, $relation_type) {
        switch ($relation_type) {
            case 'dns-prefetch':
                $urls[] = '//fonts.googleapis.com';
                $urls[] = '//fonts.gstatic.com';
                break;
                
            case 'preconnect':
                $urls[] = array(
                    'href' => 'https://fonts.googleapis.com',
                    'crossorigin',
                );
                break;
        }
        
        return $urls;
    }
    
    /**
     * Add preload hints for critical resources
     */
    public function add_preload_hints() {
        // Preload critical CSS (using minified version)
        echo '<link rel="preload" href="' . get_template_directory_uri() . '/assets/dist/css/main.min.css" as="style" onload="this.onload=null;this.rel=\'stylesheet\'">' . "\n";
        
        // Preload hero image if set
        $hero_bg = get_theme_mod('nearmenus_hero_bg');
        if ($hero_bg && is_front_page()) {
            $hero_url = wp_get_attachment_url($hero_bg);
            if ($hero_url) {
                echo '<link rel="preload" href="' . esc_url($hero_url) . '" as="image">' . "\n";
            }
        }
        
        // Preload main JavaScript (using minified version)
        echo '<link rel="preload" href="' . get_template_directory_uri() . '/assets/dist/js/main.min.js" as="script">' . "\n";
    }
    
    /**
     * Add async/defer attributes to scripts
     */
    public function add_async_defer_attributes($tag, $handle) {
        // Scripts to defer
        $defer_scripts = array(
            'nearmenus-main',
            'nearmenus-search',
        );
        
        // Scripts to async
        $async_scripts = array(
            'google-analytics',
            'facebook-pixel',
        );
        
        if (in_array($handle, $defer_scripts)) {
            return str_replace(' src', ' defer src', $tag);
        }
        
        if (in_array($handle, $async_scripts)) {
            return str_replace(' src', ' async src', $tag);
        }
        
        return $tag;
    }
    
    /**
     * Add lazy loading to images
     */
    public function add_lazy_loading($attr) {
        if (!is_admin() && !is_feed()) {
            $attr['loading'] = 'lazy';
            $attr['decoding'] = 'async';
        }
        return $attr;
    }
    
    /**
     * Add lazy loading to content images
     */
    public function add_lazy_loading_to_content($content) {
        if (!is_admin() && !is_feed()) {
            $content = preg_replace('/<img((?:[^>]*)?)>/i', '<img$1 loading="lazy" decoding="async">', $content);
        }
        return $content;
    }
    
    /**
     * Combine CSS files for production
     */
    private function combine_css_files() {
        $css_files = array(
            get_template_directory() . '/assets/css/main.css',
        );
        
        $combined_file = get_template_directory() . '/assets/css/combined.min.css';
        $combined_content = '';
        $needs_update = false;
        
        // Check if we need to rebuild
        if (!file_exists($combined_file)) {
            $needs_update = true;
        } else {
            $combined_time = filemtime($combined_file);
            foreach ($css_files as $css_file) {
                if (file_exists($css_file) && filemtime($css_file) > $combined_time) {
                    $needs_update = true;
                    break;
                }
            }
        }
        
        if ($needs_update) {
            foreach ($css_files as $css_file) {
                if (file_exists($css_file)) {
                    $content = file_get_contents($css_file);
                    $combined_content .= $this->minify_css($content);
                }
            }
            
            file_put_contents($combined_file, $combined_content);
        }
    }
    
    /**
     * Simple CSS minification
     */
    private function minify_css($css) {
        // Remove comments
        $css = preg_replace('!/\*[^*]*\*+([^/][^*]*\*+)*/!', '', $css);
        
        // Remove tabs, spaces, newlines, etc.
        $css = str_replace(array("\r\n", "\r", "\n", "\t", '  ', '    ', '    '), '', $css);
        
        return $css;
    }
    
    /**
     * Start HTML minification
     */
    public function start_html_minification() {
        if (!WP_DEBUG && !is_admin()) {
            ob_start(array($this, 'minify_html'));
        }
    }
    
    /**
     * Minify HTML output
     */
    public function minify_html($html) {
        // Don't minify if we're in debug mode
        if (WP_DEBUG) {
            return $html;
        }
        
        // Remove HTML comments (except IE conditional comments)
        $html = preg_replace('/<!--(?!\s*(?:\[if [^\]]+]|<!|>))(?:(?!-->).)*-->/s', '', $html);
        
        // Remove unnecessary whitespace
        $html = preg_replace('/\s+/', ' ', $html);
        $html = preg_replace('/>\s+</', '><', $html);
        
        return trim($html);
    }
    
    /**
     * Add critical CSS inline
     */
    public function add_critical_css() {
        if (is_front_page()) {
            ?>
            <style id="critical-css">
                /* Critical CSS for above-the-fold content */
                .site-header {
                    position: fixed;
                    top: 0;
                    width: 100%;
                    z-index: 1000;
                    background: white;
                    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
                }
                
                .hero-section {
                    min-height: 100vh;
                    display: flex;
                    align-items: center;
                    background-size: cover;
                    background-position: center;
                }
                
                .hero-content {
                    text-align: center;
                    color: white;
                    text-shadow: 0 2px 4px rgba(0,0,0,0.5);
                }
                
                .btn-primary {
                    background: var(--color-primary, #3b82f6);
                    color: white;
                    padding: 0.75rem 1.5rem;
                    border-radius: 0.375rem;
                    text-decoration: none;
                    display: inline-block;
                    transition: all 0.2s;
                }
                
                .btn-primary:hover {
                    transform: translateY(-1px);
                    box-shadow: 0 4px 8px rgba(0,0,0,0.2);
                }
            </style>
            <?php
        }
    }
    
    /**
     * Get performance metrics
     */
    public function get_performance_metrics() {
        return array(
            'cache_hits' => wp_cache_get_stats(),
            'query_count' => get_num_queries(),
            'memory_usage' => memory_get_peak_usage(true),
            'load_time' => timer_stop(0, 3),
        );
    }
}

// Initialize performance optimizations
new NearMenus_Performance();

/**
 * Helper function to get cached data
 */
function nearmenus_get_cached_data($key, $callback, $expiration = HOUR_IN_SECONDS) {
    $cached_data = get_transient($key);
    
    if (false === $cached_data) {
        $cached_data = call_user_func($callback);
        set_transient($key, $cached_data, $expiration);
    }
    
    return $cached_data;
}

/**
 * Clear all nearmenus caches
 */
function nearmenus_clear_all_caches() {
    global $wpdb;
    
    $wpdb->query(
        "DELETE FROM {$wpdb->options} WHERE option_name LIKE '_transient_nearmenus_%' OR option_name LIKE '_transient_timeout_nearmenus_%'"
    );
    
    // Clear any object cache
    if (function_exists('wp_cache_flush')) {
        wp_cache_flush();
    }
}