<?php
/**
 * Home sections: tabs, featured cards and the latest updates list.
 *
 * Argument: custom (true when used by a page template, so we run our own query).
 *
 * @package Vara_Alert_Hub
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

global $wp_query;

$vara_alert_hub_custom = ! empty( $args['custom'] );

if ( vara_alert_hub_get( 'show_tabs' ) ) {
	vara_alert_hub_tabs();
}

// Featured cards.
$vara_alert_hub_featured_cat = (int) vara_alert_hub_get( 'featured_cat' );
if ( vara_alert_hub_get( 'show_featured' ) && $vara_alert_hub_featured_cat ) {
	$vara_alert_hub_featured = new WP_Query( array(
		'category__in'        => array( $vara_alert_hub_featured_cat ),
		'posts_per_page'      => max( 1, absint( vara_alert_hub_get( 'featured_count' ) ) ),
		'orderby'             => 'modified',
		'order'               => 'DESC',
		'ignore_sticky_posts' => true,
		'no_found_rows'       => true,
	) );
	if ( $vara_alert_hub_featured->have_posts() ) :
		?>
		<h2 class="ah-section-title"><?php echo esc_html( vara_alert_hub_get( 'featured_title' ) ); ?></h2>
		<div class="ah-grid">
			<?php
			while ( $vara_alert_hub_featured->have_posts() ) {
				$vara_alert_hub_featured->the_post();
				get_template_part( 'template-parts/card' );
			}
			?>
		</div>
		<?php
	endif;
	wp_reset_postdata();
}

if ( ! vara_alert_hub_get( 'show_latest' ) ) {
	return;
}

// Latest updates list.
if ( $vara_alert_hub_custom ) {
	$vara_alert_hub_paged = vara_alert_hub_page_param();
	$vara_alert_hub_query = new WP_Query( array(
		'posts_per_page'      => max( 1, (int) get_option( 'posts_per_page' ) ),
		'paged'               => $vara_alert_hub_paged,
		'orderby'             => 'modified',
		'order'               => 'DESC',
		'ignore_sticky_posts' => true,
	) );
} else {
	$vara_alert_hub_paged = max( 1, (int) get_query_var( 'paged' ) );
	$vara_alert_hub_query = $wp_query;
}

if ( $vara_alert_hub_query->have_posts() ) :
	?>
	<h2 class="ah-section-title"><?php echo esc_html( vara_alert_hub_get( 'latest_title' ) ); ?></h2>
	<div class="ah-list">
		<?php
		while ( $vara_alert_hub_query->have_posts() ) {
			$vara_alert_hub_query->the_post();
			get_template_part( 'template-parts/row', null, array( 'compact' => true ) );
		}
		?>
	</div>
	<?php
	vara_alert_hub_pagination( $vara_alert_hub_paged, $vara_alert_hub_query->max_num_pages, $vara_alert_hub_custom );
else :
	get_template_part( 'template-parts/content-none', null, array( 'context' => 'home' ) );
endif;

if ( $vara_alert_hub_custom ) {
	wp_reset_postdata();
}
