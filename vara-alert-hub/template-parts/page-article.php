<?php
/**
 * A page: optional title block, then the content.
 *
 * Arguments: title (show the title block), bare (no card around the content).
 *
 * @package Vara_Alert_Hub
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$vara_alert_hub_show_title = ! empty( $args['title'] );
$vara_alert_hub_bare       = ! empty( $args['bare'] );
$vara_alert_hub_hide_upd   = (bool) get_post_meta( get_the_ID(), '_vara_alert_hub_hide_updated', true );
$vara_alert_hub_classes    = $vara_alert_hub_bare ? '' : 'post-card';
?>
<article id="post-<?php the_ID(); ?>" <?php post_class( $vara_alert_hub_classes ); ?>>
	<?php if ( $vara_alert_hub_show_title ) : ?>
		<header class="ah-page-title">
			<h1><?php the_title(); ?></h1>
			<?php if ( ! $vara_alert_hub_hide_upd ) : ?>
				<p>
					<?php
					/* translators: %s: date */
					printf( esc_html__( 'Last updated %s', 'vara-alert-hub' ), esc_html( get_the_modified_date() ) );
					?>
				</p>
			<?php endif; ?>
		</header>
	<?php endif; ?>

	<div class="entry-content ah-page-body<?php echo $vara_alert_hub_bare ? ' ah-page-body--bare' : ''; ?>">
		<?php
		the_content();
		wp_link_pages( array(
			'before' => '<div class="page-links">' . esc_html__( 'Pages:', 'vara-alert-hub' ),
			'after'  => '</div>',
		) );
		?>
	</div>
</article>
