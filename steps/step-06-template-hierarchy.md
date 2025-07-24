# Step 6: Template Hierarchy Implementation

## Objective
Implement and optimize the complete WordPress template hierarchy for FSE themes with **GPress**, ensuring seamless integration between all templates, template parts, and proper fallback systems for maximum compatibility and performance. Focus on conditional asset loading to ensure each template only loads the CSS and JavaScript it actually needs.

## What You'll Learn
- WordPress FSE template hierarchy
- Template fallback mechanisms
- Custom template creation with conditional assets
- Template organization best practices
- Performance optimization strategies
- Cross-template consistency

## Files to Create in This Step

```
templates/
├── home.html              # Blog posts page
├── category.html          # Category archive template  
├── tag.html              # Tag archive template
├── author.html           # Author archive template
├── date.html             # Date archive template
├── page-contact.html     # Custom contact page template
└── blank.html            # Blank canvas template

assets/css/
└── hierarchy.css         # Template hierarchy specific styles

assets/js/
└── hierarchy.js          # Template hierarchy enhancements

inc/
└── template-hierarchy.php # Template hierarchy functions and conditional loading
```

## 1. Create Template Hierarchy Functions

### File: `inc/template-hierarchy.php`
```php
<?php
/**
 * Template Hierarchy Functions for GPress Theme
 *
 * @package GPress
 * @version 1.0.0
 */

// Prevent direct access
defined('ABSPATH') || exit;

/**
 * Conditional Template Hierarchy Assets
 * Enqueue CSS/JS only for specific template hierarchies
 */
function gpress_conditional_hierarchy_assets() {
    // Only load hierarchy assets on pages that need them
    $load_hierarchy_assets = false;
    
    // Check for various template hierarchy pages
    if (is_home() || is_category() || is_tag() || is_author() || 
        is_date() || is_archive() || is_page_template()) {
        $load_hierarchy_assets = true;
    }
    
    if ($load_hierarchy_assets) {
        wp_enqueue_style(
            'gpress-hierarchy',
            GPRESS_THEME_URI . '/assets/css/hierarchy.css',
            array('gpress-style'),
            GPRESS_VERSION
        );
        
        wp_enqueue_script(
            'gpress-hierarchy',
            GPRESS_THEME_URI . '/assets/js/hierarchy.js',
            array(),
            GPRESS_VERSION,
            true
        );
        
        // Localize script for AJAX and dynamic features
        wp_localize_script('gpress-hierarchy', 'gpressHierarchy', array(
            'ajaxurl' => admin_url('admin-ajax.php'),
            'nonce' => wp_create_nonce('gpress_hierarchy_nonce'),
            'isAuthor' => is_author(),
            'isCategory' => is_category(),
            'isTag' => is_tag(),
            'isDate' => is_date()
        ));
    }
}
add_action('wp_enqueue_scripts', 'gpress_conditional_hierarchy_assets');

/**
 * Archive-Specific Asset Loading
 * Load specific styles for different archive types
 */
function gpress_archive_specific_assets() {
    if (is_category()) {
        wp_add_inline_style('gpress-hierarchy', '
            .category-archive-header { 
                background: linear-gradient(135deg, var(--wp--preset--color--primary) 0%, var(--wp--preset--color--secondary) 100%);
                color: white;
            }
            .category-post-card:hover { 
                transform: translateY(-4px); 
                box-shadow: 0 8px 25px rgba(0,0,0,0.15);
            }
        ');
    }
    
    if (is_tag()) {
        wp_add_inline_style('gpress-hierarchy', '
            .tag-archive-header { 
                background: var(--wp--preset--color--accent);
                border-left: 4px solid var(--wp--preset--color--primary);
            }
        ');
    }
    
    if (is_author()) {
        wp_add_inline_style('gpress-hierarchy', '
            .author-bio { 
                background: var(--wp--preset--color--gray-100);
                border-radius: 12px;
                padding: 2rem;
            }
            .author-avatar { 
                border: 3px solid var(--wp--preset--color--primary);
            }
        ');
    }
    
    if (is_date()) {
        wp_add_inline_style('gpress-hierarchy', '
            .date-archive-header { 
                background: var(--wp--preset--color--gray-50);
                border: 2px dashed var(--wp--preset--color--gray-300);
            }
        ');
    }
}
add_action('wp_enqueue_scripts', 'gpress_archive_specific_assets');

/**
 * Template Hierarchy Body Classes
 * Add specific body classes for each template type
 */
function gpress_template_hierarchy_body_classes($classes) {
    // Add template-specific classes
    if (is_home()) {
        $classes[] = 'gpress-blog-home';
    }
    
    if (is_category()) {
        $classes[] = 'gpress-category-archive';
        $classes[] = 'gpress-category-' . get_query_var('cat');
    }
    
    if (is_tag()) {
        $classes[] = 'gpress-tag-archive';
        $classes[] = 'gpress-tag-' . get_query_var('tag_id');
    }
    
    if (is_author()) {
        $classes[] = 'gpress-author-archive';
        $classes[] = 'gpress-author-' . get_query_var('author');
    }
    
    if (is_date()) {
        $classes[] = 'gpress-date-archive';
        if (is_year()) $classes[] = 'gpress-yearly-archive';
        if (is_month()) $classes[] = 'gpress-monthly-archive';
        if (is_day()) $classes[] = 'gpress-daily-archive';
    }
    
    return $classes;
}
add_filter('body_class', 'gpress_template_hierarchy_body_classes');

/**
 * Optimize Archive Queries
 * Improve performance for different archive types
 */
function gpress_optimize_archive_queries($query) {
    if (!is_admin() && $query->is_main_query()) {
        // Category archives
        if ($query->is_category()) {
            $query->set('posts_per_page', 12);
            $query->set('meta_key', '');
            $query->set('orderby', 'date');
            $query->set('order', 'DESC');
        }
        
        // Tag archives  
        if ($query->is_tag()) {
            $query->set('posts_per_page', 8);
        }
        
        // Author archives
        if ($query->is_author()) {
            $query->set('posts_per_page', 10);
        }
        
        // Date archives
        if ($query->is_date()) {
            $query->set('posts_per_page', 15);
        }
    }
}
add_action('pre_get_posts', 'gpress_optimize_archive_queries');

/**
 * Custom Template Registration
 * Register custom page templates
 */
function gpress_register_custom_templates() {
    // This will be handled by theme.json but we can add dynamic registration if needed
    if (function_exists('wp_is_block_theme') && wp_is_block_theme()) {
        // Additional dynamic template registration can go here
    }
}
add_action('init', 'gpress_register_custom_templates');

/**
 * Template Hierarchy Critical CSS
 * Inline critical CSS for specific templates
 */
function gpress_template_hierarchy_critical_css() {
    $critical_css = '';
    
    if (is_category()) {
        $critical_css = '
            .category-archive-header{background:linear-gradient(135deg,var(--wp--preset--color--primary),var(--wp--preset--color--secondary));color:white;border-radius:8px;padding:2rem;margin-bottom:3rem;text-align:center}
            .category-post-card{transition:transform 0.3s ease,box-shadow 0.3s ease}
        ';
    } elseif (is_author()) {
        $critical_css = '
            .author-bio{background:var(--wp--preset--color--gray-100);border-radius:12px;padding:2rem;text-align:center}
            .author-avatar{border-radius:50%;border:3px solid var(--wp--preset--color--primary)}
        ';
    } elseif (is_home()) {
        $critical_css = '
            .blog-home-header{text-align:center;margin-bottom:3rem}
            .blog-post-card{background:white;border-radius:8px;padding:1.5rem;margin-bottom:2rem;border-bottom:1px solid var(--wp--preset--color--gray-200)}
        ';
    }
    
    if (!empty($critical_css)) {
        echo '<style id="gpress-template-critical">' . $critical_css . '</style>';
    }
}
add_action('wp_head', 'gpress_template_hierarchy_critical_css', 1);

/**
 * Archive Meta Information
 * Generate enhanced meta for archive pages
 */
function gpress_get_archive_meta() {
    $meta = array();
    
    if (is_category()) {
        $category = get_queried_object();
        $meta['type'] = 'category';
        $meta['name'] = $category->name;
        $meta['description'] = $category->description;
        $meta['count'] = $category->count;
        $meta['link'] = get_category_link($category->term_id);
    } elseif (is_tag()) {
        $tag = get_queried_object();
        $meta['type'] = 'tag';
        $meta['name'] = $tag->name;
        $meta['description'] = $tag->description;
        $meta['count'] = $tag->count;
        $meta['link'] = get_tag_link($tag->term_id);
    } elseif (is_author()) {
        $author = get_queried_object();
        $meta['type'] = 'author';
        $meta['name'] = $author->display_name;
        $meta['description'] = get_user_meta($author->ID, 'description', true);
        $meta['count'] = count_user_posts($author->ID);
        $meta['link'] = get_author_posts_url($author->ID);
    } elseif (is_date()) {
        $meta['type'] = 'date';
        if (is_year()) {
            $meta['name'] = get_query_var('year');
            $meta['format'] = 'yearly';
        } elseif (is_month()) {
            $meta['name'] = get_query_var('monthnum') . '/' . get_query_var('year');
            $meta['format'] = 'monthly';
        } elseif (is_day()) {
            $meta['name'] = get_query_var('day') . '/' . get_query_var('monthnum') . '/' . get_query_var('year');
            $meta['format'] = 'daily';
        }
    }
    
    return $meta;
}

/**
 * Contact Form Handler
 * Handle contact form submissions
 */
function gpress_handle_contact_form() {
    if (isset($_POST['gpress_contact_form']) && wp_verify_nonce($_POST['gpress_contact_nonce'], 'gpress_contact_action')) {
        $name = sanitize_text_field($_POST['contact_name']);
        $email = sanitize_email($_POST['contact_email']);
        $subject = sanitize_text_field($_POST['contact_subject']);
        $message = sanitize_textarea_field($_POST['contact_message']);
        
        // Basic validation
        if (empty($name) || empty($email) || empty($message)) {
            wp_redirect(add_query_arg('contact', 'error'));
            exit;
        }
        
        // Send email (basic implementation)
        $to = get_option('admin_email');
        $email_subject = 'Contact Form: ' . $subject;
        $email_message = "Name: $name\nEmail: $email\n\nMessage:\n$message";
        $headers = array('Content-Type: text/plain; charset=UTF-8');
        
        if (wp_mail($to, $email_subject, $email_message, $headers)) {
            wp_redirect(add_query_arg('contact', 'success'));
        } else {
            wp_redirect(add_query_arg('contact', 'error'));
        }
        exit;
    }
}
add_action('template_redirect', 'gpress_handle_contact_form');
```

## 2. Create Template Hierarchy Styles

### File: `assets/css/hierarchy.css`
```css
/*
 * Template Hierarchy Styles for GPress Theme
 * Conditional styles for archive pages and custom templates
 * Version: 1.0.0
 */

/* ==========================================================================
   Archive Page Headers
   ========================================================================== */

.archive-header {
    background: linear-gradient(135deg, var(--wp--preset--color--primary) 0%, var(--wp--preset--color--secondary) 100%);
    color: white;
    border-radius: 12px;
    padding: 3rem 2rem;
    margin-bottom: 3rem;
    text-align: center;
    position: relative;
    overflow: hidden;
}

.archive-header::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><defs><pattern id="grain" width="100" height="100" patternUnits="userSpaceOnUse"><circle cx="25" cy="25" r="1" fill="white" opacity="0.1"/><circle cx="75" cy="75" r="1" fill="white" opacity="0.1"/></pattern></defs><rect width="100" height="100" fill="url(%23grain)"/></svg>');
    opacity: 0.3;
}

.archive-header .archive-title {
    position: relative;
    z-index: 1;
    margin-bottom: 1rem;
    font-size: 2.5rem;
    font-weight: 700;
    line-height: 1.2;
}

.archive-header .archive-description {
    position: relative;
    z-index: 1;
    font-size: 1.1rem;
    opacity: 0.9;
    max-width: 600px;
    margin: 0 auto;
}

/* Category Archives */
.gpress-category-archive .archive-header {
    background: linear-gradient(135deg, var(--wp--preset--color--primary) 0%, var(--wp--preset--color--secondary) 100%);
}

/* Tag Archives */
.gpress-tag-archive .archive-header {
    background: var(--wp--preset--color--accent);
    border-left: 6px solid var(--wp--preset--color--primary);
}

/* Author Archives */
.gpress-author-archive .archive-header {
    background: var(--wp--preset--color--gray-50);
    color: var(--wp--preset--color--gray-900);
    border: 2px solid var(--wp--preset--color--gray-200);
}

/* Date Archives */
.gpress-date-archive .archive-header {
    background: var(--wp--preset--color--gray-50);
    color: var(--wp--preset--color--gray-900);
    border: 2px dashed var(--wp--preset--color--gray-300);
}

/* ==========================================================================
   Archive Grid Layouts
   ========================================================================== */

.archive-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
    gap: 2rem;
    margin-bottom: 3rem;
}

.archive-post-card {
    background: white;
    border-radius: 12px;
    padding: 1.5rem;
    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
    transition: transform 0.3s ease, box-shadow 0.3s ease;
    border: 1px solid var(--wp--preset--color--gray-200);
}

.archive-post-card:hover {
    transform: translateY(-4px);
    box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15);
}

.archive-post-card .post-thumbnail {
    border-radius: 8px;
    overflow: hidden;
    margin-bottom: 1rem;
}

.archive-post-card .post-title {
    font-size: 1.25rem;
    font-weight: 600;
    line-height: 1.3;
    margin-bottom: 0.75rem;
}

.archive-post-card .post-title a {
    color: var(--wp--preset--color--gray-900);
    text-decoration: none;
    transition: color 0.3s ease;
}

.archive-post-card .post-title a:hover {
    color: var(--wp--preset--color--primary);
}

.archive-post-card .post-meta {
    display: flex;
    flex-wrap: wrap;
    gap: 1rem;
    font-size: 0.875rem;
    color: var(--wp--preset--color--gray-600);
    margin-bottom: 1rem;
}

.archive-post-card .post-excerpt {
    color: var(--wp--preset--color--gray-700);
    line-height: 1.6;
}

/* ==========================================================================
   Author Bio Enhancement
   ========================================================================== */

.author-bio {
    background: var(--wp--preset--color--gray-50);
    border-radius: 16px;
    padding: 2.5rem;
    margin-bottom: 3rem;
    text-align: center;
    border: 1px solid var(--wp--preset--color--gray-200);
}

.author-avatar {
    width: 120px;
    height: 120px;
    border-radius: 50%;
    border: 4px solid var(--wp--preset--color--primary);
    margin: 0 auto 1.5rem;
    overflow: hidden;
}

.author-bio .author-name {
    font-size: 1.75rem;
    font-weight: 700;
    margin-bottom: 0.5rem;
    color: var(--wp--preset--color--gray-900);
}

.author-bio .author-description {
    font-size: 1rem;
    color: var(--wp--preset--color--gray-700);
    line-height: 1.6;
    max-width: 500px;
    margin: 0 auto 1.5rem;
}

.author-bio .author-stats {
    display: flex;
    justify-content: center;
    gap: 2rem;
    margin-top: 1.5rem;
}

.author-bio .stat-item {
    text-align: center;
}

.author-bio .stat-number {
    display: block;
    font-size: 1.5rem;
    font-weight: 700;
    color: var(--wp--preset--color--primary);
}

.author-bio .stat-label {
    font-size: 0.875rem;
    color: var(--wp--preset--color--gray-600);
}

/* ==========================================================================
   Contact Form Styling
   ========================================================================== */

.contact-form {
    background: white;
    border-radius: 12px;
    padding: 2rem;
    border: 1px solid var(--wp--preset--color--gray-200);
}

.contact-form .form-group {
    margin-bottom: 1.5rem;
}

.contact-form label {
    display: block;
    margin-bottom: 0.5rem;
    font-weight: 600;
    color: var(--wp--preset--color--gray-900);
}

.contact-form input,
.contact-form textarea {
    width: 100%;
    padding: 0.75rem 1rem;
    border: 2px solid var(--wp--preset--color--gray-200);
    border-radius: 8px;
    font-size: 1rem;
    transition: border-color 0.3s ease, box-shadow 0.3s ease;
    background: white;
}

.contact-form input:focus,
.contact-form textarea:focus {
    outline: none;
    border-color: var(--wp--preset--color--primary);
    box-shadow: 0 0 0 3px rgba(var(--wp--preset--color--primary-rgb), 0.1);
}

.contact-form textarea {
    resize: vertical;
    min-height: 120px;
}

.contact-submit {
    background: var(--wp--preset--color--primary);
    color: white;
    border: none;
    padding: 0.875rem 2rem;
    border-radius: 8px;
    font-size: 1rem;
    font-weight: 600;
    cursor: pointer;
    transition: background-color 0.3s ease, transform 0.2s ease;
}

.contact-submit:hover {
    background: var(--wp--preset--color--secondary);
    transform: translateY(-1px);
}

.contact-submit:active {
    transform: translateY(0);
}

/* Contact form messages */
.contact-message {
    padding: 1rem 1.5rem;
    border-radius: 8px;
    margin-bottom: 2rem;
    font-weight: 500;
}

.contact-message.success {
    background: #d4edda;
    color: #155724;
    border: 1px solid #c3e6cb;
}

.contact-message.error {
    background: #f8d7da;
    color: #721c24;
    border: 1px solid #f5c6cb;
}

/* ==========================================================================
   Responsive Design
   ========================================================================== */

@media (max-width: 768px) {
    .archive-header {
        padding: 2rem 1.5rem;
    }
    
    .archive-header .archive-title {
        font-size: 2rem;
    }
    
    .archive-grid {
        grid-template-columns: 1fr;
        gap: 1.5rem;
    }
    
    .archive-post-card {
        padding: 1.25rem;
    }
    
    .author-bio {
        padding: 2rem 1.5rem;
    }
    
    .author-bio .author-stats {
        flex-direction: column;
        gap: 1rem;
    }
    
    .contact-form {
        padding: 1.5rem;
    }
}

@media (max-width: 480px) {
    .archive-header {
        padding: 1.5rem 1rem;
    }
    
    .archive-header .archive-title {
        font-size: 1.75rem;
    }
    
    .archive-post-card {
        padding: 1rem;
    }
    
    .author-bio {
        padding: 1.5rem 1rem;
    }
    
    .author-avatar {
        width: 100px;
        height: 100px;
    }
}

/* ==========================================================================
   Dark Mode Support
   ========================================================================== */

@media (prefers-color-scheme: dark) {
    .archive-post-card {
        background: var(--wp--preset--color--gray-900);
        border-color: var(--wp--preset--color--gray-700);
        color: var(--wp--preset--color--gray-100);
    }
    
    .archive-post-card .post-title a {
        color: var(--wp--preset--color--gray-100);
    }
    
    .contact-form {
        background: var(--wp--preset--color--gray-900);
        border-color: var(--wp--preset--color--gray-700);
    }
    
    .contact-form input,
    .contact-form textarea {
        background: var(--wp--preset--color--gray-800);
        border-color: var(--wp--preset--color--gray-600);
        color: var(--wp--preset--color--gray-100);
    }
}

/* ==========================================================================
   Print Styles
   ========================================================================== */

@media print {
    .archive-header,
    .archive-post-card {
        box-shadow: none;
        border: 1px solid #ddd;
    }
    
    .archive-post-card:hover {
        transform: none;
    }
    
    .contact-form {
        display: none;
    }
}
```

## 3. Create Template Hierarchy JavaScript

### File: `assets/js/hierarchy.js`
```javascript
/**
 * Template Hierarchy JavaScript for GPress Theme
 * Enhances archive pages and custom templates
 * Version: 1.0.0
 */

(function() {
    'use strict';
    
    // Initialize when DOM is ready
    document.addEventListener('DOMContentLoaded', function() {
        initArchiveEnhancements();
        initContactForm();
        initScrollProgress();
        initLoadMorePosts();
    });
    
    /**
     * Archive Page Enhancements
     */
    function initArchiveEnhancements() {
        // Add smooth scrolling to archive sections
        const archiveLinks = document.querySelectorAll('.archive-navigation a');
        archiveLinks.forEach(link => {
            link.addEventListener('click', function(e) {
                const href = this.getAttribute('href');
                if (href && href.startsWith('#')) {
                    e.preventDefault();
                    const target = document.querySelector(href);
                    if (target) {
                        target.scrollIntoView({
                            behavior: 'smooth',
                            block: 'start'
                        });
                    }
                }
            });
        });
        
        // Enhanced post card interactions
        const postCards = document.querySelectorAll('.archive-post-card');
        postCards.forEach(card => {
            // Add keyboard navigation
            card.setAttribute('tabindex', '0');
            
            // Handle card click
            card.addEventListener('click', function(e) {
                if (e.target.tagName !== 'A') {
                    const titleLink = this.querySelector('.post-title a');
                    if (titleLink) {
                        window.location.href = titleLink.href;
                    }
                }
            });
            
            // Handle keyboard navigation
            card.addEventListener('keydown', function(e) {
                if (e.key === 'Enter' || e.key === ' ') {
                    e.preventDefault();
                    this.click();
                }
            });
        });
        
        // Archive filter functionality (if filter controls exist)
        initArchiveFilters();
    }
    
    /**
     * Archive Filter Functionality
     */
    function initArchiveFilters() {
        const filterButtons = document.querySelectorAll('.archive-filter-btn');
        const postCards = document.querySelectorAll('.archive-post-card');
        
        if (filterButtons.length === 0) return;
        
        filterButtons.forEach(button => {
            button.addEventListener('click', function() {
                const filter = this.dataset.filter;
                
                // Update active button
                filterButtons.forEach(btn => btn.classList.remove('active'));
                this.classList.add('active');
                
                // Filter posts
                postCards.forEach(card => {
                    if (filter === 'all' || card.classList.contains(filter)) {
                        card.style.display = 'block';
                        card.style.animation = 'fadeIn 0.3s ease';
                    } else {
                        card.style.display = 'none';
                    }
                });
            });
        });
    }
    
    /**
     * Contact Form Enhancement
     */
    function initContactForm() {
        const contactForm = document.querySelector('.contact-form');
        if (!contactForm) return;
        
        // Add real-time validation
        const inputs = contactForm.querySelectorAll('input, textarea');
        inputs.forEach(input => {
            input.addEventListener('blur', validateField);
            input.addEventListener('input', clearFieldError);
        });
        
        // Handle form submission
        contactForm.addEventListener('submit', function(e) {
            e.preventDefault();
            
            if (validateForm(this)) {
                submitContactForm(this);
            }
        });
        
        // Auto-resize textarea
        const textarea = contactForm.querySelector('textarea');
        if (textarea) {
            textarea.addEventListener('input', function() {
                this.style.height = 'auto';
                this.style.height = this.scrollHeight + 'px';
            });
        }
    }
    
    /**
     * Form Validation
     */
    function validateField(e) {
        const field = e.target;
        const value = field.value.trim();
        const fieldGroup = field.closest('.form-group');
        
        // Remove existing error
        clearFieldError({ target: field });
        
        // Validate based on field type
        let isValid = true;
        let errorMessage = '';
        
        if (field.required && !value) {
            isValid = false;
            errorMessage = 'This field is required.';
        } else if (field.type === 'email' && value) {
            const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            if (!emailRegex.test(value)) {
                isValid = false;
                errorMessage = 'Please enter a valid email address.';
            }
        }
        
        if (!isValid) {
            showFieldError(field, errorMessage);
        }
        
        return isValid;
    }
    
    function clearFieldError(e) {
        const field = e.target;
        const fieldGroup = field.closest('.form-group');
        const existingError = fieldGroup.querySelector('.field-error');
        
        if (existingError) {
            existingError.remove();
        }
        
        field.classList.remove('error');
    }
    
    function showFieldError(field, message) {
        const fieldGroup = field.closest('.form-group');
        const errorDiv = document.createElement('div');
        errorDiv.className = 'field-error';
        errorDiv.textContent = message;
        errorDiv.style.color = '#e74c3c';
        errorDiv.style.fontSize = '0.875rem';
        errorDiv.style.marginTop = '0.25rem';
        
        fieldGroup.appendChild(errorDiv);
        field.classList.add('error');
        field.style.borderColor = '#e74c3c';
    }
    
    function validateForm(form) {
        const inputs = form.querySelectorAll('input[required], textarea[required]');
        let isValid = true;
        
        inputs.forEach(input => {
            if (!validateField({ target: input })) {
                isValid = false;
            }
        });
        
        return isValid;
    }
    
    /**
     * Submit Contact Form
     */
    function submitContactForm(form) {
        const submitButton = form.querySelector('.contact-submit');
        const originalText = submitButton.textContent;
        
        // Show loading state
        submitButton.textContent = 'Sending...';
        submitButton.disabled = true;
        
        // Create form data
        const formData = new FormData(form);
        formData.append('action', 'gpress_contact_form');
        formData.append('gpress_contact_nonce', gpressHierarchy.nonce);
        
        // Submit via AJAX
        fetch(gpressHierarchy.ajaxurl, {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showContactMessage('Thank you! Your message has been sent successfully.', 'success');
                form.reset();
            } else {
                showContactMessage('Sorry, there was an error sending your message. Please try again.', 'error');
            }
        })
        .catch(error => {
            console.error('Contact form error:', error);
            showContactMessage('Sorry, there was an error sending your message. Please try again.', 'error');
        })
        .finally(() => {
            // Reset button
            submitButton.textContent = originalText;
            submitButton.disabled = false;
        });
    }
    
    function showContactMessage(message, type) {
        // Remove existing messages
        const existingMessages = document.querySelectorAll('.contact-message');
        existingMessages.forEach(msg => msg.remove());
        
        // Create new message
        const messageDiv = document.createElement('div');
        messageDiv.className = `contact-message ${type}`;
        messageDiv.textContent = message;
        
        // Insert before form
        const form = document.querySelector('.contact-form');
        form.parentNode.insertBefore(messageDiv, form);
        
        // Auto-remove after 5 seconds
        setTimeout(() => {
            messageDiv.remove();
        }, 5000);
        
        // Scroll to message
        messageDiv.scrollIntoView({ behavior: 'smooth', block: 'center' });
    }
    
    /**
     * Reading Progress Indicator
     */
    function initScrollProgress() {
        if (!document.querySelector('.archive-posts, .single-post-content')) return;
        
        const progressBar = document.createElement('div');
        progressBar.className = 'reading-progress';
        progressBar.style.cssText = `
            position: fixed;
            top: 0;
            left: 0;
            width: 0%;
            height: 3px;
            background: var(--wp--preset--color--primary, #0073aa);
            z-index: 999;
            transition: width 0.1s ease;
        `;
        
        document.body.appendChild(progressBar);
        
        function updateProgress() {
            const scrolled = window.scrollY;
            const maxScroll = document.documentElement.scrollHeight - window.innerHeight;
            const progress = (scrolled / maxScroll) * 100;
            
            progressBar.style.width = Math.min(100, Math.max(0, progress)) + '%';
        }
        
        window.addEventListener('scroll', updateProgress);
        updateProgress(); // Initial call
    }
    
    /**
     * Load More Posts (Progressive Enhancement)
     */
    function initLoadMorePosts() {
        const loadMoreBtn = document.querySelector('.load-more-posts');
        if (!loadMoreBtn) return;
        
        let page = 2;
        let loading = false;
        
        loadMoreBtn.addEventListener('click', function(e) {
            e.preventDefault();
            
            if (loading) return;
            loading = true;
            
            const originalText = this.textContent;
            this.textContent = 'Loading...';
            this.disabled = true;
            
            // Fetch more posts
            fetch(`${window.location.href}?page=${page}`)
                .then(response => response.text())
                .then(html => {
                    const parser = new DOMParser();
                    const doc = parser.parseFromString(html, 'text/html');
                    const newPosts = doc.querySelectorAll('.archive-post-card');
                    
                    if (newPosts.length > 0) {
                        const postsContainer = document.querySelector('.archive-grid, .post-template');
                        newPosts.forEach(post => {
                            postsContainer.appendChild(post);
                        });
                        page++;
                    } else {
                        this.textContent = 'No more posts';
                        this.disabled = true;
                        return;
                    }
                })
                .catch(error => {
                    console.error('Load more error:', error);
                    this.textContent = 'Error loading posts';
                })
                .finally(() => {
                    if (!this.disabled) {
                        this.textContent = originalText;
                        this.disabled = false;
                    }
                    loading = false;
                });
        });
    }
    
    /**
     * Utility Functions
     */
    
    // Debounce function for performance
    function debounce(func, wait) {
        let timeout;
        return function executedFunction(...args) {
            const later = () => {
                clearTimeout(timeout);
                func(...args);
            };
            clearTimeout(timeout);
            timeout = setTimeout(later, wait);
        };
    }
    
    // Throttle function for scroll events
    function throttle(func, limit) {
        let inThrottle;
        return function() {
            const args = arguments;
            const context = this;
            if (!inThrottle) {
                func.apply(context, args);
                inThrottle = true;
                setTimeout(() => inThrottle = false, limit);
            }
        };
    }
    
})();
```

## 4. Create Template Files

### File: `templates/home.html`
```html
<!-- wp:template-part {"slug":"header","tagName":"header"} /-->

<!-- wp:group {"layout":{"type":"constrained"},"style":{"spacing":{"margin":{"top":"2rem","bottom":"2rem"}}}} -->
<div class="wp-block-group blog-home-container" style="margin-top:2rem;margin-bottom:2rem">
    <!-- wp:heading {"textAlign":"center","level":1,"fontSize":"xx-large","style":{"spacing":{"margin":{"bottom":"3rem"}}}} -->
    <h1 class="wp-block-heading has-text-align-center has-xx-large-font-size blog-home-title" style="margin-bottom:3rem">Latest from GPress Blog</h1>
    <!-- /wp:heading -->
    
    <!-- wp:columns {"style":{"spacing":{"blockGap":{"top":"2rem","left":"3rem"}}}} -->
    <div class="wp-block-columns">
        <!-- wp:column {"width":"70%"} -->
        <div class="wp-block-column" style="flex-basis:70%">
            <!-- wp:query {"queryId":0,"query":{"perPage":6,"pages":0,"offset":0,"postType":"post","order":"desc","orderBy":"date","author":"","search":"","exclude":[],"sticky":"exclude","inherit":false}} -->
            <div class="wp-block-query">
                <!-- wp:post-template -->
                <!-- wp:group {"style":{"spacing":{"blockGap":"1.5rem","padding":{"bottom":"2rem","top":"0"},"margin":{"bottom":"2rem"}},"border":{"bottom":{"color":"var:preset|color|gray-200","width":"1px","style":"solid"}}},"className":"blog-post-card","layout":{"type":"flex","orientation":"vertical"}} -->
                <div class="wp-block-group blog-post-card" style="border-bottom-color:var(--wp--preset--color--gray-200);border-bottom-style:solid;border-bottom-width:1px;margin-bottom:2rem;padding-top:0;padding-bottom:2rem">
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
                        
                        <!-- wp:post-excerpt {"moreText":"Continue reading","excerptLength":30} /-->
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

### File: `templates/category.html`
```html
<!-- wp:template-part {"slug":"header","tagName":"header"} /-->

<!-- wp:group {"layout":{"type":"constrained"},"style":{"spacing":{"margin":{"top":"2rem","bottom":"2rem"}}}} -->
<div class="wp-block-group" style="margin-top:2rem;margin-bottom:2rem">
    <!-- wp:group {"style":{"spacing":{"margin":{"bottom":"3rem"},"padding":{"all":"2rem"}},"border":{"radius":"8px"}},"backgroundColor":"gray-100","className":"archive-header category-archive-header","layout":{"type":"constrained","contentSize":"800px"}} -->
    <div class="wp-block-group archive-header category-archive-header has-gray-100-background-color has-background" style="border-radius:8px;margin-bottom:3rem;padding:2rem">
        <!-- wp:query-title {"type":"archive","textAlign":"center","fontSize":"xx-large","className":"archive-title","style":{"spacing":{"margin":{"bottom":"1rem"}}}} /-->
        
        <!-- wp:term-description {"textAlign":"center","fontSize":"medium","className":"archive-description","style":{"color":{"text":"var:preset|color|gray-700"}}} /-->
        
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
                <!-- wp:post-template {"layout":{"type":"grid","columnCount":2},"className":"archive-grid"} -->
                <!-- wp:group {"style":{"spacing":{"blockGap":"1rem","padding":{"all":"1.5rem"}},"border":{"radius":"8px"}},"backgroundColor":"white","className":"archive-post-card category-post-card"} -->
                <div class="wp-block-group archive-post-card category-post-card has-white-background-color has-background" style="border-radius:8px;padding:1.5rem">
                    <!-- wp:post-featured-image {"isLink":true,"aspectRatio":"4/3","scale":"cover","className":"post-thumbnail","style":{"border":{"radius":"4px"}}} /-->
                    
                    <!-- wp:group {"style":{"spacing":{"blockGap":"0.75rem"}},"layout":{"type":"flex","orientation":"vertical"}} -->
                    <div class="wp-block-group">
                        <!-- wp:post-title {"isLink":true,"fontSize":"medium","className":"post-title","style":{"typography":{"lineHeight":"1.3"}}} /-->
                        
                        <!-- wp:group {"className":"post-meta","layout":{"type":"flex","flexWrap":"wrap","justifyContent":"space-between"}} -->
                        <div class="wp-block-group post-meta">
                            <!-- wp:post-date {"fontSize":"small","style":{"color":{"text":"var:preset|color|gray-600"}}} /-->
                            <!-- wp:post-author {"fontSize":"small","style":{"color":{"text":"var:preset|color|gray-600"}}} /-->
                        </div>
                        <!-- /wp:group -->
                        
                        <!-- wp:post-excerpt {"moreText":"Read more","excerptLength":20,"className":"post-excerpt"} /-->
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

### File: `templates/author.html`
```html
<!-- wp:template-part {"slug":"header","tagName":"header"} /-->

<!-- wp:group {"layout":{"type":"constrained"},"style":{"spacing":{"margin":{"top":"2rem","bottom":"2rem"}}}} -->
<div class="wp-block-group" style="margin-top:2rem;margin-bottom:2rem">
    <!-- wp:group {"style":{"spacing":{"margin":{"bottom":"3rem"},"padding":{"all":"2rem"}},"border":{"radius":"8px"}},"backgroundColor":"gray-100","className":"author-bio archive-header","layout":{"type":"constrained","contentSize":"600px"}} -->
    <div class="wp-block-group author-bio archive-header has-gray-100-background-color has-background" style="border-radius:8px;margin-bottom:3rem;padding:2rem">
        <!-- wp:post-author-avatar {"size":120,"className":"author-avatar","style":{"border":{"radius":"50%","width":"4px","color":"var:preset|color|primary"}}} /-->
        
        <!-- wp:query-title {"type":"archive","textAlign":"center","fontSize":"xx-large","className":"author-name archive-title","style":{"spacing":{"margin":{"bottom":"1rem"}}}} /-->
        
        <!-- wp:post-author-biography {"textAlign":"center","fontSize":"medium","className":"author-description archive-description","style":{"color":{"text":"var:preset|color|gray-700"}}} /-->
        
        <!-- wp:group {"className":"author-stats","layout":{"type":"flex","justifyContent":"center","flexWrap":"wrap"},"style":{"spacing":{"gap":"2rem","margin":{"top":"1.5rem"}}}} -->
        <div class="wp-block-group author-stats" style="margin-top:1.5rem">
            <!-- wp:group {"className":"stat-item","layout":{"type":"flex","orientation":"vertical","justifyContent":"center"}} -->
            <div class="wp-block-group stat-item">
                <!-- wp:paragraph {"className":"stat-number","style":{"typography":{"fontSize":"1.5rem","fontWeight":"700"},"color":{"text":"var:preset|color|primary"}}} -->
                <p class="stat-number" style="color:var(--wp--preset--color--primary);font-size:1.5rem;font-weight:700"><!-- Dynamic post count will be inserted via JS --></p>
                <!-- /wp:paragraph -->
                
                <!-- wp:paragraph {"className":"stat-label","fontSize":"small","style":{"color":{"text":"var:preset|color|gray-600"}}} -->
                <p class="stat-label has-small-font-size" style="color:var(--wp--preset--color--gray-600)">Posts</p>
                <!-- /wp:paragraph -->
            </div>
            <!-- /wp:group -->
        </div>
        <!-- /wp:group -->
        
        <!-- wp:template-part {"slug":"social-links"} /-->
    </div>
    <!-- /wp:group -->
    
    <!-- wp:query {"queryId":0,"query":{"perPage":10,"pages":0,"offset":0,"postType":"post","order":"desc","orderBy":"date","author":"","search":"","exclude":[],"sticky":"","inherit":true}} -->
    <div class="wp-block-query">
        <!-- wp:post-template {"layout":{"type":"grid","columnCount":2},"className":"archive-grid"} -->
        <!-- wp:group {"style":{"spacing":{"blockGap":"1rem","padding":{"all":"1.5rem"}},"border":{"radius":"8px"}},"backgroundColor":"white","className":"archive-post-card"} -->
        <div class="wp-block-group archive-post-card has-white-background-color has-background" style="border-radius:8px;padding:1.5rem">
            <!-- wp:post-featured-image {"isLink":true,"aspectRatio":"16/9","scale":"cover","className":"post-thumbnail","style":{"border":{"radius":"4px"}}} /-->
            
            <!-- wp:group {"style":{"spacing":{"blockGap":"0.75rem"}},"layout":{"type":"flex","orientation":"vertical"}} -->
            <div class="wp-block-group">
                <!-- wp:post-title {"isLink":true,"fontSize":"medium","className":"post-title"} /-->
                
                <!-- wp:group {"className":"post-meta","layout":{"type":"flex","flexWrap":"wrap","justifyContent":"space-between"}} -->
                <div class="wp-block-group post-meta">
                    <!-- wp:post-date {"fontSize":"small","style":{"color":{"text":"var:preset|color|gray-600"}}} /-->
                    <!-- wp:post-terms {"term":"category","fontSize":"small"} /-->
                </div>
                <!-- /wp:group -->
                
                <!-- wp:post-excerpt {"moreText":"Read more","excerptLength":25,"className":"post-excerpt"} /-->
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

### File: `templates/page-contact.html`
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
                <p class="has-small-font-size"><strong>Email:</strong> hello@gpress-theme.com</p>
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
                <form class="contact-form" method="post" action="">
                    <?php wp_nonce_field('gpress_contact_action', 'gpress_contact_nonce'); ?>
                    <input type="hidden" name="gpress_contact_form" value="1">
                    
                    <div class="form-group">
                        <label for="contact_name">Name *</label>
                        <input type="text" id="contact_name" name="contact_name" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="contact_email">Email *</label>
                        <input type="email" id="contact_email" name="contact_email" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="contact_subject">Subject</label>
                        <input type="text" id="contact_subject" name="contact_subject">
                    </div>
                    
                    <div class="form-group">
                        <label for="contact_message">Message *</label>
                        <textarea id="contact_message" name="contact_message" rows="5" required></textarea>
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

### Files: `templates/tag.html`, `templates/date.html`, `templates/blank.html`

Create similar templates following the same pattern as `category.html` and `author.html`, but with appropriate modifications for each template type.

## 5. Update Functions.php

### Add to: `functions.php`
```php
// Template Hierarchy Support
require_once GPRESS_INC_DIR . '/template-hierarchy.php';

/**
 * Enable Template Hierarchy Support
 */
function gpress_template_hierarchy_support() {
    // Ensure FSE template support
    add_theme_support('block-templates');
    add_theme_support('block-template-parts');
    
    // Add custom template support
    add_theme_support('custom-header');
    add_theme_support('custom-background');
}
add_action('after_setup_theme', 'gpress_template_hierarchy_support');
```

## 6. Update theme.json

### Add to: `theme.json`
```json
{
    "customTemplates": [
        {
            "name": "page-contact",
            "title": "Contact Page",
            "postTypes": ["page"]
        },
        {
            "name": "blank",
            "title": "Blank Canvas",
            "postTypes": ["page", "post"]
        }
    ]
}
```

## Testing Instructions

### 1. Template Hierarchy Test
```bash
# Activate the theme and test template hierarchy
1. Go to WordPress Admin → Appearance → Themes
2. Activate GPress theme
3. Create test content:
   - Create categories and assign posts
   - Create author accounts with bios
   - Create a page with slug "contact"
4. Visit each template type:
   - /category/[category-name]/
   - /author/[author-name]/
   - /contact/
   - /?s=search-term
```

### 2. Conditional Asset Loading Test
```bash
# Test that hierarchy assets only load on appropriate pages
1. Visit homepage - should NOT load hierarchy.css/js
2. Visit category page - should load hierarchy.css/js
3. Visit single post - should NOT load hierarchy.css/js
4. Visit author page - should load hierarchy.css/js
5. Check browser Network tab to verify
```

### 3. Template Functionality Test
```bash
# Test template-specific features
1. Test category archive:
   - Verify category header displays
   - Check post grid layout
   - Test pagination
2. Test author archive:
   - Verify author bio section
   - Check author avatar
   - Test author stats
3. Test contact page:
   - Fill out contact form
   - Test form validation
   - Submit form (check email)
```

### 4. Performance Test
```bash
# Test template performance
1. Run Lighthouse on each template type
2. Check Core Web Vitals:
   - LCP < 2.5s
   - FID < 100ms
   - CLS < 0.1
3. Verify conditional asset loading improves scores
```

### 5. Accessibility Test
```bash
# Test template accessibility
1. Test keyboard navigation on all templates
2. Verify screen reader compatibility
3. Check color contrast ratios
4. Test focus states on interactive elements
```

### 6. Responsive Design Test
```bash
# Test template responsiveness
1. Test on desktop (1200px+)
2. Test on tablet (768px-1199px)
3. Test on mobile (320px-767px)
4. Verify grid layouts adapt properly
5. Test touch interactions on mobile
```

### 7. Integration Test
```bash
# Test template integration
1. Verify template parts load correctly
2. Test template fallback system
3. Check Site Editor functionality
4. Test custom template selection
```

## Expected Results

After completing this step, you should have:

- ✅ Complete template hierarchy implementation
- ✅ Conditional asset loading system
- ✅ Custom page templates (contact, blank)
- ✅ Enhanced archive pages with proper styling
- ✅ JavaScript enhancements for user experience
- ✅ Performance-optimized template queries
- ✅ Accessible and responsive template designs
- ✅ Working contact form with validation
- ✅ Reading progress indicator
- ✅ Template-specific body classes and styling

The theme should now have a complete, professional template hierarchy that only loads the CSS and JavaScript needed for each specific template type, significantly improving performance while maintaining full functionality.

## Next Steps

In Step 7, we'll implement the global CSS architecture and styling system to make all templates visually cohesive and beautiful.