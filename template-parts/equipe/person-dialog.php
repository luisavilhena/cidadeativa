<?php
/**
 * Popup de uma pessoa (membro da equipe ou colaborador): foto (cores originais, alinhada pelo topo),
 * nome, cargo, LinkedIn e, à direita, descrição e projetos.
 *
 * Argumentos: post_id, color, close_label, projects_label.
 *
 * @package cidade-ativa-3
 */

$post_id     = (int) $args['post_id'];
$color       = $args['color'];
$role        = ca3_person_role( $post_id );
$description = ca3_person_description( $post_id );
$projects    = ca3_person_projects( $post_id );
$linkedin    = function_exists( 'carbon_get_post_meta' ) ? carbon_get_post_meta( $post_id, 'linkedin_link' ) : '';
$dialog_id   = ca3_person_dialog_id( $post_id );
?>
<dialog class="c-dialog c-person-dialog has-program-<?php echo esc_attr( $color ); ?>" id="<?php echo esc_attr( $dialog_id ); ?>" aria-labelledby="<?php echo esc_attr( $dialog_id ); ?>-nome">
	<button type="button" class="c-dialog__close" aria-label="<?php echo esc_attr( $args['close_label'] ); ?>" data-dialog-close></button>

	<div class="c-person-dialog__grid">
		<div class="c-person-dialog__profile">
			<?php if ( has_post_thumbnail( $post_id ) ) : ?>
				<figure class="c-person-photo c-person-dialog__photo">
					<?php echo get_the_post_thumbnail( $post_id, 'medium_large', array( 'alt' => '', 'loading' => 'lazy' ) ); ?>
				</figure>
			<?php endif; ?>

			<h2 class="c-person-dialog__name" id="<?php echo esc_attr( $dialog_id ); ?>-nome"><?php echo esc_html( get_the_title( $post_id ) ); ?></h2>
			<?php if ( $role ) : ?>
				<p class="c-person-dialog__role"><?php echo esc_html( $role ); ?></p>
			<?php endif; ?>

			<?php if ( $linkedin ) : ?>
				<a class="c-person-dialog__linkedin" href="<?php echo esc_url( $linkedin ); ?>" target="_blank" rel="noopener" aria-label="LinkedIn">
					<?php echo ca3_icon( 'social-linkedin' ); ?>
				</a>
			<?php endif; ?>
		</div>

		<div class="c-person-dialog__text">
			<?php if ( $description ) : ?>
				<?php echo wp_kses_post( $description ); ?>
			<?php endif; ?>

			<?php if ( $projects ) : ?>
				<?php if ( $args['projects_label'] ) : ?>
					<h3 class="c-person-dialog__projects-title"><?php echo esc_html( $args['projects_label'] ); ?></h3>
				<?php endif; ?>
				<ul class="c-person-dialog__projects">
					<?php foreach ( $projects as $project ) : ?>
						<li><a href="<?php echo esc_url( get_permalink( $project ) ); ?>"><?php echo esc_html( get_the_title( $project ) ); ?></a></li>
					<?php endforeach; ?>
				</ul>
			<?php endif; ?>
		</div>
	</div>
</dialog>
