<?php
/**
 * Componente: Acervo.
 * Lista os itens mais recentes do acervo (quantidade definida no componente).
 *
 * @package cidade-ativa-3
 */

$title = ca3_get( $args, 'title' );
$text  = ca3_get( $args, 'text' );
$count = (int) ca3_get( $args, 'count', 4 );

$items = new WP_Query(
	array(
		'post_type'           => 'ca-biblioteca',
		'post_status'         => 'publish',
		'posts_per_page'      => $count > 0 ? $count : 4,
		'ignore_sticky_posts' => true,
		'no_found_rows'       => true,
	)
);

ca3_section_open( 'acervo', $args );
?>
	<div class="l-container">
		<?php if ( $title ) : ?>
			<h2 class="c-section__title"><?php echo esc_html( $title ); ?></h2>
		<?php endif; ?>

		<?php if ( $text ) : ?>
			<div class="c-section__intro"><?php echo ca3_rich_text( $text ); ?></div>
		<?php endif; ?>

		<div class="c-section__actions">
			<?php echo ca3_button( ca3_get( $args, 'button_label' ), ca3_get( $args, 'button_url' ), ca3_get( $args, 'button_style', 'solid' ), ca3_get( $args, 'button_icon' ), array( 'color' => ca3_get( $args, 'button_color', 'vermelho' ) ) ); ?>
		</div>

		<?php if ( $items->have_posts() ) : ?>
			<ul class="c-acervo__list">
				<?php
				while ( $items->have_posts() ) :
					$items->the_post();
					?>
					<li class="c-acervo__item">
						<?php get_template_part( 'template-parts/acervo/card', null, array( 'variant' => 'compact' ) ); ?>
					</li>
				<?php endwhile; ?>
			</ul>
			<?php wp_reset_postdata(); ?>
		<?php endif; ?>
	</div>
<?php
ca3_section_close();
