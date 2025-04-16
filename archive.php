<?php
/**
 * The template for displaying archive pages
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package Obulma
 */

get_header();
?>

<div id="primary" class="content-area column is-full">
	<main id="main" class="site-main">

		<?php if (have_posts()): ?>

			<header class="hero">
				<div class="hero-body">
					<?php the_archive_title('<h1 class="title is-1">', '</h1>'); ?>
				</div>
			</header><!-- .entry-header -->

			<div class="columns is-multiline">
				<?php
				/* Start the Loop */
				while (have_posts()):
					the_post();

					/*
					 * Include the Post-Type-specific template for the content.
					 * If you want to override this in a child theme, then include a file
					 * called content-___.php (where ___ is the Post Type name) and that will be used instead.
					 */
					get_template_part('template-parts/archive-card');
				endwhile;

				obulma_pagination();
		else:
			get_template_part('template-parts/content', 'none');
		endif;
		?>
		</div>
	</main><!-- #main -->
</div><!-- #primary -->

<?php
get_sidebar();
get_footer();
?>
