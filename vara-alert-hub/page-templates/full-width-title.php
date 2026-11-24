<?php
/**
 * Template Name: Full width with title
 * Template Post Type: page
 *
 * Full-width page with the title, no sidebar.
 *
 * @package Vara_Alert_Hub
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

get_header();

while ( have_posts() ) {
	the_post();
	get_template_part( 'template-parts/page-article', null, array( 'title' => true ) );
}

get_footer();
