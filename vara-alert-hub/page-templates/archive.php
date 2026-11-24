<?php
/**
 * Template Name: All updates (archive)
 * Template Post Type: page
 *
 * A page that lists every post, with category links and page numbers.
 *
 * @package Vara_Alert_Hub
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

get_header();

while ( have_posts() ) {
	the_post();
	?>
	<header class="ah-head">
		<h1><?php the_title(); ?></h1>
	</header>
	<?php
	if ( '' !== trim( get_the_content() ) ) {
		get_template_part( 'template-parts/page-article' );
	}
}

$vara_alert_hub_cats = get_categories( array(
	'orderby'    => 'count',
	'order'      => 'DESC',
	'hide_empty' => true,
) );
if ( $vara_alert_hub_cats ) :
	?>
	<ul class="ah-chips ah-chips--scroll" style="margin-bottom:14px;">
		<?php foreach ( $vara_alert_hub_cats as $vara_alert_hub_cat ) : ?>
			<li>
				<a class="ah-chip" href="<?php echo esc_url( get_category_link( $vara_alert_hub_cat->term_id ) ); ?>">
					<?php echo esc_html( $vara_alert_hub_cat->name ); ?>
					<span class="ah-chip-count"><?php echo esc_html( number_format_i18n( $vara_alert_hub_cat->count ) ); ?></span>
				</a>
			</li>
		<?php endforeach; ?>
	</ul>
	<?php
endif;

$vara_alert_hub_paged = vara_alert_hub_page_param();
$vara_alert_hub_all   = new WP_Query( array(
	'posts_per_page'      => max( 1, (int) get_option( 'posts_per_page' ) ),
	'paged'               => $vara_alert_hub_paged,
	'orderby'             => 'modified',
	'order'               => 'DESC',
	'ignore_sticky_posts' => true,
) );

if ( $vara_alert_hub_all->have_posts() ) :
	?>
	<div class="ah-list">
		<?php
		while ( $vara_alert_hub_all->have_posts() ) {
			$vara_alert_hub_all->the_post();
			get_template_part( 'template-parts/row', null, array( 'excerpt' => true ) );
		}
		?>
	</div>
	<?php
	vara_alert_hub_pagination( $vara_alert_hub_paged, $vara_alert_hub_all->max_num_pages, true );
else :
	get_template_part( 'template-parts/content-none', null, array( 'context' => 'home' ) );
endif;
wp_reset_postdata();

get_footer();
