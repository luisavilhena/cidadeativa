/**
 * Números: os itens surgem da esquerda para a direita.
 *
 * A animação (ver numeros.css) roda quando a lista entra na tela pela
 * parte de baixo, ou seja, rolando a página para baixo. Rolando para cima,
 * os itens aparecem já prontos. Quando a lista sai da tela, ela volta ao
 * estado inicial para animar de novo na próxima vez.
 */
( function () {
	'use strict';

	var reduceMotion = window.matchMedia && window.matchMedia( '(prefers-reduced-motion: reduce)' ).matches;
	var lists = document.querySelectorAll( '.c-numeros__list' );

	if ( ! lists.length || reduceMotion || ! ( 'IntersectionObserver' in window ) ) {
		return;
	}

	var observer = new IntersectionObserver( function ( entries ) {
		entries.forEach( function ( entry ) {
			var list = entry.target;

			if ( ! entry.isIntersecting ) {
				list.classList.remove( 'is-visible', 'is-instant' );
				return;
			}

			// Entrou por cima (rolando para cima): mostra sem animar.
			var fromTop = entry.boundingClientRect.top < 0;

			list.classList.toggle( 'is-instant', fromTop );
			list.classList.add( 'is-visible' );
		} );
	}, { rootMargin: '0px 0px -10% 0px' } ); // anima quando a lista já subiu um pouco na tela

	lists.forEach( function ( list ) {
		Array.prototype.forEach.call( list.children, function ( item, index ) {
			item.style.setProperty( '--reveal-index', index );
		} );

		list.classList.add( 'is-animated' );
		observer.observe( list );
	} );
}() );
