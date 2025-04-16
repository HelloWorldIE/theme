<?php
/**
 * The template for displaying all Discovery Documents with Bulma Tabs for viewing and editing.
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/#single-post
 *
 * @package Obulma
 */

get_header();
?>

<div id="primary" class="content-area column is-full">
    <main id="main" class="site-main">

        <?php while (have_posts()) : the_post();

            // Display discovery header within the_content filter
            echo apply_filters('the_content', get_template_part('template-parts/discovery-header', null, array(), true));

            // Check if the user has permission to edit the document
            $can_edit = current_user_can('edit_post', get_the_ID());

            // Determine the active tab based on user permissions
            $active_tab = $can_edit ? 'view' : 'view';
        ?>

            <div class="tabs is-boxed is-centered">
                <ul>
                    <li class="<?php echo ($active_tab === 'view') ? 'is-active' : ''; ?>" data-tab="view">
                        <a>View</a>
                    </li>
                    <?php if ($can_edit): ?>
                        <li class="<?php echo ($active_tab === 'edit') ? 'is-active' : ''; ?>" data-tab="edit">
                            <a>Edit</a>
                        </li>
                    <?php endif; ?>
                </ul>
            </div>

            <div class="tab-content">
                <!-- View Tab Content -->
                <div id="view-tab" class="tab-pane <?php echo ($active_tab === 'view') ? 'is-active' : 'is-hidden'; ?>">
                    <?php
                    // Display the different template parts using apply_filters('the_content', ...)
                    echo apply_filters('the_content', get_template_part('template-parts/discovery-text-area-fields', null, array(), true));
                    echo apply_filters('the_content', get_template_part('template-parts/learning-objectives-section', null, array(), true));
                    echo apply_filters('the_content', get_template_part('template-parts/discovery-topics-subtopics', null, array(), true));
                    ?>
                </div>

                <!-- Edit Tab Content (ACF Extended Form) -->
                <?php if ($can_edit): ?>
                    <div id="edit-tab" class="tab-pane <?php echo ($active_tab === 'edit') ? 'is-active' : 'is-hidden'; ?>">
                        <?php acfe_form('edit-discovery'); ?>
                    </div>
                <?php endif; ?>
            </div>
            <?php
            // If comments are open or we have at least one comment, load up the comment template.
            if (comments_open() || get_comments_number()):
                comments_template();
            endif;
            ?>
        <?php endwhile; // End of the loop. 
        ?>

    </main><!-- #main -->
</div><!-- #primary -->

<?php
get_sidebar();
get_footer();
?>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        const tabs = document.querySelectorAll('.tabs ul li');
        const tabPanes = document.querySelectorAll('.tab-pane');

        tabs.forEach(tab => {
            tab.addEventListener('click', function() {
                const target = tab.getAttribute('data-tab');

                // Remove active classes from all tabs and panes
                tabs.forEach(t => t.classList.remove('is-active'));
                tabPanes.forEach(p => p.classList.add('is-hidden'));

                // Activate the clicked tab and corresponding pane
                tab.classList.add('is-active');
                document.getElementById(`${target}-tab`).classList.remove('is-hidden');
                document.getElementById(`${target}-tab`).classList.add('is-active');
            });
        });
        
        // Initialise wpDiscuz inline commenting if available
        if (typeof WpdiscuzInlineComment !== "undefined") {
            WpdiscuzInlineComment.init();
        }
    });
</script>
