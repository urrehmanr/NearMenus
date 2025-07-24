# Step 1: Project Structure & Basic Files

## Objective
Set up the fundamental WordPress theme structure with proper directory organization and essential files.

## What You'll Learn
- WordPress theme directory structure
- Required vs optional theme files
- File naming conventions
- Basic theme organization

## Directory Structure

Create the following directory structure for your theme:

```
modernblog2025/
├── style.css                    # Main stylesheet (required)
├── index.php                    # Main template fallback (required)
├── functions.php               # Theme functions and features
├── theme.json                  # Theme configuration for FSE
├── screenshot.png              # Theme preview image
├── README.md                   # Theme documentation
├── templates/                  # Block templates directory
│   ├── index.html             # Main template
│   ├── single.html            # Single post template
│   ├── page.html              # Page template
│   ├── archive.html           # Archive template
│   └── 404.html               # 404 error template
├── parts/                      # Template parts directory
│   ├── header.html            # Header template part
│   ├── footer.html            # Footer template part
│   ├── sidebar.html           # Sidebar template part
│   └── navigation.html        # Navigation template part
├── assets/                     # Theme assets
│   ├── css/                   # Stylesheets
│   ├── js/                    # JavaScript files
│   ├── images/                # Theme images
│   └── fonts/                 # Custom fonts (if needed)
├── inc/                        # PHP includes
│   ├── theme-setup.php        # Theme setup functions
│   ├── enqueue-scripts.php    # Asset enqueuing
│   ├── customizer.php         # Customizer settings
│   └── block-patterns.php     # Custom block patterns
└── languages/                  # Translation files
```

## Step-by-Step Implementation

### 1. Create Theme Directory

Navigate to your WordPress themes directory and create the theme folder:

```bash
cd /path/to/wordpress/wp-content/themes/
mkdir modernblog2025
cd modernblog2025
```

### 2. Create Required Files

#### style.css (Required)
```css
/*
Theme Name: ModernBlog2025
Description: A high-performance WordPress blog theme built for 2025 with full Gutenberg support and optimized for Core Web Vitals.
Author: Your Name
Author URI: https://yourwebsite.com
Version: 1.0.0
Requires at least: 6.4
Tested up to: 6.4
Requires PHP: 8.0
License: GPL v2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html
Text Domain: modernblog2025
Tags: blog, two-columns, right-sidebar, accessibility-ready, custom-colors, custom-logo, custom-menu, editor-style, featured-images, footer-widgets, full-site-editing, block-patterns, rtl-language-support, sticky-post, threaded-comments, translation-ready
*/

/*
This file is required by WordPress but actual styles will be managed through theme.json
and separate CSS files for better performance and organization.
*/
```

#### index.php (Required)
```php
<?php
/**
 * Main template file
 *
 * This is the most generic template file in a WordPress theme
 * and one of the two required files for a theme (the other being style.css).
 * 
 * For FSE themes, this file serves as a fallback when block templates
 * are not available.
 *
 * @package ModernBlog2025
 * @since 1.0.0
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

get_header(); ?>

<main id="main" class="site-main" role="main">
    <?php if (have_posts()) : ?>
        <div class="posts-container">
            <?php while (have_posts()) : the_post(); ?>
                <article id="post-<?php the_ID(); ?>" <?php post_class('post-item'); ?>>
                    <header class="entry-header">
                        <?php
                        if (is_singular()) :
                            the_title('<h1 class="entry-title">', '</h1>');
                        else :
                            the_title('<h2 class="entry-title"><a href="' . esc_url(get_permalink()) . '" rel="bookmark">', '</a></h2>');
                        endif;
                        ?>
                    </header>

                    <div class="entry-content">
                        <?php
                        if (is_singular()) :
                            the_content();
                        else :
                            the_excerpt();
                        endif;
                        ?>
                    </div>

                    <footer class="entry-footer">
                        <?php
                        // Post meta information
                        echo '<span class="posted-on">' . get_the_date() . '</span>';
                        ?>
                    </footer>
                </article>
            <?php endwhile; ?>
        </div>

        <?php
        // Pagination
        the_posts_pagination(array(
            'mid_size' => 2,
            'prev_text' => __('Previous', 'modernblog2025'),
            'next_text' => __('Next', 'modernblog2025'),
        ));
        ?>

    <?php else : ?>
        <section class="no-results">
            <header class="page-header">
                <h1 class="page-title"><?php esc_html_e('Nothing here', 'modernblog2025'); ?></h1>
            </header>
            <div class="page-content">
                <p><?php esc_html_e('It looks like nothing was found at this location. Maybe try a search?', 'modernblog2025'); ?></p>
                <?php get_search_form(); ?>
            </div>
        </section>
    <?php endif; ?>
</main>

<?php
get_sidebar();
get_footer();
?>
```

### 3. Create Essential Directory Structure

```bash
# Create all necessary directories
mkdir -p templates parts assets/{css,js,images,fonts} inc languages

# Create placeholder files to maintain directory structure
touch templates/.gitkeep
touch parts/.gitkeep
touch assets/css/.gitkeep
touch assets/js/.gitkeep
touch assets/images/.gitkeep
touch assets/fonts/.gitkeep
touch inc/.gitkeep
touch languages/.gitkeep
```

### 4. Create Basic README.md

```markdown
# ModernBlog2025 WordPress Theme

A high-performance WordPress blog theme built for 2025 with full Gutenberg support and optimized for Core Web Vitals.

## Features

- Full Site Editing (FSE) compatible
- Gutenberg block support
- Performance optimized
- Accessibility ready (WCAG 2.1 AA)
- Mobile-first responsive design
- SEO optimized
- Translation ready

## Requirements

- WordPress 6.4 or higher
- PHP 8.0 or higher

## Installation

1. Download the theme
2. Upload to wp-content/themes/
3. Activate through WordPress admin

## Customization

This theme uses theme.json for global styling and supports the Site Editor for layout customization.

## Support

For support and documentation, visit: [Your Support URL]

## License

GPL v2 or later
```

### 5. Create Basic .gitignore (Optional)

```gitignore
# OS generated files
.DS_Store
.DS_Store?
._*
.Spotlight-V100
.Trashes
ehthumbs.db
Thumbs.db

# Development files
node_modules/
npm-debug.log*
yarn-debug.log*
yarn-error.log*

# Build files
/dist/
/build/

# IDE files
.vscode/
.idea/
*.swp
*.swo

# Temporary files
*.tmp
*.temp
```

## File Checklist

After completing this step, you should have:

- [ ] Created theme directory: `modernblog2025/`
- [ ] Added `style.css` with proper theme header
- [ ] Added `index.php` with basic template structure
- [ ] Created all required directories
- [ ] Added `README.md` with theme information
- [ ] Optional: Added `.gitignore` for development

## Next Steps

In Step 2, we'll create the core theme files including `functions.php` and set up the theme's PHP functionality.

## Best Practices Applied

1. **Proper file organization** - Separating templates, parts, and assets
2. **Security** - Added ABSPATH check in PHP files
3. **Accessibility** - Used semantic HTML elements and proper heading hierarchy
4. **Internationalization** - Added text domain and translation functions
5. **Standards compliance** - Following WordPress coding standards

## Troubleshooting

**Theme not appearing in admin:**
- Check that `style.css` has the proper theme header
- Ensure the theme is in the correct directory
- Verify file permissions

**Template not working:**
- Make sure `index.php` exists and is readable
- Check for PHP syntax errors in the error log

## Performance Notes

- This basic structure is optimized for performance with minimal overhead
- The `index.php` serves as a fallback; block templates will handle most rendering in FSE
- Asset directories are organized for efficient loading and caching