# Step 5: Template Parts Development

## Objective
Create reusable template parts (header, footer, sidebar, navigation) that provide consistent design elements across all templates while maintaining performance and accessibility standards.

## What You'll Learn
- Template part structure and organization
- Navigation block implementation
- Site branding and logo setup
- Footer widget areas and social links
- Sidebar implementation
- Performance optimization for template parts

## Understanding Template Parts

Template parts are reusable components that:
- Can be shared across multiple templates
- Are editable in the Site Editor
- Support theme.json styling
- Improve code organization and maintenance
- Enable consistent branding across the site

## Template Parts Directory Structure

```
parts/
├── header.html           # Main site header
├── footer.html           # Main site footer
├── sidebar.html          # Primary sidebar
├── navigation.html       # Main navigation menu
├── breadcrumbs.html      # Breadcrumb navigation
├── social-links.html     # Social media links
├── newsletter.html       # Newsletter signup
└── search-form.html      # Search form component
```

## Core Template Parts

### 1. parts/header.html - Site Header

```html
<!-- wp:group {"layout":{"type":"constrained"},"style":{"spacing":{"padding":{"top":"1rem","bottom":"1rem"},"blockGap":"0"}}} -->
<div class="wp-block-group" style="padding-top:1rem;padding-bottom:1rem">
    <!-- wp:group {"layout":{"type":"flex","flexWrap":"wrap","justifyContent":"space-between","verticalAlignment":"center"}} -->
    <div class="wp-block-group">
        <!-- wp:group {"layout":{"type":"flex","flexWrap":"nowrap","verticalAlignment":"center"}} -->
        <div class="wp-block-group">
            <!-- wp:site-logo {"width":60,"shouldSyncIcon":true} /-->
            
            <!-- wp:group {"style":{"spacing":{"blockGap":"0.25rem"}},"layout":{"type":"flex","orientation":"vertical"}} -->
            <div class="wp-block-group">
                <!-- wp:site-title {"fontSize":"large","style":{"typography":{"fontWeight":"700","textDecoration":"none"}}} /-->
                <!-- wp:site-tagline {"fontSize":"small","style":{"color":{"text":"var:preset|color|gray-600"}}} /-->
            </div>
            <!-- /wp:group -->
        </div>
        <!-- /wp:group -->
        
        <!-- wp:navigation {"ref":1,"layout":{"type":"flex","setCascadingProperties":true,"justifyContent":"right","orientation":"horizontal"},"style":{"spacing":{"blockGap":"2rem"}}} /-->
    </div>
    <!-- /wp:group -->
</div>
<!-- /wp:group -->

<!-- wp:separator {"style":{"color":{"background":"var:preset|color|gray-200"}},"className":"is-style-wide"} -->
<hr class="wp-block-separator has-text-color has-background is-style-wide" style="background-color:var(--wp--preset--color--gray-200);color:var(--wp--preset--color--gray-200)"/>
<!-- /wp:separator -->
```

### 2. parts/footer.html - Site Footer

```html
<!-- wp:separator {"style":{"color":{"background":"var:preset|color|gray-200"}},"className":"is-style-wide"} -->
<hr class="wp-block-separator has-text-color has-background is-style-wide" style="background-color:var(--wp--preset--color--gray-200);color:var(--wp--preset--color--gray-200)"/>
<!-- /wp:separator -->

<!-- wp:group {"style":{"spacing":{"padding":{"top":"3rem","bottom":"2rem"}}},"backgroundColor":"gray-100","layout":{"type":"constrained"}} -->
<div class="wp-block-group has-gray-100-background-color has-background" style="padding-top:3rem;padding-bottom:2rem">
    <!-- wp:columns {"style":{"spacing":{"blockGap":{"top":"2rem","left":"3rem"}}}} -->
    <div class="wp-block-columns">
        <!-- wp:column -->
        <div class="wp-block-column">
            <!-- wp:group {"style":{"spacing":{"blockGap":"1rem"}},"layout":{"type":"flex","orientation":"vertical"}} -->
            <div class="wp-block-group">
                <!-- wp:site-title {"fontSize":"large","style":{"typography":{"fontWeight":"700"}}} /-->
                
                <!-- wp:site-tagline {"fontSize":"small","style":{"color":{"text":"var:preset|color|gray-600"}}} /-->
                
                <!-- wp:paragraph {"fontSize":"small","style":{"color":{"text":"var:preset|color|gray-600"}}} -->
                <p class="has-small-font-size" style="color:var(--wp--preset--color--gray-600)">Stay connected with our latest posts and updates. Follow us on social media for more content.</p>
                <!-- /wp:paragraph -->
                
                <!-- wp:template-part {"slug":"social-links"} /-->
            </div>
            <!-- /wp:group -->
        </div>
        <!-- /wp:column -->
        
        <!-- wp:column -->
        <div class="wp-block-column">
            <!-- wp:heading {"level":4,"fontSize":"medium"} -->
            <h4 class="wp-block-heading has-medium-font-size">Quick Links</h4>
            <!-- /wp:heading -->
            
            <!-- wp:navigation {"ref":2,"layout":{"type":"flex","orientation":"vertical","justifyContent":"left"},"style":{"spacing":{"blockGap":"0.5rem"}}} /-->
        </div>
        <!-- /wp:column -->
        
        <!-- wp:column -->
        <div class="wp-block-column">
            <!-- wp:heading {"level":4,"fontSize":"medium"} -->
            <h4 class="wp-block-heading has-medium-font-size">Categories</h4>
            <!-- /wp:heading -->
            
            <!-- wp:categories {"showHierarchy":true,"showPostCounts":true,"fontSize":"small"} /-->
        </div>
        <!-- /wp:column -->
        
        <!-- wp:column -->
        <div class="wp-block-column">
            <!-- wp:heading {"level":4,"fontSize":"medium"} -->
            <h4 class="wp-block-heading has-medium-font-size">Stay Updated</h4>
            <!-- /wp:heading -->
            
            <!-- wp:template-part {"slug":"newsletter"} /-->
        </div>
        <!-- /wp:column -->
    </div>
    <!-- /wp:columns -->
</div>
<!-- /wp:group -->

<!-- wp:group {"style":{"spacing":{"padding":{"top":"1.5rem","bottom":"1.5rem"}},"border":{"top":{"color":"var:preset|color|gray-200","width":"1px","style":"solid"}}},"backgroundColor":"gray-100","layout":{"type":"constrained"}} -->
<div class="wp-block-group has-gray-100-background-color has-background" style="border-top-color:var(--wp--preset--color--gray-200);border-top-style:solid;border-top-width:1px;padding-top:1.5rem;padding-bottom:1.5rem">
    <!-- wp:group {"layout":{"type":"flex","flexWrap":"wrap","justifyContent":"space-between","verticalAlignment":"center"}} -->
    <div class="wp-block-group">
        <!-- wp:paragraph {"fontSize":"small","style":{"color":{"text":"var:preset|color|gray-600"}}} -->
        <p class="has-small-font-size" style="color:var(--wp--preset--color--gray-600)">© 2025 ModernBlog2025. All rights reserved.</p>
        <!-- /wp:paragraph -->
        
        <!-- wp:navigation {"ref":3,"layout":{"type":"flex","justifyContent":"right"},"fontSize":"small"} /-->
    </div>
    <!-- /wp:group -->
</div>
<!-- /wp:group -->
```

### 3. parts/sidebar.html - Primary Sidebar

```html
<!-- wp:group {"style":{"spacing":{"blockGap":"2rem","padding":{"top":"0","bottom":"0","left":"0","right":"0"}}},"layout":{"type":"flex","orientation":"vertical"}} -->
<div class="wp-block-group" style="padding-top:0;padding-right:0;padding-bottom:0;padding-left:0">
    <!-- wp:group {"style":{"spacing":{"padding":{"top":"1.5rem","bottom":"1.5rem","left":"1.5rem","right":"1.5rem"}},"border":{"radius":"8px"}},"backgroundColor":"gray-100","layout":{"type":"flex","orientation":"vertical"}} -->
    <div class="wp-block-group has-gray-100-background-color has-background" style="border-radius:8px;padding-top:1.5rem;padding-right:1.5rem;padding-bottom:1.5rem;padding-left:1.5rem">
        <!-- wp:heading {"level":3,"fontSize":"medium"} -->
        <h3 class="wp-block-heading has-medium-font-size">Search</h3>
        <!-- /wp:heading -->
        
        <!-- wp:search {"label":"Search","showLabel":false,"placeholder":"Search posts...","buttonPosition":"button-inside","buttonUseIcon":true} /-->
    </div>
    <!-- /wp:group -->
    
    <!-- wp:group {"style":{"spacing":{"padding":{"top":"1.5rem","bottom":"1.5rem","left":"1.5rem","right":"1.5rem"}},"border":{"radius":"8px"}},"backgroundColor":"gray-100","layout":{"type":"flex","orientation":"vertical"}} -->
    <div class="wp-block-group has-gray-100-background-color has-background" style="border-radius:8px;padding-top:1.5rem;padding-right:1.5rem;padding-bottom:1.5rem;padding-left:1.5rem">
        <!-- wp:heading {"level":3,"fontSize":"medium"} -->
        <h3 class="wp-block-heading has-medium-font-size">Recent Posts</h3>
        <!-- /wp:heading -->
        
        <!-- wp:latest-posts {"postsToShow":5,"displayPostDate":true,"displayFeaturedImage":true,"featuredImageAlign":"left","featuredImageSizeSlug":"thumbnail","fontSize":"small"} /-->
    </div>
    <!-- /wp:group -->
    
    <!-- wp:group {"style":{"spacing":{"padding":{"top":"1.5rem","bottom":"1.5rem","left":"1.5rem","right":"1.5rem"}},"border":{"radius":"8px"}},"backgroundColor":"gray-100","layout":{"type":"flex","orientation":"vertical"}} -->
    <div class="wp-block-group has-gray-100-background-color has-background" style="border-radius:8px;padding-top:1.5rem;padding-right:1.5rem;padding-bottom:1.5rem;padding-left:1.5rem">
        <!-- wp:heading {"level":3,"fontSize":"medium"} -->
        <h3 class="wp-block-heading has-medium-font-size">Categories</h3>
        <!-- /wp:heading -->
        
        <!-- wp:categories {"showPostCounts":true,"fontSize":"small"} /-->
    </div>
    <!-- /wp:group -->
    
    <!-- wp:group {"style":{"spacing":{"padding":{"top":"1.5rem","bottom":"1.5rem","left":"1.5rem","right":"1.5rem"}},"border":{"radius":"8px"}},"backgroundColor":"gray-100","layout":{"type":"flex","orientation":"vertical"}} -->
    <div class="wp-block-group has-gray-100-background-color has-background" style="border-radius:8px;padding-top:1.5rem;padding-right:1.5rem;padding-bottom:1.5rem;padding-left:1.5rem">
        <!-- wp:heading {"level":3,"fontSize":"medium"} -->
        <h3 class="wp-block-heading has-medium-font-size">Tags</h3>
        <!-- /wp:heading -->
        
        <!-- wp:tag-cloud {"smallestFontSize":"12px","largestFontSize":"16px","numberOfTags":20} /-->
    </div>
    <!-- /wp:group -->
    
    <!-- wp:group {"style":{"spacing":{"padding":{"top":"1.5rem","bottom":"1.5rem","left":"1.5rem","right":"1.5rem"}},"border":{"radius":"8px"}},"backgroundColor":"gray-100","layout":{"type":"flex","orientation":"vertical"}} -->
    <div class="wp-block-group has-gray-100-background-color has-background" style="border-radius:8px;padding-top:1.5rem;padding-right:1.5rem;padding-bottom:1.5rem;padding-left:1.5rem">
        <!-- wp:heading {"level":3,"fontSize":"medium"} -->
        <h3 class="wp-block-heading has-medium-font-size">Archives</h3>
        <!-- /wp:heading -->
        
        <!-- wp:archives {"showPostCounts":true,"fontSize":"small"} /-->
    </div>
    <!-- /wp:group -->
</div>
<!-- /wp:group -->
```

### 4. parts/navigation.html - Main Navigation

```html
<!-- wp:navigation {"ref":1,"textColor":"dark","layout":{"type":"flex","setCascadingProperties":true,"justifyContent":"left","orientation":"horizontal"},"style":{"spacing":{"blockGap":"2rem"},"typography":{"fontWeight":"500"}},"fontSize":"medium"} -->
<!-- wp:navigation-link {"label":"Home","type":"page","id":1,"url":"/","kind":"post-type"} /-->
<!-- wp:navigation-link {"label":"Blog","type":"page","id":2,"url":"/blog","kind":"post-type"} /-->
<!-- wp:navigation-link {"label":"About","type":"page","id":3,"url":"/about","kind":"post-type"} /-->
<!-- wp:navigation-link {"label":"Contact","type":"page","id":4,"url":"/contact","kind":"post-type"} /-->
<!-- wp:navigation-submenu {"label":"Categories","type":"custom","url":"#","kind":"custom"} -->
<!-- wp:navigation-link {"label":"Technology","type":"category","id":1,"url":"/category/technology","kind":"taxonomy"} /-->
<!-- wp:navigation-link {"label":"Lifestyle","type":"category","id":2,"url":"/category/lifestyle","kind":"taxonomy"} /-->
<!-- wp:navigation-link {"label":"Travel","type":"category","id":3,"url":"/category/travel","kind":"taxonomy"} /-->
<!-- /wp:navigation-submenu -->
<!-- /wp:navigation -->
```

### 5. parts/social-links.html - Social Media Links

```html
<!-- wp:social-links {"iconColor":"gray-600","iconColorValue":"var:preset|color|gray-600","iconBackgroundColor":"transparent","iconBackgroundColorValue":"transparent","size":"has-small-icon-size","style":{"spacing":{"blockGap":{"top":"0.5rem","left":"0.5rem"}}},"layout":{"type":"flex","justifyContent":"left"}} -->
<ul class="wp-block-social-links has-small-icon-size has-icon-color has-icon-background-color">
    <!-- wp:social-link {"url":"https://twitter.com/","service":"twitter"} /-->
    <!-- wp:social-link {"url":"https://facebook.com/","service":"facebook"} /-->
    <!-- wp:social-link {"url":"https://instagram.com/","service":"instagram"} /-->
    <!-- wp:social-link {"url":"https://linkedin.com/","service":"linkedin"} /-->
    <!-- wp:social-link {"url":"https://youtube.com/","service":"youtube"} /-->
    <!-- wp:social-link {"url":"/feed","service":"feed"} /-->
</ul>
<!-- /wp:social-links -->
```

### 6. parts/newsletter.html - Newsletter Signup

```html
<!-- wp:group {"style":{"spacing":{"blockGap":"1rem"}},"layout":{"type":"flex","orientation":"vertical"}} -->
<div class="wp-block-group">
    <!-- wp:paragraph {"fontSize":"small","style":{"color":{"text":"var:preset|color|gray-600"}}} -->
    <p class="has-small-font-size" style="color:var(--wp--preset--color--gray-600)">Subscribe to our newsletter for the latest updates and exclusive content.</p>
    <!-- /wp:paragraph -->
    
    <!-- wp:html -->
    <form class="newsletter-form" action="#" method="post">
        <div class="newsletter-input-group">
            <input type="email" name="email" placeholder="Enter your email" required class="newsletter-input">
            <button type="submit" class="newsletter-button">Subscribe</button>
        </div>
        <p class="newsletter-privacy">
            <small>We respect your privacy. Unsubscribe at any time.</small>
        </p>
    </form>
    <!-- /wp:html -->
</div>
<!-- /wp:group -->
```

### 7. parts/breadcrumbs.html - Breadcrumb Navigation

```html
<!-- wp:group {"style":{"spacing":{"padding":{"top":"1rem","bottom":"1rem"}}},"layout":{"type":"constrained"}} -->
<div class="wp-block-group" style="padding-top:1rem;padding-bottom:1rem">
    <!-- wp:group {"style":{"spacing":{"blockGap":"0.5rem"}},"layout":{"type":"flex","flexWrap":"wrap","verticalAlignment":"center"}} -->
    <div class="wp-block-group">
        <!-- wp:paragraph {"fontSize":"small","style":{"color":{"text":"var:preset|color|gray-600"}}} -->
        <p class="has-small-font-size" style="color:var(--wp--preset--color--gray-600)">
            <a href="/">Home</a> 
            <span class="breadcrumb-separator">→</span>
            <span class="current-page">Current Page</span>
        </p>
        <!-- /wp:paragraph -->
    </div>
    <!-- /wp:group -->
</div>
<!-- /wp:group -->
```

### 8. parts/search-form.html - Standalone Search Form

```html
<!-- wp:group {"style":{"spacing":{"padding":{"top":"1rem","bottom":"1rem"}}},"layout":{"type":"constrained","contentSize":"400px"}} -->
<div class="wp-block-group" style="padding-top:1rem;padding-bottom:1rem">
    <!-- wp:search {"label":"Search","showLabel":true,"placeholder":"Search our blog...","width":100,"widthUnit":"%","buttonPosition":"button-inside","buttonUseIcon":true,"style":{"border":{"radius":"4px"}}} /-->
</div>
<!-- /wp:group -->
```

## Template Part CSS Additions

Add these styles to your CSS files for enhanced functionality:

### Newsletter Form Styles
```css
.newsletter-form {
    width: 100%;
}

.newsletter-input-group {
    display: flex;
    gap: 0.5rem;
    margin-bottom: 0.5rem;
}

.newsletter-input {
    flex: 1;
    padding: 0.75rem;
    border: 1px solid var(--wp--preset--color--gray-300);
    border-radius: 4px;
    font-size: var(--wp--preset--font-size--small);
}

.newsletter-button {
    padding: 0.75rem 1rem;
    background: var(--wp--preset--color--primary);
    color: var(--wp--preset--color--white);
    border: none;
    border-radius: 4px;
    font-size: var(--wp--preset--font-size--small);
    font-weight: 500;
    cursor: pointer;
    transition: background-color 0.2s ease;
}

.newsletter-button:hover {
    background: var(--wp--preset--color--secondary);
}

.newsletter-privacy {
    margin: 0;
    font-size: var(--wp--preset--font-size--x-small);
    color: var(--wp--preset--color--gray-500);
}

@media (max-width: 600px) {
    .newsletter-input-group {
        flex-direction: column;
    }
}
```

### Breadcrumb Styles
```css
.breadcrumb-separator {
    margin: 0 0.5rem;
    color: var(--wp--preset--color--gray-400);
}

.current-page {
    color: var(--wp--preset--color--gray-800);
    font-weight: 500;
}
```

## Performance Optimizations

### 1. Efficient Block Usage
- Use specific blocks instead of generic groups where possible
- Minimize nested structures
- Leverage theme.json for styling

### 2. Navigation Optimization
- Use navigation block's built-in caching
- Reference navigation menus by ID
- Enable mobile-friendly responsive navigation

### 3. Widget Alternative Blocks
- Replace traditional widgets with blocks
- Use latest-posts block instead of custom queries
- Optimize image sizes in sidebar widgets

## Mobile Responsiveness

Ensure template parts work well on mobile:

### Header Mobile Optimization
```html
<!-- Add mobile menu toggle capability -->
<!-- wp:navigation {"ref":1,"overlayMenu":"mobile","layout":{"type":"flex","justifyContent":"right"}} /-->
```

### Footer Mobile Stacking
```html
<!-- Columns automatically stack on mobile -->
<!-- wp:columns {"style":{"spacing":{"blockGap":{"top":"2rem","left":"1rem"}}}} -->
```

## Accessibility Features

### 1. Semantic HTML
- Use proper heading hierarchy
- Include skip links where needed
- Ensure keyboard navigation

### 2. ARIA Labels
```html
<!-- wp:navigation {"ariaLabel":"Main navigation"} /-->
```

### 3. Focus Management
```html
<!-- wp:search {"label":"Search","showLabel":true} /-->
```

## Testing Template Parts

### 1. Site Editor Testing
- Edit each template part individually
- Test across different templates
- Verify responsive behavior

### 2. Accessibility Testing
- Test keyboard navigation
- Check screen reader compatibility
- Validate color contrast

### 3. Performance Testing
- Monitor loading times
- Check for unused CSS
- Validate Core Web Vitals

## Verification Checklist

After creating template parts:

- [ ] All template parts created and saved
- [ ] Template parts appear in Site Editor
- [ ] Navigation menus work correctly
- [ ] Social links function properly
- [ ] Newsletter form is styled
- [ ] Mobile responsiveness tested
- [ ] Accessibility standards met

## Next Steps

In Step 6, we'll implement the complete template hierarchy and ensure all templates work together seamlessly.

## Best Practices Applied

1. **Reusability** - Template parts can be used across multiple templates
2. **Consistency** - Uniform styling and structure
3. **Performance** - Optimized block usage and efficient queries
4. **Accessibility** - Proper semantic HTML and ARIA labels
5. **Mobile-First** - Responsive design considerations

## Advanced Features

### Dynamic Content
```html
<!-- wp:post-terms {"term":"category"} /-->
<!-- wp:post-date /-->
<!-- wp:post-author /-->
```

### Conditional Display
```html
<!-- wp:group {"style":{"display":{"mobile":"none"}}} -->
<!-- Desktop-only content -->
<!-- /wp:group -->
```

### Custom Styling
```html
<!-- wp:group {"className":"custom-header-section"} -->
<!-- Custom styled content -->
<!-- /wp:group -->
```

## Troubleshooting

**Template part not updating:**
- Clear site cache
- Check template part slug references
- Verify block markup syntax

**Navigation not working:**
- Create navigation menu in admin
- Assign menu to navigation block
- Check menu item permissions

**Styling not applying:**
- Verify theme.json color/font references
- Check CSS custom property names
- Clear browser cache