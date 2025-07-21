# NearMenus Theme - Build Documentation

## 🚀 Production Build

This theme includes optimized, minified assets for production use. The build process has already been completed and the theme is ready for deployment.

## 📦 Asset Structure

```
assets/
├── css/                    # Source CSS files (development)
│   ├── main.css
│   └── admin.css
├── js/                     # Source JavaScript files (development)
│   ├── main.js
│   ├── admin.js
│   ├── customizer.js
│   └── customizer-controls.js
└── dist/                   # Minified assets (production)
    ├── css/
    │   ├── main.min.css    # 22% smaller than source
    │   └── admin.min.css   # 29% smaller than source
    └── js/
        ├── main.min.js             # 58% smaller than source
        ├── admin.min.js            # 49% smaller than source
        ├── customizer.min.js       # 53% smaller than source
        └── customizer-controls.min.js # 56% smaller than source
```

## 📊 Build Results

- **Total Size Reduction**: 45% (85,053 → 46,541 bytes)
- **CSS Optimization**: Added vendor prefixes, minified
- **JavaScript Optimization**: Compressed and mangled
- **Load Time Improvement**: Significantly faster page loads

## 🛠️ Development Build (Optional)

If you need to modify source files and rebuild:

### Prerequisites
- Node.js 16+ installed
- npm package manager

### Build Commands

```bash
# Install build dependencies (first time only)
npm install

# Build all assets
npm run build

# Build specific asset types
npm run build:css    # CSS only
npm run build:js     # JavaScript only

# Clean build directory
npm run clean

# Individual builds
npm run build:css:main
npm run build:css:admin
npm run build:js:main
npm run build:js:admin
npm run build:js:customizer
```

### Build Process

1. **CSS Processing**:
   - Adds vendor prefixes with Autoprefixer
   - Minifies with clean-css
   - Removes comments and whitespace

2. **JavaScript Processing**:
   - Compresses code with UglifyJS
   - Mangles variable names
   - Removes comments and whitespace

3. **File Updates**:
   - Automatically updates `functions.php`
   - Updates `inc/customizer.php`
   - Switches references to minified files

## 📋 Deployment Checklist

✅ **Pre-deployment (Complete)**:
- [x] CSS files minified and optimized
- [x] JavaScript files minified and optimized
- [x] Vendor prefixes added to CSS
- [x] All file references updated to minified versions
- [x] Build tools configured
- [x] Source files preserved for future development

✅ **Ready for WordPress**:
- [x] Upload entire theme folder to `/wp-content/themes/`
- [x] Activate in WordPress admin
- [x] Configure theme options in Customizer

## 🎯 Performance Benefits

- **Faster Loading**: 45% reduction in asset size
- **Better Caching**: Minified files cache more efficiently
- **Improved SEO**: Faster page load times
- **Enhanced UX**: Reduced bandwidth usage
- **Mobile Optimized**: Smaller files for mobile users

## 🔧 File Modifications for Production

The following files are automatically updated to use minified assets:

1. **functions.php**:
   - `main.css` → `main.min.css`
   - `admin.css` → `admin.min.css`
   - `main.js` → `main.min.js`
   - `admin.js` → `admin.min.js`

2. **inc/customizer.php**:
   - `customizer.js` → `customizer.min.js`
   - `customizer-controls.js` → `customizer-controls.min.js`

## 📄 Development vs Production

| Mode | Files Used | Size | Performance |
|------|------------|------|-------------|
| Development | `assets/css/`, `assets/js/` | 85KB | Slower, readable |
| **Production** | `assets/dist/` | **47KB** | **Faster, optimized** |

The theme is currently configured for **production** use with optimized assets.

## 🚀 Quick Start

1. Download the complete theme folder
2. Upload to `/wp-content/themes/nearmenus/`
3. Activate in WordPress Admin > Appearance > Themes
4. Configure settings in Customizer
5. Start adding restaurant content!

---

**Note**: All source files are preserved in case you need to make modifications. The build system ensures your customizations won't be lost.