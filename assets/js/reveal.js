/**
 * Títulos surgem ao entrar na tela: h1 e h2 na home, só h1 nas outras páginas.
 *
 * Igual à lista de Números: anima quando o título entra pela parte de
 * baixo da tela (rolando para baixo); rolando para cima, aparece já pronto.
 * Quando sai da tela, volta ao estado inicial para animar de novo.
 * Estilos em layout.css (.u-reveal). Desligado com "reduzir movimento".
 * Os títulos do rodapé e de "Entre em contato" não animam.
 */
( function () {
	'use strict';

	var reduceMotion = window.matchMedia && window.matchMedia( '(prefers-reduced-motion: reduce)' ).matches;
	// Na home anima h1 e h2; nas outras páginas, só o h1.
	// Sem animação no rodapé e na seção "Entre em contato".
	var selector = document.body.classList.contains( 'home' ) ? 'h1, h2' : 'h1';
	var headings = Array.prototype.filter.call( document.querySelectorAll( selector ), function ( heading ) {
		return ! heading.closest( '.c-site-footer, .c-contato' );
	} );

	if ( ! headings.length || reduceMotion || ! ( 'IntersectionObserver' in window ) ) {
		return;
	}

	var observer = new IntersectionObserver( function ( entries ) {
		entries.forEach( function ( entry ) {
			var heading = entry.target;

			if ( ! entry.isIntersecting ) {
				heading.classList.remove( 'is-visible', 'is-instant' );
				return;
			}

			// Entrou por cima (rolando para cima): mostra sem animar.
			heading.classList.toggle( 'is-instant', entry.boundingClientRect.top < 0 );
			heading.classList.add( 'is-visible' );
		} );
	}, { rootMargin: '0px 0px -8% 0px' } );

	headings.forEach( function ( heading ) {
		heading.classList.add( 'u-reveal' );
		observer.observe( heading );
	} );
}() );
