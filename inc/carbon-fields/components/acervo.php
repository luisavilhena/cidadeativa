<?php
/**
 * Campos do componente Acervo.
 * Os itens do acervo serão definidos depois.
 *
 * @package cidade-ativa-3
 */

use Carbon_Fields\Field;

return array(
	Field::make( 'text', 'title', __( 'Título', 'cidade-ativa-3' ) ),
	Field::make( 'rich_text', 'text', __( 'Texto', 'cidade-ativa-3' ) ),
	Field::make( 'text', 'count', __( 'Quantidade de itens (os mais recentes)', 'cidade-ativa-3' ) )
		->set_attribute( 'type', 'number' )
		->set_default_value( 4 ),
	Field::make( 'text', 'button_label', __( 'Texto do botão', 'cidade-ativa-3' ) )->set_width( 50 ),
	Field::make( 'text', 'button_url', __( 'Link do botão', 'cidade-ativa-3' ) )->set_width( 50 ),
	Field::make( 'select', 'button_style', __( 'Estilo do botão', 'cidade-ativa-3' ) )
		->set_options( ca3_button_style_options() )
		->set_width( 33 ),
	Field::make( 'select', 'button_color', __( 'Cor do botão', 'cidade-ativa-3' ) )
		->set_options( ca3_button_color_options() )
		->set_width( 33 ),
	Field::make( 'select', 'button_icon', __( 'Ícone do botão', 'cidade-ativa-3' ) )
		->set_options( ca3_button_icon_options() )
		->set_width( 33 ),
);
