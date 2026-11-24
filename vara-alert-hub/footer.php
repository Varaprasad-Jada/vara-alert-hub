<?php
/**
 * The footer.
 *
 * @package Vara_Alert_Hub
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$vara_alert_hub_credit = vara_alert_hub_credit_html();
?>
		</main>

		<?php get_sidebar(); ?>
	</div>

	<footer id="colophon" class="site-footer">
		<div class="footer-inner footer-columns">
			<?php for ( $vara_alert_hub_i = 1; $vara_alert_hub_i <= 3; $vara_alert_hub_i++ ) : ?>
				<div class="footer-column">
					<?php
					if ( is_active_sidebar( 'footer-' . $vara_alert_hub_i ) ) {
						dynamic_sidebar( 'footer-' . $vara_alert_hub_i );
					} else {
						vara_alert_hub_footer_column( $vara_alert_hub_i );
					}
					?>
				</div>
			<?php endfor; ?>
		</div>
		<div class="footer-bottom">
			<div class="footer-inner">
				<span><?php echo wp_kses_post( vara_alert_hub_copyright_text() ); ?></span>
				<span>
					<?php
					if ( $vara_alert_hub_credit ) {
						echo wp_kses_post( $vara_alert_hub_credit ) . ' &middot; ';
					}
					?>
					<a href="<?php echo esc_url( __( 'https://wordpress.org/', 'vara-alert-hub' ) ); ?>"><?php esc_html_e( 'Powered by WordPress', 'vara-alert-hub' ); ?></a>
				</span>
			</div>
		</div>
	</footer>
</div><!-- #page -->
<?php wp_footer(); ?>
</body>
</html>
