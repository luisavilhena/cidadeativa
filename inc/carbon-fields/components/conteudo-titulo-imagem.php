<?php
/**
 * Campos do Conteúdo 5: título na coluna da direita e imagem grande.
 *
 * @package cidade-ativa-3
 */

use Carbon_Fields\Field;

return array(
	Field::make( 'text', 'title', __( 'Título', 'cidade-ativa-3' ) ),
	Field::make( 'image', 'image', __( 'Imagem', 'cidade-ativa-3' ) ),
);
