<?php
/**
 * Componente: Conteúdo 3 / 9. Título, texto e imagem.
 *
 * @package cidade-ativa-3
 */

$title          = ca3_get( $args, 'title' );
$text           = ca3_get( $args, 'text' );
$image          = ca3_get( $args, 'image' );
$image_position = ca3_get( $args, 'image_position', 'right' );

ca3_section_open( 'conteudo_texto_imagem', $args, array( 'c-content', 'has-image-' . $image_position ) );
?>
	<div class="l-container l-grid-2">
		<div class="c-conteudo-texto-imagem__body">
			<?php if ( $title ) : ?>
				<h2 class="c-content__title"><?php echo esc_html( $title ); ?></h2>
			<?php endif; ?>
			<div class="c-content__text"><?php echo ca3_rich_text( $text ); ?></div>
		</div>

		<?php if ( $image ) : ?>
			<figure class="c-conteudo-texto-imagem__media">
				<?php echo ca3_image( $image, 'large', 'c-content__image' ); ?>
			</figure>
		<?php endif; ?>
	</div>
<?php
ca3_section_close();
