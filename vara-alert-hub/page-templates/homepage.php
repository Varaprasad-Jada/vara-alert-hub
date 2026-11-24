<?php
/**
 * Template Name: Homepage layout
 * Template Post Type: page
 *
 * Homepage with category tabs, featured cards and the latest updates.
 * Anything written in the page editor is shown first as an introduction.
 * Set the page as your homepage in Settings > Reading.
 *
 * @package Vara_Alert_Hub
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

get_header();

while ( have_posts() ) {
	the_post();
	if ( '' !== trim( get_the_content() ) ) {
		get_template_part( 'template-parts/page-article' );
	}
}

get_template_part( 'template-parts/home-sections', null, array( 'custom' => true ) );

get_footer();
