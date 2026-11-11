<?php
/**
 * Campos do componente Números.
 *
 * @package cidade-ativa-3
 */

use Carbon_Fields\Field;

return array(
	Field::make( 'complex', 'items', __( 'Números', 'cidade-ativa-3' ) )
		->set_layout( 'tabbed-horizontal' )
		->add_fields(
			array(
				Field::make( 'text', 'number', __( 'Número', 'cidade-ativa-3' ) )->set_width( 30 ),
				Field::make( 'textarea', 'label', __( 'Descrição', 'cidade-ativa-3' ) )->set_rows( 2 )->set_width( 70 ),
				Field::make( 'image', 'shape', __( 'Forma de fundo', 'cidade-ativa-3' ) ),
			)
		)
		->set_header_template( '<%- number %>' ),
);
