<?php
/**
 * Tipo de conteúdo: Acervo (ca-biblioteca), com categorias e tags do blog.
 * Mesma chave e URL do tema antigo, para manter o conteúdo existente.
 *
 * @package cidade-ativa-3
 */

function ca3_register_biblioteca_post_type() {
	register_post_type(
		'ca-biblioteca',
		array(
			'label'         => __( 'Acervo', 'cidade-ativa-3' ),
			'labels'        => array(
				'name'          => __( 'Acervo', 'cidade-ativa-3' ),
				'singular_name' => __( 'Acervo', 'cidade-ativa-3' ),
				'menu_name'     => __( 'Acervo', 'cidade-ativa-3' ),
			),
			'description'   => __( 'Acervo', 'cidade-ativa-3' ),
			'menu_icon'     => 'dashicons-media-document',
			'supports'      => array( 'title', 'editor', 'revisions', 'page-attributes', 'excerpt', 'thumbnail' ),
			'taxonomies'    => array( 'category', 'post_tag' ),
			'public'        => true,
			'show_in_menu'  => 'cidade-ativa-biblioteca',
			'show_in_rest'  => true,
			'hierarchical'  => true,
			'has_archive'   => false,
			'capability_type' => 'page',
			'rewrite'       => array( 'slug' => 'publicacao' ),
		)
	);
}
add_action( 'init', 'ca3_register_biblioteca_post_type', 0 );
