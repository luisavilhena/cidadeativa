<?php
/**
 * Carrossel "Nossos programas": cards dos programas marcados para aparecer.
 * Usado no componente da home e na página de cada programa.
 *
 * Argumentos: label_prev, label_next (textos das setas para leitores de tela).
 *
 * @package cidade-ativa-3
 */

$programs = ca3_programs_for_carousel();
global $post;
?>
<div class="c-carousel c-programas__carousel" data-carousel data-carousel-start="middle" data-carousel-loop>
	<ul class="c-carousel__track c-programas__track" data-carousel-track>
		<?php foreach ( $programs as $post ) : ?>
			<?php setup_postdata( $post ); ?>
			<li class="c-carousel__slide">
				<?php get_template_part( 'template-parts/cards/program' ); ?>
			</li>
		<?php endforeach; ?>
		<?php wp_reset_postdata(); ?>
	</ul>

	<div class="c-carousel__nav c-programas__nav">
		<button type="button" class="<?php echo esc_attr( ca3_button_classes( array( 'icon_only' => true ) ) ); ?>" data-carousel-prev aria-label="<?php echo esc_attr( $args['label_prev'] ); ?>">
			<?php echo ca3_icon( 'arrow-prev' ); ?>
		</button>
		<div class="c-carousel__dots" data-carousel-dots></div>
		<button type="button" class="<?php echo esc_attr( ca3_button_classes( array( 'icon_only' => true ) ) ); ?>" data-carousel-next aria-label="<?php echo esc_attr( $args['label_next'] ); ?>">
			<?php echo ca3_icon( 'arrow-next' ); ?>
		</button>
	</div>
</div>
