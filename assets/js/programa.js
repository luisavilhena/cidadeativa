/**
 * Página do programa: quando o campo "Texto da coluna direita" está vazio,
 * leva para a coluna direita os primeiros parágrafos do texto do editor,
 * até a altura da coluna esquerda (produtos, downloads e ações).
 * Imagens, vídeos e galerias nunca são movidos. No tablet e no celular
 * (colunas empilhadas) o texto fica no lugar.
 */
( function () {
	'use strict';

	var side = document.querySelector( '[data-program-side]' );
	var aside = document.querySelector( '[data-program-aside]' );
	var content = document.querySelector( '[data-program-content]' );

	if ( ! side || ! aside || ! content ) {
		return;
	}

	var TEXT_TAGS = [ 'P', 'UL', 'OL', 'H2', 'H3', 'H4', 'H5', 'BLOCKQUOTE' ];
	var stacked = window.matchMedia( '(max-width: 1023px)' );
	var moved = [];
	var timer = null;

	function isText( element ) {
		return TEXT_TAGS.indexOf( element.tagName ) !== -1 && ! element.querySelector( 'img, iframe, video, figure' );
	}

	// Devolve os parágrafos ao texto, na ordem original.
	function reset() {
		for ( var i = moved.length - 1; i >= 0; i-- ) {
			content.insertBefore( moved[ i ], content.firstChild );
		}
		moved = [];
	}

	function fill() {
		reset();

		if ( stacked.matches ) {
			return;
		}

		var limit = aside.getBoundingClientRect().height;
		var element;

		while ( ( element = content.firstElementChild ) && isText( element ) ) {
			side.appendChild( element );

			if ( side.getBoundingClientRect().height > limit ) {
				content.insertBefore( element, content.firstChild );
				break;
			}

			moved.push( element );
		}
	}

	fill();

	window.addEventListener( 'resize', function () {
		clearTimeout( timer );
		timer = setTimeout( fill, 150 );
	} );

	if ( document.fonts && document.fonts.ready ) {
		document.fonts.ready.then( fill );
	}
} )();
