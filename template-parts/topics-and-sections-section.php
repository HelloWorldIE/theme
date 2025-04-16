<?php

/**
 * Topics and Sections Section Template
 * 
 * This template dynamically fetches and renders the topics and sections.
 * 
 * @package Cobulma
 * @version 1.0
 * @since 1.0
 * 
 * Author: Bryan
 */

// Initialize variables for flexible content
$topics = [];

// Global variable to store total learning time
global $total_learning_time;
$total_learning_time = 0;

// Cache the rows of 'topics' flexible content field
if (have_rows('topics')) {
    while (have_rows('topics')) {
        the_row();
        $topics[] = get_row(true);
    }
}

// Fetch the global list of learning objectives
$learning_objectives = get_field('learning_objectives');

// Function to get featured image URL
function get_featured_image_url($post_id)
{
    return get_the_post_thumbnail_url($post_id, 'full') ?: 'https://placehold.co/96x96/png?text=placeholder';
}

// Convert decimal duration to minutes and seconds
function formatDuration($duration)
{
    $minutes = floor($duration);
    $seconds = round(($duration - $minutes) * 60);
    $output = '';

    if ($minutes > 0) {
        $output .= $minutes . ' min' . ($minutes > 1 ? 's' : '');
    }

    if ($seconds > 0) {
        if ($output)
            $output .= ' '; // Add space if there are minutes before seconds
        $output .= $seconds . ' sec' . ($seconds > 1 ? 's' : '');
    }

    if (!$output) {
        $output = '0 min'; // Fallback for no time
    }

    return $output;
}

foreach ($topics as $topic_index => $topic) {
    $total_learning_time_minutes = 0;
    $total_learning_time_seconds = 0;

    foreach ($topic['sections'] as $section) {
        $section_duration = floatval($section['duration']);
        $minutes = floor($section_duration);
        $seconds = ($section_duration - $minutes) * 60;

        // Accumulate total time in minutes and seconds
        $total_learning_time_minutes += $minutes;
        $total_learning_time_seconds += $seconds;
    }

    // Convert total seconds to minutes and seconds
    $total_learning_time_minutes += floor($total_learning_time_seconds / 60);
    $total_learning_time_seconds = $total_learning_time_seconds % 60;

    // Add to global total learning time
    $total_learning_time += ($total_learning_time_minutes * 60) + $total_learning_time_seconds;
}

// Store total learning time in minutes and seconds as a global string
global $total_learning_time_string;
$total_learning_time_string = formatDuration($total_learning_time / 60);

// Function to generate JavaScript for Sankey chart
function generate_sankey_chart_js($topic_index, $section_index, $section_features)
{
    $data = [];
    $links = [];
    $images = [];
    $experience_titles = [];
    $treatment_titles = [];

    foreach ($section_features as $feature) {
        $learning_experiences = $feature['learning_experiences'];
        $treatments = $feature['treatments'];

        foreach ($learning_experiences as $le) {
            $title = get_the_title($le);
            if (!in_array($title, $experience_titles)) {
                $experience_titles[] = $title;
                $data[] = ['name' => $title];
                $images[] = get_featured_image_url($le);
            }
        }

        foreach ($treatments as $lt) {
            $title = get_the_title($lt);
            if (!in_array($title, $treatment_titles)) {
                $treatment_titles[] = $title;
                $data[] = ['name' => $title];
                $images[] = get_featured_image_url($lt);
            }
        }

        foreach ($learning_experiences as $le) {
            foreach ($treatments as $lt) {
                $links[] = ['source' => get_the_title($le), 'target' => get_the_title($lt), 'value' => 1];
            }
        }
    }

    ob_start();
?>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            var chartDom = document.getElementById("experience-treatments-<?php echo $topic_index; ?>-<?php echo $section_index; ?>");
            var myChart = echarts.init(chartDom);
            var option;

            option = {
                series: {
                    type: "sankey",
                    layout: "none",
                    emphasis: {
                        focus: "adjacency"
                    },
                    label: {
                        show: true,
                        formatter: function(params) {
                            return "{icon|} " + params.name;
                        },
                        rich: {
                            icon: {
                                height: 20,
                                align: "center",
                                backgroundColor: {
                                    image: function(params) {
                                        return <?php echo json_encode($images); ?>[params.dataIndex];
                                    }
                                }
                            }
                        }
                    },
                    nodeWidth: 20,
                    nodeGap: 10,
                    lineStyle: {
                        normal: {
                            color: "source",
                            curveness: 0.5,
                            opacity: 0.6,
                            width: 3
                        }
                    },
                    draggable: false,
                    data: <?php echo json_encode($data); ?>,
                    links: <?php echo json_encode($links); ?>
                }
            };

            option && myChart.setOption(option);

            myChart.on("click", function(params) {
                if (params.componentType === "series") {
                    document.getElementById("modalContent").textContent = `You clicked on ${params.name}`;
                    openModal();
                }
            });

            function openModal() {
                document.getElementById("infoModal").classList.add("is-active");
            }

            function closeModal() {
                document.getElementById("infoModal").classList.remove("is-active");
            }

            document.querySelectorAll(".modal-close, .modal-background").forEach((element) => {
                element.addEventListener("click", closeModal);
            });

            document.addEventListener("keydown", (event) => {
                if (event.key === "Escape") {
                    closeModal();
                }
            });
        });
    </script>
<?php
    return ob_get_clean();
}
?>

<!-- Start Topics and Sections Section -->
<section class="ld-topics-and-sections section">
    <div class="container">
        <h2 class="title">Topics and Sections</h2>
        <p class="subtitle">
            An organised list of the main subjects covered in the course, along with their respective subsections.
            <?php
            global $total_learning_time_string;

            if (!empty($total_learning_time_string)) {
                echo '<div class="has-text-centered"><span class="total-learning-time tag is-primary is-size-6">Total Learning Time: ' . esc_html($total_learning_time_string) . '</span></div>';
            }
            ?>
        </p>
        <div class="ld-topics-sections">
            <div class="columns is-multiline">
                <?php foreach ($topics as $topic_index => $topic):
                    $total_learning_time_minutes = 0;
                    $total_learning_time_seconds = 0;

                    foreach ($topic['sections'] as $section) {
                        $section_duration = floatval($section['duration']);
                        $minutes = floor($section_duration);
                        $seconds = ($section_duration - $minutes) * 60;

                        // Accumulate total time in minutes and seconds
                        $total_learning_time_minutes += $minutes;
                        $total_learning_time_seconds += $seconds;
                    }

                    // Convert total seconds to minutes and seconds
                    $total_learning_time_minutes += floor($total_learning_time_seconds / 60);
                    $total_learning_time_seconds = $total_learning_time_seconds % 60;

                    // Prepare the display time string for the topic
                    $topic_time_display = '';
                    if ($total_learning_time_minutes > 0) {
                        $topic_time_display .= $total_learning_time_minutes . ' min' . ($total_learning_time_minutes > 1 ? 's' : '');
                    }

                    if ($total_learning_time_seconds > 0) {
                        if (!empty($topic_time_display)) {
                            $topic_time_display .= ' ';
                        }
                        $topic_time_display .= round($total_learning_time_seconds) . ' sec' . (round($total_learning_time_seconds) > 1 ? 's' : '');
                    }

                    if (empty($topic_time_display)) {
                        $topic_time_display = '0 min';
                    }
                ?>
                    <div class="column mb-4 is-full topic-<?php echo $topic_index + 1; ?>">
                        <div class="card my-0">
                            <div class="card-content">
                                <div class="columns is-mobile is-multiline is-align-items-center mb-4" data-collapse="topic-<?php echo $topic_index + 1; ?>-content" data-toggle-only="true">
                                    <div class="column is-full-mobile is-two-quarters-tablet is-flex is-align-items-center">
                                        <h3 class="title is-4 m-0 is-flex is-align-items-center">
                                            <span class="tag is-primary mr-3"><?php echo $topic_index + 1; ?></span>
                                            <?php echo esc_html($topic['topict_title']) ?: 'Topic ' . $topic_index + 1; ?>
                                        </h3>
                                        <span
                                            class="tag ml-3 total-time-from-sub-sections"><?php echo $topic_time_display; ?></span>
                                    </div>
                                    <div
                                        class="column learning-experiences-used-in-sub-sections is-one-half-mobile is-one-quarter-tablet is-flex is-align-items-center is-flex-wrap-wrap">
                                        <?php
                                        $learning_experiences = [];
                                        foreach ($topic['sections'] as $section) {
                                            foreach ($section['section_features'] as $feature) {
                                                foreach ($feature['learning_experiences'] as $learning_experience) {
                                                    if (!in_array($learning_experience, $learning_experiences)) {
                                                        $learning_experiences[] = $learning_experience;
                                                        echo '<figure class="image m-1" style="width: 35px; height: 35px; display: inline-block; overflow: hidden; border-radius: 50%;"><img src="' . get_featured_image_url($learning_experience) . '" alt="Learning Experience" style="width: 90px;height: 90px;object-fit: cover;object-position: -16px -11px;"></figure>';
                                                    }
                                                }
                                            }
                                        }
                                        ?>
                                    </div>
                                    <div
                                        class="column learning-objectives-used-in-sub-sections is-one-half-mobile is-one-quarter-tablet has-text-right is-relative">
                                        <?php
                                        $aggregated_objectives = [];

                                        // Aggregate all unique learning objectives across sections
                                        foreach ($topic['sections'] as $section) {
                                            $selected_loids = isset($section['section_learning_objectives']) ? explode(',', $section['section_learning_objectives']) : [];
                                            foreach ($selected_loids as $selected_loid) {
                                                if (!in_array($selected_loid, $aggregated_objectives)) {
                                                    $aggregated_objectives[] = $selected_loid;
                                                }
                                            }
                                        }

                                        // Display the aggregated learning objectives
                                        foreach ($learning_objectives as $index => $objective) {
                                            $selected = in_array($objective['loid'], $aggregated_objectives);
                                        ?>
                                            <span
                                                class="tag is-rounded <?php echo $selected ? 'is-info' : 'op-5'; ?> has-tooltip-arrow has-text-centered has-tooltip-multiline <?php echo $selected ? 'has-tooltip-info' : ''; ?>"
                                                data-tooltip="<?php echo esc_attr($objective['learning_objective']); ?>">
                                                <?php echo $index + 1; ?>
                                            </span>
                                        <?php } ?>
                                        <span class="button is-small badge"
                                            data-collapse="topic-<?php echo $topic_index + 1; ?>-content"
                                            data-open-text="Expand Topic"
                                            data-close-text="Close Topic">Expand</span>

                                    </div>

                                </div>
                                <div class="content topic-text collapsible-content"
                                    data-collapse-target="topic-<?php echo $topic_index + 1; ?>-content">
                                    <?php echo wp_kses_post($topic['topic_text']); ?>
                                    <div class="topic-sections-<?php echo $topic_index + 1; ?> pb-4">
                                        <?php if (isset($topic['sections']) && is_array($topic['sections'])): ?>
                                            <?php foreach ($topic['sections'] as $section_index => $section): ?>
                                                <div
                                                    class="topic-section-<?php echo $topic_index + 1; ?>-<?php echo $section_index + 1; ?> mt-6">
                                                    <div class="columns is-mobile is-multiline is-align-items-center mb-4" data-collapse="topic-<?php echo $topic_index + 1; ?>-section-<?php echo $section_index + 1; ?>-content" data-toggle-only="true">
                                                        <div
                                                            class="column is-full-mobile is-two-quarters-tablet is-flex is-align-items-center ml-6">
                                                            <h4 class="title is-5 m-0 is-flex is-align-items-center">
                                                                <span
                                                                    class="tag is-primary mr-3"><?php echo $topic_index + 1; ?>.<?php echo $section_index + 1; ?></span>
                                                                <?php echo esc_html($section['section_title']) ?: 'Section ' . $topic_index + 1; ?>
                                                            </h4>
                                                            <span
                                                                class="tag ml-3 learning-time"><?php echo formatDuration($section['duration']); ?></span>
                                                        </div>
                                                        <div
                                                            class="column treatments-in-this-section is-one-half-mobile is-one-quarter-tablet is-flex is-align-items-center ml-6-mobile">
                                                            <?php
                                                            foreach ($section['section_features'] as $feature) {
                                                                foreach ($feature['treatments'] as $treatment) {
                                                                    echo '<figure class="image mr-2" style="width: 35px; height: 35px; display: inline-block; overflow: hidden; border-radius: 50%;"><img src="' . get_featured_image_url($treatment) . '" alt="Treatment"  style="width: 90px;height: 90px;object-fit: cover;object-position: -16px -11px;"></figure>';
                                                                }
                                                            }
                                                            ?>
                                                        </div>
                                                        <div
                                                            class="column learning-objectives-selected-in-this-section is-one-half-mobile is-one-quarter-tablet has-text-right is-relative">
                                                            <?php
                                                            // Retrieve and process the selected learning objectives for the section
                                                            $selected_loids = isset($section['section_learning_objectives']) ? explode(',', $section['section_learning_objectives']) : [];

                                                            foreach ($learning_objectives as $index => $objective) {
                                                                $selected = in_array($objective['loid'], $selected_loids);
                                                            ?>
                                                                <span
                                                                    class="tag is-rounded <?php echo $selected ? 'is-info' : 'op-5'; ?> has-tooltip-arrow has-text-centered has-tooltip-multiline <?php echo $selected ? 'has-tooltip-info' : ''; ?>"
                                                                    data-tooltip="<?php echo esc_attr($objective['learning_objective']); ?>">
                                                                    <?php echo $index + 1; ?>
                                                                </span>
                                                            <?php } ?>
                                                            <span class="button is-small badge"
                                                                data-collapse="topic-<?php echo $topic_index + 1; ?>-section-<?php echo $section_index + 1; ?>-content"
                                                                data-open-text="Expand Section"
                                                                data-close-text="Close Section">Expand</span>

                                                        </div>

                                                    </div>
                                                    <div class="content section-text ml-6 collapsible-content" data-collapse-target="topic-<?php echo $topic_index + 1; ?>-section-<?php echo $section_index + 1; ?>-content">
                                                        <?php echo wp_kses_post($section['section_text']); ?>
                                                        <div class="section-learning-experiences-treatments-sankey-chart">
                                                            <h4 class="title is-6 has-text-centered mt-5">Learning Experiences &
                                                                Treatments</h4>
                                                            <div id="experience-treatments-<?php echo $topic_index + 1; ?>-<?php echo $section_index + 1; ?>"
                                                                style="width:100%;min-height:50px;height:auto">Chart loading</div>
                                                            <?php echo generate_sankey_chart_js($topic_index + 1, $section_index + 1, $section['section_features']); ?>
                                                        </div>
                                                    </div>
                                                </div>
                                            <?php endforeach; ?>
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

<!-- Modal Structure -->
<div id="infoModal" class="modal">
    <div class="modal-background"></div>
    <div class="modal-content">
        <div class="box">
            <p id="modalContent">Modal Content</p>
        </div>
    </div>
    <button class="modal-close is-large" aria-label="close"></button>
</div>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        // JavaScript code for modal
        function openModal() {
            document.getElementById("infoModal").classList.add("is-active");
        }

        function closeModal() {
            document.getElementById("infoModal").classList.remove("is-active");
        }

        document.querySelectorAll(".modal-close, .modal-background").forEach((element) => {
            element.addEventListener("click", closeModal);
        });

        document.addEventListener("keydown", (event) => {
            if (event.key === "Escape") {
                closeModal();
            }
        });
    });
</script>