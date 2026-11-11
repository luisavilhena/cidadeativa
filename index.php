<?php
/**
 * Template padrão.
 *
 * @package cidade-ativa-3
 */

get_header();
?>
<div class="l-container">
	<?php
	while ( have_posts() ) :
		the_post();
		?>
		<article <?php post_class(); ?>>
			<h1><?php the_title(); ?></h1>
			<?php the_content(); ?>
		</article>
		<?php
	endwhile;
	?>
</div>
<?php
get_footer();
