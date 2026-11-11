<?php
/**
 * Configurações básicas do tema.
 *
 * @package cidade-ativa-3
 */

function ca3_setup() {
	load_theme_textdomain( 'cidade-ativa-3', CA3_DIR . '/languages' );

	add_theme_support( 'title-tag' );
	add_theme_support( 'post-thumbnails' );
	add_theme_support( 'html5', array( 'search-form', 'gallery', 'caption', 'style', 'script' ) );

	// Tamanho usado nos cards de notícias.
	add_image_size( 'ca3-card', 722, 716, true );
}
add_action( 'after_setup_theme', 'ca3_setup' );
