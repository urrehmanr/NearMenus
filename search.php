<?php
/**
 * The template for displaying search results pages
 *
 * @package NearMenus
 * @since 1.0.0
 */

get_header(); ?>

<div class="search-results">
    
    <!-- Search Header -->
    <section class="search-header bg-gray-50 py-12">
        <div class="container mx-auto px-4">
            <div class="flex items-center mb-4">
                <a href="<?php echo esc_url(home_url()); ?>" class="flex items-center space-x-2 text-gray-600 hover:text-primary transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                    </svg>
                    <span><?php _e('Back to Home', 'nearmenus'); ?></span>
                </a>
            </div>
            
            <h1 class="text-3xl md:text-4xl font-bold mb-4">
                <?php
                printf(
                    __('Search Results for: %s', 'nearmenus'),
                    '<span class="text-primary">"' . esc_html(get_search_query()) . '"</span>'
                );
                ?>
            </h1>
            
            <p class="text-gray-600 mb-6">
                <?php
                global $wp_query;
                if ($wp_query->found_posts > 0) {
                    printf(
                        _n('Found %d restaurant matching your search', 'Found %d restaurants matching your search', $wp_query->found_posts, 'nearmenus'),
                        $wp_query->found_posts
                    );
                } else {
                    _e('No restaurants found matching your search criteria.', 'nearmenus');
                }
                ?>
            </p>
            
            <!-- New Search Form -->
            <div class="max-w-2xl">
                <?php get_template_part('template-parts/search-form'); ?>
            </div>
        </div>
    </section>
    
    <!-- Search Results -->
    <section class="py-16">
        <div class="container mx-auto px-4">
            
            <?php if (have_posts()) : ?>
            
            <!-- Results Header -->
            <div class="flex flex-col lg:flex-row lg:items-center justify-between mb-8 gap-4">
                <h2 class="text-2xl font-bold">
                    <?php
                    printf(
                        _n('%d Result', '%d Results', $wp_query->found_posts, 'nearmenus'),
                        $wp_query->found_posts
                    );
                    ?>
                </h2>
                
                <!-- Sort Options -->
                <div class="flex items-center space-x-4">
                    <label for="sort-results" class="text-sm text-gray-600"><?php _e('Sort by:', 'nearmenus'); ?></label>
                    <select id="sort-results" class="px-3 py-2 border rounded-lg text-sm focus:ring-2 focus:ring-primary-500 focus:border-transparent">
                        <option value="relevance"><?php _e('Relevance', 'nearmenus'); ?></option>
                        <option value="rating"><?php _e('Highest Rated', 'nearmenus'); ?></option>
                        <option value="name"><?php _e('Name (A-Z)', 'nearmenus'); ?></option>
                        <option value="newest"><?php _e('Newest First', 'nearmenus'); ?></option>
                    </select>
                </div>
            </div>
            
            <!-- Results Grid -->
            <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-6 mb-12">
                <?php
                while (have_posts()) : the_post();
                    get_template_part('template-parts/restaurant-card');
                endwhile;
                ?>
            </div>
            
            <!-- Pagination -->
            <?php nearmenus_pagination(); ?>
            
            <?php else : ?>
            
            <!-- No Results -->
            <div class="text-center py-16">
                <div class="text-6xl mb-6">🔍</div>
                <h3 class="text-2xl font-semibold mb-4"><?php _e('No restaurants found', 'nearmenus'); ?></h3>
                <p class="text-gray-600 mb-8 max-w-2xl mx-auto">
                    <?php _e('We couldn\'t find any restaurants matching your search. Try adjusting your search terms or browse our featured restaurants below.', 'nearmenus'); ?>
                </p>
                
                <!-- Search Suggestions -->
                <div class="max-w-2xl mx-auto mb-8">
                    <h4 class="text-lg font-semibold mb-4"><?php _e('Search Suggestions:', 'nearmenus'); ?></h4>
                    <div class="text-left space-y-2">
                        <p class="text-gray-600">• <?php _e('Check your spelling', 'nearmenus'); ?></p>
                        <p class="text-gray-600">• <?php _e('Try more general keywords', 'nearmenus'); ?></p>
                        <p class="text-gray-600">• <?php _e('Use cuisine types like "Italian" or "Sushi"', 'nearmenus'); ?></p>
                        <p class="text-gray-600">• <?php _e('Search by location or neighborhood', 'nearmenus'); ?></p>
                    </div>
                </div>
                
                <!-- Action Buttons -->
                <div class="space-x-4">
                    <a href="<?php echo esc_url(home_url()); ?>" class="btn-primary">
                        <?php _e('Browse All Restaurants', 'nearmenus'); ?>
                    </a>
                    <button onclick="nearmenus_toggleSearch()" class="btn-outline">
                        <?php _e('New Search', 'nearmenus'); ?>
                    </button>
                </div>
            </div>
            
            <?php endif; ?>
            
        </div>
    </section>
    
    <!-- Popular Cuisines -->
    <?php if (!have_posts()) : ?>
    <section class="py-16 bg-gray-50">
        <div class="container mx-auto px-4">
            <h2 class="text-2xl font-bold text-center mb-8">
                <?php _e('Try Popular Cuisines', 'nearmenus'); ?>
            </h2>
            
            <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-6 gap-4">
                <?php
                $popular_cuisines = get_terms(array(
                    'taxonomy' => 'cuisine',
                    'hide_empty' => true,
                    'orderby' => 'count',
                    'order' => 'DESC',
                    'number' => 12,
                ));
                
                if ($popular_cuisines && !is_wp_error($popular_cuisines)) :
                    foreach ($popular_cuisines as $cuisine) :
                        $cuisine_icon = nearmenus_get_cuisine_icon($cuisine->slug);
                ?>
                <a href="<?php echo esc_url(get_term_link($cuisine)); ?>" class="bg-white rounded-lg p-4 text-center hover:shadow-md transition-shadow group">
                    <div class="text-2xl mb-2 group-hover:scale-110 transition-transform">
                        <?php echo $cuisine_icon; ?>
                    </div>
                    <h3 class="font-medium text-sm"><?php echo esc_html($cuisine->name); ?></h3>
                    <p class="text-xs text-gray-500 mt-1">
                        <?php echo esc_html($cuisine->count); ?> <?php _e('restaurants', 'nearmenus'); ?>
                    </p>
                </a>
                <?php endforeach; endif; ?>
            </div>
        </div>
    </section>
    <?php endif; ?>
    
</div>

<?php get_footer(); ?>