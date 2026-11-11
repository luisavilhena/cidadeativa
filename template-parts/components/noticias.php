<?php
/**
 * Componente: Notícias. Mostra os 3 últimos posts do blog.
 *
 * @package cidade-ativa-3
 */

$title   = ca3_get( $args, 'title' );
$text    = ca3_get( $args, 'text' );
$buttons = ca3_get( $args, 'buttons', array() );

$news_query = new WP_Query(
	array(
		'post_type'           => 'post',
		'posts_per_page'      => 3,
		'ignore_sticky_posts' => true,
		'no_found_rows'       => true,
	)
);

ca3_section_open( 'noticias', $args );
?>
	<div class="l-container">
		<?php if ( $title ) : ?>
			<h2 class="c-section__title"><?php echo esc_html( $title ); ?></h2>
		<?php endif; ?>

		<?php if ( $text ) : ?>
			<div class="c-section__intro c-noticias__intro"><?php echo ca3_rich_text( $text ); ?></div>
		<?php endif; ?>

		<?php if ( $buttons ) : ?>
			<div class="c-section__actions c-noticias__actions">
				<?php foreach ( $buttons as $button ) : ?>
					<?php echo ca3_button( ca3_get( $button, 'label' ), ca3_get( $button, 'url' ), ca3_get( $button, 'style', 'solid' ), ca3_get( $button, 'icon' ), array( 'color' => ca3_get( $button, 'color', 'vermelho' ) ) ); ?>
				<?php endforeach; ?>
			</div>
		<?php endif; ?>

		<?php if ( $news_query->have_posts() ) : ?>
			<ul class="c-noticias__list">
				<?php
				while ( $news_query->have_posts() ) :
					$news_query->the_post();
					?>
					<li class="c-noticias__item">
						<?php get_template_part( 'template-parts/cards/news' ); ?>
					</li>
				<?php endwhile; ?>
			</ul>
			<?php wp_reset_postdata(); ?>
		<?php endif; ?>
	</div>
<?php
ca3_section_close();
