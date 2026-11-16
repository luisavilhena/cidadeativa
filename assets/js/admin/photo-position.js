/**
 * Caixa "Posição da foto" (edição do membro da equipe): em cada recorte
 * (popup e miniatura da home), arrastar a foto muda o object-position,
 * salvo no campo oculto do recorte. Acompanha a troca da imagem destacada.
 */
( function () {
	'use strict';

	var boxes = Array.prototype.slice.call( document.querySelectorAll( '[data-ca3-photo-position]' ) );
	var empty = document.querySelector( '[data-ca3-photo-empty]' );

	if ( ! boxes.length ) {
		return;
	}

	var DEFAULT = { x: 50, y: 0 }; // Igual ao CSS do site: object-position: top.

	function parse( value ) {
		var m = /^([\d.]+)% ([\d.]+)%$/.exec( value || '' );
		return m ? { x: parseFloat( m[ 1 ] ), y: parseFloat( m[ 2 ] ) } : null;
	}

	function clamp( n ) {
		return Math.min( 100, Math.max( 0, n ) );
	}

	var editors = boxes.map( function ( box ) {
		var frame = box.querySelector( '.ca3-photo-position__frame' );
		var img = frame.querySelector( 'img' );
		var input = box.querySelector( 'input[type="hidden"]' );
		var reset = box.querySelector( '[data-ca3-photo-position-reset]' );
		var isTag = box.classList.contains( 'is-tag' );
		var pos = parse( img.style.objectPosition ) || { x: DEFAULT.x, y: DEFAULT.y };
		var drag = null;

		function apply( save ) {
			var value = pos.x.toFixed( 1 ) + '% ' + pos.y.toFixed( 1 ) + '%';
			img.style.objectPosition = value;
			if ( save ) {
				input.value = value;
			}
		}

		// Quanto a imagem passa do recorte (em px), com object-fit: cover.
		function overflow() {
			var fw = frame.clientWidth;
			var fh = frame.clientHeight;
			var iw = img.naturalWidth;
			var ih = img.naturalHeight;

			if ( ! iw || ! ih ) {
				return { x: 0, y: 0 };
			}

			var scale = Math.max( fw / iw, fh / ih );
			return { x: iw * scale - fw, y: ih * scale - fh };
		}

		frame.addEventListener( 'pointerdown', function ( event ) {
			event.preventDefault();
			frame.setPointerCapture( event.pointerId );
			drag = { x: event.clientX, y: event.clientY, start: { x: pos.x, y: pos.y }, over: overflow() };
			frame.classList.add( 'is-dragging' );
		} );

		frame.addEventListener( 'pointermove', function ( event ) {
			if ( ! drag ) {
				return;
			}
			// Arrastar para a direita mostra mais da esquerda da foto (x diminui).
			if ( drag.over.x > 0 ) {
				pos.x = clamp( drag.start.x - ( ( event.clientX - drag.x ) / drag.over.x ) * 100 );
			}
			if ( drag.over.y > 0 ) {
				pos.y = clamp( drag.start.y - ( ( event.clientY - drag.y ) / drag.over.y ) * 100 );
			}
			apply( false );
		} );

		function stop() {
			if ( drag ) {
				drag = null;
				frame.classList.remove( 'is-dragging' );
				apply( true );
				editors.forEach( function ( editor ) {
					editor.onOtherChange();
				} );
			}
		}

		frame.addEventListener( 'pointerup', stop );
		frame.addEventListener( 'pointercancel', stop );

		var editor = {
			isTag: isTag,
			input: input,
			img: img,
			box: box,
			getPosition: function () {
				return pos;
			},
			// A miniatura sem ajuste próprio acompanha o popup.
			onOtherChange: function () {
				if ( isTag && ! input.value ) {
					var popup = editors.filter( function ( item ) {
						return ! item.isTag;
					} )[ 0 ];
					pos = popup ? { x: popup.getPosition().x, y: popup.getPosition().y } : { x: DEFAULT.x, y: DEFAULT.y };
					apply( false );
				}
			},
		};

		reset.addEventListener( 'click', function () {
			input.value = '';
			if ( isTag ) {
				editor.onOtherChange();
			} else {
				pos = { x: DEFAULT.x, y: DEFAULT.y };
				apply( false );
				editors.forEach( function ( item ) {
					item.onOtherChange();
				} );
			}
		} );

		apply( false );
		return editor;
	} );

	// Troca da imagem destacada (Editor Clássico).
	function setImage( src ) {
		if ( empty ) {
			empty.hidden = !! src;
		}
		editors.forEach( function ( editor ) {
			editor.box.hidden = ! src;
			if ( src && editor.img.getAttribute( 'src' ) !== src ) {
				editor.img.src = src;
			}
		} );
	}

	function syncThumbnail() {
		var field = document.getElementById( '_thumbnail_id' );
		var id = field ? parseInt( field.value, 10 ) : 0;

		if ( ! id || id < 0 ) {
			setImage( '' );
			return;
		}

		if ( window.wp && wp.media && wp.media.attachment ) {
			var attachment = wp.media.attachment( id );
			attachment.fetch().then( function () {
				var sizes = attachment.get( 'sizes' ) || {};
				var size = sizes.medium_large || sizes.large || sizes.full;
				setImage( size ? size.url : attachment.get( 'url' ) );
			} );
			return;
		}

		var preview = document.querySelector( '#postimagediv img' );
		setImage( preview ? preview.src : '' );
	}

	var thumbBox = document.getElementById( 'postimagediv' );
	if ( thumbBox && window.MutationObserver ) {
		new MutationObserver( syncThumbnail ).observe( thumbBox, { childList: true, subtree: true } );
	}
} )();
