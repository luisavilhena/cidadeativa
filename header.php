<?php
/**
 * Cabeçalho: logo (catavento + nome) e menu principal.
 * No topo da página ele é maior; ao rolar, diminui e o catavento gira
 * (ver assets/js/header.js e assets/css/base/site-header.css).
 *
 * @package cidade-ativa-3
 */

$site_name = get_bloginfo( 'name' );
?>
<!doctype html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<?php wp_body_open(); ?>
<header class="c-site-header" data-site-header>
	<div class="l-container c-site-header__inner">
		<a class="c-site-header__logo" href="<?php echo esc_url( home_url( '/' ) ); ?>" aria-label="<?php echo esc_attr( $site_name ); ?>">
			<img class="c-site-header__pinwheel" src="<?php echo esc_url( CA3_URI . '/assets/images/logo-catavento.svg' ); ?>" alt="" width="45" height="45">
			<img class="c-site-header__wordmark" src="<?php echo esc_url( CA3_URI . '/assets/images/logo-texto.svg' ); ?>" alt="" width="184" height="22">
		</a>

		<button type="button" class="c-site-header__toggle" aria-expanded="false" aria-controls="menu-principal" data-label-open="<?php echo esc_attr( ca3_chrome_option( 'menu_open' ) ); ?>" data-label-close="<?php echo esc_attr( ca3_chrome_option( 'menu_close' ) ); ?>" aria-label="<?php echo esc_attr( ca3_chrome_option( 'menu_open' ) ); ?>" data-menu-toggle>
			<span class="c-site-header__toggle-bar" aria-hidden="true"></span>
			<span class="c-site-header__toggle-bar" aria-hidden="true"></span>
			<span class="c-site-header__toggle-bar" aria-hidden="true"></span>
		</button>

		<nav class="c-site-header__nav" id="menu-principal" aria-label="<?php echo esc_attr( $site_name ); ?>">
			<button type="button" class="c-site-header__close" aria-label="<?php echo esc_attr( ca3_chrome_option( 'menu_close' ) ); ?>" data-menu-close>
				<span aria-hidden="true"></span>
			</button>
			<?php
			wp_nav_menu(
				array(
					'theme_location' => 'principal',
					'container'      => false,
					'menu_class'     => 'c-site-header__menu',
					'depth'          => 1,
					'fallback_cb'    => false,
				)
			);
			?>
		</nav>
	</div>
</header>
<main class="l-main">
