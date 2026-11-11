<?php
/**
 * Funções auxiliares usadas nos templates dos componentes.
 *
 * @package cidade-ativa-3
 */

/**
 * Lê um valor do array de dados do componente.
 */
function ca3_get( $args, $key, $default = '' ) {
	return isset( $args[ $key ] ) && '' !== $args[ $key ] ? $args[ $key ] : $default;
}

/**
 * Abre a <section> de um componente com as classes e a âncora corretas.
 */
function ca3_section_open( $type, $args, $extra_classes = array() ) {
	$slug    = ca3_component_file_slug( $type );
	$classes = array_merge( array( 'c-section', 'c-' . $slug ), (array) $extra_classes );

	$background = ca3_get( $args, 'background' );
	if ( $background ) {
		$classes[] = 'has-bg-' . $background;
	}

	$anchor = ca3_get( $args, 'anchor' );

	printf(
		'<section class="%1$s"%2$s>',
		esc_attr( implode( ' ', $classes ) ),
		$anchor ? ' id="' . esc_attr( sanitize_title( $anchor ) ) . '"' : ''
	);
}

function ca3_section_close() {
	echo '</section>';
}

/**
 * Texto de um campo rich_text/textarea, com parágrafos.
 */
function ca3_rich_text( $text ) {
	return wp_kses_post( wpautop( $text ) );
}

/**
 * Título com o ponto final destacado na cor de acento.
 */
function ca3_title_with_accent( $text ) {
	$text = esc_html( $text );

	return preg_replace( '/\.\s*$/', '<span class="u-accent">.</span>', $text );
}

/**
 * Imagem da biblioteca de mídia.
 */
function ca3_image( $attachment_id, $size = 'large', $class = '', $attrs = array() ) {
	if ( ! $attachment_id ) {
		return '';
	}

	$attrs = array_merge(
		array(
			'class'   => $class,
			'loading' => 'lazy',
		),
		$attrs
	);

	return wp_get_attachment_image( $attachment_id, $size, false, $attrs );
}

/**
 * URL de um ícone em assets/icons.
 */
function ca3_icon_url( $name ) {
	return CA3_URI . '/assets/icons/' . sanitize_file_name( $name ) . '.svg';
}

/**
 * Ícone que assume a cor do texto (ver assets/css/base/icons.css).
 */
function ca3_icon( $name, $class = '' ) {
	return sprintf( '<span class="c-icon c-icon--%s%s" aria-hidden="true"></span>', esc_attr( $name ), $class ? ' ' . esc_attr( $class ) : '' );
}

/**
 * Opções de ícone para botões (nome do arquivo => nome no admin).
 */
function ca3_button_icon_options() {
	return array(
		''         => __( 'Sem ícone', 'cidade-ativa-3' ),
		'acervo'   => __( 'Livros', 'cidade-ativa-3' ),
		'materias' => __( 'Páginas', 'cidade-ativa-3' ),
		'email'    => __( 'E-mail', 'cidade-ativa-3' ),
		'download' => __( 'Download', 'cidade-ativa-3' ),
	);
}

/**
 * Opções de estilo para botões (componente c-button).
 */
function ca3_button_style_options() {
	return array(
		'solid'   => __( 'Cheio', 'cidade-ativa-3' ),
		'outline' => __( 'Contorno', 'cidade-ativa-3' ),
	);
}

/**
 * Opções de cor para botões (famílias de cor do Figma).
 */
function ca3_button_color_options() {
	return array(
		'vermelho' => __( 'Vermelho', 'cidade-ativa-3' ),
		'amarelo'  => __( 'Amarelo', 'cidade-ativa-3' ),
		'verde'    => __( 'Verde', 'cidade-ativa-3' ),
		'azul'     => __( 'Azul', 'cidade-ativa-3' ),
		'rosa'     => __( 'Rosa', 'cidade-ativa-3' ),
		'laranja'  => __( 'Laranja', 'cidade-ativa-3' ),
	);
}

/**
 * Classes do componente c-button.
 *
 * @param array $args style (solid|outline), color, size (sm|md|lg), icon_only, class, new_tab.
 */
function ca3_button_classes( $args = array() ) {
	$args = wp_parse_args( $args, array( 'style' => 'solid', 'color' => 'vermelho', 'size' => 'lg', 'icon_only' => false, 'class' => '' ) );

	// Valores antigos do campo "Estilo".
	$legacy = array( 'primary' => 'solid', 'secondary' => 'outline' );
	$style  = isset( $legacy[ $args['style'] ] ) ? $legacy[ $args['style'] ] : $args['style'];

	$classes = array( 'c-button', 'c-button--' . ( 'outline' === $style ? 'outline' : 'solid' ), 'c-button--' . $args['size'] );

	if ( $args['color'] && 'vermelho' !== $args['color'] ) {
		$classes[] = 'c-button--' . $args['color'];
	}
	if ( $args['icon_only'] ) {
		$classes[] = 'c-button--icon';
	}
	if ( $args['class'] ) {
		$classes[] = $args['class'];
	}

	return implode( ' ', array_map( 'sanitize_html_class', explode( ' ', implode( ' ', $classes ) ) ) );
}

/**
 * Botão/link com ícone opcional.
 *
 * @param array $args Ver ca3_button_classes().
 */
function ca3_button( $label, $url, $style = 'solid', $icon = '', $args = array() ) {
	if ( ! $label || ! $url ) {
		return '';
	}

	return sprintf(
		'<a class="%1$s" href="%2$s"%5$s><span>%3$s</span>%4$s</a>',
		esc_attr( ca3_button_classes( array_merge( $args, array( 'style' => $style ) ) ) ),
		esc_url( $url ),
		esc_html( $label ),
		$icon ? ca3_icon( $icon ) : '',
		empty( $args['new_tab'] ) ? '' : ' target="_blank" rel="noopener"'
	);
}
