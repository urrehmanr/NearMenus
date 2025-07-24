# Step 12: Navigation & Menus

## Objective
Implement advanced navigation and menu systems for the **GPress** theme with improved accessibility, mobile optimization, and modern UX patterns. Create multiple navigation areas, implement keyboard navigation, and ensure WCAG compliance with intelligent conditional asset loading.

## What You'll Learn
- Multiple navigation menu registration and management
- Conditional navigation asset loading for optimal performance
- Accessible navigation components with ARIA support
- Mobile-responsive navigation implementation
- Breadcrumb navigation with structured data
- Custom menu item fields and mega menu support
- Advanced keyboard navigation patterns

## Files to Create in This Step

```
assets/css/
├── navigation.css           # Advanced navigation styles
├── navigation.min.css       # Minified navigation styles
├── breadcrumbs.css         # Breadcrumb navigation styles
└── mobile-nav.css          # Mobile navigation specific styles

assets/js/
├── navigation.js           # Navigation functionality
├── navigation.min.js       # Minified navigation script
├── mobile-menu.js          # Mobile menu interactions
└── breadcrumbs.js          # Breadcrumb enhancements

inc/
├── navigation-system.php   # Navigation system management
├── navigation-walker.php   # Custom navigation walker
├── breadcrumbs.php        # Breadcrumb functionality
├── menu-customizer.php    # Menu customization features
└── navigation-assets.php   # Conditional navigation assets

parts/
├── navigation.html        # Main navigation template
├── mobile-menu.html      # Mobile menu template
├── breadcrumbs.html      # Breadcrumbs template
└── social-links.html     # Social navigation template
```

## 1. Create Navigation System Management

### File: `inc/navigation-system.php`
```php
<?php
/**
 * Navigation System Management for GPress Theme
 *
 * @package GPress
 * @version 1.0.0
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Initialize navigation system
 */
function gpress_init_navigation_system() {
    // Register navigation menus
    add_action('after_setup_theme', 'gpress_register_nav_menus');
    
    // Conditional navigation asset loading
    add_action('wp_enqueue_scripts', 'gpress_conditional_navigation_assets');
    
    // Enhance navigation with custom walker
    add_filter('wp_nav_menu_args', 'gpress_enhance_nav_menu_args');
    
    // Add menu item custom fields
    add_action('wp_nav_menu_item_custom_fields', 'gpress_add_menu_item_fields', 10, 2);
    add_action('wp_update_nav_menu_item', 'gpress_save_menu_item_fields', 10, 2);
    
    // Navigation body classes
    add_filter('body_class', 'gpress_navigation_body_classes');
    
    // Navigation enhancements
    add_action('wp_footer', 'gpress_navigation_accessibility_script');
}
add_action('after_setup_theme', 'gpress_init_navigation_system');

/**
 * Register navigation menus
 */
function gpress_register_nav_menus() {
    register_nav_menus(array(
        'primary'    => __('Primary Navigation', 'gpress'),
        'secondary'  => __('Secondary Navigation', 'gpress'),
        'footer'     => __('Footer Navigation', 'gpress'),
        'social'     => __('Social Media Links', 'gpress'),
        'mobile'     => __('Mobile Navigation', 'gpress'),
        'utility'    => __('Utility Menu', 'gpress')
    ));
}

/**
 * Conditional navigation asset loading
 */
function gpress_conditional_navigation_assets() {
    $load_navigation = false;
    $load_mobile = false;
    $load_breadcrumbs = false;
    
    // Check if navigation features are needed
    if (has_nav_menu('primary') || has_nav_menu('secondary')) {
        $load_navigation = true;
    }
    
    // Check for mobile menu
    if (wp_is_mobile() || has_nav_menu('mobile')) {
        $load_mobile = true;
    }
    
    // Check for breadcrumbs
    if (!is_front_page() && get_theme_mod('enable_breadcrumbs', true)) {
        $load_breadcrumbs = true;
    }
    
    // Always load basic navigation styles
    wp_enqueue_style(
        'gpress-navigation-base',
        get_theme_file_uri('/assets/css/navigation-base.css'),
        array('gpress-style'),
        GPRESS_VERSION
    );
    
    if ($load_navigation) {
        wp_enqueue_style(
            'gpress-navigation',
            get_theme_file_uri('/assets/css/navigation.css'),
            array('gpress-navigation-base'),
            GPRESS_VERSION
        );
        
        wp_enqueue_script(
            'gpress-navigation',
            get_theme_file_uri('/assets/js/navigation.js'),
            array('jquery'),
            GPRESS_VERSION,
            array('strategy' => 'defer', 'in_footer' => true)
        );
        
        // Localize navigation script
        wp_localize_script('gpress-navigation', 'gpressNav', array(
            'ajaxurl' => admin_url('admin-ajax.php'),
            'nonce' => wp_create_nonce('gpress_nav_nonce'),
            'strings' => array(
                'menu_toggle' => __('Toggle menu', 'gpress'),
                'submenu_toggle' => __('Toggle submenu', 'gpress'),
                'close_menu' => __('Close menu', 'gpress'),
                'open_menu' => __('Open menu', 'gpress'),
                'menu_expanded' => __('Menu expanded', 'gpress'),
                'menu_collapsed' => __('Menu collapsed', 'gpress')
            )
        ));
    }
    
    if ($load_mobile) {
        wp_enqueue_style(
            'gpress-mobile-nav',
            get_theme_file_uri('/assets/css/mobile-nav.css'),
            array('gpress-navigation'),
            GPRESS_VERSION
        );
        
        wp_enqueue_script(
            'gpress-mobile-menu',
            get_theme_file_uri('/assets/js/mobile-menu.js'),
            array('gpress-navigation'),
            GPRESS_VERSION,
            array('strategy' => 'defer', 'in_footer' => true)
        );
    }
    
    if ($load_breadcrumbs) {
        wp_enqueue_style(
            'gpress-breadcrumbs',
            get_theme_file_uri('/assets/css/breadcrumbs.css'),
            array('gpress-navigation-base'),
            GPRESS_VERSION
        );
        
        wp_enqueue_script(
            'gpress-breadcrumbs',
            get_theme_file_uri('/assets/js/breadcrumbs.js'),
            array('jquery'),
            GPRESS_VERSION,
            array('strategy' => 'defer', 'in_footer' => true)
        );
    }
}

/**
 * Enhance navigation menu args
 */
function gpress_enhance_nav_menu_args($args) {
    if (!isset($args['walker'])) {
        require_once get_theme_file_path('/inc/navigation-walker.php');
        $args['walker'] = new GPress_Walker_Nav_Menu();
    }
    
    // Add container classes based on menu location
    if (isset($args['theme_location'])) {
        $args['container_class'] = 'nav-container nav-' . $args['theme_location'];
        $args['menu_class'] = 'nav-menu nav-' . $args['theme_location'] . '-menu';
    }
    
    return $args;
}

/**
 * Add menu item custom fields
 */
function gpress_add_menu_item_fields($item_id, $item) {
    $icon = get_post_meta($item_id, '_menu_item_icon', true);
    $description = get_post_meta($item_id, '_menu_item_description', true);
    $badge = get_post_meta($item_id, '_menu_item_badge', true);
    $highlight = get_post_meta($item_id, '_menu_item_highlight', true);
    ?>
    <div class="gpress-menu-item-options">
        <p class="description description-wide">
            <label for="edit-menu-item-icon-<?php echo $item_id; ?>">
                <?php _e('Menu Icon', 'gpress'); ?><br>
                <input type="text" id="edit-menu-item-icon-<?php echo $item_id; ?>" 
                       class="widefat" name="menu-item-icon[<?php echo $item_id; ?>]" 
                       value="<?php echo esc_attr($icon); ?>" 
                       placeholder="<?php esc_attr_e('🏠 or text', 'gpress'); ?>">
            </label>
        </p>
        
        <p class="description description-wide">
            <label for="edit-menu-item-description-<?php echo $item_id; ?>">
                <?php _e('Description', 'gpress'); ?><br>
                <textarea id="edit-menu-item-description-<?php echo $item_id; ?>" 
                          class="widefat" name="menu-item-description[<?php echo $item_id; ?>]" 
                          rows="3"><?php echo esc_textarea($description); ?></textarea>
            </label>
        </p>
        
        <p class="description description-thin">
            <label for="edit-menu-item-badge-<?php echo $item_id; ?>">
                <?php _e('Badge', 'gpress'); ?><br>
                <input type="text" id="edit-menu-item-badge-<?php echo $item_id; ?>" 
                       class="widefat" name="menu-item-badge[<?php echo $item_id; ?>]" 
                       value="<?php echo esc_attr($badge); ?>" 
                       placeholder="<?php esc_attr_e('New', 'gpress'); ?>">
            </label>
        </p>
        
        <p class="description description-thin">
            <label for="edit-menu-item-highlight-<?php echo $item_id; ?>">
                <input type="checkbox" id="edit-menu-item-highlight-<?php echo $item_id; ?>" 
                       name="menu-item-highlight[<?php echo $item_id; ?>]" value="1" 
                       <?php checked($highlight, 1); ?>>
                <?php _e('Highlight Item', 'gpress'); ?>
            </label>
        </p>
    </div>
    <?php
}

/**
 * Save menu item custom fields
 */
function gpress_save_menu_item_fields($menu_id, $menu_item_db_id) {
    $fields = array('menu-item-icon', 'menu-item-description', 'menu-item-badge', 'menu-item-highlight');
    
    foreach ($fields as $field) {
        $key = str_replace('-', '_', $field);
        
        if (isset($_POST[$field][$menu_item_db_id])) {
            $value = $_POST[$field][$menu_item_db_id];
            
            if ($field === 'menu-item-highlight') {
                $value = (bool) $value;
            } else {
                $value = sanitize_text_field($value);
            }
            
            update_post_meta($menu_item_db_id, '_' . $key, $value);
        } else {
            delete_post_meta($menu_item_db_id, '_' . $key);
        }
    }
}

/**
 * Navigation body classes
 */
function gpress_navigation_body_classes($classes) {
    if (has_nav_menu('primary')) {
        $classes[] = 'has-primary-menu';
    }
    
    if (wp_is_mobile()) {
        $classes[] = 'is-mobile-device';
    }
    
    if (get_theme_mod('enable_mega_menu', false)) {
        $classes[] = 'has-mega-menu';
    }
    
    if (get_theme_mod('enable_breadcrumbs', true)) {
        $classes[] = 'has-breadcrumbs';
    }
    
    return $classes;
}

/**
 * Navigation accessibility script
 */
function gpress_navigation_accessibility_script() {
    if (!wp_script_is('gpress-navigation', 'enqueued')) {
        return;
    }
    ?>
    <script>
    // Basic navigation accessibility enhancements
    document.addEventListener('DOMContentLoaded', function() {
        // Add ARIA labels to menu toggles
        const menuToggles = document.querySelectorAll('.menu-toggle, .mobile-menu-toggle');
        menuToggles.forEach(function(toggle) {
            if (!toggle.getAttribute('aria-label')) {
                toggle.setAttribute('aria-label', '<?php echo esc_js(__('Toggle navigation menu', 'gpress')); ?>');
            }
        });
        
        // Announce navigation changes to screen readers
        function announceNavChange(message) {
            const announcement = document.createElement('div');
            announcement.setAttribute('aria-live', 'polite');
            announcement.setAttribute('aria-atomic', 'true');
            announcement.className = 'screen-reader-text';
            announcement.textContent = message;
            
            document.body.appendChild(announcement);
            
            setTimeout(function() {
                document.body.removeChild(announcement);
            }, 1000);
        }
        
        // Monitor navigation state changes
        const observer = new MutationObserver(function(mutations) {
            mutations.forEach(function(mutation) {
                if (mutation.type === 'attributes' && mutation.attributeName === 'aria-expanded') {
                    const isExpanded = mutation.target.getAttribute('aria-expanded') === 'true';
                    const message = isExpanded ? 
                        '<?php echo esc_js(__('Navigation menu opened', 'gpress')); ?>' : 
                        '<?php echo esc_js(__('Navigation menu closed', 'gpress')); ?>';
                    announceNavChange(message);
                }
            });
        });
        
        menuToggles.forEach(function(toggle) {
            observer.observe(toggle, { attributes: true });
        });
    });
    </script>
    <?php
}
```

## 2. Create Custom Navigation Walker

### File: `inc/navigation-walker.php`
```php
<?php
/**
 * GPress Navigation Walker
 *
 * @package GPress
 * @version 1.0.0
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Custom walker for navigation menus
 */
class GPress_Walker_Nav_Menu extends Walker_Nav_Menu {
    
    /**
     * Start element output
     */
    function start_el(&$output, $item, $depth = 0, $args = null, $id = 0) {
        $indent = ($depth) ? str_repeat("\t", $depth) : '';
        
        $classes = empty($item->classes) ? array() : (array) $item->classes;
        $classes[] = 'menu-item-' . $item->ID;
        
        // Add accessibility classes
        if (in_array('menu-item-has-children', $classes)) {
            $classes[] = 'has-submenu';
        }
        
        // Add custom classes from menu item settings
        $highlight = get_post_meta($item->ID, '_menu_item_highlight', true);
        if ($highlight) {
            $classes[] = 'menu-item-highlight';
        }
        
        $class_names = join(' ', apply_filters('nav_menu_css_class', array_filter($classes), $item, $args));
        $class_names = $class_names ? ' class="' . esc_attr($class_names) . '"' : '';
        
        $id = apply_filters('nav_menu_item_id', 'menu-item-' . $item->ID, $item, $args);
        $id = $id ? ' id="' . esc_attr($id) . '"' : '';
        
        // Create list item
        $output .= $indent . '<li' . $id . $class_names .'>';
        
        // Build link attributes
        $attributes = !empty($item->attr_title) ? ' title="' . esc_attr($item->attr_title) . '"' : '';
        $attributes .= !empty($item->target) ? ' target="' . esc_attr($item->target) . '"' : '';
        $attributes .= !empty($item->xfn) ? ' rel="' . esc_attr($item->xfn) . '"' : '';
        $attributes .= !empty($item->url) ? ' href="' . esc_attr($item->url) . '"' : '';
        
        // Add ARIA attributes for accessibility
        if (in_array('menu-item-has-children', $classes)) {
            $attributes .= ' aria-expanded="false"';
            $attributes .= ' aria-haspopup="true"';
        }
        
        // Build link content
        $item_output = isset($args->before) ? $args->before : '';
        $item_output .= '<a' . $attributes . '>';
        
        // Add icon if specified
        $icon = get_post_meta($item->ID, '_menu_item_icon', true);
        if ($icon) {
            $item_output .= '<span class="menu-icon" aria-hidden="true">' . esc_html($icon) . '</span>';
        }
        
        $item_output .= '<span class="menu-text">';
        $item_output .= isset($args->link_before) ? $args->link_before : '';
        $item_output .= apply_filters('the_title', $item->title, $item->ID);
        $item_output .= isset($args->link_after) ? $args->link_after : '';
        $item_output .= '</span>';
        
        // Add badge if specified
        $badge = get_post_meta($item->ID, '_menu_item_badge', true);
        if ($badge) {
            $item_output .= '<span class="menu-badge" aria-label="' . esc_attr($badge) . '">' . esc_html($badge) . '</span>';
        }
        
        // Add submenu indicator
        if (in_array('menu-item-has-children', $classes)) {
            $item_output .= '<span class="submenu-indicator" aria-hidden="true"><svg width="12" height="8" viewBox="0 0 12 8" fill="none"><path d="M1 1L6 6L11 1" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg></span>';
        }
        
        $item_output .= '</a>';
        
        // Add description if available
        $description = get_post_meta($item->ID, '_menu_item_description', true);
        if ($description) {
            $item_output .= '<span class="menu-description">' . esc_html($description) . '</span>';
        }
        
        $item_output .= isset($args->after) ? $args->after : '';
        
        $output .= apply_filters('walker_nav_menu_start_el', $item_output, $item, $depth, $args);
    }
    
    /**
     * Start submenu output
     */
    function start_lvl(&$output, $depth = 0, $args = null) {
        $indent = str_repeat("\t", $depth);
        $output .= "\n$indent<ul class=\"sub-menu\" role=\"menu\">\n";
    }
    
    /**
     * End submenu output
     */
    function end_lvl(&$output, $depth = 0, $args = null) {
        $indent = str_repeat("\t", $depth);
        $output .= "$indent</ul>\n";
    }
}
```

## 3. Create Breadcrumb Functionality

### File: `inc/breadcrumbs.php`
```php
<?php
/**
 * Breadcrumb Functionality for GPress Theme
 *
 * @package GPress
 * @version 1.0.0
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Initialize breadcrumb system
 */
function gpress_init_breadcrumbs() {
    // Add breadcrumb display
    add_action('gpress_breadcrumbs', 'gpress_display_breadcrumbs');
    
    // Add structured data for breadcrumbs
    add_action('wp_head', 'gpress_breadcrumbs_schema');
    
    // Add breadcrumb styles conditionally
    add_action('wp_enqueue_scripts', 'gpress_conditional_breadcrumb_assets');
}
add_action('after_setup_theme', 'gpress_init_breadcrumbs');

/**
 * Display breadcrumb navigation
 */
function gpress_display_breadcrumbs() {
    // Don't display on homepage
    if (is_front_page() || !get_theme_mod('enable_breadcrumbs', true)) {
        return;
    }
    
    global $post;
    $breadcrumbs = array();
    $separator = '<span class="breadcrumb-separator" aria-hidden="true">›</span>';
    
    // Home link
    $breadcrumbs[] = '<a href="' . esc_url(home_url('/')) . '" aria-label="' . esc_attr__('Home', 'gpress') . '">
        <span class="breadcrumb-icon" aria-hidden="true">🏠</span>
        <span class="breadcrumb-text">' . __('Home', 'gpress') . '</span>
    </a>';
    
    if (is_single()) {
        // Single post
        $post_type = get_post_type();
        
        if ($post_type === 'post') {
            // Blog posts
            $blog_page = get_option('page_for_posts');
            if ($blog_page) {
                $breadcrumbs[] = '<a href="' . esc_url(get_permalink($blog_page)) . '">' . esc_html(get_the_title($blog_page)) . '</a>';
            }
            
            // Categories
            $categories = get_the_category();
            if ($categories) {
                $main_category = $categories[0];
                $breadcrumbs[] = '<a href="' . esc_url(get_category_link($main_category->term_id)) . '">' . esc_html($main_category->name) . '</a>';
            }
        } else {
            // Custom post types
            $post_type_object = get_post_type_object($post_type);
            if ($post_type_object && $post_type_object->has_archive) {
                $breadcrumbs[] = '<a href="' . esc_url(get_post_type_archive_link($post_type)) . '">' . esc_html($post_type_object->labels->name) . '</a>';
            }
        }
        
        // Current post
        $breadcrumbs[] = '<span class="breadcrumb-current" aria-current="page">' . esc_html(get_the_title()) . '</span>';
        
    } elseif (is_page()) {
        // Pages
        if ($post->post_parent) {
            $ancestors = get_post_ancestors($post->ID);
            $ancestors = array_reverse($ancestors);
            
            foreach ($ancestors as $ancestor) {
                $breadcrumbs[] = '<a href="' . esc_url(get_permalink($ancestor)) . '">' . esc_html(get_the_title($ancestor)) . '</a>';
            }
        }
        
        $breadcrumbs[] = '<span class="breadcrumb-current" aria-current="page">' . esc_html(get_the_title()) . '</span>';
        
    } elseif (is_category()) {
        // Category archives
        $category = get_queried_object();
        
        if ($category->parent) {
            $ancestors = get_ancestors($category->term_id, 'category');
            $ancestors = array_reverse($ancestors);
            
            foreach ($ancestors as $ancestor) {
                $ancestor_category = get_category($ancestor);
                $breadcrumbs[] = '<a href="' . esc_url(get_category_link($ancestor)) . '">' . esc_html($ancestor_category->name) . '</a>';
            }
        }
        
        $breadcrumbs[] = '<span class="breadcrumb-current" aria-current="page">' . esc_html($category->name) . '</span>';
        
    } elseif (is_tag()) {
        // Tag archives
        $tag = get_queried_object();
        $breadcrumbs[] = '<span class="breadcrumb-current" aria-current="page">' . sprintf(__('Tag: %s', 'gpress'), esc_html($tag->name)) . '</span>';
        
    } elseif (is_author()) {
        // Author archives
        $author = get_queried_object();
        $breadcrumbs[] = '<span class="breadcrumb-current" aria-current="page">' . sprintf(__('Author: %s', 'gpress'), esc_html($author->display_name)) . '</span>';
        
    } elseif (is_search()) {
        // Search results
        $breadcrumbs[] = '<span class="breadcrumb-current" aria-current="page">' . sprintf(__('Search results for: %s', 'gpress'), esc_html(get_search_query())) . '</span>';
        
    } elseif (is_404()) {
        // 404 page
        $breadcrumbs[] = '<span class="breadcrumb-current" aria-current="page">' . __('Page not found', 'gpress') . '</span>';
        
    } elseif (is_archive()) {
        // Other archives
        $archive_title = get_the_archive_title();
        $breadcrumbs[] = '<span class="breadcrumb-current" aria-current="page">' . esc_html($archive_title) . '</span>';
    }
    
    // Output breadcrumbs
    if (!empty($breadcrumbs)) {
        echo '<nav class="breadcrumb-navigation" role="navigation" aria-label="' . esc_attr__('Breadcrumb', 'gpress') . '">';
        echo '<div class="breadcrumb-container">';
        echo implode(' ' . $separator . ' ', $breadcrumbs);
        echo '</div>';
        echo '</nav>';
    }
}

/**
 * Add breadcrumbs schema markup
 */
function gpress_breadcrumbs_schema() {
    if (is_front_page() || !get_theme_mod('enable_breadcrumbs', true)) {
        return;
    }
    
    global $post;
    $breadcrumbs = array();
    $position = 1;
    
    // Home
    $breadcrumbs[] = array(
        '@type' => 'ListItem',
        'position' => $position++,
        'name' => get_bloginfo('name'),
        'item' => home_url('/')
    );
    
    // Add other breadcrumb items based on context
    if (is_single() || is_page()) {
        if ($post && $post->post_parent) {
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
        
        if ($post) {
            $breadcrumbs[] = array(
                '@type' => 'ListItem',
                'position' => $position++,
                'name' => get_the_title(),
                'item' => get_permalink()
            );
        }
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
        $schema = array(
            '@context' => 'https://schema.org',
            '@type' => 'BreadcrumbList',
            'itemListElement' => $breadcrumbs
        );
        
        echo '<script type="application/ld+json">' . wp_json_encode($schema, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) . '</script>' . "\n";
    }
}

/**
 * Conditional breadcrumb asset loading
 */
function gpress_conditional_breadcrumb_assets() {
    if (!is_front_page() && get_theme_mod('enable_breadcrumbs', true)) {
        wp_enqueue_style(
            'gpress-breadcrumbs',
            get_theme_file_uri('/assets/css/breadcrumbs.css'),
            array('gpress-style'),
            GPRESS_VERSION
        );
    }
}
```

## 4. Create Navigation JavaScript

### File: `assets/js/navigation.js`
```javascript
/**
 * GPress Navigation JavaScript
 */
document.addEventListener('DOMContentLoaded', function() {
    
    // Initialize navigation components
    initPrimaryNavigation();
    initMobileMenu();
    initSubmenus();
    initKeyboardNavigation();
    initAccessibilityFeatures();
    
    /**
     * Initialize primary navigation
     */
    function initPrimaryNavigation() {
        const primaryNav = document.querySelector('.primary-navigation');
        if (!primaryNav) return;
        
        // Add responsive classes
        updateNavigationClasses();
        
        // Handle window resize
        window.addEventListener('resize', debounce(updateNavigationClasses, 100));
    }
    
    /**
     * Initialize mobile menu
     */
    function initMobileMenu() {
        const mobileToggle = document.querySelector('.mobile-menu-toggle, .menu-toggle');
        const mobileMenu = document.querySelector('.mobile-menu, .mobile-navigation');
        const mobileClose = document.querySelector('.mobile-menu-close');
        
        if (!mobileToggle || !mobileMenu) return;
        
        mobileToggle.addEventListener('click', function(e) {
            e.preventDefault();
            toggleMobileMenu();
        });
        
        if (mobileClose) {
            mobileClose.addEventListener('click', function(e) {
                e.preventDefault();
                toggleMobileMenu();
            });
        }
        
        // Close on escape key
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape' && mobileMenu.classList.contains('is-open')) {
                toggleMobileMenu();
                mobileToggle.focus();
            }
        });
        
        // Close on outside click
        document.addEventListener('click', function(e) {
            if (mobileMenu.classList.contains('is-open') && 
                !mobileMenu.contains(e.target) && 
                !mobileToggle.contains(e.target)) {
                toggleMobileMenu();
            }
        });
        
        function toggleMobileMenu() {
            const isOpen = mobileMenu.classList.contains('is-open');
            
            if (isOpen) {
                mobileMenu.classList.remove('is-open');
                mobileToggle.setAttribute('aria-expanded', 'false');
                document.body.classList.remove('mobile-menu-open');
                
                // Restore focus
                mobileToggle.focus();
            } else {
                mobileMenu.classList.add('is-open');
                mobileToggle.setAttribute('aria-expanded', 'true');
                document.body.classList.add('mobile-menu-open');
                
                // Focus first menu item
                const firstMenuItem = mobileMenu.querySelector('a');
                if (firstMenuItem) {
                    firstMenuItem.focus();
                }
            }
        }
    }
    
    /**
     * Initialize submenu functionality
     */
    function initSubmenus() {
        const menuItems = document.querySelectorAll('.menu-item-has-children > a, .has-submenu > a');
        
        menuItems.forEach(function(item) {
            // Add keyboard support
            item.addEventListener('keydown', function(e) {
                if (e.key === 'ArrowDown' || e.key === 'Enter') {
                    e.preventDefault();
                    const submenu = this.nextElementSibling;
                    if (submenu) {
                        const firstSubmenuItem = submenu.querySelector('a');
                        if (firstSubmenuItem) {
                            firstSubmenuItem.focus();
                        }
                    }
                }
            });
            
            // Handle click for touch devices
            item.addEventListener('click', function(e) {
                if (window.innerWidth <= 768) {
                    e.preventDefault();
                    const expanded = this.getAttribute('aria-expanded') === 'true';
                    this.setAttribute('aria-expanded', !expanded);
                    
                    const submenu = this.nextElementSibling;
                    if (submenu) {
                        submenu.style.display = expanded ? 'none' : 'block';
                    }
                }
            });
        });
        
        // Handle submenu item navigation
        const submenuItems = document.querySelectorAll('.sub-menu a');
        
        submenuItems.forEach(function(item, index, items) {
            item.addEventListener('keydown', function(e) {
                switch (e.key) {
                    case 'ArrowDown':
                        e.preventDefault();
                        const nextItem = items[index + 1];
                        if (nextItem) {
                            nextItem.focus();
                        }
                        break;
                        
                    case 'ArrowUp':
                        e.preventDefault();
                        if (index === 0) {
                            // Focus parent menu item
                            const parentItem = this.closest('.menu-item-has-children, .has-submenu').querySelector('> a');
                            if (parentItem) {
                                parentItem.focus();
                            }
                        } else {
                            const prevItem = items[index - 1];
                            if (prevItem) {
                                prevItem.focus();
                            }
                        }
                        break;
                        
                    case 'Escape':
                        e.preventDefault();
                        const parentMenuItem = this.closest('.menu-item-has-children, .has-submenu').querySelector('> a');
                        if (parentMenuItem) {
                            parentMenuItem.focus();
                            parentMenuItem.setAttribute('aria-expanded', 'false');
                        }
                        break;
                }
            });
        });
    }
    
    /**
     * Initialize keyboard navigation
     */
    function initKeyboardNavigation() {
        const menuItems = document.querySelectorAll('.nav-menu a');
        
        menuItems.forEach(function(item, index, items) {
            item.addEventListener('keydown', function(e) {
                let targetItem = null;
                
                switch (e.key) {
                    case 'ArrowLeft':
                        if (index > 0) {
                            targetItem = items[index - 1];
                        }
                        break;
                        
                    case 'ArrowRight':
                        if (index < items.length - 1) {
                            targetItem = items[index + 1];
                        }
                        break;
                        
                    case 'Home':
                        targetItem = items[0];
                        break;
                        
                    case 'End':
                        targetItem = items[items.length - 1];
                        break;
                }
                
                if (targetItem) {
                    e.preventDefault();
                    targetItem.focus();
                }
            });
        });
    }
    
    /**
     * Initialize accessibility features
     */
    function initAccessibilityFeatures() {
        // Add ARIA labels where missing
        const menuToggles = document.querySelectorAll('.menu-toggle, .mobile-menu-toggle');
        menuToggles.forEach(function(toggle) {
            if (!toggle.getAttribute('aria-label')) {
                toggle.setAttribute('aria-label', gpressNav.strings.menu_toggle);
            }
        });
        
        // Add ARIA expanded attributes
        const submenuParents = document.querySelectorAll('.menu-item-has-children > a, .has-submenu > a');
        submenuParents.forEach(function(parent) {
            if (!parent.getAttribute('aria-expanded')) {
                parent.setAttribute('aria-expanded', 'false');
            }
        });
        
        // Add role attributes where needed
        const submenus = document.querySelectorAll('.sub-menu');
        submenus.forEach(function(submenu) {
            submenu.setAttribute('role', 'menu');
        });
    }
    
    /**
     * Update navigation classes based on screen size
     */
    function updateNavigationClasses() {
        const isMobile = window.innerWidth <= 768;
        document.body.classList.toggle('is-mobile-nav', isMobile);
        document.body.classList.toggle('is-desktop-nav', !isMobile);
    }
    
    /**
     * Debounce function for performance
     */
    function debounce(func, wait) {
        let timeout;
        return function executedFunction(...args) {
            const later = () => {
                clearTimeout(timeout);
                func(...args);
            };
            clearTimeout(timeout);
            timeout = setTimeout(later, wait);
        };
    }
    
    // Smooth scrolling for anchor links
    const anchorLinks = document.querySelectorAll('a[href^="#"]');
    anchorLinks.forEach(function(link) {
        link.addEventListener('click', function(e) {
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
    
    // Performance optimization: Intersection Observer for menu animations
    if ('IntersectionObserver' in window) {
        const navObserver = new IntersectionObserver(function(entries) {
            entries.forEach(function(entry) {
                if (entry.isIntersecting) {
                    entry.target.classList.add('nav-item-visible');
                }
            });
        }, { threshold: 0.1 });
        
        const navItems = document.querySelectorAll('.menu-item');
        navItems.forEach(function(item) {
            navObserver.observe(item);
        });
    }
});
```

## 5. Create Navigation Styles

### File: `assets/css/navigation.css`
```css
/* GPress Navigation Styles */

/* Main Navigation */
.main-navigation {
    background: var(--wp--preset--color--background);
    border-bottom: 1px solid var(--wp--preset--color--border);
    padding: 1rem 0;
    position: sticky;
    top: 0;
    z-index: 1000;
    backdrop-filter: blur(10px);
    transition: all 0.3s ease;
}

.nav-container {
    display: flex;
    align-items: center;
    justify-content: space-between;
    max-width: 1200px;
    margin: 0 auto;
    padding: 0 1rem;
}

.nav-primary-menu,
.nav-secondary-menu {
    display: flex;
    list-style: none;
    margin: 0;
    padding: 0;
    gap: 1rem;
}

.nav-primary-menu .menu-item,
.nav-secondary-menu .menu-item {
    position: relative;
}

.nav-primary-menu a,
.nav-secondary-menu a {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    text-decoration: none;
    color: var(--wp--preset--color--foreground);
    padding: 0.75rem 1rem;
    border-radius: 8px;
    transition: all 0.3s ease;
    font-weight: 500;
}

.nav-primary-menu a:hover,
.nav-primary-menu a:focus,
.nav-secondary-menu a:hover,
.nav-secondary-menu a:focus {
    background: var(--wp--preset--color--primary);
    color: var(--wp--preset--color--background);
    outline: 2px solid var(--wp--preset--color--primary);
    outline-offset: 2px;
}

/* Menu Icons and Badges */
.menu-icon {
    font-size: 1.1em;
    flex-shrink: 0;
}

.menu-badge {
    background: var(--wp--preset--color--accent);
    color: var(--wp--preset--color--background);
    font-size: 0.75rem;
    padding: 0.125rem 0.375rem;
    border-radius: 12px;
    font-weight: 600;
    margin-left: 0.25rem;
    flex-shrink: 0;
}

.menu-item-highlight a {
    background: var(--wp--preset--color--accent);
    color: var(--wp--preset--color--background);
    font-weight: 600;
}

/* Submenu Styles */
.has-submenu .submenu-indicator {
    margin-left: 0.25rem;
    transition: transform 0.3s ease;
    flex-shrink: 0;
}

.has-submenu[aria-expanded="true"] .submenu-indicator {
    transform: rotate(180deg);
}

.sub-menu {
    position: absolute;
    top: 100%;
    left: 0;
    background: var(--wp--preset--color--background);
    border: 1px solid var(--wp--preset--color--border);
    border-radius: 12px;
    box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
    opacity: 0;
    visibility: hidden;
    transform: translateY(-10px);
    transition: all 0.3s ease;
    min-width: 220px;
    z-index: 1001;
    list-style: none;
    margin: 0;
    padding: 0.5rem 0;
}

.has-submenu:hover .sub-menu,
.has-submenu:focus-within .sub-menu,
.has-submenu[aria-expanded="true"] .sub-menu {
    opacity: 1;
    visibility: visible;
    transform: translateY(0);
}

.sub-menu .menu-item {
    width: 100%;
}

.sub-menu a {
    padding: 0.75rem 1rem;
    border-radius: 0;
    width: 100%;
    margin: 0;
}

.sub-menu a:hover,
.sub-menu a:focus {
    background: var(--wp--preset--color--background-secondary);
    color: var(--wp--preset--color--foreground);
}

/* Mobile Navigation */
.mobile-menu-toggle {
    display: none;
    background: none;
    border: none;
    cursor: pointer;
    padding: 0.5rem;
    color: var(--wp--preset--color--foreground);
    font-size: 1.5rem;
    border-radius: 8px;
    transition: all 0.3s ease;
}

.mobile-menu-toggle:hover,
.mobile-menu-toggle:focus {
    background: var(--wp--preset--color--background-secondary);
    outline: 2px solid var(--wp--preset--color--primary);
    outline-offset: 2px;
}

.mobile-menu-toggle .hamburger-icon {
    display: block;
    transition: transform 0.3s ease;
}

.mobile-menu-toggle[aria-expanded="true"] .hamburger-icon {
    transform: rotate(90deg);
}

.mobile-navigation {
    position: fixed;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: var(--wp--preset--color--background);
    z-index: 9999;
    opacity: 0;
    visibility: hidden;
    transition: all 0.3s ease;
    overflow-y: auto;
    padding: 1rem;
}

.mobile-navigation.is-open {
    opacity: 1;
    visibility: visible;
}

.mobile-menu-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 2rem;
    padding-bottom: 1rem;
    border-bottom: 1px solid var(--wp--preset--color--border);
}

.mobile-menu-close {
    background: none;
    border: none;
    font-size: 2rem;
    cursor: pointer;
    color: var(--wp--preset--color--foreground);
    padding: 0.25rem;
    border-radius: 8px;
    transition: all 0.3s ease;
}

.mobile-menu-close:hover,
.mobile-menu-close:focus {
    background: var(--wp--preset--color--background-secondary);
    outline: 2px solid var(--wp--preset--color--primary);
    outline-offset: 2px;
}

.mobile-navigation .nav-menu {
    list-style: none;
    margin: 0;
    padding: 0;
}

.mobile-navigation .menu-item {
    margin-bottom: 0.5rem;
}

.mobile-navigation a {
    display: block;
    padding: 1rem;
    border-radius: 12px;
    text-decoration: none;
    color: var(--wp--preset--color--foreground);
    background: var(--wp--preset--color--background-secondary);
    transition: all 0.3s ease;
    font-weight: 500;
}

.mobile-navigation a:hover,
.mobile-navigation a:focus {
    background: var(--wp--preset--color--primary);
    color: var(--wp--preset--color--background);
    transform: translateX(5px);
}

/* Social Navigation */
.social-navigation {
    display: flex;
    gap: 0.5rem;
    list-style: none;
    margin: 0;
    padding: 0;
}

.social-navigation a {
    display: flex;
    align-items: center;
    justify-content: center;
    width: 40px;
    height: 40px;
    border-radius: 50%;
    background: var(--wp--preset--color--background-secondary);
    color: var(--wp--preset--color--foreground-secondary);
    transition: all 0.3s ease;
    text-decoration: none;
}

.social-navigation a:hover,
.social-navigation a:focus {
    background: var(--wp--preset--color--primary);
    color: var(--wp--preset--color--background);
    transform: translateY(-2px);
}

/* Responsive Design */
@media (max-width: 768px) {
    .nav-primary-menu,
    .nav-secondary-menu {
        display: none;
    }
    
    .mobile-menu-toggle {
        display: block;
    }
    
    .sub-menu {
        position: static;
        opacity: 1;
        visibility: visible;
        transform: none;
        box-shadow: none;
        border: none;
        background: transparent;
        padding-left: 1rem;
        display: none;
    }
    
    .has-submenu[aria-expanded="true"] .sub-menu {
        display: block;
    }
}

/* High Contrast Mode Support */
@media (prefers-contrast: high) {
    .main-navigation {
        border-bottom-width: 2px;
    }
    
    .nav-primary-menu a:hover,
    .nav-primary-menu a:focus {
        outline-width: 3px;
    }
    
    .sub-menu {
        border-width: 2px;
    }
}

/* Reduced Motion Support */
@media (prefers-reduced-motion: reduce) {
    .nav-primary-menu a,
    .sub-menu,
    .mobile-navigation,
    .submenu-indicator,
    .mobile-menu-toggle .hamburger-icon {
        transition: none;
    }
    
    .nav-primary-menu a:hover,
    .mobile-navigation a:hover,
    .social-navigation a:hover {
        transform: none;
    }
}

/* Print Styles */
@media print {
    .main-navigation,
    .mobile-navigation,
    .mobile-menu-toggle {
        display: none;
    }
}

/* Focus Management */
.skip-navigation {
    position: absolute;
    left: -9999px;
    top: 1rem;
    z-index: 999999;
    text-decoration: none;
    padding: 0.5rem 1rem;
    background: var(--wp--preset--color--primary);
    color: var(--wp--preset--color--background);
    border-radius: 4px;
    font-weight: 600;
}

.skip-navigation:focus {
    left: 1rem;
}

/* Navigation Animation Classes */
.nav-item-visible {
    animation: slideInUp 0.6s ease-out;
}

@keyframes slideInUp {
    from {
        opacity: 0;
        transform: translateY(20px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

/* Body Classes for Navigation States */
.mobile-menu-open {
    overflow: hidden;
}

.is-mobile-nav .main-navigation {
    padding: 0.5rem 0;
}

.is-desktop-nav .mobile-navigation {
    display: none;
}

/* Loading States */
.nav-loading {
    opacity: 0.6;
    pointer-events: none;
}

.nav-loading::after {
    content: '';
    position: absolute;
    top: 50%;
    left: 50%;
    width: 20px;
    height: 20px;
    border: 2px solid var(--wp--preset--color--primary);
    border-top-color: transparent;
    border-radius: 50%;
    animation: spin 1s linear infinite;
    transform: translate(-50%, -50%);
}

@keyframes spin {
    to { transform: translate(-50%, -50%) rotate(360deg); }
}
```

## 6. Create Breadcrumb Styles

### File: `assets/css/breadcrumbs.css`
```css
/* GPress Breadcrumb Styles */

/* Breadcrumb Navigation */
.breadcrumb-navigation {
    background: var(--wp--preset--color--background-secondary);
    padding: 0.75rem 0;
    border-bottom: 1px solid var(--wp--preset--color--border);
    font-size: 0.875rem;
}

.breadcrumb-container {
    display: flex;
    align-items: center;
    flex-wrap: wrap;
    gap: 0.5rem;
    max-width: 1200px;
    margin: 0 auto;
    padding: 0 1rem;
}

.breadcrumb-container a {
    display: flex;
    align-items: center;
    gap: 0.25rem;
    color: var(--wp--preset--color--foreground-secondary);
    text-decoration: none;
    transition: color 0.3s ease;
    border-radius: 4px;
    padding: 0.25rem 0.5rem;
}

.breadcrumb-container a:hover,
.breadcrumb-container a:focus {
    color: var(--wp--preset--color--primary);
    background: var(--wp--preset--color--background);
    outline: 2px solid var(--wp--preset--color--primary);
    outline-offset: 2px;
}

.breadcrumb-icon {
    font-size: 0.875em;
}

.breadcrumb-text {
    font-weight: 500;
}

.breadcrumb-current {
    color: var(--wp--preset--color--foreground);
    font-weight: 600;
    padding: 0.25rem 0.5rem;
}

.breadcrumb-separator {
    color: var(--wp--preset--color--foreground-secondary);
    margin: 0 0.25rem;
    font-size: 0.75rem;
}

/* Responsive Design */
@media (max-width: 768px) {
    .breadcrumb-navigation {
        padding: 0.5rem 0;
        font-size: 0.8rem;
    }
    
    .breadcrumb-container {
        padding: 0 0.5rem;
    }
    
    .breadcrumb-text {
        display: none;
    }
    
    .breadcrumb-container a {
        padding: 0.25rem;
    }
    
    .breadcrumb-current {
        padding: 0.25rem;
    }
}

/* High Contrast Mode Support */
@media (prefers-contrast: high) {
    .breadcrumb-navigation {
        border-bottom-width: 2px;
    }
    
    .breadcrumb-container a:hover,
    .breadcrumb-container a:focus {
        outline-width: 3px;
    }
}

/* Print Styles */
@media print {
    .breadcrumb-navigation {
        background: white;
        border-bottom: 1px solid black;
    }
    
    .breadcrumb-container a {
        color: black;
    }
}
```

## 7. Create Template Parts

### File: `parts/navigation.html`
```html
<!-- wp:group {"tagName":"nav","className":"main-navigation","layout":{"type":"flex","justifyContent":"space-between","flexWrap":"wrap"}} -->
<nav class="wp-block-group main-navigation" role="navigation" aria-label="Main navigation">
    <!-- wp:site-logo {"width":120,"className":"site-logo"} /-->
    
    <!-- wp:group {"className":"nav-menu-container","layout":{"type":"flex","flexWrap":"nowrap"}} -->
    <div class="wp-block-group nav-menu-container">
        <!-- wp:navigation {"ref":1,"overlayMenu":"mobile","layout":{"type":"flex","setCascadingProperties":true,"justifyContent":"center"},"className":"primary-navigation","fontSize":"medium"} /-->
        
        <!-- wp:group {"className":"nav-actions","layout":{"type":"flex","flexWrap":"nowrap"}} -->
        <div class="wp-block-group nav-actions">
            <!-- wp:search {"label":"Search","showLabel":false,"placeholder":"Search...","width":250,"widthUnit":"px","buttonText":"Search","buttonPosition":"button-inside","buttonUseIcon":true,"className":"header-search"} /-->
            
            <!-- wp:social-links {"iconColor":"foreground","iconColorValue":"var(--wp--preset--color--foreground)","className":"header-social","layout":{"type":"flex","justifyContent":"center"}} -->
            <ul class="wp-block-social-links has-icon-color header-social">
                <!-- wp:social-link {"url":"#","service":"twitter","label":"Twitter"} /-->
                <!-- wp:social-link {"url":"#","service":"facebook","label":"Facebook"} /-->
                <!-- wp:social-link {"url":"#","service":"instagram","label":"Instagram"} /-->
            </ul>
            <!-- /wp:social-links -->
            
            <!-- wp:button {"className":"mobile-menu-toggle","fontSize":"small"} -->
            <div class="wp-block-button mobile-menu-toggle">
                <button class="wp-block-button__link" aria-expanded="false" aria-controls="mobile-menu" aria-label="Toggle mobile menu">
                    <span class="hamburger-icon" aria-hidden="true">☰</span>
                    <span class="screen-reader-text">Menu</span>
                </button>
            </div>
            <!-- /wp:button -->
        </div>
        <!-- /wp:group -->
    </div>
    <!-- /wp:group -->
</nav>
<!-- /wp:group -->
```

### File: `parts/breadcrumbs.html`
```html
<!-- wp:group {"className":"breadcrumb-wrapper","layout":{"type":"constrained"}} -->
<div class="wp-block-group breadcrumb-wrapper">
    <!-- wp:html -->
    <?php
    if (function_exists('gpress_display_breadcrumbs')) {
        gpress_display_breadcrumbs();
    }
    ?>
    <!-- /wp:html -->
</div>
<!-- /wp:group -->
```

## 8. Update Functions.php

Add to `functions.php`:

```php
// Advanced navigation system
require_once get_theme_file_path('/inc/navigation-system.php');
require_once get_theme_file_path('/inc/navigation-walker.php');
require_once get_theme_file_path('/inc/breadcrumbs.php');
```

## Testing

1. **Menu Functionality Testing**:
   - Install and activate the theme
   - Create primary, secondary, and mobile menus in Appearance > Menus
   - Test menu item custom fields (icons, badges, descriptions)
   - Verify conditional asset loading works correctly
   - Test submenu functionality and mobile menu toggle

2. **Accessibility Testing**:
   - Use screen readers (NVDA, JAWS, VoiceOver) for navigation
   - Test keyboard navigation (arrow keys, Enter, Escape)
   - Verify ARIA labels and landmark structure
   - Check skip links functionality and focus management

3. **Mobile Testing**:
   - Test mobile menu toggle and overlay
   - Verify touch targets meet 44px minimum requirement
   - Check responsive navigation behavior across devices
   - Test keyboard navigation on mobile devices

4. **Breadcrumb Testing**:
   - Check breadcrumb generation on various page types
   - Verify structured data markup appears in source code
   - Test hierarchical page and category navigation
   - Validate screen reader announcements

5. **Performance Testing**:
   - Verify navigation assets load conditionally
   - Check Core Web Vitals impact with navigation
   - Test loading speed with and without navigation features
   - Validate no JavaScript errors in console

## Next Steps

In Step 13, we'll implement comprehensive accessibility features to ensure WCAG 2.1 AA compliance.

## Key Benefits

- Conditional navigation asset loading for optimal performance
- Fully accessible navigation with ARIA support
- Mobile-optimized responsive design
- Keyboard navigation compliance
- SEO-friendly breadcrumb structure with structured data
- Advanced menu customization options with icons and badges
- Security-hardened navigation system
- Progressive enhancement approach for all devices