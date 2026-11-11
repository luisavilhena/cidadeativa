<?php
/**
 * Campos da página do Acervo (template page-templates/biblioteca.php).
 *
 * @package cidade-ativa-3
 */

use Carbon_Fields\Container;
use Carbon_Fields\Field;

Container::make( 'post_meta', __( 'Listagem do Acervo', 'cidade-ativa-3' ) )
	->where( 'post_template', '=', CA3_ACERVO_TEMPLATE )
	->add_tab(
		__( 'Conteúdo', 'cidade-ativa-3' ),
		array(
			Field::make( 'rich_text', 'ca3_acervo_intro', __( 'Texto de introdução', 'cidade-ativa-3' ) ),
			Field::make( 'select', 'ca3_acervo_parent_category', __( 'Categoria do acervo (as filhas viram filtros)', 'cidade-ativa-3' ) )
				->set_options( 'ca3_acervo_category_options' )
				->set_width( 50 ),
			Field::make( 'text', 'ca3_acervo_per_page', __( 'Itens por página', 'cidade-ativa-3' ) )
				->set_attribute( 'type', 'number' )
				->set_width( 50 ),
		)
	)
	->add_tab(
		__( 'Textos dos filtros', 'cidade-ativa-3' ),
		array(
			Field::make( 'text', 'ca3_acervo_search_placeholder', __( 'Busca: texto de exemplo', 'cidade-ativa-3' ) )->set_width( 50 ),
			Field::make( 'text', 'ca3_acervo_search_button', __( 'Busca: texto do botão (leitores de tela)', 'cidade-ativa-3' ) )->set_width( 50 ),
			Field::make( 'text', 'ca3_acervo_sort_label', __( 'Ordenação: rótulo', 'cidade-ativa-3' ) )->set_width( 33 ),
			Field::make( 'text', 'ca3_acervo_sort_newest', __( 'Ordenação: mais recentes', 'cidade-ativa-3' ) )->set_width( 33 ),
			Field::make( 'text', 'ca3_acervo_sort_oldest', __( 'Ordenação: mais antigos', 'cidade-ativa-3' ) )->set_width( 33 ),
			Field::make( 'text', 'ca3_acervo_all_label', __( 'Filtro: todas as categorias', 'cidade-ativa-3' ) )->set_width( 50 ),
			Field::make( 'text', 'ca3_acervo_empty', __( 'Mensagem quando não há resultados', 'cidade-ativa-3' ) )->set_width( 50 ),
		)
	)
	->add_tab(
		__( 'Textos da paginação', 'cidade-ativa-3' ),
		array(
			Field::make( 'text', 'ca3_acervo_page_first', __( 'Primeira página (leitores de tela)', 'cidade-ativa-3' ) )->set_width( 25 ),
			Field::make( 'text', 'ca3_acervo_page_prev', __( 'Página anterior (leitores de tela)', 'cidade-ativa-3' ) )->set_width( 25 ),
			Field::make( 'text', 'ca3_acervo_page_next', __( 'Próxima página (leitores de tela)', 'cidade-ativa-3' ) )->set_width( 25 ),
			Field::make( 'text', 'ca3_acervo_page_last', __( 'Última página (leitores de tela)', 'cidade-ativa-3' ) )->set_width( 25 ),
		)
	);

/**
 * Opções do select de categoria (todas as categorias principais).
 */
function ca3_acervo_category_options() {
	$options = array( '' => '—' );

	foreach ( get_terms( array( 'taxonomy' => 'category', 'parent' => 0, 'hide_empty' => false ) ) as $term ) {
		$options[ $term->term_id ] = $term->name;
	}

	return $options;
}
