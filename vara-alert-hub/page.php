<?php
/**
 * A page.
 *
 * @package Vara_Alert_Hub
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

get_header();

while ( have_posts() ) :
	the_post();
	get_template_part( 'template-parts/page-article', null, array( 'title' => true ) );

	if ( comments_open() || get_comments_number() ) {
		comments_template();
	}
endwhile;

get_footer();
