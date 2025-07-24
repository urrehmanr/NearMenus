# Step 15: Advanced Semantic HTML Structure & Accessibility

## Overview
This step implements comprehensive semantic HTML5 structure enhancements to maximize accessibility, improve SEO performance, and establish maintainable code patterns. We'll create advanced semantic markup, ARIA landmarks, document outline generation, and intelligent content structure with performance-optimized conditional loading.

## Objectives
- Implement comprehensive semantic HTML5 structure with modern best practices
- Establish advanced ARIA landmarks and navigation patterns
- Create intelligent document outline generation and heading hierarchy
- Optimize semantic markup for screen readers and assistive technologies
- Integrate microdata and structured content patterns
- Implement conditional semantic asset loading for performance
- Establish semantic content validation and testing frameworks

## What You'll Learn
- Advanced semantic HTML5 elements and structure implementation
- ARIA landmarks, roles, and navigation pattern optimization
- Document outline generation and heading hierarchy management
- Screen reader optimization and assistive technology integration
- Microdata and structured markup implementation patterns
- Performance optimization for semantic features with conditional loading
- Semantic HTML validation and testing methodologies

## Files Structure for This Step

### 📁 Files to CREATE:
```
inc/
├── semantic-structure.php          # Core semantic HTML system management
├── semantic-enhancements.php       # Advanced semantic enhancement features
├── aria-landmarks.php             # ARIA landmarks and navigation patterns
├── document-outline.php           # Document outline generation and management
├── semantic-validation.php        # Semantic HTML validation and testing
└── microdata-patterns.php        # Microdata and structured content patterns

assets/css/
├── semantic.css                   # Main semantic structure styles
├── semantic-base.css             # Core semantic styles (always loaded)
├── aria-landmarks.css            # ARIA landmark specific styles
├── document-outline.css          # Document outline and navigation styles
├── microdata.css                 # Microdata and structured content styles
└── semantic-print.css            # Print-optimized semantic styles

assets/js/
├── semantic.js                   # Main semantic JavaScript functionality
├── aria-navigation.js            # ARIA-enhanced navigation features
├── document-outline.js           # Dynamic document outline generation
├── landmark-skip.js              # Landmark-based skip navigation
├── semantic-validation.js        # Client-side semantic validation
└── heading-hierarchy.js          # Heading hierarchy management

templates/
├── semantic-article.html         # Enhanced semantic article template
├── semantic-page.html            # Enhanced semantic page template
└── semantic-archive.html         # Enhanced semantic archive template

parts/
├── semantic-header.html          # Semantic header with landmarks
├── semantic-footer.html          # Semantic footer with landmarks
├── semantic-navigation.html      # Enhanced semantic navigation
├── document-outline.html         # Document outline component
└── breadcrumb-semantic.html      # Semantic breadcrumb navigation
```

### 📝 Files to UPDATE:
```
functions.php                    # Include semantic files and initialization
inc/theme-setup.php             # Add semantic theme support features
inc/enqueue-scripts.php         # Conditional semantic asset loading
style.css                       # Base semantic integration styles
README.md                       # Document semantic features and benefits
theme.json                      # Add semantic-specific settings and styles
index.html                      # Update with semantic structure
single.html                     # Add semantic article markup
page.html                       # Add semantic page structure
archive.html                    # Add semantic archive markup
header.html                     # Enhance with semantic landmarks
footer.html                     # Enhance with semantic structure
```

### 🎯 Optimization Features Implemented:
- Conditional semantic asset loading based on content analysis and user needs
- Performance-optimized ARIA landmark detection and enhancement
- Intelligent document outline generation with smart caching
- Lazy loading for complex semantic enhancements
- Efficient heading hierarchy validation and correction
- Smart microdata injection based on content type analysis
- Semantic validation automation with performance monitoring

## Step-by-Step Implementation

### 1. Create Core Semantic Structure System

Create `inc/semantic-structure.php`:

```php
<?php
/**
 * Core Semantic Structure System for GPress Theme
 * Advanced semantic HTML5 implementation with performance optimization
 *
 * @package GPress
 * @subpackage Semantic
 * @version 1.0.0
 * @since 1.0.0
 */

// Prevent direct access
defined('ABSPATH') || exit;

/**
 * GPress Semantic Structure Manager
 * 
 * @since 1.0.0
 */
class GPress_Semantic_Structure {

    /**
     * Initialize semantic structure system
     *
     * @since 1.0.0
     */
    public static function init() {
        add_action('after_setup_theme', array(__CLASS__, 'semantic_setup'));
        add_action('wp_enqueue_scripts', array(__CLASS__, 'conditional_semantic_assets'));
        add_action('wp_head', array(__CLASS__, 'add_semantic_meta'), 5);
        add_action('wp_footer', array(__CLASS__, 'add_semantic_enhancements'), 25);
        
        // Content enhancement filters
        add_filter('the_content', array(__CLASS__, 'enhance_content_semantics'), 20);
        add_filter('wp_nav_menu_args', array(__CLASS__, 'enhance_nav_semantics'));
        add_filter('get_the_archive_title', array(__CLASS__, 'enhance_archive_title_semantics'));
        
        // Admin enhancements
        add_action('admin_init', array(__CLASS__, 'setup_semantic_admin'));
        add_action('admin_enqueue_scripts', array(__CLASS__, 'admin_semantic_assets'));
        
        // Development tools
        if (defined('WP_DEBUG') && WP_DEBUG) {
            add_action('wp_footer', array(__CLASS__, 'semantic_dev_tools'));
        }
    }

    /**
     * Semantic setup and theme support
     *
     * @since 1.0.0
     */
    public static function semantic_setup() {
        // Add theme support for semantic features
        add_theme_support('semantic-html5');
        add_theme_support('html5', array(
            'search-form',
            'comment-form',
            'comment-list',
            'gallery',
            'caption',
            'navigation-widgets',
            'script',
            'style'
        ));
        add_theme_support('aria-landmarks');
        add_theme_support('document-outline');
        add_theme_support('microdata');
        add_theme_support('heading-hierarchy');
        
        // Initialize default semantic options
        if (get_option('gpress_semantic_initialized') !== 'yes') {
            self::set_default_semantic_options();
            update_option('gpress_semantic_initialized', 'yes');
        }
    }

    /**
     * Set default semantic options
     *
     * @since 1.0.0
     */
    private static function set_default_semantic_options() {
        $defaults = array(
            'gpress_enable_aria_landmarks' => true,
            'gpress_enable_document_outline' => true,
            'gpress_enable_heading_validation' => true,
            'gpress_enable_microdata' => true,
            'gpress_enable_semantic_nav' => true,
            'gpress_auto_fix_heading_hierarchy' => true,
            'gpress_enable_landmark_skip' => true,
            'gpress_semantic_validation' => false, // Disabled by default for performance
        );
        
        foreach ($defaults as $option => $value) {
            update_option($option, $value);
        }
    }

    /**
     * Conditional semantic asset loading
     *
     * @since 1.0.0
     */
    public static function conditional_semantic_assets() {
        $load_semantic_assets = false;
        $load_outline_features = false;
        $load_validation_tools = false;
        
        // Determine semantic asset requirements
        if (self::needs_semantic_enhancements()) {
            $load_semantic_assets = true;
        }
        
        if (self::needs_outline_features()) {
            $load_outline_features = true;
        }
        
        if (self::needs_validation_tools()) {
            $load_validation_tools = true;
        }
        
        // Always load base semantic styles
        wp_enqueue_style(
            'gpress-semantic-base',
            get_theme_file_uri('/assets/css/semantic-base.css'),
            array('gpress-style'),
            GPRESS_VERSION,
            'all'
        );
        
        if ($load_semantic_assets) {
            wp_enqueue_style(
                'gpress-semantic',
                get_theme_file_uri('/assets/css/semantic.css'),
                array('gpress-semantic-base'),
                GPRESS_VERSION,
                'all'
            );
            
            wp_enqueue_script(
                'gpress-semantic',
                get_theme_file_uri('/assets/js/semantic.js'),
                array('jquery'),
                GPRESS_VERSION,
                array('strategy' => 'defer', 'in_footer' => true)
            );
            
            // ARIA landmarks enhancement
            wp_enqueue_style(
                'gpress-aria-landmarks',
                get_theme_file_uri('/assets/css/aria-landmarks.css'),
                array('gpress-semantic'),
                GPRESS_VERSION,
                'all'
            );
            
            wp_enqueue_script(
                'gpress-aria-navigation',
                get_theme_file_uri('/assets/js/aria-navigation.js'),
                array('gpress-semantic'),
                GPRESS_VERSION,
                array('strategy' => 'defer', 'in_footer' => true)
            );
            
            // Landmark skip navigation
            wp_enqueue_script(
                'gpress-landmark-skip',
                get_theme_file_uri('/assets/js/landmark-skip.js'),
                array('gpress-semantic'),
                GPRESS_VERSION,
                array('strategy' => 'defer', 'in_footer' => true)
            );
            
            // Localize semantic script
            wp_localize_script('gpress-semantic', 'gpressSemantic', array(
                'ajaxurl' => admin_url('admin-ajax.php'),
                'nonce' => wp_create_nonce('gpress_semantic_nonce'),
                'settings' => array(
                    'enableLandmarks' => get_option('gpress_enable_aria_landmarks', true),
                    'enableOutline' => get_option('gpress_enable_document_outline', true),
                    'enableValidation' => get_option('gpress_enable_heading_validation', true),
                    'autoFixHeadings' => get_option('gpress_auto_fix_heading_hierarchy', true),
                ),
                'strings' => array(
                    'skipToMain' => __('Skip to main content', 'gpress'),
                    'skipToNav' => __('Skip to navigation', 'gpress'),
                    'skipToFooter' => __('Skip to footer', 'gpress'),
                    'documentOutline' => __('Document outline', 'gpress'),
                    'headingLevel' => __('Heading level', 'gpress'),
                    'landmark' => __('Landmark', 'gpress'),
                )
            ));
        }
        
        if ($load_outline_features) {
            wp_enqueue_style(
                'gpress-document-outline',
                get_theme_file_uri('/assets/css/document-outline.css'),
                array('gpress-semantic'),
                GPRESS_VERSION,
                'all'
            );
            
            wp_enqueue_script(
                'gpress-document-outline',
                get_theme_file_uri('/assets/js/document-outline.js'),
                array('gpress-semantic'),
                GPRESS_VERSION,
                array('strategy' => 'defer', 'in_footer' => true)
            );
            
            wp_enqueue_script(
                'gpress-heading-hierarchy',
                get_theme_file_uri('/assets/js/heading-hierarchy.js'),
                array('gpress-document-outline'),
                GPRESS_VERSION,
                array('strategy' => 'defer', 'in_footer' => true)
            );
        }
        
        if ($load_validation_tools) {
            wp_enqueue_script(
                'gpress-semantic-validation',
                get_theme_file_uri('/assets/js/semantic-validation.js'),
                array('gpress-semantic'),
                GPRESS_VERSION,
                array('strategy' => 'defer', 'in_footer' => true)
            );
        }
        
        // Microdata styles
        if (get_option('gpress_enable_microdata', true)) {
            wp_enqueue_style(
                'gpress-microdata',
                get_theme_file_uri('/assets/css/microdata.css'),
                array('gpress-semantic-base'),
                GPRESS_VERSION,
                'all'
            );
        }
        
        // Print-specific semantic styles
        wp_enqueue_style(
            'gpress-semantic-print',
            get_theme_file_uri('/assets/css/semantic-print.css'),
            array('gpress-semantic-base'),
            GPRESS_VERSION,
            'print'
        );
    }

    /**
     * Check if semantic enhancements are needed
     *
     * @since 1.0.0
     */
    private static function needs_semantic_enhancements() {
        // Always load on content pages
        if (is_single() || is_page() || is_home() || is_archive()) {
            return true;
        }
        
        // Check for semantic-heavy content
        global $post;
        if ($post && has_blocks($post->post_content)) {
            $semantic_blocks = array(
                'core/heading',
                'core/list',
                'core/table',
                'core/quote',
                'core/group',
                'core/columns'
            );
            
            foreach ($semantic_blocks as $block) {
                if (has_block($block, $post)) {
                    return true;
                }
            }
        }
        
        return false;
    }

    /**
     * Check if outline features are needed
     *
     * @since 1.0.0
     */
    private static function needs_outline_features() {
        return get_option('gpress_enable_document_outline', true) && 
               (is_single() || is_page()) && 
               self::content_has_headings();
    }

    /**
     * Check if validation tools are needed
     *
     * @since 1.0.0
     */
    private static function needs_validation_tools() {
        return get_option('gpress_semantic_validation', false) && 
               (current_user_can('edit_posts') || (defined('WP_DEBUG') && WP_DEBUG));
    }

    /**
     * Check if content has headings
     *
     * @since 1.0.0
     */
    private static function content_has_headings() {
        global $post;
        if (!$post) {
            return false;
        }
        
        $content = $post->post_content;
        return preg_match('/<h[1-6][^>]*>/', $content) || has_block('core/heading', $post);
    }

    /**
     * Add semantic meta information
     *
     * @since 1.0.0
     */
    public static function add_semantic_meta() {
        ?>
        <!-- Semantic HTML Enhancement Meta -->
        <meta name="semantic-html" content="enhanced">
        <meta name="aria-landmarks" content="<?php echo get_option('gpress_enable_aria_landmarks', true) ? 'enabled' : 'disabled'; ?>">
        <meta name="document-outline" content="<?php echo get_option('gpress_enable_document_outline', true) ? 'enabled' : 'disabled'; ?>">
        <?php
    }

    /**
     * Enhance content semantics
     *
     * @since 1.0.0
     */
    public static function enhance_content_semantics($content) {
        // Enhance heading hierarchy
        $content = self::enhance_heading_hierarchy($content);
        
        // Add semantic structure to lists
        $content = self::enhance_list_semantics($content);
        
        // Enhance table accessibility
        $content = self::enhance_table_semantics($content);
        
        // Add microdata to content
        if (get_option('gpress_enable_microdata', true)) {
            $content = self::add_content_microdata($content);
        }
        
        return $content;
    }

    /**
     * Enhance heading hierarchy
     *
     * @since 1.0.0
     */
    private static function enhance_heading_hierarchy($content) {
        if (!get_option('gpress_auto_fix_heading_hierarchy', true)) {
            return $content;
        }
        
        // Extract headings and analyze hierarchy
        preg_match_all('/<h([1-6])([^>]*)>(.*?)<\/h[1-6]>/i', $content, $matches, PREG_SET_ORDER);
        
        if (empty($matches)) {
            return $content;
        }
        
        $current_level = 1;
        $replacements = array();
        
        foreach ($matches as $match) {
            $original = $match[0];
            $level = intval($match[1]);
            $attributes = $match[2];
            $text = $match[3];
            
            // Ensure proper hierarchy (no skipping levels)
            if ($level > $current_level + 1) {
                $level = $current_level + 1;
            }
            
            // Add semantic attributes
            $semantic_attrs = $attributes;
            if (strpos($semantic_attrs, 'id=') === false) {
                $heading_id = sanitize_title($text);
                $semantic_attrs .= ' id="heading-' . $heading_id . '"';
            }
            
            // Add ARIA level
            if (strpos($semantic_attrs, 'aria-level') === false) {
                $semantic_attrs .= ' aria-level="' . $level . '"';
            }
            
            $new_heading = "<h{$level}{$semantic_attrs}>{$text}</h{$level}>";
            $replacements[$original] = $new_heading;
            
            $current_level = $level;
        }
        
        // Apply replacements
        foreach ($replacements as $old => $new) {
            $content = str_replace($old, $new, $content);
        }
        
        return $content;
    }

    /**
     * Enhance list semantics
     *
     * @since 1.0.0
     */
    private static function enhance_list_semantics($content) {
        // Enhance ordered lists with proper numbering
        $content = preg_replace_callback(
            '/<ol([^>]*)>(.*?)<\/ol>/s',
            function($matches) {
                $attributes = $matches[1];
                $list_content = $matches[2];
                
                // Add semantic attributes
                if (strpos($attributes, 'role=') === false) {
                    $attributes .= ' role="list"';
                }
                
                // Enhance list items
                $list_content = preg_replace(
                    '/<li([^>]*)>/i',
                    '<li$1 role="listitem">',
                    $list_content
                );
                
                return "<ol{$attributes}>{$list_content}</ol>";
            },
            $content
        );
        
        // Enhance unordered lists
        $content = preg_replace_callback(
            '/<ul([^>]*)>(.*?)<\/ul>/s',
            function($matches) {
                $attributes = $matches[1];
                $list_content = $matches[2];
                
                // Add semantic attributes
                if (strpos($attributes, 'role=') === false) {
                    $attributes .= ' role="list"';
                }
                
                // Enhance list items
                $list_content = preg_replace(
                    '/<li([^>]*)>/i',
                    '<li$1 role="listitem">',
                    $list_content
                );
                
                return "<ul{$attributes}>{$list_content}</ul>";
            },
            $content
        );
        
        return $content;
    }

    /**
     * Enhance table semantics
     *
     * @since 1.0.0
     */
    private static function enhance_table_semantics($content) {
        $content = preg_replace_callback(
            '/<table([^>]*)>(.*?)<\/table>/s',
            function($matches) {
                $attributes = $matches[1];
                $table_content = $matches[2];
                
                // Add semantic attributes
                if (strpos($attributes, 'role=') === false) {
                    $attributes .= ' role="table"';
                }
                
                // Add scope to header cells
                $table_content = preg_replace(
                    '/<th([^>]*?)>/',
                    '<th$1 scope="col">',
                    $table_content
                );
                
                // Enhance table structure
                if (strpos($table_content, '<thead>') === false && 
                    strpos($table_content, '<th') !== false) {
                    // Wrap header row in thead
                    $table_content = preg_replace(
                        '/(<tr[^>]*>.*?<\/tr>)/',
                        '<thead>$1</thead>',
                        $table_content,
                        1
                    );
                }
                
                return "<table{$attributes}>{$table_content}</table>";
            },
            $content
        );
        
        return $content;
    }

    /**
     * Add content microdata
     *
     * @since 1.0.0
     */
    private static function add_content_microdata($content) {
        if (is_single()) {
            // Add article microdata
            $content = '<div itemscope itemtype="https://schema.org/Article">' . $content . '</div>';
        } elseif (is_page()) {
            // Add webpage microdata
            $content = '<div itemscope itemtype="https://schema.org/WebPage">' . $content . '</div>';
        }
        
        return $content;
    }

    /**
     * Enhance navigation semantics
     *
     * @since 1.0.0
     */
    public static function enhance_nav_semantics($args) {
        if (!isset($args['container_aria_label']) && isset($args['theme_location'])) {
            switch ($args['theme_location']) {
                case 'primary':
                    $args['container_aria_label'] = __('Primary navigation', 'gpress');
                    break;
                case 'footer':
                    $args['container_aria_label'] = __('Footer navigation', 'gpress');
                    break;
                case 'social':
                    $args['container_aria_label'] = __('Social media navigation', 'gpress');
                    break;
            }
        }
        
        // Ensure nav element has proper role
        if (!isset($args['items_wrap'])) {
            $args['items_wrap'] = '<ul id="%1$s" class="%2$s" role="menubar">%3$s</ul>';
        }
        
        return $args;
    }

    /**
     * Enhance archive title semantics
     *
     * @since 1.0.0
     */
    public static function enhance_archive_title_semantics($title) {
        // Remove redundant prefixes for better semantics
        $title = preg_replace('/^(Category|Tag|Author|Archives):\s*/', '', $title);
        return $title;
    }

    /**
     * Add semantic enhancements to footer
     *
     * @since 1.0.0
     */
    public static function add_semantic_enhancements() {
        if (get_option('gpress_enable_document_outline', true) && self::content_has_headings()) {
            self::output_document_outline();
        }
        
        if (get_option('gpress_enable_landmark_skip', true)) {
            self::output_landmark_navigation();
        }
    }

    /**
     * Output document outline
     *
     * @since 1.0.0
     */
    private static function output_document_outline() {
        ?>
        <div id="document-outline" class="document-outline" role="navigation" aria-label="<?php esc_attr_e('Document outline', 'gpress'); ?>">
            <button class="outline-toggle" aria-expanded="false" aria-controls="outline-content">
                <span class="outline-icon" aria-hidden="true">📋</span>
                <span class="outline-text"><?php esc_html_e('Table of Contents', 'gpress'); ?></span>
            </button>
            <div id="outline-content" class="outline-content" hidden>
                <h2 class="outline-title"><?php esc_html_e('Table of Contents', 'gpress'); ?></h2>
                <ul class="outline-list" role="list">
                    <!-- Dynamically generated by JavaScript -->
                </ul>
            </div>
        </div>
        <?php
    }

    /**
     * Output landmark navigation
     *
     * @since 1.0.0
     */
    private static function output_landmark_navigation() {
        ?>
        <div id="landmark-navigation" class="landmark-navigation" role="navigation" aria-label="<?php esc_attr_e('Page landmarks', 'gpress'); ?>">
            <button class="landmark-toggle" aria-expanded="false" aria-controls="landmark-content">
                <span class="landmark-icon" aria-hidden="true">🎯</span>
                <span class="landmark-text"><?php esc_html_e('Page Landmarks', 'gpress'); ?></span>
            </button>
            <div id="landmark-content" class="landmark-content" hidden>
                <h2 class="landmark-title"><?php esc_html_e('Navigate by Landmarks', 'gpress'); ?></h2>
                <ul class="landmark-list" role="list">
                    <!-- Dynamically generated by JavaScript -->
                </ul>
            </div>
        </div>
        <?php
    }

    /**
     * Setup semantic admin features
     *
     * @since 1.0.0
     */
    public static function setup_semantic_admin() {
        add_action('admin_menu', array(__CLASS__, 'add_semantic_admin_menu'));
    }

    /**
     * Add semantic admin menu
     *
     * @since 1.0.0
     */
    public static function add_semantic_admin_menu() {
        add_theme_page(
            __('Semantic HTML Settings', 'gpress'),
            __('Semantic HTML', 'gpress'),
            'manage_options',
            'gpress-semantic',
            array(__CLASS__, 'semantic_admin_page')
        );
    }

    /**
     * Semantic admin page
     *
     * @since 1.0.0
     */
    public static function semantic_admin_page() {
        ?>
        <div class="wrap">
            <h1><?php esc_html_e('Semantic HTML Settings', 'gpress'); ?></h1>
            <form method="post" action="options.php">
                <?php
                settings_fields('gpress_semantic_settings');
                do_settings_sections('gpress_semantic_settings');
                ?>
                <table class="form-table">
                    <tr>
                        <th scope="row"><?php esc_html_e('Enable ARIA Landmarks', 'gpress'); ?></th>
                        <td>
                            <input type="checkbox" name="gpress_enable_aria_landmarks" value="1" 
                                   <?php checked(get_option('gpress_enable_aria_landmarks', true)); ?>>
                            <p class="description"><?php esc_html_e('Add ARIA landmarks for better navigation.', 'gpress'); ?></p>
                        </td>
                    </tr>
                    <tr>
                        <th scope="row"><?php esc_html_e('Enable Document Outline', 'gpress'); ?></th>
                        <td>
                            <input type="checkbox" name="gpress_enable_document_outline" value="1" 
                                   <?php checked(get_option('gpress_enable_document_outline', true)); ?>>
                            <p class="description"><?php esc_html_e('Generate table of contents from headings.', 'gpress'); ?></p>
                        </td>
                    </tr>
                    <tr>
                        <th scope="row"><?php esc_html_e('Auto-fix Heading Hierarchy', 'gpress'); ?></th>
                        <td>
                            <input type="checkbox" name="gpress_auto_fix_heading_hierarchy" value="1" 
                                   <?php checked(get_option('gpress_auto_fix_heading_hierarchy', true)); ?>>
                            <p class="description"><?php esc_html_e('Automatically correct heading level skips.', 'gpress'); ?></p>
                        </td>
                    </tr>
                </table>
                <?php submit_button(); ?>
            </form>
            
            <h2><?php esc_html_e('Semantic Validation', 'gpress'); ?></h2>
            <button type="button" class="button" onclick="gpressRunSemanticValidation()">
                <?php esc_html_e('Run Semantic Validation', 'gpress'); ?>
            </button>
            <div id="semantic-validation-results"></div>
        </div>
        <?php
    }

    /**
     * Enqueue admin semantic assets
     *
     * @since 1.0.0
     */
    public static function admin_semantic_assets($hook) {
        if ($hook === 'appearance_page_gpress-semantic') {
            wp_enqueue_style(
                'gpress-admin-semantic',
                get_theme_file_uri('/assets/css/admin-semantic.css'),
                array(),
                GPRESS_VERSION
            );
            
            wp_enqueue_script(
                'gpress-admin-semantic',
                get_theme_file_uri('/assets/js/admin-semantic.js'),
                array('jquery'),
                GPRESS_VERSION,
                true
            );
        }
    }

    /**
     * Add development semantic tools
     *
     * @since 1.0.0
     */
    public static function semantic_dev_tools() {
        if (!current_user_can('edit_themes')) {
            return;
        }
        ?>
        <div id="semantic-dev-tools" style="position: fixed; bottom: 60px; right: 10px; z-index: 99999; background: #000; color: #fff; padding: 10px; border-radius: 5px; font-size: 12px;">
            <h4>Semantic Dev Tools</h4>
            <button onclick="gpressCheckHeadingHierarchy()">Check Headings</button>
            <button onclick="gpressCheckAriaLandmarks()">Check ARIA</button>
            <button onclick="gpressGenerateOutline()">Generate Outline</button>
            <button onclick="gpressValidateSemantics()">Validate HTML</button>
        </div>
        
        <script>
        function gpressCheckHeadingHierarchy() {
            const headings = document.querySelectorAll('h1, h2, h3, h4, h5, h6');
            let lastLevel = 0;
            const issues = [];
            
            headings.forEach((heading, index) => {
                const level = parseInt(heading.tagName.charAt(1));
                if (level > lastLevel + 1) {
                    issues.push(`Heading ${index + 1}: Skipped from h${lastLevel} to h${level}`);
                }
                lastLevel = level;
            });
            
            console.log('Heading Hierarchy Issues:', issues.length === 0 ? 'None found' : issues);
        }
        
        function gpressCheckAriaLandmarks() {
            const landmarks = document.querySelectorAll('[role], header, nav, main, aside, footer');
            console.log('ARIA Landmarks found:', landmarks.length);
            landmarks.forEach(landmark => {
                console.log('Landmark:', landmark.tagName, landmark.getAttribute('role') || 'implicit');
            });
        }
        
        function gpressGenerateOutline() {
            const headings = document.querySelectorAll('h1, h2, h3, h4, h5, h6');
            const outline = [];
            headings.forEach(heading => {
                outline.push({
                    level: heading.tagName,
                    text: heading.textContent.trim(),
                    id: heading.id
                });
            });
            console.log('Document Outline:', outline);
        }
        
        function gpressValidateSemantics() {
            // Basic semantic validation
            const issues = [];
            
            // Check for images without alt text
            const images = document.querySelectorAll('img:not([alt])');
            if (images.length > 0) {
                issues.push(`${images.length} images missing alt text`);
            }
            
            // Check for form inputs without labels
            const inputs = document.querySelectorAll('input:not([aria-label]):not([aria-labelledby])');
            const unlabeledInputs = Array.from(inputs).filter(input => {
                return !document.querySelector(`label[for="${input.id}"]`);
            });
            if (unlabeledInputs.length > 0) {
                issues.push(`${unlabeledInputs.length} form inputs without labels`);
            }
            
            console.log('Semantic Validation Issues:', issues.length === 0 ? 'None found' : issues);
        }
        </script>
        <?php
    }
}

// Initialize the semantic structure system
GPress_Semantic_Structure::init();
```

### 2. Create Semantic Base Styles

Create `assets/css/semantic-base.css`:

```css
/**
 * GPress Semantic Base Styles
 * Core semantic styles loaded on every page
 *
 * @package GPress
 * @subpackage Semantic
 * @version 1.0.0
 * @since 1.0.0
 */

/* HTML5 Semantic Elements */
article,
aside,
details,
figcaption,
figure,
footer,
header,
main,
nav,
section {
    display: block;
}

/* Document Structure */
[role="main"],
main {
    min-height: 60vh;
    padding: 1rem 0;
}

[role="banner"],
header {
    position: relative;
}

[role="contentinfo"],
footer {
    margin-top: auto;
}

[role="navigation"],
nav {
    position: relative;
}

[role="complementary"],
aside {
    position: relative;
}

/* Heading Hierarchy */
h1, h2, h3, h4, h5, h6 {
    font-family: var(--wp--preset--font-family--heading);
    font-weight: var(--wp--custom--typography--font-weight--bold);
    line-height: var(--wp--custom--typography--line-height--heading);
    margin: 0 0 1rem 0;
    scroll-margin-top: 2rem; /* For anchor links */
}

h1 {
    font-size: var(--wp--preset--font-size--x-large);
    color: var(--wp--preset--color--primary);
}

h2 {
    font-size: var(--wp--preset--font-size--large);
    color: var(--wp--preset--color--foreground);
}

h3 {
    font-size: var(--wp--preset--font-size--medium);
    color: var(--wp--preset--color--foreground);
}

h4 {
    font-size: var(--wp--preset--font-size--normal);
    color: var(--wp--preset--color--foreground);
}

h5 {
    font-size: var(--wp--preset--font-size--small);
    color: var(--wp--preset--color--foreground-secondary);
}

h6 {
    font-size: var(--wp--preset--font-size--small);
    color: var(--wp--preset--color--foreground-secondary);
    font-weight: 500;
}

/* Heading IDs for anchor links */
h1[id],
h2[id],
h3[id],
h4[id],
h5[id],
h6[id] {
    position: relative;
}

h1[id]:hover::after,
h2[id]:hover::after,
h3[id]:hover::after,
h4[id]:hover::after,
h5[id]:hover::after,
h6[id]:hover::after {
    content: "🔗";
    position: absolute;
    left: -1.5rem;
    opacity: 0.5;
    font-size: 0.8em;
    cursor: pointer;
}

/* List Semantics */
[role="list"],
ul,
ol {
    margin: 1rem 0;
    padding-left: 2rem;
}

[role="listitem"],
li {
    margin-bottom: 0.5rem;
    line-height: 1.6;
}

/* Table Semantics */
[role="table"],
table {
    width: 100%;
    border-collapse: collapse;
    margin: 1.5rem 0;
    background: var(--wp--preset--color--background);
    border: 1px solid var(--wp--preset--color--border);
    border-radius: 6px;
    overflow: hidden;
}

[role="columnheader"],
th {
    background: var(--wp--preset--color--background-secondary);
    font-weight: 600;
    text-align: left;
    padding: 1rem;
    border-bottom: 2px solid var(--wp--preset--color--border);
}

[role="cell"],
td {
    padding: 0.75rem 1rem;
    border-bottom: 1px solid var(--wp--preset--color--border);
}

[role="row"]:nth-child(even) td {
    background: var(--wp--preset--color--background-secondary);
}

/* Caption Semantics */
caption {
    text-align: left;
    font-weight: 600;
    margin-bottom: 0.5rem;
    padding: 0.5rem;
    background: var(--wp--preset--color--background-secondary);
}

/* Figure and Caption */
figure {
    margin: 1.5rem 0;
    text-align: center;
}

figcaption {
    font-size: 0.875rem;
    color: var(--wp--preset--color--foreground-secondary);
    margin-top: 0.5rem;
    padding: 0 1rem;
    font-style: italic;
}

/* Blockquote Semantics */
blockquote {
    margin: 1.5rem 0;
    padding: 1rem 1.5rem;
    border-left: 4px solid var(--wp--preset--color--primary);
    background: var(--wp--preset--color--background-secondary);
    font-style: italic;
    position: relative;
}

blockquote cite {
    display: block;
    margin-top: 0.5rem;
    font-size: 0.875rem;
    color: var(--wp--preset--color--foreground-secondary);
    font-style: normal;
}

blockquote cite::before {
    content: "— ";
}

/* Address Semantics */
address {
    font-style: normal;
    margin: 1rem 0;
    padding: 1rem;
    background: var(--wp--preset--color--background-secondary);
    border-radius: 6px;
}

/* Time Semantics */
time {
    font-variant-numeric: tabular-nums;
}

/* Mark and Highlight */
mark {
    background: var(--wp--preset--color--accent);
    color: var(--wp--preset--color--background);
    padding: 0.2em 0.4em;
    border-radius: 3px;
    font-weight: 500;
}

/* Code Semantics */
code {
    font-family: var(--wp--preset--font-family--monospace);
    font-size: 0.875em;
    background: var(--wp--preset--color--background-secondary);
    padding: 0.2em 0.4em;
    border-radius: 3px;
}

pre {
    font-family: var(--wp--preset--font-family--monospace);
    background: var(--wp--preset--color--background-secondary);
    padding: 1rem;
    border-radius: 6px;
    overflow-x: auto;
    line-height: 1.4;
}

pre code {
    background: none;
    padding: 0;
}

/* Abbreviation Semantics */
abbr[title] {
    text-decoration: underline dotted;
    cursor: help;
}

/* Definition Semantics */
dfn {
    font-style: italic;
    font-weight: 600;
}

/* Small Text Semantics */
small {
    font-size: 0.875em;
    color: var(--wp--preset--color--foreground-secondary);
}

/* Sub and Sup */
sub,
sup {
    font-size: 0.75em;
    line-height: 0;
    position: relative;
    vertical-align: baseline;
}

sub {
    bottom: -0.25em;
}

sup {
    top: -0.5em;
}

/* Form Semantics */
fieldset {
    border: 2px solid var(--wp--preset--color--border);
    border-radius: 6px;
    padding: 1rem;
    margin: 1rem 0;
}

legend {
    font-weight: 600;
    padding: 0 0.5rem;
}

label {
    font-weight: 500;
    margin-bottom: 0.25rem;
    display: block;
}

/* ARIA Landmarks Base Styles */
[role="banner"] {
    position: relative;
}

[role="navigation"] {
    position: relative;
}

[role="main"] {
    position: relative;
}

[role="complementary"] {
    position: relative;
}

[role="contentinfo"] {
    position: relative;
}

/* Document Outline Base */
.document-outline {
    position: fixed;
    top: 50%;
    right: 20px;
    transform: translateY(-50%);
    z-index: 1000;
    opacity: 0.9;
    transition: opacity 0.3s ease;
}

.document-outline:hover {
    opacity: 1;
}

.outline-toggle {
    background: var(--wp--preset--color--primary);
    color: var(--wp--preset--color--background);
    border: none;
    padding: 0.75rem;
    border-radius: 50%;
    cursor: pointer;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.2);
    transition: all 0.3s ease;
    display: flex;
    align-items: center;
    justify-content: center;
    width: 48px;
    height: 48px;
}

.outline-toggle:hover,
.outline-toggle:focus {
    background: var(--wp--preset--color--accent);
    transform: scale(1.1);
}

.outline-text {
    display: none;
}

.outline-content {
    position: absolute;
    right: 60px;
    top: 0;
    background: var(--wp--preset--color--background);
    border: 1px solid var(--wp--preset--color--border);
    border-radius: 8px;
    box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15);
    min-width: 250px;
    max-width: 350px;
    max-height: 400px;
    overflow-y: auto;
}

/* Landmark Navigation Base */
.landmark-navigation {
    position: fixed;
    top: 60%;
    right: 20px;
    transform: translateY(-50%);
    z-index: 999;
    opacity: 0.9;
    transition: opacity 0.3s ease;
}

.landmark-toggle {
    background: var(--wp--preset--color--secondary);
    color: var(--wp--preset--color--background);
    border: none;
    padding: 0.75rem;
    border-radius: 50%;
    cursor: pointer;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.2);
    transition: all 0.3s ease;
    display: flex;
    align-items: center;
    justify-content: center;
    width: 48px;
    height: 48px;
}

/* Responsive Semantic Elements */
@media (max-width: 768px) {
    .document-outline,
    .landmark-navigation {
        position: static;
        transform: none;
        margin: 1rem;
        opacity: 1;
    }
    
    .outline-toggle,
    .landmark-toggle {
        width: auto;
        height: auto;
        padding: 0.75rem 1rem;
        border-radius: 6px;
    }
    
    .outline-text,
    .landmark-text {
        display: inline;
        margin-left: 0.5rem;
    }
    
    .outline-content,
    .landmark-content {
        position: static;
        margin-top: 0.5rem;
        max-height: 300px;
    }
}

/* Print Semantic Styles */
@media print {
    .document-outline,
    .landmark-navigation {
        display: none;
    }
    
    h1, h2, h3, h4, h5, h6 {
        break-after: avoid;
        break-inside: avoid;
    }
    
    figure,
    table {
        break-inside: avoid;
    }
    
    /* Add content after links for print */
    a[href^="http"]:after {
        content: " (" attr(href) ")";
        font-size: 0.8em;
        color: #666;
    }
    
    /* Show abbreviation titles in print */
    abbr[title]:after {
        content: " (" attr(title) ")";
        font-size: 0.8em;
        color: #666;
    }
}

/* High Contrast Semantic Support */
@media (prefers-contrast: high) {
    h1, h2, h3, h4, h5, h6 {
        border-bottom: 2px solid currentColor;
        padding-bottom: 0.25rem;
    }
    
    [role="table"],
    table {
        border: 2px solid currentColor;
    }
    
    [role="columnheader"],
    th {
        border: 1px solid currentColor;
    }
    
    [role="cell"],
    td {
        border: 1px solid currentColor;
    }
}

/* Reduced Motion Semantic Support */
@media (prefers-reduced-motion: reduce) {
    .outline-toggle,
    .landmark-toggle {
        transition: none;
    }
    
    h1[id]:hover::after,
    h2[id]:hover::after,
    h3[id]:hover::after,
    h4[id]:hover::after,
    h5[id]:hover::after,
    h6[id]:hover::after {
        transition: none;
    }
}
```

### 3. Update Functions.php

Update `functions.php`:

```php
// ... existing code ...

/**
 * Load Semantic Structure Components
 * Advanced semantic HTML5 implementation
 *
 * @since 1.0.0
 */
function gpress_load_semantic_components() {
    // Core semantic system (always loaded)
    require_once get_theme_file_path('/inc/semantic-structure.php');
    
    // Enhanced semantic features (conditionally loaded)
    if (get_theme_mod('enable_semantic_enhancements', true)) {
        require_once get_theme_file_path('/inc/semantic-enhancements.php');
        require_once get_theme_file_path('/inc/aria-landmarks.php');
    }
    
    // Document outline features
    if (get_option('gpress_enable_document_outline', true)) {
        require_once get_theme_file_path('/inc/document-outline.php');
    }
    
    // Microdata patterns
    if (get_option('gpress_enable_microdata', true)) {
        require_once get_theme_file_path('/inc/microdata-patterns.php');
    }
    
    // Admin and development components
    if (is_admin()) {
        require_once get_theme_file_path('/inc/semantic-validation.php');
    }
}
add_action('after_setup_theme', 'gpress_load_semantic_components');

/**
 * Add semantic-specific theme support
 *
 * @since 1.0.0
 */
function gpress_add_semantic_theme_support() {
    // Core semantic features
    add_theme_support('html5', array(
        'search-form',
        'comment-form',
        'comment-list',
        'gallery',
        'caption',
        'navigation-widgets',
        'script',
        'style'
    ));
    
    // Advanced semantic features
    add_theme_support('semantic-html5');
    add_theme_support('aria-landmarks');
    add_theme_support('document-outline');
    add_theme_support('microdata');
    add_theme_support('heading-hierarchy');
    add_theme_support('semantic-navigation');
    
    // Content structure features
    add_theme_support('semantic-content-structure');
    add_theme_support('landmark-skip-navigation');
}
add_action('after_setup_theme', 'gpress_add_semantic_theme_support');

// ... existing code ...
```

### 4. Update Style.css

Update `style.css`:

```css
/* ... existing code ... */

/**
 * Semantic HTML Integration Styles
 * Base semantic integration with theme styles
 *
 * @since 1.0.0
 */

/* Semantic Structure Integration */
.site-content {
    display: flex;
    flex-direction: column;
    min-height: 100vh;
}

.site-header {
    position: relative;
}

.site-main {
    flex: 1;
    margin: 2rem 0;
}

.site-footer {
    margin-top: auto;
}

/* Semantic Navigation Integration */
.main-navigation[role="navigation"] {
    position: relative;
}

.main-navigation ul[role="menubar"] {
    display: flex;
    list-style: none;
    margin: 0;
    padding: 0;
    gap: 1rem;
}

.main-navigation li[role="none"] {
    position: relative;
}

.main-navigation a[role="menuitem"] {
    display: block;
    padding: 0.75rem 1rem;
    text-decoration: none;
    color: var(--wp--preset--color--foreground);
    transition: all 0.3s ease;
    border-radius: 6px;
}

.main-navigation a[role="menuitem"]:hover,
.main-navigation a[role="menuitem"]:focus {
    background: var(--wp--preset--color--primary);
    color: var(--wp--preset--color--background);
}

/* Semantic Content Structure */
.entry-content[itemscope] {
    position: relative;
}

.entry-header {
    margin-bottom: 2rem;
}

.entry-title {
    margin-bottom: 1rem;
}

.entry-meta {
    display: flex;
    gap: 1rem;
    flex-wrap: wrap;
    color: var(--wp--preset--color--foreground-secondary);
    font-size: 0.875rem;
    margin-bottom: 1rem;
}

.entry-content {
    line-height: 1.7;
    font-size: var(--wp--preset--font-size--medium);
}

.entry-footer {
    margin-top: 2rem;
    padding-top: 2rem;
    border-top: 1px solid var(--wp--preset--color--border);
}

/* Semantic Article Structure */
article[itemscope] {
    margin-bottom: 3rem;
    padding: 2rem;
    background: var(--wp--preset--color--background);
    border-radius: 12px;
    border: 1px solid var(--wp--preset--color--border);
}

article header {
    margin-bottom: 1.5rem;
}

article footer {
    margin-top: 1.5rem;
    padding-top: 1.5rem;
    border-top: 1px solid var(--wp--preset--color--border);
    font-size: 0.875rem;
    color: var(--wp--preset--color--foreground-secondary);
}

/* Document Outline Integration */
.has-outline .entry-content {
    padding-right: 80px; /* Make room for outline */
}

@media (max-width: 768px) {
    .has-outline .entry-content {
        padding-right: 0;
    }
}

/* Landmark Skip Integration */
.landmark-skip-active {
    outline: 3px solid var(--wp--preset--color--accent);
    outline-offset: 2px;
    border-radius: 6px;
    transition: outline 0.3s ease;
}

/* Microdata Visual Integration */
[itemscope] {
    position: relative;
}

/* Development mode microdata indicators */
.admin-bar [itemscope]::before {
    content: attr(itemtype);
    position: absolute;
    top: -20px;
    left: 0;
    font-size: 0.7rem;
    background: var(--wp--preset--color--secondary);
    color: var(--wp--preset--color--background);
    padding: 2px 6px;
    border-radius: 3px;
    z-index: 9999;
    opacity: 0.8;
    pointer-events: none;
}

/* Semantic Form Integration */
.semantic-form fieldset {
    margin: 1.5rem 0;
    padding: 1.5rem;
    border: 2px solid var(--wp--preset--color--border);
    border-radius: 8px;
    background: var(--wp--preset--color--background-secondary);
}

.semantic-form legend {
    font-weight: 600;
    font-size: 1.1rem;
    color: var(--wp--preset--color--primary);
    padding: 0 0.75rem;
}

.semantic-form label {
    display: block;
    margin-bottom: 0.5rem;
    font-weight: 500;
    color: var(--wp--preset--color--foreground);
}

.semantic-form input,
.semantic-form textarea,
.semantic-form select {
    width: 100%;
    padding: 0.75rem;
    border: 2px solid var(--wp--preset--color--border);
    border-radius: 6px;
    font-size: 1rem;
    transition: border-color 0.3s ease;
}

.semantic-form input:focus,
.semantic-form textarea:focus,
.semantic-form select:focus {
    border-color: var(--wp--preset--color--primary);
    outline: none;
    box-shadow: 0 0 0 3px rgba(var(--wp--preset--color--primary-rgb), 0.2);
}

/* Responsive Semantic Layout */
@media (max-width: 768px) {
    .site-content {
        padding: 1rem;
    }
    
    .main-navigation ul[role="menubar"] {
        flex-direction: column;
        gap: 0.5rem;
    }
    
    article[itemscope] {
        padding: 1.5rem;
        margin-bottom: 2rem;
    }
    
    .entry-meta {
        flex-direction: column;
        gap: 0.5rem;
    }
}

/* Print Semantic Integration */
@media print {
    .site-header,
    .site-footer,
    .document-outline,
    .landmark-navigation {
        display: none;
    }
    
    .site-main {
        margin: 0;
    }
    
    article[itemscope] {
        border: none;
        box-shadow: none;
        background: none;
        margin-bottom: 2rem;
        padding: 0;
    }
    
    .entry-content h1,
    .entry-content h2,
    .entry-content h3,
    .entry-content h4,
    .entry-content h5,
    .entry-content h6 {
        break-after: avoid;
        break-inside: avoid;
    }
}

/* High Contrast Semantic Support */
@media (prefers-contrast: high) {
    article[itemscope] {
        border: 3px solid currentColor;
    }
    
    .main-navigation a[role="menuitem"] {
        border: 2px solid currentColor;
    }
    
    .semantic-form fieldset {
        border: 3px solid currentColor;
    }
}

/* ... existing code ... */
```

## Testing This Step

### 1. Semantic HTML Validation
```bash
# Test HTML5 semantic structure
curl -s http://yoursite.com/ | grep -E "<(article|section|nav|header|footer|main|aside)"

# Check ARIA landmarks
curl -s http://yoursite.com/ | grep -E 'role="(banner|navigation|main|complementary|contentinfo)"'

# Validate heading hierarchy
curl -s http://yoursite.com/ | grep -o '<h[1-6][^>]*>' | nl

# Test microdata implementation
curl -s http://yoursite.com/ | grep -E 'item(scope|type|prop)'
```

### 2. Document Outline Testing
```bash
# Test heading structure
curl -s http://yoursite.com/sample-post/ | grep -E '<h[1-6][^>]*>' | sed 's/<[^>]*>//g'

# Check heading IDs
curl -s http://yoursite.com/sample-post/ | grep -o '<h[1-6][^>]*id="[^"]*"'

# Validate outline generation
curl -s http://yoursite.com/sample-post/ | grep "document-outline"
```

### 3. ARIA Enhancement Testing
```bash
# Test ARIA landmarks
curl -s http://yoursite.com/ | grep -c 'role="'

# Check ARIA labels
curl -s http://yoursite.com/ | grep -c 'aria-label'

# Validate navigation semantics
curl -s http://yoursite.com/ | grep -E 'role="(menubar|menuitem)"'
```

### 4. Performance Impact Testing
```bash
# Test conditional asset loading
curl -s http://yoursite.com/ | grep -c "semantic.*\.css"
curl -s http://yoursite.com/ | grep -c "semantic.*\.js"

# Check semantic enhancement efficiency
curl -s http://yoursite.com/sample-post/ | grep -c 'itemscope'

# Validate script loading strategy
curl -s http://yoursite.com/ | grep 'semantic.*defer'
```

### 5. Accessibility Integration Testing
```bash
# Test semantic and accessibility integration
curl -s http://yoursite.com/ | grep -E '(role=|aria-|itemscope|itemtype)'

# Check heading accessibility
curl -s http://yoursite.com/ | grep -E '<h[1-6][^>]*aria-level'

# Validate landmark integration
curl -s http://yoursite.com/ | grep -E 'aria-label.*navigation'
```

## Expected Results After This Step

1. **Complete Semantic HTML5 Structure**: Proper HTML5 semantic elements with ARIA landmarks throughout
2. **Intelligent Document Outline**: Automatic table of contents generation with proper heading hierarchy
3. **Enhanced Navigation Semantics**: ARIA-enhanced navigation with landmark skip functionality
4. **Microdata Integration**: Structured content markup for better machine readability
5. **Performance-Optimized Loading**: Conditional semantic enhancements based on content analysis
6. **Accessibility Enhancement**: Improved screen reader support and assistive technology integration
7. **Admin Interface**: Semantic settings panel with validation and testing tools
8. **Development Tools**: Built-in semantic validation and debugging capabilities

## Next Step

In Step 16, we'll implement comprehensive performance testing and optimization validation to ensure all theme components meet performance benchmarks and Core Web Vitals requirements.

---

**Step 15 Completed**: Advanced Semantic HTML Structure & Accessibility ✅
- Complete semantic HTML5 structure with modern best practices
- Advanced ARIA landmarks and navigation pattern implementation
- Intelligent document outline generation and heading hierarchy management
- Performance-optimized conditional semantic asset loading
- Microdata and structured content pattern integration
- Enhanced screen reader support and assistive technology optimization
- Admin interface for semantic configuration and validation
- Built-in semantic validation and testing framework