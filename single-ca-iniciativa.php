<?php
/**
 * Página de um programa (iniciativa de 1º nível) ou de uma iniciativa filha.
 * Ver inc/programas.php.
 *
 * @package cidade-ativa-3
 */

get_header();

while ( have_posts() ) :
	the_post();

	$post_id    = get_the_ID();
	$color      = ca3_program_color( $post_id );
	$data       = ca3_program_cover_data( $post_id );
	$quote      = ca3_program_field( $post_id, 'quote' );
	$side_text  = ca3_program_field( $post_id, 'side_text' );
	$axes       = ca3_program_axes( $post_id );
	$products   = ca3_program_rows( $post_id, 'products', 'texto' );
	$downloads  = ca3_program_downloads( $post_id );
	$actions    = ca3_program_actions( $post_id );
	$crumb      = ca3_program_text( 'breadcrumb' );
	$crumb_url  = ca3_program_text( 'breadcrumb_url' );
	$ancestors  = array_reverse( get_post_ancestors( $post_id ) );
	$aside      = array_filter( array( $quote, $axes, $products, $downloads, $actions ) );
	?>
	<article class="c-programa has-program-<?php echo esc_attr( $color ); ?>">
		<header class="c-programa__cover">
			<div class="c-programa__info">
				<p class="c-programa__breadcrumb">
					<?php if ( $crumb ) : ?>
						<?php if ( $crumb_url ) : ?>
							<a href="<?php echo esc_url( $crumb_url ); ?>"><?php echo esc_html( $crumb ); ?></a>
						<?php else : ?>
							<span><?php echo esc_html( $crumb ); ?></span>
						<?php endif; ?>
						<span class="c-programa__breadcrumb-sep" aria-hidden="true">&gt;</span>
					<?php endif; ?>
					<?php foreach ( $ancestors as $ancestor ) : ?>
						<a href="<?php echo esc_url( get_permalink( $ancestor ) ); ?>"><?php echo esc_html( get_the_title( $ancestor ) ); ?></a>
						<span class="c-programa__breadcrumb-sep" aria-hidden="true">&gt;</span>
					<?php endforeach; ?>
					<strong><?php the_title(); ?></strong>
				</p>

				<h1 class="c-programa__title"><?php echo ca3_program_title_html( get_the_title(), $post_id ); ?></h1>

				<?php if ( $data ) : ?>
					<dl class="c-programa__data">
						<?php foreach ( $data as $row ) : ?>
							<div>
								<?php if ( $row['item'] ) : ?>
									<dt><?php echo esc_html( $row['item'] ); ?>:</dt>
								<?php endif; ?>
								<dd><?php echo esc_html( $row['conteudo'] ); ?></dd>
							</div>
						<?php endforeach; ?>
					</dl>
				<?php endif; ?>
			</div>

			<?php if ( has_post_thumbnail() ) : ?>
				<figure class="c-programa__media">
					<?php the_post_thumbnail( 'full', array( 'class' => 'c-programa__image', 'alt' => '', 'loading' => 'eager' ) ); ?>
				</figure>
			<?php endif; ?>
		</header>

		<?php if ( $aside || $side_text ) : ?>
			<div class="l-container c-programa__columns">
				<div class="c-programa__aside" data-program-aside>
					<?php if ( $quote ) : ?>
						<blockquote class="c-programa__quote">
							<span class="u-accent">“</span><?php echo esc_html( $quote ); ?><span class="u-accent">”</span>
						</blockquote>
					<?php endif; ?>

					<?php if ( $axes ) : ?>
						<section class="c-programa__block">
							<h2 class="c-programa__block-title"><?php echo esc_html( ca3_program_text( 'axes_title' ) ); ?></h2>
							<ul class="c-programa__axes">
								<?php foreach ( $axes as $axis ) : ?>
									<li class="c-programa__axis">
										<span class="c-programa__axis-name"><?php echo esc_html( get_the_title( $axis ) ); ?></span>
										<?php if ( has_excerpt( $axis ) ) : ?>
											<span class="c-programa__axis-text"><?php echo esc_html( trim( get_the_excerpt( $axis ) ) ); ?></span>
										<?php endif; ?>
									</li>
								<?php endforeach; ?>
							</ul>
						</section>
					<?php endif; ?>

					<?php if ( $products ) : ?>
						<section class="c-programa__block">
							<h2 class="c-programa__block-title"><?php echo esc_html( ca3_program_text( 'products_title' ) ); ?></h2>
							<ul class="c-programa__products">
								<?php foreach ( $products as $product ) : ?>
									<li>
										<?php if ( ! empty( $product['link'] ) ) : ?>
											<a href="<?php echo esc_url( $product['link'] ); ?>"><?php echo esc_html( $product['texto'] ); ?></a>
										<?php else : ?>
											<?php echo esc_html( $product['texto'] ); ?>
										<?php endif; ?>
									</li>
								<?php endforeach; ?>
							</ul>
						</section>
					<?php endif; ?>

					<?php if ( $downloads ) : ?>
						<section class="c-programa__block">
							<h2 class="c-programa__block-title"><?php echo esc_html( ca3_program_text( 'downloads_title' ) ); ?></h2>
							<ul class="c-programa__links">
								<?php foreach ( $downloads as $download ) : ?>
									<li>
										<a class="c-link-card c-link-card--<?php echo esc_attr( $color ); ?>" href="<?php echo esc_url( $download['file'] ); ?>" target="_blank" rel="noopener">
											<span class="c-link-card__text"><?php echo esc_html( $download['name'] ); ?></span>
											<?php echo ca3_icon( 'download', 'c-link-card__icon c-link-card__icon--plain' ); ?>
										</a>
									</li>
								<?php endforeach; ?>
							</ul>
						</section>
					<?php endif; ?>

					<?php if ( $actions ) : ?>
						<section class="c-programa__block">
							<h2 class="c-programa__block-title"><?php echo esc_html( ca3_program_text( 'actions_title' ) ); ?></h2>
							<ul class="c-programa__links">
								<?php foreach ( $actions as $action ) : ?>
									<li>
										<a class="c-link-card c-link-card--<?php echo esc_attr( $color ); ?>" href="<?php echo esc_url( get_permalink( $action ) ); ?>">
											<span class="c-link-card__text"><?php echo esc_html( get_the_title( $action ) ); ?></span>
											<?php echo ca3_icon( 'link-arrow', 'c-link-card__icon' ); ?>
										</a>
									</li>
								<?php endforeach; ?>
							</ul>
						</section>
					<?php endif; ?>
				</div>

				<?php if ( $side_text ) : ?>
					<div class="c-programa__side-text"><?php echo ca3_rich_text( $side_text ); ?></div>
				<?php else : ?>
					<?php // Preenchida pelo assets/js/programa.js com os primeiros parágrafos do texto. ?>
					<div class="c-programa__side-text" data-program-side></div>
				<?php endif; ?>
			</div>
		<?php endif; ?>

		<?php if ( '' !== trim( get_the_content() ) ) : ?>
			<div class="l-container">
				<div class="c-programa__content c-post-content c-post-content--centered" data-program-content>
					<?php ca3_the_post_content(); ?>
				</div>
			</div>
		<?php endif; ?>
	</article>

	<?php $carousel_title = ca3_program_text( 'carousel_title' ); ?>
	<section class="c-section c-programas">
		<?php if ( $carousel_title ) : ?>
			<div class="l-container">
				<h2 class="c-section__title"><?php echo esc_html( $carousel_title ); ?></h2>
			</div>
		<?php endif; ?>
		<?php
		get_template_part(
			'template-parts/programas/carousel',
			null,
			array(
				'label_prev' => ca3_program_text( 'carousel_prev' ),
				'label_next' => ca3_program_text( 'carousel_next' ),
			)
		);
		?>
	</section>
	<?php
endwhile;

get_footer();
