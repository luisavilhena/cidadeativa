<?php
/**
 * Campos de cada item do Acervo (ca-biblioteca).
 * Se ficarem vazios, a página usa o que estiver no texto antigo do post
 * (ver inc/acervo-item.php). A descrição continua no editor de texto.
 *
 * @package cidade-ativa-3
 */

use Carbon_Fields\Container;
use Carbon_Fields\Field;

Container::make( 'post_meta', __( 'Dados da publicação', 'cidade-ativa-3' ) )
	->where( 'post_type', '=', 'ca-biblioteca' )
	->add_fields(
		array(
			Field::make( 'html', 'ca3_pub_help' )
				->set_html( '<p>' . esc_html__( 'Campos vazios usam o que estiver escrito no texto do post (padrão antigo).', 'cidade-ativa-3' ) . '</p>' ),
			Field::make( 'text', 'ca3_pub_date', __( 'Data de publicação (ex.: Fev/2025)', 'cidade-ativa-3' ) )
				->set_width( 50 ),
			Field::make( 'text', 'ca3_pub_download', __( 'Link do download (arquivo ou página)', 'cidade-ativa-3' ) )
				->set_width( 50 ),
			Field::make( 'complex', 'ca3_pub_ficha', __( 'Ficha técnica', 'cidade-ativa-3' ) )
				->set_layout( 'tabbed-horizontal' )
				->add_fields(
					array(
						Field::make( 'text', 'item', __( 'Item (ex.: Autores)', 'cidade-ativa-3' ) )->set_width( 30 ),
						Field::make( 'text', 'conteudo', __( 'Conteúdo', 'cidade-ativa-3' ) )->set_width( 70 ),
					)
				)
				->set_header_template( '<%- item %>' ),
			Field::make( 'complex', 'ca3_pub_links', __( 'Saiba mais (links)', 'cidade-ativa-3' ) )
				->set_layout( 'tabbed-horizontal' )
				->add_fields(
					array(
						Field::make( 'text', 'text', __( 'Texto do link', 'cidade-ativa-3' ) )->set_width( 50 ),
						Field::make( 'text', 'url', __( 'Link', 'cidade-ativa-3' ) )->set_width( 50 ),
					)
				)
				->set_header_template( '<%- text %>' ),
		)
	);
