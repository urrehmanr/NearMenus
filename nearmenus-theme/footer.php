<footer id="site-footer" class="site-footer">
    <div class="container">
        <div class="footer-content">
            <!-- Footer Widgets -->
            <div class="footer-widgets">
                <div class="footer-widget-area">
                    <div class="footer-branding">
                        <h3 class="footer-title">
                            <?php bloginfo('name'); ?>
                        </h3>
                        <p class="footer-description">
                            <?php 
                            $description = get_bloginfo('description');
                            if ($description) {
                                echo esc_html($description);
                            } else {
                                esc_html_e('Your trusted partner for discovering the best restaurants and dining experiences.', 'nearmenus');
                            }
                            ?>
                        </p>
                    </div>
                </div>
                
                <div class="footer-widget-area">
                    <h4 class="footer-widget-title"><?php esc_html_e('Quick Links', 'nearmenus'); ?></h4>
                    <ul class="footer-links">
                        <li><a href="<?php echo esc_url(home_url('/')); ?>"><?php esc_html_e('Home', 'nearmenus'); ?></a></li>
                        <li><a href="<?php echo esc_url(home_url('/about')); ?>"><?php esc_html_e('About Us', 'nearmenus'); ?></a></li>
                        <li><a href="<?php echo esc_url(home_url('/contact')); ?>"><?php esc_html_e('Contact', 'nearmenus'); ?></a></li>
                        <li><a href="<?php echo esc_url(home_url('/privacy-policy')); ?>"><?php esc_html_e('Privacy Policy', 'nearmenus'); ?></a></li>
                        <li><a href="<?php echo esc_url(home_url('/terms-of-service')); ?>"><?php esc_html_e('Terms of Service', 'nearmenus'); ?></a></li>
                    </ul>
                </div>
                
                <div class="footer-widget-area">
                    <h4 class="footer-widget-title"><?php esc_html_e('For Restaurants', 'nearmenus'); ?></h4>
                    <ul class="footer-links">
                        <li><a href="<?php echo esc_url(home_url('/list-restaurant')); ?>"><?php esc_html_e('List Your Restaurant', 'nearmenus'); ?></a></li>
                        <li><a href="<?php echo esc_url(home_url('/business-dashboard')); ?>"><?php esc_html_e('Business Dashboard', 'nearmenus'); ?></a></li>
                        <li><a href="<?php echo esc_url(home_url('/marketing-tools')); ?>"><?php esc_html_e('Marketing Tools', 'nearmenus'); ?></a></li>
                        <li><a href="<?php echo esc_url(home_url('/support')); ?>"><?php esc_html_e('Support', 'nearmenus'); ?></a></li>
                    </ul>
                </div>
                
                <div class="footer-widget-area">
                    <h4 class="footer-widget-title"><?php esc_html_e('Contact Info', 'nearmenus'); ?></h4>
                    <div class="contact-info">
                        <?php
                        // Get contact information from customizer or use defaults
                        $phone = get_theme_mod('nearmenus_contact_phone', '1-800-FOODIE');
                        $email = get_theme_mod('nearmenus_contact_email', 'support@nearmenus.com');
                        $availability = get_theme_mod('nearmenus_contact_availability', 'Available 24/7');
                        ?>
                        <p class="contact-item">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"></path>
                            </svg>
                            <a href="tel:<?php echo esc_attr(str_replace(array(' ', '-', '(', ')'), '', $phone)); ?>"><?php echo esc_html($phone); ?></a>
                        </p>
                        <p class="contact-item">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"></path>
                                <polyline points="22,6 12,13 2,6"></polyline>
                            </svg>
                            <a href="mailto:<?php echo esc_attr($email); ?>"><?php echo esc_html($email); ?></a>
                        </p>
                        <p class="contact-item">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <circle cx="12" cy="12" r="10"></circle>
                                <polyline points="12,6 12,12 16,14"></polyline>
                            </svg>
                            <?php echo esc_html($availability); ?>
                        </p>
                    </div>
                </div>
            </div>
            
            <!-- Popular Cuisines -->
            <div class="footer-cuisines">
                <h4 class="footer-widget-title"><?php esc_html_e('Popular Cuisines', 'nearmenus'); ?></h4>
                <div class="cuisine-tags">
                    <?php
                    $popular_cuisines = get_terms(array(
                        'taxonomy' => 'cuisine',
                        'hide_empty' => false,
                        'orderby' => 'count',
                        'order' => 'DESC',
                        'number' => 10,
                    ));
                    
                    if (!is_wp_error($popular_cuisines) && !empty($popular_cuisines)) :
                        foreach ($popular_cuisines as $cuisine) :
                    ?>
                        <a href="<?php echo esc_url(get_term_link($cuisine)); ?>" class="cuisine-tag">
                            <?php echo esc_html($cuisine->name); ?>
                        </a>
                    <?php 
                        endforeach;
                    endif;
                    ?>
                </div>
            </div>
            
            <!-- Footer Bottom -->
            <div class="footer-bottom">
                <div class="footer-bottom-left">
                    <p class="copyright">
                        &copy; <?php echo esc_html(date('Y')); ?> 
                        <?php bloginfo('name'); ?>. 
                        <?php esc_html_e('All rights reserved.', 'nearmenus'); ?>
                    </p>
                </div>
                
                <div class="footer-bottom-right">
                    <!-- Social Links -->
                    <?php
                    $social_links = array(
                        'facebook' => get_theme_mod('nearmenus_facebook_url'),
                        'twitter' => get_theme_mod('nearmenus_twitter_url'),
                        'instagram' => get_theme_mod('nearmenus_instagram_url'),
                        'youtube' => get_theme_mod('nearmenus_youtube_url'),
                    );
                    
                    $has_social = array_filter($social_links);
                    if (!empty($has_social)) :
                    ?>
                    <div class="social-links">
                        <?php foreach ($social_links as $platform => $url) : ?>
                            <?php if ($url) : ?>
                                <a href="<?php echo esc_url($url); ?>" 
                                   class="social-link social-<?php echo esc_attr($platform); ?>" 
                                   target="_blank" 
                                   rel="noopener noreferrer"
                                   aria-label="<?php echo esc_attr(sprintf(__('Follow us on %s', 'nearmenus'), ucfirst($platform))); ?>">
                                    <?php nearmenus_social_icon($platform); ?>
                                </a>
                            <?php endif; ?>
                        <?php endforeach; ?>
                    </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</footer>

<?php wp_footer(); ?>
</body>
</html>

<?php
/**
 * Social Media Icons
 */
function nearmenus_social_icon($platform) {
    $icons = array(
        'facebook' => '<svg width="20" height="20" viewBox="0 0 24 24" fill="currentColor"><path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/></svg>',
        'twitter' => '<svg width="20" height="20" viewBox="0 0 24 24" fill="currentColor"><path d="M23.953 4.57a10 10 0 01-2.825.775 4.958 4.958 0 002.163-2.723c-.951.555-2.005.959-3.127 1.184a4.92 4.92 0 00-8.384 4.482C7.69 8.095 4.067 6.13 1.64 3.162a4.822 4.822 0 00-.666 2.475c0 1.71.87 3.213 2.188 4.096a4.904 4.904 0 01-2.228-.616v.06a4.923 4.923 0 003.946 4.827 4.996 4.996 0 01-2.212.085 4.936 4.936 0 004.604 3.417 9.867 9.867 0 01-6.102 2.105c-.39 0-.779-.023-1.17-.067a13.995 13.995 0 007.557 2.209c9.053 0 13.998-7.496 13.998-13.985 0-.21 0-.42-.015-.63A9.935 9.935 0 0024 4.59z"/></svg>',
        'instagram' => '<svg width="20" height="20" viewBox="0 0 24 24" fill="currentColor"><path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z"/></svg>',
        'youtube' => '<svg width="20" height="20" viewBox="0 0 24 24" fill="currentColor"><path d="M23.498 6.186a3.016 3.016 0 0 0-2.122-2.136C19.505 3.545 12 3.545 12 3.545s-7.505 0-9.377.505A3.017 3.017 0 0 0 .502 6.186C0 8.07 0 12 0 12s0 3.93.502 5.814a3.016 3.016 0 0 0 2.122 2.136c1.871.505 9.376.505 9.376.505s7.505 0 9.377-.505a3.015 3.015 0 0 0 2.122-2.136C24 15.93 24 12 24 12s0-3.93-.502-5.814zM9.545 15.568V8.432L15.818 12l-6.273 3.568z"/></svg>'
    );
    
    echo $icons[$platform] ?? '';
}
?>