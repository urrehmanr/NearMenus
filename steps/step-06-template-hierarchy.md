# Step 6: Template Hierarchy Implementation

## Objective
Implement and optimize the complete WordPress template hierarchy for FSE themes, ensuring seamless integration between all templates, template parts, and proper fallback systems for maximum compatibility and performance.

## What You'll Learn
- WordPress FSE template hierarchy
- Template fallback mechanisms
- Custom template creation
- Template organization best practices
- Performance optimization strategies
- Cross-template consistency

## WordPress FSE Template Hierarchy

Understanding the template hierarchy is crucial for FSE themes:

```
Template Priority (Highest to Lowest):
├── Custom Templates (page-custom.html)
├── Specific Templates (single-post.html, page-about.html)  
├── General Templates (single.html, page.html)
├── Archive Templates (archive-category.html, archive.html)
├── Fallback Templates (index.html)
```

## Complete Template Structure

### Core Templates (Required)

#### 1. templates/index.html - Ultimate Fallback
```html
<!-- wp:template-part {"slug":"header","tagName":"header"} /-->

<!-- wp:group {"layout":{"type":"constrained"},"style":{"spacing":{"margin":{"top":"2rem","bottom":"2rem"}}}} -->
<div class="wp-block-group" style="margin-top:2rem;margin-bottom:2rem">
    <!-- wp:query-title {"type":"archive","textAlign":"center","showPrefix":false,"style":{"spacing":{"margin":{"bottom":"2rem"}}}} /-->
    
    <!-- wp:query {"queryId":0,"query":{"perPage":10,"pages":0,"offset":0,"postType":"post","order":"desc","orderBy":"date","author":"","search":"","exclude":[],"sticky":"","inherit":true}} -->
    <div class="wp-block-query">
        <!-- wp:post-template {"layout":{"type":"grid","columnCount":2}} -->
        <!-- wp:group {"style":{"spacing":{"blockGap":"1rem","padding":{"all":"1.5rem"}},"border":{"radius":"8px"}},"backgroundColor":"white","className":"post-card"} -->
        <div class="wp-block-group post-card has-white-background-color has-background" style="border-radius:8px;padding:1.5rem">
            <!-- wp:post-featured-image {"isLink":true,"aspectRatio":"16/9","scale":"cover","style":{"border":{"radius":"4px"}}} /-->
            
            <!-- wp:group {"style":{"spacing":{"blockGap":"0.75rem"}},"layout":{"type":"flex","orientation":"vertical"}} -->
            <div class="wp-block-group">
                <!-- wp:post-title {"isLink":true,"fontSize":"medium","style":{"typography":{"lineHeight":"1.3"}}} /-->
                
                <!-- wp:group {"style":{"spacing":{"blockGap":"1rem"}},"layout":{"type":"flex","flexWrap":"wrap","justifyContent":"space-between"}} -->
                <div class="wp-block-group">
                    <!-- wp:post-date {"fontSize":"small","style":{"color":{"text":"var:preset|color|gray-600"}}} /-->
                    <!-- wp:post-terms {"term":"category","fontSize":"small"} /-->
                </div>
                <!-- /wp:group -->
                
                <!-- wp:post-excerpt {"moreText":"Continue reading","excerptLength":25,"style":{"color":{"text":"var:preset|color|gray-700"}}} /-->
            </div>
            <!-- /wp:group -->
        </div>
        <!-- /wp:group -->
        <!-- /wp:post-template -->

        <!-- wp:query-pagination {"layout":{"type":"flex","justifyContent":"center"},"style":{"spacing":{"margin":{"top":"3rem"}}}} -->
        <!-- wp:query-pagination-previous {"label":"← Previous"} /-->
        <!-- wp:query-pagination-numbers /-->
        <!-- wp:query-pagination-next {"label":"Next →"} /-->
        <!-- /wp:query-pagination -->

        <!-- wp:query-no-results -->
        <!-- wp:group {"layout":{"type":"constrained","contentSize":"600px"}} -->
        <div class="wp-block-group">
            <!-- wp:heading {"textAlign":"center","level":2,"fontSize":"large"} -->
            <h2 class="wp-block-heading has-text-align-center has-large-font-size">No content found</h2>
            <!-- /wp:heading -->
            
            <!-- wp:paragraph {"align":"center","style":{"color":{"text":"var:preset|color|gray-600"}}} -->
            <p class="has-text-align-center" style="color:var(--wp--preset--color--gray-600)">We couldn't find any content matching your request.</p>
            <!-- /wp:paragraph -->
            
            <!-- wp:buttons {"layout":{"type":"flex","justifyContent":"center"},"style":{"spacing":{"margin":{"top":"2rem"}}}} -->
            <div class="wp-block-buttons" style="margin-top:2rem">
                <!-- wp:button -->
                <div class="wp-block-button"><a class="wp-block-button__link wp-element-button" href="/">Return Home</a></div>
                <!-- /wp:button -->
            </div>
            <!-- /wp:buttons -->
        </div>
        <!-- /wp:group -->
        <!-- /wp:query-no-results -->
    </div>
    <!-- /wp:query -->
</div>
<!-- /wp:group -->

<!-- wp:template-part {"slug":"footer","tagName":"footer"} /-->
```

### Page Templates

#### 2. templates/home.html - Blog Posts Page
```html
<!-- wp:template-part {"slug":"header","tagName":"header"} /-->

<!-- wp:group {"layout":{"type":"constrained"},"style":{"spacing":{"margin":{"top":"2rem","bottom":"2rem"}}}} -->
<div class="wp-block-group" style="margin-top:2rem;margin-bottom:2rem">
    <!-- wp:heading {"textAlign":"center","level":1,"fontSize":"xx-large","style":{"spacing":{"margin":{"bottom":"3rem"}}}} -->
    <h1 class="wp-block-heading has-text-align-center has-xx-large-font-size" style="margin-bottom:3rem">Latest Blog Posts</h1>
    <!-- /wp:heading -->
    
    <!-- wp:columns {"style":{"spacing":{"blockGap":{"top":"2rem","left":"3rem"}}}} -->
    <div class="wp-block-columns">
        <!-- wp:column {"width":"70%"} -->
        <div class="wp-block-column" style="flex-basis:70%">
            <!-- wp:query {"queryId":0,"query":{"perPage":6,"pages":0,"offset":0,"postType":"post","order":"desc","orderBy":"date","author":"","search":"","exclude":[],"sticky":"exclude","inherit":false}} -->
            <div class="wp-block-query">
                <!-- wp:post-template -->
                <!-- wp:group {"style":{"spacing":{"blockGap":"1.5rem","padding":{"bottom":"2rem","top":"0"},"margin":{"bottom":"2rem"}},"border":{"bottom":{"color":"var:preset|color|gray-200","width":"1px","style":"solid"}}},"layout":{"type":"flex","orientation":"vertical"}} -->
                <div class="wp-block-group" style="border-bottom-color:var(--wp--preset--color--gray-200);border-bottom-style:solid;border-bottom-width:1px;margin-bottom:2rem;padding-top:0;padding-bottom:2rem">
                    <!-- wp:post-featured-image {"isLink":true,"aspectRatio":"16/9","scale":"cover","style":{"border":{"radius":"8px"}}} /-->
                    
                    <!-- wp:group {"style":{"spacing":{"blockGap":"1rem"}},"layout":{"type":"flex","orientation":"vertical"}} -->
                    <div class="wp-block-group">
                        <!-- wp:post-title {"isLink":true,"fontSize":"large","style":{"typography":{"lineHeight":"1.2"}}} /-->
                        
                        <!-- wp:group {"style":{"spacing":{"blockGap":"1rem"}},"layout":{"type":"flex","flexWrap":"wrap"}} -->
                        <div class="wp-block-group">
                            <!-- wp:post-date {"fontSize":"small","style":{"color":{"text":"var:preset|color|gray-600"}}} /-->
                            <!-- wp:post-author {"fontSize":"small","style":{"color":{"text":"var:preset|color|gray-600"}}} /-->
                            <!-- wp:post-terms {"term":"category","fontSize":"small"} /-->
                        </div>
                        <!-- /wp:group -->
                        
                        <!-- wp:post-excerpt {"moreText":"Read full article","excerptLength":30} /-->
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
                <!-- wp:paragraph {"align":"center"} -->
                <p class="has-text-align-center">No blog posts found.</p>
                <!-- /wp:paragraph -->
                <!-- /wp:query-no-results -->
            </div>
            <!-- /wp:query -->
        </div>
        <!-- /wp:column -->
        
        <!-- wp:column {"width":"30%"} -->
        <div class="wp-block-column" style="flex-basis:30%">
            <!-- wp:template-part {"slug":"sidebar"} /-->
        </div>
        <!-- /wp:column -->
    </div>
    <!-- /wp:columns -->
</div>
<!-- /wp:group -->

<!-- wp:template-part {"slug":"footer","tagName":"footer"} /-->
```

### Archive Templates

#### 3. templates/category.html - Category Archive
```html
<!-- wp:template-part {"slug":"header","tagName":"header"} /-->

<!-- wp:group {"layout":{"type":"constrained"},"style":{"spacing":{"margin":{"top":"2rem","bottom":"2rem"}}}} -->
<div class="wp-block-group" style="margin-top:2rem;margin-bottom:2rem">
    <!-- wp:group {"style":{"spacing":{"margin":{"bottom":"3rem"},"padding":{"all":"2rem"}},"border":{"radius":"8px"}},"backgroundColor":"gray-100","layout":{"type":"constrained","contentSize":"800px"}} -->
    <div class="wp-block-group has-gray-100-background-color has-background" style="border-radius:8px;margin-bottom:3rem;padding:2rem">
        <!-- wp:query-title {"type":"archive","textAlign":"center","fontSize":"xx-large","style":{"spacing":{"margin":{"bottom":"1rem"}}}} /-->
        
        <!-- wp:term-description {"textAlign":"center","fontSize":"medium","style":{"color":{"text":"var:preset|color|gray-700"}}} /-->
        
        <!-- wp:group {"layout":{"type":"flex","justifyContent":"center"},"style":{"spacing":{"margin":{"top":"1.5rem"}}}} -->
        <div class="wp-block-group" style="margin-top:1.5rem">
            <!-- wp:post-terms {"term":"category","separator":" • ","fontSize":"small"} /-->
        </div>
        <!-- /wp:group -->
    </div>
    <!-- /wp:group -->
    
    <!-- wp:columns {"style":{"spacing":{"blockGap":{"top":"2rem","left":"3rem"}}}} -->
    <div class="wp-block-columns">
        <!-- wp:column {"width":"70%"} -->
        <div class="wp-block-column" style="flex-basis:70%">
            <!-- wp:query {"queryId":0,"query":{"perPage":12,"pages":0,"offset":0,"postType":"post","order":"desc","orderBy":"date","author":"","search":"","exclude":[],"sticky":"","inherit":true}} -->
            <div class="wp-block-query">
                <!-- wp:post-template {"layout":{"type":"grid","columnCount":2}} -->
                <!-- wp:group {"style":{"spacing":{"blockGap":"1rem","padding":{"all":"1.5rem"}},"border":{"radius":"8px"}},"backgroundColor":"white","className":"category-post-card"} -->
                <div class="wp-block-group category-post-card has-white-background-color has-background" style="border-radius:8px;padding:1.5rem">
                    <!-- wp:post-featured-image {"isLink":true,"aspectRatio":"4/3","scale":"cover","style":{"border":{"radius":"4px"}}} /-->
                    
                    <!-- wp:group {"style":{"spacing":{"blockGap":"0.75rem"}},"layout":{"type":"flex","orientation":"vertical"}} -->
                    <div class="wp-block-group">
                        <!-- wp:post-title {"isLink":true,"fontSize":"medium","style":{"typography":{"lineHeight":"1.3"}}} /-->
                        
                        <!-- wp:post-date {"fontSize":"small","style":{"color":{"text":"var:preset|color|gray-600"}}} /-->
                        
                        <!-- wp:post-excerpt {"moreText":"Read more","excerptLength":20} /-->
                    </div>
                    <!-- /wp:group -->
                </div>
                <!-- /wp:group -->
                <!-- /wp:post-template -->

                <!-- wp:query-pagination {"layout":{"type":"flex","justifyContent":"center"},"style":{"spacing":{"margin":{"top":"3rem"}}}} -->
                <!-- wp:query-pagination-previous /-->
                <!-- wp:query-pagination-numbers /-->
                <!-- wp:query-pagination-next /-->
                <!-- /wp:query-pagination -->

                <!-- wp:query-no-results -->
                <!-- wp:group {"layout":{"type":"constrained"}} -->
                <div class="wp-block-group">
                    <!-- wp:heading {"textAlign":"center","level":3} -->
                    <h3 class="wp-block-heading has-text-align-center">No posts in this category</h3>
                    <!-- /wp:heading -->
                    
                    <!-- wp:paragraph {"align":"center"} -->
                    <p class="has-text-align-center">Check back later for new content!</p>
                    <!-- /wp:paragraph -->
                </div>
                <!-- /wp:group -->
                <!-- /wp:query-no-results -->
            </div>
            <!-- /wp:query -->
        </div>
        <!-- /wp:column -->
        
        <!-- wp:column {"width":"30%"} -->
        <div class="wp-block-column" style="flex-basis:30%">
            <!-- wp:template-part {"slug":"sidebar"} /-->
        </div>
        <!-- /wp:column -->
    </div>
    <!-- /wp:columns -->
</div>
<!-- /wp:group -->

<!-- wp:template-part {"slug":"footer","tagName":"footer"} /-->
```

### Custom Templates

#### 4. templates/page-contact.html - Contact Page Template
```html
<!-- wp:template-part {"slug":"header","tagName":"header"} /-->

<!-- wp:group {"layout":{"type":"constrained","contentSize":"800px"},"style":{"spacing":{"margin":{"top":"2rem","bottom":"2rem"}}}} -->
<div class="wp-block-group" style="margin-top:2rem;margin-bottom:2rem">
    <!-- wp:post-title {"level":1,"fontSize":"xx-large","textAlign":"center","style":{"spacing":{"margin":{"bottom":"2rem"}}}} /-->
    
    <!-- wp:post-content {"layout":{"type":"constrained"}} /-->
    
    <!-- wp:group {"style":{"spacing":{"margin":{"top":"3rem"},"padding":{"all":"2rem"}},"border":{"radius":"8px"}},"backgroundColor":"gray-100","layout":{"type":"constrained"}} -->
    <div class="wp-block-group has-gray-100-background-color has-background" style="border-radius:8px;margin-top:3rem;padding:2rem">
        <!-- wp:heading {"level":2,"fontSize":"large","textAlign":"center","style":{"spacing":{"margin":{"bottom":"2rem"}}}} -->
        <h2 class="wp-block-heading has-text-align-center has-large-font-size" style="margin-bottom:2rem">Get In Touch</h2>
        <!-- /wp:heading -->
        
        <!-- wp:columns -->
        <div class="wp-block-columns">
            <!-- wp:column -->
            <div class="wp-block-column">
                <!-- wp:heading {"level":3,"fontSize":"medium"} -->
                <h3 class="wp-block-heading has-medium-font-size">Contact Information</h3>
                <!-- /wp:heading -->
                
                <!-- wp:paragraph {"fontSize":"small"} -->
                <p class="has-small-font-size"><strong>Email:</strong> hello@modernblog2025.com</p>
                <!-- /wp:paragraph -->
                
                <!-- wp:paragraph {"fontSize":"small"} -->
                <p class="has-small-font-size"><strong>Phone:</strong> +1 (555) 123-4567</p>
                <!-- /wp:paragraph -->
                
                <!-- wp:paragraph {"fontSize":"small"} -->
                <p class="has-small-font-size"><strong>Address:</strong> 123 Blog Street, Content City, CC 12345</p>
                <!-- /wp:paragraph -->
                
                <!-- wp:template-part {"slug":"social-links"} /-->
            </div>
            <!-- /wp:column -->
            
            <!-- wp:column -->
            <div class="wp-block-column">
                <!-- wp:heading {"level":3,"fontSize":"medium"} -->
                <h3 class="wp-block-heading has-medium-font-size">Send a Message</h3>
                <!-- /wp:heading -->
                
                <!-- wp:html -->
                <form class="contact-form" action="#" method="post">
                    <div class="form-group">
                        <label for="name">Name *</label>
                        <input type="text" id="name" name="name" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="email">Email *</label>
                        <input type="email" id="email" name="email" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="subject">Subject</label>
                        <input type="text" id="subject" name="subject">
                    </div>
                    
                    <div class="form-group">
                        <label for="message">Message *</label>
                        <textarea id="message" name="message" rows="5" required></textarea>
                    </div>
                    
                    <button type="submit" class="contact-submit">Send Message</button>
                </form>
                <!-- /wp:html -->
            </div>
            <!-- /wp:column -->
        </div>
        <!-- /wp:columns -->
    </div>
    <!-- /wp:group -->
</div>
<!-- /wp:group -->

<!-- wp:template-part {"slug":"footer","tagName":"footer"} /-->
```

### Author and Date Archives

#### 5. templates/author.html - Author Archive
```html
<!-- wp:template-part {"slug":"header","tagName":"header"} /-->

<!-- wp:group {"layout":{"type":"constrained"},"style":{"spacing":{"margin":{"top":"2rem","bottom":"2rem"}}}} -->
<div class="wp-block-group" style="margin-top:2rem;margin-bottom:2rem">
    <!-- wp:group {"style":{"spacing":{"margin":{"bottom":"3rem"},"padding":{"all":"2rem"}},"border":{"radius":"8px"}},"backgroundColor":"gray-100","layout":{"type":"constrained","contentSize":"600px"}} -->
    <div class="wp-block-group has-gray-100-background-color has-background" style="border-radius:8px;margin-bottom:3rem;padding:2rem">
        <!-- wp:query-title {"type":"archive","textAlign":"center","fontSize":"xx-large","style":{"spacing":{"margin":{"bottom":"1rem"}}}} /-->
        
        <!-- wp:post-author-biography {"textAlign":"center","fontSize":"medium","style":{"color":{"text":"var:preset|color|gray-700"}}} /-->
        
        <!-- wp:template-part {"slug":"social-links"} /-->
    </div>
    <!-- /wp:group -->
    
    <!-- wp:query {"queryId":0,"query":{"perPage":10,"pages":0,"offset":0,"postType":"post","order":"desc","orderBy":"date","author":"","search":"","exclude":[],"sticky":"","inherit":true}} -->
    <div class="wp-block-query">
        <!-- wp:post-template {"layout":{"type":"grid","columnCount":2}} -->
        <!-- wp:group {"style":{"spacing":{"blockGap":"1rem","padding":{"all":"1.5rem"}},"border":{"radius":"8px"}},"backgroundColor":"white"} -->
        <div class="wp-block-group has-white-background-color has-background" style="border-radius:8px;padding:1.5rem">
            <!-- wp:post-featured-image {"isLink":true,"aspectRatio":"16/9","scale":"cover","style":{"border":{"radius":"4px"}}} /-->
            
            <!-- wp:group {"style":{"spacing":{"blockGap":"0.75rem"}},"layout":{"type":"flex","orientation":"vertical"}} -->
            <div class="wp-block-group">
                <!-- wp:post-title {"isLink":true,"fontSize":"medium"} /-->
                
                <!-- wp:post-date {"fontSize":"small","style":{"color":{"text":"var:preset|color|gray-600"}}} /-->
                
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
        <!-- wp:paragraph {"align":"center"} -->
        <p class="has-text-align-center">No posts found for this author.</p>
        <!-- /wp:paragraph -->
        <!-- /wp:query-no-results -->
    </div>
    <!-- /wp:query -->
</div>
<!-- /wp:group -->

<!-- wp:template-part {"slug":"footer","tagName":"footer"} /-->
```

## Template Hierarchy Best Practices

### 1. Consistent Structure
All templates should follow this pattern:
```html
<!-- wp:template-part {"slug":"header","tagName":"header"} /-->
<!-- Main content area with consistent spacing -->
<!-- wp:template-part {"slug":"footer","tagName":"footer"} /-->
```

### 2. Semantic HTML Elements
Use proper semantic elements in template parts:
```html
<!-- wp:template-part {"slug":"header","tagName":"header"} /-->
<!-- wp:template-part {"slug":"footer","tagName":"footer"} /-->
<!-- wp:template-part {"slug":"sidebar","tagName":"aside"} /-->
```

### 3. Performance Optimization
- Use specific queries rather than inheriting when possible
- Optimize featured image aspect ratios
- Implement proper pagination

### 4. Mobile-First Design
- Use responsive column layouts
- Implement mobile-friendly navigation
- Ensure touch-friendly interaction areas

## Template Organization Strategy

### File Naming Convention
```
templates/
├── index.html              # Ultimate fallback
├── front-page.html         # Static front page
├── home.html              # Posts page
├── single.html            # Single post
├── page.html              # Single page
├── archive.html           # Generic archive
├── category.html          # Category archive
├── tag.html               # Tag archive
├── author.html            # Author archive
├── date.html              # Date archive
├── search.html            # Search results
├── 404.html               # Error page
├── page-{slug}.html       # Specific page templates
└── single-{post-type}.html # Specific post type templates
```

### Custom Template Registration
Update `theme.json` to register custom templates:
```json
{
    "customTemplates": [
        {
            "name": "page-contact",
            "title": "Contact Page",
            "postTypes": ["page"]
        },
        {
            "name": "page-about",
            "title": "About Page",
            "postTypes": ["page"]
        },
        {
            "name": "blank-canvas",
            "title": "Blank Canvas",
            "postTypes": ["page", "post"]
        }
    ]
}
```

## Testing the Template Hierarchy

### 1. Functional Testing
- Test each template type individually
- Verify fallback mechanisms work
- Check template part references

### 2. Performance Testing
- Monitor loading times for each template
- Check query efficiency
- Validate Core Web Vitals

### 3. Responsive Testing
- Test on various screen sizes
- Verify mobile navigation works
- Check touch interactions

### 4. Accessibility Testing
- Validate semantic HTML structure
- Test keyboard navigation
- Check screen reader compatibility

## Verification Checklist

After implementing the template hierarchy:

- [ ] All required templates created
- [ ] Template fallbacks work correctly
- [ ] Custom templates are selectable
- [ ] Template parts are consistent
- [ ] Mobile responsiveness verified
- [ ] Performance benchmarks met
- [ ] Accessibility standards followed
- [ ] SEO structure implemented

## Next Steps

In Step 7, we'll create the CSS architecture and global styles to make these templates visually appealing and performant.

## Advanced Template Features

### Conditional Content
```html
<!-- wp:group {"style":{"display":{"desktop":"block","mobile":"none"}}} -->
<!-- Desktop-only content -->
<!-- /wp:group -->
```

### Dynamic Queries
```html
<!-- wp:query {"queryId":1,"query":{"inherit":false,"perPage":3,"postType":"post","metaQuery":{"featured":{"key":"featured","value":"1"}}}} -->
```

### Template-Specific Styling
```html
<!-- wp:group {"className":"template-specific-class"} -->
```

## Performance Optimization Tips

1. **Efficient Queries**: Use specific parameters instead of inheriting when possible
2. **Image Optimization**: Set consistent aspect ratios for better layout stability
3. **Minimal Nesting**: Avoid unnecessary group blocks
4. **Smart Pagination**: Implement appropriate pagination for large archives
5. **Caching Strategy**: Leverage WordPress caching for template rendering

## Troubleshooting Common Issues

**Template not loading:**
- Check file naming convention
- Verify template hierarchy
- Clear WordPress cache

**Template parts missing:**
- Confirm template part files exist
- Check slug references in templates
- Verify template part registration

**Styling inconsistencies:**
- Review theme.json configuration
- Check CSS custom property usage
- Validate block markup syntax