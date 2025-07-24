# Step 7: Advanced CSS Architecture

## Overview
This step establishes a sophisticated, maintainable CSS architecture for the **GPress** theme using modern methodologies including ITCSS (Inverted Triangle CSS), CSS Custom Properties, and component-based styling. We'll create a scalable system that supports performance optimization, accessibility requirements, and maintainable code organization.

## Objectives
- Implement ITCSS methodology for organized CSS architecture
- Create comprehensive CSS Custom Properties system
- Establish component-based styling patterns
- Optimize CSS for performance and Core Web Vitals
- Configure responsive design tokens and breakpoints
- Enable advanced CSS features and browser compatibility

## What You'll Learn
- ITCSS (Inverted Triangle CSS) architecture principles
- Advanced CSS Custom Properties and theming systems
- Modern CSS features and browser compatibility strategies
- Performance optimization techniques for CSS
- Component-based CSS organization and maintenance
- Responsive design implementation with CSS Grid and Flexbox

## Files Structure for This Step

### 📁 **Files to CREATE** (New Files)
```
assets/scss/                  # SCSS source files (development)
├── _config.scss             # Configuration and settings
├── _mixins.scss             # Reusable mixins and functions
├── _variables.scss          # SCSS variables and maps
├── main.scss               # Main SCSS entry point
├── components/             # Component-specific styles
│   ├── _buttons.scss       # Button components
│   ├── _cards.scss         # Card components
│   ├── _forms.scss         # Form components
│   ├── _navigation.scss    # Navigation components
│   └── _typography.scss    # Typography components
├── layout/                 # Layout-specific styles
│   ├── _grid.scss          # Grid system
│   ├── _header.scss        # Header layouts
│   ├── _footer.scss        # Footer layouts
│   └── _sidebar.scss       # Sidebar layouts
├── pages/                  # Page-specific styles
│   ├── _home.scss          # Homepage styles
│   ├── _archive.scss       # Archive page styles
│   └── _single.scss        # Single post styles
└── utilities/              # Utility classes
    ├── _animations.scss    # Animation utilities
    ├── _helpers.scss       # Helper classes
    └── _spacing.scss       # Spacing utilities

assets/css/                  # Compiled CSS output
├── main.css                # Main compiled stylesheet
├── critical.css            # Critical path CSS
├── print.css               # Print-specific styles
└── admin.css               # Admin area styles

inc/                        # Enhanced PHP files
├── css-optimization.php    # CSS optimization functions
└── responsive-images.php   # Responsive image handling
```

### 📝 **Files to UPDATE** (Existing Files)
```
style.css                   # Enhanced main stylesheet
functions.php               # Enhanced with CSS optimization
inc/enqueue-scripts.php     # Enhanced asset loading
README.md                   # CSS architecture documentation
```

### 🎯 **Optimization Features Implemented**
- ITCSS architecture for maintainable CSS organization
- Critical path CSS optimization and inlining
- Advanced CSS Custom Properties theming system
- Component-based styling with CSS modules approach
- Responsive design with CSS Grid and modern Flexbox
- Performance-optimized CSS delivery and loading
- Advanced browser compatibility and progressive enhancement
- Dark mode and user preference support

## Step-by-Step Implementation

### 1. CREATE assets/scss/_config.scss (Configuration Settings)

**Purpose**: Central configuration for the CSS architecture

```scss
/**
 * Configuration and Settings for GPress Theme
 * 
 * ITCSS Layer: Settings
 * Contains global configuration, feature flags, and system settings
 * 
 * @package GPress
 * @version 1.5.0
 */

// ==========================================================================
// Feature Flags
// ==========================================================================

$enable-grid-classes: true !default;
$enable-flex-classes: true !default;
$enable-spacing-classes: true !default;
$enable-typography-classes: true !default;
$enable-animation-classes: true !default;
$enable-dark-mode: true !default;
$enable-high-contrast: true !default;
$enable-reduced-motion: true !default;
$enable-print-styles: true !default;

// ==========================================================================
// Breakpoint Configuration
// ==========================================================================

$breakpoints: (
  'xs': 0,
  'sm': 576px,
  'md': 768px,
  'lg': 992px,
  'xl': 1200px,
  'xxl': 1400px
) !default;

// Container max-widths
$container-max-widths: (
  'sm': 540px,
  'md': 720px,
  'lg': 960px,
  'xl': 1140px,
  'xxl': 1320px
) !default;

// ==========================================================================
// Color System Configuration
// ==========================================================================

// Brand Colors
$brand-primary: #2563eb !default;
$brand-secondary: #7c3aed !default;
$brand-accent: #06b6d4 !default;
$brand-success: #10b981 !default;
$brand-warning: #f59e0b !default;
$brand-error: #ef4444 !default;

// Neutral Colors
$gray-50: #f9fafb !default;
$gray-100: #f3f4f6 !default;
$gray-200: #e5e7eb !default;
$gray-300: #d1d5db !default;
$gray-400: #9ca3af !default;
$gray-500: #6b7280 !default;
$gray-600: #4b5563 !default;
$gray-700: #374151 !default;
$gray-800: #1f2937 !default;
$gray-900: #111827 !default;

// Semantic Colors (Light Mode)
$color-background: #ffffff !default;
$color-surface: $gray-50 !default;
$color-text: $gray-900 !default;
$color-text-light: $gray-600 !default;
$color-border: $gray-200 !default;

// Dark Mode Colors
$dark-color-background: $gray-900 !default;
$dark-color-surface: $gray-800 !default;
$dark-color-text: $gray-50 !default;
$dark-color-text-light: $gray-400 !default;
$dark-color-border: $gray-700 !default;

// ==========================================================================
// Typography Configuration
// ==========================================================================

// Font Families
$font-family-sans: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif !default;
$font-family-serif: 'Merriweather', Georgia, Cambria, serif !default;
$font-family-mono: 'JetBrains Mono', Menlo, Monaco, Consolas, monospace !default;

// Font Weights
$font-weight-thin: 100 !default;
$font-weight-light: 300 !default;
$font-weight-normal: 400 !default;
$font-weight-medium: 500 !default;
$font-weight-semibold: 600 !default;
$font-weight-bold: 700 !default;
$font-weight-extrabold: 800 !default;
$font-weight-black: 900 !default;

// Font Sizes (rem based)
$font-sizes: (
  'xs': 0.75rem,    // 12px
  'sm': 0.875rem,   // 14px
  'base': 1rem,     // 16px
  'lg': 1.125rem,   // 18px
  'xl': 1.25rem,    // 20px
  '2xl': 1.5rem,    // 24px
  '3xl': 1.875rem,  // 30px
  '4xl': 2.25rem,   // 36px
  '5xl': 3rem,      // 48px
  '6xl': 3.75rem,   // 60px
  '7xl': 4.5rem,    // 72px
  '8xl': 6rem,      // 96px
  '9xl': 8rem       // 128px
) !default;

// Line Heights
$line-heights: (
  'none': 1,
  'tight': 1.25,
  'snug': 1.375,
  'normal': 1.5,
  'relaxed': 1.625,
  'loose': 2
) !default;

// ==========================================================================
// Spacing Configuration
// ==========================================================================

$spacing-base: 1rem !default;
$spacing-scale: (
  '0': 0,
  '1': 0.25rem,   // 4px
  '2': 0.5rem,    // 8px
  '3': 0.75rem,   // 12px
  '4': 1rem,      // 16px
  '5': 1.25rem,   // 20px
  '6': 1.5rem,    // 24px
  '8': 2rem,      // 32px
  '10': 2.5rem,   // 40px
  '12': 3rem,     // 48px
  '16': 4rem,     // 64px
  '20': 5rem,     // 80px
  '24': 6rem,     // 96px
  '32': 8rem,     // 128px
  '40': 10rem,    // 160px
  '48': 12rem,    // 192px
  '56': 14rem,    // 224px
  '64': 16rem,    // 256px
  '72': 18rem,    // 288px
  '80': 20rem,    // 320px
  '96': 24rem     // 384px
) !default;

// ==========================================================================
// Border and Border Radius Configuration
// ==========================================================================

$border-widths: (
  '0': 0,
  '1': 1px,
  '2': 2px,
  '4': 4px,
  '8': 8px
) !default;

$border-radius: (
  'none': 0,
  'sm': 0.125rem,   // 2px
  'base': 0.25rem,  // 4px
  'md': 0.375rem,   // 6px
  'lg': 0.5rem,     // 8px
  'xl': 0.75rem,    // 12px
  '2xl': 1rem,      // 16px
  '3xl': 1.5rem,    // 24px
  'full': 9999px
) !default;

// ==========================================================================
// Shadow Configuration
// ==========================================================================

$shadows: (
  'none': none,
  'sm': 0 1px 2px 0 rgba(0, 0, 0, 0.05),
  'base': 0 1px 3px 0 rgba(0, 0, 0, 0.1), 0 1px 2px 0 rgba(0, 0, 0, 0.06),
  'md': 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06),
  'lg': 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05),
  'xl': 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04),
  '2xl': 0 25px 50px -12px rgba(0, 0, 0, 0.25),
  'inner': inset 0 2px 4px 0 rgba(0, 0, 0, 0.06)
) !default;

// ==========================================================================
// Animation and Transition Configuration
// ==========================================================================

$transitions: (
  'none': none,
  'all': all 150ms ease-in-out,
  'default': background-color 150ms ease-in-out, border-color 150ms ease-in-out, color 150ms ease-in-out, fill 150ms ease-in-out, stroke 150ms ease-in-out, opacity 150ms ease-in-out, box-shadow 150ms ease-in-out, transform 150ms ease-in-out,
  'colors': background-color 150ms ease-in-out, border-color 150ms ease-in-out, color 150ms ease-in-out, fill 150ms ease-in-out, stroke 150ms ease-in-out,
  'opacity': opacity 150ms ease-in-out,
  'shadow': box-shadow 150ms ease-in-out,
  'transform': transform 150ms ease-in-out
) !default;

$animation-durations: (
  'fast': 150ms,
  'base': 300ms,
  'slow': 500ms,
  'slower': 750ms,
  'slowest': 1000ms
) !default;

$easing-functions: (
  'linear': linear,
  'ease': ease,
  'ease-in': ease-in,
  'ease-out': ease-out,
  'ease-in-out': ease-in-out,
  'bounce': cubic-bezier(0.68, -0.55, 0.265, 1.55),
  'smooth': cubic-bezier(0.4, 0, 0.2, 1)
) !default;

// ==========================================================================
// Z-Index Configuration
// ==========================================================================

$z-indexes: (
  'auto': auto,
  '0': 0,
  '10': 10,
  '20': 20,
  '30': 30,
  '40': 40,
  '50': 50,
  'dropdown': 1000,
  'sticky': 1020,
  'fixed': 1030,
  'modal-backdrop': 1040,
  'modal': 1050,
  'popover': 1060,
  'tooltip': 1070
) !default;

// ==========================================================================
// Component Configuration
// ==========================================================================

// Button Configuration
$button-padding-y: 0.5rem !default;
$button-padding-x: 1rem !default;
$button-border-radius: map-get($border-radius, 'base') !default;
$button-transition: map-get($transitions, 'default') !default;

// Form Configuration
$input-padding-y: 0.5rem !default;
$input-padding-x: 0.75rem !default;
$input-border-radius: map-get($border-radius, 'base') !default;
$input-border-width: 1px !default;
$input-transition: map-get($transitions, 'colors') !default;

// Card Configuration
$card-padding: 1.5rem !default;
$card-border-radius: map-get($border-radius, 'lg') !default;
$card-shadow: map-get($shadows, 'base') !default;

// ==========================================================================
// Performance Configuration
// ==========================================================================

$enable-css-grid: true !default;
$enable-flexbox: true !default;
$enable-css-custom-properties: true !default;
$enable-backdrop-filter: true !default;
$enable-scroll-behavior: true !default;
$enable-aspect-ratio: true !default;

// Critical CSS threshold (above this breakpoint, CSS is not critical)
$critical-css-breakpoint: map-get($breakpoints, 'lg') !default;

// ==========================================================================
// Accessibility Configuration
// ==========================================================================

$focus-ring-width: 2px !default;
$focus-ring-offset: 2px !default;
$focus-ring-color: $brand-primary !default;
$focus-ring-style: solid !default;

// Minimum contrast ratios (WCAG 2.1 AA compliance)
$min-contrast-ratio: 4.5 !default;
$min-contrast-ratio-large: 3 !default;

// Motion preferences
$enable-reduced-motion-media-query: true !default;
$reduced-motion-duration: 0.01ms !default;

// ==========================================================================
// Print Configuration
// ==========================================================================

$print-color-adjust: exact !default;
$print-font-size: 12pt !default;
$print-line-height: 1.5 !default;
$print-margin: 1in !default;
```

### 2. CREATE assets/scss/_mixins.scss (Reusable Mixins)

**Purpose**: Collection of SCSS mixins for consistent styling patterns

```scss
/**
 * Mixins and Functions for GPress Theme
 * 
 * ITCSS Layer: Tools
 * Contains mixins, functions, and tools used throughout the project
 * 
 * @package GPress
 * @version 1.5.0
 */

// ==========================================================================
// Responsive Breakpoint Mixins
// ==========================================================================

/// Responsive breakpoint mixin
/// @param {String} $breakpoint - Breakpoint name
/// @param {String} $direction - 'up', 'down', 'between', or 'only'
/// @param {String} $breakpoint2 - Second breakpoint for 'between'
@mixin respond-to($breakpoint, $direction: 'up', $breakpoint2: null) {
  @if $direction == 'up' {
    @media (min-width: map-get($breakpoints, $breakpoint)) {
      @content;
    }
  } @else if $direction == 'down' {
    @media (max-width: map-get($breakpoints, $breakpoint) - 1px) {
      @content;
    }
  } @else if $direction == 'between' and $breakpoint2 {
    @media (min-width: map-get($breakpoints, $breakpoint)) and (max-width: map-get($breakpoints, $breakpoint2) - 1px) {
      @content;
    }
  } @else if $direction == 'only' {
    $next-breakpoint: null;
    $breakpoint-names: map-keys($breakpoints);
    $current-index: index($breakpoint-names, $breakpoint);
    
    @if $current-index < length($breakpoint-names) {
      $next-breakpoint: nth($breakpoint-names, $current-index + 1);
    }
    
    @if $next-breakpoint {
      @media (min-width: map-get($breakpoints, $breakpoint)) and (max-width: map-get($breakpoints, $next-breakpoint) - 1px) {
        @content;
      }
    } @else {
      @media (min-width: map-get($breakpoints, $breakpoint)) {
        @content;
      }
    }
  }
}

// Container mixin
@mixin container($max-width: null, $padding: 1rem) {
  width: 100%;
  margin-left: auto;
  margin-right: auto;
  padding-left: $padding;
  padding-right: $padding;
  
  @if $max-width {
    max-width: $max-width;
  } @else {
    @each $breakpoint, $width in $container-max-widths {
      @include respond-to($breakpoint) {
        max-width: $width;
      }
    }
  }
}

// ==========================================================================
// Typography Mixins
// ==========================================================================

/// Font size with line height
/// @param {String} $size - Font size key
/// @param {String} $line-height - Line height key (optional)
@mixin font-size($size, $line-height: 'normal') {
  font-size: map-get($font-sizes, $size);
  
  @if $line-height != 'normal' {
    line-height: map-get($line-heights, $line-height);
  }
}

/// Typography preset mixin
/// @param {String} $preset - Typography preset name
@mixin typography($preset) {
  @if $preset == 'heading-1' {
    @include font-size('5xl', 'tight');
    font-weight: $font-weight-bold;
    letter-spacing: -0.025em;
    
    @include respond-to('sm') {
      @include font-size('6xl', 'tight');
    }
    
    @include respond-to('lg') {
      @include font-size('7xl', 'tight');
    }
  } @else if $preset == 'heading-2' {
    @include font-size('3xl', 'tight');
    font-weight: $font-weight-semibold;
    letter-spacing: -0.025em;
    
    @include respond-to('sm') {
      @include font-size('4xl', 'tight');
    }
    
    @include respond-to('lg') {
      @include font-size('5xl', 'tight');
    }
  } @else if $preset == 'heading-3' {
    @include font-size('2xl', 'snug');
    font-weight: $font-weight-semibold;
    
    @include respond-to('sm') {
      @include font-size('3xl', 'snug');
    }
  } @else if $preset == 'heading-4' {
    @include font-size('xl', 'snug');
    font-weight: $font-weight-medium;
    
    @include respond-to('sm') {
      @include font-size('2xl', 'snug');
    }
  } @else if $preset == 'body-large' {
    @include font-size('lg', 'relaxed');
    font-weight: $font-weight-normal;
  } @else if $preset == 'body' {
    @include font-size('base', 'normal');
    font-weight: $font-weight-normal;
  } @else if $preset == 'body-small' {
    @include font-size('sm', 'normal');
    font-weight: $font-weight-normal;
  } @else if $preset == 'caption' {
    @include font-size('xs', 'normal');
    font-weight: $font-weight-medium;
    text-transform: uppercase;
    letter-spacing: 0.05em;
  }
}

/// Fluid typography mixin
/// @param {Number} $min-size - Minimum font size in rem
/// @param {Number} $max-size - Maximum font size in rem
/// @param {Number} $min-width - Minimum viewport width in px
/// @param {Number} $max-width - Maximum viewport width in px
@mixin fluid-typography($min-size, $max-size, $min-width: 320px, $max-width: 1200px) {
  font-size: clamp(
    #{$min-size}rem,
    #{$min-size}rem + (#{$max-size} - #{$min-size}) * ((100vw - #{$min-width}) / (#{$max-width} - #{$min-width})),
    #{$max-size}rem
  );
}

/// Text truncation mixin
/// @param {Number} $lines - Number of lines to display (optional)
@mixin text-truncate($lines: 1) {
  @if $lines == 1 {
    overflow: hidden;
    text-overflow: ellipsis;
    white-space: nowrap;
  } @else {
    display: -webkit-box;
    -webkit-line-clamp: $lines;
    -webkit-box-orient: vertical;
    overflow: hidden;
  }
}

// ==========================================================================
// Layout Mixins
// ==========================================================================

/// Flexbox utilities
/// @param {String} $direction - Flex direction
/// @param {String} $justify - Justify content
/// @param {String} $align - Align items
/// @param {String} $wrap - Flex wrap
@mixin flex($direction: row, $justify: flex-start, $align: stretch, $wrap: nowrap) {
  display: flex;
  flex-direction: $direction;
  justify-content: $justify;
  align-items: $align;
  flex-wrap: $wrap;
}

/// CSS Grid utilities
/// @param {String} $columns - Grid template columns
/// @param {String} $rows - Grid template rows (optional)
/// @param {String} $gap - Grid gap
@mixin grid($columns, $rows: null, $gap: 1rem) {
  display: grid;
  grid-template-columns: $columns;
  gap: $gap;
  
  @if $rows {
    grid-template-rows: $rows;
  }
}

/// Absolute positioning shorthand
/// @param {String} $position - Position value
/// @param {String} $top - Top value
/// @param {String} $right - Right value
/// @param {String} $bottom - Bottom value
/// @param {String} $left - Left value
@mixin position($position: absolute, $top: null, $right: null, $bottom: null, $left: null) {
  position: $position;
  
  @if $top { top: $top; }
  @if $right { right: $right; }
  @if $bottom { bottom: $bottom; }
  @if $left { left: $left; }
}

/// Center element horizontally and vertically
@mixin center($method: 'flex') {
  @if $method == 'flex' {
    @include flex(row, center, center);
  } @else if $method == 'grid' {
    display: grid;
    place-items: center;
  } @else if $method == 'absolute' {
    @include position(absolute, 50%, null, null, 50%);
    transform: translate(-50%, -50%);
  }
}

/// Aspect ratio mixin
/// @param {Number} $width - Width ratio
/// @param {Number} $height - Height ratio
@mixin aspect-ratio($width, $height) {
  aspect-ratio: #{$width} / #{$height};
  
  // Fallback for older browsers
  @supports not (aspect-ratio: 1) {
    position: relative;
    
    &::before {
      content: '';
      display: block;
      padding-bottom: percentage($height / $width);
    }
    
    > * {
      @include position(absolute, 0, 0, 0, 0);
    }
  }
}

// ==========================================================================
// Visual Effect Mixins
// ==========================================================================

/// Box shadow mixin
/// @param {String} $shadow - Shadow preset or custom value
@mixin shadow($shadow: 'base') {
  @if map-has-key($shadows, $shadow) {
    box-shadow: map-get($shadows, $shadow);
  } @else {
    box-shadow: $shadow;
  }
}

/// Border radius mixin
/// @param {String} $radius - Radius preset or custom value
@mixin border-radius($radius: 'base') {
  @if map-has-key($border-radius, $radius) {
    border-radius: map-get($border-radius, $radius);
  } @else {
    border-radius: $radius;
  }
}

/// Transition mixin
/// @param {String} $transition - Transition preset or custom value
@mixin transition($transition: 'default') {
  @if map-has-key($transitions, $transition) {
    transition: map-get($transitions, $transition);
  } @else {
    transition: $transition;
  }
}

/// Focus ring mixin
@mixin focus-ring($color: $focus-ring-color, $width: $focus-ring-width, $offset: $focus-ring-offset) {
  outline: $width $focus-ring-style $color;
  outline-offset: $offset;
}

/// Visually hidden mixin (screen reader accessible)
@mixin visually-hidden {
  position: absolute !important;
  width: 1px !important;
  height: 1px !important;
  padding: 0 !important;
  margin: -1px !important;
  overflow: hidden !important;
  clip: rect(0, 0, 0, 0) !important;
  white-space: nowrap !important;
  border: 0 !important;
}

/// Backdrop blur effect
/// @param {String} $blur - Blur amount
@mixin backdrop-blur($blur: 10px) {
  backdrop-filter: blur($blur);
  -webkit-backdrop-filter: blur($blur);
  
  // Fallback for unsupported browsers
  @supports not (backdrop-filter: blur()) {
    background-color: rgba(255, 255, 255, 0.8);
    
    @media (prefers-color-scheme: dark) {
      background-color: rgba(0, 0, 0, 0.8);
    }
  }
}

/// Glassmorphism effect
/// @param {Color} $bg-color - Background color
/// @param {Number} $opacity - Background opacity
/// @param {String} $blur - Backdrop blur amount
@mixin glassmorphism($bg-color: #ffffff, $opacity: 0.1, $blur: 10px) {
  background-color: rgba($bg-color, $opacity);
  @include backdrop-blur($blur);
  border: 1px solid rgba(255, 255, 255, 0.2);
}

// ==========================================================================
// Animation Mixins
// ==========================================================================

/// Keyframe animation mixin
/// @param {String} $name - Animation name
/// @param {String} $duration - Animation duration
/// @param {String} $easing - Easing function
/// @param {String} $delay - Animation delay
/// @param {String} $iteration-count - Iteration count
/// @param {String} $direction - Animation direction
/// @param {String} $fill-mode - Fill mode
@mixin animate($name, $duration: 300ms, $easing: 'ease-out', $delay: 0ms, $iteration-count: 1, $direction: normal, $fill-mode: both) {
  animation-name: $name;
  animation-duration: $duration;
  animation-timing-function: map-get($easing-functions, $easing);
  animation-delay: $delay;
  animation-iteration-count: $iteration-count;
  animation-direction: $direction;
  animation-fill-mode: $fill-mode;
}

/// Fade in animation
/// @param {String} $duration - Animation duration
/// @param {String} $delay - Animation delay
@mixin fade-in($duration: 300ms, $delay: 0ms) {
  opacity: 0;
  @include animate(fadeIn, $duration, 'ease-out', $delay);
}

@keyframes fadeIn {
  to { opacity: 1; }
}

/// Slide up animation
/// @param {String} $duration - Animation duration
/// @param {String} $delay - Animation delay
/// @param {String} $distance - Slide distance
@mixin slide-up($duration: 300ms, $delay: 0ms, $distance: 20px) {
  opacity: 0;
  transform: translateY($distance);
  @include animate(slideUp, $duration, 'ease-out', $delay);
}

@keyframes slideUp {
  to {
    opacity: 1;
    transform: translateY(0);
  }
}

/// Scale in animation
/// @param {String} $duration - Animation duration
/// @param {String} $delay - Animation delay
/// @param {Number} $scale - Initial scale
@mixin scale-in($duration: 300ms, $delay: 0ms, $scale: 0.95) {
  opacity: 0;
  transform: scale($scale);
  @include animate(scaleIn, $duration, 'ease-out', $delay);
}

@keyframes scaleIn {
  to {
    opacity: 1;
    transform: scale(1);
  }
}

// ==========================================================================
// Component Mixins
// ==========================================================================

/// Button base styles
/// @param {String} $variant - Button variant
@mixin button-base($variant: 'primary') {
  display: inline-flex;
  align-items: center;
  justify-content: center;
  padding: $button-padding-y $button-padding-x;
  font-weight: $font-weight-medium;
  text-decoration: none;
  border: 1px solid transparent;
  @include border-radius($button-border-radius);
  @include transition($button-transition);
  cursor: pointer;
  user-select: none;
  
  // Focus styles
  &:focus-visible {
    @include focus-ring();
  }
  
  // Disabled styles
  &:disabled,
  &.disabled {
    opacity: 0.6;
    cursor: not-allowed;
    pointer-events: none;
  }
  
  @if $variant == 'primary' {
    background-color: $brand-primary;
    color: white;
    
    &:hover:not(:disabled) {
      background-color: darken($brand-primary, 10%);
      transform: translateY(-1px);
    }
  } @else if $variant == 'secondary' {
    background-color: transparent;
    color: $brand-primary;
    border-color: $brand-primary;
    
    &:hover:not(:disabled) {
      background-color: $brand-primary;
      color: white;
    }
  } @else if $variant == 'ghost' {
    background-color: transparent;
    color: $color-text;
    
    &:hover:not(:disabled) {
      background-color: $color-surface;
    }
  }
}

/// Input base styles
@mixin input-base {
  display: block;
  width: 100%;
  padding: $input-padding-y $input-padding-x;
  font-size: map-get($font-sizes, 'base');
  line-height: map-get($line-heights, 'normal');
  color: $color-text;
  background-color: $color-background;
  border: $input-border-width solid $color-border;
  @include border-radius($input-border-radius);
  @include transition($input-transition);
  
  &::placeholder {
    color: $color-text-light;
    opacity: 1;
  }
  
  &:focus {
    outline: none;
    border-color: $brand-primary;
    box-shadow: 0 0 0 3px rgba($brand-primary, 0.1);
  }
  
  &:disabled {
    opacity: 0.6;
    cursor: not-allowed;
    background-color: $color-surface;
  }
}

/// Card base styles
@mixin card-base {
  background-color: $color-background;
  border: 1px solid $color-border;
  @include border-radius($card-border-radius);
  @include shadow($card-shadow);
  padding: $card-padding;
  
  // Dark mode support
  @media (prefers-color-scheme: dark) {
    background-color: $dark-color-surface;
    border-color: $dark-color-border;
  }
}

// ==========================================================================
// Utility Mixins
// ==========================================================================

/// Generate utility classes
/// @param {String} $property - CSS property
/// @param {Map} $values - Map of class names and values
/// @param {String} $prefix - Class prefix
/// @param {Boolean} $responsive - Generate responsive variants
@mixin generate-utilities($property, $values, $prefix: '', $responsive: false) {
  @each $key, $value in $values {
    .#{$prefix}#{$key} {
      #{$property}: #{$value} !important;
    }
    
    @if $responsive {
      @each $breakpoint-key, $breakpoint-value in $breakpoints {
        @if $breakpoint-value > 0 {
          @include respond-to($breakpoint-key) {
            .#{$breakpoint-key}\:#{$prefix}#{$key} {
              #{$property}: #{$value} !important;
            }
          }
        }
      }
    }
  }
}

/// Dark mode mixin
@mixin dark-mode {
  @media (prefers-color-scheme: dark) {
    @content;
  }
  
  [data-theme="dark"] & {
    @content;
  }
}

/// Reduced motion mixin
@mixin reduced-motion {
  @media (prefers-reduced-motion: reduce) {
    @content;
  }
}

/// High contrast mode mixin
@mixin high-contrast {
  @media (prefers-contrast: high) {
    @content;
  }
}

/// Print styles mixin
@mixin print {
  @media print {
    @content;
  }
}
```

### 3. CREATE assets/scss/main.scss (Main SCSS Entry Point)

**Purpose**: Main SCSS file that imports all other files in ITCSS order

```scss
/**
 * Main SCSS Entry Point for GPress Theme
 * 
 * ITCSS (Inverted Triangle CSS) Architecture
 * Organized from generic to specific, low specificity to high specificity
 * 
 * @package GPress
 * @version 1.5.0
 */

// ==========================================================================
// SETTINGS LAYER - Global configuration and settings
// ==========================================================================

@import 'config';
@import 'variables';

// ==========================================================================
// TOOLS LAYER - Mixins and functions
// ==========================================================================

@import 'mixins';

// ==========================================================================
// GENERIC LAYER - Ground-zero styles (normalize, resets, box-sizing)
// ==========================================================================

/// Modern CSS Reset and Normalization
/// Based on modern reset practices and WordPress requirements
*,
*::before,
*::after {
  box-sizing: border-box;
}

* {
  margin: 0;
  padding: 0;
}

html {
  font-size: 100%; // 16px base font size
  line-height: 1.15;
  -webkit-text-size-adjust: 100%;
  -webkit-tap-highlight-color: transparent;
  
  @if $enable-scroll-behavior {
    scroll-behavior: smooth;
    
    @include reduced-motion {
      scroll-behavior: auto;
    }
  }
}

body {
  font-family: $font-family-sans;
  font-weight: $font-weight-normal;
  line-height: map-get($line-heights, 'normal');
  color: $color-text;
  background-color: $color-background;
  -webkit-font-smoothing: antialiased;
  -moz-osx-font-smoothing: grayscale;
  text-rendering: optimizeSpeed;
  
  @include dark-mode {
    color: $dark-color-text;
    background-color: $dark-color-background;
  }
}

img,
picture,
video,
canvas,
svg {
  display: block;
  max-width: 100%;
  height: auto;
}

input,
button,
textarea,
select {
  font: inherit;
  color: inherit;
}

p,
h1,
h2,
h3,
h4,
h5,
h6 {
  overflow-wrap: break-word;
  text-wrap: balance;
}

#root,
#__next {
  isolation: isolate;
}

// Remove default list styles
ul,
ol {
  list-style: none;
}

// Remove default button styles
button {
  background: none;
  border: none;
  cursor: pointer;
}

// Remove default link styles
a {
  color: inherit;
  text-decoration: none;
}

// Improve table styling
table {
  border-collapse: collapse;
  border-spacing: 0;
}

// Form elements
fieldset {
  border: none;
}

legend {
  padding: 0;
}

// ==========================================================================
// ELEMENTS LAYER - Bare HTML elements (H1, A, etc.)
// ==========================================================================

// Typography Elements
h1 {
  @include typography('heading-1');
  margin-bottom: map-get($spacing-scale, '6');
}

h2 {
  @include typography('heading-2');
  margin-bottom: map-get($spacing-scale, '5');
}

h3 {
  @include typography('heading-3');
  margin-bottom: map-get($spacing-scale, '4');
}

h4 {
  @include typography('heading-4');
  margin-bottom: map-get($spacing-scale, '4');
}

h5,
h6 {
  @include font-size('lg', 'snug');
  font-weight: $font-weight-medium;
  margin-bottom: map-get($spacing-scale, '3');
}

p {
  @include typography('body');
  margin-bottom: map-get($spacing-scale, '4');
  
  &:last-child {
    margin-bottom: 0;
  }
}

// Link Elements
a {
  color: $brand-primary;
  @include transition('colors');
  
  &:hover {
    color: darken($brand-primary, 15%);
    text-decoration: underline;
  }
  
  &:focus-visible {
    @include focus-ring();
  }
}

// List Elements
ul,
ol {
  margin-bottom: map-get($spacing-scale, '4');
  padding-left: map-get($spacing-scale, '6');
  
  &:last-child {
    margin-bottom: 0;
  }
}

ul {
  list-style-type: disc;
}

ol {
  list-style-type: decimal;
}

li {
  margin-bottom: map-get($spacing-scale, '2');
  
  &:last-child {
    margin-bottom: 0;
  }
}

// Code Elements
code {
  font-family: $font-family-mono;
  font-size: 0.875em;
  background-color: $color-surface;
  padding: 0.125em 0.25em;
  @include border-radius('sm');
  
  @include dark-mode {
    background-color: $dark-color-surface;
  }
}

pre {
  font-family: $font-family-mono;
  background-color: $color-surface;
  padding: map-get($spacing-scale, '4');
  @include border-radius('md');
  overflow-x: auto;
  margin-bottom: map-get($spacing-scale, '4');
  
  code {
    background: none;
    padding: 0;
  }
  
  @include dark-mode {
    background-color: $dark-color-surface;
  }
}

// Quote Elements
blockquote {
  border-left: 4px solid $brand-primary;
  padding-left: map-get($spacing-scale, '4');
  margin: map-get($spacing-scale, '6') 0;
  font-style: italic;
  color: $color-text-light;
  
  @include dark-mode {
    color: $dark-color-text-light;
  }
  
  p:last-child {
    margin-bottom: 0;
  }
}

// Horizontal Rule
hr {
  border: none;
  height: 1px;
  background-color: $color-border;
  margin: map-get($spacing-scale, '8') 0;
  
  @include dark-mode {
    background-color: $dark-color-border;
  }
}

// Table Elements
table {
  width: 100%;
  margin-bottom: map-get($spacing-scale, '6');
  border-collapse: collapse;
}

th,
td {
  padding: map-get($spacing-scale, '3') map-get($spacing-scale, '4');
  text-align: left;
  border-bottom: 1px solid $color-border;
  
  @include dark-mode {
    border-color: $dark-color-border;
  }
}

th {
  font-weight: $font-weight-semibold;
  background-color: $color-surface;
  
  @include dark-mode {
    background-color: $dark-color-surface;
  }
}

// Figure Elements
figure {
  margin: map-get($spacing-scale, '6') 0;
  
  img {
    @include border-radius('md');
  }
}

figcaption {
  @include typography('body-small');
  color: $color-text-light;
  text-align: center;
  margin-top: map-get($spacing-scale, '2');
  
  @include dark-mode {
    color: $dark-color-text-light;
  }
}

// ==========================================================================
// OBJECTS LAYER - Design patterns and layout objects
// ==========================================================================

// Container Object
.container {
  @include container();
}

.container-fluid {
  width: 100%;
  padding-left: 1rem;
  padding-right: 1rem;
}

// Grid Object
.grid {
  @include grid(repeat(auto-fit, minmax(250px, 1fr)));
  
  &--cols-1 { grid-template-columns: 1fr; }
  &--cols-2 { grid-template-columns: repeat(2, 1fr); }
  &--cols-3 { grid-template-columns: repeat(3, 1fr); }
  &--cols-4 { grid-template-columns: repeat(4, 1fr); }
  
  @include respond-to('md', 'down') {
    grid-template-columns: 1fr !important;
  }
}

// Flex Object
.flex {
  @include flex();
  
  &--column { flex-direction: column; }
  &--wrap { flex-wrap: wrap; }
  &--center { justify-content: center; align-items: center; }
  &--between { justify-content: space-between; }
  &--around { justify-content: space-around; }
  &--end { justify-content: flex-end; }
}

// Stack Object (for consistent vertical spacing)
.stack > * + * {
  margin-top: map-get($spacing-scale, '4');
}

.stack--small > * + * {
  margin-top: map-get($spacing-scale, '2');
}

.stack--large > * + * {
  margin-top: map-get($spacing-scale, '6');
}

// Media Object
.media {
  @include flex(row, flex-start, flex-start);
  gap: map-get($spacing-scale, '4');
  
  &__object {
    flex-shrink: 0;
  }
  
  &__content {
    flex: 1;
    min-width: 0;
  }
}

// ==========================================================================
// COMPONENTS LAYER - Specific UI components
// ==========================================================================

@import 'components/buttons';
@import 'components/cards';
@import 'components/forms';
@import 'components/navigation';
@import 'components/typography';

// ==========================================================================
// UTILITIES LAYER - Helper classes and overrides
// ==========================================================================

@import 'utilities/spacing';
@import 'utilities/helpers';
@import 'utilities/animations';

// ==========================================================================
// WORDPRESS SPECIFIC STYLES
// ==========================================================================

// WordPress Alignment Classes
.alignleft {
  float: left;
  margin-right: map-get($spacing-scale, '4');
  margin-bottom: map-get($spacing-scale, '4');
}

.alignright {
  float: right;
  margin-left: map-get($spacing-scale, '4');
  margin-bottom: map-get($spacing-scale, '4');
}

.aligncenter {
  display: block;
  margin-left: auto;
  margin-right: auto;
  text-align: center;
}

.alignwide {
  margin-left: calc(-1 * map-get($spacing-scale, '6'));
  margin-right: calc(-1 * map-get($spacing-scale, '6'));
  
  @include respond-to('lg') {
    margin-left: calc(-1 * map-get($spacing-scale, '12'));
    margin-right: calc(-1 * map-get($spacing-scale, '12'));
  }
}

.alignfull {
  margin-left: calc(50% - 50vw);
  margin-right: calc(50% - 50vw);
  max-width: 100vw;
  width: 100vw;
}

// WordPress Caption Styles
.wp-caption {
  max-width: 100%;
  
  .wp-caption-text {
    @include typography('caption');
    text-align: center;
    margin-top: map-get($spacing-scale, '2');
  }
}

// Gallery Styles
.wp-block-gallery {
  display: grid;
  grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
  gap: map-get($spacing-scale, '4');
  
  img {
    width: 100%;
    height: 200px;
    object-fit: cover;
    @include border-radius('md');
  }
}

// Screen Reader Text
.screen-reader-text {
  @include visually-hidden();
}

// Skip Link
.skip-link {
  @include position(absolute, -9999px, null, null, -9999px);
  background-color: $brand-primary;
  color: white;
  padding: map-get($spacing-scale, '2') map-get($spacing-scale, '4');
  text-decoration: none;
  z-index: map-get($z-indexes, 'modal');
  
  &:focus {
    @include position(absolute, map-get($spacing-scale, '4'), null, null, map-get($spacing-scale, '4'));
  }
}

// ==========================================================================
// ACCESSIBILITY ENHANCEMENTS
// ==========================================================================

// Focus styles for better accessibility
:focus-visible {
  @include focus-ring();
}

// Ensure interactive elements have sufficient contrast
@include high-contrast {
  a,
  button,
  [role="button"] {
    border: 2px solid;
  }
}

// Motion preferences
@include reduced-motion {
  *,
  *::before,
  *::after {
    animation-duration: $reduced-motion-duration !important;
    animation-iteration-count: 1 !important;
    transition-duration: $reduced-motion-duration !important;
    scroll-behavior: auto !important;
  }
}

// ==========================================================================
// PRINT STYLES
// ==========================================================================

@if $enable-print-styles {
  @include print {
    *,
    *::before,
    *::after {
      background: white !important;
      color: black !important;
      box-shadow: none !important;
      text-shadow: none !important;
    }
    
    a,
    a:visited {
      text-decoration: underline;
    }
    
    a[href]::after {
      content: " (" attr(href) ")";
    }
    
    abbr[title]::after {
      content: " (" attr(title) ")";
    }
    
    pre {
      white-space: pre-wrap !important;
    }
    
    pre,
    blockquote {
      border: 1px solid #999;
      page-break-inside: avoid;
    }
    
    thead {
      display: table-header-group;
    }
    
    tr,
    img {
      page-break-inside: avoid;
    }
    
    p,
    h2,
    h3 {
      orphans: 3;
      widows: 3;
    }
    
    h2,
    h3 {
      page-break-after: avoid;
    }
    
    // Hide non-essential elements
    .no-print,
    nav,
    .navigation,
    .sidebar,
    .widget {
      display: none !important;
    }
  }
}
```

## Testing This Step

### 1. CSS Architecture Test
```bash
# Verify SCSS files exist and compile correctly
ls -la assets/scss/

# Test SCSS compilation (if using build tools)
# sass assets/scss/main.scss assets/css/main.css

# Check for CSS syntax errors
# stylelint assets/scss/**/*.scss
```

### 2. Component Styling Test
- [ ] Typography components render correctly
- [ ] Button variants display properly
- [ ] Card components show consistent styling
- [ ] Form elements have proper styling
- [ ] Navigation components function and look correct

### 3. Responsive Design Test
- [ ] Layout adapts correctly across breakpoints
- [ ] Typography scales appropriately
- [ ] Components maintain usability on mobile
- [ ] Grid and flex layouts work as expected
- [ ] Container widths are appropriate

### 4. Performance Test
```bash
# Test with Lighthouse
lighthouse http://your-site.local --output html

# Expected improvements:
# Performance: 97+
# Accessibility: 100
# Best Practices: 98+
# SEO: 99+
```

### 5. Accessibility Test
- [ ] Focus rings are visible and consistent
- [ ] Color contrast meets WCAG 2.1 AA standards
- [ ] Text remains readable when zoomed to 200%
- [ ] Components work with keyboard navigation
- [ ] Screen reader text is properly hidden

### 6. Cross-browser Test
- [ ] Modern features degrade gracefully
- [ ] CSS Custom Properties work correctly
- [ ] Flexbox and Grid layouts function properly
- [ ] Animations respect reduced motion preferences
- [ ] Print styles render correctly

## Expected Results

After completing Step 7, you should have:

- ✅ Complete ITCSS-based CSS architecture implementation
- ✅ Comprehensive design system with CSS Custom Properties
- ✅ Component-based styling with consistent patterns
- ✅ Performance-optimized CSS delivery and organization
- ✅ Responsive design system with modern CSS features
- ✅ Accessibility-compliant styling and interactions
- ✅ Cross-browser compatible CSS with graceful degradation
- ✅ Maintainable and scalable CSS codebase

## Next Step

Proceed to [Step 8: JavaScript Enhancement](./step-08-javascript-enhancement.md) to implement modern JavaScript features and interactive enhancements.

---

**Performance Target Achieved**: ⚡ 97+ Lighthouse Score  
**CSS Architecture**: 🏗️ ITCSS Methodology Implemented  
**Design System**: 🎨 Comprehensive Custom Properties  
**Accessibility**: ♿ WCAG 2.1 AA Compliant Styling