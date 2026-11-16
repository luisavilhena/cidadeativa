<?php
/**
 * Campos das páginas de programa / iniciativa (ca-iniciativa).
 *
 * @package cidade-ativa-3
 */

use Carbon_Fields\Container;
use Carbon_Fields\Field;

Container::make( 'post_meta', __( 'Página do programa', 'cidade-ativa-3' ) )
	->where( 'post_type', '=', 'ca-iniciativa' )
	->add_tab(
		__( 'Capa e cor', 'cidade-ativa-3' ),
		array(
			Field::make( 'select', 'ca3_prog_color', __( 'Cor da página', 'cidade-ativa-3' ) )
				->set_options( array( '' => __( 'Usar a cor do programa (iniciativas filhas)', 'cidade-ativa-3' ) ) + ca3_program_color_options() )
				->set_width( 50 ),
			Field::make( 'checkbox', 'ca3_prog_in_carousel', __( 'Mostrar no carrossel "Nossos programas"', 'cidade-ativa-3' ) )
				->set_help_text( __( 'A ordem segue o campo "Ordem" em Atributos da página.', 'cidade-ativa-3' ) )
				->set_width( 50 ),
			Field::make( 'image', 'ca3_prog_card_image', __( 'Imagem do card (carrossel "Nossos programas")', 'cidade-ativa-3' ) )
				->set_help_text( __( 'Se ficar vazia, o card usa a imagem destacada.', 'cidade-ativa-3' ) ),
			Field::make( 'text', 'ca3_prog_title_bold', __( 'Parte do nome em negrito (final do nome, ex.: do Sim)', 'cidade-ativa-3' ) )
				->set_help_text( __( 'Se ficar vazio, a última palavra fica em negrito.', 'cidade-ativa-3' ) ),
			Field::make( 'text', 'ca3_prog_subtitle', __( 'Frase do card (ex.: Cidades para e com as infâncias)', 'cidade-ativa-3' ) )
				->set_help_text( __( 'Se ficar vazio, usa o resumo do post.', 'cidade-ativa-3' ) ),
			Field::make( 'separator', 'ca3_prog_data_sep', __( 'Dados da capa (os vazios não aparecem)', 'cidade-ativa-3' ) ),
			Field::make( 'text', 'ca3_prog_year', __( 'Ano (ex.: 2018 - 2026)', 'cidade-ativa-3' ) )->set_width( 33 ),
			Field::make( 'text', 'ca3_prog_status', __( 'Status (ex.: concluído)', 'cidade-ativa-3' ) )->set_width( 33 ),
			Field::make( 'text', 'ca3_prog_actions_done', __( 'Ações concluídas', 'cidade-ativa-3' ) )->set_width( 33 ),
			Field::make( 'text', 'ca3_prog_people_impacted', __( 'Pessoas impactadas', 'cidade-ativa-3' ) )->set_width( 33 ),
			Field::make( 'text', 'ca3_prog_people_engaged', __( 'Pessoas engajadas', 'cidade-ativa-3' ) )->set_width( 33 ),
			Field::make( 'text', 'ca3_prog_reach', __( 'Alcance geográfico (ex.: SP / RJ / AL)', 'cidade-ativa-3' ) )->set_width( 33 ),
			Field::make( 'complex', 'ca3_prog_data', __( 'Outros dados (ex.: Parceiros)', 'cidade-ativa-3' ) )
				->set_layout( 'tabbed-horizontal' )
				->add_fields(
					array(
						Field::make( 'text', 'item', __( 'Item', 'cidade-ativa-3' ) )->set_width( 40 ),
						Field::make( 'text', 'conteudo', __( 'Conteúdo', 'cidade-ativa-3' ) )->set_width( 60 ),
					)
				)
				->set_header_template( '<%- item %>' ),
		)
	)
	->add_tab(
		__( 'Conteúdo', 'cidade-ativa-3' ),
		array(
			Field::make( 'rich_text', 'ca3_prog_side_text', __( 'Texto de abertura (opcional)', 'cidade-ativa-3' ) )
				->set_help_text( __( 'Aparece logo antes do texto do editor, depois das Ações da Iniciativa.', 'cidade-ativa-3' ) ),
			Field::make( 'textarea', 'ca3_prog_quote', __( 'Citação (opcional, logo antes do texto)', 'cidade-ativa-3' ) )
				->set_rows( 3 ),
			Field::make( 'complex', 'ca3_prog_products', __( 'Produtos', 'cidade-ativa-3' ) )
				->set_layout( 'tabbed-horizontal' )
				->add_fields(
					array(
						Field::make( 'text', 'texto', __( 'Produto', 'cidade-ativa-3' ) )->set_width( 50 ),
						Field::make( 'text', 'link', __( 'Link (opcional)', 'cidade-ativa-3' ) )->set_width( 50 ),
					)
				)
				->set_header_template( '<%- texto %>' ),
			Field::make( 'html', 'ca3_prog_help_columns' )
				->set_html( '<p>' . esc_html__( 'Ordem na página: capa, Eixos (caixa "Dados da iniciativa"), Produtos (inclui os arquivos da caixa "Downloads"), "Ações da Iniciativa" (iniciativas filhas publicadas, em duas colunas) e, por fim, citação, texto de abertura e texto do editor.', 'cidade-ativa-3' ) . '</p>' ),
		)
	);
