<?php
/**
 * Textos fixos das páginas de programa (menu Iniciativas → Textos da página do programa).
 *
 * @package cidade-ativa-3
 */

use Carbon_Fields\Container;
use Carbon_Fields\Field;

Container::make( 'theme_options', __( 'Textos da página do programa', 'cidade-ativa-3' ) )
	->set_page_parent( 'cidade-ativa-iniciativas' )
	->set_page_file( 'ca3-programa-textos' )
	->add_fields(
		array(
			Field::make( 'text', 'ca3_program_breadcrumb', __( 'Caminho: primeiro item (ex.: Programas)', 'cidade-ativa-3' ) )->set_width( 50 ),
			Field::make( 'text', 'ca3_program_breadcrumb_url', __( 'Caminho: link do primeiro item (opcional)', 'cidade-ativa-3' ) )->set_width( 50 ),
			Field::make( 'text', 'ca3_program_axes_title', __( 'Título: Eixos', 'cidade-ativa-3' ) )->set_width( 25 ),
			Field::make( 'text', 'ca3_program_products_title', __( 'Título: Produtos', 'cidade-ativa-3' ) )->set_width( 25 ),
			Field::make( 'text', 'ca3_program_downloads_title', __( 'Título: Downloads', 'cidade-ativa-3' ) )->set_width( 25 ),
			Field::make( 'text', 'ca3_program_actions_title', __( 'Título: Ações da Iniciativa', 'cidade-ativa-3' ) )->set_width( 25 ),
			Field::make( 'separator', 'ca3_program_labels_sep', __( 'Rótulos dos dados da capa', 'cidade-ativa-3' ) ),
			Field::make( 'text', 'ca3_program_label_year', __( 'Ano', 'cidade-ativa-3' ) )->set_width( 33 ),
			Field::make( 'text', 'ca3_program_label_status', __( 'Status', 'cidade-ativa-3' ) )->set_width( 33 ),
			Field::make( 'text', 'ca3_program_label_actions_done', __( 'Ações concluídas', 'cidade-ativa-3' ) )->set_width( 33 ),
			Field::make( 'text', 'ca3_program_label_people_impacted', __( 'Pessoas impactadas', 'cidade-ativa-3' ) )->set_width( 33 ),
			Field::make( 'text', 'ca3_program_label_people_engaged', __( 'Pessoas engajadas', 'cidade-ativa-3' ) )->set_width( 33 ),
			Field::make( 'text', 'ca3_program_label_reach', __( 'Alcance geográfico', 'cidade-ativa-3' ) )->set_width( 33 ),
			Field::make( 'separator', 'ca3_program_carousel_sep', __( 'Carrossel "Nossos programas"', 'cidade-ativa-3' ) ),
			Field::make( 'text', 'ca3_program_carousel_title', __( 'Título', 'cidade-ativa-3' ) )->set_width( 33 ),
			Field::make( 'text', 'ca3_program_carousel_prev', __( 'Seta anterior (leitores de tela)', 'cidade-ativa-3' ) )->set_width( 33 ),
			Field::make( 'text', 'ca3_program_carousel_next', __( 'Seta próxima (leitores de tela)', 'cidade-ativa-3' ) )->set_width( 33 ),
		)
	);
