<?php
/**
 * Registro e renderização dos componentes (linhas de conteúdo) das páginas.
 *
 * Para criar um componente novo:
 *   1. Adicione o slug e o nome abaixo, em ca3_components_registry().
 *   2. Crie inc/carbon-fields/components/{slug}.php retornando os campos.
 *   3. Crie template-parts/components/{slug}.php com o HTML.
 *   4. Crie assets/css/components/{slug}.css com os estilos.
 * (Use "_" no slug; nos nomes de arquivo ele vira "-".)
 *
 * @package cidade-ativa-3
 */

/**
 * Lista de componentes disponíveis: slug => nome exibido no admin.
 */
function ca3_components_registry() {
	return array(
		// Página inicial.
		'hero'                    => __( 'Hero (abertura)', 'cidade-ativa-3' ),
		'numeros'                 => __( 'Números', 'cidade-ativa-3' ),
		'pilares'                 => __( 'Pilares (colunas com destaque)', 'cidade-ativa-3' ),
		'programas'               => __( 'Nossos programas (carrossel)', 'cidade-ativa-3' ),
		'logos'                   => __( 'Logos (parcerias / premiações)', 'cidade-ativa-3' ),
		'quem_somos'              => __( 'Quem somos', 'cidade-ativa-3' ),
		'acervo'                  => __( 'Acervo', 'cidade-ativa-3' ),
		'noticias'                => __( 'Notícias (últimas do blog)', 'cidade-ativa-3' ),

		// Conteúdos genéricos.
		'conteudo_colunas'        => __( 'Conteúdo: 2 colunas (1, 2, 6)', 'cidade-ativa-3' ),
		'conteudo_texto_imagem'   => __( 'Conteúdo: título, texto e imagem (3, 9)', 'cidade-ativa-3' ),
		'conteudo_zigzag'         => __( 'Conteúdo: zigue-zague (4)', 'cidade-ativa-3' ),
		'conteudo_titulo_imagem'  => __( 'Conteúdo: título e imagem grande (5)', 'cidade-ativa-3' ),
		'conteudo_titulo_especial' => __( 'Conteúdo: título especial (7)', 'cidade-ativa-3' ),
		'conteudo_imagem_texto'   => __( 'Conteúdo: texto e imagem à direita (8)', 'cidade-ativa-3' ),
	);
}

/**
 * Converte o slug do componente no nome usado nos arquivos.
 */
function ca3_component_file_slug( $type ) {
	return str_replace( '_', '-', $type );
}

/**
 * Retorna os componentes salvos em uma página.
 */
function ca3_get_components( $post_id = null ) {
	if ( ! function_exists( 'carbon_get_post_meta' ) ) {
		return array();
	}

	$post_id    = $post_id ? $post_id : get_the_ID();
	$components = carbon_get_post_meta( $post_id, 'ca3_components' );

	return is_array( $components ) ? $components : array();
}

/**
 * Imprime todos os componentes de uma página, na ordem do admin.
 */
function ca3_render_components( $post_id = null ) {
	$registry = ca3_components_registry();

	foreach ( ca3_get_components( $post_id ) as $index => $component ) {
		$type = isset( $component['_type'] ) ? $component['_type'] : '';

		if ( ! isset( $registry[ $type ] ) ) {
			continue;
		}

		$component['_index'] = $index;

		get_template_part( 'template-parts/components/' . ca3_component_file_slug( $type ), null, $component );
	}
}
