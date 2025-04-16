<?php
/**
 * Topics and Sections Section Template (Expanded) with Inline Commenting
 * 
 * This template dynamically fetches and renders the simplified topics and subtopics with no collapse functionality.
 * 
 * @package Cobulma
 * @version 1.0
 * @since 1.0
 */

// Initialize variables for flexible content
$topics = [];

// Cache the rows of 'topics' flexible content field
if (have_rows('topics')) {
    while (have_rows('topics')) {
        the_row();
        $topic = [
            'topic_title' => get_sub_field('topic_title'),
            'source_material' => get_sub_field('source_material'),
            'subtopics' => []
        ];

        // Check for subtopics
        if (have_rows('subtopics')) {
            while (have_rows('subtopics')) {
                the_row();
                $subtopic = [
                    'subtopic_title' => get_sub_field('subtopic_title'),
                    'source_material' => get_sub_field('source_material')
                ];
                $topic['subtopics'][] = $subtopic;
            }
        }

        $topics[] = $topic;
    }
}
?>

<!-- Start Topics and Sections Section -->
<section class="ld-topics-and-sections section">
    <div class="container">
        <h2 class="title">Sections and Subsections</h2>
        <p class="subtitle">
            An organised list of the main sections covered in the course, along with their respective subsections.
        </p>
        <div class="ld-topics-sections">
            <div class="columns is-multiline">
                <?php foreach ($topics as $topic_index => $topic): ?>
                    <div class="column mb-4 is-full wpdiscuz-comment-area" data-acf-parent-block="topics" data-topic-index="<?php echo $topic_index + 1; ?>">
                        <div class="card my-0">
                            <div class="card-content">
                                <div class="columns is-mobile is-multiline is-align-items-center mb-4">
                                    <div class="column is-full-mobile is-flex is-align-items-center">
                                        <h3 class="title is-4 m-0 is-flex is-align-items-center">
                                            <span class="tag is-primary mr-3"><?php echo $topic_index + 1; ?></span>
                                            <?php echo esc_html($topic['topic_title']) ?: 'Section ' . ($topic_index + 1); ?>
                                        </h3>
                                    </div>
                                </div>
                                <div class="content topic-text">
                                    <?php echo wp_kses_post($topic['source_material']); ?>
                                    
                                    <!-- Render Subtopics if available -->
                                    <div class="topic-subtopics-<?php echo $topic_index + 1; ?> pb-4">
                                        <?php if (!empty($topic['subtopics'])): ?>
                                            <?php foreach ($topic['subtopics'] as $subtopic_index => $subtopic): ?>
                                                <div class="topic-subtopic-<?php echo $topic_index + 1; ?>-<?php echo $subtopic_index + 1; ?> mt-4 wpdiscuz-comment-area" data-acf-parent-block="subtopics" data-topic-index="<?php echo $topic_index + 1; ?>" data-subtopic-index="<?php echo $subtopic_index + 1; ?>">
                                                    <div class="columns is-mobile is-multiline is-align-items-center mb-4">
                                                        <div class="column is-full-mobile is-flex is-align-items-center ml-6">
                                                            <h4 class="title is-5 m-0 is-flex is-align-items-center">
                                                                <span class="tag is-primary mr-3"><?php echo $topic_index + 1; ?>.<?php echo $subtopic_index + 1; ?></span>
                                                                <?php echo esc_html($subtopic['subtopic_title']) ?: 'Subsection ' . ($subtopic_index + 1); ?>
                                                            </h4>
                                                        </div>
                                                    </div>
                                                    <div class="content subtopic-text ml-6">
                                                        <?php echo wp_kses_post($subtopic['source_material']); ?>
                                                    </div>
                                                </div>
                                            <?php endforeach; ?>
                                        <?php else: ?>
                                            <p class="has-text-grey">No subtopics available for this section.</p>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
</section>
<!-- End Topics and Sections Section -->
