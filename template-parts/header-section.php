<?php
/**
 * Template Part - Header Section
 * 
 * This template handles the main header structure, including the opportunity text,
 * featured image, authoring features, and client information. It uses ACF fields dynamically
 * and handles logic internally.
 * 
 * @package Cobulma
 * @version 1.0
 * @since 1.0
 * 
 * Author: Bryan
 */

global $total_learning_time_string; // Declare global variable

// Fetch ACF fields
$client = get_field('client');

// Initialize variables for flexible content
$the_opportunity = '';
$authoring_tools = [];
$course_types = [];

// Cache the rows of 'about_programme' flexible content field
$about_programme_rows = [];
if (have_rows('about_programme')) {
    while (have_rows('about_programme')) {
        the_row();
        $about_programme_rows[] = get_row(true);
    }
}

// Process the cached rows for header
foreach ($about_programme_rows as $row) {
    if ($row['acf_fc_layout'] == 'the_opportunity') {
        ob_start();
        echo $row['the_opportunity'];
        $the_opportunity = ob_get_clean();
    } elseif ($row['acf_fc_layout'] == 'authoring_tools') {
        $authoring_tools = $row['authoring_tools'];
    } elseif ($row['acf_fc_layout'] == 'course_type') {
        $course_types = $row['course_type'];
    }
}

/**
 * Generate authoring features subtitle.
 *
 * @param array $authoring_tools
 * @param array $course_types
 * @return string
 */
function generate_authoring_features_subtitle($authoring_tools, $course_types)
{
    $subtitle = '';

    // Add authoring tools as tags
    if ($authoring_tools) {
        foreach ($authoring_tools as $tool) {
            $subtitle .= '<span class="tag is-primary mr-2 mb-2">' . esc_html($tool) . '</span>';
        }
    }

    // Add course types as tags
    if ($course_types) {
        foreach ($course_types as $type) {
            $subtitle .= '<span class="tag is-secondary mr-2 mb-2">' . esc_html($type) . '</span>';
        }
    }

    return $subtitle;

}


/**
 * Generate client subtitle.
 *
 * @return string
 */
function generate_client_subtitle()
{
    $published_date = get_the_date('d/m/Y');
    $updated_date = get_the_modified_date('d/m/Y');

    $subtitle = '<span class="tag is-light mr-2 mb-2">Published: ' . esc_html($published_date) . '</span>';
    $subtitle .= '<span class="tag is-light mb-2">Updated: ' . esc_html($updated_date) . '</span>';

    return $subtitle;
}
?>

<section class="ld-header hero">
    <div class="hero-body">
        <div class="ld-header-text columns is-vcentered reverse-columns">
            <div class="column is-two-thirds">
                <h1 id="acf-field-title" class="acf-title title is-1" data-acf-field-key="field_6695afb0ec003"
                    data-acf-field-type="text">
                    <?php the_title(); ?>
                </h1>

                <?php if ($the_opportunity): ?>
                    <article class="acf-the_opportunity block">
                        <h2 class="subtitle">The Opportunity</h2>
                        <div class="content" data-acf-field-key="field_6695afdee044f" data-acf-field-type="wysiwyg"
                            data-acf-flexible-content="about_programme" data-acf-layout="the_opportunity">
                            <?php echo $the_opportunity; ?>
                        </div>
                    </article>
                <?php endif; ?>
            </div>

            <?php if (has_post_thumbnail()): ?>
                <div class="ld-featured-image column is-one-third">
                    <figure id="acf-field-featured_image" class="image is-fullwidth"
                        data-acf-field-key="field_6695afb0ec0c2" data-acf-field-type="image">
                        <?php the_post_thumbnail('full'); ?>
                    </figure>
                </div>
            <?php endif; ?>
        </div>

        <div class="columns">
            <?php if ($client):
                $post = $client;
                setup_postdata($post); ?>
                <article id="acf-article-client" class="ld-client column is-two-thirds"
                    data-acf-field-key="field_6695afb0ec047" data-acf-field-type="post_object">
                    <div class="media is-align-items-center">
                        <div class="media-left">
                            <figure class="image is-96x96 is-flex is-align-items-center">
                                <img src="<?php echo has_post_thumbnail() ? get_the_post_thumbnail_url($post, 'full') : 'https://bulma.io/assets/images/placeholders/96x96.png'; ?>"
                                    alt="<?php echo esc_attr(get_the_title()); ?>" />
                            </figure>
                        </div>
                        <div class="media-content">
                            <h3 class="title is-4"><?php echo get_the_title(); ?></h3>
                            <div class="subtitle is-6">
                                <?php echo generate_client_subtitle(); ?>
                            </div>
                        </div>
                    </div>
                </article>
                <?php wp_reset_postdata(); ?>
            <?php endif; ?>

            <?php if ($authoring_tools || $course_types): ?>
                <div id="acf-article-authoring_features" class="column is-one-third">
                    <article>
                        <div class="media is-align-items-center">
                            <div class="media-left">
                                <figure class="image is-48x48 is-align-items-center is-flex">
                                    <img src="http://designtool.cobblestonelearning.com/wp-content/uploads/2024/08/Authoring-Features.png"
                                        alt="Placeholder image">
                                </figure>
                            </div>
                            <div class="media-content">
                                <h3 class="title is-4">Authoring Features</h3>
                                <div class="subtitle is-6">
                                    <?php if ($authoring_tools): ?>
                                        <div class="is-inline" data-acf-field-key="field_6695afded60f7"
                                            data-acf-field-type="flexible_content" data-acf-flexible-content="about_programme"
                                            data-acf-layout="authoring_tools">
                                            <?php echo generate_authoring_features_subtitle($authoring_tools, []); ?>
                                        </div>
                                    <?php endif; ?>
                                    <?php if ($course_types): ?>
                                        <div class="is-inline" data-acf-field-key="field_6695afdee03d6"
                                            data-acf-field-type="select" data-acf-flexible-content="about_programme"
                                            data-acf-layout="course_type">
                                            <?php echo generate_authoring_features_subtitle([], $course_types); ?>
                                        </div>
                                    <?php endif; ?>
                                    <?php
                                    global $total_learning_time_string;

                                    if (!empty($total_learning_time_string)) {
                                        echo '<div class="total-learning-time">Total Learning Time: ' . esc_html($total_learning_time_string) . '</div>';
                                    }
                                    ?>
                                </div>
                            </div>
                        </div>
                    </article>
                </div>
            <?php endif; ?>
        </div>
    </div>
</section>