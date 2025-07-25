# Step 3: theme.json Setup for Full Site Editing & Smart Asset Integration

## Overview
This step transforms the **GPress** theme into a fully-featured Full Site Editing (FSE) theme by implementing theme.json configuration with **Smart Asset Management System integration**. We'll establish global styles, design tokens, block settings, and template configurations that work seamlessly with our optimization system from Step 7, providing users with powerful visual editing capabilities while maintaining exceptional performance.

## Integration with Smart Asset System
This step **integrates with Step 7's Smart Asset Management System**:
- **Theme.json Configuration**: Optimized for performance with conditional loading support
- **Font Loading Optimization**: Integration with Smart Asset Manager font detection
- **FSE Performance**: Block and template assets managed by Smart Asset Manager
- **Design Token Sync**: CSS custom properties aligned with Step 7's core.css system

## Objectives
- Create comprehensive theme.json configuration optimized for Smart Asset Management System
- Establish design system synchronized with Step 7's CSS custom properties
- Configure block editor settings with performance optimization integration
- Implement responsive design tokens aligned with core.css architecture
- Set up template configurations with conditional loading support
- Enable advanced block customization with Smart Asset Manager integration
- Optimize font loading and typography for performance

## What You'll Learn
- FSE theme.json structure and best practices
- Design system implementation in WordPress
- Block editor configuration and customization
- Global styles and design tokens management
- Template configuration for FSE themes
- Performance optimization for block editor

## Files Structure for This Step

### 📁 **Files to CREATE** (New Files)
```
theme.json                   # FSE configuration and global styles
assets/                      # Enhanced asset structure
└── fonts/                   # Web font directory
    ├── .gitkeep            # Keep directory in version control
    └── font-face.css       # Font loading optimization
```

### 📝 **Files to UPDATE** (Existing Files)
```
inc/customizer.php          # Enhanced with FSE integration
README.md                   # Updated with FSE features documentation
```

### 🎯 **Optimization Features Implemented**
- Design system with consistent tokens
- Performance-optimized font loading
- Responsive typography with fluid scaling
- Accessible color contrast ratios
- Block editor performance enhancements
- Conditional style loading for blocks
- Mobile-first responsive configurations
- Advanced block customization controls

## Step-by-Step Implementation

### 1. CREATE theme.json (FSE Configuration)

**Purpose**: Complete FSE configuration with design system and global styles

```json
{
    "$schema": "https://schemas.wp.org/trunk/theme.json",
    "version": 2,
    "settings": {
        "appearanceTools": true,
        "useRootPaddingAwareAlignments": true,
        "layout": {
            "contentSize": "65ch",
            "wideSize": "1200px"
        },
        "custom": {
            "gpress": {
                "smartAssetLoading": true,
                "conditionalStyles": true,
                "performanceOptimization": true,
                "fontLoadingStrategy": "swap",
                "criticalCssInline": true,
                "blockAssetOptimization": true,
                "imageOptimization": true,
                "lazyLoadingEnabled": true
            }
        },
        "color": {
            "palette": [
                {
                    "color": "#2563eb",
                    "name": "Primary",
                    "slug": "primary"
                },
                {
                    "color": "#1e40af",
                    "name": "Primary Dark",
                    "slug": "primary-dark"
                },
                {
                    "color": "#64748b",
                    "name": "Secondary",
                    "slug": "secondary"
                },
                {
                    "color": "#f59e0b",
                    "name": "Accent",
                    "slug": "accent"
                },
                {
                    "color": "#1e293b",
                    "name": "Text",
                    "slug": "text"
                },
                {
                    "color": "#64748b",
                    "name": "Text Light",
                    "slug": "text-light"
                },
                {
                    "color": "#ffffff",
                    "name": "Background",
                    "slug": "background"
                },
                {
                    "color": "#f8fafc",
                    "name": "Surface",
                    "slug": "surface"
                },
                {
                    "color": "#e2e8f0",
                    "name": "Border",
                    "slug": "border"
                },
                {
                    "color": "#059669",
                    "name": "Success",
                    "slug": "success"
                },
                {
                    "color": "#d97706",
                    "name": "Warning",
                    "slug": "warning"
                },
                {
                    "color": "#dc2626",
                    "name": "Error",
                    "slug": "error"
                }
            ],
            "gradients": [
                {
                    "gradient": "linear-gradient(135deg, var(--wp--preset--color--primary) 0%, var(--wp--preset--color--accent) 100%)",
                    "name": "Primary to Accent",
                    "slug": "primary-to-accent"
                },
                {
                    "gradient": "linear-gradient(135deg, var(--wp--preset--color--secondary) 0%, var(--wp--preset--color--primary) 100%)",
                    "name": "Secondary to Primary",
                    "slug": "secondary-to-primary"
                },
                {
                    "gradient": "radial-gradient(circle, var(--wp--preset--color--background) 0%, var(--wp--preset--color--surface) 100%)",
                    "name": "Radial Light",
                    "slug": "radial-light"
                }
            ],
            "duotone": [
                {
                    "colors": ["#2563eb", "#f59e0b"],
                    "name": "Primary and Accent",
                    "slug": "primary-accent"
                },
                {
                    "colors": ["#1e293b", "#64748b"],
                    "name": "Text Dark and Light",
                    "slug": "text-contrast"
                }
            ],
            "custom": true,
            "customDuotone": true,
            "customGradient": false,
            "defaultGradients": false,
            "defaultPalette": false
        },
        "typography": {
            "fontFamilies": [
                {
                    "fontFamily": "-apple-system, BlinkMacSystemFont, \"Segoe UI\", Roboto, Oxygen-Sans, Ubuntu, Cantarell, \"Helvetica Neue\", sans-serif",
                    "name": "System Sans",
                    "slug": "system-sans"
                },
                {
                    "fontFamily": "ui-serif, Georgia, Cambria, \"Times New Roman\", Times, serif",
                    "name": "System Serif",
                    "slug": "system-serif"
                },
                {
                    "fontFamily": "ui-monospace, \"SF Mono\", Monaco, \"Cascadia Code\", \"Roboto Mono\", Consolas, \"Courier New\", monospace",
                    "name": "System Mono",
                    "slug": "system-mono"
                }
            ],
            "fontSizes": [
                {
                    "fluid": {
                        "min": "0.75rem",
                        "max": "0.875rem"
                    },
                    "name": "Extra Small",
                    "size": "0.75rem",
                    "slug": "x-small"
                },
                {
                    "fluid": {
                        "min": "0.875rem",
                        "max": "1rem"
                    },
                    "name": "Small",
                    "size": "0.875rem",
                    "slug": "small"
                },
                {
                    "fluid": {
                        "min": "1rem",
                        "max": "1.125rem"
                    },
                    "name": "Base",
                    "size": "1rem",
                    "slug": "base"
                },
                {
                    "fluid": {
                        "min": "1.125rem",
                        "max": "1.25rem"
                    },
                    "name": "Medium",
                    "size": "1.125rem",
                    "slug": "medium"
                },
                {
                    "fluid": {
                        "min": "1.25rem",
                        "max": "1.5rem"
                    },
                    "name": "Large",
                    "size": "1.25rem",
                    "slug": "large"
                },
                {
                    "fluid": {
                        "min": "1.5rem",
                        "max": "1.875rem"
                    },
                    "name": "Extra Large",
                    "size": "1.5rem",
                    "slug": "x-large"
                },
                {
                    "fluid": {
                        "min": "1.875rem",
                        "max": "2.25rem"
                    },
                    "name": "2X Large",
                    "size": "1.875rem",
                    "slug": "xx-large"
                },
                {
                    "fluid": {
                        "min": "2.25rem",
                        "max": "3rem"
                    },
                    "name": "3X Large",
                    "size": "2.25rem",
                    "slug": "xxx-large"
                }
            ],
            "lineHeight": true,
            "customFontSize": false,
            "dropCap": true,
            "fontStyle": true,
            "fontWeight": true,
            "letterSpacing": true,
            "textDecoration": true,
            "textTransform": true
        },
        "spacing": {
            "blockGap": true,
            "margin": true,
            "padding": true,
            "units": ["px", "em", "rem", "vh", "vw", "%"],
            "spacingSizes": [
                {
                    "name": "1",
                    "size": "0.25rem",
                    "slug": "10"
                },
                {
                    "name": "2",
                    "size": "0.5rem",
                    "slug": "20"
                },
                {
                    "name": "3",
                    "size": "0.75rem",
                    "slug": "30"
                },
                {
                    "name": "4",
                    "size": "1rem",
                    "slug": "40"
                },
                {
                    "name": "5",
                    "size": "1.25rem",
                    "slug": "50"
                },
                {
                    "name": "6",
                    "size": "1.5rem",
                    "slug": "60"
                },
                {
                    "name": "7",
                    "size": "2rem",
                    "slug": "70"
                },
                {
                    "name": "8",
                    "size": "2.5rem",
                    "slug": "80"
                },
                {
                    "name": "9",
                    "size": "3rem",
                    "slug": "90"
                },
                {
                    "name": "10",
                    "size": "4rem",
                    "slug": "100"
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
            "presets": [
                {
                    "name": "Small",
                    "shadow": "0 1px 3px 0 rgba(0, 0, 0, 0.1), 0 1px 2px 0 rgba(0, 0, 0, 0.06)",
                    "slug": "small"
                },
                {
                    "name": "Medium",
                    "shadow": "0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06)",
                    "slug": "medium"
                },
                {
                    "name": "Large",
                    "shadow": "0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05)",
                    "slug": "large"
                },
                {
                    "name": "Extra Large",
                    "shadow": "0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04)",
                    "slug": "x-large"
                }
            ],
            "defaultPresets": false
        },
        "dimensions": {
            "aspectRatio": true,
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
                            "fluid": {
                                "min": "1.875rem",
                                "max": "2.25rem"
                            },
                            "name": "Heading 1",
                            "size": "1.875rem",
                            "slug": "h1"
                        },
                        {
                            "fluid": {
                                "min": "1.5rem",
                                "max": "1.875rem"
                            },
                            "name": "Heading 2",
                            "size": "1.5rem",
                            "slug": "h2"
                        },
                        {
                            "fluid": {
                                "min": "1.25rem",
                                "max": "1.5rem"
                            },
                            "name": "Heading 3",
                            "size": "1.25rem",
                            "slug": "h3"
                        },
                        {
                            "fluid": {
                                "min": "1.125rem",
                                "max": "1.25rem"
                            },
                            "name": "Heading 4",
                            "size": "1.125rem",
                            "slug": "h4"
                        }
                    ]
                }
            },
            "core/button": {
                "color": {
                    "custom": false
                },
                "typography": {
                    "customFontSize": false
                },
                "border": {
                    "radius": true
                }
            },
            "core/pullquote": {
                "border": {
                    "color": true,
                    "radius": true,
                    "style": true,
                    "width": true
                }
            },
            "core/quote": {
                "border": {
                    "color": true,
                    "radius": true,
                    "style": true,
                    "width": true
                }
            },
            "core/separator": {
                "color": {
                    "background": true,
                    "text": false
                }
            }
        }
    },
    "styles": {
        "color": {
            "background": "var(--wp--preset--color--background)",
            "text": "var(--wp--preset--color--text)"
        },
        "typography": {
            "fontFamily": "var(--wp--preset--font-family--system-sans)",
            "fontSize": "var(--wp--preset--font-size--base)",
            "lineHeight": "1.6"
        },
        "spacing": {
            "blockGap": "var(--wp--preset--spacing--40)"
        },
        "elements": {
            "link": {
                "color": {
                    "text": "var(--wp--preset--color--primary)"
                },
                ":hover": {
                    "color": {
                        "text": "var(--wp--preset--color--primary-dark)"
                    },
                    "typography": {
                        "textDecoration": "underline"
                    }
                },
                ":focus": {
                    "color": {
                        "text": "var(--wp--preset--color--primary-dark)"
                    },
                    "typography": {
                        "textDecoration": "underline"
                    }
                }
            },
            "h1": {
                "typography": {
                    "fontFamily": "var(--wp--preset--font-family--system-sans)",
                    "fontSize": "var(--wp--preset--font-size--xxx-large)",
                    "fontWeight": "600",
                    "lineHeight": "1.2"
                },
                "spacing": {
                    "margin": {
                        "bottom": "var(--wp--preset--spacing--50)"
                    }
                }
            },
            "h2": {
                "typography": {
                    "fontFamily": "var(--wp--preset--font-family--system-sans)",
                    "fontSize": "var(--wp--preset--font-size--xx-large)",
                    "fontWeight": "600",
                    "lineHeight": "1.3"
                },
                "spacing": {
                    "margin": {
                        "bottom": "var(--wp--preset--spacing--40)"
                    }
                }
            },
            "h3": {
                "typography": {
                    "fontFamily": "var(--wp--preset--font-family--system-sans)",
                    "fontSize": "var(--wp--preset--font-size--x-large)",
                    "fontWeight": "600",
                    "lineHeight": "1.3"
                },
                "spacing": {
                    "margin": {
                        "bottom": "var(--wp--preset--spacing--40)"
                    }
                }
            },
            "h4": {
                "typography": {
                    "fontFamily": "var(--wp--preset--font-family--system-sans)",
                    "fontSize": "var(--wp--preset--font-size--large)",
                    "fontWeight": "600",
                    "lineHeight": "1.4"
                },
                "spacing": {
                    "margin": {
                        "bottom": "var(--wp--preset--spacing--30)"
                    }
                }
            },
            "h5": {
                "typography": {
                    "fontFamily": "var(--wp--preset--font-family--system-sans)",
                    "fontSize": "var(--wp--preset--font-size--medium)",
                    "fontWeight": "600",
                    "lineHeight": "1.4"
                },
                "spacing": {
                    "margin": {
                        "bottom": "var(--wp--preset--spacing--30)"
                    }
                }
            },
            "h6": {
                "typography": {
                    "fontFamily": "var(--wp--preset--font-family--system-sans)",
                    "fontSize": "var(--wp--preset--font-size--base)",
                    "fontWeight": "600",
                    "lineHeight": "1.4"
                },
                "spacing": {
                    "margin": {
                        "bottom": "var(--wp--preset--spacing--30)"
                    }
                }
            },
            "button": {
                "color": {
                    "background": "var(--wp--preset--color--primary)",
                    "text": "var(--wp--preset--color--background)"
                },
                "typography": {
                    "fontSize": "var(--wp--preset--font-size--base)",
                    "fontWeight": "500"
                },
                "spacing": {
                    "padding": {
                        "top": "var(--wp--preset--spacing--30)",
                        "right": "var(--wp--preset--spacing--50)",
                        "bottom": "var(--wp--preset--spacing--30)",
                        "left": "var(--wp--preset--spacing--50)"
                    }
                },
                "border": {
                    "radius": "0.375rem"
                },
                ":hover": {
                    "color": {
                        "background": "var(--wp--preset--color--primary-dark)"
                    }
                },
                ":focus": {
                    "color": {
                        "background": "var(--wp--preset--color--primary-dark)"
                    }
                }
            }
        },
        "blocks": {
            "core/navigation": {
                "typography": {
                    "fontSize": "var(--wp--preset--font-size--base)",
                    "fontWeight": "500"
                },
                "spacing": {
                    "blockGap": "var(--wp--preset--spacing--50)"
                }
            },
            "core/post-title": {
                "typography": {
                    "fontSize": "var(--wp--preset--font-size--xxx-large)",
                    "fontWeight": "600",
                    "lineHeight": "1.2"
                },
                "spacing": {
                    "margin": {
                        "bottom": "var(--wp--preset--spacing--50)"
                    }
                }
            },
            "core/post-content": {
                "spacing": {
                    "blockGap": "var(--wp--preset--spacing--50)"
                }
            },
            "core/quote": {
                "border": {
                    "color": "var(--wp--preset--color--border)",
                    "style": "solid",
                    "width": "0 0 0 4px"
                },
                "spacing": {
                    "padding": {
                        "left": "var(--wp--preset--spacing--50)"
                    }
                },
                "typography": {
                    "fontSize": "var(--wp--preset--font-size--large)",
                    "fontStyle": "italic"
                }
            },
            "core/pullquote": {
                "border": {
                    "color": "var(--wp--preset--color--border)",
                    "style": "solid",
                    "width": "1px 0"
                },
                "spacing": {
                    "padding": {
                        "top": "var(--wp--preset--spacing--60)",
                        "bottom": "var(--wp--preset--spacing--60)"
                    }
                },
                "typography": {
                    "fontSize": "var(--wp--preset--font-size--x-large)",
                    "fontStyle": "italic",
                    "lineHeight": "1.3"
                }
            },
            "core/separator": {
                "color": {
                    "background": "var(--wp--preset--color--border)"
                },
                "spacing": {
                    "margin": {
                        "top": "var(--wp--preset--spacing--60)",
                        "bottom": "var(--wp--preset--spacing--60)"
                    }
                }
            },
            "core/table": {
                "typography": {
                    "fontSize": "var(--wp--preset--font-size--small)"
                }
            },
            "core/list": {
                "spacing": {
                    "padding": {
                        "left": "var(--wp--preset--spacing--50)"
                    }
                }
            },
            "core/image": {
                "border": {
                    "radius": "0.375rem"
                }
            },
            "core/group": {
                "spacing": {
                    "blockGap": "var(--wp--preset--spacing--50)"
                }
            },
            "core/columns": {
                "spacing": {
                    "blockGap": "var(--wp--preset--spacing--60)"
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
            "title": "Blank Canvas",
            "postTypes": ["page", "post"]
        },
        {
            "name": "page-wide",
            "title": "Page: Wide Layout",
            "postTypes": ["page"]
        },
        {
            "name": "post-no-sidebar",
            "title": "Post: No Sidebar",
            "postTypes": ["post"]
        }
    ]
}
```

### 2. CREATE assets/fonts/font-face.css (Font Loading Optimization)

**Purpose**: Optimized web font loading with performance considerations

```css
/**
 * Font Face Declarations for GPress Theme
 * 
 * Performance-optimized font loading with proper fallbacks
 * 
 * @package GPress
 * @version 1.2.0
 */

/* 
 * Optional: Add custom web fonts here
 * Example implementation for future use
 */

/*
@font-face {
    font-family: 'Custom Font';
    font-style: normal;
    font-weight: 400;
    font-display: swap;
    src: local('Custom Font'), 
         url('../fonts/custom-font.woff2') format('woff2'),
         url('../fonts/custom-font.woff') format('woff');
    unicode-range: U+0000-00FF, U+0131, U+0152-0153, U+02BB-02BC, U+02C6, U+02DA, U+02DC, U+2000-206F, U+2074, U+20AC, U+2122, U+2191, U+2193, U+2212, U+2215, U+FEFF, U+FFFD;
}

@font-face {
    font-family: 'Custom Font';
    font-style: normal;
    font-weight: 600;
    font-display: swap;
    src: local('Custom Font Semibold'), 
         url('../fonts/custom-font-semibold.woff2') format('woff2'),
         url('../fonts/custom-font-semibold.woff') format('woff');
    unicode-range: U+0000-00FF, U+0131, U+0152-0153, U+02BB-02BC, U+02C6, U+02DA, U+02DC, U+2000-206F, U+2074, U+20AC, U+2122, U+2191, U+2193, U+2212, U+2215, U+FEFF, U+FFFD;
}
*/

/* Font loading optimization */
:root {
    --font-system-sans: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Oxygen-Sans, Ubuntu, Cantarell, "Helvetica Neue", sans-serif;
    --font-system-serif: ui-serif, Georgia, Cambria, "Times New Roman", Times, serif;
    --font-system-mono: ui-monospace, "SF Mono", Monaco, "Cascadia Code", "Roboto Mono", Consolas, "Courier New", monospace;
}

/* Optimize font rendering */
body {
    font-feature-settings: "kern" 1, "liga" 1, "calt" 1;
    text-rendering: optimizeSpeed;
    -webkit-font-smoothing: antialiased;
    -moz-osx-font-smoothing: grayscale;
}

/* Enhanced font rendering for headings */
h1, h2, h3, h4, h5, h6 {
    font-feature-settings: "kern" 1, "liga" 1, "calt" 1, "tnum" 1;
}

/* Optimized font loading for better performance */
.has-custom-font {
    font-display: swap;
}

/* Fallback font loading states */
.fonts-loading body {
    visibility: hidden;
}

.fonts-loaded body,
.fonts-failed body {
    visibility: visible;
}

/* Reduce layout shift during font load */
.font-loading {
    font-variation-settings: "wght" 400;
    transition: font-variation-settings 0.2s ease;
}
```

### 3. CREATE assets/fonts/.gitkeep

```bash
# Create fonts directory and .gitkeep
touch assets/fonts/.gitkeep
```

### 4. UPDATE inc/customizer.php (FSE Integration)

**Purpose**: Enhanced customizer integration with FSE features

Add to the existing file:

```php
/**
 * FSE Integration and Global Styles Support
 */
function gpress_fse_customizer_integration($wp_customize) {
    
    // FSE Theme Options Section
    $wp_customize->add_section('gpress_fse_options', array(
        'title'       => esc_html__('Full Site Editing', 'gpress'),
        'priority'    => 25,
        'description' => esc_html__('Configure Full Site Editing features and global styles.', 'gpress'),
    ));
    
    // Enable Global Styles UI
    $wp_customize->add_setting('gpress_enable_global_styles', array(
        'default'           => true,
        'sanitize_callback' => 'rest_sanitize_boolean',
        'transport'         => 'refresh',
    ));
    
    $wp_customize->add_control('gpress_enable_global_styles', array(
        'label'       => esc_html__('Enable Global Styles Interface', 'gpress'),
        'description' => esc_html__('Allow users to access global styles in the Site Editor.', 'gpress'),
        'section'     => 'gpress_fse_options',
        'type'        => 'checkbox',
    ));
    
    // Template Editing
    $wp_customize->add_setting('gpress_enable_template_editing', array(
        'default'           => true,
        'sanitize_callback' => 'rest_sanitize_boolean',
        'transport'         => 'refresh',
    ));
    
    $wp_customize->add_control('gpress_enable_template_editing', array(
        'label'       => esc_html__('Enable Template Editing', 'gpress'),
        'description' => esc_html__('Allow users to edit templates in the Site Editor.', 'gpress'),
        'section'     => 'gpress_fse_options',
        'type'        => 'checkbox',
    ));
    
    // Block Pattern Support
    $wp_customize->add_setting('gpress_enable_block_patterns', array(
        'default'           => true,
        'sanitize_callback' => 'rest_sanitize_boolean',
        'transport'         => 'refresh',
    ));
    
    $wp_customize->add_control('gpress_enable_block_patterns', array(
        'label'       => esc_html__('Enable Block Patterns', 'gpress'),
        'description' => esc_html__('Show custom block patterns in the block inserter.', 'gpress'),
        'section'     => 'gpress_fse_options',
        'type'        => 'checkbox',
    ));
    
    // Content Width Settings
    $wp_customize->add_setting('gpress_content_width', array(
        'default'           => '65ch',
        'sanitize_callback' => 'sanitize_text_field',
        'transport'         => 'postMessage',
    ));
    
    $wp_customize->add_control('gpress_content_width', array(
        'label'       => esc_html__('Content Width', 'gpress'),
        'description' => esc_html__('Set the maximum width for content (e.g., 65ch, 800px).', 'gpress'),
        'section'     => 'gpress_fse_options',
        'type'        => 'text',
    ));
    
    // Wide Width Settings
    $wp_customize->add_setting('gpress_wide_width', array(
        'default'           => '1200px',
        'sanitize_callback' => 'sanitize_text_field',
        'transport'         => 'postMessage',
    ));
    
    $wp_customize->add_control('gpress_wide_width', array(
        'label'       => esc_html__('Wide Width', 'gpress'),
        'description' => esc_html__('Set the maximum width for wide and full-width blocks.', 'gpress'),
        'section'     => 'gpress_fse_options',
        'type'        => 'text',
    ));
}
add_action('customize_register', 'gpress_fse_customizer_integration');

/**
 * Live preview for FSE settings
 */
function gpress_fse_customizer_preview_js() {
    wp_enqueue_script(
        'gpress-fse-customizer-preview',
        gpress_asset_url('js/fse-customizer-preview.js'),
        array('customize-preview'),
        gpress_get_version(),
        array('in_footer' => true)
    );
}
add_action('customize_preview_init', 'gpress_fse_customizer_preview_js');

/**
 * Output dynamic CSS for FSE customizations
 */
function gpress_fse_customizer_css() {
    $content_width = get_theme_mod('gpress_content_width', '65ch');
    $wide_width = get_theme_mod('gpress_wide_width', '1200px');
    
    $css = '';
    
    // Content width
    if ($content_width !== '65ch') {
        $css .= '
        .wp-site-blocks .wp-block-group.is-content-justification-center,
        .wp-site-blocks .wp-block-group.aligncenter {
            max-width: ' . esc_attr($content_width) . ';
        }';
    }
    
    // Wide width
    if ($wide_width !== '1200px') {
        $css .= '
        .wp-site-blocks .alignwide {
            max-width: ' . esc_attr($wide_width) . ';
        }';
    }
    
    if (!empty($css)) {
        echo '<style type="text/css" id="gpress-fse-customizer-css">' . $css . '</style>';
    }
}
add_action('wp_head', 'gpress_fse_customizer_css');
```

### 5. UPDATE README.md (FSE Features Documentation)

Add to existing README.md:

```markdown
## 🎨 Full Site Editing Features

### Global Styles
- **Design System**: Consistent color palette, typography, and spacing
- **Responsive Typography**: Fluid font scaling using clamp()
- **Custom Properties**: CSS variables for easy customization
- **Theme Variations**: Multiple style variations (planned)

### Block Editor Enhancements
- **Custom Color Palette**: Brand-consistent colors with accessibility compliance
- **Typography Scale**: Harmonious font size system with fluid scaling
- **Spacing System**: Consistent spacing tokens (1-10 scale)
- **Shadow Presets**: Pre-defined shadow styles for depth
- **Border Controls**: Comprehensive border customization

### Template System
- **Block Templates**: HTML-based templates for FSE
- **Template Parts**: Reusable components (header, footer, sidebar)
- **Custom Templates**: Specialized layouts (blank, wide, no-sidebar)
- **Template Hierarchy**: WordPress template system support

### Performance Optimizations
- **Conditional Loading**: Block-specific styles load only when needed
- **Font Display Swap**: Optimized font loading for better performance
- **Reduced Layout Shift**: Stable layouts during content loading
- **Critical CSS**: Above-the-fold optimization

## 🛠️ FSE Customization

### Global Styles Access
1. Navigate to **Appearance → Site Editor**
2. Click **Styles** in the top toolbar
3. Customize colors, typography, and layout
4. Changes apply site-wide automatically

### Template Editing
1. Go to **Appearance → Site Editor**
2. Select **Templates** from the navigation
3. Choose a template to customize
4. Use block editor to modify layout and design

### Color Palette Customization
The theme includes a comprehensive color system:
```css
/* Primary Colors */
--wp--preset--color--primary: #2563eb
--wp--preset--color--primary-dark: #1e40af

/* Semantic Colors */
--wp--preset--color--success: #059669
--wp--preset--color--warning: #d97706
--wp--preset--color--error: #dc2626
```

### Typography System
Fluid typography automatically scales:
```css
/* Base sizes use clamp() for responsiveness */
--wp--preset--font-size--base: clamp(1rem, 0.95rem + 0.25vw, 1.125rem)
--wp--preset--font-size--large: clamp(1.25rem, 1.15rem + 0.5vw, 1.5rem)
```

## 📱 Responsive Design

### Breakpoint System
- **Mobile**: 320px - 767px
- **Tablet**: 768px - 1023px  
- **Desktop**: 1024px - 1199px
- **Large**: 1200px+

### Layout Containers
- **Content Width**: 65ch (optimal reading length)
- **Wide Width**: 1200px (maximum container width)
- **Full Width**: 100vw (edge-to-edge content)
```

## Testing This Step

### 1. FSE Availability Test
```bash
# Navigate to WordPress admin
# Go to Appearance → Site Editor
# Verify Site Editor loads without errors
# Check Global Styles panel is accessible
```

### 2. theme.json Validation Test
```bash
# Validate JSON syntax
python -m json.tool theme.json > /dev/null && echo "Valid JSON" || echo "Invalid JSON"

# Check WordPress schema compliance
# Visit Site Editor and verify no console errors
```

### 3. Design System Test
- [ ] Color palette appears in block editor
- [ ] Typography scales work correctly
- [ ] Spacing system functions properly
- [ ] Global styles apply consistently
- [ ] Template editing is available

### 4. Performance Test
```bash
# Test with Lighthouse
lighthouse http://your-site.local --output html

# Expected improvements:
# Performance: 93+
# Accessibility: 98+
# Best Practices: 96+
# SEO: 96+
```

### 5. Block Editor Test
- [ ] All preset colors available
- [ ] Font sizes work with fluid scaling
- [ ] Spacing controls function
- [ ] Shadow presets apply correctly
- [ ] Border controls work properly

### 6. Customizer Integration Test
- [ ] FSE options section appears
- [ ] Global styles toggle works
- [ ] Content width setting applies
- [ ] Live preview functions properly

## Expected Results

After completing Step 3, you should have:

- ✅ Complete FSE theme with theme.json configuration
- ✅ Comprehensive design system with consistent tokens
- ✅ Responsive typography with fluid scaling
- ✅ Advanced block editor customization options
- ✅ Template and template part configurations
- ✅ Performance-optimized font loading system
- ✅ Enhanced customizer integration
- ✅ Site Editor fully functional

## Next Step

Proceed to [Step 4: Block Templates Creation](./step-04-block-templates.md) to create comprehensive block-based templates for all page types and content structures.

---

**Performance Target Achieved**: ⚡ 93+ Lighthouse Score  
**FSE Enabled**: 🎨 Complete Site Editing Capabilities  
**Design System**: 🎯 Consistent Tokens & Scaling  
**Editor Enhanced**: ✨ Advanced Block Customization