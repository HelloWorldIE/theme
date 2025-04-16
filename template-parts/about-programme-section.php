<?php

/**
 * About Programme Section Template
 * 
 * This template dynamically fetches and renders the 'about_programme' flexible content fields.
 * 
 * @package Cobulma
 * @version 1.0
 * @since 1.0
 * 
 * Author: Bryan
 */

// Initialize variables for flexible content
$programme_goals = '';
$module_goals = '';
$target_learners = '';
$measuring_success = '';

// Cache the rows of 'about_programme' flexible content field
$about_programme_rows = [];
if (have_rows('about_programme')) {
    while (have_rows('about_programme')) {
        the_row();
        $about_programme_rows[] = get_row(true);
    }
}

// Process the cached rows for about programme
foreach ($about_programme_rows as $row) {
    if ($row['acf_fc_layout'] == 'programme_goals') {
        ob_start();
        echo $row['programme_goals'];
        $programme_goals = ob_get_clean();
    } elseif ($row['acf_fc_layout'] == 'module_goals') {
        ob_start();
        echo $row['module_goals'];
        $module_goals = ob_get_clean();
    } elseif ($row['acf_fc_layout'] == 'target_learners') {
        ob_start();
        echo $row['target_learners'];
        $target_learners = ob_get_clean();
    } elseif ($row['acf_fc_layout'] == 'measuring_success') {
        ob_start();
        echo $row['how_success_will_be_measured'];
        $measuring_success = ob_get_clean();
    }
}
?>

<section class="ld-overview section">
    <div class="container">
        <div data-collapse="item1" data-toggle-only="true">
            <h2 class="title is-flex is-align-items-center">
                Programme Overview
                <span data-collapse="item1" data-open-text="Expand Section" data-close-text="Close Section"
                    aria-label="Toggle Visibility" class="button ml-auto is-align-self-flex-end">Expand Section</span>
            </h2>
            <p class="subtitle">This section provides a comprehensive introduction to the overall learning programme,
                outlining its scope and purpose.</p>
        </div>

        <div class="columns is-multiline ld-overview-sections-grid collapsible-content" data-collapse-target="item1"
            id="programme-goals-content">
            <?php if ($programme_goals): ?>
                <div class="column is-half">
                    <article class="card my-0">
                        <div class="card-content">
                            <div class="media is-align-items-center">
                                <div class="media-left">
                                    <figure class="image is-48x48">
                                        <img src="http://designtool.cobblestonelearning.com/wp-content/uploads/2024/08/Programme-Goals.png"
                                            alt="Placeholder image">
                                    </figure>
                                </div>
                                <div class="media-content">
                                    <h3 class="title is-4">Programme Goals</h3>
                                    <div class="subtitle is-6">The overall objectives and key outcomes intended for
                                        the entire learning programme.</div>
                                </div>
                            </div>
                            <div class="content" data-acf-field-key="field_6695afdee037e" data-acf-field-type="wysiwyg"
                                data-acf-flexible-content="about_programme" data-acf-layout="programme_goals">
                                <?php echo $programme_goals; ?>
                            </div>
                        </div>
                    </article>
                </div>
            <?php endif; ?>

            <?php if ($module_goals): ?>
                <div class="column is-half">
                    <article class="card my-0">
                        <div class="card-content">
                            <div class="media is-align-items-center">
                                <div class="media-left">
                                    <figure class="image is-48x48">
                                        <img src="http://designtool.cobblestonelearning.com/wp-content/uploads/2024/08/Module-Goals.png"
                                            alt="Placeholder image">
                                    </figure>
                                </div>
                                <div class="media-content">
                                    <h3 class="title is-4">Module Goals</h3>
                                    <div class="subtitle is-6">Specific aims and outcomes for each individual module
                                        within the programme.</div>
                                </div>
                            </div>
                            <div class="content" data-acf-field-key="field_6695afdee0585" data-acf-field-type="wysiwyg"
                                data-acf-flexible-content="about_programme" data-acf-layout="module_goals">
                                <?php echo $module_goals; ?>
                            </div>
                        </div>
                    </article>
                </div>
            <?php endif; ?>

            <?php if ($target_learners): ?>
                <div class="column is-half">
                    <article class="card my-0">
                        <div class="card-content">
                            <div class="media is-align-items-center">
                                <div class="media-left">
                                    <figure class="image is-48x48">
                                        <img src="http://designtool.cobblestonelearning.com/wp-content/uploads/2024/08/Target-Learners.png"
                                            alt="Placeholder image">
                                    </figure>
                                </div>
                                <div class="media-content">
                                    <h3 class="title is-4">Target Learners</h3>
                                    <div class="subtitle is-6">The intended audience and their characteristics,
                                        detailing who the programme is designed for.</div>
                                </div>
                            </div>
                            <div class="content" data-acf-field-key="field_6695afdee05c4" data-acf-field-type="wysiwyg"
                                data-acf-flexible-content="about_programme" data-acf-layout="target_learners">
                                <?php echo $target_learners; ?>
                            </div>
                        </div>
                    </article>
                </div>
            <?php endif; ?>

            <?php if ($measuring_success): ?>
                <div class="column is-half">
                    <article class="card my-0">
                        <div class="card-content">
                            <div class="media is-align-items-center">
                                <div class="media-left">
                                    <figure class="image is-48x48">
                                        <img src="http://designtool.cobblestonelearning.com/wp-content/uploads/2024/08/Measuring-Success.png"
                                            alt="Placeholder image">
                                    </figure>
                                </div>
                                <div class="media-content">
                                    <h3 class="title is-4">Measuring Success</h3>
                                    <div class="subtitle is-6">Criteria and methods used to assess the effectiveness
                                        and impact of the programme.</div>
                                </div>
                            </div>
                            <div class="content" data-acf-field-key="field_6695afdee054b" data-acf-field-type="wysiwyg"
                                data-acf-flexible-content="about_programme" data-acf-layout="measuring_success">
                                <?php echo $measuring_success; ?>
                            </div>
                        </div>
                    </article>
                </div>
            <?php endif; ?>
        </div>

    </div>
</section>