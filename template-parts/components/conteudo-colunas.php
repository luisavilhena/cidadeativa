<?php
/**
 * Componente: Conteúdo 1 / 2 / 6.
 * Até 2 colunas (subtítulo + texto) com título opcional acima,
 * na largura toda ou alinhado à coluna da esquerda/direita.
 *
 * @package cidade-ativa-3
 */

$title          = ca3_get( $args, 'title' );
$title_position = ca3_get( $args, 'title_position', 'full' );
$columns        = ca3_get( $args, 'columns', array() );

ca3_section_open( 'conteudo_colunas', $args, array( 'c-content' ) );
?>
	<div class="l-container l-grid-2<?php echo $title ? ' has-title' : ''; ?>">
		<?php if ( $title ) : ?>
			<h2 class="c-content__title c-conteudo-colunas__title is-<?php echo esc_attr( $title_position ); ?>"><?php echo esc_html( $title ); ?></h2>
		<?php endif; ?>

		<?php foreach ( $columns as $column ) : ?>
			<div class="c-conteudo-colunas__column">
				<?php if ( ca3_get( $column, 'subtitle' ) ) : ?>
					<h3 class="c-content__subtitle"><?php echo esc_html( $column['subtitle'] ); ?></h3>
				<?php endif; ?>
				<div class="c-content__text"><?php echo ca3_rich_text( ca3_get( $column, 'text' ) ); ?></div>
			</div>
		<?php endforeach; ?>
	</div>
<?php
ca3_section_close();
