<?php
/**
 * The main loop as a list or grid, with pagination.
 *
 * @package Vara_Alert_Hub
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

global $wp_query;

if ( ! have_posts() ) {
	get_template_part( 'template-parts/content-none', null, array( 'context' => is_search() ? 'search' : 'archive' ) );
	return;
}

$vara_alert_hub_grid = is_archive() && 'grid' === vara_alert_hub_view_param();

if ( $vara_alert_hub_grid ) {
	echo '<div class="ah-grid ah-grid--auto">';
	while ( have_posts() ) {
		the_post();
		get_template_part( 'template-parts/card' );
	}
	echo '</div>';
} else {
	echo '<div class="ah-list">';
	while ( have_posts() ) {
		the_post();
		get_template_part( 'template-parts/row', null, array( 'excerpt' => true ) );
	}
	echo '</div>';
}

vara_alert_hub_pagination( max( 1, (int) get_query_var( 'paged' ) ), $wp_query->max_num_pages );
