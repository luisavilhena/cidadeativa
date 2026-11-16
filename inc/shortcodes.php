<?php
/**
 * Shortcodes herdados do tema antigo (cidade-ativa-2), usados no texto dos posts.
 *
 * @package cidade-ativa-3
 */

/**
 * [create_button link="..." text="download"]
 *
 * Botão de download com o layout do tema (o mesmo do item do Acervo):
 * botão cheio com ícone de download, abrindo o link numa nova aba.
 * O texto "download" (padrão do tema antigo) vira o rótulo de
 * Acervo → Textos da página do item → Download.
 *
 * Nas páginas do Acervo o shortcode é lido à parte e vira o botão da
 * página (ver ca3_acervo_parse_legacy_content()).
 */
function ca3_shortcode_create_button( $atts ) {
	$atts = shortcode_atts(
		array(
			'link' => '',
			'text' => 'download',
		),
		$atts,
		'create_button'
	);

	// Aspas “curvas” coladas ao valor (ex.: link=”https://...”) não são
	// reconhecidas pelo WordPress e ficam dentro do valor: tira.
	$clean = function ( $value ) {
		return trim( html_entity_decode( (string) $value, ENT_QUOTES, 'UTF-8' ), " \t\n\r\0\x0B\"'“”„″‘’" );
	};

	$link = $clean( $atts['link'] );
	$text = $clean( $atts['text'] );

	if ( '' === $link ) {
		return '';
	}

	if ( '' === $text || 'download' === strtolower( $text ) ) {
		$label = function_exists( 'ca3_acervo_item_text' ) ? ca3_acervo_item_text( 'download_label' ) : '';
		$text  = $label ? $label : __( 'Download', 'cidade-ativa-3' );
	}

	return ca3_button(
		$text,
		$link,
		'solid',
		'download',
		array(
			'class'   => 'c-post-button',
			'new_tab' => true,
		)
	);
}
add_shortcode( 'create_button', 'ca3_shortcode_create_button' );
