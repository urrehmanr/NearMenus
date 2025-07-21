<?php
/**
 * Custom Fields System for Restaurant Information
 *
 * @package NearMenus
 * @since 1.0.0
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Add restaurant meta boxes
 */
function nearmenus_add_meta_boxes() {
    add_meta_box(
        'restaurant_info',
        __('Restaurant Information', 'nearmenus'),
        'nearmenus_restaurant_info_callback',
        'post',
        'normal',
        'high'
    );
    
    add_meta_box(
        'restaurant_contact',
        __('Contact Information', 'nearmenus'),
        'nearmenus_restaurant_contact_callback',
        'post',
        'side',
        'default'
    );
    
    add_meta_box(
        'restaurant_hours',
        __('Operating Hours', 'nearmenus'),
        'nearmenus_restaurant_hours_callback',
        'post',
        'normal',
        'default'
    );
    
    add_meta_box(
        'restaurant_menu',
        __('Menu Information', 'nearmenus'),
        'nearmenus_restaurant_menu_callback',
        'post',
        'normal',
        'default'
    );
}
add_action('add_meta_boxes', 'nearmenus_add_meta_boxes');

/**
 * Restaurant Info Meta Box
 */
function nearmenus_restaurant_info_callback($post) {
    wp_nonce_field('nearmenus_restaurant_info_nonce', 'restaurant_info_nonce');
    
    $rating = get_post_meta($post->ID, '_restaurant_rating', true);
    $review_count = get_post_meta($post->ID, '_restaurant_review_count', true);
    $special_offer = get_post_meta($post->ID, '_restaurant_special_offer', true);
    $is_open = get_post_meta($post->ID, '_restaurant_is_open', true);
    $has_delivery = get_post_meta($post->ID, '_restaurant_has_delivery', true);
    $popular_dishes = get_post_meta($post->ID, '_restaurant_popular_dishes', true);
    
    ?>
    <table class="form-table">
        <tr>
            <th><label for="restaurant_rating"><?php _e('Rating', 'nearmenus'); ?></label></th>
            <td>
                <input type="number" id="restaurant_rating" name="restaurant_rating" 
                       value="<?php echo esc_attr($rating); ?>" step="0.1" min="0" max="5" class="small-text" />
                <p class="description"><?php _e('Restaurant rating out of 5', 'nearmenus'); ?></p>
            </td>
        </tr>
        <tr>
            <th><label for="restaurant_review_count"><?php _e('Review Count', 'nearmenus'); ?></label></th>
            <td>
                <input type="number" id="restaurant_review_count" name="restaurant_review_count" 
                       value="<?php echo esc_attr($review_count); ?>" min="0" class="small-text" />
                <p class="description"><?php _e('Total number of reviews', 'nearmenus'); ?></p>
            </td>
        </tr>
        <tr>
            <th><label for="restaurant_special_offer"><?php _e('Special Offer', 'nearmenus'); ?></label></th>
            <td>
                <textarea id="restaurant_special_offer" name="restaurant_special_offer" rows="3" cols="50"><?php echo esc_textarea($special_offer); ?></textarea>
                <p class="description"><?php _e('Current promotions or special offers', 'nearmenus'); ?></p>
            </td>
        </tr>
        <tr>
            <th><label for="restaurant_popular_dishes"><?php _e('Popular Dishes', 'nearmenus'); ?></label></th>
            <td>
                <textarea id="restaurant_popular_dishes" name="restaurant_popular_dishes" rows="3" cols="50" placeholder="<?php _e('Enter dishes separated by commas', 'nearmenus'); ?>"><?php echo esc_textarea($popular_dishes); ?></textarea>
                <p class="description"><?php _e('List popular dishes separated by commas', 'nearmenus'); ?></p>
            </td>
        </tr>
        <tr>
            <th><?php _e('Status', 'nearmenus'); ?></th>
            <td>
                <label for="restaurant_is_open">
                    <input type="checkbox" id="restaurant_is_open" name="restaurant_is_open" value="1" <?php checked($is_open, 1); ?> />
                    <?php _e('Currently Open', 'nearmenus'); ?>
                </label><br><br>
                <label for="restaurant_has_delivery">
                    <input type="checkbox" id="restaurant_has_delivery" name="restaurant_has_delivery" value="1" <?php checked($has_delivery, 1); ?> />
                    <?php _e('Offers Delivery', 'nearmenus'); ?>
                </label>
            </td>
        </tr>
    </table>
    <?php
}

/**
 * Contact Information Meta Box
 */
function nearmenus_restaurant_contact_callback($post) {
    wp_nonce_field('nearmenus_restaurant_contact_nonce', 'restaurant_contact_nonce');
    
    $phone = get_post_meta($post->ID, '_restaurant_phone', true);
    $email = get_post_meta($post->ID, '_restaurant_email', true);
    $website = get_post_meta($post->ID, '_restaurant_website', true);
    $address = get_post_meta($post->ID, '_restaurant_address', true);
    
    ?>
    <table class="form-table">
        <tr>
            <th><label for="restaurant_phone"><?php _e('Phone', 'nearmenus'); ?></label></th>
            <td><input type="tel" id="restaurant_phone" name="restaurant_phone" value="<?php echo esc_attr($phone); ?>" class="regular-text" /></td>
        </tr>
        <tr>
            <th><label for="restaurant_email"><?php _e('Email', 'nearmenus'); ?></label></th>
            <td><input type="email" id="restaurant_email" name="restaurant_email" value="<?php echo esc_attr($email); ?>" class="regular-text" /></td>
        </tr>
        <tr>
            <th><label for="restaurant_website"><?php _e('Website', 'nearmenus'); ?></label></th>
            <td><input type="url" id="restaurant_website" name="restaurant_website" value="<?php echo esc_attr($website); ?>" class="regular-text" /></td>
        </tr>
        <tr>
            <th><label for="restaurant_address"><?php _e('Address', 'nearmenus'); ?></label></th>
            <td><textarea id="restaurant_address" name="restaurant_address" rows="3" class="large-text"><?php echo esc_textarea($address); ?></textarea></td>
        </tr>
    </table>
    <?php
}

/**
 * Operating Hours Meta Box
 */
function nearmenus_restaurant_hours_callback($post) {
    wp_nonce_field('nearmenus_restaurant_hours_nonce', 'restaurant_hours_nonce');
    
    $days = array('monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday', 'sunday');
    $day_labels = array(
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
        <?php foreach ($days as $day) : 
            $hours = get_post_meta($post->ID, '_restaurant_hours_' . $day, true);
        ?>
        <tr>
            <th><label for="restaurant_hours_<?php echo $day; ?>"><?php echo $day_labels[$day]; ?></label></th>
            <td>
                <input type="text" id="restaurant_hours_<?php echo $day; ?>" name="restaurant_hours_<?php echo $day; ?>" 
                       value="<?php echo esc_attr($hours); ?>" class="regular-text" 
                       placeholder="<?php _e('e.g., 9:00 AM - 10:00 PM or Closed', 'nearmenus'); ?>" />
            </td>
        </tr>
        <?php endforeach; ?>
    </table>
    <?php
}

/**
 * Menu Information Meta Box
 */
function nearmenus_restaurant_menu_callback($post) {
    wp_nonce_field('nearmenus_restaurant_menu_nonce', 'restaurant_menu_nonce');
    
    $menu_data = get_post_meta($post->ID, '_restaurant_menu', true);
    if (!is_array($menu_data)) {
        $menu_data = array();
    }
    
    ?>
    <div id="restaurant-menu-container">
        <div id="menu-categories">
            <?php
            if (!empty($menu_data)) {
                foreach ($menu_data as $category_index => $category) {
                    nearmenus_render_menu_category($category_index, $category);
                }
            } else {
                nearmenus_render_menu_category(0, array());
            }
            ?>
        </div>
        <button type="button" id="add-menu-category" class="button"><?php _e('Add Category', 'nearmenus'); ?></button>
    </div>
    
    <script type="text/template" id="menu-category-template">
        <?php nearmenus_render_menu_category('{{INDEX}}', array()); ?>
    </script>
    
    <style>
    .menu-category {
        border: 1px solid #ddd;
        margin-bottom: 20px;
        padding: 15px;
        background: #f9f9f9;
    }
    .menu-item {
        border: 1px solid #ccc;
        margin-bottom: 10px;
        padding: 10px;
        background: white;
    }
    .remove-category, .remove-item {
        color: #a00;
    }
    </style>
    <?php
}

/**
 * Render menu category
 */
function nearmenus_render_menu_category($index, $category) {
    $category_name = isset($category['name']) ? $category['name'] : '';
    $items = isset($category['items']) ? $category['items'] : array(array());
    ?>
    <div class="menu-category" data-index="<?php echo $index; ?>">
        <h4><?php _e('Menu Category', 'nearmenus'); ?></h4>
        <table class="widefat">
            <tr>
                <th><label><?php _e('Category Name', 'nearmenus'); ?></label></th>
                <td>
                    <input type="text" name="restaurant_menu[<?php echo $index; ?>][name]" 
                           value="<?php echo esc_attr($category_name); ?>" class="regular-text" />
                    <button type="button" class="button remove-category"><?php _e('Remove Category', 'nearmenus'); ?></button>
                </td>
            </tr>
        </table>
        
        <div class="menu-items">
            <h5><?php _e('Menu Items', 'nearmenus'); ?></h5>
            <?php foreach ($items as $item_index => $item) : 
                nearmenus_render_menu_item($index, $item_index, $item);
            endforeach; ?>
            <button type="button" class="button add-menu-item"><?php _e('Add Item', 'nearmenus'); ?></button>
        </div>
    </div>
    <?php
}

/**
 * Render menu item
 */
function nearmenus_render_menu_item($category_index, $item_index, $item) {
    $name = isset($item['name']) ? $item['name'] : '';
    $description = isset($item['description']) ? $item['description'] : '';
    $price = isset($item['price']) ? $item['price'] : '';
    $popular = isset($item['popular']) ? $item['popular'] : false;
    ?>
    <div class="menu-item">
        <table class="widefat">
            <tr>
                <th><?php _e('Item Name', 'nearmenus'); ?></th>
                <td><input type="text" name="restaurant_menu[<?php echo $category_index; ?>][items][<?php echo $item_index; ?>][name]" value="<?php echo esc_attr($name); ?>" /></td>
            </tr>
            <tr>
                <th><?php _e('Description', 'nearmenus'); ?></th>
                <td><textarea name="restaurant_menu[<?php echo $category_index; ?>][items][<?php echo $item_index; ?>][description]" rows="2"><?php echo esc_textarea($description); ?></textarea></td>
            </tr>
            <tr>
                <th><?php _e('Price', 'nearmenus'); ?></th>
                <td>
                    <input type="number" name="restaurant_menu[<?php echo $category_index; ?>][items][<?php echo $item_index; ?>][price]" 
                           value="<?php echo esc_attr($price); ?>" step="0.01" min="0" />
                    <label>
                        <input type="checkbox" name="restaurant_menu[<?php echo $category_index; ?>][items][<?php echo $item_index; ?>][popular]" 
                               value="1" <?php checked($popular, 1); ?> />
                        <?php _e('Popular Item', 'nearmenus'); ?>
                    </label>
                    <button type="button" class="button remove-item"><?php _e('Remove', 'nearmenus'); ?></button>
                </td>
            </tr>
        </table>
    </div>
    <?php
}

/**
 * Save meta box data
 */
function nearmenus_save_restaurant_meta($post_id) {
    // Check nonces and permissions
    if (!isset($_POST['restaurant_info_nonce']) || !wp_verify_nonce($_POST['restaurant_info_nonce'], 'nearmenus_restaurant_info_nonce')) {
        return;
    }
    
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
        return;
    }
    
    if (!current_user_can('edit_post', $post_id)) {
        return;
    }
    
    // Save restaurant info
    $fields = array(
        'restaurant_rating' => 'floatval',
        'restaurant_review_count' => 'intval',
        'restaurant_special_offer' => 'sanitize_textarea_field',
        'restaurant_popular_dishes' => 'sanitize_textarea_field',
        'restaurant_is_open' => 'intval',
        'restaurant_has_delivery' => 'intval',
        'restaurant_phone' => 'sanitize_text_field',
        'restaurant_email' => 'sanitize_email',
        'restaurant_website' => 'esc_url_raw',
        'restaurant_address' => 'sanitize_textarea_field',
    );
    
    foreach ($fields as $field => $sanitize_func) {
        if (isset($_POST[$field])) {
            update_post_meta($post_id, '_' . $field, $sanitize_func($_POST[$field]));
        }
    }
    
    // Save operating hours
    $days = array('monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday', 'sunday');
    foreach ($days as $day) {
        $field = 'restaurant_hours_' . $day;
        if (isset($_POST[$field])) {
            update_post_meta($post_id, '_' . $field, sanitize_text_field($_POST[$field]));
        }
    }
    
    // Save menu data
    if (isset($_POST['restaurant_menu'])) {
        $menu_data = array();
        foreach ($_POST['restaurant_menu'] as $category) {
            if (!empty($category['name'])) {
                $clean_category = array(
                    'name' => sanitize_text_field($category['name']),
                    'items' => array()
                );
                
                if (isset($category['items'])) {
                    foreach ($category['items'] as $item) {
                        if (!empty($item['name'])) {
                            $clean_category['items'][] = array(
                                'name' => sanitize_text_field($item['name']),
                                'description' => sanitize_textarea_field($item['description']),
                                'price' => floatval($item['price']),
                                'popular' => isset($item['popular']) ? 1 : 0,
                            );
                        }
                    }
                }
                
                $menu_data[] = $clean_category;
            }
        }
        update_post_meta($post_id, '_restaurant_menu', $menu_data);
    }
}
add_action('save_post', 'nearmenus_save_restaurant_meta');