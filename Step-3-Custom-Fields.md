# Step 3: Custom Fields Implementation

## Overview
This step implements custom fields for the WordPress theme based on the React NearMenus project analysis. We'll create all the necessary fields to store restaurant data as identified in the React app, with options for both ACF plugin and manual implementation.

## React Project Analysis for Custom Fields

### From React Data Structure (`restaurantData.ts`):

**Restaurant Object Properties Identified:**
- **Basic Info**: id, name, location, rating, reviews, description, phone, email, website, address
- **Business Details**: cuisine, priceRange, hours (by day), specialOffer
- **Services**: features array, isOpen, hasDelivery
- **Media**: images array, featured image
- **Menu System**: Complex nested menu structure with categories and items
- **Reviews**: customerReviews array with ratings, comments, images
- **Policies**: Restaurant policies array

**Menu Structure Analysis:**
```javascript
menu: {
  appetizers: [{ name, description, price, popular }],
  pasta: [{ name, description, price, popular }],
  pizza: [{ name, description, price, popular }],
  mains: [{ name, description, price, popular }],
  desserts: [{ name, description, price, popular }],
  beverages: [{ name, description, price, popular }]
}
```

**Hours Structure:**
```javascript
hours: {
  "Monday - Thursday": "11:00 AM - 10:00 PM",
  "Friday - Saturday": "11:00 AM - 11:00 PM",
  "Sunday": "12:00 PM - 9:00 PM"
}
```

## Required Custom Fields

Based on React app analysis, we need these field groups:

### 1. Restaurant Basic Information
- Rating (0-5, decimal)
- Reviews Count (number)
- Phone Number (text)
- Email Address (email)
- Website URL (url)
- Physical Address (textarea)
- Special Offer (textarea)
- Currently Open (checkbox)
- Offers Delivery (checkbox)

### 2. Operating Hours
- Monday Hours (text)
- Tuesday Hours (text)
- Wednesday Hours (text)
- Thursday Hours (text)
- Friday Hours (text)
- Saturday Hours (text)
- Sunday Hours (text)
- Holiday Hours (textarea)

### 3. Menu System
- Menu Categories (repeater)
  - Category Name (text)
  - Category Description (textarea)
  - Menu Items (repeater)
    - Item Name (text)
    - Item Description (textarea)
    - Item Price (number)
    - Popular Item (checkbox)
    - Item Image (image)

### 4. Restaurant Gallery
- Gallery Images (gallery)
- Image Credits (textarea)

### 5. Additional Information
- Popular Dishes (textarea)
- Restaurant Policies (textarea)
- Dress Code (text)
- Reservations Required (checkbox)
- Parking Information (textarea)

## Implementation Options

### Option A: ACF Plugin Implementation

#### 1. ACF Field Groups Configuration

```php
<?php
/**
 * ACF Field Groups for Restaurant Theme
 * Import this into ACF or use acf_add_local_field_group()
 */

if (function_exists('acf_add_local_field_group')) {

    // Restaurant Basic Information
    acf_add_local_field_group(array(
        'key' => 'group_restaurant_basic_info',
        'title' => 'Restaurant Basic Information',
        'fields' => array(
            array(
                'key' => 'field_restaurant_rating',
                'label' => 'Rating',
                'name' => 'restaurant_rating',
                'type' => 'number',
                'instructions' => 'Restaurant rating from 0 to 5 (decimals allowed)',
                'required' => 0,
                'min' => 0,
                'max' => 5,
                'step' => 0.1,
                'default_value' => 0,
            ),
            array(
                'key' => 'field_restaurant_reviews_count',
                'label' => 'Reviews Count',
                'name' => 'restaurant_reviews_count',
                'type' => 'number',
                'instructions' => 'Total number of reviews',
                'required' => 0,
                'min' => 0,
                'default_value' => 0,
            ),
            array(
                'key' => 'field_restaurant_phone',
                'label' => 'Phone Number',
                'name' => 'restaurant_phone',
                'type' => 'text',
                'instructions' => 'Restaurant phone number',
                'placeholder' => '+1 (305) 555-0123',
            ),
            array(
                'key' => 'field_restaurant_email',
                'label' => 'Email Address',
                'name' => 'restaurant_email',
                'type' => 'email',
                'instructions' => 'Restaurant email address',
                'placeholder' => 'info@restaurant.com',
            ),
            array(
                'key' => 'field_restaurant_website',
                'label' => 'Website URL',
                'name' => 'restaurant_website',
                'type' => 'url',
                'instructions' => 'Restaurant website URL',
                'placeholder' => 'https://restaurant.com',
            ),
            array(
                'key' => 'field_restaurant_address',
                'label' => 'Physical Address',
                'name' => 'restaurant_address',
                'type' => 'textarea',
                'instructions' => 'Full restaurant address',
                'rows' => 3,
                'placeholder' => '123 Main Street, City, State 12345',
            ),
            array(
                'key' => 'field_restaurant_special_offer',
                'label' => 'Special Offer',
                'name' => 'restaurant_special_offer',
                'type' => 'textarea',
                'instructions' => 'Current special offers or promotions',
                'rows' => 3,
                'placeholder' => 'Happy Hour: 3-6 PM - 50% off appetizers!',
            ),
            array(
                'key' => 'field_restaurant_is_open',
                'label' => 'Currently Open',
                'name' => 'restaurant_is_open',
                'type' => 'true_false',
                'instructions' => 'Is the restaurant currently open?',
                'default_value' => 1,
                'ui' => 1,
            ),
            array(
                'key' => 'field_restaurant_has_delivery',
                'label' => 'Offers Delivery',
                'name' => 'restaurant_has_delivery',
                'type' => 'true_false',
                'instructions' => 'Does the restaurant offer delivery?',
                'default_value' => 0,
                'ui' => 1,
            ),
        ),
        'location' => array(
            array(
                array(
                    'param' => 'post_type',
                    'operator' => '==',
                    'value' => 'post',
                ),
            ),
        ),
        'menu_order' => 0,
        'position' => 'normal',
        'style' => 'default',
        'active' => true,
    ));

    // Operating Hours
    acf_add_local_field_group(array(
        'key' => 'group_restaurant_hours',
        'title' => 'Operating Hours',
        'fields' => array(
            array(
                'key' => 'field_hours_monday',
                'label' => 'Monday Hours',
                'name' => 'hours_monday',
                'type' => 'text',
                'placeholder' => '9:00 AM - 10:00 PM or Closed',
            ),
            array(
                'key' => 'field_hours_tuesday',
                'label' => 'Tuesday Hours',
                'name' => 'hours_tuesday',
                'type' => 'text',
                'placeholder' => '9:00 AM - 10:00 PM or Closed',
            ),
            array(
                'key' => 'field_hours_wednesday',
                'label' => 'Wednesday Hours',
                'name' => 'hours_wednesday',
                'type' => 'text',
                'placeholder' => '9:00 AM - 10:00 PM or Closed',
            ),
            array(
                'key' => 'field_hours_thursday',
                'label' => 'Thursday Hours',
                'name' => 'hours_thursday',
                'type' => 'text',
                'placeholder' => '9:00 AM - 10:00 PM or Closed',
            ),
            array(
                'key' => 'field_hours_friday',
                'label' => 'Friday Hours',
                'name' => 'hours_friday',
                'type' => 'text',
                'placeholder' => '9:00 AM - 11:00 PM or Closed',
            ),
            array(
                'key' => 'field_hours_saturday',
                'label' => 'Saturday Hours',
                'name' => 'hours_saturday',
                'type' => 'text',
                'placeholder' => '9:00 AM - 11:00 PM or Closed',
            ),
            array(
                'key' => 'field_hours_sunday',
                'label' => 'Sunday Hours',
                'name' => 'hours_sunday',
                'type' => 'text',
                'placeholder' => '10:00 AM - 9:00 PM or Closed',
            ),
            array(
                'key' => 'field_holiday_hours',
                'label' => 'Holiday Hours',
                'name' => 'holiday_hours',
                'type' => 'textarea',
                'instructions' => 'Special holiday hours or closures',
                'rows' => 3,
                'placeholder' => 'Closed on Thanksgiving and Christmas Day',
            ),
        ),
        'location' => array(
            array(
                array(
                    'param' => 'post_type',
                    'operator' => '==',
                    'value' => 'post',
                ),
            ),
        ),
        'menu_order' => 1,
        'position' => 'normal',
        'style' => 'default',
        'active' => true,
    ));

    // Menu System
    acf_add_local_field_group(array(
        'key' => 'group_restaurant_menu',
        'title' => 'Restaurant Menu',
        'fields' => array(
            array(
                'key' => 'field_menu_categories',
                'label' => 'Menu Categories',
                'name' => 'menu_categories',
                'type' => 'repeater',
                'instructions' => 'Add menu categories and items',
                'required' => 0,
                'layout' => 'block',
                'button_label' => 'Add Menu Category',
                'sub_fields' => array(
                    array(
                        'key' => 'field_category_name',
                        'label' => 'Category Name',
                        'name' => 'category_name',
                        'type' => 'text',
                        'required' => 1,
                        'placeholder' => 'Appetizers, Main Courses, Desserts, etc.',
                    ),
                    array(
                        'key' => 'field_category_description',
                        'label' => 'Category Description',
                        'name' => 'category_description',
                        'type' => 'textarea',
                        'rows' => 2,
                        'placeholder' => 'Brief description of this menu category',
                    ),
                    array(
                        'key' => 'field_menu_items',
                        'label' => 'Menu Items',
                        'name' => 'menu_items',
                        'type' => 'repeater',
                        'layout' => 'row',
                        'button_label' => 'Add Menu Item',
                        'sub_fields' => array(
                            array(
                                'key' => 'field_item_name',
                                'label' => 'Item Name',
                                'name' => 'item_name',
                                'type' => 'text',
                                'required' => 1,
                                'wrapper' => array('width' => '30'),
                            ),
                            array(
                                'key' => 'field_item_description',
                                'label' => 'Description',
                                'name' => 'item_description',
                                'type' => 'textarea',
                                'rows' => 2,
                                'wrapper' => array('width' => '40'),
                            ),
                            array(
                                'key' => 'field_item_price',
                                'label' => 'Price',
                                'name' => 'item_price',
                                'type' => 'number',
                                'min' => 0,
                                'step' => 0.01,
                                'prepend' => '$',
                                'wrapper' => array('width' => '15'),
                            ),
                            array(
                                'key' => 'field_item_popular',
                                'label' => 'Popular',
                                'name' => 'item_popular',
                                'type' => 'true_false',
                                'ui' => 1,
                                'wrapper' => array('width' => '15'),
                            ),
                        ),
                    ),
                ),
            ),
        ),
        'location' => array(
            array(
                array(
                    'param' => 'post_type',
                    'operator' => '==',
                    'value' => 'post',
                ),
            ),
        ),
        'menu_order' => 2,
        'position' => 'normal',
        'style' => 'default',
        'active' => true,
    ));

    // Additional Information
    acf_add_local_field_group(array(
        'key' => 'group_restaurant_additional',
        'title' => 'Additional Information',
        'fields' => array(
            array(
                'key' => 'field_popular_dishes',
                'label' => 'Popular Dishes',
                'name' => 'popular_dishes',
                'type' => 'textarea',
                'instructions' => 'List popular dishes (comma-separated)',
                'rows' => 3,
                'placeholder' => 'Margherita Pizza, Fettuccine Alfredo, Tiramisu',
            ),
            array(
                'key' => 'field_restaurant_policies',
                'label' => 'Restaurant Policies',
                'name' => 'restaurant_policies',
                'type' => 'textarea',
                'instructions' => 'Restaurant policies, dress code, etc.',
                'rows' => 4,
                'placeholder' => 'Reservations recommended, 18% gratuity for parties of 6+',
            ),
            array(
                'key' => 'field_dress_code',
                'label' => 'Dress Code',
                'name' => 'dress_code',
                'type' => 'select',
                'choices' => array(
                    'casual' => 'Casual',
                    'smart_casual' => 'Smart Casual',
                    'business_casual' => 'Business Casual',
                    'formal' => 'Formal',
                ),
                'allow_null' => 1,
                'default_value' => 'casual',
            ),
            array(
                'key' => 'field_reservations_required',
                'label' => 'Reservations Required',
                'name' => 'reservations_required',
                'type' => 'true_false',
                'instructions' => 'Are reservations required?',
                'default_value' => 0,
                'ui' => 1,
            ),
            array(
                'key' => 'field_parking_info',
                'label' => 'Parking Information',
                'name' => 'parking_info',
                'type' => 'textarea',
                'instructions' => 'Information about parking availability',
                'rows' => 3,
                'placeholder' => 'Valet parking available, Street parking nearby',
            ),
        ),
        'location' => array(
            array(
                array(
                    'param' => 'post_type',
                    'operator' => '==',
                    'value' => 'post',
                ),
            ),
        ),
        'menu_order' => 3,
        'position' => 'normal',
        'style' => 'default',
        'active' => true,
    ));
}
```

### Option B: Manual Custom Fields Implementation

#### 1. Custom Fields Registration (`inc/custom-fields.php`)

```php
<?php
/**
 * Manual Custom Fields Implementation
 * Based on React NearMenus app analysis
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Add custom meta boxes
 */
function nearmenus_add_custom_fields() {
    add_meta_box(
        'restaurant-basic-info',
        __('Restaurant Basic Information', 'nearmenus'),
        'nearmenus_basic_info_callback',
        'post',
        'normal',
        'high'
    );
    
    add_meta_box(
        'restaurant-hours',
        __('Operating Hours', 'nearmenus'),
        'nearmenus_hours_callback',
        'post',
        'normal',
        'high'
    );
    
    add_meta_box(
        'restaurant-menu',
        __('Restaurant Menu', 'nearmenus'),
        'nearmenus_menu_callback',
        'post',
        'normal',
        'high'
    );
    
    add_meta_box(
        'restaurant-additional',
        __('Additional Information', 'nearmenus'),
        'nearmenus_additional_callback',
        'post',
        'side',
        'default'
    );
}
add_action('add_meta_boxes', 'nearmenus_add_custom_fields');

/**
 * Basic Information Meta Box Callback
 */
function nearmenus_basic_info_callback($post) {
    wp_nonce_field('nearmenus_save_fields', 'nearmenus_fields_nonce');
    
    // Get current values
    $rating = get_post_meta($post->ID, '_restaurant_rating', true);
    $reviews_count = get_post_meta($post->ID, '_restaurant_reviews_count', true);
    $phone = get_post_meta($post->ID, '_restaurant_phone', true);
    $email = get_post_meta($post->ID, '_restaurant_email', true);
    $website = get_post_meta($post->ID, '_restaurant_website', true);
    $address = get_post_meta($post->ID, '_restaurant_address', true);
    $special_offer = get_post_meta($post->ID, '_restaurant_special_offer', true);
    $is_open = get_post_meta($post->ID, '_restaurant_is_open', true);
    $has_delivery = get_post_meta($post->ID, '_restaurant_has_delivery', true);
    ?>
    
    <table class="form-table">
        <tr>
            <th><label for="restaurant_rating"><?php _e('Rating', 'nearmenus'); ?></label></th>
            <td>
                <input type="number" 
                       id="restaurant_rating" 
                       name="restaurant_rating" 
                       value="<?php echo esc_attr($rating); ?>" 
                       min="0" 
                       max="5" 
                       step="0.1" 
                       style="width: 100px;" />
                <p class="description"><?php _e('Rating from 0 to 5 (decimals allowed)', 'nearmenus'); ?></p>
            </td>
        </tr>
        <tr>
            <th><label for="restaurant_reviews_count"><?php _e('Reviews Count', 'nearmenus'); ?></label></th>
            <td>
                <input type="number" 
                       id="restaurant_reviews_count" 
                       name="restaurant_reviews_count" 
                       value="<?php echo esc_attr($reviews_count); ?>" 
                       min="0" 
                       style="width: 100px;" />
                <p class="description"><?php _e('Total number of reviews', 'nearmenus'); ?></p>
            </td>
        </tr>
        <tr>
            <th><label for="restaurant_phone"><?php _e('Phone Number', 'nearmenus'); ?></label></th>
            <td>
                <input type="text" 
                       id="restaurant_phone" 
                       name="restaurant_phone" 
                       value="<?php echo esc_attr($phone); ?>" 
                       placeholder="+1 (305) 555-0123" 
                       style="width: 100%;" />
            </td>
        </tr>
        <tr>
            <th><label for="restaurant_email"><?php _e('Email Address', 'nearmenus'); ?></label></th>
            <td>
                <input type="email" 
                       id="restaurant_email" 
                       name="restaurant_email" 
                       value="<?php echo esc_attr($email); ?>" 
                       placeholder="info@restaurant.com" 
                       style="width: 100%;" />
            </td>
        </tr>
        <tr>
            <th><label for="restaurant_website"><?php _e('Website URL', 'nearmenus'); ?></label></th>
            <td>
                <input type="url" 
                       id="restaurant_website" 
                       name="restaurant_website" 
                       value="<?php echo esc_attr($website); ?>" 
                       placeholder="https://restaurant.com" 
                       style="width: 100%;" />
            </td>
        </tr>
        <tr>
            <th><label for="restaurant_address"><?php _e('Physical Address', 'nearmenus'); ?></label></th>
            <td>
                <textarea id="restaurant_address" 
                          name="restaurant_address" 
                          rows="3" 
                          style="width: 100%;" 
                          placeholder="123 Main Street, City, State 12345"><?php echo esc_textarea($address); ?></textarea>
            </td>
        </tr>
        <tr>
            <th><label for="restaurant_special_offer"><?php _e('Special Offer', 'nearmenus'); ?></label></th>
            <td>
                <textarea id="restaurant_special_offer" 
                          name="restaurant_special_offer" 
                          rows="3" 
                          style="width: 100%;" 
                          placeholder="Happy Hour: 3-6 PM - 50% off appetizers!"><?php echo esc_textarea($special_offer); ?></textarea>
            </td>
        </tr>
        <tr>
            <th><label for="restaurant_is_open"><?php _e('Currently Open', 'nearmenus'); ?></label></th>
            <td>
                <input type="checkbox" 
                       id="restaurant_is_open" 
                       name="restaurant_is_open" 
                       value="1" 
                       <?php checked($is_open, '1'); ?> />
                <label for="restaurant_is_open"><?php _e('Restaurant is currently open', 'nearmenus'); ?></label>
            </td>
        </tr>
        <tr>
            <th><label for="restaurant_has_delivery"><?php _e('Offers Delivery', 'nearmenus'); ?></label></th>
            <td>
                <input type="checkbox" 
                       id="restaurant_has_delivery" 
                       name="restaurant_has_delivery" 
                       value="1" 
                       <?php checked($has_delivery, '1'); ?> />
                <label for="restaurant_has_delivery"><?php _e('Restaurant offers delivery', 'nearmenus'); ?></label>
            </td>
        </tr>
    </table>
    
    <?php
}

/**
 * Operating Hours Meta Box Callback
 */
function nearmenus_hours_callback($post) {
    $days = array(
        'monday' => __('Monday', 'nearmenus'),
        'tuesday' => __('Tuesday', 'nearmenus'),
        'wednesday' => __('Wednesday', 'nearmenus'),
        'thursday' => __('Thursday', 'nearmenus'),
        'friday' => __('Friday', 'nearmenus'),
        'saturday' => __('Saturday', 'nearmenus'),
        'sunday' => __('Sunday', 'nearmenus'),
    );
    
    ?>
    <table class="form-table">
        <?php foreach ($days as $day => $label) : ?>
            <?php $hours = get_post_meta($post->ID, "_hours_{$day}", true); ?>
            <tr>
                <th><label for="hours_<?php echo $day; ?>"><?php echo $label; ?></label></th>
                <td>
                    <input type="text" 
                           id="hours_<?php echo $day; ?>" 
                           name="hours_<?php echo $day; ?>" 
                           value="<?php echo esc_attr($hours); ?>" 
                           placeholder="9:00 AM - 10:00 PM or Closed" 
                           style="width: 100%;" />
                </td>
            </tr>
        <?php endforeach; ?>
        <tr>
            <th><label for="holiday_hours"><?php _e('Holiday Hours', 'nearmenus'); ?></label></th>
            <td>
                <textarea id="holiday_hours" 
                          name="holiday_hours" 
                          rows="3" 
                          style="width: 100%;" 
                          placeholder="Closed on Thanksgiving and Christmas Day"><?php echo esc_textarea(get_post_meta($post->ID, '_holiday_hours', true)); ?></textarea>
                <p class="description"><?php _e('Special holiday hours or closures', 'nearmenus'); ?></p>
            </td>
        </tr>
    </table>
    <?php
}

/**
 * Menu Meta Box Callback (Simplified JSON storage)
 */
function nearmenus_menu_callback($post) {
    $menu_data = get_post_meta($post->ID, '_restaurant_menu', true);
    $menu_json = $menu_data ? json_encode($menu_data, JSON_PRETTY_PRINT) : '';
    ?>
    
    <p><strong><?php _e('Menu Data (JSON Format)', 'nearmenus'); ?></strong></p>
    <p class="description">
        <?php _e('Add menu data in JSON format. Example structure:', 'nearmenus'); ?>
    </p>
    
    <textarea id="restaurant_menu" 
              name="restaurant_menu" 
              rows="20" 
              style="width: 100%; font-family: monospace;" 
              placeholder='{
  "appetizers": [
    {
      "name": "Bruschetta",
      "description": "Toasted bread with tomatoes and basil",
      "price": 12.99,
      "popular": true
    }
  ],
  "mains": [
    {
      "name": "Pasta Carbonara",
      "description": "Traditional Italian pasta with eggs and bacon",
      "price": 18.99,
      "popular": false
    }
  ]
}'><?php echo esc_textarea($menu_json); ?></textarea>
    
    <div id="menu-builder" style="margin-top: 20px;">
        <h4><?php _e('Quick Menu Builder', 'nearmenus'); ?></h4>
        <button type="button" class="button" onclick="addMenuCategory()"><?php _e('Add Category', 'nearmenus'); ?></button>
        <div id="menu-categories"></div>
    </div>
    
    <script>
    let menuData = <?php echo $menu_json ? $menu_json : '{}'; ?>;
    
    function addMenuCategory() {
        const categoryName = prompt('Category Name (e.g., Appetizers, Mains):');
        if (categoryName && !menuData[categoryName.toLowerCase()]) {
            menuData[categoryName.toLowerCase()] = [];
            updateMenuDisplay();
            updateMenuTextarea();
        }
    }
    
    function addMenuItem(category) {
        const name = prompt('Item Name:');
        const description = prompt('Item Description:');
        const price = parseFloat(prompt('Item Price:'));
        const popular = confirm('Is this a popular item?');
        
        if (name && description && !isNaN(price)) {
            menuData[category].push({
                name: name,
                description: description,
                price: price,
                popular: popular
            });
            updateMenuDisplay();
            updateMenuTextarea();
        }
    }
    
    function updateMenuTextarea() {
        document.getElementById('restaurant_menu').value = JSON.stringify(menuData, null, 2);
    }
    
    function updateMenuDisplay() {
        const container = document.getElementById('menu-categories');
        container.innerHTML = '';
        
        for (const [category, items] of Object.entries(menuData)) {
            const categoryDiv = document.createElement('div');
            categoryDiv.style.marginBottom = '15px';
            categoryDiv.style.border = '1px solid #ddd';
            categoryDiv.style.padding = '10px';
            
            categoryDiv.innerHTML = `
                <h5>${category.charAt(0).toUpperCase() + category.slice(1)} (${items.length} items)</h5>
                <button type="button" class="button button-small" onclick="addMenuItem('${category}')">Add Item</button>
            `;
            
            container.appendChild(categoryDiv);
        }
    }
    
    // Initialize display
    updateMenuDisplay();
    </script>
    
    <?php
}

/**
 * Additional Information Meta Box Callback
 */
function nearmenus_additional_callback($post) {
    $popular_dishes = get_post_meta($post->ID, '_popular_dishes', true);
    $policies = get_post_meta($post->ID, '_restaurant_policies', true);
    $dress_code = get_post_meta($post->ID, '_dress_code', true);
    $reservations_required = get_post_meta($post->ID, '_reservations_required', true);
    $parking_info = get_post_meta($post->ID, '_parking_info', true);
    ?>
    
    <p>
        <label for="popular_dishes"><strong><?php _e('Popular Dishes', 'nearmenus'); ?></strong></label><br>
        <textarea id="popular_dishes" 
                  name="popular_dishes" 
                  rows="3" 
                  style="width: 100%;" 
                  placeholder="Margherita Pizza, Fettuccine Alfredo, Tiramisu"><?php echo esc_textarea($popular_dishes); ?></textarea>
        <small><?php _e('Comma-separated list', 'nearmenus'); ?></small>
    </p>
    
    <p>
        <label for="dress_code"><strong><?php _e('Dress Code', 'nearmenus'); ?></strong></label><br>
        <select id="dress_code" name="dress_code" style="width: 100%;">
            <option value=""><?php _e('Select dress code', 'nearmenus'); ?></option>
            <option value="casual" <?php selected($dress_code, 'casual'); ?>><?php _e('Casual', 'nearmenus'); ?></option>
            <option value="smart_casual" <?php selected($dress_code, 'smart_casual'); ?>><?php _e('Smart Casual', 'nearmenus'); ?></option>
            <option value="business_casual" <?php selected($dress_code, 'business_casual'); ?>><?php _e('Business Casual', 'nearmenus'); ?></option>
            <option value="formal" <?php selected($dress_code, 'formal'); ?>><?php _e('Formal', 'nearmenus'); ?></option>
        </select>
    </p>
    
    <p>
        <input type="checkbox" 
               id="reservations_required" 
               name="reservations_required" 
               value="1" 
               <?php checked($reservations_required, '1'); ?> />
        <label for="reservations_required"><?php _e('Reservations Required', 'nearmenus'); ?></label>
    </p>
    
    <p>
        <label for="restaurant_policies"><strong><?php _e('Restaurant Policies', 'nearmenus'); ?></strong></label><br>
        <textarea id="restaurant_policies" 
                  name="restaurant_policies" 
                  rows="4" 
                  style="width: 100%;" 
                  placeholder="18% gratuity for parties of 6+, No outside food or drinks"><?php echo esc_textarea($policies); ?></textarea>
    </p>
    
    <p>
        <label for="parking_info"><strong><?php _e('Parking Information', 'nearmenus'); ?></strong></label><br>
        <textarea id="parking_info" 
                  name="parking_info" 
                  rows="3" 
                  style="width: 100%;" 
                  placeholder="Valet parking available, Street parking nearby"><?php echo esc_textarea($parking_info); ?></textarea>
    </p>
    
    <?php
}

/**
 * Save custom fields
 */
function nearmenus_save_custom_fields($post_id) {
    // Check if our nonce is set and verify it
    if (!isset($_POST['nearmenus_fields_nonce']) || !wp_verify_nonce($_POST['nearmenus_fields_nonce'], 'nearmenus_save_fields')) {
        return;
    }

    // If this is an autosave, don't do anything
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
        return;
    }

    // Check user permissions
    if (!current_user_can('edit_post', $post_id)) {
        return;
    }

    // Save basic information fields
    $basic_fields = array(
        'restaurant_rating',
        'restaurant_reviews_count',
        'restaurant_phone',
        'restaurant_email',
        'restaurant_website',
        'restaurant_address',
        'restaurant_special_offer',
    );

    foreach ($basic_fields as $field) {
        if (isset($_POST[$field])) {
            update_post_meta($post_id, '_' . $field, sanitize_text_field($_POST[$field]));
        }
    }

    // Save checkbox fields
    $checkbox_fields = array('restaurant_is_open', 'restaurant_has_delivery', 'reservations_required');
    foreach ($checkbox_fields as $field) {
        $value = isset($_POST[$field]) ? '1' : '0';
        update_post_meta($post_id, '_' . $field, $value);
    }

    // Save hours
    $days = array('monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday', 'sunday');
    foreach ($days as $day) {
        if (isset($_POST["hours_{$day}"])) {
            update_post_meta($post_id, "_hours_{$day}", sanitize_text_field($_POST["hours_{$day}"]));
        }
    }

    // Save textarea fields
    $textarea_fields = array('holiday_hours', 'popular_dishes', 'restaurant_policies', 'parking_info');
    foreach ($textarea_fields as $field) {
        if (isset($_POST[$field])) {
            update_post_meta($post_id, '_' . $field, sanitize_textarea_field($_POST[$field]));
        }
    }

    // Save select fields
    if (isset($_POST['dress_code'])) {
        update_post_meta($post_id, '_dress_code', sanitize_text_field($_POST['dress_code']));
    }

    // Save menu data (JSON)
    if (isset($_POST['restaurant_menu'])) {
        $menu_data = json_decode(stripslashes($_POST['restaurant_menu']), true);
        if (json_last_error() === JSON_ERROR_NONE) {
            update_post_meta($post_id, '_restaurant_menu', $menu_data);
        }
    }
}
add_action('save_post', 'nearmenus_save_custom_fields');

/**
 * Add custom CSS for admin
 */
function nearmenus_admin_styles() {
    ?>
    <style>
    .form-table th {
        width: 200px;
        vertical-align: top;
        padding-top: 15px;
    }
    
    .form-table td {
        padding: 10px 0;
    }
    
    .form-table input[type="text"],
    .form-table input[type="email"],
    .form-table input[type="url"],
    .form-table input[type="number"],
    .form-table textarea,
    .form-table select {
        border: 1px solid #ddd;
        border-radius: 4px;
        padding: 8px;
    }
    
    .form-table textarea {
        resize: vertical;
    }
    
    .form-table .description {
        color: #666;
        font-style: italic;
        margin-top: 5px;
    }
    
    #menu-categories {
        border: 1px solid #e5e5e5;
        border-radius: 4px;
        padding: 10px;
        background: #f9f9f9;
        margin-top: 10px;
    }
    </style>
    <?php
}
add_action('admin_head', 'nearmenus_admin_styles');
```

## Frontend Display Functions

### Helper Functions for Displaying Custom Fields

```php
<?php
/**
 * Helper functions for displaying custom fields on frontend
 * Add to functions.php or inc/template-functions.php
 */

/**
 * Get restaurant rating with stars
 */
function nearmenus_get_restaurant_rating($post_id = null) {
    if (!$post_id) {
        $post_id = get_the_ID();
    }
    
    $rating = get_post_meta($post_id, '_restaurant_rating', true);
    $reviews_count = get_post_meta($post_id, '_restaurant_reviews_count', true);
    
    if (!$rating) {
        return '';
    }
    
    $output = '<div class="restaurant-rating">';
    $output .= '<div class="stars">';
    
    // Display stars (simplified - can be enhanced with CSS)
    for ($i = 1; $i <= 5; $i++) {
        if ($i <= $rating) {
            $output .= '<span class="star filled">★</span>';
        } elseif ($i - 0.5 <= $rating) {
            $output .= '<span class="star half">☆</span>';
        } else {
            $output .= '<span class="star empty">☆</span>';
        }
    }
    
    $output .= '</div>';
    $output .= '<span class="rating-value">' . esc_html($rating) . '</span>';
    
    if ($reviews_count) {
        $output .= '<span class="reviews-count">(' . esc_html($reviews_count) . ' reviews)</span>';
    }
    
    $output .= '</div>';
    
    return $output;
}

/**
 * Get restaurant contact information
 */
function nearmenus_get_contact_info($post_id = null) {
    if (!$post_id) {
        $post_id = get_the_ID();
    }
    
    $phone = get_post_meta($post_id, '_restaurant_phone', true);
    $email = get_post_meta($post_id, '_restaurant_email', true);
    $website = get_post_meta($post_id, '_restaurant_website', true);
    $address = get_post_meta($post_id, '_restaurant_address', true);
    
    $output = '<div class="restaurant-contact">';
    
    if ($phone) {
        $output .= '<div class="contact-item phone">';
        $output .= '<strong>Phone:</strong> ';
        $output .= '<a href="tel:' . esc_attr(str_replace(array(' ', '-', '(', ')'), '', $phone)) . '">' . esc_html($phone) . '</a>';
        $output .= '</div>';
    }
    
    if ($email) {
        $output .= '<div class="contact-item email">';
        $output .= '<strong>Email:</strong> ';
        $output .= '<a href="mailto:' . esc_attr($email) . '">' . esc_html($email) . '</a>';
        $output .= '</div>';
    }
    
    if ($website) {
        $output .= '<div class="contact-item website">';
        $output .= '<strong>Website:</strong> ';
        $output .= '<a href="' . esc_url($website) . '" target="_blank" rel="noopener">' . esc_html($website) . '</a>';
        $output .= '</div>';
    }
    
    if ($address) {
        $output .= '<div class="contact-item address">';
        $output .= '<strong>Address:</strong> ';
        $output .= '<span>' . esc_html($address) . '</span>';
        $output .= '</div>';
    }
    
    $output .= '</div>';
    
    return $output;
}

/**
 * Get restaurant hours
 */
function nearmenus_get_restaurant_hours($post_id = null) {
    if (!$post_id) {
        $post_id = get_the_ID();
    }
    
    $days = array(
        'monday' => 'Monday',
        'tuesday' => 'Tuesday', 
        'wednesday' => 'Wednesday',
        'thursday' => 'Thursday',
        'friday' => 'Friday',
        'saturday' => 'Saturday',
        'sunday' => 'Sunday'
    );
    
    $output = '<div class="restaurant-hours">';
    $output .= '<h4>Operating Hours</h4>';
    $output .= '<ul class="hours-list">';
    
    foreach ($days as $day_key => $day_name) {
        $hours = get_post_meta($post_id, "_hours_{$day_key}", true);
        if ($hours) {
            $output .= '<li><strong>' . $day_name . ':</strong> ' . esc_html($hours) . '</li>';
        }
    }
    
    $output .= '</ul>';
    
    $holiday_hours = get_post_meta($post_id, '_holiday_hours', true);
    if ($holiday_hours) {
        $output .= '<div class="holiday-hours">';
        $output .= '<h5>Holiday Hours</h5>';
        $output .= '<p>' . esc_html($holiday_hours) . '</p>';
        $output .= '</div>';
    }
    
    $output .= '</div>';
    
    return $output;
}

/**
 * Get restaurant menu
 */
function nearmenus_get_restaurant_menu($post_id = null) {
    if (!$post_id) {
        $post_id = get_the_ID();
    }
    
    $menu_data = get_post_meta($post_id, '_restaurant_menu', true);
    
    if (!$menu_data) {
        return '<p>Menu information not available.</p>';
    }
    
    $output = '<div class="restaurant-menu">';
    
    foreach ($menu_data as $category => $items) {
        if (empty($items)) continue;
        
        $output .= '<div class="menu-category">';
        $output .= '<h3 class="category-title">' . esc_html(ucfirst($category)) . '</h3>';
        $output .= '<div class="menu-items">';
        
        foreach ($items as $item) {
            $output .= '<div class="menu-item' . (isset($item['popular']) && $item['popular'] ? ' popular' : '') . '">';
            $output .= '<div class="item-header">';
            $output .= '<h4 class="item-name">' . esc_html($item['name']) . '</h4>';
            if (isset($item['price'])) {
                $output .= '<span class="item-price">$' . number_format($item['price'], 2) . '</span>';
            }
            $output .= '</div>';
            if (isset($item['description'])) {
                $output .= '<p class="item-description">' . esc_html($item['description']) . '</p>';
            }
            if (isset($item['popular']) && $item['popular']) {
                $output .= '<span class="popular-badge">Popular</span>';
            }
            $output .= '</div>';
        }
        
        $output .= '</div></div>';
    }
    
    $output .= '</div>';
    
    return $output;
}

/**
 * Get special offer if available
 */
function nearmenus_get_special_offer($post_id = null) {
    if (!$post_id) {
        $post_id = get_the_ID();
    }
    
    $special_offer = get_post_meta($post_id, '_restaurant_special_offer', true);
    
    if (!$special_offer) {
        return '';
    }
    
    $output = '<div class="special-offer">';
    $output .= '<h4>Special Offer</h4>';
    $output .= '<p>' . esc_html($special_offer) . '</p>';
    $output .= '</div>';
    
    return $output;
}

/**
 * Check if restaurant is currently open
 */
function nearmenus_is_restaurant_open($post_id = null) {
    if (!$post_id) {
        $post_id = get_the_ID();
    }
    
    return get_post_meta($post_id, '_restaurant_is_open', true) === '1';
}

/**
 * Check if restaurant offers delivery
 */
function nearmenus_has_delivery($post_id = null) {
    if (!$post_id) {
        $post_id = get_the_ID();
    }
    
    return get_post_meta($post_id, '_restaurant_has_delivery', true) === '1';
}
```

## Implementation Instructions

### For ACF Implementation:
1. Install Advanced Custom Fields Pro plugin
2. Add the ACF field group code to `functions.php`
3. The fields will automatically appear in post edit screen
4. Use ACF functions like `get_field()` in templates

### For Manual Implementation:
1. Create `inc/custom-fields.php` file with the manual code
2. Include it in `functions.php`: `require_once get_template_directory() . '/inc/custom-fields.php';`
3. Add helper functions to `functions.php` or create `inc/template-functions.php`
4. Update template files to display custom fields

### Testing Checklist:

- [ ] Custom fields appear in post edit screen
- [ ] All field types work correctly (text, number, checkbox, etc.)
- [ ] Data saves properly when post is updated
- [ ] Fields display correctly on frontend
- [ ] Menu system works for complex data
- [ ] Rating system displays properly
- [ ] Contact information shows correctly
- [ ] Operating hours display formatted properly
- [ ] Special offers appear when set
- [ ] Status indicators (open/closed, delivery) work

This implementation provides all the custom fields needed to replicate the React app's restaurant data structure in WordPress, with both plugin and manual options for maximum flexibility.