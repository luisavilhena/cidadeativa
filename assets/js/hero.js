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

		// Só mede de novo quando a largura muda: no celular a altura muda ao
		// rolar (barra do navegador aparece/some) e remedir faria a ilustração pular.
		var lastWidth = window.innerWidth;
		window.addEventListener( 'resize', function () {
			if ( window.innerWidth !== lastWidth ) {
				lastWidth = window.innerWidth;
				update();
			}
		} );
		window.addEventListener( 'load', update );

		if ( document.fonts && document.fonts.ready ) {
			document.fonts.ready.then( update );
		}
	}

	/**
	 * Animação "Parallax" (has-anim-parallax): ao rolar, o triângulo e a
	 * ilustração descem uma fração da rolagem, então andam mais devagar que
	 * a página. A ilustração desce mais, ou seja, é a mais lenta.
	 *
	 * Reserva: só roda em navegadores sem animação ligada à rolagem
	 * (animation-timeline), que é feita no CSS (hero.css) e não treme.
	 */
	var PARALLAX = [
		{ selector: '.c-hero__triangle', speed: 0.25 }, // anda a 75% da rolagem
		{ selector: '.c-hero__image', speed: 0.45 }, //    anda a 55% da rolagem
	];

	function initParallax( hero ) {
		if ( window.matchMedia( '(prefers-reduced-motion: reduce)' ).matches ) {
			return;
		}

		if ( window.CSS && CSS.supports( 'animation-timeline: scroll()' ) ) {
			return;
		}

		var layers = PARALLAX.map( function ( layer ) {
			return { element: hero.querySelector( layer.selector ), speed: layer.speed };
		} ).filter( function ( layer ) {
			return layer.element;
		} );

		function update() {
			// Só enquanto o hero (mais uma tela) está à vista.
			var rect = hero.getBoundingClientRect();
			if ( rect.bottom < -window.innerHeight ) {
				return;
			}

			// Mesma conta do CSS: fração da rolagem da página, em px inteiros.
			var scrolled = Math.min( window.scrollY, 2000 );
			layers.forEach( function ( layer ) {
				layer.element.style.transform = 'translate3d(0, ' + Math.round( scrolled * layer.speed ) + 'px, 0)';
			} );
		}

		// Atualiza no próprio evento de rolagem (sem esperar o próximo quadro),
		// para o transform acompanhar a rolagem o mais rápido possível.
		window.addEventListener( 'scroll', update, { passive: true } );

		update();
	}

	/**
	 * Animação "Montagem" (has-anim-montagem): as figuras só começam a entrar
	 * depois que as imagens carregaram (ou após 3s), para o fade não ser
	 * perdido numa conexão lenta. Ver hero.css.
	 */
	function initMontagem( hero ) {
		var images = Array.prototype.slice.call( hero.querySelectorAll( '.c-hero__figure' ) );
		var done = false;

		function ready() {
			if ( ! done ) {
				done = true;
				hero.classList.add( 'is-ready' );
			}
		}

		Promise.all(
			images.map( function ( image ) {
				return image.decode ? image.decode().catch( function () {} ) : Promise.resolve();
			} )
		).then( ready );

		setTimeout( ready, 3000 );
	}

	document.querySelectorAll( '.c-hero' ).forEach( initHero );
	document.querySelectorAll( '.c-hero.has-anim-parallax' ).forEach( initParallax );
	document.querySelectorAll( '.c-hero.has-anim-montagem' ).forEach( initMontagem );
} )();
