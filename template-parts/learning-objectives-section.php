<?php
/**
 * Learning Objectives Section Template
 * 
 * This template dynamically fetches and renders the learning objectives.
 * 
 * @package Cobulma
 * @version 1.0
 * @since 1.0
 * 
 * Author: Bryan
 */

// Fetch the learning objectives
$learning_objectives = get_field('learning_objectives');
?>

<!-- Start Learning Objectives Section -->
<section class="ld-learning-objectives section">
    <div class="container">
        <h2 class="title">
            Learning Objectives
        </h2>
        <p class="subtitle">
            A list of the specific, measurable goals that learners are expected to achieve by the end of the course.
        </p>

        <div class="columns">
            <div class="column is-full is-overflow-x-auto">
                <?php if ($learning_objectives): ?>
                    <ul class="steps has-content-centered is-short" data-acf-field-key="field_6695b021a22fe"
                        data-acf-field-type="repeater">
                        <?php foreach ($learning_objectives as $index => $objective): ?>
                            <li class="steps-segment" 
                                <?php if (!empty($objective['loid'])): ?>
                                    data-loid="<?php echo esc_attr($objective['loid']); ?>"
                                <?php endif; ?>>
                                <span class="steps-marker is-info"><?php echo $index + 1; ?></span>
                                <div class="steps-content">
                                    <div class="card">
                                        <p class="card-content">
                                            <?php echo esc_html($objective['learning_objective']); ?>
                                        </p>
                                    </div>
                                </div>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                <?php else: ?>
                    <p>No learning objectives have been set for this course yet.</p>
                <?php endif; ?>
            </div>
        </div>
    </div>
</section>
<!-- End Learning Objectives Section -->
