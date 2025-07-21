<?php get_header(); ?>

<main id="main-content" class="main-content error-404">
    <section class="error-section">
        <div class="container">
            <div class="error-content">
                <!-- Error Icon -->
                <div class="error-icon">
                    <svg width="120" height="120" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1">
                        <circle cx="12" cy="12" r="10"></circle>
                        <line x1="12" y1="8" x2="12" y2="12"></line>
                        <line x1="12" y1="16" x2="12.01" y2="16"></line>
                    </svg>
                </div>
                
                <!-- Error Message -->
                <div class="error-message">
                    <h1 class="error-title"><?php esc_html_e('Page Not Found', 'nearmenus'); ?></h1>
                    <h2 class="error-code">404</h2>
                    <p class="error-description">
                        <?php esc_html_e('Sorry, the page you are looking for could not be found. It might have been moved, deleted, or you entered the wrong URL.', 'nearmenus'); ?>
                    </p>
                </div>
                
                <!-- Search Form -->
                <div class="error-search">
                    <h3><?php esc_html_e('Search for Restaurants', 'nearmenus'); ?></h3>
                    <form class="search-form" role="search" method="get" action="<?php echo esc_url(home_url('/')); ?>">
                        <div class="search-input-wrapper">
                            <svg class="search-icon" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <circle cx="11" cy="11" r="8"></circle>
                                <path d="21 21l-4.35-4.35"></path>
                            </svg>
                            <input type="search" 
                                   class="search-field" 
                                   placeholder="<?php esc_attr_e('Search restaurants, cuisines, or dishes...', 'nearmenus'); ?>" 
                                   name="s" />
                            <button type="submit" class="search-submit">
                                <?php esc_html_e('Search', 'nearmenus'); ?>
                            </button>
                        </div>
                    </form>
                </div>
                
                <!-- Quick Links -->
                <div class="error-links">
                    <h3><?php esc_html_e('Quick Links', 'nearmenus'); ?></h3>
                    <div class="link-buttons">
                        <a href="<?php echo esc_url(home_url('/')); ?>" class="btn btn-primary">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"></path>
                                <polyline points="9,22 9,12 15,12 15,22"></polyline>
                            </svg>
                            <?php esc_html_e('Back to Home', 'nearmenus'); ?>
                        </a>
                        
                        <a href="<?php echo esc_url(home_url('/#restaurants')); ?>" class="btn btn-secondary">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M19 14c1.49-1.46 3-3.21 3-5.5A5.5 5.5 0 0 0 16.5 3c-1.76 0-3 .5-4.5 2-1.5-1.5-2.74-2-4.5-2A5.5 5.5 0 0 0 2 8.5c0 2.29 1.51 4.04 3 5.5l7 7Z"></path>
                            </svg>
                            <?php esc_html_e('Browse Restaurants', 'nearmenus'); ?>
                        </a>
                        
                        <a href="<?php echo esc_url(home_url('/contact')); ?>" class="btn btn-outline">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"></path>
                                <polyline points="22,6 12,13 2,6"></polyline>
                            </svg>
                            <?php esc_html_e('Contact Support', 'nearmenus'); ?>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </section>
    
    <!-- Popular Categories -->
    <section class="error-categories">
        <div class="container">
            <h3><?php esc_html_e('Popular Categories', 'nearmenus'); ?></h3>
            <div class="categories-grid">
                <?php
                $popular_cuisines = get_terms(array(
                    'taxonomy' => 'cuisine',
                    'hide_empty' => true,
                    'orderby' => 'count',
                    'order' => 'DESC',
                    'number' => 6,
                ));
                
                if (!is_wp_error($popular_cuisines) && !empty($popular_cuisines)) :
                    foreach ($popular_cuisines as $cuisine) :
                        $cuisine_icon = get_term_meta($cuisine->term_id, '_cuisine_icon', true);
                        $cuisine_color = get_term_meta($cuisine->term_id, '_cuisine_color', true);
                ?>
                    <a href="<?php echo esc_url(get_term_link($cuisine)); ?>" class="category-card">
                        <div class="category-icon" style="<?php if ($cuisine_color) echo 'color: ' . esc_attr($cuisine_color); ?>">
                            <?php if ($cuisine_icon) : ?>
                                <img src="<?php echo esc_url($cuisine_icon); ?>" alt="<?php echo esc_attr($cuisine->name); ?>" />
                            <?php else : ?>
                                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path d="M19 14c1.49-1.46 3-3.21 3-5.5A5.5 5.5 0 0 0 16.5 3c-1.76 0-3 .5-4.5 2-1.5-1.5-2.74-2-4.5-2A5.5 5.5 0 0 0 2 8.5c0 2.29 1.51 4.04 3 5.5l7 7Z"></path>
                                </svg>
                            <?php endif; ?>
                        </div>
                        <h4 class="category-name"><?php echo esc_html($cuisine->name); ?></h4>
                        <p class="category-count">
                            <?php printf(_n('%d restaurant', '%d restaurants', $cuisine->count, 'nearmenus'), $cuisine->count); ?>
                        </p>
                    </a>
                <?php 
                    endforeach;
                else :
                ?>
                    <p><?php esc_html_e('No categories available at the moment.', 'nearmenus'); ?></p>
                <?php endif; ?>
            </div>
        </div>
    </section>
    
    <!-- Recent Restaurants -->
    <section class="error-recent">
        <div class="container">
            <h3><?php esc_html_e('Recent Restaurants', 'nearmenus'); ?></h3>
            <div class="recent-restaurants">
                <?php
                $recent_restaurants = new WP_Query(array(
                    'post_type' => 'post',
                    'posts_per_page' => 3,
                    'post_status' => 'publish',
                    'meta_query' => array(
                        array(
                            'key' => '_restaurant_rating',
                            'value' => '',
                            'compare' => '!='
                        )
                    )
                ));
                
                if ($recent_restaurants->have_posts()) :
                    while ($recent_restaurants->have_posts()) : $recent_restaurants->the_post();
                ?>
                    <div class="recent-restaurant-card">
                        <?php if (has_post_thumbnail()) : ?>
                            <div class="restaurant-image">
                                <a href="<?php the_permalink(); ?>">
                                    <?php the_post_thumbnail('restaurant-thumb'); ?>
                                </a>
                            </div>
                        <?php endif; ?>
                        <div class="restaurant-info">
                            <h4 class="restaurant-name">
                                <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
                            </h4>
                            <?php
                            $rating = get_post_meta(get_the_ID(), '_restaurant_rating', true);
                            if ($rating) :
                            ?>
                                <div class="restaurant-rating">
                                    <span class="rating-value"><?php echo esc_html($rating); ?></span>
                                    <div class="stars">
                                        <?php nearmenus_display_rating_stars($rating); ?>
                                    </div>
                                </div>
                            <?php endif; ?>
                            <p class="restaurant-excerpt"><?php echo wp_trim_words(get_the_excerpt(), 15); ?></p>
                        </div>
                    </div>
                <?php 
                    endwhile;
                    wp_reset_postdata();
                else :
                ?>
                    <p><?php esc_html_e('No restaurants available yet.', 'nearmenus'); ?></p>
                <?php endif; ?>
            </div>
        </div>
    </section>
</main>

<?php get_footer(); ?>