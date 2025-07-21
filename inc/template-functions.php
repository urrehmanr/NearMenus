<?php
/**
 * Template Helper Functions
 *
 * @package NearMenus
 * @since 1.0.0
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Display star rating
 */
function nearmenus_display_rating($rating, $show_number = false) {
    if (!$rating) return '';
    
    $rating = floatval($rating);
    $full_stars = floor($rating);
    $half_star = ($rating - $full_stars) >= 0.5;
    $empty_stars = 5 - $full_stars - ($half_star ? 1 : 0);
    
    $output = '<div class="rating-stars flex items-center">';
    
    // Full stars
    for ($i = 0; $i < $full_stars; $i++) {
        $output .= '<svg class="star w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
        </svg>';
    }
    
    // Half star
    if ($half_star) {
        $output .= '<svg class="star w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
            <defs>
                <linearGradient id="half-fill">
                    <stop offset="50%" stop-color="currentColor"/>
                    <stop offset="50%" stop-color="#d1d5db"/>
                </linearGradient>
            </defs>
            <path fill="url(#half-fill)" d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
        </svg>';
    }
    
    // Empty stars
    for ($i = 0; $i < $empty_stars; $i++) {
        $output .= '<svg class="star empty w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
        </svg>';
    }
    
    if ($show_number) {
        $output .= '<span class="ml-1 text-sm text-gray-600">(' . number_format($rating, 1) . ')</span>';
    }
    
    $output .= '</div>';
    
    return $output;
}

/**
 * Get cuisine icon
 */
function nearmenus_get_cuisine_icon($cuisine_slug) {
    $icons = array(
        'italian' => '🍝',
        'japanese' => '🍣',
        'american' => '🍔',
        'indian' => '🍛',
        'mexican' => '🌮',
        'chinese' => '🥢',
        'thai' => '🍜',
        'french' => '🥐',
        'mediterranean' => '🫒',
        'seafood' => '🦞',
        'pizza' => '🍕',
        'burger' => '🍔',
        'sushi' => '🍣',
        'cafe' => '☕',
        'desserts' => '🍰',
        'bbq' => '🍖',
        'vegetarian' => '🥗',
        'vegan' => '🌱'
    );
    
    // Check if custom icon is set in term meta
    $term = get_term_by('slug', $cuisine_slug, 'cuisine');
    if ($term) {
        $custom_icon = get_term_meta($term->term_id, 'icon', true);
        if ($custom_icon) {
            return $custom_icon;
        }
    }
    
    return isset($icons[$cuisine_slug]) ? $icons[$cuisine_slug] : '🍽️';
}

/**
 * Get price range display
 */
function nearmenus_get_price_range_display($price_range_slug) {
    $displays = array(
        '$' => '$',
        '$$' => '$$', 
        '$$$' => '$$$',
        '$$$$' => '$$$$',
        'budget' => '$',
        'moderate' => '$$',
        'expensive' => '$$$',
        'very-expensive' => '$$$$'
    );
    
    return isset($displays[$price_range_slug]) ? $displays[$price_range_slug] : '';
}

/**
 * Display restaurant operating hours
 */
function nearmenus_display_operating_hours($post_id) {
    $days = array(
        'monday' => __('Monday', 'nearmenus'),
        'tuesday' => __('Tuesday', 'nearmenus'),
        'wednesday' => __('Wednesday', 'nearmenus'),
        'thursday' => __('Thursday', 'nearmenus'),
        'friday' => __('Friday', 'nearmenus'),
        'saturday' => __('Saturday', 'nearmenus'),
        'sunday' => __('Sunday', 'nearmenus')
    );
    
    $output = '<div class="operating-hours">';
    
    foreach ($days as $day_key => $day_label) {
        $hours = get_post_meta($post_id, '_restaurant_hours_' . $day_key, true);
        $output .= '<div class="flex justify-between py-1">';
        $output .= '<span class="font-medium">' . $day_label . ':</span>';
        $output .= '<span>' . ($hours ? esc_html($hours) : __('Closed', 'nearmenus')) . '</span>';
        $output .= '</div>';
    }
    
    $output .= '</div>';
    
    return $output;
}

/**
 * Check if restaurant is currently open
 */
function nearmenus_is_restaurant_open($post_id) {
    $is_open = get_post_meta($post_id, '_restaurant_is_open', true);
    return $is_open == '1';
}

/**
 * Get restaurant status badge
 */
function nearmenus_get_restaurant_status($post_id) {
    $is_open = nearmenus_is_restaurant_open($post_id);
    $has_delivery = get_post_meta($post_id, '_restaurant_has_delivery', true) == '1';
    
    $badges = array();
    
    if ($is_open) {
        $badges[] = '<span class="status-badge bg-green-100 text-green-800 px-2 py-1 rounded-full text-xs font-medium">' . __('Open Now', 'nearmenus') . '</span>';
    } else {
        $badges[] = '<span class="status-badge bg-gray-100 text-gray-600 px-2 py-1 rounded-full text-xs font-medium">' . __('Closed', 'nearmenus') . '</span>';
    }
    
    if ($has_delivery) {
        $badges[] = '<span class="status-badge bg-blue-100 text-blue-800 px-2 py-1 rounded-full text-xs font-medium">' . __('Delivery', 'nearmenus') . '</span>';
    }
    
    return implode(' ', $badges);
}

/**
 * Display breadcrumbs
 */
function nearmenus_breadcrumbs() {
    if (is_front_page()) return;
    
    $breadcrumbs = array();
    $breadcrumbs[] = '<a href="' . esc_url(home_url()) . '" class="text-gray-600 hover:text-primary">' . __('Home', 'nearmenus') . '</a>';
    
    if (is_search()) {
        $breadcrumbs[] = '<span class="text-gray-400">' . __('Search Results', 'nearmenus') . '</span>';
    } elseif (is_404()) {
        $breadcrumbs[] = '<span class="text-gray-400">' . __('Page Not Found', 'nearmenus') . '</span>';
    } elseif (is_single()) {
        // Check if restaurant post
        $is_restaurant = has_term('', 'cuisine') || get_post_meta(get_the_ID(), '_restaurant_rating', true);
        
        if ($is_restaurant) {
            $breadcrumbs[] = '<a href="' . esc_url(home_url()) . '" class="text-gray-600 hover:text-primary">' . __('Restaurants', 'nearmenus') . '</a>';
            
            // Add cuisine if available
            $cuisines = get_the_terms(get_the_ID(), 'cuisine');
            if ($cuisines && !is_wp_error($cuisines)) {
                $cuisine = array_shift($cuisines);
                $breadcrumbs[] = '<a href="' . esc_url(get_term_link($cuisine)) . '" class="text-gray-600 hover:text-primary">' . esc_html($cuisine->name) . '</a>';
            }
        }
        
        $breadcrumbs[] = '<span class="text-gray-400">' . get_the_title() . '</span>';
    } elseif (is_tax()) {
        $term = get_queried_object();
        if ($term->taxonomy === 'cuisine') {
            $breadcrumbs[] = '<a href="' . esc_url(home_url()) . '" class="text-gray-600 hover:text-primary">' . __('Restaurants', 'nearmenus') . '</a>';
        }
        $breadcrumbs[] = '<span class="text-gray-400">' . esc_html($term->name) . '</span>';
    } elseif (is_archive()) {
        $breadcrumbs[] = '<span class="text-gray-400">' . __('Restaurants', 'nearmenus') . '</span>';
    }
    
    if (!empty($breadcrumbs)) {
        echo '<nav class="breadcrumbs text-sm">';
        echo implode(' <svg class="w-3 h-3 mx-2 inline text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg> ', $breadcrumbs);
        echo '</nav>';
    }
}

/**
 * Get restaurant meta info for display
 */
function nearmenus_get_restaurant_meta($post_id) {
    $meta = array();
    
    // Rating
    $rating = get_post_meta($post_id, '_restaurant_rating', true);
    if ($rating) {
        $meta['rating'] = array(
            'label' => __('Rating', 'nearmenus'),
            'value' => nearmenus_display_rating($rating, true)
        );
    }
    
    // Review count
    $review_count = get_post_meta($post_id, '_restaurant_review_count', true);
    if ($review_count) {
        $meta['reviews'] = array(
            'label' => __('Reviews', 'nearmenus'),
            'value' => sprintf(_n('%d review', '%d reviews', $review_count, 'nearmenus'), $review_count)
        );
    }
    
    // Price range
    $price_terms = get_the_terms($post_id, 'price_range');
    if ($price_terms && !is_wp_error($price_terms)) {
        $price_term = array_shift($price_terms);
        $meta['price'] = array(
            'label' => __('Price Range', 'nearmenus'),
            'value' => nearmenus_get_price_range_display($price_term->slug)
        );
    }
    
    // Cuisine
    $cuisine_terms = get_the_terms($post_id, 'cuisine');
    if ($cuisine_terms && !is_wp_error($cuisine_terms)) {
        $cuisines = array_map(function($term) {
            return esc_html($term->name);
        }, $cuisine_terms);
        
        $meta['cuisine'] = array(
            'label' => __('Cuisine', 'nearmenus'),
            'value' => implode(', ', $cuisines)
        );
    }
    
    // Location
    $location_terms = get_the_terms($post_id, 'location');
    if ($location_terms && !is_wp_error($location_terms)) {
        $location = array_shift($location_terms);
        $meta['location'] = array(
            'label' => __('Location', 'nearmenus'),
            'value' => esc_html($location->name)
        );
    }
    
    return $meta;
}

/**
 * Format phone number for display
 */
function nearmenus_format_phone($phone) {
    $phone = preg_replace('/[^0-9]/', '', $phone);
    
    if (strlen($phone) == 10) {
        return sprintf('(%s) %s-%s', 
            substr($phone, 0, 3),
            substr($phone, 3, 3),
            substr($phone, 6)
        );
    }
    
    return $phone;
}

/**
 * Get restaurant contact info
 */
function nearmenus_get_restaurant_contact($post_id) {
    $contact = array();
    
    $phone = get_post_meta($post_id, '_restaurant_phone', true);
    if ($phone) {
        $contact['phone'] = array(
            'label' => __('Phone', 'nearmenus'),
            'value' => nearmenus_format_phone($phone),
            'link' => 'tel:' . preg_replace('/[^0-9+]/', '', $phone),
            'icon' => 'phone'
        );
    }
    
    $email = get_post_meta($post_id, '_restaurant_email', true);
    if ($email) {
        $contact['email'] = array(
            'label' => __('Email', 'nearmenus'),
            'value' => $email,
            'link' => 'mailto:' . $email,
            'icon' => 'email'
        );
    }
    
    $website = get_post_meta($post_id, '_restaurant_website', true);
    if ($website) {
        $contact['website'] = array(
            'label' => __('Website', 'nearmenus'),
            'value' => str_replace(array('http://', 'https://'), '', $website),
            'link' => $website,
            'icon' => 'website'
        );
    }
    
    $address = get_post_meta($post_id, '_restaurant_address', true);
    if ($address) {
        $contact['address'] = array(
            'label' => __('Address', 'nearmenus'),
            'value' => $address,
            'link' => 'https://maps.google.com/?q=' . urlencode($address),
            'icon' => 'location'
        );
    }
    
    return $contact;
}

/**
 * Truncate text with word boundary
 */
function nearmenus_truncate_text($text, $length = 100, $suffix = '...') {
    if (strlen($text) <= $length) {
        return $text;
    }
    
    $text = substr($text, 0, $length);
    $text = substr($text, 0, strrpos($text, ' '));
    
    return $text . $suffix;
}

/**
 * Get time ago string
 */
function nearmenus_time_ago($time) {
    $time_diff = time() - $time;
    
    if ($time_diff < 60) {
        return __('Just now', 'nearmenus');
    } elseif ($time_diff < 3600) {
        $minutes = floor($time_diff / 60);
        return sprintf(_n('%d minute ago', '%d minutes ago', $minutes, 'nearmenus'), $minutes);
    } elseif ($time_diff < 86400) {
        $hours = floor($time_diff / 3600);
        return sprintf(_n('%d hour ago', '%d hours ago', $hours, 'nearmenus'), $hours);
    } elseif ($time_diff < 2592000) {
        $days = floor($time_diff / 86400);
        return sprintf(_n('%d day ago', '%d days ago', $days, 'nearmenus'), $days);
    } else {
        return date_i18n(get_option('date_format'), $time);
    }
}

/**
 * Get restaurant gallery images
 */
function nearmenus_get_restaurant_gallery($post_id) {
    $gallery_meta = get_post_meta($post_id, '_restaurant_gallery', true);
    $images = array();
    
    if ($gallery_meta) {
        $image_ids = explode(',', $gallery_meta);
        foreach ($image_ids as $image_id) {
            $image_id = intval($image_id);
            if ($image_id) {
                $images[] = array(
                    'id' => $image_id,
                    'url' => wp_get_attachment_url($image_id),
                    'thumb' => wp_get_attachment_image_url($image_id, 'restaurant-gallery'),
                    'full' => wp_get_attachment_image_url($image_id, 'large'),
                    'alt' => get_post_meta($image_id, '_wp_attachment_image_alt', true)
                );
            }
        }
    }
    
    return $images;
}

/**
 * Custom menu walker for navigation
 */
class NearMenus_Walker_Nav_Menu extends Walker_Nav_Menu {
    
    public function start_el(&$output, $item, $depth = 0, $args = null, $id = 0) {
        $classes = empty($item->classes) ? array() : (array) $item->classes;
        $classes[] = 'menu-item-' . $item->ID;
        
        $class_names = join(' ', apply_filters('nav_menu_css_class', array_filter($classes), $item, $args));
        $class_names = $class_names ? ' class="' . esc_attr($class_names) . '"' : '';
        
        $id = apply_filters('nav_menu_item_id', 'menu-item-' . $item->ID, $item, $args);
        $id = $id ? ' id="' . esc_attr($id) . '"' : '';
        
        $output .= '<li' . $id . $class_names .'>';
        
        $attributes = ! empty($item->attr_title) ? ' title="'  . esc_attr($item->attr_title) .'"' : '';
        $attributes .= ! empty($item->target)     ? ' target="' . esc_attr($item->target     ) .'"' : '';
        $attributes .= ! empty($item->xfn)        ? ' rel="'    . esc_attr($item->xfn        ) .'"' : '';
        $attributes .= ! empty($item->url)        ? ' href="'   . esc_attr($item->url        ) .'"' : '';
        
        $item_output = isset($args->before) ? $args->before : '';
        $item_output .= '<a' . $attributes . ' class="text-gray-700 hover:text-primary font-medium transition-colors">';
        $item_output .= (isset($args->link_before) ? $args->link_before : '') . apply_filters('the_title', $item->title, $item->ID) . (isset($args->link_after) ? $args->link_after : '');
        $item_output .= '</a>';
        $item_output .= isset($args->after) ? $args->after : '';
        
        $output .= apply_filters('walker_nav_menu_start_el', $item_output, $item, $depth, $args);
    }
}

/**
 * Mobile menu walker
 */
class NearMenus_Walker_Mobile_Menu extends Walker_Nav_Menu {
    
    public function start_el(&$output, $item, $depth = 0, $args = null, $id = 0) {
        $output .= '<li class="mobile-menu-item">';
        $output .= '<a href="' . esc_attr($item->url) . '" class="block py-2 text-gray-700 hover:text-primary font-medium">';
        $output .= esc_html($item->title);
        $output .= '</a>';
        $output .= '</li>';
    }
}

/**
 * Footer menu walker  
 */
class NearMenus_Walker_Footer_Menu extends Walker_Nav_Menu {
    
    public function start_el(&$output, $item, $depth = 0, $args = null, $id = 0) {
        $output .= '<li>';
        $output .= '<a href="' . esc_attr($item->url) . '" class="text-gray-600 hover:text-primary transition-colors">';
        $output .= esc_html($item->title);
        $output .= '</a>';
        $output .= '</li>';
    }
}