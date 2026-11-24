<?php
/**
 * A single post.
 *
 * @package Vara_Alert_Hub
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

get_header();

while ( have_posts() ) :
	the_post();

	$vara_alert_hub_id   = get_the_ID();
	$vara_alert_hub_cat  = vara_alert_hub_primary_category( $vara_alert_hub_id );
	$vara_alert_hub_note = vara_alert_hub_update_note( $vara_alert_hub_id );
	$vara_alert_hub_upd  = get_the_modified_date( 'Y-m-d' ) !== get_the_date( 'Y-m-d' );
	?>
	<article id="post-<?php the_ID(); ?>" <?php post_class( 'post-card' ); ?> style="<?php echo esc_attr( vara_alert_hub_item_style( $vara_alert_hub_id ) ); ?>">
		<?php if ( $vara_alert_hub_cat ) : ?>
			<a class="ah-badge" href="<?php echo esc_url( get_category_link( $vara_alert_hub_cat->term_id ) ); ?>"><?php echo esc_html( $vara_alert_hub_cat->name ); ?></a>
		<?php endif; ?>

		<h1 class="entry-title"><?php the_title(); ?></h1>

		<div class="entry-meta">
			<span>
				<?php echo vara_alert_hub_icon( 'calendar' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
				<?php
				/* translators: %s: date */
				printf( esc_html__( 'Published %s', 'vara-alert-hub' ), esc_html( get_the_date() ) );
				?>
			</span>
			<?php if ( $vara_alert_hub_upd ) : ?>
				<span>
					<?php echo vara_alert_hub_icon( 'refresh' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
					<?php
					/* translators: %s: date */
					printf( esc_html__( 'Updated %s', 'vara-alert-hub' ), esc_html( get_the_modified_date() ) );
					?>
				</span>
			<?php endif; ?>
			<?php if ( vara_alert_hub_get( 'show_author' ) ) : ?>
				<span>
					<?php echo vara_alert_hub_icon( 'user' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
					<?php echo esc_html( get_the_author() ); ?>
				</span>
			<?php endif; ?>
			<?php echo vara_alert_hub_last_date_chip( $vara_alert_hub_id ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
		</div>

		<?php if ( '' !== $vara_alert_hub_note ) : ?>
			<div class="ah-note">
				<strong>
					<?php
					/* translators: %s: date such as 22 Nov */
					printf( esc_html__( 'Updated %s:', 'vara-alert-hub' ), esc_html( get_the_modified_date( 'j M' ) ) );
					?>
				</strong>
				<?php echo esc_html( $vara_alert_hub_note ); ?>
			</div>
		<?php endif; ?>

		<?php if ( has_post_thumbnail() ) : ?>
			<figure class="entry-thumb"><?php the_post_thumbnail( 'large' ); ?></figure>
		<?php endif; ?>

		<div class="entry-content">
			<?php
			the_content();
			wp_link_pages( array(
				'before' => '<div class="page-links">' . esc_html__( 'Pages:', 'vara-alert-hub' ),
				'after'  => '</div>',
			) );
			?>
		</div>

		<footer class="entry-footer">
			<?php
			$vara_alert_hub_cats = get_the_category_list( ', ' );
			if ( $vara_alert_hub_cats ) {
				/* translators: %s: list of categories */
				printf( '<p>%s</p>', sprintf( esc_html__( 'Posted in %s', 'vara-alert-hub' ), wp_kses_post( $vara_alert_hub_cats ) ) );
			}
			$vara_alert_hub_tags = get_the_tag_list( '', ', ' );
			if ( $vara_alert_hub_tags ) {
				/* translators: %s: list of tags */
				printf( '<p>%s</p>', sprintf( esc_html__( 'Tags: %s', 'vara-alert-hub' ), wp_kses_post( $vara_alert_hub_tags ) ) );
			}
			?>
		</footer>
	</article>

	<?php
	the_post_navigation( array(
		'prev_text' => '<span class="screen-reader-text">' . esc_html__( 'Previous post:', 'vara-alert-hub' ) . '</span> %title',
		'next_text' => '<span class="screen-reader-text">' . esc_html__( 'Next post:', 'vara-alert-hub' ) . '</span> %title',
	) );

	// Related updates from the same category.
	if ( $vara_alert_hub_cat ) {
		$vara_alert_hub_related = new WP_Query( array(
			'category__in'        => array( $vara_alert_hub_cat->term_id ),
			'post__not_in'        => array( $vara_alert_hub_id ),
			'posts_per_page'      => 3,
			'orderby'             => 'modified',
			'order'               => 'DESC',
			'ignore_sticky_posts' => true,
			'no_found_rows'       => true,
		) );
		if ( $vara_alert_hub_related->have_posts() ) :
			?>
			<h2 class="ah-section-title"><?php esc_html_e( 'Related updates', 'vara-alert-hub' ); ?></h2>
			<div class="ah-list">
				<?php
				while ( $vara_alert_hub_related->have_posts() ) {
					$vara_alert_hub_related->the_post();
					get_template_part( 'template-parts/row', null, array( 'compact' => true ) );
				}
				?>
			</div>
			<?php
		endif;
		wp_reset_postdata();
	}

	if ( comments_open() || get_comments_number() ) {
		comments_template();
	}
endwhile;

get_footer();
