<?php
/**
 * Tipos de conteúdo: Membro da equipe (ca-team-member) e Colaborador
 * (ca-collaborator), com a taxonomia Status de colaboração.
 * Mesmas chaves e URLs do tema antigo, para manter o conteúdo existente.
 *
 * @package cidade-ativa-3
 */

function ca3_register_equipe_post_types() {
	register_post_type(
		'ca-team-member',
		array(
			'label'         => __( 'Membrx da equipe', 'cidade-ativa-3' ),
			'labels'        => array(
				'name'          => __( 'Membrx da equipe', 'cidade-ativa-3' ),
				'singular_name' => __( 'Membrx da equipe', 'cidade-ativa-3' ),
				'menu_name'     => __( 'Equipe', 'cidade-ativa-3' ),
			),
			'description'   => __( 'Membrxs da equipe da Cidade Ativa', 'cidade-ativa-3' ),
			'menu_icon'     => 'dashicons-universal-access',
			'supports'      => array( 'title', 'editor', 'revisions', 'page-attributes', 'excerpt', 'thumbnail' ),
			'public'        => true,
			'show_in_menu'  => 'cidade-ativa-equipe',
			'show_in_rest'  => true,
			'hierarchical'  => true,
			'has_archive'   => false,
			'capability_type' => 'page',
			'rewrite'       => array( 'slug' => 'equipe' ),
		)
	);

	register_post_type(
		'ca-collaborator',
		array(
			'label'         => __( 'Colaborador', 'cidade-ativa-3' ),
			'labels'        => array(
				'name'          => __( 'Colaborador', 'cidade-ativa-3' ),
				'singular_name' => __( 'Colaborador', 'cidade-ativa-3' ),
				'menu_name'     => __( 'Colaboradorxs', 'cidade-ativa-3' ),
			),
			'description'   => __( 'Colaboradorxs da Cidade Ativa', 'cidade-ativa-3' ),
			'menu_icon'     => 'dashicons-universal-access',
			'supports'      => array( 'title', 'editor', 'revisions', 'page-attributes', 'excerpt', 'thumbnail' ),
			'public'        => true,
			'show_in_menu'  => 'cidade-ativa-equipe',
			'show_in_rest'  => true,
			'hierarchical'  => true,
			'has_archive'   => false,
			'capability_type' => 'page',
			'rewrite'       => array( 'slug' => 'equipe' ),
		)
	);

	register_taxonomy(
		'ca-collaborator-status',
		array( 'ca-collaborator' ),
		array(
			'labels'            => array(
				'name'          => __( 'Status de colaboração', 'cidade-ativa-3' ),
				'search_items'  => __( 'Buscar status de colaboração', 'cidade-ativa-3' ),
				'all_items'     => __( 'Todos os status de colaboração', 'cidade-ativa-3' ),
				'update_item'   => __( 'Atualizar status de colaboração', 'cidade-ativa-3' ),
				'add_new_item'  => __( 'Cadastrar novo status de colaboração', 'cidade-ativa-3' ),
				'new_item_name' => __( 'Nome do novo status de colaboração', 'cidade-ativa-3' ),
			),
			'public'            => true,
			'hierarchical'      => true,
			'show_in_rest'      => true,
			'show_admin_column' => true,
			'rewrite'           => array( 'slug' => 'collaborator-status' ),
		)
	);
}
add_action( 'init', 'ca3_register_equipe_post_types', 0 );
