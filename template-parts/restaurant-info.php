<?php
/**
 * Restaurant Information Display Template Part
 * 
 * @package NearMenus
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

global $post;

// Get restaurant data
$rating = get_post_meta($post->ID, '_restaurant_rating', true);
$review_count = get_post_meta($post->ID, '_restaurant_review_count', true);
$phone = get_post_meta($post->ID, '_restaurant_phone', true);
$email = get_post_meta($post->ID, '_restaurant_email', true);
$website = get_post_meta($post->ID, '_restaurant_website', true);
$address = get_post_meta($post->ID, '_restaurant_address', true);
$special_offers = get_post_meta($post->ID, '_restaurant_special_offers', true);
$popular_dishes = get_post_meta($post->ID, '_restaurant_popular_dishes', true);
$currently_open = get_post_meta($post->ID, '_restaurant_currently_open', true);
$offers_delivery = get_post_meta($post->ID, '_restaurant_offers_delivery', true);

// Get taxonomies
$cuisines = wp_get_post_terms($post->ID, 'cuisine');
$locations = wp_get_post_terms($post->ID, 'location');
$price_ranges = wp_get_post_terms($post->ID, 'price-range');
$features = wp_get_post_terms($post->ID, 'features');

// Get operating hours
$days = array('monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday', 'sunday');
$hours = array();
foreach ($days as $day) {
    $hours[$day] = get_post_meta($post->ID, '_restaurant_hours_' . $day, true);
}
?>

<div class="restaurant-info bg-white rounded-lg shadow-md overflow-hidden">
    <!-- Restaurant Header -->
    <div class="restaurant-header p-6 border-b border-gray-200">
        <div class="flex flex-col lg:flex-row lg:items-start lg:justify-between">
            <div class="restaurant-basic-info flex-1">
                <h1 class="restaurant-title text-3xl font-bold text-gray-900 mb-2">
                    <?php the_title(); ?>
                </h1>
                
                <!-- Rating and Status -->
                <div class="restaurant-meta flex flex-wrap items-center gap-4 mb-3">
                    <?php if ($rating): ?>
                        <div class="rating-display flex items-center">
                            <?php echo nearmenus_display_rating($rating); ?>
                            <span class="rating-value text-lg font-semibold ml-2"><?php echo esc_html($rating); ?></span>
                            <?php if ($review_count): ?>
                                <span class="review-count text-gray-600 ml-1">
                                    (<?php echo esc_html($review_count); ?> <?php _e('reviews', 'nearmenus'); ?>)
                                </span>
                            <?php endif; ?>
                        </div>
                    <?php endif; ?>
                    
                    <?php if ($currently_open): ?>
                        <span class="status-badge open bg-green-100 text-green-800 px-3 py-1 rounded-full text-sm font-medium">
                            <span class="status-dot w-2 h-2 bg-green-400 rounded-full inline-block mr-2"></span>
                            <?php _e('Open Now', 'nearmenus'); ?>
                        </span>
                    <?php else: ?>
                        <span class="status-badge closed bg-red-100 text-red-800 px-3 py-1 rounded-full text-sm font-medium">
                            <span class="status-dot w-2 h-2 bg-red-400 rounded-full inline-block mr-2"></span>
                            <?php _e('Closed', 'nearmenus'); ?>
                        </span>
                    <?php endif; ?>
                    
                    <?php if ($offers_delivery): ?>
                        <span class="delivery-badge bg-blue-100 text-blue-800 px-3 py-1 rounded-full text-sm font-medium">
                            <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                            </svg>
                            <?php _e('Delivery Available', 'nearmenus'); ?>
                        </span>
                    <?php endif; ?>
                </div>
                
                <!-- Cuisine Tags -->
                <?php if (!empty($cuisines)): ?>
                    <div class="cuisine-tags flex flex-wrap gap-2 mb-3">
                        <?php foreach ($cuisines as $cuisine): 
                            $cuisine_icon = get_term_meta($cuisine->term_id, '_cuisine_icon', true);
                        ?>
                            <span class="cuisine-tag bg-gray-100 text-gray-800 px-3 py-1 rounded-full text-sm">
                                <?php if ($cuisine_icon): ?>
                                    <span class="cuisine-icon mr-1"><?php echo $cuisine_icon; ?></span>
                                <?php endif; ?>
                                <?php echo esc_html($cuisine->name); ?>
                            </span>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
                
                <!-- Price Range -->
                <?php if (!empty($price_ranges)): ?>
                    <div class="price-range mb-3">
                        <span class="text-gray-600 mr-2"><?php _e('Price Range:', 'nearmenus'); ?></span>
                        <?php foreach ($price_ranges as $price): ?>
                            <span class="price-indicator text-lg font-semibold text-green-600">
                                <?php echo esc_html($price->name); ?>
                            </span>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
                
                <!-- Restaurant Description -->
                <div class="restaurant-description text-gray-700 leading-relaxed">
                    <?php the_content(); ?>
                </div>
            </div>
            
            <!-- Action Buttons -->
            <div class="restaurant-actions flex flex-col gap-3 mt-4 lg:mt-0 lg:ml-6">
                <?php if ($phone): ?>
                    <a href="tel:<?php echo esc_attr($phone); ?>" 
                       class="btn btn-primary flex items-center justify-center">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path>
                        </svg>
                        <?php _e('Call Now', 'nearmenus'); ?>
                    </a>
                <?php endif; ?>
                
                <?php if ($website): ?>
                    <a href="<?php echo esc_url($website); ?>" 
                       target="_blank" 
                       rel="noopener noreferrer"
                       class="btn btn-outline flex items-center justify-center">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9v-9m0-9v9"></path>
                        </svg>
                        <?php _e('Visit Website', 'nearmenus'); ?>
                    </a>
                <?php endif; ?>
                
                <?php if ($address): ?>
                    <a href="https://maps.google.com/?q=<?php echo urlencode($address); ?>" 
                       target="_blank" 
                       rel="noopener noreferrer"
                       class="btn btn-outline flex items-center justify-center">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                        </svg>
                        <?php _e('Get Directions', 'nearmenus'); ?>
                    </a>
                <?php endif; ?>
                
                <button type="button" class="btn btn-outline share-btn" data-restaurant-id="<?php echo get_the_ID(); ?>">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.684 13.342C8.886 12.938 9 12.482 9 12c0-.482-.114-.938-.316-1.342m0 2.684a3 3 0 110-2.684m0 2.684l6.632 3.316m-6.632-6l6.632-3.316m0 0a3 3 0 105.367-2.684 3 3 0 00-5.367 2.684zm0 9.316a3 3 0 105.367 2.684 3 3 0 00-5.367-2.684z"></path>
                    </svg>
                    <?php _e('Share', 'nearmenus'); ?>
                </button>
            </div>
        </div>
    </div>
    
    <!-- Special Offers -->
    <?php if ($special_offers): ?>
        <div class="special-offers bg-yellow-50 border-l-4 border-yellow-400 p-4 m-6">
            <div class="flex">
                <div class="flex-shrink-0">
                    <svg class="h-5 w-5 text-yellow-400" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                    </svg>
                </div>
                <div class="ml-3">
                    <h3 class="text-sm font-medium text-yellow-800"><?php _e('Special Offers', 'nearmenus'); ?></h3>
                    <div class="mt-1 text-sm text-yellow-700">
                        <?php echo wp_kses_post(wpautop($special_offers)); ?>
                    </div>
                </div>
            </div>
        </div>
    <?php endif; ?>
    
    <!-- Popular Dishes -->
    <?php if ($popular_dishes): ?>
        <div class="popular-dishes p-6 border-b border-gray-200">
            <h3 class="text-lg font-semibold text-gray-900 mb-3">
                <svg class="w-5 h-5 inline mr-2 text-orange-500" fill="currentColor" viewBox="0 0 20 20">
                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                </svg>
                <?php _e('Popular Dishes', 'nearmenus'); ?>
            </h3>
            <div class="popular-dishes-list">
                <?php
                $dishes = explode(',', $popular_dishes);
                foreach ($dishes as $dish):
                    $dish = trim($dish);
                    if (!empty($dish)):
                ?>
                    <span class="dish-tag inline-block bg-orange-100 text-orange-800 px-3 py-1 rounded-full text-sm mr-2 mb-2">
                        <?php echo esc_html($dish); ?>
                    </span>
                <?php 
                    endif;
                endforeach; 
                ?>
            </div>
        </div>
    <?php endif; ?>
    
    <!-- Features -->
    <?php if (!empty($features)): ?>
        <div class="restaurant-features p-6 border-b border-gray-200">
            <h3 class="text-lg font-semibold text-gray-900 mb-4"><?php _e('Features & Amenities', 'nearmenus'); ?></h3>
            <div class="features-grid grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-3">
                <?php foreach ($features as $feature): 
                    $feature_icon = get_term_meta($feature->term_id, '_feature_icon', true);
                ?>
                    <div class="feature-item flex items-center p-3 bg-gray-50 rounded-lg">
                        <?php if ($feature_icon): ?>
                            <span class="feature-icon text-xl mr-3"><?php echo $feature_icon; ?></span>
                        <?php else: ?>
                            <svg class="w-5 h-5 mr-3 text-green-500" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                            </svg>
                        <?php endif; ?>
                        <span class="text-sm font-medium"><?php echo esc_html($feature->name); ?></span>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    <?php endif; ?>
    
    <!-- Operating Hours -->
    <div class="operating-hours p-6">
        <h3 class="text-lg font-semibold text-gray-900 mb-4">
            <svg class="w-5 h-5 inline mr-2 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
            <?php _e('Operating Hours', 'nearmenus'); ?>
        </h3>
        <div class="hours-list space-y-2">
            <?php
            $day_labels = array(
                'monday' => __('Monday', 'nearmenus'),
                'tuesday' => __('Tuesday', 'nearmenus'),
                'wednesday' => __('Wednesday', 'nearmenus'),
                'thursday' => __('Thursday', 'nearmenus'),
                'friday' => __('Friday', 'nearmenus'),
                'saturday' => __('Saturday', 'nearmenus'),
                'sunday' => __('Sunday', 'nearmenus')
            );
            
            $current_day = strtolower(date('l'));
            
            foreach ($day_labels as $day => $label):
                $day_hours = $hours[$day];
                $is_today = ($day === $current_day);
            ?>
                <div class="hour-item flex justify-between items-center py-2 <?php echo $is_today ? 'bg-blue-50 px-3 rounded' : ''; ?>">
                    <span class="day-label font-medium <?php echo $is_today ? 'text-blue-900' : 'text-gray-700'; ?>">
                        <?php echo $label; ?>
                        <?php if ($is_today): ?>
                            <span class="text-xs text-blue-600 ml-2">(<?php _e('Today', 'nearmenus'); ?>)</span>
                        <?php endif; ?>
                    </span>
                    <span class="hours-text <?php echo $is_today ? 'text-blue-900 font-medium' : 'text-gray-600'; ?>">
                        <?php echo $day_hours ? esc_html($day_hours) : __('Closed', 'nearmenus'); ?>
                    </span>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</div>