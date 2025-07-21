<?php
/**
 * Search and Filtering Functions
 *
 * @package NearMenus
 * @since 1.0.0
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Enhance search to include custom fields and taxonomies
 */
function nearmenus_search_filter($query) {
    if (!is_admin() && $query->is_main_query()) {
        if ($query->is_search()) {
            $search_term = get_search_query();
            
            // Meta query for custom fields
            $meta_query = array(
                'relation' => 'OR',
                array(
                    'key' => '_restaurant_popular_dishes',
                    'value' => $search_term,
                    'compare' => 'LIKE'
                ),
                array(
                    'key' => '_restaurant_address',
                    'value' => $search_term,
                    'compare' => 'LIKE'
                ),
                array(
                    'key' => '_restaurant_special_offer',
                    'value' => $search_term,
                    'compare' => 'LIKE'
                )
            );
            
            $query->set('meta_query', $meta_query);
            
            // Tax query for cuisines
            $tax_query = array(
                'relation' => 'OR',
                array(
                    'taxonomy' => 'cuisine',
                    'field' => 'name',
                    'terms' => $search_term,
                    'operator' => 'LIKE'
                ),
                array(
                    'taxonomy' => 'location',
                    'field' => 'name',
                    'terms' => $search_term,
                    'operator' => 'LIKE'
                ),
                array(
                    'taxonomy' => 'features',
                    'field' => 'name',
                    'terms' => $search_term,
                    'operator' => 'LIKE'
                )
            );
            
            $query->set('tax_query', $tax_query);
        }
    }
}
add_action('pre_get_posts', 'nearmenus_search_filter');

/**
 * AJAX Restaurant Search
 */
function nearmenus_ajax_restaurant_search() {
    // Verify nonce
    if (!wp_verify_nonce($_POST['nonce'], 'nearmenus_search_nonce')) {
        wp_die('Security check failed');
    }
    
    $search_query = sanitize_text_field($_POST['search'] ?? '');
    $cuisine = sanitize_text_field($_POST['cuisine'] ?? '');
    $location = sanitize_text_field($_POST['location'] ?? '');
    $price_range = sanitize_text_field($_POST['price_range'] ?? '');
    $features = array_map('sanitize_text_field', $_POST['features'] ?? array());
    $min_rating = floatval($_POST['min_rating'] ?? 0);
    $sort_by = sanitize_text_field($_POST['sort_by'] ?? 'rating');
    $page = intval($_POST['page'] ?? 1);
    $posts_per_page = intval($_POST['posts_per_page'] ?? 12);
    
    // Build query args
    $args = array(
        'post_type' => 'post',
        'posts_per_page' => $posts_per_page,
        'paged' => $page,
        'post_status' => 'publish'
    );
    
    // Text search
    if (!empty($search_query)) {
        $args['s'] = $search_query;
    }
    
    // Meta query for rating and other custom fields
    $meta_query = array('relation' => 'AND');
    
    if ($min_rating > 0) {
        $meta_query[] = array(
            'key' => '_restaurant_rating',
            'value' => $min_rating,
            'type' => 'DECIMAL',
            'compare' => '>='
        );
    }
    
    // Add filter for restaurants only (exclude blog posts)
    $meta_query[] = array(
        'relation' => 'OR',
        array(
            'key' => '_restaurant_rating',
            'compare' => 'EXISTS'
        ),
        array(
            'key' => '_restaurant_phone',
            'compare' => 'EXISTS'
        ),
        array(
            'key' => '_restaurant_address',
            'compare' => 'EXISTS'
        )
    );
    
    if (count($meta_query) > 1) {
        $args['meta_query'] = $meta_query;
    }
    
    // Tax query for taxonomies
    $tax_query = array('relation' => 'AND');
    
    if (!empty($cuisine)) {
        $tax_query[] = array(
            'taxonomy' => 'cuisine',
            'field' => 'slug',
            'terms' => $cuisine
        );
    }
    
    if (!empty($location)) {
        $tax_query[] = array(
            'taxonomy' => 'location',
            'field' => 'slug',
            'terms' => $location
        );
    }
    
    if (!empty($price_range)) {
        $tax_query[] = array(
            'taxonomy' => 'price_range',
            'field' => 'slug',
            'terms' => $price_range
        );
    }
    
    if (!empty($features)) {
        $tax_query[] = array(
            'taxonomy' => 'features',
            'field' => 'slug',
            'terms' => $features,
            'operator' => 'AND'
        );
    }
    
    if (count($tax_query) > 1) {
        $args['tax_query'] = $tax_query;
    }
    
    // Sorting
    switch ($sort_by) {
        case 'rating':
            $args['meta_key'] = '_restaurant_rating';
            $args['orderby'] = 'meta_value_num';
            $args['order'] = 'DESC';
            break;
        case 'name':
            $args['orderby'] = 'title';
            $args['order'] = 'ASC';
            break;
        case 'newest':
            $args['orderby'] = 'date';
            $args['order'] = 'DESC';
            break;
        case 'reviews':
            $args['meta_key'] = '_restaurant_review_count';
            $args['orderby'] = 'meta_value_num';
            $args['order'] = 'DESC';
            break;
        default:
            $args['orderby'] = 'relevance';
            break;
    }
    
    // Execute query
    $restaurants = new WP_Query($args);
    
    $response = array(
        'success' => true,
        'found_posts' => $restaurants->found_posts,
        'max_pages' => $restaurants->max_num_pages,
        'current_page' => $page,
        'posts' => array()
    );
    
    if ($restaurants->have_posts()) {
        ob_start();
        while ($restaurants->have_posts()) {
            $restaurants->the_post();
            get_template_part('template-parts/restaurant-card');
        }
        $response['html'] = ob_get_clean();
        wp_reset_postdata();
    } else {
        $response['html'] = '<div class="col-span-full text-center py-16">
            <div class="text-6xl mb-4">🍽️</div>
            <h3 class="text-xl font-semibold mb-2">' . __('No restaurants found', 'nearmenus') . '</h3>
            <p class="text-gray-600">' . __('Try adjusting your search criteria.', 'nearmenus') . '</p>
        </div>';
    }
    
    wp_send_json($response);
}
add_action('wp_ajax_nearmenus_restaurant_search', 'nearmenus_ajax_restaurant_search');
add_action('wp_ajax_nopriv_nearmenus_restaurant_search', 'nearmenus_ajax_restaurant_search');

/**
 * Get restaurants by location
 */
function nearmenus_get_restaurants_by_location($location_slug, $limit = 10) {
    $args = array(
        'post_type' => 'post',
        'posts_per_page' => $limit,
        'tax_query' => array(
            array(
                'taxonomy' => 'location',
                'field' => 'slug',
                'terms' => $location_slug
            )
        ),
        'meta_query' => array(
            array(
                'key' => '_restaurant_rating',
                'compare' => 'EXISTS'
            )
        )
    );
    
    return new WP_Query($args);
}

/**
 * Get restaurants by cuisine
 */
function nearmenus_get_restaurants_by_cuisine($cuisine_slug, $limit = 10) {
    $args = array(
        'post_type' => 'post',
        'posts_per_page' => $limit,
        'tax_query' => array(
            array(
                'taxonomy' => 'cuisine',
                'field' => 'slug',
                'terms' => $cuisine_slug
            )
        ),
        'meta_query' => array(
            array(
                'key' => '_restaurant_rating',
                'compare' => 'EXISTS'
            )
        )
    );
    
    return new WP_Query($args);
}

/**
 * Get related restaurants
 */
function nearmenus_get_related_restaurants($post_id, $limit = 6) {
    $cuisines = wp_get_post_terms($post_id, 'cuisine', array('fields' => 'slugs'));
    $locations = wp_get_post_terms($post_id, 'location', array('fields' => 'slugs'));
    
    $tax_query = array('relation' => 'OR');
    
    if (!empty($cuisines)) {
        $tax_query[] = array(
            'taxonomy' => 'cuisine',
            'field' => 'slug',
            'terms' => $cuisines
        );
    }
    
    if (!empty($locations)) {
        $tax_query[] = array(
            'taxonomy' => 'location',
            'field' => 'slug',
            'terms' => $locations
        );
    }
    
    $args = array(
        'post_type' => 'post',
        'posts_per_page' => $limit,
        'post__not_in' => array($post_id),
        'meta_query' => array(
            array(
                'key' => '_restaurant_rating',
                'compare' => 'EXISTS'
            )
        )
    );
    
    if (count($tax_query) > 1) {
        $args['tax_query'] = $tax_query;
    }
    
    return new WP_Query($args);
}

/**
 * Search autocomplete suggestions
 */
function nearmenus_ajax_search_suggestions() {
    $search_term = sanitize_text_field($_GET['term'] ?? '');
    
    if (strlen($search_term) < 2) {
        wp_send_json(array());
        return;
    }
    
    $suggestions = array();
    
    // Restaurant names
    $restaurants = get_posts(array(
        'post_type' => 'post',
        's' => $search_term,
        'posts_per_page' => 5,
        'meta_query' => array(
            array(
                'key' => '_restaurant_rating',
                'compare' => 'EXISTS'
            )
        )
    ));
    
    foreach ($restaurants as $restaurant) {
        $suggestions[] = array(
            'label' => $restaurant->post_title,
            'value' => $restaurant->post_title,
            'type' => 'restaurant',
            'url' => get_permalink($restaurant->ID)
        );
    }
    
    // Cuisines
    $cuisines = get_terms(array(
        'taxonomy' => 'cuisine',
        'name__like' => $search_term,
        'hide_empty' => true,
        'number' => 3
    ));
    
    foreach ($cuisines as $cuisine) {
        $suggestions[] = array(
            'label' => $cuisine->name . ' (' . $cuisine->count . ' restaurants)',
            'value' => $cuisine->name,
            'type' => 'cuisine',
            'url' => get_term_link($cuisine)
        );
    }
    
    // Locations
    $locations = get_terms(array(
        'taxonomy' => 'location',
        'name__like' => $search_term,
        'hide_empty' => true,
        'number' => 3
    ));
    
    foreach ($locations as $location) {
        $suggestions[] = array(
            'label' => $location->name . ' (' . $location->count . ' restaurants)',
            'value' => $location->name,
            'type' => 'location',
            'url' => get_term_link($location)
        );
    }
    
    wp_send_json($suggestions);
}
add_action('wp_ajax_nearmenus_search_suggestions', 'nearmenus_ajax_search_suggestions');
add_action('wp_ajax_nopriv_nearmenus_search_suggestions', 'nearmenus_ajax_search_suggestions');

/**
 * Filter restaurants by open status
 */
function nearmenus_filter_restaurants_by_status($status = 'open') {
    $meta_key = $status === 'open' ? '_restaurant_is_open' : '_restaurant_has_delivery';
    
    $args = array(
        'post_type' => 'post',
        'posts_per_page' => -1,
        'meta_query' => array(
            'relation' => 'AND',
            array(
                'key' => '_restaurant_rating',
                'compare' => 'EXISTS'
            ),
            array(
                'key' => $meta_key,
                'value' => '1',
                'compare' => '='
            )
        )
    );
    
    return new WP_Query($args);
}

/**
 * Get search statistics
 */
function nearmenus_get_search_stats() {
    $stats = get_transient('nearmenus_search_stats');
    
    if (false === $stats) {
        $stats = array(
            'total_restaurants' => wp_count_posts('post')->publish,
            'total_cuisines' => wp_count_terms('cuisine', array('hide_empty' => true)),
            'total_locations' => wp_count_terms('location', array('hide_empty' => true)),
            'avg_rating' => nearmenus_get_average_rating()
        );
        
        set_transient('nearmenus_search_stats', $stats, HOUR_IN_SECONDS);
    }
    
    return $stats;
}

/**
 * Get average rating across all restaurants
 */
function nearmenus_get_average_rating() {
    global $wpdb;
    
    $avg_rating = $wpdb->get_var("
        SELECT AVG(CAST(meta_value AS DECIMAL(3,2))) 
        FROM {$wpdb->postmeta} 
        WHERE meta_key = '_restaurant_rating' 
        AND meta_value != '' 
        AND meta_value IS NOT NULL
    ");
    
    return $avg_rating ? round($avg_rating, 1) : 0;
}

/**
 * Get cuisine average rating
 */
function nearmenus_get_cuisine_average_rating($cuisine_term_id) {
    $posts = get_posts(array(
        'post_type' => 'post',
        'posts_per_page' => -1,
        'tax_query' => array(
            array(
                'taxonomy' => 'cuisine',
                'field' => 'term_id',
                'terms' => $cuisine_term_id
            )
        ),
        'meta_query' => array(
            array(
                'key' => '_restaurant_rating',
                'compare' => 'EXISTS'
            )
        )
    ));
    
    if (empty($posts)) {
        return 0;
    }
    
    $total_rating = 0;
    $count = 0;
    
    foreach ($posts as $post) {
        $rating = get_post_meta($post->ID, '_restaurant_rating', true);
        if ($rating) {
            $total_rating += floatval($rating);
            $count++;
        }
    }
    
    return $count > 0 ? round($total_rating / $count, 1) : 0;
}