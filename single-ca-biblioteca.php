<?php
/**
 * Página de um item do Acervo.
 * Os dados vêm dos campos "Dados da publicação" ou, se vazios, do texto antigo
 * do post (ver inc/acervo-item.php).
 *
 * @package cidade-ativa-3
 */

get_header();

while ( have_posts() ) :
	the_post();

	$data     = ca3_acervo_item_data( get_the_ID() );
	$category = ca3_acervo_item_category( get_the_ID(), ca3_acervo_page_id() );
	?>
	<article class="c-acervo-item">
		<div class="c-acervo-item__container">
			<div class="c-acervo-item__grid">
				<header class="c-acervo-item__header">
					<h1 class="c-acervo-item__title"><?php the_title(); ?></h1>
					<p class="c-acervo-item__meta"><?php echo esc_html( $category ? $category . '. ' . get_the_date( 'Y' ) : get_the_date( 'Y' ) ); ?></p>
				</header>

				<aside class="c-acervo-item__aside">
					<?php if ( has_post_thumbnail() ) : ?>
						<figure class="c-acervo-item__cover">
							<?php the_post_thumbnail( 'large', array( 'class' => 'c-acervo-item__cover-image', 'alt' => '' ) ); ?>
						</figure>
					<?php endif; ?>

					<?php echo ca3_button( ca3_acervo_item_text( 'download_label' ), $data['download'], 'solid', 'download', array( 'class' => 'c-acervo-item__download', 'new_tab' => true ) ); ?>
				</aside>

				<div class="c-acervo-item__body">
					<?php if ( $data['video'] ) : ?>
						<div class="c-acervo-item__video">
							<iframe src="<?php echo esc_url( $data['video'] ); ?>" title="<?php the_title_attribute(); ?>" loading="lazy" allowfullscreen></iframe>
						</div>
					<?php endif; ?>

					<?php if ( $data['description'] ) : ?>
						<div class="c-acervo-item__description"><?php echo wp_kses_post( $data['description'] ); ?></div>
					<?php endif; ?>

					<?php if ( $data['date'] || $data['ficha'] ) : ?>
						<div class="c-acervo-item__info">
							<?php if ( $data['date'] ) : ?>
								<p><?php echo esc_html( ca3_acervo_item_text( 'date_label' ) ); ?> <strong><?php echo esc_html( $data['date'] ); ?></strong></p>
							<?php endif; ?>

							<?php if ( $data['ficha'] ) : ?>
								<p><?php echo esc_html( ca3_acervo_item_text( 'ficha_label' ) ); ?></p>
								<?php foreach ( $data['ficha'] as $row ) : ?>
									<p>
										<?php if ( $row['label'] ) : ?>
											<?php echo esc_html( $row['label'] ); ?>:
										<?php endif; ?>
										<strong class="c-acervo-item__ficha-value"><?php echo esc_html( $row['value'] ); ?></strong>
									</p>
								<?php endforeach; ?>
							<?php endif; ?>
						</div>
					<?php endif; ?>
				</div>
			</div>

			<?php if ( $data['links'] ) : ?>
				<section class="c-acervo-item__links">
					<h2 class="c-acervo-item__links-title"><?php echo esc_html( ca3_acervo_item_text( 'links_title' ) ); ?></h2>
					<ul class="c-acervo-item__links-list">
						<?php foreach ( $data['links'] as $link ) : ?>
							<li>
								<a class="c-link-card" href="<?php echo esc_url( $link['url'] ); ?>">
									<span class="c-link-card__text"><?php echo esc_html( $link['text'] ); ?></span>
									<?php echo ca3_icon( 'link-arrow', 'c-link-card__icon' ); ?>
								</a>
							</li>
						<?php endforeach; ?>
					</ul>
				</section>
			<?php endif; ?>
		</div>
	</article>

	<?php get_template_part( 'template-parts/acervo/related', null, array( 'post_id' => get_the_ID() ) ); ?>
	<?php
endwhile;

get_footer();
