<?php
/**
 * Template part for displaying archive cards
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package Obulma
 */
?>

<article id="post-<?php the_ID(); ?>" <?php post_class("column is-one-quarter"); ?>>
    <div class="card is-flex is-flex-direction-column is-justify-content-flex-start" style="height:100%">

        <div class="card-image p-2">
            <figure class="image is-3by2">
                <a href="<?php the_permalink(); ?>">
                    <?php if (has_post_thumbnail()): ?>
                        <?php the_post_thumbnail('full', ['alt' => the_title_attribute(['echo' => false])]); ?>
                    <?php else: ?>
                        <img src="https://placehold.co/960x640/png?text=Learning%20Experience"
                            alt="<?php the_title_attribute(); ?>" />
                    <?php endif; ?>
                </a>
            </figure>
        </div>

        <div class="card-content">
            <?php if (is_singular()): ?>
                <h1 class="title is-6"><?php the_title(); ?></h1>
            <?php else: ?>
                <h2 class="title is-6"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
            <?php endif; ?>
            <div class="content">
                <?php the_excerpt(); ?>
                <?php
                $client = get_field('client');
                if ($client): ?>
                    <article id="acf-article-client" class="ld-client" data-acf-field-key="field_6695afb0ec047"
                        data-acf-field-type="post_object">
                        <div class="media is-flex is-align-items-center">
                            <div class="media-left">
                                <figure class="image is-48x48 is-flex is-align-items-center">
                                    <img src="<?php echo has_post_thumbnail($client->ID) ? get_the_post_thumbnail_url($client->ID, 'full') : 'https://bulma.io/assets/images/placeholders/96x96.png'; ?>"
                                        alt="<?php echo esc_attr(get_the_title($client->ID)); ?>" />
                                </figure>
                            </div>
                            <div class="media-content">
                                <strong><?php echo get_the_title($client->ID); ?></strong>
                            </div>
                        </div>
                    </article>
                <?php endif; ?>
            </div>
        </div>

        <footer class="card-footer is-align-content-end" style="margin-top: auto;">
            <?php if (get_the_category_list()): ?>
                <span class="card-footer-item"><?php echo get_the_category_list(', '); ?></span>
            <?php endif; ?>
            <a href="<?php the_permalink(); ?>" class="card-footer-item">Learn More</a>
        </footer>
    </div>
</article><!-- #post-<?php the_ID(); ?> -->