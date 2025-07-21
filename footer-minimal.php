    </main><!-- #main -->

    <footer id="colophon" class="site-footer" style="background: #f8f9fa; padding: 2rem 0; margin-top: 3rem; border-top: 1px solid #dee2e6;">
        <div class="container">
            <div style="text-align: center;">
                <p style="margin: 0; color: #666;">
                    &copy; <?php echo date('Y'); ?> <?php bloginfo('name'); ?>. 
                    Powered by <a href="https://wordpress.org/" style="color: #007cba;">WordPress</a> 
                    & NearMenus Theme.
                </p>
                
                <?php if (wp_get_theme()->get('Description')) : ?>
                    <p style="margin: 0.5rem 0 0; color: #888; font-size: 0.85rem;">
                        <?php echo wp_get_theme()->get('Description'); ?>
                    </p>
                <?php endif; ?>
                
                <!-- Debug Info -->
                <p style="margin: 1rem 0 0; font-size: 0.75rem; color: #999;">
                    Theme: <?php echo wp_get_theme()->get('Name'); ?> v<?php echo wp_get_theme()->get('Version'); ?> | 
                    WP: <?php echo get_bloginfo('version'); ?> | 
                    PHP: <?php echo PHP_VERSION; ?>
                </p>
            </div>
        </div>
    </footer><!-- #colophon -->

</div><!-- #page -->

<?php wp_footer(); ?>

</body>
</html>