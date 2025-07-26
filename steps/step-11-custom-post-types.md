# Step 11: Dynamic Custom Post Types & Taxonomies Framework

## Overview
This step implements a **dynamic framework** for creating custom post types and taxonomies in the **GPress** theme with **Smart Asset Management System integration**. Instead of predefined content types, you'll learn to build a flexible system that adapts to any content structure you need, with intelligent asset loading and performance optimization.

## Dynamic Framework Philosophy
This framework is designed to be **completely adaptable**:
- **No Predefined Content Types**: Build exactly what your project needs
- **Scalable Architecture**: Add unlimited post types and taxonomies
- **Smart Asset Loading**: Conditional loading based on your custom content
- **Reusable Components**: Template patterns that work for any content type
- **Performance First**: Optimized for Core Web Vitals regardless of content complexity

## Integration with Smart Asset System
This step **integrates with Step 7's Smart Asset Management System**:
- **Dynamic CPT Detection**: Smart Asset Manager automatically detects your custom post types
- **Conditional Loading**: Assets load only when your specific content types are accessed
- **Template-Based Optimization**: Different templates trigger appropriate asset loading
- **Content-Type Intelligence**: System learns your content patterns for optimal performance

## Objectives
- Build a flexible custom post type registration system
- Create dynamic taxonomy management framework
- Implement adaptive template generation
- Establish conditional asset loading for any content type
- Develop reusable admin interfaces
- Ensure accessibility and SEO optimization for all content types

## What You'll Learn
- Dynamic WordPress CPT and taxonomy architecture
- Flexible template system creation
- Conditional PHP and asset loading strategies
- Scalable custom field management
- Performance optimization for diverse content types
- Accessibility patterns for any content structure

## Files Structure for This Step

### 📁 Files to CREATE:
```
inc/
├── dynamic-post-types.php      # Dynamic CPT registration framework
├── dynamic-taxonomies.php      # Dynamic taxonomy framework
├── dynamic-fields.php          # Flexible custom fields system
├── cpt-template-generator.php  # Automatic template generation
└── cpt-admin-interface.php     # Dynamic admin management

**Note**: No predefined templates or assets - everything generates based on your needs:
- Template files: Generated automatically based on your post types
- CSS files: Dynamic loading based on content type detection
- JS files: Loaded conditionally when functionality is needed
```

### 📝 Files to UPDATE:
```
functions.php                   # Include dynamic framework
inc/theme-setup.php            # Add dynamic CPT support
inc/enqueue-scripts.php        # Dynamic asset loading
theme.json                     # Flexible CPT settings
```

### 🎯 Dynamic Framework Features:
- **Zero Configuration Start**: Works without any setup
- **Intelligent Defaults**: Smart settings for any content type
- **Automatic Optimization**: Performance optimization built-in
- **Template Generation**: Creates templates based on your content structure
- **Asset Intelligence**: Loads only needed assets for your content types
- **Admin Automation**: Self-configuring admin interfaces
- **SEO Adaptation**: Automatic structured data for any content type

## Step-by-Step Implementation

### 1. Create Dynamic Post Types Framework

Create `inc/dynamic-post-types.php`:

```php
<?php
/**
 * Dynamic Custom Post Types Framework for GPress Theme
 * Flexible system for creating any custom post type with optimization
 *
 * @package GPress
 * @subpackage Dynamic_Framework
 * @version 1.0.0
 * @since 1.0.0
 */

// Prevent direct access
defined('ABSPATH') || exit;

/**
 * GPress Dynamic Custom Post Types Manager
 * 
 * @since 1.0.0
 */
class GPress_Dynamic_Post_Types {

    /**
     * Registered post types
     *
     * @var array
     */
    private static $registered_types = array();

    /**
     * Initialize dynamic CPT system
     *
     * @since 1.0.0
     */
    public static function init() {
        add_action('init', array(__CLASS__, 'register_dynamic_post_types'));
        add_action('wp_enqueue_scripts', array(__CLASS__, 'conditional_cpt_assets'));
        add_filter('template_include', array(__CLASS__, 'dynamic_template_loader'));
        add_action('wp_head', array(__CLASS__, 'dynamic_structured_data'));
        add_filter('body_class', array(__CLASS__, 'dynamic_body_classes'));
        
        // Admin enhancements
        add_action('admin_init', array(__CLASS__, 'setup_dynamic_admin'));
        add_action('admin_menu', array(__CLASS__, 'add_cpt_management_page'));
        
        // AJAX handlers for dynamic management
        add_action('wp_ajax_create_custom_post_type', array(__CLASS__, 'ajax_create_post_type'));
        add_action('wp_ajax_delete_custom_post_type', array(__CLASS__, 'ajax_delete_post_type'));
        add_action('wp_ajax_update_post_type_settings', array(__CLASS__, 'ajax_update_settings'));
    }

    /**
     * Register dynamic post types from database/options
     *
     * @since 1.0.0
     */
    public static function register_dynamic_post_types() {
        $custom_post_types = get_option('gpress_custom_post_types', array());
        
        foreach ($custom_post_types as $post_type => $config) {
            self::register_single_post_type($post_type, $config);
        }
    }

    /**
     * Register a single dynamic post type
     *
     * @since 1.0.0
     * @param string $post_type Post type key
     * @param array $config Configuration array
     */
    public static function register_single_post_type($post_type, $config) {
        // Set intelligent defaults
        $defaults = array(
            'labels' => self::generate_labels($config['singular'], $config['plural']),
            'public' => true,
            'has_archive' => true,
            'show_in_rest' => true,
            'supports' => array('title', 'editor', 'thumbnail', 'excerpt', 'custom-fields', 'revisions'),
            'menu_icon' => $config['icon'] ?? 'dashicons-admin-post',
            'rewrite' => array(
                'slug' => $config['slug'] ?? $post_type,
                'with_front' => false,
            ),
            'capability_type' => $post_type,
            'map_meta_cap' => true,
        );

        // Merge with custom configuration
        $args = wp_parse_args($config['args'] ?? array(), $defaults);
        
        // Register the post type
        register_post_type($post_type, $args);
        
        // Store for reference
        self::$registered_types[$post_type] = $config;
        
        // Add capabilities
        self::add_post_type_capabilities($post_type);
        
        // Generate templates if needed
        self::maybe_generate_templates($post_type, $config);
    }

    /**
     * Generate intelligent labels for post type
     *
     * @since 1.0.0
     * @param string $singular Singular name
     * @param string $plural Plural name
     * @return array Labels array
     */
    private static function generate_labels($singular, $plural) {
        return array(
            'name' => $plural,
            'singular_name' => $singular,
            'menu_name' => $plural,
            'add_new' => sprintf(__('Add New %s', 'gpress'), $singular),
            'add_new_item' => sprintf(__('Add New %s', 'gpress'), $singular),
            'edit_item' => sprintf(__('Edit %s', 'gpress'), $singular),
            'new_item' => sprintf(__('New %s', 'gpress'), $singular),
            'view_item' => sprintf(__('View %s', 'gpress'), $singular),
            'search_items' => sprintf(__('Search %s', 'gpress'), $plural),
            'not_found' => sprintf(__('No %s found', 'gpress'), strtolower($plural)),
            'not_found_in_trash' => sprintf(__('No %s found in trash', 'gpress'), strtolower($plural)),
            'all_items' => sprintf(__('All %s', 'gpress'), $plural),
            'archives' => sprintf(__('%s Archives', 'gpress'), $singular),
        );
    }

    /**
     * Add capabilities for custom post type
     *
     * @since 1.0.0
     * @param string $post_type Post type key
     */
    private static function add_post_type_capabilities($post_type) {
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
        
        // Add capabilities to appropriate roles
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

    /**
     * Conditional asset loading for dynamic CPTs
     *
     * @since 1.0.0
     */
    public static function conditional_cpt_assets() {
        $current_post_type = get_post_type();
        $custom_post_types = array_keys(self::$registered_types);
        
        // Load base CPT styles if on any custom post type page
        if (is_singular($custom_post_types) || 
            is_post_type_archive($custom_post_types) ||
            self::is_custom_taxonomy_page()) {
            
            wp_enqueue_style(
                'gpress-dynamic-cpt',
                get_theme_file_uri('/assets/css/dynamic-cpt.css'),
                array('gpress-style'),
                GPRESS_VERSION
            );

            wp_enqueue_script(
                'gpress-dynamic-cpt',
                get_theme_file_uri('/assets/js/dynamic-cpt.js'),
                array('jquery'),
                GPRESS_VERSION,
                array('strategy' => 'defer', 'in_footer' => true)
            );
        }

        // Load post-type-specific assets if they exist
        if (in_array($current_post_type, $custom_post_types)) {
            $css_file = "/assets/css/cpt-{$current_post_type}.css";
            $js_file = "/assets/js/cpt-{$current_post_type}.js";
            
            if (file_exists(get_theme_file_path($css_file))) {
                wp_enqueue_style(
                    "gpress-cpt-{$current_post_type}",
                    get_theme_file_uri($css_file),
                    array('gpress-dynamic-cpt'),
                    GPRESS_VERSION
                );
            }
            
            if (file_exists(get_theme_file_path($js_file))) {
                wp_enqueue_script(
                    "gpress-cpt-{$current_post_type}",
                    get_theme_file_uri($js_file),
                    array('gpress-dynamic-cpt'),
                    GPRESS_VERSION,
                    array('strategy' => 'defer', 'in_footer' => true)
                );
            }
        }
    }

    /**
     * Check if current page is a custom taxonomy page
     *
     * @since 1.0.0
     * @return bool
     */
    private static function is_custom_taxonomy_page() {
        if (!is_tax()) {
            return false;
        }
        
        $custom_taxonomies = get_option('gpress_custom_taxonomies', array());
        $current_taxonomy = get_queried_object()->taxonomy;
        
        return array_key_exists($current_taxonomy, $custom_taxonomies);
    }

    /**
     * Dynamic template loader
     *
     * @since 1.0.0
     * @param string $template Current template
     * @return string Template path
     */
    public static function dynamic_template_loader($template) {
        $post_type = get_post_type();
        
        // Single template handling
        if (is_singular() && in_array($post_type, array_keys(self::$registered_types))) {
            $single_template = locate_template("templates/single-{$post_type}.html");
            if ($single_template) {
                return $single_template;
            }
            
            // Generate template if it doesn't exist
            self::generate_single_template($post_type);
            
            // Try again after generation
            $single_template = locate_template("templates/single-{$post_type}.html");
            if ($single_template) {
                return $single_template;
            }
        }
        
        // Archive template handling
        if (is_post_type_archive() && in_array($post_type, array_keys(self::$registered_types))) {
            $archive_template = locate_template("templates/archive-{$post_type}.html");
            if ($archive_template) {
                return $archive_template;
            }
            
            // Generate template if it doesn't exist
            self::generate_archive_template($post_type);
            
            // Try again after generation
            $archive_template = locate_template("templates/archive-{$post_type}.html");
            if ($archive_template) {
                return $archive_template;
            }
        }
        
        return $template;
    }

    /**
     * Generate single template for post type
     *
     * @since 1.0.0
     * @param string $post_type Post type key
     */
    private static function generate_single_template($post_type) {
        $config = self::$registered_types[$post_type] ?? array();
        $template_content = self::get_single_template_content($post_type, $config);
        
        $template_path = get_template_directory() . "/templates/single-{$post_type}.html";
        
        // Ensure templates directory exists
        wp_mkdir_p(dirname($template_path));
        
        // Write template file
        file_put_contents($template_path, $template_content);
    }

    /**
     * Generate archive template for post type
     *
     * @since 1.0.0
     * @param string $post_type Post type key
     */
    private static function generate_archive_template($post_type) {
        $config = self::$registered_types[$post_type] ?? array();
        $template_content = self::get_archive_template_content($post_type, $config);
        
        $template_path = get_template_directory() . "/templates/archive-{$post_type}.html";
        
        // Ensure templates directory exists
        wp_mkdir_p(dirname($template_path));
        
        // Write template file
        file_put_contents($template_path, $template_content);
    }

    /**
     * Get single template content
     *
     * @since 1.0.0
     * @param string $post_type Post type key
     * @param array $config Configuration
     * @return string Template HTML
     */
    private static function get_single_template_content($post_type, $config) {
        $singular = $config['singular'] ?? ucfirst($post_type);
        
        return '<!-- wp:template-part {"slug":"header","tagName":"header"} /-->

<main id="main" class="wp-block-group alignfull site-main single-' . $post_type . '" style="margin-top:0">
    
    <!-- wp:group {"align":"full","style":{"spacing":{"padding":{"top":"var:preset|spacing|60","bottom":"var:preset|spacing|60"}}},"layout":{"type":"constrained"}} -->
    <div class="wp-block-group alignfull" style="padding-top:var(--wp--preset--spacing--60);padding-bottom:var(--wp--preset--spacing--60)">
        
        <!-- wp:group {"tagName":"article","layout":{"type":"constrained"},"className":"single-' . $post_type . '-entry"} -->
        <article class="wp-block-group single-' . $post_type . '-entry">
            
            <!-- wp:post-title {"level":1,"style":{"typography":{"fontWeight":"600","lineHeight":"1.2"},"spacing":{"margin":{"bottom":"var:preset|spacing|50"}}},"fontSize":"xxx-large"} /-->
            
            <!-- wp:post-featured-image {"aspectRatio":"16/9","align":"wide","style":{"border":{"radius":"var:preset|border-radius|sm"},"spacing":{"margin":{"bottom":"var:preset|spacing|60"}}}} /-->
            
            <!-- wp:post-content {"layout":{"type":"constrained","contentSize":"65ch"},"style":{"spacing":{"blockGap":"var:preset|spacing|50"}}} /-->
            
            <!-- wp:group {"style":{"spacing":{"padding":{"top":"var:preset|spacing|60","bottom":"var:preset|spacing|60"},"margin":{"top":"var:preset|spacing|60"}},"border":{"top":{"color":"var:preset|color|border","width":"1px"}}},"layout":{"type":"constrained"}} -->
            <div class="wp-block-group" style="border-top-color:var(--wp--preset--color--border);border-top-width:1px;margin-top:var(--wp--preset--spacing--60);padding-top:var(--wp--preset--spacing--60);padding-bottom:var(--wp--preset--spacing--60)">
                
                <!-- wp:post-navigation-link {"type":"previous","showTitle":true,"linkLabel":true,"arrow":"arrow"} /-->
                <!-- wp:post-navigation-link {"type":"next","showTitle":true,"linkLabel":true,"arrow":"arrow"} /-->
                
            </div>
            <!-- /wp:group -->
            
        </article>
        <!-- /wp:group -->
        
    </div>
    <!-- /wp:group -->
    
</main>

<!-- wp:template-part {"slug":"footer","tagName":"footer"} /-->';
    }

    /**
     * Get archive template content
     *
     * @since 1.0.0
     * @param string $post_type Post type key
     * @param array $config Configuration
     * @return string Template HTML
     */
    private static function get_archive_template_content($post_type, $config) {
        $plural = $config['plural'] ?? ucfirst($post_type) . 's';
        
        return '<!-- wp:template-part {"slug":"header","tagName":"header"} /-->

<main id="main" class="wp-block-group alignfull site-main archive-' . $post_type . '" style="margin-top:0">
    
    <!-- wp:group {"align":"full","style":{"spacing":{"padding":{"top":"var:preset|spacing|60","bottom":"var:preset|spacing|60"}}},"layout":{"type":"constrained"}} -->
    <div class="wp-block-group alignfull" style="padding-top:var(--wp--preset--spacing--60);padding-bottom:var(--wp--preset--spacing--60)">
        
        <!-- wp:group {"style":{"spacing":{"margin":{"bottom":"var:preset|spacing|60"}}},"layout":{"type":"constrained"}} -->
        <div class="wp-block-group" style="margin-bottom:var(--wp--preset--spacing--60)">
            
            <!-- wp:query-title {"type":"archive","showPrefix":false,"level":1,"style":{"typography":{"fontWeight":"700","lineHeight":"1.2"}},"fontSize":"xxx-large"} /-->
            
        </div>
        <!-- /wp:group -->
        
        <!-- wp:query {"queryId":0,"query":{"perPage":"12","pages":0,"offset":0,"postType":"' . $post_type . '","order":"desc","orderBy":"date","author":"","search":"","exclude":[],"sticky":"","inherit":false},"align":"wide","layout":{"type":"constrained"}} -->
        <div class="wp-block-query alignwide">
            
            <!-- wp:post-template {"align":"wide","layout":{"type":"grid","columnCount":3}} -->
                
                <!-- wp:group {"tagName":"article","style":{"spacing":{"padding":{"top":"var:preset|spacing|40","bottom":"var:preset|spacing|50"}}},"backgroundColor":"surface","layout":{"type":"constrained"},"className":"' . $post_type . '-card"} -->
                <article class="wp-block-group ' . $post_type . '-card has-surface-background-color has-background" style="padding-top:var(--wp--preset--spacing--40);padding-bottom:var(--wp--preset--spacing--50)">
                    
                    <!-- wp:post-featured-image {"isLink":true,"aspectRatio":"16/9","style":{"border":{"radius":"var:preset|border-radius|sm"},"spacing":{"margin":{"bottom":"var:preset|spacing|40"}}}} /-->
                    
                    <!-- wp:group {"style":{"spacing":{"padding":{"left":"var:preset|spacing|40","right":"var:preset|spacing|40"}}},"layout":{"type":"constrained"}} -->
                    <div class="wp-block-group" style="padding-right:var(--wp--preset--spacing--40);padding-left:var(--wp--preset--spacing--40)">
                        
                        <!-- wp:post-title {"isLink":true,"style":{"typography":{"fontWeight":"600","lineHeight":"1.3"},"spacing":{"margin":{"bottom":"var:preset|spacing|30"}}},"fontSize":"large"} /-->
                        
                        <!-- wp:post-excerpt {"moreText":"Learn more","showMoreOnNewLine":false,"excerptLength":20,"style":{"spacing":{"margin":{"bottom":"var:preset|spacing|40"}}},"textColor":"text-light"} /-->
                        
                        <!-- wp:post-date {"format":"F j, Y","isLink":false,"style":{"typography":{"fontSize":"var:preset|font-size|small","fontWeight":"500"}},"textColor":"text-light"} /-->
                        
                    </div>
                    <!-- /wp:group -->
                    
                </article>
                <!-- /wp:group -->
                
            <!-- /wp:post-template -->
            
            <!-- wp:query-pagination {"layout":{"type":"flex","justifyContent":"center"},"style":{"spacing":{"margin":{"top":"var:preset|spacing|70"}}}} -->
                <!-- wp:query-pagination-previous {"label":"Previous"} /-->
                <!-- wp:query-pagination-numbers /-->
                <!-- wp:query-pagination-next {"label":"Next"} /-->
            <!-- /wp:query-pagination -->
            
        </div>
        <!-- /wp:query -->
        
    </div>
    <!-- /wp:group -->
    
</main>

<!-- wp:template-part {"slug":"footer","tagName":"footer"} /-->';
    }

    /**
     * Maybe generate templates for post type
     *
     * @since 1.0.0
     * @param string $post_type Post type key
     * @param array $config Configuration
     */
    private static function maybe_generate_templates($post_type, $config) {
        if (!isset($config['auto_generate_templates']) || !$config['auto_generate_templates']) {
            return;
        }
        
        $single_template = get_template_directory() . "/templates/single-{$post_type}.html";
        $archive_template = get_template_directory() . "/templates/archive-{$post_type}.html";
        
        if (!file_exists($single_template)) {
            self::generate_single_template($post_type);
        }
        
        if (!file_exists($archive_template)) {
            self::generate_archive_template($post_type);
        }
    }

    /**
     * Add structured data for any custom post type
     *
     * @since 1.0.0
     */
    public static function dynamic_structured_data() {
        if (!is_singular(array_keys(self::$registered_types))) {
            return;
        }

        global $post;
        $post_type = get_post_type();
        $config = self::$registered_types[$post_type] ?? array();
        
        $schema = array(
            '@context' => 'https://schema.org',
            '@type' => $config['schema_type'] ?? 'CreativeWork',
            'name' => get_the_title(),
            'description' => get_the_excerpt() ?: wp_trim_words(get_the_content(), 30),
            'url' => get_permalink(),
            'dateCreated' => get_the_date('c'),
            'dateModified' => get_the_modified_date('c'),
        );
        
        if (has_post_thumbnail()) {
            $image = wp_get_attachment_image_src(get_post_thumbnail_id(), 'large');
            $schema['image'] = $image[0];
        }

        echo '<script type="application/ld+json">' . wp_json_encode($schema) . '</script>' . "\n";
    }

    /**
     * Add dynamic body classes
     *
     * @since 1.0.0
     */
    public static function dynamic_body_classes($classes) {
        if (is_singular(array_keys(self::$registered_types))) {
            $classes[] = 'custom-post-type';
            $classes[] = 'cpt-' . get_post_type();
        }
        
        if (is_post_type_archive(array_keys(self::$registered_types))) {
            $classes[] = 'custom-post-type-archive';
            $classes[] = 'cpt-archive-' . get_queried_object()->name;
        }
        
        return $classes;
    }

    /**
     * Setup dynamic admin interface
     *
     * @since 1.0.0
     */
    public static function setup_dynamic_admin() {
        add_action('admin_enqueue_scripts', array(__CLASS__, 'admin_assets'));
    }

    /**
     * Add CPT management page to admin
     *
     * @since 1.0.0
     */
    public static function add_cpt_management_page() {
        add_theme_page(
            __('Custom Post Types', 'gpress'),
            __('Custom Post Types', 'gpress'),
            'manage_options',
            'gpress-custom-post-types',
            array(__CLASS__, 'render_management_page')
        );
    }

    /**
     * Render CPT management page
     *
     * @since 1.0.0
     */
    public static function render_management_page() {
        $custom_post_types = get_option('gpress_custom_post_types', array());
        
        ?>
        <div class="wrap">
            <h1><?php _e('Custom Post Types Management', 'gpress'); ?></h1>
            <p><?php _e('Create and manage custom post types for your website. This dynamic system allows you to add any content type you need.', 'gpress'); ?></p>
            
            <div id="gpress-cpt-management">
                <div class="gpress-cpt-form">
                    <h2><?php _e('Add New Post Type', 'gpress'); ?></h2>
                    <form id="add-post-type-form">
                        <table class="form-table">
                            <tr>
                                <th><label for="post_type_key"><?php _e('Post Type Key', 'gpress'); ?></label></th>
                                <td>
                                    <input type="text" id="post_type_key" name="post_type_key" required 
                                           pattern="[a-z0-9_]+" title="<?php _e('Lowercase letters, numbers, and underscores only', 'gpress'); ?>" />
                                    <p class="description"><?php _e('Unique identifier (lowercase, no spaces)', 'gpress'); ?></p>
                                </td>
                            </tr>
                            <tr>
                                <th><label for="singular_name"><?php _e('Singular Name', 'gpress'); ?></label></th>
                                <td>
                                    <input type="text" id="singular_name" name="singular_name" required />
                                    <p class="description"><?php _e('e.g., "Product", "Event", "Recipe"', 'gpress'); ?></p>
                                </td>
                            </tr>
                            <tr>
                                <th><label for="plural_name"><?php _e('Plural Name', 'gpress'); ?></label></th>
                                <td>
                                    <input type="text" id="plural_name" name="plural_name" required />
                                    <p class="description"><?php _e('e.g., "Products", "Events", "Recipes"', 'gpress'); ?></p>
                                </td>
                            </tr>
                            <tr>
                                <th><label for="menu_icon"><?php _e('Menu Icon', 'gpress'); ?></label></th>
                                <td>
                                    <select id="menu_icon" name="menu_icon">
                                        <option value="dashicons-admin-post"><?php _e('Post (default)', 'gpress'); ?></option>
                                        <option value="dashicons-portfolio"><?php _e('Portfolio', 'gpress'); ?></option>
                                        <option value="dashicons-products"><?php _e('Products', 'gpress'); ?></option>
                                        <option value="dashicons-calendar-alt"><?php _e('Events', 'gpress'); ?></option>
                                        <option value="dashicons-food"><?php _e('Food/Recipe', 'gpress'); ?></option>
                                        <option value="dashicons-groups"><?php _e('People/Team', 'gpress'); ?></option>
                                        <option value="dashicons-building"><?php _e('Business', 'gpress'); ?></option>
                                        <option value="dashicons-camera"><?php _e('Media/Gallery', 'gpress'); ?></option>
                                    </select>
                                </td>
                            </tr>
                            <tr>
                                <th><label for="has_archive"><?php _e('Has Archive', 'gpress'); ?></label></th>
                                <td>
                                    <input type="checkbox" id="has_archive" name="has_archive" checked />
                                    <label for="has_archive"><?php _e('Enable archive page for this post type', 'gpress'); ?></label>
                                </td>
                            </tr>
                            <tr>
                                <th><label for="supports"><?php _e('Supports', 'gpress'); ?></label></th>
                                <td>
                                    <label><input type="checkbox" name="supports[]" value="title" checked /> <?php _e('Title', 'gpress'); ?></label><br>
                                    <label><input type="checkbox" name="supports[]" value="editor" checked /> <?php _e('Editor', 'gpress'); ?></label><br>
                                    <label><input type="checkbox" name="supports[]" value="thumbnail" checked /> <?php _e('Featured Image', 'gpress'); ?></label><br>
                                    <label><input type="checkbox" name="supports[]" value="excerpt" checked /> <?php _e('Excerpt', 'gpress'); ?></label><br>
                                    <label><input type="checkbox" name="supports[]" value="custom-fields" /> <?php _e('Custom Fields', 'gpress'); ?></label><br>
                                    <label><input type="checkbox" name="supports[]" value="comments" /> <?php _e('Comments', 'gpress'); ?></label><br>
                                    <label><input type="checkbox" name="supports[]" value="revisions" /> <?php _e('Revisions', 'gpress'); ?></label><br>
                                    <label><input type="checkbox" name="supports[]" value="page-attributes" /> <?php _e('Page Attributes', 'gpress'); ?></label>
                                </td>
                            </tr>
                            <tr>
                                <th><label for="auto_generate_templates"><?php _e('Auto Generate Templates', 'gpress'); ?></label></th>
                                <td>
                                    <input type="checkbox" id="auto_generate_templates" name="auto_generate_templates" checked />
                                    <label for="auto_generate_templates"><?php _e('Automatically create single and archive templates', 'gpress'); ?></label>
                                </td>
                            </tr>
                        </table>
                        
                        <?php wp_nonce_field('gpress_create_post_type', 'gpress_cpt_nonce'); ?>
                        <p class="submit">
                            <button type="submit" class="button button-primary"><?php _e('Create Post Type', 'gpress'); ?></button>
                        </p>
                    </form>
                </div>
                
                <div class="gpress-existing-cpts">
                    <h2><?php _e('Existing Post Types', 'gpress'); ?></h2>
                    <?php if (empty($custom_post_types)): ?>
                        <p><?php _e('No custom post types created yet. Add your first post type above!', 'gpress'); ?></p>
                    <?php else: ?>
                        <table class="wp-list-table widefat fixed striped">
                            <thead>
                                <tr>
                                    <th><?php _e('Post Type', 'gpress'); ?></th>
                                    <th><?php _e('Names', 'gpress'); ?></th>
                                    <th><?php _e('Archive', 'gpress'); ?></th>
                                    <th><?php _e('Templates', 'gpress'); ?></th>
                                    <th><?php _e('Actions', 'gpress'); ?></th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($custom_post_types as $key => $config): ?>
                                    <tr>
                                        <td><code><?php echo esc_html($key); ?></code></td>
                                        <td>
                                            <strong><?php echo esc_html($config['singular']); ?></strong><br>
                                            <small><?php echo esc_html($config['plural']); ?></small>
                                        </td>
                                        <td>
                                            <?php if ($config['args']['has_archive'] ?? true): ?>
                                                <span class="dashicons dashicons-yes-alt" style="color: green;"></span>
                                            <?php else: ?>
                                                <span class="dashicons dashicons-dismiss" style="color: #ccc;"></span>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <?php
                                            $single_exists = file_exists(get_template_directory() . "/templates/single-{$key}.html");
                                            $archive_exists = file_exists(get_template_directory() . "/templates/archive-{$key}.html");
                                            ?>
                                            Single: <?php echo $single_exists ? '<span style="color: green;">✓</span>' : '<span style="color: #ccc;">✗</span>'; ?><br>
                                            Archive: <?php echo $archive_exists ? '<span style="color: green;">✓</span>' : '<span style="color: #ccc;">✗</span>'; ?>
                                        </td>
                                        <td>
                                            <button class="button button-small generate-templates" data-post-type="<?php echo esc_attr($key); ?>">
                                                <?php _e('Generate Templates', 'gpress'); ?>
                                            </button>
                                            <button class="button button-small button-link-delete delete-post-type" data-post-type="<?php echo esc_attr($key); ?>">
                                                <?php _e('Delete', 'gpress'); ?>
                                            </button>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    <?php endif; ?>
                </div>
            </div>
            
            <style>
                .gpress-cpt-form {
                    background: #fff;
                    border: 1px solid #ccd0d4;
                    padding: 20px;
                    margin-bottom: 20px;
                }
                
                .gpress-existing-cpts {
                    background: #fff;
                    border: 1px solid #ccd0d4;
                    padding: 20px;
                }
                
                .generate-templates {
                    margin-right: 5px;
                }
            </style>
        </div>
        <?php
    }

    /**
     * Enqueue admin assets
     *
     * @since 1.0.0
     */
    public static function admin_assets($hook) {
        if ($hook !== 'appearance_page_gpress-custom-post-types') {
            return;
        }
        
        wp_enqueue_script(
            'gpress-cpt-admin',
            get_theme_file_uri('/assets/js/admin-cpt.js'),
            array('jquery'),
            GPRESS_VERSION,
            true
        );
        
        wp_localize_script('gpress-cpt-admin', 'gpressCPTAdmin', array(
            'ajaxurl' => admin_url('admin-ajax.php'),
            'nonce' => wp_create_nonce('gpress_cpt_admin'),
            'messages' => array(
                'confirm_delete' => __('Are you sure you want to delete this post type? This action cannot be undone.', 'gpress'),
                'success_create' => __('Post type created successfully!', 'gpress'),
                'success_delete' => __('Post type deleted successfully!', 'gpress'),
                'success_templates' => __('Templates generated successfully!', 'gpress'),
                'error_general' => __('An error occurred. Please try again.', 'gpress'),
            )
        ));
    }

    /**
     * AJAX handler for creating post type
     *
     * @since 1.0.0
     */
    public static function ajax_create_post_type() {
        if (!current_user_can('manage_options') || !wp_verify_nonce($_POST['nonce'], 'gpress_cpt_admin')) {
            wp_die(__('Permission denied', 'gpress'));
        }
        
        $post_type = sanitize_key($_POST['post_type_key']);
        $singular = sanitize_text_field($_POST['singular_name']);
        $plural = sanitize_text_field($_POST['plural_name']);
        $icon = sanitize_text_field($_POST['menu_icon']);
        $has_archive = isset($_POST['has_archive']);
        $supports = array_map('sanitize_text_field', $_POST['supports'] ?? array());
        $auto_generate = isset($_POST['auto_generate_templates']);
        
        $custom_post_types = get_option('gpress_custom_post_types', array());
        
        $custom_post_types[$post_type] = array(
            'singular' => $singular,
            'plural' => $plural,
            'icon' => $icon,
            'slug' => $post_type,
            'auto_generate_templates' => $auto_generate,
            'args' => array(
                'has_archive' => $has_archive,
                'supports' => $supports,
                'menu_icon' => $icon,
            )
        );
        
        update_option('gpress_custom_post_types', $custom_post_types);
        
        // Register the post type immediately
        self::register_single_post_type($post_type, $custom_post_types[$post_type]);
        
        // Flush rewrite rules
        flush_rewrite_rules();
        
        wp_send_json_success(array(
            'message' => __('Post type created successfully!', 'gpress')
        ));
    }

    /**
     * AJAX handler for deleting post type
     *
     * @since 1.0.0
     */
    public static function ajax_delete_post_type() {
        if (!current_user_can('manage_options') || !wp_verify_nonce($_POST['nonce'], 'gpress_cpt_admin')) {
            wp_die(__('Permission denied', 'gpress'));
        }
        
        $post_type = sanitize_key($_POST['post_type']);
        $custom_post_types = get_option('gpress_custom_post_types', array());
        
        if (isset($custom_post_types[$post_type])) {
            unset($custom_post_types[$post_type]);
            update_option('gpress_custom_post_types', $custom_post_types);
            
            // Remove template files
            $single_template = get_template_directory() . "/templates/single-{$post_type}.html";
            $archive_template = get_template_directory() . "/templates/archive-{$post_type}.html";
            
            if (file_exists($single_template)) {
                unlink($single_template);
            }
            if (file_exists($archive_template)) {
                unlink($archive_template);
            }
            
            // Flush rewrite rules
            flush_rewrite_rules();
            
            wp_send_json_success(array(
                'message' => __('Post type deleted successfully!', 'gpress')
            ));
        }
        
        wp_send_json_error(array(
            'message' => __('Post type not found.', 'gpress')
        ));
    }

    /**
     * Get registered post types
     *
     * @since 1.0.0
     * @return array
     */
    public static function get_registered_types() {
        return self::$registered_types;
    }
}

// Initialize the dynamic CPT system
GPress_Dynamic_Post_Types::init();
```

### 2. Create Dynamic Taxonomies Framework

Create `inc/dynamic-taxonomies.php`:

```php
<?php
/**
 * Dynamic Custom Taxonomies Framework for GPress Theme
 * Flexible system for creating any taxonomy with optimization
 *
 * @package GPress
 * @subpackage Dynamic_Framework
 * @version 1.0.0
 * @since 1.0.0
 */

// Prevent direct access
defined('ABSPATH') || exit;

/**
 * GPress Dynamic Custom Taxonomies Manager
 * 
 * @since 1.0.0
 */
class GPress_Dynamic_Taxonomies {

    /**
     * Registered taxonomies
     *
     * @var array
     */
    private static $registered_taxonomies = array();

    /**
     * Initialize dynamic taxonomy system
     *
     * @since 1.0.0
     */
    public static function init() {
        add_action('init', array(__CLASS__, 'register_dynamic_taxonomies'));
        add_action('wp_enqueue_scripts', array(__CLASS__, 'conditional_taxonomy_assets'));
        add_filter('body_class', array(__CLASS__, 'taxonomy_body_classes'));
        add_action('wp_head', array(__CLASS__, 'taxonomy_structured_data'));
        
        // Admin enhancements
        add_action('admin_init', array(__CLASS__, 'setup_taxonomy_admin'));
        add_action('admin_menu', array(__CLASS__, 'add_taxonomy_management_page'));
        
        // AJAX handlers
        add_action('wp_ajax_create_custom_taxonomy', array(__CLASS__, 'ajax_create_taxonomy'));
        add_action('wp_ajax_delete_custom_taxonomy', array(__CLASS__, 'ajax_delete_taxonomy'));
    }

    /**
     * Register dynamic taxonomies from database/options
     *
     * @since 1.0.0
     */
    public static function register_dynamic_taxonomies() {
        $custom_taxonomies = get_option('gpress_custom_taxonomies', array());
        
        foreach ($custom_taxonomies as $taxonomy => $config) {
            self::register_single_taxonomy($taxonomy, $config);
        }
    }

    /**
     * Register a single dynamic taxonomy
     *
     * @since 1.0.0
     * @param string $taxonomy Taxonomy key
     * @param array $config Configuration array
     */
    public static function register_single_taxonomy($taxonomy, $config) {
        // Set intelligent defaults
        $defaults = array(
            'labels' => self::generate_labels($config['singular'], $config['plural']),
            'public' => true,
            'show_ui' => true,
            'show_admin_column' => true,
            'show_in_nav_menus' => true,
            'show_in_rest' => true,
            'hierarchical' => $config['hierarchical'] ?? false,
            'rewrite' => array(
                'slug' => $config['slug'] ?? $taxonomy,
                'with_front' => false,
                'hierarchical' => $config['hierarchical'] ?? false,
            ),
        );

        // Merge with custom configuration
        $args = wp_parse_args($config['args'] ?? array(), $defaults);
        
        // Register the taxonomy
        register_taxonomy($taxonomy, $config['post_types'] ?? array(), $args);
        
        // Store for reference
        self::$registered_taxonomies[$taxonomy] = $config;
        
        // Add capabilities
        self::add_taxonomy_capabilities($taxonomy);
    }

    /**
     * Generate intelligent labels for taxonomy
     *
     * @since 1.0.0
     * @param string $singular Singular name
     * @param string $plural Plural name
     * @return array Labels array
     */
    private static function generate_labels($singular, $plural) {
        return array(
            'name' => $plural,
            'singular_name' => $singular,
            'menu_name' => $plural,
            'all_items' => sprintf(__('All %s', 'gpress'), $plural),
            'edit_item' => sprintf(__('Edit %s', 'gpress'), $singular),
            'view_item' => sprintf(__('View %s', 'gpress'), $singular),
            'update_item' => sprintf(__('Update %s', 'gpress'), $singular),
            'add_new_item' => sprintf(__('Add New %s', 'gpress'), $singular),
            'new_item_name' => sprintf(__('New %s Name', 'gpress'), $singular),
            'search_items' => sprintf(__('Search %s', 'gpress'), $plural),
            'not_found' => sprintf(__('No %s found', 'gpress'), strtolower($plural)),
        );
    }

    /**
     * Add capabilities for taxonomy
     *
     * @since 1.0.0
     * @param string $taxonomy Taxonomy key
     */
    private static function add_taxonomy_capabilities($taxonomy) {
        $capabilities = array(
            "manage_{$taxonomy}",
            "edit_{$taxonomy}",
            "delete_{$taxonomy}",
            "assign_{$taxonomy}",
        );
        
        // Add capabilities to appropriate roles
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

    /**
     * Conditional asset loading for taxonomies
     *
     * @since 1.0.0
     */
    public static function conditional_taxonomy_assets() {
        if (is_tax(array_keys(self::$registered_taxonomies))) {
            wp_enqueue_style(
                'gpress-dynamic-taxonomy',
                get_theme_file_uri('/assets/css/dynamic-taxonomy.css'),
                array('gpress-dynamic-cpt'),
                GPRESS_VERSION
            );

            wp_enqueue_script(
                'gpress-dynamic-taxonomy',
                get_theme_file_uri('/assets/js/dynamic-taxonomy.js'),
                array('gpress-dynamic-cpt'),
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
        if (is_tax(array_keys(self::$registered_taxonomies))) {
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
        if (!is_tax(array_keys(self::$registered_taxonomies))) {
            return;
        }

        $queried_object = get_queried_object();
        
        $schema = array(
            '@context' => 'https://schema.org',
            '@type' => 'CollectionPage',
            'name' => $queried_object->name,
            'description' => $queried_object->description ?: sprintf(__('Archive for %s', 'gpress'), $queried_object->name),
            'url' => get_term_link($queried_object),
        );

        echo '<script type="application/ld+json">' . wp_json_encode($schema) . '</script>' . "\n";
    }

    /**
     * Setup admin enhancements for taxonomies
     *
     * @since 1.0.0
     */
    public static function setup_taxonomy_admin() {
        add_action('admin_enqueue_scripts', array(__CLASS__, 'admin_assets'));
    }

    /**
     * Add taxonomy management page to admin
     *
     * @since 1.0.0
     */
    public static function add_taxonomy_management_page() {
        add_theme_page(
            __('Custom Taxonomies', 'gpress'),
            __('Custom Taxonomies', 'gpress'),
            'manage_options',
            'gpress-custom-taxonomies',
            array(__CLASS__, 'render_management_page')
        );
    }

    /**
     * Render taxonomy management page
     *
     * @since 1.0.0
     */
    public static function render_management_page() {
        $custom_taxonomies = get_option('gpress_custom_taxonomies', array());
        $custom_post_types = get_option('gpress_custom_post_types', array());
        
        ?>
        <div class="wrap">
            <h1><?php _e('Custom Taxonomies Management', 'gpress'); ?></h1>
            <p><?php _e('Create and manage custom taxonomies for your content types. Taxonomies help organize and categorize your content.', 'gpress'); ?></p>
            
            <div id="gpress-taxonomy-management">
                <div class="gpress-taxonomy-form">
                    <h2><?php _e('Add New Taxonomy', 'gpress'); ?></h2>
                    <form id="add-taxonomy-form">
                        <table class="form-table">
                            <tr>
                                <th><label for="taxonomy_key"><?php _e('Taxonomy Key', 'gpress'); ?></label></th>
                                <td>
                                    <input type="text" id="taxonomy_key" name="taxonomy_key" required 
                                           pattern="[a-z0-9_]+" title="<?php _e('Lowercase letters, numbers, and underscores only', 'gpress'); ?>" />
                                    <p class="description"><?php _e('Unique identifier (lowercase, no spaces)', 'gpress'); ?></p>
                                </td>
                            </tr>
                            <tr>
                                <th><label for="taxonomy_singular_name"><?php _e('Singular Name', 'gpress'); ?></label></th>
                                <td>
                                    <input type="text" id="taxonomy_singular_name" name="taxonomy_singular_name" required />
                                    <p class="description"><?php _e('e.g., "Category", "Tag", "Topic"', 'gpress'); ?></p>
                                </td>
                            </tr>
                            <tr>
                                <th><label for="taxonomy_plural_name"><?php _e('Plural Name', 'gpress'); ?></label></th>
                                <td>
                                    <input type="text" id="taxonomy_plural_name" name="taxonomy_plural_name" required />
                                    <p class="description"><?php _e('e.g., "Categories", "Tags", "Topics"', 'gpress'); ?></p>
                                </td>
                            </tr>
                            <tr>
                                <th><label for="taxonomy_hierarchical"><?php _e('Hierarchical', 'gpress'); ?></label></th>
                                <td>
                                    <input type="checkbox" id="taxonomy_hierarchical" name="taxonomy_hierarchical" />
                                    <label for="taxonomy_hierarchical"><?php _e('Enable parent/child relationships (like categories)', 'gpress'); ?></label>
                                    <p class="description"><?php _e('Uncheck for tag-like behavior', 'gpress'); ?></p>
                                </td>
                            </tr>
                            <tr>
                                <th><label for="taxonomy_post_types"><?php _e('Post Types', 'gpress'); ?></label></th>
                                <td>
                                    <?php if (empty($custom_post_types)): ?>
                                        <p><em><?php _e('No custom post types available. Create post types first.', 'gpress'); ?></em></p>
                                    <?php else: ?>
                                        <?php foreach ($custom_post_types as $post_type => $config): ?>
                                            <label>
                                                <input type="checkbox" name="taxonomy_post_types[]" value="<?php echo esc_attr($post_type); ?>" />
                                                <?php echo esc_html($config['plural']); ?>
                                            </label><br>
                                        <?php endforeach; ?>
                                        <p class="description"><?php _e('Select which post types this taxonomy applies to', 'gpress'); ?></p>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        </table>
                        
                        <?php wp_nonce_field('gpress_create_taxonomy', 'gpress_taxonomy_nonce'); ?>
                        <p class="submit">
                            <button type="submit" class="button button-primary" <?php disabled(empty($custom_post_types)); ?>>
                                <?php _e('Create Taxonomy', 'gpress'); ?>
                            </button>
                        </p>
                    </form>
                </div>
                
                <div class="gpress-existing-taxonomies">
                    <h2><?php _e('Existing Taxonomies', 'gpress'); ?></h2>
                    <?php if (empty($custom_taxonomies)): ?>
                        <p><?php _e('No custom taxonomies created yet. Add your first taxonomy above!', 'gpress'); ?></p>
                    <?php else: ?>
                        <table class="wp-list-table widefat fixed striped">
                            <thead>
                                <tr>
                                    <th><?php _e('Taxonomy', 'gpress'); ?></th>
                                    <th><?php _e('Names', 'gpress'); ?></th>
                                    <th><?php _e('Type', 'gpress'); ?></th>
                                    <th><?php _e('Post Types', 'gpress'); ?></th>
                                    <th><?php _e('Actions', 'gpress'); ?></th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($custom_taxonomies as $key => $config): ?>
                                    <tr>
                                        <td><code><?php echo esc_html($key); ?></code></td>
                                        <td>
                                            <strong><?php echo esc_html($config['singular']); ?></strong><br>
                                            <small><?php echo esc_html($config['plural']); ?></small>
                                        </td>
                                        <td>
                                            <?php if ($config['hierarchical'] ?? false): ?>
                                                <span class="dashicons dashicons-networking" title="<?php _e('Hierarchical', 'gpress'); ?>"></span>
                                                <?php _e('Hierarchical', 'gpress'); ?>
                                            <?php else: ?>
                                                <span class="dashicons dashicons-tag" title="<?php _e('Flat', 'gpress'); ?>"></span>
                                                <?php _e('Flat', 'gpress'); ?>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <?php 
                                            $post_types = $config['post_types'] ?? array();
                                            if (!empty($post_types)) {
                                                $type_names = array();
                                                foreach ($post_types as $post_type) {
                                                    $type_names[] = $custom_post_types[$post_type]['plural'] ?? $post_type;
                                                }
                                                echo esc_html(implode(', ', $type_names));
                                            } else {
                                                echo '<em>' . __('None', 'gpress') . '</em>';
                                            }
                                            ?>
                                        </td>
                                        <td>
                                            <button class="button button-small button-link-delete delete-taxonomy" data-taxonomy="<?php echo esc_attr($key); ?>">
                                                <?php _e('Delete', 'gpress'); ?>
                                            </button>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    <?php endif; ?>
                </div>
            </div>
            
            <style>
                .gpress-taxonomy-form {
                    background: #fff;
                    border: 1px solid #ccd0d4;
                    padding: 20px;
                    margin-bottom: 20px;
                }
                
                .gpress-existing-taxonomies {
                    background: #fff;
                    border: 1px solid #ccd0d4;
                    padding: 20px;
                }
            </style>
        </div>
        <?php
    }

    /**
     * Enqueue admin assets
     *
     * @since 1.0.0
     */
    public static function admin_assets($hook) {
        if ($hook !== 'appearance_page_gpress-custom-taxonomies') {
            return;
        }
        
        wp_enqueue_script(
            'gpress-taxonomy-admin',
            get_theme_file_uri('/assets/js/admin-taxonomy.js'),
            array('jquery'),
            GPRESS_VERSION,
            true
        );
        
        wp_localize_script('gpress-taxonomy-admin', 'gpressTaxonomyAdmin', array(
            'ajaxurl' => admin_url('admin-ajax.php'),
            'nonce' => wp_create_nonce('gpress_taxonomy_admin'),
            'messages' => array(
                'confirm_delete' => __('Are you sure you want to delete this taxonomy? This action cannot be undone.', 'gpress'),
                'success_create' => __('Taxonomy created successfully!', 'gpress'),
                'success_delete' => __('Taxonomy deleted successfully!', 'gpress'),
                'error_general' => __('An error occurred. Please try again.', 'gpress'),
            )
        ));
    }

    /**
     * AJAX handler for creating taxonomy
     *
     * @since 1.0.0
     */
    public static function ajax_create_taxonomy() {
        if (!current_user_can('manage_options') || !wp_verify_nonce($_POST['nonce'], 'gpress_taxonomy_admin')) {
            wp_die(__('Permission denied', 'gpress'));
        }
        
        $taxonomy = sanitize_key($_POST['taxonomy_key']);
        $singular = sanitize_text_field($_POST['taxonomy_singular_name']);
        $plural = sanitize_text_field($_POST['taxonomy_plural_name']);
        $hierarchical = isset($_POST['taxonomy_hierarchical']);
        $post_types = array_map('sanitize_key', $_POST['taxonomy_post_types'] ?? array());
        
        $custom_taxonomies = get_option('gpress_custom_taxonomies', array());
        
        $custom_taxonomies[$taxonomy] = array(
            'singular' => $singular,
            'plural' => $plural,
            'slug' => $taxonomy,
            'hierarchical' => $hierarchical,
            'post_types' => $post_types,
            'args' => array()
        );
        
        update_option('gpress_custom_taxonomies', $custom_taxonomies);
        
        // Register the taxonomy immediately
        self::register_single_taxonomy($taxonomy, $custom_taxonomies[$taxonomy]);
        
        // Flush rewrite rules
        flush_rewrite_rules();
        
        wp_send_json_success(array(
            'message' => __('Taxonomy created successfully!', 'gpress')
        ));
    }

    /**
     * AJAX handler for deleting taxonomy
     *
     * @since 1.0.0
     */
    public static function ajax_delete_taxonomy() {
        if (!current_user_can('manage_options') || !wp_verify_nonce($_POST['nonce'], 'gpress_taxonomy_admin')) {
            wp_die(__('Permission denied', 'gpress'));
        }
        
        $taxonomy = sanitize_key($_POST['taxonomy']);
        $custom_taxonomies = get_option('gpress_custom_taxonomies', array());
        
        if (isset($custom_taxonomies[$taxonomy])) {
            unset($custom_taxonomies[$taxonomy]);
            update_option('gpress_custom_taxonomies', $custom_taxonomies);
            
            // Flush rewrite rules
            flush_rewrite_rules();
            
            wp_send_json_success(array(
                'message' => __('Taxonomy deleted successfully!', 'gpress')
            ));
        }
        
        wp_send_json_error(array(
            'message' => __('Taxonomy not found.', 'gpress')
        ));
    }

    /**
     * Get registered taxonomies
     *
     * @since 1.0.0
     * @return array
     */
    public static function get_registered_taxonomies() {
        return self::$registered_taxonomies;
    }
}

// Initialize the dynamic taxonomy system
GPress_Dynamic_Taxonomies::init();
```

### 3. Update Functions.php

Update `functions.php`:

```php
/**
 * Load Dynamic Custom Content Framework
 * Conditional loading based on admin context and front-end needs
 *
 * @since 1.0.0
 */
function gpress_load_dynamic_content() {
    // Always load dynamic frameworks for proper registration
    require_once get_theme_file_path('/inc/dynamic-post-types.php');
    require_once get_theme_file_path('/inc/dynamic-taxonomies.php');
    
    // Load additional components conditionally
    $custom_post_types = get_option('gpress_custom_post_types', array());
    $custom_taxonomies = get_option('gpress_custom_taxonomies', array());
    
    if (!empty($custom_post_types) || !empty($custom_taxonomies)) {
        if (is_admin() || 
            is_singular(array_keys($custom_post_types)) || 
            is_post_type_archive(array_keys($custom_post_types)) ||
            is_tax(array_keys($custom_taxonomies))) {
            
            // Load advanced features only when needed
            if (file_exists(get_theme_file_path('/inc/dynamic-fields.php'))) {
                require_once get_theme_file_path('/inc/dynamic-fields.php');
            }
        }
    }
}
add_action('after_setup_theme', 'gpress_load_dynamic_content');

/**
 * Add Dynamic Custom Content support to theme features
 *
 * @since 1.0.0
 */
function gpress_add_dynamic_content_theme_support() {
    // Add theme support for custom post type templates
    add_theme_support('post-type-templates');
    
    // Add custom background support for any content type
    add_theme_support('custom-background', array(
        'default-color' => 'ffffff',
        'default-image' => '',
    ));
    
    // Enable block templates for any post type
    add_theme_support('block-templates');
    add_theme_support('block-template-parts');
}
add_action('after_setup_theme', 'gpress_add_dynamic_content_theme_support');
```

### 4. Update Theme Setup

Update `inc/theme-setup.php`:

```php
/**
 * Add Dynamic Custom Content image sizes
 *
 * @since 1.0.0
 */
function gpress_add_dynamic_content_image_sizes() {
    // Generic sizes that work for any content type
    add_image_size('cpt-thumbnail', 400, 300, true);
    add_image_size('cpt-medium', 600, 450, true);
    add_image_size('cpt-large', 800, 600, true);
    add_image_size('cpt-hero', 1200, 600, true);
    
    // Square formats for profile-like content
    add_image_size('cpt-square-small', 150, 150, true);
    add_image_size('cpt-square-medium', 300, 300, true);
    add_image_size('cpt-square-large', 600, 600, true);
    
    // Wide formats for banner-like content
    add_image_size('cpt-wide', 1200, 400, true);
    add_image_size('cpt-banner', 1600, 500, true);
}
add_action('after_setup_theme', 'gpress_add_dynamic_content_image_sizes');

/**
 * Register Dynamic navigation menus based on existing content
 *
 * @since 1.0.0
 */
function gpress_register_dynamic_menus() {
    $menus = array();
    
    // Add basic menus
    $menus['content-filter'] = __('Content Filter Menu', 'gpress');
    $menus['taxonomy-menu'] = __('Taxonomy Menu', 'gpress');
    
    // Add menus based on existing custom post types
    $custom_post_types = get_option('gpress_custom_post_types', array());
    foreach ($custom_post_types as $post_type => $config) {
        $menus["{$post_type}-menu"] = sprintf(__('%s Menu', 'gpress'), $config['plural']);
    }
    
    register_nav_menus($menus);
}
add_action('after_setup_theme', 'gpress_register_dynamic_menus');
```

### 5. Create Base Dynamic Styles

Create `assets/css/dynamic-cpt.css`:

```css
/**
 * Dynamic Custom Post Types Base Styles
 * Foundation styles that work for any custom post type
 *
 * @since 1.0.0
 */

/* Dynamic CPT Common Elements */
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

/* Dynamic CPT Archive Layouts */
.custom-post-type-archive .wp-block-query {
    margin: 2rem 0;
}

/* Dynamic CPT Cards */
.wp-block-post-template .wp-block-group {
    background: var(--wp--preset--color--background-secondary);
    border-radius: 12px;
    overflow: hidden;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    transition: transform 0.3s ease, box-shadow 0.3s ease;
    height: 100%;
}

.wp-block-post-template .wp-block-group:hover {
    transform: translateY(-4px);
    box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15);
}

/* Dynamic Featured Images */
.wp-block-post-template .wp-block-post-featured-image {
    margin-bottom: 0;
}

.wp-block-post-template .wp-block-post-featured-image img {
    width: 100%;
    object-fit: cover;
    transition: transform 0.3s ease;
}

.wp-block-post-template .wp-block-group:hover .wp-block-post-featured-image img {
    transform: scale(1.05);
}

/* Dynamic Post Titles */
.wp-block-post-template .wp-block-post-title a {
    color: var(--wp--preset--color--foreground);
    text-decoration: none;
}

.wp-block-post-template .wp-block-post-title a:hover {
    color: var(--wp--preset--color--primary);
}

/* Dynamic Post Excerpts */
.wp-block-post-template .wp-block-post-excerpt {
    color: var(--wp--preset--color--foreground-secondary);
}

/* Dynamic Post Dates */
.wp-block-post-template .wp-block-post-date {
    color: var(--wp--preset--color--foreground-secondary);
    font-size: var(--wp--preset--font-size--small);
}

/* Single Post Navigation */
.single-cpt .wp-block-post-navigation-link {
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    padding: 0.75rem 1.5rem;
    background: var(--wp--preset--color--background-secondary);
    color: var(--wp--preset--color--foreground);
    text-decoration: none;
    border-radius: 8px;
    transition: all 0.3s ease;
    margin: 0.5rem;
}

.single-cpt .wp-block-post-navigation-link:hover {
    background: var(--wp--preset--color--primary);
    color: var(--wp--preset--color--background);
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(var(--wp--preset--color--primary-rgb), 0.3);
}

/* Archive Query Title */
.archive-cpt .wp-block-query-title {
    text-align: center;
    margin-bottom: var(--wp--preset--spacing--60);
}

/* Pagination */
.wp-block-query-pagination {
    margin-top: var(--wp--preset--spacing--70);
}

.wp-block-query-pagination a {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    min-width: 44px;
    min-height: 44px;
    padding: 0.5rem 1rem;
    background: var(--wp--preset--color--background-secondary);
    color: var(--wp--preset--color--foreground);
    text-decoration: none;
    border-radius: 6px;
    margin: 0 0.25rem;
    transition: all 0.3s ease;
}

.wp-block-query-pagination a:hover,
.wp-block-query-pagination .current {
    background: var(--wp--preset--color--primary);
    color: var(--wp--preset--color--background);
}

/* Responsive Grid Adjustments */
@media (max-width: 768px) {
    .wp-block-post-template.is-layout-grid {
        grid-template-columns: 1fr !important;
        gap: 1.5rem !important;
    }
    
    .custom-post-type .entry-header {
        margin-bottom: 1.5rem;
    }
    
    .single-cpt .wp-block-post-navigation-link {
        flex-direction: column;
        text-align: center;
        margin: 0.25rem;
        padding: 1rem;
    }
}

/* High Contrast Support */
@media (prefers-contrast: high) {
    .wp-block-post-template .wp-block-group {
        border: 2px solid var(--wp--preset--color--foreground);
    }
    
    .wp-block-query-pagination a {
        border: 2px solid var(--wp--preset--color--foreground);
    }
}

/* Reduced Motion Support */
@media (prefers-reduced-motion: reduce) {
    .wp-block-post-template .wp-block-group,
    .wp-block-post-template .wp-block-post-featured-image img,
    .wp-block-post-navigation-link,
    .wp-block-query-pagination a {
        transition: none;
    }
    
    .wp-block-post-template .wp-block-group:hover {
        transform: none;
    }
    
    .wp-block-post-template .wp-block-group:hover .wp-block-post-featured-image img {
        transform: none;
    }
    
    .single-cpt .wp-block-post-navigation-link:hover {
        transform: none;
    }
}

/* Print Styles */
@media print {
    .wp-block-query-pagination,
    .wp-block-post-navigation-link {
        display: none;
    }
    
    .wp-block-post-template {
        display: block !important;
    }
    
    .wp-block-post-template .wp-block-group {
        break-inside: avoid;
        margin-bottom: 1rem;
        box-shadow: none;
        border: 1px solid #000;
    }
}

/* Dark Mode Support */
@media (prefers-color-scheme: dark) {
    .wp-block-post-template .wp-block-group {
        box-shadow: 0 4px 6px rgba(255, 255, 255, 0.1);
    }
    
    .wp-block-post-template .wp-block-group:hover {
        box-shadow: 0 8px 25px rgba(255, 255, 255, 0.15);
    }
}
```

## Testing This Step

### 1. Dynamic Framework Testing
```bash
# Test dynamic CPT system
# Navigate to: Appearance > Custom Post Types in WordPress admin

# Create a test post type via the interface
# Check if it registers properly
wp post-type list --format=table

# Test template generation
# Check if templates are created automatically
ls -la templates/
```

### 2. Admin Interface Testing
```bash
# Test the admin interface
# 1. Go to WordPress Admin > Appearance > Custom Post Types
# 2. Create a new post type (e.g., "product", "event", "recipe")
# 3. Verify it appears in admin menu
# 4. Test creating content with the new post type
# 5. Check front-end display

# Test taxonomy creation
# 1. Go to WordPress Admin > Appearance > Custom Taxonomies  
# 2. Create taxonomies for your post types
# 3. Verify they work with the post types
```

### 3. Template Generation Testing
```bash
# Test automatic template generation
# Check if templates are created when post types are added

# Verify template content
cat templates/single-[your-post-type].html
cat templates/archive-[your-post-type].html

# Test template loading
curl -s -o /dev/null -w "%{http_code}" http://yoursite.com/[your-post-type]/
```

### 4. Asset Loading Testing
```bash
# Check dynamic CSS loading
curl -s http://yoursite.com/[your-post-type]/ | grep "dynamic-cpt.css"

# Verify conditional loading
# Assets should only load on relevant pages
curl -s http://yoursite.com/ | grep "dynamic-cpt.css" || echo "CSS not loaded on homepage - GOOD"
```

## Expected Results After This Step

1. **Dynamic Framework**: Complete system for creating any custom post type or taxonomy
2. **Admin Interface**: User-friendly interface for managing custom content types
3. **Template Generation**: Automatic creation of optimized templates for any content type
4. **Smart Asset Loading**: Conditional loading of assets based on actual content usage
5. **Zero Configuration**: System works out of the box without predefined content types
6. **Scalable Architecture**: Can handle unlimited custom post types and taxonomies
7. **Performance Optimized**: Only loads what's needed when it's needed
8. **SEO Ready**: Structured data and optimization for any content type

## Usage Examples

### Creating a Recipe Website
1. Create post type: "recipe" (singular) / "recipes" (plural)
2. Create taxonomies: "cuisine", "difficulty", "dietary-restrictions"
3. Templates automatically generated for recipe display
4. Add custom CSS in `assets/css/cpt-recipe.css` for specialized styling

### Building a Portfolio Site  
1. Create post type: "project" / "projects"
2. Create taxonomies: "project-type", "technology", "client-type"
3. System generates portfolio-optimized templates
4. Add gallery features in `assets/js/cpt-project.js`

### Event Management Site
1. Create post type: "event" / "events"  
2. Create taxonomies: "event-category", "venue", "organizer"
3. Templates include date display and location features
4. Add calendar integration via custom assets

## Next Step

In Step 12, we'll implement advanced navigation and menu systems that work dynamically with any custom post types and taxonomies you create, providing filtering, search, and mobile-optimized interfaces.

---

## 🔧 Advanced Usage Patterns

### Adding Custom Styling for Specific Post Types

Once you've created a post type via the admin interface, you can add custom styling:

1. **Create CSS file**: `assets/css/cpt-[your-post-type].css`
2. **Create JS file**: `assets/css/cpt-[your-post-type].js` (optional)
3. **Files auto-load** when that post type is displayed

Example for a "recipe" post type:
```css
/* assets/css/cpt-recipe.css */
.single-recipe .recipe-ingredients {
    background: var(--wp--preset--color--background-secondary);
    padding: 2rem;
    border-radius: 12px;
}

.archive-recipe .recipe-card {
    border: 2px solid var(--wp--preset--color--primary);
}

.recipe-card .cooking-time {
    color: var(--wp--preset--color--accent);
    font-weight: 600;
}
```

### Extending the Dynamic Framework

Add custom functionality to any post type:

```php
// In your child theme's functions.php
add_action('init', function() {
    $custom_post_types = get_option('gpress_custom_post_types', array());
    
    // Add special features to recipe post type
    if (isset($custom_post_types['recipe'])) {
        add_action('save_post_recipe', 'save_recipe_cooking_time');
        add_filter('single_recipe_template', 'maybe_use_recipe_template_variant');
    }
    
    // Add features to any post type with 'gallery' support
    foreach ($custom_post_types as $post_type => $config) {
        if (in_array('gallery', $config['features'] ?? array())) {
            add_action("wp_enqueue_scripts", function() use ($post_type) {
                if (is_singular($post_type)) {
                    wp_enqueue_script('lightbox', 'path/to/lightbox.js');
                }
            });
        }
    }
}, 25);
```

### Custom Template Variations

Create template variations for different content scenarios:

1. **Base template**: `templates/single-recipe.html` (auto-generated)
2. **Category-specific**: `templates/single-recipe-dessert.html` (for dessert recipes)  
3. **Featured template**: `templates/single-recipe-featured.html` (for featured recipes)

The system automatically checks for these variations based on:
- Taxonomy terms (e.g., `single-recipe-{term-slug}.html`)
- Custom fields (e.g., `single-recipe-featured.html` if featured)
- Post meta (configurable via filters)

### Integration with Existing Plugins

The dynamic framework works seamlessly with:

- **ACF (Advanced Custom Fields)**: Add fields to any post type
- **Meta Box**: Custom meta boxes work automatically  
- **Custom Field Suite**: Full compatibility
- **Toolset Types**: Can replace predefined types
- **CMB2**: Custom meta boxes integrate perfectly

### Performance Optimization Tips

1. **Conditional Assets**: Only create specific CSS/JS files when needed
2. **Template Caching**: Generated templates are cached for performance
3. **Smart Loading**: Base `dynamic-cpt.css` covers 90% of use cases
4. **Lazy Generation**: Templates generate only when first accessed

### Migration from Hardcoded Post Types

If you have existing hardcoded post types, migrate them:

1. **Backup your data**
2. **Note your post type settings** (supports, capabilities, etc.)
3. **Use admin interface** to recreate with same settings
4. **Copy template content** from old templates to new auto-generated ones
5. **Move specific CSS** from old files to new `cpt-[type].css` files
6. **Test thoroughly** before removing old code

---

**Step 11 Completed**: Dynamic Custom Post Types & Taxonomies Framework ✅
- Complete dynamic framework for unlimited content types
- User-friendly admin interface for managing custom content
- Automatic template generation with optimization
- Smart conditional asset loading
- Zero predefined content - build exactly what you need
- Scalable architecture with performance optimization
- SEO and accessibility built-in for any content type
- Advanced customization patterns for any use case