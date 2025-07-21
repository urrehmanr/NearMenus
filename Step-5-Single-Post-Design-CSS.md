# Step 5: Single Post Design with CSS

## Overview
This step implements the complete CSS styling for single restaurant pages based on the React NearMenus project analysis. We'll create a comprehensive, modern design that matches the functionality and visual appeal of the React app.

## React Project Analysis for Single Restaurant Design

### From React Single Restaurant Page (`restaurant/[id]/page.tsx`):

**Key Design Elements Identified:**
1. **Hero Section** - Large background image with restaurant title overlay
2. **Navigation Tabs** - Overview, Menu, Reviews, Info tabs
3. **Restaurant Header** - Title, rating, cuisine, location, features
4. **Content Sections** - Description, hours, contact, gallery
5. **Menu Display** - Categorized menu with pricing and descriptions
6. **Sidebar Elements** - Quick info, features, contact details
7. **Interactive Elements** - Ratings, favoriting, sharing buttons
8. **Modern Layout** - Clean grid, cards, proper spacing

### Design Patterns from React App:
- **Card-based layouts** for content sections
- **Gradient overlays** on hero images
- **Star ratings** with filled/empty states
- **Badge system** for features and status
- **Responsive grid** for menu items
- **Clean typography** hierarchy
- **Interactive buttons** with hover states
- **Modern spacing** and shadows

## CSS Implementation

### 1. Single Restaurant Base Styles (`assets/css/single-restaurant.css`)

```css
/* ========================================
   SINGLE RESTAURANT PAGE STYLES
   Based on React NearMenus App Analysis
======================================== */

/* Reset and Base Styles */
.single-restaurant * {
    box-sizing: border-box;
}

.single-restaurant {
    font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, sans-serif;
    line-height: 1.6;
    color: #333;
}

/* ========================================
   RESTAURANT HERO SECTION
======================================== */

.restaurant-header {
    position: relative;
    margin-bottom: 2rem;
}

.restaurant-hero {
    position: relative;
    height: 60vh;
    min-height: 400px;
    max-height: 600px;
    overflow: hidden;
    border-radius: 12px;
    box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
}

.restaurant-hero-image {
    position: relative;
    width: 100%;
    height: 100%;
}

.restaurant-hero-image img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: transform 0.3s ease;
}

.restaurant-hero:hover .restaurant-hero-image img {
    transform: scale(1.05);
}

.hero-overlay {
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: linear-gradient(
        to bottom,
        rgba(0, 0, 0, 0.1) 0%,
        rgba(0, 0, 0, 0.3) 50%,
        rgba(0, 0, 0, 0.7) 100%
    );
}

.restaurant-hero-content {
    position: absolute;
    bottom: 0;
    left: 0;
    right: 0;
    padding: 2rem;
    color: white;
    z-index: 2;
}

.back-navigation {
    position: absolute;
    top: 1rem;
    left: 1rem;
    z-index: 3;
}

.back-navigation a {
    display: inline-flex;
    align-items: center;
    padding: 0.5rem 1rem;
    background: rgba(255, 255, 255, 0.9);
    color: #333;
    text-decoration: none;
    border-radius: 25px;
    font-weight: 500;
    transition: all 0.3s ease;
    backdrop-filter: blur(10px);
}

.back-navigation a:hover {
    background: rgba(255, 255, 255, 1);
    transform: translateY(-2px);
    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
}

/* ========================================
   RESTAURANT BASIC INFO
======================================== */

.restaurant-basic-info h1 {
    font-size: 2.5rem;
    font-weight: 700;
    margin: 0 0 0.5rem 0;
    text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.5);
}

.restaurant-meta {
    display: flex;
    flex-wrap: wrap;
    gap: 1rem;
    align-items: center;
    margin-bottom: 1rem;
}

.restaurant-rating {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    background: rgba(255, 255, 255, 0.9);
    padding: 0.5rem 1rem;
    border-radius: 25px;
    color: #333;
    font-weight: 600;
}

.stars {
    display: flex;
    gap: 2px;
}

.star {
    font-size: 1.2rem;
    color: #ffd700;
    text-shadow: 1px 1px 2px rgba(0, 0, 0, 0.3);
}

.star.filled {
    color: #ffd700;
}

.star.half {
    color: #ffd700;
}

.star.empty {
    color: #ddd;
}

.rating-value {
    font-size: 1.1rem;
    font-weight: 700;
    margin-left: 0.25rem;
}

.reviews-count {
    font-size: 0.9rem;
    color: #666;
    margin-left: 0.25rem;
}

/* Status Badges */
.restaurant-status {
    display: flex;
    gap: 0.5rem;
    margin-top: 0.5rem;
}

.status-badge {
    display: inline-flex;
    align-items: center;
    gap: 0.25rem;
    padding: 0.25rem 0.75rem;
    border-radius: 15px;
    font-size: 0.85rem;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.status-badge.open {
    background: #10b981;
    color: white;
}

.status-badge.closed {
    background: #ef4444;
    color: white;
}

.status-badge.delivery {
    background: #3b82f6;
    color: white;
}

.status-indicator {
    width: 8px;
    height: 8px;
    border-radius: 50%;
    background: currentColor;
}

/* ========================================
   RESTAURANT CONTENT LAYOUT
======================================== */

.restaurant-content {
    padding: 2rem 0;
}

.restaurant-layout {
    display: grid;
    grid-template-columns: 2fr 1fr;
    gap: 3rem;
    max-width: 1200px;
    margin: 0 auto;
}

.restaurant-main {
    min-width: 0; /* Prevent overflow */
}

.restaurant-sidebar {
    position: sticky;
    top: 2rem;
    height: fit-content;
}

/* ========================================
   RESTAURANT DESCRIPTION
======================================== */

.restaurant-description {
    background: white;
    border-radius: 12px;
    padding: 2rem;
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.05);
    margin-bottom: 2rem;
    border: 1px solid #f0f0f0;
}

.restaurant-description h2 {
    font-size: 1.8rem;
    font-weight: 700;
    margin: 0 0 1rem 0;
    color: #1f2937;
    border-bottom: 3px solid #3b82f6;
    padding-bottom: 0.5rem;
    display: inline-block;
}

.restaurant-description .content {
    font-size: 1.1rem;
    line-height: 1.8;
    color: #4b5563;
}

.restaurant-description .content p {
    margin-bottom: 1rem;
}

.restaurant-description .content p:last-child {
    margin-bottom: 0;
}

/* ========================================
   RESTAURANT SECTIONS
======================================== */

.restaurant-sections {
    display: flex;
    flex-direction: column;
    gap: 2rem;
}

.section {
    background: white;
    border-radius: 12px;
    padding: 2rem;
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.05);
    border: 1px solid #f0f0f0;
    transition: all 0.3s ease;
}

.section:hover {
    box-shadow: 0 8px 30px rgba(0, 0, 0, 0.1);
    transform: translateY(-2px);
}

.section h3 {
    font-size: 1.5rem;
    font-weight: 700;
    margin: 0 0 1.5rem 0;
    color: #1f2937;
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.section h3::before {
    content: '';
    width: 4px;
    height: 1.5rem;
    background: linear-gradient(135deg, #3b82f6, #1d4ed8);
    border-radius: 2px;
}

/* ========================================
   MENU DISPLAY
======================================== */

.restaurant-menu {
    background: white;
    border-radius: 12px;
    padding: 2rem;
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.05);
    border: 1px solid #f0f0f0;
}

.menu-category {
    margin-bottom: 3rem;
}

.menu-category:last-child {
    margin-bottom: 0;
}

.category-title {
    font-size: 1.8rem;
    font-weight: 700;
    margin: 0 0 1.5rem 0;
    color: #1f2937;
    text-transform: capitalize;
    position: relative;
    padding-bottom: 0.5rem;
}

.category-title::after {
    content: '';
    position: absolute;
    bottom: 0;
    left: 0;
    width: 60px;
    height: 3px;
    background: linear-gradient(135deg, #3b82f6, #1d4ed8);
    border-radius: 2px;
}

.menu-items {
    display: grid;
    gap: 1.5rem;
}

.menu-item {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    padding: 1.5rem;
    background: #f9fafb;
    border-radius: 8px;
    border: 1px solid #e5e7eb;
    transition: all 0.3s ease;
    position: relative;
}

.menu-item:hover {
    background: #f3f4f6;
    border-color: #3b82f6;
    box-shadow: 0 4px 15px rgba(59, 130, 246, 0.1);
}

.menu-item.popular {
    border-color: #fbbf24;
    background: linear-gradient(135deg, #fef3c7, #fef9e3);
}

.menu-item.popular::before {
    content: '🔥 Popular';
    position: absolute;
    top: -8px;
    left: 1rem;
    background: #fbbf24;
    color: white;
    padding: 0.25rem 0.75rem;
    border-radius: 15px;
    font-size: 0.75rem;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.item-header {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    width: 100%;
    margin-bottom: 0.5rem;
}

.item-name {
    font-size: 1.2rem;
    font-weight: 700;
    color: #1f2937;
    margin: 0;
    flex: 1;
}

.item-price {
    font-size: 1.2rem;
    font-weight: 700;
    color: #059669;
    margin-left: 1rem;
    white-space: nowrap;
}

.item-description {
    color: #6b7280;
    line-height: 1.6;
    margin: 0;
    font-size: 0.95rem;
}

.popular-badge {
    position: absolute;
    top: -8px;
    right: 1rem;
    background: #ef4444;
    color: white;
    padding: 0.25rem 0.5rem;
    border-radius: 12px;
    font-size: 0.7rem;
    font-weight: 600;
    text-transform: uppercase;
}

/* ========================================
   SIDEBAR STYLES
======================================== */

.restaurant-sidebar .widget {
    background: white;
    border-radius: 12px;
    padding: 1.5rem;
    margin-bottom: 1.5rem;
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.05);
    border: 1px solid #f0f0f0;
    transition: all 0.3s ease;
}

.restaurant-sidebar .widget:hover {
    box-shadow: 0 8px 30px rgba(0, 0, 0, 0.1);
}

.restaurant-sidebar .widget:last-child {
    margin-bottom: 0;
}

.restaurant-sidebar .widget h4 {
    font-size: 1.3rem;
    font-weight: 700;
    margin: 0 0 1rem 0;
    color: #1f2937;
    border-bottom: 2px solid #e5e7eb;
    padding-bottom: 0.5rem;
}

/* Contact Information Widget */
.contact-widget-content .contact-item {
    display: flex;
    align-items: center;
    margin-bottom: 1rem;
    padding: 0.75rem;
    background: #f9fafb;
    border-radius: 8px;
    border: 1px solid #e5e7eb;
    transition: all 0.3s ease;
}

.contact-widget-content .contact-item:hover {
    background: #f3f4f6;
    border-color: #3b82f6;
}

.contact-widget-content .contact-item:last-child {
    margin-bottom: 0;
}

.contact-widget-content .contact-icon {
    font-size: 1.2rem;
    margin-right: 0.75rem;
    width: 24px;
    text-align: center;
}

.contact-widget-content .contact-info {
    flex: 1;
}

.contact-widget-content .contact-info label {
    display: block;
    font-size: 0.8rem;
    font-weight: 600;
    color: #6b7280;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    margin-bottom: 0.25rem;
}

.contact-widget-content .contact-info a,
.contact-widget-content .contact-info span {
    color: #1f2937;
    text-decoration: none;
    font-weight: 500;
    transition: color 0.3s ease;
}

.contact-widget-content .contact-info a:hover {
    color: #3b82f6;
}

/* Features Widget */
.features-widget-content .features-list {
    list-style: none;
    padding: 0;
    margin: 0;
}

.features-widget-content .feature-item {
    display: flex;
    align-items: center;
    padding: 0.75rem 0;
    border-bottom: 1px solid #e5e7eb;
    transition: all 0.3s ease;
}

.features-widget-content .feature-item:hover {
    padding-left: 0.5rem;
    background: #f9fafb;
    margin: 0 -0.5rem;
    border-radius: 6px;
}

.features-widget-content .feature-item:last-child {
    border-bottom: none;
}

.features-widget-content .feature-icon {
    font-size: 1.1rem;
    margin-right: 0.75rem;
    width: 20px;
    text-align: center;
}

.features-widget-content .feature-name {
    font-weight: 500;
    color: #374151;
}

.features-widget-content .feature-description {
    display: block;
    color: #6b7280;
    font-size: 0.85rem;
    margin-top: 0.25rem;
    font-style: italic;
}

/* Hours Widget */
.hours-widget-content .restaurant-status {
    display: flex;
    align-items: center;
    padding: 0.75rem;
    border-radius: 8px;
    margin-bottom: 1rem;
    font-weight: 600;
}

.hours-widget-content .restaurant-status.status-open {
    background: #d1fae5;
    color: #065f46;
    border: 1px solid #a7f3d0;
}

.hours-widget-content .restaurant-status.status-closed {
    background: #fee2e2;
    color: #991b1b;
    border: 1px solid #fca5a5;
}

.hours-widget-content .status-indicator {
    width: 10px;
    height: 10px;
    border-radius: 50%;
    margin-right: 0.5rem;
}

.hours-widget-content .status-open .status-indicator {
    background: #10b981;
}

.hours-widget-content .status-closed .status-indicator {
    background: #ef4444;
}

.hours-widget-content .hours-list {
    list-style: none;
    padding: 0;
    margin: 0;
}

.hours-widget-content .hours-item {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 0.5rem 0;
    border-bottom: 1px solid #e5e7eb;
    font-size: 0.9rem;
}

.hours-widget-content .hours-item:last-child {
    border-bottom: none;
}

.hours-widget-content .hours-item.today {
    background: #eff6ff;
    margin: 0 -0.75rem;
    padding: 0.75rem;
    border-radius: 6px;
    font-weight: 600;
    color: #1d4ed8;
    border: 1px solid #bfdbfe;
}

.hours-widget-content .hours-day {
    font-weight: 500;
    color: #374151;
}

.hours-widget-content .hours-time {
    color: #6b7280;
    font-weight: 500;
}

.hours-widget-content .holiday-hours {
    margin-top: 1rem;
    padding: 0.75rem;
    background: #fef3c7;
    border: 1px solid #fbbf24;
    border-radius: 6px;
    font-size: 0.85rem;
    color: #92400e;
}

/* ========================================
   SPECIAL OFFERS SECTION
======================================== */

.special-offer {
    background: linear-gradient(135deg, #fef3c7, #fcd34d);
    border: 2px solid #fbbf24;
    border-radius: 12px;
    padding: 1.5rem;
    margin-bottom: 2rem;
    position: relative;
    overflow: hidden;
}

.special-offer::before {
    content: '';
    position: absolute;
    top: -50%;
    left: -50%;
    width: 200%;
    height: 200%;
    background: linear-gradient(45deg, transparent, rgba(255, 255, 255, 0.1), transparent);
    transform: rotate(45deg);
    animation: shimmer 3s infinite;
}

@keyframes shimmer {
    0% {
        transform: rotate(45deg) translateX(-200%);
    }
    100% {
        transform: rotate(45deg) translateX(200%);
    }
}

.special-offer h4 {
    font-size: 1.3rem;
    font-weight: 700;
    color: #92400e;
    margin: 0 0 0.5rem 0;
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.special-offer h4::before {
    content: '🎉';
    font-size: 1.1rem;
}

.special-offer p {
    color: #451a03;
    font-weight: 500;
    margin: 0;
    line-height: 1.6;
}

/* ========================================
   RESPONSIVE DESIGN
======================================== */

@media (max-width: 1024px) {
    .restaurant-layout {
        grid-template-columns: 1fr;
        gap: 2rem;
    }
    
    .restaurant-sidebar {
        position: static;
        order: -1;
    }
    
    .restaurant-basic-info h1 {
        font-size: 2rem;
    }
}

@media (max-width: 768px) {
    .restaurant-hero {
        height: 50vh;
        min-height: 300px;
        border-radius: 8px;
    }
    
    .restaurant-hero-content {
        padding: 1.5rem;
    }
    
    .restaurant-basic-info h1 {
        font-size: 1.8rem;
    }
    
    .restaurant-meta {
        flex-direction: column;
        align-items: flex-start;
        gap: 0.75rem;
    }
    
    .restaurant-content {
        padding: 1rem 0;
    }
    
    .restaurant-description,
    .section,
    .restaurant-menu {
        padding: 1.5rem;
        border-radius: 8px;
    }
    
    .restaurant-sidebar .widget {
        padding: 1rem;
        border-radius: 8px;
    }
    
    .menu-item {
        flex-direction: column;
        align-items: flex-start;
    }
    
    .item-header {
        width: 100%;
        margin-bottom: 0.5rem;
    }
    
    .item-price {
        margin-left: 0;
        margin-top: 0.5rem;
    }
}

@media (max-width: 480px) {
    .container {
        padding: 0 15px;
    }
    
    .restaurant-hero-content {
        padding: 1rem;
    }
    
    .restaurant-basic-info h1 {
        font-size: 1.5rem;
    }
    
    .back-navigation {
        top: 0.5rem;
        left: 0.5rem;
    }
    
    .restaurant-description,
    .section,
    .restaurant-menu,
    .restaurant-sidebar .widget {
        padding: 1rem;
    }
    
    .category-title {
        font-size: 1.5rem;
    }
    
    .menu-item {
        padding: 1rem;
    }
    
    .item-name {
        font-size: 1.1rem;
    }
    
    .item-price {
        font-size: 1.1rem;
    }
}

/* ========================================
   PRINT STYLES
======================================== */

@media print {
    .restaurant-hero,
    .back-navigation,
    .restaurant-sidebar {
        display: none;
    }
    
    .restaurant-layout {
        grid-template-columns: 1fr;
    }
    
    .restaurant-content {
        padding: 0;
    }
    
    .restaurant-description,
    .section,
    .restaurant-menu {
        box-shadow: none;
        border: 1px solid #ddd;
        page-break-inside: avoid;
    }
    
    .menu-category {
        page-break-inside: avoid;
    }
}

/* ========================================
   ACCESSIBILITY IMPROVEMENTS
======================================== */

/* Focus states */
.back-navigation a:focus,
.contact-widget-content .contact-info a:focus {
    outline: 2px solid #3b82f6;
    outline-offset: 2px;
}

/* High contrast mode */
@media (prefers-contrast: high) {
    .restaurant-description,
    .section,
    .restaurant-menu,
    .restaurant-sidebar .widget {
        border: 2px solid #333;
    }
    
    .menu-item {
        border: 1px solid #333;
    }
    
    .category-title::after {
        background: #333;
    }
}

/* Reduced motion */
@media (prefers-reduced-motion: reduce) {
    *,
    *::before,
    *::after {
        animation-duration: 0.01ms !important;
        animation-iteration-count: 1 !important;
        transition-duration: 0.01ms !important;
    }
}

/* Dark mode support */
@media (prefers-color-scheme: dark) {
    .single-restaurant {
        background: #111827;
        color: #f9fafb;
    }
    
    .restaurant-description,
    .section,
    .restaurant-menu,
    .restaurant-sidebar .widget {
        background: #1f2937;
        border-color: #374151;
    }
    
    .menu-item {
        background: #374151;
        border-color: #4b5563;
    }
    
    .menu-item:hover {
        background: #4b5563;
    }
    
    .contact-widget-content .contact-item {
        background: #374151;
        border-color: #4b5563;
    }
    
    .hours-widget-content .hours-item {
        border-color: #4b5563;
    }
}
```

### 2. Enhanced Template Updates

Update the `single.php` template to use the new CSS classes:

```php
<?php get_header(); ?>

<main id="main-content" class="single-restaurant">
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
                    <div class="restaurant-hero">
                        <div class="restaurant-hero-image">
                            <?php the_post_thumbnail('restaurant-hero', array('alt' => get_the_title())); ?>
                        </div>
                        <div class="hero-overlay"></div>
                        
                        <div class="restaurant-hero-content">
                            <!-- Restaurant Basic Info -->
                            <div class="restaurant-basic-info">
                                <h1><?php the_title(); ?></h1>
                                
                                <div class="restaurant-meta">
                                    <!-- Rating Display -->
                                    <?php
                                    $rating = get_post_meta(get_the_ID(), '_restaurant_rating', true);
                                    $reviews_count = get_post_meta(get_the_ID(), '_restaurant_reviews_count', true);
                                    if ($rating) {
                                        echo nearmenus_get_restaurant_rating(get_the_ID());
                                    }
                                    ?>
                                    
                                    <!-- Cuisine Display -->
                                    <?php
                                    $cuisines = get_the_terms(get_the_ID(), 'cuisine');
                                    if ($cuisines && !is_wp_error($cuisines)) {
                                        echo '<div class="restaurant-cuisine">';
                                        foreach ($cuisines as $cuisine) {
                                            echo '<span class="cuisine-badge">' . esc_html($cuisine->name) . '</span>';
                                        }
                                        echo '</div>';
                                    }
                                    ?>
                                </div>
                                
                                <!-- Status Badges -->
                                <div class="restaurant-status">
                                    <?php
                                    $is_open = get_post_meta(get_the_ID(), '_restaurant_is_open', true);
                                    $has_delivery = get_post_meta(get_the_ID(), '_restaurant_has_delivery', true);
                                    
                                    if ($is_open) {
                                        echo '<span class="status-badge open">';
                                        echo '<span class="status-indicator"></span>';
                                        echo __('Open Now', 'nearmenus');
                                        echo '</span>';
                                    } else {
                                        echo '<span class="status-badge closed">';
                                        echo '<span class="status-indicator"></span>';
                                        echo __('Closed', 'nearmenus');
                                        echo '</span>';
                                    }
                                    
                                    if ($has_delivery) {
                                        echo '<span class="status-badge delivery">';
                                        echo '<span class="status-indicator"></span>';
                                        echo __('Delivery Available', 'nearmenus');
                                        echo '</span>';
                                    }
                                    ?>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php else : ?>
                    <div class="restaurant-header-no-image">
                        <div class="container">
                            <div class="restaurant-basic-info">
                                <h1><?php the_title(); ?></h1>
                                <!-- Same meta info as above -->
                            </div>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
        </section>
        
        <!-- Restaurant Content -->
        <section class="restaurant-content">
            <div class="container">
                <div class="restaurant-layout">
                    <!-- Main Content -->
                    <div class="restaurant-main">
                        <!-- Special Offer -->
                        <?php
                        $special_offer = get_post_meta(get_the_ID(), '_restaurant_special_offer', true);
                        if ($special_offer) {
                            echo '<div class="special-offer">';
                            echo '<h4>' . __('Special Offer', 'nearmenus') . '</h4>';
                            echo '<p>' . esc_html($special_offer) . '</p>';
                            echo '</div>';
                        }
                        ?>
                        
                        <!-- Restaurant Description -->
                        <div class="restaurant-description">
                            <h2><?php _e('About This Restaurant', 'nearmenus'); ?></h2>
                            <div class="content">
                                <?php the_content(); ?>
                            </div>
                        </div>
                        
                        <!-- Restaurant Menu -->
                        <?php
                        $menu_data = get_post_meta(get_the_ID(), '_restaurant_menu', true);
                        if ($menu_data) {
                            echo '<div class="section">';
                            echo '<h3>' . __('Menu', 'nearmenus') . '</h3>';
                            echo nearmenus_get_restaurant_menu(get_the_ID());
                            echo '</div>';
                        }
                        ?>
                        
                        <!-- Additional Sections -->
                        <div class="restaurant-sections">
                            <div class="section">
                                <h3><?php _e('Hours & Contact', 'nearmenus'); ?></h3>
                                <?php echo nearmenus_get_restaurant_hours(get_the_ID()); ?>
                                <?php echo nearmenus_get_contact_info(get_the_ID()); ?>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Sidebar -->
                    <aside class="restaurant-sidebar">
                        <?php if (is_active_sidebar('restaurant-sidebar')) : ?>
                            <?php dynamic_sidebar('restaurant-sidebar'); ?>
                        <?php else : ?>
                            <!-- Default widgets when no widgets are assigned -->
                            <?php
                            // Contact Widget
                            the_widget('NearMenus_Contact_Widget', array(
                                'title' => __('Contact Information', 'nearmenus'),
                                'restaurant_id' => get_the_ID(),
                                'show_phone' => true,
                                'show_email' => true,
                                'show_website' => true,
                                'show_address' => true,
                            ));
                            
                            // Features Widget
                            the_widget('NearMenus_Features_Widget', array(
                                'title' => __('Features & Amenities', 'nearmenus'),
                                'restaurant_id' => get_the_ID(),
                                'layout' => 'list',
                                'show_icons' => true,
                            ));
                            
                            // Hours Widget
                            the_widget('NearMenus_Hours_Widget', array(
                                'title' => __('Operating Hours', 'nearmenus'),
                                'restaurant_id' => get_the_ID(),
                                'show_status' => true,
                                'compact_mode' => false,
                            ));
                            ?>
                        <?php endif; ?>
                    </aside>
                </div>
            </div>
        </section>
        
    <?php endwhile; ?>
</main>

<?php get_footer(); ?>
```

### 3. Enhanced Helper Functions

Update the helper functions in `functions.php` to match the new design:

```php
/**
 * Enhanced restaurant rating display with new styling
 */
function nearmenus_get_restaurant_rating($post_id = null) {
    if (!$post_id) {
        $post_id = get_the_ID();
    }
    
    $rating = get_post_meta($post_id, '_restaurant_rating', true);
    $reviews_count = get_post_meta($post_id, '_restaurant_reviews_count', true);
    
    if (!$rating) {
        return '';
    }
    
    $output = '<div class="restaurant-rating">';
    $output .= '<div class="stars">';
    
    // Display stars with proper filled/empty states
    $full_stars = floor($rating);
    $half_star = ($rating - $full_stars) >= 0.5;
    $empty_stars = 5 - $full_stars - ($half_star ? 1 : 0);
    
    // Full stars
    for ($i = 0; $i < $full_stars; $i++) {
        $output .= '<span class="star filled">★</span>';
    }
    
    // Half star
    if ($half_star) {
        $output .= '<span class="star half">☆</span>';
    }
    
    // Empty stars
    for ($i = 0; $i < $empty_stars; $i++) {
        $output .= '<span class="star empty">☆</span>';
    }
    
    $output .= '</div>';
    $output .= '<span class="rating-value">' . esc_html(number_format($rating, 1)) . '</span>';
    
    if ($reviews_count) {
        $output .= '<span class="reviews-count">(' . esc_html($reviews_count) . ' reviews)</span>';
    }
    
    $output .= '</div>';
    
    return $output;
}

/**
 * Enhanced restaurant menu display
 */
function nearmenus_get_restaurant_menu($post_id = null) {
    if (!$post_id) {
        $post_id = get_the_ID();
    }
    
    $menu_data = get_post_meta($post_id, '_restaurant_menu', true);
    
    if (!$menu_data || !is_array($menu_data)) {
        return '<p>' . __('Menu information not available.', 'nearmenus') . '</p>';
    }
    
    $output = '<div class="restaurant-menu">';
    
    foreach ($menu_data as $category => $items) {
        if (empty($items) || !is_array($items)) continue;
        
        $output .= '<div class="menu-category">';
        $output .= '<h3 class="category-title">' . esc_html(ucwords(str_replace('-', ' ', $category))) . '</h3>';
        $output .= '<div class="menu-items">';
        
        foreach ($items as $item) {
            if (!is_array($item)) continue;
            
            $is_popular = isset($item['popular']) && $item['popular'];
            $output .= '<div class="menu-item' . ($is_popular ? ' popular' : '') . '">';
            
            $output .= '<div class="item-header">';
            if (isset($item['name'])) {
                $output .= '<h4 class="item-name">' . esc_html($item['name']) . '</h4>';
            }
            if (isset($item['price']) && is_numeric($item['price'])) {
                $output .= '<span class="item-price">$' . number_format((float)$item['price'], 2) . '</span>';
            }
            $output .= '</div>';
            
            if (isset($item['description'])) {
                $output .= '<p class="item-description">' . esc_html($item['description']) . '</p>';
            }
            
            $output .= '</div>';
        }
        
        $output .= '</div></div>';
    }
    
    $output .= '</div>';
    
    return $output;
}
```

### 4. CSS Integration in Functions.php

Add this to your `functions.php` to enqueue the single restaurant CSS:

```php
/**
 * Enqueue single restaurant specific styles
 */
function nearmenus_enqueue_single_restaurant_styles() {
    if (is_single()) {
        wp_enqueue_style(
            'nearmenus-single-restaurant',
            get_template_directory_uri() . '/assets/css/single-restaurant.css',
            array('nearmenus-style'),
            NEARMENUS_VERSION
        );
    }
}
add_action('wp_enqueue_scripts', 'nearmenus_enqueue_single_restaurant_styles');
```

## Implementation Instructions

### Step-by-Step Setup:

1. **Create CSS Directory Structure**:
   ```
   assets/
   └── css/
       └── single-restaurant.css
   ```

2. **Add CSS File**: Create the `single-restaurant.css` file with all the styles above

3. **Update Templates**: Modify `single.php` with the enhanced HTML structure

4. **Update Functions**: Add the enhanced helper functions and CSS enqueue

5. **Test Responsiveness**: Check on different screen sizes

6. **Test Accessibility**: Verify keyboard navigation and screen reader compatibility

### Testing Checklist:

- [ ] Hero section displays with overlay and content properly
- [ ] Rating stars display correctly (filled/half/empty)
- [ ] Status badges show open/closed and delivery status
- [ ] Menu displays in organized categories with pricing
- [ ] Sidebar widgets display with proper styling
- [ ] Contact information is accessible and clickable
- [ ] Operating hours show current day highlighted
- [ ] Special offers display with animation
- [ ] Responsive design works on mobile devices
- [ ] Print styles work for menu printing
- [ ] Dark mode styles apply correctly
- [ ] High contrast mode improves visibility
- [ ] Focus states are visible for accessibility
- [ ] Reduced motion preferences are respected

### Browser Compatibility:

- ✅ **Chrome 90+** - Full support
- ✅ **Firefox 88+** - Full support  
- ✅ **Safari 14+** - Full support
- ✅ **Edge 90+** - Full support
- ⚠️ **IE 11** - Basic support (no grid, limited animations)

### Performance Considerations:

- CSS is only loaded on single restaurant pages
- Uses modern CSS Grid and Flexbox for efficient layouts
- Optimized image sizing with object-fit
- Smooth animations with hardware acceleration
- Proper lazy loading support for images

This comprehensive CSS implementation creates a modern, accessible, and responsive single restaurant page design that matches the functionality and visual appeal of the React NearMenus application while maintaining WordPress best practices.