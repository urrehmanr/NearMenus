<?php
/**
 * Filter Controls Template Part
 * 
 * @package NearMenus
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

// Get current filter values
$selected_cuisine = isset($_GET['cuisine']) ? sanitize_text_field($_GET['cuisine']) : '';
$selected_location = isset($_GET['location']) ? sanitize_text_field($_GET['location']) : '';
$selected_price = isset($_GET['price_range']) ? sanitize_text_field($_GET['price_range']) : '';
$selected_features = isset($_GET['features']) ? array_map('sanitize_text_field', (array) $_GET['features']) : array();
$min_rating = isset($_GET['min_rating']) ? floatval($_GET['min_rating']) : 0;
$sort_by = isset($_GET['sort']) ? sanitize_text_field($_GET['sort']) : get_theme_mod('nearmenus_default_sort', 'rating');
?>

<div class="filter-controls bg-white border border-gray-200 rounded-lg p-4 mb-6">
    <form method="GET" action="" class="filter-form" id="restaurant-filters">
        <!-- Preserve search query -->
        <?php if (isset($_GET['s']) && !empty($_GET['s'])): ?>
            <input type="hidden" name="s" value="<?php echo esc_attr($_GET['s']); ?>">
        <?php endif; ?>
        
        <div class="filter-row flex flex-wrap gap-4 items-end">
            <!-- Cuisine Filter -->
            <div class="filter-group">
                <label for="cuisine-filter" class="block text-sm font-medium text-gray-700 mb-1">
                    <?php _e('Cuisine', 'nearmenus'); ?>
                </label>
                <select name="cuisine" id="cuisine-filter" class="form-select border-gray-300 rounded-md">
                    <option value=""><?php _e('All Cuisines', 'nearmenus'); ?></option>
                    <?php
                    $cuisines = get_terms(array(
                        'taxonomy' => 'cuisine',
                        'hide_empty' => true,
                        'orderby' => 'name',
                        'order' => 'ASC'
                    ));
                    
                    foreach ($cuisines as $cuisine):
                    ?>
                        <option value="<?php echo esc_attr($cuisine->slug); ?>" <?php selected($selected_cuisine, $cuisine->slug); ?>>
                            <?php echo esc_html($cuisine->name); ?> (<?php echo $cuisine->count; ?>)
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            
            <!-- Location Filter -->
            <div class="filter-group">
                <label for="location-filter" class="block text-sm font-medium text-gray-700 mb-1">
                    <?php _e('Location', 'nearmenus'); ?>
                </label>
                <select name="location" id="location-filter" class="form-select border-gray-300 rounded-md">
                    <option value=""><?php _e('All Locations', 'nearmenus'); ?></option>
                    <?php
                    $locations = get_terms(array(
                        'taxonomy' => 'location',
                        'hide_empty' => true,
                        'orderby' => 'name',
                        'order' => 'ASC'
                    ));
                    
                    foreach ($locations as $location):
                    ?>
                        <option value="<?php echo esc_attr($location->slug); ?>" <?php selected($selected_location, $location->slug); ?>>
                            <?php echo esc_html($location->name); ?> (<?php echo $location->count; ?>)
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            
            <!-- Price Range Filter -->
            <div class="filter-group">
                <label for="price-filter" class="block text-sm font-medium text-gray-700 mb-1">
                    <?php _e('Price Range', 'nearmenus'); ?>
                </label>
                <select name="price_range" id="price-filter" class="form-select border-gray-300 rounded-md">
                    <option value=""><?php _e('Any Price', 'nearmenus'); ?></option>
                    <?php
                    $price_ranges = get_terms(array(
                        'taxonomy' => 'price-range',
                        'hide_empty' => true,
                        'orderby' => 'term_order',
                        'order' => 'ASC'
                    ));
                    
                    foreach ($price_ranges as $price):
                    ?>
                        <option value="<?php echo esc_attr($price->slug); ?>" <?php selected($selected_price, $price->slug); ?>>
                            <?php echo esc_html($price->name); ?> (<?php echo $price->count; ?>)
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            
            <!-- Rating Filter -->
            <div class="filter-group">
                <label for="rating-filter" class="block text-sm font-medium text-gray-700 mb-1">
                    <?php _e('Minimum Rating', 'nearmenus'); ?>
                </label>
                <select name="min_rating" id="rating-filter" class="form-select border-gray-300 rounded-md">
                    <option value=""><?php _e('Any Rating', 'nearmenus'); ?></option>
                    <option value="4" <?php selected($min_rating, 4); ?>>4+ <?php _e('Stars', 'nearmenus'); ?></option>
                    <option value="3.5" <?php selected($min_rating, 3.5); ?>>3.5+ <?php _e('Stars', 'nearmenus'); ?></option>
                    <option value="3" <?php selected($min_rating, 3); ?>>3+ <?php _e('Stars', 'nearmenus'); ?></option>
                    <option value="2.5" <?php selected($min_rating, 2.5); ?>>2.5+ <?php _e('Stars', 'nearmenus'); ?></option>
                </select>
            </div>
            
            <!-- Sort Filter -->
            <div class="filter-group">
                <label for="sort-filter" class="block text-sm font-medium text-gray-700 mb-1">
                    <?php _e('Sort By', 'nearmenus'); ?>
                </label>
                <select name="sort" id="sort-filter" class="form-select border-gray-300 rounded-md">
                    <option value="rating" <?php selected($sort_by, 'rating'); ?>><?php _e('Highest Rated', 'nearmenus'); ?></option>
                    <option value="name" <?php selected($sort_by, 'name'); ?>><?php _e('Name (A-Z)', 'nearmenus'); ?></option>
                    <option value="newest" <?php selected($sort_by, 'newest'); ?>><?php _e('Newest First', 'nearmenus'); ?></option>
                    <option value="reviews" <?php selected($sort_by, 'reviews'); ?>><?php _e('Most Reviews', 'nearmenus'); ?></option>
                </select>
            </div>
            
            <!-- Filter Actions -->
            <div class="filter-actions flex gap-2">
                <button type="submit" class="btn btn-primary">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.414A1 1 0 013 6.707V4z"></path>
                    </svg>
                    <?php _e('Apply Filters', 'nearmenus'); ?>
                </button>
                
                <a href="<?php echo esc_url(remove_query_arg(array('cuisine', 'location', 'price_range', 'features', 'min_rating', 'sort'))); ?>" 
                   class="btn btn-outline">
                    <?php _e('Clear All', 'nearmenus'); ?>
                </a>
            </div>
        </div>
        
        <!-- Features Filter (Checkboxes) -->
        <div class="features-filter mt-4">
            <label class="block text-sm font-medium text-gray-700 mb-2">
                <?php _e('Features', 'nearmenus'); ?>
            </label>
            <div class="features-grid grid grid-cols-2 md:grid-cols-4 lg:grid-cols-6 gap-2">
                <?php
                $features = get_terms(array(
                    'taxonomy' => 'features',
                    'hide_empty' => true,
                    'orderby' => 'count',
                    'order' => 'DESC'
                ));
                
                foreach ($features as $feature):
                    $feature_icon = get_term_meta($feature->term_id, '_feature_icon', true);
                    $is_checked = in_array($feature->slug, $selected_features);
                ?>
                    <label class="feature-checkbox flex items-center cursor-pointer">
                        <input type="checkbox" 
                               name="features[]" 
                               value="<?php echo esc_attr($feature->slug); ?>"
                               <?php checked($is_checked); ?>
                               class="sr-only">
                        <div class="feature-item flex items-center p-2 border rounded-md transition-colors
                                   <?php echo $is_checked ? 'bg-primary text-white border-primary' : 'bg-white border-gray-300 hover:border-primary'; ?>">
                            <?php if ($feature_icon): ?>
                                <span class="feature-icon mr-2"><?php echo $feature_icon; ?></span>
                            <?php endif; ?>
                            <span class="text-sm"><?php echo esc_html($feature->name); ?></span>
                        </div>
                    </label>
                <?php endforeach; ?>
            </div>
        </div>
        
        <!-- Mobile Toggle for Advanced Filters -->
        <div class="mobile-filter-toggle md:hidden mt-4">
            <button type="button" class="w-full btn btn-outline" id="toggle-advanced-filters">
                <span class="filter-toggle-text"><?php _e('Show Advanced Filters', 'nearmenus'); ?></span>
                <svg class="w-4 h-4 ml-2 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                </svg>
            </button>
        </div>
    </form>
</div>

<!-- Quick Filter Buttons -->
<div class="quick-filters mb-6">
    <div class="flex flex-wrap gap-2">
        <span class="text-sm font-medium text-gray-700 mr-3"><?php _e('Quick Filters:', 'nearmenus'); ?></span>
        
        <!-- Open Now -->
        <button type="button" class="quick-filter-btn btn btn-sm btn-outline" data-filter="open_now">
            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
            <?php _e('Open Now', 'nearmenus'); ?>
        </button>
        
        <!-- Delivery Available -->
        <button type="button" class="quick-filter-btn btn btn-sm btn-outline" data-filter="delivery">
            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
            </svg>
            <?php _e('Delivery', 'nearmenus'); ?>
        </button>
        
        <!-- Top Rated -->
        <button type="button" class="quick-filter-btn btn btn-sm btn-outline" data-filter="top_rated">
            <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
            </svg>
            <?php _e('Top Rated', 'nearmenus'); ?>
        </button>
        
        <!-- New Restaurants -->
        <button type="button" class="quick-filter-btn btn btn-sm btn-outline" data-filter="new">
            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"></path>
            </svg>
            <?php _e('New', 'nearmenus'); ?>
        </button>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Auto-submit form on filter change (if AJAX enabled)
    <?php if (get_theme_mod('nearmenus_ajax_search', true)): ?>
    const filterForm = document.getElementById('restaurant-filters');
    const filterInputs = filterForm.querySelectorAll('select, input[type="checkbox"]');
    
    filterInputs.forEach(input => {
        input.addEventListener('change', function() {
            // Add loading state
            document.body.classList.add('filtering');
            
            // Submit form
            filterForm.submit();
        });
    });
    <?php endif; ?>
    
    // Quick filter buttons
    const quickFilterBtns = document.querySelectorAll('.quick-filter-btn');
    quickFilterBtns.forEach(btn => {
        btn.addEventListener('click', function() {
            const filter = this.dataset.filter;
            
            // Toggle active state
            this.classList.toggle('btn-primary');
            this.classList.toggle('btn-outline');
            
            // Apply quick filter logic
            switch(filter) {
                case 'open_now':
                    // Add hidden input for open now filter
                    toggleHiddenInput('open_now', '1');
                    break;
                case 'delivery':
                    // Select delivery feature
                    toggleFeature('delivery');
                    break;
                case 'top_rated':
                    // Set minimum rating to 4
                    document.getElementById('rating-filter').value = '4';
                    break;
                case 'new':
                    // Sort by newest
                    document.getElementById('sort-filter').value = 'newest';
                    break;
            }
            
            // Auto-submit if enabled
            <?php if (get_theme_mod('nearmenus_ajax_search', true)): ?>
            filterForm.submit();
            <?php endif; ?>
        });
    });
    
    // Mobile filter toggle
    const mobileToggle = document.getElementById('toggle-advanced-filters');
    if (mobileToggle) {
        mobileToggle.addEventListener('click', function() {
            const filterRow = document.querySelector('.filter-row');
            const featuresFilter = document.querySelector('.features-filter');
            const toggleText = this.querySelector('.filter-toggle-text');
            const toggleIcon = this.querySelector('svg');
            
            if (filterRow.classList.contains('hidden')) {
                filterRow.classList.remove('hidden');
                featuresFilter.classList.remove('hidden');
                toggleText.textContent = '<?php _e('Hide Advanced Filters', 'nearmenus'); ?>';
                toggleIcon.style.transform = 'rotate(180deg)';
            } else {
                filterRow.classList.add('hidden');
                featuresFilter.classList.add('hidden');
                toggleText.textContent = '<?php _e('Show Advanced Filters', 'nearmenus'); ?>';
                toggleIcon.style.transform = 'rotate(0deg)';
            }
        });
    }
    
    function toggleHiddenInput(name, value) {
        let input = filterForm.querySelector(`input[name="${name}"]`);
        if (input) {
            input.remove();
        } else {
            input = document.createElement('input');
            input.type = 'hidden';
            input.name = name;
            input.value = value;
            filterForm.appendChild(input);
        }
    }
    
    function toggleFeature(featureSlug) {
        const checkbox = filterForm.querySelector(`input[value="${featureSlug}"]`);
        if (checkbox) {
            checkbox.checked = !checkbox.checked;
            checkbox.dispatchEvent(new Event('change'));
        }
    }
});
</script>