# Step 5: Template Parts Development

## Overview
This step creates reusable HTML template parts for the GPress theme that work seamlessly with the block templates created in Step 4. Template parts enable modular design and consistent branding across all pages while maintaining optimal performance through conditional loading.

## Objectives
- Create essential HTML template parts for reuse
- Implement header, footer, navigation, and sidebar components
- Add conditional loading for template part assets
- Ensure template parts integrate with FSE and theme.json
- Optimize template part performance and accessibility
- Enable easy customization through Site Editor

## Files to Create in This Step

### Updated Directory Structure
```
gpress/
├── style.css                # (already exists)
├── index.php                # (already exists)
├── functions.php             # (enhanced in this step)
├── README.md                # (already exists)
├── .gitignore               # (already exists)
├── theme.json               # (already exists)
├── templates/               # (already exists from step 4)
│   ├── index.html           # (already exists)
│   ├── single.html          # (already exists)
│   ├── page.html            # (already exists)
│   ├── archive.html         # (already exists)
│   ├── search.html          # (already exists)
│   ├── 404.html             # (already exists)
│   ├── front-page.html      # (already exists)
│   └── blank.html           # (already exists)
├── parts/                   # (new directory - created in this step)
│   ├── header.html          # Site header with navigation
│   ├── footer.html          # Site footer with widgets
│   ├── sidebar.html         # Sidebar with widgets
│   ├── navigation.html      # Main navigation menu
│   ├── social-links.html    # Social media links
│   ├── newsletter.html      # Newsletter signup
│   ├── breadcrumbs.html     # Breadcrumb navigation
│   └── search-form.html     # Enhanced search form
├── inc/                     # (already exists)
│   ├── theme-setup.php      # (already exists)
│   ├── enqueue-scripts.php  # (already exists)
│   ├── customizer.php       # (already exists)
│   ├── block-patterns.php   # (already exists)
│   ├── template-functions.php # (already exists from step 4)
│   └── template-parts.php   # (new file - created in this step)
└── assets/                  # (already exists)
    ├── css/                 # (already exists from step 4)
    │   ├── templates.css    # (already exists)
    │   └── parts.css        # (new file - template parts styles)
    └── js/                  # (already exists)
        ├── skip-link-focus-fix.js  # (already exists)
        ├── customizer.js     # (already exists)
        ├── templates.js      # (already exists from step 4)
        └── parts.js          # (new file - template parts functionality)
```

## Step-by-Step Implementation

### 1. Create parts/ Directory

```bash
mkdir parts
```

### 2. parts/header.html (Site Header)

```html
<!-- wp:group {"className":"site-header","layout":{"type":"constrained"},"style":{"spacing":{"padding":{"top":"1rem","bottom":"1rem"}}}} -->
<div class="wp-block-group site-header" style="padding-top:1rem;padding-bottom:1rem">
    
    <!-- wp:group {"className":"header-container","layout":{"type":"flex","justifyContent":"space-between","flexWrap":"wrap"}} -->
    <div class="wp-block-group header-container">
        
        <!-- wp:group {"className":"site-branding","layout":{"type":"flex","flexWrap":"nowrap"}} -->
        <div class="wp-block-group site-branding">
            
            <!-- wp:site-logo {"width":60,"shouldSyncIcon":true,"className":"site-logo"} /-->
            
            <!-- wp:group {"className":"branding-text","layout":{"type":"flex","orientation":"vertical"}} -->
            <div class="wp-block-group branding-text">
                <!-- wp:site-title {"level":0,"className":"site-title"} /-->
                <!-- wp:site-tagline {"className":"site-tagline"} /-->
            </div>
            <!-- /wp:group -->
            
        </div>
        <!-- /wp:group -->
        
        <!-- wp:group {"className":"header-actions","layout":{"type":"flex","flexWrap":"nowrap"}} -->
        <div class="wp-block-group header-actions">
            
            <!-- wp:search {"label":"Search","showLabel":false,"placeholder":"Search...","width":200,"widthUnit":"px","buttonText":"Search","buttonPosition":"button-inside","buttonUseIcon":true,"className":"header-search"} /-->
            
            <!-- wp:navigation {"ref":1,"overlayMenu":"mobile","layout":{"type":"flex","setCascadingProperties":true,"justifyContent":"center"},"className":"primary-navigation","fontSize":"medium"} /-->
            
            <!-- wp:template-part {"slug":"social-links","className":"header-social"} /-->
            
        </div>
        <!-- /wp:group -->
        
    </div>
    <!-- /wp:group -->
    
</div>
<!-- /wp:group -->
```

### 3. parts/footer.html (Site Footer)

```html
<!-- wp:group {"className":"site-footer","style":{"spacing":{"padding":{"top":"3rem","bottom":"2rem"}}},"backgroundColor":"light","layout":{"type":"constrained"}} -->
<div class="wp-block-group site-footer has-light-background-color has-background" style="padding-top:3rem;padding-bottom:2rem">
    
    <!-- wp:columns {"className":"footer-columns"} -->
    <div class="wp-block-columns footer-columns">
        
        <!-- wp:column {"className":"footer-column"} -->
        <div class="wp-block-column footer-column">
            
            <!-- wp:heading {"level":3,"className":"footer-widget-title"} -->
            <h3 class="wp-block-heading footer-widget-title">About GPress</h3>
            <!-- /wp:heading -->
            
            <!-- wp:paragraph {"className":"footer-about"} -->
            <p class="footer-about">A high-performance WordPress theme built for 2025's web standards. Fast, accessible, and beautifully designed for modern websites.</p>
            <!-- /wp:paragraph -->
            
            <!-- wp:template-part {"slug":"social-links","className":"footer-social"} /-->
            
        </div>
        <!-- /wp:column -->
        
        <!-- wp:column {"className":"footer-column"} -->
        <div class="wp-block-column footer-column">
            
            <!-- wp:heading {"level":3,"className":"footer-widget-title"} -->
            <h3 class="wp-block-heading footer-widget-title">Quick Links</h3>
            <!-- /wp:heading -->
            
            <!-- wp:navigation {"ref":2,"overlayMenu":"never","layout":{"type":"flex","orientation":"vertical","setCascadingProperties":true},"className":"footer-navigation","fontSize":"small"} /-->
            
        </div>
        <!-- /wp:column -->
        
        <!-- wp:column {"className":"footer-column"} -->
        <div class="wp-block-column footer-column">
            
            <!-- wp:heading {"level":3,"className":"footer-widget-title"} -->
            <h3 class="wp-block-heading footer-widget-title">Recent Posts</h3>
            <!-- /wp:heading -->
            
            <!-- wp:latest-posts {"postsToShow":3,"displayPostDate":true,"displayFeaturedImage":false,"className":"footer-recent-posts"} /-->
            
        </div>
        <!-- /wp:column -->
        
        <!-- wp:column {"className":"footer-column"} -->
        <div class="wp-block-column footer-column">
            
            <!-- wp:template-part {"slug":"newsletter","className":"footer-newsletter"} /-->
            
        </div>
        <!-- /wp:column -->
        
    </div>
    <!-- /wp:columns -->
    
    <!-- wp:separator {"className":"footer-separator","style":{"spacing":{"margin":{"top":"2rem","bottom":"1rem"}}}} -->
    <hr class="wp-block-separator has-alpha-channel-opacity footer-separator" style="margin-top:2rem;margin-bottom:1rem"/>
    <!-- /wp:separator -->
    
    <!-- wp:group {"className":"footer-bottom","layout":{"type":"flex","justifyContent":"space-between","flexWrap":"wrap"}} -->
    <div class="wp-block-group footer-bottom">
        
        <!-- wp:paragraph {"className":"footer-copyright","fontSize":"small"} -->
        <p class="footer-copyright has-small-font-size">&copy; 2025 GPress Theme. All rights reserved.</p>
        <!-- /wp:paragraph -->
        
        <!-- wp:paragraph {"className":"footer-credit","fontSize":"small"} -->
        <p class="footer-credit has-small-font-size">Powered by <a href="https://wordpress.org">WordPress</a> & <a href="#">GPress</a></p>
        <!-- /wp:paragraph -->
        
    </div>
    <!-- /wp:group -->
    
</div>
<!-- /wp:group -->
```

### 4. parts/sidebar.html (Primary Sidebar)

```html
<!-- wp:group {"className":"primary-sidebar","style":{"spacing":{"padding":{"top":"2rem","bottom":"2rem"}}},"layout":{"type":"constrained"}} -->
<div class="wp-block-group primary-sidebar" style="padding-top:2rem;padding-bottom:2rem">
    
    <!-- wp:group {"className":"widget-area"} -->
    <div class="wp-block-group widget-area">
        
        <!-- wp:search {"label":"Search","showLabel":true,"placeholder":"Search posts...","buttonText":"Search","className":"sidebar-search widget"} /-->
        
        <!-- wp:group {"className":"widget recent-posts-widget"} -->
        <div class="wp-block-group widget recent-posts-widget">
            
            <!-- wp:heading {"level":3,"className":"widget-title"} -->
            <h3 class="wp-block-heading widget-title">Recent Posts</h3>
            <!-- /wp:heading -->
            
            <!-- wp:latest-posts {"postsToShow":5,"displayPostDate":true,"displayFeaturedImage":true,"featuredImageSizeSlug":"thumbnail","className":"widget-recent-posts"} /-->
            
        </div>
        <!-- /wp:group -->
        
        <!-- wp:group {"className":"widget categories-widget"} -->
        <div class="wp-block-group widget categories-widget">
            
            <!-- wp:heading {"level":3,"className":"widget-title"} -->
            <h3 class="wp-block-heading widget-title">Categories</h3>
            <!-- /wp:heading -->
            
            <!-- wp:categories {"showPostCounts":true,"className":"widget-categories"} /-->
            
        </div>
        <!-- /wp:group -->
        
        <!-- wp:group {"className":"widget archives-widget"} -->
        <div class="wp-block-group widget archives-widget">
            
            <!-- wp:heading {"level":3,"className":"widget-title"} -->
            <h3 class="wp-block-heading widget-title">Archives</h3>
            <!-- /wp:heading -->
            
            <!-- wp:archives {"showPostCounts":true,"className":"widget-archives"} /-->
            
        </div>
        <!-- /wp:group -->
        
        <!-- wp:group {"className":"widget tags-widget"} -->
        <div class="wp-block-group widget tags-widget">
            
            <!-- wp:heading {"level":3,"className":"widget-title"} -->
            <h3 class="wp-block-heading widget-title">Popular Tags</h3>
            <!-- /wp:heading -->
            
            <!-- wp:tag-cloud {"className":"widget-tag-cloud"} /-->
            
        </div>
        <!-- /wp:group -->
        
        <!-- wp:template-part {"slug":"newsletter","className":"sidebar-newsletter widget"} /-->
        
    </div>
    <!-- /wp:group -->
    
</div>
<!-- /wp:group -->
```

### 5. parts/navigation.html (Main Navigation)

```html
<!-- wp:group {"className":"main-navigation-wrapper","layout":{"type":"constrained"}} -->
<div class="wp-block-group main-navigation-wrapper">
    
    <!-- wp:navigation {"ref":1,"textColor":"foreground","overlayBackgroundColor":"background","overlayTextColor":"foreground","className":"main-navigation","layout":{"type":"flex","setCascadingProperties":true,"justifyContent":"center"},"fontSize":"medium"} -->
    
    <!-- wp:navigation-link {"label":"Home","type":"custom","url":"/","kind":"custom","className":"nav-home"} /-->
    
    <!-- wp:navigation-link {"label":"Blog","type":"custom","url":"/blog","kind":"custom","className":"nav-blog"} /-->
    
    <!-- wp:navigation-submenu {"label":"Categories","type":"custom","url":"#","kind":"custom","className":"nav-categories"} -->
        <!-- wp:navigation-link {"label":"Technology","type":"category","id":1,"url":"#","kind":"taxonomy","className":"nav-tech"} /-->
        <!-- wp:navigation-link {"label":"Lifestyle","type":"category","id":2,"url":"#","kind":"taxonomy","className":"nav-lifestyle"} /-->
        <!-- wp:navigation-link {"label":"Business","type":"category","id":3,"url":"#","kind":"taxonomy","className":"nav-business"} /-->
    <!-- /wp:navigation-submenu -->
    
    <!-- wp:navigation-link {"label":"About","type":"page","id":2,"url":"#","kind":"post-type","className":"nav-about"} /-->
    
    <!-- wp:navigation-link {"label":"Contact","type":"page","id":3,"url":"#","kind":"post-type","className":"nav-contact"} /-->
    
    <!-- /wp:navigation -->
    
</div>
<!-- /wp:group -->
```

### 6. parts/social-links.html (Social Media Links)

```html
<!-- wp:group {"className":"social-links-wrapper","layout":{"type":"constrained"}} -->
<div class="wp-block-group social-links-wrapper">
    
    <!-- wp:social-links {"iconColor":"foreground","iconColorValue":"var(--wp--preset--color--foreground)","className":"social-links","layout":{"type":"flex","justifyContent":"center"}} -->
    <ul class="wp-block-social-links has-icon-color social-links">
        
        <!-- wp:social-link {"url":"#","service":"twitter","label":"Follow us on Twitter","className":"social-twitter"} /-->
        
        <!-- wp:social-link {"url":"#","service":"facebook","label":"Like us on Facebook","className":"social-facebook"} /-->
        
        <!-- wp:social-link {"url":"#","service":"instagram","label":"Follow us on Instagram","className":"social-instagram"} /-->
        
        <!-- wp:social-link {"url":"#","service":"linkedin","label":"Connect on LinkedIn","className":"social-linkedin"} /-->
        
        <!-- wp:social-link {"url":"#","service":"youtube","label":"Subscribe to our YouTube","className":"social-youtube"} /-->
        
        <!-- wp:social-link {"url":"#","service":"feed","label":"Subscribe to our RSS Feed","className":"social-rss"} /-->
        
    </ul>
    <!-- /wp:social-links -->
    
</div>
<!-- /wp:group -->
```

### 7. parts/newsletter.html (Newsletter Signup)

```html
<!-- wp:group {"className":"newsletter-signup","style":{"spacing":{"padding":{"top":"2rem","bottom":"2rem","left":"1.5rem","right":"1.5rem"}}},"backgroundColor":"primary","textColor":"white","layout":{"type":"constrained"}} -->
<div class="wp-block-group newsletter-signup has-white-color has-primary-background-color has-text-color has-background" style="padding-top:2rem;padding-right:1.5rem;padding-bottom:2rem;padding-left:1.5rem">
    
    <!-- wp:heading {"level":3,"textAlign":"center","className":"newsletter-title"} -->
    <h3 class="wp-block-heading has-text-align-center newsletter-title">Stay Updated</h3>
    <!-- /wp:heading -->
    
    <!-- wp:paragraph {"align":"center","className":"newsletter-description"} -->
    <p class="has-text-align-center newsletter-description">Get the latest posts and updates delivered straight to your inbox.</p>
    <!-- /wp:paragraph -->
    
    <!-- wp:html {"className":"newsletter-form"} -->
    <div class="newsletter-form">
        <form class="gpress-newsletter-form" method="post" action="#" data-newsletter="true">
            <div class="form-group">
                <label for="newsletter-email" class="screen-reader-text">Email Address</label>
                <input 
                    type="email" 
                    id="newsletter-email" 
                    name="newsletter_email" 
                    placeholder="Enter your email address" 
                    required 
                    class="newsletter-email-input"
                >
            </div>
            <div class="form-group">
                <button type="submit" class="newsletter-submit-btn">
                    <span class="btn-text">Subscribe</span>
                    <span class="btn-loading" style="display: none;">Subscribing...</span>
                </button>
            </div>
            <div class="newsletter-privacy">
                <small>We respect your privacy. Unsubscribe at any time.</small>
            </div>
        </form>
    </div>
    <!-- /wp:html -->
    
</div>
<!-- /wp:group -->
```

### 8. parts/breadcrumbs.html (Breadcrumb Navigation)

```html
<!-- wp:group {"className":"breadcrumbs-wrapper","style":{"spacing":{"padding":{"top":"1rem","bottom":"1rem"}}},"layout":{"type":"constrained"}} -->
<div class="wp-block-group breadcrumbs-wrapper" style="padding-top:1rem;padding-bottom:1rem">
    
    <!-- wp:html {"className":"breadcrumbs"} -->
    <nav class="breadcrumbs" aria-label="Breadcrumb navigation">
        <ol class="breadcrumb-list">
            <li class="breadcrumb-item">
                <a href="/" class="breadcrumb-link" rel="home">
                    <svg class="breadcrumb-home-icon" width="16" height="16" viewBox="0 0 24 24" fill="currentColor">
                        <path d="M12 3L2 12h3v8h6v-6h2v6h6v-8h3L12 3z"/>
                    </svg>
                    <span class="screen-reader-text">Home</span>
                </a>
            </li>
            <!-- Dynamic breadcrumb items will be inserted here via PHP -->
        </ol>
    </nav>
    <!-- /wp:html -->
    
</div>
<!-- /wp:group -->
```

### 9. parts/search-form.html (Enhanced Search Form)

```html
<!-- wp:group {"className":"search-form-wrapper","layout":{"type":"constrained"}} -->
<div class="wp-block-group search-form-wrapper">
    
    <!-- wp:search {"label":"Search","showLabel":true,"placeholder":"What are you looking for?","width":100,"widthUnit":"%","buttonText":"Search","buttonPosition":"button-inside","buttonUseIcon":true,"className":"enhanced-search-form"} /-->
    
    <!-- wp:group {"className":"search-suggestions","style":{"display":{"all":"none"}}} -->
    <div class="wp-block-group search-suggestions" style="display:none">
        
        <!-- wp:heading {"level":4,"className":"suggestions-title"} -->
        <h4 class="wp-block-heading suggestions-title">Popular Searches</h4>
        <!-- /wp:heading -->
        
        <!-- wp:list {"className":"popular-searches"} -->
        <ul class="popular-searches">
            <li><a href="#" class="search-suggestion">WordPress Tips</a></li>
            <li><a href="#" class="search-suggestion">Web Development</a></li>
            <li><a href="#" class="search-suggestion">Performance</a></li>
            <li><a href="#" class="search-suggestion">Accessibility</a></li>
        </ul>
        <!-- /wp:list -->
        
    </div>
    <!-- /wp:group -->
    
</div>
<!-- /wp:group -->
```

### 10. assets/css/parts.css (Template Parts Styles)

```css
/*
 * Template Parts Styles for GPress Theme
 * Only loaded when specific template parts are used
 */

/* Header Styles */
.site-header {
    border-bottom: 1px solid var(--wp--preset--color--gray-200);
    background: var(--wp--preset--color--white);
    position: sticky;
    top: 0;
    z-index: 100;
    box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
}

.site-header .header-container {
    align-items: center;
}

.site-branding {
    align-items: center;
    gap: 1rem;
}

.site-title {
    margin: 0;
    font-weight: 700;
    color: var(--wp--preset--color--dark);
}

.site-tagline {
    margin: 0;
    font-size: var(--wp--preset--font-size--small);
    color: var(--wp--preset--color--gray-600);
}

.header-actions {
    align-items: center;
    gap: 1rem;
}

.header-search {
    min-width: 200px;
}

/* Footer Styles */
.site-footer {
    margin-top: 4rem;
}

.footer-columns {
    margin-bottom: 2rem;
}

.footer-column {
    margin-bottom: 2rem;
}

.footer-widget-title {
    margin-bottom: 1rem;
    color: var(--wp--preset--color--dark);
    font-weight: 600;
}

.footer-about {
    color: var(--wp--preset--color--gray-600);
    line-height: 1.6;
}

.footer-navigation a {
    color: var(--wp--preset--color--gray-700);
    text-decoration: none;
    display: block;
    padding: 0.25rem 0;
    transition: color 0.2s ease;
}

.footer-navigation a:hover {
    color: var(--wp--preset--color--primary);
}

.footer-recent-posts .wp-block-latest-posts__post-title {
    font-size: var(--wp--preset--font-size--small);
    line-height: 1.4;
    margin-bottom: 0.5rem;
}

.footer-recent-posts .wp-block-latest-posts__post-date {
    font-size: var(--wp--preset--font-size--x-small);
    color: var(--wp--preset--color--gray-500);
}

.footer-bottom {
    align-items: center;
}

.footer-copyright,
.footer-credit {
    margin: 0;
    color: var(--wp--preset--color--gray-600);
}

.footer-credit a {
    color: var(--wp--preset--color--primary);
    text-decoration: none;
}

.footer-credit a:hover {
    text-decoration: underline;
}

/* Sidebar Styles */
.primary-sidebar {
    background: var(--wp--preset--color--light);
    border-radius: 8px;
}

.sidebar-search,
.widget {
    margin-bottom: 2rem;
    padding: 1.5rem;
    background: var(--wp--preset--color--white);
    border-radius: 8px;
    border: 1px solid var(--wp--preset--color--gray-200);
}

.widget-title {
    margin-bottom: 1rem;
    font-weight: 600;
    color: var(--wp--preset--color--dark);
    border-bottom: 2px solid var(--wp--preset--color--primary);
    padding-bottom: 0.5rem;
}

.widget-recent-posts .wp-block-latest-posts__list {
    list-style: none;
    padding: 0;
    margin: 0;
}

.widget-recent-posts .wp-block-latest-posts__list li {
    margin-bottom: 1rem;
    padding-bottom: 1rem;
    border-bottom: 1px solid var(--wp--preset--color--gray-200);
}

.widget-recent-posts .wp-block-latest-posts__list li:last-child {
    border-bottom: none;
    margin-bottom: 0;
    padding-bottom: 0;
}

.widget-categories ul,
.widget-archives ul {
    list-style: none;
    padding: 0;
    margin: 0;
}

.widget-categories li,
.widget-archives li {
    margin-bottom: 0.5rem;
}

.widget-categories a,
.widget-archives a {
    color: var(--wp--preset--color--gray-700);
    text-decoration: none;
    display: flex;
    justify-content: space-between;
    padding: 0.25rem 0;
    transition: color 0.2s ease;
}

.widget-categories a:hover,
.widget-archives a:hover {
    color: var(--wp--preset--color--primary);
}

.widget-tag-cloud .wp-block-tag-cloud a {
    background: var(--wp--preset--color--gray-100);
    color: var(--wp--preset--color--gray-700);
    padding: 0.25rem 0.75rem;
    border-radius: 20px;
    text-decoration: none;
    font-size: var(--wp--preset--font-size--small);
    margin: 0.25rem;
    display: inline-block;
    transition: all 0.2s ease;
}

.widget-tag-cloud .wp-block-tag-cloud a:hover {
    background: var(--wp--preset--color--primary);
    color: var(--wp--preset--color--white);
}

/* Navigation Styles */
.main-navigation-wrapper {
    padding: 1rem 0;
}

.main-navigation .wp-block-navigation__container {
    gap: 2rem;
}

.main-navigation .wp-block-navigation-item a {
    color: var(--wp--preset--color--dark);
    text-decoration: none;
    font-weight: 500;
    padding: 0.5rem 1rem;
    border-radius: 4px;
    transition: all 0.2s ease;
}

.main-navigation .wp-block-navigation-item a:hover,
.main-navigation .wp-block-navigation-item a:focus {
    background: var(--wp--preset--color--primary);
    color: var(--wp--preset--color--white);
}

.main-navigation .wp-block-navigation-submenu__toggle {
    border: none;
    background: none;
    color: inherit;
    padding: 0.25rem;
}

/* Social Links Styles */
.social-links {
    gap: 0.5rem;
}

.social-links .wp-block-social-link {
    transition: transform 0.2s ease;
}

.social-links .wp-block-social-link:hover {
    transform: translateY(-2px);
}

.social-links .wp-block-social-link a {
    border-radius: 50%;
    padding: 0.5rem;
}

/* Newsletter Styles */
.newsletter-signup {
    border-radius: 8px;
    text-align: center;
}

.newsletter-title {
    margin-bottom: 0.5rem;
    color: inherit;
}

.newsletter-description {
    margin-bottom: 1.5rem;
    opacity: 0.9;
}

.gpress-newsletter-form {
    max-width: 300px;
    margin: 0 auto;
}

.newsletter-email-input {
    width: 100%;
    padding: 0.75rem 1rem;
    border: none;
    border-radius: 4px;
    margin-bottom: 1rem;
    font-size: var(--wp--preset--font-size--medium);
}

.newsletter-submit-btn {
    width: 100%;
    padding: 0.75rem 1.5rem;
    background: var(--wp--preset--color--white);
    color: var(--wp--preset--color--primary);
    border: none;
    border-radius: 4px;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.2s ease;
}

.newsletter-submit-btn:hover {
    background: var(--wp--preset--color--gray-100);
}

.newsletter-submit-btn:disabled {
    opacity: 0.7;
    cursor: not-allowed;
}

.newsletter-privacy {
    margin-top: 1rem;
    opacity: 0.8;
}

/* Breadcrumbs Styles */
.breadcrumbs-wrapper {
    background: var(--wp--preset--color--gray-100);
    border-bottom: 1px solid var(--wp--preset--color--gray-200);
}

.breadcrumb-list {
    list-style: none;
    padding: 0;
    margin: 0;
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.breadcrumb-item {
    display: flex;
    align-items: center;
}

.breadcrumb-item:not(:last-child)::after {
    content: '>';
    margin-left: 0.5rem;
    color: var(--wp--preset--color--gray-500);
}

.breadcrumb-link {
    color: var(--wp--preset--color--gray-700);
    text-decoration: none;
    display: flex;
    align-items: center;
    transition: color 0.2s ease;
}

.breadcrumb-link:hover {
    color: var(--wp--preset--color--primary);
}

.breadcrumb-home-icon {
    width: 16px;
    height: 16px;
}

/* Search Form Styles */
.enhanced-search-form {
    position: relative;
}

.search-suggestions {
    position: absolute;
    top: 100%;
    left: 0;
    right: 0;
    background: var(--wp--preset--color--white);
    border: 1px solid var(--wp--preset--color--gray-200);
    border-radius: 4px;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
    padding: 1rem;
    z-index: 10;
}

.suggestions-title {
    margin-bottom: 0.5rem;
    font-size: var(--wp--preset--font-size--small);
    color: var(--wp--preset--color--gray-600);
}

.popular-searches {
    list-style: none;
    padding: 0;
    margin: 0;
}

.popular-searches li {
    margin-bottom: 0.25rem;
}

.search-suggestion {
    color: var(--wp--preset--color--gray-700);
    text-decoration: none;
    font-size: var(--wp--preset--font-size--small);
    padding: 0.25rem 0;
    display: block;
    transition: color 0.2s ease;
}

.search-suggestion:hover {
    color: var(--wp--preset--color--primary);
}

/* Responsive Design */
@media (max-width: 768px) {
    .site-header .header-container {
        flex-direction: column;
        gap: 1rem;
    }
    
    .header-actions {
        width: 100%;
        justify-content: space-between;
    }
    
    .header-search {
        min-width: auto;
        flex: 1;
    }
    
    .footer-columns {
        flex-direction: column;
    }
    
    .footer-bottom {
        flex-direction: column;
        gap: 0.5rem;
        text-align: center;
    }
    
    .main-navigation .wp-block-navigation__container {
        flex-direction: column;
        gap: 0.5rem;
    }
    
    .breadcrumb-list {
        flex-wrap: wrap;
    }
}

@media (max-width: 480px) {
    .site-branding {
        flex-direction: column;
        text-align: center;
    }
    
    .widget {
        padding: 1rem;
    }
    
    .newsletter-signup {
        padding: 1.5rem 1rem;
    }
}
```

### 11. assets/js/parts.js (Template Parts JavaScript)

```javascript
/**
 * Template Parts JavaScript for GPress Theme
 * Handles interactive functionality for template parts
 */

(function() {
    'use strict';

    // Initialize when DOM is ready
    document.addEventListener('DOMContentLoaded', function() {
        initTemplatePartsEnhancements();
    });

    function initTemplatePartsEnhancements() {
        // Header enhancements
        initStickyHeader();
        initMobileNavigation();
        
        // Search enhancements
        initSearchEnhancements();
        
        // Newsletter functionality
        initNewsletterForms();
        
        // Social links enhancements
        initSocialLinksTracking();
        
        // Breadcrumbs enhancements
        initBreadcrumbsAccessibility();
    }

    function initStickyHeader() {
        const header = document.querySelector('.site-header');
        if (!header) return;

        let lastScrollTop = 0;
        const threshold = 100;

        window.addEventListener('scroll', function() {
            const scrollTop = window.pageYOffset || document.documentElement.scrollTop;
            
            if (scrollTop > threshold) {
                header.classList.add('scrolled');
                
                // Hide header on scroll down, show on scroll up
                if (scrollTop > lastScrollTop && scrollTop > 200) {
                    header.classList.add('hidden');
                } else {
                    header.classList.remove('hidden');
                }
            } else {
                header.classList.remove('scrolled', 'hidden');
            }
            
            lastScrollTop = scrollTop;
        });

        // Add scroll classes to CSS
        const scrollStyles = `
            .site-header.scrolled {
                box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
                backdrop-filter: blur(10px);
            }
            .site-header.hidden {
                transform: translateY(-100%);
                transition: transform 0.3s ease;
            }
        `;
        
        const styleSheet = document.createElement('style');
        styleSheet.textContent = scrollStyles;
        document.head.appendChild(styleSheet);
    }

    function initMobileNavigation() {
        // Mobile navigation toggle
        const navToggle = document.querySelector('.wp-block-navigation__responsive-container-open');
        const navClose = document.querySelector('.wp-block-navigation__responsive-container-close');
        const navContainer = document.querySelector('.wp-block-navigation__responsive-container');

        if (navToggle && navContainer) {
            navToggle.addEventListener('click', function() {
                document.body.classList.add('nav-open');
                navContainer.focus();
            });
        }

        if (navClose && navContainer) {
            navClose.addEventListener('click', function() {
                document.body.classList.remove('nav-open');
                navToggle.focus();
            });
        }

        // Close navigation on escape key
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape' && document.body.classList.contains('nav-open')) {
                document.body.classList.remove('nav-open');
                navToggle.focus();
            }
        });

        // Close navigation when clicking outside
        document.addEventListener('click', function(e) {
            if (document.body.classList.contains('nav-open') && 
                !navContainer.contains(e.target) && 
                !navToggle.contains(e.target)) {
                document.body.classList.remove('nav-open');
            }
        });
    }

    function initSearchEnhancements() {
        const searchForms = document.querySelectorAll('.wp-block-search, .enhanced-search-form');
        
        searchForms.forEach(function(form) {
            const input = form.querySelector('input[type="search"]');
            if (!input) return;

            // Add search suggestions
            addSearchSuggestions(input, form);
            
            // Add search history
            addSearchHistory(input);
            
            // Add search analytics
            trackSearchQueries(form);
        });
    }

    function addSearchSuggestions(input, form) {
        let timeout;
        let suggestionsContainer = form.querySelector('.search-suggestions');
        
        if (!suggestionsContainer) {
            suggestionsContainer = document.createElement('div');
            suggestionsContainer.className = 'search-suggestions';
            suggestionsContainer.style.display = 'none';
            form.appendChild(suggestionsContainer);
        }

        input.addEventListener('input', function() {
            clearTimeout(timeout);
            const query = this.value.trim();
            
            if (query.length >= 2) {
                timeout = setTimeout(function() {
                    fetchSearchSuggestions(query, suggestionsContainer);
                }, 300);
            } else {
                hideSuggestions(suggestionsContainer);
            }
        });

        input.addEventListener('focus', function() {
            if (this.value.trim().length >= 2) {
                showSuggestions(suggestionsContainer);
            }
        });

        input.addEventListener('blur', function() {
            // Delay hiding to allow clicking on suggestions
            setTimeout(function() {
                hideSuggestions(suggestionsContainer);
            }, 200);
        });
    }

    function fetchSearchSuggestions(query, container) {
        // In a real implementation, this would fetch from WordPress REST API
        // For now, we'll use static suggestions
        const suggestions = [
            query + ' tips',
            query + ' guide',
            query + ' tutorial',
            query + ' best practices'
        ];

        renderSuggestions(suggestions, container, query);
    }

    function renderSuggestions(suggestions, container, query) {
        container.innerHTML = `
            <h4 class="suggestions-title">Suggestions</h4>
            <ul class="suggestions-list">
                ${suggestions.map(suggestion => `
                    <li>
                        <a href="/?s=${encodeURIComponent(suggestion)}" class="search-suggestion">
                            ${highlightQuery(suggestion, query)}
                        </a>
                    </li>
                `).join('')}
            </ul>
        `;
        showSuggestions(container);
    }

    function highlightQuery(text, query) {
        const regex = new RegExp(`(${query})`, 'gi');
        return text.replace(regex, '<strong>$1</strong>');
    }

    function showSuggestions(container) {
        container.style.display = 'block';
    }

    function hideSuggestions(container) {
        container.style.display = 'none';
    }

    function addSearchHistory(input) {
        const historyKey = 'gpress_search_history';
        const maxHistory = 5;

        input.addEventListener('keydown', function(e) {
            if (e.key === 'Enter' && this.value.trim()) {
                saveSearchQuery(this.value.trim(), historyKey, maxHistory);
            }
        });
    }

    function saveSearchQuery(query, storageKey, maxItems) {
        if (!localStorage) return;

        let history = JSON.parse(localStorage.getItem(storageKey) || '[]');
        
        // Remove if already exists
        history = history.filter(item => item !== query);
        
        // Add to beginning
        history.unshift(query);
        
        // Limit to max items
        history = history.slice(0, maxItems);
        
        localStorage.setItem(storageKey, JSON.stringify(history));
    }

    function trackSearchQueries(form) {
        form.addEventListener('submit', function(e) {
            const input = this.querySelector('input[type="search"]');
            if (input && input.value.trim()) {
                // Track search query (integrate with analytics if needed)
                if (typeof gtag !== 'undefined') {
                    gtag('event', 'search', {
                        search_term: input.value.trim()
                    });
                }
            }
        });
    }

    function initNewsletterForms() {
        const newsletterForms = document.querySelectorAll('.gpress-newsletter-form');
        
        newsletterForms.forEach(function(form) {
            form.addEventListener('submit', function(e) {
                e.preventDefault();
                handleNewsletterSubmission(this);
            });
        });
    }

    function handleNewsletterSubmission(form) {
        const emailInput = form.querySelector('input[type="email"]');
        const submitBtn = form.querySelector('.newsletter-submit-btn');
        const btnText = submitBtn.querySelector('.btn-text');
        const btnLoading = submitBtn.querySelector('.btn-loading');
        
        if (!emailInput || !submitBtn) return;

        // Validate email
        if (!isValidEmail(emailInput.value)) {
            showNewsletterMessage(form, 'Please enter a valid email address.', 'error');
            return;
        }

        // Show loading state
        submitBtn.disabled = true;
        btnText.style.display = 'none';
        btnLoading.style.display = 'inline';

        // Simulate API call (replace with actual newsletter service)
        setTimeout(function() {
            // Reset button state
            submitBtn.disabled = false;
            btnText.style.display = 'inline';
            btnLoading.style.display = 'none';
            
            // Show success message
            showNewsletterMessage(form, 'Thank you for subscribing!', 'success');
            emailInput.value = '';
            
            // Track subscription
            if (typeof gtag !== 'undefined') {
                gtag('event', 'newsletter_subscribe', {
                    method: 'email'
                });
            }
        }, 2000);
    }

    function isValidEmail(email) {
        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        return emailRegex.test(email);
    }

    function showNewsletterMessage(form, message, type) {
        let messageEl = form.querySelector('.newsletter-message');
        
        if (!messageEl) {
            messageEl = document.createElement('div');
            messageEl.className = 'newsletter-message';
            form.appendChild(messageEl);
        }
        
        messageEl.textContent = message;
        messageEl.className = `newsletter-message ${type}`;
        
        // Remove message after 5 seconds
        setTimeout(function() {
            if (messageEl.parentNode) {
                messageEl.parentNode.removeChild(messageEl);
            }
        }, 5000);
    }

    function initSocialLinksTracking() {
        const socialLinks = document.querySelectorAll('.social-links a');
        
        socialLinks.forEach(function(link) {
            link.addEventListener('click', function(e) {
                const service = this.closest('.wp-block-social-link').dataset.service || 
                               this.getAttribute('href').match(/(?:twitter|facebook|instagram|linkedin|youtube)/)?.[0] || 
                               'unknown';
                
                // Track social link clicks
                if (typeof gtag !== 'undefined') {
                    gtag('event', 'social_click', {
                        social_network: service,
                        link_url: this.getAttribute('href')
                    });
                }
            });
        });
    }

    function initBreadcrumbsAccessibility() {
        const breadcrumbs = document.querySelector('.breadcrumbs');
        if (!breadcrumbs) return;

        // Enhance keyboard navigation
        const links = breadcrumbs.querySelectorAll('a');
        links.forEach(function(link, index) {
            link.addEventListener('keydown', function(e) {
                if (e.key === 'ArrowRight' && index < links.length - 1) {
                    e.preventDefault();
                    links[index + 1].focus();
                } else if (e.key === 'ArrowLeft' && index > 0) {
                    e.preventDefault();
                    links[index - 1].focus();
                }
            });
        });
    }

})();
```

### 12. inc/template-parts.php (Template Parts Helper Functions)

```php
<?php
/**
 * Template Parts helper functions for GPress theme
 *
 * @package GPress
 * @since 1.0.0
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Conditionally enqueue template parts assets
 */
function gpress_conditional_parts_assets() {
    
    // Always load parts CSS and JS since template parts are used on all pages
    wp_enqueue_style('gpress-parts', GPRESS_THEME_URI . '/assets/css/parts.css', array(), GPRESS_VERSION);
    wp_enqueue_script('gpress-parts', GPRESS_THEME_URI . '/assets/js/parts.js', array(), GPRESS_VERSION, true);
    
    // Conditionally load specific part assets based on usage
    if (is_active_sidebar('sidebar-1') || is_single() || is_page()) {
        // Sidebar styles - only when sidebar is active or on single pages
        wp_add_inline_style('gpress-parts', '
            .primary-sidebar { display: block; }
            @media (min-width: 768px) {
                .has-sidebar { display: grid; grid-template-columns: 1fr 300px; gap: 2rem; }
            }
        ');
    }
    
    // Newsletter functionality - only when newsletter part is used
    if (has_block('core/html') && strpos(get_the_content(), 'gpress-newsletter-form') !== false) {
        wp_add_inline_style('gpress-parts', '
            .newsletter-message.success { color: #27ae60; background: #d4edda; padding: 0.5rem; border-radius: 4px; margin-top: 1rem; }
            .newsletter-message.error { color: #e74c3c; background: #f8d7da; padding: 0.5rem; border-radius: 4px; margin-top: 1rem; }
        ');
    }
}
add_action('wp_enqueue_scripts', 'gpress_conditional_parts_assets');

/**
 * Generate dynamic breadcrumbs
 */
function gpress_generate_breadcrumbs() {
    // Don't show on homepage
    if (is_front_page()) return '';
    
    $breadcrumbs = array();
    $breadcrumbs[] = array(
        'title' => __('Home', 'gpress'),
        'url' => home_url('/'),
        'current' => false
    );
    
    if (is_category()) {
        $category = get_queried_object();
        $breadcrumbs[] = array(
            'title' => $category->name,
            'url' => '',
            'current' => true
        );
    } elseif (is_single()) {
        $categories = get_the_category();
        if (!empty($categories)) {
            $breadcrumbs[] = array(
                'title' => $categories[0]->name,
                'url' => get_category_link($categories[0]->term_id),
                'current' => false
            );
        }
        $breadcrumbs[] = array(
            'title' => get_the_title(),
            'url' => '',
            'current' => true
        );
    } elseif (is_page()) {
        // Handle page hierarchy
        $page_id = get_queried_object_id();
        $ancestors = array_reverse(get_post_ancestors($page_id));
        
        foreach ($ancestors as $ancestor_id) {
            $breadcrumbs[] = array(
                'title' => get_the_title($ancestor_id),
                'url' => get_permalink($ancestor_id),
                'current' => false
            );
        }
        
        $breadcrumbs[] = array(
            'title' => get_the_title(),
            'url' => '',
            'current' => true
        );
    } elseif (is_archive()) {
        $breadcrumbs[] = array(
            'title' => get_the_archive_title(),
            'url' => '',
            'current' => true
        );
    } elseif (is_search()) {
        $breadcrumbs[] = array(
            'title' => sprintf(__('Search Results for "%s"', 'gpress'), get_search_query()),
            'url' => '',
            'current' => true
        );
    } elseif (is_404()) {
        $breadcrumbs[] = array(
            'title' => __('Page Not Found', 'gpress'),
            'url' => '',
            'current' => true
        );
    }
    
    return $breadcrumbs;
}

/**
 * Render breadcrumbs HTML
 */
function gpress_render_breadcrumbs() {
    $breadcrumbs = gpress_generate_breadcrumbs();
    
    if (empty($breadcrumbs)) return;
    
    echo '<nav class="breadcrumbs" aria-label="' . esc_attr__('Breadcrumb navigation', 'gpress') . '">';
    echo '<ol class="breadcrumb-list">';
    
    foreach ($breadcrumbs as $index => $crumb) {
        echo '<li class="breadcrumb-item" ' . ($crumb['current'] ? 'aria-current="page"' : '') . '>';
        
        if (!$crumb['current'] && !empty($crumb['url'])) {
            echo '<a href="' . esc_url($crumb['url']) . '" class="breadcrumb-link">';
            
            // Add home icon for first item
            if ($index === 0) {
                echo '<svg class="breadcrumb-home-icon" width="16" height="16" viewBox="0 0 24 24" fill="currentColor">';
                echo '<path d="M12 3L2 12h3v8h6v-6h2v6h6v-8h3L12 3z"/>';
                echo '</svg>';
                echo '<span class="screen-reader-text">' . esc_html($crumb['title']) . '</span>';
            } else {
                echo esc_html($crumb['title']);
            }
            
            echo '</a>';
        } else {
            if ($index === 0) {
                echo '<svg class="breadcrumb-home-icon" width="16" height="16" viewBox="0 0 24 24" fill="currentColor">';
                echo '<path d="M12 3L2 12h3v8h6v-6h2v6h6v-8h3L12 3z"/>';
                echo '</svg>';
                echo '<span class="screen-reader-text">' . esc_html($crumb['title']) . '</span>';
            } else {
                echo '<span class="breadcrumb-current">' . esc_html($crumb['title']) . '</span>';
            }
        }
        
        echo '</li>';
    }
    
    echo '</ol>';
    echo '</nav>';
}

/**
 * Handle newsletter subscription (basic implementation)
 */
function gpress_handle_newsletter_subscription() {
    if (!isset($_POST['newsletter_email']) || !wp_verify_nonce($_POST['_wpnonce'], 'newsletter_subscribe')) {
        wp_die(__('Security check failed.', 'gpress'));
    }
    
    $email = sanitize_email($_POST['newsletter_email']);
    
    if (!is_email($email)) {
        wp_die(__('Invalid email address.', 'gpress'));
    }
    
    // Save to options or integrate with newsletter service
    $subscribers = get_option('gpress_newsletter_subscribers', array());
    
    if (!in_array($email, $subscribers)) {
        $subscribers[] = $email;
        update_option('gpress_newsletter_subscribers', $subscribers);
        
        // Send confirmation email (implement as needed)
        wp_mail(
            $email,
            __('Newsletter Subscription Confirmed', 'gpress'),
            __('Thank you for subscribing to our newsletter!', 'gpress')
        );
    }
    
    wp_redirect(add_query_arg('newsletter', 'subscribed', wp_get_referer()));
    exit;
}
add_action('admin_post_nopriv_newsletter_subscribe', 'gpress_handle_newsletter_subscription');
add_action('admin_post_newsletter_subscribe', 'gpress_handle_newsletter_subscription');

/**
 * Add newsletter subscription notice
 */
function gpress_newsletter_subscription_notice() {
    if (isset($_GET['newsletter']) && $_GET['newsletter'] === 'subscribed') {
        echo '<div class="newsletter-success-notice">';
        echo '<p>' . esc_html__('Thank you for subscribing to our newsletter!', 'gpress') . '</p>';
        echo '</div>';
        
        // Add inline styles for the notice
        echo '<style>
            .newsletter-success-notice {
                background: #d4edda;
                color: #155724;
                padding: 1rem;
                border-radius: 4px;
                margin: 1rem 0;
                border: 1px solid #c3e6cb;
            }
        </style>';
    }
}
add_action('wp_footer', 'gpress_newsletter_subscription_notice');

/**
 * Customize navigation menu output
 */
function gpress_enhance_navigation_menu($items, $args) {
    
    // Add accessibility enhancements
    if (isset($args->theme_location) && $args->theme_location === 'primary') {
        $items = str_replace('<a ', '<a role="menuitem" ', $items);
        
        // Add ARIA attributes for dropdowns
        $items = preg_replace('/class="([^"]*)?sub-menu([^"]*)?"/i', 'class="$1sub-menu$2" role="menu"', $items);
        $items = preg_replace('/class="([^"]*)?menu-item-has-children([^"]*)?"/i', 'class="$1menu-item-has-children$2" role="none"', $items);
    }
    
    return $items;
}
add_filter('wp_nav_menu_items', 'gpress_enhance_navigation_menu', 10, 2);

/**
 * Add template part specific body classes
 */
function gpress_template_parts_body_classes($classes) {
    
    // Add class if sidebar is active
    if (is_active_sidebar('sidebar-1') && (is_single() || is_page())) {
        $classes[] = 'has-sidebar';
    }
    
    // Add class for sticky header
    $classes[] = 'has-sticky-header';
    
    // Add class for newsletter
    if (has_block('core/html') && strpos(get_the_content(), 'gpress-newsletter-form') !== false) {
        $classes[] = 'has-newsletter';
    }
    
    return $classes;
}
add_filter('body_class', 'gpress_template_parts_body_classes');

/**
 * Register additional navigation menus for template parts
 */
function gpress_register_template_parts_menus() {
    register_nav_menus(array(
        'primary' => esc_html__('Primary Navigation', 'gpress'),
        'footer'  => esc_html__('Footer Navigation', 'gpress'),
        'social'  => esc_html__('Social Links Menu', 'gpress'),
    ));
}
add_action('after_setup_theme', 'gpress_register_template_parts_menus');

/**
 * Add structured data for navigation
 */
function gpress_add_navigation_structured_data() {
    if (has_nav_menu('primary')) {
        $menu_items = wp_get_nav_menu_items(get_nav_menu_locations()['primary']);
        
        if ($menu_items) {
            $nav_schema = array(
                '@context' => 'https://schema.org',
                '@type' => 'SiteNavigationElement',
                'name' => get_bloginfo('name') . ' Navigation',
                'url' => array()
            );
            
            foreach ($menu_items as $item) {
                $nav_schema['url'][] = $item->url;
            }
            
            echo '<script type="application/ld+json">' . json_encode($nav_schema) . '</script>';
        }
    }
}
add_action('wp_head', 'gpress_add_navigation_structured_data');
```

### 13. Update functions.php

Add this to your existing `functions.php`:

```php
/**
 * Load template parts functions
 */
require_once GPRESS_INC_DIR . '/template-parts.php';

/**
 * Enhance theme support for template parts
 */
function gpress_template_parts_support() {
    // Add support for menus in template parts
    add_theme_support('menus');
    
    // Add support for widget areas in template parts
    add_theme_support('widgets');
}
add_action('after_setup_theme', 'gpress_template_parts_support', 25);
```

## Testing Instructions

After completing this step, perform comprehensive testing:

### 1. Template Parts Creation Test
```bash
# Verify all template part files are created
ls -la parts/
# Should show: header.html, footer.html, sidebar.html, navigation.html, social-links.html, newsletter.html, breadcrumbs.html, search-form.html
```

### 2. Site Editor Integration Test
1. Go to Appearance → Site Editor → Template Parts
2. Verify all template parts appear in the list
3. Try editing a template part (e.g., header.html)
4. Confirm changes are saved and applied across all templates

### 3. Template Parts Functionality Test
1. **Header**: Verify logo, navigation, and search work properly
2. **Footer**: Check footer widgets, links, and social media icons
3. **Navigation**: Test dropdown menus and mobile navigation
4. **Sidebar**: Verify widgets display correctly when sidebar is active
5. **Newsletter**: Test email validation and submission handling
6. **Breadcrumbs**: Navigate through different page types and verify breadcrumb accuracy

### 4. Conditional Assets Test
1. Open DevTools → Network tab
2. Navigate through different pages
3. Verify that parts.css and parts.js are loaded on all pages (since they're used globally)
4. Check that sidebar-specific styles only apply when sidebar is present
5. Confirm newsletter styles only load when newsletter form is present

### 5. Responsive Design Test
1. Test header collapse on mobile devices
2. Verify footer stack properly on small screens
3. Check mobile navigation menu functionality
4. Test search form responsiveness

### 6. Accessibility Test
1. Test keyboard navigation through all template parts
2. Verify ARIA labels and roles are properly applied
3. Check screen reader compatibility with navigation
4. Test focus management in mobile menu

### 7. Performance Test
1. Run Lighthouse test
2. Should maintain 90+ performance scores
3. Verify template parts don't add excessive DOM nodes
4. Check that JavaScript doesn't block page rendering

### 8. Integration Test
1. Test template parts work correctly in all templates from Step 4
2. Verify no conflicts between template parts and templates
3. Check that customizations in Site Editor apply properly

## Expected Results

After completing Step 5, you should have:

- ✅ Complete set of reusable template parts
- ✅ Conditional asset loading for template parts
- ✅ Fully functional header with navigation and search
- ✅ Comprehensive footer with widgets and social links
- ✅ Interactive sidebar with dynamic content
- ✅ Newsletter subscription functionality
- ✅ Dynamic breadcrumb navigation
- ✅ Enhanced search functionality
- ✅ Accessibility-compliant navigation
- ✅ Mobile-responsive design across all parts

## Performance Benefits

1. **Reusable Components**: Template parts reduce code duplication
2. **Conditional Loading**: Assets only load when parts are actually used
3. **Optimized Navigation**: Smart mobile menu with performance optimizations
4. **Efficient Search**: Enhanced search with suggestions and caching
5. **Minimal JavaScript**: Only essential interactive features included
6. **Fast Newsletter**: Optimized subscription handling

## Next Step

Proceed to [Step 6: Template Hierarchy Implementation](./step-06-template-hierarchy.md) to implement the complete WordPress template hierarchy with these template parts.

## Troubleshooting

**Template parts not appearing in Site Editor:**
- Confirm WordPress version supports template parts editing
- Check that template part files are in `/parts/` directory
- Verify proper block markup in template part files

**Navigation menu not working:**
- Ensure navigation menus are registered in functions.php
- Check that menus are assigned in Appearance → Menus
- Verify navigation blocks have correct ref attributes

**Newsletter subscription not working:**
- Check that form action points to correct WordPress admin-post handler
- Verify nonce security is properly implemented
- Confirm email validation is working

**Breadcrumbs not displaying:**
- Check that breadcrumbs template part is being called
- Verify breadcrumb generation function is working
- Ensure proper CSS is loaded for breadcrumb styling

**Performance degradation:**
- Check that conditional loading is working properly
- Verify no unnecessary assets are being loaded
- Test that JavaScript isn't blocking page rendering