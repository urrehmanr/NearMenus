<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo('charset'); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="profile" href="https://gmpg.org/xfn/11">
    
    <?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
<?php wp_body_open(); ?>

<div id="page" class="site min-h-screen flex flex-col">
    <header id="masthead" class="site-header">
        <nav class="navbar bg-white border-b sticky top-0 z-50 shadow-sm">
            <div class="container mx-auto px-4">
                <div class="flex items-center justify-between py-4">
                    
                    <!-- Logo/Site Title -->
                    <div class="navbar-brand flex items-center">
                        <?php if (has_custom_logo()) : ?>
                            <?php the_custom_logo(); ?>
                        <?php else : ?>
                            <a href="<?php echo esc_url(home_url('/')); ?>" class="text-2xl font-bold text-primary flex items-center">
                                <svg class="w-8 h-8 mr-2 text-orange-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-4m-5 0H3m2 0h4M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 8v-8a1 1 0 011-1h4a1 1 0 011 1v8M7 7h10"></path>
                                </svg>
                                <?php bloginfo('name'); ?>
                            </a>
                        <?php endif; ?>
                    </div>
                    
                    <!-- Desktop Navigation -->
                    <div class="hidden md:flex items-center space-x-8">
                        <?php
                        wp_nav_menu(array(
                            'theme_location' => 'primary',
                            'menu_class' => 'flex items-center space-x-6',
                            'container' => false,
                            'fallback_cb' => 'nearmenus_fallback_menu',
                            'walker' => new NearMenus_Walker_Nav_Menu(),
                        ));
                        ?>
                        
                        <!-- Quick Actions -->
                        <div class="flex items-center space-x-4">
                            <!-- Search Icon -->
                            <button class="search-toggle text-gray-600 hover:text-primary transition-colors p-2 rounded-lg hover:bg-gray-100" aria-label="<?php _e('Search', 'nearmenus'); ?>" onclick="nearmenus_toggleSearch()">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                                </svg>
                            </button>
                            
                            <!-- Location Quick Access -->
                            <div class="relative">
                                <button class="location-toggle text-gray-600 hover:text-primary transition-colors p-2 rounded-lg hover:bg-gray-100 flex items-center" onclick="nearmenus_toggleLocationMenu()">
                                    <svg class="w-5 h-5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                    </svg>
                                    <span class="text-sm hidden lg:inline"><?php _e('Location', 'nearmenus'); ?></span>
                                </button>
                                
                                <!-- Location Dropdown -->
                                <div id="location-menu" class="hidden absolute right-0 mt-2 w-48 bg-white rounded-lg shadow-lg border z-50">
                                    <div class="p-2">
                                        <?php
                                        $locations = get_terms(array(
                                            'taxonomy' => 'location',
                                            'hide_empty' => true,
                                            'number' => 8,
                                        ));
                                        
                                        if ($locations && !is_wp_error($locations)) :
                                            foreach ($locations as $location) :
                                        ?>
                                        <a href="<?php echo esc_url(get_term_link($location)); ?>" class="block px-3 py-2 text-sm text-gray-700 hover:bg-gray-100 rounded">
                                            <?php echo esc_html($location->name); ?>
                                            <span class="text-xs text-gray-500 ml-1">(<?php echo $location->count; ?>)</span>
                                        </a>
                                        <?php endforeach; endif; ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Mobile Menu Toggle -->
                    <button class="md:hidden mobile-menu-toggle p-2 rounded-lg hover:bg-gray-100" aria-label="<?php _e('Menu', 'nearmenus'); ?>" onclick="nearmenus_toggleMobileMenu()">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                        </svg>
                    </button>
                    
                </div>
                
                <!-- Mobile Menu -->
                <div id="mobile-menu" class="md:hidden hidden border-t bg-white">
                    <div class="px-4 py-4 space-y-4">
                        <!-- Mobile Navigation -->
                        <?php
                        wp_nav_menu(array(
                            'theme_location' => 'mobile',
                            'menu_class' => 'mobile-menu-list space-y-2',
                            'container' => false,
                            'fallback_cb' => 'nearmenus_fallback_menu',
                            'walker' => new NearMenus_Walker_Mobile_Menu(),
                        ));
                        ?>
                        
                        <!-- Mobile Search -->
                        <div class="pt-4 border-t">
                            <button onclick="nearmenus_toggleSearch()" class="w-full flex items-center justify-center px-4 py-2 bg-primary-600 text-white rounded-lg hover:bg-primary-700 transition-colors">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                                </svg>
                                <?php _e('Search Restaurants', 'nearmenus'); ?>
                            </button>
                        </div>
                        
                        <!-- Mobile Cuisines -->
                        <div class="pt-4 border-t">
                            <h3 class="font-semibold text-gray-700 mb-3"><?php _e('Popular Cuisines', 'nearmenus'); ?></h3>
                            <div class="grid grid-cols-2 gap-2">
                                <?php
                                $mobile_cuisines = get_terms(array(
                                    'taxonomy' => 'cuisine',
                                    'hide_empty' => true,
                                    'number' => 6,
                                    'orderby' => 'count',
                                    'order' => 'DESC',
                                ));
                                
                                if ($mobile_cuisines && !is_wp_error($mobile_cuisines)) :
                                    foreach ($mobile_cuisines as $cuisine) :
                                ?>
                                <a href="<?php echo esc_url(get_term_link($cuisine)); ?>" class="flex items-center p-2 bg-gray-50 rounded text-sm hover:bg-gray-100 transition-colors">
                                    <span class="mr-2"><?php echo nearmenus_get_cuisine_icon($cuisine->slug); ?></span>
                                    <?php echo esc_html($cuisine->name); ?>
                                </a>
                                <?php endforeach; endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
                
            </div>
        </nav>
        
        <!-- Search Modal -->
        <div id="search-modal" class="search-modal hidden fixed inset-0 bg-black bg-opacity-50 z-50" onclick="nearmenus_closeSearch(event)">
            <div class="container mx-auto px-4 pt-20">
                <div class="max-w-2xl mx-auto bg-white rounded-lg p-6">
                    <div class="flex items-center justify-between mb-4">
                        <h2 class="text-lg font-semibold"><?php _e('Search Restaurants', 'nearmenus'); ?></h2>
                        <button onclick="nearmenus_closeSearch()" class="text-gray-400 hover:text-gray-600">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                        </button>
                    </div>
                    <?php get_template_part('template-parts/search-form'); ?>
                    
                    <!-- Quick Search Suggestions -->
                    <div class="mt-6">
                        <h3 class="text-sm font-medium text-gray-700 mb-3"><?php _e('Popular Searches', 'nearmenus'); ?></h3>
                        <div class="flex flex-wrap gap-2">
                            <?php
                            $popular_searches = array(
                                __('Pizza', 'nearmenus'),
                                __('Sushi', 'nearmenus'), 
                                __('Burger', 'nearmenus'),
                                __('Italian', 'nearmenus'),
                                __('Thai', 'nearmenus'),
                                __('Mexican', 'nearmenus')
                            );
                            
                            foreach ($popular_searches as $search_term) :
                            ?>
                            <button onclick="nearmenus_quickSearch('<?php echo esc_js($search_term); ?>')" class="px-3 py-1 bg-gray-100 text-gray-700 rounded-full text-sm hover:bg-gray-200 transition-colors">
                                <?php echo esc_html($search_term); ?>
                            </button>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
    </header><!-- #masthead -->

    <div id="content" class="site-content flex-1"><?php echo "\n"; ?>