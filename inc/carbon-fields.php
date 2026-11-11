<?php
/**
 * Inicialização do Carbon Fields e registro dos campos dos componentes.
 *
 * @package cidade-ativa-3
 */

use Carbon_Fields\Container;
use Carbon_Fields\Field;

/**
 * Carrega o Carbon Fields instalado via Composer (vendor/).
 */
function ca3_boot_carbon_fields() {
	// O tema fica em uma subpasta do htdocs, então a URL precisa ser informada.
	// Ver https://github.com/htmlburger/carbon-fields/issues/457
	if ( ! defined( 'Carbon_Fields\URL' ) ) {
		define( 'Carbon_Fields\URL', CA3_URI . '/vendor/htmlburger/carbon-fields' );
	}

	require_once CA3_DIR . '/vendor/autoload.php';
	\Carbon_Fields\Carbon_Fields::boot();
}
add_action( 'after_setup_theme', 'ca3_boot_carbon_fields' );

/**
 * Campos comuns a todos os componentes (fundo e âncora).
 */
function ca3_common_component_fields() {
	return array(
		Field::make( 'select', 'background', __( 'Cor de fundo', 'cidade-ativa-3' ) )
			->set_options( ca3_background_options() )
			->set_width( 50 ),
		Field::make( 'text', 'anchor', __( 'Âncora (id da seção, para links do menu)', 'cidade-ativa-3' ) )
			->set_width( 50 ),
	);
}

/**
 * Opções de cor de fundo. A chave vira a classe CSS "has-bg-{chave}".
 */
function ca3_background_options() {
	return array(
		''            => __( 'Padrão do componente', 'cidade-ativa-3' ),
		'neutro'      => __( 'Fundo neutro', 'cidade-ativa-3' ),
		'branco'      => __( 'Branco', 'cidade-ativa-3' ),
		'verde-escuro' => __( 'Verde escuro', 'cidade-ativa-3' ),
		'lima'        => __( 'Verde lima', 'cidade-ativa-3' ),
		'azul'        => __( 'Azul claro', 'cidade-ativa-3' ),
		'amarelo'     => __( 'Amarelo', 'cidade-ativa-3' ),
	);
}

/**
 * Registra o campo "Componentes da página" (lista de linhas de conteúdo).
 */
function ca3_register_component_fields() {
	$complex = Field::make( 'complex', 'ca3_components', __( 'Linhas de conteúdo', 'cidade-ativa-3' ) )
		->set_layout( 'tabbed-vertical' )
		->setup_labels(
			array(
				'plural_name'   => __( 'Componentes', 'cidade-ativa-3' ),
				'singular_name' => __( 'Componente', 'cidade-ativa-3' ),
			)
		);

	foreach ( ca3_components_registry() as $type => $label ) {
		$file = CA3_DIR . '/inc/carbon-fields/components/' . ca3_component_file_slug( $type ) . '.php';

		if ( ! file_exists( $file ) ) {
			continue;
		}

		$fields = array_merge( require $file, ca3_common_component_fields() );
		$complex->add_fields( $type, $label, $fields );
	}

	Container::make( 'post_meta', __( 'Componentes da página', 'cidade-ativa-3' ) )
		->where( 'post_type', '=', 'page' )
		->where( 'post_template', '!=', CA3_ACERVO_TEMPLATE )
		->add_fields( array( $complex ) );
}
add_action( 'carbon_fields_register_fields', 'ca3_register_component_fields' );

/**
 * Registra os campos dos tipos de conteúdo (iniciativas, eixos, equipe...).
 */
function ca3_register_post_type_fields() {
	require_once CA3_DIR . '/inc/carbon-fields/post-types/iniciativa.php';
	require_once CA3_DIR . '/inc/carbon-fields/post-types/equipe.php';
	require_once CA3_DIR . '/inc/carbon-fields/templates/acervo.php';
	require_once CA3_DIR . '/inc/carbon-fields/post-types/acervo.php';
	require_once CA3_DIR . '/inc/carbon-fields/options/acervo-item.php';
	require_once CA3_DIR . '/inc/carbon-fields/options/noticias.php';
	require_once CA3_DIR . '/inc/carbon-fields/post-types/programa.php';
	require_once CA3_DIR . '/inc/carbon-fields/options/programas.php';
	require_once CA3_DIR . '/inc/carbon-fields/options/site-chrome.php';
}
add_action( 'carbon_fields_register_fields', 'ca3_register_post_type_fields' );

/**
 * Cores dos eixos. As chaves são as mesmas do tema antigo (cidade-ativa-2),
 * porque é esse valor que está salvo em cada eixo.
 */
function ca3_legacy_color_options() {
	return array(
		'#FF5000' => 'orange',
		'#FFDD00' => 'yellow',
		'#00C4B3' => 'green',
		'#C3D500' => 'green-lemon',
		'#904799' => 'purple',
		'#0065A0' => 'dark-blue',
		'#0080BB' => 'light-blue',
		'#FF5859' => 'salmon',
	);
}
