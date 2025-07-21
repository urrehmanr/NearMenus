<?php
/**
 * Advanced Search Form Template Part
 *
 * @package NearMenus
 * @since 1.0.0
 */
?>

<div class="search-form-container">
    <form role="search" method="get" action="<?php echo esc_url(home_url('/')); ?>" class="restaurant-search-form">
        
        <!-- Main Search Input -->
        <div class="search-input-group relative">
            <div class="relative">
                <input type="search" 
                       name="s" 
                       id="restaurant-search"
                       value="<?php echo esc_attr(get_search_query()); ?>" 
                       placeholder="<?php esc_attr_e('Search restaurants, cuisines, or dishes...', 'nearmenus'); ?>"
                       class="search-input w-full pl-12 pr-4 py-4 text-lg border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent"
                       autocomplete="off">
                
                <!-- Search Icon -->
                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                    <svg class="w-6 h-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                    </svg>
                </div>
                
                <!-- Clear Button -->
                <button type="button" 
                        id="clear-search"
                        class="absolute inset-y-0 right-0 pr-4 flex items-center text-gray-400 hover:text-gray-600 hidden">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>
            
            <!-- Search Suggestions Dropdown -->
            <div id="search-suggestions" class="absolute top-full left-0 right-0 bg-white border border-gray-300 rounded-lg shadow-lg mt-1 hidden z-50">
                <div class="suggestions-content max-h-60 overflow-y-auto"></div>
            </div>
        </div>
        
        <!-- Advanced Filters Toggle -->
        <div class="advanced-filters-toggle mt-4 text-center">
            <button type="button" 
                    id="toggle-filters"
                    class="text-primary hover:text-primary-dark font-medium text-sm flex items-center mx-auto">
                <span class="filter-toggle-text"><?php _e('Advanced Filters', 'nearmenus'); ?></span>
                <svg class="w-4 h-4 ml-1 transform transition-transform filter-chevron" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                </svg>
            </button>
        </div>
        
        <!-- Advanced Filters Panel -->
        <div id="advanced-filters" class="advanced-filters hidden mt-6 p-6 bg-gray-50 rounded-lg border">
            
            <div class="grid md:grid-cols-2 lg:grid-cols-4 gap-4">
                
                <!-- Cuisine Filter -->
                <div class="filter-group">
                    <label for="filter-cuisine" class="block text-sm font-medium text-gray-700 mb-2">
                        <?php _e('Cuisine Type', 'nearmenus'); ?>
                    </label>
                    <select name="cuisine" id="filter-cuisine" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent">
                        <option value=""><?php _e('All Cuisines', 'nearmenus'); ?></option>
                        <?php
                        $cuisines = get_terms(array(
                            'taxonomy' => 'cuisine',
                            'hide_empty' => true,
                            'orderby' => 'name'
                        ));
                        
                        $selected_cuisine = get_query_var('cuisine');
                        
                        if ($cuisines && !is_wp_error($cuisines)) :
                            foreach ($cuisines as $cuisine) :
                        ?>
                        <option value="<?php echo esc_attr($cuisine->slug); ?>" <?php selected($selected_cuisine, $cuisine->slug); ?>>
                            <?php echo esc_html($cuisine->name); ?> (<?php echo $cuisine->count; ?>)
                        </option>
                        <?php endforeach; endif; ?>
                    </select>
                </div>
                
                <!-- Location Filter -->
                <div class="filter-group">
                    <label for="filter-location" class="block text-sm font-medium text-gray-700 mb-2">
                        <?php _e('Location', 'nearmenus'); ?>
                    </label>
                    <select name="location" id="filter-location" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent">
                        <option value=""><?php _e('All Locations', 'nearmenus'); ?></option>
                        <?php
                        $locations = get_terms(array(
                            'taxonomy' => 'location',
                            'hide_empty' => true,
                            'orderby' => 'name'
                        ));
                        
                        $selected_location = get_query_var('location');
                        
                        if ($locations && !is_wp_error($locations)) :
                            foreach ($locations as $location) :
                        ?>
                        <option value="<?php echo esc_attr($location->slug); ?>" <?php selected($selected_location, $location->slug); ?>>
                            <?php echo esc_html($location->name); ?> (<?php echo $location->count; ?>)
                        </option>
                        <?php endforeach; endif; ?>
                    </select>
                </div>
                
                <!-- Price Range Filter -->
                <div class="filter-group">
                    <label for="filter-price" class="block text-sm font-medium text-gray-700 mb-2">
                        <?php _e('Price Range', 'nearmenus'); ?>
                    </label>
                    <select name="price_range" id="filter-price" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent">
                        <option value=""><?php _e('Any Price', 'nearmenus'); ?></option>
                        <?php
                        $price_ranges = get_terms(array(
                            'taxonomy' => 'price_range',
                            'hide_empty' => true,
                            'orderby' => 'term_order'
                        ));
                        
                        $selected_price = get_query_var('price_range');
                        
                        if ($price_ranges && !is_wp_error($price_ranges)) :
                            foreach ($price_ranges as $price_range) :
                        ?>
                        <option value="<?php echo esc_attr($price_range->slug); ?>" <?php selected($selected_price, $price_range->slug); ?>>
                            <?php echo esc_html($price_range->name); ?> (<?php echo $price_range->count; ?>)
                        </option>
                        <?php endforeach; endif; ?>
                    </select>
                </div>
                
                <!-- Rating Filter -->
                <div class="filter-group">
                    <label for="filter-rating" class="block text-sm font-medium text-gray-700 mb-2">
                        <?php _e('Minimum Rating', 'nearmenus'); ?>
                    </label>
                    <select name="min_rating" id="filter-rating" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent">
                        <option value=""><?php _e('Any Rating', 'nearmenus'); ?></option>
                        <option value="4.5" <?php selected(get_query_var('min_rating'), '4.5'); ?>><?php _e('4.5+ Stars', 'nearmenus'); ?></option>
                        <option value="4.0" <?php selected(get_query_var('min_rating'), '4.0'); ?>><?php _e('4.0+ Stars', 'nearmenus'); ?></option>
                        <option value="3.5" <?php selected(get_query_var('min_rating'), '3.5'); ?>><?php _e('3.5+ Stars', 'nearmenus'); ?></option>
                        <option value="3.0" <?php selected(get_query_var('min_rating'), '3.0'); ?>><?php _e('3.0+ Stars', 'nearmenus'); ?></option>
                    </select>
                </div>
                
            </div>
            
            <!-- Features Filter -->
            <div class="filter-group mt-4">
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    <?php _e('Features', 'nearmenus'); ?>
                </label>
                <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-6 gap-3">
                    <?php
                    $features = get_terms(array(
                        'taxonomy' => 'features',
                        'hide_empty' => true,
                        'orderby' => 'count',
                        'order' => 'DESC'
                    ));
                    
                    $selected_features = (array) get_query_var('features');
                    
                    if ($features && !is_wp_error($features)) :
                        foreach ($features as $feature) :
                    ?>
                    <label class="flex items-center text-sm">
                        <input type="checkbox" 
                               name="features[]" 
                               value="<?php echo esc_attr($feature->slug); ?>"
                               <?php checked(in_array($feature->slug, $selected_features)); ?>
                               class="mr-2 rounded border-gray-300 text-primary focus:ring-primary-500">
                        <span class="text-gray-700"><?php echo esc_html($feature->name); ?></span>
                    </label>
                    <?php endforeach; endif; ?>
                </div>
            </div>
            
            <!-- Filter Actions -->
            <div class="filter-actions flex items-center justify-between mt-6 pt-4 border-t border-gray-200">
                <button type="button" 
                        id="clear-filters"
                        class="text-gray-600 hover:text-gray-800 text-sm font-medium">
                    <?php _e('Clear All Filters', 'nearmenus'); ?>
                </button>
                
                <div class="flex items-center space-x-3">
                    <button type="button" 
                            id="close-filters"
                            class="text-gray-600 hover:text-gray-800 text-sm font-medium">
                        <?php _e('Close', 'nearmenus'); ?>
                    </button>
                    <button type="submit" class="btn-primary">
                        <?php _e('Apply Filters', 'nearmenus'); ?>
                    </button>
                </div>
            </div>
            
        </div>
        
        <!-- Search Button (Mobile) -->
        <div class="search-button mt-4 md:hidden">
            <button type="submit" class="w-full btn-primary py-3">
                <?php _e('Search Restaurants', 'nearmenus'); ?>
            </button>
        </div>
        
        <!-- Quick Search Suggestions -->
        <div class="quick-suggestions mt-4">
            <div class="flex flex-wrap gap-2 justify-center">
                <span class="text-sm text-gray-600 mr-2"><?php _e('Quick search:', 'nearmenus'); ?></span>
                <?php
                $quick_searches = array(
                    'pizza' => __('Pizza', 'nearmenus'),
                    'sushi' => __('Sushi', 'nearmenus'),
                    'italian' => __('Italian', 'nearmenus'),
                    'fast-food' => __('Fast Food', 'nearmenus'),
                    'fine-dining' => __('Fine Dining', 'nearmenus')
                );
                
                foreach ($quick_searches as $term => $label) :
                ?>
                <button type="button" 
                        class="quick-search-btn px-3 py-1 bg-gray-100 hover:bg-gray-200 text-gray-700 text-sm rounded-full transition-colors"
                        data-search="<?php echo esc_attr($label); ?>">
                    <?php echo esc_html($label); ?>
                </button>
                <?php endforeach; ?>
            </div>
        </div>
        
    </form>
</div>

<script>
// Enhanced search functionality
document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('restaurant-search');
    const clearBtn = document.getElementById('clear-search');
    const suggestionsDiv = document.getElementById('search-suggestions');
    const filtersToggle = document.getElementById('toggle-filters');
    const filtersPanel = document.getElementById('advanced-filters');
    const closeFiltersBtn = document.getElementById('close-filters');
    const clearFiltersBtn = document.getElementById('clear-filters');
    const quickSearchBtns = document.querySelectorAll('.quick-search-btn');
    
    // Show/hide clear button
    function toggleClearButton() {
        if (searchInput.value.length > 0) {
            clearBtn.classList.remove('hidden');
        } else {
            clearBtn.classList.add('hidden');
        }
    }
    
    // Clear search
    clearBtn?.addEventListener('click', function() {
        searchInput.value = '';
        toggleClearButton();
        hideSuggestions();
        searchInput.focus();
    });
    
    // Toggle filters
    filtersToggle?.addEventListener('click', function() {
        const isVisible = !filtersPanel.classList.contains('hidden');
        
        if (isVisible) {
            filtersPanel.classList.add('hidden');
            this.querySelector('.filter-toggle-text').textContent = '<?php esc_js_e('Advanced Filters', 'nearmenus'); ?>';
            this.querySelector('.filter-chevron').style.transform = 'rotate(0deg)';
        } else {
            filtersPanel.classList.remove('hidden');
            this.querySelector('.filter-toggle-text').textContent = '<?php esc_js_e('Hide Filters', 'nearmenus'); ?>';
            this.querySelector('.filter-chevron').style.transform = 'rotate(180deg)';
        }
    });
    
    // Close filters
    closeFiltersBtn?.addEventListener('click', function() {
        filtersPanel.classList.add('hidden');
        filtersToggle.querySelector('.filter-toggle-text').textContent = '<?php esc_js_e('Advanced Filters', 'nearmenus'); ?>';
        filtersToggle.querySelector('.filter-chevron').style.transform = 'rotate(0deg)';
    });
    
    // Clear all filters
    clearFiltersBtn?.addEventListener('click', function() {
        const form = document.querySelector('.restaurant-search-form');
        const selects = form.querySelectorAll('select');
        const checkboxes = form.querySelectorAll('input[type="checkbox"]');
        
        selects.forEach(select => select.value = '');
        checkboxes.forEach(checkbox => checkbox.checked = false);
    });
    
    // Quick search buttons
    quickSearchBtns.forEach(btn => {
        btn.addEventListener('click', function() {
            const searchTerm = this.dataset.search;
            searchInput.value = searchTerm;
            toggleClearButton();
        });
    });
    
    // Search input events
    searchInput.addEventListener('input', function() {
        toggleClearButton();
        // Add debounced search suggestions here
    });
    
    // Hide suggestions when clicking outside
    document.addEventListener('click', function(e) {
        if (!e.target.closest('.search-input-group')) {
            hideSuggestions();
        }
    });
    
    function hideSuggestions() {
        suggestionsDiv.classList.add('hidden');
    }
    
    // Initialize
    toggleClearButton();
});
</script>