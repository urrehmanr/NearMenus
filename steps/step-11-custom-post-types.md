# Step 11: Advanced Form Handling & Contact Features

## Overview
This step implements advanced form handling capabilities, contact forms, newsletter subscriptions, and user interaction features while maintaining performance and security. We'll create reusable form components with conditional asset loading.

## Objectives
- Implement advanced contact forms
- Add newsletter subscription functionality  
- Create form validation and security
- Implement conditional form asset loading
- Add AJAX form submissions
- Ensure accessibility and user experience

## Implementation

### 1. Form Handler Foundation

Create `inc/form-handler.php`:

```php
<?php
/**
 * Advanced Form Handler
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Initialize form handling system
 */
function gpress_init_form_handler() {
    // Conditional asset loading for forms
    add_action('wp_enqueue_scripts', 'gpress_conditional_form_assets');
    
    // Form submission handlers
    add_action('wp_ajax_gpress_contact_form', 'gpress_handle_contact_form');
    add_action('wp_ajax_nopriv_gpress_contact_form', 'gpress_handle_contact_form');
    add_action('wp_ajax_gpress_newsletter', 'gpress_handle_newsletter_subscription');
    add_action('wp_ajax_nopriv_gpress_newsletter', 'gpress_handle_newsletter_subscription');
    
    // Add form shortcodes
    add_shortcode('gpress_contact_form', 'gpress_contact_form_shortcode');
    add_shortcode('gpress_newsletter', 'gpress_newsletter_form_shortcode');
    
    // Security enhancements
    add_action('init', 'gpress_form_security_setup');
}
add_action('after_setup_theme', 'gpress_init_form_handler');

/**
 * Conditional form asset loading
 */
function gpress_conditional_form_assets() {
    global $post;
    
    $load_forms = false;
    
    // Check if forms are needed on this page
    if (is_page() && $post) {
        $content = $post->post_content;
        
        // Check for form shortcodes or blocks
        if (strpos($content, '[gpress_contact_form]') !== false || 
            strpos($content, '[gpress_newsletter]') !== false ||
            strpos($content, 'wp:gpress/contact-form') !== false ||
            has_block('core/contact-form') ||
            is_page_template('page-contact.html')) {
            $load_forms = true;
        }
    }
    
    // Load on contact page
    if (is_page('contact') || is_page_template('contact.html')) {
        $load_forms = true;
    }
    
    // Check for form widgets in sidebars
    if (is_active_widget(false, false, 'gpress_newsletter_widget')) {
        $load_forms = true;
    }
    
    if ($load_forms) {
        wp_enqueue_style(
            'gpress-forms',
            get_theme_file_uri('/assets/css/forms.css'),
            array('gpress-style'),
            GPRESS_VERSION
        );
        
        wp_enqueue_script(
            'gpress-forms',
            get_theme_file_uri('/assets/js/forms.js'),
            array('jquery'),
            GPRESS_VERSION,
            array(
                'strategy' => 'defer',
                'in_footer' => true
            )
        );
        
        // Localize script for AJAX
        wp_localize_script('gpress-forms', 'gpressAjax', array(
            'ajaxurl' => admin_url('admin-ajax.php'),
            'nonce' => wp_create_nonce('gpress_form_nonce'),
            'messages' => array(
                'sending' => __('Sending...', 'gpress'),
                'success' => __('Message sent successfully!', 'gpress'),
                'error' => __('Sorry, there was an error. Please try again.', 'gpress'),
                'required' => __('This field is required.', 'gpress'),
                'email' => __('Please enter a valid email address.', 'gpress')
            )
        ));
    }
}

/**
 * Contact form shortcode
 */
function gpress_contact_form_shortcode($atts) {
    $atts = shortcode_atts(array(
        'title' => __('Get in Touch', 'gpress'),
        'submit_text' => __('Send Message', 'gpress'),
        'show_phone' => 'true',
        'show_subject' => 'true'
    ), $atts);
    
    $form_id = uniqid('contact-form-');
    
    ob_start();
    ?>
    <div class="gpress-contact-form-wrapper">
        <?php if ($atts['title']): ?>
            <h3 class="form-title"><?php echo esc_html($atts['title']); ?></h3>
        <?php endif; ?>
        
        <form id="<?php echo esc_attr($form_id); ?>" class="gpress-contact-form" data-form-type="contact">
            <div class="form-row">
                <div class="form-field">
                    <label for="<?php echo esc_attr($form_id); ?>-name">
                        <?php _e('Name', 'gpress'); ?> <span class="required">*</span>
                    </label>
                    <input type="text" 
                           id="<?php echo esc_attr($form_id); ?>-name" 
                           name="name" 
                           required 
                           aria-required="true"
                           autocomplete="name">
                </div>
                
                <div class="form-field">
                    <label for="<?php echo esc_attr($form_id); ?>-email">
                        <?php _e('Email', 'gpress'); ?> <span class="required">*</span>
                    </label>
                    <input type="email" 
                           id="<?php echo esc_attr($form_id); ?>-email" 
                           name="email" 
                           required 
                           aria-required="true"
                           autocomplete="email">
                </div>
            </div>
            
            <?php if ($atts['show_phone'] === 'true'): ?>
            <div class="form-row">
                <div class="form-field">
                    <label for="<?php echo esc_attr($form_id); ?>-phone">
                        <?php _e('Phone', 'gpress'); ?>
                    </label>
                    <input type="tel" 
                           id="<?php echo esc_attr($form_id); ?>-phone" 
                           name="phone"
                           autocomplete="tel">
                </div>
            </div>
            <?php endif; ?>
            
            <?php if ($atts['show_subject'] === 'true'): ?>
            <div class="form-row">
                <div class="form-field">
                    <label for="<?php echo esc_attr($form_id); ?>-subject">
                        <?php _e('Subject', 'gpress'); ?> <span class="required">*</span>
                    </label>
                    <input type="text" 
                           id="<?php echo esc_attr($form_id); ?>-subject" 
                           name="subject" 
                           required 
                           aria-required="true">
                </div>
            </div>
            <?php endif; ?>
            
            <div class="form-row">
                <div class="form-field">
                    <label for="<?php echo esc_attr($form_id); ?>-message">
                        <?php _e('Message', 'gpress'); ?> <span class="required">*</span>
                    </label>
                    <textarea id="<?php echo esc_attr($form_id); ?>-message" 
                              name="message" 
                              rows="6" 
                              required 
                              aria-required="true"
                              placeholder="<?php esc_attr_e('How can we help you?', 'gpress'); ?>"></textarea>
                </div>
            </div>
            
            <div class="form-row">
                <div class="form-field">
                    <label class="checkbox-label">
                        <input type="checkbox" name="privacy_consent" required aria-required="true">
                        <span class="checkmark"></span>
                        <?php printf(
                            __('I agree to the %s and %s', 'gpress'),
                            '<a href="' . get_privacy_policy_url() . '" target="_blank">' . __('Privacy Policy', 'gpress') . '</a>',
                            '<a href="/terms" target="_blank">' . __('Terms of Service', 'gpress') . '</a>'
                        ); ?>
                    </label>
                </div>
            </div>
            
            <div class="form-actions">
                <button type="submit" class="submit-button">
                    <span class="button-text"><?php echo esc_html($atts['submit_text']); ?></span>
                    <span class="loading-spinner" aria-hidden="true"></span>
                </button>
            </div>
            
            <div class="form-messages" role="alert" aria-live="polite"></div>
            
            <?php wp_nonce_field('gpress_contact_form', 'contact_nonce'); ?>
        </form>
    </div>
    <?php
    
    return ob_get_clean();
}

/**
 * Newsletter form shortcode
 */
function gpress_newsletter_form_shortcode($atts) {
    $atts = shortcode_atts(array(
        'title' => __('Subscribe to Newsletter', 'gpress'),
        'description' => __('Get the latest updates and articles delivered to your inbox.', 'gpress'),
        'submit_text' => __('Subscribe', 'gpress'),
        'style' => 'default'
    ), $atts);
    
    $form_id = uniqid('newsletter-form-');
    
    ob_start();
    ?>
    <div class="gpress-newsletter-form-wrapper newsletter-style-<?php echo esc_attr($atts['style']); ?>">
        <?php if ($atts['title']): ?>
            <h4 class="newsletter-title"><?php echo esc_html($atts['title']); ?></h4>
        <?php endif; ?>
        
        <?php if ($atts['description']): ?>
            <p class="newsletter-description"><?php echo esc_html($atts['description']); ?></p>
        <?php endif; ?>
        
        <form id="<?php echo esc_attr($form_id); ?>" class="gpress-newsletter-form" data-form-type="newsletter">
            <div class="newsletter-input-group">
                <label for="<?php echo esc_attr($form_id); ?>-email" class="screen-reader-text">
                    <?php _e('Email Address', 'gpress'); ?>
                </label>
                <input type="email" 
                       id="<?php echo esc_attr($form_id); ?>-email" 
                       name="email" 
                       placeholder="<?php esc_attr_e('Enter your email...', 'gpress'); ?>"
                       required 
                       aria-required="true"
                       autocomplete="email">
                <button type="submit" class="newsletter-submit">
                    <span class="button-text"><?php echo esc_html($atts['submit_text']); ?></span>
                    <span class="loading-spinner" aria-hidden="true"></span>
                </button>
            </div>
            
            <div class="newsletter-consent">
                <label class="checkbox-label">
                    <input type="checkbox" name="newsletter_consent" required aria-required="true">
                    <span class="checkmark"></span>
                    <small><?php _e('I agree to receive newsletters and promotional emails', 'gpress'); ?></small>
                </label>
            </div>
            
            <div class="form-messages" role="alert" aria-live="polite"></div>
            
            <?php wp_nonce_field('gpress_newsletter', 'newsletter_nonce'); ?>
        </form>
    </div>
    <?php
    
    return ob_get_clean();
}

/**
 * Handle contact form submission
 */
function gpress_handle_contact_form() {
    // Verify nonce
    if (!wp_verify_nonce($_POST['contact_nonce'], 'gpress_contact_form')) {
        wp_die(__('Security check failed.', 'gpress'));
    }
    
    // Sanitize and validate input
    $name = sanitize_text_field($_POST['name']);
    $email = sanitize_email($_POST['email']);
    $phone = sanitize_text_field($_POST['phone'] ?? '');
    $subject = sanitize_text_field($_POST['subject'] ?? __('Contact Form Submission', 'gpress'));
    $message = sanitize_textarea_field($_POST['message']);
    $privacy_consent = isset($_POST['privacy_consent']);
    
    // Validation
    $errors = array();
    
    if (empty($name)) {
        $errors[] = __('Name is required.', 'gpress');
    }
    
    if (empty($email) || !is_email($email)) {
        $errors[] = __('Valid email is required.', 'gpress');
    }
    
    if (empty($message)) {
        $errors[] = __('Message is required.', 'gpress');
    }
    
    if (!$privacy_consent) {
        $errors[] = __('Privacy consent is required.', 'gpress');
    }
    
    if (!empty($errors)) {
        wp_send_json_error(array(
            'message' => implode(' ', $errors)
        ));
    }
    
    // Prepare email
    $admin_email = get_option('admin_email');
    $site_name = get_bloginfo('name');
    
    $email_subject = sprintf('[%s] %s', $site_name, $subject);
    $email_message = sprintf(
        "New contact form submission:\n\n" .
        "Name: %s\n" .
        "Email: %s\n" .
        "Phone: %s\n" .
        "Subject: %s\n\n" .
        "Message:\n%s\n\n" .
        "---\n" .
        "Submitted from: %s\n" .
        "IP: %s\n" .
        "User Agent: %s",
        $name,
        $email,
        $phone,
        $subject,
        $message,
        home_url(),
        $_SERVER['REMOTE_ADDR'] ?? '',
        $_SERVER['HTTP_USER_AGENT'] ?? ''
    );
    
    $headers = array(
        'Content-Type: text/plain; charset=UTF-8',
        'From: ' . $site_name . ' <' . $admin_email . '>',
        'Reply-To: ' . $name . ' <' . $email . '>'
    );
    
    // Send email
    $sent = wp_mail($admin_email, $email_subject, $email_message, $headers);
    
    if ($sent) {
        // Save to database for backup
        gpress_save_form_submission('contact', array(
            'name' => $name,
            'email' => $email,
            'phone' => $phone,
            'subject' => $subject,
            'message' => $message,
            'ip' => $_SERVER['REMOTE_ADDR'] ?? '',
            'user_agent' => $_SERVER['HTTP_USER_AGENT'] ?? ''
        ));
        
        wp_send_json_success(array(
            'message' => __('Thank you! Your message has been sent successfully.', 'gpress')
        ));
    } else {
        wp_send_json_error(array(
            'message' => __('Sorry, there was an error sending your message. Please try again.', 'gpress')
        ));
    }
}

/**
 * Handle newsletter subscription
 */
function gpress_handle_newsletter_subscription() {
    // Verify nonce
    if (!wp_verify_nonce($_POST['newsletter_nonce'], 'gpress_newsletter')) {
        wp_die(__('Security check failed.', 'gpress'));
    }
    
    $email = sanitize_email($_POST['email']);
    $consent = isset($_POST['newsletter_consent']);
    
    if (empty($email) || !is_email($email)) {
        wp_send_json_error(array(
            'message' => __('Please enter a valid email address.', 'gpress')
        ));
    }
    
    if (!$consent) {
        wp_send_json_error(array(
            'message' => __('Please agree to receive newsletters.', 'gpress')
        ));
    }
    
    // Check if already subscribed
    if (gpress_is_email_subscribed($email)) {
        wp_send_json_error(array(
            'message' => __('This email is already subscribed.', 'gpress')
        ));
    }
    
    // Save subscription
    $saved = gpress_save_newsletter_subscription($email);
    
    if ($saved) {
        // Send confirmation email
        gpress_send_newsletter_confirmation($email);
        
        wp_send_json_success(array(
            'message' => __('Thank you! Please check your email to confirm your subscription.', 'gpress')
        ));
    } else {
        wp_send_json_error(array(
            'message' => __('Sorry, there was an error. Please try again.', 'gpress')
        ));
    }
}

/**
 * Save form submission to database
 */
function gpress_save_form_submission($type, $data) {
    global $wpdb;
    
    $table_name = $wpdb->prefix . 'gpress_form_submissions';
    
    return $wpdb->insert(
        $table_name,
        array(
            'type' => $type,
            'data' => wp_json_encode($data),
            'ip_address' => $_SERVER['REMOTE_ADDR'] ?? '',
            'user_agent' => $_SERVER['HTTP_USER_AGENT'] ?? '',
            'submitted_at' => current_time('mysql')
        ),
        array('%s', '%s', '%s', '%s', '%s')
    );
}

/**
 * Check if email is subscribed
 */
function gpress_is_email_subscribed($email) {
    global $wpdb;
    
    $table_name = $wpdb->prefix . 'gpress_newsletter_subscribers';
    
    $count = $wpdb->get_var($wpdb->prepare(
        "SELECT COUNT(*) FROM $table_name WHERE email = %s AND status = 'active'",
        $email
    ));
    
    return $count > 0;
}

/**
 * Save newsletter subscription
 */
function gpress_save_newsletter_subscription($email) {
    global $wpdb;
    
    $table_name = $wpdb->prefix . 'gpress_newsletter_subscribers';
    $token = wp_generate_password(32, false);
    
    return $wpdb->insert(
        $table_name,
        array(
            'email' => $email,
            'status' => 'pending',
            'token' => $token,
            'subscribed_at' => current_time('mysql'),
            'ip_address' => $_SERVER['REMOTE_ADDR'] ?? ''
        ),
        array('%s', '%s', '%s', '%s', '%s')
    );
}

/**
 * Send newsletter confirmation email
 */
function gpress_send_newsletter_confirmation($email) {
    $site_name = get_bloginfo('name');
    $confirm_url = add_query_arg(array(
        'action' => 'confirm_newsletter',
        'email' => urlencode($email),
        'token' => gpress_get_subscriber_token($email)
    ), home_url());
    
    $subject = sprintf(__('[%s] Please confirm your subscription', 'gpress'), $site_name);
    $message = sprintf(
        __("Thank you for subscribing to %s!\n\nPlease click the link below to confirm your subscription:\n%s\n\nIf you didn't subscribe, you can safely ignore this email.", 'gpress'),
        $site_name,
        $confirm_url
    );
    
    wp_mail($email, $subject, $message);
}

/**
 * Get subscriber token
 */
function gpress_get_subscriber_token($email) {
    global $wpdb;
    
    $table_name = $wpdb->prefix . 'gpress_newsletter_subscribers';
    
    return $wpdb->get_var($wpdb->prepare(
        "SELECT token FROM $table_name WHERE email = %s ORDER BY subscribed_at DESC LIMIT 1",
        $email
    ));
}

/**
 * Form security setup
 */
function gpress_form_security_setup() {
    // Rate limiting for form submissions
    add_action('wp_ajax_gpress_contact_form', 'gpress_check_form_rate_limit', 1);
    add_action('wp_ajax_nopriv_gpress_contact_form', 'gpress_check_form_rate_limit', 1);
    add_action('wp_ajax_gpress_newsletter', 'gpress_check_form_rate_limit', 1);
    add_action('wp_ajax_nopriv_gpress_newsletter', 'gpress_check_form_rate_limit', 1);
}

/**
 * Check form submission rate limit
 */
function gpress_check_form_rate_limit() {
    $ip = $_SERVER['REMOTE_ADDR'] ?? '';
    $transient_key = 'gpress_form_rate_limit_' . md5($ip);
    $attempts = get_transient($transient_key) ?: 0;
    
    if ($attempts >= 5) {
        wp_send_json_error(array(
            'message' => __('Too many requests. Please try again later.', 'gpress')
        ));
    }
    
    set_transient($transient_key, $attempts + 1, HOUR_IN_SECONDS);
}

/**
 * Create database tables
 */
function gpress_create_form_tables() {
    global $wpdb;
    
    require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
    
    // Form submissions table
    $table_name = $wpdb->prefix . 'gpress_form_submissions';
    $sql = "CREATE TABLE $table_name (
        id int(11) NOT NULL AUTO_INCREMENT,
        type varchar(50) NOT NULL,
        data longtext NOT NULL,
        ip_address varchar(45) DEFAULT '',
        user_agent text DEFAULT '',
        submitted_at datetime NOT NULL,
        PRIMARY KEY (id),
        KEY type (type),
        KEY submitted_at (submitted_at)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;";
    
    dbDelta($sql);
    
    // Newsletter subscribers table
    $table_name = $wpdb->prefix . 'gpress_newsletter_subscribers';
    $sql = "CREATE TABLE $table_name (
        id int(11) NOT NULL AUTO_INCREMENT,
        email varchar(255) NOT NULL,
        status enum('pending','active','unsubscribed') DEFAULT 'pending',
        token varchar(64) NOT NULL,
        subscribed_at datetime NOT NULL,
        confirmed_at datetime DEFAULT NULL,
        ip_address varchar(45) DEFAULT '',
        PRIMARY KEY (id),
        UNIQUE KEY email (email),
        KEY status (status),
        KEY token (token)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;";
    
    dbDelta($sql);
}
register_activation_hook(__FILE__, 'gpress_create_form_tables');
```

### 2. Form Styles

Create `assets/css/forms.css`:

```css
/* GPress Form Styles */

/* Contact Form Styles */
.gpress-contact-form-wrapper {
    background: var(--wp--preset--color--background-secondary);
    padding: 2rem;
    border-radius: 12px;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    margin: 2rem 0;
}

.form-title {
    color: var(--wp--preset--color--primary);
    margin-bottom: 1.5rem;
    font-size: 1.5rem;
    font-weight: 600;
}

.form-row {
    display: flex;
    gap: 1rem;
    margin-bottom: 1.5rem;
}

.form-row:last-child {
    margin-bottom: 0;
}

.form-field {
    flex: 1;
}

.form-field label {
    display: block;
    margin-bottom: 0.5rem;
    font-weight: 600;
    color: var(--wp--preset--color--foreground);
}

.required {
    color: #d63384;
}

.form-field input,
.form-field textarea,
.form-field select {
    width: 100%;
    padding: 0.75rem;
    border: 2px solid var(--wp--preset--color--border);
    border-radius: 8px;
    font-size: 1rem;
    transition: all 0.3s ease;
    background: var(--wp--preset--color--background);
    color: var(--wp--preset--color--foreground);
}

.form-field input:focus,
.form-field textarea:focus,
.form-field select:focus {
    outline: none;
    border-color: var(--wp--preset--color--primary);
    box-shadow: 0 0 0 3px rgba(var(--wp--preset--color--primary-rgb), 0.1);
}

.form-field input:invalid,
.form-field textarea:invalid {
    border-color: #d63384;
}

.form-field textarea {
    resize: vertical;
    min-height: 120px;
}

/* Checkbox Styles */
.checkbox-label {
    display: flex;
    align-items: flex-start;
    gap: 0.75rem;
    cursor: pointer;
    font-size: 0.875rem;
    line-height: 1.5;
}

.checkbox-label input[type="checkbox"] {
    position: absolute;
    opacity: 0;
    width: 0;
    height: 0;
}

.checkmark {
    display: flex;
    align-items: center;
    justify-content: center;
    width: 20px;
    height: 20px;
    background: var(--wp--preset--color--background);
    border: 2px solid var(--wp--preset--color--border);
    border-radius: 4px;
    transition: all 0.3s ease;
    flex-shrink: 0;
    margin-top: 2px;
}

.checkbox-label input[type="checkbox"]:checked + .checkmark {
    background: var(--wp--preset--color--primary);
    border-color: var(--wp--preset--color--primary);
}

.checkbox-label input[type="checkbox"]:checked + .checkmark::after {
    content: "✓";
    color: var(--wp--preset--color--background);
    font-size: 12px;
    font-weight: bold;
}

.checkbox-label input[type="checkbox"]:focus + .checkmark {
    box-shadow: 0 0 0 3px rgba(var(--wp--preset--color--primary-rgb), 0.2);
}

/* Form Actions */
.form-actions {
    display: flex;
    justify-content: flex-start;
    margin-top: 2rem;
}

.submit-button,
.newsletter-submit {
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    background: var(--wp--preset--color--primary);
    color: var(--wp--preset--color--background);
    border: none;
    padding: 0.75rem 2rem;
    border-radius: 8px;
    font-size: 1rem;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.3s ease;
    text-decoration: none;
    min-height: 44px;
}

.submit-button:hover,
.submit-button:focus,
.newsletter-submit:hover,
.newsletter-submit:focus {
    background: var(--wp--preset--color--primary-dark, var(--wp--preset--color--primary));
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(var(--wp--preset--color--primary-rgb), 0.3);
}

.submit-button:disabled,
.newsletter-submit:disabled {
    opacity: 0.6;
    cursor: not-allowed;
    transform: none;
}

.loading-spinner {
    display: none;
    width: 16px;
    height: 16px;
    border: 2px solid rgba(255, 255, 255, 0.3);
    border-radius: 50%;
    border-top-color: rgba(255, 255, 255, 0.8);
    animation: spin 1s ease-in-out infinite;
}

.form-loading .loading-spinner {
    display: block;
}

.form-loading .button-text {
    display: none;
}

@keyframes spin {
    to { transform: rotate(360deg); }
}

/* Form Messages */
.form-messages {
    margin-top: 1rem;
    padding: 1rem;
    border-radius: 8px;
    font-weight: 600;
    display: none;
}

.form-messages.success {
    background: #d4edda;
    color: #155724;
    border: 1px solid #c3e6cb;
    display: block;
}

.form-messages.error {
    background: #f8d7da;
    color: #721c24;
    border: 1px solid #f5c6cb;
    display: block;
}

/* Newsletter Form Styles */
.gpress-newsletter-form-wrapper {
    background: var(--wp--preset--color--background-secondary);
    padding: 1.5rem;
    border-radius: 12px;
    text-align: center;
    margin: 2rem 0;
}

.newsletter-title {
    color: var(--wp--preset--color--primary);
    margin-bottom: 0.5rem;
    font-size: 1.25rem;
    font-weight: 600;
}

.newsletter-description {
    color: var(--wp--preset--color--foreground-secondary);
    margin-bottom: 1.5rem;
    font-size: 0.875rem;
}

.newsletter-input-group {
    display: flex;
    gap: 0.5rem;
    margin-bottom: 1rem;
    max-width: 400px;
    margin-left: auto;
    margin-right: auto;
}

.newsletter-input-group input[type="email"] {
    flex: 1;
    padding: 0.75rem;
    border: 2px solid var(--wp--preset--color--border);
    border-radius: 8px 0 0 8px;
    font-size: 0.875rem;
}

.newsletter-submit {
    border-radius: 0 8px 8px 0;
    padding: 0.75rem 1.5rem;
    font-size: 0.875rem;
    white-space: nowrap;
}

.newsletter-consent {
    margin-top: 1rem;
}

.newsletter-consent .checkbox-label {
    justify-content: center;
    font-size: 0.75rem;
}

/* Compact Newsletter Style */
.newsletter-style-compact {
    padding: 1rem;
    background: transparent;
    border: 2px solid var(--wp--preset--color--primary);
}

.newsletter-style-compact .newsletter-input-group {
    max-width: none;
}

/* Inline Newsletter Style */
.newsletter-style-inline .newsletter-input-group {
    max-width: none;
    margin-bottom: 0.5rem;
}

.newsletter-style-inline .newsletter-consent {
    margin-top: 0.5rem;
}

/* Responsive Design */
@media (max-width: 768px) {
    .form-row {
        flex-direction: column;
        gap: 0;
    }
    
    .form-field {
        margin-bottom: 1rem;
    }
    
    .newsletter-input-group {
        flex-direction: column;
    }
    
    .newsletter-input-group input[type="email"] {
        border-radius: 8px;
        margin-bottom: 0.5rem;
    }
    
    .newsletter-submit {
        border-radius: 8px;
    }
    
    .gpress-contact-form-wrapper,
    .gpress-newsletter-form-wrapper {
        padding: 1.5rem;
    }
}

/* High Contrast Mode Support */
@media (prefers-contrast: high) {
    .form-field input,
    .form-field textarea,
    .form-field select {
        border-width: 3px;
    }
    
    .submit-button,
    .newsletter-submit {
        border: 2px solid currentColor;
    }
}

/* Reduced Motion Support */
@media (prefers-reduced-motion: reduce) {
    .form-field input,
    .form-field textarea,
    .submit-button,
    .newsletter-submit,
    .checkmark {
        transition: none;
    }
    
    .loading-spinner {
        animation: none;
    }
    
    .submit-button:hover,
    .newsletter-submit:hover {
        transform: none;
    }
}

/* Print Styles */
@media print {
    .gpress-contact-form-wrapper,
    .gpress-newsletter-form-wrapper {
        background: white;
        box-shadow: none;
        border: 1px solid #000;
    }
    
    .submit-button,
    .newsletter-submit {
        background: white;
        color: black;
        border: 1px solid black;
    }
}
```

### 3. Form JavaScript

Create `assets/js/forms.js`:

```javascript
/**
 * GPress Forms JavaScript
 */
document.addEventListener('DOMContentLoaded', function() {
    
    // Initialize all forms
    initContactForms();
    initNewsletterForms();
    initFormValidation();
    initFormAccessibility();
    
    /**
     * Initialize contact forms
     */
    function initContactForms() {
        const contactForms = document.querySelectorAll('.gpress-contact-form');
        
        contactForms.forEach(function(form) {
            form.addEventListener('submit', function(e) {
                e.preventDefault();
                handleFormSubmission(form, 'gpress_contact_form');
            });
        });
    }
    
    /**
     * Initialize newsletter forms
     */
    function initNewsletterForms() {
        const newsletterForms = document.querySelectorAll('.gpress-newsletter-form');
        
        newsletterForms.forEach(function(form) {
            form.addEventListener('submit', function(e) {
                e.preventDefault();
                handleFormSubmission(form, 'gpress_newsletter');
            });
        });
    }
    
    /**
     * Handle form submission
     */
    function handleFormSubmission(form, action) {
        const formData = new FormData(form);
        const messageContainer = form.querySelector('.form-messages');
        const submitButton = form.querySelector('[type="submit"]');
        
        // Validate form
        if (!validateForm(form)) {
            return;
        }
        
        // Set loading state
        setFormLoading(form, true);
        
        // Prepare data
        formData.append('action', action);
        formData.append('nonce', gpressAjax.nonce);
        
        // Submit via AJAX
        fetch(gpressAjax.ajaxurl, {
            method: 'POST',
            body: formData,
            credentials: 'same-origin'
        })
        .then(response => response.json())
        .then(data => {
            setFormLoading(form, false);
            
            if (data.success) {
                showFormMessage(messageContainer, data.data.message, 'success');
                form.reset();
                
                // Focus message for accessibility
                messageContainer.focus();
                
                // Track success event
                if (typeof gtag !== 'undefined') {
                    gtag('event', 'form_submit', {
                        event_category: 'Forms',
                        event_label: action
                    });
                }
            } else {
                showFormMessage(messageContainer, data.data.message, 'error');
                
                // Focus first error field
                const firstError = form.querySelector('[aria-invalid="true"]');
                if (firstError) {
                    firstError.focus();
                }
            }
        })
        .catch(error => {
            console.error('Form submission error:', error);
            setFormLoading(form, false);
            showFormMessage(messageContainer, gpressAjax.messages.error, 'error');
        });
    }
    
    /**
     * Validate form
     */
    function validateForm(form) {
        let isValid = true;
        const requiredFields = form.querySelectorAll('[required]');
        
        requiredFields.forEach(function(field) {
            if (!validateField(field)) {
                isValid = false;
            }
        });
        
        return isValid;
    }
    
    /**
     * Validate individual field
     */
    function validateField(field) {
        const value = field.value.trim();
        const fieldType = field.type;
        let isValid = true;
        let errorMessage = '';
        
        // Clear previous errors
        clearFieldError(field);
        
        // Required field validation
        if (field.hasAttribute('required') && !value) {
            isValid = false;
            errorMessage = gpressAjax.messages.required;
        }
        
        // Email validation
        else if (fieldType === 'email' && value) {
            const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            if (!emailRegex.test(value)) {
                isValid = false;
                errorMessage = gpressAjax.messages.email;
            }
        }
        
        // Checkbox validation
        else if (fieldType === 'checkbox' && field.hasAttribute('required') && !field.checked) {
            isValid = false;
            errorMessage = gpressAjax.messages.required;
        }
        
        // Update field state
        field.setAttribute('aria-invalid', isValid ? 'false' : 'true');
        
        if (!isValid) {
            showFieldError(field, errorMessage);
        }
        
        return isValid;
    }
    
    /**
     * Show field error
     */
    function showFieldError(field, message) {
        const fieldContainer = field.closest('.form-field');
        if (!fieldContainer) return;
        
        const errorId = field.id + '-error';
        const errorElement = document.createElement('div');
        errorElement.id = errorId;
        errorElement.className = 'field-error';
        errorElement.textContent = message;
        errorElement.setAttribute('role', 'alert');
        
        fieldContainer.appendChild(errorElement);
        
        // Update aria-describedby
        const describedBy = field.getAttribute('aria-describedby') || '';
        field.setAttribute('aria-describedby', (describedBy + ' ' + errorId).trim());
        
        // Style field as error
        field.classList.add('field-error-state');
    }
    
    /**
     * Clear field error
     */
    function clearFieldError(field) {
        const fieldContainer = field.closest('.form-field');
        if (!fieldContainer) return;
        
        const existingError = fieldContainer.querySelector('.field-error');
        if (existingError) {
            const errorId = existingError.id;
            existingError.remove();
            
            // Clean up aria-describedby
            const describedBy = field.getAttribute('aria-describedby') || '';
            const cleanDescribedBy = describedBy.replace(errorId, '').trim();
            if (cleanDescribedBy) {
                field.setAttribute('aria-describedby', cleanDescribedBy);
            } else {
                field.removeAttribute('aria-describedby');
            }
        }
        
        field.classList.remove('field-error-state');
    }
    
    /**
     * Set form loading state
     */
    function setFormLoading(form, loading) {
        const submitButton = form.querySelector('[type="submit"]');
        
        if (loading) {
            form.classList.add('form-loading');
            submitButton.disabled = true;
            submitButton.setAttribute('aria-busy', 'true');
        } else {
            form.classList.remove('form-loading');
            submitButton.disabled = false;
            submitButton.setAttribute('aria-busy', 'false');
        }
    }
    
    /**
     * Show form message
     */
    function showFormMessage(container, message, type) {
        container.className = 'form-messages ' + type;
        container.textContent = message;
        container.style.display = 'block';
        
        // Auto-hide success messages
        if (type === 'success') {
            setTimeout(function() {
                container.style.display = 'none';
            }, 5000);
        }
    }
    
    /**
     * Initialize form validation
     */
    function initFormValidation() {
        const formInputs = document.querySelectorAll('.gpress-contact-form input, .gpress-contact-form textarea, .gpress-newsletter-form input');
        
        formInputs.forEach(function(input) {
            // Real-time validation on blur
            input.addEventListener('blur', function() {
                if (this.value.trim() || this.hasAttribute('required')) {
                    validateField(this);
                }
            });
            
            // Clear errors on input
            input.addEventListener('input', function() {
                if (this.classList.contains('field-error-state')) {
                    clearFieldError(this);
                    this.setAttribute('aria-invalid', 'false');
                }
            });
        });
    }
    
    /**
     * Initialize form accessibility
     */
    function initFormAccessibility() {
        // Add keyboard navigation for custom checkboxes
        const checkboxLabels = document.querySelectorAll('.checkbox-label');
        
        checkboxLabels.forEach(function(label) {
            const checkbox = label.querySelector('input[type="checkbox"]');
            
            label.addEventListener('keydown', function(e) {
                if (e.key === 'Enter' || e.key === ' ') {
                    e.preventDefault();
                    checkbox.checked = !checkbox.checked;
                    checkbox.dispatchEvent(new Event('change'));
                }
            });
        });
        
        // Form submission keyboard shortcuts
        document.addEventListener('keydown', function(e) {
            if (e.ctrlKey && e.key === 'Enter') {
                const activeForm = document.activeElement.closest('form');
                if (activeForm && (activeForm.classList.contains('gpress-contact-form') || activeForm.classList.contains('gpress-newsletter-form'))) {
                    const submitButton = activeForm.querySelector('[type="submit"]');
                    if (submitButton && !submitButton.disabled) {
                        submitButton.click();
                    }
                }
            }
        });
    }
    
    // Performance optimization: Debounced validation
    function debounce(func, wait) {
        let timeout;
        return function executedFunction(...args) {
            const later = () => {
                clearTimeout(timeout);
                func(...args);
            };
            clearTimeout(timeout);
            timeout = setTimeout(later, wait);
        };
    }
    
    // Apply debounced validation to email fields
    const emailFields = document.querySelectorAll('input[type="email"]');
    emailFields.forEach(function(field) {
        const debouncedValidate = debounce(function() {
            validateField(field);
        }, 300);
        
        field.addEventListener('input', debouncedValidate);
    });
});
```

### 4. Update Functions.php

Add to `functions.php`:

```php
// Advanced forms and contact functionality
require_once get_theme_file_path('/inc/form-handler.php');
```

## Testing

1. **Form Functionality Testing**:
   - Test contact form submission and email delivery
   - Verify newsletter subscription process
   - Check form validation (client-side and server-side)
   - Test AJAX submissions and error handling

2. **Security Testing**:
   - Verify nonce protection
   - Test rate limiting functionality  
   - Check input sanitization and validation
   - Test CSRF protection

3. **Accessibility Testing**:
   - Test keyboard navigation
   - Verify screen reader compatibility
   - Check ARIA labels and descriptions
   - Test focus management

4. **Performance Testing**:
   - Verify conditional asset loading
   - Check form loading times
   - Test mobile performance
   - Validate no JavaScript errors

5. **Integration Testing**:
   - Test with WordPress core functionality
   - Check compatibility with common plugins
   - Verify database operations
   - Test email delivery

## Next Steps

In Step 12, we'll implement advanced navigation and menu systems with improved accessibility and mobile optimization.

## Key Benefits

- Advanced form handling with AJAX submissions
- Conditional asset loading for optimal performance
- Comprehensive security and rate limiting
- Full accessibility compliance
- Newsletter subscription management
- Professional form styling and UX
- Database integration for form storage
- Email confirmation workflows