<?php
/**
 * NearMenus Theme Diagnostic & Sample Data Creator
 * Run this once to check theme status and create sample restaurants
 * 
 * USAGE: Add this to your WordPress root, run it, then delete it
 */

// WordPress Bootstrap
require_once('wp-config.php');
require_once('wp-load.php');

if (!current_user_can('manage_options')) {
    die('Access denied. Admin privileges required.');
}

echo "<h1>NearMenus Theme Diagnostic</h1>";

// Check if theme is active
$current_theme = wp_get_theme();
echo "<h2>Current Theme: " . $current_theme->get('Name') . "</h2>";

// Check if CSS is enqueued
$styles = wp_styles();
echo "<h3>Enqueued Styles:</h3><ul>";
foreach ($styles->queue as $handle) {
    echo "<li>$handle: " . $styles->registered[$handle]->src . "</li>";
}
echo "</ul>";

// Check for restaurant posts
$restaurants = get_posts(array(
    'post_type' => 'post',
    'posts_per_page' => -1,
    'meta_query' => array(
        array(
            'key' => '_restaurant_rating',
            'compare' => 'EXISTS'
        )
    )
));

echo "<h3>Existing Restaurant Posts: " . count($restaurants) . "</h3>";

// Check taxonomies
$cuisines = get_terms('cuisine');
$locations = get_terms('location');
$price_ranges = get_terms('price_range');
$features = get_terms('features');

echo "<h3>Taxonomies:</h3>";
echo "<ul>";
echo "<li>Cuisines: " . count($cuisines) . "</li>";
echo "<li>Locations: " . count($locations) . "</li>";
echo "<li>Price Ranges: " . count($price_ranges) . "</li>";
echo "<li>Features: " . count($features) . "</li>";
echo "</ul>";

// Create sample data if none exists
if (count($restaurants) == 0 && isset($_GET['create_sample'])) {
    echo "<h2>Creating Sample Data...</h2>";
    
    // Create taxonomies first
    $sample_cuisines = array('Italian', 'Mexican', 'Chinese', 'Indian', 'American', 'Japanese');
    $sample_locations = array('Downtown', 'Midtown', 'Uptown', 'Westside', 'Eastside');
    $sample_prices = array('$' => 'Budget-friendly', '$$' => 'Moderate', '$$$' => 'Expensive', '$$$$' => 'Very Expensive');
    $sample_features = array('Delivery', 'Takeout', 'Outdoor Seating', 'WiFi', 'Parking', 'Family-Friendly', 'Date Night', 'Groups');
    
    // Create cuisine terms
    foreach ($sample_cuisines as $cuisine) {
        wp_insert_term($cuisine, 'cuisine');
    }
    
    // Create location terms
    foreach ($sample_locations as $location) {
        wp_insert_term($location, 'location');
    }
    
    // Create price range terms
    foreach ($sample_prices as $symbol => $desc) {
        wp_insert_term($symbol, 'price_range', array('description' => $desc));
    }
    
    // Create feature terms
    foreach ($sample_features as $feature) {
        wp_insert_term($feature, 'features');
    }
    
    // Create sample restaurants
    $sample_restaurants = array(
        array(
            'name' => 'Bella Vista Italian',
            'description' => 'Authentic Italian cuisine with a modern twist. Fresh pasta made daily and an extensive wine selection.',
            'cuisine' => 'Italian',
            'location' => 'Downtown',
            'price' => '$$',
            'features' => array('Delivery', 'Outdoor Seating', 'Date Night'),
            'rating' => 4.5,
            'reviews' => 150,
            'phone' => '(555) 123-4567',
            'address' => '123 Main St, Downtown',
            'website' => 'https://bellavista.example.com'
        ),
        array(
            'name' => 'Spice Garden Indian',
            'description' => 'Traditional Indian flavors with vegetarian and vegan options. Lunch buffet available weekdays.',
            'cuisine' => 'Indian',
            'location' => 'Midtown',
            'price' => '$',
            'features' => array('Takeout', 'Family-Friendly', 'WiFi'),
            'rating' => 4.2,
            'reviews' => 89,
            'phone' => '(555) 234-5678',
            'address' => '456 Oak Ave, Midtown',
            'website' => 'https://spicegarden.example.com'
        ),
        array(
            'name' => 'The Grill House',
            'description' => 'Premium steaks and grilled specialties. Perfect for business dinners and special occasions.',
            'cuisine' => 'American',
            'location' => 'Uptown',
            'price' => '$$$',
            'features' => array('Parking', 'Date Night', 'Groups'),
            'rating' => 4.7,
            'reviews' => 203,
            'phone' => '(555) 345-6789',
            'address' => '789 Hill Rd, Uptown',
            'website' => 'https://grillhouse.example.com'
        )
    );
    
    foreach ($sample_restaurants as $restaurant) {
        $post_id = wp_insert_post(array(
            'post_title' => $restaurant['name'],
            'post_content' => $restaurant['description'],
            'post_status' => 'publish',
            'post_type' => 'post'
        ));
        
        if ($post_id) {
            // Set featured image (placeholder)
            $image_id = media_sideload_image('https://via.placeholder.com/600x400/f97316/ffffff?text=' . urlencode($restaurant['name']), $post_id, '', 'id');
            if (!is_wp_error($image_id)) {
                set_post_thumbnail($post_id, $image_id);
            }
            
            // Add meta fields
            update_post_meta($post_id, '_restaurant_rating', $restaurant['rating']);
            update_post_meta($post_id, '_restaurant_review_count', $restaurant['reviews']);
            update_post_meta($post_id, '_restaurant_phone', $restaurant['phone']);
            update_post_meta($post_id, '_restaurant_address', $restaurant['address']);
            update_post_meta($post_id, '_restaurant_website', $restaurant['website']);
            update_post_meta($post_id, '_restaurant_is_open', 1);
            update_post_meta($post_id, '_restaurant_has_delivery', in_array('Delivery', $restaurant['features']) ? 1 : 0);
            
            // Add operating hours
            $days = array('monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday', 'sunday');
            foreach ($days as $day) {
                if (in_array($day, array('saturday', 'sunday'))) {
                    update_post_meta($post_id, '_restaurant_hours_' . $day, '10:00 AM - 11:00 PM');
                } else {
                    update_post_meta($post_id, '_restaurant_hours_' . $day, '11:00 AM - 10:00 PM');
                }
            }
            
            // Set taxonomies
            wp_set_object_terms($post_id, $restaurant['cuisine'], 'cuisine');
            wp_set_object_terms($post_id, $restaurant['location'], 'location');
            wp_set_object_terms($post_id, $restaurant['price'], 'price_range');
            wp_set_object_terms($post_id, $restaurant['features'], 'features');
            
            echo "<p>Created restaurant: " . $restaurant['name'] . "</p>";
        }
    }
    
    echo "<h3>Sample data created successfully!</h3>";
    echo "<p><a href='" . home_url() . "'>View your site</a></p>";
}

if (count($restaurants) == 0) {
    echo "<p><strong>No restaurant posts found!</strong></p>";
    echo "<p><a href='?create_sample=1'>Click here to create sample restaurant data</a></p>";
}

echo "<hr>";
echo "<h3>Theme File Check:</h3>";
$required_files = array('style.css', 'index.php', 'header.php', 'footer.php', 'functions.php');
foreach ($required_files as $file) {
    $path = get_template_directory() . '/' . $file;
    echo "<p>$file: " . (file_exists($path) ? '✅ Exists' : '❌ Missing') . "</p>";
}

$css_path = get_template_directory() . '/assets/dist/css/main.min.css';
echo "<p>main.min.css: " . (file_exists($css_path) ? '✅ Exists (' . size_format(filesize($css_path)) . ')' : '❌ Missing') . "</p>";

echo "<hr>";
echo "<h3>WordPress Info:</h3>";
echo "<p>WordPress Version: " . get_bloginfo('version') . "</p>";
echo "<p>PHP Version: " . PHP_VERSION . "</p>";
echo "<p>Site URL: " . home_url() . "</p>";
echo "<p>Theme URL: " . get_template_directory_uri() . "</p>";
?>