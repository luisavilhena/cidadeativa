/**
 * Carrossel (c-carousel): "Nossos programas", galerias e relacionados.
 * Setas rolam um card por vez; os pontos são criados a partir dos cards.
 *
 * Atributos opcionais no elemento [data-carousel]:
 *   data-carousel-start="middle"  card inicial (o do meio) ou um número
 *   data-carousel-loop            infinito: os cards são duplicados antes e
 *                                 depois e, ao entrar numa cópia, o carrossel
 *                                 volta (sem animação) ao card equivalente
 *
 * Quando todos os cards cabem, o carrossel ganha a classe "is-static"
 * (setas e pontos somem) e não fica infinito.
 */
( function () {
	'use strict';

	function initCarousel( carousel ) {
		var track = carousel.querySelector( '[data-carousel-track]' );
		var prev = carousel.querySelector( '[data-carousel-prev]' );
		var next = carousel.querySelector( '[data-carousel-next]' );
		var dotsWrap = carousel.querySelector( '[data-carousel-dots]' );

		if ( ! track ) {
			return;
		}

		var slides = Array.prototype.slice.call( track.children );
		var total = slides.length;
		var dots = [];
		var loop = false;
		var settleTimer = null;

		function step() {
			if ( ! track.children.length ) {
				return 0;
			}
			var gap = parseFloat( getComputedStyle( track ).columnGap ) || 0;
			return track.children[ 0 ].getBoundingClientRect().width + gap;
		}

		// Posição do card atual na trilha (contando as cópias, no modo infinito).
		function position() {
			var width = step();
			return width ? Math.round( track.scrollLeft / width ) : 0;
		}

		// Índice do card real (0 a total - 1).
		function realIndex() {
			var index = position();
			return loop ? ( ( index % total ) + total ) % total : index;
		}

		function scrollToPosition( index, smooth ) {
			track.scrollTo( { left: index * step(), behavior: smooth ? 'smooth' : 'instant' } );
		}

		function goTo( index ) {
			scrollToPosition( loop ? total + index : index, true );
		}

		// Infinito: se parou numa cópia, pula para o card real equivalente.
		function settle() {
			if ( ! loop ) {
				return;
			}
			var index = position();
			if ( index < total || index >= total * 2 ) {
				scrollToPosition( total + ( ( index % total ) + total ) % total, false );
			}
		}

		function update() {
			var index = realIndex();
			var maxScroll = track.scrollWidth - track.clientWidth;

			dots.forEach( function ( dot, i ) {
				dot.classList.toggle( 'is-active', i === index );
			} );

			carousel.classList.toggle( 'is-static', maxScroll <= 1 );

			if ( prev ) {
				prev.disabled = ! loop && track.scrollLeft <= 1;
			}
			if ( next ) {
				next.disabled = ! loop && track.scrollLeft >= maxScroll - 1;
			}

			clearTimeout( settleTimer );
			settleTimer = setTimeout( settle, 150 );
		}

		// Infinito: duplica os cards antes e depois (as cópias ficam ocultas
		// para leitores de tela e fora da navegação por teclado).
		if ( carousel.hasAttribute( 'data-carousel-loop' ) && total > 1 && track.scrollWidth > track.clientWidth + 1 ) {
			loop = true;

			var makeClone = function ( slide ) {
				var clone = slide.cloneNode( true );
				clone.classList.add( 'is-clone' );
				clone.setAttribute( 'aria-hidden', 'true' );
				clone.querySelectorAll( 'a, button, input, select, textarea, [tabindex]' ).forEach( function ( element ) {
					element.setAttribute( 'tabindex', '-1' );
				} );
				return clone;
			};

			slides.forEach( function ( slide ) {
				track.appendChild( makeClone( slide ) );
			} );
			slides.slice().reverse().forEach( function ( slide ) {
				track.insertBefore( makeClone( slide ), track.firstChild );
			} );
		}

		slides.forEach( function ( slide, i ) {
			if ( ! dotsWrap ) {
				return;
			}
			var dot = document.createElement( 'button' );
			dot.type = 'button';
			dot.className = 'c-carousel__dot';
			dot.setAttribute( 'aria-label', String( i + 1 ) );
			dot.addEventListener( 'click', function () {
				goTo( i );
			} );
			dotsWrap.appendChild( dot );
			dots.push( dot );
		} );

		if ( prev ) {
			prev.addEventListener( 'click', function () {
				scrollToPosition( position() - 1, true );
			} );
		}
		if ( next ) {
			next.addEventListener( 'click', function () {
				scrollToPosition( position() + 1, true );
			} );
		}

		// Card inicial.
		var start = carousel.getAttribute( 'data-carousel-start' );
		var startIndex = 0;
		if ( start && total ) {
			startIndex = 'middle' === start ? Math.floor( ( total - 1 ) / 2 ) : parseInt( start, 10 ) || 0;
		}
		if ( loop || startIndex ) {
			scrollToPosition( ( loop ? total : 0 ) + startIndex, false );
		}

		track.addEventListener( 'scroll', update, { passive: true } );
		window.addEventListener( 'resize', function () {
			scrollToPosition( ( loop ? total : 0 ) + realIndex(), false );
			update();
		} );
		update();
	}

	document.querySelectorAll( '[data-carousel]' ).forEach( initCarousel );
} )();
