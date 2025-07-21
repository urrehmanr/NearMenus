    </div><!-- #content -->

    <footer id="colophon" class="site-footer bg-gray-50 py-12 mt-16">
        <div class="container mx-auto px-4">
            
            <!-- Footer Content -->
            <div class="grid md:grid-cols-4 gap-8 mb-8">
                
                <!-- About Section -->
                <div class="footer-section">
                    <div class="flex items-center mb-4">
                        <svg class="w-8 h-8 mr-2 text-orange-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-4m-5 0H3m2 0h4M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 8v-8a1 1 0 011-1h4a1 1 0 011 1v8M7 7h10"></path>
                        </svg>
                        <h3 class="font-bold text-lg">
                            <?php bloginfo('name'); ?>
                        </h3>
                    </div>
                    <p class="text-gray-600 mb-4">
                        <?php 
                        $description = get_bloginfo('description');
                        if ($description) {
                            echo esc_html($description);
                        } else {
                            _e('Your trusted partner for discovering the best restaurants and dining experiences in your area.', 'nearmenus');
                        }
                        ?>
                    </p>
                    
                    <!-- Social Links -->
                    <div class="flex space-x-4">
                        <?php if (get_theme_mod('nearmenus_facebook_url')) : ?>
                        <a href="<?php echo esc_url(get_theme_mod('nearmenus_facebook_url')); ?>" class="text-gray-400 hover:text-primary transition-colors" target="_blank" rel="noopener">
                            <span class="sr-only"><?php _e('Facebook', 'nearmenus'); ?></span>
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/>
                            </svg>
                        </a>
                        <?php endif; ?>
                        
                        <?php if (get_theme_mod('nearmenus_twitter_url')) : ?>
                        <a href="<?php echo esc_url(get_theme_mod('nearmenus_twitter_url')); ?>" class="text-gray-400 hover:text-primary transition-colors" target="_blank" rel="noopener">
                            <span class="sr-only"><?php _e('Twitter', 'nearmenus'); ?></span>
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M23.953 4.57a10 10 0 01-2.825.775 4.958 4.958 0 002.163-2.723c-.951.555-2.005.959-3.127 1.184a4.92 4.92 0 00-8.384 4.482C7.69 8.095 4.067 6.13 1.64 3.162a4.822 4.822 0 00-.666 2.475c0 1.71.87 3.213 2.188 4.096a4.904 4.904 0 01-2.228-.616v.06a4.923 4.923 0 003.946 4.827 4.996 4.996 0 01-2.212.085 4.936 4.936 0 004.604 3.417 9.867 9.867 0 01-6.102 2.105c-.39 0-.779-.023-1.17-.067a13.995 13.995 0 007.557 2.209c9.053 0 13.998-7.496 13.998-13.985 0-.21 0-.42-.015-.63A9.935 9.935 0 0024 4.59z"/>
                            </svg>
                        </a>
                        <?php endif; ?>
                        
                        <?php if (get_theme_mod('nearmenus_instagram_url')) : ?>
                        <a href="<?php echo esc_url(get_theme_mod('nearmenus_instagram_url')); ?>" class="text-gray-400 hover:text-primary transition-colors" target="_blank" rel="noopener">
                            <span class="sr-only"><?php _e('Instagram', 'nearmenus'); ?></span>
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M12.017 0C5.396 0 .029 5.367.029 11.987c0 6.618 5.367 11.986 11.988 11.986C18.636 23.973 24 18.605 24 11.987 24 5.367 18.636.001 12.017.001zm5.568 16.225c-.537.537-1.27.868-2.076.868H8.51c-1.618 0-2.944-1.326-2.944-2.944V8.51c0-1.618 1.326-2.944 2.944-2.944h6.999c1.618 0 2.944 1.326 2.944 2.944v6.639c0 .806-.331 1.539-.868 2.076z"/>
                            </svg>
                        </a>
                        <?php endif; ?>
                    </div>
                </div>
                
                <!-- Quick Links -->
                <div class="footer-section">
                    <h4 class="font-semibold mb-4 text-gray-700"><?php _e('Quick Links', 'nearmenus'); ?></h4>
                    <?php
                    wp_nav_menu(array(
                        'theme_location' => 'footer',
                        'menu_class' => 'footer-menu space-y-2',
                        'container' => false,
                        'fallback_cb' => 'nearmenus_footer_fallback_menu',
                        'walker' => new NearMenus_Walker_Footer_Menu(),
                    ));
                    ?>
                </div>
                
                <!-- Categories -->
                <div class="footer-section">
                    <h4 class="font-semibold mb-4 text-gray-700"><?php _e('Browse Cuisines', 'nearmenus'); ?></h4>
                    <ul class="space-y-2 text-gray-600">
                        <?php
                        $cuisines = get_terms(array(
                            'taxonomy' => 'cuisine',
                            'hide_empty' => true,
                            'number' => 8,
                            'orderby' => 'count',
                            'order' => 'DESC',
                        ));
                        
                        if ($cuisines && !is_wp_error($cuisines)) :
                            foreach ($cuisines as $cuisine) :
                        ?>
                        <li>
                            <a href="<?php echo esc_url(get_term_link($cuisine)); ?>" class="hover:text-primary transition-colors flex items-center">
                                <span class="mr-2"><?php echo nearmenus_get_cuisine_icon($cuisine->slug); ?></span>
                                <?php echo esc_html($cuisine->name); ?>
                                <span class="text-xs text-gray-400 ml-auto">(<?php echo $cuisine->count; ?>)</span>
                            </a>
                        </li>
                        <?php endforeach; else : ?>
                        <li class="text-gray-500 text-sm"><?php _e('No cuisine categories found.', 'nearmenus'); ?></li>
                        <?php endif; ?>
                        
                        <?php if ($cuisines && count($cuisines) >= 8) : ?>
                        <li class="pt-2">
                            <a href="<?php echo esc_url(home_url('/')); ?>" class="text-primary hover:underline text-sm font-medium">
                                <?php _e('View All Cuisines →', 'nearmenus'); ?>
                            </a>
                        </li>
                        <?php endif; ?>
                    </ul>
                </div>
                
                <!-- Contact Info -->
                <div class="footer-section">
                    <h4 class="font-semibold mb-4 text-gray-700"><?php _e('Contact Us', 'nearmenus'); ?></h4>
                    <div class="space-y-3 text-gray-600">
                        <?php if (get_theme_mod('nearmenus_contact_phone')) : ?>
                        <div class="flex items-center">
                            <svg class="w-4 h-4 mr-3 text-primary flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path>
                            </svg>
                            <a href="tel:<?php echo esc_attr(get_theme_mod('nearmenus_contact_phone')); ?>" class="hover:text-primary transition-colors">
                                <?php echo esc_html(get_theme_mod('nearmenus_contact_phone')); ?>
                            </a>
                        </div>
                        <?php endif; ?>
                        
                        <?php if (get_theme_mod('nearmenus_contact_email')) : ?>
                        <div class="flex items-center">
                            <svg class="w-4 h-4 mr-3 text-primary flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 4.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                            </svg>
                            <a href="mailto:<?php echo esc_attr(get_theme_mod('nearmenus_contact_email')); ?>" class="hover:text-primary transition-colors">
                                <?php echo esc_html(get_theme_mod('nearmenus_contact_email')); ?>
                            </a>
                        </div>
                        <?php endif; ?>
                        
                        <?php if (get_theme_mod('nearmenus_contact_address')) : ?>
                        <div class="flex items-start">
                            <svg class="w-4 h-4 mr-3 text-primary flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                            </svg>
                            <span><?php echo esc_html(get_theme_mod('nearmenus_contact_address')); ?></span>
                        </div>
                        <?php endif; ?>
                        
                        <div class="flex items-center">
                            <svg class="w-4 h-4 mr-3 text-primary flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            <span><?php _e('Available 24/7', 'nearmenus'); ?></span>
                        </div>
                    </div>
                    
                    <!-- Newsletter Signup -->
                    <div class="mt-6">
                        <h5 class="font-medium mb-2 text-gray-700"><?php _e('Stay Updated', 'nearmenus'); ?></h5>
                        <p class="text-sm text-gray-600 mb-3"><?php _e('Get notified about new restaurants and special offers.', 'nearmenus'); ?></p>
                        <form class="flex" onsubmit="nearmenus_subscribeNewsletter(event)">
                            <input type="email" placeholder="<?php esc_attr_e('Enter your email', 'nearmenus'); ?>" class="flex-1 px-3 py-2 text-sm border rounded-l-lg focus:outline-none focus:ring-2 focus:ring-primary-500" required>
                            <button type="submit" class="bg-primary-600 text-white px-4 py-2 text-sm rounded-r-lg hover:bg-primary-700 transition-colors">
                                <?php _e('Subscribe', 'nearmenus'); ?>
                            </button>
                        </form>
                    </div>
                </div>
                
            </div>
            
            <!-- Statistics Bar -->
            <div class="border-t border-b border-gray-200 py-6 mb-6">
                <div class="grid grid-cols-2 md:grid-cols-4 gap-4 text-center">
                    <div>
                        <div class="text-2xl font-bold text-primary">
                            <?php
                            $restaurant_count = wp_count_posts('post')->publish;
                            echo number_format($restaurant_count);
                            ?>
                        </div>
                        <div class="text-sm text-gray-600"><?php _e('Restaurants Listed', 'nearmenus'); ?></div>
                    </div>
                    <div>
                        <div class="text-2xl font-bold text-primary">
                            <?php
                            $cuisine_count = wp_count_terms('cuisine', array('hide_empty' => true));
                            echo number_format($cuisine_count);
                            ?>
                        </div>
                        <div class="text-sm text-gray-600"><?php _e('Cuisine Types', 'nearmenus'); ?></div>
                    </div>
                    <div>
                        <div class="text-2xl font-bold text-primary">
                            <?php
                            $location_count = wp_count_terms('location', array('hide_empty' => true));
                            echo number_format($location_count);
                            ?>
                        </div>
                        <div class="text-sm text-gray-600"><?php _e('Cities Covered', 'nearmenus'); ?></div>
                    </div>
                    <div>
                        <div class="text-2xl font-bold text-primary">5★</div>
                        <div class="text-sm text-gray-600"><?php _e('Average Rating', 'nearmenus'); ?></div>
                    </div>
                </div>
            </div>
            
            <!-- Footer Bottom -->
            <div class="text-center text-gray-600">
                <div class="flex flex-col md:flex-row md:items-center md:justify-between">
                    <p class="mb-2 md:mb-0">
                        &copy; <?php echo date('Y'); ?> <?php bloginfo('name'); ?>. <?php _e('All rights reserved.', 'nearmenus'); ?>
                    </p>
                    
                    <div class="flex flex-col md:flex-row md:items-center space-y-2 md:space-y-0 md:space-x-6">
                        <?php if (function_exists('the_privacy_policy_link')) : ?>
                            <?php the_privacy_policy_link('<span class="text-sm hover:text-primary transition-colors">', '</span>'); ?>
                        <?php endif; ?>
                        
                        <span class="text-sm">
                            <a href="#" class="hover:text-primary transition-colors"><?php _e('Terms of Service', 'nearmenus'); ?></a>
                        </span>
                        
                        <span class="text-sm">
                            <a href="#" class="hover:text-primary transition-colors"><?php _e('Cookie Policy', 'nearmenus'); ?></a>
                        </span>
                        
                        <span class="text-sm">
                            <?php printf(__('Powered by %s', 'nearmenus'), '<a href="https://wordpress.org" class="hover:text-primary transition-colors" target="_blank" rel="noopener">WordPress</a>'); ?>
                        </span>
                    </div>
                </div>
                
                <!-- Back to Top Button -->
                <button onclick="nearmenus_scrollToTop()" class="fixed bottom-6 right-6 bg-primary-600 text-white p-3 rounded-full shadow-lg hover:bg-primary-700 transition-all transform hover:scale-110 z-40" id="back-to-top" style="display: none;">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 10l7-7m0 0l7 7m-7-7v18"></path>
                    </svg>
                    <span class="sr-only"><?php _e('Back to top', 'nearmenus'); ?></span>
                </button>
            </div>
            
        </div>
    </footer><!-- #colophon -->
    
</div><!-- #page -->

<!-- Loading Overlay -->
<div id="loading-overlay" class="hidden fixed inset-0 bg-white bg-opacity-90 z-50 flex items-center justify-center">
    <div class="text-center">
        <div class="inline-block animate-spin rounded-full h-12 w-12 border-b-2 border-primary-600 mb-4"></div>
        <p class="text-gray-600"><?php _e('Loading...', 'nearmenus'); ?></p>
    </div>
</div>

<?php wp_footer(); ?>

</body>
</html>