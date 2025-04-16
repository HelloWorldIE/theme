<?php
/**
 * Media Left Template
 * 
 * This template displays a card with media on the left. It dynamically fetches
 * the title, image, and subtitle based on the passed arguments.
 * 
 * @package Cobulma
 * @version 1.0
 * @since 1.0
 * 
 * Author: Bryan
 */

// Fetch passed arguments
$title = $args['title'] ?? '';
$image_url = $args['image_url'] ?? 'https://bulma.io/assets/images/placeholders/96x96.png';
$subtitle = $args['subtitle'] ?? '';
$acf_id = $args['acf_id'] ?? '';
?>

<article id="acf-article-<?php echo esc_attr($acf_id); ?>" data-acf-field-key="<?php echo esc_attr($acf_id); ?>" data-acf-field-type="post_object">
    <div class="media is-align-items-center">
        <div class="media-left">
            <figure class="image is-96x96 is-flex is-align-items-center">
                <img src="<?php echo esc_url($image_url); ?>" alt="Placeholder image" />
            </figure>
        </div>
        <div class="media-content">
            <h3 class="title is-4"><?php echo esc_html($title); ?></h3>
            <div class="subtitle is-6">
                <?php echo $subtitle; ?>
            </div>
        </div>
    </div>
</article>
