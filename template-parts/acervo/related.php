<?php
/**
 * "Acervo relacionado": itens da mesma categoria (ver ca3_acervo_related_query).
 * Com mais de CA3_ACERVO_RELATED_MIN itens vira carrossel; com até
 * CA3_ACERVO_RELATED_MIN (ex.: completados com outras categorias), uma grade simples.
 *
 * @package cidade-ativa-3
 */

$related = ca3_acervo_related_query( $args['post_id'] );

if ( ! $related->have_posts() ) {
	return;
}

$page_id  = ca3_acervo_page_id();
$title    = ca3_acervo_item_text( 'related_title' );
$carousel = $related->post_count > CA3_ACERVO_RELATED_MIN;
?>
<section class="c-section c-acervo-relacionado">
	<?php if ( $title ) : ?>
		<div class="l-container">
			<h2 class="c-section__title"><?php echo esc_html( $title ); ?></h2>
		</div>
	<?php endif; ?>

	<?php if ( ! $carousel ) : ?>
		<div class="l-container">
			<ul class="c-acervo-relacionado__grid">
				<?php
				while ( $related->have_posts() ) :
					$related->the_post();
					?>
					<li><?php get_template_part( 'template-parts/acervo/card', null, array( 'variant' => 'compact' ) ); ?></li>
				<?php endwhile; ?>
			</ul>
		</div>
	<?php else : ?>
	<div class="c-carousel c-acervo-relacionado__carousel" data-carousel>
		<button type="button" class="<?php echo esc_attr( ca3_button_classes( array( 'icon_only' => true, 'class' => 'c-acervo-relacionado__arrow is-prev' ) ) ); ?>" data-carousel-prev aria-label="<?php echo esc_attr( ca3_acervo_item_text( 'related_prev' ) ); ?>">
			<?php echo ca3_icon( 'arrow-prev' ); ?>
		</button>

		<div class="l-container">
			<ul class="c-carousel__track" data-carousel-track>
				<?php
				while ( $related->have_posts() ) :
					$related->the_post();
					?>
					<li class="c-carousel__slide">
						<?php get_template_part( 'template-parts/acervo/card', null, array( 'variant' => 'compact' ) ); ?>
					</li>
				<?php endwhile; ?>
			</ul>
		</div>

		<button type="button" class="<?php echo esc_attr( ca3_button_classes( array( 'icon_only' => true, 'class' => 'c-acervo-relacionado__arrow is-next' ) ) ); ?>" data-carousel-next aria-label="<?php echo esc_attr( ca3_acervo_item_text( 'related_next' ) ); ?>">
			<?php echo ca3_icon( 'arrow-next' ); ?>
		</button>

		<div class="c-carousel__nav c-acervo-relacionado__nav">
			<div class="c-carousel__dots" data-carousel-dots></div>
		</div>
	</div>
	<?php endif; ?>
	<?php wp_reset_postdata(); ?>

	<?php if ( $page_id ) : ?>
		<div class="c-section__actions c-acervo-relacionado__actions">
			<?php echo ca3_button( ca3_acervo_item_text( 'related_button' ), get_permalink( $page_id ), 'solid', 'acervo' ); ?>
		</div>
	<?php endif; ?>
</section>
