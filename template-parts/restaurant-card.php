<?php
/**
 * Restaurant Card Template Part
 *
 * @package NearMenus
 * @since 1.0.0
 */

$post_id = get_the_ID();
$rating = get_post_meta($post_id, '_restaurant_rating', true);
$review_count = get_post_meta($post_id, '_restaurant_review_count', true);
$phone = get_post_meta($post_id, '_restaurant_phone', true);
$address = get_post_meta($post_id, '_restaurant_address', true);
$is_open = get_post_meta($post_id, '_restaurant_is_open', true);
$has_delivery = get_post_meta($post_id, '_restaurant_has_delivery', true);
$special_offer = get_post_meta($post_id, '_restaurant_special_offer', true);

// Get taxonomies
$cuisines = get_the_terms($post_id, 'cuisine');
$locations = get_the_terms($post_id, 'location');
$price_ranges = get_the_terms($post_id, 'price_range');
?>

<article class="restaurant-card bg-white rounded-lg shadow-sm border overflow-hidden hover:shadow-lg transition-shadow group">
    
    <!-- Restaurant Image -->
    <div class="restaurant-image relative">
        <a href="<?php the_permalink(); ?>" class="block">
            <?php if (has_post_thumbnail()) : ?>
                <?php the_post_thumbnail('restaurant-card', array(
                    'class' => 'w-full h-48 object-cover group-hover:scale-105 transition-transform duration-300',
                    'alt' => get_the_title()
                )); ?>
            <?php else : ?>
                <div class="w-full h-48 bg-gray-200 flex items-center justify-center">
                    <svg class="w-16 h-16 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-4m-5 0H3m2 0h4M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 8v-8a1 1 0 011-1h4a1 1 0 011 1v8M7 7h10"></path>
                    </svg>
                </div>
            <?php endif; ?>
        </a>
        
        <!-- Status Badges -->
        <div class="absolute top-3 left-3 flex flex-col gap-1">
            <?php if ($is_open) : ?>
            <span class="bg-green-500 text-white px-2 py-1 rounded-full text-xs font-medium">
                <?php _e('Open Now', 'nearmenus'); ?>
            </span>
            <?php endif; ?>
            
            <?php if ($has_delivery) : ?>
            <span class="bg-blue-500 text-white px-2 py-1 rounded-full text-xs font-medium">
                <?php _e('Delivery', 'nearmenus'); ?>
            </span>
            <?php endif; ?>
        </div>
        
        <!-- Special Offer Badge -->
        <?php if ($special_offer) : ?>
        <div class="absolute top-3 right-3">
            <span class="bg-orange-500 text-white px-2 py-1 rounded-full text-xs font-medium">
                <?php _e('Special Offer', 'nearmenus'); ?>
            </span>
        </div>
        <?php endif; ?>
        
        <!-- Cuisine Icons -->
        <?php if ($cuisines && !is_wp_error($cuisines)) : ?>
        <div class="absolute bottom-3 left-3 flex space-x-1">
            <?php foreach (array_slice($cuisines, 0, 3) as $cuisine) : ?>
            <span class="text-2xl bg-white bg-opacity-90 rounded-full w-8 h-8 flex items-center justify-center">
                <?php echo nearmenus_get_cuisine_icon($cuisine->slug); ?>
            </span>
            <?php endforeach; ?>
        </div>
        <?php endif; ?>
    </div>
    
    <!-- Restaurant Content -->
    <div class="restaurant-content p-6">
        
        <!-- Header -->
        <div class="restaurant-header mb-3">
            <h3 class="restaurant-title mb-2">
                <a href="<?php the_permalink(); ?>" class="text-xl font-bold text-gray-900 hover:text-primary transition-colors">
                    <?php the_title(); ?>
                </a>
            </h3>
            
            <!-- Rating & Reviews -->
            <?php if ($rating) : ?>
            <div class="flex items-center justify-between mb-2">
                <div class="flex items-center space-x-2">
                    <?php echo nearmenus_display_rating($rating); ?>
                    <span class="text-sm font-medium text-gray-700"><?php echo number_format($rating, 1); ?></span>
                    <?php if ($review_count) : ?>
                    <span class="text-sm text-gray-500">
                        (<?php printf(_n('%d review', '%d reviews', $review_count, 'nearmenus'), $review_count); ?>)
                    </span>
                    <?php endif; ?>
                </div>
                
                <!-- Price Range -->
                <?php if ($price_ranges && !is_wp_error($price_ranges)) : ?>
                <div class="text-lg font-bold text-gray-600">
                    <?php echo nearmenus_get_price_range_display($price_ranges[0]->slug); ?>
                </div>
                <?php endif; ?>
            </div>
            <?php endif; ?>
        </div>
        
        <!-- Meta Information -->
        <div class="restaurant-meta mb-4 space-y-2">
            
            <!-- Cuisine & Location -->
            <div class="flex items-center text-sm text-gray-600">
                <?php if ($cuisines && !is_wp_error($cuisines)) : ?>
                <span class="flex items-center">
                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path>
                    </svg>
                    <?php
                    $cuisine_names = array_map(function($term) {
                        return $term->name;
                    }, array_slice($cuisines, 0, 2));
                    echo implode(', ', $cuisine_names);
                    if (count($cuisines) > 2) echo ' +' . (count($cuisines) - 2);
                    ?>
                </span>
                <?php endif; ?>
                
                <?php if ($locations && !is_wp_error($locations)) : ?>
                <span class="flex items-center ml-4">
                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                    </svg>
                    <?php echo esc_html($locations[0]->name); ?>
                </span>
                <?php endif; ?>
            </div>
            
            <!-- Address (if available) -->
            <?php if ($address) : ?>
            <div class="flex items-start text-sm text-gray-600">
                <svg class="w-4 h-4 mr-1 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                </svg>
                <span><?php echo esc_html(nearmenus_truncate_text($address, 50)); ?></span>
            </div>
            <?php endif; ?>
        </div>
        
        <!-- Restaurant Description -->
        <?php if (has_excerpt()) : ?>
        <div class="restaurant-excerpt text-gray-600 text-sm mb-4 line-clamp-2">
            <?php echo get_the_excerpt(); ?>
        </div>
        <?php endif; ?>
        
        <!-- Special Offer -->
        <?php if ($special_offer) : ?>
        <div class="special-offer bg-orange-50 border border-orange-200 rounded-lg p-3 mb-4">
            <div class="flex items-center text-orange-800 text-sm">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v13m0-13V6a2 2 0 112 2h-2zm0 0V5.5A2.5 2.5 0 109.5 8H12zm-7 4h14M5 12a2 2 0 110-4h14a2 2 0 110 4M5 12v7a2 2 0 002 2h10a2 2 0 002-2v-7"></path>
                </svg>
                <span class="font-medium"><?php echo esc_html(nearmenus_truncate_text($special_offer, 60)); ?></span>
            </div>
        </div>
        <?php endif; ?>
        
        <!-- Actions -->
        <div class="restaurant-actions flex items-center justify-between">
            <a href="<?php the_permalink(); ?>" class="btn-primary text-sm">
                <?php _e('View Details', 'nearmenus'); ?>
            </a>
            
            <!-- Quick Actions -->
            <div class="flex items-center space-x-2">
                <?php if ($phone) : ?>
                <a href="tel:<?php echo esc_attr(preg_replace('/[^0-9+]/', '', $phone)); ?>" 
                   class="p-2 text-gray-600 hover:text-primary transition-colors" 
                   title="<?php esc_attr_e('Call Restaurant', 'nearmenus'); ?>">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path>
                    </svg>
                </a>
                <?php endif; ?>
                
                <?php if ($address) : ?>
                <a href="https://maps.google.com/?q=<?php echo urlencode($address); ?>" 
                   target="_blank" 
                   class="p-2 text-gray-600 hover:text-primary transition-colors"
                   title="<?php esc_attr_e('Get Directions', 'nearmenus'); ?>">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7"></path>
                    </svg>
                </a>
                <?php endif; ?>
                
                <!-- Favorite Button (placeholder for future functionality) -->
                <button class="p-2 text-gray-600 hover:text-red-500 transition-colors favorite-btn" 
                        data-restaurant-id="<?php echo $post_id; ?>"
                        title="<?php esc_attr_e('Add to Favorites', 'nearmenus'); ?>">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
                    </svg>
                </button>
            </div>
        </div>
        
    </div>
    
</article>