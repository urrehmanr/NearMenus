<?php
/**
 * Restaurant Gallery Template Part
 * 
 * @package NearMenus
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

global $post;

// Get gallery images from custom field or WordPress gallery
$gallery_images = get_post_meta($post->ID, '_restaurant_gallery', true);

// If no custom gallery, try to get from WordPress gallery in content
if (empty($gallery_images)) {
    $galleries = get_post_galleries($post, false);
    if (!empty($galleries)) {
        $gallery = $galleries[0];
        if (!empty($gallery['ids'])) {
            $gallery_images = explode(',', $gallery['ids']);
        }
    }
}

// If still no gallery, get all attached images
if (empty($gallery_images)) {
    $attachments = get_attached_media('image', $post->ID);
    if (!empty($attachments)) {
        $gallery_images = array_keys($attachments);
    }
}

// If we have featured image, make sure it's first
$featured_image = get_post_thumbnail_id($post->ID);
if ($featured_image && !empty($gallery_images)) {
    $gallery_images = array_unique(array_merge(array($featured_image), $gallery_images));
} elseif ($featured_image) {
    $gallery_images = array($featured_image);
}

if (empty($gallery_images)) {
    return;
}
?>

<div class="restaurant-gallery bg-white rounded-lg shadow-md overflow-hidden">
    <!-- Gallery Header -->
    <div class="gallery-header p-6 border-b border-gray-200">
        <h2 class="text-2xl font-bold text-gray-900 flex items-center">
            <svg class="w-6 h-6 mr-2 text-purple-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
            </svg>
            <?php _e('Photo Gallery', 'nearmenus'); ?>
        </h2>
        <p class="text-gray-600 mt-2">
            <?php echo sprintf(_n('%d photo', '%d photos', count($gallery_images), 'nearmenus'), count($gallery_images)); ?>
        </p>
    </div>
    
    <!-- Main Gallery Container -->
    <div class="gallery-container relative">
        <!-- Featured Image -->
        <?php if (!empty($gallery_images)): 
            $main_image_id = $gallery_images[0];
            $main_image = wp_get_attachment_image_src($main_image_id, 'restaurant-hero');
            $main_image_alt = get_post_meta($main_image_id, '_wp_attachment_image_alt', true);
        ?>
            <div class="featured-image relative">
                <img src="<?php echo esc_url($main_image[0]); ?>" 
                     alt="<?php echo esc_attr($main_image_alt ?: get_the_title()); ?>"
                     class="w-full h-96 object-cover cursor-pointer"
                     data-gallery-main
                     data-image-index="0">
                
                <!-- Image Counter -->
                <div class="absolute top-4 right-4 bg-black bg-opacity-70 text-white px-3 py-1 rounded-full text-sm">
                    <span class="current-image">1</span> / <?php echo count($gallery_images); ?>
                </div>
                
                <!-- Navigation Arrows -->
                <?php if (count($gallery_images) > 1): ?>
                    <button type="button" 
                            class="gallery-nav gallery-prev absolute left-4 top-1/2 transform -translate-y-1/2 bg-black bg-opacity-70 text-white p-2 rounded-full hover:bg-opacity-90 transition-opacity"
                            aria-label="<?php _e('Previous image', 'nearmenus'); ?>">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                        </svg>
                    </button>
                    
                    <button type="button" 
                            class="gallery-nav gallery-next absolute right-4 top-1/2 transform -translate-y-1/2 bg-black bg-opacity-70 text-white p-2 rounded-full hover:bg-opacity-90 transition-opacity"
                            aria-label="<?php _e('Next image', 'nearmenus'); ?>">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                        </svg>
                    </button>
                <?php endif; ?>
                
                <!-- Expand Button -->
                <button type="button" 
                        class="expand-gallery absolute bottom-4 right-4 bg-black bg-opacity-70 text-white px-4 py-2 rounded-full hover:bg-opacity-90 transition-opacity flex items-center"
                        data-gallery-expand>
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0zM10 7v3m0 0v3m0-3h3m-3 0H7"></path>
                    </svg>
                    <?php _e('View All', 'nearmenus'); ?>
                </button>
            </div>
        <?php endif; ?>
        
        <!-- Thumbnail Grid -->
        <?php if (count($gallery_images) > 1): ?>
            <div class="thumbnail-grid p-4">
                <div class="grid grid-cols-4 md:grid-cols-6 lg:grid-cols-8 gap-2">
                    <?php foreach ($gallery_images as $index => $image_id): 
                        $thumbnail = wp_get_attachment_image_src($image_id, 'restaurant-thumb');
                        $alt_text = get_post_meta($image_id, '_wp_attachment_image_alt', true);
                        $is_active = ($index === 0);
                    ?>
                        <div class="thumbnail-item cursor-pointer relative overflow-hidden rounded-lg <?php echo $is_active ? 'ring-2 ring-primary' : ''; ?>"
                             data-image-index="<?php echo $index; ?>">
                            <img src="<?php echo esc_url($thumbnail[0]); ?>" 
                                 alt="<?php echo esc_attr($alt_text ?: get_the_title()); ?>"
                                 class="w-full h-16 object-cover hover:scale-110 transition-transform duration-200">
                            
                            <!-- Active indicator -->
                            <?php if ($is_active): ?>
                                <div class="absolute inset-0 bg-primary bg-opacity-20"></div>
                            <?php endif; ?>
                        </div>
                    <?php endforeach; ?>
                </div>
                
                <!-- Show More Button (if more than 8 images) -->
                <?php if (count($gallery_images) > 8): ?>
                    <div class="text-center mt-4">
                        <button type="button" 
                                class="show-more-thumbs btn btn-outline btn-sm"
                                data-show-text="<?php _e('Show More Photos', 'nearmenus'); ?>"
                                data-hide-text="<?php _e('Show Less', 'nearmenus'); ?>">
                            <?php _e('Show More Photos', 'nearmenus'); ?>
                        </button>
                    </div>
                <?php endif; ?>
            </div>
        <?php endif; ?>
    </div>
</div>

<!-- Lightbox Modal -->
<div class="gallery-lightbox fixed inset-0 bg-black bg-opacity-90 z-50 hidden flex items-center justify-center">
    <div class="lightbox-container relative max-w-6xl max-h-full w-full h-full flex items-center justify-center p-4">
        <!-- Close Button -->
        <button type="button" 
                class="lightbox-close absolute top-4 right-4 text-white hover:text-gray-300 z-10"
                aria-label="<?php _e('Close gallery', 'nearmenus'); ?>">
            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
            </svg>
        </button>
        
        <!-- Lightbox Image -->
        <div class="lightbox-image-container relative">
            <img class="lightbox-image max-w-full max-h-full object-contain" src="" alt="">
            
            <!-- Image Info -->
            <div class="lightbox-info absolute bottom-0 left-0 right-0 bg-black bg-opacity-70 text-white p-4">
                <div class="flex justify-between items-center">
                    <div>
                        <h3 class="lightbox-title font-semibold"></h3>
                        <p class="lightbox-description text-sm text-gray-300"></p>
                    </div>
                    <div class="lightbox-counter text-sm">
                        <span class="current">1</span> / <span class="total"><?php echo count($gallery_images); ?></span>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Navigation -->
        <?php if (count($gallery_images) > 1): ?>
            <button type="button" 
                    class="lightbox-nav lightbox-prev absolute left-4 top-1/2 transform -translate-y-1/2 text-white hover:text-gray-300"
                    aria-label="<?php _e('Previous image', 'nearmenus'); ?>">
                <svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                </svg>
            </button>
            
            <button type="button" 
                    class="lightbox-nav lightbox-next absolute right-4 top-1/2 transform -translate-y-1/2 text-white hover:text-gray-300"
                    aria-label="<?php _e('Next image', 'nearmenus'); ?>">
                <svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                </svg>
            </button>
        <?php endif; ?>
        
        <!-- Thumbnail Strip -->
        <div class="lightbox-thumbnails absolute bottom-20 left-0 right-0 flex justify-center">
            <div class="flex gap-2 overflow-x-auto max-w-full px-4">
                <?php foreach ($gallery_images as $index => $image_id): 
                    $thumbnail = wp_get_attachment_image_src($image_id, 'restaurant-thumb');
                ?>
                    <button type="button" 
                            class="lightbox-thumb flex-shrink-0 w-16 h-16 rounded overflow-hidden border-2 border-transparent hover:border-white <?php echo $index === 0 ? 'border-white' : ''; ?>"
                            data-image-index="<?php echo $index; ?>">
                        <img src="<?php echo esc_url($thumbnail[0]); ?>" 
                             alt="" 
                             class="w-full h-full object-cover">
                    </button>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
</div>

<!-- Gallery Data (for JavaScript) -->
<script type="application/json" id="gallery-data">
<?php
$gallery_data = array();
foreach ($gallery_images as $index => $image_id) {
    $full_image = wp_get_attachment_image_src($image_id, 'full');
    $attachment = get_post($image_id);
    
    $gallery_data[] = array(
        'id' => $image_id,
        'url' => $full_image[0],
        'width' => $full_image[1],
        'height' => $full_image[2],
        'title' => $attachment->post_title,
        'description' => $attachment->post_content,
        'alt' => get_post_meta($image_id, '_wp_attachment_image_alt', true)
    );
}
echo wp_json_encode($gallery_data);
?>
</script>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const galleryData = JSON.parse(document.getElementById('gallery-data').textContent);
    let currentImageIndex = 0;
    
    // Elements
    const mainImage = document.querySelector('[data-gallery-main]');
    const thumbnails = document.querySelectorAll('.thumbnail-item');
    const lightbox = document.querySelector('.gallery-lightbox');
    const lightboxImage = document.querySelector('.lightbox-image');
    const lightboxTitle = document.querySelector('.lightbox-title');
    const lightboxDescription = document.querySelector('.lightbox-description');
    const lightboxCounter = document.querySelector('.lightbox-counter');
    const lightboxThumbs = document.querySelectorAll('.lightbox-thumb');
    
    // Navigation
    const galleryPrev = document.querySelector('.gallery-prev');
    const galleryNext = document.querySelector('.gallery-next');
    const lightboxPrev = document.querySelector('.lightbox-prev');
    const lightboxNext = document.querySelector('.lightbox-next');
    
    // Controls
    const expandButton = document.querySelector('[data-gallery-expand]');
    const closeButton = document.querySelector('.lightbox-close');
    const currentCounter = document.querySelector('.current-image');
    
    // Update main image
    function updateMainImage(index) {
        currentImageIndex = index;
        const imageData = galleryData[index];
        
        if (mainImage) {
            const mainImageSrc = imageData.url.replace('/uploads/', '/uploads/') // Ensure full size
                .replace('-1200x600', '') // Remove size suffix if present
                .replace('-600x400', '');
            
            mainImage.src = mainImageSrc;
            mainImage.alt = imageData.alt || imageData.title;
        }
        
        // Update thumbnails
        thumbnails.forEach((thumb, i) => {
            thumb.classList.toggle('ring-2', i === index);
            thumb.classList.toggle('ring-primary', i === index);
        });
        
        // Update counter
        if (currentCounter) {
            currentCounter.textContent = index + 1;
        }
    }
    
    // Update lightbox
    function updateLightbox(index) {
        const imageData = galleryData[index];
        
        lightboxImage.src = imageData.url;
        lightboxImage.alt = imageData.alt || imageData.title;
        lightboxTitle.textContent = imageData.title || '<?php echo esc_js(get_the_title()); ?>';
        lightboxDescription.textContent = imageData.description || '';
        
        // Update counter
        lightboxCounter.querySelector('.current').textContent = index + 1;
        
        // Update thumbnails
        lightboxThumbs.forEach((thumb, i) => {
            thumb.classList.toggle('border-white', i === index);
        });
    }
    
    // Navigation functions
    function showPrevious() {
        const newIndex = currentImageIndex > 0 ? currentImageIndex - 1 : galleryData.length - 1;
        updateMainImage(newIndex);
        if (lightbox && !lightbox.classList.contains('hidden')) {
            updateLightbox(newIndex);
        }
    }
    
    function showNext() {
        const newIndex = currentImageIndex < galleryData.length - 1 ? currentImageIndex + 1 : 0;
        updateMainImage(newIndex);
        if (lightbox && !lightbox.classList.contains('hidden')) {
            updateLightbox(newIndex);
        }
    }
    
    // Event listeners
    thumbnails.forEach((thumb, index) => {
        thumb.addEventListener('click', () => {
            updateMainImage(index);
        });
    });
    
    if (galleryPrev) {
        galleryPrev.addEventListener('click', showPrevious);
    }
    
    if (galleryNext) {
        galleryNext.addEventListener('click', showNext);
    }
    
    // Lightbox events
    if (expandButton) {
        expandButton.addEventListener('click', () => {
            lightbox.classList.remove('hidden');
            updateLightbox(currentImageIndex);
            document.body.style.overflow = 'hidden';
        });
    }
    
    if (mainImage) {
        mainImage.addEventListener('click', () => {
            lightbox.classList.remove('hidden');
            updateLightbox(currentImageIndex);
            document.body.style.overflow = 'hidden';
        });
    }
    
    if (closeButton) {
        closeButton.addEventListener('click', () => {
            lightbox.classList.add('hidden');
            document.body.style.overflow = '';
        });
    }
    
    if (lightboxPrev) {
        lightboxPrev.addEventListener('click', () => {
            showPrevious();
            updateLightbox(currentImageIndex);
        });
    }
    
    if (lightboxNext) {
        lightboxNext.addEventListener('click', () => {
            showNext();
            updateLightbox(currentImageIndex);
        });
    }
    
    lightboxThumbs.forEach((thumb, index) => {
        thumb.addEventListener('click', () => {
            updateMainImage(index);
            updateLightbox(index);
        });
    });
    
    // Keyboard navigation
    document.addEventListener('keydown', (e) => {
        if (!lightbox.classList.contains('hidden')) {
            switch(e.key) {
                case 'Escape':
                    lightbox.classList.add('hidden');
                    document.body.style.overflow = '';
                    break;
                case 'ArrowLeft':
                    showPrevious();
                    updateLightbox(currentImageIndex);
                    break;
                case 'ArrowRight':
                    showNext();
                    updateLightbox(currentImageIndex);
                    break;
            }
        } else {
            // Gallery navigation when not in lightbox
            switch(e.key) {
                case 'ArrowLeft':
                    showPrevious();
                    break;
                case 'ArrowRight':
                    showNext();
                    break;
            }
        }
    });
    
    // Close lightbox on background click
    lightbox.addEventListener('click', (e) => {
        if (e.target === lightbox) {
            lightbox.classList.add('hidden');
            document.body.style.overflow = '';
        }
    });
    
    // Show more thumbnails functionality
    const showMoreBtn = document.querySelector('.show-more-thumbs');
    if (showMoreBtn) {
        showMoreBtn.addEventListener('click', function() {
            const grid = document.querySelector('.thumbnail-grid .grid');
            const isExpanded = grid.classList.contains('grid-cols-12');
            
            if (isExpanded) {
                grid.classList.remove('md:grid-cols-12', 'lg:grid-cols-12');
                grid.classList.add('md:grid-cols-6', 'lg:grid-cols-8');
                this.textContent = this.dataset.showText;
            } else {
                grid.classList.remove('md:grid-cols-6', 'lg:grid-cols-8');
                grid.classList.add('md:grid-cols-12', 'lg:grid-cols-12');
                this.textContent = this.dataset.hideText;
            }
        });
    }
});
</script>