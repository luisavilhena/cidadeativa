<?php
/**
 * Faixa "Leia também nossas publicações nos periódicos" com botões.
 *
 * @package cidade-ativa-3
 */

$text  = ca3_news_option( 'band_text' );
$links = array_filter(
	(array) ca3_news_option( 'band_links' ),
	function ( $link ) {
		return ! empty( $link['texto'] ) && ! empty( $link['link'] );
	}
);

if ( ! $text && ! $links ) {
	return;
}
?>
<section class="c-section c-periodicos">
	<div class="l-container">
		<?php if ( $text ) : ?>
			<p class="c-periodicos__text"><?php echo esc_html( $text ); ?></p>
		<?php endif; ?>

		<?php if ( $links ) : ?>
			<div class="c-periodicos__actions">
				<?php foreach ( $links as $link ) : ?>
					<?php echo ca3_button( $link['texto'], $link['link'], 'solid', '', array( 'class' => 'c-periodicos__button', 'new_tab' => true ) ); ?>
				<?php endforeach; ?>
			</div>
		<?php endif; ?>
	</div>
</section>
