# Step 4: Block Template Creation

## Objective
Create HTML block templates for Full Site Editing (FSE) that leverage the theme.json configuration and provide flexible, customizable layouts for different content types.

## What You'll Learn
- Block template structure and syntax
- FSE template hierarchy
- Block markup and attributes
- Template file organization
- Performance-optimized template design

## Understanding Block Templates

Block templates in FSE themes use HTML files with block markup instead of PHP. These templates:
- Use Gutenberg block markup (HTML comments)
- Are fully editable in the Site Editor
- Support all theme.json styling
- Provide better performance than PHP templates
- Enable visual template editing

## Template Hierarchy for FSE

```
templates/
├── index.html          # Main fallback template
├── front-page.html     # Home page template
├── home.html           # Blog posts page
├── single.html         # Single post template
├── page.html           # Single page template
├── archive.html        # Archive template
├── category.html       # Category archive
├── tag.html            # Tag archive
├── author.html         # Author archive
├── date.html           # Date archive
├── search.html         # Search results
├── 404.html            # 404 error page
├── blank.html          # Custom blank template
└── page-no-title.html  # Custom page template
```

## Core Template Files

### 1. templates/index.html - Main Fallback Template

```html
<!-- wp:template-part {"slug":"header","tagName":"header"} /-->

<!-- wp:group {"layout":{"type":"constrained"}} -->
<div class="wp-block-group">
    <!-- wp:query {"queryId":0,"query":{"perPage":10,"pages":0,"offset":0,"postType":"post","order":"desc","orderBy":"date","author":"","search":"","exclude":[],"sticky":"","inherit":true}} -->
    <div class="wp-block-query">
        <!-- wp:post-template {"layout":{"type":"grid","columnCount":2}} -->
        <!-- wp:group {"style":{"spacing":{"blockGap":"1rem"}},"layout":{"type":"flex","orientation":"vertical"}} -->
        <div class="wp-block-group">
            <!-- wp:post-featured-image {"isLink":true,"aspectRatio":"16/9","scale":"cover"} /-->
            
            <!-- wp:group {"style":{"spacing":{"blockGap":"0.5rem"}},"layout":{"type":"flex","orientation":"vertical"}} -->
            <div class="wp-block-group">
                <!-- wp:post-title {"isLink":true,"fontSize":"large"} /-->
                
                <!-- wp:group {"style":{"spacing":{"blockGap":"1rem"}},"layout":{"type":"flex","flexWrap":"nowrap"}} -->
                <div class="wp-block-group">
                    <!-- wp:post-date {"fontSize":"small"} /-->
                    <!-- wp:post-author {"fontSize":"small"} /-->
                </div>
                <!-- /wp:group -->
                
                <!-- wp:post-excerpt {"moreText":"Read more","excerptLength":25} /-->
            </div>
            <!-- /wp:group -->
        </div>
        <!-- /wp:group -->
        <!-- /wp:post-template -->

        <!-- wp:query-pagination {"layout":{"type":"flex","justifyContent":"center"}} -->
        <!-- wp:query-pagination-previous /-->
        <!-- wp:query-pagination-numbers /-->
        <!-- wp:query-pagination-next /-->
        <!-- /wp:query-pagination -->

        <!-- wp:query-no-results -->
        <!-- wp:group {"layout":{"type":"constrained"}} -->
        <div class="wp-block-group">
            <!-- wp:heading {"textAlign":"center","level":2} -->
            <h2 class="wp-block-heading has-text-align-center">No posts found</h2>
            <!-- /wp:heading -->
            
            <!-- wp:paragraph {"align":"center"} -->
            <p class="has-text-align-center">It looks like nothing was found at this location. Try searching for something else.</p>
            <!-- /wp:paragraph -->
            
            <!-- wp:search {"label":"Search","showLabel":false,"placeholder":"Search posts...","buttonPosition":"button-inside","buttonUseIcon":true} /-->
        </div>
        <!-- /wp:group -->
        <!-- /wp:query-no-results -->
    </div>
    <!-- /wp:query -->
</div>
<!-- /wp:group -->

<!-- wp:template-part {"slug":"footer","tagName":"footer"} /-->
```

### 2. templates/single.html - Single Post Template

```html
<!-- wp:template-part {"slug":"header","tagName":"header"} /-->

<!-- wp:group {"layout":{"type":"constrained"}} -->
<div class="wp-block-group">
    <!-- wp:group {"layout":{"type":"constrained","contentSize":"800px"}} -->
    <div class="wp-block-group">
        <!-- wp:post-featured-image {"aspectRatio":"16/9","scale":"cover"} /-->
        
        <!-- wp:group {"style":{"spacing":{"blockGap":"1rem","margin":{"top":"2rem","bottom":"2rem"}}},"layout":{"type":"flex","orientation":"vertical"}} -->
        <div class="wp-block-group" style="margin-top:2rem;margin-bottom:2rem">
            <!-- wp:post-title {"level":1,"fontSize":"xx-large"} /-->
            
            <!-- wp:group {"style":{"spacing":{"blockGap":"1rem"}},"layout":{"type":"flex","flexWrap":"wrap"}} -->
            <div class="wp-block-group">
                <!-- wp:post-date {"fontSize":"small"} /-->
                <!-- wp:post-author {"fontSize":"small"} /-->
                <!-- wp:post-terms {"term":"category","fontSize":"small"} /-->
                <!-- wp:post-terms {"term":"post_tag","fontSize":"small"} /-->
            </div>
            <!-- /wp:group -->
        </div>
        <!-- /wp:group -->
        
        <!-- wp:post-content {"layout":{"type":"constrained"}} /-->
        
        <!-- wp:group {"style":{"spacing":{"margin":{"top":"3rem"}}},"layout":{"type":"constrained"}} -->
        <div class="wp-block-group" style="margin-top:3rem">
            <!-- wp:separator {"className":"is-style-wide"} -->
            <hr class="wp-block-separator has-alpha-channel-opacity is-style-wide"/>
            <!-- /wp:separator -->
            
            <!-- wp:heading {"level":3,"fontSize":"large"} -->
            <h3 class="wp-block-heading has-large-font-size">About the Author</h3>
            <!-- /wp:heading -->
            
            <!-- wp:post-author-biography /-->
        </div>
        <!-- /wp:group -->
        
        <!-- wp:comments -->
        <div class="wp-block-comments">
            <!-- wp:comments-title /-->
            <!-- wp:comment-template -->
            <!-- wp:group {"style":{"spacing":{"margin":{"top":"0","bottom":"2rem"}}}} -->
            <div class="wp-block-group" style="margin-top:0;margin-bottom:2rem">
                <!-- wp:comment-author-name {"fontSize":"small"} /-->
                <!-- wp:comment-date {"fontSize":"small"} /-->
                <!-- wp:comment-content /-->
                <!-- wp:comment-reply-link {"fontSize":"small"} /-->
            </div>
            <!-- /wp:group -->
            <!-- /wp:comment-template -->
            
            <!-- wp:comments-pagination -->
            <!-- wp:comments-pagination-previous /-->
            <!-- wp:comments-pagination-numbers /-->
            <!-- wp:comments-pagination-next /-->
            <!-- /wp:comments-pagination -->
            
            <!-- wp:post-comments-form /-->
        </div>
        <!-- /wp:comments -->
    </div>
    <!-- /wp:group -->
</div>
<!-- /wp:group -->

<!-- wp:template-part {"slug":"footer","tagName":"footer"} /-->
```

### 3. templates/page.html - Page Template

```html
<!-- wp:template-part {"slug":"header","tagName":"header"} /-->

<!-- wp:group {"layout":{"type":"constrained"}} -->
<div class="wp-block-group">
    <!-- wp:group {"layout":{"type":"constrained","contentSize":"800px"}} -->
    <div class="wp-block-group">
        <!-- wp:post-title {"level":1,"fontSize":"xx-large","style":{"spacing":{"margin":{"bottom":"2rem"}}}} /-->
        
        <!-- wp:post-content {"layout":{"type":"constrained"}} /-->
        
        <!-- wp:comments -->
        <div class="wp-block-comments">
            <!-- wp:comments-title /-->
            <!-- wp:comment-template -->
            <!-- wp:group {"style":{"spacing":{"margin":{"top":"0","bottom":"2rem"}}}} -->
            <div class="wp-block-group" style="margin-top:0;margin-bottom:2rem">
                <!-- wp:comment-author-name {"fontSize":"small"} /-->
                <!-- wp:comment-date {"fontSize":"small"} /-->
                <!-- wp:comment-content /-->
                <!-- wp:comment-reply-link {"fontSize":"small"} /-->
            </div>
            <!-- /wp:group -->
            <!-- /wp:comment-template -->
            
            <!-- wp:comments-pagination -->
            <!-- wp:comments-pagination-previous /-->
            <!-- wp:comments-pagination-numbers /-->
            <!-- wp:comments-pagination-next /-->
            <!-- /wp:comments-pagination -->
            
            <!-- wp:post-comments-form /-->
        </div>
        <!-- /wp:comments -->
    </div>
    <!-- /wp:group -->
</div>
<!-- /wp:group -->

<!-- wp:template-part {"slug":"footer","tagName":"footer"} /-->
```

### 4. templates/archive.html - Archive Template

```html
<!-- wp:template-part {"slug":"header","tagName":"header"} /-->

<!-- wp:group {"layout":{"type":"constrained"}} -->
<div class="wp-block-group">
    <!-- wp:query-title {"type":"archive","textAlign":"center","style":{"spacing":{"margin":{"bottom":"3rem"}}}} /-->
    
    <!-- wp:term-description {"textAlign":"center","style":{"spacing":{"margin":{"bottom":"3rem"}}}} /-->
    
    <!-- wp:query {"queryId":0,"query":{"perPage":12,"pages":0,"offset":0,"postType":"post","order":"desc","orderBy":"date","author":"","search":"","exclude":[],"sticky":"","inherit":true}} -->
    <div class="wp-block-query">
        <!-- wp:post-template {"layout":{"type":"grid","columnCount":3}} -->
        <!-- wp:group {"style":{"spacing":{"blockGap":"1rem"}},"layout":{"type":"flex","orientation":"vertical"}} -->
        <div class="wp-block-group">
            <!-- wp:post-featured-image {"isLink":true,"aspectRatio":"4/3","scale":"cover"} /-->
            
            <!-- wp:group {"style":{"spacing":{"blockGap":"0.5rem"}},"layout":{"type":"flex","orientation":"vertical"}} -->
            <div class="wp-block-group">
                <!-- wp:post-title {"isLink":true,"fontSize":"medium"} /-->
                
                <!-- wp:post-date {"fontSize":"small"} /-->
                
                <!-- wp:post-excerpt {"moreText":"Read more","excerptLength":15} /-->
            </div>
            <!-- /wp:group -->
        </div>
        <!-- /wp:group -->
        <!-- /wp:post-template -->

        <!-- wp:query-pagination {"layout":{"type":"flex","justifyContent":"center"}} -->
        <!-- wp:query-pagination-previous /-->
        <!-- wp:query-pagination-numbers /-->
        <!-- wp:query-pagination-next /-->
        <!-- /wp:query-pagination -->

        <!-- wp:query-no-results -->
        <!-- wp:group {"layout":{"type":"constrained"}} -->
        <div class="wp-block-group">
            <!-- wp:heading {"textAlign":"center","level":2} -->
            <h2 class="wp-block-heading has-text-align-center">No posts found</h2>
            <!-- /wp:heading -->
            
            <!-- wp:paragraph {"align":"center"} -->
            <p class="has-text-align-center">No posts were found in this archive.</p>
            <!-- /wp:paragraph -->
        </div>
        <!-- /wp:group -->
        <!-- /wp:query-no-results -->
    </div>
    <!-- /wp:query -->
</div>
<!-- /wp:group -->

<!-- wp:template-part {"slug":"footer","tagName":"footer"} /-->
```

### 5. templates/search.html - Search Results Template

```html
<!-- wp:template-part {"slug":"header","tagName":"header"} /-->

<!-- wp:group {"layout":{"type":"constrained"}} -->
<div class="wp-block-group">
    <!-- wp:query-title {"type":"search","textAlign":"center","style":{"spacing":{"margin":{"bottom":"2rem"}}}} /-->
    
    <!-- wp:search {"label":"Search","showLabel":false,"placeholder":"Search for more content...","width":50,"widthUnit":"%","buttonPosition":"button-inside","buttonUseIcon":true,"style":{"spacing":{"margin":{"bottom":"3rem"}}}} /-->
    
    <!-- wp:query {"queryId":0,"query":{"perPage":10,"pages":0,"offset":0,"postType":"post","order":"desc","orderBy":"date","author":"","search":"","exclude":[],"sticky":"","inherit":true}} -->
    <div class="wp-block-query">
        <!-- wp:post-template -->
        <!-- wp:group {"style":{"spacing":{"blockGap":"1rem","padding":{"bottom":"2rem"}},"border":{"bottom":{"color":"var:preset|color|gray-200","width":"1px","style":"solid"}}},"layout":{"type":"flex","orientation":"vertical"}} -->
        <div class="wp-block-group" style="border-bottom-color:var(--wp--preset--color--gray-200);border-bottom-style:solid;border-bottom-width:1px;padding-bottom:2rem">
            <!-- wp:post-title {"isLink":true,"fontSize":"large"} /-->
            
            <!-- wp:group {"style":{"spacing":{"blockGap":"1rem"}},"layout":{"type":"flex","flexWrap":"wrap"}} -->
            <div class="wp-block-group">
                <!-- wp:post-date {"fontSize":"small"} /-->
                <!-- wp:post-terms {"term":"category","fontSize":"small"} /-->
            </div>
            <!-- /wp:group -->
            
            <!-- wp:post-excerpt {"moreText":"Read more","excerptLength":30} /-->
        </div>
        <!-- /wp:group -->
        <!-- /wp:post-template -->

        <!-- wp:query-pagination {"layout":{"type":"flex","justifyContent":"center"}} -->
        <!-- wp:query-pagination-previous /-->
        <!-- wp:query-pagination-numbers /-->
        <!-- wp:query-pagination-next /-->
        <!-- /wp:query-pagination -->

        <!-- wp:query-no-results -->
        <!-- wp:group {"layout":{"type":"constrained"}} -->
        <div class="wp-block-group">
            <!-- wp:heading {"textAlign":"center","level":2} -->
            <h2 class="wp-block-heading has-text-align-center">No results found</h2>
            <!-- /wp:heading -->
            
            <!-- wp:paragraph {"align":"center"} -->
            <p class="has-text-align-center">Sorry, but nothing matched your search terms. Please try again with different keywords.</p>
            <!-- /wp:paragraph -->
        </div>
        <!-- /wp:group -->
        <!-- /wp:query-no-results -->
    </div>
    <!-- /wp:query -->
</div>
<!-- /wp:group -->

<!-- wp:template-part {"slug":"footer","tagName":"footer"} /-->
```

### 6. templates/404.html - 404 Error Template

```html
<!-- wp:template-part {"slug":"header","tagName":"header"} /-->

<!-- wp:group {"layout":{"type":"constrained","contentSize":"600px"}} -->
<div class="wp-block-group">
    <!-- wp:group {"style":{"spacing":{"padding":{"top":"4rem","bottom":"4rem"}}},"layout":{"type":"flex","orientation":"vertical","justifyContent":"center"}} -->
    <div class="wp-block-group" style="padding-top:4rem;padding-bottom:4rem">
        <!-- wp:heading {"textAlign":"center","level":1,"fontSize":"huge"} -->
        <h1 class="wp-block-heading has-text-align-center has-huge-font-size">404</h1>
        <!-- /wp:heading -->
        
        <!-- wp:heading {"textAlign":"center","level":2,"fontSize":"x-large"} -->
        <h2 class="wp-block-heading has-text-align-center has-x-large-font-size">Page Not Found</h2>
        <!-- /wp:heading -->
        
        <!-- wp:paragraph {"align":"center","fontSize":"medium"} -->
        <p class="has-text-align-center has-medium-font-size">The page you are looking for doesn't exist or has been moved.</p>
        <!-- /wp:paragraph -->
        
        <!-- wp:search {"label":"Search","showLabel":false,"placeholder":"Search our site...","width":75,"widthUnit":"%","buttonPosition":"button-inside","buttonUseIcon":true,"style":{"spacing":{"margin":{"top":"2rem","bottom":"2rem"}}}} /-->
        
        <!-- wp:buttons {"layout":{"type":"flex","justifyContent":"center"}} -->
        <div class="wp-block-buttons">
            <!-- wp:button -->
            <div class="wp-block-button"><a class="wp-block-button__link wp-element-button" href="/">Go Home</a></div>
            <!-- /wp:button -->
        </div>
        <!-- /wp:buttons -->
    </div>
    <!-- /wp:group -->
</div>
<!-- /wp:group -->

<!-- wp:template-part {"slug":"footer","tagName":"footer"} /-->
```

### 7. templates/front-page.html - Home Page Template

```html
<!-- wp:template-part {"slug":"header","tagName":"header"} /-->

<!-- wp:group {"layout":{"type":"constrained"}} -->
<div class="wp-block-group">
    <!-- wp:cover {"url":"","id":0,"dimRatio":40,"minHeight":400,"contentPosition":"center center","isDark":false,"style":{"spacing":{"margin":{"bottom":"4rem"}}}} -->
    <div class="wp-block-cover" style="margin-bottom:4rem;min-height:400px">
        <span aria-hidden="true" class="wp-block-cover__background has-background-dim-40"></span>
        <div class="wp-block-cover__inner-container">
            <!-- wp:heading {"textAlign":"center","level":1,"fontSize":"huge"} -->
            <h1 class="wp-block-heading has-text-align-center has-huge-font-size">Welcome to Our Blog</h1>
            <!-- /wp:heading -->
            
            <!-- wp:paragraph {"align":"center","fontSize":"large"} -->
            <p class="has-text-align-center has-large-font-size">Discover amazing content and insights from our community</p>
            <!-- /wp:paragraph -->
            
            <!-- wp:buttons {"layout":{"type":"flex","justifyContent":"center"}} -->
            <div class="wp-block-buttons">
                <!-- wp:button {"style":{"spacing":{"padding":{"top":"1rem","bottom":"1rem","left":"2rem","right":"2rem"}}}} -->
                <div class="wp-block-button"><a class="wp-block-button__link wp-element-button" style="padding-top:1rem;padding-right:2rem;padding-bottom:1rem;padding-left:2rem">Start Reading</a></div>
                <!-- /wp:button -->
            </div>
            <!-- /wp:buttons -->
        </div>
    </div>
    <!-- /wp:cover -->
    
    <!-- wp:heading {"textAlign":"center","level":2,"fontSize":"x-large","style":{"spacing":{"margin":{"bottom":"3rem"}}}} -->
    <h2 class="wp-block-heading has-text-align-center has-x-large-font-size" style="margin-bottom:3rem">Latest Posts</h2>
    <!-- /wp:heading -->
    
    <!-- wp:query {"queryId":0,"query":{"perPage":6,"pages":0,"offset":0,"postType":"post","order":"desc","orderBy":"date","author":"","search":"","exclude":[],"sticky":"exclude","inherit":false}} -->
    <div class="wp-block-query">
        <!-- wp:post-template {"layout":{"type":"grid","columnCount":3}} -->
        <!-- wp:group {"style":{"spacing":{"blockGap":"1rem"}},"layout":{"type":"flex","orientation":"vertical"}} -->
        <div class="wp-block-group">
            <!-- wp:post-featured-image {"isLink":true,"aspectRatio":"16/9","scale":"cover"} /-->
            
            <!-- wp:group {"style":{"spacing":{"blockGap":"0.5rem"}},"layout":{"type":"flex","orientation":"vertical"}} -->
            <div class="wp-block-group">
                <!-- wp:post-title {"isLink":true,"fontSize":"medium"} /-->
                
                <!-- wp:post-date {"fontSize":"small"} /-->
                
                <!-- wp:post-excerpt {"moreText":"Read more","excerptLength":20} /-->
            </div>
            <!-- /wp:group -->
        </div>
        <!-- /wp:group -->
        <!-- /wp:post-template -->
    </div>
    <!-- /wp:query -->
    
    <!-- wp:buttons {"layout":{"type":"flex","justifyContent":"center"},"style":{"spacing":{"margin":{"top":"3rem"}}}} -->
    <div class="wp-block-buttons" style="margin-top:3rem">
        <!-- wp:button {"style":{"border":{"radius":"4px"}}} -->
        <div class="wp-block-button"><a class="wp-block-button__link wp-element-button" style="border-radius:4px" href="/blog">View All Posts</a></div>
        <!-- /wp:button -->
    </div>
    <!-- /wp:buttons -->
</div>
<!-- /wp:group -->

<!-- wp:template-part {"slug":"footer","tagName":"footer"} /-->
```

### 8. Custom Templates

#### templates/blank.html - Blank Canvas Template

```html
<!-- wp:post-content {"layout":{"type":"constrained"}} /-->
```

#### templates/page-no-title.html - Page Without Title

```html
<!-- wp:template-part {"slug":"header","tagName":"header"} /-->

<!-- wp:group {"layout":{"type":"constrained"}} -->
<div class="wp-block-group">
    <!-- wp:post-content {"layout":{"type":"constrained"}} /-->
</div>
<!-- /wp:group -->

<!-- wp:template-part {"slug":"footer","tagName":"footer"} /-->
```

## Performance Optimizations

### 1. Optimized Query Blocks
- Use specific `perPage` values
- Set appropriate `inherit` flags
- Exclude sticky posts where needed

### 2. Efficient Layout Structures
- Use CSS Grid for post layouts
- Minimize nested group blocks
- Leverage theme.json spacing

### 3. Image Optimization
- Set aspect ratios for consistency
- Use `scale="cover"` for featured images
- Enable lazy loading through block attributes

## Testing Templates

### 1. Site Editor Testing
```bash
# Access Site Editor
wp-admin -> Appearance -> Site Editor -> Templates
```

### 2. Template Validation
- Check block markup syntax
- Verify all template parts exist
- Test responsive behavior
- Validate accessibility

### 3. Performance Testing
- Check template rendering speed
- Monitor query performance
- Validate Core Web Vitals

## Verification Checklist

After creating templates:

- [ ] All template files created
- [ ] Block markup is valid
- [ ] Templates appear in Site Editor
- [ ] Template parts are referenced correctly
- [ ] Responsive layout works
- [ ] Query blocks function properly
- [ ] Custom templates are selectable

## Next Steps

In Step 5, we'll create template parts (header, footer, sidebar) that are referenced in these templates.

## Best Practices Applied

1. **Semantic HTML** - Proper use of header, main, footer elements
2. **Performance** - Optimized queries and layouts
3. **Accessibility** - Proper heading hierarchy and ARIA labels
4. **Responsive Design** - CSS Grid and flexible layouts
5. **User Experience** - Clear navigation and content hierarchy

## Advanced Features

### Custom Query Variations
```html
<!-- Featured posts query -->
<!-- wp:query {"queryId":1,"query":{"perPage":3,"postType":"post","metaQuery":{"relation":"AND","0":{"key":"featured","value":"1","compare":"="}}}} -->
```

### Conditional Block Display
```html
<!-- Show only if has featured image -->
<!-- wp:post-featured-image {"isLink":true} /-->
```

### Block Variations
```html
<!-- Different layout for mobile -->
<!-- wp:post-template {"layout":{"type":"grid","columnCount":1}} -->
```

## Troubleshooting

**Template not appearing:**
- Check file naming convention
- Verify block markup syntax
- Clear WordPress cache

**Block errors in editor:**
- Validate HTML comment format
- Check block attribute syntax
- Test individual blocks first

**Performance issues:**
- Optimize query parameters
- Reduce nested blocks
- Use appropriate image sizes