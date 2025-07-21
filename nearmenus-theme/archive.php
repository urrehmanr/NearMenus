<?php get_header(); ?>

<main id="main-content" class="main-content archive-page">
    <!-- Archive Header -->
    <section class="archive-header">
        <div class="container">
            <div class="archive-header-content">
                <h1 class="archive-title">
                    <?php
                    if (is_category()) {
                        single_cat_title();
                    } elseif (is_tag()) {
                        single_tag_title();
                    } elseif (is_author()) {
                        printf(esc_html__('Posts by %s', 'nearmenus'), get_the_author());
                    } elseif (is_date()) {
                        if (is_year()) {
                            printf(esc_html__('Posts from %s', 'nearmenus'), get_the_date('Y'));
                        } elseif (is_month()) {
                            printf(esc_html__('Posts from %s', 'nearmenus'), get_the_date('F Y'));
                        } else {
                            printf(esc_html__('Posts from %s', 'nearmenus'), get_the_date());
                        }
                    } else {
                        esc_html_e('All Restaurants', 'nearmenus');
                    }
                    ?>
                </h1>
                
                <?php if (is_category() || is_tag()) : ?>
                    <div class="archive-description">
                        <?php echo term_description(); ?>
                    </div>
                <?php endif; ?>
                
                <div class="archive-meta">
                    <span class="archive-count">
                        <?php
                        global $wp_query;
                        printf(
                            _n('%d restaurant found', '%d restaurants found', $wp_query->found_posts, 'nearmenus'),
                            $wp_query->found_posts
                        );
                        ?>
                    </span>
                </div>
            </div>
        </div>
    </section>
    
    <!-- Restaurant Listing -->
    <section class="restaurants-section">
        <div class="container">
            <!-- Controls -->
            <div class="section-header">
                <div class="section-title-wrapper">
                    <h2 class="section-title"><?php esc_html_e('Restaurant Listings', 'nearmenus'); ?></h2>
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
                            <?php esc_html_e('No restaurants match your current filters. Try adjusting your criteria.', 'nearmenus'); ?>
                        </p>
                        <a href="<?php echo esc_url(home_url('/')); ?>" class="btn btn-primary">
                            <?php esc_html_e('View All Restaurants', 'nearmenus'); ?>
                        </a>
                    </div>
                <?php endif; ?>
            </div>
            
            <!-- Pagination -->
            <?php if ($wp_query->max_num_pages > 1) : ?>
                <div class="pagination-wrapper">
                    <?php if (get_theme_mod('nearmenus_pagination_type', 'load_more') === 'load_more') : ?>
                        <button class="load-more-btn" data-load-more 
                                data-page="<?php echo esc_attr(get_query_var('paged') ? get_query_var('paged') + 1 : 2); ?>" 
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
</main>

<!-- Filter Sidebar/Modal -->
<div class="filter-overlay" data-filter-overlay>
    <div class="filter-sidebar" data-filter-sidebar>
        <?php get_template_part('template-parts/filter-sidebar'); ?>
    </div>
</div>

<?php get_footer(); ?>