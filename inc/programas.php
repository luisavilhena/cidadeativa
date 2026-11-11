<?php
/**
 * Programas = iniciativas de 1º nível (ca-iniciativa sem página-mãe).
 * As iniciativas filhas são as "Ações da Iniciativa".
 *
 * Página do programa (single-ca-iniciativa.php):
 *   capa colorida (cor do programa) + dados + foto
 *   coluna esquerda: citação, produtos, downloads e ações (filhas)
 *   coluna direita: "Texto da coluna direita" (campo próprio)
 *   depois: o texto do editor, com o grid de imagens (c-post-content)
 *   e o carrossel "Nossos programas"
 *
 * @package cidade-ativa-3
 */

/**
 * Lê um texto fixo das páginas de programa (Iniciativas → Textos da página do programa).
 */
function ca3_program_text( $name ) {
	return function_exists( 'carbon_get_theme_option' ) ? carbon_get_theme_option( 'ca3_program_' . $name ) : '';
}

/**
 * Lê um campo do programa.
 */
function ca3_program_field( $post_id, $name ) {
	return function_exists( 'carbon_get_post_meta' ) ? carbon_get_post_meta( $post_id, 'ca3_prog_' . $name ) : '';
}

/**
 * Programa (iniciativa de 1º nível) ao qual a iniciativa pertence.
 */
function ca3_program_root( $post_id ) {
	$ancestors = get_post_ancestors( $post_id );

	return $ancestors ? (int) end( $ancestors ) : (int) $post_id;
}

/**
 * Cores disponíveis para os programas (famílias de cor do Figma).
 */
function ca3_program_color_options() {
	return array(
		'azul'     => __( 'Roxo (azul)', 'cidade-ativa-3' ),
		'amarelo'  => __( 'Amarelo', 'cidade-ativa-3' ),
		'verde'    => __( 'Verde', 'cidade-ativa-3' ),
		'rosa'     => __( 'Rosa', 'cidade-ativa-3' ),
		'laranja'  => __( 'Laranja', 'cidade-ativa-3' ),
		'vermelho' => __( 'Vermelho', 'cidade-ativa-3' ),
	);
}

/**
 * Cor da iniciativa: a própria ou, se vazia, a do programa.
 */
function ca3_program_color( $post_id ) {
	$options = ca3_program_color_options();
	$color   = ca3_program_field( $post_id, 'color' );

	if ( ! $color || ! isset( $options[ $color ] ) ) {
		$root  = ca3_program_root( $post_id );
		$color = $root !== (int) $post_id ? ca3_program_field( $root, 'color' ) : '';
	}

	return isset( $options[ $color ] ) ? $color : 'azul';
}

/**
 * Título com a parte final em negrito (Figma: "Olhe o **Degrau**").
 * A parte em negrito vem do campo "Parte do nome em negrito" ou, se vazio,
 * é a última palavra.
 */
function ca3_program_title_html( $title, $post_id = null ) {
	$title    = trim( $title );
	$emphasis = $post_id ? trim( (string) ca3_program_field( $post_id, 'title_bold' ) ) : '';

	if ( '' !== $emphasis && strlen( $emphasis ) < strlen( $title ) && substr( $title, -strlen( $emphasis ) ) === $emphasis ) {
		return esc_html( rtrim( substr( $title, 0, -strlen( $emphasis ) ) ) ) . ' <strong>' . esc_html( $emphasis ) . '</strong>';
	}

	$space = strrpos( $title, ' ' );

	if ( false === $space ) {
		return '<strong>' . esc_html( $title ) . '</strong>';
	}

	return esc_html( substr( $title, 0, $space ) ) . ' <strong>' . esc_html( substr( $title, $space + 1 ) ) . '</strong>';
}

/**
 * Frase do card (subtítulo); se vazia, o resumo do post.
 */
function ca3_program_subtitle( $post_id ) {
	$subtitle = ca3_program_field( $post_id, 'subtitle' );

	return $subtitle ? $subtitle : get_the_excerpt( $post_id );
}

/**
 * Dados da capa: os campos fixos (com rótulo de "Textos da página do
 * programa") seguidos de "Outros dados". Os vazios não aparecem.
 *
 * @return array Linhas com item e conteudo.
 */
function ca3_program_cover_data( $post_id ) {
	$rows = array();

	foreach ( array( 'year', 'status', 'actions_done', 'people_impacted', 'people_engaged', 'reach' ) as $name ) {
		$value = trim( (string) ca3_program_field( $post_id, $name ) );
		if ( '' !== $value ) {
			$rows[] = array(
				'item'     => ca3_program_text( 'label_' . $name ),
				'conteudo' => $value,
			);
		}
	}

	return array_merge( $rows, ca3_program_rows( $post_id, 'data', 'conteudo' ) );
}

/**
 * Linhas não vazias de um campo complexo.
 */
function ca3_program_rows( $post_id, $name, $required ) {
	return array_values(
		array_filter(
			(array) ca3_program_field( $post_id, $name ),
			function ( $row ) use ( $required ) {
				return isset( $row[ $required ] ) && '' !== trim( $row[ $required ] );
			}
		)
	);
}

/**
 * Downloads da iniciativa (campo do tema antigo, ca_iniciativa_downloads).
 */
function ca3_program_downloads( $post_id ) {
	if ( ! function_exists( 'carbon_get_post_meta' ) ) {
		return array();
	}

	return array_values(
		array_filter(
			(array) carbon_get_post_meta( $post_id, 'ca_iniciativa_downloads' ),
			function ( $row ) {
				return ! empty( $row['file'] );
			}
		)
	);
}

/**
 * Eixos da iniciativa (campo "Eixos" da caixa "Dados da iniciativa"),
 * só os publicados.
 *
 * @return WP_Post[]
 */
function ca3_program_axes( $post_id ) {
	if ( ! function_exists( 'carbon_get_post_meta' ) ) {
		return array();
	}

	$axes = array();
	foreach ( (array) carbon_get_post_meta( $post_id, 'ca_iniciativa_eixos' ) as $item ) {
		$axis = get_post( isset( $item['id'] ) ? (int) $item['id'] : 0 );
		if ( $axis && 'publish' === $axis->post_status ) {
			$axes[] = $axis;
		}
	}

	return $axes;
}

/**
 * Ações da iniciativa: as iniciativas filhas publicadas.
 */
function ca3_program_actions( $post_id ) {
	return get_posts(
		array(
			'post_type'   => 'ca-iniciativa',
			'post_parent' => $post_id,
			'post_status' => 'publish',
			'numberposts' => -1,
			'orderby'     => array(
				'menu_order' => 'ASC',
				'date'       => 'DESC',
			),
		)
	);
}

/**
 * Programas do carrossel "Nossos programas": os marcados para aparecer,
 * na ordem do campo "Ordem" (atributos da página).
 */
function ca3_programs_for_carousel() {
	return get_posts(
		array(
			'post_type'   => 'ca-iniciativa',
			'post_parent' => 0,
			'post_status' => 'publish',
			'numberposts' => -1,
			'meta_key'    => '_ca3_prog_in_carousel',
			'meta_value'  => 'yes',
			'orderby'     => array(
				'menu_order' => 'ASC',
				'title'      => 'ASC',
			),
		)
	);
}
