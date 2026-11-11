<?php
/**
 * Página de um item do Acervo (ca-biblioteca).
 *
 * Cada informação vem do campo novo do Carbon Fields; se ele estiver vazio,
 * é lida do texto antigo do post, que segue este padrão:
 *   <iframe> (vídeo) ou <img> (capa)
 *   <p>descrição...</p>
 *   <p>Mês/ano de publicação: julho/2020</p>
 *   <p>Ficha técnica:</p>
 *   <p>Autores: ...</p>
 *   [create_button link="..." text="download"]
 *   <h3>Saiba Mais</h3> <hr> <p><a href="...">→ link</a></p>
 *
 * @package cidade-ativa-3
 */

/**
 * Lê um texto fixo da página do item (Acervo → Textos da página do item).
 */
function ca3_acervo_item_text( $name ) {
	return function_exists( 'carbon_get_theme_option' ) ? carbon_get_theme_option( 'ca3_acervo_item_' . $name ) : '';
}

/**
 * Separa o texto antigo do post nas partes do layout.
 *
 * @return array description (HTML), video (URL), date, ficha (label/value), download (URL), links (text/url).
 */
function ca3_acervo_parse_legacy_content( $content ) {
	$data = array(
		'description' => '',
		'video'       => '',
		'date'        => '',
		'ficha'       => array(),
		'download'    => '',
		'links'       => array(),
	);

	// Botão de download (shortcode do tema antigo).
	if ( preg_match( '/\[create_button[^\]]*link="([^"]+)"/', $content, $match ) ) {
		$data['download'] = $match[1];
	}
	$content = preg_replace( '/\[create_button[^\]]*\]/', '', $content );

	if ( '' === trim( $content ) ) {
		return $data;
	}

	$doc = new DOMDocument();
	libxml_use_internal_errors( true );
	$doc->loadHTML( '<?xml encoding="utf-8"?><div id="ca3-root">' . $content . '</div>', LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD );
	libxml_clear_errors();

	$xpath = new DOMXPath( $doc );
	$root  = $doc->getElementById( 'ca3-root' );
	if ( ! $root ) {
		return $data;
	}

	$remove = function ( $node ) {
		if ( $node && $node->parentNode ) {
			$node->parentNode->removeChild( $node );
		}
	};

	// Vídeo: o primeiro iframe.
	$iframe = $xpath->query( '//iframe' )->item( 0 );
	if ( $iframe ) {
		$data['video'] = $iframe->getAttribute( 'src' );
	}

	// Remove comentários, iframes e imagens (a capa vem da imagem destacada).
	foreach ( iterator_to_array( $xpath->query( '//comment() | //iframe | //img' ) ) as $node ) {
		$parent = $node->parentNode;
		$remove( $node );
		// Link que só envolvia a imagem.
		if ( $parent && 'a' === $parent->nodeName && '' === trim( $parent->textContent ) ) {
			$remove( $parent );
		}
	}

	// "Saiba mais": título + tudo o que vem depois dele.
	foreach ( iterator_to_array( $xpath->query( '//h1 | //h2 | //h3 | //h4 | //h5 | //h6' ) ) as $heading ) {
		if ( ! preg_match( '/saiba\s+mais/iu', $heading->textContent ) ) {
			continue;
		}

		$node = $heading;
		while ( $node && $node !== $root ) {
			$next = $node->nextSibling;
			while ( $next ) {
				foreach ( $xpath->query( './/a | self::a', $next ) as $link ) {
					$text = trim( preg_replace( '/^\s*(→|->)\s*/u', '', $link->textContent ) );
					if ( $link->getAttribute( 'href' ) && '' !== $text ) {
						$data['links'][] = array(
							'text' => $text,
							'url'  => $link->getAttribute( 'href' ),
						);
					}
				}
				$following = $next->nextSibling;
				$remove( $next );
				$next = $following;
			}
			$node = $node->parentNode;
		}
		$remove( $heading );
		break;
	}

	// Data e ficha técnica.
	$in_ficha = false;
	foreach ( iterator_to_array( $xpath->query( '//p' ) ) as $paragraph ) {
		$text = trim( preg_replace( '/\s+/u', ' ', $paragraph->textContent ) );

		if ( preg_match( '/^m[êe]s\s*\/\s*ano de publica[çc][ãa]o\s*:?\s*(.*)$/iu', $text, $match ) ) {
			$data['date'] = trim( $match[1] );
			$remove( $paragraph );
			continue;
		}

		if ( preg_match( '/^ficha t[ée]cnica\s*:?\s*$/iu', $text ) ) {
			$in_ficha = true;
			$remove( $paragraph );
			continue;
		}

		if ( $in_ficha && '' !== $text ) {
			$parts = array_map( 'trim', explode( ':', $text, 2 ) );
			$data['ficha'][] = array(
				'label' => count( $parts ) > 1 ? $parts[0] : '',
				'value' => rtrim( end( $parts ), ' ;' ),
			);
			$remove( $paragraph );
		}
	}

	// O que sobrou é a descrição.
	$html = '';
	foreach ( $root->childNodes as $child ) {
		$html .= $doc->saveHTML( $child );
	}

	$allowed = array(
		'p'      => array(),
		'br'     => array(),
		'strong' => array(),
		'b'      => array(),
		'em'     => array(),
		'i'      => array(),
		'ul'     => array(),
		'ol'     => array(),
		'li'     => array(),
		'a'      => array(
			'href'   => true,
			'target' => true,
			'rel'    => true,
		),
	);

	$html = wpautop( wp_kses( $html, $allowed ) );
	$html = preg_replace( '/<p>(\s|&nbsp;|\x{FEFF}|<br\s*\/?>)*<\/p>/u', '', $html );

	$data['description'] = trim( $html );

	return $data;
}

/**
 * Dados do item: campos novos com o texto antigo como reserva.
 */
function ca3_acervo_item_data( $post_id ) {
	$legacy = ca3_acervo_parse_legacy_content( get_post_field( 'post_content', $post_id ) );
	$field  = function ( $name ) use ( $post_id ) {
		return function_exists( 'carbon_get_post_meta' ) ? carbon_get_post_meta( $post_id, 'ca3_pub_' . $name ) : '';
	};

	// Os subcampos se chamam "item" e "conteudo" ("value" é reservado no Carbon Fields).
	$ficha = array();
	foreach ( (array) $field( 'ficha' ) as $row ) {
		if ( '' !== trim( $row['item'] . $row['conteudo'] ) ) {
			$ficha[] = array(
				'label' => $row['item'],
				'value' => $row['conteudo'],
			);
		}
	}

	$links = array_values(
		array_filter(
			(array) $field( 'links' ),
			function ( $row ) {
				return '' !== trim( $row['url'] );
			}
		)
	);

	return array(
		'description' => $legacy['description'],
		'video'       => $legacy['video'],
		'date'        => $field( 'date' ) ? $field( 'date' ) : $legacy['date'],
		'ficha'       => $ficha ? $ficha : $legacy['ficha'],
		'download'    => $field( 'download' ) ? $field( 'download' ) : $legacy['download'],
		'links'       => $links ? $links : $legacy['links'],
	);
}

/**
 * Itens relacionados: mesma categoria do acervo, sem o item atual.
 */
function ca3_acervo_related_query( $post_id ) {
	$count      = (int) ca3_acervo_item_text( 'related_count' );
	$categories = wp_list_pluck( get_the_category( $post_id ), 'term_id' );

	$args = array(
		'post_type'           => 'ca-biblioteca',
		'post_status'         => 'publish',
		'posts_per_page'      => $count > 0 ? $count : 8,
		'post__not_in'        => array( $post_id ),
		'ignore_sticky_posts' => true,
		'no_found_rows'       => true,
	);

	if ( $categories ) {
		$args['category__in'] = $categories;
	}

	return new WP_Query( $args );
}

/**
 * Página que usa o template da listagem do Acervo.
 */
function ca3_acervo_page_id() {
	static $page_id = null;

	if ( null !== $page_id ) {
		return $page_id;
	}

	$pages = get_pages(
		array(
			'meta_key'   => '_wp_page_template',
			'meta_value' => CA3_ACERVO_TEMPLATE,
			'number'     => 1,
		)
	);

	$page_id = $pages ? $pages[0]->ID : 0;

	return $page_id;
}
