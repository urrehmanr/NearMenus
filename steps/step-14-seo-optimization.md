# Step 14: SEO Optimization

## Overview
This step implements comprehensive SEO optimization to improve search engine visibility, ranking, and performance. We'll focus on structured data, meta tags, OpenGraph integration, XML sitemaps, and technical SEO best practices with conditional asset loading.

## Objectives
- Implement structured data (Schema.org)
- Add comprehensive meta tags and OpenGraph
- Optimize for Core Web Vitals
- Create SEO-friendly URL structures
- Implement JSON-LD markup
- Add social media optimization
- Ensure mobile-first indexing compatibility
- Implement conditional SEO asset loading

## Implementation

### 1. SEO Foundation

Create `inc/seo.php`:

```php
<?php
/**
 * GPress SEO Features
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Initialize SEO system
 */
function gpress_init_seo_system() {
    // Core SEO setup
    add_action('after_setup_theme', 'gpress_seo_setup');
    
    // Conditional SEO asset loading
    add_action('wp_enqueue_scripts', 'gpress_conditional_seo_assets');
    
    // Meta tags and structured data
    add_action('wp_head', 'gpress_add_meta_tags', 5);
    add_action('wp_head', 'gpress_add_opengraph_tags', 10);
    add_action('wp_head', 'gpress_add_twitter_cards', 15);
    add_action('wp_head', 'gpress_add_json_ld', 20);
    add_action('wp_head', 'gpress_add_canonical_url', 25);
    add_action('wp_head', 'gpress_add_robots_meta', 30);
    
    // Content optimization
    add_filter('get_the_excerpt', 'gpress_optimize_excerpt_for_seo');
}
add_action('after_setup_theme', 'gpress_init_seo_system');

/**
 * SEO setup
 */
function gpress_seo_setup() {
    // Add theme support for title-tag
    add_theme_support('title-tag');
    
    // Add custom SEO meta tags
    add_action('wp_head', 'modernblog2025_add_meta_tags', 5);
    
    // Add OpenGraph tags
    add_action('wp_head', 'modernblog2025_add_opengraph_tags', 10);
    
    // Add Twitter Card tags
    add_action('wp_head', 'modernblog2025_add_twitter_cards', 15);
    
    // Add JSON-LD structured data
    add_action('wp_head', 'modernblog2025_add_json_ld', 20);
    
    // Optimize meta descriptions
    add_filter('get_the_excerpt', 'modernblog2025_optimize_excerpt_for_seo');
    
    // Add canonical URLs
    add_action('wp_head', 'modernblog2025_add_canonical_url', 25);
    
    // Add robots meta tag
    add_action('wp_head', 'modernblog2025_add_robots_meta', 30);
}
add_action('after_setup_theme', 'modernblog2025_seo_setup');

/**
 * Add comprehensive meta tags
 */
function modernblog2025_add_meta_tags() {
    global $post;
    
    // Get page data
    $title = modernblog2025_get_seo_title();
    $description = modernblog2025_get_seo_description();
    $keywords = modernblog2025_get_seo_keywords();
    $author = modernblog2025_get_seo_author();
    
    // Essential meta tags
    echo '<meta charset="' . get_bloginfo('charset') . '">' . "\n";
    echo '<meta name="viewport" content="width=device-width, initial-scale=1.0">' . "\n";
    echo '<meta name="description" content="' . esc_attr($description) . '">' . "\n";
    
    if ($keywords) {
        echo '<meta name="keywords" content="' . esc_attr($keywords) . '">' . "\n";
    }
    
    if ($author) {
        echo '<meta name="author" content="' . esc_attr($author) . '">' . "\n";
    }
    
    // Generator tag (WordPress version)
    echo '<meta name="generator" content="WordPress ' . get_bloginfo('version') . '; ModernBlog2025 Theme">' . "\n";
    
    // Language meta
    echo '<meta name="language" content="' . get_locale() . '">' . "\n";
    
    // Additional meta tags for posts
    if (is_single() && $post) {
        $published = get_the_date('c', $post);
        $modified = get_the_modified_date('c', $post);
        
        echo '<meta name="article:published_time" content="' . esc_attr($published) . '">' . "\n";
        echo '<meta name="article:modified_time" content="' . esc_attr($modified) . '">' . "\n";
        
        // Article tags
        $tags = get_the_tags($post->ID);
        if ($tags) {
            foreach ($tags as $tag) {
                echo '<meta name="article:tag" content="' . esc_attr($tag->name) . '">' . "\n";
            }
        }
        
        // Article section (category)
        $categories = get_the_category($post->ID);
        if ($categories) {
            echo '<meta name="article:section" content="' . esc_attr($categories[0]->name) . '">' . "\n";
        }
    }
    
    // Site verification meta tags (placeholders)
    $google_verification = get_theme_mod('google_site_verification', '');
    $bing_verification = get_theme_mod('bing_site_verification', '');
    
    if ($google_verification) {
        echo '<meta name="google-site-verification" content="' . esc_attr($google_verification) . '">' . "\n";
    }
    
    if ($bing_verification) {
        echo '<meta name="msvalidate.01" content="' . esc_attr($bing_verification) . '">' . "\n";
    }
}

/**
 * Add OpenGraph tags
 */
function modernblog2025_add_opengraph_tags() {
    global $post;
    
    $title = modernblog2025_get_seo_title();
    $description = modernblog2025_get_seo_description();
    $url = modernblog2025_get_canonical_url();
    $image = modernblog2025_get_seo_image();
    $site_name = get_bloginfo('name');
    
    // Essential OpenGraph tags
    echo '<meta property="og:title" content="' . esc_attr($title) . '">' . "\n";
    echo '<meta property="og:description" content="' . esc_attr($description) . '">' . "\n";
    echo '<meta property="og:url" content="' . esc_url($url) . '">' . "\n";
    echo '<meta property="og:site_name" content="' . esc_attr($site_name) . '">' . "\n";
    echo '<meta property="og:locale" content="' . get_locale() . '">' . "\n";
    
    // Type-specific OpenGraph tags
    if (is_single() || is_page()) {
        echo '<meta property="og:type" content="article">' . "\n";
        
        if ($post) {
            $author_name = get_the_author_meta('display_name', $post->post_author);
            echo '<meta property="article:author" content="' . esc_attr($author_name) . '">' . "\n";
            echo '<meta property="article:published_time" content="' . get_the_date('c', $post) . '">' . "\n";
            echo '<meta property="article:modified_time" content="' . get_the_modified_date('c', $post) . '">' . "\n";
        }
    } else {
        echo '<meta property="og:type" content="website">' . "\n";
    }
    
    // Image
    if ($image) {
        echo '<meta property="og:image" content="' . esc_url($image['url']) . '">' . "\n";
        echo '<meta property="og:image:width" content="' . esc_attr($image['width']) . '">' . "\n";
        echo '<meta property="og:image:height" content="' . esc_attr($image['height']) . '">' . "\n";
        echo '<meta property="og:image:alt" content="' . esc_attr($image['alt']) . '">' . "\n";
    }
}

/**
 * Add Twitter Card tags
 */
function modernblog2025_add_twitter_cards() {
    $title = modernblog2025_get_seo_title();
    $description = modernblog2025_get_seo_description();
    $image = modernblog2025_get_seo_image();
    $twitter_handle = get_theme_mod('twitter_handle', '');
    
    // Twitter Card type
    $card_type = $image ? 'summary_large_image' : 'summary';
    echo '<meta name="twitter:card" content="' . esc_attr($card_type) . '">' . "\n";
    echo '<meta name="twitter:title" content="' . esc_attr($title) . '">' . "\n";
    echo '<meta name="twitter:description" content="' . esc_attr($description) . '">' . "\n";
    
    if ($twitter_handle) {
        echo '<meta name="twitter:site" content="@' . esc_attr(ltrim($twitter_handle, '@')) . '">' . "\n";
        echo '<meta name="twitter:creator" content="@' . esc_attr(ltrim($twitter_handle, '@')) . '">' . "\n";
    }
    
    if ($image) {
        echo '<meta name="twitter:image" content="' . esc_url($image['url']) . '">' . "\n";
        echo '<meta name="twitter:image:alt" content="' . esc_attr($image['alt']) . '">' . "\n";
    }
}

/**
 * Get SEO title
 */
function modernblog2025_get_seo_title() {
    if (is_home() && !is_front_page()) {
        return get_the_title(get_option('page_for_posts')) . ' - ' . get_bloginfo('name');
    } elseif (is_front_page()) {
        $title = get_bloginfo('name');
        $tagline = get_bloginfo('description');
        return $tagline ? $title . ' - ' . $tagline : $title;
    } elseif (is_single() || is_page()) {
        return get_the_title() . ' - ' . get_bloginfo('name');
    } elseif (is_category()) {
        return 'Category: ' . single_cat_title('', false) . ' - ' . get_bloginfo('name');
    } elseif (is_tag()) {
        return 'Tag: ' . single_tag_title('', false) . ' - ' . get_bloginfo('name');
    } elseif (is_author()) {
        return 'Author: ' . get_the_author() . ' - ' . get_bloginfo('name');
    } elseif (is_archive()) {
        return get_the_archive_title() . ' - ' . get_bloginfo('name');
    } elseif (is_search()) {
        return 'Search Results for: ' . get_search_query() . ' - ' . get_bloginfo('name');
    } else {
        return get_bloginfo('name') . ' - ' . get_bloginfo('description');
    }
}

/**
 * Get SEO description
 */
function modernblog2025_get_seo_description() {
    if (is_single() || is_page()) {
        $excerpt = get_the_excerpt();
        return $excerpt ? wp_trim_words($excerpt, 25) : wp_trim_words(get_the_content(), 25);
    } elseif (is_category()) {
        $description = category_description();
        return $description ? wp_trim_words(strip_tags($description), 25) : 'Browse posts in the ' . single_cat_title('', false) . ' category.';
    } elseif (is_tag()) {
        $description = tag_description();
        return $description ? wp_trim_words(strip_tags($description), 25) : 'Browse posts tagged with ' . single_tag_title('', false) . '.';
    } elseif (is_author()) {
        $description = get_the_author_meta('description');
        return $description ? wp_trim_words($description, 25) : 'Posts by ' . get_the_author() . '.';
    } elseif (is_archive()) {
        return 'Browse our archive of ' . get_the_archive_title() . ' posts.';
    } elseif (is_search()) {
        return 'Search results for: ' . get_search_query();
    } else {
        return get_bloginfo('description');
    }
}

/**
 * Get SEO keywords
 */
function modernblog2025_get_seo_keywords() {
    if (is_single()) {
        $tags = get_the_tags();
        if ($tags) {
            return implode(', ', array_map(function($tag) {
                return $tag->name;
            }, $tags));
        }
    } elseif (is_category()) {
        return single_cat_title('', false);
    } elseif (is_tag()) {
        return single_tag_title('', false);
    }
    
    return '';
}

/**
 * Get SEO author
 */
function modernblog2025_get_seo_author() {
    if (is_single()) {
        return get_the_author();
    }
    
    return get_bloginfo('name');
}

/**
 * Get SEO image
 */
function modernblog2025_get_seo_image() {
    $image = null;
    
    if (is_single() || is_page()) {
        $featured_image_id = get_post_thumbnail_id();
        if ($featured_image_id) {
            $image_data = wp_get_attachment_image_src($featured_image_id, 'large');
            if ($image_data) {
                $image = array(
                    'url' => $image_data[0],
                    'width' => $image_data[1],
                    'height' => $image_data[2],
                    'alt' => get_post_meta($featured_image_id, '_wp_attachment_image_alt', true)
                );
            }
        }
    }
    
    // Fallback to site logo or default image
    if (!$image) {
        $site_logo_id = get_theme_mod('custom_logo');
        if ($site_logo_id) {
            $image_data = wp_get_attachment_image_src($site_logo_id, 'large');
            if ($image_data) {
                $image = array(
                    'url' => $image_data[0],
                    'width' => $image_data[1],
                    'height' => $image_data[2],
                    'alt' => get_bloginfo('name') . ' logo'
                );
            }
        }
    }
    
    return $image;
}

/**
 * Get canonical URL
 */
function modernblog2025_get_canonical_url() {
    if (is_singular()) {
        return get_permalink();
    } elseif (is_category()) {
        return get_category_link(get_queried_object_id());
    } elseif (is_tag()) {
        return get_tag_link(get_queried_object_id());
    } elseif (is_author()) {
        return get_author_posts_url(get_queried_object_id());
    } elseif (is_home()) {
        return home_url('/');
    } else {
        return home_url(add_query_arg(null, null));
    }
}

/**
 * Add canonical URL
 */
function modernblog2025_add_canonical_url() {
    $canonical_url = modernblog2025_get_canonical_url();
    echo '<link rel="canonical" href="' . esc_url($canonical_url) . '">' . "\n";
}

/**
 * Add robots meta tag
 */
function modernblog2025_add_robots_meta() {
    $robots = array();
    
    if (is_search() || is_404()) {
        $robots[] = 'noindex';
        $robots[] = 'nofollow';
    } elseif (is_attachment()) {
        $robots[] = 'noindex';
        $robots[] = 'follow';
    } else {
        $robots[] = 'index';
        $robots[] = 'follow';
    }
    
    // Add additional directives
    $robots[] = 'max-snippet:-1';
    $robots[] = 'max-image-preview:large';
    $robots[] = 'max-video-preview:-1';
    
    $robots_content = implode(', ', $robots);
    echo '<meta name="robots" content="' . esc_attr($robots_content) . '">' . "\n";
}

/**
 * Optimize excerpt for SEO
 */
function modernblog2025_optimize_excerpt_for_seo($excerpt) {
    if (empty($excerpt)) {
        $excerpt = get_the_content();
    }
    
    // Remove shortcodes and HTML tags
    $excerpt = strip_shortcodes($excerpt);
    $excerpt = wp_strip_all_tags($excerpt);
    
    // Optimize length for meta description
    return wp_trim_words($excerpt, 25);
}
```

### 2. Structured Data (JSON-LD)

Create `inc/structured-data.php`:

```php
<?php
/**
 * Structured Data (Schema.org)
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Add JSON-LD structured data
 */
function modernblog2025_add_json_ld() {
    $structured_data = array();
    
    // Website/Organization schema
    $structured_data[] = modernblog2025_get_organization_schema();
    
    // Page-specific schemas
    if (is_single()) {
        $structured_data[] = modernblog2025_get_article_schema();
    } elseif (is_page()) {
        $structured_data[] = modernblog2025_get_webpage_schema();
    } elseif (is_home() || is_front_page()) {
        $structured_data[] = modernblog2025_get_website_schema();
    } elseif (is_author()) {
        $structured_data[] = modernblog2025_get_person_schema();
    }
    
    // Breadcrumb schema
    if (!is_front_page()) {
        $breadcrumb_schema = modernblog2025_get_breadcrumb_schema();
        if ($breadcrumb_schema) {
            $structured_data[] = $breadcrumb_schema;
        }
    }
    
    // Output structured data
    foreach ($structured_data as $schema) {
        if ($schema) {
            echo '<script type="application/ld+json">' . wp_json_encode($schema, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) . '</script>' . "\n";
        }
    }
}

/**
 * Get Organization schema
 */
function modernblog2025_get_organization_schema() {
    $site_name = get_bloginfo('name');
    $site_url = home_url('/');
    $site_description = get_bloginfo('description');
    
    $schema = array(
        '@context' => 'https://schema.org',
        '@type' => 'Organization',
        'name' => $site_name,
        'url' => $site_url,
        'description' => $site_description,
        'foundingDate' => get_option('date_format', date('Y-m-d')),
        'contactPoint' => array(
            '@type' => 'ContactPoint',
            'contactType' => 'customer service',
            'url' => $site_url . 'contact/'
        )
    );
    
    // Add logo if available
    $logo_id = get_theme_mod('custom_logo');
    if ($logo_id) {
        $logo_data = wp_get_attachment_image_src($logo_id, 'full');
        if ($logo_data) {
            $schema['logo'] = array(
                '@type' => 'ImageObject',
                'url' => $logo_data[0],
                'width' => $logo_data[1],
                'height' => $logo_data[2]
            );
        }
    }
    
    // Add social media profiles
    $social_profiles = array();
    $facebook = get_theme_mod('facebook_url', '');
    $twitter = get_theme_mod('twitter_url', '');
    $instagram = get_theme_mod('instagram_url', '');
    $linkedin = get_theme_mod('linkedin_url', '');
    
    if ($facebook) $social_profiles[] = $facebook;
    if ($twitter) $social_profiles[] = $twitter;
    if ($instagram) $social_profiles[] = $instagram;
    if ($linkedin) $social_profiles[] = $linkedin;
    
    if (!empty($social_profiles)) {
        $schema['sameAs'] = $social_profiles;
    }
    
    return $schema;
}

/**
 * Get Article schema
 */
function modernblog2025_get_article_schema() {
    global $post;
    
    if (!$post) {
        return null;
    }
    
    $author = get_the_author_meta('display_name', $post->post_author);
    $author_url = get_author_posts_url($post->post_author);
    $featured_image = modernblog2025_get_seo_image();
    
    $schema = array(
        '@context' => 'https://schema.org',
        '@type' => 'Article',
        'headline' => get_the_title(),
        'description' => modernblog2025_get_seo_description(),
        'url' => get_permalink(),
        'datePublished' => get_the_date('c'),
        'dateModified' => get_the_modified_date('c'),
        'author' => array(
            '@type' => 'Person',
            'name' => $author,
            'url' => $author_url
        ),
        'publisher' => array(
            '@type' => 'Organization',
            'name' => get_bloginfo('name'),
            'url' => home_url('/')
        ),
        'mainEntityOfPage' => array(
            '@type' => 'WebPage',
            '@id' => get_permalink()
        )
    );
    
    // Add featured image
    if ($featured_image) {
        $schema['image'] = array(
            '@type' => 'ImageObject',
            'url' => $featured_image['url'],
            'width' => $featured_image['width'],
            'height' => $featured_image['height']
        );
    }
    
    // Add categories and tags
    $categories = get_the_category();
    if ($categories) {
        $schema['articleSection'] = array_map(function($category) {
            return $category->name;
        }, $categories);
    }
    
    $tags = get_the_tags();
    if ($tags) {
        $schema['keywords'] = array_map(function($tag) {
            return $tag->name;
        }, $tags);
    }
    
    // Add word count and reading time
    $content = get_the_content();
    $word_count = str_word_count(strip_tags($content));
    $reading_time = max(1, ceil($word_count / 200));
    
    $schema['wordCount'] = $word_count;
    $schema['timeRequired'] = 'PT' . $reading_time . 'M';
    
    return $schema;
}

/**
 * Get WebPage schema
 */
function modernblog2025_get_webpage_schema() {
    $schema = array(
        '@context' => 'https://schema.org',
        '@type' => 'WebPage',
        'name' => get_the_title(),
        'description' => modernblog2025_get_seo_description(),
        'url' => get_permalink(),
        'datePublished' => get_the_date('c'),
        'dateModified' => get_the_modified_date('c'),
        'isPartOf' => array(
            '@type' => 'WebSite',
            'name' => get_bloginfo('name'),
            'url' => home_url('/')
        )
    );
    
    return $schema;
}

/**
 * Get Website schema
 */
function modernblog2025_get_website_schema() {
    $schema = array(
        '@context' => 'https://schema.org',
        '@type' => 'WebSite',
        'name' => get_bloginfo('name'),
        'description' => get_bloginfo('description'),
        'url' => home_url('/'),
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
 * Get Person schema for author pages
 */
function modernblog2025_get_person_schema() {
    $author_id = get_queried_object_id();
    $author_name = get_the_author_meta('display_name', $author_id);
    $author_description = get_the_author_meta('description', $author_id);
    $author_url = get_author_posts_url($author_id);
    $author_email = get_the_author_meta('email', $author_id);
    
    $schema = array(
        '@context' => 'https://schema.org',
        '@type' => 'Person',
        'name' => $author_name,
        'description' => $author_description,
        'url' => $author_url,
        'sameAs' => array()
    );
    
    // Add social profiles
    $website = get_the_author_meta('url', $author_id);
    $twitter = get_the_author_meta('twitter', $author_id);
    $facebook = get_the_author_meta('facebook', $author_id);
    
    if ($website) $schema['sameAs'][] = $website;
    if ($twitter) $schema['sameAs'][] = 'https://twitter.com/' . ltrim($twitter, '@');
    if ($facebook) $schema['sameAs'][] = $facebook;
    
    return $schema;
}

/**
 * Get Breadcrumb schema
 */
function modernblog2025_get_breadcrumb_schema() {
    $breadcrumbs = array();
    $position = 1;
    
    // Home
    $breadcrumbs[] = array(
        '@type' => 'ListItem',
        'position' => $position++,
        'name' => get_bloginfo('name'),
        'item' => home_url('/')
    );
    
    if (is_single()) {
        global $post;
        
        // Add categories
        $categories = get_the_category($post->ID);
        if ($categories) {
            $category = $categories[0];
            $breadcrumbs[] = array(
                '@type' => 'ListItem',
                'position' => $position++,
                'name' => $category->name,
                'item' => get_category_link($category->term_id)
            );
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
        
        // Parent pages
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
        
        // Current page
        $breadcrumbs[] = array(
            '@type' => 'ListItem',
            'position' => $position++,
            'name' => get_the_title(),
            'item' => get_permalink()
        );
        
    } elseif (is_category()) {
        $category = get_queried_object();
        $breadcrumbs[] = array(
            '@type' => 'ListItem',
            'position' => $position++,
            'name' => $category->name,
            'item' => get_category_link($category->term_id)
        );
    }
    
    if (count($breadcrumbs) > 1) {
        return array(
            '@context' => 'https://schema.org',
            '@type' => 'BreadcrumbList',
            'itemListElement' => $breadcrumbs
        );
    }
    
    return null;
}
```

### 3. SEO Customizer Settings

Create `inc/seo-customizer.php`:

```php
<?php
/**
 * SEO Customizer Settings
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Add SEO settings to customizer
 */
function modernblog2025_seo_customizer($wp_customize) {
    // SEO Section
    $wp_customize->add_section('modernblog2025_seo', array(
        'title' => __('SEO Settings', 'modernblog2025'),
        'priority' => 35,
        'description' => __('Configure SEO and social media settings for your site.', 'modernblog2025')
    ));
    
    // Site verification settings
    $wp_customize->add_setting('google_site_verification', array(
        'default' => '',
        'sanitize_callback' => 'sanitize_text_field',
        'transport' => 'refresh'
    ));
    
    $wp_customize->add_control('google_site_verification', array(
        'label' => __('Google Site Verification', 'modernblog2025'),
        'description' => __('Enter your Google Search Console verification code', 'modernblog2025'),
        'section' => 'modernblog2025_seo',
        'type' => 'text'
    ));
    
    $wp_customize->add_setting('bing_site_verification', array(
        'default' => '',
        'sanitize_callback' => 'sanitize_text_field',
        'transport' => 'refresh'
    ));
    
    $wp_customize->add_control('bing_site_verification', array(
        'label' => __('Bing Site Verification', 'modernblog2025'),
        'description' => __('Enter your Bing Webmaster Tools verification code', 'modernblog2025'),
        'section' => 'modernblog2025_seo',
        'type' => 'text'
    ));
    
    // Social media settings
    $wp_customize->add_setting('twitter_handle', array(
        'default' => '',
        'sanitize_callback' => 'sanitize_text_field',
        'transport' => 'refresh'
    ));
    
    $wp_customize->add_control('twitter_handle', array(
        'label' => __('Twitter Handle', 'modernblog2025'),
        'description' => __('Enter your Twitter username (without @)', 'modernblog2025'),
        'section' => 'modernblog2025_seo',
        'type' => 'text'
    ));
    
    $wp_customize->add_setting('facebook_url', array(
        'default' => '',
        'sanitize_callback' => 'esc_url_raw',
        'transport' => 'refresh'
    ));
    
    $wp_customize->add_control('facebook_url', array(
        'label' => __('Facebook Page URL', 'modernblog2025'),
        'description' => __('Enter your Facebook page URL', 'modernblog2025'),
        'section' => 'modernblog2025_seo',
        'type' => 'url'
    ));
    
    $wp_customize->add_setting('instagram_url', array(
        'default' => '',
        'sanitize_callback' => 'esc_url_raw',
        'transport' => 'refresh'
    ));
    
    $wp_customize->add_control('instagram_url', array(
        'label' => __('Instagram Profile URL', 'modernblog2025'),
        'description' => __('Enter your Instagram profile URL', 'modernblog2025'),
        'section' => 'modernblog2025_seo',
        'type' => 'url'
    ));
    
    $wp_customize->add_setting('linkedin_url', array(
        'default' => '',
        'sanitize_callback' => 'esc_url_raw',
        'transport' => 'refresh'
    ));
    
    $wp_customize->add_control('linkedin_url', array(
        'label' => __('LinkedIn Profile URL', 'modernblog2025'),
        'description' => __('Enter your LinkedIn profile URL', 'modernblog2025'),
        'section' => 'modernblog2025_seo',
        'type' => 'url'
    ));
    
    // Default social image
    $wp_customize->add_setting('default_social_image', array(
        'default' => '',
        'sanitize_callback' => 'absint',
        'transport' => 'refresh'
    ));
    
    $wp_customize->add_control(new WP_Customize_Media_Control($wp_customize, 'default_social_image', array(
        'label' => __('Default Social Media Image', 'modernblog2025'),
        'description' => __('Choose a default image for social media sharing (1200x630px recommended)', 'modernblog2025'),
        'section' => 'modernblog2025_seo',
        'mime_type' => 'image'
    )));
}
add_action('customize_register', 'modernblog2025_seo_customizer');
```

### 4. Technical SEO Optimizations

Create `inc/technical-seo.php`:

```php
<?php
/**
 * Technical SEO Optimizations
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Technical SEO setup
 */
function modernblog2025_technical_seo_setup() {
    // Clean up WordPress head
    add_action('init', 'modernblog2025_clean_wp_head');
    
    // Add preload and prefetch tags
    add_action('wp_head', 'modernblog2025_add_resource_hints', 5);
    
    // Optimize feed links
    add_action('wp_head', 'modernblog2025_add_feed_links', 10);
    
    // Add hreflang for internationalization
    add_action('wp_head', 'modernblog2025_add_hreflang', 15);
    
    // Optimize images for SEO
    add_filter('wp_get_attachment_image_attributes', 'modernblog2025_optimize_image_seo', 10, 3);
    
    // Add structured data for images
    add_action('wp_footer', 'modernblog2025_add_image_structured_data');
}
add_action('after_setup_theme', 'modernblog2025_technical_seo_setup');

/**
 * Clean up WordPress head
 */
function modernblog2025_clean_wp_head() {
    // Remove unnecessary meta tags
    remove_action('wp_head', 'wp_generator');
    remove_action('wp_head', 'wlwmanifest_link');
    remove_action('wp_head', 'rsd_link');
    remove_action('wp_head', 'wp_shortlink_wp_head');
    
    // Remove emoji scripts (if not needed)
    if (!get_theme_mod('enable_emoji_support', false)) {
        remove_action('wp_head', 'print_emoji_detection_script', 7);
        remove_action('wp_print_styles', 'print_emoji_styles');
        remove_action('admin_print_scripts', 'print_emoji_detection_script');
        remove_action('admin_print_styles', 'print_emoji_styles');
    }
    
    // Remove REST API link (if not needed)
    if (!get_theme_mod('enable_rest_api_head_link', false)) {
        remove_action('wp_head', 'rest_output_link_wp_head');
    }
}

/**
 * Add resource hints
 */
function modernblog2025_add_resource_hints() {
    // DNS prefetch for external domains
    $external_domains = array(
        '//fonts.googleapis.com',
        '//fonts.gstatic.com',
        '//gravatar.com',
        '//secure.gravatar.com'
    );
    
    foreach ($external_domains as $domain) {
        echo '<link rel="dns-prefetch" href="' . esc_url($domain) . '">' . "\n";
    }
    
    // Preload critical resources
    $preload_resources = array(
        array(
            'href' => get_theme_file_uri('/assets/css/critical.min.css'),
            'as' => 'style'
        ),
        array(
            'href' => get_theme_file_uri('/assets/fonts/primary-font.woff2'),
            'as' => 'font',
            'type' => 'font/woff2',
            'crossorigin' => 'anonymous'
        )
    );
    
    foreach ($preload_resources as $resource) {
        $attributes = array();
        foreach ($resource as $key => $value) {
            $attributes[] = $key . '="' . esc_attr($value) . '"';
        }
        echo '<link rel="preload" ' . implode(' ', $attributes) . '>' . "\n";
    }
}

/**
 * Add optimized feed links
 */
function modernblog2025_add_feed_links() {
    echo '<link rel="alternate" type="application/rss+xml" title="' . esc_attr(get_bloginfo('name')) . ' RSS Feed" href="' . esc_url(get_feed_link()) . '">' . "\n";
    echo '<link rel="alternate" type="application/atom+xml" title="' . esc_attr(get_bloginfo('name')) . ' Atom Feed" href="' . esc_url(get_feed_link('atom')) . '">' . "\n";
}

/**
 * Add hreflang tags
 */
function modernblog2025_add_hreflang() {
    $current_locale = get_locale();
    $current_url = modernblog2025_get_canonical_url();
    
    // Main language
    echo '<link rel="alternate" hreflang="' . esc_attr($current_locale) . '" href="' . esc_url($current_url) . '">' . "\n";
    
    // Add x-default for international targeting
    echo '<link rel="alternate" hreflang="x-default" href="' . esc_url($current_url) . '">' . "\n";
}

/**
 * Optimize images for SEO
 */
function modernblog2025_optimize_image_seo($attr, $attachment, $size) {
    // Ensure images have loading attribute
    if (!isset($attr['loading'])) {
        $attr['loading'] = 'lazy';
    }
    
    // Add decoding attribute
    $attr['decoding'] = 'async';
    
    // Optimize fetchpriority for above-the-fold images
    if ($size === 'large' || $size === 'full') {
        $attr['fetchpriority'] = 'high';
    }
    
    return $attr;
}

/**
 * Add image structured data
 */
function modernblog2025_add_image_structured_data() {
    if (is_single() || is_page()) {
        global $post;
        
        // Get all images in content
        $content = get_the_content();
        preg_match_all('/<img[^>]+>/i', $content, $images);
        
        if (!empty($images[0])) {
            $image_schemas = array();
            
            foreach ($images[0] as $img_tag) {
                preg_match('/src=["\']([^"\']+)["\']/', $img_tag, $src_match);
                preg_match('/alt=["\']([^"\']+)["\']/', $img_tag, $alt_match);
                
                if (!empty($src_match[1])) {
                    $image_schemas[] = array(
                        '@type' => 'ImageObject',
                        'url' => $src_match[1],
                        'caption' => !empty($alt_match[1]) ? $alt_match[1] : '',
                        'contentUrl' => $src_match[1]
                    );
                }
            }
            
            if (!empty($image_schemas)) {
                $schema = array(
                    '@context' => 'https://schema.org',
                    '@type' => 'ImageGallery',
                    'image' => $image_schemas
                );
                
                echo '<script type="application/ld+json">' . wp_json_encode($schema) . '</script>' . "\n";
            }
        }
    }
}

/**
 * Add XML sitemap functionality
 */
function modernblog2025_add_sitemap_support() {
    // WordPress 5.5+ has built-in sitemaps, enhance them
    add_filter('wp_sitemaps_max_urls', function() {
        return 2000; // Increase URL limit
    });
    
    // Add custom post types to sitemap
    add_filter('wp_sitemaps_post_types', function($post_types) {
        $post_types['portfolio'] = get_post_type_object('portfolio');
        $post_types['testimonial'] = get_post_type_object('testimonial');
        return $post_types;
    });
    
    // Exclude specific pages from sitemap
    add_filter('wp_sitemaps_posts_query_args', function($args, $post_type) {
        if ($post_type === 'page') {
            $excluded_pages = array('privacy-policy', 'terms-conditions');
            $args['meta_query'] = array(
                array(
                    'key' => '_wp_page_template',
                    'value' => $excluded_pages,
                    'compare' => 'NOT IN'
                )
            );
        }
        return $args;
    }, 10, 2);
}
add_action('init', 'modernblog2025_add_sitemap_support');

/**
 * Add robots.txt optimization
 */
function modernblog2025_optimize_robots_txt($output) {
    $output .= "# ModernBlog2025 Theme Optimizations\n";
    $output .= "User-agent: *\n";
    $output .= "Disallow: /wp-admin/\n";
    $output .= "Disallow: /wp-includes/\n";
    $output .= "Disallow: /wp-content/plugins/\n";
    $output .= "Disallow: /wp-content/themes/\n";
    $output .= "Disallow: /trackback/\n";
    $output .= "Disallow: /feed/\n";
    $output .= "Disallow: /comments/\n";
    $output .= "Disallow: /?s=\n";
    $output .= "Disallow: /search/\n";
    $output .= "\n";
    
    // Add sitemap reference
    $output .= "Sitemap: " . home_url('/wp-sitemap.xml') . "\n";
    
    return $output;
}
add_filter('robots_txt', 'modernblog2025_optimize_robots_txt');
```

### 5. Performance SEO

Create `inc/performance-seo.php`:

```php
<?php
/**
 * Performance-related SEO optimizations
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Performance SEO setup
 */
function modernblog2025_performance_seo_setup() {
    // Optimize database queries
    add_action('pre_get_posts', 'modernblog2025_optimize_queries');
    
    // Add lazy loading enhancements
    add_filter('wp_get_attachment_image_attributes', 'modernblog2025_enhance_lazy_loading', 10, 3);
    
    // Optimize embed performance
    add_action('init', 'modernblog2025_optimize_embeds');
    
    // Add Core Web Vitals monitoring
    add_action('wp_footer', 'modernblog2025_add_web_vitals_monitoring');
}
add_action('after_setup_theme', 'modernblog2025_performance_seo_setup');

/**
 * Optimize database queries
 */
function modernblog2025_optimize_queries($query) {
    if (!is_admin() && $query->is_main_query()) {
        // Limit posts per page for better performance
        if (is_home()) {
            $query->set('posts_per_page', 12);
        }
        
        // Optimize archive queries
        if (is_category() || is_tag() || is_author()) {
            $query->set('posts_per_page', 10);
            // Only get necessary fields
            $query->set('fields', 'ids');
        }
        
        // Exclude certain post types from search
        if (is_search()) {
            $query->set('post_type', 'post');
        }
    }
}

/**
 * Enhance lazy loading for SEO
 */
function modernblog2025_enhance_lazy_loading($attr, $attachment, $size) {
    // Skip lazy loading for above-the-fold images
    $priority_images = array('large', 'full');
    
    if (in_array($size, $priority_images) && is_single()) {
        // First image in post should load immediately
        static $first_image = true;
        if ($first_image) {
            $attr['loading'] = 'eager';
            $attr['fetchpriority'] = 'high';
            $first_image = false;
        }
    }
    
    return $attr;
}

/**
 * Optimize embeds for performance
 */
function modernblog2025_optimize_embeds() {
    // Remove embed discovery for better performance
    remove_action('wp_head', 'wp_oembed_add_discovery_links');
    remove_action('wp_head', 'wp_oembed_add_host_js');
    
    // Add lazy loading to video embeds
    add_filter('embed_oembed_html', function($html) {
        if (strpos($html, 'youtube') !== false || strpos($html, 'vimeo') !== false) {
            $html = str_replace('<iframe', '<iframe loading="lazy"', $html);
        }
        return $html;
    });
}

/**
 * Add Web Vitals monitoring
 */
function modernblog2025_add_web_vitals_monitoring() {
    if (defined('WP_DEBUG') && WP_DEBUG) {
        ?>
        <script>
        // Web Vitals monitoring for development
        function sendToAnalytics(metric) {
            console.log('Web Vital:', metric.name, metric.value, metric.rating);
            
            // Send to analytics service in production
            // gtag('event', metric.name, {
            //     value: Math.round(metric.name === 'CLS' ? metric.value * 1000 : metric.value),
            //     event_category: 'Web Vitals',
            //     event_label: metric.id,
            //     non_interaction: true,
            // });
        }
        
        // Load web-vitals library
        import('https://unpkg.com/web-vitals@3/dist/web-vitals.js').then(({getCLS, getFID, getFCP, getLCP, getTTFB}) => {
            getCLS(sendToAnalytics);
            getFID(sendToAnalytics);
            getFCP(sendToAnalytics);
            getLCP(sendToAnalytics);
            getTTFB(sendToAnalytics);
        });
        </script>
        <?php
    }
}
```

### 6. Update Functions.php

Add to `functions.php`:

```php
// SEO features
require_once get_theme_file_path('/inc/seo.php');
require_once get_theme_file_path('/inc/structured-data.php');
require_once get_theme_file_path('/inc/seo-customizer.php');
require_once get_theme_file_path('/inc/technical-seo.php');
require_once get_theme_file_path('/inc/performance-seo.php');
```

## SEO Testing Checklist

### Technical SEO
- [ ] Clean HTML5 markup validation
- [ ] Proper heading hierarchy (H1 → H2 → H3)
- [ ] Meta descriptions under 160 characters
- [ ] Title tags under 60 characters
- [ ] Canonical URLs implemented
- [ ] XML sitemap generated and accessible
- [ ] Robots.txt optimized

### Structured Data
- [ ] Organization/Person schema markup
- [ ] Article schema for blog posts
- [ ] Breadcrumb schema implemented
- [ ] Image schema for galleries
- [ ] Website schema for homepage
- [ ] Valid JSON-LD markup (test with Google's tool)

### Performance SEO
- [ ] Core Web Vitals scores (LCP < 2.5s, FID < 100ms, CLS < 0.1)
- [ ] Lighthouse SEO score 95+
- [ ] Mobile-first indexing compatibility
- [ ] Page speed optimization
- [ ] Image optimization and lazy loading

### Social Media
- [ ] OpenGraph tags implemented
- [ ] Twitter Card markup
- [ ] Social media images (1200x630px)
- [ ] Social profiles linked

### Content SEO
- [ ] Keyword optimization
- [ ] Internal linking structure
- [ ] Alt text for all images
- [ ] Descriptive URLs
- [ ] Content readability

## Testing Tools

1. **Google Search Console** - Index status and search performance
2. **Google PageSpeed Insights** - Core Web Vitals and performance
3. **Google Rich Results Test** - Structured data validation
4. **Facebook Sharing Debugger** - OpenGraph preview
5. **Twitter Card Validator** - Twitter Card preview
6. **Lighthouse** - Comprehensive SEO audit
7. **Screaming Frog** - Technical SEO crawling

## Next Steps

In Step 15, we'll implement semantic HTML structure to enhance accessibility and SEO further.

## Key Benefits

- Comprehensive structured data implementation
- Optimized meta tags and social sharing
- Technical SEO best practices
- Performance-focused SEO optimization
- Search engine visibility enhancement
- Social media integration
- Mobile-first SEO approach
- Core Web Vitals optimization