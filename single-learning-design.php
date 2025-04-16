<?php
/**
 * The template for displaying all single learning designs
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/#single-post
 *
 * @package Obulma
 */

get_header();

?>

<div id="primary" class="content-area column is-full">
    

    <?php if (is_user_logged_in() && current_user_can('edit_post', get_the_ID())): ?>
        <a class="button"
            href="<?php echo esc_url(add_query_arg('ldid', get_the_ID(), home_url('/edit-learning-design'))); ?>">Edit
            Learning Design</a>
    <?php endif; ?>

    <main id="main" class="site-main" style="">

        <?php
        while (have_posts()):
            the_post();
            ?>

            <?php
            get_template_part('template-parts/header-section');
            get_template_part('template-parts/about-programme-section');
            get_template_part('template-parts/look-and-feel-section');
            get_template_part('template-parts/learning-experiences-section');
            get_template_part('template-parts/learning-objectives-section');
            get_template_part('template-parts/topics-and-sections-section');
            ?>

            <?php
            // If comments are open or we have at least one comment, load up the comment template.
            if (comments_open() || get_comments_number()):
                comments_template();
            endif;
            ?>

            <?php

            if (is_user_logged_in() && current_user_can('edit_post', get_the_ID())):
                the_post_navigation(
                    array(
                        'prev_text' => '<span class="icon"><i class="fas fa-arrow-left"></i></span>' . esc_html__('Previous', 'obulma'),
                        'next_text' => esc_html__('Next', 'obulma') . '<span class="icon"><i class="fas fa-arrow-right"></i></span>',
                    )
                );
            endif;


        endwhile; // End of the loop.
        ?>

    </main><!-- #main -->
</div><!-- #primary -->

<script>
    // JavaScript to force light mode

    function copyToClipboard(text) {
        navigator.clipboard.writeText(text).then(
            function () {
                alert("Copied to clipboard: " + text);
            },
            function (err) {
                alert("Failed to copy: ", err);
            }
        );
    }

    document.addEventListener("DOMContentLoaded", function () {
        const colourTags = document.querySelectorAll(".colour-tags .tag");

        colourTags.forEach(tag => {
            const bgColor = window.getComputedStyle(tag).backgroundColor;
            const textColor = getContrastYIQ(bgColor);
            tag.style.color = textColor;
        });

        function getContrastYIQ(rgb) {
            const [r, g, b] = rgb.match(/\d+/g).map(Number);
            const yiq = ((r * 299) + (g * 587) + (b * 114)) / 1000;
            return (yiq >= 128) ? 'black' : 'white';
        }
    });

</script>

<?php
get_sidebar();
get_footer();
