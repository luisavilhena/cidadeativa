<?php
/**
 * Listagem do Acervo (tipo de conteúdo ca-biblioteca).
 *
 * Filtros pela URL (os mesmos do tema antigo, para manter links):
 *   c   → ID da categoria
 *   q   → termo de busca
 *   ord → 1 = mais antigos; vazio = mais recentes
 *   pg  → página
 *
 * @package cidade-ativa-3
 */

define( 'CA3_ACERVO_TEMPLATE', 'page-templates/biblioteca.php' );

/**
 * Lê um campo do Carbon Fields da página do Acervo.
 */
function ca3_acervo_field( $name, $page_id = null ) {
	if ( ! function_exists( 'carbon_get_post_meta' ) ) {
		return '';
	}

	return carbon_get_post_meta( $page_id ? $page_id : get_queried_object_id(), 'ca3_acervo_' . $name );
}

/**
 * Categorias usadas como filtro (filhas da categoria escolhida no admin).
 */
function ca3_acervo_categories( $page_id = null ) {
	$parent = (int) ca3_acervo_field( 'parent_category', $page_id );

	if ( ! $parent ) {
		return array();
	}

	return get_terms(
		array(
			'taxonomy'   => 'category',
			'parent'     => $parent,
			'hide_empty' => true,
		)
	);
}

/**
 * Estado atual dos filtros, validado.
 */
function ca3_acervo_state( $page_id = null ) {
	$category_ids = wp_list_pluck( ca3_acervo_categories( $page_id ), 'term_id' );

	// phpcs:disable WordPress.Security.NonceVerification.Recommended -- filtros públicos via GET.
	$category = isset( $_GET['c'] ) ? absint( $_GET['c'] ) : 0;
	$search   = isset( $_GET['q'] ) ? sanitize_text_field( wp_unslash( $_GET['q'] ) ) : '';
	$oldest   = isset( $_GET['ord'] ) && '1' === $_GET['ord'];
	$page     = isset( $_GET['pg'] ) ? max( 1, absint( $_GET['pg'] ) ) : 1;
	// phpcs:enable

	return array(
		'c'   => in_array( $category, $category_ids, true ) ? $category : 0,
		'q'   => $search,
		'ord' => $oldest ? 1 : 0,
		'pg'  => $page,
	);
}

/**
 * URL da listagem com os filtros informados (os vazios são omitidos).
 */
function ca3_acervo_url( $state, $changes = array() ) {
	$state = array_merge( $state, $changes );
	$args  = array_filter(
		array(
			'c'   => $state['c'] ? $state['c'] : null,
			'q'   => '' !== $state['q'] ? $state['q'] : null,
			'ord' => $state['ord'] ? 1 : null,
			'pg'  => $state['pg'] > 1 ? $state['pg'] : null,
		)
	);

	return add_query_arg( array_map( 'rawurlencode', $args ), get_permalink( get_queried_object_id() ) );
}

/**
 * Consulta dos itens do acervo para o estado atual.
 */
function ca3_acervo_query( $state, $page_id = null ) {
	$per_page = (int) ca3_acervo_field( 'per_page', $page_id );

	$args = array(
		'post_type'      => 'ca-biblioteca',
		'post_status'    => 'publish',
		'posts_per_page' => $per_page > 0 ? $per_page : 9,
		'paged'          => $state['pg'],
		'orderby'        => 'date',
		'order'          => $state['ord'] ? 'ASC' : 'DESC',
	);

	if ( $state['c'] ) {
		$args['cat'] = $state['c'];
	} else {
		$parent = (int) ca3_acervo_field( 'parent_category', $page_id );
		if ( $parent ) {
			$args['cat'] = $parent; // Inclui as categorias filhas.
		}
	}

	if ( '' !== $state['q'] ) {
		$args['s'] = $state['q'];
	}

	return new WP_Query( $args );
}

/**
 * Categoria do item exibida no card (a primeira filha da categoria do Acervo).
 */
function ca3_acervo_item_category( $post_id, $page_id = null ) {
	$parent = (int) ca3_acervo_field( 'parent_category', $page_id );

	foreach ( get_the_category( $post_id ) as $term ) {
		if ( ! $parent || (int) $term->parent === $parent ) {
			return $term->name;
		}
	}

	return '';
}
