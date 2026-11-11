<?php
/**
 * Cabeçalho e rodapé: menus, opções (Aparência → Cabeçalho e rodapé)
 * e item de menu ativo por tipo de conteúdo.
 *
 * @package cidade-ativa-3
 */

/**
 * Posições de menu.
 */
function ca3_register_menus() {
	register_nav_menus(
		array(
			'principal' => __( 'Menu principal (cabeçalho)', 'cidade-ativa-3' ),
			'rodape'    => __( 'Menu do rodapé', 'cidade-ativa-3' ),
		)
	);
}
add_action( 'after_setup_theme', 'ca3_register_menus' );

/**
 * Lê uma opção do cabeçalho/rodapé.
 */
function ca3_chrome_option( $name ) {
	return function_exists( 'carbon_get_theme_option' ) ? carbon_get_theme_option( 'ca3_chrome_' . $name ) : '';
}

/**
 * E-mail de contato: o do painel, o do tema antigo ou o do Figma.
 */
function ca3_contact_email() {
	$email = ca3_chrome_option( 'contact_email' );

	if ( ! $email && function_exists( 'carbon_get_theme_option' ) ) {
		$email = carbon_get_theme_option( 'ca_contact_email' );
	}
	if ( ! $email ) {
		$email = 'contato@cidadeativa.org';
	}

	return sanitize_email( (string) $email );
}

/**
 * Redes sociais preenchidas (rede + link).
 */
function ca3_social_links() {
	return array_values(
		array_filter(
			(array) ca3_chrome_option( 'social' ),
			function ( $row ) {
				return ! empty( $row['rede'] ) && ! empty( $row['link'] );
			}
		)
	);
}

/**
 * Marca como ativo o item de menu da seção atual:
 *   programas/iniciativas → item com âncora #programas
 *   itens do Acervo       → item da página do Acervo
 *   notícias              → item da página de posts
 */
function ca3_menu_current_section( $classes, $item ) {
	$section_page = 0;
	$anchor       = '';

	if ( is_singular( 'ca-iniciativa' ) ) {
		$anchor = '#programas';
	} elseif ( is_singular( 'ca-biblioteca' ) ) {
		$section_page = ca3_acervo_page_id();
	} elseif ( is_singular( 'post' ) || is_home() ) {
		$section_page = (int) get_option( 'page_for_posts' );
	}

	$matches = ( $section_page && 'page' === $item->object && (int) $item->object_id === $section_page )
		|| ( $anchor && false !== strpos( $item->url, $anchor ) );

	if ( $matches && ! in_array( 'current-menu-item', $classes, true ) ) {
		$classes[] = 'current-menu-item';
	}

	// Links com âncora (ex.: /#programas) só ficam ativos pela regra acima,
	// para não ficarem todos ativos na home.
	if ( ! $matches && false !== strpos( $item->url, '#' ) ) {
		$classes = array_diff( $classes, array( 'current-menu-item', 'current_page_item', 'current-menu-ancestor' ) );
	}

	return $classes;
}
add_filter( 'nav_menu_css_class', 'ca3_menu_current_section', 10, 2 );
