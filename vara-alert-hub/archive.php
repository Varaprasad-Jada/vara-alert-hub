<?php
/**
 * Category, tag, author, date and other archives.
 *
 * @package Vara_Alert_Hub
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

get_header();

global $wp_query;

$vara_alert_hub_object = get_queried_object();
$vara_alert_hub_label  = __( 'Archive', 'vara-alert-hub' );
$vara_alert_hub_title  = wp_strip_all_tags( get_the_archive_title() );
$vara_alert_hub_desc   = '';
$vara_alert_hub_style  = '';
$vara_alert_hub_term   = 0;

if ( is_category() && $vara_alert_hub_object ) {
	$vara_alert_hub_label = __( 'Category', 'vara-alert-hub' );
	$vara_alert_hub_title = single_cat_title( '', false );
	$vara_alert_hub_desc  = term_description();
	$vara_alert_hub_style = vara_alert_hub_cat_style( $vara_alert_hub_object->term_id );
	$vara_alert_hub_term  = (int) $vara_alert_hub_object->term_id;
} elseif ( is_tag() ) {
	$vara_alert_hub_label = __( 'Tag', 'vara-alert-hub' );
	$vara_alert_hub_title = single_tag_title( '', false );
	$vara_alert_hub_desc  = term_description();
} elseif ( is_author() ) {
	$vara_alert_hub_label = __( 'Author', 'vara-alert-hub' );
	$vara_alert_hub_title = get_the_author_meta( 'display_name', get_queried_object_id() );
	$vara_alert_hub_desc  = wpautop( esc_html( get_the_author_meta( 'description', get_queried_object_id() ) ) );
} elseif ( is_day() ) {
	$vara_alert_hub_title = get_the_date();
} elseif ( is_month() ) {
	$vara_alert_hub_title = get_the_date( 'F Y' );
} elseif ( is_year() ) {
	$vara_alert_hub_title = get_the_date( 'Y' );
}

$vara_alert_hub_updated = vara_alert_hub_last_updated_label();
?>
<header class="ah-head" style="<?php echo esc_attr( $vara_alert_hub_style ); ?>">
	<p class="ah-head__label"><?php echo esc_html( $vara_alert_hub_label ); ?></p>
	<h1><?php echo esc_html( $vara_alert_hub_title ); ?></h1>
	<?php if ( $vara_alert_hub_desc && '' !== trim( wp_strip_all_tags( $vara_alert_hub_desc ) ) ) : ?>
		<div class="ah-head__desc"><?php echo wp_kses_post( $vara_alert_hub_desc ); ?></div>
	<?php endif; ?>
	<div class="ah-head__meta">
		<span>
			<?php
			/* translators: %s: number of updates */
			echo esc_html( sprintf( _n( '%s update', '%s updates', $wp_query->found_posts, 'vara-alert-hub' ), number_format_i18n( $wp_query->found_posts ) ) );
			?>
		</span>
		<?php if ( $vara_alert_hub_updated ) : ?>
			<span>
				<?php
				/* translators: %s: date */
				printf( esc_html__( 'Last updated %s', 'vara-alert-hub' ), esc_html( $vara_alert_hub_updated ) );
				?>
			</span>
		<?php endif; ?>
	</div>
</header>

<div class="ah-toolbar">
	<?php
	$vara_alert_hub_children = $vara_alert_hub_term ? get_terms( array(
		'taxonomy'   => 'category',
		'parent'     => $vara_alert_hub_term,
		'hide_empty' => true,
	) ) : array();

	if ( $vara_alert_hub_children && ! is_wp_error( $vara_alert_hub_children ) ) :
		?>
		<ul class="ah-chips ah-chips--scroll">
			<li><a class="ah-chip is-active" href="<?php echo esc_url( get_category_link( $vara_alert_hub_term ) ); ?>"><?php esc_html_e( 'All', 'vara-alert-hub' ); ?></a></li>
			<?php foreach ( $vara_alert_hub_children as $vara_alert_hub_child ) : ?>
				<li><a class="ah-chip" href="<?php echo esc_url( get_term_link( $vara_alert_hub_child ) ); ?>"><?php echo esc_html( $vara_alert_hub_child->name ); ?></a></li>
			<?php endforeach; ?>
		</ul>
		<?php
	elseif ( ! $vara_alert_hub_term ) :
		vara_alert_hub_category_chips();
	endif;
	?>
	<div class="ah-toolbar__tools">
		<?php
		vara_alert_hub_sort_links(
			array(
				'updated'   => __( 'Recently updated', 'vara-alert-hub' ),
				'published' => __( 'Newest published', 'vara-alert-hub' ),
			),
			'updated'
		);
		vara_alert_hub_view_links();
		?>
	</div>
</div>

<?php
get_template_part( 'template-parts/loop' );

get_footer();
