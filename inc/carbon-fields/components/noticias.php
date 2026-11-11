<?php
/**
 * Campos do componente Notícias (mostra os 3 últimos posts do blog).
 *
 * @package cidade-ativa-3
 */

use Carbon_Fields\Field;

return array(
	Field::make( 'text', 'title', __( 'Título', 'cidade-ativa-3' ) ),
	Field::make( 'rich_text', 'text', __( 'Texto', 'cidade-ativa-3' ) ),
	Field::make( 'complex', 'buttons', __( 'Botões', 'cidade-ativa-3' ) )
		->set_layout( 'tabbed-horizontal' )
		->add_fields(
			array(
				Field::make( 'text', 'label', __( 'Texto', 'cidade-ativa-3' ) )->set_width( 50 ),
				Field::make( 'text', 'url', __( 'Link', 'cidade-ativa-3' ) )->set_width( 50 ),
				Field::make( 'select', 'style', __( 'Estilo', 'cidade-ativa-3' ) )
					->set_options( ca3_button_style_options() )
					->set_width( 33 ),
				Field::make( 'select', 'color', __( 'Cor', 'cidade-ativa-3' ) )
					->set_options( ca3_button_color_options() )
					->set_width( 33 ),
				Field::make( 'select', 'icon', __( 'Ícone', 'cidade-ativa-3' ) )
					->set_options( ca3_button_icon_options() )
					->set_width( 33 ),
			)
		)
		->set_header_template( '<%- label %>' ),
);
