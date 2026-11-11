<?php
/**
 * Notícias: grid de cards e paginação.
 * É esta parte que o JS troca quando a busca ou a página mudam.
 *
 * @package cidade-ativa-3
 */

$state = $args['state'];
$query = ca3_news_query( $state );
?>
<?php if ( $query->have_posts() ) : ?>
	<ul class="c-noticias-lista__grid">
		<?php
		while ( $query->have_posts() ) :
			$query->the_post();
			?>
			<li class="c-noticias-lista__item">
				<?php get_template_part( 'template-parts/cards/news' ); ?>
			</li>
		<?php endwhile; ?>
	</ul>
	<?php wp_reset_postdata(); ?>

	<?php
	get_template_part(
		'template-parts/pagination',
		null,
		array(
			'current' => $state['pg'],
			'total'   => (int) $query->max_num_pages,
			'url'     => function ( $page ) use ( $state ) {
				return ca3_news_url( $state, array( 'pg' => $page ) );
			},
			'labels'  => array(
				'first' => ca3_news_option( 'page_first' ),
				'prev'  => ca3_news_option( 'page_prev' ),
				'next'  => ca3_news_option( 'page_next' ),
				'last'  => ca3_news_option( 'page_last' ),
			),
		)
	);
	?>
<?php else : ?>
	<p class="c-noticias-lista__empty"><?php echo esc_html( ca3_news_option( 'empty' ) ); ?></p>
<?php endif; ?>
