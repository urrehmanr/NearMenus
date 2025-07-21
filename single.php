<?php
/**
 * The template for displaying single restaurant posts
 *
 * @package NearMenus
 * @since 1.0.0
 */

get_header(); ?>

<?php while (have_posts()) : the_post(); ?>

<div class="restaurant-single">
    
    <!-- Header with Back Button -->
    <header class="border-b bg-white sticky top-0 z-50">
        <div class="container mx-auto px-4 py-4 flex items-center justify-between">
            <a href="<?php echo esc_url(home_url()); ?>" class="flex items-center gap-2 text-gray-600 hover:text-primary transition-colors">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                </svg>
                <span class="font-medium"><?php _e('Back to Restaurants', 'nearmenus'); ?></span>
            </a>
            <button class="btn-outline share-button" data-title="<?php echo esc_attr(get_the_title()); ?>" data-url="<?php echo esc_url(get_permalink()); ?>">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.684 13.342C8.886 12.938 9 12.482 9 12c0-.482-.114-.938-.316-1.342m0 2.684a3 3 0 110-2.684m0 2.684l6.632 3.316m-6.632-6l6.632-3.316m0 0a3 3 0 105.367-2.684 3 3 0 00-5.367 2.684zm0 9.316a3 3 0 105.367 2.684 3 3 0 00-5.367-2.684z"></path>
                </svg>
                <?php _e('Share', 'nearmenus'); ?>
            </button>
        </div>
    </header>
    
    <!-- Breadcrumbs -->
    <div class="border-b bg-gray-50">
        <div class="container mx-auto px-4 py-3">
            <?php nearmenus_breadcrumbs(); ?>
        </div>
    </div>
    
    <!-- Main Content -->
    <main class="flex-1">
        <section class="container mx-auto px-4 py-8 md:py-12">
            <div class="grid lg:grid-cols-3 gap-8">
                
                <!-- Left Column - Main Content -->
                <div class="lg:col-span-2 space-y-8">
                    
                    <!-- Restaurant Info -->
                    <?php get_template_part('template-parts/restaurant-info'); ?>
                    
                    <!-- Menu Display -->
                    <?php get_template_part('template-parts/restaurant-menu'); ?>
                    
                    <!-- Gallery -->
                    <?php get_template_part('template-parts/restaurant-gallery'); ?>
                    
                    <!-- Restaurant Content -->
                    <?php if (get_the_content()) : ?>
                    <div class="restaurant-content">
                        <h2 class="text-2xl font-bold mb-4"><?php _e('About This Restaurant', 'nearmenus'); ?></h2>
                        <div class="prose max-w-none">
                            <?php the_content(); ?>
                        </div>
                    </div>
                    <?php endif; ?>
                    
                    <!-- Tags -->
                    <?php
                    $cuisines = get_the_terms(get_the_ID(), 'cuisine');
                    $locations = get_the_terms(get_the_ID(), 'location');
                    $features = get_the_terms(get_the_ID(), 'features');
                    
                    if ($cuisines || $locations || $features) :
                    ?>
                    <div class="restaurant-tags">
                        <h3 class="text-lg font-semibold mb-3"><?php _e('Tags', 'nearmenus'); ?></h3>
                        <div class="flex flex-wrap gap-2">
                            <?php if ($cuisines && !is_wp_error($cuisines)) : ?>
                                <?php foreach ($cuisines as $cuisine) : ?>
                                <a href="<?php echo esc_url(get_term_link($cuisine)); ?>" class="bg-primary-100 text-primary-700 px-3 py-1 rounded-full text-sm hover:bg-primary-200 transition-colors">
                                    <?php echo esc_html($cuisine->name); ?>
                                </a>
                                <?php endforeach; ?>
                            <?php endif; ?>
                            
                            <?php if ($locations && !is_wp_error($locations)) : ?>
                                <?php foreach ($locations as $location) : ?>
                                <a href="<?php echo esc_url(get_term_link($location)); ?>" class="bg-gray-100 text-gray-700 px-3 py-1 rounded-full text-sm hover:bg-gray-200 transition-colors">
                                    <?php echo esc_html($location->name); ?>
                                </a>
                                <?php endforeach; ?>
                            <?php endif; ?>
                            
                            <?php if ($features && !is_wp_error($features)) : ?>
                                <?php foreach (array_slice($features, 0, 5) as $feature) : ?>
                                <span class="bg-green-100 text-green-700 px-3 py-1 rounded-full text-sm">
                                    <?php echo esc_html($feature->name); ?>
                                </span>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </div>
                    </div>
                    <?php endif; ?>
                    
                </div>
                
                <!-- Right Column - Sidebar -->
                <div class="lg:col-span-1">
                    <?php get_template_part('template-parts/contact-widget'); ?>
                    
                    <!-- Related Restaurants -->
                    <?php get_template_part('template-parts/related-restaurants'); ?>
                </div>
                
            </div>
        </section>
    </main>
    
    <!-- Mobile Bottom Navigation -->
    <?php get_template_part('template-parts/mobile-navigation'); ?>
    
</div>

<?php endwhile; ?>

<!-- Navigation to other posts -->
<div class="bg-gray-50 py-8">
    <div class="container mx-auto px-4">
        <div class="flex justify-between items-center">
            <?php
            $prev_post = get_previous_post();
            $next_post = get_next_post();
            ?>
            
            <div class="flex-1">
                <?php if ($prev_post) : ?>
                <a href="<?php echo esc_url(get_permalink($prev_post)); ?>" class="flex items-center text-gray-600 hover:text-primary group">
                    <svg class="w-5 h-5 mr-2 group-hover:-translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                    </svg>
                    <div>
                        <div class="text-sm text-gray-500"><?php _e('Previous Restaurant', 'nearmenus'); ?></div>
                        <div class="font-medium"><?php echo esc_html(get_the_title($prev_post)); ?></div>
                    </div>
                </a>
                <?php endif; ?>
            </div>
            
            <div class="flex-1 text-right">
                <?php if ($next_post) : ?>
                <a href="<?php echo esc_url(get_permalink($next_post)); ?>" class="flex items-center justify-end text-gray-600 hover:text-primary group">
                    <div class="text-right">
                        <div class="text-sm text-gray-500"><?php _e('Next Restaurant', 'nearmenus'); ?></div>
                        <div class="font-medium"><?php echo esc_html(get_the_title($next_post)); ?></div>
                    </div>
                    <svg class="w-5 h-5 ml-2 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                    </svg>
                </a>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<?php get_footer(); ?>