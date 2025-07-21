# Step 1: Basic WordPress Theme Structure

## Overview
This step creates the foundational WordPress theme structure with core templates based on the React NearMenus project analysis. We'll build a fully functional basic theme using default WordPress posts without any special styling - just backend structure and front-end data display.

## React Project Analysis Summary

### Key Components Identified:
1. **Homepage (`page.tsx`)**: Hero section, search functionality, restaurant grid, cuisine categories
2. **Restaurant Detail (`restaurant/[id]/page.tsx`)**: Detailed restaurant view with tabs, contact info, menu
3. **Category Pages (`category/[slug]/page.tsx`)**: Cuisine-specific restaurant listings
4. **Search Functionality**: Advanced search with filters and sorting

### Data Structure from React:
- Restaurant objects with: name, location, rating, reviews, description, phone, cuisine, price range, hours, features, etc.
- Cuisine categories with icons and counts
- Menu items organized by categories (appetizers, mains, desserts, etc.)
- Customer reviews and ratings
- Special offers and features

## Required Files for Step 1

### 1. Theme Header (`style.css`)
```css
/*
Theme Name: NearMenus Restaurant Directory
Description: A restaurant discovery platform theme based on React NearMenus application
Version: 1.0.0
Author: Your Name
Text Domain: nearmenus
*/

/* Basic reset and container styles only - no design styling yet */
* {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
}

.container {
    max-width: 1200px;
    margin: 0 auto;
    padding: 0 20px;
}

/* Basic grid for restaurant listings */
.restaurants-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
    gap: 20px;
}

/* Basic visibility helpers */
.desktop-only { display: block; }
.mobile-only { display: none; }

@media (max-width: 768px) {
    .desktop-only { display: none; }
    .mobile-only { display: block; }
}
```

### 2. Functions File (`functions.php`)
```php
<?php
// Theme setup and basic functionality
function nearmenus_theme_setup() {
    // Add theme support
    add_theme_support('post-thumbnails');
    add_theme_support('title-tag');
    add_theme_support('custom-logo');
    add_theme_support('html5', array('search-form', 'comment-form', 'gallery', 'caption'));
    
    // Register navigation menus
    register_nav_menus(array(
        'primary' => __('Primary Menu', 'nearmenus'),
        'footer' => __('Footer Menu', 'nearmenus'),
    ));
    
    // Add custom image sizes for restaurants
    add_image_size('restaurant-card', 400, 300, true);
    add_image_size('restaurant-hero', 1200, 600, true);
    add_image_size('restaurant-thumb', 150, 150, true);
}
add_action('after_setup_theme', 'nearmenus_theme_setup');

// Enqueue basic styles
function nearmenus_enqueue_assets() {
    wp_enqueue_style('nearmenus-style', get_stylesheet_uri());
}
add_action('wp_enqueue_scripts', 'nearmenus_enqueue_assets');

// Modify main query for restaurant listings
function nearmenus_modify_main_query($query) {
    if (!is_admin() && $query->is_main_query()) {
        if (is_home()) {
            $query->set('posts_per_page', 12);
        }
    }
}
add_action('pre_get_posts', 'nearmenus_modify_main_query');

// Custom excerpt length
function nearmenus_excerpt_length($length) {
    return 25;
}
add_filter('excerpt_length', 'nearmenus_excerpt_length');
```

### 3. Header Template (`header.php`)
```php
<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo('charset'); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<?php wp_body_open(); ?>

<header id="site-header">
    <div class="container">
        <!-- Site Logo/Title -->
        <div class="site-branding">
            <?php if (has_custom_logo()) : ?>
                <?php the_custom_logo(); ?>
            <?php else : ?>
                <h1><a href="<?php echo esc_url(home_url('/')); ?>"><?php bloginfo('name'); ?></a></h1>
            <?php endif; ?>
        </div>
        
        <!-- Primary Navigation -->
        <nav class="primary-navigation">
            <?php 
            wp_nav_menu(array(
                'theme_location' => 'primary',
                'container' => false,
                'fallback_cb' => 'nearmenus_default_menu',
            )); 
            ?>
        </nav>
        
        <!-- Basic Search -->
        <div class="header-search">
            <?php get_search_form(); ?>
        </div>
    </div>
</header>

<?php
function nearmenus_default_menu() {
    echo '<ul>';
    echo '<li><a href="' . home_url('/') . '">Home</a></li>';
    echo '<li><a href="' . home_url('/#restaurants') . '">Restaurants</a></li>';
    echo '<li><a href="' . home_url('/about') . '">About</a></li>';
    echo '<li><a href="' . home_url('/contact') . '">Contact</a></li>';
    echo '</ul>';
}
?>
```

### 4. Footer Template (`footer.php`)
```php
<footer id="site-footer">
    <div class="container">
        <div class="footer-content">
            <!-- Footer Branding -->
            <div class="footer-section">
                <h3><?php bloginfo('name'); ?></h3>
                <p><?php bloginfo('description'); ?></p>
            </div>
            
            <!-- Quick Links -->
            <div class="footer-section">
                <h4>Quick Links</h4>
                <ul>
                    <li><a href="<?php echo home_url('/'); ?>">Home</a></li>
                    <li><a href="<?php echo home_url('/about'); ?>">About</a></li>
                    <li><a href="<?php echo home_url('/contact'); ?>">Contact</a></li>
                </ul>
            </div>
            
            <!-- Restaurant Info -->
            <div class="footer-section">
                <h4>For Restaurants</h4>
                <ul>
                    <li><a href="<?php echo home_url('/list-restaurant'); ?>">List Your Restaurant</a></li>
                    <li><a href="<?php echo home_url('/support'); ?>">Support</a></li>
                </ul>
            </div>
            
            <!-- Contact Info -->
            <div class="footer-section">
                <h4>Contact</h4>
                <p>Phone: 1-800-FOODIE</p>
                <p>Email: support@nearmenus.com</p>
            </div>
        </div>
        
        <div class="footer-bottom">
            <p>&copy; <?php echo date('Y'); ?> <?php bloginfo('name'); ?>. All rights reserved.</p>
        </div>
    </div>
</footer>

<?php wp_footer(); ?>
</body>
</html>
```

### 5. Homepage Template (`index.php`)
Based on React app analysis, the homepage includes hero section, search, and restaurant grid.

```php
<?php get_header(); ?>

<main id="main-content">
    <!-- Hero Section (Basic Structure) -->
    <section class="hero-section">
        <div class="container">
            <h1>Discover Amazing Restaurants</h1>
            <p>Explore the best dining experiences in your city.</p>
            
            <!-- Hero Search Form -->
            <div class="hero-search">
                <form role="search" method="get" action="<?php echo home_url('/'); ?>">
                    <input type="search" 
                           placeholder="Search restaurants, cuisines, or dishes..." 
                           value="<?php echo get_search_query(); ?>" 
                           name="s" />
                    <button type="submit">Search</button>
                </form>
            </div>
        </div>
    </section>
    
    <!-- Restaurant Listings -->
    <section class="restaurants-section">
        <div class="container">
            <h2>Featured Restaurants</h2>
            
            <!-- Basic Controls -->
            <div class="controls">
                <select name="sort">
                    <option value="rating">Sort by Rating</option>
                    <option value="name">Sort by Name</option>
                    <option value="newest">Sort by Newest</option>
                </select>
            </div>
            
            <!-- Restaurant Grid -->
            <div class="restaurants-grid">
                <?php if (have_posts()) : ?>
                    <?php while (have_posts()) : the_post(); ?>
                        <article class="restaurant-card">
                            <?php if (has_post_thumbnail()) : ?>
                                <div class="restaurant-image">
                                    <a href="<?php the_permalink(); ?>">
                                        <?php the_post_thumbnail('restaurant-card'); ?>
                                    </a>
                                </div>
                            <?php endif; ?>
                            
                            <div class="restaurant-info">
                                <h3><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
                                <p class="restaurant-excerpt"><?php the_excerpt(); ?></p>
                                
                                <!-- Basic meta info -->
                                <div class="restaurant-meta">
                                    <span class="post-date"><?php echo get_the_date(); ?></span>
                                </div>
                                
                                <div class="restaurant-actions">
                                    <a href="<?php the_permalink(); ?>" class="view-menu-btn">View Details</a>
                                </div>
                            </div>
                        </article>
                    <?php endwhile; ?>
                <?php else : ?>
                    <p>No restaurants found.</p>
                <?php endif; ?>
            </div>
            
            <!-- Basic Pagination -->
            <?php
            the_posts_pagination(array(
                'prev_text' => '← Previous',
                'next_text' => 'Next →',
            ));
            ?>
        </div>
    </section>
</main>

<?php get_footer(); ?>
```

### 6. Archive Template (`archive.php`)
```php
<?php get_header(); ?>

<main id="main-content">
    <!-- Archive Header -->
    <section class="archive-header">
        <div class="container">
            <h1>
                <?php
                if (is_category()) {
                    single_cat_title();
                } elseif (is_tag()) {
                    single_tag_title();
                } else {
                    echo 'Restaurant Archive';
                }
                ?>
            </h1>
            
            <?php if (is_category() || is_tag()) : ?>
                <div class="archive-description">
                    <?php echo term_description(); ?>
                </div>
            <?php endif; ?>
            
            <p>Showing <?php echo $wp_query->found_posts; ?> restaurants</p>
        </div>
    </section>
    
    <!-- Restaurant Listings -->
    <section class="archive-content">
        <div class="container">
            <div class="restaurants-grid">
                <?php if (have_posts()) : ?>
                    <?php while (have_posts()) : the_post(); ?>
                        <article class="restaurant-card">
                            <?php if (has_post_thumbnail()) : ?>
                                <div class="restaurant-image">
                                    <a href="<?php the_permalink(); ?>">
                                        <?php the_post_thumbnail('restaurant-card'); ?>
                                    </a>
                                </div>
                            <?php endif; ?>
                            
                            <div class="restaurant-info">
                                <h3><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
                                <p><?php the_excerpt(); ?></p>
                                
                                <div class="restaurant-meta">
                                    <span>Posted: <?php echo get_the_date(); ?></span>
                                </div>
                                
                                <a href="<?php the_permalink(); ?>">View Details →</a>
                            </div>
                        </article>
                    <?php endwhile; ?>
                <?php else : ?>
                    <p>No restaurants found in this category.</p>
                <?php endif; ?>
            </div>
            
            <?php
            the_posts_pagination(array(
                'prev_text' => '← Previous',
                'next_text' => 'Next →',
            ));
            ?>
        </div>
    </section>
</main>

<?php get_footer(); ?>
```

### 7. Single Restaurant Template (`single.php`)
Based on React restaurant detail page analysis:

```php
<?php get_header(); ?>

<main id="main-content">
    <?php while (have_posts()) : the_post(); ?>
        
        <!-- Restaurant Header -->
        <section class="restaurant-header">
            <div class="container">
                <!-- Back Navigation -->
                <div class="back-navigation">
                    <a href="<?php echo wp_get_referer() ? wp_get_referer() : home_url('/'); ?>">
                        ← Back to Restaurants
                    </a>
                </div>
                
                <!-- Restaurant Hero -->
                <?php if (has_post_thumbnail()) : ?>
                    <div class="restaurant-hero-image">
                        <?php the_post_thumbnail('restaurant-hero'); ?>
                    </div>
                <?php endif; ?>
                
                <!-- Basic Restaurant Info -->
                <div class="restaurant-basic-info">
                    <h1><?php the_title(); ?></h1>
                    
                    <!-- Basic meta will be enhanced in later steps -->
                    <div class="restaurant-meta">
                        <p>Posted: <?php echo get_the_date(); ?></p>
                        <p>By: <?php the_author(); ?></p>
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
                        <!-- Restaurant Description -->
                        <div class="restaurant-description">
                            <h2>About This Restaurant</h2>
                            <div class="content">
                                <?php the_content(); ?>
                            </div>
                        </div>
                        
                        <!-- Basic sections (will be enhanced with custom fields) -->
                        <div class="restaurant-sections">
                            <div class="section">
                                <h3>Menu</h3>
                                <p>Menu information will be added with custom fields.</p>
                            </div>
                            
                            <div class="section">
                                <h3>Hours</h3>
                                <p>Operating hours will be added with custom fields.</p>
                            </div>
                            
                            <div class="section">
                                <h3>Contact</h3>
                                <p>Contact information will be added with custom fields.</p>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Basic Sidebar -->
                    <aside class="restaurant-sidebar">
                        <div class="widget">
                            <h4>Quick Info</h4>
                            <p>Restaurant details will be populated with custom fields in later steps.</p>
                        </div>
                        
                        <div class="widget">
                            <h4>Categories</h4>
                            <?php
                            $categories = get_the_category();
                            if ($categories) {
                                echo '<ul>';
                                foreach ($categories as $category) {
                                    echo '<li><a href="' . get_category_link($category->term_id) . '">' . $category->name . '</a></li>';
                                }
                                echo '</ul>';
                            }
                            ?>
                        </div>
                    </aside>
                </div>
            </div>
        </section>
        
    <?php endwhile; ?>
</main>

<?php get_footer(); ?>
```

### 8. Search Results Template (`search.php`)
```php
<?php get_header(); ?>

<main id="main-content">
    <!-- Search Header -->
    <section class="search-header">
        <div class="container">
            <h1>
                <?php if (get_search_query()) : ?>
                    Search Results for "<?php echo get_search_query(); ?>"
                <?php else : ?>
                    Search Restaurants
                <?php endif; ?>
            </h1>
            
            <p>Found <?php echo $wp_query->found_posts; ?> restaurants</p>
            
            <!-- Search Form -->
            <div class="search-form-wrapper">
                <form role="search" method="get" action="<?php echo home_url('/'); ?>">
                    <input type="search" 
                           placeholder="Search restaurants, cuisines, or dishes..." 
                           value="<?php echo get_search_query(); ?>" 
                           name="s" />
                    <button type="submit">Search</button>
                </form>
            </div>
        </div>
    </section>
    
    <!-- Search Results -->
    <section class="search-results">
        <div class="container">
            <div class="restaurants-grid">
                <?php if (have_posts()) : ?>
                    <?php while (have_posts()) : the_post(); ?>
                        <article class="restaurant-card">
                            <?php if (has_post_thumbnail()) : ?>
                                <div class="restaurant-image">
                                    <a href="<?php the_permalink(); ?>">
                                        <?php the_post_thumbnail('restaurant-card'); ?>
                                    </a>
                                </div>
                            <?php endif; ?>
                            
                            <div class="restaurant-info">
                                <h3><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
                                <p><?php the_excerpt(); ?></p>
                                
                                <!-- Search relevance info -->
                                <div class="search-meta">
                                    <small>Match found in: Title/Content</small>
                                </div>
                                
                                <a href="<?php the_permalink(); ?>">View Restaurant →</a>
                            </div>
                        </article>
                    <?php endwhile; ?>
                <?php else : ?>
                    <div class="no-results">
                        <h3>No restaurants found</h3>
                        <p>Try different keywords or browse our categories.</p>
                        
                        <!-- Search suggestions -->
                        <div class="search-suggestions">
                            <h4>Try searching for:</h4>
                            <div class="suggestion-tags">
                                <a href="<?php echo home_url('/?s=pizza'); ?>">Pizza</a>
                                <a href="<?php echo home_url('/?s=sushi'); ?>">Sushi</a>
                                <a href="<?php echo home_url('/?s=italian'); ?>">Italian</a>
                                <a href="<?php echo home_url('/?s=burger'); ?>">Burger</a>
                            </div>
                        </div>
                        
                        <a href="<?php echo home_url('/'); ?>">View All Restaurants</a>
                    </div>
                <?php endif; ?>
            </div>
            
            <?php if (have_posts()) : ?>
                <?php
                the_posts_pagination(array(
                    'prev_text' => '← Previous',
                    'next_text' => 'Next →',
                ));
                ?>
            <?php endif; ?>
        </div>
    </section>
</main>

<?php get_footer(); ?>
```

### 9. 404 Error Template (`404.php`)
```php
<?php get_header(); ?>

<main id="main-content">
    <section class="error-404">
        <div class="container">
            <div class="error-content">
                <h1>Page Not Found</h1>
                <h2>404</h2>
                <p>Sorry, the page you are looking for could not be found.</p>
                
                <!-- Search Form -->
                <div class="error-search">
                    <h3>Search for Restaurants</h3>
                    <form role="search" method="get" action="<?php echo home_url('/'); ?>">
                        <input type="search" 
                               placeholder="Search restaurants..." 
                               name="s" />
                        <button type="submit">Search</button>
                    </form>
                </div>
                
                <!-- Quick Links -->
                <div class="error-links">
                    <h3>Quick Links</h3>
                    <ul>
                        <li><a href="<?php echo home_url('/'); ?>">Home</a></li>
                        <li><a href="<?php echo home_url('/#restaurants'); ?>">Browse Restaurants</a></li>
                        <li><a href="<?php echo home_url('/contact'); ?>">Contact</a></li>
                    </ul>
                </div>
            </div>
            
            <!-- Show recent posts -->
            <div class="recent-restaurants">
                <h3>Recent Restaurants</h3>
                <div class="restaurants-grid">
                    <?php
                    $recent_posts = new WP_Query(array(
                        'posts_per_page' => 3,
                        'post_status' => 'publish'
                    ));
                    
                    if ($recent_posts->have_posts()) :
                        while ($recent_posts->have_posts()) : $recent_posts->the_post();
                    ?>
                        <article class="restaurant-card">
                            <?php if (has_post_thumbnail()) : ?>
                                <div class="restaurant-image">
                                    <a href="<?php the_permalink(); ?>">
                                        <?php the_post_thumbnail('restaurant-thumb'); ?>
                                    </a>
                                </div>
                            <?php endif; ?>
                            <div class="restaurant-info">
                                <h4><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h4>
                                <p><?php echo wp_trim_words(get_the_excerpt(), 10); ?></p>
                            </div>
                        </article>
                    <?php 
                        endwhile;
                        wp_reset_postdata();
                    endif;
                    ?>
                </div>
            </div>
        </div>
    </section>
</main>

<?php get_footer(); ?>
```

### 10. Search Form Template (`searchform.php`)
```php
<form role="search" method="get" class="search-form" action="<?php echo esc_url(home_url('/')); ?>">
    <label>
        <span class="screen-reader-text">Search for restaurants:</span>
        <input type="search" 
               class="search-field" 
               placeholder="Search restaurants..." 
               value="<?php echo get_search_query(); ?>" 
               name="s" />
    </label>
    <button type="submit" class="search-submit">
        <span class="screen-reader-text">Search</span>
        <span>Search</span>
    </button>
</form>
```

## Implementation Instructions

### Step-by-Step Setup:

1. **Create Theme Directory**: Create folder `nearmenus-theme` in `wp-content/themes/`

2. **Add Required Files**: Create all the files listed above in the theme directory

3. **Activate Theme**: Go to Appearance → Themes and activate the NearMenus theme

4. **Test Basic Functionality**:
   - Homepage displays with hero section and restaurant grid
   - Single restaurant pages show content properly
   - Search functionality works
   - Archive pages display correctly
   - 404 page shows with helpful links

5. **Add Sample Content**:
   - Create a few sample restaurant posts
   - Add featured images to test image display
   - Test navigation and search

## Key Features Implemented in Step 1:

### ✅ Completed:
- Basic theme structure with all required templates
- Default WordPress post system for restaurants
- Hero section with search functionality
- Restaurant grid layout (basic)
- Single restaurant page structure
- Search results page
- 404 error page with helpful content
- Basic navigation and footer
- Responsive grid system (basic)
- Image support with custom sizes

### 🔄 Ready for Next Steps:
- Taxonomies (Step 2)
- Custom fields (Step 3)
- Widgets (Step 4)
- Styling (Steps 5-6)

## Testing Checklist:

- [ ] Theme activates without errors
- [ ] Homepage displays correctly
- [ ] Can create and view restaurant posts
- [ ] Search functionality works
- [ ] Navigation menu displays
- [ ] Images display properly
- [ ] Archive pages work
- [ ] 404 page displays correctly
- [ ] Mobile responsive (basic)
- [ ] No JavaScript errors in console

This basic theme structure provides the foundation for all the advanced features identified in the React app analysis. Each template includes the basic structure needed for later enhancement with taxonomies, custom fields, and styling.