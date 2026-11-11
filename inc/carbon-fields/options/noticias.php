<?php
/**
 * Opções e textos da listagem de Notícias (menu Posts → Listagem de Notícias).
 *
 * @package cidade-ativa-3
 */

use Carbon_Fields\Container;
use Carbon_Fields\Field;

Container::make( 'theme_options', __( 'Listagem de Notícias', 'cidade-ativa-3' ) )
	->set_page_parent( 'edit.php' )
	->set_page_file( 'ca3-listagem-noticias' )
	->add_tab(
		__( 'Conteúdo', 'cidade-ativa-3' ),
		array(
			Field::make( 'text', 'ca3_news_title', __( 'Título', 'cidade-ativa-3' ) ),
			Field::make( 'rich_text', 'ca3_news_intro', __( 'Texto de introdução', 'cidade-ativa-3' ) ),
			Field::make( 'multiselect', 'ca3_news_categories', __( 'Categorias que aparecem como notícias', 'cidade-ativa-3' ) )
				->set_options( 'ca3_news_category_options' )
				->set_width( 70 ),
			Field::make( 'text', 'ca3_news_per_page', __( 'Notícias por página', 'cidade-ativa-3' ) )
				->set_attribute( 'type', 'number' )
				->set_width( 30 ),
		)
	)
	->add_tab(
		__( 'Busca e paginação', 'cidade-ativa-3' ),
		array(
			Field::make( 'text', 'ca3_news_search_placeholder', __( 'Busca: texto de exemplo', 'cidade-ativa-3' ) )->set_width( 33 ),
			Field::make( 'text', 'ca3_news_search_button', __( 'Busca: texto do botão (leitores de tela)', 'cidade-ativa-3' ) )->set_width( 33 ),
			Field::make( 'text', 'ca3_news_empty', __( 'Mensagem quando não há resultados', 'cidade-ativa-3' ) )->set_width( 33 ),
			Field::make( 'text', 'ca3_news_page_first', __( 'Primeira página (leitores de tela)', 'cidade-ativa-3' ) )->set_width( 25 ),
			Field::make( 'text', 'ca3_news_page_prev', __( 'Página anterior (leitores de tela)', 'cidade-ativa-3' ) )->set_width( 25 ),
			Field::make( 'text', 'ca3_news_page_next', __( 'Próxima página (leitores de tela)', 'cidade-ativa-3' ) )->set_width( 25 ),
			Field::make( 'text', 'ca3_news_page_last', __( 'Última página (leitores de tela)', 'cidade-ativa-3' ) )->set_width( 25 ),
		)
	)
	->add_tab(
		__( 'Faixa de periódicos', 'cidade-ativa-3' ),
		array(
			Field::make( 'text', 'ca3_news_band_text', __( 'Texto', 'cidade-ativa-3' ) ),
			Field::make( 'complex', 'ca3_news_band_links', __( 'Botões', 'cidade-ativa-3' ) )
				->set_layout( 'tabbed-horizontal' )
				->add_fields(
					array(
						Field::make( 'text', 'texto', __( 'Texto do botão', 'cidade-ativa-3' ) )->set_width( 50 ),
						Field::make( 'text', 'link', __( 'Link', 'cidade-ativa-3' ) )->set_width( 50 ),
					)
				)
				->set_header_template( '<%- texto %>' ),
		)
	)
	->add_tab(
		__( 'Página da notícia', 'cidade-ativa-3' ),
		array(
			Field::make( 'text', 'ca3_news_single_author_prefix', __( 'Antes do autor (ex.: Por)', 'cidade-ativa-3' ) )->set_width( 33 ),
			Field::make( 'text', 'ca3_news_single_gallery_prev', __( 'Galeria: seta anterior (leitores de tela)', 'cidade-ativa-3' ) )->set_width( 33 ),
			Field::make( 'text', 'ca3_news_single_gallery_next', __( 'Galeria: seta próxima (leitores de tela)', 'cidade-ativa-3' ) )->set_width( 33 ),
			Field::make( 'separator', 'ca3_news_single_related_sep', __( 'Notícias relacionadas', 'cidade-ativa-3' ) ),
			Field::make( 'text', 'ca3_news_single_related_title', __( 'Título', 'cidade-ativa-3' ) )->set_width( 50 ),
			Field::make( 'text', 'ca3_news_single_related_count', __( 'Quantidade de notícias', 'cidade-ativa-3' ) )
				->set_attribute( 'type', 'number' )
				->set_width( 50 ),
			Field::make( 'text', 'ca3_news_single_related_prev', __( 'Seta anterior (leitores de tela)', 'cidade-ativa-3' ) )->set_width( 50 ),
			Field::make( 'text', 'ca3_news_single_related_next', __( 'Seta próxima (leitores de tela)', 'cidade-ativa-3' ) )->set_width( 50 ),
			Field::make( 'complex', 'ca3_news_single_buttons', __( 'Botões abaixo das relacionadas', 'cidade-ativa-3' ) )
				->set_layout( 'tabbed-horizontal' )
				->add_fields(
					array(
						Field::make( 'text', 'texto', __( 'Texto', 'cidade-ativa-3' ) )->set_width( 50 ),
						Field::make( 'text', 'link', __( 'Link', 'cidade-ativa-3' ) )->set_width( 50 ),
						Field::make( 'select', 'estilo', __( 'Estilo', 'cidade-ativa-3' ) )
							->set_options( ca3_button_style_options() )
							->set_width( 33 ),
						Field::make( 'select', 'cor', __( 'Cor', 'cidade-ativa-3' ) )
							->set_options( ca3_button_color_options() )
							->set_width( 33 ),
						Field::make( 'select', 'icone', __( 'Ícone', 'cidade-ativa-3' ) )
							->set_options( ca3_button_icon_options() )
							->set_width( 33 ),
					)
				)
				->set_header_template( '<%- texto %>' ),
		)
	);

/**
 * Opções do campo de categorias (todas as categorias de posts).
 */
function ca3_news_category_options() {
	$options = array();

	foreach ( get_terms( array( 'taxonomy' => 'category', 'hide_empty' => false ) ) as $term ) {
		$options[ $term->term_id ] = $term->name;
	}

	return $options;
}
