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
	return get_posts(
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
