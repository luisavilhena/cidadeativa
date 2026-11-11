<?php
/**
 * Acervo: filtros de categoria, grid de itens e paginação.
 * É esta parte que o JS troca quando os filtros mudam.
 *
 * @package cidade-ativa-3
 */

$state      = $args['state'];
$categories = $args['categories'];
$query      = ca3_acervo_query( $state );
?>
<?php if ( $categories ) : ?>
	<nav class="c-acervo-lista__filters">
		<a class="<?php echo esc_attr( ca3_button_classes( array( 'style' => 'outline', 'class' => 'c-acervo-lista__filter' . ( $state['c'] ? '' : ' is-active' ) ) ) ); ?>" href="<?php echo esc_url( ca3_acervo_url( $state, array( 'c' => 0, 'pg' => 1 ) ) ); ?>" data-listing-link<?php echo $state['c'] ? '' : ' aria-current="true"'; ?>>
			<?php echo esc_html( ca3_acervo_field( 'all_label' ) ); ?>
		</a>
		<?php foreach ( $categories as $term ) : ?>
			<?php $active = (int) $term->term_id === $state['c']; ?>
			<a class="<?php echo esc_attr( ca3_button_classes( array( 'style' => 'outline', 'class' => 'c-acervo-lista__filter' . ( $active ? ' is-active' : '' ) ) ) ); ?>" href="<?php echo esc_url( ca3_acervo_url( $state, array( 'c' => $term->term_id, 'pg' => 1 ) ) ); ?>" data-listing-link<?php echo $active ? ' aria-current="true"' : ''; ?>>
				<?php echo esc_html( $term->name ); ?>
			</a>
		<?php endforeach; ?>
	</nav>
<?php endif; ?>

<?php if ( $query->have_posts() ) : ?>
	<ul class="c-acervo-lista__grid">
		<?php
		while ( $query->have_posts() ) :
			$query->the_post();
			?>
			<li class="c-acervo-lista__item">
				<?php get_template_part( 'template-parts/acervo/card' ); ?>
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
				return ca3_acervo_url( $state, array( 'pg' => $page ) );
			},
			'labels'  => array(
				'first' => ca3_acervo_field( 'page_first' ),
				'prev'  => ca3_acervo_field( 'page_prev' ),
				'next'  => ca3_acervo_field( 'page_next' ),
				'last'  => ca3_acervo_field( 'page_last' ),
			),
		)
	);
	?>
<?php else : ?>
	<p class="c-acervo-lista__empty"><?php echo esc_html( ca3_acervo_field( 'empty' ) ); ?></p>
<?php endif; ?>
