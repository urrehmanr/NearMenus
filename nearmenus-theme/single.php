<?php get_header(); ?>

<main id="main-content" class="main-content single-restaurant">
    <?php while (have_posts()) : the_post(); ?>
        
        <!-- Restaurant Header -->
        <section class="restaurant-header">
            <div class="restaurant-hero">
                <?php if (has_post_thumbnail()) : ?>
                    <div class="restaurant-hero-image">
                        <?php the_post_thumbnail('restaurant-hero', array('alt' => get_the_title())); ?>
                        <div class="hero-overlay"></div>
                    </div>
                <?php endif; ?>
                
                <div class="container">
                    <div class="restaurant-hero-content">
                        <!-- Back Navigation -->
                        <div class="back-navigation">
                            <a href="<?php echo esc_url(wp_get_referer() ? wp_get_referer() : home_url('/')); ?>" class="back-link">
                                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path d="M19 12H5"></path>
                                    <path d="M12 19l-7-7 7-7"></path>
                                </svg>
                                <?php esc_html_e('Back to Restaurants', 'nearmenus'); ?>
                            </a>
                        </div>
                        
                        <!-- Restaurant Basic Info -->
                        <div class="restaurant-basic-info">
                            <h1 class="restaurant-title"><?php the_title(); ?></h1>
                            
                            <div class="restaurant-meta">
                                <?php
                                $rating = get_post_meta(get_the_ID(), '_restaurant_rating', true);
                                $reviews_count = get_post_meta(get_the_ID(), '_restaurant_reviews_count', true);
                                $location = get_post_meta(get_the_ID(), '_restaurant_location', true);
                                $is_open = get_post_meta(get_the_ID(), '_restaurant_is_open', true);
                                ?>
                                
                                <?php if ($rating) : ?>
                                    <div class="restaurant-rating">
                                        <div class="stars">
                                            <?php nearmenus_display_rating_stars($rating); ?>
                                        </div>
                                        <span class="rating-value"><?php echo esc_html($rating); ?></span>
                                        <?php if ($reviews_count) : ?>
                                            <span class="reviews-count">(<?php echo esc_html($reviews_count); ?>)</span>
                                        <?php endif; ?>
                                    </div>
                                <?php endif; ?>
                                
                                <?php if ($location) : ?>
                                    <div class="restaurant-location">
                                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                            <path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"></path>
                                            <circle cx="12" cy="10" r="3"></circle>
                                        </svg>
                                        <span><?php echo esc_html($location); ?></span>
                                    </div>
                                <?php endif; ?>
                                
                                <!-- Status Badge -->
                                <div class="restaurant-status">
                                    <?php if ($is_open) : ?>
                                        <span class="status-badge status-open"><?php esc_html_e('Open Now', 'nearmenus'); ?></span>
                                    <?php else : ?>
                                        <span class="status-badge status-closed"><?php esc_html_e('Closed', 'nearmenus'); ?></span>
                                    <?php endif; ?>
                                </div>
                            </div>
                            
                            <!-- Quick Actions -->
                            <div class="restaurant-actions">
                                <?php
                                $phone = get_post_meta(get_the_ID(), '_restaurant_phone', true);
                                $website = get_post_meta(get_the_ID(), '_restaurant_website', true);
                                ?>
                                
                                <?php if ($phone) : ?>
                                    <a href="tel:<?php echo esc_attr(str_replace(array(' ', '-', '(', ')'), '', $phone)); ?>" class="action-btn btn-call">
                                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                            <path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"></path>
                                        </svg>
                                        <?php esc_html_e('Call', 'nearmenus'); ?>
                                    </a>
                                <?php endif; ?>
                                
                                <?php if ($website) : ?>
                                    <a href="<?php echo esc_url($website); ?>" class="action-btn btn-website" target="_blank" rel="noopener">
                                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                            <circle cx="12" cy="12" r="10"></circle>
                                            <line x1="2" y1="12" x2="22" y2="12"></line>
                                            <path d="M12 2a15.3 15.3 0 0 1 4 10 15.3 15.3 0 0 1-4 10 15.3 15.3 0 0 1-4-10 15.3 15.3 0 0 1 4-10z"></path>
                                        </svg>
                                        <?php esc_html_e('Website', 'nearmenus'); ?>
                                    </a>
                                <?php endif; ?>
                                
                                <button class="action-btn btn-share" data-share-restaurant>
                                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <circle cx="18" cy="5" r="3"></circle>
                                        <circle cx="6" cy="12" r="3"></circle>
                                        <circle cx="18" cy="19" r="3"></circle>
                                        <line x1="8.59" y1="13.51" x2="15.42" y2="17.49"></line>
                                        <line x1="15.41" y1="6.51" x2="8.59" y2="10.49"></line>
                                    </svg>
                                    <?php esc_html_e('Share', 'nearmenus'); ?>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        
        <!-- Restaurant Content -->
        <section class="restaurant-content">
            <div class="container">
                <div class="restaurant-layout">
                    <!-- Main Content -->
                    <div class="restaurant-main">
                        <!-- Tabs Navigation -->
                        <div class="restaurant-tabs">
                            <nav class="tabs-nav" role="tablist">
                                <button class="tab-button active" data-tab="overview" role="tab" aria-selected="true">
                                    <?php esc_html_e('Overview', 'nearmenus'); ?>
                                </button>
                                <button class="tab-button" data-tab="menu" role="tab" aria-selected="false">
                                    <?php esc_html_e('Menu', 'nearmenus'); ?>
                                </button>
                                <button class="tab-button" data-tab="gallery" role="tab" aria-selected="false">
                                    <?php esc_html_e('Photos', 'nearmenus'); ?>
                                </button>
                                <button class="tab-button" data-tab="reviews" role="tab" aria-selected="false">
                                    <?php esc_html_e('Reviews', 'nearmenus'); ?>
                                </button>
                            </nav>
                        </div>
                        
                        <!-- Tab Content -->
                        <div class="tabs-content">
                            <!-- Overview Tab -->
                            <div class="tab-panel active" data-panel="overview" role="tabpanel">
                                <?php get_template_part('template-parts/restaurant-info'); ?>
                            </div>
                            
                            <!-- Menu Tab -->
                            <div class="tab-panel" data-panel="menu" role="tabpanel">
                                <?php get_template_part('template-parts/restaurant-menu'); ?>
                            </div>
                            
                            <!-- Gallery Tab -->
                            <div class="tab-panel" data-panel="gallery" role="tabpanel">
                                <?php get_template_part('template-parts/restaurant-gallery'); ?>
                            </div>
                            
                            <!-- Reviews Tab -->
                            <div class="tab-panel" data-panel="reviews" role="tabpanel">
                                <?php get_template_part('template-parts/restaurant-reviews'); ?>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Sidebar -->
                    <aside class="restaurant-sidebar">
                        <?php get_template_part('template-parts/contact-widget'); ?>
                        <?php get_template_part('template-parts/restaurant-features'); ?>
                        
                        <!-- Special Offers -->
                        <?php
                        $special_offer = get_post_meta(get_the_ID(), '_restaurant_special_offer', true);
                        if ($special_offer) :
                        ?>
                            <div class="widget special-offer-widget">
                                <h3 class="widget-title"><?php esc_html_e('Special Offer', 'nearmenus'); ?></h3>
                                <div class="special-offer-content">
                                    <p><?php echo esc_html($special_offer); ?></p>
                                </div>
                            </div>
                        <?php endif; ?>
                        
                        <?php if (is_active_sidebar('restaurant-sidebar')) : ?>
                            <?php dynamic_sidebar('restaurant-sidebar'); ?>
                        <?php endif; ?>
                    </aside>
                </div>
            </div>
        </section>
        
        <!-- Related Restaurants -->
        <section class="related-restaurants">
            <div class="container">
                <?php get_template_part('template-parts/related-restaurants'); ?>
            </div>
        </section>
        
    <?php endwhile; ?>
</main>

<?php get_footer(); ?>

<?php
/**
 * Display rating stars
 */
function nearmenus_display_rating_stars($rating) {
    $rating = floatval($rating);
    $full_stars = floor($rating);
    $half_star = ($rating - $full_stars) >= 0.5;
    $empty_stars = 5 - $full_stars - ($half_star ? 1 : 0);
    
    // Full stars
    for ($i = 0; $i < $full_stars; $i++) {
        echo '<svg class="star filled" width="16" height="16" viewBox="0 0 24 24" fill="currentColor"><polygon points="12,2 15.09,8.26 22,9.27 17,14.14 18.18,21.02 12,17.77 5.82,21.02 7,14.14 2,9.27 8.91,8.26"></polygon></svg>';
    }
    
    // Half star
    if ($half_star) {
        echo '<svg class="star half" width="16" height="16" viewBox="0 0 24 24" fill="currentColor"><defs><linearGradient id="half"><stop offset="50%" stop-color="currentColor"/><stop offset="50%" stop-color="transparent"/></linearGradient></defs><polygon points="12,2 15.09,8.26 22,9.27 17,14.14 18.18,21.02 12,17.77 5.82,21.02 7,14.14 2,9.27 8.91,8.26" fill="url(#half)"></polygon></svg>';
    }
    
    // Empty stars
    for ($i = 0; $i < $empty_stars; $i++) {
        echo '<svg class="star empty" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polygon points="12,2 15.09,8.26 22,9.27 17,14.14 18.18,21.02 12,17.77 5.82,21.02 7,14.14 2,9.27 8.91,8.26"></polygon></svg>';
    }
}
?>