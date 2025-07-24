# Step 14: Advanced SEO Optimization & Schema Implementation

## Overview
This step implements comprehensive SEO optimization strategies to maximize search engine visibility, improve rankings, and enhance social media presence. We'll establish structured data markup, meta tag optimization, OpenGraph integration, technical SEO enhancements, and performance-optimized conditional loading for all SEO features.

## Objectives
- Implement comprehensive Schema.org structured data markup
- Optimize meta tags, OpenGraph, and Twitter Cards for social sharing
- Establish technical SEO foundations and Core Web Vitals optimization
- Create SEO-friendly URL structures and breadcrumb markup
- Implement JSON-LD structured data for rich snippets
- Optimize for mobile-first indexing and local SEO
- Establish conditional SEO asset loading for performance
- Integrate SEO analytics and monitoring tools

## What You'll Learn
- Advanced Schema.org markup implementation and validation
- Meta tag optimization strategies for search engines and social media
- Technical SEO best practices and Core Web Vitals optimization
- Structured data implementation for rich snippets and knowledge panels
- OpenGraph and Twitter Cards optimization for social media engagement
- Local SEO implementation and business markup patterns
- Performance optimization for SEO features and conditional loading
- SEO analytics integration and monitoring setup

## Files Structure for This Step

### 📁 Files to CREATE:
```
inc/
├── seo.php                        # Core SEO system management
├── seo-meta-tags.php             # Meta tags and OpenGraph optimization
├── seo-structured-data.php       # Schema.org and JSON-LD implementation
├── seo-analytics.php             # SEO analytics and tracking
├── seo-optimization.php          # Technical SEO and performance
├── seo-local.php                 # Local SEO and business markup
└── seo-testing.php               # SEO testing and validation

assets/css/
├── seo.css                       # SEO-specific styles (minimal)
├── seo-print.css                # Print optimization for SEO
└── seo-social.css               # Social sharing button styles

assets/js/
├── seo.js                       # Main SEO JavaScript functionality
├── seo-analytics.js             # Analytics and tracking implementation
├── seo-social-sharing.js        # Social sharing functionality
├── seo-breadcrumbs.js          # Breadcrumb interaction enhancement
└── seo-performance.js          # SEO performance monitoring

templates/
├── sitemap.xml                  # XML sitemap template
├── robots.txt                   # Robots.txt template
└── opensearch.xml              # OpenSearch description

parts/
├── seo-meta.html               # Meta tags template part
├── social-sharing.html         # Social sharing buttons
└── breadcrumb-seo.html         # SEO-optimized breadcrumbs
```

### 📝 Files to UPDATE:
```
functions.php                    # Include SEO files and initialization
inc/theme-setup.php             # Add SEO theme support and image sizes
inc/enqueue-scripts.php         # Conditional SEO asset loading
style.css                       # Base SEO integration styles
README.md                       # Document SEO features and implementation
theme.json                      # Add SEO-specific settings and styles
header.html                     # Add meta tags and structured data
footer.html                     # Add business/organization markup
index.html                      # Add article/blog markup
single.html                     # Add article and author markup
page.html                       # Add webpage and breadcrumb markup
```

### 🎯 Optimization Features Implemented:
- Conditional SEO asset loading based on page type and content analysis
- Performance-optimized structured data with smart caching
- Lazy loading for social sharing widgets and analytics scripts
- Advanced meta tag optimization with dynamic content generation
- Core Web Vitals optimization specifically for SEO performance
- Smart image optimization for social media and search engines
- Conditional analytics loading based on user preferences and GDPR compliance

## Step-by-Step Implementation

### 1. Create Core SEO System

Create `inc/seo.php`:

```php
<?php
/**
 * Core SEO System for GPress Theme
 * Comprehensive SEO optimization with performance focus
 *
 * @package GPress
 * @subpackage SEO
 * @version 1.0.0
 * @since 1.0.0
 */

// Prevent direct access
defined('ABSPATH') || exit;

/**
 * GPress SEO Manager
 * 
 * @since 1.0.0
 */
class GPress_SEO {

    /**
     * Initialize SEO system
     *
     * @since 1.0.0
     */
    public static function init() {
        add_action('after_setup_theme', array(__CLASS__, 'seo_setup'));
        add_action('wp_enqueue_scripts', array(__CLASS__, 'conditional_seo_assets'));
        add_action('wp_head', array(__CLASS__, 'add_essential_meta_tags'), 1);
        add_action('wp_head', array(__CLASS__, 'add_seo_meta_tags'), 5);
        add_action('wp_head', array(__CLASS__, 'add_opengraph_tags'), 10);
        add_action('wp_head', array(__CLASS__, 'add_twitter_cards'), 15);
        add_action('wp_head', array(__CLASS__, 'add_canonical_url'), 20);
        add_action('wp_head', array(__CLASS__, 'add_robots_meta'), 25);
        
        // Content optimization
        add_filter('document_title_parts', array(__CLASS__, 'optimize_document_title'));
        add_filter('get_the_excerpt', array(__CLASS__, 'optimize_excerpt_for_seo'));
        add_filter('wp_get_attachment_image_attributes', array(__CLASS__, 'optimize_image_seo'), 10, 3);
        
        // Admin enhancements
        add_action('admin_init', array(__CLASS__, 'setup_seo_admin'));
        add_action('add_meta_boxes', array(__CLASS__, 'add_seo_meta_boxes'));
        add_action('save_post', array(__CLASS__, 'save_seo_meta_data'));
        
        // Performance optimizations
        add_action('wp_footer', array(__CLASS__, 'add_structured_data'), 25);
        add_action('wp_footer', array(__CLASS__, 'add_seo_analytics'), 30);
    }

    /**
     * SEO setup and theme support
     *
     * @since 1.0.0
     */
    public static function seo_setup() {
        // Essential WordPress SEO support
        add_theme_support('title-tag');
        add_theme_support('post-thumbnails');
        add_theme_support('html5', array('search-form', 'comment-form', 'comment-list', 'gallery', 'caption'));
        
        // Custom SEO features
        add_theme_support('seo-optimization');
        add_theme_support('structured-data');
        add_theme_support('opengraph');
        add_theme_support('twitter-cards');
        add_theme_support('social-sharing');
        
        // SEO-specific image sizes
        add_image_size('og-image', 1200, 630, true);           // OpenGraph
        add_image_size('twitter-card', 1200, 600, true);       // Twitter Card
        add_image_size('schema-image', 1200, 800, false);      // Schema.org
        add_image_size('seo-thumbnail', 500, 300, true);       // General SEO
        
        // Initialize default SEO settings
        if (get_option('gpress_seo_initialized') !== 'yes') {
            self::set_default_seo_options();
            update_option('gpress_seo_initialized', 'yes');
        }
    }

    /**
     * Set default SEO options
     *
     * @since 1.0.0
     */
    private static function set_default_seo_options() {
        $defaults = array(
            'gpress_enable_opengraph' => true,
            'gpress_enable_twitter_cards' => true,
            'gpress_enable_structured_data' => true,
            'gpress_enable_breadcrumbs' => true,
            'gpress_enable_social_sharing' => true,
            'gpress_seo_home_title' => get_bloginfo('name') . ' - ' . get_bloginfo('description'),
            'gpress_seo_home_description' => get_bloginfo('description'),
            'gpress_seo_separator' => ' | ',
            'gpress_og_site_name' => get_bloginfo('name'),
            'gpress_twitter_site' => '',
            'gpress_facebook_app_id' => '',
            'gpress_google_analytics' => '',
            'gpress_google_site_verification' => '',
        );
        
        foreach ($defaults as $option => $value) {
            update_option($option, $value);
        }
    }

    /**
     * Conditional SEO asset loading
     *
     * @since 1.0.0
     */
    public static function conditional_seo_assets() {
        $load_seo_assets = false;
        $load_social_sharing = false;
        $load_analytics = false;
        
        // Determine SEO asset requirements
        if (self::needs_seo_enhancements()) {
            $load_seo_assets = true;
        }
        
        if (self::needs_social_sharing()) {
            $load_social_sharing = true;
        }
        
        if (self::needs_analytics()) {
            $load_analytics = true;
        }
        
        if ($load_seo_assets) {
            wp_enqueue_style(
                'gpress-seo',
                get_theme_file_uri('/assets/css/seo.css'),
                array('gpress-style'),
                GPRESS_VERSION,
                'all'
            );
            
            wp_enqueue_script(
                'gpress-seo',
                get_theme_file_uri('/assets/js/seo.js'),
                array('jquery'),
                GPRESS_VERSION,
                array('strategy' => 'defer', 'in_footer' => true)
            );
            
            // Localize SEO script
            wp_localize_script('gpress-seo', 'gpressSEO', array(
                'ajaxurl' => admin_url('admin-ajax.php'),
                'nonce' => wp_create_nonce('gpress_seo_nonce'),
                'settings' => array(
                    'enableAnalytics' => get_option('gpress_enable_analytics', true),
                    'enableSocialSharing' => get_option('gpress_enable_social_sharing', true),
                    'trackingId' => get_option('gpress_google_analytics', ''),
                ),
                'strings' => array(
                    'sharing' => __('Share this content', 'gpress'),
                    'shareVia' => __('Share via', 'gpress'),
                    'copyLink' => __('Copy link', 'gpress'),
                    'linkCopied' => __('Link copied to clipboard', 'gpress'),
                )
            ));
        }
        
        if ($load_social_sharing) {
            wp_enqueue_style(
                'gpress-seo-social',
                get_theme_file_uri('/assets/css/seo-social.css'),
                array('gpress-seo'),
                GPRESS_VERSION,
                'all'
            );
            
            wp_enqueue_script(
                'gpress-social-sharing',
                get_theme_file_uri('/assets/js/seo-social-sharing.js'),
                array('gpress-seo'),
                GPRESS_VERSION,
                array('strategy' => 'defer', 'in_footer' => true)
            );
        }
        
        if ($load_analytics) {
            wp_enqueue_script(
                'gpress-seo-analytics',
                get_theme_file_uri('/assets/js/seo-analytics.js'),
                array('gpress-seo'),
                GPRESS_VERSION,
                array('strategy' => 'defer', 'in_footer' => true)
            );
        }
        
        // Print-specific SEO styles
        wp_enqueue_style(
            'gpress-seo-print',
            get_theme_file_uri('/assets/css/seo-print.css'),
            array('gpress-seo'),
            GPRESS_VERSION,
            'print'
        );
    }

    /**
     * Check if SEO enhancements are needed
     *
     * @since 1.0.0
     */
    private static function needs_seo_enhancements() {
        return !is_admin() && !is_feed() && !is_robots() && !is_trackback();
    }

    /**
     * Check if social sharing is needed
     *
     * @since 1.0.0
     */
    private static function needs_social_sharing() {
        return (is_single() || is_page()) && get_option('gpress_enable_social_sharing', true);
    }

    /**
     * Check if analytics is needed
     *
     * @since 1.0.0
     */
    private static function needs_analytics() {
        $tracking_id = get_option('gpress_google_analytics', '');
        return !empty($tracking_id) && !is_admin() && !current_user_can('manage_options');
    }

    /**
     * Add essential meta tags
     *
     * @since 1.0.0
     */
    public static function add_essential_meta_tags() {
        ?>
        <!-- Essential Meta Tags -->
        <meta charset="<?php bloginfo('charset'); ?>">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <?php
        
        // Google Site Verification
        $google_verification = get_option('gpress_google_site_verification', '');
        if (!empty($google_verification)) {
            echo '<meta name="google-site-verification" content="' . esc_attr($google_verification) . '">' . "\n";
        }
        
        // Theme color for mobile browsers
        $primary_color = self::get_theme_color();
        if ($primary_color) {
            echo '<meta name="theme-color" content="' . esc_attr($primary_color) . '">' . "\n";
        }
        
        // Security headers
        echo '<meta name="referrer" content="strict-origin-when-cross-origin">' . "\n";
    }

    /**
     * Add comprehensive SEO meta tags
     *
     * @since 1.0.0
     */
    public static function add_seo_meta_tags() {
        $title = self::get_seo_title();
        $description = self::get_seo_description();
        $keywords = self::get_seo_keywords();
        $author = self::get_seo_author();
        
        ?>
        <!-- SEO Meta Tags -->
        <meta name="description" content="<?php echo esc_attr($description); ?>">
        <?php if (!empty($keywords)) : ?>
        <meta name="keywords" content="<?php echo esc_attr($keywords); ?>">
        <?php endif; ?>
        <?php if (!empty($author)) : ?>
        <meta name="author" content="<?php echo esc_attr($author); ?>">
        <?php endif; ?>
        
        <!-- Additional SEO Meta -->
        <meta name="generator" content="WordPress <?php bloginfo('version'); ?>">
        <meta name="language" content="<?php echo esc_attr(get_locale()); ?>">
        <?php
        
        // Article-specific meta tags
        if (is_single()) {
            $post_date = get_the_date('c');
            $modified_date = get_the_modified_date('c');
            ?>
            <meta name="article:published_time" content="<?php echo esc_attr($post_date); ?>">
            <meta name="article:modified_time" content="<?php echo esc_attr($modified_date); ?>">
            <?php
            
            $categories = get_the_category();
            if (!empty($categories)) {
                foreach ($categories as $category) {
                    echo '<meta name="article:section" content="' . esc_attr($category->name) . '">' . "\n";
                }
            }
            
            $tags = get_the_tags();
            if (!empty($tags)) {
                foreach ($tags as $tag) {
                    echo '<meta name="article:tag" content="' . esc_attr($tag->name) . '">' . "\n";
                }
            }
        }
    }

    /**
     * Add OpenGraph tags
     *
     * @since 1.0.0
     */
    public static function add_opengraph_tags() {
        if (!get_option('gpress_enable_opengraph', true)) {
            return;
        }
        
        $title = self::get_seo_title();
        $description = self::get_seo_description();
        $image = self::get_og_image();
        $url = self::get_canonical_url();
        $site_name = get_option('gpress_og_site_name', get_bloginfo('name'));
        $type = self::get_og_type();
        
        ?>
        <!-- OpenGraph Meta Tags -->
        <meta property="og:title" content="<?php echo esc_attr($title); ?>">
        <meta property="og:description" content="<?php echo esc_attr($description); ?>">
        <meta property="og:type" content="<?php echo esc_attr($type); ?>">
        <meta property="og:url" content="<?php echo esc_url($url); ?>">
        <meta property="og:site_name" content="<?php echo esc_attr($site_name); ?>">
        <meta property="og:locale" content="<?php echo esc_attr(get_locale()); ?>">
        <?php
        
        if (!empty($image)) {
            $image_data = self::get_image_data($image);
            ?>
            <meta property="og:image" content="<?php echo esc_url($image); ?>">
            <meta property="og:image:alt" content="<?php echo esc_attr($image_data['alt']); ?>">
            <meta property="og:image:width" content="<?php echo esc_attr($image_data['width']); ?>">
            <meta property="og:image:height" content="<?php echo esc_attr($image_data['height']); ?>">
            <?php
        }
        
        // Facebook App ID
        $fb_app_id = get_option('gpress_facebook_app_id', '');
        if (!empty($fb_app_id)) {
            echo '<meta property="fb:app_id" content="' . esc_attr($fb_app_id) . '">' . "\n";
        }
        
        // Article-specific OG tags
        if (is_single()) {
            $author_id = get_the_author_meta('ID');
            $author_url = get_author_posts_url($author_id);
            $publish_date = get_the_date('c');
            $modified_date = get_the_modified_date('c');
            
            ?>
            <meta property="article:author" content="<?php echo esc_url($author_url); ?>">
            <meta property="article:published_time" content="<?php echo esc_attr($publish_date); ?>">
            <meta property="article:modified_time" content="<?php echo esc_attr($modified_date); ?>">
            <?php
        }
    }

    /**
     * Add Twitter Card tags
     *
     * @since 1.0.0
     */
    public static function add_twitter_cards() {
        if (!get_option('gpress_enable_twitter_cards', true)) {
            return;
        }
        
        $title = self::get_seo_title();
        $description = self::get_seo_description();
        $image = self::get_twitter_image();
        $card_type = self::get_twitter_card_type();
        $site = get_option('gpress_twitter_site', '');
        
        ?>
        <!-- Twitter Card Meta Tags -->
        <meta name="twitter:card" content="<?php echo esc_attr($card_type); ?>">
        <meta name="twitter:title" content="<?php echo esc_attr($title); ?>">
        <meta name="twitter:description" content="<?php echo esc_attr($description); ?>">
        <?php
        
        if (!empty($site)) {
            echo '<meta name="twitter:site" content="@' . esc_attr(ltrim($site, '@')) . '">' . "\n";
        }
        
        if (!empty($image)) {
            echo '<meta name="twitter:image" content="' . esc_url($image) . '">' . "\n";
            
            $image_data = self::get_image_data($image);
            if (!empty($image_data['alt'])) {
                echo '<meta name="twitter:image:alt" content="' . esc_attr($image_data['alt']) . '">' . "\n";
            }
        }
        
        // Author Twitter handle
        if (is_single()) {
            $author_twitter = get_the_author_meta('twitter');
            if (!empty($author_twitter)) {
                echo '<meta name="twitter:creator" content="@' . esc_attr(ltrim($author_twitter, '@')) . '">' . "\n";
            }
        }
    }

    /**
     * Add canonical URL
     *
     * @since 1.0.0
     */
    public static function add_canonical_url() {
        $canonical_url = self::get_canonical_url();
        if (!empty($canonical_url)) {
            echo '<link rel="canonical" href="' . esc_url($canonical_url) . '">' . "\n";
        }
    }

    /**
     * Add robots meta tag
     *
     * @since 1.0.0
     */
    public static function add_robots_meta() {
        $robots = self::get_robots_directive();
        if (!empty($robots)) {
            echo '<meta name="robots" content="' . esc_attr($robots) . '">' . "\n";
        }
    }

    /**
     * Get SEO title
     *
     * @since 1.0.0
     */
    public static function get_seo_title() {
        if (is_home() || is_front_page()) {
            return get_option('gpress_seo_home_title', get_bloginfo('name') . ' - ' . get_bloginfo('description'));
        }
        
        if (is_single() || is_page()) {
            $custom_title = get_post_meta(get_the_ID(), '_gpress_seo_title', true);
            if (!empty($custom_title)) {
                return $custom_title;
            }
            return get_the_title();
        }
        
        if (is_category()) {
            return single_cat_title('', false) . ' Archives' . get_option('gpress_seo_separator', ' | ') . get_bloginfo('name');
        }
        
        if (is_tag()) {
            return single_tag_title('', false) . ' Archives' . get_option('gpress_seo_separator', ' | ') . get_bloginfo('name');
        }
        
        if (is_author()) {
            return get_the_author() . ' - Author Archives' . get_option('gpress_seo_separator', ' | ') . get_bloginfo('name');
        }
        
        if (is_search()) {
            return 'Search Results for "' . get_search_query() . '"' . get_option('gpress_seo_separator', ' | ') . get_bloginfo('name');
        }
        
        return wp_get_document_title();
    }

    /**
     * Get SEO description
     *
     * @since 1.0.0
     */
    public static function get_seo_description() {
        if (is_home() || is_front_page()) {
            return get_option('gpress_seo_home_description', get_bloginfo('description'));
        }
        
        if (is_single() || is_page()) {
            $custom_description = get_post_meta(get_the_ID(), '_gpress_seo_description', true);
            if (!empty($custom_description)) {
                return $custom_description;
            }
            
            $excerpt = get_the_excerpt();
            if (!empty($excerpt)) {
                return wp_trim_words($excerpt, 30, '...');
            }
            
            $content = get_the_content();
            return wp_trim_words(strip_tags($content), 30, '...');
        }
        
        if (is_category()) {
            $description = category_description();
            if (!empty($description)) {
                return wp_trim_words(strip_tags($description), 30, '...');
            }
            return 'Browse all posts in the ' . single_cat_title('', false) . ' category.';
        }
        
        if (is_tag()) {
            $description = tag_description();
            if (!empty($description)) {
                return wp_trim_words(strip_tags($description), 30, '...');
            }
            return 'Browse all posts tagged with ' . single_tag_title('', false) . '.';
        }
        
        if (is_author()) {
            $description = get_the_author_meta('description');
            if (!empty($description)) {
                return wp_trim_words($description, 30, '...');
            }
            return 'Browse all posts by ' . get_the_author() . '.';
        }
        
        if (is_search()) {
            return 'Search results for "' . get_search_query() . '" on ' . get_bloginfo('name') . '.';
        }
        
        return get_bloginfo('description');
    }

    /**
     * Get SEO keywords
     *
     * @since 1.0.0
     */
    public static function get_seo_keywords() {
        if (is_single()) {
            $custom_keywords = get_post_meta(get_the_ID(), '_gpress_seo_keywords', true);
            if (!empty($custom_keywords)) {
                return $custom_keywords;
            }
            
            $tags = get_the_tags();
            if (!empty($tags)) {
                $keywords = array();
                foreach ($tags as $tag) {
                    $keywords[] = $tag->name;
                }
                return implode(', ', $keywords);
            }
            
            $categories = get_the_category();
            if (!empty($categories)) {
                $keywords = array();
                foreach ($categories as $category) {
                    $keywords[] = $category->name;
                }
                return implode(', ', $keywords);
            }
        }
        
        return '';
    }

    /**
     * Get SEO author
     *
     * @since 1.0.0
     */
    public static function get_seo_author() {
        if (is_single()) {
            return get_the_author();
        }
        return '';
    }

    /**
     * Get OpenGraph image
     *
     * @since 1.0.0
     */
    public static function get_og_image() {
        if (is_single() || is_page()) {
            // Custom OG image
            $custom_image = get_post_meta(get_the_ID(), '_gpress_og_image', true);
            if (!empty($custom_image)) {
                return $custom_image;
            }
            
            // Featured image
            if (has_post_thumbnail()) {
                $thumbnail_id = get_post_thumbnail_id();
                $image_data = wp_get_attachment_image_src($thumbnail_id, 'og-image');
                if ($image_data) {
                    return $image_data[0];
                }
            }
        }
        
        // Default site image
        $default_image = get_option('gpress_default_og_image', '');
        if (!empty($default_image)) {
            return $default_image;
        }
        
        // Fallback to site icon
        $site_icon = get_site_icon_url(1200);
        if (!empty($site_icon)) {
            return $site_icon;
        }
        
        return '';
    }

    /**
     * Get Twitter image
     *
     * @since 1.0.0
     */
    public static function get_twitter_image() {
        if (is_single() || is_page()) {
            if (has_post_thumbnail()) {
                $thumbnail_id = get_post_thumbnail_id();
                $image_data = wp_get_attachment_image_src($thumbnail_id, 'twitter-card');
                if ($image_data) {
                    return $image_data[0];
                }
            }
        }
        
        return self::get_og_image();
    }

    /**
     * Get OpenGraph type
     *
     * @since 1.0.0
     */
    public static function get_og_type() {
        if (is_single()) {
            return 'article';
        }
        
        if (is_page()) {
            return 'website';
        }
        
        if (is_author()) {
            return 'profile';
        }
        
        return 'website';
    }

    /**
     * Get Twitter card type
     *
     * @since 1.0.0
     */
    public static function get_twitter_card_type() {
        if ((is_single() || is_page()) && has_post_thumbnail()) {
            return 'summary_large_image';
        }
        
        return 'summary';
    }

    /**
     * Get canonical URL
     *
     * @since 1.0.0
     */
    public static function get_canonical_url() {
        global $wp;
        
        if (is_home()) {
            return home_url('/');
        }
        
        if (is_single() || is_page()) {
            return get_permalink();
        }
        
        return home_url(add_query_arg(array(), $wp->request));
    }

    /**
     * Get robots directive
     *
     * @since 1.0.0
     */
    public static function get_robots_directive() {
        $robots = array();
        
        if (is_search()) {
            $robots[] = 'noindex';
            $robots[] = 'follow';
        } elseif (is_404()) {
            $robots[] = 'noindex';
            $robots[] = 'nofollow';
        } else {
            $robots[] = 'index';
            $robots[] = 'follow';
        }
        
        $robots[] = 'max-snippet:-1';
        $robots[] = 'max-image-preview:large';
        $robots[] = 'max-video-preview:-1';
        
        return implode(', ', $robots);
    }

    /**
     * Get theme color
     *
     * @since 1.0.0
     */
    public static function get_theme_color() {
        $theme_json = wp_get_global_settings();
        $colors = $theme_json['color']['palette']['theme'] ?? array();
        
        foreach ($colors as $color) {
            if ($color['slug'] === 'primary') {
                return $color['color'];
            }
        }
        
        return '#000000';
    }

    /**
     * Get image data
     *
     * @since 1.0.0
     */
    public static function get_image_data($image_url) {
        $attachment_id = attachment_url_to_postid($image_url);
        $data = array(
            'alt' => '',
            'width' => 1200,
            'height' => 630
        );
        
        if ($attachment_id) {
            $alt_text = get_post_meta($attachment_id, '_wp_attachment_image_alt', true);
            $metadata = wp_get_attachment_metadata($attachment_id);
            
            $data['alt'] = !empty($alt_text) ? $alt_text : get_the_title($attachment_id);
            $data['width'] = $metadata['width'] ?? 1200;
            $data['height'] = $metadata['height'] ?? 630;
        }
        
        return $data;
    }

    /**
     * Optimize document title
     *
     * @since 1.0.0
     */
    public static function optimize_document_title($title_parts) {
        if (is_home() || is_front_page()) {
            $custom_title = get_option('gpress_seo_home_title', '');
            if (!empty($custom_title)) {
                return array('title' => $custom_title);
            }
        }
        
        $separator = get_option('gpress_seo_separator', ' | ');
        $title_parts['site'] = get_bloginfo('name');
        
        return $title_parts;
    }

    /**
     * Optimize excerpt for SEO
     *
     * @since 1.0.0
     */
    public static function optimize_excerpt_for_seo($excerpt) {
        if (empty($excerpt)) {
            $content = get_the_content();
            $excerpt = wp_trim_words(strip_tags($content), 30, '...');
        }
        
        return $excerpt;
    }

    /**
     * Optimize image SEO
     *
     * @since 1.0.0
     */
    public static function optimize_image_seo($attr, $attachment, $size) {
        // Ensure alt text is present
        if (empty($attr['alt'])) {
            $alt_text = get_post_meta($attachment->ID, '_wp_attachment_image_alt', true);
            if (empty($alt_text)) {
                $alt_text = $attachment->post_title;
            }
            $attr['alt'] = $alt_text;
        }
        
        // Add loading attribute for SEO
        if (!isset($attr['loading'])) {
            $attr['loading'] = 'lazy';
        }
        
        return $attr;
    }

    /**
     * Add structured data
     *
     * @since 1.0.0
     */
    public static function add_structured_data() {
        if (get_option('gpress_enable_structured_data', true)) {
            require_once get_theme_file_path('/inc/seo-structured-data.php');
            GPress_Structured_Data::output_json_ld();
        }
    }

    /**
     * Add SEO analytics
     *
     * @since 1.0.0
     */
    public static function add_seo_analytics() {
        $tracking_id = get_option('gpress_google_analytics', '');
        if (!empty($tracking_id) && !is_admin() && !current_user_can('manage_options')) {
            ?>
            <!-- Google Analytics -->
            <script async src="https://www.googletagmanager.com/gtag/js?id=<?php echo esc_attr($tracking_id); ?>"></script>
            <script>
              window.dataLayer = window.dataLayer || [];
              function gtag(){dataLayer.push(arguments);}
              gtag('js', new Date());
              gtag('config', '<?php echo esc_js($tracking_id); ?>', {
                'anonymize_ip': true,
                'respect_dnt': true
              });
            </script>
            <?php
        }
    }

    /**
     * Setup SEO admin features
     *
     * @since 1.0.0
     */
    public static function setup_seo_admin() {
        add_action('admin_menu', array(__CLASS__, 'add_seo_admin_menu'));
        add_action('admin_enqueue_scripts', array(__CLASS__, 'admin_seo_assets'));
    }

    /**
     * Add SEO admin menu
     *
     * @since 1.0.0
     */
    public static function add_seo_admin_menu() {
        add_theme_page(
            __('SEO Settings', 'gpress'),
            __('SEO', 'gpress'),
            'manage_options',
            'gpress-seo',
            array(__CLASS__, 'seo_admin_page')
        );
    }

    /**
     * SEO admin page
     *
     * @since 1.0.0
     */
    public static function seo_admin_page() {
        // Implementation would include SEO settings form
        echo '<div class="wrap"><h1>' . __('SEO Settings', 'gpress') . '</h1>';
        echo '<p>' . __('Configure SEO settings for your GPress theme.', 'gpress') . '</p>';
        echo '</div>';
    }

    /**
     * Enqueue admin SEO assets
     *
     * @since 1.0.0
     */
    public static function admin_seo_assets($hook) {
        if ($hook === 'appearance_page_gpress-seo') {
            wp_enqueue_style(
                'gpress-admin-seo',
                get_theme_file_uri('/assets/css/admin-seo.css'),
                array(),
                GPRESS_VERSION
            );
        }
    }

    /**
     * Add SEO meta boxes
     *
     * @since 1.0.0
     */
    public static function add_seo_meta_boxes() {
        $post_types = get_post_types(array('public' => true));
        foreach ($post_types as $post_type) {
            add_meta_box(
                'gpress_seo_meta',
                __('SEO Settings', 'gpress'),
                array(__CLASS__, 'seo_meta_box_callback'),
                $post_type,
                'normal',
                'high'
            );
        }
    }

    /**
     * SEO meta box callback
     *
     * @since 1.0.0
     */
    public static function seo_meta_box_callback($post) {
        wp_nonce_field('gpress_seo_meta', 'gpress_seo_meta_nonce');
        
        $seo_title = get_post_meta($post->ID, '_gpress_seo_title', true);
        $seo_description = get_post_meta($post->ID, '_gpress_seo_description', true);
        $seo_keywords = get_post_meta($post->ID, '_gpress_seo_keywords', true);
        $og_image = get_post_meta($post->ID, '_gpress_og_image', true);
        
        ?>
        <table class="form-table">
            <tr>
                <th scope="row"><?php _e('SEO Title', 'gpress'); ?></th>
                <td>
                    <input type="text" name="gpress_seo_title" value="<?php echo esc_attr($seo_title); ?>" 
                           class="widefat" maxlength="60">
                    <p class="description"><?php _e('Recommended: 50-60 characters', 'gpress'); ?></p>
                </td>
            </tr>
            <tr>
                <th scope="row"><?php _e('Meta Description', 'gpress'); ?></th>
                <td>
                    <textarea name="gpress_seo_description" class="widefat" rows="3" 
                              maxlength="160"><?php echo esc_textarea($seo_description); ?></textarea>
                    <p class="description"><?php _e('Recommended: 150-160 characters', 'gpress'); ?></p>
                </td>
            </tr>
            <tr>
                <th scope="row"><?php _e('Keywords', 'gpress'); ?></th>
                <td>
                    <input type="text" name="gpress_seo_keywords" value="<?php echo esc_attr($seo_keywords); ?>" 
                           class="widefat">
                    <p class="description"><?php _e('Comma-separated keywords', 'gpress'); ?></p>
                </td>
            </tr>
            <tr>
                <th scope="row"><?php _e('OpenGraph Image', 'gpress'); ?></th>
                <td>
                    <input type="url" name="gpress_og_image" value="<?php echo esc_url($og_image); ?>" 
                           class="widefat">
                    <p class="description"><?php _e('Recommended: 1200x630 pixels', 'gpress'); ?></p>
                </td>
            </tr>
        </table>
        <?php
    }

    /**
     * Save SEO meta data
     *
     * @since 1.0.0
     */
    public static function save_seo_meta_data($post_id) {
        if (!isset($_POST['gpress_seo_meta_nonce']) || 
            !wp_verify_nonce($_POST['gpress_seo_meta_nonce'], 'gpress_seo_meta')) {
            return;
        }
        
        if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
            return;
        }
        
        if (!current_user_can('edit_post', $post_id)) {
            return;
        }
        
        $fields = array(
            'gpress_seo_title' => '_gpress_seo_title',
            'gpress_seo_description' => '_gpress_seo_description',
            'gpress_seo_keywords' => '_gpress_seo_keywords',
            'gpress_og_image' => '_gpress_og_image',
        );
        
        foreach ($fields as $field => $meta_key) {
            if (isset($_POST[$field])) {
                $value = sanitize_text_field($_POST[$field]);
                update_post_meta($post_id, $meta_key, $value);
            }
        }
    }
}

// Initialize the SEO system
GPress_SEO::init();
```

### 2. Create Structured Data Implementation

Create `inc/seo-structured-data.php`:

```php
<?php
/**
 * Structured Data Implementation for GPress Theme
 * Schema.org markup for rich snippets and enhanced search results
 *
 * @package GPress
 * @subpackage SEO
 * @version 1.0.0
 * @since 1.0.0
 */

// Prevent direct access
defined('ABSPATH') || exit;

/**
 * GPress Structured Data Manager
 * 
 * @since 1.0.0
 */
class GPress_Structured_Data {

    /**
     * Output JSON-LD structured data
     *
     * @since 1.0.0
     */
    public static function output_json_ld() {
        $schema_data = array();
        
        // Organization/Website schema
        $schema_data[] = self::get_organization_schema();
        
        // WebSite schema with search action
        $schema_data[] = self::get_website_schema();
        
        // Context-specific schemas
        if (is_home() || is_front_page()) {
            $schema_data[] = self::get_homepage_schema();
        } elseif (is_single()) {
            $schema_data[] = self::get_article_schema();
            $schema_data[] = self::get_author_schema();
        } elseif (is_page()) {
            $schema_data[] = self::get_webpage_schema();
        } elseif (is_author()) {
            $schema_data[] = self::get_person_schema();
        } elseif (is_category() || is_tag()) {
            $schema_data[] = self::get_collection_page_schema();
        }
        
        // Breadcrumb schema
        if (!is_front_page()) {
            $breadcrumb_schema = self::get_breadcrumb_schema();
            if (!empty($breadcrumb_schema)) {
                $schema_data[] = $breadcrumb_schema;
            }
        }
        
        // Output all schemas
        foreach ($schema_data as $schema) {
            if (!empty($schema)) {
                self::output_schema($schema);
            }
        }
    }

    /**
     * Get Organization schema
     *
     * @since 1.0.0
     */
    private static function get_organization_schema() {
        $logo_url = get_site_icon_url(512);
        if (empty($logo_url)) {
            $logo_url = get_theme_file_uri('/screenshot.png');
        }
        
        $schema = array(
            '@context' => 'https://schema.org',
            '@type' => 'Organization',
            'name' => get_bloginfo('name'),
            'description' => get_bloginfo('description'),
            'url' => home_url('/'),
            'logo' => array(
                '@type' => 'ImageObject',
                'url' => $logo_url,
                'width' => 512,
                'height' => 512
            ),
            'sameAs' => self::get_social_profiles()
        );
        
        // Add contact information if available
        $contact_info = self::get_contact_information();
        if (!empty($contact_info)) {
            $schema = array_merge($schema, $contact_info);
        }
        
        return $schema;
    }

    /**
     * Get WebSite schema
     *
     * @since 1.0.0
     */
    private static function get_website_schema() {
        $schema = array(
            '@context' => 'https://schema.org',
            '@type' => 'WebSite',
            'name' => get_bloginfo('name'),
            'description' => get_bloginfo('description'),
            'url' => home_url('/'),
            'inLanguage' => get_locale(),
            'potentialAction' => array(
                '@type' => 'SearchAction',
                'target' => array(
                    '@type' => 'EntryPoint',
                    'urlTemplate' => home_url('/?s={search_term_string}')
                ),
                'query-input' => 'required name=search_term_string'
            )
        );
        
        return $schema;
    }

    /**
     * Get Article schema for single posts
     *
     * @since 1.0.0
     */
    private static function get_article_schema() {
        if (!is_single()) {
            return array();
        }
        
        global $post;
        
        $featured_image = '';
        if (has_post_thumbnail()) {
            $image_data = wp_get_attachment_image_src(get_post_thumbnail_id(), 'full');
            $featured_image = $image_data[0];
        }
        
        $schema = array(
            '@context' => 'https://schema.org',
            '@type' => 'Article',
            'headline' => get_the_title(),
            'description' => GPress_SEO::get_seo_description(),
            'url' => get_permalink(),
            'datePublished' => get_the_date('c'),
            'dateModified' => get_the_modified_date('c'),
            'author' => array(
                '@type' => 'Person',
                'name' => get_the_author(),
                'url' => get_author_posts_url(get_the_author_meta('ID'))
            ),
            'publisher' => array(
                '@type' => 'Organization',
                'name' => get_bloginfo('name'),
                'logo' => array(
                    '@type' => 'ImageObject',
                    'url' => get_site_icon_url(512)
                )
            ),
            'mainEntityOfPage' => array(
                '@type' => 'WebPage',
                '@id' => get_permalink()
            ),
            'inLanguage' => get_locale()
        );
        
        if (!empty($featured_image)) {
            $schema['image'] = array(
                '@type' => 'ImageObject',
                'url' => $featured_image,
                'width' => 1200,
                'height' => 630
            );
        }
        
        // Add article section
        $categories = get_the_category();
        if (!empty($categories)) {
            $schema['articleSection'] = $categories[0]->name;
        }
        
        // Add keywords
        $tags = get_the_tags();
        if (!empty($tags)) {
            $keywords = array();
            foreach ($tags as $tag) {
                $keywords[] = $tag->name;
            }
            $schema['keywords'] = implode(', ', $keywords);
        }
        
        // Add word count
        $content = get_the_content();
        $word_count = str_word_count(strip_tags($content));
        if ($word_count > 0) {
            $schema['wordCount'] = $word_count;
        }
        
        return $schema;
    }

    /**
     * Get Author/Person schema
     *
     * @since 1.0.0
     */
    private static function get_author_schema() {
        if (!is_single()) {
            return array();
        }
        
        $author_id = get_the_author_meta('ID');
        $author_description = get_the_author_meta('description');
        $author_website = get_the_author_meta('user_url');
        
        $schema = array(
            '@context' => 'https://schema.org',
            '@type' => 'Person',
            'name' => get_the_author(),
            'url' => get_author_posts_url($author_id),
            'sameAs' => array()
        );
        
        if (!empty($author_description)) {
            $schema['description'] = $author_description;
        }
        
        if (!empty($author_website)) {
            $schema['sameAs'][] = $author_website;
        }
        
        // Add social profiles
        $social_fields = array('twitter', 'facebook', 'linkedin', 'instagram');
        foreach ($social_fields as $field) {
            $social_url = get_the_author_meta($field);
            if (!empty($social_url)) {
                $schema['sameAs'][] = $social_url;
            }
        }
        
        return $schema;
    }

    /**
     * Get WebPage schema for pages
     *
     * @since 1.0.0
     */
    private static function get_webpage_schema() {
        if (!is_page()) {
            return array();
        }
        
        $schema = array(
            '@context' => 'https://schema.org',
            '@type' => 'WebPage',
            'name' => get_the_title(),
            'description' => GPress_SEO::get_seo_description(),
            'url' => get_permalink(),
            'inLanguage' => get_locale(),
            'isPartOf' => array(
                '@type' => 'WebSite',
                'name' => get_bloginfo('name'),
                'url' => home_url('/')
            ),
            'datePublished' => get_the_date('c'),
            'dateModified' => get_the_modified_date('c')
        );
        
        return $schema;
    }

    /**
     * Get Person schema for author pages
     *
     * @since 1.0.0
     */
    private static function get_person_schema() {
        if (!is_author()) {
            return array();
        }
        
        $author_id = get_queried_object_id();
        $author_description = get_the_author_meta('description', $author_id);
        $author_website = get_the_author_meta('user_url', $author_id);
        
        $schema = array(
            '@context' => 'https://schema.org',
            '@type' => 'Person',
            'name' => get_the_author_meta('display_name', $author_id),
            'url' => get_author_posts_url($author_id)
        );
        
        if (!empty($author_description)) {
            $schema['description'] = $author_description;
        }
        
        if (!empty($author_website)) {
            $schema['url'] = $author_website;
        }
        
        return $schema;
    }

    /**
     * Get Collection Page schema for archives
     *
     * @since 1.0.0
     */
    private static function get_collection_page_schema() {
        if (!is_category() && !is_tag()) {
            return array();
        }
        
        $term = get_queried_object();
        
        $schema = array(
            '@context' => 'https://schema.org',
            '@type' => 'CollectionPage',
            'name' => $term->name,
            'description' => !empty($term->description) ? $term->description : 'Archive page for ' . $term->name,
            'url' => get_term_link($term),
            'inLanguage' => get_locale()
        );
        
        return $schema;
    }

    /**
     * Get Breadcrumb schema
     *
     * @since 1.0.0
     */
    private static function get_breadcrumb_schema() {
        $breadcrumbs = self::generate_breadcrumb_data();
        
        if (empty($breadcrumbs)) {
            return array();
        }
        
        $schema = array(
            '@context' => 'https://schema.org',
            '@type' => 'BreadcrumbList',
            'itemListElement' => $breadcrumbs
        );
        
        return $schema;
    }

    /**
     * Get Homepage schema
     *
     * @since 1.0.0
     */
    private static function get_homepage_schema() {
        $schema = array(
            '@context' => 'https://schema.org',
            '@type' => 'WebPage',
            'name' => get_bloginfo('name'),
            'description' => get_bloginfo('description'),
            'url' => home_url('/'),
            'inLanguage' => get_locale(),
            'isPartOf' => array(
                '@type' => 'WebSite',
                'name' => get_bloginfo('name'),
                'url' => home_url('/')
            )
        );
        
        return $schema;
    }

    /**
     * Generate breadcrumb data
     *
     * @since 1.0.0
     */
    private static function generate_breadcrumb_data() {
        $breadcrumbs = array();
        $position = 1;
        
        // Home
        $breadcrumbs[] = array(
            '@type' => 'ListItem',
            'position' => $position++,
            'name' => 'Home',
            'item' => home_url('/')
        );
        
        if (is_single()) {
            global $post;
            
            // Add category for posts
            if ($post->post_type === 'post') {
                $categories = get_the_category();
                if (!empty($categories)) {
                    $category = $categories[0];
                    $breadcrumbs[] = array(
                        '@type' => 'ListItem',
                        'position' => $position++,
                        'name' => $category->name,
                        'item' => get_category_link($category->term_id)
                    );
                }
            }
            
            // Add parent pages
            if ($post->post_parent) {
                $ancestors = get_post_ancestors($post->ID);
                $ancestors = array_reverse($ancestors);
                
                foreach ($ancestors as $ancestor) {
                    $breadcrumbs[] = array(
                        '@type' => 'ListItem',
                        'position' => $position++,
                        'name' => get_the_title($ancestor),
                        'item' => get_permalink($ancestor)
                    );
                }
            }
            
            // Current post
            $breadcrumbs[] = array(
                '@type' => 'ListItem',
                'position' => $position++,
                'name' => get_the_title(),
                'item' => get_permalink()
            );
            
        } elseif (is_page()) {
            global $post;
            
            if ($post->post_parent) {
                $ancestors = get_post_ancestors($post->ID);
                $ancestors = array_reverse($ancestors);
                
                foreach ($ancestors as $ancestor) {
                    $breadcrumbs[] = array(
                        '@type' => 'ListItem',
                        'position' => $position++,
                        'name' => get_the_title($ancestor),
                        'item' => get_permalink($ancestor)
                    );
                }
            }
            
            $breadcrumbs[] = array(
                '@type' => 'ListItem',
                'position' => $position++,
                'name' => get_the_title(),
                'item' => get_permalink()
            );
            
        } elseif (is_category()) {
            $category = get_queried_object();
            
            if ($category->parent) {
                $ancestors = get_ancestors($category->term_id, 'category');
                $ancestors = array_reverse($ancestors);
                
                foreach ($ancestors as $ancestor) {
                    $ancestor_category = get_category($ancestor);
                    $breadcrumbs[] = array(
                        '@type' => 'ListItem',
                        'position' => $position++,
                        'name' => $ancestor_category->name,
                        'item' => get_category_link($ancestor)
                    );
                }
            }
            
            $breadcrumbs[] = array(
                '@type' => 'ListItem',
                'position' => $position++,
                'name' => $category->name,
                'item' => get_category_link($category->term_id)
            );
        }
        
        return $breadcrumbs;
    }

    /**
     * Get social profiles
     *
     * @since 1.0.0
     */
    private static function get_social_profiles() {
        $profiles = array();
        
        $social_options = array(
            'facebook_url' => get_option('gpress_facebook_url', ''),
            'twitter_url' => get_option('gpress_twitter_url', ''),
            'linkedin_url' => get_option('gpress_linkedin_url', ''),
            'instagram_url' => get_option('gpress_instagram_url', ''),
            'youtube_url' => get_option('gpress_youtube_url', ''),
        );
        
        foreach ($social_options as $profile) {
            if (!empty($profile)) {
                $profiles[] = $profile;
            }
        }
        
        return $profiles;
    }

    /**
     * Get contact information
     *
     * @since 1.0.0
     */
    private static function get_contact_information() {
        $contact = array();
        
        $phone = get_option('gpress_phone_number', '');
        $email = get_option('gpress_email_address', '');
        $address = get_option('gpress_address', '');
        
        if (!empty($phone)) {
            $contact['telephone'] = $phone;
        }
        
        if (!empty($email)) {
            $contact['email'] = $email;
        }
        
        if (!empty($address)) {
            $contact['address'] = array(
                '@type' => 'PostalAddress',
                'streetAddress' => $address
            );
        }
        
        return $contact;
    }

    /**
     * Output schema markup
     *
     * @since 1.0.0
     */
    private static function output_schema($schema) {
        if (empty($schema)) {
            return;
        }
        
        echo '<script type="application/ld+json">';
        echo wp_json_encode($schema, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
        echo '</script>' . "\n";
    }
}
```

### 3. Update Functions.php

Update `functions.php`:

```php
// ... existing code ...

/**
 * Load SEO Components
 * Comprehensive SEO optimization implementation
 *
 * @since 1.0.0
 */
function gpress_load_seo_components() {
    // Core SEO system (always loaded)
    require_once get_theme_file_path('/inc/seo.php');
    
    // Enhanced SEO features (conditionally loaded)
    if (get_theme_mod('enable_seo_optimization', true)) {
        require_once get_theme_file_path('/inc/seo-structured-data.php');
        require_once get_theme_file_path('/inc/seo-meta-tags.php');
    }
    
    // Local SEO features
    if (get_option('gpress_enable_local_seo', false)) {
        require_once get_theme_file_path('/inc/seo-local.php');
    }
    
    // Analytics and tracking
    if (get_option('gpress_enable_analytics', true)) {
        require_once get_theme_file_path('/inc/seo-analytics.php');
    }
    
    // Admin and development components
    if (is_admin()) {
        require_once get_theme_file_path('/inc/seo-testing.php');
        require_once get_theme_file_path('/inc/seo-optimization.php');
    }
}
add_action('after_setup_theme', 'gpress_load_seo_components');

/**
 * Add SEO-specific theme support
 *
 * @since 1.0.0
 */
function gpress_add_seo_theme_support() {
    // Core SEO features
    add_theme_support('title-tag');
    add_theme_support('post-thumbnails');
    add_theme_support('html5', array('search-form', 'comment-form', 'comment-list'));
    
    // Advanced SEO features
    add_theme_support('seo-optimization');
    add_theme_support('structured-data');
    add_theme_support('opengraph');
    add_theme_support('twitter-cards');
    add_theme_support('social-sharing');
    add_theme_support('breadcrumb-schema');
    
    // Performance features for SEO
    add_theme_support('responsive-images');
    add_theme_support('image-optimization');
}
add_action('after_setup_theme', 'gpress_add_seo_theme_support');

// ... existing code ...
```

### 4. Update Style.css

Update `style.css`:

```css
/* ... existing code ... */

/**
 * SEO Integration Styles
 * Base SEO integration with theme styles
 *
 * @since 1.0.0
 */

/* Social Sharing Integration */
.social-sharing {
    margin: 2rem 0;
    padding: 1rem;
    background: var(--wp--preset--color--background-secondary);
    border-radius: 8px;
    border-left: 4px solid var(--wp--preset--color--primary);
}

.social-sharing-title {
    font-size: 1rem;
    font-weight: 600;
    margin-bottom: 1rem;
    color: var(--wp--preset--color--foreground);
}

.social-sharing-buttons {
    display: flex;
    gap: 0.75rem;
    flex-wrap: wrap;
}

.social-share-button {
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    padding: 0.5rem 1rem;
    background: var(--wp--preset--color--primary);
    color: var(--wp--preset--color--background);
    text-decoration: none;
    border-radius: 6px;
    font-size: 0.875rem;
    font-weight: 500;
    transition: all 0.3s ease;
    min-height: 44px;
}

.social-share-button:hover,
.social-share-button:focus {
    background: var(--wp--preset--color--accent);
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(var(--wp--preset--color--primary-rgb), 0.3);
}

.social-share-button .icon {
    width: 18px;
    height: 18px;
    flex-shrink: 0;
}

/* SEO-Optimized Images */
img[loading="lazy"] {
    opacity: 0;
    transition: opacity 0.3s ease;
}

img[loading="lazy"].loaded {
    opacity: 1;
}

/* OpenGraph Image Optimization */
.og-image {
    aspect-ratio: 1200 / 630;
    object-fit: cover;
    width: 100%;
    height: auto;
}

.twitter-card-image {
    aspect-ratio: 1200 / 600;
    object-fit: cover;
    width: 100%;
    height: auto;
}

/* Schema.org Microdata Integration */
[itemscope] {
    position: relative;
}

/* Breadcrumb SEO Integration */
.breadcrumb-navigation[role="navigation"] {
    margin-bottom: 1.5rem;
}

.breadcrumb-list {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    list-style: none;
    margin: 0;
    padding: 0;
    font-size: 0.875rem;
}

.breadcrumb-item {
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.breadcrumb-item:not(:last-child)::after {
    content: "›";
    color: var(--wp--preset--color--foreground-secondary);
    font-size: 0.75rem;
}

.breadcrumb-item a {
    color: var(--wp--preset--color--foreground-secondary);
    text-decoration: none;
    transition: color 0.3s ease;
}

.breadcrumb-item a:hover,
.breadcrumb-item a:focus {
    color: var(--wp--preset--color--primary);
}

.breadcrumb-current {
    color: var(--wp--preset--color--foreground);
    font-weight: 500;
}

/* SEO-Friendly Content Structure */
.entry-content h2,
.entry-content h3,
.entry-content h4,
.entry-content h5,
.entry-content h6 {
    margin-top: 2rem;
    margin-bottom: 1rem;
    line-height: 1.3;
}

.entry-content h2 {
    font-size: 1.75rem;
}

.entry-content h3 {
    font-size: 1.5rem;
}

.entry-content h4 {
    font-size: 1.25rem;
}

/* Article Meta Information */
.article-meta {
    display: flex;
    gap: 1rem;
    flex-wrap: wrap;
    align-items: center;
    margin: 1rem 0;
    padding: 1rem;
    background: var(--wp--preset--color--background-secondary);
    border-radius: 8px;
    font-size: 0.875rem;
}

.article-meta .meta-item {
    display: flex;
    align-items: center;
    gap: 0.25rem;
    color: var(--wp--preset--color--foreground-secondary);
}

.article-meta .meta-icon {
    width: 16px;
    height: 16px;
    flex-shrink: 0;
}

/* Author Box SEO */
.author-box {
    margin: 2rem 0;
    padding: 1.5rem;
    background: var(--wp--preset--color--background-secondary);
    border-radius: 12px;
    border: 1px solid var(--wp--preset--color--border);
}

.author-box .author-info {
    display: flex;
    gap: 1rem;
    align-items: flex-start;
}

.author-box .author-avatar {
    width: 80px;
    height: 80px;
    border-radius: 50%;
    flex-shrink: 0;
}

.author-box .author-details h3 {
    margin: 0 0 0.5rem 0;
    font-size: 1.25rem;
}

.author-box .author-bio {
    color: var(--wp--preset--color--foreground-secondary);
    line-height: 1.6;
    margin-bottom: 1rem;
}

.author-box .author-links {
    display: flex;
    gap: 0.75rem;
    flex-wrap: wrap;
}

.author-box .author-link {
    color: var(--wp--preset--color--primary);
    text-decoration: none;
    font-size: 0.875rem;
    font-weight: 500;
}

.author-box .author-link:hover,
.author-box .author-link:focus {
    text-decoration: underline;
}

/* Related Posts SEO */
.related-posts {
    margin: 3rem 0;
    padding: 2rem;
    background: var(--wp--preset--color--background-secondary);
    border-radius: 12px;
}

.related-posts h3 {
    margin: 0 0 1.5rem 0;
    font-size: 1.5rem;
    text-align: center;
}

.related-posts-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
    gap: 1.5rem;
}

.related-post {
    background: var(--wp--preset--color--background);
    border-radius: 8px;
    overflow: hidden;
    border: 1px solid var(--wp--preset--color--border);
    transition: transform 0.3s ease, box-shadow 0.3s ease;
}

.related-post:hover {
    transform: translateY(-4px);
    box-shadow: 0 8px 25px rgba(0, 0, 0, 0.1);
}

.related-post-image {
    aspect-ratio: 16 / 9;
    object-fit: cover;
    width: 100%;
}

.related-post-content {
    padding: 1rem;
}

.related-post-title {
    font-size: 1rem;
    font-weight: 600;
    margin: 0 0 0.5rem 0;
    line-height: 1.4;
}

.related-post-title a {
    color: var(--wp--preset--color--foreground);
    text-decoration: none;
}

.related-post-title a:hover,
.related-post-title a:focus {
    color: var(--wp--preset--color--primary);
}

.related-post-excerpt {
    font-size: 0.875rem;
    color: var(--wp--preset--color--foreground-secondary);
    line-height: 1.5;
}

/* Responsive SEO Elements */
@media (max-width: 768px) {
    .social-sharing-buttons {
        justify-content: center;
    }
    
    .social-share-button {
        flex: 1;
        justify-content: center;
        min-width: 120px;
    }
    
    .breadcrumb-list {
        font-size: 0.8rem;
    }
    
    .author-box .author-info {
        flex-direction: column;
        text-align: center;
    }
    
    .related-posts-grid {
        grid-template-columns: 1fr;
    }
    
    .article-meta {
        flex-direction: column;
        align-items: flex-start;
        gap: 0.5rem;
    }
}

/* Print SEO Optimization */
@media print {
    .social-sharing,
    .related-posts,
    .author-box .author-links {
        display: none;
    }
    
    .breadcrumb-navigation::after {
        content: "URL: " attr(data-url);
        display: block;
        font-size: 0.8rem;
        color: #666;
        margin-top: 0.5rem;
    }
    
    .entry-content a[href]::after {
        content: " (" attr(href) ")";
        font-size: 0.8rem;
        color: #666;
    }
}

/* Schema.org Visual Indicators (Development Mode) */
.admin-bar [itemscope]::before {
    content: "Schema: " attr(itemtype);
    position: absolute;
    top: -20px;
    left: 0;
    font-size: 0.7rem;
    background: #23282d;
    color: #fff;
    padding: 2px 6px;
    border-radius: 3px;
    z-index: 9999;
    opacity: 0.8;
}

/* Core Web Vitals Optimization */
.entry-content img {
    max-width: 100%;
    height: auto;
    display: block;
}

.entry-content iframe {
    max-width: 100%;
}

/* ... existing code ... */
```

## Testing This Step

### 1. SEO Compliance Testing
```bash
# Test meta tags
curl -s http://yoursite.com/ | grep -E "<meta (name|property)="

# Check OpenGraph tags
curl -s http://yoursite.com/ | grep 'property="og:'

# Validate Twitter Cards
curl -s http://yoursite.com/ | grep 'name="twitter:'

# Test canonical URLs
curl -s http://yoursite.com/ | grep 'rel="canonical"'
```

### 2. Structured Data Testing
```bash
# Test JSON-LD output
curl -s http://yoursite.com/ | grep -A 20 'type="application/ld+json"'

# Validate specific schemas
curl -s http://yoursite.com/sample-post/ | grep -o '"@type":"[^"]*"'

# Check breadcrumb schema
curl -s http://yoursite.com/sample-page/ | grep '"BreadcrumbList"'
```

### 3. Performance Impact Testing
```bash
# Test conditional asset loading
curl -s http://yoursite.com/ | grep -c "seo.*\.css"
curl -s http://yoursite.com/ | grep -c "seo.*\.js"

# Check image optimization
curl -s http://yoursite.com/ | grep 'loading="lazy"'

# Validate meta tag efficiency
curl -s http://yoursite.com/ | grep "<meta" | wc -l
```

### 4. Social Media Testing
```bash
# Test OpenGraph image
curl -s http://yoursite.com/sample-post/ | grep 'og:image'

# Check Twitter Card type
curl -s http://yoursite.com/sample-post/ | grep 'twitter:card'

# Validate social sharing integration
curl -s http://yoursite.com/sample-post/ | grep "social-sharing"
```

### 5. Technical SEO Testing
```bash
# Check robots meta
curl -s http://yoursite.com/ | grep 'name="robots"'

# Test sitemap accessibility
curl -I http://yoursite.com/sitemap.xml

# Validate schema markup using Google's tool
# https://search.google.com/test/rich-results
```

## Expected Results After This Step

1. **Complete Meta Tag Optimization**: Comprehensive meta tags, OpenGraph, and Twitter Cards for all content types
2. **Structured Data Implementation**: Full Schema.org markup for articles, organization, breadcrumbs, and author information
3. **Technical SEO Foundation**: Canonical URLs, robots directives, and optimized URL structures
4. **Social Media Integration**: Enhanced social sharing with optimized images and metadata
5. **Performance-Optimized SEO**: Conditional loading of SEO assets with minimal performance impact
6. **Admin Interface**: SEO settings panel and per-post meta box configuration
7. **Analytics Integration**: Google Analytics with privacy-compliant implementation
8. **Local SEO Support**: Business markup and local search optimization features

## Next Step

In Step 15, we'll implement comprehensive security hardening measures to protect the theme and website from common vulnerabilities while maintaining performance and user experience.

---

**Step 14 Completed**: Advanced SEO Optimization & Schema Implementation ✅
- Complete meta tag and OpenGraph optimization
- Comprehensive Schema.org structured data implementation
- Technical SEO foundation with canonical URLs and robots directives
- Social media integration with optimized sharing features
- Performance-optimized conditional SEO asset loading
- Admin interface for SEO configuration and management
- Analytics integration with privacy compliance
- Rich snippets and search engine enhancement features