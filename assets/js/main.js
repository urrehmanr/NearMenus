/**
 * NearMenus Theme JavaScript
 * Main functionality for the theme
 *
 * @package NearMenus
 * @since 1.0.0
 */

(function() {
    'use strict';
    
    // Global variables
    let isSearchModalOpen = false;
    let isMobileMenuOpen = false;
    
    // Initialize when DOM is ready
    document.addEventListener('DOMContentLoaded', function() {
        initTheme();
    });
    
    /**
     * Initialize theme functionality
     */
    function initTheme() {
        initMobileMenu();
        initSearchModal();
        initLocationMenu();
        initBackToTop();
        initShareButtons();
        initNewsletterForm();
        initLazyLoading();
        initSmoothScrolling();
        initTooltips();
        initImageGallery();
    }
    
    /**
     * Mobile Menu
     */
    function initMobileMenu() {
        const toggleBtn = document.querySelector('.mobile-menu-toggle');
        const mobileMenu = document.getElementById('mobile-menu');
        
        if (toggleBtn && mobileMenu) {
            toggleBtn.addEventListener('click', function() {
                toggleMobileMenu();
            });
            
            // Close on escape key
            document.addEventListener('keydown', function(e) {
                if (e.key === 'Escape' && isMobileMenuOpen) {
                    closeMobileMenu();
                }
            });
            
            // Close when clicking outside
            document.addEventListener('click', function(e) {
                if (isMobileMenuOpen && !e.target.closest('#mobile-menu') && !e.target.closest('.mobile-menu-toggle')) {
                    closeMobileMenu();
                }
            });
        }
    }
    
    function toggleMobileMenu() {
        const mobileMenu = document.getElementById('mobile-menu');
        const toggleBtn = document.querySelector('.mobile-menu-toggle');
        
        if (mobileMenu.classList.contains('hidden')) {
            openMobileMenu();
        } else {
            closeMobileMenu();
        }
    }
    
    function openMobileMenu() {
        const mobileMenu = document.getElementById('mobile-menu');
        const toggleBtn = document.querySelector('.mobile-menu-toggle');
        
        mobileMenu.classList.remove('hidden');
        isMobileMenuOpen = true;
        
        // Update button icon
        toggleBtn.innerHTML = `
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
            </svg>
        `;
        
        // Prevent body scroll
        document.body.style.overflow = 'hidden';
    }
    
    function closeMobileMenu() {
        const mobileMenu = document.getElementById('mobile-menu');
        const toggleBtn = document.querySelector('.mobile-menu-toggle');
        
        mobileMenu.classList.add('hidden');
        isMobileMenuOpen = false;
        
        // Restore button icon
        toggleBtn.innerHTML = `
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
            </svg>
        `;
        
        // Restore body scroll
        document.body.style.overflow = '';
    }
    
    /**
     * Search Modal
     */
    function initSearchModal() {
        const searchModal = document.getElementById('search-modal');
        const searchToggles = document.querySelectorAll('.search-toggle');
        
        if (searchModal) {
            // Open search modal
            searchToggles.forEach(toggle => {
                toggle.addEventListener('click', function(e) {
                    e.preventDefault();
                    openSearchModal();
                });
            });
            
            // Close on escape key
            document.addEventListener('keydown', function(e) {
                if (e.key === 'Escape' && isSearchModalOpen) {
                    closeSearchModal();
                }
            });
        }
    }
    
    function openSearchModal() {
        const searchModal = document.getElementById('search-modal');
        const searchInput = searchModal.querySelector('input[type="search"]');
        
        searchModal.classList.remove('hidden');
        isSearchModalOpen = true;
        
        // Focus search input
        setTimeout(() => {
            if (searchInput) {
                searchInput.focus();
            }
        }, 100);
        
        // Prevent body scroll
        document.body.style.overflow = 'hidden';
    }
    
    function closeSearchModal() {
        const searchModal = document.getElementById('search-modal');
        
        searchModal.classList.add('hidden');
        isSearchModalOpen = false;
        
        // Restore body scroll
        document.body.style.overflow = '';
    }
    
    /**
     * Location Menu
     */
    function initLocationMenu() {
        const locationToggle = document.querySelector('.location-toggle');
        const locationMenu = document.getElementById('location-menu');
        
        if (locationToggle && locationMenu) {
            locationToggle.addEventListener('click', function(e) {
                e.preventDefault();
                locationMenu.classList.toggle('hidden');
            });
            
            // Close when clicking outside
            document.addEventListener('click', function(e) {
                if (!e.target.closest('.location-toggle') && !e.target.closest('#location-menu')) {
                    locationMenu.classList.add('hidden');
                }
            });
        }
    }
    
    /**
     * Back to Top Button
     */
    function initBackToTop() {
        const backToTopBtn = document.getElementById('back-to-top');
        
        if (backToTopBtn) {
            // Show/hide button based on scroll position
            window.addEventListener('scroll', function() {
                if (window.pageYOffset > 300) {
                    backToTopBtn.style.display = 'block';
                } else {
                    backToTopBtn.style.display = 'none';
                }
            });
        }
    }
    
    /**
     * Share Buttons
     */
    function initShareButtons() {
        const shareButtons = document.querySelectorAll('.share-button');
        
        shareButtons.forEach(button => {
            button.addEventListener('click', function(e) {
                e.preventDefault();
                
                const title = this.dataset.title || document.title;
                const url = this.dataset.url || window.location.href;
                
                // Check if Web Share API is supported
                if (navigator.share) {
                    navigator.share({
                        title: title,
                        url: url
                    }).catch(err => console.log('Error sharing:', err));
                } else {
                    // Fallback to copy to clipboard
                    copyToClipboard(url);
                    showNotification('Link copied to clipboard!');
                }
            });
        });
    }
    
    /**
     * Newsletter Form
     */
    function initNewsletterForm() {
        const newsletterForms = document.querySelectorAll('form[onsubmit*="nearmenus_subscribeNewsletter"]');
        
        newsletterForms.forEach(form => {
            form.addEventListener('submit', function(e) {
                e.preventDefault();
                
                const emailInput = this.querySelector('input[type="email"]');
                const email = emailInput.value.trim();
                
                if (email) {
                    // Simulate newsletter subscription
                    showNotification('Thank you for subscribing!', 'success');
                    emailInput.value = '';
                } else {
                    showNotification('Please enter a valid email address.', 'error');
                }
            });
        });
    }
    
    /**
     * Lazy Loading for Images
     */
    function initLazyLoading() {
        const images = document.querySelectorAll('img[data-src]');
        
        if ('IntersectionObserver' in window) {
            const imageObserver = new IntersectionObserver((entries, observer) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        const img = entry.target;
                        img.src = img.dataset.src;
                        img.classList.remove('lazy');
                        imageObserver.unobserve(img);
                    }
                });
            });
            
            images.forEach(img => imageObserver.observe(img));
        } else {
            // Fallback for older browsers
            images.forEach(img => {
                img.src = img.dataset.src;
                img.classList.remove('lazy');
            });
        }
    }
    
    /**
     * Smooth Scrolling
     */
    function initSmoothScrolling() {
        const links = document.querySelectorAll('a[href^="#"]');
        
        links.forEach(link => {
            link.addEventListener('click', function(e) {
                const targetId = this.getAttribute('href');
                
                if (targetId !== '#' && targetId.length > 1) {
                    const targetElement = document.querySelector(targetId);
                    
                    if (targetElement) {
                        e.preventDefault();
                        
                        targetElement.scrollIntoView({
                            behavior: 'smooth',
                            block: 'start'
                        });
                    }
                }
            });
        });
    }
    
    /**
     * Tooltips
     */
    function initTooltips() {
        const tooltipElements = document.querySelectorAll('[title]');
        
        tooltipElements.forEach(element => {
            const title = element.getAttribute('title');
            
            if (title) {
                element.addEventListener('mouseenter', function() {
                    showTooltip(this, title);
                });
                
                element.addEventListener('mouseleave', function() {
                    hideTooltip();
                });
                
                // Remove title to prevent browser default tooltip
                element.removeAttribute('title');
                element.dataset.tooltip = title;
            }
        });
    }
    
    /**
     * Image Gallery
     */
    function initImageGallery() {
        const galleryImages = document.querySelectorAll('.restaurant-gallery img');
        
        galleryImages.forEach(img => {
            img.addEventListener('click', function() {
                openLightbox(this);
            });
        });
    }
    
    // Global functions
    window.nearmenus_toggleSearch = function() {
        if (isSearchModalOpen) {
            closeSearchModal();
        } else {
            openSearchModal();
        }
    };
    
    window.nearmenus_closeSearch = function(event) {
        if (event && event.target === event.currentTarget) {
            closeSearchModal();
        } else if (!event) {
            closeSearchModal();
        }
    };
    
    window.nearmenus_toggleMobileMenu = function() {
        toggleMobileMenu();
    };
    
    window.nearmenus_toggleLocationMenu = function() {
        const locationMenu = document.getElementById('location-menu');
        if (locationMenu) {
            locationMenu.classList.toggle('hidden');
        }
    };
    
    window.nearmenus_scrollToTop = function() {
        window.scrollTo({
            top: 0,
            behavior: 'smooth'
        });
    };
    
    window.nearmenus_quickSearch = function(term) {
        const searchInput = document.querySelector('#restaurant-search');
        if (searchInput) {
            searchInput.value = term;
            searchInput.form.submit();
        }
    };
    
    window.nearmenus_subscribeNewsletter = function(event) {
        event.preventDefault();
        const form = event.target;
        const emailInput = form.querySelector('input[type="email"]');
        const email = emailInput.value.trim();
        
        if (email) {
            showNotification('Thank you for subscribing!', 'success');
            emailInput.value = '';
        } else {
            showNotification('Please enter a valid email address.', 'error');
        }
    };
    
    // Utility functions
    function copyToClipboard(text) {
        if (navigator.clipboard) {
            navigator.clipboard.writeText(text);
        } else {
            // Fallback for older browsers
            const textArea = document.createElement('textarea');
            textArea.value = text;
            document.body.appendChild(textArea);
            textArea.focus();
            textArea.select();
            try {
                document.execCommand('copy');
            } catch (err) {
                console.error('Unable to copy to clipboard:', err);
            }
            document.body.removeChild(textArea);
        }
    }
    
    function showNotification(message, type = 'info') {
        // Create notification element
        const notification = document.createElement('div');
        notification.className = `fixed top-4 right-4 z-50 p-4 rounded-lg shadow-lg text-white max-w-sm ${
            type === 'success' ? 'bg-green-500' : 
            type === 'error' ? 'bg-red-500' : 
            'bg-blue-500'
        }`;
        notification.textContent = message;
        
        document.body.appendChild(notification);
        
        // Remove after 3 seconds
        setTimeout(() => {
            if (notification.parentNode) {
                notification.parentNode.removeChild(notification);
            }
        }, 3000);
    }
    
    function showTooltip(element, text) {
        const tooltip = document.createElement('div');
        tooltip.className = 'tooltip absolute bg-gray-900 text-white text-sm px-2 py-1 rounded z-50 pointer-events-none';
        tooltip.textContent = text;
        tooltip.id = 'tooltip';
        
        document.body.appendChild(tooltip);
        
        const rect = element.getBoundingClientRect();
        tooltip.style.left = rect.left + (rect.width / 2) - (tooltip.offsetWidth / 2) + 'px';
        tooltip.style.top = rect.top - tooltip.offsetHeight - 5 + 'px';
    }
    
    function hideTooltip() {
        const tooltip = document.getElementById('tooltip');
        if (tooltip) {
            tooltip.parentNode.removeChild(tooltip);
        }
    }
    
    function openLightbox(img) {
        const lightbox = document.createElement('div');
        lightbox.className = 'fixed inset-0 bg-black bg-opacity-90 z-50 flex items-center justify-center p-4';
        lightbox.innerHTML = `
            <div class="relative max-w-4xl max-h-full">
                <img src="${img.src}" alt="${img.alt}" class="max-w-full max-h-full object-contain">
                <button class="absolute top-4 right-4 text-white hover:text-gray-300 text-2xl">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>
        `;
        
        document.body.appendChild(lightbox);
        
        // Close on click
        lightbox.addEventListener('click', function(e) {
            if (e.target === lightbox || e.target.closest('button')) {
                document.body.removeChild(lightbox);
            }
        });
        
        // Close on escape
        document.addEventListener('keydown', function handleEscape(e) {
            if (e.key === 'Escape') {
                document.body.removeChild(lightbox);
                document.removeEventListener('keydown', handleEscape);
            }
        });
    }
    
})();