<?php
/**
 * Componente: Conteúdo 8. Título/subtítulo/texto na coluna da esquerda e imagem na da direita.
 *
 * @package cidade-ativa-3
 */

$title    = ca3_get( $args, 'title' );
$subtitle = ca3_get( $args, 'subtitle' );
$text     = ca3_get( $args, 'text' );
$image    = ca3_get( $args, 'image' );

ca3_section_open( 'conteudo_imagem_texto', $args, array( 'c-content' ) );
?>
	<div class="l-container l-grid-2">
		<div class="c-conteudo-imagem-texto__body">
			<?php if ( $title ) : ?>
				<h2 class="c-content__title"><?php echo esc_html( $title ); ?></h2>
			<?php endif; ?>
			<?php if ( $subtitle ) : ?>
				<h3 class="c-content__subtitle"><?php echo esc_html( $subtitle ); ?></h3>
			<?php endif; ?>
			<div class="c-content__text"><?php echo ca3_rich_text( $text ); ?></div>
		</div>

		<?php if ( $image ) : ?>
			<figure class="c-conteudo-imagem-texto__media">
				<?php echo ca3_image( $image, 'large', 'c-content__image' ); ?>
			</figure>
		<?php endif; ?>
	</div>
<?php
ca3_section_close();
