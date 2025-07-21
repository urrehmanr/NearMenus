<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo('charset'); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?php wp_title('|', true, 'right'); ?><?php bloginfo('name'); ?></title>
    
    <?php wp_head(); ?>
    
    <!-- Fallback CSS if nothing else loads -->
    <style>
    body { font-family: Arial, sans-serif; margin: 0; padding: 0; }
    .container { max-width: 1200px; margin: 0 auto; padding: 0 20px; }
    header { background: #f8f9fa; padding: 1rem 0; border-bottom: 1px solid #dee2e6; }
    .hero-section { background: linear-gradient(135deg, #f97316, #ea580c); color: white; padding: 3rem 0; text-align: center; }
    .post-card { background: white; border: 1px solid #e5e7eb; border-radius: 8px; padding: 1.5rem; margin-bottom: 1.5rem; }
    .btn-primary { background: #007cba; color: white; padding: 0.5rem 1rem; text-decoration: none; border-radius: 4px; }
    .grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 1.5rem; }
    </style>
</head>

<body <?php body_class(); ?>>
<?php wp_body_open(); ?>

<div id="page" class="site">
    <header id="masthead" class="site-header">
        <div class="container">
            <div style="display: flex; justify-content: space-between; align-items: center;">
                
                <!-- Site Title -->
                <div class="site-branding">
                    <h1 style="margin: 0;">
                        <a href="<?php echo esc_url(home_url('/')); ?>" style="color: #3b82f6; text-decoration: none;">
                            <?php bloginfo('name'); ?>
                        </a>
                    </h1>
                    <?php 
                    $description = get_bloginfo('description', 'display');
                    if ($description || is_customize_preview()) : ?>
                        <p style="margin: 0; color: #666; font-size: 0.9rem;"><?php echo $description; ?></p>
                    <?php endif; ?>
                </div>
                
                <!-- Simple Navigation -->
                <nav class="main-navigation">
                    <?php
                    if (has_nav_menu('primary')) {
                        wp_nav_menu(array(
                            'theme_location' => 'primary',
                            'menu_id' => 'primary-menu',
                            'container' => false,
                        ));
                    } else {
                        echo '<ul style="list-style: none; margin: 0; padding: 0; display: flex; gap: 2rem;">';
                        echo '<li><a href="' . home_url('/') . '" style="color: #374151; text-decoration: none;">Home</a></li>';
                        echo '<li><a href="' . admin_url() . '" style="color: #374151; text-decoration: none;">Admin</a></li>';
                        echo '</ul>';
                    }
                    ?>
                </nav>
                
            </div>
        </div>
    </header>

    <main id="main" class="site-main"><?php // Note: opened here, closed in footer ?>