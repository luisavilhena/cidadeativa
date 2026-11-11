<?php
/**
 * Tipos de conteúdo: Iniciativa (ca-iniciativa) e Eixo (ca-eixo).
 * Mesmas chaves e URLs do tema antigo, para manter o conteúdo existente.
 *
 * @package cidade-ativa-3
 */

function ca3_register_iniciativa_post_types() {
	register_post_type(
		'ca-iniciativa',
		array(
			'label'         => __( 'Iniciativa', 'cidade-ativa-3' ),
			'labels'        => array(
				'name'          => __( 'Iniciativas', 'cidade-ativa-3' ),
				'singular_name' => __( 'Iniciativa', 'cidade-ativa-3' ),
				'menu_name'     => __( 'Iniciativas', 'cidade-ativa-3' ),
			),
			'description'   => __( 'Iniciativas da Cidade Ativa', 'cidade-ativa-3' ),
			'menu_icon'     => 'dashicons-megaphone',
			'supports'      => array( 'title', 'editor', 'revisions', 'page-attributes', 'excerpt', 'thumbnail' ),
			'public'        => true,
			'show_in_menu'  => 'cidade-ativa-iniciativas',
			'show_in_rest'  => true,
			'hierarchical'  => true,
			'has_archive'   => false,
			'capability_type' => 'page',
			'rewrite'       => array( 'slug' => 'iniciativa' ),
		)
	);

	register_post_type(
		'ca-eixo',
		array(
			'label'         => __( 'Eixo', 'cidade-ativa-3' ),
			'labels'        => array(
				'name'          => __( 'Eixos', 'cidade-ativa-3' ),
				'singular_name' => __( 'Eixo', 'cidade-ativa-3' ),
				'menu_name'     => __( 'Eixos', 'cidade-ativa-3' ),
			),
			'description'   => __( 'Eixos de atuação', 'cidade-ativa-3' ),
			'menu_icon'     => 'dashicons-megaphone',
			'supports'      => array( 'title', 'editor', 'page-attributes', 'excerpt' ),
			'public'        => true,
			'show_in_menu'  => 'cidade-ativa-iniciativas',
			'show_in_rest'  => true,
			'hierarchical'  => false,
			'has_archive'   => false,
			'capability_type' => 'post',
			'rewrite'       => array( 'slug' => 'eixo' ),
		)
	);
}
add_action( 'init', 'ca3_register_iniciativa_post_types', 0 );
