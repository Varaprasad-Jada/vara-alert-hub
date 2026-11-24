<?php
/**
 * Search results.
 *
 * @package Vara_Alert_Hub
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

get_header();

global $wp_query;
$vara_alert_hub_query = get_search_query( false );
?>
<header class="ah-head">
	<p class="ah-head__label"><?php esc_html_e( 'Search', 'vara-alert-hub' ); ?></p>
	<h1>
		<?php
		if ( '' === trim( $vara_alert_hub_query ) ) {
			esc_html_e( 'Search the site', 'vara-alert-hub' );
		} else {
			/* translators: 1: number of results, 2: search words */
			echo esc_html( sprintf( _n( '%1$s result for "%2$s"', '%1$s results for "%2$s"', $wp_query->found_posts, 'vara-alert-hub' ), number_format_i18n( $wp_query->found_posts ), $vara_alert_hub_query ) );
		}
		?>
	</h1>
	<?php get_search_form(); ?>
</header>

<?php if ( have_posts() ) : ?>
	<div class="ah-toolbar">
		<?php vara_alert_hub_category_chips(); ?>
		<div class="ah-toolbar__tools">
			<?php
			vara_alert_hub_sort_links(
				array(
					'relevance' => __( 'Best match', 'vara-alert-hub' ),
					'updated'   => __( 'Recently updated', 'vara-alert-hub' ),
				),
				'relevance'
			);
			?>
		</div>
	</div>
<?php endif; ?>

<?php
get_template_part( 'template-parts/loop' );

get_footer();
