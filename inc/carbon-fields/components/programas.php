<?php
/**
 * Campos do componente Nossos programas.
 * O conteúdo do carrossel será definido depois (outro tipo de conteúdo).
 *
 * @package cidade-ativa-3
 */

use Carbon_Fields\Field;

return array(
	Field::make( 'text', 'title', __( 'Título', 'cidade-ativa-3' ) ),
	Field::make( 'text', 'label_prev', __( 'Texto acessível do botão "anterior"', 'cidade-ativa-3' ) )->set_width( 50 ),
	Field::make( 'text', 'label_next', __( 'Texto acessível do botão "próximo"', 'cidade-ativa-3' ) )->set_width( 50 ),
);
