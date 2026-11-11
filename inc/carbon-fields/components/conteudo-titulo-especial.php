<?php
/**
 * Campos do Conteúdo 7: apenas um título especial.
 *
 * @package cidade-ativa-3
 */

use Carbon_Fields\Field;

return array(
	Field::make( 'textarea', 'title', __( 'Título', 'cidade-ativa-3' ) )->set_rows( 3 ),
);
