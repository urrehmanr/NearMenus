# Step 3: theme.json Setup for Full Site Editing

## Objective
Configure the `theme.json` file to enable Full Site Editing (FSE) capabilities, define global styles, typography, colors, and layout settings for optimal performance and user experience.

## What You'll Learn
- theme.json structure and configuration
- Global styling with theme.json
- Typography and color systems
- Layout and spacing controls
- Block customization and styling
- Performance optimization through theme.json

## Understanding theme.json

The `theme.json` file is the central configuration file for modern WordPress themes. It controls:
- Global styles and settings
- Block editor appearance
- Typography systems
- Color palettes
- Layout and spacing
- Custom CSS properties

## Complete theme.json Configuration

Create `theme.json` in your theme root directory:

```json
{
    "$schema": "https://schemas.wp.org/trunk/theme.json",
    "version": 2,
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
            "gradients": [],
            "palette": [
                {
                    "name": "Primary",
                    "slug": "primary",
                    "color": "#2c3e50"
                },
                {
                    "name": "Secondary",
                    "slug": "secondary",
                    "color": "#3498db"
                },
                {
                    "name": "Accent",
                    "slug": "accent",
                    "color": "#e74c3c"
                },
                {
                    "name": "Success",
                    "slug": "success",
                    "color": "#27ae60"
                },
                {
                    "name": "Warning",
                    "slug": "warning",
                    "color": "#f39c12"
                },
                {
                    "name": "Light",
                    "slug": "light",
                    "color": "#f8f9fa"
                },
                {
                    "name": "Dark",
                    "slug": "dark",
                    "color": "#2c3e50"
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
                    "name": "Georgia",
                    "slug": "georgia",
                    "fontFamily": "Georgia, serif"
                },
                {
                    "name": "Times",
                    "slug": "times",
                    "fontFamily": "'Times New Roman', Times, serif"
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

## Performance Optimization Features

### 1. CSS Custom Properties Generation
The `theme.json` automatically generates CSS custom properties:

```css
/* Auto-generated by WordPress */
:root {
    --wp--preset--color--primary: #2c3e50;
    --wp--preset--color--secondary: #3498db;
    --wp--preset--font-size--medium: 1rem;
    --wp--preset--spacing--small: 1rem;
    /* ... and many more */
}
```

### 2. Reduced CSS Bloat
By disabling default palettes and gradients:
- Removes unused WordPress default CSS
- Generates only needed custom properties
- Reduces total CSS size

### 3. Fluid Typography
Fluid font sizes automatically scale between viewports:
- Better responsive design
- Fewer media queries needed
- Improved performance

## Key Configuration Explanations

### Layout Settings
```json
"layout": {
    "contentSize": "800px",    // Max width for normal content
    "wideSize": "1200px"       // Max width for wide-aligned blocks
}
```

### Typography System
```json
"typography": {
    "fluid": true,             // Enable fluid typography
    "customFontSize": false,   // Disable custom font sizes
    "fontFamilies": [...]      // Define font stack
}
```

### Color System
```json
"color": {
    "custom": false,           // Disable custom color picker
    "defaultPalette": false,   // Remove WordPress default colors
    "palette": [...]           // Define theme colors
}
```

### Spacing System
```json
"spacing": {
    "spacingScale": {          // Generate consistent spacing
        "operator": "*",
        "increment": 1.5,
        "steps": 7
    }
}
```

## Block-Specific Styling

The theme.json allows targeting specific blocks:

```json
"blocks": {
    "core/heading": {
        "typography": {
            "fontSizes": [...]
        }
    },
    "core/button": {
        "border": {
            "radius": true
        }
    }
}
```

## Template Parts Configuration

Define reusable template parts:

```json
"templateParts": [
    {
        "name": "header",
        "title": "Header",
        "area": "header"
    }
]
```

## Verification Checklist

After creating theme.json:

- [ ] File is valid JSON (use JSON validator)
- [ ] Theme editor shows correct colors
- [ ] Font sizes appear in editor
- [ ] Spacing presets are available
- [ ] Block styles are applied correctly
- [ ] No console errors in block editor

## Testing the Configuration

1. **Block Editor Test:**
   - Create a new post
   - Verify color palette appears
   - Test font size options
   - Check spacing controls

2. **Frontend Test:**
   - View published content
   - Verify styles are applied
   - Check responsive behavior
   - Test performance impact

3. **Customizer Test:**
   - Check global styles interface
   - Test color changes
   - Verify typography updates

## Next Steps

In Step 4, we'll create block templates that utilize the theme.json configuration for consistent styling and layout.

## Performance Benefits

1. **Reduced CSS**: Only necessary styles are generated
2. **Consistent Design**: Global styles prevent style conflicts
3. **Better Caching**: CSS custom properties are cacheable
4. **Fluid Typography**: Better performance across devices
5. **Minimal JavaScript**: No JS needed for style management

## Advanced Configuration Options

### Custom CSS Variables
Add custom CSS properties in styles:

```json
"styles": {
    "css": "--custom-variable: value;"
}
```

### Responsive Breakpoints
Configure responsive behavior:

```json
"settings": {
    "custom": {
        "breakpoints": {
            "tablet": "768px",
            "desktop": "1024px"
        }
    }
}
```

## Troubleshooting

**Colors not appearing:**
- Check JSON syntax validity
- Verify palette array structure
- Clear browser cache

**Font sizes not working:**
- Ensure fontSize is enabled
- Check fontSizes array format
- Verify fluid typography settings

**Styles not applying:**
- Check block-specific settings
- Verify CSS custom property names
- Ensure theme.json is in root directory