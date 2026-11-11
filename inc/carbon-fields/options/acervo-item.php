<?php
/**
 * Textos fixos da página de cada item do Acervo (menu Acervo → Textos da página do item).
 *
 * @package cidade-ativa-3
 */

use Carbon_Fields\Container;
use Carbon_Fields\Field;

Container::make( 'theme_options', __( 'Textos da página do item', 'cidade-ativa-3' ) )
	->set_page_parent( 'cidade-ativa-biblioteca' )
	->set_page_file( 'ca3-acervo-item-textos' )
	->add_fields(
		array(
			Field::make( 'text', 'ca3_acervo_item_date_label', __( 'Rótulo da data (ex.: Mês/ano de publicação:)', 'cidade-ativa-3' ) )->set_width( 50 ),
			Field::make( 'text', 'ca3_acervo_item_ficha_label', __( 'Rótulo da ficha técnica', 'cidade-ativa-3' ) )->set_width( 50 ),
			Field::make( 'text', 'ca3_acervo_item_download_label', __( 'Texto do botão de download', 'cidade-ativa-3' ) )->set_width( 50 ),
			Field::make( 'text', 'ca3_acervo_item_links_title', __( 'Título dos links (ex.: Saiba mais:)', 'cidade-ativa-3' ) )->set_width( 50 ),
			Field::make( 'separator', 'ca3_acervo_item_related_sep', __( 'Acervo relacionado', 'cidade-ativa-3' ) ),
			Field::make( 'text', 'ca3_acervo_item_related_title', __( 'Título', 'cidade-ativa-3' ) )->set_width( 33 ),
			Field::make( 'text', 'ca3_acervo_item_related_button', __( 'Texto do botão (leva à listagem)', 'cidade-ativa-3' ) )->set_width( 33 ),
			Field::make( 'text', 'ca3_acervo_item_related_count', __( 'Quantidade de itens', 'cidade-ativa-3' ) )
				->set_attribute( 'type', 'number' )
				->set_width( 33 ),
			Field::make( 'text', 'ca3_acervo_item_related_prev', __( 'Seta anterior (leitores de tela)', 'cidade-ativa-3' ) )->set_width( 50 ),
			Field::make( 'text', 'ca3_acervo_item_related_next', __( 'Seta próxima (leitores de tela)', 'cidade-ativa-3' ) )->set_width( 50 ),
		)
	);
