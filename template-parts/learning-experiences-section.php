<?php
/**
 * Learning Experiences Section Template
 * 
 * Fetches and renders unique learning experiences as cards with a Bulma-based modal
 * that displays ACF fields. Hides empty fields or sections. The modal closes when
 * the background is clicked and disables background scrolling while open.
 */

$unique_learning_experiences = [];
$topics_rows = [];
if (have_rows('topics')):
    while (have_rows('topics')): the_row();
        $topics_rows[] = get_row(true);
    endwhile;
endif;

foreach ($topics_rows as $topic) {
    if (isset($topic['sections']) && is_array($topic['sections'])) {
        foreach ($topic['sections'] as $section) {
            if (isset($section['section_features']) && is_array($section['section_features'])) {
                foreach ($section['section_features'] as $feature) {
                    if (isset($feature['learning_experiences']) && is_array($feature['learning_experiences'])) {
                        foreach ($feature['learning_experiences'] as $learning_experience) {
                            if (!in_array($learning_experience, $unique_learning_experiences)) {
                                $unique_learning_experiences[] = $learning_experience;
                            }
                        }
                    }
                }
            }
        }
    }
}

$learning_experiences_details = [];
foreach ($unique_learning_experiences as $learning_experience_id) {
    $post = get_post($learning_experience_id);
    if ($post) {
        setup_postdata($post);

        $description     = get_field('description', $post->ID);
        $icon            = get_field('icon', $post->ID); // image array
        $when_to_use     = get_field('when_to_use', $post->ID);
        $how_to_create   = get_field('how_to_create', $post->ID);
        $benefit_of_use  = get_field('benefit_of_use', $post->ID);

        $examples_data = [];
        if (have_rows('examples', $post->ID)) {
            while (have_rows('examples', $post->ID)) {
                the_row();
                $examples_data[] = get_sub_field('example_url_embed');
            }
        }

        $learning_experiences_details[] = [
            'title'          => get_the_title($post->ID),
            'content'        => get_the_content(null, false, $post->ID),
            'featured_image' => get_the_post_thumbnail_url($post->ID, 'full'),
            'locations'      => get_field('locations', $post->ID),
            'description'    => $description ?: '',
            'icon'           => $icon,
            'when_to_use'    => $when_to_use ?: '',
            'how_to_create'  => $how_to_create ?: '',
            'benefit_of_use' => $benefit_of_use ?: '',
            'examples'       => $examples_data
        ];

        wp_reset_postdata();
    }
}
?>

<section class="ld-learning-experiences section">
    <div class="container">
        <div data-collapse="item3" data-toggle-only="true">
            <h2 class="title is-flex is-align-items-center">
                Learning Experiences
                <span data-collapse="item3" data-open-text="Expand Section" data-close-text="Close Section"
                      aria-label="Toggle Visibility" class="button ml-auto is-align-self-flex-end">
                    Expand Section
                </span>
            </h2>
            <p class="subtitle">
                Visual and design elements tailored to reflect the client's brand and ensure a consistent learner experience.
            </p>
        </div>
        <div class="columns is-multiline collapsible-content" data-collapse-target="item3">
            <?php foreach ($learning_experiences_details as $experience): ?>
                <div class="column is-one-quarter">
                    <div class="card is-flex is-flex-direction-column is-justify-content-flex-start" style="height: 100%;">
                        <div class="card-image">
                            <figure class="image">
                                <img src="<?php echo esc_url($experience['featured_image'] ?: 'https://placehold.co/960x640/png?text=Learning%20Experience'); ?>"
                                     alt="<?php echo esc_attr($experience['title']); ?>">
                            </figure>
                        </div>
                        <div class="card-content">
                            <h3 class="title is-6"><?php echo esc_html($experience['title']); ?></h3>
                            <div class="content">
                                <p><?php echo wp_kses_post($experience['content']); ?></p>
                            </div>
                        </div>
                        <footer class="card-footer is-align-content-end" style="margin-top: auto;">
                            <?php if ($experience['locations']): ?>
                                <a href="#"
                                   class="card-footer-item"
                                   data-acf-field-key="field_6695b021cc69f"
                                   data-acf-field-type="post_object">
                                    <?php echo esc_html($experience['locations']); ?>
                                </a>
                            <?php endif; ?>
                            <a href="#"
                               class="card-footer-item js-ld-learn-more"
                               data-title="<?php echo esc_attr($experience['title']); ?>"
                               data-description="<?php echo esc_attr($experience['description']); ?>"
                               data-icon="<?php echo esc_url(!empty($experience['icon']['url']) ? $experience['icon']['url'] : ''); ?>"
                               data-when_to_use="<?php echo esc_attr($experience['when_to_use']); ?>"
                               data-how_to_create="<?php echo esc_attr($experience['how_to_create']); ?>"
                               data-benefit_of_use="<?php echo esc_attr($experience['benefit_of_use']); ?>"
                               data-examples="<?php echo esc_attr(json_encode($experience['examples'])); ?>">
                                Learn More
                            </a>
                        </footer>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<!-- Bulma Modal -->
<div id="ld-learn-more-modal" class="modal">
    <div class="modal-background js-ld-modal-close"></div>
    <div class="modal-content">
        <div class="box">
            <button class="modal-close is-large js-ld-modal-close" aria-label="close"></button>

            <figure class="image" id="ld-modal-icon-wrapper" style="display:none;">
                <img id="ld-modal-icon" src="" alt="Icon">
            </figure>

            <h3 class="title is-4" id="ld-modal-title"></h3>
            <p id="ld-modal-description"></p>

            <div id="ld-modal-when-to-use-section" style="display:none;">
                <hr>
                <h4 class="title is-6">When to Use</h4>
                <p id="ld-modal-when-to-use"></p>
            </div>

            <div id="ld-modal-how-to-create-section" style="display:none;">
                <hr>
                <h4 class="title is-6">How to Create</h4>
                <p id="ld-modal-how-to-create"></p>
            </div>

            <div id="ld-modal-benefit-of-use-section" style="display:none;">
                <hr>
                <h4 class="title is-6">Benefit of Use</h4>
                <p id="ld-modal-benefit-of-use"></p>
            </div>

            <div id="ld-modal-examples-section" style="display:none;">
                <hr>
                <h4 class="title is-6" style="display:none;">Examples of Use</h4>
                <div id="ld-modal-examples" style="display:none;"></div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    var modal = document.getElementById('ld-learn-more-modal');
    var modalBg = modal.querySelector('.modal-background.js-ld-modal-close');
    var modalClose = modal.querySelector('.modal-close.js-ld-modal-close');
    var learnMoreButtons = document.querySelectorAll('.js-ld-learn-more');

    function closeModal() {
        modal.classList.remove('is-active');
        document.documentElement.classList.remove('is-clipped');
    }

    [modalBg, modalClose].forEach(function(el) {
        el.addEventListener('click', function() {
            closeModal();
        });
    });

    learnMoreButtons.forEach(function(button) {
        button.addEventListener('click', function(e) {
            e.preventDefault();

            var title         = this.getAttribute('data-title') || '';
            var description   = this.getAttribute('data-description') || '';
            var icon          = this.getAttribute('data-icon') || '';
            var whenToUse     = this.getAttribute('data-when_to_use') || '';
            var howToCreate   = this.getAttribute('data-how_to_create') || '';
            var benefitOfUse  = this.getAttribute('data-benefit_of_use') || '';
            var examplesRaw   = this.getAttribute('data-examples') || '[]';

            var modalTitle                = document.getElementById('ld-modal-title');
            var modalDescription          = document.getElementById('ld-modal-description');
            var modalIconWrapper          = document.getElementById('ld-modal-icon-wrapper');
            var modalIcon                 = document.getElementById('ld-modal-icon');
            var modalWhenToUseSection     = document.getElementById('ld-modal-when-to-use-section');
            var modalWhenToUse            = document.getElementById('ld-modal-when-to-use');
            var modalHowToCreateSection   = document.getElementById('ld-modal-how-to-create-section');
            var modalHowToCreate          = document.getElementById('ld-modal-how-to-create');
            var modalBenefitOfUseSection  = document.getElementById('ld-modal-benefit-of-use-section');
            var modalBenefitOfUse         = document.getElementById('ld-modal-benefit-of-use');
            var modalExamplesSection      = document.getElementById('ld-modal-examples-section');
            var modalExamples             = document.getElementById('ld-modal-examples');

            modalTitle.textContent        = title;
            modalDescription.textContent  = description;

            if (icon) {
                modalIconWrapper.style.display = '';
                modalIcon.src = icon;
            } else {
                modalIconWrapper.style.display = 'none';
                modalIcon.src = '';
            }

            if (whenToUse) {
                modalWhenToUseSection.style.display = '';
                modalWhenToUse.textContent = whenToUse;
            } else {
                modalWhenToUseSection.style.display = 'none';
                modalWhenToUse.textContent = '';
            }

            if (howToCreate) {
                modalHowToCreateSection.style.display = '';
                modalHowToCreate.textContent = howToCreate;
            } else {
                modalHowToCreateSection.style.display = 'none';
                modalHowToCreate.textContent = '';
            }

            if (benefitOfUse) {
                modalBenefitOfUseSection.style.display = '';
                modalBenefitOfUse.textContent = benefitOfUse;
            } else {
                modalBenefitOfUseSection.style.display = 'none';
                modalBenefitOfUse.textContent = '';
            }

            // Clear any existing example content
            while (modalExamples.firstChild) {
                modalExamples.removeChild(modalExamples.firstChild);
            }

            var parsedExamples = [];
            try {
                parsedExamples = JSON.parse(examplesRaw);
            } catch (err) {
                parsedExamples = [];
            }

            if (parsedExamples.length) {
                modalExamplesSection.style.display = '';
                parsedExamples.forEach(function(embedHTML) {
                    var exampleDiv = document.createElement('div');
                    exampleDiv.classList.add('content');
                    exampleDiv.innerHTML = embedHTML;
                    modalExamples.appendChild(exampleDiv);
                });
            } else {
                modalExamplesSection.style.display = 'none';
            }

            modal.classList.add('is-active');
            document.documentElement.classList.add('is-clipped');
        });
    });
});
</script>
