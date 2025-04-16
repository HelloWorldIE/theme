<?php

/**
 * Template Name: Client Dashboard Template
 * Description: Custom template for displaying client details with linked reviewers, learning designs, and discovery documents.
 */

get_header();
?>

<div id="primary" class="content-area column is-full">
    <main id="main" class="site-main">
        <?php while (have_posts()) : the_post(); ?>
            <!-- Client Header Section -->
            <section class="ld-header hero">
                <div class="hero-body">
                    <div class="ld-header-text columns is-vcentered">
                        <!-- Left Column: Client Name and Reviewers -->
                        <div class="column is-two-thirds">
                            <!-- Client Name -->
                            <h1 class="acf-title title is-1">
                                <?php echo esc_html(get_field('client_name')); ?>
                            </h1>

                            <!-- Reviewers (small profile icons) -->
                            <div class="reviewers mt-3">
                                <?php
                                $reviewers = get_field('reviewers');
                                if ($reviewers && is_array($reviewers)):
                                    foreach ($reviewers as $reviewer):
                                        // Since return_format is 'array', $reviewer['ID'] should give us the ID directly
                                        $user_info = get_userdata($reviewer['ID']);
                                ?>
                                        <div class="media is-inline-block mr-3 mb-3">
                                            <figure class="image is-48x48">
                                                <?php echo get_avatar($reviewer['ID'], 24); ?>
                                            </figure>
                                            <div class="media-content">
                                                <p class="is-size-7 has-text-centered">
                                                    <?php echo esc_html($user_info->display_name); ?>
                                                </p>
                                            </div>
                                        </div>
                                <?php endforeach;
                                endif;
                                ?>
                            </div>
                        </div>

                        <!-- Right Column: Client Logo -->
                        <?php
                        $client_logo = get_field('client_logo');
                        if ($client_logo && is_array($client_logo)): ?>
                            <div class="ld-featured-image column is-one-third has-text-centered">
                                <figure id="acf-field-featured_image" class="image is-inline-block"
                                    style="max-width: <?php echo esc_attr($client_logo['width']); ?>px;">
                                    <img src="<?php echo esc_url($client_logo['url']); ?>" alt="Client Logo"
                                        style="width: auto; height: auto; max-width: 100%; max-height: <?php echo esc_attr($client_logo['height']); ?>px;">
                                </figure>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </section>

            <!-- Archives for Linked Learning Designs and Discovery Documents -->
            <div class="container mt-6">
                <!-- Learning Designs Section -->
                <section class="learning-designs mb-6">
                    <h2 class="title is-4">Learning Designs</h2>
                    <div class="columns is-multiline">
                        <?php
                        $learning_designs = get_field('learning_designs');
                        if ($learning_designs && is_array($learning_designs)):
                            foreach ($learning_designs as $learning_design): ?>
                                <article id="post-<?php echo esc_attr($learning_design->ID); ?>"
                                    class="column is-one-quarter">
                                    <div class="card is-flex is-flex-direction-column is-justify-content-flex-start"
                                        style="height:100%">
                                        <div class="card-image p-2">
                                            <figure class="image is-3by2">
                                                <a href="<?php echo get_permalink($learning_design->ID); ?>">
                                                    <?php
                                                    if (has_post_thumbnail($learning_design->ID)):
                                                        echo get_the_post_thumbnail($learning_design->ID, 'full');
                                                    else: ?>
                                                        <img src="https://placehold.co/960x640/png?text=Learning%20Design"
                                                            alt="<?php echo esc_attr(get_the_title($learning_design->ID)); ?>">
                                                    <?php endif; ?>
                                                </a>
                                            </figure>
                                        </div>
                                        <div class="card-content">
                                            <h2 class="title is-6">
                                                <a href="<?php echo get_permalink($learning_design->ID); ?>">
                                                    <?php echo get_the_title($learning_design->ID); ?>
                                                </a>
                                            </h2>
                                            <div class="content">
                                                <?php echo get_the_excerpt($learning_design->ID); ?>
                                            </div>
                                        </div>
                                        <footer class="card-footer is-align-content-end" style="margin-top: auto;">
                                            <a href="<?php echo get_permalink($learning_design->ID); ?>"
                                                class="card-footer-item">Learn More</a>
                                        </footer>
                                    </div>
                                </article>
                            <?php endforeach;
                        else: ?>
                            <p class="has-text-grey">No linked learning designs found.</p>
                        <?php endif; ?>
                    </div>
                </section>

                <!-- Discovery Documents Section -->
                <section class="discovery-documents mb-6">
                    <h2 class="title is-4">Discovery Documents</h2>
                    <div class="columns is-multiline">
                        <?php
                        $discovery_documents = get_field('discovery_documents');
                        if ($discovery_documents && is_array($discovery_documents)):
                            foreach ($discovery_documents as $discovery): ?>
                                <article id="post-<?php echo esc_attr($discovery->ID); ?>" class="column is-one-quarter">
                                    <div class="card is-flex is-flex-direction-column is-justify-content-flex-start"
                                        style="height:100%">
                                        <div class="card-image p-2">
                                            <figure class="image is-3by2">
                                                <a href="<?php echo get_permalink($discovery->ID); ?>">
                                                    <?php
                                                    if (has_post_thumbnail($discovery->ID)):
                                                        echo get_the_post_thumbnail($discovery->ID, 'full');
                                                    else: ?>
                                                        <img src="https://placehold.co/960x640/png?text=Discovery%20Document"
                                                            alt="<?php echo esc_attr(get_the_title($discovery->ID)); ?>">
                                                    <?php endif; ?>
                                                </a>
                                            </figure>
                                        </div>
                                        <div class="card-content">
                                            <h2 class="title is-6">
                                                <a href="<?php echo get_permalink($discovery->ID); ?>">
                                                    <?php echo get_the_title($discovery->ID); ?>
                                                </a>
                                            </h2>
                                            <div class="content">
                                                <?php echo get_the_excerpt($discovery->ID); ?>
                                            </div>
                                        </div>
                                        <footer class="card-footer is-align-content-end" style="margin-top: auto;">
                                            <a href="<?php echo get_permalink($discovery->ID); ?>"
                                                class="card-footer-item">Learn More</a>
                                        </footer>
                                    </div>
                                </article>
                            <?php endforeach;
                        else: ?>
                            <p class="has-text-grey">No linked discovery documents found.</p>
                        <?php endif; ?>
                    </div>
                </section>
            </div>
        <?php endwhile; ?>
    </main>
</div>

<?php
get_footer();
