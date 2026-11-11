<?php
/**
 * Card de notícia (c-card): imagem, data e título.
 * Usado no componente Notícias da home e na listagem de Notícias.
 *
 * @package cidade-ativa-3
 */
?>
<a class="c-card" href="<?php the_permalink(); ?>">
	<span class="c-card__media">
		<?php the_post_thumbnail( 'ca3-card', array( 'class' => 'c-card__image', 'alt' => '' ) ); ?>
	</span>
	<span class="c-card__body">
		<time class="c-card__date" datetime="<?php echo esc_attr( get_the_date( 'c' ) ); ?>"><?php echo esc_html( ucfirst( get_the_date( 'F j. Y' ) ) ); ?></time>
		<span class="c-card__title"><?php the_title(); ?></span>
	</span>
</a>
