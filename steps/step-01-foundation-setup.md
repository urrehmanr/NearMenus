# Step 1: Foundation Setup & Basic Files

## Overview
This step establishes the basic foundation for the GPress theme. We'll create only the essential files needed for a minimal working WordPress theme that can be activated and tested.

## Objectives
- Create the basic theme directory structure
- Set up essential WordPress theme files
- Ensure the theme can be activated without errors
- Establish version control and documentation

## Files to Create in This Step

### 1. Create Theme Directory
```
gpress/
├── style.css
├── index.php  
├── functions.php
├── README.md
└── .gitignore
```

### 2. style.css (Theme Header & Basic Styles)

Create the main stylesheet with the WordPress theme header:

```css
/*
Theme Name: GPress
Description: A high-performance, accessibility-focused WordPress blog theme built for 2025's web standards.
Author: Your Name
Version: 1.0.0
Requires at least: 6.4
Tested up to: 6.5
Requires PHP: 8.0
License: GPL v2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html
Text Domain: gpress
Domain Path: /languages
Tags: blog, one-column, two-columns, custom-header, custom-menu, editor-style, featured-images, full-site-editing, rtl-language-support, sticky-post, translation-ready, block-patterns, block-styles, wide-blocks
*/

/*
 * Basic Reset and Foundation Styles
 * These styles ensure the theme has a clean starting point
 */

/* Reset */
*,
*::before,
*::after {
    box-sizing: border-box;
    margin: 0;
    padding: 0;
}

/* Base HTML */
html {
    font-size: 100%;
    line-height: 1.6;
    scroll-behavior: smooth;
}

body {
    font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Oxygen-Sans, Ubuntu, Cantarell, "Helvetica Neue", sans-serif;
    font-size: 1rem;
    line-height: 1.6;
    color: #2c3e50;
    background-color: #ffffff;
    -webkit-font-smoothing: antialiased;
    -moz-osx-font-smoothing: grayscale;
}

/* Links */
a {
    color: #3498db;
    text-decoration: none;
    transition: color 0.2s ease;
}

a:hover,
a:focus {
    color: #2980b9;
    text-decoration: underline;
}

/* Headings */
h1, h2, h3, h4, h5, h6 {
    font-family: inherit;
    font-weight: 600;
    line-height: 1.3;
    margin: 0 0 1rem 0;
    color: #2c3e50;
}

h1 { font-size: 2.5rem; }
h2 { font-size: 2rem; }
h3 { font-size: 1.75rem; }
h4 { font-size: 1.5rem; }
h5 { font-size: 1.25rem; }
h6 { font-size: 1rem; }

/* Paragraphs */
p {
    margin: 0 0 1.5rem 0;
}

/* Lists */
ul, ol {
    margin: 0 0 1.5rem 1.5rem;
}

li {
    margin-bottom: 0.5rem;
}

/* Images */
img {
    max-width: 100%;
    height: auto;
    display: block;
}

/* Basic Layout */
.site-container {
    max-width: 1200px;
    margin: 0 auto;
    padding: 0 1rem;
}

.site-header {
    padding: 2rem 0;
    border-bottom: 1px solid #e1e8ed;
}

.site-main {
    padding: 3rem 0;
    min-height: 60vh;
}

.site-footer {
    padding: 2rem 0;
    border-top: 1px solid #e1e8ed;
    background-color: #f8f9fa;
}

/* WordPress Core Classes */
.alignleft {
    float: left;
    margin: 0 1.5rem 1rem 0;
}

.alignright {
    float: right;
    margin: 0 0 1rem 1.5rem;
}

.aligncenter {
    display: block;
    margin: 0 auto 1rem auto;
}

.wp-caption {
    max-width: 100%;
}

.wp-caption-text {
    font-size: 0.875rem;
    color: #666;
    margin-top: 0.5rem;
}

/* Screen Reader Text */
.screen-reader-text {
    clip: rect(1px, 1px, 1px, 1px);
    position: absolute !important;
    height: 1px;
    width: 1px;
    overflow: hidden;
}

.screen-reader-text:focus {
    background-color: #f1f1f1;
    border-radius: 3px;
    box-shadow: 0 0 2px 2px rgba(0, 0, 0, 0.6);
    clip: auto !important;
    color: #21759b;
    display: block;
    font-size: 14px;
    font-size: 0.875rem;
    font-weight: bold;
    height: auto;
    left: 5px;
    line-height: normal;
    padding: 15px 23px 14px;
    text-decoration: none;
    top: 5px;
    width: auto;
    z-index: 100000;
}

/* Basic Responsive Design */
@media (max-width: 768px) {
    h1 { font-size: 2rem; }
    h2 { font-size: 1.75rem; }
    h3 { font-size: 1.5rem; }
    
    .site-container {
        padding: 0 0.75rem;
    }
    
    .site-header,
    .site-main,
    .site-footer {
        padding: 1.5rem 0;
    }
}

/* Skip Link */
.skip-link {
    position: absolute;
    left: -9999px;
    z-index: 999999;
    padding: 8px 16px;
    background: #000;
    color: #fff;
    text-decoration: none;
}

.skip-link:focus {
    left: 6px;
    top: 7px;
}
```

### 3. index.php (Main Template File)

Create the main PHP template file:

```php
<?php
/**
 * The main template file
 *
 * This is the most generic template file in a WordPress theme
 * and one of the two required files for a theme (the other being style.css).
 * It is used to display a page when nothing more specific matches a query.
 * E.g., it puts together the home page when no home.php file exists.
 *
 * @package GPress
 * @since 1.0.0
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

get_header(); ?>

<div class="site-container">
    <main class="site-main" id="main" role="main">
        
        <?php if (have_posts()) : ?>
            
            <?php if (is_home() && !is_front_page()) : ?>
                <header class="page-header">
                    <h1 class="page-title screen-reader-text"><?php single_post_title(); ?></h1>
                </header>
            <?php endif; ?>

            <?php while (have_posts()) : the_post(); ?>
                
                <article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
                    
                    <header class="entry-header">
                        <?php
                        if (is_singular()) :
                            the_title('<h1 class="entry-title">', '</h1>');
                        else :
                            the_title('<h2 class="entry-title"><a href="' . esc_url(get_permalink()) . '" rel="bookmark">', '</a></h2>');
                        endif;
                        ?>
                        
                        <?php if (!is_page()) : ?>
                            <div class="entry-meta">
                                <time class="entry-date published" datetime="<?php echo esc_attr(get_the_date('c')); ?>">
                                    <?php echo get_the_date(); ?>
                                </time>
                                <?php if (get_the_author()) : ?>
                                    <span class="byline">
                                        by <span class="author vcard">
                                            <a class="url fn n" href="<?php echo esc_url(get_author_posts_url(get_the_author_meta('ID'))); ?>">
                                                <?php echo get_the_author(); ?>
                                            </a>
                                        </span>
                                    </span>
                                <?php endif; ?>
                            </div>
                        <?php endif; ?>
                    </header>

                    <?php if (has_post_thumbnail() && !is_singular()) : ?>
                        <div class="post-thumbnail">
                            <a href="<?php the_permalink(); ?>" aria-hidden="true" tabindex="-1">
                                <?php the_post_thumbnail('large', array('alt' => the_title_attribute(array('echo' => false)))); ?>
                            </a>
                        </div>
                    <?php endif; ?>

                    <div class="entry-content">
                        <?php
                        if (is_singular()) {
                            the_content();
                        } else {
                            the_excerpt();
                            echo '<p class="read-more"><a href="' . esc_url(get_permalink()) . '">Read More</a></p>';
                        }

                        wp_link_pages(array(
                            'before' => '<div class="page-links">Pages:',
                            'after'  => '</div>',
                        ));
                        ?>
                    </div>

                    <?php if (is_singular() && (comments_open() || get_comments_number())) : ?>
                        <footer class="entry-footer">
                            <?php comments_template(); ?>
                        </footer>
                    <?php endif; ?>

                </article>

            <?php endwhile; ?>

            <?php
            // Pagination
            the_posts_pagination(array(
                'prev_text' => '&laquo; Previous',
                'next_text' => 'Next &raquo;',
            ));
            ?>

        <?php else : ?>

            <section class="no-results not-found">
                <header class="page-header">
                    <h1 class="page-title">Nothing Found</h1>
                </header>

                <div class="page-content">
                    <?php if (is_home() && current_user_can('publish_posts')) : ?>
                        <p>Ready to publish your first post? <a href="<?php echo esc_url(admin_url('post-new.php')); ?>">Get started here</a>.</p>
                    <?php elseif (is_search()) : ?>
                        <p>Sorry, but nothing matched your search terms. Please try again with some different keywords.</p>
                        <?php get_search_form(); ?>
                    <?php else : ?>
                        <p>It seems we can&rsquo;t find what you&rsquo;re looking for. Perhaps searching can help.</p>
                        <?php get_search_form(); ?>
                    <?php endif; ?>
                </div>
            </section>

        <?php endif; ?>

    </main>
</div>

<?php
get_footer();
```

### 4. functions.php (Theme Setup)

Create the basic functions file:

```php
<?php
/**
 * GPress functions and definitions
 *
 * @package GPress
 * @since 1.0.0
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Define theme constants
 */
define('GPRESS_VERSION', '1.0.0');
define('GPRESS_THEME_DIR', get_template_directory());
define('GPRESS_THEME_URI', get_template_directory_uri());

/**
 * Set up theme defaults and register support for various WordPress features
 */
function gpress_theme_setup() {
    
    // Add default posts and comments RSS feed links to head
    add_theme_support('automatic-feed-links');

    // Let WordPress manage the document title
    add_theme_support('title-tag');

    // Enable support for Post Thumbnails on posts and pages
    add_theme_support('post-thumbnails');

    // Add theme support for selective refresh for widgets
    add_theme_support('customize-selective-refresh-widgets');

    // Add support for HTML5 markup
    add_theme_support('html5', array(
        'search-form',
        'comment-form',
        'comment-list',
        'gallery',
        'caption',
        'style',
        'script',
    ));

    // Set up the WordPress core custom background feature
    add_theme_support('custom-background', apply_filters('gpress_custom_background_args', array(
        'default-color' => 'ffffff',
        'default-image' => '',
    )));

    // Add theme support for custom logo
    add_theme_support('custom-logo', array(
        'height'      => 250,
        'width'       => 250,
        'flex-width'  => true,
        'flex-height' => true,
    ));

    // Add support for editor styles
    add_theme_support('editor-styles');

    // Add support for responsive embeds
    add_theme_support('responsive-embeds');

    // Load theme text domain for translations
    load_theme_textdomain('gpress', GPRESS_THEME_DIR . '/languages');
}
add_action('after_setup_theme', 'gpress_theme_setup');

/**
 * Set the content width in pixels, based on the theme's design and stylesheet
 */
function gpress_content_width() {
    $GLOBALS['content_width'] = apply_filters('gpress_content_width', 1200);
}
add_action('after_setup_theme', 'gpress_content_width', 0);

/**
 * Enqueue scripts and styles
 */
function gpress_scripts() {
    
    // Enqueue main stylesheet
    wp_enqueue_style(
        'gpress-style',
        get_stylesheet_uri(),
        array(),
        GPRESS_VERSION
    );

    // Enqueue comment reply script for threaded comments
    if (is_singular() && comments_open() && get_option('thread_comments')) {
        wp_enqueue_script('comment-reply');
    }
}
add_action('wp_enqueue_scripts', 'gpress_scripts');

/**
 * Register widget areas
 */
function gpress_widgets_init() {
    register_sidebar(array(
        'name'          => esc_html__('Sidebar', 'gpress'),
        'id'            => 'sidebar-1',
        'description'   => esc_html__('Add widgets here.', 'gpress'),
        'before_widget' => '<section id="%1$s" class="widget %2$s">',
        'after_widget'  => '</section>',
        'before_title'  => '<h2 class="widget-title">',
        'after_title'   => '</h2>',
    ));
}
add_action('widgets_init', 'gpress_widgets_init');

/**
 * Enhance WordPress features
 */

// Remove unnecessary WordPress head items
function gpress_clean_head() {
    remove_action('wp_head', 'rsd_link');
    remove_action('wp_head', 'wp_generator');
    remove_action('wp_head', 'feed_links', 2);
    remove_action('wp_head', 'index_rel_link');
    remove_action('wp_head', 'wlwmanifest_link');
    remove_action('wp_head', 'feed_links_extra', 3);
    remove_action('wp_head', 'start_post_rel_link', 10, 0);
    remove_action('wp_head', 'parent_post_rel_link', 10, 0);
    remove_action('wp_head', 'adjacent_posts_rel_link', 10, 0);
    remove_action('wp_head', 'adjacent_posts_rel_link_wp_head', 10, 0);
    remove_action('wp_head', 'wp_shortlink_wp_head', 10, 0);
}
add_action('init', 'gpress_clean_head');

/**
 * Security enhancements
 */

// Remove WordPress version from RSS feeds
function gpress_remove_version() {
    return '';
}
add_filter('the_generator', 'gpress_remove_version');

// Disable XML-RPC
add_filter('xmlrpc_enabled', '__return_false');

// Remove version from scripts and styles
function gpress_remove_version_scripts_styles($src) {
    if (strpos($src, 'ver=')) {
        $src = remove_query_arg('ver', $src);
    }
    return $src;
}
add_filter('style_loader_src', 'gpress_remove_version_scripts_styles', 9999);
add_filter('script_loader_src', 'gpress_remove_version_scripts_styles', 9999);

/**
 * Performance optimizations
 */

// Optimize images
function gpress_optimize_images($attr, $attachment, $size) {
    // Add loading="lazy" to images
    if (!is_admin() && !wp_is_mobile()) {
        $attr['loading'] = 'lazy';
    }
    
    // Add decoding="async" for better performance
    $attr['decoding'] = 'async';
    
    return $attr;
}
add_filter('wp_get_attachment_image_attributes', 'gpress_optimize_images', 10, 3);

// Defer non-critical scripts
function gpress_defer_scripts($tag, $handle, $src) {
    // Don't defer admin scripts or jQuery
    if (is_admin() || strpos($handle, 'jquery') !== false) {
        return $tag;
    }
    
    // Add defer attribute to scripts
    return str_replace('<script ', '<script defer ', $tag);
}
add_filter('script_loader_tag', 'gpress_defer_scripts', 10, 3);

/**
 * Basic header template
 */
if (!function_exists('gpress_header')) {
    function gpress_header() {
        ?>
        <!doctype html>
        <html <?php language_attributes(); ?>>
        <head>
            <meta charset="<?php bloginfo('charset'); ?>">
            <meta name="viewport" content="width=device-width, initial-scale=1">
            <link rel="profile" href="https://gmpg.org/xfn/11">
            <?php wp_head(); ?>
        </head>

        <body <?php body_class(); ?>>
        <?php wp_body_open(); ?>
        
        <a class="skip-link screen-reader-text" href="#main"><?php esc_html_e('Skip to content', 'gpress'); ?></a>

        <header class="site-header">
            <div class="site-container">
                <div class="site-branding">
                    <?php
                    if (has_custom_logo()) {
                        the_custom_logo();
                    } else {
                        if (is_front_page() && is_home()) :
                            ?>
                            <h1 class="site-title"><a href="<?php echo esc_url(home_url('/')); ?>" rel="home"><?php bloginfo('name'); ?></a></h1>
                            <?php
                        else :
                            ?>
                            <p class="site-title"><a href="<?php echo esc_url(home_url('/')); ?>" rel="home"><?php bloginfo('name'); ?></a></p>
                            <?php
                        endif;
                        $description = get_bloginfo('description', 'display');
                        if ($description || is_customize_preview()) :
                            ?>
                            <p class="site-description"><?php echo $description; ?></p>
                        <?php endif;
                    }
                    ?>
                </div>
            </div>
        </header>
        <?php
    }
}

/**
 * Basic footer template
 */
if (!function_exists('gpress_footer')) {
    function gpress_footer() {
        ?>
        <footer class="site-footer">
            <div class="site-container">
                <div class="site-info">
                    <p>&copy; <?php echo date('Y'); ?> <?php bloginfo('name'); ?>. All rights reserved.</p>
                    <p>Powered by <a href="<?php echo esc_url(__('https://wordpress.org/', 'gpress')); ?>">WordPress</a> and <a href="#">GPress</a> theme.</p>
                </div>
            </div>
        </footer>

        <?php wp_footer(); ?>
        </body>
        </html>
        <?php
    }
}

// Use template functions if header.php and footer.php don't exist
if (!locate_template('header.php')) {
    function get_header($name = null) {
        gpress_header();
    }
}

if (!locate_template('footer.php')) {
    function get_footer($name = null) {
        gpress_footer();
    }
}
```

### 5. README.md (Basic Documentation)

```markdown
# GPress WordPress Theme

A high-performance, accessibility-focused WordPress blog theme built for 2025's web standards.

## Version 1.0.0 - Foundation

This is the foundation version of the GPress theme. Currently includes:

- ✅ Basic theme structure
- ✅ WordPress theme requirements
- ✅ Responsive design foundation
- ✅ Security enhancements
- ✅ Performance optimizations
- ✅ Accessibility basics

## Installation

1. Download or clone this theme
2. Upload to `/wp-content/themes/gpress/`
3. Activate in WordPress admin under Appearance > Themes

## Requirements

- WordPress 6.4 or higher
- PHP 8.0 or higher

## Features

- Clean, minimal design
- Mobile-first responsive layout
- Security hardened
- Performance optimized
- Translation ready
- Accessibility focused

## Development Status

This theme is currently in development. More features will be added in subsequent steps:

- [ ] Full Site Editing (FSE) support
- [ ] Block templates and template parts
- [ ] Advanced typography system
- [ ] Custom post types
- [ ] SEO optimization
- [ ] Advanced accessibility features

## Support

This theme is in active development. Documentation will be expanded as features are added.

## License

GPL v2 or later
```

### 6. .gitignore (Version Control)

```gitignore
# WordPress
wp-config.php
wp-content/uploads/
wp-content/blogs.dir/
wp-content/upgrade/
wp-content/backup-db/
wp-content/advanced-cache.php
wp-content/wp-cache-config.php
wp-content/cache/
wp-content/cache/supercache/

# Logs
*.log
error_log
access_log

# OS generated files
.DS_Store
.DS_Store?
._*
.Spotlight-V100
.Trashes
ehthumbs.db
Thumbs.db

# Editor files
*.sublime-project
*.sublime-workspace
*.komodoproject
.vscode/
.idea/

# Temporary files
*.tmp
*.temp
*.swp
*.swo
*~

# Dependencies
node_modules/
vendor/

# Build files
*.min.css
*.min.js
dist/
build/

# Environment files
.env
.env.local
.env.production
```

## Testing Instructions

After completing this step, perform the following tests:

### 1. Installation Test
```bash
# Navigate to your WordPress themes directory
cd /path/to/wordpress/wp-content/themes/

# Create the theme directory
mkdir gpress

# Add the files created above to the gpress directory
```

### 2. WordPress Admin Test
1. Go to WordPress Admin → Appearance → Themes
2. Verify "GPress" appears in the themes list
3. Activate the theme
4. Confirm no errors appear

### 3. Frontend Test
1. Visit your site's homepage
2. Verify the theme loads without errors
3. Check that basic styling is applied
4. Test navigation and links work

### 4. Responsive Test
1. Resize browser window to mobile size
2. Verify layout adapts properly
3. Test touch navigation works

### 5. Performance Test
1. Run basic Lighthouse test
2. Should achieve 90+ performance score even in this basic state
3. Verify no console errors

### 6. Accessibility Test
1. Test tab navigation through the page
2. Verify skip link works (Tab key first)
3. Check heading hierarchy with screen reader

## Expected Results

After completing Step 1, you should have:

- ✅ A functional WordPress theme that can be activated
- ✅ Clean, responsive design that works on all devices
- ✅ Basic performance optimizations in place
- ✅ Foundation for accessibility features
- ✅ Secure, clean code structure
- ✅ Version control setup

## Next Step

Proceed to [Step 2: Core Theme Files Configuration](./step-02-core-files.md) to add advanced functionality and FSE support.

## Troubleshooting

**Theme not appearing in admin:**
- Check that all required files are present
- Verify style.css has proper theme header
- Ensure file permissions are correct

**Styling not applied:**
- Clear any caching plugins
- Check browser developer tools for CSS errors
- Verify style.css is loading properly

**PHP errors:**
- Check that PHP version is 8.0 or higher
- Verify no syntax errors in functions.php
- Check WordPress error logs