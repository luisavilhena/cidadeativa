<?php
/**
 * Componente: Conteúdo 7. Apenas um título especial (não ocupa as 2 colunas).
 *
 * @package cidade-ativa-3
 */

$title = ca3_get( $args, 'title' );

if ( ! $title ) {
	return;
}

ca3_section_open( 'conteudo_titulo_especial', $args, array( 'c-content' ) );
?>
	<div class="l-container">
		<h2 class="c-conteudo-titulo-especial__title"><?php echo nl2br( ca3_title_with_accent( $title ) ); ?></h2>
	</div>
<?php
ca3_section_close();
