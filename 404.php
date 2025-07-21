<?php
/**
 * The template for displaying 404 pages (not found)
 *
 * @package NearMenus
 * @since 1.0.0
 */

get_header(); ?>

<div class="error-404">
    
    <!-- 404 Header -->
    <section class="error-header bg-gradient-to-r from-orange-600 to-red-600 text-white py-20">
        <div class="container mx-auto px-4 text-center">
            <div class="text-8xl md:text-9xl font-bold mb-4 opacity-50">404</div>
            <h1 class="text-3xl md:text-4xl font-bold mb-4">
                <?php _e('Oops! Page Not Found', 'nearmenus'); ?>
            </h1>
            <p class="text-xl mb-8 max-w-2xl mx-auto">
                <?php _e('The page you\'re looking for seems to have wandered off like a hungry customer looking for the perfect meal.', 'nearmenus'); ?>
            </p>
        </div>
    </section>
    
    <!-- Error Content -->
    <section class="py-16">
        <div class="container mx-auto px-4">
            
            <!-- Error Message -->
            <div class="text-center mb-12">
                <div class="text-6xl mb-6">🍽️</div>
                <h2 class="text-2xl font-bold mb-4"><?php _e('Let\'s Get You Back to Great Food!', 'nearmenus'); ?></h2>
                <p class="text-gray-600 mb-8 max-w-2xl mx-auto">
                    <?php _e('Don\'t worry, it happens to the best of us. Here are some ways to find what you\'re looking for:', 'nearmenus'); ?>
                </p>
            </div>
            
            <!-- Search Section -->
            <div class="max-w-2xl mx-auto mb-12">
                <h3 class="text-lg font-semibold mb-4 text-center"><?php _e('Search for Restaurants', 'nearmenus'); ?></h3>
                <?php get_template_part('template-parts/search-form'); ?>
            </div>
            
            <!-- Quick Actions -->
            <div class="grid md:grid-cols-3 gap-8 mb-12">
                
                <!-- Home -->
                <div class="text-center">
                    <div class="bg-primary-100 w-16 h-16 rounded-full flex items-center justify-center mx-auto mb-4">
                        <svg class="w-8 h-8 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
                        </svg>
                    </div>
                    <h4 class="font-semibold mb-2"><?php _e('Go Home', 'nearmenus'); ?></h4>
                    <p class="text-gray-600 mb-4"><?php _e('Start fresh with our featured restaurants', 'nearmenus'); ?></p>
                    <a href="<?php echo esc_url(home_url()); ?>" class="btn-primary">
                        <?php _e('Go to Homepage', 'nearmenus'); ?>
                    </a>
                </div>
                
                <!-- Browse All -->
                <div class="text-center">
                    <div class="bg-orange-100 w-16 h-16 rounded-full flex items-center justify-center mx-auto mb-4">
                        <svg class="w-8 h-8 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-4m-5 0H3m2 0h4M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 8v-8a1 1 0 011-1h4a1 1 0 011 1v8M7 7h10"></path>
                        </svg>
                    </div>
                    <h4 class="font-semibold mb-2"><?php _e('Browse All', 'nearmenus'); ?></h4>
                    <p class="text-gray-600 mb-4"><?php _e('Explore our complete restaurant directory', 'nearmenus'); ?></p>
                    <a href="<?php echo esc_url(get_post_type_archive_link('post')); ?>" class="btn-outline">
                        <?php _e('View All Restaurants', 'nearmenus'); ?>
                    </a>
                </div>
                
                <!-- Contact -->
                <div class="text-center">
                    <div class="bg-green-100 w-16 h-16 rounded-full flex items-center justify-center mx-auto mb-4">
                        <svg class="w-8 h-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
                        </svg>
                    </div>
                    <h4 class="font-semibold mb-2"><?php _e('Need Help?', 'nearmenus'); ?></h4>
                    <p class="text-gray-600 mb-4"><?php _e('Get in touch with our support team', 'nearmenus'); ?></p>
                    <?php if (get_theme_mod('nearmenus_contact_email')) : ?>
                    <a href="mailto:<?php echo esc_attr(get_theme_mod('nearmenus_contact_email')); ?>" class="btn-outline">
                        <?php _e('Contact Us', 'nearmenus'); ?>
                    </a>
                    <?php else : ?>
                    <span class="text-gray-500 text-sm"><?php _e('Contact info not available', 'nearmenus'); ?></span>
                    <?php endif; ?>
                </div>
                
            </div>
            
        </div>
    </section>
    
    <!-- Popular Restaurants -->
    <section class="py-16 bg-gray-50">
        <div class="container mx-auto px-4">
            <h2 class="text-2xl font-bold text-center mb-8">
                <?php _e('Popular Restaurants', 'nearmenus'); ?>
            </h2>
            
            <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-6">
                <?php
                $popular_restaurants = new WP_Query(array(
                    'post_type' => 'post',
                    'posts_per_page' => 6,
                    'meta_key' => '_restaurant_rating',
                    'orderby' => 'meta_value_num',
                    'order' => 'DESC',
                    'meta_query' => array(
                        array(
                            'key' => '_restaurant_rating',
                            'value' => '4.0',
                            'compare' => '>='
                        )
                    )
                ));
                
                if ($popular_restaurants->have_posts()) :
                    while ($popular_restaurants->have_posts()) : $popular_restaurants->the_post();
                        get_template_part('template-parts/restaurant-card');
                    endwhile;
                    wp_reset_postdata();
                else :
                ?>
                <div class="col-span-full text-center py-8">
                    <p class="text-gray-600"><?php _e('No restaurants available at the moment.', 'nearmenus'); ?></p>
                </div>
                <?php endif; ?>
            </div>
        </div>
    </section>
    
    <!-- Popular Cuisines -->
    <section class="py-16">
        <div class="container mx-auto px-4">
            <h2 class="text-2xl font-bold text-center mb-8">
                <?php _e('Browse by Cuisine', 'nearmenus'); ?>
            </h2>
            
            <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-6 gap-4">
                <?php
                $cuisines = get_terms(array(
                    'taxonomy' => 'cuisine',
                    'hide_empty' => true,
                    'number' => 12,
                    'orderby' => 'count',
                    'order' => 'DESC'
                ));
                
                if ($cuisines && !is_wp_error($cuisines)) :
                    foreach ($cuisines as $cuisine) :
                        $cuisine_icon = nearmenus_get_cuisine_icon($cuisine->slug);
                ?>
                <a href="<?php echo esc_url(get_term_link($cuisine)); ?>" class="bg-white rounded-lg p-4 text-center hover:shadow-md transition-shadow group border">
                    <div class="text-2xl mb-2 group-hover:scale-110 transition-transform">
                        <?php echo $cuisine_icon; ?>
                    </div>
                    <h3 class="font-medium text-sm"><?php echo esc_html($cuisine->name); ?></h3>
                    <p class="text-xs text-gray-500 mt-1">
                        <?php echo esc_html($cuisine->count); ?> <?php _e('restaurants', 'nearmenus'); ?>
                    </p>
                </a>
                <?php endforeach; else : ?>
                <div class="col-span-full text-center py-8">
                    <p class="text-gray-600"><?php _e('No cuisine categories available.', 'nearmenus'); ?></p>
                </div>
                <?php endif; ?>
            </div>
        </div>
    </section>
    
    <!-- Error Reporting -->
    <section class="py-12 bg-gray-100">
        <div class="container mx-auto px-4 text-center">
            <h3 class="text-lg font-semibold mb-2"><?php _e('Found a Broken Link?', 'nearmenus'); ?></h3>
            <p class="text-gray-600 mb-4">
                <?php _e('Help us improve by reporting broken links or missing pages.', 'nearmenus'); ?>
            </p>
            <?php if (get_theme_mod('nearmenus_contact_email')) : ?>
            <a href="mailto:<?php echo esc_attr(get_theme_mod('nearmenus_contact_email')); ?>?subject=Broken Link Report&body=I found a broken link at: <?php echo esc_url($_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI']); ?>" class="text-primary hover:underline">
                <?php _e('Report This Issue', 'nearmenus'); ?>
            </a>
            <?php endif; ?>
        </div>
    </section>
    
</div>

<?php get_footer(); ?>