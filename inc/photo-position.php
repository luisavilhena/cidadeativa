<?php
/**
 * Posição da foto dos membros da equipe.
 *
 * Caixa "Posição da foto" na edição do membro: mostra a imagem destacada no
 * mesmo recorte do popup e deixa arrastar a foto para escolher a parte que
 * aparece. O valor é salvo como "x% y%" e usado em object-position.
 *
 * @package cidade-ativa-3
 */

const CA3_PHOTO_POSITION_META = '_ca3_photo_position';

/**
 * Tipos de conteúdo com a caixa.
 */
function ca3_photo_position_post_types() {
	return array( 'ca-team-member' );
}

/**
 * Posição salva ("x% y%") ou vazio.
 */
function ca3_photo_position( $post_id ) {
	$value = get_post_meta( $post_id, CA3_PHOTO_POSITION_META, true );

	return ca3_sanitize_photo_position( $value );
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
function ca3_photo_position_style( $post_id ) {
	$position = ca3_photo_position( $post_id );

	return $position ? 'object-position: ' . $position . ';' : '';
}

/**
 * Atributos de imagem com a posição salva.
 */
function ca3_photo_position_attr( $post_id, $attr = array() ) {
	$style = ca3_photo_position_style( $post_id );

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
	$position = ca3_photo_position( $post->ID );

	wp_nonce_field( 'ca3_photo_position', 'ca3_photo_position_nonce' );
	?>
	<div class="ca3-photo-position" data-ca3-photo-position>
		<p class="ca3-photo-position__empty" <?php echo $src ? 'hidden' : ''; ?>>
			<?php esc_html_e( 'Defina a imagem destacada para posicionar a foto.', 'cidade-ativa-3' ); ?>
		</p>
		<div class="ca3-photo-position__frame" <?php echo $src ? '' : 'hidden'; ?>>
			<img src="<?php echo esc_url( $src ); ?>" alt="" draggable="false" style="object-position: <?php echo esc_attr( $position ? $position : '50% 0%' ); ?>;">
		</div>
		<p class="description">
			<?php esc_html_e( 'Arraste a foto para escolher a parte que aparece no popup e na etiqueta da home.', 'cidade-ativa-3' ); ?>
		</p>
		<p>
			<button type="button" class="button-link" data-ca3-photo-position-reset><?php esc_html_e( 'Voltar ao padrão (topo)', 'cidade-ativa-3' ); ?></button>
		</p>
		<input type="hidden" name="ca3_photo_position" value="<?php echo esc_attr( $position ); ?>">
	</div>
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

	$value = ca3_sanitize_photo_position( wp_unslash( $_POST['ca3_photo_position'] ?? '' ) );

	if ( $value ) {
		update_post_meta( $post_id, CA3_PHOTO_POSITION_META, $value );
	} else {
		delete_post_meta( $post_id, CA3_PHOTO_POSITION_META );
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
