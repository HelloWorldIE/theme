<?php

/**
 * Look and Feel Section Template
 * 
 * This template dynamically fetches and renders the 'look_and_feel' flexible content fields.
 * 
 * @package Cobulma
 * @version 1.0
 * @since 1.0
 * 
 * Author: Bryan
 */

// Initialize variables for flexible content
$programme_design = '';
$colour_scheme = [];
$design_imagery = '';

// Cache the rows of 'about_programme' flexible content field
$about_programme_rows = [];
if (have_rows('about_programme')) {
    while (have_rows('about_programme')) {
        the_row();
        $about_programme_rows[] = get_row(true);
    }
}

// Process the cached rows for look and feel
foreach ($about_programme_rows as $row) {
    if ($row['acf_fc_layout'] == 'look_and_feel') {
        if (isset($row['programme_design'])) {
            $programme_design = $row['programme_design'];
        }
        if (isset($row['colour_scheme'])) {
            $colour_scheme = $row['colour_scheme'];
        }
        if (isset($row['design_imagery'])) {
            $design_imagery = $row['design_imagery'];
        }
    }
}
?>

<!-- Start Look and Feel Section -->
<section class="ld-look-and-feel section">
    <div class="container">
        <div data-collapse="item2" data-toggle-only="true">
            <h2 class="title is-flex is-align-items-center">
                Look and Feel
                <span data-collapse="item2" data-open-text="Expand Section" data-close-text="Close Section"
                    aria-label="Toggle Visibility" class="button ml-auto is-align-self-flex-end">Expand Section</span>
            </h2>
            <p class="subtitle">
                Visual and design elements tailored to reflect the client's brand and ensure a consistent learner
                experience.
            </p>
        </div>
        <div class="columns is-multiline collapsible-content" data-collapse-target="item2">
            <?php if ($programme_design): ?>
                <div class="column is-full">
                    <div class="card my-0">
                        <div class="card-content">
                            <div class="media is-align-items-center">
                                <div class="media-left">
                                    <figure class="image is-48x48 ">
                                        <img src="http://designtool.cobblestonelearning.com/wp-content/uploads/2024/08/Programme-Design.png"
                                            alt="Placeholder image" />
                                    </figure>
                                </div>
                                <div class="media-content">
                                    <h3 class="title is-4">Programme Design</h3>
                                    <div class="subtitle is-6">
                                        The design outline for the learning programme.
                                    </div>
                                </div>
                            </div>
                            <div class="content"><?php if ($colour_scheme): ?>

                                    <div class="colour-tags" data-acf-field-key="field_6695afdee04d2"
                                        data-acf-field-type="repeater" data-acf-flexible-content="about_programme"
                                        data-acf-layout="look_and_feel">
                                        <?php foreach ($colour_scheme as $colour): ?>
                                            <span class="tag mr-2 mb-2"
                                                style="background-color: <?php echo esc_attr($colour['colour']); ?>; color: <?php echo esc_attr($colour['text_color']); ?>;"
                                                onclick="copyToClipboard('<?php echo esc_attr($colour['colour']); ?>')">
                                                <?php echo esc_html($colour['purpose']); ?> <span
                                                    style="margin-left: 5px;"><?php echo esc_html($colour['colour']); ?></span>
                                            </span>
                                        <?php endforeach; ?>
                                    </div>
                                <?php endif; ?>
                                <div class="look-and-feel" data-acf-field-key="field_6695afdee048e"
                                    data-acf-field-type="wysiwyg" data-acf-flexible-content="about_programme"
                                    data-acf-layout="look_and_feel">
                                    <?php echo $programme_design; ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endif; ?>

            <?php if ($design_imagery): ?>
                <div class="column is-full">
                    <div class="card my-0">
                        <div class="card-content">
                            <div class="media is-align-items-center">
                                <div class="media-left">
                                    <figure class="image is-48x48">
                                        <img src="http://designtool.cobblestonelearning.com/wp-content/uploads/2024/08/Design-Imagery.png"
                                            alt="Placeholder image" />
                                    </figure>
                                </div>
                                <div class="media-content">
                                    <h3 class="title is-4">Design Imagery</h3>
                                    <div class="subtitle is-6">A collection of visual materials to illustrate the design
                                        elements
                                        and
                                        concepts.</div>
                                </div>
                            </div>
                            <div class="content" data-acf-field-key="field_6695afdee0510" data-acf-field-type="gallery"
                                data-acf-flexible-content="about_programme" data-acf-layout="look_and_feel">
                                <div class="ld-look-and-feel-images">
                                    <div class="swiper mySwiper" id="mySwiper">
                                        <div class="swiper-wrapper">
                                            <?php foreach ($design_imagery as $image): ?>
                                                <div class="swiper-slide">
                                                    <div class="box"><img class="image"
                                                            src="<?php echo esc_url($image['url']); ?>"
                                                            alt="<?php echo esc_attr($image['alt']); ?>"></div>


                                                </div>
                                            <?php endforeach; ?>
                                        </div>
                                        <div class="swiper-pagination"></div>
                                        <div class="swiper-button-next"></div>
                                        <div class="swiper-button-prev"></div>
                                    </div>
                                    <div class="has-text-right">
                                        <button class="fullscreen-btn button enter tag" id="enter-fullscreen-btn">Enter Full
                                            Screen</button>

                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </div>
</section>
<!-- End Look and Feel Section -->

<script src="https://unpkg.com/swiper/swiper-bundle.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        var swiper = new Swiper(".mySwiper", {
            effect: "coverflow",
            grabCursor: true,
            centeredSlides: true,
            slidesPerView: "auto",
            coverflowEffect: {
                rotate: 50,
                stretch: 0,
                depth: 100,
                modifier: 1,
                slideShadows: true
            },
            pagination: {
                el: ".swiper-pagination"
            },
            navigation: {
                nextEl: ".swiper-button-next",
                prevEl: ".swiper-button-prev"
            }
        });

        const enterFullscreenBtn = document.getElementById('enter-fullscreen-btn');

        const swiperContainer = document.getElementById('mySwiper');

        function enterFullscreen() {
            if (swiperContainer.requestFullscreen) {
                swiperContainer.requestFullscreen();
            } else if (swiperContainer.mozRequestFullScreen) { // Firefox
                swiperContainer.mozRequestFullScreen();
            } else if (swiperContainer.webkitRequestFullscreen) { // Chrome, Safari and Opera
                swiperContainer.webkitRequestFullscreen();
            } else if (swiperContainer.msRequestFullscreen) { // IE/Edge
                swiperContainer.msRequestFullscreen();
            }
            swiperContainer.classList.add('fullscreen');
            swiper.update();
        }



        enterFullscreenBtn.addEventListener('click', enterFullscreen);

        document.addEventListener('fullscreenchange', function() {
            if (!document.fullscreenElement) {
                swiperContainer.classList.remove('fullscreen');
                swiper.update();
            } else {
                swiper.update();
            }
        });
    });
</script>