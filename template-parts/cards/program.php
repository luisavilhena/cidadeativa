<?php
/**
 * Card de programa (c-program-card): foto, nome (última palavra em negrito),
 * cata-vento e frase. Borda e cata-vento na cor do programa.
 *
 * @package cidade-ativa-3
 */

$post_id = get_the_ID();
?>
<a class="c-program-card has-program-<?php echo esc_attr( ca3_program_color( $post_id ) ); ?>" href="<?php the_permalink(); ?>">
	<span class="c-program-card__media">
		<?php the_post_thumbnail( 'medium_large', array( 'class' => 'c-program-card__image', 'alt' => '' ) ); ?>
	</span>
	<span class="c-program-card__body">
		<span class="c-program-card__head">
			<span class="c-program-card__title"><?php echo ca3_program_title_html( get_the_title(), $post_id ); ?></span>
			<?php echo ca3_icon( 'pinwheel', 'c-program-card__icon' ); ?>
		</span>
		<span class="c-program-card__text"><?php echo esc_html( ca3_program_subtitle( $post_id ) ); ?></span>
	</span>
</a>
