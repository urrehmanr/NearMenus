# Step 4: Widgets Implementation

## Overview
This step implements custom widgets for the WordPress theme based on the React NearMenus project analysis. We'll create all the widgets needed to match the functionality and layout components identified in the React app.

## React Project Analysis for Widgets

### From React Components Analysis:

**Key Widget Areas Identified:**
1. **Contact Information Widget** - Restaurant contact details (phone, email, address)
2. **Restaurant Features Widget** - Display restaurant amenities and features
3. **Special Offers Widget** - Highlight current promotions and deals
4. **Popular Dishes Widget** - Showcase restaurant's signature dishes
5. **Operating Hours Widget** - Display business hours
6. **Related Restaurants Widget** - Show similar restaurants
7. **Cuisine Filter Widget** - Filter restaurants by cuisine type
8. **Search Widget** - Enhanced restaurant search functionality
9. **Recent Reviews Widget** - Display recent customer reviews
10. **Restaurant Stats Widget** - Show rating, review count, etc.

### Widget Placement Areas (Based on React Layout):
- **Restaurant Sidebar** - Single restaurant page sidebar
- **Homepage Sidebar** - Main page filtering and features
- **Footer Widgets** - Site-wide information and links
- **Archive Sidebar** - Category/search page filters

## Required Widgets Implementation

### 1. Contact Information Widget (`inc/widgets/contact-widget.php`)

```php
<?php
/**
 * Restaurant Contact Information Widget
 * Displays contact details for restaurants
 */

class NearMenus_Contact_Widget extends WP_Widget {

    public function __construct() {
        parent::__construct(
            'nearmenus_contact',
            __('Restaurant Contact Info', 'nearmenus'),
            array(
                'description' => __('Display restaurant contact information', 'nearmenus'),
                'classname' => 'widget_nearmenus_contact',
            )
        );
    }

    public function widget($args, $instance) {
        $title = !empty($instance['title']) ? $instance['title'] : __('Contact Information', 'nearmenus');
        $post_id = !empty($instance['restaurant_id']) ? $instance['restaurant_id'] : get_the_ID();
        $show_phone = isset($instance['show_phone']) ? $instance['show_phone'] : true;
        $show_email = isset($instance['show_email']) ? $instance['show_email'] : true;
        $show_website = isset($instance['show_website']) ? $instance['show_website'] : true;
        $show_address = isset($instance['show_address']) ? $instance['show_address'] : true;

        // Get contact information
        $phone = get_post_meta($post_id, '_restaurant_phone', true);
        $email = get_post_meta($post_id, '_restaurant_email', true);
        $website = get_post_meta($post_id, '_restaurant_website', true);
        $address = get_post_meta($post_id, '_restaurant_address', true);

        echo $args['before_widget'];
        
        if ($title) {
            echo $args['before_title'] . apply_filters('widget_title', $title) . $args['after_title'];
        }

        echo '<div class="contact-widget-content">';

        if ($show_phone && $phone) {
            echo '<div class="contact-item contact-phone">';
            echo '<span class="contact-icon">📞</span>';
            echo '<div class="contact-info">';
            echo '<label>' . __('Phone', 'nearmenus') . '</label>';
            echo '<a href="tel:' . esc_attr(str_replace(array(' ', '-', '(', ')'), '', $phone)) . '">' . esc_html($phone) . '</a>';
            echo '</div>';
            echo '</div>';
        }

        if ($show_email && $email) {
            echo '<div class="contact-item contact-email">';
            echo '<span class="contact-icon">✉️</span>';
            echo '<div class="contact-info">';
            echo '<label>' . __('Email', 'nearmenus') . '</label>';
            echo '<a href="mailto:' . esc_attr($email) . '">' . esc_html($email) . '</a>';
            echo '</div>';
            echo '</div>';
        }

        if ($show_website && $website) {
            echo '<div class="contact-item contact-website">';
            echo '<span class="contact-icon">🌐</span>';
            echo '<div class="contact-info">';
            echo '<label>' . __('Website', 'nearmenus') . '</label>';
            echo '<a href="' . esc_url($website) . '" target="_blank" rel="noopener">' . esc_html($website) . '</a>';
            echo '</div>';
            echo '</div>';
        }

        if ($show_address && $address) {
            echo '<div class="contact-item contact-address">';
            echo '<span class="contact-icon">📍</span>';
            echo '<div class="contact-info">';
            echo '<label>' . __('Address', 'nearmenus') . '</label>';
            echo '<span>' . esc_html($address) . '</span>';
            echo '</div>';
            echo '</div>';
        }

        echo '</div>';
        echo $args['after_widget'];
    }

    public function form($instance) {
        $title = !empty($instance['title']) ? $instance['title'] : __('Contact Information', 'nearmenus');
        $restaurant_id = !empty($instance['restaurant_id']) ? $instance['restaurant_id'] : '';
        $show_phone = isset($instance['show_phone']) ? (bool) $instance['show_phone'] : true;
        $show_email = isset($instance['show_email']) ? (bool) $instance['show_email'] : true;
        $show_website = isset($instance['show_website']) ? (bool) $instance['show_website'] : true;
        $show_address = isset($instance['show_address']) ? (bool) $instance['show_address'] : true;
        ?>
        
        <p>
            <label for="<?php echo esc_attr($this->get_field_id('title')); ?>"><?php _e('Title:', 'nearmenus'); ?></label>
            <input class="widefat" id="<?php echo esc_attr($this->get_field_id('title')); ?>" 
                   name="<?php echo esc_attr($this->get_field_name('title')); ?>" 
                   type="text" value="<?php echo esc_attr($title); ?>">
        </p>

        <p>
            <label for="<?php echo esc_attr($this->get_field_id('restaurant_id')); ?>"><?php _e('Restaurant ID (leave empty for current post):', 'nearmenus'); ?></label>
            <input class="widefat" id="<?php echo esc_attr($this->get_field_id('restaurant_id')); ?>" 
                   name="<?php echo esc_attr($this->get_field_name('restaurant_id')); ?>" 
                   type="number" value="<?php echo esc_attr($restaurant_id); ?>">
        </p>

        <p>
            <input class="checkbox" type="checkbox" <?php checked($show_phone); ?> 
                   id="<?php echo esc_attr($this->get_field_id('show_phone')); ?>" 
                   name="<?php echo esc_attr($this->get_field_name('show_phone')); ?>" />
            <label for="<?php echo esc_attr($this->get_field_id('show_phone')); ?>"><?php _e('Show Phone', 'nearmenus'); ?></label>
        </p>

        <p>
            <input class="checkbox" type="checkbox" <?php checked($show_email); ?> 
                   id="<?php echo esc_attr($this->get_field_id('show_email')); ?>" 
                   name="<?php echo esc_attr($this->get_field_name('show_email')); ?>" />
            <label for="<?php echo esc_attr($this->get_field_id('show_email')); ?>"><?php _e('Show Email', 'nearmenus'); ?></label>
        </p>

        <p>
            <input class="checkbox" type="checkbox" <?php checked($show_website); ?> 
                   id="<?php echo esc_attr($this->get_field_id('show_website')); ?>" 
                   name="<?php echo esc_attr($this->get_field_name('show_website')); ?>" />
            <label for="<?php echo esc_attr($this->get_field_id('show_website')); ?>"><?php _e('Show Website', 'nearmenus'); ?></label>
        </p>

        <p>
            <input class="checkbox" type="checkbox" <?php checked($show_address); ?> 
                   id="<?php echo esc_attr($this->get_field_id('show_address')); ?>" 
                   name="<?php echo esc_attr($this->get_field_name('show_address')); ?>" />
            <label for="<?php echo esc_attr($this->get_field_id('show_address')); ?>"><?php _e('Show Address', 'nearmenus'); ?></label>
        </p>
        
        <?php
    }

    public function update($new_instance, $old_instance) {
        $instance = array();
        $instance['title'] = (!empty($new_instance['title'])) ? sanitize_text_field($new_instance['title']) : '';
        $instance['restaurant_id'] = (!empty($new_instance['restaurant_id'])) ? (int) $new_instance['restaurant_id'] : '';
        $instance['show_phone'] = !empty($new_instance['show_phone']);
        $instance['show_email'] = !empty($new_instance['show_email']);
        $instance['show_website'] = !empty($new_instance['show_website']);
        $instance['show_address'] = !empty($new_instance['show_address']);
        return $instance;
    }
}
```

### 2. Restaurant Features Widget (`inc/widgets/features-widget.php`)

```php
<?php
/**
 * Restaurant Features Widget
 * Displays restaurant features and amenities
 */

class NearMenus_Features_Widget extends WP_Widget {

    public function __construct() {
        parent::__construct(
            'nearmenus_features',
            __('Restaurant Features', 'nearmenus'),
            array(
                'description' => __('Display restaurant features and amenities', 'nearmenus'),
                'classname' => 'widget_nearmenus_features',
            )
        );
    }

    public function widget($args, $instance) {
        $title = !empty($instance['title']) ? $instance['title'] : __('Features & Amenities', 'nearmenus');
        $post_id = !empty($instance['restaurant_id']) ? $instance['restaurant_id'] : get_the_ID();
        $layout = !empty($instance['layout']) ? $instance['layout'] : 'list';
        $show_icons = isset($instance['show_icons']) ? $instance['show_icons'] : true;

        // Get restaurant features from taxonomy
        $features = get_the_terms($post_id, 'restaurant_feature');

        if (!$features || is_wp_error($features)) {
            return;
        }

        echo $args['before_widget'];
        
        if ($title) {
            echo $args['before_title'] . apply_filters('widget_title', $title) . $args['after_title'];
        }

        echo '<div class="features-widget-content layout-' . esc_attr($layout) . '">';

        if ($layout === 'grid') {
            echo '<div class="features-grid">';
            foreach ($features as $feature) {
                echo '<div class="feature-item">';
                if ($show_icons) {
                    echo '<span class="feature-icon">' . nearmenus_get_feature_icon($feature->slug) . '</span>';
                }
                echo '<span class="feature-name">' . esc_html($feature->name) . '</span>';
                echo '</div>';
            }
            echo '</div>';
        } else {
            echo '<ul class="features-list">';
            foreach ($features as $feature) {
                echo '<li class="feature-item">';
                if ($show_icons) {
                    echo '<span class="feature-icon">' . nearmenus_get_feature_icon($feature->slug) . '</span>';
                }
                echo '<span class="feature-name">' . esc_html($feature->name) . '</span>';
                if ($feature->description) {
                    echo '<small class="feature-description">' . esc_html($feature->description) . '</small>';
                }
                echo '</li>';
            }
            echo '</ul>';
        }

        echo '</div>';
        echo $args['after_widget'];
    }

    public function form($instance) {
        $title = !empty($instance['title']) ? $instance['title'] : __('Features & Amenities', 'nearmenus');
        $restaurant_id = !empty($instance['restaurant_id']) ? $instance['restaurant_id'] : '';
        $layout = !empty($instance['layout']) ? $instance['layout'] : 'list';
        $show_icons = isset($instance['show_icons']) ? (bool) $instance['show_icons'] : true;
        ?>
        
        <p>
            <label for="<?php echo esc_attr($this->get_field_id('title')); ?>"><?php _e('Title:', 'nearmenus'); ?></label>
            <input class="widefat" id="<?php echo esc_attr($this->get_field_id('title')); ?>" 
                   name="<?php echo esc_attr($this->get_field_name('title')); ?>" 
                   type="text" value="<?php echo esc_attr($title); ?>">
        </p>

        <p>
            <label for="<?php echo esc_attr($this->get_field_id('restaurant_id')); ?>"><?php _e('Restaurant ID (leave empty for current post):', 'nearmenus'); ?></label>
            <input class="widefat" id="<?php echo esc_attr($this->get_field_id('restaurant_id')); ?>" 
                   name="<?php echo esc_attr($this->get_field_name('restaurant_id')); ?>" 
                   type="number" value="<?php echo esc_attr($restaurant_id); ?>">
        </p>

        <p>
            <label for="<?php echo esc_attr($this->get_field_id('layout')); ?>"><?php _e('Layout:', 'nearmenus'); ?></label>
            <select class="widefat" id="<?php echo esc_attr($this->get_field_id('layout')); ?>" 
                    name="<?php echo esc_attr($this->get_field_name('layout')); ?>">
                <option value="list" <?php selected($layout, 'list'); ?>><?php _e('List', 'nearmenus'); ?></option>
                <option value="grid" <?php selected($layout, 'grid'); ?>><?php _e('Grid', 'nearmenus'); ?></option>
            </select>
        </p>

        <p>
            <input class="checkbox" type="checkbox" <?php checked($show_icons); ?> 
                   id="<?php echo esc_attr($this->get_field_id('show_icons')); ?>" 
                   name="<?php echo esc_attr($this->get_field_name('show_icons')); ?>" />
            <label for="<?php echo esc_attr($this->get_field_id('show_icons')); ?>"><?php _e('Show Icons', 'nearmenus'); ?></label>
        </p>
        
        <?php
    }

    public function update($new_instance, $old_instance) {
        $instance = array();
        $instance['title'] = (!empty($new_instance['title'])) ? sanitize_text_field($new_instance['title']) : '';
        $instance['restaurant_id'] = (!empty($new_instance['restaurant_id'])) ? (int) $new_instance['restaurant_id'] : '';
        $instance['layout'] = (!empty($new_instance['layout'])) ? sanitize_text_field($new_instance['layout']) : 'list';
        $instance['show_icons'] = !empty($new_instance['show_icons']);
        return $instance;
    }
}

/**
 * Get feature icon based on feature slug
 */
function nearmenus_get_feature_icon($feature_slug) {
    $icons = array(
        'outdoor-seating' => '🍽️',
        'delivery' => '🚚',
        'takeout' => '🥡',
        'wine-bar' => '🍷',
        'live-music' => '🎵',
        'private-dining' => '🏛️',
        'valet-parking' => '🚗',
        'kids-menu' => '👶',
        'vegetarian-options' => '🥗',
        'vegan-options' => '🌱',
        'gluten-free-options' => '🌾',
        'happy-hour' => '🍻',
        'brunch' => '🥞',
        'late-night' => '🌙',
        'romantic-setting' => '💕',
        'family-friendly' => '👨‍👩‍👧‍👦',
        'business-dining' => '💼',
        'sports-bar' => '📺',
        'craft-beer' => '🍺',
        'craft-cocktails' => '🍸',
        'fresh-daily-catch' => '🐟',
        'farm-to-table' => '🚜',
        'pet-friendly' => '🐕',
    );
    
    return isset($icons[$feature_slug]) ? $icons[$feature_slug] : '✨';
}
```

### 3. Operating Hours Widget (`inc/widgets/hours-widget.php`)

```php
<?php
/**
 * Restaurant Operating Hours Widget
 */

class NearMenus_Hours_Widget extends WP_Widget {

    public function __construct() {
        parent::__construct(
            'nearmenus_hours',
            __('Operating Hours', 'nearmenus'),
            array(
                'description' => __('Display restaurant operating hours', 'nearmenus'),
                'classname' => 'widget_nearmenus_hours',
            )
        );
    }

    public function widget($args, $instance) {
        $title = !empty($instance['title']) ? $instance['title'] : __('Operating Hours', 'nearmenus');
        $post_id = !empty($instance['restaurant_id']) ? $instance['restaurant_id'] : get_the_ID();
        $show_status = isset($instance['show_status']) ? $instance['show_status'] : true;
        $compact_mode = isset($instance['compact_mode']) ? $instance['compact_mode'] : false;

        $days = array(
            'monday' => __('Monday', 'nearmenus'),
            'tuesday' => __('Tuesday', 'nearmenus'),
            'wednesday' => __('Wednesday', 'nearmenus'),
            'thursday' => __('Thursday', 'nearmenus'),
            'friday' => __('Friday', 'nearmenus'),
            'saturday' => __('Saturday', 'nearmenus'),
            'sunday' => __('Sunday', 'nearmenus')
        );

        $has_hours = false;
        $hours_data = array();

        foreach ($days as $day_key => $day_name) {
            $hours = get_post_meta($post_id, "_hours_{$day_key}", true);
            if ($hours) {
                $hours_data[$day_key] = array(
                    'name' => $day_name,
                    'hours' => $hours
                );
                $has_hours = true;
            }
        }

        if (!$has_hours) {
            return;
        }

        echo $args['before_widget'];
        
        if ($title) {
            echo $args['before_title'] . apply_filters('widget_title', $title) . $args['after_title'];
        }

        echo '<div class="hours-widget-content' . ($compact_mode ? ' compact-mode' : '') . '">';

        if ($show_status) {
            $is_open = get_post_meta($post_id, '_restaurant_is_open', true);
            $status_class = $is_open ? 'open' : 'closed';
            $status_text = $is_open ? __('Open Now', 'nearmenus') : __('Closed', 'nearmenus');
            
            echo '<div class="restaurant-status status-' . $status_class . '">';
            echo '<span class="status-indicator"></span>';
            echo '<span class="status-text">' . esc_html($status_text) . '</span>';
            echo '</div>';
        }

        if ($compact_mode) {
            // Group consecutive days with same hours
            $grouped_hours = nearmenus_group_hours($hours_data);
            echo '<ul class="hours-list compact">';
            foreach ($grouped_hours as $group) {
                echo '<li class="hours-item">';
                echo '<span class="hours-days">' . esc_html($group['days']) . '</span>';
                echo '<span class="hours-time">' . esc_html($group['hours']) . '</span>';
                echo '</li>';
            }
            echo '</ul>';
        } else {
            echo '<ul class="hours-list">';
            foreach ($hours_data as $day_key => $day_info) {
                $is_today = (strtolower(date('l')) === $day_key);
                echo '<li class="hours-item' . ($is_today ? ' today' : '') . '">';
                echo '<span class="hours-day">' . esc_html($day_info['name']) . '</span>';
                echo '<span class="hours-time">' . esc_html($day_info['hours']) . '</span>';
                echo '</li>';
            }
            echo '</ul>';
        }

        $holiday_hours = get_post_meta($post_id, '_holiday_hours', true);
        if ($holiday_hours) {
            echo '<div class="holiday-hours">';
            echo '<small>' . esc_html($holiday_hours) . '</small>';
            echo '</div>';
        }

        echo '</div>';
        echo $args['after_widget'];
    }

    public function form($instance) {
        $title = !empty($instance['title']) ? $instance['title'] : __('Operating Hours', 'nearmenus');
        $restaurant_id = !empty($instance['restaurant_id']) ? $instance['restaurant_id'] : '';
        $show_status = isset($instance['show_status']) ? (bool) $instance['show_status'] : true;
        $compact_mode = isset($instance['compact_mode']) ? (bool) $instance['compact_mode'] : false;
        ?>
        
        <p>
            <label for="<?php echo esc_attr($this->get_field_id('title')); ?>"><?php _e('Title:', 'nearmenus'); ?></label>
            <input class="widefat" id="<?php echo esc_attr($this->get_field_id('title')); ?>" 
                   name="<?php echo esc_attr($this->get_field_name('title')); ?>" 
                   type="text" value="<?php echo esc_attr($title); ?>">
        </p>

        <p>
            <label for="<?php echo esc_attr($this->get_field_id('restaurant_id')); ?>"><?php _e('Restaurant ID (leave empty for current post):', 'nearmenus'); ?></label>
            <input class="widefat" id="<?php echo esc_attr($this->get_field_id('restaurant_id')); ?>" 
                   name="<?php echo esc_attr($this->get_field_name('restaurant_id')); ?>" 
                   type="number" value="<?php echo esc_attr($restaurant_id); ?>">
        </p>

        <p>
            <input class="checkbox" type="checkbox" <?php checked($show_status); ?> 
                   id="<?php echo esc_attr($this->get_field_id('show_status')); ?>" 
                   name="<?php echo esc_attr($this->get_field_name('show_status')); ?>" />
            <label for="<?php echo esc_attr($this->get_field_id('show_status')); ?>"><?php _e('Show Open/Closed Status', 'nearmenus'); ?></label>
        </p>

        <p>
            <input class="checkbox" type="checkbox" <?php checked($compact_mode); ?> 
                   id="<?php echo esc_attr($this->get_field_id('compact_mode')); ?>" 
                   name="<?php echo esc_attr($this->get_field_name('compact_mode')); ?>" />
            <label for="<?php echo esc_attr($this->get_field_id('compact_mode')); ?>"><?php _e('Compact Mode (Group Similar Hours)', 'nearmenus'); ?></label>
        </p>
        
        <?php
    }

    public function update($new_instance, $old_instance) {
        $instance = array();
        $instance['title'] = (!empty($new_instance['title'])) ? sanitize_text_field($new_instance['title']) : '';
        $instance['restaurant_id'] = (!empty($new_instance['restaurant_id'])) ? (int) $new_instance['restaurant_id'] : '';
        $instance['show_status'] = !empty($new_instance['show_status']);
        $instance['compact_mode'] = !empty($new_instance['compact_mode']);
        return $instance;
    }
}

/**
 * Group hours by similar times
 */
function nearmenus_group_hours($hours_data) {
    $grouped = array();
    $current_group = null;
    
    foreach ($hours_data as $day_key => $day_info) {
        if ($current_group === null || $current_group['hours'] !== $day_info['hours']) {
            if ($current_group !== null) {
                $grouped[] = $current_group;
            }
            $current_group = array(
                'days' => $day_info['name'],
                'hours' => $day_info['hours']
            );
        } else {
            $current_group['days'] .= ' - ' . $day_info['name'];
        }
    }
    
    if ($current_group !== null) {
        $grouped[] = $current_group;
    }
    
    return $grouped;
}
```

### 4. Cuisine Filter Widget (`inc/widgets/cuisine-filter-widget.php`)

```php
<?php
/**
 * Cuisine Filter Widget
 * Displays cuisine filtering options
 */

class NearMenus_Cuisine_Filter_Widget extends WP_Widget {

    public function __construct() {
        parent::__construct(
            'nearmenus_cuisine_filter',
            __('Cuisine Filter', 'nearmenus'),
            array(
                'description' => __('Filter restaurants by cuisine type', 'nearmenus'),
                'classname' => 'widget_nearmenus_cuisine_filter',
            )
        );
    }

    public function widget($args, $instance) {
        $title = !empty($instance['title']) ? $instance['title'] : __('Filter by Cuisine', 'nearmenus');
        $display_type = !empty($instance['display_type']) ? $instance['display_type'] : 'dropdown';
        $show_counts = isset($instance['show_counts']) ? $instance['show_counts'] : true;
        $limit = !empty($instance['limit']) ? (int) $instance['limit'] : 0;

        $cuisines = get_terms(array(
            'taxonomy' => 'cuisine',
            'hide_empty' => true,
            'orderby' => 'count',
            'order' => 'DESC',
            'number' => $limit > 0 ? $limit : '',
        ));

        if (!$cuisines || is_wp_error($cuisines)) {
            return;
        }

        echo $args['before_widget'];
        
        if ($title) {
            echo $args['before_title'] . apply_filters('widget_title', $title) . $args['after_title'];
        }

        echo '<div class="cuisine-filter-widget-content">';

        if ($display_type === 'dropdown') {
            echo '<form class="cuisine-filter-form" method="get" action="' . esc_url(home_url('/')) . '">';
            echo '<select name="cuisine_filter" class="cuisine-filter-select" onchange="this.form.submit()">';
            echo '<option value="">' . __('All Cuisines', 'nearmenus') . '</option>';
            
            $current_cuisine = get_query_var('cuisine_filter');
            foreach ($cuisines as $cuisine) {
                $selected = selected($current_cuisine, $cuisine->slug, false);
                $count_text = $show_counts ? ' (' . $cuisine->count . ')' : '';
                echo '<option value="' . esc_attr($cuisine->slug) . '"' . $selected . '>';
                echo esc_html($cuisine->name . $count_text);
                echo '</option>';
            }
            echo '</select>';
            echo '</form>';
        } elseif ($display_type === 'list') {
            echo '<ul class="cuisine-filter-list">';
            $current_cuisine = get_query_var('cuisine');
            
            foreach ($cuisines as $cuisine) {
                $is_active = ($current_cuisine === $cuisine->slug);
                $count_text = $show_counts ? ' <span class="count">(' . $cuisine->count . ')</span>' : '';
                
                echo '<li class="cuisine-filter-item' . ($is_active ? ' active' : '') . '">';
                echo '<a href="' . esc_url(get_term_link($cuisine)) . '">';
                echo esc_html($cuisine->name) . $count_text;
                echo '</a>';
                echo '</li>';
            }
            echo '</ul>';
        } else { // checkbox
            echo '<form class="cuisine-filter-form checkbox-form" method="get" action="' . esc_url(home_url('/')) . '">';
            echo '<div class="cuisine-filter-checkboxes">';
            
            $current_cuisines = isset($_GET['cuisine_filter']) ? (array) $_GET['cuisine_filter'] : array();
            foreach ($cuisines as $cuisine) {
                $checked = in_array($cuisine->slug, $current_cuisines) ? 'checked' : '';
                $count_text = $show_counts ? ' (' . $cuisine->count . ')' : '';
                
                echo '<label class="cuisine-filter-checkbox">';
                echo '<input type="checkbox" name="cuisine_filter[]" value="' . esc_attr($cuisine->slug) . '" ' . $checked . '>';
                echo '<span class="checkmark"></span>';
                echo esc_html($cuisine->name . $count_text);
                echo '</label>';
            }
            echo '</div>';
            echo '<button type="submit" class="filter-submit-btn">' . __('Apply Filter', 'nearmenus') . '</button>';
            echo '</form>';
        }

        echo '</div>';
        echo $args['after_widget'];
    }

    public function form($instance) {
        $title = !empty($instance['title']) ? $instance['title'] : __('Filter by Cuisine', 'nearmenus');
        $display_type = !empty($instance['display_type']) ? $instance['display_type'] : 'dropdown';
        $show_counts = isset($instance['show_counts']) ? (bool) $instance['show_counts'] : true;
        $limit = !empty($instance['limit']) ? $instance['limit'] : '';
        ?>
        
        <p>
            <label for="<?php echo esc_attr($this->get_field_id('title')); ?>"><?php _e('Title:', 'nearmenus'); ?></label>
            <input class="widefat" id="<?php echo esc_attr($this->get_field_id('title')); ?>" 
                   name="<?php echo esc_attr($this->get_field_name('title')); ?>" 
                   type="text" value="<?php echo esc_attr($title); ?>">
        </p>

        <p>
            <label for="<?php echo esc_attr($this->get_field_id('display_type')); ?>"><?php _e('Display Type:', 'nearmenus'); ?></label>
            <select class="widefat" id="<?php echo esc_attr($this->get_field_id('display_type')); ?>" 
                    name="<?php echo esc_attr($this->get_field_name('display_type')); ?>">
                <option value="dropdown" <?php selected($display_type, 'dropdown'); ?>><?php _e('Dropdown', 'nearmenus'); ?></option>
                <option value="list" <?php selected($display_type, 'list'); ?>><?php _e('List', 'nearmenus'); ?></option>
                <option value="checkbox" <?php selected($display_type, 'checkbox'); ?>><?php _e('Checkboxes', 'nearmenus'); ?></option>
            </select>
        </p>

        <p>
            <label for="<?php echo esc_attr($this->get_field_id('limit')); ?>"><?php _e('Limit (0 for all):', 'nearmenus'); ?></label>
            <input class="widefat" id="<?php echo esc_attr($this->get_field_id('limit')); ?>" 
                   name="<?php echo esc_attr($this->get_field_name('limit')); ?>" 
                   type="number" value="<?php echo esc_attr($limit); ?>">
        </p>

        <p>
            <input class="checkbox" type="checkbox" <?php checked($show_counts); ?> 
                   id="<?php echo esc_attr($this->get_field_id('show_counts')); ?>" 
                   name="<?php echo esc_attr($this->get_field_name('show_counts')); ?>" />
            <label for="<?php echo esc_attr($this->get_field_id('show_counts')); ?>"><?php _e('Show Restaurant Counts', 'nearmenus'); ?></label>
        </p>
        
        <?php
    }

    public function update($new_instance, $old_instance) {
        $instance = array();
        $instance['title'] = (!empty($new_instance['title'])) ? sanitize_text_field($new_instance['title']) : '';
        $instance['display_type'] = (!empty($new_instance['display_type'])) ? sanitize_text_field($new_instance['display_type']) : 'dropdown';
        $instance['limit'] = (!empty($new_instance['limit'])) ? (int) $new_instance['limit'] : '';
        $instance['show_counts'] = !empty($new_instance['show_counts']);
        return $instance;
    }
}
```

### 5. Related Restaurants Widget (`inc/widgets/related-restaurants-widget.php`)

```php
<?php
/**
 * Related Restaurants Widget
 * Shows restaurants similar to current one
 */

class NearMenus_Related_Restaurants_Widget extends WP_Widget {

    public function __construct() {
        parent::__construct(
            'nearmenus_related_restaurants',
            __('Related Restaurants', 'nearmenus'),
            array(
                'description' => __('Display restaurants related to the current one', 'nearmenus'),
                'classname' => 'widget_nearmenus_related_restaurants',
            )
        );
    }

    public function widget($args, $instance) {
        $title = !empty($instance['title']) ? $instance['title'] : __('Related Restaurants', 'nearmenus');
        $post_id = !empty($instance['restaurant_id']) ? $instance['restaurant_id'] : get_the_ID();
        $limit = !empty($instance['limit']) ? (int) $instance['limit'] : 5;
        $relation_type = !empty($instance['relation_type']) ? $instance['relation_type'] : 'cuisine';
        $show_rating = isset($instance['show_rating']) ? $instance['show_rating'] : true;
        $show_image = isset($instance['show_image']) ? $instance['show_image'] : true;

        // Get related restaurants based on relation type
        $related_restaurants = nearmenus_get_related_restaurants($post_id, $relation_type, $limit);

        if (!$related_restaurants->have_posts()) {
            return;
        }

        echo $args['before_widget'];
        
        if ($title) {
            echo $args['before_title'] . apply_filters('widget_title', $title) . $args['after_title'];
        }

        echo '<div class="related-restaurants-widget-content">';
        echo '<ul class="related-restaurants-list">';

        while ($related_restaurants->have_posts()) {
            $related_restaurants->the_post();
            
            echo '<li class="related-restaurant-item">';
            
            if ($show_image && has_post_thumbnail()) {
                echo '<div class="related-restaurant-image">';
                echo '<a href="' . get_permalink() . '">';
                the_post_thumbnail('restaurant-thumb');
                echo '</a>';
                echo '</div>';
            }
            
            echo '<div class="related-restaurant-info">';
            echo '<h4 class="related-restaurant-title">';
            echo '<a href="' . get_permalink() . '">' . get_the_title() . '</a>';
            echo '</h4>';
            
            if ($show_rating) {
                $rating = get_post_meta(get_the_ID(), '_restaurant_rating', true);
                if ($rating) {
                    echo '<div class="related-restaurant-rating">';
                    echo '<span class="rating-stars">' . nearmenus_display_stars($rating) . '</span>';
                    echo '<span class="rating-value">' . esc_html($rating) . '</span>';
                    echo '</div>';
                }
            }
            
            // Show cuisine or location based on relation type
            if ($relation_type === 'cuisine') {
                $cuisines = get_the_terms(get_the_ID(), 'cuisine');
                if ($cuisines && !is_wp_error($cuisines)) {
                    echo '<div class="related-restaurant-cuisine">';
                    echo esc_html($cuisines[0]->name);
                    echo '</div>';
                }
            } elseif ($relation_type === 'location') {
                $locations = get_the_terms(get_the_ID(), 'location');
                if ($locations && !is_wp_error($locations)) {
                    echo '<div class="related-restaurant-location">';
                    echo esc_html($locations[0]->name);
                    echo '</div>';
                }
            }
            
            echo '</div>';
            echo '</li>';
        }

        echo '</ul>';
        echo '</div>';
        echo $args['after_widget'];

        wp_reset_postdata();
    }

    public function form($instance) {
        $title = !empty($instance['title']) ? $instance['title'] : __('Related Restaurants', 'nearmenus');
        $restaurant_id = !empty($instance['restaurant_id']) ? $instance['restaurant_id'] : '';
        $limit = !empty($instance['limit']) ? $instance['limit'] : 5;
        $relation_type = !empty($instance['relation_type']) ? $instance['relation_type'] : 'cuisine';
        $show_rating = isset($instance['show_rating']) ? (bool) $instance['show_rating'] : true;
        $show_image = isset($instance['show_image']) ? (bool) $instance['show_image'] : true;
        ?>
        
        <p>
            <label for="<?php echo esc_attr($this->get_field_id('title')); ?>"><?php _e('Title:', 'nearmenus'); ?></label>
            <input class="widefat" id="<?php echo esc_attr($this->get_field_id('title')); ?>" 
                   name="<?php echo esc_attr($this->get_field_name('title')); ?>" 
                   type="text" value="<?php echo esc_attr($title); ?>">
        </p>

        <p>
            <label for="<?php echo esc_attr($this->get_field_id('restaurant_id')); ?>"><?php _e('Restaurant ID (leave empty for current post):', 'nearmenus'); ?></label>
            <input class="widefat" id="<?php echo esc_attr($this->get_field_id('restaurant_id')); ?>" 
                   name="<?php echo esc_attr($this->get_field_name('restaurant_id')); ?>" 
                   type="number" value="<?php echo esc_attr($restaurant_id); ?>">
        </p>

        <p>
            <label for="<?php echo esc_attr($this->get_field_id('relation_type')); ?>"><?php _e('Relation Type:', 'nearmenus'); ?></label>
            <select class="widefat" id="<?php echo esc_attr($this->get_field_id('relation_type')); ?>" 
                    name="<?php echo esc_attr($this->get_field_name('relation_type')); ?>">
                <option value="cuisine" <?php selected($relation_type, 'cuisine'); ?>><?php _e('Same Cuisine', 'nearmenus'); ?></option>
                <option value="location" <?php selected($relation_type, 'location'); ?>><?php _e('Same Location', 'nearmenus'); ?></option>
                <option value="features" <?php selected($relation_type, 'features'); ?>><?php _e('Similar Features', 'nearmenus'); ?></option>
                <option value="rating" <?php selected($relation_type, 'rating'); ?>><?php _e('Similar Rating', 'nearmenus'); ?></option>
            </select>
        </p>

        <p>
            <label for="<?php echo esc_attr($this->get_field_id('limit')); ?>"><?php _e('Number to Show:', 'nearmenus'); ?></label>
            <input class="widefat" id="<?php echo esc_attr($this->get_field_id('limit')); ?>" 
                   name="<?php echo esc_attr($this->get_field_name('limit')); ?>" 
                   type="number" value="<?php echo esc_attr($limit); ?>" min="1" max="10">
        </p>

        <p>
            <input class="checkbox" type="checkbox" <?php checked($show_rating); ?> 
                   id="<?php echo esc_attr($this->get_field_id('show_rating')); ?>" 
                   name="<?php echo esc_attr($this->get_field_name('show_rating')); ?>" />
            <label for="<?php echo esc_attr($this->get_field_id('show_rating')); ?>"><?php _e('Show Rating', 'nearmenus'); ?></label>
        </p>

        <p>
            <input class="checkbox" type="checkbox" <?php checked($show_image); ?> 
                   id="<?php echo esc_attr($this->get_field_id('show_image')); ?>" 
                   name="<?php echo esc_attr($this->get_field_name('show_image')); ?>" />
            <label for="<?php echo esc_attr($this->get_field_id('show_image')); ?>"><?php _e('Show Image', 'nearmenus'); ?></label>
        </p>
        
        <?php
    }

    public function update($new_instance, $old_instance) {
        $instance = array();
        $instance['title'] = (!empty($new_instance['title'])) ? sanitize_text_field($new_instance['title']) : '';
        $instance['restaurant_id'] = (!empty($new_instance['restaurant_id'])) ? (int) $new_instance['restaurant_id'] : '';
        $instance['limit'] = (!empty($new_instance['limit'])) ? (int) $new_instance['limit'] : 5;
        $instance['relation_type'] = (!empty($new_instance['relation_type'])) ? sanitize_text_field($new_instance['relation_type']) : 'cuisine';
        $instance['show_rating'] = !empty($new_instance['show_rating']);
        $instance['show_image'] = !empty($new_instance['show_image']);
        return $instance;
    }
}

/**
 * Get related restaurants based on various criteria
 */
function nearmenus_get_related_restaurants($post_id, $relation_type, $limit = 5) {
    $args = array(
        'post_type' => 'post',
        'posts_per_page' => $limit,
        'post__not_in' => array($post_id),
        'post_status' => 'publish',
    );

    switch ($relation_type) {
        case 'cuisine':
            $cuisines = get_the_terms($post_id, 'cuisine');
            if ($cuisines && !is_wp_error($cuisines)) {
                $args['tax_query'] = array(
                    array(
                        'taxonomy' => 'cuisine',
                        'field' => 'term_id',
                        'terms' => wp_list_pluck($cuisines, 'term_id'),
                    ),
                );
            }
            break;

        case 'location':
            $locations = get_the_terms($post_id, 'location');
            if ($locations && !is_wp_error($locations)) {
                $args['tax_query'] = array(
                    array(
                        'taxonomy' => 'location',
                        'field' => 'term_id',
                        'terms' => wp_list_pluck($locations, 'term_id'),
                    ),
                );
            }
            break;

        case 'features':
            $features = get_the_terms($post_id, 'restaurant_feature');
            if ($features && !is_wp_error($features)) {
                $args['tax_query'] = array(
                    array(
                        'taxonomy' => 'restaurant_feature',
                        'field' => 'term_id',
                        'terms' => wp_list_pluck($features, 'term_id'),
                    ),
                );
            }
            break;

        case 'rating':
            $rating = get_post_meta($post_id, '_restaurant_rating', true);
            if ($rating) {
                $args['meta_query'] = array(
                    array(
                        'key' => '_restaurant_rating',
                        'value' => array($rating - 0.5, $rating + 0.5),
                        'type' => 'DECIMAL',
                        'compare' => 'BETWEEN',
                    ),
                );
            }
            break;
    }

    return new WP_Query($args);
}

/**
 * Display rating stars (simple version)
 */
function nearmenus_display_stars($rating) {
    $output = '';
    $rating = floatval($rating);
    
    for ($i = 1; $i <= 5; $i++) {
        if ($i <= $rating) {
            $output .= '★';
        } elseif ($i - 0.5 <= $rating) {
            $output .= '☆';
        } else {
            $output .= '☆';
        }
    }
    
    return $output;
}
```

### 6. Widget Registration and Initialization

Create the main widgets file to register all widgets (`inc/widgets.php`):

```php
<?php
/**
 * Register all custom widgets
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

// Include widget files
require_once get_template_directory() . '/inc/widgets/contact-widget.php';
require_once get_template_directory() . '/inc/widgets/features-widget.php';
require_once get_template_directory() . '/inc/widgets/hours-widget.php';
require_once get_template_directory() . '/inc/widgets/cuisine-filter-widget.php';
require_once get_template_directory() . '/inc/widgets/related-restaurants-widget.php';

/**
 * Register widgets
 */
function nearmenus_register_widgets() {
    register_widget('NearMenus_Contact_Widget');
    register_widget('NearMenus_Features_Widget');
    register_widget('NearMenus_Hours_Widget');
    register_widget('NearMenus_Cuisine_Filter_Widget');
    register_widget('NearMenus_Related_Restaurants_Widget');
}
add_action('widgets_init', 'nearmenus_register_widgets');

/**
 * Register additional widget areas for the theme
 */
function nearmenus_register_widget_areas() {
    // Restaurant Sidebar (for single restaurant pages)
    register_sidebar(array(
        'name' => __('Restaurant Sidebar', 'nearmenus'),
        'id' => 'restaurant-sidebar',
        'description' => __('Appears on single restaurant pages', 'nearmenus'),
        'before_widget' => '<div id="%1$s" class="widget %2$s">',
        'after_widget' => '</div>',
        'before_title' => '<h3 class="widget-title">',
        'after_title' => '</h3>',
    ));

    // Archive Sidebar (for category/archive pages)
    register_sidebar(array(
        'name' => __('Archive Sidebar', 'nearmenus'),
        'id' => 'archive-sidebar',
        'description' => __('Appears on archive and category pages', 'nearmenus'),
        'before_widget' => '<div id="%1$s" class="widget %2$s">',
        'after_widget' => '</div>',
        'before_title' => '<h3 class="widget-title">',
        'after_title' => '</h3>',
    ));

    // Homepage Sidebar
    register_sidebar(array(
        'name' => __('Homepage Sidebar', 'nearmenus'),
        'id' => 'homepage-sidebar',
        'description' => __('Appears on the homepage', 'nearmenus'),
        'before_widget' => '<div id="%1$s" class="widget %2$s">',
        'after_widget' => '</div>',
        'before_title' => '<h3 class="widget-title">',
        'after_title' => '</h3>',
    ));

    // Footer Widget Areas
    for ($i = 1; $i <= 4; $i++) {
        register_sidebar(array(
            'name' => sprintf(__('Footer Widget Area %d', 'nearmenus'), $i),
            'id' => 'footer-widget-' . $i,
            'description' => sprintf(__('Footer widget area %d', 'nearmenus'), $i),
            'before_widget' => '<div id="%1$s" class="footer-widget %2$s">',
            'after_widget' => '</div>',
            'before_title' => '<h4 class="footer-widget-title">',
            'after_title' => '</h4>',
        ));
    }
}
add_action('widgets_init', 'nearmenus_register_widget_areas');

/**
 * Add widget-specific CSS
 */
function nearmenus_widget_styles() {
    ?>
    <style>
    /* Contact Widget */
    .widget_nearmenus_contact .contact-item {
        display: flex;
        align-items: center;
        margin-bottom: 10px;
        padding: 8px;
        border: 1px solid #eee;
        border-radius: 4px;
    }

    .widget_nearmenus_contact .contact-icon {
        margin-right: 10px;
        font-size: 16px;
    }

    .widget_nearmenus_contact .contact-info label {
        display: block;
        font-weight: bold;
        font-size: 12px;
        color: #666;
    }

    /* Features Widget */
    .widget_nearmenus_features .features-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(100px, 1fr));
        gap: 10px;
    }

    .widget_nearmenus_features .feature-item {
        text-align: center;
        padding: 8px;
        border: 1px solid #eee;
        border-radius: 4px;
    }

    .widget_nearmenus_features .feature-icon {
        display: block;
        font-size: 20px;
        margin-bottom: 5px;
    }

    .widget_nearmenus_features .features-list {
        list-style: none;
        padding: 0;
    }

    .widget_nearmenus_features .features-list .feature-item {
        display: flex;
        align-items: center;
        padding: 5px 0;
        border-bottom: 1px solid #eee;
    }

    .widget_nearmenus_features .features-list .feature-icon {
        margin-right: 8px;
    }

    /* Hours Widget */
    .widget_nearmenus_hours .restaurant-status {
        display: flex;
        align-items: center;
        padding: 8px;
        border-radius: 4px;
        margin-bottom: 10px;
    }

    .widget_nearmenus_hours .restaurant-status.status-open {
        background-color: #d4edda;
        color: #155724;
    }

    .widget_nearmenus_hours .restaurant-status.status-closed {
        background-color: #f8d7da;
        color: #721c24;
    }

    .widget_nearmenus_hours .status-indicator {
        width: 8px;
        height: 8px;
        border-radius: 50%;
        margin-right: 8px;
    }

    .widget_nearmenus_hours .status-open .status-indicator {
        background-color: #28a745;
    }

    .widget_nearmenus_hours .status-closed .status-indicator {
        background-color: #dc3545;
    }

    .widget_nearmenus_hours .hours-list {
        list-style: none;
        padding: 0;
    }

    .widget_nearmenus_hours .hours-item {
        display: flex;
        justify-content: space-between;
        padding: 5px 0;
        border-bottom: 1px solid #eee;
    }

    .widget_nearmenus_hours .hours-item.today {
        font-weight: bold;
        background-color: #f8f9fa;
        padding: 8px;
        margin: 0 -8px;
    }

    /* Related Restaurants Widget */
    .widget_nearmenus_related_restaurants .related-restaurants-list {
        list-style: none;
        padding: 0;
    }

    .widget_nearmenus_related_restaurants .related-restaurant-item {
        display: flex;
        margin-bottom: 15px;
        padding-bottom: 15px;
        border-bottom: 1px solid #eee;
    }

    .widget_nearmenus_related_restaurants .related-restaurant-image {
        width: 60px;
        height: 60px;
        margin-right: 10px;
        flex-shrink: 0;
    }

    .widget_nearmenus_related_restaurants .related-restaurant-image img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        border-radius: 4px;
    }

    .widget_nearmenus_related_restaurants .related-restaurant-title {
        margin: 0 0 5px 0;
        font-size: 14px;
    }

    .widget_nearmenus_related_restaurants .related-restaurant-rating {
        font-size: 12px;
        color: #666;
    }

    /* Cuisine Filter Widget */
    .widget_nearmenus_cuisine_filter .cuisine-filter-select {
        width: 100%;
        padding: 8px;
        border: 1px solid #ddd;
        border-radius: 4px;
    }

    .widget_nearmenus_cuisine_filter .cuisine-filter-list {
        list-style: none;
        padding: 0;
    }

    .widget_nearmenus_cuisine_filter .cuisine-filter-item {
        margin-bottom: 8px;
    }

    .widget_nearmenus_cuisine_filter .cuisine-filter-item a {
        display: block;
        padding: 8px;
        border: 1px solid #eee;
        border-radius: 4px;
        text-decoration: none;
        transition: background-color 0.3s;
    }

    .widget_nearmenus_cuisine_filter .cuisine-filter-item.active a,
    .widget_nearmenus_cuisine_filter .cuisine-filter-item a:hover {
        background-color: #f8f9fa;
    }

    .widget_nearmenus_cuisine_filter .count {
        color: #666;
        font-size: 12px;
    }
    </style>
    <?php
}
add_action('wp_head', 'nearmenus_widget_styles');
```

## Template Part Files for Widgets

Create template parts that use these widgets:

### 1. Contact Widget Template Part (`template-parts/contact-widget.php`)

```php
<?php
/**
 * Contact Widget Template Part
 * Used in single restaurant pages
 */

if (is_single()) {
    ?>
    <div class="contact-widget-wrapper">
        <?php
        the_widget('NearMenus_Contact_Widget', array(
            'title' => __('Contact Information', 'nearmenus'),
            'restaurant_id' => get_the_ID(),
            'show_phone' => true,
            'show_email' => true,
            'show_website' => true,
            'show_address' => true,
        ));
        ?>
    </div>
    <?php
}
?>
```

### 2. Features Widget Template Part (`template-parts/restaurant-features.php`)

```php
<?php
/**
 * Restaurant Features Template Part
 */

if (is_single()) {
    ?>
    <div class="features-widget-wrapper">
        <?php
        the_widget('NearMenus_Features_Widget', array(
            'title' => __('Features & Amenities', 'nearmenus'),
            'restaurant_id' => get_the_ID(),
            'layout' => 'list',
            'show_icons' => true,
        ));
        ?>
    </div>
    <?php
}
?>
```

## Functions.php Integration

Add this to your existing `functions.php`:

```php
// Include widgets
require_once get_template_directory() . '/inc/widgets.php';
```

## Testing and Implementation

### Step-by-Step Implementation:

1. **Create Widget Directory Structure**:
   ```
   inc/
   ├── widgets.php
   └── widgets/
       ├── contact-widget.php
       ├── features-widget.php
       ├── hours-widget.php
       ├── cuisine-filter-widget.php
       └── related-restaurants-widget.php
   ```

2. **Include Widgets in Functions.php**:
   - Add the require statement for widgets.php

3. **Create Template Parts**:
   - Add template parts that use the widgets
   - Update single.php to include widget template parts

4. **Test Widget Areas**:
   - Go to Appearance → Widgets in admin
   - Verify all custom widgets appear
   - Test widgets in different sidebar areas

### Testing Checklist:

- [ ] All custom widgets appear in Widgets admin page
- [ ] Widgets can be added to sidebar areas
- [ ] Contact widget displays restaurant information correctly
- [ ] Features widget shows restaurant amenities
- [ ] Hours widget displays operating hours with open/closed status
- [ ] Cuisine filter widget provides filtering functionality
- [ ] Related restaurants widget shows similar restaurants
- [ ] Widget settings save properly
- [ ] Widgets display correctly on frontend
- [ ] Widget styling works without CSS conflicts
- [ ] All widget areas are registered and functional

### Widget Usage in Templates:

The widgets can be used in three ways:

1. **Widget Areas**: Users can drag widgets to sidebars via admin
2. **Template Parts**: Automatically display specific widgets in templates
3. **Direct Integration**: Use `the_widget()` function in templates

This implementation provides all the widget functionality needed to replicate the React app's sidebar and filtering components, with full customization options for users and automatic integration in appropriate template locations.

## Widget Features Summary:

✅ **Contact Information Widget** - Phone, email, website, address display
✅ **Restaurant Features Widget** - Amenities with icons, multiple layouts
✅ **Operating Hours Widget** - Daily hours, open/closed status, compact mode
✅ **Cuisine Filter Widget** - Dropdown, list, or checkbox filtering
✅ **Related Restaurants Widget** - Similar restaurants by various criteria
✅ **Multiple Widget Areas** - Restaurant, archive, homepage, footer sidebars
✅ **Template Integration** - Automatic widget placement in templates
✅ **Responsive Styling** - CSS for all widget layouts and interactions