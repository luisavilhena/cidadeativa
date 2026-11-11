<?php
/**
 * Página montada com os componentes do Carbon Fields.
 *
 * @package cidade-ativa-3
 */

get_header();

while ( have_posts() ) :
	the_post();
	ca3_render_components();
endwhile;

get_footer();
