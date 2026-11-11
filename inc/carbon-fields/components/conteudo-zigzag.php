<?php
/**
 * Campos do Conteúdo 4: título e blocos em zigue-zague.
 * O primeiro bloco fica na coluna da esquerda, o próximo na da direita, e assim por diante.
 *
 * @package cidade-ativa-3
 */

use Carbon_Fields\Field;

return array(
	Field::make( 'text', 'title', __( 'Título', 'cidade-ativa-3' ) ),
	Field::make( 'complex', 'items', __( 'Blocos', 'cidade-ativa-3' ) )
		->set_layout( 'tabbed-horizontal' )
		->add_fields(
			array(
				Field::make( 'image', 'image', __( 'Imagem', 'cidade-ativa-3' ) ),
				Field::make( 'text', 'subtitle', __( 'Subtítulo', 'cidade-ativa-3' ) ),
				Field::make( 'rich_text', 'text', __( 'Texto', 'cidade-ativa-3' ) ),
			)
		)
		->set_header_template( '<%- subtitle %>' ),
);
