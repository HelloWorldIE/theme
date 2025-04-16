<?php
/**
 * The header for our theme
 *
 * This is the template that displays all of the <head> section and everything up until <div id="content">
 *
 * @package Obulma
 */

?>
<!doctype html>
<html data-theme="light" <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="profile" href="https://gmpg.org/xfn/11">

	<?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
<?php
if ( function_exists( 'wp_body_open' ) ) {
	wp_body_open();
}
?>
<div id="page" class="site">
	<a class="skip-link screen-reader-text" href="#content"><?php esc_html_e( 'Skip to content', 'obulma' ); ?></a>

	<nav class="navbar" role="navigation" aria-label="<?php esc_attr_e( 'Main Navigation', 'obulma' ); ?>">
		<div class="container">
			<div class="navbar-brand">
				<?php the_custom_logo(); ?>

				<a role="button" class="navbar-burger burger" aria-expanded="false" data-target="main-menu">
					<span aria-hidden="true"></span>
					<span aria-hidden="true"></span>
					<span aria-hidden="true"></span>
				</a>
			</div>

			<div id="main-menu" class="navbar-menu">
				<div class="navbar-end">
					<?php
					wp_nav_menu(
						array(
							'theme_location'  => 'menu-1',
							'container'       => '',
							'container_class' => '',
							'container_id'    => '',
							'menu_class'      => '',
							'menu_id'         => '',
							'fallback_cb'     => 'obulma_primary_navigation_fallback',
							'items_wrap'      => '%3$s',
							'depth'           => 2,
							'walker'          => new BulmaWP_Navbar_Walker(),
						)
					);
					?>
				</div>
			</div>
		</div><!-- .container -->
	</nav>

	<div id="content" class="site-content">
		<div class="container">
			<div class="columns">

