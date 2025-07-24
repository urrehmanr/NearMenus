# Step 12: Navigation & Menus

## Overview
This step implements advanced navigation and menu systems with improved accessibility, mobile optimization, and modern UX patterns. We'll create multiple navigation areas, implement keyboard navigation, and ensure WCAG compliance.

## Objectives
- Register multiple navigation menus
- Create accessible navigation components
- Implement mobile-responsive navigation
- Add breadcrumb navigation
- Support mega menus
- Ensure keyboard navigation compliance

## Implementation

### 1. Menu Registration and Setup

Update `inc/theme-setup.php`:

```php
/**
 * Register navigation menus
 */
function modernblog2025_register_nav_menus() {
    register_nav_menus(array(
        'primary' => __('Primary Navigation', 'modernblog2025'),
        'secondary' => __('Secondary Navigation', 'modernblog2025'),
        'footer' => __('Footer Navigation', 'modernblog2025'),
        'social' => __('Social Media Links', 'modernblog2025'),
        'mobile' => __('Mobile Navigation', 'modernblog2025'),
        'breadcrumb' => __('Breadcrumb Navigation', 'modernblog2025')
    ));
}
add_action('after_setup_theme', 'modernblog2025_register_nav_menus');

/**
 * Enhance navigation menu support
 */
function modernblog2025_enhance_nav_menu_support() {
    // Add custom menu fields
    add_theme_support('nav-menu-item-custom-fields', array(
        'menu-item-icon',
        'menu-item-description',
        'menu-item-badge',
        'menu-item-highlight'
    ));
    
    // Add menu item custom fields
    add_filter('wp_edit_nav_menu_walker', function() {
        return 'ModernBlog2025_Walker_Nav_Menu_Edit';
    });
}
add_action('after_setup_theme', 'modernblog2025_enhance_nav_menu_support');
```

### 2. Custom Navigation Walker

Create `inc/navigation-walker.php`:

```php
<?php
/**
 * Custom Navigation Walker
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Custom walker for navigation menus
 */
class ModernBlog2025_Walker_Nav_Menu extends Walker_Nav_Menu {
    
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
        if ($item->menu_item_highlight) {
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
        if ($item->menu_item_icon) {
            $item_output .= '<span class="menu-icon" aria-hidden="true">' . esc_html($item->menu_item_icon) . '</span>';
        }
        
        $item_output .= '<span class="menu-text">';
        $item_output .= isset($args->link_before) ? $args->link_before : '';
        $item_output .= apply_filters('the_title', $item->title, $item->ID);
        $item_output .= isset($args->link_after) ? $args->link_after : '';
        $item_output .= '</span>';
        
        // Add badge if specified
        if ($item->menu_item_badge) {
            $item_output .= '<span class="menu-badge" aria-label="' . esc_attr($item->menu_item_badge) . '">' . esc_html($item->menu_item_badge) . '</span>';
        }
        
        // Add submenu indicator
        if (in_array('menu-item-has-children', $classes)) {
            $item_output .= '<span class="submenu-indicator" aria-hidden="true">⌄</span>';
        }
        
        $item_output .= '</a>';
        
        // Add description if available
        if ($item->menu_item_description) {
            $item_output .= '<span class="menu-description">' . esc_html($item->menu_item_description) . '</span>';
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

/**
 * Admin walker for menu editing
 */
class ModernBlog2025_Walker_Nav_Menu_Edit extends Walker_Nav_Menu_Edit {
    
    /**
     * Start element output in admin
     */
    function start_el(&$output, $item, $depth = 0, $args = null, $id = 0) {
        parent::start_el($output, $item, $depth, $args, $id);
        
        // Add custom fields to menu item
        $item_id = esc_attr($item->ID);
        
        ob_start();
        ?>
        <div class="modernblog2025-menu-options">
            <p class="field-icon description description-wide">
                <label for="edit-menu-item-icon-<?php echo $item_id; ?>">
                    <?php _e('Icon (emoji or text)', 'modernblog2025'); ?><br>
                    <input type="text" id="edit-menu-item-icon-<?php echo $item_id; ?>" 
                           class="widefat edit-menu-item-icon" name="menu-item-icon[<?php echo $item_id; ?>]" 
                           value="<?php echo esc_attr($item->menu_item_icon); ?>" />
                    <small><?php _e('Add an emoji or text icon (e.g., 🏠, ★)', 'modernblog2025'); ?></small>
                </label>
            </p>
            
            <p class="field-description description description-wide">
                <label for="edit-menu-item-description-<?php echo $item_id; ?>">
                    <?php _e('Description', 'modernblog2025'); ?><br>
                    <textarea id="edit-menu-item-description-<?php echo $item_id; ?>" 
                              class="widefat edit-menu-item-description" 
                              rows="3" cols="20" name="menu-item-description[<?php echo $item_id; ?>]"><?php echo esc_textarea($item->menu_item_description); ?></textarea>
                    <small><?php _e('Brief description for accessibility and mega menus', 'modernblog2025'); ?></small>
                </label>
            </p>
            
            <p class="field-badge description description-thin">
                <label for="edit-menu-item-badge-<?php echo $item_id; ?>">
                    <?php _e('Badge Text', 'modernblog2025'); ?><br>
                    <input type="text" id="edit-menu-item-badge-<?php echo $item_id; ?>" 
                           class="widefat edit-menu-item-badge" name="menu-item-badge[<?php echo $item_id; ?>]" 
                           value="<?php echo esc_attr($item->menu_item_badge); ?>" />
                    <small><?php _e('e.g., "New", "Hot"', 'modernblog2025'); ?></small>
                </label>
            </p>
            
            <p class="field-highlight description description-thin">
                <label for="edit-menu-item-highlight-<?php echo $item_id; ?>">
                    <input type="checkbox" id="edit-menu-item-highlight-<?php echo $item_id; ?>" 
                           value="1" name="menu-item-highlight[<?php echo $item_id; ?>]" 
                           <?php checked($item->menu_item_highlight, 1); ?> />
                    <?php _e('Highlight this item', 'modernblog2025'); ?>
                </label>
            </p>
        </div>
        <?php
        
        $custom_fields = ob_get_clean();
        $output = str_replace('</div></li>', $custom_fields . '</div></li>', $output);
    }
}

/**
 * Save custom menu item fields
 */
function modernblog2025_save_menu_item_custom_fields($menu_id, $menu_item_db_id) {
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
add_action('wp_update_nav_menu_item', 'modernblog2025_save_menu_item_custom_fields', 10, 2);

/**
 * Load custom menu item fields
 */
function modernblog2025_load_menu_item_custom_fields($menu_item) {
    $menu_item->menu_item_icon = get_post_meta($menu_item->ID, '_menu_item_icon', true);
    $menu_item->menu_item_description = get_post_meta($menu_item->ID, '_menu_item_description', true);
    $menu_item->menu_item_badge = get_post_meta($menu_item->ID, '_menu_item_badge', true);
    $menu_item->menu_item_highlight = get_post_meta($menu_item->ID, '_menu_item_highlight', true);
    
    return $menu_item;
}
add_filter('wp_setup_nav_menu_item', 'modernblog2025_load_menu_item_custom_fields');
```

### 3. Navigation Template Parts

Update `parts/navigation.html`:

```html
<!-- wp:group {"className":"main-navigation","layout":{"type":"flex","justifyContent":"space-between","flexWrap":"wrap"}} -->
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
                    <span class="sr-only">Menu</span>
                </button>
            </div>
            <!-- /wp:button -->
        </div>
        <!-- /wp:group -->
    </div>
    <!-- /wp:group -->
    
    <!-- Mobile menu overlay -->
    <!-- wp:group {"className":"mobile-menu-overlay","layout":{"type":"constrained"}} -->
    <div class="wp-block-group mobile-menu-overlay" id="mobile-menu" role="dialog" aria-modal="true" aria-labelledby="mobile-menu-title">
        <!-- wp:group {"className":"mobile-menu-header","layout":{"type":"flex","justifyContent":"space-between"}} -->
        <div class="wp-block-group mobile-menu-header">
            <!-- wp:heading {"level":2,"className":"mobile-menu-title","fontSize":"large"} -->
            <h2 class="wp-block-heading mobile-menu-title" id="mobile-menu-title">Menu</h2>
            <!-- /wp:heading -->
            
            <!-- wp:button {"className":"mobile-menu-close","fontSize":"small"} -->
            <div class="wp-block-button mobile-menu-close">
                <button class="wp-block-button__link" aria-label="Close mobile menu">
                    <span aria-hidden="true">✕</span>
                </button>
            </div>
            <!-- /wp:button -->
        </div>
        <!-- /wp:group -->
        
        <!-- wp:navigation {"ref":2,"overlayMenu":"never","layout":{"type":"flex","orientation":"vertical","setCascadingProperties":true},"className":"mobile-navigation","fontSize":"medium"} /-->
        
        <!-- wp:separator {"className":"mobile-menu-separator"} -->
        <hr class="wp-block-separator has-alpha-channel-opacity mobile-menu-separator"/>
        <!-- /wp:separator -->
        
        <!-- wp:search {"label":"Search","showLabel":false,"placeholder":"Search site...","width":100,"widthUnit":"%","buttonText":"Search","buttonPosition":"button-inside","buttonUseIcon":true,"className":"mobile-search"} /-->
    </div>
    <!-- /wp:group -->
</nav>
<!-- /wp:group -->
```

Create `parts/breadcrumbs.html`:

```html
<!-- wp:group {"className":"breadcrumb-navigation","layout":{"type":"constrained"}} -->
<nav class="wp-block-group breadcrumb-navigation" role="navigation" aria-label="Breadcrumb">
    <!-- wp:group {"className":"breadcrumb-container","layout":{"type":"flex","flexWrap":"wrap"}} -->
    <div class="wp-block-group breadcrumb-container">
        <!-- wp:paragraph {"className":"breadcrumb-item"} -->
        <p class="breadcrumb-item">
            <a href="/" aria-label="Home">🏠 <span class="sr-only">Home</span></a>
        </p>
        <!-- /wp:paragraph -->
        
        <!-- wp:paragraph {"className":"breadcrumb-separator"} -->
        <p class="breadcrumb-separator" aria-hidden="true">›</p>
        <!-- /wp:paragraph -->
        
        <!-- Dynamic breadcrumb items will be inserted here via PHP -->
    </div>
    <!-- /wp:group -->
</nav>
<!-- /wp:group -->
```

### 4. Breadcrumb Functionality

Create `inc/breadcrumbs.php`:

```php
<?php
/**
 * Breadcrumb Navigation
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Generate breadcrumb navigation
 */
function modernblog2025_breadcrumbs() {
    global $post;
    
    // Don't display on homepage
    if (is_front_page()) {
        return;
    }
    
    $breadcrumbs = array();
    $separator = '<span class="breadcrumb-separator" aria-hidden="true">›</span>';
    
    // Home link
    $breadcrumbs[] = '<a href="' . esc_url(home_url('/')) . '" aria-label="' . esc_attr__('Home', 'modernblog2025') . '">🏠 <span class="sr-only">' . __('Home', 'modernblog2025') . '</span></a>';
    
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
        $breadcrumbs[] = '<span class="breadcrumb-current" aria-current="page">' . sprintf(__('Tag: %s', 'modernblog2025'), esc_html($tag->name)) . '</span>';
        
    } elseif (is_author()) {
        // Author archives
        $author = get_queried_object();
        $breadcrumbs[] = '<span class="breadcrumb-current" aria-current="page">' . sprintf(__('Author: %s', 'modernblog2025'), esc_html($author->display_name)) . '</span>';
        
    } elseif (is_search()) {
        // Search results
        $breadcrumbs[] = '<span class="breadcrumb-current" aria-current="page">' . sprintf(__('Search results for: %s', 'modernblog2025'), esc_html(get_search_query())) . '</span>';
        
    } elseif (is_404()) {
        // 404 page
        $breadcrumbs[] = '<span class="breadcrumb-current" aria-current="page">' . __('Page not found', 'modernblog2025') . '</span>';
        
    } elseif (is_archive()) {
        // Other archives
        $archive_title = get_the_archive_title();
        $breadcrumbs[] = '<span class="breadcrumb-current" aria-current="page">' . esc_html($archive_title) . '</span>';
    }
    
    // Output breadcrumbs
    if (!empty($breadcrumbs)) {
        echo '<nav class="breadcrumb-navigation" role="navigation" aria-label="' . esc_attr__('Breadcrumb', 'modernblog2025') . '">';
        echo '<div class="breadcrumb-container">';
        echo implode(' ' . $separator . ' ', $breadcrumbs);
        echo '</div>';
        echo '</nav>';
    }
}

/**
 * Add breadcrumbs schema markup
 */
function modernblog2025_breadcrumbs_schema() {
    if (is_front_page()) {
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
    }
    
    if (!empty($breadcrumbs)) {
        $schema = array(
            '@context' => 'https://schema.org',
            '@type' => 'BreadcrumbList',
            'itemListElement' => $breadcrumbs
        );
        
        echo '<script type="application/ld+json">' . wp_json_encode($schema) . '</script>';
    }
}
add_action('wp_head', 'modernblog2025_breadcrumbs_schema');
```

### 5. Navigation CSS

Add to `assets/css/style.css`:

```css
/* Navigation Styles */
.main-navigation {
    background: var(--wp--preset--color--background);
    border-bottom: 1px solid var(--wp--preset--color--border);
    padding: 1rem 0;
    position: sticky;
    top: 0;
    z-index: 1000;
    backdrop-filter: blur(10px);
}

.nav-menu-container {
    width: 100%;
    align-items: center;
}

.primary-navigation ul {
    list-style: none;
    margin: 0;
    padding: 0;
    display: flex;
    gap: 1.5rem;
}

.primary-navigation li {
    position: relative;
}

.primary-navigation a {
    text-decoration: none;
    color: var(--wp--preset--color--foreground);
    padding: 0.5rem 1rem;
    border-radius: 4px;
    transition: all 0.3s ease;
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.primary-navigation a:hover,
.primary-navigation a:focus {
    background: var(--wp--preset--color--primary);
    color: var(--wp--preset--color--background);
    outline: 2px solid var(--wp--preset--color--primary);
    outline-offset: 2px;
}

/* Menu Icons and Badges */
.menu-icon {
    font-size: 1.1em;
}

.menu-badge {
    background: var(--wp--preset--color--accent);
    color: var(--wp--preset--color--background);
    font-size: 0.75rem;
    padding: 0.125rem 0.25rem;
    border-radius: 10px;
    font-weight: 600;
    margin-left: 0.25rem;
}

.menu-item-highlight a {
    background: var(--wp--preset--color--accent);
    color: var(--wp--preset--color--background);
}

/* Submenu Styles */
.has-submenu .submenu-indicator {
    margin-left: 0.25rem;
    transition: transform 0.3s ease;
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
    border-radius: 8px;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
    opacity: 0;
    visibility: hidden;
    transform: translateY(-10px);
    transition: all 0.3s ease;
    min-width: 200px;
    z-index: 1001;
}

.has-submenu:hover .sub-menu,
.has-submenu:focus-within .sub-menu {
    opacity: 1;
    visibility: visible;
    transform: translateY(0);
}

.sub-menu li {
    width: 100%;
}

.sub-menu a {
    padding: 0.75rem 1rem;
    border-radius: 0;
    width: 100%;
}

.sub-menu a:hover,
.sub-menu a:focus {
    background: var(--wp--preset--color--background-secondary);
    color: var(--wp--preset--color--foreground);
}

/* Mobile Navigation */
.mobile-menu-toggle {
    display: none;
}

.mobile-menu-overlay {
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

.mobile-menu-overlay.is-open {
    opacity: 1;
    visibility: visible;
}

.mobile-menu-header {
    margin-bottom: 2rem;
    padding-bottom: 1rem;
    border-bottom: 1px solid var(--wp--preset--color--border);
}

.mobile-menu-close {
    background: transparent;
    border: none;
    font-size: 1.5rem;
    cursor: pointer;
}

.mobile-navigation ul {
    list-style: none;
    margin: 0;
    padding: 0;
}

.mobile-navigation li {
    margin-bottom: 0.5rem;
}

.mobile-navigation a {
    display: block;
    padding: 1rem;
    border-radius: 8px;
    text-decoration: none;
    color: var(--wp--preset--color--foreground);
    background: var(--wp--preset--color--background-secondary);
    transition: all 0.3s ease;
}

.mobile-navigation a:hover,
.mobile-navigation a:focus {
    background: var(--wp--preset--color--primary);
    color: var(--wp--preset--color--background);
}

/* Breadcrumb Navigation */
.breadcrumb-navigation {
    background: var(--wp--preset--color--background-secondary);
    padding: 0.5rem 0;
    font-size: 0.875rem;
}

.breadcrumb-container {
    display: flex;
    align-items: center;
    flex-wrap: wrap;
    gap: 0.5rem;
}

.breadcrumb-container a {
    color: var(--wp--preset--color--foreground-secondary);
    text-decoration: none;
    transition: color 0.3s ease;
}

.breadcrumb-container a:hover,
.breadcrumb-container a:focus {
    color: var(--wp--preset--color--primary);
    text-decoration: underline;
}

.breadcrumb-current {
    color: var(--wp--preset--color--foreground);
    font-weight: 600;
}

.breadcrumb-separator {
    color: var(--wp--preset--color--foreground-secondary);
    margin: 0;
}

/* Search in Navigation */
.header-search .wp-block-search__input {
    border-radius: 20px;
    padding: 0.5rem 1rem;
    border: 1px solid var(--wp--preset--color--border);
}

.header-social {
    gap: 0.5rem;
}

.header-social a {
    color: var(--wp--preset--color--foreground-secondary);
    transition: color 0.3s ease;
}

.header-social a:hover,
.header-social a:focus {
    color: var(--wp--preset--color--primary);
}

/* Responsive Design */
@media (max-width: 768px) {
    .primary-navigation,
    .header-search,
    .header-social {
        display: none;
    }
    
    .mobile-menu-toggle {
        display: block;
    }
    
    .nav-actions {
        justify-content: flex-end;
        width: auto;
    }
    
    .breadcrumb-container {
        font-size: 0.8rem;
    }
}

/* Accessibility Enhancements */
.sr-only {
    position: absolute;
    width: 1px;
    height: 1px;
    padding: 0;
    margin: -1px;
    overflow: hidden;
    clip: rect(0, 0, 0, 0);
    white-space: nowrap;
    border: 0;
}

/* Focus management */
a:focus,
button:focus {
    outline: 2px solid var(--wp--preset--color--primary);
    outline-offset: 2px;
}

/* Skip link */
.skip-link {
    position: absolute;
    left: -9999px;
    top: 1rem;
    z-index: 999999;
    text-decoration: none;
    padding: 0.5rem 1rem;
    background: var(--wp--preset--color--primary);
    color: var(--wp--preset--color--background);
    border-radius: 4px;
}

.skip-link:focus {
    left: 1rem;
}

/* High contrast mode support */
@media (prefers-contrast: high) {
    .main-navigation {
        border-bottom-width: 2px;
    }
    
    .primary-navigation a:hover,
    .primary-navigation a:focus {
        outline-width: 3px;
    }
}

/* Reduced motion support */
@media (prefers-reduced-motion: reduce) {
    .primary-navigation a,
    .sub-menu,
    .mobile-menu-overlay {
        transition: none;
    }
}
```

### 6. Navigation JavaScript

Create `assets/js/navigation.js`:

```javascript
/**
 * Navigation functionality
 */
document.addEventListener('DOMContentLoaded', function() {
    
    // Mobile menu toggle
    const mobileToggle = document.querySelector('.mobile-menu-toggle button');
    const mobileMenu = document.querySelector('.mobile-menu-overlay');
    const mobileClose = document.querySelector('.mobile-menu-close button');
    
    function toggleMobileMenu() {
        const isOpen = mobileMenu.classList.contains('is-open');
        
        if (isOpen) {
            mobileMenu.classList.remove('is-open');
            mobileToggle.setAttribute('aria-expanded', 'false');
            document.body.style.overflow = '';
        } else {
            mobileMenu.classList.add('is-open');
            mobileToggle.setAttribute('aria-expanded', 'true');
            document.body.style.overflow = 'hidden';
            
            // Focus first menu item
            const firstMenuItem = mobileMenu.querySelector('.mobile-navigation a');
            if (firstMenuItem) {
                firstMenuItem.focus();
            }
        }
    }
    
    if (mobileToggle) {
        mobileToggle.addEventListener('click', toggleMobileMenu);
    }
    
    if (mobileClose) {
        mobileClose.addEventListener('click', toggleMobileMenu);
    }
    
    // Close mobile menu on escape key
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape' && mobileMenu.classList.contains('is-open')) {
            toggleMobileMenu();
            mobileToggle.focus();
        }
    });
    
    // Submenu keyboard navigation
    const menuItems = document.querySelectorAll('.has-submenu > a');
    
    menuItems.forEach(function(item) {
        item.addEventListener('keydown', function(e) {
            if (e.key === 'ArrowDown') {
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
        
        // Handle click/enter for submenu toggle
        item.addEventListener('click', function(e) {
            const expanded = this.getAttribute('aria-expanded') === 'true';
            this.setAttribute('aria-expanded', !expanded);
        });
    });
    
    // Submenu item navigation
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
                        const parentItem = this.closest('.has-submenu').querySelector('> a');
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
                    const parentMenuItem = this.closest('.has-submenu').querySelector('> a');
                    if (parentMenuItem) {
                        parentMenuItem.focus();
                        parentMenuItem.setAttribute('aria-expanded', 'false');
                    }
                    break;
            }
        });
    });
    
    // Close submenus when clicking outside
    document.addEventListener('click', function(e) {
        const openSubmenus = document.querySelectorAll('[aria-expanded="true"]');
        openSubmenus.forEach(function(item) {
            if (!item.contains(e.target)) {
                item.setAttribute('aria-expanded', 'false');
            }
        });
    });
    
    // Search functionality enhancement
    const searchInputs = document.querySelectorAll('.wp-block-search__input');
    
    searchInputs.forEach(function(input) {
        input.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                this.blur();
            }
        });
    });
    
    // Add loading state to search
    const searchForms = document.querySelectorAll('.wp-block-search form');
    
    searchForms.forEach(function(form) {
        form.addEventListener('submit', function() {
            const button = this.querySelector('.wp-block-search__button');
            if (button) {
                button.setAttribute('aria-busy', 'true');
                button.textContent = 'Searching...';
            }
        });
    });
});
```

### 7. Update Functions.php

Add to `functions.php`:

```php
// Navigation and breadcrumbs
require_once get_theme_file_path('/inc/navigation-walker.php');
require_once get_theme_file_path('/inc/breadcrumbs.php');
```

### 8. Enqueue Navigation Scripts

Update `inc/enqueue-scripts.php`:

```php
// Navigation script
wp_enqueue_script(
    'modernblog2025-navigation',
    get_theme_file_uri('/assets/js/navigation.min.js'),
    array(),
    MODERNBLOG2025_VERSION,
    array(
        'strategy' => 'defer',
        'in_footer' => true
    )
);
```

## Testing

1. **Menu Creation**: Create and test primary, secondary, and mobile menus
2. **Accessibility Testing**: Use screen readers and keyboard navigation
3. **Mobile Testing**: Verify mobile menu functionality across devices
4. **Breadcrumb Testing**: Check breadcrumb generation on various page types
5. **Performance Testing**: Ensure navigation doesn't impact Core Web Vitals

## Next Steps

In Step 13, we'll implement comprehensive accessibility features to ensure WCAG 2.1 AA compliance.

## Key Benefits

- Fully accessible navigation with ARIA support
- Mobile-optimized responsive design
- Keyboard navigation compliance
- SEO-friendly breadcrumb structure
- Advanced menu customization options
- Performance-optimized implementation