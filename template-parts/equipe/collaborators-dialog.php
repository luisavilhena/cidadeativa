<?php
/**
 * Popup "Colaboradores": título, texto e grade de cards. Cada card abre
 * o popup da própria pessoa (impresso logo depois deste).
 *
 * Argumentos: color, title, text, close_label, projects_label.
 *
 * @package cidade-ativa-3
 */

$collaborators = ca3_collaborators();
$colors        = ca3_team_card_colors();
?>
<dialog class="c-dialog c-collaborators-dialog has-program-<?php echo esc_attr( $args['color'] ); ?>" id="colaboradores" aria-labelledby="colaboradores-titulo">
	<button type="button" class="c-dialog__close" aria-label="<?php echo esc_attr( $args['close_label'] ); ?>" data-dialog-close></button>

	<header class="c-collaborators-dialog__header">
		<?php if ( $args['title'] ) : ?>
			<h2 class="c-collaborators-dialog__title" id="colaboradores-titulo"><?php echo esc_html( $args['title'] ); ?></h2>
		<?php endif; ?>
		<?php if ( $args['text'] ) : ?>
			<p class="c-collaborators-dialog__text"><?php echo esc_html( $args['text'] ); ?></p>
		<?php endif; ?>
	</header>

	<ul class="c-collaborators-dialog__grid">
		<?php foreach ( $collaborators as $i => $person ) : ?>
			<?php
			$color    = $colors[ $i % count( $colors ) ];
			$projects = ca3_person_projects( $person->ID );
			$linkedin = function_exists( 'carbon_get_post_meta' ) ? carbon_get_post_meta( $person->ID, 'linkedin_link' ) : '';
			?>
			<li class="c-collaborator has-program-<?php echo esc_attr( $color ); ?>">
				<button type="button" class="c-collaborator__open" data-dialog-open="<?php echo esc_attr( ca3_person_dialog_id( $person->ID ) ); ?>">
					<span class="c-person-photo c-collaborator__photo">
						<?php echo get_the_post_thumbnail( $person->ID, 'medium', array( 'alt' => '', 'loading' => 'lazy' ) ); ?>
					</span>
					<span class="c-collaborator__name"><?php echo esc_html( get_the_title( $person ) ); ?></span>
				</button>
				<?php $role = ca3_person_role( $person->ID ); ?>
				<?php if ( $role ) : ?>
					<p class="c-collaborator__role"><?php echo esc_html( $role ); ?></p>
				<?php endif; ?>
				<?php if ( $projects ) : ?>
					<p class="c-collaborator__projects">
						<?php
						echo implode(
							' <span aria-hidden="true">|</span> ',
							array_map(
								function ( $project ) {
									return '<a href="' . esc_url( get_permalink( $project ) ) . '">' . esc_html( get_the_title( $project ) ) . '</a>';
								},
								$projects
							)
						);
						?>
					</p>
				<?php endif; ?>
				<?php if ( $linkedin ) : ?>
					<a class="c-collaborator__linkedin" href="<?php echo esc_url( $linkedin ); ?>" target="_blank" rel="noopener" aria-label="LinkedIn"><?php echo ca3_icon( 'social-linkedin' ); ?></a>
				<?php endif; ?>
			</li>
		<?php endforeach; ?>
	</ul>
</dialog>

<?php foreach ( $collaborators as $i => $person ) : ?>
	<?php
	get_template_part(
		'template-parts/equipe/person-dialog',
		null,
		array(
			'post_id'        => $person->ID,
			'color'          => $colors[ $i % count( $colors ) ],
			'close_label'    => $args['close_label'],
			'projects_label' => $args['projects_label'],
		)
	);
	?>
<?php endforeach; ?>
