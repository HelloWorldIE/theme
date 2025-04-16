<?php
/**
 * Horizontal Card Template
 * 
 * This template displays a horizontal card with media on the left. It dynamically fetches
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
$content = $args['content'] ?? '';
$acf_id = $args['acf_id'] ?? '';
$acf_field_key = $args['acf_field_key'] ?? '';
$acf_flexible_content = $args['acf_flexible_content'] ?? '';
$acf_layout = $args['acf_layout'] ?? '';
?>

<article id="acf-article-<?php echo esc_attr($acf_id); ?>" data-acf-field-key="<?php echo esc_attr($acf_field_key); ?>"
  data-acf-field-type="wysiwyg" data-acf-flexible-content="<?php echo esc_attr($acf_flexible_content); ?>" data-acf-layout="<?php echo esc_attr($acf_layout); ?>">
  <div class="card my-0">
    <div class="card-content">
      <div class="media is-align-items-center">
        <div class="media-left">
          <figure class="image is-96x96">
            <img src="<?php echo esc_url($image_url); ?>" alt="Placeholder image" />
          </figure>
        </div>
        <div class="media-content">
          <h3 class="title is-4" data-acf-field-key="<?php echo esc_attr($acf_field_key); ?>_title" data-acf-field-type="text"><?php echo esc_html($title); ?></h3>
          <div class="subtitle is-6" data-acf-field-key="<?php echo esc_attr($acf_field_key); ?>_subtitle" data-acf-field-type="textarea"><?php echo esc_html($subtitle); ?></div>
        </div>
      </div>
      <div class="content" data-acf-field-key="<?php echo esc_attr($acf_field_key); ?>_content" data-acf-field-type="wysiwyg">
        <?php echo $content; ?>
      </div>
    </div>
  </div>
</article>
