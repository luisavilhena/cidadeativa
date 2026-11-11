<?php
/**
 * Componente: Hero (abertura). Ocupa 100% da altura da tela.
 *
 * @package cidade-ativa-3
 */

$title = ca3_get( $args, 'title' );
$text  = ca3_get( $args, 'text' );
$image = ca3_get( $args, 'image' );

ca3_section_open( 'hero', $args );
?>
	<div class="c-hero__visual">
		<div class="c-hero__stage">
			<div class="c-hero__triangle" aria-hidden="true"></div>
			<?php echo ca3_image( $image, 'full', 'c-hero__image', array( 'loading' => 'eager' ) ); ?>
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
