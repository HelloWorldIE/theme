<?php

/**
 * Display Plain Textarea Fields as Cards with Inline Commenting
 * 
 * This template renders all textarea fields as cards with inline commenting enabled.
 * 
 * @package Cobulma
 * @version 1.0
 * @since 1.0
 */


// Get the current post ID
$post_id = get_the_ID();

// Define the list of textarea fields to include (excluding topics and subtopics)
$textarea_fields = [
    ['key' => 'field_66f6769c46c10', 'label' => 'What is the overall goal of the training', 'name' => 'goal'],
    ['key' => 'field_66f676eb46c11', 'label' => 'How will success be measured?', 'name' => 'success'],
    ['key' => 'field_66f6771546c12', 'label' => 'How is this area trained or managed today?', 'name' => 'managed'],
    ['key' => 'field_66f6773d46c13', 'label' => 'Why is this initiative/project being rolled out now?', 'name' => 'initiative'],
    ['key' => 'field_66f6776f46c14', 'label' => 'What change would you like to see?', 'name' => 'change', 'instructions' => 'What would have the biggest impact, or affect the most people if it could be changed?'],
    ['key' => 'field_66f6779a46c15', 'label' => 'What do people need to do that they are not doing now? What makes it difficult for them to do it?', 'name' => 'obstacles', 'instructions' => 'Consider processes, materials, environment, people, tech, and finance as possible obstacles.'],
    ['key' => 'field_66f677f146c16', 'label' => 'Are there any specific features you would like to see in the course?', 'name' => 'features', 'instructions' => 'Consider processes, materials, environment, people, tech, and finance as possible obstacles.'],
    ['key' => 'field_66f6780e46c18', 'label' => 'What is the current deadline for this project? What is the reason for this deadline?', 'name' => 'deadline'],
    ['key' => 'field_66f6782646c19', 'label' => 'Who is the target audience for this course?', 'name' => 'audience', 'instructions' => 'If it is for more than one group (e.g., managers and employees or different locations), are there any topics that need to be treated differently or expanded on for any of the groups?'],
];

?>


<div class="columns is-multiline">
    <?php
    // Loop through each field and render as a card
    foreach ($textarea_fields as $field) {
        // Get the field value
        $field_value = get_field($field['name']);
        if ($field_value):
            // Create a unique ID by combining the post ID and field key
            $unique_id = $post_id . '_' . $field['key'];
            // Define the question text that will be shown for inline commenting
            $question_text = !empty($field['instructions']) ? $field['instructions'] : 'What are your thoughts on this?';
    ?>
            <div class="column is-half">
                <div class="card my-0">
                    <div class="card-content">
                        <div class="media is-align-items-center">
                            <div class="media-content">
                                <h3 class="title is-4"><?php echo esc_html($field['label']); ?></h3>
                                <?php if (!empty($field['instructions'])): ?>
                                    <div class="subtitle is-6">
                                        <em class="is-size-7"><?php echo esc_html($field['instructions']); ?></em>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                        <div class="content">
                            <div class="acf-field-content">
                                <p><?php echo nl2br(esc_html($field_value)); ?></p>
                                <!-- Manually generate the inline feedback/commenting form -->
                                
                                <!-- End of inline comment form -->
                            </div>
                        </div>
                    </div>
                </div>
            </div>
    <?php
        endif;
    }
    ?>
</div>
