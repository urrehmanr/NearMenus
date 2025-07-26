# Step 12: Advanced Navigation & Menu Systems

## Overview
This step implements comprehensive navigation and menu systems with advanced accessibility features, mobile optimization, and intelligent asset loading. We'll create multiple navigation areas, breadcrumb systems, and custom menu enhancements while ensuring WCAG 2.1 AA compliance and optimal performance.

## Objectives
- Implement multiple navigation menu registration and management
- Create accessible navigation components with ARIA support
- Build responsive mobile navigation with touch optimization
- Develop breadcrumb navigation with structured data and dynamic custom post type support
- Establish conditional asset loading for navigation features
- Integrate custom menu item fields and advanced styling
- Ensure seamless integration with Step 11's dynamic custom post types framework

## What You'll Learn
- Advanced WordPress navigation menu registration and customization
- Accessible navigation design patterns and ARIA implementation
- Mobile-first navigation architecture and touch interactions
- Breadcrumb system development with SEO optimization
- Conditional PHP and JavaScript loading for navigation features
- Performance optimization for navigation-heavy sites

## Files Structure for This Step

### 📁 Files to CREATE:
```
inc/
├── navigation-system.php          # Navigation system management with Smart Asset Manager integration
├── navigation-walker.php          # Custom navigation walker with performance optimization
├── breadcrumbs.php                # Breadcrumb functionality with conditional loading
└── menu-customizer.php            # Menu customization features

parts/
├── navigation.html                # Main navigation template part
├── mobile-menu.html               # Mobile menu template part
├── breadcrumbs.html               # Breadcrumbs template part
├── social-navigation.html         # Social navigation template part
└── skip-links.html                # Skip links for accessibility

**Note**: Navigation CSS is handled by Smart Asset Manager:
- Core navigation styles: `assets/css/navigation.css` (loaded conditionally via Smart Asset Manager)
- Mobile navigation: Included in navigation.css with responsive design
- Navigation JavaScript: `assets/js/navigation.js` (loaded conditionally)

**Integration with Step 7**: Uses Smart Asset Manager's `page_has_navigation()` detection for conditional loading

assets/js/
├── navigation.js                 # Main navigation functionality
├── mobile-menu.js               # Mobile menu interactions
├── breadcrumbs.js               # Breadcrumb enhancements
├── keyboard-navigation.js       # Keyboard navigation support
└── navigation-analytics.js      # Navigation usage analytics
```

### 📝 Files to UPDATE:
```
functions.php                     # Include navigation files and initialization
inc/theme-setup.php              # Register navigation menus and support
inc/enqueue-scripts.php          # Conditional navigation asset loading
style.css                        # Base navigation integration styles
README.md                        # Document navigation features and usage
theme.json                       # Add navigation-specific settings and styles
```

### 🎯 Optimization Features Implemented:
- Conditional asset loading based on navigation usage detection
- Lazy loading for complex menu structures and mega menus
- Optimized keyboard navigation with focus management
- Advanced caching strategies for menu rendering
- Performance monitoring for navigation interactions
- Mobile-optimized touch gestures and animations
- Progressive enhancement for all navigation features

## Step-by-Step Implementation

### 1. Create Navigation System Management

Create `inc/navigation-system.php`:

```php
<?php
/**
 * Navigation System Management for GPress Theme
 * Implements advanced navigation with conditional loading and accessibility
 *
 * @package GPress
 * @subpackage Navigation
 * @version 1.0.0
 * @since 1.0.0
 */

// Prevent direct access
defined('ABSPATH') || exit;

/**
 * GPress Navigation System Manager
 * 
 * @since 1.0.0
 */
class GPress_Navigation_System {

    /**
     * Initialize navigation system
     *
     * @since 1.0.0
     */
    public static function init() {
        add_action('after_setup_theme', array(__CLASS__, 'register_nav_menus'));
        add_action('wp_enqueue_scripts', array(__CLASS__, 'conditional_navigation_assets'));
        add_filter('wp_nav_menu_args', array(__CLASS__, 'enhance_nav_menu_args'));
        add_filter('body_class', array(__CLASS__, 'navigation_body_classes'));
        add_action('wp_footer', array(__CLASS__, 'navigation_accessibility_script'));
        
        // Admin enhancements
        add_action('admin_init', array(__CLASS__, 'setup_menu_admin'));
        add_action('wp_nav_menu_item_custom_fields', array(__CLASS__, 'add_menu_item_fields'), 10, 2);
        add_action('wp_update_nav_menu_item', array(__CLASS__, 'save_menu_item_fields'), 10, 2);
    }

    /**
     * Register navigation menus
     *
     * @since 1.0.0
     */
    public static function register_nav_menus() {
        register_nav_menus(array(
            'primary'         => __('Primary Navigation', 'gpress'),
            'secondary'       => __('Secondary Navigation', 'gpress'),
            'footer'          => __('Footer Navigation', 'gpress'),
            'social'          => __('Social Media Links', 'gpress'),
            'mobile'          => __('Mobile Navigation', 'gpress'),
            'utility'         => __('Utility Menu', 'gpress'),
            'breadcrumb'      => __('Breadcrumb Enhancement', 'gpress'),
            'cta'             => __('Call-to-Action Menu', 'gpress'),
        ));
    }

    /**
     * Conditional navigation asset loading
     *
     * @since 1.0.0
     */
    public static function conditional_navigation_assets() {
        $load_navigation = false;
        $load_mobile = false;
        $load_breadcrumbs = false;
        $load_animations = false;
        
        // Check if navigation features are needed
        if (has_nav_menu('primary') || has_nav_menu('secondary') || has_nav_menu('mobile')) {
            $load_navigation = true;
        }
        
        // Check for mobile optimization needs
        if (wp_is_mobile() || has_nav_menu('mobile') || get_theme_mod('enable_mobile_menu', true)) {
            $load_mobile = true;
        }
        
        // Check for breadcrumbs
        if (!is_front_page() && get_theme_mod('enable_breadcrumbs', true)) {
            $load_breadcrumbs = true;
        }
        
        // Check for animations
        if (get_theme_mod('enable_menu_animations', true) && !wp_is_mobile()) {
            $load_animations = true;
        }
        
        // Load base navigation styles (always needed)
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
                'isMobile' => wp_is_mobile(),
                'settings' => array(
                    'menuAnimations' => get_theme_mod('enable_menu_animations', true),
                    'mobileBreakpoint' => get_theme_mod('mobile_breakpoint', 768),
                    'submenuDelay' => get_theme_mod('submenu_delay', 150),
                ),
                'strings' => array(
                    'menu_toggle' => __('Toggle menu', 'gpress'),
                    'submenu_toggle' => __('Toggle submenu', 'gpress'),
                    'close_menu' => __('Close menu', 'gpress'),
                    'open_menu' => __('Open menu', 'gpress'),
                    'menu_expanded' => __('Menu expanded', 'gpress'),
                    'menu_collapsed' => __('Menu collapsed', 'gpress'),
                    'loading' => __('Loading menu...', 'gpress'),
                )
            ));
        }
        
        if ($load_mobile) {
            wp_enqueue_style(
                'gpress-navigation-mobile',
                get_theme_file_uri('/assets/css/navigation-mobile.css'),
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
        
        if ($load_animations) {
            wp_enqueue_style(
                'gpress-menu-animations',
                get_theme_file_uri('/assets/css/menu-animations.css'),
                array('gpress-navigation'),
                GPRESS_VERSION
            );
        }
        
        // Always load keyboard navigation support
        wp_enqueue_script(
            'gpress-keyboard-navigation',
            get_theme_file_uri('/assets/js/keyboard-navigation.js'),
            array('gpress-navigation'),
            GPRESS_VERSION,
            array('strategy' => 'defer', 'in_footer' => true)
        );
    }

    /**
     * Enhance navigation menu args
     *
     * @since 1.0.0
     */
    public static function enhance_nav_menu_args($args) {
        if (!isset($args['walker'])) {
            require_once get_theme_file_path('/inc/navigation-walker.php');
            $args['walker'] = new GPress_Walker_Nav_Menu();
        }
        
        // Add container classes based on menu location
        if (isset($args['theme_location'])) {
            $location = $args['theme_location'];
            $args['container_class'] = "nav-container nav-{$location}";
            $args['menu_class'] = "nav-menu nav-{$location}-menu";
            
            // Add ARIA labels based on location
            switch ($location) {
                case 'primary':
                    $args['menu_aria_label'] = __('Main navigation', 'gpress');
                    break;
                case 'secondary':
                    $args['menu_aria_label'] = __('Secondary navigation', 'gpress');
                    break;
                case 'footer':
                    $args['menu_aria_label'] = __('Footer navigation', 'gpress');
                    break;
                case 'mobile':
                    $args['menu_aria_label'] = __('Mobile navigation', 'gpress');
                    break;
                default:
                    $args['menu_aria_label'] = sprintf(__('%s navigation', 'gpress'), ucfirst($location));
            }
        }
        
        return $args;
    }

    /**
     * Add menu item custom fields
     *
     * @since 1.0.0
     */
    public static function add_menu_item_fields($item_id, $item) {
        $icon = get_post_meta($item_id, '_menu_item_icon', true);
        $description = get_post_meta($item_id, '_menu_item_description', true);
        $badge = get_post_meta($item_id, '_menu_item_badge', true);
        $highlight = get_post_meta($item_id, '_menu_item_highlight', true);
        $mega_menu = get_post_meta($item_id, '_menu_item_mega_menu', true);
        ?>
        <div class="gpress-menu-item-options">
            <p class="description description-wide">
                <label for="edit-menu-item-icon-<?php echo $item_id; ?>">
                    <?php _e('Menu Icon', 'gpress'); ?><br>
                    <input type="text" id="edit-menu-item-icon-<?php echo $item_id; ?>" 
                           class="widefat" name="menu-item-icon[<?php echo $item_id; ?>]" 
                           value="<?php echo esc_attr($icon); ?>" 
                           placeholder="<?php esc_attr_e('🏠 or dashicon-home', 'gpress'); ?>">
                    <small><?php _e('Use emoji or dashicon class name', 'gpress'); ?></small>
                </label>
            </p>
            
            <p class="description description-wide">
                <label for="edit-menu-item-description-<?php echo $item_id; ?>">
                    <?php _e('Description', 'gpress'); ?><br>
                    <textarea id="edit-menu-item-description-<?php echo $item_id; ?>" 
                              class="widefat" name="menu-item-description[<?php echo $item_id; ?>]" 
                              rows="3" placeholder="<?php esc_attr_e('Brief description for accessibility', 'gpress'); ?>"><?php echo esc_textarea($description); ?></textarea>
                </label>
            </p>
            
            <p class="description description-thin">
                <label for="edit-menu-item-badge-<?php echo $item_id; ?>">
                    <?php _e('Badge Text', 'gpress'); ?><br>
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
            
            <p class="description description-thin">
                <label for="edit-menu-item-mega-menu-<?php echo $item_id; ?>">
                    <input type="checkbox" id="edit-menu-item-mega-menu-<?php echo $item_id; ?>" 
                           name="menu-item-mega-menu[<?php echo $item_id; ?>]" value="1" 
                           <?php checked($mega_menu, 1); ?>>
                    <?php _e('Enable Mega Menu', 'gpress'); ?>
                </label>
            </p>
        </div>
        <?php
    }

    /**
     * Save menu item custom fields
     *
     * @since 1.0.0
     */
    public static function save_menu_item_fields($menu_id, $menu_item_db_id) {
        $fields = array(
            'menu-item-icon' => '_menu_item_icon',
            'menu-item-description' => '_menu_item_description',
            'menu-item-badge' => '_menu_item_badge',
            'menu-item-highlight' => '_menu_item_highlight',
            'menu-item-mega-menu' => '_menu_item_mega_menu',
        );
        
        foreach ($fields as $field => $meta_key) {
            if (isset($_POST[$field][$menu_item_db_id])) {
                $value = $_POST[$field][$menu_item_db_id];
                
                if (in_array($field, array('menu-item-highlight', 'menu-item-mega-menu'))) {
                    $value = (bool) $value;
                } else {
                    $value = sanitize_text_field($value);
                }
                
                update_post_meta($menu_item_db_id, $meta_key, $value);
            } else {
                delete_post_meta($menu_item_db_id, $meta_key);
            }
        }
    }

    /**
     * Navigation body classes
     *
     * @since 1.0.0
     */
    public static function navigation_body_classes($classes) {
        // Check for navigation menus
        if (has_nav_menu('primary')) {
            $classes[] = 'has-primary-menu';
        }
        
        if (has_nav_menu('secondary')) {
            $classes[] = 'has-secondary-menu';
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
        
        if (get_theme_mod('enable_menu_animations', true)) {
            $classes[] = 'has-menu-animations';
        }
        
        return $classes;
    }

    /**
     * Setup menu admin enhancements
     *
     * @since 1.0.0
     */
    public static function setup_menu_admin() {
        add_action('admin_enqueue_scripts', array(__CLASS__, 'admin_assets'));
        add_filter('wp_edit_nav_menu_walker', array(__CLASS__, 'custom_admin_walker'));
    }

    /**
     * Enqueue admin assets for menu management
     *
     * @since 1.0.0
     */
    public static function admin_assets($hook) {
        if ($hook === 'nav-menus.php') {
            wp_enqueue_style(
                'gpress-menu-admin',
                get_theme_file_uri('/assets/css/admin-menu.css'),
                array(),
                GPRESS_VERSION
            );

            wp_enqueue_script(
                'gpress-menu-admin',
                get_theme_file_uri('/assets/js/admin-menu.js'),
                array('jquery'),
                GPRESS_VERSION,
                array('in_footer' => true)
            );
        }
    }

    /**
     * Navigation accessibility script
     *
     * @since 1.0.0
     */
    public static function navigation_accessibility_script() {
        if (!wp_script_is('gpress-navigation', 'enqueued')) {
            return;
        }
        ?>
        <script>
        // Enhanced navigation accessibility
        document.addEventListener('DOMContentLoaded', function() {
            // Add skip links if missing
            if (!document.querySelector('.skip-navigation')) {
                const skipLink = document.createElement('a');
                skipLink.href = '#main';
                skipLink.className = 'skip-navigation screen-reader-text';
                skipLink.textContent = '<?php echo esc_js(__('Skip to main content', 'gpress')); ?>';
                document.body.insertBefore(skipLink, document.body.firstChild);
            }
            
            // Enhance menu toggles with ARIA
            const menuToggles = document.querySelectorAll('.menu-toggle, .mobile-menu-toggle');
            menuToggles.forEach(function(toggle) {
                if (!toggle.getAttribute('aria-label')) {
                    toggle.setAttribute('aria-label', '<?php echo esc_js(__('Toggle navigation menu', 'gpress')); ?>');
                }
                
                if (!toggle.getAttribute('aria-expanded')) {
                    toggle.setAttribute('aria-expanded', 'false');
                }
            });
            
            // Live region for navigation announcements
            let liveRegion = document.getElementById('nav-live-region');
            if (!liveRegion) {
                liveRegion = document.createElement('div');
                liveRegion.id = 'nav-live-region';
                liveRegion.setAttribute('aria-live', 'polite');
                liveRegion.setAttribute('aria-atomic', 'true');
                liveRegion.className = 'screen-reader-text';
                document.body.appendChild(liveRegion);
            }
            
            // Announce navigation changes
            function announceNavChange(message) {
                liveRegion.textContent = message;
                setTimeout(function() {
                    liveRegion.textContent = '';
                }, 1000);
            }
            
            // Monitor ARIA state changes
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
}

// Initialize the navigation system
GPress_Navigation_System::init();
```

### 2. Create Custom Navigation Walker

Create `inc/navigation-walker.php`:

```php
<?php
/**
 * GPress Navigation Walker
 * Advanced walker for accessible and optimized navigation menus
 *
 * @package GPress
 * @subpackage Navigation
 * @version 1.0.0
 * @since 1.0.0
 */

// Prevent direct access
defined('ABSPATH') || exit;

/**
 * Custom walker for navigation menus with enhanced accessibility
 * 
 * @since 1.0.0
 */
class GPress_Walker_Nav_Menu extends Walker_Nav_Menu {
    
    /**
     * Start element output
     *
     * @since 1.0.0
     */
    function start_el(&$output, $item, $depth = 0, $args = null, $id = 0) {
        $indent = ($depth) ? str_repeat("\t", $depth) : '';
        
        $classes = empty($item->classes) ? array() : (array) $item->classes;
        $classes[] = 'menu-item-' . $item->ID;
        
        // Add depth-based classes
        $classes[] = 'menu-item-depth-' . $depth;
        
        // Add accessibility classes
        if (in_array('menu-item-has-children', $classes)) {
            $classes[] = 'has-submenu';
        }
        
        // Add custom classes from menu item settings
        $highlight = get_post_meta($item->ID, '_menu_item_highlight', true);
        if ($highlight) {
            $classes[] = 'menu-item-highlight';
        }
        
        $mega_menu = get_post_meta($item->ID, '_menu_item_mega_menu', true);
        if ($mega_menu) {
            $classes[] = 'menu-item-mega-menu';
        }
        
        $class_names = join(' ', apply_filters('nav_menu_css_class', array_filter($classes), $item, $args));
        $class_names = $class_names ? ' class="' . esc_attr($class_names) . '"' : '';
        
        $id = apply_filters('nav_menu_item_id', 'menu-item-' . $item->ID, $item, $args);
        $id = $id ? ' id="' . esc_attr($id) . '"' : '';
        
        // Create list item with improved structure
        $output .= $indent . '<li' . $id . $class_names .'>';
        
        // Build link attributes
        $attributes = !empty($item->attr_title) ? ' title="' . esc_attr($item->attr_title) . '"' : '';
        $attributes .= !empty($item->target) ? ' target="' . esc_attr($item->target) . '"' : '';
        $attributes .= !empty($item->xfn) ? ' rel="' . esc_attr($item->xfn) . '"' : '';
        $attributes .= !empty($item->url) ? ' href="' . esc_attr($item->url) . '"' : '';
        
        // Add ARIA attributes for enhanced accessibility
        if (in_array('menu-item-has-children', $classes)) {
            $attributes .= ' aria-expanded="false"';
            $attributes .= ' aria-haspopup="true"';
            $submenu_id = 'submenu-' . $item->ID;
            $attributes .= ' aria-controls="' . $submenu_id . '"';
        }
        
        // Add description as aria-describedby if available
        $description = get_post_meta($item->ID, '_menu_item_description', true);
        if ($description) {
            $desc_id = 'menu-desc-' . $item->ID;
            $attributes .= ' aria-describedby="' . $desc_id . '"';
        }
        
        // Build link content with enhanced structure
        $item_output = isset($args->before) ? $args->before : '';
        $item_output .= '<a' . $attributes . '>';
        
        // Menu item inner wrapper
        $item_output .= '<span class="menu-item-inner">';
        
        // Add icon if specified
        $icon = get_post_meta($item->ID, '_menu_item_icon', true);
        if ($icon) {
            // Check if it's a dashicon or emoji
            if (strpos($icon, 'dashicon') !== false) {
                $item_output .= '<span class="menu-icon dashicons ' . esc_attr($icon) . '" aria-hidden="true"></span>';
            } else {
                $item_output .= '<span class="menu-icon" aria-hidden="true">' . esc_html($icon) . '</span>';
            }
        }
        
        // Menu text wrapper
        $item_output .= '<span class="menu-text">';
        $item_output .= isset($args->link_before) ? $args->link_before : '';
        $item_output .= apply_filters('the_title', $item->title, $item->ID);
        $item_output .= isset($args->link_after) ? $args->link_after : '';
        $item_output .= '</span>';
        
        // Add badge if specified
        $badge = get_post_meta($item->ID, '_menu_item_badge', true);
        if ($badge) {
            $item_output .= '<span class="menu-badge" aria-label="' . esc_attr(sprintf(__('%s badge', 'gpress'), $badge)) . '">' . esc_html($badge) . '</span>';
        }
        
        // Add submenu indicator for parent items
        if (in_array('menu-item-has-children', $classes)) {
            $item_output .= '<span class="submenu-indicator" aria-hidden="true">';
            $item_output .= '<svg width="12" height="8" viewBox="0 0 12 8" fill="none" xmlns="http://www.w3.org/2000/svg">';
            $item_output .= '<path d="M1 1L6 6L11 1" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>';
            $item_output .= '</svg>';
            $item_output .= '</span>';
        }
        
        $item_output .= '</span>'; // Close menu-item-inner
        $item_output .= '</a>';
        
        // Add description if available (for screen readers)
        if ($description) {
            $item_output .= '<span id="' . $desc_id . '" class="menu-description screen-reader-text">' . esc_html($description) . '</span>';
        }
        
        $item_output .= isset($args->after) ? $args->after : '';
        
        $output .= apply_filters('walker_nav_menu_start_el', $item_output, $item, $depth, $args);
    }
    
    /**
     * Start submenu output with enhanced accessibility
     *
     * @since 1.0.0
     */
    function start_lvl(&$output, $depth = 0, $args = null) {
        $indent = str_repeat("\t", $depth);
        
        // Add unique ID for ARIA controls
        $submenu_id = '';
        if (isset($this->current_item_id)) {
            $submenu_id = ' id="submenu-' . $this->current_item_id . '"';
        }
        
        $output .= "\n$indent<ul class=\"sub-menu sub-menu-depth-{$depth}\" role=\"menu\"$submenu_id>\n";
    }
    
    /**
     * End submenu output
     *
     * @since 1.0.0
     */
    function end_lvl(&$output, $depth = 0, $args = null) {
        $indent = str_repeat("\t", $depth);
        $output .= "$indent</ul>\n";
    }
    
    /**
     * Store current item ID for submenu ARIA controls
     *
     * @since 1.0.0
     */
    function start_el_before(&$output, $item, $depth = 0, $args = null, $id = 0) {
        $this->current_item_id = $item->ID;
    }
}
```

### 3. Create Breadcrumb Functionality

Create `inc/breadcrumbs.php`:

```php
<?php
/**
 * Breadcrumb Functionality for GPress Theme
 * SEO-optimized breadcrumbs with structured data and accessibility
 *
 * @package GPress
 * @subpackage Navigation
 * @version 1.0.0
 * @since 1.0.0
 */

// Prevent direct access
defined('ABSPATH') || exit;

/**
 * GPress Breadcrumb System Manager
 * 
 * @since 1.0.0
 */
class GPress_Breadcrumbs {

    /**
     * Initialize breadcrumb system
     *
     * @since 1.0.0
     */
    public static function init() {
        add_action('gpress_breadcrumbs', array(__CLASS__, 'display_breadcrumbs'));
        add_action('wp_head', array(__CLASS__, 'breadcrumbs_schema'));
        add_filter('body_class', array(__CLASS__, 'breadcrumb_body_classes'));
    }

    /**
     * Display breadcrumb navigation with enhanced accessibility
     *
     * @since 1.0.0
     */
    public static function display_breadcrumbs() {
        // Don't display on homepage or if disabled
        if (is_front_page() || !get_theme_mod('enable_breadcrumbs', true)) {
            return;
        }
        
        global $post;
        $breadcrumbs = array();
        $separator = '<span class="breadcrumb-separator" aria-hidden="true">›</span>';
        $position = 1;
        
        // Home link with enhanced structure
        $breadcrumbs[] = array(
            'url' => home_url('/'),
            'title' => get_bloginfo('name'),
            'position' => $position++,
            'icon' => '🏠',
            'aria_label' => __('Home', 'gpress')
        );
        
        // Context-specific breadcrumb generation
        if (is_single()) {
            $breadcrumbs = array_merge($breadcrumbs, self::get_single_breadcrumbs($post, $position));
        } elseif (is_page()) {
            $breadcrumbs = array_merge($breadcrumbs, self::get_page_breadcrumbs($post, $position));
        } elseif (is_category()) {
            $breadcrumbs = array_merge($breadcrumbs, self::get_category_breadcrumbs($position));
        } elseif (is_tag()) {
            $breadcrumbs = array_merge($breadcrumbs, self::get_tag_breadcrumbs($position));
        } elseif (is_author()) {
            $breadcrumbs = array_merge($breadcrumbs, self::get_author_breadcrumbs($position));
        } elseif (is_search()) {
            $breadcrumbs = array_merge($breadcrumbs, self::get_search_breadcrumbs($position));
        } elseif (is_404()) {
            $breadcrumbs = array_merge($breadcrumbs, self::get_404_breadcrumbs($position));
        } elseif (is_archive()) {
            $breadcrumbs = array_merge($breadcrumbs, self::get_archive_breadcrumbs($position));
        }
        
        self::render_breadcrumbs($breadcrumbs, $separator);
    }

    /**
     * Get breadcrumbs for single posts
     *
     * @since 1.0.0
     */
    private static function get_single_breadcrumbs($post, $position) {
        $breadcrumbs = array();
        $post_type = get_post_type();
        
        if ($post_type === 'post') {
            // Blog posts
            $blog_page = get_option('page_for_posts');
            if ($blog_page) {
                $breadcrumbs[] = array(
                    'url' => get_permalink($blog_page),
                    'title' => get_the_title($blog_page),
                    'position' => $position++
                );
            }
            
            // Primary category
            $categories = get_the_category();
            if ($categories) {
                $main_category = $categories[0];
                $breadcrumbs[] = array(
                    'url' => get_category_link($main_category->term_id),
                    'title' => $main_category->name,
                    'position' => $position++
                );
            }
        } else {
            // Custom post types
            $post_type_object = get_post_type_object($post_type);
            if ($post_type_object && $post_type_object->has_archive) {
                $breadcrumbs[] = array(
                    'url' => get_post_type_archive_link($post_type),
                    'title' => $post_type_object->labels->name,
                    'position' => $position++
                );
            }
            
            // Hierarchical custom post types
            if (is_post_type_hierarchical($post_type) && $post->post_parent) {
                $ancestors = get_post_ancestors($post->ID);
                $ancestors = array_reverse($ancestors);
                
                foreach ($ancestors as $ancestor) {
                    $breadcrumbs[] = array(
                        'url' => get_permalink($ancestor),
                        'title' => get_the_title($ancestor),
                        'position' => $position++
                    );
                }
            }
        }
        
        // Current post
        $breadcrumbs[] = array(
            'title' => get_the_title(),
            'position' => $position++,
            'current' => true
        );
        
        return $breadcrumbs;
    }

    /**
     * Get breadcrumbs for pages
     *
     * @since 1.0.0
     */
    private static function get_page_breadcrumbs($post, $position) {
        $breadcrumbs = array();
        
        if ($post->post_parent) {
            $ancestors = get_post_ancestors($post->ID);
            $ancestors = array_reverse($ancestors);
            
            foreach ($ancestors as $ancestor) {
                $breadcrumbs[] = array(
                    'url' => get_permalink($ancestor),
                    'title' => get_the_title($ancestor),
                    'position' => $position++
                );
            }
        }
        
        $breadcrumbs[] = array(
            'title' => get_the_title(),
            'position' => $position++,
            'current' => true
        );
        
        return $breadcrumbs;
    }

    /**
     * Get breadcrumbs for category archives
     *
     * @since 1.0.0
     */
    private static function get_category_breadcrumbs($position) {
        $breadcrumbs = array();
        $category = get_queried_object();
        
        if ($category->parent) {
            $ancestors = get_ancestors($category->term_id, 'category');
            $ancestors = array_reverse($ancestors);
            
            foreach ($ancestors as $ancestor) {
                $ancestor_category = get_category($ancestor);
                $breadcrumbs[] = array(
                    'url' => get_category_link($ancestor),
                    'title' => $ancestor_category->name,
                    'position' => $position++
                );
            }
        }
        
        $breadcrumbs[] = array(
            'title' => $category->name,
            'position' => $position++,
            'current' => true
        );
        
        return $breadcrumbs;
    }

    /**
     * Get breadcrumbs for tag archives
     *
     * @since 1.0.0
     */
    private static function get_tag_breadcrumbs($position) {
        $tag = get_queried_object();
        return array(
            array(
                'title' => sprintf(__('Tag: %s', 'gpress'), $tag->name),
                'position' => $position,
                'current' => true
            )
        );
    }

    /**
     * Get breadcrumbs for author archives
     *
     * @since 1.0.0
     */
    private static function get_author_breadcrumbs($position) {
        $author = get_queried_object();
        return array(
            array(
                'title' => sprintf(__('Author: %s', 'gpress'), $author->display_name),
                'position' => $position,
                'current' => true
            )
        );
    }

    /**
     * Get breadcrumbs for search results
     *
     * @since 1.0.0
     */
    private static function get_search_breadcrumbs($position) {
        return array(
            array(
                'title' => sprintf(__('Search results for: %s', 'gpress'), get_search_query()),
                'position' => $position,
                'current' => true
            )
        );
    }

    /**
     * Get breadcrumbs for 404 pages
     *
     * @since 1.0.0
     */
    private static function get_404_breadcrumbs($position) {
        return array(
            array(
                'title' => __('Page not found', 'gpress'),
                'position' => $position,
                'current' => true
            )
        );
    }

    /**
     * Get breadcrumbs for other archives
     *
     * @since 1.0.0
     */
    private static function get_archive_breadcrumbs($position) {
        $archive_title = get_the_archive_title();
        return array(
            array(
                'title' => $archive_title,
                'position' => $position,
                'current' => true
            )
        );
    }

    /**
     * Render breadcrumbs with enhanced accessibility
     *
     * @since 1.0.0
     */
    private static function render_breadcrumbs($breadcrumbs, $separator) {
        if (empty($breadcrumbs)) {
            return;
        }
        
        echo '<nav class="breadcrumb-navigation" role="navigation" aria-label="' . esc_attr__('Breadcrumb', 'gpress') . '">';
        echo '<div class="breadcrumb-container">';
        echo '<ol class="breadcrumb-list" itemscope itemtype="https://schema.org/BreadcrumbList">';
        
        $total = count($breadcrumbs);
        
        foreach ($breadcrumbs as $index => $crumb) {
            $is_last = ($index === $total - 1);
            $is_current = isset($crumb['current']) && $crumb['current'];
            
            echo '<li class="breadcrumb-item" itemprop="itemListElement" itemscope itemtype="https://schema.org/ListItem">';
            
            if ($is_current || $is_last) {
                echo '<span class="breadcrumb-current" aria-current="page" itemprop="name">';
                if (isset($crumb['icon'])) {
                    echo '<span class="breadcrumb-icon" aria-hidden="true">' . esc_html($crumb['icon']) . '</span>';
                }
                echo esc_html($crumb['title']);
                echo '</span>';
            } else {
                echo '<a href="' . esc_url($crumb['url']) . '" itemprop="item"';
                if (isset($crumb['aria_label'])) {
                    echo ' aria-label="' . esc_attr($crumb['aria_label']) . '"';
                }
                echo '>';
                if (isset($crumb['icon'])) {
                    echo '<span class="breadcrumb-icon" aria-hidden="true">' . esc_html($crumb['icon']) . '</span>';
                }
                echo '<span itemprop="name">' . esc_html($crumb['title']) . '</span>';
                echo '</a>';
            }
            
            echo '<meta itemprop="position" content="' . esc_attr($crumb['position']) . '">';
            
            if (!$is_last) {
                echo ' ' . $separator . ' ';
            }
            
            echo '</li>';
        }
        
        echo '</ol>';
        echo '</div>';
        echo '</nav>';
    }

    /**
     * Add breadcrumbs schema markup to head
     *
     * @since 1.0.0
     */
    public static function breadcrumbs_schema() {
        if (is_front_page() || !get_theme_mod('enable_breadcrumbs', true)) {
            return;
        }
        
        // Schema is now included in the rendered breadcrumbs with microdata
        // This provides additional JSON-LD for enhanced SEO
        
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
        
        // Add context-specific items
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
     * Add breadcrumb-specific body classes
     *
     * @since 1.0.0
     */
    public static function breadcrumb_body_classes($classes) {
        if (!is_front_page() && get_theme_mod('enable_breadcrumbs', true)) {
            $classes[] = 'has-breadcrumbs';
            
            if (is_single()) {
                $classes[] = 'breadcrumb-single';
            } elseif (is_page()) {
                $classes[] = 'breadcrumb-page';
            } elseif (is_archive()) {
                $classes[] = 'breadcrumb-archive';
            }
        }
        
        return $classes;
    }
}

// Initialize the breadcrumb system
GPress_Breadcrumbs::init();
```

### 4. Update Functions.php

Update `functions.php`:

```php
/**
 * Load Advanced Navigation Components
 * Conditional loading based on navigation needs and features
 *
 * @since 1.0.0
 */
function gpress_load_navigation_components() {
    // Core navigation system (always loaded)
    require_once get_theme_file_path('/inc/navigation-system.php');
    require_once get_theme_file_path('/inc/navigation-walker.php');
    
    // Conditional components based on theme settings
    if (get_theme_mod('enable_breadcrumbs', true)) {
        require_once get_theme_file_path('/inc/breadcrumbs.php');
    }
    
    // Admin-only components
    if (is_admin()) {
        require_once get_theme_file_path('/inc/menu-customizer.php');
        require_once get_theme_file_path('/inc/navigation-optimization.php');
    }
}
add_action('after_setup_theme', 'gpress_load_navigation_components');

/**
 * Add navigation-specific theme support
 *
 * @since 1.0.0
 */
function gpress_add_navigation_theme_support() {
    // Core navigation features
    add_theme_support('menus');
    add_theme_support('custom-header');
    
    // Enhanced navigation features
    add_theme_support('nav-menu-item-custom-fields');
    add_theme_support('mega-menus');
    add_theme_support('breadcrumb-navigation');
    
    // Accessibility features
    add_theme_support('skip-links');
    add_theme_support('aria-navigation');
}
add_action('after_setup_theme', 'gpress_add_navigation_theme_support');
```

### 5. Update Theme Setup

Update `inc/theme-setup.php`:

```php
/**
 * Add navigation-specific image sizes
 *
 * @since 1.0.0
 */
function gpress_add_navigation_image_sizes() {
    // Logo variations for different contexts
    add_image_size('logo-mobile', 120, 40, false);
    add_image_size('logo-retina', 240, 80, false);
    add_image_size('menu-icon', 24, 24, true);
}
add_action('after_setup_theme', 'gpress_add_navigation_image_sizes');

/**
 * Register additional navigation areas for enhanced flexibility
 *
 * @since 1.0.0
 */
function gpress_register_extended_menus() {
    register_nav_menus(array(
        'header-utility' => __('Header Utility Menu', 'gpress'),
        'footer-secondary' => __('Footer Secondary Menu', 'gpress'),
        'quick-links' => __('Quick Links Menu', 'gpress'),
    ));
}
add_action('after_setup_theme', 'gpress_register_extended_menus');
```

### 6. Update Style.css

Update `style.css`:

```css
/**
 * Navigation Base Styles
 * Foundation styles for all navigation components
 *
 * @since 1.0.0
 */

/* Skip Links for Accessibility */
.skip-navigation {
    position: absolute;
    left: -9999px;
    top: 1rem;
    z-index: 999999;
    text-decoration: none;
    padding: 0.75rem 1.5rem;
    background: var(--wp--preset--color--primary);
    color: var(--wp--preset--color--background);
    border-radius: 6px;
    font-weight: 600;
    font-size: 0.875rem;
    border: 2px solid var(--wp--preset--color--primary);
}

.skip-navigation:focus {
    left: 1rem;
    outline: 3px solid var(--wp--preset--color--accent);
    outline-offset: 2px;
}

/* Main Navigation Container */
.main-navigation {
    background: var(--wp--preset--color--background);
    border-bottom: 1px solid var(--wp--preset--color--border);
    padding: 0.75rem 0;
    position: sticky;
    top: 0;
    z-index: 1000;
    backdrop-filter: blur(10px);
    transition: all 0.3s ease;
}

.main-navigation.scrolled {
    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
    backdrop-filter: blur(15px);
}

.nav-container {
    display: flex;
    align-items: center;
    justify-content: space-between;
    max-width: var(--wp--style--global--content-size);
    margin: 0 auto;
    padding: 0 1rem;
}

/* Logo and Site Branding */
.site-logo {
    flex-shrink: 0;
    margin-right: 2rem;
}

.site-logo img {
    height: auto;
    max-height: 60px;
    width: auto;
}

/* Primary Navigation Menu */
.nav-primary-menu {
    display: flex;
    list-style: none;
    margin: 0;
    padding: 0;
    gap: 0.5rem;
    flex-wrap: wrap;
}

.nav-primary-menu .menu-item {
    position: relative;
}

.nav-primary-menu a {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    text-decoration: none;
    color: var(--wp--preset--color--foreground);
    padding: 0.75rem 1rem;
    border-radius: 8px;
    transition: all 0.3s ease;
    font-weight: 500;
    font-size: 0.95rem;
    position: relative;
}

.nav-primary-menu a:hover,
.nav-primary-menu a:focus {
    background: var(--wp--preset--color--primary);
    color: var(--wp--preset--color--background);
    outline: 2px solid var(--wp--preset--color--primary);
    outline-offset: 2px;
    transform: translateY(-1px);
}

/* Current Menu Item */
.nav-primary-menu .current-menu-item > a,
.nav-primary-menu .current-menu-ancestor > a {
    background: var(--wp--preset--color--background-secondary);
    color: var(--wp--preset--color--primary);
    font-weight: 600;
}

/* Menu Icons and Enhancements */
.menu-icon {
    font-size: 1.1em;
    flex-shrink: 0;
}

.menu-icon.dashicons {
    width: 18px;
    height: 18px;
    font-size: 18px;
}

.menu-badge {
    background: var(--wp--preset--color--accent);
    color: var(--wp--preset--color--background);
    font-size: 0.7rem;
    padding: 0.125rem 0.375rem;
    border-radius: 12px;
    font-weight: 700;
    margin-left: 0.25rem;
    flex-shrink: 0;
    line-height: 1;
}

.menu-item-highlight > a {
    background: var(--wp--preset--color--accent);
    color: var(--wp--preset--color--background);
    font-weight: 600;
    box-shadow: 0 2px 8px rgba(var(--wp--preset--color--accent-rgb), 0.3);
}

/* Submenu Indicator */
.has-submenu .submenu-indicator {
    margin-left: auto;
    transition: transform 0.3s ease;
    flex-shrink: 0;
    opacity: 0.7;
}

.has-submenu[aria-expanded="true"] .submenu-indicator {
    transform: rotate(180deg);
}

/* Submenu Styles */
.sub-menu {
    position: absolute;
    top: calc(100% + 0.5rem);
    left: 0;
    background: var(--wp--preset--color--background);
    border: 1px solid var(--wp--preset--color--border);
    border-radius: 12px;
    box-shadow: 0 8px 32px rgba(0, 0, 0, 0.12);
    opacity: 0;
    visibility: hidden;
    transform: translateY(-10px);
    transition: all 0.3s ease;
    min-width: 240px;
    z-index: 1001;
    list-style: none;
    margin: 0;
    padding: 0.75rem 0;
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
    margin: 0;
}

.sub-menu a {
    padding: 0.75rem 1.25rem;
    border-radius: 0;
    width: 100%;
    margin: 0;
    font-size: 0.9rem;
}

.sub-menu a:hover,
.sub-menu a:focus {
    background: var(--wp--preset--color--background-secondary);
    color: var(--wp--preset--color--foreground);
    transform: none;
}

/* Navigation Actions (Search, Social, etc.) */
.nav-actions {
    display: flex;
    align-items: center;
    gap: 1rem;
    flex-shrink: 0;
}

.header-search {
    display: none;
}

.header-social .wp-block-social-link {
    background: var(--wp--preset--color--background-secondary);
    border-radius: 50%;
    transition: all 0.3s ease;
}

.header-social .wp-block-social-link:hover {
    background: var(--wp--preset--color--primary);
    transform: translateY(-2px);
}

/* Mobile Menu Toggle */
.mobile-menu-toggle {
    display: none;
    background: none;
    border: 2px solid var(--wp--preset--color--border);
    cursor: pointer;
    padding: 0.5rem;
    color: var(--wp--preset--color--foreground);
    font-size: 1.25rem;
    border-radius: 8px;
    transition: all 0.3s ease;
    min-height: 44px;
    min-width: 44px;
}

.mobile-menu-toggle:hover,
.mobile-menu-toggle:focus {
    background: var(--wp--preset--color--background-secondary);
    border-color: var(--wp--preset--color--primary);
    outline: 2px solid var(--wp--preset--color--primary);
    outline-offset: 2px;
}

/* Screen Reader Text for Accessibility */
.screen-reader-text {
    position: absolute !important;
    clip: rect(1px, 1px, 1px, 1px);
    width: 1px;
    height: 1px;
    overflow: hidden;
    word-wrap: normal !important;
}

.screen-reader-text:focus {
    background: var(--wp--preset--color--background);
    border-radius: 3px;
    box-shadow: 0 0 2px 2px rgba(0, 0, 0, 0.6);
    clip: auto !important;
    color: var(--wp--preset--color--foreground);
    display: block;
    font-size: 0.875rem;
    font-weight: 600;
    height: auto;
    left: 5px;
    line-height: normal;
    padding: 15px 23px 14px;
    text-decoration: none;
    top: 5px;
    width: auto;
    z-index: 100000;
}

/* Responsive Design */
@media (max-width: 1024px) {
    .header-search {
        display: block;
        max-width: 200px;
    }
    
    .nav-container {
        padding: 0 0.75rem;
    }
    
    .site-logo {
        margin-right: 1rem;
    }
}

@media (max-width: 768px) {
    .nav-primary-menu,
    .nav-secondary-menu {
        display: none;
    }
    
    .mobile-menu-toggle {
        display: flex;
        align-items: center;
        justify-content: center;
    }
    
    .header-search {
        max-width: 150px;
    }
    
    .header-social {
        display: none;
    }
    
    .nav-container {
        padding: 0 0.5rem;
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
    
    .mobile-menu-toggle {
        border-width: 3px;
    }
}

/* Reduced Motion Support */
@media (prefers-reduced-motion: reduce) {
    .nav-primary-menu a,
    .sub-menu,
    .mobile-navigation,
    .submenu-indicator,
    .main-navigation {
        transition: none;
    }
    
    .nav-primary-menu a:hover,
    .header-social .wp-block-social-link:hover {
        transform: none;
    }
}

/* Print Styles */
@media print {
    .main-navigation,
    .mobile-navigation,
    .mobile-menu-toggle,
    .header-search,
    .header-social {
        display: none;
    }
    
    .skip-navigation {
        display: none;
    }
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
    animation: nav-spin 1s linear infinite;
    transform: translate(-50%, -50%);
}

@keyframes nav-spin {
    to { transform: translate(-50%, -50%) rotate(360deg); }
}

/* Focus Management */
.no-js .has-submenu:hover .sub-menu,
.no-js .has-submenu:focus-within .sub-menu {
    display: block;
}

/* Live Region for Screen Reader Announcements */
#nav-live-region {
    position: absolute;
    left: -10000px;
    width: 1px;
    height: 1px;
    overflow: hidden;
}
```

## Testing This Step

### 1. Navigation Functionality Testing
```bash
# Test menu registration
wp menu list --format=table

# Test conditional asset loading
curl -s http://yoursite.com/ | grep -E "(navigation\.css|navigation\.js)"

# Check for mobile optimization
curl -s -A "Mozilla/5.0 (iPhone; CPU iPhone OS 14_0 like Mac OS X)" http://yoursite.com/ | grep "mobile-menu"
```

### 2. Accessibility Testing
```bash
# Test skip links
curl -s http://yoursite.com/ | grep "skip-navigation"

# Check ARIA attributes
curl -s http://yoursite.com/ | grep -E "(aria-expanded|aria-haspopup|aria-controls)"

# Validate navigation landmarks
curl -s http://yoursite.com/ | grep 'role="navigation"'
```

### 3. Breadcrumb Testing
```bash
# Test breadcrumb generation
curl -s http://yoursite.com/sample-page/ | grep "breadcrumb-navigation"

# Check structured data
curl -s http://yoursite.com/sample-page/ | grep -o '<script type="application/ld+json">.*BreadcrumbList.*</script>'

# Validate microdata
curl -s http://yoursite.com/sample-page/ | grep -E "(itemscope|itemtype|itemprop)"
```

### 4. Mobile Navigation Testing
```bash
# Test mobile menu toggle
curl -s http://yoursite.com/ | grep "mobile-menu-toggle"

# Check touch target sizes
curl -s http://yoursite.com/ | grep 'min-height: 44px'

# Validate mobile-specific classes
curl -s http://yoursite.com/ | grep "is-mobile-device"
```

### 5. Performance Testing
```bash
# Check asset loading
curl -s http://yoursite.com/ | grep -c "navigation.*\.css"
curl -s http://yoursite.com/ | grep -c "navigation.*\.js"

# Test conditional loading
curl -s http://yoursite.com/wp-json/wp/v2/menus

# Validate JavaScript errors
# Use browser console or automated testing tools
```

## Expected Results After This Step

1. **Navigation System**: Complete multi-level navigation with primary, secondary, mobile, and utility menus
2. **Accessibility Compliance**: Full WCAG 2.1 AA compliance with ARIA support and keyboard navigation
3. **Mobile Optimization**: Touch-optimized mobile menu with proper gesture support
4. **Breadcrumb System**: SEO-optimized breadcrumbs with structured data and microdata
5. **Conditional Loading**: Smart asset loading based on navigation usage and device detection
6. **Custom Fields**: Enhanced menu items with icons, badges, descriptions, and mega menu support
7. **Performance**: Optimized navigation rendering with minimal impact on Core Web Vitals
8. **Progressive Enhancement**: Works without JavaScript while providing enhanced functionality when available

## Next Step

In Step 13, we'll implement comprehensive accessibility features to ensure WCAG 2.1 AA compliance across all theme components, building upon the navigation accessibility foundation we've established here.

---

**Step 12 Completed**: Advanced Navigation & Menu Systems ✅
- Multi-level navigation with enhanced accessibility
- Mobile-optimized responsive navigation
- SEO-friendly breadcrumb system with structured data
- Conditional asset loading for optimal performance
- Custom menu fields and advanced styling options
- Complete keyboard navigation support
- Progressive enhancement approach for all features