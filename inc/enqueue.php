<?php
/**
 * Carregamento de CSS e JS.
 *
 * Os CSS de base (variáveis e componentes de interface) são carregados em
 * todas as páginas. O CSS de cada componente de página e de cada template
 * só é carregado onde é usado.
 *
 * @package cidade-ativa-3
 */

function ca3_asset_version( $relative_path ) {
	$file = CA3_DIR . '/' . $relative_path;

	return file_exists( $file ) ? (string) filemtime( $file ) : CA3_VERSION;
}

function ca3_enqueue_style( $handle, $relative_path, $deps = array() ) {
	wp_enqueue_style( $handle, CA3_URI . '/' . $relative_path, $deps, ca3_asset_version( $relative_path ) );
}

function ca3_enqueue_script( $name ) {
	$path = 'assets/js/' . $name . '.js';
	wp_enqueue_script( 'ca3-' . $name, CA3_URI . '/' . $path, array(), ca3_asset_version( $path ), true );
}

function ca3_enqueue_assets() {
	// Base: variáveis e componentes de interface (a ordem importa).
	ca3_enqueue_style( 'ca3-fonts', 'assets/css/base/fonts.css' );
	ca3_enqueue_style( 'ca3-tokens', 'assets/css/base/tokens.css', array( 'ca3-fonts' ) );
	ca3_enqueue_style( 'ca3-reset', 'assets/css/base/reset.css', array( 'ca3-tokens' ) );
	ca3_enqueue_style( 'ca3-layout', 'assets/css/base/layout.css', array( 'ca3-reset' ) );
	ca3_enqueue_style( 'ca3-icons', 'assets/css/base/icons.css', array( 'ca3-layout' ) );
	ca3_enqueue_style( 'ca3-buttons', 'assets/css/base/buttons.css', array( 'ca3-icons' ) );
	ca3_enqueue_style( 'ca3-search', 'assets/css/base/search.css', array( 'ca3-buttons' ) );
	ca3_enqueue_style( 'ca3-pagination', 'assets/css/base/pagination.css', array( 'ca3-buttons' ) );
	ca3_enqueue_style( 'ca3-carousel', 'assets/css/base/carousel.css', array( 'ca3-buttons' ) );
	ca3_enqueue_style( 'ca3-link-card', 'assets/css/base/link-card.css', array( 'ca3-buttons' ) );
	ca3_enqueue_style( 'ca3-content', 'assets/css/base/content.css', array( 'ca3-buttons' ) );
	ca3_enqueue_style( 'ca3-site-header', 'assets/css/base/site-header.css', array( 'ca3-content' ) );
	ca3_enqueue_style( 'ca3-site-footer', 'assets/css/base/site-footer.css', array( 'ca3-content' ) );
	ca3_enqueue_style( 'ca3-contato', 'assets/css/base/contato.css', array( 'ca3-content' ) );
	ca3_enqueue_style( 'ca3-dialog', 'assets/css/base/dialog.css', array( 'ca3-content' ) );
	ca3_enqueue_script( 'header' );
	ca3_enqueue_script( 'reveal' );

	// Listagem de Notícias (página de posts).
	if ( is_home() ) {
		ca3_enqueue_style( 'ca3-news-card', 'assets/css/components/news-card.css', array( 'ca3-content' ) );
		ca3_enqueue_style( 'ca3-noticias-lista', 'assets/css/templates/noticias.css', array( 'ca3-news-card' ) );
		ca3_enqueue_script( 'listing' );
		return;
	}

	if ( ! is_singular() ) {
		return;
	}

	// Página de uma notícia.
	if ( is_singular( 'post' ) ) {
		ca3_enqueue_style( 'ca3-news-card', 'assets/css/components/news-card.css', array( 'ca3-content' ) );
		ca3_enqueue_style( 'ca3-post-content', 'assets/css/components/post-content.css', array( 'ca3-news-card' ) );
		ca3_enqueue_style( 'ca3-noticia', 'assets/css/templates/noticia.css', array( 'ca3-post-content' ) );
		ca3_enqueue_script( 'carousel' );
	}

	// Página de um programa / iniciativa.
	if ( is_singular( 'ca-iniciativa' ) ) {
		ca3_enqueue_style( 'ca3-post-content', 'assets/css/components/post-content.css', array( 'ca3-content' ) );
		ca3_enqueue_style( 'ca3-program-card', 'assets/css/components/program-card.css', array( 'ca3-content' ) );
		ca3_enqueue_style( 'ca3-programas', 'assets/css/components/programas.css', array( 'ca3-program-card' ) );
		ca3_enqueue_style( 'ca3-programa', 'assets/css/templates/programa.css', array( 'ca3-post-content', 'ca3-programas' ) );
		ca3_enqueue_script( 'carousel' );
		ca3_enqueue_script( 'programa' );
	}

	// Listagem do Acervo.
	if ( is_page_template( CA3_ACERVO_TEMPLATE ) ) {
		ca3_enqueue_style( 'ca3-acervo-card', 'assets/css/components/acervo-card.css', array( 'ca3-content' ) );
		ca3_enqueue_style( 'ca3-acervo', 'assets/css/templates/acervo.css', array( 'ca3-acervo-card' ) );
		ca3_enqueue_script( 'listing' );
	}

	// Página de um item do Acervo.
	if ( is_singular( 'ca-biblioteca' ) ) {
		ca3_enqueue_style( 'ca3-acervo-card', 'assets/css/components/acervo-card.css', array( 'ca3-content' ) );
		ca3_enqueue_style( 'ca3-acervo-item', 'assets/css/templates/acervo-item.css', array( 'ca3-acervo-card' ) );
		ca3_enqueue_script( 'carousel' );
	}

	// CSS e JS extras de componentes de página.
	$component_styles  = array(
		'noticias'  => array( 'news-card' ),
		'programas' => array( 'program-card' ),
		'acervo'    => array( 'acervo-card' ),
		'quem_somos' => array( 'equipe' ),
	);
	$component_scripts = array(
		'programas'  => 'carousel',
		'quem_somos' => 'dialog',
	);

	// Componentes usados na página atual.
	$types = array_unique( wp_list_pluck( ca3_get_components( get_queried_object_id() ), '_type' ) );

	foreach ( $types as $type ) {
		$slug = ca3_component_file_slug( $type );

		foreach ( isset( $component_styles[ $type ] ) ? $component_styles[ $type ] : array() as $extra ) {
			ca3_enqueue_style( 'ca3-' . $extra, 'assets/css/components/' . $extra . '.css', array( 'ca3-content' ) );
		}

		ca3_enqueue_style( 'ca3-' . $slug, 'assets/css/components/' . $slug . '.css', array( 'ca3-content' ) );

		$script = isset( $component_scripts[ $type ] ) ? $component_scripts[ $type ] : $slug;
		if ( file_exists( CA3_DIR . '/assets/js/' . $script . '.js' ) ) {
			ca3_enqueue_script( $script );
		}
	}
}
add_action( 'wp_enqueue_scripts', 'ca3_enqueue_assets' );
