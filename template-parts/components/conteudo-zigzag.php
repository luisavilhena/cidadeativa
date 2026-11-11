<?php
/**
 * Componente: Conteúdo 4. Título e blocos em zigue-zague
 * (o 1º bloco na coluna da esquerda, o 2º na da direita...).
 *
 * @package cidade-ativa-3
 */

$title = ca3_get( $args, 'title' );
$items = ca3_get( $args, 'items', array() );

ca3_section_open( 'conteudo_zigzag', $args, array( 'c-content' ) );
?>
	<div class="l-container">
		<?php if ( $title ) : ?>
			<h2 class="c-content__title"><?php echo esc_html( $title ); ?></h2>
		<?php endif; ?>

		<?php if ( $items ) : ?>
			<div class="c-conteudo-zigzag__list l-grid-2">
				<?php foreach ( $items as $item ) : ?>
					<article class="c-conteudo-zigzag__item">
						<?php echo ca3_image( ca3_get( $item, 'image' ), 'large', 'c-content__image' ); ?>
						<?php if ( ca3_get( $item, 'subtitle' ) ) : ?>
							<h3 class="c-content__subtitle"><?php echo esc_html( $item['subtitle'] ); ?></h3>
						<?php endif; ?>
						<div class="c-content__text"><?php echo ca3_rich_text( ca3_get( $item, 'text' ) ); ?></div>
					</article>
				<?php endforeach; ?>
			</div>
		<?php endif; ?>
	</div>
<?php
ca3_section_close();
