/**
 * Cabeçalho: diminui ao rolar a página (classe is-scrolled) e abre/fecha
 * o menu no celular.
 */
( function () {
	'use strict';

	var header = document.querySelector( '[data-site-header]' );
	if ( ! header ) {
		return;
	}

	var toggle = header.querySelector( '[data-menu-toggle]' );
	var ticking = false;

	function onScroll() {
		header.classList.toggle( 'is-scrolled', window.scrollY > 10 );
		ticking = false;
	}

	window.addEventListener( 'scroll', function () {
		if ( ! ticking ) {
			window.requestAnimationFrame( onScroll );
			ticking = true;
		}
	}, { passive: true } );
	onScroll();

	if ( ! toggle ) {
		return;
	}

	function setOpen( open ) {
		header.classList.toggle( 'is-menu-open', open );
		document.body.classList.toggle( 'has-menu-open', open );
		toggle.setAttribute( 'aria-expanded', open ? 'true' : 'false' );
		toggle.setAttribute( 'aria-label', toggle.getAttribute( open ? 'data-label-close' : 'data-label-open' ) || '' );
	}

	toggle.addEventListener( 'click', function () {
		setOpen( ! header.classList.contains( 'is-menu-open' ) );
	} );

	var close = header.querySelector( '[data-menu-close]' );
	if ( close ) {
		close.addEventListener( 'click', function () {
			setOpen( false );
			toggle.focus();
		} );
	}

	// Fecha ao clicar num link do menu (ex.: âncoras da home) ou com Esc.
	header.querySelectorAll( '.c-site-header__menu a' ).forEach( function ( link ) {
		link.addEventListener( 'click', function () {
			setOpen( false );
		} );
	} );

	document.addEventListener( 'keydown', function ( event ) {
		if ( 'Escape' === event.key && header.classList.contains( 'is-menu-open' ) ) {
			setOpen( false );
			toggle.focus();
		}
	} );
} )();
