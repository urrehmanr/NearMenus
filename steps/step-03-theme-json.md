# Step 3: theme.json Setup for FSE

## Overview
This step creates the essential `theme.json` file that enables Full Site Editing (FSE) capabilities for the GPress theme. This file controls global styles, color palettes, typography, layout settings, and block configurations for optimal performance and consistency.

## Objectives
- Create the `theme.json` configuration file
- Enable Full Site Editing (FSE) capabilities
- Define global typography and color systems
- Configure layout and spacing controls
- Optimize performance through theme.json settings
- Ensure compatibility with Gutenberg blocks

## Files to Create in This Step

### Updated Directory Structure
```
gpress/
├── style.css                # (already exists)
├── index.php                # (already exists)
├── functions.php             # (already exists)
├── README.md                # (already exists)
├── .gitignore               # (already exists)
├── theme.json               # (new file - created in this step)
├── inc/                     # (already exists)
│   ├── theme-setup.php      # (already exists)
│   ├── enqueue-scripts.php  # (already exists)
│   ├── customizer.php       # (already exists)
│   └── block-patterns.php   # (already exists)
└── assets/                  # (already exists)
    └── js/                  # (already exists)
        ├── skip-link-focus-fix.js  # (already exists)
        └── customizer.js     # (already exists)
```

## Understanding theme.json

The `theme.json` file is the central configuration file for modern WordPress themes. It controls:
- Global styles and settings
- Block editor appearance and capabilities
- Typography systems with fluid scaling
- Color palettes and customization
- Layout constraints and spacing systems
- Performance optimizations through CSS custom properties

## Step-by-Step Implementation

### 1. Create theme.json

Create the `theme.json` file in your theme root directory:

```json
{
    "$schema": "https://schemas.wp.org/trunk/theme.json",
    "version": 3,
    "settings": {
        "appearanceTools": true,
        "useRootPaddingAwareAlignments": true,
        "layout": {
            "contentSize": "800px",
            "wideSize": "1200px"
        },
        "spacing": {
            "blockGap": true,
            "margin": true,
            "padding": true,
            "units": ["px", "em", "rem", "vh", "vw", "%"],
            "spacingScale": {
                "operator": "*",
                "increment": 1.5,
                "steps": 7,
                "mediumStep": 1.5,
                "unit": "rem"
            },
            "spacingSizes": [
                {
                    "name": "X-Small",
                    "size": "0.5rem",
                    "slug": "x-small"
                },
                {
                    "name": "Small",
                    "size": "1rem",
                    "slug": "small"
                },
                {
                    "name": "Medium",
                    "size": "1.5rem",
                    "slug": "medium"
                },
                {
                    "name": "Large",
                    "size": "2rem",
                    "slug": "large"
                },
                {
                    "name": "X-Large",
                    "size": "3rem",
                    "slug": "x-large"
                },
                {
                    "name": "XX-Large",
                    "size": "4rem",
                    "slug": "xx-large"
                }
            ]
        },
        "color": {
            "custom": false,
            "customDuotone": false,
            "customGradient": false,
            "defaultDuotones": false,
            "defaultGradients": false,
            "defaultPalette": false,
            "duotone": [],
            "gradients": [
                {
                    "name": "Primary to Secondary",
                    "slug": "primary-secondary",
                    "gradient": "linear-gradient(135deg, var(--wp--preset--color--primary) 0%, var(--wp--preset--color--secondary) 100%)"
                },
                {
                    "name": "Light to Dark",
                    "slug": "light-dark",
                    "gradient": "linear-gradient(180deg, var(--wp--preset--color--light) 0%, var(--wp--preset--color--dark) 100%)"
                }
            ],
            "palette": [
                {
                    "name": "Primary Blue",
                    "slug": "primary",
                    "color": "#3498db"
                },
                {
                    "name": "Secondary Blue",
                    "slug": "secondary",
                    "color": "#2980b9"
                },
                {
                    "name": "Accent Green",
                    "slug": "accent",
                    "color": "#27ae60"
                },
                {
                    "name": "Warning Orange",
                    "slug": "warning",
                    "color": "#f39c12"
                },
                {
                    "name": "Danger Red",
                    "slug": "danger",
                    "color": "#e74c3c"
                },
                {
                    "name": "Dark Text",
                    "slug": "dark",
                    "color": "#2c3e50"
                },
                {
                    "name": "Light Background",
                    "slug": "light",
                    "color": "#f8f9fa"
                },
                {
                    "name": "White",
                    "slug": "white",
                    "color": "#ffffff"
                },
                {
                    "name": "Black",
                    "slug": "black",
                    "color": "#000000"
                },
                {
                    "name": "Gray 100",
                    "slug": "gray-100",
                    "color": "#f8f9fa"
                },
                {
                    "name": "Gray 200",
                    "slug": "gray-200",
                    "color": "#e9ecef"
                },
                {
                    "name": "Gray 300",
                    "slug": "gray-300",
                    "color": "#dee2e6"
                },
                {
                    "name": "Gray 400",
                    "slug": "gray-400",
                    "color": "#ced4da"
                },
                {
                    "name": "Gray 500",
                    "slug": "gray-500",
                    "color": "#adb5bd"
                },
                {
                    "name": "Gray 600",
                    "slug": "gray-600",
                    "color": "#6c757d"
                },
                {
                    "name": "Gray 700",
                    "slug": "gray-700",
                    "color": "#495057"
                },
                {
                    "name": "Gray 800",
                    "slug": "gray-800",
                    "color": "#343a40"
                },
                {
                    "name": "Gray 900",
                    "slug": "gray-900",
                    "color": "#212529"
                }
            ]
        },
        "typography": {
            "customFontSize": false,
            "dropCap": true,
            "fluid": true,
            "fontStyle": true,
            "fontWeight": true,
            "letterSpacing": true,
            "lineHeight": true,
            "textDecoration": true,
            "textTransform": true,
            "fontFamilies": [
                {
                    "name": "System Font",
                    "slug": "system",
                    "fontFamily": "-apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen-Sans, Ubuntu, Cantarell, 'Helvetica Neue', sans-serif"
                },
                {
                    "name": "Serif",
                    "slug": "serif",
                    "fontFamily": "Georgia, 'Times New Roman', Times, serif"
                },
                {
                    "name": "Monospace",
                    "slug": "monospace",
                    "fontFamily": "Consolas, Monaco, 'Andale Mono', 'Ubuntu Mono', monospace"
                }
            ],
            "fontSizes": [
                {
                    "name": "Extra Small",
                    "slug": "x-small",
                    "size": "0.75rem",
                    "fluid": {
                        "min": "0.75rem",
                        "max": "0.875rem"
                    }
                },
                {
                    "name": "Small",
                    "slug": "small",
                    "size": "0.875rem",
                    "fluid": {
                        "min": "0.875rem",
                        "max": "1rem"
                    }
                },
                {
                    "name": "Medium",
                    "slug": "medium",
                    "size": "1rem",
                    "fluid": {
                        "min": "1rem",
                        "max": "1.125rem"
                    }
                },
                {
                    "name": "Large",
                    "slug": "large",
                    "size": "1.25rem",
                    "fluid": {
                        "min": "1.125rem",
                        "max": "1.5rem"
                    }
                },
                {
                    "name": "Extra Large",
                    "slug": "x-large",
                    "size": "1.75rem",
                    "fluid": {
                        "min": "1.5rem",
                        "max": "2rem"
                    }
                },
                {
                    "name": "XX Large",
                    "slug": "xx-large",
                    "size": "2.25rem",
                    "fluid": {
                        "min": "2rem",
                        "max": "2.5rem"
                    }
                },
                {
                    "name": "Huge",
                    "slug": "huge",
                    "size": "3rem",
                    "fluid": {
                        "min": "2.5rem",
                        "max": "3.5rem"
                    }
                }
            ]
        },
        "border": {
            "color": true,
            "radius": true,
            "style": true,
            "width": true
        },
        "shadow": {
            "defaultPresets": false,
            "presets": [
                {
                    "name": "Small",
                    "slug": "small",
                    "shadow": "0 1px 3px rgba(0, 0, 0, 0.12), 0 1px 2px rgba(0, 0, 0, 0.24)"
                },
                {
                    "name": "Medium",
                    "slug": "medium",
                    "shadow": "0 3px 6px rgba(0, 0, 0, 0.16), 0 3px 6px rgba(0, 0, 0, 0.23)"
                },
                {
                    "name": "Large",
                    "slug": "large",
                    "shadow": "0 10px 20px rgba(0, 0, 0, 0.19), 0 6px 6px rgba(0, 0, 0, 0.23)"
                },
                {
                    "name": "Extra Large",
                    "slug": "x-large",
                    "shadow": "0 14px 28px rgba(0, 0, 0, 0.25), 0 10px 10px rgba(0, 0, 0, 0.22)"
                }
            ]
        },
        "dimensions": {
            "minHeight": true
        },
        "position": {
            "sticky": true
        },
        "blocks": {
            "core/heading": {
                "typography": {
                    "fontSizes": [
                        {
                            "name": "Small",
                            "slug": "small",
                            "size": "1.25rem"
                        },
                        {
                            "name": "Medium",
                            "slug": "medium",
                            "size": "1.5rem"
                        },
                        {
                            "name": "Large",
                            "slug": "large",
                            "size": "2rem"
                        },
                        {
                            "name": "Extra Large",
                            "slug": "x-large",
                            "size": "2.5rem"
                        },
                        {
                            "name": "Huge",
                            "slug": "huge",
                            "size": "3rem"
                        }
                    ]
                }
            },
            "core/button": {
                "border": {
                    "radius": true
                },
                "color": {
                    "background": true,
                    "text": true
                },
                "spacing": {
                    "padding": true
                },
                "typography": {
                    "fontSize": true,
                    "fontWeight": true
                }
            },
            "core/pullquote": {
                "border": {
                    "color": true,
                    "style": true,
                    "width": true
                }
            },
            "core/separator": {
                "color": {
                    "background": true
                }
            },
            "core/cover": {
                "color": {
                    "background": true,
                    "text": true
                },
                "spacing": {
                    "padding": true,
                    "margin": true
                }
            },
            "core/group": {
                "spacing": {
                    "padding": true,
                    "margin": true,
                    "blockGap": true
                },
                "color": {
                    "background": true,
                    "text": true
                },
                "border": {
                    "radius": true
                }
            }
        }
    },
    "styles": {
        "color": {
            "background": "var(--wp--preset--color--white)",
            "text": "var(--wp--preset--color--gray-800)"
        },
        "typography": {
            "fontFamily": "var(--wp--preset--font-family--system)",
            "fontSize": "var(--wp--preset--font-size--medium)",
            "lineHeight": "1.6"
        },
        "spacing": {
            "blockGap": "1.5rem"
        },
        "elements": {
            "link": {
                "color": {
                    "text": "var(--wp--preset--color--primary)"
                },
                ":hover": {
                    "color": {
                        "text": "var(--wp--preset--color--secondary)"
                    }
                },
                ":focus": {
                    "color": {
                        "text": "var(--wp--preset--color--secondary)"
                    }
                }
            },
            "h1": {
                "typography": {
                    "fontSize": "var(--wp--preset--font-size--xx-large)",
                    "fontWeight": "700",
                    "lineHeight": "1.2"
                },
                "spacing": {
                    "margin": {
                        "bottom": "1rem"
                    }
                }
            },
            "h2": {
                "typography": {
                    "fontSize": "var(--wp--preset--font-size--x-large)",
                    "fontWeight": "600",
                    "lineHeight": "1.3"
                },
                "spacing": {
                    "margin": {
                        "top": "2rem",
                        "bottom": "1rem"
                    }
                }
            },
            "h3": {
                "typography": {
                    "fontSize": "var(--wp--preset--font-size--large)",
                    "fontWeight": "600",
                    "lineHeight": "1.4"
                },
                "spacing": {
                    "margin": {
                        "top": "1.5rem",
                        "bottom": "0.75rem"
                    }
                }
            },
            "h4": {
                "typography": {
                    "fontSize": "var(--wp--preset--font-size--medium)",
                    "fontWeight": "600",
                    "lineHeight": "1.4"
                }
            },
            "h5": {
                "typography": {
                    "fontSize": "var(--wp--preset--font-size--small)",
                    "fontWeight": "600",
                    "lineHeight": "1.4"
                }
            },
            "h6": {
                "typography": {
                    "fontSize": "var(--wp--preset--font-size--x-small)",
                    "fontWeight": "600",
                    "lineHeight": "1.4"
                }
            },
            "button": {
                "color": {
                    "background": "var(--wp--preset--color--primary)",
                    "text": "var(--wp--preset--color--white)"
                },
                "typography": {
                    "fontSize": "var(--wp--preset--font-size--medium)",
                    "fontWeight": "500"
                },
                "spacing": {
                    "padding": {
                        "top": "0.75rem",
                        "bottom": "0.75rem",
                        "left": "1.5rem",
                        "right": "1.5rem"
                    }
                },
                "border": {
                    "radius": "4px"
                },
                ":hover": {
                    "color": {
                        "background": "var(--wp--preset--color--secondary)"
                    }
                },
                ":focus": {
                    "color": {
                        "background": "var(--wp--preset--color--secondary)"
                    }
                }
            }
        },
        "blocks": {
            "core/navigation": {
                "typography": {
                    "fontSize": "var(--wp--preset--font-size--medium)",
                    "fontWeight": "500"
                },
                "elements": {
                    "link": {
                        "typography": {
                            "textDecoration": "none"
                        },
                        ":hover": {
                            "typography": {
                                "textDecoration": "underline"
                            }
                        }
                    }
                }
            },
            "core/post-title": {
                "typography": {
                    "fontSize": "var(--wp--preset--font-size--x-large)",
                    "fontWeight": "700",
                    "lineHeight": "1.2"
                },
                "spacing": {
                    "margin": {
                        "bottom": "1rem"
                    }
                }
            },
            "core/post-excerpt": {
                "typography": {
                    "fontSize": "var(--wp--preset--font-size--medium)",
                    "lineHeight": "1.6"
                },
                "color": {
                    "text": "var(--wp--preset--color--gray-600)"
                }
            },
            "core/post-date": {
                "typography": {
                    "fontSize": "var(--wp--preset--font-size--small)"
                },
                "color": {
                    "text": "var(--wp--preset--color--gray-500)"
                }
            },
            "core/post-author": {
                "typography": {
                    "fontSize": "var(--wp--preset--font-size--small)"
                },
                "color": {
                    "text": "var(--wp--preset--color--gray-600)"
                }
            },
            "core/quote": {
                "border": {
                    "width": "0 0 0 4px",
                    "style": "solid",
                    "color": "var(--wp--preset--color--gray-300)"
                },
                "spacing": {
                    "padding": {
                        "left": "1.5rem"
                    }
                },
                "typography": {
                    "fontSize": "var(--wp--preset--font-size--large)",
                    "fontStyle": "italic"
                }
            },
            "core/pullquote": {
                "border": {
                    "width": "2px 0",
                    "style": "solid",
                    "color": "var(--wp--preset--color--gray-300)"
                },
                "spacing": {
                    "padding": {
                        "top": "2rem",
                        "bottom": "2rem"
                    }
                },
                "typography": {
                    "fontSize": "var(--wp--preset--font-size--large)",
                    "fontStyle": "italic",
                    "textAlign": "center"
                }
            },
            "core/code": {
                "color": {
                    "background": "var(--wp--preset--color--gray-100)",
                    "text": "var(--wp--preset--color--gray-800)"
                },
                "typography": {
                    "fontFamily": "var(--wp--preset--font-family--monospace)",
                    "fontSize": "var(--wp--preset--font-size--small)"
                },
                "spacing": {
                    "padding": {
                        "top": "1rem",
                        "bottom": "1rem",
                        "left": "1rem",
                        "right": "1rem"
                    }
                },
                "border": {
                    "radius": "4px"
                }
            },
            "core/preformatted": {
                "color": {
                    "background": "var(--wp--preset--color--gray-100)",
                    "text": "var(--wp--preset--color--gray-800)"
                },
                "typography": {
                    "fontFamily": "var(--wp--preset--font-family--monospace)",
                    "fontSize": "var(--wp--preset--font-size--small)"
                },
                "spacing": {
                    "padding": {
                        "top": "1rem",
                        "bottom": "1rem",
                        "left": "1rem",
                        "right": "1rem"
                    }
                },
                "border": {
                    "radius": "4px"
                }
            },
            "core/table": {
                "typography": {
                    "fontSize": "var(--wp--preset--font-size--small)"
                }
            },
            "core/separator": {
                "color": {
                    "background": "var(--wp--preset--color--gray-200)"
                }
            },
            "core/cover": {
                "color": {
                    "text": "var(--wp--preset--color--white)"
                },
                "spacing": {
                    "padding": {
                        "top": "3rem",
                        "bottom": "3rem",
                        "left": "2rem",
                        "right": "2rem"
                    }
                }
            }
        }
    },
    "templateParts": [
        {
            "name": "header",
            "title": "Header",
            "area": "header"
        },
        {
            "name": "footer",
            "title": "Footer",
            "area": "footer"
        },
        {
            "name": "sidebar",
            "title": "Sidebar",
            "area": "uncategorized"
        }
    ],
    "customTemplates": [
        {
            "name": "blank",
            "title": "Blank Template",
            "postTypes": ["page", "post"]
        },
        {
            "name": "page-no-title",
            "title": "Page without Title",
            "postTypes": ["page"]
        }
    ]
}
```

### 2. Update functions.php for FSE Support

Add this to your existing `functions.php` after the existing content:

```php
/**
 * Add FSE-specific theme support
 */
function gpress_fse_setup() {
    // Add support for block templates
    add_theme_support('block-templates');
    
    // Add support for block template parts
    add_theme_support('block-template-parts');
    
    // Remove core block patterns (we'll add our own)
    remove_theme_support('core-block-patterns');
}
add_action('after_setup_theme', 'gpress_fse_setup', 20);

/**
 * Enqueue theme.json styles for the editor
 */
function gpress_editor_styles() {
    // The theme.json will automatically be loaded
    // This function is for any additional editor styles if needed
    wp_enqueue_style(
        'gpress-editor-style',
        get_template_directory_uri() . '/assets/css/editor-style.css',
        array(),
        GPRESS_VERSION
    );
}
add_action('enqueue_block_editor_assets', 'gpress_editor_styles');
```

## Key Configuration Explanations

### Layout Settings
- **contentSize**: Maximum width for normal content (800px)
- **wideSize**: Maximum width for wide-aligned blocks (1200px)
- **useRootPaddingAwareAlignments**: Enables better alignment handling

### Typography System
- **fluid**: Enables responsive font sizes that scale between devices
- **customFontSize**: Disabled to maintain consistency
- **fontFamilies**: System fonts for performance and compatibility

### Color System
- **custom**: Disabled to prevent color picker abuse
- **defaultPalette**: Disabled to remove WordPress defaults
- **palette**: Carefully curated brand colors with semantic naming

### Spacing System
- **spacingScale**: Generates consistent spacing using mathematical progression
- **spacingSizes**: Predefined spacing options for editors

### Performance Features
- Disables unnecessary default WordPress styles
- Generates optimized CSS custom properties
- Enables fluid typography for fewer media queries
- Uses system fonts to avoid external font loading

## Testing Instructions

After completing this step, perform these comprehensive tests:

### 1. JSON Validation Test
```bash
# Validate the JSON syntax
node -pe "JSON.parse(require('fs').readFileSync('theme.json', 'utf8'))"
# or use an online JSON validator
```

### 2. Theme Activation Test
1. Refresh WordPress Admin → Appearance → Themes
2. Re-activate the GPress theme
3. Verify no errors appear
4. Check that FSE features are enabled

### 3. Block Editor Test
1. Create a new post
2. Test the following features:
   - **Color Palette**: Check that GPress colors appear in color settings
   - **Font Sizes**: Verify custom font sizes are available
   - **Spacing**: Test padding/margin controls show custom spacing
   - **Typography**: Check font family options
   - **Shadows**: Verify shadow presets are available

### 4. Frontend Styling Test
1. Add various blocks to a test post (headings, paragraphs, buttons, quotes)
2. Publish the post and view it on the frontend
3. Verify that theme.json styles are being applied
4. Check responsive behavior at different screen sizes

### 5. Site Editor Test (WordPress 5.9+)
1. Go to Appearance → Site Editor (if available)
2. Verify templates and template parts are accessible
3. Test global styles interface
4. Check theme color and typography settings

### 6. Performance Test
1. Run Lighthouse test on homepage
2. Should maintain 90+ performance score
3. Check that CSS custom properties are generated
4. Verify no console errors

### 7. Customizer Test
1. Go to Appearance → Customize
2. Check Global Styles section (if available)
3. Test color and typography changes
4. Verify changes apply in real-time

### 8. Accessibility Test
1. Verify proper color contrast with new color palette
2. Check that font sizes remain readable
3. Test keyboard navigation in block editor

## Expected Results

After completing Step 3, you should have:

- ✅ A valid `theme.json` file with comprehensive configuration
- ✅ Full Site Editing (FSE) capabilities enabled
- ✅ Custom color palette available in block editor
- ✅ Fluid typography system working across devices
- ✅ Consistent spacing system throughout the theme
- ✅ Optimized CSS custom properties generated
- ✅ Enhanced block editor experience
- ✅ Foundation for block templates and template parts

## Performance Benefits

1. **Reduced CSS Bloat**: Only necessary styles are generated
2. **CSS Custom Properties**: Automatic generation for consistent theming
3. **Fluid Typography**: Better responsive design with fewer media queries
4. **Consistent Design System**: Global styles prevent style conflicts
5. **Better Caching**: CSS custom properties are cacheable
6. **Minimal JavaScript**: No JS needed for style management

## Next Step

Proceed to [Step 4: Block Templates Creation](./step-04-block-templates.md) to create HTML block templates that utilize the theme.json configuration for consistent styling and layout.

## Troubleshooting

**Colors not appearing in editor:**
- Check JSON syntax validity using a JSON validator
- Verify palette array structure is correct
- Clear browser cache and editor cache

**Font sizes not working:**
- Ensure fontSize setting is enabled in typography
- Check fontSizes array format matches specification
- Verify fluid typography settings are correct

**Styles not applying on frontend:**
- Check that theme.json is in the root directory
- Verify CSS custom property names are correctly generated
- Ensure no conflicting styles in style.css

**FSE not working:**
- Confirm WordPress version is 5.9 or higher
- Check that block template support is added in functions.php
- Verify theme.json version is set to 2 or 3

**Performance issues:**
- Confirm default palettes and gradients are disabled
- Check that unnecessary WordPress defaults are removed
- Verify theme.json is properly formatted and not causing parsing errors