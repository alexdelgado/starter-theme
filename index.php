<?php get_header(); ?>

    <main id="content" role="main">

        <?php if(have_posts()): ?>

            <?php while(have_posts()): the_post(); ?>

                <?php
                    /**
                     * Include the Post-Format-specific template for the content.
                     * If you want to override this in a child theme, then include a file
                     * called content-___.php (where ___ is the Post Format name) and that will be used instead.
                     */
                    get_template_part('templates/content', get_post_format());
                ?>

            <?php endwhile; ?>

            <?php the_posts_navigation(); ?>

        <?php else : ?>

            <?php get_template_part('templates/content', 'none'); ?>

        <?php endif; ?>

    </main>

    <?php get_sidebar(); ?>

<?php get_footer(); ?>
