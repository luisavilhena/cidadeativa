/**
 * Listagens (Acervo, Notícias): atualiza busca, filtros, resultados e
 * paginação na própria página, sem recarregar. Sem JS, tudo funciona
 * por links e formulário (GET).
 *
 * Marcação esperada:
 *   [data-listing]                 raiz
 *   [data-listing-form]            formulário (GET) com a busca
 *   [data-listing-search]          campo de busca
 *   [data-listing-sort]            (opcional) select de ordenação
 *   [data-listing-category-input]  (opcional) campo escondido da categoria
 *   [data-listing-results]         parte trocada a cada mudança
 *   [data-listing-link]            links de filtro e paginação
 */
( function () {
	'use strict';

	var root = document.querySelector( '[data-listing]' );
	if ( ! root || ! window.fetch || ! window.DOMParser ) {
		return;
	}

	var form = root.querySelector( '[data-listing-form]' );
	var search = root.querySelector( '[data-listing-search]' );
	var sort = root.querySelector( '[data-listing-sort]' );
	var results = root.querySelector( '[data-listing-results]' );
	var controller = null;
	var timer = null;

	// Monta a URL a partir do formulário + categoria atual (página volta para 1).
	function urlFromForm() {
		var url = new URL( form.action );
		var category = form.querySelector( '[data-listing-category-input]' );

		if ( category && category.value ) {
			url.searchParams.set( 'c', category.value );
		}
		if ( search.value.trim() ) {
			url.searchParams.set( 'q', search.value.trim() );
		}
		if ( sort && sort.value ) {
			url.searchParams.set( 'ord', sort.value );
		}

		return url.toString();
	}

	// Mantém o campo escondido de categoria igual ao filtro ativo.
	function syncCategory( url ) {
		var value = new URL( url ).searchParams.get( 'c' );
		var input = form.querySelector( '[data-listing-category-input]' );

		if ( value && ! input ) {
			input = document.createElement( 'input' );
			input.type = 'hidden';
			input.name = 'c';
			input.setAttribute( 'data-listing-category-input', '' );
			form.prepend( input );
		}
		if ( input ) {
			if ( value ) {
				input.value = value;
			} else {
				input.remove();
			}
		}
	}

	function load( url, push ) {
		if ( controller ) {
			controller.abort();
		}
		controller = new AbortController();
		root.classList.add( 'is-loading' );

		fetch( url, { signal: controller.signal, credentials: 'same-origin' } )
			.then( function ( response ) {
				return response.text();
			} )
			.then( function ( html ) {
				var doc = new DOMParser().parseFromString( html, 'text/html' );
				var fresh = doc.querySelector( '[data-listing-results]' );

				if ( fresh ) {
					results.innerHTML = fresh.innerHTML;
				}

				syncCategory( url );
				history[ push ? 'pushState' : 'replaceState' ]( null, '', url );
				root.classList.remove( 'is-loading' );
			} )
			.catch( function ( error ) {
				if ( 'AbortError' !== error.name ) {
					window.location.href = url;
				}
			} );
	}

	// Busca enquanto digita (com pequena espera).
	search.addEventListener( 'input', function () {
		clearTimeout( timer );
		timer = setTimeout( function () {
			load( urlFromForm(), false );
		}, 400 );
	} );

	if ( sort ) {
		sort.addEventListener( 'change', function () {
			load( urlFromForm(), true );
		} );
	}

	form.addEventListener( 'submit', function ( event ) {
		event.preventDefault();
		clearTimeout( timer );
		load( urlFromForm(), true );
	} );

	// Filtros de categoria e paginação.
	results.addEventListener( 'click', function ( event ) {
		var link = event.target.closest( '[data-listing-link]' );

		if ( ! link || event.metaKey || event.ctrlKey || event.shiftKey ) {
			return;
		}

		event.preventDefault();
		load( link.href, true );

		if ( link.closest( '.c-pagination' ) ) {
			root.scrollIntoView( { behavior: 'smooth' } );
		}
	} );

	// Voltar/avançar do navegador.
	window.addEventListener( 'popstate', function () {
		var params = new URL( window.location.href ).searchParams;
		search.value = params.get( 'q' ) || '';
		if ( sort ) {
			sort.value = params.get( 'ord' ) || '';
		}
		load( window.location.href, false );
	} );
} )();
