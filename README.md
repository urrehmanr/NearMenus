# NearMenus WordPress Theme

A professional restaurant directory WordPress theme converted from the NearMenus React application. Built with modern web standards, optimized for performance, and fully responsive.

## 🚀 Features

### Core Functionality
- **WordPress Native**: Uses default post type for restaurants with custom taxonomies
- **Advanced Search & Filtering**: Multi-criteria search with AJAX support
- **Custom Field System**: Built-in meta boxes (no external plugin dependencies)
- **Responsive Design**: Mobile-first approach with Tailwind-inspired CSS
- **Performance Optimized**: Caching, lazy loading, and minification
- **SEO Ready**: Schema markup, OpenGraph, and Twitter Cards

### Restaurant Management
- **Custom Taxonomies**: Cuisine, Location, Price Range, Features
- **Detailed Restaurant Profiles**: Hours, contact info, menus, galleries
- **Rating System**: 5-star rating with review counts
- **Menu Builder**: Hierarchical menu system with dietary options
- **Image Galleries**: Interactive photo galleries with lightbox
- **Operating Hours**: Weekly schedule with "Open Now" status

### Frontend Features
- **Search Modal**: Advanced search with autocomplete
- **Filter System**: Real-time filtering by multiple criteria
- **Restaurant Cards**: Informative cards with key details
- **Interactive Maps**: Integration with Google Maps
- **Social Sharing**: Built-in sharing functionality
- **Mobile Navigation**: Touch-optimized mobile interface

### Admin Features
- **Enhanced Meta Boxes**: User-friendly restaurant data entry
- **Quick Edit**: Inline editing for common fields
- **Bulk Actions**: Mass operations on restaurant data
- **Admin Columns**: Custom columns for restaurant information
- **Import/Export**: Data migration capabilities

### Customization
- **Theme Customizer**: Live preview of changes
- **Color Schemes**: Customizable brand colors
- **Typography**: Web font integration
- **Layout Options**: Flexible display settings
- **Widget Areas**: Strategically placed widget zones

## 📋 Requirements

- **WordPress**: 6.0 or higher
- **PHP**: 8.0 or higher
- **MySQL**: 5.7 or higher
- **Memory**: 256MB minimum (512MB recommended)

## 🔧 Installation

1. **Download the Theme**
   ```bash
   git clone https://github.com/your-repo/nearmenus-theme.git
   ```

2. **Upload to WordPress**
   - Upload the theme folder to `/wp-content/themes/`
   - Or use WordPress admin: Appearance > Themes > Add New > Upload Theme

3. **Activate the Theme**
   - Go to Appearance > Themes
   - Click "Activate" on NearMenus theme

4. **Initial Setup**
   - Go to Appearance > Customize
   - Configure theme settings
   - Set up menus and widgets
   - Add sample restaurant content

## 🏗️ File Structure

```
nearmenus-theme/
├── style.css                     # Theme header and main CSS import
├── index.php                     # Homepage template
├── single.php                    # Single restaurant template
├── archive.php                   # Restaurant archive template
├── taxonomy-cuisine.php          # Cuisine-specific archive
├── search.php                    # Search results template
├── 404.php                       # 404 error page
├── home.php                      # Blog home template
├── header.php                    # Header with navigation
├── footer.php                    # Footer with links
├── functions.php                 # Theme configuration
├── screenshot.png                # Theme screenshot
├── README.md                     # This file
│
├── assets/
│   ├── css/
│   │   ├── main.css             # Complete CSS framework
│   │   └── admin.css            # Admin styling
│   └── js/
│       ├── main.js              # Core functionality
│       ├── customizer.js        # Live preview
│       ├── customizer-controls.js # Enhanced controls
│       └── admin.js             # Admin interface
│
├── inc/
│   ├── taxonomies.php           # Custom taxonomies
│   ├── custom-fields.php        # Meta box system
│   ├── search-functions.php     # Search and filtering
│   ├── template-functions.php   # Helper functions
│   ├── customizer.php           # Theme customizer
│   ├── performance.php          # Performance optimizations
│   └── seo.php                  # SEO enhancements
│
└── template-parts/
    ├── search-form.php          # Advanced search form
    ├── filter-controls.php      # Filter dropdowns
    ├── restaurant-card.php      # Restaurant card component
    ├── restaurant-info.php      # Restaurant information
    ├── restaurant-menu.php      # Menu display
    └── restaurant-gallery.php   # Image gallery
```

## 📝 Content Management

### Adding Restaurants

1. **Create New Post**
   - Go to Posts > Add New
   - Enter restaurant name as title
   - Add description in content area
   - Set featured image

2. **Configure Taxonomies**
   - Select cuisine type(s)
   - Choose location
   - Set price range
   - Mark applicable features

3. **Fill Custom Fields**
   - Restaurant Information: rating, contact details
   - Operating Hours: weekly schedule
   - Menu: hierarchical menu builder

### Content Guidelines

- **Restaurant Names**: Use official business names
- **Descriptions**: 150-300 words highlighting specialties
- **Images**: High-quality photos (1200x600px recommended)
- **Menus**: Organize by logical categories
- **Hours**: Use consistent format (e.g., "9:00 AM - 10:00 PM")

## 🎨 Customization

### Theme Customizer Options

Access via **Appearance > Customize**:

- **Site Identity**: Logo, title, colors
- **Hero Section**: Title, subtitle, background image
- **Contact Information**: Phone, email, address
- **Social Media**: Platform URLs
- **Search Settings**: Default sort, results per page
- **Display Options**: Card style, pagination

### Custom CSS

Add custom styles via **Appearance > Customize > Additional CSS** or by creating a child theme.

### Child Theme

Create a child theme for custom modifications:

```php
// child-theme/style.css
/*
Theme Name: NearMenus Child
Template: nearmenus
Version: 1.0.0
*/

@import url("../nearmenus/style.css");

/* Your custom styles here */
```

## 🔍 SEO Features

### Built-in Optimizations

- **Schema Markup**: Restaurant, organization, and review schemas
- **Meta Tags**: Optimized titles and descriptions
- **OpenGraph**: Social media sharing optimization
- **Canonical URLs**: Duplicate content prevention
- **XML Sitemaps**: Automatic sitemap generation

### Integration

Compatible with popular SEO plugins:
- Yoast SEO
- RankMath
- All in One SEO

## ⚡ Performance Features

### Optimizations

- **Caching**: Transient caching for expensive queries
- **Lazy Loading**: Images load on scroll
- **Minification**: CSS and HTML compression
- **Resource Hints**: DNS prefetch and preload
- **Critical CSS**: Above-the-fold optimization

### Compatibility

- WP Rocket
- W3 Total Cache
- WP Super Cache
- LiteSpeed Cache

## 🔧 Development

### Local Development

1. **Set up WordPress locally**
2. **Clone the theme**
3. **Install dependencies** (if using build tools)
4. **Enable debug mode** in wp-config.php

### Coding Standards

- WordPress Coding Standards
- PHP 8.0+ compatibility
- WCAG 2.1 AA accessibility
- Modern JavaScript (ES6+)

### Build Process

If extending the theme:

```bash
# Install Node.js dependencies
npm install

# Build assets
npm run build

# Watch for changes
npm run watch
```

## 🐛 Troubleshooting

### Common Issues

**Search not working**
- Check that permalinks are set to "Post name"
- Verify AJAX URL is correct

**Images not displaying**
- Regenerate thumbnails after theme activation
- Check file permissions on uploads folder

**Customizer not saving**
- Increase PHP memory limit
- Check for plugin conflicts

**Performance issues**
- Enable caching plugin
- Optimize images
- Minimize plugins

### Debug Mode

Enable WordPress debug mode for development:

```php
// wp-config.php
define('WP_DEBUG', true);
define('WP_DEBUG_LOG', true);
define('WP_DEBUG_DISPLAY', false);
```

## 📚 Documentation

### Hooks and Filters

The theme provides numerous hooks for customization:

```php
// Modify search results
add_filter('nearmenus_search_results', 'custom_search_results');

// Add custom restaurant fields
add_action('nearmenus_restaurant_meta', 'custom_restaurant_fields');

// Customize card display
add_filter('nearmenus_restaurant_card_content', 'custom_card_content');
```

### Template Hierarchy

- `single.php` - Individual restaurant pages
- `archive.php` - Restaurant listings
- `taxonomy-cuisine.php` - Cuisine-specific archives
- `search.php` - Search results
- `404.php` - Error pages

## 🤝 Contributing

1. Fork the repository
2. Create a feature branch
3. Make your changes
4. Test thoroughly
5. Submit a pull request

### Guidelines

- Follow WordPress coding standards
- Include documentation for new features
- Test across different WordPress versions
- Ensure accessibility compliance

## 📄 License

This theme is licensed under the GPL v3 or later.

## 🆘 Support

- **Documentation**: [Theme Documentation](link-to-docs)
- **Issues**: [GitHub Issues](link-to-issues)
- **Community**: [Support Forum](link-to-forum)
- **Email**: support@nearmenus.com

## 🚀 Changelog

### Version 1.0.0
- Initial release
- Complete restaurant directory functionality
- Mobile-responsive design
- SEO optimizations
- Performance enhancements

## 🔮 Roadmap

### Version 1.1.0
- Online ordering integration
- User review system
- Advanced location features
- Multi-language support

### Version 1.2.0
- Reservation system
- Event management
- Loyalty program integration
- API integrations

---

**Built with ❤️ for restaurant owners and food lovers everywhere.**