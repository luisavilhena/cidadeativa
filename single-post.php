<?php
/**
 * Página de uma notícia.
 * O texto vem do editor; as imagens seguem o grid (ver inc/noticia.php).
 *
 * @package cidade-ativa-3
 */

get_header();

while ( have_posts() ) :
	the_post();
	$prefix = ca3_news_single_text( 'author_prefix' );
	?>
	<article class="c-noticia">
		<div class="l-container">
			<?php if ( has_post_thumbnail() ) : ?>
				<figure class="c-noticia__banner">
					<?php the_post_thumbnail( 'full', array( 'class' => 'c-noticia__banner-image', 'alt' => '', 'loading' => 'eager' ) ); ?>
				</figure>
			<?php endif; ?>

			<header class="c-noticia__header">
				<h1 class="c-noticia__title"><?php the_title(); ?></h1>
				<p class="c-noticia__meta">
					<time datetime="<?php echo esc_attr( get_the_date( 'c' ) ); ?>"><?php echo esc_html( ucfirst( get_the_date( 'F j. Y' ) ) ); ?></time>
					| <?php echo esc_html( trim( $prefix . ' ' . get_the_author() ) ); ?>
				</p>
			</header>

			<div class="c-noticia__content c-post-content">
				<?php ca3_the_post_content(); ?>
			</div>
		</div>
	</article>

	<?php get_template_part( 'template-parts/noticias/related', null, array( 'post_id' => get_the_ID() ) ); ?>
	<?php
endwhile;

get_footer();
