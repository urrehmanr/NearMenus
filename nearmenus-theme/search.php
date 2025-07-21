<?php get_header(); ?>

<main id="main-content" class="main-content search-page">
    <!-- Search Header -->
    <section class="search-header">
        <div class="container">
            <div class="search-header-content">
                <h1 class="search-title">
                    <?php
                    if (get_search_query()) {
                        printf(esc_html__('Search Results for "%s"', 'nearmenus'), get_search_query());
                    } else {
                        esc_html_e('Search Restaurants', 'nearmenus');
                    }
                    ?>
                </h1>
                
                <div class="search-meta">
                    <?php
                    global $wp_query;
                    if (get_search_query()) {
                        printf(
                            _n('Found %d restaurant', 'Found %d restaurants', $wp_query->found_posts, 'nearmenus'),
                            $wp_query->found_posts
                        );
                    }
                    ?>
                </div>
                
                <!-- Enhanced Search Form -->
                <div class="search-form-wrapper">
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
    
    <!-- Search Results -->
    <section class="search-results">
        <div class="container">
            <!-- Controls -->
            <div class="section-header">
                <div class="section-title-wrapper">
                    <h2 class="section-title">
                        <?php 
                        if (have_posts()) {
                            esc_html_e('Results', 'nearmenus');
                        } else {
                            esc_html_e('No Results Found', 'nearmenus');
                        }
                        ?>
                    </h2>
                </div>
                
                <?php if (have_posts()) : ?>
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
                        <option value="relevance" <?php selected(get_query_var('orderby'), 'relevance'); ?>>
                            <?php esc_html_e('Sort by Relevance', 'nearmenus'); ?>
                        </option>
                        <option value="rating" <?php selected(get_query_var('orderby'), 'rating'); ?>>
                            <?php esc_html_e('Sort by Rating', 'nearmenus'); ?>
                        </option>
                        <option value="reviews" <?php selected(get_query_var('orderby'), 'reviews'); ?>>
                            <?php esc_html_e('Sort by Reviews', 'nearmenus'); ?>
                        </option>
                        <option value="name" <?php selected(get_query_var('orderby'), 'name'); ?>>
                            <?php esc_html_e('Sort by Name', 'nearmenus'); ?>
                        </option>
                        <option value="newest" <?php selected(get_query_var('orderby'), 'date'); ?>>
                            <?php esc_html_e('Sort by Newest', 'nearmenus'); ?>
                        </option>
                    </select>
                </div>
                <?php endif; ?>
            </div>
            
            <!-- Active Filters Display -->
            <div class="active-filters" data-active-filters style="display: none;"></div>
            
            <!-- Search Results Grid -->
            <div class="restaurants-grid" data-restaurants-grid>
                <?php if (have_posts()) : ?>
                    <?php while (have_posts()) : the_post(); ?>
                        <?php get_template_part('template-parts/restaurant-card'); ?>
                    <?php endwhile; ?>
                <?php else : ?>
                    <div class="no-results">
                        <div class="no-results-icon">
                            <svg width="64" height="64" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1">
                                <circle cx="11" cy="11" r="8"></circle>
                                <path d="21 21l-4.35-4.35"></path>
                            </svg>
                        </div>
                        <h3 class="no-results-title"><?php esc_html_e('No restaurants found', 'nearmenus'); ?></h3>
                        <p class="no-results-text">
                            <?php
                            if (get_search_query()) {
                                printf(
                                    esc_html__('Sorry, no restaurants match your search for "%s". Try different keywords or browse our categories.', 'nearmenus'),
                                    get_search_query()
                                );
                            } else {
                                esc_html_e('Please enter a search term to find restaurants.', 'nearmenus');
                            }
                            ?>
                        </p>
                        
                        <!-- Search Suggestions -->
                        <div class="search-suggestions">
                            <h4><?php esc_html_e('Try searching for:', 'nearmenus'); ?></h4>
                            <div class="suggestion-tags">
                                <?php
                                $suggestions = array(
                                    'pizza', 'sushi', 'burger', 'italian', 'chinese', 
                                    'mexican', 'indian', 'seafood', 'steakhouse', 'cafe'
                                );
                                foreach ($suggestions as $suggestion) :
                                ?>
                                    <a href="<?php echo esc_url(home_url('/?s=' . urlencode($suggestion))); ?>" class="suggestion-tag">
                                        <?php echo esc_html($suggestion); ?>
                                    </a>
                                <?php endforeach; ?>
                            </div>
                        </div>
                        
                        <!-- Popular Categories -->
                        <div class="popular-categories">
                            <h4><?php esc_html_e('Browse Popular Categories:', 'nearmenus'); ?></h4>
                            <div class="category-links">
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
                                ?>
                                    <a href="<?php echo esc_url(get_term_link($cuisine)); ?>" class="category-link">
                                        <span class="category-name"><?php echo esc_html($cuisine->name); ?></span>
                                        <span class="category-count"><?php echo esc_html($cuisine->count); ?></span>
                                    </a>
                                <?php 
                                    endforeach;
                                endif;
                                ?>
                            </div>
                        </div>
                        
                        <div class="no-results-actions">
                            <a href="<?php echo esc_url(home_url('/')); ?>" class="btn btn-primary">
                                <?php esc_html_e('View All Restaurants', 'nearmenus'); ?>
                            </a>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
            
            <!-- Pagination -->
            <?php if (have_posts() && $wp_query->max_num_pages > 1) : ?>
                <div class="pagination-wrapper">
                    <?php if (get_theme_mod('nearmenus_pagination_type', 'load_more') === 'load_more') : ?>
                        <button class="load-more-btn" data-load-more 
                                data-page="<?php echo esc_attr(get_query_var('paged') ? get_query_var('paged') + 1 : 2); ?>" 
                                data-max-pages="<?php echo esc_attr($wp_query->max_num_pages); ?>"
                                data-search-query="<?php echo esc_attr(get_search_query()); ?>">
                            <?php esc_html_e('Load More Results', 'nearmenus'); ?>
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
                                'add_args' => array('s' => get_search_query()),
                                'add_fragment' => '',
                            ));
                            ?>
                        </div>
                    <?php endif; ?>
                </div>
            <?php endif; ?>
        </div>
    </section>
    
    <!-- Search Tips (when no results) -->
    <?php if (!have_posts() && get_search_query()) : ?>
    <section class="search-tips">
        <div class="container">
            <div class="search-tips-content">
                <h3><?php esc_html_e('Search Tips', 'nearmenus'); ?></h3>
                <ul class="tips-list">
                    <li><?php esc_html_e('Check your spelling and try again', 'nearmenus'); ?></li>
                    <li><?php esc_html_e('Try using fewer or different keywords', 'nearmenus'); ?></li>
                    <li><?php esc_html_e('Search for cuisine types (e.g., "Italian", "Chinese")', 'nearmenus'); ?></li>
                    <li><?php esc_html_e('Search for dish names (e.g., "pizza", "sushi")', 'nearmenus'); ?></li>
                    <li><?php esc_html_e('Try searching by location or neighborhood', 'nearmenus'); ?></li>
                </ul>
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