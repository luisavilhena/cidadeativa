<?php
/**
 * Componente: Hero (abertura). Ocupa 100% da altura da tela.
 *
 * @package cidade-ativa-3
 */

$title = ca3_get( $args, 'title' );
$text  = ca3_get( $args, 'text' );
$image = ca3_get( $args, 'image' );
$anim  = ca3_get( $args, 'animation' );
$anim  = in_array( $anim, array( 'parallax', 'montagem' ), true ) ? $anim : '';

ca3_section_open( 'hero', $args, $anim ? array( 'has-anim-' . $anim ) : array() );
?>
	<div class="c-hero__visual">
		<div class="c-hero__stage">
			<div class="c-hero__triangle" aria-hidden="true"></div>
			<?php if ( 'montagem' === $anim ) : ?>
				<?php // Ilustração em figuras separadas (inc/hero-montagem.php), que entram uma a uma. ?>
				<div class="c-hero__figures" aria-hidden="true">
					<?php foreach ( require CA3_DIR . '/inc/hero-montagem.php' as $i => $figure ) : ?>
						<img class="c-hero__figure" src="<?php echo esc_url( CA3_URI . '/assets/images/hero-montagem/' . $figure['file'] . '.png' ); ?>" alt="" style="<?php echo esc_attr( sprintf( 'left:%s%%;top:%s%%;width:%s%%;height:%s%%;--i:%d', $figure['left'], $figure['top'], $figure['width'], $figure['height'], $i ) ); ?>">
					<?php endforeach; ?>
				</div>
			<?php else : ?>
				<?php echo ca3_image( $image, 'full', 'c-hero__image', array( 'loading' => 'eager' ) ); ?>
			<?php endif; ?>
		</div>
	</div>

	<div class="l-container c-hero__inner">
		<div class="c-hero__content">
			<?php if ( $title ) : ?>
				<h1 class="c-hero__title"><?php echo ca3_title_with_accent( $title ); ?></h1>
			<?php endif; ?>

			<?php if ( $text ) : ?>
				<div class="c-hero__text"><?php echo ca3_rich_text( $text ); ?></div>
			<?php endif; ?>
		</div>
	</div>
<?php
ca3_section_close();
