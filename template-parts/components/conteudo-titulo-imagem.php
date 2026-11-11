<?php
/**
 * Componente: Conteúdo 5. Título na coluna da direita e imagem ocupando todo o espaço.
 *
 * @package cidade-ativa-3
 */

$title = ca3_get( $args, 'title' );
$image = ca3_get( $args, 'image' );

ca3_section_open( 'conteudo_titulo_imagem', $args, array( 'c-content' ) );
?>
	<?php if ( $title ) : ?>
		<div class="l-container l-grid-2">
			<h2 class="c-content__title c-conteudo-titulo-imagem__title"><?php echo esc_html( $title ); ?></h2>
		</div>
	<?php endif; ?>

	<?php if ( $image ) : ?>
		<figure class="c-conteudo-titulo-imagem__media">
			<?php echo ca3_image( $image, 'full', 'c-conteudo-titulo-imagem__image' ); ?>
		</figure>
	<?php endif; ?>
<?php
ca3_section_close();
