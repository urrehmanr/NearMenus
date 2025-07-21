/**
 * Admin JavaScript for NearMenus Theme
 * 
 * @package NearMenus
 */

(function($) {
    'use strict';

    // Initialize when DOM is ready
    $(document).ready(function() {
        initMenuBuilder();
        initImageUploads();
        initRatingInput();
        initQuickEdit();
        initDashboardWidget();
        initImportExport();
        initValidation();
    });

    /**
     * Menu Builder Functionality
     */
    function initMenuBuilder() {
        var menuContainer = $('.menu-builder');
        if (!menuContainer.length) return;

        // Add new category
        $(document).on('click', '#add-menu-category', function(e) {
            e.preventDefault();
            addMenuCategory();
        });

        // Add new menu item
        $(document).on('click', '.add-menu-item', function(e) {
            e.preventDefault();
            var categoryContainer = $(this).closest('.menu-category');
            addMenuItem(categoryContainer);
        });

        // Remove category
        $(document).on('click', '.remove-category', function(e) {
            e.preventDefault();
            if (confirm('Are you sure you want to remove this category and all its items?')) {
                $(this).closest('.menu-category').remove();
            }
        });

        // Remove menu item
        $(document).on('click', '.remove-menu-item', function(e) {
            e.preventDefault();
            if (confirm('Are you sure you want to remove this menu item?')) {
                $(this).closest('.menu-item').remove();
            }
        });

        // Category title edit
        $(document).on('blur', '.category-title-input', function() {
            var newTitle = $(this).val().trim();
            if (newTitle) {
                $(this).closest('.category-header').find('.category-title').text(newTitle);
            }
        });

        // Sortable categories and items
        if ($.fn.sortable) {
            menuContainer.sortable({
                handle: '.category-header',
                items: '.menu-category',
                placeholder: 'category-placeholder',
                update: function() {
                    updateMenuIndices();
                }
            });

            $('.menu-items').sortable({
                items: '.menu-item',
                placeholder: 'item-placeholder',
                update: function() {
                    updateMenuIndices();
                }
            });
        }
    }

    /**
     * Add new menu category
     */
    function addMenuCategory() {
        var categoryCount = $('.menu-category').length;
        var categoryHtml = `
            <div class="menu-category" data-index="${categoryCount}">
                <h4>Menu Category</h4>
                <table class="widefat">
                    <tr>
                        <th><label>Category Name</label></th>
                        <td>
                            <input type="text" name="restaurant_menu[${categoryCount}][name]" value="" class="regular-text" placeholder="Enter category name" />
                            <button type="button" class="button remove-category">Remove Category</button>
                        </td>
                    </tr>
                </table>
                <div class="menu-items">
                    <h5>Menu Items</h5>
                    <button type="button" class="button add-menu-item">Add Item</button>
                </div>
            </div>
        `;
        
        $('#menu-categories').append(categoryHtml);
        updateMenuIndices();
    }

    /**
     * Add new menu item
     */
    function addMenuItem(categoryContainer) {
        var categoryIndex = categoryContainer.data('index') || categoryContainer.index();
        var itemCount = categoryContainer.find('.menu-item').length;
        
        var itemHtml = `
            <div class="menu-item">
                <table class="widefat">
                    <tr>
                        <th>Item Name</th>
                        <td><input type="text" name="restaurant_menu[${categoryIndex}][items][${itemCount}][name]" value="" class="regular-text" placeholder="Enter item name" /></td>
                        <td rowspan="4"><button type="button" class="button remove-menu-item">Remove Item</button></td>
                    </tr>
                    <tr>
                        <th>Description</th>
                        <td><textarea name="restaurant_menu[${categoryIndex}][items][${itemCount}][description]" class="large-text" rows="2" placeholder="Item description"></textarea></td>
                    </tr>
                    <tr>
                        <th>Price</th>
                        <td><input type="number" step="0.01" name="restaurant_menu[${categoryIndex}][items][${itemCount}][price]" value="" class="regular-text" placeholder="0.00" /></td>
                    </tr>
                    <tr>
                        <th>Popular Item</th>
                        <td><input type="checkbox" name="restaurant_menu[${categoryIndex}][items][${itemCount}][popular]" value="1" /></td>
                    </tr>
                </table>
            </div>
        `;
        
        categoryContainer.find('.menu-items').append(itemHtml);
        updateMenuIndices();
    }

    /**
     * Update menu indices after sorting
     */
    function updateMenuIndices() {
        $('.menu-category').each(function(categoryIndex) {
            var categoryContainer = $(this);
            
            // Update category name input
            categoryContainer.find('input[name*="[name]"]').attr('name', `menu_categories[${categoryIndex}][name]`);
            
            // Update menu items
            categoryContainer.find('.menu-item').each(function(itemIndex) {
                var itemContainer = $(this);
                
                itemContainer.find('input, textarea, select').each(function() {
                    var name = $(this).attr('name');
                    if (name) {
                        // Replace the indices in the name attribute
                        var newName = name.replace(/menu_categories\[\d+\]\[items\]\[\d+\]/, `menu_categories[${categoryIndex}][items][${itemIndex}]`);
                        $(this).attr('name', newName);
                    }
                });
            });
        });
    }

    /**
     * Image Upload Functionality
     */
    function initImageUploads() {
        var mediaUploader;

        $(document).on('click', '.upload-image-btn', function(e) {
            e.preventDefault();
            
            var button = $(this);
            var targetInput = button.siblings('input[type="hidden"]');
            var previewContainer = button.siblings('.image-preview');

            // Create media uploader if it doesn't exist
            if (!mediaUploader) {
                mediaUploader = wp.media.frames.file_frame = wp.media({
                    title: 'Choose Image',
                    button: {
                        text: 'Choose Image'
                    },
                    multiple: false
                });

                mediaUploader.on('select', function() {
                    var attachment = mediaUploader.state().get('selection').first().toJSON();
                    targetInput.val(attachment.url);
                    updateImagePreview(previewContainer, attachment.url, button);
                });
            }

            mediaUploader.open();
        });

        // Remove image
        $(document).on('click', '.remove-image-btn', function(e) {
            e.preventDefault();
            
            var button = $(this);
            var targetInput = button.siblings('input[type="hidden"]');
            var previewContainer = button.siblings('.image-preview');
            var uploadBtn = button.siblings('.upload-image-btn');

            targetInput.val('');
            previewContainer.empty();
            uploadBtn.show();
            button.hide();
        });
    }

    /**
     * Update image preview
     */
    function updateImagePreview(container, imageUrl, uploadBtn) {
        container.html(`<img src="${imageUrl}" alt="Preview" class="menu-item-image-preview">`);
        uploadBtn.hide();
        uploadBtn.siblings('.remove-image-btn').show();
    }

    /**
     * Rating Input Enhancement
     */
    function initRatingInput() {
        $('.rating-field input').on('input', function() {
            var rating = parseFloat($(this).val());
            var starsContainer = $(this).siblings('.rating-stars');
            
            if (isNaN(rating) || rating < 0) {
                starsContainer.html('');
                return;
            }
            
            var stars = '';
            for (var i = 1; i <= 5; i++) {
                if (i <= rating) {
                    stars += '★';
                } else if (i - 0.5 <= rating) {
                    stars += '☆';
                } else {
                    stars += '☆';
                }
            }
            
            starsContainer.html(stars);
        });

        // Trigger on page load
        $('.rating-field input').trigger('input');
    }

    /**
     * Quick Edit Functionality
     */
    function initQuickEdit() {
        // Add custom fields to quick edit
        $('.editinline').on('click', function() {
            var postId = $(this).closest('tr').attr('id').replace('post-', '');
            var $editRow = $('#edit-' + postId);
            
            // Get current values from the row
            var rating = $('#post-' + postId).find('.rating-column').data('rating');
            var currentlyOpen = $('#post-' + postId).find('.status-column').data('open');
            
            // Set values in quick edit form
            if (rating) {
                $editRow.find('input[name="restaurant_rating"]').val(rating);
            }
            
            if (currentlyOpen) {
                $editRow.find('input[name="restaurant_currently_open"]').prop('checked', currentlyOpen === '1');
            }
        });
    }

    /**
     * Dashboard Widget Functionality
     */
    function initDashboardWidget() {
        // Refresh restaurant stats
        $('.refresh-stats').on('click', function(e) {
            e.preventDefault();
            
            var button = $(this);
            var widget = button.closest('.nearmenus-dashboard-widget');
            
            button.prop('disabled', true).text('Refreshing...');
            
            $.ajax({
                url: ajaxurl,
                type: 'POST',
                data: {
                    action: 'nearmenus_refresh_stats',
                    nonce: nearmenusAdmin.nonce
                },
                success: function(response) {
                    if (response.success) {
                        location.reload();
                    }
                },
                complete: function() {
                    button.prop('disabled', false).text('Refresh');
                }
            });
        });
    }

    /**
     * Import/Export Functionality
     */
    function initImportExport() {
        // File upload drag and drop
        $('.file-upload-area').on('dragover dragenter', function(e) {
            e.preventDefault();
            $(this).addClass('dragover');
        });

        $('.file-upload-area').on('dragleave', function(e) {
            e.preventDefault();
            $(this).removeClass('dragover');
        });

        $('.file-upload-area').on('drop', function(e) {
            e.preventDefault();
            $(this).removeClass('dragover');
            
            var files = e.originalEvent.dataTransfer.files;
            if (files.length > 0) {
                $(this).find('input[type="file"]')[0].files = files;
                $(this).find('.file-upload-label').text(files[0].name);
            }
        });

        // Export restaurants
        $('.export-restaurants').on('click', function(e) {
            e.preventDefault();
            
            var button = $(this);
            button.prop('disabled', true).text('Exporting...');
            
            window.location.href = ajaxurl + '?action=nearmenus_export_restaurants&nonce=' + nearmenusAdmin.nonce;
            
            setTimeout(function() {
                button.prop('disabled', false).text('Export Restaurants');
            }, 2000);
        });
    }

    /**
     * Form Validation
     */
    function initValidation() {
        // Email validation
        $('input[type="email"]').on('blur', function() {
            var email = $(this).val();
            var emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            
            if (email && !emailRegex.test(email)) {
                $(this).addClass('error-field');
                showFieldError($(this), 'Please enter a valid email address');
            } else {
                $(this).removeClass('error-field');
                hideFieldError($(this));
            }
        });

        // URL validation
        $('input[type="url"]').on('blur', function() {
            var url = $(this).val();
            var urlRegex = /^https?:\/\/.+/;
            
            if (url && !urlRegex.test(url)) {
                $(this).addClass('error-field');
                showFieldError($(this), 'Please enter a valid URL (starting with http:// or https://)');
            } else {
                $(this).removeClass('error-field');
                hideFieldError($(this));
            }
        });

        // Rating validation
        $('input[name*="rating"]').on('blur', function() {
            var rating = parseFloat($(this).val());
            
            if ($(this).val() && (isNaN(rating) || rating < 0 || rating > 5)) {
                $(this).addClass('error-field');
                showFieldError($(this), 'Rating must be between 0 and 5');
            } else {
                $(this).removeClass('error-field');
                hideFieldError($(this));
            }
        });

        // Phone validation
        $('input[type="tel"]').on('blur', function() {
            var phone = $(this).val();
            var phoneRegex = /^[\+]?[1-9][\d]{0,15}$/;
            
            if (phone && !phoneRegex.test(phone.replace(/[\s\-\(\)]/g, ''))) {
                $(this).addClass('error-field');
                showFieldError($(this), 'Please enter a valid phone number');
            } else {
                $(this).removeClass('error-field');
                hideFieldError($(this));
            }
        });
    }

    /**
     * Show field error
     */
    function showFieldError(field, message) {
        hideFieldError(field);
        field.after('<div class="field-error">' + message + '</div>');
    }

    /**
     * Hide field error
     */
    function hideFieldError(field) {
        field.siblings('.field-error').remove();
    }

    /**
     * Auto-save functionality
     */
    var autoSaveTimer;
    
    function initAutoSave() {
        $('input, textarea, select').on('change input', function() {
            clearTimeout(autoSaveTimer);
            autoSaveTimer = setTimeout(function() {
                if (typeof autosave !== 'undefined') {
                    autosave();
                }
            }, 5000);
        });
    }

    /**
     * Keyboard shortcuts
     */
    $(document).on('keydown', function(e) {
        // Ctrl/Cmd + S to save
        if ((e.ctrlKey || e.metaKey) && e.which === 83) {
            e.preventDefault();
            $('#publish, #save-post').click();
        }
        
        // Ctrl/Cmd + Shift + A to add menu item
        if ((e.ctrlKey || e.metaKey) && e.shiftKey && e.which === 65) {
            e.preventDefault();
            $('.add-menu-item:first').click();
        }
    });

    /**
     * Tooltips for help text
     */
    function initTooltips() {
        if ($.fn.tooltip) {
            $('[data-tooltip]').tooltip({
                content: function() {
                    return $(this).data('tooltip');
                },
                position: {
                    my: "left top+15",
                    at: "left bottom"
                }
            });
        }
    }

    // Initialize tooltips
    initTooltips();

    // Initialize auto-save
    initAutoSave();

})(jQuery);

// Global functions for PHP integration
window.nearmenusAdmin = {
    addMenuCategory: function() {
        jQuery('.add-menu-category').trigger('click');
    },
    
    addMenuItem: function(categoryIndex) {
        jQuery('.menu-category').eq(categoryIndex).find('.add-menu-item').trigger('click');
    },
    
    validateForm: function() {
        var isValid = true;
        
        // Check required fields
        jQuery('input[required], textarea[required], select[required]').each(function() {
            if (!jQuery(this).val().trim()) {
                jQuery(this).addClass('error-field');
                isValid = false;
            } else {
                jQuery(this).removeClass('error-field');
            }
        });
        
        // Check for validation errors
        if (jQuery('.error-field').length > 0) {
            isValid = false;
        }
        
        return isValid;
    }
};