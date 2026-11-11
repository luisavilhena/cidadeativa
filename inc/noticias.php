<?php
/**
 * Listagem de Notícias (home.php, na página de posts do site: "Blog Ativa").
 *
 * Mostra os posts das categorias escolhidas em Posts → Listagem de Notícias
 * (por padrão "Uncategorized" e "publicações").
 *
 * Filtros pela URL:
 *   q  → termo de busca
 *   pg → página
 *
 * @package cidade-ativa-3
 */

/**
 * Lê um texto/opção da listagem (Posts → Listagem de Notícias).
 */
function ca3_news_option( $name ) {
	return function_exists( 'carbon_get_theme_option' ) ? carbon_get_theme_option( 'ca3_news_' . $name ) : '';
}

/**
 * URL da listagem (página de posts do site).
 */
function ca3_news_base_url() {
	$page_id = (int) get_option( 'page_for_posts' );

	return $page_id ? get_permalink( $page_id ) : home_url( '/' );
}

/**
 * Estado atual dos filtros, validado.
 */
function ca3_news_state() {
	// phpcs:disable WordPress.Security.NonceVerification.Recommended -- filtros públicos via GET.
	return array(
		'q'  => isset( $_GET['q'] ) ? sanitize_text_field( wp_unslash( $_GET['q'] ) ) : '',
		'pg' => isset( $_GET['pg'] ) ? max( 1, absint( $_GET['pg'] ) ) : 1,
	);
	// phpcs:enable
}

/**
 * URL da listagem com os filtros informados (os vazios são omitidos).
 */
function ca3_news_url( $state, $changes = array() ) {
	$state = array_merge( $state, $changes );
	$args  = array_filter(
		array(
			'q'  => '' !== $state['q'] ? rawurlencode( $state['q'] ) : null,
			'pg' => $state['pg'] > 1 ? $state['pg'] : null,
		)
	);

	return add_query_arg( $args, ca3_news_base_url() );
}

/**
 * Consulta das notícias para o estado atual.
 */
function ca3_news_query( $state ) {
	$per_page   = (int) ca3_news_option( 'per_page' );
	$categories = array_filter( array_map( 'absint', (array) ca3_news_option( 'categories' ) ) );

	$args = array(
		'post_type'           => 'post',
		'post_status'         => 'publish',
		'posts_per_page'      => $per_page > 0 ? $per_page : 12,
		'paged'               => $state['pg'],
		'ignore_sticky_posts' => true,
	);

	if ( $categories ) {
		$args['category__in'] = $categories;
	}

	if ( '' !== $state['q'] ) {
		$args['s'] = $state['q'];
	}

	return new WP_Query( $args );
}
