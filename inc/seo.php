<?php
/**
 * SEO Optimizations and Schema Markup
 * 
 * @package NearMenus
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

/**
 * SEO and Schema Markup Class
 */
class NearMenus_SEO {
    
    public function __construct() {
        add_action('wp_head', array($this, 'add_meta_tags'));
        add_action('wp_head', array($this, 'add_schema_markup'));
        add_action('wp_head', array($this, 'add_open_graph_tags'));
        add_action('wp_head', array($this, 'add_twitter_cards'));
        add_filter('document_title_parts', array($this, 'customize_title_parts'));
        add_filter('get_the_archive_title', array($this, 'customize_archive_titles'));
        add_action('wp_head', array($this, 'add_canonical_url'));
        add_filter('wp_robots', array($this, 'customize_robots_meta'));
    }
    
    /**
     * Add basic meta tags
     */
    public function add_meta_tags() {
        echo '<meta name="viewport" content="width=device-width, initial-scale=1.0">' . "\n";
        echo '<meta http-equiv="X-UA-Compatible" content="IE=edge">' . "\n";
        
        // Meta description
        $description = $this->get_meta_description();
        if ($description) {
            echo '<meta name="description" content="' . esc_attr($description) . '">' . "\n";
        }
        
        // Meta keywords (for legacy support)
        $keywords = $this->get_meta_keywords();
        if ($keywords) {
            echo '<meta name="keywords" content="' . esc_attr($keywords) . '">' . "\n";
        }
        
        // Author meta
        if (is_single()) {
            $author = get_the_author();
            echo '<meta name="author" content="' . esc_attr($author) . '">' . "\n";
        }
        
        // Generator meta (remove WordPress version)
        remove_action('wp_head', 'wp_generator');
    }
    
    /**
     * Add Schema.org structured data
     */
    public function add_schema_markup() {
        if (is_single()) {
            $this->add_restaurant_schema();
        } elseif (is_home() || is_front_page()) {
            $this->add_website_schema();
        } elseif (is_tax('cuisine')) {
            $this->add_cuisine_schema();
        } elseif (is_archive()) {
            $this->add_collection_schema();
        }
    }
    
    /**
     * Add restaurant schema markup
     */
    private function add_restaurant_schema() {
        global $post;
        
        $rating = get_post_meta($post->ID, '_restaurant_rating', true);
        $review_count = get_post_meta($post->ID, '_restaurant_review_count', true);
        $phone = get_post_meta($post->ID, '_restaurant_phone', true);
        $address = get_post_meta($post->ID, '_restaurant_address', true);
        $website = get_post_meta($post->ID, '_restaurant_website', true);
        $price_range = wp_get_post_terms($post->ID, 'price_range', array('fields' => 'names'));
        $cuisines = wp_get_post_terms($post->ID, 'cuisine', array('fields' => 'names'));
        $features = wp_get_post_terms($post->ID, 'features', array('fields' => 'names'));
        
        // Handle WP_Error cases
        if (is_wp_error($price_range)) $price_range = array();
        if (is_wp_error($cuisines)) $cuisines = array();
        if (is_wp_error($features)) $features = array();
        
        $hours = array();
        $days = array('monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday', 'sunday');
        foreach ($days as $day) {
            $day_hours = get_post_meta($post->ID, '_restaurant_hours_' . $day, true);
            if ($day_hours && $day_hours !== 'Closed') {
                $hours[] = ucfirst($day) . ' ' . $day_hours;
            }
        }
        
        $schema = array(
            '@context' => 'https://schema.org',
            '@type' => 'Restaurant',
            'name' => get_the_title(),
            'description' => get_the_excerpt() ?: wp_trim_words(get_the_content(), 30),
            'url' => get_permalink(),
            'image' => get_the_post_thumbnail_url($post->ID, 'full'),
        );
        
        if ($phone) {
            $schema['telephone'] = $phone;
        }
        
        if ($website) {
            $schema['url'] = $website;
        }
        
        if ($address) {
            $schema['address'] = array(
                '@type' => 'PostalAddress',
                'streetAddress' => $address,
            );
        }
        
        if ($cuisines) {
            $schema['servesCuisine'] = $cuisines;
        }
        
        if ($price_range) {
            $schema['priceRange'] = implode('', $price_range);
        }
        
        if ($rating && $review_count) {
            $schema['aggregateRating'] = array(
                '@type' => 'AggregateRating',
                'ratingValue' => $rating,
                'reviewCount' => $review_count,
                'bestRating' => '5',
                'worstRating' => '1',
            );
        }
        
        if ($hours) {
            $schema['openingHours'] = $hours;
        }
        
        // Add menu if available
        $menu_items = get_post_meta($post->ID, '_restaurant_menu', true);
        if ($menu_items) {
            $menu_schema = array();
            foreach ($menu_items as $category => $items) {
                foreach ($items as $item) {
                    $menu_schema[] = array(
                        '@type' => 'MenuItem',
                        'name' => $item['name'],
                        'description' => $item['description'],
                        'offers' => array(
                            '@type' => 'Offer',
                            'price' => $item['price'],
                            'priceCurrency' => 'USD',
                        ),
                    );
                }
            }
            $schema['hasMenu'] = array(
                '@type' => 'Menu',
                'hasMenuSection' => $menu_schema,
            );
        }
        
        echo '<script type="application/ld+json">' . "\n";
        echo wp_json_encode($schema, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
        echo "\n" . '</script>' . "\n";
    }
    
    /**
     * Add website schema markup
     */
    private function add_website_schema() {
        $schema = array(
            '@context' => 'https://schema.org',
            '@type' => 'WebSite',
            'name' => get_bloginfo('name'),
            'description' => get_bloginfo('description'),
            'url' => home_url(),
            'potentialAction' => array(
                '@type' => 'SearchAction',
                'target' => home_url('/?s={search_term_string}'),
                'query-input' => 'required name=search_term_string',
            ),
        );
        
        // Add organization info
        $organization = array(
            '@type' => 'Organization',
            'name' => get_bloginfo('name'),
            'url' => home_url(),
        );
        
        $phone = get_theme_mod('nearmenus_phone');
        if ($phone) {
            $organization['telephone'] = $phone;
        }
        
        $email = get_theme_mod('nearmenus_email');
        if ($email) {
            $organization['email'] = $email;
        }
        
        $address = get_theme_mod('nearmenus_address');
        if ($address) {
            $organization['address'] = $address;
        }
        
        // Add social media profiles
        $social_profiles = array();
        $platforms = array('facebook', 'twitter', 'instagram', 'youtube', 'linkedin');
        foreach ($platforms as $platform) {
            $url = get_theme_mod("nearmenus_social_{$platform}");
            if ($url) {
                $social_profiles[] = $url;
            }
        }
        
        if ($social_profiles) {
            $organization['sameAs'] = $social_profiles;
        }
        
        $schema['publisher'] = $organization;
        
        echo '<script type="application/ld+json">' . "\n";
        echo wp_json_encode($schema, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
        echo "\n" . '</script>' . "\n";
    }
    
    /**
     * Add cuisine schema markup
     */
    private function add_cuisine_schema() {
        $term = get_queried_object();
        
        $schema = array(
            '@context' => 'https://schema.org',
            '@type' => 'CollectionPage',
            'name' => $term->name . ' Restaurants',
            'description' => $term->description ?: 'Find the best ' . $term->name . ' restaurants near you.',
            'url' => get_term_link($term),
            'mainEntity' => array(
                '@type' => 'ItemList',
                'name' => $term->name . ' Restaurants',
                'numberOfItems' => $term->count,
            ),
        );
        
        echo '<script type="application/ld+json">' . "\n";
        echo wp_json_encode($schema, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
        echo "\n" . '</script>' . "\n";
    }
    
    /**
     * Add collection schema markup
     */
    private function add_collection_schema() {
        global $wp_query;
        
        $schema = array(
            '@context' => 'https://schema.org',
            '@type' => 'CollectionPage',
            'name' => 'Restaurant Directory',
            'description' => 'Browse our collection of restaurants.',
            'url' => get_permalink(),
            'mainEntity' => array(
                '@type' => 'ItemList',
                'numberOfItems' => $wp_query->found_posts,
            ),
        );
        
        echo '<script type="application/ld+json">' . "\n";
        echo wp_json_encode($schema, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
        echo "\n" . '</script>' . "\n";
    }
    
    /**
     * Add Open Graph tags
     */
    public function add_open_graph_tags() {
        echo '<meta property="og:site_name" content="' . esc_attr(get_bloginfo('name')) . '">' . "\n";
        echo '<meta property="og:locale" content="' . esc_attr(get_locale()) . '">' . "\n";
        
        if (is_single()) {
            echo '<meta property="og:type" content="restaurant">' . "\n";
            echo '<meta property="og:title" content="' . esc_attr(get_the_title()) . '">' . "\n";
            echo '<meta property="og:description" content="' . esc_attr($this->get_meta_description()) . '">' . "\n";
            echo '<meta property="og:url" content="' . esc_url(get_permalink()) . '">' . "\n";
            
            $image = get_the_post_thumbnail_url(null, 'large');
            if ($image) {
                echo '<meta property="og:image" content="' . esc_url($image) . '">' . "\n";
                echo '<meta property="og:image:width" content="1200">' . "\n";
                echo '<meta property="og:image:height" content="630">' . "\n";
            }
        } else {
            echo '<meta property="og:type" content="website">' . "\n";
            echo '<meta property="og:title" content="' . esc_attr(wp_get_document_title()) . '">' . "\n";
            echo '<meta property="og:description" content="' . esc_attr($this->get_meta_description()) . '">' . "\n";
            echo '<meta property="og:url" content="' . esc_url(get_permalink()) . '">' . "\n";
            
            // Use site logo or default image
            $logo = get_theme_mod('custom_logo');
            if ($logo) {
                $logo_url = wp_get_attachment_url($logo);
                echo '<meta property="og:image" content="' . esc_url($logo_url) . '">' . "\n";
            }
        }
    }
    
    /**
     * Add Twitter Card tags
     */
    public function add_twitter_cards() {
        echo '<meta name="twitter:card" content="summary_large_image">' . "\n";
        
        $twitter_handle = get_theme_mod('nearmenus_social_twitter');
        if ($twitter_handle) {
            $handle = str_replace('https://twitter.com/', '@', $twitter_handle);
            echo '<meta name="twitter:site" content="' . esc_attr($handle) . '">' . "\n";
        }
        
        echo '<meta name="twitter:title" content="' . esc_attr(wp_get_document_title()) . '">' . "\n";
        echo '<meta name="twitter:description" content="' . esc_attr($this->get_meta_description()) . '">' . "\n";
        
        if (is_single()) {
            $image = get_the_post_thumbnail_url(null, 'large');
            if ($image) {
                echo '<meta name="twitter:image" content="' . esc_url($image) . '">' . "\n";
            }
        }
    }
    
    /**
     * Get meta description
     */
    private function get_meta_description() {
        if (is_single()) {
            $excerpt = get_the_excerpt();
            return $excerpt ?: wp_trim_words(get_the_content(), 30);
        } elseif (is_tax('cuisine')) {
            $term = get_queried_object();
            return $term->description ?: 'Find the best ' . $term->name . ' restaurants near you.';
        } elseif (is_home() || is_front_page()) {
            return get_bloginfo('description');
        } elseif (is_archive()) {
            return 'Browse our collection of restaurants and find your next dining experience.';
        }
        
        return get_bloginfo('description');
    }
    
    /**
     * Get meta keywords
     */
    private function get_meta_keywords() {
        $keywords = array();
        
        if (is_single()) {
            // Add cuisine terms
            $cuisines = wp_get_post_terms(get_the_ID(), 'cuisine', array('fields' => 'names'));
            if ($cuisines) {
                $keywords = array_merge($keywords, $cuisines);
            }
            
            // Add location terms
            $locations = wp_get_post_terms(get_the_ID(), 'location', array('fields' => 'names'));
            if ($locations) {
                $keywords = array_merge($keywords, $locations);
            }
            
            // Add features
            $features = wp_get_post_terms(get_the_ID(), 'features', array('fields' => 'names'));
            if ($features) {
                $keywords = array_merge($keywords, $features);
            }
        }
        
        // Add general keywords
        $keywords[] = 'restaurant';
        $keywords[] = 'dining';
        $keywords[] = 'food';
        $keywords[] = 'menu';
        
        return implode(', ', array_unique($keywords));
    }
    
    /**
     * Customize title parts
     */
    public function customize_title_parts($title_parts) {
        if (is_single()) {
            $cuisines = wp_get_post_terms(get_the_ID(), 'cuisine', array('fields' => 'names'));
            if ($cuisines) {
                $title_parts['title'] .= ' - ' . implode(', ', $cuisines) . ' Restaurant';
            }
        } elseif (is_tax('cuisine')) {
            $term = get_queried_object();
            $title_parts['title'] = $term->name . ' Restaurants';
        }
        
        return $title_parts;
    }
    
    /**
     * Customize archive titles
     */
    public function customize_archive_titles($title) {
        if (is_category() || is_tag()) {
            $title = single_term_title('', false);
        } elseif (is_tax('cuisine')) {
            $term = get_queried_object();
            $title = $term->name . ' Restaurants';
        } elseif (is_tax('location')) {
            $term = get_queried_object();
            $title = 'Restaurants in ' . $term->name;
        } elseif (is_home()) {
            $title = 'Latest Restaurants';
        }
        
        return $title;
    }
    
    /**
     * Add canonical URL
     */
    public function add_canonical_url() {
        $canonical_url = '';
        
        if (is_single() || is_page()) {
            $canonical_url = get_permalink();
        } elseif (is_tax() || is_category() || is_tag()) {
            $canonical_url = get_term_link(get_queried_object());
        } elseif (is_home() || is_front_page()) {
            $canonical_url = home_url('/');
        } elseif (is_archive()) {
            $canonical_url = get_permalink();
        }
        
        if ($canonical_url) {
            echo '<link rel="canonical" href="' . esc_url($canonical_url) . '">' . "\n";
        }
    }
    
    /**
     * Customize robots meta
     */
    public function customize_robots_meta($robots) {
        // Allow all for restaurant pages
        if (is_single()) {
            $robots['index'] = true;
            $robots['follow'] = true;
        }
        
        // Noindex for empty archives
        if (is_archive() && !have_posts()) {
            $robots['index'] = false;
        }
        
        return $robots;
    }
}

// Initialize SEO features
new NearMenus_SEO();

/**
 * Generate sitemap for restaurants
 */
function nearmenus_generate_sitemap() {
    $sitemap = '<?xml version="1.0" encoding="UTF-8"?>' . "\n";
    $sitemap .= '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">' . "\n";
    
    // Homepage
    $sitemap .= '<url>' . "\n";
    $sitemap .= '<loc>' . home_url('/') . '</loc>' . "\n";
    $sitemap .= '<changefreq>daily</changefreq>' . "\n";
    $sitemap .= '<priority>1.0</priority>' . "\n";
    $sitemap .= '</url>' . "\n";
    
    // Restaurant posts
    $restaurants = get_posts(array(
        'post_type' => 'post',
        'posts_per_page' => -1,
        'post_status' => 'publish',
    ));
    
    foreach ($restaurants as $restaurant) {
        $sitemap .= '<url>' . "\n";
        $sitemap .= '<loc>' . get_permalink($restaurant->ID) . '</loc>' . "\n";
        $sitemap .= '<lastmod>' . mysql2date('Y-m-d', $restaurant->post_modified) . '</lastmod>' . "\n";
        $sitemap .= '<changefreq>weekly</changefreq>' . "\n";
        $sitemap .= '<priority>0.8</priority>' . "\n";
        $sitemap .= '</url>' . "\n";
    }
    
    // Taxonomy pages
    $taxonomies = array('cuisine', 'location', 'price-range', 'features');
    foreach ($taxonomies as $taxonomy) {
        $terms = get_terms(array(
            'taxonomy' => $taxonomy,
            'hide_empty' => true,
        ));
        
        foreach ($terms as $term) {
            $sitemap .= '<url>' . "\n";
            $sitemap .= '<loc>' . get_term_link($term) . '</loc>' . "\n";
            $sitemap .= '<changefreq>monthly</changefreq>' . "\n";
            $sitemap .= '<priority>0.6</priority>' . "\n";
            $sitemap .= '</url>' . "\n";
        }
    }
    
    $sitemap .= '</urlset>';
    
    return $sitemap;
}