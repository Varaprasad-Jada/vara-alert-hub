<?php
/**
 * Shown when there is nothing to list.
 *
 * Argument: context (search, archive or home).
 *
 * @package Vara_Alert_Hub
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$vara_alert_hub_context = isset( $args['context'] ) ? $args['context'] : 'archive';
?>
<section class="ah-surface">
	<?php if ( 'search' === $vara_alert_hub_context ) : ?>
		<div class="ah-board" aria-live="polite">
			<p class="ah-board__line">
				<?php
				/* translators: %s: search words */
				printf( esc_html__( '> Searching for "%s" ...', 'vara-alert-hub' ), esc_html( get_search_query( false ) ) );
				?>
			</p>
			<p class="ah-board__line is-bad"><?php esc_html_e( '> 0 updates found', 'vara-alert-hub' ); ?></p>
		</div>
		<p><?php esc_html_e( 'Check the spelling, try fewer words, or browse a category.', 'vara-alert-hub' ); ?></p>
		<?php
		$vara_alert_hub_all = get_categories( array(
			'orderby'    => 'count',
			'order'      => 'DESC',
			'number'     => 8,
			'hide_empty' => true,
		) );
		if ( $vara_alert_hub_all ) :
			?>
			<ul class="ah-chips" style="margin-bottom:18px;">
				<?php foreach ( $vara_alert_hub_all as $vara_alert_hub_term ) : ?>
					<li><a class="ah-chip" href="<?php echo esc_url( get_category_link( $vara_alert_hub_term->term_id ) ); ?>"><?php echo esc_html( $vara_alert_hub_term->name ); ?></a></li>
				<?php endforeach; ?>
			</ul>
		<?php endif; ?>

		<?php
		$vara_alert_hub_latest = new WP_Query( array(
			'posts_per_page'      => 3,
			'orderby'             => 'modified',
			'order'               => 'DESC',
			'ignore_sticky_posts' => true,
			'no_found_rows'       => true,
		) );
		if ( $vara_alert_hub_latest->have_posts() ) :
			?>
			<h2 class="ah-section-title"><?php esc_html_e( 'Latest updates', 'vara-alert-hub' ); ?></h2>
			<div class="ah-list" style="margin-bottom:0;">
				<?php
				while ( $vara_alert_hub_latest->have_posts() ) {
					$vara_alert_hub_latest->the_post();
					get_template_part( 'template-parts/row', null, array( 'compact' => true ) );
				}
				?>
			</div>
			<?php
		endif;
		wp_reset_postdata();
		?>
	<?php elseif ( 'home' === $vara_alert_hub_context ) : ?>
		<h2 class="ah-section-title"><?php esc_html_e( 'No updates yet', 'vara-alert-hub' ); ?></h2>
		<p><?php esc_html_e( 'Publish your first post and it will appear here.', 'vara-alert-hub' ); ?></p>
	<?php else : ?>
		<h2 class="ah-section-title"><?php esc_html_e( 'Nothing here yet', 'vara-alert-hub' ); ?></h2>
		<p><?php esc_html_e( 'No updates match this page. Try another category or go back to the homepage.', 'vara-alert-hub' ); ?></p>
		<a class="ah-btn" href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php esc_html_e( 'Back to home', 'vara-alert-hub' ); ?></a>
	<?php endif; ?>
</section>
