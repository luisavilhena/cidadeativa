<?php
/**
 * Campos das Iniciativas e dos Eixos.
 * Os nomes dos campos são os mesmos do tema antigo, para manter os dados salvos.
 *
 * @package cidade-ativa-3
 */

use Carbon_Fields\Container;
use Carbon_Fields\Field;

Container::make( 'post_meta', __( 'Dados da iniciativa', 'cidade-ativa-3' ) )
	->where( 'post_type', '=', 'ca-iniciativa' )
	->where( 'post_level', '=', 1 ) // Só nas iniciativas de 1º nível.
	->add_fields(
		array(
			Field::make( 'association', 'ca_iniciativa_eixos', __( 'Eixos', 'cidade-ativa-3' ) )
				->set_types(
					array(
						array(
							'type'      => 'post',
							'post_type' => 'ca-eixo',
						),
					)
				)
				->set_max( 5 ),
		)
	);

Container::make( 'post_meta', __( 'Downloads', 'cidade-ativa-3' ) )
	->where( 'post_type', '=', 'ca-iniciativa' )
	->add_fields(
		array(
			Field::make( 'complex', 'ca_iniciativa_downloads', __( 'Arquivos', 'cidade-ativa-3' ) )
				->add_fields(
					array(
						Field::make( 'text', 'name', __( 'Nome do arquivo', 'cidade-ativa-3' ) ),
						Field::make( 'image', 'thumbnail', __( 'Thumbnail', 'cidade-ativa-3' ) )->set_value_type( 'url' ),
						Field::make( 'file', 'file', __( 'Arquivo', 'cidade-ativa-3' ) )->set_value_type( 'url' ),
					)
				),
		)
	);

Container::make( 'post_meta', __( 'Dados do eixo', 'cidade-ativa-3' ) )
	->where( 'post_type', '=', 'ca-eixo' )
	->add_fields(
		array(
			Field::make( 'select', 'ca_eixo_color', __( 'Cor', 'cidade-ativa-3' ) )
				->set_options( ca3_legacy_color_options() ),
			Field::make( 'text', 'ca_eixo_text_name', __( 'Nome textual', 'cidade-ativa-3' ) ),
		)
	);
