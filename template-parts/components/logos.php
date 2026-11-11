<?php
/**
 * Componente: Logos (parcerias, premiações...).
 *
 * @package cidade-ativa-3
 */

$title = ca3_get( $args, 'title' );
$logos = ca3_get( $args, 'logos', array() );

ca3_section_open( 'logos', $args );
?>
	<div class="l-container">
		<?php if ( $title ) : ?>
			<h2 class="c-section__title"><?php echo esc_html( $title ); ?></h2>
		<?php endif; ?>

		<?php if ( $logos ) : ?>
			<ul class="c-logos__list">
				<?php foreach ( $logos as $logo ) : ?>
					<?php
					$name  = ca3_get( $logo, 'name' );
					$url   = ca3_get( $logo, 'url' );
					$image = ca3_image( ca3_get( $logo, 'image' ), 'medium', 'c-logos__image', array( 'alt' => $name ) );
					?>
					<li class="c-logos__item">
						<?php if ( $url ) : ?>
							<a class="c-logos__link" href="<?php echo esc_url( $url ); ?>" target="_blank" rel="noopener"><?php echo $image; ?></a>
						<?php else : ?>
							<?php echo $image; ?>
						<?php endif; ?>
					</li>
				<?php endforeach; ?>
			</ul>
		<?php endif; ?>
	</div>
<?php
ca3_section_close();
