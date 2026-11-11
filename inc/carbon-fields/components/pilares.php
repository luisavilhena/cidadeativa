<?php
/**
 * Campos do componente Pilares (colunas com título em destaque).
 *
 * @package cidade-ativa-3
 */

use Carbon_Fields\Field;

return array(
	Field::make( 'text', 'title', __( 'Título da seção', 'cidade-ativa-3' ) ),
	Field::make( 'complex', 'items', __( 'Colunas', 'cidade-ativa-3' ) )
		->set_layout( 'tabbed-horizontal' )
		->add_fields(
			array(
				Field::make( 'text', 'title', __( 'Título', 'cidade-ativa-3' ) )->set_width( 50 ),
				Field::make( 'text', 'subtitle', __( 'Subtítulo', 'cidade-ativa-3' ) )->set_width( 50 ),
				Field::make( 'rich_text', 'text', __( 'Texto', 'cidade-ativa-3' ) ),
			)
		)
		->set_header_template( '<%- title %>' ),
);
