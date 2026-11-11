<?php
/**
 * Campos do Conteúdo 3 / 9: título, texto e imagem.
 *
 * @package cidade-ativa-3
 */

use Carbon_Fields\Field;

return array(
	Field::make( 'text', 'title', __( 'Título', 'cidade-ativa-3' ) ),
	Field::make( 'rich_text', 'text', __( 'Texto', 'cidade-ativa-3' ) ),
	Field::make( 'image', 'image', __( 'Imagem', 'cidade-ativa-3' ) )->set_width( 50 ),
	Field::make( 'select', 'image_position', __( 'Lado da imagem', 'cidade-ativa-3' ) )
		->set_options(
			array(
				'right' => __( 'Direita', 'cidade-ativa-3' ),
				'left'  => __( 'Esquerda', 'cidade-ativa-3' ),
			)
		)
		->set_width( 50 ),
);
