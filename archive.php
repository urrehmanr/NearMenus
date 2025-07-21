<?php
/**
 * The template for displaying archive pages
 *
 * @package NearMenus
 * @since 1.0.0
 */

get_header(); ?>

<div class="restaurant-archive">
    
    <!-- Hero Section -->
    <section class="hero-search bg-gradient-to-r from-orange-600 to-red-600 text-white py-16">
        <div class="container mx-auto px-4">
            <div class="flex items-center mb-4">
                <a href="<?php echo esc_url(home_url()); ?>" class="flex items-center space-x-2 text-white hover:text-gray-200 transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                    </svg>
                    <span><?php _e('Back to Home', 'nearmenus'); ?></span>
                </a>
            </div>
            
            <h1 class="text-4xl md:text-5xl font-bold mb-4">
                <?php
                if (is_category() || is_tag() || is_tax()) {
                    echo esc_html(single_term_title('', false));
                } elseif (is_search()) {
                    printf(__('Search Results for: %s', 'nearmenus'), '<span class="font-normal">"' . esc_html(get_search_query()) . '"</span>');
                } else {
                    _e('All Restaurants', 'nearmenus');
                }
                ?>
            </h1>
            
            <?php if (is_tax() && term_description()) : ?>
            <p class="text-xl mb-8 max-w-3xl">
                <?php echo term_description(); ?>
            </p>
            <?php elseif (is_search()) : ?>
            <p class="text-xl mb-8 max-w-3xl">
                <?php _e('Find the best restaurants matching your search criteria.', 'nearmenus'); ?>
            </p>
            <?php else : ?>
            <p class="text-xl mb-8 max-w-3xl">
                <?php _e('Browse through our collection of amazing restaurants and find your next dining destination.', 'nearmenus'); ?>
            </p>
            <?php endif; ?>
            
            <!-- Search Form -->
            <?php get_template_part('template-parts/search-form'); ?>
        </div>
    </section>
    
    <!-- Filters & Results -->
    <section class="py-16">
        <div class="container mx-auto px-4">
            
            <!-- Results Header -->
            <div class="flex flex-col lg:flex-row lg:items-center justify-between mb-8 gap-4">
                <h2 class="text-3xl font-bold">
                    <?php
                    global $wp_query;
                    if ($wp_query->found_posts > 0) {
                        printf(
                            _n('%d Restaurant Found', '%d Restaurants Found', $wp_query->found_posts, 'nearmenus'),
                            $wp_query->found_posts
                        );
                    } else {
                        _e('No Restaurants Found', 'nearmenus');
                    }
                    ?>
                </h2>
                
                <!-- Filter Controls -->
                <?php get_template_part('template-parts/filter-controls'); ?>
            </div>
            
            <!-- Main Content Area -->
            <div class="grid lg:grid-cols-1 gap-8">
                
                <!-- Results Grid -->
                <div class="w-full">
                    <?php if (have_posts()) : ?>
                    <div id="restaurant-results" class="grid md:grid-cols-2 gap-6">
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
                        <div class="text-6xl mb-4">🍽️</div>
                        <h3 class="text-xl font-semibold mb-2"><?php _e('No restaurants found', 'nearmenus'); ?></h3>
                        <p class="text-gray-600 mb-6">
                            <?php 
                            if (is_search()) {
                                _e('Try adjusting your search terms or browse our featured restaurants.', 'nearmenus');
                            } else {
                                _e('Try adjusting your filters to find more restaurants.', 'nearmenus');
                            }
                            ?>
                        </p>
                        <div class="space-x-4">
                            <a href="<?php echo esc_url(home_url()); ?>" class="btn-primary">
                                <?php _e('Browse All Restaurants', 'nearmenus'); ?>
                            </a>
                            <?php if (is_search() || is_tax()) : ?>
                            <button onclick="nearmenus_clearAllFilters()" class="btn-outline">
                                <?php _e('Clear Filters', 'nearmenus'); ?>
                            </button>
                            <?php endif; ?>
                        </div>
                    </div>
                    <?php endif; ?>
                </div>
                
            </div>
        </div>
    </section>
    
    <!-- Popular Cuisines Section -->
    <?php if (!is_search() && !is_tax('cuisine')) : ?>
    <section class="py-16 bg-gray-50">
        <div class="container mx-auto px-4">
            <h2 class="text-2xl font-bold text-center mb-8">
                <?php _e('Popular Cuisines', 'nearmenus'); ?>
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
                <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </div>
    </section>
    <?php endif; ?>
    
    <!-- Call to Action -->
    <section class="py-16 bg-primary-600 text-white">
        <div class="container mx-auto px-4 text-center">
            <h2 class="text-3xl font-bold mb-4">
                <?php _e("Can't Find Your Favorite Restaurant?", 'nearmenus'); ?>
            </h2>
            <p class="text-xl mb-8 max-w-2xl mx-auto">
                <?php _e('Help us improve our listings by suggesting new restaurants or reporting missing information.', 'nearmenus'); ?>
            </p>
            <?php if (get_theme_mod('nearmenus_contact_email')) : ?>
            <a href="mailto:<?php echo esc_attr(get_theme_mod('nearmenus_contact_email')); ?>?subject=Restaurant Suggestion" class="btn-secondary">
                <?php _e('Suggest a Restaurant', 'nearmenus'); ?>
            </a>
            <?php endif; ?>
        </div>
    </section>
    
</div>

<?php get_footer(); ?>