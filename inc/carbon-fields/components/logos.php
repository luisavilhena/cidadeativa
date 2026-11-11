<?php
/**
 * Campos do componente Logos (parcerias, premiações...).
 *
 * @package cidade-ativa-3
 */

use Carbon_Fields\Field;

return array(
	Field::make( 'text', 'title', __( 'Título', 'cidade-ativa-3' ) ),
	Field::make( 'complex', 'logos', __( 'Logos', 'cidade-ativa-3' ) )
		->set_layout( 'grid' )
		->add_fields(
			array(
				Field::make( 'image', 'image', __( 'Logo', 'cidade-ativa-3' ) ),
				Field::make( 'text', 'name', __( 'Nome', 'cidade-ativa-3' ) )->set_width( 50 ),
				Field::make( 'text', 'url', __( 'Link (opcional)', 'cidade-ativa-3' ) )->set_width( 50 ),
			)
		)
		->set_header_template( '<%- name %>' ),
);
