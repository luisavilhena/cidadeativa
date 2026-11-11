/**
 * Popups (<dialog class="c-dialog">): abre pelo botão com
 * data-dialog-open="id" e fecha pelo ✕, por Esc ou clicando fora.
 * Um popup pode abrir outro por cima (ex.: colaboradores → pessoa);
 * ao fechar, volta para o anterior.
 */
( function () {
	'use strict';

	if ( ! window.HTMLDialogElement ) {
		return;
	}

	// Pilha de popups abertos: Esc fecha só o de cima.
	var stack = [];

	function open( dialog ) {
		dialog.showModal();
		stack.push( dialog );
	}

	document.querySelectorAll( 'dialog.c-dialog' ).forEach( function ( dialog ) {
		dialog.addEventListener( 'close', function () {
			stack = stack.filter( function ( item ) {
				return item !== dialog;
			} );
		} );

		// O navegador pode agrupar popups e fechar todos com Esc.
		dialog.addEventListener( 'cancel', function ( event ) {
			event.preventDefault();
		} );
	} );

	document.addEventListener( 'keydown', function ( event ) {
		if ( 'Escape' === event.key && stack.length ) {
			event.preventDefault();
			stack[ stack.length - 1 ].close();
		}
	} );

	document.addEventListener( 'click', function ( event ) {
		var opener = event.target.closest( '[data-dialog-open]' );
		if ( opener ) {
			var dialog = document.getElementById( opener.getAttribute( 'data-dialog-open' ) );
			if ( dialog && ! dialog.open ) {
				event.preventDefault();
				open( dialog );
			}
			return;
		}

		var closer = event.target.closest( '[data-dialog-close]' );
		if ( closer ) {
			closer.closest( 'dialog' ).close();
			return;
		}

		// Clique no fundo (fora da caixa) fecha o popup.
		if ( event.target instanceof HTMLDialogElement && event.target.open ) {
			var box = event.target.getBoundingClientRect();
			var inside = event.clientX >= box.left && event.clientX <= box.right && event.clientY >= box.top && event.clientY <= box.bottom;
			if ( ! inside ) {
				event.target.close();
			}
		}
	} );
} )();
