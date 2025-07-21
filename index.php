<?php
/**
 * The main template file (Homepage)
 *
 * @package NearMenus
 * @since 1.0.0
 */

get_header(); ?>

<div class="nearmenus-home">
    
    <!-- Hero Section with Search -->
    <section class="hero-section bg-gradient-to-r from-orange-600 to-red-600 text-white py-16 md:py-20">
        <div class="container mx-auto px-4 text-center">
            <h1 class="text-4xl md:text-5xl lg:text-6xl font-bold mb-4 md:mb-6">
                <?php 
                $hero_title = get_theme_mod('nearmenus_hero_title', __('Discover Amazing Restaurants', 'nearmenus'));
                echo esc_html($hero_title);
                ?>
            </h1>
            <p class="text-lg md:text-xl lg:text-2xl mb-6 md:mb-8 max-w-3xl mx-auto">
                <?php 
                $hero_subtitle = get_theme_mod('nearmenus_hero_subtitle', __('Explore the best dining experiences in your city. From fine dining to casual eats, find your perfect meal.', 'nearmenus'));
                echo esc_html($hero_subtitle);
                ?>
            </p>
            
            <!-- Search Form -->
            <?php get_template_part('template-parts/search-form'); ?>
        </div>
    </section>
    
    <!-- Featured Restaurants Section -->
    <section id="restaurants" class="py-16">
        <div class="container mx-auto px-4">
            <div class="flex flex-col md:flex-row md:items-center justify-between mb-8 gap-4">
                <h2 class="text-3xl font-bold">
                    <?php _e('Featured Restaurants', 'nearmenus'); ?>
                </h2>
                
                <!-- Filter Controls -->
                <?php get_template_part('template-parts/filter-controls'); ?>
            </div>
            
            <!-- Restaurant Grid -->
            <div id="restaurant-grid" class="grid md:grid-cols-2 lg:grid-cols-2 gap-8">
                <?php
                $posts_per_page = get_theme_mod('nearmenus_posts_per_page', 8);
                
                // First try to get restaurant posts with meta
                $featured_restaurants = new WP_Query(array(
                    'post_type' => 'post',
                    'posts_per_page' => $posts_per_page,
                    'meta_query' => array(
                        array(
                            'key' => '_restaurant_rating',
                            'value' => '',
                            'compare' => '!='
                        )
                    ),
                    'meta_key' => '_restaurant_rating',
                    'orderby' => 'meta_value_num',
                    'order' => 'DESC',
                ));
                
                // If no restaurant posts found, show all published posts
                if (!$featured_restaurants->have_posts()) {
                    wp_reset_postdata();
                    $featured_restaurants = new WP_Query(array(
                        'post_type' => 'post',
                        'posts_per_page' => $posts_per_page,
                        'post_status' => 'publish',
                        'orderby' => 'date',
                        'order' => 'DESC',
                    ));
                }
                
                if ($featured_restaurants->have_posts()) :
                    while ($featured_restaurants->have_posts()) : $featured_restaurants->the_post();
                        get_template_part('template-parts/restaurant-card');
                    endwhile;
                    wp_reset_postdata();
                else :
                    echo '<div class="col-span-full text-center py-16">';
                    echo '<div class="text-6xl mb-4">🍽️</div>';
                    echo '<h3 class="text-xl font-semibold mb-2">' . __('No posts found', 'nearmenus') . '</h3>';
                    echo '<p class="text-gray-600 mb-4">' . __('Start by creating some posts. They will display here automatically.', 'nearmenus') . '</p>';
                    if (current_user_can('publish_posts')) {
                        echo '<a href="' . admin_url('post-new.php') . '" class="btn-primary">' . __('Create First Post', 'nearmenus') . '</a>';
                    }
                    echo '</div>';
                endif;
                ?>
            </div>
            
            <!-- Load More Button -->
            <?php if ($featured_restaurants->max_num_pages > 1) : ?>
            <div class="text-center mt-12">
                <button id="load-more-restaurants" class="btn-primary" data-page="1" data-max-pages="<?php echo $featured_restaurants->max_num_pages; ?>">
                    <?php _e('Load More Restaurants', 'nearmenus'); ?>
                </button>
            </div>
            <?php endif; ?>
            
            <!-- View All Link -->
            <div class="text-center mt-8">
                <a href="<?php echo esc_url(home_url('/?post_type=post')); ?>" class="text-primary font-semibold hover:underline">
                    <?php _e('View All Restaurants →', 'nearmenus'); ?>
                </a>
            </div>
        </div>
    </section>
    
    <!-- Categories Section -->
    <section class="py-16 bg-gray-50">
        <div class="container mx-auto px-4">
            <h2 class="text-3xl font-bold text-center mb-12">
                <?php _e('Browse by Cuisine', 'nearmenus'); ?>
            </h2>
            
            <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-6">
                <?php
                $cuisines = get_terms(array(
                    'taxonomy' => 'cuisine',
                    'hide_empty' => true,
                    'number' => 12,
                ));
                
                if ($cuisines && !is_wp_error($cuisines)) :
                    foreach ($cuisines as $cuisine) :
                        $cuisine_icon = nearmenus_get_cuisine_icon($cuisine->slug);
                ?>
                <a href="<?php echo esc_url(get_term_link($cuisine)); ?>" class="cuisine-card bg-white rounded-lg p-6 text-center hover:shadow-lg transition-shadow group">
                    <div class="cuisine-icon mb-4 text-4xl group-hover:scale-110 transition-transform">
                        <?php echo $cuisine_icon; ?>
                    </div>
                    <h3 class="font-semibold text-lg mb-2"><?php echo esc_html($cuisine->name); ?></h3>
                    <p class="text-gray-600 text-sm">
                        <?php printf(_n('%d restaurant', '%d restaurants', $cuisine->count, 'nearmenus'), $cuisine->count); ?>
                    </p>
                </a>
                <?php 
                    endforeach;
                else : 
                ?>
                <div class="col-span-full text-center py-8">
                    <p class="text-gray-600"><?php _e('No cuisine categories found. Please add some restaurant categories.', 'nearmenus'); ?></p>
                </div>
                <?php endif; ?>
            </div>
        </div>
    </section>
    
    <!-- Call to Action Section -->
    <section class="py-16 bg-gradient-to-r from-primary-600 to-primary-700 text-white">
        <div class="container mx-auto px-4 text-center">
            <h2 class="text-3xl font-bold mb-4">
                <?php _e('List Your Restaurant', 'nearmenus'); ?>
            </h2>
            <p class="text-xl mb-8 max-w-2xl mx-auto">
                <?php _e('Join thousands of restaurants on our platform and reach more customers.', 'nearmenus'); ?>
            </p>
            <?php if (get_theme_mod('nearmenus_contact_email')) : ?>
            <a href="mailto:<?php echo esc_attr(get_theme_mod('nearmenus_contact_email')); ?>" class="btn-secondary">
                <?php _e('Get Started Today', 'nearmenus'); ?>
            </a>
            <?php endif; ?>
        </div>
    </section>
    
</div>

<?php get_footer(); ?>