<?php
/**
 * Componente: Pilares (colunas com título em destaque).
 *
 * @package cidade-ativa-3
 */

$title = ca3_get( $args, 'title' );
$items = ca3_get( $args, 'items', array() );

ca3_section_open( 'pilares', $args );
?>
	<div class="l-container">
		<?php if ( $title ) : ?>
			<h2 class="c-section__title"><?php echo esc_html( $title ); ?></h2>
		<?php endif; ?>

		<?php if ( $items ) : ?>
			<div class="c-pilares__grid">
				<?php foreach ( $items as $item ) : ?>
					<article class="c-pilares__item">
						<header class="c-pilares__header">
							<h3 class="c-pilares__title"><?php echo esc_html( ca3_get( $item, 'title' ) ); ?></h3>
							<p class="c-pilares__subtitle"><?php echo esc_html( ca3_get( $item, 'subtitle' ) ); ?></p>
						</header>
						<div class="c-pilares__text"><?php echo ca3_rich_text( ca3_get( $item, 'text' ) ); ?></div>
					</article>
				<?php endforeach; ?>
			</div>
		<?php endif; ?>
	</div>
<?php
ca3_section_close();
