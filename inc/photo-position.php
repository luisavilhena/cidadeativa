<?php
/**
 * Posição da foto dos membros da equipe.
 *
 * Caixa "Posição da foto" na edição do membro: mostra a imagem destacada em
 * dois recortes e deixa arrastar a foto em cada um para escolher a parte que
 * aparece:
 *   - popup:     recorte do popup da pessoa (344 × 229)
 *   - miniatura: círculo da etiqueta na home (se ficar sem ajuste, usa a
 *                mesma posição do popup)
 * Os valores são salvos como "x% y%" e usados em object-position.
 *
 * @package cidade-ativa-3
 */

const CA3_PHOTO_POSITION_META     = '_ca3_photo_position';
const CA3_PHOTO_POSITION_TAG_META = '_ca3_photo_position_tag';

/**
 * Tipos de conteúdo com a caixa.
 */
function ca3_photo_position_post_types() {
	return array( 'ca-team-member' );
}

/**
 * Recortes editáveis: campo do formulário, meta e rótulo.
 */
function ca3_photo_position_contexts() {
	return array(
		'popup' => array(
			'field' => 'ca3_photo_position',
			'meta'  => CA3_PHOTO_POSITION_META,
			'label' => __( 'Popup', 'cidade-ativa-3' ),
		),
		'tag'   => array(
			'field' => 'ca3_photo_position_tag',
			'meta'  => CA3_PHOTO_POSITION_TAG_META,
			'label' => __( 'Miniatura na home', 'cidade-ativa-3' ),
		),
	);
}

/**
 * Posição salva ("x% y%") ou vazio.
 * A miniatura (tag) usa a do popup quando não tem ajuste próprio.
 */
function ca3_photo_position( $post_id, $context = 'popup' ) {
	$contexts = ca3_photo_position_contexts();
	$context  = isset( $contexts[ $context ] ) ? $context : 'popup';
	$value    = ca3_sanitize_photo_position( get_post_meta( $post_id, $contexts[ $context ]['meta'], true ) );

	if ( ! $value && 'tag' === $context ) {
		return ca3_photo_position( $post_id, 'popup' );
	}

	return $value;
}

/**
 * Aceita só "x% y%" com números de 0 a 100.
 */
function ca3_sanitize_photo_position( $value ) {
	if ( ! is_string( $value ) || ! preg_match( '/^(\d{1,3}(?:\.\d+)?)% (\d{1,3}(?:\.\d+)?)%$/', trim( $value ), $m ) ) {
		return '';
	}

	$x = min( 100, (float) $m[1] );
	$y = min( 100, (float) $m[2] );

	return round( $x, 1 ) . '% ' . round( $y, 1 ) . '%';
}

/**
 * Atributo style para a imagem destacada da pessoa (vazio se não houver posição).
 */
function ca3_photo_position_style( $post_id, $context = 'popup' ) {
	$position = ca3_photo_position( $post_id, $context );

	return $position ? 'object-position: ' . $position . ';' : '';
}

/**
 * Atributos de imagem com a posição salva.
 */
function ca3_photo_position_attr( $post_id, $attr = array(), $context = 'popup' ) {
	$style = ca3_photo_position_style( $post_id, $context );

	if ( $style ) {
		$attr['style'] = $style;
	}

	return $attr;
}

function ca3_photo_position_add_meta_box() {
	add_meta_box(
		'ca3-photo-position',
		__( 'Posição da foto', 'cidade-ativa-3' ),
		'ca3_photo_position_render_meta_box',
		ca3_photo_position_post_types(),
		'side',
		'low'
	);
}
add_action( 'add_meta_boxes', 'ca3_photo_position_add_meta_box' );

function ca3_photo_position_render_meta_box( $post ) {
	$thumb_id = get_post_thumbnail_id( $post );
	$src      = $thumb_id ? wp_get_attachment_image_url( $thumb_id, 'medium_large' ) : '';
	$popup    = ca3_sanitize_photo_position( get_post_meta( $post->ID, CA3_PHOTO_POSITION_META, true ) );

	wp_nonce_field( 'ca3_photo_position', 'ca3_photo_position_nonce' );
	?>
	<p class="ca3-photo-position__empty" data-ca3-photo-empty <?php echo $src ? 'hidden' : ''; ?>>
		<?php esc_html_e( 'Defina a imagem destacada para posicionar a foto.', 'cidade-ativa-3' ); ?>
	</p>

	<?php foreach ( ca3_photo_position_contexts() as $context => $info ) : ?>
		<?php
		$saved = ca3_sanitize_photo_position( get_post_meta( $post->ID, $info['meta'], true ) );
		// Posição mostrada: a salva; na miniatura sem ajuste, a do popup.
		$shown = $saved ? $saved : ( 'tag' === $context && $popup ? $popup : '50% 0%' );
		?>
		<div class="ca3-photo-position is-<?php echo esc_attr( $context ); ?>" data-ca3-photo-position <?php echo $src ? '' : 'hidden'; ?>>
			<p class="ca3-photo-position__label"><strong><?php echo esc_html( $info['label'] ); ?></strong></p>
			<div class="ca3-photo-position__frame">
				<img src="<?php echo esc_url( $src ); ?>" alt="" draggable="false" style="object-position: <?php echo esc_attr( $shown ); ?>;">
			</div>
			<p>
				<button type="button" class="button-link" data-ca3-photo-position-reset>
					<?php echo esc_html( 'tag' === $context ? __( 'Usar a mesma posição do popup', 'cidade-ativa-3' ) : __( 'Voltar ao padrão (topo)', 'cidade-ativa-3' ) ); ?>
				</button>
			</p>
			<input type="hidden" name="<?php echo esc_attr( $info['field'] ); ?>" value="<?php echo esc_attr( $saved ); ?>">
		</div>
	<?php endforeach; ?>

	<p class="description">
		<?php esc_html_e( 'Arraste a foto em cada recorte para escolher a parte que aparece. A miniatura só vale quando a etiqueta da home usa a imagem destacada (campo "Foto" da etiqueta vazio).', 'cidade-ativa-3' ); ?>
	</p>
	<?php
}

function ca3_photo_position_save( $post_id ) {
	if ( ! isset( $_POST['ca3_photo_position_nonce'] ) || ! wp_verify_nonce( sanitize_key( $_POST['ca3_photo_position_nonce'] ), 'ca3_photo_position' ) ) {
		return;
	}
	if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
		return;
	}
	if ( ! current_user_can( 'edit_post', $post_id ) ) {
		return;
	}

	foreach ( ca3_photo_position_contexts() as $info ) {
		$value = ca3_sanitize_photo_position( wp_unslash( $_POST[ $info['field'] ] ?? '' ) );

		if ( $value ) {
			update_post_meta( $post_id, $info['meta'], $value );
		} else {
			delete_post_meta( $post_id, $info['meta'] );
		}
	}
}
add_action( 'save_post', 'ca3_photo_position_save' );

function ca3_photo_position_admin_assets( $hook ) {
	if ( ! in_array( $hook, array( 'post.php', 'post-new.php' ), true ) ) {
		return;
	}
	if ( ! in_array( get_current_screen()->post_type, ca3_photo_position_post_types(), true ) ) {
		return;
	}

	ca3_enqueue_style( 'ca3-admin-photo-position', 'assets/css/admin/photo-position.css' );
	wp_enqueue_script( 'ca3-admin-photo-position', CA3_URI . '/assets/js/admin/photo-position.js', array(), ca3_asset_version( 'assets/js/admin/photo-position.js' ), true );
}
add_action( 'admin_enqueue_scripts', 'ca3_photo_position_admin_assets' );
