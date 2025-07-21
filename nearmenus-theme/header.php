<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo('charset'); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<?php wp_body_open(); ?>

<header id="site-header" class="site-header">
    <div class="container">
        <div class="header-content">
            <!-- Mobile Menu Toggle -->
            <button class="mobile-menu-toggle" aria-label="<?php esc_attr_e('Toggle navigation menu', 'nearmenus'); ?>">
                <span class="hamburger-line"></span>
                <span class="hamburger-line"></span>
                <span class="hamburger-line"></span>
            </button>
            
            <!-- Site Logo/Title -->
            <div class="site-branding">
                <?php if (has_custom_logo()) : ?>
                    <div class="site-logo">
                        <?php the_custom_logo(); ?>
                    </div>
                <?php else : ?>
                    <h1 class="site-title">
                        <a href="<?php echo esc_url(home_url('/')); ?>" rel="home">
                            <?php bloginfo('name'); ?>
                        </a>
                    </h1>
                <?php endif; ?>
            </div>
            
            <!-- Primary Navigation -->
            <nav class="primary-navigation" role="navigation" aria-label="<?php esc_attr_e('Primary Navigation', 'nearmenus'); ?>">
                <?php 
                wp_nav_menu(array(
                    'theme_location' => 'primary',
                    'menu_class'     => 'primary-menu',
                    'container'      => false,
                    'fallback_cb'    => 'nearmenus_default_menu',
                )); 
                ?>
            </nav>
            
            <!-- Header Search (Desktop) -->
            <div class="header-search desktop-only">
                <form class="search-form" role="search" method="get" action="<?php echo esc_url(home_url('/')); ?>">
                    <input type="search" 
                           class="search-field" 
                           placeholder="<?php esc_attr_e('Search restaurants...', 'nearmenus'); ?>" 
                           value="<?php echo get_search_query(); ?>" 
                           name="s" />
                    <button type="submit" class="search-submit" aria-label="<?php esc_attr_e('Search', 'nearmenus'); ?>">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <circle cx="11" cy="11" r="8"></circle>
                            <path d="21 21l-4.35-4.35"></path>
                        </svg>
                    </button>
                </form>
            </div>
        </div>
    </div>
    
    <!-- Mobile Navigation Overlay -->
    <div class="mobile-nav-overlay">
        <div class="mobile-nav-content">
            <div class="mobile-nav-header">
                <h3><?php esc_html_e('Menu', 'nearmenus'); ?></h3>
                <button class="mobile-nav-close" aria-label="<?php esc_attr_e('Close menu', 'nearmenus'); ?>">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <line x1="18" y1="6" x2="6" y2="18"></line>
                        <line x1="6" y1="6" x2="18" y2="18"></line>
                    </svg>
                </button>
            </div>
            
            <!-- Mobile Search -->
            <div class="mobile-search">
                <form class="search-form" role="search" method="get" action="<?php echo esc_url(home_url('/')); ?>">
                    <input type="search" 
                           class="search-field" 
                           placeholder="<?php esc_attr_e('Search restaurants...', 'nearmenus'); ?>" 
                           value="<?php echo get_search_query(); ?>" 
                           name="s" />
                    <button type="submit" class="search-submit">
                        <?php esc_html_e('Search', 'nearmenus'); ?>
                    </button>
                </form>
            </div>
            
            <!-- Mobile Menu -->
            <nav class="mobile-navigation" role="navigation" aria-label="<?php esc_attr_e('Mobile Navigation', 'nearmenus'); ?>">
                <?php 
                wp_nav_menu(array(
                    'theme_location' => 'primary',
                    'menu_class'     => 'mobile-menu',
                    'container'      => false,
                    'fallback_cb'    => 'nearmenus_default_menu',
                )); 
                ?>
            </nav>
            
            <!-- Cuisine Categories (Mobile) -->
            <div class="mobile-categories">
                <h4><?php esc_html_e('Browse by Cuisine', 'nearmenus'); ?></h4>
                <div class="category-links">
                    <?php
                    $cuisines = get_terms(array(
                        'taxonomy' => 'cuisine',
                        'hide_empty' => false,
                        'number' => 8,
                    ));
                    
                    if (!is_wp_error($cuisines) && !empty($cuisines)) :
                        foreach ($cuisines as $cuisine) :
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
        </div>
    </div>
</header>

<?php
/**
 * Default menu fallback function
 */
function nearmenus_default_menu() {
    echo '<ul class="primary-menu">';
    echo '<li><a href="' . esc_url(home_url('/')) . '">' . esc_html__('Home', 'nearmenus') . '</a></li>';
    echo '<li><a href="' . esc_url(home_url('/#restaurants')) . '">' . esc_html__('Restaurants', 'nearmenus') . '</a></li>';
    echo '<li><a href="' . esc_url(home_url('/about')) . '">' . esc_html__('About', 'nearmenus') . '</a></li>';
    echo '<li><a href="' . esc_url(home_url('/contact')) . '">' . esc_html__('Contact', 'nearmenus') . '</a></li>';
    echo '</ul>';
}
?>