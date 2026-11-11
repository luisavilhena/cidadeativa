<?php
/**
 * Página de uma notícia (single-post.php).
 *
 * O texto continua vindo do editor. Na hora de exibir, as imagens seguem o
 * grid do Figma:
 *   - imagem sozinha ou com legenda ([caption]) → largura toda do contêiner,
 *     com a legenda numa faixa branca
 *   - duas imagens seguidas ou [gallery] com 2 imagens → lado a lado
 *   - [gallery] com 3 ou mais imagens → carrossel
 *   - vídeo (link do YouTube/Vimeo sozinho no parágrafo, ou iframe) → largura toda
 * O texto fica numa coluna de 560px.
 *
 * @package cidade-ativa-3
 */

/**
 * Lê um texto da página da notícia (Posts → Listagem de Notícias → Página da notícia).
 */
function ca3_news_single_text( $name ) {
	return ca3_news_option( 'single_' . $name );
}

/**
 * Figura (imagem + legenda opcional) no padrão do grid.
 */
function ca3_post_figure( $image_html, $caption = '' ) {
	$caption_html = '';
	if ( '' !== trim( wp_strip_all_tags( $caption ) ) ) {
		$caption_html = '<figcaption class="c-post-figure__caption">' . wp_kses_post( $caption ) . '</figcaption>';
	}

	return '<figure class="c-post-figure">' . $image_html . $caption_html . '</figure>';
}

/**
 * [caption] → figura na largura toda.
 */
function ca3_post_caption_shortcode( $output, $attr, $content ) {
	$caption = isset( $attr['caption'] ) ? $attr['caption'] : '';

	return ca3_post_figure( do_shortcode( $content ), $caption );
}

/**
 * [gallery] → 1 imagem: figura; 2: lado a lado; 3 ou mais: carrossel.
 */
function ca3_post_gallery_shortcode( $output, $attr ) {
	$ids = array();

	if ( ! empty( $attr['ids'] ) ) {
		$ids = array_filter( array_map( 'absint', explode( ',', $attr['ids'] ) ) );
	} elseif ( ! empty( $attr['include'] ) ) {
		$ids = array_filter( array_map( 'absint', explode( ',', $attr['include'] ) ) );
	} else {
		$ids = get_posts(
			array(
				'post_parent'    => get_the_ID(),
				'post_type'      => 'attachment',
				'post_mime_type' => 'image',
				'numberposts'    => -1,
				'orderby'        => 'menu_order',
				'order'          => 'ASC',
				'fields'         => 'ids',
			)
		);
	}

	$figures = array();
	foreach ( $ids as $id ) {
		$image = wp_get_attachment_image( $id, 'large', false, array( 'loading' => 'lazy' ) );
		if ( $image ) {
			$figures[] = ca3_post_figure( $image, wp_get_attachment_caption( $id ) );
		}
	}

	if ( ! $figures ) {
		return '';
	}

	if ( count( $figures ) === 1 ) {
		return $figures[0];
	}

	if ( count( $figures ) === 2 ) {
		return '<div class="c-post-images">' . implode( '', $figures ) . '</div>';
	}

	$slides = '';
	foreach ( $figures as $figure ) {
		$slides .= '<li class="c-carousel__slide">' . $figure . '</li>';
	}

	$arrow = function ( $direction ) {
		return sprintf(
			'<button type="button" class="%1$s" data-carousel-%2$s aria-label="%3$s">%4$s</button>',
			esc_attr( ca3_button_classes( array( 'icon_only' => true, 'class' => 'c-post-gallery__arrow is-' . $direction ) ) ),
			esc_attr( $direction ),
			esc_attr( ca3_news_single_text( 'gallery_' . $direction ) ),
			ca3_icon( 'arrow-' . $direction )
		);
	};

	return '<div class="c-carousel c-post-gallery" data-carousel>'
		. $arrow( 'prev' )
		. '<ul class="c-carousel__track" data-carousel-track>' . $slides . '</ul>'
		. $arrow( 'next' )
		. '<div class="c-carousel__dots c-post-gallery__dots" data-carousel-dots></div>'
		. '</div>';
}

/**
 * Monta o grid no HTML do texto:
 *   - <div>/<span> sem classe que só envolvem o texto são desembrulhados
 *   - parágrafo só com link de vídeo (YouTube/Vimeo) ou iframe → vídeo
 *   - parágrafo só com imagem → figura; figuras seguidas → lado a lado
 */
function ca3_post_group_images( $html ) {
	if ( false === strpos( $html, '<img' ) && false === strpos( $html, '<iframe' ) && false === stripos( $html, 'youtu' ) && false === stripos( $html, 'vimeo' ) && false === strpos( $html, '<div' ) ) {
		return $html;
	}

	$doc = new DOMDocument();
	libxml_use_internal_errors( true );
	$doc->loadHTML( '<?xml encoding="utf-8"?><div id="ca3-post">' . $html . '</div>', LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD );
	libxml_clear_errors();

	$root = $doc->getElementById( 'ca3-post' );
	if ( ! $root ) {
		return $html;
	}

	$is_figure = function ( $node ) {
		return $node instanceof DOMElement && 'figure' === $node->nodeName && false !== strpos( $node->getAttribute( 'class' ), 'c-post-figure' );
	};

	// 0) Desembrulha <div>/<span> sem classe no primeiro nível (wrappers do texto antigo).
	do {
		$unwrapped = false;
		foreach ( iterator_to_array( $root->childNodes ) as $node ) {
			if ( $node instanceof DOMElement && in_array( $node->nodeName, array( 'div', 'span' ), true ) && '' === trim( $node->getAttribute( 'class' ) ) && '' === $node->getAttribute( 'id' ) ) {
				while ( $node->firstChild ) {
					$root->insertBefore( $node->firstChild, $node );
				}
				$root->removeChild( $node );
				$unwrapped = true;
			}
		}
	} while ( $unwrapped );

	// 0b) Vídeos: parágrafo só com iframe, ou só com um link de YouTube/Vimeo.
	foreach ( iterator_to_array( $root->childNodes ) as $node ) {
		if ( ! $node instanceof DOMElement || ! in_array( $node->nodeName, array( 'p', 'iframe' ), true ) ) {
			continue;
		}

		$embed = '';
		$text  = trim( str_replace( "\xc2\xa0", ' ', $node->textContent ) );

		if ( 'iframe' === $node->nodeName ) {
			$embed = $doc->saveHTML( $node );
		} elseif ( 1 === $node->getElementsByTagName( 'iframe' )->length && '' === $text ) {
			$embed = $doc->saveHTML( $node->getElementsByTagName( 'iframe' )->item( 0 ) );
		} elseif ( preg_match( '#^https?://(www\.)?(youtube\.com|youtu\.be|vimeo\.com)/\S+$#i', $text ) && isset( $GLOBALS['wp_embed'] ) ) {
			$embed = $GLOBALS['wp_embed']->shortcode( array(), html_entity_decode( $text ) );
			if ( false === strpos( $embed, '<iframe' ) ) {
				$embed = '';
			}
		}

		if ( '' === $embed ) {
			continue;
		}

		$video = new DOMDocument();
		$video->loadHTML( '<?xml encoding="utf-8"?><figure id="ca3-video" class="c-post-video">' . $embed . '</figure>', LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD );
		libxml_clear_errors();
		$figure = $video->getElementById( 'ca3-video' );
		if ( ! $figure ) {
			continue;
		}
		$figure->removeAttribute( 'id' );
		$root->insertBefore( $doc->importNode( $figure, true ), $node );
		$root->removeChild( $node );
	}

	// 1) <p> só com imagem (ou link com imagem) → <figure class="c-post-figure">.
	foreach ( iterator_to_array( $root->childNodes ) as $node ) {
		if ( ! $node instanceof DOMElement || 'p' !== $node->nodeName || '' !== trim( str_replace( "\xc2\xa0", '', $node->textContent ) ) ) {
			continue;
		}

		$images = $node->getElementsByTagName( 'img' );
		if ( 0 === $images->length ) {
			continue;
		}

		foreach ( iterator_to_array( $images ) as $image ) {
			$item   = ( 'a' === $image->parentNode->nodeName ) ? $image->parentNode : $image;
			$figure = $doc->createElement( 'figure' );
			$figure->setAttribute( 'class', 'c-post-figure' );
			$figure->appendChild( $item );
			$root->insertBefore( $figure, $node );
		}
		$root->removeChild( $node );
	}

	// 2) Figuras seguidas (ignorando espaços) → grupo lado a lado.
	$run = array();
	$flush = function () use ( &$run, $doc, $root ) {
		if ( count( $run ) > 1 ) {
			$group = $doc->createElement( 'div' );
			$group->setAttribute( 'class', 'c-post-images' );
			$root->insertBefore( $group, $run[0] );
			foreach ( $run as $figure ) {
				$group->appendChild( $figure );
			}
		}
		$run = array();
	};

	foreach ( iterator_to_array( $root->childNodes ) as $node ) {
		if ( $node instanceof DOMText && '' === trim( $node->textContent ) ) {
			continue;
		}
		if ( $is_figure( $node ) ) {
			$run[] = $node;
			continue;
		}
		$flush();
	}
	$flush();

	$output = '';
	foreach ( $root->childNodes as $child ) {
		$output .= $doc->saveHTML( $child );
	}

	return $output;
}

/**
 * Imprime o texto da notícia com o grid de imagens.
 */
function ca3_the_post_content() {
	add_filter( 'img_caption_shortcode', 'ca3_post_caption_shortcode', 10, 3 );
	add_filter( 'post_gallery', 'ca3_post_gallery_shortcode', 10, 2 );
	add_filter( 'the_content', 'ca3_post_group_images', 20 );

	the_content();

	remove_filter( 'img_caption_shortcode', 'ca3_post_caption_shortcode', 10 );
	remove_filter( 'post_gallery', 'ca3_post_gallery_shortcode', 10 );
	remove_filter( 'the_content', 'ca3_post_group_images', 20 );
}

/**
 * Notícias relacionadas: com as mesmas tags; completa com as mais recentes.
 */
function ca3_news_related_posts( $post_id ) {
	$count      = (int) ca3_news_single_text( 'related_count' );
	$count      = $count > 0 ? $count : 6;
	$categories = array_filter( array_map( 'absint', (array) ca3_news_option( 'categories' ) ) );
	$base       = array(
		'post_type'           => 'post',
		'post_status'         => 'publish',
		'ignore_sticky_posts' => true,
		'no_found_rows'       => true,
		'fields'              => 'ids',
	);

	if ( $categories ) {
		$base['category__in'] = $categories;
	}

	$ids  = array();
	$tags = wp_get_post_tags( $post_id, array( 'fields' => 'ids' ) );

	if ( $tags ) {
		$ids = get_posts( array_merge( $base, array( 'tag__in' => $tags, 'post__not_in' => array( $post_id ), 'posts_per_page' => $count ) ) );
	}

	if ( count( $ids ) < $count ) {
		$ids = array_merge(
			$ids,
			get_posts( array_merge( $base, array( 'post__not_in' => array_merge( array( $post_id ), $ids ), 'posts_per_page' => $count - count( $ids ) ) ) )
		);
	}

	return $ids;
}
