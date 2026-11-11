<?php
/**
 * Paginação (c-pagination): primeira, anterior, números (com "..."), próxima e última.
 * Usada nas listagens do Acervo e de Notícias.
 *
 * Argumentos:
 *   current  página atual
 *   total    total de páginas
 *   url      função que recebe o número da página e devolve a URL
 *   labels   textos para leitores de tela: first, prev, next, last
 *
 * @package cidade-ativa-3
 */

$total   = (int) $args['total'];
$current = min( max( 1, (int) $args['current'] ), max( $total, 1 ) );
$url     = $args['url'];
$labels  = wp_parse_args( isset( $args['labels'] ) ? $args['labels'] : array(), array( 'first' => '', 'prev' => '', 'next' => '', 'last' => '' ) );

if ( $total < 2 ) {
	return;
}

// Páginas visíveis: a primeira, a última e as vizinhas da atual.
$pages = array_unique( array_merge( array( 1, $total ), range( max( 1, $current - 1 ), min( $total, $current + 1 ) ) ) );
sort( $pages );

$targets = array(
	'first' => 1,
	'prev'  => max( 1, $current - 1 ),
	'next'  => min( $total, $current + 1 ),
	'last'  => $total,
);

$control = function ( $name ) use ( $targets, $labels, $current, $url ) {
	$icon = ca3_icon( 'pagination-' . $name );

	if ( $targets[ $name ] === $current ) {
		return sprintf( '<span class="c-pagination__item c-pagination__item--control is-disabled" aria-hidden="true">%s</span>', $icon );
	}

	return sprintf(
		'<a class="c-pagination__item c-pagination__item--control" href="%s" aria-label="%s" data-listing-link>%s</a>',
		esc_url( $url( $targets[ $name ] ) ),
		esc_attr( $labels[ $name ] ),
		$icon
	);
};
?>
<nav class="c-pagination">
	<?php echo $control( 'first' ) . $control( 'prev' ); ?>

	<?php
	$previous = 0;
	foreach ( $pages as $page ) :
		if ( $page - $previous > 1 ) :
			?>
			<span class="c-pagination__item c-pagination__item--gap" aria-hidden="true">...</span>
			<?php
		endif;
		$previous = $page;

		if ( $page === $current ) :
			?>
			<span class="c-pagination__item is-current" aria-current="page"><?php echo esc_html( $page ); ?></span>
		<?php else : ?>
			<a class="c-pagination__item" href="<?php echo esc_url( $url( $page ) ); ?>" data-listing-link><?php echo esc_html( $page ); ?></a>
			<?php
		endif;
	endforeach;
	?>

	<?php echo $control( 'next' ) . $control( 'last' ); ?>
</nav>
