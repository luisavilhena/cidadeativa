<?php
/**
 * Campos do componente Hero (abertura).
 *
 * @package cidade-ativa-3
 */

use Carbon_Fields\Field;

return array(
	Field::make( 'textarea', 'title', __( 'Título', 'cidade-ativa-3' ) )
		->set_rows( 3 )
		->set_help_text( __( 'Um ponto final no fim do título aparece em destaque.', 'cidade-ativa-3' ) ),
	Field::make( 'rich_text', 'text', __( 'Texto', 'cidade-ativa-3' ) ),
	Field::make( 'image', 'image', __( 'Ilustração (sobre o triângulo verde)', 'cidade-ativa-3' ) ),
	Field::make( 'select', 'animation', __( 'Animação (teste)', 'cidade-ativa-3' ) )
		->set_options(
			array(
				''         => __( 'Nenhuma', 'cidade-ativa-3' ),
				'parallax' => __( 'Parallax: triângulo e ilustração rolam mais devagar', 'cidade-ativa-3' ),
				'montagem' => __( 'Montagem: triângulo aparece e as figuras entram uma a uma', 'cidade-ativa-3' ),
			)
		)
		->set_help_text( __( 'Na "Montagem" a ilustração é a do Figma, separada em figuras (o campo Ilustração não é usado).', 'cidade-ativa-3' ) ),
);
