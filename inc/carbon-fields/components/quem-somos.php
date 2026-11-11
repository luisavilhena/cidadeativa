<?php
/**
 * Campos do componente Quem somos.
 *
 * @package cidade-ativa-3
 */

use Carbon_Fields\Field;

return array(
	Field::make( 'text', 'title', __( 'Título', 'cidade-ativa-3' ) ),
	Field::make( 'rich_text', 'text', __( 'Texto', 'cidade-ativa-3' ) ),
	Field::make( 'image', 'image', __( 'Ilustração', 'cidade-ativa-3' ) ),
	Field::make( 'complex', 'people', __( 'Etiquetas (pessoas da equipe)', 'cidade-ativa-3' ) )
		->set_layout( 'tabbed-horizontal' )
		->add_fields(
			array(
				Field::make( 'select', 'tipo', __( 'Ao clicar', 'cidade-ativa-3' ) )
					->set_options(
						array(
							'membro'        => __( 'Abre a pessoa da equipe', 'cidade-ativa-3' ),
							'colaboradores' => __( 'Abre a lista de colaboradores', 'cidade-ativa-3' ),
							'link'          => __( 'Vai para um link', 'cidade-ativa-3' ),
						)
					)
					->set_width( 33 ),
				Field::make( 'association', 'membro', __( 'Pessoa da equipe', 'cidade-ativa-3' ) )
					->set_types(
						array(
							array(
								'type'      => 'post',
								'post_type' => 'ca-team-member',
							),
						)
					)
					->set_max( 1 )
					->set_conditional_logic( array( array( 'field' => 'tipo', 'value' => 'membro' ) ) ),
				Field::make( 'text', 'name', __( 'Nome (vazio = nome da pessoa)', 'cidade-ativa-3' ) )->set_width( 50 ),
				Field::make( 'text', 'url', __( 'Link', 'cidade-ativa-3' ) )
					->set_width( 50 )
					->set_conditional_logic( array( array( 'field' => 'tipo', 'value' => 'link' ) ) ),
				Field::make( 'image', 'photo', __( 'Foto (vazio = foto ícone da pessoa)', 'cidade-ativa-3' ) )->set_width( 33 ),
				Field::make( 'select', 'color', __( 'Cor', 'cidade-ativa-3' ) )
					->set_options(
						array(
							'vermelho' => __( 'Vermelho', 'cidade-ativa-3' ),
							'azul'     => __( 'Azul', 'cidade-ativa-3' ),
							'rosa'     => __( 'Rosa', 'cidade-ativa-3' ),
							'amarelo'  => __( 'Amarelo', 'cidade-ativa-3' ),
							'verde'    => __( 'Verde', 'cidade-ativa-3' ),
							'laranja'  => __( 'Laranja', 'cidade-ativa-3' ),
						)
					)
					->set_width( 33 ),
				Field::make( 'select', 'position', __( 'Posição sobre a ilustração', 'cidade-ativa-3' ) )
					->set_options(
						array(
							'topo'            => __( 'Topo', 'cidade-ativa-3' ),
							'topo-esquerda'   => __( 'Topo à esquerda', 'cidade-ativa-3' ),
							'esquerda'        => __( 'Esquerda', 'cidade-ativa-3' ),
							'esquerda-baixo'  => __( 'Esquerda, mais abaixo', 'cidade-ativa-3' ),
							'centro'          => __( 'Centro', 'cidade-ativa-3' ),
							'direita'         => __( 'Direita', 'cidade-ativa-3' ),
							'direita-baixo'   => __( 'Direita, mais abaixo', 'cidade-ativa-3' ),
							'base'            => __( 'Base', 'cidade-ativa-3' ),
						)
					)
					->set_width( 33 ),
				Field::make( 'checkbox', 'icon', __( 'Mostrar ícone de pessoa no lugar da foto', 'cidade-ativa-3' ) ),
			)
		)
		->set_header_template( '<%- name %>' ),
	Field::make( 'separator', 'collab_sep', __( 'Popup de colaboradores', 'cidade-ativa-3' ) ),
	Field::make( 'text', 'collab_title', __( 'Título', 'cidade-ativa-3' ) )->set_width( 50 ),
	Field::make( 'text', 'collab_text', __( 'Texto', 'cidade-ativa-3' ) )->set_width( 50 ),
	Field::make( 'text', 'close_label', __( 'Botão fechar (leitores de tela)', 'cidade-ativa-3' ) )->set_width( 50 ),
	Field::make( 'text', 'projects_label', __( 'Rótulo dos projetos no popup da pessoa (ex.: Projetos)', 'cidade-ativa-3' ) )->set_width( 50 ),
);
