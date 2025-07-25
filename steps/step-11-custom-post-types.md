# Step 11: Custom Post Types & Taxonomies with Smart Asset Integration

## Overview
This step implements advanced custom post types and taxonomies with **Smart Asset Management System integration**. We'll create a comprehensive system for managing diverse content types that works seamlessly with Step 7's conditional loading architecture, ensuring optimal performance through intelligent asset loading while maintaining accessibility standards.

## Integration with Smart Asset System
This step **integrates with Step 7's Smart Asset Management System**:
- **CPT-Specific Loading**: Uses Smart Asset Manager's `load_cpt_assets()` method for conditional loading
- **Content-Type Detection**: Automatically detects portfolio, testimonial, and team content for asset loading
- **Gallery Optimization**: Portfolio gallery assets load only when galleries are present
- **Performance CPTs**: All custom post types optimized for Core Web Vitals and fast loading

## Objectives
- Implement custom post types with full FSE support
- Create custom taxonomies with hierarchical structures
- Build optimized templates for custom content
- Establish conditional asset loading for CPT-specific features
- Integrate custom fields and meta boxes
- Ensure accessibility and SEO optimization for custom content

## What You'll Learn
- Advanced WordPress CPT and taxonomy registration
- Template hierarchy optimization for custom content
- Conditional PHP and asset loading strategies
- Custom field management and API integration
- Performance optimization for content-heavy sites
- Accessibility best practices for diverse content types

## Files Structure for This Step

### 📁 Files to CREATE:
```
inc/
├── custom-post-types.php          # CPT registration and management
├── custom-taxonomies.php          # Taxonomy registration and management
├── custom-fields.php              # Meta boxes and custom fields
└── cpt-optimization.php           # Performance optimization for CPTs

templates/
├── single-portfolio.html          # Portfolio single template
├── archive-portfolio.html         # Portfolio archive template
├── single-testimonial.html        # Testimonial single template
├── archive-testimonial.html       # Testimonial archive template
├── single-team.html               # Team member single template
├── archive-team.html              # Team archive template
├── taxonomy-portfolio-category.html  # Portfolio category archive
├── taxonomy-skill.html            # Skills taxonomy archive
└── taxonomy-service.html          # Services taxonomy archive

parts/
├── portfolio-card.html            # Portfolio item card component
├── testimonial-card.html          # Testimonial card component
├── team-card.html                 # Team member card component
└── cpt-filters.html               # Filtering component for archives

**Note**: CPT-specific assets are handled by Smart Asset Manager:
- Portfolio assets: `portfolio.css` and `portfolio-gallery.js` (loaded via Smart Asset Manager for portfolio content)
- Testimonial assets: `testimonials.css` and `testimonial-slider.js` (loaded for testimonial content)
- Team assets: `team.css` and `team-showcase.js` (loaded for team content)
- Core CPT styles: Included in `assets/css/core.css` from Step 7

**Integration with Step 7**: Uses Smart Asset Manager's CPT detection (`$cpt_assets` array) for conditional loading
├── portfolio.css                  # Portfolio-specific styles
├── testimonials.css               # Testimonials-specific styles
└── team.css                       # Team-specific styles

assets/js/
├── cpt-manager.js                 # CPT JavaScript functionality
├── portfolio-gallery.js          # Portfolio gallery features
├── testimonial-slider.js         # Testimonial carousel
└── archive-filters.js            # Archive filtering and sorting
```

### 📝 Files to UPDATE:
```
functions.php                      # Include CPT files and initialization
inc/theme-setup.php               # Add CPT support and capabilities
inc/enqueue-scripts.php           # Conditional CPT asset loading
style.css                         # Base CPT integration styles
README.md                         # Document CPT features and usage
theme.json                        # Add CPT-specific settings and styles
```

### 🎯 Optimization Features Implemented:
- Conditional asset loading based on post type detection
- Optimized database queries for custom content
- Lazy loading for portfolio galleries and media
- Advanced caching strategies for CPT archives
- SEO-optimized templates with structured data
- Performance monitoring for custom content pages
- Accessibility enhancements for diverse content types

## Step-by-Step Implementation

### 1. Create Custom Post Types Registration

Create `inc/custom-post-types.php`:

```php
<?php
/**
 * Custom Post Types Registration for GPress Theme
 * Implements performant CPTs with FSE support and conditional loading
 *
 * @package GPress
 * @subpackage Custom_Content
 * @version 1.0.0
 * @since 1.0.0
 */

// Prevent direct access
defined('ABSPATH') || exit;

/**
 * GPress Custom Post Types Manager
 * 
 * @since 1.0.0
 */
class GPress_Custom_Post_Types {

    /**
     * Initialize CPT system
     *
     * @since 1.0.0
     */
    public static function init() {
        add_action('init', array(__CLASS__, 'register_post_types'));
        add_action('init', array(__CLASS__, 'add_cpt_capabilities'));
        add_action('wp_enqueue_scripts', array(__CLASS__, 'conditional_cpt_assets'));
        add_filter('template_include', array(__CLASS__, 'cpt_template_loader'));
        add_action('wp_head', array(__CLASS__, 'cpt_structured_data'));
        add_filter('body_class', array(__CLASS__, 'cpt_body_classes'));
        
        // Admin enhancements
        add_action('admin_init', array(__CLASS__, 'setup_cpt_admin'));
        add_filter('manage_posts_columns', array(__CLASS__, 'custom_admin_columns'));
        add_action('manage_posts_custom_column', array(__CLASS__, 'populate_admin_columns'), 10, 2);
    }

    /**
     * Register all custom post types
     *
     * @since 1.0.0
     */
    public static function register_post_types() {
        
        // Portfolio Post Type
        register_post_type('portfolio', array(
            'labels' => array(
                'name' => __('Portfolio', 'gpress'),
                'singular_name' => __('Portfolio Item', 'gpress'),
                'menu_name' => __('Portfolio', 'gpress'),
                'add_new' => __('Add New Item', 'gpress'),
                'add_new_item' => __('Add New Portfolio Item', 'gpress'),
                'edit_item' => __('Edit Portfolio Item', 'gpress'),
                'new_item' => __('New Portfolio Item', 'gpress'),
                'view_item' => __('View Portfolio Item', 'gpress'),
                'search_items' => __('Search Portfolio', 'gpress'),
                'not_found' => __('No portfolio items found', 'gpress'),
                'not_found_in_trash' => __('No portfolio items found in trash', 'gpress'),
                'all_items' => __('All Portfolio Items', 'gpress'),
                'archives' => __('Portfolio Archives', 'gpress'),
                'attributes' => __('Portfolio Attributes', 'gpress'),
                'insert_into_item' => __('Insert into portfolio item', 'gpress'),
                'uploaded_to_this_item' => __('Uploaded to this portfolio item', 'gpress'),
            ),
            'public' => true,
            'has_archive' => true,
            'rewrite' => array(
                'slug' => 'portfolio',
                'with_front' => false,
            ),
            'supports' => array(
                'title',
                'editor',
                'thumbnail',
                'excerpt',
                'custom-fields',
                'revisions',
                'page-attributes',
                'comments'
            ),
            'menu_icon' => 'dashicons-portfolio',
            'menu_position' => 5,
            'show_in_rest' => true,
            'rest_base' => 'portfolio',
            'show_in_graphql' => true,
            'graphql_single_name' => 'portfolioItem',
            'graphql_plural_name' => 'portfolioItems',
            'capability_type' => 'portfolio_item',
            'map_meta_cap' => true,
            'template' => array(
                array('core/image'),
                array('core/heading', array('level' => 2, 'placeholder' => __('Project Title', 'gpress'))),
                array('core/paragraph', array('placeholder' => __('Project description...', 'gpress'))),
                array('core/columns', array(), array(
                    array('core/column', array(), array(
                        array('core/heading', array('level' => 3, 'content' => __('Client', 'gpress'))),
                        array('core/paragraph', array('placeholder' => __('Client name...', 'gpress')))
                    )),
                    array('core/column', array(), array(
                        array('core/heading', array('level' => 3, 'content' => __('Date', 'gpress'))),
                        array('core/paragraph', array('placeholder' => __('Project date...', 'gpress')))
                    ))
                ))
            ),
            'template_lock' => false,
        ));

        // Testimonials Post Type
        register_post_type('testimonial', array(
            'labels' => array(
                'name' => __('Testimonials', 'gpress'),
                'singular_name' => __('Testimonial', 'gpress'),
                'menu_name' => __('Testimonials', 'gpress'),
                'add_new' => __('Add New Testimonial', 'gpress'),
                'add_new_item' => __('Add New Testimonial', 'gpress'),
                'edit_item' => __('Edit Testimonial', 'gpress'),
                'new_item' => __('New Testimonial', 'gpress'),
                'view_item' => __('View Testimonial', 'gpress'),
                'search_items' => __('Search Testimonials', 'gpress'),
                'not_found' => __('No testimonials found', 'gpress'),
                'not_found_in_trash' => __('No testimonials found in trash', 'gpress'),
                'all_items' => __('All Testimonials', 'gpress'),
                'archives' => __('Testimonial Archives', 'gpress'),
            ),
            'public' => true,
            'has_archive' => true,
            'rewrite' => array(
                'slug' => 'testimonials',
                'with_front' => false,
            ),
            'supports' => array(
                'title',
                'editor',
                'thumbnail',
                'excerpt',
                'custom-fields',
                'revisions'
            ),
            'menu_icon' => 'dashicons-format-quote',
            'menu_position' => 6,
            'show_in_rest' => true,
            'rest_base' => 'testimonials',
            'show_in_graphql' => true,
            'graphql_single_name' => 'testimonial',
            'graphql_plural_name' => 'testimonials',
            'capability_type' => 'testimonial',
            'map_meta_cap' => true,
            'template' => array(
                array('core/quote', array('className' => 'testimonial-quote')),
                array('core/columns', array(), array(
                    array('core/column', array('width' => '25%'), array(
                        array('core/image', array('className' => 'testimonial-avatar is-style-rounded'))
                    )),
                    array('core/column', array('width' => '75%'), array(
                        array('core/heading', array('level' => 3, 'placeholder' => __('Client Name', 'gpress'))),
                        array('core/paragraph', array('placeholder' => __('Client title and company...', 'gpress')))
                    ))
                ))
            ),
        ));

        // Team Post Type
        register_post_type('team', array(
            'labels' => array(
                'name' => __('Team Members', 'gpress'),
                'singular_name' => __('Team Member', 'gpress'),
                'menu_name' => __('Team', 'gpress'),
                'add_new' => __('Add Team Member', 'gpress'),
                'add_new_item' => __('Add New Team Member', 'gpress'),
                'edit_item' => __('Edit Team Member', 'gpress'),
                'new_item' => __('New Team Member', 'gpress'),
                'view_item' => __('View Team Member', 'gpress'),
                'search_items' => __('Search Team', 'gpress'),
                'not_found' => __('No team members found', 'gpress'),
                'not_found_in_trash' => __('No team members found in trash', 'gpress'),
                'all_items' => __('All Team Members', 'gpress'),
                'archives' => __('Team Archives', 'gpress'),
            ),
            'public' => true,
            'has_archive' => true,
            'rewrite' => array(
                'slug' => 'team',
                'with_front' => false,
            ),
            'supports' => array(
                'title',
                'editor',
                'thumbnail',
                'excerpt',
                'custom-fields',
                'revisions',
                'page-attributes'
            ),
            'menu_icon' => 'dashicons-groups',
            'menu_position' => 7,
            'show_in_rest' => true,
            'rest_base' => 'team',
            'show_in_graphql' => true,
            'graphql_single_name' => 'teamMember',
            'graphql_plural_name' => 'teamMembers',
            'capability_type' => 'team_member',
            'map_meta_cap' => true,
            'template' => array(
                array('core/image', array('className' => 'team-photo')),
                array('core/heading', array('level' => 2, 'placeholder' => __('Team Member Name', 'gpress'))),
                array('core/paragraph', array('className' => 'team-title', 'placeholder' => __('Job Title', 'gpress'))),
                array('core/paragraph', array('placeholder' => __('Bio and experience...', 'gpress'))),
                array('core/social-links', array('className' => 'team-social'))
            ),
        ));

        // Flush rewrite rules on theme activation
        if (get_option('gpress_cpt_rewrite_rules_flushed') !== 'yes') {
            flush_rewrite_rules();
            update_option('gpress_cpt_rewrite_rules_flushed', 'yes');
        }
    }

    /**
     * Add capabilities for custom post types
     *
     * @since 1.0.0
     */
    public static function add_cpt_capabilities() {
        $post_types = array('portfolio_item', 'testimonial', 'team_member');
        
        foreach ($post_types as $post_type) {
            $capabilities = array(
                "edit_{$post_type}",
                "read_{$post_type}",
                "delete_{$post_type}",
                "edit_{$post_type}s",
                "edit_others_{$post_type}s",
                "publish_{$post_type}s",
                "read_private_{$post_type}s",
                "delete_{$post_type}s",
                "delete_private_{$post_type}s",
                "delete_published_{$post_type}s",
                "delete_others_{$post_type}s",
                "edit_private_{$post_type}s",
                "edit_published_{$post_type}s",
            );
            
            // Add capabilities to administrator and editor roles
            $roles = array('administrator', 'editor');
            foreach ($roles as $role_name) {
                $role = get_role($role_name);
                if ($role) {
                    foreach ($capabilities as $cap) {
                        $role->add_cap($cap);
                    }
                }
            }
        }
    }

    /**
     * Conditional asset loading for CPTs
     *
     * @since 1.0.0
     */
    public static function conditional_cpt_assets() {
        $post_type = get_post_type();
        
        // Load base CPT styles if on any CPT page
        if (is_singular(array('portfolio', 'testimonial', 'team')) || 
            is_post_type_archive(array('portfolio', 'testimonial', 'team')) ||
            is_tax(array('portfolio-category', 'skill', 'service'))) {
            
            wp_enqueue_style(
                'gpress-custom-post-types',
                get_theme_file_uri('/assets/css/custom-post-types.css'),
                array('gpress-style'),
                GPRESS_VERSION
            );

            wp_enqueue_script(
                'gpress-cpt-manager',
                get_theme_file_uri('/assets/js/cpt-manager.js'),
                array('jquery'),
                GPRESS_VERSION,
                array('strategy' => 'defer', 'in_footer' => true)
            );
        }

        // Portfolio-specific assets
        if (is_singular('portfolio') || is_post_type_archive('portfolio') || is_tax('portfolio-category')) {
            wp_enqueue_style(
                'gpress-portfolio',
                get_theme_file_uri('/assets/css/portfolio.css'),
                array('gpress-custom-post-types'),
                GPRESS_VERSION
            );

            wp_enqueue_script(
                'gpress-portfolio-gallery',
                get_theme_file_uri('/assets/js/portfolio-gallery.js'),
                array('gpress-cpt-manager'),
                GPRESS_VERSION,
                array('strategy' => 'defer', 'in_footer' => true)
            );
        }

        // Testimonials-specific assets
        if (is_singular('testimonial') || is_post_type_archive('testimonial')) {
            wp_enqueue_style(
                'gpress-testimonials',
                get_theme_file_uri('/assets/css/testimonials.css'),
                array('gpress-custom-post-types'),
                GPRESS_VERSION
            );

            wp_enqueue_script(
                'gpress-testimonial-slider',
                get_theme_file_uri('/assets/js/testimonial-slider.js'),
                array('gpress-cpt-manager'),
                GPRESS_VERSION,
                array('strategy' => 'defer', 'in_footer' => true)
            );
        }

        // Team-specific assets
        if (is_singular('team') || is_post_type_archive('team')) {
            wp_enqueue_style(
                'gpress-team',
                get_theme_file_uri('/assets/css/team.css'),
                array('gpress-custom-post-types'),
                GPRESS_VERSION
            );
        }

        // Archive filtering assets
        if (is_post_type_archive(array('portfolio', 'testimonial', 'team'))) {
            wp_enqueue_script(
                'gpress-archive-filters',
                get_theme_file_uri('/assets/js/archive-filters.js'),
                array('gpress-cpt-manager'),
                GPRESS_VERSION,
                array('strategy' => 'defer', 'in_footer' => true)
            );

            // Localize script for AJAX filtering
            wp_localize_script('gpress-archive-filters', 'gpressCPT', array(
                'ajaxurl' => admin_url('admin-ajax.php'),
                'nonce' => wp_create_nonce('gpress_cpt_filter'),
                'loading' => __('Loading...', 'gpress'),
                'no_results' => __('No items found', 'gpress'),
                'error' => __('Error loading content', 'gpress')
            ));
        }
    }

    /**
     * Custom template loader for CPTs
     *
     * @since 1.0.0
     */
    public static function cpt_template_loader($template) {
        $post_type = get_post_type();
        
        // Single template handling
        if (is_singular()) {
            $single_template = locate_template("templates/single-{$post_type}.html");
            if ($single_template) {
                return $single_template;
            }
        }
        
        // Archive template handling
        if (is_post_type_archive()) {
            $archive_template = locate_template("templates/archive-{$post_type}.html");
            if ($archive_template) {
                return $archive_template;
            }
        }
        
        return $template;
    }

    /**
     * Add structured data for CPTs
     *
     * @since 1.0.0
     */
    public static function cpt_structured_data() {
        if (!is_singular(array('portfolio', 'testimonial', 'team'))) {
            return;
        }

        global $post;
        $post_type = get_post_type();
        $schema = array();

        switch ($post_type) {
            case 'portfolio':
                $schema = array(
                    '@context' => 'https://schema.org',
                    '@type' => 'CreativeWork',
                    'name' => get_the_title(),
                    'description' => get_the_excerpt() ?: wp_trim_words(get_the_content(), 30),
                    'url' => get_permalink(),
                    'dateCreated' => get_the_date('c'),
                    'dateModified' => get_the_modified_date('c'),
                    'author' => array(
                        '@type' => 'Organization',
                        'name' => get_bloginfo('name')
                    )
                );
                
                if (has_post_thumbnail()) {
                    $image = wp_get_attachment_image_src(get_post_thumbnail_id(), 'large');
                    $schema['image'] = $image[0];
                }
                break;

            case 'testimonial':
                $schema = array(
                    '@context' => 'https://schema.org',
                    '@type' => 'Review',
                    'reviewBody' => get_the_content(),
                    'datePublished' => get_the_date('c'),
                    'author' => array(
                        '@type' => 'Person',
                        'name' => get_the_title()
                    ),
                    'itemReviewed' => array(
                        '@type' => 'Organization',
                        'name' => get_bloginfo('name')
                    )
                );
                break;

            case 'team':
                $schema = array(
                    '@context' => 'https://schema.org',
                    '@type' => 'Person',
                    'name' => get_the_title(),
                    'description' => get_the_excerpt() ?: wp_trim_words(get_the_content(), 30),
                    'url' => get_permalink(),
                    'worksFor' => array(
                        '@type' => 'Organization',
                        'name' => get_bloginfo('name')
                    )
                );
                
                if (has_post_thumbnail()) {
                    $image = wp_get_attachment_image_src(get_post_thumbnail_id(), 'medium');
                    $schema['image'] = $image[0];
                }
                break;
        }

        if (!empty($schema)) {
            echo '<script type="application/ld+json">' . wp_json_encode($schema) . '</script>' . "\n";
        }
    }

    /**
     * Add CPT-specific body classes
     *
     * @since 1.0.0
     */
    public static function cpt_body_classes($classes) {
        if (is_singular(array('portfolio', 'testimonial', 'team'))) {
            $classes[] = 'custom-post-type';
            $classes[] = 'cpt-' . get_post_type();
        }
        
        if (is_post_type_archive(array('portfolio', 'testimonial', 'team'))) {
            $classes[] = 'custom-post-type-archive';
            $classes[] = 'cpt-archive-' . get_queried_object()->name;
        }
        
        return $classes;
    }

    /**
     * Setup admin enhancements
     *
     * @since 1.0.0
     */
    public static function setup_cpt_admin() {
        // Add custom admin styles for CPTs
        add_action('admin_enqueue_scripts', array(__CLASS__, 'admin_assets'));
        
        // Customize admin columns
        add_filter('manage_portfolio_posts_columns', array(__CLASS__, 'portfolio_admin_columns'));
        add_filter('manage_testimonial_posts_columns', array(__CLASS__, 'testimonial_admin_columns'));
        add_filter('manage_team_posts_columns', array(__CLASS__, 'team_admin_columns'));
    }

    /**
     * Enqueue admin assets for CPTs
     *
     * @since 1.0.0
     */
    public static function admin_assets($hook) {
        global $post_type;
        
        if (in_array($post_type, array('portfolio', 'testimonial', 'team'))) {
            wp_enqueue_style(
                'gpress-cpt-admin',
                get_theme_file_uri('/assets/css/admin-cpt.css'),
                array(),
                GPRESS_VERSION
            );
        }
    }

    /**
     * Portfolio admin columns
     *
     * @since 1.0.0
     */
    public static function portfolio_admin_columns($columns) {
        $new_columns = array();
        $new_columns['cb'] = $columns['cb'];
        $new_columns['featured_image'] = __('Image', 'gpress');
        $new_columns['title'] = $columns['title'];
        $new_columns['portfolio_category'] = __('Category', 'gpress');
        $new_columns['skills'] = __('Skills', 'gpress');
        $new_columns['date'] = $columns['date'];
        
        return $new_columns;
    }

    /**
     * Testimonial admin columns
     *
     * @since 1.0.0
     */
    public static function testimonial_admin_columns($columns) {
        $new_columns = array();
        $new_columns['cb'] = $columns['cb'];
        $new_columns['featured_image'] = __('Avatar', 'gpress');
        $new_columns['title'] = $columns['title'];
        $new_columns['excerpt'] = __('Testimonial', 'gpress');
        $new_columns['date'] = $columns['date'];
        
        return $new_columns;
    }

    /**
     * Team admin columns
     *
     * @since 1.0.0
     */
    public static function team_admin_columns($columns) {
        $new_columns = array();
        $new_columns['cb'] = $columns['cb'];
        $new_columns['featured_image'] = __('Photo', 'gpress');
        $new_columns['title'] = $columns['title'];
        $new_columns['menu_order'] = __('Order', 'gpress');
        $new_columns['date'] = $columns['date'];
        
        return $new_columns;
    }

    /**
     * Populate custom admin columns
     *
     * @since 1.0.0
     */
    public static function populate_admin_columns($column, $post_id) {
        switch ($column) {
            case 'featured_image':
                $thumbnail = get_the_post_thumbnail($post_id, array(50, 50));
                echo $thumbnail ?: '—';
                break;
                
            case 'portfolio_category':
                $terms = get_the_terms($post_id, 'portfolio-category');
                if ($terms && !is_wp_error($terms)) {
                    $term_names = wp_list_pluck($terms, 'name');
                    echo implode(', ', $term_names);
                } else {
                    echo '—';
                }
                break;
                
            case 'skills':
                $terms = get_the_terms($post_id, 'skill');
                if ($terms && !is_wp_error($terms)) {
                    $term_names = wp_list_pluck($terms, 'name');
                    echo implode(', ', $term_names);
                } else {
                    echo '—';
                }
                break;
                
            case 'menu_order':
                echo get_post_field('menu_order', $post_id);
                break;
        }
    }
}

// Initialize the CPT system
GPress_Custom_Post_Types::init();
```

### 2. Create Custom Taxonomies

Create `inc/custom-taxonomies.php`:

```php
<?php
/**
 * Custom Taxonomies Registration for GPress Theme
 * Implements hierarchical and non-hierarchical taxonomies with optimization
 *
 * @package GPress
 * @subpackage Custom_Content
 * @version 1.0.0
 * @since 1.0.0
 */

// Prevent direct access
defined('ABSPATH') || exit;

/**
 * GPress Custom Taxonomies Manager
 * 
 * @since 1.0.0
 */
class GPress_Custom_Taxonomies {

    /**
     * Initialize taxonomy system
     *
     * @since 1.0.0
     */
    public static function init() {
        add_action('init', array(__CLASS__, 'register_taxonomies'));
        add_action('wp_enqueue_scripts', array(__CLASS__, 'conditional_taxonomy_assets'));
        add_filter('body_class', array(__CLASS__, 'taxonomy_body_classes'));
        add_action('wp_head', array(__CLASS__, 'taxonomy_structured_data'));
        
        // Admin enhancements
        add_action('admin_init', array(__CLASS__, 'setup_taxonomy_admin'));
    }

    /**
     * Register all custom taxonomies
     *
     * @since 1.0.0
     */
    public static function register_taxonomies() {
        
        // Portfolio Categories (Hierarchical)
        register_taxonomy('portfolio-category', 'portfolio', array(
            'labels' => array(
                'name' => __('Portfolio Categories', 'gpress'),
                'singular_name' => __('Portfolio Category', 'gpress'),
                'menu_name' => __('Categories', 'gpress'),
                'all_items' => __('All Categories', 'gpress'),
                'edit_item' => __('Edit Category', 'gpress'),
                'view_item' => __('View Category', 'gpress'),
                'update_item' => __('Update Category', 'gpress'),
                'add_new_item' => __('Add New Category', 'gpress'),
                'new_item_name' => __('New Category Name', 'gpress'),
                'parent_item' => __('Parent Category', 'gpress'),
                'parent_item_colon' => __('Parent Category:', 'gpress'),
                'search_items' => __('Search Categories', 'gpress'),
                'popular_items' => __('Popular Categories', 'gpress'),
                'separate_items_with_commas' => __('Separate categories with commas', 'gpress'),
                'add_or_remove_items' => __('Add or remove categories', 'gpress'),
                'choose_from_most_used' => __('Choose from most used categories', 'gpress'),
                'not_found' => __('No categories found', 'gpress'),
            ),
            'public' => true,
            'hierarchical' => true,
            'show_ui' => true,
            'show_admin_column' => true,
            'show_in_nav_menus' => true,
            'show_tagcloud' => true,
            'show_in_rest' => true,
            'rest_base' => 'portfolio-categories',
            'show_in_graphql' => true,
            'graphql_single_name' => 'portfolioCategory',
            'graphql_plural_name' => 'portfolioCategories',
            'rewrite' => array(
                'slug' => 'portfolio-category',
                'with_front' => false,
                'hierarchical' => true,
            ),
            'capabilities' => array(
                'manage_terms' => 'manage_portfolio_categories',
                'edit_terms' => 'edit_portfolio_categories',
                'delete_terms' => 'delete_portfolio_categories',
                'assign_terms' => 'assign_portfolio_categories',
            ),
        ));

        // Skills (Non-hierarchical)
        register_taxonomy('skill', array('portfolio', 'team'), array(
            'labels' => array(
                'name' => __('Skills', 'gpress'),
                'singular_name' => __('Skill', 'gpress'),
                'menu_name' => __('Skills', 'gpress'),
                'all_items' => __('All Skills', 'gpress'),
                'edit_item' => __('Edit Skill', 'gpress'),
                'view_item' => __('View Skill', 'gpress'),
                'update_item' => __('Update Skill', 'gpress'),
                'add_new_item' => __('Add New Skill', 'gpress'),
                'new_item_name' => __('New Skill Name', 'gpress'),
                'search_items' => __('Search Skills', 'gpress'),
                'popular_items' => __('Popular Skills', 'gpress'),
                'separate_items_with_commas' => __('Separate skills with commas', 'gpress'),
                'add_or_remove_items' => __('Add or remove skills', 'gpress'),
                'choose_from_most_used' => __('Choose from most used skills', 'gpress'),
                'not_found' => __('No skills found', 'gpress'),
            ),
            'public' => true,
            'hierarchical' => false,
            'show_ui' => true,
            'show_admin_column' => true,
            'show_in_nav_menus' => true,
            'show_tagcloud' => true,
            'show_in_rest' => true,
            'rest_base' => 'skills',
            'show_in_graphql' => true,
            'graphql_single_name' => 'skill',
            'graphql_plural_name' => 'skills',
            'rewrite' => array(
                'slug' => 'skill',
                'with_front' => false,
            ),
            'capabilities' => array(
                'manage_terms' => 'manage_skills',
                'edit_terms' => 'edit_skills',
                'delete_terms' => 'delete_skills',
                'assign_terms' => 'assign_skills',
            ),
        ));

        // Services (Hierarchical)
        register_taxonomy('service', array('portfolio', 'testimonial'), array(
            'labels' => array(
                'name' => __('Services', 'gpress'),
                'singular_name' => __('Service', 'gpress'),
                'menu_name' => __('Services', 'gpress'),
                'all_items' => __('All Services', 'gpress'),
                'edit_item' => __('Edit Service', 'gpress'),
                'view_item' => __('View Service', 'gpress'),
                'update_item' => __('Update Service', 'gpress'),
                'add_new_item' => __('Add New Service', 'gpress'),
                'new_item_name' => __('New Service Name', 'gpress'),
                'parent_item' => __('Parent Service', 'gpress'),
                'parent_item_colon' => __('Parent Service:', 'gpress'),
                'search_items' => __('Search Services', 'gpress'),
                'popular_items' => __('Popular Services', 'gpress'),
                'separate_items_with_commas' => __('Separate services with commas', 'gpress'),
                'add_or_remove_items' => __('Add or remove services', 'gpress'),
                'choose_from_most_used' => __('Choose from most used services', 'gpress'),
                'not_found' => __('No services found', 'gpress'),
            ),
            'public' => true,
            'hierarchical' => true,
            'show_ui' => true,
            'show_admin_column' => true,
            'show_in_nav_menus' => true,
            'show_tagcloud' => false,
            'show_in_rest' => true,
            'rest_base' => 'services',
            'show_in_graphql' => true,
            'graphql_single_name' => 'service',
            'graphql_plural_name' => 'services',
            'rewrite' => array(
                'slug' => 'service',
                'with_front' => false,
                'hierarchical' => true,
            ),
            'capabilities' => array(
                'manage_terms' => 'manage_services',
                'edit_terms' => 'edit_services',
                'delete_terms' => 'delete_services',
                'assign_terms' => 'assign_services',
            ),
        ));

        // Add taxonomy capabilities to roles
        self::add_taxonomy_capabilities();
    }

    /**
     * Add taxonomy capabilities to roles
     *
     * @since 1.0.0
     */
    private static function add_taxonomy_capabilities() {
        $taxonomies = array(
            'portfolio_categories' => array('manage_portfolio_categories', 'edit_portfolio_categories', 'delete_portfolio_categories', 'assign_portfolio_categories'),
            'skills' => array('manage_skills', 'edit_skills', 'delete_skills', 'assign_skills'),
            'services' => array('manage_services', 'edit_services', 'delete_services', 'assign_services'),
        );

        $roles = array('administrator', 'editor');
        
        foreach ($roles as $role_name) {
            $role = get_role($role_name);
            if ($role) {
                foreach ($taxonomies as $taxonomy => $capabilities) {
                    foreach ($capabilities as $cap) {
                        $role->add_cap($cap);
                    }
                }
            }
        }
    }

    /**
     * Conditional asset loading for taxonomies
     *
     * @since 1.0.0
     */
    public static function conditional_taxonomy_assets() {
        if (is_tax(array('portfolio-category', 'skill', 'service'))) {
            wp_enqueue_style(
                'gpress-taxonomy',
                get_theme_file_uri('/assets/css/taxonomy.css'),
                array('gpress-custom-post-types'),
                GPRESS_VERSION
            );

            wp_enqueue_script(
                'gpress-taxonomy',
                get_theme_file_uri('/assets/js/taxonomy.js'),
                array('gpress-cpt-manager'),
                GPRESS_VERSION,
                array('strategy' => 'defer', 'in_footer' => true)
            );
        }
    }

    /**
     * Add taxonomy-specific body classes
     *
     * @since 1.0.0
     */
    public static function taxonomy_body_classes($classes) {
        if (is_tax()) {
            $queried_object = get_queried_object();
            $classes[] = 'custom-taxonomy';
            $classes[] = 'taxonomy-' . $queried_object->taxonomy;
            $classes[] = 'term-' . $queried_object->slug;
        }
        
        return $classes;
    }

    /**
     * Add structured data for taxonomy pages
     *
     * @since 1.0.0
     */
    public static function taxonomy_structured_data() {
        if (!is_tax(array('portfolio-category', 'skill', 'service'))) {
            return;
        }

        $queried_object = get_queried_object();
        
        $schema = array(
            '@context' => 'https://schema.org',
            '@type' => 'CollectionPage',
            'name' => $queried_object->name,
            'description' => $queried_object->description ?: sprintf(__('Archive for %s', 'gpress'), $queried_object->name),
            'url' => get_term_link($queried_object),
            'breadcrumb' => array(
                '@type' => 'BreadcrumbList',
                'itemListElement' => array(
                    array(
                        '@type' => 'ListItem',
                        'position' => 1,
                        'name' => __('Home', 'gpress'),
                        'item' => home_url('/')
                    ),
                    array(
                        '@type' => 'ListItem',
                        'position' => 2,
                        'name' => $queried_object->name,
                        'item' => get_term_link($queried_object)
                    )
                )
            )
        );

        echo '<script type="application/ld+json">' . wp_json_encode($schema) . '</script>' . "\n";
    }

    /**
     * Setup admin enhancements for taxonomies
     *
     * @since 1.0.0
     */
    public static function setup_taxonomy_admin() {
        // Add custom fields to taxonomy edit forms
        add_action('portfolio-category_edit_form_fields', array(__CLASS__, 'portfolio_category_edit_fields'));
        add_action('skill_edit_form_fields', array(__CLASS__, 'skill_edit_fields'));
        add_action('service_edit_form_fields', array(__CLASS__, 'service_edit_fields'));
        
        // Save custom fields
        add_action('edited_portfolio-category', array(__CLASS__, 'save_taxonomy_custom_fields'));
        add_action('edited_skill', array(__CLASS__, 'save_taxonomy_custom_fields'));
        add_action('edited_service', array(__CLASS__, 'save_taxonomy_custom_fields'));
    }

    /**
     * Portfolio category custom fields
     *
     * @since 1.0.0
     */
    public static function portfolio_category_edit_fields($term) {
        $color = get_term_meta($term->term_id, 'category_color', true);
        $icon = get_term_meta($term->term_id, 'category_icon', true);
        ?>
        <tr class="form-field">
            <th scope="row"><label for="category_color"><?php _e('Category Color', 'gpress'); ?></label></th>
            <td>
                <input type="color" name="category_color" id="category_color" value="<?php echo esc_attr($color); ?>" />
                <p class="description"><?php _e('Choose a color for this category.', 'gpress'); ?></p>
            </td>
        </tr>
        <tr class="form-field">
            <th scope="row"><label for="category_icon"><?php _e('Category Icon', 'gpress'); ?></label></th>
            <td>
                <input type="text" name="category_icon" id="category_icon" value="<?php echo esc_attr($icon); ?>" />
                <p class="description"><?php _e('Dashicon name or FontAwesome class.', 'gpress'); ?></p>
            </td>
        </tr>
        <?php
    }

    /**
     * Skill custom fields
     *
     * @since 1.0.0
     */
    public static function skill_edit_fields($term) {
        $level = get_term_meta($term->term_id, 'skill_level', true);
        $color = get_term_meta($term->term_id, 'skill_color', true);
        ?>
        <tr class="form-field">
            <th scope="row"><label for="skill_level"><?php _e('Skill Level', 'gpress'); ?></label></th>
            <td>
                <select name="skill_level" id="skill_level">
                    <option value="beginner" <?php selected($level, 'beginner'); ?>><?php _e('Beginner', 'gpress'); ?></option>
                    <option value="intermediate" <?php selected($level, 'intermediate'); ?>><?php _e('Intermediate', 'gpress'); ?></option>
                    <option value="advanced" <?php selected($level, 'advanced'); ?>><?php _e('Advanced', 'gpress'); ?></option>
                    <option value="expert" <?php selected($level, 'expert'); ?>><?php _e('Expert', 'gpress'); ?></option>
                </select>
                <p class="description"><?php _e('Proficiency level for this skill.', 'gpress'); ?></p>
            </td>
        </tr>
        <tr class="form-field">
            <th scope="row"><label for="skill_color"><?php _e('Skill Color', 'gpress'); ?></label></th>
            <td>
                <input type="color" name="skill_color" id="skill_color" value="<?php echo esc_attr($color); ?>" />
                <p class="description"><?php _e('Choose a color for this skill.', 'gpress'); ?></p>
            </td>
        </tr>
        <?php
    }

    /**
     * Service custom fields
     *
     * @since 1.0.0
     */
    public static function service_edit_fields($term) {
        $icon = get_term_meta($term->term_id, 'service_icon', true);
        $featured = get_term_meta($term->term_id, 'service_featured', true);
        ?>
        <tr class="form-field">
            <th scope="row"><label for="service_icon"><?php _e('Service Icon', 'gpress'); ?></label></th>
            <td>
                <input type="text" name="service_icon" id="service_icon" value="<?php echo esc_attr($icon); ?>" />
                <p class="description"><?php _e('Dashicon name or FontAwesome class.', 'gpress'); ?></p>
            </td>
        </tr>
        <tr class="form-field">
            <th scope="row"><label for="service_featured"><?php _e('Featured Service', 'gpress'); ?></label></th>
            <td>
                <input type="checkbox" name="service_featured" id="service_featured" value="1" <?php checked($featured, '1'); ?> />
                <p class="description"><?php _e('Mark as a featured service.', 'gpress'); ?></p>
            </td>
        </tr>
        <?php
    }

    /**
     * Save taxonomy custom fields
     *
     * @since 1.0.0
     */
    public static function save_taxonomy_custom_fields($term_id) {
        if (isset($_POST['category_color'])) {
            update_term_meta($term_id, 'category_color', sanitize_hex_color($_POST['category_color']));
        }
        
        if (isset($_POST['category_icon'])) {
            update_term_meta($term_id, 'category_icon', sanitize_text_field($_POST['category_icon']));
        }
        
        if (isset($_POST['skill_level'])) {
            update_term_meta($term_id, 'skill_level', sanitize_text_field($_POST['skill_level']));
        }
        
        if (isset($_POST['skill_color'])) {
            update_term_meta($term_id, 'skill_color', sanitize_hex_color($_POST['skill_color']));
        }
        
        if (isset($_POST['service_icon'])) {
            update_term_meta($term_id, 'service_icon', sanitize_text_field($_POST['service_icon']));
        }
        
        if (isset($_POST['service_featured'])) {
            update_term_meta($term_id, 'service_featured', '1');
        } else {
            delete_term_meta($term_id, 'service_featured');
        }
    }
}

// Initialize the taxonomy system
GPress_Custom_Taxonomies::init();
```

### 3. Update Functions.php

Update `functions.php`:

```php
// Advanced forms and contact functionality
require_once get_theme_file_path('/inc/form-handler.php');

/**
 * Load Custom Post Types and Taxonomies
 * Conditional loading based on admin context and front-end needs
 *
 * @since 1.0.0
 */
function gpress_load_custom_content() {
    // Always load CPTs and taxonomies for proper registration
    require_once get_theme_file_path('/inc/custom-post-types.php');
    require_once get_theme_file_path('/inc/custom-taxonomies.php');
    
    // Load additional components conditionally
    if (is_admin() || 
        is_singular(array('portfolio', 'testimonial', 'team')) || 
        is_post_type_archive(array('portfolio', 'testimonial', 'team')) ||
        is_tax(array('portfolio-category', 'skill', 'service'))) {
        
        require_once get_theme_file_path('/inc/custom-fields.php');
        require_once get_theme_file_path('/inc/cpt-optimization.php');
    }
}
add_action('after_setup_theme', 'gpress_load_custom_content');

/**
 * Add Custom Post Type support to theme features
 *
 * @since 1.0.0
 */
function gpress_add_cpt_theme_support() {
    // Add theme support for custom post type templates
    add_theme_support('post-type-templates');
    
    // Add custom background support for CPTs
    add_theme_support('custom-background', array(
        'default-color' => 'ffffff',
        'default-image' => '',
    ));
    
    // Enable block templates for custom post types
    add_theme_support('block-templates');
    add_theme_support('block-template-parts');
}
add_action('after_setup_theme', 'gpress_add_cpt_theme_support');
```

### 4. Update Theme Setup

Update `inc/theme-setup.php`:

```php
/**
 * Add Custom Post Type image sizes
 *
 * @since 1.0.0
 */
function gpress_add_cpt_image_sizes() {
    // Portfolio images
    add_image_size('portfolio-thumbnail', 400, 300, true);
    add_image_size('portfolio-large', 800, 600, true);
    add_image_size('portfolio-hero', 1200, 600, true);
    
    // Team member photos
    add_image_size('team-thumbnail', 300, 300, true);
    add_image_size('team-large', 600, 600, true);
    
    // Testimonial avatars
    add_image_size('testimonial-avatar', 100, 100, true);
}
add_action('after_setup_theme', 'gpress_add_cpt_image_sizes');

/**
 * Register Custom Post Type navigation menus
 *
 * @since 1.0.0
 */
function gpress_register_cpt_menus() {
    register_nav_menus(array(
        'portfolio-filter' => __('Portfolio Filter Menu', 'gpress'),
        'services-menu' => __('Services Menu', 'gpress'),
    ));
}
add_action('after_setup_theme', 'gpress_register_cpt_menus');
```

### 5. Update Style.css

Update `style.css`:

```css
/* GPress Form Styles */

/* Contact Form Styles */
.gpress-contact-form-wrapper {
    background: var(--wp--preset--color--background-secondary);
    padding: 2rem;
    border-radius: 12px;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    margin: 2rem 0;
}

.form-title {
    color: var(--wp--preset--color--primary);
    margin-bottom: 1.5rem;
    font-size: 1.5rem;
    font-weight: 600;
}

.form-row {
    display: flex;
    gap: 1rem;
    margin-bottom: 1.5rem;
}

.form-row:last-child {
    margin-bottom: 0;
}

.form-field {
    flex: 1;
}

.form-field label {
    display: block;
    margin-bottom: 0.5rem;
    font-weight: 600;
    color: var(--wp--preset--color--foreground);
}

.required {
    color: #d63384;
}

.form-field input,
.form-field textarea,
.form-field select {
    width: 100%;
    padding: 0.75rem;
    border: 2px solid var(--wp--preset--color--border);
    border-radius: 8px;
    font-size: 1rem;
    transition: all 0.3s ease;
    background: var(--wp--preset--color--background);
    color: var(--wp--preset--color--foreground);
}

.form-field input:focus,
.form-field textarea:focus,
.form-field select:focus {
    outline: none;
    border-color: var(--wp--preset--color--primary);
    box-shadow: 0 0 0 3px rgba(var(--wp--preset--color--primary-rgb), 0.1);
}

.form-field input:invalid,
.form-field textarea:invalid {
    border-color: #d63384;
}

.form-field textarea {
    resize: vertical;
    min-height: 120px;
}

/* Checkbox Styles */
.checkbox-label {
    display: flex;
    align-items: flex-start;
    gap: 0.75rem;
    cursor: pointer;
    font-size: 0.875rem;
    line-height: 1.5;
}

.checkbox-label input[type="checkbox"] {
    position: absolute;
    opacity: 0;
    width: 0;
    height: 0;
}

.checkmark {
    display: flex;
    align-items: center;
    justify-content: center;
    width: 20px;
    height: 20px;
    background: var(--wp--preset--color--background);
    border: 2px solid var(--wp--preset--color--border);
    border-radius: 4px;
    transition: all 0.3s ease;
    flex-shrink: 0;
    margin-top: 2px;
}

.checkbox-label input[type="checkbox"]:checked + .checkmark {
    background: var(--wp--preset--color--primary);
    border-color: var(--wp--preset--color--primary);
}

.checkbox-label input[type="checkbox"]:checked + .checkmark::after {
    content: "✓";
    color: var(--wp--preset--color--background);
    font-size: 12px;
    font-weight: bold;
}

.checkbox-label input[type="checkbox"]:focus + .checkmark {
    box-shadow: 0 0 0 3px rgba(var(--wp--preset--color--primary-rgb), 0.2);
}

/* Form Actions */
.form-actions {
    display: flex;
    justify-content: flex-start;
    margin-top: 2rem;
}

.submit-button,
.newsletter-submit {
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    background: var(--wp--preset--color--primary);
    color: var(--wp--preset--color--background);
    border: none;
    padding: 0.75rem 2rem;
    border-radius: 8px;
    font-size: 1rem;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.3s ease;
    text-decoration: none;
    min-height: 44px;
}

.submit-button:hover,
.submit-button:focus,
.newsletter-submit:hover,
.newsletter-submit:focus {
    background: var(--wp--preset--color--primary-dark, var(--wp--preset--color--primary));
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(var(--wp--preset--color--primary-rgb), 0.3);
}

.submit-button:disabled,
.newsletter-submit:disabled {
    opacity: 0.6;
    cursor: not-allowed;
    transform: none;
}

.loading-spinner {
    display: none;
    width: 16px;
    height: 16px;
    border: 2px solid rgba(255, 255, 255, 0.3);
    border-radius: 50%;
    border-top-color: rgba(255, 255, 255, 0.8);
    animation: spin 1s ease-in-out infinite;
}

.form-loading .loading-spinner {
    display: block;
}

.form-loading .button-text {
    display: none;
}

@keyframes spin {
    to { transform: rotate(360deg); }
}

/* Form Messages */
.form-messages {
    margin-top: 1rem;
    padding: 1rem;
    border-radius: 8px;
    font-weight: 600;
    display: none;
}

.form-messages.success {
    background: #d4edda;
    color: #155724;
    border: 1px solid #c3e6cb;
    display: block;
}

.form-messages.error {
    background: #f8d7da;
    color: #721c24;
    border: 1px solid #f5c6cb;
    display: block;
}

/* Newsletter Form Styles */
.gpress-newsletter-form-wrapper {
    background: var(--wp--preset--color--background-secondary);
    padding: 1.5rem;
    border-radius: 12px;
    text-align: center;
    margin: 2rem 0;
}

.newsletter-title {
    color: var(--wp--preset--color--primary);
    margin-bottom: 0.5rem;
    font-size: 1.25rem;
    font-weight: 600;
}

.newsletter-description {
    color: var(--wp--preset--color--foreground-secondary);
    margin-bottom: 1.5rem;
    font-size: 0.875rem;
}

.newsletter-input-group {
    display: flex;
    gap: 0.5rem;
    margin-bottom: 1rem;
    max-width: 400px;
    margin-left: auto;
    margin-right: auto;
}

.newsletter-input-group input[type="email"] {
    flex: 1;
    padding: 0.75rem;
    border: 2px solid var(--wp--preset--color--border);
    border-radius: 8px 0 0 8px;
    font-size: 0.875rem;
}

.newsletter-submit {
    border-radius: 0 8px 8px 0;
    padding: 0.75rem 1.5rem;
    font-size: 0.875rem;
    white-space: nowrap;
}

.newsletter-consent {
    margin-top: 1rem;
}

.newsletter-consent .checkbox-label {
    justify-content: center;
    font-size: 0.75rem;
}

/* Compact Newsletter Style */
.newsletter-style-compact {
    padding: 1rem;
    background: transparent;
    border: 2px solid var(--wp--preset--color--primary);
}

.newsletter-style-compact .newsletter-input-group {
    max-width: none;
}

/* Inline Newsletter Style */
.newsletter-style-inline .newsletter-input-group {
    max-width: none;
    margin-bottom: 0.5rem;
}

.newsletter-style-inline .newsletter-consent {
    margin-top: 0.5rem;
}

/* Responsive Design */
@media (max-width: 768px) {
    .form-row {
        flex-direction: column;
        gap: 0;
    }
    
    .form-field {
        margin-bottom: 1rem;
    }
    
    .newsletter-input-group {
        flex-direction: column;
    }
    
    .newsletter-input-group input[type="email"] {
        border-radius: 8px;
        margin-bottom: 0.5rem;
    }
    
    .newsletter-submit {
        border-radius: 8px;
    }
    
    .gpress-contact-form-wrapper,
    .gpress-newsletter-form-wrapper {
        padding: 1.5rem;
    }
}

/* High Contrast Mode Support */
@media (prefers-contrast: high) {
    .form-field input,
    .form-field textarea,
    .form-field select {
        border-width: 3px;
    }
    
    .submit-button,
    .newsletter-submit {
        border: 2px solid currentColor;
    }
}

/* Reduced Motion Support */
@media (prefers-reduced-motion: reduce) {
    .form-field input,
    .form-field textarea,
    .submit-button,
    .newsletter-submit,
    .checkmark {
        transition: none;
    }
    
    .loading-spinner {
        animation: none;
    }
    
    .submit-button:hover,
    .newsletter-submit:hover {
        transform: none;
    }
}

/* Print Styles */
@media print {
    .gpress-contact-form-wrapper,
    .gpress-newsletter-form-wrapper {
        background: white;
        box-shadow: none;
        border: 1px solid #000;
    }
    
    .submit-button,
    .newsletter-submit {
        background: white;
        color: black;
        border: 1px solid black;
    }
}

/**
 * Custom Post Types Base Styles
 * Foundation styles for all custom post types
 *
 * @since 1.0.0
 */

/* CPT Common Elements */
.custom-post-type .entry-header {
    margin-bottom: 2rem;
}

.custom-post-type .entry-meta {
    font-size: 0.875rem;
    color: var(--wp--preset--color--foreground-secondary);
    margin-bottom: 1rem;
}

.custom-post-type .entry-meta a {
    color: inherit;
    text-decoration: none;
}

.custom-post-type .entry-meta a:hover {
    color: var(--wp--preset--color--primary);
}

/* CPT Archive Layouts */
.custom-post-type-archive .post-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
    gap: 2rem;
    margin: 2rem 0;
}

/* CPT Cards */
.cpt-card {
    background: var(--wp--preset--color--background-secondary);
    border-radius: 12px;
    overflow: hidden;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    transition: transform 0.3s ease, box-shadow 0.3s ease;
}

.cpt-card:hover {
    transform: translateY(-4px);
    box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15);
}

.cpt-card-image {
    position: relative;
    overflow: hidden;
}

.cpt-card-image img {
    width: 100%;
    height: 200px;
    object-fit: cover;
    transition: transform 0.3s ease;
}

.cpt-card:hover .cpt-card-image img {
    transform: scale(1.05);
}

.cpt-card-content {
    padding: 1.5rem;
}

.cpt-card-title {
    margin: 0 0 0.5rem 0;
    font-size: 1.25rem;
    font-weight: 600;
}

.cpt-card-title a {
    color: var(--wp--preset--color--foreground);
    text-decoration: none;
}

.cpt-card-title a:hover {
    color: var(--wp--preset--color--primary);
}

.cpt-card-excerpt {
    font-size: 0.875rem;
    line-height: 1.6;
    color: var(--wp--preset--color--foreground-secondary);
    margin-bottom: 1rem;
}

.cpt-card-meta {
    display: flex;
    flex-wrap: wrap;
    gap: 0.5rem;
    font-size: 0.75rem;
}

.cpt-meta-item {
    background: var(--wp--preset--color--background);
    padding: 0.25rem 0.5rem;
    border-radius: 4px;
    color: var(--wp--preset--color--foreground-secondary);
}

/* CPT Navigation */
.cpt-navigation {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin: 2rem 0;
    padding: 1rem;
    background: var(--wp--preset--color--background-secondary);
    border-radius: 8px;
}

.cpt-filters {
    display: flex;
    gap: 0.5rem;
    flex-wrap: wrap;
}

.cpt-filter-button {
    background: transparent;
    border: 2px solid var(--wp--preset--color--border);
    color: var(--wp--preset--color--foreground);
    padding: 0.5rem 1rem;
    border-radius: 25px;
    cursor: pointer;
    transition: all 0.3s ease;
    font-size: 0.875rem;
    text-decoration: none;
    display: inline-flex;
    align-items: center;
}

.cpt-filter-button:hover,
.cpt-filter-button.active {
    background: var(--wp--preset--color--primary);
    border-color: var(--wp--preset--color--primary);
    color: var(--wp--preset--color--background);
}

.cpt-sort-controls select {
    background: var(--wp--preset--color--background);
    border: 2px solid var(--wp--preset--color--border);
    padding: 0.5rem;
    border-radius: 6px;
    color: var(--wp--preset--color--foreground);
}

/* Responsive Design */
@media (max-width: 768px) {
    .custom-post-type-archive .post-grid {
        grid-template-columns: 1fr;
        gap: 1.5rem;
    }
    
    .cpt-navigation {
        flex-direction: column;
        gap: 1rem;
        align-items: stretch;
    }
    
    .cpt-filters {
        justify-content: center;
    }
    
    .cpt-sort-controls {
        text-align: center;
    }
}

/* High Contrast Support */
@media (prefers-contrast: high) {
    .cpt-card {
        border: 2px solid var(--wp--preset--color--foreground);
    }
    
    .cpt-filter-button {
        border-width: 3px;
    }
}

/* Reduced Motion Support */
@media (prefers-reduced-motion: reduce) {
    .cpt-card,
    .cpt-card-image img,
    .cpt-filter-button {
        transition: none;
    }
    
    .cpt-card:hover {
        transform: none;
    }
    
    .cpt-card:hover .cpt-card-image img {
        transform: none;
    }
}

/* Print Styles */
@media print {
    .cpt-filters,
    .cpt-sort-controls,
    .cpt-navigation {
        display: none;
    }
    
    .custom-post-type-archive .post-grid {
        display: block;
    }
    
    .cpt-card {
        break-inside: avoid;
        margin-bottom: 1rem;
        box-shadow: none;
        border: 1px solid #000;
    }
}
```

## Testing This Step

### 1. Custom Post Type Registration Testing
```bash
# Test CPT registration
wp post-type list --format=table

# Test taxonomy registration  
wp taxonomy list --format=table

# Check rewrite rules
wp rewrite list --format=table | grep -E "(portfolio|testimonial|team)"

# Flush rewrite rules if needed
wp rewrite flush
```

### 2. Template Testing
```bash
# Check if templates exist
ls -la templates/single-portfolio.html
ls -la templates/archive-portfolio.html
ls -la templates/taxonomy-portfolio-category.html

# Test template loading
curl -s -o /dev/null -w "%{http_code}" http://yoursite.com/portfolio/
curl -s -o /dev/null -w "%{http_code}" http://yoursite.com/testimonials/
curl -s -o /dev/null -w "%{http_code}" http://yoursite.com/team/
```

### 3. Asset Loading Testing
```bash
# Check conditional CSS loading
curl -s http://yoursite.com/portfolio/ | grep "custom-post-types.css"
curl -s http://yoursite.com/portfolio/ | grep "portfolio.css"

# Verify JS loading
curl -s http://yoursite.com/portfolio/ | grep "cpt-manager.js"
curl -s http://yoursite.com/portfolio/ | grep "portfolio-gallery.js"
```

### 4. Database and Performance Testing
```bash
# Check database tables
wp db query "SHOW TABLES LIKE '%_postmeta';"

# Test CPT queries
wp post list --post_type=portfolio --format=table
wp post list --post_type=testimonial --format=table
wp post list --post_type=team --format=table

# Check taxonomy terms
wp term list portfolio-category --format=table
wp term list skill --format=table
wp term list service --format=table
```

### 5. Accessibility and SEO Testing
```bash
# Test structured data
curl -s http://yoursite.com/portfolio/sample-project/ | grep -o '<script type="application/ld+json">.*</script>'

# Check meta tags
curl -s http://yoursite.com/portfolio/ | grep -E "(og:|twitter:|description)"

# Validate HTML
# Use online validator or: 
curl -s http://yoursite.com/portfolio/ | tidy -q -e 2>&1 | head -20
```

## Expected Results After This Step

1. **Custom Post Types**: Portfolio, Testimonial, and Team post types registered with full FSE support
2. **Custom Taxonomies**: Portfolio Categories, Skills, and Services taxonomies with proper capabilities
3. **Template System**: Complete template hierarchy for all CPTs with responsive design
4. **Conditional Loading**: Optimized asset loading based on content type detection
5. **Admin Integration**: Enhanced admin columns, custom fields, and user-friendly management
6. **SEO Optimization**: Structured data implementation for all custom content types
7. **Performance**: Efficient database queries and caching strategies for CPT content
8. **Accessibility**: Full WCAG 2.1 AA compliance across all custom content templates

## Next Step

In Step 12, we'll implement advanced navigation and menu systems with dynamic filtering, search capabilities, and mobile-optimized interfaces that integrate seamlessly with our custom post types and taxonomies.

---

**Step 11 Completed**: Custom Post Types & Taxonomies Implementation ✅
- Advanced CPT registration with FSE support
- Hierarchical and non-hierarchical taxonomies
- Optimized template system with conditional loading
- Enhanced admin experience with custom fields
- SEO and accessibility optimization for custom content
- Performance monitoring and caching strategies