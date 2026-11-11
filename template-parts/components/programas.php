<?php
/**
 * Componente: Nossos programas (carrossel).
 * Os cards são os programas marcados em "Mostrar no carrossel Nossos programas".
 *
 * @package cidade-ativa-3
 */

$title = ca3_get( $args, 'title' );

ca3_section_open( 'programas', $args );
?>
	<div class="l-container">
		<?php if ( $title ) : ?>
			<h2 class="c-section__title"><?php echo esc_html( $title ); ?></h2>
		<?php endif; ?>
	</div>

	<?php
	get_template_part(
		'template-parts/programas/carousel',
		null,
		array(
			'label_prev' => ca3_get( $args, 'label_prev' ),
			'label_next' => ca3_get( $args, 'label_next' ),
		)
	);
	?>
<?php
ca3_section_close();
