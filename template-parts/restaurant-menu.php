<?php
/**
 * Restaurant Menu Display Template Part
 * 
 * @package NearMenus
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

global $post;

// Get menu data
$menu_items = get_post_meta($post->ID, '_restaurant_menu', true);

if (!$menu_items || empty($menu_items)) {
    return;
}
?>

<div class="restaurant-menu bg-white rounded-lg shadow-md overflow-hidden">
    <div class="menu-header p-6 border-b border-gray-200 bg-gray-50">
        <h2 class="text-2xl font-bold text-gray-900">
            <svg class="w-6 h-6 inline mr-2 text-orange-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
            </svg>
            <?php _e('Menu', 'nearmenus'); ?>
        </h2>
        <p class="text-gray-600 mt-2"><?php _e('Explore our delicious offerings', 'nearmenus'); ?></p>
    </div>
    
    <!-- Menu Navigation (for categories) -->
    <?php if (count($menu_items) > 1): ?>
        <div class="menu-navigation p-4 border-b border-gray-200 bg-white sticky top-0 z-10">
            <div class="flex flex-wrap gap-2">
                <?php foreach ($menu_items as $category => $items): ?>
                    <button type="button" 
                            class="menu-nav-btn px-4 py-2 text-sm font-medium rounded-md border transition-colors
                                   hover:bg-primary hover:text-white hover:border-primary
                                   focus:outline-none focus:ring-2 focus:ring-primary focus:ring-offset-2"
                            data-category="<?php echo esc_attr(sanitize_title($category)); ?>">
                        <?php echo esc_html($category); ?>
                    </button>
                <?php endforeach; ?>
            </div>
        </div>
    <?php endif; ?>
    
    <!-- Menu Categories -->
    <div class="menu-content">
        <?php foreach ($menu_items as $category => $items): 
            $category_id = sanitize_title($category);
        ?>
            <div class="menu-category" id="category-<?php echo esc_attr($category_id); ?>">
                <div class="category-header p-6 bg-gray-50 border-b border-gray-200">
                    <h3 class="text-xl font-semibold text-gray-900"><?php echo esc_html($category); ?></h3>
                    <p class="text-gray-600 text-sm mt-1">
                        <?php echo sprintf(_n('%d item', '%d items', count($items), 'nearmenus'), count($items)); ?>
                    </p>
                </div>
                
                <div class="menu-items">
                    <?php foreach ($items as $index => $item): 
                        $is_popular = isset($item['popular']) && $item['popular'];
                        $is_vegetarian = isset($item['vegetarian']) && $item['vegetarian'];
                        $is_vegan = isset($item['vegan']) && $item['vegan'];
                        $is_gluten_free = isset($item['gluten_free']) && $item['gluten_free'];
                        $has_image = !empty($item['image']);
                    ?>
                        <div class="menu-item p-6 border-b border-gray-100 hover:bg-gray-50 transition-colors">
                            <div class="flex flex-col md:flex-row">
                                <!-- Item Info -->
                                <div class="item-info flex-1 mb-4 md:mb-0 md:mr-6">
                                    <div class="item-header flex items-start justify-between mb-2">
                                        <div class="item-title-area">
                                            <h4 class="text-lg font-semibold text-gray-900 flex items-center">
                                                <?php echo esc_html($item['name']); ?>
                                                
                                                <!-- Popular Badge -->
                                                <?php if ($is_popular): ?>
                                                    <span class="popular-badge ml-2 bg-orange-100 text-orange-800 px-2 py-1 rounded-full text-xs font-medium">
                                                        <svg class="w-3 h-3 inline mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                                                        </svg>
                                                        <?php _e('Popular', 'nearmenus'); ?>
                                                    </span>
                                                <?php endif; ?>
                                            </h4>
                                            
                                            <!-- Dietary Icons -->
                                            <?php if ($is_vegetarian || $is_vegan || $is_gluten_free): ?>
                                                <div class="dietary-icons flex gap-1 mt-1">
                                                    <?php if ($is_vegan): ?>
                                                        <span class="dietary-icon bg-green-100 text-green-800 px-2 py-1 rounded text-xs font-medium" title="<?php _e('Vegan', 'nearmenus'); ?>">
                                                            V
                                                        </span>
                                                    <?php elseif ($is_vegetarian): ?>
                                                        <span class="dietary-icon bg-green-100 text-green-800 px-2 py-1 rounded text-xs font-medium" title="<?php _e('Vegetarian', 'nearmenus'); ?>">
                                                            VG
                                                        </span>
                                                    <?php endif; ?>
                                                    
                                                    <?php if ($is_gluten_free): ?>
                                                        <span class="dietary-icon bg-blue-100 text-blue-800 px-2 py-1 rounded text-xs font-medium" title="<?php _e('Gluten Free', 'nearmenus'); ?>">
                                                            GF
                                                        </span>
                                                    <?php endif; ?>
                                                </div>
                                            <?php endif; ?>
                                        </div>
                                        
                                        <!-- Price -->
                                        <div class="item-price text-lg font-bold text-green-600">
                                            <?php if (!empty($item['price'])): ?>
                                                $<?php echo esc_html($item['price']); ?>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                    
                                    <!-- Description -->
                                    <?php if (!empty($item['description'])): ?>
                                        <p class="item-description text-gray-600 leading-relaxed">
                                            <?php echo esc_html($item['description']); ?>
                                        </p>
                                    <?php endif; ?>
                                    
                                    <!-- Ingredients/Allergens -->
                                    <?php if (!empty($item['ingredients'])): ?>
                                        <div class="item-ingredients mt-2">
                                            <span class="text-xs text-gray-500 uppercase tracking-wide font-semibold">
                                                <?php _e('Ingredients:', 'nearmenus'); ?>
                                            </span>
                                            <span class="text-xs text-gray-600 ml-1">
                                                <?php echo esc_html($item['ingredients']); ?>
                                            </span>
                                        </div>
                                    <?php endif; ?>
                                    
                                    <!-- Calories -->
                                    <?php if (!empty($item['calories'])): ?>
                                        <div class="item-calories mt-1">
                                            <span class="text-xs text-gray-500">
                                                <?php echo sprintf(__('%s calories', 'nearmenus'), esc_html($item['calories'])); ?>
                                            </span>
                                        </div>
                                    <?php endif; ?>
                                </div>
                                
                                <!-- Item Image -->
                                <?php if ($has_image): ?>
                                    <div class="item-image flex-shrink-0">
                                        <img src="<?php echo esc_url($item['image']); ?>" 
                                             alt="<?php echo esc_attr($item['name']); ?>"
                                             class="w-full md:w-24 h-24 object-cover rounded-lg">
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
    
    <!-- Menu Footer -->
    <div class="menu-footer p-6 bg-gray-50 border-t border-gray-200">
        <div class="text-center">
            <p class="text-sm text-gray-600 mb-4">
                <?php _e('Prices and availability may vary. Please contact the restaurant for the most current information.', 'nearmenus'); ?>
            </p>
            
            <!-- Dietary Legend -->
            <div class="dietary-legend flex flex-wrap justify-center gap-4 text-xs">
                <div class="legend-item flex items-center">
                    <span class="bg-green-100 text-green-800 px-2 py-1 rounded mr-2 font-medium">V</span>
                    <?php _e('Vegan', 'nearmenus'); ?>
                </div>
                <div class="legend-item flex items-center">
                    <span class="bg-green-100 text-green-800 px-2 py-1 rounded mr-2 font-medium">VG</span>
                    <?php _e('Vegetarian', 'nearmenus'); ?>
                </div>
                <div class="legend-item flex items-center">
                    <span class="bg-blue-100 text-blue-800 px-2 py-1 rounded mr-2 font-medium">GF</span>
                    <?php _e('Gluten Free', 'nearmenus'); ?>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Menu navigation functionality
    const menuNavBtns = document.querySelectorAll('.menu-nav-btn');
    const menuCategories = document.querySelectorAll('.menu-category');
    
    if (menuNavBtns.length > 0) {
        // Set first button as active by default
        menuNavBtns[0].classList.add('bg-primary', 'text-white', 'border-primary');
        
        menuNavBtns.forEach(btn => {
            btn.addEventListener('click', function() {
                const category = this.dataset.category;
                const targetCategory = document.getElementById('category-' + category);
                
                // Remove active state from all buttons
                menuNavBtns.forEach(navBtn => {
                    navBtn.classList.remove('bg-primary', 'text-white', 'border-primary');
                    navBtn.classList.add('border-gray-300', 'text-gray-700');
                });
                
                // Add active state to clicked button
                this.classList.add('bg-primary', 'text-white', 'border-primary');
                this.classList.remove('border-gray-300', 'text-gray-700');
                
                // Scroll to category
                if (targetCategory) {
                    const headerOffset = 120; // Account for sticky header and menu nav
                    const elementPosition = targetCategory.getBoundingClientRect().top;
                    const offsetPosition = elementPosition + window.pageYOffset - headerOffset;
                    
                    window.scrollTo({
                        top: offsetPosition,
                        behavior: 'smooth'
                    });
                }
            });
        });
        
        // Update active button on scroll
        let ticking = false;
        
        function updateActiveButton() {
            const scrollPosition = window.pageYOffset + 150;
            
            menuCategories.forEach((category, index) => {
                const categoryTop = category.offsetTop;
                const categoryBottom = categoryTop + category.offsetHeight;
                
                if (scrollPosition >= categoryTop && scrollPosition < categoryBottom) {
                    // Remove active state from all buttons
                    menuNavBtns.forEach(btn => {
                        btn.classList.remove('bg-primary', 'text-white', 'border-primary');
                        btn.classList.add('border-gray-300', 'text-gray-700');
                    });
                    
                    // Add active state to corresponding button
                    if (menuNavBtns[index]) {
                        menuNavBtns[index].classList.add('bg-primary', 'text-white', 'border-primary');
                        menuNavBtns[index].classList.remove('border-gray-300', 'text-gray-700');
                    }
                }
            });
            
            ticking = false;
        }
        
        window.addEventListener('scroll', function() {
            if (!ticking) {
                requestAnimationFrame(updateActiveButton);
                ticking = true;
            }
        });
    }
    
    // Add click handlers for menu items (for future order functionality)
    const menuItems = document.querySelectorAll('.menu-item');
    menuItems.forEach(item => {
        item.addEventListener('click', function() {
            // Add subtle click effect
            this.style.transform = 'scale(0.99)';
            setTimeout(() => {
                this.style.transform = 'scale(1)';
            }, 100);
            
            // Future: Add to cart or order functionality
            console.log('Menu item clicked:', this.querySelector('h4').textContent);
        });
    });
});
</script>