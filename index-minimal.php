<?php
/**
 * Minimal Index Template - Guaranteed to show content
 *
 * @package NearMenus
 * @since 1.0.0
 */

get_header(); ?>

<div class="site-content">
    
    <!-- Hero Section -->
    <section class="hero-section">
        <div class="container">
            <h1>Welcome to NearMenus</h1>
            <p>Your restaurant discovery starts here!</p>
        </div>
    </section>
    
    <!-- Main Content -->
    <section class="py-16">
        <div class="container">
            <h2 class="mb-8 text-center">Latest Posts</h2>
            
            <div class="grid">
                <?php
                // Get all published posts - no restrictions
                $all_posts = get_posts(array(
                    'post_type' => 'post',
                    'post_status' => 'publish',
                    'numberposts' => 10,
                    'orderby' => 'date',
                    'order' => 'DESC'
                ));
                
                if ($all_posts) {
                    foreach ($all_posts as $post) {
                        setup_postdata($post);
                        ?>
                        <article class="post-card">
                            <h3><a href="<?php echo get_permalink($post); ?>"><?php echo get_the_title($post); ?></a></h3>
                            
                            <?php if (has_post_thumbnail($post)) : ?>
                                <div class="post-image mb-4">
                                    <?php echo get_the_post_thumbnail($post, 'medium'); ?>
                                </div>
                            <?php endif; ?>
                            
                            <div class="post-meta mb-4">
                                <small>Published on <?php echo get_the_date('', $post); ?></small>
                            </div>
                            
                            <div class="post-excerpt">
                                <?php 
                                $excerpt = get_the_excerpt($post);
                                if ($excerpt) {
                                    echo $excerpt;
                                } else {
                                    echo wp_trim_words(get_the_content('', false, $post), 20);
                                }
                                ?>
                            </div>
                            
                            <a href="<?php echo get_permalink($post); ?>" class="btn-primary">Read More</a>
                        </article>
                        <?php
                    }
                    wp_reset_postdata();
                } else {
                    // Absolutely no posts found
                    ?>
                    <div class="text-center py-16">
                        <h3>No Posts Found</h3>
                        <p>It looks like there are no published posts in your WordPress database.</p>
                        
                        <?php if (current_user_can('publish_posts')) : ?>
                            <p><a href="<?php echo admin_url('post-new.php'); ?>" class="btn-primary">Create Your First Post</a></p>
                        <?php endif; ?>
                        
                        <hr style="margin: 2rem 0;">
                        
                        <h4>Theme Information:</h4>
                        <ul style="text-align: left; max-width: 400px; margin: 0 auto;">
                            <li><strong>Theme:</strong> <?php echo wp_get_theme()->get('Name'); ?></li>
                            <li><strong>Version:</strong> <?php echo wp_get_theme()->get('Version'); ?></li>
                            <li><strong>WordPress:</strong> <?php echo get_bloginfo('version'); ?></li>
                            <li><strong>PHP:</strong> <?php echo PHP_VERSION; ?></li>
                        </ul>
                        
                        <p style="margin-top: 2rem;">
                            <strong>This means the theme is working!</strong><br>
                            You just need to create some content.
                        </p>
                    </div>
                    <?php
                }
                ?>
            </div>
            
            <!-- WordPress Loop Fallback -->
            <?php if (have_posts()) : ?>
                <hr style="margin: 3rem 0;">
                <h3 class="text-center mb-4">WordPress Default Loop:</h3>
                <div class="grid">
                    <?php while (have_posts()) : the_post(); ?>
                        <article class="post-card">
                            <h3><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
                            <div class="post-meta mb-4">
                                <small>Published on <?php the_date(); ?></small>
                            </div>
                            <div class="post-excerpt">
                                <?php the_excerpt(); ?>
                            </div>
                            <a href="<?php the_permalink(); ?>" class="btn-primary">Read More</a>
                        </article>
                    <?php endwhile; ?>
                </div>
            <?php endif; ?>
            
        </div>
    </section>
    
</div>

<?php get_footer(); ?>