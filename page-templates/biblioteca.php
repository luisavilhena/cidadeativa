<?php
/**
 * Template Name: Acervo
 *
 * Listagem do Acervo com busca, ordenação, filtro por categoria e paginação.
 * O mesmo caminho do tema antigo, para a página "Acervo" continuar usando este template.
 *
 * @package cidade-ativa-3
 */

get_header();

$state      = ca3_acervo_state();
$categories = ca3_acervo_categories();
$intro      = ca3_acervo_field( 'intro' );
?>
<section class="c-acervo-lista" data-listing>
	<div class="c-acervo-lista__container">
		<header class="c-page-header">
			<h1 class="c-page-header__title"><?php the_title(); ?></h1>
			<?php if ( $intro ) : ?>
				<div class="c-page-header__intro"><?php echo ca3_rich_text( $intro ); ?></div>
			<?php endif; ?>
		</header>

		<form class="c-acervo-lista__toolbar" action="<?php echo esc_url( get_permalink() ); ?>" method="get" role="search" data-listing-form>
			<?php if ( $state['c'] ) : ?>
				<input type="hidden" name="c" value="<?php echo esc_attr( $state['c'] ); ?>" data-listing-category-input>
			<?php endif; ?>

			<div class="c-search c-acervo-lista__search">
				<?php echo ca3_icon( 'search', 'c-search__icon' ); ?>
				<input
					class="c-search__input"
					type="search"
					name="q"
					value="<?php echo esc_attr( $state['q'] ); ?>"
					placeholder="<?php echo esc_attr( ca3_acervo_field( 'search_placeholder' ) ); ?>"
					aria-label="<?php echo esc_attr( ca3_acervo_field( 'search_placeholder' ) ); ?>"
					data-listing-search
				>
				<button class="u-visually-hidden" type="submit"><?php echo esc_html( ca3_acervo_field( 'search_button' ) ); ?></button>
			</div>

			<div class="c-acervo-lista__sort">
				<label class="c-acervo-lista__sort-label" for="acervo-ordem"><?php echo esc_html( ca3_acervo_field( 'sort_label' ) ); ?></label>
				<select class="c-acervo-lista__sort-select" id="acervo-ordem" name="ord" data-listing-sort>
					<option value=""<?php selected( $state['ord'], 0 ); ?>><?php echo esc_html( ca3_acervo_field( 'sort_newest' ) ); ?></option>
					<option value="1"<?php selected( $state['ord'], 1 ); ?>><?php echo esc_html( ca3_acervo_field( 'sort_oldest' ) ); ?></option>
				</select>
			</div>
		</form>

		<div data-listing-results>
			<?php get_template_part( 'template-parts/acervo/results', null, array( 'state' => $state, 'categories' => $categories ) ); ?>
		</div>
	</div>
</section>
<?php
get_footer();
