/**
 * Hero: mede o triângulo.
 *
 * O triângulo vai do topo do título do hero até o fim da primeira
 * linha de conteúdo da seção seguinte (ex.: 1ª linha de Números).
 * Os valores são passados ao CSS (hero.css) pelas variáveis
 * --hero-visual-offset e --hero-visual-height.
 */
( function () {
	'use strict';

	// Posição de um elemento em relação ao topo do documento.
	function pageTop( element ) {
		return element.getBoundingClientRect().top + window.scrollY;
	}

	function pageBottom( element ) {
		return element.getBoundingClientRect().bottom + window.scrollY;
	}

	// Desce pelos elementos que têm um único filho até chegar ao nível
	// com vários itens (ex.: section > .l-container > ul > li).
	function contentItems( section ) {
		var element = section;

		while ( element.children.length === 1 ) {
			element = element.children[ 0 ];
		}

		return Array.prototype.slice.call( element.children );
	}

	// Fim da primeira linha de conteúdo da seção: maior "bottom" entre os
	// itens que começam na mesma altura que o primeiro.
	function firstRowBottom( section ) {
		var items = contentItems( section );

		if ( ! items.length ) {
			return pageBottom( section );
		}

		var rowTop = Math.round( pageTop( items[ 0 ] ) );

		return items.reduce( function ( bottom, item ) {
			return Math.round( pageTop( item ) ) === rowTop ? Math.max( bottom, pageBottom( item ) ) : bottom;
		}, 0 );
	}

	function initHero( hero ) {
		var title = hero.querySelector( '.c-hero__title' ) || hero.querySelector( '.c-hero__content' );
		var next = hero.nextElementSibling;

		if ( ! title ) {
			return;
		}

		function update() {
			var top = pageTop( title );
			var bottom = next ? firstRowBottom( next ) : pageBottom( hero );

			hero.style.setProperty( '--hero-visual-offset', ( top - pageTop( hero ) ) + 'px' );
			hero.style.setProperty( '--hero-visual-height', Math.max( bottom - top, 0 ) + 'px' );
		}

		update();
		window.addEventListener( 'resize', update );
		window.addEventListener( 'load', update );

		if ( document.fonts && document.fonts.ready ) {
			document.fonts.ready.then( update );
		}
	}

	document.querySelectorAll( '.c-hero' ).forEach( initHero );
} )();
