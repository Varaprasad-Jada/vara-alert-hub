<?php
/**
 * Template Name: No title, with sidebar
 * Template Post Type: page
 *
 * Normal page layout with the sidebar, without the page title.
 *
 * @package Vara_Alert_Hub
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

get_header();

while ( have_posts() ) {
	the_post();
	get_template_part( 'template-parts/page-article' );
}

get_footer();
