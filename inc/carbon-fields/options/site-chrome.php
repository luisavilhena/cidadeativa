<?php
/**
 * Opções do cabeçalho e do rodapé (menu Aparência → Cabeçalho e rodapé).
 * Os itens dos menus ficam em Aparência → Menus.
 *
 * @package cidade-ativa-3
 */

use Carbon_Fields\Container;
use Carbon_Fields\Field;

Container::make( 'theme_options', __( 'Cabeçalho e rodapé', 'cidade-ativa-3' ) )
	->set_page_parent( 'themes.php' )
	->set_page_file( 'ca3-cabecalho-rodape' )
	->add_tab(
		__( 'Cabeçalho', 'cidade-ativa-3' ),
		array(
			Field::make( 'text', 'ca3_chrome_menu_open', __( 'Botão do menu no celular: abrir (leitores de tela)', 'cidade-ativa-3' ) )->set_width( 50 ),
			Field::make( 'text', 'ca3_chrome_menu_close', __( 'Botão do menu no celular: fechar (leitores de tela)', 'cidade-ativa-3' ) )->set_width( 50 ),
		)
	)
	->add_tab(
		__( 'Rodapé', 'cidade-ativa-3' ),
		array(
			Field::make( 'separator', 'ca3_chrome_contact_sep', __( 'Entre em contato (última seção antes do rodapé)', 'cidade-ativa-3' ) ),
			Field::make( 'text', 'ca3_chrome_contact_title', __( 'Título', 'cidade-ativa-3' ) )
				->set_default_value( 'Entre em contato' )
				->set_width( 50 ),
			Field::make( 'text', 'ca3_chrome_contact_email', __( 'E-mail', 'cidade-ativa-3' ) )
				->set_help_text( __( 'Se ficar vazio, usa o e-mail de contato do tema antigo. As redes sociais são as de baixo.', 'cidade-ativa-3' ) )
				->set_width( 50 ),
			Field::make( 'separator', 'ca3_chrome_footer_sep', __( 'Rodapé', 'cidade-ativa-3' ) ),
			Field::make( 'textarea', 'ca3_chrome_address', __( 'Endereço', 'cidade-ativa-3' ) )->set_rows( 2 ),
			Field::make( 'complex', 'ca3_chrome_social', __( 'Redes sociais', 'cidade-ativa-3' ) )
				->set_layout( 'tabbed-horizontal' )
				->add_fields(
					array(
						Field::make( 'select', 'rede', __( 'Rede', 'cidade-ativa-3' ) )
							->set_options(
								array(
									'instagram' => 'Instagram',
									'youtube'   => 'YouTube',
									'linkedin'  => 'LinkedIn',
								)
							)
							->set_width( 30 ),
						Field::make( 'text', 'link', __( 'Link', 'cidade-ativa-3' ) )->set_width( 70 ),
					)
				)
				->set_header_template( '<%- rede %>' ),
		)
	);
