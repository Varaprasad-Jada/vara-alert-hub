<?php
/**
 * Template Name: Full width, no title (landing page)
 * Template Post Type: page
 *
 * Full-width page without the title or a card around it. Good for landing pages built with the banner and link card patterns.
 *
 * @package Vara_Alert_Hub
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

get_header();

while ( have_posts() ) {
	the_post();
	get_template_part( 'template-parts/page-article', null, array( 'bare' => true ) );
}

get_footer();
