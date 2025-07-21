<?php
/**
 * The template for displaying cuisine taxonomy pages
 *
 * @package NearMenus
 * @since 1.0.0
 */

get_header(); 

$cuisine = get_queried_object();
?>

<div class="cuisine-archive">
    
    <!-- Hero Section -->
    <section class="hero-search bg-gradient-to-r from-orange-600 to-red-600 text-white py-16">
        <div class="container mx-auto px-4">
            <div class="flex items-center mb-4">
                <a href="<?php echo esc_url(home_url()); ?>" class="flex items-center space-x-2 text-white hover:text-gray-200 transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                    </svg>
                    <span><?php _e('Back to All Restaurants', 'nearmenus'); ?></span>
                </a>
            </div>
            
            <div class="flex items-center mb-4">
                <div class="text-6xl mr-6">
                    <?php echo nearmenus_get_cuisine_icon($cuisine->slug); ?>
                </div>
                <div>
                    <h1 class="text-4xl md:text-5xl font-bold mb-2">
                        <?php printf(__('%s Restaurants', 'nearmenus'), esc_html($cuisine->name)); ?>
                    </h1>
                    <?php if ($cuisine->description) : ?>
                    <p class="text-xl max-w-3xl">
                        <?php echo esc_html($cuisine->description); ?>
                    </p>
                    <?php endif; ?>
                </div>
            </div>
            
            <!-- Quick Stats -->
            <div class="flex items-center space-x-6 mb-8 text-white/80">
                <div class="flex items-center">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-4m-5 0H3m2 0h4M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 8v-8a1 1 0 011-1h4a1 1 0 011 1v8M7 7h10"></path>
                    </svg>
                    <span>
                        <?php printf(_n('%d Restaurant', '%d Restaurants', $cuisine->count, 'nearmenus'), $cuisine->count); ?>
                    </span>
                </div>
                
                <?php
                // Get average rating for this cuisine
                $avg_rating = nearmenus_get_cuisine_average_rating($cuisine->term_id);
                if ($avg_rating) :
                ?>
                <div class="flex items-center">
                    <svg class="w-5 h-5 mr-2 text-yellow-400" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                    </svg>
                    <span><?php printf(__('Avg Rating: %s', 'nearmenus'), number_format($avg_rating, 1)); ?></span>
                </div>
                <?php endif; ?>
            </div>
            
            <!-- Search Form -->
            <?php get_template_part('template-parts/search-form'); ?>
        </div>
    </section>
    
    <!-- Restaurant Listings -->
    <section class="py-16">
        <div class="container mx-auto px-4">
            
            <!-- Results Header -->
            <div class="flex flex-col lg:flex-row lg:items-center justify-between mb-8 gap-4">
                <h2 class="text-3xl font-bold">
                    <?php
                    global $wp_query;
                    printf(
                        _n('%d %s Restaurant', '%d %s Restaurants', $wp_query->found_posts, 'nearmenus'),
                        $wp_query->found_posts,
                        esc_html($cuisine->name)
                    );
                    ?>
                </h2>
                
                <!-- Sort Options -->
                <div class="flex items-center space-x-4">
                    <label for="sort-restaurants" class="text-sm text-gray-600"><?php _e('Sort by:', 'nearmenus'); ?></label>
                    <select id="sort-restaurants" class="px-3 py-2 border rounded-lg text-sm focus:ring-2 focus:ring-primary-500 focus:border-transparent">
                        <option value="rating"><?php _e('Highest Rated', 'nearmenus'); ?></option>
                        <option value="name"><?php _e('Name (A-Z)', 'nearmenus'); ?></option>
                        <option value="reviews"><?php _e('Most Reviewed', 'nearmenus'); ?></option>
                        <option value="newest"><?php _e('Newest First', 'nearmenus'); ?></option>
                    </select>
                </div>
            </div>
            
            <!-- Filters -->
            <div class="mb-8">
                <div class="bg-white rounded-lg shadow-sm border p-6">
                    <h3 class="font-semibold mb-4 text-gray-700"><?php _e('Filter Results', 'nearmenus'); ?></h3>
                    
                    <div class="grid md:grid-cols-2 lg:grid-cols-4 gap-4">
                        <!-- Location Filter -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2"><?php _e('Location', 'nearmenus'); ?></label>
                            <select class="w-full px-3 py-2 border rounded-lg text-sm filter-select" data-filter="location">
                                <option value=""><?php _e('All Locations', 'nearmenus'); ?></option>
                                <?php
                                $locations = get_terms(array(
                                    'taxonomy' => 'location',
                                    'hide_empty' => true,
                                ));
                                foreach ($locations as $location) {
                                    echo '<option value="' . esc_attr($location->slug) . '">' . esc_html($location->name) . '</option>';
                                }
                                ?>
                            </select>
                        </div>
                        
                        <!-- Price Range Filter -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2"><?php _e('Price Range', 'nearmenus'); ?></label>
                            <select class="w-full px-3 py-2 border rounded-lg text-sm filter-select" data-filter="price_range">
                                <option value=""><?php _e('Any Price', 'nearmenus'); ?></option>
                                <?php
                                $price_ranges = get_terms(array(
                                    'taxonomy' => 'price_range',
                                    'hide_empty' => true,
                                ));
                                foreach ($price_ranges as $price_range) {
                                    echo '<option value="' . esc_attr($price_range->slug) . '">' . esc_html($price_range->name) . '</option>';
                                }
                                ?>
                            </select>
                        </div>
                        
                        <!-- Rating Filter -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2"><?php _e('Minimum Rating', 'nearmenus'); ?></label>
                            <select class="w-full px-3 py-2 border rounded-lg text-sm filter-select" data-filter="rating">
                                <option value=""><?php _e('Any Rating', 'nearmenus'); ?></option>
                                <option value="4.5"><?php _e('4.5+ Stars', 'nearmenus'); ?></option>
                                <option value="4.0"><?php _e('4.0+ Stars', 'nearmenus'); ?></option>
                                <option value="3.5"><?php _e('3.5+ Stars', 'nearmenus'); ?></option>
                            </select>
                        </div>
                        
                        <!-- Features Filter -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2"><?php _e('Features', 'nearmenus'); ?></label>
                            <select class="w-full px-3 py-2 border rounded-lg text-sm filter-select" data-filter="features">
                                <option value=""><?php _e('All Features', 'nearmenus'); ?></option>
                                <option value="delivery"><?php _e('Delivery Available', 'nearmenus'); ?></option>
                                <option value="outdoor-seating"><?php _e('Outdoor Seating', 'nearmenus'); ?></option>
                                <option value="vegan-options"><?php _e('Vegan Options', 'nearmenus'); ?></option>
                                <option value="parking"><?php _e('Parking Available', 'nearmenus'); ?></option>
                            </select>
                        </div>
                    </div>
                    
                    <div class="flex justify-between items-center mt-4">
                        <button class="text-sm text-gray-600 hover:text-primary" onclick="nearmenus_clearFilters()">
                            <?php _e('Clear all filters', 'nearmenus'); ?>
                        </button>
                        <div class="text-sm text-gray-600">
                            <span id="filter-count">0</span> <?php _e('filters applied', 'nearmenus'); ?>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Restaurant Grid -->
            <?php if (have_posts()) : ?>
            <div id="cuisine-restaurant-grid" class="grid md:grid-cols-2 lg:grid-cols-3 gap-6">
                <?php
                while (have_posts()) : the_post();
                    get_template_part('template-parts/restaurant-card');
                endwhile;
                ?>
            </div>
            
            <!-- Pagination -->
            <div class="mt-12">
                <?php nearmenus_pagination(); ?>
            </div>
            
            <?php else : ?>
            <div class="text-center py-16">
                <div class="text-6xl mb-4"><?php echo nearmenus_get_cuisine_icon($cuisine->slug); ?></div>
                <h3 class="text-xl font-semibold mb-2">
                    <?php printf(__('No %s restaurants found', 'nearmenus'), strtolower(esc_html($cuisine->name))); ?>
                </h3>
                <p class="text-gray-600 mb-6">
                    <?php _e('Check back later for new restaurants in this category.', 'nearmenus'); ?>
                </p>
                <a href="<?php echo esc_url(home_url()); ?>" class="btn-primary">
                    <?php _e('Browse All Restaurants', 'nearmenus'); ?>
                </a>
            </div>
            <?php endif; ?>
            
        </div>
    </section>
    
    <!-- Related Cuisines -->
    <section class="py-16 bg-gray-50">
        <div class="container mx-auto px-4">
            <h2 class="text-2xl font-bold text-center mb-8">
                <?php _e('Explore Other Cuisines', 'nearmenus'); ?>
            </h2>
            
            <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-6 gap-4">
                <?php
                $related_cuisines = get_terms(array(
                    'taxonomy' => 'cuisine',
                    'hide_empty' => true,
                    'exclude' => array($cuisine->term_id),
                    'number' => 12,
                ));
                
                if ($related_cuisines && !is_wp_error($related_cuisines)) :
                    foreach ($related_cuisines as $related_cuisine) :
                        $related_icon = nearmenus_get_cuisine_icon($related_cuisine->slug);
                ?>
                <a href="<?php echo esc_url(get_term_link($related_cuisine)); ?>" class="bg-white rounded-lg p-4 text-center hover:shadow-md transition-shadow group">
                    <div class="text-2xl mb-2 group-hover:scale-110 transition-transform">
                        <?php echo $related_icon; ?>
                    </div>
                    <h3 class="font-medium text-sm"><?php echo esc_html($related_cuisine->name); ?></h3>
                    <p class="text-xs text-gray-500 mt-1">
                        <?php echo esc_html($related_cuisine->count); ?> <?php _e('restaurants', 'nearmenus'); ?>
                    </p>
                </a>
                <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </div>
    </section>
    
</div>

<?php get_footer(); ?>