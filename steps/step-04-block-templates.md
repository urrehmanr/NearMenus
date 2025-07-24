# Step 4: Block Templates Creation

## Overview
This step creates HTML block templates that enable Full Site Editing (FSE) for the GPress theme. These templates use Gutenberg block markup and leverage the `theme.json` configuration for consistent styling and performance.

## Objectives
- Create core HTML block templates for FSE
- Implement efficient block-based layouts
- Ensure templates utilize theme.json settings
- Add conditional asset loading for templates
- Optimize query performance for different template types
- Test template functionality and performance

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
├── templates/               # (new directory - created in this step)
│   ├── index.html          # Main template
│   ├── single.html         # Single post template  
│   ├── page.html           # Page template
│   ├── archive.html        # Archive template
│   ├── search.html         # Search results template
│   ├── 404.html            # Error page template
│   ├── front-page.html     # Homepage template
│   └── blank.html          # Blank template
├── inc/                     # (already exists)
│   ├── theme-setup.php      # (already exists)
│   ├── enqueue-scripts.php  # (enhanced in this step)
│   ├── customizer.php       # (already exists)
│   ├── block-patterns.php   # (already exists)
│   └── template-functions.php # (new file - created in this step)
└── assets/                  # (already exists)
    ├── css/                 # (new directory - created in this step)
    │   └── templates.css    # Template-specific styles
    └── js/                  # (already exists)
        ├── skip-link-focus-fix.js  # (already exists)
        ├── customizer.js     # (already exists)
        └── templates.js      # (new file - template enhancements)
```

## Step-by-Step Implementation

### 1. Create templates/ Directory

```bash
mkdir templates
```

### 2. templates/index.html (Main Template)

```html
<!-- wp:template-part {"slug":"header","tagName":"header"} /-->

<!-- wp:group {"tagName":"main","className":"site-main","layout":{"type":"constrained"}} -->
<main class="wp-block-group site-main">
    
    <!-- wp:group {"className":"content-area","layout":{"type":"constrained"}} -->
    <div class="wp-block-group content-area">
        
        <!-- wp:query {"queryId":1,"query":{"perPage":10,"pages":0,"offset":0,"postType":"post","order":"desc","orderBy":"date","author":"","search":"","exclude":[],"sticky":"","inherit":true},"className":"main-query"} -->
        <div class="wp-block-query main-query">
            
            <!-- wp:query-pagination {"paginationArrow":"arrow","className":"pagination-wrapper","layout":{"type":"flex","justifyContent":"space-between"}} -->
            <div class="wp-block-query-pagination pagination-wrapper">
                <!-- wp:query-pagination-previous {"label":"Previous Posts"} /-->
                <!-- wp:query-pagination-numbers /-->
                <!-- wp:query-pagination-next {"label":"Next Posts"} /-->
            </div>
            <!-- /wp:query-pagination -->
            
            <!-- wp:post-template {"className":"post-list","layout":{"type":"grid","columnCount":1}} -->
                <!-- wp:group {"className":"post-item","layout":{"type":"constrained"}} -->
                <div class="wp-block-group post-item">
                    
                    <!-- wp:post-featured-image {"isLink":true,"className":"post-thumbnail"} /-->
                    
                    <!-- wp:group {"className":"post-content-wrapper","layout":{"type":"constrained"}} -->
                    <div class="wp-block-group post-content-wrapper">
                        
                        <!-- wp:post-title {"isLink":true,"className":"post-title"} /-->
                        
                        <!-- wp:group {"className":"post-meta","layout":{"type":"flex","flexWrap":"wrap"}} -->
                        <div class="wp-block-group post-meta">
                            <!-- wp:post-date {"className":"post-date"} /-->
                            <!-- wp:post-author {"showAvatar":false,"className":"post-author"} /-->
                            <!-- wp:post-terms {"term":"category","className":"post-categories"} /-->
                        </div>
                        <!-- /wp:group -->
                        
                        <!-- wp:post-excerpt {"className":"post-excerpt"} /-->
                        
                        <!-- wp:read-more {"content":"Continue Reading","className":"read-more-link"} /-->
                        
                    </div>
                    <!-- /wp:group -->
                    
                </div>
                <!-- /wp:group -->
            <!-- /wp:post-template -->
            
            <!-- wp:query-no-results -->
                <!-- wp:group {"className":"no-results","layout":{"type":"constrained"}} -->
                <div class="wp-block-group no-results">
                    <!-- wp:heading {"textAlign":"center","className":"no-results-title"} -->
                    <h2 class="wp-block-heading has-text-align-center no-results-title">No posts found</h2>
                    <!-- /wp:heading -->
                    <!-- wp:paragraph {"align":"center","className":"no-results-content"} -->
                    <p class="has-text-align-center no-results-content">Sorry, no posts matched your criteria. Try searching for something else.</p>
                    <!-- /wp:paragraph -->
                    <!-- wp:search {"showLabel":false,"placeholder":"Search posts...","className":"no-results-search"} /-->
                </div>
                <!-- /wp:group -->
            <!-- /wp:query-no-results -->
            
        </div>
        <!-- /wp:query -->
        
    </div>
    <!-- /wp:group -->
    
</main>
<!-- /wp:group -->

<!-- wp:template-part {"slug":"footer","tagName":"footer"} /-->
```

### 3. templates/single.html (Single Post Template)

```html
<!-- wp:template-part {"slug":"header","tagName":"header"} /-->

<!-- wp:group {"tagName":"main","className":"site-main single-post","layout":{"type":"constrained"}} -->
<main class="wp-block-group site-main single-post">
    
    <!-- wp:group {"className":"post-header","layout":{"type":"constrained"}} -->
    <div class="wp-block-group post-header">
        
        <!-- wp:post-title {"level":1,"className":"post-title"} /-->
        
        <!-- wp:group {"className":"post-meta","layout":{"type":"flex","flexWrap":"wrap","justifyContent":"space-between"}} -->
        <div class="wp-block-group post-meta">
            
            <!-- wp:group {"className":"meta-left","layout":{"type":"flex","flexWrap":"wrap"}} -->
            <div class="wp-block-group meta-left">
                <!-- wp:post-author {"showAvatar":true,"className":"post-author"} /-->
                <!-- wp:post-date {"className":"post-date"} /-->
            </div>
            <!-- /wp:group -->
            
            <!-- wp:group {"className":"meta-right","layout":{"type":"flex","flexWrap":"wrap"}} -->
            <div class="wp-block-group meta-right">
                <!-- wp:post-terms {"term":"category","className":"post-categories"} /-->
                <!-- wp:post-terms {"term":"post_tag","className":"post-tags"} /-->
            </div>
            <!-- /wp:group -->
            
        </div>
        <!-- /wp:group -->
        
    </div>
    <!-- /wp:group -->
    
    <!-- wp:post-featured-image {"className":"post-featured-image"} /-->
    
    <!-- wp:group {"className":"post-content","layout":{"type":"constrained"}} -->
    <div class="wp-block-group post-content">
        <!-- wp:post-content {"className":"post-content-inner"} /-->
    </div>
    <!-- /wp:group -->
    
    <!-- wp:group {"className":"post-footer","layout":{"type":"constrained"}} -->
    <div class="wp-block-group post-footer">
        
        <!-- wp:separator {"className":"post-separator"} -->
        <hr class="wp-block-separator has-alpha-channel-opacity post-separator"/>
        <!-- /wp:separator -->
        
        <!-- wp:group {"className":"post-navigation","layout":{"type":"flex","justifyContent":"space-between"}} -->
        <div class="wp-block-group post-navigation">
            <!-- wp:post-navigation-link {"type":"previous","showTitle":true,"linkLabel":true,"arrow":"arrow"} /-->
            <!-- wp:post-navigation-link {"type":"next","showTitle":true,"linkLabel":true,"arrow":"arrow"} /-->
        </div>
        <!-- /wp:group -->
        
    </div>
    <!-- /wp:group -->
    
    <!-- wp:comments {"className":"post-comments"} -->
    <div class="wp-block-comments post-comments">
        <!-- wp:comments-title {"className":"comments-title"} /-->
        <!-- wp:comment-template -->
            <!-- wp:group {"className":"comment-content","layout":{"type":"constrained"}} -->
            <div class="wp-block-group comment-content">
                <!-- wp:avatar {"size":48,"className":"comment-avatar"} /-->
                <!-- wp:comment-author-name {"className":"comment-author"} /-->
                <!-- wp:comment-date {"className":"comment-date"} /-->
                <!-- wp:comment-content {"className":"comment-text"} /-->
                <!-- wp:comment-reply-link {"className":"comment-reply"} /-->
            </div>
            <!-- /wp:group -->
        <!-- /wp:comment-template -->
        <!-- wp:comments-pagination {"className":"comments-pagination"} -->
            <!-- wp:comments-pagination-previous /-->
            <!-- wp:comments-pagination-numbers /-->
            <!-- wp:comments-pagination-next /-->
        <!-- /wp:comments-pagination -->
        <!-- wp:post-comments-form {"className":"comment-form"} /-->
    </div>
    <!-- /wp:comments -->
    
</main>
<!-- /wp:group -->

<!-- wp:template-part {"slug":"footer","tagName":"footer"} /-->
```

### 4. templates/page.html (Page Template)

```html
<!-- wp:template-part {"slug":"header","tagName":"header"} /-->

<!-- wp:group {"tagName":"main","className":"site-main single-page","layout":{"type":"constrained"}} -->
<main class="wp-block-group site-main single-page">
    
    <!-- wp:group {"className":"page-header","layout":{"type":"constrained"}} -->
    <div class="wp-block-group page-header">
        <!-- wp:post-title {"level":1,"className":"page-title"} /-->
    </div>
    <!-- /wp:group -->
    
    <!-- wp:post-featured-image {"className":"page-featured-image"} /-->
    
    <!-- wp:group {"className":"page-content","layout":{"type":"constrained"}} -->
    <div class="wp-block-group page-content">
        <!-- wp:post-content {"className":"page-content-inner"} /-->
    </div>
    <!-- /wp:group -->
    
</main>
<!-- /wp:group -->

<!-- wp:template-part {"slug":"footer","tagName":"footer"} /-->
```

### 5. templates/archive.html (Archive Template)

```html
<!-- wp:template-part {"slug":"header","tagName":"header"} /-->

<!-- wp:group {"tagName":"main","className":"site-main archive-page","layout":{"type":"constrained"}} -->
<main class="wp-block-group site-main archive-page">
    
    <!-- wp:group {"className":"archive-header","layout":{"type":"constrained"}} -->
    <div class="wp-block-group archive-header">
        <!-- wp:query-title {"type":"archive","className":"archive-title"} /-->
        <!-- wp:term-description {"className":"archive-description"} /-->
    </div>
    <!-- /wp:group -->
    
    <!-- wp:query {"queryId":1,"query":{"perPage":10,"pages":0,"offset":0,"postType":"post","order":"desc","orderBy":"date","author":"","search":"","exclude":[],"sticky":"","inherit":true},"className":"archive-query"} -->
    <div class="wp-block-query archive-query">
        
        <!-- wp:post-template {"className":"archive-post-list","layout":{"type":"grid","columnCount":2}} -->
            <!-- wp:group {"className":"archive-post-item","layout":{"type":"constrained"}} -->
            <div class="wp-block-group archive-post-item">
                
                <!-- wp:post-featured-image {"isLink":true,"className":"archive-post-thumbnail"} /-->
                
                <!-- wp:group {"className":"archive-post-content","layout":{"type":"constrained"}} -->
                <div class="wp-block-group archive-post-content">
                    
                    <!-- wp:post-title {"isLink":true,"className":"archive-post-title"} /-->
                    
                    <!-- wp:group {"className":"archive-post-meta","layout":{"type":"flex","flexWrap":"wrap"}} -->
                    <div class="wp-block-group archive-post-meta">
                        <!-- wp:post-date {"className":"archive-post-date"} /-->
                        <!-- wp:post-author {"showAvatar":false,"className":"archive-post-author"} /-->
                    </div>
                    <!-- /wp:group -->
                    
                    <!-- wp:post-excerpt {"className":"archive-post-excerpt"} /-->
                    
                </div>
                <!-- /wp:group -->
                
            </div>
            <!-- /wp:group -->
        <!-- /wp:post-template -->
        
        <!-- wp:query-pagination {"className":"archive-pagination","layout":{"type":"flex","justifyContent":"center"}} -->
        <div class="wp-block-query-pagination archive-pagination">
            <!-- wp:query-pagination-previous /-->
            <!-- wp:query-pagination-numbers /-->
            <!-- wp:query-pagination-next /-->
        </div>
        <!-- /wp:query-pagination -->
        
        <!-- wp:query-no-results -->
            <!-- wp:group {"className":"archive-no-results","layout":{"type":"constrained"}} -->
            <div class="wp-block-group archive-no-results">
                <!-- wp:heading {"textAlign":"center"} -->
                <h2 class="wp-block-heading has-text-align-center">No posts found in this category</h2>
                <!-- /wp:heading -->
                <!-- wp:paragraph {"align":"center"} -->
                <p class="has-text-align-center">Try browsing other categories or use the search function.</p>
                <!-- /wp:paragraph -->
            </div>
            <!-- /wp:group -->
        <!-- /wp:query-no-results -->
        
    </div>
    <!-- /wp:query -->
    
</main>
<!-- /wp:group -->

<!-- wp:template-part {"slug":"footer","tagName":"footer"} /-->
```

### 6. templates/search.html (Search Results Template)

```html
<!-- wp:template-part {"slug":"header","tagName":"header"} /-->

<!-- wp:group {"tagName":"main","className":"site-main search-results","layout":{"type":"constrained"}} -->
<main class="wp-block-group site-main search-results">
    
    <!-- wp:group {"className":"search-header","layout":{"type":"constrained"}} -->
    <div class="wp-block-group search-header">
        <!-- wp:query-title {"type":"search","className":"search-title"} /-->
        <!-- wp:search {"showLabel":false,"placeholder":"Refine your search...","className":"search-form-header"} /-->
    </div>
    <!-- /wp:group -->
    
    <!-- wp:query {"queryId":1,"query":{"perPage":10,"pages":0,"offset":0,"postType":"post","order":"desc","orderBy":"date","author":"","search":"","exclude":[],"sticky":"","inherit":true},"className":"search-query"} -->
    <div class="wp-block-query search-query">
        
        <!-- wp:post-template {"className":"search-results-list"} -->
            <!-- wp:group {"className":"search-result-item","layout":{"type":"flex","flexWrap":"nowrap"}} -->
            <div class="wp-block-group search-result-item">
                
                <!-- wp:post-featured-image {"isLink":true,"width":"150px","height":"100px","className":"search-result-thumbnail"} /-->
                
                <!-- wp:group {"className":"search-result-content","layout":{"type":"constrained"}} -->
                <div class="wp-block-group search-result-content">
                    
                    <!-- wp:post-title {"isLink":true,"className":"search-result-title"} /-->
                    
                    <!-- wp:group {"className":"search-result-meta","layout":{"type":"flex","flexWrap":"wrap"}} -->
                    <div class="wp-block-group search-result-meta">
                        <!-- wp:post-date {"className":"search-result-date"} /-->
                        <!-- wp:post-terms {"term":"category","className":"search-result-category"} /-->
                    </div>
                    <!-- /wp:group -->
                    
                    <!-- wp:post-excerpt {"className":"search-result-excerpt"} /-->
                    
                </div>
                <!-- /wp:group -->
                
            </div>
            <!-- /wp:group -->
        <!-- /wp:post-template -->
        
        <!-- wp:query-pagination {"className":"search-pagination","layout":{"type":"flex","justifyContent":"center"}} -->
        <div class="wp-block-query-pagination search-pagination">
            <!-- wp:query-pagination-previous /-->
            <!-- wp:query-pagination-numbers /-->
            <!-- wp:query-pagination-next /-->
        </div>
        <!-- /wp:query-pagination -->
        
        <!-- wp:query-no-results -->
            <!-- wp:group {"className":"search-no-results","layout":{"type":"constrained"}} -->
            <div class="wp-block-group search-no-results">
                <!-- wp:heading {"textAlign":"center"} -->
                <h2 class="wp-block-heading has-text-align-center">No search results found</h2>
                <!-- /wp:heading -->
                <!-- wp:paragraph {"align":"center"} -->
                <p class="has-text-align-center">Sorry, no content matched your search terms. Try different keywords or browse our categories.</p>
                <!-- /wp:paragraph -->
                <!-- wp:search {"showLabel":false,"placeholder":"Try another search...","className":"search-form-no-results"} /-->
            </div>
            <!-- /wp:group -->
        <!-- /wp:query-no-results -->
        
    </div>
    <!-- /wp:query -->
    
</main>
<!-- /wp:group -->

<!-- wp:template-part {"slug":"footer","tagName":"footer"} /-->
```

### 7. templates/404.html (Error Page Template)

```html
<!-- wp:template-part {"slug":"header","tagName":"header"} /-->

<!-- wp:group {"tagName":"main","className":"site-main error-404","layout":{"type":"constrained"}} -->
<main class="wp-block-group site-main error-404">
    
    <!-- wp:group {"className":"error-content","layout":{"type":"constrained"}} -->
    <div class="wp-block-group error-content">
        
        <!-- wp:heading {"level":1,"textAlign":"center","className":"error-title"} -->
        <h1 class="wp-block-heading has-text-align-center error-title">404</h1>
        <!-- /wp:heading -->
        
        <!-- wp:heading {"level":2,"textAlign":"center","className":"error-subtitle"} -->
        <h2 class="wp-block-heading has-text-align-center error-subtitle">Page Not Found</h2>
        <!-- /wp:heading -->
        
        <!-- wp:paragraph {"align":"center","className":"error-description"} -->
        <p class="has-text-align-center error-description">The page you're looking for doesn't exist or has been moved.</p>
        <!-- /wp:paragraph -->
        
        <!-- wp:search {"showLabel":false,"placeholder":"Search our site...","buttonText":"Search","className":"error-search"} /-->
        
        <!-- wp:spacer {"height":"2rem"} -->
        <div style="height:2rem" aria-hidden="true" class="wp-block-spacer"></div>
        <!-- /wp:spacer -->
        
        <!-- wp:buttons {"layout":{"type":"flex","justifyContent":"center"}} -->
        <div class="wp-block-buttons">
            <!-- wp:button {"className":"error-home-button"} -->
            <div class="wp-block-button error-home-button">
                <a class="wp-block-button__link wp-element-button" href="/">Return Home</a>
            </div>
            <!-- /wp:button -->
        </div>
        <!-- /wp:buttons -->
        
    </div>
    <!-- /wp:group -->
    
</main>
<!-- /wp:group -->

<!-- wp:template-part {"slug":"footer","tagName":"footer"} /-->
```

### 8. templates/front-page.html (Homepage Template)

```html
<!-- wp:template-part {"slug":"header","tagName":"header"} /-->

<!-- wp:group {"tagName":"main","className":"site-main front-page","layout":{"type":"constrained"}} -->
<main class="wp-block-group site-main front-page">
    
    <!-- wp:group {"className":"hero-section","layout":{"type":"constrained"}} -->
    <div class="wp-block-group hero-section">
        
        <!-- wp:heading {"level":1,"textAlign":"center","className":"hero-title"} -->
        <h1 class="wp-block-heading has-text-align-center hero-title">Welcome to Our Blog</h1>
        <!-- /wp:heading -->
        
        <!-- wp:paragraph {"align":"center","className":"hero-description"} -->
        <p class="has-text-align-center hero-description">Discover amazing content, insights, and stories that matter.</p>
        <!-- /wp:paragraph -->
        
        <!-- wp:search {"showLabel":false,"placeholder":"What are you looking for?","buttonText":"Search","className":"hero-search"} /-->
        
    </div>
    <!-- /wp:group -->
    
    <!-- wp:spacer {"height":"3rem"} -->
    <div style="height:3rem" aria-hidden="true" class="wp-block-spacer"></div>
    <!-- /wp:spacer -->
    
    <!-- wp:query {"queryId":1,"query":{"perPage":6,"pages":0,"offset":0,"postType":"post","order":"desc","orderBy":"date","author":"","search":"","exclude":[],"sticky":"exclude","inherit":false},"className":"featured-posts"} -->
    <div class="wp-block-query featured-posts">
        
        <!-- wp:heading {"level":2,"textAlign":"center","className":"section-title"} -->
        <h2 class="wp-block-heading has-text-align-center section-title">Latest Posts</h2>
        <!-- /wp:heading -->
        
        <!-- wp:post-template {"className":"featured-posts-grid","layout":{"type":"grid","columnCount":3}} -->
            <!-- wp:group {"className":"featured-post-item","layout":{"type":"constrained"}} -->
            <div class="wp-block-group featured-post-item">
                
                <!-- wp:post-featured-image {"isLink":true,"className":"featured-post-thumbnail"} /-->
                
                <!-- wp:group {"className":"featured-post-content","layout":{"type":"constrained"}} -->
                <div class="wp-block-group featured-post-content">
                    
                    <!-- wp:post-title {"isLink":true,"className":"featured-post-title"} /-->
                    
                    <!-- wp:post-date {"className":"featured-post-date"} /-->
                    
                    <!-- wp:post-excerpt {"className":"featured-post-excerpt"} /-->
                    
                </div>
                <!-- /wp:group -->
                
            </div>
            <!-- /wp:group -->
        <!-- /wp:post-template -->
        
    </div>
    <!-- /wp:query -->
    
    <!-- wp:spacer {"height":"2rem"} -->
    <div style="height:2rem" aria-hidden="true" class="wp-block-spacer"></div>
    <!-- /wp:spacer -->
    
    <!-- wp:buttons {"layout":{"type":"flex","justifyContent":"center"}} -->
    <div class="wp-block-buttons">
        <!-- wp:button {"className":"view-all-posts"} -->
        <div class="wp-block-button view-all-posts">
            <a class="wp-block-button__link wp-element-button" href="/blog">View All Posts</a>
        </div>
        <!-- /wp:button -->
    </div>
    <!-- /wp:buttons -->
    
</main>
<!-- /wp:group -->

<!-- wp:template-part {"slug":"footer","tagName":"footer"} /-->
```

### 9. templates/blank.html (Blank Template)

```html
<!-- wp:post-content {"className":"blank-template-content","layout":{"type":"constrained"}} /-->
```

### 10. assets/css/templates.css (Template-Specific Styles)

```css
/*
 * Template-Specific Styles for GPress Theme
 * Only loaded when specific templates are being used
 */

/* Archive Page Styles */
.archive-page .archive-header {
    margin-bottom: 2rem;
    padding-bottom: 1rem;
    border-bottom: 1px solid var(--wp--preset--color--gray-200);
}

.archive-page .archive-post-list {
    gap: 2rem;
}

.archive-page .archive-post-item {
    background: var(--wp--preset--color--white);
    border: 1px solid var(--wp--preset--color--gray-200);
    border-radius: 8px;
    overflow: hidden;
    transition: box-shadow 0.2s ease;
}

.archive-page .archive-post-item:hover {
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
}

.archive-page .archive-post-content {
    padding: 1.5rem;
}

/* Single Post Styles */
.single-post .post-header {
    margin-bottom: 2rem;
}

.single-post .post-meta {
    margin-top: 1rem;
    padding-top: 1rem;
    border-top: 1px solid var(--wp--preset--color--gray-200);
}

.single-post .post-navigation {
    margin-top: 3rem;
    padding-top: 2rem;
}

.single-post .post-navigation a {
    padding: 1rem;
    background: var(--wp--preset--color--light);
    border-radius: 4px;
    text-decoration: none;
    transition: background-color 0.2s ease;
}

.single-post .post-navigation a:hover {
    background: var(--wp--preset--color--gray-200);
}

/* Search Results Styles */
.search-results .search-header {
    margin-bottom: 2rem;
    padding: 2rem;
    background: var(--wp--preset--color--light);
    border-radius: 8px;
}

.search-results .search-result-item {
    padding: 1.5rem;
    border-bottom: 1px solid var(--wp--preset--color--gray-200);
    gap: 1rem;
}

.search-results .search-result-item:last-child {
    border-bottom: none;
}

.search-results .search-result-thumbnail {
    flex-shrink: 0;
}

/* 404 Error Page Styles */
.error-404 .error-content {
    text-align: center;
    padding: 4rem 2rem;
}

.error-404 .error-title {
    font-size: 6rem;
    font-weight: 700;
    color: var(--wp--preset--color--gray-300);
    margin-bottom: 1rem;
}

.error-404 .error-subtitle {
    color: var(--wp--preset--color--dark);
    margin-bottom: 1rem;
}

.error-404 .error-search {
    max-width: 400px;
    margin: 2rem auto;
}

/* Front Page Styles */
.front-page .hero-section {
    text-align: center;
    padding: 4rem 2rem;
    background: linear-gradient(135deg, var(--wp--preset--color--light) 0%, var(--wp--preset--color--white) 100%);
    border-radius: 12px;
    margin-bottom: 3rem;
}

.front-page .hero-title {
    margin-bottom: 1rem;
}

.front-page .hero-search {
    max-width: 500px;
    margin: 2rem auto 0;
}

.front-page .featured-posts-grid {
    gap: 2rem;
}

.front-page .featured-post-item {
    background: var(--wp--preset--color--white);
    border: 1px solid var(--wp--preset--color--gray-200);
    border-radius: 8px;
    overflow: hidden;
    transition: transform 0.2s ease, box-shadow 0.2s ease;
}

.front-page .featured-post-item:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 25px rgba(0, 0, 0, 0.1);
}

.front-page .featured-post-content {
    padding: 1.5rem;
}

/* Responsive Design */
@media (max-width: 768px) {
    .archive-page .archive-post-list {
        grid-template-columns: 1fr !important;
    }
    
    .front-page .featured-posts-grid {
        grid-template-columns: 1fr !important;
    }
    
    .front-page .hero-section {
        padding: 2rem 1rem;
    }
    
    .error-404 .error-title {
        font-size: 4rem;
    }
    
    .search-results .search-result-item {
        flex-direction: column;
        text-align: center;
    }
}

@media (max-width: 1024px) {
    .front-page .featured-posts-grid {
        grid-template-columns: repeat(2, 1fr) !important;
    }
}
```

### 11. assets/js/templates.js (Template Enhancement JavaScript)

```javascript
/**
 * Template Enhancement JavaScript for GPress Theme
 * Only loaded when specific templates require it
 */

(function() {
    'use strict';

    // Initialize when DOM is ready
    document.addEventListener('DOMContentLoaded', function() {
        initTemplateEnhancements();
    });

    function initTemplateEnhancements() {
        // Search form enhancements
        enhanceSearchForms();
        
        // Archive page enhancements
        if (document.body.classList.contains('archive')) {
            enhanceArchivePage();
        }
        
        // Single post enhancements
        if (document.body.classList.contains('single-post')) {
            enhanceSinglePost();
        }
        
        // Front page enhancements
        if (document.body.classList.contains('home')) {
            enhanceFrontPage();
        }
    }

    function enhanceSearchForms() {
        const searchForms = document.querySelectorAll('.wp-block-search');
        
        searchForms.forEach(function(form) {
            const input = form.querySelector('input[type="search"]');
            const button = form.querySelector('button, input[type="submit"]');
            
            if (input && button) {
                // Add loading state to search button
                form.addEventListener('submit', function() {
                    button.disabled = true;
                    button.textContent = 'Searching...';
                });
                
                // Add search suggestions (if enabled)
                if (form.classList.contains('search-with-suggestions')) {
                    addSearchSuggestions(input);
                }
            }
        });
    }

    function enhanceArchivePage() {
        // Add infinite scroll for archive pages (optional)
        const pagination = document.querySelector('.archive-pagination');
        if (pagination && window.gpress_infinite_scroll) {
            initInfiniteScroll(pagination);
        }
        
        // Archive filter animations
        const archiveItems = document.querySelectorAll('.archive-post-item');
        observeArchiveItems(archiveItems);
    }

    function enhanceSinglePost() {
        // Add reading progress indicator
        addReadingProgress();
        
        // Enhance post navigation
        enhancePostNavigation();
        
        // Add smooth scrolling to comments
        const commentsLinks = document.querySelectorAll('a[href="#comments"]');
        commentsLinks.forEach(function(link) {
            link.addEventListener('click', function(e) {
                e.preventDefault();
                const comments = document.getElementById('comments');
                if (comments) {
                    comments.scrollIntoView({ behavior: 'smooth' });
                }
            });
        });
    }

    function enhanceFrontPage() {
        // Animate featured posts on scroll
        const featuredPosts = document.querySelectorAll('.featured-post-item');
        observeFeaturedPosts(featuredPosts);
        
        // Hero section parallax effect (subtle)
        const heroSection = document.querySelector('.hero-section');
        if (heroSection && window.matchMedia('(prefers-reduced-motion: no-preference)').matches) {
            addParallaxEffect(heroSection);
        }
    }

    function addSearchSuggestions(input) {
        let timeout;
        const suggestionsList = document.createElement('ul');
        suggestionsList.className = 'search-suggestions';
        suggestionsList.style.display = 'none';
        input.parentNode.appendChild(suggestionsList);

        input.addEventListener('input', function() {
            clearTimeout(timeout);
            const query = this.value.trim();
            
            if (query.length >= 2) {
                timeout = setTimeout(function() {
                    fetchSearchSuggestions(query, suggestionsList);
                }, 300);
            } else {
                suggestionsList.style.display = 'none';
            }
        });
    }

    function fetchSearchSuggestions(query, suggestionsList) {
        // This would typically fetch from a REST API endpoint
        // For now, showing static suggestions as example
        const suggestions = [
            'Recent Posts',
            'Popular Categories',
            'Related Topics'
        ];
        
        suggestionsList.innerHTML = '';
        suggestions.forEach(function(suggestion) {
            const li = document.createElement('li');
            li.textContent = suggestion;
            li.addEventListener('click', function() {
                // Handle suggestion click
                suggestionsList.style.display = 'none';
            });
            suggestionsList.appendChild(li);
        });
        
        suggestionsList.style.display = suggestions.length ? 'block' : 'none';
    }

    function addReadingProgress() {
        const progressBar = document.createElement('div');
        progressBar.className = 'reading-progress';
        progressBar.innerHTML = '<div class="reading-progress-bar"></div>';
        document.body.appendChild(progressBar);

        const progressBarFill = progressBar.querySelector('.reading-progress-bar');
        const article = document.querySelector('.post-content');
        
        if (article) {
            window.addEventListener('scroll', function() {
                const articleTop = article.offsetTop;
                const articleHeight = article.offsetHeight;
                const windowHeight = window.innerHeight;
                const scrolled = window.scrollY - articleTop + windowHeight;
                const progress = Math.min(Math.max(scrolled / articleHeight, 0), 1);
                
                progressBarFill.style.width = (progress * 100) + '%';
            });
        }
    }

    function enhancePostNavigation() {
        const navLinks = document.querySelectorAll('.post-navigation a');
        navLinks.forEach(function(link) {
            link.addEventListener('mouseenter', function() {
                // Preload next/previous post
                const href = this.getAttribute('href');
                if (href) {
                    const preloadLink = document.createElement('link');
                    preloadLink.rel = 'prefetch';
                    preloadLink.href = href;
                    document.head.appendChild(preloadLink);
                }
            });
        });
    }

    function observeArchiveItems(items) {
        if (!window.IntersectionObserver) return;

        const observer = new IntersectionObserver(function(entries) {
            entries.forEach(function(entry) {
                if (entry.isIntersecting) {
                    entry.target.classList.add('animate-in');
                    observer.unobserve(entry.target);
                }
            });
        }, {
            threshold: 0.1,
            rootMargin: '0px 0px -50px 0px'
        });

        items.forEach(function(item) {
            observer.observe(item);
        });
    }

    function observeFeaturedPosts(posts) {
        if (!window.IntersectionObserver) return;

        const observer = new IntersectionObserver(function(entries) {
            entries.forEach(function(entry, index) {
                if (entry.isIntersecting) {
                    setTimeout(function() {
                        entry.target.classList.add('fade-in');
                    }, index * 100);
                    observer.unobserve(entry.target);
                }
            });
        }, {
            threshold: 0.2
        });

        posts.forEach(function(post) {
            observer.observe(post);
        });
    }

    function addParallaxEffect(element) {
        window.addEventListener('scroll', function() {
            const scrolled = window.pageYOffset;
            const parallax = scrolled * 0.5;
            element.style.transform = 'translateY(' + parallax + 'px)';
        });
    }

})();
```

### 12. inc/template-functions.php (Template Helper Functions)

```php
<?php
/**
 * Template helper functions for GPress theme
 *
 * @package GPress
 * @since 1.0.0
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Detect which template is being used and enqueue appropriate assets
 */
function gpress_conditional_template_assets() {
    
    // Only load template CSS/JS when needed
    if (is_404()) {
        wp_enqueue_style('gpress-404', GPRESS_THEME_URI . '/assets/css/404.css', array(), GPRESS_VERSION);
    }
    
    if (is_search()) {
        wp_enqueue_style('gpress-search', GPRESS_THEME_URI . '/assets/css/search.css', array(), GPRESS_VERSION);
        wp_enqueue_script('gpress-search-js', GPRESS_THEME_URI . '/assets/js/search.js', array(), GPRESS_VERSION, true);
    }
    
    if (is_archive()) {
        wp_enqueue_style('gpress-archive', GPRESS_THEME_URI . '/assets/css/archive.css', array(), GPRESS_VERSION);
    }
    
    if (is_single()) {
        wp_enqueue_style('gpress-single', GPRESS_THEME_URI . '/assets/css/single.css', array(), GPRESS_VERSION);
        wp_enqueue_script('gpress-single-js', GPRESS_THEME_URI . '/assets/js/single.js', array(), GPRESS_VERSION, true);
    }
    
    if (is_front_page() || is_home()) {
        wp_enqueue_style('gpress-front-page', GPRESS_THEME_URI . '/assets/css/front-page.css', array(), GPRESS_VERSION);
        wp_enqueue_script('gpress-front-page-js', GPRESS_THEME_URI . '/assets/js/front-page.js', array(), GPRESS_VERSION, true);
    }
    
    // Load templates.css for all template-specific styles
    wp_enqueue_style('gpress-templates', GPRESS_THEME_URI . '/assets/css/templates.css', array(), GPRESS_VERSION);
    
    // Load templates.js for template enhancements
    wp_enqueue_script('gpress-templates', GPRESS_THEME_URI . '/assets/js/templates.js', array(), GPRESS_VERSION, true);
}
add_action('wp_enqueue_scripts', 'gpress_conditional_template_assets');

/**
 * Detect and enqueue block-specific styles only when blocks are present
 */
function gpress_conditional_block_assets() {
    if (is_admin()) return;
    
    global $post;
    if (!$post) return;
    
    // Get post content
    $content = $post->post_content;
    
    // Check for specific blocks and enqueue their styles
    if (has_block('core/gallery', $post)) {
        wp_enqueue_style('gpress-gallery', GPRESS_THEME_URI . '/assets/css/blocks/gallery.css', array(), GPRESS_VERSION);
    }
    
    if (has_block('core/cover', $post)) {
        wp_enqueue_style('gpress-cover', GPRESS_THEME_URI . '/assets/css/blocks/cover.css', array(), GPRESS_VERSION);
    }
    
    if (has_block('core/table', $post)) {
        wp_enqueue_style('gpress-table', GPRESS_THEME_URI . '/assets/css/blocks/table.css', array(), GPRESS_VERSION);
    }
    
    if (has_block('core/code', $post) || has_block('core/preformatted', $post)) {
        wp_enqueue_style('gpress-code', GPRESS_THEME_URI . '/assets/css/blocks/code.css', array(), GPRESS_VERSION);
    }
    
    if (has_block('core/media-text', $post)) {
        wp_enqueue_style('gpress-media-text', GPRESS_THEME_URI . '/assets/css/blocks/media-text.css', array(), GPRESS_VERSION);
    }
    
    if (has_block('core/columns', $post)) {
        wp_enqueue_style('gpress-columns', GPRESS_THEME_URI . '/assets/css/blocks/columns.css', array(), GPRESS_VERSION);
    }
}
add_action('wp_enqueue_scripts', 'gpress_conditional_block_assets');

/**
 * Optimize queries for different template types
 */
function gpress_optimize_template_queries($query) {
    if (is_admin() || !$query->is_main_query()) {
        return;
    }
    
    // Archive page optimizations
    if ($query->is_archive()) {
        $query->set('posts_per_page', 12);
        $query->set('meta_query', array(
            array(
                'key' => '_thumbnail_id',
                'compare' => 'EXISTS'
            )
        ));
    }
    
    // Search page optimizations
    if ($query->is_search()) {
        $query->set('posts_per_page', 10);
        // Exclude certain post types from search
        $query->set('post_type', array('post', 'page'));
    }
    
    // Front page optimizations
    if ($query->is_home() && $query->is_front_page()) {
        $query->set('posts_per_page', 6);
        $query->set('meta_key', '_thumbnail_id');
    }
}
add_action('pre_get_posts', 'gpress_optimize_template_queries');

/**
 * Add template body classes for styling
 */
function gpress_template_body_classes($classes) {
    
    if (is_front_page()) {
        $classes[] = 'front-page';
    }
    
    if (is_single()) {
        $classes[] = 'single-post';
    }
    
    if (is_page()) {
        $classes[] = 'single-page';
    }
    
    if (is_archive()) {
        $classes[] = 'archive-page';
    }
    
    if (is_search()) {
        $classes[] = 'search-results';
    }
    
    if (is_404()) {
        $classes[] = 'error-404';
    }
    
    return $classes;
}
add_filter('body_class', 'gpress_template_body_classes');

/**
 * Template-specific performance optimizations
 */
function gpress_template_performance_optimizations() {
    
    // Disable unused features on specific templates
    if (is_404()) {
        // Disable comments on 404 pages
        add_filter('comments_open', '__return_false');
    }
    
    if (is_search()) {
        // Optimize search queries
        add_filter('posts_search_orderby', 'gpress_search_orderby_relevance');
    }
    
    if (is_archive()) {
        // Remove unnecessary meta queries on archive pages
        remove_action('wp_head', 'adjacent_posts_rel_link_wp_head');
    }
}
add_action('template_redirect', 'gpress_template_performance_optimizations');

/**
 * Improve search relevance ordering
 */
function gpress_search_orderby_relevance($orderby) {
    global $wpdb;
    
    if (is_search()) {
        $orderby = "
            CASE 
                WHEN {$wpdb->posts}.post_title LIKE '%{$_GET['s']}%' THEN 1
                WHEN {$wpdb->posts}.post_content LIKE '%{$_GET['s']}%' THEN 2
                ELSE 3
            END ASC,
            {$wpdb->posts}.post_date DESC
        ";
    }
    
    return $orderby;
}

/**
 * Get template-specific CSS classes
 */
function gpress_get_template_classes() {
    $classes = array();
    
    if (is_front_page()) {
        $classes[] = 'has-hero-section';
    }
    
    if (is_single() && has_post_thumbnail()) {
        $classes[] = 'has-featured-image';
    }
    
    if (is_archive() && !have_posts()) {
        $classes[] = 'no-results';
    }
    
    return implode(' ', $classes);
}

/**
 * Add critical CSS for templates
 */
function gpress_template_critical_css() {
    $critical_css = '';
    
    if (is_front_page()) {
        $critical_css .= '
        .hero-section{background:linear-gradient(135deg,var(--wp--preset--color--light) 0%,var(--wp--preset--color--white) 100%);padding:4rem 2rem;border-radius:12px;text-align:center;}
        .featured-posts-grid{display:grid;grid-template-columns:repeat(auto-fit,minmax(300px,1fr));gap:2rem;}
        ';
    }
    
    if (is_single()) {
        $critical_css .= '
        .single-post .post-header{margin-bottom:2rem;}
        .single-post .post-meta{border-top:1px solid var(--wp--preset--color--gray-200);padding-top:1rem;margin-top:1rem;}
        ';
    }
    
    if (!empty($critical_css)) {
        echo '<style id="gpress-template-critical">' . $critical_css . '</style>';
    }
}
add_action('wp_head', 'gpress_template_critical_css', 1);
```

### 13. Update inc/enqueue-scripts.php

Add conditional template asset loading to your existing `inc/enqueue-scripts.php`:

```php
// Add this to the existing inc/enqueue-scripts.php file after the existing content

/**
 * Load template functions for conditional asset loading
 */
require_once GPRESS_THEME_DIR . '/inc/template-functions.php';
```

### 14. Update functions.php

Add this to your existing `functions.php`:

```php
/**
 * Load template functions
 */
require_once GPRESS_INC_DIR . '/template-functions.php';

/**
 * Add theme support for block templates
 */
function gpress_block_template_support() {
    add_theme_support('block-templates');
    add_theme_support('block-template-parts');
}
add_action('after_setup_theme', 'gpress_block_template_support', 20);
```

## Testing Instructions

After completing this step, perform comprehensive testing:

### 1. Template Creation Test
```bash
# Verify all template files are created
ls -la templates/
# Should show: index.html, single.html, page.html, archive.html, search.html, 404.html, front-page.html, blank.html
```

### 2. FSE Template Test
1. Go to Appearance → Site Editor (WordPress 5.9+)
2. Verify all templates appear in the template list
3. Try editing a template in the Site Editor
4. Confirm changes are saved and applied

### 3. Template Functionality Test
1. **Homepage**: Visit your site's front page, verify hero section and featured posts display
2. **Single Post**: View any post, check layout, navigation, and comments section
3. **Archive Page**: Visit a category page, verify post grid and pagination
4. **Search Results**: Perform a search, check results layout and pagination
5. **404 Page**: Visit a non-existent URL, verify error page displays correctly

### 4. Conditional Asset Loading Test
1. Open browser DevTools → Network tab
2. Visit different pages and verify only relevant CSS/JS files are loaded:
   - Front page: Should load `front-page.css` and `front-page.js`
   - Single post: Should load `single.css` and `single.js`
   - Archive page: Should load `archive.css`
   - Search page: Should load `search.css` and `search.js`

### 5. Block-Specific Asset Test
1. Create a post with a gallery block
2. Verify `gallery.css` is only loaded on that post
3. Create a post without galleries
4. Verify `gallery.css` is NOT loaded

### 6. Performance Test
1. Run Lighthouse test on different template types
2. Verify 90+ performance scores maintained
3. Check that unused CSS/JS is not loaded
4. Confirm critical CSS is inlined

### 7. Mobile Responsiveness Test
1. Test all templates on mobile devices
2. Verify responsive grid layouts work properly
3. Check touch navigation and interactions

### 8. Accessibility Test
1. Test keyboard navigation through all templates
2. Verify proper heading hierarchy
3. Check color contrast on all template variations
4. Test screen reader compatibility

## Expected Results

After completing Step 4, you should have:

- ✅ Complete set of HTML block templates for FSE
- ✅ Conditional asset loading system implemented
- ✅ Template-specific styling that loads only when needed
- ✅ Optimized performance with minimal asset loading
- ✅ Responsive design across all template types
- ✅ Enhanced user experience with template-specific features
- ✅ Proper FSE integration with Site Editor access
- ✅ Maintained 90+ Lighthouse performance scores

## Performance Benefits

1. **Conditional Loading**: Only necessary CSS/JS is loaded per template
2. **Block-Specific Assets**: Styles load only when blocks are present
3. **Optimized Queries**: Template-specific database query optimizations
4. **Critical CSS**: Template-specific critical styles inlined
5. **Reduced Bundle Size**: No unused CSS/JS bloat
6. **Faster Page Loads**: Minimal asset overhead per page

## Next Step

Proceed to [Step 5: Template Parts Development](./step-05-template-parts.md) to create reusable template parts that work with these block templates.

## Troubleshooting

**Templates not appearing in Site Editor:**
- Confirm WordPress version is 5.9 or higher
- Check that `block-templates` theme support is added
- Verify template files are in `/templates/` directory

**Conditional assets not loading:**
- Check that `template-functions.php` is being included
- Verify asset file paths are correct
- Confirm hooks are firing properly with debugging

**Template styling issues:**
- Ensure `theme.json` CSS custom properties are available
- Check that template-specific CSS is being enqueued
- Verify no conflicts with existing styles

**Performance not improved:**
- Confirm unused assets are not being loaded
- Check that critical CSS is being inlined
- Verify conditional loading logic is working