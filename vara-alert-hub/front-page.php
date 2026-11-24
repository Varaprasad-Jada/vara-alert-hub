<?php
/**
 * Front page: latest updates, or the static page chosen in Settings > Reading.
 *
 * @package Vara_Alert_Hub
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// A static front page uses its own page template, or the normal page layout.
if ( is_page() ) {
	$vara_alert_hub_template = get_page_template_slug();
	if ( $vara_alert_hub_template && locate_template( $vara_alert_hub_template ) ) {
		locate_template( $vara_alert_hub_template, true, false );
		return;
	}
	locate_template( 'page.php', true, false );
	return;
}

get_header();
get_template_part( 'template-parts/home-sections' );
get_footer();
