<?php
/**
 * The 404 page: a live update board.
 *
 * @package Vara_Alert_Hub
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

get_header();

$vara_alert_hub_path = isset( $_SERVER['REQUEST_URI'] ) ? wp_parse_url( esc_url_raw( wp_unslash( $_SERVER['REQUEST_URI'] ) ), PHP_URL_PATH ) : '';
$vara_alert_hub_path = $vara_alert_hub_path ? wp_html_excerpt( $vara_alert_hub_path, 60, '...' ) : '/';
?>
<div class="ah-404">
	<?php if ( vara_alert_hub_get( 'e404_board' ) ) : ?>
		<?php
		$vara_alert_hub_latest = get_posts( array(
			'numberposts'      => 3,
			'orderby'          => 'modified',
			'order'            => 'DESC',
			'suppress_filters' => true,
		) );
		?>
		<div class="ah-board" role="group" aria-label="<?php esc_attr_e( 'Latest updates', 'vara-alert-hub' ); ?>">
			<div class="ah-board__bar">
				<span class="ah-board__live"><?php esc_html_e( 'LIVE UPDATE BOARD', 'vara-alert-hub' ); ?></span>
				<span><?php echo esc_html( vara_alert_hub_board_text( get_bloginfo( 'name' ), 18 ) ); ?></span>
			</div>

			<?php foreach ( $vara_alert_hub_latest as $vara_alert_hub_item ) : ?>
				<?php
				$vara_alert_hub_is_new = vara_alert_hub_is_new( $vara_alert_hub_item->ID );
				?>
				<div class="ah-board__row<?php echo $vara_alert_hub_is_new ? ' is-new' : ''; ?>">
					<span class="ah-bseg ah-bseg--date" data-w="6"><?php echo esc_html( vara_alert_hub_board_text( get_the_modified_date( 'j M', $vara_alert_hub_item ), 6 ) ); ?></span>
					<span class="ah-bseg ah-bseg--tag" data-w="5"><?php echo $vara_alert_hub_is_new ? esc_html( vara_alert_hub_board_text( vara_alert_hub_get( 'new_label' ), 5 ) ) : ''; ?></span>
					<span class="ah-bseg ah-bseg--title" data-w="22"><?php echo esc_html( vara_alert_hub_board_text( get_the_title( $vara_alert_hub_item ), 22 ) ); ?></span>
				</div>
			<?php endforeach; ?>

			<div class="ah-board__row is-bad">
				<span class="ah-bseg ah-bseg--date" data-w="6"><?php echo esc_html( vara_alert_hub_board_text( __( 'Now', 'vara-alert-hub' ), 6 ) ); ?></span>
				<span class="ah-bseg ah-bseg--tag" data-w="5">404</span>
				<span class="ah-bseg ah-bseg--title" data-w="22"><?php echo esc_html( vara_alert_hub_board_text( __( 'This page: not found', 'vara-alert-hub' ), 22 ) ); ?></span>
			</div>

			<p class="ah-board__status">
				<?php
				/* translators: %s: address the visitor tried */
				printf( esc_html__( '> Looking up %s ... no match found', 'vara-alert-hub' ), esc_html( $vara_alert_hub_path ) );
				?>
			</p>
		</div>
	<?php else : ?>
		<p class="ah-404__code">404</p>
	<?php endif; ?>

	<h1 class="ah-404__title"><?php echo esc_html( vara_alert_hub_get( 'e404_title' ) ); ?></h1>
	<p class="ah-404__text"><?php echo esc_html( vara_alert_hub_get( 'e404_text' ) ); ?></p>

	<?php get_search_form(); ?>

	<p class="ah-404__links">
		<a href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php esc_html_e( 'Back to home', 'vara-alert-hub' ); ?></a>
		<button type="button" class="ah-go-back" hidden><?php esc_html_e( 'Go back', 'vara-alert-hub' ); ?></button>
	</p>
</div>
<?php
get_footer();
