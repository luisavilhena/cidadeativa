<?php
/**
 * "Acervo relacionado": grade com até 4 itens (ver ca3_acervo_related_query):
 * 4 colunas no computador, 2 × 2 no tablet e só 2 itens no celular.
 *
 * @package cidade-ativa-3
 */

$related = ca3_acervo_related_query( $args['post_id'] );

if ( ! $related->have_posts() ) {
	return;
}

$page_id = ca3_acervo_page_id();
$title   = ca3_acervo_item_text( 'related_title' );
?>
<section class="c-section c-acervo-relacionado">
	<div class="l-container">
		<?php if ( $title ) : ?>
			<h2 class="c-section__title"><?php echo esc_html( $title ); ?></h2>
		<?php endif; ?>

		<ul class="c-acervo-relacionado__grid">
			<?php
			while ( $related->have_posts() ) :
				$related->the_post();
				?>
				<li><?php get_template_part( 'template-parts/acervo/card', null, array( 'variant' => 'compact' ) ); ?></li>
			<?php endwhile; ?>
		</ul>
	</div>
	<?php wp_reset_postdata(); ?>

	<?php if ( $page_id ) : ?>
		<div class="c-section__actions c-acervo-relacionado__actions">
			<?php echo ca3_button( ca3_acervo_item_text( 'related_button' ), get_permalink( $page_id ), 'solid', 'acervo' ); ?>
		</div>
	<?php endif; ?>
</section>
