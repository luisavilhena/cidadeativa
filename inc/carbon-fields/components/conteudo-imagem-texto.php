<?php
/**
 * Campos do Conteúdo 8: texto na coluna da esquerda e imagem na da direita.
 *
 * @package cidade-ativa-3
 */

use Carbon_Fields\Field;

return array(
	Field::make( 'text', 'title', __( 'Título', 'cidade-ativa-3' ) ),
	Field::make( 'text', 'subtitle', __( 'Subtítulo', 'cidade-ativa-3' ) ),
	Field::make( 'rich_text', 'text', __( 'Texto', 'cidade-ativa-3' ) ),
	Field::make( 'image', 'image', __( 'Imagem', 'cidade-ativa-3' ) ),
);
