<?php

/**
 * Template part for displaying learning experiences
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package Obulma
 */

?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
    <header class="entry-header">
        <?php
        if (is_singular()) :
            the_title('<h1 class="entry-title">', '</h1>');
        else :
            the_title('<h2 class="entry-title"><a href="' . esc_url(get_permalink()) . '" rel="bookmark">', '</a></h2>');
        endif;

        if ('post' === get_post_type()) :
        ?>
            <div class="entry-meta">
                <?php
                obulma_posted_on();
                obulma_posted_by();
                ?>
            </div><!-- .entry-meta -->
        <?php endif; ?>
    </header><!-- .entry-header -->

    <?php obulma_post_thumbnail(); ?>

    <div class="entry-content content">
        <?php
        the_content(
            sprintf(
                wp_kses(
                    /* translators: %s: Name of current post. Only visible to screen readers */
                    __('Continue reading<span class="screen-reader-text"> "%s"</span>', 'obulma'),
                    array(
                        'span' => array(
                            'class' => array(),
                        ),
                    )
                ),
                get_the_title()
            )
        );
        ?>

        <?php if (get_field('description')) : ?>
            <p><strong>Description: </strong><?php the_field('description'); ?></p>
        <?php endif; ?>

        <?php if (get_field('when_to_use')) : ?>
            <p><strong>When to use: </strong><?php the_field('when_to_use'); ?></p>
        <?php endif; ?>

        <?php if (get_field('how_to_create')) : ?>
            <p><strong>How to create: </strong><?php the_field('how_to_create'); ?></p>
        <?php endif; ?>

        <?php if (get_field('benefit_of_use')) : ?>
            <p><strong>Benefit of use:</strong> <?php the_field('benefit_of_use'); ?></p>
        <?php endif; ?>

        <button class="button" onclick="window.history.back();">Go Back</button>
        
        <?php if (have_rows('examples')) : ?>
            <?php while (have_rows('examples')) : the_row(); ?>
                <?php the_sub_field('example_url_embed'); ?>
            <?php endwhile; ?>
        <?php else : ?>
            <?php // No rows found 
            ?>
        <?php endif; ?>

        <?php

        wp_link_pages(
            array(
                'before' => '<div class="page-links">' . esc_html__('Pages:', 'obulma'),
                'after'  => '</div>',
            )
        );
        ?>
    </div><!-- .entry-content -->

    <footer class="entry-footer">
        <?php obulma_entry_footer(); ?>
    </footer><!-- .entry-footer -->
</article><!-- #post-<?php the_ID(); ?> -->