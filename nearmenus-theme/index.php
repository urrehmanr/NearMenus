<?php get_header(); ?>

<main id="main-content" class="main-content">
    <!-- Hero Section with Search -->
    <section class="hero-section">
        <div class="hero-background">
            <div class="hero-overlay"></div>
        </div>
        <div class="container">
            <div class="hero-content">
                <h1 class="hero-title">
                    <?php echo esc_html(get_theme_mod('nearmenus_hero_title', __('Discover Amazing Restaurants', 'nearmenus'))); ?>
                </h1>
                <p class="hero-description">
                    <?php echo esc_html(get_theme_mod('nearmenus_hero_description', __('Explore the best dining experiences in your city. From fine dining to casual eats, find your perfect meal.', 'nearmenus'))); ?>
                </p>
                
                <!-- Hero Search Form -->
                <div class="hero-search">
                    <form class="search-form enhanced-search" role="search" method="get" action="<?php echo esc_url(home_url('/')); ?>">
                        <div class="search-input-wrapper">
                            <svg class="search-icon" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <circle cx="11" cy="11" r="8"></circle>
                                <path d="21 21l-4.35-4.35"></path>
                            </svg>
                            <input type="search" 
                                   class="search-field" 
                                   placeholder="<?php esc_attr_e('Search restaurants, cuisines, or dishes...', 'nearmenus'); ?>" 
                                   value="<?php echo get_search_query(); ?>" 
                                   name="s" />
                            <button type="submit" class="search-submit">
                                <?php esc_html_e('Search', 'nearmenus'); ?>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>
    
    <!-- Featured Restaurants Section -->
    <section id="restaurants" class="restaurants-section">
        <div class="container">
            <!-- Section Header -->
            <div class="section-header">
                <div class="section-title-wrapper">
                    <h2 class="section-title">
                        <?php 
                        if (is_search()) {
                            printf(esc_html__('Search Results (%d)', 'nearmenus'), $wp_query->found_posts);
                        } else {
                            esc_html_e('Featured Restaurants', 'nearmenus');
                        }
                        ?>
                    </h2>
                    <p class="section-subtitle">
                        <?php esc_html_e('Discover top-rated dining experiences near you', 'nearmenus'); ?>
                    </p>
                </div>
                
                <!-- Filter and Sort Controls -->
                <div class="controls-wrapper">
                    <button class="filter-toggle" data-filter-toggle>
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <polygon points="22,3 2,3 10,12.46 10,19 14,21 14,12.46"></polygon>
                        </svg>
                        <?php esc_html_e('Filters', 'nearmenus'); ?>
                        <span class="filter-count" style="display: none;"></span>
                    </button>
                    
                    <select class="sort-select" name="sort" data-sort-select>
                        <option value="rating"><?php esc_html_e('Sort by Rating', 'nearmenus'); ?></option>
                        <option value="reviews"><?php esc_html_e('Sort by Reviews', 'nearmenus'); ?></option>
                        <option value="name"><?php esc_html_e('Sort by Name', 'nearmenus'); ?></option>
                        <option value="newest"><?php esc_html_e('Sort by Newest', 'nearmenus'); ?></option>
                    </select>
                </div>
            </div>
            
            <!-- Active Filters Display -->
            <div class="active-filters" data-active-filters style="display: none;"></div>
            
            <!-- Restaurant Grid -->
            <div class="restaurants-grid" data-restaurants-grid>
                <?php if (have_posts()) : ?>
                    <?php while (have_posts()) : the_post(); ?>
                        <?php get_template_part('template-parts/restaurant-card'); ?>
                    <?php endwhile; ?>
                <?php else : ?>
                    <div class="no-results">
                        <div class="no-results-icon">
                            <svg width="64" height="64" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1">
                                <path d="M19 14c1.49-1.46 3-3.21 3-5.5A5.5 5.5 0 0 0 16.5 3c-1.76 0-3 .5-4.5 2-1.5-1.5-2.74-2-4.5-2A5.5 5.5 0 0 0 2 8.5c0 2.29 1.51 4.04 3 5.5l7 7Z"></path>
                                <path d="M12 5L8 21l4-7 4 7-4-16"></path>
                            </svg>
                        </div>
                        <h3 class="no-results-title"><?php esc_html_e('No restaurants found', 'nearmenus'); ?></h3>
                        <p class="no-results-text">
                            <?php 
                            if (is_search()) {
                                esc_html_e('Try adjusting your search or filters to find more restaurants.', 'nearmenus');
                            } else {
                                esc_html_e('No restaurants have been added yet. Check back soon!', 'nearmenus');
                            }
                            ?>
                        </p>
                        <?php if (is_search()) : ?>
                            <a href="<?php echo esc_url(home_url('/')); ?>" class="btn btn-primary">
                                <?php esc_html_e('View All Restaurants', 'nearmenus'); ?>
                            </a>
                        <?php endif; ?>
                    </div>
                <?php endif; ?>
            </div>
            
            <!-- Load More / Pagination -->
            <?php if (have_posts() && $wp_query->max_num_pages > 1) : ?>
                <div class="pagination-wrapper">
                    <?php if (get_theme_mod('nearmenus_pagination_type', 'load_more') === 'load_more') : ?>
                        <button class="load-more-btn" data-load-more 
                                data-page="2" 
                                data-max-pages="<?php echo esc_attr($wp_query->max_num_pages); ?>">
                            <?php esc_html_e('Load More Restaurants', 'nearmenus'); ?>
                        </button>
                    <?php else : ?>
                        <div class="pagination">
                            <?php
                            echo paginate_links(array(
                                'total' => $wp_query->max_num_pages,
                                'current' => max(1, get_query_var('paged')),
                                'format' => '?paged=%#%',
                                'show_all' => false,
                                'type' => 'list',
                                'end_size' => 3,
                                'mid_size' => 1,
                                'prev_next' => true,
                                'prev_text' => __('« Previous', 'nearmenus'),
                                'next_text' => __('Next »', 'nearmenus'),
                                'add_args' => false,
                                'add_fragment' => '#restaurants',
                            ));
                            ?>
                        </div>
                    <?php endif; ?>
                </div>
            <?php endif; ?>
        </div>
    </section>
    
    <!-- Cuisine Categories Section -->
    <?php if (!is_search() && !is_paged()) : ?>
    <section class="cuisine-categories-section">
        <div class="container">
            <div class="section-header">
                <h2 class="section-title"><?php esc_html_e('Browse by Cuisine', 'nearmenus'); ?></h2>
                <p class="section-subtitle"><?php esc_html_e('Explore restaurants by your favorite cuisine types', 'nearmenus'); ?></p>
            </div>
            
            <div class="cuisine-grid">
                <?php
                $cuisines = get_terms(array(
                    'taxonomy' => 'cuisine',
                    'hide_empty' => true,
                    'orderby' => 'count',
                    'order' => 'DESC',
                    'number' => 8,
                ));
                
                if (!is_wp_error($cuisines) && !empty($cuisines)) :
                    foreach ($cuisines as $cuisine) :
                        $cuisine_icon = get_term_meta($cuisine->term_id, '_cuisine_icon', true);
                        $cuisine_color = get_term_meta($cuisine->term_id, '_cuisine_color', true);
                ?>
                    <a href="<?php echo esc_url(get_term_link($cuisine)); ?>" class="cuisine-card" 
                       style="<?php if ($cuisine_color) echo 'border-color: ' . esc_attr($cuisine_color); ?>">
                        <div class="cuisine-icon" style="<?php if ($cuisine_color) echo 'color: ' . esc_attr($cuisine_color); ?>">
                            <?php if ($cuisine_icon) : ?>
                                <img src="<?php echo esc_url($cuisine_icon); ?>" alt="<?php echo esc_attr($cuisine->name); ?>" />
                            <?php else : ?>
                                <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path d="M19 14c1.49-1.46 3-3.21 3-5.5A5.5 5.5 0 0 0 16.5 3c-1.76 0-3 .5-4.5 2-1.5-1.5-2.74-2-4.5-2A5.5 5.5 0 0 0 2 8.5c0 2.29 1.51 4.04 3 5.5l7 7Z"></path>
                                </svg>
                            <?php endif; ?>
                        </div>
                        <h3 class="cuisine-name"><?php echo esc_html($cuisine->name); ?></h3>
                        <p class="cuisine-count">
                            <?php printf(_n('%d restaurant', '%d restaurants', $cuisine->count, 'nearmenus'), $cuisine->count); ?>
                        </p>
                    </a>
                <?php 
                    endforeach;
                else :
                ?>
                    <p class="no-cuisines"><?php esc_html_e('No cuisine categories have been added yet.', 'nearmenus'); ?></p>
                <?php endif; ?>
            </div>
        </div>
    </section>
    <?php endif; ?>
    
    <!-- Call to Action Section -->
    <?php if (!is_search() && !is_paged()) : ?>
    <section class="cta-section">
        <div class="container">
            <div class="cta-content">
                <h2 class="cta-title"><?php esc_html_e('List Your Restaurant', 'nearmenus'); ?></h2>
                <p class="cta-description">
                    <?php esc_html_e('Join thousands of restaurants already on NearMenus. Reach new customers and grow your business.', 'nearmenus'); ?>
                </p>
                <a href="<?php echo esc_url(home_url('/list-restaurant')); ?>" class="btn btn-primary btn-large">
                    <?php esc_html_e('Get Started Today', 'nearmenus'); ?>
                </a>
            </div>
        </div>
    </section>
    <?php endif; ?>
</main>

<!-- Filter Sidebar/Modal -->
<div class="filter-overlay" data-filter-overlay>
    <div class="filter-sidebar" data-filter-sidebar>
        <?php get_template_part('template-parts/filter-sidebar'); ?>
    </div>
</div>

<?php get_footer(); ?>