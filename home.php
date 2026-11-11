<?php
/**
 * Listagem de Notícias (página de posts do site).
 * Busca que atualiza na própria página, grid de cards e paginação.
 *
 * @package cidade-ativa-3
 */

get_header();

$state = ca3_news_state();
$intro = ca3_news_option( 'intro' );
$title = ca3_news_option( 'title' );
?>
<section class="c-noticias-lista" data-listing>
	<div class="l-container">
		<header class="c-page-header">
			<?php if ( $title ) : ?>
				<h1 class="c-page-header__title"><?php echo esc_html( $title ); ?></h1>
			<?php endif; ?>
			<?php if ( $intro ) : ?>
				<div class="c-page-header__intro c-page-header__intro--lg"><?php echo ca3_rich_text( $intro ); ?></div>
			<?php endif; ?>
		</header>

		<form class="c-noticias-lista__toolbar" action="<?php echo esc_url( ca3_news_base_url() ); ?>" method="get" role="search" data-listing-form>
			<div class="c-search c-search--lg c-noticias-lista__search">
				<?php echo ca3_icon( 'search', 'c-search__icon' ); ?>
				<input
					class="c-search__input"
					type="search"
					name="q"
					value="<?php echo esc_attr( $state['q'] ); ?>"
					placeholder="<?php echo esc_attr( ca3_news_option( 'search_placeholder' ) ); ?>"
					aria-label="<?php echo esc_attr( ca3_news_option( 'search_placeholder' ) ); ?>"
					data-listing-search
				>
				<button class="u-visually-hidden" type="submit"><?php echo esc_html( ca3_news_option( 'search_button' ) ); ?></button>
			</div>
		</form>

		<div data-listing-results>
			<?php get_template_part( 'template-parts/noticias/results', null, array( 'state' => $state ) ); ?>
		</div>
	</div>
</section>

<?php get_template_part( 'template-parts/noticias/periodicos' ); ?>
<?php
get_footer();
