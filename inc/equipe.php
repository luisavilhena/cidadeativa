<?php
/**
 * Equipe e colaboradores (popups da seção "Quem somos" da home).
 *
 *   Membro da equipe (ca-team-member): foto ícone (etiqueta), imagem
 *   destacada (popup), resumo = cargo, texto = descrição, LinkedIn.
 *   Colaborador (ca-collaborator): imagem destacada, cargo, iniciativas
 *   em que colaborou, LinkedIn.
 *
 * @package cidade-ativa-3
 */

/**
 * Cores usadas em sequência nos cards de colaboradores (Figma).
 */
function ca3_team_card_colors() {
	return array( 'azul', 'amarelo', 'verde', 'vermelho' );
}

/**
 * ID do popup de uma pessoa.
 */
function ca3_person_dialog_id( $post_id ) {
	return 'pessoa-' . (int) $post_id;
}

/**
 * Cargo da pessoa: campo próprio, resumo ou texto curto do editor.
 */
function ca3_person_role( $post_id ) {
	$role = function_exists( 'carbon_get_post_meta' ) ? carbon_get_post_meta( $post_id, 'ca3_member_role' ) : '';

	if ( ! $role ) {
		$role = get_post_field( 'post_excerpt', $post_id );
	}
	if ( ! $role && 'ca-collaborator' === get_post_type( $post_id ) ) {
		$role = wp_strip_all_tags( get_post_field( 'post_content', $post_id ) );
	}

	return trim( $role );
}

/**
 * Descrição longa (só membros da equipe; nos colaboradores o texto é o cargo).
 */
function ca3_person_description( $post_id ) {
	if ( 'ca-team-member' !== get_post_type( $post_id ) ) {
		return '';
	}

	return apply_filters( 'the_content', get_post_field( 'post_content', $post_id ) );
}

/**
 * Iniciativas em que a pessoa colaborou (publicadas).
 *
 * @return WP_Post[]
 */
function ca3_person_projects( $post_id ) {
	$projects = array();

	if ( function_exists( 'carbon_get_post_meta' ) ) {
		foreach ( (array) carbon_get_post_meta( $post_id, 'iniciativas' ) as $item ) {
			$project = get_post( isset( $item['id'] ) ? (int) $item['id'] : 0 );
			if ( $project && 'publish' === $project->post_status ) {
				$projects[] = $project;
			}
		}
	}

	return $projects;
}

/**
 * Colaboradores publicados, em ordem alfabética.
 *
 * @return WP_Post[]
 */
function ca3_collaborators() {
	$posts = get_posts(
		array(
			'post_type'   => 'ca-collaborator',
			'post_status' => 'publish',
			'numberposts' => -1,
			'orderby'     => array(
				'menu_order' => 'ASC',
				'title'      => 'ASC',
			),
		)
	);

	return ca3_only_site_language( $posts );
}

/**
 * Remove as traduções (inglês, espanhol...) quando existe o perfil no
 * idioma do site.
 *
 * Os idiomas vêm das tabelas do Polylang (o plugin está desativado, mas os
 * dados continuam no banco): taxonomia "language" (idioma de cada post) e
 * "post_translations" (grupo de traduções: idioma => ID).
 * Posts sem idioma ficam. Se a pessoa não tem perfil no idioma do site,
 * fica uma única versão (a primeira de: idioma do site, en, es...).
 *
 * @param WP_Post[] $posts
 * @return WP_Post[]
 */
function ca3_only_site_language( $posts ) {
	global $wpdb;

	if ( ! $posts ) {
		return $posts;
	}

	$ids          = wp_list_pluck( $posts, 'ID' );
	$placeholders = implode( ',', array_fill( 0, count( $ids ), '%d' ) );

	// Idioma e grupo de traduções de cada post.
	$rows = $wpdb->get_results(
		$wpdb->prepare(
			"SELECT tr.object_id, tt.taxonomy, t.slug, tt.description
			FROM {$wpdb->term_relationships} tr
			INNER JOIN {$wpdb->term_taxonomy} tt ON tt.term_taxonomy_id = tr.term_taxonomy_id
			INNER JOIN {$wpdb->terms} t ON t.term_id = tt.term_id
			WHERE tt.taxonomy IN ( 'language', 'post_translations' ) AND tr.object_id IN ( $placeholders )", // phpcs:ignore WordPress.DB.PreparedSQLPlaceholders
			$ids
		)
	);

	if ( ! $rows ) {
		return $posts;
	}

	$languages = array();
	$groups    = array();

	foreach ( $rows as $row ) {
		if ( 'language' === $row->taxonomy ) {
			$languages[ (int) $row->object_id ] = $row->slug;
		} else {
			$group = maybe_unserialize( $row->description );
			if ( is_array( $group ) ) {
				$groups[ (int) $row->object_id ] = array_map( 'intval', $group );
			}
		}
	}

	$site_language = substr( get_locale(), 0, 2 );
	$preference    = array( $site_language, 'pt', 'en', 'es' );
	$published     = array_flip( $ids );

	return array_values(
		array_filter(
			$posts,
			function ( $post ) use ( $languages, $groups, $preference, $published ) {
				$id = $post->ID;

				if ( ! isset( $languages[ $id ], $groups[ $id ] ) ) {
					return true;
				}

				// Fica só a versão preferida entre as traduções publicadas.
				$group = $groups[ $id ];
				foreach ( $preference as $language ) {
					if ( isset( $group[ $language ] ) && isset( $published[ $group[ $language ] ] ) ) {
						return $group[ $language ] === $id;
					}
				}

				return true;
			}
		)
	);
}

/**
 * Pessoa da equipe ligada a uma etiqueta da home.
 */
function ca3_tag_member( $person ) {
	$items = isset( $person['membro'] ) ? (array) $person['membro'] : array();
	$id    = isset( $items[0]['id'] ) ? (int) $items[0]['id'] : 0;
	$post  = $id ? get_post( $id ) : null;

	return ( $post && 'publish' === $post->post_status ) ? $post : null;
}
