<?php
/**
 * Card de um item do acervo: capa, "Categoria. Ano" e título.
 * Variante "compact": capa na largura toda (carrossel de relacionados).
 *
 * @package cidade-ativa-3
 */

$category = ca3_acervo_item_category( get_the_ID(), ca3_acervo_page_id() );
$variant  = isset( $args['variant'] ) ? $args['variant'] : '';
?>
<a class="c-acervo-card<?php echo $variant ? ' c-acervo-card--' . esc_attr( $variant ) : ''; ?>" href="<?php the_permalink(); ?>">
	<span class="c-acervo-card__cover">
		<?php the_post_thumbnail( 'large', array( 'class' => 'c-acervo-card__image', 'alt' => '' ) ); ?>
	</span>
	<span class="c-acervo-card__meta">
		<?php echo esc_html( $category ? $category . '. ' . get_the_date( 'Y' ) : get_the_date( 'Y' ) ); ?>
	</span>
	<span class="c-acervo-card__title"><?php the_title(); ?></span>
</a>
