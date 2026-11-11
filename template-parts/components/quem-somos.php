<?php
/**
 * Componente: Quem somos.
 * Semicírculo com título e texto, e ilustração com as etiquetas da equipe.
 *
 * @package cidade-ativa-3
 */

$title  = ca3_get( $args, 'title' );
$text   = ca3_get( $args, 'text' );
$image  = ca3_get( $args, 'image' );
$people = ca3_get( $args, 'people', array() );

// Popups abertos pelas etiquetas (impressos no fim da seção).
$dialogs      = array();
$collab_color = '';

ca3_section_open( 'quem_somos', $args );
?>
	<div class="c-quem-somos__intro">
		<div class="l-container c-quem-somos__intro-inner">
			<?php if ( $title ) : ?>
				<h2 class="c-section__title"><?php echo esc_html( $title ); ?></h2>
			<?php endif; ?>

			<?php if ( $text ) : ?>
				<div class="c-quem-somos__text"><?php echo ca3_rich_text( $text ); ?></div>
			<?php endif; ?>
		</div>
	</div>

	<?php if ( $image || $people ) : ?>
		<div class="l-container c-quem-somos__team">
			<?php echo ca3_image( $image, 'full', 'c-quem-somos__image' ); ?>

			<?php if ( $people ) : ?>
				<ul class="c-quem-somos__people">
					<?php foreach ( $people as $person ) : ?>
						<?php
						$type   = ca3_get( $person, 'tipo', ca3_get( $person, 'icon' ) ? 'colaboradores' : 'link' );
						$color  = ca3_get( $person, 'color', 'vermelho' );
						$member = 'membro' === $type ? ca3_tag_member( $person ) : null;
						$name   = ca3_get( $person, 'name', $member ? get_the_title( $member ) : '' );
						$photo  = ca3_get( $person, 'photo' );

						// Foto: a da etiqueta, a foto ícone da pessoa ou a imagem destacada.
						if ( ! $photo && $member && function_exists( 'carbon_get_post_meta' ) ) {
							$photo = carbon_get_post_meta( $member->ID, 'ca3_member_icon' );
						}
						if ( ! $photo && $member ) {
							$photo = get_post_thumbnail_id( $member );
						}

						// O que acontece ao clicar: abre um popup ou segue um link.
						$dialog = '';
						if ( $member ) {
							$dialog              = ca3_person_dialog_id( $member->ID );
							$dialogs[ $dialog ] = array( 'post_id' => $member->ID, 'color' => $color );
						} elseif ( 'colaboradores' === $type ) {
							$dialog              = 'colaboradores';
							$collab_color        = $color;
						}
						$url = 'link' === $type ? ca3_get( $person, 'url' ) : '';

						$classes = array( 'c-tag', 'c-tag--' . $color, 'c-quem-somos__person', 'is-' . ca3_get( $person, 'position', 'centro' ) );
						?>
						<li class="<?php echo esc_attr( implode( ' ', $classes ) ); ?>">
							<?php if ( $dialog ) : ?>
								<button type="button" class="c-tag__inner" data-dialog-open="<?php echo esc_attr( $dialog ); ?>" aria-haspopup="dialog">
							<?php elseif ( $url ) : ?>
								<a class="c-tag__inner" href="<?php echo esc_url( $url ); ?>">
							<?php else : ?>
								<span class="c-tag__inner">
							<?php endif; ?>
								<span class="c-tag__avatar">
									<?php if ( 'colaboradores' === $type && ! $photo ) : ?>
										<?php echo ca3_icon( 'perm-identity', 'c-tag__icon' ); ?>
									<?php else : ?>
										<?php echo ca3_image( $photo, 'thumbnail', 'c-tag__photo', array( 'alt' => '' ) ); ?>
									<?php endif; ?>
								</span>
								<span class="c-tag__name"><?php echo esc_html( $name ); ?></span>
							<?php echo $dialog ? '</button>' : ( $url ? '</a>' : '</span>' ); ?>
						</li>
					<?php endforeach; ?>
				</ul>
			<?php endif; ?>
		</div>
	<?php endif; ?>

	<?php
	$close_label    = ca3_get( $args, 'close_label' );
	$projects_label = ca3_get( $args, 'projects_label' );

	foreach ( $dialogs as $dialog ) {
		get_template_part( 'template-parts/equipe/person-dialog', null, array_merge( $dialog, array( 'close_label' => $close_label, 'projects_label' => $projects_label ) ) );
	}

	if ( $collab_color ) {
		get_template_part(
			'template-parts/equipe/collaborators-dialog',
			null,
			array(
				'color'          => $collab_color,
				'title'          => ca3_get( $args, 'collab_title' ),
				'text'           => ca3_get( $args, 'collab_text' ),
				'close_label'    => $close_label,
				'projects_label' => $projects_label,
			)
		);
	}
	?>
<?php
ca3_section_close();
