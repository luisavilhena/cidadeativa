<?php
/**
 * "Notícias relacionadas": carrossel de cards + botões.
 *
 * @package cidade-ativa-3
 */

$ids     = ca3_news_related_posts( $args['post_id'] );
$title   = ca3_news_single_text( 'related_title' );
$buttons = array_filter(
	(array) ca3_news_single_text( 'buttons' ),
	function ( $button ) {
		return ! empty( $button['texto'] ) && ! empty( $button['link'] );
	}
);

if ( ! $ids ) {
	return;
}

$related = new WP_Query(
	array(
		'post_type'           => 'post',
		'post__in'            => $ids,
		'orderby'             => 'post__in',
		'posts_per_page'      => count( $ids ),
		'ignore_sticky_posts' => true,
		'no_found_rows'       => true,
	)
);
?>
<section class="c-section c-noticias-relacionadas">
	<div class="l-container">
		<hr class="c-noticias-relacionadas__divider">
		<?php if ( $title ) : ?>
			<h2 class="c-section__title"><?php echo esc_html( $title ); ?></h2>
		<?php endif; ?>
	</div>

	<div class="c-carousel c-noticias-relacionadas__carousel" data-carousel>
		<button type="button" class="<?php echo esc_attr( ca3_button_classes( array( 'icon_only' => true, 'class' => 'c-noticias-relacionadas__arrow is-prev' ) ) ); ?>" data-carousel-prev aria-label="<?php echo esc_attr( ca3_news_single_text( 'related_prev' ) ); ?>">
			<?php echo ca3_icon( 'arrow-prev' ); ?>
		</button>

		<div class="l-container">
			<ul class="c-carousel__track" data-carousel-track>
				<?php
				while ( $related->have_posts() ) :
					$related->the_post();
					?>
					<li class="c-carousel__slide">
						<?php get_template_part( 'template-parts/cards/news' ); ?>
					</li>
				<?php endwhile; ?>
			</ul>
		</div>

		<button type="button" class="<?php echo esc_attr( ca3_button_classes( array( 'icon_only' => true, 'class' => 'c-noticias-relacionadas__arrow is-next' ) ) ); ?>" data-carousel-next aria-label="<?php echo esc_attr( ca3_news_single_text( 'related_next' ) ); ?>">
			<?php echo ca3_icon( 'arrow-next' ); ?>
		</button>

		<div class="c-carousel__nav c-noticias-relacionadas__nav">
			<div class="c-carousel__dots" data-carousel-dots></div>
		</div>
	</div>
	<?php wp_reset_postdata(); ?>

	<?php if ( $buttons ) : ?>
		<div class="c-section__actions c-noticias-relacionadas__actions">
			<?php foreach ( $buttons as $button ) : ?>
				<?php echo ca3_button( $button['texto'], $button['link'], isset( $button['estilo'] ) ? $button['estilo'] : 'solid', isset( $button['icone'] ) ? $button['icone'] : '', array( 'color' => isset( $button['cor'] ) ? $button['cor'] : 'vermelho', 'class' => 'c-noticias-relacionadas__button' ) ); ?>
			<?php endforeach; ?>
		</div>
	<?php endif; ?>
</section>
