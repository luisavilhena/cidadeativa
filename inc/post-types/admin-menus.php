<?php
/**
 * Menus do admin que agrupam os tipos de conteúdo.
 * Os nomes (slugs) são os mesmos do tema antigo (cidade-ativa-2).
 *
 * @package cidade-ativa-3
 */

function ca3_register_admin_menus() {
	add_menu_page( __( 'Iniciativas', 'cidade-ativa-3' ), __( 'Iniciativas', 'cidade-ativa-3' ), 'manage_options', 'cidade-ativa-iniciativas', '', 'dashicons-megaphone', 5 );
	add_menu_page( __( 'Acervo', 'cidade-ativa-3' ), __( 'Acervo', 'cidade-ativa-3' ), 'manage_options', 'cidade-ativa-biblioteca', '', 'dashicons-media-document', 5 );
	add_menu_page( __( 'Equipe', 'cidade-ativa-3' ), __( 'Equipe', 'cidade-ativa-3' ), 'manage_options', 'cidade-ativa-equipe', '', 'dashicons-universal-access-alt', 5 );
}
add_action( 'admin_menu', 'ca3_register_admin_menus' );
