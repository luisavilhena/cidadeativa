<?php
/**
 * Seção "Entre em contato" (última antes do rodapé) e rodapé: logo, menu
 * do rodapé, endereço e redes sociais.
 * Textos em Aparência → Cabeçalho e rodapé; itens em Aparência → Menus.
 *
 * @package cidade-ativa-3
 */

$address = ca3_chrome_option( 'address' );
$social  = ca3_social_links();

// Entre em contato
$contact_title = ca3_chrome_option( 'contact_title' ) ? ca3_chrome_option( 'contact_title' ) : __( 'Entre em contato', 'cidade-ativa-3' );
$contact_email = ca3_contact_email();
?>
	<?php if ( $contact_title || $contact_email ) : ?>
		<section class="c-contato" id="contato">
			<div class="l-container c-contato__inner">
				<img class="c-contato__icon" src="<?php echo esc_url( CA3_URI . '/assets/images/contato-catavento.svg' ); ?>" alt="" width="66" height="65">

				<?php if ( $contact_title ) : ?>
					<h2 class="c-contato__title"><?php echo esc_html( $contact_title ); ?></h2>
				<?php endif; ?>

				<?php if ( $contact_email ) : ?>
					<a class="c-contato__email" href="<?php echo esc_url( 'mailto:' . $contact_email ); ?>"><?php echo esc_html( $contact_email ); ?></a>
				<?php endif; ?>

				<?php if ( $social ) : ?>
					<ul class="c-contato__social">
						<?php foreach ( $social as $link ) : ?>
							<li>
								<a href="<?php echo esc_url( $link['link'] ); ?>" target="_blank" rel="noopener" aria-label="<?php echo esc_attr( ucfirst( $link['rede'] ) ); ?>">
									<?php echo ca3_icon( 'social-' . $link['rede'], 'c-contato__social-icon' ); ?>
								</a>
							</li>
						<?php endforeach; ?>
					</ul>
				<?php endif; ?>
			</div>
		</section>
	<?php endif; ?>
</main>
<footer class="c-site-footer">
	<div class="l-container c-site-footer__inner">
		<a class="c-site-footer__logo" href="<?php echo esc_url( home_url( '/' ) ); ?>">
			<img src="<?php echo esc_url( CA3_URI . '/assets/images/logo-rodape.svg' ); ?>" alt="<?php echo esc_attr( get_bloginfo( 'name' ) ); ?>" width="259" height="48">
		</a>

		<?php
		wp_nav_menu(
			array(
				'theme_location' => 'rodape',
				'container'      => 'nav',
				'container_class' => 'c-site-footer__nav',
				'menu_class'     => 'c-site-footer__menu',
				'depth'          => 1,
				'fallback_cb'    => false,
			)
		);
		?>

		<div class="c-site-footer__contact">
			<?php if ( $address ) : ?>
				<address class="c-site-footer__address"><?php echo nl2br( esc_html( $address ) ); ?></address>
			<?php endif; ?>

			<?php if ( $social ) : ?>
				<ul class="c-site-footer__social">
					<?php foreach ( $social as $link ) : ?>
						<li>
							<a href="<?php echo esc_url( $link['link'] ); ?>" target="_blank" rel="noopener" aria-label="<?php echo esc_attr( ucfirst( $link['rede'] ) ); ?>">
								<?php echo ca3_icon( 'social-' . $link['rede'], 'c-site-footer__social-icon' ); ?>
							</a>
						</li>
					<?php endforeach; ?>
				</ul>
			<?php endif; ?>
		</div>
	</div>
</footer>
<?php wp_footer(); ?>
</body>
</html>
