<?php
/**
 * Campos do Conteúdo 1 / 2 / 6: até 2 colunas (subtítulo + texto)
 * com um título opcional acima.
 *
 * @package cidade-ativa-3
 */

use Carbon_Fields\Field;

return array(
	Field::make( 'text', 'title', __( 'Título (opcional)', 'cidade-ativa-3' ) )->set_width( 60 ),
	Field::make( 'select', 'title_position', __( 'Posição do título', 'cidade-ativa-3' ) )
		->set_options(
			array(
				'full'  => __( 'Largura toda', 'cidade-ativa-3' ),
				'left'  => __( 'Coluna da esquerda', 'cidade-ativa-3' ),
				'right' => __( 'Coluna da direita', 'cidade-ativa-3' ),
			)
		)
		->set_width( 40 ),
	Field::make( 'complex', 'columns', __( 'Colunas', 'cidade-ativa-3' ) )
		->set_layout( 'tabbed-horizontal' )
		->set_max( 2 )
		->add_fields(
			array(
				Field::make( 'text', 'subtitle', __( 'Subtítulo', 'cidade-ativa-3' ) ),
				Field::make( 'rich_text', 'text', __( 'Texto', 'cidade-ativa-3' ) ),
			)
		)
		->set_header_template( '<%- subtitle %>' ),
);
