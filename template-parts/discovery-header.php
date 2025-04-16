<?php
/**
 * Template Part - Header Section (Simplified)
 * 
 * This template handles the main header structure, displaying only the title and client box on the left,
 * and the featured image on the right.
 * 
 * @package Cobulma
 * @version 1.0
 * @since 1.0
 */

global $total_learning_time_string; // Declare global variable

// Fetch ACF fields
$client = get_field('client');
?>

<section class="ld-header hero">
    <div class="hero-body">
        <div class="ld-header-text columns is-vcentered">
            <!-- Left Column: Title and Client Box -->
            <div class="column is-two-thirds">
                <!-- Title -->
                <h1 id="acf-field-title" class="acf-title title is-1" data-acf-field-key="field_6695afb0ec003"
                    data-acf-field-type="text">
                    <?php the_title(); ?>
                </h1>

                <!-- Client Box -->
                <?php if ($client):
                    $post = $client;
                    setup_postdata($post); ?>
                    <article id="acf-article-client" class="ld-client"
                        data-acf-field-key="field_6695afb0ec047" data-acf-field-type="post_object">
                        <div class="media is-align-items-center">
                            <div class="media-left">
                                <figure class="image is-96x96 is-flex is-align-items-center">
                                    <img src="<?php echo has_post_thumbnail($post) ? get_the_post_thumbnail_url($post, 'full') : 'https://bulma.io/assets/images/placeholders/96x96.png'; ?>"
                                        alt="<?php echo esc_attr(get_the_title($post)); ?>" />
                                </figure>
                            </div>
                            <div class="media-content">
                                <h3 class="title is-4"><?php echo get_the_title($post); ?></h3>
                                <div class="subtitle is-6">
                                    <?php
                                    // Display published and updated dates from the client post
                                    $published_date = get_the_date('d/m/Y', $post);
                                    $updated_date = get_the_modified_date('d/m/Y', $post);
                                    ?>
                                    <span class="tag is-light mr-2 mb-2">Published: <?php echo esc_html($published_date); ?></span>
                                    <span class="tag is-light mb-2">Updated: <?php echo esc_html($updated_date); ?></span>
                                </div>
                            </div>
                        </div>
                    </article>
                    <?php wp_reset_postdata(); ?>
                <?php endif; ?>
            </div>

            <!-- Right Column: Featured Image -->
            <?php if (has_post_thumbnail()): ?>
                <div class="ld-featured-image column is-one-third">
                    <figure id="acf-field-featured_image" class="image is-fullwidth"
                        data-acf-field-key="field_6695afb0ec0c2" data-acf-field-type="image">
                        <?php the_post_thumbnail('full'); ?>
                    </figure>
                </div>
            <?php endif; ?>
        </div>
    </div>
</section>
