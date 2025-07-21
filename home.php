<?php
/**
 * The template for displaying the blog home page
 * Used when the front page displays the latest posts
 *
 * @package NearMenus
 * @since 1.0.0
 */

get_header(); ?>

<div class="blog-home">
    
    <!-- Blog Header -->
    <section class="blog-header bg-gray-50 py-12">
        <div class="container mx-auto px-4 text-center">
            <h1 class="text-3xl md:text-4xl font-bold mb-4">
                <?php _e('Restaurant Blog', 'nearmenus'); ?>
            </h1>
            <p class="text-gray-600 mb-6 max-w-2xl mx-auto">
                <?php _e('Discover the latest news, reviews, and culinary adventures from the world of restaurants.', 'nearmenus'); ?>
            </p>
        </div>
    </section>
    
    <!-- Blog Posts -->
    <section class="py-16">
        <div class="container mx-auto px-4">
            
            <div class="grid lg:grid-cols-3 gap-8">
                
                <!-- Main Content -->
                <div class="lg:col-span-2">
                    
                    <?php if (have_posts()) : ?>
                    
                    <!-- Posts Grid -->
                    <div class="space-y-8">
                        <?php
                        while (have_posts()) : the_post();
                            // Check if this is a restaurant post or blog post
                            $is_restaurant = has_term('', 'cuisine') || get_post_meta(get_the_ID(), '_restaurant_rating', true);
                            
                            if ($is_restaurant) :
                                // Display restaurant card
                                get_template_part('template-parts/restaurant-card');
                            else :
                                // Display blog post
                        ?>
                        <article class="blog-post bg-white rounded-lg shadow-sm border overflow-hidden">
                            <?php if (has_post_thumbnail()) : ?>
                            <div class="post-thumbnail">
                                <a href="<?php the_permalink(); ?>">
                                    <?php the_post_thumbnail('large', array('class' => 'w-full h-48 object-cover')); ?>
                                </a>
                            </div>
                            <?php endif; ?>
                            
                            <div class="p-6">
                                <div class="post-meta mb-3">
                                    <div class="flex items-center text-sm text-gray-500 space-x-4">
                                        <time datetime="<?php echo get_the_date('c'); ?>">
                                            <?php echo get_the_date(); ?>
                                        </time>
                                        <span>•</span>
                                        <span><?php echo esc_html(get_the_author()); ?></span>
                                        <?php if (get_the_category()) : ?>
                                        <span>•</span>
                                        <span><?php the_category(', '); ?></span>
                                        <?php endif; ?>
                                    </div>
                                </div>
                                
                                <h2 class="post-title mb-3">
                                    <a href="<?php the_permalink(); ?>" class="text-xl font-bold hover:text-primary transition-colors">
                                        <?php the_title(); ?>
                                    </a>
                                </h2>
                                
                                <div class="post-excerpt text-gray-600 mb-4">
                                    <?php the_excerpt(); ?>
                                </div>
                                
                                <a href="<?php the_permalink(); ?>" class="btn-outline">
                                    <?php _e('Read More', 'nearmenus'); ?>
                                </a>
                            </div>
                        </article>
                        <?php
                            endif;
                        endwhile;
                        ?>
                    </div>
                    
                    <!-- Pagination -->
                    <div class="mt-12">
                        <?php nearmenus_pagination(); ?>
                    </div>
                    
                    <?php else : ?>
                    
                    <!-- No Posts -->
                    <div class="text-center py-16">
                        <div class="text-6xl mb-6">📝</div>
                        <h2 class="text-2xl font-bold mb-4"><?php _e('No posts found', 'nearmenus'); ?></h2>
                        <p class="text-gray-600 mb-8">
                            <?php _e('There are no blog posts available at the moment. Check back later for the latest restaurant news and reviews.', 'nearmenus'); ?>
                        </p>
                        <a href="<?php echo esc_url(home_url()); ?>" class="btn-primary">
                            <?php _e('Explore Restaurants', 'nearmenus'); ?>
                        </a>
                    </div>
                    
                    <?php endif; ?>
                    
                </div>
                
                <!-- Sidebar -->
                <div class="lg:col-span-1">
                    <div class="space-y-8">
                        
                        <!-- Search -->
                        <div class="bg-white rounded-lg shadow-sm border p-6">
                            <h3 class="font-semibold mb-4"><?php _e('Search Blog', 'nearmenus'); ?></h3>
                            <form role="search" method="get" action="<?php echo esc_url(home_url('/')); ?>">
                                <div class="flex">
                                    <input type="search" name="s" placeholder="<?php esc_attr_e('Search posts...', 'nearmenus'); ?>" 
                                           value="<?php echo esc_attr(get_search_query()); ?>" 
                                           class="flex-1 px-3 py-2 border rounded-l-lg focus:outline-none focus:ring-2 focus:ring-primary-500">
                                    <button type="submit" class="bg-primary text-white px-4 py-2 rounded-r-lg hover:bg-primary-700 transition-colors">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                                        </svg>
                                    </button>
                                </div>
                            </form>
                        </div>
                        
                        <!-- Recent Posts -->
                        <div class="bg-white rounded-lg shadow-sm border p-6">
                            <h3 class="font-semibold mb-4"><?php _e('Recent Posts', 'nearmenus'); ?></h3>
                            <?php
                            $recent_posts = new WP_Query(array(
                                'posts_per_page' => 5,
                                'post_status' => 'publish',
                                'meta_query' => array(
                                    'relation' => 'OR',
                                    array(
                                        'key' => '_restaurant_rating',
                                        'compare' => 'NOT EXISTS'
                                    ),
                                    array(
                                        'key' => '_restaurant_rating',
                                        'value' => '',
                                        'compare' => '='
                                    )
                                )
                            ));
                            
                            if ($recent_posts->have_posts()) :
                            ?>
                            <div class="space-y-4">
                                <?php while ($recent_posts->have_posts()) : $recent_posts->the_post(); ?>
                                <article class="flex space-x-3">
                                    <?php if (has_post_thumbnail()) : ?>
                                    <div class="flex-shrink-0">
                                        <a href="<?php the_permalink(); ?>">
                                            <?php the_post_thumbnail('thumbnail', array('class' => 'w-16 h-16 object-cover rounded')); ?>
                                        </a>
                                    </div>
                                    <?php endif; ?>
                                    <div class="flex-1 min-w-0">
                                        <h4 class="text-sm font-medium leading-tight mb-1">
                                            <a href="<?php the_permalink(); ?>" class="hover:text-primary transition-colors">
                                                <?php the_title(); ?>
                                            </a>
                                        </h4>
                                        <p class="text-xs text-gray-500">
                                            <?php echo get_the_date(); ?>
                                        </p>
                                    </div>
                                </article>
                                <?php endwhile; ?>
                            </div>
                            <?php
                            wp_reset_postdata();
                            else :
                            ?>
                            <p class="text-gray-600 text-sm"><?php _e('No recent posts available.', 'nearmenus'); ?></p>
                            <?php endif; ?>
                        </div>
                        
                        <!-- Categories -->
                        <?php
                        $categories = get_categories(array('hide_empty' => true));
                        if ($categories) :
                        ?>
                        <div class="bg-white rounded-lg shadow-sm border p-6">
                            <h3 class="font-semibold mb-4"><?php _e('Categories', 'nearmenus'); ?></h3>
                            <div class="space-y-2">
                                <?php foreach ($categories as $category) : ?>
                                <a href="<?php echo esc_url(get_category_link($category->term_id)); ?>" 
                                   class="flex items-center justify-between text-sm text-gray-600 hover:text-primary transition-colors">
                                    <span><?php echo esc_html($category->name); ?></span>
                                    <span class="text-xs bg-gray-100 px-2 py-1 rounded">
                                        <?php echo esc_html($category->count); ?>
                                    </span>
                                </a>
                                <?php endforeach; ?>
                            </div>
                        </div>
                        <?php endif; ?>
                        
                        <!-- Popular Restaurants -->
                        <div class="bg-white rounded-lg shadow-sm border p-6">
                            <h3 class="font-semibold mb-4"><?php _e('Popular Restaurants', 'nearmenus'); ?></h3>
                            <?php
                            $popular_restaurants = new WP_Query(array(
                                'posts_per_page' => 5,
                                'meta_query' => array(
                                    array(
                                        'key' => '_restaurant_rating',
                                        'value' => '',
                                        'compare' => '!='
                                    )
                                ),
                                'meta_key' => '_restaurant_rating',
                                'orderby' => 'meta_value_num',
                                'order' => 'DESC'
                            ));
                            
                            if ($popular_restaurants->have_posts()) :
                            ?>
                            <div class="space-y-3">
                                <?php while ($popular_restaurants->have_posts()) : $popular_restaurants->the_post(); ?>
                                <div class="flex items-center space-x-3">
                                    <?php if (has_post_thumbnail()) : ?>
                                    <a href="<?php the_permalink(); ?>" class="flex-shrink-0">
                                        <?php the_post_thumbnail('thumbnail', array('class' => 'w-12 h-12 object-cover rounded')); ?>
                                    </a>
                                    <?php endif; ?>
                                    <div class="flex-1 min-w-0">
                                        <h4 class="text-sm font-medium truncate">
                                            <a href="<?php the_permalink(); ?>" class="hover:text-primary transition-colors">
                                                <?php the_title(); ?>
                                            </a>
                                        </h4>
                                        <?php
                                        $rating = get_post_meta(get_the_ID(), '_restaurant_rating', true);
                                        if ($rating) :
                                        ?>
                                        <div class="flex items-center">
                                            <?php echo nearmenus_display_rating($rating); ?>
                                            <span class="text-xs text-gray-500 ml-1"><?php echo esc_html($rating); ?></span>
                                        </div>
                                        <?php endif; ?>
                                    </div>
                                </div>
                                <?php endwhile; ?>
                            </div>
                            <?php
                            wp_reset_postdata();
                            else :
                            ?>
                            <p class="text-gray-600 text-sm"><?php _e('No restaurants available.', 'nearmenus'); ?></p>
                            <?php endif; ?>
                        </div>
                        
                    </div>
                </div>
                
            </div>
            
        </div>
    </section>
    
</div>

<?php get_footer(); ?>