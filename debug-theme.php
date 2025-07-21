<?php
/**
 * Simple theme debug - add this to WordPress root and access via browser
 * This will help diagnose why content isn't showing
 */

// Include WordPress
require_once('wp-config.php');
require_once('wp-load.php');

// Force authentication (comment out if testing publicly)
// if (!current_user_can('manage_options')) {
//     die('Access denied');
// }

echo "<h1>NearMenus Theme Debug</h1>";
echo "<style>body{font-family:Arial;margin:20px;}h1,h2{color:#333;}ul{margin:10px 0;}li{margin:5px 0;}</style>";

// 1. Check current theme
$current_theme = wp_get_theme();
echo "<h2>✅ Current Theme: " . $current_theme->get('Name') . " v" . $current_theme->get('Version') . "</h2>";

// 2. Check if our CSS is being enqueued
global $wp_styles;
wp_enqueue_scripts(); // Trigger the enqueue
echo "<h2>🎨 Enqueued Stylesheets:</h2><ul>";
if (isset($wp_styles->registered)) {
    foreach ($wp_styles->registered as $handle => $style) {
        echo "<li><strong>$handle:</strong> " . $style->src . "</li>";
    }
}
echo "</ul>";

// 3. Check if CSS file exists and is accessible
$css_path = get_template_directory() . '/assets/dist/css/main.min.css';
$css_url = get_template_directory_uri() . '/assets/dist/css/main.min.css';
echo "<h2>📁 CSS File Check:</h2>";
echo "<p>File exists: " . (file_exists($css_path) ? "✅ YES" : "❌ NO") . "</p>";
echo "<p>File size: " . (file_exists($css_path) ? number_format(filesize($css_path)) . " bytes" : "N/A") . "</p>";
echo "<p>CSS URL: <a href='$css_url' target='_blank'>$css_url</a> (click to test)</p>";

// 4. Check for posts
$all_posts = get_posts(array('post_type' => 'post', 'posts_per_page' => -1));
echo "<h2>📝 Posts Check:</h2>";
echo "<p>Total posts: " . count($all_posts) . "</p>";

if (count($all_posts) > 0) {
    echo "<h3>Recent Posts:</h3><ul>";
    foreach (array_slice($all_posts, 0, 5) as $post) {
        echo "<li><a href='" . get_permalink($post) . "'>" . $post->post_title . "</a> (Status: " . $post->post_status . ")</li>";
    }
    echo "</ul>";
} else {
    echo "<p>❌ <strong>NO POSTS FOUND!</strong> This is likely why content isn't showing.</p>";
    echo "<p><a href='?create_test_post=1' style='background:#007cba;color:white;padding:10px;text-decoration:none;'>Create Test Post</a></p>";
}

// 5. Check restaurant posts specifically
$restaurant_posts = get_posts(array(
    'post_type' => 'post',
    'meta_query' => array(
        array(
            'key' => '_restaurant_rating',
            'compare' => 'EXISTS'
        )
    )
));
echo "<h2>🍽️ Restaurant Posts:</h2>";
echo "<p>Restaurant posts with meta: " . count($restaurant_posts) . "</p>";

// 6. Check taxonomies
$taxonomies = array('cuisine', 'location', 'price_range', 'features');
echo "<h2>🏷️ Taxonomies:</h2><ul>";
foreach ($taxonomies as $taxonomy) {
    $terms = get_terms(array('taxonomy' => $taxonomy, 'hide_empty' => false));
    if (!is_wp_error($terms)) {
        echo "<li><strong>$taxonomy:</strong> " . count($terms) . " terms</li>";
    } else {
        echo "<li><strong>$taxonomy:</strong> ❌ Error or not registered</li>";
    }
}
echo "</ul>";

// 7. Check if functions are loaded
echo "<h2>🔧 Theme Functions:</h2><ul>";
$functions_to_check = array('nearmenus_scripts', 'nearmenus_setup', 'nearmenus_add_meta_boxes');
foreach ($functions_to_check as $func) {
    echo "<li><strong>$func:</strong> " . (function_exists($func) ? "✅ Exists" : "❌ Missing") . "</li>";
}
echo "</ul>";

// 8. Create test post if requested
if (isset($_GET['create_test_post']) && current_user_can('publish_posts')) {
    echo "<h2>Creating Test Post...</h2>";
    
    $post_id = wp_insert_post(array(
        'post_title' => 'Test Restaurant - ' . date('Y-m-d H:i:s'),
        'post_content' => 'This is a test restaurant post to check if the theme is working properly. It includes sample content and should display with the theme styling.',
        'post_status' => 'publish',
        'post_type' => 'post'
    ));
    
    if ($post_id) {
        // Add restaurant meta
        update_post_meta($post_id, '_restaurant_rating', '4.5');
        update_post_meta($post_id, '_restaurant_review_count', '150');
        update_post_meta($post_id, '_restaurant_phone', '(555) 123-4567');
        update_post_meta($post_id, '_restaurant_address', '123 Test Street, Test City');
        update_post_meta($post_id, '_restaurant_is_open', 1);
        
        echo "<p>✅ Created test post: <a href='" . get_permalink($post_id) . "'>View Post</a></p>";
        echo "<p><a href='?'>Refresh this page</a> to see updated counts.</p>";
    } else {
        echo "<p>❌ Failed to create test post</p>";
    }
}

// 9. Test basic WordPress functions
echo "<h2>🔍 WordPress Environment:</h2><ul>";
echo "<li><strong>WordPress Version:</strong> " . get_bloginfo('version') . "</li>";
echo "<li><strong>PHP Version:</strong> " . PHP_VERSION . "</li>";
echo "<li><strong>Site URL:</strong> " . home_url() . "</li>";
echo "<li><strong>Admin URL:</strong> " . admin_url() . "</li>";
echo "<li><strong>Theme Directory:</strong> " . get_template_directory() . "</li>";
echo "<li><strong>Theme URL:</strong> " . get_template_directory_uri() . "</li>";
echo "</ul>";

// 10. Check for any PHP errors in error log
echo "<h2>🚨 Quick Health Check:</h2>";
echo "<p>If you see this page properly formatted, PHP is working.</p>";
echo "<p>If CSS loads when you click the link above, file permissions are OK.</p>";
echo "<p>If posts show up after creating test content, the theme templates are working.</p>";

echo "<hr>";
echo "<p><strong>Next Steps:</strong></p>";
echo "<ol>";
echo "<li>Click the CSS URL above to verify it loads</li>";
echo "<li>Create a test post if none exist</li>";
echo "<li>Visit your homepage to see if content appears</li>";
echo "<li>Check browser console for JavaScript errors</li>";
echo "</ol>";

echo "<p><a href='" . home_url() . "' style='background:#28a745;color:white;padding:10px;text-decoration:none;'>Go to Homepage</a></p>";
?>