<?php
/**
 * Cidade Ativa 3 - funções do tema.
 *
 * @package cidade-ativa-3
 */

define( 'CA3_DIR', get_template_directory() );
define( 'CA3_URI', get_template_directory_uri() );
define( 'CA3_VERSION', wp_get_theme()->get( 'Version' ) );

require_once CA3_DIR . '/inc/setup.php';
require_once CA3_DIR . '/inc/post-types/admin-menus.php';
require_once CA3_DIR . '/inc/post-types/iniciativa.php';
require_once CA3_DIR . '/inc/post-types/biblioteca.php';
require_once CA3_DIR . '/inc/post-types/equipe.php';
require_once CA3_DIR . '/inc/components.php';
require_once CA3_DIR . '/inc/acervo.php';
require_once CA3_DIR . '/inc/acervo-item.php';
require_once CA3_DIR . '/inc/noticias.php';
require_once CA3_DIR . '/inc/noticia.php';
require_once CA3_DIR . '/inc/programas.php';
require_once CA3_DIR . '/inc/site-chrome.php';
require_once CA3_DIR . '/inc/equipe.php';
require_once CA3_DIR . '/inc/carbon-fields.php';
require_once CA3_DIR . '/inc/enqueue.php';
require_once CA3_DIR . '/inc/helpers/template-tags.php';
