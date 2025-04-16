<?php

/**
 * Custom Archive for Learning Experiences
 * Fully self-contained with Bulma styling and single-category filtering.
 *
 * @package Obulma
 */

get_header();

// Fetch all posts (ensure no pagination)
$query_args = [
    'post_type'      => 'learning-experience',
    'posts_per_page' => -1, // Display all posts
    'orderby'        => 'title', // Sort alphabetically
    'order'          => 'ASC' // A to Z order
];
$learning_experiences = new WP_Query($query_args);

// Fetch only categories that are used in these posts
$used_categories = [];
if ($learning_experiences->have_posts()) {
    while ($learning_experiences->have_posts()) {
        $learning_experiences->the_post();
        foreach (get_the_category() as $category) {
            $used_categories[$category->slug] = $category->name;
        }
    }
    wp_reset_postdata();
}
?>

<div id="learning-experiences-archive" class="content-area column is-full">
    <main id="main" class="site-main">

        <!-- Page Header -->
        <header class="hero">
            <div class="hero-body">
                <h1 class="title is-1">Learning Experiences & Treatments</h1>
            </div>
        </header>

        <!-- Filters Section -->
        <div class="container has-text-centered">
            <input type="text" id="learning-search" class="input" placeholder="Search Experiences & Treatments...">

            <!-- Category Filters -->
            <div class="buttons learning-category-filters is-centered">
                <button class="button is-info" id="reset-filter">Reset</button>
                <?php foreach ($used_categories as $slug => $name) : ?>
                    <button class="button is-light category-filter" data-category="<?php echo esc_attr($slug); ?>">
                        <?php echo esc_html($name); ?>
                    </button>
                <?php endforeach; ?>
            </div>
        </div>

        <!-- Learning Experiences Grid -->
        <div class="columns is-multiline learning-experiences-grid">
            <?php if ($learning_experiences->have_posts()) : ?>
                <?php while ($learning_experiences->have_posts()) : $learning_experiences->the_post(); ?>
                    <?php
                    $post_id = get_the_ID();
                    $categories = get_the_category();
                    $category_classes = implode(' ', array_map(fn($cat) => 'cat-' . $cat->slug, $categories));


                    // Get all ACF fields
                    $description = get_field('description');
                    $when_to_use = get_field('when_to_use');
                    $how_to_create = get_field('how_to_create');
                    $benefit_of_use = get_field('benefit_of_use');
                    $examples = get_field('examples');
                    $learning_designs = get_field('learning_designs');
                    ?>
                    <article id="post-<?php echo $post_id; ?>"
                        class="column is-half learning-card <?php echo esc_attr($category_classes); ?>"
                        data-category="<?php echo esc_attr(implode(' ', wp_list_pluck($categories, 'slug'))); ?>">


                        <div class="card">
                            <div class="card-image">
                                <?php if (has_post_thumbnail()) : ?>
                                    <figure class="image is-16by9">
                                        <?php the_post_thumbnail('full'); ?>
                                    </figure>
                                <?php endif; ?>
                            </div>

                            <div class="card-content">
                                <h2 class="title is-5 click-to-copy" data-title="<?php the_title(); ?>">
                                    <?php the_title(); ?>
                                </h2>

                                <?php if ($description) : ?>
                                    <p><?php echo esc_html($description); ?></p>
                                <?php endif; ?>

                                <div class="tags">
                                    <?php foreach ($categories as $category) : ?>
                                        <span class="tag is-info category-filter"
                                            data-category="<?php echo esc_attr($category->slug); ?>">
                                            <?php echo esc_html($category->name); ?>
                                        </span>
                                    <?php endforeach; ?>
                                </div>
                            </div>

                            <div class="card-footer">
                                <?php if ($when_to_use) : ?>
                                    <div class="card-footer-item is-block">
                                        <h3>When to Use</h3><?php echo esc_html($when_to_use); ?>
                                    </div>
                                <?php endif; ?>

                                <?php if ($how_to_create) : ?>
                                    <div class="card-footer-item is-block">
                                        <h3>How to Create</h3><?php echo esc_html($how_to_create); ?>
                                    </div>
                                <?php endif; ?>

                                <?php if ($benefit_of_use) : ?>
                                    <div class="card-footer-item is-block">
                                        <h3>Benefit</h3><?php echo esc_html($benefit_of_use); ?>
                                    </div>
                                <?php endif; ?>
                            </div>

                            <?php if (current_user_can('edit_others_posts') && $learning_designs) : ?>
                                <div class="card-content">
                                    <strong>Related Learning Designs:</strong>
                                    <ul>
                                        <?php foreach ($learning_designs as $design) : ?>
                                            <li><a href="<?php echo get_permalink($design->ID); ?>">
                                                    <?php echo esc_html($design->post_title); ?>
                                                </a></li>
                                        <?php endforeach; ?>
                                    </ul>
                                </div>
                            <?php endif; ?>

                        </div>
                    </article>
                <?php endwhile;
                wp_reset_postdata(); ?>
            <?php else : ?>
                <p>No learning experiences found.</p>
            <?php endif; ?>
        </div>

    </main>
</div>

<style>
    /* --- Learning Experiences Specific Styles --- */
    #learning-search {
        width: 50%;
        margin-bottom: 10px;
    }

    .learning-category-filters {
        margin-bottom: 20px;
    }

    .learning-card {
        display: block;
    }

    /* Click-to-Copy Feedback */
    .title.is-5 {
        cursor: pointer;
    }

    .title.is-5.copied {
        color: green;
    }
</style>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        const searchInput = document.getElementById("learning-search");
        const resetButton = document.getElementById("reset-filter");
        const categoryButtons = document.querySelectorAll(".category-filter");
        const learningCards = document.querySelectorAll(".learning-card");

        function filterExperiences() {
            const searchTerm = searchInput.value.toLowerCase();
            const activeCategory = document.querySelector(".category-filter.is-success")?.dataset.category || null;

            learningCards.forEach(card => {
                const textContent = card.innerText.toLowerCase();
                const categories = card.dataset.category.split(" ");

                const matchesCategory = !activeCategory || categories.includes(activeCategory);
                const matchesSearch = searchTerm === "" || textContent.includes(searchTerm);

                card.style.display = matchesCategory && matchesSearch ? "block" : "none";
            });

            resetButton.style.display = "inline-flex";
        }

        searchInput.addEventListener("input", filterExperiences);

        categoryButtons.forEach(button => {
            button.addEventListener("click", function() {
                categoryButtons.forEach(btn => btn.classList.remove("is-success"));
                this.classList.add("is-success");
                filterExperiences();
            });
        });

        resetButton.addEventListener("click", function() {
            categoryButtons.forEach(btn => btn.classList.remove("is-success"));
            searchInput.value = "";
            filterExperiences();
        });

        document.querySelectorAll(".click-to-copy").forEach(title => {
            title.addEventListener("click", function() {
                navigator.clipboard.writeText(this.dataset.title);
                this.classList.add("copied");
                setTimeout(() => this.classList.remove("copied"), 2000);
            });
        });
    });
</script>

<?php get_footer(); ?>