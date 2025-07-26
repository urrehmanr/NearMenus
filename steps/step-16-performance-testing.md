# Step 16: Performance Testing & Monitoring Implementation

## Overview
Implement comprehensive performance testing and monitoring systems for the **GPress** theme to ensure 95+ Lighthouse scores, optimal Core Web Vitals, and real-world performance excellence through automated testing, continuous monitoring, and intelligent conditional asset loading.

## Objectives
- Implement automated performance testing with Lighthouse and WebPageTest integration
- Create Real User Monitoring (RUM) system for actual user performance data
- Establish performance budgets with automatic enforcement and alerts
- Build conditional asset loading for testing tools and monitoring scripts
- Create comprehensive performance dashboard and analytics
- Implement continuous performance monitoring and optimization workflows

## What You'll Learn
- Advanced performance testing automation with conditional loading patterns
- Core Web Vitals monitoring and optimization strategies
- Real User Monitoring (RUM) implementation with data analytics
- Performance budget enforcement and alert systems
- Automated testing pipeline setup with CI/CD integration
- Progressive performance enhancement and optimization techniques

## Files Structure for This Step

### 📁 Files to CREATE
```
inc/
├── performance-validation.php  # Validates Smart Asset Manager effectiveness from Step 7
├── smart-asset-testing.php    # Tests conditional loading performance
├── optimization-monitoring.php # Monitors Step 7 optimization performance
└── performance-reporting.php   # Reports on Smart Asset Manager performance

assets/js/
├── optimization-validator.js   # Validates Step 7 Smart Asset Manager performance
├── asset-loading-monitor.js   # Monitors conditional loading effectiveness
├── performance-dashboard.js   # Dashboard for Step 7 optimization monitoring
└── core-web-vitals-tracker.js # Tracks Core Web Vitals achieved by Step 7

tools/
├── smart-asset-analyzer.php   # Analyzes Smart Asset Manager effectiveness
├── optimization-audit.php     # Audits Step 7 optimization performance
├── conditional-loading-test.js # Tests conditional loading scenarios
└── lighthouse-validation.js   # Validates 95+ Lighthouse scores from Step 7

tests/
├── step7-validation/
│   ├── smart-asset-tests.js   # Tests Smart Asset Manager functionality
│   ├── conditional-loading.js # Tests conditional loading effectiveness
│   ├── performance-targets.js # Validates performance targets from Step 7
│   └── optimization-verify.js # Verifies all Step 7 optimizations work

**Note**: This step validates and monitors the performance optimizations implemented in Step 7, rather than creating new optimization systems.

**Integration with Step 7**: Focuses on testing and validating the Smart Asset Management System effectiveness
```

### 📝 Files to UPDATE
```
functions.php              # Add performance testing system initialization
inc/theme-setup.php        # Add performance testing theme support features
inc/enqueue-scripts.php    # Add conditional performance testing asset loading
style.css                  # Add performance testing integration styles
README.md                  # Document performance testing and monitoring features
```

### 🎯 Optimization Features Implemented
- **Conditional Asset Loading**: Performance testing scripts load only for admin users and testing scenarios
- **Real User Monitoring**: Collect actual user performance data with privacy compliance
- **Performance Budgets**: Automatic enforcement with email alerts and CI/CD integration
- **Core Web Vitals Tracking**: Comprehensive CWV monitoring with historical data
- **Automated Testing**: Scheduled Lighthouse and WebPageTest automation
- **Progressive Enhancement**: Performance features enhance without blocking core functionality

## Step-by-Step Implementation

### 1. Create Performance Testing Management

### File: `inc/performance-testing.php`
```php
<?php
/**
 * Performance Testing Management for GPress Theme
 * Handles automated testing, monitoring, and conditional asset loading
 *
 * @package GPress
 * @subpackage Performance_Testing
 * @version 1.0.0
 * @since 1.0.0
 */

// Prevent direct access
defined('ABSPATH') || exit;

/**
 * GPress Performance Testing Manager
 * 
 * @since 1.0.0
 */
class GPress_Performance_Testing {

    /**
     * Initialize performance testing system
     *
     * @since 1.0.0
     */
    public static function init() {
        add_action('after_setup_theme', array(__CLASS__, 'setup_performance_testing'));
        add_action('wp_enqueue_scripts', array(__CLASS__, 'conditional_testing_assets'));
        add_action('admin_enqueue_scripts', array(__CLASS__, 'conditional_testing_assets'));
        add_action('init', array(__CLASS__, 'register_performance_endpoints'));
        add_action('init', array(__CLASS__, 'schedule_performance_tests'));
        
        // Admin interface
        add_action('admin_menu', array(__CLASS__, 'add_performance_menu'));
        add_action('admin_init', array(__CLASS__, 'setup_performance_admin'));
        
        // Performance monitoring hooks
        add_action('wp_head', array(__CLASS__, 'inject_performance_markers'), 1);
        add_action('wp_footer', array(__CLASS__, 'inject_performance_measurement'), 999);
        
        // AJAX handlers
        add_action('wp_ajax_gpress_run_performance_test', array(__CLASS__, 'handle_performance_test'));
        add_action('wp_ajax_gpress_collect_rum_data', array(__CLASS__, 'handle_rum_data'));
        add_action('wp_ajax_nopriv_gpress_collect_rum_data', array(__CLASS__, 'handle_rum_data'));
    }

/**
 * Conditional Performance Testing Asset Loading
 * Load testing scripts only when needed
 */
function gpress_conditional_testing_assets() {
    $load_testing_js = false;
    $load_monitoring_css = false;
    
    // Load testing assets for admin users
    if (current_user_can('manage_options')) {
        $load_testing_js = true;
        $load_monitoring_css = true;
    }
    
    // Load on testing pages
    if (isset($_GET['gpress_performance_test']) || 
        is_admin() && isset($_GET['page']) && $_GET['page'] === 'gpress-performance') {
        $load_testing_js = true;
        $load_monitoring_css = true;
    }
    
    // Load when performance monitoring is enabled
    if (get_theme_mod('enable_performance_monitoring', false)) {
        wp_enqueue_script(
            'gpress-rum-collector',
            GPRESS_THEME_URI . '/assets/js/rum-collector.js',
            array(),
            GPRESS_VERSION,
            true
        );
    }
    
    if ($load_testing_js) {
        wp_enqueue_script(
            'gpress-performance-tests',
            GPRESS_THEME_URI . '/assets/js/performance-tests.js',
            array(),
            GPRESS_VERSION,
            true
        );
        
        wp_enqueue_script(
            'gpress-performance-monitor',
            GPRESS_THEME_URI . '/assets/js/performance-monitor.js',
            array('gpress-performance-tests'),
            GPRESS_VERSION,
            true
        );
        
        // Localize script with performance settings
        wp_localize_script('gpress-performance-tests', 'gpressPerformance', array(
            'ajaxUrl' => admin_url('admin-ajax.php'),
            'nonce' => wp_create_nonce('gpress_performance_nonce'),
            'budgets' => gpress_get_performance_budgets(),
            'thresholds' => gpress_get_performance_thresholds(),
        ));
    }
    
    if ($load_monitoring_css) {
        wp_enqueue_style(
            'gpress-performance-testing',
            GPRESS_THEME_URI . '/assets/css/performance-testing.css',
            array(),
            GPRESS_VERSION
        );
        
        wp_enqueue_style(
            'gpress-monitoring-dashboard',
            GPRESS_THEME_URI . '/assets/css/monitoring-dashboard.css',
            array('gpress-performance-testing'),
            GPRESS_VERSION
        );
    }
}
add_action('wp_enqueue_scripts', 'gpress_conditional_testing_assets');
add_action('admin_enqueue_scripts', 'gpress_conditional_testing_assets');

/**
 * Setup Performance Testing
 */
function gpress_setup_performance_testing() {
    // Add admin menu for performance testing
    add_action('admin_menu', 'gpress_add_performance_testing_menu');
    
    // Add AJAX handlers for performance tests
    add_action('wp_ajax_gpress_run_lighthouse_test', 'gpress_handle_lighthouse_test');
    add_action('wp_ajax_gpress_run_webpagetest', 'gpress_handle_webpagetest');
    add_action('wp_ajax_gpress_collect_rum_data', 'gpress_handle_rum_data');
    add_action('wp_ajax_nopriv_gpress_collect_rum_data', 'gpress_handle_rum_data');
    
    // Add performance test hooks
    add_action('wp_head', 'gpress_inject_performance_markers');
    add_action('wp_footer', 'gpress_inject_performance_measurement');
}

/**
 * Register Performance API Endpoints
 */
function gpress_register_performance_endpoints() {
    add_action('rest_api_init', function() {
        register_rest_route('gpress/v1', '/performance/test', array(
            'methods' => 'POST',
            'callback' => 'gpress_rest_performance_test',
            'permission_callback' => function() {
                return current_user_can('manage_options');
            }
        ));
        
        register_rest_route('gpress/v1', '/performance/metrics', array(
            'methods' => 'GET',
            'callback' => 'gpress_rest_performance_metrics',
            'permission_callback' => function() {
                return current_user_can('manage_options');
            }
        ));
        
        register_rest_route('gpress/v1', '/performance/rum', array(
            'methods' => 'POST',
            'callback' => 'gpress_rest_rum_data',
            'permission_callback' => '__return_true'
        ));
    });
}

/**
 * Schedule Automated Performance Tests
 */
function gpress_schedule_performance_tests() {
    if (!wp_next_scheduled('gpress_automated_performance_test')) {
        wp_schedule_event(time(), 'daily', 'gpress_automated_performance_test');
    }
}
add_action('gpress_automated_performance_test', 'gpress_run_automated_tests');

/**
 * Add Performance Testing Admin Menu
 */
function gpress_add_performance_testing_menu() {
    add_theme_page(
        __('Performance Testing', 'gpress'),
        __('Performance', 'gpress'),
        'manage_options',
        'gpress-performance',
        'gpress_render_performance_page'
    );
}

/**
 * Render Performance Testing Page
 */
function gpress_render_performance_page() {
    $current_metrics = gpress_get_latest_performance_metrics();
    $historical_data = gpress_get_performance_history();
    ?>
    <div class="wrap gpress-performance-testing">
        <h1><?php _e('GPress Performance Testing', 'gpress'); ?></h1>
        
        <div class="gpress-performance-dashboard">
            <div class="performance-summary">
                <h2><?php _e('Current Performance Status', 'gpress'); ?></h2>
                <div class="metrics-grid">
                    <div class="metric-card lighthouse-score">
                        <h3><?php _e('Lighthouse Score', 'gpress'); ?></h3>
                        <div class="score"><?php echo esc_html($current_metrics['lighthouse_score'] ?? 'N/A'); ?></div>
                    </div>
                    <div class="metric-card lcp">
                        <h3><?php _e('LCP', 'gpress'); ?></h3>
                        <div class="score"><?php echo esc_html($current_metrics['lcp'] ?? 'N/A'); ?>s</div>
                    </div>
                    <div class="metric-card fid">
                        <h3><?php _e('FID', 'gpress'); ?></h3>
                        <div class="score"><?php echo esc_html($current_metrics['fid'] ?? 'N/A'); ?>ms</div>
                    </div>
                    <div class="metric-card cls">
                        <h3><?php _e('CLS', 'gpress'); ?></h3>
                        <div class="score"><?php echo esc_html($current_metrics['cls'] ?? 'N/A'); ?></div>
                    </div>
                </div>
            </div>
            
            <div class="performance-actions">
                <h2><?php _e('Performance Tests', 'gpress'); ?></h2>
                <div class="test-buttons">
                    <button id="run-lighthouse" class="button button-primary">
                        <?php _e('Run Lighthouse Test', 'gpress'); ?>
                    </button>
                    <button id="run-webpagetest" class="button button-secondary">
                        <?php _e('Run WebPageTest', 'gpress'); ?>
                    </button>
                    <button id="test-core-vitals" class="button button-secondary">
                        <?php _e('Test Core Web Vitals', 'gpress'); ?>
                    </button>
                    <button id="analyze-bundles" class="button button-secondary">
                        <?php _e('Analyze Asset Bundles', 'gpress'); ?>
                    </button>
                </div>
            </div>
            
            <div class="performance-budgets">
                <h2><?php _e('Performance Budgets', 'gpress'); ?></h2>
                <div id="budget-status"></div>
            </div>
            
            <div class="test-results">
                <h2><?php _e('Test Results', 'gpress'); ?></h2>
                <div id="test-output"></div>
            </div>
        </div>
    </div>
    <?php
}

/**
 * Get Performance Budgets
 */
function gpress_get_performance_budgets() {
    return array(
        'lighthouse_performance' => 95,
        'lighthouse_accessibility' => 100,
        'lighthouse_seo' => 100,
        'lcp' => 2.5,
        'fid' => 100,
        'cls' => 0.1,
        'total_css_size' => 50000, // 50KB
        'total_js_size' => 150000, // 150KB
        'total_image_size' => 500000, // 500KB
        'dom_elements' => 1500,
        'requests' => 50,
    );
}

/**
 * Get Performance Thresholds
 */
function gpress_get_performance_thresholds() {
    return array(
        'good' => array(
            'lcp' => 2.5,
            'fid' => 100,
            'cls' => 0.1,
        ),
        'needs_improvement' => array(
            'lcp' => 4.0,
            'fid' => 300,
            'cls' => 0.25,
        ),
    );
}

/**
 * Handle Lighthouse Test
 */
function gpress_handle_lighthouse_test() {
    check_ajax_referer('gpress_performance_nonce', 'nonce');
    
    $url = home_url('/');
    $results = gpress_run_lighthouse_test($url);
    
    if ($results) {
        gpress_save_performance_metrics($results);
        wp_send_json_success($results);
    } else {
        wp_send_json_error(__('Lighthouse test failed', 'gpress'));
    }
}

/**
 * Run Lighthouse Test
 */
function gpress_run_lighthouse_test($url) {
    // This would integrate with Lighthouse CI or Google PageSpeed Insights API
    $api_url = 'https://www.googleapis.com/pagespeedonline/v5/runPagespeed';
    $api_key = get_option('gpress_pagespeed_api_key');
    
    if (!$api_key) {
        return false;
    }
    
    $request_url = add_query_arg(array(
        'url' => $url,
        'key' => $api_key,
        'category' => 'performance',
        'category' => 'accessibility',
        'category' => 'seo',
        'strategy' => 'mobile',
    ), $api_url);
    
    $response = wp_remote_get($request_url, array(
        'timeout' => 60,
    ));
    
    if (is_wp_error($response)) {
        return false;
    }
    
    $body = wp_remote_retrieve_body($response);
    $data = json_decode($body, true);
    
    if (!isset($data['lighthouseResult'])) {
        return false;
    }
    
    $lighthouse = $data['lighthouseResult'];
    
    return array(
        'lighthouse_score' => $lighthouse['categories']['performance']['score'] * 100,
        'accessibility_score' => $lighthouse['categories']['accessibility']['score'] * 100,
        'seo_score' => $lighthouse['categories']['seo']['score'] * 100,
        'lcp' => $lighthouse['audits']['largest-contentful-paint']['displayValue'] ?? 0,
        'fid' => $lighthouse['audits']['max-potential-fid']['displayValue'] ?? 0,
        'cls' => $lighthouse['audits']['cumulative-layout-shift']['displayValue'] ?? 0,
        'timestamp' => current_time('timestamp'),
    );
}

/**
 * Save Performance Metrics
 */
function gpress_save_performance_metrics($metrics) {
    $history = get_option('gpress_performance_history', array());
    $history[] = $metrics;
    
    // Keep only last 30 days
    $thirty_days_ago = current_time('timestamp') - (30 * DAY_IN_SECONDS);
    $history = array_filter($history, function($metric) use ($thirty_days_ago) {
        return $metric['timestamp'] > $thirty_days_ago;
    });
    
    update_option('gpress_performance_history', $history);
    update_option('gpress_latest_performance_metrics', $metrics);
}

/**
 * Get Latest Performance Metrics
 */
function gpress_get_latest_performance_metrics() {
    return get_option('gpress_latest_performance_metrics', array());
}

/**
 * Get Performance History
 */
function gpress_get_performance_history() {
    return get_option('gpress_performance_history', array());
}

/**
 * Inject Performance Markers
 */
function gpress_inject_performance_markers() {
    if (!get_theme_mod('enable_performance_monitoring', false)) {
        return;
    }
    ?>
    <script>
    // Performance timing markers
    if (window.performance && window.performance.mark) {
        window.performance.mark('gpress-head-start');
    }
    </script>
    <?php
}

/**
 * Inject Performance Measurement
 */
function gpress_inject_performance_measurement() {
    if (!get_theme_mod('enable_performance_monitoring', false)) {
        return;
    }
    ?>
    <script>
    // Performance timing measurement
    if (window.performance && window.performance.mark) {
        window.performance.mark('gpress-footer-end');
        
        // Measure time from head start to footer end
        if (window.performance.measure) {
            window.performance.measure('gpress-page-load', 'gpress-head-start', 'gpress-footer-end');
        }
    }
    </script>
    <?php
}

/**
 * Run Automated Performance Tests
 */
function gpress_run_automated_tests() {
    $url = home_url('/');
    $results = gpress_run_lighthouse_test($url);
    
    if ($results) {
        gpress_save_performance_metrics($results);
        
        // Check performance budgets
        $budgets = gpress_get_performance_budgets();
        $violations = array();
        
        if ($results['lighthouse_score'] < $budgets['lighthouse_performance']) {
            $violations[] = 'Lighthouse Performance score below budget';
        }
        
        if ($results['lcp'] > $budgets['lcp']) {
            $violations[] = 'LCP exceeds budget';
        }
        
        if (!empty($violations)) {
            // Send alert email to admin
            gpress_send_performance_alert($violations, $results);
        }
    }
}

/**
 * Send Performance Alert
 */
function gpress_send_performance_alert($violations, $metrics) {
    $admin_email = get_option('admin_email');
    $subject = sprintf(__('[%s] Performance Budget Violation', 'gpress'), get_bloginfo('name'));
    
    $message = __('Performance budget violations detected:', 'gpress') . "\n\n";
    $message .= implode("\n", $violations) . "\n\n";
    $message .= __('Current metrics:', 'gpress') . "\n";
    $message .= sprintf(__('Lighthouse Score: %s', 'gpress'), $metrics['lighthouse_score']) . "\n";
    $message .= sprintf(__('LCP: %s', 'gpress'), $metrics['lcp']) . "\n";
    $message .= sprintf(__('FID: %s', 'gpress'), $metrics['fid']) . "\n";
    $message .= sprintf(__('CLS: %s', 'gpress'), $metrics['cls']) . "\n";
    
    wp_mail($admin_email, $subject, $message);
}
```

## 2. Create Real User Monitoring System

### File: `inc/performance-monitoring.php`
```php
<?php
/**
 * Real User Monitoring for GPress Theme
 *
 * @package GPress
 * @version 1.0.0
 */

// Prevent direct access
defined('ABSPATH') || exit;

/**
 * Initialize RUM System
 */
function gpress_init_rum_monitoring() {
    if (get_theme_mod('enable_performance_monitoring', false)) {
        gpress_setup_rum_collection();
        gpress_register_rum_endpoints();
    }
}
add_action('after_setup_theme', 'gpress_init_rum_monitoring');

/**
 * Setup RUM Data Collection
 */
function gpress_setup_rum_collection() {
    // Add RUM data collection to frontend
    add_action('wp_footer', 'gpress_inject_rum_collector', 999);
    
    // Add AJAX handler for RUM data
    add_action('wp_ajax_gpress_collect_rum_data', 'gpress_handle_rum_data');
    add_action('wp_ajax_nopriv_gpress_collect_rum_data', 'gpress_handle_rum_data');
    
    // Process RUM data periodically
    add_action('gpress_process_rum_data', 'gpress_process_rum_analytics');
    
    if (!wp_next_scheduled('gpress_process_rum_data')) {
        wp_schedule_event(time(), 'hourly', 'gpress_process_rum_data');
    }
}

/**
 * Register RUM API Endpoints
 */
function gpress_register_rum_endpoints() {
    add_action('rest_api_init', function() {
        register_rest_route('gpress/v1', '/rum/collect', array(
            'methods' => 'POST',
            'callback' => 'gpress_rest_rum_collect',
            'permission_callback' => '__return_true'
        ));
        
        register_rest_route('gpress/v1', '/rum/analytics', array(
            'methods' => 'GET',
            'callback' => 'gpress_rest_rum_analytics',
            'permission_callback' => function() {
                return current_user_can('manage_options');
            }
        ));
    });
}

/**
 * Inject RUM Data Collector
 */
function gpress_inject_rum_collector() {
    ?>
    <script id="gpress-rum-collector">
    (function() {
        'use strict';
        
        // Collect Core Web Vitals
        function collectWebVitals() {
            if (!window.PerformanceObserver) return;
            
            const vitals = {};
            
            // Largest Contentful Paint
            new PerformanceObserver((list) => {
                const entries = list.getEntries();
                const lastEntry = entries[entries.length - 1];
                vitals.lcp = lastEntry.startTime;
            }).observe({entryTypes: ['largest-contentful-paint']});
            
            // First Input Delay
            new PerformanceObserver((list) => {
                const entries = list.getEntries();
                entries.forEach((entry) => {
                    vitals.fid = entry.processingStart - entry.startTime;
                });
            }).observe({entryTypes: ['first-input']});
            
            // Cumulative Layout Shift
            let clsValue = 0;
            new PerformanceObserver((list) => {
                const entries = list.getEntries();
                entries.forEach((entry) => {
                    if (!entry.hadRecentInput) {
                        clsValue += entry.value;
                    }
                });
                vitals.cls = clsValue;
            }).observe({entryTypes: ['layout-shift']});
            
            // Send data after page load
            window.addEventListener('load', function() {
                setTimeout(() => {
                    sendRUMData(vitals);
                }, 2000);
            });
        }
        
        // Collect additional performance metrics
        function collectAdditionalMetrics() {
            const navigation = performance.getEntriesByType('navigation')[0];
            const paint = performance.getEntriesByType('paint');
            
            const metrics = {
                dns_lookup: navigation.domainLookupEnd - navigation.domainLookupStart,
                tcp_connect: navigation.connectEnd - navigation.connectStart,
                server_response: navigation.responseStart - navigation.requestStart,
                dom_load: navigation.domContentLoadedEventEnd - navigation.navigationStart,
                page_load: navigation.loadEventEnd - navigation.navigationStart,
                fcp: paint.find(entry => entry.name === 'first-contentful-paint')?.startTime || 0,
                resources_count: performance.getEntriesByType('resource').length,
                page_size: document.documentElement.outerHTML.length,
                viewport: window.innerWidth + 'x' + window.innerHeight,
                connection: navigator.connection?.effectiveType || 'unknown',
                device_memory: navigator.deviceMemory || 'unknown',
                user_agent: navigator.userAgent.substring(0, 200),
                url: window.location.href,
                timestamp: Date.now()
            };
            
            return metrics;
        }
        
        // Send RUM data to server
        function sendRUMData(vitals) {
            const metrics = collectAdditionalMetrics();
            const data = Object.assign({}, vitals, metrics);
            
            // Use sendBeacon if available, fallback to fetch
            if (navigator.sendBeacon) {
                navigator.sendBeacon(
                    '<?php echo admin_url('admin-ajax.php'); ?>',
                    new URLSearchParams({
                        action: 'gpress_collect_rum_data',
                        data: JSON.stringify(data),
                        nonce: '<?php echo wp_create_nonce('gpress_rum_nonce'); ?>'
                    })
                );
            } else {
                fetch('<?php echo admin_url('admin-ajax.php'); ?>', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded',
                    },
                    body: new URLSearchParams({
                        action: 'gpress_collect_rum_data',
                        data: JSON.stringify(data),
                        nonce: '<?php echo wp_create_nonce('gpress_rum_nonce'); ?>'
                    })
                }).catch(() => {}); // Silently fail
            }
        }
        
        // Initialize collection
        collectWebVitals();
        
        // Error tracking
        window.addEventListener('error', function(e) {
            const errorData = {
                type: 'javascript_error',
                message: e.message,
                filename: e.filename,
                lineno: e.lineno,
                colno: e.colno,
                url: window.location.href,
                timestamp: Date.now()
            };
            
            if (navigator.sendBeacon) {
                navigator.sendBeacon(
                    '<?php echo admin_url('admin-ajax.php'); ?>',
                    new URLSearchParams({
                        action: 'gpress_collect_rum_data',
                        data: JSON.stringify(errorData),
                        nonce: '<?php echo wp_create_nonce('gpress_rum_nonce'); ?>'
                    })
                );
            }
        });
        
    })();
    </script>
    <?php
}

/**
 * Handle RUM Data Collection
 */
function gpress_handle_rum_data() {
    if (!wp_verify_nonce($_POST['nonce'], 'gpress_rum_nonce')) {
        wp_die('Security check failed');
    }
    
    $data = json_decode(stripslashes($_POST['data']), true);
    
    if ($data) {
        gpress_store_rum_data($data);
    }
    
    wp_die(); // AJAX response
}

/**
 * Store RUM Data
 */
function gpress_store_rum_data($data) {
    global $wpdb;
    
    $table_name = $wpdb->prefix . 'gpress_rum_data';
    
    // Create table if it doesn't exist
    $charset_collate = $wpdb->get_charset_collate();
    $sql = "CREATE TABLE IF NOT EXISTS $table_name (
        id bigint(20) NOT NULL AUTO_INCREMENT,
        session_id varchar(32) NOT NULL,
        page_url text NOT NULL,
        metric_name varchar(50) NOT NULL,
        metric_value decimal(10,3) DEFAULT NULL,
        metric_data longtext DEFAULT NULL,
        user_agent text DEFAULT NULL,
        viewport varchar(20) DEFAULT NULL,
        connection_type varchar(20) DEFAULT NULL,
        device_memory varchar(20) DEFAULT NULL,
        timestamp bigint(20) NOT NULL,
        created_at datetime DEFAULT CURRENT_TIMESTAMP,
        PRIMARY KEY (id),
        KEY session_id (session_id),
        KEY metric_name (metric_name),
        KEY timestamp (timestamp)
    ) $charset_collate;";
    
    require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
    dbDelta($sql);
    
    // Generate session ID
    $session_id = wp_get_session_token() ?: md5(uniqid());
    
    // Store individual metrics
    $metrics_to_store = array('lcp', 'fid', 'cls', 'fcp', 'dom_load', 'page_load');
    
    foreach ($metrics_to_store as $metric) {
        if (isset($data[$metric])) {
            $wpdb->insert(
                $table_name,
                array(
                    'session_id' => $session_id,
                    'page_url' => $data['url'],
                    'metric_name' => $metric,
                    'metric_value' => floatval($data[$metric]),
                    'metric_data' => json_encode($data),
                    'user_agent' => substr($data['user_agent'], 0, 500),
                    'viewport' => $data['viewport'],
                    'connection_type' => $data['connection'],
                    'device_memory' => $data['device_memory'],
                    'timestamp' => intval($data['timestamp'])
                ),
                array('%s', '%s', '%s', '%f', '%s', '%s', '%s', '%s', '%s', '%d')
            );
        }
    }
    
    // Store error data separately
    if (isset($data['type']) && $data['type'] === 'javascript_error') {
        $error_table = $wpdb->prefix . 'gpress_js_errors';
        $error_sql = "CREATE TABLE IF NOT EXISTS $error_table (
            id bigint(20) NOT NULL AUTO_INCREMENT,
            message text NOT NULL,
            filename varchar(255) DEFAULT NULL,
            lineno int DEFAULT NULL,
            colno int DEFAULT NULL,
            page_url text NOT NULL,
            user_agent text DEFAULT NULL,
            timestamp bigint(20) NOT NULL,
            created_at datetime DEFAULT CURRENT_TIMESTAMP,
            PRIMARY KEY (id),
            KEY timestamp (timestamp)
        ) $charset_collate;";
        
        dbDelta($error_sql);
        
        $wpdb->insert(
            $error_table,
            array(
                'message' => $data['message'],
                'filename' => $data['filename'],
                'lineno' => $data['lineno'],
                'colno' => $data['colno'],
                'page_url' => $data['url'],
                'user_agent' => substr($data['user_agent'], 0, 500),
                'timestamp' => intval($data['timestamp'])
            ),
            array('%s', '%s', '%d', '%d', '%s', '%s', '%d')
        );
    }
}

/**
 * Process RUM Analytics
 */
function gpress_process_rum_analytics() {
    global $wpdb;
    
    $table_name = $wpdb->prefix . 'gpress_rum_data';
    $one_hour_ago = (time() - 3600) * 1000; // Convert to milliseconds
    
    // Calculate averages for the last hour
    $metrics = array('lcp', 'fid', 'cls', 'fcp', 'dom_load', 'page_load');
    $analytics = array();
    
    foreach ($metrics as $metric) {
        $result = $wpdb->get_row($wpdb->prepare(
            "SELECT 
                AVG(metric_value) as avg_value,
                MIN(metric_value) as min_value,
                MAX(metric_value) as max_value,
                COUNT(*) as sample_count
             FROM $table_name 
             WHERE metric_name = %s 
             AND timestamp > %d",
            $metric,
            $one_hour_ago
        ));
        
        if ($result && $result->sample_count > 0) {
            $analytics[$metric] = array(
                'avg' => round($result->avg_value, 2),
                'min' => round($result->min_value, 2),
                'max' => round($result->max_value, 2),
                'samples' => intval($result->sample_count)
            );
        }
    }
    
    // Store processed analytics
    if (!empty($analytics)) {
        $analytics['timestamp'] = time();
        $history = get_option('gpress_rum_analytics_history', array());
        $history[] = $analytics;
        
        // Keep only last 7 days
        $seven_days_ago = time() - (7 * DAY_IN_SECONDS);
        $history = array_filter($history, function($item) use ($seven_days_ago) {
            return $item['timestamp'] > $seven_days_ago;
        });
        
        update_option('gpress_rum_analytics_history', $history);
        update_option('gpress_latest_rum_analytics', $analytics);
    }
}

/**
 * Get RUM Analytics
 */
function gpress_get_rum_analytics() {
    return get_option('gpress_latest_rum_analytics', array());
}
```

## 3. Create Performance Testing JavaScript

### File: `assets/js/performance-tests.js`
```javascript
/**
 * Performance Testing Suite for GPress Theme
 * Client-side performance testing and monitoring
 */

document.addEventListener('DOMContentLoaded', function() {
    initPerformanceTests();
});

/**
 * Initialize Performance Testing
 */
function initPerformanceTests() {
    if (typeof gpressPerformance === 'undefined') {
        return;
    }
    
    setupTestButtons();
    initBudgetMonitoring();
    initContinuousMonitoring();
}

/**
 * Setup Test Buttons
 */
function setupTestButtons() {
    const lighthouseBtn = document.getElementById('run-lighthouse');
    const webPageTestBtn = document.getElementById('run-webpagetest');
    const coreVitalsBtn = document.getElementById('test-core-vitals');
    const bundleAnalyzerBtn = document.getElementById('analyze-bundles');
    
    if (lighthouseBtn) {
        lighthouseBtn.addEventListener('click', runLighthouseTest);
    }
    
    if (webPageTestBtn) {
        webPageTestBtn.addEventListener('click', runWebPageTest);
    }
    
    if (coreVitalsBtn) {
        coreVitalsBtn.addEventListener('click', testCoreWebVitals);
    }
    
    if (bundleAnalyzerBtn) {
        bundleAnalyzerBtn.addEventListener('click', analyzeBundles);
    }
}

/**
 * Run Lighthouse Test
 */
function runLighthouseTest() {
    const button = document.getElementById('run-lighthouse');
    const output = document.getElementById('test-output');
    
    setButtonLoading(button, true);
    output.innerHTML = '<div class="test-running">Running Lighthouse test...</div>';
    
    fetch(gpressPerformance.ajaxUrl, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: new URLSearchParams({
            action: 'gpress_run_lighthouse_test',
            nonce: gpressPerformance.nonce
        })
    })
    .then(response => response.json())
    .then(data => {
        setButtonLoading(button, false);
        
        if (data.success) {
            displayLighthouseResults(data.data);
            updateBudgetStatus();
        } else {
            output.innerHTML = `<div class="test-error">Error: ${data.data}</div>`;
        }
    })
    .catch(error => {
        setButtonLoading(button, false);
        output.innerHTML = `<div class="test-error">Error: ${error.message}</div>`;
    });
}

/**
 * Display Lighthouse Results
 */
function displayLighthouseResults(results) {
    const output = document.getElementById('test-output');
    
    const html = `
        <div class="lighthouse-results">
            <h3>Lighthouse Test Results</h3>
            <div class="results-grid">
                <div class="result-item">
                    <div class="score ${getScoreClass(results.lighthouse_score)}">${results.lighthouse_score}</div>
                    <div class="label">Performance</div>
                </div>
                <div class="result-item">
                    <div class="score ${getScoreClass(results.accessibility_score)}">${results.accessibility_score}</div>
                    <div class="label">Accessibility</div>
                </div>
                <div class="result-item">
                    <div class="score ${getScoreClass(results.seo_score)}">${results.seo_score}</div>
                    <div class="label">SEO</div>
                </div>
            </div>
            <div class="core-vitals">
                <h4>Core Web Vitals</h4>
                <div class="vitals-grid">
                    <div class="vital-item">
                        <div class="value ${getVitalClass('lcp', results.lcp)}">${results.lcp}</div>
                        <div class="label">LCP</div>
                    </div>
                    <div class="vital-item">
                        <div class="value ${getVitalClass('fid', results.fid)}">${results.fid}</div>
                        <div class="label">FID</div>
                    </div>
                    <div class="vital-item">
                        <div class="value ${getVitalClass('cls', results.cls)}">${results.cls}</div>
                        <div class="label">CLS</div>
                    </div>
                </div>
            </div>
            <div class="test-timestamp">
                Tested: ${new Date(results.timestamp * 1000).toLocaleString()}
            </div>
        </div>
    `;
    
    output.innerHTML = html;
}

/**
 * Test Core Web Vitals
 */
function testCoreWebVitals() {
    const button = document.getElementById('test-core-vitals');
    const output = document.getElementById('test-output');
    
    setButtonLoading(button, true);
    output.innerHTML = '<div class="test-running">Measuring Core Web Vitals...</div>';
    
    // Use Performance Observer API to measure Core Web Vitals
    const vitals = {};
    const promises = [];
    
    // Measure LCP
    if (window.PerformanceObserver) {
        promises.push(new Promise((resolve) => {
            new PerformanceObserver((list) => {
                const entries = list.getEntries();
                const lastEntry = entries[entries.length - 1];
                vitals.lcp = Math.round(lastEntry.startTime);
                resolve();
            }).observe({entryTypes: ['largest-contentful-paint']});
            
            // Timeout after 10 seconds
            setTimeout(() => resolve(), 10000);
        }));
        
        // Measure CLS
        let clsValue = 0;
        new PerformanceObserver((list) => {
            const entries = list.getEntries();
            entries.forEach((entry) => {
                if (!entry.hadRecentInput) {
                    clsValue += entry.value;
                }
            });
            vitals.cls = Math.round(clsValue * 1000) / 1000;
        }).observe({entryTypes: ['layout-shift']});
        
        // Measure FID (if available)
        new PerformanceObserver((list) => {
            const entries = list.getEntries();
            entries.forEach((entry) => {
                vitals.fid = Math.round(entry.processingStart - entry.startTime);
            });
        }).observe({entryTypes: ['first-input']});
    }
    
    // Measure additional metrics
    setTimeout(() => {
        const navigation = performance.getEntriesByType('navigation')[0];
        const paint = performance.getEntriesByType('paint');
        
        vitals.fcp = Math.round(paint.find(entry => entry.name === 'first-contentful-paint')?.startTime || 0);
        vitals.domLoad = Math.round(navigation.domContentLoadedEventEnd - navigation.navigationStart);
        vitals.pageLoad = Math.round(navigation.loadEventEnd - navigation.navigationStart);
        vitals.ttfb = Math.round(navigation.responseStart - navigation.requestStart);
        
        setButtonLoading(button, false);
        displayCoreVitalsResults(vitals);
    }, 3000);
}

/**
 * Display Core Web Vitals Results
 */
function displayCoreVitalsResults(vitals) {
    const output = document.getElementById('test-output');
    
    const html = `
        <div class="core-vitals-results">
            <h3>Core Web Vitals Measurement</h3>
            <div class="vitals-detailed">
                <div class="vital-row">
                    <span class="vital-name">Largest Contentful Paint (LCP)</span>
                    <span class="vital-value ${getVitalClass('lcp', vitals.lcp)}">${vitals.lcp}ms</span>
                    <span class="vital-status">${getVitalStatus('lcp', vitals.lcp)}</span>
                </div>
                <div class="vital-row">
                    <span class="vital-name">First Input Delay (FID)</span>
                    <span class="vital-value ${getVitalClass('fid', vitals.fid || 0)}">${vitals.fid || 'N/A'}ms</span>
                    <span class="vital-status">${getVitalStatus('fid', vitals.fid || 0)}</span>
                </div>
                <div class="vital-row">
                    <span class="vital-name">Cumulative Layout Shift (CLS)</span>
                    <span class="vital-value ${getVitalClass('cls', vitals.cls || 0)}">${vitals.cls || 'N/A'}</span>
                    <span class="vital-status">${getVitalStatus('cls', vitals.cls || 0)}</span>
                </div>
                <div class="vital-row additional">
                    <span class="vital-name">First Contentful Paint (FCP)</span>
                    <span class="vital-value">${vitals.fcp}ms</span>
                    <span class="vital-status">-</span>
                </div>
                <div class="vital-row additional">
                    <span class="vital-name">DOM Content Loaded</span>
                    <span class="vital-value">${vitals.domLoad}ms</span>
                    <span class="vital-status">-</span>
                </div>
                <div class="vital-row additional">
                    <span class="vital-name">Page Load Complete</span>
                    <span class="vital-value">${vitals.pageLoad}ms</span>
                    <span class="vital-status">-</span>
                </div>
                <div class="vital-row additional">
                    <span class="vital-name">Time to First Byte (TTFB)</span>
                    <span class="vital-value">${vitals.ttfb}ms</span>
                    <span class="vital-status">-</span>
                </div>
            </div>
        </div>
    `;
    
    output.innerHTML = html;
}

/**
 * Analyze Bundles
 */
function analyzeBundles() {
    const button = document.getElementById('analyze-bundles');
    const output = document.getElementById('test-output');
    
    setButtonLoading(button, true);
    output.innerHTML = '<div class="test-running">Analyzing asset bundles...</div>';
    
    // Analyze loaded resources
    const resources = performance.getEntriesByType('resource');
    const analysis = analyzeLoadedResources(resources);
    
    setTimeout(() => {
        setButtonLoading(button, false);
        displayBundleAnalysis(analysis);
    }, 1000);
}

/**
 * Analyze Loaded Resources
 */
function analyzeLoadedResources(resources) {
    const analysis = {
        css: { count: 0, totalSize: 0, files: [] },
        js: { count: 0, totalSize: 0, files: [] },
        images: { count: 0, totalSize: 0, files: [] },
        fonts: { count: 0, totalSize: 0, files: [] },
        other: { count: 0, totalSize: 0, files: [] }
    };
    
    resources.forEach(resource => {
        const size = resource.transferSize || resource.decodedBodySize || 0;
        const type = getResourceType(resource.name);
        
        analysis[type].count++;
        analysis[type].totalSize += size;
        analysis[type].files.push({
            name: resource.name.split('/').pop(),
            size: size,
            duration: Math.round(resource.duration)
        });
    });
    
    // Sort files by size
    Object.keys(analysis).forEach(type => {
        analysis[type].files.sort((a, b) => b.size - a.size);
        analysis[type].files = analysis[type].files.slice(0, 10); // Top 10
    });
    
    return analysis;
}

/**
 * Get Resource Type
 */
function getResourceType(url) {
    if (url.includes('.css')) return 'css';
    if (url.includes('.js')) return 'js';
    if (url.match(/\.(jpg|jpeg|png|gif|webp|svg)$/i)) return 'images';
    if (url.match(/\.(woff|woff2|ttf|otf)$/i)) return 'fonts';
    return 'other';
}

/**
 * Display Bundle Analysis
 */
function displayBundleAnalysis(analysis) {
    const output = document.getElementById('test-output');
    
    let html = `
        <div class="bundle-analysis">
            <h3>Asset Bundle Analysis</h3>
            <div class="analysis-summary">
                <div class="summary-grid">
    `;
    
    Object.keys(analysis).forEach(type => {
        const data = analysis[type];
        html += `
            <div class="summary-item">
                <div class="type">${type.toUpperCase()}</div>
                <div class="count">${data.count} files</div>
                <div class="size">${formatBytes(data.totalSize)}</div>
            </div>
        `;
    });
    
    html += '</div></div>';
    
    // Add detailed breakdown
    Object.keys(analysis).forEach(type => {
        const data = analysis[type];
        if (data.files.length > 0) {
            html += `
                <div class="type-breakdown">
                    <h4>${type.toUpperCase()} Files (${formatBytes(data.totalSize)})</h4>
                    <div class="file-list">
            `;
            
            data.files.forEach(file => {
                html += `
                    <div class="file-item">
                        <span class="file-name">${file.name}</span>
                        <span class="file-size">${formatBytes(file.size)}</span>
                        <span class="file-duration">${file.duration}ms</span>
                    </div>
                `;
            });
            
            html += '</div></div>';
        }
    });
    
    html += '</div>';
    output.innerHTML = html;
}

/**
 * Initialize Budget Monitoring
 */
function initBudgetMonitoring() {
    updateBudgetStatus();
    
    // Update budget status every 30 seconds
    setInterval(updateBudgetStatus, 30000);
}

/**
 * Update Budget Status
 */
function updateBudgetStatus() {
    const budgetContainer = document.getElementById('budget-status');
    if (!budgetContainer) return;
    
    const budgets = gpressPerformance.budgets;
    const currentMetrics = getCurrentMetrics();
    
    let html = '<div class="budget-items">';
    
    Object.keys(budgets).forEach(metric => {
        const budget = budgets[metric];
        const current = currentMetrics[metric] || 0;
        const isWithinBudget = checkBudget(metric, current, budget);
        const percentage = Math.round((current / budget) * 100);
        
        html += `
            <div class="budget-item ${isWithinBudget ? 'within-budget' : 'over-budget'}">
                <div class="budget-metric">${metric.replace(/_/g, ' ').toUpperCase()}</div>
                <div class="budget-values">
                    <span class="current">${formatMetricValue(metric, current)}</span>
                    <span class="separator">/</span>
                    <span class="budget">${formatMetricValue(metric, budget)}</span>
                </div>
                <div class="budget-percentage">${percentage}%</div>
                <div class="budget-bar">
                    <div class="budget-fill" style="width: ${Math.min(percentage, 100)}%"></div>
                </div>
            </div>
        `;
    });
    
    html += '</div>';
    budgetContainer.innerHTML = html;
}

/**
 * Get Current Metrics
 */
function getCurrentMetrics() {
    const navigation = performance.getEntriesByType('navigation')[0];
    const resources = performance.getEntriesByType('resource');
    
    const cssSize = resources
        .filter(r => r.name.includes('.css'))
        .reduce((sum, r) => sum + (r.transferSize || 0), 0);
    
    const jsSize = resources
        .filter(r => r.name.includes('.js'))
        .reduce((sum, r) => sum + (r.transferSize || 0), 0);
    
    const imageSize = resources
        .filter(r => r.name.match(/\.(jpg|jpeg|png|gif|webp|svg)$/i))
        .reduce((sum, r) => sum + (r.transferSize || 0), 0);
    
    return {
        lighthouse_performance: 0, // Would come from latest test
        lighthouse_accessibility: 0, // Would come from latest test
        lighthouse_seo: 0, // Would come from latest test
        lcp: 0, // Would be measured
        fid: 0, // Would be measured
        cls: 0, // Would be measured
        total_css_size: cssSize,
        total_js_size: jsSize,
        total_image_size: imageSize,
        dom_elements: document.getElementsByTagName('*').length,
        requests: resources.length + 1 // +1 for main document
    };
}

/**
 * Helper Functions
 */
function setButtonLoading(button, loading) {
    if (loading) {
        button.disabled = true;
        button.dataset.originalText = button.textContent;
        button.textContent = 'Running...';
        button.classList.add('loading');
    } else {
        button.disabled = false;
        button.textContent = button.dataset.originalText;
        button.classList.remove('loading');
    }
}

function getScoreClass(score) {
    if (score >= 90) return 'good';
    if (score >= 50) return 'average';
    return 'poor';
}

function getVitalClass(metric, value) {
    const thresholds = gpressPerformance.thresholds;
    
    if (value <= thresholds.good[metric]) return 'good';
    if (value <= thresholds.needs_improvement[metric]) return 'needs-improvement';
    return 'poor';
}

function getVitalStatus(metric, value) {
    const thresholds = gpressPerformance.thresholds;
    
    if (value <= thresholds.good[metric]) return 'Good';
    if (value <= thresholds.needs_improvement[metric]) return 'Needs Improvement';
    return 'Poor';
}

function checkBudget(metric, current, budget) {
    return current <= budget;
}

function formatMetricValue(metric, value) {
    if (metric.includes('size')) {
        return formatBytes(value);
    }
    if (metric.includes('score')) {
        return value;
    }
    if (metric === 'lcp' || metric === 'fcp') {
        return value + 's';
    }
    if (metric === 'fid') {
        return value + 'ms';
    }
    return value;
}

function formatBytes(bytes) {
    if (bytes === 0) return '0 B';
    const k = 1024;
    const sizes = ['B', 'KB', 'MB', 'GB'];
    const i = Math.floor(Math.log(bytes) / Math.log(k));
    return parseFloat((bytes / Math.pow(k, i)).toFixed(1)) + ' ' + sizes[i];
}

function initContinuousMonitoring() {
    // Implement continuous monitoring if enabled
    if (window.PerformanceObserver) {
        // Monitor long tasks
        new PerformanceObserver((list) => {
            const entries = list.getEntries();
            entries.forEach((entry) => {
                if (entry.duration > 50) {
                    console.warn('Long task detected:', entry.duration + 'ms');
                }
            });
        }).observe({entryTypes: ['longtask']});
        
        // Monitor memory usage (if available)
        if (performance.memory) {
            setInterval(() => {
                const memory = performance.memory;
                if (memory.usedJSHeapSize > memory.jsHeapSizeLimit * 0.8) {
                    console.warn('High memory usage detected');
                }
            }, 10000);
        }
    }
}
```

## 4. Update Functions.php

### File: `functions.php` (Update)
```php
// ... existing code ...

/**
 * Require Performance Testing Files
 */
require_once GPRESS_INC_DIR . '/performance-testing.php';
require_once GPRESS_INC_DIR . '/performance-monitoring.php';

/**
 * Add Performance Testing Support
 */
function gpress_performance_testing_support() {
    // Add performance testing customizer settings
    add_action('customize_register', 'gpress_performance_testing_customizer_settings');
}
add_action('after_setup_theme', 'gpress_performance_testing_support');

/**
 * Add Performance Testing Customizer Settings
 */
function gpress_performance_testing_customizer_settings($wp_customize) {
    // Performance Monitoring Section
    $wp_customize->add_section('gpress_performance_monitoring', array(
        'title' => __('Performance Monitoring', 'gpress'),
        'description' => __('Configure performance testing and monitoring features.', 'gpress'),
        'priority' => 40,
    ));
    
    // Enable Performance Monitoring
    $wp_customize->add_setting('enable_performance_monitoring', array(
        'default' => false,
        'sanitize_callback' => 'wp_validate_boolean',
    ));
    
    $wp_customize->add_control('enable_performance_monitoring', array(
        'label' => __('Enable Performance Monitoring', 'gpress'),
        'description' => __('Collect real user performance data for analysis.', 'gpress'),
        'section' => 'gpress_performance_monitoring',
        'type' => 'checkbox',
    ));
    
    // PageSpeed API Key
    $wp_customize->add_setting('pagespeed_api_key', array(
        'default' => '',
        'sanitize_callback' => 'sanitize_text_field',
    ));
    
    $wp_customize->add_control('pagespeed_api_key', array(
        'label' => __('Google PageSpeed API Key', 'gpress'),
        'description' => __('Required for automated Lighthouse testing.', 'gpress'),
        'section' => 'gpress_performance_monitoring',
        'type' => 'text',
    ));
}

// ... existing code ...
```

}

// Initialize the performance testing system
GPress_Performance_Testing::init();
```

### 2. Update Functions.php

Add the performance testing system integration:

```php
// ... existing code ...

/**
 * Load Performance Testing Components
 */
require_once GPRESS_INC_DIR . '/performance-testing.php';
require_once GPRESS_INC_DIR . '/performance-monitoring.php';
require_once GPRESS_INC_DIR . '/performance-budgets.php';
require_once GPRESS_INC_DIR . '/rum-analytics.php';

/**
 * Add Performance Testing Theme Support
 */
function gpress_performance_testing_support() {
    // Performance testing capabilities
    add_theme_support('gpress-performance-testing');
    add_theme_support('gpress-rum-monitoring');
    add_theme_support('gpress-performance-budgets');
    
    // Performance customizer integration
    add_action('customize_register', 'gpress_performance_customizer_settings');
}
add_action('after_setup_theme', 'gpress_performance_testing_support');

// ... existing code ...
```

### 3. Update README.md

Add performance testing documentation:

```markdown
## Performance Testing & Monitoring

The GPress theme includes comprehensive performance testing and monitoring capabilities:

### Features
- **Automated Testing**: Lighthouse and WebPageTest integration
- **Real User Monitoring**: Collect actual user performance data
- **Performance Budgets**: Automatic enforcement with alerts
- **Core Web Vitals Tracking**: Comprehensive CWV monitoring
- **Conditional Loading**: Performance assets load only when needed

### Setup
1. Get Google PageSpeed Insights API key
2. Add API key in Customizer > Performance Monitoring
3. Enable Performance Monitoring
4. Access admin panel: Appearance > Performance

### Performance Targets
- Lighthouse Performance: 95+
- Lighthouse Accessibility: 100
- Lighthouse SEO: 100
- LCP: < 2.5s
- FID: < 100ms
- CLS: < 0.1
```

## Testing This Step

### 1. **File Verification**
```bash
# Verify all performance testing files are created
ls -la inc/performance-testing.php
ls -la inc/performance-monitoring.php
ls -la inc/performance-budgets.php
ls -la inc/rum-analytics.php
ls -la assets/js/performance-tests.js
ls -la assets/js/rum-collector.js
ls -la assets/css/performance-testing.css

# Check PHP syntax
php -l inc/performance-testing.php
php -l inc/performance-monitoring.php
```

### 2. **Performance Testing Setup**
```bash
# Test theme activation
wp theme activate gpress

# Check performance menu exists
wp eval "echo (current_user_can('manage_options') && function_exists('GPress_Performance_Testing::init')) ? 'Performance testing ready' : 'Setup incomplete';"

# Verify database tables
wp db query "SHOW TABLES LIKE 'wp_gpress_rum_data';"
```

### 3. **Functionality Testing**
- **Admin Access**: Navigate to Appearance > Performance
- **Lighthouse Test**: Click "Run Lighthouse Test" button
- **Core Web Vitals**: Click "Test Core Web Vitals" button
- **RUM Collection**: Enable monitoring and check browser network tab
- **Budget Alerts**: Test budget violation triggers

### 4. **API Testing**
```bash
# Test performance endpoints
curl -X GET "http://your-site.test/wp-json/gpress/v1/performance/metrics" \
  -H "Content-Type: application/json"

# Test RUM data submission
curl -X POST "http://your-site.test/wp-json/gpress/v1/performance/rum" \
  -H "Content-Type: application/json" \
  -d '{"lcp":1.2,"fid":50,"cls":0.05}'
```

### 5. **Performance Validation**
```bash
# Run Lighthouse test
npx lighthouse http://your-site.test --output json

# Check Core Web Vitals
# Use Chrome DevTools > Lighthouse > Performance

# Monitor RUM data collection
wp db query "SELECT COUNT(*) FROM wp_gpress_rum_data WHERE created_at > DATE_SUB(NOW(), INTERVAL 1 DAY);"
```

### 6. **Dynamic Custom Post Types Performance Testing**
```bash
# Test custom post type performance (Step 11 Integration)
# Create test custom post types via admin interface
wp eval "
if (function_exists('GPress_Dynamic_Post_Types::ajax_create_post_type')) {
    \$_POST = array(
        'post_type_key' => 'product',
        'singular_name' => 'Product',
        'plural_name' => 'Products',
        'menu_icon' => 'dashicons-products',
        'has_archive' => true,
        'supports' => array('title', 'editor', 'thumbnail'),
        'auto_generate_templates' => true,
        'nonce' => wp_create_nonce('gpress_cpt_admin')
    );
    GPress_Dynamic_Post_Types::ajax_create_post_type();
    echo 'Custom post type created for testing';
} else {
    echo 'Dynamic post types system not available';
}
"

# Test CPT archive performance
npx lighthouse http://your-site.test/product/ --output json

# Test CPT single page performance  
wp post create --post_type=product --post_title="Test Product" --post_content="Test content" --post_status=publish
npx lighthouse http://your-site.test/product/test-product/ --output json

# Verify dynamic asset loading for CPTs
curl -s http://your-site.test/product/ | grep -q "dynamic-cpt.css"
echo $? # Should be 0 (found) when viewing custom post type pages
```

### 7. **Conditional Loading Test**
```bash
# Test admin-only asset loading
curl -s http://your-site.test | grep -q "performance-tests.js"
echo $? # Should be 1 (not found) for non-admin users

# Test monitoring asset loading when enabled
wp option update theme_mods_gpress '{"enable_performance_monitoring":true}'
```

## Expected Results

After completing this step, you should have:

### ✅ Performance Testing System
- Comprehensive performance testing management
- Real User Monitoring (RUM) data collection
- Performance budget enforcement with alerts
- Automated Lighthouse and WebPageTest integration

### ✅ Admin Interface
- Performance dashboard in admin area
- Real-time performance metrics display
- Test execution controls and results
- Performance budget status monitoring

### ✅ Conditional Asset Loading
- Performance scripts load only for admin users
- RUM collection respects user privacy settings
- Testing assets don't impact site performance
- Progressive enhancement for performance features

### ✅ API Integration
- REST API endpoints for performance data
- AJAX handlers for testing operations
- WebPageTest and Lighthouse automation
- RUM data collection and analysis

### ✅ Performance Optimization
- < 2.5s Largest Contentful Paint (LCP)
- < 100ms First Input Delay (FID)
- < 0.1 Cumulative Layout Shift (CLS)
- 95+ Lighthouse Performance Score
- Automated performance monitoring and alerts

## Next Step
Continue to **Step 17: Cross-Browser Testing & Compatibility** to implement comprehensive cross-browser testing and ensure consistent functionality across all major browsers and devices.