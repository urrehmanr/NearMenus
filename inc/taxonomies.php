<?php
/**
 * Custom Taxonomies for Restaurant Classification
 *
 * @package NearMenus
 * @since 1.0.0
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

// Cuisine Type Taxonomy
function nearmenus_register_cuisine_taxonomy() {
    $labels = array(
        'name' => _x('Cuisines', 'taxonomy general name', 'nearmenus'),
        'singular_name' => _x('Cuisine', 'taxonomy singular name', 'nearmenus'),
        'search_items' => __('Search Cuisines', 'nearmenus'),
        'all_items' => __('All Cuisines', 'nearmenus'),
        'parent_item' => __('Parent Cuisine', 'nearmenus'),
        'parent_item_colon' => __('Parent Cuisine:', 'nearmenus'),
        'edit_item' => __('Edit Cuisine', 'nearmenus'),
        'update_item' => __('Update Cuisine', 'nearmenus'),
        'add_new_item' => __('Add New Cuisine', 'nearmenus'),
        'new_item_name' => __('New Cuisine Name', 'nearmenus'),
        'menu_name' => __('Cuisines', 'nearmenus'),
        'view_item' => __('View Cuisine', 'nearmenus'),
        'popular_items' => __('Popular Cuisines', 'nearmenus'),
        'separate_items_with_commas' => __('Separate cuisines with commas', 'nearmenus'),
        'add_or_remove_items' => __('Add or remove cuisines', 'nearmenus'),
        'choose_from_most_used' => __('Choose from the most used cuisines', 'nearmenus'),
        'not_found' => __('No cuisines found.', 'nearmenus'),
    );

    $args = array(
        'hierarchical' => true,
        'labels' => $labels,
        'show_ui' => true,
        'show_admin_column' => true,
        'query_var' => true,
        'rewrite' => array('slug' => 'cuisine'),
        'show_in_rest' => true,
        'meta_box_cb' => 'post_categories_meta_box',
        'public' => true,
        'show_in_nav_menus' => true,
        'show_tagcloud' => true,
    );

    register_taxonomy('cuisine', array('post'), $args);
}
add_action('init', 'nearmenus_register_cuisine_taxonomy');

// Location Taxonomy
function nearmenus_register_location_taxonomy() {
    $labels = array(
        'name' => _x('Locations', 'taxonomy general name', 'nearmenus'),
        'singular_name' => _x('Location', 'taxonomy singular name', 'nearmenus'),
        'search_items' => __('Search Locations', 'nearmenus'),
        'all_items' => __('All Locations', 'nearmenus'),
        'parent_item' => __('Parent Location', 'nearmenus'),
        'parent_item_colon' => __('Parent Location:', 'nearmenus'),
        'edit_item' => __('Edit Location', 'nearmenus'),
        'update_item' => __('Update Location', 'nearmenus'),
        'add_new_item' => __('Add New Location', 'nearmenus'),
        'new_item_name' => __('New Location Name', 'nearmenus'),
        'menu_name' => __('Locations', 'nearmenus'),
        'view_item' => __('View Location', 'nearmenus'),
        'popular_items' => __('Popular Locations', 'nearmenus'),
        'separate_items_with_commas' => __('Separate locations with commas', 'nearmenus'),
        'add_or_remove_items' => __('Add or remove locations', 'nearmenus'),
        'choose_from_most_used' => __('Choose from the most used locations', 'nearmenus'),
        'not_found' => __('No locations found.', 'nearmenus'),
    );

    $args = array(
        'hierarchical' => true,
        'labels' => $labels,
        'show_ui' => true,
        'show_admin_column' => true,
        'query_var' => true,
        'rewrite' => array('slug' => 'location'),
        'show_in_rest' => true,
        'meta_box_cb' => 'post_categories_meta_box',
        'public' => true,
        'show_in_nav_menus' => true,
        'show_tagcloud' => true,
    );

    register_taxonomy('location', array('post'), $args);
}
add_action('init', 'nearmenus_register_location_taxonomy');

// Price Range Taxonomy
function nearmenus_register_price_range_taxonomy() {
    $labels = array(
        'name' => _x('Price Ranges', 'taxonomy general name', 'nearmenus'),
        'singular_name' => _x('Price Range', 'taxonomy singular name', 'nearmenus'),
        'search_items' => __('Search Price Ranges', 'nearmenus'),
        'all_items' => __('All Price Ranges', 'nearmenus'),
        'edit_item' => __('Edit Price Range', 'nearmenus'),
        'update_item' => __('Update Price Range', 'nearmenus'),
        'add_new_item' => __('Add New Price Range', 'nearmenus'),
        'new_item_name' => __('New Price Range Name', 'nearmenus'),
        'menu_name' => __('Price Ranges', 'nearmenus'),
        'view_item' => __('View Price Range', 'nearmenus'),
        'popular_items' => __('Popular Price Ranges', 'nearmenus'),
        'separate_items_with_commas' => __('Separate price ranges with commas', 'nearmenus'),
        'add_or_remove_items' => __('Add or remove price ranges', 'nearmenus'),
        'choose_from_most_used' => __('Choose from the most used price ranges', 'nearmenus'),
        'not_found' => __('No price ranges found.', 'nearmenus'),
    );

    $args = array(
        'hierarchical' => false,
        'labels' => $labels,
        'show_ui' => true,
        'show_admin_column' => true,
        'query_var' => true,
        'rewrite' => array('slug' => 'price-range'),
        'show_in_rest' => true,
        'meta_box_cb' => 'post_tags_meta_box',
        'public' => true,
        'show_in_nav_menus' => true,
        'show_tagcloud' => true,
    );

    register_taxonomy('price_range', array('post'), $args);
}
add_action('init', 'nearmenus_register_price_range_taxonomy');

// Features Taxonomy
function nearmenus_register_features_taxonomy() {
    $labels = array(
        'name' => _x('Features', 'taxonomy general name', 'nearmenus'),
        'singular_name' => _x('Feature', 'taxonomy singular name', 'nearmenus'),
        'search_items' => __('Search Features', 'nearmenus'),
        'all_items' => __('All Features', 'nearmenus'),
        'edit_item' => __('Edit Feature', 'nearmenus'),
        'update_item' => __('Update Feature', 'nearmenus'),
        'add_new_item' => __('Add New Feature', 'nearmenus'),
        'new_item_name' => __('New Feature Name', 'nearmenus'),
        'menu_name' => __('Features', 'nearmenus'),
        'view_item' => __('View Feature', 'nearmenus'),
        'popular_items' => __('Popular Features', 'nearmenus'),
        'separate_items_with_commas' => __('Separate features with commas', 'nearmenus'),
        'add_or_remove_items' => __('Add or remove features', 'nearmenus'),
        'choose_from_most_used' => __('Choose from the most used features', 'nearmenus'),
        'not_found' => __('No features found.', 'nearmenus'),
    );

    $args = array(
        'hierarchical' => false,
        'labels' => $labels,
        'show_ui' => true,
        'show_admin_column' => true,
        'query_var' => true,
        'rewrite' => array('slug' => 'features'),
        'show_in_rest' => true,
        'meta_box_cb' => 'post_tags_meta_box',
        'public' => true,
        'show_in_nav_menus' => true,
        'show_tagcloud' => true,
    );

    register_taxonomy('features', array('post'), $args);
}
add_action('init', 'nearmenus_register_features_taxonomy');

/**
 * Create default taxonomy terms
 */
function nearmenus_create_default_terms() {
    // Default cuisines
    $default_cuisines = array(
        'italian' => __('Italian', 'nearmenus'),
        'japanese' => __('Japanese', 'nearmenus'),
        'american' => __('American', 'nearmenus'),
        'indian' => __('Indian', 'nearmenus'),
        'mexican' => __('Mexican', 'nearmenus'),
        'chinese' => __('Chinese', 'nearmenus'),
        'thai' => __('Thai', 'nearmenus'),
        'french' => __('French', 'nearmenus'),
        'mediterranean' => __('Mediterranean', 'nearmenus'),
        'seafood' => __('Seafood', 'nearmenus'),
    );
    
    foreach ($default_cuisines as $slug => $name) {
        if (!term_exists($name, 'cuisine')) {
            wp_insert_term($name, 'cuisine', array('slug' => $slug));
        }
    }
    
    // Default price ranges
    $default_price_ranges = array(
        '$' => __('Budget Friendly ($)', 'nearmenus'),
        '$$' => __('Moderate ($$)', 'nearmenus'),
        '$$$' => __('Expensive ($$$)', 'nearmenus'),
        '$$$$' => __('Very Expensive ($$$$)', 'nearmenus'),
    );
    
    foreach ($default_price_ranges as $slug => $name) {
        if (!term_exists($name, 'price_range')) {
            wp_insert_term($name, 'price_range', array('slug' => sanitize_title($slug)));
        }
    }
    
    // Default features
    $default_features = array(
        'outdoor-seating' => __('Outdoor Seating', 'nearmenus'),
        'delivery' => __('Delivery', 'nearmenus'),
        'takeout' => __('Takeout', 'nearmenus'),
        'parking' => __('Parking Available', 'nearmenus'),
        'wheelchair-accessible' => __('Wheelchair Accessible', 'nearmenus'),
        'wifi' => __('Free WiFi', 'nearmenus'),
        'live-music' => __('Live Music', 'nearmenus'),
        'bar' => __('Full Bar', 'nearmenus'),
        'vegan-options' => __('Vegan Options', 'nearmenus'),
        'kids-menu' => __('Kids Menu', 'nearmenus'),
        'reservations' => __('Accepts Reservations', 'nearmenus'),
        'credit-cards' => __('Credit Cards Accepted', 'nearmenus'),
    );
    
    foreach ($default_features as $slug => $name) {
        if (!term_exists($name, 'features')) {
            wp_insert_term($name, 'features', array('slug' => $slug));
        }
    }
    
    // Default locations (examples)
    $default_locations = array(
        'downtown' => __('Downtown', 'nearmenus'),
        'uptown' => __('Uptown', 'nearmenus'),
        'midtown' => __('Midtown', 'nearmenus'),
        'beach' => __('Beach Area', 'nearmenus'),
        'suburbs' => __('Suburbs', 'nearmenus'),
    );
    
    foreach ($default_locations as $slug => $name) {
        if (!term_exists($name, 'location')) {
            wp_insert_term($name, 'location', array('slug' => $slug));
        }
    }
}
add_action('after_switch_theme', 'nearmenus_create_default_terms');

/**
 * Add custom fields to taxonomy terms
 */
function nearmenus_add_taxonomy_fields($taxonomy) {
    ?>
    <div class="form-field">
        <label for="term-icon"><?php _e('Icon/Emoji', 'nearmenus'); ?></label>
        <input type="text" id="term-icon" name="term_icon" value="" size="40" />
        <p><?php _e('Enter an emoji or icon for this term (e.g., 🍕 for pizza)', 'nearmenus'); ?></p>
    </div>
    
    <div class="form-field">
        <label for="term-color"><?php _e('Color', 'nearmenus'); ?></label>
        <input type="color" id="term-color" name="term_color" value="#3b82f6" />
        <p><?php _e('Choose a color to represent this term', 'nearmenus'); ?></p>
    </div>
    <?php
}
add_action('cuisine_add_form_fields', 'nearmenus_add_taxonomy_fields');
add_action('location_add_form_fields', 'nearmenus_add_taxonomy_fields');

/**
 * Save custom taxonomy fields
 */
function nearmenus_save_taxonomy_fields($term_id) {
    if (isset($_POST['term_icon'])) {
        update_term_meta($term_id, 'icon', sanitize_text_field($_POST['term_icon']));
    }
    if (isset($_POST['term_color'])) {
        update_term_meta($term_id, 'color', sanitize_hex_color($_POST['term_color']));
    }
}
add_action('created_cuisine', 'nearmenus_save_taxonomy_fields');
add_action('created_location', 'nearmenus_save_taxonomy_fields');