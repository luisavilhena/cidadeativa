<?php
/**
 * Campos dos Membros da equipe e dos Colaboradores.
 * Os nomes dos campos são os mesmos do tema antigo, para manter os dados salvos.
 *
 * @package cidade-ativa-3
 */

use Carbon_Fields\Container;
use Carbon_Fields\Field;

Container::make( 'post_meta', __( 'Dados dx membrx da equipe', 'cidade-ativa-3' ) )
	->where( 'post_type', '=', 'ca-team-member' )
	->add_fields(
		array(
			Field::make( 'image', 'ca3_member_icon', __( 'Foto ícone (etiqueta da home)', 'cidade-ativa-3' ) )
				->set_help_text( __( 'Foto pequena e redonda. A foto maior do popup é a imagem destacada; o cargo é o resumo e a descrição é o texto do editor.', 'cidade-ativa-3' ) ),
			Field::make( 'text', 'photo_top', __( 'Posição Y na foto', 'cidade-ativa-3' ) ),
			Field::make( 'text', 'photo_left', __( 'Posição X na foto', 'cidade-ativa-3' ) ),
			Field::make( 'text', 'facebook_link', __( 'Link para perfil no Facebook', 'cidade-ativa-3' ) ),
			Field::make( 'text', 'linkedin_link', __( 'Link para perfil no LinkedIn', 'cidade-ativa-3' ) ),
			Field::make( 'text', 'instagram_link', __( 'Link para perfil no Instagram', 'cidade-ativa-3' ) ),
		)
	);

Container::make( 'post_meta', __( 'Dados dx colaboradorx', 'cidade-ativa-3' ) )
	->where( 'post_type', '=', 'ca-collaborator' )
	->add_fields(
		array(
			Field::make( 'text', 'ca3_member_role', __( 'Cargo / formação (ex.: Tecnóloga Arquiteta)', 'cidade-ativa-3' ) )
				->set_help_text( __( 'Se ficar vazio, usa o texto do editor. A foto é a imagem destacada.', 'cidade-ativa-3' ) ),
			Field::make( 'text', 'facebook_link', __( 'Link para perfil no Facebook', 'cidade-ativa-3' ) ),
			Field::make( 'text', 'linkedin_link', __( 'Link para perfil no LinkedIn', 'cidade-ativa-3' ) ),
			Field::make( 'text', 'instagram_link', __( 'Link para perfil no Instagram', 'cidade-ativa-3' ) ),
			Field::make( 'association', 'iniciativas', __( 'Iniciativas em que colaborou', 'cidade-ativa-3' ) )
				->set_types(
					array(
						array(
							'type'      => 'post',
							'post_type' => 'ca-iniciativa',
						),
					)
				),
		)
	);
