<?php
/**
 * Componente: Números.
 *
 * @package cidade-ativa-3
 */

$items = ca3_get( $args, 'items', array() );

if ( ! $items ) {
	return;
}

ca3_section_open( 'numeros', $args );
?>
	<div class="l-container">
		<ul class="c-numeros__list">
			<?php foreach ( $items as $item ) : ?>
				<li class="c-numeros__item">
					<?php echo ca3_image( ca3_get( $item, 'shape' ), 'medium', 'c-numeros__shape', array( 'alt' => '' ) ); ?>
					<strong class="c-numeros__number"><?php echo esc_html( ca3_get( $item, 'number' ) ); ?></strong>
					<span class="c-numeros__label"><?php echo esc_html( ca3_get( $item, 'label' ) ); ?></span>
				</li>
			<?php endforeach; ?>
		</ul>
	</div>
<?php
ca3_section_close();
